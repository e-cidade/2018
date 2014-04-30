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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");

$oGet          = db_utils::postMemory($_GET);
$iAnousu       = db_getsession("DB_anousu");
$iInstituicao  = db_getsession("DB_instit");


$dtInicial     = $oGet->dtInicial;
$dtFinal       = $oGet->dtFinal;
$iAno          = substr($dtInicial,0,4);

$iOrdem        = $oGet->iOrdenacao;
$sAgrupamento  = $oGet->iAgrupamento;
$lAnalitica    = ($oGet->iTipoImpressao == "1") ? true : false;

$sWhere = "1=1";

//Filtro por conta patrimonial
if(!empty($oGet->sContas) && $sAgrupamento == '3') {
  $sWhere = "c61_reduz in ({$oGet->sContas})";
}

//Filtro por conta de despesa
if(!empty($oGet->sContas) && $sAgrupamento == '2') {
  $sWhere = "c61_reduz in ({$oGet->sContas})";
}

//Verifica Ordem
switch($iOrdem) {

  case '1' :
    $sOrder = " order by m60_codmater asc";
  break;

  case '2' :
    $sOrder = " order by m60_descr asc";
  break;
}

/**
 * Switch para definir a função chamada para Agrupamento
 * $sAgrupamento            = value provindo do select de agrupamento, da tela de filtro
 * $sFuncaoTipoAgrupamento  = define a função chamada para agrupar um material
 * $lContaPatrimonial       = define se, caso seja agrupamento por conta, usará conta patrimonial ou não
 *
 * o parâmetro  $lContaPatrimonial é usado na chamada agruparPorGrupoSubGrupo, para que ambas chamadas
 * tenham o mesmo número de parâmetros
 */
switch($sAgrupamento) {

  case '1':

    $sTipoRelatorio          = "RELATÓRIO DE GRUPOS E SUBGRUPOS";
    $sFuncaoTipoAgrupamento  = "agruparPorGrupoSubGrupo";
    $sFuncaoImpressao        = "imprimirPdfGrupos";
    $lContaPatrimonial       = false;
  break;

  case '2':

    $sTipoRelatorio          = "RELATÓRIO DE CONTAS DE DESPESA";
    $sFuncaoTipoAgrupamento  = "agruparPorConta";
    $sFuncaoImpressao        = "imprimirPdfContas";
    $lContaPatrimonial       = false;
  break;

  case '3':

    $sTipoRelatorio          = "RELATÓRIO DE CONTAS PATRIMONIAIS";
    $sFuncaoTipoAgrupamento  = "agruparPorConta";
    $sFuncaoImpressao        = "imprimirPdfContas";
    $lContaPatrimonial       = true;
  break;

  case '0':

    $sTipoRelatorio          = "RELATÓRIO DE MATERIAIS";
    $sFuncaoTipoAgrupamento  = "";

    break;
}

/**
 * Busca descrição do Município da Instituição
 */
$rsInstituicao          = db_query("select munic from db_config where codigo = {$iInstituicao} ");
$oInstituicao           = db_utils::fieldsmemory($rsInstituicao,0);
$sDescricaoInstituicao  = "MUNICÍPIO DE " . $oInstituicao->munic;

/**
 * Inicia Impressão do PDF
 */
$head2              =  $sDescricaoInstituicao;
$head3              = "RELATÓRIO DE MOVIMENTAÇÕES";
$head4              = $sTipoRelatorio;
$dtInicialFormatada = db_formatar($dtInicial,"d");
$dtFinalFormatada   = db_formatar($dtFinal,"d");
$head5              = "PERÍODO: {$dtInicialFormatada} a {$dtFinalFormatada}";
/**
 *  Seta as propriedades do pdf
 */
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$iAlturaLinha = 5;
$oPdf->AddPage();
$oPdf->SetFont('arial', '', 7);

$rsMateriais = buscaMateriais($dtInicial, $dtFinal, $iInstituicao, $sOrder, $oGet->sAlmoxarifados);
$aItens      = contabilizaMateriais($rsMateriais, $dtInicial, $sAgrupamento);

if (isset($sFuncaoImpressao)) {

  $oAgrupamento    = $sFuncaoTipoAgrupamento($aItens, $lContaPatrimonial, $iAno,  $sWhere);
  $sFuncaoImpressao($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica);
  imprimirTotal($oPdf, $iAlturaLinha, $oAgrupamento);
} else {
  imprimirItens($oPdf, $iAlturaLinha, $aItens);
}



/**
 * Busca movimentações de estoque em determinado período, para determinada instituição
 * @param date     $dtInicial    - data inicial do período
 * @param date     $dtFinal      - data final do período
 * @param integer  $iInstituicao - código da instituição
 * @param string   $sAlmoxarifados - lista de almoxarifados selecionados pelo usuário
 */
function buscaMateriais($dtInicial, $dtFinal, $iInstituicao, $sOrder, $sAlmoxarifados) {

  db_query("drop table if exists w_materiais_saldo_inicial");

	/**
   * Cria tabela temporária, com o saldo inicial de cada código de material presente na matmater
   */
  $sSql  = " create table w_materiais_saldo_inicial as                                                              ";
  $sSql .= "             select m70_codmatmater as codigo_material_saldo_inicial,                                   ";
  $sSql .= "                    sum(coalesce( case when m81_tipo = 1                                                ";
  $sSql .= "                                 then m82_quant when m81_tipo = 2                                       ";
  $sSql .= "                                 then m82_quant *-1 end, 0)                                             ";
  $sSql .= "                                 ) as quantidade_inicial,                                               ";
  $sSql .= "                    sum(coalesce( case when m81_tipo = 1                                                ";
  $sSql .= "                                 then m82_quant*m89_valorunitario when m81_tipo = 2                     ";
  $sSql .= "                                 then m82_quant*m89_precomedio *-1 end, 0) ) as saldo_inicial           ";
  $sSql .= "               from matestoqueini                                                                       ";
  $sSql .= "                    inner join matestoquetipo     on  m80_codtipo = m81_codtipo                         ";
  $sSql .= "                                                  and m80_codtipo <> 4                                  ";
  $sSql .= "                    inner join matestoqueinimei   on  m82_matestoqueini = m80_codigo                    ";
  $sSql .= "                    inner join matestoqueinimeipm on  m82_codigo = m89_matestoqueinimei                 ";
  $sSql .= "                    inner join matestoqueitem     on  m82_matestoqueitem = m71_codlanc                  ";
  $sSql .= "                    inner join matestoque         on  m71_codmatestoque = m70_codigo                    ";
  $sSql .= "                    inner join db_depart          on  m70_coddepto = coddepto                           ";
  $sSql .= "                    inner join db_almox           on db_almox.m91_depto = db_depart.coddepto            ";
  $sSql .= "             where m80_data < '{$dtInicial}'                                                            ";
  $sSql .= "               and instit = {$iInstituicao}                                                             ";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "             and db_almox.m91_codigo in ({$sAlmoxarifados})                                           ";
  }
  $sSql .= "             group by m70_codmatmater                                                                   ";
  db_query($sSql);


  /**
   *  Cria indice na tabela temporária
   */
  $sSql  = "create index
               w_materiais_saldo_inicial_codigo_material_saldo_inicial_in
                 ON w_materiais_saldo_inicial(codigo_material_saldo_inicial)";
  db_query($sSql);

  $sSql  = " analyze w_materiais_saldo_inicial ";
  db_query($sSql);

  /**
   * Busca todos materiais da instituição que estiverem ativos e não forem serviço
   */
  $sSql  = " select distinct m60_codmater as codigo_material,                                                       ";
  $sSql .= "       m60_descr    as descricao_material,                                                              ";
  $sSql .= "       ( select sum( coalesce((m82_quant::numeric * m89_valorunitario::numeric),0) )                    ";
  $sSql .= "           from matestoqueinimei                                                                        ";
  $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                         ";
  $sSql .= "                                             and m80_data  >= '{$dtInicial}'                            ";
  $sSql .= "                                             and m80_data  <= '{$dtFinal}'                              ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                       ";
  $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                       ";
  $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}   ";
  $sSql .= "                inner join db_almox           on db_almox.m91_depto = db_depart.coddepto                ";
  $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                              ";
  $sSql .= "                                             and m81_codtipo <> 4                                       ";
  $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                      ";
  $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                      ";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                              ";
  }
  $sSql .= "            and m81_tipo = 1 ) as entrada,                                                              ";

  $sSql .= "       ( select coalesce(sum(m82_quant) ,0)                                                             ";
  $sSql .= "           from matestoqueinimei                                                                        ";
  $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                         ";
  $sSql .= "                                             and m80_data  >= '{$dtInicial}'                            ";
  $sSql .= "                                             and m80_data  <= '{$dtFinal}'                              ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                       ";
  $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                       ";
  $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}   ";
  $sSql .= "                inner join db_almox           on db_almox.m91_depto = db_depart.coddepto                ";
  $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                              ";
  $sSql .= "                                             and m81_codtipo <> 4                                       ";
  $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                      ";
  $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                      ";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                              ";
  }
  $sSql .= "            and m81_tipo = 1 ) as quantidade_entrada,                                                   ";

  $sSql .= "       ( select sum( coalesce(m89_valorfinanceiro::numeric,0) )                    ";
  $sSql .= "           from matestoqueinimei                                                                        ";
  $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                         ";
  $sSql .= "                                             and m80_data  >= '{$dtInicial}'                            ";
  $sSql .= "                                             and m80_data  <= '{$dtFinal}'                              ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                       ";
  $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                       ";
  $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}   ";
  $sSql .= "                inner join db_almox           on db_almox.m91_depto = db_depart.coddepto                ";
  $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                              ";
  $sSql .= "                                             and m81_codtipo <> 4                                       ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                      ";
  $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                      ";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                              ";
  }
  $sSql .= "            and m81_tipo = 2 ) as saida,                                                                ";

  $sSql .= "       ( select coalesce(sum(m82_quant) ,0)                                                             ";
  $sSql .= "           from matestoqueinimei                                                                        ";
  $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                         ";
  $sSql .= "                                             and m80_data  >= '{$dtInicial}'                            ";
  $sSql .= "                                             and m80_data  <= '{$dtFinal}'                              ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                       ";
  $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                       ";
  $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}   ";
  $sSql .= "                inner join db_almox           on db_almox.m91_depto = db_depart.coddepto                ";
  $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                              ";
  $sSql .= "                                             and m81_codtipo <> 4                                       ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                      ";
  $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                      ";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                              ";
  }
  $sSql .= "            and m81_tipo = 2 ) as quantidade_saida,                                                     ";


  $sSql .= "       ( select coalesce(sum(case when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant *-1 end ), 0 )  ";
  $sSql .= "           from matestoqueinimei                                                                        ";
  $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                         ";
//   $sSql .= "                                             and m80_data  >= '{$dtInicial}'                            ";
  $sSql .= "                                             and m80_data  <= '{$dtFinal}'                              ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                       ";
  $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                       ";
  $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}   ";
  $sSql .= "                inner join db_almox           on db_almox.m91_depto = db_depart.coddepto                ";
  $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                              ";
  $sSql .= "                                             and m81_codtipo <> 4                                       ";
  $sSql .= "                                                                                                        ";
  $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                      ";
  $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                      ";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                              ";
  }
  $sSql .= "            ) as quantidade_estoque,                                                                    ";

  $sSql .= "       saldo_inicial,                                                                                   ";
  $sSql .= "       quantidade_inicial                                                                               ";
  $sSql .= "                                                                                                        ";
  $sSql .= "  from matmater                                                                                         ";
  $sSql .= "       inner join matestoque                   on m70_codmatmater = m60_codmater                        ";
  $sSql .= "       left  join w_materiais_saldo_inicial si on si.codigo_material_saldo_inicial = m70_codmatmater    ";
  $sSql .= "       inner join db_depart                    on m70_coddepto = coddepto                               ";
  $sSql .= "                                               and instit = {$iInstituicao}                             ";
  $sSql .= "       inner join db_almox           on  db_almox.m91_depto = db_depart.coddepto                        ";
  $sSql .= " where	instit = {$iInstituicao}                                																		    ";
  $sSql .= "				and m60_ativo is true		                                                												";
  if (!empty($sAlmoxarifados)) {
    $sSql .= "		  and db_almox.m91_codigo in ({$sAlmoxarifados})                                                  ";
  }
  $sSql .= " 				and not exists (																																								";
  $sSql .= " 										select 1                                                                            ";
  $sSql .= " 										from matestoqueitem                                                                 ";
  $sSql .= " 											inner join matestoque on matestoque.m70_codigo = matestoqueitem.m71_codmatestoque ";
  $sSql .= " 										where m70_codmatmater = m60_codmater                                                ";
  $sSql .= " 													and m71_servico is true limit 1)           												            ";
  $sSql .= " {$sOrder}  																													 																	";

  $rsMateriais = db_query($sSql);


  if ($rsMateriais == false) {
    db_redireciona("db_erros.php?fechar=true&db_erro=ERRO AO CONSULTAR AS MOVIMENTAÇÕES.");
  }

  if (pg_num_rows($rsMateriais) == 0) {
     db_redireciona("db_erros.php?fechar=true&db_erro=NÃO FORAM ENCONTRADAS MOVIMENTAÇÕES PARA O PERÍODO.");
  }

  $sSql = "drop table w_materiais_saldo_inicial";
  db_query($sSql);

  return $rsMateriais;
}


/**
 * Função contabilizaMateriais
 * Contabiliza cada um dos materiais no período referenciado
 * @param resultset $rsMateriais
 * @param date      $dtInicial
 */
function contabilizaMateriais($rsMateriais, $dtInicial) {

  $aItens           = array();
  $iNumeroMateriais = pg_num_rows($rsMateriais);

  for ($iMaterial = 0; $iMaterial < $iNumeroMateriais; $iMaterial++) {

    $oMaterial = db_utils::fieldsmemory($rsMateriais, $iMaterial);

    $oItem                               = new stdClass();
    $oItem->iCodigo                      = $oMaterial->codigo_material;
    $oItem->sDescricao                   = $oMaterial->descricao_material;
    $oItem->nTotalSaidas                 = $oMaterial->saida   + 0;
    $oItem->nTotalEntradas               = $oMaterial->entrada + 0;
    $oItem->nSaldoFinal                  = 0 + ($oMaterial->saldo_inicial + $oMaterial->entrada - $oMaterial->saida);
    $oItem->nSaldoAnterior               = $oMaterial->saldo_inicial + 0;
    $oItem->iQuantidadeEntrada           = $oMaterial->quantidade_entrada;
    $oItem->iQuantidadeSaida             = $oMaterial->quantidade_saida;
    $oItem->iQuantidadeEmEstoque         = ($oMaterial->quantidade_estoque);
    $oItem->iQuantidadeInicial           = (($oMaterial->quantidade_estoque + $oMaterial->quantidade_saida) - $oMaterial->quantidade_entrada);
    $aItens[$oMaterial->codigo_material] = $oItem;
    unset($oMaterial);
  }
  return $aItens;
}


/**
 * Contabiliza os materiais, agrupando por Conta
 * @param array   $aItem          - array de materiais e suas movimentações
 * @param boolean $lPatrimonial   -
 * @param integer $iAno           - ano do intervalo pesquisado
 * @param string  $sWhere         - filtro de contas
 */
function agruparPorConta(&$aItem, $lPatrimonial = false, $iAno, $sWhere) {

  $count 					        = 0;
  $countSem 			        = 0;
  $aContas 				        = array();
  $aVinculoContasMaterial = array();

  if ($lPatrimonial) {
    $sFuncaoBuscaConta  = "buscaContaPatrimonial";
  } else {
    $sFuncaoBuscaConta  = "buscaContaDespesa";
  }

  $oAgrupamento                       = new stdClass();
  $oAgrupamento->nTotalSaidas         = 0;
  $oAgrupamento->nTotalEntradas       = 0;
  $oAgrupamento->nSaldoAnterior       = 0;
  $oAgrupamento->nSaldoFinal          = 0;
  $oAgrupamento->iQuantidadeEntrada   = 0;
  $oAgrupamento->iQuantidadeSaida     = 0;
  $oAgrupamento->iQuantidadeEmEstoque = 0;
  $oAgrupamento->iQuantidadeInicial   = 0;

  //Para cada agrupamento de material, buscar a conta e agrupar corretamente os totais
  foreach ($aItem as $oItem) {

  	if (!isset($aVinculoContasMaterial[$oItem->iCodigo])) {

  		$oConta = $sFuncaoBuscaConta($oItem->iCodigo, $iAno, $sWhere);
  		$aVinculoContasMaterial[$oItem->iCodigo] = $oConta;
  	}

  	$oConta = $aVinculoContasMaterial[$oItem->iCodigo];

    if (empty($oConta) && $lPatrimonial ) {
      continue;
    }

    if (empty($oConta) && !$lPatrimonial) {

      $oConta = new stdClass();
      $oConta->conta_codigo     = 0;
      $oConta->conta_descricao  = "Entrada Manual";
      $oConta->conta_reduzido   = "Sem Reduzido";
      $oConta->conta_estrutural = "Sem Estrutural";
    }

    $iConta           = $oConta->conta_codigo;
    $sDescricaoConta  = $oConta->conta_descricao;
    $iContaReduz      = $oConta->conta_reduzido;
    $sEstrutural      = $oConta->conta_estrutural;

    //Caso nenhuma movimentação do item estiver contabilizada
    if (empty($aContas[$sEstrutural]) ) {

      $oConta = new stdClass();

      //Totalizadores
      $oConta->nTotalSaidas         = 0;
      $oConta->nTotalEntradas       = 0;
      $oConta->nSaldoAnterior       = 0;
      $oConta->nSaldoFinal          = 0;
      $oConta->iQuantidadeEntrada   = 0;
      $oConta->iQuantidadeSaida     = 0;
      $oConta->iQuantidadeEmEstoque = 0;
      $oConta->iQuantidadeInicial   = 0;

      //Caracteristicas
      $oConta->iConta      = $iConta;
      $oConta->sDescricao  = $sDescricaoConta;
      $oConta->iReduzido   = $iContaReduz;
      $oConta->sEstrutural = $sEstrutural;

      $oConta->aItens        = array();
      $aContas[$sEstrutural] = $oConta;
    }


    //Contabiliza Totalizadores do material
    $aContas[$sEstrutural]->nSaldoAnterior       += $oItem->nSaldoAnterior;
    $aContas[$sEstrutural]->nTotalSaidas         += $oItem->nTotalSaidas;
    $aContas[$sEstrutural]->nTotalEntradas       += $oItem->nTotalEntradas;
    $aContas[$sEstrutural]->nSaldoFinal          += $oItem->nSaldoAnterior + $oItem->nTotalEntradas - $oItem->nTotalSaidas;
    $aContas[$sEstrutural]->iQuantidadeEntrada   += $oItem->iQuantidadeEntrada;
    $aContas[$sEstrutural]->iQuantidadeSaida     += $oItem->iQuantidadeSaida;
    $aContas[$sEstrutural]->iQuantidadeEmEstoque += $oItem->iQuantidadeEmEstoque;
    $aContas[$sEstrutural]->iQuantidadeInicial   += $oItem->iQuantidadeInicial;

    $aContas[$sEstrutural]->aItens[]        =  $oItem;

    $oAgrupamento->nTotalSaidas         += $oItem->nTotalSaidas;
    $oAgrupamento->nTotalEntradas       += $oItem->nTotalEntradas;
    $oAgrupamento->nSaldoAnterior       += $oItem->nSaldoAnterior;
    $oAgrupamento->nSaldoFinal          += $oItem->nSaldoAnterior + $oItem->nTotalEntradas - $oItem->nTotalSaidas;
    $oAgrupamento->iQuantidadeEntrada   += $oItem->iQuantidadeEntrada;
    $oAgrupamento->iQuantidadeSaida     += $oItem->iQuantidadeSaida;
    $oAgrupamento->iQuantidadeEmEstoque += $oItem->iQuantidadeEmEstoque;
    $oAgrupamento->iQuantidadeInicial   += $oItem->iQuantidadeInicial;
  }

  if (empty($aContas)) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem vinculos entre materiais e contas.');
  }

  // Ordenando array pela chave
  ksort($aContas);
  $oAgrupamento->aContas = $aContas;
  return $oAgrupamento;
}

/**
 * Contabiliza a Movimentação, Agrupando por grupo e subgrupo de material
 * @param array   $aItem          - array de movimentações agrupadas por material
 */
function agruparPorGrupoSubGrupo(&$aMateriais) {

  $oGrupos->aAgrupamentos        = array();
  $aGruposFilhos                 = array();
  $oGrupos                       = new stdClass();
  $oGrupos->nTotalSaidas         = 0;
  $oGrupos->nTotalEntradas       = 0;
  $oGrupos->nSaldoAnterior       = 0;
  $oGrupos->nSaldoFinal          = 0;
  $oGrupos->iQuantidadeEntrada   = 0;
  $oGrupos->iQuantidadeSaida     = 0;
  $oGrupos->iQuantidadeEmEstoque = 0;
  $oGrupos->iQuantidadeInicial   = 0;

  /**
   * Percorre cada um dos agrupamentos de material, procurando pelo respectivo grupo
   * O grupo encontrado estará em um nível de detalhamento mais alto, ou seja, sera o mais analítico
   */
  foreach ($aMateriais as $oMaterial) {

    $iCodigoMaterial = $oMaterial->iCodigo;
    $oDadosGrupo     = buscaGrupoAnalitico($iCodigoMaterial);

    if ($oDadosGrupo == null) {
      continue;
    }

    $iCodigoGrupo    = $oDadosGrupo->codigo;

    if(empty($oGrupos->aAgrupamentos[$iCodigoGrupo])) {

      $oNovoGrupo = new stdClass();
      $oNovoGrupo->sEstruturalGrupo     = $oDadosGrupo->estrurural;
      $oNovoGrupo->sDescricaoGrupo      = $oDadosGrupo->descricao;
      $oNovoGrupo->iCodigo              = $oDadosGrupo->codigo;
      $oNovoGrupo->iEstoque             = $oDadosGrupo->codigo_estoque;
      $oNovoGrupo->iPai                 = $oDadosGrupo->pai;
      $oNovoGrupo->iNivel               = $oDadosGrupo->nivel;
      $oNovoGrupo->nTotalSaidas         = 0;
      $oNovoGrupo->nTotalEntradas       = 0;
      $oNovoGrupo->nSaldoAnterior       = 0;
      $oNovoGrupo->nSaldoFinal          = 0;
      $oNovoGrupo->iQuantidadeEntrada   = 0;
      $oNovoGrupo->iQuantidadeSaida     = 0;
      $oNovoGrupo->iQuantidadeEmEstoque = 0;
      $oNovoGrupo->iQuantidadeInicial   = 0;
      $oGrupos->aAgrupamentos[$iCodigoGrupo]             = $oNovoGrupo ;
      $oGrupos->aAgrupamentos[$iCodigoGrupo]->aMateriais = array();
      $iNivel            = $oDadosGrupo->nivel;
      $aGruposFilhos[]   = $iCodigoGrupo;
      unset($oDadosGrupo);
    }

    $oGrupos->aAgrupamentos[$iCodigoGrupo]->nTotalSaidas         += $oMaterial->nTotalSaidas;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->nTotalEntradas       += $oMaterial->nTotalEntradas;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->nSaldoAnterior       += $oMaterial->nSaldoAnterior;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->nSaldoFinal          += $oMaterial->nSaldoFinal;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeEntrada   += $oMaterial->iQuantidadeEntrada;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeSaida     += $oMaterial->iQuantidadeSaida;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeEmEstoque += $oMaterial->iQuantidadeEmEstoque;
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeInicial   += (($oMaterial->iQuantidadeEmEstoque+$oMaterial->iQuantidadeSaida)-$oMaterial->iQuantidadeEntrada);
    $oGrupos->aAgrupamentos[$iCodigoGrupo]->aMateriais[]          = $oMaterial;

    $oGrupos->nTotalSaidas         += $oMaterial->nTotalSaidas;
    $oGrupos->nTotalEntradas       += $oMaterial->nTotalEntradas;
    $oGrupos->nSaldoAnterior       += $oMaterial->nSaldoAnterior;
    $oGrupos->nSaldoFinal          += $oMaterial->nSaldoAnterior + $oMaterial->nTotalEntradas - $oMaterial->nTotalSaidas;
    $oGrupos->iQuantidadeEntrada   += $oMaterial->iQuantidadeEntrada;
    $oGrupos->iQuantidadeSaida     += $oMaterial->iQuantidadeSaida;
    $oGrupos->iQuantidadeEmEstoque += $oMaterial->iQuantidadeEmEstoque;
    $oGrupos->iQuantidadeInicial   += (($oMaterial->iQuantidadeEmEstoque+$oMaterial->iQuantidadeSaida)-$oMaterial->iQuantidadeEntrada);
  }

  if ( empty($oGrupos->aAgrupamentos[$iCodigoGrupo]) ) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem materiais vinculados a grupos.');
  }

  $oGrupos =  montarArvoreGrupos($oGrupos, $aGruposFilhos, $iNivel);
  return $oGrupos;
}

function montarArvoreGrupos($oArvore, $aGruposNivel, $iNivel) {

  $oArvore->iNivelMaior = $iNivel;

  while($iNivel != 0) {

    $aPais = array();

    foreach ($aGruposNivel as $iGrupoFilho) {

      $oDadosGrupoPai = buscaGrupoPai($iGrupoFilho);

      if($oDadosGrupoPai == null) {
        continue;
      }
      $iCodigoPai     = $oDadosGrupoPai->db121_sequencial;

      if(empty($oArvore->aAgrupamentos[$iCodigoPai])) {

        $oGrupoPai = new stdClass();
        $oGrupoPai->sEstruturalGrupo     = $oDadosGrupoPai->db121_estrutural;
        $oGrupoPai->sDescricaoGrupo      = $oDadosGrupoPai->db121_descricao;
        $oGrupoPai->iCodigo              = $oDadosGrupoPai->db121_sequencial;
        $oGrupoPai->iPai                 = $oDadosGrupoPai->db121_estruturavalorpai;
        $oGrupoPai->iNivel               = $oDadosGrupoPai->db121_nivel;
        $oGrupoPai->nTotalSaidas         = 0;
        $oGrupoPai->nTotalEntradas       = 0;
        $oGrupoPai->nSaldoAnterior       = 0;
        $oGrupoPai->nSaldoFinal          = 0;
        $oGrupoPai->iQuantidadeEntrada   = 0;
        $oGrupoPai->iQuantidadeSaida     = 0;
        $oGrupoPai->iQuantidadeEmEstoque = 0;
        $oGrupoPai->iQuantidadeInicial   = 0;
        $oArvore->aAgrupamentos[$iCodigoPai] = $oGrupoPai;
        $aPais[] = $oGrupoPai->iCodigo;
      }

      $iNivel = $oArvore->aAgrupamentos[$iCodigoPai]->iNivel;
      $oArvore->aAgrupamentos[$iCodigoPai]->nTotalSaidas         +=  $oArvore->aAgrupamentos[$iGrupoFilho]->nTotalSaidas;
      $oArvore->aAgrupamentos[$iCodigoPai]->nTotalEntradas       +=  $oArvore->aAgrupamentos[$iGrupoFilho]->nTotalEntradas;
      $oArvore->aAgrupamentos[$iCodigoPai]->nSaldoAnterior       +=  $oArvore->aAgrupamentos[$iGrupoFilho]->nSaldoAnterior;
      $oArvore->aAgrupamentos[$iCodigoPai]->nSaldoFinal          +=  $oArvore->aAgrupamentos[$iGrupoFilho]->nSaldoFinal;
      $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeEntrada   +=  $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEntrada;
      $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeSaida     +=  $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeSaida;
      $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeEmEstoque +=  $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEmEstoque;
      $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeInicial   +=  (($oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEmEstoque +
                                                                       $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeSaida) -
                                                                       $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEntrada);
      $oArvore->aAgrupamentos[$iCodigoPai]->aFilhos[]             =  $oArvore->aAgrupamentos[$iGrupoFilho]->iCodigo;
    }
    $iNivel--;

    $aGruposNivel = $aPais;

  }
  $oArvore->aRaizes =  $aPais;
  return $oArvore;
}



/**
 *  Função para busca do grupo pai, de um determinado grupo
 */
function buscaGrupoAnalitico($iCodigoMaterial) {

  $sSql  = "       SELECT                                                                ";
  $sSql .= "            m65_sequencial            as codigo_estoque,                     ";
  $sSql .= "            db121_descricao           as descricao,                          ";
  $sSql .= "            db121_sequencial          as codigo,                             ";
  $sSql .= "            db121_estrutural          as estrurural,                         ";
  $sSql .= "            db121_nivel               as nivel,                              ";
  $sSql .= "            db121_estruturavalorpai   as pai                                 ";
  $sSql .= "       FROM matmatermaterialestoquegrupo                                     ";
  $sSql .= "       inner join materialestoquegrupo                                       ";
  $sSql .= "               ON m68_materialestoquegrupo = m65_sequencial                  ";
  $sSql .= "       inner join db_estruturavalor                                          ";
  $sSql .= "               ON m65_db_estruturavalor = db121_sequencial                   ";
  $sSql .= "       inner join matparam                                                   ";
  $sSql .= "               ON db121_db_estrutura = m90_db_estrutura                      ";
  $sSql .= "       WHERE matmatermaterialestoquegrupo.m68_matmater = {$iCodigoMaterial}  ";

  $rsGrupo = db_query($sSql);

  if (!$rsGrupo) {
    db_redireciona("db_erros.php?fechar=true&db_erro=ERRO AO CONSULTAR GRUPO ANALITICO DO MATERIAL {$iCodigoMaterial}.");
  }

  $oGrupo = null;

  if (pg_num_rows($rsGrupo) == 1) {
    $oGrupo = db_utils::fieldsmemory($rsGrupo, 0);
  }
  return $oGrupo;
}

/**
 *  Função para busca do grupo pai, de um determinado grupo
 */
function buscaGrupoPai ($iCodigo) {

  $oGrupo  = null;
  
  if (!empty($iCodigo)) {
  
    $sSql  = "select grupo_pai.*                                   ";
    $sSql .= "from  db_estruturavalor      as grupo_pai  ";
    $sSql .= "inner join db_estruturavalor as grupo_filho";
    $sSql .= "      on grupo_pai.db121_sequencial = grupo_filho.db121_estruturavalorpai";
    $sSql .= "      and grupo_filho.db121_sequencial ={$iCodigo}";
  
    $rsGrupo = db_query($sSql);
  
    if ($rsGrupo && pg_num_rows($rsGrupo) > 0) {
      $oGrupo = db_utils::fieldsmemory($rsGrupo, 0);
    }
  }

  return $oGrupo;
}

/**
 *  Função para busca da conta de despesa relacionada ao código de material
 */
function buscaContaDespesa($iCodigoMaterial, $iAno, $sWhere) {

  $sSql  = "      SELECT                                                         ";
  $sSql .= "              c60_codcon AS conta_codigo,                            ";
  $sSql .= "              c61_reduz  AS conta_reduzido,                          ";
  $sSql .= "              c60_estrut  AS conta_estrutural,                       ";
  $sSql .= "              c60_descr  AS conta_descricao                           ";

  $sSql .= "      FROM transmater                                                ";
  $sSql .= "      inner join pcmater                                             ";
  $sSql .= "              ON pcmater.pc01_codmater = transmater.m63_codpcmater   ";
  $sSql .= "      inner join pcmaterele                                          ";
  $sSql .= "              ON pcmaterele.pc07_codmater = pcmater.pc01_codmater    ";
  $sSql .= "      inner join conplano AS conta_despesa                           ";
  $sSql .= "              ON conta_despesa.c60_codcon = pcmaterele.pc07_codele   ";
  $sSql .= "                 AND conta_despesa.c60_anousu = {$iAno}              ";
  $sSql .= "      inner join conplanoreduz                                       ";
  $sSql .= "              ON conta_despesa.c60_codcon = c61_codcon               ";
  $sSql .= "                 AND c61_anousu = {$iAno}                            ";
  $sSql .= "                 AND c61_instit =".db_getsession("DB_instit")        ;
  $sSql .= "      WHERE   m63_codmatmater = {$iCodigoMaterial} and {$sWhere}     ";

  $sSql           = analiseQueryPlanoOrcamento($sSql);
  $rsContaDespesa = db_query($sSql);


  if (!$rsContaDespesa) {
    db_redireciona("db_erros.php?fechar=true&db_erro=ERRO AO CONSULTAR CONTA DE DESPESA DO MATERIAL {$iCodigoMaterial}.");
  }

  $oContaDespesa = null;

  if (pg_num_rows($rsContaDespesa) >= 1) {
    $oContaDespesa = db_utils::fieldsmemory($rsContaDespesa, 0);
  }

  return $oContaDespesa;
}

/**
 *  Função para busca da conta patrimonial relacionada ao código de material
 */
function buscaContaPatrimonial($iCodigoMaterial, $iAno, $sWhere) {

  $sSql  = " select c60_codcon AS conta_codigo,                                                                ";
  $sSql .= "        c61_reduz  AS conta_reduzido,                                                              ";
  $sSql .= "        c60_estrut AS conta_estrutural,                                                            ";
  $sSql .= "        c60_descr  AS conta_descricao                                                              ";
  $sSql .= "   from matmatermaterialestoquegrupo                                                               ";
  $sSql .= "        inner join materialestoquegrupo       on m68_materialestoquegrupo = m65_sequencial         ";
  $sSql .= "        inner join db_estruturavalor          on m65_db_estruturavalor    = db121_sequencial       ";
  $sSql .= "        inner join materialestoquegrupoconta  on m66_materialestoquegrupo = m65_sequencial         ";
  $sSql .= "                                             and m66_anousu = {$iAno}                              ";
  $sSql .= "        inner join conplano                   on c60_codcon = materialestoquegrupoconta.m66_codcon ";
  $sSql .= "                                             and c60_anousu = {$iAno}                              ";
  $sSql .= "        inner join conplanoreduz              on conplano.c60_codcon = c61_codcon                  ";
  $sSql .= "                                             and c61_anousu = {$iAno}                              ";
  $sSql .= "                                             and c61_instit =".db_getsession("DB_instit");
  $sSql .= "  where m66_anousu = {$iAno}                                                                       ";
  $sSql .= "    and {$sWhere}                                                                                  ";
  $sSql .= "    and matmatermaterialestoquegrupo.m68_matmater = {$iCodigoMaterial}                             ";


  $rsContaPatrimonial = db_query($sSql);

  if (!$rsContaPatrimonial) {
    db_redireciona("db_erros.php?fechar=true&db_erro=ERRO AO CONSULTAR CONTA PATRIMONIAL DO MATERIAL {$iCodigoMaterial}.");
  }

  $oContaPatrimonial = null;

  if (pg_num_rows($rsContaPatrimonial) == 1) {
    $oContaPatrimonial = db_utils::fieldsmemory($rsContaPatrimonial, 0);
  } elseif (pg_num_rows($rsContaPatrimonial) > 1) {
    echo "{$sSql}</br>";
  }

  return $oContaPatrimonial;
}

/**
 * Funções para impressão de Grupo e SubGrupos
 */
function imprimirPdfGrupos ($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica) {

  foreach ($oAgrupamento->aRaizes as $iRaiz) {
    
    $oRaiz = $oAgrupamento->aAgrupamentos[$iRaiz];
    imprimirGrupo ($oAgrupamento, $oRaiz, $oPdf, $iAlturaLinha, $lAnalitica, true);
    $oPdf->ln(3);
  }
}

function imprimirGrupo($oAgrupamento, $oGrupo, $oPdf, $iAlturaLinha, $lAnalitica,  $lImprimeCabecalho = false) {

  if($oPdf->gety() > $oPdf->h-35) {

      imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
      $lImprimeCabecalho = true;
  }

  if ($lImprimeCabecalho) {

    $oPdf->ln(1);
    $oPdf->setfont('arial','b',7);
    $oPdf->cell(10, $iAlturaLinha, "Nível", "TBR",0,"C",1);
    $oPdf->cell(20, $iAlturaLinha, "Estrutural", "LTBR",0,"C",1);
    $oPdf->cell(50, $iAlturaLinha, "Descrição Grupo", "LTBR",0,"C",1);
    $oPdf->cell(30, $iAlturaLinha, "Saldo Anterior ", "LTBR",0,"C", 1);
    $oPdf->cell(20, $iAlturaLinha, "Qtd. Inicial", "LTBR",0,"C", 1);
    $oPdf->cell(30, $iAlturaLinha, "Entradas", "LTBR",0,"C", 1);
    $oPdf->cell(20, $iAlturaLinha, "Qtd. Entrada", "LTBR",0,"C", 1);
    $oPdf->cell(30, $iAlturaLinha, "Saídas", "LTBR",0,"C", 1);
    $oPdf->cell(20, $iAlturaLinha, "Qtd. Saída", "LTBR",0,"C", 1);
    $oPdf->cell(30, $iAlturaLinha, "Saldo Final","LTBR",0,"C", 1);
    $oPdf->cell(20, $iAlturaLinha, "Qtd. Estoque", "LTB",1,"C", 1);
    $lImprimeCabecalho = false;
  }

  $oPdf->setfont('arial','',7);
  $oPdf->cell(10, $iAlturaLinha, "Nível: {$oGrupo->iNivel}", "TBR",0,"L",1);
  $oPdf->cell(20, $iAlturaLinha, "{$oGrupo->sEstruturalGrupo}", "LTBR",0,"L",1);
  $oPdf->cell(50, $iAlturaLinha, substr($oGrupo->sDescricaoGrupo, 0, 28), "LTBR",0,"L",1);
  $oPdf->cell(30, $iAlturaLinha, db_formatar(abs($oGrupo->nSaldoAnterior),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20, $iAlturaLinha, db_formatar($oGrupo->iQuantidadeInicial,"f"), "LTBR",0,"R", 1);
  $oPdf->cell(30, $iAlturaLinha, db_formatar(abs($oGrupo->nTotalEntradas),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20, $iAlturaLinha, db_formatar($oGrupo->iQuantidadeEntrada,"f"), "LTBR",0,"R", 1);
  $oPdf->cell(30, $iAlturaLinha, db_formatar(abs($oGrupo->nTotalSaidas),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20, $iAlturaLinha, db_formatar($oGrupo->iQuantidadeSaida,"f"), "LTBR",0,"R", 1);
  $oPdf->cell(30, $iAlturaLinha, db_formatar(abs($oGrupo->nSaldoFinal),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20, $iAlturaLinha, db_formatar($oGrupo->iQuantidadeEmEstoque,"f"), "LTB",1,"R", 1);

  //if ($oAgrupamento->iNivelMaior == $oGrupo->iNivel) {

    if ($lAnalitica) {

      $lInicio = true;
      if (!empty($oGrupo->aMateriais) && count($oGrupo->aMateriais) > 0) { 
        foreach ($oGrupo->aMateriais as $oMaterial) {
  
          imprimircabecalhoItem($oPdf, $iAlturaLinha, $lInicio);
          imprimirItem($oPdf, $iAlturaLinha, $oMaterial);
          $lInicio = false;
        }
      }
      $oPdf->ln(4);
    }
  //}

  if(empty($oGrupo->aFilhos)) {
    return;
  }

  foreach($oGrupo->aFilhos as $iFilho) {
    imprimirGrupo($oAgrupamento, $oAgrupamento->aAgrupamentos[$iFilho], $oPdf, $iAlturaLinha, $lAnalitica);
  }

}

/**
 * Funções para impressão da Conta
 */
function imprimirPdfContas($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica) {

  $aAgrupamento = $oAgrupamento->aContas;

  foreach ($aAgrupamento as $oAgrupamento) {

    //Imprime o Cabecalho com informações do agrupamento
    imprimirCabecalhoConta($oPdf, $iAlturaLinha, $oAgrupamento);

    //Se Impressão for analítica, deve imprimir item a item
    if ($lAnalitica) {

      $lInicio = true;

      foreach ($oAgrupamento->aItens as $oMaterial) {

        imprimircabecalhoItem($oPdf, $iAlturaLinha, $lInicio);
        imprimirItem($oPdf, $iAlturaLinha, $oMaterial);
        $lInicio = false;
      }
    }
    //Imprime o Rodapé com totalizadores
    imprimirRodapeConta($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica);
  }
}

function imprimirCabecalhoConta($oPdf, $iAlturaLinha, $oConta, $lPatrimonial = true) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  $oPdf->setfont('arial','b',7);
  $oPdf->ln(2);
  $oPdf->cell(20,  $iAlturaLinha, "Reduzido: {$oConta->iReduzido}", "TB",0,"L",1);
  $oPdf->cell(40,  $iAlturaLinha, "Estrutural: {$oConta->sEstrutural}", "TB",0,"L",1);
  $oPdf->cell(220,  $iAlturaLinha,"Descrição: " . $oConta->sDescricao, "TB",1,"L",1);
}

function imprimirRodapeConta($oPdf, $iAlturaLinha, $oConta, $lAnalitica) {

  if($oPdf->gety() > $oPdf->h-35) {
      imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  if (!$lAnalitica) {

    $oPdf->setfont('arial','',7);
    $oPdf->cell(80,$iAlturaLinha,"", "TBR",0,"C",0);
    $oPdf->cell(30,$iAlturaLinha,"Saldo Inicial", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Inicial", "TBR",0,"C", 0);
    $oPdf->cell(30,$iAlturaLinha,"Entradas", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Entradas", "TBR",0,"C", 0);
    $oPdf->cell(30,$iAlturaLinha,"Saídas", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Saídas", "TBR",0,"C", 0);
    $oPdf->cell(30,$iAlturaLinha,"Saldo final", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Estoque", "TB",1,"C", 0);
  }

  $oPdf->setfont('arial','b',7);
  $oPdf->cell(80,$iAlturaLinha,"TOTAL", "TB",0,"R",1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oConta->nSaldoAnterior),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oConta->iQuantidadeInicial,"f"), "LTBR",0,"R", 1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oConta->nTotalEntradas),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oConta->iQuantidadeEntrada,"f"), "LTBR",0,"R", 1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oConta->nTotalSaidas),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oConta->iQuantidadeSaida,"f"), "LTBR",0,"R", 1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oConta->nSaldoFinal),"f"), "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oConta->iQuantidadeEmEstoque,"f"), "LTB",1,"R", 1);
}


/**
 * Motor para impressão dos itens
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param array itens
 */
function imprimirItens($oPdf, $iAlturaLinha, $aItens) {

  $oTotal = new stdClass();
  $oTotal->nTotalSaidas         = 0;
  $oTotal->nTotalEntradas       = 0;
  $oTotal->nSaldoAnterior       = 0;
  $oTotal->nSaldoFinal          = 0;
  $oTotal->iQuantidadeEntrada   = 0;
  $oTotal->iQuantidadeSaida     = 0;
  $oTotal->iQuantidadeEmEstoque = 0;
  $oTotal->iQuantidadeInicial   = 0;


  imprimirCabecalhoItem($oPdf, $iAlturaLinha, true);

  foreach($aItens as $oItem) {

    imprimirItem($oPdf, $iAlturaLinha,$oItem);
    $oTotal->nTotalSaidas         += $oItem->nTotalSaidas;
    $oTotal->nTotalEntradas       += $oItem->nTotalEntradas;
    $oTotal->nSaldoAnterior       += $oItem->nSaldoAnterior;
    $oTotal->nSaldoFinal          += $oItem->nSaldoFinal;
    $oTotal->iQuantidadeEntrada   += $oItem->iQuantidadeEntrada;
    $oTotal->iQuantidadeSaida     += $oItem->iQuantidadeSaida;
    $oTotal->iQuantidadeEmEstoque += $oItem->iQuantidadeEmEstoque;
    $oTotal->iQuantidadeInicial   += (($oItem->iQuantidadeEmEstoque + $oItem->iQuantidadeSaida) - $oItem->iQuantidadeEntrada);
  }
  imprimirTotal($oPdf, $iAlturaLinha, $oTotal);
}

/**
 * Imprime o cabeçalho do item
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param boolean $lInicio
 */
function imprimirCabecalhoItem($oPdf, $iAlturaLinha, $lInicio = false) {

  if($oPdf->gety() > $oPdf->h-35 || $lInicio) {

    if (!$lInicio) {
      imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
    }

    $oPdf->setfont('arial','b', 6);
    $oPdf->cell(20,$iAlturaLinha,"Cód. do Material", "TBR",0,"C",0);
    $oPdf->cell(60,$iAlturaLinha,"Nome do Material", "TBR",0,"C",0);
    $oPdf->cell(30,$iAlturaLinha,"Saldo Inicial", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Inicial", "TBR",0,"C", 0);
    $oPdf->cell(30,$iAlturaLinha,"Entradas", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Entradas", "TBR",0,"C", 0);
    $oPdf->cell(30,$iAlturaLinha,"Saídas", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Saída", "TBR",0,"C", 0);
    $oPdf->cell(30,$iAlturaLinha,"Saldo Final", "TBR",0,"C", 0);
    $oPdf->cell(20,$iAlturaLinha,"Qtd. Estoque", "TB",1,"C", 0);
  }

}

/**
 * Imprime os dados do item
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oItem
 */
function imprimirItem ($oPdf, $iAlturaLinha,$oItem) {

  imprimirCabecalhoItem($oPdf, $iAlturaLinha);
  $oPdf->setfont('arial','',7);
  $oPdf->cell(20,$iAlturaLinha,$oItem->iCodigo, "TBR",0,"C",0);
  $oPdf->cell(60,$iAlturaLinha,substr($oItem->sDescricao, 0, 30), "TBR",0,"L",0);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oItem->nSaldoAnterior),"f"), "TBR",0,"R", 0);
  $oPdf->cell(20,$iAlturaLinha, db_formatar(abs($oItem->iQuantidadeInicial),"f"), "TBR",0,"R", 0);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oItem->nTotalEntradas),"f"), "TBR",0,"R", 0);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oItem->iQuantidadeEntrada, "f") , "TBR",0,"R", 0);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oItem->nTotalSaidas),"f"), "TBR",0,"R", 0);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oItem->iQuantidadeSaida, "f") , "TBR",0,"R", 0);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oItem->nSaldoFinal),"f"), "TBR",0,"R", 0);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oItem->iQuantidadeEmEstoque, "f"), "TB",1,"R", 0);
}

/**
 * Imprime as células de continuação da página
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 */
function imprimirContinuacaoPagina($oPdf, $iAlturaLinha) {

  $oPdf->cell(280,$iAlturaLinha,'Continua na Página '.($oPdf->pageNo()+1)."/{nb}","T",1,"R",0);
  $oPdf->addpage();
  $oPdf->ln(2);
  $oPdf->cell(280,$iAlturaLinha,'Continuação '.($oPdf->pageNo()-1)."/{nb}","B",1,"R",0);
}

/**
 * Imprime a última linha do relatório com os totalizadores
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oTotal
 */
function imprimirTotal($oPdf, $iAlturaLinha, $oTotal) {

  if($oPdf->gety() > $oPdf->h-32) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  $oPdf->setfont('arial','b',8);
  $oPdf->ln(2);
  $oPdf->cell(80,$iAlturaLinha,"TOTAL ACUMULADO :", "TBR",0,"R",1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oTotal->nSaldoAnterior),"f"),   "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oTotal->iQuantidadeInicial, "f"),   "LTBR",0,"R", 1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oTotal->nTotalEntradas),"f"),   "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oTotal->iQuantidadeEntrada, "f"),   "LTBR",0,"R", 1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oTotal->nTotalSaidas),"f"),     "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oTotal->iQuantidadeSaida, "f"),     "LTBR",0,"R", 1);
  $oPdf->cell(30,$iAlturaLinha, db_formatar(abs($oTotal->nSaldoFinal),"f"),      "LTBR",0,"R", 1);
  $oPdf->cell(20,$iAlturaLinha, db_formatar($oTotal->iQuantidadeEmEstoque, "f"), "LTBR",1,"R", 1);
}

$oPdf->Output();
?>