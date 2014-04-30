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
include("libs/db_sql.php");
include("libs/db_liborcamento.php");
include("fpdf151/assinatura.php");
include("classes/db_orcparamrel_classe.php");
include("libs/db_libcontabilidade.php");
include("libs/db_libtxt.php");
include("dbforms/db_funcoes.php");

$anousu = db_getsession("DB_anousu");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$classinatura = new cl_assinatura;
$orcparamrel = new cl_orcparamrel;

$anousu  = db_getsession("DB_anousu");
$dt = datas_bimestre($bimestre,$anousu); // no dbforms/db_funcoes.php
$dt_ini= $dt[0]; // data inicial do período
$dt_fin= $dt[1]; // data final do período

// seleciona matriz com estruturais selecionados pelo usuario
// variareis
$n1 = 5;
$n2= 10;

$instituicao  = str_replace("-",",",$db_selinstit);

$m_semuso     = $orcparamrel->sql_parametro('15','0',"f",$instituicao,db_getsession("DB_anousu"));// sem uso
$m_impostos   = $orcparamrel->sql_parametro('15','1',"f",$instituicao,db_getsession("DB_anousu"));
$m_taxas      = $orcparamrel->sql_parametro('15','2',"f",$instituicao,db_getsession("DB_anousu"));
$m_melhorias  = $orcparamrel->sql_parametro('15','3',"f",$instituicao,db_getsession("DB_anousu"));
$m_sociais    = $orcparamrel->sql_parametro('15','4',"f",$instituicao,db_getsession("DB_anousu"));
$m_economicas = $orcparamrel->sql_parametro('15','5',"f",$instituicao,db_getsession("DB_anousu"));
$m_imobiliarias = $orcparamrel->sql_parametro('15','6',"f",$instituicao,db_getsession("DB_anousu"));
$m_valmobiliarias = $orcparamrel->sql_parametro('15','7',"f",$instituicao,db_getsession("DB_anousu"));
$m_permissoes = $orcparamrel->sql_parametro('15','8',"f",$instituicao,db_getsession("DB_anousu"));
$m_patrimoniais = $orcparamrel->sql_parametro('15','9',"f",$instituicao,db_getsession("DB_anousu"));
$m_vegetal  = $orcparamrel->sql_parametro('15','10',"f",$instituicao,db_getsession("DB_anousu"));
$m_animal = $orcparamrel->sql_parametro('15','11',"f",$instituicao,db_getsession("DB_anousu"));
$m_agropecuarias = $orcparamrel->sql_parametro('15','12',"f",$instituicao,db_getsession("DB_anousu"));
$m_mineral = $orcparamrel->sql_parametro('15','13',"f",$instituicao,db_getsession("DB_anousu"));
$m_transformacao = $orcparamrel->sql_parametro('15','14',"f",$instituicao,db_getsession("DB_anousu"));
$m_construcao  = $orcparamrel->sql_parametro('15','15',"f",$instituicao,db_getsession("DB_anousu"));
$m_servicos   = $orcparamrel->sql_parametro('15','16',"f",$instituicao,db_getsession("DB_anousu"));
$m_intragovernamental = $orcparamrel->sql_parametro('15','17',"f",$instituicao,db_getsession("DB_anousu"));
$m_privadas  = $orcparamrel->sql_parametro('15','18',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_exterior  = $orcparamrel->sql_parametro('15','19',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_pessoas  = $orcparamrel->sql_parametro('15','20',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_convenios   = $orcparamrel->sql_parametro('15','21',"f",$instituicao,db_getsession("DB_anousu"));
$m_multas  = $orcparamrel->sql_parametro('15','22',"f",$instituicao,db_getsession("DB_anousu"));
$m_indenizacao = $orcparamrel->sql_parametro('15','23',"f",$instituicao,db_getsession("DB_anousu"));
$m_divida_ativa  = $orcparamrel->sql_parametro('15','24',"f",$instituicao,db_getsession("DB_anousu"));
$m_correntes_diversas = $orcparamrel->sql_parametro('15','25',"f",$instituicao,db_getsession("DB_anousu"));
$m_oper_internas = $orcparamrel->sql_parametro('15','26',"f",$instituicao,db_getsession("DB_anousu"));
$m_oper_externas = $orcparamrel->sql_parametro('15','27',"f",$instituicao,db_getsession("DB_anousu"));
$m_bens_moveis = $orcparamrel->sql_parametro('15','28',"f",$instituicao,db_getsession("DB_anousu"));
$m_bens_imoveis = $orcparamrel->sql_parametro('15','29',"f",$instituicao,db_getsession("DB_anousu"));
$m_emprestimos  = $orcparamrel->sql_parametro('15','30',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_capital_intragovernamentais  = $orcparamrel->sql_parametro('15','31',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_capital_privadas = $orcparamrel->sql_parametro('15','32',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_capital_exterior = $orcparamrel->sql_parametro('15','33',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_capital_pessoas  = $orcparamrel->sql_parametro('15','34',"f",$instituicao,db_getsession("DB_anousu"));
$m_transf_capital_convenios = $orcparamrel->sql_parametro('15','35',"f",$instituicao,db_getsession("DB_anousu"));
$m_outras_social = $orcparamrel->sql_parametro('15','36',"f",$instituicao,db_getsession("DB_anousu"));
$m_outras_disponibilidades = $orcparamrel->sql_parametro('15','37',"f",$instituicao,db_getsession("DB_anousu"));
$m_outras_diversas = $orcparamrel->sql_parametro('15','38',"f",$instituicao,db_getsession("DB_anousu"));
$m_oper_int_mobiliaria = $orcparamrel->sql_parametro('15','39',"f",$instituicao,db_getsession("DB_anousu"));//operações de credito/refinanciamento
$m_oper_int_outras = $orcparamrel->sql_parametro('15','40',"f",$instituicao,db_getsession("DB_anousu"));
$m_oper_ext_mobiliaria = $orcparamrel->sql_parametro('15','41',"f",$instituicao,db_getsession("DB_anousu"));
$m_oper_ext_outras = $orcparamrel->sql_parametro('15','42',"f",$instituicao,db_getsession("DB_anousu"));


$m_saldo_anterior['estrut'] = $orcparamrel->sql_parametro('15','43',"f",$instituicao,db_getsession("DB_anousu")); // contas do compensado que demonstram superavit de exercicios anteriores (creditos reabertos )
$m_saldo_anterior['valor']  =0;

$m_sem_uso = $orcparamrel->sql_parametro('15','44',"f",$instituicao,db_getsession("DB_anousu")); // sem uso

$desp_pessoal = $orcparamrel->sql_parametro('15','45',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_juros   = $orcparamrel->sql_parametro('15','46',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_outras  = $orcparamrel->sql_parametro('15','47',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_investimentos = $orcparamrel->sql_parametro('15','48',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_inversoes     = $orcparamrel->sql_parametro('15','49',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_amortizacao   = $orcparamrel->sql_parametro('15','50',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_reserva        = $orcparamrel->sql_parametro('15','51',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_int_mobiliaria = $orcparamrel->sql_parametro('15','52',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_int_outras     = $orcparamrel->sql_parametro('15','53',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_ext_mobiliaria = $orcparamrel->sql_parametro('15','54',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais
$desp_ext_outras = $orcparamrel->sql_parametro('15','55',"f",$instituicao,db_getsession("DB_anousu"));// despesa com pessoal e encargos sociais

$somador_I_inicial    = 0;
$somador_I_atualizada = 0; // ;
$somador_I_nobim      = 0;
$somador_I_atebim   = 0;
$somador_I_realizar = 0;
$somador_II_inicial    = 0;
$somador_II_atualizada = 0; // ;
$somador_II_nobim      = 0;
$somador_II_atebim   = 0;
$somador_II_realizar = 0;
$somador_III_inicial    = 0;
$somador_III_atualizada = 0; // ;
$somador_III_nobim      = 0;
$somador_III_atebim   = 0;
$somador_III_realizar = 0;
$somador_IV_inicial    = 0;
$somador_IV_atualizada = 0; // ;
$somador_IV_nobim      = 0;
$somador_IV_atebim   = 0;
$somador_IV_realizar = 0;
$somador_V_inicial    = 0;
$somador_V_atualizada = 0; // ;
$somador_V_nobim      = 0;
$somador_V_atebim   = 0;
$somador_V_realizar = 0;
// despesas
$somador_VI_inicial    = 0;
$somador_VI_adicional  = 0; // ;
$somador_VI_emp_nobim  = 0;
$somador_VI_emp_atebim = 0;
$somador_VI_liq_nobim  = 0;
$somador_VI_liqatebim  = 0;

$somador_VII_inicial    = 0;
$somador_VII_adicional  = 0; // ;
$somador_VII_emp_nobim  = 0;
$somador_VII_emp_atebim = 0;
$somador_VII_liq_nobim  = 0;
$somador_VII_liqatebim  = 0;


// ----------------------- // -------------------------- // -----------------------


$db_filtro  = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_rec = db_receitasaldo(4,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
// db_criatabela($result_rec);
// exit;

$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
//$sql_dotacao = db_dotacaosaldo(8,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin,8,0,true);
$result_desp = db_dotacaosaldo(7,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin);


$result_bal= db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
for($i=0;$i<pg_numrows($result_bal);$i++){
  db_fieldsmemory($result_bal,$i);  
  if (in_array($estrutural,$m_saldo_anterior['estrut'])){
      $m_saldo_anterior['valor'] += $saldo_final ;
  }   
}



///////////////////////////////  ///////////////
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

$head2 = $descr_inst;
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4 = "BALANÇO ORÇAMENTÁRIO";
$head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$txt = strtoupper(db_mes('01'));
$dt  = split("-",$dt_fin);
$txt.= " À ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";;
$dt  = split("-",$dt_ini);
$txt.= strtoupper(db_mes($dt[1]))."-";
$dt  = split("-",$dt_fin);
$txt.= strtoupper(db_mes($dt[1]));
$head6 = "$txt";
////////////////////////// ///////////////////

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->addpage();

$troca = 1;
$alt = 4;
$dataini = $dt_ini;
$datafin = $dt_fin;
$pagina = 1;
$tottotal = 0;

$pdf->setfont('arial','',6);
$pdf->cell(170,$alt,"LRF, Art. 52, inciso I, alíneas \"a\" e \"b\" do inciso II e §1 - Anexo I ",'0',0,"L",0);
$pdf->cell(20,$alt,"R$",'0',1,"R",0);


$pdf->setfont('arial','',6);
$pdf->cell(60,($alt*2),"RECEITAS",'TBR',0,"C",0);
$pdf->cell(20,$alt,"PREVISÃO",'TR',0,"C",0);
$pdf->cell(20,$alt,"PREVISÃO",'TR',0,"C",0);
$pdf->cell(70,$alt,"RECEITAS REALIZADAS",'TBR',0,"C",0);
$pdf->cell(20,$alt,"SALDO A",'T',1,"C",0); //BR
$pdf->setX(70);
$pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
$pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);
$pdf->cell(25,$alt,"No Bimestre (b)",'BR',0,"C",0);
$pdf->cell(10,$alt,"% (b/a)",'BR',0,"C",0);
$pdf->cell(25,$alt,"Até Bimestre (c)",'BR',0,"C",0);
$pdf->cell(10,$alt,"% (c/a)",'BR',0,"C",0);
$pdf->cell(20,$alt,"REALIZAR (a-c)",'B',0,"R",0); //BR
$pdf->ln(4);

//--------------------------------
$pos_rec_correntes = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA TRIBUTÁRIA",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_impostos)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic; // ;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Impostos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;



// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_taxas)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic; // ;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Taxas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;

// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_melhorias)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Cotribuição de Melhorias",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 


// --------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA DE CONTRIBUIÇÃO",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_sociais)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Cotribuição de Sociais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_economicas)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Contribuições Economicas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;

// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 

// --------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA PATRIMONIAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_imobiliarias)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas Imobiliárias",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_valmobiliarias)){ 
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim    += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita de Valores Mobiliários",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_permissoes)){ // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas de Concessões e Permissões",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;



// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_patrimoniais)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Outras Receitas Patrimoniais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 


// --------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA AGROPECUÁRIA",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_vegetal)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Produção Vegetal",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_animal)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Produção Animal e Derivados",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_agropecuarias)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Outras Receitas Agropecuárias",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA INDUSTRIAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_mineral)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas da Industria Extrativa Mineral",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transformacao)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas da Indústria de Transformação",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_construcao)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas da Indústria de Construção",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;



// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA DE SERVIÇÕES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_servicos)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas de Serviços",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."TRANSFERÊNCIAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_intragovernamental)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências Intergovernamentais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_privadas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Instituições Privadas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_exterior)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências do Exterior",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_pessoas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Pessoas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_convenios)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Convenios",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."OUTRAS RECEITAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_multas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Multas e Juros de Mora",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_indenizacao)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Indenizações e Restituições",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_divida_ativa)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Dívida Ativa",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;



//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_correntes_diversas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas Correntes Diversas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 


//-------------------------------- * --------------------------------------------------
// guarda o total das receitas correntes
  $rec_cor_I_inicial    = $somador_I_inicial ;
  $rec_cor_I_atualizada = $somador_I_atualizada ; // ;
  $rec_cor_I_nobim      = $somador_I_nobim  ;
  $rec_cor_I_atebim     = $somador_I_atebim ;
  $rec_cor_I_realizar   = $somador_I_realizar ;

 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
 $pdf->setY($pos_rec_correntes);
 $pdf->setX(70);
 $pdf->cell(20,$alt,db_formatar($rec_cor_I_inicial,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($rec_cor_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(25,$alt,db_formatar($rec_cor_I_nobim,'f'),'0',0,"R",0);
 @$pdf->cell(10,$alt,db_formatar(($rec_cor_I_nobim*100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(25,$alt,db_formatar($rec_cor_I_atebim ,'f'),'0',0,"R",0);
 @$pdf->cell(10,$alt,db_formatar(($rec_cor_I_atebim *100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($rec_cor_I_realizar,'f'),'0',0,"R",0);
 $pdf->setY($pos_atu); // desce novamente até aki 





//-------------------------------- * --------------------------------------------------



//--------------------------------
 $pos_rec_capital = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."OPERAÇÕES DE CRÉDITO",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_internas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Operações de Crédito Internas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;



//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_externas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Operações de Crédito Externas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."ALIENAÇÃO DE BENS",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_bens_moveis)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Alienação de Bens Móveis",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_bens_imoveis)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Alienação de Bens Imóveis",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;

// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."AMORTIZAÇÃO DE EMPRÉSTIMOS",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_emprestimos)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Amortização de Empréstimos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 




//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."TRANSFERÊNCIAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_intragovernamentais)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências Intragovernamentais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_privadas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Instituições Privadas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_exterior)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências do Exterior",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_pessoas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Pessoas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_convenios)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Convenios",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;



// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 




//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."OUTRAS RECEITAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_social)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Integralização do Capital Social",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_disponibilidades)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Remuneração das Disponibilidades",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_diversas)){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas de Capital Diversas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_I_inicial    += $tot_inicial;
  $somador_I_atualizada += $tot_atualizada; // ;
  $somador_I_nobim      += $tot_nobim;
  $somador_I_atebim   += $tot_atebim;
  $somador_I_realizar += $tot_realizar;
  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;



// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//-------------------------------- * --------------------------------------------------
// guarda o total das receitas correntes
  $rec_cor_I_inicial    = $somador_I_inicial-$rec_cor_I_inicial ;
  $rec_cor_I_atualizada = $somador_I_atualizada-$rec_cor_I_atualizada ; // ;
  $rec_cor_I_nobim      = $somador_I_nobim-$rec_cor_I_nobim   ;
  $rec_cor_I_atebim     = $somador_I_atebim-$rec_cor_I_atebim ;
  $rec_cor_I_realizar   = $somador_I_realizar-$rec_cor_I_realizar ;

 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
 $pdf->setY($pos_rec_capital);
 $pdf->setX(70);
 $pdf->cell(20,$alt,db_formatar($rec_cor_I_inicial,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($rec_cor_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(25,$alt,db_formatar($rec_cor_I_nobim,'f'),'0',0,"R",0);
 @$pdf->cell(10,$alt,db_formatar(($rec_cor_I_nobim*100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(25,$alt,db_formatar($rec_cor_I_atebim ,'f'),'0',0,"R",0);
 @$pdf->cell(10,$alt,db_formatar(($rec_cor_I_atebim *100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($rec_cor_I_realizar,'f'),'0',0,"R",0);
 $pdf->setY($pos_atu); // desce novamente até aki 





//-------------------------------- * --------------------------------------------------









//--------------------------------
// // // // // // // SUBTOTAL DAS RECEITAS  // // // // // // //
$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SUBTOTAL DAS RECEITAS (I)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_I_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_I_nobim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_I_nobim*100)/$somador_I_atualizada,'f'),'TBR',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($somador_I_atebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_I_atebim*100)/$somador_I_atualizada,'f'),'TBR',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($somador_I_realizar,'f'),'TB',1,"R",0);
//--------------------------------

$pdf->setfont('arial','',5);
$pdf->Ln(4);
$pdf->cell(60,$alt,"Continua na página 2",'0',1,"L",0);

$pdf->addpage();
$pdf->setfont('arial','',5);
$pdf->cell(60,$alt,"Continuação da página 1 ",'0',1,"L",0);
$pdf->Ln(4);


$pos_refi = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"OPERAÇES DE CREDITO / REFINANCIAMENTO (II)",'TR',0,"L",0);
$pdf->cell(20,$alt,'','TR',0,"R",0);
$pdf->cell(20,$alt,'','TR',0,"R",0);
$pdf->cell(25,$alt,'','TR',0,"R",0);
$pdf->cell(10,$alt,'','TR',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','TR',0,"R",0);
$pdf->cell(10,$alt,'','TR',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'','T',1,"R",0);
//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."Operações de Credito Internas",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_int_mobiliaria )){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Para Refinanciamento da Dívida Mobiliária",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_II_inicial    += $tot_inicial;
  $somador_II_atualizada += $tot_atualizada; // ;
  $somador_II_nobim      += $tot_nobim;
  $somador_II_atebim   += $tot_atebim;
  $somador_II_realizar += $tot_realizar;
  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_int_outras )){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Para Refinanciamento de Outras Dívidas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_II_inicial    += $tot_inicial;
  $somador_II_atualizada += $tot_atualizada; // ;
  $somador_II_nobim      += $tot_nobim;
  $somador_II_atebim   += $tot_atebim;
  $somador_II_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;


// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."Operações de Credito Externas",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_ext_mobiliaria )){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Para Refinanciamento da Dívida Mobiliária",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_II_inicial    += $tot_inicial;
  $somador_II_atualizada += $tot_atualizada; // ;
  $somador_II_nobim      += $tot_nobim;
  $somador_II_atebim   += $tot_atebim;
  $somador_II_realizar += $tot_realizar;

  $v_inicial    = $tot_inicial;
  $v_atualizada = $tot_atualizada; // ;
  $v_nobim      = $tot_nobim;
  $v_atebim     = $tot_atebim;
  $v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_ext_outras )){
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Para Refinanciamento de Outras Dívidas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
  $somador_II_inicial    += $tot_inicial;
  $somador_II_atualizada += $tot_atualizada; // ;
  $somador_II_nobim      += $tot_nobim;
  $somador_II_atebim   += $tot_atebim;
  $somador_II_realizar += $tot_realizar;

  $v_inicial    += $tot_inicial;
  $v_atualizada += $tot_atualizada; // ;
  $v_nobim      += $tot_nobim;
  $v_atebim     += $tot_atebim;
  $v_realizar   += $tot_realizar;



// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 


 // sobe, escreve e desce
 // escreve o operações de crédito/refinamento

$pdf->setY($pos_refi);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($somador_II_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_II_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_II_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_II_nobim*100)/$somador_II_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_II_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_II_atebim*100)/$somador_II_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_II_realizar,'f'),'0',0,"R",0);

$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
// // // // // // // SUBTOTAL COM REFINANCIAMENTO  // // // // // // //

  $somador_III_inicial    = $somador_I_inicial + $somador_II_inicial   ;
  $somador_III_atualizada = $somador_I_atualizada+ $somador_II_atualizada  ; // ;
  $somador_III_nobim      = $somador_I_nobim + $somador_II_nobim  ;
  $somador_III_atebim   =  $somador_I_atebim + $somador_II_atebim ;
  $somador_III_realizar =  $somador_I_realizar + $somador_II_realizar;



$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SUBTOTAL COM REFINANCIAMENTO (III) = (I+II)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_III_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_III_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_III_nobim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_III_nobim*100)/$somador_III_atualizada,'f'),'TBR',0,"R",0); // %
$pdf->cell(25,$alt,db_formatar($somador_III_atebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_III_atebim*100)/$somador_III_atualizada,'f'),'TBR',0,"R",0); // %
$pdf->cell(20,$alt,db_formatar($somador_III_realizar,'f'),'TB',1,"R",0);
//--------------------------------
  $pos_deficit = $pdf->getY();
$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"DÉFICIT (IV)",'TBR',0,"L",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(25,$alt,'','TBR',0,"R",0);
$pdf->cell(10,$alt,'','TBR',0,"R",0);
$pdf->cell(25,$alt,'','TBR',0,"R",0);
$pdf->cell(10,$alt,'','TBR',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'','TB',1,"R",0);
//--------------------------------
 $pos_total_rec = $pdf->getY(); 
$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"TOTAL (V) = (III+IV)",'TBR',0,"L",0);
$pdf->cell(20,$alt,'-','TBR',0,"R",0);
$pdf->cell(20,$alt,'-','TBR',0,"R",0);
$pdf->cell(25,$alt,'-','TBR',0,"R",0);
$pdf->cell(10,$alt,'-','TBR',0,"R",0); // %
$pdf->cell(25,$alt,'','TBR',0,"R",0);
$pdf->cell(10,$alt,'-','TBR',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'-','TB',1,"R",0);

//--------------------------------

$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SALDO DE EXERCÍCIOS ANTERIORES",'TBR',0,"L",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(25,$alt,'','TBR',0,"R",0);
$pdf->cell(10,$alt,'','TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($m_saldo_anterior['valor'],'f'),'TBR',0,"R",0);
$pdf->cell(10,$alt,'','TBR',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,'','TB',1,"R",0);
//--------------------------------



// quebra a pagina,  


$pdf->Ln(7);

$pdf->setfont('arial','',5);
$pdf->cell(55,($alt*2),"DESPESAS",'TBR',0,"C",0);
$pdf->cell(20,$alt,"DOTAÇÂO",'TR',0,"C",0);
$pdf->cell(20,$alt,"CREDITOS",'TR',0,"C",0);
$pdf->cell(20,$alt,"DOTAÇÂO",'TR',0,"C",0);
$pdf->cell(28,$alt,"DESPESAS EMPENHADA",'TBR',0,"C",0); 
$pdf->cell(38,$alt,"DESPESAS LIQUIDADAS",'TBR',0,"C",0); 
$pdf->cell(14,$alt,"SALDO A",'T',1,"C",0); //BR
$pdf->setX(65);
$pdf->cell(20,$alt,"INICIAL (d)",'BR',0,"C",0);
$pdf->cell(20,$alt,"ADICIONAIS (e)",'BR',0,"C",0);
$pdf->cell(20,$alt,"ATUALIZADA(f)=(d+e)",'BR',0,"C",0);
$pdf->cell(14,$alt,"No Bimestre(g)",'BR',0,"C",0);
$pdf->cell(14,$alt,"Até Bimestre(h)",'BR',0,"C",0);
$pdf->cell(14,$alt,"No Bimestre(i)",'BR',0,"C",0);
$pdf->cell(14,$alt,"Até Bimestre(j)",'BR',0,"C",0);
$pdf->cell(10,$alt,"%& (j/f)",'BR',0,"C",0);
$pdf->cell(14,$alt,"LIQUIDAR (f-j)",'B',0,"C",0);
$pdf->ln(4);

//--------------------------------
  $pos_corrente = $pdf->getY(); // guarda posição corrente

$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"DESPESAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  // $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";
  if (substr($estrutural,0,3)=='331') {
  // if (in_array($estrutural,$desp_pessoal)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."PESSOAL E ENCARGOS SOCIAIS",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_liq_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  // $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";

  if (substr($estrutural,0,3)=='332') {
  // if (in_array($estrutural,$desp_juros)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."JUROS E ENCARGOS DA DÍVIDA",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim*100)/$tot_atualizada),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;


//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  //  $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";

  if (substr($estrutural,0,3)=='333') {
  // if (in_array($estrutural,$desp_outras)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."OUTRAS DESPESAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_liq_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;


// ----------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_corrente);
$pdf->setX(65);
$pdf->cell(20,$alt,db_formatar($somador_VI_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_adicional,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_liqatebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_VI_liqatebim*100)/($somador_VI_inicial+$somador_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional)-$somador_VI_liqatebim,'f'),'0',0,"R",0);

$pdf->setY($pos_atu); // desce novamente até aki 




//--------------------------------
  $pos_capital = $pdf->getY(); //pega posição e guarda
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"DESPESAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  //  $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";

  if (substr($estrutural,0,3)=='344') {
  //  if (in_array($estrutural,$desp_investimentos)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."INVESTIMENTOS",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_liq_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;
   $v_VI_inicial    = $tot_inicial ;
   $v_VI_adicional  = $tot_adicional; // ;
   $v_VI_emp_nobim  = $tot_emp_nobim;
   $v_VI_emp_atebim = $tot_emp_atebim;
   $v_VI_liq_nobim  = $tot_liq_nobim;
   $v_VI_liqatebim  = $tot_liq_atebim;



//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  // $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";

  if (substr($estrutural,0,3)=='345') {
  //  if (in_array($estrutural,$desp_inversoes)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."INVERSÕES FINANCEIRAS",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_liq_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;
   $v_VI_inicial    += $tot_inicial ;
   $v_VI_adicional  += $tot_adicional; // ;
   $v_VI_emp_nobim  += $tot_emp_nobim;
   $v_VI_emp_atebim += $tot_emp_atebim;
   $v_VI_liq_nobim  += $tot_liq_nobim;
   $v_VI_liqatebim  += $tot_liq_atebim;


//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  //  $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";

  if (substr($estrutural,0,3)=='346') {
  // if (in_array($estrutural,$desp_amortizacao)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."AMORTIZAÇÃO DA DÍVIDA",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_liq_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;
   $v_VI_inicial    += $tot_inicial ;
   $v_VI_adicional  += $tot_adicional; // ;
   $v_VI_emp_nobim  += $tot_emp_nobim;
   $v_VI_emp_atebim += $tot_emp_atebim;
   $v_VI_liq_nobim  += $tot_liq_nobim;
   $v_VI_liqatebim  += $tot_liq_atebim;




// ----------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_capital);
$pdf->setX(65);
$pdf->cell(20,$alt,db_formatar($v_VI_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_VI_adicional,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($v_VI_inicial+$v_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_VI_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_VI_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_VI_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_VI_liqatebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($v_VI_liqatebim*100/($v_VI_inicial+$v_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($v_VI_inicial+$v_VI_adicional)-$v_VI_liqatebim,'f'),'0',0,"R",0);

$pdf->setY($pos_atu); // desce novamente até aki 

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento."00";
  if (substr($estrutural,0,3)=='399') {
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"RESERVA DE CONTINGÊNCIA",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim*100)/$tot_atualizada),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;


// // // // // // //  RPPS
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento."00";
  if (substr($estrutural,0,3)=='377') {
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"RESERVA DE RPPS",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim*100)/$tot_atualizada),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VI_inicial    += $tot_inicial ;
   $somador_VI_adicional  += $tot_adicional; // ;
   $somador_VI_emp_nobim  += $tot_emp_nobim;
   $somador_VI_emp_atebim += $tot_emp_atebim;
   $somador_VI_liq_nobim  += $tot_liq_nobim;
   $somador_VI_liqatebim  += $tot_liq_atebim;

//--------------------------------

// // // // // // //  subtotal das despesas (VI)
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"SUBTOTAL DAS DESPESAS (VI)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_adicional,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional),'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_emp_nobim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_emp_atebim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_liq_nobim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VI_liqatebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($somador_VI_liqatebim*100/($somador_VI_inicial+$somador_VI_adicional),'f'),'TBR',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar((($somador_VI_inicial+$somador_VI_adicional)-$somador_VI_liqatebim),'f'),'TB',1,"R",0); // (f-j)
//--------------------------------
  $pos_div_amort = $pdf->getY(); // guarda posição
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"AMORTIZAÇÃO DA DÍVIDA/REFINANCIAMENTO (VII)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
  // pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n1)."Amortização da Dívida Interna",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  //  $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";

  if (in_array($estrutural,$desp_int_mobiliaria)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."Dívida Mobiliária",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($tot_liq_atebim*100 /($tot_atualizada*100),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VII_inicial    += $tot_inicial ;
   $somador_VII_adicional  += $tot_adicional; // ;
   $somador_VII_emp_nobim  += $tot_emp_nobim;
   $somador_VII_emp_atebim += $tot_emp_atebim;
   $somador_VII_liq_nobim  += $tot_liq_nobim;
   $somador_VII_liqatebim  += $tot_liq_atebim;
   $v_inicial    = $tot_inicial ;
   $v_adicional  = $tot_adicional; // ;
   $v_nobim      = $tot_emp_nobim;
   $v_emp_atebim = $tot_emp_atebim;
   $v_liq_nobim  = $tot_liq_nobim;
   $v_liqatebim  = $tot_liq_atebim;


//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  //  $estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";
  if (in_array($estrutural,$desp_int_outras)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."Outras Dívidas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($tot_liq_atebim*100 /($tot_atualizada*100),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VII_inicial    += $tot_inicial ;
   $somador_VII_adicional  += $tot_adicional; // ;
   $somador_VII_emp_nobim  += $tot_emp_nobim;
   $somador_VII_emp_atebim += $tot_emp_atebim;
   $somador_VII_liq_nobim  += $tot_liq_nobim;
   $somador_VII_liqatebim  += $tot_liq_atebim;
   $v_inicial    += $tot_inicial ;
   $v_adicional  += $tot_adicional; // ;
   $v_nobim  += $tot_emp_nobim;
   $v_emp_atebim += $tot_emp_atebim;
   $v_liq_nobim  += $tot_liq_nobim;
   $v_liqatebim  += $tot_liq_atebim;

// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(65);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_adicional,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($v_inicial+$v_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_liqatebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($tot_liq_atebim*100 /($tot_atualizada*100),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($v_inicial+$v_adicional)-$v_liqatebim,'f'),'0',0,"R",0);
$pdf->setY($pos_atu); // desce novamente até aki 



//--------------------------------
// pega altura e guarda
  $pos_y = $pdf->getY();

$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n1)."Amortização da Dívida Externa",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);  // % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  //$estrutural = $c60_estrut;
  $estrutural = $o58_elemento."00";
  if (in_array($estrutural,$desp_ext_mobiliaria)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."Dívida Mobiliária",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($tot_liq_atebim*100 /($tot_atualizada*100),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VII_inicial    += $tot_inicial ;
   $somador_VII_adicional  += $tot_adicional; // ;
   $somador_VII_emp_nobim  += $tot_emp_nobim;
   $somador_VII_emp_atebim += $tot_emp_atebim;
   $somador_VII_liq_nobim  += $tot_liq_nobim;
   $somador_VII_liqatebim  += $tot_liq_atebim;
   $v_inicial    = $tot_inicial ;
   $v_adicional  = $tot_adicional; // ;
   $v_nobim  = $tot_emp_nobim;
   $v_emp_atebim = $tot_emp_atebim;
   $v_liq_nobim  = $tot_liq_nobim;
   $v_liqatebim  = $tot_liq_atebim;


//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento."00";
  if (in_array($estrutural,$desp_ext_outras)){
      $tot_inicial    += $dot_ini;
      $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado; // adicional;
      $tot_emp_nobim  += $empenhado  - $anulado;
      $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
      $tot_liq_nobim  += $liquidado;
      $tot_liq_atebim += $liquidado_acumulado;
  }
}  
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,espaco($n2)."Outras Dívidas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($tot_liq_atebim*100 /($tot_atualizada*100),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - $tot_liq_atebim),'f'),'0',1,"R",0); // (f-j)
   $somador_VII_inicial    += $tot_inicial ;
   $somador_VII_adicional  += $tot_adicional; // ;
   $somador_VII_emp_nobim  += $tot_emp_nobim;
   $somador_VII_emp_atebim += $tot_emp_atebim;
   $somador_VII_liq_nobim  += $tot_liq_nobim;
   $somador_VII_liqatebim  += $tot_liq_atebim;
   $v_inicial    += $tot_inicial ;
   $v_adicional  += $tot_adicional; // ;
   $v_nobim  += $tot_emp_nobim;
   $v_emp_atebim += $tot_emp_atebim;
   $v_liq_nobim  += $tot_liq_nobim;
   $v_liqatebim  += $tot_liq_atebim;



// ------------------------------
// --------------------------------
 $pos_atu = $pdf->y; // posição atual
 // sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(65);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_adicional,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($v_inicial+$v_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($v_liqatebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($v_liqatebim*100/($v_inicial+$v_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($v_inicial+$v_adicional)-$v_liqatebim,'f'),'0',0,"R",0);

$pdf->setY($pos_div_amort);
$pdf->setX(65);
$pdf->cell(20,$alt,db_formatar($somador_VII_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar( $somador_VII_adicional,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($somador_VII_inicial+$somador_VII_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VII_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VII_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VII_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VII_liqatebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($somador_VII_liqatebim*100/($somador_VII_inicial+$somador_VII_adicional),'f'),'R',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar(($somador_VII_inicial+$somador_VII_adicional)-$somador_VII_liqatebim,'f'),'0',0,"R",0);

$pdf->setY($pos_atu); // desce novamente até aki 


//--------------------------------

// // // // // // //  subtotal com refinamento 
   $somador_VIII_inicial    =  $somador_VI_inicial   + $somador_VII_inicial;
   $somador_VIII_adicional  =  $somador_VI_adicional + $somador_VII_adicional ; // ;
   $somador_VIII_emp_nobim  =  $somador_VI_emp_nobim + $somador_VII_emp_nobim ;
   $somador_VIII_emp_atebim =  $somador_VI_emp_atebim+ $somador_VII_emp_atebim ;
   $somador_VIII_liq_nobim  =  $somador_VI_liq_nobim + $somador_VII_liq_nobim ;
   $somador_VIII_liqatebim  =  $somador_VI_liqatebim + $somador_VII_liqatebim ;



$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"SUBTOTAL COM REFINANCIAMENTO (VIII) = (VI + VII)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_VIII_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VIII_adicional,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_emp_nobim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_emp_atebim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_liq_nobim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_liqatebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar($somador_VIII_liqatebim*100/($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar((($somador_VIII_inicial+$somador_VIII_adicional) - $somador_VIII_liqatebim),'f'),'TB',1,"R",0); // (f-j)
//--------------------------------

// // // // // // //  subtotal com refinamento 
$pos_superavit = $pdf->getY();
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"SUPERÁVIT (IX)",'TBR',0,"L",0);
$pdf->cell(20,$alt,'-','TBR',0,"C",0);
$pdf->cell(20,$alt,'-','TBR',0,"C",0);
$pdf->cell(20,$alt,'-','TBR',0,"C",0);
$pdf->cell(14,$alt,'-','TBR',0,"C",0);
$pdf->cell(14,$alt,'-','TBR',0,"C",0);
$pdf->cell(14,$alt,'-','TBR',0,"C",0);
$pdf->cell(14,$alt,'','TBR',0,"C",0);
@$pdf->cell(10,$alt,'-','TBR',0,"C",0); // % (j/f)
$pdf->cell(14,$alt,'-','TB',1,"C",0); // (f-j)
//--------------------------------

$pos_total_desp = $pdf->getY();
$pdf->setfont('arial','b',5);
$pdf->cell(55,$alt,"TOTAL (X) = (VIII + IX)",'TBR',0,"L",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(20,$alt,'','TBR',0,"R",0);
$pdf->cell(14,$alt,'','TBR',0,"R",0);
$pdf->cell(14,$alt,'','TBR',0,"R",0);
$pdf->cell(14,$alt,'','TBR',0,"R",0);
$pdf->cell(14,$alt,'','TBR',0,"R",0);
@$pdf->cell(10,$alt,'','TBR',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,'','TB',1,"R",0); // (f-j)
//--------------------------------
// calculo do superávit ou déficit 
// verifica se tem superávit
$TEM_SUPERAVIT = false;
if ($somador_III_atebim > $somador_VIII_liqatebim) { // receita realizada maior que despesa liquidada:superávit
  $somador_IX_liqatebim = $somador_III_atebim - $somador_VIII_liqatebim ;
  $pos = $pdf->getY();
  $pdf->setY($pos_superavit);
  $pdf->setX(167);
  $pdf->cell(14,$alt,db_formatar($somador_IX_liqatebim,'f'),'0',0,"R",0); // %
  $pdf->setY($pos);
  $TEM_SUPERAVIT = true;
} else {
  $somador_IV_atebim = $somador_III_atebim - $somador_VIII_liqatebim ;
  $pos = $pdf->getY();
  $pdf->setY($pos_deficit);
  $pdf->setX(145);
  $pdf->cell(25,$alt,db_formatar($somador_IV_atebim,'f'),'0',0,"R",0); // %
  $pdf->setY($pos);
}

$pos = $pdf->getY();
$pdf->setY($pos_total_rec);
  $somador_V_inicial    = $somador_III_inicial    + $somador_IV_inicial   ;
  $somador_V_atualizada = $somador_III_atualizada + $somador_IV_atualizada  ; // ;
  $somador_V_nobim      = $somador_III_nobim   + $somador_IV_nobim  ;
  $somador_V_atebim   =  $somador_III_atebim   + $somador_IV_atebim ;
  $somador_V_realizar =  $somador_III_realizar + $somador_IV_realizar;

$pdf->setfont('arial','B',6);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($somador_V_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_V_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_V_nobim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_V_nobim*100)/$somador_V_atualizada,'f'),'TBR',0,"R",0); // %
$pdf->cell(25,$alt,db_formatar($somador_V_atebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_V_atebim*100)/$somador_V_atualizada,'f'),'TBR',0,"R",0);  // % (b/a)
$pdf->cell(20,$alt,db_formatar($somador_V_realizar,'f'),'TB',1,"R",0);


$pdf->setY($pos_total_desp);
$pdf->setX(65);
$pdf->setfont('arial','b',5);
$pdf->cell(20,$alt,db_formatar($somador_VIII_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VIII_adicional,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_emp_nobim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_emp_atebim,'f'),'TBR',0,"R",0);
$pdf->cell(14,$alt,db_formatar($somador_VIII_liq_nobim,'f'),'TBR',0,"R",0);
if ($TEM_SUPERAVIT ==true)
  $pdf->cell(14,$alt,db_formatar($somador_VIII_liqatebim+$somador_IX_liqatebim,'f'),'TBR',0,"R",0);
else
  $pdf->cell(14,$alt,db_formatar($somador_VIII_liqatebim,'f'),'TBR',0,"R",0);
 
@$pdf->cell(10,$alt,db_formatar($somador_VIII_liqatebim*100/($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0); // % (j/f)
$pdf->cell(14,$alt,db_formatar((($somador_VIII_inicial+$somador_VIII_adicional) - $somador_VIII_liqatebim),'f'),'TB',1,"R",0); // (f-j)

$pdf->setY($pos);
//--------------------------------

$pdf->ln(2);
$pdf->setfont('arial','',5);
$pdf->cell(55,$alt,"FONTE: Contabilidade",'0',0,"L",0);

$pdf->ln(25);


// assinaturas


assinaturas(&$pdf,&$classinatura,'LRF');






// saida
$pdf->Output();


?>