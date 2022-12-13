<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
* Classe com o natureza de uma taxa de diversos lançada pelo fiscal
*/
class NaturezaTaxaDiversosRepository extends BaseClassRepository {

  /**
   * Sobrescreve o atributo da classe pai para
   * manter apenas as referências da classe atual
   */
  protected static $oInstance;

  protected function make($iCodigo) {
    
    $oDaoTaxadiversos = new cl_taxadiversos;
    $sSqlTaxadiversos = $oDaoTaxadiversos->sql_query_file($iCodigo);
    $rsTaxadiversos   = db_query($sSqlTaxadiversos);

    if(!$rsTaxadiversos) {
      throw new DBException("Ocorreu um erro ao buscar a taxa de diversos.");
    }

    if(pg_num_rows($rsTaxadiversos) == 0) {
      throw new BusinessException("Não há taxa para o código informado.");
    }

    $oNaturezaTaxaDiversos = new NaturezaTaxaDiversos;

    db_utils::makeFromRecord($rsTaxadiversos, function ($oDados) use ($oNaturezaTaxaDiversos) {

      $oNaturezaTaxaDiversos->setCodigo            ($oDados->y119_sequencial);
      $oNaturezaTaxaDiversos->setGrupoTaxaDiversos (GrupoTaxaDiversosRepository::getInstanciaPorCodigo($oDados->y119_grupotaxadiversos));
      $oNaturezaTaxaDiversos->setNatureza          ($oDados->y119_natureza);
      $oNaturezaTaxaDiversos->setFormula           ($oDados->y119_formula);
      $oNaturezaTaxaDiversos->setUnidade           ($oDados->y119_unidade);
      $oNaturezaTaxaDiversos->setTipoPeriodo       ($oDados->y119_tipo_periodo);
      $oNaturezaTaxaDiversos->setTipoCalculo       ($oDados->y119_tipo_calculo);
    }, 0);
    
    return $oNaturezaTaxaDiversos;
  }
}
