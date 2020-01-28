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

//MODULO: pessoal
$clselecaoponto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r44_descr");

if($db_opcao==1){
  $db_action="pes1_selecaoponto004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="pes1_selecaoponto005.php";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="pes1_selecaoponto006.php";
}
  
?>
<center>
<form name="form1" method="post" action="<?=$db_action?>">
  <fieldset>
    <legend>
      <b>Configuração da Seleção</b>
    </legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tr72_sequencial?>">
	       <?=@$Lr72_sequencial?>
	    </td>
	    <td> 
				<?
				  db_input('r72_sequencial',10,$Ir72_sequencial,true,'text',3,"")
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr72_selecao?>">
	      <?
	        db_ancora(@$Lr72_selecao,"js_pesquisar72_selecao(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
	      <?
				  db_input('r72_selecao',10,$Ir72_selecao,true,'text',$db_opcao," onchange='js_pesquisar72_selecao(false);'");
				  db_input('r44_descr',37,$Ir44_descr,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tr72_descricao?>">
	       <?=@$Lr72_descricao?>
	    </td>
	    <td> 
				<?
				  db_input('r72_descricao',50,$Ir72_descricao,true,'text',$db_opcao,"")
				?>
	    </td>
	  </tr>
	</table>
	</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
</center>
<script>

function js_pesquisar72_selecao(mostra){
  if( mostra==true ){
    js_OpenJanelaIframe('top.corpo.iframe_selecaoponto','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostraselecao1|r44_selec|r44_descr','Pesquisa',true);
  }else{
     if(document.form1.r72_selecao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_selecaoponto','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r72_selecao.value+'&funcao_js=parent.js_mostraselecao','Pesquisa',false);
     }else{
       document.form1.r44_descr.value = ''; 
     }
  }
}
function js_mostraselecao(chave,erro){
  document.form1.r44_descr.value = chave; 
  if(erro==true){ 
    document.form1.r72_selecao.focus(); 
    document.form1.r72_selecao.value = ''; 
  }
}
function js_mostraselecao1(chave1,chave2){
  document.form1.r72_selecao.value = chave1;
  document.form1.r44_descr.value = chave2;
  db_iframe_selecao.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_selecaoponto','db_iframe_selecaoponto','func_selecaoponto.php?funcao_js=parent.js_preenchepesquisa|r72_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_selecaoponto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>