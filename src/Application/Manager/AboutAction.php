<?php

namespace Wanphp\Plugins\About\Application\Manager;

use Psr\Http\Message\ResponseInterface as Response;
use Wanphp\Components\Category\Domain\TagsInterface;
use Wanphp\Libray\Slim\Action;
use Wanphp\Plugins\About\Domain\AboutInterface;

class AboutAction extends Action
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
    $id = intval($this->args['id'] ?? 0);
    if ($id > 0) $about = $this->about->get('*', ['id' => $this->args['id']]);
    $data = [
      'title' => '添加/修改通知、公告',
      'tags' => $this->tags->select('id,name', ['code' => 'about', 'ORDER' => ['sortOrder' => 'ASC']]),
      'about' => $about ?? [],
      'time' => time()
    ];

    return $this->respondView('@about/about.html', $data);
  }
}
