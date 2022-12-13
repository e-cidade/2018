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

namespace ECidade\Patrimonial\Licitacao\Licitacon;

use DBDate;

/**
 * ConfiguracaoLicitacon
 * Configurações referente ao LicitaCon.
 */
class Versao
{

    private $versao;

    /**
     * ConfiguracaoLicitacon constructor.
     *
     * @param DBDate $dataGeracao Data da geração do arquivo para o LicitaCon.
     */
    public function __construct(DBDate $dataGeracao)
    {
        $this->versao = $this->getVersaoPorDataGeracao($dataGeracao);
    }

    /**
     * Define qual a versão do LicitaCon de acordo com a data da geração do arquivo.
     * @param DBDate $dataGeracao
     *
     * @return string
     */
    private function getVersaoPorDataGeracao(DBDate $dataGeracao)
    {
        $versao = '1.2';
        $ano = $dataGeracao->getAno();
        $dezembro = $dataGeracao->getMes() == 12;
        $anoLayout13 = 2016;
        $anoLayout14 = 2017;

        if ($ano > $anoLayout13 || ($ano == $anoLayout13 && $dezembro)) {
            $versao = '1.3';
        }

        if ($ano > $anoLayout14 || ($ano == $anoLayout14 && $dezembro)) {
            $versao = '1.4';
        }

        return $versao;
    }

    /**
     * Versão do LicitaCon.
     * @return string
     */
    public function getVersao()
    {
        return $this->versao;
    }
}