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

/**
 * Class ConjuntoLancamentoPadronizado
 */
class ConjuntoLancamentoPadronizado {

  /**
   * @var ConjuntoLancamentoPadronizado
   */
  private static $oInstancia;

  /**
   * @var array
   */
  private $aLancamentosPadronizados = array();

  /**
   * Caminho do arquivo XML contendo as configurações
   * @var string
   */
  const PATH_XML = 'config/tce/AC/EventosContabeis.xml';


  private function __construct() {

    if (!file_exists(self::PATH_XML)) {
      throw new BusinessException("Arquivo XML [config/tce/AC/EventosContabeis.xml] não localizado no projeto.");
    }

    $oDomXml = new DOMDocument();
    $oDomXml->load(self::PATH_XML);
    $aConjutoLancamento = $oDomXml->getElementsByTagName("eventocontabil");
    foreach ($aConjutoLancamento as $oEvento) {

      $oStdEventoContabil          = new stdClass();
      $oStdEventoContabil->tce     = $oEvento->getAttribute("tce");
      $oStdEventoContabil->ecidade = $oEvento->getAttribute("ecidade");
      $this->aLancamentosPadronizados[] = $oStdEventoContabil;
    }
    $this->registrar();
  }

  /**
   * Passa o código do evento contábil do ECIDADE e retorna o código do TCE/AC
   * @param $iCodigo
   * @return int
   */
  public function getVinculo($iCodigo) {
    return DBRegistry::get("doc_{$iCodigo}");
  }

  /**
   * Registra os códigos contidos no XML
   */
  private function registrar() {

    foreach ($this->getConjuntoLancamentoPadronizado() as $oStdConjunto) {
      DBRegistry::add("doc_".$oStdConjunto->ecidade, $oStdConjunto->tce);
    }
  }

  /**
   * @return stdClass
   */
  public function getConjuntoLancamentoPadronizado() {
    return $this->aLancamentosPadronizados;
  }

  /**
   * @return ConjuntoLancamentoPadronizado
   */
  public static function getInstancia() {

    if (empty(self::$oInstancia)) {
      self::$oInstancia = new ConjuntoLancamentoPadronizado();
    }
    return self::$oInstancia;
  }

  private function __clone() {}
}
















