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
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
$clprojmelhorias = new cl_projmelhorias;
$clprojmelhorias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("d01_codedi");
$clrotulo->label("d01_descr");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio1() {
  jan = window.open('con2_reledital004.php?edital='+document.form1.d01_codedi.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">

      <tr>
      <br>
        <td nowrap title="<?=@$Td01_codedi?>">
        <?
          db_ancora(@$Ld01_codedi,"js_edi(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('d01_codedi',6,$Id01_codedi,true,'text',$db_opcao," onchange='js_edi(false);'");
      db_input('d01_descr',40,$Id01_descr,true,'text',3);
         ?>
        </td>
      </tr>
            <tr>
              <td colspan="2"   height="25" align="center"><input name="boletim" type="button" id="boletim" onClick="js_relatorio1()" value="Gerar relat�rio">
	      </td>
              <td>
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
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
<script>
function js_edi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_edital.php?funcao_js=parent.js_mostracontri1|d01_codedi|d01_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_edital.php?pesquisa_chave='+document.form1.d01_codedi.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    alert('Edital inv�lido.');  
    document.form1.d01_codedi.value=""; 
    document.form1.d01_codedi.focus(); 
  } else{
    document.form1.d01_descr.value = chave;
  } 
}
function js_mostracontri1(chave1,chave2){
  document.form1.d01_codedi.value = chave1;
  document.form1.d01_descr.value = chave2;
  db_iframe.hide();
}
</script>