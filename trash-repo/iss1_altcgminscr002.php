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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issbase_classe.php");

$clissbase = new cl_issbase;

$clrotulo = new rotulocampo;
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');

db_postmemory($HTTP_POST_VARS);

if ((isset($z01_numcgm) && $z01_numcgm!="") && (isset($alterar))){
  db_inicio_transacao();
  $sqlerro=false;
  $result_alt=$clissbase->sql_record($clissbase->sql_query_file($q02_inscr));
  if ($clissbase->numrows!=0){
    db_fieldsmemory($result_alt,0);
    $clissbase->q02_numcgm=$z01_numcgm;
    $clissbase->alterar($q02_inscr);
    $erro_msg = $clissbase->erro_msg;
  }
  db_fim_transacao($sqlerro);
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  if (document.form1.z01_numcgm.value!=""){
     return true;
  }else{
    alert("Campo vazio!!");
    return false;
  }
}

</script>  
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
text-align: center;
color: black;
background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" target="" action="">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
<?
$result=$clissbase->sql_record($clissbase->sql_query($q02_inscr,"q02_numcgm as numcgm,z01_nome as nome"));
if ($clissbase->numrows!=0){
   db_fieldsmemory($result,0);
   $q02_inscr=$q02_inscr;
   db_input('q02_inscr',10,"",true,'hidden',3);
}

?>
  <tr> 
    <td  align="left" nowrap title="<?=$Tz01_numcgm?>"><b>Numcgm atual:</b></td>
    <td align="left" nowrap>
      <?db_input("numcgm",6,$Iz01_numcgm,true,"text",3,"");
         db_input("nome",40,"$Iz01_nome",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tz01_numcgm?>"><?db_ancora(@$Lz01_numcgm,"js_pesquisa(true);",1);?></td>
    <td align="left" nowrap>
      <?db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",2,"onchange='js_pesquisa(false);'");
         db_input("nome_novo",40,"$Iz01_nome",true,"text",3);  
        ?></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="alterar" type="submit"   value="Alterar"  onclick=' return js_emite();'>
   </td>
  </tr>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
 
  </table>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//---------------------------------------------------------------
function js_pesquisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.nome_novo.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.nome_novo.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.nome_novo.value = chave2;
  db_iframe_cgm.hide();
}
//----------------------------------------------------------------------
</script>
<?
if ((isset($z01_numcgm) && $z01_numcgm!="") && (isset($alterar))){
    db_msgbox($erro_msg);
    if($clissbase->erro_campo!=""){
      echo "<script> document.form1.".$clissbase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissbase->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='iss1_altcgminscr001.php';</script>";
    }
}
?>
</body>
</html>