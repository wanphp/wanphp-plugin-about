<?php
declare(strict_types=1);

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Server\MiddlewareInterface as Middleware;

return function (App $app, Middleware $PermissionMiddleware, Middleware $OAuthServerMiddleware) {
  $app->group('/api', function (Group $group) {
    //分类标签
    $group->get('/about/tags', \Wanphp\Plugins\About\Application\TagApi::class);
    //取内容
    $group->get('/about[/{id:[0-9]+}]', \Wanphp\Plugins\About\Application\AboutApi::class);
  });
  $app->group('/admin', function (Group $group) {
    //内容管理
    $group->map(['GET', 'PUT', 'POST', 'DELETE'], '/about[/{id:[0-9]+}]', \Wanphp\Plugins\About\Application\AboutApi::class);
  })->addMiddleware($PermissionMiddleware);
};


