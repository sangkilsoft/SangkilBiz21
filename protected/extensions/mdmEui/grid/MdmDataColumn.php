<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmGridColumn
 *
 * @author mdmunir
 */
class MdmDataColumn extends CComponent {

    //put your code here
    public $grid;
    public $field;
    public $title;
    public $sortable = false;
    public $formatter;
    public $frozen = false;
    public $input = false;
    public $selector;

    /**
     * @var array the HTML options for the header cell tag.
     */
    public $htmlOptions = array();

    public function __construct($grid) {
        $this->grid = $grid;
    }

    public function generateColumn() {
        $config = $this->htmlOptions;
        $config['field'] = $this->field;
        $config['title'] = isset($this->title) ? $this->title : $this->field;
        if (isset($this->formatter)) {
            if (strpos($this->formatter, 'js:') !== 0)
                $this->formatter = 'js:' . $this->formatter;
            $config['formatter'] = $this->formatter;
        }
        if ($this->sortable)
            $config['sortable'] = 'true';
        if ($this->input)
            $config['editor'] = 'readonly';
        if (isset($this->selector))
            $config['selector'] = $this->selector;
        return $config;
    }

}

?>
