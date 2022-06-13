<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2021/4/17
 * Time: 9:53
 */

namespace Wanphp\Plugins\About\Application;


use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Wanphp\Components\Category\Domain\TagsInterface;

class TagApi extends Api
{
  private TagsInterface $tag;

  public function __construct(TagsInterface $tag)
  {
    $this->tag = $tag;
  }

  /**
   * @return Response
   * @throws Exception
   * @OA\Get(
   *  path="/about/tags",
   *  tags={"Tag"},
   *  summary="获取标签列表，可选参数，查找关键词：keyword，开始位置：start默认为0，取的数量：length默认为10",
   *  operationId="GetTags",
   *  @OA\Response(
   *    response="200",
   *    description="请求成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(type="array",@OA\Items(
   *            @OA\Property(property="id",type="integer",description="标签ID"),
   *            @OA\Property(property="name",type="string",description="标签名"),
   *            @OA\Property(property="sortOrder",type="integer",description="显示排序")
   *        )
   *       )
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
  protected function action(): Response
  {
    $where = ['code' => 'about'];
    $params = $this->request->getQueryParams();
    if (isset($params['keyword']) && !empty($params['keyword'])) {
      $keyword = trim($params['keyword']);
      $where['name[~]'] = $keyword;
    }
    $where['LIMIT'] = $this->getLimit();
    $where['ORDER'] = ['sortOrder' => 'ASC'];
    return $this->respondWithData($this->tag->select('id,name,sortOrder', $where));
  }
}
