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
require_once("classes/db_emppresta_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clemppresta = new cl_emppresta;
$oGet = db_utils::postMemory($_GET);

$db_opcao = 2;
$db_botao = true;

if (isset($atualizar) ) {

  db_inicio_transacao();

  $sqlerro = false;

  $oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($e60_numemp);
  $oPrestacaoContas = new PrestacaoConta($oEmpenhoFinanceiro, $oGet->e45_sequencial);
  if ( count($oPrestacaoContas->getItens()) == 0) {

    $sqlerro = true;
    $erro_msg = "Nenhum item lançado na prestação de contas.";
  } else {

    $clemppresta->e45_numemp     = $e60_numemp;
    $clemppresta->e45_sequencial = $oGet->e45_sequencial;
    $clemppresta->alterar($oGet->e45_sequencial);

    $erro_msg = $clemppresta->erro_msg;

    if($clemppresta->erro_status==0){
      $sqlerro=true;
    }
  }
  db_fim_transacao($sqlerro);
}

if (isset($oGet->e45_sequencial)) {
  $result = $clemppresta->sql_record( $clemppresta->sql_query_file($oGet->e45_sequencial,"e45_acerta") );

  db_fieldsmemory($result,0);

  if ($e45_acerta == '' ) {
    $db_opcao = 1;
  } else {
    $db_opcao = 2;
  }
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
<body bgcolor=#CCCCCC leftmargin="0" style="margin-top: 30px" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<?php
	require_once("forms/db_frmempprestaencerra.php");
	?>
</center>
</body>
</html>
<?php
if(isset($atualizar)){
    db_msgbox($erro_msg);
    echo "<script> parent.location.href='emp1_emppresta002.php'</script>";
}
?>