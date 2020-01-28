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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$dtFinal_dia = date("d", db_getsession("DB_datausu"));
$dtFinal_mes = date("m", db_getsession("DB_datausu"));
$dtFinal_ano = date("Y", db_getsession("DB_datausu"));
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
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCCC">
    <form class="container" name="form1" method="post" >
      <fieldset>
      <legend>Modelo XII - Bens Patrimoniais - Demonstrativo da Movimentação</legend>
      <table class="form-container">
        <tr>
          <td>
            Data Inicial:
          </td>
          <td>
            <?
              db_inputdata('dtDataInicial','','','',true,'text',1);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            Data Final:
          </td>
          <td>
            <?
              db_inputdata('dtDataFinal',$dtFinal_dia, $dtFinal_mes, $dtFinal_ano,true,'text',1);
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
    <input type='button' name="btnImprimir" value="Imprimir" onclick="js_imprimir();">
    </form>

<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

<script>

function js_imprimir() {

  var sDataInicial = $F('dtDataInicial');
  var sDataFinal   = $F('dtDataFinal');

  //verificação da informação das datas do período
  if (sDataInicial == '' || sDataFinal == '') {

    alert (_M("patrimonial.patrimonio.pat2_relatoriolegalanexodoze001.informe_periodo"));
    return false;
  }

  iAnoInicial = sDataInicial.substring(6,10);
  iAnoFinal   = sDataFinal.substring(6,10);

  //verifica se o ano inicial e final do período coincidem
  if (iAnoInicial != iAnoFinal) {

    alert (_M("patrimonial.patrimonio.pat2_relatoriolegalanexodoze001.anos_diferentes"));
    return false;
  }

  var dtDataInicial = js_formatar(sDataInicial,'d');
  var dtDataFinal   = js_formatar(sDataFinal,'d');

  //verifica se data inicial não é maior que a data final do período
  if (dtDataInicial > dtDataFinal) {

    alert (_M("patrimonial.patrimonio.pat2_relatoriolegalanexodoze001.data_inicial_menor_data_final"));
    return false;
  }

  var sQuery  = "pat2_relatoriolegalanexodoze002.php?dtDataInicial="+dtDataInicial;
  sQuery     += "&dtDataFinal="+dtDataFinal;

  jan = window.open(sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}
</script>
</body>
</html>

<script>

$("dtDataInicial").addClassName("field-size2");
$("dtDataFinal").addClassName("field-size2");

</script>