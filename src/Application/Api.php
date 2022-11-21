<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2021/4/17
 * Time: 10:49
 */

namespace Wanphp\Plugins\About\Application;

/**
 * @OA\Info(
 *     description="帮助说明接口",
 *     version="1.1.0",
 *     title="帮助说明"
 * )
 * @OA\Tag(
 *     name="Tag",
 *     description="标签分组"
 * )
 * @OA\Tag(
 *     name="About",
 *     description="前端取用"
 * )
 * @OA\Tag(
 *     name="Manage About",
 *     description="后端管理"
 * )
 */

/**
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 * )
 * @OA\Schema(
 *   title="出错提示",
 *   schema="Error",
 *   type="object"
 * )
 * @OA\Schema(
 *   title="成功提示",
 *   schema="Success",
 *   type="object"
 * )
 */

use Wanphp\Libray\Slim\Action;

abstract class Api extends Action
{
}
