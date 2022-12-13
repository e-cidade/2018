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

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

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
<table width="100%" align='center' border="0" cellspacing="0" cellpadding="0">
<tr><td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<center>
<form name="form1" method="post">
<?
if(!isset($HTTP_POST_VARS['mod'])){
  ?>
	<input name="proced" type="hidden" value="<?=(isset($proced)?$proced:false)?>">
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
  </tr>
  <Tr>
  <td> <strong>M&oacute;dulo:</strong><br> 
  <select onDblClick="document.form1.mod.click()" name="modulos" size="18"  >
  <?
  $result = db_query("select db_modulos.id_item,nome_modulo,descr_modulo 
  from db_modulos 
       inner join db_itensmenu on db_modulos.id_item = db_itensmenu.id_item
  where libcliente is true
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
  $result = db_query("select nome_modulo,descr_modulo from db_modulos where id_item = ".$HTTP_POST_VARS["modulos"]);
  $mod = pg_result($result,0,0);
  $des = pg_result($result,0,1);
  ?>
  <table border="1" cellspacing="0" cellpadding="0">
  <tr><td>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td>Módulo:</td>
    <td nowrap>
      <?=$mod?>&nbsp;&nbsp;<font style="font-size:10px">(<?=$des?>)</font>
  </td>
  </tr>
  </table>
  </td></tr>
  <tr><td valign="top">
  </td></tr>
  <tr>
  <tr>
  <td align="center" style='display:none'><strong>Ambiente:</strong>
  <input name="modulos" type="hidden" value="<?=$HTTP_POST_VARS["modulos"]?>">
  <input name="mod" type="hidden" value="selecionar">
  <input name="ambiente" type="radio" id="web" value="1" onClick="document.form1.submit()" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="1"?"checked":""):"checked" ?>> 
  <label for="web"><strong>Web</strong></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  <input type="radio" name="ambiente" id="caracter" onClick="document.form1.submit()" value="0" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="0"?"checked":""):"" ?>>
  <label for="caracter"><strong>Caracter</strong></label>
  </td>
  </tr>
  <td valign="top">
  <script>
  function js_mostradiv(liga,evt,vlr,vlr1,vlr2){
    evt= (evt)?evt:(window.event)?window.event:""; 
    if(liga){
      document.getElementById('vlr').innerHTML=vlr;
      document.getElementById('vlr1').innerHTML=vlr1;
      document.getElementById('vlr2').innerHTML=vlr2;
      document.getElementById('divlabel').style.left=evt.clientX - 60;
      document.getElementById('divlabel').style.top=evt.clientY + 30;
      document.getElementById('divlabel').style.visibility='visible';
    }else{
      document.getElementById('divlabel').style.visibility='hidden';
    }  
  }
  </script>
  <div align="left" id="divlabel" style="position:absolute; z-index:12; top:25; left:600; visibility: hidden; border: 2px outset #666666; background-color: #6699cc; font-style:italic;">
  <table cellpadding="2" border='1'>
  <tr nowrap>
  <td align="center" nowrap>
  <strong>Login:</strong><span color="#9966cc" id="vlr1"></span>&nbsp;&nbsp;&nbsp;<br> 
  </td>
  <td align="center" nowrap>
  <strong>Usuário:</strong><span color="#9966cc" id="vlr"></span><br> 
  </td>
  <td align="center" nowrap>
  <strong>Ano-Instituicao:</strong><span color="#9966cc" id="vlr2"></span><br> 
  </td>
  </tr>
  </table>  
  </div>
  <table border="1" width="100%" height="100%" cellspacing="0" cellpadding="0">	  
  <tr> 
  <td> 
  <? 
  $ambiente = (!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"]);		  		   
  $wid = 15;
  $conta = 0;
	if (!isset($HTTP_POST_VARS["proced"])) {
		$HTTP_POST_VARS["proced"] = false;
	}
  /***************/
  function submenus($item,$id,$mod) {
    global $conta;
    global $wid;
    global $ambiente;
    global $libcliente;
    global $HTTP_POST_VARS;
		global $descrproced;
    $sOrdenacao = DBMenu::getCampoOrdenacao();
    $sub = db_query("select m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo, libcliente
    from db_menu m 
    inner join db_itensmenu i on i.id_item = m.id_item_filho 
    where m.modulo = $mod 
    and m.id_item = $item 
    and i.itemativo = $ambiente
    order by {$sOrdenacao}");			  
    $numrows = pg_numrows($sub);
    if($numrows > 0) {
      for($x = 0;$x < $numrows;$x++) {
        $libcliente = pg_result($sub,$x,"libcliente");
        $valor = pg_result($sub,$x,"id_item_filho");
        $sub1 = db_query("
        select distinct nome,login
        from db_permissao p inner join db_usuarios u on u.id_usuario = p.id_usuario
        where p.id_item = $valor" 
        );			 
        $numrows1 = pg_numrows($sub1);
        $usuarios = "<br>";
        $login    = "<br>";
        for($y = 0;$y < $numrows1;$y++) {                  
          $usuarios .= pg_result($sub1,$y,"nome")."<br>";
          $login    .= pg_result($sub1,$y,"login")."<br>";
        }
        
        $sqlsub2 = "select distinct nome,login,anousu,id_instit  
        from db_permissao p inner join db_usuarios u on u.id_usuario = p.id_usuario
        where p.id_item = $valor";
        $sub2 = db_query($sqlsub2);
        $anos    = "<br>";
        if (pg_numrows($sub2) > 0) {
          $ultimo= pg_result($sub2,0,"login");
          for($z = 0;$z < pg_numrows($sub2);$z++) {
            if ($ultimo != pg_result($sub2,$z,"login")) {;
              $anos .= "<br>";
              $ultimo = pg_result($sub2,$z,"login");
            }
            $anos .= pg_result($sub2,$z,"anousu")."-".pg_result($sub2,$z,"id_instit")."|";
          }
        }
        $anos .= "<br>";
        
        echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" ><input size=\"1\" onClick=\"parent.js_pesquisaitemcad('$valor','".$HTTP_POST_VARS["modulos"]."')\" type=\"button\" id=\"ID$valor\" name=\"CHECK$valor\" ".($libcliente=="f"?"style=\"background-color:blue\" title=\"Bloqueado Cliente\"":"")." value=\"$valor\" >
        <label for=\"ID$valor\">".pg_result($sub,$x,"descricao")."</label>";
				
				if (isset($HTTP_POST_VARS["proced"]) and $HTTP_POST_VARS["proced"] == true) {
					$sqlproced = "select descrproced from db_syscadproceditem 
												inner join db_syscadproced on db_syscadproceditem.codproced = db_syscadproced.codproced
												where id_item = $valor";
					$resultproced = db_query($sqlproced) or die($sqlproced);
					$mostrar="";
					if (pg_numrows($resultproced) > 0) {
						for ($xxx=0; $xxx <  pg_numrows($resultproced); $xxx++) {
							db_fieldsmemory($resultproced,$xxx);
							$mostrar .= substr($descrproced,0,20) . ($xxx == pg_numrows($resultproced) -1?"":" / ");
						}
					}
					echo " - <font color=\"red\">$mostrar<font color=\"black\">";
				}

				echo "<br>\n";
        //<label onmouseover=\"js_mostradiv(true,event,'$usuarios','$login','$anos')\" onmouseout=\"js_mostradiv(false,event)\" for=\"ID$valor\">".pg_result($sub,$x,"descricao")."</label>
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
  inner join db_menu m 
  on m.id_item_filho = i.id_item 
  where m.modulo = ".$HTTP_POST_VARS["modulos"]."
  and i.itemativo = $ambiente							   
  and m.id_item = ".$HTTP_POST_VARS["modulos"]." order by {$sOrdenacao} ";
  $result = db_query($SQL);			
  for($i = 0;$i < pg_numrows($result);$i++) {
    $valor = pg_result($result,$i,"id_item_filho");
    echo "<td id=\"col$i\" valign=\"top\" nowrap>\n<input size=\"1\" type=\"button\" onclick=\"js_trocacor(this);parent.js_pesquisaitemcad('$valor','".$HTTP_POST_VARS["modulos"]."');\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" >
    <label for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
    submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($HTTP_POST_VARS["modulos"],"##"));
    echo "</td>\n";
  }	   
  ?> 
  </td>
  </tr>
  </table>
  </td></tr>
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
<script>
function js_trocacor(obj){
}
</script>