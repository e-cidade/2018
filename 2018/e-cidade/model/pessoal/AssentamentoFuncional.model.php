<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

class AssentamentoFuncional extends Assentamento {

  /**
   * Codigo do assentamento da vida funcional
   * @var Integer
   */
  private $iCodigoAssentamentoFuncional;

  /**
   * Objeto assentamento de efetividade que originou o assentamento de vida funcional
   * @var Assentamento
   */
  private $oAssentamentoEfetividade;

  /**
   * Construtor da classe
   *
   * @param Integer $iCodigoAssentamentoFuncional
   */
  public function __construct($iCodigoAssentamentoFuncional = null) {

    if(!empty($iCodigoAssentamentoFuncional)) {
      $this->iCodigoAssentamentoFuncional = $iCodigoAssentamentoFuncional;
    }
  }

  /**
   * Retorna o código do assentamento funcional
   *
   * @return Integer
   */
  public function getCodigoAssentamentoFuncional(){

    return $this->iCodigoAssentamentoFuncional;
  }

  /**
   * Define o código do assentamento funcional
   *
   * @param  Integer $iCodigoAssentamentoFuncional
   * @return Integer
   */
  public function setCodigoAssentamentoFuncional($iCodigoAssentamentoFuncional){

    $this->iCodigoAssentamentoFuncional = $iCodigoAssentamentoFuncional;
    return;
  }

  /**
   * Retorna o assentamento de efetividade que gerou o assentamento de vida funcional
   *
   * @param  no params
   * @return Assentamento
   */
  public function getAssentamentoEfetividade() {

    return $this->oAssentamentoEfetividade;
  }

  /**
   * Define o assentamento de efetividade que gerou o assentamento de vida funcional
   *
   * @param  Assentamento $oAssentamentoEfetividade
   * @return void
   */
  public function setAssentamentoEfetividade(Assentamento $oAssentamentoEfetividade) {

    $this->oAssentamentoEfetividade = $oAssentamentoEfetividade;
    return;
  }
}