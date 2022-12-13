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
include("classes/db_db_versao_classe.php");
include("classes/db_db_versaousu_classe.php");
include("classes/db_db_versaoant_classe.php");
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
<style>
.clicado {
  color : red;
}
.desclicado {
  color : blue;
}
</style>
<script>
var ultimoclicado = 0;

function js_mostra_versao(versao,release,item){
  parent.mostrahelp.location.href = 'con3_versao002.php?versao='+versao+'&release='+release+'&item='+item; 	
}

function js_troca_cor(idobj){
	
  if(ultimoclicado!=0){ 
    document.getElementById(ultimoclicado).style.color = 'black';
    document.getElementById(ultimoclicado).style.fontWeight = 'bold';
  }
  document.getElementById(idobj).style.color = 'red';
  document.getElementById(idobj).style.fontWeight = 'bold';
  
  // atualiza ultimo clicado
  ultimoclicado = idobj;
  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post">
   <?
   $cldb_itensmenu = new cl_db_itensmenu;
   $cldb_versaoant = new cl_db_versaoant;
   $resant = $cldb_versaoant->sql_record($cldb_versaoant->sql_query());
   if($cldb_versaoant->numrows==0){
     $db31_codver = 0;
     $db31_data = date('Y-m-d');	 
   }else{
   	 db_fieldsmemory($resant,0);
   }
   $cldb_versao = new cl_db_versao;
   if( $id_modulo == 0 ){
     $result = $cldb_versao->sql_record($cldb_versao->sql_query(null,'distinct db30_codversao, db30_codrelease,db32_id_item ','db30_codversao,db30_codrelease'," db30_codver > $db31_codver and not db32_obs is null and db32_id_item in (select distinct id_item from db_menu union select distinct id_item_filho from db_menu ) "));
   }else{
     $result = $cldb_versao->sql_record($cldb_versao->sql_query(null,'distinct db30_codversao, db30_codrelease,db32_id_item ','db30_codversao,db30_codrelease'," db30_codver > $db31_codver and not db32_obs is null and db32_id_item in (select distinct id_item from db_menu where modulo = $id_modulo union select distinct id_item_filho from db_menu where modulo = $id_modulo ) "));
   }
   if($cldb_versao->numrows==0){
   	echo '<table><tr><td>';
   	echo "<strong>Não existe atualização de versão disponível para este módulo.</strong><br>
   	      <a href='' onclick='parent.form1.nome_modulo.onchange();return false;'> Clique Aqui para atualizar</a>";
    echo '</td></tr></table>';
   }else{
     echo '<table>';
     // utiliza a variavel para testar a quebra por versao
     $qual_codversao=0;
     $qual_codrelease=0;
   	 for($i=0;$i<$cldb_versao->numrows;$i++){
   	 	db_fieldsmemory($result,$i);
   	 	if($qual_codversao == 0 || $qual_codversao != $db30_codversao || $qual_codrelease != $db30_codrelease ){
     	 	echo '<tr>';
            echo '<td>';
            $qual_codversao=$db30_codversao;
            $qual_codrelease=$db30_codrelease;
            echo "<strong>Versão: 2.$db30_codversao.$db30_codrelease</strong>";
    	 	echo '</td>';
    	 	echo '</tr>';
   	 	}            
        $resultitem = $cldb_itensmenu->sql_record($cldb_itensmenu->sql_query($db32_id_item));
        if($cldb_itensmenu->numrows>0){
          db_fieldsmemory($resultitem,0);

          if( $id_modulo == 0 ){
            $sql = "select nome_modulo
                    from db_menu
                         inner join db_modulos on db_menu.modulo = db_modulos.id_item
                    where db_menu.id_item = $db32_id_item 
                    union
                    select nome_modulo
                    from db_menu
                         inner join db_modulos on db_menu.modulo = db_modulos.id_item
                    where db_menu.id_item_filho = $db32_id_item 
                    limit 1
                    ";
            $res = pg_exec($sql);
            if(pg_numrows($res)>0){
   	 	      echo '<tr>';
              echo '<td>';
              echo pg_result($res,0,0);
  	 	      echo '</td>';
              echo '</tr>';
            }
          }

  	 	  echo '<tr>';
          echo '<td>';

          $item_anterior="";
          if(trim($funcao)!=""){
            $sql = "select db_menu.id_item, descricao
                    from db_menu
                         inner join db_itensmenu on db_menu.id_item = db_itensmenu.id_item
                    where id_item_filho = $db32_id_item limit 1";
            $res = pg_exec($sql);
            if(pg_numrows($res)>0){
              $item_anterior .= pg_result($res,0,1).":";
            }
 	 	  }else{
            $item_anterior="";
 	 	  }

 	 	  echo "&nbsp;&nbsp<a href='' id='$db32_id_item' style='color:black' onclick='js_troca_cor(this.id);js_mostra_versao($qual_codversao,$qual_codrelease,$db32_id_item);return false;' ><strong>$item_anterior $descricao</strong> </a>";
   	 	  echo '</td>';
   	 	  echo '</tr>';
        }
   	 }
     echo '</table>';
   }
 ?>
</form>
</body>
</html>