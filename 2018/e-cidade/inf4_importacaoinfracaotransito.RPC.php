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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

use ECidade\Financeiro\Tesouraria\InfracaoTransito\Importacao\Importacao;

$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';
$oRetorno->multasNaoProcessadas = false;
$iAno = db_getsession("DB_anousu");

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case "importar":
            if (empty($oParam->sArquivos)) {
                throw new \ParameterException("Nenhum arquivo informado.");
            }

            $oFiles = db_utils::postMemory($_FILES);

            if ($oFiles->arquivo_importacao['error'] != UPLOAD_ERR_OK) {
                throw new Exception("Houve um erro no envio do arquivo.");
            }

            $sArquivoNome = time() . $oFiles->arquivo_importacao['name'];
            $sCaminhoArquivo = 'tmp/' . $sArquivoNome;

            if (!move_uploaded_file($oFiles->arquivo_importacao['tmp_name'], $sCaminhoArquivo)) {
                throw new Exception("Houve um erro no envio do arquivo.");
            }

            $oImportacaoInfracoes = new Importacao();
            $oImportacaoInfracoes->setCaminhoArquivo($sCaminhoArquivo);
            $oRetorno->multasNaoProcessadas = false;
            $oRetorno->arquivoMultas = 'tmp/multas_nao_cadastradas_' . str_replace(" ", "_", $sArquivoNome) . ".json";
            $oImportacaoInfracoes->processar();
            if ($oImportacaoInfracoes->temMultasNaoProcessadas()) {
                $multas = array();
                $oRetorno->multasNaoProcessadas = true;

                foreach ($oImportacaoInfracoes->getMultasNaoCadastradas() as $multaNaoCadastrada) {
                    $oMulta = new \stdClass();
                    $oMulta->auto_infracao   = $multaNaoCadastrada->getAutoInfracao();
                    $oMulta->codigo_infracao = $multaNaoCadastrada->getCodigoInfracaoTransito();
                    $multas[] = $oMulta;
                }

                file_put_contents($oRetorno->arquivoMultas, json_encode($multas));
                throw new BusinessException("Foram encontradas multas não cadastradas.\nDeseja visualizá-las?");
            }
            $oRetorno->sMessage = "Arquivo importado e Lançamentos efetuados com sucesso!";
            $oRetorno->sMessage .= "\nCódigo da planilha gerada: {$oImportacaoInfracoes->getPlanilhaArrecadacao()->getCodigo()}";
            break;
    }

    db_fim_transacao(false);
} catch (BusinessException $oErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $oErro->getMessage();
} catch (DBException $oErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $oErro->getMessage();
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $oErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);
