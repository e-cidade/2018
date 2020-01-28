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
 * Valor do aproveitamento quando o tipo eh NIVEL
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.6 $
 */
require_once modification("model/educacao/avaliacao/ValorAproveitamento.model.php");
final class ValorAproveitamentoNivel extends ValorAproveitamento {

  private $iOrdem;

  protected $lUtilizaNivel = true;
  public function __construct($mValor='', $iOrdem = '') {

    $this->mValorAproveitamento     = $mValor;
    $this->mValorAproveitamentoReal = $mValor;
    $this->iOrdem                   = $iOrdem;
  }

  public function setOrdem($iOrdem) {

    $this->iOrdem = $iOrdem;
  }

  public function getOrdem() {

    return $this->iOrdem;
  }
}