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

$clparjuridico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$oRotulos = new rotulocampo();
$oRotulos->label("v19_templateparcelamento");
$oRotulos->label("v19_templateinicialquitada");
$oRotulos->label("db82_descricao");

if($db_opcao == 1){
?>
<div style="color:red;">
  <strong>Sem parâmetros para o Exercício de <?php echo $v19_anousu; ?>, favor incluir.</strong>
</div>
<?php
  }
?>
<form class="container" name="form1" id="form_parametros" method="post" action="" onsubmit=" return js_validaDados();">
    <fieldset style="width:730px; !important">
    <legend>Manutenção de Parâmetros</legend>
    <table>
      <tr>
        <td width="200px" nowrap title="<?php echo @$Tv19_anousu?>">
          <?php echo @$Lv19_anousu?>
        </td>
        <td>
          <?php
            db_input('v19_anousu',10,$Iv19_anousu,true,'text',3,"");
            db_input('v19_instit', 10, $Iv19_instit, true, 'hidden', 3, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tv19_envolinicialiptu; ?>">
          <?php echo @$Lv19_envolinicialiptu; ?>
        </td>
        <td>
          <?php
            $aInicialIptu = array('0'=>'Todos', '1'=>'Somente Proprietários', '2'=>'Somente Promitentes');
            db_select('v19_envolinicialiptu', $aInicialIptu, true, $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tv19_envolinicialiss; ?>">
          <?php echo @$Lv19_envolinicialiss; ?>
        </td>
        <td>
          <?php
            $aInicialIss = array('0' => 'Não Vincular Sócios',
                                 '1' => 'Vincular Sócios');
            db_select('v19_envolinicialiss', $aInicialIss, true, $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tv19_envolprinciptu; ?>">
          <?php echo @$Lv19_envolprinciptu;?>
        </td>
        <td>
          <?php
            $aPrincIptu = array("f"=>"Não",
                                "t"=>"Sim");
            db_select('v19_envolprinciptu', $aPrincIptu, true, $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tv19_vlrexecmin; ?>">
          <?php echo @$Lv19_vlrexecmin;?>
        </td>
        <td>
          <?php
            db_input('v19_vlrexecmin', 10, '', true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tv19_partilha; ?>">
          <?php echo @$Lv19_partilha; ?>
        </td>
        <td>
          <?php
            db_select('v19_partilha', array('t' => 'Sim', 'f' =>'Não'), "", $db_opcao,
                      " onChange='js_toggleWebService(this.value);'");
          ?>
        </td>
      </tr>
      <tr class="partilha" style="display: none;">
        <td nowrap title="<?php echo @$Tv19_urlwebservice; ?>">
          <?php echo @$Lv19_urlwebservice;?>
        </td>
        <td>
          <?php
            db_input('v19_urlwebservice', 50, '', true, 'text', $db_opcao, "", "", "", "", 400);
          ?>
        </td>
      </tr>
      <tr class="partilha" style="display: none;">
        <td nowrap title="<?php echo @$Tv19_login; ?>">
          <?php echo @$Lv19_login;?>
        </td>
        <td>
          <?php
            db_input('v19_login', 20, '', true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr class="partilha" style="display: none;">
        <td nowrap title="<?php echo @$Tv19_senha; ?>">
          <?php echo @$Lv19_senha;?>
        </td>
        <td>
          <?php
            db_input('v19_senha', 20, '', true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr class="partilha" style="display: none;">
        <td nowrap title="<?php echo @$Tv19_codorgao; ?>">
          <?php echo @$Lv19_codorgao;?>
        </td>
        <td>
          <?php
            db_input('v19_codorgao', 10, '', true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
    </table>
    </fieldset>

    <fieldset>
      <legend>
        <strong>Modelos de Documentos</strong>
      </legend>
      <table style="width: 100%;">
        <tr>
          <td style="width: 200px"><strong>Petição de Inicial Quitada:</strong></td>
          <td>
          <?php
            $aTipoInicial = array('0' => 'Padrão do Sistema',
                                  '1' => 'Modelo do OpenOffice');
            db_select('tipoInicialQuitada',
                      $aTipoInicial,
                      "",
                      $db_opcao,
                      " container='documentoTipoInicialQuitada' onChange='js_toggleBuscaDocumentos(this);'");
          ?>
          </td>
        </tr>

        <tr id="documentoTipoInicialQuitada" style="display: none;">
          <td nowrap="nowrap" title="<?php echo @$Tp90_db_documentotemplate; ?>">
            <?php
              db_ancora("<strong>Documento Template:</strong>", "js_pesquisaDocumento($('v19_templateinicialquitada'), true);", $db_opcao);
            ?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('v19_templateinicialquitada', 10, $Iv19_templateinicialquitada,
                       true, 'text', $db_opcao, 'onchange="js_pesquisaDocumento( $(\'v19_templateinicialquitada\'), false );"');

              db_input('db82_descricao', 50, $Idb82_descricao, true, 'text', 3, '', 'db82_descricao_inicialquitada');
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Petição de Parcelamento:</strong>
          </td>
          <td>
          <?php
           db_select('tipoParcelamento',
                      array('0' => 'Padrão do Sistema','1' =>'Modelo do OpenOffice'),
                      "",
                      $db_opcao,
                      "container='documentoTipoParcelamento'  onChange='js_toggleBuscaDocumentos(this);'");
          ?>
          </td>
        </tr>
        <tr id="documentoTipoParcelamento" style="display: none;">
          <td nowrap="nowrap" title="<?php echo @$Tp90_db_documentotemplate; ?>">
            <?php
              db_ancora("<strong>Documento Template:</strong>", "js_pesquisaDocumento($('v19_templateparcelamento'), true);", $db_opcao);
            ?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('v19_templateparcelamento', 10, $Iv19_templateparcelamento,
                       true, 'text', $db_opcao, 'onchange="js_pesquisaDocumento( $(\'v19_templateparcelamento\'), false );"');

              db_input('db82_descricao', 50, $Idb82_descricao, true, 'text', 3, '', 'db82_descricao_parcelamento');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
  <input name="<?php echo ($db_opcao == 1 ? "incluir" : "alterar"); ?>"
         type="submit" id="db_opcao" value="<?php echo ($db_opcao == 1 ? "Incluir" : "Alterar")?>">
</form>
<script type="text/javascript">

  $("v19_anousu").addClassName("field-size2");
  $("v19_vlrexecmin").addClassName("field-size2");
  $("v19_urlwebservice").addClassName("field-size7");
  $("v19_login").addClassName("field-size7");
  $("v19_senha").addClassName("field-size7");
  $("v19_codorgao").addClassName("field-size7");
  $("v19_templateinicialquitada").addClassName("field-size2");
  $("db82_descricao_inicialquitada").addClassName("field-size7");
  $("v19_templateparcelamento").addClassName("field-size2");
  $("db82_descricao_parcelamento").addClassName("field-size7");
  $("v19_envolprinciptu").setAttribute("rel","ignore-css");
  $("v19_envolprinciptu").addClassName("field-size2");
  $("v19_partilha").setAttribute("rel","ignore-css");
  $("v19_partilha").addClassName("field-size2");
  $("tipoInicialQuitada").style.width = "100%";
  $("tipoParcelamento").style.width = "100%";

  js_toggleBuscaDocumentos($('tipoInicialQuitada'));
  js_toggleBuscaDocumentos($('tipoParcelamento'));
  js_toggleWebService($('v19_partilha').value);

  /**
   * Mostra/Oculta campos do documento template
   * @param object - oElemento - Elemento HTML base para a funçao
   */
  function js_toggleBuscaDocumentos(oElemento) {

    if ( parseInt( oElemento.value ) == 0 ) {
      $(oElemento.getAttribute("container")).style.display = 'none';
    } else {
      $(oElemento.getAttribute("container")).style.display = '';
    }
  }

  /**
   * Mostra/Oculta campos ref a conexao do webservice
   * @param object - oElemento - Elemento HTML base para a funçao
   */
  function js_toggleWebService(oElemento) {

    if ( oElemento == 'f' ) {
      $$(".partilha").each(function (oElemento) { oElemento.style.display = 'none'});
    } else {
    	$$(".partilha").each(function (oElemento) { oElemento.style.display = ''});
    }
  }

  /**
   * Pesquisa dados via lookup ou digitação
   * @param object   oElemento Elemento HTML base para pesquisa
   * @param boolean  lMostra   Valida se mostra a lookup de pesquisa
   */
  function js_pesquisaDocumento(oElemento, lMostra) {

    var iLayoutTipo         = 0;
    var oInputTexoRetorno   = null;
    var oInputCodigoRetorno = null;
    var sArquivoPesquisa    = null;

    if ( oElemento.id == "v19_templateparcelamento" ) {

      iLayoutTipo         = 16;
      oInputTexoRetorno   = $('db82_descricao_parcelamento');
      oInputCodigoRetorno = $('v19_templateparcelamento');
    }

    if ( oElemento.id == "v19_templateinicialquitada" ) {

      iLayoutTipo         = 17;
      oInputTexoRetorno   = $('db82_descricao_inicialquitada');
      oInputCodigoRetorno = $('v19_templateinicialquitada');
    }

    if ( !lMostra && oElemento.value == '' ) {
      return;
    }

    sArquivoPesquisa    = 'func_db_documentotemplate.php?pesquisa_chave=' + oInputCodigoRetorno.getValue() + '&funcao_js=parent.js_mostraDocumentoDigitacao&tipo=' + iLayoutTipo;
    if (lMostra) {
      sArquivoPesquisa  = 'func_db_documentotemplate.php?funcao_js=parent.js_mostraDocumentoLookUp|db82_sequencial|db82_descricao|db82_templatetipo&tipo=' + iLayoutTipo;
    }
    /**
     * Abre a janela
     */
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_db_documentotemplate',
                        sArquivoPesquisa,
                        'Pesquisa Documentos Template',
                        lMostra);
  }

  /**
   * Preenche dados dos campos via digitação
   * @param string  sRetorno      Descrição do Layout
   * @param boolean lErro         Valida se existe erro ao buscar os dados
   * @param integer iTipoTemplate Grupo do Layout da Tabela db_documentotemplatetipo
   */
  function js_mostraDocumentoDigitacao(sRetorno, lErro, iTipoModelo){

      var oInputTexoRetorno   = null;
      var oInputCodigoRetorno = null;

      if ( iTipoModelo == 16 ) {

        oInputTexoRetorno   = $('db82_descricao_parcelamento');
        oInputCodigoRetorno = $('v19_templateparcelamento');
      };

      if ( iTipoModelo == 17 ) {

        oInputTexoRetorno   = $('db82_descricao_inicialquitada');
        oInputCodigoRetorno = $('v19_templateinicialquitada');
      };


      oInputTexoRetorno.value = sRetorno;

    if (lErro) {
      oInputCodigoRetorno.focus();
      oInputCodigoRetorno.value = '';
    }
  }
  /**
   * Preenche dados dos campos via lookup
   * @param integer iCodigo       Código do Layout da tabela db_documentotemplate
   * @param string  sRetorno      Descrição do Layout
   * @param integer iTipoTemplate Grupo do Layout da Tabela db_documentotemplatetipo
   */
  function js_mostraDocumentoLookUp(iCodigo, sRetorno, iTipoTemplate) {

      var oInputTexoRetorno   = null;
      var oInputCodigoRetorno = null;

      if ( iTipoTemplate == 17 ) {

        oInputTexoRetorno   = $('db82_descricao_inicialquitada');
        oInputCodigoRetorno = $('v19_templateinicialquitada');
      };

      if ( iTipoTemplate == 16 ) {

        oInputTexoRetorno   = $('db82_descricao_parcelamento');
        oInputCodigoRetorno = $('v19_templateparcelamento');
      };

      oInputCodigoRetorno.value = iCodigo;
      oInputTexoRetorno.value   = sRetorno;

      db_iframe_db_documentotemplate.hide();
  }

  /**
   * Valida os dados do formulário ao postar
   */
  function js_validaDados() {

    if ( $F('v19_partilha')  == 'f' ) {

      $('v19_urlwebservice').value = '';
      $('v19_login').value         = '';
      $('v19_senha').value         = '';
      $('v19_codorgao').value      = '';
    }

    if ( $F('tipoInicialQuitada') == 1) {

      if($F('v19_templateinicialquitada') == ''){

        alert('Campo Documento Inicial para Petição de Inicial Quitada é de preenchimento obrigatório.');
        return false;
      }
    }

    if ( $F('tipoParcelamento') == 1) {

      if($F('v19_templateparcelamento') == ''){

        alert('Campo Documento Inicial para Petição de Parcelamento é de preenchimento obrigatório.');
        return false;
      }
    }

    if ( $F('tipoInicialQuitada') == 0) {
      $('v19_templateinicialquitada').value = '';
    }

    if ( $F('tipoParcelamento') == 0) {
      $('v19_templateparcelamento').value   = '';
    }

    return true;
  }

</script>