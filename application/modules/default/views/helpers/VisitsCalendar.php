<?php

/**
 * @category Ext
 * @package  View_Helper
 */
class View_Helper_VisitsCalendar extends Zend_View_Helper_Abstract
{

    public function visitsCalendar(Doctrine_Collection $visitsCollection, $property)
    {
		$visitsIndex = array();
		foreach ($visitsCollection as $visit) {
			$visitsIndex[ $visit->availability ] = array('id' => $visit->id, 'visitors' => $visit->visitors);
		} unset($visit);

        $from = new DateTime(($visit = $visitsCollection->getFirst()) ? $visit->availability : null);
        $to = new DateTime(($visit = $visitsCollection->getLast()) ? $visit->availability : null);

        // go to the first day of month
        $from->modify('-'.($from->format('d') - 1).' days');
        $to->modify('-'.($to->format('d') - 1).' days');

        $hasAnyVisits = FALSE;
        $months = array();
        while ($from <= $to) {
            $month = $this->_createMonth(clone $from, $visitsIndex);
            if ($month['visits'])
                $hasAnyVisits = TRUE;
            $months[] = $month;
            $from->modify('+1 month');
        }

        return $this->view->partial('_partials/helpers/visits-calendar.phtml', array(
			'property' => $property,
            'months' => $months,
            'hasAnyVisits' => $hasAnyVisits,
        ));
    }

    protected function _createMonth(DateTime $monthStartDate, $visitsIndex)
    {
        $today = date('Y-m-d');
        $output = array(
            'year' => $monthStartDate->format('Y'),
            'index' => $monthStartDate->format('n'),
            'days' => array(),
            'visits' => array(),
        );

        // go to monday
        $monthStartDate->modify('-'.($monthStartDate->format('N') - 1).' days');

        $iteration = 0;
        $step = 0;
        while (1) {
            $dateStr = $monthStartDate->format('Y-m-d');
            $monthIndex = $monthStartDate->format('n');
            $isCurMonth = $output['index'] == $monthIndex;
            $hasVisits = $isCurMonth && !empty($visitsIndex[$dateStr]);
            $day = array('day' => $monthStartDate->format('j'));
			if (!$isCurMonth) {
				$day['isOtherMonth'] = true;
			}
			if ($isCurMonth && $dateStr == $today) {
				$day['isToday'] = true;
			}
			if ($hasVisits) {
				$day['id'] = $visitsIndex[$dateStr]['id'];
				$day['hasVisits'] = true;
			}
			$output['days'][] = $day;
            if ($hasVisits) {
                $output['visits'][] = array('id' => $visitsIndex[$dateStr]['id']) + array_combine(
                    array('weekDay', 'day', 'month', 'year'),
                    explode('-', $monthStartDate->format('N-d-n-Y'))
                );
            }
            if ($step == 0 && $isCurMonth) {
                $step = 1;
            }
            if ($step == 1 && !$isCurMonth) {
                $step = 2;
            }
            if ($step == 2 && $monthStartDate->format('N') == 7) {
                break;
            }
            $monthStartDate->modify('+1 day');
            $iteration++;
            // to prevent infinite loop
            if ($iteration > 100) {
                throw new Exception('too more iterations');
            }
        }

        return $output;
    }
}
