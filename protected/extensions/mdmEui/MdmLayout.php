<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of MdmLayout
 *
 * @author mdmunir
 */
class MdmLayout extends MdmEuiWidget {
    protected $avaliableReg = array('north','south','east','west','center');
    public $regions=array();
    public $tagName='div';
    public $regionTag='div';

    private $_opened=true;
    private $_regions=array();

    public function init() {
        parent::init();

        $id=$this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;

        if(empty($this->options)) {
            $this->htmlOptions['class']='easyui-layout';
        }else {
            $options=CJavaScript::encode($this->options);
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').layout($options);");
        }
        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        $this->initRegion();
    }

    /**
     * Renders the content of the portlet.
     */
    public function run() {
        echo CHtml::closeTag($this->tagName);
        $this->_opened = FALSE;
    }

    protected function initRegion() {
        foreach ($this->regions as $region=>$value) {
            if(in_array($region, $this->avaliableReg)) {
                if(is_array($value)) {
                    $value['region'] = $region;
                    if(isset ($value['content'])) {
                        $content = $value['content'];
                        unset ($value['content']);
                    }else {
                        $content = '';
                    }
                    echo CHtml::tag($this->regionTag, $value, $content)."\n";
                }elseif (is_string($value)) {
                    echo CHtml::tag($this->regionTag, array('region'=>$region), $value)."\n";
                }
                $this->_regions[$region] = TRUE;
            }
        }
    }
    
    public function getOpened() {
        return $this->_opened;
    }

    public function beginRegion($region,$option=array(),$content=false) {
        if(!in_array($region, $this->avaliableReg)) {
            throw new CException(Yii::t('yii','Region {region} tidak didefinisikan.',
            array('{region}'=>$region)));
        }
        if(isset ($this->_regions[$region])) {
            throw new CException(Yii::t('yii','Duplikasi region {region}.',
            array('{region}'=>$region)));
        }
        if(!$this->_opened) {
            throw new CException(Yii::t('yii','Region tidak bisa digunakan diluar layout.'));
        }
        $this->_regions[$region] = TRUE;
        $option['region'] = $region;
        echo CHtml::openTag($this->regionTag,$option)."\n";
        if($content !== FALSE)
            echo $content."\n";
    }

    public function endRegion() {
        echo CHtml::closeTag($this->regionTag);
    }

    public function region($region,$option=array(),$content='') {
        if(!in_array($region, $this->avaliableReg)) {
            throw new CException(Yii::t('yii','Region {region} tidak didefinisikan.',
            array('{region}'=>$region)));
        }
        if(isset ($this->_regions[$region])) {
            throw new CException(Yii::t('yii','Duplikasi region {region}.',
            array('{region}'=>$region)));
        }
        if(!$this->_opened) {
            throw new CException(Yii::t('yii','Region tidak bisa digunakan diluar layout.'));
        }
        $this->_regions[$region] = TRUE;
        $option['region'] = $region;
        echo CHtml::tag($this->regionTag, $option,$content);
    }
}
?>
