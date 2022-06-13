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
 * @route /admin/about
 * @package Wanphp\Plugins\About\Application
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
   * @OA\Post(
   *  path="/admin/about",
   *  tags={"Manage About"},
   *  summary="新建帮助说明",
   *  operationId="addAbout",
   *  security={{"bearerAuth":{}}},
   *   @OA\RequestBody(
   *     description="数据",
   *     required=true,
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(ref="#/components/schemas/newAbout")
   *     )
   *   ),
   *  @OA\Response(
   *    response="201",
   *    description="创建成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(@OA\Property(property="id",type="integer"))
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Put(
   *  path="/admin/about/{id}",
   *  tags={"Manage About"},
   *  summary="修改信息",
   *  operationId="editAbout",
   *  security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="D",
   *     required=true,
   *     @OA\Schema(format="int64",type="integer")
   *   ),
   *   @OA\RequestBody(
   *     description="指定需要更新数据",
   *     required=true,
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(ref="#/components/schemas/newAbout")
   *     )
   *   ),
   *  @OA\Response(
   *    response="201",
   *    description="更新成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(@OA\Property(property="upNum",type="integer"))
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Delete(
   *  path="/admin/about/{id}",
   *  tags={"Manage About"},
   *  summary="删除信息",
   *  operationId="delAbout",
   *  security={{"bearerAuth":{}}},
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
   *       @OA\Schema(@OA\Property(property="delNum",type="integer"))
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *  path="/admin/about/{id}",
   *  tags={"Manage About"},
   *  summary="获取指定内容",
   *  operationId="GetAbout",
   *  security={{"bearerAuth":{}}},
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
   *       @OA\Schema(ref="#/components/schemas/About")
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *  path="/admin/about",
   *  tags={"Manage About"},
   *  summary="获取数据列表，可选参数，标签ID：tagId，查找关键词：keyword，开始位置：start默认为0，取的数量：length默认为10",
   *  operationId="GetAboutList",
   *  @OA\Response(response="200",description="请求成功",@OA\JsonContent(ref="#/components/schemas/Success")),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
  protected function action(): Response
  {
    switch ($this->request->getMethod()) {
      case 'POST':
        $data = $this->request->getParsedBody();
        $id = $this->about->get('id', ['tagId' => $data['tagId'], 'title' => $data['title']]);
        if (is_numeric($id) && $id > 0) {
          return $this->respondWithError('内容已添加过');
        } else {
          if (isset($data['ctime'])) $data['ctime'] = strtotime($data['ctime']);
          else $data['ctime'] = time();
          return $this->respondWithData(['id' => $this->about->insert($data)], 201);
        }
      case 'PUT':
        $data = $this->request->getParsedBody();
        $id = (int)$this->args['id'];
        $about_id = $this->about->get('id', ['id[!]' => $id, 'tagId' => $data['tagId'], 'title' => $data['title']]);
        if (is_numeric($about_id) && $about_id > 0) {
          return $this->respondWithError('内容已存在');
        }
        if ($id > 0) {
          if (isset($data['ctime'])) $data['ctime'] = strtotime($data['ctime']);
          return $this->respondWithData(['upNum' => $this->about->update($data, ['id' => $id])], 201);
        } else {
          return $this->respondWithError('缺少ID');
        }
      case 'DELETE':
        $id = (int)($this->args['id'] ?? 0);
        if ($id > 0) {
          return $this->respondWithData(['delNum' => $this->about->delete(['id' => $id])]);
        } else {
          return $this->respondWithError('缺少ID');
        }
      case 'GET';
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
          $where['LIMIT'] = $this->getLimit();
          $where['ORDER'] = ['ctime' => 'DESC'];

          return $this->respondWithData($this->about->select('id,title,ctime', $where));
        }
      default:
        return $this->respondWithError('禁止访问', 403);
    }
  }

  /**
   * @OA\Get(
   *  path="/api/about/{id}",
   *  tags={"About"},
   *  summary="获取指定内容详情",
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
   *       @OA\Schema(ref="#/components/schemas/About")
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *  path="/api/about",
   *  tags={"About"},
   *  summary="获取数据列表，可选参数，标签ID：tagId，查找关键词：keyword，开始位置：start默认为0，取的数量：length默认为10",
   *  operationId="GetAboutList",
   *  @OA\Response(response="200",description="请求成功",@OA\JsonContent(ref="#/components/schemas/Success")),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
}
