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


define("TAREFA",true);

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_tarefa_lanc_classe.php");
include("classes/db_tarefa_aut_classe.php");
include("classes/db_tarefaparam_classe.php");
include("classes/db_atendimento_classe.php");
include("classes/db_tecnico_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefamodulo_classe.php");
include("classes/db_tarefaproced_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefausu_classe.php");
include("classes/db_atenditem_classe.php");
include("classes/db_tarefaitem_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefamotivo_classe.php");
include("classes/db_tarefaclientes_classe.php");
include("classes/db_tarefa_agenda_classe.php");

$cltarefa         = new cl_tarefa;
$cltarefamodulo   = new cl_tarefamodulo;
$cltarefaproced   = new cl_tarefaproced;
$cltarefaparam    = new cl_tarefaparam;
$cltarefasituacao = new cl_tarefasituacao;
$clatenditem      = new cl_atenditem;
$cltarefaitem     = new cl_tarefaitem;
$cltarefausu      = new cl_tarefausu;
$cltarefaenvol    = new cl_tarefaenvol;
$cltarefamotivo   = new cl_tarefamotivo;
$cltarefaclientes = new cl_tarefaclientes;
$cltecnico        = new cl_tecnico;
$cltarefa_agenda  = new cl_tarefa_agenda;
$cltarefa_aut     = new cl_tarefa_aut;
$cltarefa_lanc    = new cl_tarefa_lanc;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 11;
$db_botao = true;

$responsavel 	= db_getsession("DB_id_usuario");
$dataini 	= date("Y-m-d",db_getsession("DB_datausu"));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_agenda(responsavel, dataini) {
  js_OpenJanelaIframe('top.corpo','db_iframe_agenda','func_agendamentotarefas.php?at40_responsavel='+responsavel+'&data_ini='+dataini,'Agenda de Tarefas',true);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    </td>
  </tr>
  </table>
  <form name="form1">
  </form>
      <? 
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>


</body>
</html>
<?
echo "	<script>
	js_agenda($responsavel,'$dataini');
	</script>";
?>