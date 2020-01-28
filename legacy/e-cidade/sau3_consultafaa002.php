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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
 <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta http-equiv="Expires" CONTENT="0">
   <link href="estilos.css" rel="stylesheet" type="text/css">
   <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
   <style type="text/css">
   .valores {
      background-color: #FFFFFF;
   }

   .tabela-cabecalho {
      width: 100%;
      border-spacing: 1;
   }
   </style>
</head>
<body >

  <div class="subcontainer" style="width: 99%;">
    <fieldset>
      <legend>Dados da FAA - <?=$oGet->iProntuario?></legend>

      <!-- Container dados do paciente  -->
      <div style="float: left;display: inline;width: 50%;">
        <fieldset class="separator">
          <legend>Dados do Paciente</legend>
          <table class="tabela-cabecalho">

            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%">
                Paciente:
              </td>
              <td nowrap="nowrap" colspan="3" class="valores"  style="width: 85%">
                <span id='paciente'></span>
              </td>
            </tr>

            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%">
                Mãe:
              </td>
              <td nowrap="nowrap"  colspan="3" class="valores"  style="width: 85%">
                <span id='maePaciente'></span>
              </td>
            </tr>

            <tr>
              <td nowrap="nowrap"class="bold text-left"  style="width: 15%">
                Estado Civil:
              </td>
              <td nowrap="nowrap" class="valores" style="width: 10%">
                <span id='estadoCivil'></span>
              </td>
              <td nowrap="nowrap" class="bold text-left" style="width: 10%">
                Sexo:
              </td>
              <td nowrap="nowrap" class="valores" style="width: 65%">
                <span id='sexo'></span>
              </td>
            </tr>

            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%">
                Data de Nascimento:
              </td>
              <td nowrap="nowrap" class="valores"  style="width: 10%">
                <span id='dataNascimento'></span>
              </td>

              <td nowrap="nowrap" class="bold text-left" style="width: 10%">
                Idade:
              </td>
              <td nowrap="nowrap" class="valores"  colspan="3"  style="width: 65%">
              <span id='idadePaciente'></span>
              </td>
            </tr>

          </table>
        </fieldset>
      </div>

      <!-- Container dos dados do endereço  -->
      <div style="float: left;width: 50%">
        <fieldset class="separator">
          <legend>Endereço</legend>
          <table class="tabela-cabecalho">
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">Endereço:</td>
              <td nowrap="nowrap" colspan="3" class="valores">
                <span id="enderecoPaciente"></span>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">Complemento:</td>
              <td nowrap="nowrap" colspan="3" class="valores">
                <span id="complementoPaciente"></span>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">Bairro:</td>
              <td nowrap="nowrap" class="valores">
                <span id="bairroPaciente"></span>
              </td>

              <td nowrap="nowrap" class="bold text-left" style="width: 5%">CEP:</td>
              <td nowrap="nowrap" class="valores">
                <span id="cepPaciente"></span>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">Município:</td>
              <td nowrap="nowrap" class="valores">
                <span id="municipioPaciente"></span>
              </td>
              <td nowrap="nowrap" class="bold text-left" style="width: 5%">UF:</td>
              <td nowrap="nowrap" class="valores">
                <span id="ufPaciente"></span>
              </td>
            </tr>
          </table>
        </fieldset>
      </div>
    </fieldset>

  </div>
  <!-- Container das abas  -->
  <div class="subcontainer" style="width: 99%;">
    <fieldset>
      <legend>Opções</legend>
      <?php
        $sProntuario  = "iProntuario={$oGet->iProntuario}";
        $oTabDetalhes = new verticalTab( "detalhesemp", 350 );
        $oTabDetalhes->add("abaCartaoSus",     "Cartão SUS",            "sau3_cartaosuscgs.iframe.php?z01_i_cgsund={$oGet->iCgs}");
        $oTabDetalhes->add("abaEmitirFAA",     "Emissão de Documentos", "sau3_emissaodocumentos001.php?{$sProntuario}");
        $oTabDetalhes->add("abaExames",        "Exames",                "sau3_requisicaoexames001.php?{$sProntuario}");
        $oTabDetalhes->add("abaMovimentacao",  "Movimentação",          "func_movimentacaofaa.php?{$sProntuario}");
        $oTabDetalhes->add("abaProcedimentos", "Procedimentos",         "sau3_procedimentosfaa.php?{$sProntuario}");
        $oTabDetalhes->add("abaTriagem",       "Triagem",               "sau3_triagensfaa.php?{$sProntuario}&iCgs={$oGet->iCgs}");
        $oTabDetalhes->show();
      ?>
    </fieldset>
  </div>

</body>

<script type="text/javascript">

var oGet         = js_urlToObject();
var oParametro   = {'sExecucao': 'buscarDadosPaciente','iProntuario': oGet.iProntuario};
var oAjaxRequest = new AjaxRequest('sau4_fichaatendimento.RPC.php', oParametro, callBackRetorno);
    oAjaxRequest.setMessage('Buscando departamentos...');
    oAjaxRequest.execute();

function callBackRetorno(oRetorno, lErro) {

  if (lErro) {

    alert ( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  $('paciente').innerHTML       = oRetorno.oDadosPaciente.sNome.urlDecode();
  $('maePaciente').innerHTML    = oRetorno.oDadosPaciente.sNomeMae.urlDecode();
  $('estadoCivil').innerHTML    = oRetorno.oDadosPaciente.sEstadoCivil.urlDecode();
  $('sexo').innerHTML           = oRetorno.oDadosPaciente.sSexo.urlDecode();
  $('dataNascimento').innerHTML = oRetorno.oDadosPaciente.dtNascimento;
  $('idadePaciente').innerHTML  = oRetorno.oDadosPaciente.sIdadeCompleta.urlDecode();

  var enderecoPaciente = oRetorno.oDadosPaciente.sEndereco.urlDecode();
  if( !empty(oRetorno.oDadosPaciente.iNumero) ) {
    enderecoPaciente += ", " + oRetorno.oDadosPaciente.iNumero;
  }

  $('enderecoPaciente').innerHTML    = enderecoPaciente;
  $('complementoPaciente').innerHTML = oRetorno.oDadosPaciente.sComplemento.urlDecode();
  $('bairroPaciente').innerHTML      = oRetorno.oDadosPaciente.sBairro.urlDecode();
  $('cepPaciente').innerHTML         = oRetorno.oDadosPaciente.sCep;
  $('municipioPaciente').innerHTML   = oRetorno.oDadosPaciente.sMunicipio.urlDecode();
  $('ufPaciente').innerHTML          = oRetorno.oDadosPaciente.sUF.urlDecode();
}

function js_emitirFaa() {

  sChave = '?chave_sd29_i_prontuario='+oGet.iProntuario;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
  WindowObjectReference = window.open('sau2_emitirfaa002.php'+sChave,"CNN_WindowName", strWindowFeatures);
  return false;
}

function js_retornoEmissaofaa( oAjax ) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
  } else {
    js_emitiefaaPDF (oRetorno);
  }
}

</script>
</html>
