<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

$oDaoPermHerda = db_utils::getDao('db_permherda');
$oDaoPermissao = db_utils::getDao('db_permissao');

$instit = db_getsession("DB_instit");
$anousu = db_getsession("DB_anousu");

if(isset($HTTP_POST_VARS["atualizarperm"])) {
  $modulo = $HTTP_POST_VARS["modulos"];
  if (isset($HTTP_POST_VARS["anousu"])){
    $anousu = $HTTP_POST_VARS["anousu"];
  }
  if (isset($HTTP_POST_VARS["instit"])){
    $instit = $HTTP_POST_VARS["instit"];
  }
  $ambiente = $HTTP_POST_VARS["ambiente"];

  db_query("BEGIN");
//primeiro delete os itens

  $sWherePermissao = "     id_item in ( select i.id_item
                                          from db_itensmenu i
				                                 inner join db_permissao p on p.id_item = i.id_item
				                                                          and p.id_usuario = $usuario
                                                      			      and p.anousu = $anousu
                                                      			      and p.id_instit = $instit
                                                      			      and p.id_modulo = $modulo
	                                       where i.itemativo = $ambiente)
			                 and id_usuario = $usuario
			                 and anousu = $anousu
			                 and id_instit = $instit
			                 and id_modulo = $modulo";

  $oDaoPermissao->excluir(null, null, null, null, null, $sWherePermissao);
  if ($oDaoPermissao->erro_status == 0) {
    die("Erro(22) excluindo db_permissao: ".$oDaoPermissao->erro_banco);
  }

//inclui novamente os itens
  $result = db_query("select id_item
                     from db_permissao
					 where id_usuario = $usuario
					 and id_item = $modulo
					 and anousu = $anousu
					 and id_instit = $instit
					 and id_modulo = $modulo");
  if(pg_numrows($result) == 0) {

    $oDaoPermissao->id_usuario     = $usuario;
    $oDaoPermissao->id_item        = $modulo;
    $oDaoPermissao->permissaoativa = 1;
    $oDaoPermissao->anousu         = $anousu;
    $oDaoPermissao->id_instit      = $instit;
    $oDaoPermissao->id_modulo      = $modulo;

    $oDaoPermissao->incluir($usuario, $modulo, $anousu, $instit, $modulo);

    if ($oDaoPermissao->erro_status == 0) {
      die("Erro(18) inserindo em db_permissao: ".$oDaoPermissao->erro_banco);
    }
  }
  $tam_vetor = sizeof($HTTP_POST_VARS);
  reset($HTTP_POST_VARS);
  for ($i = 0;$i < $tam_vetor;$i++) {

    if (db_indexOf(key($HTTP_POST_VARS),"CHECK") > 0) {

      $oDaoPermissao->id_usuario     = $usuario;
      $oDaoPermissao->id_item        = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
      $oDaoPermissao->permissaoativa = 1;
      $oDaoPermissao->anousu         = $anousu;
      $oDaoPermissao->id_instit      = $instit;
      $oDaoPermissao->id_modulo      = $modulo;
      $oDaoPermissao->incluir($usuario, $HTTP_POST_VARS[key($HTTP_POST_VARS)], $anousu, $instit, $modulo);

      if ($oDaoPermissao->erro_status == 0) {
        die("Erro(18) inserindo em db_permissao: ".$oDaoPermissao->erro_banco);
      }
    }
    next($HTTP_POST_VARS);
  }

  db_query("COMMIT");
}

// atualiza heranca dos usuarios

if (isset($atuusuarios) || isset($atuperfil)) {

  $sMensagem    = "Alteração realizada com sucesso!";
  $iInstituicao = db_getsession('DB_instit');

  db_query("begin");

  $sCamposPermHerda = "db_permherda.id_usuario as usuario, db_permherda.id_perfil as perfil";
  $sWherePermHerda  = "db_permherda.id_usuario = {$usuario}";
  $sSqlPermHerda    = $oDaoPermHerda->sql_query_db_userinst(null, null, $sCamposPermHerda, null, $sWherePermHerda);
  $rsPermHerda      = $oDaoPermHerda->sql_record($sSqlPermHerda);
  $iTotalPermHerda  = $oDaoPermHerda->numrows;

  if ( $iTotalPermHerda > 0 ) {

  	for ( $iContador = 0; $iContador < $iTotalPermHerda; $iContador++ ) {

  		$oDadosPermHerda = db_utils::fieldsMemory($rsPermHerda, $iContador);
  		$sWherePermHerda = "id_usuario = {$oDadosPermHerda->usuario} and id_perfil = {$oDadosPermHerda->perfil}";
  		$oDaoPermHerda->excluir(null, null, $sWherePermHerda);

  		if ( $oDaoPermHerda->erro_status == "0" ) {

  		  $sMensagem = $oDaoPermHerda->erro_msg;
  		  throw new Exception($oDaoPermHerda->erro_msg);
  		}
  	}
  }

  if (isset($usuarios)) {

    $qver = sizeof($usuarios);
    if ($qver > 0) {

      for ($i = 0; $i < $qver; $i++) {

        $oDaoPermHerda->id_usuario = $usuario;
        $oDaoPermHerda->id_perfil  = $usuarios[$i];
        $oDaoPermHerda->incluir($usuario, $usuarios[$i]);

        if ( $oDaoPermHerda->erro_status == "0" ) {

          $sMensagem = $oDaoPermHerda->erro_msg;
          throw new Exception($oDaoPermHerda->erro_msg);
        }
      }
    }
  }
  // atualiza heranca dos perfis
  if (isset($perfil)) {

    $qver = sizeof($perfil);
    if ($qver > 0) {

      for ($i = 0; $i < $qver; $i++) {

        $oDaoPermHerda->id_usuario = $usuario;
        $oDaoPermHerda->id_perfil  = $perfil[$i];
        $oDaoPermHerda->incluir($usuario, $perfil[$i]);

        if ( $oDaoPermHerda->erro_status == "0" ) {

          $sMensagem = $oDaoPermHerda->erro_msg;
          throw new Exception($oDaoPermHerda->erro_msg);
        }
      }
    }
  }

  /**
   * Limpa o cache do usuário que esta sendo alterado
   */
  DBMenu::limpaCache($usuario);

  db_query("commit");
  $HTTP_POST_VARS["mod"] = true;
  db_msgbox($sMensagem);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" >
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

	  ?>
      <table border="0" cellspacing="0" cellpadding="0">
      <!--
	  <tr>
	    <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
	    <td></td>
	    <td></td>
      </tr>
	  <Tr>
	    <td> <strong>M&oacute;dulo:</strong><br>
	  <select onDblClick="document.form1.verificar.click()" name="modulos" size="18" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
        <?
	   	      if(db_getsession("DB_id_usuario") == 1) {
			    $result = db_query("select id_item,nome_modulo,descr_modulo
			    from db_modulos
			    order by lower(nome_modulo)");
		      } else {
			    $result = db_query("select m.id_item,m.nome_modulo,m.descr_modulo
			    from db_modulos m
				inner join db_usermod u
				on u.id_modulo = m.id_item
				where u.id_usuario = ".db_getsession("DB_id_usuario")."
				and u.id_instit = ".db_getsession("DB_instit")."
			    order by lower(m.nome_modulo)");
		      }
		      $numrows = pg_numrows($result);
		      if($numrows>0){
		        for($i = 0;$i < $numrows;$i++) {
			   $sql = "select p.id_modulo
			           from db_permissao p
				        inner join db_itensmenu i on i.id_item = p.id_item
				   where id_modulo = ".pg_result($result,$i,"id_item")." and id_instit = ".$instit."
				         and id_usuario = ".$usuario." and itemativo = 1
				   limit 1";
		           $res = db_query($sql);
			   if($res && pg_num_rows($res) > 0)
			     $temp = "+ ";
			   else
			     $temp = "- ";
		           echo "<option value=\"".pg_result($result,$i,"id_item")."\">".$temp." ".pg_result($result,$i,"nome_modulo")."</option>\n";
		        }
	              }
		?>
        </select>
	    </td>


		-->
		 <!--<td><strong>Usuários:</strong><br>-->
	         <?
	         $record_select = "select d.id_perfil
		                   from db_permherda d
		                   where d.id_usuario = $usuario";
		 $record_select = db_query($record_select);
		 if($record_select==false || pg_numrows($record_select)==0){
		   $record_select = "";
		 }
		 /*
		 $sql = "select id_usuario,nome
		         from db_usuarios
			 where usuext = 0 ";
		 if(isset($retorno)){
	  	   $sql .= " and id_usuario <> $usuario";
	         }
		 $sql .= " order by nome ";
	         $result = db_query($sql);
	         db_selectmultiple("usuarios",$result,18,2,"","","",$record_select);
	         */
		 ?>
		 </td>
     <?
		 //
      $sql  = " select u.id_usuario, u.id_usuario || '-' || nome as nome";
		  $sql .= "  from db_usuarios u";
      $sql .= " inner join db_userinst i on i.id_usuario = u.id_usuario";
			$sql .= " where usuext = 2 and u.id_usuario <> ".$usuario." and i.id_instit = ".db_getsession("DB_instit");
      $sql .= " and usuarioativo <> 0";

		  if(isset($retorno)){
		    $sql .=  " and u.id_usuario <> $retorno and u.id_usuario <> ".$usuario."";
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
		<input type="hidden" name="instit" value="<?=$instit?>">
		<input type="hidden" name="usuario" value="<?=$usuario?>">
		<input type="hidden" name="anousu" value="<?=$anousu?>">
		<?
		if(pg_numrows($result) > 0){
		?>
		  <input name="atuperfil" type="submit" id="atuperfil" value="Gravar"></td>
		<?
		}
		?>
		<!--
		<input onClick="if(document.form1.modulos.selectedIndex == -1 ) { alert('Selecione um usuário!'); return false; }" name="verificar" type="submit" id="atualiza" value="Atualiza"></td>
            <td>
		<input name="atuusuarios" type="submit" id="atuusuarios" value="Gravar"></td>
	    </td>-->


	    </td>
            </tr>
	  </table>
    </form>
  </center>
  <?
   // db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </td></tr>
</table>
</body>
</html>
