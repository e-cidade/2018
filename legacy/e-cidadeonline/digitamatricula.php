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

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("db_funcoes.php");
require_once("classes/db_imobil_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_configdbpref_classe.php");

$climobil       = new cl_imobil;
$cliptubase     = new cl_iptubase;
$clconfigdbpref = new cl_configdbpref;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$rsParametro = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession("DB_instit"),"w13_exigecpfcnpjmatricula"));
$oRetorno    = db_utils::fieldsMemory($rsParametro,0);

if ($oRetorno->w13_exigecpfcnpjmatricula == "t") {
	/*
	 * Esta mensagem deve ser alterada diretamente no banco de dados
	 * db_confmensagem
	 * cod: imovel_cab_cpfcnpj
	 */
	$sMensagem = "imovel_cab_cpfcnpj";
} else {
	$sMensagem = "imovel_cab";
}
db_mensagem($sMensagem,"");

$result = db_query(" select distinct m_publico,m_arquivo,m_descricao
                       from db_menupref
											where m_arquivo = 'digitamatricula.php'
											order by m_descricao ");
db_fieldsmemory($result,0);

if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}

mens_help();
db_mensagem($sMensagem,$sMensagem);
$onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
	<? db_estilosite(); ?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2;padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:visible">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center">
   <img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='hidden';
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <?mens_OnHelp()?>>
<br /> <br /> <br /> <center>
<?
  //verifica se está logado
	if ((isset($id_usuario) && trim($id_usuario) != "") ) {

	  $usuario = $id_usuario;

  //é imobilária

  if(is_numeric($usuario)){

   $result = $climobil->sql_record($climobil->sql_query("","iptubase.j01_matric, a.z01_nome as z01_nome,a.z01_cgccpf as z01_cgccpf","","imobil.j44_numcgm = $usuario"));
   $imobil = $climobil->numrows;
   //iptubase

		$sqlconf = "select db21_regracgmiptu, db21_regracgmiss from db_config where codigo = ".db_getsession("DB_instit");
		$resconf = db_query($sqlconf);
		db_fieldsmemory($resconf, 0);


		// exibe os imovéis do usuário
		$cliptubase    = new cl_iptubase;
		$sqlpromitente = $cliptubase ->sqlmatriculas_nome_numero($usuario, $db21_regracgmiptu);

    $result2  = db_query($sqlpromitente);
    $iptubase = pg_num_rows($result2);
    $sqlcgm		= "select z01_cgccpf from cgm where z01_numcgm = $usuario ";
    $resultcgm = db_query($sqlcgm);
    $cgccpf_imobil = pg_result($resultcgm,0,0);
    $linhascgm = pg_num_rows($resultcgm);
  }
    if(isset($linhascgm) and $linhascgm > 0){
      db_fieldsmemory($resultcgm,0);
    }
  }

  $iLogin = db_getsession ( 'DB_login' );

	if((@$imobil==0 && @$iptubase==0) || @$usuario=="") {
	?>
   <form name="form1" method="post" <?=$onsubmit?> action="opcoesdebitospendentes.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
     <tr>
     	<td colspan=2 align="center"><?=$DB_mens1?></td>
     </tr>
     <tr>
      <td width="50%" height="30" align="right">
        Matr&iacute;cula do Im&oacute;vel:&nbsp;
      </td>
      <td width="50%" height="30">
      	<?
      	  db_input("Matricula", 10, 1, true, "text", 1, " class=\"digitacgccpf\" onfocus=this.value=''", "matricula1");

      	?>
      </td>
     </tr>

<?
  if ($oRetorno->w13_exigecpfcnpjmatricula == "t") {

?>
     <tr>
      <td width="50%" height="30" align="right">
       CNPJ:&nbsp;
      </td>
      <td width="50%" height="30">
       <input name="cgc" type="text" class="digitacgccpf" id="cgc" size="18" maxlength="18" onKeyPress="FormataCNPJ(this,event); return js_teclas(event);">
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
         <input  class="botao" type="submit" name="pesquisa" value="Pesquisar" class="botaoconfirma" onClick="return js_valida();">
         <input type="hidden" name="opcao" value="m" >
      </td>
     </tr>
    </table>
  </form>

<?

 } else {

 ?>


   <a href="digitamatricula.php?outro">:: Pesquisar Outro Imóvel ::</a><br><br>
   <table width="350" border="0" cellspacing="0" cellpadding="3" class="texto">

 <?

   //busca clientes do escritório

  for ( $x=0; $x<$imobil; $x++ ) {
    if ( $x==0 ) {
	?>
     <tr height="20" bgcolor="#eaeaea">
	   <td colspan="3">
		 <b>
		  Matrículas que tenho acesso
	    </b>
	  </td>
	</tr>
	<?
      }
			db_fieldsmemory($result,$x);
			if($imobil==1 && $iptubase==0){
					$redireciona = "opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=$id_usuario");
					db_redireciona($redireciona);
			}
    echo "<tr height=\"20\">";
    echo "<td><img src=\"imagens/seta.gif\" border=\"0\"></td><td align=\"right\"><a href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario."&imob=true")."\"><b>".$j01_matric."</b></a></td>";
    echo "<td><a href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario."&imob=true")."\">".$z01_nome."</a></td></tr>";
    echo "<tr height=\"1\" bgcolor=\"#cccccc\"><td colspan=\"3\"></td></tr>";
   }

// verifica se o usuário não está logado
$iLogin = db_getsession ( 'DB_login' );

if( !isset($iLogin) ) {

 // condição faz com que entre novamente na tela para o usuário informar o número da matrícula
 ?>
   <script>
     document.cookie = 'cookie_codigo_cgm=;';
	 location.href = 'digitamatricula.php';
   </script>
 <?

}
   //busca dados do issbase
   for($x=0;$x<$iptubase;$x++){

	 if($x==0){
       ?>
	     <tr height="20" bgcolor="<?=$w01_corfundomenu?>">
	     <td colspan="3"> <b> Minhas Matrículas </b> </td>
	     </tr>
	   <?
     }

	db_fieldsmemory($result2,$x);

	if($imobil==0 && $iptubase==1){
      $redireciona = "opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);

	  if(!isset($DB_LOGADO)){
        @include($redireciona);
      } else {
        db_redireciona($redireciona);
      }

	}
?>

<tr height="20">
  <td>
    <img src="imagens/seta.gif" border="0">
  </td>
<td align="right">

<?
  echo " <a class=\"links\" href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=$cgccpf_imobil&opcao=m&id_usuario=".@$id_usuario)."\"> <b>".$j01_matric."</b> </a> ";
?>

  </td>
  <td>

<?
  if(strlen($cgccpf_imobil) > 11){
   $cgc = $cgccpf_imobil;
  } else {
   $cpf = $cgccpf_imobil;
  }
  echo " <a class=\"links\" href=\"opcoesdebitospendentes.php?".base64_encode("matricula1=$j01_matric&cgc=".@$cgc."&cpf=".@$cpf."&opcao=m&id_usuario=".@$id_usuario)."\"> ".$z01_nome." - ".$proprietario."</a> ";
?>

  </td>
</tr>
<tr height="1" bgcolor="<?=$w01_corfundomenu?>">
  <td colspan="3">
  </td>
</tr>

<?
  }
?>

</table>

<?

   }
   db_logs("","",0,"Digita Codigo da Matrícula.");
  if(isset($erroscripts)){
    echo "<script>alert('".$erroscripts."');</script>";
  }
?>
</center>
<br /> <br />

<script>
/**
 * Validação para tentativa de colar caracteres especiais e/ou caracteres EM campo numérico
 */
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

  document.getElementById('matricula1').onpaste = function(event) {
  
     var self = this;
     return setTimeout(function() {
      var lNumeros = new RegExp(/^[0-9]+$/).test(self.value);
        if (!lNumeros) {
           alert('Matrícula do Imóvel deve ser preenchido somente com números!');
           self.value = '';
         }
       }, 5);
     }

  function js_valida(){

    <?php
      if ($oRetorno->w13_exigecpfcnpjmatricula == "t") {
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

     if( document.getElementById('matricula1').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('matricula1').value ) ) {
          alert('Campo Matrícula do Imóvel deve ser preenchido somente com números!');
          document.getElementById('matricula1').value = '';
          return false;
        }
     }

    if(document.form1.matricula1.value == ""){
	    alert('Campo Matrícula do Imóvel é de preenchimento obrigatório.');
	    return false;
	  } else {
	    return true;
    }
  }
	document.getElementById('int_perc1').style.visibility='hidden';
</script>