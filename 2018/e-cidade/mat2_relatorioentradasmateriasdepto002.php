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
require_once(modification("classes/db_matestoque_classe.php"));
require_once(modification("classes/db_matestoqueitem_classe.php"));
require_once(modification("classes/db_db_almox_classe.php"));
require_once(modification("classes/materialestoque.model.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once modification("libs/db_app.utils.php");
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
$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$cldb_almox = new cl_db_almox;

$clrotulo = new rotulocampo;
$clrotulo->label('m60_descr');
$clrotulo->label('descrdepto');

/**
 * busca o parametro de casas decimais para formatar o valor jogado na grid
 */

$oDaoParametros = new cl_empparametro;
$iAnoSessao = db_getsession("DB_anousu");
$sWherePeriodoParametro = " e39_anousu = {$iAnoSessao} ";
$sSqlPeriodoParametro = $oDaoParametros->sql_query_file(null, "e30_numdec", null, $sWherePeriodoParametro);
$rsPeriodoParametro = $oDaoParametros->sql_record($sSqlPeriodoParametro);
$iParametroNumeroDecimal = db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec;

$iAlt = 4;
$sOrderBy = "m80_data";

if (isset($oParametros->quebra) && $oParametros->quebra == "S") {
    $sOrderBy = 'm80_coddepto, m60_descr, m80_data';
} else {
    if ($oParametros->ordem == 'a') {
        $sOrderBy = 'm70_codmatmater, m80_data';
    } else {
        if ($oParametros->ordem == 'b') {
            $sOrderBy = 'm80_coddepto, m60_descr, m80_data';
        } else {
            if ($oParametros->ordem == 'c') {
                $sOrderBy = 'm60_descr';
            } else {
                if ($oParametros->ordem == 'd') {
                    $sOrderBy = "m80_data asc, m70_codmatmater";
                }
            }
        }
    }
}

if ($oParametros->listamatestoquetipo != "") {
    $sWhere = " m80_codtipo in ({$oParametros->listamatestoquetipo}) ";
} else {
    $sWhere = " m81_tipo  = 1 ";
}

$sWhere .= " and instit=" . db_getsession('DB_instit');
$sWhere .= " and m71_servico = false";

if ($oParametros->listaorgao != "") {
    $sWhere .= " and o40_orgao in ({$oParametros->listaorgao}) ";
    $sWhere .= " and o40_anousu = " . db_getsession('DB_anousu');
    $sWhere .= " and o40_instit=" . db_getsession('DB_instit');
}

if ($oParametros->listadepart != "") {
    if (isset($oParametros->verdepart) && $oParametros->verdepart == "com") {
        $sWhere .= " and m80_coddepto in ({$oParametros->listadepart})";
    } else {
        $sWhere .= " and m80_coddepto not in ({$oParametros->listadepart})";
    }
}

if ($oParametros->listamat != "") {
    if (isset($oParametros->vermat) && $oParametros->vermat == "com") {
        $sWhere .= " and m70_codmatmater in ({$oParametros->listamat})";
    } else {
        $sWhere .= " and m70_codmatmater not in ({$oParametros->listamat})";
    }
}

if ($oParametros->listausu != "") {
    if (isset($oParametros->verusu) && $oParametros->verusu == "com") {
        $sWhere .= " and m80_login in ({$oParametros->listausu})";
    } else {
        $sWhere .= " and m80_login not in ({$oParametros->listausu})";
    }
}


/*
 * implementado logica para ir até os grupos caso eles venham selecionados
*/

$sInnerJoinGrupos = '';
$sFiltroGrupo = '';

if (isset($oParametros->grupos) && trim($oParametros->grupos) != "") {
    $sWhere .= " and materialestoquegrupo.m65_db_estruturavalor in ({$oParametros->grupos}) ";
    $sInnerJoinGrupos = " 
         inner join matmatermaterialestoquegrupo on matmater.m60_codmater = matmatermaterialestoquegrupo.m68_matmater 
	       inner join materialestoquegrupo on matmatermaterialestoquegrupo.m68_materialestoquegrupo = materialestoquegrupo.m65_sequencial 
	";

    $sFiltroGrupo = 'Filtro por Grupos/Subgrupos';
    $head4 = $sFiltroGrupo;//"Relatório de Saída de Material por Departamento";
}

$sDataIni = implode('-', array_reverse(explode('/', $oParametros->dataini)));
$sDataFin = implode('-', array_reverse(explode('/', $oParametros->datafin)));

if ((trim($oParametros->dataini) != "--") && (trim($oParametros->datafin) != "--")) {
    $sWhere .= " and m80_data between '{$sDataIni}' and '{$sDataFin}' ";
    $info = "De " . $oParametros->dataini . " até " . $oParametros->datafin;
} else {
    if (trim($oParametros->dataini) != "--") {
        $sWhere .= " and m80_data >= '{$sDataIni}' ";
        $info = "Apartir de " . $oParametros->dataini;
    } else {
        if (trim($oParametros->datafin) != "--") {
            $sWhere .= " and m80_data <= '{$sDataFin}' ";
            $info = "Até " . $oParametros->datafin;
        } else {
            if ($sDataIni == $sDataFin) {
                $sWhere .= " and m80_data = '{$sDataFin}' ";
                $info = "Dia: " . $oParametros->datafin;
            }
        }
    }
}
$info_listar_serv = " LISTAR: TODOS";
$head3 = "Relatório de Entrada de Material por Departamento";
$head5 = "$info";
$head7 = "$info_listar_serv";

$sSqlSaidas = "SELECT m80_codigo,  ";
$sSqlSaidas .= "       m70_coddepto,  ";
$sSqlSaidas .= "       m70_codmatmater, ";
$sSqlSaidas .= "       m80_coddepto, ";
$sSqlSaidas .= "       m60_descr,  ";
$sSqlSaidas .= "       descrdepto,  ";
$sSqlSaidas .= "       sum(m82_quant) as qtde, ";
$sSqlSaidas .= "       m80_data,  ";
$sSqlSaidas .= "       m80_codtipo,  ";
$sSqlSaidas .= "       m83_coddepto,  ";
$sSqlSaidas .= "       m81_descr,  ";
$sSqlSaidas .= "       m41_codmatrequi, ";
$sSqlSaidas .= "       m89_precomedio as precomedio, ";
$sSqlSaidas .= "       sum(coalesce((m89_valorfinanceiro),0)) as m89_valorfinanceiro, ";
$sSqlSaidas .= "       m40_depto ";
$sSqlSaidas .= "  from matestoqueini  ";
$sSqlSaidas .= "       inner join matestoqueinimei    on m80_codigo              = m82_matestoqueini ";
$sSqlSaidas .= "       inner join matestoqueinimeipm  on m82_codigo              = m89_matestoqueinimei ";
$sSqlSaidas .= "       inner join matestoqueitem      on m82_matestoqueitem      = m71_codlanc  ";
$sSqlSaidas .= "       inner join matestoque          on m70_codigo              = m71_codmatestoque ";
$sSqlSaidas .= "       inner join matmater            on m70_codmatmater         = m60_codmater  ";
$sSqlSaidas .= "       inner join matestoquetipo      on m80_codtipo             = m81_codtipo  ";
$sSqlSaidas .= "       left  join db_depart           on m70_coddepto            = coddepto  ";
$sSqlSaidas .= "       left  join db_departorg        on db01_coddepto           = db_depart.coddepto  ";
$sSqlSaidas .= "                                     and db01_anousu             = " . db_getsession("DB_anousu");
$sSqlSaidas .= "       left  join orcorgao            on o40_orgao               = db_departorg.db01_orgao ";
$sSqlSaidas .= "                                     and o40_anousu              = " . db_getsession("DB_anousu");
$sSqlSaidas .= "       left  join matestoquetransf    on m83_matestoqueini       = m80_codigo   ";
$sSqlSaidas .= "       left  join matestoqueinimeiari on m49_codmatestoqueinimei = m82_codigo  ";
$sSqlSaidas .= "       left  join atendrequiitem      on m49_codatendrequiitem   = m43_codigo  ";
$sSqlSaidas .= "       left  join matrequiitem        on m41_codigo              = m43_codmatrequiitem ";

$sSqlSaidas .= $sInnerJoinGrupos; // string de inner caso venha grupos selecionados

$sSqlSaidas .= "       left  join matrequi            on m40_codigo              = m41_codmatrequi ";
$sSqlSaidas .= " where {$sWhere} ";
$sSqlSaidas .= " group by m80_codigo,  ";
$sSqlSaidas .= "          m70_coddepto,  ";
$sSqlSaidas .= "          m70_codmatmater, ";
$sSqlSaidas .= "          m80_data,  ";
$sSqlSaidas .= "          m40_depto,  ";
$sSqlSaidas .= "          m81_descr,  ";
$sSqlSaidas .= "          m80_codtipo,  ";
$sSqlSaidas .= "          m80_coddepto,  ";
$sSqlSaidas .= "          m83_coddepto,  ";
$sSqlSaidas .= "          descrdepto,  ";
$sSqlSaidas .= "          m89_precomedio,  ";
$sSqlSaidas .= "          m60_descr,  ";
$sSqlSaidas .= "          m41_codmatrequi ";
$sSqlSaidas .= " order by {$sOrderBy} ";

$rsSaidas = db_query($sSqlSaidas);
$iNumRows = pg_num_rows($rsSaidas);
$aLinhas = array();

for ($i = 0; $i < $iNumRows; $i++) {
    $oItem = db_utils::fieldsMemory($rsSaidas, $i);

    $oMaterialEstoque = new materialEstoque($oItem->m70_codmatmater);
    array_push($aLinhas, $oItem);
    unset($oItem);
}

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->SetAutoPageBreak(false);
$lEscreveHeader = true;
$nTotalItens = 0;
$nValorTotal = 0;

foreach ($aLinhas as $oLinha) {
    if ($pdf->gety() > $pdf->h - 20 || $lEscreveHeader) {
        $pdf->AddPage();
        $pdf->setfont('arial', 'b', 8);
        $pdf->Cell(15, $iAlt, "Material", "RTB", 0, "C", 1);
        $pdf->Cell(75, $iAlt, "Descrição do Material", 1, 0, "C", 1);
        $pdf->Cell(32, $iAlt, "Depto Origem", 1, 0, "C", 1);
        $pdf->Cell(32, $iAlt, "Depto Destino", 1, 0, "C", 1);
        $pdf->Cell(50, $iAlt, "Lançamento", 1, 0, "C", 1);
        $pdf->Cell(18, $iAlt, "Data", 1, 0, "C", 1);
        $pdf->Cell(18, $iAlt, "Preço Médio", 1, 0, "C", 1);
        $pdf->Cell(20, $iAlt, "Quantidade", 1, 0, "C", 1);
        $pdf->Cell(20, $iAlt, "Valor Total", "LTB", 1, "C", 1);
        $lEscreveHeader = false;
        $pdf->setfont('arial', '', 6);
    }

    $pdf->Cell(15, $iAlt, substr($oLinha->m70_codmatmater, 0, 40), "RTB", 0, "R");
    $pdf->Cell(75, $iAlt, $oLinha->m60_descr, 1, 0, "L");
    $pdf->Cell(32, $iAlt, substr($oLinha->m70_coddepto . " - " . $oLinha->descrdepto, 0, 25), 1, 0, "L");
    $iDeptoDestino = $oLinha->m40_depto;
    if ($oLinha->m83_coddepto != "") {
        $iDeptoDestino = $oLinha->m83_coddepto;
    }
    /**
     * consultamos a descricao do departamento de origem.
     */
    if ($iDeptoDestino != "") {
        $sSqlDeptoDestino = "select descrdepto from db_depart where coddepto = {$iDeptoDestino}";
        $rsDeptoDestino = db_query($sSqlDeptoDestino);
        $iDeptoDestino = "{$iDeptoDestino} - " . db_utils::fieldsMemory($rsDeptoDestino, 0)->descrdepto;
    }

    $pdf->Cell(32, $iAlt, substr($iDeptoDestino, 0, 24), 1, 0, "L");
    $iCodigoLancamento = $oLinha->m41_codmatrequi;

    if ($oLinha->m41_codmatrequi == "") {
        $iCodigoLancamento = $oLinha->m80_codigo;
    }

    $pdf->Cell(50, $iAlt, substr($oLinha->m81_descr, 0, 30) . "(" . $iCodigoLancamento . ")", 1, 0, "L");
    $pdf->Cell(18, $iAlt, db_formatar($oLinha->m80_data, "d"), 1, 0, "C");
    $pdf->Cell(18, $iAlt, number_format($oLinha->precomedio, $iParametroNumeroDecimal), 1, 0, "R");
    $pdf->Cell(20, $iAlt, $oLinha->qtde, 1, 0, "R");
    $pdf->Cell(20, $iAlt, db_formatar($oLinha->m89_valorfinanceiro, 'f'), "LTB", 1, "R");
    $nValorTotal += $oLinha->m89_valorfinanceiro;
    $nTotalItens += $oLinha->qtde;
}
$pdf->setfont('arial', 'b', 6);
$pdf->Cell(240, $iAlt, "Total", "RTB", 0, "R", 1);
$pdf->Cell(20, $iAlt, $nTotalItens, 1, 0, "R", 1);
$pdf->Cell(20, $iAlt, db_formatar($nValorTotal, "f"), "LTB", 1, "R", 1);
$pdf->Output();
