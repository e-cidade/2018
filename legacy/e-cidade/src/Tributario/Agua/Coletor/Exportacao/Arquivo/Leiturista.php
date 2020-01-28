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

class Leiturista extends Arquivo {

  /**
   * Leiturista constructor.
   */
  public function __construct() {

    $this->iCodigoLayout = 267;
    $this->sNomeArquivo = 'leiturista';
  }

  /**
   * @return array
   * @throws \DBException
   */
  public function getDados() {

    $oDaoLeiturista = new \cl_agualeiturista();
    $sSqlLeiturista = $oDaoLeiturista->sql_query(null, 'x16_numcgm, z01_nome, x16_senha');
    $rsLeiturista   = db_query($sSqlLeiturista);

    if (!$rsLeiturista) {
      throw new \DBException('Não foi possível encontrar as informações de Leiturista.');
    }

    if (pg_num_rows($rsLeiturista) == 0) {
      return array();
    }

    $aLeituristas = array();
    while ($oLeiturista = pg_fetch_object($rsLeiturista)) {

      $aLeituristas[] = (object) array(
        'codigo' => $oLeiturista->x16_numcgm,
        'nome' => trim($oLeiturista->z01_nome),
        'senha' => $oLeiturista->x16_senha
      );
    }

    return $aLeituristas;
  }
}
