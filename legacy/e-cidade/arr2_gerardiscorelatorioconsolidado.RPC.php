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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_app.utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_libpessoal.php";
require_once "dbforms/db_funcoes.php";
require_once "libs/JSON.php";
require_once "std/DBDate.php";
require_once "std/db_stdClass.php";
require_once "libs/exceptions/DBException.php";
require_once "libs/exceptions/BusinessException.php";
require_once "libs/exceptions/ParameterException.php";
require_once "libs/exceptions/FileException.php";

$oJson     = new services_json();
$oParam    = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno  = new db_stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

$lErro   = false;

try {
  
  switch ($oParam->sExec) {

    case "gerarRelatorio":
     
      db_inicio_transacao();
      
      $iInstit = db_getsession('DB_instit');
      
      $oConsolidacaoDebitos          = db_utils::getDao('consolidacaodebitos');
      $oConsolidacaoDebitosRegistros = db_utils::getDao('consolidacaodebitosregistros');
      
      // Verifica se foi selecionado algum relatório.
      if (empty($oParam->aSelecionados)) {
        throw new Exception('Nenhum relatório selecionado para geração.');
      }
      
      /*
       * Validação para verificar se o período incial e final foram preenchidos
       * quando os relatórios de 1 a 6 foram selecionados.
       */
      foreach ($oParam->aSelecionados as $oRelatorio) {
        
        if ($oRelatorio->iCodigoRelatorio <= 6 && (empty($oParam->dPeriodoInicial) || empty($oParam->dPeriodoFinal))) {
          throw new Exception('Período inicial e final devem ser informados');
        }
        
        if ($oRelatorio->iCodigoRelatorio == 7 && empty($oParam->dDataCalculo)) {
          throw new Exception('Data de cálculo deve ser informado');
        }
        
      }
      
      $oConsolidacaoDebitosRegistros->excluir(null, 'true');
      $oConsolidacaoDebitos->excluir(null, 'true');
      
      $oConsolidacaoDebitos->k161_datageracao          = date('Y-m-d', db_getsession('DB_datausu'));
      $oConsolidacaoDebitos->k161_usuario              = db_getsession('DB_id_usuario');
      $oConsolidacaoDebitos->k161_filtrosselecionados  = $oParam->dPeriodoInicial . "|" .
                                                         $oParam->dPeriodoFinal . "|" .
                                                         $oParam->dDataCalculo;
      
      $iCodConsolidacaoDebitos = $oConsolidacaoDebitos->incluir(null);
      
      if ($oConsolidacaoDebitos->erro_status == 0) {
      
        throw new Exception($oConsolidacaoDebitos->erro_msg);
      }
     
      /*
       * Relatórios de 1 a 6 utilizar $oParam->dPeriodoInicial e $oParam->dPeriodoFinal.
       * Relatório 7 utilizar $oParam->dDataCalculo.
       */
      foreach ($oParam->aSelecionados as $oRelatorio) {
        
        $iTipoRelatorio              = null;
        $iReceitaOrcamentaria        = null;
        $iReceitTesouraria           = null;
        $sDescricaoReceitaTesouraria = "";
        $fValorHistorico             = null;
        $fValorCorrigido             = null;
        $fValorMulta                 = null;
        $fValorJuro                  = null;
        $fValorTotal                 = null;
        $fValorAPagar                = null;
        $fValorPago                  = null;
        $fDescontoConcedido          = null;
        
        switch ($oRelatorio->iCodigoRelatorio) {
          
          // 1 - Descontos concedidos por regra
          case 1:
            
            $oDaoArrepaga  = db_utils::getDao('arrepaga');
            
            
            $dDataInicial  = db_formatar($oParam->dPeriodoInicial, "xxxv");
            $dDataFinal    = db_formatar($oParam->dPeriodoFinal, "xxxv");
            
            $sSqlDescontos = $oDaoArrepaga->sql_queryDescontoConcedidoPorRegraAgrupado($dDataInicial, $dDataFinal, true);
            $rsDescontos   = $oDaoArrepaga->sql_record($sSqlDescontos);
            
            $aDescontos    = db_utils::getCollectionByRecord($rsDescontos);
            
            foreach ($aDescontos as $oDesconto) {
              
              $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $oDesconto->receita_orcamento           ; 
              $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $oDesconto->receita_tesouraria          ; 
              $oConsolidacaoDebitosRegistros->k162_descricao           = $oDesconto->descricao_receita_tesouraria;
              $oConsolidacaoDebitosRegistros->k162_valorhistorico      = $oDesconto->valor_historico;
              $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = $oDesconto->valor_corrigido;
              $oConsolidacaoDebitosRegistros->k162_multa               = $oDesconto->multa;
              $oConsolidacaoDebitosRegistros->k162_juros               = $oDesconto->juros;
              $oConsolidacaoDebitosRegistros->k162_total               = $oDesconto->total;
              $oConsolidacaoDebitosRegistros->k162_valorpagar          = $oDesconto->valor_pagar;
              $oConsolidacaoDebitosRegistros->k162_valorpago           = $oDesconto->valor_pago;
              $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = $oDesconto->desconto;
              $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = 1;
              $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
              
              $oConsolidacaoDebitosRegistros->incluir(null);
              
              if ($oConsolidacaoDebitosRegistros->erro_status == "0") {
                throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
              }
              
            }    
           
          break;
            
          
          case 2:
           
            $iTipoRelatorio = 2; /* 2 - Débitos cancelados */
            
            $oCancDebitosProc = db_utils::getDao('cancdebitosproc');
            
            $sCampos  = " round(SUM(k00_valor), 2)                        as vlrhistorico,         \n";
            $sCampos .= " round(SUM(k24_vlrcor), 2)                       as vlrcorrigido,         \n";
            $sCampos .= " round(SUM(k24_juros), 2)                        as vlrjuro,              \n";
            $sCampos .= " round(SUM(k24_multa), 2)                        as vlrmulta,             \n";
            $sCampos .= " round(SUM(k24_vlrcor) + SUM(k24_juros) +                                 \n";
            $sCampos .= " SUM(k24_multa)  - SUM(k24_desconto), 2)   as vlrtotal,                   \n";
            $sCampos .= " tabrec.k02_codigo                     as receittesouraria,               \n";
            $sCampos .= " tabrec.k02_drecei                     as dscreceitatesouraria,           \n";
            $sCampos .= " taborc.k02_estorc                     as receitaorcamentaria             \n";
            
            $sWhere  = "      k23_data between '".db_formatar($oParam->dPeriodoInicial, "xxxv")."' \n";
            $sWhere .= "                   and '".db_formatar($oParam->dPeriodoFinal, "xxxv")."'   \n";
            $sWhere .= "  and k00_valor <> 0                                                       \n";
            
            $sWhere .= "group by tabrec.k02_codigo, tabrec.k02_drecei, taborc.k02_estorc \n";
            
            $sSqlCancDebitosProc = $oCancDebitosProc->sql_query_debitos_cancelados($sCampos, null, $sWhere);
            
            $rsCancDebitosProc   = $oCancDebitosProc->sql_record($sSqlCancDebitosProc);
            $aCancDebitosProc    = db_utils::getColectionByRecord($rsCancDebitosProc);
            
            foreach ( $aCancDebitosProc as $oDebitoCancelado ) {
              
              $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $oDebitoCancelado->receitaorcamentaria;
              $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $oDebitoCancelado->receittesouraria;
              $oConsolidacaoDebitosRegistros->k162_descricao           = $oDebitoCancelado->dscreceitatesouraria;
              $oConsolidacaoDebitosRegistros->k162_valorhistorico      = $oDebitoCancelado->vlrhistorico;
              $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = $oDebitoCancelado->vlrcorrigido;
              $oConsolidacaoDebitosRegistros->k162_multa               = $oDebitoCancelado->vlrmulta;
              $oConsolidacaoDebitosRegistros->k162_juros               = $oDebitoCancelado->vlrjuro;
              $oConsolidacaoDebitosRegistros->k162_total               = $oDebitoCancelado->vlrtotal;
              $oConsolidacaoDebitosRegistros->k162_valorpagar          = null;
              $oConsolidacaoDebitosRegistros->k162_valorpago           = null;
              $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = null;
              $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = $iTipoRelatorio;
              $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
              
              $oConsolidacaoDebitosRegistros->incluir(null);
              
              if ($oConsolidacaoDebitosRegistros->erro_status == 0) {
                
                throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
              }
            }
            
          break;
          
          
          case 3:
          
            $iTipoRelatorio = 3; /* 3 - Prescrição de dívida */
            
            $oArrePrescr = db_utils::getDao('arreprescr');
            
            $sCampos  = " SUM(k30_valor)                        as vlrhistorico,         \n";
            $sCampos .= " SUM(k30_vlrcorr)                      as vlrcorrigido,         \n";
            $sCampos .= " SUM(k30_multa)                        as vlrmulta,             \n";
            $sCampos .= " SUM(k30_vlrjuros)                     as vlrjuro,              \n";
            $sCampos .= " (SUM(k30_vlrcorr) + SUM(k30_multa) +                           \n";
            $sCampos .= " SUM(k30_vlrjuros))                    as vlrtotal,             \n";
            $sCampos .= " tabrec.k02_codigo                     as receittesouraria,     \n";
            $sCampos .= " taborc.k02_estorc                     as receitaorcamentaria,  \n";
            $sCampos .= " tabrec.k02_drecei                     as dscreceitatesouraria  \n";
           
            $sWhere  = "     prescricao.k31_data between '" . db_formatar($oParam->dPeriodoInicial, "xxxv") . "' \n";
            $sWhere .= "                             and '" . db_formatar($oParam->dPeriodoFinal, "xxxv") . "'   \n";
            $sWhere .= " and prescricao.k31_instit     = {$iInstit}                                              \n";
            $sWhere .= " and arreprescr.k30_sequencial = ( select max(a.k30_sequencial) as seq                   \n";
            $sWhere .= "                                     from arreprescr a                                   \n";
            $sWhere .= "                                    where a.k30_numpre = arreprescr.k30_numpre           \n";
            $sWhere .= "                                      and a.k30_numpar = arreprescr.k30_numpar           \n";
            $sWhere .= "                                      and a.k30_receit = arreprescr.k30_receit )         \n";
            $sWhere .= " and k30_anulado  = 'f'                                                                  \n";
            $sWhere .= "group by receitTesouraria, receitaorcamentaria, dscreceitatesouraria                     \n";
            
            $sSqlArrePrescr      = $oArrePrescr->sql_query_divida_prescrita($sCampos, null, $sWhere);
           
            $rsDividasPrescritas = $oArrePrescr->sql_record($sSqlArrePrescr);
            $aDividasPrescritas  = db_utils::getColectionByRecord($rsDividasPrescritas);
            
            foreach ( $aDividasPrescritas as $oDividasPrescritas ) {
              
              $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $oDividasPrescritas->receitaorcamentaria;
              $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $oDividasPrescritas->receittesouraria;
              $oConsolidacaoDebitosRegistros->k162_descricao           = $oDividasPrescritas->dscreceitatesouraria;
              $oConsolidacaoDebitosRegistros->k162_valorhistorico      = $oDividasPrescritas->vlrhistorico;
              $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = $oDividasPrescritas->vlrcorrigido;
              $oConsolidacaoDebitosRegistros->k162_multa               = $oDividasPrescritas->vlrmulta;
              $oConsolidacaoDebitosRegistros->k162_juros               = $oDividasPrescritas->vlrjuro;
              $oConsolidacaoDebitosRegistros->k162_total               = $oDividasPrescritas->vlrtotal;
              $oConsolidacaoDebitosRegistros->k162_valorpagar          = null;
              $oConsolidacaoDebitosRegistros->k162_valorpago           = null;
              $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = null;
              $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = $iTipoRelatorio;
              $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
              
              $oConsolidacaoDebitosRegistros->incluir(null);
              
              if ($oConsolidacaoDebitosRegistros->erro_status == 0) {
              
                throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
              }
            }
           
          break;
          
          case 4:
            
             /* 4 - Inscrição de dívida  */
            
            $oDaoDivida = db_utils::getDao('divida');
            
            $aDadosResumo = $oDaoDivida->getResumoDeReceitas(db_formatar($oParam->dPeriodoInicial, "xxxv"),
                                                                          db_formatar($oParam->dPeriodoFinal, "xxxv"));
            
            foreach ( $aDadosResumo as $sAgrupa => $aDadosAgrupa ) {
            
              foreach ( $aDadosAgrupa as $sCampoAgrupa => $aCampoAgrupa) {
               
                foreach ( $aCampoAgrupa as $sReceitas => $oDados) {
                 
                  $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['codigreceitorcamentaria'];
                  $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['codigreceittesouraria'];
                  $oConsolidacaoDebitosRegistros->k162_descricao           = $aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['descrreceit'];
                  $oConsolidacaoDebitosRegistros->k162_valorhistorico      = db_formatar($aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['nVlrHist'], "p");
                  $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = db_formatar($aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['nVlrCorr'], "p");
                  $oConsolidacaoDebitosRegistros->k162_multa               = db_formatar($aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['nMulta'], "p");
                  $oConsolidacaoDebitosRegistros->k162_juros               = db_formatar($aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['nJuros'], "p");
                  $oConsolidacaoDebitosRegistros->k162_total               = db_formatar($aDadosResumo[$sAgrupa][$sCampoAgrupa][$sReceitas]['nTotal'], "p");
                  $oConsolidacaoDebitosRegistros->k162_valorpagar          = null;
                  $oConsolidacaoDebitosRegistros->k162_valorpago           = null;
                  $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = null;
                  $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = ($sAgrupa=="aLongoPrazo" ? '5' : '4');
                  $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
                  
                  $oConsolidacaoDebitosRegistros->incluir(null);
                  
                  if ($oConsolidacaoDebitosRegistros->erro_status == 0) {
                   
                   throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
                  }
                 
                }
              }
            }
           
           
          break;
             
          // 5 - Pagamento geral
          case 5:
            
            $oDaoArrepaga  = db_utils::getDao('arrepaga');
            
            $dDataInicial  = db_formatar($oParam->dPeriodoInicial, "xxxv");
            $dDataFinal    = db_formatar($oParam->dPeriodoFinal, "xxxv");
            
            $sSqlPagamentos = $oDaoArrepaga->sql_queryPagamentosPorPeriodo($dDataInicial, $dDataFinal);
            $rsPagamentos   = $oDaoArrepaga->sql_record($sSqlPagamentos);
            
            $aPagamentos    = db_utils::getCollectionByRecord($rsPagamentos);
            
            foreach ($aPagamentos as $oPagamento) {
            
              $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $oPagamento->receita_orcamento                                         ;
              $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $oPagamento->receita_tesouraria                                        ;
              $oConsolidacaoDebitosRegistros->k162_descricao           = $oPagamento->descricao_receita                                         ;
              $oConsolidacaoDebitosRegistros->k162_valorhistorico      = $oPagamento->valor_historico                                           ;
              $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = $oPagamento->valor_corrigido                                           ;
              $oConsolidacaoDebitosRegistros->k162_multa               = $oPagamento->multas                                                    ;
              $oConsolidacaoDebitosRegistros->k162_juros               = $oPagamento->juros                                                     ;
              $oConsolidacaoDebitosRegistros->k162_total               = $oPagamento->valor_corrigido + $oPagamento->multas + $oPagamento->juros;
              $oConsolidacaoDebitosRegistros->k162_valorpagar          = '0'                                                                      ;
              $oConsolidacaoDebitosRegistros->k162_valorpago           = '0'                                                                      ;
              $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = $oPagamento->valor_desconto                                            ;
              $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = 6                                                                      ;
              $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial                                 ;
              
              $oConsolidacaoDebitosRegistros->incluir(null);
            
              if ($oConsolidacaoDebitosRegistros->erro_status == "0") {
                throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
              }
            
            }
             
             
          break;
          
          
          case 6:
           
           $iTipoRelatorio = 7; /* 6 - Descontos concedidos quota única */
           
           $oDaoReciboPaga = db_utils::getDao('recibopaga');
           
           $sSqlDbConfig  = " select db21_regracgmiss,   \n";
           $sSqlDbConfig .= "        db21_regracgmiptu   \n";
           $sSqlDbConfig .= "   from db_config           \n";
           $sSqlDbConfig .= "  where codigo = {$iInstit} \n";
           
           $rsSqlDbConfig = db_query($sSqlDbConfig);
           $oDbConfig     = db_utils::fieldsMemory($rsSqlDbConfig, 0);
           $iRegra        = $oDbConfig->db21_regracgmiptu;
           
           $sSqldescontoConcedUnica =
              $oDaoReciboPaga->sql_query_descontoConced_cotaUnica($iRegra,
                                                                  date('Y', db_getsession('DB_datausu')),
                                                                  db_formatar($oParam->dPeriodoInicial, "xxxv"),
                                                                  db_formatar($oParam->dPeriodoFinal, "xxxv"));
           
           $rsDescontosCotaUnica = $oDaoReciboPaga->sql_record($sSqldescontoConcedUnica);
           $aDescontosCotaUnica  = db_utils::getColectionByRecord($rsDescontosCotaUnica);
           
           $aDadosDesconto = array();
           
           foreach ( $aDescontosCotaUnica as $oDescontosCotaUnica ) {
             
            if ( !isset($aDadosDesconto[$oDescontosCotaUnica->receita]) ) {
           
             $aDadosDesconto[$oDescontosCotaUnica->receita]['dscreceitatesouraria'] = $oDescontosCotaUnica->descricao;
             $aDadosDesconto[$oDescontosCotaUnica->receita]['receitaorcamentaria']  = $oDescontosCotaUnica->k02_estorc;
             $aDadosDesconto[$oDescontosCotaUnica->receita]['VlrCalc']              = $oDescontosCotaUnica->vlrcalculado;
             $aDadosDesconto[$oDescontosCotaUnica->receita]['VlrDesc']              = $oDescontosCotaUnica->vlrdesconto;
             $aDadosDesconto[$oDescontosCotaUnica->receita]['VlrPago']              = $oDescontosCotaUnica->vlrpago;
           
            } else {
           
             $aDadosDesconto[$oDescontosCotaUnica->receita]['VlrCalc']             += $oDescontosCotaUnica->vlrcalculado;
             $aDadosDesconto[$oDescontosCotaUnica->receita]['VlrDesc']             += $oDescontosCotaUnica->vlrdesconto;
             $aDadosDesconto[$oDescontosCotaUnica->receita]['VlrPago']             += $oDescontosCotaUnica->vlrpago;
            }
             
           }
           
           foreach ( $aDadosDesconto as $iCodReceita => $aDescontos ) {
             
            $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $aDescontos['receitaorcamentaria'];
            $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $iCodReceita;
            $oConsolidacaoDebitosRegistros->k162_descricao           = $aDescontos['dscreceitatesouraria'];
            $oConsolidacaoDebitosRegistros->k162_valorhistorico      = null;
            $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = null;
            $oConsolidacaoDebitosRegistros->k162_multa               = null;
            $oConsolidacaoDebitosRegistros->k162_juros               = null;
            $oConsolidacaoDebitosRegistros->k162_total               = null;
            $oConsolidacaoDebitosRegistros->k162_valorpagar          = $aDescontos['VlrCalc'];
            $oConsolidacaoDebitosRegistros->k162_valorpago           = $aDescontos['VlrPago'];
            $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = $aDescontos['VlrDesc'];
            $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = $iTipoRelatorio;
            $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
           
            $oConsolidacaoDebitosRegistros->incluir(null);
           
            if ($oConsolidacaoDebitosRegistros->erro_status == 0) {
           
             throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
            }
           }
            
          break;
             
          // 7 - Resumo geral da dívida
          case 7:
             
            $oDaoDivida = db_utils::getDao('divida');
            
            $aResumoGeralDivida = $oDaoDivida->processamentoResumoGeralDivida($oParam->dDataCalculo);
            
            foreach ($aResumoGeralDivida['aCurtoPrazo'] as $aCurtoPrazo) {
              
              foreach ($aCurtoPrazo as $iCodigoReceita => $oRegistro) {
                
                $oDaoTaborc               = db_utils::getDao('taborc');
                $sSqlTaborc               = $oDaoTaborc->sql_query_file(db_getsession('DB_anousu'), $iCodigoReceita);
                $rsTaborc                 = $oDaoTaborc->sql_record($sSqlTaborc);
                
                if ($oDaoTaborc->numrows == 0) {
                  throw new Exception("Receita orçamentária não configurada para a receita da tesouraria {$iCodigoReceita}.");
                }
                
                $iCodigoReceitaOrcamento  = db_utils::fieldsMemory($rsTaborc, 0)->k02_estorc;
                                
                $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $iCodigoReceitaOrcamento;
                $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $iCodigoReceita;
                $oConsolidacaoDebitosRegistros->k162_descricao           = $oRegistro['sDescricao'];
                $oConsolidacaoDebitosRegistros->k162_valorhistorico      = $oRegistro['nVlrHist'];
                $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = $oRegistro['nVlrCorr'];
                $oConsolidacaoDebitosRegistros->k162_multa               = $oRegistro['nMulta'];
                $oConsolidacaoDebitosRegistros->k162_juros               = $oRegistro['nJuros'];
                $oConsolidacaoDebitosRegistros->k162_total               = $oRegistro['nTotal'];
                $oConsolidacaoDebitosRegistros->k162_valorpagar          = null;
                $oConsolidacaoDebitosRegistros->k162_valorpago           = null;
                $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = null;
                $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = 8;
                $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
                
                $oConsolidacaoDebitosRegistros->incluir(null);
                
                if ($oConsolidacaoDebitosRegistros->erro_status == 0) {
                
                  throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
                  
                }
                
              }
              
            }
            
            foreach ($aResumoGeralDivida['aLongoPrazo'] as $aLongoPrazo) {
            
              foreach ($aLongoPrazo as $iCodigoReceita => $oRegistro) {
                
                $oDaoTaborc               = db_utils::getDao('taborc');
                $sSqlTaborc               = $oDaoTaborc->sql_query_file(db_getsession('DB_anousu'), $iCodigoReceita);
                $rsTaborc                 = $oDaoTaborc->sql_record($sSqlTaborc);
            
                if ($oDaoTaborc->numrows == 0) {
                  throw new Exception("Receita orçamentária não configurada para a receita da tesouraria {$iCodigoReceita}.");
                }
                $iCodigoReceitaOrcamento  = db_utils::fieldsMemory($rsTaborc, 0)->k02_estorc;

                $oConsolidacaoDebitosRegistros->k162_receitaorcamento    = $iCodigoReceitaOrcamento;
                $oConsolidacaoDebitosRegistros->k162_receitatesouraria   = $iCodigoReceita;
                $oConsolidacaoDebitosRegistros->k162_descricao           = $oRegistro['sDescricao'];
                $oConsolidacaoDebitosRegistros->k162_valorhistorico      = $oRegistro['nVlrHist'];
                $oConsolidacaoDebitosRegistros->k162_valorcorrigido      = $oRegistro['nVlrCorr'];
                $oConsolidacaoDebitosRegistros->k162_multa               = $oRegistro['nMulta'];
                $oConsolidacaoDebitosRegistros->k162_juros               = $oRegistro['nJuros'];
                $oConsolidacaoDebitosRegistros->k162_total               = $oRegistro['nTotal'];
                $oConsolidacaoDebitosRegistros->k162_valorpagar          = null;
                $oConsolidacaoDebitosRegistros->k162_valorpago           = null;
                $oConsolidacaoDebitosRegistros->k162_descontoconcedido   = null;
                $oConsolidacaoDebitosRegistros->k162_tiporelatorio       = 9;
                $oConsolidacaoDebitosRegistros->k162_consolidacaodebitos = $oConsolidacaoDebitos->k161_sequencial;
            
                $oConsolidacaoDebitosRegistros->incluir(null);
            
                if ($oConsolidacaoDebitosRegistros->erro_status == "0") {
            
                  throw new Exception($oConsolidacaoDebitosRegistros->erro_msg);
            
                }
            
              }
            
            }
            
          break;
          
        }
        
      }
      
      db_fim_transacao(false);
      
    break;
  
  }
    
} catch (Exception $eErro) {
  
  $oRetorno->iStatus    = 2;
  $oRetorno->sMensagem = urlEncode($eErro->getMessage());
  
  db_fim_transacao(true);
  
}

echo $oJson->encode($oRetorno);