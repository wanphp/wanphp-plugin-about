<?php

namespace Wanphp\Plugins\About\Application\Manage;

use Psr\Http\Message\ResponseInterface as Response;
use Wanphp\Components\Category\Domain\TagsInterface;
use Wanphp\Libray\Slim\Action;
use Wanphp\Plugins\About\Domain\AboutInterface;

/**
 * Class ListAction
 * @title 通知公告
 * @route /admin/about/list
 * @package App\Application\Actions\About
 */
class ListAction extends Action
{

  private AboutInterface $about;
  private TagsInterface $tags;

  public function __construct(AboutInterface $about, TagsInterface $tags)
  {
    $this->about = $about;
    $this->tags = $tags;
  }

  /**
   * @inheritDoc
   */
  protected function action(): Response
  {
    if ($this->request->getHeaderLine("X-Requested-With") == "XMLHttpRequest") {
      $where = [];
      $params = $this->request->getQueryParams();
      if (!empty($params['search']['value'])) {
        $keyword = trim($params['search']['value']);
        $where['title[~]'] = $keyword;
      }
      if (isset($params['tagId']) && $params['tagId'] > 0) {
        $where['tagId'] = intval($params['tagId']);
      }

      $where['LIMIT'] = $this->getLimit();
      $order = $this->getOrder();
      if ($order) $where['ORDER'] = $order;

      $data = [
        "draw" => $params['draw'],
        "recordsTotal" => $this->about->count('id'),
        "recordsFiltered" => $this->about->count('id', $where),
        'data' => $this->about->select('id,title,cover,ctime', $where)
      ];
      return $this->respondWithData($data);
    } else {
      $data = [
        'title' => '通知公告管理',
        'tags' => $this->tags->select('id,name', ['code' => 'about', 'ORDER' => ['sortOrder' => 'ASC']])
      ];

      return $this->respondView('@about/list.html', $data);
    }
  }
}
