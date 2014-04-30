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
include("classes/db_transfmarca_classe.php");
$cltransfmarca = new cl_transfmarca;
if($escolha=="Todas"){
 $where = "";
 $opcao = "TODAS";
}elseif($escolha=="Por Marca"){
 $where = " ma02_i_marca = $marca and ";
 $opcao = "MARCA N°: $marca";
}else{
 $where = " (ma02_i_propant = $cgm OR ma02_i_propnovo = $cgm) and ";
 $opcao = "CGM N° $cgm";
}
$where .= " ma02_d_data between '$data_ini' and '$data_fim' ";
$periodo = "PERÍODO: ".db_formatar($data_ini,'d')." até ".db_formatar($data_fim,'d');
$campos = "cgm1.z01_nome as ant,
           cgm4.z01_nome as novo,
           cgm3.z01_nome as atual,
           cgm3.z01_numcgm as cgmatual,
           protprocesso.p58_requer as req,
           protprocesso.p58_numcgm as cgmreq,
           protprocesso.p58_dtproc as dataproc,
           transfmarca.*";
$sql = $cltransfmarca->sql_query("",$campos,"ma02_d_data,ma02_i_marca",$where);
$result = $cltransfmarca->sql_record($sql);
//db_criatabela($result);
//exit;
if($cltransfmarca->numrows == 0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum Registro para o Relatório<br>
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
$pdf->setfillcolor(243);
$head1 = "RELATÓRIO DE TRANSFERÊNCIAS";
$head2 = $periodo;
$head3 = $opcao;
$pdf->addpage('P');
$pdf->ln(5);
for($x=0;$x<$cltransfmarca->numrows;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfont('arial','b',7);
 $pdf->cell(20,4,"Cód","LTB",0,"C",1);
 $pdf->cell(30,4,"Data Transf.","TB",0,"C",1);
 $pdf->cell(30,4,"Código Marca","TB",0,"C",1);
 $pdf->cell(110,4,"Proprietário Atual","TBR",1,"L",1);
 $pdf->setfont('arial','',6);
 $pdf->cell(20,4,$ma02_i_codigo,"LB",0,"C",0);
 $pdf->cell(30,4,db_formatar($ma02_d_data,'d'),"B",0,"C",0);
 $pdf->cell(30,4,$ma02_i_marca,"B",0,"C",0);
 $pdf->cell(110,4,$cgmatual."  ".$atual,"RB",1,"L",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(25,4,"Requerente:","L",0,"L",0);
 $pdf->setfont('arial','',6);
 $pdf->cell(90,4,$cgmreq."  ".$req,0,0,"L",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(20,4,"Processo N°:",0,0,"L",0);
 $pdf->setfont('arial','',6);
 $pdf->cell(15,4,$ma02_i_codproc,0,0,"L",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(25,4,"Data Processo:",0,0,"R",0);
 $pdf->setfont('arial','',6);
 $pdf->cell(15,4,db_formatar($dataproc,'d'),"R",1,"L",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(25,4,"Prop. Anterior:","L",0,"L",0);
 if($escolha=="Por CGM" && $ma02_i_propant==$cgm){
  $pdf->setfont('arial','b',7);
  $pdf->cell(165,4,$ma02_i_propant."  ".$ant,"R",1,"L",0);
 }else{
  $pdf->setfont('arial','',6);
  $pdf->cell(165,4,$ma02_i_propant."  ".$ant,"R",1,"L",0);
 }
 $pdf->setfont('arial','b',7);
 $pdf->cell(25,4,"Prop. Novo:","L",0,"L",0);
 if($escolha=="Por CGM" && $ma02_i_propnovo==$cgm){
  $pdf->setfont('arial','b',7);
  $pdf->cell(165,4,$ma02_i_propnovo."  ".$novo,"R",1,"L",0);
 }else{
  $pdf->setfont('arial','',6);
  $pdf->cell(165,4,$ma02_i_propnovo."  ".$novo,"R",1,"L",0);
 }
 $pdf->setfont('arial','b',7);
 $pdf->cell(25,4,"Obs.:","LB",0,"L",0);
 $pdf->setfont('arial','',6);
 $pdf->cell(165,4,$ma02_t_obs,"RB",1,"L",0);
}
$pdf->Output();
?>