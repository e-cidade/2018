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

require_once("fpdf151/scpdf.php");
require_once("classes/db_carteira_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcarteira = new cl_carteira;
$prop       = explode(",",$lista);
$pdf        = new scpdf();

$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);

$head1 = "CARTEIRA DE LEITOR DA BIBLIOTECA";
$head2 = "";
$head3 = "";

$pdf->addpage('P');
$pdf->ln(0);

$colunas = 0;
$alt     = $pdf->getY();
$lar     = $pdf->getX();
$setY    = 0;
$multiX  = 20;
$multiY  = 7;
$multi1  = 20;
$multi2  = 32;

//coordenadas iniciais
$rectx  = $pdf->getX();//retangulo
$recty  = 5;
$imgx   = $pdf->getX()+10;//imagem
$imgy   = 20;
$bibx   = $pdf->getX()+15;//nome da biblioteca
$biby   = 19;
$leix   = $pdf->getX()+21;//nome do leitor
$leiy   = 23;
$docx   = $pdf->getX()+25;//documento do leitor
$docy   = 28;
$identx = $pdf->getX()+55;//documento do leitor
$identy = 32;
$catx   = $pdf->getX()+25;//categoria do leitor
$caty   = 32;
$valx   = $pdf->getX()+25;//validade da carteira
$valy   = 36;
$numx   = $pdf->getX()+37;//numero codigo de barras
$numy   = 41;
$codx   = $pdf->getX()+30;//codigo de barras
$cody   = 44;
$cont   = 0;
$total  = 0;

for ($i = 0; $i < count($prop); $i++) {
	
  if ($cont == 10 && $total <= count($prop)) {
  	
    $pdf->addpage('P');
    $pdf->ln(0);
    $multiX = 20;
    $multiY = 7;
    $multi1 = 20;
    $multi2 = 32;
    
    //coordenadas iniciais
    $rectx   = $pdf->getX();//retangulo
    $recty   = 5;
    $imgx    = $pdf->getX()+14;//imagem
    $imgy    = 20;
    $bibx    = $pdf->getX()+15;//nome da biblioteca
    $biby    = 19;
    $leix    = $pdf->getX()+21;//nome do leitor
    $leiy    = 23;
    $docx    = $pdf->getX()+25;//documento do leitor
    $docy    = 28;
    $identx  = $pdf->getX()+55;//documento do leitor
    $identy  = 32;
    $catx    = $pdf->getX()+25;//categoria do leitor
    $caty    = 32;
    $valx    = $pdf->getX()+25;//validade da carteira
    $valy    = 36;
    $numx    = $pdf->getX()+37;//numero codigo de barras
    $numy    = 41;
    $codx    = $pdf->getX()+30;//codigo de barras
    $cody    = 44;
    $colunas = 0;
    $cont    = 0;
  }

  $alt      = $pdf->setY($setY);
  $lar      = $pdf->setX(0);   
  $sCampos  = " carteira.*, leitorcategoria.*, biblioteca.*, ov02_nome, ov02_cnpjcpf, ov02_ident";
  $sql      = $clcarteira->sql_query_leitorcidadao("",$sCampos,"","bi16_codigo = $prop[$i] and bi16_validade > now()");
  $result   = $clcarteira->sql_record($sql);
  
  if ($clcarteira->numrows > 0) {
  	 
    db_fieldsmemory($result,0);
    $t1 = str_pad($bi16_codigo,11,0,STR_PAD_LEFT); //numero codigo barras
    $pdf->rect($rectx, $recty, 90, 54, 'D'); //retangulo
    $pdf->Image('imagens/files/logo_boleto.png', $imgx, $imgy, 10); //imagem
    
    $pdf->setfont('arial','b',10);
    $pdf->setY($multiY);
    $pdf->setX($multiX);
    $pdf->multiCell(75, 5, $bi17_nome, 0, "C", 0, 0); //nome biblioteca
      
    $pdf->setfont('arial','b',9);
    $pdf->setY($multi1);
    $pdf->setX($multi2);
    $pdf->multiCell(65, 3, $ov02_nome, 0, "C", 0, 0); //nome do leitor
    
    $pdf->setfont('arial','',8);
    $pdf->text($docx,   $docy,   "CPF: ".$ov02_cnpjcpf); //cgccpf leitor
    $pdf->text($identx, $identy, "Identidade: ".substr($ov02_ident, 0, 12)); //identidade leitor
    $pdf->text($catx,   $caty,   "Categoria: ".$bi07_nome); //categoria leitor
    $pdf->text($valx,   $valy,   "Validade: ".db_formatar($bi16_validade, 'd')); //validade da carteira
    
    $pdf->setfont('arial','b',9);
    $pdf->text($numx, $numy, $t1); //numeros do codbarras
    $pdf->SetFillColor(000); //fundo codbarras
    $pdf->int25($codx, $cody, $t1, 12, 0.341); //codbarras
    
    $rectx  += 100;
    $imgx   += 100;
    $multiX += 100;
    $multi2 += 100;
    $bibx   += 100;
    $leix   += 100;
    $docx   += 100;
    $identx += 100;
    $catx   += 100;
    $valx   += 100;
    $numx   += 100;
    $codx   += 100;
    $total  += 1;
    $cont++; 
    
    if (($colunas%2) != 0) {
    	
      $recty  += 55;
      $rectx  -= 200;
      $imgy   += 55;
      $imgx   -= 200;
      $multiY += 55;
      $multiX -= 200;
      $multi1 += 55;
      $multi2 -= 200;
      $biby   += 55;
      $bibx   -= 200;
      $leiy   += 55;
      $leix   -= 200;
      $docy   += 55;
      $docx   -= 200;
      $identy += 55;
      $identx -= 200;
      $caty   += 55;
      $catx   -= 200;
      $valy   += 55;
      $valx   -= 200;
      $numy   += 55;
      $numx   -= 200;
      $cody   += 55;
      $codx   -= 200;
    }
    $colunas++;
  }
}
$pdf->Output();
?>