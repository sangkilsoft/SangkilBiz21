<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of MdmMenu
 *
 * @author mdmunir
 */
class MdmMenu extends MdmEuiWidget {
    public $tagName='div';
    public $items=array();
    public $encodeLabel = true;

    public $targetSelector;

    /**
     * Calls {@link renderMenu} to render the menu.
     */
    public function run() {
        $this->items=$this->normalizeItems($this->items);
        if(count($this->items) == 0)
            return ;

        $id=$this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;

        if(empty($this->options)) {
            $this->htmlOptions['class']='easyui-menu';
        }else {
            $options=CJavaScript::encode($this->options);
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').menu($options);");
        }
        if(!isset ($this->htmlOptions['style']))
                $this->htmlOptions['style'] = 'width:120px';
        
        if(isset ($this->targetSelector)) {
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id.'contextmenu',
                    "$('{$this->targetSelector}').bind('contextmenu',function(e){
				$('#{$id}').menu('show', {
					left: e.pageX,
					top: e.pageY
				});
				return false;
			});");
        }
        $this->items = $this->normalizeItems($this->items);

        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        $this->renderMenu($this->items);
        echo CHtml::closeTag($this->tagName)."\n";
    }

    /**
     * Renders the menu items.
     * @param array $items menu items. Each menu item will be an array with at least two elements: 'label' and 'active'.
     * It may have three other optional elements: 'items', 'linkOptions' and 'itemOptions'.
     */
    protected function renderMenu($items) {
        foreach($items as $item) {
            if(empty ($item['items'])) {
                echo CHtml::tag('div', $item['htmlOptions'], $item['content'])."\n";
            }else {
                echo "<div>\n".CHtml::tag('span', $item['htmlOptions'], $item['content'])."\n";
                echo CHtml::openTag($this->tagName,$item['itemOptions'])."\n";
                $this->renderMenu($item['items']);
                echo CHtml::closeTag($this->tagName)."\n";
                echo "</div>\n";
            }
        }
    }


    protected function normalizeItems($items) {
        foreach ($items as $i => $item) {
            if(isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if(empty($item)) {
                $items[$i]['htmlOptions']['class'] = 'menu-sep';
                $items[$i]['content'] = '';
            }else {
                if(!isset($item['label']))
                    $item['label']='';
                if($this->encodeLabel)
                    $item['label']=CHtml::encode($item['label']);
                if(isset ($item['url'])) {
                    $items[$i]['content'] = CHtml::link($item['label'],$item['url']);
                }else {
                    $items[$i]['content'] = $item['label'];
                }
                if(!isset ($item['htmlOptions']))
                    $items[$i]['htmlOptions']=array();
                if(isset ($item['items'])){
                    $items[$i]['items'] = $this->normalizeItems($item['items']);
                    if(!isset ($item['itemOptions']['style']))
                        $items[$i]['itemOptions']['style'] = 'width:120px;';
                }
            }
        }
        return array_values($items);
    }
}
?>
