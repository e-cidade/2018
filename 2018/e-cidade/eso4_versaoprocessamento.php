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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\RecursosHumanos\ESocial\Model\Migracao;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/ProgressBar.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body >
<div class="container">
  <fieldset style="width: 700px; padding: 2px">
    <progress id="barra-progresso" value="0" style="width: 100%; height: 25px;">Processando</progress>
  </fieldset>
  <fieldset style="width: 700px; padding: 1px 2px">
    <div id="log-processamento"></div>
  </fieldset>
</div>
<?php
  db_menu();
?>
</body>
<script type='text/javascript'>
</script>

<script type="text/javascript">
    var bar = $('barra-progresso');
    var logs = $('log-processamento');
    var progress = new ProgressBar(bar, logs);

    function fimProcessamento() {
      alert("Atualização finalizada com sucesso!");
      location.href = 'eso4_versao001.php'
    }
</script>
<?php

db_inicio_transacao();

try {
    $progressBar = new ProgressBar('progress');
    $migracao = new Migracao();
    $migracao->setUsuario(db_getsession("DB_id_usuario"));
    $migracao->migrar($_POST['versao'], $progressBar);

    db_fim_transacao(false);
    echo "<script>fimProcessamento();</script>";
} catch (Exception $e) {
    echo $e->getMessage();
    db_fim_transacao(true);
}
