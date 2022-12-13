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


if (!isset($arqinclude)){
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_orcparamrel_classe.php");
  include("dbforms/db_funcoes.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");

$clconrelinfo  = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
//******************************************************************
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
  }else{
       $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head2 = "INSTITUIÇÕES : ".$descr_inst;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DE OPERAÇÕES DE CREDITO";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$period = '';
if ($periodo=="1Q"){
  $period = '1º QUADRIMESTRE';
}elseif($periodo=="2Q"){  
  $period = '2º QUADRIMESTRE'; 
}elseif($periodo=="3Q"){  
  $period = '3º QUADRIMESTRE'; 
}elseif($periodo=="1S"){
  $period = '1º SEMESTRE';
}elseif($periodo=="2S"){
  $period = '2º SEMESTRE';
}
$head6 = "PERIODO : $period";
//******************************************************************

$where      = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";
$anousu     = db_getsession("DB_anousu");
$anousu_ant = $anousu - 1;

$head7 = "EXERCÍCIO : $anousu";
//---
$soma_interna  =0;
$soma_externa  =0;
$soma_antecipacao = 0;
//--------- // ------------- // ------------- // ---------------
// recupera elementos da configuração dos relatorios
//--------- // ------------- // ------------- // ---------------

$instituicao  = str_replace("-",",",$db_selinstit);

$parametro[0] = $orcparamrel->sql_parametro('6','0',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[1] = $orcparamrel->sql_parametro('6','1',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[2] = $orcparamrel->sql_parametro('6','2',"f",$instituicao,db_getsession("DB_anousu"));

$dt       = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini   = $dt[0];  // data inicial do periodo
$dt_fim   = $dt[1];  // data final do período

// se o ano atual é bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
$dt = split('-',$dt_fim);  // mktime -- (mes,dia,ano)
//$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1],$dt[2]-364,$dt[0]));
$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1]-11,"01",$dt[0]));
$dt_fim_ant = $anousu_ant.'-12-31';

$where     = "  c61_instit in (".str_replace('-',', ',$db_selinstit).")   "; 
$resultado = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fim,false,$where);
@pg_exec("drop table work_pl");

for ($x=0; $x < 11;$x++) {
  $valor1[$x] = 0;
}

for($i=0;$i< pg_numrows($resultado);$i++) {
  db_fieldsmemory($resultado,$i);
  if (in_array($estrutural,$parametro[0])){
    $valor1[1] += $saldo_final;
  }
  
  if (in_array($estrutural,$parametro[1])){
    $valor1[2] += $saldo_final;
  }
  
  if (in_array($estrutural,$parametro[2])){
    $valor1[3] += $saldo_final;
  }
}

$valor1[0] = $valor1[1] + $valor1[2];
$valor1[4] = $valor1[0] + $valor1[3];
///////////////////////////////////////////////////////////////////////////////////////////
//                            '     receita corrente liquida.
//////////////////////////////////////////////////////////////////////////////////////////
$valor1[5]  = calcula_rcl($anousu,$anousu.'-01-01',$dt_fim,$db_selinstit);
$valor1[5] += calcula_rcl($anousu_ant,$dt_ini_ant,$dt_fim_ant,$db_selinstit);

@$valor1[6] = ($valor1[0] / $valor1[5]) * 100;
@$valor1[7] = ($valor1[3] / $valor1[5]) * 100;
$valor1[8]  = $valor1[5] * 0.16;
$valor1[9]  = $valor1[5] * 0.07;

//--------- // ------------- // ------------- // ---------------
$texto[0] = 'OPERAÇÕES DE CRÉDITOS (I)';
$texto[1] = ' Externas';
$texto[2] = ' Internas';
$texto[3] = 'POR ANTECIPAÇÃO DA RECEITA(II)';
$texto[4] = 'TOTAL DAS OPERAÇÕES DE CRÉDITO (I + II)';
$texto[5] = 'RECEITA CORRENTE LIQUIDA - RCL';
$texto[6] = '% das OPERAÇÕES DE CRÉDITOS INTERNAS E EXTERNAS sobre a RCL';
$texto[7] = '% das OPERAÇÕES DE CRÉDITOS POR ANTECIPAÇÃO DA RECEITA sobre a RCL';
$texto[8] = 'LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO INTERNAS E EXTERNAS';
$texto[9] = "LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA";
$texto[10] = '';

if (!isset($arqinclude)){
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $pdf->cell(160,$alt,'LRF, Art 55, inciso I, alínea "d" e inciso III alínea "c" - Anexo IV','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ Unidades','B',1,"R",0);
  $pdf->cell(145,$alt,"EMPRÉSTIMOS E FINANCIAMENTOS",'R',0,"C",0);
  $pdf->cell(40,$alt,"OPERAÇÕES REALIZADAS",'',1,"C",0);
  $pdf->cell(145,$alt,"",'RB',0,"R",0);
  
  if ($periodo!="1S"&&$periodo!="2S"){
    $period = "Quadrimestre";
  }else{
    $period = "Semestre";
  }
  
  $pdf->cell(40,$alt,"Até o $period",'B',1,"C",0);
  
  $pdf->setfont('arial','',6);
  $d = 0;
  $pdf->cell(145,$alt,$texto[0],'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($valor1[0], 'f'),'',1,"R",0);
  $pdf->cell(145,$alt,$texto[1],'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($valor1[1], 'f'),'',1,"R",0);
  $te = 0;
  for($x=0;$x< pg_numrows($resultado);$x++) {
    db_fieldsmemory($resultado,$x);
    if (in_array($estrutural,$parametro[0])){
      $pdf->cell(145,$alt,'    '.$c60_descr,'R',0,"L",0);
      $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),'',1,"R",0);
    }
  }
  
  $pdf->cell(145,$alt,$texto[2],'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($valor1[2], 'f'),'',1,"R",0);
  for($x=0;$x < pg_numrows($resultado);$x++) {
    db_fieldsmemory($resultado,$x);
    if (in_array($estrutural,$parametro[1])){
      $pdf->cell(145,$alt,'    '.$c60_descr,'R',0,"L",0);
      $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),'',1,"R",0);
    }
  }
  $pdf->cell(145,$alt,$texto[3],'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($valor1[3], 'f'),'',1,"R",0);
  for($x=4;$x < 10;$x++) {
    $pdf->cell(145,$alt,$texto[$x],'TBR',0,"L",0);
    $pdf->cell(40,$alt,db_formatar($valor1[$x],'f'),'TB',1,"R",0);
  }
  
  // assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);
  
  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  
}

$total_operacoes_credito				= $valor1[4];
$perc_total_operacoes_credito		= $valor1[6];

$total_antecipacao_receita			= $valor1[7];
$perc_antecipacao_receita				= $valor1[3];

// Variaveis do relatorio
$valor_int_ext     = 0;
$valor_antecipacao = 0;

$res_variaveis     = $clconrelinfo->sql_record($clconrelinfo->sql_query_file(null,"c83_codigo,c83_variavel","c83_codigo","c83_codrel = 6 and c83_anousu = ".db_getsession("DB_anousu")));
if ($clconrelinfo->numrows > 0){
  for($i = 0; $i < $clconrelinfo->numrows; $i++){
    db_fieldsmemory($res_variaveis,$i);

    $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = $c83_codigo and c83_periodo = '$periodo' and c83_instit in (".str_replace("-",",",$db_selinstit).")"));
    if ($clconrelvalor->numrows > 0){
      db_fieldsmemory($res_valor,0);
      if ($c83_codigo == 297){
        $valor_int_ext = $c83_informacao;  
      }

      if ($c83_codigo == 298){
        $valor_antecipacao = $c83_informacao;  
      }
    }
  }
}
// Fim das Variaveis

$perc_limite_senado_int_ext     = $valor_int_ext;
$perc_limite_senado_antecipacao = $valor_antecipacao;

if ($valor_int_ext > 0){
  $limite_senado_int_ext = $valor1[8];
} else {
  $limite_senado_int_ext = 0;
}

if ($valor_antecipacao > 0){
  $limite_senado_antecipacao = $valor1[9];
} else {
  $limite_senado_antecipacao = 0;
}

?>