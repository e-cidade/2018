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
  $result = pg_exec("select * from arretipo where k00_tipo = $retorno ");
  db_fieldsmemory($result,0);
} 
if(isset($HTTP_POST_VARS["enviar"])) {
  db_postmemory($HTTP_POST_VARS);
  $k00_codbco = $k00_codbco==""?"null":$k00_codbco;  
  pg_exec("update arretipo set k00_descr = '$k00_descr',
                               k00_emrec = '$k00_emrec',
			       k00_agnum = '$k00_agnum',
			       k00_agpar = '$k00_agpar',
			       k00_codbco = $k00_codbco,
                               k00_codage = '$k00_codage',
                               k00_hist1 = '$k00_hist1',
                               k00_hist2 = '$k00_hist2',
                               k00_hist3 = '$k00_hist3',
                               k00_hist4 = '$k00_hist4',
                               k00_hist5 = '$k00_hist5',
                               k00_hist6 = '$k00_hist6',
                               k00_hist7 = '$k00_hist7',
                               k00_hist8 = '$k00_hist8',
			       codmodelo = $codmodelo,
                               k00_impval = '$k00_impval',
                               k00_vlrmin = '$k00_vlrmin',
                               k03_tipo   = $k03_tipo
                  where k00_tipo = $k00_tipo") or die("Erro(13) alterando arretipo.");
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
      if(!empty($k00_tipo)) {
        $result = pg_exec("select k00_tipo from arretipo where k00_tipo = $k00_tipo");
	    if(pg_numrows($result) > 0) {
 	      db_redireciona("cai1_tipodebito002.php?".base64_encode("retorno=".pg_result($result,0,0)));
	      exit;
	    } else {
          $sql = "select k00_tipo as db_codigo,k00_tipo as Tipo,k00_descr as Descrição,k03_tipo as Détito from arretipo where k00_tipo like '".$k00_tipo."%' order by k00_tipo";
	    }
      } else {
          $sql = "select k00_tipo as db_codigo,k00_descr as Descrição,k00_tipo as Tipo,k03_tipo as Débito  from arretipo where upper(k00_descr) like upper('".@$k00_descr."%') order by k00_tipo";
      }
	  echo "<center>";
      db_lov($sql,15,"cai1_tipodebito002.php");
	  echo "</center>";
    } else if(!isset($retorno)) {
	  ?>
	  <center>
	  <form name="form1" method="post" action="">
      <table width="42%" border="0" cellspacing="0" cellpadding="0">
      <tr>
              <td width="35%" height="25"><strong>C&oacute;digo:</strong></td>
        <td width="65%" height="25"><input name="k00_tipo" type="text" size="10"></td>
      </tr>
      <tr>
              <td height="25"><strong>Identifica&ccedil;&atilde;o:</strong></td>
        <td height="25"><input name="k00_descr" type="text" size="40" maxlength="40"></td>
      </tr>
      <tr>
        <td height="25">&nbsp;</td>
        <td height="25"><input name="procurar" type="submit" value="Procurar"></td>
      </tr>
      </table>
      </form>
	  </center>
	  <?
    } else { 
	  include("forms/db_frmtipodebito.php");
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