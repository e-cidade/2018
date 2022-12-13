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
 * Especializa��o para valores de aproveitamento
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
   * Em casos onde sistema esta configurado para N�O EXIBIR O RESULTADO PROPORCIONAL (Par�mtros Globais - campo
   * Apresentar nota proporcional = N�O), o valor presente em $mValorAproveitamentoReal � a nota original do
   * aluno no per�odo. Enquando $mValorAproveitamento corresponde ao valor proporcional.
   *
   * O valor proprocional ser� sempre utilizado para c�lculo.
   *
   * Se o par�metro (Apresentar nota proporcional) estiver com seu valor default (SIM),
   * $mValorAproveitamentoReal ser� igual a $mValorAproveitamento
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