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

include("fpdf151/pdf.php");
include("classes/db_lab_requiitem_classe.php");
require_once("libs/db_utils.php");
$oDaoVacAplicalote = db_utils::getdao('vac_aplicalote');
$dIni              = substr($dDataini,6,4)."-".substr($dDataini,3,2)."-".substr($dDataini,0,2);
$dFim              = substr($dDatafim,6,4)."-".substr($dDatafim,3,2)."-".substr($dDatafim,0,2);
$sWhere            = " vc16_d_data between '$dIni' and '$dFim' ";
if ($iBairro != "0") {
  $sWhere .= " and z01_v_bairro = '$iBairro' ";
}
if ($iVacina != "0") {
  $sWhere .= " and vc06_i_codigo = $iVacina ";
}
$sCampos  = " z01_v_bairro,";
$sCampos .= " vc06_c_descr, ";
$sCampos .= " vc06_i_codigo, ";
$sCampos .= " z01_i_cgsund, ";
$sCampos .= " z01_v_nome, ";
$sCampos .= " vc07_c_nome ";
$sSql     = $oDaoVacAplicalote->sql_query2(null,$sCampos,"z01_v_bairro,vc07_c_nome",$sWhere);
$rsDados  = $oDaoVacAplicalote->sql_record($sSql);
if ($oDaoVacAplicalote->numrows == 0) {?>
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
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1        = "Aplicações de Vacinas por Bairro";
$head2        = "Período: $dDataini A $dDatafim ";
$iTotalVacina = 0;
$iTotalBairro = 0;
$sBairro      = "";
$iVacina      = 0;
$lFirst       = true;
$pdf->setfillcolor(200);
for ($iX = 0; $iX < $oDaoVacAplicalote->numrows; $iX++) {
  $oDados = db_utils::fieldsmemory($rsDados,$iX);
  if ($pdf->GetY() > $pdf->h -25 || $oDados->z01_v_bairro != $sBairro || $oDados->vc06_i_codigo != $iVacina) {

    if ($pdf->GetY() > $pdf->h -25 || $lFirst == true) {

      $pdf->ln(5);
      $pdf->addpage('P');
      $pdf->setfont('arial','b',10);
      $lFirst = false;

    }
    if ($oDados->vc06_i_codigo != $iVacina) {

      $iVacina = $oDados->vc06_i_codigo;
      if ($iTotalVacina > 0) {
        $pdf->cell(190,4,"Total Vacina:$iTotalVacina",1,1,"L",0);
        $iTotalVacina = 0;
      }

    }
    if ($oDados->z01_v_bairro != $sBairro) {

      $sBairro = $oDados->z01_v_bairro;
      if ($iTotalVacina > 0) {

        $pdf->cell(190,4,"Total Vacina:$iTotalVacina",1,1,"L",0);
        $iTotalVacina = 0;

      }
      if ($iTotalBairro > 0) {

        $pdf->cell(190,4,"Total Bairro:$iTotalBairro",1,1,"L",0);
        $iTotalBairro = 0;

      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(190,4,"Bairro: ".$oDados->z01_v_bairro,1,1,"L",1);  
    }
    $pdf->setfont('arial','b',8);    
    $pdf->cell(190,4,"Vacina: ".$oDados->vc06_c_descr,1,1,"L",1);
    $pdf->cell(20,4,"CGS",1,0,"L",0);
    $pdf->cell(110,4,"Nome",1,0,"L",0);
    $pdf->cell(60,4,"Dose",1,1,"L",0);

  }
  $pdf->setfont('arial','',8);
  $pdf->cell(20,4,$oDados->z01_i_cgsund,1,0,"L",0);
  $pdf->cell(110,4,$oDados->z01_v_nome,1,0,"L",0);
  $pdf->cell(60,4,$oDados->vc07_c_nome,1,1,"L",0); 
  $iTotalVacina++;
  $iTotalBairro++;
}
$pdf->cell(190,4,"Total Vacina:$iTotalVacina",1,1,"L",0);
$pdf->cell(190,4,"Total Bairro:$iTotalBairro",1,1,"L",0);
$pdf->Output();
?>