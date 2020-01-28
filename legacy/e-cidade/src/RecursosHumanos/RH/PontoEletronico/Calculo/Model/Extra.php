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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model;

/**
 * Class Extra
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author John Lenon Reis <john.reis@dbseller.com.br>
 */
class Extra {

  const TIPO_DIURNA  = 1;
  const TIPO_NOTURNA = 0;

  /**
   * Total de hotas noturnas em minutos
   * @var integer
   */
  private $iNoturnas;

  /**
   * Total de hotas diurnas em minutos
   * @var integer
   */
  private $iDiurnas;

  /**
   * @var integer
   */
  protected $iLimite;

  /**
   * @return int
   */

  public function getLimite()
  {
    return $this->iLimite;
  }

  /**
   * @param int $iLimite
   */
  public function setLimite($iLimite)
  {
    $this->iLimite = $iLimite;
  }




  /**
   * Define o limite de horas
   * Extra constructor
   * @param $iLimite
   */
  public function __construct($iLimite)
  {
    $this->iLimite = $iLimite;
  }

  /**
   * Incrementa a hora, respeitando o limite, caso nao consiga acrescentar, retorna o resto
   * @param ExtraCalculada $extraCalculada
   * @return int
   * @internal param $iMinutos
   * @internal param $iTipo
   */
  public function incrementar(ExtraCalculada $extraCalculada) {

    $iResto = 0;
    $iMinutosAdicionar = $extraCalculada->getMinutos();
    $iTotalMinutos = $this->iLimite - ($this->getTotal() + $iMinutosAdicionar);

    if ($iTotalMinutos < 0) {

      $iMinutosAdicionar -= abs($iTotalMinutos);
      $iResto = abs($iTotalMinutos);
    }

    switch ($extraCalculada->getTipo()){
      case self::TIPO_DIURNA:

        $this->iDiurnas += $iMinutosAdicionar;
        break;
      case self::TIPO_NOTURNA:
        $this->iNoturnas += $iMinutosAdicionar;
        break;
    }
    return $iResto;
  }

  public function atingeLimite($iAdicional = 0){
    if(($this->iDiurnas + $this->iNoturnas + $iAdicional) >= $this->iLimite){
      return true;
    }
    return false;
  }

  /**
   * @return int
   */
  public function getNoturnas()
  {
    return $this->iNoturnas;
  }

  /**
   * @param int $iNoturnas
   */
  public function setNoturnas($iNoturnas)
  {
    $this->iNoturnas = $iNoturnas;
  }

  /**
   * @return int
   */
  public function getDiurnas()
  {
    return $this->iDiurnas;
  }

  /**
   * @param int $iDiurnas
   */
  public function setDiurnas($iDiurnas)
  {
    $this->iDiurnas = $iDiurnas;
  }

  /**
   * Retorna o total de horas
   * @return int
   */
  public function getTotal()
  {
    return $this->getDiurnas() + $this->getNoturnas();
  }

  public function deveCalcular() {
    return ($this->getTotal() < $this->iLimite);
  }
}
