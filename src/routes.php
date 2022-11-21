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
    $group->get('/about/tags', \Wanphp\Plugins\About\Application\Manage\TagsAction::class);
    $group->map(['PUT', 'POST', 'DELETE'], '/about/tags[/{id:[0-9]+}]', \Wanphp\Components\Category\Application\TagApi::class);
    $group->get('/about/article[/{id:[0-9]+}]', \Wanphp\Plugins\About\Application\Manage\AboutAction::class);
    $group->get('/about/list', \Wanphp\Plugins\About\Application\Manage\ListAction::class);
    //内容管理
    $group->map(['GET', 'PUT', 'POST', 'DELETE'], '/about[/{id:[0-9]+}]', \Wanphp\Plugins\About\Application\AboutApi::class);
  })->addMiddleware($PermissionMiddleware);
};


