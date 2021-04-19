<?php
declare(strict_types=1);

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Server\MiddlewareInterface as Middleware;

return function (App $app, Middleware $PermissionMiddleware, Middleware $OAuthServerMiddleware) {
  //分类标签
  $app->get('/about/tags', \Wanphp\Plugins\About\Application\TagApi::class);

  $app->group('/api/manage', function (Group $group) {
    //分类
    $group->map(['GET', 'PUT', 'POST', 'DELETE'], '/about[/{id:[0-9]+}]', \Wanphp\Plugins\About\Application\Manage\AboutApi::class);
  })->addMiddleware($PermissionMiddleware)->addMiddleware($OAuthServerMiddleware);
};


