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


class AvaliacaoPergunta {

  protected $iPergunta;

  protected $iTipo;

  protected $aOpcoes = array();

  protected $aResposta = array();

  protected $lAtivo;

  protected $iGrupo;

  protected $lObrigatorio;

  protected $sDescricao;

  protected $iAvaliacao;

  protected $sIdentificador;

  /**
   *
   */
  function __construct($iPergunta) {

    $this->iPergunta = $iPergunta;
    $oDaoPergunta    = db_utils::getDao("avaliacaopergunta");
    $sSqlPergunta    = $oDaoPergunta->sql_query_file($iPergunta);
    $rsPergunta      = $oDaoPergunta->sql_record($sSqlPergunta);
    if ($oDaoPergunta->numrows == 1) {

      $oDadosPergunta = db_utils::fieldsMemory($rsPergunta, 0);
      $this->setAtivo($oDadosPergunta->db103_ativo=='t'?true:false);
      $this->setObrigatoria($oDadosPergunta->db103_obrigatoria=='t'?true:false);
      $this->setDescricao($oDadosPergunta->db103_descricao);
      $this->setGrupo($oDadosPergunta->db103_avaliacaogrupopergunta);
      $this->setTipo($oDadosPergunta->db103_avaliacaotiporesposta);
      $this->setIdentificador($oDadosPergunta->db103_identificador);
    }
  }
  /**
   * @return unknown
   */
  public function getGrupo() {

    return $this->iGrupo;
  }
  /**
   * @param unknown_type $iGrupo
   */
  public function setGrupo($iGrupo) {

    $this->iGrupo = $iGrupo;
  }


  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iPergunta;
  }

  /**
   * @return unknown
   */
  public function getTipo() {

    return $this->iTipo;
  }

  /**
   * @param unknown_type $iTipo
   */
  public function setTipo($iTipo) {

    $this->iTipo = $iTipo;
  }

  /**
   * @return unknown
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * @param unknown_type $lAtivo
   */
  public function setAtivo($lAtivo) {

    $this->lAtivo = $lAtivo;
  }

  /**
   * @return boolean
   */
  public function isObrigatoria() {

    return $this->lObrigatorio;
  }

  /**
   * @param unknown_type $lObrigatorio
   */
  public function setObrigatoria($lObrigatorio) {

    $this->lObrigatorio = $lObrigatorio;
  }


  /**
   * @return unknown
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * @param unknown_type $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o identificador da pergunta
   * @return string
   */
  public function getIdentificador() {
    return $this->sIdentificador;
  }

  /**
   * Seta o identificador da pergunta
   * @param string $sIdentificador
   */
  public function setIdentificador($sIdentificador) {
    $this->sIdentificador = $sIdentificador;
  }

  public function getRespostas() {

    if (count($this->aOpcoes) == 0) {

      $oDaoRespostas = db_utils::getDao("avaliacaoperguntaopcao");
      $sSqlRepostas  = $oDaoRespostas->sql_query_file(null,"*", "db104_sequencial",
                                                          "db104_avaliacaopergunta = {$this->getCodigo()}"
                                                          );
      $rsRepostas   = $oDaoRespostas->sql_record($sSqlRepostas);
      if ($oDaoRespostas->numrows > 0) {

        for ($i = 0; $i < $oDaoRespostas->numrows; $i++) {

          $oRespostaTemp     = db_utils::fieldsMemory($rsRepostas, $i);
          $oResposta         = new stdClass();

          $oResposta->codigoresposta    = $oRespostaTemp->db104_sequencial;
          $oResposta->descricaoresposta = urlencode($oRespostaTemp->db104_descricao);
          $oResposta->aceitatexto       = $oRespostaTemp->db104_aceitatexto=='t'?true:false;
          $oResposta->texto             = $oRespostaTemp->db104_aceitatexto=='t'?true:false;
          $oResposta->marcada           = false;
          $oResposta->textoresposta     = '';
          $oResposta->identificador     = $oRespostaTemp->db104_identificador;
          $oResposta->peso              = $oRespostaTemp->db104_peso;
          /**
           * Verifica se a já existe alguma resposta para a avaliacao
           */
          $oDaoAvaliacaoResposta = db_utils::getDao("avaliacaogrupoperguntaresposta");
          if (!empty($this->iAvaliacao)) {

            $sSqlAvaliacao = $oDaoAvaliacaoResposta->sql_query_pergunta(null,
                                                       "avaliacaoresposta.*",
                                                       null,
                                                       "db106_avaliacaoperguntaopcao     = {$oRespostaTemp->db104_sequencial}
                                                        and db108_avaliacaogruporesposta = {$this->iAvaliacao}"
                                                      );
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
          $this->aOpcoes[]              = $oResposta;
        }
      }
    }
    return $this->aOpcoes;
  }

  public function setResposta($aResposta) {

    foreach ($this->aOpcoes as $oOpcao) {

      $oOpcao->marcada       = false;
      $oOpcao->textoresposta = '';

    }

    foreach ($aResposta as $oResposta) {
      foreach ($this->aOpcoes as $oOpcao) {

        if ($oOpcao->codigoresposta == $oResposta->codigoresposta) {

          $oOpcao->marcada       = true;
          $oOpcao->textoresposta = $oResposta->textoresposta;
        }
      }
    }
    $this->aResposta = $aResposta;

  }

  public function setAvaliacao($iAvaliacao) {
    $this->iAvaliacao = $iAvaliacao;
  }
  public function salvarRespostas() {

    $oDaoAvaliacaoResposta = db_utils::getDao("avaliacaoresposta");
    $oDaoAvaliacaoGrupo    = db_utils::getDao("avaliacaogrupoperguntaresposta");
    /**
     * consultamos todas as respostas salvas para a avaliaçãoS
     */
    $sSqlAvaliacaoSalva = $oDaoAvaliacaoGrupo->sql_query(null,"*", null,
                                                         "db108_avaliacaogruporesposta = {$this->iAvaliacao}
                                                         and db104_avaliacaopergunta   = {$this->getCodigo()}"
                                                         );
    $rsAvaliacaoSalva   = db_query($sSqlAvaliacaoSalva);
    for ($iResp = 0; $iResp < pg_num_rows($rsAvaliacaoSalva); $iResp++) {

      $oRespostaGrupo  = db_utils::fieldsMemory($rsAvaliacaoSalva, $iResp);
      $oDaoAvaliacaoGrupo->excluir(null   , "db108_sequencial = {$oRespostaGrupo->db108_sequencial}");
      $oDaoAvaliacaoResposta->excluir(null, "db106_sequencial = {$oRespostaGrupo->db106_sequencial}");
      if ($oDaoAvaliacaoResposta->erro_status == 0) {

       throw new Exception("2 = Erro ao Salvar Resposta da questão.\n{$oDaoAvaliacaoResposta->erro_msg}");
      }
    }

    foreach ($this->aResposta as $oResposta) {

      $oDaoAvaliacaoResposta->db106_avaliacaoperguntaopcao = $oResposta->codigoresposta;
      $oDaoAvaliacaoResposta->db106_resposta               = "".addslashes(utf8_decode(db_stdClass::db_stripTagsJson($oResposta->textoresposta)))."";
      $oDaoAvaliacaoResposta->incluir(null);
      if ($oDaoAvaliacaoResposta->erro_status == 0) {
        throw new Exception(" 1 Erro ao Salvar Resposta da questão.\n{$oDaoAvaliacaoResposta->erro_msg}");
      }

      /**
       * incluimos a ligacao das  respostas do exame com o grupo de repostas
       */
      $oDaoAvaliacaoGrupo->db108_avaliacaoresposta      = $oDaoAvaliacaoResposta->db106_sequencial;
      $oDaoAvaliacaoGrupo->db108_avaliacaogruporesposta = $this->iAvaliacao;
      $oDaoAvaliacaoGrupo->incluir(null);
    }
  }

  public function getRespostaAvaliacao() {

    if (count($this->aResposta) == 0) {

      $oDaoAvaliacaoGrupo = db_utils::getDao("avaliacaogrupoperguntaresposta");
      $sSqlAvaliacoes     = $oDaoAvaliacaoGrupo->sql_query_pergunta(null,
                                                           "*",
                                                           null,
                                                           "db103_sequencial = {$this->getCodigo()}"
                                                          );

      $rsAvaliacoes      = $oDaoAvaliacaoGrupo->sql_record($sSqlAvaliacoes);
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
  public function getVinculosComLayout() {

    $oDaoVinculos = db_utils::getDao("avaliacaoperguntaopcaolayoutcampo");
    $sCampos      = "db104_sequencial as codigoresposta, db104_aceitatexto as aceita_texto, ";
    $sCampos     .= "db52_nome as nome_campo, db103_avaliacaotiporesposta as tipo_resposta, ";
    $sCampos     .= "ed313_layoutvalorcampo as valor_resposta";
    $sWhere       = "db104_avaliacaopergunta = {$this->getCodigo()}";
    $sSqlVinculos = $oDaoVinculos->sql_query_avaliacao(null, $sCampos, null, $sWhere);
    $rsVinculos   = $oDaoVinculos->sql_record($sSqlVinculos);
    $aVinculos    = db_utils::getColectionByRecord($rsVinculos);
    return $aVinculos;
  }

  /**
   * Inclui as respostas da pergunra atraves de uma linha de um layout txt
   * @param DBLayouLinha $oLinhaLaoyout Linha do layout;
   */
  public function setRespostasPorLayout(DBLayoutLinha $oLinhaLayout) {

    $aVinculos   = $this->getVinculosComLayout();
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
  public static function getAvaliacacoesComResposta($iCodigo, array $aRespostasDissertativas = null) {

    $aAvaliacoes           = array();
    $sWhere                = "db106_avaliacaoperguntaopcao={$iCodigo}";
    if (!empty($aRespostasDissertativas) && count($aRespostasDissertativas) > 0) {

      $sRespostas = implode("','", $aRespostasDissertativas);
      $sRespostas = "'{$sRespostas}'";
      $sWhere    .= " and db106_resposta in({$sRespostas})";
    }
    $oDaoAvaliacaoResposta = db_utils::getDao("avaliacaogrupoperguntaresposta");
    $sSqlRespostas         = $oDaoAvaliacaoResposta->sql_query_avaliacao(null,
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
}
?>