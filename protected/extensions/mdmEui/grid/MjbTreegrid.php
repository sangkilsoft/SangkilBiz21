<?php

/**
 * Description of MdmGrid
 *
 * @author mdmunir
 */
Yii::import('mdmEui.MdmEuiWidget');
Yii::import('mdmEui.grid.*');

class MjbTreegrid extends MdmEuiWidget {

    //put your code here
    public $columns = array();
    public $dataUrl;
    public $saveUrl;
    private $_columns = array();
    private $_frozenColumns = array();
    private $_fields;

    public function normalizeColumn() {
        $this->_fields = array();
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $f = $column;
                $column = array(
                    'class' => 'MdmDataColumn',
                    'field' => $f,
                );
            } else {
                if (!isset($column['class']))
                    $column['class'] = 'MdmDataColumn';
            }
            $column = Yii::createComponent($column, $this);

            if ($column->frozen)
                $this->_frozenColumns[] = $column->generateColumn();
            else
                $this->_columns[] = $column->generateColumn();

            if ($column instanceof MdmInputColumn) {
                if (isset($column->initEditor)) {
                    if (strpos($column->initEditor, 'js:') !== 0)
                        $column->initEditor = 'js:' . $column->initEditor;
                    $this->options['initRowEditor'][$column->field] = $column->initEditor;
                }
                if (isset($column->onKeydown)) {
                    $this->options['evenKeydowns'][$column->field] = $column->onKeydown;
                }
            }
        }
    }

    public function init() {
        parent::init();
//        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery.mdmegrid.js';
//        $path = Yii::app()->getAssetManager()->publish($path);
//        
//        $cs = Yii::app()->getClientScript();
//        $cs->registerScriptFile($path);
        //$cs->registerPackage('jquery.ui');

        if (isset($this->htmlOptions['id'])) {
            $id = $this->id = $this->htmlOptions['id'];
        } else {
            $id = $this->htmlOptions['id'] = $this->getId();
        }

        $this->htmlOptions['class'] = 'easyui-treegrid';

        if (isset($this->dataUrl))
            $this->options['url'] = CHtml::normalizeUrl($this->dataUrl);
        if (isset($this->saveUrl))
            $this->options['saveUrl'] = CHtml::normalizeUrl($this->saveUrl);

        $this->normalizeColumn();
        $this->options['columns'] = array($this->_columns);
        if (!empty($this->_frozenColumns))
            $this->options['frozenColumns'] = array($this->_frozenColumns);

        $this->renderTable();

        $options = CJavaScript::encode($this->options);
        $script = <<< SCRIPT
jQuery('#{$id}').treegrid($options);
SCRIPT;
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, $script);
    }

    protected function renderTable() {
        echo CHtml::tag('table', $this->htmlOptions, "\n") . "\n";
    }

}

?>
