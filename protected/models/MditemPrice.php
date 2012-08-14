<?php

/**
 * This is the model class for table "minidb.mditem_price".
 *
 * The followings are the available columns in table 'minidb.mditem_price':
 * @property string $cditem
 * @property integer $lnitem
 * @property string $cduom
 * @property string $cdpcat
 * @property string $price_comp
 * @property double $val_price
 * @property double $prsn_price
 * @property integer $create_by
 * @property string $create_date
 * @property string $update_date
 * @property integer $update_by
 */
class MditemPrice extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return MditemPrice the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.mditem_price';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cditem, cduom, cdpcat, price_comp, prsn_price', 'required'),
            array('lnitem, create_by, update_by', 'numerical', 'integerOnly' => true),
            array('val_price, prsn_price', 'numerical'),
            array('cditem, cduom, cdpcat', 'length', 'max' => 13),
            array('create_date, update_date', 'safe'),
            array('cduom', 'exist', 'allowEmpty' => false, 'attributeName' => 'cduom', 'className' => 'MditemUom'),
            array('cditem', 'exist', 'allowEmpty' => false, 'attributeName' => 'cditem', 'className' => 'Mditem'),
            array('cdpcat', 'exist', 'allowEmpty' => false, 'attributeName' => 'cdpcat', 'className' => 'MdpriceCat'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cditem, lnitem, cduom, cdpcat, price_comp, val_price, prsn_price, create_by, create_date, update_date, update_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cditem' => 'Cditem',
            'lnitem' => 'Lnitem',
            'cduom' => 'Cduom',
            'cdpcat' => 'Cdpcat',
            'price_comp' => 'Price Comp',
            'val_price' => 'Val Price',
            'prsn_price' => 'Prsn Price',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'update_by' => 'Update By',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('cditem', $this->cditem, true);
        $criteria->compare('lnitem', $this->lnitem);
        $criteria->compare('cduom', $this->cduom, true);
        $criteria->compare('cdpcat', $this->cdpcat, true);
        $criteria->compare('price_comp', $this->price_comp, true);
        $criteria->compare('val_price', $this->val_price);
        $criteria->compare('prsn_price', $this->prsn_price);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('update_by', $this->update_by);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->create_by = Yii::app()->user->Id;
            $this->create_date = new CDbExpression('NOW()');
            $this->update_by = Yii::app()->user->Id;
            $this->update_date = new CDbExpression('NOW()');
        } else {
            $this->update_by = Yii::app()->user->Id;
            $this->update_date = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}