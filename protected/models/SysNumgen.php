<?php

/**
 * This is the model class for table "minidb.sys_numgen".
 *
 * The followings are the available columns in table 'minidb.sys_numgen':
 * @property string $cdnumgen
 * @property string $prefix
 * @property string $pattern
 * @property string $startnum
 * @property string $year
 * @property string $date
 * @property string $last_value
 * @property string $dscrp
 * @property integer $update_by
 * @property string $create_date
 * @property string $update_date
 * @property integer $create_by
 */
class SysNumgen extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return SysNumgen the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.sys_numgen';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdnumgen, pattern, startnum, year, dscrp', 'required'),
            array('update_by, create_by', 'numerical', 'integerOnly' => true),
            array('cdnumgen, startnum', 'length', 'max' => 13),
            array('prefix', 'length', 'max' => 8),
            array('year', 'length', 'max' => 2),
            array('dscrp', 'length', 'max' => 32),
            array('cdnumgen', 'unique'),
            array('last_value, create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cdnumgen, prefix, pattern, startnum, year, date, last_value, dscrp, update_by, create_date, update_date, create_by', 'safe', 'on' => 'search'),
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
            'cdnumgen' => 'Code',
            'prefix' => 'Prefix',
            'pattern' => 'Pattern',
            'startnum' => 'Start Number',
            'year' => 'Year',
            'date' => 'Date',
            'last_value' => 'Last Value',
            'dscrp' => 'Description',
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

        $criteria->compare('cdnumgen', $this->cdnumgen, true);
        $criteria->compare('prefix', $this->prefix, true);
        $criteria->compare('pattern', $this->pattern, true);
        $criteria->compare('startnum', $this->startnum, true);
        $criteria->compare('year', $this->year, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('last_value', $this->last_value, true);
        $criteria->compare('dscrp', $this->dscrp, true);
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
            $this->update_by = 0; //Yii::app()->user->Id;
            $this->update_date = new CDbExpression('NOW()');
        } else {
            $this->update_by = 0; //Yii::app()->user->Id;
            $this->update_date = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}