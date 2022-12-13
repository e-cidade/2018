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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
$iAnousu = db_getsession("DB_anousu");
$iInstituicao = db_getsession("DB_instit");

$dtInicial = trim($oGet->dtInicial);
$dtFinal = trim($oGet->dtFinal);

if (empty($dtInicial) || empty($dtFinal)) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Os campos Data são de preenchimento obrigatório.");
    exit;
}

$iAno = substr($dtInicial, 0, 4);
$iOrdem = $oGet->iOrdenacao;
$sAgrupamento = $oGet->iAgrupamento;
$lAnalitica = ($oGet->iTipoImpressao == "1") ? true : false;
$sOrder = '';
$sFuncaoTipoAgrupamento = '';
$lContaPatrimonial = '';

$sWhere = "1=1";

//Filtro por conta patrimonial
if (!empty($oGet->sContas) && $sAgrupamento == '3') {
    $sWhere = "c61_reduz in ({$oGet->sContas})";
}

//Filtro por conta de despesa
if (!empty($oGet->sContas) && $sAgrupamento == '2') {
    $sWhere = "c61_reduz in ({$oGet->sContas})";
}

switch ($iOrdem) {
    case '1':
        $sOrder = " order by m60_codmater asc";
        break;

    case '2':
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
switch ($sAgrupamento) {
    case '1':
        $sTipoRelatorio = "RELATÓRIO DE GRUPOS E SUBGRUPOS";
        $sFuncaoTipoAgrupamento = "agruparPorGrupoSubGrupo";
        $sFuncaoImpressao = "imprimirPdfGrupos";
        $lContaPatrimonial = false;
        break;

    case '2':
        $sTipoRelatorio = "RELATÓRIO DE CONTAS DE DESPESA";
        $sFuncaoTipoAgrupamento = "agruparPorConta";
        $sFuncaoImpressao = "imprimirPdfContas";
        $lContaPatrimonial = false;
        break;

    case '3':
        $sTipoRelatorio = "RELATÓRIO DE CONTAS PATRIMONIAIS";
        $sFuncaoTipoAgrupamento = "agruparPorConta";
        $sFuncaoImpressao = "imprimirPdfContas";
        $lContaPatrimonial = true;
        break;

    case '0':
        $sTipoRelatorio = "RELATÓRIO DE MATERIAIS";
        $sFuncaoTipoAgrupamento = "";
        break;
}

/**
 * Busca descrição do Município da Instituição
 */
$rsInstituicao = db_query("select munic from db_config where codigo = {$iInstituicao} ");
$oInstituicao = db_utils::fieldsmemory($rsInstituicao, 0);
$sDescricaoInstituicao = "MUNICÍPIO DE " . $oInstituicao->munic;

/**
 * Inicia Impressão do PDF
 */
$head2 = $sDescricaoInstituicao;
$head3 = "RELATÓRIO DE MOVIMENTAÇÕES";
$head4 = $sTipoRelatorio;
$dtInicialFormatada = db_formatar($dtInicial, "d");
$dtFinalFormatada = db_formatar($dtFinal, "d");
$head5 = "PERÍODO: {$dtInicialFormatada} a {$dtFinalFormatada}";
/**
 *  Seta as propriedades do pdf
 */
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(235);
$iAlturaLinha = 5;
$oPdf->AddPage();
$oPdf->SetFont('arial', '', 6);

$oConfigItem = new stdClass();
$oConfigItem->iTamanhoCodigoMaterial = 18;
$oConfigItem->iTamanhoNomeMaterial = 62;
$oConfigItem->iTamanhoSaldoInicial = 25;
$oConfigItem->iTamanhoQuantidadeInicial = 25;
$oConfigItem->iTamanhoEntradas = 25;
$oConfigItem->iTamanhoQuantidadeEntradas = 25;
$oConfigItem->iTamanhoSaidas = 25;
$oConfigItem->iTamanhoQuantidadeSaidas = 25;
$oConfigItem->iTamanhoSaldoFinal = 25;
$oConfigItem->iTamanhoQuantidadeEstoque = 25;

$rsMateriais = buscaMateriais($dtInicial, $dtFinal, $iInstituicao, $sOrder, $oGet->sAlmoxarifados);
$aItens = contabilizaMateriais($rsMateriais);

if (isset($sFuncaoImpressao)) {
    $oAgrupamento = $sFuncaoTipoAgrupamento($aItens, $lContaPatrimonial, $iAno, $sWhere);
    $sFuncaoImpressao($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica, $oConfigItem);
    imprimirTotal($oPdf, $iAlturaLinha, $oAgrupamento, $oConfigItem);
} else {
    imprimirItens($oPdf, $iAlturaLinha, $aItens, $oConfigItem);
}

/**
 * Busca movimentações de estoque em determinado período, para determinada instituição
 * @param string $dtInicial - data inicial do período
 * @param string $dtFinal - data final do período
 * @param integer $iInstituicao - código da instituição
 * @param $sOrder
 * @param string $sAlmoxarifados - lista de almoxarifados selecionados pelo usuário
 * @return bool|resource
 */
function buscaMateriais($dtInicial, $dtFinal, $iInstituicao, $sOrder, $sAlmoxarifados)
{
    db_query("drop table if exists w_materiais_saldo_inicial");

    /**
     * Cria tabela temporária, com o saldo inicial de cada código de material presente na matmater
     */
    $sSql = " create table w_materiais_saldo_inicial as                                                              ";
    $sSql .= "             select m70_codmatmater as codigo_material_saldo_inicial,                                   ";
    $sSql .= "                    sum(coalesce( case when m81_tipo = 1                                                ";
    $sSql .= "                                 then m82_quant when m81_tipo = 2                                       ";
    $sSql .= "                                 then m82_quant *-1 end, 0)                                             ";
    $sSql .= "                                 ) as quantidade_inicial,                                               ";
    $sSql .= "                    sum(coalesce( case when m81_tipo = 1                                                ";
    $sSql .= "                                 then m89_valorfinanceiro when m81_tipo = 2                     ";
    $sSql .= "                                 then m89_valorfinanceiro *-1 end, 0) ) as saldo_inicial           ";
    $sSql .= "               from matestoqueini                                                                       ";
    $sSql .= "                    inner join matestoquetipo     on  m80_codtipo = m81_codtipo                         ";
    $sSql .= "                                                  and m81_tipo <> 4                                  ";
    $sSql .= "                    inner join matestoqueinimei   on  m82_matestoqueini = m80_codigo                    ";
    $sSql .= "                    inner join matestoqueinimeipm on  m82_codigo = m89_matestoqueinimei                 ";
    $sSql .= "                    inner join matestoqueitem     on  m82_matestoqueitem = m71_codlanc                  ";
    $sSql .= "                    inner join matestoque         on  m71_codmatestoque = m70_codigo                    ";
    $sSql .= "                    inner join db_depart          on  m70_coddepto = coddepto                           ";
    $sSql .= "                    left join db_almox           on db_almox.m91_depto = db_depart.coddepto            ";
    $sSql .= "             where m80_data < '{$dtInicial}' and m71_servico is false                                   ";
    $sSql .= "               and instit = {$iInstituicao}                                                             ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "             and db_almox.m91_codigo in ({$sAlmoxarifados})                                           ";
    }
    $sSql .= "             group by m70_codmatmater                                                                   ";
    db_query($sSql);

//    $sSqlAcerto = "update w_materiais_saldo_inicial set saldo_inicial = 0 where quantidade_inicial <= 0 and (saldo_inicial > 0 or saldo_inicial < 0)";
//    db_query($sSqlAcerto);


    /**
     *  Cria indice na tabela temporária
     */
    $sSql = "create index
               w_materiais_saldo_inicial_codigo_material_saldo_inicial_in
                 ON w_materiais_saldo_inicial(codigo_material_saldo_inicial)";
    db_query($sSql);

    $sSql = " analyze w_materiais_saldo_inicial ";
    db_query($sSql);

    /**
     * Busca todos materiais da instituição que estiverem ativos e não forem serviço
     */
    $sSql = " select m60_codmater as codigo_material,                                                                                         ";
    $sSql .= "       m60_descr    as descricao_material,                                                                                                ";
    $sSql .= "       ( select sum( coalesce(m89_valorfinanceiro ,0) )                                                                                   ";
    $sSql .= "           from matestoqueinimei                                                                                                          ";
    $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                                                           ";
    $sSql .= "                                             and m80_data  >= '{$dtInicial}'                                                              ";
    $sSql .= "                                             and m80_data  <= '{$dtFinal}'                                                                ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                                                         ";
    $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                                                         ";
    $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}                                     ";
    $sSql .= "                left join db_almox           on db_almox.m91_depto = db_depart.coddepto                                                  ";
    $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                                                                ";
    $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                              ";
    $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                                                        ";
    $sSql .= "            and m71_servico is false ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                                                                ";
    }
    $sSql .= "            and m81_tipo = 1 ) as entrada,                                                                                                ";

    $sSql .= "       ( select coalesce(sum(m82_quant) ,0)                                                                                               ";
    $sSql .= "           from matestoqueinimei                                                                                                          ";
    $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                                                           ";
    $sSql .= "                                             and m80_data  >= '{$dtInicial}'                                                              ";
    $sSql .= "                                             and m80_data  <= '{$dtFinal}'                                                                ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                                                         ";
    $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                                                         ";
    $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}                                     ";
    $sSql .= "                left join db_almox           on db_almox.m91_depto = db_depart.coddepto                                                  ";
    $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                                                                ";
    $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                                 ";
    $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                                                       ";
    $sSql .= "            and m71_servico is false ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                                                                ";
    }
    $sSql .= "            and m81_tipo = 1 ) as quantidade_entrada,                                                                                     ";

    $sSql .= "       ( select sum( coalesce((m89_valorfinanceiro)::numeric,0) )                                                                           ";
    $sSql .= "           from matestoqueinimei                                                                                                          ";
    $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                                                           ";
    $sSql .= "                                             and m80_data  >= '{$dtInicial}'                                                              ";
    $sSql .= "                                             and m80_data  <= '{$dtFinal}'                                                                ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                                                         ";
    $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                                                         ";
    $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}                                     ";
    $sSql .= "                left join db_almox           on db_almox.m91_depto = db_depart.coddepto                                                  ";
    $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                                                                ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                                                        ";
    $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater and m71_servico is false                                               ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                                                                ";
    }
    $sSql .= "            and m81_tipo = 2 ) as saida,                                                                                                  ";

    $sSql .= "       ( select coalesce(sum(m82_quant) ,0)                                                                                               ";
    $sSql .= "           from matestoqueinimei                                                                                                          ";
    $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                                                           ";
    $sSql .= "                                             and m80_data  >= '{$dtInicial}'                                                              ";
    $sSql .= "                                             and m80_data  <= '{$dtFinal}'                                                                ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                                                         ";
    $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                                                         ";
    $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}                                     ";
    $sSql .= "                left join db_almox           on db_almox.m91_depto = db_depart.coddepto                                                  ";
    $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                                                                ";
    $sSql .= "                                             and m81_tipo <> 4                                                                            ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                                                        ";
    $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater and m71_servico is false                                               ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                                                                ";
    }
    $sSql .= "            and m81_tipo = 2) as quantidade_saida,                                                                                       ";
    $sSql .= "       ( select coalesce(sum(case when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant *-1 end ), 0 )                        ";
    $sSql .= "           from matestoqueinimei                                                                                                          ";
    $sSql .= "                inner join matestoqueini      on m80_codigo = m82_matestoqueini                                                           ";
    $sSql .= "                                             and m80_data  <= '{$dtFinal}'                                                                ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                                                         ";
    $sSql .= "                inner join matestoque         on m70_codigo   = m71_codmatestoque                                                         ";
    $sSql .= "                inner join db_depart          on m70_coddepto = coddepto and instit = {$iInstituicao}                                     ";
    $sSql .= "                left join db_almox           on db_almox.m91_depto = db_depart.coddepto                                                  ";
    $sSql .= "                inner join matestoquetipo     on m80_codtipo = m81_codtipo                                                                ";
    $sSql .= "                                             and m81_tipo <> 4                                                                            ";
    $sSql .= "                                                                                                                                          ";
    $sSql .= "                inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei                                                        ";
    $sSql .= "          where matestoque.m70_codmatmater = matmater.m60_codmater                                                                        ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "          and db_almox.m91_codigo in ({$sAlmoxarifados})                                                                                ";
    }
    $sSql .= "            ) as quantidade_estoque,                                                                                                      ";

    $sSql .= "       saldo_inicial,                                                                                                                     ";
    $sSql .= "       quantidade_inicial                                                                                                                 ";
    $sSql .= "                                                                                                                                          ";

    $sSql .= "  from matmater                                                                                                                           ";
    $sSql .= "       inner join matestoque                   on m70_codmatmater = m60_codmater                                                          ";
    $sSql .= "       left  join w_materiais_saldo_inicial si on si.codigo_material_saldo_inicial = m70_codmatmater                                      ";
    $sSql .= "       inner join db_depart                    on m70_coddepto = coddepto                                                                 ";
    $sSql .= "                                               and instit = {$iInstituicao}                                                               ";
    $sSql .= "       left join db_almox           on  db_almox.m91_depto = db_depart.coddepto                                                          ";
    $sSql .= " where	instit = {$iInstituicao}                                				                                      ";
    if (!empty($sAlmoxarifados)) {
        $sSql .= "		  and db_almox.m91_codigo in ({$sAlmoxarifados})                                                                              ";
    }

    $sSql .= " {$sOrder}  											                                      ";

    $rsMateriais = db_query($sSql);

    if ($rsMateriais == false) {
        exit;
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
 * @param resource $rsMateriais
 * @return array
 */
function contabilizaMateriais($rsMateriais)
{
    $aItens = array();
    $iNumeroMateriais = pg_num_rows($rsMateriais);

    for ($iMaterial = 0; $iMaterial < $iNumeroMateriais; $iMaterial++) {
        $oMaterial = db_utils::fieldsmemory($rsMateriais, $iMaterial);

        $oItem = new stdClass();
        $oItem->iCodigo = $oMaterial->codigo_material;
        $oItem->sDescricao = $oMaterial->descricao_material;
        $oItem->nTotalSaidas = (float)$oMaterial->saida;
        $oItem->nTotalEntradas = (float)$oMaterial->entrada;
        $oItem->nSaldoAnterior = (float)$oMaterial->saldo_inicial;
        $oItem->iQuantidadeEntrada = (float)$oMaterial->quantidade_entrada;
        $oItem->iQuantidadeSaida = (float)$oMaterial->quantidade_saida;
        $oItem->iQuantidadeEmEstoque = round((float)$oMaterial->quantidade_estoque, 2);
        $oItem->iQuantidadeInicial = (($oItem->iQuantidadeEmEstoque + $oItem->iQuantidadeSaida) - $oItem->iQuantidadeEntrada);

        $oItem->nSaldoFinal          =  round($oMaterial->saldo_inicial + $oMaterial->entrada - $oMaterial->saida, 2);

//        $oItem->nSaldoFinal = (empty($oItem->iQuantidadeEmEstoque) ? 0 : $oItem->nSaldoFinal);
//        $oItem->nSaldoAnterior = (empty($oItem->iQuantidadeInicial) ? 0 : $oItem->nSaldoAnterior);

        $aItens[$oMaterial->codigo_material] = $oItem;
        unset($oMaterial);
    }

    return $aItens;
}


/**
 * Contabiliza os materiais, agrupando por Conta
 * @param array $aItem - array de materiais e suas movimentações
 * @param bool $lPatrimonial
 * @param integer $iAno - ano do intervalo pesquisado
 * @param string $sWhere - filtro de contas
 * @return stdClass
 */
function agruparPorConta(&$aItem, $lPatrimonial = false, $iAno, $sWhere)
{
    $aContas = array();
    $aVinculoContasMaterial = array();

    if ($lPatrimonial) {
        $sFuncaoBuscaConta = "buscaContaPatrimonial";
    } else {
        $sFuncaoBuscaConta = "buscaContaDespesa";
    }

    $oAgrupamento = new stdClass();
    $oAgrupamento->nTotalSaidas = 0;
    $oAgrupamento->nTotalEntradas = 0;
    $oAgrupamento->nSaldoAnterior = 0;
    $oAgrupamento->nSaldoFinal = 0;
    $oAgrupamento->iQuantidadeEntrada = 0;
    $oAgrupamento->iQuantidadeSaida = 0;
    $oAgrupamento->iQuantidadeEmEstoque = 0;
    $oAgrupamento->iQuantidadeInicial = 0;

    //Para cada agrupamento de material, buscar a conta e agrupar corretamente os totais
    foreach ($aItem as $oItem) {
        if (!isset($aVinculoContasMaterial[$oItem->iCodigo])) {
            $oConta = $sFuncaoBuscaConta($oItem->iCodigo, $iAno, $sWhere);
            $aVinculoContasMaterial[$oItem->iCodigo] = $oConta;
        }

        $oConta = $aVinculoContasMaterial[$oItem->iCodigo];

        if (empty($oConta) && $lPatrimonial) {
            continue;
        }

        if (empty($oConta) && !$lPatrimonial) {
            $oConta = new stdClass();
            $oConta->conta_codigo = 0;
            $oConta->conta_descricao = "Entrada Manual";
            $oConta->conta_reduzido = "Sem Reduzido";
            $oConta->conta_estrutural = "Sem Estrutural";
        }

        $iConta = $oConta->conta_codigo;
        $sDescricaoConta = $oConta->conta_descricao;
        $iContaReduz = $oConta->conta_reduzido;
        $sEstrutural = $oConta->conta_estrutural;

        //Caso nenhuma movimentação do item estiver contabilizada
        if (empty($aContas[$sEstrutural])) {
            $oConta = new stdClass();

            //Totalizadores
            $oConta->nTotalSaidas = 0;
            $oConta->nTotalEntradas = 0;
            $oConta->nSaldoAnterior = 0;
            $oConta->nSaldoFinal = 0;
            $oConta->iQuantidadeEntrada = 0;
            $oConta->iQuantidadeSaida = 0;
            $oConta->iQuantidadeEmEstoque = 0;
            $oConta->iQuantidadeInicial = 0;

            //Caracteristicas
            $oConta->iConta = $iConta;
            $oConta->sDescricao = $sDescricaoConta;
            $oConta->iReduzido = $iContaReduz;
            $oConta->sEstrutural = $sEstrutural;

            $oConta->aItens = array();
            $aContas[$sEstrutural] = $oConta;
        }

        //Contabiliza Totalizadores do material
        $aContas[$sEstrutural]->nSaldoAnterior += $oItem->nSaldoAnterior;
        $aContas[$sEstrutural]->nTotalSaidas += $oItem->nTotalSaidas;
        $aContas[$sEstrutural]->nTotalEntradas += $oItem->nTotalEntradas;
        $aContas[$sEstrutural]->nSaldoFinal += $oItem->nSaldoAnterior + $oItem->nTotalEntradas - $oItem->nTotalSaidas;
        $aContas[$sEstrutural]->iQuantidadeEntrada += $oItem->iQuantidadeEntrada;
        $aContas[$sEstrutural]->iQuantidadeSaida += $oItem->iQuantidadeSaida;
        $aContas[$sEstrutural]->iQuantidadeEmEstoque += $oItem->iQuantidadeEmEstoque;
        $aContas[$sEstrutural]->iQuantidadeInicial += $oItem->iQuantidadeInicial;

        $aContas[$sEstrutural]->aItens[] = $oItem;

        $oAgrupamento->nTotalSaidas += $oItem->nTotalSaidas;
        $oAgrupamento->nTotalEntradas += $oItem->nTotalEntradas;
        $oAgrupamento->nSaldoAnterior += $oItem->nSaldoAnterior;
        $oAgrupamento->nSaldoFinal += $oItem->nSaldoAnterior + $oItem->nTotalEntradas - $oItem->nTotalSaidas;
        $oAgrupamento->iQuantidadeEntrada += $oItem->iQuantidadeEntrada;
        $oAgrupamento->iQuantidadeSaida += $oItem->iQuantidadeSaida;
        $oAgrupamento->iQuantidadeEmEstoque += $oItem->iQuantidadeEmEstoque;
        $oAgrupamento->iQuantidadeInicial += $oItem->iQuantidadeInicial;
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
 * @param array $aMateriais - array de movimentações agrupadas por material
 * @return mixed|stdClass
 */
function agruparPorGrupoSubGrupo(&$aMateriais)
{
    $aGruposFilhos = array();
    $oGrupos = new stdClass();
    $oGrupos->aAgrupamentos = array();
    $oGrupos->nTotalSaidas = 0;
    $oGrupos->nTotalEntradas = 0;
    $oGrupos->nSaldoAnterior = 0;
    $oGrupos->nSaldoFinal = 0;
    $oGrupos->iQuantidadeEntrada = 0;
    $oGrupos->iQuantidadeSaida = 0;
    $oGrupos->iQuantidadeEmEstoque = 0;
    $oGrupos->iQuantidadeInicial = 0;

    /**
     * Percorre cada um dos agrupamentos de material, procurando pelo respectivo grupo
     * O grupo encontrado estará em um nível de detalhamento mais alto, ou seja, sera o mais analítico
     */
    foreach ($aMateriais as $oMaterial) {
        $iCodigoMaterial = $oMaterial->iCodigo;
        $oDadosGrupo = buscaGrupoAnalitico($iCodigoMaterial);

        if ($oDadosGrupo == null) {
            continue;
        }

        $iCodigoGrupo = $oDadosGrupo->codigo;

        if (empty($oGrupos->aAgrupamentos[$iCodigoGrupo])) {
            $oNovoGrupo = new stdClass();
            $oNovoGrupo->sEstruturalGrupo = $oDadosGrupo->estrurural;
            $oNovoGrupo->sDescricaoGrupo = $oDadosGrupo->descricao;
            $oNovoGrupo->iCodigo = $oDadosGrupo->codigo;
            $oNovoGrupo->iEstoque = $oDadosGrupo->codigo_estoque;
            $oNovoGrupo->iPai = $oDadosGrupo->pai;
            $oNovoGrupo->iNivel = $oDadosGrupo->nivel;
            $oNovoGrupo->nTotalSaidas = 0;
            $oNovoGrupo->nTotalEntradas = 0;
            $oNovoGrupo->nSaldoAnterior = 0;
            $oNovoGrupo->nSaldoFinal = 0;
            $oNovoGrupo->iQuantidadeEntrada = 0;
            $oNovoGrupo->iQuantidadeSaida = 0;
            $oNovoGrupo->iQuantidadeEmEstoque = 0;
            $oNovoGrupo->iQuantidadeInicial = 0;
            $oGrupos->aAgrupamentos[$iCodigoGrupo] = $oNovoGrupo;
            $oGrupos->aAgrupamentos[$iCodigoGrupo]->aMateriais = array();
            $iNivel = $oDadosGrupo->nivel;
            $aGruposFilhos[] = $iCodigoGrupo;
            unset($oDadosGrupo);
        }

        $oGrupos->aAgrupamentos[$iCodigoGrupo]->nTotalSaidas += $oMaterial->nTotalSaidas;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->nTotalEntradas += $oMaterial->nTotalEntradas;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->nSaldoAnterior += $oMaterial->nSaldoAnterior;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->nSaldoFinal += $oMaterial->nSaldoFinal;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeEntrada += $oMaterial->iQuantidadeEntrada;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeSaida += $oMaterial->iQuantidadeSaida;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeEmEstoque += $oMaterial->iQuantidadeEmEstoque;
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->iQuantidadeInicial += (($oMaterial->iQuantidadeEmEstoque + $oMaterial->iQuantidadeSaida) - $oMaterial->iQuantidadeEntrada);
        $oGrupos->aAgrupamentos[$iCodigoGrupo]->aMateriais[] = $oMaterial;

        $oGrupos->nTotalSaidas += $oMaterial->nTotalSaidas;
        $oGrupos->nTotalEntradas += $oMaterial->nTotalEntradas;
        $oGrupos->nSaldoAnterior += $oMaterial->nSaldoAnterior;
        $oGrupos->nSaldoFinal += $oMaterial->nSaldoFinal;
        $oGrupos->iQuantidadeEntrada += $oMaterial->iQuantidadeEntrada;
        $oGrupos->iQuantidadeSaida += $oMaterial->iQuantidadeSaida;
        $oGrupos->iQuantidadeEmEstoque += $oMaterial->iQuantidadeEmEstoque;
        $oGrupos->iQuantidadeInicial += (($oMaterial->iQuantidadeEmEstoque + $oMaterial->iQuantidadeSaida) - $oMaterial->iQuantidadeEntrada);

        if (empty($oGrupos->aAgrupamentos[$iCodigoGrupo])) {
            db_redireciona('db_erros.php?fechar=true&db_erro=Não existem materiais vinculados a grupos.');
        }
    }

    $oGrupos = montarArvoreGrupos($oGrupos, $aGruposFilhos, $iNivel);

    return $oGrupos;
}

function montarArvoreGrupos($oArvore, $aGruposNivel, $iNivel)
{
    $oArvore->iNivelMaior = $iNivel;
    $aPais = array();

    while ($iNivel != 0) {
        foreach ($aGruposNivel as $iGrupoFilho) {
            $oDadosGrupoPai = buscaGrupoPai($iGrupoFilho);

            if ($oDadosGrupoPai == null) {
                continue;
            }

            $iCodigoPai = $oDadosGrupoPai->db121_sequencial;

            if (empty($oArvore->aAgrupamentos[$iCodigoPai])) {
                $oGrupoPai = new stdClass();
                $oGrupoPai->sEstruturalGrupo = $oDadosGrupoPai->db121_estrutural;
                $oGrupoPai->sDescricaoGrupo = $oDadosGrupoPai->db121_descricao;
                $oGrupoPai->iCodigo = $oDadosGrupoPai->db121_sequencial;
                $oGrupoPai->iPai = $oDadosGrupoPai->db121_estruturavalorpai;
                $oGrupoPai->iNivel = $oDadosGrupoPai->db121_nivel;
                $oGrupoPai->nTotalSaidas = 0;
                $oGrupoPai->nTotalEntradas = 0;
                $oGrupoPai->nSaldoAnterior = 0;
                $oGrupoPai->nSaldoFinal = 0;
                $oGrupoPai->iQuantidadeEntrada = 0;
                $oGrupoPai->iQuantidadeSaida = 0;
                $oGrupoPai->iQuantidadeEmEstoque = 0;
                $oGrupoPai->iQuantidadeInicial = 0;
                $oArvore->aAgrupamentos[$iCodigoPai] = $oGrupoPai;
                $aPais[] = $oGrupoPai->iCodigo;
            }

            $iNivel = $oArvore->aAgrupamentos[$iCodigoPai]->iNivel;
            $oArvore->aAgrupamentos[$iCodigoPai]->nTotalSaidas += $oArvore->aAgrupamentos[$iGrupoFilho]->nTotalSaidas;
            $oArvore->aAgrupamentos[$iCodigoPai]->nTotalEntradas += $oArvore->aAgrupamentos[$iGrupoFilho]->nTotalEntradas;
            $oArvore->aAgrupamentos[$iCodigoPai]->nSaldoAnterior += $oArvore->aAgrupamentos[$iGrupoFilho]->nSaldoAnterior;
            $oArvore->aAgrupamentos[$iCodigoPai]->nSaldoFinal += $oArvore->aAgrupamentos[$iGrupoFilho]->nSaldoFinal;
            $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeEntrada += $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEntrada;
            $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeSaida += $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeSaida;
            $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeEmEstoque += $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEmEstoque;
            $oArvore->aAgrupamentos[$iCodigoPai]->iQuantidadeInicial += (($oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEmEstoque +
                $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeSaida) -
              $oArvore->aAgrupamentos[$iGrupoFilho]->iQuantidadeEntrada);
            $oArvore->aAgrupamentos[$iCodigoPai]->aFilhos[] = $oArvore->aAgrupamentos[$iGrupoFilho]->iCodigo;
        }
        $iNivel--;
        $aGruposNivel = $aPais;
    }

    $oArvore->aRaizes = $aPais;

    return $oArvore;
}


/**
 *  Função para busca do grupo pai, de um determinado grupo
 */
function buscaGrupoAnalitico($iCodigoMaterial)
{
    $sSql = "       SELECT                                                                ";
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
function buscaGrupoPai($iCodigo)
{
    $oGrupo = null;

    if (!empty($iCodigo)) {
        $sSql = "select grupo_pai.*                                   ";
        $sSql .= "from  db_estruturavalor      as grupo_pai  ";
        $sSql .= "inner join db_estruturavalor as grupo_filho";
        $sSql .= "      on grupo_pai.db121_sequencial = grupo_filho.db121_estruturavalorpai";
        $sSql .= "      and grupo_filho.db121_sequencial = {$iCodigo}";

        $rsGrupo = db_query($sSql);

        if ($rsGrupo && pg_num_rows($rsGrupo) > 0) {
            $oGrupo = db_utils::fieldsmemory($rsGrupo, 0);
        }
    }

    return $oGrupo;
}

/**
 * Função para busca da conta de despesa relacionada ao código de material
 * @param integer $iCodigoMaterial
 * @param integer $iAno
 * @param string $sWhere
 * @return _db_fields|null|stdClass
 */
function buscaContaDespesa($iCodigoMaterial, $iAno, $sWhere)
{
    $sSql = "      SELECT                                                         ";
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
    $sSql .= "                 AND c61_instit = " . db_getsession("DB_instit");
    $sSql .= "      WHERE   m63_codmatmater = {$iCodigoMaterial} and {$sWhere}     ";

    $sSql = analiseQueryPlanoOrcamento($sSql);
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
 * Função para busca da conta patrimonial relacionada ao código de material
 * @param integer $iCodigoMaterial
 * @param integer $iAno
 * @param string $sWhere
 * @return _db_fields|null|stdClass
 */
function buscaContaPatrimonial($iCodigoMaterial, $iAno, $sWhere)
{
    $sSql = " select c60_codcon AS conta_codigo,                                                                ";
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
    $sSql .= "                                             and c61_instit = " . db_getsession("DB_instit");
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
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oAgrupamento
 * @param bool $lAnalitica
 * @param stdClass $oConfigItem
 */
function imprimirPdfGrupos(PDF $oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica, $oConfigItem)
{
    foreach ($oAgrupamento->aRaizes as $iRaiz) {
        $oRaiz = $oAgrupamento->aAgrupamentos[$iRaiz];
        imprimirGrupo($oAgrupamento, $oRaiz, $oPdf, $iAlturaLinha, $lAnalitica, true, $oConfigItem);
        $oPdf->Ln(3);
    }
}

/**
 * @param stdClass $oAgrupamento
 * @param stdClass $oGrupo
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param bool $lAnalitica
 * @param bool $lImprimeCabecalho
 * @param stdClass $oConfigItem
 */
function imprimirGrupo(
    $oAgrupamento,
    $oGrupo,
    PDF $oPdf,
    $iAlturaLinha,
    $lAnalitica,
    $lImprimeCabecalho = false,
    $oConfigItem
) {
    if ($oPdf->GetY() > $oPdf->h - 35) {
        imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
        $lImprimeCabecalho = true;
    }

    if ($lImprimeCabecalho) {
        $oPdf->Ln(1);
        $oPdf->SetFont('arial', 'b', 6);
        $oPdf->Cell(10, $iAlturaLinha, "Nível", "TBR", 0, "C", 1);
        $oPdf->Cell(20, $iAlturaLinha, "Estrutural", "LTBR", 0, "C", 1);
        $oPdf->Cell(30, $iAlturaLinha, "Descrição Grupo", "LTBR", 0, "C", 1);
        $oPdf->Cell(30, $iAlturaLinha, "Saldo Anterior ", "LTBR", 0, "C", 1);
        $oPdf->Cell(25, $iAlturaLinha, "Qtd. Inicial", "LTBR", 0, "C", 1);
        $oPdf->Cell(30, $iAlturaLinha, "Entradas", "LTBR", 0, "C", 1);
        $oPdf->Cell(25, $iAlturaLinha, "Qtd. Entrada", "LTBR", 0, "C", 1);
        $oPdf->Cell(30, $iAlturaLinha, "Saídas", "LTBR", 0, "C", 1);
        $oPdf->Cell(25, $iAlturaLinha, "Qtd. Saída", "LTBR", 0, "C", 1);
        $oPdf->Cell(30, $iAlturaLinha, "Saldo Final", "LTBR", 0, "C", 1);
        $oPdf->Cell(25, $iAlturaLinha, "Qtd. Estoque", "LTB", 1, "C", 1);

        $lImprimeCabecalho = false;
    }

    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell(10, $iAlturaLinha, "Nível: {$oGrupo->iNivel}", "TBR", 0, "L", 1);
    $oPdf->Cell(20, $iAlturaLinha, "{$oGrupo->sEstruturalGrupo}", "LTBR", 0, "L", 1);
    $oPdf->Cell(30, $iAlturaLinha, substr($oGrupo->sDescricaoGrupo, 0, 28), "LTBR", 0, "L", 1);
    $oPdf->Cell(30, $iAlturaLinha, db_formatar(($oGrupo->nSaldoAnterior), "f"), "LTBR", 0, "R", 1);
    $oPdf->Cell(25, $iAlturaLinha, ($oGrupo->iQuantidadeInicial), "LTBR", 0, "R", 1);
    $oPdf->Cell(30, $iAlturaLinha, db_formatar(($oGrupo->nTotalEntradas), "f"), "LTBR", 0, "R", 1);
    $oPdf->Cell(25, $iAlturaLinha, $oGrupo->iQuantidadeEntrada, "LTBR", 0, "R", 1);
    $oPdf->Cell(30, $iAlturaLinha, db_formatar(($oGrupo->nTotalSaidas), "f"), "LTBR", 0, "R", 1);
    $oPdf->Cell(25, $iAlturaLinha, $oGrupo->iQuantidadeSaida, "LTBR", 0, "R", 1);

    if ($oGrupo->iQuantidadeEmEstoque <= 0) {
        $oGrupo->nSaldoFinal = "0.00";
        $oGrupo->iQuantidadeEmEstoque = "0.00";
    }

    $oPdf->Cell(30, $iAlturaLinha, db_formatar(($oGrupo->nSaldoFinal), "f"), "LTBR", 0, "R", 1);
    $oPdf->Cell(25, $iAlturaLinha, $oGrupo->iQuantidadeEmEstoque, "LTB", 1, "R", 1);

    if ($lAnalitica) {
        $lInicio = true;
        if (!empty($oGrupo->aMateriais) && count($oGrupo->aMateriais) > 0) {
            foreach ($oGrupo->aMateriais as $oMaterial) {
                imprimircabecalhoItem($oPdf, $iAlturaLinha, $lInicio, $oConfigItem);
                imprimirItem($oPdf, $iAlturaLinha, $oMaterial, $oConfigItem);
                $lInicio = false;
            }
        }

        $oPdf->Ln(4);
    }

    if (empty($oGrupo->aFilhos)) {
        return;
    }

    foreach ($oGrupo->aFilhos as $iFilho) {
        imprimirGrupo($oAgrupamento, $oAgrupamento->aAgrupamentos[$iFilho], $oPdf, $iAlturaLinha, $lAnalitica, false,
            $oConfigItem);
    }
}

/**
 * Funções para impressão da Conta
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oAgrupamento
 * @param bool $lAnalitica
 * @param stdClass $oConfigItem
 */
function imprimirPdfContas($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica, $oConfigItem)
{
    $aAgrupamento = $oAgrupamento->aContas;

    foreach ($aAgrupamento as $oAgrupamento) {
        //Imprime o Cabecalho com informações do agrupamento
        imprimirCabecalhoConta($oPdf, $iAlturaLinha, $oAgrupamento);

        //Se Impressão for analítica, deve imprimir item a item
        if ($lAnalitica) {
            $lInicio = true;

            foreach ($oAgrupamento->aItens as $oMaterial) {
                imprimircabecalhoItem($oPdf, $iAlturaLinha, $lInicio, $oConfigItem);
                imprimirItem($oPdf, $iAlturaLinha, $oMaterial, $oConfigItem);
                $lInicio = false;
            }
        }
        //Imprime o Rodapé com totalizadores
        imprimirRodapeConta($oPdf, $iAlturaLinha, $oAgrupamento, $lAnalitica, $oConfigItem);
    }
}

/**
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oConta
 */
function imprimirCabecalhoConta(PDF $oPdf, $iAlturaLinha, $oConta)
{
    if ($oPdf->GetY() > $oPdf->h - 35) {
        imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
    }

    $oPdf->SetFont('arial', 'b', 6);
    $oPdf->Ln(2);
    $oPdf->Cell(20, $iAlturaLinha, "Reduzido: {$oConta->iReduzido}", "TB", 0, "L", 1);
    $oPdf->Cell(40, $iAlturaLinha, "Estrutural: {$oConta->sEstrutural}", "TB", 0, "L", 1);
    $oPdf->Cell(220, $iAlturaLinha, "Descrição: " . $oConta->sDescricao, "TB", 1, "L", 1);
}

/**
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oConta
 * @param bool $lAnalitica
 */
function imprimirRodapeConta(PDF $oPdf, $iAlturaLinha, $oConta, $lAnalitica, $oConfigItem)
{
    if ($oPdf->GetY() > $oPdf->h - 35) {
        imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
    }

    $iTamanhoTotal = $oConfigItem->iTamanhoCodigoMaterial + $oConfigItem->iTamanhoNomeMaterial;

    if (!$lAnalitica) {
        $oPdf->SetFont('arial', '', 6);
        $oPdf->Cell($iTamanhoTotal, $iAlturaLinha, "", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoSaldoInicial, $iAlturaLinha, "Saldo Inicial", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeInicial, $iAlturaLinha, "Qtd. Inicial", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoEntradas, $iAlturaLinha, "Entradas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEntradas, $iAlturaLinha, "Qtd. Entradas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoSaidas, $iAlturaLinha, "Saídas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeSaidas, $iAlturaLinha, "Qtd. Saídas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoSaldoFinal, $iAlturaLinha, "Saldo final", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEstoque, $iAlturaLinha, "Qtd. Estoque", "TB", 1, "C", 0);
    }

    $oPdf->SetFont('arial', 'b', 6);
    $oPdf->Cell($iTamanhoTotal, $iAlturaLinha, "TOTAL", "TB", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoSaldoInicial, $iAlturaLinha, db_formatar(($oConta->nSaldoAnterior), "f"),
        "LTBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeInicial, $iAlturaLinha, $oConta->iQuantidadeInicial, "LTBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoEntradas, $iAlturaLinha, db_formatar(($oConta->nTotalEntradas), "f"), "LTBR",
        0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEntradas, $iAlturaLinha, $oConta->iQuantidadeEntrada, "LTBR", 0, "R",
        1);
    $oPdf->Cell($oConfigItem->iTamanhoSaidas, $iAlturaLinha, db_formatar(($oConta->nTotalSaidas), "f"), "LTBR", 0,
        "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeSaidas, $iAlturaLinha, $oConta->iQuantidadeSaida, "LTBR", 0, "R", 1);

    if ($oConta->iQuantidadeEmEstoque <= 0) {
        $oConta->nSaldoFinal = "0.00";
        $oConta->iQuantidadeEmEstoque = "0.00";
    }

    $oPdf->Cell($oConfigItem->iTamanhoSaldoFinal, $iAlturaLinha, db_formatar(($oConta->nSaldoFinal), "f"), "LTBR", 0,
        "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEstoque, $iAlturaLinha, $oConta->iQuantidadeEmEstoque, "LTB", 1, "R",
        1);
}

/**
 * Motor para impressão dos itens
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param array $aItens
 * @param stdClass $oConfigItem
 */
function imprimirItens($oPdf, $iAlturaLinha, $aItens, $oConfigItem)
{
    $oTotal = new stdClass();
    $oTotal->nTotalSaidas = 0;
    $oTotal->nTotalEntradas = 0;
    $oTotal->nSaldoAnterior = 0;
    $oTotal->nSaldoFinal = 0;
    $oTotal->iQuantidadeEntrada = 0;
    $oTotal->iQuantidadeSaida = 0;
    $oTotal->iQuantidadeEmEstoque = 0;
    $oTotal->iQuantidadeInicial = 0;

    imprimirCabecalhoItem($oPdf, $iAlturaLinha, true, $oConfigItem);

    foreach ($aItens as $oItem) {

        if (!imprimirItem($oPdf, $iAlturaLinha, $oItem, $oConfigItem)) {
            continue;
        }

        $oTotal->nTotalSaidas += $oItem->nTotalSaidas;
        $oTotal->nTotalEntradas += $oItem->nTotalEntradas;
        $oTotal->nSaldoAnterior += $oItem->nSaldoAnterior;
        $oTotal->nSaldoFinal += $oItem->nSaldoFinal;
        $oTotal->iQuantidadeEntrada += $oItem->iQuantidadeEntrada;
        $oTotal->iQuantidadeSaida += $oItem->iQuantidadeSaida;
        $oTotal->iQuantidadeEmEstoque += $oItem->iQuantidadeEmEstoque;
        $oTotal->iQuantidadeInicial += (($oItem->iQuantidadeEmEstoque + $oItem->iQuantidadeSaida) - $oItem->iQuantidadeEntrada);
    }

    imprimirTotal($oPdf, $iAlturaLinha, $oTotal, $oConfigItem);
}

/**
 * Imprime o cabeçalho do item
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param bool $lInicio
 * @param stdClass $oConfigItem
 */
function imprimirCabecalhoItem($oPdf, $iAlturaLinha, $lInicio = false, $oConfigItem)
{
    if ($oPdf->GetY() > $oPdf->h - 35 || $lInicio) {
        if (!$lInicio) {
            imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
        }

        $oPdf->SetFont('arial', 'b', 6);
        $oPdf->Cell($oConfigItem->iTamanhoCodigoMaterial, $iAlturaLinha, "Cód. do Material", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoNomeMaterial, $iAlturaLinha, "Nome do Material", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoSaldoInicial, $iAlturaLinha, "Saldo Inicial", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeInicial, $iAlturaLinha, "Qtd. Inicial", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoEntradas, $iAlturaLinha, "Entradas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEntradas, $iAlturaLinha, "Qtd. Entradas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoSaidas, $iAlturaLinha, "Saídas", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeSaidas, $iAlturaLinha, "Qtd. Saída", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoSaldoFinal, $iAlturaLinha, "Saldo Final", "TBR", 0, "C", 0);
        $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEstoque, $iAlturaLinha, "Qtd. Estoque", "TB", 1, "C", 0);
    }
}

/**
 * Imprime os dados do item
 *
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oItem
 * @param stdClass $oConfigItem
 *
 * @return bool
 */
function imprimirItem($oPdf, $iAlturaLinha, $oItem, $oConfigItem)
{

    if ($oItem->nTotalSaidas == 0 &&
        $oItem->nTotalEntradas == 0 &&
        $oItem->nSaldoAnterior == 0 &&
        $oItem->iQuantidadeEntrada == 0 &&
        $oItem->iQuantidadeSaida == 0 &&
        $oItem->iQuantidadeEmEstoque == 0 &&
        $oItem->iQuantidadeInicial == 0 &&
        $oItem->nSaldoFinal == 0) {
        return false;
    }

    imprimirCabecalhoItem($oPdf, $iAlturaLinha, false, $oConfigItem);

    $oPdf->SetFont('arial', '', 6);

    if ($oItem->iQuantidadeInicial < 0) {
        $oItem->sDescricao .= "*";
    }

    $iQuantidadeInicial = number_format($oItem->iQuantidadeInicial, 0, '', '.');
    $iQuantidadeEntrada = number_format($oItem->iQuantidadeEntrada, 0, '', '.');
    $iQuantidadeSaida = number_format($oItem->iQuantidadeSaida, 0, '', '.');
    $iQuantidadeEmEstoque = number_format($oItem->iQuantidadeEmEstoque, 0, '', '.');

    $oPdf->Cell($oConfigItem->iTamanhoCodigoMaterial, $iAlturaLinha, $oItem->iCodigo, "TBR", 0, "C", 0);
    $oPdf->Cell($oConfigItem->iTamanhoNomeMaterial, $iAlturaLinha, substr($oItem->sDescricao, 0, 44), "TBR", 0, "L", 0);
    $oPdf->Cell($oConfigItem->iTamanhoSaldoInicial, $iAlturaLinha, db_formatar(($oItem->nSaldoAnterior), "f"), "TBR",
        0, "R", 0);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeInicial, $iAlturaLinha, $iQuantidadeInicial, "TBR", 0, "R", 0);
    $oPdf->Cell($oConfigItem->iTamanhoEntradas, $iAlturaLinha, db_formatar(($oItem->nTotalEntradas), "f"), "TBR", 0,
        "R", 0);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEntradas, $iAlturaLinha, $iQuantidadeEntrada, "TBR", 0, "R", 0);
    $oPdf->Cell($oConfigItem->iTamanhoSaidas, $iAlturaLinha, db_formatar(($oItem->nTotalSaidas), "f"), "TBR", 0, "R",
        0);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeSaidas, $iAlturaLinha, $iQuantidadeSaida, "TBR", 0, "R", 0);

//    if ($oItem->iQuantidadeEmEstoque == 0) {
//        $oItem->nSaldoFinal = "0.00";
//        $oItem->iQuantidadeEmEstoque = "0.00";
//    }

    $oPdf->Cell($oConfigItem->iTamanhoSaldoFinal, $iAlturaLinha, db_formatar(($oItem->nSaldoFinal), "f"), "TBR", 0,
        "R", 0);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEstoque, $iAlturaLinha, $iQuantidadeEmEstoque, "TB", 1, "R", 0);

    return true;
}

/**
 * Imprime as células de continuação da página
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 */
function imprimirContinuacaoPagina($oPdf, $iAlturaLinha)
{
    $oPdf->Cell(280, $iAlturaLinha, 'Continua na Página ' . ($oPdf->PageNo() + 1) . "/{nb}", "T", 1, "R", 0);
    $oPdf->AddPage();
    $oPdf->Ln(2);
    $oPdf->Cell(280, $iAlturaLinha, 'Continuação ' . ($oPdf->PageNo() - 1) . "/{nb}", "B", 1, "R", 0);
}

/**
 * Imprime a última linha do relatório com os totalizadores
 * @param PDF $oPdf
 * @param integer $iAlturaLinha
 * @param stdClass $oTotal
 */
function imprimirTotal($oPdf, $iAlturaLinha, $oTotal, $oConfigItem)
{
    if ($oPdf->GetY() > $oPdf->h - 32) {
        imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
    }

    $iTotalAcumulado = $oConfigItem->iTamanhoCodigoMaterial + $oConfigItem->iTamanhoNomeMaterial;
    $iQuantidadeInicial = number_format(($oTotal->iQuantidadeInicial), 0, '', '.');
    $iQuantidadeEntrada = number_format($oTotal->iQuantidadeEntrada, 0, '', '.');
    $iQuantidadeSaida = number_format($oTotal->iQuantidadeSaida, 0, '', '.');
    $iQuantidadeEmEstoque = number_format($oTotal->iQuantidadeEmEstoque, 0, '', '.');

    $oPdf->SetFont('arial', 'b', 6);
    $oPdf->Ln(2);
    $oPdf->Cell($iTotalAcumulado, $iAlturaLinha, "TOTAL ACUMULADO", "TBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoSaldoInicial, $iAlturaLinha, db_formatar(($oTotal->nSaldoAnterior), "f"),
        "LTBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeInicial, $iAlturaLinha, $iQuantidadeInicial, "LTBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoEntradas, $iAlturaLinha, db_formatar(($oTotal->nTotalEntradas), "f"), "LTBR",
        0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEntradas, $iAlturaLinha, $iQuantidadeEntrada, "LTBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoSaidas, $iAlturaLinha, db_formatar(($oTotal->nTotalSaidas), "f"), "LTBR", 0,
        "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeSaidas, $iAlturaLinha, $iQuantidadeSaida, "LTBR", 0, "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoSaldoFinal, $iAlturaLinha, db_formatar(($oTotal->nSaldoFinal), "f"), "LTBR", 0,
        "R", 1);
    $oPdf->Cell($oConfigItem->iTamanhoQuantidadeEstoque, $iAlturaLinha, $iQuantidadeEmEstoque, "LTBR", 1, "R", 1);
}

$oPdf->Output();
