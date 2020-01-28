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


class Evento
{

  /**
   * C�digo do evento
   * @var integer $iC�digo
   */
  private $iCodigo;

  /**
   * Descri��o do evento
   * @var string $sEvento
   */
  private $sEvento;

  /**
   * @return int
   */
  public function getCodigo()
  {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo)
  {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string
   */
  public function getEvento()
  {
    return $this->sEvento;
  }

  /**
   * @param string $sEvento
   */
  public function setEvento($sEvento)
  {
    $this->sEvento = $sEvento;
  }

  public function __construct($iCodigo, $sEvento){

    $this->iCodigo = $iCodigo;
    $this->sEvento = utf8_decode($sEvento);
  }
}
