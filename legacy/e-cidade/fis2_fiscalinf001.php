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
include("classes/db_fiscal_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
//$aux = new cl_arquivo_auxiliar;
$cldbfiscal = new cl_fiscal;
$cldbfiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y30_codnoti");
$clrotulo->label("");
$clrotulo->label("");
$clrotulo->label("");
$clrotulo->label("");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <table    align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td ></td>
         <td ></td>
      </tr>
       <tr> 
       <td nowrap align="right" title="<?=@$y30_codnoti?>"><?db_ancora(@$Ly30_codnoti,"js_codfiscal(true);",1);?></td>
            <td><?db_input('y30_codnoti',6,$Iy30_codnoti,true,'text',1," onchange='js_codfiscal(false);'");
                  db_input('y30_nome',35,$Iy30_nome,true,'text',3,'');?>
            </td>
      <tr>
         <td> 
	 </td>
	 <td>
	 </td>
      </tr>
      <tr>
        <td align='center'  colspan=2 >
	</td>
      </tr>
      <tr>
      </tr>
      <tr>
      </tr>
      <tr align="center" >
       <td colspan=2>
	</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input name="consultar" type="submit" value="Processar" onclick="js_mandadados();" >
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

function js_mandadados(){
  if(document.form1.y30_codnoti.value == ""){
    alert("Preencha o código do fiscal");
    document.form1.y30_codnoti.focus();  
  }else{
   jan = window.open('fis2_fiscalinf002.php?codfiscal='+document.form1.y30_codnoti.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
  } 
}
function js_mostracodfiscal1(chave1,chave2){
    document.form1.y30_codnoti.value = chave1;
    document.form1.y30_nome.value = chave2;
    db_iframe_fiscal.hide();
}
function js_mostracodfiscal(chave,erro){
    document.form1.y30_nome.value = chave; 
    if(erro==true){ 
      document.form1.y30_codnoti.focus(); 
      document.form1.y30_codnoti.value = ''; 
    }
}
function js_codfiscal(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscalalt.php?funcao_js=parent.js_mostracodfiscal1|y30_codnoti|z01_nome','Pesquisa',true);
    }else{
      y30_codnoti = document.form1.y30_codnoti.value;
      if(y30_codnoti!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscalalt.php?pesquisa_chave='+y30_codnoti+'&funcao_js=parent.js_mostracodfiscal','Pesquisa',false);
      }else{ 	
	document.form1.y30_nome.value='';
      } 	
    }
} 
</script>