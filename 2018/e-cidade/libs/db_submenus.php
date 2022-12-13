<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$wid = 15;
$ambiente = $ambiente==""?"1":$ambiente;
$conta = 0;
function submenus($item,$id,$mod) {

  global $wid;
  global $ambiente;
  global $conta;	
  $sOrdenacao = DBMenu::getCampoOrdenacao();		  			  
  $sub = db_query("select m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo
                     from db_menu m 
                    inner join db_itensmenu i
                       on i.id_item = m.id_item_filho 
                    where m.modulo = $mod
         					    and m.id_item = $item
							        and i.itemativo = '$ambiente'
                    order by {$sOrdenacao} asc");
							  
  if(pg_numrows($sub) > 0) {

    for($x = 0;$x < pg_numrows($sub);$x++) {

		  $valor = pg_result($sub,$x,"id_item")."#".pg_result($sub,$x,"id_item_filho")."#".pg_result($sub,$x,"modulo");
      echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" ><input onClick=\"js_marcaP1('$id','Img".$conta."');js_marcaP2('$id','Img".$conta."')\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" onmouseover=\"js_msg_status('".pg_result($sub,$x,"help")."')\" onmouseout=\"js_lmp_status()\"><label onmouseover=\"js_msg_status('".pg_result($sub,$x,"help")."')\" onmouseout=\"js_lmp_status()\" for=\"ID$valor\">".pg_result($sub,$x,1)."</label><br>\n";
  	  $wid += 15;
	    $conta++;
      submenus(pg_result($sub,$x,"id_item_filho"),$id,$mod); 
		  $wid -= 15;
    }
  }
}

$sOrdenacao = DBMenu::getCampoOrdenacao();
$result = db_query("select i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao 
                      from db_itensmenu i 
                     inner join db_menu m on m.id_item_filho = i.id_item 
	                   where m.modulo = ".db_strpos($HTTP_POST_VARS["modulos"],"##")."
							         and m.id_item = ".db_strpos($HTTP_POST_VARS["modulos"],"##")."
							         and i.itemativo = $ambiente
                     order by {$sOrdenacao} asc");
echo "<table border=\"1\" cellpadding=\"10\" cellspacing=\"0\">\n<tr border=\"1\">\n";
for($i = 0;$i < pg_numrows($result);$i++) {

	$valor = pg_result($result,$i,"id_item")."#".pg_result($result,$i,"id_item_filho")."#".pg_result($result,$i,"modulo");
  if($ambiente == "1") {

	  echo "<td id=\"col$i\" valign=\"top\" nowrap>\n<input onclick=\"js_marca('col$i',this)\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" onmouseover=\"js_msg_status('".pg_result($result,$i,"help")."')\" onmouseout=\"js_lmp_status()\"><label onmouseover=\"js_msg_status('".pg_result($result,$i,"help")."')\" onmouseout=\"js_lmp_status()\" for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
  } else {

		echo "<td id=\"col$i\" valign=\"top\" nowrap>\n";
  }  
	submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($HTTP_POST_VARS["modulos"],"##"));
	echo "</td>\n";
}
echo "</tr>\n</table>\n";
?>