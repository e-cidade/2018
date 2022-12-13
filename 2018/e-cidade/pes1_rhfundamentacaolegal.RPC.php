<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                  www.dbseller.com.br
 *               e-cidade@dbseller.com.br
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
 *
 * @author $Author: dbmarcos $
 * @version $Revision: 1.1 $
 */

require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/JSON.php';

define('MENSAGENS', 'recursoshumanos.pessoal.pes1_rhfundamentacaolegal.');

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->message  = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    /**
     * Verifica se a fundamentação legal possui algum vínculo com as rubricas.
     * 
     * @param Integer $iCodigoFundamentacao
     * @return Boolean $lRubricas
     */
    case 'verificarVinculoRubrica':
      
      $iCodigoFundamentacao = $oParam->iCodigoFundamentacao;
      $oDaoFundamentacao    = new cl_rhfundamentacaolegal();
      $sSqlFundamentacao    = $oDaoFundamentacao->sql_query_fundamentacao_rubrica($iCodigoFundamentacao, "rh137_sequencial");
      $rsFundamentacao      = db_query($sSqlFundamentacao);
      
      if (!$rsFundamentacao) {
        throw new DBException(_M(MENSAGENS . "erro_buscar_vinculo_rubrica"));
      }
      
      $oRetorno->lRubricas = false;
      
      if (pg_numrows($rsFundamentacao) > 0) {
        
        $oRetorno->message   = urlencode(_M(MENSAGENS . "confirma_excluir_fundamentacao"));
        $oRetorno->lRubricas = true;
      }
      
      break;
  }
  
  db_fim_transacao();
    
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->message = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);