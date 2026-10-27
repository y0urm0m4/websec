<?php

namespace src\services;

use src\exceptions\UnauthorizedException;

class CsrfService
{
    private const COOKIE_NAME = 'csrf_token';
    private const FORM_FIELD  = '_csrf_token';
    private const GET_FIELD   = '_csrf_token';

    public static function getToken(): string
    {
        if (!empty($_COOKIE[self::COOKIE_NAME])) {
            return $_COOKIE[self::COOKIE_NAME];
        }

        $token = bin2hex(random_bytes(32));
        setcookie(self::COOKIE_NAME, $token, [
            'expires'  => 0,
            'path'     => '/',
            'secure'   => false, 
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[self::COOKIE_NAME] = $token;

        return $token;
    }


    public static function getHiddenInput(): string
    {
        return '<input type="hidden" name="' . self::FORM_FIELD . '" value="'
            . htmlspecialchars(self::getToken()) . '">';
    }


    public static function getQueryParam(): string
    {
        return self::GET_FIELD . '=' . urlencode(self::getToken());
    }

    public static function validate(): void
    {
        $cookieToken = $_COOKIE[self::COOKIE_NAME] ?? '';

        $requestToken = $_POST[self::FORM_FIELD] ?? $_GET[self::GET_FIELD] ?? '';

        if (empty($cookieToken) || empty($requestToken)
            || !hash_equals($cookieToken, $requestToken)) {
            throw new UnauthorizedException('Недействительный CSRF-токен');
        }
    }
}