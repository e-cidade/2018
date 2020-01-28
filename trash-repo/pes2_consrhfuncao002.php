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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_rhfuncao_classe.php");
include("classes/db_rhregime_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$clrhfuncao = new cl_rhfuncao;
$clrhregime = new cl_rhregime;

if(!isset($ano)){
  $ano = db_anofolha();
}
if(!isset($mes)){
  $mes = db_mesfolha();
}

$where = " ";
if($colunas1!=""){
   $where = " and rh30_codreg in (".$colunas1.") ";
}

$titulorel = "TODOS OS CARGOS";
        $sql1 = "select funcao as rh37_funcao,
                        rh37_descr,
                        rh37_vagas,
                        sum(ocupados)               as ocupados,
                        sum(tot_ativos)             as tot_ativos, 
                        sum(tot_inativos)           as tot_inativos,
                        sum(tot_pensionistas)       as tot_pensionistas,
                        rh37_vagas - sum(ocupados)  as saldo
                   from ( select rh37_funcao as funcao,
                                 rh37_descr,
                                 rh37_vagas,
                                 count(rh01_regist) as ocupados,
                                 sum(case when rh30_vinculo = 'A' then 1 else 0 end) as tot_ativos,
                                 sum(case when rh30_vinculo = 'I' then 1 else 0 end) as tot_inativos,
                                 sum(case when rh30_vinculo = 'P' then 1 else 0 end) as tot_pensionistas
                           from rhfuncao 
                           inner join rhpessoalmov  on rhpessoalmov.rh02_funcao  = rhfuncao.rh37_funcao
                                                   and rhpessoalmov.rh02_anousu  = $ano
                                                   and rhpessoalmov.rh02_mesusu  = $mes
                                                   and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."
                           inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist 
                           left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                           inner join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
                                                   and rhregime.rh30_instit      = rhpessoalmov.rh02_instit 
                           inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm 
                           inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                   and rhlota.r70_instit         = rhpessoalmov.rh02_instit 
                           where rh37_instit = ".db_getsession("DB_instit")."
                    $where
                and rh05_seqpes is null
              group by rh37_funcao,
                       rh37_descr,
                       rh30_vinculo,
                       rh37_vagas
              order by rh37_funcao) as x 
              group by rh37_funcao,
                       rh37_descr,
                       rh37_vagas
              order by funcao";	

$result_funcoes=  pg_query($sql1);
if (pg_numrows($result_funcoes) == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum cargo encontrado");
}

$result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_vinculo","", " rh30_instit = ".db_getsession('DB_instit')." and rh30_codreg in (".$colunas1.")"));
$colunas = "";    
$virgula = "";
for($x = 0; $x < $clrhregime->numrows; $x ++) {
  db_fieldsmemory($result_regime, $x);
  $colunas .= $virgula.strtolower($rh30_vinculo);
  $virgula = ",";
}

$head3 = "CARGOS";
$head5 = $titulorel;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$totalt = 0;
$valort = 0;
$quantt = 0;
$troca = 1;
$p = 1;
$alt = 4;
$totalvaga = 0;
$totalocup = 0;
$totalativ = 0;
$totalinat = 0;
$totalpens = 0;
$totalsald = 0;
$totalfunc = 0;

for($x = 0; $x < pg_numrows($result_funcoes); $x ++) {
  db_fieldsmemory($result_funcoes, $x);

  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);

    $pdf->cell(20,$alt,"Cargo","TBL",0,"C",1);
    $pdf->cell(60,$alt,"Descrição","TBL",0,"C",1);
    $pdf->cell(30,$alt,"Vagas","TBL",0,"C",1);
    $pdf->cell(15,$alt,"Ativos","TBL",0,"C",1);
    $pdf->cell(15,$alt,"Inativos","TBL",0,"C",1);
    $pdf->cell(20,$alt,"Pensionistas","TBL",0,"C",1);
    $pdf->cell(15,$alt,"Ocupadas","TBL",0,"C",1);
    $pdf->cell(15,$alt,"Saldo"   ,"TBLR",1,"C",1);

    $troca   = 0;
    $pre     = 1;
  }
  if($pre == 0)
    $pre = 1;
  else
    $pre = 0;
  
  $totalvaga += $rh37_vagas;
  $totalocup += $ocupados;
  $totalativ += $tot_ativos;
  $totalinat += $tot_inativos;
  $totalpens += $tot_pensionistas;
  $totalsald += $saldo;
  $totalfunc += 1;

  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$rh37_funcao,0,0,"C",$pre);
  $pdf->cell(60,$alt,$rh37_descr,0,0,"L",$pre);
  $pdf->cell(30,$alt,$rh37_vagas,0,0,"R",$pre);
  $pdf->cell(15,$alt,$tot_ativos,0,0,"R",$pre);
  $pdf->cell(15,$alt,$tot_inativos,0,0,"R",$pre);
  $pdf->cell(20,$alt,$tot_pensionistas,0,0,"R",$pre);
  $pdf->cell(15,$alt,$ocupados,0,0,"R",$pre);
  $pdf->cell(15,$alt,$saldo,0,1,"R",$pre);
}

$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,"TOTAIS DE REGISTROS :","T",0,"R",0);
$pdf->cell(10,$alt,$totalfunc,"T",0,"R",0);

$pdf->cell(60,$alt,$totalvaga,"T",0,"R",0);
$pdf->cell(15,$alt,$totalativ,"T",0,"R",0);
$pdf->cell(15,$alt,$totalinat,"T",0,"R",0);
$pdf->cell(20,$alt,$totalpens,"T",0,"R",0);
$pdf->cell(15,$alt,$totalocup,"T",0,"R",0);
$pdf->cell(15,$alt,$totalsald,"T",1,"R",0);

$pdf->Output();
?>