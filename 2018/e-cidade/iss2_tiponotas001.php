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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_AbreJanelaRelatorio() {
	itemselecionado = 0;
	numElems = document.form1.ordem_relatorio.length;
	for (i=0;i<numElems;i++) {
	  if (document.form1.ordem_relatorio[i].checked) itemselecionado = i;
	}
	relatorio = document.form1.opcao_relatorio.value;
	ordem = document.form1.ordem_relatorio[itemselecionado].value;
    jan = window.open('iss2_tiponotas002.php?opcaoRelatorio='+relatorio+'&opcaoOrdem='+ordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	jan.moveTo(0,0);
	}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">

<form name="form1" method="post">

<table style="width:530px;" >
<tr>
<td align="center">
  <table width="100%" border="1" >
    <tr align="center">
      <td><strong>Relatório</strong></td>
      <td colspan="2"><strong>Ordem</strong></td>
    </tr>
    <tr>
      <td> <input type="radio" name="opcao_relatorio" value="tiponotas" checked>
        &nbsp;Relatório dos Tipos de Notas</td>
      <td width="22%"> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
        &nbsp; Alfabética&nbsp;</td>
      <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
        Numérica&nbsp;</td>
    </tr>
  </table><br/>
  <input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()"/>
  </form>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>