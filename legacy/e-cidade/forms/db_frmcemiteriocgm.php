<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$clcemiteriocgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm14_i_codigo");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Cemitério Urbano</legend>

      <table>
        <tr>
          <td nowrap title="<?=@$Tcm14_i_codigo?>">
             <?=@$Lcm14_i_codigo?>
          </td>
          <td>
            <?php
              db_input('cm15_i_cemiterio',10,$Icm15_i_cemiterio,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm15_i_cgm?>">
             <?php
              db_ancora(@$Lcm15_i_cgm,"js_pesquisacm15_i_cgm(true);",$db_opcao);
             ?>
          </td>
          <td>
            <?php
              db_input('cm15_i_cgm',10,$Icm15_i_cgm,true,'text',$db_opcao," onchange='js_pesquisacm15_i_cgm(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
      </table>

  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <? if($db_opcao == 1){ echo "disabled"; } ?> />
</form>
<script type="text/javascript">
function js_pesquisacm15_i_cemiterio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cemiterio','func_cemiterio.php?funcao_js=parent.js_mostracemiterio1|cm14_i_codigo|cm14_i_codigo','Pesquisa',true);
  }else{

     if(document.form1.cm15_i_cemiterio.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cemiterio','func_cemiterio.php?pesquisa_chave='+document.form1.cm15_i_cemiterio.value+'&funcao_js=parent.js_mostracemiterio','Pesquisa',false);
     }else{
       document.form1.cm14_i_codigo.value = '';
     }
  }
}
function js_mostracemiterio(erro, chave){

  document.form1.cm14_i_codigo.value = chave;
  if(erro==true){
    document.form1.cm15_i_cemiterio.focus();
    document.form1.cm15_i_cemiterio.value = '';
  }
}
function js_mostracemiterio1(chave1,chave2){
  document.form1.cm15_i_cemiterio.value = chave1;
  document.form1.cm14_i_codigo.value = chave2;
  db_iframe_cemiterio.hide();
}
function js_pesquisacm15_i_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm15_i_cgm.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.cm15_i_cgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro, chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.cm15_i_cgm.focus();
    document.form1.cm15_i_cgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.cm15_i_cgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cemiteriocgm','func_cemiteriocgm.php?funcao_js=parent.js_preenchepesquisa|cm15_i_cemiterio','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cemiteriocgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tp=$tp&chavepesquisa='+chave";
  }
  ?>
}
</script>