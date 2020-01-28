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

/**
 * Forma de Avaliacao da escola
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.10 $
 */
class FormaAvaliacao {

  /**
   * Codigo da forma de avaliacao
   * @var integer
   */
  private $iCodigo;

  /**
   * Descricao da forma de avaliacao
   * @var string
   */
  private $sDescricao;

  /**
   *
   * @var string
   */
  private $sTipo;

  /**
   * Menor valor que pode ter uma forma de avaliacao.
   * Valido somente para tipo: Nota, para outros assume valor 0
   * @var integer
   */
  private $iMenorValor;

  /**
   * Maior valor que pode ter uma forma de avaliacao.
   * Valido somente para tipo: Nota, para outros assume valor 0
   * @var integer
   */
  private $iMaiorValor;

  /**
   * Valor minimo para aprovamento em uma forma de avaliacao
   * Valido para tipo: Nota e Nivel, para outros assume valor 0
   * @var string
   */
  private $mAproveitamentoMinimo;

  /**
   * Só quando o tipo da forma de avaliacao for = NIVEL
   * @var array
   */
  private $aConceitos;

  /**
   * Variacao da nota permitida
   * @var float
   */
  private $nVariacao;

  /**
   *
   * @param integer $iCodigoFormaAvaliacao
   */
  public function __construct($iCodigoFormaAvaliacao = null) {

    if (!empty($iCodigoFormaAvaliacao)) {

      $oDaoFormaAvaliacao = db_utils::getDao('formaavaliacao');
      $sSqlFormaAvaliacao = $oDaoFormaAvaliacao->sql_query_file($iCodigoFormaAvaliacao);
      $rsFormaAvaliacao   = $oDaoFormaAvaliacao->sql_record($sSqlFormaAvaliacao);

      if ($oDaoFormaAvaliacao->numrows > 0) {

        $oFormaAvaliacao             = db_utils::fieldsMemory($rsFormaAvaliacao, 0);
        $this->iCodigo               = $oFormaAvaliacao->ed37_i_codigo;
        $this->sDescricao            = $oFormaAvaliacao->ed37_c_descr;
        $this->sTipo                 = trim($oFormaAvaliacao->ed37_c_tipo);
        $this->iMenorValor           = $oFormaAvaliacao->ed37_i_menorvalor;
        $this->iMaiorValor           = $oFormaAvaliacao->ed37_i_maiorvalor;
        $this->mAproveitamentoMinimo = $oFormaAvaliacao->ed37_c_minimoaprov;
        $this->nVariacao             = $oFormaAvaliacao->ed37_i_variacao;
      }
    }
  }

  /**
   * Retorna o codigo sequencial da forma de avaliacao
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * atribui uma descricao a forma de avaliacao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }

  /**
   * retorna a descricao da forma de avaliacao
   * @return string
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * atribui um tipo a forma de avaliacao
   * @param string $sTipo
   */
  public function setTipo($sTipo) {

    $this->sTipo = $sTipo;
  }

  /**
   * Retorna o tipo da forma de avaliacao
   * os tipos que retorna sao :
   * NIVEL, PARECER, NOTA
   * return string
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * Define menor valor que pode ter uma forma de avaliacao.
   * Valido somente para tipo: Nota, para outros assume valor 0
   * @param integer $iMenorValor
   */
  public function setMenorValor($iMenorValor) {

    $this->iMenorValor = $iMenorValor;
  }

  /**
   * Retorna menor valor que pode ter uma forma de avaliacao.
   * Valido somente para tipo: Nota, para outros assume valor 0
   * @return integer
   */
  public function getMenorValor() {

    return $this->iMenorValor;
  }

  /**
   * Define maior valor que pode ter uma forma de avaliacao.
   * Valido somente para tipo: Nota, para outros assume valor 0
   * @param integer $iMaiorValor
   */
  public function setMaiorValor($iMaiorValor) {

    $this->iMaiorValor = $iMaiorValor;
  }

  /**
   * Retorna maior valor que pode ter uma forma de avaliacao.
   * Valido somente para tipo: Nota, para outros assume valor 0
   * @return integer
   */
  public function getMaiorValor() {

    return $this->iMaiorValor;
  }

  /**
   * Define valor minimo para a aprovacao em uma forma de avaliacao
   * Valido para tipo: Nota e Nivel, para outros assume valor 0
   * @param mixed $mAproveitamentoMinimo
   */
  public function setAproveitamentoMinimo($mAproveitamentoMinimo) {

    $this->mAproveitamentoMinimo = $mAproveitamentoMinimo;
  }

  /**
   * Retorna valor minimo para aprovamento em uma forma de avaliacao
   * Valido para tipo: Nota e Nivel, para outros assume valor 0
   * @return mixed
   */
  public function getAproveitamentoMinino() {
    return $this->mAproveitamentoMinimo;
  }

  /**
   * Retorna a variacao
   * @return float
   */
  public function getVariacao() {
    return $this->nVariacao;
  }

  /**
   * Retorna os conceitos da Forma de Avaliacao
   * @return array
   */
  public function getConceitos() {

    if (count($this->aConceitos) == 0 && !empty($this->iCodigo)) {

      $oDaoConceitos = db_utils::getDao('conceito');
      $sWhere        = " ed39_i_formaavaliacao = {$this->getCodigo()} ";
      $sCampos       = "ed39_i_codigo, ed39_c_conceito, ed39_i_sequencia, ed39_c_nome, ed39_c_conceitodescr";
      $sSqlConceitos = $oDaoConceitos->sql_query_file(null, $sCampos, "ed39_i_sequencia", $sWhere);
      $rsConceitos   = $oDaoConceitos->sql_record($sSqlConceitos);
      $iTotalLinhas  = $oDaoConceitos->numrows;

      if ($iTotalLinhas > 0) {

        for ($i = 0; $i < $iTotalLinhas; $i++) {

          $oDadosConceito        = db_utils::fieldsMemory($rsConceitos, $i);
          $oConceito             = new stdClass();
          $oConceito->iCodigo    = $oDadosConceito->ed39_i_codigo;
          $oConceito->sConceito  = $oDadosConceito->ed39_c_conceito;
          $oConceito->iOrdem     = $oDadosConceito->ed39_i_sequencia;
          $oConceito->sNome      = $oDadosConceito->ed39_c_nome;
          $oConceito->sDescricao = $oDadosConceito->ed39_c_conceitodescr;
          $this->aConceitos[]    = $oConceito;
          unset($oDadosConceito);
        }
      }
    }
    return $this->aConceitos;
  }

  /**
   * Retorna um conceito pelo conceito
   * @param string $sConceito dados do conceito
   * @return stdclass|NULL
   */
  public function getConceitoMinimo() {

    if ($this->getTipo() != "NIVEL") {
      return null;
    }
    $aConceitos = $this->getConceitos();
    foreach ($aConceitos as $oConceito) {

      if ($oConceito->sConceito == $this->getAproveitamentoMinino()) {
        return $oConceito;
      }
    }
    return null;
  }
}