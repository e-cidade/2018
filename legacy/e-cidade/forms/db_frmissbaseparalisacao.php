<?php
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


$clissbaseparalisacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");
$clrotulo->label("q141_descricao");

if (!isset($q140_datainicio_dia )) {
  $q140_datainicio_dia = "";
  $q140_datainicio_mes = "";
  $q140_datainicio_ano = "";
}
if (!isset($q140_datafim_dia )) {
  $q140_datafim_dia    = "";
  $q140_datafim_mes    = "";
  $q140_datafim_ano    = "";
}

$sAction      = ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"));
$sActionAlias = ucfirst($sAction);

?>

<div class="container">

  <form name="form1" method="post" action="" onsubmit="return js_validaFormulario()">

    <fieldset style="width:500px;">
      <legend>Paralisação de Inscrição</legend>

      <?php
        db_input('q140_sequencial',10,$Iq140_sequencial,true,'hidden',$db_opcao,"");
      ?>

      <table border="0" class="form-container">
        <tr>
          <td nowrap title="<?php echo $Tq02_inscr; ?>">
             <?php
              db_ancora($Lq140_issbase,"js_pesquisaq140_issbase(true);",$db_opcao);
             ?>
          </td>
          <td>
            <?php
              db_input('q140_issbase',10,$Iq140_issbase,true,'text',$db_opcao," onchange='js_pesquisaq140_issbase(false);'");
              db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tq140_issmotivoparalisacao; ?>">
            <?php
              db_ancora($Lq140_issmotivoparalisacao,"js_pesquisaq140_issmotivoparalisacao(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('q140_issmotivoparalisacao',10,$Iq140_issmotivoparalisacao,true,'text',$db_opcao," onchange='js_pesquisaq140_issmotivoparalisacao(false);'");
              db_input('q141_descricao',30,$Iq141_descricao,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tq140_datainicio; ?>">
             <?php echo $Lq140_datainicio; ?>
          </td>
          <td>
            <?php
              db_inputdata('q140_datainicio', $q140_datainicio_dia, $q140_datainicio_mes, $q140_datainicio_ano, true, 'text',$db_opcao,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tq140_datafim; ?>">
             <?php echo $Lq140_datafim; ?>
          </td>
          <td>
            <?php
              db_inputdata('q140_datafim', $q140_datafim_dia, $q140_datafim_mes, $q140_datafim_ano,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>
      </table>

    </fieldset>

    <fieldset>
      <legend><?php echo $Lq140_observacao; ?></legend>

        <table>
          <tr>
            <td>
              <?php
                db_textarea('q140_observacao',5,0,$Iq140_observacao,true,'text',$db_opcao,"style='width: 490px;'");
              ?>
            </td>
          </tr>
        </table>
    </fieldset>

    <input name="<?php echo $sAction; ?>" type="submit" id="db_opcao" value="<?php echo $sActionAlias; ?>" <?=($db_botao==false?"disabled":"")?> >
    <?php if ($db_opcao <> 1) {?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php } ?>

  </form>

</div>

<script>

  var MENSAGEM_SISTEMA = 'tributario.issqn.db_frmissbaseparalisacao.';

	function js_validaFormulario() {

		var iCodigoInscricao         = document.form1.q140_issbase.value;
		var iCodigoMotivoParalisacao = document.form1.q140_issmotivoparalisacao.value

		if (!isNumeric(iCodigoInscricao)) {
			alert('Código da inscrição municipal é inválido.');
			document.form1.q140_issbase.value = '';
			return false;
		}

		if (!isNumeric(iCodigoMotivoParalisacao)) {
			alert('Código do motivo da paralisação é inválido.');
			document.form1.q140_issmotivoparalisacao.value = '';
			return false;
		}

		return true;
	}

  function js_pesquisaq140_issmotivoparalisacao(mostra){
    if (mostra==true) {
      js_OpenJanelaIframe('top.corpo','db_iframe_issmotivoparalisacao','func_issmotivoparalisacao.php?funcao_js=parent.js_mostraissmotivoparalisacao1|q141_sequencial|q141_descricao','Pesquisa',true);
    } else {

       if (document.form1.q140_issmotivoparalisacao.value != '') {
          js_OpenJanelaIframe('top.corpo','db_iframe_issmotivoparalisacao','func_issmotivoparalisacao.php?pesquisa_chave='+document.form1.q140_issmotivoparalisacao.value+'&funcao_js=parent.js_mostraissmotivoparalisacao','Pesquisa',false);
       } else {
         document.form1.q141_descricao.value = '';
       }
    }
  }

  function js_mostraissmotivoparalisacao(chave,erro){
    document.form1.q141_descricao.value = chave;
    if (erro==true) {

      document.form1.q140_issmotivoparalisacao.focus();
      document.form1.q140_issmotivoparalisacao.value = '';
    }
  }

  function js_mostraissmotivoparalisacao1(chave1,chave2){
    document.form1.q140_issmotivoparalisacao.value = chave1;
    document.form1.q141_descricao.value = chave2;
    db_iframe_issmotivoparalisacao.hide();
  }

  function js_pesquisaq140_issbase(mostra){
    if (mostra==true) {
      js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
    } else {
       if (document.form1.q140_issbase.value != '') {
          js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q140_issbase.value+'&funcao_js=parent.js_mostraissbase&sani=0','Pesquisa',false);
       } else {
         document.form1.q02_numcgm.value = '';
       }
    }
  }

  function js_mostraissbase(chave,erro, sDtBaixa){
    if (sDtBaixa) {
      alert( _M(MENSAGEM_SISTEMA + 'empresa_baixada', {sInscricao: document.form1.q140_issbase.value}) )
      document.form1.q140_issbase.value = '';
      document.form1.q140_issbase.onchange();
      return false;
    }
    document.form1.z01_nome.value = chave;
    if (erro==true) {
      document.form1.q140_issbase.focus();
      document.form1.q140_issbase.value = '';
    }
  }

  function js_mostraissbase1(chave1,chave2, sDtBaixa){
    db_iframe_issbase.hide();

    if (sDtBaixa) {
      alert( _M(MENSAGEM_SISTEMA + 'empresa_baixada', {sInscricao: chave1}) )
      document.form1.q140_issbase.value = '';
      document.form1.q140_issbase.onchange();
      return false;
    }

    document.form1.q140_issbase.value = chave1;
    document.form1.z01_nome.value = chave2;
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbaseparalisacao','func_issbaseparalisacao.php?funcao_js=parent.js_preenchepesquisa|q140_sequencial','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){
    db_iframe_issbaseparalisacao.hide();
    <?php
      if ($db_opcao!=1) {
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
    ?>
  }
</script>