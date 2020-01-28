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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

$oJson = JSON::create();
$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {
    switch ($oParam->exec) {
        case 'buscarAvaliacao':
            if (empty($oParam->iMatricula)) {
                throw new BusinessException("Matrícula não informada.");
            }

            $configuracao = new Configuracao();
            $formularioId = $configuracao->getFormulario(Tipo::SERVIDOR);

            $lTrazerSugestoes = false;
            if (!empty($oParam->trazerSugestoes)) {
                $lTrazerSugestoes = $oParam->trazerSugestoes;
            }
            $oServidor  = ServidorRepository::getInstanciaByCodigo($oParam->iMatricula);
            $oAvaliacao = new AvaliacaoEsocialAdapter(AvaliacaoRepository::getAvaliacaoByCodigo($formularioId));
            $oAvaliacao->setServidor($oServidor);
            $oAvaliacao->trazerSugestoes($lTrazerSugestoes);



            $oRetorno->oFormulario = $oAvaliacao->getObject();
            break;

        case 'salvarAvaliacao':
            if (empty($oParam->iMatricula)) {
                throw new BusinessException("Matrícula não informada.");
            }

            $oAvaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->iCodigoAvaliacao);
            $oAvaliacao->setAvaliacaoGrupo();

            $iCodigoGrupoPerguntas = null;

            if (!empty($oParam->iCodigoGrupoPerguntas)) {
                $iCodigoGrupoPerguntas = $oParam->iCodigoGrupoPerguntas;
            }

            $oServidor         = ServidorRepository::getInstanciaByCodigo($oParam->iMatricula);
            $oAvaliacaoESocial = new AvaliacaoESocial();
            $oAvaliacaoESocial->setAvaliacao($oAvaliacao);
            $oAvaliacaoESocial->setServidor($oServidor);
            $oAvaliacaoESocial->setPerguntasRespostas($oParam->aPerguntasRespostas);
            $oAvaliacaoESocial->salvar($iCodigoGrupoPerguntas);

            $oRetorno->mensagem = "Avaliação salva com sucesso.";
            break;

        case 'getMatriculas':
            $oCgm = UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario"))->getCGM();
            if (empty($oCgm)) {
                throw  new BusinessException("Usuário sem CGM vinculado.");
            }
            if (!$oCgm instanceof CgmFisico) {
                throw  new BusinessException("Cgm do Usuário está cadastradado como Pessoa Jurídica");
            }

            $aMatriculas = ServidorRepository::getServidoresByCgm($oCgm);

            if (count($aMatriculas) == 0) {
                throw  new BusinessException("Cgm não é servidor da instituição");
            }
            $oRetorno->matriculas = array_map(function (Servidor $oServidor) {

                $oStdMatricula            = new \stdClass();
                $oStdMatricula->matricula = $oServidor->getMatricula();
                $oStdMatricula->nome      = $oServidor->getCgm()->getNome();
                return $oStdMatricula;
            }, $aMatriculas);

            usort($oRetorno->matriculas, function ($oMatricula, $oProximaMatricula) {
                   return ($oMatricula->matricula > $oProximaMatricula->matricula);
            });
            break;

        case 'limparRespostas':
            if (empty($oParam->pergunta)) {
                throw new ParameterException("Informe a pergunta para limpar as respostas.");
            }

            db_inicio_transacao();

            $oDaoAvaliacaoResposta = new cl_avaliacaoresposta();
            $rsAvaliacaoResposta   = db_query($sqlAvaliacaoResposta = $oDaoAvaliacaoResposta->sql_query(null, ' db106_sequencial AS respostas', null, "db103_sequencial = {$oParam->pergunta}"));

            if (!$rsAvaliacaoResposta) {
                throw new BusinessException("Ocorreu um erro ao excluir as respostas da pergunta.");
            }

            if (pg_num_rows($rsAvaliacaoResposta) == 0) {
                throw new DBException("Não há respostas para esta pergunta.");
                break;
            }

            $respostasApagar = db_utils::makeCollectionFromRecord($rsAvaliacaoResposta, function ($retorno) {
                return $retorno->respostas;
            });

            $oDaoAvaliacaoGrupoPerguntaResposta = new cl_avaliacaogrupoperguntaresposta();

            if ($oDaoAvaliacaoGrupoPerguntaResposta->excluir(null, "db108_avaliacaoresposta in (". implode(',', $respostasApagar) .")") == false) {
                  throw new DBException($oDaoAvaliacaoGrupoPerguntaResposta->erro_msg);
            }

            if ($oDaoAvaliacaoResposta->excluir(null, "db106_sequencial in (". implode(',', $respostasApagar) .")") == false) {
                  throw new DBException($oDaoAvaliacaoResposta->erro_msg);
            }

            db_fim_transacao(false);
            $oRetorno->mensagem = 'Respostas excluídas com sucesso.';

            break;
    }
} catch (Exception $e) {
    if (db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->erro = true;
    $oRetorno->mensagem = $e->getMessage();
}
echo $oJson->stringify($oRetorno);
