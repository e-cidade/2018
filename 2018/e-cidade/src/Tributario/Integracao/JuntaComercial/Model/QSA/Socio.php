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

namespace ECidade\Tributario\Integracao\JuntaComercial\Model\QSA;
use ECidade\Tributario\Integracao\JuntaComercial\Model\QSA;

/**
 * Class Socio
 * @package ECidade\Tributario\Integracao\JuntaComercial\Model\QSA
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class Socio extends QSA
{
  const TIPO_SOCIO = 1;

  private $valorCapital = "0";

  public function __construct(\CgmBase $cgm, $tipoRelacionamento, $valorCapital)
  {
    parent::__construct($cgm, $tipoRelacionamento);
    $this->setValorCapital($valorCapital);
  }

  /**
   * @return mixed
   */
  public function getValorCapital()
  {
    return $this->valorCapital;
  }

  /**
   * @param mixed $valorCapital
   */
  public function setValorCapital($valorCapital)
  {
    $valorCapital = trim($valorCapital);
    if (!empty($valorCapital) && is_numeric($valorCapital)) {
      $this->valorCapital = $valorCapital;
    }
  }
}
