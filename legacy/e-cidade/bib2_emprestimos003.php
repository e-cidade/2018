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
require_once ("classes/db_emprestimo_classe.php");
require_once ("classes/db_emprestimoacervo_classe.php");
require_once ("classes/db_devolucaoacervo_classe.php");
require_once ("classes/db_biblioteca_classe.php");

$clemprestimo       = new cl_emprestimo;
$clemprestimoacervo = new cl_emprestimoacervo;
$cldevolucaoacervo  = new cl_devolucaoacervo;
$clbiblioteca       = new cl_biblioteca;

$depto  = db_getsession("DB_coddepto");
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));

if ($clbiblioteca->numrows != 0) {
  db_fieldsmemory($result, 0);
}

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ($filtro != "") {
  
  $filtro      = " and bi06_tipoitem = $filtro";
  $desc_filtro = "Filtro: $desc_filtro";
} else {
  
  $filtro      = "";
  $desc_filtro = "Filtro: TODOS";
}
$sql = "SELECT ov02_nome, bi18_retirada, bi18_devolucao, bi06_titulo, bi16_codigo, bi19_codigo, bi21_entrega
          FROM emprestimoacervo
               left  join devolucaoacervo on bi21_codigo                = bi19_codigo
               inner join emprestimo       on bi18_codigo               = bi19_emprestimo
               inner join acervo           on bi06_seq                  = bi19_acervo
               inner join carteira         on bi16_codigo               = bi18_leitor
               inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
               inner join biblioteca       on bi17_codigo               = bi07_biblioteca
               inner join leitor           on bi10_codigo               = bi16_leitor
               left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo
               left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                          and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
         WHERE bi17_codigo = $bi17_codigo AND bi18_devolucao <= '$data'
         $filtro
           AND not exists(select *
                            from devolucaoacervo
                           where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo)
         ORDER BY bi18_retirada desc";

$result = $clemprestimoacervo->sql_record($sql);
$linhas = $clemprestimoacervo->numrows;
if ($linhas == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem empréstimos para as opções escolhidas.');
}

$head1 = "RELATÓRIO DE EMPRÉSTIMOS";
$head2 = "NÃO DEVOLVIDOS ATÈ ".db_formatar($data,'d');
$head3 = $desc_filtro;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$troca = 1;
$alt   = 4;
$total = 0;
$cor1  = "0";
$cor2  = "1";
$cor   = "";

for ($x = 0; $x < $linhas; $x++) {
  
  db_fieldsmemory($result, $x);
  $pdf->setfillcolor(215);
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(10, $alt, "Código",       1, 0, "C", 1);
    $pdf->cell(65, $alt, "Leitor",       1, 0, "L", 1);
    $pdf->cell(55, $alt, "Acervo",       1, 0, "L", 1);
    $pdf->cell(20, $alt, "Retirada",     1, 0, "C", 1);
    $pdf->cell(20, $alt, "Devolver até", 1, 0, "C", 1);
    $pdf->cell(20, $alt, "Devolvido em", 1, 1, "C", 1);
    $troca = 0;
  }
  if ($cor == $cor1) {
    $cor = $cor2;
  } else {
    $cor = $cor1;
  }
  $pdf->setfillcolor(240);
  $pdf->setfont('arial','',7);
  $pdf->cell(10, $alt, $bi19_codigo,                                                     0, 0, "C", $cor);
  $pdf->cell(65, $alt, $ov02_nome,                                                       0, 0, "L", $cor);
  $pdf->cell(55, $alt, $bi06_titulo,                                                     0, 0, "L", $cor);
  $pdf->cell(20, $alt, db_formatar($bi18_retirada,'d'),                                  0, 0, "C", $cor);
  $pdf->cell(20, $alt, db_formatar($bi18_devolucao,'d'),                                 0, 0, "C", $cor);
  $pdf->cell(20, $alt, $bi21_entrega==""?"Não devolvido":db_formatar($bi21_entrega,'d'), 0, 1, "C", $cor);
  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190, $alt, 'TOTAL DE EMPRÉSTIMOS:  '.$total, "T", 0, "L", 0);
$pdf->Output();
?>