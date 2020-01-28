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

if(!isset($iEnderecoMunicipio)){
  $iEnderecoMunicipio = '1';
}

$aEnderecoMunicipio = array(
  '1' => 'Sim',
  '0' => 'Não'
);

$clrotulo = new rotulocampo;
$clrotulo->label('j01_numcgm');

$sBotaoDisabled = '';
$sBotaoName     = 'excluir';
$sBotaoValue    = 'Excluir';
if($db_opcao == 1){
  $sBotaoName  = 'incluir';
  $sBotaoValue = 'Incluir';
}elseif($db_opcao == 2 || $db_opcao == 22){
  $sBotaoName  = 'alterar';
  $sBotaoValue = 'Alterar';
}elseif($db_opcao == 6){
  $sBotaoName  = 'atualizar';
  $sBotaoValue = 'Atualizar';
}

if($db_botao == false){
  $sBotaoDisabled = 'disabled';
}

?>
<style>
[name=j43_numimo], [name=j43_cxpost], [name=iEnderecoMunicipio], [name=j43_cep], [name=j43_uf]{
  width: 83px!important;
}
[name=j43_comple], [name=j43_dest], [name=j43_munic]{
  width: 427px!important;
}
</style>
<div class="container">
  <form name="form1" id="formEndereco" method="post">
    <fieldset>
      <legend class="bold">Manutenção de endereço de entrega</legend>
      <table>
        <tr>
          <td nowrap title="<?php echo $Tj43_matric; ?>">
            <label for="j43_matric">
              <a href id="labelMatricula">
                Matrícula
              </a>
            </label>
          </td>
          <td>
          <?php
            db_input('j43_matric', 10, $Ij43_matric, true, 'text', ($db_opcao != 1 ? 3 : 1));
            db_input('z01_nome', 78, $Ij01_numcgm, true, 'text', 3);
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Endereço do Municipio">
            <label for="iEnderecoMunicipio" class="bold">Endereço do Município:</label>
          </td>
          <td>
            <?php db_select('iEnderecoMunicipio', $aEnderecoMunicipio, true, $db_opcao); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_cep; ?>">
            <label for="j43_cep">
              <a href id="labelCep"><?php echo $Lj43_cep; ?></a>
            </label>
          </td>
          <td>
            <?php
              db_input('j43_cep', 10, $Ij43_cep, true, 'text', $db_opcao);
              db_input('j43_cep_hidden', null, null, null, 'hidden', null);
            ?>
          </td>
        </tr>
        <tr id="trj43_uf">
          <td nowrap title="Estado">
            <label for="j43_uf">
              <strong>
                Estado:
              </strong>
            </label>
          </td>
          <td>
            <?php
              $GLOBALS['Sj43_uf'] = 'Estado';
              db_input('j43_uf', null, $Ij43_uf, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr id="trj43_munic">
          <td nowrap title="Município">
            <label for="j43_munic">
              <strong>
                Município:
              </strong>
            </label>
          </td>
          <td>
            <?php
              db_input('j43_munic', null, $Ij43_munic, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_bairro; ?>">
            <label for="j13_codi">
              <a href id="labelBairro"><?php echo $Lj43_bairro; ?></a>
            </label>
          </td>
          <td>
            <?php
              $GLOBALS['Sj13_codi'] = 'Código do Bairro';
              db_input('j13_codi', 10, 1, true, 'text', $db_opcao);
              db_input('j43_bairro', 78, $Ij43_bairro, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_ender; ?>">
            <label for="j34_loteam">
              <a href id="labelEnder"><?php echo $Lj43_ender; ?></a>
            </label>
          </td>
          <td>
            <?php
              $GLOBALS['Sj34_loteam'] = 'Código do Logradouro';
              db_input('j34_loteam', 10, 1, true, 'text', $db_opcao);
              db_input('j43_ender', 78, $Ij43_ender, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_numimo; ?>">
            <label for="j43_numimo">
              <?php echo $Lj43_numimo; ?>
            </label>
          </td>
          <td>
            <?php db_input('j43_numimo', null, $Ij43_numimo, true, 'text', $db_opcao); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_cxpost; ?>">
            <label for="j43_cxpost">
              <?php echo $Lj43_cxpost; ?>
            </label>
          </td>
          <td>
            <?php
              $GLOBALS['Sj43_cxpost'] = 'Caixa postal';
              db_input('j43_cxpost', null, $Ij43_cxpost, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_comple; ?>">
            <label for="j43_comple">
              <?php echo $Lj43_comple; ?>
            </label>
          </td>
          <td>
            <?php db_input('j43_comple', null, $Ij43_comple, true, 'text', $db_opcao); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tj43_dest; ?>">
            <label for="j43_dest">
              <?php echo $Lj43_dest; ?>
            </label>
          </td>
          <td>
            <?php db_input('j43_dest', null, $Ij43_dest, true, 'text', $db_opcao); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="submit" id="db_botao" name="<?php echo $sBotaoName; ?>" value="<?php echo $sBotaoValue; ?>" <?php echo $sBotaoDisabled; ?> onclick="return js_validaTamanhoCampo();">
    <?php if($db_opcao == 6){ ?>
      <input type="submit" id="db_botao" name="excluir" value="Excluir" <?php echo (isset($db_botao_excluir) && $db_botao_excluir == false ? 'disabled' : '')?>/>
    <?php }elseif($db_opcao != 1){ ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    <?php } ?>
    <?php db_input('db_opcao', null, 1, true, 'hidden'); ?>
  </form>
</div>
<script type="text/javascript">



function js_validaTamanhoCampo(){

  var sLogradouro = $F('j43_munic');
  if (sLogradouro.length > 20 ) {

	  alert('Campo Município deve conter no máximo 20 caracteres.');
	  return false;
  }

  return true;
	
}




  var lLimpaCampos       = false,
      lBairroValido      = true,
      lLogradouroValido  = true;
      iDbOpcao           = $F('db_opcao');

  var setBairroInvalido = function(){
    lBairroValido = false;
  }

  var setLogradouroInvalido = function(){
    lLogradouroValido = false;
  }

  $('iEnderecoMunicipio').addEventListener("change", function(){

    if(lLimpaCampos){

      $('j43_cep').value    = "";
      $('j43_bairro').value = "";
      $('j43_ender').value  = "";
      $('j43_uf').value     = "";
      $('j43_munic').value  = "";
      $('j13_codi').value   = "";
      $('j34_loteam').value = "";
      $('j43_numimo').value = "";
      $('j43_cxpost').value = "";
      $('j43_comple').value = "";
      $('j43_dest').value   = "";
    }

    lLimpaCampos = true;

    if(this.value == 0){

      oLookUpBairro.desabilitar();
      oLookUpLogradouro.desabilitar();

      oLookUpCep.habilitar();

      $('trj43_uf').style.display    = null;
      $('trj43_munic').style.display = null;
      $('j13_codi').style.display    = 'none';
      $('j34_loteam').style.display  = 'none';

      $('j43_bairro').classList.remove("readOnly");
      $('j43_bairro').classList.remove("readonly");

      $('j43_ender').classList.remove("readOnly");
      $('j43_ender').classList.remove("readonly");

      $('j43_bairro').readOnly = false;
      $('j43_ender').readOnly = false;

      $('j43_bairro').removeAttribute("style");
      $('j43_ender').removeAttribute("style");

      $('j43_bairro').style.width = '427px';
      $('j43_ender').style.width = '427px';

      $('j43_cep').onchange = function(){
        carregaDadosCep(this.value);
      };
    }else{

      oLookUpBairro.habilitar();
      oLookUpLogradouro.habilitar();

      oLookUpCep.desabilitar();

      $('j13_codi').style.display    = null;
      $('j34_loteam').style.display  = null;

      $('trj43_uf').style.display    = 'none';
      $('trj43_munic').style.display = 'none';

      $('j43_bairro').classList.add("readonly");
      $('j43_ender').classList.add("readonly");

      $('j43_cep').classList.remove("readOnly");
      $('j43_cep').classList.remove("readonly");

      $('j43_bairro').readOnly = true;
      $('j43_ender').readOnly = true;

      $('j43_bairro').style.width = '340px';
      $('j43_ender').style.width = '340px';

      $('j43_cep').readOnly = false;

      $('j43_cep').onchange = null;
    }
  });

  /**
   * Ancora para Matricula
   * @type  {DBLookUp}
   */
  var oLookUpMatricula = new DBLookUp($('labelMatricula'), $('j43_matric'), $('z01_nome'), {
    'sArquivo'              : 'func_iptubase.php',
    'sObjetoLookUp'         : 'db_iframe_iptubase',
    'sLabel'                : 'Pesquisar Matrícula',
    'oBotaoParaDesabilitar' : $('excluir'),
    'aCamposAdicionais'     : ['j01_matric']
  });

  oLookUpMatricula.callBackClick = function(sErro, sNome, sMatric){

    $('z01_nome').value   = sNome;
    $('j43_matric').value = sMatric;
    db_iframe_iptubase.hide();
  };

  /**
   * Ancora para Logradouro
   * @type  {DBLookUp}
   */
  var oLookUpCep = new DBLookUp($('labelCep'), $('j43_cep'), $('j43_cep_hidden'), {
    'sArquivo'              : 'func_cep.php',
    'sObjetoLookUp'         : 'db_iframe_cep',
    'sLabel'                : 'Pesquisa CEP',
    'oBotaoParaDesabilitar' : $('excluir'),
    'aCamposAdicionais'     : ['cep','cp01_bairro', 'cp06_logradouro']
  });

  oLookUpCep.callBackClick = function(sErro, sCepHidden, sCep){

    $('j43_cep').value = sCep;

    carregaDadosCep(sCep);
    db_iframe_cep.hide();
  };

  function carregaDadosCep(sCep){

    var oPesquisa = new Object();
    oPesquisa.exec        = 'findEnderecoByCep';
    oPesquisa.codigoCep   = sCep;
    oPesquisa.sNomeBairro = null;

    var oAjax = new AjaxRequest('con4_endereco.RPC.php', oPesquisa, callBackCep).setMessage('Carregando dados do CEP.').execute();
  }

  function callBackCep(oRetorno, lErro){

    $('j43_uf').value     = '';
    $('j43_munic').value  = '';
    $('j43_bairro').value = '';
    $('j43_ender').value  = '';

    if (oRetorno.endereco.length > 0){

      $('j43_uf').value     = oRetorno.endereco[0].sestadosigla.urlDecode();
      $('j43_munic').value  = oRetorno.endereco[0].smunicipio.urlDecode();
      $('j43_bairro').value = oRetorno.endereco[0].sbairro.urlDecode();
      $('j43_ender').value  = oRetorno.endereco[0].srua.urlDecode();
    }
  }

  /**
   * Ancora para Logradouro
   * @type  {DBLookUp}
   */
  var oLookUpBairro = new DBLookUp($('labelBairro'), $('j13_codi'), $('j43_bairro'), {
    'sArquivo'              : 'func_bairro.php',
    'sObjetoLookUp'         : 'db_iframe_bairro',
    'sLabel'                : 'Pesquisar Bairro',
    'fCallBack'             : setBairroInvalido,
    'oBotaoParaDesabilitar' : $('excluir'),
    'aCamposAdicionais'     : ['j13_descr']
  });

  oLookUpBairro.callBackClick = function(iBairroCodigo, sBairroDescNotExists, sBairroDesc){

    $('j13_codi').value   = iBairroCodigo;
    $('j43_bairro').value = sBairroDesc;

    setBairroInvalido();

    db_iframe_bairro.hide();
  };

  /**
   * Ancora para Logradouro
   * @type  {DBLookUp}
   */
  var oLookUpLogradouro = new DBLookUp($('labelEnder'), $('j34_loteam'), $('j43_ender'), {
    'sArquivo'              : 'func_ruas.php',
    'sObjetoLookUp'         : 'db_iframe_loteam',
    'sLabel'                : 'Pesquisar Logradouro',
    'oBotaoParaDesabilitar' : $('excluir'),
    'aCamposAdicionais'     : ['j14_codigo', 'j14_nome', 'j29_cep']
  });

  oLookUpLogradouro.callBackClick = function(sErro, sDesc, iLograCodigo, sLograDesc, sLograCep){

    $('j34_loteam').value = iLograCodigo;
    $('j43_ender').value  = sLograDesc;

    if(sLograCep != ''){

      $('j43_cep').value    = sLograCep;
      $('j43_cep').readOnly = true;
      $('j43_cep').classList.add("readonly");
    }else if(sLograCep == ''){

      $('j43_cep').readOnly = false;
      $('j43_cep').classList.remove("readonly");
    }

    setLogradouroInvalido();

    db_iframe_loteam.hide();
  };

  oLookUpLogradouro.callBackChange = function() {

    var aArgumentos = arguments,
        lErro       = null,
        sDescricao  = null;

    sDescricao = aArgumentos[0];
    lErro = aArgumentos[1];

    this.oInputDescricao.value = sDescricao;
    if (lErro) {
      this.oInputID.value = '';
    }

    if (this.oParametros.oBotaoParaDesabilitar != '') {
      this.oParametros.oBotaoParaDesabilitar.disabled = false;
    }
    this.oCallback.onChange(lErro);

    if(aArgumentos[2] != ''){
      $('j43_cep').value    = aArgumentos[2];
      $('j43_cep').readOnly = true;
      $('j43_cep').classList.add("readonly");
    }else if(aArgumentos[2] == ''){
      $('j43_cep').readOnly = false;
      $('j43_cep').classList.remove("readonly");
    }

    setLogradouroInvalido();

    return;
  };

  var oEvent = new Event("change");
  $('iEnderecoMunicipio').dispatchEvent(oEvent);

  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptuender','func_iptuender.php?funcao_js=parent.js_preenchepesquisa|j43_matric','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){

    db_iframe_iptuender.hide();
    <?php echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave"; ?>
  }

  if($('db_opcao').value != 1){
    oLookUpMatricula.desabilitar();
  }

  if($('db_opcao').value == 3 || $('db_opcao').value == 33){

    oLookUpCep.desabilitar();

    $('j43_bairro').classList.add("readonly");
    $('j43_ender').classList.add("readonly");

    $('j43_bairro').readOnly = true;
    $('j43_ender').readOnly  = true;
  }

  $('formEndereco').onsubmit = function(){

    if($F('iEnderecoMunicipio') == 1){

      if($F('j13_codi') == '' && lBairroValido == false){
        alert('Campo Bairro é de preenchimento obrigatório!');
        return false;
      }

      if($F('j34_loteam') == '' && lLogradouroValido == false){
        alert('Campo Logradouro é de preenchimento obrigatório!');
        return false;
      }
    }
    return true;
  };

</script>