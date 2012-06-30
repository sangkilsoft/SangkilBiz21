<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmComboColumn
 *
 * @author mdmunir
 */
class MdmAutocompleteColumn extends MdmInputColumn {

    //put your code here
    public $data;
    public $url;
    public $options = array();

    public function generateColumn() {
        $this->registerCoreScripts();
        $this->editorType = 'autocomplete';
        $this->editorOptions = $this->getOption();
        return parent::generateColumn();
    }

    protected function getOption() {
        $option = array();
        if (isset($this->url))
            $option['source'] = CHtml::normalizeUrl($this->url);
        else
            $option['source'] = $this->data;

        return $option;
    }

    protected function registerCoreScripts() {
        $cs = Yii::app()->getClientScript();
        $jsPath = $cs->getCoreScriptUrl() . '/jui/js/jquery-ui.min.js';
        $cssPath = $cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css';
        $cs->registerCssFile($cssPath);
        $cs->registerScriptFile($jsPath, CClientScript::POS_END);
    }

}

?>
