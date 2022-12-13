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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("classes/db_db_sysregrasacessoip_classe.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno          = new stdClass();
$oRetorno->message = '';
$oRetorno->erro    = false;

switch ($oParam->exec) {

    case "gerarToken":
        db_inicio_transacao();
        try {

            if (empty($oParam->idRegra)) {
                throw new ParameterException("Não foi possível gerar um token de acesso.\nO código da regra não foi informado.");
            }

            $sToken = hash("sha256", getmypid().time());
            $oDaoRegraIp = new cl_db_sysregrasacessoip;
            $oSqlRegraIp = $oDaoRegraIp->sql_query($oParam->idRegra);

            if (!pg_query($oSqlRegraIp)){
                throw new Exception("Regra [{$oParam->idRegra}] não está registrada.");
            }
            $oDaoRegraIp->db48_idacesso     = $oParam->idRegra;
            $oDaoRegraIp->db48_tokenpublico = $sToken;
            $oDaoRegraIp->alterar($oParam->idRegra);

            $oRetorno->token = $sToken;
            db_fim_transacao();
        } catch (Exception $oErro) {
            db_fim_transacao(true);
            $oRetorno->erro     = true;
            $oRetorno->message  = urlencode($oErro->getMessage());
        }
        break;
}

echo $oJson->encode($oRetorno);
