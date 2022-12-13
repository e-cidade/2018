<?
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");


define('MENSAGENS', 'recursoshumanos.pessoal.pes1_cfpessRPC.');

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

$lErro    = false;
$sMsgErro = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'ativarSuplementar':
      
      $iInstituicao      = $oParam->iInstituicao;
      $oCompetenciaAtual = DBPessoal::getCompetenciaFolha();
        
      $oDaoCfpess = new cl_cfpess();
      $sSqlCfpess = $oDaoCfpess->sql_query_file($oCompetenciaAtual->getAno(),
                                                $oCompetenciaAtual->getMes(),
                                                $iInstituicao);
      $rsCfPess   = $oDaoCfpess->sql_record($sSqlCfpess);

      $oStdClassCfpess = db_utils::fieldsMemory( $rsCfPess, 0);

      $oDaoCfpess->r11_instit                            = $oStdClassCfpess->r11_instit;
      $oDaoCfpess->r11_anousu                            = $oStdClassCfpess->r11_anousu;
      $oDaoCfpess->r11_mesusu                            = $oStdClassCfpess->r11_mesusu;
      $oDaoCfpess->r11_codaec                            = $oStdClassCfpess->r11_codaec;
      $oDaoCfpess->r11_natest                            = $oStdClassCfpess->r11_natest;
      $oDaoCfpess->r11_cdfpas                            = $oStdClassCfpess->r11_cdfpas;
      $oDaoCfpess->r11_cdactr                            = $oStdClassCfpess->r11_cdactr;
      $oDaoCfpess->r11_peactr                            = $oStdClassCfpess->r11_peactr;
      $oDaoCfpess->r11_pctemp                            = $oStdClassCfpess->r11_pctemp;
      $oDaoCfpess->r11_pcterc                            = $oStdClassCfpess->r11_pcterc;
      $oDaoCfpess->r11_fgts12                            = $oStdClassCfpess->r11_fgts12;
      $oDaoCfpess->r11_cdcef                             = $oStdClassCfpess->r11_cdcef;
      $oDaoCfpess->r11_cdfgts                            = $oStdClassCfpess->r11_cdfgts;
      $oDaoCfpess->r11_ultger                            = $oStdClassCfpess->r11_ultger;
      $oDaoCfpess->r11_ultfec                            = $oStdClassCfpess->r11_ultfec;
      $oDaoCfpess->r11_arredn                            = $oStdClassCfpess->r11_arredn;
      $oDaoCfpess->r11_sald13                            = $oStdClassCfpess->r11_sald13;
      $oDaoCfpess->r11_datai                             = $oStdClassCfpess->r11_datai;
      $oDaoCfpess->r11_dataf                             = $oStdClassCfpess->r11_dataf;
      $oDaoCfpess->r11_fecha                             = $oStdClassCfpess->r11_fecha;
      $oDaoCfpess->r11_ultreg                            = $oStdClassCfpess->r11_ultreg;
      $oDaoCfpess->r11_codipe                            = $oStdClassCfpess->r11_codipe;
      $oDaoCfpess->r11_mes13                             = $oStdClassCfpess->r11_mes13;
      $oDaoCfpess->r11_tbprev                            = $oStdClassCfpess->r11_tbprev;
      $oDaoCfpess->r11_confer                            = $oStdClassCfpess->r11_confer;
      $oDaoCfpess->r11_valor                             = $oStdClassCfpess->r11_valor;
      $oDaoCfpess->r11_dtipe                             = $oStdClassCfpess->r11_dtipe;
      $oDaoCfpess->r11_implan                            = $oStdClassCfpess->r11_implan;
      $oDaoCfpess->r11_subpes                            = $oStdClassCfpess->r11_subpes;
      $oDaoCfpess->r11_rubmat                            = $oStdClassCfpess->r11_rubmat;
      $oDaoCfpess->r11_eleina                            = $oStdClassCfpess->r11_eleina;
      $oDaoCfpess->r11_elepen                            = $oStdClassCfpess->r11_elepen;
      $oDaoCfpess->r11_rubnat                            = $oStdClassCfpess->r11_rubnat;
      $oDaoCfpess->r11_rubdec                            = $oStdClassCfpess->r11_rubdec;
      $oDaoCfpess->r11_qtdcal                            = $oStdClassCfpess->r11_qtdcal;
      $oDaoCfpess->r11_palime                            = $oStdClassCfpess->r11_palime;
      $oDaoCfpess->r11_altfer                            = $oStdClassCfpess->r11_altfer;
      $oDaoCfpess->r11_ferias                            = $oStdClassCfpess->r11_ferias;
      $oDaoCfpess->r11_fer13                             = $oStdClassCfpess->r11_fer13;
      $oDaoCfpess->r11_ferant                            = $oStdClassCfpess->r11_ferant;
      $oDaoCfpess->r11_fer13o                            = $oStdClassCfpess->r11_fer13o;
      $oDaoCfpess->r11_fer13a                            = $oStdClassCfpess->r11_fer13a;
      $oDaoCfpess->r11_ferabo                            = $oStdClassCfpess->r11_ferabo;
      $oDaoCfpess->r11_feabot                            = $oStdClassCfpess->r11_feabot;
      $oDaoCfpess->r11_feradi                            = $oStdClassCfpess->r11_feradi;
      $oDaoCfpess->r11_fadiab                            = $oStdClassCfpess->r11_fadiab;
      $oDaoCfpess->r11_recalc                            = $oStdClassCfpess->r11_recalc;
      $oDaoCfpess->r11_pagaab                            = $oStdClassCfpess->r11_pagaab;
      $oDaoCfpess->r11_fersal                            = $oStdClassCfpess->r11_fersal;
      $oDaoCfpess->r11_vtprop                            = $oStdClassCfpess->r11_vtprop;
      $oDaoCfpess->r11_desliq                            = $oStdClassCfpess->r11_desliq;
      $oDaoCfpess->r11_propae                            = $oStdClassCfpess->r11_propae;
      $oDaoCfpess->r11_propac                            = $oStdClassCfpess->r11_propac;
      $oDaoCfpess->r11_codestrut                         = $oStdClassCfpess->r11_codestrut;
      $oDaoCfpess->r11_geracontipe                       = $oStdClassCfpess->r11_geracontipe;
      $oDaoCfpess->r11_13ferias                          = $oStdClassCfpess->r11_13ferias;
      $oDaoCfpess->r11_pagarferias                       = $oStdClassCfpess->r11_pagarferias;
      $oDaoCfpess->r11_vtfer                             = $oStdClassCfpess->r11_vtfer;
      $oDaoCfpess->r11_vtcons                            = $oStdClassCfpess->r11_vtcons;
      $oDaoCfpess->r11_vtmpro                            = $oStdClassCfpess->r11_vtmpro;
      $oDaoCfpess->r11_localtrab                         = $oStdClassCfpess->r11_localtrab;
      $oDaoCfpess->r11_databaseatra                      = $oStdClassCfpess->r11_databaseatra;
      $oDaoCfpess->r11_rubpgintegral                     = $oStdClassCfpess->r11_rubpgintegral;
      $oDaoCfpess->r11_conver                            = $oStdClassCfpess->r11_conver;
      $oDaoCfpess->r11_concatdv                          = $oStdClassCfpess->r11_concatdv;
      $oDaoCfpess->r11_infla                             = $oStdClassCfpess->r11_infla;
      $oDaoCfpess->r11_baseipe                           = $oStdClassCfpess->r11_baseipe;
      $oDaoCfpess->r11_txadm                             = $oStdClassCfpess->r11_txadm;
      $oDaoCfpess->r11_modanalitica                      = $oStdClassCfpess->r11_modanalitica;
      $oDaoCfpess->r11_viravalemes                       = $oStdClassCfpess->r11_viravalemes;
      $oDaoCfpess->r11_histslip                          = $oStdClassCfpess->r11_histslip;
      $oDaoCfpess->r11_mensagempadraotxt                 = $oStdClassCfpess->r11_mensagempadraotxt;
      $oDaoCfpess->r11_recpatrafasta                     = $oStdClassCfpess->r11_recpatrafasta;
      $oDaoCfpess->r11_relatoriocontracheque             = $oStdClassCfpess->r11_relatoriocontracheque;
      $oDaoCfpess->r11_relatorioempenhofolha             = $oStdClassCfpess->r11_relatorioempenhofolha;
      $oDaoCfpess->r11_relatoriocomprovanterendimentos   = $oStdClassCfpess->r11_relatoriocomprovanterendimentos;
      $oDaoCfpess->r11_relatoriotermorescisao            = $oStdClassCfpess->r11_relatoriotermorescisao;
      $oDaoCfpess->r11_geraretencaoempenho               = $oStdClassCfpess->r11_geraretencaoempenho;
      $oDaoCfpess->r11_percentualipe                     = $oStdClassCfpess->r11_percentualipe;
      $oDaoCfpess->r11_datainiciovigenciarpps            = $oStdClassCfpess->r11_datainiciovigenciarpps;
      $oDaoCfpess->r11_sistemacontroleponto              = $oStdClassCfpess->r11_sistemacontroleponto;
      $oDaoCfpess->r11_baseconsignada                    = $oStdClassCfpess->r11_baseconsignada;
      $oDaoCfpess->r11_abonoprevidencia                  = $oStdClassCfpess->r11_abonoprevidencia;
      $oDaoCfpess->r11_compararferias                    = $oStdClassCfpess->r11_compararferias;
      $oDaoCfpess->r11_baseferias                        = $oStdClassCfpess->r11_baseferias;
      $oDaoCfpess->r11_basesalario                       = $oStdClassCfpess->r11_basesalario;
      $oDaoCfpess->r11_suplementar                       = $oStdClassCfpess->r11_suplementar;

      $sWhereAlterar  = "   (r11_anousu  = {$oDaoCfpess->r11_anousu} and ";
      $sWhereAlterar .= "    r11_mesusu <= {$oDaoCfpess->r11_mesusu} and ";
      $sWhereAlterar .= "    r11_instit  = {$oDaoCfpess->r11_instit})    ";
      $sWhereAlterar .= "or (r11_instit  = {$oDaoCfpess->r11_instit} and ";
      $sWhereAlterar .= "    r11_anousu  < {$oDaoCfpess->r11_anousu});   ";
      $oDaoCfpessAnteriores                  = new cl_cfpess();
      
      if ( $oParam->lAtivar === true || $oParam->lAtivar === 't' || $oParam->lAtivar === '1' || $oParam->lAtivar === 1 ) {
        
        $oDaoCfpessAnteriores->r11_suplementar = 't';
        $oDaoCfpess->r11_suplementar           = 't';

      } else {
        
        $oDaoCfpessAnteriores->r11_suplementar        = 'f';
        $oDaoCfpess->r11_suplementar                  = 'f';
        $GLOBALS["HTTP_POST_VARS"]["r11_suplementar"] = 'f';

      }

      if ( !$oDaoCfpessAnteriores->alterarWhere($sWhereAlterar) ) {
        $oRetorno->erro = true;
        $oRetorno->sMessage = urlencode(_M(MENSAGENS . 'erro_parametro_suplementar_anteriores'));
      }

      if ( !$oRetorno->erro ) {
        
        //Setando em varíval de Sessão para a instituição se a suplementar está ativa
        DBPessoal::declararEstruturaFolhaPagamento(new Instituicao($iInstituicao), $oCompetenciaAtual);

        if( $oParam->lAtivar === false || $oParam->lAtivar === 'f' || $oParam->lAtivar === '0' || $oParam->lAtivar === 0 ) {
          $oRetorno->sMessage = urlencode(_M(MENSAGENS .  'sucesso_desativado'));
        } else {
          $oRetorno->sMessage = urlencode(_M(MENSAGENS .  'sucesso_ativado'));
        }

        db_fim_transacao();
      }

    break;
  }

  if ( db_utils::inTransaction() ){
    db_fim_transacao(true);
  }

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro  = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);