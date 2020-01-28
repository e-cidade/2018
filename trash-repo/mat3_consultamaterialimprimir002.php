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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/materialestoque.model.php");
require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
$oGet = db_utils::postMemory($_GET);
/**
 * Instancia de classes
 */
$oDaoMatMater           = db_utils::getDao('matmater');
$oDaoMatEstoque         = db_utils::getDao('matestoque');
$oDaoMatRequi           = db_utils::getDao('matrequi');
$oDaoMatEstoqueDev      = db_utils::getDao('matestoquedev');
$oDaoMatMaterEstoque    = db_utils::getDao('matmaterestoque');
$oDaoMatEstoqueItemLote = db_utils::getDao('matestoqueitemlote');
$oDaoAtendRequi         = db_utils::getDao('atendrequi');
$oDaoAlmoxDepto         = db_utils::getDao('db_almoxdepto');
$oDaoAlmox              = db_utils::getDao('db_almox');
$oDaoDepartOrg          = db_utils::getDao('db_departorg');
$oDaoMatParam           = db_utils::getDao('matparam');
$iAnoSessao             = db_getsession("DB_anousu");

$oMaterialEstoque = new materialEstoque($oGet->iMaterial);
$nPrecoMedio      = $oMaterialEstoque->getPrecoMedio();

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$iAltura = 4;

$head2 = "CONSULTA MATERIAL";
$head3 = "DATA EMISSÃO: ".date("d/m/Y", db_getsession("DB_datausu"));

/**
 * Informações do material
 */
$sSqlBuscaMatMater      = $oDaoMatMater->sql_query($oGet->iMaterial);
$rsBuscaMaterial        = $oDaoMatMater->sql_record($sSqlBuscaMatMater);
$oDadoMaterial          = db_utils::fieldsMemory($rsBuscaMaterial, 0);

/**
 * Quantidade atual e valor atual do estoque
 */
$sCamposMatEstoque      = "coalesce(sum(m70_valor), 0) as valor_total, coalesce(sum(m70_quant), 0) as quantidade_total";
$sSqlMatEstoqueValores  = $oDaoMatEstoque->sql_query_almox(null, $sCamposMatEstoque, null, "m70_codmatmater = {$oGet->iMaterial} ", "", true);
$rsMatEstoqueValores    = $oDaoMatEstoque->sql_record($sSqlMatEstoqueValores);
$oValorMaterial         = db_utils::fieldsMemory($rsMatEstoqueValores, 0);

$oPdf->addpage();
$oPdf->setfont('arial','b',8);
$oPdf->cell(280, $iAltura+2, "INFORMAÇÕES DO MATERIAL", 1, 1, "L", 1);
$oPdf->cell(20,  $iAltura+2, "Código", 1, 0, "C", 1);
$oPdf->cell(130, $iAltura+2, "Descrição", 1, 0, "C", 1);
$oPdf->cell(31,  $iAltura+2, "Unidade", 1, 0, "C", 1);
$oPdf->cell(33,  $iAltura+2, "Valor", 1, 0, "C", 1);
$oPdf->cell(33,  $iAltura+2, "Quantidade", 1, 0, "C", 1);
$oPdf->cell(33,  $iAltura+2, "Preço Médio", 1, 1, "C", 1);

$oPdf->setfont('arial','',8);
$oPdf->cell(20, $iAltura,  $oDadoMaterial->m60_codmater, 0, 0, "C", 0);
$oPdf->cell(130, $iAltura, substr($oDadoMaterial->m60_descr, 0, 100), 0, 0, "L", 0);
$oPdf->cell(30, $iAltura,  $oDadoMaterial->m61_descr, 0, 0, "C", 0);
$oPdf->cell(32, $iAltura,  db_formatar($oValorMaterial->valor_total, 'f'), 0, 0, "R", 0);
$oPdf->cell(32, $iAltura,  $oValorMaterial->quantidade_total, 0, 0, "R", 0);
$oPdf->cell(32, $iAltura,  $nPrecoMedio, 0, 1, "R", 0);




/**
 * Estado atual do estoque
 */
if (isset($oGet->lEstoque) && $oGet->lEstoque) {

  $sSqlTotaisTransferencias  = "select sum(coalesce(case when m81_tipo = 4 then m82_quant end, 0)) as saida";
  $sSqlTotaisTransferencias .= "  from matestoqueinimei ";
  $sSqlTotaisTransferencias .= "       inner join matestoqueitem on m71_codlanc       = m82_matestoqueitem";
  $sSqlTotaisTransferencias .= "       inner join matestoque trans  on m71_codmatestoque = trans.m70_codigo ";
  $sSqlTotaisTransferencias .= "       inner join matestoqueini  on m80_codigo        = m82_matestoqueini ";
  $sSqlTotaisTransferencias .= "       left  join matestoqueinil on m80_codigo        = m86_matestoqueini ";
  $sSqlTotaisTransferencias .= "       inner join matestoquetipo on m80_codtipo       = m81_codtipo ";
  $sSqlTotaisTransferencias .= " where trans.m70_codigo = matestoque.m70_codigo ";
  $sSqlTotaisTransferencias .= "   and m81_codtipo = 7";
  $sSqlTotaisTransferencias .= "   and m86_matestoqueini IS NULL";

  $sCamposMatEstoque  = "distinct m70_coddepto, descrdepto, m70_quant,";
  $sCamposMatEstoque .= "         round((m70_quant*$nPrecoMedio),2)::float as m70_valor,";
  $sCamposMatEstoque .= "         coalesce(({$sSqlTotaisTransferencias}),0) as transferencias";
  $sSqlMatEstoque     = $oDaoMatEstoque->sql_query_almox(null, $sCamposMatEstoque, null, "m70_codmatmater = {$oGet->iMaterial}", "", true);
  $rsMatEstoque       = $oDaoMatEstoque->sql_record($sSqlMatEstoque);
  $iLinhasMatEstoque  = $oDaoMatEstoque->numrows;


  if ($iLinhasMatEstoque > 0) {

    $oPdf->ln();
    $oPdf->setfont('arial','b',8);
    $oPdf->cell(280, $iAltura, "Informação: ESTOQUE", 1, 1, "L", 0);
    $oPdf->cell(100, $iAltura, "Departamento", 1, 0, "C", 1);
    $oPdf->cell(60,  $iAltura, "Quantidade em Estoque", 1, 0, "C", 1);
    $oPdf->cell(60,  $iAltura, "Valor em Estoque", 1, 0, "C", 1);
    $oPdf->cell(60,  $iAltura, "Transferências", 1, 1, "C", 1);

    /**
     * Percorremos o resultset com as informações do estoque imprimindo no relatório
     */
    $oPdf->setfont('arial','',8);
    for ($iEstoque = 0; $iEstoque < $iLinhasMatEstoque; $iEstoque++) {

      $oDadoEstoque = db_utils::fieldsMemory($rsMatEstoque, $iEstoque);
      $oPdf->cell(100, $iAltura, "{$oDadoEstoque->m70_coddepto} - {$oDadoEstoque->descrdepto}", 0, 0, "L", 0);
      $oPdf->cell(60, $iAltura, $oDadoEstoque->m70_quant,                                       0, 0, "R", 0);
      $oPdf->cell(60, $iAltura, db_formatar($oDadoEstoque->m70_valor, 'f'),                     0, 0, "R", 0);
      $oPdf->cell(60, $iAltura, $oDadoEstoque->transferencias,                                  0, 1, "R", 0);
    }
  }
}

/**
 * Lançamentos efetuados para o material (entradas e saidas)
 */
if (isset($oGet->lLancamentos) && $oGet->lLancamentos) {

  $rsBuscaParametro = db_query("select e30_numdec from empparametro where e39_anousu = {$iAnoSessao}");
  $iNumerosDecimais = db_utils::fieldsMemory($rsBuscaParametro, 0)->e30_numdec;

  $sSqlBuscaLancamento  = " select m80_codigo,";
  $sSqlBuscaLancamento .= "	       m81_descr,";
  $sSqlBuscaLancamento .= "        case when m81_entrada::integer = 1";
  $sSqlBuscaLancamento .= "             then 'Sim'";
  $sSqlBuscaLancamento .= "             else 'Nao'";
  $sSqlBuscaLancamento .= "             end  as m81_entrada,";
  $sSqlBuscaLancamento .= "        origem as lancamento_origem,";
  $sSqlBuscaLancamento .= "	       (select sum(m82_quant)";
  $sSqlBuscaLancamento .= "           from matestoqueinimei";
  $sSqlBuscaLancamento .= "             inner join matestoqueitem on m71_codlanc = m82_matestoqueitem";
  $sSqlBuscaLancamento .= "             inner join matestoque on m71_codmatestoque = m70_codigo";
  $sSqlBuscaLancamento .= "           where m82_matestoqueini = m80_codigo";
  $sSqlBuscaLancamento .= "             and m70_codmatmater = {$oGet->iMaterial}";
  $sSqlBuscaLancamento .= "         ) as quantidade,";
  $sSqlBuscaLancamento .= "        round(avg(m89_valorunitario), {$iNumerosDecimais}) as valor_unitario,";
  $sSqlBuscaLancamento .= "        round(avg(fc_calculapm), {$iNumerosDecimais}) as valor_precomedio,";
  $sSqlBuscaLancamento .= "	       descrdepto as departamento_origem,";
  $sSqlBuscaLancamento .= "	       coddepto_destino as departamento_destino,";
  $sSqlBuscaLancamento .= "	       m80_data,";
  $sSqlBuscaLancamento .= "	       m80_hora,";
  $sSqlBuscaLancamento .= "	       nome";
  $sSqlBuscaLancamento .= "   from ( select m80_codigo,";
  $sSqlBuscaLancamento .= "	                m81_descr,";
  $sSqlBuscaLancamento .= "                 m81_entrada,";
  $sSqlBuscaLancamento .= "	                m86_matestoqueini,";
  $sSqlBuscaLancamento .= "                 case";
  $sSqlBuscaLancamento .= "                   when m86_matestoqueini is not null then m86_matestoqueini";
  $sSqlBuscaLancamento .= "                   else ( case";
  $sSqlBuscaLancamento .= "                            when m52_codordem  is not null and m81_descr = 'ENTRADA DA ORDEM DE COMPRA' then m52_codordem";
  $sSqlBuscaLancamento .= "                            else null";
  $sSqlBuscaLancamento .= "                          end )";
  $sSqlBuscaLancamento .= "                 end as origem,";
  $sSqlBuscaLancamento .= "                 case when m81_tipo = 2 then 0 when m81_tipo = 1 then round(m89_valorunitario, 5)::numeric end as m89_valorunitario,";
  $sSqlBuscaLancamento .= "	                m82_quant,";
  $sSqlBuscaLancamento .= "                 round(m89_precomedio, {$iNumerosDecimais})::numeric as fc_calculapm ,";
  $sSqlBuscaLancamento .= "	                descrdepto,";
  $sSqlBuscaLancamento .= "	                m80_data,";
  $sSqlBuscaLancamento .= "	                m80_hora,";
  $sSqlBuscaLancamento .= "	                nome,";
  $sSqlBuscaLancamento .= "                (select db_depart.descrdepto";
  $sSqlBuscaLancamento .= "	                  from matestoqueinimei";
  $sSqlBuscaLancamento .= "	                       inner join matestoqueitem      on matestoqueitem.m71_codlanc                  = matestoqueinimei.m82_matestoqueitem";
  $sSqlBuscaLancamento .= "	                       inner join matestoqueinimeiari on matestoqueinimeiari.m49_codmatestoqueinimei = matestoqueinimei.m82_codigo";
  $sSqlBuscaLancamento .= "	                       inner join atendrequiitem      on atendrequiitem.m43_codigo                   = matestoqueinimeiari.m49_codatendrequiitem";
  $sSqlBuscaLancamento .= "	                       inner join matrequiitem        on matrequiitem.m41_codigo                     = atendrequiitem.m43_codmatrequiitem";
  $sSqlBuscaLancamento .= "	                       inner join matrequi            on matrequi.m40_codigo                         = matrequiitem.m41_codmatrequi";
  $sSqlBuscaLancamento .= "	                       inner join db_depart           on db_depart.coddepto                          = matrequi.m40_depto";
  $sSqlBuscaLancamento .= "	                 where matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo";
  $sSqlBuscaLancamento .= "	                 limit 1) as coddepto_destino";
  $sSqlBuscaLancamento .= "            from matestoqueini";
  $sSqlBuscaLancamento .= "	                inner join matestoquetipo on m80_codtipo = m81_codtipo";
  $sSqlBuscaLancamento .= "	                inner join matestoqueinimei on m82_matestoqueini = m80_codigo";
  $sSqlBuscaLancamento .= "	                inner join db_usuarios on m80_login = id_usuario";
  $sSqlBuscaLancamento .= "	                inner join db_depart on m80_coddepto = coddepto";
  $sSqlBuscaLancamento .= "	                inner join matestoqueitem on m82_matestoqueitem = m71_codlanc";
  $sSqlBuscaLancamento .= "	                inner join matestoque on m71_codmatestoque = m70_codigo";
  $sSqlBuscaLancamento .= "	                inner join matmater on m60_codmater = m70_codmatmater";
  $sSqlBuscaLancamento .= "                 left join matestoqueitemoc on  m71_codlanc = m73_codmatestoqueitem and m73_cancelado is false";
  $sSqlBuscaLancamento .= "                 left join matordemitem on m52_codlanc = m73_codmatordemitem";
  $sSqlBuscaLancamento .= "	                left join matestoqueinill on m87_matestoqueini = m80_codigo";
  $sSqlBuscaLancamento .= "	                left join matestoqueinil on m86_codigo = m87_matestoqueinil";
  $sSqlBuscaLancamento .= "                 left join matestoqueinimeipm on m82_codigo  = m89_matestoqueinimei";
  $sSqlBuscaLancamento .= "           where m70_codmatmater = {$oGet->iMaterial} and m71_servico is false";
  $sSqlBuscaLancamento .= "         ) as x ";
  $sSqlBuscaLancamento .= "group by m80_data,";
  $sSqlBuscaLancamento .= "         m80_codigo,";
  $sSqlBuscaLancamento .= "	        m81_descr,";
  $sSqlBuscaLancamento .= "         m81_entrada,";
  $sSqlBuscaLancamento .= "	        m86_matestoqueini,";
  $sSqlBuscaLancamento .= "	        descrdepto,";
  $sSqlBuscaLancamento .= "	        m80_hora,";
  $sSqlBuscaLancamento .= "	        nome,";
  $sSqlBuscaLancamento .= "         origem,coddepto_destino ";
  $sSqlBuscaLancamento .= "order by m80_data,";
  $sSqlBuscaLancamento .= "         m80_codigo,";
  $sSqlBuscaLancamento .= "         m81_descr,";
  $sSqlBuscaLancamento .= "         m81_entrada,";
  $sSqlBuscaLancamento .= "         m86_matestoqueini,";
  $sSqlBuscaLancamento .= "         descrdepto,";
  $sSqlBuscaLancamento .= "         m80_hora,";
  $sSqlBuscaLancamento .= "         nome,";
  $sSqlBuscaLancamento .= "         origem";

  $rsBuscaLancamento    = db_query($sSqlBuscaLancamento);
  $iLinhasLancamento    = pg_num_rows($rsBuscaLancamento);
  if ($iLinhasLancamento > 0) {

    $oPdf->ln();
    $oPdf->setfont('arial','b',8);
    /*
     * Primeira linha dos lançamentos do material
    */
    $oPdf->cell(280, $iAltura, "Informação: LANÇAMENTOS", 1, 1, "L", 0);
    $oPdf->cell(20,  $iAltura, "Lançamento",              1, 0, "C", 1);
    $oPdf->cell(140, $iAltura, "Descrição",               1, 0, "C", 1);
    $oPdf->cell(30,  $iAltura, "Entrada",                 1, 0, "C", 1);
    $oPdf->cell(30,  $iAltura, "Quantidade.",             1, 0, "C", 1);
    $oPdf->cell(30,  $iAltura, "Valor Unitário",          1, 0, "C", 1);
    $oPdf->cell(30,  $iAltura, "Preço Médio",             1, 1, "C", 1);
    /*
     * Segunda linha dos lançamentos do material
    */
    $oPdf->cell(75,  $iAltura, "Departamento Origem",     1, 0, "C", 1);
    $oPdf->cell(75,  $iAltura, "Departamento Destino",    1, 0, "C", 1);
    $oPdf->cell(30,  $iAltura, "Data Lançamento",         1, 0, "C", 1);
    $oPdf->cell(30,  $iAltura, "Hora Lançamento",         1, 0, "C", 1);
    $oPdf->cell(70,  $iAltura, "Usuário",                 1, 1, "C", 1);

    $oPdf->setfont("arial", "", 8);
    for ($iLancamento = 0; $iLancamento < $iLinhasLancamento; $iLancamento++) {

      $oLancamento = db_utils::fieldsMemory($rsBuscaLancamento, $iLancamento);

      /*
       * Imprime os dados da primeira linha
      */
      $oPdf->cell(20,  $iAltura, $oLancamento->m80_codigo,                 0, 0, "C", 0);
      $oPdf->cell(140, $iAltura, substr($oLancamento->m81_descr, 0, 120),  0, 0, "L", 0);
      $oPdf->cell(30,  $iAltura, $oLancamento->m81_entrada,                0, 0, "C", 0);
      $oPdf->cell(30,  $iAltura, $oLancamento->quantidade,                 0, 0, "R", 0);
      $oPdf->cell(30,  $iAltura, $oLancamento->valor_unitario,             0, 0, "R", 0);
      $oPdf->cell(30,  $iAltura, $oLancamento->valor_precomedio,           0, 1, "R", 0);
      /*
       * Imprime os dados da segunda linha
      */
      $oPdf->cell(75,  $iAltura, substr($oLancamento->departamento_origem, 0, 65),  'B', 0, "L", 0);
      $oPdf->cell(75,  $iAltura, substr($oLancamento->departamento_destino, 0, 65), 'B', 0, "L", 0);
      $oPdf->cell(30,  $iAltura, db_formatar($oLancamento->m80_data, 'd'),          'B', 0, "C", 0);
      $oPdf->cell(30,  $iAltura, $oLancamento->m80_hora,                            'B', 0, "C", 0);
      $oPdf->cell(70,  $iAltura, substr($oLancamento->nome, 0, 60),                 'B', 1, "L", 0);
    }
  }
}

/**
 * Requisições efetuadas para o material
 */
if (isset($oGet->lRequisicoes) && $oGet->lRequisicoes) {

  $sCamposRequisicao  = "distinct m40_codigo,";
  $sCamposRequisicao .= "         m40_data,";
  $sCamposRequisicao .= "         m40_hora,";
  $sCamposRequisicao .= "         m40_depto || ' - ' || descrdepto as departamento,";
  $sCamposRequisicao .= "         m40_login || ' - ' || nome as usuario,";
  $sCamposRequisicao .= "         m40_obs";
  $sWhereRequisicao   = "m41_codmatmater = {$oGet->iMaterial}";
  $sSqlRequisicao    = $oDaoMatRequi->sql_query_requisaida(null, $sCamposRequisicao, null, $sWhereRequisicao);
  $rsRequisicao      = $oDaoMatRequi->sql_record($sSqlRequisicao);
  $iLinhasRequisicao = $oDaoMatRequi->numrows;

  if ($iLinhasRequisicao > 0) {

    $oPdf->ln();
    $oPdf->setfont('arial','b',8);
    /*
     * Primeira linha dos lançamentos do material
    */
    $oPdf->cell(280, $iAltura, "Informação: REQUISIÇÕES", 1, 1, "L", 0);
    $oPdf->cell(26,  $iAltura, "Código",                  1, 0, "C", 1);
    $oPdf->cell(26,  $iAltura, "Data",                    1, 0, "C", 1);
    $oPdf->cell(26,  $iAltura, "Hora",                    1, 0, "C", 1);
    $oPdf->cell(101,  $iAltura, "Departamento.",           1, 0, "C", 1);
    $oPdf->cell(101,  $iAltura, "Usuário",                 1, 1, "C", 1);
    /*
     * Multicell para a impressão das observações da requisição
     */
    $oPdf->MultiCell(280, $iAltura, "Observação", 1, "L", 1);
    $oPdf->setfont('arial','',8);
    for ($iRequisicao = 0; $iRequisicao < $iLinhasRequisicao; $iRequisicao++) {

      $oRequisicao = db_utils::fieldsMemory($rsRequisicao, $iRequisicao);
      $oPdf->cell(26,  $iAltura, $oRequisicao->m40_codigo,                 0, 0, "C", 0);
      $oPdf->cell(26,  $iAltura, db_formatar($oRequisicao->m40_data, 'd'), 0, 0, "C", 0);
      $oPdf->cell(26,  $iAltura, $oRequisicao->m40_hora,                   0, 0, "C", 0);
      $oPdf->cell(101, $iAltura, $oRequisicao->departamento,               0, 0, "L", 0);
      $oPdf->cell(101, $iAltura, $oRequisicao->usuario,                    0, 1, "L", 0);

      $oPdf->MultiCell(280, $iAltura, $oRequisicao->m40_obs, "B", "L", 0);
    }
  }
}

/**
 * Atendimento das requisições do material
 */
if (isset($oGet->lAtendimentos) && $oGet->lAtendimentos) {

  $sCamposAtendimento  = "distinct m42_codigo as atendimento,";
  $sCamposAtendimento .= "         m42_data,";
  $sCamposAtendimento .= "         m42_hora,";
  $sCamposAtendimento .= "         m42_depto || ' - ' || descrdepto as departamento,";
  $sCamposAtendimento .= "         m42_login || ' - ' || nome as usuario,";
  $sCamposAtendimento .= "         m40_codigo as requisicao";
  $sWhereAtendimento   = "m41_codmatmater = {$oGet->iMaterial}";
  $sSqlAtendimentos    = $oDaoAtendRequi->sql_query_requi(null, $sCamposAtendimento, null, $sWhereAtendimento);
  $rsAtendimentos      = $oDaoAtendRequi->sql_record($sSqlAtendimentos);
  $iLinhasAtendimentos = $oDaoAtendRequi->numrows;

  if ($iLinhasAtendimentos > 0) {

    $oPdf->ln();
    $oPdf->setfont('arial','b',8);
    /*
     * Cabeçalho dos atendimentos das requisições do item
     */
    $oPdf->cell(280, $iAltura, "Informação: ATENDIMENTOS", 1, 1, "L", 0);
    $oPdf->cell(20,  $iAltura, "Atendimento",              1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Data",                     1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Hora",                     1, 0, "C", 1);
    $oPdf->cell(100, $iAltura, "Departamento.",            1, 0, "C", 1);
    $oPdf->cell(100, $iAltura, "Usuário",                  1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Requisicao",               1, 1, "C", 1);

    /*
     * Percorremos o resultset imprimindo os atendimentos efetuados
     */
    $oPdf->setfont('arial','',8);
    for ($iAtendimento = 0; $iAtendimento < $iLinhasAtendimentos; $iAtendimento++) {

      $oAtendimento = db_utils::fieldsMemory($rsAtendimentos, $iAtendimento);
      $oPdf->cell(20,  $iAltura, $oAtendimento->atendimento,                 0, 0, "C", 0);
      $oPdf->cell(20,  $iAltura, db_formatar($oAtendimento->m42_data, 'd'),  0, 0, "C", 0);
      $oPdf->cell(20,  $iAltura, $oAtendimento->m42_hora,                    0, 0, "C", 0);
      $oPdf->cell(100, $iAltura, $oAtendimento->departamento,                0, 0, "L", 0);
      $oPdf->cell(100, $iAltura, $oAtendimento->usuario,                     0, 0, "L", 0);
      $oPdf->cell(20,  $iAltura, $oAtendimento->requisicao,                  0, 1, "C", 0);
    }
  }
}

/**
 * Devoluções efetuadas para o material
 */
if (isset($oGet->lDevolucoes) && $oGet->lDevolucoes) {

  $sCamposDevolucoes  = "distinct m45_codigo,";
  $sCamposDevolucoes .= "m45_data,";
  $sCamposDevolucoes .= "m45_hora,";
  $sCamposDevolucoes .= "m45_depto || ' - ' || descrdepto as departamento,";
  $sCamposDevolucoes .= "m45_login || ' - ' || nome as usuario, ";
  $sCamposDevolucoes .= "m45_obs,";
  $sCamposDevolucoes .= "m42_codigo";
  $sWhereDevolucoes   = "m41_codmatmater = {$oGet->iMaterial}";
  $sSqlDevolucoes     = $oDaoMatEstoqueDev->sql_query_itens_devolvidos(null, $sCamposDevolucoes, null, $sWhereDevolucoes);
  $rsDevolucoes       = $oDaoMatEstoqueDev->sql_record($sSqlDevolucoes);
  $iLinhasDevolucoes  = $oDaoMatEstoqueDev->numrows;

  if ($iLinhasDevolucoes) {

    $oPdf->ln();
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(280, $iAltura, "Informação: DEVOLUÇÕES", 1, 1, "L", 0);
    $oPdf->cell(20,  $iAltura, "Código",                 1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Data",                   1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Hora",                   1, 0, "C", 1);
    $oPdf->cell(100, $iAltura, "Departamento.",          1, 0, "C", 1);
    $oPdf->cell(100, $iAltura, "Usuário",                1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Atendimento",            1, 1, "C", 1);
    $oPdf->MultiCell(280, $iAltura, "Observação", 1, "L", 1);

    for ($iDevolucao = 0; $iDevolucao < $iLinhasDevolucoes; $iDevolucao++) {

      $oPdf->setfont('arial', '', 8);
      $oDevolucao = db_utils::fieldsMemory($rsDevolucoes, $iDevolucao);
      $oPdf->cell(20,  $iAltura, $oDevolucao->m45_codigo ,                0, 0, "C", 0);
      $oPdf->cell(20,  $iAltura, db_formatar($oDevolucao->m45_data, 'd'), 0, 0, "C", 0);
      $oPdf->cell(20,  $iAltura, $oDevolucao->m45_hora ,                  0, 0, "C", 0);
      $oPdf->cell(100, $iAltura, $oDevolucao->departamento,               0, 0, "L", 0);
      $oPdf->cell(100, $iAltura, $oDevolucao->usuario,                    0, 0, "L", 0);
      $oPdf->cell(20,  $iAltura, $oDevolucao->m42_codigo,                 0, 1, "C", 0);
      $oPdf->MultiCell(280, $iAltura, $oDevolucao->m45_obs, "B", "L", 0);
    }
  }
}

/**
 * Configurações para a busca do ponto de pedido de um material
 */
if (isset($oGet->lPontoPedido) && $oGet->lPontoPedido) {

  $sCamposPontoPedido = "distinct m91_depto ||' - '|| descrdepto as departamento, m64_estoqueminimo, m64_estoquemaximo, m64_pontopedido";
  $sWherePontoPedido  = "m64_matmater = {$oGet->iMaterial}";
  $sSqlPontoPedido    = $oDaoMatMaterEstoque->sql_query(null, $sCamposPontoPedido, null, $sWherePontoPedido);
  $rsPontoPedido      = $oDaoMatMaterEstoque->sql_record($sSqlPontoPedido);
  $iLinhasPontoPedido = $oDaoMatMaterEstoque->numrows;

  if ($iLinhasPontoPedido > 0) {

    if ($oPdf->GetY() > 180) {
      $oPdf->addpage("L");
    }

    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(280, $iAltura, "Informação: PONTO DE PEDIDO", 1, 1, "L", 0);
    $oPdf->cell(150, $iAltura, "Departamento",    1, 0, "C", 1);
    $oPdf->cell(40,  $iAltura, "Estoque Mínimo",  1, 0, "C", 1);
    $oPdf->cell(45,  $iAltura, "Estoque Máximo",  1, 0, "C", 1);
    $oPdf->cell(45,  $iAltura, "Ponto de Pedido", 1, 1, "C", 1);

    $oPdf->setfont('arial', '', 8);
    for ($iPontoPedido = 0; $iPontoPedido < $iLinhasPontoPedido; $iPontoPedido++) {

      $oPontoPedido = db_utils::fieldsMemory($rsPontoPedido, $iPontoPedido);
      $oPdf->cell(150, $iAltura, $oPontoPedido->departamento,       0, 0, "L", 0);
      $oPdf->cell(40,  $iAltura, $oPontoPedido->m64_estoqueminimo,  0, 0, "C", 0);
      $oPdf->cell(45,  $iAltura, $oPontoPedido->m64_estoquemaximo,  0, 0, "C", 0);
      $oPdf->cell(45,  $iAltura, $oPontoPedido->m64_pontopedido,    0, 1, "C", 0);
    }

  }
}

/**
 * Configurações para a busca dos lotes de um material
 */
if (isset($oGet->lLotes) && $oGet->lLotes) {

  $sCamposLotes   = " m77_sequencial,";
  $sCamposLotes  .= " m77_lote,";
  $sCamposLotes  .= " m77_dtvalidade,";
  $sCamposLotes  .= " m81_descr,";
  $sCamposLotes  .= " m82_quant,";
  $sCamposLotes  .= " m71_quantatend,";
  $sCamposLotes  .= " m80_data,";
  $sCamposLotes  .= " descrdepto";
  $sOrderLotes    = "m80_data, m80_codigo";
  $sWhereLotes    = "m70_codmatmater = {$oGet->iMaterial}";
  $sSqlLotes      = $oDaoMatEstoqueItemLote->sql_query_informacoes_lote(null, $sCamposLotes, $sOrderLotes, $sWhereLotes);
  $rsLotes        = $oDaoMatEstoqueItemLote->sql_record($sSqlLotes);
  $iLinhasLotes   = $oDaoMatEstoqueItemLote->numrows;

  if ($iLinhasLotes > 0) {

    if ($oPdf->GetY() > 180) {
      $oPdf->addpage("L");
    }
    $oPdf->ln();
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(280, $iAltura, "Informação: LOTES", 1, 1, "L", 0);
    $oPdf->cell(10,  $iAltura, "Seq.",        1, 0, "C", 1);
    $oPdf->cell(45,  $iAltura, "Lote",              1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Data Validade",     1, 0, "C", 1);
    $oPdf->cell(56,  $iAltura, "Tipo",              1, 0, "C", 1);
    $oPdf->cell(17,  $iAltura, "Quantidade",        1, 0, "C", 1);
    $oPdf->cell(20,  $iAltura, "Qtd. Atendida",     1, 0, "C", 1);
    $oPdf->cell(17,  $iAltura, "Data",              1, 0, "C", 1);
    $oPdf->cell(95, $iAltura, "Departamento",      1, 1, "C", 1);

    for ($iLote = 0; $iLote < $iLinhasLotes; $iLote++) {

      $oLote = db_utils::fieldsMemory($rsLotes, $iLote);
      $oPdf->cell(10,  $iAltura, $oLote->m77_sequencial                  , 0, 0, "C", 0);
      $oPdf->cell(45,  $iAltura, $oLote->m77_lote                        , 0, 0, "L", 0);
      $oPdf->cell(20,  $iAltura, db_formatar($oLote->m77_dtvalidade, 'd'), 0, 0, "C", 0);
      $oPdf->cell(56,  $iAltura, $oLote->m81_descr                       , 0, 0, "L", 0);
      $oPdf->cell(17,  $iAltura, $oLote->m82_quant                       , 0, 0, "C", 0);
      $oPdf->cell(20,  $iAltura, $oLote->m71_quantatend                  , 0, 0, "C", 0);
      $oPdf->cell(17,  $iAltura, db_formatar($oLote->m80_data, 'd')      , 0, 0, "C", 0);
      $oPdf->cell(95, $iAltura, $oLote->descrdepto                      , 0, 1, "L", 0);
    }
  }
}

/**
 * Imprime as notas fiscais do material, caso exista
 */
if (isset($oGet->lNotaFiscal) && $oGet->lNotaFiscal) {

  $sCamposMaterial  = "e69_numero, e69_codnota, e69_dtnota, ";
  $sCamposMaterial .= "matestoqueitemnotafiscalmanual.m79_notafiscal, matestoqueitemnotafiscalmanual.m79_data";
  $sWhereMaterial   = "    matmater.m60_codmater = {$oGet->iMaterial}";
  $sWhereMaterial  .= "and matestoqueini.m80_codtipo in (1, 3, 12, 15)";
  $sWhereMaterial  .= "and (empnota.e69_codnota is not null or matestoqueitemnotafiscalmanual.m79_matestoqueitem is not null)";
  $sSqlMaterial     = $oDaoMatMater->sql_query_material_nota(null, $sCamposMaterial, null, $sWhereMaterial);
  $rsBuscaNotas     = $oDaoMatMater->sql_record($sSqlMaterial);
  $iLinhasMaterial  = $oDaoMatMater->numrows;

  if ($iLinhasMaterial) {

    if ($oPdf->GetY() > 180) {
      $oPdf->addpage("L");
    }

    $oPdf->ln();
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(180, $iAltura, "Informação: NOTA FISCAL", 1, 1, "L", 0);
    $oPdf->cell(100, $iAltura, "Tipo de Entrada",         1, 0, "C", 1);
    $oPdf->cell(40,  $iAltura, "Número da Nota",          1, 0, "C", 1);
    $oPdf->cell(40,  $iAltura, "Data da Nota",            1, 1, "C", 1);

    $oPdf->setfont('arial', '', 8);
    for ($iNota = 0; $iNota < $iLinhasMaterial; $iNota++) {

      $oNota = db_utils::fieldsMemory($rsBuscaNotas, $iNota);

      $sTipoEntrada = "Ordem de Compra";
      $iCodigoNota  = $oNota->e69_numero;
      $dtDataNota   = $oNota->e69_dtnota;
      if (empty($oNota->e69_numero)) {

        $sTipoEntrada = "Manual";
        $iCodigoNota  = $oNota->m79_notafiscal;
        $dtDataNota   = $oNota->m79_data;
      }

      $oPdf->cell(100, $iAltura, $sTipoEntrada                , 0, 0, "L", 0);
      $oPdf->cell(40,  $iAltura, $iCodigoNota                 , 0, 0, "R", 0);
      $oPdf->cell(40,  $iAltura, db_formatar($dtDataNota, 'd'), 0, 1, "C", 0);
    }
  }
}

$oPdf->Output();
?>