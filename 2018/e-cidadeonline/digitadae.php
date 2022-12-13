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
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_escrito_classe.php");
include("classes/db_issbase_classe.php");
$clissbase = new cl_issbase;
$clescrito = new cl_escrito;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
/*$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitadae.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  	if(!session_is_registered("DB_acesso"))
    	echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}   */ 
mens_help();
db_mensagem("dae_cab","dae_rod");
$dblink="index.php";
$db_verificaip = db_verifica_ip();
if($db_verificaip == "0"){
  	$onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),'');\"";
}else{
  	$onsubmit = "";
}  
//busca dados para armazemar em cookies
if(@$_COOKIE["cookie_codigo_cgm"]==""){
 // issbase
 if(@$inscricaow!=""){
  $result  = $clissbase->sql_record($clissbase->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","issbase.q02_inscr = $inscricaow"));
  $linhas1 = $clissbase->numrows;
 }
 // cgm
 if(@$codigo_cgm!=""){
  $result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","cgm.z01_numcgm = $codigo_cgm"));
  $linhas2 = $clcgm->numrows;
 }
 // iptu
 if(@$matricula1!=""){
  $result  = $cliptubase->sql_record($cliptubase->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","iptubase.j01_matric = $matricula1"));
  $linhas3 = $cliptubase->numrows;
 }
 db_fieldsmemory($result,0);
 @setcookie("cookie_codigo_cgm",$z01_numcgm);
 @setcookie("cookie_nome_cgm",$z01_nome);
 @$cookie_codigo_cgm = $z01_numcgm;
}else{
 @$cookie_codigo_cgm = $_COOKIE["cookie_codigo_cgm"]; 
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_vericampos(){
  	inscricaow = document.form1.inscricaow.value; 
  	cgc = document.form1.cgc.value; 
  	 
  	expReg = "/[- /.]/g"; 
  
  	var cgc = new Number(cgc.replace(expReg,"")); 
  	var inscricaow = new Number(inscricaow.replace(expReg,"")); 
  
 	 f
	    alert("Favor preencher um dos campos de identificação!");
	    document.form1.inscricaow.focus();
	    return false  
  	}
 	if(isNaN(inscricaow)){
    	alert("Verifique o campo Inscricão!");
     	return false
  	}
}
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<?
if(isset($erroscripts)){
echo "<script>
		function js_loadmensagem(){
	      	alert('".$erroscripts."');
	    }
      </script>
";     		
}else{
echo "<script>
      	function js_loadmensagem(){
     	}
      </script>";
}
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="js_loadmensagem();" <? mens_OnHelp() ?>>
<? mens_div(); ?>




<?
  //verifica se está logado
  if(@$id_usuario!="" || @$_COOKIE["cookie_codigo_cgm"]!=""){
	   @$usuario = $id_usuario==""?$_COOKIE["cookie_codigo_cgm"]:$id_usuario;
	   //é escritório?
	   $result  = $clescrito->sql_record($clescrito->sql_query("","q02_inscr,a.z01_nome as z01_nome,a.z01_cgccpf as z01_cgccpf","","q10_numcgm = $usuario"));
	   $escrito = $clescrito->numrows;
	   //é issbase
	   $result2 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $usuario"));
	   //$result2 = $clissbase->sql_record($clissbase->sqlinscricoes_socios(0,$cookie_codigo_cgm,"*"));
	   $issbase = $clissbase->numrows;
	   //sócios
	   //$sql = $clissbase->sqlinscricoes_socios(0,$cookie_codigo_cgm,"*");
  }
  if((@$escrito==0 && @$issbase==0) || @$usuario==""){
?>
   
<form name="form1" method="post" <?=$onsubmit?> action="opcoesdae.php">
 	<input name="primeiravez" type="hidden" value="true">
 	<table border="0" cellpadding="0" cellspacing="5" align="center" class="texto">
  		<tr>
  		 	<td colspan="2" align="center"> <br> <?=$DB_mens1?> <br><br><br> </td>
  		</tr>
  		<tr>
			<td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
			<td><input name="inscricaow" type="text" class="digitacgccpf" size="8" maxlength="6"> </td>
  		</tr>
 		<tr>
			<td >CNPJ:</td>
			<td><input name="cgc" type="text" class="digitacgccpf" 
                 id="cgc" size="18" maxlength="18" 
                 onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);'></td>
 		</tr>
  <tr>
    <td>Exercício:</td>
  	<td>
  	
  	<select name="ano" size="1">
	  	  <?php
		   $anohj= date('Y');
		   $anohj= $anohj -1;
		   $anoant= $anohj-5;
		  	for($i=$anohj;$i>$anoant;$i--){
            echo "<option value='$i'>$i</option>";
           }
	      ?>
        </select> 
  	
  
  	</td>
  </tr>
  <tr>
   <td colspan="2" align="center">
    <input name="first" type="hidden">
    <input class="botao" type="submit" name="pesquisa" value="Pesquisa"  onclick="return js_vericampos()">
   </td>
  </tr>
  <tr height="50" align="center">
   <td colspan="2"><?=$DB_mens2?></td>
  </tr>
</table>
</form>

<?
  }else{
  	$sql= "select z01_cgccpf,z01_nome,q02_inscr,q02_numcgm from issbase inner join cgm on z01_numcgm=q02_numcgm where z01_numcgm=$usuario;";
  
  	$result2= db_query("select z01_cgccpf,z01_nome,q02_inscr,q02_numcgm from issbase inner join cgm on z01_numcgm=q02_numcgm where z01_numcgm=$usuario");
  	db_fieldsmemory($result2,0);
  //	echo "<br><br>inscrição=  $z01_nome ";
  
  
  //onKeyDown="FormataCNPJ(this,event)"
  
    ?>
<form name="form1" method="post" <?=$onsubmit?> action="opcoesdae.php">
 	<input name="primeiravez" type="hidden" value="true">
 	<table border="0" cellpadding="0" cellspacing="5" align="center" class="texto">
  		<tr>
  		 	<td colspan="2" align="center"> <br><br> <?=$DB_mens1?> <br><br><br> </td>
  		</tr>
  		<tr>
  			<td colspan="2" align="center" class="titulo2"><strong> <?echo $z01_nome;?> </strong></td>
  		</tr>
  		
  		<tr>
			<td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
			<td><input name="inscricaow" type="text" class="digitacgccpf" size="8" maxlength="6" value="<?=$q02_inscr?>"> </td>
  		</tr>
 		<tr>
			<td align="right">CNPJ:</td>
			<td><input name="cgc" type="text" class="digitacgccpf" id="cgc" size="18" maxlength="18" value="<?=$z01_cgccpf?>" 
                 onKeyPress="FormataCNPJ(this,event); return js_teclas(event);"></td>
  </tr>
  <tr><td></td>
  	<td>
  	<select  name="ano" >
	    	<option value="2004">2004</option>
	    	<option value="2005" selected>2005</option>
	    	
	</select>
  	</td>
  </tr>
  <tr>
   <td colspan="2" align="center">
    <input name="first" type="hidden">
    <input class="botao" type="submit" name="pesquisa" value="Pesquisa"  onclick="return js_vericampos()">
   </td>
  </tr>
  <tr height="50" align="center">
   <td colspan="2"><?=$DB_mens2?></td>
  </tr>
</table>
</form>

<?
  
 
  
  
  }
db_logs("","",0,"Digita Codigo da Inscricao para acessar a DAI.");
?>