<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");  

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->sExecuta) {

    case "fotoCgm":

      $oDaoCgmfoto = db_utils::getDao("cgmfoto");
      $rsCgmfoto = $oDaoCgmfoto->sql_record( $oDaoCgmfoto->sql_query_file( null, 
                                                                           "z16_arquivofoto"
                                                                           . ", (select sum(length(data)) from pg_largeobject where loid = z16_arquivofoto) as tamanho", 
                                                                           null,
                                                                           "z16_numcgm = {$oParam->iCgm}"
                                                                           . " and z16_principal is true"
                                                                           . " and z16_fotoativa is true" ) );

      $oRetorno->sFoto = null;

      if ($rsCgmfoto) {

        $oFoto = db_utils::fieldsMemory($rsCgmfoto, 0);
        $rsFoto = pg_lo_open($conn, $oFoto->z16_arquivofoto, "r");

        $oRetorno->sFoto = base64_encode(pg_lo_read($rsFoto, $oFoto->tamanho));
      }

      break;
  }
  
  db_fim_transacao(false);
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);

?>