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
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>">
       <?
       db_ancora(@$Lcoddepto,"js_pesquisacoddepto(true);",$db_opcao);
       ?>
    </td>
    <td colspan="3">
       <?
db_input('coddepto',10,$Icoddepto,true,'text',$db_opcao," onchange='js_pesquisacoddepto(false);'")
       ?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      <center>
        <input name="<?=($db_opcao==1?"excluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Excluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onClick="return confirm('Tem certeza?')" >
      </center>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisacoddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.coddepto.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_mostradepart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostradepart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.coddepto.focus();
    document.form1.coddepto.value = '';
  }
}
function js_mostradepart1(chave1,chave2){
  document.form1.coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_depart.hide();
}
</script>