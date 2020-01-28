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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory( $_POST );

$oDaoTfdAgendaSaida = new cl_tfd_agendasaida();
$oDaoTfdAgendaSaida->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label("tf03_i_codigo");
$oRotulo->label("tf03_c_descr");

$data1 = $data2 = date('d/m/Y', db_getsession('DB_datausu'));
$dia1  = $dia2  = date('d', db_getsession('DB_datausu'));
$mes1  = $mes2  = date('m', db_getsession('DB_datausu'));
$ano1  = $ano2  = date('Y', db_getsession('DB_datausu'));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load(" prototype.js, strings.js, webseller.js, scripts.js ");
    db_app::load(" estilos.css, dbautocomplete.widget.js ");
    ?>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Período de Saída</legend>
          <table class="form-container">
            <tr>
              <td title="Data de Saída.">
                <label for="data1">Início:</label>
              </td>
              <td>
                <?php
                db_inputdata('data1', $dia1, $mes1, $ano1, true, 'text', 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="data2">Fim:</label>
              </td>
              <td title="Data de Saída.">
                <?php
                db_inputdata('data2', $dia2, $mes2, $ano2, true, 'text', 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="sTipo">Tipo:</label>
              </td>
              <td>
                <select id="sTipo">
                  <option value="0">VIAGENS - VEÍCULOS</option>
                  <option value="3">VIAGENS - TRANSPORTE COLETIVO</option>
                  <option value="1">SAÍDAS COM TOTAIS</option>
                  <option value="2">SAÍDAS SEM TOTAIS</option>
                </select>
              </td>
            </tr>
            <tr>
              <td title="Destino dos pedidos.">
                <label for="tf03_i_codigo">
                  <?php
                  db_ancora("Destino:", "js_pesquisadestino(true);", 1);
                  ?>
                </label>
              </td>
              <td colspan="5">
                <?php
                db_input('tf03_i_codigo', 10, $Itf03_i_codigo, true, 'hidden', 3);
                db_input('tf03_c_descr',  65, $Itf03_c_descr,  true, 'text',   1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" name="relatorio" id="relatorio" value="Relatório" onclick="js_relatorio();">
      </form>
    </div>
  </body>
</html>
<?php
db_menu();
?>
<script>
/* AUTOCOMPLETE DESTINO */

oAutoCompleteDestino  = new dbAutoComplete($('tf03_c_descr'), 'sau4_autocompletesaude.RPC.php');
oAutoCompleteDestino.setTxtFieldId($('tf03_c_descr'));
oAutoCompleteDestino.setHeightList(180);
oAutoCompleteDestino.show();
oAutoCompleteDestino.setCallBackFunction(function(iId, sLabel) {

                                          $('tf03_i_codigo').value = iId;
                                          $('tf03_c_descr').value  = sLabel;
                                         });

oAutoCompleteDestino.setQueryStringFunction(function() { 

                                              $('tf03_i_codigo').value = '';
                                              var oParamComplete       = new Object();
                                              oParamComplete.exec      = 'DesinoPedidoTFD';
                                              oParamComplete.string    = $('tf03_c_descr').value;
                                              return 'json='+Object.toJSON(oParamComplete); 
                                            });

/* FIM AUTOCOMPLETE DESTINO */

function js_pesquisadestino() {

  var sCampos = 'tf03_i_codigo|tf03_c_descr';
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_tfd_destino',
                       'func_tfd_destino.php?funcao_js=parent.js_mostradestino|'+ sCampos,
                       'Pesquisa Destino',
                       true
                     );
}

function js_mostradestino(tf03_i_codigo, tf03_c_descr) {

  $('tf03_i_codigo').value  = tf03_i_codigo; 
  $('tf03_c_descr').value   = tf03_c_descr; 
  db_iframe_tfd_destino.hide();
}

function js_relatorio() {

  if (!js_dadosValidos()) {
    return;
  }

  if ( $F('tf03_c_descr') == '' ) {
    $('tf03_i_codigo').value = '';
  }

  var sArquivo = $F('sTipo') == 3 ? 'tfd2_saidaspassagens002.php' : 'tfd2_saidas002.php';

  var sVariaveis  = '?dataInicial=' + $('data1').value;
      sVariaveis += '&dataFinal=' + $('data2').value;
      sVariaveis += '&destino=' + $('tf03_i_codigo').value;
      sVariaveis += '&tipo=' + $('sTipo').value;
      sVariaveis += '&iDestino=' + $F('tf03_i_codigo');
      sVariaveis += '&sDestino=' + $F('tf03_c_descr');

  var oJan = window.open(sArquivo + sVariaveis,'',
                               'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                               ',scrollbars=1,location=0 '
                               );
  oJan.moveTo(0, 0);
}

function js_dadosValidos() {

   if ($('data1').value == '') {

     alert('Informe a Data Inicial.');
     return false;
  }

  if ($('data2').value == '') {

    alert('Informe a Data Final.');
    return false;
  }

  dWsDate1 = new wsDate($('data1').value);
  if (dWsDate1.thisHigher($('data2').value)) {
          
    alert('Data Inicial superior a data final.');
    return false;
  }

  return true;
}

$('data1').className        = 'field-size2';
$('data2').className        = 'field-size2';
$('sTipo').className        = 'field-size-max';
$('tf03_c_descr').className = 'field-size-max';
</script>
