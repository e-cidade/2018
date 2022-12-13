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

//MODULO: prefeitura
$cldb_confplan->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("k01_descr");
$clrotulo->label("k00_descr");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Manutenção de Planilhas</legend>
    <table border="0">
      <tr>
        <td nowrap title="<?php echo @$Tw10_valor?>">
        <input name="oid" type="hidden" value="<?php echo @$oid?>">
           <?php echo @$Lw10_valor?>
        </td>
        <td>
          <?php
            db_input('w10_valor',6,$Iw10_valor,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tw10_receit?>">
          <?php
            db_ancora(@$Lw10_receit,"js_pesquisaw10_receit(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input('w10_receit',6,$Iw10_receit,true,'text',$db_opcao," onchange='js_pesquisaw10_receit(false);'");
            db_input('k02_descr',40,$Ik02_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tw10_hist?>">
          <?php
            db_ancora(@$Lw10_hist,"js_pesquisaw10_hist(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input('w10_hist',6,$Iw10_hist,true,'text',$db_opcao," onchange='js_pesquisaw10_hist(false);'");
            db_input('k01_descr',40,$Ik01_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tw10_tipo?>">
           <?php
           db_ancora(@$Lw10_tipo,"js_pesquisaw10_tipo(true);",$db_opcao);
           ?>
        </td>
        <td>
          <?php
            db_input('w10_tipo',6,$Iw10_tipo,true,'text',$db_opcao," onchange='js_pesquisaw10_tipo(false);'");
            db_input('k00_descr',40,$Ik00_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tw10_dia?>">
           <?php echo @$Lw10_dia?>
        </td>
        <td>
          <?php
            db_input('w10_dia',6,$Iw10_dia,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="db_opcao" type="submit" id="db_opcao" value="<?php echo ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?php echo ($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaw10_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.w10_receit.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.w10_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = '';
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave;
  if(erro==true){
    document.form1.w10_receit.focus();
    document.form1.w10_receit.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.w10_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisaw10_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
  }else{
     if(document.form1.w10_hist.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.w10_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
     }else{
       document.form1.k01_descr.value = '';
     }
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave;
  if(erro==true){
    document.form1.w10_hist.focus();
    document.form1.w10_hist.value = '';
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.w10_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}
function js_pesquisaw10_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.w10_tipo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.w10_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = '';
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave;
  if(erro==true){
    document.form1.w10_tipo.focus();
    document.form1.w10_tipo.value = '';
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.w10_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_confplan','func_db_confplan.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_confplan.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>