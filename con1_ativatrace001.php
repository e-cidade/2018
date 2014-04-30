<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
if(isset($ativar)){

  db_putsession("DB_traceLog",true);

} else if (isset($ativar_acount)) {

  db_putsession("DB_traceLogAcount",true);

} else if (isset($desativar_acount)) {

  db_destroysession("DB_traceLogAcount");

} else if (isset($desativar)) {

  db_destroysession("DB_traceLog");

} else if(isset($testaTrace)) {

  for($i=0; $i<6; $i++){
    db_query("select 'teste ".($i + 1)."' ", null, "Teste Trace Log ".($i + 1));
  }
  db_query("select 'TRACE LOG OK' ", null, "Trace Log OK");
}



$lMostrarMenu = true;
if (!empty($lParametroExibeMenu) && $lParametroExibeMenu === "false") {
  $lMostrarMenu = false;
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
<body style="background-color: #cccccc; margin-top: 20px;" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
        <form name="form1" method="post" action="">
          <input type="submit" name="<?=(db_getsession("DB_traceLog",false) == null ? "ativar" : "desativar")?>" value="<?=(db_getsession("DB_traceLog",false) == null ? "Ativar" : "Desativar")?> Trace Log">

          <input type="submit" name="<?=(db_getsession("DB_traceLogAcount",false) == null ? "ativar_acount" : "desativar_acount")?>" value="<?=(db_getsession("DB_traceLogAcount",false) == null ? "Ativar" : "Desativar")?> Trace Log Sem Accounts">

          <input type="submit" name="testaTrace" value="Testar Trace Log" <?=(db_getsession("DB_traceLog",false) || db_getsession("DB_traceLogAcount",false) == null ? "disabled" : "")?>>
        </form>
      </center>
	  </td>
  </tr>
</table>
<?
if ($lMostrarMenu) {
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
</html>