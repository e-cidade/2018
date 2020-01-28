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
 * Avaliacao atribuida
 * Retorna a propria avaliacao lancada para o resultado
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.8 $
 */
require_once(modification("model/educacao/avaliacao/FormaObtencao.model.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
class FormaObtencaoAtribuida extends FormaObtencao implements IFormaObtencao {

  /**
   * Define as notas que ira ser usada no calculo
   * Recebe um array de AvaliacaoAproveitamento
   * @param array   $aAproveitamentos
   * @param integer $iAno
   * @return string
   */
  public function processarResultado( $aAproveitamentos, $iAno ) {

    $mAproveitamento = new ValorAproveitamentoParecer();
    $aPeriodos       = $this->getElementosParaCalculo( $aAproveitamentos, $iAno );

    /**
     * Percorremos o array de aproveitamentos buscando o valor do aproveitamento
     */
    foreach ($aAproveitamentos as $oAproveitemento) {

      if ($oAproveitemento->getOrdemSequencia() == $this->getResultadoAvaliacao()->getOrdemSequencia()) {
        $mAproveitamento = $oAproveitemento->getValorAproveitamento();
      }
    }
    /**
     * Devolvemos as notas Originais
     */
    $this->acertaNotasSubstituidasParaCalculo($aPeriodos);
    return $mAproveitamento;
  }
}