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

require_once ("fpdf151/scpdf.php");
require_once ("classes/db_emprestimo_classe.php");
require_once ("classes/db_emprestimoacervo_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clemprestimo       = new cl_emprestimo;
$clemprestimoacervo = new cl_emprestimoacervo;

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);

$head1 = "COMPROVANTE EMPRÉSTIMO DE ACERVO";
$pdf->addpage('P');
$pdf->ln(8);

//variaveis das coordenadas iniciais
$xhead          = 15;
$yhead          = 15;
$xNbarras       = 175;
$yNbarras       = 14;
$xCodbarras     = 175;
$yCodbarras     = 15;

//dados do empréstimo
$xEmp           = 10;
$yEmp           = 20;
$xRet           = 30;
$yRet           = 20;
$xDev           = 50;
$yDev           = 20;
$xLei           = 70;
$yLei           = 20;

for ($w = 1; $w <= 2; $w++) {
  
  $sql = "SELECT bi18_codigo, bi18_retirada, bi18_devolucao, ov02_sequencial, ov02_nome, bi17_nome
            FROM emprestimoacervo
                 inner join emprestimo       on bi18_codigo               = bi19_emprestimo
                 inner join carteira         on bi16_codigo               = bi18_carteira
                 inner join leitor           on bi10_codigo               = bi16_leitor
                 left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo
                 left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                            and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
                 inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
                 inner join biblioteca       on bi17_codigo               = bi07_biblioteca
                 inner join exemplar         on bi23_codigo               = bi19_exemplar
                 inner join acervo           on bi06_seq                  = bi23_acervo
           WHERE bi18_codigo = $emp";
  
  $result = $clemprestimo->sql_record($sql);
  db_fieldsmemory($result,0);
  
  $pdf->setfont('arial','b',8);
  $pdf->text($xhead, $yhead - 10, $bi17_nome);
  
  $pdf->setfont('arial', 'b', 18);
  $pdf->text($xhead, $yhead, $head1);
  
  $barras = str_pad($ov02_sequencial, 8, 0, STR_PAD_LEFT);
  
  $pdf->setfont('arial','b',7);
  $pdf->text($xEmp, $yEmp,   "Empréstimo");
  $pdf->text($xRet, $yRet,   "Retirada");
  $pdf->text($xDev, $yDev,   "Devolver até");
  $pdf->text($xLei, $yLei,   "Leitor");
  $pdf->line($xEmp, $yLei+1, $xCodbarras-5, $yLei+1);
  
  $pdf->setfont('arial','',7);
  ///dados do emprestimo
  $pdf->text($xEmp, $yEmp+4, $bi18_codigo);
  $pdf->text($xRet, $yRet+4, db_formatar($bi18_retirada,'d'));
  $pdf->text($xDev, $yDev+4, db_formatar($bi18_devolucao,'d'));
  $pdf->text($xLei, $yLei+4, $barras." - ".$ov02_nome);
  
  ///relaçao dos itens
  $pdf->setfont('arial','b',7);
  $pdf->text($xEmp,    $yEmp+10, "RELAÇÃO DE ITENS EMPRESTADOS");
  $pdf->text($xEmp,    $yEmp+15, "Cód. Barras");
  $pdf->text($xEmp+50, $yEmp+15, "Acervo");
  
  $pdf->line($xEmp,$yEmp+16,200,$yEmp+16);
  $sql    = $clemprestimoacervo->sql_query("","bi23_codbarras, bi06_titulo","","bi19_emprestimo = $emp");
  $result = $clemprestimoacervo->sql_record($sql);
  
  $cont  = 0;
  $cont2 = 4;
  
  for ($i = 0; $i < $clemprestimoacervo->numrows; $i++) {
    
    db_fieldsmemory($result, $i);
    $pdf->setfont('arial','',7);
    $pdf->text($xEmp,    $yEmp + 15 + $cont2, $bi23_codbarras);
    $pdf->text($xEmp+50, $yEmp + 15 + $cont2, $bi06_titulo);
    $cont2 += 4;
    $cont ++;
  }
  $pdf->setfont('arial','b',7);
  $pdf->text($xEmp+10, $yEmp+15+$cont2+4, "Total de ítens: $cont");
  
  //assinaturas
  $pdf->text($xhead,     $yhead+110, "________________________________");
  $pdf->text($xhead+100, $yhead+110, "________________________________");
  $pdf->text($xhead,     $yhead+115, "ASSINATURA DO LEITOR");
  $pdf->text($xhead+100, $yhead+115, "RESPONSÁVEL PELA BIBLIOTECA");
  
  //linha de corte
  if ($w == 1) {
    $pdf->line(0, $yhead+140, 220, $yhead+140);
  }
  
  $pdf->SetFillColor(000);
  $pdf->text($xNbarras, $yNbarras, $barras); //numeros do codbarras
  $pdf->int25($xCodbarras, $yCodbarras, $barras, 15, 0.341);
  $pdf->SetFillColor(255);
  ///monta nova linha
  $xhead      = 15;
  $yhead      = 15+158;
  $xNbarras   = 175;
  $yNbarras   = 14+158;
  $xCodbarras = 175;
  $yCodbarras = 15+158;
  
  //dados do empréstimo
  $xEmp  = 10;
  $yEmp  = 20+158;
  $xRet  = 30;
  $yRet  = 20+158;
  $xDev  = 50;
  $yDev  = 20+158;
  $xLei  = 70;
  $yLei  = 20+158;
}
$pdf->Output();
?>