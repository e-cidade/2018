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

session_start();
global $HTTP_SESSION_VARS;
session_register("DB_codperfil");

include_once ("functions/func_sys.php");
include ("libs/db_conecta.php");
include ("libs/menu.php");
include ("libs/db_stdlib.php");
include ("libs/db_sql.php");

require_once("libs/db_encriptacao.php");

db_postmemory($HTTP_SERVER_VARS);

if (!isset ($login)) { // sem estar logado

  if (!isset ($DB_LOGADO)) { // entra aqui tb
    session_destroy();
  } else {
    session_register("DB_acesso");
  }

} else {
  session_register("DB_acesso");
}

db_mensagem("corpoprincipal", "mensagemsenha");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

if (isset ($again)) {
  $cgm = $_SESSION['CGM'];

  setcookie("cookie_codigo_cgm");
  db_logs("", "", "$cgm", "index.php - usuario fez log-off.");
  db_redireciona("index.php");
}

if (isset ($DB_login)) { //echo" entra aqui qd  o usuario esta logado no sistema";

/**
 * Adicionada verificação para usuário externo
 */
  $sqllog  = "select db_usuarios.id_usuario, senha, u.cgmlogin, usuarioativo         ";
  $sqllog .= "  from db_usuarios                                                     ";
  $sqllog .= "       inner join db_usuacgm u on u.id_usuario = db_usuarios.id_usuario";
  $sqllog .= " where login        = '$DB_login'                                      ";
  //$sqllog .= "   and usuext       = 1                                                ";

  $base = @$_SESSION["BASE"];
  if($base!=""){
    $DB_BASEDADOS = $base;
  }

  $result = db_query($sqllog);
  $linhas = pg_numrows($result);

  if ($linhas == 0) {

    $erroscripts = "1";
  } elseif ( Encriptacao::hash( $DB_senha)  != pg_result($result, 0, "senha") ) {

    $erroscripts = "2";
  } elseif ( pg_result($result, 0, "usuarioativo") <> 1 ) {

    $erroscripts = "5";
  } else {

    db_fieldsmemory($result, 0);
    db_putsession("DB_login", $cgmlogin);
    db_putsession("CGM", $cgmlogin);
    $DB_LOGADO = "";
    $usuario   = db_getsession("DB_login");
    $cgm       = db_getsession('CGM');
    db_putsession("DB_acesso", $id_usuario);
    db_putsession("hora", date("H:i:s"));
    db_putsession("DB_id_usuario", $id_usuario);
  }

} else {

  $sqluser  = "select id_usuario ";
  $sqluser .= "  from db_usuarios ";
  $sqluser .= " where login = 'dbpref' ";

  $resultuser = db_query($sqluser);
  if (pg_num_rows($resultuser)>0) {
    $usuario = pg_result($resultuser, 0, 0);
  } else {
    $usuario = 1;
  }
  db_putsession("DB_id_usuario", $usuario);
}

if (@$cgm != "") {

 /**
  * Adicionada verificação para usuário externo e se esta ativo
  */
  $sql1  = "select nome,d.id_usuario as id_usuario                       ";
  $sql1 .= "  from db_usuarios d                                         ";
  $sql1 .= "       inner join db_usuacgm u on u.id_usuario = d.id_usuario";
  $sql1 .= " where u.cgmlogin     = '$cgm'                               ";
  //$sql1 .= "   and usuext         = 1                                    ";
  $sql1 .= "   and usuarioativo   = 1                                    ";

  $result      = db_query($sql1);
  $nomeusuario = pg_result($result, 0, 'nome');
  $id_usuario  = pg_result($result, 0, 'id_usuario');
  $cgm         = $usuario;
  session_register("id");
  $_SESSION["id"] = $id_usuario;
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/db_script.js"></script>
<script>
function js_alapucha(evt) {
    evt = (evt) ? evt : (window.event) ? window.event : "";
    if(evt.keyCode == 13)
    	js_submeter();
}
function js_submeter() {

	document.form1.DB_senha.value = calcMD5(document.form1.senha.value);
	document.form1.DB_login.value = document.form1.login.value;
	document.form1.senha.value = "";
	document.form1.login.value = "";
	wname = 'wname' + Math.floor(Math.random() * 10000);
	document.form1.submit();
}

</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
<?

$result4 = db_query("select upper(trim(munic)) as munic from db_config where codigo = ".db_getsession("DB_instit"));
if( pg_num_rows($result4) > 0 ) {
	db_fieldsmemory($result4, 0);

	switch ($munic) {
		case "CHARQUEADAS":
			$altura = 90;
			break;
		case "SAPIRANGA":
			$altura = 133;
			break;
		case "GUAIBA":
		case "ALEGRETE":
		case "BAGE":
		case "OSORIO":
		case "ARAPIRACA":
			$altura = 80;
			break;
		case "ARROIO DO SAL":
		case "CARAZINHO":
			$altura = 100;
			break;
	}

} else {
	$altura = 90;
}
$base=@$_SESSION["BASE"];
$altura = 200;
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	bgcolor="<?=$w01_corbody?>"
	onLoad="<?=!isset($DB_LOGADO) && (db_getsession('DB_login') == '')?'js_foco();':''?>">

<?
if ( (isset($usuario) && $usuario !="") || (isset($id_usuario) && $id_usuario != "")) {

	if ( $usuario != "" && !isset( $id_usuario ) ) {
		$id_usuario = $usuario;
	}

	$sqlusu = "select * from db_usuarios where id_usuario = $id_usuario";
  $resusu = db_query($sqlusu);
  $linhausu=pg_num_rows($resusu);
  if ($linhausu >0){
    db_fieldsmemory($resusu, 0);
    if ($login=='admin'){
      echo"<a href='trocabase.php' target='CentroPref'>trocar base</a>";
    }
  }

}
?>

<table width="100%" align="center" border="0" cellpadding="0"
	cellspacing="0" bgcolor="<?=$w01_corbody?>">
 <!--
	<tr>
		<td height="<? //=$altura?>px" colspan="3"
			 style="background-image:url('imagens/cabecalho.jpg'); background-repeat: no-repeat;" align="center"></td>
	</tr>
 -->
 <?
  /*
   * Modificacao incluida para que nao repita ou corte a imagem  do cabecalho enviado pelo cliente
   */
 ?>
  <tr>
    <td colspan="3">
       <img src="imagens/cabecalho.jpg">
    </td>
  </tr>

	<tr>
		<td colspan="3" height="1" bgcolor="#999999"></td>
	</tr>
	<tr height="15" bgcolor="<?=$w01_corbody?>" class="texto">

		<td><?

		if (db_getsession("DB_login") == "") {
		  echo "
		  				<form name=\"form1\" action=\"\" method=\"post\">
						<table class=\"texto\">
		      				<tr align=\"left\">
		  	    				<td>Login:
		     	    				<input name=\"login\" type=\"text\" size=\"10\">&nbsp;&nbsp;&nbsp;&nbsp; </td>
			   					<td>Senha:
								    <input name=\"senha\" onKeyUp=\"js_alapucha(event)\" type=\"password\" size=\"10\"> &nbsp;
								    <input name=\"Submit\" type=\"button\" class=\"botao\" onClick=\"js_submeter()\" value=\"Acessar\">&nbsp;&nbsp;
						  	    	<input type='hidden' name='DB_senha'>
							      	<input type='hidden' name='DB_login'>

		";
		  if ($w13_liberapedsenha == "t") {
		    ?> &nbsp;&nbsp;&nbsp;&nbsp;
           <a href="pedido_senha.php?eqm=0"  target="CentroPref">Pedido de Senha</a>&nbsp;&nbsp;
           <a href="pedido_senha.php?eqm=1"  target="CentroPref">Esqueci Minha Senha</a>&nbsp;&nbsp;
           <a href="rhpes_autcontracheq.php" target="CentroPref">Autenticidade de Contracheque</a>
		    <?

//#################################################################

	}
	echo "
								</td>
		     				</tr>
		      			</table>
		     			</form>
		";
	$sql = "select * from db_usuarios where lower(login) = 'dbpref'";
	$result = db_query($sql);
	db_fieldsmemory($result, 0);
	$codperf = $id_usuario;

} elseif ($usuario != "") {

	$sqlidusu  = "select *                                                                        ";
  $sqlidusu .= "  from db_usuacgm                                                               ";
  $sqlidusu .= "       inner join db_usuarios on db_usuacgm.id_usuario = db_usuarios.id_usuario ";
  $sqlidusu .= " where cgmlogin     = $usuario                                                  ";
  //$sqlidusu .= "   and usuext       = 1                                                         ";
  $sqlidusu .= "   and usuarioativo = 1                                                         ";

	$resdelidusu = db_query($sqlidusu);
	db_fieldsmemory($resdelidusu, 0);
  $usu = $id_usuario;

  /**
   *  Mostra o nome de quem esta logado
   */
	echo $usuario." - ".$nomeusuario;

	// busca os perfis do usuario
	$sqlperfil    = "select * from db_permherda where id_usuario=$usu";
	$resultperfil = db_query($sqlperfil);
	$linhasperfil = pg_num_rows($resultperfil);

	if ($linhasperfil>0){
		for ($i = 0; $i < $linhasperfil; $i ++) {
			db_fieldsmemory($resultperfil, $i);
			// pega o id e busca o login na db_usuarios..ex...'contribuinte'
			$sql   =  "select * from db_usuarios where id_usuario = $id_perfil";
			$result = db_query($sql);
			$linhas = pg_num_rows($result);
			db_fieldsmemory($result, 0);

			if (strtolower($login) =='contribuinte' || strtolower($login) =='escritorio'|| strtolower($login) =='imobiliaria' || strtolower($login) =='fornecedor' || strtolower($login) =='funcionario' ){
				// deletar somente os que são perfis de dbpref
				$del="delete from db_permherda where id_usuario='$usu'and id_perfil =$id_perfil";
				$resdel = db_query($del);
			}

		}
	}
	//echo $del;

	// se for administrador
	$sqlusu="select * from db_usuarios where id_usuario= $usu";
	$resusu = db_query($sqlusu);
	$linhausu=pg_num_rows($resusu);
	if ($linhausu >0){
		db_fieldsmemory($resusu, 0);
		if ($login=='admin'){
			$sqlid = "select * from db_usuarios where lower(login) = 'administrador'";
			//echo"<br>$sqlid";
			$resultid = db_query($sqlid);
			$li = pg_num_rows($resultid);
			if ($li > 0) {
				db_fieldsmemory($resultid, 0);
				$ins = " insert into db_permherda values ($usu,$id_usuario)";
				$resins = db_query($ins);
				$codperf = $usu;
			}
		}
	}

	//se for escritorio
	$sql = "select * from cadescrito where q86_numcgm= $usuario";
	$result = db_query($sql);
	$linhas = pg_num_rows($result);
	if ($linhas > 0) {
		//gravar na db_permherda (id_usuario e id_perfil)

		$sqlid = "select * from db_usuarios where lower(login) = 'escritorio'";
		$resultid = db_query($sqlid);
		$li = pg_num_rows($resultid);
		if ($li > 0) {
			db_fieldsmemory($resultid, 0);
			$ins = " insert into db_permherda values ($usu,$id_usuario)";
			$resins = db_query($ins);
			$codperf = $usu;
		}
	}

	// se for imobiliaria.............
	$sqlimb = "select * from cadimobil where j63_numcgm = $usuario";
	$resultimb = db_query($sqlimb);
	$linhasimb = pg_num_rows($resultimb);
	if ($linhasimb > 0) {
		//deletar da db_permherda
		//gravar na db_permherda (id_usuario e id_perfil)
		$sqlid = "select * from db_usuarios where lower(login) = 'imobiliaria'";
		$resultid = db_query($sqlid);
		$li = pg_num_rows($resultid);
		if ($li > 0) {
			db_fieldsmemory($resultid, 0);
			$ins = " insert into db_permherda values ($usu,$id_usuario)";
			$resins = db_query($ins);
			$codperf = $usu;

		}
	}

	// se for grafica
	$sqlgra = "select * from graficas where y20_grafica  = $usuario";
	$resultgra = db_query($sqlgra);
	$linhasgra = pg_num_rows($resultgra);
	if ($linhasgra > 0) {
		//deletar da db_permherda
		//gravar na db_permherda (id_usuario e id_perfil)
		$sqlid = "select * from db_usuarios where lower(login) = 'grafica'";
		$resultid = db_query($sqlid);
		$li = pg_num_rows($resultid);
		if ($li > 0) {
			db_fieldsmemory($resultid, 0);
			$ins = " insert into db_permherda values ($usu,$id_usuario)";
			$resins = db_query($ins);
			$codperf = $usu;

		}
	}

	// se for fornecedor
	$sqlfor = "select * from pcforne where pc60_numcgm= $usuario";
	$resultfor = db_query($sqlfor);
	$linhasfor = pg_num_rows($resultfor);
	if ($linhasfor > 0) {
		//deletar da db_permherda
		//gravar na db_permherda (id_usuario e id_perfil)
		$sqlid = "select * from db_usuarios where lower(login) = 'fornecedor'";
		$resultid = db_query($sqlid);
		$li = pg_num_rows($resultid);
		if ($li > 0) {
			db_fieldsmemory($resultid, 0);
			$ins = " insert into db_permherda values ($usu,$id_usuario)";
			$resins = db_query($ins);
			$codperf = $usu;
		}
	}

	if($w13_utilizafolha=='t'){
		// se for funcionario

		$sqlfun = "	select rh01_regist, rh01_numcgm
									from rhpessoal
									 		 inner join rhpessoalmov on rh01_regist = rh02_regist
											 left  join rhpesrescisao on rh02_seqpes = rh05_seqpes
						 		 where rh01_numcgm = $usuario";

		$resultfun = db_query($sqlfun);
		$linhasfun = pg_num_rows($resultfun);
		if ($linhasfun > 0) {
			//deletar da db_permherda
			//gravar na db_permherda (id_usuario e id_perfil)
			$sqlid = "select * from db_usuarios where lower(login) = 'funcionario'";
			$resultid = db_query($sqlid);
			$li = pg_num_rows($resultid);
			if ($li > 0) {
				db_fieldsmemory($resultid, 0);
				$ins = " insert into db_permherda values ($usu,$id_usuario)";
				$resins = db_query($ins);
				$codperf = $usu;
			}
		}
	}
	$sqlid = "select * from db_usuarios where lower(login) = 'contribuinte'";
	$resultid = db_query($sqlid);
	$li = pg_num_rows($resultid);
	if ($li > 0) {
		db_fieldsmemory($resultid, 0);
		$ins = " insert into db_permherda values ($usu,$id_usuario)";
		$resins = db_query($ins);
		$codperf = $usu;
	}

}

$HTTP_SESSION_VARS["DB_codperfil"] = $id_usuario;

?>

		</td>
		<td> <?db_logon(isset($login)?false:true,$w13_liberaatucgm,$w13_liberaescritorios); ?></td>

 		<td align="left"> <?=date('d/m/Y') ?></td>
  	</tr>
 	<tr>
  		<td colspan="3" height="1" bgcolor="#999999"></td>
 	</tr>

	<tr>
			<?$ano= date("Y"); ?>

		<td colspan="3"> <?db_menu_dbpref($codperf,5457,$ano,$DB_INSTITUICAO,@$cgm,@$nomeusuario); ?>

		</td>
	</tr>
	<tr id="handlerIframe" height="800">
  		<td colspan="3" width="100%" align="center">
   			<iframe onload="isChrome()" id="CentroPref" name="CentroPref" src="centro_pref.php" width="100%" height="100%" frameborder="0"></iframe>
  		</td>
 	</tr>

</table>
</body>
</html>
<?

if (isset ($erroscripts) && !isset ($DB_LOGADO)) {
	if (@ $erroscripts == 1)
	echo "<script>alert('Login Inválido');</script>\n";
	elseif (@ $erroscripts == 2) echo "<script>alert('Senha Inválida');</script>\n";
	elseif (@ $erroscripts == 3) echo "<script>alert('Acesso a rotina inválido.');</script>\n";
	elseif (@ $erroscripts == 4) echo "<script>alert('Sem permissão de acesso, Contate a Prefeitura.');</script>\n";
  elseif (@ $erroscripts == 5) echo "<script>alert('Usuário com Login Desativado, Contate a Prefeitura.');</script>\n";
}

?>
<script type="text/javascript">

  /**
   * verifica versão do Chrome para depois colocar
   * o iframe no lugar correto
   *
   * @var {objappVersion}  versão do browser
   * @var {objAgent }  pega o engine do browser
   * @var {objbrowserName} nome do browser
   * @var {objfullVersion} versão do browser
   */
  function isChrome() {

    var objappVersion   = navigator.appVersion;
    var objAgent        = navigator.userAgent;
    var objbrowserName  = navigator.appName;
    var objfullVersion  = ''+parseFloat(navigator.appVersion);

    // In Chrome
    if ((objOffsetVersion=objAgent.indexOf("Chrome"))!=-1) {
     objbrowserName = "Chrome";
     objfullVersion = objAgent.substring(objOffsetVersion);
     browserVersion = objfullVersion.substring(0,11);
    }

    if (browserVersion >= "Chrome/59.0") {

      document.getElementById("handlerIframe").style.display  = "inline-flex";
      document.getElementById("handlerIframe").style.width    =  "100%";
    }
  }

	function js_foco() {
		document.form1.login.focus();
	}
</script>
