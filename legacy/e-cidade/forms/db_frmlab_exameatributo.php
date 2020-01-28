<?php
/*
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

//MODULO: Laborat?io
$cllab_exameatributo->rotulo->label();
?>

<form name="form1" method="post" action="">
  <fieldset>
    <legend>Exames</legend>
    <table class='form-container'>
      <tr>
        <td nowrap title="<?=@$Tla42_i_codigo?>">
           <?=@$Lla42_i_codigo?>
        </td>
        <td>
          <?php db_input('la42_i_codigo',10,$Ila42_i_codigo,true,'text',3,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla42_i_exame?>">
          <?db_ancora ( @$Lla42_i_exame, "js_pesquisala42_i_exame(true);", 3 );?>
        </td>
        <td>
          <?php
            db_input ( 'la42_i_exame', 10, $Ila42_i_exame, true, 'text', 3, " onchange='js_pesquisala42_i_exame(false);'" );
            db_input ( 'la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '' );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla42_i_atributo?>">
           <?php db_ancora ( @$Lla42_i_atributo, "js_pesquisala42_i_atributo(true);", $db_opcao );?>
        </td>
        <td>
          <?php
            db_input('la42_i_atributo', 10, $Ila42_i_atributo, true, 'text', $db_opcao, " onchange='js_pesquisala42_i_atributo(false);'" );
            db_input ( 'la25_c_descr', 50, @$Ila25_c_descr, true, 'text', 3, '' );
          ?>
        </td>
      </tr>
    </table>



  </fieldset>
  <?php if ($db_opcao==2) { ?>

    <div id='ctnAtributos' style="height: 500px; overflow: auto; ">
      <? $cllab_tributo_componente->atributos($la42_i_exame,$la42_i_atributo,"", 4 , 0, 0)?>
    </div>

    <input type="hidden" name="repositorios" id="repoditorios" value="<?=$cllab_tributo_componente->getInputs()?>" >
    <input type="hidden" name="sAtributos"   id="sAtributos" value="">
    <input type="hidden" name="sValores"     id="sValores" value="" >

 <?}?>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida()">
</form>

<script>
F=document.form1;
function js_valida() {

  aVet               = F.repositorios.value.split(',');
  F.sValores.value   = '';
  sSep               = '';
  F.sAtributos.Value = '';
  for(x=0;x<aVet.length;x++){

    aVet3=aVet[x].split('A');
    F.sAtributos.value+=sSep+aVet3[1];
    if (document.getElementById(aVet[x]).checked==false) {
      F.sValores.value += sSep+'1';
    } else {
      F.sValores.value += sSep+'0';
    }
    sSep = '|';
  }
  return true;
}
function js_pesquisala42_i_atributo(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_lab_atributo','func_lab_atributo2.php?funcao_js=parent.js_mostralab_atributo1|la25_i_codigo|la25_c_descr','Pesquisa',true);
  } else {
   if(document.form1.la42_i_atributo.value != ''){
    js_OpenJanelaIframe('','db_iframe_lab_atributo','func_lab_atributo2.php?pesquisa_chave='+document.form1.la42_i_atributo.value+'&funcao_js=parent.js_mostralab_atributo','Pesquisa',false);
  } else {
   document.form1.la25_c_descr.value = '';
  }
}
}
function js_mostralab_atributo(chave,erro) {

  document.form1.la25_c_descr.value = chave;
  if(erro==true){
    document.form1.la42_i_atributo.focus();
    document.form1.la42_i_atributo.value = '';
  }
}
function js_mostralab_atributo1(chave1,chave2) {

  document.form1.la42_i_atributo.value = chave1;
  document.form1.la25_c_descr.value = chave2;
  db_iframe_lab_atributo.hide();
}

</script>
