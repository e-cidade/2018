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
require_once ("classes/db_carteira_classe.php");
require_once ("classes/db_biblioteca_classe.php");

$clcarteira   = new cl_carteira;
$clbiblioteca = new cl_biblioteca;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$depto  = db_getsession("DB_coddepto");
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("", "bi17_codigo,bi17_nome", "", " bi17_coddepto = $depto"));

if ($clbiblioteca->numrows != 0) {
  db_fieldsmemory($result,0);
}

if ($ordem == "a") {
  
  $desc_ordem = "ALFABÉTICA";
  $order_by = "ov02_nome";
} else {
  
  $desc_ordem = "NUMÉRICA";
  $order_by = "bi16_codigo";
}

if ($categoria != "") {
  $where = " bi07_biblioteca = $bi17_codigo AND bi16_valida = 'S' AND bi07_codigo = $categoria";
} else {
  $where = " bi07_biblioteca = $bi17_codigo AND bi16_valida = 'S'";
}

$campos = "bi16_codigo, ov02_nome, ov02_cnpjcpf, ov02_endereco, ov02_numero, bi07_nome";
$sSqlCarteira = $clcarteira->sql_query_leitorcidadao("", $campos, $order_by, $where);
$rsCarteira   = $clcarteira->sql_record($sSqlCarteira);

if ($clcarteira->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe leitor cadastrado para as opções escolhidas.');
}

$head1 = "RELATÓRIO DE LEITORES";
$head2 = "Ordem: $desc_ordem";
$head3 = "Categoria: $desc_categoria";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$troca = 1;
$alt   = 4;
$total = 0;
$cor1  = "0";
$cor2  = "1";
$cor  = "";

for ($x = 0; $x < $clcarteira->numrows; $x++) {
  
  db_fieldsmemory($rsCarteira,$x);
  $pdf->setfillcolor(215);
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(5,  $alt, "",            1, 0, "C", 1);
    $pdf->cell(20, $alt, "N° Carteira", 1, 0, "C", 1);
    $pdf->cell(60, $alt, "Nome",        1, 0, "C", 1);
    $pdf->cell(20, $alt, "CPF",         1, 0, "C", 1);
    $pdf->cell(60, $alt, "Endereço",    1, 0, "C", 1);
    $pdf->cell(25, $alt, "Categoria",   1, 1, "C", 1);
    $troca = 0;
  }
  
  if ($cor == $cor1) {
    $cor = $cor2;
  } else {
    $cor = $cor1;
  }
  
  $sEndereco = $ov02_endereco.", ".$ov02_numero;
  $pdf->setfillcolor(240);
  $pdf->setfont('arial','',7);
  $pdf->cell(5,  $alt, $x+1,          0, 0, "C", $cor);
  $pdf->cell(20, $alt, $bi16_codigo,  0, 0, "C", $cor);
  $pdf->cell(60, $alt, $ov02_nome,    0, 0, "L", $cor);
  $pdf->cell(20, $alt, $ov02_cnpjcpf, 0, 0, "L", $cor);
  $pdf->cell(60, $alt, $sEndereco,    0, 0, "L", $cor);
  $pdf->cell(25, $alt, $bi07_nome,    0, 1, "L", $cor);
  $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(190, $alt, 'TOTAL DE LEITORES:  '.$total, "T", 0, "L", 0);
$pdf->Output();
?>