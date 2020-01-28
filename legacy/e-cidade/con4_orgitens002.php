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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

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
<script>
function js_pesquisaitemcad(jsvalor,jsmodulos){
  if(document.form1.ambiente[0].checked)
    ambiente = '1';
  else
    ambiente = '0';
  location.href='con4_orgitens003.php?coditem='+jsvalor+'&modulos='+jsmodulos+'&ambiente='+ambiente;
}
</script>



</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >

<table width="790" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br/>
<table width="790" align="center" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td align= "center" height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <form name="form1" method="post">
        <?
          if(!isset($mod)){
        ?>
        <table border="0" cellspacing="0" cellpadding="0">
	        
          <tr>
	          <td>
              <strong>Pesquisa:</strong><br/>
              <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25">
            </td>
          </tr>

	        <tr>
	          <td> 
              <strong>M&oacute;dulo:</strong><br/>
	            <select onDblClick="document.form1.mod.click()" name="modulos" size="18"  >
                <?
	                $result = db_query("select db_modulos.id_item,nome_modulo,descr_modulo 
	                                      from db_modulos
	                                     inner join db_itensmenu on db_itensmenu.id_item = db_modulos.id_item
	                                     where libcliente is true
	                                     order by lower(nome_modulo)");
	                $numrows = pg_numrows($result);
		              for($i = 0;$i < $numrows;$i++) {
		                echo "<option value=\"".pg_result($result,$i,"id_item")."\">".pg_result($result,$i,"nome_modulo")."</option>\n";
		              }  
		            ?>
              </select> 
	          </td>
	        </tr>
	        
          <tr>
	          <td>
		          <input onClick="if(document.form1.modulos.selectedIndex == -1 ) { alert('Selecione um módulo!'); return false; }" name="mod" type="submit" id="selecionar" value="Selecionar">
            </td>
	        </tr>

	      </table>
	      <?
	        } else {
	          $result = db_query("select nome_modulo,descr_modulo from db_modulos where id_item = ".$modulos);
	          $mod = pg_result($result,0,0);
	          $des = pg_result($result,0,1);
	      ?>
        <table border="1" align="center" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" cellspacing="0" cellpadding="0">
          	   
                <tr>
          		    <td>Módulo:</td>
          		    <td nowrap><?=$mod?>&nbsp;&nbsp;<input size="1" type="button" onclick="js_pesquisaitemcad('<?=$modulos?>','<?=$modulos?>')" id="ID<?=$modulos?>" name="CHECK<?=$modulos?>" value="<?=$modulos?>" ><font style="font-size:10px">(<?=$des?>)</font></td>		
          		  </tr>

          	  </table>
            </td>
          </tr>

          <tr>
            <td valign="top"></td>
          </tr>

          <tr>
		        <td align="center">
              <strong>Ambiente:</strong>
			        <input name="modulos" type="hidden" value="<?=$modulos?>" />
			        <input name="mod" type="hidden" value="selecionar" />
			        <input name="ambiente" type="radio" id="web" value="1" onClick="document.form1.submit()" 
                <? echo isset($ambiente)?($ambiente=="1"?"checked":""):"checked" ?>
              /> 
              <label for="web"><strong>Web</strong></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input type="radio" name="ambiente" id="caracter" onClick="document.form1.submit()" value="0" <? echo isset($ambiente)?($ambiente=="0"?"checked":""):"" ?>>
              <label for="caracter"><strong>Caracter</strong></label>
	            <input name='retornar' type='button' value='Retornar' onclick="location.href='con4_orgitens002.php'" /> 
			      </td>
		      </tr>
          <tr>
            <td valign="top">
              <table border="1" cellspacing="0" cellpadding="0">	  
                <tr> 
                  <td> 
		                <? 
		                $ambiente = (!isset($ambiente)?"1":$ambiente);		  		   
  		   	          $wid = 15;
  			            $conta = 0;
  			            /***************/			
                    function submenus($item,$id,$mod) {
  
                      $sOrdenacao = DBMenu::getCampoOrdenacao();  
  			              global $conta;
  			              global $wid;
  			              global $ambiente;
                      $sub = db_query("select m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo 
                                         from db_menu m 
  			                                 left join db_itensmenu i on i.id_item = m.id_item_filho 
                                        where m.modulo = $mod 
  							                          and m.id_item = $item 
  							                          and i.itemativo = $ambiente                              
                                        order by m.id_item, {$sOrdenacao} asc");			  
  
  			              $numrows = pg_numrows($sub);
                      if($numrows > 0) {
                        
                        for($x = 0;$x < $numrows;$x++) {                  
  
				                  $valor = pg_result($sub,$x,"id_item_filho");
                          echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" >
                          <input size=\"1\" onClick=\"js_pesquisaitemcad('$valor','".$GLOBALS['modulos']."')\" type=\"button\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" >
				                  <label for=\"ID$valor\">".pg_result($sub,$x,"descricao")."</label><br>\n";
				                  $wid += 15;
				                  $conta++;
				                  submenus(pg_result($sub,$x,"id_item_filho"),$id,$mod);
				                  $wid -= 15;
                        }				                
                      }
                    }
			              /**************/
                    $sOrdenacao = DBMenu::getCampoOrdenacao();  
		                $SQL = "select i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao 
	                            from db_itensmenu i 
	                           inner join db_menu m on m.id_item_filho = i.id_item 
	                           where m.modulo = ".$modulos."
							                 and i.itemativo = $ambiente							   
							                 and m.id_item = {$modulos} 
                             order by {$sOrdenacao} asc";
                    $result = db_query($SQL);			
                    for($i = 0;$i < pg_numrows($result);$i++) {

			                $valor = pg_result($result,$i,"id_item_filho");
                      echo "<td id=\"col$i\" valign=\"top\" nowrap>\n
                      <input size=\"1\" type=\"button\" onclick=\"js_pesquisaitemcad('$valor','".$modulos."')\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" >
			                <label for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
                      submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($modulos,"##"));
			                echo "</td>\n";
                    }	   
		                ?> 
		              </td>
                </tr>                
              </table>
            </td>
          </tr>
        </table>
	      <?
	        }
	      ?>	  
        </form>
      </center>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>