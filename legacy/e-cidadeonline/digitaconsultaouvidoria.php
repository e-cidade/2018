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
if (isset($outro)) {
	
 setcookie("cookie_codigo_cgm");
 echo"<script>location.href='digitaconsultaouvidoria.php'</script>";
}

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("classes/db_protprocesso_classe.php");

$clprotprocesso = new cl_protprocesso;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$sSqlDbMenuPref  = "   select distinct m_publico,                       ";
$sSqlDbMenuPref .= "          m_arquivo,                                ";
$sSqlDbMenuPref .= "          m_descricao                               ";
$sSqlDbMenuPref .= "     from db_menupref                               ";
$sSqlDbMenuPref .= "    where m_arquivo = 'digitaconsultaouvidoria.php' ";
$sSqlDbMenuPref .= " order by m_descricao                               ";
$rsSqlDbMenuPref = db_query($sSqlDbMenuPref);
db_fieldsmemory($rsSqlDbMenuPref, 0);

if ($m_publico != 't') {
	
  if (! session_is_registered("DB_acesso")) {
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
  }
}

mens_help();
db_mensagem("ouvidoria_cab", "ouvidoria_rod");
$db_verificaip = db_verifica_ip();
if ($db_verificaip == "0") {
  $onsubmit="onsubmit=\"return js_num_processo()\"";
} else {
  $onsubmit = "";
}  
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_num_processo() {

  var cgc     = document.form1.cgc.value;
  var cpf     = document.form1.cpf.value;
  var codproc = document.form1.codproc.value;

  if (codproc == "" && isNaN(codproc)) {
    
    var sMsgErro  = "Informe o número de controle do processo \n";
        sMsgErro += " ou um número do processo válido!";
    alert(sMsgErro);
    return false;
  }

  if (cgc == "" && cpf == "" ) {
  
    alert("Codigo de CNPJ ou CPF Inválido.");
    return false;
  } else {  
  
    var icnpj = js_CNPJ(cgc);
    var icpf  = js_CPF(cpf);   
    if (cgc != "" && icnpj != 14) {
    
      alert("Codigo de CNPJ Inválido.");
      document.form1.cgc.value = '';
      return false;    
    } else if (cpf != "" && icpf != 11) {
    
      alert("Codigo de CPF Inválido.");
      document.form1.cpf.value = '';
      return false;       
    } else {
      document.form1.submit();
    } 
  }
}

function js_CNPJ(campo) {

  var Campo   = campo;
  var vr      = new String(Campo);
      vr      = vr.replace(".", "");
      vr      = vr.replace(".", "");
      vr      = vr.replace("/", "");
      vr      = vr.replace("-", "");
  var tamcnpj = vr.length
  return tamcnpj;
}

function js_CPF(campo) {

  var Campo  = campo;
  var vr     = new String(Campo);
      vr     = vr.replace(".", "");
      vr     = vr.replace(".", "");
      vr     = vr.replace("-", "");
  var tamcpf = vr.length
  return tamcpf;
}
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center">
   <img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<br>
<center>
<?
//verifica se está logado
if (isset($id_usuario) && !empty($id_usuario)) {
 
	$sCampos  = "distinct cgm.z01_numcgm, cgm.z01_cgccpf, cgm.z01_nome, protprocesso.p58_codproc, ";
  $sCampos .= "protprocesso.p58_dtproc, protprocesso.p58_obs";
	$sSqlProtProcesso = $clprotprocesso->sql_query(null, $sCampos, "protprocesso.p58_dtproc desc", "cgm.z01_numcgm = {$id_usuario}");
  $result           = $clprotprocesso->sql_record($sSqlProtProcesso);
 
 $linhas  = $clprotprocesso->numrows;
 if($linhas!=0){
  db_fieldsmemory($result,0);
  //11 14
  if(strlen($z01_cgccpf)>11){
   $cgc = $z01_cgccpf;
   $cpf = "";
  }else{
   $cgc = "";
   $cpf = $z01_cgccpf;
  }
  //mostra encontrados
  ?>
   <a href="digitaconsultaouvidoria.php?outro='outro processo'">:: Pesquisar Outro Processo ::</a><br><br>
   <b><?=$z01_numcgm." - ".$z01_nome?></b>
   <table width="90%"  class="tab">
   <?
   //busca clientes do escritório
   for ($x =0; $x < $linhas; $x++) {
   	
    if ($x == 0) {
     ?><tr height="20" bgcolor="<?=$w01_corfundomenu?>"><th colspan="4">Meus Processos</th></tr><?
    }
    
    db_fieldsmemory($result,$x);
    ?>
     <tr height="20" onclick="location='pro3_conspro002.php?id_usuario=<?=$id_usuario?>&codproc=<?=$p58_codproc?>&cgccpf=<?=$z01_cgccpf?>'" style="Cursor='hand';" onmouseover="bgColor='#ffffcc'" onmouseout="bgColor=''">
      <td width="5%"><img src="imagens/seta.gif" border="0"></td>
      <td align="right" width="10%"><b><?=$p58_codproc?></b></td>
      <td width="15%"><?=db_formatar($p58_dtproc,'d')?></td>
      <td>&nbsp;<?=$p58_obs?></td>
     </tr>
    <?
   }
  ?></table><?
	
 } else {
  //não tem processo
  ?>
  <br><br>
  <table width="350"  class="tab">
  <tr>
   <td width="100%" nowrap height="28" bgcolor="<?=$w01_corfundomenu?>">
    <table width="100%" border="0" cellpadding="1" cellspacing="0" class="texto">
     <tr><td>
      <img src="imagens/icone.gif" border="0">
     </td><td>
      Numcgm:&nbsp; <span class="bold3"><?=$_COOKIE["cookie_codigo_cgm"]?></span><br>
     </td></tr>
    </table>
   </td>
  </tr>
  <tr height="100">
   <td align="center" class="green">
    Contribuinte sem Processos.<br><br>
    <a href="digitaconsultaouvidoria.php?outro='outro processo 2'">:: Pesquisar Outro Processo ::</a>
   </td>
  </tr>
  </table>
  <?
 }
} else {
?>
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
  <tr>
    <td height="60" align="<?=$DB_align1?>">
      <?=$DB_mens1?>
    </td>
  </tr>
 </table>
 <form name="form1" method="post" <?=$onsubmit?> action="pro3_conspro002.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
    <tr>
      <td width="50%" height="30" align="right">
       Número de Controle do Processo:&nbsp;
      </td>
      <td width="50%" height="30">
        <input type="text" id="cod_processo" name="codproc" class="digitacgccpf" size="10" maxlength="10">
      </td>
    </tr>
    <tr>
      <td width="50%" height="30" align="right">
       CNPJ:&nbsp;
      </td>
      <td width="50%" height="30">
        <input name="cgc" type="text" class="digitacgccpf" id="cgc" size="18" maxlength="18" 
               onChange='js_teclas(event);'
               onKeyPress="FormataCNPJ(this,event); return js_teclas(event);">
      </td>
    </tr>
    <tr>
      <td width="50%" height="30" align="right">
       CPF:&nbsp;
      </td>
      <td width="50%" height="30">
        <input name="cpf" type="text" class="digitacgccpf" id="cpf" size="14" maxlength="14" 
               onChange='js_teclas(event);'
               onKeyPress="FormataCPF(this,event); return js_teclas(event);">
      </td>
    </tr>
    <tr>
      <td width="50%" height="30">&nbsp;</td>
      <td width="50%" height="30">
        <input class="botao" type="submit" name="pesquisa" value="Pesquisa"
               onclick="return js_num_processo();" class="botaoconfirma">
      </td>
    </tr>
  </table>
</form>
<?
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
 <tr>
  <td height="60" align="<?=$DB_align2?>">
   <?=$DB_mens2?>
  </td>
 </tr>
</table>
</center>
</body>
</html>
<?
db_logs("","",0,"Digita Consulta da Ouvidoria.");
if (isset($erroscripts)) {
	
  echo "<script> \n";
  echo "  alert('".$erroscripts."'); \n";
  echo "  location='digitaconsultaouvidoria.php'; \n";
  echo "</script> \n";
}
?>
<script>
  document.getElementById('int_perc1').style.visibility='hidden';
</script>