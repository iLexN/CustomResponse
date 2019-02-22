# CustomResponse

[![Coverage Status](https://coveralls.io/repos/github/iLexN/CustomResponse/badge.svg?branch=master)](https://coveralls.io/github/iLexN/CustomResponse?branch=master)
[![Build Status](https://travis-ci.org/iLexN/CustomResponse.svg?branch=master)](https://travis-ci.org/iLexN/CustomResponse)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/iLexN/CustomResponse/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/iLexN/CustomResponse/?branch=master)


Use any PSR 17 Factory to create PSR 7 Response.

Easy to create Json Response, Redirect Response.

## Install

Via Composer

``` bash
$ composer require ilexn/custom-response
```

## Usage

``` php
/** var ResponseFactoryInterface $psr17ResponseFactory **/ 
$factory = new \Ilex\CustomResponse\CustomResponseFactory($psr17ResponseFactory);
$response = $factory->createJsonResponseFromArray(['data']);

$url = 'http://www.example.com';
$response = $factory->createRedirectResponseFromString($url);

/** var UriInterface $uri **/
$response = $factory->createRedirectResponseFromUri($uri);

$response = $factory->createEmptyResponse();
```
