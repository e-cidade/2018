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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$cliptucalc            = new cl_iptucalc;
$cliptuender           = new cl_iptuender;
$cliptunump            = new cl_iptunump;
$clmassamat            = new cl_massamat;

$clcadban              = new cl_cadban;
$cldb_config           = new cl_db_config;
$cldb_docparag         = new cl_db_docparag;
$clarrematric          = new cl_arrematric;
$cllistadoc            = new cl_listadoc;
$cllote                = new cl_lote;
$clsetor               = new cl_setor;
$cldb_layouttxtgeracao = new cl_db_layouttxtgeracao;

$clrotulo = new rotulocampo();
$cllote->rotulo->label();
$clsetor->rotulo->label();

$oPost = \db_utils::postMemory($_POST);

?>
<html>
 <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
   <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
   <?php
     db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js, grid.style.css");
     db_app::load("estilos.css, classes/dbViewAvaliacoes.classe.js, widgets/windowAux.widget.js");
     db_app::load("widgets/dbmessageBoard.widget.js, dbcomboBox.widget.js, DBHint.widget.js");
     db_app::load("widgets/DBAbas.widget.js, widgets/Collection.widget.js, widgets/DatagridCollection.widget.js");
     db_app::load("AjaxRequest.js");
   ?>
</head>
<body class="body-default" onload="js_mostraOpVenc();js_mostracapa(); js_gridUnicas();">
  <form name="form1" action="" class="form-container" method="post">
    <div id="abaProcessamento" class="container">
      <fieldset>
        <legend>Processamento da Geral de IPTU</legend>

        <fieldset class="separator" id='configGeral'>
          <legend>Configuração de Geração</legend>

          <table border="0" class="form-container">
            <tr>
              <td width="45%">
                <strong>Quantidade de registros do select:</strong>
              </td>
              <td nowrap>
                <input type='text' size="20px;" id='quantidade' name='quantidade' value=<?=(isset($quantidade)?$quantidade:1000)?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Ordem da Emissão:</strong>
              </td>
              <td>
                <?
                   $aOrdem = array ( "endereco"        => "Cidade / Logradouro",
                                     "bairroender"     => "Bairro / Logradouro",
                                     "alfabetica"      => "Alfabética / Nome",
                                     "zonaentrega"     => "Zona de entrega",
                                     "refant"          => "Referência Anterior",
                                     "setorquadralote" => "Setor / Quadra / Lote",
                                     "bairroalfa"      => "Bairro / Alfabética" );
                   db_select('ordem', $aOrdem, true, 2,"style='width:165px;'");
                ?>
              </td>
            </tr>

          </table>
        </fieldset>

        <fieldset class="separator" style="margin-top: 20px;">
          <legend>Configuração de Dados</legend>

          <table border="0" class="form-container">
            <tr>
              <td width="45%">
                <strong>Tipo de Imóvel:</strong>
              </td>
              <td>
                <?
                  $aEspecie = array ( "todos"       => "Todos",
                                      "predial"     => "Somente Predial",
                                      "territorial" => "Somente Territorial" );
                  db_select('especie', $aEspecie, true, 1);
                ?>
              </td>
            </tr>

            <tr id="showSetor" style='display: none;'>
              <td nowrap title="<?=@$Tj34_setor?>">
                <?php
                  db_ancora(@$Lj34_setor,"js_pesquisaj34_setor(true);",1);
                ?>
              </td>
              <td>
                <?php
                  db_input('j34_setor',10,$Ij34_setor,true,'text',1,"onchange='js_pesquisaj34_setor(false);'");
                  db_input('j30_descr',51,$Ij30_descr,true,'text',3,'');
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Valor Mínimo de:</strong>
              </td>
              <td>
                <input type='text' id='vlrmin' size='10' name='vlrmin' value=<?=(isset($vlrmin)?$vlrmin:0)?>>
                <strong>à</strong>
                <input type='text' id='vlrmax' size='10' name='vlrmax' value=<?=(isset($vlrmax)?$vlrmax:999999999)?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Valor de Intervalo para Parcelado:</strong>
              </td>
              <td>
                <input type='text' id='vlrminunica' name='vlrminunica' size='10' value=<?=(isset($vlrminunica)?$vlrminunica:0)?>><strong> à </strong>
                <input type='text' id='vlrmaxunica' name='vlrmaxunica' size='10' value=<?=(isset($vlrmaxunica)?$vlrmaxunica:999999999)?>>
                <?
                  $aIntervaloParcelamento = array ( "desconsiderar" => "Desconsiderar intervalo",
                                                    "gerar"         => "Gerar para os que estiverem no intervalo",
                                                    "naogerar"      => "Nao gerar para os que estiverem no intervalo" );
                  db_select('intervalo', $aIntervaloParcelamento, true, 1,"style='width:276px;'");
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Filtro Principal:</strong>
              </td>
              <td>
                <?
                  $aFiltroPrincipal = array ( "normal"  => "Normal",
                                              "compgto" => "Somente sem parcelas em atraso",
                                              "sempgto" => "Somente os registros sem pagamentos" );
                  db_select('filtroprinc', $aFiltroPrincipal, true, 1,"style='width:480px;'");
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Vinculo com Imobiliária:</strong>
              </td>
              <td>
                <?
                  $aVinculoImobiliaria = array ( "todos" => "Imprimir todos os registros, independente do vinculo com imobiliaria",
                                                 "com"   => "Somente os que tenham vinculo com imobiliaria",
                                                 "sem"   => "Somente os que nao tenham vinculo com imobiliaria" );
                  db_select('imobiliaria', $aVinculoImobiliaria, true, 1,"style='width:480px;'");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Vinculo com Loteamentos:</strong>
              </td>
              <td>
                <?
                  $aVinculoLoteamentos = array ( "todos" => "Imprimir todos os registros, independente do vinculo com loteamento",
                                                 "com"   => "Somente os que tenham vinculo com loteamento",
                                                 "sem"   => "Somente os que nao tenham vinculo com loteamento" );
                  db_select('loteamento', $aVinculoLoteamentos, true, 1,"style='width:480px;'");
                ?>
              </td>
            </tr>
          </table>

        </fieldset>

        <fieldset class="separator" style="margin-top: 20px;">
          <legend>Outros Filtros</legend>

          <table border="0" class="form-container">

            <tr id="tercDigBarrasUnica" style="display: none;">
              <td width="45%">
                <strong>Terceiro Digito do Codigo de Barras das Únicas:</strong>
              </td>

              <td>
                <?
                  $aTercDigBarraUnicas = array ("seis" => "6 (seis)", "sete" => "7 (sete)");
                  db_select('barrasunica', $aTercDigBarraUnicas, true, 1,"style='width:165px;'");
                ?>
              </td>
            </tr>

            <tr id="tercDigBarrasParc" style="display:none;">
              <td>
                <strong>Terceiro Digito do Código de Barras Parcelado:</strong>
              </td>

              <td>
                <?
                  $aTercDigBarraParcelados = array ("seis" => "6 (seis)", "sete" => "7 (sete)");
                  db_select('barrasparc', $aTercDigBarraParcelados, true, 1,"style='width:165px;'");
                ?>
              </td>
            </tr>

            <tr id="capa" style="display:none;">
              <td>
                <strong>Imprimir Capa:</strong>
              </td>
              <td>
                <?php
                  $aImpCapa = array ("n" => "Não", "s" => "Sim");
                  db_select('imprimecapa', $aImpCapa, true, 1,"style='width:165px;'");
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Considerar Movimento nos Anos:</strong>
              </td>
              <td nowrap>
                <input type='text' size="20px;" name='processarmovimentacao' id='processarmovimentacao' value=<?=(isset($processarmovimentacao)?$processarmovimentacao:"")?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Parcela Obrigatória em Aberto:</strong>
              </td>
              <td>
                <input type='text' id='parcobrig' name='parcobrig' value=<?=(isset($parcobrig)?$parcobrig:"")?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Quantidade total de parcelas:</strong>
              </td>
              <td>
                <input type='text' id='quantidadeparcelas' name='quantidadeparcelas' value=<?=(isset($quantidadeparcelas)?$quantidadeparcelas:"")?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Gerar Apenas para as Matrículas:</strong>
              </td>
              <td>
                <input type='text' id='listamatrics' name='listamatrics' value=<?=(isset($listamatrics)?$listamatrics:"")?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Gerar com Cidade em Branco e sem Caixa Postal:</strong>
              </td>
              <td>
                <input type='checkbox' id='cidadebranco' name='cidadebranco'<?=(isset($cidadebranco)?"checked":"")?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Somente com Endereço Válido ou com Caixa Postal:</strong>
              </td>
              <td>
                <input type='checkbox' id= 'entregavalido' name='entregavalido'<?=(!isset($ordem)?"checked":(isset($entregavalido)?"checked":""))?>>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Processar Massa Falida:</strong>
              </td>
              <td>
                <input type='checkbox' id='proc' name='proc' <?=(isset($proc)?"checked":"")?> >
              </td>
            </tr>

            <tr>
              <td>
                <strong>Ano:</strong>
              </td>
              <td>
                <?php
                  $result = db_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");

                  if (pg_numrows($result) > 0) {

                    echo "<select id='anousu' name='anousu' onChange='document.form1.submit();' style='width:165px;'>";

                    if(!isset($anousu)) {
                      $anousu = pg_result($result, 0, "j18_anousu");
                    }

                    for ($i = 0; $i < pg_numrows($result); $i ++) {
                      db_fieldsmemory($result, $i);

                      echo "<option value='{$j18_anousu}' " ;

                      if(isset($anousu) && $anousu == $j18_anousu){
                        echo "selected";
                      }

                      echo  ">";
                      echo $j18_anousu;
                      echo "</option>";
                    }

                    echo "</select>";
                  }
                ?>
              </td>
            </tr>
          </table>
        </fieldset>

        <fieldset class="separator" style="margin-top: 20px;" >
          <legend>Parcela(s) única(s)</legend>

          <div id='cntUnicas' style="width:98%"> </div>
        </fieldset>

        <?php

          $datausu = date('Y-m-d',db_getsession('DB_datausu'));

          $sql  =  " select distinct k00_dtvenc, k00_dtoper, k00_percdes ";
          $sql .=  "   from recibounica ";
          $sql .=  "        inner join iptunump on j20_numpre = k00_numpre ";
          $sql .=  "                           and j20_anousu = {$anousu} ";
          $sql .=  "  where k00_tipoger = 'G' ";
          $sql .=  "    and k00_dtvenc > '{$datausu}' order by k00_dtvenc, k00_percdes ";

          $result = db_query($sql);
        ?>
      </fieldset>

      <input name="totcheck"  type="hidden" id="totcheck" value="<?=pg_numrows($result)?>" />
      <input name="processar" type="submit" id="processar" value="Processar" onclick="js_getUnicas();"/>
      <input type="hidden"    value="" name="listaUnicas" id="listaUnicas" />
    </div>

    <div id="abaArquivo" class="container">
      <fieldset>
        <legend>Geração do Arquivo para a Gráfica</legend>
        <table>
        <tr>
          <td>
            <strong>Tipo de Arquivo:</strong>
          </td>
          <td>
            <?
              $aTipos = array ( "txt"    => "TXT",
                                "txtbsj" => "TXT BSJ");
              db_select('tipo', $aTipos, true, 1,"onChange='js_mostracapa();js_mostraOpVenc();' style='width:165px;'");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap width="45%">
            <strong>Quantidade de registros a gerar no txt:</strong>
          </td>
          <td nowrap>
            <input type='text' size="20px;" id='quantidade_registros_real' name='quantidade_registros_real' value=<?=(isset($quantidade_registros_real)?$quantidade_registros_real:"")?>>
          </td>
        </tr>

        <tr>
          <td nowrap width="45%">
            <strong>Mensagem Débitos Anos Anteriores:</strong>
          </td>
          <td>
            <?
              $aMsgDebitosAnt = array ("n" => "Não", "s" => "Sim");
              db_select('mensagemanosanteriores', $aMsgDebitosAnt, true, 1,"style='width:165px;'");
            ?>
          </td>
        </tr>

        <tr id = "geraOpVenc" style='display: none;'>
          <td>
            <strong>Gera Opção de Vencimento:</strong>
          </td>
          <td>
            <?
              $aOpVenc = array ("0" => "Não", "1" => "Sim");
              db_select('opVenc', $aOpVenc, true, 2,"style='width:165px;'");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap colspan="2">
            <fieldset class="separator">
              <legend>Emissões Processadas</legend>

              <div id="containerGrid"></div>
            </fieldset>
          </td>
        </tr>

        </table>
      </fieldset>

      <input name="emitir" type="button" id="emitir" value="Gerar Arquivos" />
    </div>

    <div id="conteudo_abas" class="form-container" />
  </form>
  <?php
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">

var oDBAbas = new DBAbas($('conteudo_abas'));
oDBAbas.adicionarAba('Processar Emissão' , $('abaProcessamento'));
oDBAbas.adicionarAba('Emitir Carnês', $('abaArquivo'));

const TIPO_EMISSAO = 1;
const URL_RPC      = 'cad4_emiteiptuNovo.RPC.php';

/**
 * Alteramos o callaback ao clicar na aba, para que a grid seja atualizada
 */
oDBAbas.aAbas[1].setCallback(function(){
  js_carregarGrid();
});

var oCollection = new Collection().setId('id');

var oGrid = DatagridCollection.create(oCollection).configure({
  order  : false,
  align  : "center",
  height : '200'
});

oCollection.clear();

/**
 * Adicionamos as colunas na grid
 */
oGrid.addColumn("id",          {label: "Código",      align: "center", width: "15%"});
oGrid.addColumn("data",        {label: "Data",        align: "center", width: "30%"});
oGrid.addColumn("hora",        {label: "Hora",        align: "center", width: "20%"});
oGrid.addColumn("ocorrencias", {label: "Ocorrências", align: "center", width: "35%"});

/**
 * Função que será chamada no onclick das linhas na grid
 */
function js_onClickLinhaGrid (aRows, oSelectedRow) {

  aRows.each( function(oRow, iIndex){
    $(oRow.sId).setAttribute('class', 'normal');
  });

  oSelectedRow.setAttribute('class', 'marcado selecionado');
}

oGrid.sEventSelectRow = " onclick=\"js_onClickLinhaGrid("+oGrid.grid.nameInstance+".aRows, this);\" ";
oGrid.show( $('containerGrid') );

/**
 * Função que consulta as emissões gerais e popula a grid
 */
function js_carregarGrid() {

  var aParametros = {
    'sExecucao'    : 'consultarEmissao',
    'iTipoEmissao' : TIPO_EMISSAO
  }

  var oAjax = new AjaxRequest(URL_RPC, aParametros, function(oRetorno, lErro) {
    if ( lErro ) {

      alert(oRetorno.sMensagem);
      return false;
    }

    oRetorno.aDados.each(function(oLinha, iIndice){

      oCollection.add(oLinha);
      oGrid.reload();
    });
  });

  oAjax.execute();
}

/**
 * Função executada ao clicar no botão Emitir Carnês
 * Responsável por enviar os parâmetros da tela e enviar para o programa que emite os carnês
 */
$('emitir').observe('click', function(){

  var aEmissao = oGrid.getCollectionByRowClass('selecionado');

  if (!aEmissao) {
    alert("Selecione uma Emissão Processada para gerar os arquivos!");
    return false;
  }

  if (aEmissao.length > 1) {
    alert("Selecione somente uma Emissão Processada.");
    return false;
  }

  var oEmissao = aEmissao[0];

  sParametros  = 'tipo='                       + $F('tipo');
  sParametros += '&quantidade_registros_real=' + $F('quantidade_registros_real');
  sParametros += '&mensagemanosanteriores='    + $F('mensagemanosanteriores');
  sParametros += '&opVenc='                    + $F('opVenc');
  sParametros += '&emissao='                   + oEmissao.id;

  js_OpenJanelaIframe('','db_iframe_carne','cad4_emissaogeraliptuarquivo.php?'+sParametros,'Gerando Carnês...',true);
});

function js_pesquisaj34_setor(mostra) {

  if (mostra==true) {
     js_OpenJanelaIframe('','db_iframe','func_setor.php?funcao_js=parent.js_mostrasetor1|0|1','Pesquisa',true);
  } else {
     js_OpenJanelaIframe('','db_iframe','func_setor.php?pesquisa_chave='+document.form1.j34_setor.value+'&funcao_js=parent.js_mostrasetor','Pesquisa',false);
  }
}

function js_mostrasetor(chave,erro) {

  if(erro==true){
    document.form1.j34_setor.focus();
    document.form1.j34_setor.value = '';
  }
  document.form1.j30_descr.value = chave;
}

function js_mostrasetor1(chave1,chave2) {

  document.form1.j34_setor.value = chave1;
  document.form1.j30_descr.value = chave2;
  db_iframe.hide();
}

var aEventoShow = new Array('onMouseover','onFocus');
  var aEventoHide = new Array('onMouseout' ,'onBlur');

  var oDbHintQuantidade = new DBHint('oDbHintQuantidade');
      sHintQuantidade  = "Quantidade de registros a processar no select principal. <br> ";
      sHintQuantidade += "Nao significa que vao ser gerados essa quantidade de registros no txt, <br> ";
      sHintQuantidade += "pois existes testes e bloqueios que podem limitar alguns registros, dependendo dos filtros. <br> ";
      sHintQuantidade += "<strong>* deixe em branco para processar todos </strong>";
      oDbHintQuantidade.setText(sHintQuantidade);
      oDbHintQuantidade.setShowEvents(aEventoShow);
      oDbHintQuantidade.setHideEvents(aEventoHide);
      oDbHintQuantidade.make($('quantidade'));

  var oDbHintQuantidadeRegistrosReal = new DBHint('oDbHintQuantidadeRegistrosReal');
      sHintQuantidadeRegistrosReal  = "Quantidade de registros real a serem gerados no txt. <br> ";
      sHintQuantidadeRegistrosReal += "<br> Valor limitado ao campo [Quantidade de registros do select].  <br> ";
      sHintQuantidadeRegistrosReal += "<strong>* deixe em branco para processar todos </strong>";
      oDbHintQuantidadeRegistrosReal.setText(sHintQuantidadeRegistrosReal);
      oDbHintQuantidadeRegistrosReal.setShowEvents(aEventoShow);
      oDbHintQuantidadeRegistrosReal.setHideEvents(aEventoHide);
      oDbHintQuantidadeRegistrosReal.make($('quantidade_registros_real'));

  var oDbHintTipo = new DBHint('oDbHintTipo');
      oDbHintTipo.setText('Tipo de arquivo a ser gerado. ');
      oDbHintTipo.setShowEvents(aEventoShow);
      oDbHintTipo.setHideEvents(aEventoHide);
      oDbHintTipo.make($('tipo'));

  var oDbHintOrdem = new DBHint('oDbHintOrdem');
      oDbHintOrdem.setText('Definirá a ordem em que os carnes serão gerados. ');
      oDbHintOrdem.setShowEvents(aEventoShow);
      oDbHintOrdem.setHideEvents(aEventoHide);
      oDbHintOrdem.make($('ordem'));

  var oDbHintOpVencimento = new DBHint('oDbHintOpVencimento');
      oDbHintOpVencimento.setText('Definirá se serão gerados opções de vencimentos adicionais <br> para as parcelas e únicas. ');
      oDbHintOpVencimento.setShowEvents(aEventoShow);
      oDbHintOpVencimento.setHideEvents(aEventoHide);
      oDbHintOpVencimento.make($('opVenc'));

  var oDbHintEspecie = new DBHint('oDbHintEspecie');
      oDbHintEspecie.setText('Definir se serão gerados carnês <br> do tipo territorial, predial ou todos. ');
      oDbHintEspecie.setShowEvents(aEventoShow);
      oDbHintEspecie.setHideEvents(aEventoHide);
      oDbHintEspecie.make($('especie'));

  var oDbHintValorMinimo = new DBHint('oDbHintValorMinimo');
      oDbHintValorMinimo.setText('Valor mínimo que deseja considerar <br> para a emissão geral. ');
      oDbHintValorMinimo.setShowEvents(aEventoShow);
      oDbHintValorMinimo.setHideEvents(aEventoHide);
      oDbHintValorMinimo.make($('vlrmin'));

  var oDbHintValorMaximo = new DBHint('oDbHintValorMaximo');
      oDbHintValorMaximo.setText('Valor máximo que deseja considerar <br> para a emissão geral. ');
      oDbHintValorMaximo.setShowEvents(aEventoShow);
      oDbHintValorMaximo.setHideEvents(aEventoHide);
      oDbHintValorMaximo.make($('vlrmax'));

  var oDbHintValorValorMinUnica = new DBHint('oDbHintValorValorMinUnica');
      oDbHintValorValorMinUnica.setText('Informe o intervalo de valores que deseja <br> considerar nas parcelas, para que seja <br> gerado o carnê em apenas uma parcela. ');
      oDbHintValorValorMinUnica.setShowEvents(aEventoShow);
      oDbHintValorValorMinUnica.setHideEvents(aEventoHide);
      oDbHintValorValorMinUnica.make($('vlrminunica'));

  var oDbHintValorValorMaxUnica = new DBHint('oDbHintValorValorMaxUnica');
      oDbHintValorValorMaxUnica.setText('Informe o intervalo de valores que deseja <br> considerar nas parcelas, para que seja <br> gerado o carnê em apenas uma parcela. ');
      oDbHintValorValorMaxUnica.setShowEvents(aEventoShow);
      oDbHintValorValorMaxUnica.setHideEvents(aEventoHide);
      oDbHintValorValorMaxUnica.make($('vlrmaxunica'));

  var oDbHintintervalo = new DBHint('oDbHintintervalo');
      oDbHintintervalo.setText('Selecionar se deseja considerar <br> ou não o intervalo indicado. ');
      oDbHintintervalo.setShowEvents(aEventoShow);
      oDbHintintervalo.setHideEvents(aEventoHide);
      oDbHintintervalo.make($('intervalo'));

  var oDbHintfiltroprinc = new DBHint('oDbHintfiltroprinc');
      oDbHintfiltroprinc.setText('Informar se deseja garar somente parcelas sem atraso, <br> sem pagamento ou todas. ');
      oDbHintfiltroprinc.setShowEvents(aEventoShow);
      oDbHintfiltroprinc.setHideEvents(aEventoHide);
      oDbHintfiltroprinc.make($('filtroprinc'));

  var oDbHintimobiliaria = new DBHint('oDbHintimobiliaria');
      oDbHintimobiliaria.setText('Informar se deseja gerar somente <br> registros com vínculo em Imobiliária ou não. ');
      oDbHintimobiliaria.setShowEvents(aEventoShow);
      oDbHintimobiliaria.setHideEvents(aEventoHide);
      oDbHintimobiliaria.make($('imobiliaria'));

  var oDbHintloteamento = new DBHint('oDbHintloteamento');
      oDbHintloteamento.setText('Informar se deseja gerar registros <br> somente dos imóveis que tenham <br> vínculo com loteamento. ');
      oDbHintloteamento.setShowEvents(aEventoShow);
      oDbHintloteamento.setHideEvents(aEventoHide);
      oDbHintloteamento.make($('loteamento'));

  var oDbHintMensagemAnosAnteriores = new DBHint('oDbHintMensagemAnosAnteriores');
      oDbHintMensagemAnosAnteriores.setText("Informar 'Sim', para exibir mensagem <br> de débito dos anos anteriores. ");
      oDbHintMensagemAnosAnteriores.setShowEvents(aEventoShow);
      oDbHintMensagemAnosAnteriores.setHideEvents(aEventoHide);
      oDbHintMensagemAnosAnteriores.make($('mensagemanosanteriores'));

  var oDbHintProcessarMovimentacao = new DBHint('oDbHintProcessarMovimentacao');
  var sHintProcMovimento  = "Quando informado neste campo o valor 3 por exemplo, <br> o sistema verificará nos 3 ";
      sHintProcMovimento += "exercícios anteriores <br> ao que está sendo emitido se existem pagamentos.<br><strong>";
      sHintProcMovimento += "* deixe em branco para processar todos</strong>";
      oDbHintProcessarMovimentacao.setText(sHintProcMovimento);
      oDbHintProcessarMovimentacao.setShowEvents(aEventoShow);
      oDbHintProcessarMovimentacao.setHideEvents(aEventoHide);
      oDbHintProcessarMovimentacao.make($('processarmovimentacao'));

  var oDbHintParcObrig  = new DBHint('oDbHintParcObrig');
  var sHintParcObrig    = "Caso queira gerar os carnês somente para as matrículas <br> ";
      sHintParcObrig   += "que estiverem uma determinada parcela em aberto, informe <br> ";
      sHintParcObrig   += "nesse campo o número dessa parcela.";
      oDbHintParcObrig.setText(sHintParcObrig);
      oDbHintParcObrig.setShowEvents(aEventoShow);
      oDbHintParcObrig.setHideEvents(aEventoHide);
      oDbHintParcObrig.make($('parcobrig'));

  var oDbHintQuantParc  = new DBHint('oDbHintQuantParc');
  var sHintQuantParc    = "Caso queira gerar os carnês somente para os <br> ";
      sHintQuantParc   += "imoveis que tem uma quantidade especifica de parcelas <br> ";
      sHintQuantParc   += "em aberto. Normalmente utilizado para BSJ. <br> ";
      sHintQuantParc   += "A logica é sempre a quantidade maxima de parcelas mais a parcela unica, <br> ";
      sHintQuantParc   += "então se você preencher 2, vao ser processados os imoveis com até a parcela 1 mais a única em aberto. <br> ";
      sHintQuantParc   += "Obs: parcelas unicas somam apenas uma em caso de ter varias. <br> ";
      oDbHintQuantParc.setText(sHintQuantParc);
      oDbHintQuantParc.setShowEvents(aEventoShow);
      oDbHintQuantParc.setHideEvents(aEventoHide);
      oDbHintQuantParc.make($('quantidadeparcelas'));

  var oDbHintListaMatriculas = new DBHint('oDbHintListaMatriculas');
      oDbHintListaMatriculas.setText("Informar as matrículas desejadas para a geração dos carnes. <br> <strong>*Separadas por vírgula</strong>. ");
      oDbHintListaMatriculas.setShowEvents(aEventoShow);
      oDbHintListaMatriculas.setHideEvents(aEventoHide);
      oDbHintListaMatriculas.make($('listamatrics'));

  var oDbHintCidadeBranco = new DBHint('oDbHintCidadeBranco');
  var sHintCidadeBranco  = "Se esse campo estiver selecionado, serão gerados <br> no arquivo inclusive as matrículas que o ";
      sHintCidadeBranco += "endereço de envio <br> do carnê estiver com a cidade ou caixa postal em branco.";
      oDbHintCidadeBranco.setText(sHintCidadeBranco);
      oDbHintCidadeBranco.setShowEvents(aEventoShow);
      oDbHintCidadeBranco.setHideEvents(aEventoHide);
      oDbHintCidadeBranco.make($('cidadebranco'));

  var sHintEntregavalido   = "Se esse campo estiver marcado, só serão <br> inseridas no arquivo as matrículas";
      sHintEntregavalido  += "que possuem <br> endereço de entrega.";

  var oDbHintEntregaValido = new DBHint('oDbHintEntregaValido');
      oDbHintEntregaValido.setText(sHintEntregavalido);
      oDbHintEntregaValido.setShowEvents(aEventoShow);
      oDbHintEntregaValido.setHideEvents(aEventoHide);
      oDbHintEntregaValido.make($('entregavalido'));

  var oDbHintProc = new DBHint('oDbHintProc');
  var sHintProc   = "Se esse campo estiver marcado, serão inseridas no arquivo <br> as matrículas que estiverem no ";
      sHintProc  += "cadastro de massa falida.";
      oDbHintProc.setText(sHintProc);
      oDbHintProc.setShowEvents(aEventoShow);
      oDbHintProc.setHideEvents(aEventoHide);
      oDbHintProc.make($('proc'));

  var oDbHintAnousu = new DBHint('oDbHintAnousu');
      oDbHintAnousu.setText('Exercício da geração dos carnês.');
      oDbHintAnousu.setShowEvents(aEventoShow);
      oDbHintAnousu.setHideEvents(aEventoHide);
      oDbHintAnousu.make($('anousu'));

  var oDbHintBarrasUnica = new DBHint('oDbHintBarrasUnica');
  var sHintBarrasUnica   = "Esse campo só será utilizado quando o <br> padrão de cobrança adotado pela prefeitura para ";
      sHintBarrasUnica  += "<br> os carnês de IPTU for arrecadação.";
      oDbHintBarrasUnica.setText(sHintBarrasUnica);
      oDbHintBarrasUnica.setShowEvents(aEventoShow);
      oDbHintBarrasUnica.setHideEvents(aEventoHide);
      oDbHintBarrasUnica.make($('barrasunica'));

  var oDbHintBarrasParc = new DBHint('oDbHintBarrasParc');
      oDbHintBarrasParc.setText(sHintBarrasUnica);
      oDbHintBarrasParc.setShowEvents(aEventoShow);
      oDbHintBarrasParc.setHideEvents(aEventoHide);
      oDbHintBarrasParc.make($('barrasparc'));

  var oDbHintImprimeCapa = new DBHint('oDbHintImprimeCapa');
      oDbHintImprimeCapa.setText("Informar 'Sim' para imprimir capa para os carnês.<br><strong>*Arquivo em PDF</strong> ");
      oDbHintImprimeCapa.setShowEvents(aEventoShow);
      oDbHintImprimeCapa.setHideEvents(aEventoHide);
      oDbHintImprimeCapa.make($('imprimecapa'));


function js_mostraOpVenc(){

  document.getElementById('showSetor').style.display = 'none';

  var iMostraOp = $F('tipo');

  if( iMostraOp == 'pdf' ) {
    document.getElementById('showSetor').style.display = '';
  }

  if (iMostraOp == 'txt' ) {

      document.getElementById('geraOpVenc').style.display = '';
      $("opVenc").options.length = 0;
      $("opVenc").options[0]     = new Option('Não', '0');
      $("opVenc").options[1]     = new Option('Sim', '1');
    } else {

      document.getElementById('geraOpVenc').style.display = 'none';

      $("opVenc").options.length = 0;
      $("opVenc").options[0]     = new Option('Não', '0');
      $("opVenc").options[1]     = new Option('Sim', '1');
  }
}

function js_getUnicas(){

   var aListaCheckbox = oGridUnicas.getSelection();
   var aListaUnicas   = new Array();

   aListaCheckbox.each(
     function ( aRow ) {
       aListaUnicas.push(aRow[0]);
    }
   );

   var sListaUnicas = aListaUnicas.join('U');
   $('listaUnicas').value = sListaUnicas;
}

/*
 * funcao para montar os registros iniciais da grid
 *
 */
function lista_unicas() {

   var iAnousu          = $('anousu').value;
   var sUrlRPC          = "cad4_emiteiptuRPC.php";
   var msgDiv           = "Aguarde ... \nPesquisando Únicas";
   var oParametros      = new Object();

   oParametros.exec     = 'VerUnicas';
   oParametros.iAnousu  = iAnousu;

   js_divCarregando(msgDiv,'msgBox');

   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaUnicas
                                             });
}

/*
 * funcao para montar a grid com os registros de Unicas
 *  retornado do RPC
 *
 */
function js_retornoCompletaUnicas(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    oGridUnicas.clearAll(true);

    if ( oRetorno.dados.length == 0 ) {

      $('cntUnicas').innerHTML = '<strong>Sem únicas disponíveis...</strong>';
      return false;

    } else {

      oRetorno.dados.each( function (oDado, iInd) {

        var aRow = new Array();
        aRow[0]  = oDado.id;
        aRow[1]  = oDado.unicas.urlDecode();
        oGridUnicas.addRow(aRow,null,null,true);
      });
      oGridUnicas.renderRows();
    }
  }
}

/*
 * Inicia a Montagem do grid UNICAS (sem os registros)
 *
 */
function js_gridUnicas() {

  oGridUnicas = new DBGrid('Unicas');
  oGridUnicas.nameInstance = 'oGridUnicas';
  oGridUnicas.setCheckbox(0);
  oGridUnicas.setCellWidth(new Array('25%',
                                     '85%'
                                   ));

  oGridUnicas.setCellAlign(new Array( 'left',
                                      'left'
                                   ));


  oGridUnicas.setHeader(new Array( 'id',
                                   'Descrição das Unicas'
                                ));


  oGridUnicas.aHeaders[1].lDisplayed = false;
  oGridUnicas.setHeight(150);

  oGridUnicas.show($('cntUnicas'));
  oGridUnicas.clearAll(true);

  lista_unicas();
}


  function js_validaTercDigito(){

    js_divCarregando('Aguarde, carregando...','msgBox');
    var url     = 'cad4_emiteiptuRPC.php';
    var sQuery  = "anousu="+document.form1.anousu.value;
        sQuery += "&tipo="+document.form1.tipo.value;
    var oAjax   = new Ajax.Request( url, {
                                        method: 'post',
                                        parameters: sQuery,
                                        onComplete: js_recTercDig
                                       }
                                  );
  }

  function js_recTercDig(oAjax){

     js_removeObj("msgBox");
     var aRetorno = eval("("+oAjax.responseText+")");

    if (aRetorno.lErro == true){

      alert(aRetorno.sMsg.urlDecode());
      return false;
    } else {

      if ( aRetorno.sMsg == "s") {
        document.getElementById('tercDigBarrasUnica').style.display = "";
        document.getElementById('tercDigBarrasParc').style.display  = "";
      } else {
        document.getElementById('tercDigBarrasUnica').style.display = "none";
        document.getElementById('tercDigBarrasParc').style.display  = "none";
      }
    }
  }

  function js_abrelayout(){
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_layout', 'cad4_emiteiptulayout001.php', 'Seleção de Campos ...', true);
  }

  function disableform(caminhoform, formstatus){

    js_OpenJanelaIframe('', 'iframe', 'testeprogress.php', 'Processando ...');

    if (formstatus == 'off') {
      for (i = 0; i < caminhoform.elements.length; i++) {
        caminhoform.elements[i].disabled = true;
      }
    }

    if (formstatus == 'on') {
      for (i = 0; i < caminhoform.elements.length; i++) {
        caminhoform.elements[i].disabled = false;
      }
    }

    caminhoform.abilitar.disabled    = false;
    caminhoform.desabilitar.disabled = false;
  }

  function js_mostracapa(){

    if (document.form1.tipo.value != 'txt') {
      document.getElementById('capa').style.display = '';
    } else {
      document.getElementById('capa').style.display = 'none';
    }

    js_validaTercDigito();
  }

  js_validaTercDigito();
</script>
<?php

if(isset($processar)){

  $querystring  = "unica={$oPost->listaUnicas}";
  $querystring .= (empty($oPost->proc)? "" : "&proc={$oPost->proc}");
  $querystring .= (empty($oPost->entregavalido) ? "" : "&entregavalido={$oPost->entregavalido}");
  $querystring .= (empty($oPost->cidadebranco) ? "" : "&cidadebranco={$oPost->cidadebranco}");
  $querystring .= "&parcobrig={$oPost->parcobrig}";
  $querystring .= "&quantidadeparcelas={$oPost->quantidadeparcelas}";
  $querystring .= "&listamatrics={$oPost->listamatrics}";
  $querystring .= "&anousu={$oPost->anousu}";
  $querystring .= "&quantidade={$oPost->quantidade}";
  $querystring .= "&processarmovimentacao={$oPost->processarmovimentacao}";
  $querystring .= "&ordem={$oPost->ordem}";
  $querystring .= "&especie={$oPost->especie}";
  $querystring .= "&imobiliaria={$oPost->imobiliaria}";
  $querystring .= "&loteamento={$oPost->loteamento}";
  $querystring .= "&filtroprinc={$oPost->filtroprinc}";
  $querystring .= "&barrasparc={$oPost->barrasparc}";
  $querystring .= "&barrasunica={$oPost->barrasunica}";
  $querystring .= "&totcheck={$oPost->totcheck}";
  $querystring .= "&vlrminunica={$oPost->vlrminunica}";
  $querystring .= "&intervalo={$oPost->intervalo}";
  $querystring .= "&vlrmaxunica={$oPost->vlrmaxunica}";
  $querystring .= "&vlrmin={$oPost->vlrmin}";
  $querystring .= "&vlrmax={$oPost->vlrmax}";

  echo " <script>  ";
  echo "   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_carne','cad4_emissaogeralipturecibos.php?$querystring','Processando Emissão...',true); ";
  echo " </script> ";
}
