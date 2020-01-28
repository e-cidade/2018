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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if(isset($retorno)) {
  $result = pg_exec("select v51_codigo as codigo,v51_descr as descr from tipojur where v51_codigo = $retorno");
  db_fieldsmemory($result,0);
}

if(isset($HTTP_POST_VARS["alterar"])) {
  pg_exec("update tipojur set v51_descr = '".$HTTP_POST_VARS["descr"]."' where v51_codigo = ".$HTTP_POST_VARS["codigo"]) or die("Erro(14) alterando tipojur");
  db_redireciona();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<?
	  if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
        db_postmemory($HTTP_POST_VARS);
        if(!empty($codigo)) {
          $result = pg_exec("select v51_codigo from tipojur where v51_codigo = $codigo");
	      if(pg_numrows($result) > 0) {
 	        db_redireciona("jur1_tipojur002.php?".base64_encode("retorno=".pg_result($result,0,0)));
	        exit;
	      } else {             
             $filtro = base64_encode("v51_codigo like '".$codigo."%' order by v51_codigo");
	      }
        } else {
		  if(!empty($descr))
            $filtro = base64_encode("upper(v51_descr) like upper('".$HTTP_POST_VARS["descr"]."%') order by v51_descr");
	    }
        if(isset($HTTP_POST_VARS["filtro"]))
          $filtro = $HTTP_POST_VARS["filtro"];
	    $sql = "select v51_codigo as db_codigo,v51_codigo as código,v51_descr as descrição from tipojur where ".base64_decode($filtro);
        echo "<center>\n";
        db_lov($sql,15,"jur1_tipojur002.php",$filtro);
        echo "</center>\n";		
	  } else {
	    if(isset($retorno))
		  $submit = "alterar";
		else
	      $submit = "procurar";
	    include("forms/db_frmjurtabaux.php");
	  }
	?>	
	</td>
  </tr>
</table>
<?	
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>