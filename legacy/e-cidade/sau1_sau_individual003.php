<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
require("libs/db_app.utils.php");

include("classes/db_prontuarios_classe.php");
include("classes/db_prontuarios_ext_classe.php");
include("classes/db_prontproced_classe.php");
include("classes/db_prontcid_classe.php");
include("classes/db_prontprocedcid_classe.php");
include("classes/db_unidades_ext_classe.php");
include("classes/db_cgs_und_classe.php");
include("classes/db_sau_config_ext_classe.php");
include("classes/db_sau_fechapront_classe.php");

include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clprontuarios_ext = new cl_prontuarios_ext;
$clprontuarios     = new cl_prontuarios;
$clprontproced     = new cl_prontproced;
$clprontcid        = new cl_prontcid;
$clprontprocedcid  = new cl_prontprocedcid;
$clunidades        = new cl_unidades_ext;
$clcgs_und         = new cl_cgs_und;
$clsau_config      = new cl_sau_config_ext;
$clFechapront      = new cl_sau_fechapront;

//Sau_Config
$resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
$objSau_config = db_utils::fieldsMemory($resSau_config,0 );

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $lErro = false;

  $clFechapront->excluir(null,"sd98_i_prontproced in (select sd29_i_codigo from prontproced where  sd29_i_prontuario = $sd24_i_codigo)");
  if ($clFechapront->erro_status == "0") {

    $clprontuarios->erro_msg = $clFechapront->erro_msg;
    $clprontuarios->erro_status = "0";
    $lErro == true;

  }
  if ($lErro == false) {
    $clprontprocedcid->excluir(null,"s135_i_prontproced in (select sd29_i_codigo from prontproced where  sd29_i_prontuario = $sd24_i_codigo)");
    if ($clprontprocedcid->erro_status == "0") {

      $clprontuarios->erro_msg = $clprontprocedcid->erro_msg;
      $clprontuarios->erro_status = "0";
      $lErro == true;

    }
  }
  if ($lErro == false) {
    $clprontproced->excluir(null, " sd29_i_prontuario = $sd24_i_codigo");
    if ($clprontproced->erro_status == "0") {

      $clprontuarios->erro_msg = $clprontproced->erro_msg;
      $clprontuarios->erro_status = "0";
      $lErro == true;

    }
  }
  $clprontuarios->excluir($sd24_i_codigo);
  if ($clprontuarios->erro_status == "0") {
    $lErro == true;
  }
  db_fim_transacao($lErro);
}else if(isset($chavepesquisaprontuario)){
   $db_opcao = 3;
   $result= $clprontuarios_ext->sql_record($clprontuarios_ext->sql_query_nolote_ext(null,"m.z01_nome as profissional_triagem, rhcbo.rh70_descr as cbo_triagem,  sau_lotepront.* ",null,"sd24_i_codigo = $chavepesquisaprontuario"));

   if( $clprontuarios_ext->numrows > 0 ){
      $obj_prontuario_ext = db_utils::fieldsMemory($result, 0);
      if( $obj_prontuario_ext->sd59_i_prontuario != "" ){
        db_msgbox("Impossivel alteracao de FAA incluida via Lote.");
        $sd24_i_codigo = null;
	 	    db_redireciona("sau1_sau_individual003.php?idarq=".$idarq);
      }
   }
   $result = $clprontuarios->sql_record($clprontuarios->sql_query($chavepesquisaprontuario));
   db_fieldsmemory($result,0);
   $db_botao = true;
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmsau_individual.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
	if($clprontuarios->erro_status!="0"){

		$clprontuarios->erro(true,false);
		db_redireciona("sau1_sau_individual003.php?idarq=$idarq" );

	}
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisarfaa.click();</script>";
}
?>
<script>
	js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>