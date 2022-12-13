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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_matordem_classe.php"));
require_once(modification("classes/db_matordemitem_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));


/*
 * Configurações GED
*/
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php"));
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php"));
require_once(modification("libs/exceptions/BusinessException.php"));

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if (empty($oGet->m51_codordem_ini) && !empty($cods)) {
    $oGet->m51_codordem_ini = $cods;
  }

  if (empty($oGet->m51_codordem_fim) && !empty($cods)) {
    $oGet->m51_codordem_fim = $cods;
  }

  if ($oGet->m51_codordem_ini != $oGet->m51_codordem_fim) {

    $sMsgErro  = "O parâmetro para utilização do GED (Gerenciador Eletrônico de Documentos) está ativado.<br><br>";
    $sMsgErro .= "Neste não é possível informar interválos de códigos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}

$clmatordem     = new cl_matordem;
$clmatordemitem = new cl_matordemitem;
$clempparametro = new cl_empparametro;
$clpcparam      = new cl_pcparam;
$oDaoDbDepart   = new cl_db_depart;

$sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao ";
$sqlpref .= "  from db_config                                                     ";
$sqlpref .= " inner join cgm on cgm.z01_numcgm = db_config.numcgm                 ";
$sqlpref .=	"	where codigo = ".db_getsession("DB_instit");

$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);
$emailPref = $email;

$rsPcParam = $clpcparam->sql_record($clpcparam->sql_query(db_getsession("DB_instit")));
if ($clpcparam->numrows > 0){
  $oParam = db_utils::fieldsMemory($rsPcParam,0);
}else{
  db_redireciona("db_erros.php?fechar=true&db_erro=Não há parametros configurados para essa instituição.");
}
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$txt_where='1=1';

if(isset($m51_codordem_ini) && $m51_codordem_ini!="" && isset($m51_codordem_fim) && $m51_codordem_fim!=""){
  $txt_where .= " and  m51_codordem between $m51_codordem_ini and  $m51_codordem_fim";
}else if(isset($m51_codordem_ini) && $m51_codordem_ini!=""){
  $txt_where .= " and  m51_codordem>$m51_codordem_ini";
}else  if(isset($m51_codordem_fim) && $m51_codordem_fim!=""){
  $txt_where .= " and  m51_codordem<$m51_codordem_fim";
}else if (isset($cods)&&$cods!=""){
  $txt_where.=" and m51_codordem in ($cods) ";
}



$result = $clmatordem->sql_record($clmatordem->sql_query(null,"*","","$txt_where"));
$num=$clmatordem->numrows;


$pdf = new scpdf();
$pdf->Open();

$pdf1 = new db_impcarne($pdf, $oParam->pc30_modeloordemcompra);


//$pdf1->modelo = 10;
//$pdf1->nvias= 2 ;
$pdf1->objpdf->SetTextColor(0,0,0);

$flag_imprime = true;

for($i = 0;$i < $num;$i++){

  db_fieldsmemory($result,$i);

  $sSqlLicitacao  = "select e60_numerol, l03_descr ";
  $sSqlLicitacao .= " from matordemitem ";
  $sSqlLicitacao .= "      inner join empempitem on (m52_numemp, m52_sequen) = (e62_numemp, e62_sequen) ";
  $sSqlLicitacao .= "      inner join empempenho on e62_numemp = e60_numemp";
  $sSqlLicitacao .= "      inner join cflicita   on l03_tipo = e60_tipol";
  $sSqlLicitacao .= " where m52_codordem = {$m51_codordem} limit 1";

  $oLicitacao  = null;
  $rsLicitacao = db_query($sSqlLicitacao);
  if ($rsLicitacao && pg_num_rows($rsLicitacao) > 0) {
    $oLicitacao = db_utils::fieldsMemory($rsLicitacao, 0);
  }

  $sSqlDeptoOrigem = $oDaoDbDepart->sql_query_file(null, "*", null, "coddepto = {$m51_deptoorigem}");

  $rsOrigem        = $oDaoDbDepart->sql_record($sSqlDeptoOrigem);
  $sOrigem = db_utils::fieldsMemory($rsOrigem, 0)->descrdepto;
  $iOrigem = db_utils::fieldsMemory($rsOrigem, 0)->coddepto;

  $sqlItem = 	$clmatordemitem->sql_query_emiteordem(null,"distinct m52_codordem,
                                                         m52_sequen,
                                                         m52_quant,
                                                         m52_numemp,
                                                         m52_vlruni,
                                                         m52_valor,
                                                         pcmater.pc01_descrmater,
                                                         pc01_codmater,
                                                         e62_descr,
                                                         empempenho.e60_codemp,
                                                         empempenho.e60_anousu,
                                                         e62_vltot,
                                                         e62_quant,
                                                         e54_conpag,
                                                         e54_destin,
                                                         case when rp.pc81_codproc is not null then rp.pc81_codproc
                                                              else  pcprocitem.pc81_codproc end as pc81_codproc,
                                                         case when solrp.pc11_numero is not null then solrp.pc11_numero
                                                              else  solicitem.pc11_numero end as pc11_numero,
                                                         case when pc10_solicitacaotipo = 5 then coalesce(trim(pcitemvalrp.pc23_obs), '')
                                                              else  coalesce(trim(pcorcamval.pc23_obs), '') end as pc23_obs,
	                                                       pc50_descr,
                                                         coalesce(matunid.m61_descr, matunidautorizacao.m61_descr) as unidade
                                                         ",
                                                    "m52_numemp, m52_sequen",
                                                    "m52_codordem = $m51_codordem");
  $resultitem = $clmatordemitem->sql_record($sqlItem);
  // db_criatabela($resultitem); exit;


  $numrows=$clmatordemitem->numrows;
  if ($numrows == 0){
    $flag_imprime = false;
    continue;
  } else {
    $flag_imprime = true;
  }

  $datahj=date("Y-m-d",db_getsession("DB_datausu"));

  $pdf1->prefeitura   = $nomeinst;
  $pdf1->enderpref    = trim($ender).",".$numero;
  $pdf1->municpref    = $munic;
  $pdf1->uf           = $uf;
  $pdf1->telefpref    = $telef;
  $pdf1->logo		   = $logo;
  $pdf1->emailpref    = $emailPref;

  $pdf1->inscricaoestadualinstituicao    = '';
  if ($db21_usasisagua == 't') {
    $pdf1->inscricaoestadualinstituicao    = "Inscrição Estadual: ".$inscricaoestadualinstituicao;
  }

  $pdf1->licitacao    = $oLicitacao;
  $pdf1->numordem     = $m51_codordem;
  $pdf1->dataordem    = $m51_data;
  $pdf1->coddepto     = $m51_depto;
  $pdf1->descrdepto   = $descrdepto;
  $pdf1->numcgm       = $m51_numcgm;
  $pdf1->nome         = $z01_nome;
  $pdf1->email        = $z01_email;
  $pdf1->cnpj         = $z01_cgccpf;
  $pdf1->cgc          = $cgc;
  $pdf1->url          = $url;
  $pdf1->ender        = $z01_ender;
  $pdf1->munic        = $z01_munic;
  $pdf1->bairro       = $z01_bairro;
  $pdf1->cep          = $z01_cep;
  $pdf1->ufFornecedor = $z01_uf;
  $pdf1->numero       = $z01_numero;
  $pdf1->compl        = $z01_compl;
  $pdf1->contato      = $z01_telcon;
  $pdf1->telef_cont   = $z01_telef;
  $pdf1->telef_fax		= $z01_fax;
  $pdf1->recorddositens = $resultitem;
  $pdf1->linhasdositens = $numrows;
  $pdf1->emissao = $datahj;
  // $pdf1->item	      = 'm52_sequen';
  $pdf1->obs            = $m51_obs;
  $pdf1->sTipoCompra    = 'pc50_descr'; //campo do pg_result
  $pdf1->empempenho      = 'e60_codemp';
  $pdf1->anousuemp       = 'e60_anousu';
  $pdf1->quantitem      = 'm52_quant';
  $pdf1->condpag        = 'e54_conpag';
  $pdf1->destino        = 'e54_destin';
  $pdf1->sOrigem        = $iOrigem . " - " .$sOrigem;
  //$pdf1->iOrigem        = $iOrigem;
  //   $pdf1->quantitememp   = 'e62_quant';
  $anousu=db_getsession("DB_anousu");
  $result_numdec=$clempparametro->sql_record($clempparametro->sql_query_file($anousu));

  $e30_numdec = 4 ;
  if ($clempparametro->numrows>0){
    db_fieldsmemory($result_numdec,0);
  }

  $pdf1->numdec         = $e30_numdec;
  $pdf1->valoritem      = 'm52_valor';
  $pdf1->vlrunitem      = 'm52_vlruni';
  $pdf1->descricaoitem  = 'pc01_descrmater';
  $pdf1->codmater       = 'pc01_codmater';
  $pdf1->observacaoitem = 'e62_descr';
  $pdf1->depto          = $m51_depto;
  $pdf1->prazoent       = $m51_prazoent;

  $pdf1->Snumeroproc    = "pc81_codproc";
  $pdf1->Snumero        = "pc11_numero";
  $pdf1->obs_ordcom_orcamval = "pc23_obs";

  $pdf1->imprime();

}



if ($flag_imprime == true){

  if ($oConfiguracaoGed->utilizaGED()) {

    try {

      if (!empty($cods)) {
        $m51_codordem_ini = $cods;
      }

      $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::ORDEM_COMPRA;

      $oGerenciador = new GerenciadorEletronicoDocumento();
      $oGerenciador->setLocalizacaoOrigem("tmp/");
      $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$m51_codordem_ini}.pdf");

      $oStdDadosGED        = new stdClass();
      $oStdDadosGED->nome  = $sTipoDocumento;
      $oStdDadosGED->tipo  = "NUMERO";
      $oStdDadosGED->valor = $m51_codordem_ini;
      $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$m51_codordem_ini}.pdf");
      $oGerenciador->moverArquivo(array($oStdDadosGED));


    } catch (Exception $eErro) {

      db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
    }
  } else {

    $pdf1->objpdf->Output();
  }


} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Verifique a(s) ordem(ns) selecionada(s).");
}
