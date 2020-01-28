<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


/**
 * Model resposável pelo vinculo entre as figuras de assentamento
 * e periodo aquisitivo
 * @author Vitor Rocha <vitor@dbseller.com.br>
 * @package Recursos Humanos
 */
class PeriodoAquisitivoAssentamento {

  /**
   * Código do PeriodoAquisitivoAssentamento
   * @var integer
   */
  private $iCodigo;

  /**
   * Referência do Assentamento
   * @var Assentamento
   */
  private $oAssentamento;

  /**
   * Referência do PeriodoAquisitivo
   * @var PeriodoAquisitivoFerias
   */ 
  private $oPeriodoAquisitivo;

  /**
   * Constante do arquivo de mensagens da classe
   */
  const MENSAGENS = "recursoshumanos.rh.PeriodoAquisitivoAssentamento.";

  public function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return;
    }

    $this->setCodigo($iCodigo);

    $oDaoRhFeriasAssenta = db_utils::getDao("rhferiasassenta");

    $sSqlRhFeriasAssenta = $oDaoRhFeriasAssenta->sql_query_file( $iCodigo );
    $rsRhFeriasAssenta   = $oDaoRhFeriasAssenta->sql_record( $sSqlRhFeriasAssenta );

    if (pg_num_rows($rsRhFeriasAssenta) == 0) {
      throw new BusinessException( _M(self::MENSAGENS . "registro_nao_encontrado") );
    }

    $oRhFeriasAssenta = db_utils::fieldsMemory($rsRhFeriasAssenta, 0);

    $this->setCodigo($oRhFeriasAssenta->rh131_sequencial);
    $this->setPeriodoAquisitivo( new PeriodoAquisitivoFerias($oRhFeriasAssenta->rh131_rhferias) );
    $this->setAssentamento( new Assentamento($oRhFeriasAssenta->rh131_assenta) );

  }

  /**
   * Salvar vinculacao de periodoaquisitivo com assentamento
   * @return boolean
   */
  public function salvar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M(self::MENSAGENS . 'nenhuma_transacao_banco'));
    }

    if ( empty($this->oAssentamento) ) {
      throw new BusinessException( _M(self::MENSAGENS . "assentamento_nao_informado") );
    }

    if ( empty($this->oPeriodoAquisitivo) ) {
      throw new BusinessException( _M(self::MENSAGENS . "periodo_aquisitivo_nao_informado") );
      
    }

    $oDaoRhFeriasAssenta = db_utils::getDao("rhferiasassenta");
    $oDaoRhFeriasAssenta->rh131_assenta    = $this->getAssentamento()->getCodigo();
    $oDaoRhFeriasAssenta->rh131_rhferias   = $this->getPeriodoAquisitivo()->getCodigo();

    /**
     * Inclusão do registro na tabela
     */ 
    if ( empty($this->iCodigo) ) {

      $oDaoRhFeriasAssenta->rh131_sequencial = null;  
      $oDaoRhFeriasAssenta->incluir(null);

      /**
       * Erro na inclusao da vinculacao
       */
      if ($oDaoRhFeriasAssenta->erro_status == "0") {

        $oMensagemErro = (object) array("sMensagem" => $oDaoRhFeriasAssenta->erro_banco);
        throw new DBException( _M( self::MENSAGENS . "erro_inclusao", $oMensagemErro) );         
      }        

      $this->setCodigo($oDaoRhFeriasAssenta->rh131_sequencial);

      return true;
    }

    /**
     * Alteração da vinculacao
     */
    $oDaoRhFeriasAssenta->rh131_sequencial = $this->getCodigo();
    $oDaoRhFeriasAssenta->alterar($this->getCodigo());

    /**
     * Erro na alteração da vinculção
     */ 
    if ($oDaoRhFeriasAssenta->erro_status == "0") {

      $oMensagemErro = (object) array("sMensagem" => $oDaoRhFeriasAssenta->erro_banco);
      throw new DBException( _M( self::MENSAGENS . "erro_alteracao", $oMensagemErro) );
    }

  }  

  /**
   * Exlcui a vinculação do periodo aquisitivo com assentamento
   * @return boolean
   */
  public function excluir() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M(self::MENSAGENS . 'nenhuma_transacao_banco'));
    }

    if ( empty($this->iCodigo) ) {
      throw new BusinessException( _M(self::MENSAGENS . "nenhum_registro_informado") );      
    }

    $oDaoRhFeriasAssenta = db_utils::getDao("rhferiasassenta");

    $oDaoRhFeriasAssenta->rh131_sequencial = $this->getCodigo();
    $oDaoRhFeriasAssenta->excluir($this->getCodigo());

    if ($oDaoRhFeriasAssenta->erro_status == "0") {

      $oMensagemErro = (object) array("sMensagem" => $oDaoRhFeriasAssenta->erro_banco);
      throw new DBException( _M(self::MENSAGENS . "erro_exclusao", $oMensagemErro) );
      
    }

    $this->setCodigo(null);    
    return true;
  }


  /**
   * Retorna o Código do PeriodoAquisitivoAssentamento.
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Seta o Código do PeriodoAquisitivoAssentamento.
   * @param integer $iCodigo 
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
    return $this;
  }

  /**
   * Retorna a Referência do Assentamento.
   * @return Assentamento
   */
  public function getAssentamento() {
    return $this->oAssentamento;
  }
  
  /**
   * Seta a Referência do Assentamento.
   * @param Assentamento $oAssentamento
   */
  public function setAssentamento(Assentamento $oAssentamento) {
    $this->oAssentamento = $oAssentamento;
    return $this;
  }

  /**
   * Retorna a Referência do PeriodoAquisitivoFerias.
   * @return PeriodoAquisitivo
   */
  public function getPeriodoAquisitivo() {
    return $this->oPeriodoAquisitivo;
  }
  
  /**
   * Seta a Referência do PeriodoAquisitivoFerias.
   * @param PeriodoAquisitivo $oPeriodoAquisitivo
   */
  public function setPeriodoAquisitivo(PeriodoAquisitivoFerias $oPeriodoAquisitivo) {
    $this->oPeriodoAquisitivo = $oPeriodoAquisitivo;
    return $this;
  }

  public static function getSaldoDiasDireito($iCodigoPeriodoAquisitivo, $iCodigoAssentamento = null, $iSequencialAssentamento = '') {

    $oDaoAssenta = db_utils::getDao('assenta');
    $sSqlAssenta = $oDaoAssenta->sql_saldoDiasDireito($iCodigoPeriodoAquisitivo, $iCodigoAssentamento, $iSequencialAssentamento);
    $rsAssenta   = db_query($sSqlAssenta);
    $oSaldo      = db_utils::fieldsMemory($rsAssenta, 0);
    
    return $oSaldo;
  }

  /**
   * Valida o Saldo de dias de Direito
   *
   * @param Integer $iCodigoPeriodoAquisitivo
   * @param Integer $iTipoAssentamento
   * @param Integer $iDias
   * @param Integer $iCodigoAssentamento
   * @return boolean.
   */
  public static function validaSaldoDiasDireito($iCodigoPeriodoAquisitivo, $iTipoAssentamento, $iDias, $iCodigoAssentamento, $iSequencialAssentamento = '') {

    $oDiasDireito         = self::getSaldoDiasDireito($iCodigoPeriodoAquisitivo, $iCodigoAssentamento, $iSequencialAssentamento);
    $iSaldoDiasDireito    = $oDiasDireito->saldodiasdireito;
    $iDiasDireito         = $oDiasDireito->rh109_diasdireito;
    $lSoma                = false;
    $oDaoTipoAssentamento = db_utils::getDao('tipoasse');
    $sSqlTipoAssentamento = $oDaoTipoAssentamento->sql_tipoAssentamento($iTipoAssentamento);
    $rsTipoAssentamento   = db_query($sSqlTipoAssentamento);

    $oTipoAssentamento    = db_utils::fieldsMemory($rsTipoAssentamento, 0);
    $lSoma                = $oTipoAssentamento->lsomadiminui == 't';

    if (!$lSoma && $iDias > $iSaldoDiasDireito  ) {
      throw new BusinessException( _M(self::MENSAGENS . "erro_diminui_validacao_dias") );
    }
    
    if ($lSoma && ( ($iSaldoDiasDireito - $iDias) < 0 || ($iSaldoDiasDireito + $iDias) > $iDiasDireito)) {
      throw new BusinessException( _M(self::MENSAGENS . "erro_soma_validacao_dias") );
    }

    return true;
  }

  /**
   * Retorna uma instância da classe para o registro com o assentamento passado
   *
   * @param Assentamento $oAssentamento
   * @param Integer $iMatriculaServidor
   * @return PeridoAquisitivoAssentmento
   */
  public static function getPeriodoAquisitivoAssentamento(Assentamento $oAssentamento, $iMatriculaServidor = null) {

    if ( empty($oAssentamento) ) {
      throw new BusinessException( _M(self::MENSAGENS . "assentamento_nao_informado") );
    }

    $sWhere = '';
    if ($iMatriculaServidor) {
      $sWhere = " and rhferias.rh109_regist = $iMatriculaServidor";
    }

    $oDaoRhFeriasAssenta = db_utils::getDao('rhferiasassenta');
    $sSql = $oDaoRhFeriasAssenta->sql_query( null,
                                                  "rh131_sequencial",
                                                  null,
                                                  "rh131_assenta = " . $oAssentamento->getCodigo() . $sWhere );
    $rsRhFeriasAssenta = $oDaoRhFeriasAssenta->sql_record($sSql);

    if (!$rsRhFeriasAssenta) {
      return null;
    }

    $oPeriodoAquisitivoAssenta = db_utils::fieldsMemory($rsRhFeriasAssenta, 0);
    return new PeriodoAquisitivoAssentamento($oPeriodoAquisitivoAssenta->rh131_sequencial);
  }


  /**
   * Verifica se existe um assentamento no mesmo periodo do mesmo tipo
   *
   * @param Integer $iCodigoServidor
   * @param Integer $iTipoAssentamento
   * @param String $sDataInicial
   * @param String $sDataFinal
   * @return Boolean
   */  
  public static function validaPeriodoGozo($iCodigoServidor, $iTipoAssentamento, $sDataInicial, $sDataFinal, $iSequencialAssentamento) {

    $oDaoAssenta = db_utils::getDao('assenta');
    $sSqlAssenta = $oDaoAssenta->sql_validaPeriodoGozoFerias($iCodigoServidor, $iTipoAssentamento, $sDataInicial, $sDataFinal, $iSequencialAssentamento );
    $rsAssenta   = db_query($sSqlAssenta);
    if(pg_num_rows($rsAssenta) > 0){
       throw new BusinessException(self::MENSAGENS . "periodo_cadastrado");
    }

    return true;
  }

}