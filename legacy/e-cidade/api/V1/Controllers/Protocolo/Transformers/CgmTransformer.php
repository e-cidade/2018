<?php

namespace ECidade\Api\V1\Controllers\Protocolo\Transformers;

use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\ParameterBag;

class CgmTransformer extends TransformerAbstract
{

  private $fields;

  public function setFields(ParameterBag $fields) {
    $this->fields = $fields;
  }

  public function transform(\CgmBase $cgm)
  {
    $cgmRetorno = new \stdClass();

    $cgmRetorno->nome = $cgm->getNome();
    $cgmRetorno->id   = (int) $cgm->getCodigo();
    $cgmRetorno->sexo = '';
    $cgmRetorno->cpf  = '';
    $cgmRetorno->cnpj = '';
    $cgmRetorno->tipo = '';
    $cgmRetorno->email = $cgm->getEmail();
    $cgmRetorno->data_nascimento = '';

    if ($cgm->isFisico()) {
      $cgmRetorno->tipo = 'Física';
      $cgmRetorno->sexo = $cgm->getSexo();
      $cgmRetorno->cpf = $cgm->getCpf();
      $cgmRetorno->data_nascimento = $cgm->getDataNascimento();
    }

    if ($cgm->isJuridico()) {
      $cgmRetorno->tipo = 'Jurídica';
      $cgmRetorno->cnpj = $cgm->getCnpj();
    }

    return $this->filter((array) $cgmRetorno);
  }

  private function filter($cgm) {

    if (!count($this->fields)) {
      return $cgm;
    }

    $fields = $this->fields->all();

    foreach(array_keys($cgm) as $key) {
      if (!in_array($key, $fields)) {
        unset($cgm[$key]);
      }
    }

    return $cgm;
  }
}
