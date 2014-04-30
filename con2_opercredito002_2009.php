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
  include("libs/db_utils.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_orcparamrel_classe.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_orcparamrelopcre_classe.php");
  include_once("classes/db_conrelinfo_classe.php");
  include_once("classes/db_conrelvalor_classe.php");
  include_once("classes/db_orcparamrelopcre_classe.php");
  include_once("classes/db_orcparamelemento_classe.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  $clconrelinfo      = new cl_conrelinfo;
  $clconrelvalor     = new cl_conrelvalor;
  $oOrcParamRelopcre = new cl_orcparamrelopcre;
  $clorcparamelemento = new cl_orcparamelemento();
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

include_once("libs/db_utils.php");

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
$descr_periodo = '';
if ($periodo=="1Q"){
  $period = "JANEIRO A ABRIL DE {$anousu}";
  $descr_periodo = "Quadrimestre";
}elseif($periodo=="2Q"){  
  $period = "JANEIRO A AGOSTO DE {$anousu}";
  $descr_periodo = "Quadrimestre";  
}elseif($periodo=="3Q"){  
  $period = "JANEIRO A DEZEMBRO DE {$anousu}";
  $descr_periodo = "Quadrimestre";  
}elseif($periodo=="1S"){
  $period = "JANEIRO A JUNHO DE {$anousu}";
  $descr_periodo = "Semestre";  
}elseif($periodo=="2S"){
  $period = "JANEIRO A DEZEMBRO DE {$anousu}";
  $descr_periodo = "Semestre";    
}
$head6 = "$period";

$where      = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";


$soma_interna     = 0;
$soma_externa     = 0;
$soma_antecipacao = 0;
$instituicao      = str_replace("-",",",$db_selinstit);
$dt               = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini_plano     = $dt[0];  // data inicial do periodo
$dt_ini           = "{$anousu}-01-01";  // data inicial do periodo
$dt_fim           = $dt[1];  // data final do período
$dt = split('-',$dt_fim);  // mktime -- (mes,caddia,ano)
$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1]+1,"01",$anousu_ant));
$dt_fim_ant = $anousu_ant.'-12-31';
$iCodigoRelatorio = 63;
/**
 * Parametros 
 */

for ($i = 1; $i <=16;$i++) {
  
  $aParametros[$i]["params"]  = $orcparamrel->sql_parametro($iCodigoRelatorio, $i, "f", $instituicao, db_getsession("DB_anousu"));
  $aParametros[$i]["nobim"]   = 0;
  $aParametros[$i]["atebim"]  = 0;
  if ($i == 10) {

    $aParametros[$i]["itens"] = Array();

    for ($j = 0; $j < count($aParametros[$i]["params"]); $j++) {
      
      $sSqlConplano  = "select c60_estrut, c60_descr from conplano where c60_anousu = ".db_getsession("DB_anousu");
      $sSqlConplano .= "   and c60_estrut = {$aParametros[$i]["params"][$j]}";
      $rsConplano    = db_query($sSqlConplano);
      $oConplano     = db_utils::fieldsMemory($rsConplano,0);
      $aItem["descricao"] =  $oConplano->c60_descr;  
      $aItem["nobim"]     = 0;
      $aItem["atebim"]    = 0;
      $aParametros[$i]["itens"][$oConplano->c60_estrut] = $aItem;
      
    }
  }
  
}
$where            = "  c61_instit in (".str_replace('-',', ',$db_selinstit).")   "; 
$rsDadosRelatorio = db_planocontassaldo_matriz($anousu,$dt_ini_plano,$dt_fim,false,$where);
@pg_exec("drop table work_pl");
$iNumRows = pg_num_rows($rsDadosRelatorio);
for ($iParam = 1; $iParam <= 16; $iParam++) {
  
  if ($iParam == 10) {
    
    for ($i = 0; $i < $iNumRows; $i++) {
  
      $oLinhaRelatorio = db_utils::fieldsMemory($rsDadosRelatorio, $i);
      if (count($aParametros[$iParam]["params"]) > 0 && 
          in_array($oLinhaRelatorio->estrutural, $aParametros[$iParam]['params'])) {
        $aParametros[$iParam]["itens"][$oLinhaRelatorio->estrutural]["nobim"] = ($oLinhaRelatorio->saldo_final - 
                                                                                $oLinhaRelatorio->saldo_anterior);
        if ($periodo == "1Q") {
          $aParametros[$iParam]["itens"][$oLinhaRelatorio->estrutural]["nobim"] = $oLinhaRelatorio->saldo_final;                                                                                          
        }
        $aParametros[$iParam]["itens"][$oLinhaRelatorio->estrutural]["atebim"] = $oLinhaRelatorio->saldo_final;                                                                                    
      }
    }
    
  } else {
  
    for ($i = 0; $i < $iNumRows; $i++) {
  
      $oLinhaRelatorio = db_utils::fieldsMemory($rsDadosRelatorio, $i);
      if (count($aParametros[$iParam]["params"]) > 0 && 
          in_array($oLinhaRelatorio->estrutural, $aParametros[$iParam]['params'])) {
        
        
        if ($periodo == "1Q") {
          $aParametros[$iParam]["nobim"] += $oLinhaRelatorio->saldo_final;
        } else {
          $aParametros[$iParam]["nobim"]  += ($oLinhaRelatorio->saldo_final - $oLinhaRelatorio->saldo_anterior);        	
        }
        $aParametros[$iParam]["atebim"] += $oLinhaRelatorio->saldo_final;
      }
    }
  }
}
/**
 * Linhas do relatorio
 */
$aLinhasRelatorio              = array();
$aLinhasRelatorio[0]["label"]  = "SUJEITAS AO LIMITE PARA FINS DE CONTRATAÇÃO (I)";    
$aLinhasRelatorio[1]["label"]  = "    Mobiliária";    
$aLinhasRelatorio[2]["label"]  = "       Interna";    
$aLinhasRelatorio[3]["label"]  = "       Externa";    
$aLinhasRelatorio[4]["label"]  = "    Contratual";    
$aLinhasRelatorio[5]["label"]  = "        Interna";    
$aLinhasRelatorio[6]["label"]  = "            Abertura de Crédito";    
$aLinhasRelatorio[7]["label"]  = "            Aquisição Financiada de Bens e Arrendamento Mercantil Financeiro";    
$aLinhasRelatorio[8]["label"]  = "                Derivadas de PPP";    
$aLinhasRelatorio[9]["label"]  = "                Demais Aquisições Financiadas";    
$aLinhasRelatorio[10]["label"] = "            Antecipação de Receita";    
$aLinhasRelatorio[11]["label"] = "                Pela Venda a Termo de Bens e Serviços";      
$aLinhasRelatorio[12]["label"] = "                Demais Antecipações de Receita";      
$aLinhasRelatorio[13]["label"] = "            Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 29, § 1º)";      
$aLinhasRelatorio[14]["label"] = "            Outras Operações de Crédito";      
$aLinhasRelatorio[15]["label"] = "        Externa";      
$aLinhasRelatorio[15]["itens"] = array();      
$aLinhasRelatorio[16]["label"] = "NÃO SUJEITAS AO LIMITE PARA FINS DE CONTRATAÇÃO (II)";      
$aLinhasRelatorio[17]["label"] = "    Parcelamentos de Dívidas";      
$aLinhasRelatorio[18]["label"] = "        De Tributos";      
$aLinhasRelatorio[19]["label"] = "        De Contribuições Sociais";      
$aLinhasRelatorio[20]["label"] = "            Previdenciárias";      
$aLinhasRelatorio[21]["label"] = "            Demais Contribuições Sociais";      
$aLinhasRelatorio[22]["label"] = "        Do FGTS";      
$aLinhasRelatorio[23]["label"] = "    Melhoria da Administração de Receitas e da Gestão Fiscal, Financeira e Patrimonial ";      
$aLinhasRelatorio[24]["label"] = "    Programa de Iluminação Pública - RELUZ ";      
$aLinhasRelatorio[25]["label"] = "RECEITA CORRENTE LÍQUIDA - RCL ";      
$aLinhasRelatorio[26]["label"] = "TOTAL CONSIDERADO PARA FINS DA APURAÇÃO DO CUMPRIMENTO DO LIMITE = (Ia)";   
$aLinhasRelatorio[27]["label"] = "LIMITE GERAL DEFINIDO POR RESOLUÇÃO DO SENADO FED. PARA AS OPERAÇÕES DE CRÉDITO ";   
$aLinhasRelatorio[27]["label"].= "INTERNAS E EXTERNAS";   
$aLinhasRelatorio[28]["label"] = "OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA ORÇAMENTÁRIA";   
$aLinhasRelatorio[29]["label"] = "LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FED. PARA AS OP. DE CRED POR ";   
$aLinhasRelatorio[29]["label"].= "ANTECIPAÇÃO DA REC. ORÇAMENTÁRIA";   
$aLinhasRelatorio[30]["label"] = "TOTAL CONSIDERADO PARA CONTRATAÇÃO DE NOVAS OPERAÇÕES DE CRÉDITO (III) = (Ia + IIa)";   
for ($i = 0; $i < count($aLinhasRelatorio); $i++) {
  
  $aLinhasRelatorio[$i]["atebim"] = 0;
  $aLinhasRelatorio[$i]["nobim"]  = 0;
  
}
/**
 * calculamos os totais, e vinculamos o valores encontrado nos parametros e 
 * vinculamos as linhas do relatorio
 */

/*
 * Grupo Mobiliaria
 */
$aLinhasRelatorio[2]["nobim"]  = $aParametros[1]["nobim"];
$aLinhasRelatorio[2]["atebim"] = $aParametros[1]["atebim"];
$aLinhasRelatorio[3]["nobim"]  = $aParametros[2]["nobim"];
$aLinhasRelatorio[3]["atebim"] = $aParametros[2]["atebim"];
$aLinhasRelatorio[1]["nobim"]  = $aLinhasRelatorio[3]["nobim"]+$aLinhasRelatorio[2]["nobim"];
$aLinhasRelatorio[1]["atebim"] = $aLinhasRelatorio[3]["atebim"]+$aLinhasRelatorio[2]["atebim"];
/*
 * Grupo Contratual 
 */
$aLinhasRelatorio[6]["nobim"]   = $aParametros[3]["nobim"];
$aLinhasRelatorio[6]["atebim"]  = $aParametros[3]["atebim"];
$aLinhasRelatorio[8]["nobim"]   = $aParametros[4]["nobim"];
$aLinhasRelatorio[8]["atebim"]  = $aParametros[4]["atebim"];
$aLinhasRelatorio[9]["nobim"]   = $aParametros[5]["nobim"];
$aLinhasRelatorio[9]["atebim"]  = $aParametros[5]["atebim"];
$aLinhasRelatorio[7]["nobim"]   = $aLinhasRelatorio[8]["nobim"]+$aLinhasRelatorio[9]["nobim"];
$aLinhasRelatorio[7]["atebim"]  = $aLinhasRelatorio[8]["atebim"]+$aLinhasRelatorio[9]["atebim"];

$aLinhasRelatorio[11]["nobim"]  = $aParametros[6]["nobim"];
$aLinhasRelatorio[11]["atebim"] = $aParametros[6]["atebim"];
$aLinhasRelatorio[12]["nobim"]  = $aParametros[7]["nobim"];
$aLinhasRelatorio[12]["atebim"] = $aParametros[7]["atebim"];
$aLinhasRelatorio[10]["nobim"]  = $aLinhasRelatorio[11]["nobim"]+$aLinhasRelatorio[12]["nobim"];
$aLinhasRelatorio[10]["atebim"] = $aLinhasRelatorio[11]["atebim"]+$aLinhasRelatorio[12]["atebim"];

$aLinhasRelatorio[13]["nobim"]  = $aParametros[8]["nobim"];
$aLinhasRelatorio[13]["atebim"] = $aParametros[8]["atebim"];
$aLinhasRelatorio[14]["nobim"]  = $aParametros[9]["nobim"];
$aLinhasRelatorio[14]["atebim"] = $aParametros[9]["atebim"];
$aLinhasRelatorio[5]["nobim"]   = $aLinhasRelatorio[6]["nobim"]+$aLinhasRelatorio[7]["nobim"]+
                                  $aLinhasRelatorio[13]["nobim"]+$aLinhasRelatorio[14]["nobim"]
                                  +$aLinhasRelatorio[10]["nobim"];
$aLinhasRelatorio[5]["atebim"]  = $aLinhasRelatorio[6]["atebim"]+$aLinhasRelatorio[7]["atebim"]+
                                  $aLinhasRelatorio[13]["atebim"]+$aLinhasRelatorio[14]["atebim"]
                                  +$aLinhasRelatorio[10]["atebim"];                                  

                                  
/*
 * Calculamos o total da linha 16, onde é a soma de todos os estruturais cadastrados para ela.
 */
$aLinhasRelatorio[15]["itens"] = $aParametros[10]["itens"];                                  
foreach ($aParametros[10]["itens"] as $aParametroItem) {
  
  $aLinhasRelatorio[15]["nobim"]  += $aParametroItem["nobim"];
  $aLinhasRelatorio[15]["atebim"] += $aParametroItem["atebim"];
  
}
$aLinhasRelatorio[4]["nobim"]  = $aLinhasRelatorio[5]["nobim"]+ $aLinhasRelatorio[15]["nobim"]; 
$aLinhasRelatorio[4]["atebim"] = $aLinhasRelatorio[5]["atebim"]+ $aLinhasRelatorio[15]["atebim"]; 
$aLinhasRelatorio[0]["nobim"]  = $aLinhasRelatorio[4]["nobim"]+ $aLinhasRelatorio[1]["nobim"]; 
$aLinhasRelatorio[0]["atebim"] = $aLinhasRelatorio[4]["atebim"]+ $aLinhasRelatorio[1]["atebim"];
/*
 * Grupo nao sujeitas
 */
$aLinhasRelatorio[18]["nobim"]  = $aParametros[11]["nobim"];
$aLinhasRelatorio[18]["atebim"] = $aParametros[11]["atebim"];
$aLinhasRelatorio[20]["nobim"]  = $aParametros[12]["nobim"];
$aLinhasRelatorio[20]["atebim"] = $aParametros[12]["atebim"];
$aLinhasRelatorio[21]["nobim"]  = $aParametros[13]["nobim"];
$aLinhasRelatorio[21]["atebim"] = $aParametros[13]["atebim"];
$aLinhasRelatorio[19]["nobim"]  = $aLinhasRelatorio[20]["nobim"]+$aLinhasRelatorio[21]["nobim"];
$aLinhasRelatorio[19]["atebim"] = $aLinhasRelatorio[20]["atebim"]+$aLinhasRelatorio[21]["atebim"];
$aLinhasRelatorio[22]["nobim"]  = $aParametros[14]["nobim"];
$aLinhasRelatorio[22]["atebim"] = $aParametros[14]["atebim"];
$aLinhasRelatorio[23]["nobim"]  = $aParametros[15]["nobim"];
$aLinhasRelatorio[23]["atebim"] = $aParametros[15]["atebim"];
$aLinhasRelatorio[24]["nobim"]  = $aParametros[16]["nobim"];
$aLinhasRelatorio[24]["atebim"]  = $aParametros[16]["atebim"];
$aLinhasRelatorio[17]["nobim"]  = $aLinhasRelatorio[18]["nobim"]+$aLinhasRelatorio[19]["nobim"]+
                                  $aLinhasRelatorio[22]["nobim"];
$aLinhasRelatorio[17]["atebim"] = $aLinhasRelatorio[18]["atebim"]+$aLinhasRelatorio[19]["atebim"]+
                                  $aLinhasRelatorio[22]["atebim"];
$aLinhasRelatorio[16]["nobim"]  = $aLinhasRelatorio[17]["nobim"] + $aLinhasRelatorio[23]["nobim"]+
                                  $aLinhasRelatorio[24]["nobim"];
$aLinhasRelatorio[16]["atebim"] = $aLinhasRelatorio[17]["atebim"]+$aLinhasRelatorio[23]["atebim"]+
                                  $aLinhasRelatorio[24]["atebim"];                                  
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
$sTodasInstit = null;

$rsInstit =  pg_query("select codigo from db_config");
for ($xinstit=0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {
  db_fieldsmemory($rsInstit, $xinstit);
  $sTodasInstit .= $codigo . ($xinstit==pg_num_rows($rsInstit)-1?"":",");
}
$iExercAnt  = (db_getsession('DB_anousu')-1);
$sCodParRel = "59";
  
  // Exclui elementos referente ao exercício anterior;
$clorcparamelemento->o44_anousu    = $iExercAnt;
$clorcparamelemento->o44_codparrel = $sCodParRel;
$clorcparamelemento->excluir($iExercAnt,$sCodParRel);

  // Inclui elemento no exercício anterior com base no atual;
duplicaReceitaaCorrenteLiquida($anousu, 81);
$nTotalRcl  = calcula_rcl2($anousu,$dt_ini,$dt_fim,$sTodasInstit, false, 81);
//ano anterior
$nTotalRcl += calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fim_ant,$sTodasInstit, false, 81, $dt_fim);
$aLinhasRelatorio[25]["nobim"]  = $nTotalRcl;
$aLinhasRelatorio[25]["atebim"] = "-";
$aLinhasRelatorio[26]["nobim"]  = $aLinhasRelatorio[0]["atebim"];
$aLinhasRelatorio[26]["atebim"] = @($aLinhasRelatorio[0]["atebim"]*100)/$nTotalRcl;
$aLinhasRelatorio[27]["atebim"] = $clconrelinfo->getValorVariavel(472,$instituicao,$periodo);
$aLinhasRelatorio[27]["nobim"]  = @(($aLinhasRelatorio[27]["atebim"])*$nTotalRcl)/100;
$aLinhasRelatorio[28]["nobim"]  = $aLinhasRelatorio[10]["atebim"];
$aLinhasRelatorio[28]["atebim"] = @($aLinhasRelatorio[28]["nobim"]*100)/$nTotalRcl;
$aLinhasRelatorio[29]["atebim"] = $clconrelinfo->getValorVariavel(474,$instituicao,$periodo);
$aLinhasRelatorio[29]["nobim"]  = @(($aLinhasRelatorio[29]["atebim"])*$nTotalRcl)/100;
$aLinhasRelatorio[30]["nobim"]  = ($aLinhasRelatorio[0]["atebim"]+$aLinhasRelatorio[16]["atebim"]);
$aLinhasRelatorio[30]["atebim"] = @($aLinhasRelatorio[30]["nobim"]*100)/$nTotalRcl;
if (!isset($arqinclude)) {

//totais das variaveis

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
if (!isset($arqinclude)){
  
  $pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $pdf->cell(165,$alt,'RGF - ANEXO IV (LRF, art. 55, inciso I, alínea "d" e inciso III alínea "c")','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  $pdf->cell(100,$alt,"",'RT',0,"C",0);
  $pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
  $pdf->cell(100,$alt,"OPERAÇÕES DE CRÉDITO",'R',0,"C",0);
  $pdf->cell(45,$alt,"No ".$descr_periodo,'LT',0,"C",0);
  $pdf->cell(45,$alt,"Até o ".$descr_periodo,'LT',1,"C",0);
  $pdf->cell(100,$alt,"",'RB',0,"C",0);
  $pdf->cell(45,$alt,"de Referência",'LB',0,"C",0);
  $pdf->cell(45,$alt,"de Referência (a)",'LB',1,"C",0);
  
  /*
   * Inicio do Corpo do relatorio
   */
  $pdf->setfont('arial','',7);
  for ($i = 0; $i < 25; $i++) {
    
    $pdf->cell(100,$alt,$aLinhasRelatorio[$i]["label"],"R",0,"L");
    $pdf->cell(45,$alt,db_formatar($aLinhasRelatorio[$i]["nobim"],"f"),"R",0,"R");
    $pdf->cell(45,$alt,db_formatar($aLinhasRelatorio[$i]["atebim"],"f"),"L",1,"R");
    if ($i == 15) {
      foreach ($aLinhasRelatorio[15]["itens"] as $item) {

        
        $pdf->cell(100,$alt,"            ".ucfirst(strtolower($item["descricao"])),"R",0,"L");
        $pdf->cell(45,$alt,db_formatar($item["nobim"],"f"),"R",0,"R");
        $pdf->cell(45,$alt,db_formatar($item["atebim"],"f"),"L",1,"R");
           
      }
    }
  }
  $pdf->cell(190,$alt,"","TB",1,"L");
  $pdf->setfont('arial','b',7);
  $pdf->cell(130,$alt,"APURAÇÃO DO CUMPRIMENTO DOS LIMITES","TBR",0,"C");
  $pdf->cell(30,$alt,"VALOR","TBL",0,"C");
  $pdf->cell(30,$alt,"% SOBRE RCL","TBL",1,"C");
  
  for ($i = 25; $i < 30; $i++) {
    
    $pdf->setfont('arial','',6);
    $pdf->cell(130,$alt,$aLinhasRelatorio[$i]["label"],"TBR",0,"L");
    $pdf->setfont('arial','',7);
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$i]["nobim"],"f"),"TBR",0,"R");
    if ($i == 25) {
      $pdf->cell(30,$alt, $aLinhasRelatorio[$i]["atebim"],"TBL",1,"C");
    } else {
      $pdf->cell(30,$alt, db_formatar($aLinhasRelatorio[$i]["atebim"],'f'),"TBL",1,"R");
    }
    
  }
  $pdf->cell(190,$alt,"","TB",1,"L");
  $pdf->setfont('arial','',6);
  $pdf->cell(130,$alt,$aLinhasRelatorio[30]["label"],"TBR",0,"L");
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt, db_formatar($aLinhasRelatorio[30]["nobim"],"f"),"TBR",0,"R");
  $pdf->cell(30,$alt, db_formatar($aLinhasRelatorio[30]["atebim"],"f"),"TBL",1,"R");
  
  $pdf->ln();
  // ----------------------------------------------------------------
  notasExplicativas(&$pdf, 63, $periodo,190); 
  
  $pdf->Ln(5);
  
  // assinaturas
  $pdf->setfont('arial','',5);
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
?>