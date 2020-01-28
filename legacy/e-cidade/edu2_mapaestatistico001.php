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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$lDbOpcao    = 1;
$dDataIniDia = '01';
$dDataIniMes = date('m');
$dDataIniAno = date('Y');
$dDataFimDia = date('d');
$dDataFimMes = date('m');
$dDataFimAno = date('Y');

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js,
                  prototype.js");

    db_app::load("estilos.css,
                  grid.style.css"
                );
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmDiarioClasse' method="post">
    <center>
      <div style='display:table;' id='ctnForm'>
        <fieldset>
        <legend style="font-weight: bold">Mapa Estatístico</legend>
          <table class="tabela" border='0' width="100%">
            <tr>
              <td>
                <b>Período:</b>
              </td>
              <td>
                <?db_inputdata('dtInicio', "$dDataIniDia", "$dDataIniMes", "$dDataIniAno", true, 'text', $lDbOpcao, "")?>
                <b>até</b>
                <?db_inputdata('dtFim', "$dDataFimDia", "$dDataFimMes", "$dDataFimAno", true, 'text', $lDbOpcao,"")?>
              </td>
            </tr>
          </table>
        </fieldset>
      </div>
      <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
    </center>
  </form>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>

<script type="text/javascript">


  $('btnImprimir').observe('click', function () {

    if ( validaDatas() ) {

      var dtInicio = $F('dtInicio');
      var dtFim    = $F('dtFim');

      var sUrlRelatorio = 'edu2_mapaestatistico002.php';
      sUrlRelatorio    += '?dtInicio=' + dtInicio;
      sUrlRelatorio    += '&dtFim=' + dtFim;

      oWindow = window.open(sUrlRelatorio, '',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      oWindow.moveTo(0,0);
    }
  })

  function validaDatas() {

    var oDtInicio = new Date($('dtInicio_ano').value, $('dtInicio_mes').value, $('dtInicio_dia').value);
    var oDtFinal  = new Date($('dtFim_ano').value, $('dtFim_mes').value, $('dtFim_dia').value);

    if ($('dtInicio_dia').value == "" || $('dtInicio_mes').value == "" || $('dtInicio_ano').value == ""
        ||$('dtFim_dia').value == "" || $('dtFim_mes').value == "" || $('dtFim_ano').value == "") {

      alert("Preencha as datas corretamente!");
      return false;
    }

    if ( oDtInicio.getTime() > oDtFinal.getTime() ) {

      alert("Início do período não pode ser maior que o final do período!");
      return false;
    }

    if ( $('dtInicio_ano').value != $('dtFim_ano').value ) {

      alert ("O período deve estar dentro do mesmo ano.");
      return false;
    }

    return true;
  }

</script>
</html>