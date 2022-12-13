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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$iTamanhoLimite    = 0;
$iIdComponente     = 0;
$sInputFile        = 'imagem';
$lAjax             = true;
$sCaminhoArquivo   = '';
$sNomeArquivo      = '';
$sErro             = '';
$lSubmitFormulario = true;
$sExtensao         = '';
try {

  /**
   * Valida se o que foi postado não ultrapassou o limite do PHP
   */
  db_utils::checkContentSize();

  $oGet           = db_utils::postMemory($_GET);
  $oPost          = db_utils::postMemory($_POST);
  $oFiles         = db_utils::postMemory($_FILES);


  if (!isset($oPost->json)) {

    $iTamanhoLimite = $oGet->sizeLimit;
    $iIdComponente  = $oGet->id;
    $sInputFile     = $oGet->input . '-' . $iIdComponente;
    $lAjax          = false;
  }

  if (isset($_FILES[$sInputFile])) {

    $sNomeArquivo       = $_FILES[$sInputFile]["name"];
    $sArquivoTemporario = $_FILES[$sInputFile]["tmp_name"];
    $iCodigoErro        = $_FILES[$sInputFile]['error'];
    $sExtensao          = pathinfo( $sNomeArquivo, PATHINFO_EXTENSION );
    $sCaminhoArquivo    = md5(time() . $sNomeArquivo);
    $sCaminhoArquivo    = "tmp/{$sCaminhoArquivo}.{$sExtensao}";

    /**
     * Verifica se existe erro no upload
     */
    switch ($iCodigoErro) {

      /**
       * Value: 4; No file was uploaded.
       */
      case UPLOAD_ERR_NO_FILE :

      /**
       * Value: 6; Missing a temporary folder.
       */
      case UPLOAD_ERR_NO_TMP_DIR :

      /**
       * Value: 7; Failed to write file to disk.
       */
      case UPLOAD_ERR_CANT_WRITE :

      /**
       * Value: 8; A PHP extension stopped the file upload.
       */
      case UPLOAD_ERR_EXTENSION :
        throw new Exception("Erro ao enviar arquivo.");
      break;

      /**
       * Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
       */
      case UPLOAD_ERR_INI_SIZE :
      case UPLOAD_ERR_FORM_SIZE :
        throw new Exception("Arquivo ultrapassa o limite configurado.");
      break;

      /**
       * Value: 3; The uploaded file was only partially uploaded.
       */
      case UPLOAD_ERR_PARTIAL :
        throw new Exception("Arquivo enviado parcialmente, tente novamente.");
      break;
    }

    if (empty($sArquivoTemporario)) {
      throw new Exception("Arquivo para upload não informado.");
    }

    if (!move_uploaded_file($sArquivoTemporario, $sCaminhoArquivo)) {
      throw new Exception('Erro ao copiar arquivo para diretório: ' . $sCaminhoArquivo);
    }

    $lSubmitFormulario = false;
  }

} catch(Exception $oErro) {
  $sErro = $oErro->getMessage();
}

if (!$lAjax) {
  return viewRetornoHTML($sInputFile, $lSubmitFormulario, $sErro, $sCaminhoArquivo, $sNomeArquivo, $iIdComponente, $sExtensao);
}

return viewRetornoAjax($sInputFile, $lSubmitFormulario, $sErro, $sCaminhoArquivo, $sNomeArquivo, $iIdComponente, $sExtensao);


function viewRetornoAjax($sInputFile, $lSubmitFormulario, $sErro, $sCaminhoArquivo, $sNomeArquivo, $iIdComponente, $sExtensao) {

  echo JSON::create()->stringify(array(
    "nomeobjeto"     => $sInputFile,
    "erro"           => !empty($sErro),
    "mensagem"       => $sErro,
    "nome_original"  => $sNomeArquivo,
    "caminho_upload" => $sCaminhoArquivo,
    "extensao"       => $sExtensao
  ));
  return;
}

function viewRetornoHTML($sInputFile, $lSubmitFormulario, $sErro, $sCaminhoArquivo, $sNomeArquivo, $iIdComponente, $sExtensao) {

  $oGet = db_utils::postMemory($_GET);
  $lSubmitFormulario = !!$lSubmitFormulario ? 'true' : 'false';

  echo <<<HTML

    <html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    </head>
      <body>    
        <form name="formUploadFile" id="formUploadFile" method="post" action="" enctype="multipart/form-data">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $oGet->sizeLimit; ?>" />
        </form>    
      </body>
    </html>
    <script type="text/javascript">
    (function() {

      var sInputFile        = '{$sInputFile}';
      var lSubmitFormulario = $lSubmitFormulario;
      var sErro             = '{$sErro}';
      var sCaminhoArquivo   = '{$sCaminhoArquivo}';
      var sNomeArquivo      = '{$sNomeArquivo}';
      var iIdComponente     = {$iIdComponente};
      var sExtensao         = '{$sExtensao}';

      var oParametros = {
        'filePath'  : sCaminhoArquivo,
        'file'      : sNomeArquivo,
        'error'     : sErro,
        'extension' : sExtensao
      }; 

      if (lSubmitFormulario && sErro == '') {
        
        var file = parent.$(sInputFile).cloneNode(true);    
        $('formUploadFile').appendChild(file);
        $('formUploadFile').submit(); 
        return;
      }  
      
      return parent.DBFileUpload.getInstance(iIdComponente).processCallBack(oParametros); 
    })();
    </script>
HTML;
  return true;
}