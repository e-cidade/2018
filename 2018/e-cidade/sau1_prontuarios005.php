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
//include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_agendamentos_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprontuarios = new cl_prontuarios;
$clagendamentos = new cl_agendamentos;
$db_opcao = 1;
$db_botao = true;
$tp = 2;

//variaveis
$clprontuarios->sd24_i_usuario = db_getsession("DB_id_usuario");;
$clprontuarios->sd24_d_datalog = date("Y-m-d",db_getsession("DB_datausu"));
$clprontuarios->sd24_d_data    = date("Y-m-d",db_getsession("DB_datausu"));
$clprontuarios->sd24_c_hora    = date("H:m");
$clprontuarios->sd24_i_unidade = db_getsession("DB_coddepto");
if(isset($incluir)){
  $clprontuarios->sd24_c_atendimento = $clagendamentos->Atendimento($ano,$mes);
  db_inicio_transacao();
  $clprontuarios->incluir($sd24_i_id);
  db_fim_transacao();
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmprontuarios2.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clprontuarios->erro_status=="0"){
    $clprontuarios->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprontuarios->erro_campo!=""){
      echo "<script> document.form1.".$clprontuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontuarios->erro_campo.".focus();</script>";
    };
  }else{
//    db_msgbox($clprontuarios->erro_msg);
    echo "<script>";
    echo " parent.document.formaba.a2.disabled=false;";
    echo " top.corpo.iframe_a2.location.href='sau1_prontproced001.php?pront=".$clprontuarios->sd24_i_id."';";
    echo " parent.mo_camada('a2');";
    echo "</script>";
  }
}
?>