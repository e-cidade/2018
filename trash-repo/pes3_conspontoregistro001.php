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
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label("r01_regist");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="if(document.form1.r01_regist)document.form1.r01_regist.focus();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="10">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="10">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <form name="form1" method="post">
	  <table border="0">
        <tr> 
          <td align="right" title="<?=$Tr01_regist?>"> 
            <?
            db_ancora(@ $Lr01_regist, "js_pesquisarregistro(true);", 1);
    		?>
          </td>
          <td> 
            <?
            db_input('r01_regist', 8, $Ir01_regist, true, 'text', 1, " onchange='js_pesquisarregistro(false);'")
            ?>
            <?
            db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr> 
          <td height="25" colspan="2" align="center">
            <input type="button" value="Consultar" name="pesquisar" onclick="js_abrejan();">
          </td>
        </tr>
      </table>
      </form>
      </center>
    </td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_abrejan(){
  qry = "";
  rog = "?";
  if(document.form1.r01_regist.value!=""){
    qry = rog+"r01_regist="+document.form1.r01_regist.value;
    location.href = 'pes3_conspontoregistro002.php'+qry;
  }else{
  	alert("Informe o registro a ser pesquisado");
  }
}
function js_pesquisarregistro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframepessoal','func_rhpessoal.php?funcao_js=parent.js_mostraregistro1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.r01_regist.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframepessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.r01_regist.value+'&funcao_js=parent.js_mostraregistro','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraregistro(chave,erro){
  document.form1.z01_nome.value  = chave;
  if(erro==true){
    document.form1.r01_regist.value = '';
    document.form1.r01_regist.focus();
  }
}
function js_mostraregistro1(chave1,chave2){
  document.form1.r01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframepessoal.hide();
}
</script>