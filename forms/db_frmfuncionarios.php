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

//MODULO: educação
$clfuncionarios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("ed02_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted19_i_escola?>">
       <?
       db_ancora(@$Led19_i_escola,"js_pesquisaed19_i_escola(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?
      db_input('ed19_i_escola',5,$Ied19_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed19_i_escola(false);'")
     ?>
     <?
      db_input('ed02_i_codigo',10,$Ied02_i_codigo,true,'text',3,'')
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted19_i_codigo?>">
       <?
       db_ancora(@$Led19_i_codigo,"js_pesquisaed19_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
    <?
     db_input('ed19_i_codigo',5,$Ied19_i_codigo,true,'text',$db_opcao," onchange='js_pesquisaed19_i_codigo(false);'")
    ?>
    <?
     db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
    ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed19_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostracgm1|r01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed19_i_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.ed19_i_codigo.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed19_i_codigo.focus(); 
    document.form1.ed19_i_codigo.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ed19_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaed19_i_escola(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?funcao_js=parent.js_mostraescolas1|ed02_i_codigo|ed02_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed19_i_escola.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?pesquisa_chave='+document.form1.ed19_i_escola.value+'&funcao_js=parent.js_mostraescolas','Pesquisa',false);
     }else{
       document.form1.ed02_i_codigo.value = ''; 
     }
  }
}
function js_mostraescolas(chave,erro){
  document.form1.ed02_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed19_i_escola.focus(); 
    document.form1.ed19_i_escola.value = ''; 
  }
}
function js_mostraescolas1(chave1,chave2){
  document.form1.ed19_i_escola.value = chave1;
  document.form1.ed02_i_codigo.value = chave2;
  db_iframe_escolas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_funcionarios','func_funcionarios.php?funcao_js=parent.js_preenchepesquisa|ed19_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_funcionarios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>