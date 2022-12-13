<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2017  DBselller Servicos de Informatica
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

namespace ECidade\Tributario\Juridico\Repository;

use DBException;

class Inicial
{
    public function isReciboEmitidoDebito($iInicial)
    {
        $sSql  = " select 1                                                                           ";
        $sSql .= "   from inicial                                                                     ";
        $sSql .= "        inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial ";
        $sSql .= "        inner join recibopaga on recibopaga.k00_numpre = inicialnumpre.v59_numpre   ";
        $sSql .= "  where inicial.v50_inicial = {$iInicial}                                           ";
        $sSql .= "  limit 1                                                                           ";
        
        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar dados da inicial {$iInicial}");
        }

        $lReciboEmitido = false;

        if (pg_num_rows($rsResult) > 0) {
            $lReciboEmitido = true;
        }

        return $lReciboEmitido;
    }

    public function isDebitoPago($iInicial)
    {
        $sSql  = " select 1                                                                           ";
        $sSql .= "   from inicial                                                                     ";
        $sSql .= "        inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial ";
        $sSql .= "        inner join arrepaga on arrepaga.k00_numpre = inicialnumpre.v59_numpre       ";
        $sSql .= "  where inicial.v50_inicial = {$iInicial}                                           ";
        $sSql .= "  limit 1                                                                           ";
        
        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar dados da inicial {$iInicial}");
        }

        $lDebitoPago = false;

        if (pg_num_rows($rsResult) > 0) {
            $lDebitoPago = true;
        }

        return $lDebitoPago;
    }
}
