<?php

/**
 * This is the model class for table "minidb.sales_hdr".
 *
 * The followings are the available columns in table 'minidb.sales_hdr':
 * @property string $sal_num
 * @property string $cdunit
 * @property string $cdwhse
 * @property string $cdvend
 * @property string $dscrp
 * @property string $date_sales
 * @property string $sal_type
 * @property integer $id_periode
 * @property string $create_date
 * @property integer $create_by
 * @property string $update_date
 * @property integer $update_by
 */
class SalesHdr extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return SalesHdr the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.sales_hdr';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sal_num, cdunit, cdwhse, cdvend, dscrp, date_sales, sal_type, id_periode', 'required'),
            array('id_periode, create_by, update_by', 'numerical', 'integerOnly' => true),
            array('cdunit, cdwhse, cdvend', 'length', 'max' => 13),
            array('dscrp', 'length', 'max' => 128),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sal_num, cdunit, cdwhse, cdvend, dscrp, date_sales, sal_type, id_periode, create_date, create_by, update_date, update_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'details' => array(self::HAS_MANY, 'SalesDtl', 'sal_num'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sal_num' => 'Sal Num',
            'cdunit' => 'Cdunit',
            'cdwhse' => 'Cdwhse',
            'cdvend' => 'Cdvend',
            'dscrp' => 'Dscrp',
            'date_sales' => 'Date Sales',
            'sal_type' => 'Sal Type',
            'id_periode' => 'Id Periode',
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
        $criteria->compare('cdunit', $this->cdunit, true);
        $criteria->compare('cdwhse', $this->cdwhse, true);
        $criteria->compare('cdvend', $this->cdvend, true);
        $criteria->compare('dscrp', $this->dscrp, true);
        $criteria->compare('date_sales', $this->date_sales, true);
        $criteria->compare('sal_type', $this->sal_type, true);
        $criteria->compare('id_periode', $this->id_periode);
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