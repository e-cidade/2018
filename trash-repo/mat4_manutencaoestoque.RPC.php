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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("model/ordemCompra.model.php");
require_once("libs/exceptions/BusinessException.php");

$iInstituicaoSessao = db_getsession("DB_instit");
$oDaoMatEstoqueIni  = db_utils::getDao("matestoqueini");
$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->message  = "";

switch ($oParam->exec) {

  case "salvarDadosMovimentacao":

    try {

      db_inicio_transacao();
      db_query("select fc_putsession('DB_habilita_trigger_movimentacao_estoque', '1'::text);");

      /**
       * Definimos que valor vamos usar (entrada ou saida)
       */
      $nValorUnitario = $oParam->nValorUnitarioEntrada;
      $iQuantidade    = $oParam->iQuantidadeEntrada;
      if ($oParam->nValorUnitarioEntrada == 0) {

        $nValorUnitario = $oParam->nPrecoMedioSaida;
        $iQuantidade    = $oParam->iQuantidadeSaida;
      }

      /**
       * Configuramos a data para o formato banco (yyyy-mm-dd)
       */
      $oParam->dtMovimentacao = implode("-", array_reverse(explode("/", $oParam->dtMovimentacao)));

      /**
       * Salvamos os dados da tabela 'matestoqueini'
       */
      $oDaoMatEstoqueIni->m80_codigo   = $oParam->iCodigoLancamento;
      $oDaoMatEstoqueIni->m80_data     = $oParam->dtMovimentacao;
      $oDaoMatEstoqueIni->m80_hora     = $oParam->sHoraMovimentacao;
      $oDaoMatEstoqueIni->alterar($oParam->iCodigoLancamento);
      if ($oDaoMatEstoqueIni->erro_status == "0") {

        $oDaoMatEstoqueIni->erro_msg = str_replace("\\n", "\n", $oDaoMatEstoqueIni->erro_msg);
        throw new BusinessException("Não foi possível alterar os dados da tabela 'matestoqueini'.\n\n{$oDaoMatEstoqueIni->erro_msg}");
      }

      /**
       * Buscamos o vínculo do estoque com o item
       */
      $oDaoMatEstoqueIniMei    = db_utils::getDao('matestoqueinimei');
      $sWhereMatEstoqueIniMei  = "m82_matestoqueini = {$oParam->iCodigoLancamento}";
      $sWhereMatEstoqueIniMei .= " and m82_matestoqueitem = {$oParam->iCodigoItemEstoque}";
      $sSqlBuscaVinculoItem    = $oDaoMatEstoqueIniMei->sql_query(null, "matestoqueini.*, m70_codmatmater, matestoqueitem.*, m82_codigo", null, $sWhereMatEstoqueIniMei);
      $rsBuscaVinculoItem      = $oDaoMatEstoqueIniMei->sql_record($sSqlBuscaVinculoItem);
      if ($oDaoMatEstoqueIniMei->erro_status == "0") {

        $oDaoMatEstoqueIniMei->erro_msg = str_replace("\\n", "\n", $oDaoMatEstoqueIniMei->erro_msg);
        throw new BusinessException("Não foi possível buscar o vínculo do lançamento com o item.\n\n{$oDaoMatEstoqueIniMei->erro_msg}");
      }

      /**
       * Alteramos os dados da tabela matestoqueitem
       */
      $oDadoMatEstoqueItem             = db_utils::fieldsMemory($rsBuscaVinculoItem, 0);
      $oDaoMatEstoqueItem              = db_utils::getDao('matestoqueitem');
      $oDaoMatEstoqueItem->m71_data    = $oParam->dtMovimentacao;
      $oDaoMatEstoqueItem->m71_codlanc = $oDadoMatEstoqueItem->m71_codlanc;
      if ($oParam->iTipoMovimento == 1) {

        $oDaoMatEstoqueItem->m71_quant = $iQuantidade;
        $oDaoMatEstoqueItem->m71_valor = round($nValorUnitario * $iQuantidade, 2);

      } else if ($oParam->iTipoMovimento == 2) {

        $sWhereMatEstoqueIniMei  = "m82_codigo <> {$oDadoMatEstoqueItem->m82_codigo}";
        $sWhereMatEstoqueIniMei .= " and m82_matestoqueitem = {$oParam->iCodigoItemEstoque}";
        $sWhereMatEstoqueIniMei .= " and m81_tipo = 2";
        $sSqlBuscaVinculoItem    = $oDaoMatEstoqueIniMei->sql_query(null,
                                                                   "coalesce(sum(m82_quant), 0) as quantidade",
                                                                    null,
                                                                    $sWhereMatEstoqueIniMei
                                                                   );

        $rsQuantidadeAtendidada = $oDaoMatEstoqueIniMei->sql_record($sSqlBuscaVinculoItem);
        $iQuantidadeAtendida    = 0;
        if ($oDaoMatEstoqueIniMei->numrows > 0) {
          $iQuantidadeAtendida = db_utils::fieldsMemory($rsQuantidadeAtendidada, 0)->quantidade;
        }
        $oDaoMatEstoqueItem->m71_quantatend = $iQuantidadeAtendida + $iQuantidade;
      }

      /*$oDaoMatEstoqueItem->alterar($oDadoMatEstoqueItem->m71_codlanc);
      if ($oDaoMatEstoqueItem->erro_status == "0") {

        $oDaoMatEstoqueItem->erro_msg = str_replace("\\n", "\n", $oDaoMatEstoqueItem->erro_msg);
        throw new BusinessException("Não foi possível alterar os dados da tabela 'matestoqueitem'.\n\n{$oDaoMatEstoqueItem->erro_msg}");
      }
      */
      /**
       * Alteramos a quantidade na tabela matestoqueinimei
       */
      $oDaoMatEstoqueIniMei->m82_quant  = $iQuantidade;
      $oDaoMatEstoqueIniMei->m82_codigo = $oDadoMatEstoqueItem->m82_codigo;
      $oDaoMatEstoqueIniMei->alterar($oDadoMatEstoqueItem->m82_codigo);
      if ($oDaoMatEstoqueIniMei->erro_status == "0") {

        $oDaoMatEstoqueIniMei->erro_msg = str_replace("\\n", "\n", $oDaoMatEstoqueIniMei->erro_msg);
        throw new BusinessException("Não foi possível alterar a quantidade da tabela matestoqueinimei.\n\n{$oDaoMatEstoqueIniMei->erro_msg}");
      }

      $timestamp  = "to_timestamp('{$oDadoMatEstoqueItem->m80_data}'::date|| ' ' || ";
      $timestamp .= "'{$oDadoMatEstoqueItem->m80_hora}'::time, 'YYYY-MM-DD HH24:MI:SS')";

      $sSqlMovimentacoes = "select m82_quant,";
      $sSqlMovimentacoes .= "      m82_matestoqueini,                                                                 ";
      $sSqlMovimentacoes .= "      m82_codigo                                                                         ";
      $sSqlMovimentacoes .= " from matestoqueinimei                                                                   ";
      $sSqlMovimentacoes .= "      inner join matestoqueini  on m82_matestoqueini  = m80_codigo                       ";
      $sSqlMovimentacoes .= "      inner join matestoqueitem on m82_matestoqueitem = m71_codlanc                      ";
      $sSqlMovimentacoes .= "      inner join matestoque     on m70_codigo         = m71_codmatestoque                ";
      $sSqlMovimentacoes .= "where to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') >= {$timestamp} ";
      $sSqlMovimentacoes .= "  and m82_codigo <> {$oDadoMatEstoqueItem->m82_codigo}                                    ";
      $sSqlMovimentacoes .= "  and m70_codmatmater = {$oDadoMatEstoqueItem->m70_codmatmater}                          ";
      $sSqlMovimentacoes .= "order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS')              ";
      $rsOutrosMovimentos = db_query($sSqlMovimentacoes);
      $iTotalLinhas       = pg_num_rows($rsOutrosMovimentos);
      for ($i = 0; $i < $iTotalLinhas; $i++) {

        $o    = db_utils::fieldsMemory($rsOutrosMovimentos, $i);
        $sSql = "select fc_calculaprecomedio({$o->m82_codigo}, {$o->m82_matestoqueini}, {$o->m82_quant}, false);";
        db_query($sSql);
      }
      $oRetorno->message = urlencode("Movimentação salva com sucesso.");
      db_fim_transacao(false);

    } catch (BusinessException $eBusiness) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusiness->getMessage());
      db_fim_transacao(true);
    }

    /**
     * Deletamos a sessão criada anteriormente para desabilitar a trigger
     * Desta forma a trigger na MATESTOQUEINI permanece funcionando
     */
    $rsDeletarSessao = db_query("select fc_delsession('DB_habilita_trigger_movimentacao_estoque');");

    break;

  case "buscarDadosLancamento":

    try {

      $sCamposQuery  = " m80_codigo,    ";
      $sCamposQuery .= " m80_coddepto,  ";
      $sCamposQuery .= " m80_data,      ";
      $sCamposQuery .= " m80_hora,      ";
      $sCamposQuery .= " m82_quant,     ";
      $sCamposQuery .= " m89_precomedio as preco_medio, ";
      $sCamposQuery .= " m89_valorunitario, ";
      $sCamposQuery .= " deptousu.descrdepto,    ";
      $sCamposQuery .= " nome,          ";
      $sCamposQuery .= " login,         ";
      $sCamposQuery .= " m60_descr,     ";
      $sCamposQuery .= " m71_valor,     ";
      $sCamposQuery .= " m71_quant,     ";
      $sCamposQuery .= " m71_codlanc, ";
      $sCamposQuery .= " (m71_valor * m71_quant) as total, ";
      $sCamposQuery .= " m81_codtipo,   ";
      $sCamposQuery .= " m81_descr,     ";
      $sCamposQuery .= " m81_entrada,   ";
      $sCamposQuery .= " m81_tipo       ";

      $sOrderQuery   = "to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'), m80_codigo, m82_codigo";
      $sWhereQuery   = "    deptoest.instit = {$iInstituicaoSessao} ";
      $sWhereQuery  .= "and m80_codigo      = {$oParam->iCodigoLancamento} ";
      $sWhereQuery  .= "and m70_codmatmater = {$oParam->iCodigoMaterial} ";
      $sWhereQuery  .= "and m71_codlanc     = {$oParam->iCodigoItemEstoque} ";
      //$sWhereQuery  .= "and m71_servico is false";
      $sSqlMovimentacao = $oDaoMatEstoqueIni->sql_query_movimentacoes_gerais(null, $sCamposQuery, $sOrderQuery, $sWhereQuery);
      $rsMovimentacao   = $oDaoMatEstoqueIni->sql_record($sSqlMovimentacao);

      if ($oDaoMatEstoqueIni->numrows == 0) {
        throw new BusinessException("Não foi possível buscar os dados do lançamento {$oParam->iCodigoLancamento}.");
      }

      $oDadoMaterial = db_utils::fieldsMemory($rsMovimentacao, 0);

      /**
       * Variaveis configuradas para retorno
       */
      $oRetorno->iCodigoMovimento           = $oDadoMaterial->m80_codigo;
      $oRetorno->iCodigoDepartamento        = $oDadoMaterial->m80_coddepto;
      $oRetorno->sDescricaoDepartamento     = urlencode($oDadoMaterial->descrdepto);
      $oRetorno->iCodigoTipoMovimentacao    = $oDadoMaterial->m81_codtipo;
      $oRetorno->sDescricaoTipoMovimentacao = urlencode($oDadoMaterial->m81_descr);
      $oRetorno->dtMovimentacao             = $oDadoMaterial->m80_data;
      $oRetorno->sHoraMovimentacao          = $oDadoMaterial->m80_hora;
      $oRetorno->iCodigoMaterial            = $oParam->iCodigoMaterial;
      $oRetorno->sDescricaoMaterial         = urlencode($oDadoMaterial->m60_descr);
      $oRetorno->iTipoMovimento             = $oDadoMaterial->m81_tipo;

      /**
       * Objeto com os dados da entrada do material
       */
      $oRetorno->oDadosEntrada               = new stdClass();
      $oRetorno->oDadosEntrada->nValor       = 0;
      $oRetorno->oDadosEntrada->iQuantidade  = 0;
      $oRetorno->oDadosEntrada->nTotal       = 0;

      /**
       * Objeto com os dados da saida do material
       */
      $oRetorno->oDadosSaida                 = new stdClass();
      $oRetorno->oDadosSaida->nValor       = 0;
      $oRetorno->oDadosSaida->iQuantidade  = 0;
      $oRetorno->oDadosSaida->nTotal       = 0;

      if ($oDadoMaterial->m81_tipo == 1) {

        $oRetorno->oDadosEntrada->nValor       = $oDadoMaterial->m89_valorunitario;
        $oRetorno->oDadosEntrada->iQuantidade  = $oDadoMaterial->m82_quant;
        $oRetorno->oDadosEntrada->nTotal       = ($oDadoMaterial->m89_valorunitario * $oDadoMaterial->m82_quant);

      } else {

        $oRetorno->oDadosSaida->nValor       = $oDadoMaterial->preco_medio;
        $oRetorno->oDadosSaida->iQuantidade  = $oDadoMaterial->m82_quant;
        $oRetorno->oDadosSaida->nTotal       = ($oDadoMaterial->preco_medio * $oDadoMaterial->m82_quant);
      }

    } catch (BusinessException $eBusiness) {

      $oRetorno->message = $eBusiness->getMessage();
      $oRetorno->status  = 2;
    }
    break;
}
echo $oJson->encode($oRetorno);