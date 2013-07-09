<?php

class Grid_Array extends Ext_Grid
{
    protected $_gridName = 'array';

    protected $_columns = array(
        'column_1' => array(
            'header'    => 'Column #1'
        ),
        'column_2' => array(
            'header'    => 'Column #2'
        ),
        'column_3' => array(
            'header'    => 'Column #3'
        )
    );

    public function init()
    {
        $this->setAdapter(new Ext_Grid_Adapter_Array(array(
            array(
                'column_1' => 'value 1-1',
                'column_2' => 'value 1-2',
                'column_3' => 'value 1-3'
            ),
            array(
                'column_1' => 'value 2-1',
                'column_2' => 'value 2-2',
                'column_3' => 'value 2-3'
            ),
            array(
                'column_1' => 'value 3-1',
                'column_2' => 'value 3-2',
                'column_3' => 'value 3-3'
            ),
            array(
                'column_1' => 'value 4-1',
                'column_2' => 'value 4-2',
                'column_3' => 'value 4-3'
            ),
            array(
                'column_1' => 'value 5-1',
                'column_2' => 'value 5-2',
                'column_3' => 'value 5-3'
            )
        )));

        parent::init();
    }
}