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

define('MENSAGEM', 'tributario.issqn.db_frmissbaseparalisacao.');
$clissbaseparalisacao = new cl_issbaseparalisacao;
$oPost    = db_utils::postMemory($_POST);
$incluir  = isset($oPost->incluir) ? $oPost->incluir : null;
$db_opcao = 1;
$db_botao = true;

if ($incluir) {

  try {

    if (!$oPost->q140_issbase) {
      throw new ParameterException( _M(MENSAGEM . 'erro_inscricao'));
    }

    $oParalisacaoEmpresa = new ParalisacaoEmpresa();

    $oParalisacaoEmpresa->setEmpresa( new Empresa($oPost->q140_issbase));
    $oParalisacaoEmpresa->setMotivo ( $oPost->q140_issmotivoparalisacao);

    if (!empty($oPost->q140_datainicio)) {
      $oParalisacaoEmpresa->setDataInicio( new DBDate($oPost->q140_datainicio));
    }

    if (!empty($oPost->q140_datafim)) {
      $oParalisacaoEmpresa->setDataFim(    new DBDate($oPost->q140_datafim));
    }

    $oParalisacaoEmpresa->setObservacao( $oPost->q140_observacao);

    db_inicio_transacao();
    $oParalisacaoEmpresa->salvar();
    db_msgbox(_M(MENSAGEM . 'incluir'));
    db_fim_transacao(false);
    db_redireciona('iss1_issbaseparalisacao001.php');

  } catch (Exception $eErro) {

    db_msgbox($eErro->getMessage());
    db_fim_transacao(true);

  }
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css"); ?>
  </head>

  <body class="body-default">
  	<?php
  	  require_once("forms/db_frmissbaseparalisacao.php");
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

  </body>
</html>

<script>
  js_tabulacaoforms("form1","q140_issbase",true,1,"q140_issbase",true);
</script>
<?php
  /** Extensao : Inicio [BloqueioManutencaoInscricaoSistemaExterno] */
  /** Extensao : Fim [BloqueioManutencaoInscricaoSistemaExterno] */
?>