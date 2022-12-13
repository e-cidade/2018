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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));  

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->erro        = false;
$oRetorno->sMessage     = '';

CONST MENSAGEM = "recursoshumanos.rh.rec4_agendaassentamentoRPC.";

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "carregarSelecao":

      if(empty($oParam->iTipoAssentamento)) {
        throw new BusinessException(_M(MENSAGEM ."erro_buscar_selecoes"));
      }

      $aSelecao = array();

      $oTipoAssentamento   = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);
      $oAgendaAssentamento = AgendaAssentamentoRepository::getInstanciaPorTipoAssentamento($oTipoAssentamento);
      $oAgendaAssentamento = AgendaAssentamentoRepository::getListaSelecaoParaTipo($oAgendaAssentamento);

      foreach ($oAgendaAssentamento->getListaSelecao() as $oSelecao) {

        $oStdSelecao = new stdClass;
        $oStdSelecao->iCodigo    = $oSelecao->getCodigo();
        $oStdSelecao->sDescricao = $oSelecao->getDescricao();

        $aSelecao[] = $oStdSelecao;
      }

      $oRetorno->aSelecao = $aSelecao;

    break;

    case "buscarServidoresAssentamento":

      $oTipoAssentamento   = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);
      $oInstituicao        = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
      $oSelecao            = new Selecao($oParam->iCodigoSelecao);
      $oAgendaAssentamento = AgendaAssentamentoRepository::getInstanciaPorTipoSelecaoInstituicao($oTipoAssentamento, $oSelecao, $oInstituicao);
      $aServidoresSelecao         = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(),
                                                                        DBPessoal::getMesFolha(), 
                                                                        $oParam->iCodigoSelecao);

      foreach ($aServidoresSelecao as $oServidorSelecao) {
        $aMatriculasServidoresSelecao[] = $oServidorSelecao->getMatricula();
      }

      $aLotacao = LotacaoRepository::getLotacoesByUsuario(UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario")));
      $aLotacoesUsuario = null;
      foreach ($aLotacao as $oLotacao) {
        $aLotacoesUsuario[] = $oLotacao->getCodigoLotacao();
      }

      if(empty($aLotacoesUsuario)) {
        throw new BusinessException("Erro ao buscar lotação.");
      }

      $aServidoresLotacao  = ServidorRepository::getServidoresByLotacao(DBPessoal::getAnoFolha(),
                                                                        DBPessoal::getMesFolha(), 
                                                                        $aLotacoesUsuario);

      $aServidores = array();
      foreach ($aServidoresLotacao as $oServidor) {

        if(in_array($oServidor->getMatricula(), $aMatriculasServidoresSelecao)) {
          $aServidores[] = $oServidor;
        }
      }

      $aServidoresComDireito = array();

      foreach ($aServidores as $oServidor) {

        $oFormula             = new DBFormulaServidorAgendaAssentamentos($oServidor);
        $sSqlCondicaoServidor = $oFormula->parse("SELECT [". $oAgendaAssentamento->getNomeFormulaCondicao() ."] as condicao");


        $rsCondicaoServidor   = db_query($sSqlCondicaoServidor);

        if(!$rsCondicaoServidor) {
          throw new BusinessException(_M(MENSAGEM .'erro_executar_formula_condicao'));
        }

        if(pg_num_rows($rsCondicaoServidor) > 0) {

          $lDireitoServidor[$oServidor->getMatricula()] = false;

          if((bool)db_utils::fieldsMemory($rsCondicaoServidor, 0)->condicao) {

            $lDireitoServidor[$oServidor->getMatricula()] = true;

            $oStdServidorComDireito = new stdClass;
            $oStdServidorComDireito->iMatricula = $oServidor->getMatricula();
            $oStdServidorComDireito->sNome      = $oServidor->getCgm()->getNome();

            $aServidoresComDireito[] = $oStdServidorComDireito;
          }
        }
      }
      
      $oRetorno->aServidores = $aServidoresComDireito;

    break;

    case "processarAssentamentos":

      if(count($oParam->aServidores) < 1) {
        throw new BusinessException(_M(MENSAGEM ."nenhum_assentamento_processar"));
      }

      $oDaoAgendaAssentamento = new cl_agendaassentamento();
      $sSqlAgendaAssentamento = $oDaoAgendaAssentamento->sql_query(null, "formulainicio.db148_nome as db148_nome_inicio, formulafim.db148_nome as db148_nome_fim, formulafaltasperiodo.db148_nome as db148_nome_faltasperiodo, h82_tipoassentamento, h82_selecao", null, "h82_tipoassentamento = ". $oParam->iTipoAssentamento . " and h82_selecao = " . $oParam->iSelecao);
      $rsAgendaAssentamento = db_query($sSqlAgendaAssentamento);
      $stdAgendaAssentamento = db_utils::fieldsMemory($rsAgendaAssentamento, 0);
      
      $sFormulaInicio        = $stdAgendaAssentamento->db148_nome_inicio;// 'INICIO';
      $sFormulaFinal         = !empty($stdAgendaAssentamento->db148_nome_fim) ? $stdAgendaAssentamento->db148_nome_fim : null; // 'FIM';
      $sFormulaFaltasPeriodo = !empty($stdAgendaAssentamento->db148_nome_faltasperiodo) ? '['. $stdAgendaAssentamento->db148_nome_faltasperiodo .']' : 0; // 'FALTAS_PERIODO';

      foreach ($oParam->aServidores as $iMatricula) {

        $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
        $oFormula  = new DBFormulaServidorAgendaAssentamentos($oServidor);
        
        $sSqlInformacoesAssentamentos     = $oFormula->parse("SELECT [$sFormulaInicio] as inicio");

        if(!empty($sFormulaFinal)) {

          $sSqlInformacoesAssentamentos   = $oFormula->parse("SELECT [$sFormulaInicio] as inicio, [$sFormulaFinal] as final");
          
          if(!empty($sFormulaFaltasPeriodo)) {
            $sSqlInformacoesAssentamentos = $oFormula->parse("SELECT [$sFormulaInicio] as inicio, [$sFormulaFinal] as final, {$sFormulaFaltasPeriodo} as faltas");
          }
        }

        $rsInformacoesAssentamentos   = db_query($sSqlInformacoesAssentamentos);

        if(!$rsInformacoesAssentamentos) {
          throw new BusinessException(_M(MENSAGEM ."erro_gerar_informacoes_assentamentos"));
        }

        if(pg_num_rows($rsInformacoesAssentamentos) > 0) {

          $stdInformacoesAssentamentos = db_utils::fieldsMemory($rsInformacoesAssentamentos, 0);

          $oDataConcessao   = new DBDate($stdInformacoesAssentamentos->inicio);
          $oDataTermino     = null;
          $iQuantidadeDias  = 0;
          
          if(isset($stdInformacoesAssentamentos->final) && !empty($stdInformacoesAssentamentos->final)) {

            if(isset($stdInformacoesAssentamentos->faltas)) {
              $iFaltas        = (int)$stdInformacoesAssentamentos->faltas;
            }

            $oDataTermino     = new DBDate($stdInformacoesAssentamentos->final);
            $oDataTermino     = $oDataTermino->adiantarPeriodo($iFaltas, 'd');
            $iQuantidadeDias  = DBDate::getIntervaloEntreDatas($oDataConcessao, $oDataTermino);
            $iQuantidadeDias  = $iQuantidadeDias->format('%a')+1;
          }

          $oDataAtual       = new DBDate(date('Y-m-d'));

          $oDaoAssentamento = new cl_assenta;

          $oDaoAssentamento->h16_regist = $oServidor->getMatricula();
          $oDaoAssentamento->h16_assent = $oParam->iTipoAssentamento;
          $oDaoAssentamento->h16_dtconc = $oDataConcessao->getDate();
          $oDaoAssentamento->h16_dtterm = $oDataTermino instanceof DBDate ? $oDataTermino->getDate() : '';
          $oDaoAssentamento->h16_dtlanc = $oDataAtual->getDate();
          $oDaoAssentamento->h16_quant  = $iQuantidadeDias;
          $oDaoAssentamento->h16_perc   = '0';
          $oDaoAssentamento->h16_login  = db_getsession("DB_id_usuario");
          $oDaoAssentamento->h16_anoato = date('Y');
          $GLOBALS["HTTP_POST_VARS"]["h16_conver"] = 'f';
          $oDaoAssentamento->h16_conver = 'f';

          $oDaoAssentamento->incluir(null);

          if($oDaoAssentamento->erro_status == '0') {
            throw new BusinessException($oDaoAssentamento->erro_msg);
          }

          $oDaoAssentamentoFuncional = new cl_assentamentofuncional;
          $oDaoAssentamentoFuncional->rh193_assentamento_funcional   = $oDaoAssentamento->h16_codigo;
          $oDaoAssentamentoFuncional->rh193_assentamento_efetividade = 'null';

          $oDaoAssentamentoFuncional->incluir(null);

          if($oDaoAssentamentoFuncional->erro_status == '0') {
            throw new BusinessException($oDaoAssentamentoFuncional->erro_msg);
          }
        }
      }

      $oRetorno->sMessage = urlencode(_M(MENSAGEM ."sucesso_processar"));

    break;
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo JSON::stringify($oRetorno);