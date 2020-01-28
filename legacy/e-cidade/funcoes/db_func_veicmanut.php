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

$campos = "distinct veicmanut.ve62_codigo,(veicmanut.ve62_numero||'/'||veicmanut.ve62_anousu)::varchar as ve62_numero, "
        . "case veicmanut.ve62_situacao when " . VeiculoManutencao::SITUACAO_PENDENTE . " then 'Pendente'::varchar when "
        . VeiculoManutencao::SITUACAO_REALIZADO . " then 'Realizado'::varchar end as ve62_situacao,"
        . "veicmanut.ve62_veiculos,veicmanut.ve62_dtmanut,veicmanut.ve62_vlrmobra,"
        . "veicmanut.ve62_vlrpecas,veicmanut.ve62_descr,veicmanut.ve62_notafisc,veicmanut.ve62_medida,ve28_descr as ve62_veiccadtiposervico,"
        . "veicmanut.ve62_usuario,veicmanut.ve62_data,veicmanut.ve62_hora";
?>