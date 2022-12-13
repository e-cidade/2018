<?php
Class AvaliacaoSocialWebservice {

  const CODIGO_AVALIACAO = 3000008;
  private $iCodigoMatricula    = null;

  public function __construct($iCodigoMatricula) {
    $this->iCodigoMatricula = $iCodigoMatricula;
  }

  /**
   * @return mixed
   * @throws DBException
   * @internal param $iCodigoCgm
   */
  public function getDadosAvaliacao() {

    $iCodigoAvaliacaoGrupo = $this->getCodigoAvaliacaoMatricula($this->iCodigoMatricula);
    $oAvaliacao            = AvaliacaoRepository::getAvaliacaoByCodigo(self::CODIGO_AVALIACAO);
    $oAvaliacao->setAvaliacaoGrupo($iCodigoAvaliacaoGrupo);

    $oAvaliacaoAdapter = new AvaliacaoAdapter($oAvaliacao);
    $oDadosAvaliacao   = utf8_encode_all($oAvaliacaoAdapter->getObject());

    return $oDadosAvaliacao;
  }

  public function salvar($aRespostas) {


    $oAvaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(self::CODIGO_AVALIACAO);
    $oAvaliacao->setAvaliacaoGrupo();

    $oAvaliacaoESocial = new AvaliacaoESocial();
    $oAvaliacaoESocial->setAvaliacao($oAvaliacao);
    $oAvaliacaoESocial->SetServidor(MatriculaRepository::getMatriculaByCodigo($this->iCodigoMatricula));
    $oAvaliacaoESocial->setPerguntasRespostas($aRespostas);
    $oAvaliacaoESocial->salvar();


    return $aRespostas;
  }


  /**
   * Retorna o Codigo da Avaliação do CGM
   * @param $iMatricula
   * @return string
   * @throws DBException
   */
  private function getCodigoAvaliacaoMatricula($iMatricula) {

    $oDaoAvaliacaoMatricula = new cl_avaliacaogruporespostamatricula();
    $sSqlDadosMatricula           = $oDaoAvaliacaoMatricula->sql_query(null, "db107_sequencial", "db107_sequencial desc", "eso02_rhpessoal = {$iMatricula}");
    $rsDadosMatricula             = db_query($sSqlDadosMatricula);
    if (!$rsDadosMatricula) {
      throw new DBException("Erro ao realizar consulta dos dados da avaliação da matricula {$iMatricula}".pg_last_error());
    }

    $iCodigoAvaliacaoGrupo = '';
    if (pg_num_rows($rsDadosMatricula) > 0) {
      $iCodigoAvaliacaoGrupo = db_utils::fieldsMemory($rsDadosMatricula, 0)->db107_sequencial;
    }

    return $iCodigoAvaliacaoGrupo;

  }
}
