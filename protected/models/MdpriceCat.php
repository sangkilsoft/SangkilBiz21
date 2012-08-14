<?php

/**
 * This is the model class for table "minidb.mdprice_cat".
 *
 * The followings are the available columns in table 'minidb.mdprice_cat':
 * @property string $cdpcat
 * @property string $dscrp
 * @property string $update_date
 * @property string $create_date
 * @property integer $update_by
 * @property integer $create_by
 */
class MdpriceCat extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return MdpriceCat the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.mdprice_cat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdpcat, dscrp', 'required'),
            array('update_by, create_by', 'numerical', 'integerOnly' => true),
            array('cdpcat', 'length', 'max' => 13),
            array('dscrp', 'length', 'max' => 32),
            array('update_date, create_date', 'safe'),
            array('cdpcat', 'unique'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cdpcat, dscrp, update_date, create_date, update_by, create_by', 'safe', 'on' => 'search'),
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
            'cdpcat' => 'Cdpcat',
            'dscrp' => 'Dscrp',
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

        $criteria->compare('cdpcat', $this->cdpcat, true);
        $criteria->compare('dscrp', $this->dscrp, true);
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