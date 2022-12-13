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

db_postmemory($_POST);
db_postmemory($_GET);

?>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</script>
<script>
function js_manda(campo){
 parent.location.href='con4_libusumod.php?selecionar=eco&retorno='+campo;

}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table  border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
  </tr>
</table>
    <table border="1" width='100%' cellspacing="0" cellpadding="5">
          <tr> 
            <td> 
              <?
			$sql = "select (u.id_instit || '_' || u.id_usuario || '_' || u.id_modulo) as db_codigo,us.login,c.nomeinst as Instituição,m.nome_modulo as Módulo
			        from db_usermod u
					inner join db_usuarios us
					on us.id_usuario = u.id_usuario
					inner join db_modulos m
					on m.id_item = u.id_modulo
					inner join db_config c
					on c.codigo = u.id_instit
					where u.id_usuario = ".$HTTP_GET_VARS["usuario"];
          //echo $sql;
			 db_lovrot($sql,10,"()",'','js_manda|codigo',"","NoMe", array(),false); 
//echo $retorno;
			?>
            </td>
          </tr>
        </table>
</body>
</html>