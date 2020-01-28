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
include("libs/db_sql.php");
include("classes/db_clabens_classe.php");

$clclabens = new cl_clabens;

$clrotulo = new rotulocampo;
$clrotulo->label('t64_codcla');
$clrotulo->label('t64_class');
$clrotulo->label('t64_descr');
$clrotulo->label('c60_codcon');
$clrotulo->label('c60_estrut');
$clrotulo->label('c60_descr');
$clrotulo->label('t64_obs');
$clrotulo->label('t64_analitica');
$clrotulo->label('c61_reduz');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a"){
$desc_ordem = "Descrição";
$order_by = "t64_descr";
}else if($ordem == "b"){
$desc_ordem = "Código";
$order_by = "t64_codcla";
}else if($ordem == "c"){
$desc_ordem = "Estrutural";
$order_by = "t64_class";
}
 
$head3 = "CADASTRO DE CLASSIFICAÇÃO DOS BENS";
$head5 = "ORDEM $desc_ordem";

$result = $clclabens->sql_record($clclabens->sql_query(null,"*",$order_by,"t64_instit = ".db_getsession("DB_instit")));
//db_criatabela($result);exit;

if ($clclabens->numrows == 0) {
  
   $sMsg = _M('patrimonial.patrimonio.pat2_clabens002.nao_existem_bens');
   db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);

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

for($x = 0; $x < $clclabens->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLt64_codcla,1,0,"C",1);
      $pdf->cell(15,$alt,"Analitica",1,0,"C",1);
      $pdf->cell(20,$alt,$RLt64_class,1,0,"C",1);
      $pdf->cell(60,$alt,$RLt64_descr,1,0,"C",1);
      $pdf->cell(15,$alt,$RLc60_codcon,1,0,"C",1);
      $pdf->cell(15,$alt,$RLc61_reduz,1,0,"C",1);
      $pdf->cell(25,$alt,$RLc60_estrut,1,0,"C",1);
      $pdf->cell(60,$alt,$RLc60_descr,1,0,"C",1);
      $pdf->cell(0,$alt,$RLt64_obs,1,1,"C",1);
      $troca = 0;
   }
   $p=0;
   if($t64_analitica=='t'){
     $analise = "Sim";
     $p=0;
   }else{
     $analise = "Não";
     $p=1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$t64_codcla,0,0,"C",$p);
   $pdf->cell(15,$alt,$analise,0,0,"C",$p);
   $pdf->cell(20,$alt,$t64_class,0,0,"C",$p);
   $pdf->cell(60,$alt,$t64_descr,0,0,"L",$p);
   $pdf->cell(15,$alt,$c60_codcon,0,0,"C",$p);
   $pdf->cell(15,$alt,$c61_reduz,0,0,"C",$p);
   $pdf->cell(25,$alt,$c60_estrut,0,0,"C",$p);
   $pdf->cell(60,$alt,substr($c60_descr,0,40),0,0,"L",$p);
   $pdf->multicell(0,$alt,$t64_obs,0,"L",$p);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>