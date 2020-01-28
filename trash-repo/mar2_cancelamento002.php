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
include("classes/db_cancmarca_classe.php");
$clcancmarca = new cl_cancmarca;
if($escolha=="Cancelamentos"){
 $where = "ma03_c_tipo = 'C' and ";
}elseif($escolha=="Reativações"){
 $where = "ma03_c_tipo = 'R' and ";
}else{
 $where = "";
}
$where .= "ma03_d_data between '$data_ini' and '$data_fim'";
$titulo = "PERÍODO: ".db_formatar($data_ini,'d')." até ".db_formatar($data_fim,'d');
$campos = "a.z01_nome as atual,
           a.z01_numcgm as cgmatual,
           protprocesso.p58_requer as req,
           protprocesso.p58_numcgm as cgmreq,
           protprocesso.p58_dtproc as dataproc,
           cancmarca.*";
$sql = $clcancmarca->sql_query("",$campos,"ma03_d_data,ma03_i_marca",$where);
$result = $clcancmarca->sql_record($sql);
//db_criatabela($result);
//exit;
if($clcancmarca->numrows == 0){?>
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
$head1 = "RELATÓRIO DE CANCELAMENTOS";
$head2 = $titulo;
$head3 = "TIPO: $escolha";
$pdf->addpage('P');
$pdf->ln(5);
$pdf->setfont('arial','b',7);
$pdf->cell(10,4,"Cód",1,0,"C",1);
$pdf->cell(20,4,"Data Canc",1,0,"C",1);
$pdf->cell(20,4,"Código Marca",1,0,"C",1);
$pdf->cell(55,4,"Proprietário Atual",1,0,"C",1);
$pdf->cell(55,4,"Requerente",1,0,"C",1);
$pdf->cell(15,4,"Processo",1,0,"C",1);
$pdf->cell(15,4,"Tipo",1,1,"C",1);
for($x=0;$x<$clcancmarca->numrows;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfont('arial','',6);
 $pdf->cell(10,4,$ma03_i_codigo,"L",0,"C",0);
 $pdf->cell(20,4,db_formatar($ma03_d_data,'d'),0,0,"C",0);
 $pdf->cell(20,4,$ma03_i_marca,0,0,"C",0);
 $pdf->cell(55,4,$cgmatual."  ".$atual,0,0,"L",0);
 $pdf->cell(55,4,$cgmreq."  ".$req,0,0,"L",0);
 $pdf->cell(15,4,$ma03_i_codproc,0,0,"C",0);
 $pdf->cell(15,4,$ma03_c_tipo=='C'?'Cancelamento':'Reativação',"R",1,"C",0);
 $pdf->cell(10,3,"","LB",0,"L",0);
 $pdf->cell(170,3,"Obs.:  ".$ma03_t_obs,"B",0,"L",0);
 $pdf->cell(10,3,"","BR",1,"L",0);
}
$pdf->Output();
?>