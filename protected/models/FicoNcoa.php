<?php

/**
 * This is the model class for table "minidb.fico_ncoa".
 *
 * The followings are the available columns in table 'minidb.fico_ncoa':
 * @property integer $id_coa
 * @property string $cdfiacc
 * @property string $dscrp
 * @property string $dk
 * @property integer $strata
 * @property integer $update_by
 * @property string $update_date
 * @property integer $create_by
 * @property string $create_date
 * @property integer $parent_id_coa
 * @property double $begining_balance
 */
class FicoNcoa extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FicoNcoa the static model class
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
		return 'minidb.fico_ncoa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cdfiacc, dscrp, dk, strata, parent_id_coa', 'required'),
			array('strata, update_by, create_by, parent_id_coa', 'numerical', 'integerOnly'=>true),
			array('begining_balance', 'numerical'),
			array('cdfiacc', 'length', 'max'=>12),
			array('dk', 'length', 'max'=>2),
			array('update_date, create_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_coa, cdfiacc, dscrp, dk, strata, update_by, update_date, create_by, create_date, parent_id_coa, begining_balance', 'safe', 'on'=>'search'),
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
			'id_coa' => 'Id Coa',
			'cdfiacc' => 'Cdfiacc',
			'dscrp' => 'Dscrp',
			'dk' => 'Dk',
			'strata' => 'Strata',
			'update_by' => 'Update By',
			'update_date' => 'Update Date',
			'create_by' => 'Create By',
			'create_date' => 'Create Date',
			'parent_id_coa' => 'Parent Id Coa',
			'begining_balance' => 'Begining Balance',
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

		$criteria->compare('id_coa',$this->id_coa);
		$criteria->compare('cdfiacc',$this->cdfiacc,true);
		$criteria->compare('dscrp',$this->dscrp,true);
		$criteria->compare('dk',$this->dk,true);
		$criteria->compare('strata',$this->strata);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('parent_id_coa',$this->parent_id_coa);
		$criteria->compare('begining_balance',$this->begining_balance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}