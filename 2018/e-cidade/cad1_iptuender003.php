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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoIptuEnder = new cl_iptuender();
$db_botao = false;
$db_opcao = 33;

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoIptuEnder->excluir($j43_matric);
  db_fim_transacao();

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $rsEndereco = $oDaoIptuEnder->sql_record( $oDaoIptuEnder->sql_query($chavepesquisa) );

  if (!empty($rsEndereco)) {

    db_fieldsmemory($rsEndereco, 0);
    $db_botao = true;

    /**
     * Verifica se o endereço selecionado é do municipio
     */
    $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

    if (empty($oPrefeitura)) {
      $oPrefeitura = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
    }

    $lMunicipio = mb_strtoupper($oPrefeitura->getUf()) == $j43_uf && mb_strtoupper($oPrefeitura->getMunicipio()) == mb_strtoupper($j43_munic);
    $iEnderecoMunicipio = $lMunicipio ? 1 : 0;
  }
}

$oDaoIptuEnder->rotulo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  	<?php
      include modification("forms/db_frmiptuender.php");
      db_menu();
    ?>
    <script type="text/javascript">
      js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
    </script>
  </body>
</html>
<?php
  if (isset($excluir)) {

    if ($oDaoIptuEnder->erro_status == "0") {
      $oDaoIptuEnder->erro(true, false);
    } else {
      $oDaoIptuEnder->erro(true, true);
    }
  }

  if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>