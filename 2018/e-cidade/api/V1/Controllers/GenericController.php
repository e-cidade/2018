<?php

namespace ECidade\Api\V1\Controllers;

use ECidade\Api\V1\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use League\Fractal;
use League\Fractal\Serializer\DataArraySerializer;

class GenericController
{
  /**
   * @var Request
   */
  protected $request;

  /**
   * @var ParameterBag
   */
  protected $fields;

  /**
   * @var ParameterBag
   */
  protected $filters;

  /**
   * @var \ECidade\Api\V1\Page
   */
  protected $page;

  /**
   * GenericController constructor.
   * @param \Symfony\Component\HttpFoundation\Request $request
   */
  public function __construct(Request $request)
  {
    $this->request = $request;

    $filters = $this->request->query->get("filter");
    $filters = empty($filters) ? array() : $filters;

    $this->filters = new ParameterBag($filters);

    $fields = $this->request->query->get("fields");
    $fields = empty($fields) ? array() : array_map('trim', explode(',', $fields));
    $this->fields = new ParameterBag($fields);
    
    $page = $this->request->query->get("page");
    if (!empty($page)) {
      
      $this->page = new Page();
      $this->page->setNumber($page["number"]);
      $this->page->setSize($page["size"]);
    }
    
  }

  protected function format($data)
  {
    $fractal = new Fractal\Manager();
    $fractal->setSerializer(new DataArraySerializer());
    
    $output = $fractal->createData($data)->toArray();

    return new JsonResponse(\DBString::utf8_encode_all($output));
  }
}
