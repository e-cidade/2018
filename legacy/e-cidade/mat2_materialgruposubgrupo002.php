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
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/materialestoque.model.php"));
require_once(modification("classes/db_matestoque_classe.php"));
require_once modification("libs/db_app.utils.php");
ini_set('display_errors', 0);

db_app::import("configuracao.DBEstrutura");
db_app::import("estoque.MaterialGrupo");
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

$oParametros = db_utils::postMemory($_GET);
$sWhere      = " m81_tipo in(1, 2) ";
$sDataFin    = implode('-',array_reverse(explode('/', $oParametros->datafin)));

if (trim($oParametros->datafin) != "--") {

  $sWhere .= " and m80_data <= '{$sDataFin}' ";
  $info   = "Até ".$oParametros->datafin;
}

$sWhereMater = '1 = 1';
if ($oParametros->listamat != "") {
  if (isset ($oParametros->vermat) && $oParametros->vermat == "com") {
    $sWhereMater .= " and m60_codmater in ({$oParametros->listamat})";
  } else {
    $sWhereMater .= " and m60_codmater not in ({$oParametros->listamat})";
  }
}

$sOrderByMaterial = "m60_codmater";
$sOrdemMaterial   = "Ordem: Código do Material";
if ( $oParametros->ordem == 2 ) {
  $sOrderByMaterial = "m60_descr";
  $sOrdemMaterial   = "Ordem: Descrição do Material";
}
if ( isset($oParametros->grupos) && trim($oParametros->grupos) != "" )  {
  $sWhereMater  .= " and db121_sequencial in ({$oParametros->grupos}) ";
}

$WhereAlmoxarifados = " ";
if (!empty($sAlmoxarifados)) {
  $WhereAlmoxarifados .= "             and db_almox.m91_codigo in ({$sAlmoxarifados})                                           ";
}

$sSqlMovimentacao  = " select  coalesce(sum(case when m81_tipo = 1 then m82_quant ";
$sSqlMovimentacao .= "             when m81_tipo = 2 then m82_quant*-1  end), 0) as quantidadeestoque";
$sSqlMovimentacao .= "                  from matestoqueini   ";
$sSqlMovimentacao .= "                       inner join matestoquetipo   on m80_codtipo        = m81_codtipo ";
$sSqlMovimentacao .= "                       inner join matestoqueinimei on m82_matestoqueini  = m80_codigo ";
$sSqlMovimentacao .= "                       inner join matestoqueitem   on m82_matestoqueitem = m71_codlanc ";
$sSqlMovimentacao .= "                       inner join matestoque       on m71_codmatestoque  = m70_codigo ";
$sSqlMovimentacao .= "                       inner join db_depart df     on m70_coddepto = coddepto ";
$sSqlMovimentacao .= "                                                  and instit       = ".db_getsession("DB_instit");
$sSqlMovimentacao .= "                       inner join matmater b       on b.m60_codmater       = m70_codmatmater ";
$sSqlMovimentacao .= "                       left  join db_almox  on db_almox.m91_depto = db_depart.coddepto ";
$sSqlMovimentacao .= "                 where {$sWhere} and instit = " . db_getsession("DB_instit") ;
$sSqlMovimentacao .= "                   and b.m60_codmater = matmater.m60_codmater {$WhereAlmoxarifados} ";
$sSqlMovimentacao .= "                   and df.coddepto = db_depart.coddepto";

$sSqlValorFinanceiro  = " select  coalesce(sum(case when m81_tipo = 1 then m89_valorfinanceiro ";
$sSqlValorFinanceiro .= "             when m81_tipo = 2 then m89_valorfinanceiro*-1  end), 0) as valorfinanceiro";
$sSqlValorFinanceiro .= "                  from matestoqueini   ";
$sSqlValorFinanceiro .= "                       inner join matestoquetipo      on m80_codtipo          = m81_codtipo ";
$sSqlValorFinanceiro .= "                       inner join matestoqueinimei    on m82_matestoqueini    = m80_codigo ";
$sSqlValorFinanceiro .= "                       inner join matestoqueitem      on m82_matestoqueitem   = m71_codlanc ";
$sSqlValorFinanceiro .= "                       inner join matestoqueinimeipm  on m89_matestoqueinimei = m82_codigo ";
$sSqlValorFinanceiro .= "                       inner join matestoque          on m71_codmatestoque    = m70_codigo ";
$sSqlValorFinanceiro .= "                       inner join db_depart df        on m70_coddepto = coddepto ";
$sSqlValorFinanceiro .= "                                                     and instit       = ".db_getsession("DB_instit");
$sSqlValorFinanceiro .= "                       inner join matmater b          on b.m60_codmater       = m70_codmatmater ";
$sSqlValorFinanceiro .= "                       left  join db_almox  on db_almox.m91_depto = db_depart.coddepto ";
$sSqlValorFinanceiro .= "                 where {$sWhere} and instit = " . db_getsession("DB_instit");
$sSqlValorFinanceiro .= "                   and b.m60_codmater = matmater.m60_codmater {$WhereAlmoxarifados} ";
$sSqlValorFinanceiro .= "                   and df.coddepto = db_depart.coddepto";

$sSqlMateriais  = " select m60_codmater as material,";
$sSqlMateriais .= "       m60_descr as descricao,";
$sSqlMateriais .= "       coalesce(db121_descricao, 'S/G') as descricaogrupo,";
$sSqlMateriais .= "       m65_sequencial as codigogrupo,";
$sSqlMateriais .= "       coalesce(db121_estrutural, '00.00') as estrutural,";
$sSqlMateriais .= "       ($sSqlMovimentacao) as quantidade,";
$sSqlMateriais .= "       ($sSqlValorFinanceiro)   as valorfinanceiro,";
$sSqlMateriais .= "       fc_estrutural_pai(COALESCE(db121_estrutural, '00.00')) as conta_sintetica";
$sSqlMateriais .= "  from matmater    ";
$sSqlMateriais .= "       inner join matmatermaterialestoquegrupo on m68_matmater             = m60_codmater     ";
$sSqlMateriais .= "       inner join materialestoquegrupo         on m68_materialestoquegrupo = m65_sequencial   ";
$sSqlMateriais .= "       inner join db_estruturavalor            on m65_db_estruturavalor    = db121_sequencial ";
$sSqlMateriais .= "       inner join matparam                     on db121_db_estrutura       = m90_db_estrutura ";

$sSqlMateriais .= "       inner join matestoque                   on m70_codmatmater          = m60_codmater     ";
$sSqlMateriais .= "       inner join db_depart                    on m70_coddepto             = coddepto ";
$sSqlMateriais .= "                                              and instit                   = ".db_getsession("DB_instit");
$sSqlMateriais .= "       left  join db_almox                     on db_almox.m91_depto       = db_depart.coddepto ";

$sSqlMateriais .= " where $sWhereMater {$WhereAlmoxarifados} and m60_ativo is true ";
$sSqlMateriais .= " order by db121_estrutural, $sOrderByMaterial";

$rsMateriais   = db_query($sSqlMateriais);
if (!$rsMateriais) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Ocorreu um erro ao buscar os materiais.');
  exit;
}
$iNumRowsItens = pg_num_rows($rsMateriais);
$aGrupos       = array();
$iMaximaCodigo = 0;
$aGlobal = array();

for ($i = 0; $i < $iNumRowsItens; $i++) {

  $oItem  = db_utils::fieldsMemory($rsMateriais, $i);
  $oGrupo = new MaterialGrupo($oItem->codigogrupo);

  if ($oItem->quantidade <= 0) {
    $oItem->quantidade      = 0;
    $oItem->valorfinanceiro = 0;
  }

  $oItem->valor = $oItem->valorfinanceiro;

  criaNopai($oGrupo, &$aGrupos, $oItem);

  if (strlen($oItem->material) > $iMaximaCodigo) {
     $iMaximaCodigo = strlen($oItem->material);
  }
  if (!isset($aGrupos[$oItem->estrutural])) {

    $aGrupos[$oItem->estrutural] = new stdClass();
    $aGrupos[$oItem->estrutural]->quantidade = 0;
    $aGrupos[$oItem->estrutural]->valor      = 0;
    $aGrupos[$oItem->estrutural]->nivel      = $oGrupo->getNivel();
    $aGrupos[$oItem->estrutural]->descricao  = $oGrupo->getDescricao();

  }
  $aGrupos[$oItem->estrutural]->quantidade += $oItem->quantidade;
  $aGrupos[$oItem->estrutural]->valor      += $oItem->valor;
  $aGrupos[$oItem->estrutural]->itens[]     = $oItem;

}

function criaNopai($oGrupo, &$aGrupos, $oItem) {

  global $aGlobal;
  if ($oGrupo->getEstruturaPai() != "") {

    if (!isset($aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()])) {

      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()] = new stdClass();
      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()]->quantidade = $oItem->quantidade;
      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()]->valor      = $oItem->valor;
      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()]->nivel      = $oGrupo->getEstruturaPai()->getNivel();
      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()]->descricao  = $oGrupo->getEstruturaPai()->getDescricao();

    } else {

      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()]->quantidade += $oItem->quantidade;
      $aGrupos[$oGrupo->getEstruturaPai()->getEstrutural()]->valor      += $oItem->valor;
    }
    criaNopai($oGrupo->getEstruturaPai(), &$aGrupos, $oItem);
  }
}

$sTipoEmissao = "Tipo de Emissão: Analítica ";
if ($oParametros->emissao == 2) {
  $sNegrito  = "";
  $sTipoEmissao = "Tipo de Emissão: Sintética";
}

$head3 = "Relatório de Material por Grupo/SubGrupo";
$head4 = "Posição até: ".$oParametros->datafin;
$head5 = $sOrdemMaterial;
$head6 = $sTipoEmissao;

$pdf = new PDF("P");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->SetAutoPageBreak(false);
$iAlt             = 4;
$lEscreveHeader   = true;
$nTotalItens      = 0;
$nValorTotal      = 0;
$lAddPage         = true;
$lGrupo           = true;
$nQuantidadeTotal = 0;
$nValorTotal      = 0;
$sNegrito         = "b";

ksort($aGrupos);

if ($iNumRowsItens == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não há vínculo de material com Grupo/Subgrupos.');
}

foreach ($aGrupos as $sEstruturalGrupo => $oGrupo) {

  if ($pdf->gety() > $pdf->h -20 || $lEscreveHeader) {

    if ($pdf->gety() > $pdf->h -20 || $lAddPage) {

      // Adiciona o Cabceçalho
      $pdf->AddPage();
      $pdf->setfont('arial', 'b', 8);
      $pdf->Cell(20 , $iAlt, "Código"     , "B", 0, "L", 0);
      $pdf->Cell(120, $iAlt, "Descrição"  , "B", 0, "C", 0);
      $pdf->Cell(20 , $iAlt, "Quantidade" , "B", 0, "R", 0);
      $pdf->Cell(30 , $iAlt, "Valor Total", "B", 1, "R", 0);
    }

    /**
     * Verifica se é nível 1 para imprimir no inicio da linha e soma
     * o valor total para apresentar no final do relatório.
     */
    if ($oGrupo->nivel == 1) {

      $nQuantidadeTotal += $oGrupo->quantidade;
      $nValorTotal      += $oGrupo->valor;
      $pdf->setfont('arial', 'b' , 6);
      $pdf->Cell(20 , $iAlt, "{$sEstruturalGrupo}"                 , 0, 0, "L");
      $pdf->Cell(120, $iAlt, "{$oGrupo->descricao}"                , 0, 0, "L", 0, '', ".");
      $pdf->Cell(20 , $iAlt, db_formatar($oGrupo->quantidade, "f") , 0, 0, "R", 0);
      $pdf->Cell(30 , $iAlt, db_formatar($oGrupo->valor, "f")      , 0, 1, "R", 0);

    } else {

      $pdf->setfont('arial',  $sNegrito, 6);
      $pdf->Cell(20 , $iAlt, "{$sEstruturalGrupo}"                                   , 0, 0, "L");
      $pdf->Cell(120, $iAlt, str_repeat("    ",$oGrupo->nivel)."{$oGrupo->descricao}", 0, 0, "L", 0, '', ".");
      $pdf->Cell(20 , $iAlt, db_formatar($oGrupo->quantidade, "f")                   , 0, 0, "R", 0);
      $pdf->Cell(30 , $iAlt, db_formatar($oGrupo->valor, "f")                        , 0, 1, "R", '', ".");
    }

    /**
     * Verifica se há itens a serem impressos. Se existir, só irá imprimir se o tipo de emissão for igual a 1
     * 1 = Analítica | 2 = Sintética
     */
    if ( isset($oGrupo->itens) && is_array($oGrupo->itens) && $oParametros->emissao == 1 ) {

      foreach ( $oGrupo->itens as $aDadosItem ) {

          if ($pdf->gety() > $pdf->h -20) {

			      // Adiciona o Cabceçalho
			      $pdf->AddPage();
			      $pdf->setfont('arial', 'b', 8);
			      $pdf->Cell(20 , $iAlt, "Código"     , "B", 0, "L", 0);
			      $pdf->Cell(120, $iAlt, "Descrição"  , "B", 0, "C", 0);
			      $pdf->Cell(20 , $iAlt, "Quantidade" , "B", 0, "R", 0);
			      $pdf->Cell(30 , $iAlt, "Valor Total", "B", 1, "R", 0);
			    }


        $pdf->setfont('arial',  '', 6);
        $sEstrutIdMater = "{$aDadosItem->estrutural}.".str_pad($aDadosItem->material, $iMaximaCodigo,"0", STR_PAD_LEFT);

        $pdf->Cell(20 , $iAlt, $sEstrutIdMater                                               , 0, 0, "L");
        $pdf->Cell(120, $iAlt, str_repeat("    ",$oGrupo->nivel+1)."{$aDadosItem->descricao}", 0, 0, "L", 0, '', ".");
        $pdf->Cell(20 , $iAlt, db_formatar($aDadosItem->quantidade, "f")                     , 0, 0, "R", 0);
        $pdf->Cell(30 , $iAlt, db_formatar($aDadosItem->valor, "f")                          , 0, 1, "R", '', ".");
      }
    }

    $lEscreveHeader = false;
    $lAddPage       = false;
  }
  $lEscreveHeader   = true;
}

$pdf->setfont('arial', 'b', 6);
$pdf->Cell(140, $iAlt, "Total Geral"                 , "T", 0, "R");
$pdf->Cell(20 , $iAlt, $nQuantidadeTotal             , "T", 0, "R", 0);
$pdf->Cell(30 , $iAlt, db_formatar($nValorTotal, "f"), "T", 1, "R");

$pdf->Output();
?>
