<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));

$clrhgeracaofolha     = new cl_rhgeracaofolha();
$clrhgeracaofolhatipo = new cl_rhgeracaofolhatipo();
$clrhgeracaofolhareg  = new cl_rhgeracaofolhareg();
$oJson                = new services_json();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = 1;
$oRetorno->message    = '';

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_rhgeracaofolha.');


try {

  switch ($oParam->exec) {

  case "getServidores":

    $sSqlServidores         = $clrhgeracaofolha->sqlGeracaoFolha($oParam);
    $rsServidores           = $clrhgeracaofolha->sql_record($sSqlServidores);
    $aServidores            = db_utils::getCollectionByRecord($rsServidores, false, false, true);
    if (count($aServidores) == 0) {
      throw new Exception( _M( MENSAGENS . 'sem_registros_encontrados' ) );
    }
    $oRetorno->aServidores  = $aServidores;

    break;

  case "geraFolha":

    $oDadosGeracaoFolha     = $oParam->oDados;
    $lExisteDadosComSaldo   = false;
    $sMsgGeracao            = "geracao_com_sucesso";
    $sNomeRelatorio         = "tmp/relatorio_inconsistencia_geracao_folha_disco_".date("Ymdhis").".pdf";

    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();

    $descricao_folha = "";
    switch ($oParam->oDados->folhaselecion) {
    case "0": 
      $descricao_folha = "Salário";
      break;  
    case "1":
      $descricao_folha = "Adiantamento";
      break;
    case "3":
      $descricao_folha = "Rescisão";
      break;
    case "4":
      $descricao_folha = "Saldo do 13o";
      break;
    case "5":
      $descricao_folha = "Complementar";
      break;
    case "6":
      $descricao_folha = "Suplementar";
      break;
    }
    $head2 = "Relatório de Inconsistências da Geração em Disco";
    $head4 = "Ano/Mês: ".$oDadosGeracaoFolha->anofolha."/".$oDadosGeracaoFolha->mesfolha;
    $head5 = "Tipo de Folha: ".$descricao_folha;
    $head6 = "Descrição: ".$oDadosGeracaoFolha->rh102_descricao;

    $lCabecalhoRelatorio = true;
    $lRelatorio          = false;
    $lCor                = 1;
    $iTotalServidores    = 0;

    db_inicio_transacao();

    $aServidores = array();
    foreach ($oParam->aDadosServidores as $aServidorTipoFolha => $iServidorTipoFolha ) {

      list($iServidor) = explode('_',$iServidorTipoFolha);
      array_push( $aServidores, $iServidor );
    }

    $sSqlServidores    = $clrhgeracaofolha->sqlGeracaoFolha($oParam, $aServidores);
    $rsServidores      = $clrhgeracaofolha->sql_record($sSqlServidores);
    if ($clrhgeracaofolha->numrows == 0){
      throw new Exception( _M( MENSAGENS . 'sem_registros_encontrados' ) );
    }
    $aServidores       = db_utils::getCollectionByRecord($rsServidores, false, false, true);

    $clrhgeracaofolha->rh102_descricao  = $oDadosGeracaoFolha->rh102_descricao;
    $clrhgeracaofolha->rh102_usuario    = db_getsession('DB_id_usuario');
    $clrhgeracaofolha->rh102_dtproc     = date('Y-m-d',db_getsession('DB_datausu'));;
    $clrhgeracaofolha->rh102_ativo      = 't';
    $clrhgeracaofolha->rh102_mesusu     = $oDadosGeracaoFolha->mesfolha;
    $clrhgeracaofolha->rh102_anousu     = $oDadosGeracaoFolha->anofolha;
    $clrhgeracaofolha->rh102_instit     = db_getsession('DB_instit');
    $clrhgeracaofolha->incluir("");
    if($clrhgeracaofolha->erro_status == "0"){
      throw new Exception( _M( MENSAGENS . 'erro_gravardadosgeracao' ) );
    }

    foreach ($aServidores as $oDados) {

      /*
       * Se o saldo a receber for menor que o liquido e o valor recebido subtraido do
       * liquido for uma diferença menor que 0,01 realizamos um ajuste devido a arredondamento
       *
       * O ajuste é subtrair a diferença do valor liquido que está sendo gerado.
       *
       * Este caso ocorre frequentemente quando é pago 50/50 do salario.
       */
      if ( round($oDados->valor_recebido,2) > 0
        && ( round(($oDados->proven - $oDados->descon - $oDados->valor_recebido),2) < round($oDados->liquido,2) )
        && ( round((round($oDados->liquido,2) - round(($oDados->proven - $oDados->descon - $oDados->valor_recebido),2)),2) == 0.01 ) 
      ) {

        $oDados->liquido =  $oDados->liquido - 0.01;
      }

      /*
       * Adicionada Valiação dos servidores que possuem saldo a receber
       */
      if( round($oDados->liquido,2) > 0
        && (round((($oDados->proven - $oDados->descon) - $oDados->valor_recebido),2)) > 0  
        && (round((($oDados->proven - $oDados->descon) - $oDados->valor_recebido ),2) <= round(($oDados->proven - $oDados->descon),2))
        && (round($oDados->liquido,2) <= round((($oDados->proven - $oDados->descon) - $oDados->valor_recebido ),2)) 
      ) {

        $lExisteDadosComSaldo = true;
        /**
         * Incluindo dado na tabela rhgeracaofolhareg
         */
        $clrhgeracaofolhareg->rh104_sequencial     = null;
        $clrhgeracaofolhareg->rh104_seqpes         = $oDados->rh02_seqpes;
        $clrhgeracaofolhareg->rh104_instit         = db_getsession('DB_instit');
        $clrhgeracaofolhareg->rh104_rhgeracaofolha = $clrhgeracaofolha->rh102_sequencial;
        $clrhgeracaofolhareg->rh104_vlrsalario     = $oDados->f010;
        $clrhgeracaofolhareg->rh104_vlrliquido     = $oDados->liquido;
        $clrhgeracaofolhareg->rh104_vlrprovento    = $oDados->proven;
        $clrhgeracaofolhareg->rh104_vlrdesconto    = $oDados->descon;
        $clrhgeracaofolhareg->incluir("");
        if ($clrhgeracaofolhareg->erro_status == "0") {
          throw new Exception( _M( MENSAGENS . 'erro_gravardadosgeracao' ) );
        }

        /**
         * Incluindo dados na tabela rhgeracaofolhareg
         */
        $clrhgeracaofolhatipo->rh103_sequencial        = null;
        $clrhgeracaofolhatipo->rh103_rhgeracaofolhareg = $clrhgeracaofolhareg->rh104_sequencial;
        $clrhgeracaofolhatipo->rh103_tipofolha         = $oDados->tipo_folha;

        if(isset($oDadosGeracaoFolha->complementares)){
          $iCodigoComplementar = $oDadosGeracaoFolha->complementares;
        } else {
          $iCodigoComplementar = "0";
        }
        $clrhgeracaofolhatipo->rh103_complementar      =  $iCodigoComplementar;
        $clrhgeracaofolhatipo->incluir("");
        if($clrhgeracaofolhatipo->erro_status == "0"){
          throw new Exception( _M( MENSAGENS . 'erro_gravardadosgeracao' ) );
        }

      } else {

        $lRelatorio  = true; 
        $sMsgGeracao = "geracao_com_sucesso_com_inconsistencias";

        /*
         * Geramos o relatório com as inconsistencias.
         */
        if ($oPdf->gety() > $oPdf->h - 30 || $lCabecalhoRelatorio) {

          $oPdf->AddPage();
          $oPdf->SetTextColor(0,0,0);
          $oPdf->SetFillColor(220);
          $oPdf->SetFont('arial','B',8);
          $oPdf->cell(25 ,4,"Matrícula"  ,1,0,"C",1);
          $oPdf->cell(105,4,"Nome"       ,1,0,"C",1);
          $oPdf->cell(30 ,4,"Valor Pago" ,1,0,"C",1);
          $oPdf->cell(30 ,4,"Saldo"      ,1,1,"C",1);

          $lCabecalhoRelatorio = false;
        }

        $lCor = ($lCor == 1?"0":"1");

        $oPdf->setfont('arial','',8);
        $oPdf->cell(25 ,4,$oDados->regist                                                                ,1,0,"C",$lCor);
        $oPdf->cell(105,4,urlDecode($oDados->z01_nome)                                                   ,1,0,"L",$lCor);
        $oPdf->cell(30 ,4,db_formatar($oDados->valor_recebido,'f')                                       ,1,0,"R",$lCor);
        $oPdf->cell(30 ,4,db_formatar(($oDados->proven - $oDados->descon - $oDados->valor_recebido),'f') ,1,1,"R",$lCor);

        $iTotalServidores++;
      }

    }

    if ($lExisteDadosComSaldo == false) {
      throw new Exception( _M( MENSAGENS . 'sem_registros_com_saldo' ) );
    }

    if ($lRelatorio) {
      $oPdf->setfont('arial','B',8);
      $oPdf->cell(160,4,"Total de Servidores:",1,0,"R",1);
      $oPdf->cell(30 ,4,$iTotalServidores     ,1,1,"R",1);
      $oPdf->Output($sNomeRelatorio, false, true);
      $oRetorno->relatorio_inconsistencias = $sNomeRelatorio;
    } else {
      unset($oPdf);
    }

    $oRetorno->message = urlencode( _M ( MENSAGENS . $sMsgGeracao ) );
    db_fim_transacao(false);

    break;

    /**
     * Verifica existencia de lançamento escrituração de férias ou décimo terceiro
     */
    case "verificaExistenciaLancamentoFeriasDecimoTerceiro":

      $iAno                   = db_getsession("DB_anousu");
      $iMes                   = date("m", db_getsession("DB_datausu"));
      $iInstituicao           = db_getsession("DB_instit");
      $oDaoEscrituraProvisao  = db_utils::getDao('escrituraprovisao');

      $sWhere  = "     c102_instit = {$iInstituicao}";
      $sWhere .= " and c102_processado is true";
      $sWhere .= " and c102_ano = {$iAno} and c102_mes >= {$iMes}";

      $sSqlBuscaEscrituraProvisao = $oDaoEscrituraProvisao->sql_query_file(null, "*", null, $sWhere);
      $rsBuscaEscrituraProvisao   = $oDaoEscrituraProvisao->sql_record($sSqlBuscaEscrituraProvisao);

      if ($oDaoEscrituraProvisao->numrows > 0) {

        $oLancamento = db_utils::fieldsMemory($rsBuscaEscrituraProvisao, 0);
        $sTipo       = $oLancamento->c102_tipoprovisao == "2" ? "Férias" : "Décimo Terceiro";

        $oRetorno->status  = 2;
        $sMensagem         = "Existem lançamentos para {$sTipo}\n";
        $sMensagem        .= "Para executar novo processamento da rotina, os lançamentos da escrituração devem ser estornados";
        $oRetorno->message = urlencode($sMensagem);
      }
      break;

      /**
       * Verifica se a folha de pagamento por adiantamento, 13º salário e rescisão
       * foi liberada no DBPref
       */
    case "verificarFolhaPagamentoDBPref" :

      $sMensagem = "";
      $lLiberada = false;

      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

        $iTipoFolha     = $oParam->tipoFolha;
        $oCompetencia   = DBPessoal::getCompetenciaFolha();
        $oCampoMensagem = new stdClass();

        switch ($iTipoFolha) {

        case 2:

          if (!FolhaPagamentoAdiantamento::hasFolhaAberta($oCompetencia)) {

            $oCampoMensagem->sTipoFolha = "adiantamento";
            $sMensagem                  = _M(MENSAGENS . 'folha_pagamento_liberado_dbpref', $oCampoMensagem);
            $lLiberada                  = true;
          }
          break;

        case 4:

          if (!FolhaPagamentoRescisao::hasFolhaAberta($oCompetencia)) {

            $oCampoMensagem->sTipoFolha = "rescisão";
            $sMensagem                  = _M(MENSAGENS . 'folha_pagamento_liberado_dbpref', $oCampoMensagem);
            $lLiberada                  = true;
          }
          break;

        case 5:

          if (!FolhaPagamento13o::hasFolhaAberta($oCompetencia)) {

            $oCampoMensagem->sTipoFolha = "13º salário";
            $sMensagem                  = _M(MENSAGENS . 'folha_pagamento_liberado_dbpref', $oCampoMensagem);
            $lLiberada                  = true;
          }
          break;

        } 
      }

      $oRetorno->message = urlencode($sMensagem);
      $oRetorno->dados   = $lLiberada; 
      break;

      /**
       * Retorna todas as folhas de pagamento abertas, além disso
       * retorna as folhas que foram liberadas no DBPref 
       */  
    case "retornarFolhasAbertas":

      $aFolhasDBPref = array();
      $aTipoFolha    = array(
        1 => urlencode("Salário"),
        2 => urlencode("Adiantamento"),
        3 => urlencode("Férias"),
        4 => urlencode("Rescisão"),
        5 => urlencode("13o"),
        6 => urlencode("Complementar"),
        7 => urlencode("Fixo")
      );

      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

        $aTipoFolha[8] = urlencode("Suplementar");
        $oCompetencia  = DBPessoal::getCompetenciaFolha();

        if (!FolhaPagamentoSalario::hasFolhaAberta($oCompetencia)) {         
          unset($aTipoFolha[1]);
        } 

        if (!FolhaPagamentoComplementar::hasFolhaAberta($oCompetencia)) {
          unset($aTipoFolha[6]);
        }

        if (!FolhaPagamentoSuplementar::hasFolhaAberta($oCompetencia)) {
          unset($aTipoFolha[8]);
        }

        if (FolhaPagamentoAdiantamento::hasFolha($oCompetencia) && !FolhaPagamentoAdiantamento::hasFolhaAberta($oCompetencia)) {
          $aFolhasDBPref[] = 2;
        }

        if (FolhaPagamentoRescisao::hasFolha($oCompetencia) && !FolhaPagamentoRescisao::hasFolhaAberta($oCompetencia)) {
          $aFolhasDBPref[] = 4;
        }        

        if (FolhaPagamento13o::hasFolha($oCompetencia) && !FolhaPagamento13o::hasFolhaAberta($oCompetencia)) {
          $aFolhasDBPref[] = 5;
        }

        if (!FolhaPagamentoSalario::hasFolhaAberta() && !FolhaPagamentoComplementar::hasFolhaAberta()) {
          unset($aTipoFolha[3]);
        }
      }

      $oRetorno->aTipoFolha    = $aTipoFolha;
      $oRetorno->aFolhasDBPref = $aFolhasDBPref;
      break;
    case "buscaMatriculas": 

      $oDadosFormulario = $oParam->oDadosFormulario;
      $oDaoRhPessoalMov = new cl_rhpessoalmov();

      $sWhereServidores  = "     rh02_anousu = " . DBPessoal::getAnoFolha();
      $sWhereServidores .= " and rh02_mesusu = " . DBPessoal::getMesFolha();
      $sWhereServidores .= " and rh02_instit = " . db_getsession('DB_instit');

      if (isset($oDadosFormulario->opcao_geral) and array_search($oDadosFormulario->opcao_geral, array(1,3,4,5)) ) {
        
        $aPrefix[1] = array("pontofs",  "r10");
        $aPrefix[3] = array("pontofe",  "r29");
        $aPrefix[4] = array("pontofr",  "r19");
        $aPrefix[5] = array("pontof13", "r34");
        $aPrefix[8] = array("pontocom", "r47");

        $sTable  = $aPrefix[$oDadosFormulario->opcao_geral][0];
        $sPrefix = $aPrefix[$oDadosFormulario->opcao_geral][1];
        
        $sWhereServidores .= "    and exists ( select 1 
                                                 from {$sTable} 
                                                where {$sPrefix}_regist = rh02_regist 
                                                  and {$sPrefix}_anousu = rh02_anousu
                                                  and {$sPrefix}_mesusu = rh02_mesusu
                                                  and {$sPrefix}_instit = rh02_instit )";

      }

      /**
       * Realiza o tratamento para a consulta de acordo com o filtro selecionado, 
       * verifica se foi escolhido o intervalo de matriculas ou as Matriculas selecinada.
       */
      if ($oDadosFormulario->opcao_gml == 'm' && isset($oDadosFormulario->opcao_filtro)) {

        switch ($oDadosFormulario->opcao_filtro) {
        case 'i':
          $sWhereServidores .= " and rh02_regist between {$oDadosFormulario->r110_regisi} and {$oDadosFormulario->r110_regisf}";
          break;
        case 's':

          $sMatriculas = implode(',', $oDadosFormulario->selregist);
          $sWhereServidores .= " and rh02_regist in ({$sMatriculas}) ";
          break;
        }
      }

      /**
       * Realiza o tratamento para a consulta de acordo com o filtro selecionado, 
       * verifica se foi escolhido o intervalo de lotações as as lotações selecionadas.
       */
      if ($oDadosFormulario->opcao_gml == 'l' && isset($oDadosFormulario->opcao_filtro)) {

        switch ($oDadosFormulario->opcao_filtro) {

          case 'i':
            $sCodigolotacoes   = " select r70_codigo from rhlota where r70_instit = " . db_getsession('DB_instit');
            $sCodigolotacoes  .= "                                 and r70_estrut between '{$oDadosFormulario->r110_lotaci}' ";
            $sCodigolotacoes  .= "                                                    and '{$oDadosFormulario->r110_lotacf}' ";
            break;

          case 's':
            $slotacoes         = implode('\',\'', $oDadosFormulario->sellotac);
            $sCodigolotacoes   = "select r70_codigo from rhlota where r70_instit = ". db_getsession('DB_instit') ." and r70_estrut IN ('{$slotacoes}')";
            break;
        }

        $sWhereServidores .= " and rh02_lota in ({$sCodigolotacoes}) ";
      }

      $sSqlServidores            = $oDaoRhPessoalMov->sql_query_file(null, null, 'rh02_regist', null, $sWhereServidores);
      $sSqlDuploVinculo          = $oDaoRhPessoalMov->sql_servidores_duplo_vinculo_rescindidos($sSqlServidores, DBPessoal::getAnoFolha(),DBPessoal::getMesFolha());
      $rsServidoresDuploVinculo  = db_query($sSqlDuploVinculo);
      
      if (!$rsServidoresDuploVinculo) {
        throw new DBException("Ocorreu um erro ao buscar as matrículas");
      }

      $aMatriculasDuploVinculo = db_utils::makeCollectionFromRecord($rsServidoresDuploVinculo, function($oResultado) {
        return $oResultado->rh01_regist;
      });

      rsort($aMatriculasDuploVinculo);

      $oRetorno->aServidores = $aMatriculasDuploVinculo;
      break;
    case 'validaComparativoFerias':

      /**
       * Verifica se o compartivo de férias esta ativo e se esta sendo realizado o cálculo de salário.
       */
      $oParametrosPessoal = ParametrosPessoalRepository::getParametros(DBPessoal::getCompetenciaFolha(), InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));

      if ($oParametrosPessoal->isComparativo()) {

        $iAnoFolha    = DBPessoal::getAnoFolha();
        $iMesFolha    = DBPessoal::getMesFolha();
        $iInstituicao = db_getsession('DB_instit');

        if (isset($oParam->iMatricula)){
          $sMatriculas  = $oParam->iMatricula;
        } 

        if (isset($oParam->aMatriculas)) {
          $sMatriculas  = implode(',', $oParam->aMatriculas);
        }

        /**
         * Verifica se todos os servidores do ponto de férias estão com salário calculado.
         */
        $oDaPontoFe     = new cl_pontofe();
        $sWherePontoFe  = "     r29_anousu = {$iAnoFolha}                                ";
        $sWherePontoFe .= " and r29_mesusu = {$iMesFolha}                                ";
        $sWherePontoFe .= " and r29_instit = {$iInstituicao}                             ";
        $sWherePontoFe .= " and r29_regist in ({$sMatriculas})                           ";
        $sWherePontoFe .= " and r29_regist not in (select r14_regist                     ";
        $sWherePontoFe .= "                          from gerfsal                        ";
        $sWherePontoFe .= "                         where r14_anousu = {$iAnoFolha}      ";
        $sWherePontoFe .= "                           and r14_mesusu = {$iMesFolha}      ";
        $sWherePontoFe .= "                           and r14_instit = {$iInstituicao} ) ";
        $sSqlPontoFe    = $oDaPontoFe->sql_query_file(null, null, null, null, null, 'distinct r29_regist', null, $sWherePontoFe);
        $rsPontoFe      = db_query($sSqlPontoFe);

        if (!$rsPontoFe) {
          throw new DBException(_M(MENSAGENS . 'erro_ponto_ferias'));
        }

        if (pg_num_rows($rsPontoFe) > 0) {
          throw new DBException(_M(MENSAGENS . 'falta_calcular_salario'));
        }
      }

      break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);