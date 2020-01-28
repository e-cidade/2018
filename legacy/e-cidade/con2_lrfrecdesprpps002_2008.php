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
   //$pdf = new fpdf();
 //notasExplicativas(&$pdf,23,"{$periodo}",180); 
 //exit;
// PARAMETROS de 
//receitas previdenciarias;
for ($linha=1;$linha<=19;$linha++){	
	$m_receita[$linha]['estrut']     = $orcparamrel->sql_parametro('23',$linha,"f",$instituicao,db_getsession("DB_anousu"));
	$m_receita[$linha]['inicial']    = 0;
	$m_receita[$linha]['atualizada'] = 0;
	$m_receita[$linha]['bimestre']   = 0;
	$m_receita[$linha]['exercicio']  = 0;  // ate o bimestre
	$m_receita[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
}

//parametros de despesa
for ($linha=20; $linha<=30; $linha++){	
	
	$m_despesa[$linha]['estrut']     = $orcparamrel->sql_parametro('23',$linha,"f",$instituicao,db_getsession("DB_anousu"));
	$m_despesa[$linha]['nivel']      = $orcparamrel->sql_nivel('23',$linha);

  if ($linha >= 20 && $linha <= 29){
    $m_despesa[$linha]["funcao"]  = $orcparamrel->sql_funcao("23",$linha);
  } else {
    $m_despesa[$linha]["funcao"]  = -1;
  }

	$m_despesa[$linha]['inicial']    = 0;
	$m_despesa[$linha]['atualizada'] = 0;
	$m_despesa[$linha]['bimestre']   = 0;
	$m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
	$m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior

  $m_despesa[$linha]['rpnp_exe']   = 0;  // RP nao processado exercicio
  $m_despesa[$linha]['rpnp_ant']   = 0;  // RP nao processado exercicio anterior
}

/*
echo "<xmp>";
print_r($m_despesa);
echo "</xmp>";
*/

//paramentros de disponibilidade
for($linha=31; $linha<=33; $linha++){
  $m_disponivel[$linha]['estrut']                 = $orcparamrel->sql_parametro('23',$linha,"f",$instituicao,db_getsession("DB_anousu"));
  $m_disponivel[$linha]['saldo_inicial']          = 0;
  $m_disponivel[$linha]['saldo_periodo_atual']    = 0;       
  $m_disponivel[$linha]['saldo_periodo_anterior'] = 0;
}

//paramentros de receitas intra orçamentarias
for ($linha = 34; $linha <= 47; $linha++){	
  $m_receita[$linha]['estrut']     = $orcparamrel->sql_parametro('23',$linha,"f",$instituicao,db_getsession("DB_anousu")); 
	$m_receita[$linha]['inicial']    = 0;
	$m_receita[$linha]['atualizada'] = 0;
	$m_receita[$linha]['bimestre']   = 0;
	$m_receita[$linha]['exercicio']  = 0;  // ate o bimestre
	$m_receita[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
}	

for ($linha = 48; $linha <= 50; $linha++){	

  $m_despesa[$linha]['estrut']     = $orcparamrel->sql_parametro('23',$linha,"f",$instituicao,db_getsession("DB_anousu")); 
	$m_despesa[$linha]['nivel']      = $orcparamrel->sql_nivel('23',$linha);

  if ($linha >= 48 && $linha <= 49){
    $m_despesa[$linha]["funcao"]   = $orcparamrel->sql_funcao("23",$linha);
  } else {
    $m_despesa[$linha]["funcao"]   = -1;
  }

	$m_despesa[$linha]['inicial']    = 0;
	$m_despesa[$linha]['atualizada'] = 0;
	$m_despesa[$linha]['bimestre']   = 0;
	$m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
	$m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior

  $m_despesa[$linha]['rpnp_exe']   = 0;  // RP nao processado exercicio
  $m_despesa[$linha]['rpnp_ant']   = 0;  // RP nao processado exercicio anterior
}	

/*
echo "<xmp>";
print_r($m_despesa);
echo "</xmp>";
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Receitas
$receita  = array();
$receita[0]['txt']  = " RECEITAs PREVIDENCIÁRIAS - RPPS (EXCETO INTRA-ORÇAMENTÁRIAS)(I)";
$receita[1]['txt']  = " RECEITAS CORRENTES";
$receita[2]['txt']  = "  Receita de Contribuições";
$receita[3]['txt']  = "   Pessoal Civil";
$receita[4]['txt']  = "      Contribuição de Servidor Ativo Civil"; // linha 1
$receita[5]['txt']  = "      Contribuição de Servidor Inativo Civil"; // linha 2
$receita[6]['txt']  = "      Contribuição de Pensionista Civil"; // linha 3
$receita[7]['txt']  = "   Pessoal Militar";
$receita[8]['txt']  = "      Contribuição de Militar Ativo"; // linha 4
$receita[9]['txt']  = "      Contribuição de Militar Inativo"; // linha 5 
$receita[10]['txt'] = "      Contribuição de Pensionista Militar"; // linha 6
$receita[11]['txt'] = "  Receita Patrimonial"; 
$receita[12]['txt'] = "   Receitas Imobiliárias"; // linha 7
$receita[13]['txt'] = "   Receitas de Valores Mobiliários"; // 8
$receita[14]['txt'] = "   Outras Receitas Patrimoniais"; // 9
$receita[15]['txt'] = "  Receita de Serviços"; //10 
$receita[16]['txt'] = "  Outras Receitas Correntes"; // total outras receitas.
$receita[17]['txt'] = "   Compensação Previdenciária entre RGPS e RPPS";  //11
$receita[18]['txt'] = "  Outras Receitas Correntes"; // 12
$receita[19]['txt'] = " RECEITAS DE CAPITAL"; // totalizador
$receita[20]['txt'] = "  Alienação de Bens"; // 13
$receita[21]['txt'] = "  Amortização de Empréstimos"; // 14
$receita[22]['txt'] = "  Outras Receitas de Capital"; // 15
$receita[23]['txt'] = " (-) DEDUÇÕES DA RECEITA"; // 16
$receita[24]['txt'] = "RECEITAS PREVIDENCIÁRIAS - RPPS (INTRA-ORÇAMENTÁRIAS) (II)";//totalizar das intras 
$receita[25]['txt'] = "REPASSES PREVIDENCIARIOS PARA COBERTURA DE DÉFICIT ATUARIAL - RPPS (III)"; //17; 
$receita[26]['txt'] = "REPASSES PREVIDENCIARIOS PARA COBERTURA DE DÉFICIT FINANCEIRO - RPPS (IV)"; //18; 
$receita[27]['txt'] = "OUTROS APORTES AO RPPS (V)"; //19; 




//RECEITAS INTRA-ORÇAMENTÁRIAS
$receita[28]['txt'] = "RECEITAS CORRENTES";
$receita[29]['txt'] = " Receita de Contribuições";
$receita[30]['txt'] = "   Pessoal Civil";
$receita[31]['txt'] = "      Contribuição Patronal Ativo Civil"; // 34
$receita[32]['txt'] = "      Contribuição Patronal Inativo Civil"; // 35
$receita[33]['txt'] = "      Contribuição Patronal Pensionista Civil"; // 36
$receita[34]['txt'] = "   Pessoal Militar";
$receita[35]['txt'] = "      Contribuição Patronal Militar Ativo"; // 37
$receita[36]['txt'] = "      Contribuição Patronal Militar Inativo"; // 38
$receita[37]['txt'] = "      Contribuição Patronal Pensionista Militar"; // 39
$receita[38]['txt'] = "   Contribuição Previdenciária Para Cobertura de Déficit Atuarial"; //40
$receita[39]['txt'] = "   Contribuição Previdenciária em Regime de Débitos e Parcelamentos"; //41

$receita[40]['txt'] = " Receita Patrimonial"; // 42
$receita[41]['txt'] = " Outras receitas Correntes";//43
$receita[42]['txt'] = "RECEITAS DE CAPITAL";
$receita[43]['txt'] = " Alienação de Bens";//44
$receita[44]['txt'] = " Amortização de Empréstimos";//45
$receita[45]['txt'] = " Outras Receitas de Capital";//46
$receita[46]['txt'] = "(-) DEDUÇÕES DA RECEITA";//47

for ($linha=1;$linha<=46;$linha++){
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

// Caso o Periodo Seja 6B (Sexto Bimestre) ou 3Q (Terceiro Quadrimestre) ou 2S (Segundo Simestre)
// seta $ultimo_periodo como true, caso contrario false
$ultimo_periodo = ($periodo=="6B") || ($periodo=="3Q") || ($periodo=="2S");

$dt          = split("-",$dt_ini);
$periodo_mes = strtoupper(db_mes($dt[1]));

$dt = split("-",$dt_ini);
$dt_ini_ant = $anousu_ant."-".$dt[1]."-".$dt[2];

$dt = split("-",$dt_fin);
if ($dt[1] == 2){
   $dt[2]  = 28;
}
$dt_fin_ant = $anousu_ant."-".$dt[1]."-".$dt[2];

// RPPS ///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// seleciona instituição do RPPS

$instit ='';
$sql    = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
$resultinst = pg_exec($sql);
if  (pg_numrows($resultinst)>0){
$instit ='';
}
else{
$instit=$instituicao;
}
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $instit     .= $xvirg.$codigo; // salva insituição
  $xvirg       = ', ';		  
}
$db_filtro  = " o70_instit in (".$instit.")";

// Exercicio Atual
$result_rec     = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
@pg_query("drop table work_receita");

// Exercicio Anterior
$result_rec_ant = db_receitasaldo(11,1,3,true,$db_filtro,$anousu_ant,$dt_ini_ant,$dt_fin_ant);
@pg_query("drop table work_receita");
//db_criatabela($result_rec); exit;

$db_filtro_balver  = " c61_instit in (".$instit.")" ;
$result_balver     = db_planocontassaldo_matriz($anousu,    $dt_ini,    $dt_fin,    false,$db_filtro_balver);

@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");

$result_balver_ant = db_planocontassaldo_matriz($anousu_ant,$dt_ini_ant,$dt_fin_ant,false,$db_filtro_balver);

@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");

/*
echo "<pre>";
print_r($m_receita[17]["estrut"]);
print_r($m_receita[18]["estrut"]);
echo "</pre>";

exit;
*/

$linha = 17;
while ($linha <= 19) {
  foreach($m_receita[$linha]["estrut"] as $estrut_inicial){
    
    if (db_conplano_grupo($anousu,substr($estrut_inicial,0,1)."%",9004) == false) {
      // NAO E RECEITA ORCAMENTARIA
      for ($ii = 0; $ii < pg_numrows($result_balver); $ii++) {
        db_fieldsmemory($result_balver,$ii);
        $estrut_balver = $estrutural;
        
        if ($estrut_balver == $estrut_inicial) {
          $m_receita[$linha]["exercicio"] += ($saldo_anterior_credito - $saldo_anterior_debito);
        }
      }
    } else {
      for ($i=0; $i < pg_numrows($result_rec); $i++) {
        db_fieldsmemory($result_rec,$i);
        $estrut = $o57_fonte;
        
        if (in_array($estrut,$m_receita[$linha]['estrut'])) {
          
          $m_receita[$linha]['inicial']    += $saldo_inicial;
          $m_receita[$linha]['atualizada'] += $saldo_inicial_prevadic;
          $m_receita[$linha]['bimestre']   += $saldo_arrecadado ;
          $m_receita[$linha]['exercicio']  += $saldo_arrecadado_acumulado;
          
        }
      }
    }
  }
  
  $linha++;
}

for ($i=0; $i < pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrut = $o57_fonte;
  
  for ($linha=1; $linha <= 16; $linha++) {
    if (in_array($estrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['inicial']    += $saldo_inicial;
      $m_receita[$linha]['atualizada'] += $saldo_inicial_prevadic;
      $m_receita[$linha]['bimestre']   += $saldo_arrecadado ;
      $m_receita[$linha]['exercicio']  += $saldo_arrecadado_acumulado;
    }
  }
  
  for ($linha=34; $linha <= 47; $linha++) {
    if (in_array($estrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['inicial']    += $saldo_inicial;
      $m_receita[$linha]['atualizada'] += $saldo_inicial_prevadic;
      $m_receita[$linha]['bimestre']   += $saldo_arrecadado ;
      $m_receita[$linha]['exercicio']  += $saldo_arrecadado_acumulado;
    }
  }
}

$linha = 17;
while ($linha <= 19) {
  foreach($m_receita[$linha]["estrut"] as $estrut_inicial){
    
    if (db_conplano_grupo($anousu_ant,substr($estrut_inicial,0,1)."%",9004) == false) {
      // NAO E RECEITA ORCAMENTARIA
      for ($ii = 0; $ii < pg_numrows($result_balver_ant); $ii++) {
        db_fieldsmemory($result_balver_ant,$ii);
        $estrut_balver = $estrutural;
        
        if ($estrut_balver == $estrut_inicial) {
          $m_receita[$linha]["anterior"] += ($saldo_anterior_credito - $saldo_anterior_debito);
        }
      }
    } else {
      for ($i=0; $i < pg_numrows($result_rec_ant); $i++) {
        db_fieldsmemory($result_rec_ant,$i);
        $estrut = $o57_fonte;
        
        if (in_array($estrut,$m_receita[$linha]['estrut'])) {
          $m_receita[$linha]['anterior'] += $saldo_arrecadado_acumulado;
        }
      }
    }
  }
  
  $linha++;
}

for ($i=0; $i < pg_numrows($result_rec_ant); $i++) {
  db_fieldsmemory($result_rec_ant,$i);
  $estrut = $o57_fonte;
  
  for ($linha=1; $linha <= 16; $linha++) {
    if (in_array($estrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['anterior'] += $saldo_arrecadado_acumulado;
    }
  }
  
  for ($linha=34; $linha <= 47; $linha++) {
    if (in_array($estrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['anterior'] += $saldo_arrecadado_acumulado;
    }
  }
  
}

$pcol = array(1 => 'inicial',
              2 => 'atualizada',
              3 => 'bimestre',
              4 => 'exercicio',
              5 => 'anterior');
$ipcol = count($pcol);

//calculamos os totais das receitas previdenciarias
for ($col = 1; $col <= $ipcol; $col++) {
  
  // total Servidor Ativo, Inativo e Pensionista Civil
  $receita[3][$pcol[$col]]  = $m_receita[1][$pcol[$col]]+$m_receita[2][$pcol[$col]]+$m_receita[3][$pcol[$col]];
  
  // Servidor Ativo
  $receita[4][$pcol[$col]]  = $m_receita[1][$pcol[$col]];
  
  // Servidor Inativo
  $receita[5][$pcol[$col]]  = $m_receita[2][$pcol[$col]];
  
  // Pensionista Civil
  $receita[6][$pcol[$col]]  = $m_receita[3][$pcol[$col]];
  
  // total Militar Ativo, Inativo e Pensionista
  $receita[7][$pcol[$col]]  = $m_receita[4][$pcol[$col]]+$m_receita[5][$pcol[$col]]+$m_receita[6][$pcol[$col]];
  
  // Militar Ativo
  $receita[8][$pcol[$col]]  = $m_receita[4][$pcol[$col]];
  
  // Militar Inativo
  $receita[9][$pcol[$col]]  = $m_receita[5][$pcol[$col]];
  
  // Pensionista Militar
  $receita[10][$pcol[$col]] = $m_receita[6][$pcol[$col]];
  //---------------------
  // total receita patrimonial
  $receita[11][$pcol[$col]] = $m_receita[7][$pcol[$col]]+$m_receita[8][$pcol[$col]]+$m_receita[9][$pcol[$col]];
  
  // receitas Imobiliarias
  $receita[12][$pcol[$col]] = $m_receita[7][$pcol[$col]];
  
  // receitas de Valores  Mobiliários
  $receita[13][$pcol[$col]] = $m_receita[8][$pcol[$col]];
  
  // outras receitas Patrimoniais
  $receita[14][$pcol[$col]] = $m_receita[9][$pcol[$col]];
  
  //receita de servicos
  $receita[15][$pcol[$col]] = $m_receita[10][$pcol[$col]];
  //---------------------------------
  // total Outras receitas correntes
  $receita[16][$pcol[$col]] = $m_receita[11][$pcol[$col]]+$m_receita[12][$pcol[$col]];
  
  // Compensacao Previdenciáriado RGPS para o rpps
  $receita[17][$pcol[$col]] = $m_receita[11][$pcol[$col]];
  
  // outras receitas correntes
  $receita[18][$pcol[$col]] = $m_receita[12][$pcol[$col]];
  
  // total receitas de capital
  $receita[19][$pcol[$col]] = $m_receita[13][$pcol[$col]]+$m_receita[14][$pcol[$col]]+$m_receita[15][$pcol[$col]];
  
  // alienacao de bens
  $receita[20][$pcol[$col]] = $m_receita[13][$pcol[$col]];
  
  // alienacao de bens
  $receita[21][$pcol[$col]] = $m_receita[14][$pcol[$col]];
  
  // outras receitas de capital
  $receita[22][$pcol[$col]] = $m_receita[15][$pcol[$col]];
  
  // deducoes 2da receita
  $receita[23][$pcol[$col]] = $m_receita[16][$pcol[$col]];
  
  // receitas previdenciarias cobertura deficit autarial
  $receita[25][$pcol[$col]] = $m_receita[17][$pcol[$col]];
  
  // receitas previdenciarias cobertura deficit financeiro
  $receita[26][$pcol[$col]] = $m_receita[18][$pcol[$col]];
  
  // outros aportes ao rpps
  $receita[27][$pcol[$col]] = $m_receita[19][$pcol[$col]];
  
  // total da Receita de Contribuição
  $receita[2][$pcol[$col]]  = $receita[3][$pcol[$col]]+$receita[7][$pcol[$col]];
  
  $receita[1][$pcol[$col]]  = $receita[2][$pcol[$col]]+$receita[11][$pcol[$col]]+$receita[15][$pcol[$col]]+$receita[16][$pcol[$col]];
  $receita[0][$pcol[$col]]  = ($receita[1][$pcol[$col]]+$receita[19][$pcol[$col]])-$receita[23][$pcol[$col]];
}

$pcol = array(1 => 'inicial',
              2 => 'atualizada',
              3 => 'bimestre',
              4 => 'exercicio',
              5 => 'anterior');
$ipcol = count($pcol);
//receitas intraorcamentarias
for ($col = 1; $col <= $ipcol; $col++) {
  
  //total receita  pessoal civil
  $receita[30][$pcol[$col]] = $m_receita[34][$pcol[$col]]+$m_receita[35][$pcol[$col]]+$m_receita[36][$pcol[$col]];
  
  //  Contribuição Patronal Ativo Civil";   // 15
  $receita[31][$pcol[$col]] = $m_receita[34][$pcol[$col]];
  
  //  Contribuição Patronal Inativo Civil";  // 16
  $receita[32][$pcol[$col]] = $m_receita[35][$pcol[$col]];
  
  //  Contribuição Patronal Pensionista Civil"; //17
  $receita[33][$pcol[$col]] = $m_receita[36][$pcol[$col]];
  
  //  total pessoal militar"; // 18
  $receita[34][$pcol[$col]] = $m_receita[37][$pcol[$col]]+$m_receita[38][$pcol[$col]]+$m_receita[39][$pcol[$col]];
  
  //  Contribuição Patronal Militar ativo"; // 19
  $receita[35][$pcol[$col]] = $m_receita[37][$pcol[$col]];
  
  //  Contribuição Patronal de pensionista Militar ; // 19
  $receita[36][$pcol[$col]] = $m_receita[38][$pcol[$col]];
  
  //  Contribuição Patronal Militar Inativo"; // 19
  $receita[37][$pcol[$col]] = $m_receita[39][$pcol[$col]];
  
  //  contribuição previdenciaria para cobertura de deficit atuarial; // 20
  $receita[38][$pcol[$col]] = $m_receita[40][$pcol[$col]];
  
  //  contribuição previdenciaria em regime de debitos e parcelamento"; // 20
  $receita[39][$pcol[$col]] = $m_receita[41][$pcol[$col]];
  
  // receita patrimonial // 21
  $receita[40][$pcol[$col]] = $m_receita[42][$pcol[$col]];
  
  /// Outras receitas Correntes"; // 25
  $receita[41][$pcol[$col]] = $m_receita[43][$pcol[$col]];
  
  
  //  alienacao de bens"; // 27
  $receita[43][$pcol[$col]] = $m_receita[44][$pcol[$col]];
  
  // Amortização dos emprestimos
  $receita[44][$pcol[$col]] = $m_receita[45][$pcol[$col]];
  
  // outras receitas de capital
  $receita[45][$pcol[$col]] = $m_receita[46][$pcol[$col]];
  
  // deducoes
  $receita[46][$pcol[$col]] = $m_receita[47][$pcol[$col]];
  
  
  // Total contribuicoes receitas
  $receita[29][$pcol[$col]] = $receita[30][$pcol[$col]]+$receita[34][$pcol[$col]]+$receita[38][$pcol[$col]]+$receita[39][$pcol[$col]];
  
  //total receitas correntes
  $receita[28][$pcol[$col]] = $receita[29][$pcol[$col]]+$receita[40][$pcol[$col]]+$receita[41][$pcol[$col]];
  
  //  receitas de capital"; // 26
  $receita[42][$pcol[$col]] = $m_receita[44][$pcol[$col]]+$m_receita[45][$pcol[$col]]+$m_receita[46][$pcol[$col]];
  
  //totas intra
  $receita[48][$pcol[$col]] = ($receita[28][$pcol[$col]]+$m_receita[42][$pcol[$col]])-$m_receita[47][$pcol[$col]];
  
  //totalizador da receita naointra(intra)
  $receita[24][$pcol[$col]] = $receita[48][$pcol[$col]];
  
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Despesas
$despesa  = array();
$despesa[0]['txt']  = "DESPESAS PREVIDENCIÁRIAS - RPPS (EXCETO INTRA-ORÇAMENTÁRIAS) (VII)";
$despesa[1]['txt']  = "ADMINISTRAÇÃO";
$despesa[2]['txt']  = " Despesas Correntes";
$despesa[3]['txt']  = " Despesas de Capital";
$despesa[4]['txt']  = "PREVIDÊNCIA SOCIAL";
$despesa[5]['txt']  = " Pessoal Civil";
$despesa[6]['txt']  = "    Aposentadorias";
$despesa[7]['txt']  = "    Pensões";
$despesa[8]['txt']  = "    Outros Benefícios Previdenciários";
$despesa[9]['txt']  = " Pessoal Militar";
$despesa[10]['txt'] = "    Reformas";
$despesa[11]['txt'] = "    Pensões";
$despesa[12]['txt'] = "    Outros Benefícios Previdenciários";
$despesa[13]['txt']  = " Outras Despesas Previdenciárias";
//$despesa[14]['txt'] = "    Compensação Previdenciária de Aposentadorias entre o RPPS e o RGPS";
$despesa[14]['txt'] = "    Compensação Previdenciária de Aposentadorias entre RPPS e RGPS";
$despesa[15]['txt'] = "    Demais Despesas Previdenciárias";
$despesa[16]['txt']  = "RESERVA DO RPPS(IX)";

for ($linha=1;$linha<=16;$linha++){
	$despesa[$linha]['inicial']    = 0;
	$despesa[$linha]['atualizada'] = 0;
	$despesa[$linha]['bimestre']   = 0;
	$despesa[$linha]['exercicio']  = 0; // ate o bimestre
	$despesa[$linha]['anterior']   = 0; // ate o bimestre exercicio anterior

	$despesa[$linha]['rpnp_exe']   = 0; // RP Nao Processado Exercicio
  $despesa[$linha]['rpnp_ant']   = 0; // RP Nao Processado Exercicio Anterior
}	
$db_filtro = "o58_instit in (".$instit.") ";

$despesa[44]['txt']  = "DESPESAS PREVIDENCIARIAS-RPPS(EXCETO INTRA-ORÇAM)(VII)";
$despesa[45]['txt']  = "DESPESAS PREVIDENCIARIAS-RPPS(INTRA-ORÇAMENTARIA)(VIII)";

$m_despesa[45]['inicial']    = 0;
$m_despesa[45]['atualizada'] = 0;
$m_despesa[45]['bimestre']   = 0;
$m_despesa[45]['exercicio']  = 0; // ate o bimestre
$m_despesa[45]['anterior']   = 0; // ate o bimestre exercicio anterior
$m_despesa[45]['rpnp_exe']   = 0; // RP Nao Processado Exercicio
$m_despesa[45]['rpnp_ant']   = 0; // RP Nao Processado Exercicio Anterior


// Exercicio Atual
$result_despesa = db_dotacaosaldo(8, 2, 3, true, $db_filtro, $anousu, $dt_ini, $dt_fin);
for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
  db_fieldsmemory($result_despesa, $i);
  if(substr($o58_elemento,3,2) == '91'){

    $m_despesa[45]['inicial']    += $dot_ini; 
	  $m_despesa[45]['atualizada'] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
	  $m_despesa[45]['bimestre']   += $liquidado;  
	  $m_despesa[45]['exercicio']  += $liquidado_acumulado;  	
    $m_despesa[45]['rpnp_exe']   += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  	continue;
  }   	
}  

// RESERVA DO RPPS
for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
  db_fieldsmemory($result_despesa, $i);

  $nivel        = $m_despesa[30]['nivel'];
  $estrutural   = $o58_elemento.'00';
  $estrutural   = substr($estrutural,0,$nivel);
  $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	
  if (in_array($v_estrutural, $m_despesa[30]['estrut'])){

    $m_despesa[30]['inicial']    += $dot_ini; 
    $m_despesa[30]['atualizada'] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
    $m_despesa[30]['bimestre']   += $liquidado;  
    $m_despesa[30]['exercicio']  += $liquidado_acumulado;
    $m_despesa[30]['rpnp_exe']   += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
}

for ($linha = 20; $linha <= 29; $linha++){	
   $v_funcao = "";
   $sp       = "";
   foreach($m_despesa[$linha]["funcao"] as $registro){
      $v_funcao .= $sp.$registro;
      $sp        = ",";
   }

   if (trim($v_funcao) != ""){
     $v_funcao = " and o58_funcao in (".$v_funcao.") ";
   }

   $result_desp_funcao = db_dotacaosaldo(8,2, 3, true, $db_filtro.$v_funcao, $anousu, $dt_ini, $dt_fin);
   for ($i = 0; $i < pg_numrows($result_desp_funcao); $i ++) {
      db_fieldsmemory($result_desp_funcao, $i);
      
      $nivel        = $m_despesa[$linha]['nivel'];
      $estrutural   = $o58_elemento.'00';
      $estrutural   = substr($estrutural,0,$nivel);
      $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

      if (substr($o58_elemento,3,2) == "91"){
        continue;
      }

      if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){

  	    $m_despesa[$linha]['inicial']    += $dot_ini; 
 	      $m_despesa[$linha]['atualizada'] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
	      $m_despesa[$linha]['bimestre']   += $liquidado;  
 	      $m_despesa[$linha]['exercicio']  += $liquidado_acumulado;
        $m_despesa[$linha]['rpnp_exe']   += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      }
   }
}

/*
echo "<xmp>";
print_r($m_despesa);
echo "</xmp>";
exit;
*/

// Exercicio Anterior
$result_despesa_ant = db_dotacaosaldo(8,2, 3, true, $db_filtro, $anousu_ant, $dt_ini_ant, $dt_fin_ant);
for ($i = 0; $i < pg_numrows($result_despesa_ant); $i ++) {
  db_fieldsmemory($result_despesa_ant, $i);   	
  if(substr($o58_elemento,3,2) == '91'){
    $m_despesa[45]['anterior'] += $liquidado_acumulado;
    $m_despesa[45]['rpnp_ant'] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  	continue;
  }   
}

// RESERVA DO RPPS
for ($i = 0; $i < pg_numrows($result_despesa_ant); $i ++) {
  db_fieldsmemory($result_despesa_ant, $i);   	

  $nivel        = $m_despesa[30]['nivel'];
  $estrutural   = $o58_elemento.'00';
  $estrutural   = substr($estrutural,0,$nivel);
  $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	
  if (in_array($v_estrutural, $m_despesa[30]['estrut'])){
	  $m_despesa[30]['anterior'] += $liquidado_acumulado;
    $m_despesa[30]['rpnp_ant'] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
}

for ($linha = 20; $linha <= 29; $linha++){	
   $v_funcao = "";
   $sp       = "";
   foreach($m_despesa[$linha]["funcao"] as $registro){
      $v_funcao .= $sp.$registro;
      $sp        = ",";
   }

   if (trim($v_funcao) != ""){
     $v_funcao = " and o58_funcao in (".$v_funcao.") ";
   }

   $result_desp_funcao_ant = db_dotacaosaldo(8,2, 3, true, $db_filtro.$v_funcao, $anousu_ant, $dt_ini_ant, $dt_fin_ant);
   for ($i = 0; $i < pg_numrows($result_desp_funcao_ant); $i ++) {
      db_fieldsmemory($result_desp_funcao_ant, $i);
      
      $nivel        = $m_despesa[$linha]['nivel'];
      $estrutural   = $o58_elemento.'00';
      $estrutural   = substr($estrutural,0,$nivel);
      $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

      if (substr($o58_elemento,3,2) == "91"){
        continue;
      }

      if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
	      $m_despesa[$linha]['anterior'] += $liquidado_acumulado;
        $m_despesa[$linha]['rpnp_ant'] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      }
   }
}

$pcol = array(1 => 'inicial',
              2 => 'atualizada',
              3 => 'bimestre',
              4 => 'exercicio',
              5 => 'anterior',
              6 => 'rpnp_exe',
              7 => 'rpnp_ant');
$ipcol = count($pcol);
for ($col = 1; $col <= $ipcol; $col++){ 
  
  // Despesas Correntes
  $despesa[2][$pcol[$col]]  = $m_despesa[20][$pcol[$col]];
  // Despesas de Capital     
  $despesa[3][$pcol[$col]]  = $m_despesa[21][$pcol[$col]];
  // total Administracao
  $despesa[1][$pcol[$col]]  = $despesa[2][$pcol[$col]]+$despesa[3][$pcol[$col]];
  // Aposentadorias Civil
  $despesa[6][$pcol[$col]]  = $m_despesa[22][$pcol[$col]];
  // Pensoes Civil
  $despesa[7][$pcol[$col]]  = $m_despesa[23][$pcol[$col]];
  // Outros Beneficios Prev. Civil 
  $despesa[8][$pcol[$col]]  = $m_despesa[24][$pcol[$col]];
  // Pessoal Civil
  $despesa[5][$pcol[$col]]  = $despesa[6][$pcol[$col]]+$despesa[7][$pcol[$col]]+$despesa[8][$pcol[$col]];
  // Reformas 
  $despesa[10][$pcol[$col]] = $m_despesa[25][$pcol[$col]];
  // Pensoes Militar
  $despesa[11][$pcol[$col]] = $m_despesa[26][$pcol[$col]];
  // Outros Beneficios Prev. Militar 
  $despesa[12][$pcol[$col]] = $m_despesa[27][$pcol[$col]];
  // Pessoal Militar
  $despesa[9][$pcol[$col]]  = $despesa[10][$pcol[$col]]+$despesa[11][$pcol[$col]]+$despesa[12][$pcol[$col]];
  // Compensacao Prev. de Aposentadorias
  $despesa[14][$pcol[$col]] = $m_despesa[28][$pcol[$col]];
  // Compensacao Prev. de Pensoes 
  $despesa[15][$pcol[$col]] = $m_despesa[29][$pcol[$col]];
  // Outras Despesas Prev. 
  $despesa[13][$pcol[$col]] = $despesa[14][$pcol[$col]]+$despesa[15][$pcol[$col]];
  // Prev. Social 
  $despesa[4][$pcol[$col]]  = $despesa[5][$pcol[$col]]+$despesa[9][$pcol[$col]]+$despesa[13][$pcol[$col]];
  // Reserva do RPPS 
  $despesa[16][$pcol[$col]] = $m_despesa[30][$pcol[$col]];
  // Despesas exceto intra-orçamentaria
  $despesa[44][$pcol[$col]] = $despesa[4][$pcol[$col]]+$despesa[1][$pcol[$col]];
  // Despesas intra-orçamentaria
  $despesa[45][$pcol[$col]] = $m_despesa[45][$pcol[$col]];
  //total despesas previdenciarias - rpps
  $despesa[0][$pcol[$col]]   = $despesa[1][$pcol[$col]]+$despesa[4][$pcol[$col]];
}

$total_desp_inicial     = 0;
$total_desp_atualizada  = 0;
$total_desp_bimestre    = 0;
$total_desp_exercicio   = 0;
$total_desp_anterior    = 0;

$total_desp_rp_np_bim   = 0;
$total_desp_rp_np_exe   = 0;

$res_prev_inicial       = 0; 
$res_prev_atualizada    = 0;
$res_prev_bimestre      = 0;
$res_prev_exercicio     = 0;
$res_prev_anterior      = 0;

$total_desp_inicial     = $despesa[44]['inicial']    + $despesa[45]['inicial']    + $despesa[16]['inicial']; 
$total_desp_atualizada  = $despesa[44]['atualizada'] + $despesa[45]['atualizada'] + $despesa[16]['atualizada']; 
$total_desp_bimestre    = $despesa[44]['bimestre']   + $despesa[45]['bimestre']   + $despesa[16]['bimestre']; 
$total_desp_exercicio   = $despesa[44]['exercicio']  + $despesa[45]['exercicio']  + $despesa[16]['exercicio']; 
$total_desp_anterior    = $despesa[44]['anterior']   + $despesa[45]['anterior']   + $despesa[16]['anterior']; 
$total_desp_rpnp_exe    = $despesa[44]['rpnp_exe']   + $despesa[45]['rpnp_exe']   + $despesa[16]['rpnp_exe'];
$total_desp_rpnp_ant    = $despesa[44]['rpnp_ant']   + $despesa[45]['rpnp_ant']   + $despesa[16]['rpnp_ant'];

//$res_prev_inicial       = $total_rec_inicial    - $total_desp_inicial; 
//$res_prev_atualizada    = $total_rec_atualizada - $total_desp_atualizada;
//$res_prev_bimestre      = $total_rec_bimestre   - $total_desp_bimestre;
//$res_prev_exercicio     = $total_rec_exercicio  - $total_desp_exercicio;
//$res_prev_anterior      = $total_rec_anterior   - $total_desp_anterior;
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
$db_filtro_disponivel = "c61_instit in (".$instit.") ";

// saldo inicial
// demonstra o saldo do mes anterior ao periodo de referencia

$dt_ini_per = split('-',$dt_fin);
$dt_ini_per = $dt_ini_per[0].'-'.$dt_ini_per[1].'-01';
// Exercicio Atual
$result_disponivel = db_planocontassaldo_matriz($anousu,$dt_ini_per,$dt_fin,false,$db_filtro_disponivel);
for ($i=0; $i < pg_numrows($result_disponivel); $i++){
     db_fieldsmemory($result_disponivel,$i);
     
     for ($linha=31;$linha<=33;$linha++){
    	  if (in_array($estrutural,$m_disponivel[$linha]['estrut'])){
	        $m_disponivel[$linha]['saldo_inicial']       += $saldo_anterior;
	       	$m_disponivel[$linha]['saldo_periodo_atual'] += $saldo_final;
	     }
     }
}
@pg_exec("drop table work_pl");
// Exercicio Anterior
$result_disponivel_ant = db_planocontassaldo_matriz($anousu_ant,$dt_ini_ant,$dt_fin_ant,false,$db_filtro_disponivel);

for ($i=0; $i < pg_numrows($result_disponivel_ant); $i++){
     db_fieldsmemory($result_disponivel_ant,$i);
     for ($linha=31;$linha<=33;$linha++){
    	  if (in_array($estrutural,$m_disponivel[$linha]['estrut'])){
   	    	$m_disponivel[$linha]['saldo_periodo_anterior'] += $saldo_final;
	      }
     }
}

for ($col=1;$col<=3;$col++){ 
     $pcol = array(1=>'saldo_inicial','2'=>'saldo_periodo_atual',3=>'saldo_periodo_anterior');

     $disponivel[1][$pcol[$col]]  = $m_disponivel[31][$pcol[$col]];
     $disponivel[2][$pcol[$col]]  = $m_disponivel[32][$pcol[$col]];
     $disponivel[3][$pcol[$col]]  = $m_disponivel[33][$pcol[$col]];
}

// adiciona a reserva do rpps nas receitas



$total_rec_inicial       = 0;
$total_rec_atualizada    = 0;
$total_rec_bimestre      = 0;
$total_rec_exercicio     = 0;
$total_rec_anterior      = 0;
$total_rec_inicial    = $receita[0]['inicial']    + $receita[24]['inicial']    + $receita[25]['inicial'] + $receita[26]['inicial']    + $receita[27]['inicial'];
$total_rec_atualizada = $receita[0]['atualizada'] + $receita[24]['atualizada'] + $receita[25]['atualizada'] + $receita[26]['atualizada'] + $receita[27]['atualizada'];
$total_rec_bimestre   = $receita[0]['bimestre']   + $receita[24]['bimestre']   + $receita[25]['bimestre']  + $receita[26]['bimestre']   + $receita[27]['bimestre'];
$total_rec_exercicio  = $receita[0]['exercicio']  + $receita[24]['exercicio']  + $receita[25]['exercicio'] + $receita[26]['exercicio']  + $receita[27]['exercicio'];
$total_rec_anterior   = $receita[0]['anterior']   + $receita[24]['anterior']   + $receita[25]['anterior']  + $receita[26]['anterior']   + $receita[27]['anterior'];
//totais das receitas...

for ($linha=48;$linha<=50;$linha++){	

    $m_despesa[$linha]['inicial']    = 0;
    $m_despesa[$linha]['atualizada'] = 0;
    $m_despesa[$linha]['bimestre']   = 0;
    $m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
    $m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
    $m_despesa[$linha]['rpnp_exe']   = 0;  // RP Nao Processados Exercicio
    $m_despesa[$linha]['rpnp_ant']   = 0;  // RP Nao Processados Exercicio Anterior
}


$m_despesa[45]['inicial']    = 0;
$m_despesa[45]['atualizada'] = 0;
$m_despesa[45]['bimestre']   = 0;
$m_despesa[45]['exercicio']  = 0; // ate o bimestre
$m_despesa[45]['anterior']   = 0; // ate o bimestre exercicio anterior
$m_despesa[45]['rpnp_exe']   = 0; // RP Nao Processado Exercicio
$m_despesa[45]['rpnp_ant']   = 0; // RP Nao Processado Exercicio Anterior


for ($linha = 48; $linha <= 49; $linha++){	
   $v_funcao = "";
   $sp       = "";
   foreach($m_despesa[$linha]["funcao"] as $registro){
      $v_funcao .= $sp.$registro;
      $sp        = ",";
   }

   if (trim($v_funcao) != ""){
     $v_funcao = " and o58_funcao in (".$v_funcao.") ";
   }
   
   $result_desp_funcao = db_dotacaosaldo(8,2, 3, true, $db_filtro.$v_funcao, $anousu, $dt_ini, $dt_fin);
   for ($i = 0; $i < pg_numrows($result_desp_funcao); $i ++) {
      db_fieldsmemory($result_desp_funcao, $i);
      
      $nivel        = $m_despesa[$linha]['nivel'];
      $estrutural   = $o58_elemento.'00';
      $estrutural   = substr($estrutural,0,$nivel);
      $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

      if (substr($o58_elemento,3,2) != "91"){
        continue;
      }

      if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){

  	    $m_despesa[$linha]['inicial']    += $dot_ini; 
 	      $m_despesa[$linha]['atualizada'] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
	      $m_despesa[$linha]['bimestre']   += $liquidado;  
 	      $m_despesa[$linha]['exercicio']  += $liquidado_acumulado;
        $m_despesa[$linha]['rpnp_exe']   += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      }
   }
}

$despesa[48]["txt"] = "ADMINISTRAÇÃO";
$despesa[49]["txt"] = " Despesas Correntes";
$despesa[50]["txt"] = " Despesas de Capital";
// Exercicio Anterior
for ($linha = 48; $linha <= 49; $linha++){	
   $v_funcao = "";
   $sp       = "";
   foreach($m_despesa[$linha]["funcao"] as $registro){
      $v_funcao .= $sp.$registro;
      $sp        = ",";
   }

   if (trim($v_funcao) != ""){
     $v_funcao = " and o58_funcao in (".$v_funcao.") ";
   }

   $result_desp_funcao_ant = db_dotacaosaldo(8,2, 3, true, $db_filtro.$v_funcao, $anousu_ant, $dt_ini_ant, $dt_fin_ant);
   for ($i = 0; $i < pg_numrows($result_desp_funcao_ant); $i ++) {
      db_fieldsmemory($result_desp_funcao_ant, $i);
      
      $nivel        = $m_despesa[$linha]['nivel'];
      $estrutural   = $o58_elemento.'00';
      $estrutural   = substr($estrutural,0,$nivel);
      $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

      if (substr($o58_elemento,3,2) != "91"){
        continue;
      }

      if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
        $m_despesa[$linha]['anterior'] += $liquidado_acumulado;
        $m_despesa[$linha]['rpnp_ant'] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      }
   }
}

$pcol = array(1 => 'inicial',
              2 => 'atualizada',
              3 => 'bimestre',
              4 => 'exercicio',
              5 => 'anterior',
              6 => 'rpnp_exe',
              7 => 'rpnp_ant');
$ipcol = count($pcol);

for($col = 1; $col <= $ipcol; $col++){ 

   // Despesas Correntes
   $despesa[49][$pcol[$col]]  = $m_despesa[48][$pcol[$col]];
   // Despesas de Capital     
   $despesa[50][$pcol[$col]]  = $m_despesa[49][$pcol[$col]];
   // Administracao     
   $despesa[48][$pcol[$col]]  = $m_despesa[48][$pcol[$col]] + $m_despesa[49][$pcol[$col]];
}
$despesa[51]["inicial"]    = $despesa[48]['inicial']   ; 
$despesa[51]["atualizada"] = $despesa[48]['atualizada'];  
$despesa[51]["bimestre"]   = $despesa[48]['bimestre']  ; 
$despesa[51]["exercicio"]  = $despesa[48]['exercicio'] ; 
$despesa[51]["anterior"]   = $despesa[48]['anterior']  ; 
$despesa[51]["rpnp_exe"]   = $despesa[48]['rpnp_exe']  ; 
$despesa[51]["rpnp_ant"]   = $despesa[48]['rpnp_ant']  ; 


if (!isset($arqinclude)){ //
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Imprimindo Relatorio
  $perini = $dt_ini;
  $perfin = $dt_fin;
 
  $resultinst = pg_exec("select upper(munic) as munic, codigo,nomeinst,nomeinstabrev from db_config where db21_tipoinstit in (5,6)");
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

  $head1 = "MUNICÍPIO DE {$munic}";
  $head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS PREVIDENCIÁRIAS DO REGIME PRÓPRIO DOS SERVIDORES PÚBLICOS";
  $head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  $txt = strtoupper(db_mes('01'));
  $dt  = split("-",$dt_fin);
  $txt.= " À ".strtoupper(db_mes($dt[1]))."$anousu/BIMESTRE ";;
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
  $pdf->cell(90,$alt,"RREO - Anexo V (LRF, Art. 53, inciso II)",0,0,"L",0);
  $pdf->cell(100,$alt,"R$ 1,00",0,1,"R",0);
  
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
  
  // receita exceto intra
  /*$pdf->cell(90,$alt,$receita[42]['txt'],'R',0,"L",0);
  $pdf->cell(20,$alt,db_formatar($receita[42]['inicial'],'f'),'R',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[42]['atualizada'],'f'),'R',0,"R",0);    
  $pdf->cell(20,$alt,db_formatar($receita[42]['bimestre'],'f'),'R',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[42]['exercicio'],'f'),'R',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[42]['anterior'],'f'),0,0,"R",0);       
  $pdf->Ln();	    
*/
  $sBordas = '';
  for($linha=0;$linha<=27;$linha++){
     $pdf->cell(90,$alt,$receita[$linha]['txt'],"R","R","L",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f'),"R",0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),"R",0,"R",0);    
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f'),"R",0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f'),"R",0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['anterior'],'f'),"",0,"R",0);       
     $pdf->Ln();
     if ($linha == 24){
        $pdf->line(10,$pdf->getY(),200,$pdf->getY());
     }
       // receita exceto intra
     	    
  }

  $pdf->cell(90,$alt,"TOTAL DAS RECEITAS PREVIDENCIÁRIAS(VI)=(I+II+III+IV+V)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_inicial,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_atualizada,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_bimestre,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_exercicio,'f'),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_anterior,'f'),"TB",0,"R",0);
  $pdf->ln();
 
  //$pdf->addpage();

  //
  // D E S P E S A S
  //
  $tam_lin  = ($ultimo_periodo)? 04: 02;
  $tam_col1 = ($ultimo_periodo)? 71: 90;
  $tam_col2 = ($ultimo_periodo)? 17: 20;
  $tam_col3 = ($ultimo_periodo)? 17: 20;
  $tam_col4 = ($ultimo_periodo)? 17: 20;
  $tam_col5 = ($ultimo_periodo)? 17: 20;
  $tam_col6 = ($ultimo_periodo)? 17: 20; // Inscr RP Nao Proc
  $tam_col7 = ($ultimo_periodo)? 17: 20; // Inscr RP Nao Proc Anterior
  $tam_col8 = ($ultimo_periodo)? 17: 20; // Inscr RP Nao Proc Anterior
  $tam_desp = ($ultimo_periodo)?190:190; // tamanho colunas 3+4+5 (e +6+7 qdo $ultimo_periodo==true)

  $pdf->Ln(3);
  if($ultimo_periodo) {
    $pdf->cell($tam_col1, ($alt*$tam_lin), "DESPESAS PREVIDENCIÁRIAS",    "TBR", 0, "C", 0); //col1
    $pdf->cell($tam_col2, $alt,     "",                            "TR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "",                            "TR",  0, "C", 0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6+$tam_col7+$tam_col8;
    $pdf->cell($tam,      $alt,     "DESPESAS EXECUTADAS",         "TB",  0, "C", 0); //col4+col5+col6
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "DOTAÇÃO",                     "R",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "DOTAÇÃO",                     "R",  0, "C", 0); //col3

    $pdf->cell($tam_col4+$tam_col5+$tam_col7, $alt, "Em ".$anousu,     "RB", 0, "C", 0);
    $pdf->cell($tam_col6+$tam_col8,           $alt, "Em ".($anousu-1), "B", 0, "C", 0);
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "INICIAL",                     "R",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "ATUALIZADA",                  "R",  0, "C", 0); //col3

    $posX = $pdf->getX();
    $pdf->cell($tam_col4+$tam_col5, $alt,     "LIQUIDADAS",         "RB",  0, "C", 0);
    $posY = $pdf->getY()+$alt;

    $pdf->cell($tam_col7,           ($alt*2), "Inscritas RP NP", "RB", 0, "C", 0);

    $pdf->cell($tam_col6,           $alt, "LIQUIDADAS",          "R",  0, "C", 0);
    $pdf->cell($tam_col8,           ($alt*2), "Inscritas RP NP", "B", 0, "C", 0);
    $pdf->ln();

    $pdf->setY($posY);
    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "",                            "BR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "",                            "BR",  0, "C", 0); //col3

    $pdf->cell($tam_col4, $alt,     "No Bimestre",    "BR", 0, "C", 0); //col4
    $pdf->cell($tam_col5, $alt,     "Até o Bimestre", "BR", 0, "C", 0); //col5
    
    $pdf->setX($pdf->getX()+$tam_col7);
    $pdf->cell($tam_col6, $alt,     "Até o Bimestre", "BR",  0, "C", 0); //col6
  } else {
    $pdf->cell($tam_col1, ($alt*$tam_lin), "DESPESAS PREVIDENCIÁRIAS",    "TBR", 0, "C", 0); //col1
    $pdf->cell($tam_col2, $alt,     "DOTAÇÃO",                     "TR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "DOTAÇÃO",                     "TR",  0, "C", 0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6;
    $pdf->cell($tam,      $alt,     "DESPESAS LIQUIDADAS",         "TB",  1, "C", 0); //col4+col5+col6
    $pdf->setX(100); 
    $pdf->cell($tam_col2, $alt,     "INICIAL",                     "BR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "ATUALIZADA",                  "BR",  0, "C", 0); //col3
    $pdf->setX(140); 
    $pdf->cell($tam_col4, $alt,     "No Bimestre",                 "TBR", 0, "C", 0); //col4
    $pdf->cell($tam_col5, $alt,     "Até o Bimestre/".$anousu,     "TBR", 0, "C", 0); //col5
    $pdf->cell($tam_col6, $alt,     "Até o Bimestre/".$anousu_ant, "TB",  0, "C", 0); //col6
  }
  $pdf->ln();
 
  // despesas exceto intra-orçamentaria
  $pdf->cell($tam_col1, $alt,             $despesa[44]['txt'],              'R', 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($despesa[44]['inicial'],    'f'), 'R', 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($despesa[44]['atualizada'], 'f'), 'R', 0, "R", 0);    
  $pdf->cell($tam_col4, $alt, db_formatar($despesa[44]['bimestre'],   'f'), 'R', 0, "R", 0);
  $pdf->cell($tam_col5, $alt, db_formatar($despesa[44]['exercicio'],  'f'), 'R', 0, "R", 0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col7, $alt, db_formatar($despesa[44]['rpnp_exe'],   'f'), 'R', 0, "R", 0);       
    $pdf->cell($tam_col6, $alt, db_formatar($despesa[44]['anterior'],   'f'), 'R', 0, "R", 0);       
    $pdf->cell($tam_col8, $alt, db_formatar($despesa[44]['rpnp_ant'],   'f'), '0', 0, "R", 0);       
  } else {
    $pdf->cell($tam_col6, $alt, db_formatar($despesa[44]['anterior'],   'f'), '0', 0, "R", 0);       
  }
  $pdf->Ln();	    


  for ($linha=1; $linha<=16; $linha++) {
    $pdf->cell($tam_col1, $alt,             $despesa[$linha]['txt'],              'R', 0, "L", 0);
    $pdf->cell($tam_col2, $alt, db_formatar($despesa[$linha]['inicial'],    'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col3, $alt, db_formatar($despesa[$linha]['atualizada'], 'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col4, $alt, db_formatar($despesa[$linha]['bimestre'],   'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col5, $alt, db_formatar($despesa[$linha]['exercicio'],  'f'), 'R', 0, "R", 0);
    if ($ultimo_periodo) {
      $pdf->cell($tam_col7, $alt, db_formatar($despesa[$linha]['rpnp_exe'],   'f'), 'R', 0, "R", 0);
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'],   'f'), 'R', 0, "R", 0);
      $pdf->cell($tam_col8, $alt, db_formatar($despesa[$linha]['rpnp_ant'],   'f'), '0', 0, "R", 0);
    } else {
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'],   'f'), '0', 0, "R", 0);
    }
    $pdf->Ln();
    
    if ($linha == 15) {
      // despesas exceto intra-orçamentaria
      $pdf->cell($tam_col1, $alt,             $despesa[45]['txt'],              'R', 0, "L", 0);
      $pdf->cell($tam_col2, $alt, db_formatar($despesa[51]['inicial'],    'f'), 'R', 0, "R", 0);
      $pdf->cell($tam_col3, $alt, db_formatar($despesa[51]['atualizada'], 'f'), 'R', 0, "R", 0);
      $pdf->cell($tam_col4, $alt, db_formatar($despesa[51]['bimestre'],   'f'), 'R', 0, "R", 0);
      $pdf->cell($tam_col5, $alt, db_formatar($despesa[51]['exercicio'],  'f'), 'R', 0, "R", 0);
      if ($ultimo_periodo) {
        $pdf->cell($tam_col7, $alt, db_formatar($despesa[51]['rpnp_exe'],   'f'), 'R', 0, "R", 0);
        $pdf->cell($tam_col6, $alt, db_formatar($despesa[51]['anterior'],   'f'), 'R', 0, "R", 0);
        $pdf->cell($tam_col8, $alt, db_formatar($despesa[51]['rpnp_ant'],   'f'), '0', 0, "R", 0);
      } else {
        $pdf->cell($tam_col6, $alt, db_formatar($despesa[51]['anterior'],   'f'), '0', 0, "R", 0);
      }
      $pdf->Ln();
      $pdf->line(10,$pdf->getY(),200,$pdf->getY());
    }
    
  }
 
  $pdf->cell($tam_col1, $alt, "TOTAL DAS DESPESAS PREVIDENCIÁRIAS(X)=(VII+VIII+IX)", "TBR", 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($total_desp_inicial,    'f'),              "TBR", 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($total_desp_atualizada, 'f'),              "TBR", 0, "R", 0);
  $pdf->cell($tam_col4, $alt, db_formatar($total_desp_bimestre,   'f'),              "TBR", 0, "R", 0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($total_desp_exercicio + $total_desp_rpnp_exe, 'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6+$tam_col8, $alt, db_formatar($total_desp_anterior  + $total_desp_rpnp_ant, 'f'), "TB",  0, "R", 0);

  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($total_desp_exercicio,  'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6, $alt, db_formatar($total_desp_anterior,   'f'), "TB",  0, "R", 0);
  }
  $pdf->ln();
 
  $pdf->cell($tam_col1, $alt, "RESULTADO PREVIDENCIÁRIO(XI)=(VI-X)",                          "TBR", 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($total_rec_inicial    - $total_desp_inicial,    'f'), "TBR", 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($total_rec_atualizada - $total_desp_atualizada, 'f'), "TBR", 0, "R", 0);
  $pdf->cell($tam_col4, $alt, db_formatar($total_rec_bimestre   - $total_desp_bimestre,   'f'), "TBR", 0, "R", 0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($total_rec_exercicio - ($total_desp_exercicio + $total_desp_rpnp_exe), 'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6+$tam_col8, $alt, db_formatar($total_rec_anterior  - ($total_desp_anterior  + $total_desp_rpnp_ant), 'f'), "TB",  0, "R", 0);

  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($total_rec_exercicio - $total_desp_exercicio, 'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6, $alt, db_formatar($total_rec_anterior  - $total_desp_anterior,  'f'), "TB",  0, "R", 0);
  } 
  $pdf->ln();
 
  $pdf->cell(190,$alt,"Continua(2/{nb})",'TB',1,"R",0);
  $pdf->addpage();
  $pdf->cell(190,$alt,"Continuação",'TB',1,"R",0);

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
    $pdf->cell(40,$alt,db_formatar($disponivel[$linha]['saldo_inicial'],'f'),"R",0,"R",0);
    $pdf->cell(30,$alt,db_formatar($disponivel[$linha]['saldo_periodo_atual'],'f'),"R",0,"R",0);
    $pdf->cell(30,$alt,db_formatar($disponivel[$linha]['saldo_periodo_anterior'],'f'),0,0,"R",0);    
    $pdf->Ln();	    
  }
  $pdf->cell(190,0,"","T");
  $pdf->ln(3);
  $pdf->setfont('arial','',6);
  $pdf->cell(90,($alt*2),"RECEITAS INTRA-ORÇAMENTÁRIAS - RPPS",'TBR',0,"C",0);
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
  
  for($linha=28;$linha <=46;$linha++){
     $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),'R',0,"R",0);    
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f'),'R',0,"R",0);
     $pdf->cell(20,$alt,db_formatar($receita[$linha]['anterior'],'f'),0,0,"R",0);       
     $pdf->Ln();
  }
 
  $pdf->cell(90,$alt,"TOTAL DAS RECEITAS PREVIDENCIÁRIAS INTRA-ORÇAMENTARIAS","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($receita[48]['inicial'],'f'),'TBR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[48]['atualizada'],'f'),'TBR',0,"R",0);    
  $pdf->cell(20,$alt,db_formatar($receita[48]['bimestre'],'f'),'TBR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[48]['exercicio'],'f'),'TBR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[48]['anterior'],'f'),'TB',0,"R",0);       
  $pdf->ln();
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Despesas

  $pdf->Ln(3);
  //$pdf->cell(90,($alt*2),"DESPESAS PREVIDENCIÁRIAS INTRA-ORÇAMENTARIAS - RPPS",'TBR',0,"C",0);
  //$pdf->cell(20,$alt,"DOTAÇÃO","TR",0,"C",0);
  //$pdf->cell(20,$alt,"DOTAÇÃO","TR",0,"C",0);
  //$pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);  //br
  //$pdf->setX(100); 
  //$pdf->cell(20,$alt,"INICIAL","BR",0,"C",0);
  //$pdf->cell(20,$alt,"ATUALIZADA","BR",0,"C",0);
  //$pdf->setX(140); 
  //$pdf->cell(20,$alt,"No Bimestre","TBR",0,"C",0);
  //$pdf->cell(20,$alt,"Até o Bimestre/".$anousu,"TBR",0,"C",0);
  //$pdf->cell(20,$alt,"Até o Bimestre/".$anousu_ant,'TB',0,"C",0);
  //$pdf->ln();

  if($ultimo_periodo) {
    $pdf->cell($tam_col1, ($alt*$tam_lin), "DESPESAS PREVIDENCIÁRIAS INTRA-ORÇAMENTARIAS - RPPS",    "TBR", 0, "C", 0); //col1
    $pdf->cell($tam_col2, $alt,     "",                            "TR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "",                            "TR",  0, "C", 0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6+$tam_col7+$tam_col8;
    $pdf->cell($tam,      $alt,     "DESPESAS EXECUTADAS",         "TB",  0, "C", 0); //col4+col5+col6
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "DOTAÇÃO",                     "R",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "DOTAÇÃO",                     "R",  0, "C", 0); //col3

    $pdf->cell($tam_col4+$tam_col5+$tam_col7, $alt, "Em ".$anousu,     "RB", 0, "C", 0);
    $pdf->cell($tam_col6+$tam_col8,           $alt, "Em ".($anousu-1), "B", 0, "C", 0);
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "INICIAL",                     "R",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "ATUALIZADA",                  "R",  0, "C", 0); //col3

    $posX = $pdf->getX();
    $pdf->cell($tam_col4+$tam_col5, $alt,     "LIQUIDADAS",         "RB",  0, "C", 0);
    $posY = $pdf->getY()+$alt;

    $pdf->cell($tam_col7,           ($alt*2), "Inscritas RP NP", "RB", 0, "C", 0);

    $pdf->cell($tam_col6,           $alt, "LIQUIDADAS",          "R",  0, "C", 0);
    $pdf->cell($tam_col8,           ($alt*2), "Inscritas RP NP", "B", 0, "C", 0);
    $pdf->ln();

    $pdf->setY($posY);
    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "",                            "BR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "",                            "BR",  0, "C", 0); //col3

    $pdf->cell($tam_col4, $alt,     "No Bimestre",    "BR", 0, "C", 0); //col4
    $pdf->cell($tam_col5, $alt,     "Até o Bimestre", "BR", 0, "C", 0); //col5
    
    $pdf->setX($pdf->getX()+$tam_col7);
    $pdf->cell($tam_col6, $alt,     "Até o Bimestre", "BR",  0, "C", 0); //col6
  } else {
    $pdf->cell($tam_col1, ($alt*$tam_lin), "DESPESAS PREVIDENCIÁRIAS",    "TBR", 0, "C", 0); //col1
    $pdf->cell($tam_col2, $alt,     "DOTAÇÃO",                     "TR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "DOTAÇÃO",                     "TR",  0, "C", 0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6;
    $pdf->cell($tam,      $alt,     "DESPESAS LIQUIDADAS",         "TB",  1, "C", 0); //col4+col5+col6
    $pdf->setX(100); 
    $pdf->cell($tam_col2, $alt,     "INICIAL",                     "BR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "ATUALIZADA",                  "BR",  0, "C", 0); //col3
    $pdf->setX(140); 
    $pdf->cell($tam_col4, $alt,     "No Bimestre",                 "TBR", 0, "C", 0); //col4
    $pdf->cell($tam_col5, $alt,     "Até o Bimestre/".$anousu,     "TBR", 0, "C", 0); //col5
    $pdf->cell($tam_col6, $alt,     "Até o Bimestre/".$anousu_ant, "TB",  0, "C", 0); //col6
  }
  $pdf->ln();



  for($linha=48;$linha<=50;$linha++){
    $pdf->cell($tam_col1, $alt,             $despesa[$linha]['txt'],              'R', 0, "L", 0);
    $pdf->cell($tam_col2, $alt, db_formatar($despesa[$linha]['inicial'],    'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col3, $alt, db_formatar($despesa[$linha]['atualizada'], 'f'), 'R', 0, "R", 0);    
    $pdf->cell($tam_col4, $alt, db_formatar($despesa[$linha]['bimestre'],   'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col5, $alt, db_formatar($despesa[$linha]['exercicio'],  'f'), 'R', 0, "R", 0);
    if($ultimo_periodo) {
      $pdf->cell($tam_col7, $alt, db_formatar($despesa[$linha]['rpnp_exe'], 'f'), 'R', 0, "R", 0);       
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'], 'f'), 'R', 0, "R", 0);       
      $pdf->cell($tam_col8, $alt, db_formatar($despesa[$linha]['rpnp_ant'], 'f'), '0', 0, "R", 0);       
    } else {
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'], 'f'), '0', 0, "R", 0);       
    } 
    $pdf->Ln();	    
  }

    
  $pdf->cell($tam_col1, $alt, "TOTAL DAS DESPESAS PREVIDENCIÁRIAS INTRA-ORÇAMENTARIAS", "TBR", 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($despesa[51]['inicial'],    'f'),             "TBR", 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($despesa[51]['atualizada'], 'f'),             "TBR", 0, "R", 0);
  $pdf->cell($tam_col4, $alt, db_formatar($despesa[51]['bimestre'],   'f'),             "TBR", 0, "R", 0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($despesa[51]['exercicio'] + $despesa[51]['rpnp_exe'],  'f'),             "TBR", 0, "R", 0);
    $pdf->cell($tam_col6+$tam_col8, $alt, db_formatar($despesa[51]['anterior']  + $despesa[51]['rpnp_ant'],   'f'),             "TB",  0, "R", 0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($despesa[51]['exercicio'],  'f'),             "TBR", 0, "R", 0);
    $pdf->cell($tam_col6, $alt, db_formatar($despesa[51]['anterior'],   'f'),             "TB",  0, "R", 0);
  }
  $pdf->ln();
  $pdf->cell($tam_desp, $alt, "({nb}/{nb})", 'TB', 1, "R", 0);
  //assinatura
  notasExplicativas(&$pdf,23,$periodo,180);
  $pdf->ln(10);
  $pdf->setfont('arial','',6);
 
  assinaturas(&$pdf,&$classinatura,'LRF');
 
  $pdf->Output();
 
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

?>