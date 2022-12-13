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
include("dbforms/db_funcoes.php");
include("libs/db_libcontabilidade.php");

include("classes/db_empresto_classe.php");

$clempresto     = new cl_empresto;

db_postmemory($HTTP_POST_VARS);

if(isset($processar)){
  db_inicio_transacao();
  $sqlerro=false;

  
  db_fim_transacao($sqlerro);
}


$clrotulo = new rotulocampo;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <form name="form1" method="post" action="">
    <center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height='20' colspan='2'>
    </td> 
  </tr> 
  <tr> 
    <td align='right'> <strong><?=$Le91_anousu?></strong>
    </td > 
    <td > 
    <?
    $result = $clempresto->sql_record($clempresto->sql_query(null,"distinct e91_anousu","e91_anousu limit 1"));
    global $e91_anousu;
    db_input("e91_anousu",5,$Se91_anousu,true,'',3);
    ?>
    <input name="processar" type="submit" id="db_opcao" value="Processar" onclick='return js_verfica();'>

    </td>

  </tr>
  <tr> 
    <td align='center' colspan='2'>
    </td> 
  </tr> 

</table>
    </form>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($processar)){
  db_msgbox($db_msgerro);
}
?>