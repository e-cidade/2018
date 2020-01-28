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
$clseguradoras->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Cadastros - Seguradoras</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tt80_segura?>">
          <?=@$Lt80_segura?>
        </td>
        <td> 
          <?
            db_input('t80_segura',8,$It80_segura,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt80_numcgm?>">
          <?
            db_ancora(@$Lt80_numcgm,"js_pesquisat80_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t80_numcgm',8,$It80_numcgm,true,'text',$db_opcao," onchange='js_pesquisat80_numcgm(false);'")
          ?>
          <?
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt80_contato?>">
          <?=@$Lt80_contato?>
        </td>
        <td> 
          <?
            db_input('t80_contato',51,$It80_contato,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat80_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.t80_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.t80_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.t80_numcgm.focus(); 
    document.form1.t80_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.t80_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_seguradoras','func_seguradoras.php?funcao_js=parent.js_preenchepesquisa|t80_segura','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_seguradoras.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t80_segura").addClassName("field-size2");
$("t80_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("t80_contato").addClassName("field-size9");

</script>