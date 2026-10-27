<?php
namespace src\models;

use src\exceptions\InvalidArgumentException;

class  User extends ActiveRecordEntity
{
    protected $nickname;
    protected $email;
    protected $created_at;
    protected $is_confirmed;
    protected $role;
    protected $password_hash;
    protected $auth_token;

    protected static function getTableName(): string
    {
        return 'users';
    }
    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }
    public function getAuthToken()
    {
        return $this->auth_token;
    }
    public static function signUp(array $userData)
    {
        if (empty($userData['nickname'])){
            throw new InvalidArgumentException('Не передан логин');
        }
        if (empty($userData['email'])){
            throw new InvalidArgumentException('Не передан адрес эл. почты');
        }
        if (empty($userData['password'])){
            throw new InvalidArgumentException('Не передан пароль');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])){
            throw new InvalidArgumentException('Логин должен содержать  только символы латинского алфавита и цифры');
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException('Некорректный email');
        }
        if (mb_strlen($userData['password']) < 8 ){
            throw new InvalidArgumentException('Пароль должен состоять из не менее 8 символов');
        }
        if (static::findOneByColumn('nickname', $userData['nickname']) !== null){
            throw new InvalidArgumentException('Пользователь с таким логином уже существует');
        }
        if (static::findOneByColumn('email', $userData['email']) !== null){
            throw new InvalidArgumentException('Пользователь с таким email уже существует');
        }
        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->password_hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->is_confirmed = true;
        $user->role = 'user';

        $user->refreshAuthToken();
        $user->save();

        return $user;

    }
    public static function login(array $loginData): User
    {
        if (empty($loginData['nickname'])){
            throw new InvalidArgumentException('Не передан логин');
        }

        if (empty($loginData['password'])){
            throw new InvalidArgumentException('Не передан пароль');
        }

        $user = User::findOneByColumn('nickname', $loginData['nickname']);
        if ($user === null){
            throw new InvalidArgumentException('Неправильный логин или пароль');
        }

        if (!password_verify($loginData['password'], $user->getPasswordHash())){
            throw new InvalidArgumentException('Неправильный логин или пароль');
        }
        $user->refreshAuthToken();
        $user->save();
        return $user;
    }

    public function refreshAuthToken()
    {
        $this->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
}