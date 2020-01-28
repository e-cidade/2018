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
require_once 'std/DBNumber.php';
/**
 * Class ImportacaoArquivoTCEAC
 */
class ImportacaoArquivoTCEAC {

  /**
   * @type string
   */
  const ARQUIVO_PLANOCONTA = 'config/tce/AC/PlanoDeContas.xml';

  /**
   * @type string
   */
  const ARQUIVO_RECURSO    = 'config/tce/AC/Recursos.xml';

  /**
   * @type string
   */
  const ARQUIVO_DOCUMENTOS = 'config/tce/AC/DocumentosContabeis.xml';

  /**
   * @type integer
   */
  const TIPO_ARQUIVO_PLANOCONTA = 1;

  /**
   * @type integer
   */
  const TIPO_ARQUIVO_RECURSO    = 2;

  /**
   * @type integer
   */
  const TIPO_ARQUIVO_DOCUMENTOS = 3;

  private function __construct() {}
  private function __clone() {}


  /**
   * @param \File   $oArquivo
   * @param integer $iTipoArquivo
   *
   * @return bool
   * @throws \BusinessException
   * @throws \Exception
   */
  public static function criarXML(File $oArquivo, $iTipoArquivo) {

    if (!file_exists($oArquivo->getFilePath())) {
      throw new BusinessException("Arquivo carregado não encontrado.");
    }

    $aDadosArquivo   = file($oArquivo->getFilePath());
    $oXmlDocument    = new DOMDocument('1.0', 'ISO-8859-1');
    $oElementoGlobal = $oXmlDocument->createElement(self::getNodeName($iTipoArquivo).'s');
    foreach ($aDadosArquivo as $sDadosArquivo) {

      $sDadosArquivo = str_replace(';', ',', $sDadosArquivo);
      list($iCodigoTCE, $iCodigoEcidade) = explode(",", $sDadosArquivo);
      if ( (empty($iCodigoTCE) || empty($iCodigoEcidade)) || $sDadosArquivo == "Código do MP Acre, Código do e-Cidade\n" ) {
        continue;
      }

      $oDocumentoXML = $oXmlDocument->createElement(self::getNodeName($iTipoArquivo));
      $oDocumentoXML->setAttribute('tce', trim($iCodigoTCE));
      $oDocumentoXML->setAttribute('ecidade', trim($iCodigoEcidade));
      $oElementoGlobal->appendChild($oDocumentoXML);
    }
    $oXmlDocument->appendChild($oElementoGlobal);
    $oXmlDocument->preserveWhiteSpace = false;
    $oXmlDocument->formatOutput       = true;
    $oXmlDocument->save(self::getPath($iTipoArquivo));
    return true;
  }

  /**
   * Retorna o nome do nó a ser utilizado pelo programa
   * @param $iTipo
   *
   * @return string
   * @throws \Exception
   */
  public static function getNodeName($iTipo) {

    switch ($iTipo) {

      case self::TIPO_ARQUIVO_RECURSO:
        $sNodeName = 'recurso';
        break;

      case self::TIPO_ARQUIVO_PLANOCONTA:
        $sNodeName = 'planoconta';
        break;

      case self::TIPO_ARQUIVO_DOCUMENTOS:
        $sNodeName = 'documento';
        break;

      default:
        throw new Exception("Programa não configurado para ler o arquivo tipo {$iTipo}.");
    }

    return $sNodeName;
  }

  /**
   * @param $iTipo
   *
   * @return string
   * @throws \Exception
   */
  public static function getPath($iTipo) {

    switch ($iTipo) {

      case self::TIPO_ARQUIVO_RECURSO:
        $sPath = self::ARQUIVO_RECURSO;
        break;

      case self::TIPO_ARQUIVO_PLANOCONTA:
        $sPath = self::ARQUIVO_PLANOCONTA;
        break;

      case self::TIPO_ARQUIVO_DOCUMENTOS:
        $sPath = self::ARQUIVO_DOCUMENTOS;
        break;

      default:
        throw new Exception("Programa não configurado para ler o arquivo tipo {$iTipo}.");
    }

    return $sPath;
  }

  /**
   * @throws BusinessException
   * @return File
   */
  public static function criarCSV($iTipo) {

    if (!self::possuiArquivoImportado($iTipo) ) {
      throw new BusinessException("Arquivo de configuração não encontrado.");
    }

    $aArquivo = array("Código do MP Acre, Código do e-Cidade");
    $oDomDocument = new DOMDocument('1.0', 'ISO-8859-1');
    $oDomDocument->load(self::getPath($iTipo));
    $aPlanoContas = $oDomDocument->getElementsByTagName(self::getNodeName($iTipo));
    foreach ($aPlanoContas as $oElemento) {
      $aArquivo[] = "{$oElemento->getAttribute('tce')},{$oElemento->getAttribute('ecidade')}";
    }

    $sNomeArquivo = 'tmp/'.self::getNodeName($iTipo).'.csv';
    $hArquivoCSV  = fopen($sNomeArquivo, "w");
    fwrite($hArquivoCSV, implode("\n", $aArquivo));
    fclose($hArquivoCSV);
    return new File($sNomeArquivo);
  }

  /**
   * @param $iTipo
   *
   * @return bool
   * @throws \Exception
   */
  public static function possuiArquivoImportado($iTipo) {

    if (!file_exists(self::getPath($iTipo))) {
      return false;
    }
    return true;
  }
}