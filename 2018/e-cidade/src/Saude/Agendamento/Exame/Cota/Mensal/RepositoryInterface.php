<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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
namespace ECidade\Saude\Agendamento\Exame\Cota\Mensal;

use ECidade\Saude\Agendamento\Exame\Cota\Mensal;

interface RepositoryInterface
{
  /**
   * Adicionamos uma cota mensal
   *
   * @param Mensal
   */
  public function add(Mensal $oMensal);

  /**
   * removemos uma cota mensal
   *
   * @param Mensal
   */
  public function remove(Mensal $oMensal);

  /**
   * Buscamos a cota através das informção do grupo
   *
   * @param   \stdClass     Código do grupo
   * @return  Mensal        Objeto da cota mensal
   */
  public function getCotaByIdGrupo($iGrupo);

  /**
   * Criamos a query que consulta os dados do grupo de cotas
   *
   * @param  integer $iGrupo
   * @param  string  $sCampos
   * @param  string  $sGroupBy
   *
   * @return string           sql query
   */
  public function getQueryByGrupo($iGrupo, $sCampos, $sGroupBy);
}