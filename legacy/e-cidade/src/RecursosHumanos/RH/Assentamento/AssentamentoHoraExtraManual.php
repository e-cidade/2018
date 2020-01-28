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

class AssentamentoHoraExtraManual extends \Assentamento {

  /**
   * Código da natureza do assentamento
   *@var integer 
   */
  const CODIGO_NATUREZA = 8;

  /**
   * @var array
   */
  private $horasExtras = array();

  /**
   * AssentamentoHoraExtraManual constructor.
   * Instância um assentamento de HoraExtraManual
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
    
    $oDaoAssentamentoHoraExtraManual = new \cl_assentamentohoraextra();
    $sSqlHoraExtraManual             = $oDaoAssentamentoHoraExtraManual->sql_query_file(null, '*', null, "h17_assenta = {$iCodigo}");
    $rsHoraExtraManual               = db_query($sSqlHoraExtraManual);
    if (!$rsHoraExtraManual) {
      throw new \DBException("Erro ao consultar dados de hora extra manual do assentamento com código: ({$iCodigo}).\nContate o suporte");
    }
    
    if (pg_num_rows($rsHoraExtraManual) == 0) {
      throw new \BusinessException("Assentamento de hora extra manual ({$iCodigo}) não encontrado no sistema.");
    }

    $horasExtras = array();

    \db_utils::makeCollectionFromRecord($rsHoraExtraManual, function($oRetornoHorasExtrasManuais) use (&$horasExtras){
      $horasExtras[$oRetornoHorasExtrasManuais->h17_tipo] = $oRetornoHorasExtrasManuais->h17_hora;
    });

    $this->horasExtras = $horasExtras;
  }

  /**
   * Retorna uma instância de AssentamentoHoraExtraManual
   */
  public function create() {
    return new static;
  }

  /**
   * Retorna as horas extras do assentamento
   * @param  integer $tipo
   * @return array
   */
  public function getHorasExtras($tipo = null) {

    if(!empty($tipo) && isset($this->horasExtras[$tipo])) {
      return $this->horasExtras[$tipo];
    }

    return $this->horasExtras;
  }

  /**
   * Valida se existe já algum assentamento de hora extra manual de efetividade para a data informada
   * @param  \DBDate $dataHoraExtraManual
   * @return Boolean
   */
  public static function existeAssentamentoHoraExtraEfetividadeNaData(\DBDate $dataHoraExtraManual, \Servidor $servidor) {
    return AssentamentoHoraExtraManual::create()->setServidor($servidor)->validarExistenciaAssentamentoHoraExtraNaData($dataHoraExtraManual, false);
  }
  
  /**
   * Valida se existe já algum assentamento de hora extra manual de histórico funcional para a data informada
   * @param  \DBDate $dataHoraExtraManual
   * @return Boolean
   */
  public static function existeAssentamentoHoraExtraHistoricoFuncionalNaData(\DBDate $dataHoraExtraManual, \Servidor $servidor) {
    return AssentamentoHoraExtraManual::create()->setServidor($servidor)->validarExistenciaAssentamentoHoraExtraNaData($dataHoraExtraManual, true);
  }

  /**
   * Valida se existe já algum assentamento de hora extra manual para a data informada
   * @param  \DBDate $dataHoraExtraManual
   * @param  Boolean $lFuncional
   * @return Boolean
   */
  public function validarExistenciaAssentamentoHoraExtraNaData(\DBDate $dataHoraExtraManual, $lFuncional = null) {

    $assentamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($this->getServidor(), 'S', $dataHoraExtraManual, self::CODIGO_NATUREZA, $lFuncional);

    if(!empty($assentamentos)){
      return true;
    }

    return false;
  }

  /**
   * Persiste na base de dados um assentamento de hora extra manual
   */
  public function persist() {

    parent::persist();

    $DAOassentamentoHoraExtraManualperiodo = new \cl_assentamentohoraextraperiodo();
    $DAOassentamentoHoraExtraManualperiodo->excluir($this->getCodigo());

    if($DAOassentamentoHoraExtraManualperiodo->erro_status == '0') {
      throw new \DBException($DAOassentamentoHoraExtraManualperiodo->erro_msg);
    }

    for ($periodo = 1; $periodo <= 3; $periodo++) {
      
      if($periodo == 1 && (bool)$this->periodo1 || $periodo == 2 && (bool)$this->periodo2 || $periodo == 3 && (bool)$this->periodo3) {
        
        $DAOassentamentoHoraExtraManualperiodo->rh206_codigo  = $this->getCodigo();
        $DAOassentamentoHoraExtraManualperiodo->rh206_periodo = $periodo;
        $DAOassentamentoHoraExtraManualperiodo->incluir(null);

        if($DAOassentamentoHoraExtraManualperiodo->erro_status == '0') {
          throw new \DBException($DAOassentamentoHoraExtraManualperiodo->erro_msg);
        }
      }
    }

    return $this;
  }
}
