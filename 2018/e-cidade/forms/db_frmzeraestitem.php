<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<tr>
  <br>
  <br>
  <br>
	  <td nowrap title="<?=@$Tm60_codmater?>">
	     <?
	     db_ancora(@$Lm60_codmater,"js_pesquisam60_codmater(true);",1);
	     ?>
	  </td>
	  <td colspan="3"> 
	     <?
      db_input('m60_codmater',10,$Im60_codmater,true,'text',1," onchange='js_pesquisam60_codmater(false);'")
	     ?>
	     <?
      db_input('m60_descr',40,$Im60_descr,true,'text',3,'')
	     ?>
	  </td>
	</tr> 
	  <td nowrap title="<?=@$Tm60_codmater?>">
	    <b>Informe os codigos dos materias : </b>
	  </td>
	  <td colspan="3"> 
	     <?
      db_input('codigos_materiais',53,null,true,'text',1,"");
	     ?>
	  </td>
	</tr>  
	 
</table>
</center>
<input name="<?=($db_opcao==1?"excluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" onclick='return js_confirma();'  id="db_opcao" value="<?=($db_opcao==1?"Excluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"   <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_confirma(){
 	if(confirm('Tem certeza que deseja zerar o estoque deste item?')){
     	return true;
	}else{
		return false;
	} 
}
function js_pesquisam60_codmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
     if(document.form1.m60_codmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostramatmater','Pesquisa',false);
     }else{
       document.form1.m60_descr.value = ''; 
     }
  }
}
function js_mostramatmater(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true){ 
    document.form1.m60_codmater.focus(); 
    document.form1.m60_codmater.value = ''; 
  }
}
function js_mostramatmater1(chave1,chave2){
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
}
</script>