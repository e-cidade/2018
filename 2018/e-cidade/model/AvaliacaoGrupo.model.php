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

class AvaliacaoGrupo
{

    protected $iGrupo;

    protected $sDescricao;

    protected $aPerguntas = array();

    /**
     * Identificador do grupo
     * @var string
     */
    protected $sIdentificador;

    /**
     * Esse campo é uma string/hash para identificar o grupo no formulário.
     * O Identificador do campo deve ser único no formulário
     *
     * @var string
     */
    protected $sIdentificadorCampo;

    public function __construct($iGrupo = null)
    {
        if (empty($iGrupo)) {
            return;
        }

        $oDaoAvaliacaoGrupo = new cl_avaliacaogrupopergunta();
        $sSqlGrupos         = $oDaoAvaliacaoGrupo->sql_query_file($iGrupo);
        $rsGrupo            = db_query($sSqlGrupos);

        if (!$rsGrupo) {
            throw new DBException('Erro ao buscar grupo.');
        }

        if (pg_num_rows($rsGrupo) == 0) {
            throw new BusinessException('Grupo não encontrado.');
        }

        $this->iGrupo = $iGrupo;
        $oDadosGrupo = db_utils::fieldsMemory($rsGrupo, 0);
        $this->sDescricao = $oDadosGrupo->db102_descricao;
        $this->sIdentificador = $oDadosGrupo->db102_identificador;
        $this->sIdentificadorCampo = $oDadosGrupo->db102_identificadorcampo;
        unset($oDadosGrupo);
    }
    /**
     * @return integer
     * @deprecated
     * @see self::getCodigo()
     */
    public function getGrupo()
    {
        return $this->iGrupo;
    }

    /**
     * retorna o id
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iGrupo;
    }

    /**
     * @param integer $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        return $this->iGrupo = $iCodigo;
    }


    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }

    /**
     * @param integer $sDescricao
     */
    public function setDescricao($sDescricao)
    {

        $this->sDescricao = $sDescricao;
    }

    /**
     * Retorna o identificador do grupo
     * @return string
     */
    public function getIdentificador()
    {
        return $this->sIdentificador;
    }

    /**
     * Seta o identificador do grupo
     * @param string $sIdentificador
     * @return AvaliacaoPergunta[]
     */
    public function setIdentificador($sIdentificador)
    {
        $this->sIdentificador = $sIdentificador;
    }

    /**
     * Define um identificador para o campo
     *
     * @param string $string
     */
    public function setIdentificadorCampo($string)
    {
        $this->sIdentificadorCampo = $string;
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
     * Adiciona pergunta ao grupo
     * @param AvaliacaoPergunta $oPergunta
     */
    public function addPergunta(AvaliacaoPergunta $oPergunta)
    {
        $this->aPerguntas[] = $oPergunta;
    }

    public function getPerguntas()
    {
        if (count($this->aPerguntas) == 0 && !empty($this->iGrupo)) {
            $oDaoPergunta    = new cl_avaliacaopergunta();
            $sSqlPergunta    = $oDaoPergunta->sql_query_file(null, "*", "db103_ordem", "db103_avaliacaogrupopergunta={$this->iGrupo}");
            $rsPergunta      = $oDaoPergunta->sql_record($sSqlPergunta);
            if ($oDaoPergunta->numrows > 0) {
                $aPerguntas = db_utils::getCollectionByRecord($rsPergunta);
                foreach ($aPerguntas as $oPerguntaTemp) {
                    $oPergunta          = new AvaliacaoPergunta($oPerguntaTemp->db103_sequencial);
                    $this->aPerguntas[] = $oPergunta;
                }
            }
        }
        return $this->aPerguntas;
    }

    /**
     * Setta a avaliação da qual o grupo faz parte
     * @param Avaliacao $oAvaliacao
     */
    public function setAvaliacao(Avaliacao $oAvaliacao)
    {
        $this->oAvaliacao = $oAvaliacao;
    }

    public function salvar()
    {
        $identifier = new Identifier('avaliacaogrupopergunta', $this->iGrupo);

        if (empty($this->sIdentificador)) {
            $this->sIdentificador = $identifier->slugify($this->sDescricao);
        } elseif (!$identifier->validate($this->sIdentificador)) {
            throw new \DBException("Identificador do grupo de avaliação já cadastrado: '$this->sIdentificador'");
        }

        $oDao = new cl_avaliacaogrupopergunta();

        $oDao->db102_sequencial         = $this->iGrupo;
        $oDao->db102_avaliacao          = $this->oAvaliacao->getAvaliacao();
        $oDao->db102_descricao          = pg_escape_string($this->sDescricao);
        $oDao->db102_identificador      = $this->sIdentificador;
        $oDao->db102_identificadorcampo = $this->sIdentificadorCampo;

        if (empty($this->iGrupo)) {
            $oDao->incluir(null);
        } else {
            $oDao->alterar($this->iGrupo);
        }

        if ($oDao->erro_status == 0) {
            throw new Exception("Não foi possível salvar o grupo.");
        }
        $this->iGrupo = $oDao->db102_sequencial;

        foreach ($this->aPerguntas as $oPergunta) {
            $oPergunta->setGrupo($this->iGrupo);
            $oPergunta->salvar();
        }
    }

    /**
     * Exclui grupo e suas dependencias
     * @throws DBException
     * @return boolean
     */
    public function excluir()
    {
        if (empty($this->iGrupo)) {
            return false;
        }

        foreach ($this->getPerguntas() as $oPergunta) {
            $oPergunta->excluir();
        }

        $oDao = new cl_avaliacaogrupopergunta();
        $oDao->excluir($this->iGrupo);
        if ($oDao->erro_status == 0) {
            throw new DBException("Erro ao excluir grupo.");
        }

        return true;
    }
}
