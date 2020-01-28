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

//MODULO: patrim
$cldepartdiv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Cadastros - Cadastro de Divisões</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tt30_codigo?>">
          <?=@$Lt30_codigo?>
        </td>
        <td> 
          <?
            db_input('t30_codigo',8,$It30_codigo,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt30_descr?>">
          <?=@$Lt30_descr?>
        </td>
        <td> 
          <?
            db_input('t30_descr',40,$It30_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt30_depto?>">
          <?
            db_ancora(@$Lt30_depto,"js_pesquisat30_depto(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t30_depto',5,$It30_depto,true,'text',$db_opcao," onchange='js_pesquisat30_depto(false);'")
          ?>
          <?
            db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt30_ativo?>">
          <?=@$Lt30_ativo?>
        </td>
        <td> 
          <?
            $x = array('t'=>'Sim','f'=>'Não');
            db_select('t30_ativo',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt30_numcgm?>">
          <?
            db_ancora(@$Lt30_numcgm,"js_pesquisat30_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t30_numcgm',10,$It30_numcgm,true,'text',$db_opcao," onchange='js_pesquisat30_numcgm(false);'")
          ?>
          <?
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat30_depto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t30_depto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.t30_depto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.t30_depto.focus(); 
    document.form1.t30_depto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.t30_depto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisat30_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.t30_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.t30_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.t30_numcgm.focus(); 
    document.form1.t30_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.t30_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_departdiv','func_departdiv.php?funcao_js=parent.js_preenchepesquisa|t30_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_departdiv.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t30_codigo").addClassName("field-size2");
$("t30_descr").addClassName("field-size9");
$("t30_depto").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("t30_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("t30_ativo").setAttribute("rel","ignore-css");
$("t30_ativo").addClassName("field-size2");
<?php if($db_opcao != 1){ ?> 
  $("t30_ativo_select_descr").addClassName("field-size2");
<?php }?>
</script>