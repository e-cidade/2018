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
  include("classes/db_orcparamrelopcre_classe.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");
require_once("classes/db_orcparamrelopcre_classe.php");
require_once("libs/db_utils.php");

$clconrelinfo      = new cl_conrelinfo;
$clconrelvalor     = new cl_conrelvalor;
$oOrcParamRelopcre = new cl_orcparamrelopcre;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,munic,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
$nTotalRcl = 0;
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

$anousu     = db_getsession("DB_anousu");
$anousu_ant = $anousu - 1;
$head2 = "MUNICÍPIO DE ".$munic;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DE OPERAÇÕES DE CREDITO";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$period = '';
if ($periodo=="1Q"){
  $period = "JANEIRO A ABRIL DE {$anousu}";
}elseif($periodo=="2Q"){  
  $period = "JANEIRO A AGOSTO DE {$anousu}";
}elseif($periodo=="3Q"){  
  $period = "JANEIRO A DEZEMBRO DE {$anousu}";
}elseif($periodo=="1S"){
  $period = "JANEIRO A JUNHO DE {$anousu}";
}elseif($periodo=="2S"){
  $period = "JANEIRO A DEZEMBRO DE {$anousu}";
}
$head6 = "$period";

$where      = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";


$soma_interna     = 0;
$soma_externa     = 0;
$soma_antecipacao = 0;
$instituicao      = str_replace("-",",",$db_selinstit);
$dt               = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini           = "{$anousu}-01-01";  // data inicial do periodo
$dt_fim           = $dt[1];  // data final do período

$dt = split('-',$dt_fim);  // mktime -- (mes,caddia,ano)
$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1]+1,"01",$anousu_ant));
$dt_fim_ant = $anousu_ant.'-12-31';

/*
 * Selecionamos as linhas cadastradas pelo usuário,
 * e atualizamos os totais de cada linha;
 */
$sWhere                  = '';
switch ($periodo){

  case "1Q":
  
    $sWhere = "o98_periodo = '1Q'";
    break;
    
  case "2Q":
   
    $sWhere = "o98_periodo in('1Q', '2Q')";
    break;
    
  case "3Q":     
  
    $sWhere = "o98_periodo in('1Q', '2Q', '3Q')";
    break;
    
  case "1S":     
  
    $sWhere = "o98_periodo = '1S'";
    break;   
  
  case "2S":     
  
    $sWhere = "o98_periodo in('1S', '2S')";
    break;  
    
}
$nTotalOperacoesCredito  = 0;
$nTotalInternas          = 0;
$nTotalExternas          = 0;
$nValorAntecipacao       = 0;
$nValorInternoExterno    = 0;
$nPercValorInterno       = 0;
$nPercValorAntecipacao   = 0;
$aOperacaoCredito["interna"]     = array();//linhas operacoes internas
$aOperacaoCredito["externa"]     = array();//linhas operacoes externas
$aOperacaoCredito["antecipacao"] = array();//linhas operacoes antecipadas;

$sSqllinhaUsuario  = "select o98_identificacao, ";
$sSqllinhaUsuario .= "       o98_valor,         ";
$sSqllinhaUsuario .= "       substring(o98_credor,1,28) as o98_credor,";
$sSqllinhaUsuario .= "       o98_orcparamseq    ";
$sSqllinhaUsuario .= "  from orcparamrelopcre   ";
$sSqllinhaUsuario .= " where o98_instit in ({$instituicao}) ";
$sSqllinhaUsuario .= "   and o98_anousu = {$anousu}"; 
$sSqllinhaUsuario .= "   and {$sWhere}"; 
$rsLInhaUsuario    = $oOrcParamRelopcre->sql_record($sSqllinhaUsuario);
if ($oOrcParamRelopcre->numrows > 0){
  
  for ($iInd = 0; $iInd < $oOrcParamRelopcre->numrows; $iInd++){
  
    $oLinhaAtual = db_utils::fieldsMemory($rsLInhaUsuario, $iInd);
    //Atualizamos as operacoes internas
    if ($oLinhaAtual->o98_orcparamseq == 1){
        
       $aOperacaoCredito["interna"][] = array(
                                              "identificacao" => $oLinhaAtual->o98_identificacao,
                                              "credor"        => $oLinhaAtual->o98_credor,
                                              "valor"         => $oLinhaAtual->o98_valor
                                              );
       $nTotalInternas +=  $oLinhaAtual->o98_valor;                                       
    }
    if ($oLinhaAtual->o98_orcparamseq == 2){
        
      $aOperacaoCredito["externa"][] = array(
                                              "identificacao" => $oLinhaAtual->o98_identificacao,
                                              "credor"        => $oLinhaAtual->o98_credor,
                                              "valor"         => $oLinhaAtual->o98_valor
                                             );
       $nTotalExternas +=  $oLinhaAtual->o98_valor;                                       
    }
    
    if ($oLinhaAtual->o98_orcparamseq == 3){
        
      $aOperacaoCredito["antecipacao"][] = array(
                                              "identificacao" => $oLinhaAtual->o98_identificacao,
                                              "credor"        => $oLinhaAtual->o98_credor,
                                              "valor"         => $oLinhaAtual->o98_valor
                                             );
      $nTotalOperacoesCredito +=  $oLinhaAtual->o98_valor;                                       
    }
  }
}
$sTodasInstit = null;
$rsInstit =  pg_query("select codigo from db_config");
for ($xinstit=0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {
  db_fieldsmemory($rsInstit, $xinstit);
  $sTodasInstit .= $codigo . ($xinstit==pg_num_rows($rsInstit)-1?"":",");
}
//ano corrente
$nTotalRcl  = calcula_rcl2($anousu,$dt_ini,$dt_fim,$sTodasInstit, false,27);
//ano anterior
$nTotalRcl += calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fim_ant,$sTodasInstit, false,27,$dt_fim);

if (!isset($arqinclude)) {

//totais das variaveis
  if ( $nTotalRcl == 0 || $oOrcParamRelopcre->numrows == 0 ) { 
    db_redireciona("db_erros.php?db_erro=Período sem movimentação.&fechar=true");
  }

}
 
$res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(32,str_replace('-',',',$db_selinstit),$periodo));
if ($clconrelinfo->numrows > 0){
  for($i = 0; $i < $clconrelinfo->numrows; $i++){
    db_fieldsmemory($res_variaveis,$i);

    if ($c83_codigo == 364){
      $nValorInternoExterno = $c83_informacao;  
    }

    if ($c83_codigo == 365){
      $nValorAntecipacao = $c83_informacao;  
    }
  }
}
if ($nValorInternoExterno == 0){
  $nValorInternoExterno = '< % >';
}else{
  $nPercValorInterno     = db_formatar(($nTotalRcl*$nValorInternoExterno)/100,"f");
  $nValorInternoExterno .= '%';
}

if ($nValorAntecipacao == 0){
   $nValorAntecipacao = '< % >';
}else{
  $nPercValorAntecipacao  = db_formatar(($nTotalRcl*$nValorAntecipacao)/100,"f"); 
  $nValorAntecipacao     .= '%'; 
}
//linhas do relatório;
$texto[0]  = 'OPERAÇÕES DE CRÉDITO (I)';
$texto[1]  = '  Internas';
$texto[2]  = '  Externas';
$texto[3]  = 'OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA(II)';
$texto[4]  = 'TOTAL DAS OPERAÇÕES DE CRÉDITO (III) = (I + II)';
$texto[5]  = 'RECEITA CORRENTE LIQUIDA - RCL';
$texto[6]  = '% DAS OPERAÇÕES DE CRÉDITO INTERNAS E EXTERNAS SOBRE A RCL (I/RCL)';
$texto[7]  = '% DAS OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA SOBRE A RCL (II/RCL)';
$texto[8]  = "LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO EXTERNAS E INTERNAS {$nValorInternoExterno}";
$texto[9]  = "LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA {$nValorAntecipacao}";
$texto[10] = '';

if (!isset($arqinclude)){
  
  $pdf = new PDF("L", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $pdf->cell(250,$alt,'RGF - ANEXO IV (LRF, art. 55, inciso I, alínea "d" e inciso III alínea "c")','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  $pdf->cell(185,$alt,"EMPRÉSTIMOS E FINANCIAMENTOS",'R',0,"C",0);
  $pdf->cell(90,$alt,"OPERAÇÕES REALIZADAS",'',1,"C",0);
  $pdf->cell(185,$alt,"",'R',0,"R",0);
  
  
  if ($periodo!="1S"&&$periodo!="2S"){
    $period = "Quadrimestre";
  }else{
    $period = "Semestre";
  }
  $pdf->cell(90,$alt,"Até o $period de Referência",'B',1,"C",0);
  $pdf->cell(185,$alt,"",'RB',0,"C",0);
  $pdf->cell(45,$alt,"CREDOR",'BR',0,"C",0);
  $pdf->cell(45,$alt,"VALOR",'LB',1,"C",0);
  
  //linha dos totais de Credito
  $pdf->setfont('arial','',7);
  $pdf->cell(185,$alt,$texto[0],'R',0,"L",0);
  $pdf->cell(45,$alt,"",0,0,"R",0);
  $pdf->cell(45,$alt,db_formatar($nTotalExternas+$nTotalInternas,"f"),'L',1,"R",0);
      
  //linha dos totais de Credito internos
  $pdf->cell(185,$alt,$texto[1],'R',0,"L",0);
  $pdf->cell(45,$alt,"",0,0,"R",0);
  $pdf->cell(45,$alt,db_formatar($nTotalInternas,"f"),'L',1,"R",0);  
  /*
   * Varremos todos os itens que o usuario cadastrou como interna
   */
  for ($iInd = 0; $iInd < count($aOperacaoCredito["interna"]); $iInd++){
    
    setHeaderOP(&$pdf, $period);
    $pdf->cell(185,$alt,"    {$aOperacaoCredito["interna"][$iInd]["identificacao"]}",'R',0,"L",0);
    $pdf->cell(45,$alt,$aOperacaoCredito["interna"][$iInd]["credor"],"R",0,"L",0);
    $pdf->cell(45,$alt,db_formatar($aOperacaoCredito["interna"][$iInd]["valor"],"f"),'L',1,"R",0);
        
  }
//Totais Externa
    
  $pdf->cell(185,$alt,$texto[2],'R',0,"L",0);
  $pdf->cell(45,$alt,"",0,0,"R",0);
  $pdf->cell(45,$alt,db_formatar($nTotalExternas,"f"),'L',1,"R",0);  
  /*
   * Varremos todos os itens que o usuario cadastrou como externa
   */
  for ($iInd = 0; $iInd < count($aOperacaoCredito["externa"]); $iInd++){
    
    setHeaderOP(&$pdf, $period);
    $pdf->cell(185,$alt,"    {$aOperacaoCredito["externa"][$iInd]["identificacao"]}",'R',0,"L",0);
    $pdf->cell(45,$alt,$aOperacaoCredito["externa"][$iInd]["credor"],"R",0,"L",0);
    $pdf->cell(45,$alt,db_formatar($aOperacaoCredito["externa"][$iInd]["valor"],"f"),'L',1,"R",0);
        
  }
   //Totais de antecipacao
  $pdf->cell(185,$alt,$texto[3],'R',0,"L",0);
  $pdf->cell(45,$alt,"","R",0,"",0);
  $pdf->cell(45,$alt,db_formatar($nTotalOperacoesCredito,"f"),'L',1,"R",0);  
  /*
   * Varremos todos os itens que o usuario cadastrou como externa
   */
  for ($iInd = 0; $iInd < count($aOperacaoCredito["antecipacao"]); $iInd++){
    
    setHeaderOP(&$pdf, $period);
    $pdf->cell(185,$alt,"    {$aOperacaoCredito["antecipacao"][$iInd]["identificacao"]}",'R',0,"L",0);
    $pdf->cell(45,$alt,$aOperacaoCredito["antecipacao"][$iInd]["credor"],"R",0,"L",0);
    $pdf->cell(45,$alt,db_formatar($aOperacaoCredito["antecipacao"][$iInd]["valor"],"f"),'L',1,"R",0);
        
  }
   //Totais da operacao de credito
  $pdf->cell(230,$alt,$texto[4],'RTB',0,"L",0);
  $pdf->cell(45,$alt,db_formatar($nTotalOperacoesCredito+$nTotalExternas+$nTotalInternas,"f"),'TBL',1,"R",0);
  
  //Totais da RCL
  $pdf->cell(230,$alt,$texto[5],'RTB',0,"L",0);
  $pdf->cell(45,$alt,db_formatar($nTotalRcl,"f"),'TBL',1,"R",0);  
  
  // % operacoes internas/externas sobre RCL
  $pdf->cell(230,$alt,$texto[6],'RTB',0,"L",0);
  $pdf->cell(45,$alt,db_formatar((($nTotalExternas + $nTotalInternas) / $nTotalRcl)*100,"f"),'TBL',1,"R",0);  
  
   // % operacoes antecipadas RCL
  $pdf->cell(230,$alt,$texto[7],'RTB',0,"L",0);
  $pdf->cell(45,$alt,db_formatar((($nTotalOperacoesCredito)/$nTotalRcl)*100,"f"),'TBL',1,"R",0);
  
  // %  antecipadas RCL
  $pdf->cell(230,$alt,$texto[8],'RTB',0,"L",0);
  $pdf->cell(45,$alt,$nPercValorInterno,'TBL',1,"R",0);
  
  // % operacoes antecipadas RCL
  $pdf->cell(230,$alt,$texto[9],'RTB',0,"L",0);
  $pdf->cell(45,$alt,$nPercValorAntecipacao,'TBL',1,"R",0);
  if ($pdf->pageNo() > 1){
    $pdf->cell(275,$alt,"(".$pdf->pageNo()."/{nb})",0,1,"R");
  }
  
  
  // assinaturas
  $pdf->setfont('arial','',5);
  notasExplicativas(&$pdf, 32, "{$periodo}",195);
  $pdf->ln(20);
  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  
}

$total_operacoes_credito      = 0;
$perc_total_operacoes_credito = 0;

$total_operacoes_credito = $nTotalExternas+$nTotalInternas;
if ($nTotalRcl > 0){
  $perc_total_operacoes_credito	= (($nTotalExternas + $nTotalInternas) / $nTotalRcl)*100;
}

$total_antecipacao_receita = 0;
$perc_antecipacao_receita  = 0;

$total_antecipacao_receita	= $nTotalOperacoesCredito ;
if ($nTotalRcl > 0){
  $perc_antecipacao_receita	= (($nTotalOperacoesCredito)/$nTotalRcl)*100;
}

// Variaveis do relatorio
$valor_int_ext     = 0;
$valor_antecipacao = 0;

$res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(32,str_replace('-',',',$db_selinstit),$periodo));
if ($clconrelinfo->numrows > 0){
  for($i = 0; $i < $clconrelinfo->numrows; $i++){
    db_fieldsmemory($res_variaveis,$i);

    if ($c83_codigo == 364){
      $valor_int_ext = $c83_informacao;  
    }

    if ($c83_codigo == 365){
      $valor_antecipacao = $c83_informacao;  
    }
  }
}
// Fim das Variaveis
$perc_limite_senado_int_ext     = 0;
$perc_limite_senado_antecipacao = 0;

if ($valor_int_ext > 0){
  $perc_limite_senado_int_ext = ($valor_int_ext);
  $limite_senado_int_ext      = (($nTotalRcl*$valor_int_ext)/100);
} else {
  $limite_senado_int_ext = 0;
}

if ($valor_antecipacao > 0){
  $perc_limite_senado_antecipacao = ($valor_antecipacao);
  $limite_senado_antecipacao      = (($nTotalRcl*$valor_antecipacao)/100);
} else {
  $limite_senado_antecipacao = 0;
}

function setHeaderOP(&$pdf, $period){
  
  if ($pdf->getY() > $pdf-> h-35){
    
    $pdf->cell(275,5 ,"Continua (".($pdf->pageNo()+1)."/{nb})","T",1,"R");
    $pdf->addPage();
    $pdf->setfillcolor(235);
    $pdf->setfont('arial','b',7);
    $alt            = 4;
    $pagina         = 1;
    $pdf->setfont('arial','b',7);
    $pdf->cell(250,$alt,'RGF - ANEXO IV (LRF, art. 55, inciso I, alínea "d" e inciso III alínea "c")','B',0,"L",0);
    $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
    $pdf->cell(185,$alt,"EMPRÉSTIMOS E FINANCIAMENTOS",'R',0,"C",0);
    $pdf->cell(90,$alt,"OPERAÇÕES REALIZADAS",'',1,"C",0);
    $pdf->cell(185,$alt,"",'R',0,"R",0);
    $pdf->cell(90,$alt,"Até o $period de Referência",'B',1,"C",0);
    $pdf->cell(185,$alt,"",'RB',0,"C",0);
    $pdf->cell(45,$alt,"CREDOR",'BR',0,"C",0);
    $pdf->cell(45,$alt,"VALOR",'LB',1,"C",0);
    $pdf->cell(275,5 ,"Continuação","B",1,"R");
    
    }  
}
?>