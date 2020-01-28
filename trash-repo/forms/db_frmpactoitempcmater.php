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

//MODULO: orcamento
$clpactoitempcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o109_descricao");
$clrotulo->label("pc01_descrmater");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To89_sequencial?>">
       <?=@$Lo89_sequencial?>
    </td>
    <td> 
<?
db_input('o89_sequencial',10,$Io89_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To89_pactoitem?>">
       <?
       db_ancora(@$Lo89_pactoitem,"js_pesquisao89_pactoitem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o89_pactoitem',10,$Io89_pactoitem,true,'text',$db_opcao," onchange='js_pesquisao89_pactoitem(false);'")
?>
       <?
db_input('o109_descricao',50,$Io109_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To89_pcmater?>">
       <?
       db_ancora(@$Lo89_pcmater,"js_pesquisao89_pcmater(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o89_pcmater',10,$Io89_pcmater,true,'text',$db_opcao," onchange='js_pesquisao89_pcmater(false);'")
?>
       <?
db_input('pc01_descrmater',80,$Ipc01_descrmater,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao89_pactoitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pactoitem','func_pactoitem.php?funcao_js=parent.js_mostrapactoitem1|o109_sequencial|o109_descricao','Pesquisa',true);
  }else{
     if(document.form1.o89_pactoitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pactoitem','func_pactoitem.php?pesquisa_chave='+document.form1.o89_pactoitem.value+'&funcao_js=parent.js_mostrapactoitem','Pesquisa',false);
     }else{
       document.form1.o109_descricao.value = ''; 
     }
  }
}
function js_mostrapactoitem(chave,erro){
  document.form1.o109_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o89_pactoitem.focus(); 
    document.form1.o89_pactoitem.value = ''; 
  }
}
function js_mostrapactoitem1(chave1,chave2){
  document.form1.o89_pactoitem.value = chave1;
  document.form1.o109_descricao.value = chave2;
  db_iframe_pactoitem.hide();
}
function js_pesquisao89_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true);
  }else{
     if(document.form1.o89_pcmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.o89_pcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.o89_pcmater.focus(); 
    document.form1.o89_pcmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.o89_pcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pactoitempcmater','func_pactoitempcmater.php?funcao_js=parent.js_preenchepesquisa|o89_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pactoitempcmater.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>