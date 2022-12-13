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


require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_jsplibwebseller.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");

include("classes/db_prontuarios_classe.php");
include("classes/db_prontproced_classe.php");
include("classes/db_prontproced_ext_classe.php");
include("classes/db_prontagendamento_classe.php");
include("classes/db_tmp_prontproced_classe.php");
include("classes/db_cgs_und_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$sd24_i_unidade = db_getsession("DB_coddepto");

$clprontuarios     = new cl_prontuarios;
$clprontproced_ext = new cl_prontproced_ext;
$clprontproced      = new cl_prontproced;
$clcgs_und         = new cl_cgs_und;
$clrotulo          = new rotulocampo;
$clprontagendamento = new cl_prontagendamento;

$clprontproced_ext->rotulo->label();
$clrotulo->label("z01_i_cgsund");

$db_opcao     = 1;
$db_botao     = true;
$db_botao1    = false;
$db_processar = isset($db_processar)?$db_processar:false;

$sd29_i_usuario = DB_getsession("DB_id_usuario");
$login          = DB_getsession("DB_login");


$result = $clprontagendamento->sql_record($clprontagendamento->sql_query(null,"*",null,"s102_i_prontuario = $sd24_i_codigo"));
if( $clprontagendamento->numrows > 0 ){
  db_fieldsmemory($result,0);
  $sd29_d_data_dia = substr($sd23_d_consulta,8,2);
  $sd29_d_data_mes = substr($sd23_d_consulta,5,2);
  $sd29_d_data_ano = substr($sd23_d_consulta,0,4);
  $sd29_c_hora     = $sd23_c_hora;
}else{
  $sd29_d_data_dia = date("d",db_getsession("DB_datausu"));
  $sd29_d_data_mes = date("m",db_getsession("DB_datausu"));
  $sd29_d_data_ano = date("Y",db_getsession("DB_datausu"));
  $sd29_c_hora     = date("H").":".date("m");
}



//Botões Alterar/Excluir
if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao = $opcao=="alterar"?2:3;

	$result = $clprontproced->sql_record($clprontproced->sql_query($sd29_i_codigo));
	if( $clprontproced->numrows > 0 ){
		db_fieldsmemory($result,0);
		$profissional_branco = true;
	}else{
		echo "<script>alert('Procedimento ja processado.')</script>";
	}
}


//Incluir, Alterar, Exluir
if(isset($incluir)){
     //$clprontproced->sd29_i_prontuario = $chavepesquisaprontuario;
     db_inicio_transacao();

     $clprontproced->sd29_i_prontuario = $sd24_i_codigo;
     $clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
     $clprontproced->sd29_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
     $clprontproced->sd29_c_cadastro = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
     $clprontproced->incluir("");
     db_fim_transacao();
}else if(isset($alterar)){
     db_inicio_transacao();

     $clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
     $clprontproced->alterar($sd29_i_codigo);
     db_fim_transacao();
}else if(isset($excluir)){
     db_inicio_transacao();
     $clprontproced->excluir($sd29_i_codigo);
     db_fim_transacao();
}else if(isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)){
	//die("auiii2");
   $sd24_i_codigo = $chavepesquisaprontuario;
   if( $db_opcao == 1 ){
	   $result = $clprontproced->sql_record($clprontproced->sql_query(null,"prontuarios.*, cgs_und.*, medicos.*, m.*, rhcbo.*, prontproced.sd29_i_profissional ",null,"sd29_i_prontuario = $chavepesquisaprontuario"));
	   if( $clprontproced->numrows > 0){
	      db_fieldsmemory($result,0);
	   }

	   $result = $clprontuarios->sql_record($clprontuarios->sql_query(null,"m.z01_nome as profissional_triagem, rhcbo.rh70_descr as cbo_triagem ",null,"sd24_i_codigo = $chavepesquisaprontuario"));
	   if( $clprontuarios->numrows > 0 ){
			   db_fieldsmemory($result,0);
			   if( isset($sd03_i_codigo) && (int)$sd03_i_codigo != 0 ){
			      $profissional_branco = false;
			   }
	   }
   }
}

//Processar
if( isset($incluir) || isset($excluir) ){
	$clprontproced->sql_record( "select sd29_i_codigo
	                             from prontproced
	                             inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
	                             where prontproced.sd29_i_prontuario = $sd24_i_codigo" );
	$clprontuarios->sd24_c_digitada = 'S';
	if( $clprontproced->numrows == 0){
	 		$clprontuarios->sd24_c_digitada = 'N';
	}
	db_inicio_transacao();

	$clprontuarios->sd24_i_login  = DB_getsession("DB_id_usuario");
	$clprontuarios->sd24_i_codigo = $sd24_i_codigo;
	$clprontuarios->alterar($sd24_i_codigo);
	db_fim_transacao();
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmsau_individualproced001.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
	js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);
	document.form1.sd24_i_unidade.value = parent.iframe_a1.document.form1.sd24_i_unidade.value;
	document.form1.sd24_i_codigo.value  = parent.iframe_a1.document.form1.sd24_i_codigo.value;
	//document.form1.login.value          = parent.iframe_a1.document.form1.login.value;
</script>