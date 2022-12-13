<?php
/*
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

/**
 * Classe para manipulação de Nome da Classe
 *
 * @package Configuracao
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.1 $
 */

class FaixaValor {

  /**
   * Representa o valor de Inicio da faixa do IRRF
   */
  protected $nValorInicio;

  /**
   * Representa o valor de Fim da faixa do IRRF
   */
  protected $nValorFim;

  /**
   * Define o Valor de Início da Faixa
   * @param Number
   */
  public function setInicio ($nValorInicio) {
    $this->nValorInicio = (float)$nValorInicio;
  }

  /**
   * Retorna o Valor de Início da Faixa
   * @return Number
   */
  public function getInicio () {
    return $this->nValorInicio;
  }

  /**
   * Define o Valor de Fim da Faixa
   * @param Number
   */
  public function setFim ($nValorFim) {
    $this->nValorFim = (float)$nValorFim;
  }

  /**
   * Retorna o Valor de Fim da Faixa
   * @return Number
   */
  public function getFim () {
    return $this->nValorFim;
  }
}

//$Id: FaixaValor.model.php,v 1.1 2016/01/27 17:34:31 dbrafael.nery Exp $
