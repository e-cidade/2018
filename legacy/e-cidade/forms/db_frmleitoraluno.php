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

//MODULO: biblioteca
$clleitoraluno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi10_codigo");
$clrotulo->label("ed47_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbi11_leitor?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lbi11_leitor,"js_pesquisabi11_leitor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bi11_leitor',10,$Ibi11_leitor,true,'text',$db_opcao," onchange='js_pesquisabi11_leitor(false);'")
?>
       <?
db_input('bi10_codigo',10,$Ibi10_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi11_aluno?>">
       <?
       db_ancora(@$Lbi11_aluno,"js_pesquisabi11_aluno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bi11_aluno',10,$Ibi11_aluno,true,'text',$db_opcao," onchange='js_pesquisabi11_aluno(false);'")
?>
       <?
db_input('ed47_i_codigo',10,$Ied47_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisabi11_leitor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leitor','func_leitor.php?funcao_js=parent.js_mostraleitor1|bi10_codigo|bi10_codigo','Pesquisa',true);
  }else{
     if(document.form1.bi11_leitor.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_leitor','func_leitor.php?pesquisa_chave='+document.form1.bi11_leitor.value+'&funcao_js=parent.js_mostraleitor','Pesquisa',false);
     }else{
       document.form1.bi10_codigo.value = ''; 
     }
  }
}
function js_mostraleitor(chave,erro){
  document.form1.bi10_codigo.value = chave; 
  if(erro==true){ 
    document.form1.bi11_leitor.focus(); 
    document.form1.bi11_leitor.value = ''; 
  }
}
function js_mostraleitor1(chave1,chave2){
  document.form1.bi11_leitor.value = chave1;
  document.form1.bi10_codigo.value = chave2;
  db_iframe_leitor.hide();
}
function js_pesquisabi11_aluno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.bi11_aluno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?pesquisa_chave='+document.form1.bi11_aluno.value+'&funcao_js=parent.js_mostraaluno','Pesquisa',false);
     }else{
       document.form1.ed47_i_codigo.value = ''; 
     }
  }
}
function js_mostraaluno(chave,erro){
  document.form1.ed47_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.bi11_aluno.focus(); 
    document.form1.bi11_aluno.value = ''; 
  }
}
function js_mostraaluno1(chave1,chave2){
  document.form1.bi11_aluno.value = chave1;
  document.form1.ed47_i_codigo.value = chave2;
  db_iframe_aluno.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_leitoraluno','func_leitoraluno.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_leitoraluno.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>