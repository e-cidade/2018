<?php
/**
 * Model responsável por verificar se é utilizado GED
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @author Bruno Silva <bruno.silva@dbseller.com.br>
 * @version $Revision: 1.4 $
 */
class GerenciadorEletronicoDocumentoConfiguracao {

  /**
   * Controla se usa ged ou não
   * @var boolean
   */
  private $lUsaGed;

  /**
   * URI utilizada para acesso do webservice
   * @var string
   */
  private $sUriWebService;

  /**
   * Location utilizada para acesso do webservice
   * @var string
   */
  private $sPathWebService;

  /**
   *
   * @var unknown
   */
  private static $oGerenciadorEletronicoDocumentoConfiguracao;

  /**
   * Localização padrão do arquivo de configuração do GED
   * @var string
   */
  const LOCALIZACAO_ARQUIVO_CONFIGURACAO_GED = "integracao_externa/ged/libs/configuracao_ged.ini";

  /**
   * Constante para a Solicitação de Compras
   * @var string
   */
  const SOLICITACAO_COMPRA = "SolicitacaoCompra";

  /**
   * Constante para emissão do Processo de Compra
   * @var string
   */
  const PROCESSO_COMPRA = "ProcessoCompra";

  /**
   * Constante para emissão da autorização de empenho
   * @var string
   */
  const AUTORIZACAO_EMPENHO = "AutorizacaoEmpenho";

  /**
   * Constante para emissão do empenho
   * @var string
   */
  const EMPENHO = "Empenho";

  /**
   * Constante para emissão da ordem de compra
   * @var string
   */
  const ORDEM_COMPRA = "OrdemCompra";

  /**
   * Constante para a emissão da Ordem de Pagamento
   * @var string
   */
  const ORDEM_PAGAMENTO = "OrdemPagamento";

  /**
   * Constante para emissão de mapa de proposta
   * @var string
   */
  const MAPA_PROPOSTA = "MapaProposta";

  /**
   * Retorna uma do objeto.
   * @return GerenciadorEletronicoDocumentoConfiguracao
   */
  public static function getInstance () {

  if ( empty(self::$oGerenciadorEletronicoDocumentoConfiguracao) ) {

      $oGEDConfiguracao          = new GerenciadorEletronicoDocumentoConfiguracao();
      $oGEDConfiguracao->lUsaGed = false;
      $sArquivoConfiguracaoGED   = self::LOCALIZACAO_ARQUIVO_CONFIGURACAO_GED;
      if (!file_exists($sArquivoConfiguracaoGED)) {
        return $oGEDConfiguracao;
      }

      $aArquivoConfiguracaoGed           = parse_ini_file($sArquivoConfiguracaoGED);
      $oGEDConfiguracao->lUsaGed         = $aArquivoConfiguracaoGed['usa_ged'] == "1" ? true : false;
      $oGEDConfiguracao->sUriWebService  = $aArquivoConfiguracaoGed['uri'];
      $oGEDConfiguracao->sPathWebService = $aArquivoConfiguracaoGed['location'];
      self::$oGerenciadorEletronicoDocumentoConfiguracao = $oGEDConfiguracao;
    }
    return self::$oGerenciadorEletronicoDocumentoConfiguracao;
  }

  /**
   * Retorna se o cliente utiliza GED
   * @return boolean
   */
  public function utilizaGED() {
    return $this->lUsaGed;
  }

  /**
   * Retorna a URI para ser utilizada pelo webservice
   * @return string
   */
  public function getUriWebService() {
    return $this->sUriWebService;
  }

  /**
   * Retorna o location que será utilizado pelo webservice
   * @return string
   */
  public function getPathWebService() {
    return $this->sPathWebService;
  }

  /**
   * Construtor Privado
   */
  private function __construct() {

  }
}