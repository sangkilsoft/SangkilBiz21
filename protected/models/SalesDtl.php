<?php

/**
 * This is the model class for table "minidb.sales_dtl".
 *
 * The followings are the available columns in table 'minidb.sales_dtl':
 * @property string $sal_num
 * @property string $lnum
 * @property string $cditem
 * @property integer $lnitem
 * @property double $qty
 * @property double $uomprice
 * @property double $uomdiskon
 * @property string $create_date
 * @property integer $create_by
 * @property string $update_date
 * @property integer $update_by
 */
class SalesDtl extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return SalesDtl the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.sales_dtl';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sal_num, lnum, cditem, uomdiskon', 'required'),
            array('lnitem, create_by, update_by', 'numerical', 'integerOnly' => true),
            array('qty, uomprice, uomdiskon', 'numerical'),
            array('cditem', 'length', 'max' => 13),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sal_num, lnum, cditem, lnitem, qty, uomprice, uomdiskon, create_date, create_by, update_date, update_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'number' => array(self::BELONGS_TO, 'SalesHdr', 'sal_num'),
            'item' => array(self::BELONGS_TO, 'Mditem', 'cditem'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sal_num' => 'Sal Num',
            'lnum' => 'Lnum',
            'cditem' => 'Cditem',
            'lnitem' => 'Lnitem',
            'qty' => 'Qty',
            'uomprice' => 'Uomprice',
            'uomdiskon' => 'Uomdiskon',
            'create_date' => 'Create Date',
            'create_by' => 'Create By',
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

        $criteria->compare('sal_num', $this->sal_num, true);
        $criteria->compare('lnum', $this->lnum, true);
        $criteria->compare('cditem', $this->cditem, true);
        $criteria->compare('lnitem', $this->lnitem);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('uomprice', $this->uomprice);
        $criteria->compare('uomdiskon', $this->uomdiskon);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('create_by', $this->create_by);
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