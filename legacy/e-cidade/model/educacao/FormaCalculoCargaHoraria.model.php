<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

define("MSG_FORMACALCULOCARGAHORARIA", "educacao.escola.FormaCalculoCargaHoraria.");

/**
 * Configuração da formula de calculo da carga horária da escola
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package educacao
 * @version $Revision: 1.2 $
 */
class FormaCalculoCargaHoraria {

  const MSG_FORMACALCULOCARGAHORARIA = "educacao.escola.FormaCalculoCargaHoraria.";

  private $iCodigo;

  /**
   * Ano de vigencia
   * @var integer
   */
  private $iAno;

  /**
   *  - se true aplica fórmula: (Somatório das Aulas Dadas * duração do período) / 60 "1 hora"
   *  - se false retorna : ( Somatório das Aulas Dadas )
   * @var boolean
   */
  private $lCalculaDuracaoPeriodo = false;

  /**
   * Escola configurada
   * @var Escola
   */
  private $oEscola;

  function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return ;
    }

    $oDaoRegra = new cl_regracalculocargahoraria();
    $sSqlRegra = $oDaoRegra->sql_query_file($iCodigo);
    $rsRegra   = db_query($sSqlRegra);

    if ( $rsRegra && pg_num_rows($rsRegra) > 0 ) {

      $oDados = db_utils::fieldsMemory($rsRegra, 0);

      $this->iCodigo                = $oDados->ed127_codigo;
      $this->iAno                   = $oDados->ed127_ano;
      $this->lCalculaDuracaoPeriodo = $oDados->ed127_calculaduracaoperiodo == 't';
      $this->oEscola                = EscolaRepository::getEscolaByCodigo($oDados->ed127_escola);
    }

  }

  /**
   * Getter codigo
   * @param integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }


  /**
   * Setter Ano
   * @param integer
   */
  public function setAno ($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Getter Ano
   * @param integer
   */
  public function getAno () {
    return $this->iAno;
  }


  /**
   * Setter Tipo de calculo para duração do período
   *  - se true aplica fórmula: (Somatório das Aulas Dadas * duração do período) / 60 "1 hora"
   *  - se false retorna : ( Somatório das Aulas Dadas )
   * @param boolean
   */
  public function setCalculaDuracaoPeriodo ($CalculaDuracaoPeriodo) {
    $this->lCalculaDuracaoPeriodo = $CalculaDuracaoPeriodo;
  }

  /**
   * Valida se é para calcular a carga horária pela duração do período
   * @param boolean
   */
  public function isCalcularDuracaoPeriodo () {
    return $this->lCalculaDuracaoPeriodo;
  }


  /**
   * Setter escola
   * @param Escola
   */
  public function setEscola (Escola $oEscola) {
    $this->oEscola = $oEscola;
  }

  /**
   * Getter escola
   * @param Escola
   */
  public function getEscola () {
    return $this->oEscola;
  }

}