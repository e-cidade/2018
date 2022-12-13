<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("ed52_i_ano");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");
$escola = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Verificar informações do Arquivo - CENSO ESCOLAR</b></legend>
   <table border="0" align="left">
    <tr>
     <td colspan="2">
      <b>Arquivo de exportação gerado pelo sistema:</b>
      <?db_input('arquivo_censo',50,@$Iarquivo_censo,true,'file',3,"");?>
      <?db_input('caminho_arquivo',100,@$Icaminho_arquivo,true,'hidden',3,"");?>
     </td>
    </tr>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
 <tr>
  <td align="center">
   <input name="processar" type="submit" id="arquivo" value="Processar" onclick="return js_valida()">
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_valida(){
 if(document.form1.arquivo_censo.value==""){
  alert("Informe o arquivo gerado pelo sistema!");
  return false;
 }
 document.form1.caminho_arquivo.value = document.form1.arquivo_censo.value;
 return true;
}
</script>
<?
if(isset($processar)){
 $tmp_name = $_FILES["arquivo_censo"]["tmp_name"];
 $name     = $_FILES["arquivo_censo"]["name"];
 $type     = $_FILES["arquivo_censo"]["type"];
 $size     = $_FILES["arquivo_censo"]["size"];
 if(!@copy($tmp_name,"tmp/".$name)){
  db_msgbox("Não foi possível efetuar upload. Verifique permissão do Diretório");
  db_redireciona("edu4_verifexportcenso001.php");
  exit;
 }
 $caminho_arquivo = "tmp/".$name;
 $inicio_nome = "censo_".$escola;
 $explode_nome = explode("_",$name);
 $inicio_nome_arquivo = $explode_nome[0]."_".$explode_nome[1];
 if(trim($inicio_nome)!=trim($inicio_nome_arquivo)){
  db_msgbox("[1] Arquivo informado não é um arquivo de exportação desta escola !");
 }else{
  if(isset($explode_nome[7]) && $explode_nome[7]=="logerro.txt"){
   db_msgbox("[2] Arquivo informado não é um arquivo de exportação gerado pelo sistema!");
  }else{
   $ponteiro3 = fopen($caminho_arquivo,"r");
   $valida_arquivo = false;
   while(!feof($ponteiro3)){
    $linha = fgets($ponteiro3,500);
    if(substr($linha,0,2)!="00"){
     $valida_arquivo = true;
    }
    break;
   }
   fclose($ponteiro3);
   if($valida_arquivo==true){
    db_msgbox("[3] Arquivo informado não é um arquivo de exportação gerado pelo sistema!");
   }else{
    ?>
    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td align="center">
       <fieldset style="width:95%"><legend><b>Informações geradas no arquivo: <font color="red"><?=$name?></font></b></legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td width="38%">
           <iframe name="arvore" id="arvore" src="edu4_verifexportcenso002.php?arquivogerado=<?=$caminho_arquivo?>" width="100%" height="440" scrolling="yes"></iframe>
          </td>
          <td>
           <iframe name="dados" id="dados" src="" width="100%" height="440"></iframe>
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
     </tr>
    </table>
    <?
   }
  }
 }
}
?>
</body>
</html>