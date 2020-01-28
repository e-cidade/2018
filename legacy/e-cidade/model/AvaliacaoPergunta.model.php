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

use ECidade\Configuracao\Formulario\Identifier;

class AvaliacaoPergunta
{
    /**
     * Tipo de Resposta Objetiva
     */
    const TIPO_RESPOSTA_OBJETIVA = 1;

    /**
     * Tipo de Resposta dissertativa
     */
    const TIPO_RESPOSTA_DISSERTATIVA = 2;

    /**
     * Tipo de Resposta Multipla
     */
    const TIPO_RESPOSTA_MULTIPLA = 3;

    protected $iPergunta;

    /**
     * Tipo da resposta
     * @var integer
     */
    protected $iTipo;

    /**
     * Stdclass com as opções de respostas e informação se foi respondida
     * @var stdClass[]
     */
    protected $aOpcoesResposta = array();

    protected $aResposta = array();

    protected $lAtivo = true;

    protected $iGrupo;

    protected $lObrigatorio = false;

    protected $sDescricao;

    /**
     * Define o codigo a avalicao(resposta)
     * tabela: avaliacaogruporesposta
     * @var integer
     */
    protected $iAvaliacao;

    protected $sIdentificador;

    protected $sMascara;

    protected $iCodigoFormula;

    /**
     * Tipo de mascara aplicada ao campo
     * 1: Texto
     * 2: CEP
     * 3: CNPJ
     * 4: CPF
     * 5: Data
     * 6: Inteiro
     * 7: Telefone
     * 8: Valor
     * 9: Hora
     *
     * @var integer
     */
    private $iTipoComponente = 1;

    /**
     * Campo do sql(carga do formulario) usado para predefinir uma resposta
     * @var string
     */
    private $sCampoCarga;

    /**
     * Pergunta "pk" do formulario
     * campo db103_perguntaidentificadora
     *
     * @var boolean
     */
    private $lPerguntaIdentificadora = false;

    /**
     * Ordem para exibicao do grupo
     * @var integer
     */
    private $iOrdem;

    /**
     * @var string
     */
    private $sLayoutCampo;


    /**
     * Opções de resposta da pergunta ()
     * @var AvaliacaoPerguntaOpcao[]
     */
    private $aOpcoes = array();


    /**
     * Esse campo é uma string/hash para identificar a pergunta no formulário.
     * O Identificador do campo deve ser único no formulário
     *
     * @var string
     */
    protected $sIdentificadorCampo;

    /**
     * @param integer $iPergunta
     */
    public function __construct($iPergunta = null)
    {
        if (empty($iPergunta)) {
            return;
        }

        $oDaoPergunta = new cl_avaliacaopergunta;
        $sSqlPergunta = $oDaoPergunta->sql_query($iPergunta);
        $rsPergunta   = db_query($sSqlPergunta);

        if (!$rsPergunta) {
            throw new DBException("Erro ao buscar dados de pergunta na avaliação.");
        }

        if (pg_num_rows($rsPergunta) == 0) {
            throw new DBException("Pergunta não encontrada.");
        }

        $oDadosPergunta = db_utils::fieldsMemory($rsPergunta, 0);
        $this->iPergunta = $iPergunta;

        $this->setAtivo($oDadosPergunta->db103_ativo=='t');
        $this->setObrigatoria($oDadosPergunta->db103_obrigatoria=='t');
        $this->setDescricao($oDadosPergunta->db103_descricao);
        $this->setGrupo($oDadosPergunta->db103_avaliacaogrupopergunta);
        $this->setTipo($oDadosPergunta->db103_avaliacaotiporesposta);
        $this->setIdentificador($oDadosPergunta->db103_identificador);
        $this->setCodigoFormula(!!$oDadosPergunta->eso01_db_formulas ? $oDadosPergunta->eso01_db_formulas : null);
        $this->setTipoComponente($oDadosPergunta->db103_tipo);
        $this->setMascara($oDadosPergunta->db103_mascara);
        $this->setCampoCarga($oDadosPergunta->db103_camposql);
        $this->setPerguntaIdentificadora($oDadosPergunta->db103_perguntaidentificadora == 't');
        $this->setOrdem($oDadosPergunta->db103_ordem);
        $this->setLayoutCampo($oDadosPergunta->db103_dblayoutcampo);
        $this->setIdentificadorCampo($oDadosPergunta->db103_identificadorcampo);
    }

    /**
     * @return integer
     */
    public function getGrupo()
    {
        return $this->iGrupo;
    }

    /**
     * @param integer $iGrupo
     */
    public function setGrupo($iGrupo)
    {
        $this->iGrupo = $iGrupo;
    }

    /**
     * Define o código (id da pergunta)
     * @param integer $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iPergunta = $iCodigo;
    }

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iPergunta;
    }

    /**
     * @return integer
     */
    public function getTipo()
    {
        return $this->iTipo;
    }

    /**
     * @param integer $iTipo
     */
    public function setTipo($iTipo)
    {
        $this->iTipo = $iTipo;
    }

    /**
     * @return boolean
     */
    public function isAtivo()
    {
        return $this->lAtivo;
    }

    /**
     * @param boolean $lAtivo
     */
    public function setAtivo($lAtivo)
    {
        $this->lAtivo = $lAtivo;
    }

    /**
     * @return boolean
     */
    public function isObrigatoria()
    {
        return $this->lObrigatorio;
    }

    /**
     * @param boolean $lObrigatorio
     */
    public function setObrigatoria($lObrigatorio)
    {
        $this->lObrigatorio = $lObrigatorio;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }

    /**
     * @param string $sDescricao
     */
    public function setDescricao($sDescricao)
    {
        $this->sDescricao = $sDescricao;
    }

    /**
     * Retorna o identificador da pergunta
     * @return string
     */
    public function getIdentificador()
    {
        return $this->sIdentificador;
    }

    /**
     * Seta o identificador da pergunta
     * @param string $sIdentificador
     */
    public function setIdentificador($sIdentificador)
    {
        $this->sIdentificador = $sIdentificador;
    }

    /**
     * @return string
     */
    public function getLayoutCampo()
    {
        return $this->sLayoutCampo;
    }

    /**
     * @param string $sLayoutCampo
     */
    public function setLayoutCampo($sLayoutCampo)
    {
        $this->sLayoutCampo = $sLayoutCampo;
    }

    public function getRespostas()
    {
        if (count($this->aOpcoesResposta) == 0) {
            $oDaoRespostas = db_utils::getDao("avaliacaoperguntaopcao");
            $sSqlRepostas = $oDaoRespostas->sql_query_file(null, "*", "db104_sequencial", "db104_avaliacaopergunta = {$this->getCodigo()}");
            $rsRepostas = $oDaoRespostas->sql_record($sSqlRepostas);
            if ($oDaoRespostas->numrows > 0) {
                for ($i = 0; $i < $oDaoRespostas->numrows; $i++) {
                    $oRespostaTemp     = db_utils::fieldsMemory($rsRepostas, $i);
                    $oResposta         = new stdClass();

                    $oResposta->codigoresposta     = $oRespostaTemp->db104_sequencial;
                    $oResposta->descricaoresposta  = $oRespostaTemp->db104_descricao;
                    $oResposta->aceitatexto        = $oRespostaTemp->db104_aceitatexto=='t'?true:false;
                    $oResposta->texto              = $oRespostaTemp->db104_aceitatexto=='t'?true:false;
                    $oResposta->marcada            = false;
                    $oResposta->textoresposta      = '';
                    $oResposta->identificador      = $oRespostaTemp->db104_identificador;
                    $oResposta->peso               = $oRespostaTemp->db104_peso;
                    $oResposta->identificadorcampo = $oRespostaTemp->db104_identificadorcampo;

                    /**
                     * Verifica se a já existe alguma resposta para a avaliacao
                     */
                    $oDaoAvaliacaoResposta = db_utils::getDao("avaliacaogrupoperguntaresposta");
                    if (!empty($this->iAvaliacao)) {
                        $sWhere = "db106_avaliacaoperguntaopcao = {$oRespostaTemp->db104_sequencial} ";
                        $sWhere .= " and db108_avaliacaogruporesposta = {$this->iAvaliacao} ";
                        $sSqlAvaliacao = $oDaoAvaliacaoResposta->sql_query_pergunta(null, "avaliacaoresposta.*", null, $sWhere);
                        $rsRespostaAvaliacao = $oDaoAvaliacaoResposta->sql_record($sSqlAvaliacao);
                        if ($oDaoAvaliacaoResposta->numrows > 0) {
                            $oRespostaAvaliacao       = db_utils::fieldsMemory($rsRespostaAvaliacao, 0);
                            $oResposta->marcada       = true;
                            $oResposta->textoresposta = $oRespostaAvaliacao->db106_resposta;
                            $oRespostaUsuario = new stdClass();
                            $oRespostaUsuario->codigoresposta = $oRespostaAvaliacao->db106_avaliacaoperguntaopcao;
                            $oRespostaUsuario->textoresposta  = $oRespostaAvaliacao->db106_resposta;
                            $this->aResposta[] = $oRespostaUsuario;
                            unset($oRespostaAvaliacao);
                        }
                    }
                      $this->aOpcoesResposta[] = $oResposta;
                }
            }
        }
        return $this->aOpcoesResposta;
    }

    public function setResposta($aResposta)
    {
        foreach ($this->aOpcoesResposta as $oOpcao) {
            $oOpcao->marcada       = false;
            $oOpcao->textoresposta = '';
        }

        foreach ($aResposta as $oResposta) {
            foreach ($this->aOpcoesResposta as $oOpcao) {
                if ($oOpcao->codigoresposta == $oResposta->codigoresposta) {
                    $oOpcao->marcada       = true;
                    $oOpcao->textoresposta = $oResposta->textoresposta;
                }
            }
        }

        $this->aResposta = $aResposta;
    }

    /**
     * Define o codigo do preenchimento(resposta)
     * tabela: avaliacaogruporesposta
     * @param integer $iAvaliacao
     * @deprecated
     * @see self::setPreenchimento
     */
    public function setAvaliacao($iAvaliacao)
    {
        $this->setPreenchimento($iAvaliacao);
    }

    /**
     * Código do preenchimento do formulário
     *
     * @param integer $preenchimento
     */
    public function setPreenchimento($preenchimento)
    {
        $this->iAvaliacao = $preenchimento;
    }


    public function setCodigoFormula($iCodigoFormula)
    {

        $this->iCodigoFormula = $iCodigoFormula;
        return $this;
    }

    public function getCodigoFormula()
    {
        return $this->iCodigoFormula;
    }

    public function setMascara($sMascara)
    {
        $this->sMascara = $sMascara;
    }

    public function getMascara()
    {
        return $this->sMascara;
    }

    public function setTipoComponente($iTipoComponente)
    {
        $this->iTipoComponente = $iTipoComponente;
    }

    public function getTipoComponente()
    {
        return $this->iTipoComponente;
    }

    /**
     * Codigo da avaliacao(resposta)
     * @return integer
     */
    public function getAvaliacao()
    {
        return $this->iAvaliacao;
    }

    /**
     * Setter campo carga
     * @param string
     */
    public function setCampoCarga($sCampo)
    {
        $this->sCampoCarga = $sCampo;
    }

    /**
     * Getter campo carga
     * @param string
     */
    public function getCampoCarga()
    {
        return $this->sCampoCarga;
    }

    /**
     * Setter pergunta identificadora
     * @param boolean
     */
    public function setPerguntaIdentificadora($lPerguntaIdentificadora)
    {
        $this->lPerguntaIdentificadora = $lPerguntaIdentificadora;
    }

    /**
     * Getter pergunta identificadora
     * @param boolean
     */
    public function getPerguntaIdentificadora()
    {
        return $this->lPerguntaIdentificadora;
    }

    /**
     * Setter ordem
     * @param integer
     */
    public function setOrdem($iOrdem)
    {
        $this->iOrdem = $iOrdem;
    }

    /**
     * Getter ordem
     * @param integer
     */
    public function getOrdem()
    {
        return $this->iOrdem;
    }

    /**
     * Adiciona opcao de resposta a pergunta
     * @param AvaliacaoPerguntaOpcao $oOpcao
     */
    public function addOpcao(AvaliacaoPerguntaOpcao $oOpcao)
    {
        $this->aOpcoes[] = $oOpcao;
    }

    /**
     * Define um identificador para o campo
     *
     * @param string $sIdentificadorCampo
     */
    public function setIdentificadorCampo($sIdentificadorCampo)
    {
        $this->sIdentificadorCampo = $sIdentificadorCampo;
    }

    /**
     * Retorna o identificador do campo
     *
     * @return string
     */
    public function getIdentificadorCampo()
    {
        return $this->sIdentificadorCampo;
    }

    /**
     * Opcoes de resposta da pergunta
     * @return AvaliacaoPerguntaOpcao[]
     */
    public function getOpcoes()
    {

        if (empty($this->aOpcoes) && !empty($this->iPergunta)) {
            $oDao = new cl_avaliacaoperguntaopcao();
            $sWhere = 'db104_avaliacaopergunta = ' . $this->iPergunta;
            $sSql = $oDao->sql_query_file(null, 'db104_sequencial as id', 'db104_sequencial asc', $sWhere);

            $rs = db_query($sSql);

            if (!$rs) {
                throw new DBException("Erro ao buscar opções.");
            }

            if (pg_num_rows($rs) > 0) {
                $this->aOpcoes = db_utils::makeCollectionFromRecord($rs, function ($oDado) {
                    return new AvaliacaoPerguntaOpcao($oDado->id);
                });
            }
        }

        return $this->aOpcoes;
    }

    public function salvarRespostas()
    {
        $oDaoAvaliacaoResposta = db_utils::getDao("avaliacaoresposta");
        $oDaoAvaliacaoGrupo    = db_utils::getDao("avaliacaogrupoperguntaresposta");

        /*
         * consultamos todas as respostas salvas para a avaliação
         */
        $sSqlAvaliacaoSalva = $oDaoAvaliacaoGrupo->sql_query(
            null,
            "*",
            null,
            "db108_avaliacaogruporesposta = {$this->iAvaliacao} and db104_avaliacaopergunta   = {$this->getCodigo()}"
        );
        $rsAvaliacaoSalva   = db_query($sSqlAvaliacaoSalva);
        for ($iResp = 0; $iResp < pg_num_rows($rsAvaliacaoSalva); $iResp++) {
            $oRespostaGrupo  = db_utils::fieldsMemory($rsAvaliacaoSalva, $iResp);
            $oDaoAvaliacaoGrupo->excluir(null, "db108_sequencial = {$oRespostaGrupo->db108_sequencial}");
            $oDaoAvaliacaoResposta->excluir(null, "db106_sequencial = {$oRespostaGrupo->db106_sequencial}");
            if ($oDaoAvaliacaoResposta->erro_status == 0) {
                throw new Exception("2 = Erro ao Salvar ResposAta da questão.\n{$oDaoAvaliacaoResposta->erro_msg}");
            }
        }

        foreach ($this->aResposta as $oResposta) {
            $oDaoAvaliacaoResposta->db106_avaliacaoperguntaopcao = $oResposta->codigoresposta;
            $oDaoAvaliacaoResposta->db106_resposta               = "".addslashes(utf8_decode(db_stdClass::db_stripTagsJson($oResposta->textoresposta)))."";
            $oDaoAvaliacaoResposta->incluir(null);

            if ($oDaoAvaliacaoResposta->erro_status == 0) {
                throw new Exception(" 1 Erro ao Salvar Resposta da questão.\n{$oDaoAvaliacaoResposta->erro_msg}");
            }

            /*
             * incluimos a ligacao das  respostas do exame com o grupo de repostas
             */
            $oDaoAvaliacaoGrupo->db108_avaliacaoresposta      = $oDaoAvaliacaoResposta->db106_sequencial;
            $oDaoAvaliacaoGrupo->db108_avaliacaogruporesposta = $this->iAvaliacao;
            $oDaoAvaliacaoGrupo->incluir(null);
        }
    }

    public function getRespostaAvaliacao()
    {
        if (count($this->aResposta) == 0) {
            $oDaoAvaliacaoGrupo = db_utils::getDao("avaliacaogrupoperguntaresposta");
            $sSqlAvaliacoes = $oDaoAvaliacaoGrupo->sql_query_pergunta(null, "*", null, "db103_sequencial = {$this->getCodigo()}");

            $rsAvaliacoes = $oDaoAvaliacaoGrupo->sql_record($sSqlAvaliacoes);
            if ($oDaoAvaliacaoGrupo->numrows > 0) {
                for ($iResp = 0; $iResp < $oDaoAvaliacaoGrupo->numrows; $iResp++) {
                    $oRespostaTemp = db_utils::fieldsMemory($rsAvaliacoes, $iResp);
                    $oResposta     = new stdClass();
                    $oResposta->codigoresposta = $oRespostaTemp->db106_sequencial;
                    $oResposta->texto          = $oRespostaTemp->db106_resposta;
                    $this->aResposta[]         = $oResposta;
                }
            }
        }
        return $this->aResposta;
    }

    /**
     * Retorna um array com o vinculo das respostas com os campos de layout
     * @return array com stdclass
     */
    public function getVinculosComLayout($iAno)
    {
        $oDaoVinculos = db_utils::getDao("avaliacaoperguntaopcaolayoutcampo");
        $sCampos      = "db104_sequencial as codigoresposta, db104_aceitatexto as aceita_texto, ";
        $sCampos     .= "db52_nome as nome_campo, db103_avaliacaotiporesposta as tipo_resposta, ";
        $sCampos     .= "ed313_layoutvalorcampo as valor_resposta";
        $sWhere       = "db104_avaliacaopergunta = {$this->getCodigo()}";

        if (!empty($iAno)) {
            $sWhere .= "and ed313_ano = {$iAno}";
        }

        $sSqlVinculos = $oDaoVinculos->sql_query_avaliacao(null, $sCampos, null, $sWhere);
        $rsVinculos   = $oDaoVinculos->sql_record($sSqlVinculos);
        $aVinculos    = db_utils::getCollectionByRecord($rsVinculos);
        return $aVinculos;
    }

    /**
     * Inclui as respostas da pergunra atraves de uma linha de um layout txt
     * @param DBLayouLinha $oLinhaLaoyout Linha do layout;
     */
    public function setRespostasPorLayout(DBLayoutLinha $oLinhaLayout, $iAno = null)
    {
        $aVinculos   = $this->getVinculosComLayout($iAno);
        $aRespostas  = array();

        foreach ($aVinculos as $oVinculo) {
            $sTextoResposta            = '';
            $sValorCampo               = $oLinhaLayout->{$oVinculo->nome_campo};
            if ($oVinculo->aceita_texto == 't') {
                $sTextoResposta = str_replace("'", " ", $sValorCampo);
            }
            $lMarcarResposta = false;
            $oResposta                 = new stdClass();
            $oResposta->textoresposta  = $sTextoResposta;
            $oResposta->codigoresposta = $oVinculo->codigoresposta;
            if ($oVinculo->tipo_resposta == 1 &&  $oVinculo->valor_resposta == $sValorCampo) {
                $lMarcarResposta = true;
            }
            if ($oVinculo->tipo_resposta == 3 &&
             ($oVinculo->valor_resposta == $sValorCampo || $oVinculo->aceita_texto == 't')) {
                $lMarcarResposta = true;
            }
            if ($oVinculo->tipo_resposta == 2) {
                $lMarcarResposta = true;
            }
            if ($lMarcarResposta) {
                $aRespostas[] = $oResposta;
            }
        }
        unset($aVinculos);
        $this->setResposta($aRespostas);
    }

    /**
     * Retorna todas as Avaliacoes que possuem a resposta marcada
     * @param integer $iCodigo codigo da Avaliacao
     * @return Array com as avaliacoes que possuem a resposta
     */
    public static function getAvaliacacoesComResposta($iCodigo, array $aRespostasDissertativas = null)
    {
        $aAvaliacoes           = array();
        $sWhere                = "db106_avaliacaoperguntaopcao={$iCodigo}";
        if (!empty($aRespostasDissertativas) && count($aRespostasDissertativas) > 0) {
            $sRespostas = implode("','", $aRespostasDissertativas);
            $sRespostas = "'{$sRespostas}'";
            $sWhere    .= " and db106_resposta in({$sRespostas})";
        }
        $oDaoAvaliacaoResposta = db_utils::getDao("avaliacaogrupoperguntaresposta");
        $sSqlRespostas = $oDaoAvaliacaoResposta->sql_query_avaliacao(
            null,
            "db108_avaliacaogruporesposta,
            db101_sequencial",
            null,
            $sWhere
        );
        $rsAvaliacoes = $oDaoAvaliacaoResposta->sql_record($sSqlRespostas);
        if ($oDaoAvaliacaoResposta->numrows > 0) {
            for ($i = 0; $i <  $oDaoAvaliacaoResposta->numrows; $i++) {
                $oAvaliacao            = db_utils::fieldsMemory($rsAvaliacoes, $i);
                $iCodigoAvaliacao      = $oAvaliacao->db101_sequencial;
                $iCodigoGrupoRespostas = $oAvaliacao->db108_avaliacaogruporesposta;
                $oAvaliacao            = new Avaliacao($iCodigoAvaliacao);
                $oAvaliacao->setAvaliacaoGrupo($iCodigoGrupoRespostas);
                $aAvaliacoes[]        = $oAvaliacao;
            }
        }
        return $aAvaliacoes;
    }

    /**
     * @throws \DBException
     * @return string|null
     */
    public function getFormulaVinculada()
    {
        if (empty($this->iPergunta)) {
            return null;
        }
        $oDaoPergunta = new cl_avaliacaoperguntadb_formulas();
        $sSqlFormula  = $oDaoPergunta->sql_query(null, "db148_nome", null, "eso01_avaliacaopergunta = {$this->iPergunta}");
        $rsFormula    = db_query($sSqlFormula);
        if (!$rsFormula) {
            throw new DBException("Erro ao pesquisar formula para sugestao da pergunta.");
        }
        $iTotalLinhas = pg_num_rows($rsFormula);
        if ($iTotalLinhas == 0) {
            return null;
        }

        $oFormula = db_utils::fieldsMemory($rsFormula, 0);
        return $oFormula->db148_nome;
    }

    public function salvar()
    {
        $identifier = new Identifier('avaliacaopergunta', $this->iPergunta);

        if (empty($this->sIdentificador)) {
            $this->sIdentificador = $identifier->slugify($this->sDescricao);
        } elseif (!$identifier->validate($this->sIdentificador)) {
            throw new \DBException("Identificador da pergunta já cadastrado: '$this->sIdentificador'");
        }

        $oDao = new cl_avaliacaopergunta();
        $oDao->db103_sequencial             = $this->iPergunta;
        $oDao->db103_avaliacaotiporesposta  = $this->iTipo;
        $oDao->db103_avaliacaogrupopergunta = $this->iGrupo;
        $oDao->db103_descricao              = pg_escape_string($this->sDescricao) ;
        $oDao->db103_obrigatoria            = $this->lObrigatorio ? "true" : "false";
        $oDao->db103_ativo                  = $this->lAtivo ? "true" : "false";
        $oDao->db103_ordem                  = $this->iOrdem;
        $oDao->db103_identificador          = $this->sIdentificador;
        $oDao->db103_tipo                   = $this->iTipoComponente;
        $oDao->db103_mascara                = $this->sMascara;
        $oDao->db103_dblayoutcampo          = $this->sLayoutCampo;
        $oDao->db103_perguntaidentificadora = $this->lPerguntaIdentificadora ? "true" : "false";
        $oDao->db103_camposql               = $this->sCampoCarga;
        $oDao->db103_identificadorcampo     = $this->sIdentificadorCampo;

        if (empty($this->iPergunta)) {
            $oDao->incluir(null);

            if ($this->iTipo == 2 && empty($this->aOpcoes)) {
                $option = new \AvaliacaoPerguntaOpcao();
                $option->setAceitaTexto(true);
                $this->addOpcao($option);
            }
        } else {
            $oDao->alterar($this->iPergunta);
        }

        if ($oDao->erro_status == 0) {
            $logger = \ECidade\V3\Extension\Registry::get('app.container')->get('app.logger');
            $logger->debug("Pergunta: " . $this->sDescricao);
            $logger->debug($oDao->erro_msg);
            throw new DBException("Erro ao salvar perguntas.");
        }

        $this->iPergunta = $oDao->db103_sequencial;

        $iFormula = $this->iCodigoFormula;
        $this->removerFormula(); // remove a formula vinculada a pergunta atual
        if (!empty($iFormula)) {
            $this->salvarFormula($iFormula);
        }

        foreach ($this->aOpcoes as $oOpcao) {
            $oOpcao->setPergunta($this);
            $oOpcao->salvar();
        }
    }

    /**
     * Exclui pergunta e suas dependencias
     * @throws DBException
     * @return boolean
     */
    public function excluir()
    {

        if (empty($this->iPergunta)) {
            return false;
        }

        foreach ($this->getOpcoes() as $oOpcao) {
            $oOpcao->excluir();
        }

        $oDao = new cl_avaliacaopergunta();
        $oDao->excluir($this->iPergunta);
        if ($oDao->erro_status == 0) {
            throw new DBException("Erro ao excluir pergunta.");
        }

        return true;
    }

    /**
     * Remove a fórumla vinculada
     */
    private function removerFormula()
    {
        $oDao = new \cl_avaliacaoperguntadb_formulas;
        $oDao->excluir(null, "eso01_avaliacaopergunta = $this->iPergunta");

        if ($oDao->erro_status == 0) {
            throw new DBException("Erro ao remover fórmula da pergunta.");
        }
        $this->iCodigoFormula = null;
    }

    /**
     * Cria um vínculo da pergunta com a formula
     *
     * @param integer $iFormula
     */
    private function salvarFormula($iFormula)
    {
        $oDao = new \cl_avaliacaoperguntadb_formulas;
        $oDao->eso01_db_formulas = $iFormula;
        $oDao->eso01_avaliacaopergunta = $this->iPergunta;
        $oDao->incluir(null);

        if ($oDao->erro_status == 0) {
            $logger = \ECidade\V3\Extension\Registry::get('app.container')->get('app.logger');
            $logger->debug("Pergunta: " . $this->sDescricao);
            $logger->debug($oDao->erro_msg);
            throw new DBException("Erro ao vincular fórmula.");
        }

        $this->iCodigoFormula = $iFormula;
    }
}
