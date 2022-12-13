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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));

$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo = new rotulocampo;
$anousu=db_getsession("DB_anousu");

$fonte="con2_lrfnominal002_2010.php";
if ($anousu<=2008){
  $fonte="con2_lrfnominal002.php";
}

if ($anousu >= 2017) {
  $fonte="con2_emissaoAnexoV002.php";
}


$oRelatorio = new relatorioContabil($oGet->codrel);
$sLabelMsg = "Anexo V - Demonstrativo do  Resultado Nominal";
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>
    function js_emite() {

      obj     = document.form1;
      var periodo = obj.o116_periodo.value;
      fonte="<? $fonte ?>";
      jan = window.open('<?=$fonte?>?periodo='+periodo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }
  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table  align="center">
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3  class='table_header'>
      <?=$sLabelMsg?>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <form name="form1" method="post" action="con2_lrfnominal002.php" >
          <table>

            <tr>
              <td colspan=2 nowrap><b>Período :</b>
                <?
                $aPeriodos = $oRelatorio->getPeriodos();
                $aListaPeriodos = array();
                $aListaPeriodos[0] = "Selecione";
                foreach ($aPeriodos as $oPeriodo) {
                  $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                }
                db_select("o116_periodo", $aListaPeriodos, true, 1);
                ?>
              </td>
            </tr>
          </table>
      </fieldset>
    </td>
  </tr>
  <tr><td colspab="2">&nbsp;</td></tr>
  <tr>
    <td align="center" colspan="2">
      <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
    </td>
  </tr>


  </form>
</table>



</body>
</html>