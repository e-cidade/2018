<?
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
require_once("classes/db_empprestaitem_classe.php");
require_once("classes/db_emppresta_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empprestaitemempagemov_classe.php");
require_once("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oGet = db_utils::postMemory($_GET);

$clempprestaitem          = new cl_empprestaitem;
$clemppresta              = new cl_emppresta;
$clempempenho             = new cl_empempenho;
$clempprestaitemempagemov = new cl_empprestaitemempagemov;

$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
}

if (isset($incluir)) {

  if (empty($e45_codmov)) {

    $erro_msg = _M("financeiro.empenho.emp1_empprestaitem001.movimento_nao_selecionado");
  } else if ($sqlerro == false) {


    $sSqlVerificaTotalItem = $clempprestaitem->sql_query_file( null, "coalesce(sum(e46_valor), 0) as valor ", null, "e46_numemp = {$e46_numemp}" );

    $rsTotalItens        = $clempprestaitem->sql_record($sSqlVerificaTotalItem);
    $nValorTotalItens    = db_utils::fieldsMemory($rsTotalItens, 0)->valor;
    $oEmpenhooFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($e46_numemp);
    if (($nValorTotalItens + $e46_valor) > $oEmpenhooFinanceiro->getValorEmpenho()) {

      $sqlerro  = true;
      $erro_msg = _M("financeiro.empenho.emp1_empprestaitem001.valor_prestacao_maior_empenho");
    }
    if (!$sqlerro) {

      db_inicio_transacao();

      $clempprestaitem->e46_id_usuario = db_getsession("DB_id_usuario");
      $clempprestaitem->e46_emppresta  = $oGet->e45_sequencial;
      $clempprestaitem->incluir(null);

      $erro_msg = $clempprestaitem->erro_msg;

      if ($clempprestaitem->erro_status == 0) {
        $sqlerro = true;
      }
      db_fim_transacao($sqlerro);
    }

  }
} else if(isset($alterar)) {
  if (empty($e45_codmov)) {
    $erro_msg = _M("financeiro.empenho.emp1_empprestaitem001.movimento_nao_selecionado");
  } else if ($sqlerro == false) {

    $sSqlVerificaTotalItem = $clempprestaitem->sql_query_file( null,
                                                               "coalesce(sum(e46_valor), 0) as valor ",
                                                               null,
                                                               "e46_numemp = {$e46_numemp} and e46_codigo <> {$e46_codigo}" );

    $rsTotalItens        = $clempprestaitem->sql_record($sSqlVerificaTotalItem);
    $nValorTotalItens    = db_utils::fieldsMemory($rsTotalItens, 0)->valor;
    $oEmpenhooFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($e46_numemp);
    if (($nValorTotalItens + $e46_valor) > $oEmpenhooFinanceiro->getValorEmpenho()) {

      $sqlerro  = true;
      $erro_msg = _M("financeiro.empenho.emp1_empprestaitem001.valor_prestacao_maior_empenho");
    }
    if (!$sqlerro) {

      db_inicio_transacao();
      $clempprestaitem->e46_emppresta = $oGet->e45_sequencial;
      $clempprestaitem->alterar($e46_codigo);
      $erro_msg = $clempprestaitem->erro_msg;

      if ($clempprestaitem->erro_status == 0) {
        $sqlerro = true;
      }


      db_fim_transacao($sqlerro);
    }
  }
} else if (isset($excluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();

    $clempprestaitem->excluir($e46_codigo);
    $erro_msg = $clempprestaitem->erro_msg;

    if ( $clempprestaitem->erro_status == 0) {
      $sqlerro=true;
    }

    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clempprestaitem->sql_record($clempprestaitem->sql_query_emp($e46_numemp,$e46_codigo));
   if($result!=false && $clempprestaitem->numrows>0){
     db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" style="margin-top: 30px;" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<?
	include("forms/db_frmempprestaitem.php");
	?>
</center>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
    db_msgbox($erro_msg);

    if ($clempprestaitem->erro_campo != "") {
        echo "<script> document.form1.".$clempprestaitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clempprestaitem->erro_campo.".focus();</script>";
    }
}
?>