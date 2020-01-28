<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$iDepartamento     = db_getsession("DB_coddepto");

try {

  switch($oParam->exec) {
    
    /**
     * Persiste as informacoes referente a um local de atendimento
     * @param integer $iDepartamento - Codigo do departamento
     * @param string  $oParam->sDescricao - Descricao do local de atendimento
     * @param string  $oParam->sIdentificadorUnico - Identificador unico do local de atendimento
     * @param integer $oParam->iTipo - Tipo de atendimento do local
     * @param integer $oParam->iCodigo - Codigo do local de atendimento, caso ja tenha sido configurado
     * @return stdClass $oRetorno
     */
    case 'salvarLocalAtendimento':
      
      db_inicio_transacao();
      
      $oDBDepartamento         = DBDepartamentoRepository::getDBDepartamentoByCodigo($iDepartamento);
      $oLocalAtendimentoSocial = new LocalAtendimentoSocial();
      
      if (!empty($oParam->iCodigo)) {
        $oLocalAtendimentoSocial = new LocalAtendimentoSocial($oParam->iCodigo);
      }
      $oLocalAtendimentoSocial->setDescricao($oParam->sDescricao);
      $oLocalAtendimentoSocial->setIdentificadorUnico($oParam->sIdentificadorUnico);
      $oLocalAtendimentoSocial->setTipo($oParam->iTipo);
      $oLocalAtendimentoSocial->setDbDepart($oDBDepartamento);
      
      if (isset($oParam->iCodigo) && !empty($oParam->iCodigo)) {
        $oLocalAtendimentoSocial->setCodigo($oParam->iCodigo);
      }
      
      $oLocalAtendimentoSocial->salvar();
      DBDepartamentoRepository::removerDBDepartamento($oDBDepartamento);
      
      $oRetorno->message = urlencode("Dados do Local de Atendimento salvos com sucesso.");
      
      db_fim_transacao();
      break;
      
    /**
     * Retorna os dados do local de atendimento
     */
    case 'getDados':
      
      $oRetorno->iCodigo                = '';
      $oRetorno->sDescricao             = '';
      $oRetorno->iTipo                  = '';
      $oRetorno->sIdentificadorUnico    = '';
      $oRetorno->iDepartamento          = $iDepartamento;
      $oRetorno->sDescricaoDepartamento = '';
      $oRetorno->lTemVinculo            = false;
      
      $oDaoLocalAtendimentoSocial   = new cl_localatendimentosocial();
      $sWhereLocalAtendimentoSocial = "as16_db_depart = {$iDepartamento}";
      $sSqlLocalAtendimentoSocial   = $oDaoLocalAtendimentoSocial->sql_query_file(
                                                                                   null,
                                                                                   "as16_sequencial",
                                                                                   null,
                                                                                   $sWhereLocalAtendimentoSocial
                                                                                 );
      $rsLocalAtendimentoSocial     = $oDaoLocalAtendimentoSocial->sql_record($sSqlLocalAtendimentoSocial);
      
      if ($oDaoLocalAtendimentoSocial->numrows > 0) {
        
        $iCodigo                       = db_utils::fieldsMemory($rsLocalAtendimentoSocial, 0)->as16_sequencial;
        $oLocalAtendimentoSocial       = new LocalAtendimentoSocial($iCodigo);
        $oRetorno->iCodigo             = $oLocalAtendimentoSocial->getCodigo();
        $oRetorno->sDescricao          = urlencode($oLocalAtendimentoSocial->getDescricao());
        $oRetorno->iTipo               = $oLocalAtendimentoSocial->getTipo();
        $oRetorno->sIdentificadorUnico = urlencode($oLocalAtendimentoSocial->getIdentificadorUnico());
        
        if ($oLocalAtendimentoSocial->temVinculo()) {
          
          $oRetorno->lTemVinculo = true;
          $sMensagem             = "Este local de atendimento, possui família(s) vinculada(s) e não pode ser excluido.";
          $oRetorno->message     = urlencode($sMensagem);
        }
        
        unset($oLocalAtendimentoSocial);
      }
      
      $oDBDepartamento                  = DBDepartamentoRepository::getDBDepartamentoByCodigo($iDepartamento);
      $oRetorno->sDescricaoDepartamento = urlencode($oDBDepartamento->getNomeDepartamento());
      
      DBDepartamentoRepository::removerDBDepartamento($oDBDepartamento);
      break;
      
    case 'excluirLocalAtendimento':
      
      $oLocalAtendimentoSocial = new LocalAtendimentoSocial($oParam->iCodigo);
      
      db_inicio_transacao();
      
      $oLocalAtendimentoSocial->removerLocalAtendimento();
      
      db_fim_transacao();
      $oRetorno->message = urlencode("Dados do Local de Atendimento removido.");
      break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>