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
use ECidade\Educacao\Escola\Censo\Importacao\Factory\ArquivoImportacao;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
    <html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/ProgressBar.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body >
<div class="container">
    <fieldset style="width: 700px; padding: 2px">
        <progress id="barra-progresso" value="0" style="width: 100%; height: 25px;">Processando</progress>
    </fieldset>
    <fieldset style="width: 700px; padding: 1px 2px">
        <div id="log-processamento"></div>
    </fieldset>
</div>
<?php
db_menu();
?>
</body>
<script type='text/javascript'>
</script>

<script type="text/javascript">
  var bar = $('barra-progresso');
  var logs = $('log-processamento');
  var progress = new ProgressBar(bar, logs);
</script>
<?php
db_postmemory($_POST);

$sMsgErro = '';
$iAnoAtual = date("Y", db_getsession("DB_datausu"));
$oPost = db_utils::postMemory($_POST);
$iAnoEscolhido = isset($oPost->ano_opcao) ? $oPost->ano_opcao : $iAnoAtual;
$lErro = false;

if (isset($oPost->importar)) {
    try {
        if(empty($_FILES['arquivo'])) {
            throw new BusinessException('Selecione o arquivo a ser importado.');
        }

        db_inicio_transacao();

        $iCodigoInepEscola = null;

        if (isset($oPost->codigoinep_banco) && trim($oPost->codigoinep_banco) != "") {
            $iCodigoInepEscola = $oPost->codigoinep_banco;
        }

        $oCenso = ArquivoImportacao::getArquivoPorAno($iAnoEscolhido, $iCodigoInepEscola);

        if($oCenso == null) {
            throw new BusinessException("Arquivo do ano escolhido não existe!");
        }

        if($oCenso->temInconsistencia()) {
            $oCenso->gerarLog();
            throw new BusinessException();
        }

        $progressBar = new ProgressBar('progress');
        $progressBar->flush();
        $progressBar->updateMaxProgress($oCenso->getTotalAlunosArquivo());
        $progressBar->setMessageLog("Importando os alunos...");

        $oCenso->lIncluirAlunoNaoEncontrado = true;
        $oCenso->setProgressBar($progressBar);
        $iContador = 0;

        foreach($oCenso->getCaminhosArquivosFatiados() as $indice => $oArquivo) {
            $oCenso->sCaminhoArquivo = $oArquivo->sCaminho;
            $oCenso->importarArquivo();
        }

        $lErro = false;
    } catch (Exception $eException) {
        $lErro = true;
        $sMsgErro = $eException->getMessage();
    }

    db_fim_transacao($lErro);

    $sUrl = "edu4_importaralunoscenso001.php";

    if(!$lErro && !empty($oCenso)) {
        if(!$oCenso->temRegistroImportado()) {
            db_msgbox('Importação concluída com sucesso. Porém, nenhum novo aluno foi incluído.');
            db_redireciona($sUrl);
        }

        if(!empty($oCenso->sNomeArquivoLog)) {
            db_redireciona("edu4_importaralunoscenso001.php?sArquivoLog={$oCenso->sNomeArquivoLog}");
        }
    }

    if($lErro) {
        if(!empty($oCenso) && $oCenso->temInconsistencia()) {
            db_redireciona("edu4_importaralunoscenso001.php?lTemInconsistencia=true&sArquivoLog={$oCenso->sNomeArquivoLog}");
        }

        db_msgbox($sMsgErro);
        db_redireciona($sUrl);
    }
}
