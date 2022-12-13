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

class Isencoes extends Arquivo {

  /**
   * Isencoes constructor.
   */
  public function __construct() {

    $this->iCodigoLayout = 269;
    $this->sNomeArquivo = 'isencoes';
  }

  /**
   * @return \stdClass[]
   * @throws \DBException
   */
  public function getDados() {

    $oTipoIsencao = new \cl_aguaisencaotipo;
    $sSql = $oTipoIsencao->sql_query_file();
    $rsTiposIsencao = db_query($sSql);
    if (!$rsTiposIsencao) {
      throw new \DBException('Não foi possível buscar os tipos de isenção.');
    }

    $aTiposIsencao = pg_fetch_all($rsTiposIsencao);
    $aIsencoes = array();
    foreach ($aTiposIsencao as $aTipoIsencao) {

      $aIsencoes[] = (object) array(
        'codigo'       => $aTipoIsencao['x29_codisencaotipo'],
        'descricao'    => $aTipoIsencao['x29_descr'],
        'tipo_isencao' => $aTipoIsencao['x29_tipo'],
      );
    }

    return $aIsencoes;
  }
}
