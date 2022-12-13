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

require_once(modification("fpdf151/impcarne.php"));
require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_empempitem_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("classes/db_cgmalt_classe.php"));
require_once(modification("classes/db_pcforneconpad_classe.php"));


/*
 * Configurações GED
*/
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php"));
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php"));
require_once(modification("libs/exceptions/BusinessException.php"));

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if ( !empty($oGet->dtInicial) || !empty($oGet->dtFinal) ) {

    $sMsgErro  = "O parâmetro para utilização do GED (Gerenciador Eletrônico de Documentos) está ativado.<br><br>";
    $sMsgErro .= "Neste não é possível informar interválos de códigos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}


$clempparametro	    = new cl_empparametro;
$clempautitem       = new cl_empautitem;
$clcgmalt           = new cl_cgmalt;
$cldb_pcforneconpad = new cl_pcforneconpad;
$clempempitem       = new cl_empempitem;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$head3 = "CADASTRO DE CÓDIGOS";
//$head5 = "PERÍODO : ".$mes." / ".$ano;

$sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao ";
$sqlpref .= "  from db_config                                                     ";
$sqlpref .= " inner join cgm on cgm.z01_numcgm = db_config.numcgm                 ";
$sqlpref .=	"	where codigo = ".db_getsession("DB_instit");

$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);
$sNomePrefeitura = $nomeinst;
$sCnpjPrefeitura = $cgc;

$anousu = db_getsession("DB_anousu");
if(isset($e60_numemp) && $e60_numemp != ''){
  $dbwhere     = " e60_numemp = $e60_numemp ";
  $sql         = "select e60_anousu as anousu from empempenho where $dbwhere";
  $res_empenho = @db_query($sql);
  $numrows_empenho = @pg_numrows($res_empenho);
  if ($numrows_empenho != 0){
    db_fieldsmemory($res_empenho,0);
  }
} else if (isset($e60_codemp) && $e60_codemp !=''){
  $arr = split("/",$e60_codemp);
  if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
    $dbwhere_ano = " and e60_anousu = ".$arr[1];
    $anousu = $arr[1];
  }else{
    $dbwhere_ano = " and e60_anousu = ".db_getsession("DB_anousu");
  }
  $dbwhere = "e60_codemp='".$arr[0]."'$dbwhere_ano";

}else{
  if( isset($dtini_dia) ){
    $dbwhere = " e60_emiss >= '$dtini_ano-$dtini_mes-$dtini_dia'";

    if( isset($dtfim_dia) ){
      $dbwhere .= " and e60_emiss <= '$dtfim_ano-$dtfim_mes-$dtfim_dia'";
    }
  }

}

$sqlemp = "
	select empempenho.*,
	       cgm.* ,
	       o58_orgao,
	       o40_descr,
	       o58_unidade,
	       o41_descr,
	       o58_funcao,
	       o52_descr,
	       o58_subfuncao,
	       o53_descr,
	       o58_programa,
	       o54_descr,
	       o58_projativ,
	       o55_descr,
	       o58_coddot,
	       o41_cnpj,
	       o56_elemento as sintetico,
	       o56_descr as descr_sintetico,
	       o58_codigo,
     	       o15_descr,
	       e61_autori,
           pc50_descr,
	       fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural,
	       e41_descr,
         c58_descr,
         e56_orctiporec,
         e54_praent,
         e54_codout,
         e54_conpag

	from empempenho
	     left join pctipocompra	on pc50_codcom = e60_codcom
	     inner join orcdotacao 	on o58_coddot = e60_coddot
	                               and o58_instit = ".db_getsession("DB_instit")."
				       and o58_anousu = e60_anousu
	     inner join orcorgao   	on o58_orgao = o40_orgao
	                               and o40_anousu = $anousu
	     inner join orcunidade 	on o58_unidade = o41_unidade
	                               and o58_orgao = o41_orgao
	                               and o41_anousu = o58_anousu
	     inner join orcfuncao  	on o58_funcao = o52_funcao
	     inner join orcsubfuncao  	on o58_subfuncao = o53_subfuncao
	     inner join orcprograma  	on o58_programa = o54_programa
	                               and o54_anousu = o58_anousu
	     inner join orcprojativ  	on o58_projativ = o55_projativ
	                               and o55_anousu = o58_anousu
	     inner join orcelemento 	on o58_codele = o56_codele
	                               and o58_anousu = o56_anousu
	     inner join orctiporec  	on o58_codigo = o15_codigo
	     inner join cgm 		on z01_numcgm = e60_numcgm
       inner join concarpeculiar on concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar
	     left outer join empempaut	on e60_numemp = e61_numemp
             left join  empautoriza     on e61_autori = e54_autori
	     left join  empautidot      on e61_autori = e56_autori
	     left outer join emptipo  	on e60_codtipo= e41_codtipo
	where  $dbwhere
	";

$result = db_query($sqlemp);
//db_criatabela($result);exit;
if (pg_numrows($result)==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado !  ");
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'6');
//$pdf1->modelo = 6;
$pdf1->objpdf->SetTextColor(0,0,0);

//   $pdf1->imprime();

//$pdf1->objpdf->Output();

//exit;

//rotina que pega o numero de vias
//add campo e30_impobslicempenho
$sCampos      = "e30_nroviaemp,e30_numdec,e30_impobslicempenho,e30_dadosbancoempenho";
$sSqlEmpParam = $clempparametro->sql_query_file(db_getsession("DB_anousu"),$sCampos);
$result02     = $clempparametro->sql_record($sSqlEmpParam);
//echo $clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_nroviaemp");
if($clempparametro->numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado na empparametro!");
}

db_fieldsmemory($result02,0);

//recebido variavel
$pdf1->nvias              = $e30_nroviaemp;
$pdf1->casadec            = $e30_numdec;
$pdf1->dadosbancoemprenho = $e30_dadosbancoempenho;

//db_criatabela($result); exit;

for ($i = 0;$i < pg_numrows($result);$i++) {

  db_fieldsmemory($result,$i);

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

  $sSqlPacto  = " SELECT distinct pactoplano.* ";
  $sSqlPacto .= "   from empautitem ";
  $sSqlPacto .= "        inner join empautitempcprocitem       on empautitempcprocitem.e73_autori = empautitem.e55_autori";
  $sSqlPacto .= "                                             and empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
  $sSqlPacto .= "        inner join pcprocitem                 on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
  $sSqlPacto .= "        inner join solicitem                  on pc81_solicitem                  = pc11_codigo";
  $sSqlPacto .= "        inner join orctiporecconveniosolicita on pc11_numero                     = o78_solicita";
  $sSqlPacto .= "        inner join pactoplano                 on o78_pactoplano                  = o74_sequencial";
  $sSqlPacto .= "  where e55_autori = {$e61_autori}";
  $rsPacto    = db_query($sSqlPacto);

  $o74_descricao       = null;
  $o78_pactoplano      = null;
  if (@pg_num_rows($rsPacto) > 0) {

    $oPacto              = db_utils::fieldsMemory($rsPacto, 0);
    $o74_descricao       = $oPacto->o74_descricao;
    $o78_pactoplano      = $oPacto->o74_sequencial;
  }

  /**
   * Busca o processo
   */
  $oDaoEmpAutorizaProcesso  = db_utils::getDao("empautorizaprocesso");
  $sWhereBuscaProcessoAdmin = " e150_empautoriza = {$e61_autori}";
  $sSqlBuscaProcessoAdmin   = $oDaoEmpAutorizaProcesso->sql_query_file(null, "e150_numeroprocesso", null, $sWhereBuscaProcessoAdmin);
  $rsBuscaProcessoAdmin     = $oDaoEmpAutorizaProcesso->sql_record($sSqlBuscaProcessoAdmin);
  $sProcessoAdministrativo  = "";

  if ($rsBuscaProcessoAdmin && $oDaoEmpAutorizaProcesso->numrows > 0) {
    $sProcessoAdministrativo = db_utils::fieldsMemory($rsBuscaProcessoAdmin, 0)->e150_numeroprocesso;
  }

  $sqlitem  = " select distinct ";
  $sqlitem .= "	       case when e62_descr = '' then pc01_descrmater else pc01_descrmater||' \\n('||e62_descr||')' end as ";
  $sqlitem .= "        pc01_descrmater, ";
  $sqlitem .= "        e62_sequen, ";
  $sqlitem .= "        e62_numemp, ";
  $sqlitem .= "        e62_quant, ";
  $sqlitem .= "        e62_vltot, ";
  $sqlitem .= "        e62_vlrun, ";
  $sqlitem .= "        e62_codele, ";
  $sqlitem .= "        o56_elemento, ";
  $sqlitem .= "        o56_descr, ";
  $sqlitem .= "        rp.pc81_codproc, ";
  $sqlitem .= "        solrp.pc11_numero, ";
  $sqlitem .= "        solrp.pc11_codigo, ";
  $sqlitem .= "        case when pc10_solicitacaotipo = 5 then coalesce(trim(pcitemvalrp.pc23_obs), '') ";
  $sqlitem .= "             else  coalesce(trim(pcorcamval.pc23_obs), '') end as pc23_obs ";
  $sqlitem .= "   from empempitem ";
  $sqlitem .= "       inner join empempenho           on empempenho.e60_numemp           = empempitem.e62_numemp ";
  $sqlitem .= "       inner join pcmater              on pcmater.pc01_codmater           = empempitem.e62_item ";
  $sqlitem .= "       inner join orcelemento          on orcelemento.o56_codele          = empempitem.e62_codele ";
  $sqlitem .= "                                      and orcelemento.o56_anousu          = empempenho.e60_anousu ";
  $sqlitem .= "       left join empempaut             on empempaut.e61_numemp            = empempenho.e60_numemp ";
  $sqlitem .= "       left join empautitem            on empautitem.e55_autori           = empempaut.e61_autori ";
  $sqlitem .= "                                      and e62_sequen = e55_sequen ";

  // verificação de empenhos de registro de preco

  $sqlitem .= "       left join empautitempcprocitem        on empautitempcprocitem.e73_autori      = empautitem.e55_autori ";
  $sqlitem .= "                                            and empautitempcprocitem.e73_sequen      = empautitem.e55_sequen ";
  $sqlitem .= "       left join pcprocitem rp               on rp.pc81_codprocitem                  = empautitempcprocitem.e73_pcprocitem ";
  $sqlitem .= "       left join solicitem solrp             on solrp.pc11_codigo                    = rp.pc81_solicitem ";
  $sqlitem .= "       left join solicita                    on solicita.pc10_numero                 = solrp.pc11_numero ";
  $sqlitem .= "       left join solicitemvinculo            on solicitemvinculo.pc55_solicitemfilho = solrp.pc11_codigo ";
  $sqlitem .= "       left join solicitem compilacao        on solicitemvinculo.pc55_solicitempai   = compilacao.pc11_codigo ";
  $sqlitem .= "       left join pcprocitem proccompilacao   on pc55_solicitempai                    = proccompilacao.pc81_solicitem ";
  $sqlitem .= "       left join liclicitem licitarp         on proccompilacao.pc81_codprocitem      = licitarp.l21_codpcprocitem ";
  $sqlitem .= "       left join pcorcamitemlic pcitemrp     on licitarp.l21_codigo                  = pcitemrp.pc26_liclicitem ";
  $sqlitem .= "       left join pcorcamjulg julgrp          on pcitemrp.pc26_orcamitem              = julgrp.pc24_orcamitem ";
  $sqlitem .= "                                            and julgrp.pc24_pontuacao                = 1 ";
  $sqlitem .= "       left join pcorcamval pcitemvalrp      on julgrp.pc24_orcamitem                = pcitemvalrp.pc23_orcamitem ";
  $sqlitem .= "                                            and julgrp.pc24_orcamforne               = pcitemvalrp.pc23_orcamforne ";

  //verficaao de empenhos gerados a partir de licitacao normal.

  $sqlitem .= "       left join empautitempcprocitem  pcprocitemaut  on pcprocitemaut.e73_autori        = empautitem.e55_autori ";
  $sqlitem .= "                                                     and pcprocitemaut.e73_sequen        = empautitem.e55_sequen ";
  $sqlitem .= "       left join pcprocitem                           on pcprocitem.pc81_codprocitem     = pcprocitemaut.e73_pcprocitem ";
  $sqlitem .= "       left join solicitem                            on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem ";
  $sqlitem .= "       left join liclicitem                           on liclicitem.l21_codpcprocitem    = pcprocitemaut.e73_pcprocitem ";
  $sqlitem .= "       left join pcorcamitemlic                       on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo ";
  $sqlitem .= "       left join pcorcamjulg                          on pcorcamjulg.pc24_orcamitem      = pcorcamitemlic.pc26_orcamitem ";
  $sqlitem .= "                                                     and pcorcamjulg.pc24_pontuacao      = 1 ";
  $sqlitem .= "       left join pcorcamval                           on pcorcamval.pc23_orcamitem       = pcorcamjulg.pc24_orcamitem ";
  $sqlitem .= "                                                     and pcorcamval.pc23_orcamforne      = pcorcamjulg.pc24_orcamforne ";

  $sqlitem .= "  where e62_numemp = '{$e60_numemp}' ";
  $sqlitem .= " order by e62_sequen, o56_elemento,pc01_descrmater";


  $resultitem = db_query($sqlitem);
  $result_cgmalt=$clcgmalt->sql_record($clcgmalt->sql_query_file(null,"z05_numcgm as z01_numcgm,z05_nome as z01_nome,z05_telef as z01_telef,z05_ender as z01_ender,z05_numero as z01_numero,z05_munic as z01_munic,z05_cgccpf as z01_cgccpf,z05_cep as z01_cep"," abs(z05_data_alt - date '$e60_emiss') asc, z05_sequencia desc limit 1","z05_numcgm = $z01_numcgm and z05_data_alt > '$e60_emiss' "));

  if ($clcgmalt->numrows>0) {
    db_fieldsmemory($result_cgmalt,0);
  }

  /**
   * Verificamos o cnpj da unidade. caso diferente de null, e diferente do xcnpj da instituição,
   * mostramso a descrição e o cnpj da unidade
   */
  $nomeinst = $sNomePrefeitura;
  $cgc = $sCnpjPrefeitura;
   if ($o41_cnpj != "" && $o41_cnpj!= $cgc) {

     $nomeinst = $o41_descr;
     $cgc      = $o41_cnpj;

   }
   $pdf1->emptipo              = $e41_descr;
   $pdf1->prefeitura           = $nomeinst;
   $pdf1->enderpref            = $ender.", ".$numero;
   $pdf1->cgcpref              = $cgc;
   $pdf1->municpref            = $munic;
   $pdf1->telefpref            = $telef;
   $pdf1->emailpref            = $email;

   $pdf1->inscricaoestadualinstituicao    = '';
   if ($db21_usasisagua == 't') {
     $pdf1->inscricaoestadualinstituicao    = "- Inscrição Estadual: ".$inscricaoestadualinstituicao;
   }

   $pdf1->numcgm               = $z01_numcgm;
   $pdf1->nome                 = $z01_nome;
   $pdf1->telefone             = $z01_telef;
   $pdf1->ender                = $z01_ender.', '.$z01_numero;
   $pdf1->munic                = $z01_munic;
   $pdf1->cnpj                 = $z01_cgccpf;
   $pdf1->cep                  = $z01_cep;
   $pdf1->ufFornecedor         = $z01_uf;
   $pdf1->prazo_entrega        = $e54_praent;
   $pdf1->condicao_pagamento   = $e54_conpag;
   $pdf1->outras_condicoes     = $e54_codout;

   $pdf1->iBancoFornecedor     = $oPcFornecOnPad->pc63_banco;
   $pdf1->iAgenciaForncedor    = $oPcFornecOnPad->pc63_agencia."-".$oPcFornecOnPad->pc63_agencia_dig;
   $pdf1->iContaForncedor      = $oPcFornecOnPad->pc63_conta."-".$oPcFornecOnPad->pc63_conta_dig;

   $pdf1->dotacao              = $estrutural;
   $pdf1->num_licitacao        = $e60_numerol;
   $pdf1->cod_concarpeculiar   = $e60_concarpeculiar;
   $pdf1->descr_concarpeculiar = substr($c58_descr,0,34);
   $pdf1->logo                 = $logo;
   $pdf1->SdescrPacto          = $o74_descricao;
   $pdf1->iPlanoPacto          = $o78_pactoplano;
   $pdf1->contrapartida        = $e56_orctiporec;
   $pdf1->observacaoitem       = "pc23_obs";
   $pdf1->Snumeroproc          = "pc81_codproc";
   $pdf1->Snumero              = "pc11_numero";

   $pdf1->processo_administrativo = $sProcessoAdministrativo;

   //Zera as variáveis
   $pdf1->resumo = "";
   $resumo_lic   = "";

   $result_licita = $clempautitem->sql_record($clempautitem->sql_query_lic(null,null,"distinct l20_edital, l20_anousu, l20_objeto, l03_descr",null,"e55_autori = $e61_autori "));
   if ($clempautitem->numrows>0){
     db_fieldsmemory($result_licita,0);

     $pdf1->edital_licitacao     = $l20_edital;
     $pdf1->ano_licitacao        = $l20_anousu;
     $resumo_lic                 = $l20_objeto;

   } else {

     $l03_descr                  = '';
     $l20_objeto                 = '';
     $pdf1->edital_licitacao     = '';
     $pdf1->ano_licitacao        = '';

   }


   if (isset($resumo_lic)&&$resumo_lic!=""){
     if ($e30_impobslicempenho=='t') {
       $pdf1->resumo = $resumo_lic."\n".$e60_resumo;
     } else {
       $pdf1->resumo = $e60_resumo;
     }
   } else {
     $pdf1->resumo = $e60_resumo;
   }


   $Sresumo = $pdf1->resumo;
   $vresumo = split("\n",$Sresumo);

   if (count($vresumo) > 1){
     $Sresumo   = "";
     $separador = "";
     for ($x = 0; $x < count($vresumo); $x++){
       if (trim($vresumo[$x]) != ""){
         $separador = ". ";
         $Sresumo  .= $vresumo[$x].$separador;
       }
     }
   }

   if (count($vresumo) == 0){
     $Sresumo = str_replace("\n",". ",$Sresumo);
   }

   $Sresumo = str_replace("\r","",$Sresumo);

	// $pdf1->resumo = substr(str_replace("\n","-", $Sresumo ),0,750);
	 $pdf1->resumo = substr($Sresumo,0,730);

  // echo 'Resumo --> '.$pdf1->resumo;exit;

   if (isset($l03_descr)&&($l03_descr!="")){
     $pdf1->descr_licitacao = $l03_descr;
   } else {
     $sqllic = "select l03_descr from cflicita where l03_codcom=$e60_codcom and l03_tipo='$e60_tipol'";
     $rpc    = db_query($sqllic);

     //  system("echo '".$sqllic."\n' >> tmp/logsql.txt");

     if (pg_numrows($rpc) > 0 ){
       $pdf1->descr_licitacao = pg_result($rpc,0,0);
     } else {
       $pdf1->descr_licitacao = $pc50_descr;

     }


   }
   //$pdf1->descr_licitacao  = $pc50_descr;
   $pdf1->coddot           = $o58_coddot;
   $pdf1->destino          = $e60_destin;
   //$pdf1->resumo           = $e60_resumo;
   $pdf1->licitacao        = $e60_codtipo;
   $pdf1->recorddositens   = $resultitem;
   $pdf1->linhasdositens   = pg_numrows($resultitem);
   $pdf1->quantitem        = "e62_quant";
   $pdf1->valoritem        = "e62_vltot";
   $pdf1->valor            = "e62_vlrun";
   $pdf1->descricaoitem    = "pc01_descrmater";

   $pdf1->orcado	   = $e60_vlrorc;
   $pdf1->saldo_ant        = $e60_salant;
   $pdf1->empenhado        = $e60_vlremp;
   $pdf1->numemp           = $e60_numemp;
   $pdf1->codemp           = $e60_codemp;
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
   $pdf1->banco            = null;
   $pdf1->agencia          = null;
   $pdf1->conta            = null;

   $pdf1->fax              = $z01_fax;

   $sql  = "select c61_codcon
              from conplanoreduz
                   inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu
                   inner join consistema on c52_codsis = c60_codsis
             where c61_instit   = ".db_getsession("DB_instit")."
               and c61_anousu   =".db_getsession("DB_anousu")."
               and c61_codigo   = $o58_codigo
               and c52_descrred = 'F' ";
   $result_conta = db_query($sql);
   //system("echo '".$sql."\n' >> tmp/logsql.txt");

	 //die ($sql);
   if ($result_conta != false && (pg_numrows($result_conta) == 1)) {

     db_fieldsmemory($result_conta,0);
     $sqlconta     = "select * from conplanoconta where c63_codcon = $c61_codcon and c63_anousu = ".db_getsession("DB_anousu");
     $result_conta = db_query($sqlconta);

     //   system("echo '".$sqlconta."\n' >> tmp/logsql.txt");

     if (pg_result($result_conta,0) == 1) {

       db_fieldsmemory($result_conta,0);
       $pdf1->banco            = $c63_banco;
       $pdf1->agencia          = $c63_agencia;
       $pdf1->conta            = $c63_conta;
     }
   }

   $pdf1->emissao          = db_formatar($e60_emiss,'d');
   $pdf1->texto            = "";

   $pdf1->imprime();
}
//include(modification("fpdf151/geraarquivo.php"));


if ($oConfiguracaoGed->utilizaGED()) {

  try {

    $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::EMPENHO;

    $oGerenciador = new GerenciadorEletronicoDocumento();
    $oGerenciador->setLocalizacaoOrigem("tmp/");
    $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$e60_numemp}.pdf");

    $oStdDadosGED        = new stdClass();
    $oStdDadosGED->nome  = $sTipoDocumento;
    $oStdDadosGED->tipo  = "NUMERO";
    $oStdDadosGED->valor = $e60_numemp;
    $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$e60_numemp}.pdf");
    $oGerenciador->moverArquivo(array($oStdDadosGED));


  } catch (Exception $eErro) {

    db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
  }
} else {

  $pdf1->objpdf->Output();
}
?>
