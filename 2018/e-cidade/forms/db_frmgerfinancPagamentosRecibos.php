<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBseller Servicos de Informatica
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

$oGet = db_utils::postMemory($_GET);

$iNumCgm = null;
$iMatric = null;
$iInscr  = null;

if(!empty($oGet->numcgm)){
  $iNumCgm = $oGet->numcgm;
}

if(!empty($oGet->matric)){
  $iMatric = $oGet->matric;
}

if(!empty($oGet->inscr)){
  $iInscr = $oGet->inscr;
}

$oDataHoje = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));

$iDia = $oDataHoje->getDia();
$iMes = $oDataHoje->getMes();
$iAno = $oDataHoje->getAno();
?>
<form id="formulario" action="" method="POST">

  <input name="nivel"  type="hidden" id="nivel"  value=0 />
  <input name="codigo" type="hidden" id="codigo" value=0 />

  <div class="container" id='formularioFiltros'>
    <fieldset class="form-container">
      <legend>Consulta Boletos Pagos</legend>

      <table class="form-container">
        <tr>
          <td><label for="sDataInicio">Data Início:</label></td>
          <td>
           <?php
             db_inputdata('sDataInicio', null, null, null, true, 'text', 1);
           ?>
          </td>
        </tr>
        <tr>
          <td><label for="sDataFim">Data Fim:</label></td>
          <td>
           <?php
             db_inputdata('sDataFim', $iDia, $iMes, $iAno, true, 'text', 1);
           ?>
          </td>
        </tr>
        <tr>
          <?php
            $GLOBALS['SiCodigoArrecadacao'] = 'Código de Arrecadação';
          ?>
          <td><label for="iCodigoArrecadacao">Código de Arrecadação:</label></td>
          <td>
           <?php
             db_input('iCodigoArrecadacao', null, 1, true, 'text', null, 'style="width:94px;"');
           ?>
          </td>
        </tr>
        <tr>
          <?php
            $GLOBALS['SiTipoDebito'] = 'Tipo de Débito';
          ?>

          <td><a href="" id="sTipoDebitoLabel">Tipo de Débito:</label></td>
          <td>
            <?php
              db_input('iTipoDebito',          null, 1, true, 'text', null, 'data="k00_tipo"', null, null, 'width:94px');
              db_input('sTipoDebitoDescricao', 20,   3, true, 'text', 3,    'data="k00_descr"');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <input name="procurar" type="button" id="procurar" value="Procurar" onclick="return js_alteraNivel(true);" />
    <input type="reset"  id="limpar"   value="Limpar" />
  </div>

  <input type="hidden" id="iCgm"    value="<?php echo $iNumCgm; ?>">
  <input type="hidden" id="iMatric" value="<?php echo $iMatric; ?>">
  <input type="hidden" id="iInscr"  value="<?php echo $iInscr; ?>">

  <div id="formularioGrid" class="subcontainer">

    <div id="containerGrid" style="width: 1200px;" ></div>

    <div style="padding-top: 15px;">
      <input name="voltar"   type="button" id="voltar"   value="Voltar"   onclick="return js_alteraNivel(false);"/>
      <input name="imprimir" type="button" id="imprimir" value="Imprimir" onclick="return js_imprimir();" />
    </div>
  </div>
</form>

<script type="text/javascript">

  parent.document.getElementById('processando').style.visibility = 'hidden';

  /**
   * Setamos por padrão a visibilidade das div do formulário
   */
  $('formularioFiltros').show();
  $('formularioGrid').hide();

  var sUrlRPC = 'cai3_gerfinancPagamentosRecibos.RPC.php';

  /**
   * Criamos a Collection e as grids de cada nível
   */
  var oCollection = new Collection().setId('id');

  var oGridNivelUm = DatagridCollection.create(oCollection).configure({
      order  : false,
      align  : "center",
      height : "auto"
  });

  var oGridNivelDois = DatagridCollection.create(oCollection).configure({
      order  : false,
      align  : "center",
      height : "auto",
  });

  /**
   * Adicionamos as colunas de cada grid
   */
  oGridNivelUm.addColumn("codigoarrecadacao",   {label: "Código de Arrecadacao", align: "center", width: "17%"});
  oGridNivelUm.addColumn("tipo",                {label: "Tipo",                  align: "center", width: "10%"});
  oGridNivelUm.addColumn("tipodebitodescricao", {label: "Tipo de Débito",        align: "left",   width: "33%"});
  oGridNivelUm.addColumn("datavencimento",      {label: "Vencimento",            align: "center", width: "10%"});
  oGridNivelUm.addColumn("datapagamento",       {label: "Pagamento",             align: "center", width: "10%"});
  oGridNivelUm.addColumn("valor",               {label: "Valor",                 align: "right",  width: "10%"});
  oGridNivelUm.addAction("Detalhar", null, function( oEvento, oItem){

    $('codigo').value = oItem.codigo;
    js_alteraNivel(true);
  });

  oGridNivelDois.addColumn("mi",                  {label : "MI",             align : "center", width : "2%"});
  oGridNivelDois.addColumn("numpre",              {label : "Numpre",         align : "center", width : "8%"});
  oGridNivelDois.addColumn("parcela",             {label : "Parcela",        align : "center", width : "4%"});
  oGridNivelDois.addColumn("total",               {label : "Total",          align : "center", width : "3%"});
  oGridNivelDois.addColumn("tipo",                {label : "Tipo",           align : "center", width : "5%"});
  oGridNivelDois.addColumn("tipodebitodescricao", {label : "Tipo de Débito", align : "left",   width : "18%"});
  oGridNivelDois.addColumn("receita",             {label : "Receita",        align : "center", width : "4%"});
  oGridNivelDois.addColumn("receitadescricao",    {label : "Descrição",      align : "left",   width : "24%"});
  oGridNivelDois.addColumn("datavencimento",      {label : "Vencimento",     align : "center", width : "9%"});
  oGridNivelDois.addColumn("datapagamento",       {label : "Processamento",  align : "center", width : "9%"});
  oGridNivelDois.addColumn("dataefetivacao",      {label : "Pagamento",      align : "center", width : "9%"});
  oGridNivelDois.addColumn("valor",               {label : "Valor",          align : "right",  width : "5%"});

  /**
   * Criamos um array com as grids e seus respectivos níveis
   */
  var aGrids = {
    1 : oGridNivelUm,
    2 : oGridNivelDois
  };

  /**
   * Deixamos o array com as grids imutável
   */
  Object.freeze( aGrids );


  var oTipoDebito = new DBLookUp($('sTipoDebitoLabel'), $('iTipoDebito'), $('sTipoDebitoDescricao'), {
    'sArquivo'       : 'func_arretipo.php',
    'sObjetoLookUp'  : 'db_iframe_arretipo',
    'sLabel'         : 'Pesquisar Tipo de Débito',
    'sDestinoLookUp' : 'CurrentWindow.corpo'
  });

  /**
   * Função que altera os níveis de consultas e grids do formulário
   */
  function js_alteraNivel( lDetalhamento ) {

    var iNivel  = $F('nivel');
    var iCodigo = $F('codigo');

    /**
     * Caso seja um detalhamento, o js_buscar foi chamado pelo botão detalhar da grid ou Procurar do formulário,
     * portanto o nível deve ser adiantado
     * Se não for, o nível deve ser regredido, pois o botão Voltar foi clicado
     */
    var iProximoNivel = parseInt(iNivel) - 1;

    if (lDetalhamento) {
      var iProximoNivel = parseInt(iNivel) + 1;
    }

    /**
     * Alteramos o nível para que os próximos procedimentos executem conforme o nível desejado
     */
    $('nivel').value = iProximoNivel;

    if ( iProximoNivel == 0 ) {

      $('formularioFiltros').show();
      $('formularioGrid').hide();
    } else {
      js_buscar();
    }
  }

  /**
   * Função que faz a primeira consulta dos recibos, utilizando os filtros
   */
  function js_buscar() {

    var iProximoNivel = $F('nivel');
    var iCodigo       = $F('codigo');

    /**
     * Parâmetros para o Ajax
     */
    var aParametros = {
      'sExecucao'          : 'getReciboPago',
      'iCgm'               : $F('iCgm'),
      'iMatric'            : $F('iMatric'),
      'iInscr'             : $F('iInscr'),
      'sDataInicio'        : $F('sDataInicio'),
      'sDataFim'           : $F('sDataFim'),
      'iCodigoArrecadacao' : $F('iCodigoArrecadacao'),
      'iTipoDebito'        : $F('iTipoDebito'),
      'iProximoNivel'      : iProximoNivel,
    }

    if(iProximoNivel == 2){

      aParametros = {
        'sExecucao'     : 'getReciboPagoCodigoArrecadacao',
        'iProximoNivel' : iProximoNivel,
        'iCodigo'       : iCodigo
      }
    }

    /**
     * Variável que guarda a função de callback do ajax
     */
    var ajaxCallBack = function(oRetorno, lErro) {

      if(lErro){

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      if(oRetorno.oDados.aLinhas.length > 0){

        $('formularioFiltros').hide();
        $('formularioGrid').show();

        js_carregaGrid(oRetorno.oDados);
      }else{

        $('nivel').value = parseInt($F('nivel')) - 1;

        alert("Nenhum registro encontrado para o(s) filtro(s) selecionado(s)");
        return false;
      }
    }

    var oAjaxRequest = new AjaxRequest(sUrlRPC, aParametros, ajaxCallBack);
    oAjaxRequest.setMessage('Aguarde...').execute();
  }

  /**
   * Função que faz a primeira consulta dos recibos, utilizando os filtros
   */
  function js_imprimir() {

    var iNivel  = $F('nivel');
    var iCodigo = $F('codigo');

    /**
     * Parâmetros para o Ajax
     */
    var aParametros = {
      'sExecucao'          : 'imprimirDados',
      'iCgm'               : $F('iCgm'),
      'iMatric'            : $F('iMatric'),
      'iInscr'             : $F('iInscr'),
      'sDataInicio'        : $F('sDataInicio'),
      'sDataFim'           : $F('sDataFim'),
      'iCodigoArrecadacao' : $F('iCodigoArrecadacao'),
      'iTipoDebito'        : $F('iTipoDebito'),
      'iNivel'             : iNivel,
      'iCodigo'            : iCodigo
    }

    /**
     * Variável que guarda a função de callback do ajax
     */
    var ajaxCallBack = function(oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      window.open("db_download.php?arquivo="+oRetorno.sArquivo);
    }

    var oAjaxRequest = new AjaxRequest(sUrlRPC, aParametros, ajaxCallBack);
    oAjaxRequest.execute();
  }

  /**
   * Função responsável pelo carregamento dos dados retornados do rpc na grid,
   * assim como qual grid deve ser mostrada, de acordo com o nível selecionado
   *
   * @param  {Object} oDados [Array com os dados retornado do RPC]
   */
  function js_carregaGrid( oDados ) {

    oCollection.clear();

    oDados.aLinhas.each(function (oLinha, iLinha) {

      /**
       * Formatamos os valores de acordo com os tipos de dados
       */
      if(oDados.iNivel == 2){
        oLinha.dataefetivacao = js_formatar(oLinha.dataefetivacao, 'd');
      }

      oLinha.datavencimento = js_formatar(oLinha.datavencimento, 'd');
      oLinha.datapagamento  = js_formatar(oLinha.datapagamento, 'd');
      oLinha.valor          = js_formatar(oLinha.valor, 'f');

      /**
       * Criamos o registro da grid para referente ao MI da consulta
       */
      if ( oDados.iNivel == 2) {

        var sEncode = btoa(oLinha.tipodebito + "#" + oLinha.numpre + "#" + oLinha.parcela + "#" + oLinha.abatimento);
        var sFuncao = "parent.js_mostradetalhes('cai3_gerfinanc025.php?" + sEncode + "','','width=600,height=500,scrollbars=1')";
        var oLink   = "<a href='javascript:;' onclick=\"" + sFuncao + "\"><strong>MI</strong></a>";

        oLinha.mi = oLink;
      }

      oLinha.id = iLinha;

      oCollection.add(oLinha);
    });

    /**
     * Colocamos um array vazio no header da grid para que os cabeçalhos não sejam
     * acumulados caso a grid ja esteja criada e inicializada
     */
    aGrids[oDados.iNivel].grid.aHeaders = new Array();

    aGrids[oDados.iNivel].reload();
    aGrids[oDados.iNivel].show( $('containerGrid') );
  }

</script>

