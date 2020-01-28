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

namespace ECidade\Configuracao\Formulario\Repository;

use ECidade\Configuracao\Formulario\Model\Formulario as FormularioModel;
use ECidade\Configuracao\Formulario\Model\Opcao;
use ECidade\Configuracao\Formulario\Model\Pergunta as PerguntaModel;

/**
 * Repository para perguntas de um formulario
 * Class Pergunta
 * @package ECidade\Configuracao\Formulario\Repository
 */
class Pergunta
{

    /**
     * @var \ECidade\Configuracao\Formulario\Repository\Pergunta
     */
    private static $instance = null;

    /**
     * @var \ECidade\Configuracao\Formulario\Model\Pergunta[]
     */
    public $perguntas = array();

    protected function __construct()
    {
    }

    /**
     * @param $codigo
     * @return \ECidade\Configuracao\Formulario\Model\Pergunta
     * @throws \DBException
     */
    public static function getBydId($codigo)
    {
        if (!empty(self::getInstance()->perguntas[$codigo])) {
            return self::getInstance()->perguntas[$codigo];
        }

        $oDaoAvaliacaoPergunta = new \cl_avaliacaopergunta();
        $sWhere                = 'db103_sequencial = '.$codigo;
        $sSqlPerguntas         = $oDaoAvaliacaoPergunta->sql_query(null, "avaliacaopergunta.*", 'db103_ordem', $sWhere);
        $rsPerguntas           = db_query($sSqlPerguntas);
        if (!$rsPerguntas) {
            throw new \DBException('Erro ao pesquisar Perguntas da pergunta '.$codigo);
        }
        $iTotalLinhas = pg_num_rows($rsPerguntas);
        if ($iTotalLinhas == 0) {
            throw new \DBException('pergunta '.$codigo. 'não encontrada no sistema.');
        }
        $oPergunta = self::getInstance()->make(\db_utils::fieldsMemory($rsPerguntas, 0));
        self::getInstance()->perguntas[$codigo] = $oPergunta;
    }

    /**
     * @return \ECidade\Configuracao\Formulario\Repository\Pergunta
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     *Retorna todas as respostas do Formulário
    */
    public static function getPerguntasDoFormulario(FormularioModel $formulario)
    {
        $oDaoAvaliacaoPergunta = new \cl_avaliacaopergunta();
        $sWhere                = 'db102_avaliacao = '.$formulario->getCodigo();
        $sSqlPerguntas         = $oDaoAvaliacaoPergunta->sql_query(null, "avaliacaopergunta.*", 'db102_sequencial,db103_ordem', $sWhere);
        $rsPerguntas           = db_query($sSqlPerguntas);
        if (!$rsPerguntas) {
            throw new \DBException('Erro ao pesquisar Perguntas do formulario '.$formulario->getNome());
        }
        $iTotalLinhas = pg_num_rows($rsPerguntas);
        if ($iTotalLinhas == 0) {
            return array();
        }
        $instancia = self::getInstance();
        $perguntas = \db_utils::makeCollectionFromRecord($rsPerguntas, function ($dados) use ($instancia) {

            if (empty($instancia->perguntas[$dados->db103_sequencial])) {
                $instancia->perguntas[$dados->db103_sequencial] = $instancia->make($dados);
            }

            return $instancia->perguntas[$dados->db103_sequencial];
        });
        return $perguntas;
    }

    /**
     * @param $dados
     * @todo adicionar campos necessários para gerar o arquivo TXT
     * @return \ECidade\Configuracao\Formulario\Model\Pergunta
     */
    public function make($dados)
    {
        $oPergunta  = new PerguntaModel();
        $oPergunta->setCodigo($dados->db103_sequencial);
        $oPergunta->setAtivo($dados->db103_ativo);
        $oPergunta->setTipo($dados->db103_tipo);
        $oPergunta->setDescricao($dados->db103_descricao);
        $oPergunta->setCampoCarga($dados->db103_camposql);
        $oPergunta->setIdentificador($dados->db103_identificador);
        $oPergunta->setIdentificadorCampo($dados->db103_identificadorcampo);
        $oPergunta->setTipoResposta($dados->db103_avaliacaotiporesposta);
        $oPergunta->setObrigatoria($dados->db103_obrigatoria == 't');
        $oPergunta->setPerguntaIdentificadora($dados->db103_perguntaidentificadora == 't');
        return $oPergunta;
    }

    /**
     * Retorna todas as opcoes de resposta das Perguntas
     * @param \ECidade\Configuracao\Formulario\Model\Pergunta $pergunta
     * @return mixed
     * @throws \DBException
     */
    public static function getOpcoesDaPergunta(PerguntaModel $pergunta)
    {
        $oDaoPerguntaOpcao = new \cl_avaliacaoperguntaopcao();
        $where             = "db104_avaliacaopergunta = {$pergunta->getCodigo()}";
        $sSqlOpcoes        = $oDaoPerguntaOpcao->sql_query_file(null, "*", 'db104_sequencial', $where);
        $rsOpcoes          = db_query($sSqlOpcoes);
        if (!$rsOpcoes) {
            throw new \DBException('Erro ao pesquisar Opções de resposta da pergunta '.$pergunta->getDescricao());
        }
        $opcoes = \db_utils::makeCollectionFromRecord($rsOpcoes, function ($dados) {

            $opcao = new Opcao();
            $opcao->setDescricao($dados->db104_descricao);
            $opcao->setCodigo($dados->db104_sequencial);
            $opcao->setIdentificadorCampo($dados->db104_identificadorcampo);
            $opcao->setValorOpcao($dados->db104_valorresposta);
            return $opcao;
        });

        return $opcoes;
    }
}
