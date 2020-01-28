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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ensino_classe.php");
include("dbforms/db_funcoes.php");

$oDaoEnsino    = new cl_ensino();
$sCamposEnsino = " ed10_i_codigo, ed10_c_descr ";
$sOrdemCampos  = " ed10_ordem ";
$sSqlEnsino    = $oDaoEnsino->sql_query_file( null, $sCamposEnsino, $sOrdemCampos );
$rsEnsino      = db_query( $sSqlEnsino );

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
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <form name="form1" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <br>
          <center>
            <fieldset style="width:200px"><legend><b>Ordenação de Níveis de Ensino</b></legend>
              <table width="100%">
                <tbody>
                  <tr>
                    <td colspan="2" valign="top">
                      <table border="0">
                        <tr>
                          <td>
                            <table border="0">
                              <tr>
                                <td>
                                  <select name="nivel_ensino" id="nivel_ensino" size="15" multiple style="width:300px;">
                                    <?php
                                      if ( pg_num_rows( $rsEnsino ) > 0 ) {

                                        for( $i = 0; $i < pg_num_rows( $rsEnsino ); $i++ ) {

                                          $oEnsino = db_utils::fieldsMemory( $rsEnsino, $i );
                                          ?>
                                            <option value="<?=$oEnsino->ed10_i_codigo?>"><?=$oEnsino->ed10_c_descr?></option>
                                          <?php
                                        }
                                      } else {
                                        ?>
                                          <option value="">Nenhuma etapa vinculada ao regime de matrícula no ensino selecionado.</option>
                                        <?php
                                      }
                                    ?>
                                  </select>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td valign="top">
                            <br>
                            <img style="cursor:hand" onClick="js_sobe();return false" src="skins/img.php?file=Controles/seta_up.png" width="20" height="20" border="0">
                            <br><br>
                            <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" width="20" height="20" border="0">
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <center>
                              <input name="atualizar2" type="button" value="Atualizar" onclick="js_selecionar()"/>
                            </center>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </fieldset>
          </center>
        </td>
      </tr>
    </table>
  </form>

  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
  <script type="text/javascript">

    function js_sobe() {

      var sNivelEnsino = document.getElementById( "nivel_ensino" );

      if ( sNivelEnsino.selectedIndex != -1 && sNivelEnsino.selectedIndex > 0 ) {

        var iNivelEnsino  = sNivelEnsino.selectedIndex - 1;
        var auxText       = sNivelEnsino.options[iNivelEnsino].text;
        var auxValue      = sNivelEnsino.options[iNivelEnsino].value;

        sNivelEnsino.options[iNivelEnsino] = new Option(sNivelEnsino.options[iNivelEnsino + 1].text,sNivelEnsino.options[iNivelEnsino + 1].value);
        sNivelEnsino.options[iNivelEnsino + 1] = new Option(auxText,auxValue);
        sNivelEnsino.options[iNivelEnsino].selected = true;
      }
    }

    function js_desce() {

      var sNivelEnsino = document.getElementById( "nivel_ensino" );

      if (sNivelEnsino.selectedIndex != -1 && sNivelEnsino.selectedIndex < (sNivelEnsino.length - 1) ) {

        var iNivelEnsino = sNivelEnsino.selectedIndex + 1;
        var auxText = sNivelEnsino.options[iNivelEnsino].text;
        var auxValue = sNivelEnsino.options[iNivelEnsino].value;
        sNivelEnsino.options[iNivelEnsino] = new Option(sNivelEnsino.options[iNivelEnsino - 1].text,sNivelEnsino.options[iNivelEnsino - 1].value);
        sNivelEnsino.options[iNivelEnsino - 1] = new Option(auxText,auxValue);
        sNivelEnsino.options[iNivelEnsino].selected = true;
      }
    }

    function js_selecionar(){

      var sNivelEnsino  = document.getElementById("nivel_ensino").options;
      sRegistros        = "";
      sSeparador        = "";

      for ( var i = 0; i < sNivelEnsino.length; i++ ) {

        sNivelEnsino[i].selected = true;
        sRegistros              += sSeparador + sNivelEnsino[i].value;
        sSeparador               = ",";
      }

      if ( sRegistros!="" ) {

       js_divCarregando("Aguarde, atualizando registro(s)","msgBox");

       var sAction      = 'UpdateNiveisEnsino';
       var sUrl         = 'edu1_ordenacaoniveisensinoRPC.php';
       var sParametros  = 'sAction='+sAction+'&sRegistros='+sRegistros;
       var oAjax        = new Ajax.Request(sUrl, { method    : 'post',
                                            parameters: sParametros,
                                            onComplete: js_retornaUpdate
                                        });
      }
    }

    function js_retornaUpdate(oAjax) {
      js_removeObj("msgBox");
      var oRetorno = eval("("+oAjax.responseText+")");
      alert(oRetorno.urlDecode());
      window.location.href = 'edu2_ordenarniveisensino001.php';
    }

  </script>
</html>
