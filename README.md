# Validate Apple User Code with Laravel

![N|Solid](https://media.licdn.com/dms/image/C4D0BAQEvcWyGMS9Y4g/company-logo_200_200/0?e=2159024400&v=beta&t=vl8MQYN2beakdmB0GW7886EUSTwzV0oAKDm4V5MgPhU)

## 1. Installation

 ```sh
 composer require myshell/apple-laravel-authorization-code
```

Add Service Provider in config/app.php

```
'providers' => [
   ...
    AnimusCoop\AppleTokenAuth\AppleTokenAuthServiceProvider::class,
]
```
## 2. Configuration Setup

You will need to add an entry to the services configuration file so that after config files are cached for usage in production environment (Laravel command `artisan config:cache`) all config is still available.

To set up the required environment variables you can use the following artisan command which comes with this package.

```
php artisan socialite:apple
```

The command will prompt you the required values.You need to provide the following keys.
1. Team_ID
2. Key_ID
3. Client_ID
4. Key ( file name of p8 auth file, located inside storage/app/ ) Example: AuthKey_SAMPKEY.p8
5. Token Refresh Interval ( in days )

Client Secret will be automatically generated and added to the .env file by using the above command.

> The expiration time registered claim key, the value of which must not be greater than 15777000 (6 months in seconds) from the Current Unix Time on the server.
[Sign in with Apple](https://developer.apple.com/documentation/sign_in_with_apple/generate_and_validate_tokens) Client Secret expiration time cannot be greater than six months. Hence it is advisible to refresh the Client Secret atleast once in six months after creation. You can adjust the Token Refresh Interval. There is a scheduled task which comes along with this package which will ensure that the Client Token is refreshed automatically. Please do ensure that you have enabled [Task Scheduling](https://laravel.com/docs/master/scheduling#introduction)

To manually refresh the Client Secret, please run the following command
```
php artisan apple:help --refresh
```

## 3. Use

Controller.

```
use AnimusCoop\AppleTokenAuth\Classes\AppleAuth;
```

```
$data = [
   "code" => "" //code sended by your front end guy
];

$appleAuth = new AppleAuth($data);

// if you need only the jwt signed with your p8 key file

// $jwt = $appleAuth->getJwtSigned();

// Refresh Token and get user Data
$user = $appleAuth->getUserData();

```
Response getUserData()
```
[
  "authorization" => {
    "access_token": ""
    "token_type": "Bearer"
    "expires_in": 3600
    "refresh_token": ""
    "id_token": ""
  }
  "user" => {
    "iss": "https://appleid.apple.com"
    "aud": "your client id"
    "exp": 1605393470
    "iat": 1605307070
    "sub": ""
    "at_hash": ""
    "email": "isaias@animus.com.ar"
    "email_verified": "true"
    "auth_time": 1605307067
    "nonce_supported": true
  }
]

```
