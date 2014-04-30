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
include("classes/db_marca_classe.php");
include("classes/db_cancmarca_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmarca = new cl_marca;
$clcancmarca = new cl_cancmarca;
$clmarca->rotulo->label();
$clcancmarca->rotulo->label();
$prop = str_replace("X",",",$lista);
$where = "ma01_c_ativo = 'N' AND ma01_i_cgm in ($prop)";
$campos = "marca.*,localmarca.*,cgm.z01_nome";
$sql = $clmarca->sql_query("",$campos,"z01_nome",$where);
$result = $clmarca->sql_record($sql);
//db_criatabela($result);
//exit;
if($clmarca->numrows == 0){?>
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
$head1 = "RELATÓRIO DE MARCAS CANCELADAS";
$pdf->addpage('P');
$pdf->ln(5);
for($x=0;$x<$clmarca->numrows;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfont('arial','b',7);
 $pdf->cell(20,4,"Código Marca",1,0,"C",1);
 $pdf->cell(22,4,"CGM",1,0,"C",1);
 $pdf->cell(50,4,"Nome/Razão Social",1,0,"C",1);
 $pdf->cell(25,4,"Localidade",1,0,"C",1);
 $pdf->cell(25,4,"Subdistrito",1,0,"C",1);
 $pdf->cell(20,4,"Data Cadastro",1,0,"C",1);
 $pdf->cell(20,4,"Livro",1,0,"C",1);
 $pdf->cell(10,4,"Folha",1,1,"C",1);
 $pdf->setfont('arial','',6);
 $pdf->cell(20,4,$ma01_i_codigo,"L",0,"C",0);
 $pdf->cell(22,4,$ma01_i_cgm,0,0,"C",0);
 $pdf->cell(50,4,$z01_nome,0,0,"L",0);
 $pdf->cell(25,4,$ma04_c_descr,0,0,"C",0);
 $pdf->cell(25,4,$ma04_c_subdistrito,0,0,"C",0);
 $pdf->cell(20,4,db_formatar($ma01_d_data,'d'),0,0,"C",0);
 $pdf->cell(20,4,$ma01_i_livro,0,0,"C",0);
 $pdf->cell(10,4,$ma01_i_folha,"R",1,"C",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(5,4,"","L",0,"C",0);
 $pdf->cell(182,4,"Cancelamentos:","B",0,"L",0);
 $pdf->cell(5,4,"","R",1,"C",0);
 $campos = "cancmarca.*,cgm.z01_nome,cgm.z01_numcgm";
 $sql1 = $clcancmarca->sql_query("",$campos,"ma03_d_data","ma03_i_marca = $ma01_i_codigo");
 $result1 = $clcancmarca->sql_record($sql1);
 $pdf->setfont('arial','b',7);
 $pdf->cell(5,4,"","L",0,"C",0);
 $pdf->cell(25,4,"Data Cancelamento","B",0,"C",0);
 $pdf->cell(15,4,"CGM","B",0,"C",0);
 $pdf->cell(50,4,"Requerente","B",0,"L",0);
 $pdf->cell(20,4,"Processo","B",0,"C",0);
 $pdf->cell(20,4,"Tipo","B",0,"C",0);
 $pdf->cell(52,4,"Observações","B",0,"L",0);
 $pdf->cell(5,4,"","R",1,"C",0);
 $cor1 = 0;
 $cor2 = 1;
 $cor = $cor1;
 for($z=0;$z<$clcancmarca->numrows;$z++){
  if($cor==$cor1){
   $cor=$cor2;
  }else{
   $cor=$cor1;
  }
  db_fieldsmemory($result1,$z);
  $pdf->setfont('arial','',6);
  $pdf->cell(5,4,"","L",0,"C",0);
  $pdf->cell(25,4,db_formatar($ma03_d_data,'d'),0,0,"C",0);
  $pdf->cell(15,4,$z01_numcgm,0,0,"C",0);
  $pdf->cell(50,4,$z01_nome,0,0,"L",0);
  $pdf->cell(20,4,$ma03_i_codproc,0,0,"C",0);
  $pdf->cell(20,4,$ma03_c_tipo=="C"?"Cancelamento":"Reativação",0,0,"C",0);
  $pdf->cell(52,4,$ma03_t_obs,0,0,"L",0);
  $pdf->cell(5,4,"","R",1,"C",0);
 }
 $pdf->cell(192,4,"","LRB",1,"C",0);
}
$pdf->Output();
?>