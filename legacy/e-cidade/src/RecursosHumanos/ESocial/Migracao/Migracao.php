<?php

namespace ECidade\RecursosHumanos\ESocial\Migracao;

use ECidade\Configuracao\Formulario\Repository\Formulario as FormularioRepository;
use ECidade\Configuracao\Formulario\Repository\Pergunta as PerguntaRepository;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta as RespostaRepository;
use ProgressBar;
use stdClass;

/**
 * Classe base para efetuar a migra��o dos formul�rios
 *
 * @package ECidade\RecursosHumanos\ESocial\Migracao
 * @author  Andrio Costa - andrio.costa@dbseller.com.br
 * @author  Igor Cemim - igor.cemim@dbseller.com.br
 */
abstract class Migracao
{
    /**
     * Dados do fomul�rio atual
     * {
     *    formulario : id
     *    tipo :
     *    versao :
     * }
     *
     * @var stdClass
     */
    protected $formularioAtual;

    /**
     * Dados do fomul�rio novo
     * {
     *    formulario : id
     *    tipo :
     *    versao :
     * }
     *
     * @var stdClass
     */
    protected $formularioNovo;

    /**
     * Perguntas do formul�rio antigo
     *
     * @var \ECidade\Configuracao\Formulario\Model\Pergunta[]
     */
    protected $perguntasAtual;

    /**
     * Cont�m os c�digos das op��es de respostas do fomul�rio antigo indexado pelo identificador campo
     *
     * @var array
     */
    protected $opcoesAtual = array();

    /**
     * Cont�m os c�digos das op��es de respostas do novo fomul�rio indexado pelo identificador campo
     *
     * @var array
     */
    protected $opcoesNovas = array();

    /**
     * C�digo do usu�rio
     *
     * @var integer
     */
    protected $usuario;

    /**
     * Nome do formul�rio
     *
     * @var string
     */
    protected $nomeFormulario;

    /**
     * Barra de progresso
     *
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * Informa o c�digo do usu�rio
     *
     * @param integer $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Informa o formulario usado na vers�o atual
     *
     * @param \stdClass $formulario
     */
    public function formularioAtual(\stdClass $formulario)
    {
        $this->formularioAtual = $formulario;
        $this->perguntasAtual = $this->mapearPerguntas($formulario->formulario);
        $this->opcoesAtual = $this->mapearOpcoesRespostaPorIdentificador($formulario->formulario);
    }

    /**
     * Informa o formulario que ser� usado na vers�o nova vers�o
     *
     * @param \stdClass $formulario
     */
    public function formularioNovo(\stdClass $formulario)
    {
        $this->formularioNovo = $formulario;
        $this->opcoesNovas = $this->mapearOpcoesRespostaPorIdentificador($formulario->formulario);
    }

    /**
     * Retorna as perguntas do formul�rio
     *
     * @param integer $codigoFormulario
     * @return \ECidade\Configuracao\Formulario\Model\Pergunta[]
     */
    private function mapearPerguntas($codigoFormulario)
    {
        $formulario = FormularioRepository::getById($codigoFormulario);
        return PerguntaRepository::getPerguntasDoFormulario($formulario);
    }
    /**
     * Mapeia as op��es de resposta de um formul�rio por identicador do campo
     *
     * @param int $codigoFormulario
     * @return array
     */
    private function mapearOpcoesRespostaPorIdentificador($codigoFormulario)
    {
        $formulario = FormularioRepository::getById($codigoFormulario);
        $perguntas = PerguntaRepository::getPerguntasDoFormulario($formulario);
        $opcoes = array();

        foreach ($perguntas as $pergunta) {
            foreach ($pergunta->getOpcoes() as $opcao) {
                if (in_array($opcao->getIdentificadorCampo(), $opcoes)) {
                    throw new \Exception('Identificador de campo repetido no formul�rio.');
                }
                $opcoes[$opcao->getIdentificadorCampo()] = $opcao->getCodigo();
            }
        }

        return $opcoes;
    }

    /**
     * Busca o c�digo da op��o de resposta da nova vers�o utilizando o identificador do campo
     * Se n�o existir retorna null
     *
     * @param string $identificador
     * @return integer|null
     */
    protected function deAtualParaNovo($identificador)
    {
        return isset($this->opcoesNovas[$identificador]) ? $this->opcoesNovas[$identificador] : null;
    }

    /**
     * Migra as respostas da avalia��o atual para a vers�o selecionada desde que encontra um mesmo identificador
     *
     * @param integer $novoPreenchimento
     * @param array   $respostas
     */
    protected function migrarRespostas($novoPreenchimento, array $respostas)
    {
        foreach ($respostas as $dadosResposta) {
            $novoCodigoOpcao = $this->deAtualParaNovo($dadosResposta->identificadorcampo);
            // se op��o n�o foi encontrada na nova vers�o, discarta a resposta
            if (empty($novoCodigoOpcao)) {
                continue;
            }

            // inst�ncia da pergunta � necess�ria em AvaliacaoResposta
            $pergunta = new \AvaliacaoPergunta();
            $pergunta->setPreenchimento($novoPreenchimento);

            $resposta = new \AvaliacaoResposta();
            $resposta->setPergunta($pergunta);
            $resposta->setPerguntaOpcao($novoCodigoOpcao);
            $resposta->setResposta($dadosResposta->textoresposta);
            \AvaliacaoRespostaRepository::persist($resposta);
        }
    }

    /**
     * Busca as op��es de respostas (e seu valor) respondidas em um preenchimento
     *
     * @param integer $idPreenchimento
     * @return \stdClass[]
     */
    protected function buscarRespostasPreenchimento($idPreenchimento)
    {
        $respostas = array();
        foreach ($this->perguntasAtual as $pergunta) {
            $oPergunta = new \AvaliacaoPergunta($pergunta->getCodigo());
            $oPergunta->setPreenchimento($idPreenchimento);
            $todasRespostas = $oPergunta->getRespostas();

            // percorre todas respostas e indexa somente as respondidas
            foreach ($todasRespostas as $resposta) {
                if ($resposta->marcada) {
                    $respostas[] = $resposta;
                }
            }
        }
        return $respostas;
    }

    /**
     * Cria um novo preenchimento
     *
     * @param \stdClass $preenchimento
     * @throws \Exception
     * @return integer
     */
    protected function criarNovoPreenchimento($preenchimento)
    {
        $daoPreenchimento = new \cl_avaliacaogruporesposta;
        $daoPreenchimento->db107_sequencial = null;
        $daoPreenchimento->db107_usuario = $this->usuario;
        $daoPreenchimento->db107_datalancamento = date('Y-m-d');
        $daoPreenchimento->db107_hora = date('H:i');
        $daoPreenchimento->incluir(null);

        if ($daoPreenchimento->erro_status == 0) {
            throw new \Exception("Erro ao incluir novo preenchimento do formul�rio." . $daoPreenchimento->erro_sql);
        }

        return $daoPreenchimento->db107_sequencial;
    }

    public function setProgressBar(ProgressBar $progresBar)
    {
        $this->progressBar = $progresBar;
    }

    abstract public function buscarUltimoPreenchimento($codigoFormulario);

    /**
     * Processa a migra��o do formul�rio atual para o formul�rio da nova vers�o
     */
    public function processar()
    {
        $this->progressBar->flush();
        $this->progressBar->setMessageLog("Buscando informa��es do Formul�rio: " . $this->nomeFormulario);
        $preenchimentosServidores = $this->buscarUltimoPreenchimento($this->formularioAtual->formulario);

        $this->progressBar->setMessageLog("Migrando as respostas do formul�rio: " . $this->nomeFormulario);
        $this->progressBar->updateMaxProgress(count($preenchimentosServidores));
        foreach ($preenchimentosServidores as $indice => $preenchimento) {
            $respostas = $this->buscarRespostasPreenchimento($preenchimento->preenchimento);
            $novoPreenchimento = $this->criarNovoPreenchimento($preenchimento);
            $this->migrarRespostas($novoPreenchimento, $respostas);
            $this->progressBar->updatePercentual($indice + 1);
        }
    }
}
