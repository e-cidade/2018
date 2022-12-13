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
      <legend>Cadastro Geral da Saúde</legend>

      <!-- Container dados do cgs  -->
      <div style="float: left;display: inline;width: 50%;">
        <fieldset>
          <legend>Dados do CGS</legend>
          <table class="tabela-cabecalho">

            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%">
                Nome:
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
        <fieldset >
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

        $oTabDetalhes = new verticalTab("detalhesemp",300);
        $oTabDetalhes->add("abaCartaoSus", "Cartão SUS","sau3_cartaosuscgs.iframe.php?z01_i_cgsund={$oGet->iCgs}");
        $oTabDetalhes->add("abaLaboratorio", "Laboratório", "sau3_consultaexameslaboratorio.php?iCgs={$oGet->iCgs}");

        $oTabDetalhes->show();
      ?>
    </fieldset>
  </div>

</body>

<script type="text/javascript">

var oGet = js_urlToObject();

var oParametro    = {'sExecucao': 'buscarDadosCgs','iCgs': oGet.iCgs}
var oAjaxRequest  = new AjaxRequest('sau4_cgs.RPC.php', oParametro, callBackRetorno);
oAjaxRequest.setMessage('Buscando dados do cgs...');
oAjaxRequest.execute();

function callBackRetorno(oRetorno, lErro) {

  if (lErro) {
    alert ( oRetorno.sMessage.urlDecode() );
    return false;
  }

  $('paciente').innerHTML       = oRetorno.oCgs.sNome.urlDecode();
  $('maePaciente').innerHTML    = oRetorno.oCgs.sNomeMae.urlDecode();
  $('estadoCivil').innerHTML    = oRetorno.oCgs.sEstadoCivil.urlDecode();
  $('sexo').innerHTML           = oRetorno.oCgs.sSexo.urlDecode();
  $('dataNascimento').innerHTML = oRetorno.oCgs.dtNascimento;
  $('idadePaciente').innerHTML  = oRetorno.oCgs.sIdadeCompleta.urlDecode();

  var enderecoPaciente = oRetorno.oCgs.sEndereco.urlDecode();
  if( !empty(oRetorno.oCgs.iNumero) ) {
    enderecoPaciente += ", " + oRetorno.oCgs.iNumero;
  }

  $('enderecoPaciente').innerHTML    = enderecoPaciente;
  $('complementoPaciente').innerHTML = oRetorno.oCgs.sComplemento.urlDecode();
  $('bairroPaciente').innerHTML      = oRetorno.oCgs.sBairro.urlDecode();
  $('cepPaciente').innerHTML         = oRetorno.oCgs.sCep;
  $('municipioPaciente').innerHTML   = oRetorno.oCgs.sMunicipio.urlDecode();
  $('ufPaciente').innerHTML          = oRetorno.oCgs.sUF.urlDecode();
}

</script>
</html>
