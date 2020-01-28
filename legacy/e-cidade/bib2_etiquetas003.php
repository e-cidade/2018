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
require_once("fpdf151/scpdf.php");
require_once("libs/db_utils.php");
require_once("classes/db_exemplar_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$lista = db_getsession('sListaImpressao');

$oDaoExemplar = db_utils::getdao('exemplar');
$sSql         = $oDaoExemplar->sql_query("", "*", $ordenacao, " bi23_codigo in ($lista) ");
$rsSql        = $oDaoExemplar->sql_record($sSql);
$iLinhas      = $oDaoExemplar->numrows;

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);
$pdf->addpage('P');
$pdf->ln(8);

//coordenadas iniciais
$recty       = 5;//retangulo
$rectx       = 10;
$numx        = 29;//numero codigo de barras
$numy        = 10;
$codx        = 21;//codigo de barras
$cody        = 12;
$acerx       = 21;//nome do leitor
$acery       = 28;
$largura_ret = 64;//largura do retângulo
$altura_ret  = 25;//altura do retângulo
$colunas     = 0;
$cont        = 0;

for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

  db_fieldsmemory($rsSql, $iCont);
  
  $bi23_codbarras = str_pad($bi23_codbarras, 15, '0', STR_PAD_LEFT);
  $cont++;
  $pdf->rect($rectx, $recty, $largura_ret, $altura_ret, 'D');//retangulo
  $pdf->setfont('arial', '', 9);
  $pdf->text($numx, $numy, @$bi23_codbarras);//numero codigo de barras
  $pdf->setfont('arial', '', 7);
  $pdf->SetFillColor(000);//fundo codbarras
  $pdf->int25($codx, $cody, @$bi23_codbarras, 12, 0.341);//codbarras
  $pdf->text($acerx, $acery, @$bi23_codigo." - ".substr($bi06_titulo, 0, 25));//numero codigo de barras
  
  $rectx += $largura_ret+1;
  $acerx += $largura_ret+1;
  $numx  += $largura_ret+1;
  $codx  += $largura_ret+1;
  $colunas++;
  
  if ($colunas == 3) {

    $recty  += $altura_ret + 1;
    $rectx  -= ($largura_ret + 1) * 3;
    $acery  += $altura_ret + 1;
    $acerx  -=($largura_ret + 1) * 3;
    $numy   += $altura_ret + 1;
    $numx   -=($largura_ret + 1) * 3;
    $cody   += $altura_ret + 1;
    $codx   -= ($largura_ret + 1) * 3;
    $colunas = 0;

 }

 if ($cont == 30) {

   $pdf->addpage('P');
   $pdf->ln(8);
   $recty       = 5;//retangulo
   $rectx       = 10;
   $numx        = 29;//numero codigo de barras
   $numy        = 10;
   $codx        = 21;//codigo de barras
   $cody        = 12;
   $acerx       = 21;//nome do leitor
   $acery       = 28;
   $largura_ret = 64;//largura do retângulo
   $altura_ret  = 25;//altura do retângulo
   $colunas     = 0;
   $cont        = 0;

 }

}

$pdf->Output();

?>