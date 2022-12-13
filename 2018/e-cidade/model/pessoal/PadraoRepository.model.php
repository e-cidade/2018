<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015 DBSeller Servicos de Informatica
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
 * Representa o padrão que o Servidor
 *
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
class PadraoRepository {

  /**
   * Instancia do Repositório
   *
   * @var mixed
   */
  private static $oInstance;

  /**
   * Coleção de Padrões
   *
   * @var array
   */
  private $aCollection = array();

  /**
   * Evita com que o repositório seja contruido fora dele mesmo(singleton)
   */
  private function __construct() {
  }

  /**
   * Evita a clonagem 
   */
  private function __clone() {

  }

  /**
   * Retorna a Instancia do Repositório
   */
  public static function getInstance() {

    if (self::$oInstance === null) {
      self::$oInstance = new PadraoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona um padrão ao Repositório
   *
   * @param Padrao $oPadrao
   */
  public function add(Padrao $oPadrao) {
    $sHash = $this->getHash(
      $oPadrao->getCodigo(),
      $oPadrao->getCompetencia(),
      $oPadrao->getRegime(),
      $oPadrao->getInstituicao()
    );
    $this->aCollection[$sHash] = $oPadrao;
  }


  /**
   * Retorna o Padrão pelos Identificadores Unicos
   *
   * @param String $sCodigo
   * @param Regime $oRegime
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   */
  public static function getByChave($sCodigo, Regime $oRegime, DBCompetencia $oCompetencia = null, Instituicao $oInstituicao= null) {

    $oRepository = self::getInstance();

    if ( !$oInstituicao ) {
      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    }

    if (!$oCompetencia) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    $sHash = $oRepository->getHash($sCodigo,$oCompetencia,$oRegime, $oInstituicao);

    if (!array_key_exists($sHash, $oRepository->aCollection)) {
      $oRepository->add(self::make($sCodigo, $oRegime, $oCompetencia, $oInstituicao));
    }

    return $oRepository->aCollection[$sHash];
  }

  /**
   * Cria o Objeto Padrão atráves de sua representação no banco de dados
   *
   * @param mixed $sCodigo
   * @param Regime $oRegime
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   */
  private static function make($sCodigo, Regime $oRegime, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

    $oDaoPadrao = new cl_padroes();
    $sSql = $oDaoPadrao->sql_query_file(
      $oCompetencia->getAno(),
      $oCompetencia->getMes(),
      $oRegime->getCodigo(),
      $sCodigo,
      $oInstituicao->getCodigo()
    ); 

    $rsQuery = db_query($sSql);

    if (!$rsQuery) {
      throw new DBException("Erro ao buscar os dados do padrão.");
    }

    if (pg_num_rows($rsQuery) == 0) {
      $sMsgErro  = "Padrão (".$sCodigo.") ";
      $sMsgErro .= "não encontrado na competência (".$oCompetencia->getAno()."/".$oCompetencia->getMes().")\n";
      $sMsgErro .= "para o regime (".$oRegime->getDescricao()."). Favor verificar.";
      throw new BusinessException($sMsgErro);
    }

    return db_utils::makeFromRecord($rsQuery, function($oDados) {

      $sCodigo      = trim($oDados->r02_codigo);
      $oRegime      = RegimeRepository::getInstanciaPorCodigo($oDados->r02_regime);
      $oCompetencia = new DBCompetencia($oDados->r02_anousu, $oDados->r02_mesusu);
      $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo($oDados->r02_instit);
      $oPadrao      = new Padrao($sCodigo, $oRegime, $oCompetencia, $oInstituicao);
      $oPadrao->setValor($oDados->r02_valor);
      $oPadrao->setDescricao(trim($oDados->r02_descr));
      $oPadrao->setFormula(trim($oDados->r02_form));
      $oPadrao->setTipo(trim($oDados->r02_tipo));
      return $oPadrao;
    });

  }

  /**
   * Retorna um hash unico para o objeto criado para que a complexidade do repositório seja baixa.
   *
   * @param mixed $sCodigo
   * @param DBCompetencia $oCompetencia
   * @param Regime $oRegime
   * @param Instituicao $oInstituicao
   */
  private function getHash($sCodigo, DBCompetencia $oCompetencia, Regime $oRegime, Instituicao $oInstituicao) {

    $iAnoCompetencia     = $oCompetencia->getAno();
    $iMesCompetencia     = $oCompetencia->getMes();
    $iCodigoRegime       = $oRegime->getCodigo();
    $iCodigoPadrao       = trim($sCodigo);
    $iCodigoInstituicao  = $oInstituicao->getCodigo();

    return md5(
      $iAnoCompetencia.
      $iMesCompetencia.
      $iCodigoInstituicao.
      $iCodigoRegime.
      $iCodigoPadrao.
      $iCodigoInstituicao
    );
  }
}
