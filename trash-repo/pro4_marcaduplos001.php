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

include("classes/db_db_syscampo_classe.php");
include("classes/db_db_syscampodep_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

$db_opcao = 1;
$db_botao = true;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($gravar)){

  



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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name='form1'>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <table border="0" align='left'> 
	<tr>
	  <td colspan='2'>
	    <table border="0">
	      <tr>
	        <td align="right" title="<?=$Tz01_nome?>">
		  <?
		  echo $Lz01_nome;
		  ?>
		</td>
	        <td align="left">
		   <?
		   db_input("z01_nome",50,$Iz01_nome,true,"text",2);
		   ?>
		</td>
	      </tr> 


	      <tr>
	        <td colspan="2">
		     <iframe name="campos"   marginwidth="0" marginheight="0" frameborder="0" src="" width="750" height="320">
		     </iframe>
                </td>
              </tr>
	      <tr>
	        <td align="center" colspan="2">
		   <input name='elecionar' type='button' value='Selecionar' onclick='js_selecionar();'>
		   <input name='gravar' type='button' value='Gravar' onclick='js_gravar();'>
		</td>
	      </tr> 

            </table> 
	  </td>
	</tr>
      </table>
    </center>
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_selecionar(){
  var nome = document.form1.z01_nome.value.replace('%','|');
  campos.location.href = 'pro4_marcaduplos002.php?z01_nome='+nome;
}
function js_gravar(){
  bj = campos.document.form1;
  for(i=0; i<bj.pri.length; i++){
    if(bj.pri[i].checked==true){
      pri = bj.pri[i].value;
      bj.pri[i].style.visibility='hidden';
      break;
    }
  }
  obj=campos.document.getElementsByTagName("INPUT")
  var vir='';
  var secs="";
  var marcado=false;
  for(i=0; i<obj.length; i++){
    if(obj[i].type=='checkbox'){
      if(obj[i].checked==true){
	 secs += vir+obj[i].value;
	 marcado=true;
	 vir="XX";
         obj[i].style.visibility='hidden';
	 for(ii=0; ii<bj.pri.length; ii++){
           if(bj.pri[ii].value == obj[i].name.substring(4)){
             bj.pri[ii].style.visibility='hidden';
             break;
           }
         } 
      }
      if(pri==obj[i].name.substring(4)){
        obj[i].style.visibility='hidden';
      } 
    }
  }
  if(marcado==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_camp','pro4_marcaduplos003.php?segundo='+secs+'&principal='+pri,'Pesquisa',false);

    bj = campos.document.form1;
    for(i=0; i<bj.pri.length; i++){
      bj.pri[i].checked = false;
    }
    obj=campos.document.getElementsByTagName("INPUT")
    for(i=0; i<obj.length; i++){
      if(obj[i].type=='checkbox'){
        obj[i].checked = false;
      }
    }
  }else{
    alert('Selecione os c�digos!');
  }    
}

</script>