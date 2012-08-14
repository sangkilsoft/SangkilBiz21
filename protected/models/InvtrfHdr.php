<?php

/**
 * This is the model class for table "minidb.invtrf_hdr".
 *
 * The followings are the available columns in table 'minidb.invtrf_hdr':
 * @property string $trf_num
 * @property string $cdunit
 * @property string $cdwhse
 * @property string $cdwhse2
 * @property string $dscrp
 * @property string $date_trf
 * @property string $status
 * @property string $gi_num
 * @property string $gr_num
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 */
class InvtrfHdr extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return InvtrfHdr the static model class
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
		return 'minidb.invtrf_hdr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('trf_num, cdunit, cdwhse, cdwhse2, dscrp, date_trf, gi_num, gr_num', 'required'),
			array('create_by, update_by', 'numerical', 'integerOnly'=>true),
			array('trf_num, cdunit, cdwhse, cdwhse2, gi_num, gr_num', 'length', 'max'=>13),
			array('dscrp', 'length', 'max'=>128),
			array('status, create_date, update_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('trf_num, cdunit, cdwhse, cdwhse2, dscrp, date_trf, status, gi_num, gr_num, create_by, create_date, update_by, update_date', 'safe', 'on'=>'search'),
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
			'trf_num' => 'Trf Num',
			'cdunit' => 'Unit Kirim',
			'cdwhse' => 'Gdg Asal',
			'cdwhse2' => 'Gdg Tujuan',
			'dscrp' => 'Deskripsi',
			'date_trf' => 'Tgl Transfer',
			'status' => 'Status',
			'gi_num' => 'Gi Num',
			'gr_num' => 'Gr Num',
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
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('trf_num',$this->trf_num,true);
		$criteria->compare('cdunit',$this->cdunit,true);
		$criteria->compare('cdwhse',$this->cdwhse,true);
		$criteria->compare('cdwhse2',$this->cdwhse2,true);
		$criteria->compare('dscrp',$this->dscrp,true);
		$criteria->compare('date_trf',$this->date_trf,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('gi_num',$this->gi_num,true);
		$criteria->compare('gr_num',$this->gr_num,true);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('update_date',$this->update_date,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}