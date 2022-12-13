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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/exceptions/BusinessException.php');
require_once('libs/exceptions/DBException.php');
require_once('libs/exceptions/FileException.php');
require_once('libs/exceptions/ParameterException.php');


$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
switch ($oParam->exec) {

  case 'salvarVisita':
    
    db_inicio_transacao();
    try {
      
      /**
       * Variavel que identifica se sera alterado ou incluido um novo registro de visita, sendo passada como
       * parametro para FamiliaVisita
       */
      $iCodigoVisita = null;
      if (isset($oParam->iCodigoVisita)) {
        $iCodigoVisita = $oParam->iCodigoVisita;
      }
      $oFamiliaVisita = new FamiliaVisita($iCodigoVisita);
      
      $oFamiliaVisita->setProfissionalVisita($oParam->iProfissionalVisita);
      $oFamiliaVisita->setDataVisita($oParam->dDataVisita);
      $oFamiliaVisita->setHoraVisita($oParam->sHoraVisita);
      $oFamiliaVisita->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao));
      $oFamiliaVisita->setVisitaTipo(VisitaTipoRepository::getVisitaTipoByCodigo($oParam->iTipoVisita));
      
      if (isset($oParam->iLocalEncaminhamento) && !empty($oParam->iLocalEncaminhamento)) {
        
        $oCgmEncaminhamento = CgmFactory::getInstanceByCgm($oParam->iLocalEncaminhamento);
        $oFamiliaVisita->setCgmEncaminhamento($oCgmEncaminhamento);
      } else {
        $oFamiliaVisita->removeCgmEncaminhamento();
      }
      
      $oFamiliaVisita->salvar($oParam->iCodigoCidadaoFamilia);
      
      /**
       * Salvamos a informacao referente ao aparelho ligado a rede eletrica, na familia
       */
      $oFamilia                      = new Familia($oParam->iCodigoCidadaoFamilia);
      
      $lAparelhoRedeEletricaContinuo = 'false';
      if ($oParam->sAparelhoRedeEletrica == '1') {
        $lAparelhoRedeEletricaContinuo = 'true';
      }
      $oFamilia->getComposicaoFamiliar();
      $oFamilia->setAparelhoRedeEletricaContinuo($lAparelhoRedeEletricaContinuo);
      
      
      
      $oFamilia->salvar();

      
      $oDaoCidadaoFamiliaVisitaContato = db_utils::getDao("cidadaofamiliavisitacontato");
      /**
       * Verificamos se a opcao de contato telefonico esta setada como sim (1), para salvarmos os dados em 
       * cidadaofamiliavisitacontato
       */
      if ($oParam->sContatoTelefone == '1') {
        
        $oDaoCidadaoFamiliaVisitaContato->as10_cidadaofamiliavisita = $oFamiliaVisita->getCodigoSequencial();
        $oDaoCidadaoFamiliaVisitaContato->as10_profissionalcontato  = $oParam->iProfissionalContato;
        $oDaoCidadaoFamiliaVisitaContato->as10_data                 = $oParam->sDataContato;
        
        /**
         * Verificamos se foi setado iCodigoVisitaContato. Caso sim, alteramos o registro existente. Caso contrario,
         * inserimos um novo registro
         */
        if (isset($oParam->iCodigoVisitaContato) && $oParam->iCodigoVisitaContato != 0) {
          
          $oDaoCidadaoFamiliaVisitaContato->as10_sequencial = $oParam->iCodigoVisitaContato;
          $oDaoCidadaoFamiliaVisitaContato->alterar($oDaoCidadaoFamiliaVisitaContato->as10_sequencial);
        } else {
          
          $oDaoCidadaoFamiliaVisitaContato->incluir(null);
          $iCodigoVisitaContato = $oDaoCidadaoFamiliaVisitaContato->as10_sequencial;
        }
        
        /**
         * Caso esteja setado como nao (0) o contato telefonico, porem exista iCodigoVisitaContato (alteracao),
         * excluimos o registro da tabela cidadaofamiliavisitacontato
         */
      } else if ($oParam->sContatoTelefone == '0' && 
                 isset($oParam->iCodigoVisitaContato) && 
                 $oParam->iCodigoVisitaContato != 0) {
        
        $sWhereFamiliaVisitaContato = "as10_sequencial = {$oParam->iCodigoVisitaContato}";
        $oDaoCidadaoFamiliaVisitaContato->excluir(null, $sWhereFamiliaVisitaContato);
      }
      
      $oRetorno->iCodigoVisita        = $oFamiliaVisita->getCodigoSequencial();
      if (!empty($iCodigoVisitaContato)) {
        $oRetorno->iCodigoVisitaContato = $iCodigoVisitaContato;
      }
      $oRetorno->status = 1;
      db_fim_transacao(false);
    } catch (BusinessException $eErro) {
      
      $oRetorno->status = 2;
      db_fim_transacao(true);
      $oRetorno->message = urlencode(str_replace('"', '\"', $eErro->getMessage()));
    }
    break;
  
  case 'salvarAtualizacao':
    
    db_inicio_transacao();
    try {

      $oFamilia            = new Familia($oParam->iCodigoFamilia);
      
      foreach ($oFamilia->getComposicaoFamiliar() as $oCidadao) {
        
        if ($oCidadao instanceof CadastroUnico) {
         
          $oCidadao->setDataAtualizacaoCadastroUnico($oParam->dtAtualizacao);
          $oCidadao->salvar();
        }
      }
      
      $oRetorno->status = 1;
      db_fim_transacao(false);
    } catch (BusinessException $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
  
  case 'retornaDadosVisita':
    
    $oFamiliaVisita                    = new FamiliaVisita($oParam->iCodigoFamiliaVisita);
    $oRetorno->iCodigoVisita           = $oParam->iCodigoFamiliaVisita;
    $oRetorno->iCodigoCidadaoFamilia   = $oFamiliaVisita->getCodigoCidadaoFamilia();
    $oRetorno->iTipoVisita             = $oFamiliaVisita->getVisitaTipo()->getCodigo();
    $oRetorno->sTipoVisita             = urlencode($oFamiliaVisita->getVisitaTipo()->getDescricao());
    
    $oRetorno->iCgmEncaminhamento      = '';
    $oRetorno->sLocalEncaminhamento    = '';
    $oCgmEncaminhamento = $oFamiliaVisita->getCgmEncaminhamento();
    if (!empty($oCgmEncaminhamento)) {
      
      $oRetorno->iCgmEncaminhamento   = $oCgmEncaminhamento->getCodigo();
      $oRetorno->sLocalEncaminhamento = urlencode($oCgmEncaminhamento->getNome());
    }

    /**
     * Buscamos o nome do cidadao responsavel da familia pelo codigo
     */
    $oFamilia                          = new Familia($oRetorno->iCodigoCidadaoFamilia);
    $oRetorno->iCidadao                = $oFamilia->getResponsavel()->getCodigo();
    $oRetorno->sNomeCidadao            = $oFamilia->getResponsavel()->getNome();
    $oRetorno->lAparelhoRedeEletrica   = $oFamilia->getAparelhoRedeEletricaContinuo();
    $oRetorno->iProfissionalVisita     = $oFamiliaVisita->getProfissionalVisita();
    $oRetorno->iNis                    = $oFamilia->getResponsavel()->getNis();
    
    /**
     * Buscamos o nome do profissional que fez a visita
     */
    $oCgm                              = new CgmFisico($oRetorno->iProfissionalVisita);
    $oRetorno->sNomeProfissionalVisita = $oCgm->getNomeCompleto();
    $oRetorno->dtDataVisita            = $oFamiliaVisita->getDataVisita();
    $oRetorno->sHoraVisita             = $oFamiliaVisita->getHoraVisita();
    $oRetorno->sObservacao             = $oFamiliaVisita->getObservacao();
    $oRetorno->sContatoTelefone        = '0';
    
    /**
     * Buscamos os dados de contato da visita. Caso retorne algo, preenchemos os campos
     */
    $oFamiliaVisitaContato      = db_utils::getDao("cidadaofamiliavisitacontato");
    $sWhereFamiliaVisitaContato = "as10_cidadaofamiliavisita = {$oParam->iCodigoFamiliaVisita}";
    $sSqlFamiliaVisitaContato   = $oFamiliaVisitaContato->sql_query(null, "*", null, $sWhereFamiliaVisitaContato);
    $rsFamiliaVisitaContato     = $oFamiliaVisitaContato->sql_record($sSqlFamiliaVisitaContato);
    
    if ($oFamiliaVisitaContato->numrows > 0) {
      
      $oDadosFamiliaVisitaContato         = db_utils::fieldsMemory($rsFamiliaVisitaContato, 0);
      $oRetorno->sContatoTelefone         = '1';
      $oRetorno->iCodigoVisitaContato     = $oDadosFamiliaVisitaContato->as10_sequencial;
      $oRetorno->iProfissionalContato     = $oDadosFamiliaVisitaContato->as10_profissionalcontato;
      $oRetorno->sNomeProfissionalContato = $oDadosFamiliaVisitaContato->z01_nome;
      
      /**
       * Instanciamos um objeto data para converter a data para o padrao PTBR
       */
      $oData                   = new DBDate($oDadosFamiliaVisitaContato->as10_data);
      $oRetorno->dtDataContato = $oData->convertTo(DBDate::DATA_PTBR);
    }
    
    unset($oFamiliaVisita);
    unset($oFamilia);
    unset($oCgm);
    unset($oFamiliaVisitaContato);
    unset($oData);
    
    break;
    
  case 'excluirVisita':
    
    db_inicio_transacao();
    try {

      $oDaoFamiliaEncaminhada   = new cl_cidadaofamiliavisitaencaminhamento();
      $sWhereFamiliaEncaminhada = "as14_cidadaofamiliavisita = {$oParam->iCodigoVisita} ";
      $oDaoFamiliaEncaminhada->excluir(null, $sWhereFamiliaEncaminhada);
      
      /**
       * Verificamos se a visita a ser excluida, possui registro de contato para exclusao
       */
      if (isset($oParam->iCodigoVisitaContato)) {
        
        $oDaoFamiliaVisitaContato   = db_utils::getDao("cidadaofamiliavisitacontato");
        $sWhereFamiliaVisitaContato = "as10_sequencial = {$oParam->iCodigoVisitaContato}";
        $oDaoFamiliaVisitaContato->excluir(null, $sWhereFamiliaVisitaContato);
      }
      
      /**
       * Excluimos os dados da visita
       */
      $oDaoFamiliaVisita   = db_utils::getDao("cidadaofamiliavisita");
      $sWhereFamiliaVisita = "as05_sequencial = {$oParam->iCodigoVisita}";
      $oDaoFamiliaVisita->excluir(null, $sWhereFamiliaVisita);
      
      $oRetorno->status = 1;
      db_fim_transacao(false);
    } catch (BusinessException $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
}
echo $oJson->encode($oRetorno);
?>