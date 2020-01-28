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
  $clrotulo = new rotulocampo;  
  $clrotulo->label("ob09_codhab");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_AbreJanelaRelatorio() { 
    if( document.form1.ob09_codhab.value!='' ) {
      jan = window.open('pro2_cartahabitacao002.php?codigo='+document.form1.ob09_codhab.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    
    }else{
      alert('Você deverá digitar o código da habitação.');
    }    
  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.ob09_codhab.focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"><table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
		    <form name="form1" method="post" >
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center">
				    <table width="100%" border="1" cellspacing="0" cellpadding="0">
                      <tr align="center"> 
                        <td colspan="2" width="34%" bgcolor="#0099CC"><strong>Carta de habitação</strong></td>
                      </tr>
                      <tr>
                        <td nowrap title="<?=@$Tob09_codhab?>" align="center">
		        <?
		          db_ancora(@$Lob09_codhab,"js_pesquisaob09_codhab(true);",4);
		        ?>
		        </td>
			<td align="center"> 
			<?
			  db_input('ob09_codhab',10,$Iob09_codhab,true,'text',4," onchange='js_pesquisaob09_codhab(false);'")
			?>
                        </td>
	              </tr>
		      <tr align="center"> 
                        <td colspan="2"><input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()"></td>
                     </tr>
                  </table>
			   </td>
             </tr>
           </table>
		   </form>
		   </td>
        </tr>
      </table></td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisaob09_codhab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?funcao_js=parent.js_mostratermohabite1|ob09_codhab','Pesquisa',true);
  }else{
    if(document.form1.ob09_codhab.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?pesquisa_chave='+document.form1.ob09_codhab.value+'&funcao_js=parent.js_mostratermohabite','Pesquisa',false);
    }
  }
}
function js_mostratermohabite(chave,erro){
  if(erro==true){ 
    document.form1.ob09_codhab.focus(); 
    document.form1.ob09_codhab.value = ''; 
  }
}
function js_mostratermohabite1(chave1){
  document.form1.ob09_codhab.value = chave1;
  db_iframe_obrashabite.hide();
}
</script>