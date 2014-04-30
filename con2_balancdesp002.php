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


include("libs/db_liborcamento.php");

$tipo_mesini = 1;
$tipo_mesfim = 1;

// $tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
// $tipo_agrupa = 1;
// 1 = geral
// 2 = orgao
// 3 = unidade
// $tipo_nivel = 6;
// 1 = funcao
// 2 = subfuncao
// 3 = programa
// 4 = projeto/atividade
// 5 = elemento
// 6 = recurso

$tipo_agrupa = 3;
$tipo_nivel = 6;

$qorgao = 0;
$qunidade = 0;


include("fpdf151/pdf.php");
include("libs/db_sql.php");

include("fpdf151/assinatura.php");
$classinatura = new cl_assinatura;

//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

if ($orgaos == "") {
  db_redireciona('db_erros.php?fechar=true&db_erro=Selecione orgao/unidade!');   
}

$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($opcao == 3)
  $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
  $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}
$head1 = "DEMONSTRATIVO DA DESPESA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev; 
       $flag_abrev  = true;
  } else {
       $descr_inst .= $xvirg.$nomeinst; 
  }

  $xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;



$nivela = substr($vernivel,0,1);
$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
if($nivela >= 1){
  $sele_work .= " and exists (select 1 from t where t.o58_orgao = w.o58_orgao) ";
}
if($nivela >= 2){
  $sele_work .= "  and exists (select 1 from t where t.o58_unidade = w.o58_unidade) ";
}
if($recurso!=0){
  $resrec = pg_exec("select o15_descr from orctiporec where o15_codigo = $recurso");
  $head2 = "Recurso: ".$recurso."-".substr(pg_result($resrec,0,0),0,30);
  $sele_work .= " and o58_codigo = $recurso";
}   
pg_exec("begin");
pg_exec("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");

$xcampos = split("-",$orgaos);
//print_r($xcampos);
for($i=0;$i < sizeof($xcampos);$i++){
  $where = '';
  $virgula = ''; 
  $xxcampos = split("_",$xcampos[$i]);
  for($ii=0;$ii<sizeof($xxcampos);$ii++){
    if($ii > 0){
      $where .= $virgula.$xxcampos[$ii];
      $virgula = ', ';
    }
  }
  if($nivela == 1)
  $where .= ",0,0,0,0,0,0,0";
  if($nivela == 2)
  $where .= ",0,0,0,0,0,0";
  pg_exec("insert into t values($where)");
}
$anousu = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

//die($sele_work);
//db_criatabela(pg_exec("select * from t"));exit;

if ($totaliza == "A") {
  $sqlprinc = db_dotacaosaldo(8,1,4,true,$sele_work,$anousu,$dataini,$datafin, 8, 0, true);
 
} else {
  $sqlprinc = db_dotacaosaldo(2,2,4,true,$sele_work,$anousu,$dataini,$datafin, 8, 0, true);
}

//echo $sqlprinc;
//exit;
// funcao para gerar work
// db_criatabela(pg_exec("select * from work w inner join temporario t on $sele_work "));exit;

pg_exec("commit");

$result = pg_exec($sqlprinc) or die($sqlprinc);
if (pg_num_rows($result) == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado, verifique as datas e tente novamente');   
} 
//db_criatabela($result);
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);

$troca         = 1;
$alt           = 4;
$pagina        = 1;
$xorgao        = 0;
$xunidade      = 0;
$xfuncao       = 0;
$xsubfuncao    = 0;
$xprograma     = 0;
$xprojativ     = 0;
$pagina        = 1;

$totorgaodot_ini                = 0;
$totorgaosuplementado_acumulado = 0;
$totorgaosuplemen_acumulado			= 0;
$totorgaoespecial_acumulado			= 0;
$totorgaoreduzido_acumulado     = 0;
$totorgaoatual                  = 0;
$totorgaoempenhado              = 0;
$totorgaoanulado                = 0;
$totorgaoliquidado              = 0;
$totorgaopago                   = 0;
$totorgaoatual_a_pagar          = 0;
$totorgaoempenhado_acumulado    = 0;
$totorgaoanulado_acumulado      = 0;
$totorgaoliquidado_acumulado    = 0;
$totorgaopago_acumulado         = 0;
$totorgaoatual_a_pagar_liquidado= 0;      

$totgeraldot_ini                 = 0; 
$totgeralsuplementado_acumulado  = 0;
$totgeralsuplemen_acumulado			 = 0;
$totgeralespecial_acumulado			 = 0;
$totgeralreduzido_acumulado      = 0;
$totgeralatual                   = 0;
$totgeralempenhado               = 0;
$totgeralanulado                 = 0;
$totgeralliquidado               = 0;
$totgeralpago                    = 0;
$totgeralatual_a_pagar           = 0;
$totgeralempenhado_acumulado     = 0;
$totgeralanulado_acumulado       = 0;
$totgeralliquidado_acumulado     = 0;
$totgeralpago_acumulado          = 0;
$totgeralatual_a_pagar_liquidado = 0; 

$quebra_orgao = 'S';

if($nivela == 2)
$quebra_unidade = 'S';
else
$quebra_unidade = 'N';
//////////// ANALÍTICO //////////////

if($totaliza == "A") {
  
  $orguniant = "";
  
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result,0);
    $orguniant = db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'unidade');
  }
  
  for($i=0;$i<pg_numrows($result);$i++) {
    db_fieldsmemory($result,$i);
    
    if(($xunidade != $o58_orgao.$o58_unidade) && ($quebra_unidade == 'S') && ($pagina != 1) ){
      $pdf->setfont('arial','b',7);
      $xunidade = $o58_orgao.$o58_unidade;
      if($pdf->gety() > $pdf->h-40) {
        
        $pdf->addpage();
        
        $pdf->cell(0,0.5,'',"TB",1,"C",0);
        $pdf->cell(10,$alt,"ÓRGÃO  -  ".db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
        
        if($nivela ==1){
          $pdf->cell(0,0.5,'',"TB",1,"C",0);
        }
        
        if($nivela==2){
          $pdf->cell(10,$alt,"UNIDADE ORÇAMENTÁRIA  -  ".db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')."  -  ".$o41_descr,0,1,"L",0); 
          $pdf->cell(0,0.5,'',"TB",1,"C",0);
        }
        
        $pdf->ln(2);
        $pdf->cell(10,$alt,"",0,0,"C",0);
        $pdf->cell(30,$alt,"SALDO INICIAL",0,0,"R",0);
        $pdf->cell(30,$alt,"SUPLEMENTAÇÕES",0,0,"R",0);
        $pdf->cell(30,$alt,"CRED. ESPECIAIS",0,0,"R",0);
        $pdf->cell(30,$alt,"REDUÇÕES",0,0,"R",0);
        $pdf->cell(30,$alt,"TOTAL CRÉDITOS",0,0,"R",0);
        $pdf->cell(30,$alt,"SALDO DISPONÍVEL",0,1,"R",0);
        
        $pdf->cell(10,$alt,"REDUZ",0,0,"L",0);
        
        $pdf->cell(30,$alt,"EMPENHADO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"ANULADO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"EMP LIQUIDO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"LIQUIDADO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"PAGO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"A LIQUIDAR",0,1,"R",0);
        
        $pdf->cell(10,$alt,"",0,0,"L",0);
        
        $pdf->cell(30,$alt,"EMPENHADO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"ANULADO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"EMP LIQUIDO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"LIQUIDADO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"PAGO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"A PAGAR LIQUIDADO",0,1,"R",0);
        
        $pdf->cell(0,$alt,'',"T",1,"C",0);
        $pdf->setfont('arial','',7);
        
      }
      
      $pdf->ln(3);
      $pdf->cell(10,$alt,'UNIDADE',0,0,"L",0,'.');
      
      $sqltot = "	select 
      dot_ini as totdot_ini, 
      suplemen_acumulado as totsuplemen_acumulado,
      especial_acumulado as totespecial_acumulado,
      reduzido_acumulado as totreduzido_acumulado,
      suplementado_acumulado as totsuplementado_acumulado,
      empenhado as totempenhado,
      anulado as totanulado,
      liquidado as totliquidado,
      pago as totpago,
      atual_a_pagar as totatual_a_pagar,
      empenhado_acumulado as totempenhado_acumulado,
      anulado_acumulado as totanulado_acumulado,
      liquidado_acumulado as totliquidado_acumulado,
      pago_acumulado as totpago_acumulado
      from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2) . " and o58_unidade = " . substr($orguniant,2,2) . " and o58_funcao = 0";
      $resulttot = pg_exec($sqltot) or die($sqltot);
      db_fieldsmemory($resulttot, 0);
      
      $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado  - $totreduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
     
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
      $pdf->setfont('arial','',7);
      
    }
    
    if( $xorgao != $o58_orgao && $pagina != 1 ){
      $pdf->setfont('arial','b',7);
      $xorgao = $o58_orgao;
      $pagina = 1;
      if($pdf->gety() > $pdf->h-40){
        
        $pdf->addpage();
        $pdf->cell(0,0.5,'',"TB",1,"C",0);
        $pdf->cell(10,$alt,"ÓRGÃO  -  ".db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
        
        if($nivela ==1){
          $pdf->cell(0,0.5,'',"TB",1,"C",0);
        }
        
        if($nivela==2){
          $pdf->cell(10,$alt,"UNIDADE ORÇAMENTÁRIA  -  ".db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')."  -  ".$o41_descr,0,1,"L",0); 
          $pdf->cell(0,0.5,'',"TB",1,"C",0);
        }
        $pdf->ln(2);
        $pdf->cell(10,$alt,"",0,0,"C",0);
        $pdf->cell(30,$alt,"SALDO INICIAL",0,0,"R",0);
        $pdf->cell(30,$alt,"SUPLEMENTAÇÕES",0,0,"R",0);
        $pdf->cell(30,$alt,"CRED. ESPECIAIS",0,0,"R",0);
        $pdf->cell(30,$alt,"REDUÇÕES",0,0,"R",0);
        $pdf->cell(30,$alt,"TOTAL CRÉDITOS",0,0,"R",0);
        $pdf->cell(30,$alt,"SALDO DISPONÍVEL",0,1,"R",0);
        
        $pdf->cell(10,$alt,"REDUZ",0,0,"L",0);
        
        $pdf->cell(30,$alt,"EMPENHADO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"ANULADO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"EMP LIQUIDO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"LIQUIDADO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"PAGO NO MÊS",0,0,"R",0);
        $pdf->cell(30,$alt,"A LIQUIDAR",0,1,"R",0);
        
        $pdf->cell(10,$alt,"",0,0,"L",0);
        
        $pdf->cell(30,$alt,"EMPENHADO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"ANULADO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"EMP LIQUIDO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"LIQUIDADO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"PAGO NO ANO",0,0,"R",0);
        $pdf->cell(30,$alt,"A PAGAR LIQUIDADO",0,1,"R",0);
        
        $pdf->cell(0,$alt,'',"T",1,"C",0);
        $pdf->setfont('arial','',7);
        
      }
      
      $pdf->ln(3);
      $pdf->cell(10,$alt,'ORGÃO',0,0,"L",0,'.');
      
      $sqltot = "	select 
      dot_ini as totdot_ini, 
      suplemen_acumulado as totsuplemen_acumulado,
      especial_acumulado as totespecial_acumulado,
      reduzido_acumulado as totreduzido_acumulado,
      suplementado_acumulado as totsuplementado_acumulado,
      empenhado as totempenhado,
      anulado as totanulado,
      liquidado as totliquidado,
      pago as totpago,
      atual_a_pagar as totatual_a_pagar,
      empenhado_acumulado as totempenhado_acumulado,
      anulado_acumulado as totanulado_acumulado,
      liquidado_acumulado as totliquidado_acumulado,
      pago_acumulado as totpago_acumulado
      from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2) . " and o58_unidade = 0";
      $resulttot = pg_exec($sqltot) or die($sqltot);
      db_fieldsmemory($resulttot, 0);
      
      $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
  //    echo "> dotacao inicial:$totdot_ini,sup_acumu:$totsuplementado_acumulado,reduz_acum:$totreduzido_acumulado,Emp_acum:$totempenhado_acumulado,Anulado:$totanulado_acumulado.<br>";
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
      $pdf->setfont('arial','',7);
      
    }
    
    if($nivela == 2 and $o58_unidade == 0) {
      continue;
    }
    
    if($pdf->gety() > $pdf->h-40 || $pagina == 1) {
      
      $pagina = 0;
      
      $pdf->addpage();
      $pdf->setfont('arial','b',7);
      $pdf->cell(0,0.5,'',"TB",1,"C",0);
      $pdf->cell(10,$alt,"ÓRGÃO  -  ".db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
      
      if($nivela ==1){
        $pdf->cell(0,0.5,'',"TB",1,"C",0);
      }
      
      if($nivela==2){
        $pdf->cell(10,$alt,"UNIDADE ORÇAMENTÁRIA  -  ".db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')."  -  ".$o41_descr,0,1,"L",0); 
        $pdf->cell(0,0.5,'',"TB",1,"C",0);
      }
      
      $pdf->ln(2);
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(30,$alt,"SALDO INICIAL",0,0,"R",0);
      $pdf->cell(30,$alt,"SUPLEMENTAÇÕES",0,0,"R",0);
      $pdf->cell(30,$alt,"CRED. ESPECIAIS",0,0,"R",0);
      $pdf->cell(30,$alt,"REDUÇÕES",0,0,"R",0);
      $pdf->cell(30,$alt,"TOTAL CRÉDITOS",0,0,"R",0);
      $pdf->cell(30,$alt,"SALDO DISPONÍVEL",0,1,"R",0);
      
      $pdf->cell(10,$alt,"REDUZ",0,0,"L",0);
      
      $pdf->cell(30,$alt,"EMPENHADO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"ANULADO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"EMP LIQUIDO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"LIQUIDADO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"PAGO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"A LIQUIDAR",0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      
      $pdf->cell(30,$alt,"EMPENHADO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"ANULADO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"EMP LIQUIDO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"LIQUIDADO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"PAGO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"A PAGAR LIQUIDADO",0,1,"R",0);
      
      $pdf->cell(0,$alt,'',"T",1,"C",0);
      $pdf->setfont('arial','',7);
    }
    
    
    if($o58_orgao != $xorgao && $o58_orgao != 0 ){
      $xorgao = $o58_orgao;
    }
    if($o58_orgao.$o58_unidade != $xunidade && $o58_unidade != 0 ){
      $xunidade = $o58_orgao.$o58_unidade;
      $descr = $o41_descr;
      if($nivela != 2){
        $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
        $pdf->cell(60,$alt,$descr,0,1,"L",0);
      }
    }
    if($o58_orgao.$o58_unidade.$o58_funcao != $xfuncao && $o58_funcao != 0 ){
      $xfuncao = $o58_orgao.$o58_unidade.$o58_funcao;
      $descr = $o52_descr;
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
    if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao != $xsubfuncao && $o58_subfuncao != 0){
      $xsubfuncao = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao;
      $descr = $o53_descr;
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
    if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa != $xprograma && $o58_programa != 0){
      $xprograma = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa;
      $descr = $o54_descr;
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
    if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ != $xprojativ && $o58_projativ != 0){
      $xprojativ = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ;
      $descr = $o55_descr;
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
      
      // Gera total por atividade
      if( $totaliza_atividade == 'S'){
        
        $pdf->cell(10,$alt,"",0,0,"L",0);    
        $pdf->cell(30,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($suplemen_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($especial_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($reduzido_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($dot_ini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($dot_ini + $suplementado_acumulado - $reduzido_acumulado - $empenhado_acumulado + $anulado_acumulado,'f'),0,1,"R",0);
        
        $pdf->cell(10,$alt,"",0,0,"L",0);
        $pdf->cell(30,$alt,db_formatar($empenhado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($anulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($empenhado-$anulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($pago,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,'f'),0,1,"R",0);
        
        $pdf->cell(10,$alt,"",0,0,"L",0);
        $pdf->cell(30,$alt,db_formatar($empenhado_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($anulado_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($empenhado_acumulado-$anulado_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
        $pdf->cell(30,$alt,db_formatar($liquidado_acumulado - $pago_acumulado,'f'),0,1,"R",0);
        
      }
      
    }
    
    if($o58_codigo > 0){
      $descr = $o56_descr;
      $pdf->cell(20,$alt,$o58_elemento,0,0,"L",0);
      $pdf->cell(60,$alt,$descr.'    Recurso: '.$o58_codigo.'-'.$o15_descr,0,1,"L",0);
      
      $pdf->cell(10,$alt,$o58_coddot."-".db_CalculaDV($o58_coddot),0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($suplemen_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($especial_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($reduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($dot_ini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($dot_ini + $suplementado_acumulado - $reduzido_acumulado - $empenhado_acumulado + $anulado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($empenhado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($anulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar(($empenhado-$anulado),'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($pago,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($empenhado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($empenhado_acumulado-$anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($liquidado_acumulado - $pago_acumulado,'f'),0,1,"R",0);
      
      $totgeraldot_ini                += $dot_ini;
      $totgeralsuplementado_acumulado += $suplementado_acumulado;
      $totgeralsuplemen_acumulado			+= $suplemen_acumulado;
      $totgeralespecial_acumulado			+= $especial_acumulado;
      $totgeralreduzido_acumulado     += $reduzido_acumulado;
      $totgeralatual                  += $atual;
      $totgeralempenhado              += $empenhado;
      $totgeralanulado                += $anulado;
      $totgeralliquidado              += $liquidado;
      $totgeralpago                   += $pago;
      $totgeralatual_a_pagar          += $atual_a_pagar;
      $totgeralempenhado_acumulado    += $empenhado_acumulado;
      $totgeralanulado_acumulado      += $anulado_acumulado;
      $totgeralliquidado_acumulado    += $liquidado_acumulado;
      $totgeralpago_acumulado         += $pago_acumulado;      
      $totgeralatual_a_pagar_liquidado+= $atual_a_pagar_liquidado;      
      
    }
    
    $orguniant = db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'unidade');
   
  }
  
  $pdf->setfont('arial','b',7);

  if($quebra_unidade == "S"){
    
    $sqltot = "	select 
    dot_ini as totdot_ini, 
    suplemen_acumulado as totsuplemen_acumulado,
    especial_acumulado as totespecial_acumulado,
    reduzido_acumulado as totreduzido_acumulado,
    suplementado_acumulado as totsuplementado_acumulado,
    empenhado as totempenhado,
    anulado as totanulado,
    liquidado as totliquidado,
    pago as totpago,
    atual_a_pagar as totatual_a_pagar,
    empenhado_acumulado as totempenhado_acumulado,
    anulado_acumulado as totanulado_acumulado,
    liquidado_acumulado as totliquidado_acumulado,
    pago_acumulado as totpago_acumulado
    from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2) . " and o58_unidade = " . substr($orguniant,2,2) . " and o58_funcao = 0";
    $resulttot = pg_exec($sqltot) or die($sqltot);
    db_fieldsmemory($resulttot, 0);
    
    $pdf->ln(3);
    $pdf->cell(10,$alt,'UNIDADE',0,0,"L",0,'.');
    $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
    
    $pdf->cell(10,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar(($totempenhado-$totanulado),'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
    
    $pdf->cell(10,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
  }
  
  $pdf->ln(3);
  $pdf->cell(10,$alt,'ORGÃO',0,0,"L",0,'.');
  
  $sqltot = "	select 
  dot_ini as totdot_ini, 
  suplemen_acumulado as totsuplemen_acumulado,
  especial_acumulado as totespecial_acumulado,
  reduzido_acumulado as totreduzido_acumulado,
  suplementado_acumulado as totsuplementado_acumulado,
  empenhado as totempenhado,
  anulado as totanulado,
  liquidado as totliquidado,
  pago as totpago,
  atual_a_pagar as totatual_a_pagar,
  empenhado_acumulado as totempenhado_acumulado,
  anulado_acumulado as totanulado_acumulado,
  liquidado_acumulado as totliquidado_acumulado,
  pago_acumulado as totpago_acumulado
  from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2) . " and o58_unidade = 0";
  $resulttot = pg_exec($sqltot) or die($sqltot);
  db_fieldsmemory($resulttot, 0);
  
  $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
  
  $pdf->ln(3);
  $pdf->cell(30,$alt,'TOTAL GERAL ',0,1,"L",0,'.');
  
  $sqltot = "	select 
  sum(dot_ini) as totdot_ini, 
  sum(suplemen_acumulado) as totsuplemen_acumulado,
  sum(especial_acumulado) as totespecial_acumulado,
  sum(reduzido_acumulado) as totreduzido_acumulado,
  sum(suplementado_acumulado) as totsuplementado_acumulado,
  sum(empenhado) as totempenhado,
  sum(anulado) as totanulado,
  sum(liquidado) as totliquidado,
  sum(pago) as totpago,
  sum(atual_a_pagar) as totatual_a_pagar,
  sum(empenhado_acumulado) as totempenhado_acumulado,
  sum(anulado_acumulado) as totanulado_acumulado,
  sum(liquidado_acumulado) as totliquidado_acumulado,
  sum(pago_acumulado) as totpago_acumulado
  from ($sqlprinc) as x where o58_orgao > 0 and o58_unidade = 0";
  $resulttot = pg_exec($sqltot) or die($sqltot);
  db_fieldsmemory($resulttot, 0);
  
  $pdf->cell(10,$alt,'',0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
  $pdf->setfont('arial','',7);
  
} else {
  
  /////////// SINTÉTICO ///////////////////
  
  db_fieldsmemory($result,0);
  $xunidade = $o58_orgao.$o58_unidade; 
  $xorgao   = $o58_orgao;
  
  $alt = 4;
  
  $orguniant = "";
  
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result,0);
    $orguniant = db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'unidade');
  }
 
  for($i=0;$i<pg_numrows($result);$i++) {
  
    db_fieldsmemory($result,$i);
    
    if($pdf->gety() > $pdf->h-60 || $pagina == 1){
      
      $pdf->addpage();
      $pdf->setfont('arial','b',7);
      
      $pdf->ln(2);
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(30,$alt,"SALDO INICIAL",0,0,"R",0);
      $pdf->cell(30,$alt,"SUPLEMENTAÇÕES",0,0,"R",0);
      $pdf->cell(30,$alt,"CRED. ESPECIAIS",0,0,"R",0);
      $pdf->cell(30,$alt,"REDUÇÕES",0,0,"R",0);
      $pdf->cell(30,$alt,"TOTAL CRÉDITOS",0,0,"R",0);
      $pdf->cell(30,$alt,"SALDO DISPONÍVEL",0,1,"R",0);
      
      $pdf->cell(10,$alt,"REDUZ",0,0,"L",0);
      
      $pdf->cell(30,$alt,"EMPENHADO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"ANULADO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"EMP LIQUIDO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"LIQUIDADO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"PAGO NO MÊS",0,0,"R",0);
      $pdf->cell(30,$alt,"A LIQUIDAR",0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      
      $pdf->cell(30,$alt,"EMPENHADO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"ANULADO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"EMP LIQUIDO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"LIQUIDADO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"PAGO NO ANO",0,0,"R",0);
      $pdf->cell(30,$alt,"A PAGAR LIQUIDADO",0,1,"R",0);
      
      $pdf->cell(0,$alt,'',"T",1,"C",0);
      $pdf->setfont('arial','',7);
      
      if($pagina == 1) {
				$pdf->cell(10,$alt,db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
			}
			
      $pdf->setfont('arial','',7);
      $pagina = 0;
      
    }

    if( $xorgao != $o58_orgao ){
      $pdf->setfont('arial','b',7);
      $xorgao = $o58_orgao;
      $pdf->ln(3);
      $pdf->cell(10,$alt,'ORGÃO',0,0,"L",0,'.');
			
	    $sqltot = "	select 
     	sum(dot_ini) as totdot_ini, 
		  sum(suplemen_acumulado) as totsuplemen_acumulado,
			sum(especial_acumulado) as totespecial_acumulado,
			sum(reduzido_acumulado) as totreduzido_acumulado,
			sum(suplementado_acumulado) as totsuplementado_acumulado,
			sum(empenhado) as totempenhado,
			sum(anulado) as totanulado,
			sum(liquidado) as totliquidado,
			sum(pago) as totpago,
			sum(atual_a_pagar) as totatual_a_pagar,
			sum(empenhado_acumulado) as totempenhado_acumulado,
			sum(anulado_acumulado) as totanulado_acumulado,
			sum(liquidado_acumulado) as totliquidado_acumulado,
			sum(pago_acumulado) as totpago_acumulado
			from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2);
		/*	$sqltot = "	select 
			sum(dot_ini) as totdot_ini, 
			suplemen_acumulado as totsuplemen_acumulado,
			especial_acumulado as totespecial_acumulado,
			reduzido_acumulado as totreduzido_acumulado,
			suplementado_acumulado as totsuplementado_acumulado,
			empenhado as totempenhado,
			anulado as totanulado,
			liquidado as totliquidado,
			pago as totpago,
			atual_a_pagar as totatual_a_pagar,
			empenhado_acumulado as totempenhado_acumulado,
			anulado_acumulado as totanulado_acumulado,
			liquidado_acumulado as totliquidado_acumulado,
			pago_acumulado as totpago_acumulado
			from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2)."";
		*/
			$resulttot = pg_exec($sqltot) or die($sqltot);
			db_fieldsmemory($resulttot, 0);
			
      $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
      $pdf->ln(3);
      
      $pdf->setfont('arial','B',7);
      $pdf->cell(10,$alt,db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
      $pdf->setfont('arial','',7);
      
    }
    
    if($nivela==2){
      $pdf->cell(10,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')."  -  ".$o41_descr,0,1,"L",0); 
      $pdf->cell(10,$alt,'',0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($suplemen_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($especial_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($reduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($totdot_ini + $suplementado_acumulado - $reduzido_acumulado - $empenhado_acumulado + $anulado_acumulado,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($empenhado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($anulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($empenhado-$anulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($pago,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado ,'f'),0,1,"R",0);
      
      $pdf->cell(10,$alt,"",0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($empenhado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($empenhado_acumulado-$anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($liquidado_acumulado - $pago_acumulado ,'f'),0,1,"R",0);
      $pdf->setfont('arial','',7);
      
    }
    
    $orguniant = db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'unidade');
    
  }

  $pdf->setfont('arial','b',7);
	
  if($quebra_unidade == "S"){
    $pdf->ln(3);
    $pdf->cell(10,$alt,'UNIDADE',0,0,"L",0,'.');
    $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($suplemen_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($especial_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($reduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totdot_ini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totdot_ini + $suplementado_acumulado - $reduzido_acumulado - $empenhado_acumulado + $anulado_acumulado,'f'),0,1,"R",0);
    
    $pdf->cell(10,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($empenhado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($anulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($empenhado-$anulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($pago,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,'f'),0,1,"R",0);
    
    $pdf->cell(10,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($empenhado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($anulado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($empenhado_acumulado-$anulado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($liquidado_acumulado - $pago_acumulado,'f'),0,1,"R",0);

  }
  
  $pdf->ln(3);
  $pdf->cell(10,$alt,'ORGÃO',0,0,"L",0,'.');

	$sqltot = "	select 
	sum(dot_ini) as totdot_ini, 
	sum(suplemen_acumulado) as totsuplemen_acumulado,
	sum(especial_acumulado) as totespecial_acumulado,
	sum(reduzido_acumulado) as totreduzido_acumulado,
	sum(suplementado_acumulado) as totsuplementado_acumulado,
	sum(empenhado) as totempenhado,
	sum(anulado) as totanulado,
	sum(liquidado) as totliquidado,
	sum(pago) as totpago,
	sum(atual_a_pagar) as totatual_a_pagar,
	sum(empenhado_acumulado) as totempenhado_acumulado,
	sum(anulado_acumulado) as totanulado_acumulado,
	sum(liquidado_acumulado) as totliquidado_acumulado,
	sum(pago_acumulado) as totpago_acumulado
	from ($sqlprinc) as x where o58_orgao = " . substr($orguniant,0,2);
	$resulttot = pg_exec($sqltot) or die($sqltot);
	//echo $orguniant;
	db_fieldsmemory($resulttot, 0);

  $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
  
  $pdf->ln(3);
  $pdf->cell(10,$alt,'GERAL',0,0,"L",0,'.');

  $sqltot = "	select 
  sum(dot_ini) as totdot_ini, 
  sum(suplemen_acumulado) as totsuplemen_acumulado,
  sum(especial_acumulado) as totespecial_acumulado,
  sum(reduzido_acumulado) as totreduzido_acumulado,
  sum(suplementado_acumulado) as totsuplementado_acumulado,
  sum(empenhado) as totempenhado,
  sum(anulado) as totanulado,
  sum(liquidado) as totliquidado,
  sum(pago) as totpago,
  sum(atual_a_pagar) as totatual_a_pagar,
  sum(empenhado_acumulado) as totempenhado_acumulado,
  sum(anulado_acumulado) as totanulado_acumulado,
  sum(liquidado_acumulado) as totliquidado_acumulado,
  sum(pago_acumulado) as totpago_acumulado
  from ($sqlprinc) as x where o58_orgao > 0";
  $resulttot = pg_exec($sqltot) or die($sqltot);
  db_fieldsmemory($resulttot, 0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totsuplemen_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totespecial_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totdot_ini + $totsuplementado_acumulado - $totreduzido_acumulado - $totempenhado_acumulado + $totanulado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado-$totanulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado - $totanulado_acumulado - $totliquidado_acumulado,'f'),0,1,"R",0);
  
  $pdf->cell(10,$alt,"",0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totempenhado_acumulado-$totanulado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totpago_acumulado,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($totliquidado_acumulado - $totpago_acumulado,'f'),0,1,"R",0);
  $pdf->setfont('arial','',7);
  
}

pg_free_result($result);
//include("fpdf151/geraarquivo.php");
$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);

//echo $ass_pref;
if( $pdf->gety() > ( $pdf->h - 30 ) ){
$pdf->addpage();
}
$largura = ( $pdf->w ) / 2;
$pdf->ln(5);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);

$pdf->Output();
?>