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

//MODULO: contabilidade
$clconcadtce->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db12_uf");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td >
       
    </td>
    <td> 
<?
db_input('c10_sequencial',10,$Ic10_sequencial,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc10_nome?>">
       <?=@$Lc10_nome?>
    </td>
    <td> 
<?
db_input('c10_nome',60,$Ic10_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc10_sigla?>">
       <?=@$Lc10_sigla?>
    </td>
    <td> 
<?
db_input('c10_sigla',10,$Ic10_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc10_db_uf?>">
       <?
       db_ancora(@$Lc10_db_uf,"js_pesquisac10_db_uf(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c10_db_uf',10,$Ic10_db_uf,true,'text',$db_opcao," onchange='js_pesquisac10_db_uf(false);'")
?>
       <?
db_input('db12_uf',10,$Idb12_uf,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac10_db_uf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_uf','func_db_uf.php?funcao_js=parent.js_mostradb_uf1|db12_codigo|db12_uf','Pesquisa',true);
  }else{
     if(document.form1.c10_db_uf.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_uf','func_db_uf.php?pesquisa_chave='+document.form1.c10_db_uf.value+'&funcao_js=parent.js_mostradb_uf','Pesquisa',false);
     }else{
       document.form1.db12_uf.value = ''; 
     }
  }
}
function js_mostradb_uf(chave,erro){
  document.form1.db12_uf.value = chave; 
  if(erro==true){ 
    document.form1.c10_db_uf.focus(); 
    document.form1.c10_db_uf.value = ''; 
  }
}
function js_mostradb_uf1(chave1,chave2){
  document.form1.c10_db_uf.value = chave1;
  document.form1.db12_uf.value = chave2;
  db_iframe_db_uf.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_concadtce','func_concadtce.php?funcao_js=parent.js_preenchepesquisa|c10_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_concadtce.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>