<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_face_classe.php");
require_once("classes/db_carface_classe.php");
require_once("classes/db_cfiptu_classe.php");
require_once("classes/db_facevalor_classe.php");

$clface      = db_utils::getDao('face');
$clcarface   = db_utils::getDao('carface');
$clcfiptu    = db_utils::getDao('cfiptu');
$clfacevalor = db_utils::getDao('facevalor');

db_postmemory($HTTP_POST_VARS);

$db_opcao = 22;
$db_botao = false;

if (isset($alterarface) && $alterarface=='t') {

	$sqlerro = false;
	db_inicio_transacao();

	$sqlverificaruas = " select x.j36_face
		                        from (select testada.j36_face,
														             testada.j36_codigo as lograd1,
																				 face.j37_codigo as lograd2
	         	                        from testada
					                               inner join face on testada.j36_face = face.j37_face
								         where $j37_codigo  <> face.j37_codigo
												   and face.j37_face = $j37_face ) as x";

	$rsverificaruas = db_query($sqlverificaruas);
	$intnumrows     = pg_numrows($rsverificaruas);
	if (isset ($intnumrows) && $intnumrows > 0) {

		$confirm     = 't';
		$podealterar = 't';

		db_inicio_transacao();

		$sqltestada = "update testada set j36_codigo = $j37_codigo where j36_face = $j37_face";
		db_query($sqltestada);
		$sqltestpri = "update testpri set j49_codigo = $j37_codigo where j49_face = $j37_face";
		db_query($sqltestpri);

		db_fim_transacao();
	} else {
		$podealterar = 't';
	}

	if (isset ($podealterar) && $podealterar == 't') {

		db_inicio_transacao();
		$rsCarFace = $clcarface->sql_record($clcarface->sql_query($j37_face, null, "*", "j31_grupo"));

		$db_opcao = 2;
		$clcarface->j38_face = $j37_face;
		$clcarface->excluir($j37_face);

		if ($clcarface->erro_status == 0) {

			$sqlerro  = true;
			$erro_msg = $clcarface->erro_msg;
		}

		$matriz = split("X", $caracteristica);

		for ($i = 0; $i < sizeof($matriz); $i++) {

			$oCarFace = db_utils::fieldsMemory($rsCarFace, $i - 1);

			$j38_caract = $matriz[$i];
			if ($j38_caract != "") {

				/**
				 * Caso haja alteraçao de caracteristica, alterar a data para a data atual do usuario
				 * Senao, mantem a que ja estava
				 */
				$clcarface->j38_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));



				if ($j38_caract == $oCarFace->j38_caract) {
					$clcarface->j38_datalancamento = $oCarFace->j38_datalancamento;
				}

				$clcarface->incluir($j37_face, $j38_caract);
				if ($clcarface->erro_status == 0) {

					$sqlerro = true;
					$erro_msg = $clcarface->erro_msg;
				}
			}
		}

		$j37_quadra = str_pad($j37_quadra, 4, "0", STR_PAD_LEFT);
		$clface->j37_quadra = $j37_quadra;
		$clface->alterar($j37_face);
		if ($clface->erro_status == 0) {

			$sqlerro = true;
			$erro_msg = $clface->erro_msg;
		}

		if ($sqlerro == false) {
			$erro_msg = $clface->erro_msg;
		}
		db_fim_transacao($sqlerro);
	}

	$db_opcao = 2;
	$db_botao = true;
} else if (isset ($chavepesquisa)) {

	$result = $clface->sql_record($clface->sql_query($chavepesquisa));
	db_fieldsmemory($result, 0);

	$result = $clcarface->sql_record($clcarface->sql_query($chavepesquisa));
	$caracteristica = null;
	$car            = "X";
	for ($i = 0; $i < $clcarface->numrows; $i++) {

		db_fieldsmemory($result, $i);
		$caracteristica .= $car . $j38_caract;
		$car = "X";
	}

	$caracteristica .= $car;
	$db_opcao = 2;
	$db_botao = true;
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
<body class="body-default abas">
	<div class="container">
		<?php
			include ("forms/db_frmface.php");
		?>
  </div>
</body>
</html>
<?php

if (isset ($alterarface)) {

	if ($sqlerro == true) {

		db_msgbox($erro_msg);
		if ($clface->erro_campo != "") {

			echo "<script> document.form1." . $clface->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1." . $clface->erro_campo . ".focus();</script>";
		};
	} else {
		db_msgbox($erro_msg);
	}
}

if (isset ($chavepesquisa)) {

	echo "
	  <script>
	      function js_db_libera(){

	         parent.document.formaba.facevalor.disabled=false;
	         top.corpo.iframe_facevalor.location.href='cad1_facevalor001.php?j81_face=" . @ $j37_face . "';
	     ";
	if (isset ($liberaaba)) {
		echo "  parent.mo_camada('facevalor');";
	}
	echo "}\n
	    js_db_libera();
	  </script>\n
	 ";
}
if ($db_opcao == 22 || $db_opcao == 33) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>