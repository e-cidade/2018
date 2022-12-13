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

//MODULO: Habitacao
$clavaliacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db100_sequencial");
if ($db_opcao == 1) {
 	$db_action="hab1_avaliacao004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
 	$db_action="hab1_avaliacao005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action="hab1_avaliacao006.php";
}
?>
<form name="form1" method="post" action="<?=$db_action?>" class="container">
<fieldset>
<legend><b>Formulário</b></legend>
<table border="0" class="form-container">
  <tr>
    <td nowrap title="<?=@$Tdb101_sequencial?>">
      <b>Código do Formulário:</b>
    </td>
    <td>
			<?
			  db_input('db101_sequencial', 10, $Idb101_sequencial, true, 'text', 3, "");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb101_avaliacaotipo?>">
      <label for="db101_avaliacaotipo">Formulário tipo:</label>
    </td>
    <td>
		  <?
        $sSqlAvaliacaoTipo  = $clavaliacaotipo->sql_query(null, "*", "db100_sequencial", "");
        $rsSqlAvaliacaoTipo = $clavaliacaotipo->sql_record($sSqlAvaliacaoTipo);

        $aAvaliacaoTipo     = array();
        $aAvaliacaoTipo[0]  = "Selecione ...";
        for ($iInd = 0; $iInd < $clavaliacaotipo->numrows; $iInd++) {

          $oAvaliacaoTipo = db_utils::fieldsMemory($rsSqlAvaliacaoTipo, $iInd);
          $aAvaliacaoTipo[$oAvaliacaoTipo->db100_sequencial] = $oAvaliacaoTipo->db100_descricao;
        }

        db_select('db101_avaliacaotipo', $aAvaliacaoTipo, true, $db_opcao_tipoAvaliacao, " onchange='js_desabilitaselecionar();'");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb101_descricao?>">
       <?=@$Ldb101_descricao?>
    </td>
    <td>
			<?
			  db_input('db101_descricao', 50, $Idb101_descricao, true, 'text', $db_opcao, "");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb101_identificador?>">
       <?=@$Ldb101_identificador?>
    </td>
    <td>
			<?
			  db_input('db101_identificador', 58, $Idb101_identificador, true, 'text', $db_opcao, "");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb101_ativo?>">
       <?=@$Ldb101_ativo?>
    </td>
    <td>
      <?
        $lAtivo = array("t"=>"SIM","f"=>"NÃO");
        db_select('db101_ativo',$lAtivo,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb101_permiteedicao?>">
       <?=@$Ldb101_permiteedicao?>
    </td>
    <td>
      <?
        $lAtivo = array("t"=>"SIM","f"=>"NÃO");
        db_select('db101_permiteedicao',$lAtivo,true,$db_opcao,"");
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap colspan="2">
       <fieldset>
	       <legend><b>Observação</b></legend>
	       <table border="0" cellpadding="0" cellspacing="0">
				   <tr valign="top">
				     <td>
				       <?php
			 	         db_textarea('db101_obs', 5, 70, $Idb101_obs, true, 'text', $db_opcao, "style='width: 535px;'");
				       ?>
				     </td>
				   </tr>
	       </table>
       </fieldset>
    </td>
  </tr>
  </table>
</fieldset>
<table align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" onclick="return js_validarcampos();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?> >
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
</form>
<script>
$('db101_descricao').style.width             = '100%';
$('db101_avaliacaotipo').style.width         = '100%';

function js_desabilitaselecionar() {

  var iAvaliacaoTipo  = $('db101_avaliacaotipo').value;
  if (iAvaliacaoTipo != 0) {
    $('db101_avaliacaotipo').options[0].disabled = true;
  }
}

function js_validarcampos() {

  var iAvaliacaoTipo = $('db101_avaliacaotipo').value;

  if (iAvaliacaoTipo == 0) {
    var sMsg  = "Usuario:\n\n";
        sMsg += " Informe o Tipo de Avaliação!\n\n";
    alert(sMsg);
    return false;
  }
  return js_validaCaracteres();
}

function js_pesquisa(){
  <?php if(isset($iTipoAvaliacao) && $iTipoAvaliacao == 5){ ?>
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_avaliacao','db_iframe_avaliacao','func_avaliacao.php?iTipoAvaliacao=5&funcao_js=parent.js_preenchepesquisa|db101_sequencial','Pesquisa',true,'0');
  <?php } else if(isset($iTipoAvaliacao) && $iTipoAvaliacao == 6){ ?>
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_avaliacao','db_iframe_avaliacao','func_avaliacao.php?iTipoAvaliacao=6&funcao_js=parent.js_preenchepesquisa|db101_sequencial','Pesquisa',true,'0');
  <?php } else { ?>
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_avaliacao','db_iframe_avaliacao','func_avaliacao.php?funcao_js=parent.js_preenchepesquisa|db101_sequencial','Pesquisa',true,'0');
  <?php } ?>
}

function js_preenchepesquisa(chave){
  db_iframe_avaliacao.hide();
  <?
  if($db_opcao!=1){
    if(isset($iTipoAvaliacao) && ($iTipoAvaliacao == 5 || $iTipoAvaliacao == 6)){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])
        . "?iTipoAvaliacao=" . $iTipoAvaliacao . "&chavepesquisa='+chave";
    } else {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  }
  ?>
}

/**
 * Validamos os caracteres do identificador registrado
 * Primeiramente verificamos o caracter inicial, permitindo apenas letras
 * Em seguida, verificamos o que vem a seguir, permitindo letras, numeros e _
 */
function js_validaCaracteres() {

  var sValorInicial     = $F('db101_identificador').substring(0,1);
  var sExpressaoInicial = /[A-Za-z]/;
  var sRegExpInicial    = new RegExp(sExpressaoInicial);
  var lResultadoInicial = sRegExpInicial.test(sValorInicial);

  if (sValorInicial == '') {

    alert('É necessário informar um identificador');
    $('db101_identificador').focus();
    return false;
  }

  if (lResultadoInicial) {

    var sValorCaracteres      = $F('db101_identificador').substring(1);
    var sExpressaoCaracteres  = /^[A-Za-z0-9_]+?$/i;
    var sRegExpCaracteres     = new RegExp(sExpressaoCaracteres);
    var lResultadoCaracteres  = sRegExpCaracteres.test(sValorCaracteres);
    if (!lResultadoCaracteres) {

      alert('São permitidas apenas letras, números e/ou caracter "_" (underline)');
      return false;
    }
  } else {

    alert('É permitido apenas letra no caracter inicial');
    return false;
  }
  return true;
}
</script>
