<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 08/09/17
 * Time: 09:02
 */

namespace ECidade\Tributario\Integracao\JuntaComercial\Processamento;


use ECidade\Tributario\Integracao\JuntaComercial\Dicionario;
use ECidade\Tributario\Integracao\JuntaComercial\Model\QSA\Factory as QSAFactory;

/**
 * Classe que processa as informação dos QSAs da empresa.
 * @package ECidade\Tributario\Integracao\JuntaComercial\Processamento
 */
class QSA extends Base
{
  private $cgms;
  private $qsas;

  /**
   * QSA construtor.
   * @param Dicionario $dicionario
   */
  public function __construct(Dicionario $dicionario)
  {
    parent::__construct($dicionario);
    $this->cgms = array();
    $this->qsas = array();
  }

  /**
   * Adicionamos os cgms que serão incluidos/alterados para os QSAs.
   * @param \CgmBase $cgm
   */
  public function addCgm(\CgmBase $cgm)
  {
    $indice = count($this->cgms);

    if ($cgm instanceof \CgmFisico) {
      $indice = $cgm->getCpf();
    }

    if ($cgm instanceof \CgmJuridico) {
      $indice = $cgm->getCnpj();
    }

    $this->cgms[$indice] = $cgm;
  }

  /**
   * Processamos as informação dos QSAs que vieram no arquivo
   * @throws \ParameterException
   */
  public function processar()
  {
    foreach ($this->grupo as $grupo) {

      $this->dicionario->selecionarGrupo($grupo);

      while ($this->dicionario->next()) {

        $indice = $this->dicionario->key();

        if ( !is_null($this->dicionario->getCampo("identificador")) ) {
          $indice = trim($this->dicionario->getCampo("identificador"));
        }

        $tipoRelacionamento = trim($this->getValor($indice, "tipo_relacionamento"));

        if (!empty($tipoRelacionamento)) {
          $indice .= "-" . $tipoRelacionamento;
        }

        if (empty($this->registro[$indice])) {
          $this->registro[$indice] = new \stdClass();
        }

        $this->registro[$indice]->tipo_relacionamento = $this->getValor($indice, "tipo_relacionamento");
        $this->registro[$indice]->valor_capital = $this->getValor($indice, "valor_capital");
        $this->registro[$indice]->cgm = $this->cgms[trim($this->dicionario->getCampo("identificador"))];
      }
    }
  }

  /**
   *
   * @return array
   * @throws \ParameterException
   */
  public function getQsa()
  {
    foreach ($this->registro as $identificador => $item) {

      $parametros = array();
      $parametros["valor_capital"] = $this->registro[$identificador]->valor_capital;
      $parametros["cgm"] = $this->registro[$identificador]->cgm;

      $this->qsas[$identificador] = QSAFactory::getInstance($this->registro[$identificador]->tipo_relacionamento, $parametros);
    }
    
    return $this->qsas;
  }
}
