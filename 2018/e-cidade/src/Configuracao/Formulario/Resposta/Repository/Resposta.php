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
namespace ECidade\Configuracao\Formulario\Resposta\Repository;

use ECidade\Configuracao\Formulario\Model\Formulario;
use ECidade\Configuracao\Formulario\Model\Opcao;
use ECidade\Configuracao\Formulario\Repository\Pergunta;
use ECidade\Configuracao\Formulario\Resposta\Model\Resposta as RespostaModel;
use ECidade\Configuracao\Formulario\Resposta\Model\Valor;

/**
 * Class Resposta
 * @package ECidade\Configuracao\Formulario\Model
 */
class Resposta
{

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @var \ECidade\Configuracao\Formulario\Resposta\Model\Resposta
     */
    public $respostas;


    protected function __construct()
    {
    }

    /**
     * @return \ECidade\Configuracao\Formulario\Resposta\Repository\Resposta
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return Resposta[]
     */
    public static function getPorFormularioEPerguntas(Formulario $oFormulario, array $aPerguntas)
    {
        $aCodigos = array();
        foreach ($aPerguntas as $oPergunta) {
            $aCodigos[] = $oPergunta->getCodigo();
        }

        $sCodigos = implode(', ', $aCodigos);
        $aWhere = array(
            "db101_sequencial = {$oFormulario->getCodigo()}",
            "db104_avaliacaopergunta in({$sCodigos})",
        );

        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;
        $sCampos = 'distinct db107_sequencial, db107_usuario, db107_datalancamento, db107_hora';
        $sSqlRespostas = $oDaoAvaliacaoResposta->sql_query_avaliacao(null, $sCampos, null, implode(' and ', $aWhere));
        $rsRespostas = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            throw new \BusinessException("Erro ao pesquisar as respostas do formulário {$oFormulario->getNome()}.");
        }

        $aRespostas = \db_utils::makeCollectionFromRecord($rsRespostas, function ($oDados) use ($oFormulario) {
            return Resposta::make($oDados, $oFormulario);
        });

        return $aRespostas;
    }


    /**
     * Retorna todos preenchimentos do formulário
     *
     * @param Formulario $formulario
     * @return array
     */
    private static function getPreenchimentos(Formulario $formulario)
    {
        $dao = new \cl_avaliacaogruporesposta();
        $sql = $dao->sql_avaliacao_preenchida(
            null,
            'distinct avaliacaogruporesposta.*',
            null,
            " db102_avaliacao = " . $formulario->getCodigo()
        );

        $rs = db_query($sql);
        if (!$rs) {
            throw new \Exception("Erro ao buscar preenchimentos.");
        }

        return \db_utils::getCollectionByRecord($rs);
    }

    /**
     * Busca as respostas das perguntas informadas de um preenchimento
     *
     * @param integer $idPreenchimento id do preenchimento
     * @param array $idPerguntas array com os id das perguntas
     * @return void
     */
    private static function getRespostaByPreenchimentoAndPergurnta($idPreenchimento, array $idPerguntas)
    {
        $campos = 'db103_sequencial as pergunta, ';
        $campos .= 'db103_avaliacaotiporesposta as tipo, ';
        $campos .= 'db106_avaliacaoperguntaopcao as opcao_respondida, ';
        $campos .= 'db106_resposta as resposta_texto ';

        $dao = new \cl_avaliacaogruporesposta();
        $sql = $dao->sql_avaliacao_preenchida(
            null,
            $campos,
            null,
            "db107_sequencial in ({$idPreenchimento}) and db103_sequencial in (". implode(', ', $idPerguntas) .") "
        );

        $rs = db_query($sql);
        if (!$rs) {
            throw new \Exception("Erro ao buscar respostas dos preenchimentos.");
        }

        return \db_utils::getCollectionByRecord($rs);
    }


    /**
     * Retorna os Preenchimentos que responderam as perguntas informadas
     * Se informado mais de uma pergunta, só retorna os Preenchimentos que responderam todas as perguntas
     * Se não retorna um array vazio
     *
     * @param \ECidade\Configuracao\Formulario\Model\Formulario $formulario
     * @param array                                             $perguntas
     * @throws \BusinessException
     *
     * @return Resposta[]|array
     */
    public static function getPorFormularioECampos(Formulario $formulario, array $perguntas)
    {
        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;
        $preenchimentos = self::getPreenchimentos($formulario);

        if (empty($preenchimentos)) {
            return array();
        }

        $idPerguntas = array();
        $respostasCarga = array();
        // Percorre as perguntas informadas separando os codigos das perguntas e as respostas em dois arrays
        foreach ($perguntas as $dadosPergunta) {
            $idPerguntas[] = $dadosPergunta['pergunta']->getCodigo();
            $respostasCarga[$dadosPergunta['pergunta']->getCodigo()] = $dadosPergunta['resposta'];
        }

        // Percorre todos preenchimentos do formulário e valida se encontra um preenchimento que respondeu
        // as perguntas informadas por parâmetro com as mesmas respostas
        foreach ($preenchimentos as $dadoPreenchimento) {
            $dadoPreenchimento->match = false;

            $respostaPreenchimento = self::getRespostaByPreenchimentoAndPergurnta($dadoPreenchimento->db107_sequencial, $idPerguntas);

            // controle para saber se as respostas informadas batem com as respostas chaves do formulário
            $iOpcoesRespondidas = 0;
            foreach ($respostaPreenchimento as $resposta) {
                // Se a pergunta é do tipo Objetiva devemos validar a resposta como o id da opção respondida
                if ($resposta->tipo == 1) {
                    if ($respostasCarga[$resposta->pergunta]  == $resposta->opcao_respondida) {
                        $iOpcoesRespondidas ++;
                    }
                } else {
                    // Quando a pergunta é descritiva devemos comparar a resposta digitada
                    if ($respostasCarga[$resposta->pergunta]  == $resposta->resposta_texto) {
                        $iOpcoesRespondidas ++;
                    }
                }
            }

            if (count($perguntas) == $iOpcoesRespondidas) {
                $dadoPreenchimento->match = true;
            }
        }

        $respostas = array();
        foreach ($preenchimentos as $dadoPreenchimento) {
            if ($dadoPreenchimento->match) {
                $respostas[] = Resposta::make($dadoPreenchimento, $formulario);
            }
        }
        return $respostas;
    }

    /**
     * Persiste os dados da Resposta
     * @param \ECidade\Configuracao\Formulario\Resposta\Model\Resposta $resposta
     * @throws \Exception
     */
    public static function persist(RespostaModel $resposta)
    {

        if ($resposta->getCodigo() == '') {
            $oDaoAvaliacaoGrupoResposta = new \cl_avaliacaogruporesposta;
            $oDaoAvaliacaoGrupoResposta->db107_datalancamento = $resposta->getData()->getDate();
            $oDaoAvaliacaoGrupoResposta->db107_hora = db_hora();
            $oDaoAvaliacaoGrupoResposta->db107_usuario = db_getsession("DB_id_usuario"); //todo retirar o usuario fixo.;

            $oDaoAvaliacaoGrupoResposta->incluir(null);
            if ($oDaoAvaliacaoGrupoResposta->erro_status == 0) {
                throw new \Exception("Erro ao salvar os dados da Resposta para o formulário {$resposta->getFormulario()->getNome()}");
            }
            $resposta->setCodigo($oDaoAvaliacaoGrupoResposta->db107_sequencial);
        }

        foreach ($resposta->getRespostas() as $valorResposta) {
            $iCodigoResposta = $valorResposta->getCodigo();
            $oDaoAvaliacaoResposta = new \cl_avaliacaoresposta();
            $oDaoAvaliacaoResposta->db106_avaliacaoperguntaopcao = $valorResposta->getOpcao()->getCodigo();
            $oDaoAvaliacaoResposta->db106_resposta               = $valorResposta->getValor();

            if (empty($iCodigoResposta)) {
                $oDaoAvaliacaoResposta->incluir(null);
                if ($oDaoAvaliacaoResposta->erro_status == 0) {
                    throw new \Exception("Erro ao salvar os dados da Resposta para a pergunta {$valorResposta->getPergunta()->getDescricao()}");
                }

                $valorResposta->setCodigo($oDaoAvaliacaoResposta->db106_sequencial);
                $oDaoAvaliacaoGrupoPerguntaResposta = new \cl_avaliacaogrupoperguntaresposta();
                $oDaoAvaliacaoGrupoPerguntaResposta->db108_avaliacaogruporesposta = $resposta->getCodigo();
                $oDaoAvaliacaoGrupoPerguntaResposta->db108_avaliacaoresposta = $oDaoAvaliacaoResposta->db106_sequencial;
                $oDaoAvaliacaoGrupoPerguntaResposta->incluir(null);

                if ($oDaoAvaliacaoGrupoPerguntaResposta->erro_status == 0) {
                    throw new \Exception("Erro ao salvar os dados da Resposta para a pergunta {$valorResposta->getPergunta()->getDescricao()}");
                }
            } else {
                $oDaoAvaliacaoResposta->db106_sequencial = $valorResposta->getCodigo();
                $oDaoAvaliacaoResposta->alterar($valorResposta->getCodigo());
                if ($oDaoAvaliacaoResposta->erro_status == 0) {
                    throw new \Exception("Erro ao salvar os dados da Resposta para a pergunta {$valorResposta->getPergunta()->getDescricao()}");
                }
            }
        }
    }

    /**
     * Constroi a instancia da resposta
     * @param                                                   $dados
     * @param \ECidade\Configuracao\Formulario\Model\Formulario $formulario
     * @return \ECidade\Configuracao\Formulario\Resposta\Model\Resposta
     */
    public static function make($dados, Formulario $formulario)
    {

        $resposta = new RespostaModel();
        $resposta->setCodigo($dados->db107_sequencial);
        $resposta->setData(new \DBDate($dados->db107_datalancamento));
        $resposta->setFormulario($formulario);
        return $resposta;
    }

    /**
     * Retorna todas as valores respondidos da resposta
     * @param \ECidade\Configuracao\Formulario\Resposta\Model\Resposta $resposta
     * @return \ECidade\Configuracao\Formulario\Resposta\Model\Valor[]
     * @throws \BusinessException
     */
    public static function getRespostasDaResposta(RespostaModel $resposta)
    {

        if ($resposta->getCodigo() == '') {
            return array();
        }
        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;
        $where                 = "db107_sequencial = {$resposta->getCodigo()}";

        $sSqlRespostas = $oDaoAvaliacaoResposta->sql_query_avaliacao(null, "avaliacaoresposta.*, avaliacaoperguntaopcao.*", "db102_sequencial,db103_ordem", $where);
        $rsRespostas   = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            throw new \BusinessException("Erro ao pesquisar valor das respostas.");
        }
        $respostas = \db_utils::makeCollectionFromRecord($rsRespostas, function ($dados) {

            $valorResposta = new Valor();
            $valorResposta->setCodigo($dados->db106_sequencial);
            $valorResposta->setPergunta(Pergunta::getBydId($dados->db104_avaliacaopergunta));
            $valorResposta->setValor($dados->db106_resposta);

            $opcao = new Opcao();
            $opcao->setCodigo($dados->db104_sequencial);
            $opcao->setIdentificadorCampo($dados->db104_identificadorcampo);
            $opcao->setDescricao($dados->db104_descricao);
            $valorResposta->setOpcao($opcao);
            return $valorResposta;
        });
        return $respostas;
    }

    public static function getBydId(Formulario $formulario, $codigoResposta)
    {

        if (empty($codigoResposta)) {
            return array();
        }
        $oDaoAvaliacaoResposta = new \cl_avaliacaogruporesposta();
        $where                 = "db107_sequencial = {$codigoResposta}";

        $sSqlRespostas = $oDaoAvaliacaoResposta->sql_query_file(null, "*", "", $where);
        $rsRespostas   = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            throw new \BusinessException("Erro ao pesquisar respostas de avaliacao.");
        }
        if (pg_num_rows($rsRespostas) == 0) {
            throw new \BusinessException("Não foi encontrado resposta para o código ($codigoResposta).");
        }
        return self::make(\db_utils::fieldsMemory($rsRespostas, 0), $formulario);
    }

    /**
     * @param \ECidade\Configuracao\Formulario\Resposta\Model\Resposta $resposta
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public static function remover(\ECidade\Configuracao\Formulario\Resposta\Model\Resposta $resposta)
    {

        if (!\db_utils::inTransaction()) {
            throw new \DBException('Sem transação com o banco de dados.');
        }
        if (empty($resposta)) {
            throw new \ParameterException("Resposta não informada.");
        }
        $oDaoAvaliacaoResposta              = new \cl_avaliacaoresposta();
        $oDaoAvaliacaoGrupoPerguntaResposta = new \cl_avaliacaogrupoperguntaresposta();
        $oDaoAvaliacaoGrupoResposta         = new \cl_avaliacaogruporesposta();
        foreach ($resposta->getRespostas() as $valorResposta) {
            $oDaoAvaliacaoGrupoPerguntaResposta->excluir(null, "db108_avaliacaoresposta = {$valorResposta->getCodigo()}");
            if ($oDaoAvaliacaoGrupoPerguntaResposta->erro_status == 0) {
                throw new \BusinessException("Erro ao excluir vinculo das respostas com o formulário.");
            }

            $oDaoAvaliacaoResposta->excluir($valorResposta->getCodigo());
            if ($oDaoAvaliacaoResposta->erro_status == 0) {
                throw new \BusinessException("Erro ao excluir respostas do formulário.");
            }
        }
        $oDaoAvaliacaoGrupoResposta->excluir($resposta->getCodigo());
        if ($oDaoAvaliacaoResposta->erro_status == 0) {
            throw new \BusinessException("Erro ao excluir respostas do formulário. Alguns valores de resposta não foram excluídos.");
        }
    }
}
