<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2021/4/17
 * Time: 9:14
 */

namespace Wanphp\Plugins\About\Repositories;


use Wanphp\Libray\Mysql\BaseRepository;
use Wanphp\Libray\Mysql\Database;
use Wanphp\Plugins\About\Domain\AboutInterface;
use Wanphp\Plugins\About\Entities\AboutEntity;

class AboutRepository extends BaseRepository implements AboutInterface
{
  public function __construct(Database $database)
  {
    parent::__construct($database, self::TABLENAME, AboutEntity::class);
  }
}
