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
include("classes/db_marcaloc_classe.php");
include("classes/db_cgm_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmarca = new cl_marca;
$clmarcaloc = new cl_marcaloc;
$clcgm = new cl_cgm;
$clmarca->rotulo->label();
$clcgm->rotulo->label();
//ver sim nao
$prop = str_replace("X",",",$lista);
$where=" ma01_i_cgm in ($prop)";
$campos = "marca.*,cgm.z01_nome";
$sql = $clmarca->sql_query("",$campos,"z01_nome,ma01_i_codigo",$where);
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
$head1 = "Relatório de Marcas por Proprietário";
$head2 = "Tipo: ";
$head2 .= $tipo=="res"?"Resumido":"Completo";
$pdf->addpage('P');
$pdf->ln(5);
if($tipo=="res"){
 $pdf->setfont('arial','b',7);
 $pdf->cell(25,4,"Código da Marca","LTB",0,"C",1);
 $pdf->cell(10,4,"CGM","BT",0,"C",1);
 $pdf->cell(60,4,"Nome/Razão Social","BT",0,"C",1);
 $pdf->cell(25,4,"Data Cadastro","BT",0,"C",1);
 $pdf->cell(25,4,"Livro","BT",0,"C",1);
 $pdf->cell(25,4,"Folha","BT",0,"C",1);
 $pdf->cell(22,4,"Situação","BTR",1,"C",1);
}
$paginacao_com = 8;
$paginacao_res = 38;
$contador = 0;
$img = 51;
for($x=0;$x<$clmarca->numrows;$x++){
 db_fieldsmemory($result,$x);
 if($tipo=="com"){
  if($contador==$paginacao_com){
   $pdf->addpage('P');
   $pdf->ln(5);
   $img = 51;
   $contador = 0;
  }
  $pdf->setfont('arial','b',7);
  $pdf->cell(25,4,"Código da Marca","LTB",0,"C",1);
  $pdf->cell(10,4,"CGM","BT",0,"C",1);
  $pdf->cell(60,4,"Nome/Razão Social","BT",0,"C",1);
  $pdf->cell(25,4,"Data Cadastro","BT",0,"C",1);
  $pdf->cell(25,4,"Livro","BT",0,"C",1);
  $pdf->cell(25,4,"Folha","BT",0,"C",1);
  $pdf->cell(22,4,"Situação","BTR",1,"C",1);
 }elseif($tipo=="res" && $contador==$paginacao_res){
   $pdf->addpage('P');
   $pdf->ln(5);
   $pdf->setfont('arial','b',7);
   $pdf->cell(25,4,"Código da Marca","LTB",0,"C",1);
   $pdf->cell(10,4,"CGM","BT",0,"C",1);
   $pdf->cell(60,4,"Nome/Razão Social","BT",0,"C",1);
   $pdf->cell(25,4,"Data Cadastro","BT",0,"C",1);
   $pdf->cell(25,4,"Livro","BT",0,"C",1);
   $pdf->cell(25,4,"Folha","BT",0,"C",1);
   $pdf->cell(22,4,"Situação","BTR",1,"C",1);
   $contador=0;
 }
 $pdf->setfont('arial','',6);
 $pdf->cell(25,4,$ma01_i_codigo,"L",0,"C",0);
 $pdf->cell(10,4,$ma01_i_cgm,0,0,"C",0);
 $pdf->cell(60,4,$x." ".$z01_nome,0,0,"C",0);
 $pdf->cell(25,4,db_formatar($ma01_d_data,'d'),0,0,"C",0);
 $pdf->cell(25,4,$ma01_i_livro,0,0,"C",0);
 $pdf->cell(25,4,$ma01_i_folha,0,0,"C",0);
 $pdf->cell(22,4,$ma01_c_ativo=="S"?"Ativa":"Cancelada","R",1,"C",0);
 if($tipo=="com"){
  if($ma01_o_imagem){
        $arquivo = "tmp/".$ma01_c_nomeimagem;
   pg_exec("begin");
   pg_loexport($ma01_o_imagem,$arquivo);
   pg_exec("end");
  }else{
   $arquivo = "imagens/semmarca.jpg";
  }
  $pdf->cell(192,1,"","LR",1,"C",0);
  
  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(50,4,"Localidades:","LTB",0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Letra:","LT",0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(35,4,$ma01_c_letra1,"T",0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Figura:","T",0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(30,4,$ma01_c_figura1,"T",0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(16,4,"Objeto:","T",0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(25,4,$ma01_c_objeto1,"TR",0,"L",0);
  $pdf->cell(3,4,"","R",1,"C",0);
  
  $campos = "localmarca.*";
  $sql1 = $clmarcaloc->sql_query("",$campos,"z01_nome"," ma05_i_marca = $ma01_i_codigo");
  $result1 = $clmarcaloc->sql_record($sql1);

  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->setfont('arial','',6);
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,$ma04_c_descr,"L",0,"L",0);
  $pdf->cell(20,4,$ma04_c_subdistrito,"R",0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Letra2:","L",0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(35,4,$ma01_c_letra2,0,0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Figura2:",0,0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(30,4,$ma01_c_figura2,0,0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(16,4,"Objeto2:",0,0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(25,4,$ma01_c_objeto2,"R",0,"L",0);
  $pdf->cell(3,4,"","R",1,"C",0);
  
  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->setfont('arial','',6);
  if($clmarcaloc->numrows>1){
   db_fieldsmemory($result1,1);
   $pdf->cell(30,4,$ma04_c_descr,"L",0,"L",0);
   $pdf->cell(20,4,$ma04_c_subdistrito,"R",0,"L",0);
  }else{
   $pdf->cell(50,4,"","R",0,"L",0);
  }
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Letra3:","L",0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(35,4,$ma01_c_letra3,0,0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Figura3:",0,0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(30,4,$ma01_c_figura3,0,0,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(16,4,"Objeto3:",0,0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(25,4,$ma01_c_objeto3,"R",0,"L",0);
  $pdf->cell(3,4,"","R",1,"C",0);

  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->setfont('arial','',6);
  if($clmarcaloc->numrows>2){
   db_fieldsmemory($result1,2);
   $pdf->cell(30,4,$ma04_c_descr,"L",0,"L",0);
   $pdf->cell(20,4,$ma04_c_subdistrito,"R",0,"L",0);
  }else{
   $pdf->cell(50,4,"","R",0,"L",0);
  }
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,4,"Letra4:","LB",0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(35,4,$ma01_c_letra4,"B",0,"L",0);
  $pdf->cell(45,4,"","B",0,"L",0);
  $pdf->cell(41,4,"","RB",0,"L",0);
  $pdf->cell(3,4,"","R",1,"C",0);
  if($imagem=="c"){
   $pdf->Image($arquivo,182,$img,15);
  }
 }
 $pdf->cell(192,2,"","L",1,"C",0);
 $img += 27;
 $contador++;
}
$pdf->Output();
?>