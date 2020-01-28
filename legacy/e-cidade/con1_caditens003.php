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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
echo($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS,2);
// recebe como parametro duas variaveis com os nomes de:
// funcao_objeto=[nome da funcao sem as ()]
// objelemento=[nome do input que recebera os itens selecionados]
// Quando clicar no gravar o sistema verifica os ítens clicados e envia para a funcao
// não esquecer de colocar no nome da função "parent." para que saiba que é da página anterior
// quando for o caso.
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_envia_itens(){
  var F = document.form1;
  var itens = '';
  for(i=0;i<F.elements.length;i++){
    if(F.elements[i].type == 'checkbox' && F.elements[i].checked ){
       itens = itens +"-"+ F.elements[i].value + '-';
    }
  }
  <?=$funcao_objeto?>(itens);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr><td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
    <form name="form1" method="post">
	<input name="objeto_conteudo" type="hidden" value="<?=$objeto_conteudo?>">
     <?
     if(!isset($HTTP_POST_VARS['mod'])){
      ?>
      <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
      </tr>
	  <Tr>
	    <td> <strong>M&oacute;dulo:</strong><br> 
	  <select onDblClick="document.form1.mod.click()" name="modulos" size="18"  >
        <?
	    $result = pg_exec("select id_item,nome_modulo,descr_modulo 
	    from db_modulos 
	    order by lower(nome_modulo)");
	    $numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		   echo "<option value=\"".pg_result($result,$i,"id_item")."\">".pg_result($result,$i,"nome_modulo")."</option>\n";
		}  
		?>
        </select> 
	    </td>
	  </Tr>
	  <tr>
	    <td>
		<input onClick="if(document.form1.modulos.selectedIndex == -1 ) { alert('Selecione um módulo!'); return false; }" name="mod" type="submit" id="selecionar" value="Selecionar"></td>
	  </tr>
	  </table>
	  <?
	  } else if(isset($HTTP_POST_VARS["mod"])) {
		  $result = pg_exec("select nome_modulo,descr_modulo from db_modulos where id_item = ".$HTTP_POST_VARS["modulos"]);
	      $mod = pg_result($result,0,0);
	      $des = pg_result($result,0,1);
	  ?>
<table border="1" cellspacing="0" cellpadding="0">
<tr><td>
       <table border="0" cellspacing="0" cellpadding="0">
	     <tr>
		<td>Módulo:</td>
		<td nowrap><?=$mod?>&nbsp;&nbsp;
		   <font style="font-size:10px">(<?=$des?>)</font>&nbsp;&nbsp;
		   <input name="retorna" value='Retornar' type="button" onclick="location.href='con1_caditens003.php?funcao_objeto=<?=$funcao_objeto?>&objeto_elemento=<?=$objeto_elemento?>&objeto_conteudo=<?=$objeto_conteudo?>'">&nbsp;&nbsp;
		   <input name="gravar" value='Gravar' type="button" onclick="js_envia_itens();parent.db_prog_iframe.hide();">
	       </td>
	     </tr>
	  </table>
</td></tr>
<tr><td valign="top">
</td></tr>
<tr>
		    <td align="center"><strong>Ambiente:</strong>
			<input name="modulos" type="hidden" value="<?=$HTTP_POST_VARS["modulos"]?>">
			<input name="mod" type="hidden" value="selecionar">
			<input name="ambiente" type="radio" id="web" value="1" onClick="document.form1.submit()" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="1"?"checked":""):"checked" ?>> 
             <label for="web"><strong>Web</strong></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
             <input type="radio" name="ambiente" id="caracter" onClick="document.form1.submit()" value="0" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="0"?"checked":""):"" ?>>
             <label for="caracter"><strong>Caracter</strong></label>
			</td>
		  </tr>
<td valign="top">
      <table border="1" cellspacing="0" cellpadding="0">	  
         <tr> 
           <td> 
		   <?
		   $ambiente = (!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"]);		  		   
		   	$wid = 15;
			$conta = 0;
			/***************/			
            function submenus($item,$id,$mod) {
			  global $conta, $objeto_conteudo;
			  global $wid;
			  global $ambiente;
			  global $HTTP_POST_VARS;
              $sub = pg_exec("select m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo 
                              from db_menu m 
			           inner join db_itensmenu i on i.id_item = m.id_item_filho 
                              where m.modulo = $mod  and m.id_item = $item 
						     and i.itemativo = $ambiente");			  
			  $numrows = pg_numrows($sub);
              if($numrows > 0) {
                for($x = 0;$x < $numrows;$x++) {                  
		  $valor = pg_result($sub,$x,"id_item_filho");
	          $objeto_conteudo = str_replace($objeto_conteudo,"-".$valor."-",'');
                  echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" >
	             <input size=\"1\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" ";
		     if(strpos("###".$HTTP_POST_VARS["objeto_conteudo"],"-".$valor."-")>0)
		       echo "checked";
	             echo   ">  <label for=\"ID$valor\">".pg_result($sub,$x,"descricao")."</label><br>\n";
				  $wid += 15;
				  $conta++;
				  submenus(pg_result($sub,$x,"id_item_filho"),$id,$mod);
				  $wid -= 15;
                }				                
              }
            }
			/**************/
		$SQL = "select i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao 
	                           from db_itensmenu i 
	                           inner join db_menu m 
	                           on m.id_item_filho = i.id_item 
	                           where m.modulo = ".$HTTP_POST_VARS["modulos"]."
							   and i.itemativo = $ambiente							   
							   and m.id_item = ".$HTTP_POST_VARS["modulos"];
            $result = pg_exec($SQL);			
            for($i = 0;$i < pg_numrows($result);$i++) {
	      $valor = pg_result($result,$i,"id_item_filho");
	      $objeto_conteudo = str_replace($objeto_conteudo,"-".$valor."-",'');
              echo "<td id=\"col$i\" valign=\"top\" nowrap>\n
	             <input size=\"1\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" ";
	      if(strpos("###".$HTTP_POST_VARS["objeto_conteudo"],"-".$valor."-")>0)
		 echo "checked";
	      echo   "> <label for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
              submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($HTTP_POST_VARS["modulos"],"##"));
			  echo "</td>\n";
            }	   
           if($objeto_conteudo!=""){
	    ?>
	    <script>
	    <?=$objeto_elemento?>.value = '<?=$objeto_conteudo?>';
	    </script>
	    <?
	   }
	   ?>
	    </td>
         </tr>
       </table>
</td>
</table>
	<?
	}
	?>	  
    </form>
  </center>
  </td></tr>
</table>
</body>
</html>