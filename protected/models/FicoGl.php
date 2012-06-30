<?php

/**
 * This is the model class for table "minidb.fico_gl".
 *
 * The followings are the available columns in table 'minidb.fico_gl':
 * @property string $cdfigl
 * @property string $cdunit
 * @property integer $id_periode
 * @property string $gl_date
 * @property string $refnum
 * @property string $dscrp
 * @property string $update_date
 * @property integer $update_by
 * @property string $create_date
 * @property integer $create_by
 */
class FicoGl extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FicoGl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'minidb.fico_gl';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cdfigl, cdunit, id_periode, gl_date, refnum, dscrp', 'required'),
			array('id_periode, update_by, create_by', 'numerical', 'integerOnly'=>true),
			array('cdunit, refnum', 'length', 'max'=>13),
			array('dscrp', 'length', 'max'=>128),
			array('update_date, create_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cdfigl, cdunit, id_periode, gl_date, refnum, dscrp, update_date, update_by, create_date, create_by', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'gldtls'=>array(self::HAS_MANY, 'FicoGldtl', 'cdfigl'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cdfigl' => 'Cdfigl',
			'cdunit' => 'Cdunit',
			'id_periode' => 'Id Periode',
			'gl_date' => 'Gl Date',
			'refnum' => 'Refnum',
			'dscrp' => 'Dscrp',
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
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cdfigl',$this->cdfigl,true);
		$criteria->compare('cdunit',$this->cdunit,true);
		$criteria->compare('id_periode',$this->id_periode);
		$criteria->compare('gl_date',$this->gl_date,true);
		$criteria->compare('refnum',$this->refnum,true);
		$criteria->compare('dscrp',$this->dscrp,true);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('create_by',$this->create_by);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}