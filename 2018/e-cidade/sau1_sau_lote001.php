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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_jsplibwebseller.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);

$clsau_lote      = new cl_sau_lote;
$clsau_lotepront = new cl_sau_lotepront_ext;
$clprontuarios   = new cl_prontuarios_ext;
$clprontcid      = new cl_prontcid;
$clunidades      = new cl_unidades_ext;
$clcgs_und       = new cl_cgs_und;
$clsau_config    = new cl_sau_config_ext;


$db_opcao = 1;
$db_botao = true;

$sd58_i_login   = DB_getsession("DB_id_usuario");
$login          = DB_getsession("DB_login");
$sd24_i_unidade = isset($sd24_i_unidade)?$sd24_i_unidade:DB_getsession("DB_coddepto");

//Sau_Config
$resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
$objSau_config = db_utils::fieldsMemory($resSau_config,0 );

//Pesquisas
if ( isset($chavepesquisaprontuario) && (int)$chavepesquisaprontuario != 0) {

	$result = $clprontuarios->sql_record($clprontuarios->sql_query_ext($chavepesquisaprontuario));
	if ( $clprontuarios->numrows > 0) {

		db_fieldsmemory($result,0);
		if ( $sd24_c_digitada == 'N') {

			$result = $clsau_lotepront->sql_record($clsau_lotepront->sql_query_ext("","*","", "sd59_i_prontuario = $chavepesquisaprontuario  and sd59_i_lote <> ".(int)$sd58_i_codigo));
	   	if ( $clsau_lotepront->numrows > 0 ) {

	   		$obj_lotepront = db_utils::fieldsMemory($result, 0);
	   		db_msgbox("FAA foi lançada no lote {$obj_lotepront->sd59_i_lote}.");
	   		db_redireciona("sau1_sau_lote001.php?sd58_i_codigo=".$sd58_i_codigo."&idarq=".$idarq);
   		} else {

				$result = $clprontcid->sql_record($clprontcid->sql_query("","prontcid.*, sau_cid.*", ""," sd55_i_prontuario = $chavepesquisaprontuario "));
				if ( $clprontcid->numrows > 0) {
					db_fieldsmemory($result,0);
				}
   		}
		} else {

	   	db_msgbox('FAA já digitada.');
	   	db_redireciona("sau1_sau_lote001.php?sd58_i_codigo=".$sd58_i_codigo."&idarq=".$idarq);
		}
	} else {

   	db_msgbox('FAA não localizada.');
   	db_redireciona("sau1_sau_lote001.php?sd58_i_codigo=".$sd58_i_codigo."&idarq=".$idarq);
	}
}
if ( isset($chavepesquisacgs) && (int)$chavepesquisacgs != 0) {

   $result = $clcgs_und->sql_record($clcgs_und->sql_query($chavepesquisacgs));
   db_fieldsmemory($result,0);
} else if(isset($chavepesquisalote)) {

	//$result = $clsau_lote->sql_record($clsau_lote->sql_query($chavepesquisalote));
	$result = $clsau_lotepront->sql_record($clsau_lotepront->sql_query_ext(null, "sau_lote.*, db_usuarios.*, sd24_i_unidade", "", "sd59_i_lote = $chavepesquisalote"));
	db_fieldsmemory($result,0);
	$idarq=22;
} else if ( isset($chavepesquisalotepront) && (int)$chavepesquisalotepront != 0) {

	$result = $clsau_lotepront->sql_record( $clsau_lotepront->sql_query_ext($chavepesquisalotepront) );
  db_fieldsmemory($result,0);
  $db_opcao = 2;
}

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?php
		try{
			db_app::load("scripts.js");
			db_app::load("prototype.js");
			db_app::load("datagrid.widget.js");
			db_app::load("strings.js");
			db_app::load("webseller.js");
			db_app::load("grid.style.css");
			db_app::load("estilos.css");
		}catch (Exception $eException){
			die( $eException->getMessage() );
		}
	?>
	<script type="text/javascript" language="JavaScript" src="scripts/AjaxRequest.js"></script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC" id="idTD">
    <center>
	<?php
	include("forms/db_frmsau_lote.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>


</script>

<?php
if(isset($chavepesquisalotepront)&&(int)$chavepesquisalotepront != 0){
	echo "<script>
		focoInclusao   = $('sd70_c_cid');
		js_tabulacaoforms(\"form1\",\"sd70_c_cid\",true,1,\"sd24_t_diagnostico\",true);
		</script>";
}else{
	?><script>
	js_tabulacaoforms("form1","sd24_i_unidade",false,1,"sd24_t_diagnostico",true);
	if( $('sd24_i_unidade').readOnly ){
		$('sd24_i_codigo').focus();
		$('sd24_i_codigo').select();
		focoInclusao   = $('sd24_i_codigo');
		$('campoFocado').value = 'sd24_i_codigo';
	}else{
		$('sd24_i_unidade').focus();
		$('sd24_i_unidade').select();
	}
	</script><?php
}
?>

<script>
function js_sair(){
	if(confirm("Deseja realmente sair?")){
		<?
		if (isset($_SESSION["objRegistros"])) {
			unset($_SESSION["objRegistros"]);
		}
		if (isset($_SESSION["objRegProfissional"])) {
			unset($_SESSION["objRegProfissional"]);
		}
		?>
		js_retorna();
	}
}

function js_retorna(){
	parent.document.formaba.a1.disabled = true;
	parent.document.formaba.a2.disabled = true;
	parent.iframe_a1.location.href='sau1_sau_lote001.php?idarq=1';
	parent.mo_camada('a1');
}

</script>
<?php
if(isset($incluir) || isset($alterar) ){
  if($clsau_lote->erro_status=="0"){
    $clsau_lote->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsau_lote->erro_campo!=""){
      echo "<script> document.form1.".$clsau_lote->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsau_lote->erro_campo.".focus();</script>";
    }
  }else{
	  if($clprontuarios->erro_status=="0"){
	    $clprontuarios->erro(true,false);
	    $db_botao=true;
	    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
	    if($clprontuarios->erro_campo!=""){
	      echo "<script> document.form1.".$clprontuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	      echo "<script> document.form1.".$clprontuarios->erro_campo.".focus();</script>";
	    }
	  }else{
		  if($clsau_lotepront->erro_status=="0"){
		    $clsau_lotepront->erro(true,false);
		    $db_botao=true;
		    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		    if($clsau_lotepront->erro_campo!=""){
		      echo "<script> document.form1.".$clsau_lotepront->erro_campo.".style.backgroundColor='#99A9AE';</script>";
		      echo "<script> document.form1.".$clsau_lotepront->erro_campo.".focus();</script>";
		    }
		  }else{
			  if($clprontcid->erro_status=="0"){
			    $clprontcid->erro(true,false);
			    $db_botao=true;
			    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
			    if($clprontcid->erro_campo!=""){
			      echo "<script> document.form1.".$clprontcid->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			      echo "<script> document.form1.".$clprontcid->erro_campo.".focus();</script>";
			    }
			  }else{
			  	//$clsau_lote->erro(true,false);
			  	db_redireciona("sau1_sau_lote001.php?sd58_i_codigo=$sd58_i_codigo&idarq=$idarq&sd24_i_unidade=$sd24_i_unidade");
			  }
		  }
	  }
  }
}

if ( isset($idarq) && $idarq == 2 ) {
	echo "<script>js_pesquisalote();</script>";
} else if ( $idarq == 22 && $sd58_c_digitada == 'S' ) {
	db_msgbox('Lote ja foi digitado.');
}

?>