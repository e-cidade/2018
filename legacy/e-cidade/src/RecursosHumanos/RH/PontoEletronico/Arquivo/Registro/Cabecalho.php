<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro;

/**
 * Classe referente a regra de negócio do cabeçalho do arquivo do ponto eletrônico
 *
 * Class Cabecalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Cabecalho {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sArquivo;

  /**
   * @var \DBLayoutLinha
   */
  private $oLayoutLinha;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return \DBLayoutLinha
   */
  public function getLayoutLinha() {
    return $this->oLayoutLinha;
  }

  /**
   * @return string
   */
  public function getArquivo() {
    return $this->sArquivo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param \DBLayoutLinha $oLayoutLinha]
   */
  public function setLayoutLinha(\DBLayoutLinha $oLayoutLinha) {
    $this->oLayoutLinha = $oLayoutLinha;
  }

  /**
   * @param string $sArquivo
   */
  public function setArquivo($sArquivo) {
    $this->sArquivo = $sArquivo;
  }
}