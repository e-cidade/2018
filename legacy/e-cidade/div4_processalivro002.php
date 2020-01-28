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

include ("fpdf151/scpdf.php");
include ("libs/db_sql.php");
include ("classes/db_divida_classe.php");
include ("classes/db_proced_classe.php");
include ("classes/db_arreinscr_classe.php");
include ("classes/db_arrematric_classe.php");
include ("classes/db_db_docparag_classe.php");
include ("classes/db_pardiv_classe.php");
include ("libs/db_utils.php");
include ("libs/db_libdocumento.php");

// ################################################## //
//               VARIAVES DISPONIVEIS                 //
//     livro                  = $livro  
//     exercicio              = $exercicio 
//     procedencia            = $descr
//     nº primeira folha      = $folhaini
//     nº ultima folha        = $ultimafolha
//     qtd de folhas do livro = $qtdfolha
//     natureza               = $natureza
//     data de abertura       = $datacompleta


// ################################################## //

$cldivida      = new cl_divida();
$clproced      = new cl_proced();
$clarrematric  = new cl_arrematric();
$clarreinscr   = new cl_arreinscr();
$clpardiv      = new cl_pardiv();
$cldb_docparag = new cl_db_docparag();

$clrotulo = new rotulocampo();
$clrotulo->label('v01_coddiv');
$clrotulo->label('v01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_ender');
$clrotulo->label('v01_exerc');
$clrotulo->label('v01_dtvenc');
$clrotulo->label('v01_proced');
$clrotulo->label('v03_descr');
$clrotulo->label('v01_vlrhis');
$clrotulo->label('v01_numpar');
$clrotulo->label('v03_receit');

parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS); //exit;
$valor = 0;
$alt = "5";
$pdf = new SCPDF();
$pdf->Open();
$pdf->AliasNbPages();
if (isset($v01_dtoper_ano)) {
  $datacorr = $v01_dtoper_ano . "-" . $v01_dtoper_mes . "-" . $v01_dtoper_dia;
}

$instit = db_getsession("DB_instit");
$livro = $v01_livro;
$result = $cldivida->sql_record($cldivida->sql_query_divida(null, "divida.v01_coddiv,
                                                                   v01_dtinsc,
                                                                   v01_numpar,
                                                                   v01_dtoper,
                                                                   v01_valor,
                                                                   v01_numcgm,
                                                                   z01_nome,
                                                                   v01_exerc,
                                                                   v01_dtvenc,
                                                                   v01_proced,
                                                                   v03_receit,
                                                                   v03_descr,
                                                                   v01_vlrhis,
                                                                   v01_numpre,
                                                                   z01_ender,
                                                                   (select k00_numpre 
                                                                      from arrecad 
                                                                     where arrecad.k00_numpre = divida.v01_numpre 
                                                                       and arrecad.k00_numpar = divida.v01_numpar
                                                                     limit 1) as k00_numprearrecad, 
                                                                   (select k00_numpre 
                                                                      from arrecant 
                                                                     where divida.v01_numpre = arrecant.k00_numpre 
                                                                       and divida.v01_numpar = arrecant.k00_numpar
                                                                     limit 1) as k00_numprearrecant,
                                                                   (select numpreant 
                                                                      from termodiv 
                                                                     where divida.v01_coddiv = termodiv.coddiv
                                                                    union  
                                                                   select d.v01_numpre 
                                                                     from termoini 
                                                                    inner join inicialcert on termoini.inicial         = inicialcert.v51_inicial 
                                                                    inner join certdiv     on inicialcert.v51_certidao = certdiv.v14_certid 
                                                                    inner join divida as d on certdiv.v14_coddiv       = d.v01_coddiv  
                                                                    where divida.v01_coddiv = d.v01_coddiv 
                                                                    limit 1) as numpreant, 
                                                                   divida.v01_folha", 
                                                                   'v01_folha, v01_coddiv,v01_numpar',
                                                                   "v01_livro = {$livro} 
                                                                    and v01_instit = {$instit}"));
                                                                    
$numrows = $cldivida->numrows;

if ( $numrows == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Código do livro não encontrado ou não vinculado a instituição logada.");
}

db_fieldsmemory($result, 0);
$pdf->AddPage("L");
$dados = @ db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = " . db_getsession("DB_instit"));
if (pg_numrows($dados) > 0) {
  db_fieldsmemory($dados, 0);
}
$pdf->SetXY(150, 1);
if (strlen($nomeinst) > 42)
  $TamFonteNome = 8;
else
  $TamFonteNome = 9;
$dist = 33;

$pdf->Image("imagens/files/$logo", 7, 3, 15);
$pdf->SetFont('Arial', 'BI', $TamFonteNome);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Text($dist, 6, $nomeinst);
$pdf->Text($dist, 10, $ender);
$pdf->Text($dist, 14, $munic . " - " . $uf . " - " . $telef);
$pdf->Text($dist, 18, $email);
$pdf->Text($dist, 22, $url);
//$pdf->Text(230,18,"Data de Correção: ".db_formatar($datacorr,'d')."");	
$pdf->SetY(25);

$pdf->setfillcolor(255);
$pdf->SetFont('Arial', 'B', 12);

// exercicio
$sqlexerc = "select count(*),v01_exerc from divida where v01_livro = $livro and v01_instit = $instit group by v01_exerc order by count(*) desc limit 1 ";
$resultexec = db_query($sqlexerc);
db_fieldsmemory($resultexec, 0);
$exercicio = $v01_exerc;

// data da abertura
$sqldata = "select count(*),v01_dtinsc from divida where v01_livro = $livro and v01_instit = $instit group by v01_dtinsc order by count(*) desc limit 1";
$resultexec = db_query($sqldata);
db_fieldsmemory($resultexec, 0);
$datainscr = $v01_dtinsc;

list ( $ano, $mes, $dia ) = split('-', $datainscr);
$mes1 = db_mes($mes, 2);
$datacompleta = "$munic, $dia de $mes1 de $ano";

//informações de livro e folha
$sqlfolha = "select v01_livro,
                    lpad(min(v01_folha),3,0) as folhaini, 
                    lpad(max(v01_folha),3,0) as ultimafolha, 
                    array_accum( distinct v01_folha ) as folhas,
                    count( distinct v01_folha ) as qtdfolha 
               from divida 
              where v01_livro  = $livro 
                and v01_instit = $instit
                and v01_folha <> 0
              group by v01_livro  ";
$resultfolha = db_query($sqlfolha);
db_fieldsmemory($resultfolha, 0);

$folhas = str_replace('{','',$folhas);
$folhas = str_replace('}','',$folhas);

//// procedencias
$sSql = " select distinct v03_descr, v03_tributaria  
            from (select distinct v01_proced,
                                  sum(v01_vlrhis) as valor 
                             from divida 
                            where v01_livro = {$livro} 
                              and v01_instit = " . db_getsession('DB_instit') . " 
                         group by v01_proced) as x 
                  inner join proced     on v01_proced = v03_codigo  
                  inner join tipoproced on v07_sequencial = v03_tributaria";
                             
$resultproc = db_query($sSql);
$numr       = pg_numrows($resultproc);

$tra = "";
$descr = "";
$natureza = "";

if ($numr > 0) {
  for($x = 0; $x < $numr; $x ++) {
    db_fieldsmemory($resultproc, $x);
    
    $descr .= $tra . $v03_descr;
    
    if ($v03_tributaria == "1") {
      $v03_tributaria = "Tributária";
    } else if ($v03_tributaria == "2") {
      $v03_tributaria = "Não Tributária";
    } else {
    	$v03_tributaria = "Certidão TCE";
    }
    if ($natureza != $v03_tributaria) {
      $natureza .= $tra . $v03_tributaria;
    }
    $tra = " , ";
  
  }

}

 
/*
echo "<br> livro        = $livro  
      <br> exerc        = $exercicio 
      <br> descr        = $descr
      <br> ultima folha = $ultimafolha
      <br> natureza     = $natureza
           data de abertura = $datacompleta
      <br> ";

*/
$pdf->ln(10);
$pdf->Cell(280, 7, "INSCRIÇÃO DA DÍVIDA ATIVA", 0, 1, "C", 1);
$pdf->Cell(280, 7, "LIVRO $livro", 0, 1, "C", 1);
$pdf->Cell(280, 7, "EXERCÍCIO $exercicio", 0, 1, "C", 1);
$pdf->Cell(280, 7, "TERMO DE ABERTURA", 0, 1, "C", 1);

$pdf->ln(7);
$x = $pdf->getX();
$par = $clpardiv->sql_record($clpardiv->sql_query_file());

if (pg_numrows($par) > 0) {
  db_fieldsmemory($par, 0);
  if (@$v04_docum != "") {
    
    $xx = db_query($cldb_docparag->sql_query(null, null, "*", null, "db03_tipodoc = 30"));
    if (pg_num_rows($xx) == 0) {
      db_redireciona("db_erros.php?fechar=true&db_erro=Verifique configuração do documento. [TIPODOC-30].");
      exit();
    }
    
    $objteste = new libdocumento(30);
    if ($objteste->lErro) {
      db_redireciona("db_erros.php?fechar=true&db_erro={$objtest->sMsgErro}.");
      exit();
    }
    $objteste->getParagrafos();
    $parag = $objteste->aParagrafos;
    /*echo "<pre>";
       print_r($parag);
       echo "</pre>";
       */
    //echo "<br> corpo =$corpo ";
    $pdf->SetFont('Arial', '', 10);
    foreach ( $parag as $chave ) {
      // echo "<br> valor =".$chave->db02_texto."<br>\n";
      if ($chave->db02_alinha == 1) {
        $alinhamento = "J";
      } elseif ($chave->db02_alinha == 2) {
        $alinhamento = "C";
      } elseif ($chave->db02_alinha == 3) {
        $alinhamento = "R";
      } elseif ($chave->db02_alinha == 4) {
        $alinhamento = "L";
      } else {
        $alinhamento = "J";
      }
      
      $pdf->MultiCell(280, 6, "        " . $objteste->geratexto($chave->db02_texto), 0, $alinhamento);
      $pdf->cell(280, $alt, "", 0, 1, "C", 1);
    }
  
  }
}

$pdf->getX($x);
// colocar fc_corre junto com o select...


$folha = 1;
//echo "$complementar"; exit;


if ($tipo == "c") {
  $pdf->AddPage("L");
  $pdf->Image("imagens/files/$logo", 7, 3, 15);
  $pdf->SetFont('Arial', 'BI', $TamFonteNome);
  $pdf->SetFont('Arial', 'I', 8);
  $pdf->Text($dist, 6, $nomeinst);
  $pdf->Text($dist, 10, $ender);
  $pdf->Text($dist, 14, $munic . " - " . $uf . " - " . $telef);
  $pdf->Text($dist, 18, $email);
  $pdf->Text($dist, 22, $url);
  $pdf->Text(230, 14, "Livro: $livro");
  $pdf->Text(230, 18, "Folha: $folha");
  $pdf->Text(230, 22, "Data de Correção: " . db_formatar($datacorr, 'd') . "");
  $pdf->SetY(25);
  $bord = 0;
  
  $pdf->setfont('arial', 'I', 7);
  $pdf->cell(13, $alt, "Cod", $bord, 0, "C", 1);
  $pdf->cell(60, $alt, $RLz01_nome, $bord, 0, "L", 1);
  $pdf->cell(10, $alt, $RLv01_exerc, $bord, 0, "C", 1);
  $pdf->cell(40, $alt, $RLv03_descr, $bord, 0, "L", 1);
  $pdf->cell(7, $alt, "Parc", $bord, 0, "C", 1);
  $pdf->cell(15, $alt, "Dt inscr", $bord, 0, "C", 1);
  $pdf->cell(24, $alt, "Origem", $bord, 0, "L", 1);
  $pdf->cell(15, $alt, $RLv01_dtvenc, $bord, 0, "C", 1);
  $pdf->cell(20, $alt, $RLv01_vlrhis, $bord, 0, "R", 1);
  $pdf->cell(16, $alt, "Vlr corrigido", $bord, 0, "R", 1);
  $pdf->cell(12, $alt, "Juros", $bord, 0, "R", 1);
  $pdf->cell(12, $alt, "Multa", $bord, 0, "R", 1);
  $pdf->cell(12, $alt, "Desconto", $bord, 0, "R", 1);
  $pdf->cell(20, $alt, "Total", $bord, 0, "R", 1);
  $pdf->ln();
}
$cont = 0;
$hist_total = 0;
$corr_total = 0;
$juro_total = 0;
$mult_total = 0;
$tota_total = 0;
$vlrjur = 0;
$vlrmul = 0;
$vlrcor = 0;
$total = 0;
$matrics = "0";
$inscrs = "0";
$cgms = "0";
$cont = 0;
$n_reg = 0;

$total_proc = array ();

db_query("begin");

//db_criatabela($result);exit;


for($i = 0; $i < $numrows; $i ++) {
  db_fieldsmemory($result, $i);
  if ($k00_numprearrecant == "" && $k00_numprearrecad == "" && $numpreant == "") {
    $n_reg += 1;
    continue;
  }
  $matric = $clarrematric->sql_record($clarrematric->sql_query_file($v01_numpre, null, "k00_numpre,k00_matric"));
  if ($clarrematric->numrows > 0) {
    db_fieldsmemory($matric, 0);
    $origem = "Matrícula " . @ $k00_matric;
    $matrics += 1;
  } else {
    $inscr = $clarreinscr->sql_record($clarreinscr->sql_query_file($v01_numpre, null, "k00_numpre,k00_inscr"));
    if ($clarreinscr->numrows > 0) {
      db_fieldsmemory($inscr, 0);
      $origem = "Inscrição " . @ $k00_inscr;
      $inscrs += 1;
    } else {
      $origem = "Numcgm " . @ $v01_numcgm;
      $cgms += 1;
    }
  }
  if ($cont == 30) {
    $folha ++;
    if ($tipo == "c") {
      $cont = 0;
      $pdf->addpage("L");
      $pdf->Image("imagens/files/$logo", 7, 3, 15);
      $pdf->SetFont('Arial', 'BI', $TamFonteNome);
      $pdf->SetFont('Arial', 'I', 8);
      $pdf->Text($dist, 6, $nomeinst);
      $pdf->Text($dist, 10, $ender);
      $pdf->Text($dist, 14, $munic . " - " . $uf . " - " . $telef);
      $pdf->Text($dist, 18, $email);
      $pdf->Text($dist, 22, $url);
      $pdf->Text(230, 14, "Livro: $livro ");
      $pdf->Text(230, 18, "Folha: $folha");
      $pdf->Text(230, 22, "Data de Correção: " . db_formatar($datacorr, 'd') . "");
      $pdf->SetY(25);
      $pdf->setfont('arial', 'I', 7);
      $pdf->cell(13, $alt, "Cod", $bord, 0, "C", 1);
      $pdf->cell(60, $alt, $RLz01_nome, $bord, 0, "L", 1);
      $pdf->cell(10, $alt, $RLv01_exerc, $bord, 0, "C", 1);
      $pdf->cell(40, $alt, $RLv03_descr, $bord, 0, "L", 1);
      $pdf->cell(7, $alt, "Parc", $bord, 0, "C", 1);
      $pdf->cell(15, $alt, "Dt inscr", $bord, 0, "C", 1);
      $pdf->cell(24, $alt, "Origem", $bord, 0, "L", 1);
      $pdf->cell(15, $alt, $RLv01_dtvenc, $bord, 0, "C", 1);
      $pdf->cell(20, $alt, $RLv01_vlrhis, $bord, 0, "R", 1);
      $pdf->cell(16, $alt, "Vlr corrigido", $bord, 0, "R", 1);
      $pdf->cell(12, $alt, "Juros", $bord, 0, "R", 1);
      $pdf->cell(12, $alt, "Multa", $bord, 0, "R", 1);
      $pdf->cell(12, $alt, "Desconto", $bord, 0, "R", 1);
      $pdf->cell(20, $alt, "Total", $bord, 0, "R", 1);
      $pdf->ln();
    }
  }

  if ($v01_folha == 0) {
    db_query("update divida set v01_folha = $folha where v01_livro = $livro and v01_coddiv = $v01_coddiv and v01_instit = " . db_getsession('DB_instit'));
  }

  $result3 = $clproced->sql_record($clproced->sql_query_file(null, "v03_receit", '', " v03_codigo = $v01_proced and v03_instit = " . db_getsession('DB_instit')));
  if (pg_numrows($result3) > 0) {
    db_fieldsmemory($result3, 0);
  }
  $sql2 = "select fc_corre($v03_receit,'$v01_dtvenc',$v01_vlrhis,'$datacorr'," . db_getsession("DB_anousu") . ",'$v01_dtvenc') as vlrcor from divida where v01_coddiv = $v01_coddiv and v01_instit = " . db_getsession('DB_instit');
  $result2 = db_query($sql2);
  if (pg_numrows($result2) > 0) {
    db_fieldsmemory($result2, 0);
  }
  $sql2 = "select fc_juros($v03_receit,'$v01_dtvenc','$datacorr','$v01_dtoper',false," . db_getsession("DB_anousu") . ") as vlrjur from divida where v01_coddiv = $v01_coddiv and v01_instit = " . db_getsession('DB_instit');
  $result2 = db_query($sql2);
  if (pg_numrows($result2) > 0) {
    db_fieldsmemory($result2, 0);
  }
  $vlrjur = $vlrcor * $vlrjur;
  $sql2 = "select fc_multa($v03_receit,'$v01_dtvenc','$datacorr','$v01_dtoper'," . db_getsession("DB_anousu") . ") as vlrmul from divida where v01_coddiv = $v01_coddiv and v01_instit = " . db_getsession('DB_instit');
  $result2 = db_query($sql2);
  if (pg_numrows($result2) > 0) {
    db_fieldsmemory($result2, 0);
  }
  $vlrmul = $vlrcor * $vlrmul;
  if (($vlrcor + $vlrjur + $vlrmul) == 0) {
    continue;
  }
  $z01_nome = (strlen($z01_nome) > 10 ? substr($z01_nome, 0, 38) : $z01_nome);
  $pdf->setfont('arial', 'b', 7);
  if ($tipo == "c") {
    $pdf->cell(13, $alt, $v01_coddiv, $bord, 0, "C", 0);
    $pdf->cell(60, $alt, $z01_nome, $bord, 0, "L", 0);
    $pdf->cell(10, $alt, $v01_exerc, $bord, 0, "C", 0);
    $pdf->cell(40, $alt, $v01_proced . " - " . $v03_descr, $bord, 0, "L", 0);
    $total = $vlrcor + $vlrjur + $vlrmul;
    $pdf->cell(7, $alt, $v01_numpar, $bord, 0, "C", 0);
    $pdf->cell(15, $alt, db_formatar($v01_dtinsc, 'd'), $bord, 0, "C", 0);
    $pdf->cell(24, $alt, $origem, $bord, 0, "L", 0);
    $pdf->cell(15, $alt, db_formatar($v01_dtvenc, "d"), $bord, 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($v01_vlrhis, 'f'), $bord, 0, "R", 0);
    $pdf->cell(16, $alt, db_formatar($vlrcor, 'f'), $bord, 0, "R", 0);
    $pdf->cell(12, $alt, db_formatar($vlrjur, 'f'), $bord, 0, "R", 0);
    $pdf->cell(12, $alt, db_formatar($vlrmul, 'f'), $bord, 0, "R", 0);
    $pdf->cell(12, $alt, '0', 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($total, 'f'), $bord, 1, "R", 0);
  
  }
  
  if (! isset($total_proc [$v03_descr . " ($v01_proced)"])) {
    $total_proc [$v03_descr . " ($v01_proced)"] [0] = 0;
    $total_proc [$v03_descr . " ($v01_proced)"] [1] = 0;
    $total_proc [$v03_descr . " ($v01_proced)"] [2] = 0;
    $total_proc [$v03_descr . " ($v01_proced)"] [3] = 0;
    $total_proc [$v03_descr . " ($v01_proced)"] [4] = 0;
  }
  
  $total_proc [$v03_descr . " ($v01_proced)"] [0] += $v01_vlrhis;
  $total_proc [$v03_descr . " ($v01_proced)"] [1] += $vlrcor;
  $total_proc [$v03_descr . " ($v01_proced)"] [2] += $vlrjur;
  $total_proc [$v03_descr . " ($v01_proced)"] [3] += $vlrmul;
  $total_proc [$v03_descr . " ($v01_proced)"] [4] += 0;
  
  $hist_total += $v01_vlrhis;
  $corr_total += $vlrcor;
  $juro_total += $vlrjur;
  $mult_total += $vlrmul;
  $tota_total += $vlrcor + $vlrjur + $vlrmul;
  $cont ++;

}

db_query("commit");
$pdf->AddPage("L");
$pdf->Image("imagens/files/$logo", 7, 3, 15);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Text($dist, 6, $nomeinst);
$pdf->Text($dist, 10, $ender);
$pdf->Text($dist, 14, $munic . " - " . $uf . " - " . $telef);
$pdf->Text($dist, 18, $email);
$pdf->Text($dist, 22, $url);
$pdf->SetY(40);
$pdf->SetFont('Arial', 'BI', 14);
//$pdf->setleftmargin(100);
$pdf->cell(270, $alt, "Totalizador", 0, 1, "L", 0);
$pdf->ln(5);
$pdf->SetFont('Arial', 'I', 12);
$pdf->cell(90, $alt, "Total histórico:", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($hist_total, 'f'), 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total corrigido:", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($corr_total, 'f'), 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total juros:", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($juro_total, 'f'), 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total multa:", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($mult_total, 'f'), 0, 1, "R", 0);
$pdf->cell(90, $alt, 'Total desconto:', 0, 0, "L", 0);
$pdf->cell(30, $alt, '0', 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total geral:", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($tota_total, 'f'), 0, 1, "R", 0);
$pdf->ln(5);

$pdf->cell(90, $alt, "Total de registros:", 0, 0, "L", 0);
$pdf->cell(30, $alt, ($numrows - $n_reg), 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total de matrículas:", 0, 0, "L", 0);
$pdf->cell(30, $alt, $matrics, 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total de inscrições:", 0, 0, "L", 0);
$pdf->cell(30, $alt, $inscrs, 0, 1, "R", 0);
$pdf->cell(90, $alt, "Total de CGM's:", 0, 0, "L", 0);
$pdf->cell(30, $alt, $cgms, 0, 1, "R", 0);
//$result = db_query("select valor,v03_descr from (select distinct v01_proced,sum(v01_vlrhis) as valor from divida where v01_livro = $v01_livro group by v01_proced) as x inner join proced on v01_proced = v03_codigo");
$numrows = pg_numrows($result);
$matproc = array ();
if ($numrows > 0) {
  for($x = 0; $x < $numrows; $x ++) {
    db_fieldsmemory($result, $x);
    if ($k00_numprearrecant == "" && $k00_numprearrecad == "" && $numpreant == "") {
      continue;
    }
    db_fieldsmemory($result, $x);
    $valor = $v01_vlrhis;
    $matproc ["$v03_descr"] = (isset($matproc ["$v03_descr"]) ? $matproc [$v03_descr] + $valor : $valor);
  }
}
$pdf->ln(5);

$pdf->AddPage("L");

$pdf->SetFont('Arial', 'b', 8);
$pdf->SetFillColor(180);
$pdf->cell(80, $alt, "PROCEDÊNCIA", 0, 0, "L", 1);
$pdf->cell(32, $alt, "VLR HISTÓRICO", 0, 0, "R", 1);
$pdf->cell(32, $alt, "VLR CORRIGIDO", 0, 0, "R", 1);
$pdf->cell(32, $alt, "JUROS", 0, 0, "R", 1);
$pdf->cell(32, $alt, "MULTA", 0, 0, "R", 1);
$pdf->cell(32, $alt, "DESCONTO", 0, 0, "R", 1);
$pdf->cell(32, $alt, "VALOR TOTAL", 0, 0, "R", 1);
$pdf->ln(5);
$pdf->SetFillColor(0);
$pdf->SetFont('Arial', '', 8);

$total_historico = 0;
$total_corrigido = 0;
$total_juros = 0;
$total_multa = 0;
$total_desconto = 0;

//var_dump($total_proc);exit;


//array_multisort($total_proc[0]);
//sort($total_proc);


foreach ( $total_proc as $a => $b [] ) {
  
  $pdf->Cell(80, $alt, $a, 0, 0, "L", 0);
  
  $pdf->cell(32, $alt, db_formatar($total_proc [$a] [0], 'f'), 0, 0, "R", 0);
  $pdf->cell(32, $alt, db_formatar($total_proc [$a] [1], 'f'), 0, 0, "R", 0);
  $pdf->cell(32, $alt, db_formatar($total_proc [$a] [2], 'f'), 0, 0, "R", 0);
  $pdf->cell(32, $alt, db_formatar($total_proc [$a] [3], 'f'), 0, 0, "R", 0);
  $pdf->cell(32, $alt, db_formatar($total_proc [$a] [4], 'f'), 0, 0, "R", 0);
  $pdf->cell(32, $alt, db_formatar($total_proc [$a] [1] + $total_proc [$a] [2] + $total_proc [$a] [3] - $total_proc [$a] [4], 'f'), 0, 0, "R", 0);
  $pdf->ln();
  
  $total_historico += $total_proc [$a] [0];
  $total_corrigido += $total_proc [$a] [1];
  $total_juros += $total_proc [$a] [2];
  $total_multa += $total_proc [$a] [3];
  $total_desconto += $total_proc [$a] [4];

}
$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(80, $alt, "TOTAL GERAL:", 0, 0, "L", 0);
$pdf->cell(32, $alt, db_formatar($total_historico, 'f'), 0, 0, "R", 0);
$pdf->cell(32, $alt, db_formatar($total_corrigido, 'f'), 0, 0, "R", 0);
$pdf->cell(32, $alt, db_formatar($total_juros, 'f'), 0, 0, "R", 0);
$pdf->cell(32, $alt, db_formatar($total_multa, 'f'), 0, 0, "R", 0);
$pdf->cell(32, $alt, db_formatar($total_desconto, 'f'), 0, 0, "R", 0);
$pdf->cell(32, $alt, db_formatar($total_corrigido + $total_juros + $total_multa, 'f'), 0, 0, "R", 0);
$pdf->ln();
$pdf->ln(5);

/*
if (count($matproc) > 0) {
	for ($x = 0; $x < count($matproc); $x ++) {
	   $pdf->cell(90, $alt, "Total Procedência ".key($matproc).":", 0, 0, "L", 0);
	   $pdf->cell(30, $alt, db_formatar($matproc[key($matproc)], 'f'), 0, 1, "R", 0);
	   next($matproc);
	}
}
*/

// TERMO DE ENCERRAMENTO


$pdf->AddPage("L");
$pdf->Image("imagens/files/$logo", 7, 3, 15);
$pdf->SetFont('Arial', 'BI', $TamFonteNome);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Text($dist, 6, $nomeinst);
$pdf->Text($dist, 10, $ender);
$pdf->Text($dist, 14, $munic . " - " . $uf . " - " . $telef);
$pdf->Text($dist, 18, $email);
$pdf->Text($dist, 22, $url);
//$pdf->Text(230,18,"Data de Correção: ".db_formatar($datacorr,'d')."");	
$pdf->SetY(25);
$pdf->setleftmargin(10);
$pdf->setfillcolor(255);
$pdf->SetFont('Arial', 'B', 12);

$pdf->ln(7);
$pdf->Cell(280, 7, "INSCRIÇÃO DA DÍVIDA ATIVA", 0, 1, "C", 1);
$pdf->Cell(280, 7, "LIVRO $livro", 0, 1, "C", 1);
$pdf->Cell(280, 7, "EXERCÍCIO $exercicio", 0, 1, "C", 1);
$pdf->Cell(280, 7, "TERMO DE ENCERRAMENTO", 0, 1, "C", 1);

$pdf->ln(7);
$x = $pdf->getX();

$xx = db_query($cldb_docparag->sql_query(null, null, "*", null, "db03_tipodoc = 31"));
if (pg_num_rows($xx) == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Verifique configuração do documento. [TIPODOC-31].");
  exit();
}

$objteste = new libdocumento(31);
$objteste->getParagrafos();
$parag = $objteste->aParagrafos;
$pdf->SetFont('Arial', '', 10);
foreach ( $parag as $chave ) {
  // echo "<br> valor =".$chave->db02_texto."<br>\n";
  if ($chave->db02_alinha == 1) {
    $alinhamento = "J";
  } elseif ($chave->db02_alinha == 2) {
    $alinhamento = "C";
  } elseif ($chave->db02_alinha == 3) {
    $alinhamento = "R";
  } elseif ($chave->db02_alinha == 4) {
    $alinhamento = "L";
  } else {
    $alinhamento = "J";
  }
  
  $pdf->MultiCell(280, 6, "        " . $objteste->geratexto($chave->db02_texto), 0, $alinhamento);
  $pdf->cell(280, $alt, "", 0, 1, "C", 1);
}

//include("fpdf151/geraarquivo.php");
//$pdf->output("/tmp/livro.pdf");


$pdf->output();
?>