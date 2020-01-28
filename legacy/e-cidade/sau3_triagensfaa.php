<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body class='body-default'>

  <div class="container">

    <fieldset style='width:1000px;'>
      <legend>Triagens </legend>
      <div class='field-size-max' id='ctnGridTriagens'></div>
    </fieldset>

  </div>

</body>

<script type="text/javascript">

var oGet = js_urlToObject();

var oGridTriagem          = new DBGrid( 'gridTriagem' );
oGridTriagem.nameInstance = 'oGridTriagem';
oGridTriagem.setCellAlign( [ 'left', 'center', 'center' ]     );
oGridTriagem.setHeader(    [ 'Profissional', 'Data', 'Hora' ] );
oGridTriagem.setCellWidth( [ '80%', '10', '10%']              );
oGridTriagem.setHeight(150);
oGridTriagem.show($('ctnGridTriagens'));

var sRPC = 'sau4_fichaatendimento.RPC.php';
if ( !empty(oGet.iProntuario) ) {


  var oAjaxRequest = new AjaxRequest(sRPC, {sExecucao: 'buscarTriagemProntuario', iProntuario : oGet.iProntuario }, js_retornoTriagem);
  oAjaxRequest.setMessage('Buscando triagens...');
  oAjaxRequest.execute();

}
function js_retornoTriagem (oRetorno, lErro) {

 if (lErro) {

    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }


  if ( oRetorno.aTriagem.length == 0 ) {
    oGridTriagem.setStatus("Nenhuma triagem realizada.");
  }

  oGridTriagem.clearAll(true);
  oRetorno.aTriagem.each( function (oTriagem) {

    var aLinha = [];
    aLinha.push(oTriagem.sProfissional.urlDecode());
    aLinha.push(oTriagem.sData);
    aLinha.push(oTriagem.sHora);

    oGridTriagem.addRow(aLinha);
  });

  oGridTriagem.renderRows();

  oRetorno.aTriagem.each( function (oTriagem, iLinha) {

    var sIdLinha = oGridTriagem.aRows[iLinha].sId;

    if ($(sIdLinha) ) {

      $(sIdLinha).onclick = function () {
        js_consultaTriagem(oTriagem.iCodigo);
      };
    }
  });

}

function js_consultaTriagem ( iTriagem ) {

  iTop  = (screen.availHeight - 800) / 2;
  iLeft = (screen.availWidth - 800) / 2;
  sUrl  = 'sau4_triagemconsulta001.php';
  sUrl += '?iProntuario=' + oGet.iProntuario + '&iCgs=' + oGet.iCgs + '&iTriagem='+iTriagem;

  js_OpenJanelaIframe( 'top.corpo', 'db_iframe_triagemavulsa', sUrl, 'Triagem', true, 20, iLeft, 750, 800 );
}

</script>
</html>