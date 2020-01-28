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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("model/licitacao.model.php");

db_postmemory($HTTP_POST_VARS);

$clpcorcamforne               = new cl_pcorcamforne;
$clpcorcamfornelic            = new cl_pcorcamfornelic;
$clpcorcam                    = new cl_pcorcam;
$clpcorcamitem                = new cl_pcorcamitem;
$clpcorcamitemlic             = new cl_pcorcamitemlic;
$clliclicita                  = new cl_liclicita;
$clliclicitem                 = new cl_liclicitem;
$clpcorcamval                 = new cl_pcorcamval;
$oDaoTipoEmpresa              = new cl_liclicitatipoempresa;
$oDaoPcorcamjulgamentologitem = new cl_pcorcamjulgamentologitem;

$db_opcao = 2;
$db_botao = true;
$op       = 11;

if (isset ($alterar) || isset ($excluir) || isset ($incluir) || isset ($verificado)) {
	$sqlerro = false;
	$clpcorcamforne->pc21_orcamforne = $pc21_orcamforne;
	$clpcorcamforne->pc21_codorc = $pc20_codorc;
	$clpcorcamforne->pc21_numcgm = $pc21_numcgm;
	$clpcorcamforne->pc21_importado = '0';
}

if (isset ($incluir)) {


	db_inicio_transacao();

	$result = $clliclicita->sql_record($clliclicita->sql_query_pco($l20_codigo));

	if ($clliclicita->numrows == 0) {

		$result_dt = $clliclicita->sql_record($clliclicita->sql_query_file($l20_codigo));
		db_fieldsmemory($result_dt, 0);
		$clpcorcam->pc20_dtate = $l20_dataaber;
		$clpcorcam->pc20_hrate = $l20_horaaber;
		$clpcorcam->incluir(null);
		$pc20_codorc = $clpcorcam->pc20_codorc;
		if ($clpcorcam->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $clpcorcam->erro_msg;
		}
		if ($sqlerro == false) {
			$result_itens = $clliclicitem->sql_record($clliclicitem->sql_query_file(null, "l21_codigo", null, "l21_codliclicita=$l20_codigo and l21_situacao=0"));
			if ($clliclicitem->numrows==0){
			  echo "<script>
			           alert('Impossivel incluir fornecedores!!Licitação sem itens cadastrados!!');
				   location.href='lic1_fornec001.php';
			        </script>";
			  exit;
			}

			for ($w = 0; $w < $clliclicitem->numrows; $w ++) {
				db_fieldsmemory($result_itens, $w);
				if ($sqlerro == false) {
					$clpcorcamitem->pc22_codorc = $pc20_codorc;
					$clpcorcamitem->incluir(null);
					$pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;
					if ($clpcorcamitem->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clpcorcamitem->erro_msg;
					}
				}
				if ($sqlerro == false) {
					$clpcorcamitemlic->pc26_orcamitem = $pc22_orcamitem;
					$clpcorcamitemlic->pc26_liclicitem = $l21_codigo;
					$clpcorcamitemlic->incluir();
					if ($clpcorcamitemlic->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clpcorcamitemlic->erro_msg;
					}
				}
			}
		}
	}
	$result_igualcgm = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null, "pc21_codorc", "", " pc21_numcgm=$pc21_numcgm and pc21_codorc=$pc20_codorc"));
	if ($clpcorcamforne->numrows > 0) {
		$sqlerro = true;
		$erro_msg = "ERRO: Número de CGM já cadastrado.";
	}

	if ($sqlerro == false) {
		$clpcorcamforne->pc21_codorc = $pc20_codorc;
		$clpcorcamforne->incluir($pc21_orcamforne);
		$pc21_orcamforne = $clpcorcamforne->pc21_orcamforne;
		$erro_msg = $clpcorcamforne->erro_msg;
		if ($clpcorcamforne->erro_status == 0) {
			$sqlerro = true;
		}
	}
	if ($sqlerro == false) {
		$clpcorcamfornelic->incluir($pc21_orcamforne);
		if ($clpcorcamfornelic->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $clpcorcamfornelic->erro_msg;
		}
	}
	db_fim_transacao($sqlerro);
	$op = 1;



} else if (isset ($excluir)) {
	
	
	    $sWhere = "pc23_orcamforne = {$pc21_orcamforne} and pc23_percentualdesconto > 0 ";
	    $sSql = $clpcorcamval->sql_query_file(null,  null, "*", null, $sWhere);
	
	    $result_val=$clpcorcamval->sql_record($sSql);
	    if ($clpcorcamval->numrows>0){
	    	$erro_msg="Existe valor lançado para o fornecedor!!";
	    }else{
			db_inicio_transacao();
			if ($sqlerro == false) {
				$clpcorcamfornelic->excluir($pc21_orcamforne);
				if ($clpcorcamfornelic->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $clpcorcamfornelic->erro_msg;
				}
			}

			/**
			 * Exclui da pcorcamjulgamentologitem
			 */
			if ( !$sqlerro ) {

				$sWhere = "pc93_pcorcamforne = {$pc21_orcamforne}";
				$oDaoPcorcamjulgamentologitem->excluir(null, $sWhere);

				$erro_msg = $oDaoPcorcamjulgamentologitem->erro_msg;
				if ( $oDaoPcorcamjulgamentologitem->erro_status == 0 ) {
					$sqlerro = true;
				}
			}
			
			
			
			if ($sqlerro == false) {
			
				$clpcorcamval->excluir(null, null, "pc23_orcamforne = {$pc21_orcamforne}");
				if ($clpcorcamval->erro_status == 0 ) {
			
					$erro_msg = $clpcorcamval->erro_msg;
					$sqlerro = true;
				}
					
			}			
			

			if ($sqlerro == false) {
				$clpcorcamforne->excluir($pc21_orcamforne);
				$erro_msg = $clpcorcamforne->erro_msg;
				if ($clpcorcamforne->erro_status == 0) {
					$sqlerro = true;
				}
			}
			if ($sqlerro == false) {

			  $result_forne = $clpcorcamfornelic->sql_record($clpcorcamfornelic->sql_query(null,"*",null,"pc20_codorc=$pc20_codorc"));

				if ($clpcorcamfornelic->numrows==0){

					if ($sqlerro == false) {

					  $sWhere  = "pc26_orcamitem in                                         ";
					  $sWhere .= "(select pcorcamitem.pc22_orcamitem                        ";
					  $sWhere .= " from pcorcamitem                                         ";
					  $sWhere .= "   inner join pcorcam                                     ";
					  $sWhere .= "     on    pcorcam.pc20_codorc = pcorcamitem.pc22_codorc  ";
					  $sWhere .= "     where pcorcam.pc20_codorc ={$pc20_codorc})           ";
					  $sWhere .= "                                                          ";
						$clpcorcamitemlic->excluir(null, $sWhere);

						if ($clpcorcamitemlic->erro_status == 0) {

							$sqlerro = true;
							$erro_msg = $clpcorcamitemlic->erro_msg;
						}
					}

					if ($sqlerro == false) {

					  $sWhere  = " pcorcamitem.pc22_codorc in (                          ";
					  $sWhere .= " select pc20_codorc                                    ";
					  $sWhere .= " from pcorcam                                          ";
					  $sWhere .= " where                                                 ";
					  $sWhere .= "         pcorcam.pc20_codorc = pcorcamitem.pc22_codorc ";
					  $sWhere .= "   and   pcorcam.pc20_codorc = {$pc20_codorc})         ";
					  $clpcorcamitem->excluir(null, $sWhere);

						$pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;

						if ($clpcorcamitem->erro_status == 0) {

						  $sqlerro = true;
							$erro_msg = $clpcorcamitem->erro_msg;
						}
					}

					if ($sqlerro == false) {

					  $clpcorcam->excluir($pc20_codorc);
						if ($clpcorcam->erro_status == 0) {

						  $sqlerro = true;
							$erro_msg = $clpcorcam->erro_msg;
						}
					}
					if ($sqlerro == false) {
						$exc_tudo=true;
					}
				}
			}

			$op = 1;
			db_fim_transacao($sqlerro);
	    }
} else if (isset($alterar)) {

	db_inicio_transacao();

	$sqlerro = false;

	$clpcorcamfornelic->pc31_orcamforne = $pc21_orcamforne;
	$clpcorcamfornelic->alterar($pc21_orcamforne);
	$erro_msg = $clpcorcamfornelic->erro_msg;

	if ($clpcorcamfornelic->erro_status == 0) {
		$sqlerro = true;
	}

	$op = 1;
	db_fim_transacao($sqlerro);

} else if (isset ($opcao) || (isset ($chavepesquisa) && $chavepesquisa != "")) {
	$l20_codigo = $chavepesquisa;
	$result = $clliclicita->sql_record($clliclicita->sql_query_pco($chavepesquisa));
	if ($result != false && $clliclicita->numrows > 0) {
		db_fieldsmemory($result, 0);
	}
	$db_botao = true;
	$op = 1;
}
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
	<body class="body-default">
		<?php
			include modification("forms/db_frmforneclic.php");
			db_menu();
		?>
	</body>
</html>
<?php

if (isset ($alterar) || isset ($excluir) || isset ($incluir) || isset ($verificado)) {
	if (isset ($excluir)&&isset($exc_tudo)&&$exc_tudo==true){
		db_msgbox($erro_msg);
		echo "<script>location.href='lic1_fornec001.php';</script>";
	}else{
		if ($sqlerro == true) {
			db_msgbox($erro_msg);
			if ($clpcorcamforne->erro_campo != "") {
				echo "<script> document.form1.".$clpcorcamforne->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clpcorcamforne->erro_campo.".focus();</script>";
			}
		} else {
			db_msgbox($erro_msg);
			echo "<script>location.href='lic1_fornec001.php?chavepesquisa=$l20_codigo';</script>";
		}
	}
}

$sWhere = "1!=1";
if (isset($pc21_codorc) && !empty($pc21_codorc)) {
  $sWhere = "pc21_codorc=".@ $pc21_codorc;
}

$result_libera = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null, "pc21_codorc", "", $sWhere));
$tranca        = "true";

if ($clpcorcamforne->numrows > 0) {
	$tranca = "false";
}
if ($op == 11) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>