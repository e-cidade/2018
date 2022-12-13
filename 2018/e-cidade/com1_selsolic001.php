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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

$clsolicita = new cl_solicita;
$clpcsugforn = new cl_pcsugforn;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$clrotulo = new rotulocampo;
$clsolicita->rotulo->label();
db_postmemory($HTTP_POST_VARS);
$action = "com1_orcamento004.php";
if($op == "alterar"){
  $action = "com1_orcamento005.php";
}else if($op == "excluir"){
  $action = "com1_orcamento006.php";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">
<center>
<form name="form1" method="post" action="<?=$action?>">
  <table border='0'>
    <tr height="20px">
      <td ></td>
      <td ></td>
    </tr>
    <tr>
      <td  align="left" nowrap title="<?=$Tpc10_numero?>"> <? db_ancora(@$Lpc10_numero,"js_pesquisapc10_numero(true);",1);?></td>
      <td align="left" nowrap>
    <?
    db_input('pc10_numero',8,$Ipc10_numero,true,"text",3);
    $pc10_depto = db_getsession("DB_coddepto");
    db_input('pc10_depto',8,0,true,"hidden",3,"");
    db_input('db_opcaoal',8,0,true,"hidden",3,"");
    db_input('pc22_codorc',8,0,true,"hidden",3,"");
    db_input('retorno',8,0,true,"hidden",3,"");
    ?>
      </td>
    </tr>
    <tr>
      <td colspan='2' align='center'>
        <input name="enviar" type="button" id="enviar" value="Enviar dados" onclick='js_verifica();'>
      </td>
    </tr>
  </table>
</form>
</center>
</body>
</html>
<?php
$clickaut = false;

if ( !empty($pc22_codorc) ) {

	$result_solic = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query(null,null,"pc22_orcamitem","","pc22_codorc=".@$pc22_codorc));

	if($clpcorcamitemsol->numrows>0){
	  $clickaut = true;
	}
}
?>
<script type="text/javascript">
  function js_verifica(){
  if(document.form1.pc10_numero.value==''){
    alert("Informe o número da solicitação.");
  }else{
    document.form1.submit();
  }
}
function js_pesquisapc10_numero(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcam','db_iframe_solicita','func_solicitaorcam.php?funcao_js=parent.js_mostrapcorcamitem1|pc10_numero&orc=<?=(@$pc22_codorc)?>&departamento=<?=db_getsession("DB_coddepto")?>','Pesquisa',true,'0');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcam','db_iframe_solicita','func_solicitaorcam.php?funcao_js=parent.js_mostrapcorcamitem1|pc10_numero&orc=<?=(@$pc22_codorc)?>&departamento=<?=db_getsession("DB_coddepto")?>','Pesquisa',false,'0');
  }
}
function js_mostrapcorcamitem1(chave1,chave2){
  document.form1.pc10_numero.value = chave1;
  db_iframe_solicita.hide();
  top.corpo.iframe_orcam.location.href = 'com1_selsolic001.php?numerodesolicita='+chave1+'&op=<?=$op?>';
}
<?
if($clickaut == true){
  echo "js_pesquisapc10_numero(false);";
}else if(!isset($numerodesolicita)){
  echo "js_pesquisapc10_numero(true);";
}
?>
document.form1.retorno.value = document.form1.pc22_codorc.value;
<?
if(isset($numerodesolicita) && trim($numerodesolicita)!=""){
  $result_orcam = $clpcsugforn->sql_record($clpcsugforn->sql_query_file($numerodesolicita));
  if($clpcsugforn->numrows>0){
    echo "alert('Usuário: \\n\\nExistem fornecedores sugeridos nesta solicitação. \\nOrçamento não poderá ser gerado.\\n\\nAdministrador:');";
  }else{
    echo "document.form1.pc10_numero.value = $numerodesolicita;\n";
//    echo "document.form1.enviar.click();\n";
  }
}
?>
</script>