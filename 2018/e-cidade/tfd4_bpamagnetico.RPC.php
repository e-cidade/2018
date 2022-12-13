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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_layouttxt.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");

define("URL_MENSAGEM_TFD4_BPAMAGNETICO_RPC", "saude.tfd.tfd4_bpamagneticoRPC.");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$oMsgErro = new stdClass();

try {

  switch($oParam->exec) {

    /**
     * Busca os financiamentos mais atualizados da base
     */
  	case 'getFinanciamentosAtualizados':
  	  
  	  $oDaoFinanciamento = new cl_sau_financiamento();
  	  $sSqlFinanciamento = $oDaoFinanciamento->sql_query_ultimos_financiamentos(" sd65_c_financiamento ");
  	  
  	  $rsFinanciamento = db_query($sSqlFinanciamento);
  	  if (!$rsFinanciamento) {
  	    
  	    $oMsgErro->erro_banco = pg_last_error();
  	  	throw new DBException(_M(URL_MENSAGEM_TFD4_BPAMAGNETICO_RPC."erro_ao_buscar_financiamentos", $oMsgErro));
  	  }
  	  $oRetorno->aDados = db_utils::getColectionByRecord($rsFinanciamento, false, false, true);
      break;
    
  	case 'getDadosCompetenciaEncerrada':
  	  
  	  $sCampos  = " tf32_i_codigo,";
      $sCampos .= " case ";
      $sCampos .= "   when exists (select 1";
      $sCampos .= "                  from tfd_bpamagnetico";
      $sCampos .= "                 where tfd_bpamagnetico.tf33_i_fechamento = tfd_fechamento.tf32_i_codigo)";
      $sCampos .= "   then true";
      $sCampos .= "   else false";
      $sCampos .= " end as gero_arquivo   ";
  	  
  	  $oDaoFechamento = new cl_tfd_fechamento();
  	  $sSqlFechamento = $oDaoFechamento->sql_query_file(null, $sCampos, " tf32_i_codigo desc ");
  	  $rsFechamento   = $oDaoFechamento->sql_record($sSqlFechamento);
  	  $iLinhas        = $oDaoFechamento->numrows;
  	  
  	  if ($iLinhas > 0) {
  	  	
  	    for ($i = 0; $i < $iLinhas; $i++) {
  	    	
  	      $oDadosFechamento = db_utils::fieldsMemory($rsFechamento, $i);
  	      $oCompetencia     = new CompetenciaTFD($oDadosFechamento->tf32_i_codigo);
  	      
  	      $oDados = new stdClass();
  	      $oDados->iCodigoFechamento = $oCompetencia->getCodigo();
  	      $oDados->iMesCompetencia   = $oCompetencia->getCompetencia()->getMes();
  	      $oDados->iAnoCompetencia   = $oCompetencia->getCompetencia()->getAno();
  	      $oDados->dtSistema         = $oCompetencia->getDataInclusao()->convertTo(DBDate::DATA_PTBR);
  	      $oDados->sHoraSistema      = $oCompetencia->getHora();
  	      $oDados->iFinanciamento    = $oCompetencia->getFinanciamento()->getCodigo();
  	      $oDados->dtInicio          = $oCompetencia->getPeriodoInicial()->convertTo(DBDate::DATA_PTBR);
  	      $oDados->dtFim             = $oCompetencia->getPeriodoFinal()->convertTo(DBDate::DATA_PTBR);
  	      $oDados->sDescricao        = urlencode($oCompetencia->getDescricao());
  	      $oDados->sUsuario          = urlencode($oCompetencia->getUsuario()->getNome());
  	      $oDados->lGerouArquivo     = $oDadosFechamento->gero_arquivo == 't';
  	      
  	      $oRetorno->aDados[] = $oDados;
  	    }
  	  }
  	  
  	  break;
  	  
  	case 'remover':
  	  
  	  db_inicio_transacao();
  	  $oCompetencia = new CompetenciaTFD($oParam->iCodigo);
  	  $oCompetencia->remover();
  	  db_fim_transacao();
  	  
  	  break;
  	  
	  case 'processar':

	    /**
       * Processa o fechamento da competência
	     */
	    $oCompetencia = new CompetenciaTFD();
	    
	    if (!empty($oParam->iCodigo)) {
	      $oCompetencia = new CompetenciaTFD($oParam->iCodigo);
	    }
	    $oCompetencia->setCompetencia(new DBCompetencia($oParam->iAnoCompetencia, $oParam->iMesCompetencia));
	    $oCompetencia->setDataInclusao(new DBDate(date("d/m/Y")));
	    $oCompetencia->setHora(date("H:i"));
	    $oCompetencia->setUsuario(new UsuarioSistema(db_getsession("DB_id_usuario")));
	    $oCompetencia->setPeriodoInicial(new DBDate($oParam->dtInicio));
	    $oCompetencia->setPeriodoFinal(new DBDate($oParam->dtFim));
	    $oCompetencia->setFinanciamento(FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo($oParam->iFinanciamento));
	    $oCompetencia->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricao));
	    
	    db_inicio_transacao();
	    
	    $oCompetencia->salvar();
	    
	    db_fim_transacao();
	    
	    break;
	    
	  case 'dadosFormGerarArquivo':
	    
	    $oDaoDbConfig = new cl_db_config();
	    $sCamposDB    = " nomeinst, cgc as cnpj ";
	    $sSqlDB       = $oDaoDbConfig->sql_query_file(db_getsession("DB_instit"), $sCamposDB, null);
	    $rsDBConfig   = $oDaoDbConfig->sql_record($sSqlDB);
	    
	    $oDaoSauConfig = new cl_sau_config();
	    $sCamposSau    = " s103_c_bpasigla, s103_c_bpasecrdestino, s103_c_bpaibge ";
	    $sWhereSau     = " s103_c_bpasecrdestino is not null and s103_c_bpaibge  is not null ";
	    $sSqlSau       = $oDaoSauConfig->sql_query_file(null, $sCamposSau, null, $sWhereSau);
	    $rsSauConfig   = $oDaoSauConfig->sql_record($sSqlSau);
	    
	    if ($oDaoSauConfig->numrows == 0) {
	      throw new BusinessException(_M(URL_MENSAGEM_TFD4_BPAMAGNETICO_RPC ."paramentros_saude_nao_configurados"));
	    }

	    $oDadosConfig = db_utils::fieldsMemory($rsDBConfig, 0);
	    $oDadosSaude  = db_utils::fieldsMemory($rsSauConfig, 0);
	    
	    $oRetorno->sInstituicao = urlencode($oDadosConfig->nomeinst);
	    $oRetorno->iCnpj        = $oDadosConfig->cnpj;
	    $oRetorno->sBpaSigla    = urlencode($oDadosSaude->s103_c_bpasigla);
	    $oRetorno->sBpaDestino  = urlencode($oDadosSaude->s103_c_bpasecrdestino);
	    $oRetorno->sBpaIbge     = urlencode($oDadosSaude->s103_c_bpaibge);
	    $oRetorno->iMesAtual    = date("n", db_getsession("DB_datausu"));
	    
	    break;

	  case 'gerarArquivo':
	    
	    $iTipoLayout = BPAMagnetico::BPA_INDIVIDUAL;
	    
	    $oCompetencia  = new CompetenciaTFD($oParam->iCompetencia);
	    
	    
	    $sNomeArquivo            = "/tmp/{$oParam->sNomeArquivo}";
	    $sArquivoInconsistencia  = "tmp/erro_bpa_magnetico.json";
	    
	    $oBPAMagnetico = new BPAMagnetico($iTipoLayout, $sNomeArquivo, $oCompetencia, new DBLogJSON($sArquivoInconsistencia));
	    $oBPAMagnetico->setInstituicao(new Instituicao(db_getsession("DB_instit")));
	    
	    /**
	     * Adiciona as UPs como filtro 
	     */
	    if (!empty($oParam->sUps)) {
	    
	      foreach (explode(",", $oParam->sUps) as $iUnidade) {
	        $oBPAMagnetico->adicionarUnidades(UnidadeProntoSocorroRepository::getUnidadeProntoSocorroByCodigo($iUnidade));
	      }
	    }
	    
	    $oBPAMagnetico->setVersaoSistema(DB_VERSION);
	    
	    db_inicio_transacao();
	    $oBPAMagnetico->escreverArquivo();
	    db_fim_transacao();
	    
	    
	    /**
	     * Retorno dos dados para cliente
	     */
	    $oRetorno->oDadosBPA              = $oBPAMagnetico->getInformacoesCabecalho();
	    $oRetorno->sNomeArquivo           = urlencode($sNomeArquivo);
	    $oRetorno->lTemInconsistencia     = $oBPAMagnetico->temInconsistencia();
	    $oRetorno->sArquivoInconsistencia = urlencode($sArquivoInconsistencia);
	    break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>