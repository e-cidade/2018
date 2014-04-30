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
include("libs/db_sql.php");
include("classes/db_cadfiscais_classe.php");

$clcadfiscais = new cl_cadfiscais;

$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
$clrotulo->label('nome');
$clrotulo->label('descrdepto');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "nome";
}
else {
$desc_ordem = "Numérica";
$order_by = "cadfiscais.id_usuario";
}
$head3 = "CADASTRO DE FISCAIS ";
$head5 = "ORDEM $desc_ordem";
//die ($clcadfiscais->sql_query_descrdepto("","distinct db_usuarios.id_usuario, db_usuarios.nome, db_depart.descrdepto",$order_by));
$result = $clcadfiscais->sql_record($clcadfiscais->sql_query_descrdepto("","db_usuarios.id_usuario, db_usuarios.nome, db_depart.descrdepto",$order_by," db_depart.instit = ".db_getsession('DB_instit') ));
// echo $clcadfiscais->sql_query("","*",$order_by); exit;
// db_criatabela($result);exit;
if ($clcadfiscais->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem fiscais cadastrados.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$id_usuarioaux = ""; 
for($x = 0; $x < $clcadfiscais->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(40,$alt,$RLid_usuario,1,0,"C",1);
      $pdf->cell(70,$alt,$RLnome,1,0,"C",1); 
      $pdf->cell(70,$alt,$RLdescrdepto,1,1,"C",1); 
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   if($id_usuario!=$id_usuarioaux){
      $pdf->cell(40,$alt,$id_usuario,0,0,"C",0);
      $pdf->cell(70,$alt,$nome,0,0,"L",0);
      $id_usuarioaux = $id_usuario; 
   }else{
      $pdf->cell(40,$alt,"",0,0,"C",0);
      $pdf->cell(70,$alt,"",0,0,"L",0);
   }
   $pdf->cell(70,$alt,$descrdepto,0,1,"L",0);
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(130,$alt,'TOTAL DE FISCAIS  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>