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


if (!isset($arqinclude)) {
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_orcparamrel_classe.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_conrelinfo_classe.php");
  include("classes/db_db_config_classe.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel = new cl_orcparamrel;
  $clconrelinfo = new cl_conrelinfo;
  $cldb_config  = new cl_db_config;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

// -------------------------------------
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

$tipo_emissao='periodo';

if (!isset($arqinclude)) {
  
	$anousu  = db_getsession("DB_anousu");

  $dt = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
  $dt_ini= $anousu.'-01-01'; // data inicial do período
  $dt_fin= $dt[1]; // data final do período
  // $texto = $dt['texto'];
  // $txtper = $dt['periodo'];
  $anousu_ant  = db_getsession("DB_anousu")-1;
  
  $dt = data_periodo($anousu_ant,$periodo); // no dbforms/db_funcoes.php
  $dt_ini_ant= $dt[0]; // data inicial do período
  $dt_fin_ant= $dt[1]; // data final do período
  
  $bimestre = substr($periodo,0,1); // bimestre do exercicio atual
  
}

// caso tenha datas manuais selecionada , sobrescrevo as variaveis acima
if ($dtini!=''&&$dtfin!='') {
  
  $bimestre_anterior = 13;
  $tipo_emissao='datas';
  
  $dt_ini = $dtini;
  $dt_fin = $dtfin;
  
  $dt     = split("-",$dt_ini);
  $mes    = $dt[1];
  
  // 1 Bimestre
  if ($mes >= 1 && $mes <= 2){
    $bimestre = 1;      
  }elseif ($mes >= 3  && $mes <= 4 ){  // 2 Bimestre 
    $bimestre = 2;
  }elseif ($mes >= 5  && $mes <= 6 ){  // 3 Bimestre
    $bimestre = 3;
  }elseif ($mes >= 7  && $mes <= 8 ){  // 4 Bimestre
    $bimestre = 4;
  }elseif ($mes >= 9  && $mes <= 10){  // 5 Bimestre
    $bimestre = 5;
  }elseif ($mes >= 11 && $mes <= 12){  // 6 Bimestre
    $bimestre = 6;      
  }
  
  $dt = split('-',$dt_fin);
  $dt_ini_ant = $anousu_ant.'-'.$dt[1].'-'.$dt[2];
  $dt = split('-',$dt_fin);
  $dt_fin_ant = $anousu_ant.'-'.$dt[1].'-'.$dt[2];
  
}  

// calculo do mes inicial que sera considerado no exercicio anterior
$bimestre_anterior = ($bimestre*2)+1;

if ($bimestre_anterior==13){  
  // aqui é pq esta emitindo o relatorio do sexto bimestre então é só o exercicio atual a ser mostrado
  // echo "<Br> bim, ante = 13";exit;  
}

if ($tipo_emissao=="datas"){
  $bimestre_anterior = $bimestre*2;
}

$head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTARIA";
$head3 = "DEMONSTRATIVO DA RECEITA CORRENTE LIQUIDA";
$head4 = "ORÇAMENTO FISCAL E DA SEGURIDADE SOCIAL ";

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head7 = "INSTITUIÇÕES : ".$descr_inst;

if ($tipo_emissao!='datas'){
  $dtd1 = split('-',$dt_ini);
  $dtd2 = split('-',$dt_fin);
  $dt1 = "$dtd1[2]/$dtd1[1]/$dtd1[0]";
  $dt2 = "$dtd2[2]/$dtd2[1]/$dtd2[0]";
  $txt = strtoupper(db_mes('01'));
  $dt  = split("-",$dt_fin);
  $txt.= " À ".strtoupper(db_mes($dt[1]));
  $txt .= "  / ".$anousu;
  $head5 = "$txt";
}else {
  $dtd1 = split('-',$dt_ini);
  $dtd2 = split('-',$dt_fin);
  $dt1 = "$dtd1[2]/$dtd1[1]/$dtd1[0]";
  $dt2 = "$dtd2[2]/$dtd2[1]/$dtd2[0]";
  $head5 = "EMISSÃO POR DATAS";
  $head6 = 'Periodo:  '.$dt1.' à '.$dt2;  
} 

// busca os estruturais que o usuário selecionou nos parametros
$param[1] = $orcparamrel->sql_parametro('5','1', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[2] = $orcparamrel->sql_parametro('5','2', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[3] = $orcparamrel->sql_parametro('5','3', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[4] = $orcparamrel->sql_parametro('5','4', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[5] = $orcparamrel->sql_parametro('5','5', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[6] = $orcparamrel->sql_parametro('5','6', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[7] = $orcparamrel->sql_parametro('5','7', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[8] = $orcparamrel->sql_parametro('5','8', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[9] = $orcparamrel->sql_parametro('5','9', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[10] = $orcparamrel->sql_parametro('5','10', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[11] = $orcparamrel->sql_parametro('5','11', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[12] = $orcparamrel->sql_parametro('5','12', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[13] = $orcparamrel->sql_parametro('5','13', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[14] = $orcparamrel->sql_parametro('5','14', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[15] = $orcparamrel->sql_parametro('5','15', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);

$param[16] = $orcparamrel->sql_parametro('5','16', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[17] = $orcparamrel->sql_parametro('5','17', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);
$param[18] = $orcparamrel->sql_parametro('5','18', 'f', str_replace('-', ', ', $db_selinstit), $anousu - 1,false);

// linha,coluna
$rec[1][0]  = "    IPTU";
$rec[2][0]  = "    ISS";
$rec[3][0]  = "    ITBI";
$rec[4][0]  = "    Outras Receitas Tributárias";
$rec[5][0]  = "  Receita de Contribuições";
$rec[6][0]  = "  Receita Patrimonial";
$rec[7][0]  = "  Receita Agropecuária";
$rec[8][0]  = "  Receita Industrial";
$rec[9][0]  = "  Receita de Serviços";
$rec[10][0] = "    Cota-Parte do FPM";
$rec[11][0] = "    Cota-Parte do ICMS";
$rec[12][0] = "    Cota-Parte do IPVA";
$rec[13][0] = "    Transferências do FUNDEF";
$rec[14][0] = "    Outras Transferências Correntes";
$rec[15][0] = "  Outras Receitas Correntes";

// - Deduções
$rec[16][0] = "      Servidor";
$rec[17][0] = "    Compensação Financ. entre Regimes Previd.";
$rec[18][0] = "    Deduções da Receitas para Formação do FUNDEF";

// gera matris com todos os estruturais selecionados nas configurações do relatorio
$m_todos = $orcparamrel->sql_parametro('5',"","f",str_replace("-",",",$db_selinstit),$anousu,false);
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

// monta receita do bimestre do exercicio anterior, quando for o caso
$dt1 = ($anousu - 1).'-01-01'; 
$dt2 = ($anousu - 1).'-12-31';

$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
}

$clreceita_saldo_mes = new cl_receita_saldo_mes;
$clreceita_saldo_mes->anousu = ($anousu - 1);
$clreceita_saldo_mes->dtini = $dt1;
$clreceita_saldo_mes->dtfim = $dt2;
$clreceita_saldo_mes->usa_datas = 'sim';
//$clreceita_saldo_mes->instit = "".str_replace('-',', ',$db_selinstit)." ";
$clreceita_saldo_mes->instit = $todasinstit;
$clreceita_saldo_mes->sql_record();
//db_criatabela($clreceita_saldo_mes->result);exit;
@pg_exec("drop table work_plano");

for ($p=1;$p<=18;$p++) {
  // 18 é a quantidade de parametros (linhas existentes nos parametros)
  for ($i=0;$i<$clreceita_saldo_mes->numrows;$i++){
    db_fieldsmemory($clreceita_saldo_mes->result,$i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural,$param[$p])){    
      
      if ($p == 18 ) {
			  
        $janeiro   *= -1;
        $fevereiro *= -1;
        $marco     *= -1;
        $abril     *= -1;
        $maio      *= -1;
        $junho     *= -1;
        $julho     *= -1;
        $agosto    *= -1;
        $setembro  *= -1;
        $outubro   *= -1;
        $novembro  *= -1;
        $dezembro  *= -1;
        
      }    
      if (!isset($rec[$p][1]))  $rec[$p][1]= $janeiro;    else $rec[$p][1] += $janeiro;
      if (!isset($rec[$p][2]))  $rec[$p][2]= $fevereiro;  else $rec[$p][2] += $fevereiro;
      if (!isset($rec[$p][3]))  $rec[$p][3]= $marco;      else $rec[$p][3] += $marco;
      if (!isset($rec[$p][4]))  $rec[$p][4]= $abril;      else $rec[$p][4] += $abril;
      if (!isset($rec[$p][5]))  $rec[$p][5]= $maio;       else $rec[$p][5] += $maio;
      if (!isset($rec[$p][6]))  $rec[$p][6]= $junho;      else $rec[$p][6] += $junho;
      if (!isset($rec[$p][7]))  $rec[$p][7]= $julho;      else $rec[$p][7] += $julho;
      if (!isset($rec[$p][8]))  $rec[$p][8]= $agosto;     else $rec[$p][8] += $agosto;
      if (!isset($rec[$p][9]))  $rec[$p][9]= $setembro;   else $rec[$p][9] += $setembro;
      if (!isset($rec[$p][10])) $rec[$p][10]= $outubro;   else $rec[$p][10]+= $outubro;
      if (!isset($rec[$p][11])) $rec[$p][11]= $novembro;  else $rec[$p][11]+= $novembro;
      if (!isset($rec[$p][12])) $rec[$p][12]= $dezembro;  else $rec[$p][12]+= $dezembro;
      
      // matriz de totalizador do exercicio anterior
      if ($p <=15){
        // Trec da linha 0 (zero) contem o total da arrecadação da receita corrente
        if (!isset($Trec[0][1]))  $Trec[0][1]= $janeiro;    else $Trec[0][1] += $janeiro;
        if (!isset($Trec[0][2]))  $Trec[0][2]= $fevereiro;  else $Trec[0][2] += $fevereiro;
        if (!isset($Trec[0][3]))  $Trec[0][3]= $marco;      else $Trec[0][3] += $marco;
        if (!isset($Trec[0][4]))  $Trec[0][4]= $abril;      else $Trec[0][4] += $abril;
        if (!isset($Trec[0][5]))  $Trec[0][5]= $maio;       else $Trec[0][5] += $maio;
        if (!isset($Trec[0][6]))  $Trec[0][6]= $junho;      else $Trec[0][6] += $junho;
        if (!isset($Trec[0][7]))  $Trec[0][7]= $julho;      else $Trec[0][7] += $julho;
        if (!isset($Trec[0][8]))  $Trec[0][8]= $agosto;     else $Trec[0][8] += $agosto;
        if (!isset($Trec[0][9]))  $Trec[0][9]= $setembro;   else $Trec[0][9] += $setembro;
        if (!isset($Trec[0][10])) $Trec[0][10]= $outubro;   else $Trec[0][10]+= $outubro;
        if (!isset($Trec[0][11])) $Trec[0][11]= $novembro;  else $Trec[0][11]+= $novembro;
        if (!isset($Trec[0][12])) $Trec[0][12]= $dezembro;  else $Trec[0][12]+= $dezembro;	  
      } else {
        // Trec da linha 1 contem o total da dedução da receita corrente
        if (db_conplano_grupo($anousu,substr($estrutural,0,3)."%",9001) == true){  // 497 e 917 
          if (!isset($Trec[1][1]))  $Trec[1][1]  = abs($janeiro);    else {$Trec[1][1]  += ($janeiro);   } 
          if (!isset($Trec[1][2]))  $Trec[1][2]  = abs($fevereiro);  else {$Trec[1][2]  += ($fevereiro); }
          if (!isset($Trec[1][3]))  $Trec[1][3]  = abs($marco);      else {$Trec[1][3]  += ($marco);  } 
          if (!isset($Trec[1][4]))  $Trec[1][4]  = abs($abril);      else {$Trec[1][4]  += ($abril); }
          if (!isset($Trec[1][5]))  $Trec[1][5]  = abs($maio);       else {$Trec[1][5]  += ($maio);  }
          if (!isset($Trec[1][6]))  $Trec[1][6]  = abs($junho);      else {$Trec[1][6]  += ($junho); }
          if (!isset($Trec[1][7]))  $Trec[1][7]  = abs($julho);      else {$Trec[1][7]  += ($julho); } 
          if (!isset($Trec[1][8]))  $Trec[1][8]  = abs($agosto);     else {$Trec[1][8]  += ($agosto);    } 
          if (!isset($Trec[1][9]))  $Trec[1][9]  = abs($setembro);   else {$Trec[1][9]  += ($setembro); } 
          if (!isset($Trec[1][10])) $Trec[1][10] = abs($outubro);    else {$Trec[1][10] += ($outubro);   }
          if (!isset($Trec[1][11])) $Trec[1][11] = abs($novembro);   else {$Trec[1][11] += ($novembro);  }
          if (!isset($Trec[1][12])) $Trec[1][12] = abs($dezembro);   else {$Trec[1][12] += ($dezembro);  } 
        }else{
          if (!isset($Trec[1][1]))  $Trec[1][1] = ($janeiro);    else $Trec[1][1] += ($janeiro);
          if (!isset($Trec[1][2]))  $Trec[1][2] = ($fevereiro);  else $Trec[1][2] += ($fevereiro);
          if (!isset($Trec[1][3]))  $Trec[1][3] = ($marco);      else $Trec[1][3] += ($marco);
          if (!isset($Trec[1][4]))  $Trec[1][4] = ($abril);      else $Trec[1][4] += ($abril);
          if (!isset($Trec[1][5]))  $Trec[1][5] = ($maio);       else $Trec[1][5] += ($maio);
          if (!isset($Trec[1][6]))  $Trec[1][6] = ($junho);      else $Trec[1][6] += ($junho);
          if (!isset($Trec[1][7]))  $Trec[1][7] = ($julho);      else $Trec[1][7] += ($julho);
          if (!isset($Trec[1][8]))  $Trec[1][8] = ($agosto);     else $Trec[1][8] += ($agosto);
          if (!isset($Trec[1][9]))  $Trec[1][9] = ($setembro);   else $Trec[1][9] += ($setembro);
          if (!isset($Trec[1][10])) $Trec[1][10]= ($outubro);    else $Trec[1][10] += ($outubro);
          if (!isset($Trec[1][11])) $Trec[1][11]= ($novembro);   else $Trec[1][11] += ($novembro);
          if (!isset($Trec[1][12])) $Trec[1][12]= ($dezembro);  else $Trec[1][12] += ($dezembro);
        }	  
      } 	      
    }     
  } 
} 

// --------------------------------------------------------
// monta receita do bimestre escolhido
$clreceita_saldo_mes = new cl_receita_saldo_mes;
$clreceita_saldo_mes->anousu = $anousu ;
$clreceita_saldo_mes->dtini = $dt_ini;
$clreceita_saldo_mes->dtfim = $dt_fin;
$clreceita_saldo_mes->usa_datas = 'sim';
//$clreceita_saldo_mes->instit = "".str_replace('-',', ',$db_selinstit)." ";
$clreceita_saldo_mes->instit = $todasinstit;
$clreceita_saldo_mes->sql_record();
//echo $clreceita_saldo_mes->sql; exit;
@pg_exec("drop table work_plano");

//echo $dt_ini." => ".$dt_fin."<br>"; exit;

//db_criatabela($clreceita_saldo_mes->result); exit;

// busca os estruturais que o usuário selecionou nos parametros
$param[1] = $orcparamrel->sql_parametro('5','1', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[2] = $orcparamrel->sql_parametro('5','2', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[3] = $orcparamrel->sql_parametro('5','3', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[4] = $orcparamrel->sql_parametro('5','4', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[5] = $orcparamrel->sql_parametro('5','5', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[6] = $orcparamrel->sql_parametro('5','6', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[7] = $orcparamrel->sql_parametro('5','7', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[8] = $orcparamrel->sql_parametro('5','8', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[9] = $orcparamrel->sql_parametro('5','9', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[10] = $orcparamrel->sql_parametro('5','10', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[11] = $orcparamrel->sql_parametro('5','11', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[12] = $orcparamrel->sql_parametro('5','12', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[13] = $orcparamrel->sql_parametro('5','13', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[14] = $orcparamrel->sql_parametro('5','14', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[15] = $orcparamrel->sql_parametro('5','15', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);

$param[16] = $orcparamrel->sql_parametro('5','16', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[17] = $orcparamrel->sql_parametro('5','17', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);
$param[18] = $orcparamrel->sql_parametro('5','18', 'f', str_replace('-', ', ', $db_selinstit), $anousu,false);

//var_dump($param);exit;

for ($p=1;$p<=18;$p++) {
  // 18 é a quantidade de parametros ou linhas existentes nos parametros

  for ($i=0;$i<$clreceita_saldo_mes->numrows;$i++){
    db_fieldsmemory($clreceita_saldo_mes->result,$i);
		
    $estrutural = $o57_fonte;
		
    if (in_array($estrutural,$param[$p])) {
			
      if ($p == 18 ) {
			  
        $janeiro   *= -1;
        $fevereiro *= -1;
        $marco     *= -1;
        $abril     *= -1;
        $maio      *= -1;
        $junho     *= -1;
        $julho     *= -1;
        $agosto    *= -1;
        $setembro  *= -1;
        $outubro   *= -1;
        $novembro  *= -1;
        $dezembro  *= -1;
        $o70_valor *= -1;
        
      }  
      if (!isset($recB[$p][1]))  $recB[$p][1]= $janeiro;    else $recB[$p][1] += $janeiro;
      if (!isset($recB[$p][2]))  $recB[$p][2]= $fevereiro;  else $recB[$p][2] += $fevereiro;
      if (!isset($recB[$p][3]))  $recB[$p][3]= $marco;      else $recB[$p][3] += $marco;
      if (!isset($recB[$p][4]))  $recB[$p][4]= $abril;      else $recB[$p][4] += $abril;
      if (!isset($recB[$p][5]))  $recB[$p][5]= $maio;       else $recB[$p][5] += $maio;
      if (!isset($recB[$p][6]))  $recB[$p][6]= $junho;      else $recB[$p][6] += $junho;
      if (!isset($recB[$p][7]))  $recB[$p][7]= $julho;      else $recB[$p][7] += $julho;
      if (!isset($recB[$p][8]))  $recB[$p][8]= $agosto;     else $recB[$p][8] += $agosto;
      if (!isset($recB[$p][9]))  $recB[$p][9]= $setembro;   else $recB[$p][9] += $setembro;
      if (!isset($recB[$p][10])) $recB[$p][10]= $outubro;   else $recB[$p][10]+= $outubro;
      if (!isset($recB[$p][11])) $recB[$p][11]= $novembro;  else $recB[$p][11]+= $novembro;
      if (!isset($recB[$p][12])) $recB[$p][12]= $dezembro;  else $recB[$p][12]+= $dezembro;
      // a coluna 13 ira guardar a previsao 
      if (!isset($recB[$p][13])) $recB[$p][13]= $o70_valor;  else $recB[$p][13]+= $o70_valor;
      
      /*
      chamamos de "recB" esta segunda matriz, porque "rec" é foi a primeira matriz criada
      que ira quardar os dados do exercicio-1, e este "recB" ira guardar dados do exercicio atual
      */       
      if ($p <=15) {
        // Trec da linha 0 (zero) contem o total da arrecadação da receita corrente
        if (!isset($TrecB[0][1]))  $TrecB[0][1]= $janeiro;    else $TrecB[0][1] += $janeiro;
        if (!isset($TrecB[0][2]))  $TrecB[0][2]= $fevereiro;  else $TrecB[0][2] += $fevereiro;
        if (!isset($TrecB[0][3]))  $TrecB[0][3]= $marco;      else $TrecB[0][3] += $marco;
        if (!isset($TrecB[0][4]))  $TrecB[0][4]= $abril;      else $TrecB[0][4] += $abril;
        if (!isset($TrecB[0][5]))  $TrecB[0][5]= $maio;       else $TrecB[0][5] += $maio;
        if (!isset($TrecB[0][6]))  $TrecB[0][6]= $junho;      else $TrecB[0][6] += $junho;
        if (!isset($TrecB[0][7]))  $TrecB[0][7]= $julho;      else $TrecB[0][7] += $julho;
        if (!isset($TrecB[0][8]))  $TrecB[0][8]= $agosto;     else $TrecB[0][8] += $agosto;
        if (!isset($TrecB[0][9]))  $TrecB[0][9]= $setembro;   else $TrecB[0][9] += $setembro;
        if (!isset($TrecB[0][10])) $TrecB[0][10]= $outubro;   else $TrecB[0][10]+= $outubro;
        if (!isset($TrecB[0][11])) $TrecB[0][11]= $novembro;  else $TrecB[0][11]+= $novembro;
        if (!isset($TrecB[0][12])) $TrecB[0][12]= $dezembro;  else $TrecB[0][12]+= $dezembro;	  
        if (!isset($TrecB[0][13])) $TrecB[0][13]= $o70_valor; else $TrecB[0][13]+= $o70_valor;
      } else {
        // Trec da linha 1 contem o total da dedução da receita corrente
        if (db_conplano_grupo($anousu,substr($estrutural,0,3)."%",9001) == true){  // 497 e 917 
          if (!isset($TrecB[1][1]))  $TrecB[1][1]= ($janeiro);    else $TrecB[1][1] += ($janeiro);
          if (!isset($TrecB[1][2]))  $TrecB[1][2]= ($fevereiro);  else $TrecB[1][2] += ($fevereiro);
          if (!isset($TrecB[1][3]))  $TrecB[1][3]= ($marco);      else $TrecB[1][3] += ($marco);
          if (!isset($TrecB[1][4]))  $TrecB[1][4]= ($abril);      else $TrecB[1][4] += ($abril);
          if (!isset($TrecB[1][5]))  $TrecB[1][5]= ($maio);       else $TrecB[1][5] += ($maio);
          if (!isset($TrecB[1][6]))  $TrecB[1][6]= ($junho);      else $TrecB[1][6] += ($junho);
          if (!isset($TrecB[1][7]))  $TrecB[1][7]= ($julho);      else $TrecB[1][7] += ($julho);
          if (!isset($TrecB[1][8]))  $TrecB[1][8]= ($agosto);     else $TrecB[1][8] += ($agosto);
          if (!isset($TrecB[1][9]))  $TrecB[1][9]= ($setembro);   else $TrecB[1][9] += ($setembro);
          if (!isset($TrecB[1][10])) $TrecB[1][10]= ($outubro);   else $TrecB[1][10]+= ($outubro);
          if (!isset($TrecB[1][11])) $TrecB[1][11]= ($novembro);  else $TrecB[1][11]+= ($novembro);
          if (!isset($TrecB[1][12])) $TrecB[1][12]= ($dezembro);  else $TrecB[1][12]+= ($dezembro);	  
          if (!isset($TrecB[1][13])) $TrecB[1][13]= ($o70_valor); else $TrecB[1][13]+= ($o70_valor);
        }else{
          if (!isset($TrecB[1][1]))  $TrecB[1][1]= ($janeiro);    else $TrecB[1][1] += ($janeiro);
          if (!isset($TrecB[1][2]))  $TrecB[1][2]= ($fevereiro);  else $TrecB[1][2] += ($fevereiro);
          if (!isset($TrecB[1][3]))  $TrecB[1][3]= ($marco);      else $TrecB[1][3] += ($marco);
          if (!isset($TrecB[1][4]))  $TrecB[1][4]= ($abril);      else $TrecB[1][4] += ($abril);
          if (!isset($TrecB[1][5]))  $TrecB[1][5]= ($maio);       else $TrecB[1][5] += ($maio);
          if (!isset($TrecB[1][6]))  $TrecB[1][6]= ($junho);      else $TrecB[1][6] += ($junho);
          if (!isset($TrecB[1][7]))  $TrecB[1][7]= ($julho);      else $TrecB[1][7] += ($julho);
          if (!isset($TrecB[1][8]))  $TrecB[1][8]= ($agosto);     else $TrecB[1][8] += ($agosto);
          if (!isset($TrecB[1][9]))  $TrecB[1][9]= ($setembro);   else $TrecB[1][9] += ($setembro);
          if (!isset($TrecB[1][10])) $TrecB[1][10]= ($outubro);   else $TrecB[1][10]+= ($outubro);
          if (!isset($TrecB[1][11])) $TrecB[1][11]= ($novembro);  else $TrecB[1][11]+= ($novembro);
          if (!isset($TrecB[1][12])) $TrecB[1][12]= ($dezembro);  else $TrecB[1][12]+= ($dezembro);	  
          if (!isset($TrecB[1][13])) $TrecB[1][13]= ($o70_valor); else $TrecB[1][13]+= ($o70_valor);
        }
      } 
    }     
  } 
} 

// ------------------------------
// somadores avulsos
$tot_rec_trib = array(); //zera matriz
for ($x=0;$x<=13;$x++){
  $tot_rec_trib[0][$x]=0;
}  
for ($x=1;$x<=4;$x++){
  for ($y=$bimestre_anterior;$y<=12;$y++){
    if (isset($rec[$x][$y])){
      $tot_rec_trib[0][$y] += $rec[$x][$y];
    } else
    $tot_rec_trib[0][$y] += 0;
  }  
  // procura valores do exercicio atual
  for ($y=1;$y <=$bimestre*2;$y++){
    if ($tipo_emissao=="datas"){
      if ($y==($bimestre*2)){
        break;
      }
    }
    if (isset($recB[$x][$y])){
      $tot_rec_trib[0][$y] += $recB[$x][$y];
    } else
    $tot_rec_trib[0][$y] += 0;
  }  
  // listamos a previsão
  if (isset($recB[$x][13]))
  $tot_rec_trib[0][13] += $recB[$x][13];	
  else
  $tot_rec_trib[0][13] += 0;
}

// 
$tot_transf = array(); //zera matriz
for ($x=0;$x<=13;$x++){
  $tot_transf[0][$x]=0;
}  
for ($x=10;$x<=14;$x++){
  for ($y=$bimestre_anterior;$y<=12;$y++){
    if (isset($rec[$x][$y])){
      $tot_transf[0][$y] += $rec[$x][$y];
    } else
    $tot_transf[0][$y] += 0;
  }  
  // procura valores do exercicio atual
  for ($y=1;$y <=$bimestre*2;$y++){
    if ($tipo_emissao=="datas"){
      if ($y==($bimestre*2)){
        break;
      }
    }
    if (isset($recB[$x][$y])){
      $tot_transf[0][$y] += $recB[$x][$y];
    } else
    $tot_transf[0][$y] += 0;
  }  
  
  // listamos a previsão
  if (isset($recB[$x][13]))
  $tot_transf[0][13] += $recB[$x][13];	
  else
  $tot_transf[0][13] += 0;
  
}

// uma matriz para facilitar a impresso dos nomes dos meses no relatorio
$mes_dresc[1]		= 'Janeiro';
$mes_dresc[2]		= 'Fevereiro';
$mes_dresc[3]		= 'Março';
$mes_dresc[4]		= 'Abril';
$mes_dresc[5]		= 'Maio';
$mes_dresc[6]		= 'Junho';
$mes_dresc[7]		= 'Julho';
$mes_dresc[8]		= 'Agosto';
$mes_dresc[9]		= 'Setembro';
$mes_dresc[10]	= 'Outubro';
$mes_dresc[11]	= 'Novembro';
$mes_dresc[12]	= 'Dezembro';

if (!isset($arqinclude)){
  
  //-------------------------------------------------------------------------------------------------
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','',6);
  $alt            = 4;
  $pagina         = 1;
  $cl = 16;
  $tp = 'B';
  $ta ='TBRL';
  
  $pdf->addpage("L");
  
  $pdf->cell(60,$alt,"LRF, Art 53, inciso I, Anexo III","b",0,"L",0);
  $pdf->cell(($cl*14),$alt,"R$ Unidades","b",1,"R",0);
  $pdf->cell(60,$alt,"ESPECIFICAÇÃO",'RT',0,"C",0);
  $pdf->cell(($cl*12),$alt,"EVOLUÇÃO DA RECEITA REALIZADA",'RTB',0,"C",0);
  $pdf->cell($cl,$alt,"TOTAL",'RT',0,"C",0);
  $pdf->cell($cl,$alt,"PREVISAO",'T',0,"C",0);
  $pdf->ln();
  $pdf->cell(60,$alt,"",'BR',0,"C",0);
  
  if ($bimestre_anterior!=13){
    /* 
    * lista meses do periodo anterior ( exercicio anterior )
    */
    for ($x=$bimestre_anterior;$x<=12;$x++){
      $pdf->cell($cl,$alt,$mes_dresc[$x],'TBR',0,"C",0);
    }  
  }  
  // meses do exercicio atual
  for ($x=1;$x <=$bimestre*2;$x++){
    if ($tipo_emissao=="datas"){
      if ($x==($bimestre*2)){
        break;
      }
    }
    $pdf->cell($cl,$alt,$mes_dresc[$x],'TBR',0,"C",0);
  }  
  $pdf->cell($cl,$alt,"ULT 12 MESES",'BR',0,"C",0);
  $pdf->cell($cl,$alt,"ATUAL EXERC",'B',0,"C",0);
  $pdf->ln();
  
  $total = 0; // esse total é sempre calculado para cada linha
  
  // imprime as linhas/valores do exercicio anterior  
  for ($x=1;$x<=18;$x++){ // 18 é a qtd de parametros existentes
    
    // ----------------------------------
    if ($x==1){
      // aqui imprime a linha com os totalizadores
      $pdf->setfont('arial','b',6);
      $pdf->cell(60,$alt,"RECEITA CORRENTE(I)",'R',0,"L",0);    
      $total = 0;
      for ($y=$bimestre_anterior;$y<=12;$y++){
        if (isset($Trec[0][$y])){
          $pdf->cell($cl,$alt,db_formatar($Trec[0][$y],'f'),'R',0,"R",0);	 
          $total += $Trec[0][$y];
        }else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }   
      for ($y=1;$y <=$bimestre*2;$y++){
        if ($tipo_emissao=="datas"){
          if ($y==($bimestre*2)){
            break;
          }
        }
        if (isset($TrecB[0][$y])){
          $pdf->cell($cl,$alt,db_formatar($TrecB[0][$y],'f'),'R',0,"R",0);	 
          $total += $TrecB[0][$y];
        }else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  
      $pdf->cell($cl,$alt,db_formatar($total,'f'),'R',0,"R",0);
      if (isset($TrecB[0][13]))
      $pdf->cell($cl,$alt,db_formatar($TrecB[0][13],'f'),0,0,"R",0);	 	   
      else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),0,0,"R",0);       
      $pdf->ln();
      //-------------------------------
      $pdf->setfont('arial','',6);
      $pdf->cell(60,$alt," Receita Tributária",'R',0,"L",0);        
      $total = 0;
      for ($y=$bimestre_anterior;$y<=12;$y++){
        if (isset($tot_rec_trib[0][$y])){
          $pdf->cell($cl,$alt,db_formatar($tot_rec_trib[0][$y],'f'),'R',0,"R",0);	 
          $total += $tot_rec_trib[0][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  
      for ($y=1;$y <=$bimestre*2;$y++){
        if ($tipo_emissao=="datas"){
          if ($y==($bimestre*2)){
            break;
          }
        }
        if (isset($tot_rec_trib[0][$y])){
          $pdf->cell($cl,$alt,db_formatar($tot_rec_trib[0][$y],'f'),'R',0,"R",0);	 
          $total += $tot_rec_trib[0][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  	
      // listamos o total das 12 colunas
      $pdf->cell($cl,$alt,db_formatar($total,'f'),'R',0,"R",0);
      
      // listamos a previsão
      if (isset($tot_rec_trib[0][13]))
      $pdf->cell($cl,$alt,db_formatar($tot_rec_trib[0][13],'f'),0,0,"R",0);	 
      else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),0,0,"R",0);
      $pdf->ln();   
      
    }  
    //-------------------------------
    if ($x==10){
      $pdf->setfont('arial','',6);
      $pdf->cell(60,$alt," Transferências Correntes",'R',0,"L",0);        
      $total = 0;
      for ($y=$bimestre_anterior;$y<=12;$y++){
        if (isset($tot_transf[0][$y])){
          $pdf->cell($cl,$alt,db_formatar($tot_transf[0][$y],'f'),'R',0,"R",0);	 
          $total += $tot_transf[0][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  
      for ($y=1;$y <=$bimestre*2;$y++){
        if ($tipo_emissao=="datas"){
          if ($y==($bimestre*2)){
            break;
          }
        }
        if (isset($tot_transf[0][$y])){
          $pdf->cell($cl,$alt,db_formatar($tot_transf[0][$y],'f'),'R',0,"R",0);	 
          $total += $tot_transf[0][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  	
      // listamos o total das 12 colunas
      $pdf->cell($cl,$alt,db_formatar($total,'f'),'R',0,"R",0);
      
      // listamos a previsão
      if (isset($tot_transf[0][13]))
      $pdf->cell($cl,$alt,db_formatar($tot_transf[0][13],'f'),0,0,"R",0);	 
      else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),0,0,"R",0);
      $pdf->ln();   
      
    }
    
    // ----------------------------------        
    if ($x==16){
      // aqui imprime a linha com os totalizadores das deduções
      $pdf->setfont('arial','b',6);
      $pdf->cell(60,$alt,"DEDUÇÕES(II)",'R',0,"L",0);    
      
      $total = 0;
      for ($y=$bimestre_anterior;$y<=12;$y++){
        if (isset($Trec[1][$y])){
          $pdf->cell($cl,$alt,db_formatar($Trec[1][$y],'f'),'R',0,"R",0);	 
          $total += $Trec[1][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }   
      for ($y=1;$y <=$bimestre*2;$y++){
        if ($tipo_emissao=="datas"){
          if ($y==($bimestre*2)){
            break;
          }
        }
        if (isset($TrecB[1][$y])){
          $pdf->cell($cl,$alt,db_formatar($TrecB[1][$y],'f'),'R',0,"R",0);	 
          $total += $TrecB[1][$y]; 
        }else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  
      $pdf->cell($cl,$alt,db_formatar($total,'f'),'R',0,"R",0);
      if (isset($TrecB[1][13]))
      $pdf->cell($cl,$alt,db_formatar($TrecB[1][13],'f'),0,0,"R",0);	 
      else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),0,0,"R",0);       
      $pdf->ln();
    }   
    if ($x==16){
      $pdf->setfont('arial','',6);
      $pdf->cell(60,$alt,"   Contrib. Plano Seg. Social Servidor  ",'R',0,"L",0);        
      // Procura valores do exercicio anterior (anousu -1) e imprime
      $total = 0;
      for ($y=$bimestre_anterior;$y<=12;$y++){
        if (isset($rec[$x][$y])){
          $pdf->cell($cl,$alt,db_formatar($rec[$x][$y],'f'),'R',0,"R",0);	 
          $total += $rec[$x][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  
      // procura valores do exercicio atual
      for ($y=1;$y <=$bimestre*2;$y++){
        if ($tipo_emissao=="datas"){
          if ($y==($bimestre*2)){
            break;
          }
        }
        if (isset($recB[$x][$y])){
          $pdf->cell($cl,$alt,db_formatar($recB[$x][$y],'f'),'R',0,"R",0);	 
          $total += $recB[$x][$y];
        } else
        $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
      }  
      // listamos o total das 12 colunas
      $pdf->cell($cl,$alt,db_formatar($total,'f'),'R',0,"R",0);
      
      // listamos a previsão
      if (isset($recB[$x][13]))
      $pdf->cell($cl,$alt,db_formatar($recB[$x][13],'f'),0,0,"R",0);	 
      else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),0,0,"R",0);
      
      $pdf->ln();   
    } 
    
    // ----------------------------------        
    // imprime os nomes das linhas
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,$rec[$x][0],'R',0,"L",0);        
    // Procura valores do exercicio anterior (anousu -1) e imprime
    $total = 0;
    for ($y=$bimestre_anterior;$y<=12;$y++){
      if (isset($rec[$x][$y])){
        if ($x == 18) {
          $rec[$x][$y] *= -1;
        }
        $pdf->cell($cl,$alt,db_formatar($rec[$x][$y],'f'),'R',0,"R",0);	 
        $total += $rec[$x][$y];
      } else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
    }  
    // procura valores do exercicio atual
    for ($y=1;$y <=$bimestre*2;$y++){
      if ($tipo_emissao=="datas"){
        if ($y==($bimestre*2)){
          break;
        }
      }
      if (isset($recB[$x][$y])){
        if ($x == 18) {
          $recB[$x][$y] *= -1;
        }
        $pdf->cell($cl,$alt,db_formatar($recB[$x][$y],'f'),'R',0,"R",0);	 
        $total += $recB[$x][$y];
      } else
      $pdf->cell($cl,$alt,db_formatar(0,'f'),'R',0,"R",0);
    }  
    // listamos o total das 12 colunas
    $pdf->cell($cl,$alt,db_formatar($total,'f'),'R',0,"R",0);
    
    // listamos a previsão
    if (isset($recB[$x][13])) {
       if ($x == 18) {
        $recB[$x][13] *= -1;
      }  
      $pdf->cell($cl,$alt,db_formatar($recB[$x][13],'f'),0,0,"R",0);	 
    } else {
      $pdf->cell($cl,$alt,db_formatar(0,'f'),0,0,"R",0);
    }  
    
    $pdf->ln();   
    
  }//endfor
  
  // aqui imprime a linha com os totalizadores das deduções
  
  $pdf->setfont('arial','b',6);
  $pdf->cell(60,$alt,"RECEITA CORRENTE LÍQUIDA (I-II)",'RBT',0,"L",0);    
  $total = 0;
	
  for ($y=$bimestre_anterior;$y<=12;$y++){
    if (isset($Trec[0][$y])){ 
      if(isset($Trec[1][$y])){
        $pdf->cell($cl,$alt,db_formatar($Trec["0"]["$y"]-$Trec["1"]["$y"],'f'),'RBT',0,"R",0);	 
        $total += $Trec["0"]["$y"]-$Trec["1"]["$y"];
      } else {
        $pdf->cell($cl,$alt,db_formatar($Trec[0][$y],'f'),'RBT',0,"R",0);	 
        $total += $Trec[0][$y];
      }  
    } else{
      $pdf->cell($cl,$alt,db_formatar(0,'f'),'RBT',0,"R",0);
    }   
  }   
  // bimestre do exercício corrente
  for ($y=1;$y <=$bimestre*2;$y++){
    if ($tipo_emissao=="datas"){
      if ($y==($bimestre*2)){
        break;
      }
    }
    if (isset($TrecB[0][$y])){
      if (isset($TrecB[1][$y])){
        $pdf->cell($cl,$alt,db_formatar($TrecB[0][$y]-$TrecB[1][$y],'f'),'RTB',0,"R",0);	 
        $total += $TrecB[0][$y]-$TrecB[1][$y];
      }else{
        $pdf->cell($cl,$alt,db_formatar($TrecB[0][$y],'f'),'RTB',0,"R",0);	 
        $total += $TrecB[0][$y];
      }	
    }else {
			$pdf->cell($cl,$alt,db_formatar(0,'f'),'TRB',0,"R",0);
		}
//		echo "$y: " . $TrecB[0][$y] . "<br>";
  }

  $pdf->cell($cl,$alt,db_formatar($total,'f'),'TRB',0,"R",0);
  if (isset($TrecB[0][13])){
    if (isset($TrecB[1][13])){
      $pdf->cell($cl,$alt,db_formatar($TrecB[0][13]-$TrecB[1][13],'f'),'TB',0,"R",0);	 
    } else{
      $pdf->cell($cl,$alt,db_formatar($TrecB[0][13],'f'),'TB',0,"R",0);	      
    }  
  }else{
    $pdf->cell($cl,$alt,db_formatar(0,'f'),'TB',0,"R",0);       
  }   
  $pdf->ln();
  // ----------------------------------------------------------------
  
  $pdf->cell(20,$alt,"Fonte: Contabilidade",'',1,"L",0);
  // assinaturas
  
  $pdf->ln(15);
  
  assinaturas(&$pdf,&$classinatura,'LRF');
  
  $pdf->Output();
  
}

?>