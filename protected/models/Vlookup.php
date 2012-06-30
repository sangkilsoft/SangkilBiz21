<?php

/**
 * This is the model class for table "minidb.sys_vlookup".
 *
 * The followings are the available columns in table 'minidb.sys_vlookup':
 * @property string $groupv
 * @property integer $cdlookup
 * @property string $dscrp
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 * @property integer $idlook
 */
class Vlookup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Vlookup the static model class
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
		return 'minidb.sys_vlookup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('groupv, cdlookup, dscrp', 'required'),
			array('create_by, update_by', 'numerical', 'integerOnly'=>true),
			array('groupv', 'length', 'max'=>64),
			array('dscrp', 'length', 'max'=>128),
			array('cdlookup', 'length', 'max'=>13),
			array('create_date, update_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('groupv, cdlookup, dscrp, create_by, create_date, update_by, update_date, idlook', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'groupv' => 'Groupv',
			'cdlookup' => 'Cdlookup',
			'dscrp' => 'Dscrp',
			'create_by' => 'Create By',
			'create_date' => 'Create Date',
			'update_by' => 'Update By',
			'update_date' => 'Update Date',
			'idlook' => 'Idlook',
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

		$criteria->compare('groupv',$this->groupv,true);
		$criteria->compare('cdlookup',$this->cdlookup);
		$criteria->compare('dscrp',$this->dscrp,true);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('idlook',$this->idlook);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}