<?php

/**
 * This is the model class for table "minidb.invmv_stock".
 *
 * The followings are the available columns in table 'minidb.invmv_stock':
 * @property string $mvstock_id
 * @property string $cditem
 * @property integer $lnitem
 * @property string $cduom
 * @property string $cdwhse
 * @property string $date_mv
 * @property double $qtymv
 * @property double $qtynow
 * @property integer $update_by
 * @property string $create_date
 * @property string $update_date
 * @property integer $create_by
 */
class InvStock extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return InvStock the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.invmv_stock';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cditem, cduom, cdwhse, date_mv', 'required'),
            array('lnitem, update_by, create_by', 'numerical', 'integerOnly' => true),
            array('qtymv, qtynow', 'numerical'),
            array('cditem, cduom, cdwhse', 'length', 'max' => 13),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('mvstock_id, cditem, lnitem, cduom, cdwhse, date_mv, qtymv, qtynow, update_by, create_date, update_date, create_by', 'safe', 'on' => 'search'),
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
            'mvstock_id' => 'Mvstock',
            'cditem' => 'Cditem',
            'lnitem' => 'Lnitem',
            'cduom' => 'Cduom',
            'cdwhse' => 'Cdwhse',
            'date_mv' => 'Date Mv',
            'qtymv' => 'Qtymv',
            'qtynow' => 'Qtynow',
            'update_by' => 'Update By',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
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

        $criteria->compare('mvstock_id', $this->mvstock_id, true);
        $criteria->compare('cditem', $this->cditem, true);
        $criteria->compare('lnitem', $this->lnitem);
        $criteria->compare('cduom', $this->cduom, true);
        $criteria->compare('cdwhse', $this->cdwhse, true);
        $criteria->compare('date_mv', $this->date_mv, true);
        $criteria->compare('qtymv', $this->qtymv);
        $criteria->compare('qtynow', $this->qtynow);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_date', $this->update_date, true);
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