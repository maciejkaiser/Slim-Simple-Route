# MK Slim SimpleRoute

Simple Routing system for [Slim Framework][1] that loads all of routes automatically. Also provides a useful route-block-list.

## Requirements
This plugin assuming a similar project structure:
```
project
└───app
    ├───config
    ├───MK
    │   │   SimpleRoute.php
    │  
    └───routes
    │   ├───auth
    │       login.php
    │       register.php
    │   ├───users
    │       all.php
    │       single.php
    start.php
```
All routes are in specific folders i.e users routes are in users folder etc.

## Usage

Place this plugin to your app folder, then add autoload to your composer.json file:
```
"psr-4":{
  "MK\\": "app/MK"
}
```
Don't forget run composer dump-autoload.

You can add INC_ROOT in your start file.
```
define('INC_ROOT', dirname(__DIR__));
```

### MK/SimpleRoute
In start.php (or another "start" file in your project) place dependency injection container as below:
```
$app->container->singleton('simpleroute', function() {
	return new SimpleRoute("../app/routes", []);
});
```
As a parameter of SimpleRoute object you need to specify main route folder (as string), and optionally routes to block (as an array). If you want to block only specific routes, for example users, just add:
```
return new SimpleRoute("../app/routes", ['users']);
```
That means you block all user routes, but
```
return new SimpleRoute("../app/routes", ['users'] => ['single']);
```
means, that you block only single.php in user's routes. Nothing else will blocked (in this example all.php will be accessible). Make sure that you're not using urlFor() function to call out blocked routes!

Then just run:
```
$app->simpleroute->run();
```
Now you are able to forget about adding a route any time if you create one. Just remember to place all of your routes in specific subfolders in folder named "routes" (i.e app/routes).

[1]: http://www.slimframework.com/
