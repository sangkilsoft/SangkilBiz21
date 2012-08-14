<?php

/**
 * This is the model class for table "minidb.vhutang".
 *
 * The followings are the available columns in table 'minidb.vhutang':
 * @property string $id
 * @property string $txt
 * @property string $parentid
 * @property double $total_hutang
 * @property double $total_bayar
 * @property double $sisa
 * @property integer $status
 * @property string $date_post
 */
class Vhutang extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Vhutang the static model class
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
		return 'minidb.vhutang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('total_hutang, total_bayar, sisa', 'numerical'),
			array('id', 'length', 'max'=>13),
			array('txt, parentid, date_post', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, txt, parentid, total_hutang, total_bayar, sisa, status, date_post', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'txt' => 'Txt',
			'parentid' => 'Parentid',
			'total_hutang' => 'Total Hutang',
			'total_bayar' => 'Total Bayar',
			'sisa' => 'Sisa',
			'status' => 'Status',
			'date_post' => 'Date Post',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('txt',$this->txt,true);
		$criteria->compare('parentid',$this->parentid,true);
		$criteria->compare('total_hutang',$this->total_hutang);
		$criteria->compare('total_bayar',$this->total_bayar);
		$criteria->compare('sisa',$this->sisa);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_post',$this->date_post,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}