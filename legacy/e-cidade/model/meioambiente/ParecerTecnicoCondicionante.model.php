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
 * Condicionante para Parecer Técnico
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class ParecerTecnicoCondicionante {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Descrição da Condicionante
   * @var string
   */
  private $oCondicionante = null;

  /**
   * Variável que define se esta condicionante é padrão
   * @var boolean
   */
  private $oParecerTecnico = null;


  public function __construct( $iSequencial = null ) {

    $oDaoParecerTecnicoCondicionante = db_utils::getDao('parecertecnicocondicionante');
    $rsParecerTecnicoCondicionante   = null;

    if (!is_null($iSequencial)) {

      $sSql                          = $oDaoParecerTecnicoCondicionante->sql_query($iSequencial);
      $rsParecerTecnicoCondicionante = $oDaoParecerTecnicoCondicionante->sql_record($sSql);
    }

    if (!is_null($rsParecerTecnicoCondicionante)) {

      $oDados = db_utils::fieldsMemory($rsParecerTecnicoCondicionante, 0);

      $this->iSequencial     = $oDados->am12_sequencial;
      $this->oCondicionante  = new Condicionante($oDados->am12_condicionante);
      $this->oParecerTecnico = new ParecerTecnico($oDados->am12_parecertecnico);
    }
  }

  public function getSequencial() {
    return $this->iSequencial;
  }

  public function getCondicionante() {
    return $this->oCondicionante;
  }

  public function setCondicionante(Condicionante $oCondicionante) {
    $this->oCondicionante = $oCondicionante;
  }

  public function getParecerTecnico() {
    return $this->oParecerTecnico;
  }

  public function setParecerTecnico(ParecerTecnico $oParecerTecnico) {
    $this->oParecerTecnico = $oParecerTecnico;
  }

  /**
   * Salva o vínculo entre Parecer Técnico em Condicionante do DB
   * @throws Excception
   */
  public function incluir() {

    try {

      $oDaoParecerTecnicoCondicionante = db_utils::getDao('parecertecnicocondicionante');
      $oDaoParecerTecnicoCondicionante->am12_parecertecnico = $this->oParecerTecnico->getSequencial();
      $oDaoParecerTecnicoCondicionante->am12_condicionante  = $this->oCondicionante->getSequencial();
      $oDaoParecerTecnicoCondicionante->incluir();

      $this->iSequencial = $oDaoParecerTecnicoCondicionante->am12_sequencial;
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }
}