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
include("classes/db_matmater_classe.php");
$clrotulo = new rotulocampo;
$clmatmater = new cl_matmater;
$clrotulo->label('m60_codmater');
$clrotulo->label('m60_descr');
$clrotulo->label('m60_quantent');
//$clrotulo->label('m61_abrev');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($_GET,2);exit;

$where = 'and  1=1';
$txt_where = "1=1";
$xordem = '';

if($ordem == 'n'){
  if($tipo_ordem == 'a'){
    $xordem = 'm60_codmater asc ';
    $head5 = " ORDEM : NUMÉRICA - ASCENDENTE";
  }
  else{
    $xordem = 'm60_codmater desc ';
    $head5 = " ORDEM : NUMÉRICA - DESCENDENTE";
  }
}
elseif($ordem == 'a'){
  if($tipo_ordem == 'a'){
    $xordem = 'm60_descr asc ';
    $head5 = " ORDEM : ALFABÉTICA - ASCENDENTE";
  }
  else{
    $xordem = ' m60_descr desc ';
    $head5 = " ORDEM : ALFABÉTICA - DESCENDENTE";
  }
} 

$info_listar_serv = "";

if ($listar_serv == "M") {
  
  $txt_where           .= " and (pc01_servico is false or pc01_servico is null)";
  $info_listar_serv    .= " LISTAR : SOMENTE MATERIAIS";
  
} else if ($listar_serv == "S") {
  
  $txt_where           .= " and pc01_servico is true ";
  $info_listar_serv    .= " LISTAR : SOMENTE SERVIÇOS";
  
} else {
  $info_listar_serv = " LISTAR : TODOS";
}
 
$head3 = "CADASTRO DE MATERIAIS ";
$head6 = "$info_listar_serv";

$campos = " distinct m60_codmater,trim(m60_descr) as m60_descr,m60_quantent,m60_ativo ";

if($listar_serv == "T") {
	$sql = $clmatmater->sql_query_file_pcmater(null, $campos, $xordem);
} else {
	$sql = $clmatmater->sql_query_file_pcmater(null, $campos, $xordem, "$txt_where");
}

$result = $clmatmater->sql_record($sql);

//$result =  $clmatmater->sql_record($clmatmater->sql_query_file(null,"*",$xordem));
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
for($x = 0; $x < pg_numrows($result);$x++)
{
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
   {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(35,$alt,$RLm60_codmater,1,0,"C",1);
      $pdf->cell(110,$alt,$RLm60_descr,1,0,"C",1);
      $pdf->cell(35,$alt,$RLm60_quantent,1,0,"C",1);
      $pdf->cell(13,$alt,"Situação",1,1,"C",1);
      //$pdf->cell(20,$alt,$RLm61_abrev,1,1,"R",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(35,$alt,$m60_codmater,0,0,"C",0);
   $pdf->cell(110,$alt,$m60_descr,0,0,"L",0);
   $pdf->cell(35,$alt,$m60_quantent,0,0,"C",0);
    if ($m60_ativo == "t") {
    	$situ = "Ativo";
    } else{
    	$situ = "Inativo";
    }
   $pdf->cell(13,$alt,$situ,0,1,"C",0);   
   //$pdf->cell(20,$alt,$m61_abrev,0,1,"L",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>