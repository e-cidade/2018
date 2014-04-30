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

//MODULO: contabilidade
$clreconhecimentocontabiltipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c53_descr");
?>
<form name="form1" method="post" action="">
<center>

<fieldset style="margin-top: 30px; width: 700px;">
<legend id='lgdTipoReconhecimentoContabil'>Tipos de Reconhecimento Contábil</legend>
<table border="0" align = 'left'>
  <tr>
    <td nowrap title="<?=@$Tc111_sequencial?>">
       <strong><label id='lblsequencial'>Sequencial:</label></strong>
    </td>
    <td> 
			<?
			db_input('c111_sequencial',10,$Ic111_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc111_descricao?>">
    <strong>
    <label id='lbldescricao'>
       Descrição:
    </label>
    </strong>
    </td>
    <td> 
			<?
			db_input('c111_descricao',54,$Ic111_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc111_conhistdoc?>">
      <strong><label id='lbldocumentoLancamento'>
       <?
       db_ancora("Documento de Lançamento:","js_pesquisac111_conhistdoc(true);",$db_opcao);
       ?>
      </label>
      </strong>
    </td>
    <td> 
				<?
				db_input('c111_conhistdoc',10,$Ic111_conhistdoc,true,'text',$db_opcao," onchange='js_pesquisac111_conhistdoc(false);'");
        db_input('c53_descr',40,$Ic53_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc111_conhistdocestorno?>">
    <strong><strong>
     <label id='lblDocumentoEstorno'>
       <?//=@$Lc111_conhistdocestorno
         db_ancora("Documento de Estorno","js_pesquisac111_conhistdocEstorno(true);",$db_opcao);
       ?>
     </label>
    </strong>
    </strong>
    </td>
    <td> 
			<?
			db_input('c111_conhistdocestorno',10,$Ic111_conhistdocestorno,true,'text',$db_opcao,"onchange='js_pesquisac111_conhistdocEstorno(false);'");
			db_input('c53_descrestorno',40,$Ic53_descr,true,'text',3,'');
			?>
    </td>
  </tr>
  </table>
</fieldset>
</center>
  
<div style="margin-top: 10px;">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=(empty($sLabelBotao)?"Incluir":$sLabelBotao)?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</div>  
</form>

<script>
function js_pesquisac111_conhistdocEstorno(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdocEstorno','func_conhistdoc.php?lTipoReconhecimentoContabil=true&funcao_js=parent.js_mostraconhistdocEstorno1|c53_coddoc|c53_descr','Pesquisa',true);
	  }else{
	     if(document.form1.c111_conhistdoc.value != ''){ 
	        js_OpenJanelaIframe('top.corpo','db_iframe_conhistdocEstorno','func_conhistdoc.php?lTipoReconhecimentoContabil=true&pesquisa_chave='+document.form1.c111_conhistdocestorno.value+'&funcao_js=parent.js_mostraconhistdocEstorno','Pesquisa',false);
	     }else{
	       document.form1.c53_descrestorno.value = ''; 
	     }
	  }
	}
	function js_mostraconhistdocEstorno(chave,erro){
	  document.form1.c53_descrestorno.value = chave; 
	  if(erro==true){ 
	    document.form1.c111_conhistdocestorno.focus(); 
	    document.form1.c111_conhistdocestorno.value = ''; 
	  }
	}
	function js_mostraconhistdocEstorno1(chave1,chave2){

	  document.form1.c111_conhistdocestorno.value = chave1;
	  document.form1.c53_descrestorno.value = chave2;
	  db_iframe_conhistdocEstorno.hide();
	}



			
function js_pesquisac111_conhistdoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?lTipoReconhecimentoContabil=true&funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true);
  }else{
     if(document.form1.c111_conhistdoc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?lTipoReconhecimentoContabil=true&pesquisa_chave='+document.form1.c111_conhistdoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false);
     }else{
       document.form1.c53_descr.value = ''; 
     }
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.c111_conhistdoc.focus(); 
    document.form1.c111_conhistdoc.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.c111_conhistdoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}




function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_reconhecimentocontabiltipo','func_reconhecimentocontabiltipo.php?funcao_js=parent.js_preenchepesquisa|c111_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
	
  db_iframe_reconhecimentocontabiltipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>