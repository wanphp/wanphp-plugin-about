<?php
declare(strict_types=1);

namespace Wanphp\Plugins\About\Entities;


use JsonSerializable;
use Wanphp\Libray\Mysql\EntityTrait;

/**
 * Class AboutEntity
 * @package Wanphp\Plugins\About\Entities
 * @OA\Schema(schema="newAbout",title="添加帮助说明",required={"title"})
 */
class AboutEntity implements JsonSerializable
{
  use EntityTrait;

  /**
   * @DBType({"key":"PRI","type":"smallint NOT NULL AUTO_INCREMENT"})
   * @var integer|null
   */
  private ?int $id;
  /**
   * @DBType({"key":"MUL","type":"smallint(6) NULL DEFAULT NULL"})
   * @var integer
   * @OA\Property(description="标签ID")
   */
  private int $tagId;
  /**
   * @DBType({"type":"varchar(80) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="标题")
   */
  private string $title;
  /**
   * @DBType({"type":"varchar(200) NOT NULL DEFAULT ''"})
   * @OA\Property(description="封面")
   * @var string
   */
  private string $cover;
  /**
   * @DBType({"type":"varchar(300) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="简介")
   */
  private string $description;
  /**
   * @DBType({"type":"text NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="内容")
   */
  private string $content;
  /**
   * @DBType({"type":"char(10) NOT NULL DEFAULT '0'"})
   * @OA\Property(description="添加时间")
   * @var integer
   */
  private int $ctime;
}
/**
 * @OA\Schema(
 *   schema="About",
 *   title="帮助说明",
 *   type="object",
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/newAbout"),
 *       @OA\Schema(
 *           required={"id"},
 *           @OA\Property(property="id",format="int64", type="integer", description="ID")
 *       )
 *   }
 * )
 */
