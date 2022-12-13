<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$oGet     = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");
$ano_arquivo = DBPessoal::getAnoFolha();
$mes_arquivo = DBPessoal::getMesFolha();
?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/FormCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      #gridConsignados {
        width: 800px;
      }

      #processado td {
        text-align: center;
        color: red;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <form id="formConfiguracoesArquivosConfignados" method="POST">
        <fieldset>
          <legend>Conferência dos consignados:</legend>
          <table class="form-container">
            <tr>
              <td>
                <label for="tipo_lancamento">Tipo de Lançamento:</label>
              </td>
              <td>
                <?php
                  db_select('tipo_lancamento', array('S'=>'Selecione', 'M'=>'Manual', 'A'=>'Arquivo'), true, 1, "onchange='configuraTipoLancamento(this)'");//, $nomevar = "", $bgcolor = "")
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="banco">Banco:</label>
              </td>
              <td>
                <select id="banco" disabled="true"></select>
              </td>
            </tr>
            <tr>
              <td>
                <label id="lbl_rh01_regist" for="rh01_regist"><a href="javascript:void(0)"><?=$Lrh01_regist?></a></label>
              </td>
              <td>
                <?php
                  db_input('rh01_regist', 10, $Irh01_regist, true, "text", 1); 
                  db_input('z01_nome', 50, $Iz01_nome, true, "text", 3); 
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="ano_folha">Competência:</label>
              </td>
              <td>
                <?php db_input('ano_arquivo', 6, 0, true, 'text', 3); ?>
                /
                <?php db_input('mes_arquivo', 6, 0, true, 'text', 3); ?>
              </td>
            </tr>
            <tr id="processado" style="display: none">
              <td colspan="2" align="center">Arquivo já está processado nesta competência, ações não são permitidas.</td>
            </tr>
          </table>
        </fieldset>

        <input type="button" id="pesquisar" onclick="enviarDados()" name="pesquisar" value="Pesquisar" />
      </form>
    </div>
    

    <div class="container" style="width: 900px;">
      <fieldset>
        <legend>Consignados dos Servidores</legend>
        <div id="gridConsignados" style="width: 100%"></div>
      </fieldset>

    </div>
    <?php db_menu(); ?>
  </body>
</html>
<?php 
  $sMensagem  = "Este menu mudou para:\n";
  $sMensagem .= "Pessoal > Procedimentos > Manutenção de Empréstimos Consignados > Gestão de Consignados > Conferência de Dados\n";
  $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

  if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
    db_msgbox($sMensagem);
  }
?>
<script>
(function() {
  
  try {
    this.oConfiguracoesDatagridCollection = montarGrid();
  } catch (eError) {
    console.error(eError);
  }

  new DBLookUp($('lbl_rh01_regist'), $('rh01_regist'), $('z01_nome'), {sArquivo: 'func_rhpessoal.php'});
})();

function configuraTipoLancamento(node) {

  var oDadosRequisicaoBancos = {
    url :'pes4_importararquivoconsignado.RPC.php',
    acao: {exec:'getBancosConfigurados'}
  };

  switch(node.value) {
    case 'M':
      oDadosRequisicaoBancos.url       = 'pes4_manutencaocontratosconsignados.RPC.php';
      oDadosRequisicaoBancos.acao.exec = 'getBancos';
      getBancos(oDadosRequisicaoBancos);
      break;
      
    default:
      getBancos(oDadosRequisicaoBancos);
      break;
  }
}

function enviarDados() {

  if ($F('banco') == '') {

    alert('Nenhum Banco informado.');
    return false;
  }

  carregarDadosGrid();
}

function getBancos(oDadosRequisicaoBancos) {

  new AjaxRequest(oDadosRequisicaoBancos.url, oDadosRequisicaoBancos.acao, function(oResponse) {

    if (oResponse.erro) {
      if(oResponse.sMessage) {
        alert(oResponse.sMessage);
      }
      if(oResponse.messagem) {
        alert(oResponse.messagem);
      }
    }

    var oSelect = $('banco');
        oSelect.options.length = 0;

    oResponse.bancos.forEach(function(oBanco) {
      
       var oOption = new Option(oBanco.banco + ' - ' + oBanco.descricao, oBanco.banco);
       oSelect.add(oOption);
    });

    oSelect.disabled = false;

    if (oResponse.bancos.length > 0) {

      if($F('tipo_lancamento') == 'A') {
        carregarDadosGrid();
      }
    }

  }).setMessage('Buscando bancos...')
    .asynchronous(false)
    .execute();
}

function montarGrid() {

  var oConfiguracoesDatagridCollection = new DatagridCollection(new Collection().setId('iCodigoConsignado'), 'gridConsignados');

  oConfiguracoesDatagridCollection.configure({"height":"350", "width":"900px", "update":false, "delete":false});

  oConfiguracoesDatagridCollection.addColumn("rh152_nome", {"width": "300px"})
                                  .setOption("align","left")
                                  .setOption("label","Servidor");

  oConfiguracoesDatagridCollection.addColumn("rh153_valordescontar", {"width": "70px"})
                                  .setOption("align","center")
                                  .setOption("label","Valor");

  oConfiguracoesDatagridCollection.addColumn("rh153_parcela", {"width": "75px"})
                                  .setOption("align","center")
                                  .setOption("label","Parcela");

  oConfiguracoesDatagridCollection.addColumn("rh152_consignadomotivo", {"width": "240px"})
                                  .setOption("align","left")
                                  .setOption("label","Situação");

  oConfiguracoesDatagridCollection.addColumn("acao", {"width": "75px"})
                                  .setOption("align","center")
                                  .setOption("label","Ações");

  oConfiguracoesDatagridCollection.show($('gridConsignados'));

  return oConfiguracoesDatagridCollection;
}

function carregarDadosGrid() {

  var oParametros  = {};
      oParametros.exec  = 'getDados';
      oParametros.banco = $F(banco);

  if ($F('rh01_regist')) {
    oParametros.matricula = $F('rh01_regist');
  }
  AjaxRequest.create(
    getUrlAPI(),
    oParametros, 
    atualizarDadosGrid.bind(this)
  ).setMessage('Buscando Configurações...').execute();
}

function atualizarDadosGrid(response, erro) {

  this.oConfiguracoesDatagridCollection.collection.clear();

  if (erro) {

    this.oConfiguracoesDatagridCollection.reload();
    alert(response.mensagem.urlDecode());
    return;
  }

  var oCompetencia  = new DBViewFormularioFolha.CompetenciaFolha();
      oCompetencia.iAno = response.ano_arquivo;
      oCompetencia.iMes = response.mes_arquivo;
      oCompetencia.renderizaFormulario($('ano_arquivo'), $('mes_arquivo'));
      oCompetencia.desabilitarFormulario();

  if (response.arquivo_processado) {
    $('processado').show();
  }

  if(response.consignacoes.length > 0) {

    for (var consignado of response.consignacoes) {

      var lProcessado = $F('tipo_lancamento') == 'M' ? consignado.processado : response.arquivo_processado;
      this.oConfiguracoesDatagridCollection.collection.add({
        'iCodigoConsignado'     : consignado.codigo,
        'rh152_nome'            : consignado.matricula + ' - ' + consignado.nome.urlDecode(),
        'rh153_valordescontar'  : consignado.valor,
        'rh153_parcela'         : consignado.parcela,
        'rh152_consignadomotivo': consignado.descricao_motivo.urlDecode(),
        'acao'                  : montaBotaoAcao(consignado.motivo, consignado.codigo, lProcessado)
      });
    }
  }

  this.oConfiguracoesDatagridCollection.reload();
}

function movimentarRegistro(iCodigo) {
  
  var oParametros  = {};
  oParametros.exec = $F('tipo_lancamento') != 'M' ? 'salvar' : 'salvarConferencia'
  oParametros.codigo_registro = iCodigo;

  AjaxRequest.create(getUrlAPI(),
    oParametros,
    atualizarRegistro.bind(this)
  ).setMessage('Salvando Registro...').execute();
}

function atualizarRegistro(response, erro) {

  if (erro) {
    alert(response.mensagem);
  }

  oCollectionConsignado  = this.oConfiguracoesDatagridCollection.collection.get(response.codigo_registro);
  oCollectionConsignado.rh152_consignadomotivo = response.descricao_motivo.urlDecode();
  oCollectionConsignado.acao = montaBotaoAcao(response.motivo, response.codigo_registro);

  this.oConfiguracoesDatagridCollection.reload();
}

function montaBotaoAcao(iMotivo, iCodigo, lArquivoProcessado) {

  var oButton =  document.createElement("input");
      oButton.setAttribute("type", "button");
      oButton.setAttribute("value", "Excluir");
      oButton.setAttribute("onclick", 'movimentarRegistro('+iCodigo+')');

  if (iMotivo == 8) {
    oButton.setAttribute("value", "Incluir");
  }

  if ((!empty(iMotivo)  && iMotivo != 8) || lArquivoProcessado) {
    oButton.setAttribute("disabled", "true");
  }

  return oButton.outerHTML;
}

function getUrlAPI() {

  var url = 'pes4_conferenciaconsignados.RPC.php';
  if ($F('tipo_lancamento') == 'M') {
    url = 'pes4_manutencaocontratosconsignados.RPC.php';
  }
  return url;
}
</script>
