# CustomResponse

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
```
