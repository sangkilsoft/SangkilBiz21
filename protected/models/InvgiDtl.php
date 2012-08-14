<?php

/**
 * This is the model class for table "minidb.invgi_dtl".
 *
 * The followings are the available columns in table 'minidb.invgi_dtl':
 * @property string $gi_num
 * @property integer $lnum
 * @property string $cditem
 * @property integer $lnitem
 * @property string $cduom
 * @property double $qty
 * @property double $uomcost
 * @property double $uomprice
 * @property string $update_date
 * @property string $create_date
 * @property integer $update_by
 * @property integer $create_by
 */
class InvgiDtl extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return InvgiDtl the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.invgi_dtl';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gi_num, lnum, cditem, cduom', 'required'),
            array('lnum, lnitem, update_by, create_by', 'numerical', 'integerOnly' => true),
            array('qty, uomcost, uomprice', 'numerical'),
            array('gi_num, cditem, cduom', 'length', 'max' => 13),
            array('update_date, create_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('gi_num, lnum, cditem, lnitem, cduom, qty, uomcost, uomprice, update_date, create_date, update_by, create_by', 'safe', 'on' => 'search'),
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
            'gi_num' => 'Gi Num',
            'lnum' => 'Lnum',
            'cditem' => 'Cditem',
            'lnitem' => 'Lnitem',
            'cduom' => 'Cduom',
            'qty' => 'Qty',
            'uomcost' => 'Uomcost',
            'uomprice' => 'Uomprice',
            'update_date' => 'Update Date',
            'create_date' => 'Create Date',
            'update_by' => 'Update By',
            'create_by' => 'Create By',
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

        $criteria->compare('gi_num', $this->gi_num, true);
        $criteria->compare('lnum', $this->lnum);
        $criteria->compare('cditem', $this->cditem, true);
        $criteria->compare('lnitem', $this->lnitem);
        $criteria->compare('cduom', $this->cduom, true);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('uomcost', $this->uomcost);
        $criteria->compare('uomprice', $this->uomprice);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('create_by', $this->create_by);

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