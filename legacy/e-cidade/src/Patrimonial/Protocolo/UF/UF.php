<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Patrimonial\Protocolo\UF;

class UF
{
  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sUF;

  /**
   * @var string
   */
  private $sNome;

  /**
   * @var string
   */
  private $sNomeExtenso;

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  /**
   * @return string
   */
  public function getUF() {
    return $this->sUf;
  }

  /**
   * @param string sUf
   */
  public function setUF($sUf) {
    $this->sUf = $sUf;
  }
  /**
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * @param string sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
  /**
   * @return string
   */
  public function getNomeExtenso() {
    return $this->sNomeExtenso;
  }

  /**
   * @param string sNomeExtenso
   */
  public function setNomeExtenso($sNomeExtenso) {
    $this->sNomeExtenso = $sNomeExtenso;
  }
}
