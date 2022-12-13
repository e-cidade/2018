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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tabrecjm_classe.php");

$db_opcao   = 1 ;
$cltabrecjm = new cl_tabrecjm();
$instit     = db_getsession("DB_instit");

if ( isset($incluir) ) {

  db_postmemory($HTTP_POST_VARS);  

  pg_exec("BEGIN");
  $cltabrecjm->k02_desjm  = 'false';
  $cltabrecjm->k02_instit = $instit;
  $cltabrecjm->incluir($k02_codjm);
  pg_exec("COMMIT");
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>
  <table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td height="430"  valign="top" bgcolor="#CCCCCC"> 
        <?php require_once("forms/db_frmrecejm.php"); ?>
      </td>
    </tr>
  </table>
</center>

</body>
</html>
<?php
if ( $cltabrecjm->erro_status == 0 ) {
  $cltabrecjm->erro(true, false);
} else {
  $cltabrecjm->erro(true, false);
  db_redireciona("cai1_recejm005.php?liberaaba=false&chavepesquisa=$cltabrecjm->k02_codjm");
}
?>