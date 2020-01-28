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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_db_confplan_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 2;
$db_botao = true;
$q144_ano = db_getsession('DB_anousu');

$cldb_confplan = new cl_db_confplan();
$cldb_confplan->rotulo->label();

$clconfvencissqnvariavel = new cl_confvencissqnvariavel();
$clconfvencissqnvariavel->rotulo->label();

$clrotulo = new rotulocampo();
$clrotulo->label('k02_descr');
$clrotulo->label('k01_descr');
$clrotulo->label('k00_descr');
$clrotulo->label('q92_descr');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxRequest.js"></script>
<script type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_carregar();" >
<div class="container">
  <form id="form1" name="form1" method="post" action="">
    <table border="0" align="center" style="text-align: center;">
      <tr>
        <td>
          <fieldset>
            <legend>Configuração da Planilha</legend>
            <fieldset>
              <legend>ISSQN Retido</legend>
              <table border="0" align="left">
                <tr id="valorminimoretido">
                  <td nowrap title="<?php echo @$Tw10_valor?>">
                    <input id="w10_oid" name="w10_oid" type="hidden" value="">
                    <?php echo @$Lw10_valor?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_valor',6,$Iw10_valor,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
                <tr id="receitaretido">
                  <td nowrap title="<?php echo @$Tw10_receit?>">
                    <?php
                    db_ancora(@$Lw10_receit,"js_pesquisareceita(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_receit',6,$Iw10_receit,true,'text',$db_opcao," onchange='js_pesquisareceita(false);'");
                    db_input('k02_descr',40,$Ik02_descr,true,'text',3,'','k02_descr_retido');
                    ?>
                  </td>
                </tr>
                <tr id="historicoretido">
                  <td nowrap title="<?php echo @$Tw10_hist?>">
                    <?php
                    db_ancora(@$Lw10_hist,"js_pesquisahistorico(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_hist',6,$Iw10_hist,true,'text',$db_opcao," onchange='js_pesquisahistorico(false);'");
                    db_input('k01_descr',40,$Ik01_descr,true,'text',3,'','k01_descr_retido');
                    ?>
                  </td>
                </tr>
                <tr id="tipodebitoretido">
                  <td nowrap title="<?php echo @$Tw10_tipo?>">
                    <?php
                    db_ancora(@$Lw10_tipo,"js_pesquisatipodebito(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_tipo',6,$Iw10_tipo,true,'text',$db_opcao," onchange='js_pesquisatipodebito(false);'");
                    db_input('k00_descr',40,$Ik00_descr,true,'text',3,'','k00_descr_retido');
                    ?>
                  </td>
                </tr>
                <tr id="diavencimentoretido">
                  <td nowrap title="<?php echo @$Tw10_dia?>">
                    <?php echo @$Lw10_dia?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_dia',6,$Iw10_dia,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend>ISSQN Variável</legend>
              <table border="0" align="left">
                <tr>
                  <td nowrap title="<?php echo @$Tw10_valor?>">
                    <input id="q144_sequencial" name="q144_sequencial" type="hidden" value="">
                    <?php echo @$Lq144_ano?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_ano',6,$Iq144_ano,true,'text',3);
                    ?>
                  </td>
                </tr>
                <tr id="vencimentovariavel">
                  <td nowrap title="<?php echo @$Tq144_codvenc?>">
                    <?php
                    db_ancora(@$Lq144_codvenc,"js_pesquisavencimento(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_codvenc',6,$Iq144_codvenc,true,'text',$db_opcao," onchange='js_pesquisavencimento(false);'");
                    db_input('q92_descr',40,$Iq92_descr,true,'text',3,'','q92_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="receitavariavel">
                  <td nowrap title="<?php echo @$Tq144_receita?>">
                    <?php
                    db_ancora(@$Lq144_receita,"js_pesquisareceitavariavel(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_receita',6,$Iq144_receita,true,'text',$db_opcao," onchange='js_pesquisareceitavariavel(false);'");
                    db_input('k02_descr',40,$Ik02_descr,true,'text',3,'','k02_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="tipodebitovariavel">
                  <td nowrap title="<?php echo @$Tq144_tipo?>">
                    <?php
                    db_ancora($Lq144_tipo,"js_pesquisatipodebitovariavel(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_tipo',6,$Iq144_tipo,true,'text',$db_opcao," onchange='js_pesquisatipodebitovariavel(false);'");
                    db_input('k00_descr',40,$Ik00_descr,true,'text',3,'','k00_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="historicovariavel">
                  <td nowrap title="<?php echo @$Tq144_hist?>">
                    <?php
                    db_ancora(@$Lq144_hist,"js_pesquisahistoricovariavel(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_hist',6,$Iw10_hist,true,'text',$db_opcao," onchange='js_pesquisahistoricovariavel(false);'");
                    db_input('k01_descr',40,$Ik01_descr,true,'text',3,'','k01_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="diavencimentovariavel">
                  <td nowrap title="<?php echo @$Tq144_diavenc?>">
                    <?php echo @$Lq144_diavenc?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_diavenc',6,$Iq144_diavenc,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <input type="button" name="db_opcao" id="db_opcao" value="Salvar" <?php echo ($db_botao==false?"disabled":'')?> onclick="js_salvar();">
        </td>
      </tr>
    </table>
  </form>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  /**
   * Hints do formulario
   */
  var aEventoShow = new Array('onMouseover','onFocus');
  var aEventoHide = new Array('onMouseout' ,'onBlur');

  var oDbHintVencimento = new DBHint('oDbHintVencimento');
  oDbHintVencimento.setText('Nesse campo irá informar o código do vencimento que será vinculado ao valor cálculado de ISSQN.');
  oDbHintVencimento.setShowEvents(aEventoShow);
  oDbHintVencimento.setHideEvents(aEventoHide);
  oDbHintVencimento.make($('vencimentovariavel'));

  var oDbHintReceita = new DBHint('oDbHintReceita');
  oDbHintReceita.setText('Nesse campo irá informar a receita padrão para o cálculo geral de ISSQN.');
  oDbHintReceita.setShowEvents(aEventoShow);
  oDbHintReceita.setHideEvents(aEventoHide);
  oDbHintReceita.make($('receitaretido'));
  oDbHintReceita.make($('receitavariavel'));

  var oDbHintHistoricoCalculo = new DBHint('oDbHintHistoricoCalculo');
  oDbHintHistoricoCalculo.setText('Nesse campo irá informar o histórico de cálculo que será vinculado ao valor cálculado de ISSQN.');
  oDbHintHistoricoCalculo.setShowEvents(aEventoShow);
  oDbHintHistoricoCalculo.setHideEvents(aEventoHide);
  oDbHintHistoricoCalculo.make($('historicoretido'));
  oDbHintHistoricoCalculo.make($('historicovariavel'));

  var oDbHintTipoDebito = new DBHint('oDbHintTipoDebito');
  oDbHintTipoDebito.setText('Nesse campo irá informar o tipo de débito que será vinculado ao valor cálculado de ISSQN.');
  oDbHintTipoDebito.setShowEvents(aEventoShow);
  oDbHintTipoDebito.setHideEvents(aEventoHide);
  oDbHintTipoDebito.make($('tipodebitoretido'));
  oDbHintTipoDebito.make($('tipodebitovariavel'));

  var oDbHintDiaVencimento = new DBHint('oDbHintDiaVencimento');
  oDbHintDiaVencimento.setText('Nesse campo irá informar o dia de vencimento para o cálculo geral de ISSQN.');
  oDbHintDiaVencimento.setShowEvents(aEventoShow);
  oDbHintDiaVencimento.setHideEvents(aEventoHide);
  oDbHintDiaVencimento.make($('diavencimentoretido'));
  oDbHintDiaVencimento.make($('diavencimentovariavel'));

  var oDbHintValorMin = new DBHint('oDbHintValorMin');
  oDbHintValorMin.setText('Nesse campo poderá informar o valor minímo que será lançado para o cálculo geral de ISSQN.');
  oDbHintValorMin.setShowEvents(aEventoShow);
  oDbHintValorMin.setHideEvents(aEventoHide);
  oDbHintValorMin.make($('valorminimoretido'));

  function js_pesquisareceita(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_tabrec_retido', 'func_tabrec.php?funcao_js=parent.js_mostrareceita1|k02_codigo|k02_drecei', 'Pesquisa', true);
    } else {
      if (document.form1.w10_receit.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_tabrec_retido', 'func_tabrec.php?pesquisa_chave=' + document.form1.w10_receit.value + '&funcao_js=parent.js_mostrareceita', 'Pesquisa', false);
      } else {
        document.form1.k02_descr_retido.value = '';
      }
    }
  }
  function js_mostrareceita(chave, erro) {
    document.form1.k02_descr_retido.value = chave;
    if (erro == true) {
      document.form1.w10_receit.focus();
      document.form1.w10_receit.value = '';
    }
  }
  function js_mostrareceita1(chave1, chave2) {
    document.form1.w10_receit.value = chave1;
    document.form1.k02_descr_retido.value = chave2;
    db_iframe_tabrec_retido.hide();
  }
  function js_pesquisahistorico(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_histcalc_retido', 'func_histcalc.php?funcao_js=parent.js_mostrahistorico1|k01_codigo|k01_descr', 'Pesquisa', true);
    } else {
      if (document.form1.w10_hist.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_histcalc_retido', 'func_histcalc.php?pesquisa_chave=' + document.form1.w10_hist.value + '&funcao_js=parent.js_mostrahistorico', 'Pesquisa', false);
      } else {
        document.form1.k01_descr_retido.value = '';
      }
    }
  }
  function js_mostrahistorico(chave, erro) {
    document.form1.k01_descr_retido.value = chave;
    if (erro == true) {
      document.form1.w10_hist.focus();
      document.form1.w10_hist.value = '';
    }
  }
  function js_mostrahistorico1(chave1, chave2) {
    document.form1.w10_hist.value = chave1;
    document.form1.k01_descr_retido.value = chave2;
    db_iframe_histcalc_retido.hide();
  }
  function js_pesquisatipodebito(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_arretipo_retido', 'func_arretipo.php?funcao_js=parent.js_mostratipodebito1|k00_tipo|k00_descr', 'Pesquisa', true);
    } else {
      if (document.form1.w10_tipo.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_arretipo_retido', 'func_arretipo.php?pesquisa_chave=' + document.form1.w10_tipo.value + '&funcao_js=parent.js_mostratipodebito', 'Pesquisa', false);
      } else {
        document.form1.k00_descr_retido.value = '';
      }
    }
  }
  function js_mostratipodebito(chave, erro) {
    document.form1.k00_descr_retido.value = chave;
    if (erro == true) {
      document.form1.w10_tipo.focus();
      document.form1.w10_tipo.value = '';
    }
  }
  function js_mostratipodebito1(chave1, chave2) {
    document.form1.w10_tipo.value = chave1;
    document.form1.k00_descr_retido.value = chave2;
    db_iframe_arretipo_retido.hide();
  }
  function js_pesquisavencimento(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_cadvencdesc_variavel', 'func_cadvencdesc.php?funcao_js=parent.js_mostravencimento1|q92_codigo|q92_descr', 'Pesquisa', true);
    } else {
      if (document.form1.q144_codvenc.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_cadvencdesc_variavel', 'func_cadvencdesc.php?pesquisa_chave=' + document.form1.q144_codvenc.value + '&funcao_js=parent.js_mostravencimento', 'Pesquisa', false);
      } else {
        document.form1.q92_descr_variavel.value = '';
      }
    }
  }
  function js_mostravencimento(chave, erro) {
    document.form1.q92_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_codvenc.focus();
      document.form1.q144_codvenc.value = '';
    }
  }
  function js_mostravencimento1(chave1, chave2) {
    document.form1.q144_codvenc.value = chave1;
    document.form1.q92_descr_variavel.value = chave2;
    db_iframe_cadvencdesc_variavel.hide();
  }
  function js_pesquisareceitavariavel(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_tabrec_variavel', 'func_tabrec.php?funcao_js=parent.js_mostrareceitavariavel1|k02_codigo|k02_drecei', 'Pesquisa', true);
    } else {
      if (document.form1.q144_receita.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_tabrec_variavel', 'func_tabrec.php?pesquisa_chave=' + document.form1.q144_receita.value + '&funcao_js=parent.js_mostrareceitavariavel', 'Pesquisa', false);
      } else {
        document.form1.k02_descr_variavel.value = '';
      }
    }
  }
  function js_mostrareceitavariavel(chave, erro) {
    document.form1.k02_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_receita.focus();
      document.form1.q144_receita.value = '';
    }
  }
  function js_mostrareceitavariavel1(chave1, chave2) {
    document.form1.q144_receita.value = chave1;
    document.form1.k02_descr_variavel.value = chave2;
    db_iframe_tabrec_variavel.hide();
  }
  function js_pesquisatipodebitovariavel(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_arretipo_variavel', 'func_arretipo.php?funcao_js=parent.js_mostratipodebitovariavel1|k00_tipo|k00_descr', 'Pesquisa', true);
    } else {
      if (document.form1.q144_tipo.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_arretipo_variavel', 'func_arretipo.php?pesquisa_chave=' + document.form1.q144_tipo.value + '&funcao_js=parent.js_mostratipodebitovariavel', 'Pesquisa', false);
      } else {
        document.form1.k00_descr_variavel.value = '';
      }
    }
  }
  function js_mostratipodebitovariavel(chave, erro) {
    document.form1.k00_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_tipo.focus();
      document.form1.q144_tipo.value = '';
    }
  }
  function js_mostratipodebitovariavel1(chave1, chave2) {
    document.form1.q144_tipo.value = chave1;
    document.form1.k00_descr_variavel.value = chave2;
    db_iframe_arretipo_variavel.hide();
  }
  function js_pesquisahistoricovariavel(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_histcalc_variavel', 'func_histcalc.php?funcao_js=parent.js_mostrahistoricovariavel1|k01_codigo|k01_descr', 'Pesquisa', true);
    } else {
      if (document.form1.q144_hist.value != '') {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_histcalc_variavel', 'func_histcalc.php?pesquisa_chave=' + document.form1.q144_hist.value + '&funcao_js=parent.js_mostrahistoricovariavel', 'Pesquisa', false);
      } else {
        document.form1.k01_descr_variavel.value = '';
      }
    }
  }
  function js_mostrahistoricovariavel(chave, erro) {
    document.form1.k01_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_hist.focus();
      document.form1.q144_hist.value = '';
    }
  }
  function js_mostrahistoricovariavel1(chave1, chave2) {
    document.form1.q144_hist.value = chave1;
    document.form1.k01_descr_variavel.value = chave2;
    db_iframe_histcalc_variavel.hide();
  }
  function js_salvar() {

    var oParametro = {
      'exec'            : 'salvar',
      'w10_oid'         : $('w10_oid').value,
      'w10_valor'       : $('w10_valor').value,
      'w10_receit'      : $('w10_receit').value,
      'w10_hist'        : $('w10_hist').value,
      'w10_tipo'        : $('w10_tipo').value,
      'w10_dia'         : $('w10_dia').value,
      'q144_sequencial' : $('q144_sequencial').value,
      'q144_ano'        : $('q144_ano').value,
      'q144_codvenc'    : $('q144_codvenc').value,
      'q144_receita'    : $('q144_receita').value,
      'q144_tipo'       : $('q144_tipo').value,
      'q144_hist'       : $('q144_hist').value,
      'q144_diavenc'    : $('q144_diavenc').value
    };

    new AjaxRequest('pre4_db_confplan.RPC.php', oParametro,
      function (oRetorno, lErro) {
        if (lErro) {
          alert(oRetorno.message.urlDecode());
          return false;
        } else {
          alert(oRetorno.message.urlDecode());
          js_carregar();
        }
      }
    ).setMessage('Buscando configurações...').execute();
  }
  function js_carregar() {

    new AjaxRequest('pre4_db_confplan.RPC.php', {'exec' : 'pesquisar'},
      function (oRetorno, lErro) {
        if (lErro) {
          alert(oRetorno.message.urlDecode());
          return false;
        } else {
          oRetorno.aDados.each(
            function (oCampo) {
              // ISSQN Retido
              if (oCampo.oid) {
                $('w10_oid').value    = oCampo.oid;
                $('w10_valor').value  = oCampo.w10_valor;
                $('w10_receit').value = oCampo.w10_receit;
                $('w10_hist').value   = oCampo.w10_hist;
                $('w10_tipo').value   = oCampo.w10_tipo;
                $('w10_dia').value    = oCampo.w10_dia;
              }
              // ISSQN Variável
              if (oCampo.q144_sequencial) {
                $('q144_sequencial').value = oCampo.q144_sequencial;
                $('q144_ano').value        = oCampo.q144_ano;
                $('q144_codvenc').value    = oCampo.q144_codvenc;
                $('q144_receita').value    = oCampo.q144_receita;
                $('q144_tipo').value       = oCampo.q144_tipo;
                $('q144_hist').value       = oCampo.q144_hist;
                $('q144_diavenc').value    = oCampo.q144_diavenc;
              }
            }
          );
          js_pesquisareceita(false);
          js_pesquisahistorico(false);
          js_pesquisatipodebito(false);
          js_pesquisavencimento(false);
          js_pesquisareceitavariavel(false);
          js_pesquisatipodebitovariavel(false);
          js_pesquisahistoricovariavel(false);
        }
      }
    ).setMessage('Salvando configurações...').execute();
  }
</script>