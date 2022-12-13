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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo;
$oRotulo->label("tf18_i_destino");
$oRotulo->label("tf18_i_veiculo");
$oRotulo->label("tf18_c_horasaida");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name='form1'>
      <fieldset>
        <legend>
          <?php
          if( isset( $menu ) ) {
            echo 'Relatório Saída de Veículo';
          } else {
            echo 'Relatório Lista de Passageiros para o DAER';
          }
          ?>
        </legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ttf18_i_destino?>">
              <?php
              db_ancora( '<b>Destino:</b>', "js_pesquisatf18_i_destino(true);", "" );
              ?>
            </td>
            <td nowrap>
              <?php
              $sChange = " onchange='js_pesquisatf18_i_destino(false);'";
              db_input( 'tf18_i_destino', 10, @$Itf03_i_codigo, true, 'text', "", $sChange );
              db_input( 'tf03_c_descr',   50, @$Itf03_c_descr,  true, 'text', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf18_i_veiculo?>">
              <?php
              db_ancora( @$Ltf18_i_veiculo, "js_pesquisatf18_i_veiculo(true);", "" );
              ?>
            </td>
            <td nowrap>
              <?php
              $sChange = " onchange='js_pesquisatf18_i_veiculo(false);'";
              db_input( 'tf18_i_veiculo',   10, $Itf18_i_veiculo,    true, 'text', "", $sChange );
              db_input( 've01_veiccadtipo', 50, @$Ive01_veiccadtipo, true, 'text', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve01_placa?>">
              <label class="bold">Placa:</label>
            </td>
            <td nowrap>
              <?php
              db_input( 've01_placa', 8, @$Ive01_placa, true, 'text', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold">Data de Saída:</label>
            </td>
            <td nowrap>
              <?php
               db_inputdata(
                             'tf18_d_datasaida',
                             @$tf18_d_datasaida_dia,
                             @$tf18_d_datasaida_mes,
                             @$tf18_d_datasaida_ano,
                             true,
                             'text',
                             1,
                             ' onchange="js_verificaData();"',
                             '',
                             '',
                             ' parent.js_getHorariosData(); '
                           );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold">Hora de Saída:</label>
            </td>
            <td nowrap>
              <?php
              $aX = array( '' => '' );
              db_select( 'tf18_c_horasaida', $aX, true, 1, " onchange=\"js_loadGridCgs();\"" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name='start' type='button' value='Gerar' onclick="js_mandaDados()">
    </form>
  </div>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
?>

<script>if ('<?=isset($menu)?>' == '1') {
   iMenu = 1;
}

var sUrl = 'tfd4_pedidotfd.RPC.php';
var iMenu = 0;
if ('<?=isset($menu)?>' == '1') {
   iMenu = 1;
}

function js_ajax(oParam, jsRetorno) {

	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method    : 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax) {
                          				var evlJS = jsRetorno+'(objAjax);';
                                  return eval(evlJS);
                          			}
                         }
                        );

}

function js_pesquisatf18_i_destino(mostra) {
	
  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_tfd_destino', 'func_tfd_destino.php?'+
                        'funcao_js=parent.js_mostradestino1|tf03_i_codigo|tf03_c_descr&chave_validade=true',
                        'Pesquisa', true);
  } else {
	  
    if (document.form1.tf18_i_destino.value != '') {

      js_OpenJanelaIframe('','db_iframe_tfd_destino', 'func_tfd_destino.php?&pesquisa_chave='+
                          document.form1.tf18_i_destino.value+'&funcao_js=parent.js_mostradestino&chave_validade=true',
                          'Pesquisa', false);

    } else {
      document.form1.tf03_c_descr.value = ''; 
    }
  }
}

function js_mostradestino(chave, erro) {
	
  document.form1.tf03_c_descr.value = chave; 
  if (erro == true) {
	   
    document.form1.tf18_i_destino.focus(); 
    document.form1.tf18_i_destino.value = '';
  } else {
    js_getHorariosData();
  }
}

function js_mostradestino1(chave1, chave2) {
	
  document.form1.tf18_i_destino.value = chave1;
  document.form1.tf03_c_descr.value   = chave2;
  db_iframe_tfd_destino.hide();
  js_getHorariosData();
}

function js_pesquisatf18_i_veiculo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_veiculos', 'func_tfd_veiculostfd.php?funcao_js=parent.js_mostraveiculo1'+
                        '|ve01_codigo|ve20_descr|ve01_placa', 'Pesquisa', true
                       );
  } else {

    if (document.form1.tf18_i_veiculo.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_veiculos', 'func_tfd_veiculostfd.php?pesquisa_chave='+
                          document.form1.tf18_i_veiculo.value+'&funcao_js=parent.js_mostraveiculo',
                          'Pesquisa', false
                         );
    } else {

      document.form1.ve01_veiccadtipo.value = '';
      document.form1.ve01_placa.value       = '';
    }
  }
}

function js_mostraveiculo(placa, erro, descr) {
	
  if (erro) {

    descr = placa;
    placa = '';
  }

  document.form1.ve01_veiccadtipo.value = descr; 
  document.form1.ve01_placa.value       = placa;

  if (erro == true) {
	   
	  document.form1.tf18_i_veiculo.focus(); 
	  document.form1.tf18_i_veiculo.value = '';
  } else {
    js_getHorariosData();
  }
}

function js_mostraveiculo1(chave1, chave2, chave3) {
	
  document.form1.tf18_i_veiculo.value   = chave1;
  document.form1.ve01_veiccadtipo.value = chave2;
  document.form1.ve01_placa.value       = chave3;
  db_iframe_veiculos.hide();
  js_getHorariosData();
}

function js_mandaDados() {

  if ($F('tf18_i_destino') == '') {

    alert('Informe o destino.');
    return false;
  }

  if ($F('tf18_i_veiculo') == '') {

    alert('Informe o veículo.');
    return false;
  }
 
  if ($F('tf18_d_datasaida') == '') {

    alert('Informe a data de saída.');
    return false;
  }

  if ($F('tf18_c_horasaida') == null || $F('tf18_c_horasaida') == '') {

    alert('Informe a hora de saída');
    return false;
  }

  iCodVeiculo = 'codveiculo='+$F('tf18_i_veiculo');  
  iCodDestino = '&coddestino='+$F('tf18_i_destino');
  sDatasaida  = '&datasaida='+$F('tf18_d_datasaida');
  iHora       = '&hora='+$F('tf18_c_horasaida');

  sArquivo    = (iMenu == 0) ? 'tfd2_listapassageirodaer002.php?' : 'tfd2_saidaveiculo001.php?';
  
  oJan        = window.open(sArquivo+iCodVeiculo+sDatasaida+iHora+iCodDestino,'',
                            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                            ',scrollbars=1,location=0 '
                           );
  oJan.moveTo(0, 0);
}

function js_verificaData() {

  if (js_validaDbData($('tf18_d_datasaida'))) {
    js_getHorariosData();
  }
}

function js_getHorariosData() {

  if ($F('tf18_i_veiculo') == '') {
    return false;
  }

  if ($F('tf18_i_destino') == '') {
    return false;
  }

  if ($F('tf18_d_datasaida') == '') {
    return false;
  }

  var oParam      = new Object();
  oParam.exec     = 'getHorariosDataSaida';
  oParam.iVeiculo = $F('tf18_i_veiculo');
  oParam.iDestino = $F('tf18_i_destino');
  oParam.dData    = $F('tf18_d_datasaida');
  js_ajax(oParam, 'js_retornogetHorariosData');
}

function js_retornogetHorariosData(oRetorno) {
  
  iTam = $('tf18_c_horasaida').options.length;
  for (iCont = 0; iCont < iTam; iCont++) { // for para remover todos os options
    $('tf18_c_horasaida').options[0] = null;
  }

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    for (var iCont = 0; iCont < oRetorno.aHorarios.length; iCont++) {

      $('tf18_c_horasaida').options[iCont] = new Option(oRetorno.aHorarios[iCont].urlDecode(), 
                                                        oRetorno.aHorarios[iCont].urlDecode()
                                                       );

    }
  } else {
    alert('Nao foi possível encontrar horários de saída para a data indicada.');
  }
}

$('tf18_i_destino').className   = 'field-size2';
$('tf03_c_descr').className     = 'field-size7';
$('tf18_i_veiculo').className   = 'field-size2';
$('ve01_veiccadtipo').className = 'field-size7';
$('ve01_placa').className       = 'field-size2';
$('tf18_d_datasaida').className = 'field-size2';
$('tf18_c_horasaida').className = 'field-size2';
</script>
</body>
</html>