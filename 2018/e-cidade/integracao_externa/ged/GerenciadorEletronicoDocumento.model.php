<?php
require_once 'integracao_externa/ged/GEDAcessoWebService.model.php';

/**
 * Gerenciador Eletrônico de Documento
 *
 * Model responsável por preparar os dados do GED que serão indexados posteriormente por ferramenta
 * externa via webservice.
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @author Bruno Silva <bruno.silva@dbseller.com.br>
 * @package integracao_externa
 * @subpackage ged
 * @version $Revision: 1.2 $
 */
class GerenciadorEletronicoDocumento {

  /**
   * Localização destino arquivo
   * @var string
   */
  const GED_LOCALIZACAO_DESTINO = "integracao_externa/ged/arquivos/";

  /**
   * Localização de origem do arquivo
   * @var string
   */
  private $sLocalizacaoOrigem;

  /**
   * Nome do Arquivo
   * @var string
   */
  private $sNomeArquivo;


  /**
   * Método responsável por mover o arquivo gerado para o usuário para as pastas que webservice do GED
   * irá varrer para verificar se existe novo arquivo à ser indexado
   * @param array $aDadosArquivo
   * @throws Exception
   * @return boolean
   */
  public function moverArquivo(array $aDadosArquivo) {

    $this->validarPropriedadesDoObjeto($aDadosArquivo);

    $sDestinoArquivo    = self::GED_LOCALIZACAO_DESTINO.$this->sNomeArquivo;
    $sOrigemArquivo     = $this->sLocalizacaoOrigem.$this->sNomeArquivo;
    $rsCopiarArquivo    = copy($sOrigemArquivo, $sDestinoArquivo);

    if (!$rsCopiarArquivo) {
      throw new Exception("Não foi possível mover o arquivo {$this->sNomeArquivo}.");
    }

    $lDefiniuPermissoes = chmod($sDestinoArquivo, 0777);
    if ( !$lDefiniuPermissoes ) {
      throw new Exception("Não foi possivel definir permissões para o arquivo.");
    }

    $this->indexarArquivo($this->sNomeArquivo, $aDadosArquivo);
    return true;
  }

  /**
   * Verifica se todas as propriedades vieram setadas corretamente no objeto que será fornecido ao webservice
   * @param  array $aDadosArquivo
   * @throws BusinessException
   * @return boolean
   */
  private function validarPropriedadesDoObjeto($aDadosArquivo) {

    foreach ($aDadosArquivo as $oStdDados) {

      $aDadosObjeto = get_object_vars($oStdDados);

      if (!array_key_exists("nome", $aDadosObjeto)) {
        throw new BusinessException("Variável [nome] não encontrada no objeto.");
      }

      if (!array_key_exists("tipo", $aDadosObjeto)) {
        throw new BusinessException("Variável [tipo] não encontrada no objeto.");
      }

      if (!array_key_exists("valor", $aDadosObjeto)) {
        throw new BusinessException("Variável [valor] não encontrada no objeto.");
      }
    }

    return true;
  }

  /**
   * Executa o objeto que verifica se há novos arquivos à serem indexados
   * @param string $sDestinoArquivo
   * @param array $aDadosArquivo
   * @return boolean true
   */
  private function indexarArquivo($sNomeArquivo, array $aDadosArquivo) {

    $oAcessoGed = new GEDAcessoWebService();
    $oAcessoGed->verificarNovoArquivo($sNomeArquivo, $aDadosArquivo);
    return true;
  }

  /**
   * Seta o nome do arquivo
   * @param string $sNomeArquivo
   */
  public function setNomeArquivo($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * Retorna o nome do arquivo
   * @return string
   */
  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }

  /**
   * Seta a localização de origem do arquivo
   * @param string $sLocalizacaoOrigem
   */
  public function setLocalizacaoOrigem($sLocalizacaoOrigem) {
    $this->sLocalizacaoOrigem = $sLocalizacaoOrigem;
  }

  /**
   * Retorna a localização de destino do arquivo
   * @return string
   */
  public function getLocalizacaoDestino() {
    return $this->sLocalizacaoDestino;
  }
}
?>