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

    case "getConfiguracoes":

   	$oRetorno->configuracoes = array();
     $oInstituicao            = InstituicaoRepository::getInstituicaoSessao();

     $aDados = array_map(function(ConfiguracaoConsignado $oConfiguracao) use ($oInstituicao) {

       $oDados                      = new stdClass();
       $oDados->codigo_banco        = $oConfiguracao->getBanco()->getCodigo();
       $oDados->nome_banco          = urlencode($oConfiguracao->getBanco()->getNome());
       $oDados->codigo_layout       = $oConfiguracao->getLayout()->getCodigo();
       $oDados->nome_layout         = urlencode($oConfiguracao->getLayout()->getDescricao());
       $oDados->codigo_rubrica      = $oConfiguracao->getRubrica()->getCodigo();
       $oDados->nome_rubrica        = urlencode($oConfiguracao->getRubrica()->getDescricao());
       $oDados->iCodigoConfiguracao = $oConfiguracao->getCodigo();
      return $oDados;
     }, ConfiguracaoConsignadoRepository::getConfiguracaoInstituicao($oInstituicao));

     $oRetorno->configuracoes = $aDados;

      break;

    case "salvarConfiguracoes":

      $oConfiguracao = new ConfiguracaoConsignado();
      if(!empty($oParam->iCodigoConfiguracao)) {
        $oConfiguracao = new ConfiguracaoConsignado((int)$oParam->iCodigoConfiguracao);
      }
      $oConfiguracao->setBanco(new Banco($oParam->sBanco));
      $oConfiguracao->setLayout(new DBLayoutTXT((int)$oParam->iLayout));
      $oConfiguracao->setRubrica(RubricaRepository::getInstanciaByCodigo($oParam->sRubrica));
    	$oConfiguracao->salvar();
      $oRetorno->iCodigoConfiguracao = $oConfiguracao->getCodigo();
      $oRetorno->sMessage            = "Configuração salva com sucesso.";

      break;

    case 'removerConfiguracoes':

      if (empty($oParam->iCodigoConfiguracao)) {
        throw new ParameterException('Codigo da configuracao nao informado');
      }

      $oConfiguracao = new ConfiguracaoConsignado((int)$oParam->iCodigoConfiguracao);
      $oConfiguracao->remover();
      $oRetorno->sMessage = "Configuração removida com sucesso.";
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);