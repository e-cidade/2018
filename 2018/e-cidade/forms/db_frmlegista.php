<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

$cllegista->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Legista</legend>
    <table border="0">
      <tr>
        <td nowrap title="<?php echo $Tcm32_i_codigo?>">
           <?php echo $Lcm32_i_codigo?>
        </td>
        <td>
          <?php
          db_input('cm32_i_codigo',5,$Icm32_i_codigo,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tcm32_i_numcgm?>">
           <?php
           db_ancora(@$Lcm32_i_numcgm,"js_pesquisacm32_i_numcgm(true);",$db_opcao);
           ?>
        </td>
        <td>
          <?php
            db_input('cm32_i_numcgm',10,$Icm32_i_numcgm,true,'text',$db_opcao," onchange='js_pesquisacm32_i_numcgm(false);'");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tcm32_i_crm?>">
           <?php echo $Lcm32_i_crm?>
        </td>
        <td>
          <?php
            db_input('cm32_i_crm',10,$Icm32_i_crm,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script type="text/javascript">
function js_pesquisacm32_i_numcgm(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm32_i_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','func_nome','func_cgm.php?pesquisa_chave='+document.form1.cm32_i_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro, chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){

    document.form1.cm32_i_numcgm.focus();
    document.form1.cm32_i_numcgm.value = '';
  }
}
function js_mostracgm1(chave1, chave2){

  document.form1.cm32_i_numcgm.value = chave1;
  document.form1.z01_nome.value      = chave2;
  func_nome.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_legista','func_legista.php?funcao_js=parent.js_preenchepesquisa|cm32_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_legista.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>