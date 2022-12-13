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
include("classes/db_cfiptu_classe.php");
include("classes/db_iptubase_classe.php");


db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao=1;
$db_opcao=1;

$clcfiptu = new cl_cfiptu;
$clcfiptu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

$exercicio=db_getsession("DB_anousu");

  $result = $clcfiptu->sql_record($clcfiptu->sql_query_file($exercicio,"cfiptu.*","",""));
  if($clcfiptu->numrows!=0){
    db_fieldsmemory($result,0);
    $db_botao=3; 
  }
   
  if(isset($incluir)){
    $clcfiptu->j18_anousu=$exercicio;   
    $clcfiptu->incluir($exercicio);
  }else{
    if(isset($alterar)){
      $clcfiptu->j18_anousu=$exercicio;   
      $clcfiptu->alterar($exercicio);
    }else{
      if(isset($excluir)){
        $clcfiptu->j18_anousu=$exercicio;   
        $clcfiptu->excluir($exercicio);
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
<script>
function js_checa(){
  if(!js_verifica_campos_digitados()){
    return false;
  } 
  if(document.form1.j44_matric.value==""){
    alert("Informe a matrícula!");
    return false;
  }
  return true;

}
function js_verimatri(valor){
  if(valor!=""){
    

  }  
}
</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
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
    <td align="left" valign="center" bgcolor="#CCCCCC">
      <center>
      <table border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td nowrap title="<?=@$Tj18_anousu?>">
       <?=@$Lj18_anousu?>
    </td>
    <td> 
<?
db_input('j18_anousu',4,$Ij18_anousu,true,'text',3,"","exercicio")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj18_vlrref?>">
       <?=@$Lj18_vlrref?>
    </td>
    <td> 
<?
db_input('j18_vlrref',15,$Ij18_vlrref,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj18_dtoper?>">
       <?=@$Lj18_dtoper?>
    </td>
    <td> 
<?
db_inputdata('j18_dtoper',@$j18_dtoper_dia,@$j18_dtoper_mes,@$j18_dtoper_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj18_rterri?>">
       <?=@$Lj18_rterri?>
    </td>
    <td> 
<?
db_input('j18_rterri',4,$Ij18_rterri,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj18_rpredi?>">
       <?=@$Lj18_rpredi?>
    </td>
    <td> 
<?
db_input('j18_rpredi',4,$Ij18_rpredi,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj18_vencim?>">
       <?=@$Lj18_vencim?>
    </td>
    <td> 
<?
db_input('j18_vencim',4,$Ij18_vencim,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

      </table>
      <input name="incluir" type="submit" id="incluir" value="Incluir" <?=($db_botao!=1?"disabled":"")?> >
      <input name="alterar" type="submit" id="alterar" value="Alterar" <?=($db_botao!=3?"disabled":"")?> >
      <input name="excluir" type="submit" id="excluir" value="Excluir" <?=($db_botao!=3?"disabled":"")?> >
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
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_cfiptu.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_pesquisaj44_matric(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j44_matric.value+'&funcao_js=parent.js_mostraiptubase';
  }
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if(isset($alterar)||isset($excluir)||isset($incluir)){
  if($clcfiptu->erro_status=="0"){
    $clcfiptu->erro(true,false);
    if($clcfiptu->erro_campo!=""){
      echo "<script> document.form1.".$clcfiptu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfiptu->erro_campo.".focus();</script>";
    }
  }else{
    $clcfiptu->erro(true,false);
    echo "<script>document.form1.submit();</script>"; 
  }
}
?>