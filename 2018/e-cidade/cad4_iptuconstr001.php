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
include("classes/db_iptuconstr_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cliptuconstr = new cl_iptuconstr;
$cliptuconstr->rotulo->label();
$cliptuconstr->rotulo->tlabel();

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
  if(document.form1.j39_matric.value==""){
    alert("Informe a matrícula!");
    return false;
  }
  return true;

}
</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.j39_matric.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="cad4_iptuconstr002.php">
  <tr>
    <td align="left" valign="center" bgcolor="#CCCCCC">
      <center>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td>     
           <?
            db_ancora($Lj39_matric,' js_matri(true); ',1);
           ?>
          </td>
          <td> 
          <?
           db_input('j39_matric',5,0,true,'text',1,"onchange='js_matri(false)'");
           db_input('z01_nome',30,0,true,'text',3,"");
          ?>
          </td>
        </tr>
      </table>
      <input name="entrar" type="submit" id="pesquisa" value="Entrar" onclick="return js_checa()">
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
function js_matri(mostra){
  var matri=document.form1.j39_matric.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostra|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1';
  }
}
function js_mostra(chave1,chave2){
  document.form1.j39_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j39_matric.focus(); 
    document.form1.j39_matric.value = ''; 
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

if(isset($invalido)){
  echo "<script>alert('Numero de matrícula inválido!')</script>";
}
?>