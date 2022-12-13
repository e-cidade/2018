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

namespace ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo;

class SituacoesLeitura extends Arquivo {

  /**
   * SituacoesLeitura constructor.
   */
  public function __construct() {

    $this->iCodigoLayout = 265;
    $this->sNomeArquivo = 'situacoes_leitura';
  }

  /**
   * @return array
   * @throws \DBException
   */
  public function getDados() {

    $oDaoAguaSitLeitura= new \cl_aguasitleitura();
    $sSqlAguaSitLeitura = $oDaoAguaSitLeitura->sql_query_file();
    $rsAguaSitLeitura = db_query($sSqlAguaSitLeitura);

    if (!$rsAguaSitLeitura) {
      throw new \DBException("Não foi possível obter as informações de Situação da Leitura.");
    }

    if (pg_num_rows($rsAguaSitLeitura) == 0) {
      return array();
    }

    $aSituacoesLeitura = array();
    while ($oSituacaoLeitura = pg_fetch_object($rsAguaSitLeitura)) {

      $aSituacoesLeitura[] = (object) array(
        'codigo' => $oSituacaoLeitura->x17_codigo,
        'descricao' => $oSituacaoLeitura->x17_descr,
        'regra' => $oSituacaoLeitura->x17_regra
      );
    }

    return $aSituacoesLeitura;
  }
}
