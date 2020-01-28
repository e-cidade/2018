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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("model/dbLayoutLinha.model.php"));

db_postmemory($_POST);

/**
 * Função que verifica se o arquivo de LOG possui mais de uma linha.
 *
 * @param  String $sLog  => Arquivo do LOG
 * @return boolean true  => Se existir mais de uma linha
 *                 false => Se existir uma linha
 *
 * @author Thiago A. de Lima - thiago.lima@dbseller.com.br
 */
function webChecaLog($sLog) {

  $iContador   = 0;
  $pArquivoLog = fopen($sLog, "r");
  $lVazio      = true;

  if( file_exists($sLog)){

    while (!feof($pArquivoLog)) {

      $sLinhaArquivo = fgets($pArquivoLog, 2000);

      if (trim($sLinhaArquivo) != "") {
        $iContador++;
      }

      if ($iContador > 1) {
        $lVazio = false;
        break;
      }

    }

    if (!$lVazio) {
      return true;
    } else {
      return false;
    }

  }else{

    return true;
  }

}

$oDaoEscola        = db_utils::getdao('escola');
$iEscola           = db_getsession("DB_coddepto");
$iAnoAtual         = date("Y",db_getsession("DB_datausu"));
$oPost             = db_utils::postMemory($_POST);
$oFile             = db_utils::postMemory($_FILES);
$iCodigoInepEscola = '';
$iAnoEscolhido     = '';

if (isset($oPost->importar)) {

  $lErro     = false;
  $aAnoOpcao = array( 2010, 2011, 2012, 2013, 2014, 2015 );

  db_inicio_transacao();

  try {

    if (isset($oPost->codigoinep_banco)) {
      $iCodigoInepEscola = $oPost->codigoinep_banco;
    }

    if (isset($oPost->ano_opcao)) {
      $iAnoEscolhido= $oPost->ano_opcao;
    }

    switch ($oPost->ano_opcao) {

      case '2010':

        require_once(modification("model/educacao/importacaoCodigoInep2010.model.php"));
        $oCenso = new importacaoCodigoInep2010($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola, 98 );
        $oCenso->lLayoutComPipe = false;
        break;

      case '2014':

        require_once(modification("model/educacao/importacaoCodigoInep2014.model.php"));
        $oCenso = new importacaoCodigoInep2014($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola, 219);
        break;

      case '2015':

        $oCenso = new importacaoCenso2015($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola, 226);
        $oCenso->setCodigoEscola( $iEscola );
        break;

      default:

        require_once(modification("model/educacao/importacaoCodigoInep2011.model.php"));
        $oCenso = new importacaoCodigoInep2011($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola, 96);
        break;
    }

    if ( in_array( $oPost->ano_opcao, $aAnoOpcao ) ) {

      if (isset($oPost->turma)) {
        $oCenso->lImportarTurma = true;
      }

      if (isset($oPost->docente)) {
        $oCenso->lImportarDocente = true;
      }

      if (isset($oPost->aluno)) {
        $oCenso->lImportarAluno = true;
      }

      if ($iAnoEscolhido >= 2015) {
        $oCenso->importarINEP();
      } else {
        $oCenso->importarArquivo();
      }

    } else {
      db_msgbox("Arquivo do ano escolhido não existe!");
    }

  } catch ( Exception $eException ) {

    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }

  db_fim_transacao( $lErro );
}

if ( isset($ano_opcao) ) {
  $sTituloFieldset = "<font color='red'> -> CÓDIGO INEP</font>";
} else {
  $sTituloFieldset = "";
}

$sSqlEscola = $oDaoEscola->sql_query("", "ed18_c_codigoinep", "", "ed18_i_codigo = $iEscola");
$rsEscola = $oDaoEscola->sql_record($sSqlEscola);
$iCodigoInepBanco = db_utils::fieldsMemory($rsEscola, 0)->ed18_c_codigoinep;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class="container">
    <form name="form1" enctype="multipart/form-data" method="post" action="">
      <fieldset>
        <legend>Importação de informações do CENSO ESCOLAR <?=$sTituloFieldset?></legend>
        <table class="form-container">
          <tr>
            <td>
              <label for='ano_opcao'>Ano das informações do arquivo:</label>
            </td>
            <td>
              <select name="ano_opcao">
                <option value="<?=$iAnoAtual?>" <?=@$ano_opcao == $iAnoAtual ? "selected" : "" ?>><?=$iAnoAtual?></option>
                <option
                  value="<?=$iAnoAtual - 1?>" <?=@$ano_opcao == $iAnoAtual - 1 ? "selected" : "" ?>><?=$iAnoAtual - 1?></option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label for="codigoinep_banco">Código INEP:</label>
            </td>
            <td>
              <input type="text" name="codigoinep_banco" value="<?=$iCodigoInepBanco?>" size="8" readonly style="background: #deb887;">
            </td>
          </tr>
          <tr>
            <td>
              <label for="arquivo">Dados para importar:</label>
            </td>
            <td>
              <input type="checkbox" name="turma" id="turma" value="turma" <?= !isset($iTurma) ? "" : "checked" ?>>
              <label for="turma">Turmas</label>
              <input type="checkbox" name="docente" id="docente" value="docente" <?= !isset($iDocente) ? "" : "checked" ?>>
              <label for="docente">Docentes</label>
              <input type="checkbox" name="aluno" id="aluno" value="aluno" <?= !isset($iAluno) ? "" : "checked" ?>>
              <label for="aluno">Alunos</label>
            </td>
          </tr>
          <tr>
            <td>
              <label for="arquivo">Arquivo de importação do Censo:</label>
            </td>
            <td>
              <?=db_input('arquivo', 50, @$Iarquivo, true, 'file', 3, "")?>
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <? if (trim($iCodigoInepBanco) == "") : ?>
                <div style="padding: 5px; color: red;">
                  * Código INEP desta escola não informado no sistema. Operação Não Permitida. <br>
                  <a href='edu1_escolaabas002.php'>Informar Código INEP</a>
                </div>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="importar" type="submit" id="importar" value="Importar"
             onclick="return js_valida()" <?=$iCodigoInepBanco == "" || isset($importar) ? "disabled" : ""?>>
    </form>
  </div>

<?php db_menu(); ?>

<script type="text/javascript">

  (function() {

    if ( $('msgbox') ) {
      js_removeObj($('msgbox'));
    }

    function js_criaObj() {
      js_divCarregando("Aguarde, importando dados...", "msgbox");
    }
  })();

  function js_valida() {

    if (document.form1.arquivo.value == "") {
      alert("Informe o arquivo para realizar a importação!");
      return false;
    }

    if ( !js_validaOpcao() ) {
      return false;
    }

    js_criaObj();
  }

  function js_tipoopcao(valor) {

    if (valor == 1) {
      document.getElementById("teste").style.display = "";
    } else if (valor == 2) {
      document.getElementById("teste").style.display = "none";
    }
  }

  function js_validaOpcao() {

    if (
      document.form1.turma.checked       == false
      && document.form1.aluno.checked       == false
      && document.form1.docente.checked     == false
    ) {

      alert( "Escolha no mínimo uma das opções para realizar a importação.\n(Escola, Turmas, Alunos ou Docentes)!" );
      return false;
    }

    return true;
  }
</script>
<?
if (isset($oPost->importar)) {

  if ($lErro) {
    db_msgbox(str_replace("\n","\\n",$sMsgErro));
  } else {

    if (webChecaLog($oCenso->sNomeArquivoLog)) {

      ?>
      <script type="text/javascript">
        sEndereco = 'edu4_importarcodigoinep002.php?arquivo_erro=<?=$oCenso->sNomeArquivoLog?>';
        jan       = window.open(sEndereco,'',
          'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
          ',scrollbars=1,location=0'
        );
        jan.moveTo(0,0);
      </script>
      <?php

    } else {
      db_msgbox("Importação dos Dados Realizada com Sucesso.");
    }
  }
  db_redireciona("edu4_importarmatriculainep001.php");
}
?>
</body>
</html>