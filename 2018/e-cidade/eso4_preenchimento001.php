<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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
$rh01_regist      = '';
$matricula        = null;
$lTrazerSugestoes = false;
db_postmemory($_GET);

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("object.js");
      db_app::load("Input/DBInput.widget.js, DBInputHora.widget.js, Input/DBInputCep.widget.js,Input/DBInputCNPJ.js,Input/DBInputCpf.widget.js,Input/DBInputDate.widget.js");
      db_app::load("Input/DBInputInteger.widget.js, Input/DBInputTelefone.widget.js,Input/DBInputValor.widget.js");
      db_app::load("Input/DBInputCheckboxRadio.widget.js, Input/DBCheckBox.widget.js,Input/DBRadio.widget.js,Collection.widget.js");
      db_app::load("avaliacao/DBViewFormulario.classe.js, avaliacao/DBViewGrupoPerguntas.classe.js,avaliacao/DBViewPergunta.classe.js,avaliacao/DBViewResposta.classe.js");
      db_app::load("AjaxRequest.js,estilos.css,grid.style.css,avaliacao.css");

      db_postmemory($_POST);

      if (!empty($rh01_regist)) {
        $matricula = $rh01_regist;
      }
    ?>
    <style>
      .controle {
        width: 80px;
      }

      #anterior {
        margin-left: 2px;
        float: left;
      }

      #proximo {
        margin-right: 2px;
        float: right;
      }

      .db-tooltip {
        display: none;
      }
    </style>
  </head>
  <body>

      <form class="container" style="width: 800px;">
        <?php
          if (empty($matricula)) {
            $lTrazerSugestoes = true;
           ?>
          <fieldset >
          <legend ><label for="matricula" > Escolha sua Matrícula </label ></legend>
          <select id = 'matricula' style="width:100%" onchange="buscarAvaliacao(Event)">
          </select >
        </fieldset >
         <?php
        } else {
          ?>
           <input type="hidden" id="matricula" name="matricula" value="<?=$matricula?>" />
          <?php
        }
        ?>
        <fieldset>
        <legend>Formulário de Cadastro para o eSocial</legend>
          <div id="questionario"></div>
        </fieldset>
        <input type="button" id="anterior" name="anterior" value="Anterior" class="controle" />
        <input type="button" id="limpar"   name="limpar"   value="Limpar"   class="controle" />
        <input type="button" id="salvar"   name="salvar"   value="Salvar"   class="controle" />
        <input type="button" id="proximo"  name="proximo"  value="Próximo"  class="controle" />
      <form>

    <?php
      if(!isset($iframe) || !$iframe) {
        db_menu(); 
      }
    ?>

    <script>
      viewAvaliacao          = '';
      var iMatriculaAnterior = '';
      (function(){

        try {
          buscarMatriculas();
        } catch (e) {
          alert(e);
        }
      })();

    function buscarAvaliacao(Event) {

      if(Event) {
        if(!confirmaSaida("Se você alterar a matrícula os dados que não foram salvos serão perdidos.\nTem certeza que deseja trocar de matrícula?")) {
          $('matricula').value = iMatriculaAnterior;
          return false;
        }
      }

      removeEventoBotoes();
      iMatriculaAnterior = $F('matricula');
      $('questionario').innerHTML = '';
      var iMatricula =  $F('matricula');


      var oDados  = {};
      oDados.exec            = 'buscarAvaliacao';
      oDados.trazerSugestoes = $('matricula').nodeName !== 'INPUT';
      if (!empty(iMatricula)) {
        oDados.iMatricula = iMatricula;
      }

      var oAjaxRequest = new AjaxRequest('eso4_preenchimento.RPC.php', oDados, montarAvaliacao);
          oAjaxRequest.setMessage('Buscando dados da avaliação...');
          oAjaxRequest.execute();
    }

    function montarAvaliacao(oResponse, lErro) {

      if (lErro) {
        alert(oResponse.mensagem);
      }

      viewAvaliacao   = DBViewFormulario.makeFromObject(oResponse.oFormulario)
          .setEvent('changeStep', controlarBotoes)
          .show($('questionario'));

      $('proximo').observe('click', function() {
        
        this.blur();
        salvarQuestionario(viewAvaliacao, viewAvaliacao.getStatus().grupoAtual.getCodigo());
      });

      $('anterior').observe('click', function() {

        var sMensagem = "As informações preenchidas, poderão ser perdidas.\n";
        sMensagem    += "Tem certeza que deseja voltar?";

        if(confirm(sMensagem)) {
          viewAvaliacao.recurarGrupo();
        }
      });

      $('salvar').observe('click', function() {
        salvarQuestionario(viewAvaliacao);
      });
      $('limpar').observe('click', function() {

        if (viewAvaliacao.getStatus().grupoAtual) {
          viewAvaliacao.getStatus().grupoAtual.limparRespostas();
        }
      });
    }

    function salvarQuestionario(viewAvaliacao, iCodigoGrupo) {

      if(! viewAvaliacao.getStatus().grupoAtual.isValido()) {
        alert("Há informações obrigatórias inconsistentes.\nVerifique.");
        return false;
      }
      var lRetorno = true;
      var oAjaxRequest = new AjaxRequest(
        'eso4_preenchimento.RPC.php',
        {
          exec                  : 'salvarAvaliacao',
          iMatricula            : iMatriculaAnterior,
          iCodigoAvaliacao      : viewAvaliacao.codigo,
          iCodigoGrupoPerguntas : iCodigoGrupo,
          aPerguntasRespostas   : viewAvaliacao.getDados(iCodigoGrupo)
        },

        function(oResponse, lErro){
          
          if (!iCodigoGrupo || lErro) {
            alert(oResponse.mensagem);
          }
          if (lErro) {
            lRetorno = false;
            return ;
          }
          
          viewAvaliacao.avancarGrupo();
        }
      );

      oAjaxRequest.setMessage('Salvando dados da avaliação...');
      oAjaxRequest.execute();
      return lRetorno;
    }

    var controlarBotoes = function(event) {

      var status = this.getStatus();

      $('proximo').disabled  = true;
      $('anterior').disabled = true;
      $('salvar').disabled   = true;

      if (status.grupoPosterior) {
        $('proximo').disabled = false;
      }

      if (status.grupoAnterior) {
        $('anterior').disabled = false;
      }
      
      if (status.grupoAtual) {
        $('salvar').disabled = false;
      }
    };

    function buscarMatriculas() {

      if (!empty($F('matricula'))) {

        buscarAvaliacao();
        return;
      }
      var oDados = {};
      oDados.exec = 'getMatriculas';


      var oAjaxRequest = new AjaxRequest('eso4_preenchimento.RPC.php', oDados, function(oResponse, lErro) {

        var oComboMatriculas = $('matricula');
        oComboMatriculas.options.lenght = 0;
        if (lErro) {

          alert(oResponse.mensagem);
          return false;
        }
        for (oMatricula of oResponse.matriculas) {

          var oOption  = new Option(oMatricula.matricula+" - "+oMatricula.nome, oMatricula.matricula);
          oComboMatriculas.add(oOption);
        }

        buscarAvaliacao();
      });
      oAjaxRequest.setMessage('Buscando dados da avaliação...');
      oAjaxRequest.execute();

    }

    function removeEventoBotoes() {

      $('salvar').stopObserving('click');
      $('proximo').stopObserving('click');
      $('anterior').stopObserving('click');
    }

    function confirmaSaida (sMensagem) {

      if(typeof sMensagem == 'undefined' || sMensagem == null || sMensagem == false) {
        sMensagem = 'Você está saindo do cadastro do eSocial.\nAntes de sair, salve seus dados.';
      }

      if (!confirm(sMensagem)) {
        return false;
      }
      return true;
    }


    if (parent.windowLiberacao) {
      parent.windowLiberacao.setShutDownFunction(function() {
        if(!confirmaSaida()) {
          return false;
        }
        parent.windowLiberacao.destroy();
      });
    }
    </script>
  </body>
</html>
