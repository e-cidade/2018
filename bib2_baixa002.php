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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("classes/db_baixabib_classe.php");
require_once ("classes/db_biblioteca_classe.php");
require_once ("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clbiblioteca = new cl_biblioteca;
$clbaixa      = new cl_baixa;
$depto        = db_getsession("DB_coddepto");
$result       = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));

if ($clbiblioteca->numrows != 0) {
  
  db_fieldsmemory($result,0);
}
if ($filtro != "") {
  
  $where    = " bi06_biblioteca = $bi17_codigo AND bi06_tipoitem = $filtro AND bi08_inclusao BETWEEN '$data1' AND '$data2'";
  $order_by = " bi08_inclusao desc";
} else {
  
  $where    = " bi06_biblioteca= $bi17_codigo AND bi08_inclusao BETWEEN '$data1' AND '$data2'";
  $order_by = " bi08_inclusao desc";
}
$result = $clbaixa->sql_record($clbaixa->sql_query_baixa_acervo("", "*", $order_by, $where));
$linhas = $clbaixa->numrows;
if ($linhas == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem baixas no período.');
}
$head1 = "RELATÓRIO DE BAIXAS";
$head2 = "Período: ".db_formatar($data1,'d')." até ".db_formatar($data2,'d');
$pdf   = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage();
$troca = 1;
$alt   = 4;
$total = 0;
$cor1  = "0";
$cor2  = "1";
$cor   = "";

for ($x = 0; $x < $linhas; $x++) {
  
  db_fieldsmemory($result,$x);
  $pdf->setfillcolor(215);
  if ($x == 0) {
    
    $pdf->setfont('arial','b',8);
    $pdf->cell(10,$alt,"",1,0,"C",1);
    $pdf->cell(25,$alt,"Código da Baixa",1,0,"C",1);
    $pdf->cell(125,$alt,"Código do Exemplar",1,0,"L",1);
    $pdf->cell(30,$alt,"Data Baixa",1,1,"C",1);
  }
  if ($cor == $cor1) {
    $cor = $cor2;
  } else {
    $cor = $cor1;
  }
  $pdf->setfillcolor(240);
  $pdf->setfont('arial','',7);
  $pdf->cell(10,  $alt, $x+1,                           "T", 0, "C", $cor);
  $pdf->cell(25,  $alt,$bi08_codigo,                    "T", 0, "C", $cor);
  $pdf->cell(125, $alt,$bi23_codigo." - ".$bi06_titulo, "T", 0, "L", $cor);
  $pdf->cell(30,  $alt,db_formatar($bi08_inclusao,'d'), "T", 1, "C", $cor);
  $pdf->cell(190, $alt,"Descrição: ".$bi08_descr,        0, 1, "L", $cor);
  
  $sColecao = $bi29_nome != "" ? $bi29_nome : "";
  $pdf->cell(190, $alt,"Coleção: {$sColecao}",           0, 1, "L", $cor);
  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE BAIXAS NO PERÍODO: '.$total,"T",0,"L",0);
$pdf->Output();
?>