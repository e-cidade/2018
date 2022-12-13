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

require_once('fpdf151/pdf.php');
require_once('libs/db_utils.php');

$oDaoLabRequiItem    = new cl_lab_requiitem();
$sWhere              = '';
$lInformouRequisicao = false;
$sPeriodo            = "PERÍODO: ";

/**
 * Quando requisição estiver informada, não devemos olhar os outros filtros
 */
if (isset($iRequisicao) && $iRequisicao != "") {

  $lInformouRequisicao = true;
  $sWhere              = " la22_i_codigo = $iRequisicao";
  $dDataini            = "";
  $dDatafim            = "";
}

if( !$lInformouRequisicao ) {

  if (isset($dDataini) && $dDatafim != "") {

    @$dData1  = substr(@$dDataini,6,4)."-".substr(@$dDataini,3,2)."-".substr(@$dDataini,0,2);
    @$dData2  = substr(@$dDatafim,6,4)."-".substr(@$dDatafim,3,2)."-".substr(@$dDatafim,0,2);
    $sWhere   = " la22_d_data between '$dData1' and '$dData2'";
    $sPeriodo = "PERÍODO: ".$dDataini." até ".$dDatafim;
  }

  if (isset($iLaboratorio) && $iLaboratorio != "") {
    $sWhere .= " and la02_i_codigo = $iLaboratorio";
  }

  if (isset($iLabsetor) && $iLabsetor != "") {
    $sWhere .= " and la24_i_codigo = $iLabsetor";
  }

  if (isset($iExame) && $iExame != "") {
    $sWhere .= " and la08_i_codigo = $iExame";
  }

  $sSep = " and ( ";
  if (isset($lAutorizado) && $lAutorizado == 1) {

    $sWhere .= " $sSep la21_c_situacao='8 - Autorizado'";
    $sSep    = " or ";
  }

  if (isset($lColetado) && $lColetado == 1) {

    $sWhere .= " $sSep la21_c_situacao = '6 - Coletado' ";
    $sSep    = " or ";
  }

  if (isset($lConfirmado) && $lConfirmado == 1) {

    $sWhere .= " $sSep la21_c_situacao = '7 - Conferido' ";
    $sSep    = " or ";
  }

  if (isset($lEntregue) && $lEntregue == 1) {

    $sWhere .= " $sSep la21_c_situacao='3 - Entregue' ";
    $sSep    =" or ";
  }

  if ($sSep == " or ") {
    $sWhere .= ")";
  }
}

$sCampos  = " distinct on (la22_i_codigo) la22_i_codigo,la02_c_descr, la23_c_descr, la21_d_data, ";
$sCampos .= " z01_v_nome, la08_c_descr, la15_c_descr, la32_d_data, la52_d_data, la31_d_data ";
$sOrder   = " la22_i_codigo ";
$sSql     = $oDaoLabRequiItem->sql_query_nova("", $sCampos, $sOrder, $sWhere);
$rsResult = $oDaoLabRequiItem->sql_record($sSql);
if ($oDaoLabRequiItem->numrows == 0) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado<br>
          <input type='button' value='Fechar' onclick='window.close()'></b>
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
$oPdf->SetFillColor("230");
$head1 = "RELATÓRIO DE EXAMES";
if ($oDaoLabRequiItem->numrows > 0) {
  
  $oDataRequiItem = db_utils::fieldsmemory($rsResult, 0);
  if (isset($iLaboratorio) && $iLaboratorio != "") {
    $head2 = "LABORATÓRIO: $oDataRequiItem->la02_c_descr";
  } else {
    $head2 = "LABORATÓRIO: ";  
  }
  if (isset($iLabsetor) && $iLabsetor != "") {
    $head3 = "SETOR: $oDataRequiItem->la23_c_descr";
  } else {
    $head3 = "SETOR: ";
  }
  if (isset($iExame) && $iExame != "") {
    $head4 = "EXAME: $oDataRequiItem->la08_c_descr";
  } else {
    $head4 = "EXAME: ";
  }
   
} else {
  
  $head2 = "LABORATÓRIO: ";
  $head3 = "SETOR: ";
  $head4 = "EXAME: ";

}
$head5 = $sPeriodo;
$iCont = 0;
$oPdf->ln(5);
$oPdf->addpage('L');
$oPdf->setfont('arial', 'b', 9);
$oPdf->Cell(24, 4, "Requisição ", 1, 0, "C", 1);
$oPdf->cell(20, 4, "Data ",       1, 0, "C", 1);
$oPdf->cell(80, 4, "Paciente ",   1, 0, "C", 1);
$oPdf->cell(50, 4, "Exame ",      1, 0, "C", 1);
$oPdf->cell(45, 4, "Material ",   1, 0, "C", 1);
$oPdf->cell(20, 4, "Coleta ",     1, 0, "C", 1);
$oPdf->cell(20, 4, "Resultado ",  1, 0, "C", 1);
$oPdf->cell(20, 4, "Entrega ",    1, 1, "C", 1);

for ($iI = 0; $iI < $oDaoLabRequiItem->numrows; $iI++) {

  $oDataRequiItem = db_utils::fieldsmemory($rsResult, $iI);
  if ($iCont == 35) {

    $iCont = 0;
    $oPdf->ln(5);
    $oPdf->addpage('L');
    $oPdf->setfont('arial', 'b', 9);
    $oPdf->Cell(24, 4, "Requisição ", 1, 0, "C", 1);
    $oPdf->cell(20, 4, "Data ",       1, 0, "C", 1);
    $oPdf->cell(80, 4, "Paciente ",   1, 0, "C", 1);
    $oPdf->cell(50, 4, "Exame ",      1, 0, "C", 1);
    $oPdf->cell(45, 4, "Material ",   1, 0, "C", 1);
    $oPdf->cell(20, 4, "Coleta ",     1, 0, "C", 1);
    $oPdf->cell(20, 4, "Resultado ",  1, 0, "C", 1);
    $oPdf->cell(20, 4, "Entrega ",    1, 1, "C", 1);
  
  } 
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(24, 4, $oDataRequiItem->la22_i_codigo, 1, 0, "R", 0);
  $oPdf->cell(20, 4, db_formatar($oDataRequiItem->la21_d_data, 'd'), 1, 0, "R", 0);
  $oPdf->cell(80, 4, substr($oDataRequiItem->z01_v_nome, 0, 80), 1, 0, "L", 0);
  $oPdf->cell(50, 4, substr($oDataRequiItem->la08_c_descr, 0, 23), 1, 0, "L", 0);
  $oPdf->cell(45, 4, substr($oDataRequiItem->la15_c_descr, 0, 23), 1, 0, "L", 0);
  $oPdf->cell(20, 4, db_formatar($oDataRequiItem->la32_d_data, 'd'), 1, 0, "R", 0);
  $oPdf->cell(20, 4, db_formatar($oDataRequiItem->la52_d_data, 'd'), 1, 0, "R", 0);
  $oPdf->cell(20, 4, db_formatar($oDataRequiItem->la31_d_data, 'd'), 1, 1, "R", 0); 
  $iCont++;
 
} 
$oPdf->setfont('arial', 'b', 10);
$oPdf->cell(175, 10, "TOTAL DE EXAMES DO PRESTADOR: ".$oDaoLabRequiItem->numrows, 0, 1, "R", 0); 
$oPdf->Output();
?>