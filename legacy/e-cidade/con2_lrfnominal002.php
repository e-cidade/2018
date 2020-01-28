<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("libs/db_libtxt.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_orcparamrel_classe.php");
  include("classes/db_conrelinfo_classe.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel = new cl_orcparamrel;
  $clconrelinfo = new cl_conrelinfo;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  //-----------------------------------
  $tipo_emissao='periodo';
  
  $anousu      = db_getsession("DB_anousu");
  $anousu_ant  = db_getsession("DB_anousu")-1;
  
  $dt = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
  $dt_ini= $anousu.'-01-01'; // data inicial do perodo
  $dt_fin= $dt[1]; // data final do perodo
  $texto = $dt['texto'];
  $txtper = $dt['periodo'];
  
}   // end !include

// verifica periodo anterior ( bimestre anterior )
$per = substr($periodo,0,1);
if ($per >1 ){
  $periodo_ant= ($per -1).'B';
} else {
  $periodo_ant= '1B';
} 

$anousu_ant  = db_getsession("DB_anousu");

$dt = data_periodo($anousu_ant,$periodo_ant); // no dbforms/db_funcoes.php
$dt_ini_ant= $dt[0]; // data inicial do perodo
$dt_fin_ant= $dt[1]; // data final do perodo

$META_NOMINAL = 0;

//Debug
//echo "<br>$periodo<br>$periodo_ant<br>";
//die($clconrelinfo->sql_query_valores(16,str_replace('-',',',$db_selinstit),$periodo));

$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(16,str_replace('-',',',$db_selinstit),$periodo));
if ($clconrelinfo->numrows > 0 ){
  // se aumentar a quantidade de variaveis forneca um "loop" aqui
  db_fieldsmemory($res,0);
  if ($c83_codigo==271){
    $META_NOMINAL  = $c83_informacao;
  }
} 

$n1 = 5;

$instituicao = str_replace("-",",",$db_selinstit);

$m_divida_consolidada   = $orcparamrel->sql_parametro('16','0',"f",$instituicao,db_getsession("DB_anousu"));
$m_r_ativo_disp         = $orcparamrel->sql_parametro('16','1',"f",$instituicao,db_getsession("DB_anousu"));
$m_r_haveres_financeiro = $orcparamrel->sql_parametro('16','2',"f",$instituicao,db_getsession("DB_anousu"));
$m_r_rp_processados     = $orcparamrel->sql_parametro('16','3',"f",$instituicao,db_getsession("DB_anousu"));
$m_privatizacao         = $orcparamrel->sql_parametro('16','4',"f",$instituicao,db_getsession("DB_anousu"));
$m_passivos             = $orcparamrel->sql_parametro('16','5',"f",$instituicao,db_getsession("DB_anousu"));
$m_rpps_investimentos   = $orcparamrel->sql_parametro('16','6',"f",$instituicao,db_getsession("DB_anousu"));

$somador_I_ant =0;
$somador_I_antbim =0;
$somador_I_bim =0;
$somador_II_ant =0;
$somador_II_antbim =0;
$somador_II_bim =0;
$somador_III_ant =0;
$somador_III_antbim =0;
$somador_III_bim =0;
$somador_IV_ant =0;
$somador_IV_antbim =0;
$somador_IV_bim =0;
$somador_V_ant =0;
$somador_V_antbim =0;
$somador_V_bim =0;

// RPPS
// DIVIDA CONSOLIDADA PREVIDENCIARIA
$somador_VI_ant      = 0;
$somador_VI_antbim   = 0;
$somador_VI_bim      = 0;
// DEDUCOES
$somador_VII_ant     = 0;
$somador_VII_antbim  = 0;
$somador_VII_bim     = 0;
// DIVIDA CONSOLIDADA LIQUIDA PREVIDENCIARIA
$somador_VIII_ant    = 0;
$somador_VIII_antbim = 0;
$somador_VIII_bim    = 0;
// PASSIVOS RECONHECIDOS
$somador_IX_ant      = 0;
$somador_IX_antbim   = 0;
$somador_IX_bim      = 0;

//----------------------------------- // ------------------------------- 
// seleciona as instituies que no so RPPS

$db_selinstit_sem_rpps = "";

$xinstit    = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,munic,db21_tipoinstit from db_config where codigo in (".str_replace('-',', ',$db_selinstit).")");
$descr_inst = '';
$xvirgi     = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  
  if($db21_tipoinstit != 5 && $db21_tipoinstit != 6 ){
    $db_selinstit_sem_rpps .= $xvirgi.$codigo;
    $xvirgi       = ', ';
  }
}

db_fieldsmemory($resultinst,0);

$descr_inst = $munic;

if (!isset($arqinclude)){
  
  ///////////////////////////////
  $head2  = "MUNICÍPIO DE ".$descr_inst;
  $head3  = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head4  = "DEMONSTRATIVO DO RESULTADO NOMINAL";
  $head5  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

  $dados  = data_periodo($anousu,$periodo);
  $perini = split("-",$dados[0]);
  $perfin = split("-",$dados[1]);

  $txtper = strtoupper($dados["periodo"]);
  $mesini = strtoupper(db_mes($perini[1]));
  $mesfin = strtoupper(db_mes($perfin[1]));

  $head6 = "JANEIRO A ".$mesfin."/".$anousu." - ".$txtper." ".$mesini."-".$mesfin;
}  // end !include
//////////////////////////

$where = " c61_instit in (".str_replace('-',', ',$db_selinstit_sem_rpps).")  ";

//echo(db_getsession("DB_anousu")." ".$dt_ini_ant." ".$dt_fin_ant." ".$where);
//echo (db_getsession("DB_anousu").$dt_ini.$dt_fin.$where);exit;

$result_peranterior = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini_ant,$dt_fin_ant,false,$where);
@pg_query("Drop table Work_pl");


$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini,$dt_fin,false,$where);
@pg_query("Drop table Work_pl");
//////////////////////////

if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $alt            = 4;
  $pagina         = 1;
  
  $pdf->addpage();
  $pdf->setfont('arial','',8);
  
  $pdf->ln();
  $pdf->cell(70,$alt,"RREO - ANEXO VI(LRF, art. 53, inciso III)",'0',0,"L",0);
  $pdf->cell(120,$alt,"R$ 1,00",0,"R",0);
  
  $pdf->ln();
  $pdf->cell(70,($alt*2),"ESPECIFICAÇÃO",'TBR',0,"C",0);
  $pdf->cell(120,($alt),"SALDO",'TB',1,"C",0);      // br
  $pdf->setX(80);
  $pdf->cell(40,$alt,"Em 31/Dez/".($anousu_ant-1)." (a)",'1',0,"C",0);
  $dt = split("-",$dt_fin_ant);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (b)",'1',0,"C",0);
  
  $dt = split("-",$dt_fin);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (c)",'TB',0,"C",0);
  $pdf->Ln();
  
} // end !include

//---------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_divida_consolidada)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_divida_consolidada)){
    $tot_biant += $saldo_final;
  }
}

//echo "Aqui"; exit;

if (!isset($arqinclude)){
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DÍVIDA CONSOLIDADA (I)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
  
}

$somador_I_ant    +=$tot_ant;
$somador_I_antbim +=$tot_biant;
$somador_I_bim    +=$tot_bi;

// -- dedudoes
if (!isset($arqinclude)){
  
  $pos_deducao = $pdf->getY();
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DEDUÇÕES (II)",'R',0,"L",0);
  $pdf->cell(40,$alt,'','R',0,"R",0);
  $pdf->cell(40,$alt,'','R',0,"R",0);
  $pdf->cell(40,$alt,'','0',0,"R",0);
  $pdf->Ln();
  
} // end !include

// -----------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;

for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_r_ativo_disp)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
  if (in_array($estrutural,$m_rpps_investimentos)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
  
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_r_ativo_disp)){
    $tot_biant += $saldo_final;
  }
  if (in_array($estrutural,$m_rpps_investimentos)){
    $tot_biant += $saldo_final;
  }
  
}

if (!isset($arqinclude)) {
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Ativo Disponível",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
  
}

$somador_II_ant    += $tot_ant;
$somador_II_antbim += $tot_biant;
$somador_II_bim    += $tot_bi;

// -----------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_r_haveres_financeiro)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_r_haveres_financeiro)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Haveres Financeiros",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}

$somador_II_ant    += $tot_ant;
$somador_II_antbim += $tot_biant;
$somador_II_bim    += $tot_bi;

// ------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_r_rp_processados)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_r_rp_processados)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."(-) Restos a Pagar Processados",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}  
$somador_II_ant    -= $tot_ant;
$somador_II_antbim -= $tot_biant;
$somador_II_bim    -= $tot_bi;

//-----------
// imprime la em cima o total das deducoes

if (!isset($arqinclude)){
  
  $pos_atu = $pdf->y; // posio atual
  // sobe, escreve e desce
  $pdf->setY($pos_deducao);
  $pdf->setX(80);
  
  $pdf->cell(40,$alt,($somador_II_ant < 0    ?"-":db_formatar($somador_II_ant,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_II_antbim < 0?"-":db_formatar($somador_II_antbim,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_II_bim < 0?   "-":db_formatar($somador_II_bim,'f')),'0',0,"R",0);
  
  $pdf->setY($pos_atu); // desce novamente at aki 
  
}

if ($somador_II_ant < 0) {
  $somador_II_ant = 0;
}

if ($somador_II_antbim < 0) {
  $somador_II_antbim = 0;
}

if ($somador_II_bim < 0) {
  $somador_II_bim = 0;
}


//------------
$somador_III_ant    = ($somador_I_ant - $somador_II_ant);
$somador_III_antbim = ($somador_I_antbim - $somador_II_antbim);
$somador_III_bim    = ($somador_I_bim - $somador_II_bim);

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DIVIDA CONSOLIDADA LIQUIDA (III) = (I-II)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador_III_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_III_antbim,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_III_bim,'f'),'0',0,"R",0);
  $pdf->Ln();
} // end !include    
//---------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_privatizacao)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_privatizacao)){
    $tot_biant += $saldo_final;
  }
}
if (!isset($arqinclude)){
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"RECEITA DE PRIVATIZAÇÕES (IV)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
} // end !include

$somador_IV_ant    +=$tot_ant;
$somador_IV_antbim +=$tot_biant;
$somador_IV_bim    +=$tot_bi;
//---------------

$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_passivos)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_passivos)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"PASSIVOS RECONHECIDOS (V)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
} // end !include

$somador_V_ant    +=$tot_ant;
$somador_V_antbim +=$tot_biant;
$somador_V_bim    +=$tot_bi;

//echo("somador_III_bim: $somador_III_bim - somador_IV_bim:  $somador_IV_bim - somador_V_bim: $somador_V_bim<br>");
//echo("somador_III_ant: $somador_III_ant - somador_IV_ant: $somador_IV_ant - somador_V_ant: $somador_V_ant<br>");

//------------
$tot_ant   = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);
$tot_biant = (($somador_III_antbim + $somador_IV_antbim) - $somador_V_antbim);
$tot_bi    = (($somador_III_bim  + $somador_IV_bim) - $somador_V_bim );

$TOTAL_ANTERIOR = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);

if (!isset($arqinclude)){
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DÍVIDA FISCAL LÍQUIDA(VI) = (III+IV-V)",'TBR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'TBR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'TBR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'TB',0,"R",0);
  $pdf->Ln();
  
  //-------------- // ----------- // --------------
  // imprime resultado nominal
  $pdf->Ln(4);
  $pdf->setfont('arial','',7);
  $pdf->cell(70,($alt*2),"ESPECIFICAÇÃO",'TBR',0,"C",0);
  $pdf->cell(120,($alt),"PERÍODO DE REFERÊNCIA",'TB',1,"C",0); // br
  $pdf->setX(80);
  $pdf->cell(60,$alt,"No Bimestre (c-b)",'1',0,"C",0);
  $pdf->cell(60,$alt,"Até o Bimestre (c-a)",'TB',0,"C",0);
  $pdf->Ln();
  
  //
  $pdf->cell(70,$alt,"RESULTADO NOMINAL",'TBR',0,"L",0);
  if ($tot_bi < 0 && $tot_biant <0){
    // subtrai e mantem o sinal do maior
    $pdf->cell(60,$alt,db_formatar(((abs($tot_bi)-abs($tot_biant))*-1),'f'),'TBR',0,"R",0);   
  } else {  
    $pdf->cell(60,$alt,db_formatar(($tot_bi-$tot_biant),'f'),'TBR',0,"R",0);
  }  
  if ($tot_bi <0 && $tot_ant  <0){
    // subtrai e fica o sinal do maior
    $pdf->cell(60,$alt,db_formatar(((abs($tot_bi)-abs($tot_ant))*-1),'f'),'TB',0,"R",0);  
  }else { 
    $pdf->cell(60,$alt,db_formatar(($tot_bi-$tot_ant),'f'),'TB',0,"R",0);
  }  
  $pdf->ln(4);
  
  // imprime meta fiscal
  $pdf->ln(4);
  $pdf->cell(130,$alt,"DISCRIMINAÇÃO DA META FISCAL",'TBR',0,"C",0);
  $pdf->cell(60,$alt,"VALOR CORRENTE",'TB',1,"C",0);
  
  $pdf->cell(130,$alt,"META DE RESULTADO NOMINAL FIXADA NO ANEXO DE METAS FISCAIS",'R',0,"L",0);
  $pdf->cell(60,$alt,db_formatar($META_NOMINAL,'f'),'0',0,"R",0);
  
  $pdf->Ln();
  $pdf->cell(130,$alt,"DA LDO P/ O EXERCÍCIO DE REFERÊNCIA",'BR',0,"L",0);
  $pdf->cell(60,$alt,'','TB',1,"R",0);
  
  //echo "Antes RPPS"; exit;
  
}

// RPPS ///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// seleciona instituio do RPPS
$sql    = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
$resultinst = pg_exec($sql);
$instit ='0';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $instit     .= $xvirg.$codigo; // salva insituio
  $xvirg       = ', ';		  
}
$where = " c61_instit in (".$instit.") "; 
$result_peranterior = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini_ant,$dt_fin_ant,false,$where);
@pg_query("drop table work_pl");


$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini,$dt_fin,false,$where);
@pg_query("drop table work_pl");
///////////////////////////////////////////////////////////////////////////////////////////////////////

//echo "Depois RPPS"; exit;

if (!isset($arqinclude)){
  
  $pdf->ln();
  $pdf->cell(190,$alt,"REGIME PREVIDENCIÁRIO",'TB',1,"C",0);
  $pdf->cell(70,($alt*2),"ESPECIFICAÇÃO",'TBR',0,"C",0);
  $pdf->cell(120,($alt),"SALDO",'TB',1,"C",0);      // br
  $pdf->setX(80);
  $pdf->cell(40,$alt,"Em 31/Dez/".($anousu_ant-1)." (a)",'1',0,"C",0);
  $dt = split("-",$dt_fin_ant);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (b)",'1',0,"C",0);
  
  $dt = split("-",$dt_fin);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (c)",'TB',0,"C",0);
  $pdf->Ln();
  
  
}

//---------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_divida_consolidada)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_divida_consolidada)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DIVIDA CONSOLIDADA PREVIDENCIÁRIA (VII)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$somador_VI_ant    +=$tot_ant;
$somador_VI_antbim +=$tot_biant;
$somador_VI_bim    +=$tot_bi;

// -- dedudoes

if (!isset($arqinclude)){
  $pos_deducao = $pdf->getY();
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DEDUÇÕES (VIII)",'R',0,"L",0);
  $pdf->cell(40,$alt,'','R',0,"R",0);
  $pdf->cell(40,$alt,'','R',0,"R",0);
  $pdf->cell(40,$alt,'','0',0,"R",0);
  $pdf->Ln();
} 
// -----------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_r_ativo_disp)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_r_ativo_disp)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Ativo Disponível",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$somador_VII_ant    += $tot_ant;
$somador_VII_antbim += $tot_biant;
$somador_VII_bim    += $tot_bi;

// -----------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_rpps_investimentos)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_rpps_investimentos)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Investimentos",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$somador_VII_ant    += $tot_ant;
$somador_VII_antbim += $tot_biant;
$somador_VII_bim    += $tot_bi;

// ----- 

$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_r_haveres_financeiro)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_r_haveres_financeiro)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Haveres Financeiros",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$somador_VII_ant    += $tot_ant;
$somador_VII_antbim += $tot_biant;
$somador_VII_bim    += $tot_bi;

// ------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;
for($i=0;$i< pg_numrows($result);$i++) {
  db_fieldsmemory($result,$i);
  if (in_array($estrutural,$m_r_rp_processados)){
    $tot_ant += $saldo_anterior;
    $tot_bi  += $saldo_final;
  }
}
for($i=0;$i< pg_numrows($result_peranterior);$i++) {
  db_fieldsmemory($result_peranterior,$i);
  if (in_array($estrutural,$m_r_rp_processados)){
    $tot_biant += $saldo_final;
  }
}

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."(-) Restos a Pagar Processados",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$somador_VII_ant    -= $tot_ant;
$somador_VII_antbim -= $tot_biant;
$somador_VII_bim    -= $tot_bi;

//-----------
// imprime la em cima o total das deducoes

if (!isset($arqinclude)){
  $pos_atu = $pdf->y; // posio atual
  // sobe, escreve e desce
  $pdf->setY($pos_deducao);
  $pdf->setX(80);
  $pdf->cell(40,$alt,($somador_VII_ant    < 0?"-":db_formatar($somador_VII_ant,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_VII_antbim < 0?"-":db_formatar($somador_VII_antbim,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_VII_bim    < 0?"-":db_formatar($somador_VII_bim,'f')),'0',0,"R",0);
  
}
if ($somador_VII_ant < 0) {
  $somador_VII_ant = 0;
}

if ($somador_VII_antbim < 0) {
  $somador_VII_antbim = 0;
}

if ($somador_VII_bim < 0) {
  $somador_VII_bim = 0;
}

if (!isset($arqinclude)){
  $pdf->setY($pos_atu); // desce novamente at aki 
}

//------------
$somador_VIII_ant    = ($somador_VI_ant    - $somador_VII_ant);
$somador_VIII_antbim = ($somador_VI_antbim - $somador_VII_antbim);
$somador_VIII_bim    = ($somador_VI_bim    - $somador_VII_bim);

if (!isset($arqinclude)){
  $pdf->setfont('arial','',6);
  $pdf->cell(70,$alt,"DíVIDA CONSOLIDADA LíQUIDA PREVIDENCIÁRIA(IX) = (VII-VIII)",'R',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(40,$alt,db_formatar($somador_VIII_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_VIII_antbim,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_VIII_bim,'f'),'0',0,"R",0);
  $pdf->Ln();
  
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"PASSIVOS RECONHECIDOS(X)",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador_IX_ant,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_IX_antbim,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_IX_bim,'f'),'0',0,"R",0);
  $pdf->Ln();
} 
$tot_ant   = ($somador_VIII_ant    - $somador_IX_ant);
$tot_biant = ($somador_VIII_antbim - $somador_IX_antbim);
$tot_bi    = ($somador_VIII_bim    - $somador_IX_bim);

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DÍVIDA FISCAL LÍQUIDA PREVIDENCIÁRIA(XI) = (IX-X)",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'TB',0,"R",0);
  
  ///////////////////////////////////////////////////////////////////////////////
  $pdf->Ln();

  notasExplicativas(&$pdf,16,"{$periodo}",190);
  
  $pdf->Ln(24);
  
  assinaturas(&$pdf,&$classinatura,'LRF');
  
  $pdf->Output();
  
}  // end !include

?>