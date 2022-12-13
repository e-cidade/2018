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
* Classe Repository para manipulação do Grupo das taxas de diversos
*/
class GrupoTaxaDiversosRepository extends BaseClassRepository {

  /**
   * Sobrescreve o atributo da classe pai para
   * manter apenas as referências da classe atual
   */
  protected static $oInstance;
  
  
  protected function make($iCodigo) {

    $oDaoGrupoTaxadiversos = new cl_grupotaxadiversos;
    $sSqlGrupoTaxadiversos = $oDaoGrupoTaxadiversos->sql_query_file($iCodigo);
    $rsGrupoTaxadiversos   = db_query($sSqlGrupoTaxadiversos);

    if(!$rsGrupoTaxadiversos) {
      throw new DBException("Ocorreu um erro ao buscar o lançamento de taxa de diversos.");
    }

    if(pg_num_rows($rsGrupoTaxadiversos) == 0) {
      throw new BusinessException("Não há lançamento de taxa para o código informado.");
    }

    $oGrupoTaxaDiversos = new GrupoTaxaDiversos;

    db_utils::makeFromRecord($rsGrupoTaxadiversos, function ($oDados) use ($oGrupoTaxaDiversos) {

      $oGrupoTaxaDiversos->setCodigo            ($oDados->y118_sequencial);
      $oGrupoTaxaDiversos->setDescricao         ($oDados->y118_descricao);
      $oGrupoTaxaDiversos->setCodigoInflator    ($oDados->y118_inflator);
      $oGrupoTaxaDiversos->setCodigoProcedencia ($oDados->y118_procedencia);

    }, 0);
    
    return $oGrupoTaxaDiversos;
  }
}
