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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$cldb_depart 		= new cl_db_depart;
$clcfpatri 			= new cl_cfpatri;
$clbens      		= new cl_bens;
$clclabens   		= new cl_clabens;
$cldepartdiv 		= new cl_departdiv;
$cldb_estrut 		= new cl_db_estrut;
$oAuxDpto       = new cl_arquivo_auxiliar;
$oAuxDpto       = new cl_arquivo_auxiliar;
$iAnoSessao     = db_getsession("DB_anousu");

$clbens->rotulo->label();
$clclabens->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<script type="text/javascript" src="scripts/scripts.js"></script>
	<script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1">
    	<fieldset>
    		<legend>Relatório de Depreciações</legend>
        <table>
          <tr>
            <td colspan="2"><div id="oLancadorDepartamento"></td>
          </tr>
          <tr>
            <td colspan="2"><div id="oLancadorContas"></td>
          </tr>
          <tr>
            <td colspan="2">
              <fieldset class="separator">
                <legend>Classificações</legend>
                <table class="form-container">
                  <tr title="<?= $Tt64_class; ?>" >
                    <td >
                      <?php db_ancora("<b>De:</b> ","js_pesquisaClasseInicial(true);",1);?>
                    </td>
                    <td nowrap="nowrap">
                      <?php
                        db_input('t64_classInicio',10,$It64_class,true,'text',3,'');
                        db_input('t64_descrInicio',30,$It64_descr,true,'text',3,'');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <?php db_ancora("<b>Até:</b>","js_pesquisaClasseFinal(true);",1); ?>
                    </td>
                    <td nowrap="nowrap">
                      <?php
                        db_input('t64_classFinal',10,$It64_class,true,'text',3,'');
                        db_input('t64_descrFinal',30,$It64_descr,true,'text',3,'');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>

            </td>
          </tr>
          <tr>
            <td>
              <label class="bold" for="periodoInicial">Período:</label>
            </td>
            <td>
              <?php db_inputdata('periodoInicial',null, null, null, true,'text',1,""); ?>
              <label class="bold" for="periodoFinal">a</label>
              <?php db_inputdata('periodoFinal',null, null, null, true,'text',1,""); ?>
            </td>
       	  </tr>
          <tr>
            <td nowrap style="width: 1%">
              <label class="bold" for="impressao">Tipo de Impressão:</label>
            </td>
            <td>
              <?php
                $aTipoImpressao = array("S"=>"Sintético","A"=>"Análitico", "T"=>"Acumulado");
                db_select("impressao", $aTipoImpressao, true, 1);
              ?>
            </td>
          </tr>
        </table>
    	</fieldset>
      <input type="button" name="btnProcessar" id="btnProcessar" value="Emitir">
    </form>
  </div>
  <?php db_menu(); ?>
  <script type="text/javascript">

    var oLancadorDepartamento = new DBLancador("oLancadorDepartamento");
    oLancadorDepartamento.setNomeInstancia("oLancadorDepartamento");
    oLancadorDepartamento.setLabelAncora("Departamento:");
    oLancadorDepartamento.setLabelValidacao("Departamento");
    oLancadorDepartamento.setTextoFieldset("Departamentos");
    oLancadorDepartamento.setParametrosPesquisa("func_db_depart.php", ['coddepto', 'descrdepto']);
    oLancadorDepartamento.setGridHeight("400px");
    oLancadorDepartamento.setTituloJanela("Pesquisar Departamentos");
    oLancadorDepartamento.show($("oLancadorDepartamento"));

    var oLancadorContas = new DBLancador("oLancadorContas");
    oLancadorContas.setNomeInstancia("oLancadorContas");
    oLancadorContas.setLabelAncora("Código:");
    oLancadorContas.setLabelValidacao("Código");
    oLancadorContas.setTextoFieldset("Contas Contábeis");
    oLancadorContas.setParametrosPesquisa("func_clabensconta.php", ['c60_codcon', 'c60_descr']);
    oLancadorContas.setGridHeight("400px");
    oLancadorContas.setTituloJanela("Pesquisar Contas");
    oLancadorContas.show($("oLancadorContas"));
    /**
     * Verificar se vai ser usado classe mais descrição
     */
    function js_pesquisaClasseInicial(mostra) {

      if (mostra==true) {

        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_clabens',
                            'func_clabens.php?funcao_js=parent.js_mostraClasseInicial1|t64_class|t64_descr&analitica=true',
                            'Pesquisa',
                            true);
      } else {

         testa = new String($F("t64_classInicio"));
         if (testa != '' && testa != 0) {

           i = 0;
           for (i = 0;i < document.form1.t64_classInicio.value.length;i++) {
             testa = testa.replace('.','');
           }
           js_OpenJanelaIframe('CurrentWindow.corpo',
                               'db_iframe_clabens',
                               'func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraClasseInicial&analitica=true',
                               'Pesquisa',false);
         }else{
           $("t64_descrInicio").value = '';
         }
      }
    }

    function js_mostraClasseInicial(chave, erro) {

      $("t64_descrInicio").value = chave;
      if (erro) {

        $("t64_classInicio").value = '';
        $("t64_classInicio").focus();
      }
    }

    function js_mostraClasseInicial1(chave1, chave2) {

      $("t64_classInicio").value = chave1;
      $("t64_descrInicio").value = chave2;
      db_iframe_clabens.hide();
    }

    /**
     * Pesquisa Classe Final
     */
    function js_pesquisaClasseFinal(mostra) {

      if (mostra) {

        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_clabens',
                            'func_clabens.php?funcao_js=parent.js_mostraClasseFinal1|t64_class|t64_descr&analitica=true',
                            'Pesquisa',
                            true);
      } else {

         testa = new String($F("t64_classFinal"));
         if (testa != '' && testa != 0) {

           i = 0;
           for (i = 0;i < document.form1.t64_classFinal.value.length; i++) {
             testa = testa.replace('.','');
           }
           js_OpenJanelaIframe('CurrentWindow.corpo',
                               'db_iframe_clabens',
                               'func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraClasseFinal&analitica=true',
                               'Pesquisa',false);
         }else{
           $("t64_descrFinal").value = '';
         }
      }
    }

    function js_mostraClasseFinal(chave, erro) {

      $("t64_descrFinal").value = chave;
      if (erro) {

        $("t64_classFinal").value = '';
        $("t64_classFinal").focus();
      }
    }

    function js_mostraClasseFinal1(chave1, chave2) {

      $("t64_classFinal").value = chave1;
      $("t64_descrFinal").value = chave2;
      db_iframe_clabens.hide();
    }

    /**
     * Quando clicado em Imprimir, processa as informações e envia ao fonte do relatório.
     */
    $("btnProcessar").observe("click", function() {

      var sDepartamentos = "";
      var sDataInico     = $F("periodoInicial");
      var sDataFinal     = $F("periodoFinal");
      var sDepartamentos = oLancadorDepartamento.getRegistros().map(function (obj) {
        return obj.sCodigo;
      }).join(',');
      var sClasseInicio  = $F("t64_classInicio");
      var sClasseFim     = $F("t64_classFinal");
      var sImpressao     = $F("impressao");
      var sContasContabeis = oLancadorContas.getRegistros().map(function (obj) {
        return obj.sCodigo;
      }).join(',');

      if (sDataInico !="" && sDataFinal !="") {

        if (js_comparadata(sDataInico, sDataFinal, ">")) {
          return alert(_M("patrimonial.patrimonio.pat2_bensdepreciacao001.periodo_inicial_menor_periodo_final"));
        }
      }

      if ($F("periodoInicial_ano") != $F("periodoFinal_ano") ) {
        return alert(_M("patrimonial.patrimonio.pat2_bensdepreciacao001.anos_diferentes"));
      }

      oRelatorio = new EmissaoRelatorio("pat2_bensdepreciacao002.php", {
          sDataInicio : sDataInico,
          sDataFinal : sDataFinal,
          sClasseInicio : sClasseInicio,
          sClasseFim : sClasseFim,
          sDepartamentos : sDepartamentos,
          sImpressao: sImpressao,
          sContasContabeis : oLancadorContas.getRegistros().map(function (obj) {
            return obj.sCodigo;
          }).join(',')
        });

        oRelatorio.open();
    });
  </script>
</body>
</html>
