<?php
/**
 *  E-cidade Software Publico para Gestao Municipal
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

/**
 * Consulta a situação do debitos
 * @author Luiz Marcelo Schmitt <luiz.marcelo@dbseller.com.br>
 */
class PesquisaSituacaoGuias {

  public function __construct(){}

  public function statusDebitos($aDadosSituacao) {

    $aRetorno = array();

    if (is_array($aDadosSituacao)) {

      foreach($aDadosSituacao as $iKey => $aSituacao) {

        $sSql            = "SELECT rtstatus AS situacao FROM fc_statusdebitos({$aSituacao['iNumpre']}, {$aSituacao['iNumpar']});";
        $rsStatusDebitos = db_query($sSql);

        $oDados          = db_utils::fieldsMemory($rsStatusDebitos, 0);
        $aRetorno[$iKey] = $oDados->situacao;
      }
    }

    return $aRetorno;
  }
}