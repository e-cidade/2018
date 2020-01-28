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

//MODULO: patrimonio
$clbensmodeloetiqueta->rotulo->label();
$clbensmodeloetiquetapadrao->rotulo->label();
?>
<form class="container" name="form1" method="post" action="" enctype="multipart/form-data" >
  <fieldset>
    <legend>Configurar Etiqueta Bens</legend>
		<table class="form-container">
		  <tr>
		    <td title="<?=@$Tt71_sequencial?>">
		      <?=@$Lt71_sequencial?>
		    </td>
		    <td> 
					<?
					  db_input('t71_sequencial',8,$It71_sequencial,true,'text',3,"")
					?>
		    </td>
		  </tr>
		  <tr> 
        <td title="modelo etiqueta">Modelo Etiqueta:</td>
        <td>
          <?
            db_input("t72_sequencial",8,$It72_sequencial,true,"text",3,"onchange='js_pesquisa_modelo(false);'"); 
            db_input("t72_descr",40,$It72_descr,true,"text",3);  
          ?>
        </td>
       </tr>
		  <tr>
		    <td title="<?=@$Tt71_descr?>">
		       <?=@$Lt71_descr?>
		    </td>
		    <td> 
					<?
					  db_input('t71_descr',51,$It71_descr,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>
		  <tr>
        <td>Arquivo:</td>
        <td>
          <?
            db_input('fileXml',40,0,true,'file',$db_opcao);
          ?>
        </td>
       </tr>
		</table>
  </fieldset>
  <input onclick="return js_incluir();"
  name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <? 
    if($db_opcao > 1){
    ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?
    }
    if($db_opcao == 1){
    ?>
      <input value='Importar' type='button' id='importar' name='importar' onclick="js_pesquisa_modelo(true);" >
    <?
    }
    if($db_opcao == 2){
    ?>
      <input value='Editar XML' type='button' id='editar' name='editar' onclick="js_janela();">
    <?
    }
  ?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiqueta',
                      'func_bensmodeloetiqueta.php?funcao_js=parent.js_preenchepesquisa|t71_sequencial',
                      'Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bensmodeloetiqueta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisa_modelo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiquetapadrao',
                        'func_bensmodeloetiquetapadrao.php?funcao_js=parent.js_mostramodelo1|t72_sequencial|t72_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.t72_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiquetapadrao',
                            'func_bensmodeloetiquetapadrao.php?'+
                            +'pesquisa_chave='+document.form1.t72_sequencial.value+'&funcao_js=parent.js_mostramodelo',
                            'Pesquisa',false);
     }else{
       document.form1.t72_sequencial.value = ''; 
     }
  }
}
function js_mostramodelo(chave,erro){
  document.form1.t72_descr.value = chave; 
  if(erro==true){ 
    document.form1.t72_sequencial.focus(); 
    document.form1.t72_sequencial.value = ''; 
  }
}
function js_mostramodelo1(chave1,chave2){
  document.form1.t72_sequencial.value = chave1;
  document.form1.t72_descr.value = chave2;
  db_iframe_bensmodeloetiquetapadrao.hide();
}
function js_incluir(){
  if($('db_opcao').value == "Incluir"){
	  if($('fileXml').value != '' && $('t71_descr').value == ''){
	    alert(_M("patrimonial.patrimonio.db_frmbensmodeloetiqueta.informe_descricao"));
	    $('t71_descr').focus();
	    return false;
	  }else if ($('t72_sequencial').value == '' && $('fileXml').value == ''){
	    alert(_M("patrimonial.patrimonio.db_frmbensmodeloetiqueta.informe_arquivo"));
	    return false;
	  }
  }else if($('db_opcao').value == "Alterar"){
    if($('t71_descr').value == ""){
      alert(_M("patrimonial.patrimonio.db_frmbensmodeloetiqueta.informe_descricao"));
      $('t71_descr').focus();
      return false;
    }
    js_salvaEdita();
    return false;
  }  
}
</script>
<script>

$("t71_sequencial").addClassName("field-size2");
$("t72_sequencial").addClassName("field-size2");
$("t72_descr").addClassName("field-size7");
$("t71_descr").addClassName("field-size9");

</script>