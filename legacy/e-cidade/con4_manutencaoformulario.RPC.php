<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;
use ECidade\Configuracao\Formulario\Arquivo\Yaml\Parser;
use ECidade\Configuracao\Formulario\Arquivo\Yaml\Validator;
use ECidade\Configuracao\Formulario\Arquivo\Yaml\Dumper;
use ECidade\Configuracao\Formulario\Importacao\Import;
use ECidade\Configuracao\Formulario\Exportacao\Export;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/JSON.php");
require_once modification("dbforms/db_funcoes.php");

$oJson = JSON::create();
$oParam = $oJson->parse(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case 'getDadosFormulario':
            $formulario = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->formulario);
            $avaliacao  = new AvaliacaoAdapter($formulario);
            if (!empty($oParam->codigo_resposta)) {

            $formulario->setAvaliacaoGrupo($oParam->codigo_resposta);
            $avaliacao->setCodigoGrupoResposta($oParam->codigo_resposta);
            }
            $oRetorno->oFormulario = $avaliacao->getObject();
            break;

        case 'salvar':
            $oFormulario     = \ECidade\Configuracao\Formulario\Repository\Formulario::getById($oParam->formulario);
            $aPerguntas      = getPerguntas($oParam->aPerguntasRespostas);
            $aPerguntasChave = $oFormulario->getPerguntasIdentificadoras();
            $aCampos = array();

            /**
             * @todo - Refatorar codigo complexo
             */
            $oResposta   = new \ECidade\Configuracao\Formulario\Resposta\Model\Resposta();
            $oResposta->setFormulario($oFormulario);
            $oResposta->setData(new DBDate(date('Y-m-d', db_getsession("DB_datausu"))));
            if  (!empty($oParam->codigo_resposta)) {
                $oResposta = Resposta::getBydId($oFormulario, $oParam->codigo_resposta);
            }

            $iCodigoRespostaFormulario = !empty($oParam->codigo_resposta) ? $oParam->codigo_resposta : null;
            foreach ($aPerguntasChave as $pergunta) {

                foreach ($aPerguntas as $perguntaRespondidas) {
                    if ($perguntaRespondidas->codigo != $pergunta->getCodigo()) {
                        continue;
                    }

                    $respostaDadas = getRespostasDaPerguntas($perguntaRespondidas);
                    $valorResposta = $respostaDadas[0]->valor;
                    if ($pergunta->getTipoResposta() == AvaliacaoPergunta::TIPO_RESPOSTA_OBJETIVA) {

                        foreach ($respostaDadas as $resposta) {

                            if ($resposta->valor == 1) {
                                $valorResposta = $resposta->codigo;
                                break;
                            }
                        }
                    }
                    $aCampos[] = array("pergunta" => $pergunta, "resposta" => $valorResposta);
                }
            }

            if (count($aCampos) > 0) {
                $aRespostas = Resposta::getPorFormularioECampos($oFormulario, $aCampos);
                if (count($aRespostas) > 0 && $iCodigoRespostaFormulario != $aRespostas[0]->getCodigo()) {
                    $sMensagem = "Já existem dados cadastrados para o formulário {$oFormulario->getNome()}. Dados Informados:\n";
                    foreach ($aCampos as $campo) {
                        $sMensagem .= "{$pergunta->getDescricao()}: {$campo["resposta"]}\n";
                    }

                    $sMensagem .= " preenchimento informado: {$iCodigoRespostaFormulario}  preenchimento encontrado: " . $aRespostas[0]->getCodigo();
                    throw new BusinessException( $sMensagem);
                }
            }



            /*
            * @todo mover codigo para classe de preenchimento
            * Codigo muito complexo
            */
            foreach ($oFormulario->getPerguntas() as $pergunta) {

                foreach ($aPerguntas as $perguntasRespondidas) {

                    if ($perguntasRespondidas->codigo == $pergunta->getCodigo()) {

                        switch ($pergunta->getTipoResposta()) {

                            case AvaliacaoPergunta::TIPO_RESPOSTA_DISSERTATIVA:
                                $oResposta->adicionarRespostaParaPergunta($pergunta, $perguntasRespondidas->respostas[0]->valor);
                                break;

                            case AvaliacaoPergunta::TIPO_RESPOSTA_OBJETIVA:
                                foreach ($perguntasRespondidas->respostas as $resposta) {
                                    if ($resposta->valor) {
                                        $oResposta->adicionarRespostaParaPergunta($pergunta, $resposta->codigo);
                                    }
                                }
                                break;
                        }
                    }
                }
            }
            Resposta::persist($oResposta);
            if (!empty($oParam->iCodigoGrupoPerguntas)) {
                $iCodigoGrupoPerguntas = $oParam->iCodigoGrupoPerguntas;
            }
            $oRetorno->mensagem = "Dados do formulário foram salvos com sucesso.";
            break;

        case 'remover':
            $formulario  = \ECidade\Configuracao\Formulario\Repository\Formulario::getById((int)$oParam->formulario);
            if (empty($formulario)) {
                throw new BusinessException("Formulário de código ({$oParam->formulario}) não encontrado no sistema. Verifique.");
            }

            $resposta = Resposta::getBydId($formulario, (int)$oParam->codigo_resposta);
            if (empty($resposta)) {
                throw new BusinessException('Resposta não encontrada no sistema. Verifique.');
            }
            Resposta::remover($resposta);
            $oRetorno->mensagem = "Resposta removida com sucesso.";
            break;

        case 'importar':
            $oFiles = db_utils::postMemory($_FILES);
            if (empty($oFiles)) {
                throw new Exception('Selecione um arquivo');
            }

            $sDestino =  ECIDADE_PATH . "tmp". DS . $oFiles->file["name"];
            move_uploaded_file($oFiles->file["tmp_name"], $sDestino);

            $oValidador = new Validator($sDestino);
            if (!$oValidador->validate()) {
                throw new \Exception(implode("\n", $oValidador->getErrors()));
            }

            $oParser = new Parser($sDestino);
            $oImport = new Import($oParser);
            $oImport->import();

            $oRetorno->mensagem = "Formulário importado com sucesso.";

            break;

        case 'exportar':
            if ( empty($oParam->formulario) ) {
                throw new Exception("Informe o formulário.");
            }

            $avaliacao = new \Avaliacao($oParam->formulario);
            $export = new Export(new Dumper($avaliacao));

            $oRetorno->sArquivo = $export->export();
            break;
    }

    db_fim_transacao(false);
} catch (Exception $e) {
    if (db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->erro = true;
    $oRetorno->mensagem = $e->getMessage();
}
echo $oJson->stringify($oRetorno);

/**
 * Retorna as respostas
 * @param $aPerguntasRespostas
 * @return array
 */
function getPerguntas($aPerguntasRespostas) {

    $perguntas = array();
    foreach ($aPerguntasRespostas->grupos as $grupos) {
        foreach ($grupos->perguntas as $pergunta) {
            $perguntas[] = $pergunta;
        }
    }
    return $perguntas;
}

/**
 * Retorna as respostas
 * @param $pergunta
 * @return array
 * @internal param $aPerguntasRespostas
 */
function getRespostasDaPerguntas($pergunta) {

    $respostas = array();
    foreach ($pergunta->respostas as $resposta) {
        $respostas[] = $resposta;
    }
    return $respostas;
}
