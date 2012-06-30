<?php

/**
 * This is the model class for table "minidb.fico_bayar".
 *
 * The followings are the available columns in table 'minidb.fico_bayar':
 * @property string $cdvend
 * @property string $purch_num
 * @property integer $lnum
 * @property string $cdfigl
 * @property double $jml_bayar
 * @property string $update_date
 * @property integer $update_by
 * @property integer $create_by
 * @property string $create_date
 */
class FicoBayar extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return FicoBayar the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.fico_bayar';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdvend, purch_num, lnum, cdfigl', 'required'),
            array('lnum, update_by, create_by', 'numerical', 'integerOnly' => true),
            array('jml_bayar', 'numerical'),
            array('cdvend, purch_num', 'length', 'max' => 13),
            array('update_date, create_date', 'safe'),
            array('cdfigl', 'exist', 'allowEmpty' => false, 'attributeName' => 'cdfigl', 'className' => 'FicoGl'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cdvend, purch_num, lnum, cdfigl, jml_bayar, update_date, update_by, create_by, create_date', 'safe', 'on' => 'search'),
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
            'lnum' => 'Lnum',
            'cdfigl' => 'Cdfigl',
            'jml_bayar' => 'Jml Bayar',
            'update_date' => 'Update Date',
            'update_by' => 'Update By',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
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
        $criteria->compare('lnum', $this->lnum);
        $criteria->compare('cdfigl', $this->cdfigl, true);
        $criteria->compare('jml_bayar', $this->jml_bayar);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_date', $this->create_date, true);

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