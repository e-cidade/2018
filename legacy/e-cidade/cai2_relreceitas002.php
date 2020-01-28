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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$head3 = "RELATÓRIO DE RECEITAS PAGAS";
if ( $codrec == '') {
  
   $head4 = 'TODAS AS RECEITAS';
   $ordem = ' order by g.k02_codigo, f.k00_dtpaga, f.k00_numpre ';
   $where = '';
} else {
  
   $sql    = "select upper(k02_drecei) as k07_descr from tabrec where k02_codigo = $codrec";
   $result = db_query($sql);
   $head4  = $codrec.' - '.pg_result($result,0,'k07_descr'); 
   $ordem  = ' order by g.k02_codigo, f.k00_dtpaga, e.z01_nome ';
   $where  = ' f.k00_receit = '.$codrec.' and '; 
}
$head6 = "Período : ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');

if ($estrutural != '') {

  $xopcao  = " inner join taborc  a  on a.k02_codigo = g.k02_codigo ";  
  $xopcao .= " inner join receita b  on b.o08_codigo = a.k02_estorc ";
  $where   = ' b.o08_reduz = '.$estrutural.' and ' ;
  $head6   = 'Reduzido : '.$estrutural;
  $head8   = "Período : ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');
} else {
 $xopcao = '';
}
if ($tipo == 'o') {
  
  $head4   = 'RECEITAS ORÇAMENTÁRIAS';
  $xxtipo  = " inner join taborc     on taborc.k02_codigo     = g.k02_codigo "; 
  $xxtipo .= "                      and taborc.k02_anousu     = ".db_getsession("DB_anousu");
  $xxtipo .= " inner join orcreceita on orcreceita.o70_anousu = taborc.k02_anousu ";
  $xxtipo .= "                      and orcreceita.o70_codrec = taborc.k02_codrec ";
  $xxtipo .= "                      and orcreceita.o70_instit = ". db_getsession("DB_instit");
  
} elseif ($tipo == 'e') {
  
  $head4   = 'RECEITAS EXTRA-ORÇAMENTÁRIAS';
  $xxtipo  = " inner join tabplan p     on p.k02_codigo = g.k02_codigo "; 
  $xxtipo .= "                         and p.k02_anousu =".db_getsession("DB_anousu");
  $xxtipo .= " inner join conplanoreduz on conplanoreduz.c61_anousu = p.k02_anousu ";
  $xxtipo .= "                         and conplanoreduz.c61_reduz  = p.k02_reduz ";
  $xxtipo .= "                         and conplanoreduz.c61_instit = ". db_getsession("DB_instit");
  
} else {
  
  $xxtipo  = " left join taborc         on taborc.k02_codigo         = g.k02_codigo "; 
  $xxtipo .= "                         and taborc.k02_anousu         = ".db_getsession("DB_anousu");
  $xxtipo .= " left join orcreceita     on orcreceita.o70_anousu    = taborc.k02_anousu ";
  $xxtipo .= "                         and orcreceita.o70_codrec    = taborc.k02_codrec ";
  $xxtipo .= " left join tabplan p      on p.k02_codigo             = g.k02_codigo ";
  $xxtipo .= "                         and p.k02_anousu             = ".db_getsession("DB_anousu");
  $xxtipo .= " left join conplanoreduz  on conplanoreduz.c61_anousu = p.k02_anousu ";
  $xxtipo .= "                         and conplanoreduz.c61_reduz  = p.k02_reduz ";
  
  
  $where .= "  case when o70_instit is null then conplanoreduz.c61_instit = ". db_getsession("DB_instit");
  $where .= "       else o70_instit =  " . db_getsession("DB_instit") . " end ";
  $where .= " and ";
  
}



if ($agrupar == 't') {
  
  $sql  ="    select  g.k02_codigo,                                              ";
  $sql .="            g.k02_drecei,                                              ";
  $sql .="            sum(f.k00_valor) as valor                                  ";
  $sql .="            from arrepaga f                                            ";
  $sql .="            inner join tabrec g    on g.k02_codigo  = f.k00_receit      ";
  $sql .="            $xopcao                                                    ";
  $sql .="            $xxtipo                                                    ";
  $sql .="      where $where                                                     ";
  $sql .="            k00_dtpaga between '$datai' and '$dataf'                   ";
  $sql .="   group by g.k02_codigo,                                              ";
  $sql .="            g.k02_drecei                                               ";
  $sql .="   order by g.k02_codigo                                               ";
} else {
  $sql  ="    select  g.k02_codigo,                                                                        ";
  $sql .="            g.k02_drecei,                                                                        ";
  $sql .="            f.k00_numpre,                                                                        ";
  $sql .="            e.z01_nome,                                                                          ";
  $sql .="             f.k00_dtpaga,                                                                        ";
  $sql .="            coalesce(                                                                            ";
  $sql .="              (select nullif(trim(array_to_string(array_accum(matinscr),', ')), '')              ";
  $sql .="                 from (select k00_numpre,'M-'||k00_matric as matinscr                            ";
  $sql .="                         from arrematric m                                                       ";
  $sql .="                        where m.k00_numpre = f.k00_numpre                                        ";
  $sql .="                        union all                                                                ";
  $sql .="                       select k00_numpre,'I-'||k00_inscr as matinscr                             ";
  $sql .="                         from arreinscr i                                                        ";
  $sql .="                        where i.k00_numpre = f.k00_numpre) as numpre), '0') as matinscr,         ";
  $sql .="            (select d.k00_histtxt                                                                ";
  $sql .="               from arrehist d                                                                   ";
  $sql .="              where d.k00_numpre = f.k00_numpre                                                  ";
  $sql .="              order by d.k00_dtoper desc, d.k00_hora desc                                        ";
  $sql .="              limit 1) as k00_histtxt,                                                           ";


  $sql .="            sum(f.k00_valor) as valor                                                            ";
  $sql .="            from arrepaga f                                                                      ";
  $sql .="                 inner join tabrec g    on g.k02_codigo  = f.k00_receit                          ";
  $sql .="                $xopcao                                                                          ";
  $sql .="                $xxtipo                                                                          ";
  $sql .="                 inner join cgm e                on f.k00_numcgm = e.z01_numcgm                  ";
  $sql .="    where $where                                                                                 ";
  $sql .="          k00_dtpaga between '$datai' and '$dataf'                                               ";
  $sql .=" group by g.k02_codigo,                                                                          ";
  $sql .="           g.k02_drecei,                                                                         ";
  $sql .="           f.k00_numpre,                                                                         ";
  $sql .="           e.z01_nome,                                                                           ";
  $sql .="           k00_dtpaga,                                                                           ";
  $sql .="           matinscr,                                                                             ";
  $sql .="           k00_histtxt                                                                            ";
  $sql .="   $ordem                                                                                        ";
}
// echo $sql;exit;

$result = db_query($sql);
$xxnum  = pg_numrows($result);
if ($xxnum == 0) {
  
  $sMsgErro  = "db_erros.php?fechar=true&db_erro=Não existem lançamentos para a receita ";
  $sMsgErro .= "{$codrec} no período de ". db_formatar($datai,'d'). " a " . db_formatar($dataf,'d');
  db_redireciona($sMsgErro);
}
$linha       = 0;
$pre         = 0;
$total_taxa  = 0;
$total_geral = 0;
$pagina = 0;
if ($agrupar == 't') {
 
  $pdf->ln(2);
  $pdf->AddPage(); 
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(220);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
  $pdf->Cell(100,6,"RECEITA",1,0,"C",1);
  $pdf->Cell(25,6,"VALOR",1,1,"C",1);
  $pdf->SetFont('Arial','B',9);
  for ($i = 0; $i < $xxnum; $i++) {
    
    db_fieldsmemory($result,$i);
    if ($pdf->gety() > $pdf->h - 30 ) {
      
       $pdf->addpage();
       $pdf->SetFont('Arial','B',9);
       $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
       $pdf->Cell(100,6,"RECEITA",1,0,"C",1);
       $pdf->Cell(25,6,"VALOR",1,1,"C",1);
    }
    $pdf->setfont('arial','',7);
    $pdf->cell(20,4,$k02_codigo,1,0,"C",$pre);
    $pdf->cell(100,4,strtoupper($k02_drecei),1,0,"L",$pre);
    $pdf->cell(25,4,db_formatar($valor,'f'),1,1,"R",$pre);
    $total_geral +=$valor;
  }
  $pdf->cell(120,4,"TOTAL GERAL",1,0,"C",0);
  $pdf->cell(25,4,db_formatar($total_geral,'f'),1,1,"R",0);

} else {
   
  $pdf->ln(2);
  $pdf->AddPage('L'); 
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(220);
  $pdf->SetFont('Arial','B',7);
  $receita = trim(pg_result($result,0,'k02_codigo')).' - '.trim(strtoupper(pg_result($result,0,'k02_drecei')));
  $pdf->multicell(0,6,$receita,0,"L",0);
  $pdf->Cell(12,6,"NUMPRE",1,0,"C",1);
  $pdf->Cell(60,6,"NOME",1,0,"C",1);
  $pdf->Cell(15,6,"PAGTO",1,0,"C",1);
  $pdf->Cell(32,6,"MAT/INSC",1,0,"C",1);
  $pdf->Cell(130,6,"HISTORICO",1,0,"C",1);
  $pdf->Cell(20,6,"VALOR",1,1,"C",1);
  $pdf->SetFont('Arial','B',9);
  
  for( $i = 0; $i < $xxnum; $i++) {
    
    db_fieldsmemory($result,$i);
    if ($pdf->gety() > $pdf->h - 30 ) {
      
      $pdf->AddPage('L');
      $pdf->SetFont('Arial','B',7);
      $pdf->multicell(0,6,$k02_codigo.' - '.strtoupper($k02_drecei),0,"L",0);
      $pdf->Cell(12,6,"NUMPRE",1,0,"C",1);
      $pdf->Cell(60,6,"NOME",1,0,"C",1);
      $pdf->Cell(15,6,"PAGTO",1,0,"C",1);
      $pdf->Cell(32,6,"MAT/INSC",1,0,"C",1);
      $pdf->Cell(130,6,"HISTORICO",1,0,"C",1);
      $pdf->Cell(20,6,"VALOR",1,1,"C",1);
    }
    if ( $receita != $k02_codigo.' - '.strtoupper($k02_drecei) ) {
  //      $pdf->AddPage('L');
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(249,6,"TOTAL DA RECEITA : ",1,0,"L",0);
        $pdf->Cell(20,6,db_formatar($total_taxa,'f'),1,1,"R",0);
        $total_taxa = 0;
        $pdf->SetFont('Arial','B',7);
        $pdf->ln(3);
        $pdf->multicell(0,6,$k02_codigo.' - '.strtoupper($k02_drecei),0,"L",0);
        $pdf->Cell(12,6,"NUMPRE",1,0,"C",1);
        $pdf->Cell(60,6,"NOME",1,0,"C",1);
        $pdf->Cell(15,6,"PAGTO",1,0,"C",1);
        $pdf->Cell(32,6,"MAT/INSC",1,0,"C",1);
        $pdf->Cell(130,6,"HISTORICO",1,0,"C",1);
        $pdf->Cell(20,6,"VALOR",1,1,"C",1);
    }
    $pdf->SetFont('Arial','',7);
    $pdf->cell(12,4,$k00_numpre,1,0,"R",$pre);
    $pdf->cell(60,4,substr($z01_nome,0,35),1,0,"L",$pre);
    $pdf->Cell(15,4,db_formatar($k00_dtpaga,'d'),1,0,"C",$pre);
    $pdf->cell(32,4,$matinscr,1,0,"L",$pre);
    $pdf->cell(130,4,substr(strtoupper($k00_histtxt),0,80),1,0,"L",$pre);
    $pdf->cell(20,4,db_formatar($valor,'f'),1,1,"R",$pre);
    $total_geral += $valor;
    $total_taxa += $valor;
    $receita = $k02_codigo.' - '.strtoupper($k02_drecei);
  }
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(249,6,"TOTAL DA RECEITA : ",1,0,"L",0);
  $pdf->Cell(20,6,db_formatar($total_taxa,'f'),1,1,"R",0);
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(249,6,"TOTAL GERAL : ",1,0,"L",0);
  $pdf->Cell(20,6,db_formatar($total_geral,'f'),1,1,"R",0);
}

$pdf->Output();

?>