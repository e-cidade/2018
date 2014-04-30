<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_rhteutri_classe.php");
include("classes/db_rhtipovale_classe.php");
include("dbforms/db_funcoes.php");

$clrhteutri   = new cl_rhteutri;
$clrhtipovale = new cl_rhtipovale;
$clrotulo = new rotulocampo;
$clrotulo->label('rh67_codigo');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$res_tipovale = $clrhtipovale->sql_record($clrhtipovale->sql_query($rh67_rhtipovale,"*"));
db_fieldsmemory($res_tipovale,0);
$head3 = "RELATÓRIO DO CADASTRO VALE TRANSPORTE INTEGRADO";
$head5 = "TIPO : ".$rh68_sequencial." - ".$rh68_descr;

if($ordem == 'a'){
  $xordem = 'z01_nome';
}else{
  $xordem = 'rh67_regist';
}

$where = " rh67_rhtipovale = $rh67_rhtipovale ";

if($tipo == 'a'){
  $where .= " and rh67_ativo = 't'";
}elseif($tipo == 'i'){
  $where .= " and rh67_ativo = 'f'";
}

$xgrupo = '';
if(trim($grupo) != ''){
  $where .= " and rh67_grupo = $grupo ";
}

$result = $clrhteutri->sql_record($clrhteutri->sql_query(null,"*",$xordem,$where));
//echo $clrhteutri->sql_query(null,"*",$xordem,$where);exit;
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona("db_erros.php?fechar=true&db_erro=Não existem Vales Cadastrados para o tipo ".$rh68_sequencial." - ".$rh68_descr.". Verifique!");

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'GRUPO',1,0,"C",1);
      $pdf->cell(30,$alt,'CARTÃO',1,0,"C",1);
      $pdf->cell(12,$alt,'DIAS',1,0,"C",1);
      $pdf->cell(20,$alt,'ATI/INA',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   if($rh67_ativo == 't'){
     $xativo = 'ATIVO';
   }else{
     $xativo = 'INATIVO';
   }
   $pdf->cell(15,$alt,$rh67_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,$rh67_grupo,0,0,"L",$pre);
   $pdf->cell(30,$alt,$rh67_cartao,0,0,"L",$pre);
   $pdf->cell(12,$alt,$rh67_dias,0,0,"C",$pre);
   $pdf->cell(20,$alt,$xativo,0,1,"C",$pre);
   $total += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(157,$alt,'TOTAL :  '.$total.' FUNCIONÁRIOS',"T",0,"C",0);

$pdf->Output();
   
?>