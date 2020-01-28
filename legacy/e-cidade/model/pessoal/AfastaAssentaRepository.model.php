<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

class AfastaAssentaRepository {

  public static function getAfastamentosPorAssentamento(Assentamento $oAssentamento) {

    $aLista = array();
    $oDaoAfastaAssenta   = new cl_afastaassenta;
    $sWhereAfastaAssenta = " h81_assenta = ". $oAssentamento->getCodigo();
    $sSqlAfastaAssenta   = $oDaoAfastaAssenta->sql_query_file(null, "*", null, $sWhereAfastaAssenta);
    $rsAfastaAssenta     = db_query($sSqlAfastaAssenta);

    if(!$rsAfastaAssenta) {
      throw new DBException("Erro ao buscar vínculo entre assentamentos e afastamentos.");
    }

    if(pg_num_rows($rsAfastaAssenta) > 0) {

      for ($iIndAfastaAssenta=0; $iIndAfastaAssenta < pg_num_rows($rsAfastaAssenta) ; $iIndAfastaAssenta++) {
        $aLista[] = AfastamentoRepository::getInstanciaPorCodigo(db_utils::fieldsMemory($rsAfastaAssenta, $iIndAfastaAssenta)->h81_afasta);
      }

      return $aLista;
    }

    return false;
  }
}