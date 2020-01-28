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

class InformacoesExternasTipoAssentamento {

  private static $aInstance = array();
  private $oCompetencia;
  private $sSefip;
  private $oTipoAssentamento;
  private $oInstituicao;
  private $iSituacaoAfastamento;
  private $sCodigoRetorno;

  const MENSAGEM = 'recursoshumanos.rh.InformacoesFinanceirasTipoAssentamento.';

  public function __construct(TipoAssentamento $oTipoAssentamento, DBCompetencia $oCompetencia = null, Instituicao $oInstituicao = null) {

    if (!$oCompetencia) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    if (!$oInstituicao) {
      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    }

    $this->oCompetencia      = $oCompetencia;
    $this->oTipoAssentamento = $oTipoAssentamento;
    $this->oInstituicao      = $oInstituicao;

    $oDaoTipoAsseExterno    = new cl_tipoasseexterno();
    $sWhereTipoAsseExterno  = "    rh167_anousu   = {$oCompetencia->getAno()} ";
    $sWhereTipoAsseExterno .= "and rh167_mesusu   = {$oCompetencia->getMes()} ";
    $sWhereTipoAsseExterno .= "and rh167_tipoasse = {$oTipoAssentamento->getSequencial()} ";
    $sWhereTipoAsseExterno .= "and rh167_instit   = {$oInstituicao->getSequencial()} ";
    $sSqlTipoAsseExterno    = $oDaoTipoAsseExterno->sql_query(null, "rh167_codmovsefip, rh167_situacaoafastamento, r67_reto", null, $sWhereTipoAsseExterno);

    $rsTipoAsseExterno       = db_query($sSqlTipoAsseExterno);

    if ( !$rsTipoAsseExterno ) {
      throw new DBException(_M(self::MENSAGEM . "erro_ao_buscar_informacoes_externas"));
    }

    $oTipoAsseExterno           = db_utils::fieldsMemory($rsTipoAsseExterno, 0);
    $this->sSefip               = $oTipoAsseExterno->rh167_codmovsefip;
    $this->iSituacaoAfastamento = $oTipoAsseExterno->rh167_situacaoafastamento;
    $this->sCodigoRetorno       = $oTipoAsseExterno->r67_reto;
  }

  public static function getInstance(TipoAssentamento $oTipoAssentamento) {

    if ( !array_key_exists($oTipoAssentamento->getSequencial(), self::$aInstance ) ) {
      self::$aInstance[$oTipoAssentamento->getSequencial()] = new InformacoesExternasTipoAssentamento($oTipoAssentamento);
    }

    return self::$aInstance[$oTipoAssentamento->getSequencial()];
  }

  public function getCompetencia() {
    return $this->oCompetencia;
  }

  public function getSefip() {
    return $this->sSefip;
  }

  public function getTipoAssentamento() {
    return $this->oTipoAssentamento;
  }

  public function getInstituicao() {
    return $this->oInstituicao;
  }

  public function getSituacaoAfastamento() {
    return $this->iSituacaoAfastamento;
  }

  public function getCodigoRetorno() {
    return $this->sCodigoRetorno;
  }

  public static function getTipoAssentamentoConfiguradosPorCompetencia(DBCompetencia $oCompetencia = null, Instituicao $oInstituicao = null) {

    if(empty($oCompetencia)) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }
    
    $iAno         = $oCompetencia->getAno();
    $iMes         = $oCompetencia->getMes();
    $iInstituicao = db_getsession("DB_instit");

    if(!empty($oInstituicao)) {
      $iInstituicao = $oInstituicao->getSequencial();
    }

    $oDaoTipoAsseExterno    = new cl_tipoasseexterno;
    $sWhereTipoAsseExterno  = "     rh167_anousu = {$iAno}";
    $sWhereTipoAsseExterno .= " and rh167_mesusu = {$iMes}";
    $sWhereTipoAsseExterno .= " and rh167_instit = {$iInstituicao}";
    $sSqlTipoAsseExterno    = $oDaoTipoAsseExterno->sql_query_file(null, "rh167_tipoasse", null, $sWhereTipoAsseExterno);
    $rsTipoAsseExterno      = db_query($sSqlTipoAsseExterno);

    if(!$rsTipoAsseExterno) {
      throw new DBException("Ocorreu um erro ao buscar os tipos de assentamentos com configurações externas.");
    }

    $aListaTipos = array();
    if(pg_num_rows($rsTipoAsseExterno) > 0) {
      for ($iIndTipoasseExterno=0; $iIndTipoasseExterno < pg_num_rows($rsTipoAsseExterno) ; $iIndTipoasseExterno++) { 
        $aListaTipos[] = InformacoesExternasTipoAssentamento::getInstance(TipoAssentamentoRepository::getInstanciaPorCodigo(db_utils::fieldsMemory($rsTipoAsseExterno, $iIndTipoasseExterno)->rh167_tipoasse));
      }
    }

    return count($aListaTipos) > 0 ? $aListaTipos : false;
  }
}
