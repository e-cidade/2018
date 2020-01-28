<?php

/**
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_parissqn_classe.php");
require_once("classes/db_isstipoalvara_classe.php");
require_once("classes/db_meiimporta_classe.php");
require_once("classes/db_certbaixanumero_classe.php");
require_once("classes/db_db_documentotemplate_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($_POST);

$clparissqn             = new cl_parissqn();
$clMeiImporta           = new cl_meiimporta();
$clTipoAlvara           = new cl_isstipoalvara();
$clcertbaixanumero      = new cl_certbaixanumero();
$cldb_documentotemplate = new cl_db_documentotemplate();

$db_opcao = 2;
$db_botao = false;
$iAnousu  = db_getsession('DB_anousu');

if (isset($alterar)) {

  $sql_erro = false;
  $sMsgErro = '';

  db_inicio_transacao();

  if (trim($q60_templatebaixaalvaranormal) == '') {

    $sMsgErro                = "Usuário:\\n\\n Campo Template Baixa Normal não informado.\\n\\nAdministrador: \\n\\n ";
    $clparissqn->erro_msg    = "usuário:\\n\\n Campo Template Baixa Normal não informado.\\n\\nAdministrador: \\n\\n ";
    $clparissqn->erro_status = "0";
    $sql_erro                = true;
  }

  if ($q60_modalvara == 9) {

    if (trim($q60_templatealvara) == '') {

      $sMsgErro                = "Usuário:\\n\\n Campo Documento Alvará não informado.\\n\\nAdministrador: \\n\\n ";
      $clparissqn->erro_msg    = "usuário:\\n\\n Campo Documento Alvará não informado.\\n\\nAdministrador: \\n\\n ";
      $clparissqn->erro_status = "0";
      $sql_erro                = true;
    }
  } else {
    $clparissqn->q60_templatealvara = "null";
  }

  if (empty($q60_parcelasalvara)) {

    $sMsgErro                = "Campo Limite Parcelas Alvará não pode ser 0 ou em branco.";
    $clparissqn->erro_msg    = $sMsgErro;
    $clparissqn->erro_status = "0";
    $clparissqn->erro_campo  = "q60_parcelasalvara";
    $sql_erro                = true;
  }

  if (!$sql_erro) {

    $result = $clparissqn->sql_record($clparissqn->sql_query(null, "q60_dataimpmei"));
    if ($result == false || $clparissqn->numrows == 0) {

      $clparissqn->incluir();
      $sMsgErro = $clparissqn->erro_msg;
    } else {

      $oParIssqn = db_utils::fieldsMemory($result, 0);
      $rsMei     = $clMeiImporta->sql_record($clMeiImporta->sql_query_file());
      if ($clMeiImporta->numrows > 0) {

        if ($oParIssqn->q60_dataimpmei != implode("-", array_reverse(explode("/", $q60_dataimpmei)))) {

          $sql_erro  = true;
          $sMsgErro  = "Não é possível alterar a data de implantação do MEI";
          $sMsgErro .= "\\nExistem registros já lançados!";
        }
      }

      if (!$sql_erro) {

        if ($q60_dataimpmei == "") {
          $clparissqn->q60_dataimpmei = 'NULL';
        } else {
          $clparissqn->q60_dataimpmei = $q60_dataimpmei;
        }

        $clparissqn->alterarParametro();
        $sMsgErro = $clparissqn->erro_msg;
      }
    }

    /**
     * Altera ou inclui na certbaixanumero
     */
    if (($q60_tiponumcertbaixa == 2 || $q60_tiponumcertbaixa == 3) && !empty($q79_ultcodcertbaixa)) {

      $sSqlCertBaixaNumero = $clcertbaixanumero->sql_query(null, "q79_sequencial", null, "q79_anousu = {$iAnousu}");
      $rsCertBaixaNumero   = $clcertbaixanumero->sql_record($sSqlCertBaixaNumero);

      /**
       * Já existe registo na sequencial a rotina deve alterar o valor
       */
      if ($rsCertBaixaNumero != false && $clcertbaixanumero->numrows > 0) {

        db_fieldsmemory($rsCertBaixaNumero, 0);

        $clcertbaixanumero->q79_sequencial      = $q79_sequencial;
        $clcertbaixanumero->q79_anousu          = $iAnousu;
        $clcertbaixanumero->q79_ultcodcertbaixa = $q79_ultcodcertbaixa;
        $clcertbaixanumero->alterar($clcertbaixanumero->q79_sequencial);
      } else {

        $clcertbaixanumero->q79_anousu          = $iAnousu;
        $clcertbaixanumero->q79_ultcodcertbaixa = $q79_ultcodcertbaixa;
        $clcertbaixanumero->incluir(null);
      }
    }
  }

  /**
   * Altera os parâmetros da planilha para ISSQN Váriavel
   */
  $oDaoConfVencISSQNVariavel    = db_utils::getDao('confvencissqnvariavel');
  $sWhere                       = "q144_ano = {$iAnousu}";
  $sSqlConfVencISSQNVariavel    = $oDaoConfVencISSQNVariavel->sql_query_file(null, "*", null, $sWhere);
  $rsSqlConfVencISSQNVariavel   = $oDaoConfVencISSQNVariavel->sql_record($sSqlConfVencISSQNVariavel);
  $iLinhasConfVencISSQNVariavel = $oDaoConfVencISSQNVariavel->numrows;
  if ($iLinhasConfVencISSQNVariavel > 0) {

    $oConfVencISSQNVariavel = db_utils::fieldsMemory($rsSqlConfVencISSQNVariavel, 0);

    $oDaoConfVencISSQNVariavel->q144_sequencial = $oConfVencISSQNVariavel->q144_sequencial;
    $oDaoConfVencISSQNVariavel->q144_ano        = $oConfVencISSQNVariavel->q144_ano;
    $oDaoConfVencISSQNVariavel->q144_codvenc    = $q60_codvencvar;
    $oDaoConfVencISSQNVariavel->q144_receita    = $q60_receit;
    $oDaoConfVencISSQNVariavel->q144_tipo       = $q60_tipo;
    $oDaoConfVencISSQNVariavel->q144_hist       = $q60_histsemmov;
    $oDaoConfVencISSQNVariavel->q144_diavenc    = $oConfVencISSQNVariavel->q144_diavenc;
    $oDaoConfVencISSQNVariavel->q144_valor      = $oConfVencISSQNVariavel->q144_valor;
    $oDaoConfVencISSQNVariavel->alterar($oConfVencISSQNVariavel->q144_sequencial);

    if ($oDaoConfVencISSQNVariavel->erro_status == '0') {

      $clparissqn->erro_msg    = $oDaoConfVencISSQNVariavel->erro_msg;
      $clparissqn->erro_campo  = $oDaoConfVencISSQNVariavel->erro_campo;
      $clparissqn->erro_status = '0';
      $sql_erro                = true;
      $sMsgErro                = $clparissqn->erro_msg;
    }
  }

  db_fim_transacao($sql_erro);
} else {

  $db_opcao = 2;
  $result   = $clparissqn->sql_record($clparissqn->sql_query());
  if ($result != false && $clparissqn->numrows > 0) {
    db_fieldsmemory($result, 0);
  }

  $rsTipoAlvara = $clTipoAlvara->sql_record($clTipoAlvara->sql_query($q60_isstipoalvaraprov,
                                                                     "q98_descricao as q98_descricaoprov"));
  if ($rsTipoAlvara != false && $clTipoAlvara->numrows > 0) {
    db_fieldsmemory($rsTipoAlvara, 0);
  }

  $rsTipoAlvara = $clTipoAlvara->sql_record($clTipoAlvara->sql_query($q60_isstipoalvaraper,
                                                                     "q98_descricao as q98_descricaoper"));
  if ($rsTipoAlvara != false && $clTipoAlvara->numrows > 0) {
    db_fieldsmemory($rsTipoAlvara, 0);
  }

  /**
   * Retorna a descricao do documento template da certidao normal
   */
  if (isset($q60_templatebaixaalvaranormal) && $q60_templatebaixaalvaranormal != "") {

    $sSqlCertidaoBaixaNormal = $cldb_documentotemplate->sql_query_file(null, 'db82_descricao as db82_descricaocertidaonormal',
                                                                       null, "db82_sequencial = {$q60_templatebaixaalvaranormal} and db82_templatetipo = 46");
    $rsCertidaoBaixaNormal   = $cldb_documentotemplate->sql_record($sSqlCertidaoBaixaNormal);
    if ($rsCertidaoBaixaNormal != false && $cldb_documentotemplate->numrows > 0) {
      db_fieldsmemory($rsCertidaoBaixaNormal, 0);
    }
  }

  /**
   * Retorna a descricao do documento template da certidao oficial
   */
  if (isset($q60_templatebaixaalvaraoficial) && $q60_templatebaixaalvaraoficial != "") {

    $sSqlCertidaoBaixaOficial = $cldb_documentotemplate->sql_query_file(null, 'db82_descricao as db82_descricaocertidaooficial',
                                                                        null, "db82_sequencial = {$q60_templatebaixaalvaraoficial} and db82_templatetipo = 46");
    $rsCertidaoBaixaOficial   = $cldb_documentotemplate->sql_record($sSqlCertidaoBaixaOficial);
    if ($rsCertidaoBaixaOficial != false && $cldb_documentotemplate->numrows > 0) {
      db_fieldsmemory($rsCertidaoBaixaOficial, 0);
    }
  }

  /**
   * Busca o sequencial da certbaixanumero
   */
  $sSqlCertBaixaNumero = $clcertbaixanumero->sql_query(null, "*", null, "q79_anousu={$iAnousu}");
  $rsCertBaixaNumero   = $clcertbaixanumero->sql_record($sSqlCertBaixaNumero);
  if ($rsCertBaixaNumero != false && $clcertbaixanumero->numrows > 0) {
    db_fieldsmemory($rsCertBaixaNumero, 0);
  }
}

$db_botao = true;
?>
<html>
  <head>
  <?php
    db_app::load('scripts.js, prototype.js, strings.js, DBHint.widget.js');
    db_app::load('estilos.css');
  ?>
  </head>
  <body class="body-default">
	<?php
	  include("forms/db_frmparissqn.php");

    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<?
if(isset($alterar)){
  if( $sql_erro ){
  	db_msgbox($sMsgErro);

  	if ($sMsgErro == '') {
      $clparissqn->erro(false,true);
  	}
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clparissqn->erro_campo!=""){
      echo "<script> document.form1.".$clparissqn->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparissqn->erro_campo.".focus();</script>";
    }
  }else{
    $clparissqn->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>