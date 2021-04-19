<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2021/4/17
 * Time: 9:14
 */

namespace Wanphp\Plugins\About\Domain;


use Wanphp\Libray\Mysql\BaseInterface;

interface AboutInterface extends BaseInterface
{
  const TABLENAME = "about";
}
