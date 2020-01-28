<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once("fpdf151/impcarne.php");
require_once("fpdf151/scpdf.php");

require_once("libs/db_sql.php");
require_once("libs/db_libpessoal.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_rhdepend_classe.php");
require_once("classes/db_cfpess_classe.php");
require_once("classes/db_rhpessoalmov_classe.php");

$oDaoCfpess = new cl_cfpess;
/**
 * Tipo de relatório comprovante de rescisão
 * Retorna false caso der erro na consulta
 */   
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('termorescisao', db_anofolha(), db_mesfolha());
//$iTipoRelatorio = 34;


if ( !$iTipoRelatorio ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode('Modelo de impressão invalido, verifique parametros.'));
}

if ( $iTipoRelatorio == 80 ) {

	try {

		require_once ('pes2_termorescisaomodimprime80.php');

	} catch (Exception $oErro) {
		db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode($oErro->getMessage()) );
	}

	exit;
}

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$cl_depend = new cl_rhdepend;

db_sel_cfpess($anofolha, $mesfolha, "r11_codaec");

$sql_inst = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result_inst = db_query($sql_inst);

db_fieldsmemory($result_inst,0);

$cl_rhpessoalmov = new cl_rhpessoalmov;
$clsql           = new cl_gera_sql_folha;

$dbwhere = "";
if($tipores == "m"){
  if($tipofil == "s"){
    $vir = "";
    foreach($selregist as $index=>$valor) {
      $dbwhere.= $vir.$valor;
      $vir = ",";
    }
    $dbwhere = " and rh01_regist in (".$dbwhere.") ";
  }else if($tipofil == "i"){
    if(trim($registro1) != "" && trim($registro2)){
      $dbwhere = " and rh01_regist between ".$registro1." and ".$registro2;
    }else if(trim($registro1) != ""){
      $dbwhere = " and rh01_regist >= ".$registro1;
    }else if(trim($registro2) != ""){
      $dbwhere = " and rh01_regist <= ".$registro2;
    }
  }
}

$sql_cad = $cl_rhpessoalmov->sql_query_rescisao_afastamento($anofolha,$mesfolha,$dbwhere);						                
//echo $sql_cad;exit;
$result_cad = db_query($sql_cad);

$xxnum = pg_numrows($result_cad);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe rescisões para os funcionarios escolhidos no período de '.$mesfolha.' / '.$anofolha);
}

$clsql->usar_doc = false;
$clsql->usar_cgm  = false;
$clsql->usar_tpc  = false;
$clsql->usar_rub  = true;


global $pdf;
$pdf = new scpdf();
$pdf->Open();
$pdf->setfont('Arial','',14);


$pdf1 = new db_impcarne($pdf, $iTipoRelatorio);
$pdf1->logo       = $logo;
$pdf1->prefeitura = $nomeinst;
$pdf1->enderpref  = $ender.', '.$numero;
$pdf1->cgcpref    = $cgc;
$pdf1->bairropref = $bairro;
$pdf1->ceppref    = $cep;
$pdf1->ufpref     = $uf;
$pdf1->cgcpref    = $cgc;
$pdf1->municpref  = $munic;
$pdf1->telefpref  = $telef;
$pdf1->emailpref  = $email;
$pdf1->cnae	  = $r11_codaec;
for($ixx=0; $ixx<$xxnum; $ixx++){
  db_fieldsmemory($result_cad,$ixx);

  $res_depend = $cl_depend->sql_record($cl_depend->sql_query_file(null,"rh31_nome",null,"rh31_regist = $rh01_regist and rh31_gparen = 'M'"));
  if($cl_depend->numrows > 0){
    db_fieldsmemory($res_depend,0);
  }else{
    $rh31_nome = "";
  }
  /// dados pessoais
  $pdf1->nome           = $z01_nome;
  $pdf1->pis		= $rh16_pis;
  $pdf1->endereco	= $z01_ender.', '.$z01_numero.'   '.$z01_compl;
  $pdf1->bairro		= $z01_bairro;
  $pdf1->munic		= $z01_munic;
  $pdf1->uf		= $z01_uf;
  $pdf1->cep		= $z01_cep;
  $pdf1->ctps		= $rh16_ctps_n.'/'.$rh16_ctps_s.' '.($rh16_ctps_d==0?'':$rh16_ctps_d).' '.$rh16_ctps_uf;
  $pdf1->cpf		= $z01_cgccpf;
  $pdf1->nasc		= $rh01_nasc;
  $pdf1->admiss         = $rh01_admiss;
  $pdf1->aviso		= '';
  $pdf1->recis		= $rh05_recis;
  $pdf1->causa		= $r59_descr;
  $pdf1->cod_afas	= $r59_movsef; //$r59_codsaq;
  $pdf1->pensao		= 0;
  $pdf1->categoria	= $h13_tpcont;
  $pdf1->regime		= $rh30_regime;
  $pdf1->projativ	= $o55_projativ;
  $pdf1->descr_projativ = $o55_descr;
  if(strtoupper($munic)=='SAPIRANGA'){
    $pdf1->descr_recurso 	= $recurso;
  }else{
    $pdf1->descr_recurso 	= $o15_descr;
  }
  $pdf1->recurso 	    = $o15_codigo;
  $pdf1->orgao     	  = db_formatar($o40_orgao,'orgao');
  $pdf1->descr_orgao 	= $o40_descr;
  $pdf1->unidade 	    = db_formatar($o40_orgao,'orgao').db_formatar($o41_unidade,'orgao');
  $pdf1->descr_unidade 	= $o41_descr;
  if($rh05_mremun == 0 || $rh05_mremun == null){
    $pdf1->mremun    	= $f010;
  }else{
    $pdf1->mremun    	= $rh05_mremun;
  }
  $pdf1->mae	   	    = $z01_mae;
  $pdf1->ano          = $anofolha;
  $pdf1->mes          = $mesfolha;



  $sql_valPROV = $clsql->gerador_sql("r20", $anofolha, $mesfolha, $rh01_regist, "", " rh27_rubric, rh27_descr, #s#_quant, #s#_valor ", " rh01_regist ", " #s#_pd = 1");
  $sql_valDESC = $clsql->gerador_sql("r20", $anofolha, $mesfolha, $rh01_regist, "", " rh27_rubric, rh27_descr, #s#_quant, #s#_valor ", " rh01_regist ", " #s#_pd = 2");
  $result_valPROV = db_query($sql_valPROV);
  $result_valDESC = db_query($sql_valDESC);

  $pdf1->linhasproventos  = pg_numrows($result_valPROV);
  $pdf1->linhasdescontos  = pg_numrows($result_valDESC);
  $pdf1->resultproventos  = $result_valPROV;
  $pdf1->resultdescontos  = $result_valDESC;

  $pdf1->imprime();
}

$pdf1->objpdf->output();
?>
