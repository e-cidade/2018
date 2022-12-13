<?php
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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_solicitem_classe.php");
require_once("classes/db_pcdotac_classe.php");
require_once("classes/db_pcsugforn_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_orcreservasol_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_empparametro_classe.php");

/*
 * Configurações GED
*/
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php");
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php");
require_once ("libs/exceptions/BusinessException.php");

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if ( !empty($oGet->pc80_data_inicial) || !empty($oGet->pc80_data_final) ||
        $oGet->pc80_codproc_inicial != $oGet->pc80_codproc_final) {

    $sMsgErro  = "O parâmetro para utilização do GED (Gerenciador Eletrônico de Documentos) está ativado.<br><br>";
    $sMsgErro .= "Neste não é possível informar interválos de códigos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}

$oDaoPcProc       = db_utils::getDao("pcproc");
$oDaoEmparametro  = db_utils::getDao("empparametro");
$oDaoPcProcItem   = db_utils::getDao("pcprocitem");
$oDaoDbDepartOrg  = db_utils::getDao("db_departorg");
$oDaoSolicitem    = db_utils::getDao("solicitem");
$classinatura     = new cl_assinatura();
$sqlpref    = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

$iNumeroViasDocumento              = 1;
$iNumeroCasasDecimaisValorUnitario = 2;

$sSqlParametroEmpenho = $oDaoEmparametro->sql_query_file(db_getsession("DB_anousu"), "e30_nroviaaut,e30_numdec");
$rsParametrosEmpenho  = $oDaoEmparametro->sql_record($sSqlParametroEmpenho);
if ($oDaoEmparametro->numrows > 0) {

  $oParametrosEmpenho                = db_utils::fieldsMemory($rsParametrosEmpenho, 0);
  $iNumeroCasasDecimaisValorUnitario = $oParametrosEmpenho->e30_numdec;
  $iNumeroViasDocumento              = $oParametrosEmpenho->e30_nroviaaut;
  unset($oParametrosEmpenho);
}

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet = db_utils::postMemory($_GET);

$aWhereProcessoCompras = array();
if (!empty($oGet->pc80_codproc_inicial)) {
  $aWhereProcessoCompras[] = " pc80_codproc >= {$oGet->pc80_codproc_inicial} ";
}
if (!empty($oGet->pc80_codproc_final)) {
  $aWhereProcessoCompras[] = " pc80_codproc <= {$oGet->pc80_codproc_final} ";
}

if ( !empty($oGet->pc80_data_inicial) ) {
  $oGet->pc80_data_inicial = new DBDate($oGet->pc80_data_inicial);
  $aWhereProcessoCompras[] = " pc80_data >= '{$oGet->pc80_data_inicial->getDate()}'";
}
if (!empty($oGet->pc80_data_final)) {

  $oGet->pc80_data_final = new DBDate($oGet->pc80_data_final);
  $aWhereProcessoCompras[] = " pc80_data <= '{$oGet->pc80_data_final->getDate()}' ";
}

$sWhereProcessoCompras  = implode(' and ', $aWhereProcessoCompras);
$sSqlProcessoCompras    = $oDaoPcProc->sql_query(null,
                                               "distinct pc80_codproc,
                                                pc80_data,
                                                pc80_resumo,
                                                descrdepto,
                                                coddepto,
                                                nomeresponsavel,
                                                pc80_usuario,
                                                nome",
                                                'pc80_codproc',
                                                $sWhereProcessoCompras
                                              );

$rsDadosProcessoCompras        = $oDaoPcProc->sql_record($sSqlProcessoCompras);
$iTotalLinhasProcessoDeCompras = $oDaoPcProc->numrows;

if ($iTotalLinhasProcessoDeCompras == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! Verifique seu departamento.");
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf, '77');
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";
$pdf1->logo        = $logo;

for ($iContador = 0;$iContador < $iTotalLinhasProcessoDeCompras; $iContador++) {

  $oDadosProcessoDeCompras = db_utils::fieldsMemory($rsDadosProcessoCompras, $iContador);
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = trim($ender).",".$numero;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d", db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  $sec  = "______________________________"."\n"."Secretaria da Fazenda";
  $pref = "______________________________"."\n"."Prefeito";


  $pdf1->casadec     = $iNumeroCasasDecimaisValorUnitario;
  $pdf1->secfaz      = $classinatura->assinatura(1002);
  $pdf1->nompre      = $classinatura->assinatura(1000);

  $pdf1->Snumero     = $oDadosProcessoDeCompras->pc80_codproc;
  $pdf1->Sdata       = $oDadosProcessoDeCompras->pc80_data;
  $pdf1->Sresumo     = substr(stripslashes(addslashes($oDadosProcessoDeCompras->pc80_resumo)), 0, 735);
  $pdf1->Sdepart     = $oDadosProcessoDeCompras->coddepto.' - '.$oDadosProcessoDeCompras->descrdepto;
  $pdf1->Srespdepart = $oDadosProcessoDeCompras->nomeresponsavel;
  $pdf1->Susuarioger = $oDadosProcessoDeCompras->nome;

  $sSqlOrgaDoDepartamento = $oDaoDbDepartOrg->sql_query_orgunid($oDadosProcessoDeCompras->coddepto,
                                                               db_getsession('DB_anousu'),
                                                               "o40_descr,o41_descr"
                                                              );
  $rsOrgaoDoDepartamento  = $oDaoDbDepartOrg->sql_record($sSqlOrgaDoDepartamento);
  $pdf1->Sorgao     = '';
  $pdf1->Sunidade   = '';
  if ($oDaoDbDepartOrg->numrows > 0) {

    $oDadosOrgao    = db_utils::fieldsMemory($rsOrgaoDoDepartamento, 0);
    $pdf1->Sorgao   = $oDadosOrgao->o40_descr;
    $pdf1->Sunidade = $oDadosOrgao->o41_descr;
  }


  $sCamposItem  = " distinct pc01_servico,                                   ";
  $sCamposItem .= "          pc11_seq,                                       ";
  $sCamposItem .= "          pc11_codigo,                                    ";
  $sCamposItem .= "          pc11_seq,                                       ";
  $sCamposItem .= "          pc11_quant,                                     ";
  $sCamposItem .= "          pc11_prazo,                                     ";
  $sCamposItem .= "          pc11_pgto,                                      ";
  $sCamposItem .= "          pc11_resum,                                     ";
  $sCamposItem .= "          pc11_just,                                      ";
  $sCamposItem .= "          m61_abrev,                                      ";
  $sCamposItem .= "          m61_descr,                                      ";
  $sCamposItem .= "          pc17_quant,                                     ";
  $sCamposItem .= "          pc01_codmater,                                  ";
  $sCamposItem .= "          pc01_descrmater,                                ";
  $sCamposItem .= "          pc10_numero,                                    ";
  $sCamposItem .= "          pc90_numeroprocesso as processo_administrativo, ";
  $sCamposItem .= "          (pc11_quant * pc11_vlrun) as pc11_valtot,       ";
  $sCamposItem .= "          m61_usaquant,                                   ";
  $sCamposItem .= "          o56_elemento as so56_elemento,                  ";
  $sCamposItem .= "          case when";
  $sCamposItem .= "            pc11_vlrun = 0";
  $sCamposItem .= "              then (select pcorcamval.pc23_vlrun as pc11_vlrun";
  $sCamposItem .= "                      from solicitem s";
  $sCamposItem .= "                           inner join pcorcamitemsol on s.pc11_codigo               = pcorcamitemsol.pc29_solicitem";
  $sCamposItem .= "                           inner join pcorcamitem    on pcorcamitem.pc22_orcamitem  = pcorcamitemsol.pc29_orcamitem";
  $sCamposItem .= "                           inner join pcorcam        on pcorcam.pc20_codorc         = pcorcamitem.pc22_codorc";
  $sCamposItem .= "                           inner join pcorcamval     on pcorcamval.pc23_orcamitem   = pcorcamitem.pc22_orcamitem";
  $sCamposItem .= "                           inner join pcorcamforne   on pcorcamforne.pc21_codorc    = pcorcam.pc20_codorc";
  $sCamposItem .= "                                                    and pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";
  $sCamposItem .= "                           inner join pcorcamjulg    on pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne";
  $sCamposItem .= "                                                    and pcorcamjulg.pc24_orcamitem  = pcorcamitem.pc22_orcamitem";
  $sCamposItem .= "                                                    and pcorcamjulg.pc24_pontuacao  = 1";
  $sCamposItem .= "                     where s.pc11_codigo = solicitem.pc11_codigo)";
  $sCamposItem .= "              else ";
  $sCamposItem .= "                pc11_vlrun ";
  $sCamposItem .= "              end as pc11_vlrun,";
  $sCamposItem .= "          o56_descr as descrele";

  $sSqlItensDoProcessoDeCompras = $oDaoSolicitem->sql_query_item_processo_compras(null,
                                                                                  $sCamposItem,
                                                                                   'pc11_seq',
                                                                                   "pc81_codproc = {$oDadosProcessoDeCompras->pc80_codproc}");

  $rsDadosItem                   = $oDaoSolicitem->sql_record($sSqlItensDoProcessoDeCompras);
  $iTotalLinhasItens             = $oDaoSolicitem->numrows;
  $pdf1->recorddositens          = $rsDadosItem;
  $pdf1->linhasdositens          = $iTotalLinhasItens;
  $pdf1->item	                   = 'pc11_seq';
  $pdf1->quantitem               = 'pc11_quant';
  $pdf1->valoritem               = 'pc11_vlrun';
  $pdf1->descricaoitem           = 'pc01_descrmater';
  $pdf1->squantunid              = 'pc17_quant';
  $pdf1->sprazo                  = 'pc11_prazo';
  $pdf1->spgto                   = 'pc11_pgto';
  $pdf1->sresum                  = 'pc11_resum';
  $pdf1->sjust                   = 'pc11_just';
  $pdf1->sunidade                = 'm61_descr';
  $pdf1->sabrevunidade           = 'm61_abrev';
  $pdf1->pc10_numero             = 'pc10_numero';
  $pdf1->processo_administrativo = 'processo_administrativo';
  $pdf1->sservico                = 'pc01_servico';
  $pdf1->svalortot               = 'pc11_valtot';
  $pdf1->susaquant               = 'm61_usaquant';
  $pdf1->scodpcmater             = 'pc01_codmater';
  $pdf1->selemento               = 'so56_elemento';
  $pdf1->sdelemento              = 'descrele';

  $oDaoPcParam    = db_utils::getDao('pcparam');
  $result_emissao = $oDaoPcParam->sql_record($oDaoPcParam->sql_query_file(db_getsession("DB_instit"),"pc30_tipoemiss"));
  if ($oDaoPcParam->numrows > 0) {
    db_fieldsmemory($result_emissao, 0);
  }
  $pdf1->sImprimeDadosDotacao = $pc30_tipoemiss;

  if ($pc30_tipoemiss == "t") {

    $sSqlDotacoesItens = $oDaoPcProcItem->sql_query_dotacao_reserva(
                                                               null,
                                                               "pc13_codigo,
                                                                pc13_anousu,
                                                                pc13_coddot,
                                                                pc13_quant,
                                                                pc13_valor,
                                                                pc19_orctiporec,
                                                                o56_elemento as do56_elemento,
                                                                o41_descr,
                                                                o15_codigo,
                                                                o15_descr,
                                                                o55_projativ,
                                                                o55_descr,
                                                                o56_descr as descrestrutural",
                                                                'pc13_codigo',
                                                                "pc81_codproc = {$oDadosProcessoDeCompras->pc80_codproc}"
                                                               );
    $rsDotacoesItens      = $oDaoPcProcItem->sql_record($sSqlDotacoesItens);

    $iTotalDotacoesItens  = $oDaoPcProcItem->numrows;
    $pdf1->recorddasdotac = $rsDotacoesItens;
    $pdf1->linhasdasdotac = $iTotalDotacoesItens;
  }
  $pdf1->descrunid      = 'o41_descr';
  $pdf1->dcprojativ     = 'o55_projativ';
  $pdf1->dprojativ      = 'o55_descr';
  $pdf1->dctiporec      = 'o15_codigo';
  $pdf1->dtiporec       = 'o15_descr';
  $pdf1->dcodigo        = 'pc13_codigo';
  $pdf1->dcoddot        = 'pc13_coddot';
  $pdf1->danousu        = 'pc13_anousu';
  $pdf1->dquant         = 'pc13_quant';
  $pdf1->dcontrap       = 'pc19_orctiporec';
  $pdf1->dvalor         = 'pc13_valor';
  $pdf1->delemento      = 'do56_elemento';
  $pdf1->ddescrest      = 'descrestrutural';


  $pdf1->imprime();
	$pdf1->Snumero_ant = $oDadosProcessoDeCompras->pc80_codproc;

}


if ($oConfiguracaoGed->utilizaGED()) {

  try {

    $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::PROCESSO_COMPRA;
    $oGerenciador = new GerenciadorEletronicoDocumento();
    $oGerenciador->setLocalizacaoOrigem("tmp/");
    $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$pc80_codproc_inicial}.pdf");

    $oStdDadosGED        = new stdClass();
    $oStdDadosGED->nome  = $sTipoDocumento;
    $oStdDadosGED->tipo  = "NUMERO";
    $oStdDadosGED->valor = $pc80_codproc_inicial;
    $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$pc80_codproc_inicial}.pdf");
    $oGerenciador->moverArquivo(array($oStdDadosGED));

  } catch (Exception $eErro) {

    db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
  }

} else {
  $pdf1->objpdf->Output();
}
?>