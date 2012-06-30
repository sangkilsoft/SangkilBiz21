<?php

/**
 * This is the model class for table "minidb.fico_hutang".
 *
 * The followings are the available columns in table 'minidb.fico_hutang':
 * @property string $cdvend
 * @property string $purch_num
 * @property double $total_hutang
 * @property double $total_bayar
 * @property string $date_post
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 * @property integer $status
 */
class FicoHutang extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return FicoHutang the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.fico_hutang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdvend, purch_num, date_post, status', 'required'),
            array('create_by, update_by, status', 'numerical', 'integerOnly' => true),
            array('total_hutang, total_bayar', 'numerical'),
            array('cdvend, purch_num', 'length', 'max' => 13),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cdvend, purch_num, total_hutang, total_bayar, date_post, create_by, create_date, update_by, update_date', 'safe', 'on' => 'search'),
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
            'cdvend' => 'Cdvend',
            'purch_num' => 'Purch Num',
            'total_hutang' => 'Total Hutang',
            'total_bayar' => 'Total Bayar',
            'date_post' => 'Date Post',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'update_by' => 'Update By',
            'update_date' => 'Update Date',
            'status' => 'Status',
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

        $criteria->compare('cdvend', $this->cdvend, true);
        $criteria->compare('purch_num', $this->purch_num, true);
        $criteria->compare('total_hutang', $this->total_hutang);
        $criteria->compare('total_bayar', $this->total_bayar);
        $criteria->compare('date_post', $this->date_post, true);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('status', $this->status, true);

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