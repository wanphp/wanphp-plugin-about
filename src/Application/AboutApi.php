<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2021/4/17
 * Time: 9:32
 */

namespace Wanphp\Plugins\About\Application;


use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Wanphp\Plugins\About\Domain\AboutInterface;

/**
 * Class AboutApi
 * @title 关于我们
 * @route /api/manage/about
 * @package Wanphp\Plugins\About\Application\Manage
 */
class AboutApi extends Api
{
  private AboutInterface $about;

  public function __construct(AboutInterface $about)
  {
    $this->about = $about;
  }

  /**
   * @return Response
   * @throws Exception
   * @OA\Get(
   *  path="/about/{id}",
   *  tags={"About"},
   *  summary="获取指定内容",
   *  operationId="GetAbout",
   *  @OA\Parameter(
   *    name="id",
   *    in="path",
   *    description="ID",
   *    required=true,
   *    @OA\Schema(format="int64",type="integer")
   *  ),
   *  @OA\Response(
   *    response="200",
   *    description="请求成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(
   *         @OA\Property(property="datas",ref="#/components/schemas/About")
   *       )
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *  path="/about",
   *  tags={"About"},
   *  summary="获取数据列表",
   *  operationId="GetAboutList",
   *  @OA\Response(
   *    response="200",
   *    description="请求成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(
   *         @OA\Property(property="datas",type="array",@OA\Items(ref="#/components/schemas/About"))
   *       )
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
  protected function action(): Response
  {
    $id = $this->args['id'] ?? 0;
    if ($id > 0) {
      return $this->respondWithData($this->about->get('*', ['id' => $id]));
    } else {
      $where = [];
      $params = $this->request->getQueryParams();
      if (isset($params['tagId']) && !empty($params['tagId'])) {
        $where['tagId'] = intval($params['tagId']);
      }
      if (isset($params['keyword']) && !empty($params['keyword'])) {
        $keyword = trim($params['keyword']);
        $where['title[~]'] = $keyword;
      }
      if (isset($params['page'])) {
        $cur_page = $params['page'] > 0 ? $params['page'] : 1;
        $pageSize = isset($params['size']) && $params['size'] > 0 ? $params['size'] : 10;
        $where['LIMIT'] = [($cur_page - 1) * $pageSize, $pageSize];
        if ($cur_page == 1) $total = $this->about->count('id', $where);
      } else {
        $where['LIMIT'] = 10;
      }
      $where['ORDER'] = ['sortOrder' => 'ASC'];
      $datas = $this->about->select('*', $where);
      return $this->respondWithData(['abouts' => $datas, 'total' => $total ?? null]);
    }
  }
}
