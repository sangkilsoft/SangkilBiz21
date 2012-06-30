<?php

/**
 * This is the model class for table "minidb.fico_coa".
 *
 * The followings are the available columns in table 'minidb.fico_coa':
 * @property string $cdfiacc
 * @property string $cdfigroup
 * @property string $dscrp
 * @property string $dk
 * @property integer $update_by
 * @property string $create_date
 * @property integer $create_by
 * @property string $update_date
 */
class FicoCoa extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return FicoCoa the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.fico_coa';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdfiacc, cdfigroup, dscrp, dk', 'required'),
            array('update_by, create_by', 'numerical', 'integerOnly' => true),
            array('cdfiacc', 'length', 'max' => 13),
            array('cdfigroup', 'length', 'max' => 64),
            array('dscrp', 'length', 'max' => 128),
            array('dk', 'length', 'max' => 2),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cdfiacc, cdfigroup, dscrp, dk, update_by, create_date, create_by, update_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'group'=>array(self::BELONGS_TO, 'FicoCoagroup', 'cdfigroup'),
            'glcoas'=>array(self::HAS_MANY, 'FicoGldtl', 'cdfiacc'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cdfiacc' => 'Cdfiacc',
            'cdfigroup' => 'Cdfigroup',
            'dscrp' => 'Dscrp',
            'dk' => 'Dk',
            'update_by' => 'Update By',
            'create_date' => 'Create Date',
            'create_by' => 'Create By',
            'update_date' => 'Update Date',
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

        $criteria->compare('cdfiacc', $this->cdfiacc, true);
        $criteria->compare('cdfigroup', $this->cdfigroup, true);
        $criteria->compare('dscrp', $this->dscrp, true);
        $criteria->compare('dk', $this->dk, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('update_date', $this->update_date, true);

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