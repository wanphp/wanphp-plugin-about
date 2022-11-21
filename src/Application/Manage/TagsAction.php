<?php

namespace Wanphp\Plugins\About\Application\Manager;


use Psr\Http\Message\ResponseInterface as Response;
use Wanphp\Components\Category\Domain\TagsInterface;
use Wanphp\Libray\Slim\Action;

/**
 * Class TagsAction
 * @title 分类管理
 * @route /admin/about/tags
 * @package App\Application\Actions\About
 */
class TagsAction extends Action
{
  private TagsInterface $tags;

  public function __construct(TagsInterface $tags)
  {
    $this->tags = $tags;
  }

  protected function action(): Response
  {
    if ($this->request->getHeaderLine("X-Requested-With") == "XMLHttpRequest") {
      $where = ['code' => 'about'];
      $params = $this->request->getQueryParams();
      if (!empty($params['search']['value'])) {
        $keyword = trim($params['search']['value']);
        $where['name[~]'] = $keyword;
      }

      $where['LIMIT'] = $this->getLimit();
      $order = $this->getOrder();
      if ($order) $where['ORDER'] = $order;

      $data = [
        "draw" => $params['draw'],
        "recordsTotal" => $this->tags->count('id'),
        "recordsFiltered" => $this->tags->count('id', $where),
        'data' => $this->tags->select('id,name,sortOrder', $where)
      ];
      return $this->respondWithData($data);
    } else {
      $data = [
        'title' => '分类管理'
      ];

      return $this->respondView('@about/tags.html', $data);
    }
  }
}
