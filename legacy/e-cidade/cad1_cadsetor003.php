<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

$clsetor  = new cl_setor();
$clcfiptu = new cl_cfiptu();

$db_opcao = 3;
$db_botao = false;
$lExcluiu = false;

if(isset($_POST["db_opcao"]) && $_POST["db_opcao"] == "Excluir") {

  $sSqlVinculosSetor = $clsetor->vinculosSetor(
    array('j37_setor', 'j34_setor', 'j141_setor'),
    array("j30_codi = '{$j30_codi}'")
  );

  $rsVinculosSetor = db_query($sSqlVinculosSetor);

  if($rsVinculosSetor && pg_num_rows($rsVinculosSetor) > 0) {

    $oVinculosSetor = db_utils::fieldsMemory($rsVinculosSetor, 0);

    if(!empty($oVinculosSetor->j37_setor) || !empty($oVinculosSetor->j34_setor) || !empty($oVinculosSetor->j141_setor)) {

      db_msgbox('Exclusão não permitida! Setor já está sendo utilizado.');
      db_redireciona('cad1_cadsetor003.php');
    }
  }

  db_inicio_transacao();

  $clsetor->excluir($j30_codi);
  $lExcluiu = true;

  db_fim_transacao();
}

if(isset($chavepesquisa) && !$lExcluiu) {

  $sSqlSetor = $clsetor->sql_query($chavepesquisa);
  $result    = db_query($sSqlSetor);

  if($result && pg_num_rows($result) > 0) {

    db_fieldsmemory($result,0);
    $db_botao = true;
  }
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
<script>
function js_load_setor() {
  <?php
  if(!isset($chavepesquisa)) {
    echo "js_pesquisa()";
  }
  ?>
}
</script>

<body class="body-default">
	<?php
	include(modification("forms/db_frmsetor.php"));
  db_menu();
  ?>
</body>
</html>
<?php
$clsetor->erro(true,false);

if($lExcluiu) {
  db_redireciona('cad1_cadsetor003.php');
}