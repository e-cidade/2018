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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_orcparamrel_classe.php"));
include(modification("classes/db_empresto_classe.php"));
include(modification("classes/db_empempenho_classe.php"));
include(modification("classes/db_conrelinfo_classe.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$oDaoConrelInfo  = new cl_conrelinfo();
$classinatura    = new cl_assinatura;
$clempresto      = new cl_empresto;
$descr_receita   = "DEDUÇÕES DA RECEITA CORRENTE";
$xinstit         = split("-",$db_selinstit);
$resultinst      = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst      = '';
$xvirg           = '';
$consolidado     = false;
$flag_abrev      = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  
  db_fieldsmemory($resultinst,$xins);
  if ($xvirg == ',') {
    $consolidado = true;
  }  

  if (strlen(trim($nomeinstabrev)) > 0) {
    
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ',';
}

$head3 = "BALANÇO ORCAMENTÁRIO";
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu");

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,140);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "ANEXO 12 - ".strtoupper(db_mes($mes)) ;


$anousu = db_getsession("DB_anousu");

$dataini = db_getsession("DB_anousu").'-'.'01'.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));

$somatorio_receita_ini=0;
$somatorio_receita_exec=0;
$somatorio_despesa_ini=0;
$somatorio_despesa_exec=0;
$somatorio_despesa_original = 0;


// busca no balancete de verificação a execução passiva das interferencias
$saldo_interferencia_ativa   = 0;
$saldo_interferencia_passiva = 0;
$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).")  ";
$result_balancete = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);
for($i=0;$i<pg_numrows($result_balancete);$i++){
   db_fieldsmemory($result_balancete,$i);
   if (substr($estrutural,0,7)=='5120000'){
       $saldo_interferencia_passiva += $saldo_final;
   }   
 
   if (substr($estrutural,0,7)=='6120000'){
       $saldo_interferencia_ativa += $saldo_final;
   }   
   
} 

// balancete de receita
$db_filtro = ' o70_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dataini,$datafin);
//db_criatabela($result_rec);
//exit;

//variaveis da previsão da receita
$receita_tributaria[0]       = 0;
$receita_contribuicoes[0]    = 0;
$receita_patrimonial[0]      = 0; 
$receita_agropecuaria[0]     = 0;
$receita_industrial[0]       = 0;
$receita_servicos[0]         = 0;
$transf_correntes[0]         = 0;
$outras_receitas_correntes[0]= 0;
$operacoes_credito[0]        = 0;
$alienacao_bens[0]           = 0;
$amortizacao_emprestimos[0]  = 0;
$transf_capital[0]           = 0;
$outras_receitas_capital[0]  = 0; 
$deducao[0]                  = 0;
// variaveis da arrecadação
$receita_tributaria[1]       = 0;
$receita_contribuicoes[1]    = 0;
$receita_patrimonial[1]      = 0; 
$receita_agropecuaria[1]     = 0;
$receita_industrial[1]     = 0;
$receita_servicos[1]         = 0;
$transf_correntes[1]         = 0;
$outras_receitas_correntes[1]= 0;
$operacoes_credito[1]        = 0;
$alienacao_bens[1]           = 0;
$amortizacao_emprestimos[1]  = 0;
$transf_capital[1]           = 0;
$outras_receitas_capital[1]  = 0; 
$deducao[1]                  = 0;

$receita_contr_intra_orcamentaria[0]   = "RECEITA DE CONTR. INTRA-ORÇAM.";
$receita_contr_intra_orcamentaria[1]   = 0;
$receita_contr_intra_orcamentaria[2]   = 0;
$receita_contr_intra_orcamentaria[3]   = "RECEITAS INTRA-ORÇAMENTÁRIAS";

$receita_outras_intra_orcamentarias[0] = "OUTRAS RECEITAS INTRA-ORÇAM.";
$receita_outras_intra_orcamentarias[1] = 0;
$receita_outras_intra_orcamentarias[2] = 0;

//db_criatabela($result_rec); exit;

$lIndustrial = false;

for ($i=0;$i<pg_numrows($result_rec);$i++){
	
    db_fieldsmemory($result_rec,$i);   
    
    if ($tipoprevisao == 1) {
    	$nValorInicial = $saldo_inicial;
    } else {
    	$nValorInicial = $saldo_inicial_prevadic;
    }
    
    $controle=false;
    // receitas correntes
    if ($o57_fonte=='411000000000000' && $o70_codigo == 0){
       $receita_tributaria[0]+= $nValorInicial;
       $receita_tributaria[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte =='412000000000000' && $o70_codigo == 0){      
       $receita_contribuicoes[0]+= $nValorInicial;   
       $receita_contribuicoes[1]+= $saldo_arrecadado_acumulado;   
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte  =='413000000000000' && $o70_codigo == 0){
       $receita_patrimonial[0]+= $nValorInicial;
       $receita_patrimonial[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte == '414000000000000' && $o70_codigo == 0){
       $receita_agropecuaria[0]+= $nValorInicial;
       $receita_agropecuaria[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }   
    if ($o57_fonte == '415000000000000' && $o70_codigo == 0){
       $lIndustrial = true;    	
       $receita_industrial[0]+= $nValorInicial;
       $receita_industrial[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }    
    if ($o57_fonte == '416000000000000' && $o70_codigo == 0){
       $receita_servicos[0]+= $nValorInicial;
       $receita_servicos[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte =='417000000000000' && $o70_codigo == 0){
       $transf_correntes[0]+= $nValorInicial;
       $transf_correntes[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte =='419000000000000' && $o70_codigo == 0){
       // se não entrou em outras receitas, sendo rec. corrente
       $outras_receitas_correntes[0]+= $nValorInicial;
       $outras_receitas_correntes[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }     
    // receitas de capital
    if ($o57_fonte =='421000000000000' && $o70_codigo == 0){
       $operacoes_credito[0]+= $nValorInicial;
       $operacoes_credito[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte == '422000000000000' && $o70_codigo == 0){
       $alienacao_bens[0]+= $nValorInicial;
       $alienacao_bens[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte =='423000000000000' && $o70_codigo == 0){
       $amortizacao_emprestimos[0]+= $nValorInicial;
       $amortizacao_emprestimos[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte == '424000000000000' && $o70_codigo == 0){
       $transf_capital[0]+= $nValorInicial;
       $transf_capital[1]+= $saldo_arrecadado_acumulado;
       $controle=true;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }  
    if ($o57_fonte == '425000000000000' && $o70_codigo == 0){
       $outras_receitas_capital[0]+= $nValorInicial;
       $outras_receitas_capital[1]+= $saldo_arrecadado_acumulado;
       
       $somatorio_receita_ini  += $nValorInicial;
       $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }     

    // deduções
    $flag_ok = false;
    if ($anousu > 2007) {
      
      if ($o57_fonte == "910000000000000" || $o57_fonte == "920000000000000" || 
          $o57_fonte == "970000000000000" || $o57_fonte == "980000000000000") {
        $flag_ok = true;
      }
    } else {

      $estrut = substr($o57_fonte,0,2)."0000000000000";
      if (db_conplano_grupo($anousu,$estrut,9001)==true){
        $flag_ok = true;
      }
    }

    $descr_receita = "DEDUÇÕES DA RECEITA ";
    if ($flag_ok == true){   // 49 e 9172
      $estrut     = "910000000000000, 972000000000000"; 
      //$estrut     = substr($o57_fonte,0,1)."00000000000000"; 
      $sql_estrut = "select o57_descr from orcfontes where cast(o57_fonte as bigint) in($estrut) limit 1";
      $result_estrut = @pg_query($sql_estrut);
      if (@pg_numrows($result_estrut) > 0){
        
        //$descr_receita = pg_result($result_estrut,0,"o57_descr");
      }
        $deducao[0] += $saldo_inicial;
        $deducao[1] += $saldo_arrecadado_acumulado;
        //echo "aqui.saldoini {$saldo_inicial}  saldo_acumulado {$saldo_arrecadado_acumulado}<br>";
        $somatorio_receita_ini  -= abs($saldo_inicial);
        $somatorio_receita_exec -= abs($saldo_arrecadado_acumulado);
    }

    if ($o57_fonte == "472100000000000"){
      $receita_contr_intra_orcamentaria[1] += $saldo_inicial;
      $receita_contr_intra_orcamentaria[2] += $saldo_arrecadado_acumulado;
       
      $somatorio_receita_ini  += $saldo_inicial;
      $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }

    if ($o57_fonte == "479000000000000" || $o57_fonte == "472301000000000") {
//    echo $o57_fonte." - ".$saldo_inicial." - ".$saldo_arrecadado_acumulado. "<br>";
     $receita_outras_intra_orcamentarias[1] += $saldo_inicial;
      $receita_outras_intra_orcamentarias[2] += $saldo_arrecadado_acumulado;
       
      $somatorio_receita_ini  += $saldo_inicial;
      $somatorio_receita_exec += $saldo_arrecadado_acumulado;
    }
}

$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_desp = db_dotacaosaldo(8,1,4,true,$sele_work,$anousu,$dataini,$datafin,8, 0);
//db_criatabela($result_desp); exit;

// variaveis da previsão da despesa
$m_creditos_orcamentario[0]= 0;
$m_creditos_especial    [0]= 0; 
$m_creditos_extra       [0]= 0; 
// variaveis da fixação da despesa
$m_creditos_orcamentario[1]= 0;
$m_creditos_especial    [1]= 0; 
$m_creditos_extra       [1]= 0; 

$reserva_contingencia = 0;
//db_criatabela($result_desp);exit;
for ($i=0;$i<pg_numrows($result_desp);$i++) {
	
    db_fieldsmemory($result_desp,$i);   

    if ($tipofixacao == 1) {
      $nValorDespesaInicial = $dot_ini;
    } else {
      $nValorDespesaInicial = (($dot_ini + $suplementado_acumulado) - $reduzido_acumulado);
    }
    
    if ($o58_coddot > 0) {

//    echo("$i - elemento: $o58_elemento - dotini: " . $dot_ini . " - creditos_especial_1: " . $m_creditos_especial[1] . " - emp: " . $empenhado_acumulado . " - anu: " . $anulado_acumulado . "<br>");

    if (substr($o58_elemento,0,2)=='39' || substr($o58_elemento,0,2)=='37'){

	  $reserva_contingencia += $nValorDespesaInicial;     
               
    } 
    //else {  

      $m_creditos_orcamentario[0] += $nValorDespesaInicial - ($especial_acumulado);
      $m_creditos_especial[0]     += $especial_acumulado;
      if ($dot_ini > 0){ 
         $m_creditos_orcamentario[1] += $empenhado_acumulado-$anulado_acumulado;
      } else {
         $m_creditos_especial[1]     += $empenhado_acumulado-$anulado_acumulado;
      }	 
    //}
    
    // somatorio
    $somatorio_despesa_ini  += $nValorDespesaInicial;
    $somatorio_despesa_exec += $empenhado_acumulado-$anulado_acumulado;

    $somatorio_despesa_original += $nValorDespesaInicial;

    }
}

//------------------//-------------------//---------------------

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$alt            = 3;
$pagina         = 1;
$maislinha      = 0;

$pdf->addpage("L");
$pdf->setfont('arial','b',7);
$pdf->cell(135,$alt,"RECEITA ORÇAMENTÁRIA",0,0,"C",0);
$pdf->cell(135,$alt,"DESPESA ORÇAMENTÁRIA",0,1,"C",0);

// RECEITA E DESPERA ORÇAMENTARIA
$pdf->cell(63,$alt,"TÍTULO","TB",0,"C",0);
$pdf->cell(25,$alt,"PREVISÃO","TB",0,"C",0);
$pdf->cell(25,$alt,"EXECUÇÃO","TB",0,"C",0);
$pdf->cell(25,$alt,"DIFERENÇA","TB",0,"C",0);

$pdf->cell(63,$alt,"TÍTULO","TB",0,"C",0);
$pdf->cell(25,$alt,"FIXAÇÃO","TB",0,"C",0);
$pdf->cell(25,$alt,"EXECUÇÃO","TB",0,"C",0);
$pdf->cell(25,$alt,"DIFERENÇA","TB",1,"C",0);

$pdf->setfont('arial','',7);

// $pdf->ln();
$pdf->cell(63,$alt,"RECEITAS CORRENTES","0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);

//$pdf->cell(63,$alt,"DESPESAS CORRENTES","0",0,"L",0);
$pdf->cell(63,$alt,"","0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",1,"C",0);

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Receita Tributária","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_tributaria[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_tributaria[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_tributaria[0]-$receita_tributaria[1],'f'),"0",0,"R",0);
$pdf->cell(63,$alt,"CREDITOS ORÇAMENTARIOS","0",0,"L",0);
$pdf->ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Receita de Contribuições","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_contribuicoes[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_contribuicoes[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_contribuicoes[0]-$receita_contribuicoes[1],'f'),"0",0,"R",0);
$pdf->cell(63,$alt," E SUPLEMENTARES","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_orcamentario[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_orcamentario[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_orcamentario[0]-$m_creditos_orcamentario[1],'f'),"0",0,"R",0);

$pdf->ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Receita Patrimonial","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_patrimonial[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_patrimonial[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_patrimonial[0]-$receita_patrimonial[1],'f'),"0",0,"R",0);
$pdf->ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Receita Agropecuária","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_agropecuaria[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_agropecuaria[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_agropecuaria[0]-$receita_agropecuaria[1],'f'),"0",0,"R",0);
$pdf->cell(63,$alt,"CRÉDITOS ESPECIAIS","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_especial[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_especial[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_especial[0]-$m_creditos_especial[1],'f'),"0",0,"R",0);
$pdf->ln();

if ($lIndustrial == true) {
  $pdf->cell(5,$alt,"","0",0,"L",0);
  $pdf->cell(58,$alt,"Receita Industrial","0",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($receita_industrial[0],'f'),"0",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($receita_industrial[1],'f'),"0",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($receita_industrial[0]-$receita_industrial[1],'f'),"0",0,"R",0);
  $pdf->ln();
}

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Receita de Serviços","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_servicos[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_servicos[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_servicos[0]-$receita_servicos[1],'f'),"0",0,"R",0);
$pdf->ln();


$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Transferências Correntes","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($transf_correntes[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($transf_correntes[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($transf_correntes[0]-$transf_correntes[1],'f'),"0",0,"R",0);
$pdf->ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Outras Receitas Correntes","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_correntes[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_correntes[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_correntes[0]-$outras_receitas_correntes[1],'f'),"0",0,"R",0);
$pdf->cell(63,$alt,"CRÉDITOS EXTRAORDINÁRIOS","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_extra[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_extra[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_creditos_extra[0]-$m_creditos_extra[1],'f'),"0",0,"R",0);
$pdf->ln();

$pdf->ln();
$pdf->cell(63,$alt,"RECEITAS DE CAPITAL","0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Operações de Crédito","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($operacoes_credito[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($operacoes_credito[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($operacoes_credito[0]-$operacoes_credito[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Alienação de Bens","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($alienacao_bens[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($alienacao_bens[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($alienacao_bens[0]-$alienacao_bens[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Amortização de Empréstimos","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($amortizacao_emprestimos[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($amortizacao_emprestimos[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($amortizacao_emprestimos[0]-$amortizacao_emprestimos[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Transferências de Capital","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($transf_capital[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($transf_capital[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($transf_capital[0]-$transf_capital[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Outras Receitas de Capital","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_capital[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_capital[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_capital[0]-$outras_receitas_capital[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->Ln();
$pdf->cell(63,$alt,$receita_contr_intra_orcamentaria[3],"0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(63,$alt,"","0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);

$pdf->Ln();
$pdf->cell( 5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,$receita_contr_intra_orcamentaria[0],"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_contr_intra_orcamentaria[1],"f"),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_contr_intra_orcamentaria[2],"f"),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_contr_intra_orcamentaria[1]-$receita_contr_intra_orcamentaria[2],"f"),"0",0,"R",0);
$pdf->Ln();

$pdf->cell( 5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,$receita_outras_intra_orcamentarias[0],"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_outras_intra_orcamentarias[1],"f"),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_outras_intra_orcamentarias[2],"f"),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_outras_intra_orcamentarias[1]-$receita_outras_intra_orcamentarias[2],"f"),"0",0,"R",0);
$pdf->Ln();

$pdf->Ln();
$pdf->cell(63,$alt,"DEDUÇÕES DA RECEITA ","0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(63,$alt,"","0",0,"L",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->cell(25,$alt,"","0",0,"C",0);
$pdf->ln();

$pdf->cell(5,$alt,"","0",0,"L",0);
//$pdf->cell(58,$alt,"Dedução para Formação do FUNDEF","0",0,"L",0);
$pdf->cell(58,$alt,$descr_receita,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($deducao[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($deducao[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar(($deducao[0])-($deducao[1]),'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->ln();
$pdf->cell(63,$alt,"SOMATÓRIO","TB",0,"L",0);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_ini,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_exec,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_ini-$somatorio_receita_exec,'f'),"TB",0,"R",0);
$pdf->cell(63,$alt,"SOMATORIO","TB",0,"L",0);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_ini,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_exec,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_ini-$somatorio_despesa_exec,'f'),"TB",0,"R",0);
$pdf->ln();

// calculo das diferenças
$soma_rec =$somatorio_receita_ini-$somatorio_receita_exec;
$soma_desp=$somatorio_despesa_ini-$somatorio_despesa_exec;

$pdf->ln();
$pdf->cell(63,$alt,"DEFICITS","TB",0,"L",0);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_ini<$somatorio_despesa_ini?($somatorio_despesa_ini-$somatorio_receita_ini):0,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_exec<$somatorio_despesa_exec?($somatorio_despesa_exec-$somatorio_receita_exec):0,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($soma_rec<$soma_desp?($soma_desp-$soma_rec):0,'f'),"TB",0,"R",0);
$pdf->cell(63,$alt,"SUPERAVITS","TB",0,"L",0);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_ini<$somatorio_receita_ini?($somatorio_receita_ini-$somatorio_despesa_ini):0,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_exec<$somatorio_receita_exec?($somatorio_receita_exec-$somatorio_despesa_exec):0,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($soma_desp<$soma_rec?($soma_rec-$soma_desp):0,'f'),"TB",0,"R",0);
$pdf->ln();


$tot_rec_ini = $somatorio_receita_ini+($somatorio_receita_ini<$somatorio_despesa_ini?($somatorio_despesa_ini-$somatorio_receita_ini):0);
$tot_rec_exe = $somatorio_receita_exec+($somatorio_receita_exec<$somatorio_despesa_exec?($somatorio_despesa_exec-$somatorio_receita_exec):0);
$tot_rec_dif = ($somatorio_receita_ini-$somatorio_receita_exec)+($soma_rec<$soma_desp?($soma_desp-$soma_rec):0);

$tot_desp_ini = $somatorio_despesa_ini+($somatorio_despesa_ini<$somatorio_receita_ini?($somatorio_receita_ini-$somatorio_despesa_ini):0);
$tot_desp_exe = $somatorio_despesa_exec+($somatorio_despesa_exec<$somatorio_receita_exec?($somatorio_receita_exec-$somatorio_despesa_exec):0);
$tot_desp_dif = ($somatorio_despesa_ini-$somatorio_despesa_exec)+($soma_desp<$soma_rec?($soma_rec-$soma_desp):0);

$pdf->ln();
$pdf->cell(63,$alt,"TOTAL","TB",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_rec_ini,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_rec_exe,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_rec_dif,'f'),"TB",0,"R",0);
$pdf->cell(63,$alt,"TOTAL","TB",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_desp_ini,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_desp_exe,'f'),"TB",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_desp_dif,'f'),"TB",0,"R",0);
$pdf->ln();

if ($consolidado==false){
  $sele_work = str_replace('-',', ',$db_selinstit);
 // só entra aqui quando foi selecionado uma única instituição
 $instit = $db_selinstit;
 $i_ativa = 0;
 $i_passiva = 0;
 $i_passiva_exec = 0; 
 
/* 
  echo $somatorio_receita_ini.'<br>';
  echo $somatorio_despesa_original.'<br>';
  echo $reserva_contingencia.'<br>';
*/  
$i_passiva = $somatorio_receita_ini - $somatorio_despesa_original + $reserva_contingencia;

 
 $i_passiva_exec = $saldo_interferencia_passiva;
 $pdf->ln();
 $nInterferenciasAtivas   = $oDaoConrelInfo->getValorVariavel(470, $sele_work, "6B");
 $nInterferenciasPassivas = $oDaoConrelInfo->getValorVariavel(471, $sele_work, "6B");
 $nDiferencaInterAtivas   = 0;
 $nDiferencaInterPassivas = 0;
 if ($instit == 2 || $instit == 3  ){
 	
   $pdf->cell(63,$alt,"INTEREFÊNCIAS ATIVAS","0",0,"L",0);
   $pdf->cell(25,$alt,db_formatar($nInterferenciasAtivas,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar($saldo_interferencia_ativa,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar(($nInterferenciasAtivas-$saldo_interferencia_ativa),'f'),"0",0,"R",0);
   $nDiferencaInterAtivas = ($i_passiva-$nInterferenciasAtivas);
   $i_ativa = $reserva_contingencia;
   
   $pdf->cell(63,$alt,"INTERFERÊNCIAS PASSIVAS","0",0,"L",0);
   $pdf->cell(25,$alt,db_formatar(0,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar(0,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar(0,'f'),"0",0,"R",0);
   
 } else {
   
   $pdf->cell(63,$alt,"INTEREFÊNCIAS ATIVAS","0",0,"L",0);
   $pdf->cell(25,$alt,db_formatar($nInterferenciasAtivas,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar($saldo_interferencia_ativa,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar( ($nInterferenciasAtivas-$saldo_interferencia_ativa),'f'),"0",0,"R",0);
   $nDiferencaInterAtivas = ($nInterferenciasAtivas);
   
 }  

 if ($instit == 1 || $instit == 2 ) {
   
   if ($instit == 2) {

     $i_ativa = 0;
     $i_passiva = 0;
     $i_passiva_exec = 0;
     $saldo_interferencia_passiva = 0;
     
   }
   $pdf->cell(63,$alt,"INTERFERÊNCIAS PASSIVAS","0",0,"L",0);
   $pdf->cell(25,$alt,db_formatar($nInterferenciasPassivas,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar($saldo_interferencia_passiva,'f'),"0",0,"R",0);
   $pdf->cell(25,$alt,db_formatar($nInterferenciasPassivas-$saldo_interferencia_passiva ,'f'),"0",0,"R",0);
   $nDiferencaInterPassivas = $nInterferenciasPassivas-$saldo_interferencia_passiva;
 }
 $pdf->ln();

 $deficit   = 0;
 $superavit = 0;  
 if ($nInterferenciasAtivas > $nInterferenciasPassivas){
   $superavit = $nInterferenciasAtivas - $nInterferenciasPassivas;
 }  else {
   $deficit =   $nInterferenciasPassivas - $nInterferenciasAtivas;
 }  
 $pdf->ln();
 $pdf->cell(63,$alt,"DEFICITS","0",0,"L",0);
 $pdf->cell(25,$alt,db_formatar($deficit,'f'),"0",0,"R",0);
 $pdf->cell(25,$alt,db_formatar($i_passiva_exec,'f'),"0",0,"R",0);
 $pdf->cell(25,$alt,db_formatar($deficit-$i_passiva_exec,'f'),"0",0,"R",0);
 $nValorDiferencaDeficit = ($deficit-$i_passiva_exec);
 $pdf->cell(63,$alt,"SUPERAVIT","0",0,"L",0);
 $pdf->cell(25,$alt,db_formatar($superavit,'f'),"0",0,"R",0);
 $pdf->cell(25,$alt,db_formatar(0,'f'),"0",0,"R",0);
 $pdf->cell(25,$alt,db_formatar(0,'f'),"0",0,"R",0);
 $nValorDiferencaSuperavit = ($superavit);
 $pdf->ln();
 
 $pdf->ln();
 $pdf->cell(63,$alt,"TOTAL","TB",0,"L",0);
 $pdf->cell(25,$alt,db_formatar($tot_rec_ini + $nInterferenciasAtivas +$deficit,'f'),"TB",0,"R",0);
 $pdf->cell(25,$alt,db_formatar($tot_rec_exe + $i_passiva_exec,'f'),"TB",0,"R",0);
 //echo "{$nValorDiferencaDeficit}+{$tot_rec_ini} + {$nInterferenciasAtivas} + {$deficit}  - ({$tot_rec_exe}+{$i_passiva_exec})<br>";
 $pdf->cell(25,$alt,db_formatar($tot_rec_ini + $nInterferenciasAtivas + $deficit  - ($tot_rec_exe+$i_passiva_exec),'f'),"TB",0,"R",0);
 $pdf->cell(63,$alt,"TOTAL","TB",0,"L",0);
 $pdf->cell(25,$alt,db_formatar($tot_desp_ini + $nInterferenciasPassivas + $superavit,'f'),"TB",0,"R",0);
 $pdf->cell(25,$alt,db_formatar($tot_desp_exe + $i_passiva_exec,'f'),"TB",0,"R",0);
 //die("{$nValorDiferencaSuperavit}+{$tot_desp_ini} + {$nInterferenciasPassivas} +{$superavit} - ({$tot_desp_exe}+ {$i_passiva_exec})");
 $pdf->cell(25,$alt,db_formatar($tot_desp_ini + $nInterferenciasPassivas +$superavit - ($tot_desp_exe+ $i_passiva_exec),'f'),"TB",1,"R",0);
}
 
$pdf->Ln(2);
$pdf->setfont('arial','',3);
notasExplicativas($pdf,$iCodRel,"2S",190);


// assinaturas

// include(modification("dbforms/db_assinaturas_balancetes.php"));
$pdf->Ln(14);
$pdf->setfont('arial','',6);

assinaturas($pdf, $classinatura,'BG');


$pdf->Output();
?>
