<?php
/**
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libpostgres.php"));

db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

//se esta no modulo agua, seleciona a funcao js que recupera valores da aba filtro imoveis, se não utiliza a funcao normalmente
//caso seja o modulo agua, a tela principal não mostrará o db_menu pq terá abas
$funcao_js_verifica = $db21_usasisagua == 't' ? 'js_verifica_agua()' : 'js_verifica()';
$monta_menu         = $db21_usasisagua == 't' ? false : true;

$clpostgresqlutils = new PostgreSQLUtils;
$clarretipo        = new cl_arretipo;
$clarretipo->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('k60_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');

db_postmemory($HTTP_POST_VARS);

$instit = db_getsession("DB_instit");
if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

  db_msgbox(_M('tributario.notificacoes.cai4_lista003.problema_indices_debitos'));
  $db_botao = false;
  $db_opcao = 3;
} else {

  $db_botao = true;
  $db_opcao = 1;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css");
      db_app::load("widgets/DBToogle.widget.js, widgets/DBLancador.widget.js");
    ?>
    <script type="text/javascript">

    function js_fimprocessamento() {

     (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_lista002.hide();
     location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>';
    }

    function js_sobe() {

      var F = document.getElementById("campos");

      if(F.selectedIndex != -1 && F.selectedIndex > 0) {

        var SI            = F.selectedIndex - 1;
        var auxText       = F.options[SI].text;
        var auxValue      = F.options[SI].value;
        F.options[SI]     = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
        F.options[SI + 1] = new Option(auxText,auxValue);

        js_trocacordeselect();
        F.options[SI].selected = true;
      }
    }

    function js_desce() {

      var F = document.getElementById("campos");

      if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

        var SI            = F.selectedIndex + 1;
        var auxText       = F.options[SI].text;
        var auxValue      = F.options[SI].value;
        F.options[SI]     = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
        F.options[SI - 1] = new Option(auxText,auxValue);

        js_trocacordeselect();
        F.options[SI].selected = true;
      }
    }

    function js_excluir() {

      var F  = document.getElementById("campos");
      var SI = F.selectedIndex;

      if(F.selectedIndex != -1 && F.length > 0) {

        F.options[SI] = null;
        js_trocacordeselect();

        if(SI <= (F.length - 1)) {
          F.options[SI].selected = true;
        }
      }
    }

    function js_insSelect() {

      oLancadorProcedencia.clearAll();

      var texto = document.form1.k00_descr.value;
      var valor = document.form1.k00_tipo.value;

      if(texto != "" && valor != "") {

        var F     = document.getElementById("campos");
        var testa = false;

        for(var x = 0; x < F.length; x++) {

          if(F.options[x].value == valor || F.options[x].text == texto) {

            testa = true;
            break;
          }
        }

        if(testa == false) {

          F.options[F.length] = new Option(texto,valor);
          js_trocacordeselect();
        }
      }

      texto = document.form1.k00_descr.value = "";
      valor = document.form1.k00_tipo.value  = "";

      document.form1.lanca.onclick = '';
    }

    //funcao para criar notificao se estiver utilizando o modulo agua
    function js_verifica_agua() {

      var val1 = new Number($F('DBtxt10'));
      var val2 = new Number($F('DBtxt11'));

      var nomelista = $F('k60_descr');

      var oComboSituacao        = parent.iframe_filtroimoveis.document.getElementById('db_situacaocorte');
      var oComboZonaEntrega     = parent.iframe_filtroimoveis.document.getElementById('db_zonaentrega');
      var oComboCaracteristicas = parent.iframe_filtroimoveis.document.getElementById('db_caracteristica');
      var oComboRuas            = parent.iframe_filtroimoveis.document.getElementById('db_ruas');

      var sComSemSituacoes      = parent.iframe_filtroimoveis.document.getElementById('situacaocorte').value;
      var sComSemZonaEntrega    = parent.iframe_filtroimoveis.document.getElementById('zonaentrega').value;
      var sComSemCaracteristica = parent.iframe_filtroimoveis.document.getElementById('caracteristica').value;
      var sComSemRuas           = parent.iframe_filtroimoveis.document.getElementById('logradouro').value;
      var sMatBaixadas          = parent.iframe_filtroimoveis.document.getElementById('matriculasbaixadas').value;
      var sTerrenos             = parent.iframe_filtroimoveis.document.getElementById('terrenos').value;

      var lExisteFiltro  = false;

      if (nomelista == "") {

        alert(_M('tributario.notificacoes.cai4_lista003.informe_descricao'));
        document.form1.k60_descr.focus();
        return false;
      }

      if(val1.valueOf() >= val2.valueOf()) {

        alert(_M('tributario.notificacoes.cai4_lista003.valor_maximo_menor_valor_minimo'));
        document.form1.DBtxt11.focus();
        return false;
      }

      qry  = '?sDescricaoLista='+$F('k60_descr');
      qry += '&dDataDebitos='+$F('data');
      qry += '&iQtdListar='+$F('numerolista22');
      qry += '&nValorIni='+$F('DBtxt10');
      qry += '&nValorFim='+$F('DBtxt11');
      qry += '&sTipoLista='+$F('k60_tipo');
      qry += '&sTipoListaDescr='+$('k60_tipo').options[$('k60_tipo').selectedIndex].text;
      qry += '&dNotifDataLimite='+$F('data1');
      qry += '&iNotifTipo='+$F('notiftipo');
      qry += '&sNotifTipo='+$('notiftipo').options[$('notiftipo').selectedIndex].text;
      qry += '&sMassaFalida='+$F('massa');
      qry += '&sLoteamento='+$F('loteamento');
      qry += '&dDtOperIni='+$F('dtini');
      qry += '&dDtOperFim='+$F('dtfim');
      qry += '&dDtVencIni='+$F('dataini');
      qry += '&dDtVencFim='+$F('datafim');
      qry += '&iExercIni='+$F('exercini');
      qry += '&iExercFim='+$F('exercfim');
      qry += '&iIgnoraExercIni='+$F('desconexercini');
      qry += '&iIgnoraExercFim='+$F('desconexercfim');
      qry += '&iQtdParcAtrasoIni='+$F('parcini');
      qry += '&iQtdParcAtrasoFim='+$F('parcfim');
      qry += '&iNroParcAtrasoIni='+$F('numini');
      qry += '&iNroParcAtrasoFim='+$F('numfim');
      qry += '&sConsideraPosterior='+$F('considerar');
      qry += '&iOpcaoTipoDebito='+$F('opcaofiltro');
      qry += '&sOpcaoTipoDebito='+$('opcaofiltro').options[$('opcaofiltro').selectedIndex].text;
      qry += '&baixadas='+sMatBaixadas;
      qry += '&terrenos='+sTerrenos;

      sTipoDebitos = '';
      sVirgula     = '';

      if($('campos').options.length > 0) {

        for(var i = 0; i < $('campos').options.length; i++) {

          sTipoDebitos += sVirgula + $('campos').options[i].value;
          sVirgula      = ',';
        }
      }

      qry += '&sTiposDebitos='+sTipoDebitos;

      /*caso utilize o modulo agua libera aba para opçoes de filtro de imoveis*/
      var virgula   = '';
      var situacoes = '';

      for(var a = 0; a < oComboSituacao.length; a++) {

        situacoes     += virgula+oComboSituacao.options[a].value;
        virgula        = ',';
        lExisteFiltro  = true;
      }

      qry += '&situacao='+sComSemSituacoes+'&situacoes='+situacoes;

      virgula   = '';
      var zonas = '';

      for(var b = 0; b < oComboZonaEntrega.length; b++) {

        zonas         += virgula+oComboZonaEntrega.options[b].value;
        virgula        = ',';
        lExisteFiltro  = true;
      }

      qry += '&zona='+sComSemZonaEntrega+'&zonas='+zonas;

      virgula             = '';
      var caracteristicas = '';

      for(var c = 0; c < oComboCaracteristicas.length; c++) {

        caracteristicas += virgula+oComboCaracteristicas.options[c].value;
        virgula          = ',';
        lExisteFiltro    = true;
      }

      qry += '&caracteristica='+sComSemCaracteristica+'&caracteristicas='+caracteristicas;

      virgula  = '';
      var ruas = '';

      for(var d = 0; d < oComboRuas.length; d++) {

        ruas          += virgula+oComboRuas.options[d].value;
        virgula        = ',';
        lExisteFiltro  = true;
      }

      qry += '&rua='+sComSemRuas+'&ruas='+ruas;

      /**
       * Envia as procedências selecionadas
       */
      var aProcedencias = [];
      if(oLancadorProcedencia.getRegistros().length > 0) {

        oLancadorProcedencia.getRegistros().each(function(oProcedencia) {
          aProcedencias.push(oProcedencia.sCodigo);
        });
      }

      qry += '&sProcedencias=' + aProcedencias.join(',');

      if(lExisteFiltro) {

    	  if($F('k60_tipo') != 'M') {

    		  alert(_M('tributario.notificacoes.cai4_lista003.filtro_utilizados_caso_diferente_matricula'));
    		  document.form1.k60_tipo.focus();
    		  return false;
    	  }
      }

      js_OpenJanelaIframe('','db_iframe_lista002','cai4_lista002.php'+qry,'Processando Lista ...',true);

      return true;
    }

    function js_verifica() {

      var val1 = new Number($F('DBtxt10'));
      var val2 = new Number($F('DBtxt11'));

      var nomelista = $F('k60_descr');

      if (nomelista == "") {

        alert(_M('tributario.notificacoes.cai4_lista003.informe_descricao'));
        document.form1.k60_descr.focus();
        return false;
      }

      if(val1.valueOf() >= val2.valueOf()) {

        alert(_M('tributario.notificacoes.cai4_lista003.valor_maximo_menor_valor_minimo'));
        document.form1.DBtxt11.focus();
        return false;
      }

      qry  = '?sDescricaoLista='+$F('k60_descr');
      qry += '&dDataDebitos='+$F('data');
      qry += '&iQtdListar='+$F('numerolista22');
      qry += '&nValorIni='+$F('DBtxt10');
      qry += '&nValorFim='+$F('DBtxt11');
      qry += '&sTipoLista='+$F('k60_tipo');
      qry += '&sTipoListaDescr='+$('k60_tipo').options[$('k60_tipo').selectedIndex].text;
      qry += '&dNotifDataLimite='+$F('data1');
      qry += '&iNotifTipo='+$F('notiftipo');
      qry += '&sNotifTipo='+$('notiftipo').options[$('notiftipo').selectedIndex].text;
      qry += '&sMassaFalida='+$F('massa');
      qry += '&sLoteamento='+$F('loteamento');
      qry += '&dDtOperIni='+$F('dtini');
      qry += '&dDtOperFim='+$F('dtfim');
      qry += '&dDtVencIni='+$F('dataini');
      qry += '&dDtVencFim='+$F('datafim');
      qry += '&iExercIni='+$F('exercini');
      qry += '&iExercFim='+$F('exercfim');
      qry += '&iIgnoraExercIni='+$F('desconexercini');
      qry += '&iIgnoraExercFim='+$F('desconexercfim');
      qry += '&iQtdParcAtrasoIni='+$F('parcini');
      qry += '&iQtdParcAtrasoFim='+$F('parcfim');
      qry += '&iNroParcAtrasoIni='+$F('numini');
      qry += '&iNroParcAtrasoFim='+$F('numfim');
      qry += '&sConsideraPosterior='+$F('considerar');
      qry += '&iOpcaoTipoDebito='+$F('opcaofiltro');
      qry += '&sOpcaoTipoDebito='+$('opcaofiltro').options[$('opcaofiltro').selectedIndex].text;
      qry += '&dtDesconsiderarDebitos='+$F('dtDesconsiderarDebitos');
      qry += parent.iframe_filtros.js_getContribuinte();
      qry += parent.iframe_filtros.js_getRuas();
      qry += parent.iframe_filtros.js_getBairros();
      qry += parent.iframe_filtros.js_getZonas();

      sTipoDebitos = '';
      sVirgula     = '';

      if($('campos').options.length > 0) {

        for(var i = 0; i < $('campos').options.length; i++) {

          sTipoDebitos += sVirgula + $('campos').options[i].value;
          sVirgula      = ',';
        }
      }

      qry += '&sTiposDebitos='+sTipoDebitos;

      /**
       * Envia as procedências selecionadas
       */
      var aProcedencias = [];
      if(oLancadorProcedencia.getRegistros().length > 0) {

        oLancadorProcedencia.getRegistros().each(function(oProcedencia) {
          aProcedencias.push(oProcedencia.sCodigo);
        });
      }

      qry += '&sProcedencias=' + aProcedencias.join(',');

      js_OpenJanelaIframe('','db_iframe_lista002','cai4_lista002.php'+qry,'Processando Lista ...',true);

      return true;
    }
    </script>
  </head>
  <body class="body-default" onLoad="js_montaFiltro();a=1">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Inclusão de Lista</legend>
          <table class="form-container">

            <tr>
              <td nowrap colspan="2">
                <fieldset class="separator">
                  <legend>Dados da Lista</legend>
                  <table>
                    <tr>
                      <td nowrap title="<?php echo $Tk60_descr?>">
                        <label for="k60_descr">Descrição:</label>
                      </td>
                      <td nowrap colspan="3">
                        <?php
                        $xopcao = $db_opcao;
                        if($db_opcao == 1) {
                          $xopcao = 3;
                        }
                        db_input('k60_descr', 10, $Ik60_descr, true, 'text', $db_opcao, "");
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td nowrap title="Data da Geração da tabela débitos">
                        <label for="data">Data Débitos :</label>
                      </td>
                      <td>
                        <?php
                        $sql    = "select k115_data as k22_data";
                        $sql   .= "  from datadebitos";
                        $sql   .= " where k115_instit = ".db_getsession("DB_instit");
                        $sql   .= " order by k115_data desc limit 1";
                        $result = db_query($sql);

                        $data_ano = '';
                        $data_mes = '';
                        $data_dia = '';

                        if (pg_numrows($result) > 0) {

                          db_fieldsmemory($result, 0);
                          $data_ano = substr($k22_data, 0, 4);
                          $data_mes = substr($k22_data, 5, 2);
                          $data_dia = substr($k22_data, 8, 2);
                        }

                        db_inputdata('data', $data_dia, $data_mes, $data_ano, true, 'text', $db_opcao)
                        ?>
                      </td>
                      <td nowrap title="Quantidade de contribuintes a ser listado, ou zero para todos">
                        <label for="numerolista22">Quantidade a Listar:</label>
                      </td>
                      <td>
                        <?php
                        db_input('numerolista22', 23, '', true, 'text', $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="Intervalo de valores a serem listados">
                        <label for="DBtxt10">Valores:</label>
                      </td>
                      <td>
                        <?php
                        db_input('DBtxt10', 10, $IDBtxt10, true, 'text', $db_opcao);
                        ?>
                        <label for="DBtxt11"> à </label>
                        <?php
                        db_input('DBtxt11', 10, $IDBtxt11, true, 'text', $db_opcao);
                        ?>
                      </td>
                      <td>
                        <label for="k60_tipo">Tipo de Lista:</label>
                      </td>
                      <td>
                        <?php
                        $x = array(
                          "N" => "Nome (  CGM Geral  )",
                          "C" => "Somente por CGM",
                          "M" => "Matrícula",
                          "I" => "Inscrição"
                        );
                        db_select('k60_tipo',$x,true,$db_opcao,"onchange='js_montaFiltro();'");
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <label for="massa">Massa Falida:</label>
                      </td>
                      <td class="field-size2">
                        <select id="massa">
                          <option value="N">NÃO</option>
                          <option value="S">SIM</option>
                        </select>
                      </td>
                      <td>
                        <label for="loteamento">Loteamentos:</label>
                      </td>
                      <td>
                        <?php
                        $x = array ("N" => "NÃO", "S" => "SIM");
                        db_select('loteamento', $x, true, $db_opcao, "");
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td nowrap title="Não lista os contribuintes notificados após esta data">
                        <label for="data1">Não Considerar Notificados Até:</label>
                      </td>
                      <td>
                        <?php
                        $data1_ano = substr(date('Y'), 0, 4);
                        $data1_mes = substr(date('m'),0,2);
                        $data1_dia =  substr(date('d'),0,2);

                        db_inputdata('data1', $data1_dia, $data1_mes, $data1_ano, true, 'text', $db_opcao);

                        $xx = array ("0" => "Geral", "1" => "Tipo de débito","2"=>"Numpre/Parcela");
                        db_select('notiftipo', $xx, true, $db_opcao, "");
                        ?>
                      </td>
                    </tr>

                  </table>
                </fieldset>
              </td>
            </tr>

            <tr>
              <td>
                <fieldset id="filtrosGerais" class="separator">
                  <legend>Filtros</legend>

                  <table>
                    <tr>
                      <td nowrap title="Data de operação do débito">
                        <label for="dtini">Data de operação:</label>
                      </td>
                      <td>
                        <?php
                        db_inputdata('dtini', "", "", "", true, 'text', $db_opcao, "");
                        ?>
                      </td>
                      <td>
                        <label for="dtfim"> à </label>
                        <?php
                        db_inputdata('dtfim', "", "", "", true, 'text', $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Td40_codigo?>">
                        <label for="dataini">Data do Vencimento:</label>
                      </td>
                      <td>
                        <?php
                        db_inputdata('dataini', "", "", "", true, 'text', $db_opcao, "");
                        ?>
                      </td>
                      <td>
                        <label for="datafim"> à </label>
                        <?php
                        db_inputdata('datafim', "", "", "", true, 'text', $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tk22_exerc?>">
                        <label for="exercini">Exercicio:</label>
                      </td>
                      <td>
                        <?php
                        db_input('exercini', 10, "Exercicio inicial", true, 'text', $db_opcao);
                        ?>
                      </td>
                      <td>
                        <label for="exercfim"> à </label>
                        <?php
                        db_input('exercfim', 10, "Exercicio final", true, 'text', $db_opcao);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tk22_exerc?>">
                        <label for="desconexercini">Desconsidera Exercícios:</label>
                      </td>
                      <td>
                        <?php
                        db_input('desconexercini', 10, "Exercicio inicial", true, 'text', $db_opcao);
                        ?>
                      </td>
                      <td>
                        <label for="desconexercfim"> à </label>
                        <?php
                        db_input('desconexercfim', 10, "Exercicio final", true, 'text', $db_opcao);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tk22_exerc?>">
                        <label for="parcini">Qtde de Parcelas em Atraso:</label>
                      </td>
                      <td>
                        <?php
                       db_input('parcini', 10, "Parcela inicial", true, 'text', $db_opcao);
                        ?>
                      </td>
                      <td>
                        <label for="parcfim"> à </label>
                        <?php
                        db_input('parcfim', 10, "Parcela final", true, 'text', $db_opcao);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tk22_exerc?>">
                        <label for="numini">Número das Parcelas em Atraso:</label>
                      </td>
                      <td class="field-size2">
                        <?php
                        db_input('numini', 10, "Número parcela inicial", true, 'text', $db_opcao);
                        ?>
                      </td>
                      <td>
                        <label for="numfim"> à </label>
                        <?php
                        db_input('numfim', 10, "Número parcela final", true, 'text', $db_opcao);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tk22_exerc?>">
                        <label for="considerar">Considerar além dos filtros:</label>
                      </td>
                      <td class="field-size2">
                        <?php
                        $x = array ("N" => "NÃO", "S" => "SIM");
                        db_select('considerar', $x, true, $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label for="dtDesconsiderarDebitos">Desconsiderar Débitos com recibo válido após:</label>
                      </td>
                      <td nowrap colspan="2">
                        <?php
                        db_inputdata('dtDesconsiderarDebitos', "", "", "", true, 'text', $db_opcao, "");
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>

            <tr>
              <td>
                <fieldset id="fieldTiposDebitos" class="separator">
                  <legend>Tipos de Débito</legend>

                  <table>
                    <tr>
                      <td>
                        <label for="opcaofiltro">Opção:</label>
                      </td>
                      <td class="field-size-max">
                        <select id="opcaofiltro">
                          <option value="1">COM os selecionados</option>
                          <option value="2">SEM os selecionados</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo$Tk00_tipo?>">
                        <?php
                        db_ancora($Lk00_tipo, "js_pesquisak00_tipo(true);", $db_opcao);
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input('k00_tipo', 8, $Ik00_tipo, true, 'text', $db_opcao, " onchange='js_pesquisak00_tipo(false);'");
                        db_input('k00_descr', 25, $Ik00_descr, true, 'text', 3, '');
                        ?>
                        <input name="lanca" type="button" value="Lançar" >
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>
                        <select name="campos[]" id="campos" size="7" multiple>
                          <?php
                          if (isset ($chavepesquisa)) {

                            $resulta = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa, "", "k00_tipo,k00_descr", ""));
                            if ($clarretipo->numrows != 0) {

                              $numrows = $clarretipo->numrows;
                              for ($i = 0; $i < $numrows; $i ++) {

                                db_fieldsmemory($resulta, $i);
                                echo "<option value=\"$k00_tipo \">$k00_descr</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </td>
                      <td align="left" valign="middle" width="20%">
                        <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
                         <br/><br/>
                        <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
                         <br/><br/>
                        <img style="cursor:hand" onClick="js_excluir()" src="skins/img.php?file=Controles/bt_excluir.png" />
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>

            <tr>
              <td>
                <fieldset id="fieldProcedencia" class="separator">
                  <legend>Procedência</legend>

                  <div id="procedencia"></div>

                </fieldset>
              </td>
            </tr>

          </table>
        </fieldset>
        <input name="db_opcao" type="button" id="db_opcao" value="Incluir" onClick="<?=$funcao_js_verifica?>" <?=($db_botao ? '' : 'disabled')?>>
      </form>
    </div>
  </body>
</html>
<script type="text/javascript">

new DBToogle('filtrosGerais', true);
new DBToogle('fieldTiposDebitos', true);
new DBToogle('fieldProcedencia', false);

var fVerificaTiposDebitos = function() {

  var aTiposDebitos = [];

  if($('campos').options.length > 0) {

    for(var i = 0; i < $('campos').options.length; i++) {
      aTiposDebitos.push($('campos').options[i].value);
    }
  }

  var sTiposDebitos = 'sTiposDebitos=' + aTiposDebitos.join(',');
  oLancadorProcedencia.setParametrosPesquisa('func_procedencia.php', ['sequencial','descricao','tipo_procedencia'], sTiposDebitos);
};

var oLancadorProcedenciaRetornoPesquisaDigitacaoOriginal;
var oLancadorProcedenciaRetornoPesquisaLookUp;


var oLancadorProcedencia = new DBLancador('oLancadorProcedencia');

    oLancadorProcedenciaRetornoPesquisaDigitacaoOriginal = oLancadorProcedencia.retornoPesquisaDigitacao;
    oLancadorProcedencia.retornoPesquisaDigitacao = function () {

      var sequencial, tipo_procedencia;
      sequencial         = arguments[0];
      tipo_procedencia   = arguments[2];

      oLancadorProcedenciaRetornoPesquisaDigitacaoOriginal.apply(this, arguments);
      this.oElementos.oInputCodigo.value = sequencial +'-'+ tipo_procedencia;

    }.bind(oLancadorProcedencia);
    
    oLancadorProcedenciaRetornoPesquisaLookUp = oLancadorProcedencia.retornoPesquisaLookUp;
    oLancadorProcedencia.retornoPesquisaLookUp = function () {

      var sequencial, tipo_procedencia;
      sequencial         = arguments[0];
      tipo_procedencia   = arguments[2];
      
      oLancadorProcedenciaRetornoPesquisaLookUp.apply(this, arguments);
      this.oElementos.oInputCodigo.value = sequencial +'-'+ tipo_procedencia;

    }.bind(oLancadorProcedencia);

    oLancadorProcedencia.setNomeInstancia('oLancadorProcedencia');
    oLancadorProcedencia.setLabelAncora('Procedência: ');
    oLancadorProcedencia.setTituloJanela('Pesquisar Procedência');
    oLancadorProcedencia.setGridHeight(100);
    oLancadorProcedencia.setCallbackAncora(fVerificaTiposDebitos);
    oLancadorProcedencia.show($('procedencia'));

function js_pesquisak00_tipo(mostra) {

  document.form1.lanca.onclick = "";

  var sFrame        = 'db_iframe_arretipo';
  var sTitulo       = 'Pesquisar Tipo de Débito';
  var aProcedencias = [];
  var sProcedencias = '&sProcedencias=' + aProcedencias.join(',');

  if(mostra == true) {

    js_OpenJanelaIframe(
      '',
      sFrame,
      'func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr' + sProcedencias,
      sTitulo,
      mostra
    );
  } else {

    js_OpenJanelaIframe(
      '',
      sFrame,
      'func_arretipo.php?pesquisa_chave='+document.form1.k00_tipo.value+'&funcao_js=parent.js_mostraarretipo' + sProcedencias,
      sTitulo,
      mostra
    );
  }
}

function js_mostraarretipo(chave, erro) {

  document.form1.k00_descr.value = chave;

  if(erro == true) {

    document.form1.k00_tipo.focus();
    document.form1.k00_tipo.value = '';
  } else {
    document.form1.lanca.onclick = js_insSelect;
  }
}

function js_mostraarretipo1(chave1,chave2) {

  document.form1.k00_tipo.value  = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
  document.form1.lanca.onclick  = js_insSelect;
}

function js_pesquisa(){

  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}

function js_preenchepesquisa(chave){

  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_montaFiltro(){

  var sTipo   = $F('k60_tipo');

  parent.document.formaba.filtros.disabled = false;
  parent.iframe_filtros.location.href      = "cai4_listafiltros003.php?iFiltro="+sTipo+" ";
}

$('k60_descr').addClassName('field-size-max');
$('data').addClassName('field-size2');
$('massa').addClassName('field-size2');
$('data1').addClassName('field-size2');
$('DBtxt10').addClassName('field-size2');
$('DBtxt11').addClassName('field-size2');
$('notiftipo').addClassName('field-size2');
$('numerolista22').addClassName('field-size-max');
$('k00_tipo').addClassName('field-size2');

$('k00_descr').setStyle({'width': '362px'});
$('considerar').setStyle({'width': '94px'});
$('massa').setStyle({'width': '84px'});
</script>