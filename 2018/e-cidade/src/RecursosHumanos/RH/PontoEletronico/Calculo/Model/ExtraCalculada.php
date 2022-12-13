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
 * Class ExtraCalculada
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author John Lenon Reis <john.reis@dbseller.com.br>
 */
class ExtraCalculada {
  /**
   * @var int
   */
  private $iMinutos;

  /**
   * Constante da classe Extra
   * @var int
   */
  private $iTipo;

  /**
   * @return int
   */
  public function getMinutos()
  {
    return $this->iMinutos;
  }

  /**
   * @param int $iMinutos
   */
  public function setMinutos($iMinutos)
  {
    $this->iMinutos = $iMinutos;
  }

  /**
   * @return int
   */
  public function getTipo()
  {
    return $this->iTipo;
  }

  /**
   * @param int $iTipo
   */
  public function setTipo($iTipo)
  {
    $this->iTipo = $iTipo;
  }



  /**
   * ExtraCalculada constructor.
   * @param int $iMinutos
   * @param int $iTipo
   */
  public function __construct($iMinutos, $iTipo)
  {
    $this->iMinutos = $iMinutos;
    $this->iTipo = $iTipo;
  }

}
