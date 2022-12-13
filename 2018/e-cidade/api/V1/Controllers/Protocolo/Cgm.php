<?php

namespace ECidade\Api\V1\Controllers\Protocolo;

use ECidade\Api\V1\ResourceInterface;
use ECidade\Api\V1\Controllers\GenericController;
use ECidade\Api\V1\Controllers\Protocolo\Transformers\CgmTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use League\Fractal;

class Cgm extends GenericController implements ResourceInterface {

  public function get($id)
  {
    $cgm = \CgmRepository::getByCodigo($id);

    if (empty($cgm)) {
      throw new NotFoundHttpException("Cgm não encontrado.");
    }

    $transformer = new CgmTransformer();
    $transformer->setFields($this->fields);

    $result = new Fractal\Resource\Item($cgm, $transformer);

    return $this->format($result);
  }

  public function getAll()
  {
    
    $where = array(
      " length(z01_nome) >= 2 "
    );

    if ($this->filters->has('nome')) {
      $where[] = "z01_nome ilike '%{$this->filters->getAlnum('nome')}%'";
    }

    $offsetLimit  = null;
    if (!empty($this->page)) {
      
      $offSet = $this->page->getNumber() <= 1 ? 0 : ($this->page->getSize()) * ($this->page->getNumber() - 1); 
      $offsetLimit = " limit {$this->page->getSize()} offset {$offSet};";
    }
    if ($this->filters->has('cpf')) {
      $where[] = "z01_cgccpf = '{$this->filters->getAlnum('cpf')}'";
    }

    $cgmDao = new \cl_cgm;
    $sql    = $cgmDao->sql_query(null, 'z01_numcgm', 'z01_nome '.$offsetLimit, implode(" and ", $where));  
    
    $rs = \db_query($sql);

    if (!$rs) {
      throw new BadRequestHttpException("Erro ao buscar cgm.");
    }

    $dados = \db_utils::makeCollectionFromRecord($rs, function($data) {
      return \CgmRepository::getByCodigo($data->z01_numcgm);
    });

    $transformer = new CgmTransformer();
    $transformer->setFields($this->fields);

    $result = new Fractal\Resource\Collection($dados, $transformer);

    return $this->format($result);
  }
}
