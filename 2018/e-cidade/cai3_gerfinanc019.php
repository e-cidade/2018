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
include("classes/db_notificacao_classe.php");
include("classes/db_notisitu_classe.php");
include("classes/db_noticonf_classe.php");
include("dbforms/db_funcoes.php");
$clnotificacao = new cl_notificacao;
$clnoticonf    = new cl_noticonf;
$clnotisitu    = new cl_notisitu;
$clrotulo      = new rotulocampo;
$clrotulo->label("k54_assinante");
$clrotulo->label("k54_hora");
$clrotulo->label("k50_notifica");
$clrotulo->label("k50_dtemite");
$clrotulo->label("k54_data");
$clrotulo->label("k54_obs");
$clrotulo->label("k54_codigo");
$clrotulo->label("k59_codigo");
$clrotulo->label("k59_descr");


db_postmemory($HTTP_POST_VARS);

if(isset($incluir)){
  $usuario = db_getsession("DB_id_usuario");
  db_inicio_transacao();
  $erro1 = false;
  $clnoticonf->k54_data = $k54_data_ano."-".$k54_data_mes."-".$k54_data_dia;
  $clnoticonf->k54_hora = $k54_hora;
  $clnoticonf->k54_assinante = $k54_assinante;
  $clnoticonf->k54_obs = $k54_obs; 
  $clnoticonf->k54_codigo = $k54_codigo;
  $clnoticonf->k54_notifica = $k50_notifica;
  $resultconf = $clnoticonf->sql_record($clnoticonf->sql_query($k50_notifica)); 
  if ($clnoticonf->numrows == 0){
     $clnoticonf->incluir($k50_notifica);
  }else{
     $clnoticonf->alterar($k50_notifica);
  }
  if($clnoticonf->erro_status !="1"){
     $erro1 = true;
     $clnoticonf->erro(true,false);
  }
  db_fim_transacao($erro1);
  if ($erro1 == false){
//     db_msgbox('Processamento Concluído Com Sucesso!');
     echo"<script>parent.db_iframe_notificacao.hide();
                  parent.debitos.location.reload()</script>";
  }
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
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="0" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <input name="proximobanco" value="" type="hidden">
    <?
	include("forms/db_notifi019.php");
	?>
    </td>
  </tr>
</table>
</body>
</html>