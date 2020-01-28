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

/**
 * Classe para controle de avaliacoes
 * @package Configuracao
 */

class Avaliacao {

  /**
   * Codigo da Avaliacao
   *
   * @var integer
   */
  protected $iAvaliacao ;

  /**
   * descricao da avaliacao
   *
   * @var string
   */
  protected $sDescricao;

  /**
   * Grupos de perguntas da avaliacao
   *
   * @var Array
   */
  protected $aGrupos = array();

  /**
   * Observacoes da avaliacao
   *
   * @var string
   */
  protected $sObservacao;

  /**
   * Identificador da avaliacao
   * @var string
   */
  protected $sIdentificador;

  /**
   * Tipo de avaliação (avaliacaotipo)
   * @var integer
   */
  protected $iTipo;

  /**
   * @var boolean
   */
  protected $lAtivo = true;

  /**
   * Sql para carga de dados
   * @var string
   */
  protected $sSqlCargaDados;

  /**
   * @var boolean
   */
  protected $lPermiteEdicao = true;

  /**
   * Esse cara não é o código do grupo da avaliação. Isso é o código de uma resposta de "alguem"
   *
   * @var integer
   */
  protected $iAvaliacaoGrupo;

  function __construct($iAvaliacao = null) {

    if (empty($iAvaliacao)) {
      return;
    }

    $oDaoAvaliacao = new cl_avaliacao;
    $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file($iAvaliacao);
    $rsAvaliacao   = db_query($sSqlAvaliacao);

    if (!$rsAvaliacao) {
      throw new DBException('Erro ao buscar avaliação.');
    }

    if (pg_num_rows($rsAvaliacao) == 0) {
      throw new BusinessException('Avaliação não encontrada.');
    }

    $oDadosAvaliacao = db_utils::fieldsMemory($rsAvaliacao, 0);
    $this->setAvaliacao($iAvaliacao)
         ->setDescricao($oDadosAvaliacao->db101_descricao)
         ->setObservacao($oDadosAvaliacao->db101_obs)
         ->setIdentificador($oDadosAvaliacao->db101_identificador)
         ->setPermiteEdicao($oDadosAvaliacao->db101_permiteedicao == 't')
         ->setSqlCargaDados($oDadosAvaliacao->db101_cargadados)
         ->setAtivo($oDadosAvaliacao->db101_ativo == 't')
         ->setTipoAvaliacao($oDadosAvaliacao->db101_avaliacaotipo);

  }

  /**
   * @return integer
   * @deprecated
   * @see self::getCodigo()
   */
  public function getAvaliacao() {
    return $this->iAvaliacao;
  }

  /**
   * retorna o id
   * @return integer
   */
  public function getCodigo() {
    return $this->iAvaliacao;
  }

  /**
   * @param integer $iCodigo
   * @return Avaliacao
   */
  public function setCodigo($iCodigo) {
    $this->iAvaliacao = $iCodigo;
    return $this;
  }


  /**
   * @param intger $iAvaliacao
   * @deprecated
   * @see sell::setCodigo()
   * @return Avaliacao
   */
  public function setAvaliacao($iAvaliacao) {

    $this->iAvaliacao = $iAvaliacao;
    return $this;
  }

  /**
   *
   * @return string descricao do item
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   * @return Avaliacao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
    return $this;
  }

  /**
   * @return string
   */
  public function getObservacao() {

    return $this->sObservacao;
  }

  /**
   * @param string $sObservacao
   * @return Avaliacao
   */
  public function setObservacao($sObservacao) {

    $this->sObservacao = $sObservacao;
    return $this;
  }

  /**
   * Retorna o identificador da avaliacao
   * @return string
   */
  public function getIdentificador() {
    return $this->sIdentificador;
  }

  /**
   * Seta um identificador para a avaliacao
   * @param string $sIdentificador
   */
  public function setIdentificador($sIdentificador) {

    $this->sIdentificador = $sIdentificador;
    return $this;
  }

  public function getGruposPerguntas() {

    if (count($this->aGrupos) == 0 && !empty($this->iAvaliacao)) {

      $oDaoGrupoPerguntas = new cl_avaliacaogrupopergunta;
      $sSqlGrupos         = $oDaoGrupoPerguntas->sql_query_file(null,
                                                                "*",
                                                                "db102_sequencial",
                                                                "db102_avaliacao = {$this->iAvaliacao}"
                                                               );
      $rsGrupos  = $oDaoGrupoPerguntas->sql_record($sSqlGrupos);
      if ($oDaoGrupoPerguntas->numrows > 0) {

        $aGrupos = db_utils::getCollectionByRecord($rsGrupos);
        foreach ($aGrupos as $oGrupo) {

          $oGrupo = new AvaliacaoGrupo($oGrupo->db102_sequencial);
          $oGrupo->setAvaliacao($this);
          $this->aGrupos[] = $oGrupo;

        }
      }
    }
    return $this->aGrupos;
  }

  public function getGrupos() {
    return $this->getGruposPerguntas();
  }

  function getPerguntas($iGrupo = null) {

    $aPerguntas = array();
    $aPerguntasDoGrupo = $this->getGruposPerguntas();
    foreach ($this->getGruposPerguntas() as $oGrupo) {

      $aPerguntasGrupo = $oGrupo->getPerguntas();
      if (!empty($iGrupo)) {

        if ($iGrupo == $oGrupo->getGrupo()) {

          foreach ($oGrupo->getPerguntas() as $oPergunta) {

            $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
            $aPerguntas[] = $oPergunta;
          }
          break;
        }
      } else {

        foreach ($oGrupo->getPerguntas() as $oPergunta) {

          $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
          $aPerguntas[] = $oPergunta;
        }
      }
    }
    return $aPerguntas;

  }

  /**
   * Enter description here...
   *
   * @param integer $iAValiacao codigo da avaliacao o usuario
   * @return Avaliacao
   */
  public function setAvaliacaoGrupo($iAValiacao = null) {

    $oDaoAvaliacaoGrupo = db_utils::getDao("avaliacaogruporesposta");
    if (empty($iAValiacao)) {

      $oDaoAvaliacaoGrupo->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoAvaliacaoGrupo->db107_hora           = db_hora();
      $oDaoAvaliacaoGrupo->db107_usuario        = db_getsession("DB_id_usuario");
      $oDaoAvaliacaoGrupo->incluir(null);
      if ($oDaoAvaliacaoGrupo->erro_status == 0) {

        $sErrorMessage  = "Erro ao incluir nova avaliação para usuário.\n";
        $sErrorMessage .= "\n{$oDaoAvaliacaoGrupo->erro_msg}";
      }
      $this->iAvaliacaoGrupo = $oDaoAvaliacaoGrupo->db107_sequencial;
    } else {
      $this->iAvaliacaoGrupo = $iAValiacao;
    }
    return $this;
  }

  public function getAvaliacaoGrupo() {
    return $this->iAvaliacaoGrupo;
  }

  /**
   * Retorna as Respostas da Pergunta Informada
   */
  public function getRespostasDaPerguntaPoCodigo($iCodigoPergunta) {

    $aRespostas = array();
    if ($this->iAvaliacaoGrupo != "") {

      $oPergunta = new AvaliacaoPergunta($iCodigoPergunta);
      $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
      $aRespostas = $oPergunta->getRespostas();
    }
    return $aRespostas;
  }

  /**
   * Retorna as Respostas da Pergunta Informada
   * @param $sIdentificador String identificadora da pergunta db103_identificador
   */
  public function getRespostasDaPerguntaPorIdentificador($sIdentificador) {

    $aRespostas            = array();
    $oDaoAvaliacaoPergunta = new cl_avaliacaopergunta;
    $sSqlPergunta          = $oDaoAvaliacaoPergunta->sql_query_file(null,
                                                                    "db103_sequencial",
                                                                    null,
                                                                    "db103_identificador='{$sIdentificador}'"
                                                                    );
    $rsPergunta = $oDaoAvaliacaoPergunta->sql_record($sSqlPergunta);
    if ($oDaoAvaliacaoPergunta->numrows > 0 && $this->iAvaliacaoGrupo != "") {

      $oPergunta = new AvaliacaoPergunta(db_utils::fieldsMemory($rsPergunta, 0)->db103_sequencial);
      $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
      $aRespostasPergunta = $oPergunta->getRespostas();
      foreach ($aRespostasPergunta as $oResposta) {
        if ($oResposta->marcada == true) {
          $aRespostas[] = $oResposta;
        }
      }
    }
    return $aRespostas;
  }

  /**
   * Verifica se uma resposta de uma pergunta esta marcada (selecioda, respondida)
   * @param string $sIdentificadorPergunta - Identificador unico da pergunta
   * @param integer $iCodigoOpcaoResposta - Codigo da opcao de resposta
   * @return boolean
   */
  public function verificaSeRespostaEstaMarcada($sIdentificadorPergunta, $iCodigoOpcaoResposta) {

    /**
     * Buscamos as respostas da pergunta pelo identificador
     */
    if (is_int($sIdentificadorPergunta)) {
      $aRespostas = $this->getRespostasDaPerguntaPoCodigo($sIdentificadorPergunta);
    } else {
      $aRespostas = $this->getRespostasDaPerguntaPorIdentificador($sIdentificadorPergunta);
    }
    $iRespostas = count($aRespostas);

    if ($iRespostas == 0) {
      return false;
    }

    /**
     * Iteramos sobre as respostas da pergunta procurando a opcao identificada pelo parametro {$iCodigoOpcaoResposta}
     */
    foreach ($aRespostas as $oResposta) {

      if ($oResposta->codigoresposta == $iCodigoOpcaoResposta && $oResposta->marcada == 1) {
        return true;
      }
    }

    return false;
  }

  /**
   * Retorna o valor da Resposta de uma pergunta
   * OBS.: Só retorna o valor de perguntas onde:
   *       -> A pergunta for do tipo dissertativa
   *       -> A pergunta ter opcao aceita texto SIM
   *
   * @param string $sIdentificadorPergunta - Identificador unico da pergunta
   * @param integer $iCodigoOpcaoResposta - Codigo da opcao de resposta
   * @return string | null texto da resposta
   */
  public function retornaValorRespostaMarcada($sIdentificadorPergunta, $iCodigoOpcaoResposta) {

    /**
     * Buscamos as respostas da pergunta pelo identificador
     */
    $aRespostas = $this->getRespostasDaPerguntaPorIdentificador($sIdentificadorPergunta);
    $iRespostas = count($aRespostas);

    if ($iRespostas == 0) {
      return null;
    }

    /**
     * Iteramos sobre as respostas da pergunta procurando a opcao identificada pelo parametro {$iCodigoOpcaoResposta}
     */
    foreach ($aRespostas as $oResposta) {

      if ($oResposta->texto == 1 || $oResposta->aceitatexto == 1) {

        if ($oResposta->codigoresposta == $iCodigoOpcaoResposta && !empty($oResposta->textoresposta)) {
          return $oResposta->textoresposta;
        }
      }
    }
    return null;
  }

  /**
   * Setter permite edicao
   * @param boolean
   */
  public function setPermiteEdicao ($lPermiteEdicao) {
    $this->lPermiteEdicao = $lPermiteEdicao;
    return $this;
  }

  /**
   * Getter permite edicao
   * @param boolean
   */
  public function getPermiteEdicao () {
    return $this->lPermiteEdicao;
  }

  /**
   * Setter carga de dados
   * @param string
   */
  public function setSqlCargaDados ($sSqlCargaDados) {
    $this->sSqlCargaDados = $sSqlCargaDados;
    return $this;
  }

  /**
   * Getter carga de dados
   * @param string
   */
  public function getSqlCargaDados () {
    return $this->sSqlCargaDados;
  }


  /**
   * Setter ativo
   * @param boolean
   */
  public function setAtivo ($lAtivo) {
    $this->lAtivo = $lAtivo;
    return $this;
  }

  /**
   * Getter ativo
   * @param boolean
   */
  public function isAtivo () {
    return $this->lAtivo;
  }

  /**
   * Setter tipo
   * @param integer
   */
  public function setTipoAvaliacao ($iTipo) {
    $this->iTipo = $iTipo;
    return $this;
  }

  /**
   * Getter tipo
   * @param integer
   */
  public function getTipoAvaliacao () {
    return $this->iTipo;
  }

  /**
   * Adiciona um grupo
   * @param AvaliacaoGrupo $oGrupo
   */
  public function addGrupo(AvaliacaoGrupo $oGrupo ) {

    $this->aGrupos[] = $oGrupo;
    return $this;
  }

  public function salvar()  {

    $identifier = new Identifier('avaliacao', $this->iAvaliacao);

    if (empty($this->sIdentificador)) {
      $this->sIdentificador = $identifier->slugify($this->sDescricao);
    } else if ( !$identifier->validate($this->sIdentificador) ) {
      throw new \DBException("Identificador da avaliação já cadastrado: '$this->sIdentificador'");
    }

    $oDao                      = new cl_avaliacao;
    $oDao->db101_sequencial    = $this->iAvaliacao;
    $oDao->db101_avaliacaotipo = $this->iTipo;
    $oDao->db101_descricao     = pg_escape_string($this->sDescricao);
    $oDao->db101_obs           = pg_escape_string($this->sObservacao);
    $oDao->db101_ativo         = $this->lAtivo ? "true" : "false";
    $oDao->db101_identificador = $this->sIdentificador;
    $oDao->db101_cargadados    = pg_escape_string($this->sSqlCargaDados);
    $oDao->db101_permiteedicao = $this->lPermiteEdicao ? "true" : "false";

    if ( empty($this->iAvaliacao) ) {
      $oDao->incluir(null);
    } else {
      $oDao->alterar($this->iAvaliacao);
    }

    if ($oDao->erro_status == 0 ) {
      throw new \Exception("Não foi possível salvar a avaliação.");
    }

    $this->iAvaliacao = $oDao->db101_sequencial;

    foreach ($this->aGrupos as $oGrupo) {

      $oGrupo->setAvaliacao($this);
      $oGrupo->salvar();
    }

    return true;
  }

  /**
   * Exclui avaliacao e suas dependencias
   * @throws DBException
   * @return boolean
   */
  public function excluir() {

    if (empty($this->iAvaliacao)) {
      return false;
    }

    foreach ($this->getGrupos() as $oGrupo) {
      $oGrupo->excluir();
    }

    $oDao = new cl_avaliacao();
    $oDao->excluir($this->iAvaliacao);
    if ($oDao->erro_status == 0) {
      throw new DBException("Erro ao excluir avaliação.");
    }

    return true;
  }

}
