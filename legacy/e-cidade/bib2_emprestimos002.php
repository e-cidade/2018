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
require_once ("classes/db_carteira_classe.php");
require_once ("classes/db_acervo_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clemprestimo       = new cl_emprestimo;
$clemprestimoacervo = new cl_emprestimoacervo;
$cldevolucaoacervo  = new cl_devolucaoacervo;
$clbiblioteca       = new cl_biblioteca;
$clcarteira         = new cl_carteira;
$clacervo           = new cl_acervo;

$depto  = db_getsession("DB_coddepto");

$sSqlBiblioteca = $clbiblioteca->sql_query("", "bi17_codigo,bi17_nome", "", " bi17_coddepto = $depto");
$rsBiblioteca   = $clbiblioteca->sql_record($sSqlBiblioteca);

if ($clbiblioteca->numrows != 0) {
  db_fieldsmemory($rsBiblioteca, 0);
}

$head3 = "";
$hoje  = date("Y-m-d");

if ($filtro == 1) {
  
  $head3 .= "Filtro: TODOS EMPRÉSTIMOS";
  $where  = "";
} else if ($filtro == 2) {
  
  $head3 .= "Filtro: EMPRÉSTIMOS DEVOLVIDOS";
  $where  = " AND exists(select *
                           from devolucaoacervo
                          where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo)";
} else if ($filtro == 3) {
  
  $head3 .= "Filtro: EMPRÉSTIMOS EM ABERTO";
  $where  = "AND not exists(select *
                              from devolucaoacervo
                             where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo)";
} else if ($filtro == 4) {
  
  $head3 .= "Filtro: EMPRÉSTIMOS EM ATRASO";
  $where  = "AND bi18_devolucao < '$hoje'
             AND not exists(select *
                              from devolucaoacervo
                              where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo)";
}

$sql = "SELECT ov02_nome,
               bi18_retirada,
               bi18_devolucao,
               bi06_titulo,
               bi16_codigo,
               bi19_codigo,
               bi21_entrega
          FROM emprestimoacervo
               left  join devolucaoacervo  on bi21_codigo               = bi19_codigo
               inner join emprestimo       on bi18_codigo               = bi19_emprestimo
               inner join exemplar         on bi23_codigo               = bi19_exemplar
               inner join acervo           on bi06_seq                  = bi23_acervo
               inner join carteira         on bi16_codigo               = bi18_carteira
               inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
               inner join biblioteca       on bi17_codigo               = bi07_biblioteca
               inner join leitor           on bi10_codigo               = bi16_leitor
               left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo
               left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                          and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
        WHERE bi17_codigo = $bi17_codigo AND bi18_retirada BETWEEN '$data1' AND '$data2'
        $where";

$head4 = "";

if ($leitor != "") {
  
  $sql          .= " AND bi16_codigo = $leitor";
  $sSqlCarteira  = $clcarteira->sql_query_leitorcidadao("", "ov02_nome", "", " bi16_codigo = $leitor");
  $rsCarteira    = $clcarteira->sql_record($sSqlCarteira);
  $head4        .= "Leitor: ".pg_result($rsCarteira, 0, 'ov02_nome')."\n";
}

if ($acervo != "") {
  
  $sql        .= " AND bi06_seq = $acervo";
  $sSqlAcervo  = $clacervo->sql_query("", "bi06_titulo", "", " bi06_seq = $acervo");
  $rsAcervo    = $clacervo->sql_record($sSqlAcervo);
  $head4      .= "Acervo: ".pg_result($rsAcervo, 0, 'bi06_titulo')."\n";
}

$sql .= " ORDER BY bi18_retirada desc";

$result = $clemprestimoacervo->sql_record($sql);
$linhas = $clemprestimoacervo->numrows;

if ($linhas == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem empréstimos para as opções escolhidas.');
}

$head1 = "RELATÓRIO DE EMPRÉSTIMOS";
$head2 = "Período: ".db_formatar($data1,'d')." até ".db_formatar($data2,'d');

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
  
  db_fieldsmemory($result,$x);
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
  
  $sRetirada  = db_formatar($bi18_retirada, 'd');
  $sDevolucao = db_formatar($bi18_devolucao, 'd');
  $sEntrega   = $bi21_entrega == "" ? "Não devolvido" : db_formatar($bi21_entrega, 'd');
  
  $pdf->setfillcolor(240);
  $pdf->setfont('arial','',7);
  $pdf->cell(10, $alt, $bi19_codigo, 0, 0, "C", $cor);
  $pdf->cell(65, $alt, $ov02_nome,   0, 0, "L", $cor);
  $pdf->cell(55, $alt, $bi06_titulo, 0, 0, "L", $cor);
  $pdf->cell(20, $alt, $sRetirada,   0, 0, "C", $cor);
  $pdf->cell(20, $alt, $sDevolucao,  0, 0, "C", $cor);
  $pdf->cell(20, $alt, $sEntrega,    0, 1, "C", $cor);
  $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(190, $alt, 'TOTAL DE EMPRÉSTIMOS:  '.$total, "T", 0, "L", 0);
$pdf->Output();
?>