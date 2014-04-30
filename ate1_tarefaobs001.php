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

$sqlerro  = false;
$db_botao = true;

if(isset($alterar)) {
  
  $result = $cltarefa->sql_record($cltarefa->sql_query($at42_tarefa, "at40_autorizada"));
  if ($cltarefa->numrows > 0) {
    db_fieldsmemory($result, 0);
    if ($at40_autorizada == 't') {
      $cltarefa->at40_autorizada = "true";
    } else {
      $cltarefa->at40_autorizada = "false";
    }
  } else {
    $cltarefa->at40_autorizada = "false";
  }
  
  $cltarefa->at40_sequencial	= $at42_tarefa;
  $cltarefa->at40_obs 		= $at40_obs;

  $cltarefa->alterar($at42_tarefa);
  if($cltarefa->erro_status == 0) {
    $sqlerro = true;
  }
  $erro_msg = $cltarefa->erro_msg;
} else {
	$result = $cltarefa->sql_record($cltarefa->sql_query($at42_tarefa, "at40_obs"));
	$linhastar = $cltarefa->numrows;
	if($linhastar>0){
		 db_fieldsmemory($result, 0);
	}else{
	
		$sql = "
			select at05_feito  from atendimento inner join atenditem on at02_codatend = at05_codatend 
			where at05_codatend = $at02_codatend
			and at05_seq = $at05_seq;
		";
		$res = pg_query($sql); 
		$linhas =pg_num_rows($res);
	
	  	if ($linhas > 0) {
		    db_fieldsmemory($res, 0);
		    $at40_obs = $at05_feito;
		    //$cltarefa->at40_obs = '$at40_obs';
		   // $cltarefa->alterar($at42_tarefa);
		    $upd = "update tarefa set at40_obs = '$at40_obs' where at40_sequencial = $at42_tarefa";
		    pg_query($upd);
	    }
	}
}

$db_opcao = 2;
$db_botao = true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmtarefaobs.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cltarefa->erro_campo!=""){
      echo "<script> document.form1.".$cltarefa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefa->erro_campo.".focus();</script>";
    };
  } else {
    db_msgbox($erro_msg);
    echo "<script>top.corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at42_tarefa."'</script>";
  }
}

?>