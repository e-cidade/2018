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

//MODULO: divida
$clprocedtipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v29_descricao");
?>
<form name="form1" method="post" action="">
<center>
<fieldset style='margin-top:10px;'>
<legend><b>Cadastro de Tipos de Procedências</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv28_sequencial?>">
       <?=@$Lv28_sequencial?>
    </td>
    <td> 
<?
db_input('v28_sequencial',10,$Iv28_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv28_descricao?>">
       <?=@$Lv28_descricao?>
    </td>
    <td> 
<?
db_input('v28_descricao',50,$Iv28_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv28_grupo?>">
       <?=@$Lv28_grupo?>
    </td>
    <td> 
       <?
       include("classes/db_procedtipogrupo_classe.php");
       $clprocedtipogrupo = new cl_procedtipogrupo;
       $result = $clprocedtipogrupo->sql_record($clprocedtipogrupo->sql_query("","*","", ""));
       db_selectrecord("v28_grupo",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  </table>
  </center>
  </fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav28_grupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedtipogrupo','func_procedtipogrupo.php?funcao_js=parent.js_mostraprocedtipogrupo1|v29_sequencial|v29_descricao','Pesquisa',true);
  }else{
     if(document.form1.v28_grupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procedtipogrupo','func_procedtipogrupo.php?pesquisa_chave='+document.form1.v28_grupo.value+'&funcao_js=parent.js_mostraprocedtipogrupo','Pesquisa',false);
     }else{
       document.form1.v29_descricao.value = ''; 
     }
  }
}
function js_mostraprocedtipogrupo(chave,erro){
  document.form1.v29_descricao.value = chave; 
  if(erro==true){ 
    document.form1.v28_grupo.focus(); 
    document.form1.v28_grupo.value = ''; 
  }
}
function js_mostraprocedtipogrupo1(chave1,chave2){
  document.form1.v28_grupo.value = chave1;
  document.form1.v29_descricao.value = chave2;
  db_iframe_procedtipogrupo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procedtipo','func_procedtipo.php?funcao_js=parent.js_preenchepesquisa|v28_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procedtipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>