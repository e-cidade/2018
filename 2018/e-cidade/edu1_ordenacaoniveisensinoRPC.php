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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_ensino_classe.php");
include("dbforms/db_funcoes.php");

$oDaoEnsino = new cl_ensino();
$oPost      = db_utils::postMemory($_POST);

switch ( $oPost->sAction ) {

  case 'UpdateNiveisEnsino':

    $aRegistros = explode(",",$oPost->sRegistros);
    $aRegistros = array_filter( $aRegistros ); //Remove elementos vazios

    for ( $iCont = 0; $iCont < count( $aRegistros ); $iCont++ ){

      db_inicio_transacao();

      $oDaoEnsino->ed10_ordem     = ($iCont+1);
      $oDaoEnsino->ed10_i_codigo  = $aRegistros[$iCont];
      $oDaoEnsino->alterar( $aRegistros[$iCont] );

      db_fim_transacao();
    }

    $sMsg = "Alteraçao Efetuada com Sucesso!";

    if($oDaoEnsino->erro_status=="0"){
      $sMsg = $oDaoEnsino->erro_msg;
    }

    $oJson = new services_json();

    echo $oJson->encode( urlencode($sMsg) );

    break;
}