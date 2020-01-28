<?
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
$oRotulo->label('s163_i_anocomp');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br><br>
<form name="form1" action="">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <fieldset style='width: 75%;'> <legend><b>Controle de Cotas</b></legend>

        <center>
          <table border="0">
            <tr>
              <td nowrap title="<?=@$Ttf12_i_codigo?>">
                <b>Mês:</b>
              </td>
              <td>
                <?
                $aX = array('1' => 'JANEIRO', '2' => 'FEVEREIRO', '3' => 'MARÇO', '4' => 'ABRIL',
                            '5' => 'MAIO', '6' => 'JUNHO', '7' => 'JULHO', '8' => 'AGOSTO',
                            '9' => 'SETEMBRO', '10' => 'OUTUBRO', '11' => 'NOVEMBRO', '12' => 'DEZEMBRO'
                           );
                db_select('iMes', $aX, true, 1, '');
                ?>
              </td>
              <td>
                <b>Ano:</b>
              </td>
              <td>
                <?
                $iAno = date('Y', db_getsession('DB_datausu'));
                db_input('iAno', 4, $Is163_i_anocomp, true, 'text', 1, '', '', '', '', 4);
                ?>
              </td>
            </tr>
          </table>
          <br>
          <input type="button" id="imprimir" value="Gerar Relatório" onclick="js_mandaDados();">
        </center>

      </fieldset>
    </center>
	</td>
  </tr>
</table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>

function js_validaDados() {

  if ($F('iAno') == '') {

    alert('Informe o ano.');
    return false;

  }

  var iAno = parseInt($F('iAno'), 10);
  if (iAno < 1000 || iAno > 3000) {

    alert('Informe um ano válido.');
    return false;

  }

  return true;

}


function js_mandaDados() {

  if (!js_validaDados()) {
    return false;
  }

  sChave = 'iMes='+$F('iMes')+'&iAno='+parseInt($F('iAno'), 10)+
           '&sMes='+$('iMes').options[$('iMes').selectedIndex].text;
  oJan   = window.open('sau2_controlecotas002.php?'+sChave, '', 'width='+(screen.availWidth - 5)+',height='+
                       (screen.availHeight - 40)+',scrollbars=1,location=0 '
                      );
  oJan.moveTo(0, 0);

}

</script>

</body>
</html>