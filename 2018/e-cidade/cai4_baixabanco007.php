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
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);


$iInstit = db_getsession("DB_instit");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<script>
function js_imprime(){
  window.open('cai4_baixabanco009.php?codcla=<?=$codcla?>','','width=790,height=530,scrollbars=1,location=0');
}
</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="748" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="21" height="468" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <table width="100%" border="0" cellspacing="0">
          <tr>
            <td align="center"><form name="form1" method="post" action="">
                <input name="imprimir" id="imprimir" value="Imprimir" type="button" onClick="js_imprime()">
              </form></td>
          </tr>
          <tr> 
            <td align="center"> 
              <?
	$sql = "select discla.*,disrec.k00_receit,tabrec.k02_drecei,disrec.vlrrec
	        from discla
			     inner join disrec on discla.codcla = disrec.codcla
			     inner join tabrec on tabrec.k02_codigo = disrec.k00_receit
		    where discla.codcla = $codcla
          and discla.instit = $iInstit ";
//		    echo "parou";
	db_lovrot($sql,15,"","","")
	?>
            </td>
          </tr>
        </table>
      </center>
	</td>
  </tr>
</table>
</body>
</html>