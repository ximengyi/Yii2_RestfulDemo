<?php

namespace common\models;
use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "adminuser".
 *
 * @property int $id ID
 * @property string $username 用户名
 * @property string $realname 姓名
 * @property string $email 电子邮箱
 * @property int $status 状态
 * @property string $password_hash 密码
 * @property string $auth_key 授权key
 * @property string $password_reset_token 密码重置token
 * @property string $access_token 访问token
 * @property int $expire_at 过期时间
 * @property int $logged_at 登入时间
 * @property int $created_at 创建时间
 * @property int $updated_at 最后修改时间
 */
class Adminuser extends \yii\db\ActiveRecord implements IdentityInterface
{
  const STATUS_DELETED = 0;
  const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adminuser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'email', 'password_hash', 'auth_key'], 'required'],
            [['id', 'status', 'expire_at', 'logged_at', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['realname', 'email', 'password_hash', 'auth_key', 'password_reset_token', 'access_token'], 'string', 'max' => 255],
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
            'realname' => 'Realname',
            'email' => 'Email',
            'status' => 'Status',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'access_token' => 'Access Token',
            'expire_at' => 'Expire At',
            'logged_at' => 'Logged At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
      //  throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    //  return static::findOne(['access_token'=>$token]);
    return static::find()
     ->where(['access_token'=>$token,'status'=>self::STATUS_ACTIVE])
     ->andWhere(['>','expire_at',time()])
     ->one();
    }

    public function generateAccessToken()
    {
      $this->access_token = Yii::$app->security->generateRandomString();
      return  $this->access_token;
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
        /**
         * @inheritdoc
         */
        public function getId()
        {
            return $this->getPrimaryKey();
        }

        /**
         * @inheritdoc
         */
        public function getAuthKey()
        {
            return $this->auth_key;
        }

        /**
         * @inheritdoc
         */
        public function validateAuthKey($authKey)
        {
            return $this->getAuthKey() === $authKey;
        }


}
