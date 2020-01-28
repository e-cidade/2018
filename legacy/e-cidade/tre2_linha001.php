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
  <script language="JavaScript" type="text/javascript" src="scripts/classes/transporteescolar/formulario/DBViewLinha.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/transporteescolar/formulario/LookUpLinha.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/transporteescolar/formulario/ComboItinerario.js"></script>
</head>
<body class='body-default'>
  <div class='container'>

    <form method="post" action="">
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td><label for="tre06_sequencial"><a id='ancoraLinha' href="#">Linha:</a> </label></td>
            <td>
              <input type="text" name="tre06_sequencial" id="tre06_sequencial" class="field-size2" />
              <input type="text" name="tre06_nome" id="tre06_nome" class="field-size8 readonly" disabled="disabled" />
            </td>
          </tr>
          <tr>
            <td><label for="cboItinerario">Itinerário:</label></td>
            <td id='ctnTipoItinenario'> </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btnImprimir" value="Imprimir" disabled="disabled" />
    </form>
  </div>
<?php
  db_menu();
?>
</body>
<script type="text/javascript">

var oLinha = new DBViewLinha.LookUpLinha();
oLinha.setCallBackClick(liberaBotao);
oLinha.setCallBackChange(liberaBotao);

oLinha.criarAncora($('ancoraLinha'), $('tre06_sequencial'), $('tre06_nome'));

function liberaBotao() {

  if ($F('tre06_sequencial') != '') {

    $('btnImprimir').removeAttribute('disabled');
    return;
  }
  $('btnImprimir').setAttribute('disabled', 'disabled');
}


var oItinerario = DBViewLinha.ComboItinerario($('ctnTipoItinenario'));

$('btnImprimir').addEventListener('click', function() {

  if ( $F('tre06_sequencial') == '') {

    alert( _M('educacao.transporteescolar.tre2_linha002.' + "informe_linha" ) );
    return;
  }

  var sParamentros  = "iLinha="+$F('tre06_sequencial');
      sParamentros += "&sLinha="+$F('tre06_nome');
      sParamentros += "&iItinerario="+$F('cboItinerario');

  var sUrl  = "tre2_linha002.php?q=" + btoa(sParamentros);

  jan = window.open(sUrl,'','scrollbars=1,location=0');
  jan.moveTo(0,0);
});

</script>
</html>

