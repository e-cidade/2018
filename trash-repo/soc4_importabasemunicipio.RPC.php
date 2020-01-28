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
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

db_app::import("social.*");
db_app::import("exceptions.*");
db_app::import("dbLayoutReader");
db_app::import("dbLayoutLinha");
db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");
db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("educacao.censo.CensoUF");
db_app::import("educacao.censo.CensoUFRepository");

$oJson              = new Services_JSON();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;

switch ($oParam->exec){

  case "importaBase":
    
    try {
      
      $oBase = new ImportacaoCadastroUnico();
      $oBase->processarArquivo(utf8_decode($oParam->arquivo));
      $oRetorno->message = "Base importada com sucesso!";
      
    } catch (FileException $oErro) {
      
      $sArquivo           = basename($oParam->arquivo);
      $oRetorno->message  = "Falha ao carregar arquivo \"$sArquivo\"!\n";
      $oRetorno->message .= $oErro->getMessage();
      $oRetorno->status   = 2;
    } catch (BusinessException $oErro) {
      
      db_fim_transacao(true);
      $oRetorno->message  = "Falha ao importar base!\n";
      $oRetorno->message .= $oErro->getMessage();
      $oRetorno->status   = 2;
    } 
    break;

  case "verificarBeneficiosNaCompetencia":
    
    try {

      $oImportacaoBeneficio        = new ImportacaoBeneficiosCidadao(utf8_decode($oParam->arquivo));
      $oRetorno->lPossuiBeneficios = false;
      $oRetorno->sCompetencia      = "{$oImportacaoBeneficio->getMesCompetencia()}/{$oImportacaoBeneficio->getAnoCompetencia()}";
      if ($oImportacaoBeneficio->hasBeneficiosNaCompetencia()) {
        $oRetorno->lPossuiBeneficios = true; 
       }
    } catch (FileException $eFileError) {
       
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eFileError->getMessage());   
    }
    break;
    
  case "importaBeneficios":

    try {
    
      db_inicio_transacao();
      $oImportacaoBeneficio = new ImportacaoBeneficiosCidadao(utf8_decode($oParam->arquivo));
      $oImportacaoBeneficio->processarArquivo();
      db_fim_transacao(false);
      $oRetorno->status = 1;
      $sCompetencia     = "{$oImportacaoBeneficio->getMesCompetencia()}/{$oImportacaoBeneficio->getAnoCompetencia()}";
      $oRetorno->message = urlencode("Benefícios para competência {$sCompetencia} importados com sucesso.");
    } catch (FileException $eFileException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eFileException->getMessage());
    } catch (ParameterException $eParametro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eParametro->getMessage());
    } catch (BusinessException $eBusiness) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusiness->getMessage());
    }
    break;  
    
  case "getTotalCidadoesFamiliasSemAvaliacao": 
    
    $oDaoCadastroBaseMunicipal = db_utils::getDao("cadastrounicobasemunicipal");
    
    /**
     * Busca o numero de familias que ainda tem avaliacoes para serem processados
     */
    $sCampo      = " count(*) as familia ";
    $sSqlFamilia = $oDaoCadastroBaseMunicipal->sql_query_base_familia(null, $sCampo);
    $rsFamilia   = $oDaoCadastroBaseMunicipal->sql_record($sSqlFamilia);
    
    $iFamiliaSemAvaliacao = db_utils::fieldsMemory($rsFamilia, 0)->familia;

    /**
     * Busca o numero de cidadoes que ainda tem avaliacoes para serem processadas  
     */
    $sCampo      = " count(*) as cidadao ";
    $sSqlCidadao = $oDaoCadastroBaseMunicipal->sql_query_base_cidadao(null, $sCampo);
    $rsCidadao   = $oDaoCadastroBaseMunicipal->sql_record($sSqlCidadao);
    
    $iCidadaoSemAvaliacao = db_utils::fieldsMemory($rsCidadao, 0)->cidadao;
    
    $oRetorno->qtdCidadaoSemAvaliacao = $iCidadaoSemAvaliacao;
    $oRetorno->qtdFamiliaSemAvaliacao = $iFamiliaSemAvaliacao;
      
    break;
    
  case "processaAvaliacao":
    
    $oDaoCadastroBaseMunicipal = db_utils::getDao("cadastrounicobasemunicipal");
    
    /**
     * Buscamos todos os cidadoes sem avaliacao processada ou atualizada
     */
    $sCampoCidadao   = " distinct as02_sequencial";
    $sSqlCidadao     = $oDaoCadastroBaseMunicipal->sql_query_base_cidadao(null, $sCampoCidadao);
    $rsCadastroUnico = $oDaoCadastroBaseMunicipal->sql_record($sSqlCidadao);
    $iCidadoes       = $oDaoCadastroBaseMunicipal->numrows;
    
    /**
     * Buscamos todas as familias sem avaliacao processada ou atualizada
     */
    $sCampoFamilia = " distinct as15_cidadaofamilia ";
    $sSqlFamilia   = $oDaoCadastroBaseMunicipal->sql_query_base_familia(null, $sCampoFamilia);
    $rsFamilia     = $oDaoCadastroBaseMunicipal->sql_record($sSqlFamilia);
    $iFamilias     = $oDaoCadastroBaseMunicipal->numrows;
    
    try {
      
      /**
       * Percorremos todos os cidadoes processando a avaliacao ou atualizando
       */
      for ($i = 0; $i < $iCidadoes; $i++) {
        
        $iCidadoCadastroUnico = db_utils::fieldsMemory($rsCadastroUnico, $i)->as02_sequencial;
        $oCidadao             = new CadastroUnico($iCidadoCadastroUnico);
        $oAvaliacao           = $oCidadao->getAvaliacao();
        unset($oCidadao);
        unset($oAvaliacao);
        unset($iCidadoCadastroUnico);
      }
      
      /**
       * Percorremos todas as familias processando a avaliacao ou atualizando
       */
      for ($i = 0; $i < $iFamilias; $i++) {
        
        db_inicio_transacao();
        $iFamilia  = db_utils::fieldsMemory($rsFamilia, $i)->as15_cidadaofamilia;
        $oFamilia  = new Familia($iFamilia);
        $oAvaliacao = $oFamilia->getAvaliacao();
        unset($oFamilia);
        unset($oAvaliacao);
        unset($iFamilia);
        db_fim_transacao();
      }
      $oRetorno->message .= urlencode("Avaliações importadas com sucesso");
    } catch (BusinessException $oErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode($oErro->getMessage());
    } catch (Exception $oErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode($oErro->getMessage());
    } catch (ParameterException $oErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode($oErro->getMessage());
    }
    
    
    break;
}
echo $oJson->encode($oRetorno);