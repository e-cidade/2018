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

namespace ECidade\Tributario\Integracao\JuntaComercial\Model;

/**
 * Class QSA
 * @package ECidade\Tributario\Integracao\JuntaComercial\Model
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
abstract class QSA
{
  /**
   * @var \CgmBase
   */
  private $cgm;
  private $tipoRelacionamento;

  public function __construct(\CgmBase $cgm, $tipoRelacionamento)
  {
    $this->cgm = $cgm;
    $this->tipoRelacionamento = $tipoRelacionamento;
  }

  /**
   * @return \CgmBase
   */
  public function getCgm()
  {
    return $this->cgm;
  }

  /**
   * @param \CgmBase $cgm
   */
  public function setCgm($cgm)
  {
    $this->cgm = $cgm;
  }

  /**
   * @return mixed
   */
  public function getTipoRelacionamento()
  {
    return $this->tipoRelacionamento;
  }

  /**
   * @param mixed $tipoRelacionamento
   */
  public function setTipoRelacionamento($tipoRelacionamento)
  {
    $this->tipoRelacionamento = $tipoRelacionamento;
  }
}