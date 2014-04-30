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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$oDaoPermHerda = db_utils::getDao('db_permherda');

if(isset($HTTP_POST_VARS["atualizarperm"])) {
  
  $modulo   = $HTTP_POST_VARS["modulos"];
  $usuario  = $HTTP_POST_VARS["usuario"];
  $anousu   = $HTTP_POST_VARS["anousu"];
  $instit   = $HTTP_POST_VARS["instit"];
  $ambiente = $HTTP_POST_VARS["ambiente"];
  
  pg_exec("BEGIN");
  //primeiro delete os itens

}
if (isset($HTTP_POST_VARS["incluir"])) {
  
  db_postmemory($HTTP_POST_VARS); 
  pg_exec("begin");
  
  $oDaoPermHerda->excluir(null, $perfil);
  if ($oDaoPermHerda->erro_status == 0) {
    die("Erro (10). Excluindo db_permherda: " . $oDaoPermHerda->erro_banco);
  }
  if (isset($usuario)) {
    
    $num = sizeof($usuario);
    for ($i = 0; $i < $num; $i++) {
      
      $oDaoPermHerda->id_usuario = $usuario[$i];
      $oDaoPermHerda->id_perfil  = $perfil;
      $oDaoPermHerda->incluir($usuario[$i], $perfil);
      if ($oDaoPermHerda->erro_status == 0) {
        die ("Erro (14). Inserindo db_permherda.");
      }
    }
  }
  pg_exec("end");
  db_redireciona();
  
}
// atualiza heranca dos usuarios

if (isset($atuusuarios) || isset($atuperfil)) {
  
  pg_exec("begin");
  
  $oDaoPermHerda->excluir($usuario);
  //$sql = pg_exec("delete from db_permherda where id_usuario = $usuario");
  if (isset($usuarios)) {
    
    $qver = sizeof($usuarios);
    if ($qver > 0) {
    
      for($i=0;$i<$qver;$i++){
        
        $oDaoPermHerda->id_usuario = $usuario;
        $oDaoPermHerda->id_perfil  = $usuarios[$i];
        $oDaoPermHerda->incluir($usuario, $usuarios[$i]);
      }
    }
  }
  // atualiza heranca dos perfis
  if (isset($perfil)) {
    
    $qver = sizeof($perfil);
    if ($qver > 0) {
    
      for ($i=0;$i<$qver;$i++) {
        
        $oDaoPermHerda->id_usuario = $usuario;
        $oDaoPermHerda->id_perfil  = $usuarios[$i];
        $oDaoPermHerda->incluir($usuario, $perfil[$i]);
      }
    }
  }
  pg_exec("commit");
  $HTTP_POST_VARS["mod"] = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">
function js_marcaP1(tag,inp) {
  var tag = document.getElementById(tag);
  var inp = document.getElementById(inp);
   //marca o item principal
  for(var i = 0;i < tag.childNodes.length;i++) {    
    if(tag.childNodes[i].nodeName == "INPUT") {
	  tag.childNodes[i].checked = true;
	  break;
	}
  }
  //marca o item principal do submenu  
  var subm2 = inp;
  var wd = subm2.width;
  while(subm2 != null) {
    for(;;) {	
	  subm2 = subm2.previousSibling;
	  if(subm2 == null)	    
	    return true;	 
	  if(subm2.nodeName == "IMG")
	    break;
	}
	if(wd > subm2.width) {
	  subm2.nextSibling.checked = true;
	  wd = subm2.width;
	}
  }  
  return true;
}
/*
function js_marcaP1(tag,inp) {
  var tag = document.getElementById(tag);
  var inp = document.getElementById(inp);
   //marca o item principal
  for(var i = 0;i < tag.childNodes.length;i++) {    
    if(tag.childNodes[i].nodeName == "INPUT") {
	  tag.childNodes[i].checked = true;
	  break;
	}
  }
  //marca o item principal do submenu  
  var subm2 = inp;
  var wd = subm2.width;
  for(;;) {
    for(;;) {	
	  subm2 = subm2.previousSibling;
	  if(subm2 == null)	    
	    return true;
	  //alert(subm2.nodeName);
	  if(subm2.nodeName == "IMG")
	    break;
	}
	if(subm2.width > wd)
	  break;
	//alert(subm2.nodeName + ' = ' + subm2.width);
	if(wd > subm2.width) {
	  subm2.nextSibling.checked = true;
	  break;
	}
  }  
  return true;
}
*/
function js_marcaP2(tag,inp) {
  var tag = document.getElementById(tag);
  var inp = document.getElementById(inp);
  //marca todo o submenu  
  var inp2 = inp;
  for(;;) {
    for(;;) {
	  inp2 = inp2.nextSibling;
	  if(inp2 == null)
	    return true;
	  if(inp2.nodeName == "IMG")
	    break;
	}
      if(inp2.width > inp.width) {
	    if(inp.nextSibling.checked == true) {
	      inp.nextSibling.checked = true;
	      inp2.nextSibling.checked = true;
	    } else {
	      inp.nextSibling.checked = false;
	      inp2.nextSibling.checked = false;
	    }
	  } else
	    break;	
  }
  return true;
}
function js_marca(tag,inp) {
  var tag = document.getElementById(tag);
  
  if(inp.checked == true)
    var ck = true;
  else
    var ck = false;
  for(var i = 0;i < tag.childNodes.length;i++) {    
	if(tag.childNodes[i].nodeName == "INPUT") {
	  tag.childNodes[i].checked = ck;	  
	}
  }
/*
  for(var i in tag)
    document.getElementById('dd').innerHTML += i + ' = ' + tag[i] + '<br>';
*/	
  return true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr><td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
    <form name="form1" method="post">
      <?
	    if(!isset($HTTP_POST_VARS["selecionar"]) && !isset($HTTP_POST_VARS["mod"]) && !isset($HTTP_POST_VARS["verificar"])) {
	  ?>     
		  <table border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td><strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.instit)" size="25"></td>
             </tr>
             <tr> 
               <td> 
			   <strong>Institui&ccedil;&atilde;o:</strong><br> 
			<select onDblClick="document.form1.selecionar.click()" name="instit" size="18">                        
		 <?
		   if(db_getsession("DB_id_usuario") == "1" || db_getsession("DB_administrador") == "1"  ) {
		    $result = pg_exec("select codigo,nomeinst from db_config");
		  } else {
  	            
                    $result = pg_exec("select c.codigo,c.nomeinst 
		                   from db_config c where c.codigo = ".db_getsession("DB_instit"));

//                    $result = pg_exec("select c.codigo,c.nomeinst 
//		                   from db_config c
//				        inner join db_userinst u on u.id_instit = c.codigo where u.id_usuario = ".db_getsession("DB_id_usuario"));
		  }
		    for($i = 0;$i < pg_numrows($result);$i++) {
		      echo "<option ".($i == 0?'selected':'')." value=\"".pg_result($result,$i,"codigo")."\">".pg_result($result,$i,"nomeinst")."</option>\n";
		    }
	      ?>
             </select> 
			</td>
           </tr>
           <tr>
             <td><input onClick="if(document.form1.instit.selectedIndex == -1 ) { alert('Selecione uma Instituição!'); return false; }" name="selecionar" type="submit" id="selecionar" value="Selecionar"></td>
	       <?
	       if($i==1){
	         echo "<script>document.form1.selecionar.click()</script>";
	       }
	       ?>
           </tr>
          </table>
      <?
	  } else if(isset($HTTP_POST_VARS["selecionar"])) {
	  ?>
	   <table border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.usuario)" size="25"></td>
			<td>&nbsp;</td>
          </tr>
          <tr> 
            <td valign="top"><strong>Usuário:</strong><br>
	      <select onDblClick="document.form1.mod.click()" name="usuario" size="18" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
	        <optgroup style="text-align:center;color:#333333;background-color:#6699cc;letter-spacing:0.4em" label="Perfis">
		<?
		if(db_getsession("DB_id_usuario") == "1"  || db_getsession("DB_administrador") == "1" ) {
		  $result = pg_exec("select * from (select distinct u.id_usuario,u.nome,u.login 
                                     from db_usuarios u
                                          inner join db_permissao p on p.id_usuario = u.id_usuario 
                                     where u.usuarioativo = 1 and u.usuext = 2
                                      and p.id_instit = $instit ) as x
                                     order by lower(login) ");
		} else {
		  $result = pg_exec("select * from ( select distinct u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
                                          inner join db_permissao p on p.id_usuario = u.id_usuario

							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							  and p.id_instit = $instit
                                                          and u.usuext = 2 ) as x
							 order by lower(login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  echo "<option style='text-align:left;color:black;letter-spacing:normal;' value=\"".pg_result($result,$i,"id_usuario")."\" onClick=\"document.form1.perfil.value='".pg_result($result,$i,"nome")."'\">".pg_result($result,$i,"nome")."</option>\n";
		  //echo "<option ".(pg_result($result,$i,"usuext") == 2?"style='color: blue;font-weight:bold'":"")." value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
	       </select> 
		<input type='hidden' name ='perfil' value="">
   	     </td>
            <td valign="top" width="80" align="right"> <strong>Exercício:</strong><br> 
	        <input type="hidden" name="instit" value="<?=$HTTP_POST_VARS["instit"]?>">	  
			<select name="anousu" id="anousu">
			    <?
	  		   $ano = date("Y");
	         for($i=($ano-10); $i < $ano+7; $i++){
          	 echo "<option ";
          	  if ($i == $ano){
          	  	echo " selected ";
          	  }	
          	 echo " value=\"".$i."\">".$i."</option>\n";	         	 	
	         }
	        ?>
              </select> 
			 </td>
          </tr>
		  <tr>
		    <td>
			  <input onClick="if(document.form1.usuario.selectedIndex == -1 ) { alert('Selecione um usuário!'); return false; }" name="mod" type="submit" id="selecionar" value="Selecionar">
			</td>
		  </tr>
		 </table>
      <?
        }elseif(isset($mod)){
	  db_postmemory($HTTP_POST_VARS);
          $sql = "select db_permherda.id_usuario from db_usuarios inner join db_permherda on db_usuarios.id_usuario = db_permherda.id_usuario where db_permherda.id_perfil = $usuario"; 
	  $res = pg_query($sql);
	  $usuarios = "";
	  $vir = "";
	  if(pg_numrows($res) > 0){
            $usuarios[0] = "";
	    for($i=0;$i<pg_numrows($res);$i++){				
	      $usuarios[$i] = pg_result($res,$i,0);
	    }
	  }
	   ?>
	   <table border="0" cellspacing="0" cellpadding="0">
          <tr>
           <br> 
	   <input type='hidden' name='perfil' value='<?=$usuario?>'>
            <td valign="top"><strong>Perfil: </strong><?=$perfil?><br>
	      <select multiple onDblClick="document.form1.incluir.click()" name="usuario[]" size="18" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
	        <optgroup style="text-align:center;color:#333333;background-color:#6699cc;letter-spacing:0.4em" label="Usuários">
		<?
		if(db_getsession("DB_id_usuario") == "1") {
		  $result = pg_exec("select id_usuario,nome,login from db_usuarios where usuarioativo = 1 and usuext <> 2 order by lower(login)");
		} else {
		  $result = pg_exec("select u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							 and u.usuext <> 2
							 order by lower(u.login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  db_fieldsmemory($result,$i);
		  echo "<option ".(in_array($id_usuario,$usuarios)?"selected":"" )." style='text-align:left;color:black;letter-spacing:normal;' value=\"".pg_result($result,$i,"id_usuario")."\" >".pg_result($result,$i,"login")." - ".pg_result($result,$i,"nome")."</option>\n";
		  //echo "<option ".(pg_result($result,$i,"usuext") == 2?"style='color: blue;font-weight:bold'":"")." value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
	       </select> 
   	     </td>
	     <?
	     ?>
            <td valign="top" width="80" align="right"> <strong>Exercício:</strong><br> 
	        <input type="hidden" name="instit" value="<?=$HTTP_POST_VARS["instit"]?>">	  
			<select name="anousu" id="anousu">
           <?
           $ano = date("Y");
	         for($i=($ano-10); $i < $ano+7; $i++){
          	 echo "<option ";
          	  if ($i == $ano){
          	  	echo " selected ";
          	  }	
          	 echo " value=\"".$i."\">".$i."</option>\n";	         	 	
	         }
		      ?>
              </select> 
			 </td>
          </tr>
		  <tr>
		    <td>
			  <input onClick="if(document.form1.usuario.selectedIndex == -1 ) { alert('Selecione um usuário!'); return false; }" name="incluir" type="submit" id="selecionar" value="Selecionar">
			</td>
		  </tr>
		 </table>
		 <?
	} else if(isset($HTTP_POST_VARS["verificar"])) {
		  $result = pg_exec("select nome_modulo,descr_modulo from db_modulos where id_item = ".$HTTP_POST_VARS["modulos"]);
	  $mod = pg_result($result,0,0);
	  $des = pg_result($result,0,1);
	  $result = pg_exec("select login,nome from db_usuarios where id_usuario = ".$HTTP_POST_VARS["usuario"]);
	  $log = pg_result($result,0,0);
	  $nom = pg_result($result,0,1);
	  $result = pg_exec("select nomeinst from db_config where codigo = ".$HTTP_POST_VARS["instit"]);
	  $ins = pg_result($result,0,0);
	  ?>
	  <input type="hidden" name="modulos" value="<?=$HTTP_POST_VARS["modulos"]?>">
	  <input type="hidden" name="usuario" value="<?=$HTTP_POST_VARS["usuario"]?>">
	  <input type="hidden" name="anousu" value="<?=$HTTP_POST_VARS["anousu"]?>">
	  <input type="hidden" name="instit" value="<?=$HTTP_POST_VARS["instit"]?>">	  
<table border="1" cellspacing="0" cellpadding="0">
<tr><td>
       <table border="0" cellspacing="0" cellpadding="0">
	   <tr>
		   <td>Instituição:</td>
		   <td nowrap><?=$ins?></td>
		 </tr>
	     <tr>
		   <td>Módulo:</td>
		   <td nowrap><?=$mod?>&nbsp;&nbsp;<font style="font-size:10px">(<?=$des?>)</font></td>
		 </tr>
		 <tr>
		   <td>Usuário:</td>
		   <td nowrap><?=$log?>&nbsp;&nbsp;<font style="font-size:10px">(<?=$nom?>)</font></td>
		 </tr>
		 <tr>
		   <td>Exercício:</td>
		   <td><?=$HTTP_POST_VARS["anousu"]?></td>
		 </tr>
	  </table>
</td></tr>
<tr><td valign="top">
       <table border="0" cellspacing="0" cellpadding="0">
	     <tr>
		   <td><input type="submit" name="atualizarperm" value="Atualizar Permiss&otilde;es">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <input type="submit" name="mod" value="Retornar">
		   </td>
		 </tr>
	  </table>
</td></tr>
<tr>
		  <tr>
		    <td align="center"><strong>Ambiente:</strong>
			<input name="verificar" type="hidden" value="verificar">
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
			  global $conta;
			  global $wid;
			  global $ambiente;
			  global $HTTP_POST_VARS;
              $sub = pg_exec("select p.id_item as perm,m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo
                              from db_menu m 
                              inner join db_itensmenu i
                              on i.id_item = m.id_item_filho 
							  left outer join db_permissao p
                              on p.id_item = m.id_item_filho 
							  and p.id_usuario = ".$HTTP_POST_VARS["usuario"]."
							  and p.anousu = ".$HTTP_POST_VARS["anousu"]."
							  and p.id_instit = ".$HTTP_POST_VARS["instit"]."
							  and p.id_modulo = ".$HTTP_POST_VARS["modulos"]."							  
                              where m.modulo = $mod
							  and m.id_item = $item
							  and i.itemativo = $ambiente order by id_item,menusequencia"  );			  
			  $numrows = pg_numrows($sub);
              if($numrows > 0) {
                for($x = 0;$x < $numrows;$x++) {                  
				  $valor = pg_result($sub,$x,"id_item_filho");
                  echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" ><input onClick=\"js_marcaP1('$id','Img".$conta."');js_marcaP2('$id','Img".$conta."')\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" onmouseover=\"js_msg_status('".pg_result($sub,$x,"help")."')\" onmouseout=\"js_lmp_status()\" ".(pg_result($sub,$x,"perm")==""?"":"checked").">
				  <label onmouseover=\"js_msg_status('".pg_result($sub,$x,"help")."')\" onmouseout=\"js_lmp_status()\" for=\"ID$valor\">".pg_result($sub,$x,"descricao")."</label><br>\n";
				  $wid += 15;
				  $conta++;
				  submenus(pg_result($sub,$x,"id_item_filho"),$id,$mod);
				  $wid -= 15;
                }				                
              }
            }
			/**************/
		$SQL = "select p.id_item as perm,i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao 
	                           from db_itensmenu i 
	                           inner join db_menu m 
	                           on m.id_item_filho = i.id_item 
							   left outer join db_permissao p
                               on p.id_item = m.id_item_filho 
							   and p.id_usuario = ".$HTTP_POST_VARS["usuario"]."
							   and p.anousu = ".$HTTP_POST_VARS["anousu"]."
							   and p.id_instit = ".$HTTP_POST_VARS["instit"]."
							   and p.id_modulo = ".$HTTP_POST_VARS["modulos"]."
	                           where m.modulo = ".$HTTP_POST_VARS["modulos"]."
							   and i.itemativo = $ambiente							   
							   and m.id_item = ".$HTTP_POST_VARS["modulos"];
            $result = pg_exec($SQL);			
            for($i = 0;$i < pg_numrows($result);$i++) {
			  $valor = pg_result($result,$i,"id_item_filho");
              echo "<td id=\"col$i\" valign=\"top\" nowrap>\n<input onclick=\"js_marca('col$i',this)\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" onmouseover=\"js_msg_status('".pg_result($result,$i,"help")."')\" onmouseout=\"js_lmp_status()\" ".(pg_result($result,$i,"perm")==""?"":"checked").">
			  <label onmouseover=\"js_msg_status('".pg_result($result,$i,"help")."')\" onmouseout=\"js_lmp_status()\" for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
              submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($HTTP_POST_VARS["modulos"],"##"));
			  echo "</td>\n";
            }	   
		   ?> 
		   </td>
         </tr>
		 <tr>
		 <td>
		 <? /*
		 $result = pg_exec("select id_item 
		                    from db_permissao 
							where id_usuario = ".$HTTP_POST_VARS["usuario"]." 
							and anousu = ".$HTTP_POST_VARS["anousu"]);
		 $numrows = pg_numrows($result);
		 for($i = 0;$i < $numrows;$i++) {
		   echo pg_result($result,$i,0)."<br>";
		 }
		*/ ?>
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
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </td></tr>
</table>
</body>
</html>