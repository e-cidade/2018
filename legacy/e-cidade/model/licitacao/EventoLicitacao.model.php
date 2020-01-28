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

class EventoLicitacao {

  const FASE_INTERNA                 = 1;
  const FASE_EDITAL_PUBLICADO        = 2;
  const FASE_PUBLICACAO              = 3;
  const FASE_HABILITACAO_PROPOSTAS   = 4;
  const FASE_ADJUDICACAO_HOMOLOGACAO = 5;

  const RESULTADO_INDEFERIDO            = 1;
  const RESULTADO_DEFERIDO              = 2;
  const RESULTADO_PARCIALMENTE_DEFERIDO = 3;

  const PUBLICACAO_DIARIO_ESTADO       = 1;
  const PUBLICACAO_INTERNET            = 2;
  const PUBLICACAO_JORNAL              = 3;
  const PUBLICACAO_MURAL_ENTIDADE      = 4;
  const PUBLICACAO_DIARIO_MUNICIPIO    = 5;
  const PUBLICACAO_DIARIO_MUNICIPIO_RS = 6;
  const PUBLICACAO_DIARIO_UNIAO        = 7;
  const PUBLICACAO_NAO_PUBLICADO       = 8;

  const SIGLA_PUBLICACAO_DIARIO_ESTADO       = 'E';
  const SIGLA_PUBLICACAO_INTERNET            = 'I';
  const SIGLA_PUBLICACAO_JORNAL              = 'J';
  const SIGLA_PUBLICACAO_MURAL_ENTIDADE      = 'M';
  const SIGLA_PUBLICACAO_DIARIO_MUNICIPIO    = 'O';
  const SIGLA_PUBLICACAO_DIARIO_MUNICIPIO_RS = 'R';
	const SIGLA_PUBLICACAO_DIARIO_UNIAO        = 'U';
  const SIGLA_PUBLICACAO_NAO_PUBLICADO       = 'N';

  const TIPO_EVENTO_ANULACAO_DETERMINACAO_JUDICIAL             = 2;
  const TIPO_EVENTO_ANULACAO_OFICIO                            = 3;
  const TIPO_EVENTO_ENCERRAMENTO_FALTA_PROPOSTAS_CLASSIFICADAS = 4;
  const TIPO_EVENTO_ENCERRAMENTO_FALTA_LICITANTES_HABILITADOS  = 5;
  const TIPO_EVENTO_ENCERRAMENTO_FALTA_INTERESSADOS            = 6;
  const TIPO_EVENTO_ENCERRAMENTO                               = 7;
  const TIPO_EVENTO_REVOGACAO_OFICIO                           = 15;

  /**
   *
   * @var integer
   */
  private $iCodigo;

  /**
   *
   * @var integer
   */
  private $iLicitacao;

  /**
   *
   * @var licitacao
   */
  private $oLicitacao;

  /**
   *
   * @var integer
   */
  private $iFase;

  /**
   *
   * @var integer
   */
  private $iTipoEvento;

  /**
   *
   * @var DBDate
   */
  private $oData;

  /**
   *
   * @var DBDate
   */
  private $oDataJulgamento;

  /**
   *
   * @var integer
   */
  private $iAutor;

  /**
   *
   * @var CGMBase
   */
  private $oAutor;

  /**
   *
   * @var integer
   */
  private $iTipoPublicacao;

  /**
   *
   * @var string
   */
  private $sDescricaoPublicacao;

  /**
   *
   * @var integer
   */
  private $iTipoResultado;

  /**
   * @var array
   */
  private $aDocumentos = array();

  /**
   * Construtor
   *
   * @throws DBException
   * @param integet $iCodigo
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo !== null) {

      $oDaoEventoLicitacao = new cl_liclicitaevento;
      $sSql = $oDaoEventoLicitacao->sql_query_file($iCodigo);
      $rsEventoLicitacao = db_query($sSql);

      if ($rsEventoLicitacao === false || pg_num_rows($rsEventoLicitacao) === 0) {
        throw new DBException("Não foi possível encontrar o evento.");
      }

      $oStdEvento = db_utils::fieldsMemory($rsEventoLicitacao, 0);

      $this->iCodigo              = $iCodigo;
      $this->iLicitacao           = $oStdEvento->l46_liclicita;
      $this->iFase                = $oStdEvento->l46_fase;
      $this->iTipo                = $oStdEvento->l46_liclicitatipoevento;
      $this->oData                = new DBDate($oStdEvento->l46_dataevento);
      $this->iAutor               = $oStdEvento->l46_cgm;
      $this->iTipoPublicacao      = $oStdEvento->l46_tipopublicacao;
      $this->iTipoResultado       = $oStdEvento->l46_tiporesultado;
      $this->sDescricaoPublicacao = $oStdEvento->l46_descricaopublicacao;

      if ($oStdEvento->l46_datajulgamento) {
        $this->oDataJulgamento = new DBDate($oStdEvento->l46_datajulgamento);
      }
    }
  }

  /**
   * @return integer Sequencial do evento
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   *
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer Sequencial da licitação
   */
  public function getCodigoLicitacao() {
    return $this->iLicitacao;
  }

  /**
   * @param integer $iLicitacao
   */
  public function setCodigoLicitacao($iLicitacao) {
    $this->iLicitacao = $iLicitacao;
  }

  /**
   * @return licitacao Instancia da model de licitação
   */
  public function getLicitacao() {

    if (!$this->oLicitacao && $this->iLicitacao) {
      $this->oLicitacao = new licitacao($this->iLicitacao);
    }

    return $this->oLicitacao;
  }

  /**
   * @param licitacao $oLicitacao
   */
  public function setLicitacao(licitacao $oLicitacao) {

    $this->iLicitacao = $oLicitacao->getCodigo();
    $this->oLicitacao = $oLicitacao;
  }

  /**
   * @return integer Sequencial do Autor (CGM)
   */
  public function getCodigoAutor() {
    return $this->iAutor;
  }

  /**
   * @param integer $iAutor
   */
  public function setCodigoAutor($iAutor) {
    $this->iAutor = $iAutor;
  }

  /**
   * @return CgmBase
   */
  public function getAutor() {

    if (!$this->oAutor && $this->iAutor) {
      $this->oAutor = CgmRepository::getByCodigo($this->iAutor);
    }

    return $this->oAutor;
  }

  /**
   * @param CgmBase $oAutor
   */
  public function setAutor(CgmBase $oAutor) {

    $this->iAutor = empty($oAutor) ? null : $oAutor->getCodigo();
    $this->oAutor = $oAutor;
  }

  /**
   * @return DBDate Data do evento
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @param DBDate $oData
   */
  public function setData(DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * @return DBDate Data do Julgamento
   */
  public function getDataJulgamento() {
    return $this->oDataJulgamento;
  }

  /**
   * @param DBDate $oDataJulgamento
   */
  public function setDataJulgamento(DBDate $oDataJulgamento) {
    $this->oDataJulgamento = $oDataJulgamento;
  }

  /**
   * @return integer Tipo da Publicacao
   */
  public function getTipoPublicacao() {
    return $this->iTipoPublicacao;
  }

  /**
   * @param integer $iTipoPublicacao
   */
  public function setTipoPublicacao($iTipoPublicacao) {
    $this->iTipoPublicacao = $iTipoPublicacao;
  }

  /**
   * @return integer Tipo de Evento
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * @return string Descrição da Publicação do Evento
   */
  public function getDescricaoPublicacao() {
    return $this->sDescricaoPublicacao;
  }

  /**
   * @param string $sDescricaoPublicacao
   */
  public function setDescricaoPublicacao($sDescricaoPublicacao) {
    $this->sDescricaoPublicacao = $sDescricaoPublicacao;
  }

  /**
   * @return integer Tipo de Resultado
   */
  public function getTipoResultado() {
    return $this->iTipoResultado;
  }

  /**
   * @param integer $iTipoResultado
   */
  public function setTipoResultado($iTipoResultado) {
    $this->iTipoResultado = $iTipoResultado;
  }

  /**
   * @return integer Fase do Evento
   */
  public function getFase() {
    return $this->iFase;
  }

  /**
   * @param integer $iFase
   */
  public function setFase($iFase) {
    $this->iFase = $iFase;
  }

  /**
   * @return integer Código do evento.
   * @throws DBException
   */
  public function salvar() {

    $oDaoEventoLicitacao                          = new cl_liclicitaevento;
    $oDaoEventoLicitacao->l46_sequencial          = $this->iCodigo;
    $oDaoEventoLicitacao->l46_dataevento          = $this->getData()->getDate();
    $oDaoEventoLicitacao->l46_fase                = $this->getFase();
    $oDaoEventoLicitacao->l46_liclicitatipoevento = $this->getTipo();
    $oDaoEventoLicitacao->l46_tipopublicacao      = $this->getTipoPublicacao();
    $oDaoEventoLicitacao->l46_descricaopublicacao = $this->getDescricaoPublicacao();
    $oDaoEventoLicitacao->l46_tiporesultado       = $this->getTipoResultado();
    $oDaoEventoLicitacao->l46_liclicita           = $this->getCodigoLicitacao();

    $oDaoEventoLicitacao->l46_cgm = "null";
    if ($this->getCodigoAutor()) {
      $oDaoEventoLicitacao->l46_cgm = $this->getCodigoAutor();
    }

    $oDaoEventoLicitacao->l46_datajulgamento = null;
    if ($this->oDataJulgamento) {
      $oDaoEventoLicitacao->l46_datajulgamento = $this->getDataJulgamento()->getDate();
    }

    if ($this->iCodigo) {
      $oDaoEventoLicitacao->alterar($this->iCodigo);
    } else {

      $oDaoEventoLicitacao->incluir(null);
      $this->iCodigo = $oDaoEventoLicitacao->l46_sequencial;
    }

    if ($oDaoEventoLicitacao->erro_status == "0") {
      throw new DBException("Não foi possível salvar o Evento da Licitação.");
    }

    return $this->iCodigo;
  }

  /**
   * @throws ParameterException
   * @return DocumentoEventoLicitacao[]
   */
  public function getDocumentos() {

    if (!$this->iCodigo) {
      throw new ParameterException('O evento não foi carregado.');
    }

    $oDaoDocumentos = new cl_liclicitaeventodocumento;
    $sWhere         = "l47_liclicitaevento = {$this->iCodigo}";
    $sSqlDocumentos = $oDaoDocumentos->sql_query_file(null, "l47_sequencial", null, $sWhere);
    $rsDocumentos   = db_query($sSqlDocumentos);
    $iQuantidade    = pg_num_rows($rsDocumentos);

    if ($rsDocumentos && $iQuantidade > 0) {

      for ($iRegistro = 0; $iRegistro < $iQuantidade; $iRegistro++) {

        $iCodigo = db_utils::fieldsMemory($rsDocumentos, $iRegistro)->l47_sequencial;
        $this->aDocumentos[] = new DocumentoEventoLicitacao($iCodigo);
      }
    }

    return $this->aDocumentos;
  }

  /**
   * @throws DBException
   * @throws ParameterException
   * @return boolean
   */
  public function excluir() {

    if (!$this->iCodigo) {
      throw new ParameterException('O evento não foi carregado.');
    }

    $aDocumentos = $this->getDocumentos();
    foreach ($aDocumentos as $oDocumento) {
      $oDocumento->excluir();
    }

    $oDaoEventoLicitacao = new cl_liclicitaevento;
    $oDaoEventoLicitacao->excluir($this->iCodigo);
    if ($oDaoEventoLicitacao->erro_status == '0') {
      throw new DBException('Não foi possível excluir o Evento da Licitação.');
    }
    return true;
  }

	/**
	 * Diz se o evento é encerramento.
	 * @return bool
	 */
  public function isEncerramento() {
  	return $this->isEncerramentoSucesso() || $this->isEncerramentoFracasso();
	}

	/**
	 * Diz se o evento é encerramento ENC
	 * @return bool
	 */
  public function isEncerramentoSucesso() {
  	return $this->getTipo() == self::TIPO_EVENTO_ENCERRAMENTO;
	}

	/**
	 * Diz se o evento é encerramento, diferente de ENC.
	 * @return bool
	 */
	public function isEncerramentoFracasso() {

		return $this->getTipo() == self::TIPO_EVENTO_ENCERRAMENTO_FALTA_PROPOSTAS_CLASSIFICADAS
					 || $this->getTipo() == self::TIPO_EVENTO_ENCERRAMENTO_FALTA_LICITANTES_HABILITADOS
					 || $this->getTipo() == self::TIPO_EVENTO_ENCERRAMENTO_FALTA_INTERESSADOS;
	}

	/**
	 * Diz se o evento é revogação.
	 * @return bool
	 */
	public function isRevogacao() {
		return $this->getTipo() == self::TIPO_EVENTO_REVOGACAO_OFICIO;
	}


	/**
	 * Diz se o evento é anulação.
	 * @return bool
	 */
	public function isAnulacao() {

		return $this->getTipo() == self::TIPO_EVENTO_ANULACAO_DETERMINACAO_JUDICIAL
					 || $this->getTipo() == self::TIPO_EVENTO_ANULACAO_OFICIO;
	}

  /**
   * Retornar constantes relativas aos tipos de envento que caracteriza encerramento.
   * @param boolean $lEncerramentoPadrao Se busca somente encerramento padrão.
   * @return array
   */
  public static function getEventosEncerramento($lEncerramentoPadrao = false) {

    if ($lEncerramentoPadrao) {
      return array(self::TIPO_EVENTO_ENCERRAMENTO);
    }

    return array(
      self::TIPO_EVENTO_ANULACAO_DETERMINACAO_JUDICIAL,
      self::TIPO_EVENTO_ANULACAO_OFICIO,
			self::TIPO_EVENTO_ENCERRAMENTO_FALTA_PROPOSTAS_CLASSIFICADAS,
			self::TIPO_EVENTO_ENCERRAMENTO_FALTA_LICITANTES_HABILITADOS,
			self::TIPO_EVENTO_ENCERRAMENTO_FALTA_INTERESSADOS,
			self::TIPO_EVENTO_ENCERRAMENTO,
			self::TIPO_EVENTO_REVOGACAO_OFICIO
    );
  }

  /**
   * Verifica se a licitação possui eventos cadastrados na fase e evento.
   *
   * @param licitacao $oLicitacao
   * @param integer   $iFase
   * @param integer   $iEvento
   *
   * @return bool
   * @throws DBException|ParameterException
   */
  public static function possuiFaseEvento(licitacao $oLicitacao, $iFase, $iEvento) {

    $iCodigoLicitacao = $oLicitacao->getCodigo();
    if (empty($iCodigoLicitacao)) {
      throw new ParameterException("Licitação informada nao possui um código válido.");
    }

    if (empty($iFase)) {
      throw new ParameterException("Parâmetro Fase é de preenchimento obrigatório.");
    }

    if (empty($iEvento)) {
      throw new ParameterException("Parâmetro Evento é de preenchimento obrigatório.");
    }

    $aWhere = array(
      "l46_liclicita = {$iCodigoLicitacao}",
      "l46_fase = {$iFase}",
      "l46_liclicitatipoevento = {$iEvento}"
    );

    $oDaoEvento    = new cl_liclicitaevento();
    $oSqlEvento    = $oDaoEvento->sql_query_file(null, "1", null, implode(' and ', $aWhere));
    $rsBuscaEvento = db_query($oSqlEvento);

    if (!$rsBuscaEvento) {
      throw new DBException("Ocorreu um erro ao verificar as fases e eventos da Licitação.");
    }

    if (pg_num_rows($rsBuscaEvento) == 0) {
      return false;
    }
    return true;
  }

}
