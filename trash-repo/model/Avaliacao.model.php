<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

  protected $iAvaliacaoGrupo;

  function __construct($iAvaliacao = null) {

    if (!empty($iAvaliacao)) {

      $oDaoAvaliacao = db_utils::getDao("avaliacao");
      $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file($iAvaliacao);
      $rsAvaliacao   = $oDaoAvaliacao->sql_record($sSqlAvaliacao);
      if ($oDaoAvaliacao->numrows == 1) {

        $oDadosAvaliacao = db_utils::fieldsMemory($rsAvaliacao, 0);
        $this->setAvaliacao($iAvaliacao)
             ->setDescricao($oDadosAvaliacao->db101_descricao)
             ->setObservacao($oDadosAvaliacao->db101_obs)
             ->setIdentificador($oDadosAvaliacao->db101_identificador);
      }
    }
  }

  /**
   * @return integer
   */
  public function getAvaliacao() {

    return $this->iAvaliacao;
  }

  /**
   * @param intger $iAvaliacao
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

  function getGruposPerguntas() {

    if (count($this->aGrupos) == 0) {

      $oDaoGrupoPerguntas = db_utils::getDao("avaliacaogrupopergunta");
      $sSqlGrupos         = $oDaoGrupoPerguntas->sql_query_file(null,
                                                                "*",
                                                                "db102_sequencial",
                                                                "db102_avaliacao = {$this->iAvaliacao}"
                                                               );
      $rsGrupos  = $oDaoGrupoPerguntas->sql_record($sSqlGrupos);
      if ($oDaoGrupoPerguntas->numrows > 0) {

        $aGrupos = db_utils::getColectionByRecord($rsGrupos);
        foreach ($aGrupos as $oGrupo) {
          $this->aGrupos[] = new AvaliacaoGrupo($oGrupo->db102_sequencial);
        }
      }
    }
    return $this->aGrupos;
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

        $sErrorMessage  = "Erro ao incluir nova avaliaзгo para usuбrio.\n";
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
   * OBS.: Sу retorna o valor de perguntas onde:
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

}
?>