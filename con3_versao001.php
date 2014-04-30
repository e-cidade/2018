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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_versao_classe.php");
include("classes/db_db_versaoant_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldb_versao    = new cl_db_versao;
$cldb_versaoant = new cl_db_versaoant;

$result = $cldb_versaoant->sql_record($cldb_versaoant->sql_query(null," db31_codver, fc_versao(db30_codversao, db30_codrelease) as versao",' db31_codver desc limit 1 '));

$versao_inicial = 0;
if($cldb_versaoant->numrows > 0){
  db_fieldsmemory($result,0,0);
  $versao_inicial = $db31_codver;
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
<form name="form1" action"" method="POST">
<table width="100%" height="100%" border="1">
<tr>
<td valign="top">
<? 
if(isset($item) && $item != ""){
  echo "Para consultas atualizacoes do modulo <a href='con3_versao004.php?id_item=$modulo'>Clique Aqui</a><br>";
  
  $sql = "select distinct db30_codversao,db30_codrelease,help,trim(db32_obs) as db32_obs
        from db_versaousu
             inner join db_versao on db30_codver = db32_codver
             inner join db_itensmenu on id_item = db32_id_item
        where db32_codver >= $versao_inicial 
        and db32_id_item in 
        (
          select id_item
          from db_menu
          where id_item = $item
          union
          select id_item_filho
          from db_menu
          where id_item_filho = $item
        )
        ";

  $resitem = pg_query($sql);
  if( pg_numrows($resitem) > 0 ){
    $lista = 'X';
    for($mi=0;$mi<pg_numrows($resitem);$mi++){
      db_fieldsmemory($resitem,$mi);
      if($lista != $help){
        echo "<br><strong>$help</strong><br>";
        $lista = $help;
      }
      echo "&nbsp&nbsp&nbsp<strong>2.$db30_codversao.$db30_codrelease</strong> $db32_obs<br>";
    }
  }else{
    echo "Não existem atualizações para este item de menu. ($item)";
  }
}else{
  echo "<script>location.href='con3_versao004.php?id_item=$modulo'</script>";
  exit;
}
?>
</td>
</tr>
</table>
</form>
</body>
</html>