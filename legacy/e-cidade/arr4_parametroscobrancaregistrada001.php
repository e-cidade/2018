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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("libs/db_utils.php"));

$clrotulo    = new rotulocampo();
$clrotulo->label('ar28_sequencial');
$clrotulo->label('ar28_usuario');

try {

  $oDaoParametrosCobrancaRegistrada      = new cl_parametroscobrancaregistrada();
  $sSqlDadosPArametrosCobrancaRegistada  = $oDaoParametrosCobrancaRegistrada->sql_query();
  $rsSqlDadosPArametrosCobrancaRegistada = db_query($sSqlDadosPArametrosCobrancaRegistada);

  if (!$rsSqlDadosPArametrosCobrancaRegistada) {
    throw new DBException("Ocorreu um erro ao buscar dados de configurações.");
  }

  if (pg_num_rows($rsSqlDadosPArametrosCobrancaRegistada)) {
    db_fieldsmemory($rsSqlDadosPArametrosCobrancaRegistada, 0);
  }

  db_postmemory($HTTP_POST_VARS);

  if (isset($salvar)) {

    $oDaoParametrosCobrancaRegistrada->ar28_sequencial = $ar28_sequencial;
    $oDaoParametrosCobrancaRegistrada->ar28_usuario = $ar28_usuario;

    if (empty($ar28_sequencial))  {
      $oDaoParametrosCobrancaRegistrada->incluir();
    } else {
      $oDaoParametrosCobrancaRegistrada->alterar($ar28_sequencial);
    }

    if ($oDaoParametrosCobrancaRegistrada->erro_status == '0') {
      throw new DBException("Ocorreu um erro ao alterar os dados de configurações.");
    }

    $sMensagem = "Usuário salvo com sucesso.";
  }
} catch (Exception $oException) {
  $sMensagem = $oException->getMessage();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>

  <form action="" method="post" class="container">
    <fieldset>
        <legend>Webservice - Caixa</legend>
        <table class="form-container">
          <tr>
              <td><label for="ar28_usuario">Usuário:</label></td>
              <td>
                <?php
                  db_input('ar28_sequencial',30,$Iar28_sequencial,true, 'hidden', 1);
                  db_input('ar28_usuario',30,$Iar28_usuario,true, 'text', 1);
                ?>
              </td>
          </tr>
        </table>
    </fieldset>
    <input type="submit" name="salvar" value="Salvar" />
  </form>
</center>

<?php
  db_menu();
  if (isset($sMensagem)) {
    db_msgbox($sMensagem);
  }
?>
</body>
</html>
