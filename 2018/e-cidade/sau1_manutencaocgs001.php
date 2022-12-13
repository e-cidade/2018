<?php
/**
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
require_once modification("dbforms/db_funcoes.php");

$oGet     = db_utils::postMemory($_GET);
$sDisplay = isset($oGet->lBloqueiaBotoes) ? "style='display: none;'" : "";
?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
      $assets = array(
        "scripts.js",
        "prototype.js",
        "estilos.css",
        "AjaxRequest.js",

        /**
         * Abas
         */
        "DBAbas.widget.js",
        "DBAbasItem.widget.js",
        
        "windowAux.widget.js",
        "dbautocomplete.widget.js",
        "dbmessageBoard.widget.js",
        "dbtextField.widget.js",
        "dbcomboBox.widget.js",
        "dbViewCadEndereco.classe.js",

        /**
         * Inputs
         */
        "Input/DBInput.widget.js",
        "Input/DBInputDate.widget.js",
        "Input/DBInputFoto.widget.js",
        "Input/DBInputEmail.widget.js",
        "Input/DBInputEndereco.widget.js",
        "Input/DBInputTelefone.widget.js",
        
        /**
         * Ancora
         */
        "DBAncora.widget.js",
        "DBLookUp.widget.js", 
        /**
         * Validador de CNS
         */
        "saude/validaCNS.js",

        /**
         * Grid
         */
        "Collection.widget.js",
        "datagrid.widget.js",
        "DatagridCollection.widget.js",
        "dbViewCadastroDocumento.js",

        "dbtextFieldData.widget.js"

      );
      db_app::load($assets);
    ?>
    <style type="text/css">
      .container > input[type="button"],
      .container > input[type="submit"] {
        width: 80px;
      }

      .container .form-container > tbody > tr > td:first-child {
        width: 200px;
      }

      .aba, .abaAtiva{
        width: 100px;
      }
    </style>
    <script>
      var validacoes = [];
      var callbackCarregamento = {
        dadosPessoais : function(dadosCGS, dadosPadrao){
          return true;
        },        
        contatos      : function(dadosCGS, dadosPadrao){
          return true;
        },   
        documentos    : function(dadosCGS, dadosPadrao){
          return true;
        },     
        biometria     : function(dadosCGS, dadosPadrao){
          return true;
        },    
        outrosDados   : function(dadosCGS, dadosPadrao){
          return true;
        },      
      }; 
    </script>
  </head>
  <body class="body-default">

    <form class="container" style="min-width: 700px">
      <fieldset class="">
        <legend>Manutenção do Cadastro Geral da Saúde<span id="codigoCgs"></span></legend>
        <div id="ctnAbas">
          <!-- CONTAINER REFERENTE A ABA DADOS PESSOAIS -->
          <div id="ctnAbaDadosPessoais">
            <?php require_once("dbforms/db_frmcgsdadospessoais.php"); ?>
          </div>

          <!-- CONTAINER REFERENTE A ABA CONTATOS -->
          <div id="ctnAbaContatos">
            <?php require_once("dbforms/db_frmcgscontato.php"); ?>
          </div>

          <!-- CONTAINER REFERENTE A ABA DOCUMENTOS -->
          <div id="ctnDocumentos">
            <?php require_once("dbforms/db_frmcgsdocumentos.php"); ?>
          </div>

          <!-- CONTAINER REFERENTE A ABA BIOMETRIA -->
          <div id="ctnBiometria">
            <?php require_once("dbforms/db_frmcgsbiometria.php"); ?>
          </div>

          <!-- CONTAINER REFERENTE A ABA OUTROS DADOS -->
          <div id="ctnOutrosDados">
            <?php require_once("dbforms/db_frmcgsoutrosdados.php"); ?>
          </div>
        </div>
      </fieldset>  
      <input type="button" id="pesquisar"   value="Pesquisar" func-arquivo="func_cgs_und.php" func-objeto="func_nome" <?=$sDisplay?> />
      <input type="submit" id="salvarDados" value="Salvar" />
      <input type="button" id="novo"        value="Novo" <?=$sDisplay?> />
      <input type="button" id="excluir"     value="Excluir" disabled <?=$sDisplay?> />
    </form>
  </body>
  <?php
  if(!isset($oGet->lBloqueiaMenu)){
    db_menu();
  }
  ?>
</html>
<script>

  /**
   * Dados básicos 
   */
  const MENSAGENS_MANUTENCAO_CGS = 'saude.ambulatorial.sau1_manutencaocgs001.';
  var oGet             = js_urlToObject();
  var sRpc             = "sau4_cgs.RPC.php";
  var iCgs             = !!oGet.cgs ? oGet.cgs : null;
  var iCodigoCartaoSus = null;

  /**
   * Criação do elemento DBAbas, com as respectivas abas existentes
   */
  var oDBAba            = new DBAbas($('ctnAbas'));
  var oAbaDadosPessoais = oDBAba.adicionarAba('Dados Pessoais', $('ctnAbaDadosPessoais'));
  var oAbaContatos      = oDBAba.adicionarAba('Contatos',       $('ctnAbaContatos'));
  var oAbaDocumentos    = oDBAba.adicionarAba('Documentos',     $('ctnDocumentos'));
  var oAbaBiometria     = oDBAba.adicionarAba('Biometria',      $('ctnBiometria'));
  var oAbaOutrosDados   = oDBAba.adicionarAba('Outros Dados',   $('ctnOutrosDados'));

  $('codigoCgs').innerHTML = '';

  /**
   * Executa cada callback que será implementado nos arquivos do "dbforms"
   */
  function carregaDados() {

    /**
     * Carregamento dos dados
     */
    var parametros        = {
      "sExecucao" : !!iCgs ? "getDadosCadastroAlteracao" : "getDadosCadastroNovo",
      "cgs"       : iCgs
    };

    biometria.foto_nova.setValue('');

    AjaxRequest.create(sRpc, parametros, function(resposta) {

      var informacoesCGS = resposta.informacoesCGS || {};// resposta.informacoesCGS.dados_pessoais : null;

      callbackCarregamento.dadosPessoais( informacoesCGS.dados_pessoais || null, resposta.informacoesPadrao);
      callbackCarregamento.contatos( informacoesCGS.contato || null, resposta.informacoesPadrao);
      callbackCarregamento.biometria( informacoesCGS.biometria || null, resposta.informacoesPadrao);
      callbackCarregamento.outrosDados( informacoesCGS.outros_dados || null, resposta.informacoesPadrao);
      callbackCarregamento.documentos( null, resposta.informacoesPadrao );

      /**
       * Cria os comportamentos básicos para a tela
       */
      criarComportamentos();
    })

    .setMessage( _M( MENSAGENS_MANUTENCAO_CGS + 'buscando_dados' ) )

    .execute();
  }
 

  /**
   * Cria os comportamentos da tela referentes a botoões e lookups de pesquisa 
   */
  function criarComportamentos() {

    /**
     * Botão excluir
     */
    $('excluir').stopObserving('click');
    $('excluir').observe('click', excluirCGS);
    $('excluir').disabled = !iCgs;

    /**
     * Botão novo 
     */
    $('novo').stopObserving('click');
    $('novo').observe('click', function() {
      return window.location.href = 'sau1_manutencaocgs001.php';
    });

    /**
     * Aba documentos
     */
    oAbaDocumentos.bloquear();

    if(!!iCgs) {
      oAbaDocumentos.desbloquear();
    }

    /**
     * Postagem dos dados
     */
    document.forms[0].stopObserving("submit");
    document.forms[0].observe("submit", enviarFormulario);
    
    $('pesquisar').stopObserving("click");

    var codigoCGS = document.createElement('input');
    codigoCGS.data  = 'z01_i_cgsund';

    var nomeCGS   = document.createElement('input');
    nomeCGS.data  = 'z01_v_nome'; 

    var lookup = new DBLookUp($('pesquisar'), codigoCGS, nomeCGS);
    lookup.setCallBack("onClick", function(parametros){
      window.location.href = 'sau1_manutencaocgs001.php?cgs=' + parametros[0];
    });

    lookup.setObjetoLookUp('db_iframe_cgs_und');
    lookup.setQueryString("&lDesabilitaCgs");

    if(!iCgs) {
      lookup.abrirJanela(true);
    }
  }

  /**
   * Responsável por chamar a exclusão de um CGS
   */
  function excluirCGS() {

    if(!confirm('Deseja realmente excluir o CGS: \n\n' + iCgs + ' - ' + dadosPessoais.nome.getValue() + '?')) {
      return;
    }

    var oParametros = { 'sExecucao': 'excluirCgs', 'iCgs': iCgs };
    AjaxRequest.create(sRpc, oParametros, function( oRetorno, lErro ) {

      alert( oRetorno.sMessage.urlDecode() );

      if( lErro ) {
        return;
      }

      window.location.href = 'sau1_manutencaocgs001.php';
    }).setMessage( _M( MENSAGENS_MANUTENCAO_CGS + 'excluindo_cgs' ) )
      .execute();
  }

  function enviarFormulario(event) {

    event.preventDefault();
    event.stopPropagation();


    var validacao, erro = false;

    for(validacao of validacoes) {

      if(!validacao()) {
        return;
      }
    }

    /**
     * Envia os dados a serem salvos. Cada formulário retorna seus dados, setando os atributos no objeto a ser enviado
     * para o RPC
     * @type {{sExecucao: string}}
     */
    var oParametros = { 'sExecucao': 'salvarCgs', 'iCgs': iCgs };

    setValoresDadosPessoais(oParametros);
    setValoresContatos(oParametros);
    setValoresBiometria(oParametros);
    setValoresOutrosDados(oParametros);

    AjaxRequest.create(sRpc, oParametros, function( oRetorno, lErro ) {

      alert( oRetorno.sMessage.urlDecode() );

      if( lErro ) {
        return;
      }

      iCgs = oRetorno.iCgs;

      carregaDados();
      carregaDadosPessoais();
      buscaDocumentosCgs();

    }).setMessage( _M( MENSAGENS_MANUTENCAO_CGS + 'salvando_dados' ) )
      .execute();
  }

  carregaDados();
</script>
