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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';
$sMensagens         = "configuracao.configuracao.con4_parametros";

try {

  switch ($oParam->exec) {

    case "salvar":

      $oPreferenciaCliente = new PreferenciaCliente();

      if ($oParam->sSkinDefault) {

        $oPreferenciaCliente->setSkinDefault($oParam->sSkinDefault);
      }

      if (!empty($oParam->iTentativasLogin)) {

        $oPreferenciaCliente->setTentativasLogin($oParam->iTentativasLogin);
      }

      if (!empty($oParam->iDiasExpiraToken)) {

        $oPreferenciaCliente->setDiasExpiraToken($oParam->iDiasExpiraToken);
      }

      if($oParam->iTentativasLogin == 0) {

        throw new Exception(_M("{$sMensagens}.valida_zero", (Object) array( "sCampo" => "Tentativas de Login")));      
      }
      
      if($oParam->iDiasExpiraToken == 0) {
        
        throw new Exception(_M("{$sMensagens}.valida_zero", (Object) array( "sCampo" => "Dias para expirar link de ativação")));       
      }  
      if(!$oPreferenciaCliente->salvarPreferencias()) {

        throw new Exception(_M("{$sMensagens}.configuracao_nao_salva"));
      }

      $oRetorno->sMessage = urlencode(_M("{$sMensagens}.configuracao_salva"));

    break;

    case "aplicarSkinDefault":

      if (!isset($oParam->sSkinDefault)) {
        throw new Exception(_M("{$sMensagens}.skin_nao_informado"));
      }

      if (is_dir(PreferenciaUsuario::CAMINHO_ARQUIVO)) {

        $aDiretorio = scandir(PreferenciaUsuario::CAMINHO_ARQUIVO);
        foreach ($aDiretorio as $sFile) {

          if (is_file(PreferenciaUsuario::CAMINHO_ARQUIVO . $sFile)) {

            $oPreferenciaUsuario       = json_decode(file_get_contents(PreferenciaUsuario::CAMINHO_ARQUIVO . $sFile));
            $oPreferenciaUsuario->skin = $oParam->sSkinDefault;
            $lSalvo                    = file_put_contents(PreferenciaUsuario::CAMINHO_ARQUIVO . $sFile, json_encode($oPreferenciaUsuario));
            if(!$lSalvo) {

              throw new Exception(_M("{$sMensagens}.alteracao_nao_efetuada", (Object) array("sArquivo" => $sFile)));
            }
          }
        }
      }

      $oRetorno->sMessage = urlencode(_M("{$sMensagens}.configuracao_aplicada"));

      /**
       * Limpa o cache dos usuários
       */
      DBMenu::limpaCache();

    break;

  }

} catch (Exception $eErro) {

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);