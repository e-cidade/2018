<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification("libs/db_barras.php"));
require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification(Modification::getFile("fpdf151/impcarne.php")));
require_once(modification("dbforms/db_funcoes.php"));


use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

try {

  $oRegraEmissao = new regraEmissao(29,3,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'));
  $pdf1          = $oRegraEmissao->getObjPdf();

} catch (Exception $eExeption){

  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

$clitbiavalia        = new cl_itbiavalia;
$clitbi              = new cl_itbi;
$oDaoItbiCancela     = new cl_itbicancela;
$clitbidadosimovel   = new cl_itbidadosimovel;
$clitbirural         = new cl_itbirural;
$clitbiruralcaract   = new cl_itbiruralcaract;
$clitbimatric        = new cl_itbimatric;
$clitbilogin         = new cl_itbilogin;
$clitburbano         = new cl_itburbano;
$clitbinumpre        = new cl_itbinumpre;
$cldb_bancos         = new cl_db_bancos;
$clitbinome          = new cl_itbinome;
$clitbinomecgm       = new cl_itbinomecgm;
$clitbicgm           = new cl_itbicgm;
$clitbiconstr        = new cl_itbiconstr;
$clitbipropriold     = new cl_itbipropriold;
$clitbiconstrespecie = new cl_itbiconstrespecie;
$clitbiconstrtipo    = new cl_itbiconstrtipo;
$clnumpref           = new cl_numpref;
$clparreciboitbi     = new cl_parreciboitbi;
$clrecibo            = new cl_recibo;
$clarrenumcgm        = new cl_arrenumcgm;
$clarrematric        = new cl_arrematric;
$clparitbi           = new cl_paritbi;
$clitbiretificacao   = new cl_itbiretificacao;
$clhistcalc          = new cl_histcalc;
$cl_db_usuacgm       = new cl_db_usuacgm;
$oItbiIntermediador  = new cl_itbiintermediador;

$compradoresm        = 0;
$compradoresf        = 0;
$transmitentesm      = 0;
$transmitentesf      = 0;
$outroscompradores   = "";
$outrostransmitentes = "";
$iAnoUsu             = db_getsession('DB_anousu');
$iInstit             = db_getsession('DB_instit');
$dtEmissao           = date('Y-m-d',db_getsession('DB_datausu'));

$lLiberado           = false;
$lRetificado         = false;

$it14_valoravalterfinanc    = 0;
$it14_valoravalconstrfinanc = 0;
$it14_valoravalfinanc       = 0;
$it04_aliquotafinanc        = 0;

$it14_valoraval             = 0;
$it14_valorpaga             = 0;
$it14_valoravalter          = 0;
$it14_valoravalconstr       = 0;
$it14_desc                  = 0;

$p                          = 0;

$config = db_query("select * from db_config where codigo = ".db_getsession("DB_instit"));
db_fieldsmemory($config,0);

$oStdInstituicao = db_utils::fieldsMemory($config, 0);

$iNumprePago = 0;

if($db21_codcli == 19985 or $db21_codcli == 18 or $db21_codcli == 74 or $db21_codcli == 15){

  $rsItbiNumpre = $clitbinumpre->sql_record($clitbinumpre->sql_query_recibo($itbi,"itbinumpre.it15_numpre, arrepaga.k00_dtpaga, arrepaga.k00_valor,
                                                                                   case when disbanco.dtpago is null
                                                                                        then arrepaga.k00_dtpaga
                                                                                        else disbanco.dtpago
                                                                                   end as datapagamento, disbanco.k15_codbco as bancopagamento,
                                                                                   disbanco.k15_codage as agenciapagamento"," k00_dtpaga"));

  if ( $clitbinumpre->numrows == 0 ) {
    $dk00_dtpaga_testa = "";
  } else {
    $oItbiNumpreTesta  = db_utils::fieldsMemory($rsItbiNumpre,0);
    $dk00_dtpaga_testa = $oItbiNumpreTesta->k00_dtpaga;
    $iNumprePago       = $oItbiNumpreTesta->it15_numpre;
  }

  if (isset($tipoguia) && $tipoguia == "n" and $dk00_dtpaga_testa != "" ) {

    $sMensagemErro = 'Sistema nao permite reemitir guia paga. Utiliza a opção [declaração de quitação].';
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
    exit;
  } elseif ( isset($tipoguia) && $tipoguia == "q" and $dk00_dtpaga_testa == "" ) {

    $sMensagemErro = 'Guia não está paga!';
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
    exit;
  }

}

$sSqlRetificacao = $clitbiretificacao->sql_query_file(null,"*",null," it32_itbi = {$itbi}");
$rsretificado    = $clitbiretificacao->sql_record($sSqlRetificacao);

if($clitbiretificacao->numrows > 0){

  $lRetificado     = true;
  $oItbiRetificada = db_utils::fieldsMemory($rsretificado,0);
}

$rsParItbi = $clparitbi->sql_record($clparitbi->sql_query($iAnoUsu,"*",null,""));
if ($clparitbi->numrows > 0) {
  db_fieldsmemory($rsParItbi,0);
}

$rsItbiAvalia = $clitbiavalia->sql_record($clitbiavalia->sql_query($itbi));
if ($clitbiavalia->numrows > 0) {

  db_fieldsmemory($rsItbiAvalia,0);
  $nomeUsuarioLiberado   = $nome;

  $rsUsuaCGM = db_query($cl_db_usuacgm->sql_matricula_usuario($it14_id_usuario));
  $codigoUsuarioLiberado = 'NP';

  if (pg_num_rows($rsUsuaCGM) > 0){

    db_fieldsmemory($rsUsuaCGM,0);
    $codigoUsuarioLiberado = $rh01_regist;
  }

  $dataLiberado = $it14_dtliber;
  $lLiberado    = true;
}

$rsItbi = $clitbi->sql_record($clitbi->sql_query( null,
                                                  "*",
                                                  null,
                                                  "it01_guia = {$itbi}"
                                                  . " and it01_coddepto = " . db_getsession("DB_coddepto") ));

if ($clitbi->numrows == 0) {

  $sMensagemErro = 'ITBI não encontrada para o departamento!';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;
}

/**
 * Valida se a guia esta cancelada não deve permitir emissão
 */
$sSqlVerificaItbiCancelada = $oDaoItbiCancela->sql_query( $itbi );
$rsVerificaItbiCancelada   = $oDaoItbiCancela->sql_record( $sSqlVerificaItbiCancelada );

if ($oDaoItbiCancela->numrows != 0) {

  $sMensagemErro = 'ITBI cancelada, emissão não permitida!';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;
}

if ($clitbi->numrows > 0) {

  db_fieldsmemory($rsItbi,0);
  $nomeUsuarioIncluido = $nome;
  $nomeDepartamento = $descrdepto;
}

$areaterreno = $it01_areaterreno;
$areatran = $it01_areatrans;

$rsItbUrbano = $clitburbano->sql_record($clitburbano->sql_query($itbi));
if($clitburbano->numrows > 0){
  db_fieldsmemory($rsItbUrbano,0);
  $tipo = "urbano";
}

$rsItbiRural = $clitbirural->sql_record($clitbirural->sql_query($itbi));
if($clitbirural->numrows > 0){
  db_fieldsmemory($rsItbiRural,0);
  $tipo = "rural";
}

$rsItbiMatric = $clitbimatric->sql_record($clitbimatric->sql_query($itbi));
if($clitbimatric->numrows > 0){
  db_fieldsmemory($rsItbiMatric,0);
}

/*
 * verifica parametros preenchidos
 * CGM e RECEITA
 */
$sSqlParametroparreciboitbi = $clparreciboitbi->sql_query();
$clparreciboitbi->sql_record($sSqlParametroparreciboitbi);
if ($clparreciboitbi->numrows == 0) {

  $sMensagemErro = 'Parâmetros de CGM e Código da Receita não cadastrado ! ';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;

}

/*
 * Verifica se o 707 e o 808
 * estao na base de dados
 */
$sSQLParametroHistcalc707 = $clhistcalc->sql_query("","k01_codigo","","k01_codigo = 707 ");
$clhistcalc->sql_record($sSQLParametroHistcalc707);
if ($clhistcalc->numrows == 0) {

  $sMensagemErro = 'Histórico 707 Não Cadastrado ! ';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;
}

$sSQLParametroHistcalc807 = $clhistcalc->sql_query("","k01_codigo","","k01_codigo = 807 ");
$clhistcalc->sql_record($sSQLParametroHistcalc807);
if ($clhistcalc->numrows == 0) {

  $sMensagemErro = 'Histórico 807 Não Cadastrado ! ';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;
}

if ( !isset($tipoguia) or ($db21_codcli != 19985 and $db21_codcli != 18 and $db21_codcli != 74 and $db21_codcli != 15) ) {
  $tipoguia = "n";
}

$proprietarios = "";

/*************************************************************   A D Q U I R E N T E S   ****************************************************************************************/

/*===================================  COM A NOVA EXTRUTURA BUSCA OS DADOS DE TRANSMISSORES E ADQUIRENTES NA TABELA ITBINOME ===================================================*/

/* AQUI PEGA SO O ADQUIRENTE PRINCIPAL E SEUS DADOS */
$rscompprinc = $clitbinome->sql_record($clitbinome->sql_query("","
                                   it03_nome     as nomecompprinc,
                                   it03_mail     as mailcomprador,
                                   it03_cpfcnpj  as cgccpfcomprador,
                                   it03_endereco as enderecocomprador,
                                   it03_numero   as numerocomprador,
                                   it03_compl    as complcomprador,
                                   it03_munic    as municipiocomprador,
                                   it03_uf       as ufcomprador,
                                   it03_cep      as cepcomprador,
                                   it03_sexo     as sexocomprador,
                                   it03_princ    as principalcomprador,
                                   it03_bairro   as bairrocomprador",
                                   ""," it03_guia = $itbi
                                   and upper(it03_tipo)  = 'C'
                                   and it03_princ = 't' "));

if($clitbinome->numrows  > 0){
  db_fieldsmemory($rscompprinc,$p);
}else{

  $sMensagemErro = 'Adquirente principal não encontrado ! ';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;

}

/* AQUI PEGA OS ADQUIRENTES SECUNDARIOS */

$result = $clitbinome->sql_record($clitbinome->sql_query(""," it03_nome as nomecomp,  it03_sexo as sexocomprador",""," it03_guia = $itbi and upper(it03_tipo)  = 'C' and it03_princ = 'f' "));

if($clitbinome->numrows  > 0){
  $traco = '';
  $proprietarios .= "-".'ADQUIRENTES : ';
  $num = pg_numrows($result);
  for ($p = 0;$p < $num;$p++){
    db_fieldsmemory($result,$p);
    // acumula o numero compradores homens e mulheres para colocar na observação da guia
    if(strtoupper($sexocomprador) == 'M'){
      $compradoresm++;
    }elseif(strtoupper($sexocomprador) == 'F'){
      $compradoresf++;
    }

    $proprietarios .= $traco.trim($nomecomp);
    $traco = ' - ';
  }

  if($compradoresm == 1 && $compradoresf == 0){
    $outroscompradores = " e outro... ";
  }else if($compradoresm > 0){
    $outroscompradores = " e outros... ";
  }else if($compradoresf == 1 && $compradoresm == 0){
    $outroscompradores = " e outra... ";
  }else if($compradoresf > 0 && $compradoresm == 0){
    $outroscompradores = " e outras... ";
  }

}

$resultcons = $clitbiconstr->sql_record( $clitbiconstr->sql_query(null,"*",null," it08_guia = $itbi"));
$linhasresultcons = $clitbiconstr->numrows;

if($clitbiconstr->numrows  > 0){
  $num = pg_numrows($resultcons);
  $linhasresultcons = $num;
  $areatotal = 0;
  $areatrans = 0;
  for ($p = 0;$p < $num;$p++){

    db_fieldsmemory($resultcons,$p);
    $areatrans += $it08_areatrans;
    $areatotal += $it08_area;
  }
}

$pdf1->intermediadorNome  = null;
$pdf1->intermediadorCpf   = null;
$pdf1->intermediadorCreci = null;

// Intermediador
$sSqlItbiIntermediador   = $oItbiIntermediador->sql_get_principal($itbi);
$resultItbiIntermediador = $oItbiIntermediador->sql_record($sSqlItbiIntermediador);

if($oItbiIntermediador->numrows > 0){

  db_fieldsmemory($resultItbiIntermediador, 0);
  $pdf1->intermediadorNome  = utf8_decode($it35_nome);
  $pdf1->intermediadorCpf   = $it35_cnpj_cpf;
  $pdf1->intermediadorCreci = $it35_creci;
}

/*============================================================================================================================================================================*/
/********************************************************** T R A N S M I T E N T E S *****************************************************************************************/
/*============================================================================================================================================================================*/

$result1 = $clitbinome->sql_record($clitbinome->sql_queryguia("","z01_numcgm,it03_nome as z01_nome,z01_telef as fonetransmitente,it03_mail as mailtransmitente ,it03_sexo,it03_cpfcnpj as z01_cgccpf,it03_endereco as z01_ender,it03_numero,it03_compl,it03_cxpostal,it03_bairro as z01_bairro,it03_munic as z01_munic,it03_uf as z01_uf,it03_cep as z01_cep,it03_mail,it22_itbi,it22_setor as j34_setor,it22_quadra as j34_quadra,it22_lote as j34_lote,it22_descrlograd as j14_nome,j13_descr,it22_numero as j39_numero,it22_compl as j39_compl,it06_matric,it04_codigo,it04_descr,it04_desconto,it04_obs,itbi.*,itburbano.*,itbirural.*,itbiavalia.*",""," it03_guia  = $itbi and upper(it03_tipo)  = 'T' and it03_princ = 't' "));

if($clitbinome->numrows  > 0){
  db_fieldsmemory($result1,0);
}

/** Extensao : Inicio [guia-itbi-setor-quadra-lote-localizacao] */
/** Extensao : Fim [guia-itbi-setor-quadra-lote-localizacao] */

$propri = "";

$result = $clitbinome->sql_record($clitbinome->sql_queryguia("","it03_nome as nomeoutro, it03_guia as it20_guia,it03_sexo ",""," it03_guia = $itbi and upper(it03_tipo) = 'T' and it03_princ= 'f' "));

$transmitentesm = 0;
$transmitentesf = 0;
if($clitbinome->numrows  > 0){
  $traco = '';
  $propri .= "-".'OUTRO(S) TRANSMITENTE(S) : ';
  $num = pg_numrows($result);

  // acumula o numero compradores homens e mulheres para colocar na observação da guia
  for ($p = 0;$p < $num;$p++){
    db_fieldsmemory($result,$p);
    if(strtoupper($it03_sexo)== 'M'){
      $transmitentesm++;
    }elseif(strtoupper($it03_sexo) == 'F'){
      $transmitentesf++;
    }
    $propri .= $traco.trim($nomeoutro);
    $traco = ' - ';
  }

  if($transmitentesm == 1 && $transmitentesf == 0){
    $outrostransmitentes = " e outro...";
  }else if($transmitentesm > 0){
    $outrostransmitentes = " e outros...";
  }else if($transmitentesf == 1 && $transmitentesm == 0){
    $outrostransmitentes = " e outra...";
  }else if($transmitentesf > 0 && $transmitentesm == 0){
    $outrostransmitentes = " e outras...";
  }

}

/*================================================  B U S C A   O   C G M   D O   D E V E D O R   =======================================================================*/

$result = $clparreciboitbi->sql_record($clparreciboitbi->sql_query_file());
if($clparreciboitbi->numrows > 0){
  db_fieldsmemory($result,0);
  $cgmdevedor = $it17_numcgm;
}
$rscgmdevedor = $clitbinomecgm->sql_record($clitbinomecgm->sql_query(null," it21_numcgm ",null," itbinome.it03_princ = 't' and itbinome.it03_tipo = 'C' and itbinome.it03_guia  = $itbi"));
if($clitbinomecgm->numrows > 0){
  db_fieldsmemory($rscgmdevedor,0);
  $cgmdevedor = $it21_numcgm;
}else{
  $rscgmdevedor = $clitbinomecgm->sql_record($clitbinomecgm->sql_query(null," it21_numcgm ",null," itbinome.it03_princ = 'f' and itbinome.it03_tipo = 'C' and itbinome.it03_guia  = $itbi"));
  if($clitbinomecgm->numrows > 0){
    db_fieldsmemory($rscgmdevedor,0);
    $cgmdevedor = $it21_numcgm;
  }
}
if(!isset($cgmdevedor) || $cgmdevedor == ""){

  $sMensagemErro = 'Parâmetros do recibo não configurados! \n Contate suporte!';
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  exit;
}

$pdf1->cgc = $cgc;

/**
 * Verifica se a guia está liberada para emissão
 * Caso não esteja não gera recibo
 */
if ($lLiberado and $tipoguia != "q") {

/**************************************************    I N S E R E   O   R E C I B O    **********************************************************************************/
// Não Insere Recibo quando não estiver liberada
  $sqlerro = false;
  db_inicio_transacao();
  $numpre       = $clnumpref->sql_numpre();
  $numpre_ficha = $numpre;
  $resnumpre    = $clitbinumpre->sql_record($clitbinumpre->sql_query(null,"*",""," it15_guia = {$itbi}"));
  /**
   * Se existirem guias emitidas altera a situação de ultima emitida para false
   */
  if ($clitbinumpre->numrows > 0) {

    $aItbiNumpre  = db_utils::getCollectionByRecord($resnumpre);
    foreach ($aItbiNumpre as $oItbiNumpre) {

      $clitbinumpre->it15_sequencial = $oItbiNumpre->it15_sequencial;
      $clitbinumpre->it15_ultimaguia = 'f';
      $clitbinumpre->alterar($oItbiNumpre->it15_sequencial);
    }
  }
  $clitbinumpre->it15_guia       = $itbi;
  $clitbinumpre->it15_numpre     = $numpre;
  $clitbinumpre->it15_ultimaguia = 't';
  $clitbinumpre->incluir(null);

  $numpre = $clitbinumpre->it15_numpre;

  $clrecibo->k00_numcgm    = $cgmdevedor;
  $clrecibo->k00_dtoper    = date("Y-m-d",db_getsession("DB_datausu"));
  $clrecibo->k00_receit    = $it17_codigo;
  $clrecibo->k00_hist      = 707;
  $clrecibo->k00_valor     = $it14_valorpaga;
  $clrecibo->k00_dtvenc    = $it14_dtvenc;
  $clrecibo->k00_numpre    = $numpre;
  $clrecibo->k00_numpar    = 1;
  $clrecibo->k00_numtot    = 1;
  $clrecibo->k00_numdig    = '0';
  $clrecibo->k00_tipo      = 29;
  $clrecibo->k00_tipojm    = '0';
  $clrecibo->k00_numnov    = 0;
  $clrecibo->k00_codsubrec = '0';
  $clrecibo->incluir();
  if($clrecibo->erro_status == 0){

    $sqlerro = true;
    $erromsg = "Erro recibo ".$clrecibo->erro_msg;
  }

  $oRecibo = new recibo(1);
  $oRecibo->setNumnov($numpre);
  $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

  //@TODO validação para  não fazer requisição para webservice SIGCB  quando valor da guia for zerado.
  $lExisteValor =  $it14_valorpaga  !=  "0" || $it14_valorpaga != 0 ;

  if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio()) && $lExisteValor) {
    CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
  }


  if( isset($it06_matric) && $it06_matric != "" ) {
    if ($sqlerro == false) {
      // inclui na arrematric
      $clarrematric->k00_numpre = $numpre;
      $clarrematric->k00_matric = $it06_matric;
      $clarrematric->k00_perc   = 100;
      $clarrematric->incluir($numpre,$it06_matric);
      if($clarrematric->erro_status == 0){
          $sqlerro = true;
          $erromsg = "Erro arrematric ".$clarrematric->erro_msg;
      }
    }
  }

  if ($sqlerro == false) {
      // inclui na arrenumcgm
      $clarrenumcgm->incluir_se_nao_existir($cgmdevedor,$numpre);
      if($clarrenumcgm->erro_status == 0){
          $sqlerro = true;
          $erromsg = "Erro arrenumcgm ".$clarrenumcgm->erro_msg;
      }
  }

  if ($sqlerro == true) {

    $sMensagemErro = $erromsg;
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
    exit;
  }
  db_fim_transacao($sqlerro);

/******************************************************************************************************************************************************************/
} else {

  if ( $iNumprePago > 0 ) {
    $numpre = $iNumprePago;
  } else {

    $rsItbiNumpre = $clitbinumpre->sql_record($clitbinumpre->sql_query($itbi));

    if($clitbinumpre->numrows > 0) {
      $oItbiNumpre = db_utils::fieldsMemory($rsItbiNumpre, 0);
      $numpre = $oItbiNumpre->it15_numpre;
    }

  }

}

$pdf1->datapagamento    = "";
$pdf1->valorpagamento   = 0;
$pdf1->bancopagamento   = 0;
$pdf1->agenciapagamento = "";

if ( $tipoguia == "q" ) {

  $sCamposItbiNumpre  = "arrepaga.k00_dtpaga,                    ";
  $sCamposItbiNumpre .= "arrepaga.k00_valor,                     ";
  $sCamposItbiNumpre .= "case                                    ";
  $sCamposItbiNumpre .= "  when disbanco.dtpago is null          ";
  $sCamposItbiNumpre .= "    then arrepaga.k00_dtpaga            ";
  $sCamposItbiNumpre .= "  else disbanco.dtpago                  ";
  $sCamposItbiNumpre .= "end as datapagamento,                   ";
  $sCamposItbiNumpre .= "disbanco.k15_codbco as bancopagamento,  ";
  $sCamposItbiNumpre .= "disbanco.k15_codage as agenciapagamento,";
  $sCamposItbiNumpre .= "itbi.it01_data                          ";
  $rsItbiNumpre = $clitbinumpre->sql_record($clitbinumpre->sql_query_recibo($itbi,$sCamposItbiNumpre));

  for($x = 0; $x < $clitbinumpre->numrows; $x++){
    $oItbiNumpre = db_utils::fieldsMemory($rsItbiNumpre, $x);

      if ( $oItbiNumpre->datapagamento != "" ) {

        $iAnoPagamento = date( 'Y', strtotime($oItbiNumpre->it01_data) );
        $rsTxParItbi   = db_query($clparitbi->sql_query($iAnoPagamento, 'it24_taxabancaria '));
        $oParItbi      = db_utils::fieldsMemory($rsTxParItbi, 0);

        $pdf1->datapagamento    = $oItbiNumpre->datapagamento;
        $pdf1->valorpagamento   = $oItbiNumpre->k00_valor;
        $pdf1->bancopagamento   = $oItbiNumpre->bancopagamento;
        $pdf1->agenciapagamento = $oItbiNumpre->agenciapagamento;
        $pdf1->taxa_bancaria    = $oParItbi->it24_taxabancaria;
        break;
      }
  }
}

$datavencimento = $it14_dtvenc;
$valorpagamento = (float)$it14_valorpaga;

// Valor da taxa bancário deverá vir sempre dos parametros do ITBI
$tx_banc        = $it24_taxabancaria;

// caso existir taxa bancaria na tabela db_config soma no valor da itbi
if(isset($tx_banc) && $tx_banc != '' && $tx_banc > 0){
  $valorpagamento += $tx_banc;
}

$vlrbar = db_formatar(str_replace('.','',str_pad(number_format($valorpagamento,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');

if($oRegraEmissao->isCobranca()){

  if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0) {
    $k00_valor = 0;
    $especie   = $ninfla;
    $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
  }else{
    $especie   = 'R$';
    $histinf   = "";
  }

}

$pdf1->agencia_cedente = null;
$pdf1->nosso_numero    = null;
$pdf1->carteira        = null;

if ($lLiberado) {
  // Nao gera código de barras quando não estiver liberada
  try {
    $oConvenio = new convenio($oRegraEmissao->getConvenio(),$numpre,1,$it14_valorpaga,$vlrbar,$datavencimento,6);

    if ($lConvenioCobrancaValido && CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio()) && $lExisteValor) {
      CobrancaRegistrada::registrarReciboWebservice($numpre, $oRegraEmissao->getConvenio(), $it14_valorpaga);
    }
  } catch (Exception $eExeption){
     db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
     exit;
  }

  $codigo_barras   = $oConvenio->getCodigoBarra();
  $linha_digitavel = $oConvenio->getLinhaDigitavel();

  $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
  $pdf1->carteira        = $oConvenio->getCarteira();
  $pdf1->nosso_numero    = $oConvenio->getNossoNumero();

  $pdf1->tipo_convenio = $oConvenio->getTipoConvenio();
  /// Fim código barras
}

$areaterrenomat   = split('\.',$areatran);
$areaedificadamat = split('\.',@$areatotal);

$result = $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query($itbi,"","*","j31_codigo"));
$linhasitbiruralcaract = $clitbiruralcaract->numrows;

if ( $linhasitbiruralcaract > 0 ) {
  for ( $ru = 0; $ru < $linhasitbiruralcaract; $ru++ ){
    db_fieldsmemory($result,$ru);
    $arrayj13_descr[$ru]  = @$j31_descr;
    $arrayit19_valor[$ru] = @$it19_valor;
  }
}

if($linhasresultcons > 0){

  for ($n = 0;$n < $linhasresultcons ; $n++){
    db_fieldsmemory($resultcons,$n);

    $rsItbiConsEspecie = $clitbiconstrespecie->sql_record($clitbiconstrespecie->sql_query($it08_codigo));
    if ($clitbiconstrespecie->numrows > 0) {
      db_fieldsmemory($rsItbiConsEspecie,0);
    }

    $it09_codigo = @$j31_descr;

    $rsConsTipo  = $clitbiconstrtipo->sql_record($clitbiconstrtipo->sql_query($it08_codigo));
    if ($clitbiconstrtipo->numrows > 0) {
      db_fieldsmemory($rsConsTipo,0);
    }

    $it10_codigo = @$j31_descr;

    $arrayit09_codigo[$n]    = @$it09_codigo;
    $arrayit10_codigo[$n]    = @$it10_codigo;
    $arrayit08_area[$n]      = @$it08_area;
    $arrayit08_areatrans[$n] = @$it08_areatrans;
    $arrayit08_ano[$n]       = @$it08_ano;

    if($n == 9)
    break;
  }

}

if($oRegraEmissao->isCobranca()){

  $sqltipo= "select k00_tipo,k03_tipo,k00_descr from arretipo where k03_tipo = 8";
  $resulttipo = db_query($sqltipo);
  $linhastipo = pg_num_rows($resulttipo);
  if($linhastipo>0){
    db_fieldsmemory($resulttipo,0);
    $tipo_exerc = $k00_descr."/ ".date('Y',db_getsession('DB_datausu'));
  }

  $valor_documento =db_formatar($it14_valorpaga,"f");

  if($tipo=='urbano'){
    $historico = "
  ITBI N".chr(176)." ".db_formatar($itbi,'s','0',5)."/".db_getsession('DB_anousu')."    Tipo: $tipo
  Setor/Quadra/Lote: ".@$j34_setor."/".@$j34_quadra."/".@$j34_lote."
  Endereço: ".@$j14_tipo." ".@$j14_nome." ".@$j39_numero.(@$j39_compl!=""?"/".@$j39_compl:"")."
  Bairro:".@$j13_descr."
  Transmitente: $z01_nome
  ";
  }else{

    $historico = "
  ITBI N".chr(176)." ".db_formatar($itbi,'s','0',5)."/".db_getsession('DB_anousu')."    Tipo: $tipo
  Endereço: ".@$j14_tipo." ".@$j14_nome." ".@$j39_numero.(@$j39_compl!=""?"/".@$j39_compl:"")."
  Bairro:".@$j13_descr."
  Transmitente: ".@$z01_nome;
  }

  $pdf1->valor_documento  =@$valor_documento;
  $pdf1->especie          =@$especie;
  $pdf1->valor_cobrado    =@$valor_cobrado;
  $pdf1->valtotal         =@$valor_documento;
  $pdf1->descr10          = "1 / 1";
  $pdf1->dtparapag        = db_formatar(@$datavencimento,"d");
  $pdf1->data_processamento = date('d/m/Y',db_getsession('DB_datausu'));
  $pdf1->descr11_1        = @$nomecompprinc;                          // nome sacado
  $pdf1->descr11_2        = @$enderecocomprador."-".@$bairrocomprador;// endereço sacado
  $pdf1->cep              = @$cepcomprador;
  $pdf1->munic            = @$municipiocomprador;
  $pdf1->descr9           = @$numpre_ficha."001";
  $pdf1->descr12_1        = @$historico;
  $pdf1->tipo_exerc       = @$tipo_exerc;
  $pdf1->prefeitura       = @$nomeinst;
}

  $rsDadosFormaPgto = $clitbiavalia->sql_record($clitbiavalia->sql_query_pag($itbi,"it27_descricao, it27_aliquota, it24_valor, it04_desconto","it28_sequencial"));

  $iLinhasFormaPgto = $clitbiavalia->numrows;
  $aDadosFormasPgto = array();

  $iForma = 0;

  if ( $iLinhasFormaPgto > 0 ) {

    for ( $iInd=0; $iInd < $iLinhasFormaPgto; $iInd++) {

      $oDadosFormasPgto = db_utils::fieldsMemory($rsDadosFormaPgto,$iInd);

      $nValorImposto = $oDadosFormasPgto->it24_valor * ($oDadosFormasPgto->it27_aliquota / 100);
      $nDescImposto  = $nValorImposto * ( $oDadosFormasPgto->it04_desconto / 100 );
      $nTotalImposto = $nValorImposto - $nDescImposto;

      if ($nTotalImposto >= 0) {

        $aDadosFormasPgto[$iForma]["Descricao"] = $oDadosFormasPgto->it27_descricao;
        $aDadosFormasPgto[$iForma]["Aliquota"]  = $oDadosFormasPgto->it27_aliquota;
        $aDadosFormasPgto[$iForma]["Valor"]     = $oDadosFormasPgto->it24_valor;
        $aDadosFormasPgto[$iForma]["Imposto"]   = $nTotalImposto;

        $iForma++;
      }
    }
  }

  if ( $iForma < 2 ) {

      for ( $iFormaNova=$iForma;$iFormaNova<=1;$iFormaNova++) {

        $aDadosFormasPgto[$iFormaNova]["Descricao"] = "";
        $aDadosFormasPgto[$iFormaNova]["Aliquota"]  = "";
        $aDadosFormasPgto[$iFormaNova]["Valor"]     = "";
        $aDadosFormasPgto[$iFormaNova]["Imposto"]   = "";
      }
  }

  $sWhere  = "     it19_guia    = {$itbi}";
  $sWhere .= " and it19_tipocaract = 1     ";

  $rsBuscaCaractDistr = $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query(null,null,"j31_descr,it19_tipocaract,it19_valor",null,$sWhere));
  $iLinhasCaractDistr = $clitbiruralcaract->numrows;
  $aDadosCaractDistr  = array();

  if ( $iLinhasCaractDistr > 0 ) {

    for ( $iInd=0; $iInd < $iLinhasCaractDistr; $iInd++){
      $oDadosCaractDistr = db_utils::fieldsMemory($rsBuscaCaractDistr,$iInd);
      $aDadosCaractDistr[$iInd]['Descricao'] = $oDadosCaractDistr->j31_descr;
      $aDadosCaractDistr[$iInd]['Valor']  = $oDadosCaractDistr->it19_valor;
    }
  }

  $sWhere  = "     it19_guia    = {$itbi}";
  $sWhere .= " and it19_tipocaract = 2     ";

  $rsBuscaCaractUtil = $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query(null,null,"j31_descr,it19_tipocaract,it19_valor",null,$sWhere));

  $iLinhasCaractUtil = $clitbiruralcaract->numrows;
  $aDadosCaractUtil  = array();

  if ( $iLinhasCaractUtil > 0 ) {
    for ( $iInd=0; $iInd < $iLinhasCaractUtil; $iInd++){
      $oDadosCaractUtil = db_utils::fieldsMemory($rsBuscaCaractUtil,$iInd);
      $aDadosCaractUtil[$iInd]['Descricao'] = $oDadosCaractUtil->j31_descr;
      $aDadosCaractUtil[$iInd]['Valor']     = $oDadosCaractUtil->it19_valor;
    }
  }

  $rsDadosImovel = $clitbidadosimovel->sql_record($clitbidadosimovel->sql_query(null,"*",null,"it22_itbi = {$itbi}"));
  if ( $clitbidadosimovel->numrows > 0 ) {
     $oDadosImovel = db_utils::fieldsMemory($rsDadosImovel,0);

     if ($oDadosImovel->it22_matricri == 'null') {
      $oDadosImovel->it22_matricri = '';
     }

     $iMatricri    = $oDadosImovel->it22_matricri;
     $iQuadrari    = $oDadosImovel->it22_quadrari;
     $iLoteri      = $oDadosImovel->it22_loteri;
  }

  if ( isset($it24_impsituacaodeb) && $it24_impsituacaodeb != 'f') {

    if ( isset($tipo) && $tipo == 'urbano' ) {

      $rsNumPref = $clnumpref->sql_record($clnumpref->sql_query($iAnoUsu,$iInstit,"numpref.k03_regracnd",null,""));

      if ( $clnumpref->numrows > 0 ) {
        db_fieldsmemory($rsNumPref,0);
      }

      if (isset($k03_regracnd)){
        if (!empty($j01_matric)) {

          $sSqlTipoCertidao  = " select fc_tipocertidao as tipocertidao                                ";
          $sSqlTipoCertidao .= "   from fc_tipocertidao($j01_matric,'m','$dtEmissao','',$k03_regracnd)  ";

          $rsTipoCertidao    = db_query($sSqlTipoCertidao);
          $iTipoCertidao     = pg_num_rows($rsTipoCertidao);
          if ( $iTipoCertidao > 0 ) {
            db_fieldsmemory($rsTipoCertidao,0);
          }

          switch ($tipocertidao) {

            case 'positiva':

              $sMsgSituacao = 'IMÓVEL COM DÉBITOS PENDENTES NESTA DATA';
              break;

            default:

              $sMsgSituacao = 'IMÓVEL EM DIA NESTA DATA';

          }

        }
      }
    }
  }

  $sMsgObs = @$it01_obs;
  if ( $lRetificado ) {

    // Pega o ano da itbi retificada
    $rsItbiRetificativa = db_query($clitbi->sql_query($oItbiRetificada->it32_itbiretif));
    $oItbiRetificativa  = db_utils::fieldsMemory($rsItbiRetificativa, 0);

    // Repassa a qual guia a atual é retificativa
    $pdf1->sOrigemRetificacaoNumero = $oItbiRetificada->it32_itbiretif;
    $pdf1->sOrigemRetificacaoAno    = date('Y', strtotime($oItbiRetificativa->it01_data));

    // Se for de Araruama, não escreve esta observação, pois é escrita em outra parte do relatório
    if ($db21_codcli != 74) {
      $sMsgObs .= " GUIA RETIFICATIVA À GUIA DE NÚMERO {$oItbiRetificada->it32_itbiretif}";
    }
  }

  $sUsuarioAtual  = " select rh01_regist, z01_nome, rh37_descr                                                                     ";
  $sUsuarioAtual .= " from db_usuarios ";
  $sUsuarioAtual .= " left join db_usuacgm on db_usuarios.id_usuario = db_usuacgm.id_usuario ";
  $sUsuarioAtual .= " left join pessoal.rhpessoal on cgmlogin = rh01_numcgm ";
  $sUsuarioAtual .= " left join protocolo.cgm on rh01_numcgm = z01_numcgm ";
  $sUsuarioAtual .= "        left join pessoal.rhpessoalmov on rh01_regist              = rh02_regist
                                                           and rh02_anousu              = fc_anofolha(rhpessoalmov.rh02_instit)
                                                           and rhpessoalmov.rh02_mesusu = fc_mesfolha(rhpessoalmov.rh02_instit)    ";
  $sUsuarioAtual .= "        left join pessoal.rhfuncao      on rh37_funcao = rh02_funcao
                                                            and rh37_instit = rh02_instit                                          ";
  $sUsuarioAtual .= " left join pessoal.rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
  $sUsuarioAtual .= " where db_usuarios.id_usuario = " . db_getsession("DB_id_usuario") . " and rhpesrescisao.rh05_seqpes is null ";
  $rsUsuarioAtual = db_query($sUsuarioAtual) or die($sUsuarioAtual);
  if ( pg_numrows($rsUsuarioAtual) > 0 ) {
     $oUsuarioAtual = db_utils::fieldsMemory($rsUsuarioAtual,0);
  } else {

    $oUsuarioAtual->rh01_regist = "";
    $oUsuarioAtual->z01_nome    = "";
    $oUsuarioAtual->rh37_descr  = "";
  }

/**
 * Busca a mensagem do recibo configurada no débito 29
 */
$k00_msgrecibo = '';
$oDaoArreTipo  = new cl_arretipo();
$sSqlArreTipo  = $oDaoArreTipo->sql_query_file(null, "k00_msgrecibo", null, "k00_tipo = 29");
$rsArreTipo    = $oDaoArreTipo->sql_record($sSqlArreTipo);

if ($oDaoArreTipo->numrows > 0) {
  $k00_msgrecibo = db_utils::fieldsMemory($rsArreTipo, 0)->k00_msgrecibo;
}


/** Extensao : Inicio [guia-itbi-remove-informacao-transmitente-usucapiao] */
/** Extensao : Fim [guia-itbi-remove-informacao-transmitente-usucapiao] */

$pdf1->usuario_atual_regist = $oUsuarioAtual->rh01_regist;
$pdf1->usuario_atual_nome   = $oUsuarioAtual->z01_nome;
$pdf1->usuario_atual_funcao = $oUsuarioAtual->rh37_descr;

$pdf1->z01_nome                  =@$z01_nome;
$pdf1->logoitbi                  =@$logo;
$pdf1->nomeinst                  =@$nomeinst;
$pdf1->tipoitbi                  =@$tipo;
$pdf1->datavencimento            =@$datavencimento;
$pdf1->it04_descr                =@$it04_descr;
$pdf1->numpreitbi                =@$numpre;
$pdf1->ano                       =db_getsession("DB_anousu");
$pdf1->itbi                      =@$itbi;
$pdf1->nomecompprinc             =@$nomecompprinc;
$pdf1->outroscompradores         =@$outroscompradores;
$pdf1->z01_cgccpf                =@$z01_cgccpf;
$pdf1->cgccpfcomprador           =@$cgccpfcomprador;
$pdf1->z01_ender                 =@$z01_ender.",".$it03_numero.($it03_compl!=""?"/".$it03_compl:"");
$pdf1->z01_bairro                =@$z01_bairro;
$pdf1->enderecocomprador         =@$enderecocomprador;
$pdf1->numerocomprador           =@$numerocomprador;
$pdf1->complcomprador            =@$complcomprador;
$pdf1->z01_munic                 =@$z01_munic;
$pdf1->z01_uf                    =@$z01_uf;
$pdf1->z01_cep                   =@$z01_cep;
$pdf1->municipiocomprador        =@$municipiocomprador;
$pdf1->fonecomprador             =@$fonecomprador;
$pdf1->fonetransmitente          =@$fonetransmitente;
$pdf1->ufcomprador               =@$ufcomprador;
$pdf1->cepcomprador              =@$cepcomprador;
$pdf1->bairrocomprador           =@$bairrocomprador;
$pdf1->it06_matric               =@$it06_matric;
$pdf1->j39_numero                =@$j39_numero;
$pdf1->j39_compl                 =@$j39_compl;
$pdf1->j34_setor                 =@$j34_setor;
$pdf1->j34_quadra                =@$j34_quadra;
$pdf1->matriz                    =@$matriz[3];
$pdf1->j34_lote                  =@$j34_lote;
$pdf1->j13_descr                 =@$j13_descr;
$pdf1->j14_tipo                  =@$j14_tipo;
$pdf1->j14_nome                  =@$j14_nome;
$pdf1->it07_descr                =@$it07_descr;
$pdf1->it05_frente               =@$it05_frente;
$pdf1->it05_fundos               =@$it05_fundos;
$pdf1->it05_esquerdo             =@$it05_esquerdo;
$pdf1->it05_direito              =@$it05_direito;
$pdf1->it18_frente               =@$it18_frente;
$pdf1->it18_fundos               =@$it18_fundos;
$pdf1->it18_prof                 =@$it18_prof;
$pdf1->it18_nomelograd           =@$it18_nomelograd;
$pdf1->areaterreno               =@$areaterreno;
$pdf1->areatran                  =@$areatran;
$pdf1->areaterrenomat            =@$areaterrenomat;
$pdf1->areatotal                 =@$areatotal;
$pdf1->areaedificadamat          =@$areaedificadamat;
$pdf1->areatotal                 =@$areatotal;
$pdf1->areaedificadamat          =@$areaedificadamat;
$pdf1->areatrans                 =@$areatrans;
$pdf1->arrayj13_descr            =@$arrayj13_descr;
$pdf1->arrayj13_valor            =@$arrayj13_valor;
$pdf1->linhasresultcons          =@$linhasresultcons;
$pdf1->arrayit09_codigo          =@$arrayit09_codigo;
$pdf1->arrayit10_codigo          =@$arrayit10_codigo;
$pdf1->arrayit08_area            =@$arrayit08_area;
$pdf1->arrayit08_areatrans       =@$arrayit08_areatrans;
$pdf1->arrayit08_ano             =@$arrayit08_ano;
$pdf1->tx_banc                   =@$tx_banc;
$pdf1->propri                    =@$propri;
$pdf1->proprietarios             =@$proprietarios;
$pdf1->it14_valoravalter         =(float)$it14_valoravalter;
$pdf1->it14_valoravalconstr      =(float)$it14_valoravalconstr;
$pdf1->it14_valoraval            =(float)$it14_valoraval;
$pdf1->it14_valoravalterfinanc   =(float)$it14_valoravalterfinanc;
$pdf1->it14_valoravalconstrfinanc=(float)$it14_valoravalconstrfinanc;
$pdf1->it14_valoravalfinanc      =(float)$it14_valoravalfinanc;
$pdf1->it01_valortransacao       =@$it01_valortransacao;
$pdf1->it01_valorterreno         =@$it01_valorterreno;
$pdf1->it01_valorconstr          =@$it01_valorconstr;
$pdf1->it04_aliquotafinanc       =@$it04_aliquotafinanc;
$pdf1->it04_aliquota             =@$it04_aliquota;
$pdf1->it14_desc                 =@$it14_desc;
$pdf1->it14_valorpaga            =@$it14_valorpaga;
$pdf1->munic                     =$pdf1->munic == '' ? @$munic : $pdf1->munic;
$pdf1->it01_data                 =@$it01_data;
$pdf1->linha_digitavel           =@$linha_digitavel;
$pdf1->codigo_barras             =@$codigo_barras;
$pdf1->outrostransmitentes       =@$outrostransmitentes;
$pdf1->linhasitbiruralcaract     =@$linhasitbiruralcaract;
$pdf1->it01_obs                  =mb_strtoupper(@$sMsgObs);
$pdf1->arrayj13_descr            =@$arrayj13_descr;
$pdf1->arrayit19_valor           =@$arrayit19_valor;
$pdf1->aDadosRuralCaractDist     =$aDadosCaractDistr;
$pdf1->aDadosRuralCaractUtil     =$aDadosCaractUtil;
$pdf1->aDadosFormasPgto          =$aDadosFormasPgto;
$pdf1->it18_distcidade           =@$it18_distcidade;
$pdf1->it22_matricri             =@$iMatricri;
$pdf1->it22_quadrari             =@$iQuadrari;
$pdf1->it22_loteri               =@$iLoteri;
$pdf1->lLiberado                 =@$lLiberado;
$pdf1->dataemissao               =@$dtEmissao;
$pdf1->sMsgSituacaoImovel        =@$sMsgSituacao;
$pdf1->sMensagemRecibo           = $k00_msgrecibo;
$pdf1->sMensagemCaixa            = null;

$pdf1->lFrenteVia                =(isset($it18_nomelograd)&&trim($it18_nomelograd)!=""?"Sim":"Não");

$pdf1->mailcomprador             =@$mailcomprador;
$pdf1->mailtransmitente          =@$mailtransmitente;
$pdf1->usuarioNomeIncluido       =@$nomeUsuarioIncluido;
$pdf1->usuarioNomeLiberado       =@$nomeUsuarioLiberado;
$pdf1->usuarioCodigoLiberado     =@$codigoUsuarioLiberado;
$pdf1->nomeDepartamento          =@$nomeDepartamento;
$pdf1->dataLiberado              =@ db_formatar($dataLiberado , "d");

$pdf1->cnpjBeneficiario          = db_formatar($oStdInstituicao->cgc, 'cnpj');
$pdf1->enderecoInstituicao       = "{$oStdInstituicao->ender} - {$oStdInstituicao->munic}/{$oStdInstituicao->uf} - CEP: {$oStdInstituicao->cep}  ";

$pdf1->observacaoIncluido        =@ $sMsgObs;
$pdf1->observacaoLiberado        =@ $it14_obs;

$pdf1->adquirintes               = @$proprietarios;
$pdf1->transmitentes             = @$propri;

$pdf1->tipoguia                  = @$tipoguia;

$pdf1->lRetificado         = $lRetificado;

// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco

if ($lLiberado and $tipoguia != "q") {

  // Não imprime ficha compensação quando não estiver liberada
  if($oRegraEmissao->isCobranca()){

    $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
    $oBanco     = db_utils::fieldsMemory($rsConsultaBanco,0);
    $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
    $pdf1->banco      = $oBanco->db90_abrev;

    try{
      $pdf1->imagemlogo = $oConvenio->getImagemBanco();
    } catch (Exception $eExeption){
      db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
    }

  }
}

// propriedades referentes ao modelo 48
if(!empty($pdf1->it14_valorpaga)){
  $pdf1->valor_cobrado = db_formatar($pdf1->it14_valorpaga, "f");
}

if(!empty($pdf1->z01_cgccpf)){
  $pdf1->cgccpf = $pdf1->z01_cgccpf;
}
if(!empty($pdf1->z01_uf)){
  $pdf1->ufcgm = $pdf1->z01_uf;
}

//#############################################################
if ( $db21_codcli == 19985 or $db21_codcli == 18 or $db21_codcli == 74 /*or $db21_codcli == 15*/ ) {
  $pdf1->lUtilizaModeloDefault = false;
}

$pdf1->imprime();
$pdf1->objpdf->output();
