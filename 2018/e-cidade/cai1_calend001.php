<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");

if(isset($HTTP_POST_VARS["excluir"])) {
  $data = mktime(0,0,0,$HTTP_POST_VARS["data_mes"],$HTTP_POST_VARS["data_dia"],$HTTP_POST_VARS["data_ano"]);
  db_query("delete from calend where k13_data = '".date("Y-m-d",$data)."'") or die("Erro(9) excluindo calend");
  unset($HTTP_POST_VARS);
}

if(isset($HTTP_POST_VARS["sabdom"])) {
  $anoexe = $HTTP_POST_VARS["exercicio"];
  if(!preg_match("/[12][0-9][0-9][0-9]/",$anoexe) || preg_match("/[^0-9]/",$anoexe))
    db_erro("Exercício inválido");
  for($i = 1;$i <= 12;$i++) {
    $totdia = date("t",mktime(0,0,0,$i,1,$anoexe));
    for($j = 1;$j <= $totdia;$j++) {
	  $data = mktime(0,0,0,$i,$j,$anoexe);
	  if(date("w",$data) == "0" || date("w",$data) == "6") {
	    $result = db_query("select k13_data from calend where k13_data = '".date("Y-m-d",$data)."'");
		if(pg_numrows($result) == 0)
		  db_query("insert into calend values('".date("Y-m-d",$data)."')") or die("Erro(67)($i)($j) inserindo em calend");
	  }
	}
  }
  unset($HTTP_POST_VARS);
  $MSG = "Exercício $anoexe inserido com sucesso.";
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>Manutenção de Calendário</legend>
        <table>
          <tr>
            <td><strong>Exercício:</strong></td>
            <td><input name="exercicio" type="text" id="exercicio" value="<?php echo !empty($HTTP_POST_VARS["exercicio"]) ? $HTTP_POST_VARS["exercicio"] : ''; ?>" size="4" maxlength="4"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="sabdom" type="submit" onClick="return js_validasabdom()" id="sabdom" value="Incluir Sábados e Domingos"></td>
          </tr>
          <tr>
            <td><strong>Data:</strong></td>
            <td>
      			  <?php
      			  include modification("dbforms/db_funcoes.php");
      			  db_inputdata("data",@$HTTP_POST_VARS["data_dia"],@$HTTP_POST_VARS["data_mes"],@$HTTP_POST_VARS["data_ano"],true,"text",1);
      			  ?>
    			  </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
              <input name="feriado" onClick="return js_feriado()" type="submit" id="feriado" value="Incluir Feriado">
              <input name="emite" onClick="return js_geracalendario()" type="button" id="emite" value="Emite Calendário">
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
  </div>
  <?php db_menu(); ?>
  <script type="text/javascript">

    function js_validasabdom() {
      var F = document.form1.exercicio;
      var str = F.value;
      var expr = /[12][0-9][0-9][0-9]/;
      var expr1 = /[^0-9]/;
      if(str == "") {
        alert("Informe o Exercício");
      F.focus();
      return false;
      }
      if(str.match(expr) == null || str.match(expr1)) {
        alert("Exercicio Inválido");
      F.select();
      return false;
      }
    }
    function js_feriado() {
      var F = document.form1;
      if(F.data_dia.value == "" || F.data_mes.value == "" || F.data_ano.value == "") {
        alert("Informe a data");
      if(F.data_dia.value == "")
        F.data_dia.focus();
        else
          F.data_dia.select();
      return false;
      }
    }

    function js_geracalendario(){

      var oEmissao = new EmissaoRelatorio("cai2_calend002.php", {
        anousu : document.form1.exercicio.value
      });

      oEmissao.open();
    }

    </script>
</body>
</html>
<?php
if(isset($HTTP_POST_VARS["feriado"])) {
  $data = mktime(0,0,0,$HTTP_POST_VARS["data_mes"],$HTTP_POST_VARS["data_dia"],$HTTP_POST_VARS["data_ano"]);
  $result = db_query("select k13_data from calend where k13_data = '".date("Y-m-d",$data)."'");
  if(pg_numrows($result) > 0) {
  ?>
    <script>
      if(confirm("Esta data já esta cadastrada, deseja exclui-la?")) {
        document.getElementById("feriado").name = "excluir";
		document.getElementById("feriado").click();
      } else
	    location.href = location.href;
    </script>
  <?
  } else {
    db_query("insert into calend values('".date("Y-m-d",$data)."')");
	db_redireciona();
  }
}
if(isset($MSG))
  db_msgbox($MSG);
?>