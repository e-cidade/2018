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
 * Value Object
 * Class ProgramaMetaIndice
 */
class ProgramaMetaIndice {

  /**
   * @var integer
   */
  private $ano;

  /**
   * @var float
   */
  private $indice;

  /**
   * @var string
   */
  private $unidadeMedida;

  /**
   * @return int
   */
  public function getAno() {
    return $this->ano;
  }

  /**
   * @param int $ano
   */
  public function setAno($ano) {
    $this->ano = $ano;
  }

  /**
   * @return float
   */
  public function getIndice() {
    return $this->indice;
  }

  /**
   * @param float $indice
   */
  public function setIndice($indice) {
    $this->indice = $indice;
  }

  /**
   * @return string
   */
  public function getUnidadeMedida() {
    return $this->unidadeMedida;
  }

  /**
   * @param string $unidadeMedida
   */
  public function setUnidadeMedida($unidadeMedida) {
    $this->unidadeMedida = $unidadeMedida;
  }

  /**
   * Exclui um índice
   * @throws DBException
   */
  public function excluir() {

    $daoOrcMetaIndice = new cl_orcmetaindices();
    $daoOrcMetaIndice->excluir(null, "o154_indice = {$this->indice}");

    if($daoOrcMetaIndice->erro_status == '0') {
      throw new DBException('Erro ao remover o índice da Meta.');
    }
  }
}