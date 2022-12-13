<?php

class AvaliacaoESocial
{
  
    /**
     * Instancia da Avaliacao
     * @var Avaliacao
     */
    private $oAvaliaco;

    /**
     * Instancia do servidor
     * @var Servidor
     */
    private $oServidor;

    /**
     * Instancia do cgm
     * @var \CgmBase
     */
    private $oCgm;

    private $oPerguntasRespostas;

    public function __construct()
    {
    }

    public function setAvaliacao($oAvaliacao)
    {
        $this->oAvaliacao = $oAvaliacao;
    }

    public function getAvaliacao()
    {
        return $this->oAvaliacao;
    }

    /**
     * Define do servidor da Avaliacao
     * @param \Servidor $oServidor
     */
    public function setServidor(Servidor $oServidor)
    {
        $this->oServidor = $oServidor;
    }

    /**
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->oServidor;
    }

    public function setPerguntasRespostas($oPerguntasRespostas)
    {
        $this->oPerguntasRespostas = $oPerguntasRespostas;
    }

    public function gerPerguntasRespostas()
    {
        return $this->oPerguntasRespostas;
    }

    public function salvar($iCodigoGrupoPerguntas = null, $sTipoFormulario = null)
    {
        if (empty($this->oPerguntasRespostas)) {
            throw new ParameterException("Não foram enviadas respostas para salvar.");
        }

        /**
         * Verifica se foi informado um grupo de perguntas a salvar,
         * se não houver irá trazer todas as perguntas
         */
        if (!empty($iCodigoGrupoPerguntas)) {
            $iCodigoGrupoPerguntas = $iCodigoGrupoPerguntas;
        }
        
        /**
         * Obtém as perguntas da avaliação
         */
        $aPerguntasAvaliacao = $this->oAvaliacao->getPerguntas($iCodigoGrupoPerguntas);

        /**
         * Percorre os grupoas enviados para montar array com as perguntas
         * e respostas que será utilizado para salvar as repostas logo abaixo
         */
        $aPerguntasRespondidas = array();

        if (is_object($this->oPerguntasRespostas->grupos)) {
            $this->oPerguntasRespostas->grupos = array($this->oPerguntasRespostas->grupos);
        }

        foreach ($this->oPerguntasRespostas->grupos as $iGrupo => $oGrupo) {
            foreach ($oGrupo->perguntas as $oPergunta) {
                $aPerguntasRespondidas[$oPergunta->codigo] = $oPergunta->respostas;
            }
        }

        /**
         * Percorre as perguntas da avaliação para salvar as respostas
         */
        foreach ($aPerguntasAvaliacao as $oPergunta) {
            $oAvaliacaoResposta = new AvaliacaoResposta();
            $oAvaliacaoResposta->setPergunta($oPergunta);
            AvaliacaoRespostaRepository::delete($oAvaliacaoResposta);
          
            $oPergunta->getRespostas();

            if (isset($aPerguntasRespondidas[$oPergunta->getCodigo()])) {
                $aRespostasSalvar = array(); // Array com as respostas que devem ser salvas

                foreach ($aPerguntasRespondidas[$oPergunta->getCodigo()] as $oRespostaSalvar) {
                    if (in_array((int)$oPergunta->getTipo(), array(1, 3))) { // Se for pergunta do tipo objetiva ou multipla escolha
                        if ((bool)$oRespostaSalvar->valor === false) { // Salva apenas se resposta estiver marcada
                            continue;
                        }
                    }
                    if ($oPergunta->getTipoComponente() == 5 && !empty($oRespostaSalvar->valor)) {
                        $datetime = new DateTime($oRespostaSalvar->valor);
                        $oRespostaSalvar->valor = $datetime->format("Y-m-d");
                    }
                    /**
                     * Popula array com objetos de resposta para salvar
                     * caso a pergunta seja dos tipo 1 ou 3 que são objetivas
                     * ou múltipla escolha então nao salva o valor apenas vincula a reposta
                     * salva o valor auxiliar quando existir
                     */
                    $sTextoResposta = (!empty($oRespostaSalvar->valorAuxiliar)) ? $oRespostaSalvar->valorAuxiliar : ((in_array((int)$oPergunta->getTipo(), array(1, 3))) ? '' : $oRespostaSalvar->valor );
                    $oAvaliacaoResposta->setPerguntaOpcao($oRespostaSalvar->codigo);
                    $oAvaliacaoResposta->setResposta($sTextoResposta);
                    AvaliacaoRespostaRepository::persist($oAvaliacaoResposta);
                }
            }
        }

        if (!empty($sTipoFormulario)) {
            switch ($sTipoFormulario) {
                case 'lotacaoTributaria':
                    $this->persitirDadosLotacao();
                    break;
                default:
                    $this->persitirDadosServidor();
                    $this->persitirDadosCgm();
                    break;
            }
        } else {
            $this->persitirDadosServidor();
            $this->persitirDadosCgm();
        }
    }

    /**
     * Persiste os dados da avaliacao do servidor
     * @throws \DBException
     */
    private function persitirDadosServidor()
    {
        if (empty($this->oServidor)) {
            return ;
        }
        /**
         * Vincula as matriculas as repostas
         */
        $oDaoAvaliacaoGrupoRespostaMatricula                                = new cl_avaliacaogruporespostarhpessoal;
        $oDaoAvaliacaoGrupoRespostaMatricula->eso02_avaliacaogruporesposta  = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso02_rhpessoal               = $this->getServidor()->getMatricula();
        $oDaoAvaliacaoGrupoRespostaMatricula->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaMatricula->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n".$oDaoAvaliacaoGrupoRespostaMatricula->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     * Persiste os ddos da Avaliacao do Cgm
     * @throws \DBException
     */
    private function persitirDadosCgm()
    {
        if (empty($this->oCgm)) {
            return ;
        }
        $oDaoAvaliacaoGrupoRespostaCgm = new cl_avaliacaogruporespostacgm();
        $oDaoAvaliacaoGrupoRespostaCgm->eso03_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaCgm->eso03_cgm                    = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaCgm->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaCgm->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n".$oDaoAvaliacaoGrupoRespostaCgm->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     *
     * @return \CgmBase
     */
    public function getCgm()
    {
        return $this->oCgm;
    }

    public function setCgm(\CgmBase $oCgm)
    {
        $this->oCgm = $oCgm;
    }

    /**
     * Persiste os ddos da Avaliacao da Lotacao
     * @throws \DBException
     */
    private function persitirDadosLotacao()
    {
        if (empty($this->oCgm)) {
            return ;
        }
        $oDaoAvaliacaoGrupoRespostaLotacao = new cl_avaliacaogruporespostalotacao();
        $oDaoAvaliacaoGrupoRespostaLotacao->eso04_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaLotacao->eso04_cgm                    = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaLotacao->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaLotacao->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n".$oDaoAvaliacaoGrupoRespostaLotacao->erro_sql . PHP_EOL . pg_last_error());
        }
    }
}
