<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of MdmRegion
 *
 * @author mdmunir
 */
class MdmRegion extends CWidget {
    public $region;
    public $content=false;
    public $htmlOptions;

    public function __construct($owner) {
        if($owner instanceof MdmLayout && $owner->opened)
            parent::__construct($owner);
        else
            throw new CException(Yii::t('yii','{class} tidak bisa digunakan diluar layout.',
            array('{class}'=>get_class($this))));
    }

    public function init() {
        $this->getOwner()->renderOpenRegion($this->region,$this->htmlOptions,$this->content);
    }

    public function run() {
        $this->getOwner()->renderCloseRegion();
    }
}
?>
