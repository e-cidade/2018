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
include("classes/db_orcparamrel_classe.php");
include("dbforms/db_funcoes.php");

$classinatura = new cl_assinatura;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$anousu = db_getsession("DB_anousu");
$dt = datas_quadrimestre($bimestre,$anousu); // no dbforms/db_funcoes.php
$dt_ini= $dt[0]; // data inicial do período
$dt_fin= $dt[1]; // data final do período

$orcparamrel = new cl_orcparamrel;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $descr_inst .= $xvirg.$nomeinst ;
    $xvirg = ', ';
}

$head2 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");
//  ATENÇÂO : a variavel bimestre contem a informação do quadrimestre
$head4 = "PERIODO : $bimestre º QUADRIMESTRE  de $anousu ";
$head6 = "INSTITUIÇÕES : ".$descr_inst;




//-- pega total da receita corrente liquida -- estruturais selecionados 
// gera matris com todos os estruturais selecionados nas configurações do relatorio
$m_todos = $orcparamrel->sql_parametro('5');
$virgula='';
$lista = '(';
$tt = sizeof($m_todos);
for ($x=0; $x <sizeof($m_todos);$x++){
  $lista .= $virgula."'".$m_todos[$x]."'";
  if ($x == $tt-1)  	
  $virgula ='';
  else $virgula =',';   	  
}
$lista = $lista.')';

$clreceita_saldo_mes = new cl_receita_saldo_mes;
$clreceita_saldo_mes->estrut= $lista;
$clreceita_saldo_mes->instit = str_replace('-',', ',$db_selinstit);
$clreceita_saldo_mes->dtini =  $dt_ini;
$clreceita_saldo_mes->dtfim = $dt_fin;
$clreceita_saldo_mes->usa_datas = 'sim';
$clreceita_saldo_mes->sql_record_total();

db_fieldsmemory($clreceita_saldo_mes->result,0);


// gera o balancete de verificação com as contas 3 e 4 incluidas

// $where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") "; 
// $result = db_planocontassaldo_completo($anousu,$dt_ini,$dt_fin,false,$where);

$sele_work = 'o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);

// para listar as interferencias
$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";
$result_plano = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,$where);
// db_criatabela($result_plano);exit;
//---
$soma_ativo  =0;
$soma_inativo=0;
$soma_indenizacoes =0;
$soma_judicial =0;
$soma_desp_anteriores =0;
$soma_pensionistas    =0;
$soma_pessoal  = 0;
$soma_repasses = 0;
//--------- // ------------- // ------------- // ---------------
// recupera elementos da configuração dos relatorios
//--------- // ------------- // ------------- // ---------------
$m_ativo = $orcparamrel->sql_parametro('20','0');
$m_inativo = $orcparamrel->sql_parametro('20','1');
$m_indenizacoes = $orcparamrel->sql_parametro('20','2');
$m_judicial = $orcparamrel->sql_parametro('20','3');
$m_desp_anteriores = $orcparamrel->sql_parametro('20','4');
$m_pensionistas = $orcparamrel->sql_parametro('20','5');
$m_pessoal  = $orcparamrel->sql_parametro('20','6');
$m_repasses = $orcparamrel->sql_parametro('20','7');
//--------- // ------------- // ------------- // ---------------
for($i=0;$i< pg_numrows($result);$i++) {
   db_fieldsmemory($result,$i);
   //$valor_desp = ($saldo_final - $saldo_anterior);        
    $o58_elemento = $o58_elemento."00";
    $estrutural= substr($o58_elemento,0,15);       
    $valor_desp = $liquidado;
   
   if (in_array($estrutural,$m_ativo)){
   	  $soma_ativo += $valor_desp;   	  
   } 
   if (in_array($estrutural,$m_inativo)){
   	  $soma_inativo += $valor_desp;
   } 
   if (in_array($estrutural,$m_indenizacoes)){
   	  $soma_indenizacoes += $valor_desp;
   }
   if (in_array($estrutural,$m_judicial)){
   	  $soma_judicial += $valor_desp;
   }
   if (in_array($estrutural,$m_desp_anteriores)){
   	  $soma_desp_anteriores += $valor_desp;
   }
   if (in_array($estrutural,$m_pensionistas)){
   	  $soma_pensionistas += $valor_desp;
   }
   if (in_array($estrutural,$m_pessoal)){
   	  $soma_pessoal += $valor_desp;
   }
   // repasses e interferencia
   //if (in_array($estrutural,$m_repasses)){
   //	  $soma_repasses += $valor_desp;
   // }
}
for($i=0;$i< pg_numrows($result_plano);$i++) {
    db_fieldsmemory($result_plano,$i);
          
    $valor_desp = $saldo_anterior_debito - $saldo_anterior_credito;
    if (in_array($estrutural,$m_repasses)){
   	   $soma_repasses += $valor_desp;
   } 
}
//  include("dados_2004.php");


$deducoes = $soma_indenizacoes +$soma_judicial+$soma_desp_anteriores+$soma_pensionistas;
$soma_grupo01 = ($soma_ativo +$soma_inativo) - $deducoes;

// + dados do periodo anterior

//  $soma_grupo01 =$soma_grupo01 + $soma_desp_2004;

$total_despesa_pessoal = $soma_grupo01+ $soma_pessoal +$soma_repasses;

// dados da receita corrente liquida 

// $rcl = ($total_rcl + $soma_rec_2004); 

$rcl = $total_rcl;
@$tdp = (($total_despesa_pessoal *100)/ $rcl ); // percentagem da despesa com pessoal

// limite maximo 60% da receita corrente liquida
$instit = str_replace('-',', ',$db_selinstit); 
// $instit = db_getsession("DB_instit");
if ($instit==1){ // prefeitura
  $limite_maximo= 54;
  $val_maximo = ($rcl * $limite_maximo)/100 ;
  $limite_prudencial= 51.3;
  $val_prudencial = ($rcl * $limite_prudencial)/100 ;
  $limite_alerta= 48.6;
  $val_alerta = ($rcl * $limite_alerta)/100 ; 	
} else if ($instit==2){ // camara
  $limite_maximo= 6;
  $val_maximo = ($rcl * $limite_maximo)/100 ;
  $limite_prudencial= 5.7;
  $val_prudencial = ($rcl * $limite_prudencial)/100 ;
  $limite_alerta= 5.4;
  $val_alerta = ($rcl * $limite_alerta)/100 ;	
} else { // ra
  $limite_maximo= 0;
  $val_maximo = 0 ;//($rcl * $limite_maximo)/100 ;
  $limite_prudencial= 0 ; //51.3;
  $val_prudencial = 0; //($rcl * $limite_prudencial)/100 ;
  $limite_alerta= 0;// 48.6;
  $val_alerta = 0; //($rcl * $limite_alerta)/100 ;	
}	
	

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina     = 1;

$pdf->addpage();
$pdf->setfont('arial','b',9);
$pdf->cell(95,$alt,"DESPESA COM PESSOAL",'B',0,"L",0);
$pdf->cell(95,$alt,"DESPESA LIQUIDADA",'B',1,"R",0);
$pdf->ln(5);

$pdf->setfont('arial','',9);
$pdf->cell(160,$alt,"DESPESA LÍQUIDA COM PESSOAL",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_grupo01,'f'),0,1,"R",0);

/*
$pdf->Ln();
$pdf->cell(160,$alt,"TDP - Período anterior ( $info_periodo )",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_desp_2004,'f'),0,1,"R",0);
*/

$pdf->Ln();
$pdf->setfont('arial','',9);
$pdf->cell(160,$alt,"Pessoal Ativo",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_ativo,'f'),0,1,"R",0);  
   
$pdf->cell(160,$alt,"Pessoal Inativo e Pensionistas",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_inativo,'f'),0,1,"R",0);
     
$pdf->cell(160,$alt,"(-) Despesas não computadas(art.19 da LRF)",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($deducoes,'f'),0,1,"R",0);     

$pdf->setfont('arial','',8);
$pdf->setX(30); $pdf->cell(140,$alt,"Indenizações por Demissão e PDV",0,0,"L",0);
                $pdf->cell(25,$alt,db_formatar($soma_indenizacoes,'f'),0,1,"R",0);     

$pdf->setX(30); $pdf->cell(140,$alt,"Decorrentes de Decisão Judicial",0,0,"L",0);
                $pdf->cell(25,$alt,db_formatar($soma_judicial,'f'),0,1,"R",0);     

$pdf->setX(30); $pdf->cell(140,$alt,"Despesas de Exercícios Anteriores",0,0,"L",0);
                $pdf->cell(25,$alt,db_formatar($soma_desp_anteriores,'f'),0,1,"R",0);     

$pdf->setX(30); $pdf->cell(140,$alt,"Inativos e Pensionistas com Recursos Vinculados",0,0,"L",0);
                $pdf->cell(25,$alt,db_formatar($soma_pensionistas,'f'),0,1,"R",0);     

//-- /--
$pdf->Ln();
$pdf->setfont('arial','',9);
$pdf->cell(160,$alt,"Outras Desp. de Pessoal Decorrente de Contratos de Terceirização",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_pessoal,'f'),0,1,"R",0);

$pdf->cell(160,$alt,"Repasses Previdenciários ao Regime Próprio de Previdência Social",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_repasses,'f'),0,1,"R",0);
$pdf->setfont('arial','',8);
$pdf->setX(30); $pdf->cell(140,$alt,"Contribuições Patronais",0,0,"L",0);
                $pdf->cell(25,$alt,db_formatar($soma_repasses,'f'),0,1,"R",0);     

$pdf->cell(185,$alt,"",'B',1,"R",0);
$pdf->setfont('arial','',9);

$pdf->Ln();
$pdf->cell(160,$alt,"TOTAL DA DESPESA COM PESSOAL PARA FINS DE APURAÇÃO DO LIMITE -TDP",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($total_despesa_pessoal,'f'),0,1,"R",0);

$pdf->Ln();
$pdf->cell(160,$alt,"RECEITA CORRENTE LÍQUIDA -RCL ",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($rcl,'f'),0,1,"R",0);

// if ( guaiba )
/*
$pdf->Ln();
$pdf->cell(160,$alt,"RCL - Período anterior ( $info_periodo )",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($soma_rec_2004,'f'),0,1,"R",0);
*/

$pdf->Ln();
$pdf->cell(160,$alt,"% DO TOTAL DA DESPESA COM PESSOAL PARA FINS DE APURAÇÃO DO LIMITE TDP SOBRE RCL",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($tdp,'f'),0,1,"R",0);

$pdf->Ln();
$pdf->cell(160,$alt,"LIMITE MÁXIMO (incisos I,II e III, art 20 da LRF )< $limite_maximo % >",0,0,"L",0);
$pdf->cell(25,$alt, db_formatar($val_maximo,'f'),0,1,"R",0);

$pdf->Ln();
$pdf->cell(160,$alt,"LIMITE PRUDENCIAL (unico, art 22 da LRF )< $limite_prudencial % >",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($val_prudencial,'f'),0,1,"R",0);

$pdf->Ln();
$pdf->cell(160,$alt,"LIMITE DE EMISSÃO DE ALERTA < $limite_alerta % >",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($val_alerta,'f'),0,1,"R",0);




$pdf->Ln(15);
// assinaturas
$pref    =  "______________________________"."\n"."Prefeito";
$sec     =  "______________________________"."\n"."Secretario da Fazenda";
$cont    =  "______________________________"."\n"."Controle Interno";
$ass_pref       = $classinatura->assinatura(1000,$pref);
$ass_secretario = $classinatura->assinatura(1002,$sec);
$ass_cont   = $classinatura->assinatura(1005,$cont);

$pdf->setfont('arial','',6);

$largura = ( $pdf->w ) / 3;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_secretario,0,"C",0,0);
$pdf->setxy(($largura*2),$pos);
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);




$pdf->Output();
   
?>