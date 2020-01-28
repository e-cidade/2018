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

//MODULO: projetos
$clobrascaractarqsisobra->rotulo->label();
?>
<form name="form1" method="post" action="">
	<center>
		<table border="0">
			<tr>
				<td nowrap title="<?=@$Tob23_sequencial?>">
				</td>
				<td> 
					<?
						db_input('ob23_sequencial',10,$Iob23_sequencial,true,'hidden',3,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob23_caractorigem?>">
					<?
						db_ancora(@$Lob23_caractorigem,"js_consultaorigem(true);",$db_opcao);
					?>
				</td>
				<td> 
					<?
						db_input('ob23_caractorigem',10,$Iob23_caractorigem,true,'text',$db_opcao,"onChange=js_consultaorigem(false);");
						db_input('descrOrigem',40,$Iob23_caractorigem,true,'text',3,"");
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob23_caractdestino?>">
					<?
						db_ancora(@$Lob23_caractdestino,"js_consultadestino(true);",$db_opcao);
					?>
				</td>
				<td> 
					<?
						db_input('ob23_caractdestino',10,$Iob23_caractdestino,true,'text',$db_opcao,"onChange=js_consultadestino(false);");
						db_input('descrDestino',40,$Iob23_caractdestino,true,'text',3,"");
					?>
				</td>
			</tr>
			</table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_consultaorigem(mostra){
	
	if (mostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_caracter','func_caracter.php?funcao_js=parent.js_mostraorigem1|j31_codigo|j31_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_caracter','func_caracter.php?pesquisa_chave='+document.form1.ob23_caractorigem.value+'&funcao_js=parent.js_mostraorigem','Pesquisa',false);	
	}

}
	
function js_mostraorigem(chave1,erro) {
	if (erro) {
	 document.form1.ob23_caractorigem.value = "";
	 document.form1.descrOrigem.value 		  = "";
	} else {
	 document.form1.descrOrigem.value = chave1;
	}

}

function js_mostraorigem1(chave1,chave2) {
	 document.form1.ob23_caractorigem.value = chave1;
	 document.form1.descrOrigem.value 		  = chave2;
	 db_iframe_caracter.hide();
}

function js_consultadestino(mostra){
	
	if (mostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_caracter','func_caracter.php?funcao_js=parent.js_mostradestino1|j31_codigo|j31_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_caracter','func_caracter.php?pesquisa_chave='+document.form1.ob23_caractdestino.value+'&funcao_js=parent.js_mostradestino','Pesquisa',false);	
	}

}
	
function js_mostradestino(chave1,erro) {
	if (erro) {
	 document.form1.ob23_caractdestino.value = "";
	 document.form1.descrDestino.value 		  = "";
	} else {
	 document.form1.descrDestino.value = chave1;
	}

}

function js_mostradestino1(chave1,chave2) {
	 document.form1.ob23_caractdestino.value = chave1;
	 document.form1.descrDestino.value 		  = chave2;
	 db_iframe_caracter.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrascaractarqsisobra','func_obrascaractarqsisobra.php?funcao_js=parent.js_preenchepesquisa|ob23_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrascaractarqsisobra.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>