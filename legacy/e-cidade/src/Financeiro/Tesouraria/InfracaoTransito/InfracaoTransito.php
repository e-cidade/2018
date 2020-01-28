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

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito;


/**
 * Class InfracaoTransito
 * Classe que representa o vinculo dos codigos de infracao com os niveis
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito
 */
class InfracaoTransito
{
  /**
   * @var int
   */
  private $iId;

  /**
   * @var string
   */
  private $sCodigoInfracao;

  /**
   * @var int
   */
  private $iNivel;

  /**
   * @var string
   */
  private $sDescricao;

  public function __construct()
  {

  }

  /**
   * @return int
   */
  public function getId()
  {
    return $this->iId;
  }

  /**
   * @param int $iId
   */
  public function setId($iId)
  {
    $this->iId = $iId;
  }

  /**
   * @return string
   */
  public function getCodigoInfracao()
  {
    return $this->sCodigoInfracao;
  }

  /**
   * @param string $sCodigoInfracao
   */
  public function setCodigoInfracao($sCodigoInfracao)
  {
    $this->sCodigoInfracao = $sCodigoInfracao;
  }

  /**
   * @return int
   */
  public function getNivel()
  {
    return $this->iNivel;
  }

  /**
   * @param int $iNivel
   */
  public function setNivel($iNivel)
  {
    $this->iNivel = $iNivel;
  }

  /**
   * @return string
   */
  public function getDescricao()
  {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao)
  {
    $this->sDescricao = $sDescricao;
  }
}