<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
$iMesFolha  = db_mesfolha();
$iAnoFolha  = db_Anofolha();
$oDaoCfpess = new cl_cfpess();
$oRotuloCampo = new rotulocampo();
$oRotuloCampo->label("rh27_descr");
$oDaoCfpess->rotulo->label("r11_rubricaescalaferias");
$sMensagem = "";
if (!empty($_POST["btnSalvar"])) {

  try {

    db_inicio_transacao();
    $oDaoCfpess->r11_anousu              = $iAnoFolha;
    $oDaoCfpess->r11_mesusu              = $iMesFolha;
    $oDaoCfpess->r11_instit              = db_getsession("DB_instit");
    $oDaoCfpess->r11_rubricaescalaferias = $_POST["r11_rubricaescalaferias"];
    $oDaoCfpess->alterar($iAnoFolha, $iMesFolha, db_getsession("DB_instit"));
    $sMensagem = $oDaoCfpess->erro_msg;
    if ($oDaoCfpess->erro_status == 0) {
      throw new BusinessException($oDaoCfpess->erro_msg);
    }
    db_fim_transacao();
  } catch (Exception $oErro) {

    $sMensagem = $oErro->getMessage();
    db_fim_transacao();


  }

}
$sSqlRubricaFerias  = $oDaoCfpess->sql_query_parametro($iAnoFolha,
                                                       $iMesFolha,
                                                       db_getsession("DB_instit"),
                                                       "r11_rubricaescalaferias,
                                                       m.rh27_descr"
                                                      );

$rsRubricaFerias = $oDaoCfpess->sql_record($sSqlRubricaFerias);
if ($rsRubricaFerias && $oDaoCfpess->numrows > 0) {
  db_fieldsmemory($rsRubricaFerias, 0);
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="frmRubricasEscalaFerias" id="frmRubricasEscalaFerias" method="post">
      <fieldset>
        <legend>
           <b>Rubrica para Escala de Férias</b>
        </legend>
        <table>
          <tr>
            <td>
              <label for="r11_rubricaescalaferias">
                <a id='lblRubricasFerias' href="javascript:void(0)"><?=$Lr11_rubricaescalaferias?></a>
              </label>
            </td>
            <td>
              <?php
              db_input('r11_rubricaescalaferias',4,$Ir11_rubricaescalaferias,true,'text', 1,"onchange='js_pesquisa_rubricas(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="submit" value="Salvar" id="btnSalvar" name='btnSalvar'>
    </form>
  </div>
<?php db_menu(); ?>
</body>
</html>
<script>
  var oParametros = {
    "sArquivo" : "func_rhrubricas.php",
    "sLabel"   : "Pesquisar Rubricas",
    "sObjetoLookUp" : "db_iframe_rhrubricas"
  }
  $('r11_rubricaescalaferias').lang = 'rh27_rubric';
  new DBLookUp($('lblRubricasFerias'), $('r11_rubricaescalaferias'), $('rh27_descr'), oParametros);
  $('btnSalvar').observe("click", function(event) {

    if ($F('r11_rubricaescalaferias') == '') {

      alert('Campo Rubricas para Escala de Férias deve ser informado');
      event.stopPropagation();
      event.preventDefault();
      return false;
    }
  })
</script>
<?php
if (!empty($sMensagem)) {
  db_msgbox($sMensagem);
}
