<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//include("classes/db_prontuarios_classe.php");
include("classes/db_undmedhorario_ext_classe.php");
//include("classes/db_especmedico_classe.php");
include("classes/db_agendamentos_ext_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$sd02_i_codigo = db_getsession("DB_coddepto");


$clagendamentos = new cl_agendamentos_ext;
$clundmedhorario = new cl_undmedhorario_ext;

if( !session_is_registered("arr_transferidos") ){
	session_register("arr_transferidos");
	db_putsession("arr_transferidos", array(array( 6 )) );
}
$data_dia = date("d",db_getsession("DB_datausu"));
$data_mes = date("m",db_getsession("DB_datausu"));
$data_ano = date("Y",db_getsession("DB_datausu"));


$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" onunload="return js_sair()"  >
<table align="center" width="70%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
        <?
        include("forms/db_frmtransfereagenda003.php");
        ?>
    </center>
    </td>
  </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);

function js_sair(){
 		x  = 'sau4_transfereagenda005.php';
 		x += '?limpar=true';

		js_OpenJanelaIframe('','db_iframe_transferesair',x,'Agenda: ',false)
}
</script>

<?  
	if( isset( $limpar )){
		if( session_is_registered("arr_transferidos") ){
			db_destroysession("arr_transferidos");
		}
		db_redireciona("sau4_transfereagenda003.php");
	}
?>