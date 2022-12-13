<?php
require_once ("std/DBSoapClient.php");
require_once ("libs/JSON.php");

/**
 * Model responsável por se comunicar com o webservice e indexar o arquivo
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @author Bruno Silva <bruno.silva@dbseller.com.br>
 */
class GEDAcessoWebService {

  /**
   * Executa o webservice para que verifique se há novos arquivos à serem indexados.
   * @param string $sDestinoArquivo
   * @param array $aDadosArquivo
   * @throws BusinessException
   * @return boolean
   */
  public function verificarNovoArquivo($sNomeArquivo, array $aDadosArquivo) {

    $oJson                       = new Services_JSON();
    $sDadosJson                  = $oJson->encode($aDadosArquivo);
    $oConfiguracaoGED            = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
    $aParametrosSoap             = array();
    $aParametrosSoap['uri']      = $oConfiguracaoGED->getUriWebService();
    $aParametrosSoap['location'] = $oConfiguracaoGED->getPathWebService();

    try {

      $oDBSoapClient               = new DBSoapClient(null, $aParametrosSoap);
      $oDBSoapClient->indexarArquivo($sNomeArquivo, $sDadosJson);

    } catch (SoapFault $eSoapFault) {
      throw new BusinessException($eSoapFault->getMessage());
    }

    return true;
  }
}