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
require_once(modification("libs/db_app.utils.php"));
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
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHighlight.plugin.js"></script>
  </head>
<body class="body-default">

  <fieldset>
    <legend>Exemplares</legend>
    <div id='ctnExemplar'></div>
  </fieldset>
</body>

<script type="text/javascript">

var oGet = js_urlToObject();

var oGridExemplar      = new DBGrid('Exemplares');
var aHeaders   = [ 'Nº', 'Título', 'Cód. Barras', 'Data Aquisição', 'Situação', 'Aquisição', 'Empréstimo'];
var aCellWidth = [ '5%', '30%', '15%',  '15%', '10%', '15%', '10%' ];
var aCellAlign = [ 'center', 'left', 'center', 'center', 'center', 'center', 'center' ];

oGridExemplar.nameInstance = 'oGridExemplar';
oGridExemplar.setCellWidth(aCellWidth);
oGridExemplar.setCellAlign(aCellAlign);
oGridExemplar.setHeader(aHeaders);
oGridExemplar.setHeight(130);
oGridExemplar.show($('ctnExemplar'));

(function(){

  oGridExemplar.clearAll(true);
  var oAjax = new AjaxRequest('bib4_acervo.RPC.php', {exec: 'exemplaresByAcervo', iAcervo : oGet.iAcervo},
    function(oRetorno, lErro) {

      if ( lErro ) {

        alert(oRetorno.sMessage);
        return;
      }

      for (var oExemplar of oRetorno.aExemplares) {

        var sStatus = oExemplar.status;
        if (oExemplar.status == 'DISPONÍVEL') {

          var sClick  = "CurrentWindow.corpo.location.href='bib1_emprestimo001.php?bi23_codigo="+oExemplar.bi23_codigo;
              sClick += "&bi06_titulo="+oExemplar.bi06_titulo+"&assunto'";
          var oLink = new Element('a', {href:'#', 'title':'Realizar Empréstimo', 'onclick':sClick}).update(oExemplar.status);
          sStatus = oLink.outerHTML;
        }

        var aLinha = [];
        aLinha.push(oExemplar.bi23_exemplar);
        aLinha.push(oExemplar.bi06_titulo);
        aLinha.push(oExemplar.bi23_codbarras);
        aLinha.push(oExemplar.data_aquisicao);
        aLinha.push(oExemplar.situacao);
        aLinha.push(oExemplar.aquisicao);
        aLinha.push(sStatus);

        oGridExemplar.addRow(aLinha);
      }

      oGridExemplar.renderRows();

      oParametros = {iWidth:'200', oPosition : {sVertical : 'T', sHorizontal : 'R'}};
      oRetorno.aExemplares.each( function ( oExemplar, i) {

        var sHint  = '<div><b>Localização:</b> ' + oExemplar.estante + '</div>';
            sHint += '<b>Ordenação:</b> ' + oExemplar.ordem;


        oGridExemplar.setHint(i, 1, sHint,  oParametros);
      });
  });

  oAjax.setMessage('Buscando exemplares...');
  oAjax.execute();
})();

</script>
</html>


<!--                     <a href="#"
                       onclick="location.href='bib1_emprestimo001.php?bi23_codigo=<?=$bi23_codigo?>&bi06_titulo=<?=$bi06_titulo?>&assunto'"
                       title="Realizar Empréstimo">Disponível</a>
 -->