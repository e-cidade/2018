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

session_start();
include("fpdf151/scpdf.php");
include("classes/db_exemplar_classe.php");
include("classes/db_assunto_classe.php");
include("classes/db_localacervo_classe.php");
include("classes/db_localizacao_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$lista = db_getsession('sListaImpressao');
$clexemplar = new cl_exemplar;
$classunto = new cl_assunto;
$cllocalacervo = new cl_localacervo;
$cllocalizacao = new cl_localizacao;
$result = $clexemplar->sql_record($clexemplar->sql_query_etiq3("","bi23_codigo,bi20_sequencia,bi27_letra,bi09_abrev,bi06_seq,bi06_titulo,bi03_classificacao,bi23_acervo","$ordenacao"," bi23_codigo in ($lista)"));
$linhas = $clexemplar->numrows;
$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);
$pdf->SetTopMargin(5);
$pdf->addpage('P');
$colunas = 0;
$cont = 0;
$posy = ceil($pdf->getY());
$posx = ceil($pdf->getX());
$addx = 0;
$addy = 0;
for($i=0;$i<$linhas;$i++){
 db_fieldsmemory($result,$i);
 $sequencia = $bi20_sequencia.$bi27_letra;
 $cont++;
 $colunas++;
 $result1 = $classunto->sql_record($classunto->sql_query_file("","bi15_assunto",""," bi15_acervo = ".@$bi06_seq." LIMIT 1"));
 if($classunto->numrows>0){
  db_fieldsmemory($result1,0);
 }else{
  $bi15_assunto = "SEM REGISTRO";
 }
 $pdf->setY($posy+$addy);
 $pdf->setX($posx+$addx);
 $pdf->cell(46,1,"","LRT",2,"C",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(46,3,"Título:","LR",2,"C",0);
 $pdf->setfont('arial','',8);
 $pdf->cell(46,3,substr($bi23_codigo." - ".$bi06_titulo,0,22),"LR",2,"C",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(46,3,"Assunto:","LR",2,"C",0);
 $pdf->setfont('arial','',8);
 $pdf->cell(46,3,substr($bi15_assunto,0,22),"LR",2,"C",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(46,3,"Localização:","LR",2,"C",0);
 $pdf->setfont('arial','',8);
 $pdf->cell(46,3,$bi09_abrev==""?"SEM REGISTRO":$bi09_abrev,"LR",2,"C",0);
 $pdf->setfont('arial','b',7);
 $pdf->cell(46,3,"Ordenação:","LR",2,"C",0);
 $pdf->setfont('arial','',8);
 $pdf->cell(46,3,$sequencia==""?"SEM REGISTRO":$sequencia,"LR",2,"C",0);
 $pdf->cell(46,1,"","LRB",2,"C",0);
 $addx += 48;
 if($colunas==4){
  $colunas = 0;
  $addx = 0;
  $addy += 27;
 }
 if($cont==40){
  $pdf->addpage('P');
  $colunas = 0;
  $cont = 0;
  $addx = 0;
  $addy = 0;
 }
}
$pdf->Output();
?>