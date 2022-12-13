<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: educação
$clturmaturnoadicional->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed15_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted246_i_codigo?>">
       <?=@$Led246_i_codigo?>
    </td>
    <td> 
<?
db_input('ed246_i_codigo',10,$Ied246_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted246_i_turma?>">
       <?
       db_ancora(@$Led246_i_turma,"js_pesquisaed246_i_turma(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed246_i_turma',5,$Ied246_i_turma,true,'text',$db_opcao," onchange='js_pesquisaed246_i_turma(false);'")
?>
       <?
db_input('ed57_i_codigo',20,$Ied57_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted246_i_turno?>">
       <?
       db_ancora(@$Led246_i_turno,"js_pesquisaed246_i_turno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed246_i_turno',10,$Ied246_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed246_i_turno(false);'")
?>
       <?
db_input('ed15_i_codigo',20,$Ied15_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed246_i_turma(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_turma','func_turma.php?funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed246_i_turma.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_turma','func_turma.php?pesquisa_chave='+document.form1.ed246_i_turma.value+'&funcao_js=parent.js_mostraturma','Pesquisa',false);
     }else{
       document.form1.ed57_i_codigo.value = ''; 
     }
  }
}
function js_mostraturma(chave,erro){
  document.form1.ed57_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed246_i_turma.focus(); 
    document.form1.ed246_i_turma.value = ''; 
  }
}
function js_mostraturma1(chave1,chave2){
  document.form1.ed246_i_turma.value = chave1;
  document.form1.ed57_i_codigo.value = chave2;
  db_iframe_turma.hide();
}
function js_pesquisaed246_i_turno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_turno','func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed246_i_turno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_turno','func_turno.php?pesquisa_chave='+document.form1.ed246_i_turno.value+'&funcao_js=parent.js_mostraturno','Pesquisa',false);
     }else{
       document.form1.ed15_i_codigo.value = ''; 
     }
  }
}
function js_mostraturno(chave,erro){
  document.form1.ed15_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed246_i_turno.focus(); 
    document.form1.ed246_i_turno.value = ''; 
  }
}
function js_mostraturno1(chave1,chave2){
  document.form1.ed246_i_turno.value = chave1;
  document.form1.ed15_i_codigo.value = chave2;
  db_iframe_turno.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_turmaturno','func_turmaturno.php?funcao_js=parent.js_preenchepesquisa|ed246_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_turmaturno.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>