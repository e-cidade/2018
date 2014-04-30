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
include("classes/db_db_cadhelp_classe.php");
include("classes/db_db_itenshelp_classe.php");
include("classes/db_db_tipohelp_classe.php");
include("classes/db_db_modulos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

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
<form name="form1" action"" method="POST">
<table width="100%" height="100%" border="1">
<tr>
  <td width="40%" valign="top">
  Módulo:
  <?
  $nome_modulo = $modulo;
  $id_item = $modulo;
  $cldb_modulos = new cl_db_modulos;
  //$sql = "select 0 as modulo, 'Todos' as nome_modulo
  //        union
  $sql = "select modulo,nome_modulo 
          from db_versaousu 
               inner join db_menu on db32_id_item = db_menu.id_item 
               inner join db_modulos on modulo = db_modulos.id_item
          union 
          select modulo,nome_modulo 
          from db_versaousu 
               inner join db_menu on db32_id_item = id_item_filho 
               inner join db_modulos on modulo = db_modulos.id_item
          order by modulo";
  $result_modulo = $cldb_modulos->sql_record($sql);
  if($result_modulo!=false && $cldb_modulos->numrows>0){
    db_selectrecord('nome_modulo',$result_modulo,true,2,'','','','',"location.href='con3_versao001.php?item='+document.form1.nome_modulo.value+'&modulo='+document.form1.nome_modulo.value",1);
    $tem_modulo = false;
    for($i=0;$i<$cldb_modulos->numrows;$i++){
      db_fieldsmemory($result_modulo,$i);
      if($id_item == $modulo){
        $tem_modulo=true;
      }
    }
    if($tem_modulo==false){
      $id_item = pg_result($result_modulo,0,0);
    }
  }
  global $todosmodulos;
  $todosmodulos=' Fechar ';
  db_input('todosmodulos',10,0,true,'button',2,'onclick="parent.db_janelaVersao_OnLine.hide();"');
  ?>
<hr>
  <iframe frameborder="0" name="mostrahelpmenu" height="430" width="500" src="con3_versao003.php?item=<?=$item?>&id_modulo=<?=$id_item?>"></iframe>
</td>
<td width="60%" valign="top">
  <iframe frameborder="0" name="mostrahelp" height="480" width="600" src=""></iframe>
</td>
</tr>
</table>
</form>
</body>
</html>