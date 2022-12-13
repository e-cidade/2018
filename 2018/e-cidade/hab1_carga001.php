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

  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_usuariosonline.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("dbforms/db_funcoes.php"));

  $db101_cargadados = false;

  db_postmemory($HTTP_POST_VARS);
  $mensagem = '';

  try {

    $oDaoAvaliacao    = new cl_avaliacao();
    $sSqlDaoAvaliacao = $oDaoAvaliacao->sql_query_file($db101_sequencial);
    $rsAvaliacao      = db_query($sSqlDaoAvaliacao);

    if (!$rsAvaliacao) {
      throw new DBException("Erro ao buscar dados da avaliação.");
    }

    if (pg_num_rows($rsAvaliacao) > 0) {
      db_fieldsmemory($rsAvaliacao, 0);
    }

    if (isset($_POST['db101_cargadados'])) {

      $sCargaDados = str_replace(array("\\", ';'), '', $_POST['db101_cargadados']);
      db_inicio_transacao();
      $rsCargaDados = db_query($sCargaDados);

      if (!$rsCargaDados) {
        throw new DBException("Instrução SQL inválida, por favor verifique a sintaxe da sua consulta.");
      }

      if (pg_num_rows($rsCargaDados) === 0) {

        db_fim_transacao(true);
        throw new BusinessException("INSERT, UPDATE e DELETE não permitido.");
      }

      db_fim_transacao(false);

      $oDaoAvaliacao = new cl_avaliacao();
      $oDaoAvaliacao->db101_sequencial = $db101_sequencial;
      $oDaoAvaliacao->db101_cargadados = $_POST["db101_cargadados"];
      $oDaoAvaliacao->alterar($db101_sequencial);

      $db101_cargadados = $sCargaDados;
      $mensagem = "Carga configurada com sucesso.";
    }
  } catch (Exception $e) {
    $mensagem = ($e->getMessage());
  }
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <form action="" method="post" class="container">
      <fieldset class="form-container">
        <legend>Carga de dados:</legend>
        <fieldset>
          <legend><label for="db101_cargadados">Consulta:</label></legend>
          <?php
    			  db_input('db101_sequencial', 10, '0', true, 'hidden', 3, "");
    			?>
          <textarea cols="150" rows="20"  name="db101_cargadados" id="db101_cargadados"><?=($db101_cargadados) ? $db101_cargadados : '' ?></textarea>
        </fieldset>
      </fieldset>
      <input type="submit" name="salvar" value="Salvar" />
    </form>
  </body>
</html>
<?php
if (!empty($mensagem)) {
  db_msgbox($mensagem);
}
