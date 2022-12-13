<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

$oDataVencimento = new DBDate(date('Y-m-d'), strtotime('next Month'));

$Tgrupo                 = "Grupos:\n\nGrupos para cálculo das taxas";
$Tnaturezas             = "Naturezas:\n\nNaturezas à calcular taxas";
$Tdata_vencimento_geral = "Data de Vencimento:\n\nData sugerida para vencimento de todas as taxas à calcular";

?>

<html>
<head>
  <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load(array(
        "scripts.js",
        "prototype.js",
        "strings.js",
        "AjaxRequest.js",
        "datagrid.widget.js",
        "widgets/Collection.widget.js",
        "widgets/DatagridCollection.widget.js",
        "widgets/DBHint.widget.js",
        "widgets/datagrid/plugins/DBHint.plugin.js",
        "../ext/javascript/prototype.maskedinput.js",
        "widgets/Input/DBInput.widget.js",
        "widgets/Input/DBInputDate.widget.js",
        "estilos.css",
        "grid.style.css"
      ));
    ?>
</head>
<body>
<div class="container">
  <form method="POST" id="formTaxa">
    <fieldset>
      <legend>Filtros</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?php echo $Tgrupo; ?>">
            <label id="lbl_grupo" for="grupos">Grupo:</label>
          </td>
          <td><?php db_select('grupos', array('S'=>'Selecione'), true, 1, 'onchange="popularComboNatureza(event)"'); ?></td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tnaturezas; ?>">
            <label id="lbl_naturezas" for="naturezas">Natureza:</label>
          </td>
          <td><?php db_select('naturezas', array('S'=>'Selecione'), true, 1, 'onchange="carregarTaxas(event)"'); ?></td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tdata_vencimento_geral; ?>">
            <label id="lbl_data_vencimento_geral" for="data_vencimento_geral">Data de Vencimento:</label>
          </td>
          <td><input type="text" id='data_vencimento_geral' name='data_vencimento_geral' onchange="atualizarDataVencimentoGrid(this)" value="<?php echo $oDataVencimento->getDate(DBDate::DATA_PTBR)?>" /> </td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<div class="container">
  <fieldset>
    <legend>Taxas</legend>
    <div id="gridTaxas" style="width:960px"></div>
  </fieldset>
  <input type="button" value="Calcular"    id="btnCalcular"    onclick="calcular()" >
</div>
<script type="text/javascript">

var oCollectionTaxas     = Collection.create().setId('codigo');
var oGridTaxas           = montarGrid(oCollectionTaxas, 'gridTaxas');
var oDataVencimentoGeral = DBInputDate.create($('data_vencimento_geral'));
    oDataVencimentoGeral.inputElement.observe('keyup', function(event){
      
      if(this.value.match(/^\d{2}\/\d{2}\/\d{4}$/)){
        oDataVencimentoGeral.inputElement.dispatchEvent(new Event('change'));
      }
    });

buscaGrupos();
function buscaGrupos() {

  AjaxRequest.create(
    'fis1_taxadiversos.RPC.php',
    {exec: 'getGrupos'},
    function(oRetorno, lErro) {

      if(lErro === true) {

        alert(oRetorno.sMessage);
        return;
      }

      $('grupos').length = 0;
      $('grupos').add(new Option('Selecione', ''));
      $('grupos').add(new Option('Todos', 'T'));

      oRetorno.aGrupos.each(function(oGrupo) {
        $('grupos').add(new Option(oGrupo.descricao, oGrupo.codigo));
      });
    }
  ).setMessage('Aguarde, buscando os grupos...')
    .execute();
}

function popularComboNatureza () {

  $('naturezas').length = 0;
  $('naturezas').add(new Option('Selecione', 'S'));
  popularGrid([], true);

  if($F('grupos') == '') {

    alert('Selecione um grupo para calcular as taxas');
    return;
  }

  AjaxRequest.create(
    'fis1_taxadiversos.RPC.php',
    {
      exec   : 'getNaturezas',
      iGrupo : $F('grupos')
    },
    function (oRetorno) {

      if(oRetorno.lErro) {
        alert(oRetorno.sMessage);
        return;
      }

      oRetorno.aNaturezas.each(function (item, i) {

        option           = document.createElement('option');
        option.value     = item.codigo;
        option.innerHTML = item.descricao.substr(0, 30);

        if(item.descricao.length > 30){
          option.innerHTML += '...';
        }

        $('naturezas').appendChild(option);
      });
    }
  ).setMessage('Buscando naturezas de taxas...').execute();
}

function montarGrid(oCollectionTaxas, sIdGrid) {

  var oGrid = new DatagridCollection(oCollectionTaxas, sIdGrid);

      oGrid.configure({'height':'300', 'width':'960', 'update':false, 'delete':false, 'order':false});
      
      oGrid.addColumn('cgm')
           .setOption('width', '235px')
           .setOption('align', 'left')
           .setOption('label', 'Contribuinte')
           .transform(function (cgm, itemCollection) {

             var sNome = 'CGM: ' + cgm +' - '+ itemCollection.cgm_nome;

             if(itemCollection.inscricao_municipal != null) {
               sNome = 'Inscr.: ' + itemCollection.inscricao_municipal +' - '+ itemCollection.cgm_nome;
             }

             if(sNome.length > 33) {
               sNome = sNome.substring(0, 33) + '...';
             }

             return sNome;
           });

      oGrid.addColumn('natureza')
           .setOption('width', '170px')
           .setOption('align', 'left')
           .setOption('label', 'Natureza')
           .transform(function (natureza) {
              
              if(natureza.length > 20) {
                return natureza.substring(0, 20) + '...';
              }
              return natureza;
           });

      oGrid.addColumn('unidade')
           .setOption('width', '80px')
           .setOption('align', 'center')
           .setOption('label', 'Unidade')

      oGrid.addColumn('data_fim')
           .setOption('width', '80px')
           .setOption('align', 'center')
           .setOption('label', 'Data Final');

      oGrid.addColumn('data_vencimento')
           .setOption('width', '130px')
           .setOption('align', 'center')
           .setOption('label', 'Data de Vencimento')
           .transform(function (data_vencimento, itemCollection) {
              return '<input type="text" data-id="'+itemCollection.ID+'" class="input-data field-size2" style="text-align: center;" name="data_vencimento" value="'+ itemCollection.data_vencimento +'"/>';
           });

      oGrid.addColumn('status')
           .setOption('width', '110px')
           .setOption('align', 'center')
           .setOption('label', 'Status');

      oGrid.grid.setCheckbox(0);
      oGrid.show($(sIdGrid));

	  carregarTaxas();

  return oGrid;
}

function carregarTaxas (e) {

  var natureza = $F('naturezas');

  if(typeof e == 'undefined') {
    return;
  }

  if(e) {

    if(natureza == 'S') {
      alert('Selecione uma natureza para calcular as taxas.');
      popularGrid([], true);
      return
    }
  }

  AjaxRequest.create(
    'fis1_taxadiversos.RPC.php',
    {
      exec     : 'getTaxasCalcular',
      iGrupo   : $F('grupos'),
      natureza : natureza
    },
    function (oRetorno) {

      popularGrid(oRetorno.aLancamentos);
    }
  ).setMessage('Buscando...').execute();
}

function calcular() {

  var aTaxasCalcular    = [];
  var lTemItemCalculado = false;

  oGridTaxas.grid.getSelection('object').each(function (item) {

    if(item.itemCollection.calculou === true) {
      lTemItemCalculado = true;
    }

    aTaxasCalcular.push({
      idTaxa         : item.itemCollection.ID,
      dataVencimento : item.itemCollection.data_vencimento
    });
  });

  var sMensagem  = 'Foram selecionadas taxas já calculadas. Deseja continuar e realizar um novo cálculo?';
      sMensagem += " (lembrando que um novo débito será gerado)";

  if(lTemItemCalculado && !confirm(sMensagem)) {
    return;
  }

  if(aTaxasCalcular.length == 0) {

    alert('Nenhuma taxa selecionada para cálculo.');
    return;
  }

  AjaxRequest.create(
    'fis1_taxadiversos.RPC.php',
    {
      exec            : 'calcularTaxasGeral',
      aTaxas          : aTaxasCalcular
    },
    function (oRetorno) {

      if(!empty(oRetorno.sMessage)) {
        alert(oRetorno.sMessage);
      }

      if (oRetorno.lErro) {
        return false;
      }

      popularGrid(oRetorno.aTaxas);
    }
  ).setMessage('Calculando...').execute();
}

function popularGrid(aTaxas, lLimparGrid) {

  if(lLimparGrid) {
    oGridTaxas.clear();
  }

  if(aTaxas.length == 0) {
    oGridTaxas.collection = Collection.create().setId('codigo');
  }

  aTaxas.forEach(function(oTaxa, iLinha) {
    oGridTaxas.collection.add(oTaxa);
  });

  oGridTaxas.collection.sort('ASC', ['natureza', 'codigo']);
  oGridTaxas.reload();
  
  oGridTaxas.collection.get().forEach(function(oTaxa, iLinha) {

    if(oTaxa.datagridRow.aCells[1].content.length > 33) {
      oGridTaxas.grid.setHint(iLinha, 1, oTaxa.cgm_nome);
    }

    if(oTaxa.natureza.length > 20) {
      oGridTaxas.grid.setHint(iLinha, 2, oTaxa.natureza);
    }

    if(oTaxa.status_hint) {
      oGridTaxas.grid.setHint(iLinha, 6, oTaxa.status_hint);
    }
  });

  new MaskedInput('.input-data', '99/99/9999', {completed: atualizarDataVencimento});

  itens = document.getElementsByClassName('input-data');

  for(var item of itens) {
    item.addEventListener('keyup', atualizarDataVencimento);
  };
}

function atualizarDataVencimento (e) {

  var itemCollection = oCollectionTaxas.get(this.getAttribute('data-id'));
        itemCollection.data_vencimento = this.value;
        oCollectionTaxas.add(itemCollection);
}

function atualizarDataVencimentoGrid (nodeData) {
  
  for(var item of oGridTaxas.collection.get()) {
    item.data_vencimento = nodeData.value;
  };

  oGridTaxas.reload();
}
</script>
<?php db_menu(); ?>
</body>
</html>