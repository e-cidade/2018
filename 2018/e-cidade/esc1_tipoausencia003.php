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
require_once(modification("classes/db_tipoausencia_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$cltipoausencia = new cl_tipoausencia;
$cltipoausencia->rotulo->label();
$db_botao       = false;
$db_opcao       = 33;

$oDaoAusencia = new cl_rechumanoausente();
try {

  if (isset($excluir)) {

    $sWhere       = " ed348_tipoausencia = {$chavepesquisa} ";
    $sSqlAusencia = $oDaoAusencia->sql_query_file(null, "1", null, $sWhere);
    $rsAusencia   = db_query($sSqlAusencia);
    if ( !$rsAusencia ) {
      throw new DBException("Erro ao executar query:\n".pg_last_error());
    }
    if ( pg_num_rows($rsAusencia) > 0 ) {
      throw new BusinessException("Existe uma ou mais ausência informada a este Tipo de Ausência. Você não pode excluí-la.");
    }

    db_inicio_transacao();
    $db_opcao = 3;
    $cltipoausencia->excluir($ed320_sequencial);
    db_fim_transacao();
  } else if(isset($chavepesquisa)) {

    $db_opcao = 3;
    $result   = $cltipoausencia->sql_record($cltipoausencia->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
    $db_botao = true;
  }
} catch(Exception $e) {

  db_fim_transacao(true);
  db_msgbox($e->getMessage());
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body >
  <div class="container">
    <?php
      include(modification("forms/db_frmtipoausencia.php"));
    ?>
  </div>

<?php
  db_menu();
?>
</body>
</html>
<?php
if (isset($excluir)) {

  if ($cltipoausencia->erro_status == "0") {
    $cltipoausencia->erro(true,false);
  } else {
    $cltipoausencia->erro(true,true);
  }
}
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>