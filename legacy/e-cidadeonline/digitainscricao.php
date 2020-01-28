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

session_start();

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("db_funcoes.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_escrito_classe.php");

$clissbase = new cl_issbase;
$clescrito = new cl_escrito;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));


$rsParametro = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession("DB_instit"),"w13_exigecpfcnpjinscricao"));
$oRetorno    = db_utils::fieldsMemory($rsParametro,0);

if ($oRetorno->w13_exigecpfcnpjinscricao == "t") {
	/*
	 * Esta mensagem deve ser alterada diretamente no banco de dados
	* db_confmensagem
	* cod: imovel_cab_cpfcnpj
	*/
	$sMensagemCabecalho = "alvara_cab_cpfcnpj";
	$sMensagemRodape = "alvara_rod_cpfcnpj";
} else {
	$sMensagemCabecalho = "alvara_cab";
	$sMensagemRodape = "alvara_rod";
}

$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref
                       WHERE m_arquivo = 'digitainscricao.php'
                       ORDER BY m_descricao
                       ");

db_fieldsmemory($result,0);
if ($m_publico != 't') {
  if (!session_is_registered("DB_acesso")) {
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
  }
}
mens_help();
db_mensagem($sMensagemCabecalho,$sMensagemRodape);

$db_verificaip = db_verifica_ip();
if ($db_verificaip == "0") {
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";
} else {
  $onsubmit = "";
}

// busca dados para armazenar em cookies
if (@$_COOKIE["cookie_codigo_cgm"]=="") {

 // issbase
 if (@$inscricaow!="") {
  $result  = $clissbase->sql_record($clissbase->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","issbase.q02_inscr = $inscricaow"));
  $linhas1 = $clissbase->numrows;
 }

 // cgm
 if (@$codigo_cgm!="") {
  $result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","cgm.z01_numcgm = $codigo_cgm"));
  $linhas2 = $clcgm->numrows;
 }

 // iptu
 if (@$matricula1!=""){
  $result  = $cliptubase->sql_record($cliptubase->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","iptubase.j01_matric = $matricula1"));
  $linhas3 = $cliptubase->numrows;
 }

 db_fieldsmemory($result,0);
 @setcookie("cookie_codigo_cgm",$z01_numcgm);
 @setcookie("cookie_nome_cgm",$z01_nome);
 @$cookie_codigo_cgm = $z01_numcgm;
} else {
 @$cookie_codigo_cgm = $_COOKIE["cookie_codigo_cgm"];
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center"><img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<!--<img src="imagens/alvara.gif">-->
<?//mens_div();?>
<br><br><center>
<?

// verifica se está logado
  if(( @$id_usuario!="" ) && !isset($outro)){
   @$usuario = $id_usuario;

   // é escritório?
   $wherebx = " and q10_dtfim is null ";
   if (@$mostrainscricao == 1) {

   // todas
   $wherebx = " and q10_dtfim is null ";

   } else if (@$mostrainscricao == 2) {

   // baixadas
     $wherebx = " and q10_dtfim is null and q02_dtbaix is not null ";
   } if (@$mostrainscricao == 3) {

   // não baixadas
     $wherebx = " and q10_dtfim is null and q02_dtbaix is null ";
   }

   // retorna todos os clientes do escritório
   $result  = $clescrito->sql_record($clescrito->sql_query("","q02_inscr,cgm.z01_nome as z01_nome,cgm.z01_cgccpf as z01_cgccpf","cgm.z01_nome","q10_numcgm = $usuario $wherebx"));
   // echo($clescrito->sql_query("","q02_inscr,a.z01_nome as z01_nome,a.z01_cgccpf as z01_cgccpf","a.z01_nome","q10_numcgm = $usuario"));
   $escrito = $clescrito->numrows;

   // é issbase
   $result2 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $usuario"));

   //$result2 = $clissbase->sql_record($clissbase->sqlinscricoes_socios(0,$cookie_codigo_cgm,"*"));
   $issbase = $clissbase->numrows;

   // sócios
   //$sql = $clissbase->sqlinscricoes_socios(0,$cookie_codigo_cgm,"*");
   }

   if( isset($usuario) ) {
   $sqlesc = "select * from cadescrito where q86_numcgm= $usuario";
					$resultesc  =  @db_query($sqlesc);
					$escritorio =  @pg_num_rows($resultesc);
   }

  if((@$escrito==0 && @$issbase==0) || @$usuario==""){
   ?>
   <form name="form1" method="post" <?=$onsubmit?> action="opcoesdebitospendentes.php?id_usuario=<?=$id_usuario?>">
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
 		<tr>
  		 	<td colspan="2" align="center"> <?=$DB_mens1?> <br /> <br /> <br /> </td>
  		</tr>
    <tr>
     <td width="50%" height="30" align="right">
      Inscri&ccedil;&atilde;o Alvar&aacute;:&nbsp;
     </td>

     <td width="50%" height="30">
        <?
         db_input("Inscrição", 10, 1, true, "text", 1, " class=\"digitacgccpf\" onfocus=this.value=''", "inscricaow");
        ?>

     </td>
    </tr>

<?
 if ($oRetorno->w13_exigecpfcnpjinscricao == "t") {
?>
    <tr>
     <td width="50%" height="30" align="right">
      CNPJ:&nbsp;
     </td>
     <td width="50%" height="30">
      <input name="cgc" type="text" class="digitacgccpf"
             id="cgc" size="18" maxlength="18" onKeyPress="FormataCNPJ(this,event); return js_teclas(event);">
     </td>
    </tr>
    <tr>
     <td width="50%" height="30" align="right">
      CPF:&nbsp;
     </td>
     <td width="50%" height="30">
      <input name="cpf" type="text" class="digitacgccpf" id="cpf" size="14" maxlength="14" onKeyPress="FormataCPF(this,event); return js_teclas(event);">
     </td>
    </tr>
<?
 }
?>
    <tr>
     <td width="50%" height="30">&nbsp;</td>
     <td width="50%" height="30">
      <input class="botao" type="submit" name="pesquisa" value="Pesquisar" class="botaoconfirma" onClick="return js_valida();">
      <input type="hidden" name="opcao" value="i">
     </td>
    </tr>
   </table>
   </form>
   <?
   }else{//#################### esta logado  como escritorio

   if($escritorio!=0){

   	?>
   	<form name="form2" method="post" <?=$onsubmit?> action="opcoesdebitospendentes.php?id_usuario=<?=$id_usuario?>">
   	<table width="50%">
   	<tr>
     <td width="50%" height="30" align="center" >
      Inscri&ccedil;&atilde;o Alvar&aacute;:&nbsp;
      <input name="inscricao" type="text" class="digitacgccpf" size="8" maxlength="6">
     </td>
     </tr>
     <tr>
     <td align="center">
      CNPJ:
      <input name="cgc" type="text" class="digitacgccpf"
             id="cgc" size="18" maxlength="18" onKeyPress="FormataCNPJ(this,event); return js_teclas(event);">
      <input class="botao" type="submit" name="pesquisa" value="Pesquisar" class="botaoconfirma">
      <input type="hidden" name="opcao" value="i">
      <input type="hidden" name="logadoescrito" value="1">
     </td>
    </tr>
    </table>
    </form>

   <?
   }
   if($escrito>0){
     ?>
     <a href="digitainscricao.php?id_usuario=<?=$id_usuario?>&outro=''">:: Pesquisar Outro Alvará ::</a><br><br>
     <?
   }
   ?>
   <form name="form3" method="post" >
   <table width="350" border="0" cellspacing="0" cellpadding="3" class="texto">
   <? if($escrito>0){ ?>
   <tr height="20" ><td colspan="3"><b>Mostrar inscriçoes</b>
   <select name="mostrainscricao" onchange = "document.form3.submit();">
   <? echo "
   <option value = '1'".($mostrainscricao == 1?"selected":"").">Todas</option>
   <option value = '2'".($mostrainscricao == 2?"selected":"").">Somente baixadas</option>
   <option value = '3'".($mostrainscricao == 3?"selected":"").">Somente não baixadas</option>
   ";
   ?>
   </select>

   <? } ?>
   </td></tr>
   <tr height="20" ><td colspan="3">&nbsp;</td></tr>
   <?

   //busca clientes do escritório
   for($x=0;$x<$escrito;$x++){
    if($x==0){
     ?><tr height="20" bgcolor="#eaeaea"><td colspan="3"><b>Inscrições que tenho acesso</b></td></tr><?
    }
    db_fieldsmemory($result,$x);
    if($escrito==1 && $issbase==0){
     $redireciona = "opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=i&id_usuario=$id_usuario");
     db_redireciona($redireciona);
    }
    echo "<tr height=\"20\"><td><img src=\"imagens/seta.gif\" border=\"0\"></td><td align=\"right\"><a href=\"opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&opcao=i&id_usuario=$id_usuario")."\"><b>".$q02_inscr."</b></a></td><td><a href=\"opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=i&id_usuario=$id_usuario")."\">".$z01_nome."</a></td></tr>";
    echo "<tr height=\"1\" bgcolor=\"#cccccc\"><td colspan=\"3\"></td></tr>";
   }

   //busca dados do issbase
   for($x=0;$x<$issbase;$x++){
    if($x==0){
     ?><tr height="20" bgcolor="<?=$w01_corfundomenu?>"><td colspan="3"><b>Minha Inscrição</b></td></tr>
     <?
    }
    db_fieldsmemory($result2,$x);
    if($escrito==0 && $issbase==1){
     $redireciona = "opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&opcao=i&id_usuario=".@$id_usuario);
     if(!isset($DB_LOGADO)){
       @include($redireciona);
     }else{
        db_redireciona($redireciona);
     }
    }
    echo "<tr height=\"20\"><td><img src=\"imagens/seta.gif\" border=\"0\"></td><td align=\"right\"><a class='links' href=\"opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&opcao=i&id_usuario=".@$id_usuario)."\"><b>".$q02_inscr."</b></a></td><td><a class='links'href=\"opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&opcao=i&id_usuario=".@$id_usuario)."\">".$z01_nome."</a></td></tr>";
    echo "<tr height=\"1\" bgcolor=\"$w01_corfundomenu\"><td colspan=\"3\"></td></tr>";
   }
   ?></table>
   </form>
   <?
   }
   db_logs("","",0,"Digita Codigo da Inscricao.");
  if(isset($erroscripts)){
   echo "<script>alert('".$erroscripts."');</script>";
  }
?>
</center>

<script type="text/javascript">
/**
 * Validação para tentativa de colar caracteres especiais e/ou caracteres EM campo numérico
 */

<?php
 if ($oRetorno->w13_exigecpfcnpjinscricao == "t") {
?>
  document.getElementById('cpf').onpaste = function(event) {

     var self = this;
     return setTimeout(function() {
      var lNumeros = new RegExp(/^[0-9]+$/).test(self.value);
        if (!lNumeros) {
           alert('CPF deve ser preenchido somente com números!');
           self.value = '';
         }
       }, 5);
     }

  document.getElementById('cgc').onpaste = function(event) {

     var self = this;
     return setTimeout(function() {
      var lNumeros = new RegExp(/^[0-9]+$/).test(self.value);
        if (!lNumeros) {
           alert('CNPJ deve ser preenchido somente com números!');
           self.value = '';
         }
       }, 5);
     }
<?php
 }

 if((@$escrito==0 && @$issbase==0) || @$usuario==""){
?>
  document.getElementById('inscricaow').onpaste = function(event) {

     var self = this;
     return setTimeout(function() {
      var lNumeros = new RegExp(/^[0-9]+$/).test(self.value);
        if (!lNumeros) {
           alert('Inscrição Alvará deve ser preenchido somente com números!');
           self.value = '';
         }
       }, 5);
     }
<?php } ?>

  function js_valida(){

    <?php
      if ($oRetorno->w13_exigecpfcnpjinscricao == "t") {
    ?>
       if( document.getElementById('cpf').value == '' &&  document.getElementById('cgc').value == '') {

         alert('É obrigatório informar o CNPJ ou CPF');
         document.getElementById('cgc').focus();
         return false;
       }

       var oRegexCPFCNPJ  = /(^\d{3}\.\d{3}\.\d{3}\-\d{2}$)|(^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$)/;
       if( document.getElementById('cpf').value != '') {
          if ( !oRegexCPFCNPJ.test( document.getElementById('cpf').value ) ) {

            alert('O número de CPF informado é inválido!');
            document.getElementById('cpf').value = '';
            return false;
          }
       }
       if( document.getElementById('cgc').value != '') {
          if ( !oRegexCPFCNPJ.test( document.getElementById('cgc').value ) ) {

            alert('O número de CNPJ informado é inválido!');
            document.getElementById('cgc').value = '';
            return false;
          }
       }
    <?
      }
    ?>

    if( document.getElementById('inscricaow').value != '') {
      var oRegex  = /^[0-9]+$/;
      if ( !oRegex.test( document.getElementById('inscricaow').value ) ) {
      alert('Campo Inscrição Alvará deve ser preenchido somente com números!');
      document.getElementById('inscricaow').value = '';
      return false;
      }
    }

		if (document.form1.inscricaow.value == ""){
			alert('Campo Inscrição Alvará é de preenchimento obrigatório.');
			return false;
		}else{
			return true;
		}
	}
  document.getElementById('int_perc1').style.visibility='hidden';
</script>