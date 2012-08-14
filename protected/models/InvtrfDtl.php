<?php

/**
 * This is the model class for table "minidb.invtrf_dtl".
 *
 * The followings are the available columns in table 'minidb.invtrf_dtl':
 * @property string $trf_num
 * @property integer $lnum
 * @property string $cditem
 * @property string $cduom
 * @property integer $lnitem
 * @property double $qtytrf
 * @property double $uomprice
 * @property double $uomcost
 * @property string $update_date
 * @property string $create_date
 * @property integer $update_by
 * @property integer $create_by
 */
class InvtrfDtl extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return InvtrfDtl the static model class
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
		return 'minidb.invtrf_dtl';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('trf_num, lnum, cditem, cduom', 'required'),
			array('lnum, lnitem, update_by, create_by', 'numerical', 'integerOnly'=>true),
			array('qtytrf, uomprice, uomcost', 'numerical'),
			array('trf_num, cditem, cduom', 'length', 'max'=>13),
			array('update_date, create_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('trf_num, lnum, cditem, lnitem, qtytrf, uomprice, uomcost, update_date, create_date, update_by, create_by', 'safe', 'on'=>'search'),
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
                    'item'=>array(self::BELONGS_TO, 'Mditem', 'cditem'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'trf_num' => 'Trf Num',
			'lnum' => 'Lnum',
			'cditem' => 'Cd Item',
			'lnitem' => 'Lnitem',
			'cduom' => 'Cd Uom',
			'qtytrf' => 'Qtytrf',
			'uomprice' => 'Uomprice',
			'uomcost' => 'Uomcost',
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
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('trf_num',$this->trf_num,true);
		$criteria->compare('lnum',$this->lnum);
		$criteria->compare('cditem',$this->cditem,true);
		$criteria->compare('lnitem',$this->lnitem);
		$criteria->compare('qtytrf',$this->qtytrf);
		$criteria->compare('uomprice',$this->uomprice);
		$criteria->compare('uomcost',$this->uomcost);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('create_by',$this->create_by);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}