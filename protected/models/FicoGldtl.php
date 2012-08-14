<?php

/**
 * This is the model class for table "minidb.fico_gldtl".
 *
 * The followings are the available columns in table 'minidb.fico_gldtl':
 * @property string $idgldtl
 * @property string $cdfigl
 * @property integer $id_coa
 * @property double $debit
 * @property double $kredit
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 */
class FicoGldtl extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return FicoGldtl the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.fico_gldtl';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cdfigl, id_coa', 'required'),
            array('id_coa, create_by, update_by', 'numerical', 'integerOnly' => true),
            array('debit, kredit', 'numerical'),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('idgldtl, cdfigl, id_coa, debit, kredit, create_by, create_date, update_by, update_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'glhdr' => array(self::BELONGS_TO, 'FicoGl', 'cdfigl'),
            'coa' => array(self::BELONGS_TO, 'FicoNcoa', 'id_coa'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'idgldtl' => 'Idgldtl',
            'cdfigl' => 'Cdfigl',
            'id_coa' => 'Id Coa',
            'debit' => 'Debit',
            'kredit' => 'Kredit',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'update_by' => 'Update By',
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

        $criteria->compare('idgldtl', $this->idgldtl, true);
        $criteria->compare('cdfigl', $this->cdfigl, true);
        $criteria->compare('id_coa', $this->id_coa);
        $criteria->compare('debit', $this->debit);
        $criteria->compare('kredit', $this->kredit);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('update_date', $this->update_date, true);

        return new CActiveDataProvider($this, array(
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