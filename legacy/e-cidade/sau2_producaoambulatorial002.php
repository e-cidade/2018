<?
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

require_once('fpdf151/pdf.php');
require_once('libs/db_utils.php');

$oDaoProntProced  = db_utils::getdao('prontproced_ext');

function getTotalPorProcedimento ($sListaProcedimentos, $sIntervalo, $sd02_i_codigo = '') {
  
  if ($sListaProcedimentos == '' || $sIntervalo == '') {
    return null;
  }
  global $oDaoProntProced;
  $sCampos = " count(*) as total_realizado, sd63_c_procedimento, sd63_c_nome";
  $sWhere  = $sIntervalo;
  $sWhere .= " and sd63_c_procedimento in (".$sListaProcedimentos.") ";
  if ($sd02_i_codigo != '') {
    $sWhere .= " and sd02_i_codigo = $sd02_i_codigo ";
  }
  $sGroup  = " group by sd63_c_procedimento, sd63_c_nome ";
  $sOrder  = " sd63_c_nome ";
  $sSql    = $oDaoProntProced->sql_query_producao("", $sCampos, $sOrder, $sWhere.$sGroup);
  $rs      = $oDaoProntProced->sql_record($sSql);
  if ($oDaoProntProced->numrows > 0) {
    
    $aData = array();
    for ($iI = 0; $iI < $oDaoProntProced->numrows; $iI++) {
    
      $aData[$iI] = db_utils::fieldsmemory($rs, $iI);
      
    }
    return $aData;
    
  } 
  return null;
  
}

function getTotalEstrutura ($sd02_i_codigo, $sEstrutura, $sIntervalo) {
  
  global $oDaoProntProced;
  $sCampos = " count(*) as total_realizado ";
  $sWhere  = $sIntervalo;
  if ($sd02_i_codigo != '') {
    $sWhere .= " and sd02_i_codigo = $sd02_i_codigo ";
  }
  $sWhere .= " and sd63_c_procedimento ilike '";
  $sWhere .= $sEstrutura;
  $sWhere .= "%' ";    
  $sSql    = $oDaoProntProced->sql_query_producao("", $sCampos, '', $sWhere);
  $rs      = $oDaoProntProced->sql_record($sSql);
  if ($oDaoProntProced->numrows > 0) {
  
    $oData = db_utils::fieldsmemory($rs, 0);
    return $oData->total_realizado;
    
  } 
  return 0;
  
}

function impTopo($oPdf, $sd02_i_codigo, $descrdepto) {

  global $iCont;
  if ($iCont >= 35) {
    novaPagina($oPdf);
  }
  if ($iCont != 0) {
    
    $oPdf->setXY($oPdf->getX(), $oPdf->getY() + 4);
    $iCont++;
  
  }
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 9);
  $oPdf->cell(280, 4, $sd02_i_codigo." - ".$descrdepto, 1, 1, "L", 1); 
  $iCont++;
    
}

function impCabecalho($oPdf) {
  
  global $iCont;
  if ($iCont >= 35) {
    novaPagina($oPdf);
  }
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(50, 4, "Estrutura ", 1, 0, "L", 1);
  $oPdf->cell(170, 4, "Descrição", 1, 0, "L", 1);
  $oPdf->cell(60, 4, "Realizado", 1, 1, "L", 1);  
  $oPdf->setfillcolor(255);
  $iCont++;
  
}

function impProcedimentos($oPdf, $aData) {
  
  global $iCont;
  global $iProducao;
  $oPdf->setfont('arial', '', 8);
  for ($iI = 0; $iI < count($aData); $iI++) {
      
    if ($iCont >= 36) {
      
      novaPagina($oPdf);
      impCabecalho($oPdf);
      $oPdf->setfont('arial', '', 8);
    }
    $iProducao += (int)$aData[$iI]->total_realizado; 
    $oPdf->cell(50, 4, $aData[$iI]->sd63_c_procedimento, 1, 0, "L", 1);
    $oPdf->cell(170, 4, $aData[$iI]->sd63_c_nome, 1, 0, "L", 1);
    $oPdf->cell(60, 4, $aData[$iI]->total_realizado, 1, 1, "L", 1); 
    $iCont++; 
        
  }
  
}

function impEstrutura($oPdf, $sEstrutura, $sd02_i_codigo, $sIntervalo) {
  
  global $iCont;
  global $sGrupo;
  global $sSubGrupo;
  global $sFormaOrg;
  
  if ($sGrupo != '') {

    if ($iCont >= 36) {
      
      novaPagina($oPdf);
      impCabecalho($oPdf);
      
    }
    $iTotalGrupo = getTotalEstrutura ($sd02_i_codigo, substr($sEstrutura, 0, 2), $sIntervalo); 
    $oPdf->cell(50, 4, "Grupo ", 1, 0, "L", 0);
    $oPdf->cell(170, 4, $sGrupo, 1, 0, "L", 0);
    $oPdf->cell(60, 4, $iTotalGrupo, 1, 1, "L", 0);
    $iCont++;
    
  } 
  if ($sSubGrupo != '') {

    if ($iCont >= 36) {
      
      novaPagina($oPdf);
      impCabecalho($oPdf);
      
    }
    $iTotalSubGrupo = getTotalEstrutura ($sd02_i_codigo, substr($sEstrutura, 0, 4), $sIntervalo);
    $oPdf->cell(50, 4, "Sub-Grupo ", 1, 0, "L", 0);
    $oPdf->cell(170, 4, $sSubGrupo, 1, 0, "L", 0);
    $oPdf->cell(60, 4, $iTotalSubGrupo, 1, 1, "L", 0);
    $iCont++;
    
  } 
  if ($sFormaOrg != '') {
    
    if ($iCont >= 36) {
      
      novaPagina($oPdf);
      impCabecalho($oPdf);
      
    }
    $iTotalFormaOrg = getTotalEstrutura ($sd02_i_codigo, $sEstrutura, $sIntervalo);
    $oPdf->cell(50, 4, "Forma de Organização ", 1, 0, "L", 0);
    $oPdf->cell(170, 4, $sFormaOrg, 1, 0, "L", 0);
    $oPdf->cell(60, 4, $iTotalFormaOrg, 1, 1, "L", 0);
    $iCont++;
       
  }
  
}

function impRodape($oPdf, $iGeral) {
  
  global $iCont;
  global $iProducao;
  global $sProcedimentos;
  global $todosprocedimentos;
  global $agrupar; 
  if ($iCont + 2 >= 36) {
    novaPagina($oPdf);
  }
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(220, 4, "Total da Produção:", 1, 0, "L", 1); 
  $oPdf->cell(60, 4, "$iProducao", 1, 1, "L", 1);   
  $oPdf->cell(220, 4, "Total da Produção Geral:", 1, 0, "L", 1); 
  $oPdf->cell(60, 4, "$iGeral", 1, 1, "L", 1);  
  $oPdf->setfillcolor(255);
  $iCont += 2;

}

function novaPagina($oPdf) {
  
  global $iCont;
  $iCont = 0;
  $oPdf->ln(5);
  $oPdf->addpage('L');

}

$iCont          = 0;
$dData1         = substr($dataini,6,4)."-".substr($dataini,3,2)."-".substr($dataini,0,2);
$dData2         = substr($datafim,6,4)."-".substr($datafim,3,2)."-".substr($datafim,0,2);
$sIntervalo     = "  sd29_d_data between '".$dData1."'::date and '".$dData2."'::date ";

$sWhere         = $sIntervalo;

$iProducao      = 0;
$sGrupo         = '';
$sSubGrupo      = '';
$sFormaOrg      = '';
$aProcedimentos = array();

if ($lBuscarProcedimentos) {
  $sProcedimentos = $_SESSION['procedimentos'];
  unset ($_SESSION['procedimentos']);
} else {
  $sProcedimentos = '';
}

/* NÃO SÃO TODAS AS  UNIDADES BUSCA PELAS RECEBIDAS EM STRING POR GET*/
if (!isset($todos) && $unidades != '') {
  $sWhere .= " and sd02_i_codigo in (".$unidades.") ";
}

/* AGRUPADO POR UPS */
if ($agrupar == 1) {

  $sCampos        = " sd02_i_codigo, descrdepto, count(sd02_i_codigo) as total_procedimentos ";
  $sGroup         = " group by sd02_i_codigo, descrdepto ";
  $sOrdem         = " sd02_i_codigo ";
  $sSql           = $oDaoProntProced->sql_query_producao("", $sCampos, $sOrdem, $sWhere.$sGroup);
  $rsProcedimento = $oDaoProntProced->sql_record($sSql);

} else { /* BUSCA PROCEDIMENTOS APENAS */
  
  $aProcedimentos = getTotalPorProcedimento($sProcedimentos, $sIntervalo, '');
  
}

/* SE EXISTIR FORMA DE ORGANIZAÇÃO BUSCA AS INFORMAÇÕES */
if (isset($fo)) {

  $sCampos               = " sd62_c_formaorganizacao||' - '||sd62_c_nome as formaorganizacao ";
  $sWhere                = " sd62_i_codigo = $fo ";
  $oDaoFormaOrganizacao  = db_utils::getdao('sau_formaorganizacao');
  $sSql                  = $oDaoFormaOrganizacao->sql_query_file("", $sCampos, "", $sWhere);
  $rsFormaOrganizacao    = $oDaoFormaOrganizacao->sql_record($sSql);
  $oDataFormaOrganizacao = db_utils::fieldsmemory($rsFormaOrganizacao, 0);
  $sFormaOrg             = $oDataFormaOrganizacao->formaorganizacao;
  
  
}
/* SE EXISTIR SUBGRUPO BUSCA AS INFORMAÇÕES */
if (isset($sg)) {
  
  $sCampos       = " sd61_c_subgrupo||' - '||sd61_c_nome as subgrupo ";
  $sWhere        = " sd61_i_codigo = $sg ";
  $oDaoSubGrupo  = db_utils::getdao('sau_subgrupo');
  $sSql          = $oDaoSubGrupo->sql_query_file("", $sCampos, "", $sWhere);
  $rsSubGrupo    = $oDaoSubGrupo->sql_record($sSql);
  $oDataSubGrupo = db_utils::fieldsmemory($rsSubGrupo, 0);
  $sSubGrupo     = $oDataSubGrupo->subgrupo;
  
}
/* SE EXISTIR GRUPO BUSCA AS INFORMAÇÕES */
if (isset($gp)) {
  
  $sCampos    = " sd60_c_grupo||' - '||sd60_c_nome as grupo ";
  $sWhere     = " sd60_i_codigo = $gp ";
  $oDaoGrupo  = db_utils::getdao('sau_grupo');
  $sSql       = $oDaoGrupo->sql_query_file("", $sCampos, "", $sWhere);
  $rsGrupo    = $oDaoGrupo->sql_record($sSql);
  $oDataGrupo = db_utils::fieldsmemory($rsGrupo, 0);
  $sGrupo     = $oDataGrupo->grupo;
  
}

if ($oDaoProntProced->numrows == 0) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado<br>
          <input type='button' value='Fechar' onclick='window.close()'><die($sSql);/b>
        </font>
      </td>
    </tr>
  </table>
  <?
  exit;
  
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "Relatório de Produção Ambulatorial";
$head2 = "Período: ".$dataini." a ".$datafim;

if (isset($todos)) {
  $head3 = "UPS: TODAS";
} else {
  $head3 = "UPS: ".$unidades;
}

novaPagina($oPdf);

if ($agrupar == 1) { /* LISTAR AGRUPADO POR UPS */

  $iTam = $oDaoProntProced->numrows;
  for ($iI = 0; $iI < $iTam; $iI++) {

    $oDataProced = db_utils::fieldsmemory($rsProcedimento, $iI);
    impTopo($oPdf, $oDataProced->sd02_i_codigo, $oDataProced->descrdepto);
    if (isset($gp)|| $sProcedimentos != '') {
      impCabecalho($oPdf); 
    }
    impEstrutura($oPdf, $estrutura, $oDataProced->sd02_i_codigo, $sIntervalo);
    $aProcedimentos = getTotalPorProcedimento($sProcedimentos, $sIntervalo, $oDataProced->sd02_i_codigo);
    if ($aProcedimentos != null) {
      impProcedimentos($oPdf, $aProcedimentos);
    }
    
    impRodape($oPdf, $oDataProced->total_procedimentos, $agrupar);
    $iProducao = 0;
    
  }
  
} else { /* LISTAR PROCEDIMENTOS */
  

  impCabecalho($oPdf); 
  impEstrutura($oPdf, $estrutura, '', $sIntervalo);
  impProcedimentos($oPdf, $aProcedimentos);
  impRodape($oPdf, $iProducao);
  
}

$oPdf->Output();
?>