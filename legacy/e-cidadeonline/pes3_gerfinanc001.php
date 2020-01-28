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
if(isset($outro)){
 setcookie("cookie_codigo_cgm");
 header("location:digitainscricao.php");
}
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_cgm_classe.php");

$clcgm = new cl_cgm;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$dblink="corpoprincipal.php";
db_logs("","",0,"Consulta Funcional.");
$db_verificaip = db_verifica_ip();
if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";
}else{
  $onsubmit = "";
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style>
a:hover {
  color: #000000;
  }
a:visited {
  color: #0000FF;
}

a:active{
    background-color:#0000FF
}
  
#navigation a {
  font: bold 11px Arial, Helvetica, sans-serif;
  color: #000000;
  line-height:14px;
  letter-spacing:0.1em;
  text-decoration: none;
  display:block;
  padding:4px 4px 4px 10px;
  }
  
#navigation a:hover {
  background: #E6F3FF;
  color:#333333;
  border-bottom: #EDEDED;
  text-transform: capitalize;
  }

#navigation a:visited {
  color: #0000FF;
}

#navigation a:active{
    background-color:#0000FF
}
  
.navText {
  font: 10px Arial, Helvetica, sans-serif;
  color: #DAE3F6;
  line-height:14px;
  letter-spacing:normal;
  text-decoration: none;
  }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<table width="100%" border="1" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
 <tr>
  <td><br></td>
 </tr>
</table>
<?if($id_usuario!=""){?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td height="275">
    <table width="100%" height="408" border="0" align="center" cellpadding="1" cellspacing="1">
      <tr>
        <td width="16%" height="21">&nbsp;</td>
        <td width="84%">
          <div align="center"></div>
        </td>
        <td width="0%">&nbsp;</td>
      </tr>
      <tr>
        <td height="362" valign="top">
	        <table width="135" height="77" id="navigation" border="1">
	          <tr>
	            <td><a href="#" class="navText" onClick="js_alteraFrame('consultaDados');" >Consulta Dados</a></td>
	          </tr>
	          <tr>
	            <td><a href="" class="navText">Assentamentos</a></td>
	          </tr>     
	          <tr>
	            <td><a href="" class="navText">Averbação</a></td>
	          </tr>    
	          <tr>
	            <td><a href="" class="navText" onClick="history.back()">Voltar</a></td>
	          </tr>  
	          <tr>
	            <td><a href="" class="navText" onClick="imprimir()">Imprimir</a></td>
	          </tr>                               
	        </table>
        </td>
        <td valign="top" width="30px">
         <iframe id="iframeDadosFuncionario" name="iframe" src="" width="100%" height="400" style="border:1"></iframe>
        </td>
        <td width="30px">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="center"></div></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
<?}elseif($w13_permfornsemlog == "f"){?>
 <table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
  <tr height="220">
   <td align="center">
    <img src="imagens/atencao.gif"><br>
    Para acessar suas informações, efetue login.
   </td>
  </tr>
 </table>
<?}elseif($w13_permfornsemlog == "t"){

//verifica se está logado
if(@$codigo_cgm!="" || @$_COOKIE["cookie_codigo_cgm"]!=""){
 $usuario = $codigo_cgm==""?$_COOKIE["cookie_codigo_cgm"]:$codigo_cgm;
 $result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_cgccpf, cgm.z01_nome, cgm.z01_numcgm","","cgm.z01_numcgm = $usuario"));
 $linhas  = $clcgm->numrows;

 
 
 }else{?>
 <form name="form1" method="post" action="digitafornecedor.php">
  <table width="100%" border="1" cellspacing="0" cellpadding="0" class="texto">
   <tr>
    <td width="50%" height="30" align="right">Nº Funcionário / CGM:&nbsp;</td>
    <td width="50%" height="30"><input name="codigo_cgm" type="text" class="digitacgccpf" 
        id="codigo_cgm" size="10" maxlength="10"></td>
   </tr>
   <tr>
    <td width="50%" height="30" align="right">CPF:&nbsp;</td>
    <td width="50%" height="30"><input name="cpf" type="text" class="digitacgccpf" id="cpf" 
        onKeyDown="FormataCPF(this,event)" size="14" maxlength="14"></td>
   </tr>
   <tr>
    <td width="50%" height="30">&nbsp;</td>
    <td width="50%" height="30">
     <input class="botao" type="submit" name="pesquisa" value="Pesquisa" class="botaoconfirma">
    </td>
   </tr>
  </table>
 </form>
<?}
}?>
</body>
</html>
<script>
function imprimir(){
 jan=window.open('',
                 '',
                 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
                 
 jan.moveTo(0,0);
}

function js_alteraFrame(sOpcao){

  if ( sOpcao == 'consultaDados') {
    document.getElementById('iframeDadosFuncionario').src = 'dadosfuncionario.php';
  }

}

</script>