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
$clrotulo->label('pc01_codmater');
$clrotulo->label('pc01_descrmater');
//$clrotulo->label('m61_abrev');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$xordem  = '';
$dbwhere = "1=1 ";

if ($ordem == 'n') {
  if ($tipo_ordem == 'a') {
    $xordem = 'm60_codmater asc ';
    $head5 = " ORDEM : NUMÉRICA - ASCENDENTE";
  } else {
    $xordem = 'm60_codmater desc ';
    $head5 = " ORDEM : NUMÉRICA - DESCENDENTE";
  }
} else if ($ordem == 'a') {
  if ($tipo_ordem == 'a') {
    $xordem = 'm60_descr asc ';
    $head5 = " ORDEM : ALFABÉTICA - ASCENDENTE";
  } else {
    $xordem = ' m60_descr desc ';
    $head5 = " ORDEM : ALFABÉTICA - DESCENDENTE";
  }
}

if (isset($listar_mat)&&trim($listar_mat)!="") {
  if (trim($listar_mat) == "I") {
    $head6    = " MATERIAIS: INATIVOS";
    $dbwhere .= "and m60_ativo is false";
  }
  
  if (trim($listar_mat) == "A") {
    $head6    = " MATERIAIS: ATIVOS";
    $dbwhere .= "and m60_ativo is true";
  }
  
  if (trim($listar_mat) == "T") {
    $head6   = " MATERIAIS: TODOS";
  }
}

$info_listar_serv = "";

if ($listar_serv == "M") {
  
  $dbwhere          .= " and (pc01_servico is false or pc01_servico is null) ";
  $info_listar_serv .= " LISTAR: SOMENTE MATERIAIS";
  
} else if ($listar_serv == "S") {
  
  $dbwhere          .= " and pc01_servico is true ";
  $info_listar_serv .= " LISTAR: SOMENTE SERVIÇOS";
  
} else {
  $info_listar_serv = " LISTAR: TODOS";
}

$head3 = "MATERIAIS E ORIGEM ";
$head7 = "$info_listar_serv";

$campos = " distinct m60_codmater,trim(m60_descr) as m60_descr,pc01_codmater,trim(pc01_descrmater) as pc01_descrmater";

if ($listar_serv == "T") {
  $sql = $clmatmater->sql_query_com(null, $campos, $xordem, $dbwhere);
} else {
  $sql = $clmatmater->sql_query_com_pcmater(null, $campos, $xordem, $dbwhere);
}

$result =$clmatmater->sql_record($sql);

//$result =  $clmatmater->sql_record($clmatmater->sql_query_com(null,"*",$xordem,$dbwhere));
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0) {
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
for ($x = 0; $x < pg_numrows($result); $x++) {
  db_fieldsmemory($result,$x);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(110,$alt,"Almoxarifado",1,0,"C",1);
    $pdf->cell(80,$alt,"Compras",1,1,"C",1);
    $pdf->cell(20,$alt,"Cod. Material",1,0,"C",1);
    $pdf->cell(90,$alt,"Descrição",1,0,"C",1);
    $pdf->cell(20,$alt,"Cod. Material",1,0,"C",1);
    $pdf->cell(60,$alt,"Descrição",1,1,"C",1);
    
    //$pdf->cell(20,$alt,$RLm61_abrev,1,1,"R",1);
    $troca = 0;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(20, $alt, $m60_codmater,0,0,"C",0);
  $pdf->cell(90, $alt, substr($m60_descr, 0, 60), 0, 0, "L", 0);
  $pdf->cell(20, $alt, $pc01_codmater,0,0,"C",0);
  $pdf->cell(80, $alt, $pc01_descrmater,0,1,"L",0);
  
  $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>