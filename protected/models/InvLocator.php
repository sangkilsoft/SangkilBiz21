<?php

/**
 * This is the model class for table "minidb.inv_locator".
 *
 * The followings are the available columns in table 'minidb.inv_locator':
 * @property string $cdloct
 * @property string $dscrp
 * @property integer $update_by
 * @property string $update_date
 * @property integer $create_by
 * @property string $create_date
 * @property string $cdwhse
 */
class InvLocator extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return InvLocator the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.inv_locator';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdloct, dscrp, cdwhse', 'required'),
            array('update_by, create_by', 'numerical', 'integerOnly' => true),
            array('cdloct, cdwhse', 'length', 'max' => 13),
            array('dscrp', 'length', 'max' => 128),
            array('update_date, create_date', 'safe'),
            array('cdwhse', 'exist', 'allowEmpty' => false, 'attributeName' => 'cdwhse', 'className' => 'InvWarehouse'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cdloct, dscrp, update_by, update_date, create_by, create_date, cdwhse', 'safe', 'on' => 'search'),
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
            'cdloct' => 'Locator Code',
            'dscrp' => 'Description',
            'update_by' => 'Update By',
            'update_date' => 'Update Date',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'cdwhse' => 'Warehouse Code',
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

        $criteria->compare('cdloct', $this->cdloct, true);
        $criteria->compare('dscrp', $this->dscrp, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('cdwhse', $this->cdwhse, true);

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