<?php
/**
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

require_once('model/contabilidade/arquivos/tce/AC/ImportacaoArquivoTCEAC.model.php');
/**
 * Class ArquivoConfiguracao
 */
class ArquivoConfiguracaoTCEAC {

  /**
   * @var ArquivoConfiguracaoTCEAC
   */
  private static $oInstancia;

  private $aConfiguracoes = array(
    'documento'      => array( 'file' => ImportacaoArquivoTCEAC::ARQUIVO_DOCUMENTOS, 'label' => "Vinculação de Documentos" ),
    'recurso'        => array( 'file' => ImportacaoArquivoTCEAC::ARQUIVO_RECURSO, 'label' => "Vinculação de Recursos" ),
    'planoconta'     => array( 'file' => ImportacaoArquivoTCEAC::ARQUIVO_PLANOCONTA, 'label' => "Vinculação do Plano de Contas" ),
    'contacorrente'  => array( 'file' => 'config/tce/AC/ContaCorrente.xml', 'label' => "Conta Corrente"),
    'tipoconta'      => array( 'file' => 'config/tce/AC/TipoConta.xml', 'label' => "Tipo de Conta"),
    'tipoempenho'    => array( 'file' => 'config/tce/AC/TipoEmpenho.xml', 'label' => "Tipo de Empenho")
  );

  private $aDados = array();

  private function __construct() {

    foreach ($this->aConfiguracoes as $sNodeName => $aFile) {

      $sFile = $aFile['file'];

      if (!file_exists($sFile)) {
        throw new BusinessException("Arquivo de confinguração {$aFile['label']} não encontrado.");
      }

      $oDomXml = new DOMDocument();
      $oDomXml->load($sFile);
      $aDadosXml = $oDomXml->getElementsByTagName($sNodeName);

      foreach ($aDadosXml as $oNodeValue) {
        $this->aDados[$sNodeName][$oNodeValue->getAttribute("ecidade")] = $oNodeValue->getAttribute("tce");
      }
    }
  }

  /**
   * @return array
   */
  public function getEventosContabis() {
    return $this->aDados['documento'];
  }

  /**
   * @return array
   */
  public function getContaCorrente() {
    return $this->aDados['contacorrente'];
  }

  /**
   * @param  integer $iCodigo
   * @return integer
   */
  public function getTipoContaPorCodigo($iCodigo) {
    return isset($this->aDados['tipoconta'][$iCodigo]) ? $this->aDados['tipoconta'][$iCodigo] : null;
  }

  /**
   * @param  string $sCodigo
   * @return string
   */
  public function getPlanoContaPorCodigo($sCodigo) {
    return isset($this->aDados['planoconta'][$sCodigo]) ? $this->aDados['planoconta'][$sCodigo] : null;
  }

  /**
   * @param  integer $iCodigo
   * @return integer
   */
  public function getTipoEmpenhoPorCodigo($iCodigo) {
    return isset($this->aDados['tipoempenho'][$iCodigo]) ? $this->aDados['tipoempenho'][$iCodigo] : null;
  }

  /**
   * @param $iCodigo
   * @return integer
   */
  public function getEventoPorCodigo($iCodigo) {
    return isset($this->aDados['documento'][$iCodigo]) ? $this->aDados['documento'][$iCodigo] : null;
  }

  /**
   * @param $iCodigo
   * @return mixed
   */
  public function getRecursoPorCodigo($iCodigo) {
    return isset($this->aDados['recurso'][$iCodigo]) ? $this->aDados['recurso'][$iCodigo] : null;
  }

  /**
   * @param $iCodigo
   * @return integer
   */
  public function getContaCorrentePorCodigo($iCodigo) {
    return isset($this->aDados['contacorrente'][$iCodigo]) ? $this->aDados['contacorrente'][$iCodigo] : null;
  }

  /**
   * @return ArquivoConfiguracaoTCEAC
   */
  public static function getInstancia() {

    if (empty(self::$oInstancia)) {
      self::$oInstancia = new ArquivoConfiguracaoTCEAC();
    }
    return self::$oInstancia;
  }

  private function __clone() {}
}
