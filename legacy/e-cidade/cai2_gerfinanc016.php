<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("classes/db_cgm_classe.php"));

$clcgm    = new cl_cgm;
$clrotulo = new rotulocampo;
$clrotulo->label("t64_class"); //classificação

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($matric)){
	$from = "arrematric";
}else if(isset($inscr)){
	$from = "arreinscr";
}else{
	$from = "arrenumcgm";
}

$sql = "select distinct p.k00_numpre                 as numpre,
               p.k00_numpar                          as par,
               p.k00_numtot                          as tot,
               p.k00_dtvenc                          as venc,
               p.k00_hist                            as hist,
               p.k00_receit                          as rec,
               q.k00_numcgm                          as numcgm1,
               p.k00_valor                           as valor,
               k02_drecei                            as descrec,
               k01_descr                             as deschis,
               0                                     as k00_conta,
               null                                  as k00_dtpaga,
               cancdebitosreg.k21_obs||' - '||cancdebitosproc.k23_obs as histtxt,
               k23_data as dtlhist,
               k23_hora as hrlhist,
               login        as usuario  ,
               cancdebitosreg.k21_codigo                            
        from $from o
         inner join arrenumcgm q               on q.k00_numpre                          = o.k00_numpre
				 inner join arrecant p                 on p.k00_numpre                          = o.k00_numpre
				 inner join arreinstit                 on arreinstit.k00_numpre                 = p.k00_numpre 
						                                      and arreinstit.k00_instit                 =	".db_getsession('DB_instit')."
                 left join cancdebitosreg     on cancdebitosreg.k21_numpre             = p.k00_numpre 
                                                  and cancdebitosreg.k21_numpar         = p.k00_numpar
                 left join cancdebitosprocreg on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia
				         inner join cancdebitosproc    on cancdebitosprocreg.k24_codigo         = cancdebitosproc.k23_codigo
				         left  join arreprescr         on arreprescr.k30_numpre                 = p.k00_numpre 
				                                          and arreprescr.k30_anulado is false
              	 left outer join arrepaga a    on p.k00_numpre                          = a.k00_numpre 
                                              and p.k00_numpar                          = a.k00_numpar
						                                  and p.k00_receit                          = a.k00_receit
                 left join arrehist aa         on p.k00_numpre                          = aa.k00_numpre 
                                                  and (p.k00_numpar                     = aa.k00_numpar or aa.k00_numpar = 0)
                 left outer join db_usuarios   on id_usuario                            = cancdebitosproc.k23_usuario  
                 inner join tabrec             on p.k00_receit                          = k02_codigo
                 inner join tabrecjm           on tabrecjm.k02_codjm                    = tabrec.k02_codjm
                 inner join histcalc           on p.k00_hist                            = k01_codigo 
                 
                 "; 

if(isset($numcgm)) {
  $sql = $sql . " where o.k00_numcgm = ".$numcgm;
}else if(isset($matric)){
  $sql = $sql . " where k00_matric = ".$matric;
}else if(isset($inscr)){
  $sql = $sql . " where k00_inscr = ".$inscr;
} else {
  $sql = $sql . " where p.k00_numpre = ".$numpre;
}

$sql .= " and arreprescr.k30_numpre is null ";

if(isset($receita)){
  $sql .= " and p.k00_receit = ".$receita;
}

if(isset($datainicial) || isset($dataini) ){
  $sql .= " and cancdebitosproc.k23_data between '$dataini' and '$datafim' ";
}
	  
$sql = $sql . " and a.k00_numpre is null 
                order by p.k00_numpre,
                         p.k00_numpar";
if(!isset($numcgm) && !isset($matric) && !isset($inscr) && !isset($numpre)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Sem parâmetros para impressão");
}
$result  = db_query($sql);
$numrows = pg_numrows($result);

if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

db_fieldsmemory($result,0);
$sSqlCgm    = $clcgm->sql_query_file($numcgm1,"z01_nome, z01_ender, z01_munic, z01_uf, z01_cgccpf, z01_ident, z01_numero");
$result_cgm = $clcgm->sql_record($sSqlCgm);
if ($clcgm->numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=CGM não encontrado");
} else {
  
  db_fieldsmemory($result_cgm,0);
  $head2 = "CANCELAMENTOS EFETUADOS";
  if(isset($dataini)){
    $head3 = "Período entre ".db_formatar($dataini,'d')." e ".db_formatar($datafim,'d');
  }
  $head5 = $numcgm1." - ".$z01_nome;
  $head6 = $z01_ender.", ".$z01_numero;
  $head7 = $z01_munic." / ".$z01_uf;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(215);
$total = 0;
$troca = 1;
$p     = 1;
$alt   = 4;

for ($cont = 0; $cont < $numrows; $cont++){
  
  db_fieldsmemory($result,$cont);
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    
    $pdf->cell(15,$alt,"NUMPRE",     1, 0, "C", 1);
    $pdf->cell(15,$alt,"PAR.",       1, 0, "C", 1);
    $pdf->cell(15,$alt,"TOT.",       1, 0, "C", 1);
    $pdf->cell(15,$alt,"VENC.",      1, 0, "C", 1);
    $pdf->cell(20,$alt,"CANCEL.",     1, 0, "C", 1);
    $pdf->cell(15,$alt,"HIST.",      1, 0, "C", 1);
    $pdf->cell(60,$alt,"DESCRIÇÃO",  1, 0, "C", 1);
    $pdf->cell(15,$alt,"REC.",       1, 0, "C", 1);
    $pdf->cell(60,$alt,"DESCRIÇÃO",  1, 0, "C", 1);	
    $pdf->cell(15,$alt,"VALOR",      1, 0, "C", 1);
    $pdf->cell(35,$alt,"USUÁRIO",    1, 1, "C", 1);
    $pdf->cell(280,$alt,"HISTÓRICO", 1, 1, "C", 1);
    $pdf->cell(280,1,"",             0, 1, "C", 0);
    
    $troca = 0;
  }
  $pdf->setfont('arial','',6);
	if($cont % 2 == 0){
    $corfundo = 236;
  }else{
    $corfundo = 245;
  }
  $pdf->SetFillColor($corfundo);

  $pdf->cell(15, $alt, $numpre,                      "0", 0, "C", 1);
  $pdf->cell(15, $alt, $par,                         "0", 0, "C", 1);
  $pdf->cell(15, $alt, $tot,                         "0", 0, "C", 1);
  $pdf->cell(15, $alt, db_formatar($venc,"d"),       "0", 0, "C", 1);
  $pdf->cell(20, $alt, $k21_codigo,                  "0", 0, "C", 1);
  $pdf->cell(15, $alt, $hist,                        "0", 0, "C", 1);
  $pdf->cell(60, $alt, $deschis,                     "0", 0, "L", 1);
  $pdf->cell(15, $alt, $rec,                         "0", 0, "C", 1);
  $pdf->cell(60, $alt, $descrec,                     "0", 0, "L", 1);    
  $pdf->cell(15, $alt, db_formatar(($valor*-1),"f"), "0", 0, "R", 1);
  $pdf->cell(35, $alt, $usuario,                     "0", 1, "L", 1);
  $pdf->multicell(280,$alt,db_formatar($dtlhist,"d") . " (" .$hrlhist. ") - " . $histtxt,0,"J",1);
  $total += ($valor*-1);
}

$pdf->setfont('arial','b',8);
$pdf->cell(210, $alt, 'TOTAL PAGO',            "T", 0, "L", 0);
$pdf->cell(35,  $alt, db_formatar($total,"f"), "T", 0, "R", 0);
$pdf->cell(35,  $alt, "",                      "T", 0, "L", 0);
$pdf->Output();
?>