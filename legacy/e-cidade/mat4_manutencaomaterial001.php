<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$rotulo = new rotulocampo();
$rotulo->label("m60_codmater");
$rotulo->label("m60_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 30px;">
    <table width="600" align="center">
      <tr>
        <td>
          <fieldset>
            <legend><b>Material</b></legend>
            <form name="form1" method="post" action="">
            	<table align="center">
            	  <tr>
            			<td nowrap title="<?=@$Tm60_codmater?>" align="right">
            				<?php
            				  db_ancora(@$Lm60_codmater,"js_pesquisam60_codmater(true);",1);
            				?>
            			</td>
            			<td>
            				<?php
            				  db_input('m60_codmater',10,$Im60_codmater,true,'text',1," onchange='js_pesquisam60_codmater(false);'");
            				  db_input('m60_descr',40,$Im60_descr,true,'text',3,'');
            				?>
            		  </td>
            		</tr>
              </table>
            </form>
          </fieldset>
          <center>
            <input style="margin-top:10px;" name="btnPesquisar" id="btnPesquisar" type="button" value="Pesquisar" onclick="js_pesquisar();" >
          </center>
        </td>
      </tr>
    </table>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>

<script>
function js_pesquisar() {

  if (document.form1.m60_codmater.value=="") {

    alert('Informe um Material.');
    document.form1.m60_codmater.focus();
    return false;
  }

  var sAbrirUrl = "mat4_manutencaomaterial002.php?iCodigoMaterial="+$('m60_codmater').value + "&sDescricaoMaterial=" + $('m60_descr').value;
  js_OpenJanelaIframe('top.corpo','db_iframe_material', sAbrirUrl, "Material: "+$('m60_codmater').value+" - "+$('m60_descr').value, true);

}

function js_pesquisam60_codmater(mostra) {
  if(mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  } else {
    if(document.form1.m60_codmater.value != '') {
      js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostramatmater','Pesquisa',false);
    } else {
      document.form1.m60_descr.value = '';
    }
  }
}

function js_mostramatmater(chave,erro) {
  document.form1.m60_descr.value = chave;
  if(erro==true) {

    document.form1.m60_codmater.focus();
    document.form1.m60_codmater.value = '';
  }
}

function js_mostramatmater1(chave1,chave2) {
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
}
</script>