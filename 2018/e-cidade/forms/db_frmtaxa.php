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

//MODULO: arrecadacao
$clrotulo = new rotulocampo;
$clrotulo->label("ar37_descricao");
$clrotulo->label("ar36_sequencial");
$clrotulo->label("k02_descr");
$clrotulo->label("ar36_debitoscomprocesso");
$clrotulo->label("ar36_debitossemprocesso");
$clrotulo->label("ar36_descricao");
$clrotulo->label("ar36_grupotaxa");
$clrotulo->label("ar36_receita");
$clrotulo->label("ar36_valor");
$clrotulo->label("ar36_perc");
$clrotulo->label("ar36_valormin");
$clrotulo->label("ar36_valormax");


?>
<br>
<form name="form1" method="post" action="">
  <fieldset >
    <legend>Cadastro de Taxas / Custas</legend>
    <table class="form-container">
      <tr>
        <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_sequencial?>">
         <?=$Lar36_sequencial?>
       </td>
       <td>
         <?
         db_input('ar36_sequencial',10,$Iar36_sequencial,true,'text',3,"")
         ?>
       </td>
      </tr>

      <tr>
        <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_descricao?>">
          <?=$Lar36_descricao?>
        </td>
        <td>
         <? db_input('ar36_descricao',70,$Iar36_descricao,true,'text',$db_opcao,"") ?>
        </td>
      </tr>

      <tr>
        <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_grupotaxa?>">
         <?php
           db_ancora($Lar36_grupotaxa,"js_pesquisaar36_grupotaxa(true);",$db_opcao);
         ?>
        </td>
        <td>
          <?php
            db_input('ar36_grupotaxa',10,$Iar36_grupotaxa,true,'text',$db_opcao," onchange='js_pesquisaar36_grupotaxa(false);'");
            db_input('ar37_descricao',56,$Iar37_descricao,true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_receita?>">
          <?php
            db_ancora($Lar36_receita,"js_pesquisaar36_receita(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input('ar36_receita',10,$Iar36_receita,true,'text',$db_opcao," onchange='js_pesquisaar36_receita(false);'");
            db_input('k02_descr',56,$Ik02_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td class="field-size3">
          <b>Tipo de Cobrança</b>
        </td>
        <td>
          <?php
            $aTipoCobranca = array('1'=>'Valor Fixado','2'=>'Percentual de Débito');
            db_select('tipo_cobranca', $aTipoCobranca, true, $db_opcao, "onchange='js_tipoCobranca(this.value);' ");
          ?>
        </td>
      </tr>
    </table>

    <div id ='cntValor' >
      <table class="form-container" >
        <tr >
          <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_valor?>">
           <?=$Lar36_valor?>
         </td>
         <td>
           <?php // db_input('ar36_valor', 10, $Iar36_valor, true, 'text',$db_opcao,"") ?>
           <?php db_input('ar36_valor', 10, '', false, 'text',$db_opcao,"") ?>
         </td>
        </tr>
      </table>
    </div>

    <div id ='cntPerc' style="display: none;">
      <table class="form-container">
        <tr>
          <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_perc?>">
            <?=$Lar36_perc?>
          </td>
          <td>
            <?php db_input('ar36_perc',10,$Iar36_perc,true,'text',$db_opcao,"") ?>
          </td>
        </tr>
        <tr>
          <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_valormin?>">
            <?=$Lar36_valormin?>
          </td>
          <td>
            <?php db_input('ar36_valormin',10,$Iar36_valormin,true,'text',$db_opcao,"") ?>
          </td>
        </tr>
        <tr>
          <td class="field-size3" nowrap='nowrap' title="<?=$Tar36_valormax?>">
            <?=$Lar36_valormax?>
          </td>
          <td>
            <?php db_input('ar36_valormax',10,$Iar36_valormax,true,'text',$db_opcao,"") ?>
          </td>
        </tr>
      </table>
    </div><br>
    <fieldset>
        <legend>Aplicar taxa a</legend>
        <table class="form-container">
          <tr>
            <td style="width:10px">
              <?php db_input('ar36_debitoscomprocesso',"",$Iar36_debitoscomprocesso,true,'checkbox',$db_opcao,"");?>               
            </td>
            <td>          
              <strong>Débitos com cobrança judicial</strong>
            </td>
          </tr>
          <tr>
            <td style="width:10px">
            <?php db_input('ar36_debitossemprocesso',"",$Iar36_debitossemprocesso,true,'checkbox',$db_opcao,"");?>
            </td>
            <td>
              <strong>Débitos com cobrança administrativa</strong>
            </td>
          </tr>
        </table>
      </fieldset>
  </fieldset>  
</center>
<div style="margin-top: 10px;">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
        id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?> onclick="return js_verifica(tipo_cobranca.value);" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</div>
</form>
<script>

const MSG_FRMTAXA = 'tributario.arrecadacao.db_frmtaxa.';

  /**
  *  validação de debitos com 
  *   processo ou sem processo
  */
  var comProcesso = $('ar36_debitoscomprocesso');
  var semProcesso = $('ar36_debitossemprocesso');
  
  comProcesso.observe('click', function() {

    if (comProcesso.getAttribute('checked') == null){
      comProcesso.setAttribute('checked','');
      comProcesso.value = 't';
    } else {
      comProcesso.removeAttribute('checked');
      comProcesso.value = 'f';   
    }
  });
  console.log(comProcesso.value);
  semProcesso.observe('click', function() {
    
    if(semProcesso.getAttribute('checked') == null){
      semProcesso.setAttribute('checked','');
      semProcesso.value = 't';
    } else {
      semProcesso.removeAttribute('checked');
      semProcesso.value = 'f';
    }
  });

function js_verifica(iTipo){

  var iValor     = document.getElementById('ar36_valor').value;
  var iPerc      = document.getElementById('ar36_perc').value;
  var iMin       = document.getElementById('ar36_valormin').value;
  var iMax       = document.getElementById('ar36_valormax').value;
  var sDescricao = document.getElementById('ar36_descricao').value;
  var iGrupotaxa = document.getElementById('ar36_grupotaxa').value;
  var iReceita   = document.getElementById('ar36_receita').value;

  if (sDescricao == '' || sDescricao == null) {
    alert(_M( MSG_FRMTAXA + 'descricao_obrigatorio'));
    return false;
  }

  if (iGrupotaxa == '' || iGrupotaxa == null) {
    alert(_M( MSG_FRMTAXA + 'grupo_taxa_obrigatorio'));
    return false;
  }

  if (iReceita == '' || iReceita == null) {
    alert(_M( MSG_FRMTAXA + 'receita_obrigatorio'));
    return false;
  }

  if(iTipo == 1 && (iValor == '' || iValor == null ) ){
    alert(_M( MSG_FRMTAXA + 'valor_obrigatorio'));
    return false;
  }

  if (iTipo == 1 && iValor == 0 )  {
    alert(_M( MSG_FRMTAXA + 'valor_maior_zero'));
    return false;
  }

  if (iTipo == 2) {

    if (iPerc == "" || iPerc == null ) {

      alert(_M( MSG_FRMTAXA + 'percentual_obrigatorio'));
      return false;
    }

    if (iMin == "" || iMin == null ) {

      alert(_M( MSG_FRMTAXA + 'valor_minimo_obrigatorio'));
      return false;
    }

    if (iMax == "" || iMax == null ) {

      alert(_M( MSG_FRMTAXA + 'valor_maximo_obrigatorio'));
      return false;
    }

    if (iMax < iMin) {

      alert(_M( MSG_FRMTAXA + 'valor_maximo_menor_minimo'));
      return false;
    }
  }

  if (iTipo == 2 && iPerc == 0) {

    alert(_M( MSG_FRMTAXA + 'percentual_maior_zero'));
    return false;
  }

}

function js_tipoCobranca(iTipo){

  if (iTipo == 1) {

    document.getElementById('cntPerc').style.display  = 'none';
    document.getElementById('cntValor').style.display = 'inline';
    document.getElementById('ar36_perc').value        = '';
    document.getElementById('ar36_valormin').value    = '';
    document.getElementById('ar36_valormax').value    = '';

  } else if (iTipo == 2) {

    document.getElementById('cntPerc').style.display  = 'inline';
    document.getElementById('cntValor').style.display = 'none';
    document.getElementById('ar36_valor').value       = '';
  }

}

function js_pesquisaar36_grupotaxa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_grupotaxa','func_grupotaxa.php?funcao_js=parent.js_mostragrupotaxa1|ar37_sequencial|ar37_descricao','Pesquisa',true);
  }else{
   if(document.form1.ar36_grupotaxa.value != ''){
    js_OpenJanelaIframe('','db_iframe_grupotaxa','func_grupotaxa.php?pesquisa_chave='+document.form1.ar36_grupotaxa.value+'&funcao_js=parent.js_mostragrupotaxa','Pesquisa',false);
  }else{
   document.form1.ar37_descricao.value = '';
 }
}
}
function js_mostragrupotaxa(chave,erro){
  document.form1.ar37_descricao.value = chave;
  if(erro==true){
    document.form1.ar36_grupotaxa.focus();
    document.form1.ar36_grupotaxa.value = '';
  }
}
function js_mostragrupotaxa1(chave1,chave2){
  document.form1.ar36_grupotaxa.value = chave1;
  document.form1.ar37_descricao.value = chave2;
  db_iframe_grupotaxa.hide();
}

function js_pesquisaar36_receita(mostra){
 if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_receita','func_tabrec.php?funcao_js=parent.js_mostrareceita1|k02_codigo|k02_descr','Pesquisa',true);
 }else{
  if(document.form1.ar36_receita.value != ''){
   js_OpenJanelaIframe('','db_iframe_receita','func_tabrec.php?pesquisa_chave='+document.form1.ar36_receita.value+'&funcao_js=parent.js_mostrareceita','Pesquisa',false);
 }else{
  document.form1.k02_descr.value = '';
}
}
}
function js_mostrareceita(chave,erro){
 document.form1.k02_descr.value = chave;
 if(erro==true){
   document.form1.ar36_receita.focus();
   document.form1.ar36_receita.value = '';
 }
}
function js_mostrareceita1(chave1,chave2){
 document.form1.ar36_receita.value = chave1;
 document.form1.k02_descr.value = chave2;
 db_iframe_receita.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_taxa','func_taxa.php?funcao_js=parent.js_preenchepesquisa|ar36_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_taxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

document.getElementById('ar36_valor').setAttribute("onKeyPress", "return mascaraValor(event, this)");
document.getElementById('ar36_valormin').setAttribute("onKeyPress", "return mascaraValor(event, this)");
document.getElementById('ar36_valormax').setAttribute("onKeyPress", "return mascaraValor(event, this)");

</script>
