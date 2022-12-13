<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case 'exportar':

      if(empty($oParam->iBanco)) {
        throw new ParameterException("Não foi informado o banco para gerar o arquivo de retorno.");
      }

      if(empty($oParam->iAno)) {
        throw new ParameterException("Não foi informado o ano para gerar o arquivo de retorno.");
      }

      if(empty($oParam->iMes)) {
        throw new ParameterException("Não foi informado o mês para gerar o arquivo de retorno.");
      }

      $oExportacao = ExportacaoArquivoConsignadoFactory::getByBanco(new Banco($oParam->iBanco), new DBCompetencia($oParam->iAno, $oParam->iMes));
      
      $oRetorno->sArquivo = $oExportacao->processar();
      $oRetorno->sMessage = 'Arquivo de retorno processado com sucesso.';
      break;

  }
  db_fim_transacao(false);
} catch (Exception $eErro) {


  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);