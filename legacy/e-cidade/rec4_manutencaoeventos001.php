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

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$horasExtras = Evento::$horasExtrasPermitidas;
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <title>DBSeller Sistemas Integrados</title>
</head>
<body>
<div class="container">
  <form name="formEvento" id="formEvento">
    <br />
    <fieldset style="width: 600px;">
      <legend class="bold">Manutenção de Eventos</legend>
      <input type="hidden" value="" id="codigoEvento" readonly/>
      <table width="100%">
        <tr>
          <td style="width: 60px;"><label for="titulo"><b>Título:</b></label></td>
          <td>
            <?php
            $Stitulo = 'Título';
            db_input('titulo', 70, 0, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td><label for="dataInicial"><b>Data:</b></label></td>
          <td>
            <input id="dataInicial" />
          </td>
        </tr>
      </table>
      <fieldset class="separator">
        <legend class="bold">Horários</legend>
        <table width="100%" border='0'>
          <tr>
            <td class="bold" style="width: 60px"><label for="entrada_1">Entrada 1:</label></td>
            <td>
              <input id="entrada_1" size="5"/>
            </td>
            <td class="bold" style="width: 60px; text-align: right""><label for="saida_1">Saída 1:</label></td>
            <td>
              <input id="saida_1" size="5"/>
            </td>
            <td class="bold" style="width: 100px; text-align: right""><label for="horaExtra_1">Hora Extra 1:</label></td>
            <td>
              <select id="horaExtra_1" style="width: 100%">
                <option value="<?php echo BaseHora::HORAS_EXTRA50;?>">50%</option>
                <option value="<?php echo BaseHora::HORAS_EXTRA75;?>">75%</option>
                <option value="<?php echo BaseHora::HORAS_EXTRA100;?>" selected>100%</option>
              </select>
            </td>
          </tr>

          <tr>
            <td class="bold" style="width: 60px"><label for="entrada_2">Entrada 2:</label></td>
            <td>
              <input id="entrada_2" size="5"/>
            </td>
            <td class="bold" style="width: 60px; text-align: right"><label for="saida_2">Saída 2:</label></td>
            <td>
              <input id="saida_2" size="5"/>
            </td>
            <td class="bold" style="width: 100px; text-align: right""><label for="horaExtra_2">Hora Extra 2:</label></td>
            <td>
              <select id="horaExtra_2" style="width: 100%">
                <option value="<?php echo BaseHora::HORAS_EXTRA50;?>">50%</option>
                <option value="<?php echo BaseHora::HORAS_EXTRA75;?>">75%</option>
                <option value="<?php echo BaseHora::HORAS_EXTRA100;?>" selected>100%</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <div id="ctnLancadorMatricula"></div>
    </fieldset>
    <p>
      <input type="button" value="Salvar" id="btnSalvar" onclick="salvarEvento()" />
      <input type="button" value="Limpar" id="btnLimpar" onclick="limparFormulario()" />
    </p>
  </form>
</div>
<div class="container">
  <fieldset style="width: 900px">
    <legend class="bold">Eventos Cadastrados</legend>
    <div id="ctnGridEventos"></div>
  </fieldset>
</div>
<?php db_menu(); ?>
</body>
</html>

<script type="text/javascript">

  const RPC_MANUTENCAO_EVENTO = 'rec4_manutencaoeventos.RPC.php';

  var input = {
    codigo       : $('codigoEvento'),
    titulo       : $('titulo'),
    dataInicial  : $('dataInicial'),
    dataFinal    : $('dataInicial'), /* se mantem a data inicial para posterior refatoração */
    entradaUm    : $('entrada_1'),
    tipoHoraUm   : $('horaExtra_1'),
    saidaUm      : $('saida_1'),
    entradaDois  : $('entrada_2'),
    saidaDois    : $('saida_2'),
    tipoHoraDois : $('horaExtra_2')
  };

  new DBInputHora(input.entradaUm);
  new DBInputHora(input.saidaUm);
  new DBInputHora(input.entradaDois);
  new DBInputHora(input.saidaDois);
  new DBInputDate(input.dataInicial);

  var oLancadorMatricula = new DBLancador('oLancadorMatricula');
  oLancadorMatricula.setLabelAncora('Matrícula:');
  oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
  oLancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
  oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
  oLancadorMatricula.setTextoFieldset('Matrículas');
  oLancadorMatricula.setGridHeight(150);
  oLancadorMatricula.show($('ctnLancadorMatricula'));

  var eventosCadastrados = new Collection();
  eventosCadastrados.setId('codigo');

  var gridEventosCadastrados = new DatagridCollection(eventosCadastrados, 'gridMatriculas').configure({'order' : false});
  gridEventosCadastrados.addColumn('codigo', {width: '10%', 'label' : 'Código'});
  gridEventosCadastrados.addColumn('titulo', {width: '40%', 'label' : "Título"});
  gridEventosCadastrados.addColumn('data', {width: '10%', 'label' : "Data"}).configure({'align' : 'center'});
  gridEventosCadastrados.addColumn('matriculas', {width: '30%', 'label' : "Matrículas"});
  gridEventosCadastrados.addColumn('acoes', {width: '10%', 'label' : "Ações"}).configure({'align' : 'center'});
  gridEventosCadastrados.configure({'height' : '200px'});
  gridEventosCadastrados.hideColumns([0]);
  gridEventosCadastrados.show($('ctnGridEventos'));



  /**
   * Limpa os dados digitados no formulário
   */
  function limparFormulario() {

    input.codigo.value = '';
    oLancadorMatricula.clearAll();
    document.formEvento.reset();
  }

  /**
   * Salva os dados do evento
   */
  function salvarEvento() {

    var evento = {
      codigo       : input.codigo.value,
      titulo       : input.titulo.value,
      dataInicial  : input.dataInicial.value,
      dataFinal    : input.dataFinal.value,
      entradaUm    : input.entradaUm.value,
      saidaUm      : input.saidaUm.value,
      tipoHoraUm   : input.tipoHoraUm.value,
      entradaDois  : input.entradaDois.value,
      saidaDois    : input.saidaDois.value,
      tipoHoraDois : input.tipoHoraDois.value,
      matriculas   : oLancadorMatricula.getRegistros()
    };

    if (evento.titulo.trim() === '') {
      return alert('Título é de preenchimento obrigatório.');
    }

    if (evento.dataInicial.trim() === '') {
      return alert('Data é de preenchimento obrigatório.');
    }

    if (evento.entradaUm.trim() === '') {
      return alert('Entrada 1 é de preenchimento obrigatório.');
    }

    if (evento.saidaUm.trim() === '') {
      return alert('Saída 1 é de preenchimento obrigatório.');
    }

    if (evento.matriculas.length === 0) {
      return alert('Matrícula é de preenchimento obrigatório.');
    }


    AjaxRequest.create(
      RPC_MANUTENCAO_EVENTO,
      {'exec' : 'salvar', evento: evento},
      function (retorno, erro) {

        if ((retorno.relatorioDeInconsistencia || erro) && confirm(retorno.mensagem)) {

          var oJanela = window.open('rec2_pontoeletronicoinconsistencias002.php');
          oJanela.moveTo(0, 0);

        } else {
          alert(retorno.mensagem);
        }

        getEventosCadastrados();
        if (!erro) {
          limparFormulario();
        }

      }
    ).setMessage('Aguarde, salvando evento...').execute();
  }

  /**
   * Busca os eventos cadatrados no sistema
   */
  function getEventosCadastrados() {

    limparFormulario();

    AjaxRequest.create(
      RPC_MANUTENCAO_EVENTO,
      {'exec' : 'getEventos'},
      function (retorno, erro) {

        if (erro) {
          return alert(retorno.mensagem);
        }
        gridEventosCadastrados.clear();

        if (retorno.eventos.length === 0) {
          return false;
        }

        retorno.eventos.each(
          function (evento) {

            var codigosMatriculas = [];

            if (evento.matriculas !== undefined) {

            }
            evento.matriculas.each(
              function(matricula) {
                codigosMatriculas.push(matricula.codigo);
              }
            );

            var botaoAlterar = "<input type='button' id='btnAlterar' value='A' onclick='alterar("+evento.codigo+")' />";
            var botaoExcluir = "<input type='button' id='btnExcluir' value='E' onclick='excluir("+evento.codigo+")' />";
            var eventoGrid = {
              codigo       : evento.codigo,
              titulo       : evento.titulo,
              data         : evento.dataInicial,
              entradaUm    : evento.entradaUm,
              saidaUm      : evento.saidaUm,
              tipoHoraUm   : evento.tipoHoraUm,
              entradaDois  : evento.entradaDois,
              saidaDois    : evento.saidaDois,
              tipoHoraDois : evento.tipoHoraDois,
              matriculas   : codigosMatriculas.join(', '),
              colecaoMatricula : evento.matriculas,
              acoes        : botaoAlterar +"  "+botaoExcluir
            };
            eventosCadastrados.add(eventoGrid);
          }
        );
        gridEventosCadastrados.reload();
      }
    ).setMessage('Aguarde, carregando eventos cadastrados...').execute();
  }

  /**
   * Preenche o formulário para alteração dos dados
   */
  function alterar(codigo) {

    limparFormulario();
    var eventoSelecionado = eventosCadastrados.get(codigo);
    input.codigo.value       = eventoSelecionado.codigo;
    input.titulo.value       = eventoSelecionado.titulo;
    input.dataInicial.value  = eventoSelecionado.data;
    input.dataFinal.value    = eventoSelecionado.data;
    input.entradaUm.value    = eventoSelecionado.entradaUm;
    input.saidaUm.value      = eventoSelecionado.saidaUm;
    input.tipoHoraUm.value   = eventoSelecionado.tipoHoraUm;
    input.entradaDois.value  = eventoSelecionado.entradaDois;
    input.saidaDois.value    = eventoSelecionado.saidaDois;
    input.tipoHoraDois.value = eventoSelecionado.tipoHoraDois;

    eventoSelecionado.colecaoMatricula.each(
      function(matricula) {
        oLancadorMatricula.adicionarRegistro(matricula.codigo, matricula.nome);
      }
    );
  }

  /**
   * Exclui o evento selecionado
   * @param codigo
   * @returns {boolean}
   */
  function excluir(codigo) {

    if (!confirm('Confirma a exclusão do evento?')) {
      return false;
    }

    AjaxRequest.create(
      RPC_MANUTENCAO_EVENTO,
      {'exec':'excluir', 'codigo':codigo},
      function (retorno, erro) {

        if (retorno.mensagem) {
          alert(retorno.mensagem);
        }

        if(erro) {
          return;
        }
        getEventosCadastrados();
      }
    ).setMessage('Aguarde, excluindo evento...').execute();
  }

  getEventosCadastrados();
</script>
