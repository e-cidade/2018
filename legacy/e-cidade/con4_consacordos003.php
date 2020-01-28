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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("model/Acordo.model.php");
require_once("model/AcordoComissao.model.php");
require_once("model/AcordoComissaoMembro.model.php");
require_once("model/CgmFactory.model.php");

$clrotulo = new rotulocampo;
$db_opcao = 3;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_depto");
$clrotulo->label("descrdepto");
$clrotulo->label("pc67_motivo");

$oGet     = db_utils::postMemory($_GET);
$clAcordo = new Acordo($oGet->ac16_sequencial);
if ($clAcordo->getInstit() != db_getsession('DB_instit') ) {

  $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
  $sMensagem = "Acordo de código {$oGet->ac16_sequencial} não pertence a instituição {$oInstituicao->getDescricao()}.";
  header("Location: db_erros.php?db_erro={$sMensagem}");
}

db_app::import("configuracao.DBDepartamento");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?php
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js,widgets/dbtextField.widget.js");
db_app::load("DBViewAcordoPrevisao.classe.js,widgets/dbtextFieldData.widget.js,classes/DBViewAcordoExecucao.classe.js, widgets/DBHint.widget.js");
db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
 .tdWidth   {width:150px;}
 .tdBgColor {background-color:#FFFFFF; color: #000000;}
 .fora      {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <center>
  <table width="100%">
  <tr>
  <td>
  <fieldset><legend><b>Dados Acordo</b></legend>
    <table width="100%">

      <tr>
        <td class="tdWidth">
          <b>Código:</b>
        </td>
        <td class="tdBgColor" width="150">
          <?php echo $clAcordo->getCodigoAcordo(); ?>
        </td>
        <td width="150">
          <b>Grupo:</b>
        </td>
        <td class="tdBgColor">
          <?php
            $oDaoAcordoGrupo       = db_utils::getDao("acordogrupo");
            $sWhereAcordoGrupo     = "ac02_sequencial = {$clAcordo->getGrupo()}";
            $sSqlAcordoGrupo       = $oDaoAcordoGrupo->sql_query_file(null, "ac02_descricao",
                                                                      null, $sWhereAcordoGrupo);
            $rsSqlAcordoGrupo      = $oDaoAcordoGrupo->sql_record($sSqlAcordoGrupo);
            $iNumRowsAcordoGrupo   = $oDaoAcordoGrupo->numrows;
            $sDescricaoAcordoGrupo = "Não definido!";
            if ($iNumRowsAcordoGrupo > 0) {

            	$oAcordoGrupo          = db_utils::fieldsMemory($rsSqlAcordoGrupo, 0);
            	$sDescricaoAcordoGrupo = $oAcordoGrupo->ac02_descricao;
            }
            echo "{$clAcordo->getGrupo()} - {$sDescricaoAcordoGrupo}";
          ?>
        </td>
      </tr>

      <tr>
        <td width="150">
          <b>Acordo:</b>
        </td>
        <td class="tdBgColor" width="150">
          <?php echo $clAcordo->getNumeroAcordo() . '/' . $clAcordo->getAno(); ?>
        </td>
        <td class="tdWidth">
          <b>Número:</b>
        </td>
        <td class="tdBgColor">
          <?php echo $clAcordo->getNumero() . '/' . $clAcordo->getAno(); ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth">
          <b>Origem:</b>
        </td>
        <td class="tdBgColor">
          <?php
            $iOrigem    = $clAcordo->getOrigem();
            $oDaoOrigem = db_utils::getDao("acordoorigem");
            $sSqlOrigem = $oDaoOrigem->sql_query($iOrigem);
            $rsOrigem   = $oDaoOrigem->sql_record($sSqlOrigem);
            $oOrigem    = db_utils::fieldsMemory($rsOrigem, 0);
            echo $iOrigem . " - " . $oOrigem->ac28_descricao;
          ?>
        </td>
        <td width="150">
          <b>Data da Assinatura:</b>
        </td>
        <td class="tdBgColor">
          <?php echo $clAcordo->getDataAssinatura(); ?>
        </td>
      </tr>
      <tr>
        <td class="tdWidth">
          <b>Situacao Atual:</b>
        </td>
        <td class="tdBgColor">
          <?php echo $clAcordo->getDescricaoSituacao(); ?>
        </td>
        <td width="150">
          <b>Período de Vigência:</b>
        </td>
        <td class="tdBgColor">
          <?php
            $oDataInicial  = $clAcordo->getDataInicialVigenciaOriginal();
            $oDataFinal    = $clAcordo->getDataFinalVigenciaOriginal();
            echo "{$oDataInicial->getDate(DBDate::DATA_PTBR)} até {$oDataFinal->getDate(DBDate::DATA_PTBR)}";
          ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth">
          <b>Tipo:</b>
        </td>
        <td class="tdBgColor">
          <?php echo $clAcordo->getDescricaoTipo(); ?>
        </td>
        <td width="150">
          <b>Depto. de Inclusão:</b>
        </td>
        <td class="tdBgColor">
          <?php
            $iDepartamento = $clAcordo->getDepartamento();
            $oDepartamento = new DBDepartamento($iDepartamento);
            echo "{$iDepartamento} - {$oDepartamento->getNomeDepartamento()}";
          ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth"><b>Lei:</b></td>
        <td class="tdBgColor" colspan="1"><?php echo $clAcordo->getLei();?></td>

        <td width="150">
          <b>Depto. Responsável:</b>
        </td>
        <td class="tdBgColor">
          <?php
          $iDepartamentoResponsavel = $clAcordo->getDepartamentoResponsavel();
          $oDepartamento            = new DBDepartamento($iDepartamentoResponsavel);
          echo "{$iDepartamentoResponsavel} - {$oDepartamento->getNomeDepartamento()}";
          ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth"><b>Valor Total:</b></td>
        <td class="tdBgColor" colspan="1">
          <?php echo db_formatar($clAcordo->getValorContrato(), 'f'); ?>
        </td>

        <td class="tdWidth"><b>Classificação:</b></td>
        <td class="tdBgColor" colspan="1">
          <?php echo $clAcordo->getClassificacao()->getDescricao(); ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth">
          <b>Contratado:</b>
        </td>
        <td colspan="" class="tdBgColor">
          <?php echo $clAcordo->getContratado()->getCodigo(); ?>
        </td>
        <td colspan="2" class="tdBgColor">
         <?php echo $clAcordo->getContratado()->getNome(); ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth"><b>Processo:</b></td>
        <td class="tdBgColor" colspan="3"><?php echo $clAcordo->getProcesso();?></td>
      </tr>

      <tr>
        <td class="tdWidth">
          <b>Categoria:</b>
        </td>
        <td colspan="4" class="tdBgColor" >
          <?php
            $iCategoria          = $clAcordo->getCategoriaAcordo();
            $oDAOAcordoCategoria = db_utils::getDao("acordocategoria");
            $sSqlAcordoCategoria = $oDAOAcordoCategoria->sql_query_file($iCategoria);
            $sRsAcordoCategoria  = $oDAOAcordoCategoria->sql_record($sSqlAcordoCategoria);

            if ($oDAOAcordoCategoria->numrows >0) {

              $oStdCategoria = db_utils::fieldsMemory($sRsAcordoCategoria, 0);
              echo $oStdCategoria->ac50_sequencial . " - " .$oStdCategoria->ac50_descricao;
            }
          ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth">
          <b>Objeto:</b>
        </td>
        <td colspan="4" class="tdBgColor" >
          <?php echo $clAcordo->getObjeto(); ?>
        </td>
      </tr>

      <tr>
        <td class="tdWidth">
          <b>Resumo do Objeto:</b>
        </td>
        <td class="tdBgColor" colspan="3"><?php echo $clAcordo->getResumoObjeto(); ?></td>
      </tr>

    </table>
 </fieldset>
 </td>
 </tr>
 </table>
 <fieldset>
    <?php
    $oTabDetalhes = new verticalTab("detalhesemp",300);

    $oTabDetalhes->add("itens", "Itens",
                       "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=itens");
    /*
     * verificamos se a consulta vem do menu Empenho > Consultas > Consulta Empenho
     * caso venha, nao exibimos o botao empenhamentos
     */

    if (!isset($lEmpenho)) {

      $oTabDetalhes->add("empenhamentos" , "Empenhamentos" ,
                       "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=empenhamentos");
    }

    switch($iOrigem) {

      case '1':

        $oTabDetalhes->add("empenhamentos", "Processo de Compras",
          "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=processodecompras");

        break;

      case '2':

        $oTabDetalhes->add("empenhamentos", "Licitações",
                           "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=licitacoes");

        break;
      case '3':

        // Manual

        break;
      case '4':

        // Interno

        break;

      case '5':

        // Custo Fixo

        break;


      case '6':

        $oTabDetalhes->add("empenhamentos", "Empenhos",
                           "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=empenhos");
        break;
    }

    $oTabDetalhes->add("posicoes" , "Posições" ,
                       "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=aditamentos");

    $oTabDetalhes->add("rescisoes" , "Rescisões" ,
                       "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=rescisoes");

    $oTabDetalhes->add("paralisacoes" ,
                       "Paralisações" ,
                       "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=paralisacoes");


    $oTabDetalhes->add("anulacoes" , "Anulações" ,
                       "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=anulacoes");
    $oTabDetalhes->add("documentos" , "Documentos" ,
                          "con4_consacordosdetalhes001.php?ac16_sequencial={$oGet->ac16_sequencial}&exec=documentos");

    $iCodigoComissao = $clAcordo->getComissao()->getCodigo();
    $oTabDetalhes->add("comissao" , "Comissões" ,
                          "con4_consacordosdetalhecomissao001.php?iComissao={$iCodigoComissao}");
    $oTabDetalhes->show();
    ?>

    </fieldset>
</center>
</body>
</html>

<script type="text/javascript">
function js_windowAditamentosDetalhes(aDados) {

    var iWidthGrid     = 790;
    var iWheigthGrid   = 330 ;

    oWindowGridDetalhesAditamento  = new windowAux('wndGridDetalhesAditamento', 'Ítens do Aditamento ',
                                                          iWidthGrid, iWheigthGrid);

    sContentGridAditamento  = "<div  id='ctnMessageBoardRua' style='text-align:center;padding:2px;width:99%;'>";
    sContentGridAditamento += "  <div style='width:100%' id='GridItens'>";
    sContentGridAditamento += "  </div>";
    sContentGridAditamento += "</div>";

    oWindowGridDetalhesAditamento.setContent(sContentGridAditamento);

    oWindowGridDetalhesAditamento.setShutDownFunction(function () {

      oWindowGridDetalhesAditamento.destroy();
    });


    oWindowGridDetalhesAditamento.show();
    /**
     * Defincao da Grid que exibe os complementos cadastrados para a rua e nuemro selecionado
     */
    oGrvDetalhesAditamento = new DBGrid('itens');
    oGrvDetalhesAditamento.nameInstance = 'oGrvDetalhesAditamento';

    oGrvDetalhesAditamento.setCellWidth(new Array('10%', '50%', '10%', '10%', '10%', '10%'));

    oGrvDetalhesAditamento.setCellAlign(new Array('right', 'left', 'right', 'right', 'right', 'right'));

    oGrvDetalhesAditamento.setHeader(new Array('Código', 'Descrição', 'Quantidade', 'Unidade', 'Valor Unitário',
                                               'Valor Total'));
    oGrvDetalhesAditamento.setHeight(230);

    oGrvDetalhesAditamento.show($('GridItens'));

    var iNumDados = aDados.length;

    oGrvDetalhesAditamento.clearAll(true);

    if (iNumDados > 0) {
      aItens=aDados;
      aDados.each(
                  function (oDado, iInd) {

                    var aRow = new Array();

                    aRow[0] = oDado.codigo;
                    aRow[1] = oDado.descricao.urlDecode();
                    aRow[2] = oDado.quantidade;
                    aRow[3] = oDado.unidade;
                    aRow[4] = oDado.vlrUnit;
                    aRow[5] = oDado.vlrTotal;

                    oGrvDetalhesAditamento.addRow(aRow);
                    oGrvDetalhesAditamento.aRows[iInd].sEvents += "onDblclick='js_showDadosItem("+iInd+")'";

                  }

                 );
      oGrvDetalhesAditamento.renderRows();

    }

}

function js_showDadosItem(iLinha) {
  js_showInfoItem(aItens[iLinha]);
}

function js_consultaEmpenho(iNumeroEmpenho) {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho001', 'func_empempenho001.php?e60_numemp=' + iNumeroEmpenho, 'Pesquisa Empenho', true);
}

function js_consultaLicitacao(iCodigoLicitacao) {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_infolic', 'lic3_licitacao002.php?l20_codigo=' + iCodigoLicitacao, 'Pesquisa Licitação', true);
}

function js_consultaProcessoCompras(iCodigoProcesso) {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_pesquisa_processo', 'com3_pesquisaprocessocompras003.php?pc80_codproc=' + iCodigoProcesso, 'Pesquisa Processo de Compras', true);
}

function js_showInfoItem(oDados) {

    var iWidthGrid     = 790;
    var iWheigthGrid   = 380 ;
    oWindowGridDetalhesItem  = new windowAux('wndGridDetalhesItem', 'Detalhamento do Item ',
                                                          iWidthGrid, iWheigthGrid);

    sContent = "  <div style='width:100%' id='ctnDados'>";
    sContent += "  <fieldset style='text-align:center;border:0px;border-top:2px groove white'>";
    sContent += "    <legend><b>Resumo Financeiro</b></legend>";
    sContent += "  <table>";
    sContent += "    <tr>";
    sContent += "      <td style='width:'10%;' nowrap>";
    sContent += "       <b>Valor Autorizado:</b>";
    sContent += "      </td>"
    sContent += "      <td id='ctnValorAutorizado' style='width:40%;background:white'>"
    sContent += "      </td>";
    sContent += "      <td style='width:'10%;' nowrap>";
    sContent += "       <b>Quantidade Autorizada:</b>";
    sContent += "      </td>"
    sContent += "      <td id='ctnQuantidadeAutorizado' style='width:40%;background:white'>";
    sContent += "      </td>";
    sContent += "   </tr>";
    sContent += "    <tr>";
    sContent += "      <td style='width:'10%;' nowrap>";
    sContent += "       <b>Valor Executado:</b>";
    sContent += "      </td>"
    sContent += "      <td id='ctnValorExecutado' style='width:40%;background:white'>"
    sContent += "      </td>";
    sContent += "      <td style='width:'10%;' nowrap>";
    sContent += "       <b>Quantidade Executado:</b>";
    sContent += "      </td>"
    sContent += "      <td id='ctnQuantidadeExecutada' style='width:40%;background:white'>";
    sContent += "      </td>";
    sContent += "   </tr>";
    sContent += "      <td style='width:'10%;' nowrap>";
    sContent += "       <b>Valor a Autorizar:</b>";
    sContent += "      </td>"
    sContent += "      <td id='ctnValorAutorizar' style='width:40%;background:white'>"
    sContent += "      </td>";
    sContent += "      <td style='width:'10%;' nowrap>";
    sContent += "       <b>Quantidade a Autorizar:</b>";
    sContent += "      </td>"
    sContent += "      <td id='ctnQuantidadeAutorizar' style='width:40%;background:white'>";
    sContent += "      </td>";
    sContent += "   </tr>";
    sContent += "  </table>";
    sContent += "  </fieldset>";
    sContent += "  </div>";
    sContent += "  <fieldset style='text-align:center;border:0px;border-top:2px groove white'>";
    sContent += "    <legend><b>Dotações</b></legend>";
    sContent += "  <div style='width:100%' id='cntgridDotacoes'>";
    sContent += "  </div>";
    sContent += "  </fieldset>";
    sContent += "</div>";

    oWindowGridDetalhesItem.setContent(sContent);

    oMessageBoard = new DBMessageBoard('msgboard1',
                                    'Detalhes do item:',
                                    '    '+oDados.descricao.urlDecode(),
                                    $('windowwndGridDetalhesItem_content')
                                    );
    oMessageBoard.show();
    oWindowGridDetalhesItem.setShutDownFunction(function () {

      oWindowGridDetalhesItem.destroy();
    });
    $('ctnValorAutorizado').innerHTML      = js_formatar(oDados.saldos.valorautorizado, 'f');
    $('ctnQuantidadeAutorizado').innerHTML = js_formatar(oDados.saldos.quantidadeautorizada, 'f');
    $('ctnValorExecutado').innerHTML       = js_formatar(oDados.saldos.valorexecutado, 'f');
    $('ctnQuantidadeExecutada').innerHTML  = js_formatar(oDados.saldos.quantidadeexecutada, 'f');
    $('ctnValorAutorizar').innerHTML       = js_formatar(oDados.saldos.valorautorizar, 'f');
    $('ctnQuantidadeAutorizar').innerHTML  = js_formatar(oDados.saldos.quantidadeautorizar, 'f');

    oGridDotacoes              = new DBGrid('gridDotacoes');
    oGridDotacoes.nameInstance = 'oGridDotacoes';
    oGridDotacoes.setCellWidth(new Array('30%', '30%', '30%', '10%'));
    oGridDotacoes.setCellAlign(new Array("center", "right", "right", "right"));
    oGridDotacoes.setHeader(new Array("Dotação", "Valor","Valor Util.", "Valor reservado", "Reserva"));
    oGridDotacoes.setHeight(oWindowGridDetalhesItem.getHeight()/3.2);
    oGridDotacoes.show($('cntgridDotacoes'));
    oGridDotacoes.clearAll(true);
    oDados.dotacoes.each(function (oDotacao, iDot) {

      aLinha    = new Array();
      aLinha[0] = "<a href='#' onclick='js_mostraSaldo("+oDotacao.dotacao+");return false'>"+oDotacao.dotacao+"</a>";
      aLinha[1] = js_formatar(oDotacao.valor, "f");
      aLinha[2] = js_formatar(oDotacao.executado, "f");
      aLinha[3] = js_formatar(oDotacao.valorreserva, "f");
      aLinha[4] = oDotacao.reserva;
      oGridDotacoes.addRow(aLinha);
   });

   oGridDotacoes.renderRows();
   oWindowGridDetalhesItem.show();
}

function js_detalhesAutorizacao(iCodigo) {

  var sQuery = '';
  sQuery     = 'e54_autori='+iCodigo;
  js_OpenJanelaIframe('parent','db_iframe_autorizacao',
                     'func_empempenhoaut001.php?'+sQuery,
                     'Detalhes Autorização',true);
}

function js_detalhesEmpenho(iCodigo) {

  var sQuery = '';
  sQuery     = 'e60_numemp='+iCodigo;
  js_OpenJanelaIframe('parent','db_iframe_empenho',
                     'func_empempenho001.php?'+sQuery,
                     'Detalhes Empenho',true);
}

function js_mostraSaldo(chave){

  arq = 'func_saldoorcdotacao.php?o58_coddot='+chave
  js_OpenJanelaIframe('top.corpo','db_iframe_saldos',arq,'Saldo da dotação',true);
  $('Jandb_iframe_saldos').style.zIndex='1500000';
}

function js_openPrevisao(iCodigo) {

  var iCodigoContrato    = iCodigo;
  var sDescricaoContrato = '<?php echo $clAcordo->getResumoObjeto();?>';

  oPrevisao              = new DBViewAcordoPrevisao(iCodigo, 'oPrevisao', 'Previsão de Execução do Contrato',
                                                    true, true, null, false);
  oPrevisao.onPeriodoClick = function (iPeriodo, iItem) {

    oExecucao = new DBViewAcordoExecucao(oPrevisao.aItens[iItem], iPeriodo, 'oExecucao', oPrevisao.wndAcordoPrevisao);
    oExecucao.show();
    oExecucao.setReadOnly(true);
    oExecucao.showTabs('fldsExecucoes');
    oExecucao.setTabs(new Array('tabfldsExecucoes'));

  }
  oPrevisao.show();
  oPrevisao.setAjuda('Previsões de execução do contrato '+iCodigo+' - '+sDescricaoContrato);
}
</script>
