<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_benstransfconf_classe.php");
include("classes/db_bens_classe.php");
$clbens = new cl_bens;
$clbenstransfconf = new cl_benstransfconf;
$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clbenstransfconf->rotulo->label();
$clrotulo->label("nome");

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_reemite() {
  if ( document.form1.t96_codtran.value == "" ) {
	  
    document.form1.t96_codtran.style.backgroundColor='#99A9AE';
    document.form1.t96_codtran.focus();
    alert(_M('patrimonial.patrimonio.pat2_relbenstransf001.informe_codigo'));
    
  }else{
	  
    jan = window.open('pat2_relbenstransf002.php?t96_codtran='+document.form1.t96_codtran.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.t96_codtran.style.backgroundColor='';	
    jan.moveTo(0,0);
  }
}
</script><link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="document.form1.t96_codtran.focus();">



<form class="container" name="form1" method="post">
  <fieldset>
    <legend>Reemissão Documento de Transferência</legend>
    <table class="form-contianer">
      <tr>
        <td nowrap title="<?=@$Tt96_codtran?>">
          <?
            db_ancora(@$Lt96_codtran,"js_pesquisat96_codtran(true);",1);
          ?>
        </td>
        <td>
          <?
            db_input('t96_codtran',8,$It96_codtran,true,'text',1," onchange='js_pesquisat96_codtran(false);'");
            db_input('nome',40,$Inome,true,'text',3,'','nome_transf');
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisa" type="button" onclick='js_reemite();'  value="Gerar Relatório">
</form>

<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

</body>
</html>

<script>
function js_pesquisat96_codtran(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_benstransf','func_benstransf001.php?funcao_js=parent.js_mostrabenstransf1|t93_codtran|nome&rel=true','Pesquisa',true);
  }else{
    if(document.form1.t96_codtran.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_benstransf','func_benstransf001.php?pesquisa_chave='+document.form1.t96_codtran.value+'&funcao_js=parent.js_mostrabenstransf&rel=true','Pesquisa',false);
    }else{
      document.form1.nome_transf.value = ''; 
    }
  }
}
function js_mostrabenstransf(chave,erro){
  document.form1.nome_transf.value = chave; 
  if(erro==true){
    document.form1.t96_codtran.value = ''; 
    document.form1.t96_codtran.focus(); 
  }
}
function js_mostrabenstransf1(chave1,chave2){
  document.form1.t96_codtran.value = chave1;
  document.form1.nome_transf.value = chave2;
  db_iframe_benstransf.hide();
}
</script>
<script>

$("t96_codtran").addClassName("field-size2");
$("nome_transf").addClassName("field-size7");

</script>