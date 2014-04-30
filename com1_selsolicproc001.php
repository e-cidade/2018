<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
$clpcproc = new cl_pcproc;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clrotulo = new rotulocampo;
$clpcproc->rotulo->label();
db_postmemory($HTTP_POST_VARS);
$action = "com1_processo004.php";
if($op == "alterar"){
  $action = "com1_processo005.php";
}else if($op == "excluir"){
  $action = "com1_processo006.php";
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
      <td  align="left" nowrap title="<?=$Tpc80_codproc?>"> <? db_ancora(@$Lpc80_codproc,"js_pesquisapc80_codproc(true);",1);?></td>
      <td align="left" nowrap>
        <?
        db_input('pc80_codproc',8,$Ipc80_codproc,true,"text",3);
        db_input('db_opcaoal',8,0,true,"hidden",3,"");
        db_input('pc22_codorc',8,0,true,"hidden",3,"");
        db_input('retorno',8,0,true,"hidden",3,"");
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align='center'>
        <input name="enviar" type="button" id="enviar" value="Enviar dados" onclick='js_verifica();'>
      </td>
    </tr>
  </table>
</form>
</center>
</body>
</html>
<script type="text/javascript">
<?
$clickaut     = false;
if (isset($pc22_codorc) && !empty($pc22_codorc)) {
  
  $result_solic = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query(null,null,"pc22_orcamitem","","pc22_codorc=".@$pc22_codorc));
  if ($clpcorcamitemproc->numrows>0) {
    $clickaut = true;
  }
}
?>

function js_verifica() {
  
  if(document.form1.pc80_codproc.value==''){
    alert("Informe o número do processo de compras.");
  }else{
    document.form1.submit();
  }
}

function js_pesquisapc80_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcam',
                        'db_iframe_pcproc',
                        'func_pcproc.php?orclic=true&situacao=2&funcao_js=parent.js_mostrapcproc1|pc80_codproc',
                        'Pesquisa',
                        true,
                        '0');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcam',
                        'db_iframe_pcproc',
                        'func_pcproc.php?orclic=true&situacao=2&funcao_js=parent.js_mostrapcproc1|pc80_codproc',
                        'Pesquisa',false,'0');
  }
}
function js_mostrapcproc1(chave1,chave2){
  document.form1.pc80_codproc.value = chave1;
  db_iframe_pcproc.hide();
  <?
  if($clickaut == true){
    echo "document.form1.enviar.click();";
  }
  ?>
}
<?
if($clickaut == true){
  echo "js_pesquisapc80_codproc(false);";
}else{
  echo "js_pesquisapc80_codproc(true);";
}
?>
document.form1.retorno.value = document.form1.pc22_codorc.value;
</script>