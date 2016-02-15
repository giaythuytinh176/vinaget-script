# logboost-api-php

##Install composer
    curl -sS https://getcomposer.org/installer | php

##Update composer
    composer update 
    	--prefer-dist to remove .git
###For composer, add OpenID-Connect-PHP repository 
    {
        "type": "vcs",
        "url": "https://github.com/jumbojett/OpenID-Connect-PHP"
    }

##Install phpunit
    wget https://phar.phpunit.de/phpunit.phar
    php phpunit.phar --version
    phpunit --bootstrap autoload.php tests/

##Include LogboostAPI
    include("logboost-api-php/LogboostAPI.php") ;

##Define global variables
    Logboost_clientID = "Your Logboost client id"
    Logboost_clientSecret = "Your Logboost client secret" ;

##Create Logboost session
    $session = new LogboostSession([redirect_url]) ;

##Open Logboost session
    $session->openSession() ;

##After-login callback ( redirect_url )
    $session->handleSession() ;

##Session variables :
| Attribute   | Description                    |
| ------------|--------------------------------|
| id          | local session id               |
| sid         | php session id                 |
| authcode    | Openid authentication code     |
| username    | Session username               |
| ip          | User IP                        |
| date        | Session creation date          |
| validuntil  | Logboost plan end date         |
| plan        | Logboost subscribed plan id    |