<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 11.08.13
 * Time: 15:39
 * To change this template use File | Settings | File Templates.
 */

class User_Calendar_Month
{
    const RENDER_SECONDARY = true;

    /**
     * @var array
     */
    private $_options = array();

    /**
     * @var DateTime
     */
    private $_dateTime;

    /**
     * @var User_Calendar_Month
     */
    private $_prev;

    /**
     * @var User_Calendar_Month
     */
    private $_next;

    private $_days = array(
        'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
    );

    public function __construct(DateTime $dateTime)
    {
        $dateTime->setTime(0, 0, 0);
        $dateTime->setDate($dateTime->format('Y'), $dateTime->format('m'), 1);
        $this->_dateTime = $dateTime;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->_dateTime->format('Ym');
    }

    public function prev()
    {
        if (!$this->_prev) {

            $dt = clone $this->_dateTime;
            $dt->add(DateInterval::createFromDateString('-1 month'));
            $this->_prev = new User_Calendar_Month($dt);
            $this->_prev->setOptions($this->getOptions());
        }
        return $this->_prev;
    }

    public function next()
    {
        if (!$this->_next) {

            $dt = clone $this->_dateTime;
            $dt->add(DateInterval::createFromDateString('+1 month'));
            $this->_next = new User_Calendar_Month($dt);
            $this->_next->setOptions($this->getOptions());
        }
        return $this->_next;
    }

    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {

            $options = $options->toArray();
        }
        $this->_options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param $dateTime
     * @param array|null $options
     * @return User_Calendar_Day
     */
    public function getDay($dateTime, $options = null)
    {
        $day = new User_Calendar_Day($dateTime);
        if ($options) {

            $day->setOptions($options);
        }
        return $day;
    }

    /**
     * @param $num
     * @return null
     */
    public function getDayByNum($num)
    {
        if (isset($this->_options['days_names']) && isset($this->_options['days_names'][$num])) {

            return $this->_options['days_names'][$num];
        }
        if (isset($this->_days[$num])) {

            return $this->_days[$num];
        }
        return null;
    }

    public function render()
    {
        $dayOptions = !empty($this->_options['day']) ? $this->_options['day'] : null;
        $result = '';
        //previous month link
        $pdt = clone $this->_dateTime;
        $pdt->add(DateInterval::createFromDateString('-1 month'));
        $result .= '<a href="/user/landlord/index/t/' . $pdt->getTimestamp() . '">&larr;</a>';
        if (!empty($this->_options['render_label'])) {

            $result .= '<strong>' . $this->_dateTime->format('F') . '</strong>';
        }
        //next month link
        $ndt = clone $this->_dateTime;
        $ndt->add(DateInterval::createFromDateString('+1 month'));
        $result .= '<a href="/user/landlord/index/t/' . $ndt->getTimestamp() . '">&rarr;</a>';

        $firstDay = (isset($this->_options['first_day']) && (int)$this->_options['first_day'] < 7)
            ? (int)$this->_options['first_day'] : 0;
        $day = $this->getDay($this->_dateTime, $dayOptions);
        $w = (int)$day->getDateTime()->format('w');
        $cldr = $this->_weekOpenTag();
        $dc = 0;
        $head = array();
        if ($w != $firstDay) {

            $c = $w > $firstDay ? ($w - $firstDay) : (7 - $firstDay + $w);
            for ($i = $c; $i > 0; --$i) {

                $dt = clone $this->_dateTime;
                $dt->add(DateInterval::createFromDateString('-' . $i . ' day'));
                $sDay = $this->getDay($dt, $dayOptions);
                $cldr .= $sDay->render();
                $head[] = $this->_headItem($this->getDayByNum((int)$sDay->getDateTime()->format('w')));
                ++$dc;
            }
        }
        $head[] = $this->_headItem($this->getDayByNum((int)$day->getDateTime()->format('w')));
        $cldr .= $day->render();
        ++$dc;
        if (!($dc % 7)) {

            $cldr .= $this->_weekCloseTag() . $this->_weekOpenTag();
        }
        $cd = 1;
        while (true) {

            $dt = clone $this->_dateTime;
            $dt->add(DateInterval::createFromDateString('+' . $cd . ' day'));
            $day = $this->getDay($dt);
            $cldr .= $day->render();
            if ($dc < 7) {

                $head[] = $this->_headItem($this->getDayByNum((int)$day->getDateTime()->format('w')));
            }
            ++$dc;
            ++$cd;
            if (!($dc % 7)) {

                if ($day->getDateTime()->format('m') != $this->_dateTime->format('m')) {

                    break;
                }
                $cldr .= $this->_weekCloseTag() . $this->_weekOpenTag();
            }
        }
        $cldr .= $this->_weekCloseTag();
        return $result
            . $this->_inMonthContainer($this->_inHead(implode('', $head)) . $this->_inBody($cldr));
    }

    private function _weekOpenTag()
    {
        $result = '';
        $tagName = isset($this->_options['render']['week']['tag'])
            ?
            $this->_options['render']['week']['tag']
            : array('tr');
        $attributes = isset($this->_options['render']['week']['attributes'])
            ? $this->_options['render']['week']['attributes'] : null;
        if (!is_array($tagName)) {

            $tagName = array($tagName);
        }
        foreach ($tagName as $k => $n) {

            $tag = $this->_getTag($n, (is_array($attributes) && isset($attributes[$k])) ? $attributes[$k] : $attributes);
            $result .= $tag[0];
        }
        return $result;
    }

    private function _weekCloseTag()
    {
        $result = '';
        $tagName = isset($this->_options['render']['week']['tag'])
            ?
            $this->_options['render']['week']['tag']
            : array('tr');
        if (!is_array($tagName)) {

            $tagName = array($tagName);
        }
        foreach (array_reverse($tagName) as $n) {

            $tag = $this->_getTag($n);
            if (isset($tag[1])) {

                $result .= $tag[1];
            }
        }

        return $result;
    }

    private function _inMonthContainer($content)
    {
        return $this->__wrap('month_container', $content, 'table');
    }

    private function _inHead($head)
    {
        return $this->__wrap('head', $head, array('tr', 'thead'));
    }

    private function _headItem($content)
    {
        return $this->__wrap('header_item', $content, 'th');
    }

    private function _inBody($content)
    {
        return '<tbody>' . $content . '</tbody>';
    }

    /**
     * @param string $key
     * @param string $content
     * @param string|array $default
     * @return string
     */
    private function __wrap($key, $content, $default = 'div')
    {
        $tagName = isset($this->_options['render'][$key]['tag'])
            ?
            $this->_options['render'][$key]['tag']
            : null;
        if (!is_array($tagName)) {

            $tagName = array_fill(0, is_array($default) ? count($default) : 1, $tagName);
        }
        $attributes = isset($this->_options['render'][$key]['attributes'])
            ? $this->_options['render'][$key]['attributes'] : array();
        foreach ($tagName as $k => $n) {

            if (!$n) {

                if (is_array($default)) {

                    $n = isset($default[$k]) ? $default[$k] : reset($default);
                } else {

                    $n = $default;
                }
            }
            $tag = $this->_getTag($n, isset($attributes[$k])
                ? $attributes[$k] : $attributes);
            $content = $tag[0] . $content . $tag[1];
        }
        return $content;

    }

    public function __toString()
    {
        return $this->render();
    }

    private function _getTag($name, $attributes = null)
    {
        $openTag = '<' . $name;
        $_attrs = array();
        if (!empty($attributes)) {

            foreach ($attributes as $name => $value) {

                if (!is_string($name)) {

                    continue;
                }
                $_attrs[] = $name . '="' . str_replace('"', '\"', (string)$value) . '"';
            }
        }
        $openTag .= ' ' . implode(' ', $_attrs) . '>';
        return array(
            $openTag,
            '</' . $name . '>'
        );
    }
}