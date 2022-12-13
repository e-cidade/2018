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

//MODULO: empenho
$clemppresta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e44_descr");
$clrotulo->label("e60_codemp");
      $db_op=3;
?>
<form name="form1" method="post" action="" onSubmit="return js_validaFormulario();">
<fieldset style="width: 600px">
<legend><b>Validação da Prestação de Contas</b></legend>
<center>
<table border="0">
  <tr style="display: none">
    <td>
      <?php
        db_input('e45_sequencial', 10, $Ie45_sequencial, true, 'hidden', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te60_codemp?>">
       <?=@$Le60_codemp?>
    </td>
    <td>
      <?php
        db_input('e60_codemp',10,$Ie60_codemp,true,'text',3);
        db_input('e45_numemp',10,$Ie45_numemp,true,'hidden',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Te45_codmov; ?>">
      <?php echo $Le45_codmov; ?>
    </td>
    <td>
      <?php db_input('e45_codmov', 10, $Ie45_codmov, true); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te45_tipo?>">
       <?
       db_ancora(@$Le45_tipo,"js_pesquisae45_tipo(true);",$db_op);
       ?>
    </td>
    <td>
<?
db_input('e45_tipo',8,$Ie45_tipo,true,'text',$db_op," onchange='js_pesquisae45_tipo(false);'")
?>
       <?
db_input('e44_descr',40,$Ie44_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te45_data?>">
       <?=@$Le45_data?>
    </td>
    <td>
<?
db_inputdata('e45_data',@$e45_data_dia,@$e45_data_mes,@$e45_data_ano,true,'text',$db_op)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te45_conferido?>">
       <?=@$Le45_conferido?>
    </td>
    <td>
<?
db_inputdata('e45_conferido',@$e45_conferido_dia,@$e45_conferido_mes,@$e45_conferido_ano,true,'text',$db_opcao)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te45_obs?>" colspan="2">
      <fieldset>
        <legend><b><?=@$Le45_obs?></b></legend>
      <?
        db_textarea('e45_obs', 5, 70,$Ie45_obs,true,'text',$db_op)
      ?>
      </fieldset>
    </td>
  </tr>
  <?php
  /**
   * Campo observacao para prestacao de conta
   */
  if (isset($lPrestacaoConta) && $lPrestacaoConta && USE_PCASP) {
    echo '
          <tr>
            <td nowrap colspan="2">
              <fieldset>
                <legend><b>Observações do Lançamento</b></legend>';
                  db_textarea('observacao_lancamento', 5, 70,$Ie45_obs,true,'text',1);
    echo '
              </fieldset>
            </td>
          </tr>';
  }
  ?>
  </table>
  </center>
</fieldset>
<br/>
<input name="alterar" type="submit" id="db_opcao" value="Atualizar" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_validaFormulario() {

	if ( $F('e45_conferido') == '' ) {

		alert('Informe a data de conferência.');
		return false;
	}

	return true;
}

function js_pesquisae45_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_emppresta','db_iframe_empprestatip','func_empprestatip.php?funcao_js=parent.js_mostraempprestatip1|e44_tipo|e44_descr','Pesquisa',true);
  }else{
     if(document.form1.e45_tipo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_emppresta','db_iframe_empprestatip','func_empprestatip.php?pesquisa_chave='+document.form1.e45_tipo.value+'&funcao_js=parent.js_mostraempprestatip','Pesquisa',false);
     }else{
       document.form1.e44_descr.value = '';
     }
  }
}
function js_mostraempprestatip(chave,erro){
  document.form1.e44_descr.value = chave;
  if(erro==true){
    document.form1.e45_tipo.focus();
    document.form1.e45_tipo.value = '';
  }
}
function js_mostraempprestatip1(chave1,chave2){
  document.form1.e45_tipo.value = chave1;
  document.form1.e44_descr.value = chave2;
  db_iframe_empprestatip.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe( 'CurrentWindow.corpo.iframe_emppresta',
                       'db_iframe_emppresta',
                       'func_empprestaconfere.php?exibeMovimento=1&funcao_js=parent.js_preenchepesquisa|e60_numemp|e45_codmov',
                       'Pesquisa',
                       true,
                       0 );
}

function js_preenchepesquisa(iNumeroEmpenho, iCodigoMovimento){
  db_iframe_emppresta.hide();

  <?php
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])
           . "?chavepesquisa=' + iNumeroEmpenho + '&chavemovimento=' + iCodigoMovimento";
    }
  ?>
}


function pesquisaItensPrestacaoContas() {

  if ($F('e45_numemp') == "" || $F('e45_sequencial') == "") {
    return;
  }

  js_divCarregando("Aguarde, verificando prestação de contas...", "msgBox");
  var oParam = {
    "exec" : 'getItensPrestacaoContas',
    "iSequencialEmpenho" : $F('e45_numemp'),
    "iSequencialPC" : $F('e45_sequencial')
  };

  new Ajax.Request("emp4_prestacaocontas004.RPC.php",
                  {method: 'post',
                   asynchronous: false,
                   parameters: 'json='+Object.toJSON(oParam),
                   onComplete: function(oAjax) {

                     js_removeObj("msgBox");
                     var oRetorno = eval("("+oAjax.responseText+")");
                     var oBtnOpcao = $('db_opcao');
                     oBtnOpcao.disabled = false;
                     if (oRetorno.iTotalItens == 0) {

                       oBtnOpcao.disabled = true;
                       alert('Não há itens/valores lançados para esta prestação de contas. Não é possível executar a validação.');
                     }

                   }
                  }) ;
}


pesquisaItensPrestacaoContas();
</script>
