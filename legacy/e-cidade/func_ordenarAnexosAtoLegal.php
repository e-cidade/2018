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


require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_edu_anexoatolegal_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

function getOptionsArquivos($iCodigo) {

  $oDaoAnexoAtoLegal = db_utils::getdao('edu_anexoatolegal');

  $sOrderBy   = " ed292_ordem ASC ";
  $sWhere     = " ed292_atolegal = ".$iCodigo;
  $sSqlAnexos = $oDaoAnexoAtoLegal->sql_query("", "*", $sOrderBy, $sWhere);
  $rsAnexos   = $oDaoAnexoAtoLegal->sql_record($sSqlAnexos);

  $sHtml      = "";

  if ($oDaoAnexoAtoLegal->numrows > 0) {

    $iTamanho     = $oDaoAnexoAtoLegal->numrows;

    for ($iCont = 0; $iCont < $iTamanho; $iCont++) {

      $oDados = db_utils::fieldsmemory($rsAnexos, $iCont);
      $sHtml .= "<option value='".$oDados->ed292_sequencial."'>".$oDados->ed292_nomearquivo."</option>";

    }

  } else {
    $sHtml .= "<option value=''>Nenhum arquivo encontrado!</option>";
  }

  return $sHtml;

}

if (isset($ordenar)) {

  $iTamanho = sizeof($ordenar);

  for ($iCont = 0; $iCont < $iTamanho; $iCont++) {

    $oDaoAnexoAtoLegal                   = db_utils::getdao('edu_anexoatolegal');
    $oDaoAnexoAtoLegal->ed292_ordem      = $iCont + 1;
    $oDaoAnexoAtoLegal->ed292_sequencial = $ordenar[$iCont];
    $oDaoAnexoAtoLegal->alterar($ordenar[$iCont]);

  }
?>
  <script language="JavaScript">

    parent.js_buscaArquivos();
    parent.db_iframe_ordenar.hide();

  </script>
<?

}

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
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
    <form name="form1" method="post" action="" >
      <table border="0" width="500" cellspacing="2" cellpading="0">
        <tr>
          <td align="center">
            <br /><br />
            <fieldset><legend><b>Ordenar Arquivos</b></legend>
              <table>
                <tr>
                  <td>
                    <select multiple="true" name="ordenar[]" id="ordenar" size="10" style="font-size:9px;width:300px"
                            onClick="js_selecionaUm('ordenar');" >

                      <?
                        echo getOptionsArquivos($iCodAtoLegal);
                      ?>

                    </select>
                  </td>
                  <td>
                    <img style="cursor:hand" onClick="js_sobePosicao();" src="skins/img.php?file=Controles/seta_up.png" />
                    <br/><br/>
                    <img style="cursor:hand" onClick="js_baixaPosicao();" src="skins/img.php?file=Controles/seta_down.png" />
                  </td>
                </tr>
                <tr>
                  <td align="center">
                    <input type="submit" name="btnOrdenar" id="btnOrdenar" value="Alterar" onclick="js_ordenar();" />
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </form>
  </center>
  </body>
</html>

<script language="JavaScript">

/* Sobe posição do item no select multiplo */
function js_sobePosicao() {

  var F = $("ordenar");

  if(F.selectedIndex != -1 && F.selectedIndex > 0 ) {

    var SI                 = F.selectedIndex - 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;

    F.options[SI]          = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }

}

/* Baixa posição do item no select multiplo */
function js_baixaPosicao() {

  var F = document.getElementById("ordenar");

  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1) ) {

    var SI                 = F.selectedIndex + 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;

    F.options[SI]          = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI - 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }

}

function js_selecionaUm(sValor) {

  var F = $(sValor);

  for(var i = 0;i < F.options.length;i++){

    if(F.selectedIndex == i){
      F.options[i].selected = true;
    }else{
      F.options[i].selected = false;
    }

  }

}

function js_ordenar() {

  var F = $("ordenar").options;

  for(var iCont = 0; iCont < F.length; iCont++) {

    F[iCont].selected = true;

  }

  return true;

}

</script>