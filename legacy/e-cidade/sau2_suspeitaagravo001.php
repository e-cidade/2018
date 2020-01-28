<?php

/* 
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 * 
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 * 
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 * 
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

define("MSG_SUSPEITA_AGRAVO", "saude.ambulatorial.sau2_suspeitaagravo.");

$dtInicio_dia = '';
$dtInicio_mes = '';
$dtInicio_ano = '';

$dtFim_dia = '';
$dtFim_mes = '';
$dtFim_ano = '';
?>

<html>
  
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
    <script language='JavaScript' type='text/javascript' src='scripts/strings.js'></script>
    <script language='JavaScript' type='text/javascript' src='scripts/prototype.js'></script>
    <script language='JavaScript' type='text/javascript' src='scripts/widgets/DBLancador.widget.js'></script>
    
  </head>
  <body class="body-default">

    <div class="container">
      <form>

        <fieldset>
          <legend>Relatório de Agravos</legend>
          <table class="form-container">
            <tr>
              <td nowrap='nowrap'>Data da Triagem:</td>
              <td nowrap='nowrap'>
              <?php 
                db_inputdata("dtInicio", $dtInicio_dia, $dtInicio_mes, $dtInicio_ano, true, 'text', 1, "onChange='js_validata();'", '', '', '', '', '', 'js_validata()');
              ?>
              </td>
              <td nowrap='nowrap'> até </td>
              <td nowrap='nowrap'>
              <?php 
                db_inputdata("dtFim", $dtFim_dia, $dtFim_mes, $dtFim_ano, true, 'text', 1, "onChange='js_validata();'", '', '', '', '', '', 'js_validata()');
              ?>
              </td>
            </tr>
            
            <tr>
              <td nowrap='nowrap'>Gestante:</td>
              <td nowrap='nowrap' colspan="3">
                <select id='gestante'>
                  <option value='1' >Ambos</option>
                  <option value='2' >Sim</option>
                  <option value='3' >Não</option>
                </select>
              </td>
            </tr>
            
            <tr>
              <td nowrap='nowrap'>Quebra página por bairro:</td>
              <td nowrap='nowrap' colspan="3">
                <select id='quebraPaginaBairro'>
                  <option value='N' >Não</option>
                  <option value='S' >Sim</option>
                </select>
              </td>
            </tr>
          </table>
        
          <br />
          <div id='ctnLancadorTipoAgravo' ></div>
          <br />
          <div id='ctnLancadorBairro' ></div>
          
        </fieldset>  
        <input type="button" name="imprimir" id='imprimir' value="Imprimir" />
        
      </form>
    </div>
  </body>
  
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script type="text/javascript">
  
  const MSG_SUSPEITA_AGRAVO = "saude.ambulatorial.sau2_suspeitaagravo.";
  
  function js_validata() {
    
    if ($F('dtFim') == '') {
      return;
    }
    
    if ($F('dtFim') != '' && $F('dtInicio') == '') {
      return;
    }
    
    var oDtInicio = new Date($F('dtInicio_ano'), $F('dtInicio_mes'), $F('dtInicio_dia'));
    var oDtFim    = new Date($F('dtFim_ano'), $F('dtFim_mes'), $F('dtFim_dia'));

    if (oDtInicio > oDtFim) {
      
      alert(_M( MSG_SUSPEITA_AGRAVO + "periodo_inical_maior_que_final"));
      $('dtFim').value     = '';
      $('dtFim_ano').value = '';
      $('dtFim_mes').value = '';
      $('dtFim_dia').value = '';
    }
    
    sFiltrosAutoComplete = 'dtInicio=' + $F('dtInicio') + '&dtFim=' + $F('dtFim');
    oLancadorAgravo.setParametrosPesquisa('func_sauagravaotriagem.php', aCamposAgravo, sFiltrosAutoComplete);
    oLancadorBairro.setParametrosPesquisa('func_bairrotriagem.php', aCamposBairro, sFiltrosAutoComplete);
  }

  var sFiltrosAutoComplete = 'dtInicio=' + $F('dtInicio') + '&dtFim=' + $F('dtFim');
  var oLancadorAgravo      = new DBLancador('agravos');
  oLancadorAgravo.setNomeInstancia('oLancadorAgravo');  
  oLancadorAgravo.setLabelAncora('Agravo:');   
  oLancadorAgravo.setTextoFieldset("Tipo de Agravo");
  oLancadorAgravo.setGridHeight(120);
  var aCamposAgravo = ['sd70_i_codigo','sd70_c_nome'];
  oLancadorAgravo.setParametrosPesquisa('func_sauagravaotriagem.php', aCamposAgravo, sFiltrosAutoComplete);
  oLancadorAgravo.show($('ctnLancadorTipoAgravo'));
  
  var oLancadorBairro = new DBLancador('bairros');
  oLancadorBairro.setNomeInstancia('oLancadorBairro');  
  oLancadorBairro.setLabelAncora('Bairro:');   
  oLancadorBairro.setTextoFieldset("Selecione os Bairros");
  oLancadorBairro.setGridHeight(120);
  var aCamposBairro = ['j13_codi','j13_descr'];
  oLancadorBairro.setParametrosPesquisa('func_bairrotriagem.php', aCamposBairro, sFiltrosAutoComplete);
  oLancadorBairro.show($('ctnLancadorBairro'));
  
  $('imprimir').observe('click', function () {
    
    if ($F('dtInicio') == '' || $F('dtFim') == '') {
      
      alert(_M(MSG_SUSPEITA_AGRAVO + "periodo_deve_ser_informado"));
      return;
    }
    
    var aRegistrosAgravo = [];
    var aRegistrosBairro = [];
    
    oLancadorAgravo.getRegistros().each( function (oAgravo) {
      aRegistrosAgravo.push(oAgravo.sCodigo);
    });
    
    oLancadorBairro.getRegistros().each( function (oBairro) {
      aRegistrosBairro.push(oBairro.sCodigo);
    });    
    
    var sUrl  = "sau2_suspeitaagravo002.php";
        sUrl += "?dtInicial="+$F('dtInicio');
        sUrl += "&dtFinal="+$F('dtFim');
        sUrl += "&iFiltroGestante="+$F('gestante');
        sUrl += "&aAgravos="+aRegistrosAgravo;
        sUrl += "&aBairros="+aRegistrosBairro;
        sUrl += "&sQuebraPaginaBairro="+$F('quebraPaginaBairro');
      
    jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  });
  
</script>