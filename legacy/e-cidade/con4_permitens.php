<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

if (isset($HTTP_POST_VARS["atualizarperm"])) {
  
  $modulo   = $HTTP_POST_VARS["modulos"];
  $usuario  = $HTTP_POST_VARS["usuario"];
  $anousu   = $HTTP_POST_VARS["anousu"];

  $instit = isset($HTTP_POST_VARS["instit"]) ? $HTTP_POST_VARS["instit"] : db_getsession("DB_instit");

  /**
   * Instanciamos as classes 
   */
  $oDaoDBItensMenu = db_utils::getDao('db_itensmenu');
  $oDaoDBPermissao = db_utils::getDao('db_permissao');
  $oDaoDBUsuarios  = db_utils::getDao('db_usuarios');
  $ambiente        = $HTTP_POST_VARS["ambiente"];
  
  db_query("BEGIN");
    
  /**
   * primeiro delete os itens
   */
  $sWhereItensMenu = " id_item in ( select i.id_item 
                                     from db_itensmenu i
  			                            inner join db_permissao p on p.id_item = i.id_item and p.id_usuario = $usuario
  		                                                       and p.anousu = $anousu
  		                                                       and p.id_instit = $instit
  		                                                       and p.id_modulo = $modulo
                                    where i.itemativo = $ambiente)
  		                 and id_usuario = $usuario
  		                 and anousu     = $anousu
  		                 and id_instit  = $instit
  		                 and id_modulo  = $modulo";
  
  
  $oDaoDBPermissao->excluir(null, null, null, null, null, $sWhereItensMenu);
  if ($oDaoDBPermissao->erro_status == 0) {
    die("Erro(22) excluindo db_permissao: " . $oDaoDBPermissao->erro_banco);
  }
  
  //inclui novamente os itens
  
  $sWherePermissao  = "     id_usuario = {$usuario}";
  $sWherePermissao .= " and id_item    = {$modulo}";
  $sWherePermissao .= " and anousu     = {$anousu}";
  $sWherePermissao .= " and id_instit  = {$instit}";
  $sWherePermissao .= " and id_modulo  = {$modulo}";
  
  $oDaoDBPermissao->excluir(null, null, null, null, null, $sWherePermissao);

  /**
   * Apaga os caches de menu do usuario, para o modulo que foi atualizado.
   */
  $sSqlUsuario = $oDaoDBUsuarios->sql_query_file($usuario, "usuext");
  $rsUsuario   = $oDaoDBUsuarios->sql_record( $sSqlUsuario );

  $oUsuario = db_utils::fieldsMemory($rsUsuario, 0);

  /**
   * Verifica se o usuário é um perfil.
   * Caso seja então limpa o cache dos usuários vinculados a ele
   */
  if ($oUsuario->usuext == '2') {
    $oDaoDBPermHerda = db_utils::getDao('db_permherda');

    $sSqlPermheda = $oDaoDBPermHerda->sql_query_file(null, $usuario, "id_usuario");
    $rsPermherda  = $oDaoDBPermHerda->sql_record( $sSqlPermheda );

    if ($oDaoDBPermHerda->numrows > 0) {

      for ($iIndice = 0; $iIndice < $oDaoDBPermHerda->numrows; $iIndice++) {
        $oPermherda = db_utils::fieldsMemory($rsPermherda, $iIndice);

        DBMenu::limpaCache($oPermherda->id_usuario, $instit, $modulo);
      }
    }
  }

  DBMenu::limpaCache($usuario, $instit, $modulo);

  $grava_permissoes = true;
  $tam_vetor = sizeof($HTTP_POST_VARS);

  reset($HTTP_POST_VARS);

  for ($i = 0; $i < $tam_vetor; $i++) {
    
    if (db_indexOf(key($HTTP_POST_VARS), "CHECK") > 0) {
      
      if ($grava_permissoes == true) {
        
        $oDaoDBPermissao->id_usuario     = $usuario;
        $oDaoDBPermissao->id_item        = $modulo;
        $oDaoDBPermissao->permissaoativa = 1;
        $oDaoDBPermissao->anousu         = $anousu;
        $oDaoDBPermissao->id_instit      = $instit;
        $oDaoDBPermissao->id_modulo      = $modulo;
        
        $oDaoDBPermissao->incluir($usuario, $modulo, $anousu, $instit, $modulo);
        if ($oDaoDBPermissao->erro_status == 0) {
          die("Erro(18) inserindo em db_permissao: " . $oDaoDBPermissao->erro_msg);
        }
        $grava_permissoes = false;
      }
  
      $oDaoDBPermissao->id_usuario     = $usuario;
      $oDaoDBPermissao->id_item        = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
      $oDaoDBPermissao->permissaoativa = 1;
      $oDaoDBPermissao->anousu         = $anousu;
      $oDaoDBPermissao->id_instit      = $instit;
      $oDaoDBPermissao->id_modulo      = $modulo;
      
      $oDaoDBPermissao->incluir($usuario, $HTTP_POST_VARS[key($HTTP_POST_VARS)], $anousu, $instit, $modulo);
      if ($oDaoDBPermissao->erro_status == 0) {
        die("Erro(18) inserindo em db_permissao: " . $oDaoDBPermissao->erro_msg);
      }
    }
    next($HTTP_POST_VARS);
  }
  db_query("COMMIT");  
}

// atualiza heranca dos usuarios

if (isset($atuusuarios) || isset($atuperfil)) {
  
  $oDaoPermHerda = db_utils::getDao('db_permherda');
  
  db_query("begin");
  
  $sWherePermHerda  = "     db_permherda.id_perfil  in ( select db_userinst.id_usuario ";  
  $sWherePermHerda .= "                                    from db_userinst ";
  $sWherePermHerda .= "                                   where db_userinst.id_instit   = {$instit} )";
  $sWherePermHerda .= " and db_permherda.id_usuario = {$usuario}";
  $oDaoPermHerda->excluir(null, null, $sWherePermHerda);  

  /**
   * Limpa o cache do menu
   */
  DBMenu::limpaCache($usuario, $instit);
  
  // atualiza usuários
  if (isset($usuarios)) {
    
    $qver = sizeof($usuarios);
    if ($qver>0) {
    
      for ($i = 0; $i < $qver; $i++) {
        
        $oDaoPermHerda->id_usuario = $usuario;
        $oDaoPermHerda->id_perfil  = $usuarios[$i];
        $oDaoPermHerda->incluir($usuario, $usuarios[$i]);
      }
    }
  }
  // atualiza heranca dos perfis
  if (isset($perfil)) {
    
    $qver = sizeof($perfil);
    if ($qver>0) {
    
      for ($i = 0; $i < $qver; $i++) {
        
        $oDaoPermHerda->id_usuario = $usuario;
        $oDaoPermHerda->id_perfil  = $perfil[$i];
        $oDaoPermHerda->incluir($usuario, $perfil[$i]);
      }
    }
  }
  db_query("commit");
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
<body class="body-default" onLoad="js_trocacordeselect()" >
<table width="100%" align='center' border="0" cellspacing="0" cellpadding="0">
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
		   if(db_getsession("DB_id_usuario") == 1 || db_getsession("DB_administrador") == 1) {
		    $result = db_query("select codigo,nomeinst from db_config");
		  } else {
 	        $result = db_query("select c.codigo,c.nomeinst 
		                       from db_config c
				    	  	   inner join db_userinst u
							   on u.id_instit = c.codigo
							   where u.id_usuario = ".db_getsession("DB_id_usuario"). " and u.id_instit = ".db_getsession("DB_instit"));
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
	  } else if(isset($HTTP_POST_VARS["mod"])) {
	  ?>
      <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
	    <td></td>
	    <td></td>
      </tr>
	  <Tr>
	    <td> <strong>M&oacute;dulo:</strong><br> 
	  <select onDblClick="document.form1.verificar.click()" name="modulos" size="18" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
        <?
	   	      if(db_getsession("DB_id_usuario") == 1 || db_getsession("DB_administrador") == 1 ) {
			    $result = db_query("select i.id_item,nome_modulo,descr_modulo 
			    from db_modulos m
                                 inner join db_itensmenu i on i.id_item = m.id_item
                            where i.libcliente is true
			    order by lower(nome_modulo)");
		      } else {
			    $result = db_query("select m.id_item,m.nome_modulo,m.descr_modulo 
			    from db_modulos m
				inner join db_usermod u
				on u.id_modulo = m.id_item
                                inner join db_itensmenu i on i.id_item = m.id_item
				where u.id_usuario = ".db_getsession("DB_id_usuario")."
				and u.id_instit = ".db_getsession("DB_instit")."
                                and i.libcliente is true
			    order by lower(m.nome_modulo)");
		      }
		      $numrows = pg_numrows($result);
		      if($numrows>0){
		        for($i = 0;$i < $numrows;$i++) {
			   $sql = "select p.id_modulo 
			           from db_permissao p
				        inner join db_itensmenu i on i.id_item = p.id_item
				   where id_modulo = ".pg_result($result,$i,"id_item")." and id_instit = ".$HTTP_POST_VARS["instit"]." 
				         and id_usuario = ".$HTTP_POST_VARS["usuario"]." and itemativo = 1
                                         and anousu = $anousu
				   limit 1";
		           $res = db_query($sql);
			   if(pg_numrows($res)>0)
			     $temp = "+ ";
			   else
			     $temp = "- ";
		           echo "<option value=\"".pg_result($result,$i,"id_item")."\">".$temp." ".pg_result($result,$i,"nome_modulo")."</option>\n";
		        }  
	              }
		?>
        </select> 
	    </td>
	   


		 <!--<td><strong>Usuários:</strong><br>-->
	         <?
	         $record_select = "select d.id_perfil
		                   from db_permherda d
		                   where d.id_usuario = $usuario";
		 $record_select = db_query($record_select);
		 if($record_select==false || pg_numrows($record_select)==0){
		   $record_select = "";
		 }
	
		 ?>
		 </td>
	         <?
		 //
   	         $sql = "select i.id_usuario,i.nome 
		         from db_usuarios i
			      inner join db_userinst u on i.id_usuario = u.id_usuario								   
			 where i.usuarioativo = '1' and i.usuext = 2 and i.id_usuario <> ".$HTTP_POST_VARS["usuario"]." and id_instit = ".$HTTP_POST_VARS["instit"	]." ";
		 if(isset($retorno)){
		   $sql .=  " and id_usuario <> $retorno and id_usuario <> ".$HTTP_POST_VARS["usuario"]."";
		 }
		 $sql .= " order by nome ";
	         $result = db_query($sql);
		 if(pg_numrows($result) > 0){
		 ?>  
		 <td><strong>Perfis:</strong><br>
		 <?
		   db_selectmultiple("perfil",$result,18,2,"","","",$record_select);
		 ?>
                 </td>
		 <?
		 }
	         ?>
	   
	  </Tr>
	  <tr>
	    <td>
		<input type="hidden" name="instit" value="<?=$HTTP_POST_VARS["instit"]?>">
		<input type="hidden" name="usuario" value="<?=$HTTP_POST_VARS["usuario"]?>">
		<input type="hidden" name="anousu" value="<?=$HTTP_POST_VARS["anousu"]?>">
		<input onClick="if(document.form1.modulos.selectedIndex == -1 ) { alert('Selecione um usuário!'); return false; }" name="verificar" type="submit" id="atualiza" value="Atualiza"></td>
           <!-- <td>
		<input name="atuusuarios" type="submit" id="atuusuarios" value="Gravar"></td>
	    </td>-->
	    <td>
	        <?
		if(pg_numrows($result) > 0){
		?>
		  <input name="atuperfil" type="submit" id="atuperfil" value="Gravar"></td>
		<?
		}
		?>
	    </td>
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
	        <optgroup style="text-align:center;color:#333333;background-color:#6699cc;letter-spacing:0.4em" label="Usuários" onClick="return false">
		<?
		if(db_getsession("DB_id_usuario") == 1 || db_getsession("DB_administrador") == 1) {
		  $result = db_query("select id_usuario,nome,login from db_usuarios where usuarioativo = 1 and usuext = 0 order by lower(login)");
		} else {
		  $result = db_query("select u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							 and u.usuext = 0
							 order by lower(u.login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  echo "<option style='text-align:left;color:black;letter-spacing:normal'  value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		  //echo "<option ".(pg_result($result,$i,"usuext") == 2?"style='color: blue;font-weight:bold'":"")." value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
		</optgroup>
	        <optgroup style="text-align:center;color:#333333;background-color:#6699cc;letter-spacing:0.4em" label="Perfis">
		<?
		if(db_getsession("DB_id_usuario") == 1 || db_getsession("DB_administrador") == 1) {
		  $result = db_query("select id_usuario,nome,login from db_usuarios where usuarioativo = 1 and usuext = 2 order by lower(login)");
		} else {
		  $result = db_query("select u.id_usuario,u.nome,u.login,u.usuext 
					 from db_usuarios u
							 inner join db_userinst i
							 on i.id_usuario = u.id_usuario								   
							 where u.usuarioativo = 1 
							 and i.id_instit = ".db_getsession("DB_instit")."
							 and u.usuext = 2
							 order by lower(u.login)");
		}				
		$numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		  echo "<option style='text-align:left;color:black;letter-spacing:normal;' value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"nome")."</option>\n";
		  //echo "<option ".(pg_result($result,$i,"usuext") == 2?"style='color: blue;font-weight:bold'":"")." value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
		}  
		?>
	       </select> 
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
	} else if(isset($HTTP_POST_VARS["verificar"])) {
		  $result = db_query("select nome_modulo,descr_modulo from db_modulos where id_item = ".$HTTP_POST_VARS["modulos"]);
	  $mod = pg_result($result,0,0);
	  $des = pg_result($result,0,1);
	  $result = db_query("select login,nome from db_usuarios where id_usuario = ".$HTTP_POST_VARS["usuario"]);
	  $log = pg_result($result,0,0);
	  $nom = pg_result($result,0,1);
	  $result = db_query("select nomeinst from db_config where codigo = ".$HTTP_POST_VARS["instit"]);
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
		  <tr style='display:none'>
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

        /**
         * Busca a preferênia do usuário
         */
        
        $sOrdenacao = DBMenu::getCampoOrdenacao();
        
        $sub = db_query("select p.id_item as perm,m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo
                           from db_menu m 
                                inner join db_itensmenu i      on i.id_item     = m.id_item_filho 
					                      left outer join db_permissao p on p.id_item     = m.id_item_filho 
					                                                     and p.id_usuario = ".$HTTP_POST_VARS["usuario"]."
					                                                     and p.anousu     = ".$HTTP_POST_VARS["anousu"]."
					                                                     and p.id_instit  = ".$HTTP_POST_VARS["instit"]."
					                                                     and p.id_modulo  = ".$HTTP_POST_VARS["modulos"]."							  
                          where m.modulo    = $mod
					                  and m.id_item   = $item
					                  and i.itemativo = $ambiente 
                            and i.libcliente is true
                          order by {$sOrdenacao} asc");

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
    $sOrdenacao = DBMenu::getCampoOrdenacao();  
		$SQL = "select distinct p.id_item as perm,i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao, m.menusequencia 
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
                                                           and i.libcliente is true							   
							   and m.id_item = ".$HTTP_POST_VARS["modulos"] . " order by {$sOrdenacao} asc";
                
            $result = db_query($SQL);			
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
		 $result = db_query("select id_item 
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