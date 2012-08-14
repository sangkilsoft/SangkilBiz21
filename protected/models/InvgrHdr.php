<?php

/**
 * This is the model class for table "minidb.invgr_hdr".
 *
 * The followings are the available columns in table 'minidb.invgr_hdr':
 * @property string $gr_num
 * @property string $cdunit
 * @property string $cdwhse
 * @property string $dscrp
 * @property integer $id_periode
 * @property string $date_gr
 * @property string $refnum
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 */
class InvgrHdr extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return InvgrHdr the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'minidb.invgr_hdr';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gr_num, cdunit, cdwhse, dscrp, id_periode, date_gr, refnum', 'required'),
            array('id_periode, create_by, update_by', 'numerical', 'integerOnly' => true),
            array('gr_num, cdunit, cdwhse', 'length', 'max' => 13),
            array('dscrp', 'length', 'max' => 128),
            array('refnum', 'length', 'max' => 32),
            array('create_date, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('gr_num, cdunit, cdwhse, dscrp, id_periode, date_gr, refnum, create_by, create_date, update_by, update_date', 'safe', 'on' => 'search'),
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
            'gr_num' => 'Gr Num',
            'cdunit' => 'Cdunit',
            'cdwhse' => 'Cdwhse',
            'dscrp' => 'Dscrp',
            'id_periode' => 'Id Periode',
            'date_gr' => 'Date Gr',
            'refnum' => 'Refnum',
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

        $criteria->compare('gr_num', $this->gr_num, true);
        $criteria->compare('cdunit', $this->cdunit, true);
        $criteria->compare('cdwhse', $this->cdwhse, true);
        $criteria->compare('dscrp', $this->dscrp, true);
        $criteria->compare('id_periode', $this->id_periode);
        $criteria->compare('date_gr', $this->date_gr, true);
        $criteria->compare('refnum', $this->refnum, true);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_by', $this->update_by);
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