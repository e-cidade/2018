<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_stdlibwebseller.php");
include("libs/db_jsplibwebseller.php");
include("libs/db_app.utils.php");
include("classes/db_unidades_classe.php" );
include("classes/db_undmedhorario_ext_classe.php");
include("classes/db_agendamentos_classe.php");
include("classes/db_sau_config_ext_classe.php");
include("classes/db_sau_upsparalisada_ext_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clunidades         = new cl_unidades;
$clagendamentos     = new cl_agendamentos;
$clundmedhorario    = new cl_undmedhorario_ext;
$clsau_config       = new cl_sau_config_ext;


$clsau_upsparalisada = new cl_sau_upsparalisada_ext;

$res_sau_config  = pg_query( $clsau_config->sql_query_ext() );
$booProced       = pg_num_rows( $res_sau_config ) > 0 && pg_result($res_sau_config, 0, "s103_c_agendaproc") == "S";
$sd02_c_centralagenda = "N";
$upssolicitante       = db_getsession("DB_coddepto"); 
$result_unidades      = $clunidades->sql_record( $clunidades->sql_query($upssolicitante,"sd02_c_centralagenda,descrdepto",null,"") );

$oAgendaParametros = loadConfig('sau_parametrosagendamento');
if ($oAgendaParametros != null) {
  $s165_formatocomprovanteagend = $oAgendaParametros->s165_formatocomprovanteagend; 
}

if( $clunidades->numrows != 0 ){
	@db_fieldsmemory($result_unidades,0);
}else{
	db_msgbox("Departamento $upssolicitante não é uma UPS.");
}

if( isset( $chave_diasemana ) && $chave_diasemana != "" ){
	$result = $clundmedhorario->sql_record( $clundmedhorario->sql_query_ext("","*","", "sd30_i_codigo = $sd30_i_codigo and sd30_i_diasemana = $chave_diasemana ") );
	if( $clundmedhorario->numrows == 0 ){
		db_msgbox("Profissional não possui agendamento.");
	}else{
		db_fieldsmemory( $result, 0 );
		$agendados = true;
	}
}

$db_opcao_cotas = 1;
$oResult = getCotasAgendamento($upssolicitante, null, null, null, null);
if ($oResult->lStatus != 1) {

  $sd02_i_codigo = $upssolicitante;
  $db_opcao_cotas = 3;
  
} else {
	 
  $sd02_i_codigo  = "";
  $descrdepto     = ""; 
  	
}

$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
        <?
			include("forms/db_frmagendamento.php");
        ?>
    </center>
    </td>
  </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
  js_tabulacaoforms("form1","sd02_i_codigo",true,1,"sd02_i_codigo",true);
</script>