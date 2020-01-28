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
 * Especialização para valores de aproveitamento
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.5 $
 */
abstract class ValorAproveitamento {

  /**
   * Recebe o valor do aproveitamento do aluno.
   */
  protected $mValorAproveitamento;

  /**
   * Recebe o valor real do aproveitamento do aluno.
   * Em casos onde sistema esta configurado para NÃO EXIBIR O RESULTADO PROPORCIONAL (Parâmtros Globais - campo
   * Apresentar nota proporcional = NÃO), o valor presente em $mValorAproveitamentoReal é a nota original do
   * aluno no período. Enquando $mValorAproveitamento corresponde ao valor proporcional.
   *
   * O valor proprocional será sempre utilizado para cálculo.
   *
   * Se o parâmetro (Apresentar nota proporcional) estiver com seu valor default (SIM),
   * $mValorAproveitamentoReal será igual a $mValorAproveitamento
   */
  protected $mValorAproveitamentoReal;

  protected $lUtilizaNivel = false;

  public function setAproveitamento($mValorAproveitamento) {

    $this->mValorAproveitamento = $mValorAproveitamento;
  }

  public function getAproveitamento() {

    return $this->mValorAproveitamento;
  }

  public function hasOrdem() {
    return $this->lUtilizaNivel;
  }

  public function setAproveitamentoReal($mValorAproveitamentoReal) {

    $this->mValorAproveitamentoReal = $mValorAproveitamentoReal;
  }

  public function getAproveitamentoReal() {

    return $this->mValorAproveitamentoReal;
  }

}