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


include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_conrelinfo_classe.php");
include("classes/db_conrelvalor_classe.php");
include("classes/db_db_config_classe.php");

$clconrelinfo = new cl_conrelinfo;
$classinatura = new cl_assinatura;
$orcparamrel = new cl_orcparamrel;
$cldb_config  = new cl_db_config;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$v_despesa_pessoal = "s";
$v_receita_rcl     = "s";
$v_divida          = "s";
$v_garantias       = "s";
$v_operacoes       = "s";
$v_restos          = "s";

$anousu     = db_getsession("DB_anousu");
$anousu_ant = (db_getsession("DB_anousu")-1);

$orcparamrel = new cl_orcparamrel;

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
  }else{
       $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ', ';
}

//$head2 = "INSTITUIÇÕES : ".$descr_inst;
//$head3 = "RELATÓRIO DE GESTÃO FISCAL";
//$head4 = "DEMONSTRATIVO DOS LIMITES";
//$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

$dt     = data_periodo($anousu,$periodo);
$dt_ini = split("-",$dt[0]);
$dt_fin = split("-",$dt[1]);

$descr_periodo = "PERIODO: ".$dt["texto"];

/*
if ($bimestre == 1){
  $period = '1º QUADRIMESTRE';
}elseif($bimestre == 2){  
  $period = '2º QUADRIMESTRE'; 
}elseif($bimestre == 3){  
  $period = '3º QUADRIMESTRE'; 
} 
*/

$arqinclude = true;
$quadrimestre = substr($periodo,0,1);
$bimestre     = $quadrimestre;
$dtini = "";
$dtfin = "";

if ($quadrimestre == 1) {
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-04-30'; 
  $dt_ini_ant = ($anousu-1).'-05-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($quadrimestre == 2) {
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-08-31';
  $dt_ini_ant = ($anousu-1).'-09-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($quadrimestre == 3) {
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-12-31';
  $dt_ini_ant = $anousu.'-01-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
}

//////////////////////////////////////////////////////////////////////////

// data apresentada na tela 
$dtd1 = split('-',$dt_ini);
$dtd2 = split('-',$dt_fin);

if ($v_receita_rcl=="s") {
  include("con2_lrfreceitacorrente002.php");
}

if ($v_despesa_pessoal=="s") {
  include((db_getsession("DB_anousu")<2007?"con2_lrfdesppessoal002.php":"con2_lrfdesppessoal002_2007.php"));
}

if ($v_divida=="s"){
  include("con2_lrfdivida002.php");
}

if ($v_garantias=="s"){
  include("con2_lrfgarantias002.php");
}

if ($v_operacoes=="s"){
  include("con2_opercredito002.php");
}

//$head4 = "PERÍODO SELECIONADO  $dtd1[2]/$dtd1[1]/$dtd1[0] à  $dtd2[2]/$dtd2[1]/$dtd2[0]  ";

$head1 = "RELATÓRIO DE GESTÃO FISCAL";
$head2 = "DEMONSTRATIVO DOS LIMITES";
$head3 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head4 = $descr_periodo;
if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "";

$where = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";

$where = "  c61_instit in (".str_replace('-',', ',$db_selinstit).") "; 

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->addpage();
$total = 0;
$alt   = 4;

if ($v_despesa_pessoal=="s"){
  
  $pdf->cell(110,$alt,"DESPESA COM PESSOAL",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Total da Despesa com Pessoal para fins de apuração do Limite - TDP",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_despesa_pessoal_limites,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($total_rcl_limites,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Máximo (incisos I, II e III, art. 20 da LRF - <%>)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($limite_maximo_valor,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_maximo,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Prudencial (§ único, art. 22 da LRF) - <%>",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($limite_prudencial_valor,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_prudencial,'f'),'B',1,"R",0);
  
  $pdf->Ln();
  
}

if ($v_divida=="s"){
  
  $pdf->cell(110,$alt,"DIVIDA",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Dívida Consolidada Liquida",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_divida,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($percdclsobrercl,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido por Resolução do Senado Federal",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_divida, 'f'),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar(120,'f'),'B',1,"R",0);
  
  $pdf->Ln();
  
}

if ($v_garantias=="s"){
  
  $pdf->cell(110,$alt,"GARANTIAS DE VALORES",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Total das Garantias",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($garantiascondedidas,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($garantiasrcl,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido por Resolução do Senado Federal",'RB',0,'L',0);

  if ($periodo == "1Q"){
    $limite_senado = $limite_senado1;
  } else if ($periodo == "2Q"){
    $limite_senado = $limite_senado2;
  } else if ($periodo == "3Q"){
    $limite_senado = $limite_senado3;
  } else if ($periodo == "1S"){  
    $limite_senado = $limite_senado1;
  } else if ($periodo == "2S"){
    $limite_senado = $limite_senado2;
  }

  $pdf->cell(40,$alt,db_formatar($limite_senado,"f"),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado,"f"),'B',1,"R",0);
  
  $pdf->Ln();
	
}

if ($v_operacoes=="s"){
  
  $pdf->cell(110,$alt,"OPERAÇÕES DE CRÉDITO",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Operações de Crédito Internas e Externas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_operacoes_credito,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_total_operacoes_credito,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Operações de Crédito por Antecipação de Receita",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_antecipacao_receita,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_antecipacao_receita,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido pelo Senado Federal para Operações de Credito Internas e Externas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_senado_int_ext,"f"),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado_int_ext,"f"),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido pelo Senado Federal para Operações de Credito por Antecipação da Receita",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_senado_antecipacao,"f"),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado_antecipacao,"f"),'B',1,"R",0);
  
}

if ($v_restos =="s"){
  
  $pdf->Ln();

  $pdf->cell(110,$alt,"",'TR',0,'C',0);
  $pdf->cell(40,$alt,"INSCRIÇÃO EM RESTOS",'TR',0,"C",0);
  $pdf->cell(40,$alt,"SUFICIÊNCIA ANTES DA",'T',1,"C",0);

  $pdf->cell(110,$alt,"RESTOS A PAGAR",'R',0,'C',0);
  $pdf->cell(40,$alt,"A PAGAR NÃO",'R',0,"C",0);
  $pdf->cell(40,$alt,"INSCRIÇÃO EM RESTOS A",'',1,"C",0);

  $pdf->cell(110,$alt,"",'BR',0,'C',0);
  $pdf->cell(40,$alt,"PROCESSADOS",'BR',0,"C",0);
  $pdf->cell(40,$alt,"A PAGAR NÃO PROCESSADOS",'B',1,"C",0);

// Variaveis do relatorio
$total_inscricao_rp_nao_processados            = 0;
$suficiencia_antes_incricao_rp_nao_processados = 0;

$res_variaveis     = $clconrelinfo->sql_record($clconrelinfo->sql_query_file(null,"c83_codigo,c83_variavel","c83_codigo","c83_codrel = 99999 and c83_anousu = ".db_getsession("DB_anousu")));
if ($clconrelinfo->numrows > 0){
  for($i = 0; $i < $clconrelinfo->numrows; $i++){
    db_fieldsmemory($res_variaveis,$i);

    $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = $c83_codigo and c83_periodo = '$periodo' and c83_instit in (".str_replace("-",",",$db_selinstit).")"));
    if ($clconrelvalor->numrows > 0){
      db_fieldsmemory($res_valor,0);
      if ($c83_codigo == 299){
        $total_inscricao_rp_nao_processados = $c83_informacao;  
      }

      if ($c83_codigo == 300){
        $suficiencia_antes_incricao_rp_nao_processados = $c83_informacao;  
      }
    }
  }
}
// Fim das Variaveis

  $pdf->cell(110,$alt,"Valor apurado nos demonstrativos respectivos",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_inscricao_rp_nao_processados,'f'),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($suficiencia_antes_incricao_rp_nao_processados,'f'),"B",1,"R",0);
  
}
$pdf->cell(110,$alt,"FONTE: ",'',0,'L',0);
$pdf->Ln(15);

$pdf->setfont('arial','',6);

// assinaturas

assinaturas(&$pdf,&$classinatura,'GF');

$pdf->Output();

?>