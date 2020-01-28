<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
 * Licença para Parecer Técnico
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class LicencaEmpreendimento {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Arquivo da Licença
   * @var int
   */
  private $iArquivo = null;

  /**
   * ParecerTecnico da Licença
   * @var ParecerTecnico
   */
  private $oParecerTecnico = null;

  public function __construct( $iSequencial = null ) {

    $oDaoLicencaEmpreencimento = db_utils::getDao('licencaempreendimento');
    $rsLicencaEmpreencimento   = null;

    if (!is_null($iSequencial)) {

      $sSql                    = $oDaoLicencaEmpreencimento->sql_query($iSequencial);
      $rsLicencaEmpreencimento = $oDaoLicencaEmpreencimento->sql_record($sSql);
    }

    if (!is_null($rsLicencaEmpreencimento)) {

      $oDados = db_utils::fieldsMemory($rsLicencaEmpreencimento, 0);

      $this->iSequencial      = $oDados->am13_sequencial;
      $this->iArquivo         = $oDados->am13_arquivo;
      $this->oParecerTecnico  = new ParecerTecnico($oDados->am13_parecertecnico);
    }
  }

  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Altera o ParecerTecnico
   * @param ParecerTecnico
   */
  public function setParecerTecnico ($oParecerTecnico) {
    $this->oParecerTecnico = $oParecerTecnico;
  }

  /**
   * Busca o ParecerTecnico
   * @return $oParecerTecnico
   */
  public function getParecerTecnico () {
    return $this->oParecerTecnico;
  }

  /**
   * Altera o arquivo de Licença
   * @param int
   */
  public function setArquivo ($iArquivo) {
    $this->iArquivo = $iArquivo;
  }

  /**
   * Busca o arquivo de linceça
   * @return $iArquivo
   */
  public function getArquivo () {
    return $this->iArquivo;
  }

  public function incluir() {

    try {

      $oDaoLicencaEmpreendimento = db_utils::getDao("licencaempreendimento");
      $oDaoLicencaEmpreendimento->am13_parecertecnico = $this->oParecerTecnico->getSequencial();
      $oDaoLicencaEmpreendimento->incluir();

      $this->iSequencial = $oDaoLicencaEmpreendimento->am13_sequencial;
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }

  public function excluir( $iCodigoParecerTecnico ) {

    $oDaoLicencaEmpreendimento = db_utils::getDao("licencaempreendimento");

    $sWhere = " am13_parecertecnico = {$iCodigoParecerTecnico} ";
    $oDaoLicencaEmpreendimento->excluir( null, $sWhere );

    if ($oDaoLicencaEmpreendimento->erro_status == "0" ) {
      return false;
    }

    return true;
  }
}