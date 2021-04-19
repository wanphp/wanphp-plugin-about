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
   *  path="/api/manage/about/tags",
   *  tags={"Tag"},
   *  summary="获取标签列表",
   *  operationId="GetTags",
   *  @OA\Response(
   *    response="200",
   *    description="请求成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(
   *         @OA\Property(property="datas",type="array",@OA\Items(
   *            @OA\Property(property="id",type="integer",description="标签ID"),
   *            @OA\Property(property="name",type="string",description="标签名"),
   *            @OA\Property(property="sortOrder",type="integer",description="显示排序")
   *        ))
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
    if (isset($params['page'])) {
      $cur_page = $params['page'] > 0 ? $params['page'] : 1;
      $pageSize = isset($params['size']) && $params['size'] > 0 ? $params['size'] : 10;
      $where['LIMIT'] = [($cur_page - 1) * $pageSize, $pageSize];
      if ($cur_page == 1) $total = $this->tag->count('id', $where);
    }
    $datas = $this->tag->select('id,name,sortOrder', $where);
    return $this->respondWithData(['aboutlist' => $datas, 'total' => $total ?? null]);
  }
}
