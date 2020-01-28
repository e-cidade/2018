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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptuender_classe.php");
include("classes/db_iptubase_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$cliptuender = new cl_iptuender;
$cliptuender->rotulo->label();
$cliptuender->rotulo->tlabel();

if(isset($atualizar)){
   db_inicio_transacao();
   $result = $cliptuender->sql_record($cliptuender->sql_query($j43_matric,"*","",""));
   @db_fieldsmemory($result,0);
   if($cliptuender->numrows==0){
     $cliptuender->incluir($j43_matric);
   }else{
     $cliptuender->alterar($j43_matric);
   }
   db_fim_transacao();
}else{
  if(isset($excluir)){
    $cliptuender->excluir($j43_matric);
  }else{ 
    if(isset($j43_matric)){ 
      $result = $cliptubase->sql_record($cliptubase->sql_query($j43_matric,"j01_matric",""));
      @db_fieldsmemory($result,0);
      if($cliptubase->numrows==0){
        db_redireciona("cad4_iptuender001.php?invalido=true");
      }else{
        $result = $cliptuender->sql_record($cliptuender->sql_query($j43_matric,"*","",""));
        @db_fieldsmemory($result,0);
        if($cliptuender->numrows==0){
          $db_opcao=1;
        }else{
          $db_opcao=2;
        } 
      }
    }  
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
<script>
function js_lo3(){
  document.form1.j43_munic.focus();  
}
function js_volta(){
  location.href="cad4_iptuender001.php";
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_lo3()">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <table border="0">
      <tr>
        <td nowrap title="<?=@$Tj43_matric?>">
          <?=@$Lj43_matric?>
        </td>
        <td> 
<?
  db_input('j43_matric',4,$Ij43_matric,true,'text',3," onchange='js_pesquisaj43_matric(false);'")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_ender?>">
       <?=@$Lj43_ender?>
        </td>
        <td> 
<?
  db_input('j43_ender',40,$Ij43_ender,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_numimo?>">
       <?=@$Lj43_numimo?>
        </td>
        <td> 
<?
  db_input('j43_numimo',10,$Ij43_numimo,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_comple?>">
       <?=@$Lj43_comple?>
        </td>
        <td> 
<?
  db_input('j43_comple',20,$Ij43_comple,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_cxpost?>">
       <?=@$Lj43_cxpost?>
        </td>
        <td> 
<?
  db_input('j43_cxpost',10,$Ij43_cxpost,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_bairro?>">
       <?=@$Lj43_bairro?>
    </td>
    <td> 
<?
db_input('j43_bairro',40,$Ij43_bairro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_munic?>">
          <?=@$Lj43_munic?>
        </td>
        <td> 
<?
  db_input('j43_munic',20,$Ij43_munic,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_uf?>">
       <?=@$Lj43_uf?>
        </td>
        <td> 
<?
  db_input('j43_uf',2,$Ij43_uf,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_cep?>">
       <?=@$Lj43_cep?>
        </td>
        <td> 
<?
  db_input('j43_cep',8,$Ij43_cep,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj43_dest?>">
       <?=@$Lj43_dest?>
        </td>
        <td> 
<?
  db_input('j43_dest',40,$Ij43_dest,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
    </table>
    <input name="atualizar" type="submit" id="atualizar" value="Atualizar" onclick="return js_verifica_campos_digitados();">
    <input name="excluir" type="submit" id="excluir" value="Excluir" <?=($db_opcao==2?"":"disabled")?> >
    <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_volta()" >
    </center>
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if(isset($excluir)){
  if($cliptuender->erro_status=="0"){
    $cliptuender->erro(true,false);
    if($cliptuender->erro_campo!=""){
      echo "<script> document.form1.".$cliptuender->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuender->erro_campo.".focus();</script>";
    }
  }else{
    $cliptuender->erro(true,false);
    db_redireciona("cad4_iptuender001.php");
  }
}
if(isset($atualizar)){
  if($cliptuender->erro_status=="0"){
    $cliptuender->erro(true,false);
    if($cliptuender->erro_campo!=""){
      echo "<script> document.form1.".$cliptuender->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuender->erro_campo.".focus();</script>";
    }
  }else{
    $cliptuender->erro(true,false);
    db_redireciona("cad4_iptuender001.php");
  }
}
?>