<?php 
/*
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
class InformacoesFinanceirasTipoAssentamento {

  private static $aInstance = array();

  private $sFormula;
  private $oRubrica;
  private $iTipoLancamento;
  private $oDataInicio;

  const TIPO_LANCAMENTO_VALOR      = 1;
  const TIPO_LANCAMENTO_QUANTIDADE = 2;
  const M                          = "recursoshumanos.rh.InformacoesFinanceirasTipoAssentamento.";

  private function  __construct( TipoAssentamento  $oTipoAssentamento ) {

    if (!$oTipoAssentamento->getSequencial() ) {
      throw new ParameterException( _M(self::M ."assentamento_sem_codigo_definido") );
    }

    $oDaoTipoassefinanceiro    = new cl_tipoassefinanceiro;
    $sWhereTipoassefinanceiro  = "     rh165_tipoasse = {$oTipoAssentamento->getSequencial()}";
    $sWhereTipoassefinanceiro .= " and rh165_instit   = ". db_getsession('DB_instit');
    $sSqlTipoassefinanceiro    = $oDaoTipoassefinanceiro->sql_query(null, "*", null, $sWhereTipoassefinanceiro);

    $rsTipoassefinanceiro = db_query($sSqlTipoassefinanceiro);


    if ( !$rsTipoassefinanceiro ) {
      throw new DBException(_M(self::M . "erro_ao_buscar_informacoes_financeiras"));
    }

    if( pg_num_rows($rsTipoassefinanceiro) == 0 ) {
      return;
    }

    $oDados                = db_utils::fieldsMemory($rsTipoassefinanceiro, 0);
    $this->oRubrica        = RubricaRepository::getInstanciaByCodigo($oDados->rh165_rubric);
    $this->sFormula        = $oDados->db148_formula;
    $this->iTipoLancamento = $oDados->rh165_tipolancamento;
    $this->oDataInicio     = (!empty($oDados->rh165_datainicio) ? new DBDate($oDados->rh165_datainicio) : null);
  }

  public static function getInstance(TipoAssentamento $oTipoAssentamento) {

    if ( !array_key_exists($oTipoAssentamento->getSequencial(), self::$aInstance ) ) {
      self::$aInstance[$oTipoAssentamento->getSequencial()] = new InformacoesFinanceirasTipoAssentamento($oTipoAssentamento);
    }

    return self::$aInstance[$oTipoAssentamento->getSequencial()];
  }

 
  public function getFormula() {
    return $this->sFormula;
  }

  public function getRubrica() {
    return $this->oRubrica;
  }

  public function getTipoLancamento() {
    return $this->iTipoLancamento;
  }

  public function getDataInicio() {
    return $this->oDataInicio;
  }
}
