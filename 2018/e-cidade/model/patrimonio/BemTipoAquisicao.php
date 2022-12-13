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
 * Class BemTipoAquisicao
 */
class BemTipoAquisicao {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @param $iCodigo
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoTipoDepreciacao = db_utils::getDao("benstipoaquisicao");

      $sSql                = $oDaoTipoDepreciacao->sql_query_file($iCodigo);
      $rsTipoDeprciacao    = $oDaoTipoDepreciacao->sql_record($sSql);

      if($oDaoTipoDepreciacao->numrows == 1){

        $oTipoDepreciacao = db_utils::fieldsMemory($rsTipoDeprciacao, 0);

        $this->setCodigo($oTipoDepreciacao->t45_sequencial);
        $this->setDescricao($oTipoDepreciacao->t45_descricao);
      }
    }
  }

  /**
   * Código da Aquisição
   * @param $iCodigo
   */
  public function setCodigo($iCodigo) {
      $this->iCodigo = $iCodigo;
  }

  /**
   * Descrição da Aquisição
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
      $this->sDescricao = $sDescricao;
  }


  /**
   * Código da Aquisição
   * @return integer
   */
  public function getCodigo() {
      return $this->iCodigo;
  }

  /**
   * Descrição da Aquisição
   * @return String
   */
  public function getDescricao() {
      return $this->sDescricao;
  }

}