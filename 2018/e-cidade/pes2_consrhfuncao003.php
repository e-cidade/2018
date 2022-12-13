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

if(isset($funcao) && trim($funcao)!=""){
  $where = " ";
  
  if($colunas1!=""){
    
    $where = " and rh30_codreg in (".$colunas1.") ";
  }
  
  $sql1  = "select rh37_funcao,                                                                              \n";
  $sql1 .= "       rh37_descr,                                                                               \n";
  $sql1 .= "       rh37_vagas,                                                                               \n";
  $sql1 .= "       count(rh01_regist) as ocupados,                                                           \n";
  $sql1 .= "       rh30_vinculo as r01_tpvinc                                                                \n";
  $sql1 .= "  from rhfuncao                                                                                  \n";
  $sql1 .= "       inner join rhpessoalmov  on rhpessoalmov.rh02_funcao  = rhfuncao.rh37_funcao              \n";
  $sql1 .= "                               and rhpessoalmov.rh02_anousu  = {$ano}                            \n";
  $sql1 .= "                               and rhpessoalmov.rh02_mesusu  = {$mes}                            \n";
  $sql1 .= "                               and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."    \n";
  $sql1 .= "       inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist          \n";
  $sql1 .= "       left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes          \n";
  $sql1 .= "       inner join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg          \n";
  $sql1 .= "                               and rhregime.rh30_instit      = rhpessoalmov.rh02_instit          \n";
  $sql1 .= "       inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm             \n";
  $sql1 .= "       inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota            \n";
  $sql1 .= "                               and rhlota.r70_instit         = rhpessoalmov.rh02_instit          \n";
  $sql1 .= " where rh37_funcao = {$funcao}                                                                   \n";
  $sql1 .= "   and rh37_instit = ".db_getsession("DB_instit")."                                              \n";
  $sql1 .= "   and rh05_seqpes is null                                                                       \n";
  $sql1 .= "{$where}                                                                                         \n";
  $sql1 .= " group by rh37_funcao,                                                                           \n";
  $sql1 .= "          rh37_descr,                                                                            \n";
  $sql1 .= "          rh30_vinculo,                                                                          \n";
  $sql1 .= "          rh37_vagas                                                                             \n";
  
  $result_funcao = pg_query($sql1);
  
  if(pg_numrows($result_funcao) == 0){
    db_redireciona("db_erros.php?fechar=true&db_erro=Cargo não encontrado");
  }
  
  db_fieldsmemory($result_funcao,0);
  $ocup = 0;
  $saldo = 0;
  for($i=0;$i<pg_numrows($result_funcao);$i++){
    db_fieldsmemory($result_funcao,$i);                                  
    $ocup += $ocupados;
  }
  if($rh37_vagas != 0){
     $saldo = $rh37_vagas - $ocup;
  }
  $ocupados = $ocup;
}
$where = " ";
if($colunas1!=""){
   $where = " and rh30_codreg in (".$colunas1.")";
}

  $sql1  = "select rh01_regist as r01_regist,                                                                \n";
  $sql1 .= " z01_nome,                                                                                       \n";
  $sql1 .= " rh30_descr,                                                                                     \n";
  $sql1 .= " rh30_codreg,                                                                                    \n";
  $sql1 .= " case when rh30_vinculo='A'                                                                      \n";
  $sql1 .= "      then 'ATIVO'                                                                               \n";
  $sql1 .= "      else case when rh30_vinculo='I'                                                            \n";
  $sql1 .= "                 then 'INATIVO'                                                                  \n";
  $sql1 .= "                 else 'PENSIONISTA'                                                              \n";
  $sql1 .= "      end                                                                                        \n";
  $sql1 .= " end as vinculo,                                                                                 \n";
  $sql1 .= " r70_codigo ,                                                                                    \n";
  $sql1 .= " r70_descr                                                                                       \n";
  $sql1 .= "  from rhfuncao                                                                                  \n";
  $sql1 .= "       inner join rhpessoalmov  on rhpessoalmov.rh02_funcao  = rhfuncao.rh37_funcao              \n";
  $sql1 .= "                               and rhpessoalmov.rh02_anousu  = {$ano}                            \n";
  $sql1 .= "                               and rhpessoalmov.rh02_mesusu  = {$mes}                            \n";
  $sql1 .= "                               and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."    \n";
  $sql1 .= "       inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist          \n";
  $sql1 .= "       left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes          \n";
  $sql1 .= "       inner join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg          \n";
  $sql1 .= "                               and rhregime.rh30_instit      = rhpessoalmov.rh02_instit          \n";
  $sql1 .= "       inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm             \n";
  $sql1 .= "       inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota            \n";
  $sql1 .= "                               and rhlota.r70_instit         = rhpessoalmov.rh02_instit          \n";
  $sql1 .= " where rh37_funcao = {$funcao}                                                                   \n";
  $sql1 .= "   and rh37_instit = ".db_getsession("DB_instit")."                                              \n";
  $sql1 .= "   and rh05_seqpes is null                                                                       \n";
  $sql1 .= "{$where}                                                                                         \n";
  $sql1 .= " order by z01_nome                                                                               \n";

  $result_funcionarios = pg_query($sql1);

$numrows = pg_numrows($result_funcionarios);
if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_vinculo","", " rh30_instit = ".db_getsession('DB_instit')." and rh30_codreg in (".$colunas1.")"));
$colunas = "";    
$virgula = "";
for($x = 0; $x < $clrhregime->numrows; $x ++) {
  db_fieldsmemory($result_regime, $x);
  $colunas .= $virgula.strtolower($rh30_vinculo);
  $virgula = ",";
}

$head2 = "CARGOS";
$head4 = $rh37_funcao." - ".$rh37_descr;
$head6 = "Vagas:        ".$rh37_vagas;
$head7 = "Ocupados:  ".$ocupados;
$head8 = "Saldo:         ".$saldo;


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$totalt = 0;
$troca = 1;
$p = 1;
$alt = 4;
$pre = 1;

for($x = 0; $x < $numrows; $x ++) {
  db_fieldsmemory($result_funcionarios, $x);

  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);

    $pdf->cell(15,$alt,"Registro","TBL",0,"C",1);
    $pdf->cell(50,$alt,"Nome","TBL",0,"C",1);
    $pdf->cell(20,$alt,"Lotação","TBL",0,"C",1);
    $pdf->cell(60,$alt,"Descrição","TBL",0,"C",1);
    $pdf->cell(45,$alt,"Vínculo","TBLR",1,"C",1);

    $troca = 0;
  }
  if($pre == 0)
    $pre = 1;
  else
    $pre = 0;

  $totalt++;  
  $pdf->setfont('arial','',7);

  $pdf->cell(15,$alt,$r01_regist,"T",0,"C",$pre);
  $pdf->cell(50,$alt,$z01_nome,"T",0,"L",$pre);
  $pdf->cell(20,$alt,$r70_codigo,"T",0,"C",$pre);
  $pdf->cell(60,$alt,$r70_descr,"T",0,"L",$pre);
  $pdf->cell(45,$alt,substr($rh30_codreg." - ".$rh30_descr,0,45),"T",1,"L",$pre);
}

$pdf->setfont('arial','b',7);
$pdf->cell(230,$alt,"TOTAL DE REGISTROS  ".$totalt,"T",0,"L",0);
$pdf->Output();
?>