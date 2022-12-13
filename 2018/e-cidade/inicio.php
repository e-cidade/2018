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
require_once(modification("classes/db_db_versao_classe.php"));
require_once(modification("model/configuracao/SkinService.service.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldb_versao = new cl_db_versao;
$result      = $cldb_versao->sql_record($cldb_versao->sql_query(null,"db30_codversao,db30_codrelease","db30_codver desc limit 1"));

if ( $cldb_versao->numrows == 0 ) {

  $db30_codversao  = "1";
  $db30_codrelease = "1";
} else {
  db_fieldsmemory($result,0);
}

/**
 * Salva o skin no cookie
 */
$oSkin = new SkinService();
$oSkin->setCookie();

db_logsmanual_demais("Acesso Liberado ao sistema - Login: " . db_getsession("DB_login"), db_getsession("DB_id_usuario"));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - e-cidade</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <meta name="ecidade:version" content="2.<?php echo "{$db30_codversao}.{$db30_codrelease}"; ?>" />
    <script>

      function js_fechaJanela() {
        window.open('encerrar.php', '', 'width=400,height=400');
      }
    </script>
  </head>
  <?php
    $oSkin = new SkinService();
    include(modification( $oSkin->getPathFile("inicio.php")) );
  ?>
</html>