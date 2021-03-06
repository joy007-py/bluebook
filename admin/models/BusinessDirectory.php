<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "business_directory".
 *
 * @property int $id
 * @property string $business_name
 * @property string|null $small_banner_image
 * @property string|null $description
 * @property string|null $bannerimg
 * @property string|null $email
 * @property string|null $contactno
 * @property string|null $address1
 * @property string|null $city
 * @property string|null $otherinfo
 * @property s|ringnull $showbusinesstype
 * @property int|null $countryId
 * @property int|null $stateId
 * @property int|null $showpriority
 *
 * 
 */
class BusinessDirectory extends \yii\db\ActiveRecord 
{
    /**
     * @var $TYPE
     */
    //public const TYPE = 'Business Directory';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_directory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_name'], 'required'],
            [['description', 'small_banner_image','bannerimg','email','duration','otherinfo','contactno','textlink','weburl','keywords','ownercontact','storehours'], 'string'],
            [['countryId', 'stateId','contactno','duration','showpriority'], 'integer'],
			[['city','address1','showbusinesstype'],'safe'],
            [['business_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'small_banner_image' => 'Small Banner Image',
            'business_name' => 'Business Name',
            'description' => 'Description',
            'bannerimg' => 'Big Banner Image',
            'email' => 'Email',
            'contactno' => 'Contact Number',
            'countryId' => 'Country',
			'address1' => 'Address',
            'stateId' => 'State',
            'city' => 'City',
			'ownercontact' => 'Owner Contact',
            'otherinfo' => 'Other Info',
			'showpriority' => 'Show Priority',
			'showbusinesstype' => 'Show Business Type'
        ];
    }
}
