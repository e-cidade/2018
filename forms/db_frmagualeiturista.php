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

//MODULO: Agua
$clagualeiturista->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="" onsubmit="<?=$fvalidaSenha?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx16_dtini?>">
       <?=@$Lx16_dtini?>
    </td>
    <td> 
      <?
        db_inputdata('x16_dtini',@$x16_dtini_dia,@$x16_dtini_mes,@$x16_dtini_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx16_dtfim?>">
       <?=@$Lx16_dtfim?>
    </td>
    <td> 
      <?
        db_inputdata('x16_dtfim',@$x16_dtfim_dia,@$x16_dtfim_mes,@$x16_dtfim_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx16_numcgm?>">
       <?
         db_ancora(@$Lx16_numcgm,"js_pesquisax16_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('x16_numcgm',10,$Ix16_numcgm,true,'text',$db_opcao," onchange='js_pesquisax16_numcgm(false);'")
      ?>
      <?
        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  
  <?
  //Constroi input se usuario não for administrador do sistema e somente na tela de alteração. 
  //Variável $mostrainputalterar setada em agu1_agualeiturista002.php
  if((db_getsession("DB_administrador") != 1) and ($mostrainputalterar == true)) { 
  ?>
  <tr>
    <td nowrap title="Senha Atual">
       <b>Senha Atual</b>
    </td>
    <td> 
      <?
        db_input('x16_senha_atual',50,'',true,'password',$db_opcao," onchange=\"return validaSenhaAtual()\"; ")
      ?>
    </td>
  </tr>
  <?
  }
  ?>
 
  <tr>
    <td nowrap title="<?=@$Tx16_senha?>">
       <b>Nova Senha:</b>
    </td>
    <td> 
      <?
        db_input('x16_senha',50,'',true,'password',$db_opcao,"")
      ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="Confirma senha de acesso ao sistema de coletores">
       <b>Confirma Senha:</b>
    </td>
    <td> 
      <?
        db_input('x16_senha_confirma',50,"",true,'password',$db_opcao,"")
      ?>
    </td>
    
  </tr>
  
  </table>
  </center>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script><!--
//ajax
function validaSenhaAtual() {
	var sSenhaAtual = $F('x16_senha_atual');
	var sCGM				=	$F('x16_numcgm');
	var sAction     = 'pesquisaSenhaHash';
	var url 				=	'agu4_senhaleiturista.RPC.php';

	var oAjax = new Ajax.Request (
										url, { 	method		 : 'POST',
													 	parameters : 's='+sSenhaAtual+'&cgm='+sCGM+'&sAction='+sAction,
													 	onComplete : js_retornoPesquisa
												 }
									);
}	
	
function js_retornoPesquisa(oAjax) {
	var oRetorno = eval("("+oAjax.responseText+")");
 
	var vCount   = '';

	for (var i = 0;i < oRetorno.length; i++) {
		with (oRetorno[i]) {
			vCount    = count.urlDecode();
		}   
	}
	if(vCount != 1){
		alert('Erro: Senha Atual Inválida!');
		document.form1.x16_senha_atual.style.backgroundColor='#99A9AE';
		document.form1.x16_senha_atual.value = '';
		document.form1.x16_senha_atual.focus();
		document.form1.alterar.disabled = true;
		return false;
	}else {
		document.form1.alterar.disabled = false;
	}
}

function validaSenha() {
	var x16_senha 				 = document.form1.x16_senha;
	var x16_senha_confirma = document.form1.x16_senha_confirma;

	if(x16_senha.value != x16_senha_confirma.value) {
		alert('Erro: Confirmação de senha inválida');
		return false;
	}
	
}

function js_pesquisax16_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.x16_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.x16_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.x16_numcgm.focus(); 
    document.form1.x16_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.x16_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_agualeiturista','func_agualeiturista.php?funcao_js=parent.js_preenchepesquisa|x16_numcgm','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_agualeiturista.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
--></script>