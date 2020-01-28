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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo;
$clrotulo->label("codigo");
$clrotulo->label("nomeinst");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");

$mesinicial = db_mesfolha();
$anoinicial = db_anofolha();
$anofinal   = db_anofolha();
$mesfinal   = db_mesfolha();

$Smesinicial = "O mês inicial";
$Sanoinicial = "O ano inicial";
$Sanofinal   = "O ano final";
$Smesfinal   = "O mês final";
?>
<html>
<head>
<title>DBSeller Informática Ltda - Página Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("DBDownload.widget.js");
?>

</head>
<style>
  .container{
      width: 545px;
  }
</style>
<body class="body-default" onLoad="js_gridArquivossiprev();a=1" >
  <form name="form1" class="container" method="post" action="">
    <fieldset>
      <legend>Gerar Arquivos SIPREV</legend>

      <fieldset class="separator">
        <legend>Competência</legend>
        <table class="form-container">
          <tr>
            <td class="field-size3">
              <label>
                Mês / Ano:
              </label>
            </td>
            <td>
              <?php db_input('mesinicial', 2, true, $mesinicial, 'text', 1, null, null, null, null, 2); ?>
              /<?php db_input('anoinicial', 4, true, $anoinicial, 'text', 1, null, null, null, null, 4); ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset class="separator">
        <legend>Arquivos Disponíveis</legend>
        <table class="form-container">
          <tr>
            <td>
              <div id="gridArquivossiprev" style="margin-top: 5px;"></div>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset class="separator" id="containerUnidadeGestora">

        <legend>Unidade Gestora</legend>
        <table class="form-container">
          <tr>
            <td class="field-size3">
              <label for="codigo">
                <?php db_ancora($Lcodigo, "js_pesquisacodigo(true);", 1); ?>
              </label>
            </td>
            <td>
              <?php
              db_input('codigo', 6, 1, true, 'text', 1, " onchange='js_pesquisacodigo(false);'");
              db_input('nomeinst', 40, $Inomeinst, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>
                Número / Ano do Ato:
              </label>
            </td>
            <td>
              <?php
                $SNumeroAto = "Número do Ato";
                db_input('NumeroAto', 6, 1, true,'text', 4, '', '', '', '', 12);
              ?>
              /
              <?php
                $SAnoAto = "Ano do Ato";
                db_input('AnoAto',4, 1,true,'text',4, null, null, null, null, 4);
               ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="DataAto">
                Data do Ato:
              </label>
            </td>
            <td>
              <?php db_inputdata('DataAto', '', '', '', true, 'text', 1); ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="">
                Ato Legal:
              </label>
            </td>
            <td>
              <?php

              $aAtosLegais = array(
                                     "2"  => "Decreto",
                                     "1"  => "Constituição Federal",
                                     "3"  => "Decreto Legislativo",
                                     "4"  => "Emenda",
                                     "5"  => "Lei Complementar",
                                     "6"  => "Lei Ordinária",
                                     "7"  => "Lei Delegada",
                                     "8"  => "Lei Orgânica",
                                     "9"  => "Medida Provisória",
                                     "10" => "Portaria",
                                     "11" => "Resolução",
                                     "12" => "Parecer",
                                     "13" => "Orientação Normativa",
                                     "99" => "Outros"
                                  );

              db_select('TipoAto', $aAtosLegais, true, 4, "rel=\"ignore-css\""); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label>
                <?php db_ancora("Representante Legal:", "js_pesquisacgm(true);", 1); ?>
              </label>
            </td>
            <td>
              <?php
              db_input('z01_numcgm',  6, $Iz01_numcgm, true, 'text', 1, " onchange='js_pesquisacgm(false);'");
              db_input('z01_nome',   40, $Iz01_nome,   true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </fieldset>
    <table style="margin: 0 auto;">
      <tr>
        <td>
          <input type="button" id="btnGerarArquivos" value="Gerar" onClick="gerar();">
        </td>
      </tr>
    </table>

  </form>

  <?php db_menu(); ?>

</body>
</html>
<script>
  var sUrlRPC = 'pes4_arquivossiprev.RPC.php';
  var oParam  = new Object();

  /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
  function js_gridArquivossiprev() {

    $('containerUnidadeGestora').style.display = 'none';

    oGridSiprev              = new DBGrid('Arquivossiprev');
    oGridSiprev.nameInstance = 'oGridSiprev';
    oGridSiprev.setCheckbox(0);
    oGridSiprev.setCellWidth(new Array('50px', '403px'));
    oGridSiprev.setCellAlign(new Array('center', 'left'));
    oGridSiprev.setHeader(new Array('Código', 'Arquivo'));
    oGridSiprev.setHeight(430);
    oGridSiprev.hasTotalizador = true;
    oGridSiprev.show($('gridArquivossiprev'));
    oGridSiprev.setHeight(430);

    oGridSiprev.clearAll(true);

    /**
     * Quando for selecionado o Arquivo 'Órgão' o fieldset
     * Unidade Gestora é apresentado para o usário.
     */
    oGridSiprev.selectSingle = function(oCheckbox,sRow,oRow){

      DBGrid.prototype.selectSingle.apply(this,[oCheckbox,sRow,oRow]);

      if ( oCheckbox.value != 3 ) {
        return false;
      }

      if ( oCheckbox.checked ) {

        $('containerUnidadeGestora').style.display = '';
        return true;
      }

      $('codigo').value     = '';
      $('nomeinst').value   = '';
      $('NumeroAto').value  = '';
      $('AnoAto').value     = '';
      $('DataAto').value    = '';
      $('z01_numcgm').value = '';
      $('z01_nome').value   = '';
      $('TipoAto').value    = 2;

      $('containerUnidadeGestora').style.display = 'none';

      return false;
    }

    //inicia o preenchimento com o retorno dos registros
    lista_rharquivossiprev();
  }

  /*
   * funcao para montar os registros iniciais da grid
   */
  function lista_rharquivossiprev() {

     var oParametros      = new Object();
     oParametros.exec     = 'Lista';
     oParametros.sTodos   = ('*');

     var msgDiv    = "Aguarde ...";
     js_divCarregando(msgDiv,'msgBox');

     var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                               {method: "post",
                                                parameters:'json='+Object.toJSON(oParametros),
                                                onComplete: js_retornoCompletaSiprev
                                               });

  }

  /*
   * funcao para montar a grid com os registros da tabela rharquivossiprev
   *
   */
  function js_retornoCompletaSiprev(oAjax) {

      js_removeObj('msgBox');
      var oRetorno = eval("("+oAjax.responseText+")");

      if (oRetorno.status == 1) {

        oGridSiprev.clearAll(true);

        if ( oRetorno.dados.length == 0 ) {

          alert('Nenhum registro encontrado!');
          return false;
        }
        oRetorno.dados.each(function (oDado, iInd) {

          aRow = new Array();
          aRow[0]  = oDado.rh94_sequencial;
          aRow[1]  = oDado.rh94_descricao.urlDecode();
          oGridSiprev.addRow(aRow);
        });
        oGridSiprev.renderRows();
      }

      if(oRetorno.lConfigurouAssentamentos === false) {

        var sMensagem  = 'É necessário configurar os tipos de assentamentos para integração do SIPREV, para geração dos';
            sMensagem += ' seguintes arquivos:';
            sMensagem += ' \n\n- Históricos Funcionais - RGPS';
            sMensagem += ' \n- Históricos Funcionais - RPPS';
            sMensagem += ' \n- Tempo de Contribuição - RGPS';
            sMensagem += ' \n- Tempo de Contribuição - RPPS';
            sMensagem += ' \n- Tempos Fictícios';
            sMensagem += ' \n- Tempo sem Contribuição';
            sMensagem += ' \n\nAcesse RH > Procedimentos > Parametros RH, para informar os tipos de assentamentos.';
        alert(sMensagem);
      }
  }

   /*
    * Inicia o envio dos checkbox selecionados no grid
    */
  function gerar() {

     var iMesIni         = $F('mesinicial');
     var iAnoIni         = $F('anoinicial');
     var iMesFim         = $F('mesinicial');
     var iAnoFim         = $F('anoinicial');
     var iUnidadeGestora = $F('codigo');
     var iTipoAto        = $F('TipoAto');
     var iNumeroAto      = $F('NumeroAto');
     var iAnoAto         = $F('AnoAto');
     var dDataAto        = $F('DataAto');
     var cRepresentante  = $F('z01_nome');

     var aListaCheckbox = oGridSiprev.getSelection();
     var aListaArquivos = new Array();

     aListaCheckbox.each( function ( aRow ) {
          aListaArquivos.push(aRow[0]);
     });

     /**
      * Valida os dados da unidade gestora, todos os dados
      * são obrigatórios caso seja selecionado a Instituição.
      */
     if (iUnidadeGestora != '') {

       if (iTipoAto == '') {

         alert('Ato Legal é de preenchimento obrigatório.');
         return false;
       }

       if (iNumeroAto == '') {

         alert('Número do Ato é de preenchimento obrigatório.');
         return false;
       }

       if (iAnoAto == '') {

         alert('Ano do Ato é de preenchimento obrigatório.');
         return false;
       }

       if (dDataAto == '') {

         alert('Data do Ato é de preenchimento obrigatório.');
         return false;
       }

       if (cRepresentante == '') {

         alert('Representante Legal é de preenchimento obrigatório.');
         return false;
       }
     }

     /**
      * Definimos as propriedades do objeto que será postado para o RPC
      */
     var oParametros            = new Object();
     oParametros.exec           = 'Gerar';
     oParametros.sListaArquivos = aListaArquivos.join(',');
     oParametros.iMesinicial    = iMesIni;
     oParametros.iAnoinicial    = iAnoIni;
     oParametros.iMesfinal      = iMesIni;
     oParametros.iAnofinal      = iAnoIni;
     oParametros.iUnidadeGestora= iUnidadeGestora;
     oParametros.iTipoAto       = iTipoAto;
     oParametros.iNumeroAto     = iNumeroAto;
     oParametros.iAnoAto        = iAnoAto;
     oParametros.dDataAto       = dDataAto;
     oParametros.cRepresentante = cRepresentante;

     /*
      * Valida os dados antes da postagem
      * se a data inicial e final estao preenchida
      * se a data inicial é menor que a data final
      * se no minimo um arquivo foi selecionado
      * então a postagem para processamento será realizada.
     */
     if ( iMesIni == 0 || iMesIni > 12 || iMesIni == '' || iMesIni == null) {

       alert('Mês Inicial Inválido');
       $('mesinicial').value = '';
       $('mesinicial').focus();
     } else if (iAnoIni == null || iAnoIni == '' || iAnoIni < 1900) {

       alert('Ano Inicial Inválido');
       $('anoinicial').value = '';
       $('anoinicial').focus();
     } else if (oParametros.sListaArquivos == null || oParametros.sListaArquivos == "") {
       alert("Selecione no mínimo um tipo de arquivo");
     } else {


  	   var msgDiv    = "Aguarde ...";
  	   js_divCarregando(msgDiv,'msgBox');

  	   var oAjaxArquivos  = new Ajax.Request(sUrlRPC,{ method: "post",
  	                                                   parameters:'json='+Object.toJSON(oParametros),
  	                                                   onComplete: retorno_siprev
  	                                                 });
    }
  }

  /*
   * Trata o Retorno do Processamento Siprev
   */
  function retorno_siprev(oAjax) {

      // Instancia do DBDownload para o arquivo do SIPREV
      var oDBDownload = new DBDownload();
      oDBDownload.addGroups("todos", "Arquivos XML Compactados");

      var sArquivo    = "tmp/SIPREV.zip";
      var sLabel      = "SIPREV.zip";
      var oRetorno    = eval("("+oAjax.responseText+")");

      var resposta = JSON.parse(oAjax.responseText);

      for(var item of resposta.itens) {

        if(item.nome === 'SIPREV.zip') {

          /**
           * Adiciona o arquivo ZIP ao DBDownload
           */
          oDBDownload.addFile(item.caminho, item.nome, 'todos');
          continue;
        }
      }

      if( oRetorno.lTemInconsistencias === true ) {

        oDBDownload.addGroups("inconsistencias", "Relatório de Inconsistências");
        oDBDownload.addFile('tmp/inconsistencias_siprev.pdf', 'Inconsistencias.pdf', 'inconsistencias');
      }

      if (oRetorno.status == 1) {

        if ( oRetorno.dados.length == 0 ) {

          js_removeObj('msgBox');
          alert('Nenhum registro encontrado!');
          return false;
        } else {
          oDBDownload.show();
        }
      } else {

        alert(oRetorno.message.urlDecode());
        js_removeObj('msgBox');
        return false;
      }
      js_removeObj('msgBox');
  }

  function js_pesquisacodigo (mostra){

    if (mostra==true) {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostrarhpessoal1|codigo|nomeinst','Pesquisa Instituição',true);
    } else {

      if (document.form1.codigo.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.codigo.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa Instituição',false);
      } else {
        document.form1.nomeinst.value = '';
      }
    }
  }

  function js_mostrarhpessoal (chave,erro) {

    document.form1.nomeinst.value = chave;
    if (erro==true) {

      document.form1.codigo.focus();
      document.form1.codigo.value = '';
    }
  }

  function js_mostrarhpessoal1 (chave1,chave2) {

    document.form1.codigo.value = chave1;
    document.form1.nomeinst.value = chave2;
    db_iframe_db_config.hide();
  }

  function js_pesquisacgm (mostra) {

    if (mostra==true) {
      js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa Representante Legal',true);
    } else {

      if (document.form1.z01_numcgm.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_cgm.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa Representante Legal',false);
      } else {
        document.form1.z01_nome.value = '';
      }
    }
  }
  function js_mostracgm (erro, chave) {

    document.form1.z01_nome.value = chave;
    if (erro==true) {
      document.form1.z01_numcgm.focus();
      document.form1.z01_numcgm.value = '';
    }
  }

  function js_mostracgm1 (chave1,chave2) {

    document.form1.z01_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    func_nome.hide();
  }

</script>
