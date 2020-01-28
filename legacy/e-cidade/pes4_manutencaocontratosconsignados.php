<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

 require(modification("libs/db_stdlib.php"));
 require(modification("libs/db_conecta.php"));
 include(modification("libs/db_sessoes.php"));
 include(modification("libs/db_usuariosonline.php"));
 include(modification("dbforms/db_funcoes.php"));

 $clrotulo = new rotulocampo;
 $oDaoRhConsignadoMovimento                = new cl_rhconsignadomovimento();
 $oDaoRhConsignadoMovimentoServicorRubrica = new cl_rhconsignadomovimentoservidorrubrica();
 $oDaoRhConsignadoMovimento->rotulo->label();
 $oDaoRhConsignadoMovimentoServicorRubrica->rotulo->label();
 $clrotulo->label("rh01_regist");
 $clrotulo->label("z01_nome");
 $clrotulo->label("db90_descr");
 $clrotulo->label("rh27_descr");
 $clrotulo->label("rh27_rubric");
?>
<html>
  <head>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("strings.js");
      db_app::load("AjaxRequest.js");
      db_app::load("widgets/DBLookUp.widget.js");
      db_app::load("datagrid.widget.js");
      db_app::load("widgets/Collection.widget.js");
      db_app::load("widgets/DatagridCollection.widget.js");
      db_app::load("widgets/FormCollection.widget.js");
      db_app::load("widgets/FormCollection.widget.js");
      db_app::load("widgets/Input/DBInput.widget.js");
      db_app::load("widgets/Input/DBInputValor.widget.js");
      db_app::load("widgets/Input/DBInputInteger.widget.js");
      db_app::load("widgets/Input/DBInputInteger.widget.js");
      db_app::load("classes/DBViewFormularioFolha/CompetenciaFolha.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body>
    <div class="container">
      <form method="POST" id="frmConsignados">
        <?php
          db_input('iCodigoConsignado', 10, $Irh01_regist, true, "hidden", 1); 
          db_input('iCodigoOrigem', 10, $Irh01_regist, true, "hidden", 1); 
        ?>
        <fieldset class="container">
          <Legend>Gestão de Consignados</Legend>
          <table class="form-container">
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
                <label id="lbl_rh151_banco" for="rh151_banco"><a href="javascript:void(0)"><?=$Lrh151_banco?></a></label>
              </td>
              <td>
                <?php
                  db_input('rh151_banco', 10, $Irh151_banco, true, "text", 1, "data='db90_codban'"); 
                  db_input('db90_descr', 50, $Idb90_descr, true, "text", 3); 
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label id="lbl_rh153_rubrica" for="rh153_rubrica"><a href="javascript:void(0)"><?=$Lrh153_rubrica?></a></label>
              </td>
              <td>
                <?php
                  db_input('rh153_rubrica', 10, $Irh27_rubric, true, "text", 1, "data='rh27_rubric'");
                  db_input('rh27_descr', 50, $Irh27_descr, true, "text", 3); 
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label id="lbl_rh153_valordescontar" for="rh153_valordescontar"><?=$Lrh153_valordescontar?></label>
              </td>
              <td>
                <input type="text" name="rh153_valordescontar" id="rh153_valordescontar" />
              </td>
            </tr>
            <tr>
              <td>
                <label id="lbl_parcelas" for="rh153_parcela">Parcelas:</label>
              </td>
              <td>
                <input type="text" name="rh153_parcela" id="rh153_parcela" />
                <label for="rh153_totalparcelas">de: </label>
                <input type="text" name="rh153_totalparcelas" id="rh153_totalparcelas" />
              </td>
            </tr>
            <tr>
              <td><label>Competência: </label></td>
              <td><div id="formularioCompetencia"></div></td>
            </tr>

          </table>
        </fieldset>
        <input type="button" value="Salvar" onclick="salvar()" id="btnProcessar">
        <input type="button" value="Novo" id="btnNovo">
      </form>
    </div>
    <div class="container">
      <fieldset>
        <legend>
          Empréstimos do Servidor
        </legend>
        <div id="gridConsignados" style="width:1050px"></div>
      </fieldset>
    </div>
  </body>
</html>
<?php
db_menu();
?>

<style>
  #gridConsignados tr td {
    height: 23px;
  }
</style>

<script>

var oConfiguracoesDatagridCollection;

var oLookupBanco = '';

(function(){

  $('btnNovo').observe('click', function () {

    $('btnProcessar').value = 'Salvar';
    $('btnProcessar').setAttribute('onclick', 'salvar()');
    $('frmConsignados').reset();
    $('rh151_banco').disabled = false;
    $('iCodigoConsignado').value = '';
    $('iCodigoOrigem').value     = '';
    oLookupBanco.habilitar();
    getContratos();
  });

  var oValor         = new DBInputValor($('rh153_valordescontar'));
      oValor.getElement().style.width = '83px';

  var oParcela       = new DBInputInteger($('rh153_parcela'));
      oParcela.getElement().style.width = '83px';
      oParcela.getElement().maxLength   = '3';

  var oTotalParcelas = new DBInputInteger($('rh153_totalparcelas'));
      oTotalParcelas.getElement().style.width = '83px';
      oTotalParcelas.getElement().maxLength   = '3';

  var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
      oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));


  montaAncoras();

  try {
    oConfiguracoesDatagridCollection = montaGrid();
  } catch (eError) {
    console.error(eError);
  }
})();

function montaAncoras() {
  
  // Matricula.
  var oMatricula = new DBLookUp($('lbl_rh01_regist'), $('rh01_regist'), $('z01_nome'), { sArquivo: 'func_rhpessoal.php',
                                                                                         fCallBack: function() {
                                                                                            getContratos();
                                                                                         }
                                                                                        });
                                                                                        
  // Código Banco.
  oLookupBanco =  new DBLookUp($('lbl_rh151_banco'), $('rh151_banco'), $('db90_descr'), {sArquivo: 'func_db_bancos.php'});
  // Rubrica
  var oLookRubruca = new DBLookUp($('lbl_rh153_rubrica'), $('rh153_rubrica'), $('rh27_descr'), {sArquivo: 'func_rhrubricas.php'});
}

function montaGrid() {

  oConfiguracoesDatagridCollection = new DatagridCollection(new Collection().setId('iCodigoConsignado'), 'gridConsignados');

  oConfiguracoesDatagridCollection.configure({"height":"250", "width":"10950", "update":false, "delete":false});

  oConfiguracoesDatagridCollection.addColumn("iCodigoConsignado", {"width": "42px"})
                                  .setOption("align","center")
                                  .setOption("label","Cód.");

  oConfiguracoesDatagridCollection.addColumn("banco", {"width": "200px"})
                                  .setOption("align","left")
                                  .setOption("label","Banco");

  oConfiguracoesDatagridCollection.addColumn("situacao", {"width": "130px"}) 
                                  .setOption("align","center")
                                  .setOption("label","Situação")
                                  .transform(function(sValor, iItemCollection) {
                                    
                                    var sSituacao = 'Ativo';

                                    switch(sValor) {
                                      case 'R':
                                        sSituacao = 'Refinanciado: Cód. ' + iItemCollection.origem;
                                        break;
                                      case 'P':
                                        sSituacao = 'Portado: Cód. ' + iItemCollection.origem;
                                        break;
                                      case 'C':
                                        sSituacao = 'Cancelado';
                                        break;
                                      case 'I':
                                        sSituacao = 'Inativo';
                                        break;
                                    }

                                    return sSituacao;
                                  });

  oConfiguracoesDatagridCollection.addColumn("rubrica", {"width": "210px"})
                                  .setOption("align","left")
                                  .setOption("label","Rubrica");                                 

  oConfiguracoesDatagridCollection.addColumn("valor", {"width": "50px"})
                                  .setOption("align","center")
                                  .setOption("label","Valor");

  oConfiguracoesDatagridCollection.addColumn("parcelas", {"width": "52px"})
                                  .setOption("align","center")
                                  .setOption("label","Parcela");

  oConfiguracoesDatagridCollection.addColumn("acao", {"width": "75px"})
                                  .setOption("align","center")
                                  .setOption("label","Ações")
                                  .transform(function(valor, itemCollection) {
                                    return getOpcoes(itemCollection);
                                  });
  

  oConfiguracoesDatagridCollection.show($('gridConsignados'));

  return oConfiguracoesDatagridCollection;
}

function getContratos() {

  var iMatricula = $F('rh01_regist');

  oConfiguracoesDatagridCollection.collection.clear();
  oConfiguracoesDatagridCollection.reload();

  if (empty(iMatricula)) {
    return false;
  }

  var oAjaxRequest = new AjaxRequest('pes4_manutencaocontratosconsignados.RPC.php', {exec: 'getContratos', iMatricula: iMatricula}, function(oRetorno) {

    if(!empty(oRetorno.mensagem)) {
      alert(oRetorno.mensagem);
    }

    if (oRetorno.erro) {
      return false;
    }

    oRetorno.aContratos.forEach(function(oContrato) {

      oConfiguracoesDatagridCollection.collection.add({
        'iCodigoConsignado': oContrato.iCodigoConsignado,
        'banco': oContrato.sBanco,
        'situacao': oContrato.sSituacao,
        'rubrica': oContrato.sRubrica + ' - ' + oContrato.sDescricaoRubrica,
        'valor': oContrato.nValor,
        'parcelas': oContrato.iParcela + '/' + oContrato.iTotalParcela,
        'historico': oContrato.lHistorico,
        'origem': oContrato.iCodigoOrigem
      });
    });

    oConfiguracoesDatagridCollection.reload();
  });

  oAjaxRequest.setMessage('Buscando consignado...');
  oAjaxRequest.execute();
}

function getOpcoes(oItemCollection) {

  var oSelect = document.createElement("select");
      oSelect.id = 'opcoes_' + oItemCollection.iCodigoConsignado;

  var aOpcoes = [
    'Selecione',
    'Alterar',
    'Excluir',
    'Cancelar',
    'Refinanciar',
    'Portar',
    'Histórico'
  ];

  if (!oItemCollection.historico) {
    return '';
  }

  aOpcoes.forEach(function(sOpcao, iIndice) {

    var oOpcao   = document.createElement('option'); 

    oOpcao.text  = sOpcao;
    oOpcao.value = iIndice;

    if(oItemCollection.situacao == 'C' && sOpcao.toLowerCase() != 'selecione' && sOpcao.toLowerCase() != 'histórico') {
      oOpcao.disabled = true
    }

    oSelect.add(oOpcao);
  });

  oSelect.setAttribute('onchange', 'eventos(this, '+oItemCollection.iCodigoConsignado+')');

  return oSelect.outerHTML;
}

function eventos(oOpcao, iConsignado) {

  if (oOpcao.value != 0 && oOpcao.value != 6) {
    getDadosConsignado(iConsignado, oOpcao.value);
    $('rh151_banco').disabled = false;
    oLookupBanco.habilitar();
  }

  switch (oOpcao.value) {

    case '1':
        
      $('btnProcessar').value = 'Alterar';
      $('btnProcessar').setAttribute('onclick', 'salvar()');
      break;
    case '2':

      $('btnProcessar').value = 'Excluir';
      $('btnProcessar').setAttribute('onclick', 'cancelar()');
      break;
    case '3':
      
      $('btnProcessar').value = 'Cancelar';
      $('btnProcessar').setAttribute('onclick', 'cancelar()');
      break;
    case '4':
    
      $('rh151_banco').disabled = true;
      $('btnProcessar').value   = 'Refinanciar';
      $('iCodigoOrigem').value  = iConsignado;
      $('btnProcessar').setAttribute('onclick', 'salvar()');
      oLookupBanco.desabilitar();
      break;
    case '5':

      $('btnProcessar').value = 'Portar';
      $('iCodigoOrigem').value = iConsignado;
      $('btnProcessar').setAttribute('onclick', 'salvar()');
      break;
    case '6':
      
      getHistorico(iConsignado);
      break;
  }
}

function getDadosConsignado(iConsignado, iAcao) {
  
  var oAjaxRequest = new AjaxRequest('pes4_manutencaocontratosconsignados.RPC.php', {exec: 'getContrato', iCodigoConsignado: iConsignado}, function(oRetorno) {

    $('frmConsignados').reset();

    if (!empty(oRetorno.mensagem)) {
      alert(oRetorno.mensagem);
    }

    if (oRetorno.erro) {
      return false;
    }

    var oContrato = oRetorno.oContrato;
    
    $('iCodigoConsignado').value    = oContrato.iCodigoConsignado
    $('rh01_regist').value          = oContrato.iMatricula;
    $('z01_nome').value             = oContrato.sServidor;
    $('ano').value                  = oContrato.iAno;
    $('mes').value                  = oContrato.iMes;

    if (iAcao != 5) {
      $('rh151_banco').value          = oContrato.iBanco;
      $('db90_descr').value           = oContrato.sBanco;
    }

    if (iAcao != 4 && iAcao != 5) {

      $('rh153_rubrica').value        = oContrato.sRubrica;
      $('rh27_descr').value           = oContrato.sDescricaoRubrica;
      $('rh153_valordescontar').value = oContrato.nValor;
      $('rh153_parcela').value        = oContrato.iParcela;
      $('rh153_totalparcelas').value  = oContrato.iTotalParcela;
    }
  });

  oAjaxRequest.setMessage('Buscando dados do contrato...');
  oAjaxRequest.execute();
}

function salvar() {

  if ($F('rh01_regist') == '' || $F('z01_nome') == '') {

    alert('Matrícula não informada.'); 
    return false;
  }

  if ($F('rh151_banco') == '' || $F('db90_descr') == '') {

    alert('Banco não informado.'); 
    return false;
  }

  if ($F('rh153_rubrica') == '' || $F('rh27_descr') == '') {

    alert('Rubrica não informada.'); 
    return false;
  }

  if ($F('rh153_valordescontar') == '') {

    alert('Valor da parcela deve ser informada.'); 
    return false;
  }

  if ($F('rh153_parcela') == '' || $F('rh153_totalparcelas') == '') {

    alert('Quantidade de Parcelas devem ser informadas.'); 
    return false;
  }

  if (parseInt($F('rh153_parcela')) > parseInt($F('rh153_totalparcelas'))) {

    alert('Parcela inicial não pode ser maior que o total de parcelas.');
    return false;
  }

  if ($F('ano') == '' || $F('mes') == '') {

    alert('Competência deve ser informada.'); 
    return false;
  }

  var oDadosContrato  = {};
  oDadosContrato.exec = 'salvar';

  oDadosContrato.iCodigoConsignado = null;
  oDadosContrato.iMatricula        = $F('rh01_regist');
  oDadosContrato.iBanco            = $F('rh151_banco');
  oDadosContrato.sRubrica          = $F('rh153_rubrica');
  oDadosContrato.nValor            = $F('rh153_valordescontar');
  oDadosContrato.iParcelaInicial   = $F('rh153_parcela');
  oDadosContrato.iParcelas         = $F('rh153_totalparcelas');
  oDadosContrato.iAno              = $F('ano');
  oDadosContrato.iMes              = $F('mes');

  if ($F('iCodigoConsignado') != '') {
    oDadosContrato.iCodigoConsignado = $F('iCodigoConsignado');
  }

  if ($F('iCodigoOrigem') != '') {
    oDadosContrato.iCodigoOrigem     = $F('iCodigoOrigem');
    oDadosContrato.iCodigoConsignado = null;
  }

  var oAjaxRequest = new AjaxRequest('pes4_manutencaocontratosconsignados.RPC.php', oDadosContrato, function(oRetorno) {
    
    if (!empty(oRetorno.mensagem)) {
      alert(oRetorno.mensagem);
    }

    if (oRetorno.erro) {
      return false;
    }

    getContratos();

    $('frmConsignados').reset();
    $('btnProcessar').value = 'Salvar';
    $('btnProcessar').setAttribute('onclick', 'salvar()');
    $('iCodigoConsignado').value = '';
    $('iCodigoOrigem').value     = '';
  }.bind(this));

  oAjaxRequest.setMessage('Salvando contrato...');
  oAjaxRequest.execute();
}

function getHistorico(iConsignado) {

  window.open('pes2_historicoconsignado002.php?codigoconsignado='+iConsignado, null, 'location=0');
  return false;
}

function cancelar() {

  var iCodigoConsignado = $F('iCodigoConsignado');

  sAcao = 'cancelar';
  if($F('btnProcessar') == 'Excluir') {
    sAcao = 'excluir';
  }

  var oAjaxRequest = new AjaxRequest('pes4_manutencaocontratosconsignados.RPC.php', {exec: sAcao, iCodigoConsignado: iCodigoConsignado}, function(oRetorno) {

    if(sAcao == 'excluir' && oRetorno.lProcessado) {
      if(confirm(oRetorno.mensagem)) {
        $('btnProcessar').value = 'Cancelar';
        cancelar()
      }
    } else {      

      if (!empty(oRetorno.mensagem)) {
        alert(oRetorno.mensagem);
      }
    }

    if (oRetorno.erro) {
      return false;
    }

    getContratos();
    
    $('frmConsignados').reset();
    $('btnProcessar').value = 'Salvar';
    $('btnProcessar').setAttribute('onclick', 'salvar()');
    $('iCodigoConsignado').value = '';
    $('iCodigoOrigem').value     = '';
  });

  oAjaxRequest.setMessage('Cancelando contrato...');
  oAjaxRequest.execute();
}

</script>