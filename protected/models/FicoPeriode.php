<?php

/**
 * This is the model class for table "minidb.fico_periode".
 *
 * The followings are the available columns in table 'minidb.fico_periode':
 * @property integer $id_periode
 * @property string $nmperiode
 * @property string $date_fr
 * @property string $date_to
 * @property integer $is_active
 * @property string $update_date
 * @property integer $update_by
 * @property string $create_date
 * @property integer $create_by
 * @property integer $tahun
 */
class FicoPeriode extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return FicoPeriode the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.fico_periode';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nmperiode, date_fr, date_to, is_active, tahun', 'required'),
            array('is_active, update_by, create_by', 'numerical', 'integerOnly' => true),
            array('update_date, create_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_periode, nmperiode, date_fr, date_to, is_active, update_date, update_by, create_date, create_by', 'safe', 'on' => 'search'),
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
            'id_periode' => 'Id Periode',
            'nmperiode' => 'Nmperiode',
            'date_fr' => 'Date Fr',
            'date_to' => 'Date To',
            'is_active' => 'Is Active',
            'update_date' => 'Update Date',
            'update_by' => 'Update By',
            'create_date' => 'Create Date',
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

        $criteria->compare('id_periode', $this->id_periode);
        $criteria->compare('nmperiode', $this->nmperiode, true);
        $criteria->compare('date_fr', $this->date_fr, true);
        $criteria->compare('date_to', $this->date_to, true);
        $criteria->compare('is_active', $this->is_active);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('create_date', $this->create_date, true);
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

            $nfr = explode('-', $this->date_fr);
            $nfr = $nfr[2] . '-' . $nfr[1] . '-' . $nfr[0];
            $this->date_fr = $nfr;

            $nto = explode('-', $this->date_to);
            $nto = $nto[2] . '-' . $nto[1] . '-' . $nto[0];
            $this->date_to = $nto;
        } else {
            $nfr = explode('-', $this->date_fr);
            $nfr = $nfr[2] . '-' . $nfr[1] . '-' . $nfr[0];
            $this->date_fr = $nfr;

            $nto = explode('-', $this->date_to);
            $nto = $nto[2] . '-' . $nto[1] . '-' . $nto[0];
            $this->date_to = $nto;

            $this->update_by = Yii::app()->user->Id;
            $this->update_date = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}