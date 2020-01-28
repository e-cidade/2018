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
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_empanulado_classe.php");
require_once("classes/db_empanuladoele_classe.php");
require_once("classes/db_pcforneconpad_classe.php");
require_once("libs/db_liborcamento.php");

$clempparametro	    = new cl_empparametro;
$clempanulado	      = new cl_empanulado;
$cldb_pcforneconpad = new cl_pcforneconpad;
$clempanuladoele    = new cl_empanuladoele;
$atual              = 0;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$where1 = '';
if(isset($e94_codanu)) {

  $re = db_query("select distinct e94_numemp from empanulado where e94_codanu = $e94_codanu");
  if(pg_numrows($re) == 0 ){
    db_redireciona('db_erros.php?fechar=true&db_erro=Anulação n'.chr(176).' '.$e94_codanu.' não encontrada. Verifique!');
  }
  db_fieldsmemory($re,0);
  $e60_numemp = $e94_numemp;
  $where1 = " where e94_codanu = $e94_codanu";

} elseif(isset($e60_codemp)) {

  $arr = split("/",$e60_codemp);
  if (count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ) {
	  $ano = " and e60_anousu = ".$arr[1];
  } else {
	  $ano = " and e60_anousu = ".db_getsession("DB_anousu");
  }

  $where  = "where e60_codemp =  '".$arr[0]."' $ano";
  $where .= " and e60_instit = " . db_getsession('DB_instit');

  $re = db_query("select distinct e94_numemp  from empanulado inner join empempenho on e94_numemp = e60_numemp $where");
  if(!$re || pg_num_rows($re) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Anulação n'.chr(176).' '.$e94_codanu.' não encontrada. Verifique!');
  }
  db_fieldsmemory($re,0);
  $e60_numemp = $e94_numemp;
  $where1 = " where e94_numemp = $e94_numemp";

} else {
  $where1 = " where e94_numemp = $e60_numemp";
}

$dbwhere = " e60_numemp = ".$e60_numemp;
$head3 = "CADASTRO DE CÓDIGOS";

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

$sqlemp  = "select empempenho.*,                                                              ";
$sqlemp .= "       cgm.* ,                                                                    ";
$sqlemp .= "       o58_orgao,                                                                 ";
$sqlemp .= "       o40_descr,                                                                 ";
$sqlemp .= "       o58_unidade,                                                               ";
$sqlemp .= "       o41_descr,                                                                 ";
$sqlemp .= "       o58_funcao,                                                                ";
$sqlemp .= "       o52_descr,                                                                 ";
$sqlemp .= "       o58_subfuncao,                                                             ";
$sqlemp .= "       o53_descr,                                                                 ";
$sqlemp .= "       o58_programa,                                                              ";
$sqlemp .= "       o54_descr,                                                                 ";
$sqlemp .= "       o58_projativ,                                                              ";
$sqlemp .= "       o55_descr,                                                                 ";
$sqlemp .= "       o58_coddot,                                                                ";
$sqlemp .= "       o58_anousu,                                                                ";
$sqlemp .= "       o56_elemento as sintetico,                                                 ";
$sqlemp .= "       o56_descr as descr_sintetico,                                              ";
$sqlemp .= "       o58_codigo,                                                                ";
$sqlemp .= "       o15_descr,                                                                 ";
$sqlemp .= "       e61_autori,                                                                ";
$sqlemp .= "       l03_descr,                                                                 ";
$sqlemp .= "       fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural                  ";
$sqlemp .= "  from empempenho                                                                 ";
$sqlemp .= "       left join cflicita	     on l03_tipo         = e60_tipol                  ";
$sqlemp .= "                               and l03_instit       = e60_instit                  ";
$sqlemp .= "       left join orcdotacao    	 on o58_coddot       = e60_coddot                 ";
$sqlemp .= "                                and o58_instit       = ".db_getsession("DB_instit");
$sqlemp .= "	                            and o58_anousu       = e60_anousu                 ";
$sqlemp .= "       inner join orcorgao   	 on o58_orgao        = o40_orgao                  ";
$sqlemp .= "                                and o40_anousu       = e60_anousu                 ";
$sqlemp .= "       inner join orcunidade 	 on o58_unidade      = o41_unidade                ";
$sqlemp .= "                                and o58_orgao        = o41_orgao                  ";
$sqlemp .= "                                and o41_anousu       = o58_anousu                 ";
$sqlemp .= "       inner join orcfuncao   	 on o58_funcao       = o52_funcao                 ";
$sqlemp .= "       inner join orcsubfuncao   on o58_subfuncao    = o53_subfuncao              ";
$sqlemp .= "       inner join orcprograma    on o58_programa     = o54_programa               ";
$sqlemp .= "                                and o54_anousu       = o58_anousu                 ";
$sqlemp .= "       inner join orcprojativ  	 on o58_projativ     = o55_projativ               ";
$sqlemp .= "                                and o55_anousu       = o58_anousu                 ";
$sqlemp .= "       inner join orcelemento a	 on o58_codele       = o56_codele                 ";
$sqlemp .= "                                and o58_anousu       = o56_anousu                 ";
$sqlemp .= "       inner join orctiporec  	 on o58_codigo       = o15_codigo                 ";
$sqlemp .= "       inner join cgm 		     on z01_numcgm       = e60_numcgm                 ";
$sqlemp .= "       left join empempaut	     on e60_numemp       = e61_numemp                 ";
$sqlemp .= "	where  $dbwhere ";
$result = db_query($sqlemp);
if($result==false || pg_numrows($result) == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Anulação n'.chr(176).' '.$e94_codanu.' não encontrada. Verifique!');
}
db_fieldsmemory($result,0);

/**
 *
 * Busca dados bancários
 */
$sSqlPcFornecOnPad  = $cldb_pcforneconpad->sql_query(null, "*", null, "pc63_numcgm = {$e60_numcgm}");
$rsSqlPcFornecOnPad = $cldb_pcforneconpad->sql_record($sSqlPcFornecOnPad);

if (!$rsSqlPcFornecOnPad == false && $cldb_pcforneconpad->numrows > 0) {
  $oPcFornecOnPad     = db_utils::fieldsMemory($rsSqlPcFornecOnPad,0);
} else {

  $oPcFornecOnPad = new stdClass();
  $oPcFornecOnPad->pc63_banco       = '';
  $oPcFornecOnPad->pc63_agencia     = '';
  $oPcFornecOnPad->pc63_agencia_dig = '';
  $oPcFornecOnPad->pc63_conta       = '';
  $oPcFornecOnPad->pc63_conta_dig   = '';
}

$res_dot = db_dotacaosaldo(8,2,2,true," o58_coddot = $o58_coddot and o58_anousu = $o58_anousu");
if (pg_numrows($res_dot)>0){
  db_fieldsmemory($res_dot,0);
}


$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'12');
$pdf1->objpdf->SetTextColor(0,0,0);

//rotina que pega o numero de vias
$result02 = db_query("select * from empanulado $where1 ");
if($clempparametro->numrows>0){
  db_fieldsmemory($result02,0);
}

$pdf1->nvias= 1;
$nValorTotalAnulado = 0;
for($i = 0;$i < pg_numrows($result02);$i++){
   db_fieldsmemory($result02,$i);

   $sqlitens  = "select distinct *                                                                           ";
   $sqlitens .= "     from empanuladoele                                                                     ";
   $sqlitens .= "                inner join empanulado      on e94_codanu            = e95_codanu            ";
   $sqlitens .= "                inner join empanuladoitem  on e37_empanulado        = e94_codanu            ";
   $sqlitens .= "                inner join empempitem      on e62_sequencial        = e37_empempitem        ";
   $sqlitens .= "                                          and empempitem.e62_numemp = empanulado.e94_numemp ";
   $sqlitens .= "                inner join empempenho      on e60_numemp            = e62_numemp            ";
   $sqlitens .= "       		 inner join orcelemento     on o56_codele            = e62_codele            ";
   $sqlitens .= "       		      					   and o56_anousu            = e60_anousu            ";
   $sqlitens .= "                inner join pcmater         on pc01_codmater         = e62_item              ";
   $sqlitens .= " 	where e95_codanu = $e94_codanu ";
   $resultitem = db_query($sqlitens);

   $nValorTotalAnulado     += $e94_valor;
   $pdf1->notaanulacao      = $e94_codanu;
   $pdf1->prefeitura        = $nomeinst;
   $pdf1->enderpref         = trim($ender).",".$numero;
   $pdf1->municpref         = $munic;
   $pdf1->telefpref         = $telef;
   $pdf1->emailpref         = $email;
   $pdf1->numcgm            = $z01_numcgm;
   $pdf1->nome              = $z01_nome;
   $pdf1->ender             = $z01_ender;
   $pdf1->munic             = $z01_munic;
   $pdf1->dotacao           = $estrutural;
   $pdf1->descr_licitacao   = $l03_descr;
   $pdf1->coddot            = $o58_coddot;
   $pdf1->destino           = $e60_destin;
   $pdf1->logo              = $logo;
   $pdf1->valorTotalAnulado = $nValorTotalAnulado;

   $e60_resumo = str_replace("\n",'   -   ',$e94_motivo);
   $e60_resumo = str_replace("\r",'',$e94_motivo);
   //$e60_resumo = @iconv("UTF-8","ISO-8859-1",$e60_resumo);
   $e60_resumo = mb_convert_encoding($e60_resumo, "ISO-8859-1", mb_detect_encoding($e60_resumo, "UTF-8, ISO-8859-1, ISO-8859-15", true));

   $pdf1->resumo           = $e60_resumo;
   $pdf1->licitacao        = $e60_codtipo;
   $pdf1->recorddositens   = $resultitem;
   $pdf1->linhasdositens   = pg_numrows($resultitem);
// $pdf1->quantitem        = "e62_quant";
   $pdf1->valoritem        = "e95_valor";
// $pdf1->descricaoitem    = "pc01_descrmater";

   $pdf1->orcado	         = $e60_vlrorc;
   $pdf1->saldo_ant        = @$saldo_anterior;
   $pdf1->saldo_atu        = $atual;
   $pdf1->empenhado        = $e60_vlremp;
   $pdf1->anulado          = $e94_valor;
   $pdf1->numemp           = $e60_numemp;
   $pdf1->codemp           = $e60_codemp;
   $pdf1->anousu           = $e60_anousu;
   $pdf1->numaut           = $e61_autori;
   $pdf1->orgao            = $o58_orgao;
   $pdf1->descr_orgao      = $o40_descr;
   $pdf1->unidade          = $o58_unidade;
   $pdf1->descr_unidade    = $o41_descr;
   $pdf1->funcao           = $o58_funcao;
   $pdf1->descr_funcao     = $o52_descr;
   $pdf1->subfuncao        = $o58_subfuncao;
   $pdf1->descr_subfuncao  = $o53_descr;
   $pdf1->programa         = $o58_programa;
   $pdf1->descr_programa   = $o54_descr;
   $pdf1->projativ         = $o58_projativ;
   $pdf1->descr_projativ   = $o55_descr;
   $pdf1->analitico        = "o56_elemento";
   $pdf1->descr_analitico  = "o56_descr";
   $pdf1->sintetico        = $sintetico;
   $pdf1->descr_sintetico  = $descr_sintetico;
   $pdf1->recurso          = $o58_codigo;
   $pdf1->descr_recurso    = $o15_descr;
   $pdf1->emissao          = db_formatar($e94_data,'d');
   $pdf1->texto		         = db_getsession("DB_login").'  -  '.date("d-m-Y",db_getsession("DB_datausu")).'    '.db_hora(db_getsession("DB_datausu"));
   $pdf1->cnpj             = $z01_cgccpf;
   $pdf1->cep              = $z01_cep;

   /**
    * Dados Bancários
    */
   $pdf1->iBancoFornecedor     = $oPcFornecOnPad->pc63_banco;
   $pdf1->iAgenciaForncedor    = $oPcFornecOnPad->pc63_agencia."-".$oPcFornecOnPad->pc63_agencia_dig;
   $pdf1->iContaForncedor      = $oPcFornecOnPad->pc63_conta."-".$oPcFornecOnPad->pc63_conta_dig;


   $pdf1->imprime();
}
$pdf1->objpdf->Output();
