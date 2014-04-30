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
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once("libs/exceptions/ParameterException.php");
require_once("libs/exceptions/BusinessException.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$iDepartamento       = db_getsession("DB_coddepto");
$dtAtual             = date("d/m/Y");
$iUsuario            = db_getsession("DB_id_usuario");

try {

  switch($oParam->sExecucao) {

    /**
     * Persiste os dados do vinculo de uma familia com um local de atendimento
     * @param integer $oParam->iFamilia      - Codigo da familia
     * @param integer $oParam->iDepartamento - Codigo do departamento a ser vinculado
     * @param string  $oParam->sObservacao   - Observacao em relacao ao vinculo
     */
    case 'salvar':

      if (isset($oParam->iFamilia)) {

        db_inicio_transacao();

        /**
         * Variavel que recebe uma instancia de LocalAtendimentoSocial.
         * Buscamos o codigo do local de atendimento através do codigo do departamento
         */
        $oLocalAtendimentoSocial      = null;
        $oDaoLocalAtendimentoSocial   = new cl_localatendimentosocial();
        $sWhereLocalAtendimentoSocial = "as16_db_depart = {$oParam->iDepartamento}";
        $sSqlLocalAtendimentoSocial   = $oDaoLocalAtendimentoSocial->sql_query_file(
                                                                                     null,
                                                                                     "as16_sequencial",
                                                                                     null,
                                                                                     $sWhereLocalAtendimentoSocial
                                                                                   );

        $rsLocalAtendimentoSocial     = $oDaoLocalAtendimentoSocial->sql_record($sSqlLocalAtendimentoSocial);

        if ($oDaoLocalAtendimentoSocial->numrows > 0) {

          $iLocalAtendimento       = db_utils::fieldsMemory($rsLocalAtendimentoSocial, 0)->as16_sequencial;
          $oLocalAtendimentoSocial = new LocalAtendimentoSocial($iLocalAtendimento);
        }

        $oLocalAtendimentoFamilia = new LocalAtendimentoFamilia();
        $oLocalAtendimentoFamilia->setFamilia(FamiliaRepository::getFamiliaByCodigo($oParam->iFamilia));
        $oLocalAtendimentoFamilia->setDataVinculo(new DBDate($dtAtual));
        $oLocalAtendimentoFamilia->setUsuario(new UsuarioSistema($iUsuario));
        $oLocalAtendimentoFamilia->setLocalAtendimentoSocial($oLocalAtendimentoSocial);
        $oLocalAtendimentoFamilia->setObservacao($oParam->sObservacao);
        $oLocalAtendimentoFamilia->salvar();

        $oRetorno->sMensagem = urlencode("Dados do local de atendimento salvos com sucesso.");

        db_fim_transacao();
      } else {
        $oRetorno->sMensagem = urlencode("Código da família não informado ou inválido.");
      }

      break;

    /**
     * Retorna as informacoes sobre o local de atendimento atual de uma familia
     * @param integer $oParam->iFamilia - Codigo da familia
     */
    case 'getLocalAtendimentoFamilia':

      $oRetorno->lTemLocalVinculado = false;
      $oLocalAtendimentoSocial      = null;

      if (isset($oParam->iFamilia)) {

        $oFamilia = FamiliaRepository::getFamiliaByCodigo($oParam->iFamilia);

        if ($oFamilia->getLocalAtendimentoAtual() != null) {
          $oLocalAtendimentoSocial  = $oFamilia->getLocalAtendimentoAtual()->getLocalAtendimentoSocial();
        }
        $oRetorno->iFamilia           = $oFamilia->getCodigoSequencial();
        $oRetorno->sResponsavel       = urlencode($oFamilia->getResponsavel()->getNome());

        if ($oLocalAtendimentoSocial != null) {

          $oRetorno->dtVinculo          = urlencode($oFamilia->getLocalAtendimentoAtual()
                                                             ->getDataVinculo()
                                                             ->getDate(DBDate::DATA_PTBR));
          $oRetorno->iLocalAtendimento  = $oLocalAtendimentoSocial->getDbDepart()->getCodigo();
          $oRetorno->sLocalAtendimento  = urlencode($oLocalAtendimentoSocial->getDescricao());
          $oRetorno->lTemLocalVinculado = true;
        }
      } else {

        $oRetorno->iStatus   = 2;
        $oRetorno->sMensagem = urlencode("Código da família não informado ou inválido.");
      }

      if (!$oRetorno->lTemLocalVinculado) {

        $sMensagem  = "Não foi encontrado vínculo da família com um local de atendimento.\n";
        $sMensagem .= "Para realizar o vínculo, selecione um local de atendimento de destino.";
        $oRetorno->sMensagem = urlencode($sMensagem);
      }
      break;

    /**
     * Retorna um array com o codigo e descricao dos locais de atendimento
     * @return array $oRetorno->aLocaisAtendimento
     *               stdClass integer iDepartamento    - Codigo do departamento
     *                        string  sDescricao - Descricao do local de atendimento
     */
    case 'getLocaisAtendimento':

      $oRetorno->lTemLocaisAtendimento = false;
      $oRetorno->aLocaisAtendimento    = array();
      $oDaoLocalAtendimentoSocial      = new cl_localatendimentosocial();
      $sSqlLocalAtendimentoSocial      = $oDaoLocalAtendimentoSocial->sql_query_file(null, "as16_sequencial", "as16_sequencial");
      $rsLocalAtendimentoSocial        = $oDaoLocalAtendimentoSocial->sql_record($sSqlLocalAtendimentoSocial);
      $iTotalLocalAtendimentoSocial    = $oDaoLocalAtendimentoSocial->numrows;

      if ($iTotalLocalAtendimentoSocial > 0) {

        for ($iContador = 0; $iContador < $iTotalLocalAtendimentoSocial; $iContador++) {

          $oDadosLocalAtendimento                = new stdClass();
          $iLocalAtendimentoSocial               = db_utils::fieldsMemory($rsLocalAtendimentoSocial, $iContador)->as16_sequencial;
          $oLocalAtendimentoSocial               = new LocalAtendimentoSocial($iLocalAtendimentoSocial);
          $oDadosLocalAtendimento->iDepartamento = $oLocalAtendimentoSocial->getDbDepart()->getCodigo();
          $oDadosLocalAtendimento->sDescricao    = urlencode($oLocalAtendimentoSocial->getDescricao());
          $oRetorno->aLocaisAtendimento[]        = $oDadosLocalAtendimento;

          unset($oDadosLocalAtendimento);
          unset($oLocalAtendimentoSocial);
        }
        $oRetorno->lTemLocaisAtendimento = true;
      } else {
        $oRetorno->sMensagem = urlencode("Não foram encontrados departamentos cadastros como CRAS/CREAS.");
      }

      unset($oDaoLocalAtendimentoSocial);
      break;

    /**
     * Encerra um atendimento de uma familia com um local de atendimento
     * @param integer $oParam->iFamilia         - Codigo da familia
     * @param string  $oParam->dtFimAtendimento - Data do fim do atendimento
     * @param string  $oParam->sObservacao      - Observacao sobre o fim do atendimento
     */
    case 'encerrarAtendimento':

      if (isset($oParam->iFamilia)) {

        db_inicio_transacao();

        $oFamilia          = FamiliaRepository::getFamiliaByCodigo($oParam->iFamilia);
        $oLocalAtendimento = $oFamilia->getLocalAtendimentoAtual();
        $oLocalAtendimento->setFimAtendimento(new DBDate($oParam->dtFimAtendimento));
        $oLocalAtendimento->setObservacao($oParam->sMotivo);
        $oLocalAtendimento->encerraAtendimento();

        $oRetorno->sMensagem = urlencode("Atendimento encerrado com sucesso.");

        db_fim_transacao();
      } else {

        $oRetorno->iStatus   = 2;
        $oRetorno->sMensagem = urlencode("Código da família não informado ou inválido.");
      }

      FamiliaRepository::removerFamilia($oFamilia);
      break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>