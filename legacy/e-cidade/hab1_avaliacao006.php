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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_avaliacao_classe.php"));
require_once(modification("classes/db_avaliacaotipo_classe.php"));
require_once(modification("classes/db_avaliacaopergunta_classe.php"));
require_once(modification("classes/db_avaliacaogrupopergunta_classe.php"));

db_postmemory($HTTP_POST_VARS);

$clavaliacao                      = new cl_avaliacao;
$clavaliacaotipo                  = new cl_avaliacaotipo;
$clavaliacaopergunta              = new cl_avaliacaopergunta;
$clavaliacaogrupopergunta         = new cl_avaliacaogrupopergunta;
$clavaliacaoperguntaopcao         = new cl_avaliacaoperguntaopcao;
$clavaliacaoresposta              = new cl_avaliacaoresposta;
$clavaliacaogrupoperguntaresposta = new cl_avaliacaogrupoperguntaresposta;

$db_opcao = 33;
$db_botao = false;


/**
 * Se o formulário for carregado pela módulo do e-Social
 * deve dar manutenção apenas em avaliações do tipo e-Social
 */
$db_opcao_tipoAvaliacao   = $db_opcao;

if(isset($iTipoAvaliacao) && $iTipoAvaliacao == 5) {
  $db_opcao_tipoAvaliacao = 3;
  $db101_avaliacaotipo    = 5;
}

if (isset($excluir)) {

  try {

    $sqlerro = false;
    db_inicio_transacao();

    $sSqlAvaliacaoGrupoPergunta = $clavaliacaogrupopergunta->sql_query_file(null, "db102_sequencial", null, "db102_avaliacao = $db101_sequencial");
    $sSqlAvalicaoPergunta       = $clavaliacaopergunta->sql_query_file(null, 'db103_sequencial', null, "db103_avaliacaogrupopergunta in ($sSqlAvaliacaoGrupoPergunta)");
    $sSqlAvalicaoPerguntaopcao  = $clavaliacaoperguntaopcao->sql_query_file(null, "db104_sequencial", null, "db104_avaliacaopergunta in ($sSqlAvalicaoPergunta)");
    $sSqlAvalicaoResposta       = $clavaliacaoresposta->sql_query_file(null, "db106_sequencial", null, "db106_avaliacaoperguntaopcao in ($sSqlAvalicaoPerguntaopcao)");


    $clavaliacaogrupoperguntaresposta->excluir(null, "db108_avaliacaoresposta in ($sSqlAvalicaoResposta)");

    if ($clavaliacaogrupoperguntaresposta->erro_status == "0") {
      throw new DBException($clavaliacaogrupoperguntaresposta->erro_msg);
    }


    $clavaliacaoresposta->excluir(null, "db106_avaliacaoperguntaopcao in ($sSqlAvalicaoPerguntaopcao)");

    if ($clavaliacaoresposta->erro_status == "0") {
      throw new DBException($clavaliacaoresposta->erro_msg);
    }

    $clavaliacaoperguntaopcao->excluir(null, "db104_avaliacaopergunta in ($sSqlAvalicaoPergunta)");

    if ($clavaliacaoperguntaopcao->erro_status == "0") {
      throw new DBException($clavaliacaoperguntaopcao->erro_msg);
    }

    $clavaliacaopergunta->excluir(null, "db103_avaliacaogrupopergunta in ($sSqlAvaliacaoGrupoPergunta)");

    if ($clavaliacaopergunta->erro_status == "0") {
      throw new DBException($clavaliacaopergunta->erro_msg);
    }

    $clavaliacaogrupopergunta->excluir(null, "db102_avaliacao = $db101_sequencial");

    if ($clavaliacaogrupopergunta->erro_status == "0") {
      throw new DBException($clavaliacaogrupopergunta->erro_msg);
    }

    $clavaliacao->excluir($db101_sequencial);
    if ($clavaliacao->erro_status == 0) {
      throw new DBException($clavaliacao->erro_msg);
    }

    db_fim_transacao(false);
    $db_opcao = 3;
    $db_botao = true;

    $mensagem = "Formulário excluido com sucesso.";
  } catch (Exception $exception) {

    db_fim_transacao(true);
    $sqlerro = true;
    $mensagem = $exception->getMessage();
  }
} else if (isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $clavaliacao->sql_record($clavaliacao->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
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
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 120px;
              white-space: nowrap
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#CCCCCC">
    <center>
      <?php
        include(modification("forms/db_frmavaliacao.php"));
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?php

if (!empty($mensagem)) {
  db_msgbox($mensagem);
}

if (isset($excluir)) {

	 echo "
	  <script>
	    function js_db_tranca(){
	      parent.location.href='hab1_avaliacao003.php';
	    }\n
	    js_db_tranca();
	  </script>\n
	 ";
}

if (isset($chavepesquisa)) {

  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.avaliacaogrupopergunta.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_avaliacaogrupopergunta.location.href='hab1_avaliacaogrupopergunta001.php?db_opcaoal=33&db102_avaliacao=".@$db101_sequencial."';
     ";

  if (isset($liberaaba)) {
    echo "  parent.mo_camada('avaliacaopergunta');";
  }

  echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
