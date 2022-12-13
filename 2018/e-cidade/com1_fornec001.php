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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "libs/db_utils.php";
require_once "libs/db_app.utils.php";
require_once "dbforms/db_funcoes.php";
require_once "model/CgmFactory.model.php";
require_once "model/fornecedor.model.php";

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcorcamforne = new cl_pcorcamforne;
$clpcorcam      = new cl_pcorcam;
$clpcorcamitem  = new cl_pcorcamitem;
$clpcparam      = new cl_pcparam;
$pcorcamjulg    = new cl_pcorcamjulg;
$sqlerro        = false;

$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)|| isset($verificado)) {

  try {

    $oFornecedor = new fornecedor($pc21_numcgm);
    if($solicitacao == 'true') {
      $oFornecedor->verificaBloqueioSolicitacao($pc10_numero);
    } else if ($solicitacao == 'false'){
    	$oFornecedor->verificaBloqueioProcessoCompra($pc80_codproc);
    }

    $iStatusBloqueio = $oFornecedor->getStatusBloqueio();
  } catch (Exception $eException) {

    $sqlerro  = true;
    $erro_msg = $eException->getMessage();
  }

  if ($iStatusBloqueio == 2) {
    $erro_msg  = "\\nusuário:\\n\\n Fornecedor com débito na prefeitura !\\n\\n\\n\\n";
  }

  $clpcorcamforne->pc21_orcamforne = $pc21_orcamforne;
  $clpcorcamforne->pc21_codorc = $pc21_codorc;
  $clpcorcamforne->pc21_numcgm = $pc21_numcgm;
  $clpcorcamforne->pc21_importado = '0';
}

if (isset($incluir)) {


  db_inicio_transacao();

	if ($sqlerro==false) {

	  $result_igualcgm = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null,"pc21_codorc",""," pc21_numcgm=$pc21_numcgm and pc21_codorc=$pc21_codorc"));
	  if($clpcorcamforne->numrows > 0){

	    $sqlerro = true;
	    $erro_msg = "ERRO: Número de CGM já cadastrado.";
	  }
	}
  if ($sqlerro==false) {

    $clpcorcamforne->incluir($pc21_orcamforne);
    $erro_msg = $clpcorcamforne->erro_msg;
    if ($clpcorcamforne->erro_status==0) {
      $sqlerro=true;
    }
  }

  db_fim_transacao($sqlerro);
} else if (isset($excluir)) {

  if($sqlerro==false) {

    db_inicio_transacao();

    //Verifica se o fornecedor não está julgado para o orçamento deste processo de compras
    $pcorcamjulg->sql_record($pcorcamjulg->sql_query(null,null,"pc24_orcamforne",null," pcorcamforne.pc21_codorc = {$pc21_codorc} and pc24_orcamforne = {$pc21_orcamforne} "));
    if ($pcorcamjulg->numrows > 0) {
    	$erro_msg = "Orçamento de processo de compras já julgado para o fornecedor!";
    	$sqlerro = true;
    } else {

      $clpcorcamforne->excluir($pc21_orcamforne);
      $erro_msg = $clpcorcamforne->erro_msg;
      if ($clpcorcamforne->erro_status==0) {
        $sqlerro=true;
      }

    }

    db_fim_transacao($sqlerro);
  }
} else if(isset($opcao)) {

  $result = $clpcorcamforne->sql_record($clpcorcamforne->sql_query($pc21_orcamforne,"pc21_orcamforne,pc21_codorc,pc21_numcgm,z01_nome"));
  if ($result!=false && $clpcorcamforne->numrows > 0) {
    db_fieldsmemory($result,0);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js, strings.js, widgets/dbtextField.widget.js,
               dbViewNotificaFornecedor.js, dbmessageBoard.widget.js, dbautocomplete.widget.js,
               dbcomboBox.widget.js,datagrid.widget.js,widgets/dbtextFieldData.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
			<?
			include("forms/db_frmfornec.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir) || isset($verificado)) {

  if ($sqlerro==true) {

    db_msgbox($erro_msg);
    if ($clpcorcamforne->erro_campo!="") {

	    echo "<script> document.form1.".$clpcorcamforne->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	    echo "<script> document.form1.".$clpcorcamforne->erro_campo.".focus();</script>";
    }
  } else if ($iStatusBloqueio == 2) {
    db_msgbox($erro_msg);
  }
}

$result_libera = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null,"pc21_codorc","","pc21_codorc=$pc21_codorc"));
$tranca = "true";
if ($clpcorcamforne->numrows > 0) {
  $tranca = "false";
}
?>