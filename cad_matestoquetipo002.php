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
include("classes/db_matestoquetipo_classe.php");
$clrotulo = new rotulocampo;
$clmatunid = new cl_matestoquetipo;
$clrotulo->label('m81_codtipo'); //metodo que pega o label do campo da tabela indicado
$clrotulo->label('m81_descr');   

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$xordem = '';
if($ordem == 'n'){
  if($tipo_ordem == 'a'){
    $xordem = 'm81_codtipo asc ';
    $head5 = "ORDEM : NUMÉRICA - ASCENDENTE";
  }
  else{
    $xordem = 'm81_codtipo desc ';
    $head5 = "ORDEM : NUMÉRICA - DESCENDENTE";
  }
}
elseif($ordem == 'a'){
    if($tipo_ordem == 'a'){
       $xordem = 'm81_descr asc ';
       $head5 = "ORDEM : ALFABÉTICA - ASCENDENTE";
    }
    else{
       $xordem = ' m81_descr desc ';
       $head5 = "ORDEM : ALFABÉTICA - DESCENDENTE";
    }
}
$head3 = "CADASTRO DE MATERIAIS";

$result =  $clmatunid->sql_record($clmatunid->sql_query_file(null,"*",$xordem));
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);

if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem unidades cadastrados.');
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
      $pdf->cell(15,$alt,$RLm81_codtipo,1,0,"C",1);
      $pdf->cell(80,$alt,$RLm81_descr,1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$m81_codtipo,0,0,"C",0);
   $pdf->cell(80,$alt,$m81_descr,0,1,"L",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->Output();
   
?>