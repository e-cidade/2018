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
include("classes/db_db_sysmodulo_classe.php");
include("classes/db_db_syscadproced_classe.php");
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
<script>

function js_atualiza_help(codproced){
  
  parent.js_linca_procedimento(codproced);
  
}


</script>
<style>
.clicado {
  color : red;
}
.desclicado {
  color : blue;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post">
<table whidth="100%">
<tr>
<td valign="top">
<?
    if(!isset($nome_modulo)){
      $nome_modulo = $modulo;
    }
    
    echo "Modulo:";
    $cldb_sysmodulo = new cl_db_sysmodulo;
    $result_modulo = $cldb_sysmodulo->sql_record($cldb_sysmodulo->sql_query('','*','nomemod'));
    if($result_modulo!=false && $cldb_sysmodulo->numrows>0){
      
      db_selectrecord('nome_modulo',$result_modulo,true,2,'','','','',"location.href='con1_help004.php?nome_modulo='+document.form1.nome_modulo.value",1);


      if(!isset($nome_modulo)){
        db_fieldsmemory($result_modulo,0,"codmod");
      }

      echo "<hr>";
      
      $cldb_syscadproced = new cl_db_syscadproced;
      $result = $cldb_syscadproced->sql_record($cldb_syscadproced->sql_query_file(null,"*","descrproced"," codmod = $nome_modulo")); 
      if($cldb_syscadproced->numrows>0){
        echo "<table>";
        for($i=0;$i<$cldb_syscadproced->numrows;$i++){
          db_fieldsmemory($result,$i);
          echo "<tr><td title='$obsproced'><a href='' onclick='js_atualiza_help($codproced);return false;' >$codproced</a></td><td>$descrproced</td></tr>";
        }
        echo "</table>";
      }
    }
  
  ?>
</td>
</tr>
</table>
</form>
</body>
</html>