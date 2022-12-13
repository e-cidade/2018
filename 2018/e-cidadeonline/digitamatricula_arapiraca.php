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
 header("location:digitamatricula.php");
}
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_imobil_classe.php");
include("classes/db_iptubase_classe.php");
$climobil   = new cl_imobil;
$cliptubase = new cl_iptubase;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitamatricula.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help('digitamatricula.php');
db_mensagem("imovel_cab","imovel_rod");
//$db_verificaip = db_verifica_ip();
//if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";
//}else{
//  $onsubmit = "";
//}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>

try {
    xmlhttp = new XMLHttpRequest();
} catch(ee) {
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch(e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(E){
            xmlhttp = false;
        }
    }
}

</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2;padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center"><img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<br>
<?//mens_div();?>
<br><br>
<center>
<?
  //verifica se está logado
  if(@$id_usuario!="" || @$_COOKIE["cookie_codigo_cgm"]!=""){
   @$usuario = $id_usuario==""?$_COOKIE["cookie_codigo_cgm"]:$id_usuario;
   //é imobiliária?
   $result  = $climobil->sql_record($climobil->sql_query("","iptubase.j01_matric, a.z01_nome as z01_nome,a.z01_cgccpf as z01_cgccpf","","imobil.j44_numcgm = $usuario"));
   $imobil = $climobil->numrows;
   //iptubase

$sqlconf = "select db21_regracgmiptu, db21_regracgmiss from db_config where codigo = ".db_getsession("DB_instit");
$resconf = db_query($sqlconf);
db_fieldsmemory($resconf, 0);

$cliptubase    = new cl_iptubase;
$sqlpromitente = $cliptubase ->sqlmatriculas_nome_numero($usuario, $db21_regracgmiptu);

      $result2 = db_query($sqlpromitente);
      $iptubase = pg_num_rows($result2);
      $sqlcgm = "select z01_cgccpf from cgm where z01_numcgm = $usuario ";
      $resultcgm = db_query($sqlcgm);
      $linhascgm = pg_num_rows($resultcgm);
      if($linhascgm>0){
        db_fieldsmemory($resultcgm,0);
      }
  }
 if((@$imobil==0 && @$iptubase==0) || @$usuario==""){
 ?>
 <form name="form1" method="post" <?=$onsubmit?> action="opcoesdebitospendentes.php">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
     
    <? /*
     <tr>
      <td width="50%" height="30" align="right">
        Matr&iacute;cula Im&oacute;vel:&nbsp;
      </td>
      <td width="50%" height="30">
       <input name="matricula1" type="text" class="digitacgccpf" id="matricula1" size="20" maxlength="10">
      </td>
     </tr>
     */ ?>
     <tr>
      <td width="50%" height="30" align="right">
        INSCRICAO:&nbsp;
      </td>
      <td width="50%" height="30">
       <input name="referencia" type="text"  id="referencia" size="20" maxlength="20">
      </td>
     </tr>

     <tr>
     <td width="50%" height="30" align="right">
      <img src="captcha/securimage_show.php?sid=<? echo md5(uniqid(time())); ?>" id="image" align="absmiddle" />
      <br>
      <a href="#" onclick="document.getElementById('image').src = 'captcha/securimage_show.php?sid=' + Math.random(); return false">Nova imagem</a>
     </td>
     <td width="50%" height="30">
       <input name="letras" type="text" value="" size= "20">
     </td>
    </tr>
    <tr>
      <td width="50%" height="30">&nbsp;</td>
      <td width="50%" height="30">
         <input  class="botao" type="button" name="pesquisa" value="Pesquisa" onClick = "return js_captcha();">
         <input type="hidden" name="opcao" value="m" >
      </td>
     </tr>
    </table>
  </form>
 <?
 }else{
   ?>
   <a href="digitamatricula.php?outro">:: Pesquisar Outro Imóvel ::</a><br><br>
   <table width="350" border="0" cellspacing="0" cellpadding="3" class="texto">
   <?
  
   //busca clientes do escritório
   for($x=0;$x<$imobil;$x++){
    if($x==0){
     ?><tr height="20" bgcolor="#eaeaea"><td colspan="3"><b>Matrículas que tenho acesso</b></td></tr><?
    }
    db_fieldsmemory($result,$x);
    if($imobil==1 && $iptubase==0){
     $redireciona = "opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=$id_usuario");
     db_redireciona($redireciona);
    }
    echo "<tr height=\"20\"><td><img src=\"imagens/seta.gif\" border=\"0\"></td><td align=\"right\"><a href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario)."\"><b>".$j01_matric."</b></a></td><td><a href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario)."\">".$z01_nome."</a></td></tr>";
    echo "<tr height=\"1\" bgcolor=\"#cccccc\"><td colspan=\"3\"></td></tr>";
   }
   //busca dados do issbase
   for($x=0;$x<$iptubase;$x++){
    if($x==0){
     ?><tr height="20" bgcolor="<?=$w01_corfundomenu?>"><td colspan="3"><b>Minhas Matrículas</b></td></tr><?
    }
    db_fieldsmemory($result2,$x);
    if($imobil==0 && $iptubase==1){
     $redireciona = "opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);
     if(!isset($DB_LOGADO)){
       @include($redireciona);
     }else{
        db_redireciona($redireciona);
     }
    }
    echo "<tr height=\"20\"><td><img src=\"imagens/seta.gif\" border=\"0\"></td><td align=\"right\"><a class=\"links\" href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario)."\"><b>".$j01_matric."</b></a></td><td><a class='links' href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario)."\"> ".$z01_nome." - ".$proprietario."</a></td></tr>";
    echo "<tr height=\"1\" bgcolor=\"$w01_corfundomenu\"><td colspan=\"3\"></td></tr>";
   }
   ?></table><?
   }
   db_logs("","",0,"Digita Codigo da Matrícula.");
  if(isset($erroscripts)){
   echo "<script>alert('".$erroscripts."');</script>";
  }
?>

<iframe src='captcha/pesquisa_captcha.php?code=<?@$code?>' name="pesquisacaptcha" style="visibility:hidden"></iframe>

<br><br>
</center>
</body>
</html>

<script>
  document.getElementById('int_perc1').style.visibility='hidden';
   
function js_captcha(){

  xmlhttp.open("GET",'captcha/pesquisa_captcha.php?code='+document.form1.letras.value,true);

    //Executada quando o navegador obtiver o código
    xmlhttp.onreadystatechange=function() {

        if (xmlhttp.readyState==4){
            var texto=xmlhttp.responseText;
            if (texto=='true') {
              retorno_captcha(true);
            }else {
              retorno_captcha(false);
            }
        }
    }
    xmlhttp.send(null)
}
function retorno_captcha(ret){
  // váriavel ret retorna true ou false
  
  if (ret) {  
    document.form1.submit();
  } else {
     alert('Número de confirmação não confere');
     document.form1.letras.value = '';
     document.form1.letras.focus();
     document.getElementById('image').src = 'captcha/securimage_show.php?sid=' + Math.random(); 
     return false;
  }
}
</script>