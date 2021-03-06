<?php

namespace admin\models;

use Yii;
use yii\web\IdentityInterface;
 
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $user_pic
 * @property string $bio
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirm_password
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $usertype
 * @property integer $total_posts
 * @property string $access_token
 * @property string|null $address
 * @property string|null $city
 * @property int|null $countryId
 * @property int|null $stateId
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * user default image name
     */
    const DEFULAT_IMAGE_NAME = 'account.png';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','first_name', 'last_name', 'gender', 'password_hash', 'email'], 'required'],
            [['gender', 'status', 'usertype', 'address', 'city'], 'string'],
            [['created_at', 'updated_at',  'user_pic', 'auth_key' ], 'safe'],
            [['username', 'first_name', 'last_name', 'user_pic', 'password_hash', 'confirm_password', 'password_reset_token', 'email', 'access_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 40],
            [['bio'], 'string'],
			[['stateId', 'countryId'], 'integer'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            ['email', 'email'],
            [['password_reset_token'], 'unique'],
        ];
    }

        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'fullName'  => 'Full Name',
            'gender' => 'Gender',
            'user_pic' => 'Profile Picture',
            'bio' => 'Bio',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'confirm_password' => 'Confirm Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'usertype' => 'User Type',
            'access_token' => 'Access Token',		
            'countryId' => 'Country',
			'address' => 'Address',
            'stateId' => 'State',
            'city' => 'City',
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
            if ($this->isNewRecord) 
                $this->auth_key = \Yii::$app->security->generateRandomString();
            return true;
        }
        return false;
    }
 
    /**
     * register an user
     * @param string $username
     * @param string $password
     * @param string $email
     */
    public function registerUser( $username, $password, $email )
    {
        $this->username = $username;
        $this->setPassword($password);
        $this->email = $email;
        return $this->insert();
    }

    /**
     * change email address
     * @param string $username
     * @param string $email
     * @return bool
     */
    public static function changeAdminEmailAddress($username, $email)
    {
        if(empty($email) || !isset($email)) 
            return false;
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) 
            return false;
        $model = self::findByUsername($username);
        $model->email = $email;
        return $model->update();
    }

    /**
     * change user password
     * @param string $current_password
     * @param string $new_password
     * @param string $retyped_password
     * @return bool
     */
    public function changeUserPassword($current_password, $new_password, $retyped_password )
    {
        if($new_password !== $retyped_password) 
            return false;
        $user = self::findByUsername(Yii::$app->user->identity->username);
        if(!$user || !$user->validatePassword($current_password)) 
            return false;
        $user->password = Yii::$app->security->generatePasswordHash($new_password);
        return $user->update();
    }
	
	/**
     * return user full name
     */
    public function getFullName() 
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * return user first name only
     */
    public function getFname()
    {
        return $this->first_name;
    }
	/**
     * get user type
     */
    public function getUserType()
    {
        if ( $this->usertype == 'G' )
        {
            return '<span class="label label-success">General</span>'; 
        }
        else if ( $this->usertype == 'A' )
        {
            return '<span class="label label-danger">ADMIN</span>'; 
        }
        else
        {
            return '<span class="label label-warning">USER</span>'; 
        }
    }
	public function getNotification()
    {
        return $this->hasOne(Notification::className(), ['userId' => 'id']);
    }
	/**
     * get lat ong from address
     */
	public function getLatLong($address)
	{
		if(!empty($address))
		{
			//Formatted address
			//$formattedAddr = str_replace(' ','+',$address);
			//Send request and receive json data by address
			//$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
			//add urlencode to your address
$address = urlencode("technopark, Trivandrun, kerala,India");
$region = "IND";
$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false&region=$region");

echo $json;

$decoded = json_decode($json);

print_r($decoded); die;
		}
	}
}
