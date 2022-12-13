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
 * @author Andrio Costa     <andrio.costa@gmail.com>
 * @author Jeferson Belmiro <jeferson.belmiro@gmail.com>
 */
class AvaliacaoPerguntaOpcao {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * Instancia da pergunta
   * @var AvaliacaoPergunta
   */
  private $oPergunta;

  /**
   * Código da pergunta
   * @var integer
   */
  private $iPergunta;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @var boolean
   */
  private $lAceitaTexto = false;

  /**
   * @var string
   */
  private $sIdentificador;

  /**
   * @var integer
   */
  private $iPeso;

  /**
   * Valor da resposta
   * @exemple 01 - Casa
   *          01   -> é o valor da resposta
   *          Casa -> descrição da opção
   *
   * @var string
   */
  private $sValorResposta;

  /**
   * Esse campo deve identificar a opção da resposta no formulário. DEVE SER ÚNICO NO FORMULÁRIO
   * Usado para identificar as respostas dos formulários do e-Social
   *
   * Recomendações:
   * - Não usar espaço;
   * - Não usar caracteres especiais;
   * - Pode usar letras e números;
   *
   * @var string
   */
  private $sIdentificadorCampo;

  /**
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return;
    }

    $oDao = new cl_avaliacaoperguntaopcao();
    $sSql = $oDao->sql_query_file($iCodigo);
    $rs   = db_query($sSql);

    if (!$rs) {
      throw new DBException("Erro ao buscar o opção da pergunta.");
    }

    if (pg_num_rows($rs) == 0) {
      throw new BusinessException("Não foi possível localizar a opção.");
    }

    $oDados = db_utils::fieldsMemory($rs, 0);

    $this->iCodigo = $iCodigo;
    $this->iPergunta = $oDados->db104_avaliacaopergunta;
    $this->sDescricao = $oDados->db104_descricao;
    $this->lAceitaTexto = $oDados->db104_aceitatexto == 't';
    $this->sIdentificador = $oDados->db104_identificador;
    $this->iPeso = $oDados->db104_peso;
    $this->sValorResposta = $oDados->db104_valorresposta;
    $this->sIdentificadorCampo = $oDados->db104_identificadorcampo;
  }


  /**
   * Setter codigo
   * @param integer
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Getter codigo
   * @param integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }


  /**
  * Setter pergunta
  * @param AvaliacaoPergunta
  */
  public function setPergunta(AvaliacaoPergunta $oPergunta) {

    $this->oPergunta = $oPergunta;
    $this->iPergunta = $oPergunta->getCodigo();
  }

  /**
  * Getter pergunta
  * @param AvaliacaoPergunta
  */
  public function getPergunta() {

    if (is_null($this->oPergunta) && !empty($this->iPergunta)) {
      $this->oPergunta = new AvaliacaoPergunta($this->iPergunta);
    }
    return $this->oPergunta;
  }

  /**
  * Setter descrição
  * @param string
  */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
  * Getter descrição
  * @param string
  */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
  * Define se aceita texto para complementar a resposta
  * @param type
  */
  public function setAceitaTexto($lAceitaTexto) {
    $this->lAceitaTexto = $lAceitaTexto;
  }

  /**
  * aceita texto para complementar a resposta
  * @param type
  */
  public function getAceitaTexto() {
    return $this->lAceitaTexto;
  }

  /**
  * Setter identificador
  * @param string
  */
  public function setIdentificador($sIdentificador) {
    $this->sIdentificador = $sIdentificador;
  }

  /**
  * Getter identificador
  * @param string
  */
  public function getIdentificador() {
    return $this->sIdentificador;
  }

  /**
  * Setter peso
  * @param integer
  */
  public function setPeso($iPeso) {
    $this->iPeso = $iPeso;
  }

  /**
  * Getter peso
  * @param integer
  */
  public function getPeso() {
    return $this->iPeso;
  }

  /**
   * Setter valor resposta
   * @param string
   */
  public function setValorResposta($sValorResposta) {
    $this->sValorResposta = $sValorResposta;
  }

  /**
   * Getter valor resposta
   * @param string
   */
  public function getValorResposta() {
    return $this->sValorResposta;
  }

  /**
   * Setter identificador do campo
   * @param string
   */
  public function setIdentificadorCampo($string) {
    $this->sIdentificadorCampo = $string;
  }

  /**
   * Getter identificador do campo
   * @param string
   */
  public function getIdentificadorCampo() {
    return $this->sIdentificadorCampo;
  }


  /**
   * Salva
   * @throws DBException
   * @return boolean
   */
  public function salvar() {

    $identifier = new Identifier('avaliacaoperguntaopcao', $this->iCodigo);

    if ( empty($this->sIdentificador) ) {
      $this->sIdentificador = $identifier->slugify($this->sDescricao);
    } else if ( !$identifier->validate($this->sIdentificador) ) {
      throw new \DBException("Identificador da opção de resposta já cadastrado: '$this->sIdentificador'");
    }

    $oDao = new cl_avaliacaoperguntaopcao();
    $oDao->db104_sequencial = $this->iCodigo;
    $oDao->db104_avaliacaopergunta = $this->iPergunta;
    $oDao->db104_descricao = $this->sDescricao;
    $oDao->db104_aceitatexto = $this->lAceitaTexto ? 'true' : 'false';
    $oDao->db104_identificador = $this->sIdentificador;
    $oDao->db104_peso = $this->iPeso;
    $oDao->db104_valorresposta = $this->sValorResposta;
    $oDao->db104_identificadorcampo = $this->sIdentificadorCampo;

    if (empty($this->iCodigo)) {
      $oDao->incluir(null);
    } else {
      $oDao->alterar($this->iCodigo);
    }

    if ($oDao->erro_status == 0) {
      throw new DBException("Erro ao salvar opção de pergunta.");
    }

    $this->iCodigo = $oDao->db104_sequencial;
    return true;
  }

  /**
   * Excluir opcao de avaliacao e suas respostas
   * @throws DBException
   * @return boolean
   */
  public function excluir() {

    if (empty($this->iCodigo)) {
      return false;
    }

    // Busca as respostas vinculadas a opcao
    $oDaoAvaliacaoResposta = new cl_avaliacaoresposta();
    $sWhere = "db106_avaliacaoperguntaopcao = {$this->iCodigo}";
    $sSql = $oDaoAvaliacaoResposta->sql_query_file(null, "db106_sequencial as id_resposta", null, $sWhere);
    $rsRespostas = db_query($sSql);

    if (!$rsRespostas) {
      throw new DBException("Erro ao buscar respostas.");
    }

    // cria um array com o id de todas respostas da opção
    $aIdRespostas = db_utils::makeCollectionFromRecord($rsRespostas, function($oData){
      return $oData->id_resposta;
    });

    // Percorre todas respostas buscando o vinculo de quem respondeu e verifica se tem que remove-lo
    foreach ($aIdRespostas as $iIdResposta) {

      $sWhereGrupoRespostas = " db108_avaliacaoresposta = {$iIdResposta} ";
      $oDaoAvaliacaoGrupoPerguntaResposta = new cl_avaliacaogrupoperguntaresposta();

      // buscando o cabeçalho de quem respondeu
      $sSqlGrupoRespostas = $oDaoAvaliacaoGrupoPerguntaResposta->sql_query_file(
        null,
        "db108_avaliacaogruporesposta",
        null,
        $sWhereGrupoRespostas
      );

      $rsGrupoRespostas = db_query($sSqlGrupoRespostas);
      if ( !$rsGrupoRespostas ) {
        throw new DBException("Erro ao buscar grupo de respostas.");
      }

      $aGrupoRespostas = db_utils::makeCollectionFromRecord($rsGrupoRespostas, function($oData){
        return $oData->db108_avaliacaogruporesposta;
      });

      $sCampos = "count(*)";
      $sGroup  = " group by db108_avaliacaogruporesposta";
      foreach ($aGrupoRespostas as $iIdAvaliacaoGrupoResposta) {

        // conta os vínculos do cabeçalho para verificar se é o ultmo e deve remove-lo
        $sWhere = "db108_avaliacaogruporesposta = {$iIdAvaliacaoGrupoResposta}";
        $sSql = $oDaoAvaliacaoGrupoPerguntaResposta->sql_query_file(null, $sCampos, null, $sWhere );
        $rs = db_query($sSql);

        if (!$rs) {
          throw new DBException("Erro ao buscar vínculos das respostas.");
        }

        //Exclui a resposta da avaliação (Cabeçalho de Quem respondeu)
        db_utils::makeCollectionFromRecord($rs, function($oData) use ($iIdAvaliacaoGrupoResposta){

          // se retornar mais de um, tem mais respostas vinculadas
          if ( $oData->count != 1 ) {
            return;
          }

          $aVinculos = array(
            'avaliacaogrupoperguntaresposta' => 'db108_avaliacaogruporesposta',
            'avaliacaogruporespostacgm' => 'eso03_avaliacaogruporesposta',
            'cidadaoavaliacao' => 'as01_avaliacaogruporesposta',
            'cidadaofamiliaavaliacao' => 'as06_avaliacaogruporesposta',
            'escoladadoscenso' => 'ed308_avaliacaogruporesposta',
            'avaliacaogruporespostarhpessoal' => 'eso02_avaliacaogruporesposta',
            'habitfichasocioeconomica' => 'ht12_avaliacaogruporesposta',
            'rechumanodadoscenso' => 'ed309_avaliacaogruporesposta',
          );

          foreach ($aVinculos as $table => $key) {

            $className = "cl_$table";
            $oDaoVinculo = new $className;
            $oDaoVinculo->excluir(null, "$key = $iIdAvaliacaoGrupoResposta");
            if ($oDaoVinculo->erro_status == 0) {
              throw DBException("Erro ao excluir vinculo das respostas.");
            }
          }

          // remove o cabecalho das respostas
          $oDao = new cl_avaliacaogruporesposta();
          $oDao->excluir($iIdAvaliacaoGrupoResposta);
          if ($oDao->erro_status == 0) {
            throw new DBException("Erro ao excluir resposta da avaliacao.");
          }
        });
      }

      // exclui avaliacaogrupoperguntaresposta
      $oDaoAvaliacaoGrupoPerguntaResposta->excluir(null, $sWhereGrupoRespostas);
      if ($oDaoAvaliacaoGrupoPerguntaResposta->erro_status == 0) {
        throw new DBException("Erro ao excluir vínculo da resposta.");
      }
    }

    // remove respostas
    $oDaoAvaliacaoResposta->excluir(null, "db106_avaliacaoperguntaopcao = {$this->iCodigo}" );
    if ( $oDaoAvaliacaoResposta->erro_status == 0 ) {
      throw new DBException("Não foi possível excluir as respostas.");
    }

    // remove vinculo com layout campos
    $oDaoAvaliacaoPerguntaOpcaoLayoutCampo = new cl_avaliacaoperguntaopcaolayoutcampo();
    $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->excluir(null, " ed313_avaliacaoperguntaopcao = {$this->iCodigo}");
    if ( $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->erro_status == 0 ) {
      throw new DBException("Não foi possível excluir vinculo com layout campos.");
    }

    // exclui as opções
    $oDaoOpcao = new cl_avaliacaoperguntaopcao();
    $oDaoOpcao->excluir($this->iCodigo);
    if ( $oDaoOpcao->erro_status == 0 ) {
      throw new DBException("Não foi possível excluir a opção.");
    }

    return true;
  }

}
