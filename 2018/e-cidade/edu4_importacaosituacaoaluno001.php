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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load('scripts.js, prototype.js, strings.js, arrays.js, DBFileUpload.widget.js, AjaxRequest.js');
  db_app::load('estilos.css');
?>

</head>
<body bgcolor="#cccccc">
  <div class="container">
    <form method="post" action="" enctype="multipart/form-data">
      <fieldset>
       <legend>Importar Situação do Aluno</legend>
        <fieldset class="separator">
          <legend><label for='ano'>Selecione o ano do arquivo</label></legend>

          <select id='ano' name="ano" class="field-size-max"> </select>
        </fieldset>

        <fieldset class="separator">
          <legend>Clique no botão "Arquivo" e selecione o arquivo</legend>
            <div id="ctnImportacao"></div>
        </fieldset>
      </fieldset>

       <input type="button" id="btnProcessar" value="Processar" >
    </form>
  </div>
  <?php db_menu(); ?>
</body>
<script type="text/javascript">

var sMgsFile = 'educacao.escola.edu4_importacaosituacaoaluno001.';
var oData    = new Date();
var oCboAno  = $('ano');
oCboAno.add( new Option(oData.getFullYear(), oData.getFullYear()) );
oCboAno.add( new Option(oData.getFullYear() - 1, oData.getFullYear() - 1, true) );

$('btnProcessar').disabled = true;


function retornoEnvioArquivo(oRetorno) {

  if (oRetorno.error) {

    alert(oRetorno.error);
    $('btnProcessar').disabled = true;
    return false;
  }

  if( oRetorno.extension.toLowerCase() != 'txt' ) {

    alert( _M( sMgsFile + 'arquivo_invalido' ) );
    $('btnProcessar').disabled = true;
    return false;
  }

  $('btnProcessar').disabled = false;
}

var oFileUpload = new DBFileUpload( {callBack: retornoEnvioArquivo, labelButton : 'Arquivo'} );
    oFileUpload.show($('ctnImportacao'));

document.querySelector(".inputUploadFile").addClassName('field-size5');


/**
 * Processa o arquivo que foi feito upload, enviando os dados para o RPC
 */
$('btnProcessar').addEventListener('click', function() {

  var oParametros = {
    'exec'  : 'importarSituacaoAluno',
    'iAno'  : $F('ano'),
    'sFile' : oFileUpload.file,
    'sPath' : oFileUpload.filePath
  };

  new AjaxRequest( 'edu4_censo.RPC.php', oParametros, function ( oRetorno, lErro ) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return false;
    }

    var jan  = window.open( 'edu4_importacaosituacaoaluno002.php?iAno='+oRetorno.iAno+'&sCaminhoArquivo='+oRetorno.sArquivoLog,
                            'Erros Geração de Arquivo de Situação do Aluno do Censo escolar',
                            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                          );
    jan.moveTo(0,0);

  }).setMessage( _M( sMgsFile + 'processando_arquivo' ) ).execute();
});


</script>

</html>