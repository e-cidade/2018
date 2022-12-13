<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllab_requiitem = new cl_lab_requiitem;

$sAdd    = "";
$sCampos = "";
if ($iTipo == 2) {
	
  $sAdd = "||'__'||(coalesce(sd63_f_sa,0)+coalesce(sd63_f_sp,0))";
  
  $sCampos .= " la21_d_data,";
  $sCampos .= " z01_i_cgsund,";
  $sCampos .= " z01_v_nome,";
  $sCampos .= " la08_c_sigla,";
  
}
$sCampos .= " la02_i_codigo, ";
$sCampos .= " la02_c_descr, ";
$sCampos .= " (select sd63_c_nome||'__'||sd63_c_procedimento$sAdd from lab_conferencia
               inner join sau_procedimento on sd63_i_codigo = la47_i_procedimento
               inner join db_usuarios on id_usuario = la47_i_login
               where la47_i_requiitem=la21_i_codigo 
               order by la47_d_data desc,la47_c_hora desc limit 1) as conferencia, ";
$sCampos .= " la08_c_descr";

$sWhere   = "exists (select * from lab_conferencia
                     inner join sau_procedimento on sd63_i_codigo = la47_i_procedimento
                     inner join db_usuarios on id_usuario = la47_i_login
                     where la47_i_requiitem=la21_i_codigo and
                     la47_d_data between '$dInicio' and '$dFim'
                     order by la47_d_data desc,la47_c_hora desc limit 1)";
$sWhere  .= " and la02_i_codigo in ($sLaboratorios) ";
if ($iTipo == 1) {
	
	$sCampos .= " ,count(la21_i_codigo) as total";
  $sWhere .= " group by la02_i_codigo,la02_c_descr,la08_c_descr,conferencia ";
  $order   = " la02_i_codigo ";
  
} else {
  $order   = " la21_d_data asc,z01_v_nome,la02_c_descr ";	
}
$sSql = $cllab_requiitem->sql_query2("",$sCampos,$order,$sWhere);
$result = $cllab_requiitem->sql_record($sSql);

if($cllab_requiitem->numrows==0){?>
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
$lFirst  = true;
$iLab    = 0;
$iCgs    = 0;
$iQuebra = 0;
$iTotal  = 0;
$head1   = "RELATÓRIO DE PRODUÇÃO";
$head2   = "PERIODO: $dInicio até $dFim ";
$head3   = "TOTAL DE EXAMES: $cllab_requiitem->numrows ";
$head4   = "TOTAL DE COLETA: $cllab_requiitem->numrows ";
$iCont   = 0;
if ($iTipo==1) {
  for($iI=0; $iI < $cllab_requiitem->numrows; $iI++){

    db_fieldsmemory($result,$iI);
    if ($iQuebra == 35 || $la02_i_codigo != $iLab || $lFirst==true) {
  
	    if($lFirst == false){
	  	
        $pdf->cell(190,4,"Total: $iCont",1,1,"R",0);     
        $head5   = "LABORATÓRIO: $la02_c_descr ";
        $iCont = 0;

	    }
	    $head5   = "LABORATÓRIO: $la02_c_descr ";
      $pdf->ln(5);
      $pdf->addpage('P');
	    $pdf->setfont('arial','b',10);
      $pdf->cell(190,4,"Laboratorio: $la02_c_descr",1,1,"C",0);
      $pdf->cell(30,4,"Procedimento ",0,0,"C",0);
      $pdf->cell(100,4,"Descrição ",0,0,"C",0);
      $pdf->cell(50,4,"Exame ",0,0,"C",0);
      $pdf->cell(10,4,"Total ",0,1,"C",0);
      $lFirst  = false;
      $iLab  = $la02_i_codigo;
      $iQuebra = 0;
    
	  } 
	  $pdf->setfont('arial','',8);
    $aProc = explode("__",$conferencia);
    $pdf->cell(30,4,$aProc[1],0,0,"L",0);
    $pdf->cell(100,4,substr($aProc[0],0,58),0,0,"L",0);
    $pdf->cell(50,4,"$la08_c_descr",0,0,"C",0);
    $pdf->cell(10,4,"$total",0,1,"R",0);
    $iQuebra++;
    $iCont += $total;
  }
  $pdf->setfont('arial','b',10);
  $pdf->cell(190,4,"Total: ".$iCont,1,1,"R",0);
} else {
  for($iI=0; $iI < $cllab_requiitem->numrows; $iI++){

    db_fieldsmemory($result,$iI);
    if ($iQuebra == 35 || $la02_i_codigo != $iLab || $z01_i_cgsund != $iCgs || $lFirst==true) {

      $pdf->setfont('arial','b',8);

      if($lFirst == false) {
        
        $pdf->cell(20,4,'',0,0,"L",0);
        $pdf->cell(30,4,'',0,0,"L",0);
        $pdf->cell(70,4,'',0,0,"L",0);
        $pdf->cell(25,4,'',0,0,"C",0);
        $pdf->cell(45,4,"R$ ".number_format($iTotal, 2,',',''),"T",1,"R",0);
        $iTotal = 0;
        
      }
      
      if ($iQuebra == 35 || $lFirst == true) {
      	
        $head5   = "LABORATÓRIO: $la02_c_descr ";
        $pdf->ln(5);
        $pdf->addpage('P');
        $iQuebra = 0;
        
      }
      if ($iCgs != $z01_i_cgsund) {
      	 
        $aData = explode("-",$la21_d_data);
      	$pdf->cell(20,4,$aData[2]."/".$aData[1]."/".$aData[0],"T",0,"L",0);
        $pdf->cell(30,4,$z01_i_cgsund,"T",0,"L",0);
        $pdf->cell(70,4,$z01_v_nome,"T",0,"L",0);
        $pdf->cell(25,4,'Código',"T",0,"C",0);
        $pdf->cell(45,4,'Valor Procedimento',"T",1,"R",0);
        
      }
      
      if ($iLab != $la02_i_codigo || $iCgs != $z01_i_cgsund) {
      	
        $iLab = $la02_i_codigo;
        $iCgs = $z01_i_cgsund;
        $pdf->cell(20,4,'',0,0,"L",0);
        $pdf->cell(30,4,'',0,0,"L",0);
        $pdf->cell(70,4,$la02_c_descr,0,0,"L",0);
        $pdf->cell(25,4,'',0,0,"C",0);
        $pdf->cell(45,4,'',0,1,"R",0);
      
      }
      $lFirst  = false;

    } 
    $pdf->setfont('arial','',8);
    $aProc = explode("__", $conferencia);
    $pdf->cell(20,4,'',0,0,"L",0);
    $pdf->cell(30,4,$la08_c_sigla,0,0,"L",0);
    $pdf->cell(70,4,$la08_c_descr,0,0,"L",0);
    $pdf->cell(25,4,$aProc[1],0,0,"C",0);
    $pdf->cell(45,4,"R$ ".number_format($aProc[2], 2,',',''),0,1,"R",0);
    $iTotal += $aProc[2];
    $iQuebra++;

  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(20,4,'',0,0,"L",0);
  $pdf->cell(30,4,'',0,0,"L",0);
  $pdf->cell(70,4,'',0,0,"L",0);
  $pdf->cell(25,4,'',0,0,"C",0);
  $pdf->cell(45,4,"R$ ".number_format($iTotal, 2,',',''),"T",1,"R",0);	
}


$pdf->Output();
?>