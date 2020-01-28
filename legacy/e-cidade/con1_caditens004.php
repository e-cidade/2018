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
include("classes/db_db_itensmenu_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<?
$cldb_itensmenu = new cl_db_itensmenu;
$result = $cldb_itensmenu->sql_record($cldb_itensmenu->sql_query($idmodulo,'descricao,help'));
db_fieldsmemory($result,0);
echo "<strong>Módulo:</strong> $idmodulo - $descricao<br>";

$result = $cldb_itensmenu->sql_record($cldb_itensmenu->sql_query($item,'descricao,help'));
db_fieldsmemory($result,0);
echo "<strong>Ítem:</strong> $item - $descricao<br> ";

$sql = "SELECT 'Usuário'::varchar as dl_usuario,null::varchar as dl_tipo,u.login,u.nome,u.email
        from db_permissao p 
          INNER JOIN db_usuarios u on u.id_usuario = p.id_usuario
		  INNER JOIN db_itensmenu i ON i.id_item = p.id_item
	    WHERE p.id_item = $item 
	      AND p.id_modulo = $idmodulo 
          AND i.itemativo = '1' 
          AND i.libcliente = true
          AND P.id_instit = ".db_getsession('DB_instit')."
          AND p.anousu = ".db_getsession('DB_anousu')." and usuarioativo = '1'
	  AND u.usuext = '0'
        ";
        
	  $sql .= "
			  
	     UNION all
	  
	     SELECT distinct null as dl_usuario,'Perfil' as dl_tipo,uu.login,uu.nome,uu.email
	     FROM db_permissao p 
		  INNER JOIN db_permherda h on h.id_perfil = p.id_usuario
          INNER JOIN db_usuarios uu on h.id_perfil = uu.id_usuario

		  INNER JOIN db_usuarios u on u.id_usuario = h.id_usuario
		  
		  INNER JOIN db_itensmenu i ON i.id_item = p.id_item

	     WHERE p.anousu = ".db_getsession('DB_anousu')."
		   AND p.id_instit = ".db_getsession('DB_instit')."
	       AND p.id_modulo = $idmodulo 
           AND i.itemativo = 1 
	and uu.usuext = '2'
	       and i.id_item = $item
		   and i.libcliente = true and uu.usuarioativo = '1' and u.usuarioativo = '1' ";

$sql = "select dl_usuario,dl_tipo,login,nome,email
        from ( $sql ) as x 
        order by dl_tipo desc, nome,dl_usuario desc";

//echo $sql;
db_lovrot($sql,100,'()')

?>
</td>
</tr>
</table>
</body>
</html>