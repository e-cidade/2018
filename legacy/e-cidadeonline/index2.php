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
include("libs/db_conecta.php");
include_once("functions/func_sys.php");
include("libs/db_conn.php");
include("libs/menu.php");

if(!isset($login)){ // sem estar logado
  if(!isset($DB_LOGADO)){ // entra aqui tb
   	session_destroy();
  }else{
   	session_register("DB_acesso");
  }
}else{ 
  session_register("DB_acesso");
}

include("libs/db_stdlib.php");
include("libs/db_sql.php");

db_mensagem("corpoprincipal","mensagemsenha");
db_fieldsmemory($result,0);
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

if(isset($again)){ 
	setcookie("cookie_codigo_cgm");
	db_logs("","","0","index.php - usuario fez log-off.");
	db_redireciona("index.php");
}
//echo "<br><br><br> $login <br><br><br>";

//if(!session_is_registered("DB_login")) {
//  session_register("DB_login");
//}

session_register("DB_login");
session_register("DB_acesso");
session_register("hora");
session_register("DB_id_usuario");
session_register("DB_cgm_login");

if(isset($login)) { //echo" entra aqui qd  o usuario esta logado no sistema"; login é o input

	$result = db_query($conn,"select db_usuarios.id_usuario,senha,u.cgmlogin
                              from db_usuarios
                              inner join db_usuacgm u on u.id_usuario = db_usuarios.id_usuario
                           	  where login = '$DB_login'");
                         	  
                        	                             	 
  if(@pg_numrows($result) == 0) {
    $erroscripts = "1";
  }elseif($DB_senha != md5(~pg_result($result,0,"senha"))) {
    $erroscripts = "2";
  }else{
    	    
	    db_fieldsmemory($result,0,true,true);

	    db_putsession("DB_login", $DB_login);
			db_putsession("DB_id_usuario", $id_usuario);
			db_putsession("DB_cgm_usuario", $cgmlogin);

	    $DB_LOGADO = "";
	    db_logs("","","0","index.php - Usuário fez login.");
	    $sql = "select fc_permissaodbpref($cgmlogin,0,0)";

	    $result = @db_query($sql);
	    if(@pg_numrows($result)==0) { 
        db_redireciona("index.php?".base64_encode("erroscripts='4'"));
    	}
			//$HTTP_SESSION_VARS["DB_acesso"] = pg_result($result,0,0); 
	    //$HTTP_SESSION_VARS["hora"]      = date("H:i:s");
      session_register("DB_acesso");
			session_register("hora");

			db_putsession("DB_acesso", pg_result($result,0,0));
			db_putsession("hora", date("H:i:s"));

  	}
}
//echo "erro = $erroscripts";
$usuario = db_getsession("DB_login");

$hora    = db_getsession("hora");
//echo "usu= $usuario hora = $hora v $DB_acesso";
if($usuario != ""){
	$result = db_query("select nome,d.id_usuario as id_usuario from db_usuarios d inner join db_usuacgm u on u.id_usuario = d.id_usuario where u.cgmlogin = ".@$usuario);
	$nomeusuario = pg_result($result,0,'nome');
	$id_usuario = pg_result($result,0,'id_usuario');
	$cgm=$usuario;
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/db_script.js"></script>
</script>
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
db_estilosite()
?>
</style>
<?
$result4 = db_query("select munic from db_config where prefeitura='t'");
db_fieldsmemory($result4,0);

if($munic=="CHARQUEADAS"){
	$altura= 90;
	
}
if($munic=="Sapiranga"){
	$altura= 133;
	
}
if($munic=="GUAIBA"){
	$altura= 80;
}
if($munic=="ALEGRETE"){
	$altura= 80;
}
if($munic=="BAGE"){
	$altura= 80;
}
if($munic=="OSORIO"){
	$altura= 80;
}

?>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="<?=!isset($DB_LOGADO) && (db_getsession("DB_login") == "")?'js_foco()':''?>">
<?mens_div();?>
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
	<tr bgcolor="#cccccc">
		<td height="<?=$altura?>px" colspan="3" background="imagens/cabecalho.jpg" align="center"></td>
 	</tr>
 	<tr>
  		<td colspan="3" height="1" bgcolor="#999999"></td>
 	</tr>
 	<tr height="15" bgcolor="<?=$w01_corbody?>" class="texto">
 		
 		<td >
<?
			if(db_getsession("DB_login") == ""){ // não ta logado
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
 							if($w13_liberapedsenha=="t"){
    							?><a href="pedido_senha.php" target="CentroPref">Pedido de Senha</a><?
 							}
echo" 
						</td>
     				</tr>
      			</table>
     			</form>
";
			$sql = "select * from db_usuarios where login = 'dbpref'";
			$result = db_query($sql);
			db_fieldsmemory($result,0);
			$codperf= $id_usuario;

			}elseif($usuario!=""){ 
				    $sqlidusu = "select * from db_usuacgm where cgmlogin=$usuario";
				    $resdelidusu = db_query($sqlidusu);
				    db_fieldsmemory($resdelidusu,0);
				    $usu= $id_usuario;
					echo $usuario." - ".$nomeusuario ; // mostra o nome de quem esta logado
					$del = "delete from db_permherda where id_usuario=$usu"; 
					$resdel = db_query($del);					
					//se for escritorio
					$sql = "select * from cadescrito where q86_numcgm= $usuario";
					$result = db_query($sql);
					$linhas = pg_num_rows($result);
					if($linhas>0){
						//gravar na db_permherda (id_usuario e id_perfil)
						
						$sqlid= "select * from db_usuarios where login = 'escritorio'";
						$resultid = db_query($sqlid);
						$li=pg_num_rows($resultid);
						if($li>0){
							db_fieldsmemory($resultid,0);
							$ins =" insert into db_permherda values ($usu,$id_usuario)";
							$resins = db_query($ins);
							$codperf=$usu;
						}
					}
						// se for imobiliaria.............
						$sqlimb= "select * from cadimobil where j63_numcgm = $usuario";
						$resultimb= db_query($sqlimb);
						$linhasimb = pg_num_rows($resultimb);
						if($linhasimb>0){
						//deletar da db_permherda
						//gravar na db_permherda (id_usuario e id_perfil)
							$sqlid= "select * from db_usuarios where login = 'imobiliaria'";
							$resultid = db_query($sqlid);
							$li=pg_num_rows($resultid);
							if($li>0){ 
								db_fieldsmemory($resultid,0);
								$ins =" insert into db_permherda values ($usu,$id_usuario)";
								$resins = db_query($ins);
								$codperf=$usu;
																
							}
						}
							// se for fornecedor
							$sqlfor="select * from pcforne where pc60_numcgm= $usuario";
							$resultfor= db_query($sqlfor);
							$linhasfor = pg_num_rows($resultfor);
							if($linhasfor>0){
								//deletar da db_permherda
								//gravar na db_permherda (id_usuario e id_perfil)
								$sqlid= "select * from db_usuarios where login = 'fornecedor'";
								$resultid = db_query($sqlid);
								$li=pg_num_rows($resultid);
								if($li>0){ 
									db_fieldsmemory($resultid,0);
									$ins =" insert into db_permherda values ($usu,$id_usuario)";
									$resins = db_query($ins);
									$codperf=$usu;
								}
							}
								// se for funcionario
								$sqlfun = "	select rh01_regist, rh01_numcgm 
											from rhpessoal 
											inner join rhpessoalmov on rh01_regist = rh02_regist 
											left join rhpesrescisao on rh02_seqpes = rh05_seqpes
											where rh05_seqpes is null and rh01_numcgm=$usuario";
								$resultfun= db_query($sqlfun);
								$linhasfun = pg_num_rows($resultfun);
								if($linhasfun>0){
									//deletar da db_permherda
									//gravar na db_permherda (id_usuario e id_perfil)
									$sqlid= "select * from db_usuarios where login = 'funcionario'";
									$resultid = db_query($sqlid);
									$li=pg_num_rows($resultid);
									if($li>0){ 
										db_fieldsmemory($resultid,0);
										$ins =" insert into db_permherda values ($usu,$id_usuario)";
										$resins = db_query($ins);
										$codperf=$usu;
									}
								} 
								$sqlid= "select * from db_usuarios where login = 'contribuinte'";
								$resultid = db_query($sqlid);
								$li=pg_num_rows($resultid);
								if($li>0){ 
									db_fieldsmemory($resultid,0);
									$ins =" insert into db_permherda values ($usu,$id_usuario)";
									$resins = db_query($ins);
									$codperf=$usu;
								}
							
			}
//echo  @$user;
//die ("xxx= $codperf");
?>
		

		</td>
		<td><?db_logon(isset($login)?false:true,$w13_liberaatucgm,$w13_liberaescritorios);?></td>
		
 		<td align="left"> <?=date('d/m/Y')?></td>
  	</tr>
 	<tr>
  		<td colspan="3" height="1" bgcolor="#999999"></td>
 	</tr>
 	
	<tr>
			
		<td colspan="3"> <?db_menu_dbpref($codperf,5457,2006,1,@$cgm,@$nomeusuario); ?>
		
		</td>
	</tr>
	<tr height="800">
  		<td colspan="3" width="100%" align="center">
   			<iframe id="CentroPref" name="CentroPref" src="centro_pref.php" width="100%" height="100%" frameborder="0"></iframe>
  		</td>
 	</tr>
</table>
</body>
</html>
<?
  
if(isset($erroscripts) && !isset($DB_LOGADO)){
	if(@$erroscripts == 1)
    	echo "<script>alert('Login Inválido');</script>\n";
  	elseif(@$erroscripts == 2)
    	echo "<script>alert('Senha Inválida');</script>\n";
  	elseif(@$erroscripts == 3)
    	echo "<script>alert('Acesso a rotina inválido.');</script>\n";
  	elseif(@$erroscripts == 4)
    	echo "<script>alert('Sem permissão de acesso, Contate a Prefeitura.');</script>\n";
}

?>
<script>
<?
if(!isset($DB_LOGADO) && (db_getsession("DB_login") == "")){

	function js_foco(){
    document.form1.login.focus();
  	}

}
?>
</script>