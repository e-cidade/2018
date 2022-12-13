<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: orcamento
$clorcparamrel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o112_descricao");
      if($db_opcao==1){
 	   $db_action="orc1_orcparamrel004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="orc1_orcparamrel005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="orc1_orcparamrel006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
	<fieldset style="width:630px">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To42_codparrel?>">
       <?=@$Lo42_codparrel?>
    </td>
    <td> 
		<?
			db_input('o42_codparrel',8,$Io42_codparrel,true,'text',3,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To42_orcparamrelgrupo?>">
       <?
       db_ancora(@$Lo42_orcparamrelgrupo,"js_pesquisao42_orcparamrelgrupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
			db_input('o42_orcparamrelgrupo',10,$Io42_orcparamrelgrupo,true,'text',$db_opcao," onchange='js_pesquisao42_orcparamrelgrupo(false);'");
			db_input('o112_descricao',50,$Io112_descricao,true,'text',3,'');
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To42_descrrel?>">
       <?=@$Lo42_descrrel?>
    </td>
    <td> 
		<?
			db_input('o42_descrrel',64,$Io42_descrrel,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To42_notapadrao?>">
       <?=@$Lo42_notapadrao?>
    </td>
    <td> 
    <?
      db_textarea('o42_notapadrao',10,64,$Io42_descrrel,true,'text',$db_opcao,"");
    ?>
    </td>
  </tr>
  </table>
  </center>
	</fieldset>

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

  <?php if ($db_opcao != 1) : ?>
    <input type="button" value="Exportar" onClick="js_exportar()" />
  <?php endif; ?>

  <input type="button" id="inportarRelatorio" value="Importar" onClick="js_importar()" />

</form>

<script type="text/javascript">

require_once('scripts/prototype.js');
require_once('scripts/strings.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/DBFileUpload.widget.js');
require_once('scripts/widgets/DBDownload.widget.js');

var sRPC = 'con4_relatorioslegais.RPC.php';
var oFileUpload = new DBFileUpload({callBack : js_retornoEnvioArquivo});

<?php if ($db_opcao != 1) : ?>

  $('inportarRelatorio').disabled = true;

  if (!empty($('o42_codparrel').value)) {
    $('inportarRelatorio').disabled = false;
  }

<?php endif; ?>

function js_exportar() {

  js_divCarregando('Aguarde, exportando arquivo...', 'msgBox');

  var oParametros = {exec : 'exportarRelatorio', iCodigoRelatorio : $('o42_codparrel').value};

  var oAjax = new Ajax.Request(sRPC, 
    {
      method: 'post', parameters:'json='+Object.toJSON(oParametros),
      onComplete: function(oAjax) {
        
        js_removeObj('msgBox');
        var oRetorno = eval("("+oAjax.responseText+")");
        var sMensagem = oRetorno.message.urlDecode();

        if (oRetorno.status > 1) {
          return alert(sMensagem);
        }

        alert(sMensagem);

        var oDownload = new DBDownload();
        oDownload.addFile(oRetorno.sCaminho.urlDecode(), oRetorno.sCaminho.urlDecode());
        oDownload.show(); 
      }
    }
  );

}

function js_importarArquivo() {

  js_divCarregando('Aguarde, importando arquivo...', 'msgBox');

  var oParametros = {
    exec : 'importarRelatorio', iCodigoRelatorio : $('o42_codparrel').value, sCaminhoArquivo : oFileUpload.filePath
  };

  var oAjax = new Ajax.Request(sRPC, 
    {
      method: 'post', parameters:'json='+Object.toJSON(oParametros),
      onComplete: function(oAjax) {
        
        js_removeObj('msgBox');
        var oRetorno = eval("("+oAjax.responseText+")");
        var sMensagem = oRetorno.message.urlDecode();

        if (oRetorno.status > 1) {
          return alert(sMensagem);
        }

        alert(sMensagem);
        document.location.href = 'orc1_orcparamrel005.php?chavepesquisa=' + oRetorno.iCodigoRelatorio;
      }
    }
  );

}

function js_retornoEnvioArquivo(oRetorno) {

  if (oRetorno.error) {

    alert(oRetorno.error);
    $('importarArquivo').disabled = true;
    return false;
  }

  $('importarArquivo').disabled = false;
}

function js_importar() {

  if (typeof(windowExerc) != 'undefined') {
    windowExerc.destroy();
  }        

  var sConteudoWindowAux = '<div id="importarRelatorio">';
  sConteudoWindowAux    += '  <div id="msgImportarRelatorio" style="overflow:hidden;"></div>';
  sConteudoWindowAux    += '  <fieldset>';
  sConteudoWindowAux    += '    <legend>Importar Arquivo </legend>';
  sConteudoWindowAux    += '    <div id="containerArquivo"></div>';
  sConteudoWindowAux    += '  </fieldset>';
  sConteudoWindowAux    += '  <center>';
  sConteudoWindowAux    += '    <br /><input type="button" disabled id="importarArquivo" value="Importar" onClick="js_importarArquivo();" />';
  sConteudoWindowAux    += '  </center>';
  sConteudoWindowAux    += '</div>';
 
  windowExerc  = new windowAux('importarRelatorio', 'Importação dos dados do relatório legal', 590, 450);
  windowExerc.setContent(sConteudoWindowAux);
  windowExerc.show();

  var sTitulo = "Importação dos dados do relatório legal";
  oMessageBoard = new DBMessageBoard('messageBoardRelatorio', sTitulo, "", $('msgImportarRelatorio'));
  oMessageBoard.show();  

  if (!empty(oFileUpload.filePath)) {
    $('importarArquivo').disabled = false;
  }

  oFileUpload.show($('containerArquivo')); 
} 

function js_pesquisao42_orcparamrelgrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamrel','db_iframe_orcparamrelgrupo','func_orcparamrelgrupo.php?funcao_js=parent.js_mostraorcparamrelgrupo1|o112_sequencial|o112_descricao','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o42_orcparamrelgrupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamrel','db_iframe_orcparamrelgrupo','func_orcparamrelgrupo.php?pesquisa_chave='+document.form1.o42_orcparamrelgrupo.value+'&funcao_js=parent.js_mostraorcparamrelgrupo','Pesquisa',false,'0','1');
     }else{
       document.form1.o112_descricao.value = ''; 
     }
  }
}
function js_mostraorcparamrelgrupo(chave,erro){
  document.form1.o112_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o42_orcparamrelgrupo.focus(); 
    document.form1.o42_orcparamrelgrupo.value = ''; 
  }
}
function js_mostraorcparamrelgrupo1(chave1,chave2){
  document.form1.o42_orcparamrelgrupo.value = chave1;
  document.form1.o112_descricao.value = chave2;
  db_iframe_orcparamrelgrupo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcparamrel','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_preenchepesquisa|o42_codparrel','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_orcparamrel.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
