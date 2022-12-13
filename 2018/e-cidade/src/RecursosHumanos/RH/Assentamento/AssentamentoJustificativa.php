<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\RH\Assentamento;

class AssentamentoJustificativa extends \Assentamento {

  /**
   * Código da natureza do assentamento
   *@var integer 
   */
  const CODIGO_NATUREZA = 5;

  /**
   * Código do período da justificativa
   * @var integer
   */
  private $periodo1 = null;

  /**
   * Código do período da justificativa
   * @var integer
   */
  private $periodo2 = null;

  /**
   * Código do período da justificativa
   * @var integer
   */
  private $periodo3 = null;

  /**
   * @var bool
   */
  private $lTotal = false;

  /**
   * AssentamentoRRA constructor.
   * Instância um assentamento de RRA
   *
   * @param int|null $iCodigo Código do assentamento
   * @throws \BusinessException
   * @throws \DBException
   */
  function __construct($iCodigo = null) {
    
    if (empty($iCodigo)) {
      return;
    }
    
    parent::__construct($iCodigo);
    
    $oDaoAssentamentoJustificativa = new \cl_assentamentojustificativaperiodo();
    $sSqlJustificativa             = $oDaoAssentamentoJustificativa->sql_query_file(null, null, "*", null, " rh206_codigo = {$iCodigo}");
    $rsJustificativa               = db_query($sSqlJustificativa);
    if (!$rsJustificativa) {
      throw new \DBException("Erro ao consultar dados do Justificativa {$iCodigo}.");
    }
    
    if (pg_num_rows($rsJustificativa) == 0) {
      throw new \BusinessException("Assentamento de Justificativa {$iCodigo} não encontrada no sistema.");
    }

    $periodo1 = null;
    $periodo2 = null;
    $periodo3 = null;

    \db_utils::makeCollectionFromRecord($rsJustificativa, function($oRetorno) use (&$periodo1, &$periodo2, &$periodo3) {

      switch ($oRetorno->rh206_periodo) {
        
        case 1:
          $periodo1 = $oRetorno->rh206_periodo;
          break;

        case 2:
          $periodo2 = $oRetorno->rh206_periodo;
          break;

        case 3:
          $periodo3 = $oRetorno->rh206_periodo;
          break;
      }
    });

    $this->periodo1 = $periodo1;
    $this->periodo2 = $periodo2;
    $this->periodo3 = $periodo3;

    if(!is_null($this->periodo1) && !is_null($this->periodo2) && !is_null($this->periodo3)) {
      $this->lTotal = true;
    }
  }


  /**
   * Define o periodo 1
   * @param integer
   */
  public function setPeriodo1 ($periodo1) {
    $this->periodo1 = $periodo1;
  }
  
  /**
   * Retorna o periodo 1
   * @return integer
   */
  public function getPeriodo1 () {
    return $this->periodo1; 
  }

  /**
   * Define o período 2
   * @param integer
   */
  public function setPeriodo2 ($periodo2) {
    $this->periodo2 = $periodo2;
  }
  
  /**
   * Retorna o período 2
   * @return integer
   */
  public function getPeriodo2 () {
    return $this->periodo2; 
  }

  /**
   * Define o período 3
   * @param integer
   */
  public function setPeriodo3 ($periodo3) {
    $this->periodo3 = $periodo3;
  }
  
  /**
   * Retorna o período 3
   * @return integer
   */
  public function getPeriodo3 () {
    return $this->periodo3; 
  }

  /**
   * @return bool
   */
  public function isTotal() {
    return $this->lTotal;
  }

  /**
   * Valida se existe já alguma justificativa no período para o servidor
   * @param  \DBDate $dataJustificativa
   * @return Boolean
   */
  public function validarExistenciaJustificativaNoPeriodo(\DBDate $dataJustificativa) {

    $assentamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($this->getServidor(), 'S', $dataJustificativa, 5);

    if(empty($assentamentos)){
      return false;
    }

    foreach ($assentamentos as $assentamento) {
      
      if($assentamento instanceof AssentamentoJustificativa) {

        if(    ($this->periodo1 && $assentamento->getPeriodo1())
            || ($this->periodo2 && $assentamento->getPeriodo2())
            || ($this->periodo3 && $assentamento->getPeriodo3()) )
        {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Persiste na base de dados um assentamento de justificativa
   */
  public function persist() {

    parent::persist();

    $DAOassentamentojustificativaperiodo = new \cl_assentamentojustificativaperiodo();
    $DAOassentamentojustificativaperiodo->excluir($this->getCodigo());

    if($DAOassentamentojustificativaperiodo->erro_status == '0') {
      throw new \DBException($DAOassentamentojustificativaperiodo->erro_msg);
    }

    for ($periodo = 1; $periodo <= 3; $periodo++) {
      
      if($periodo == 1 && (bool)$this->periodo1 || $periodo == 2 && (bool)$this->periodo2 || $periodo == 3 && (bool)$this->periodo3) {
        
        $DAOassentamentojustificativaperiodo->rh206_codigo  = $this->getCodigo();
        $DAOassentamentojustificativaperiodo->rh206_periodo = $periodo;
        $DAOassentamentojustificativaperiodo->incluir(null);

        if($DAOassentamentojustificativaperiodo->erro_status == '0') {
          throw new \DBException($DAOassentamentojustificativaperiodo->erro_msg);
        }
      }
    }

    return $this;
  }
}
