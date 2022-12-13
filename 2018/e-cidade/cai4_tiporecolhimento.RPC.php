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

use ECidade\Tributario\Grm\TipoRecolhimento;
use ECidade\Tributario\Grm\Repository\TipoRecolhimento as tipoRecolhimentoRepository;
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oParam                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

$tipoRecolhimentoRepository = new tipoRecolhimentoRepository;

try {

  db_inicio_transacao();
  switch ($oParam->exec) {
     
    case 'salvar':
      
      $tipoRecolhimento = new TipoRecolhimento();
      $tipoRecolhimento->setCodigo((int)$oParam->codigo);
      $tipoRecolhimento->setCodigoRecolhimento($oParam->codigo_recolhimento);
      $tipoRecolhimento->setNome($oParam->nome);
      $tipoRecolhimento->setTituloReduzido($oParam->titulo_reduzido);
      $tipoRecolhimento->setEspecieIngresso((int)$oParam->especie_ingresso);
      $tipoRecolhimento->setTipoPessoa((int)$oParam->tipo_recolhedor);      
      $tipoRecolhimento->setObrigaNumeroReferencia((bool)$oParam->obriga_referencia);
      $tipoRecolhimento->setInformaDesconto((bool)$oParam->informa_desconto);
      $tipoRecolhimento->setInformaMulta((bool)$oParam->informa_multa);
      $tipoRecolhimento->setInformaJuros((bool)$oParam->informa_juros);
      $tipoRecolhimento->setInstrucoes($oParam->instrucoes);
      $tipoRecolhimento->setInformaOutrosAcrescimos((bool)$oParam->informa_outros_acrescimos);
      $tipoRecolhimento->setInformaOutrasDeducoes((bool)$oParam->informa_outras_deducoes);
      if (!empty($oParam->grupo_atributo_dinamico)) {
         $tipoRecolhimento->setAtributoDinamico(new DBAttDinamico($oParam->grupo_atributo_dinamico));
      }
      if (!empty($oParam->codigo_workflow)) {
        
        $oWorkFlowRepository = new \ECidade\Configuracao\Workflow\Repository\Workflow();
        $oWorkflow           = $oWorkFlowRepository->getById($oParam->codigo_workflow);        
        $tipoRecolhimento->setWorkflow($oWorkflow);
      }
      $tipoRecolhimentoRepository->persist($tipoRecolhimento);
      $oRetorno->sMessage = 'Tipo Recolhimento Salvo com sucesso.';
      break;
      
  case 'getDadosTipoRecolhimento':

     $oRecolhimento = $tipoRecolhimentoRepository->getTipoRecolhimento($oParam->codigo);
     if (empty($oRecolhimento)) {
       throw new BusinessException('Tipo de Recolhimento não encontrado');
     }
     
     $oRetorno->codigo                    = $oRecolhimento->getCodigo();
     $oRetorno->codigo_recolhimento       = $oRecolhimento->getCodigoRecolhimento();
     $oRetorno->nome                      = $oRecolhimento->getNome();
     $oRetorno->titulo_reduzido           = $oRecolhimento->getTituloReduzido();
     $oRetorno->especie_ingresso          = $oRecolhimento->getEspecieIngresso();
     $oRetorno->tipo_recolhedor           = $oRecolhimento->getTipoPessoa();
     $oRetorno->obriga_referencia         = $oRecolhimento->obrigaNumeroReferencia() == true ? '1' : '2';  
     $oRetorno->informa_desconto          = $oRecolhimento->informaDesconto() == true ? '1' : '2';  
     $oRetorno->informa_multa             = $oRecolhimento->informaMulta() == true ? '1' : '2';  
     $oRetorno->informa_juros             = $oRecolhimento->informaJuros() == true ? '1' : '2';  
     $oRetorno->informa_outros_acrescimos = $oRecolhimento->informaOutrosAcrescimos() == true ? '1' : '2';  
     $oRetorno->informa_outras_deducoes   = $oRecolhimento->informaOutrasDeducoes() == true ? '1' : '2';  
     $oRetorno->instrucoes                = $oRecolhimento->getInstrucoes();  
     $oRetorno->codigo_workflow           = '';  
     $oRetorno->nome_workflow             = '';
     $oRetorno->grupo_atributo_dinamico   = '';
     if ($oRecolhimento->getWorkflow() != '') {
       
       $oRetorno->codigo_workflow = $oRecolhimento->getWorkflow()->getCodigo();
       $oRetorno->nome_workflow   = $oRecolhimento->getWorkflow()->getNome();
     }
     $oAtributoDinamico  = tipoRecolhimentoRepository::getAtributosDoRecolhimento($oRecolhimento);
     if (!empty($oAtributoDinamico)) {
      $oRetorno->grupo_atributo_dinamico = $oAtributoDinamico->getCodigo();
     }
     break;

  case 'remover':

    $oRecolhimento = $tipoRecolhimentoRepository->getTipoRecolhimento($oParam->codigo);
    if (empty($oRecolhimento)) {
      throw new BusinessException('Tipo de Recolhimento não encontrado');
    }
    $tipoRecolhimentoRepository->remove($oRecolhimento);
    $oRetorno->sMessage = 'Tipo Recolhimento excluído com sucesso.';
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
echo JSON::create()->stringify($oRetorno);