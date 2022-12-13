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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php

  db_app::load("scripts.js, prototype.js, estilos.css, grid.style.css, datagrid.widget.js");
  db_app::load("dbcomboBox.widget.js, widgets/DBDownload.widget.js, AjaxRequest.js");
?>
<style type="text/css">

  #windowErroAvisos{
    overflow: hidden;
  }
  #windowwindowErroAvisos_content{
    overflow: hidden !important;
  }
</style>
</head>
<body class="body-default">
  <div class="container">
    <form class="container" id="form1" name="form1" method="post" action="" target="">
      <fieldset>
        <legend>Geração de arquivo para INSS</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="iMes" id="lbl_imes">Competência das Obras:</label>
              <input id="iMes" maxlength="2" size="2" onChange="js_ValidaCampos(this,1,'Mês da obra','f','f',event);" name="iMes" />
               &nbsp; <strong>/</strong> &nbsp;
              <input id="iAno" maxlength="4" size="4" onChange="js_ValidaCampos(this,1,'Ano da obra','f','f',event);" name="iAno" />
            </td>
         </tr>
        </table>
      </fieldset>
      <input name="gerar" id="gerar" onClick="js_gerarArquivo('false');" type="button" value="Gerar" />
    </form>
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

var sUrlRPC           = 'pro4_gerarTxtINSS.RPC.php';
var sCaminhoMensagens = 'tributario.projetos.pro4_gerarTxt.';

/**
 * Função que enviaa os dados para o RPC
 */
function js_gerarArquivo(lAviso){

  $('gerar').disabled = true;

  var iMes   = $F('iMes');
  var iAno   = $F('iAno');

  try {

    if (iMes == null || iMes == '') {

      throw ( _M ( sCaminhoMensagens + "mes_obrigatorio" ) );
      $('iMes').focus();
    }

    if (iMes <= 0 || iMes > 12) {

      throw ( _M ( sCaminhoMensagens + "mes_invalido" ) );
      $('iMes').focus();
    }

    if (iAno == null || iAno == '') {

      throw ( _M ( sCaminhoMensagens + "ano_obrigatorio" ) );
      $('iAno').focus();
    }

    if (iAno <= 0) {

      throw ( _M ( sCaminhoMensagens + "ano_invalido" ) );
      $('iMes').focus();
    }

  }catch ( sMensagemErro ) {

    alert(sMensagemErro);
    $('gerar').disabled = false;
    return false;
  }

  var oParametros = {
      sExecucao : "gerarTXT",
      iMes      : iMes,
      iAno      : iAno,
      lAviso    : lAviso
  }

  new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      $('gerar').disabled = false;
      return false;
    }

    if (oRetorno.iInconsistencia == 1) {

      js_mostraErros();
      js_listaErros(oRetorno.aErros);
      return false;
    }

    windowErroAvisos.destroy();

    /**
     * Janela de Download do arquivo gerado
     */
    var oDownload = new DBDownload();

    // Verifica se já existe, se existir apaga-o
    if( $('window01') ){
      $('window01').outerHTML = '';
    }
    oDownload.addGroups( 'txt', 'Arquivo INSS');
    oDownload.addFile( oRetorno.sArquivo, 'Download do Arquivo', 'txt' );
    oDownload.show();

    $('gerar').disabled = false;
    $('form1').reset();

  }).setMessage( _M( sCaminhoMensagens + 'processando_arquivo' ) ).execute();
}

function js_mostraErros() {

  var iLarguraJanela = screen.availWidth  - 400;
  var iAlturaJanela  = screen.availHeight - 250;

  windowErroAvisos   = new windowAux( 'windowErroAvisos',
                                      'Erros e Avisos Encontrados',
                                      iLarguraJanela,
                                      iAlturaJanela
                                    );

  var sConteudoErroAvisos  = "<div>";
        sConteudoErroAvisos += "<div id='sTituloWindow'></div> ";
        sConteudoErroAvisos += "<div id='sContGrid'></div> ";
        sConteudoErroAvisos += "<div id='ctnGerarRelatorio' style='margin-top:10px; text-align:center;'>";
        sConteudoErroAvisos += " <input type='button' value='Emitir Relatório' onclick='js_emiteRelatorio();' />";
        sConteudoErroAvisos += " <input type='button' id='gerararquivo' value='Processar Arquivo' onclick='js_gerarArquivo(\"true\");' />";
        sConteudoErroAvisos += " <input type='button' value='Cancelar' onclick='js_destroyWindowAux();' />";
        sConteudoErroAvisos += "</div>";
      sConteudoErroAvisos += "</div>";

   windowErroAvisos.setContent(sConteudoErroAvisos);

  var sTextoMessageBoard  = '<p style="padding:0; margin:0;">Registros do tipo <strong>ERRO</strong> devem ser corrigidos.</p>';
      sTextoMessageBoard += '<p style="padding:0; margin:0;">Registros do tipo <strong>AVISO</strong> podem ser ignorados.</p>';
      messageBoard        = new DBMessageBoard('msgboard1',
                                               'Dados das Inconsistências do Arquivo.',
                                                sTextoMessageBoard,
                                                $('sTituloWindow'));

   windowErroAvisos.setShutDownFunction(function () {
     js_destroyWindowAux();
   });

   windowErroAvisos.show();
   messageBoard.show();
   js_montaGridErros();
}

function js_destroyWindowAux(){

  windowErroAvisos.destroy();
  $('gerar').disabled = false;
}

function js_montaGridErros() {

  oGridErroAvisos = new DBGrid('Erro e Avisos');
  oGridErroAvisos.nameInstance = 'oGridErroAvisos';
  oGridErroAvisos.allowSelectColumns(false);
  oGridErroAvisos.setCellWidth( new Array( '10px', '20px', '80px' ) );
  oGridErroAvisos.setCellAlign( new Array( 'center', 'left', 'left' ) );
  oGridErroAvisos.setHeader( new Array('Tipo', 'Registro', 'Detalhes') );
  oGridErroAvisos.setHeight(300);
  oGridErroAvisos.show($('sContGrid'));
  oGridErroAvisos.clearAll(true);
}

function js_listaErros(aErros){

  aErros.each(
               function (oDado, iInd) {
                   var aRow    = new Array();

                       var sTipoInconsistencia = 'AVISO';
                      /**
                       * Verifica se ha erros para bloquear a geração do arquivo
                       * caso tenha somente avisos, sera permitida a geração
                       */
                       if (oDado.tipo == "ERRO") {
                         $('gerararquivo').disabled = true;
                         sTipoInconsistencia = '<span style="color:#FF0000; font-weight:bold;">ERRO</span>';
                       }
                       aRow[0] = sTipoInconsistencia;
                       aRow[1] = oDado.registro.urlDecode();
                       aRow[2] = oDado.detalhe.urlDecode();
                       oGridErroAvisos.addRow(aRow);
                  });
  oGridErroAvisos.renderRows();
}

function js_emiteRelatorio(){

  var sFonte  = "pro3_inconsistenciaInss.php";
      sQuery  = "?sDataA=a";
      sQuery += "&sDataB=b";
      jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}

</script>