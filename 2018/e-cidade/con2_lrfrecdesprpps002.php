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

if (!isset($arqinclude)){ // se este arquivo não esta incluido por outro

  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_liborcamento.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_libtxt.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_conrelinfo_classe.php");
  include("classes/db_orcparamrel_classe.php");
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  //$clconrelinfo = new cl_conrelinfo;

} // end !include

$instituicao = db_getsession("DB_instit");

// PARAMETROS
for ($linha=1;$linha<=28;$linha++){	
	$m_receita[$linha]['estrut']     = $orcparamrel->sql_parametro('42',$linha,"f",$instituicao,db_getsession("DB_anousu")); 
	$m_receita[$linha]['inicial']    = 0;
	$m_receita[$linha]['atualizada'] = 0;
	$m_receita[$linha]['bimestre']   = 0;
	$m_receita[$linha]['exercicio']  = 0;  // ate o bimestre
	$m_receita[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
}	

for ($linha=29;$linha<=39;$linha++){	
	$m_despesa[$linha]['estrut']     = $orcparamrel->sql_parametro('42',$linha,"f",$instituicao,db_getsession("DB_anousu"));
	$m_despesa[$linha]['nivel']      = $orcparamrel->sql_nivel('42',$linha);
	$m_despesa[$linha]['inicial']    = 0;
	$m_despesa[$linha]['atualizada'] = 0;
	$m_despesa[$linha]['bimestre']   = 0;
	$m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
	$m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
}

for($linha=40; $linha<=42; $linha++){
       $m_disponivel[$linha]['estrut']                 = $orcparamrel->sql_parametro('42',$linha,"f",$instituicao,db_getsession("DB_anousu"));
       $m_disponivel[$linha]['saldo_inicial']          = 0;
       $m_disponivel[$linha]['saldo_periodo_atual']    = 0;       
       $m_disponivel[$linha]['saldo_periodo_anterior'] = 0;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Receitas
$receita  = array();
$receita[1]['txt']  = "RECEITAS CORRENTES(I)";
$receita[2]['txt']  = " Receita de Contribuições";
$receita[3]['txt']  = "   Pessoal Civil";
$receita[4]['txt']  = "      Contribuição de Servidor Ativo Civil";
$receita[5]['txt']  = "      Contribuição de Servidor Inativo Civil";
$receita[6]['txt']  = "      Contribuição de Pensionista Civil";
$receita[7]['txt']  = "   Pessoal Militar";
$receita[8]['txt']  = "      Contribuição de Militar Ativo";
$receita[9]['txt']  = "      Contribuição de Militar Inativo";
$receita[10]['txt'] = "      Contribuição de Pensionista Militar";
$receita[11]['txt'] = "   Outras Contribuições Previdenciárias";
$receita[12]['txt'] = "   Compensação Previdenciária entre RGPS e RPPS";
$receita[13]['txt'] = " Receita Patrimonial";
$receita[14]['txt'] = "   Receitas Imobiliárias";
$receita[15]['txt'] = "   Receitas de Valores Mobiliários";
$receita[16]['txt'] = "   Outras Receitas Patrimoniais";
$receita[17]['txt'] = " Outras Receitas Correntes";
$receita[18]['txt'] = "RECEITAS DE CAPITAL(II)";
$receita[19]['txt'] = " Alienação de Bens";
$receita[20]['txt'] = " Outras Receitas de Capital";
$receita[21]['txt'] = "REPASSES PREVIDENCIÁRIOS RECEBIDOS PELO RPPS(III)";
$receita[22]['txt'] = " Contribuição Patronal do Exercício";
$receita[23]['txt'] = "   Pessoal Civil";
$receita[24]['txt'] = "      Contribuição Patronal Ativo Civil";
$receita[25]['txt'] = "      Contribuição Patronal Inativo Civil";
$receita[26]['txt'] = "      Contribuição Patronal Pensionista Civil";
$receita[27]['txt'] = "   Pessoal Militar";
$receita[28]['txt'] = "      Contribuição Patronal Militar Ativo";
$receita[29]['txt'] = "      Contribuição Patronal Militar Inativo";
$receita[30]['txt'] = "      Contribuição Patronal Pensionista Militar";
$receita[31]['txt'] = " Contribuição Patronal de Exercícios Anteriores";
$receita[32]['txt'] = "   Pessoal Civil";
$receita[33]['txt'] = "      Contribuição Patronal Ativo Civil";
$receita[34]['txt'] = "      Contribuição Patronal Inativo Civil";
$receita[35]['txt'] = "      Contribuição Patronal Pensionista Civil";
$receita[36]['txt'] = "   Pessoal Militar";
$receita[37]['txt'] = "      Contribuição Patronal Militar Ativo";
$receita[38]['txt'] = "      Contribuição Patronal Militar Inativo";
$receita[39]['txt'] = "      Contribuição Patronal Pensionista Militar";
$receita[40]['txt'] = "REPASSES PREVIDENCIÁRIOS PARA COBERTURA DE DÉFICIT(IV)";
$receita[41]['txt'] = "OUTROS APORTES AO RPPS(V)";

for ($linha=1;$linha<=41;$linha++){
	$receita[$linha]['inicial']    = 0;
	$receita[$linha]['atualizada'] = 0;
	$receita[$linha]['bimestre']   = 0;
	$receita[$linha]['exercicio']  = 0; // ate o bimestre
	$receita[$linha]['anterior']   = 0; // ate o bimestre exercicio anterior
}	

/////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!isset($arqinclude)){ // se este arquivo não esta incluido por outro

  $anousu     = db_getsession("DB_anousu");
  $anousu_ant = $anousu-1;
  
  $dt         = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
  $dt_ini     = $dt[0]; // data inicial do período
  $dt_fin     = $dt[1]; // data final do período

} // end !include

$dt          = split("-",$dt_ini);
$periodo_mes = strtoupper(db_mes($dt[1]));

$dt = split("-",$dt_ini);
$dt_ini_ant = $anousu_ant."-".$dt[1]."-".$dt[2];

$dt = split("-",$dt_fin);
$dt_fin_ant = $anousu_ant."-".$dt[1]."-".$dt[2];

// RPPS ///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// seleciona instituição do RPPS
$sql    = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
$resultinst = pg_exec($sql);
$instit ='';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $instit     .= $xvirg.$codigo; // salva insituição
    $xvirg       = ', ';		  
}
$db_filtro  = " o70_instit in (".$instit.")";

// Exercicio Atual
$result_rec     = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
pg_exec("drop table work_receita");

// Exercicio Anterior
$result_rec_ant = db_receitasaldo(11,1,3,true,$db_filtro,$anousu_ant,$dt_ini_ant,$dt_fin_ant);
pg_exec("drop table work_receita");
//db_criatabela($result_rec); exit;

for ($i=0; $i < pg_numrows($result_rec); $i++){
     db_fieldsmemory($result_rec,$i);
     $estrutural = $o57_fonte;
     
     for ($linha=1;$linha < 15;$linha++){
    	  if (in_array($estrutural,$m_receita[$linha]['estrut'])){
	    	$m_receita[$linha]['inicial']    += $saldo_inicial;
		$m_receita[$linha]['atualizada'] += $saldo_inicial_prevadic; 
		$m_receita[$linha]['bimestre']   += $saldo_arrecadado ;   
		$m_receita[$linha]['exercicio']  += $saldo_arrecadado_acumulado;
	  }
     }
}

for ($i=0; $i < pg_numrows($result_rec_ant); $i++) {
     db_fieldsmemory($result_rec_ant,$i);
     $estrutural = $o57_fonte;
     
     for ($linha=1;$linha < 15;$linha++){
          if (in_array($estrutural,$m_receita[$linha]['estrut'])){
                $m_receita[$linha]['anterior'] += $saldo_arrecadado_acumulado;
          }
     }
}

$sele_work = ' c61_instit in ('.$instit.')';

// Exercicio Atual
$result_res_rep = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,$sele_work); 
//db_criatabela($result_res_rep); exit;
@pg_exec("drop table work_pl"); 

// Exercicio Anterior
$result_res_rep_ant = db_planocontassaldo_matriz($anousu_ant,$dt_ini_ant,$dt_fin_ant,false,$sele_work); 
@pg_exec("drop table work_pl"); 

for ($i=0; $i < pg_numrows($result_res_rep); $i++) {
     db_fieldsmemory($result_res_rep,$i);

     for ($linha=15;$linha<=28;$linha++){
          if (substr($estrutural,0,1)=="6"){ // RESULTADOS(6) 
	       if (in_array($estrutural,$m_receita[$linha]['estrut'])){
                     $m_receita[$linha]['bimestre']  += $saldo_anterior_credito-$saldo_anterior_debito;
                     $m_receita[$linha]['exercicio'] += $saldo_final;
	       } 
	  }
     }
}

for ($i=0; $i < pg_numrows($result_res_rep_ant); $i++) {
     db_fieldsmemory($result_res_rep_ant,$i);
     
     for ($linha=15;$linha<=28;$linha++){
          if (substr($estrutural,0,1)=="6"){ // RESULTADOS(6) 
	       if (in_array($estrutural,$m_receita[$linha]['estrut'])){
                     $m_receita[$linha]['anterior'] += $saldo_anterior;
               }
	  }
     }
}

for ($col=1;$col<=5;$col++){ 
     $pcol =array(1=>'inicial',2=>'atualizada',3=>'bimestre',4=>'exercicio',5=>'anterior');
  
// Servidor Ativo, Inativo e Pensionista Civil
     $receita[3][$pcol[$col]]  = $m_receita[1][$pcol[$col]]+$m_receita[2][$pcol[$col]]+$m_receita[3][$pcol[$col]];
// Servidor Ativo
     $receita[4][$pcol[$col]]  = $m_receita[1][$pcol[$col]];
// Servidor Inativo     
     $receita[5][$pcol[$col]]  = $m_receita[2][$pcol[$col]];
// Pensionista Civil     
     $receita[6][$pcol[$col]]  = $m_receita[3][$pcol[$col]];
// Militar Ativo, Inativo e Pensionista
     $receita[7][$pcol[$col]]  = $m_receita[4][$pcol[$col]]+$m_receita[5][$pcol[$col]]+$m_receita[6][$pcol[$col]];
// Militar Ativo
     $receita[8][$pcol[$col]]  = $m_receita[4][$pcol[$col]];
// Militar Inativo     
     $receita[9][$pcol[$col]]  = $m_receita[5][$pcol[$col]];
// Pensionista Militar
     $receita[10][$pcol[$col]] = $m_receita[6][$pcol[$col]];
// Outras Contr. Prev.     
     $receita[11][$pcol[$col]] = $m_receita[7][$pcol[$col]];
// Compens. Prev. entre RGPS e RPPS     
     $receita[12][$pcol[$col]] = $m_receita[8][$pcol[$col]];
// Receita de Contribuição     
     $receita[2][$pcol[$col]]  = $receita[3][$pcol[$col]]+$receita[7][$pcol[$col]]+$receita[11][$pcol[$col]]+$receita[12][$pcol[$col]];
// Receita Imobiliaria    
     $receita[14][$pcol[$col]] = $m_receita[9][$pcol[$col]];
// Receita de Valores Mobiliarios
     $receita[15][$pcol[$col]] = $m_receita[10][$pcol[$col]];
// Outras Receitas Patrimoniais
     $receita[16][$pcol[$col]] = $m_receita[11][$pcol[$col]];
// Outras Receitas Correntes
     $receita[17][$pcol[$col]] = $m_receita[12][$pcol[$col]];
// Receita Patrimonial
     $receita[13][$pcol[$col]] = $receita[14][$pcol[$col]]+$receita[15][$pcol[$col]]+$receita[16][$pcol[$col]];
// Receita Corrente     
     $receita[1][$pcol[$col]]  = $receita[2][$pcol[$col]]+$receita[13][$pcol[$col]]+$receita[17][$pcol[$col]];
// Alienacao de Bens
     $receita[19][$pcol[$col]] = $m_receita[13][$pcol[$col]];
// Outras Receitas de Capital
     $receita[20][$pcol[$col]] = $m_receita[14][$pcol[$col]];
// Receitas de Capital     
     $receita[18][$pcol[$col]] = $receita[19][$pcol[$col]]+$receita[20][$pcol[$col]];
// Patronal Ativo Civil     
     $receita[24][$pcol[$col]] = $m_receita[15][$pcol[$col]];
// Patronal Inativo Civil
     $receita[25][$pcol[$col]] = $m_receita[16][$pcol[$col]];
// Patronal Pensionista Civil     
     $receita[26][$pcol[$col]] = $m_receita[17][$pcol[$col]];
// Contribuicao Patronal - Pessoal Civil     
     $receita[23][$pcol[$col]] = $receita[24][$pcol[$col]]+$receita[25][$pcol[$col]]+$receita[26][$pcol[$col]];
// Patronal Militar Ativo
     $receita[28][$pcol[$col]] = $m_receita[18][$pcol[$col]];
// Patronal Militar Inativo 
     $receita[29][$pcol[$col]] = $m_receita[19][$pcol[$col]];
// Patronal Pensionista Militar     
     $receita[30][$pcol[$col]] = $m_receita[20][$pcol[$col]];
// Contribuicao Patronal - Pessoal Militar     
     $receita[27][$pcol[$col]] = $receita[28][$pcol[$col]]+$receita[29][$pcol[$col]]+$receita[30][$pcol[$col]];
// Contribuicao Patronal do Exercicio
     $receita[22][$pcol[$col]] = $receita[23][$pcol[$col]]+$receita[27][$pcol[$col]];
// Patronal Ativo Civil - Exercicio Anterior
     $receita[33][$pcol[$col]] = $m_receita[21][$pcol[$col]];
// Patronal Inativo Civil - Exercicio Anterior
     $receita[34][$pcol[$col]] = $m_receita[22][$pcol[$col]];
// Patronal Pensionista Civil - Exercicio Anterior    
     $receita[35][$pcol[$col]] = $m_receita[23][$pcol[$col]];
// Contribuicao Patronal - Pessoal Civil - Exercicio Anterior    
     $receita[32][$pcol[$col]] = $receita[33][$pcol[$col]]+$receita[34][$pcol[$col]]+$receita[35][$pcol[$col]];
// Patronal Militar Ativo - Exercicio Anterior
     $receita[37][$pcol[$col]] = $m_receita[24][$pcol[$col]];
// Patronal Militar Inativo - Exercicio Anterior
     $receita[38][$pcol[$col]] = $m_receita[25][$pcol[$col]];
// Patronal Pensionista Militar - Exercicio Anterior    
     $receita[39][$pcol[$col]] = $m_receita[26][$pcol[$col]];
// Contribuicao Patronal - Pessoal Militar - Exercicio Anterior    
     $receita[36][$pcol[$col]] = $receita[28][$pcol[$col]]+$receita[29][$pcol[$col]]+$receita[30][$pcol[$col]];
// Contribuicao Patronal do Exercicio Anterior
     $receita[31][$pcol[$col]] = $receita[32][$pcol[$col]]+$receita[36][$pcol[$col]];
// Repasse Prev. Recebidos pelo RPPS
     $receita[21][$pcol[$col]] = $receita[22][$pcol[$col]]+$receita[31][$pcol[$col]];
// Repasse Prev. para Cobertura de Deficit
     $receita[40][$pcol[$col]] = $m_receita[27][$pcol[$col]];
// Outros Aportes ao RPPS
     $receita[41][$pcol[$col]] = $m_receita[28][$pcol[$col]];
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Despesas
$despesa  = array();
$despesa[1]['txt']  = "ADMINISTRAÇÃO(VII)";
$despesa[2]['txt']  = " Despesas Correntes";
$despesa[3]['txt']  = " Despesas de Capital";
$despesa[4]['txt']  = "PREVIDÊNCIA SOCIAL(VIII)";
$despesa[5]['txt']  = " Pessoal Civil";
$despesa[6]['txt']  = "    Aposentadorias";
$despesa[7]['txt']  = "    Pensões";
$despesa[8]['txt']  = "    Outros Benefícios Previdenciários";
$despesa[9]['txt']  = " Pessoal Militar";
$despesa[10]['txt'] = "    Reformas";
$despesa[11]['txt'] = "    Pensões";
$despesa[12]['txt'] = "    Outros Benefícios Previdenciários";
$despesa[13]['txt']  = " Outras Despesas Previdenciárias";
$despesa[14]['txt'] = "    Compensação Previdenciária de Aposentadorias entre o RPPS e o RGPS";
$despesa[15]['txt'] = "    Compensação Previdenciária de Pensões entre o RPPS e o RGPS";
$despesa[16]['txt']  = "RESERVA DO RPPS(IX)";

for ($linha=1;$linha<=16;$linha++){
	$despesa[$linha]['inicial']    = 0;
	$despesa[$linha]['atualizada'] = 0;
	$despesa[$linha]['bimestre']   = 0;
	$despesa[$linha]['exercicio']  = 0; // ate o bimestre
	$despesa[$linha]['anterior']   = 0; // ate o bimestre exercicio anterior
}	

$db_filtro = "o58_instit in (".$instit.") ";

// Exercicio Atual
$result_despesa = db_dotacaosaldo(8,2, 3, true, $db_filtro, $anousu, $dt_ini, $dt_fin);
for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
     db_fieldsmemory($result_despesa, $i);
     	
    for ($linha=29;$linha<=39;$linha++){
	 $nivel        = $m_despesa[$linha]['nivel'];
	 $estrutural   = $o58_elemento.'00';
    	 $estrutural   = substr($estrutural,0,$nivel);
    	 $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	
		 	
    	if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
	      $m_despesa[$linha]['inicial']    += $dot_ini; 
	      $m_despesa[$linha]['atualizada'] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
	      $m_despesa[$linha]['bimestre']   += $liquidado;  
	      $m_despesa[$linha]['exercicio']  += $liquidado_acumulado;
       	}
    }     
}

// Exercicio Anterior
$result_despesa_ant = db_dotacaosaldo(8,2, 3, true, $db_filtro, $anousu_ant, $dt_ini_ant, $dt_fin_ant);
for ($i = 0; $i < pg_numrows($result_despesa_ant); $i ++) {
     db_fieldsmemory($result_despesa_ant, $i);
     	
    for ($linha=29;$linha<=39;$linha++){
	 $nivel        = $m_despesa[$linha]['nivel'];
	 $estrutural   = $o58_elemento.'00';
    	 $estrutural   = substr($estrutural,0,$nivel);
    	 $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	
		 	
    	if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
	      $m_despesa[$linha]['anterior'] += $liquidado_acumulado;
       	}
    }     
}

for ($col=1;$col<=5;$col++){ 
     $pcol =array(1=>'inicial',2=>'atualizada',3=>'bimestre',4=>'exercicio',5=>'anterior');
  
// Despesas Correntes
     $despesa[2][$pcol[$col]]  = $m_despesa[29][$pcol[$col]];
// Despesas de Capital     
     $despesa[3][$pcol[$col]]  = $m_despesa[30][$pcol[$col]];
// Administracao
     $despesa[1][$pcol[$col]]  = $despesa[2][$pcol[$col]]+$despesa[3][$pcol[$col]];
// Aposentadorias Civil
     $despesa[6][$pcol[$col]]  = $m_despesa[31][$pcol[$col]];
// Pensoes Civil
     $despesa[7][$pcol[$col]]  = $m_despesa[32][$pcol[$col]];
// Outros Beneficios Prev. Civil 
     $despesa[8][$pcol[$col]]  = $m_despesa[33][$pcol[$col]];
// Pessoal Civil
     $despesa[5][$pcol[$col]]  = $despesa[6][$pcol[$col]]+$despesa[7][$pcol[$col]]+$despesa[8][$pcol[$col]];
// Reformas 
     $despesa[10][$pcol[$col]] = $m_despesa[34][$pcol[$col]];
// Pensoes Militar
     $despesa[11][$pcol[$col]] = $m_despesa[35][$pcol[$col]];
// Outros Beneficios Prev. Militar 
     $despesa[12][$pcol[$col]] = $m_despesa[36][$pcol[$col]];
// Pessoal Militar
     $despesa[9][$pcol[$col]]  = $despesa[10][$pcol[$col]]+$despesa[11][$pcol[$col]]+$despesa[12][$pcol[$col]];
// Compensacao Prev. de Aposentadorias
     $despesa[14][$pcol[$col]] = $m_despesa[37][$pcol[$col]];
// Compensacao Prev. de Pensoes 
     $despesa[15][$pcol[$col]] = $m_despesa[38][$pcol[$col]];
// Outras Despesas Prev. 
     $despesa[13][$pcol[$col]] = $despesa[14][$pcol[$col]]+$despesa[15][$pcol[$col]];
// Prev. Social 
     $despesa[4][$pcol[$col]]  = $despesa[5][$pcol[$col]]+$despesa[9][$pcol[$col]]+$despesa[13][$pcol[$col]];
// Reserva do RPPS 
     $despesa[16][$pcol[$col]]  = $m_despesa[39][$pcol[$col]];
}

$total_desp_inicial     = 0;
$total_desp_atualizada  = 0;
$total_desp_bimestre    = 0;
$total_desp_exercicio   = 0;
$total_desp_anterior    = 0;

$res_prev_inicial       = 0; 
$res_prev_atualizada    = 0;
$res_prev_bimestre      = 0;
$res_prev_exercicio     = 0;
$res_prev_anterior      = 0;

$total_desp_inicial     = $despesa[1]['inicial']    + $despesa[4]['inicial']    + $despesa[16]['inicial']; 
$total_desp_atualizada  = $despesa[1]['atualizada'] + $despesa[4]['atualizada'] + $despesa[16]['atualizada']; 
$total_desp_bimestre    = $despesa[1]['bimestre']   + $despesa[4]['bimestre']   + $despesa[16]['bimestre']; 
$total_desp_exercicio   = $despesa[1]['exercicio']  + $despesa[4]['exercicio']  + $despesa[16]['exercicio']; 
$total_desp_anterior    = $despesa[1]['anterior']   + $despesa[4]['anterior']   + $despesa[16]['anterior']; 

$res_prev_inicial       = $total_desp_inicial; 
$res_prev_atualizada    = $total_desp_atualizada;
$res_prev_bimestre      = $total_desp_bimestre;
$res_prev_exercicio     = $total_desp_exercicio;
$res_prev_anterior      = $total_desp_anterior;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Disponibilidades e Investimentos Financeiros
$disponivel  = array();
$disponivel[1]['txt'] = "Caixa";
$disponivel[2]['txt'] = "Bancos Conta Movimento";
$disponivel[3]['txt'] = "Investimentos";

for ($linha=1;$linha<=3;$linha++){
	$disponivel[$linha]['saldo_inicial']          = 0;
	$disponivel[$linha]['saldo_periodo_atual']    = 0;
	$disponivel[$linha]['saldo_periodo_anterior'] = 0;
}	
$db_filtro = "c61_instit in (".$instit.") ";

// saldo inicial
// demonstra o saldo do mes anterior ao periodo de referencia

$dt_ini_per = split('-',$dt_fin);
$dt_ini_per = $dt_ini_per[0].'-'.$dt_ini_per[1].'-01';

/*
$result_disponivel = db_planocontassaldo_matriz($anousu,$dt_ini_per,$dt_fin,false,$db_filtro);
for ($i=0; $i < pg_numrows($result_disponivel); $i++){
     db_fieldsmemory($result_disponivel,$i);
     
     for ($linha=40;$linha<=42;$linha++){
    	  if (in_array($estrutural,$m_disponivel[$linha]['estrut'])){
	    	$m_disponivel[$linha]['saldo_inicial'] += $saldo_inicial;
	  }
     }
}
@pg_exec("drop table work_pl");
*/

// Exercicio Atual
$result_disponivel = db_planocontassaldo_matriz($anousu,$dt_ini_per,$dt_fin,false,$db_filtro);
for ($i=0; $i < pg_numrows($result_disponivel); $i++){
     db_fieldsmemory($result_disponivel,$i);
     
     for ($linha=40;$linha<=42;$linha++){
    	  if (in_array($estrutural,$m_disponivel[$linha]['estrut'])){
	        $m_disponivel[$linha]['saldo_inicial']       += $saldo_anterior;
	    	$m_disponivel[$linha]['saldo_periodo_atual'] += $saldo_final;
	  }
     }
}
@pg_exec("drop table work_pl");
// Exercicio Anterior
$result_disponivel_ant = db_planocontassaldo_matriz($anousu_ant,$dt_ini_ant,$dt_fin_ant,false,$db_filtro);
for ($i=0; $i < pg_numrows($result_disponivel_ant); $i++){
     db_fieldsmemory($result_disponivel_ant,$i);
     
     for ($linha=40;$linha<=42;$linha++){
    	  if (in_array($estrutural,$m_disponivel[$linha]['estrut'])){
	    	$m_disponivel[$linha]['saldo_periodo_anterior'] += $saldo_final;
	  }
     }
}

for ($col=1;$col<=3;$col++){ 
     $pcol = array(1=>'saldo_inicial','2'=>'saldo_periodo_atual',3=>'saldo_periodo_anterior');

     $disponivel[1][$pcol[$col]]  = $m_disponivel[40][$pcol[$col]];
     $disponivel[2][$pcol[$col]]  = $m_disponivel[41][$pcol[$col]];
     $disponivel[3][$pcol[$col]]  = $m_disponivel[42][$pcol[$col]];
}

// adiciona a reserva do rpps nas receitas


$receita[21]['inicial']   += $receita[22]['inicial']   += $receita[23]['inicial']   += $receita[24]['inicial']   += $despesa[16]['inicial'];
$receita[21]['atualizada']+= $receita[22]['atualizada']+= $receita[23]['atualizada']+= $receita[24]['atualizada']+= $despesa[16]['atualizada'];
//$receita[21]['bimestre']  += $receita[22]['bimestre']  += $receita[23]['bimestre']  += $receita[24]['bimestre']  += $despesa[16]['bimestre'];
//$receita[21]['exercicio'] += $receita[22]['exercicio'] += $receita[23]['exercicio'] += $receita[24]['exercicio'] += $despesa[16]['exercicio'];
//$receita[21]['anterior']  += $receita[22]['anterior']  += $receita[23]['anterior']  += $receita[24]['anterior']  += $despesa[16]['anterior'];

$total_rec_inicial       = 0;
$total_rec_atualizada    = 0;
$total_rec_bimestre      = 0;
$total_rec_exercicio     = 0;
$total_rec_anterior      = 0;

$total_rec_inicial    = $receita[1]['inicial']    + $receita[18]['inicial']    + $receita[21]['inicial']    + $receita[40]['inicial']    + $receita[41]['inicial'];
$total_rec_atualizada = $receita[1]['atualizada'] + $receita[18]['atualizada'] + $receita[21]['atualizada'] + $receita[40]['atualizada'] + $receita[41]['atualizada'];
$total_rec_bimestre   = $receita[1]['bimestre']   + $receita[18]['bimestre']   + $receita[21]['bimestre']   + $receita[40]['bimestre']   + $receita[41]['bimestre'];
$total_rec_exercicio  = $receita[1]['exercicio']  + $receita[18]['exercicio']  + $receita[21]['exercicio']  + $receita[40]['exercicio']  + $receita[41]['exercicio'];
$total_rec_anterior   = $receita[1]['anterior']   + $receita[18]['anterior']   + $receita[21]['anterior']   + $receita[40]['anterior']   + $receita[41]['anterior'];



if (!isset($arqinclude)){ //
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Imprimindo Relatorio
  $perini = $dt_ini;
  $perfin = $dt_fin;
 
  $resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where db21_tipoinstit in (5,6)");
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

  if ($flag_abrev == false){
       if (strlen($descr_inst) > 42){
            $descr_inst = substr($descr_inst,0,100);
       }
  }

  $head1 = $descr_inst;
  $head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS DO RPPS";
  $head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  $txt = strtoupper(db_mes('01'));
  $dt  = split("-",$dt_fin);
  $txt.= " À ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";;
  $dt  = split("-",$dt_ini);
  $txt.= strtoupper(db_mes($dt[1]))."-";
  $dt  = split("-",$dt_fin);
  $txt.= strtoupper(db_mes($dt[1]));
  $head5 = "$txt";
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $troca = 1;
  $alt = 4;
  
  $pagina = 1;
  $tottotal = 0;
  $pagina = 0;
  $n1 =5;
  $n2=10;
  
  $pdf->addpage();
  $pdf->setfont('arial','',6);
  $pdf->cell(90,$alt,"LRF, Art.53, inciso II - Anexo V",0,0,"L",0);
  $pdf->cell(100,$alt,"R$",0,1,"R",0);
  
  $pdf->setfont('arial','',6);
  $pdf->cell(90,($alt*2),"RECEITAS PREVIDENCIÁRIAS",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVISÃO","TR",0,"C",0);
  $pdf->cell(20,$alt,"PREVISÃO","TR",0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);  //br
  $pdf->setX(100); 
  $pdf->cell(20,$alt,"INICIAL","BR",0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA","BR",0,"C",0);
  $pdf->setX(140); 
  $pdf->cell(20,$alt,"No Bimestre","TBR",0,"C",0);
  $pdf->cell(20,$alt,"Até o Bimestre/".$anousu,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"Até o Bimestre/".$anousu_ant,'TB',0,"C",0);
  $pdf->ln();
  
  for($linha=1;$linha<=41;$linha++){
     $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),'R',0,"R",0);    
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['anterior'],'f'),0,0,"R",0);       
     $pdf->Ln();	    
  }
 
  $pdf->cell(90,$alt,"TOTAL DAS RECEITAS PREVIDENCIÁRIAS(VI)=(I+II+III+IV+V)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_inicial,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_atualizada,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_bimestre,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_exercicio,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_anterior,'f'),"TB",0,"R",0);
  $pdf->ln();
 
  $pdf->cell(150,($alt*2),'Continua na página 2',0,0,"L",0);
  $pdf->addpage();
 
  $pdf->cell(150,$alt,'Continuação da página 1',0,0,"L",0);
  $pdf->Ln();

  $pdf->Ln(3);
  $pdf->cell(90,($alt*2),"DESPESAS PREVIDENCIÁRIAS",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"DOTAÇÃO","TR",0,"C",0);
  $pdf->cell(20,$alt,"DOTAÇÃO","TR",0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);  //br
  $pdf->setX(100); 
  $pdf->cell(20,$alt,"INICIAL","BR",0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA","BR",0,"C",0);
  $pdf->setX(140); 
  $pdf->cell(20,$alt,"No Bimestre","TBR",0,"C",0);
  $pdf->cell(20,$alt,"Até o Bimestre/".$anousu,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"Até o Bimestre/".$anousu_ant,'TB',0,"C",0);
  $pdf->ln();
 
  for($linha=1;$linha<=16;$linha++){
     $pdf->cell(90,$alt,$despesa[$linha]['txt'],'R',0,"L",0);
     $pdf->cell(20,$alt,db_formatar($despesa[$linha]['inicial'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($despesa[$linha]['atualizada'],'f'),'R',0,"R",0);    
     $pdf->cell(20,$alt,db_formatar($despesa[$linha]['bimestre'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($despesa[$linha]['exercicio'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($despesa[$linha]['anterior'],'f'),0,0,"R",0);       
     $pdf->Ln();	    
  }
 
  $pdf->cell(90,$alt,"TOTAL DAS DESPESAS PREVIDENCIÁRIAS(X)=(VII+VIII+IX)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($total_desp_inicial,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_desp_atualizada,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_desp_bimestre,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_desp_exercicio,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_desp_anterior,'f'),"TB",0,"R",0);
  $pdf->ln();
 
  $pdf->cell(90,$alt,"RESULTADO PREVIDENCIÁRIO(XI)=(VI-X)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_inicial-$total_desp_inicial,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_atualizada-$total_desp_atualizada,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_bimestre-$total_desp_bimestre,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_exercicio-$total_desp_exercicio,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_anterior-$total_desp_anterior,'f'),"TB",0,"R",0);
  $pdf->ln();
 
  $pdf->cell(190,$alt,"",'TB',1,"L",0);
 
  $pdf->cell(90,($alt*2),"SALDO DAS DISPONIBILIDADES FINANCEIRAS E INVESTIMENTOS DO RPPS",'TBR',0,"C",0);
  $pdf->cell(40,$alt,$periodo_mes."/".$anousu,"TR",0,"C",0);
  $pdf->cell(60,$alt,"PERÍODO DE REFERÊNCIA",'TB',1,"C",0);  //br
  $pdf->setX(100); 
  $pdf->cell(20,$alt,"","B",0,"C",0);
  $pdf->cell(20,$alt,"","BR",0,"C",0);
  $pdf->setX(140); 
  $pdf->cell(30,$alt,$anousu,"TBR",0,"C",0);
  $pdf->cell(30,$alt,$anousu_ant,'TB',0,"C",0);
  $pdf->ln();
 
  for($linha=1;$linha<=3;$linha++){
      $pdf->cell(90,$alt,$disponivel[$linha]['txt'],'R',0,"L",0);
      $pdf->cell(40,$alt,db_formatar($disponivel[$linha]['saldo_inicial'],'f'),'BR',0,"R",0);
      $pdf->cell(30,$alt,db_formatar($disponivel[$linha]['saldo_periodo_atual'],'f'),'BR',0,"R",0);
      $pdf->cell(30,$alt,db_formatar($disponivel[$linha]['saldo_periodo_anterior'],'f'),'B',0,"R",0);    
      $pdf->Ln();	    
  }
 
  $pdf->cell(90,$alt,"FONTE: Contabilidade","T",0,"L",0);
 
  //assinaturas
  $pdf->ln(30);
 
  assinaturas(&$pdf,&$classinatura,'LRF');
 
  $pdf->Output();
 
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>