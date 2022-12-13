<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcaixa_ze.php");
require_once("libs/db_libgertxt.php");
require_once("classes/db_empage_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_empord_classe.php");
require_once("classes/db_empagepag_classe.php");
require_once("classes/db_empageslip_classe.php");
require_once("classes/db_empagemovforma_classe.php");
require_once("classes/db_empagegera_classe.php");
require_once("classes/db_empageconf_classe.php");
require_once("classes/db_empageconfgera_classe.php");
require_once("classes/db_conplanoconta_classe.php");
require_once("classes/db_empagemod_classe.php");
require_once("classes/db_db_bancos_classe.php");

$cllayout_BBBS    = new cl_layout_BBBS;
$clempage         = new cl_empage;
$clempagetipo     = new cl_empagetipo;
$clempagemov      = new cl_empagemov;
$clempord         = new cl_empord;
$clempagepag      = new cl_empagepag;
$clempageslip     = new cl_empageslip;
$clempagemovforma = new cl_empagemovforma;
$clempagegera     = new cl_empagegera;
$clempageconf     = new cl_empageconf;
$clempageconfgera = new cl_empageconfgera;
$clempagemod      = new cl_empagemod;
$cldb_bancos      = new cl_db_bancos;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = false;

if(isset($mostra)){
  $result_arquivo = $clempagegera->sql_record($clempagegera->sql_query_file(null,"*","","e87_codgera=$e87_codgera"));
  if($clempagegera->numrows>0){
  	db_fieldsmemory($result_arquivo,0);
  	$db_opcao = 3;
  }else{
  	$e87_descgera = "CHAVE ($e87_codgera) NÃO ENCONTRADO";
  	$e87_codgera  = "";
  }
}else if(isset($movimentos) && trim($movimentos)!=""){
  
  db_inicio_transacao();
  $sqlerro = false;
  $arr_movimentos = split(",",$movimentos);  
  for($i=0; $i < sizeof($arr_movimentos); $i++) {
  	
    $movimento = $arr_movimentos[$i];
    $clempageconfgera->e90_codmov    = $movimento;
    $clempageconfgera->e90_codgera   = $e87_codgera;
    $clempageconfgera->e90_cancelado = 'true';
  	$clempageconfgera->alterar($clempageconfgera->e90_codmov, $clempageconfgera->e90_codgera);
  	
    if ($clempageconfgera->erro_status == 0) {
      
      $erro_msg = $clempageconfgera->erro_msg;
      $sqlerro = true;
  	  break;
    }
  }
  
  $reemite = true;
  $result_contamovimentosarquivo = $clempageconfgera->sql_record($clempageconfgera->sql_query_file(null,$e87_codgera,"count(*) as contador"));
  db_fieldsmemory($result_contamovimentosarquivo,0);
  
  if($contador == 0){
  	$reemite = false;
  }
  
  
  
  
  if($reemite == true){
  	$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
    $resultinst = db_query($sqlinst);
    db_fieldsmemory($resultinst,0);
  	$sql = "
  select  distinct
	  e90_codgera,
	  e90_codmov,
	  e87_data,
	  e87_dataproc,
	  c63_banco,
	  c63_agencia,
	  c63_dvagencia,
	  c63_conta,
	  c63_dvconta,
	  pc63_agencia,
	  pc63_agencia_dig,	  
	  pc63_conta,
	  pc63_conta_dig,	  
	  translate(to_char(round(e81_valor,2),'99999999999.99'),'.','') as valor,
	  e81_valor as valorori,
	  case when  pc63_banco = c63_banco then '01' else '03' end as  lanc,
	  coalesce(pc63_banco,'000') as pc63_banco,
	  e83_convenio as convenio,	  
	  z01_numcgm as numcgm,
	  substr(z01_nome,1,40) as z01_nome,
	  case when pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then length(trim(z01_cgccpf)) else length(trim(pc63_cnpjcpf)) end as tam,
	  case when  pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then z01_cgccpf else pc63_cnpjcpf end as z01_cgccpf,
	  e88_codmov as cancelado,
	  z01_ender,
	  z01_numero,
	  z01_compl,
	  z01_bairro,
	  z01_munic,
	  z01_cep,
	  z01_cep,
	  z01_uf
  from empageconfgera
	  inner join empagegera on e90_codgera=e87_codgera
	  inner join empagemov on e90_codmov = e81_codmov 
    inner join empage on empage.e80_codage = empagemov.e81_codage
	  left join empempenho on e60_numemp = e81_numemp
	  inner join empagepag on e81_codmov = e85_codmov 
	  inner join empagetipo on e85_codtipo = e83_codtipo 
	  left join empageslip on e81_codmov = e89_codmov 
	  inner join conplanoreduz on e83_conta = c61_reduz and c61_anousu=".db_getsession("DB_anousu")." and c61_instit = " . db_getsession("DB_instit") . "
	  inner join conplanoconta on c63_codcon = c61_codcon and c63_anousu = c61_anousu 
	  left join slip on slip.k17_codigo = e89_codigo
	  left join slipnum on slipnum.k17_codigo = slip.k17_codigo
	  left join empageconfcanc on e88_codmov = e90_codmov
	  left join empagemovconta on e90_codmov = e98_codmov
	  left join pcfornecon on pc63_contabanco = e98_contabanco
	  left join cgm on z01_numcgm = pc63_numcgm
	  
  where e80_instit = " . db_getsession("DB_instit") . " and e90_codgera = $e87_codgera 

  order by c63_conta,lanc,e90_codmov
	 ";
//	 die($sql);
    $result  =  @db_query($sql);
    $numrows =  @pg_numrows($result);
    if($numrows==0){
      $sqlerro =true;
      $erro_msg = "Erro. Contate suporte";
    }
    if($sqlerro==false){
      ///////////////////////////////////////////////////////////////////////////////////////////////////////
      //////LAYOUTS//////////////////////////////////////////////////////////////////////////////////////////
      //// LAYOUT BANCO DO BANRISUL
      $registro = 0;
      db_fieldsmemory($result,0);


      if($sqlerro == false){
	$arr_data  = split("-",$e87_data);
	$arr_datap = split("-",$e87_dataproc);
	$data    =  $arr_data[2].$arr_data[1].$arr_data[0];
	$dat_cred = $arr_datap[2].$arr_datap[1].$arr_datap[0];
	/// criar campo de sequencia do arquivo no empagetipo.
	$banco = db_formatar(str_replace('.','',str_replace('-','',$c63_banco)),'s','0',3,'e',0);

	$nomearquivo = 'pagt'.$c63_banco.'_'.$arr_datap[2].'-'.$arr_datap[1].'-'.$arr_datap[0].'_'.$e90_codgera.'.txt';
	//indica qual será o nome do arquivo
        
	$cllayout_BBBS->nomearq = "tmp/$nomearquivo";
       
	if(!is_writable("tmp/")){
	  $sqlerro= true;
	  $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
	}
	
	if($sqlerro == false){
	
	  if($banco == '001'){
	    $dbanco = db_formatar('BANCO DO BRASIL','s',' ',30,'d',0);
	  }else{
	    $dbanco = db_formatar('BANRISUL','s',' ',30,'d',0);; 
	  }
	  $agencia_pre = db_formatar(str_replace('.','',str_replace('-','',$c63_agencia)),'s','0',5,'e',0);

	  $dvagencia_pre = "0";
	  $dvconta_pre   = "0";
	  $dvagconta_pre = "0";

	  if(trim($c63_dvconta)!=""){
	    $digitos = strlen($c63_dvconta);
	    $dvconta_pre   = $c63_dvconta[0];
	    if($digitos>1){
	      $dvagconta_pre = $c63_dvconta[1];
	    }
	  }
	  if(trim($c63_dvagencia)!=""){
	    $digitos1 = strlen($c63_dvagencia);
	    $dvagencia_pre   = $c63_dvagencia[0];
	    if(isset($digitos) && $digitos==1){
	      $dvagconta_pre = $c63_dvagencia[0];
	      if($digitos1>1){
		$dvagconta_pre = $c63_dvagencia[1];
	      }
	    }
	  }

	  $numero_dia = 2;
	  $seq_arq = 1; 
	  
	  ///// HEADER DO ARQUIVO
	  if($banco=="041"){
	    $conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta.$dvconta_pre)),'s','0',10,'e',0);
	    $cllayout_BBBS->BSheaderA_001_003 = $banco;
	    $cllayout_BBBS->BSheaderA_004_007 = str_repeat('0',4);
	    $cllayout_BBBS->BSheaderA_008_008 = "0";
	    $cllayout_BBBS->BSheaderA_009_017 = str_repeat(' ',9);
	    $cllayout_BBBS->BSheaderA_018_018 = "2";	  
	    $cllayout_BBBS->BSheaderA_019_032 = $cgc;
	    $cllayout_BBBS->BSheaderA_033_037 = db_formatar(substr($convenio,0,5),'s',' ',5,'e',0);
	    $cllayout_BBBS->BSheaderA_038_052 = str_repeat(' ',15);
	    $cllayout_BBBS->BSheaderA_053_057 = $agencia_pre;
	    $cllayout_BBBS->BSheaderA_058_058 = "0";
	    $cllayout_BBBS->BSheaderA_059_061 = str_repeat('0',3);
	    $cllayout_BBBS->BSheaderA_062_071 = $conta_pre;
	    $cllayout_BBBS->BSheaderA_072_072 = "0";
	    $cllayout_BBBS->BSheaderA_073_102 = db_formatar(substr(strtoupper($nomeinst),0,30),'s',' ',30,'e',0);
	    $cllayout_BBBS->BSheaderA_103_132 = $dbanco;
	    $cllayout_BBBS->BSheaderA_133_142 = str_repeat(' ',10);
	    $cllayout_BBBS->BSheaderA_143_143 = "1";
	    $cllayout_BBBS->BSheaderA_144_151 = $data;
	    $cllayout_BBBS->BSheaderA_152_157 = date("H").date("i").date("s");
	    $cllayout_BBBS->BSheaderA_158_163 = db_formatar($e90_codgera,'s','0',6,'e',0);
	    $cllayout_BBBS->BSheaderA_164_166 = "030";
	    $cllayout_BBBS->BSheaderA_167_171 = str_repeat('0',5);
	    $cllayout_BBBS->BSheaderA_172_191 = str_repeat(' ',20);
	    $cllayout_BBBS->BSheaderA_192_211 = db_formatar($e90_codgera,'s',' ',20,'e',0);
	    $cllayout_BBBS->BSheaderA_212_240 = str_repeat(' ',29);
	    $cllayout_BBBS->geraHEADERArqBS();
	  }else if($banco=="001"){
	    $conveniobb  = db_formatar(trim($convenio),'s','0',9,'e',0);
        $conveniobb .= '0126';
	    $conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta)),'s','0',12,'e',0);
	    $cllayout_BBBS->BBheaderA_001_003 = $banco;
	    $cllayout_BBBS->BBheaderA_004_007 = str_repeat('0',4);
	    $cllayout_BBBS->BBheaderA_008_008 = "0";
	    $cllayout_BBBS->BBheaderA_009_017 = str_repeat(' ',9);
	    $cllayout_BBBS->BBheaderA_018_018 = "2";
	    $cllayout_BBBS->BBheaderA_019_032 = $cgc;
	    $cllayout_BBBS->BBheaderA_033_052 = db_formatar($conveniobb,'s',' ',20,'d',0);
	    $cllayout_BBBS->BBheaderA_053_057 = $agencia_pre;
	    $cllayout_BBBS->BBheaderA_058_058 = $dvagencia_pre;
	    $cllayout_BBBS->BBheaderA_059_070 = $conta_pre;
	    $cllayout_BBBS->BBheaderA_071_071 = $dvconta_pre;
	    $cllayout_BBBS->BBheaderA_072_072 = ' ';
	    $cllayout_BBBS->BBheaderA_073_102 = db_formatar(substr(strtoupper($nomeinst),0,30),'s',' ',30,'e',0);
	    $cllayout_BBBS->BBheaderA_103_132 = $dbanco;
	    $cllayout_BBBS->BBheaderA_133_142 = str_repeat(' ',10);
	    $cllayout_BBBS->BBheaderA_143_143 = "1";
	    $cllayout_BBBS->BBheaderA_144_151 = $data;
	    $cllayout_BBBS->BBheaderA_152_157 = date("H").date("i").date("s");
	    $cllayout_BBBS->BBheaderA_158_163 = db_formatar($e90_codgera,'s','0',6,'e',0);
	    $cllayout_BBBS->BBheaderA_164_166 = "030";
	    $cllayout_BBBS->BBheaderA_167_171 = str_repeat('0',5);
	    $cllayout_BBBS->BBheaderA_172_191 = str_repeat(' ',20);
	    $cllayout_BBBS->BBheaderA_192_211 = db_formatar($e90_codgera,'s',' ',20,'e',0);
	    $cllayout_BBBS->BBheaderA_212_222 = str_repeat(' ',11);
	    $cllayout_BBBS->BBheaderA_223_225 = str_repeat(' ',3);
	    $cllayout_BBBS->BBheaderA_226_228 = str_repeat('0',3);
	    $cllayout_BBBS->BBheaderA_229_230 = str_repeat(' ',2);
	    $cllayout_BBBS->BBheaderA_231_240 = str_repeat(' ',10);
	    $cllayout_BBBS->geraHEADERArqBB();
	  }  
	  ///// FINAL HEADER DO ARQUIVO
	  
	  $seq_header   = 0;
	  $xconta       = '';
	  $registro     = 1;
	  $valor_header = 0;
	  $xlanc        = 0;
          $cep          = str_replace(".","",$cep);
          $cep          = str_replace("-","",$cep);
	  for($i = 0;$i < $numrows;$i++){
	    if($i == 0){
	      $pri = "$c63_banco";
	    }  
	    db_fieldsmemory($result,$i);
	    if($xconta != $c63_conta || $lanc != $xlanc){
	      $xconta = $c63_conta;
	      $xlanc  = $lanc;
	      if($pri != $pc63_banco){
		$pri = $pc63_banco;
	      }
	      if($pc63_banco == $banco){
		$tiposerv = "20";
		$tipopag = "01";
	      }else{
		if($banco=="001" || $valorori<5000){
		  $tiposerv = "20";
		}else{
		  $tiposerv = "12";
		}
		$tipopag = "03";
	      }
	      
	      if($seq_header != 0){
		///// TRAILLER DO LOTE
		$cllayout_BBBS->BBBStraillerL_001_003 = $banco; 
		$cllayout_BBBS->BBBStraillerL_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
		$cllayout_BBBS->BBBStraillerL_008_008 = '5';
		$cllayout_BBBS->BBBStraillerL_009_017 = str_repeat(' ',9);
		$cllayout_BBBS->BBBStraillerL_018_023 = db_formatar($seq_detalhe + 2,'s','0',6,'e',0); 
		$cllayout_BBBS->BBBStraillerL_024_041 = db_formatar(str_replace(',','',str_replace('.','',$valor_header)),'s','0',18,'e',0);
		$cllayout_BBBS->BBBStraillerL_042_059 = str_repeat('0',18);
		$cllayout_BBBS->BBBStraillerL_060_230 = str_repeat(' ',171);
		$cllayout_BBBS->BBBStraillerL_231_240 = str_repeat(' ',10);
		$cllayout_BBBS->geraTRAILLERLote();
		$valor_header = 0;
		$registro += 1;
		///// FINAL DO TRAILLER DO LOTE
	      }
	      $seq_header  += 1;
	      $seq_detalhe  = 0;
	      $registro    += 1;

	      $agencia_pre = db_formatar(str_replace('.','',str_replace('-','',$c63_agencia)),'s','0',5,'e',0);

	      $dvagencia_pre = "0";
	      $dvconta_pre   = "0";
	      $dvagconta_pre = "0";

	      if(trim($c63_dvconta)!=""){
		$digitos = strlen($c63_dvconta);
		$dvconta_pre   = $c63_dvconta[0];
		if($digitos>1){
		  $dvagconta_pre = $c63_dvconta[1];
		}
	      }
	      if(trim($c63_dvagencia)!=""){
		$digitos1 = strlen($c63_dvagencia);
		$dvagencia_pre   = $c63_dvagencia[0];
		if(isset($digitos) && $digitos==1){
		  $dvagconta_pre = $c63_dvagencia[0];
		  if($digitos1>1){
		    $dvagconta_pre = $c63_dvagencia[1];
		  }
		}
	      }
	      
	      // HEADER DO LOTE
	      if($banco=="041"){
		$conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta.$dvconta_pre)),'s','0',10,'e',0);
		$cllayout_BBBS->BSheaderL_001_003 = $banco;
		$cllayout_BBBS->BSheaderL_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
		$cllayout_BBBS->BSheaderL_008_008 = "1";
		$cllayout_BBBS->BSheaderL_009_009 = "C";
		$cllayout_BBBS->BSheaderL_010_011 = $tiposerv;
		$cllayout_BBBS->BSheaderL_012_013 = $tipopag;
		$cllayout_BBBS->BSheaderL_014_016 = '020';
		$cllayout_BBBS->BSheaderL_017_017 = ' ';
		$cllayout_BBBS->BSheaderL_018_018 = '2'; 
		$cllayout_BBBS->BSheaderL_019_032 = $cgc;
		$cllayout_BBBS->BSheaderL_033_037 = db_formatar(substr($convenio,0,5),'s',' ',5,'e',0);
		$cllayout_BBBS->BSheaderL_038_052 = str_repeat(' ',15);
		$cllayout_BBBS->BSheaderL_053_057 = $agencia_pre;
		$cllayout_BBBS->BSheaderL_058_061 = str_repeat('0',4);
		$cllayout_BBBS->BSheaderL_062_071 = $conta_pre;
		$cllayout_BBBS->BSheaderL_072_072 = " ";
		$cllayout_BBBS->BSheaderL_073_102 = substr(strtoupper($nomeinst),0,30);
		$cllayout_BBBS->BSheaderL_103_142 = str_repeat(' ',40);
		$cllayout_BBBS->BSheaderL_143_172 = db_formatar(strtoupper($ender),'s',' ',30,'d',0);
		$cllayout_BBBS->BSheaderL_173_177 = db_formatar($numero,'s',' ',5,'e',0);
		$cllayout_BBBS->BSheaderL_178_192 = str_repeat(' ',15); 
		$cllayout_BBBS->BSheaderL_193_212 = db_formatar(strtoupper($munic),'s',' ',20,'d',0);
		$cllayout_BBBS->BSheaderL_213_220 = db_formatar($cep,'s',' ',8,'e',0);
		$cllayout_BBBS->BSheaderL_221_222 = $uf;
		$cllayout_BBBS->BSheaderL_223_224 = str_repeat(' ',2);
		$cllayout_BBBS->BSheaderL_225_240 = str_repeat(' ',16);
		$cllayout_BBBS->geraHEADERLoteBS();
	      }else if($banco=="001"){
		$conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta)),'s','0',12,'e',0);
		$tamanho = strlen($cep);
		if($tamanho>5){
		  $com = db_formatar(substr($cep,5,$tamanho),'s',' ',3,'d',0);
		  $cep = substr($cep,0,5);
		}
        $conveniobb  = db_formatar(trim($convenio),'s','0',9,'e',0);
        $conveniobb .= '0126';
		$cllayout_BBBS->BBheaderL_001_003 = $banco;
		$cllayout_BBBS->BBheaderL_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
		$cllayout_BBBS->BBheaderL_008_008 = "1";
		$cllayout_BBBS->BBheaderL_009_009 = "C";
		$cllayout_BBBS->BBheaderL_010_011 = $tiposerv;
		$cllayout_BBBS->BBheaderL_012_013 = $tipopag;
		$cllayout_BBBS->BBheaderL_014_016 = '020';
		$cllayout_BBBS->BBheaderL_017_017 = ' ';
		$cllayout_BBBS->BBheaderL_018_018 = '2';
		$cllayout_BBBS->BBheaderL_019_032 = $cgc;
		$cllayout_BBBS->BBheaderL_033_052 = db_formatar($conveniobb,'s',' ',20,'d',0) ;
		$cllayout_BBBS->BBheaderL_053_057 = $agencia_pre;
		$cllayout_BBBS->BBheaderL_058_058 = $dvagencia_pre;
		$cllayout_BBBS->BBheaderL_059_070 = $conta_pre;
		$cllayout_BBBS->BBheaderL_071_071 = $dvconta_pre;
		$cllayout_BBBS->BBheaderL_072_072 = ' ';
		$cllayout_BBBS->BBheaderL_073_102 = substr(strtoupper($nomeinst),0,30);
		$cllayout_BBBS->BBheaderL_103_142 = str_repeat(' ',40);
		$cllayout_BBBS->BBheaderL_143_172 = db_formatar(strtoupper($ender),'s',' ',30,'d',0);
		$cllayout_BBBS->BBheaderL_173_177 = db_formatar($numero,'s',' ',5,'e',0);
		$cllayout_BBBS->BBheaderL_178_192 = str_repeat(' ',15); 
		$cllayout_BBBS->BBheaderL_193_212 = db_formatar(strtoupper(trim($munic)),'s',' ',20,'d',0);	      
		$cllayout_BBBS->BBheaderL_213_217 = db_formatar($cep,'s',' ',5,'e',0);
		$cllayout_BBBS->BBheaderL_218_220 = $com;
		$cllayout_BBBS->BBheaderL_221_222 = $uf;
		$cllayout_BBBS->BBheaderL_223_230 = str_repeat(' ',8);
		$cllayout_BBBS->BBheaderL_231_240 = str_repeat(' ',10);
		$cllayout_BBBS->geraHEADERLoteBB();
	      }
	      // FINAL HEADER DO LOTE
	    }
	    
	    $seq_detalhe += 1;
	    $tot_valor    = 0;
	    $numero_lote  = 1;
	    
	    $tama =  strlen($pc63_conta);
	    if($tama>11){
	      $pc63_conta = substr($pc63_conta,($tama-11));
	    }
	    
	    if($tam == 14){
	      $conf = 2;
	      $cgccpf = $z01_cgccpf;
	    }elseif($tam == 11){
	      $conf = 1;
	      $cgccpf = db_formatar($z01_cgccpf,'s','0',14,'e',0);
	    }else{
	      $conf = 3;
	      $cgccpf= str_repeat('0',14);
	    }
	    
	    $registro += 1;
	    $compensacao = "   ";
	    if($pc63_banco == $banco || $valorori<5000){
	      if($banco=="001"){
		$compensacao = "700";
	      }else{
		$compensacao = "010";
	      }
	    }else{
	      if($valorori>=5000){
		$compensacao = "018";
	      }
	    }

	    $agencia_fav = $pc63_agencia;
	    $conta_fav   = db_formatar(str_replace('.','',str_replace('-','',trim($pc63_conta))),'s','0',12,'e',0);
	    
	    $dvagencia_fav = "0";
	    $dvconta_fav   = "0";
	    $dvagconta_fav = "0";

	    if(trim($pc63_conta_dig)!=""){
	      $digitos2 = strlen($pc63_conta_dig);
	      $dvconta_fav   = $pc63_conta_dig[0];
	      if($digitos2>1){
		$dvagconta_fav = $pc63_conta_dig[1];
	      }
	    }
	    if(trim($pc63_agencia_dig)!=""){
	      $digitos3 = strlen($pc63_agencia_dig);
	      $dvagencia_fav   = $pc63_agencia_dig[0];
	      if(isset($digitos2) && $digitos2==1){
		$dvagconta_fav = $pc63_agencia_dig[0];
		if($digitos3>1){
		  $dvagconta_fav = $pc63_agencia_dig[1];
		}
	      }
	    }

	    if($banco == '001' && $pc63_banco == '001'){
	      $dvagconta_fav = '0';
	    }
	     
	    // REGISTROS 
	    if($banco=="041"){
	      $cllayout_BBBS->BSregist_001_003 = $banco; 
	      $cllayout_BBBS->BSregist_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
	      $cllayout_BBBS->BSregist_008_008 = "3"; 
	      $cllayout_BBBS->BSregist_009_013 = db_formatar($seq_detalhe,'s','0',5,'e',0);
	      $cllayout_BBBS->BSregist_014_014 = "A"; 
	      $cllayout_BBBS->BSregist_015_015 = "0";
	      $cllayout_BBBS->BSregist_016_017 = "00";
	      $cllayout_BBBS->BSregist_018_020 = $compensacao; 
	      $cllayout_BBBS->BSregist_021_023 = $pc63_banco;
	      $cllayout_BBBS->BSregist_024_028 = db_formatar($agencia_fav,'s','0',5,'e',0);
	      $cllayout_BBBS->BSregist_029_029 = "0"; 
	      $cllayout_BBBS->BSregist_030_042 = $conta_fav.$dvconta_fav;
	      $cllayout_BBBS->BSregist_043_043 = " "; 
	      $cllayout_BBBS->BSregist_044_073 = db_formatar(str_replace("-",'',substr($z01_nome,0,30)),'s',' ',30,'d',0);
	      $cllayout_BBBS->BSregist_074_088 = db_formatar($e90_codmov,'s','0',15,'d',0);
	      $cllayout_BBBS->BSregist_089_093 = "00005";
	      $cllayout_BBBS->BSregist_094_101 = $dat_cred;
	      $cllayout_BBBS->BSregist_102_104 = "BRL"; 
	      $cllayout_BBBS->BSregist_105_119 = str_repeat('0',15);
	      $cllayout_BBBS->BSregist_120_134 = db_formatar(str_replace(',','',str_replace('.','',$valor)),'s','0',15,'e',0);
	      $cllayout_BBBS->BSregist_135_154 = str_repeat(' ',20);
	      $cllayout_BBBS->BSregist_155_162 = str_repeat(' ',8);
	      $cllayout_BBBS->BSregist_163_177 = str_repeat(' ',15);
	      $cllayout_BBBS->BSregist_178_182 = str_repeat(' ',5);
	      $cllayout_BBBS->BSregist_183_202 = str_repeat(' ',20);
	      $cllayout_BBBS->BSregist_203_203 = $conf;
	      $cllayout_BBBS->BSregist_204_217 = $cgccpf;
	      $cllayout_BBBS->BSregist_218_229 = str_repeat(' ',12);
	      $cllayout_BBBS->BSregist_230_230 = "0";
	      $cllayout_BBBS->BSregist_231_240 = str_repeat(' ',10);
	      $cllayout_BBBS->geraREGISTROSBS();
	    }else if($banco=="001"){
              // REGISTROS SEGMENTO A
	      if($pc63_banco == $banco){
		$compensacao = str_repeat('0',3);
	      }
	      $cllayout_BBBS->BBregistA_001_003 = $banco; 
	      $cllayout_BBBS->BBregistA_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
	      $cllayout_BBBS->BBregistA_008_008 = "3"; 
	      $cllayout_BBBS->BBregistA_009_013 = db_formatar($seq_detalhe,'s','0',5,'e',0);
	      $cllayout_BBBS->BBregistA_014_014 = "A"; 
	      $cllayout_BBBS->BBregistA_015_015 = "0";
	      $cllayout_BBBS->BBregistA_016_017 = "00";
	      $cllayout_BBBS->BBregistA_018_020 = $compensacao; 
	      $cllayout_BBBS->BBregistA_021_023 = $pc63_banco;	    
	      $cllayout_BBBS->BBregistA_024_028 = db_formatar(str_replace('.','',str_replace('-','',$agencia_fav)),'s','0',5,'e',0);
	      $cllayout_BBBS->BBregistA_029_029 = $dvagencia_fav; 
	      $cllayout_BBBS->BBregistA_030_041 = $conta_fav;
	      $cllayout_BBBS->BBregistA_042_042 = $dvconta_fav; 
	      $cllayout_BBBS->BBregistA_043_043 = $dvagconta_fav;
	      $cllayout_BBBS->BBregistA_044_073 = db_formatar(str_replace("-",'',substr($z01_nome,0,30)),'s',' ',30,'d',0);
	      $cllayout_BBBS->BBregistA_074_093 = db_formatar($e90_codmov,'s','0',20,'d',0);
	      $cllayout_BBBS->BBregistA_094_101 = $dat_cred;
	      $cllayout_BBBS->BBregistA_102_104 = "BRL"; 
	      $cllayout_BBBS->BBregistA_105_119 = str_repeat('0',15);
	      $cllayout_BBBS->BBregistA_120_134 = db_formatar(str_replace(',','',str_replace('.','',$valor)),'s','0',15,'e',0);
	      $cllayout_BBBS->BBregistA_135_154 = str_repeat(' ',20);
	      $cllayout_BBBS->BBregistA_155_162 = str_repeat(' ',8);
	      $cllayout_BBBS->BBregistA_163_177 = str_repeat(' ',15);
	      $cllayout_BBBS->BBregistA_178_217 = str_repeat(' ',40);
	      $cllayout_BBBS->BBregistA_218_229 = str_repeat(' ',12);
	      $cllayout_BBBS->BBregistA_230_230 = "0";
	      $cllayout_BBBS->BBregistA_231_240 = str_repeat(' ',10);
          $seq_detalhe += 1;


          $z01_cep = str_replace(".","",$z01_cep);
          $z01_cep = str_replace("-","",$z01_cep);
          // REGISTROS SEGMENTO B
	      $cllayout_BBBS->BBregistB_001_003 = $banco; 
	      $cllayout_BBBS->BBregistB_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
	      $cllayout_BBBS->BBregistB_008_008 = "3"; 
	      $cllayout_BBBS->BBregistB_009_013 = db_formatar($seq_detalhe,'s','0',5,'e',0);
	      $cllayout_BBBS->BBregistB_014_014 = "B"; 
	      $cllayout_BBBS->BBregistB_015_017 = str_repeat(' ',3);
	      $cllayout_BBBS->BBregistB_018_018 = $conf;
	      $cllayout_BBBS->BBregistB_019_032 = $cgccpf;
	      $cllayout_BBBS->BBregistB_033_062 = db_formatar(substr($z01_ender,0,30),'s',' ',30,'d',0);
	      $cllayout_BBBS->BBregistB_063_067 = db_formatar($z01_numero,'s','0',5,'e',0);
	      $cllayout_BBBS->BBregistB_068_082 = db_formatar(substr($z01_compl,0,30),'s',' ',15,'d',0);
	      $cllayout_BBBS->BBregistB_083_097 = db_formatar(substr($z01_bairro,0,30),'s',' ',15,'d',0);
	      $cllayout_BBBS->BBregistB_098_117 = db_formatar(substr($z01_munic,0,20),'s',' ',20,'d',0); 
	      $cllayout_BBBS->BBregistB_118_122 = substr($z01_cep,0,5);
	      $cllayout_BBBS->BBregistB_123_125 = substr($z01_cep,5,3);
	      $cllayout_BBBS->BBregistB_126_127 = db_formatar($z01_uf,'s',' ',2,'d',0);
	      $cllayout_BBBS->BBregistB_128_135 = $dat_cred;
	      $cllayout_BBBS->BBregistB_136_150 = db_formatar(str_replace(',','',str_replace('.','',$valor)),'s','0',15,'e',0);
	      $cllayout_BBBS->BBregistB_151_165 = str_repeat('0',15);
	      $cllayout_BBBS->BBregistB_166_180 = str_repeat('0',15);
	      $cllayout_BBBS->BBregistB_181_195 = str_repeat('0',15);
	      $cllayout_BBBS->BBregistB_196_210 = str_repeat('0',15);
	      $cllayout_BBBS->BBregistB_211_225 = str_repeat(' ',15);
	      $cllayout_BBBS->BBregistB_226_240 = str_repeat(' ',15);
	      $cllayout_BBBS->geraREGISTROSBB();
              $registro += 1;
	    }
	    $valor_header += $valor;
	    // FINAL REGISTROS 
	  }
	  
	    
	  ///// TRAILLER DO LOTE
	  $cllayout_BBBS->BBBStraillerL_001_003 = $banco; 
	  $cllayout_BBBS->BBBStraillerL_004_007 = db_formatar($seq_header,'s','0',4,'e',0);
	  $cllayout_BBBS->BBBStraillerL_008_008 = '5';
	  $cllayout_BBBS->BBBStraillerL_009_017 = str_repeat(' ',9);
	  $cllayout_BBBS->BBBStraillerL_018_023 = db_formatar($seq_detalhe + 2,'s','0',6,'e',0); 
	  $cllayout_BBBS->BBBStraillerL_024_041 = db_formatar(str_replace(',','',str_replace('.','',$valor_header)),'s','0',18,'e',0);
	  $cllayout_BBBS->BBBStraillerL_042_059 = str_repeat('0',18);
	  $cllayout_BBBS->BBBStraillerL_060_230 = str_repeat(' ',171);
	  $cllayout_BBBS->BBBStraillerL_231_240 = str_repeat(' ',10);
	  $cllayout_BBBS->geraTRAILLERLote();
	  $valor_header = 0;
	  $registro += 1;
	  ///// FINAL DO TRAILLER DO LOTE
	  

	  ////  TRAILLER DO ARQUIVO
	  $registro += 1;
	  $cllayout_BBBS->BBBStraillerA_001_003 = $banco; 
	  $cllayout_BBBS->BBBStraillerA_004_007 = '9999';
	  $cllayout_BBBS->BBBStraillerA_008_008 = '9';
	  $cllayout_BBBS->BBBStraillerA_009_017 = str_repeat(' ',9);
	  $cllayout_BBBS->BBBStraillerA_018_023 = db_formatar($seq_header,'s','0',6,'e',0);
	  $cllayout_BBBS->BBBStraillerA_024_029 = db_formatar($registro,'s','0',6,'e',0);
	  $cllayout_BBBS->BBBStraillerA_230_035 = str_repeat('0',6);
	  $cllayout_BBBS->BBBStraillerA_236_240 = str_repeat(' ',205);
	  $cllayout_BBBS->geraTRAILLERArquivo();
	  $cllayout_BBBS->gera();
	  ///// FINAL DO TRAILLER DO ARQUIVO

        }
      }
    }
  }
  if($sqlerro==true){
    $db_opcao = 3;
  }
	  //$sqlerro = true;
  db_fim_transacao($sqlerro);
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1.e87_codgera)document.form1.e87_codgera.focus();" >
<center>
   <?
   	include("forms/db_frmcancarq.php");
   ?>
</center>


<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_empage(){
    js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage|e80_data','Pesquisa',true);
}
function js_mostra(codage,data){
  arr = data.split('-');
  
  obj = document.form1;

  obj.e80_data_ano.value = arr[0];
  obj.e80_data_mes.value = arr[1];
  obj.e80_data_dia.value = arr[2];
  obj.e80_data.value = arr[2]+'/'+arr[1]+'/'+arr[0];
 
            obj=document.createElement('input');
            obj.setAttribute('name','pri_codage');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',codage);
            document.form1.appendChild(obj);

  document.form1.pesquisar.click();
 
  db_iframe_empage.hide();
  
}
</script>
<?//
if(isset($sqlerro)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{
    if($reemite == true){
    
    $sEmite = 'Cancelamento efetuado com sucesso.\nDeseja imprimir o arquivo?';
    echo "
    <script>
      function js_emitir(){

        if ( confirm( '".$sEmite."') ) {

          jan = window.open('tmp/$nomearquivo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          jan.moveTo(0,0);
        }
        
      }   
      
      js_emitir();
      
    </script>
    ";
  	}
  	echo '<script>location.href = "emp4_empagecancarq001.php";</script>';
  	
  }
}
?>