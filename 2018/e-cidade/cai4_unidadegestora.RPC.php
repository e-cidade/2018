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

use ECidade\Financeiro\Tesouraria\Repository\Receita as ReceitaRepository;
use ECidade\Tributario\Grm\RecolhimentoUnidadeGestora;
use ECidade\Tributario\Grm\Repository\UnidadeGestora as UnidadeGestoraRepository;
use ECidade\Tributario\Grm\TipoRecolhimento;
use ECidade\Tributario\Grm\Repository\TipoRecolhimento as TipoRecolhimentoRepository;
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = \JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

$oUnidadeGestoraRepository   = new UnidadeGestoraRepository();
$oTipoRecolhimentoRepository = new TipoRecolhimentoRepository();
try {
  
  db_inicio_transacao();
  switch ($oParam->exec) {
    
    case 'salvar':

      $oUnidadeGestora = new \ECidade\Tributario\Grm\UnidadeGestora();
      if (!empty($oParam->codigo)) {
        $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->codigo);        
      }
      $oUnidadeGestora->setNome($oParam->nome);
      $oUnidadeGestora->setDepartamento(DBDepartamentoRepository::getDBDepartamentoByCodigo($oParam->departamento));
      $oUnidadeGestoraRepository->persist($oUnidadeGestora);
      $oRetorno->sMessage = 'Unidade Gestora salva com sucesso!';
      $oRetorno->codigo   = $oUnidadeGestora->getCodigo();
      break;
    
    case 'adicionarRecolhimento':
      
      $oTipoRecolhimento = $oTipoRecolhimentoRepository->getTipoRecolhimento($oParam->recolhimento->codigo_recolhimento);
      if (empty($oTipoRecolhimento)) {
        throw new BusinessException('Tipo de recolhimento '.$oParam->recolhimento->codigo_recolhimento.' não encontrado');        
      }      
      $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->codigo_unidade);
      if (empty($oUnidadeGestora)) {
        throw new BusinessException('Unidade Gestora '.$oParam->tipo_recolhimento.' não encontrada');
      }
      $oReceita = ReceitaRepository::getById($oParam->recolhimento->receita);
      if (empty($oUnidadeGestora)) {
        throw new BusinessException('Receita '.$oParam->recolhimento->receita.' não encontrada');
      }
      
      $oRecolhimentoUnidade = new RecolhimentoUnidadeGestora();
      $oRecolhimentoUnidade->setTipoRecolhimento($oTipoRecolhimento);
      $oRecolhimentoUnidade->setReceita($oReceita);
      if ($oTipoRecolhimento->informaMulta() && $oReceita->getReceitaMulta() == '') {
        
        $sMensagem = "Não foi possível incluir o tipo de recolhimento.\n";
        $sMensagem .= "O tipo de recolhimento {$oTipoRecolhimento->getNome()} está configurado para aceitar valores de multa. ";
        $sMensagem .= "A receita informada ({$oReceita->getCodigo()} - {$oReceita->getNome()}) não possui receita de multa configurada";
        throw new BusinessException($sMensagem);
      }

      if ($oTipoRecolhimento->informaJuros() && $oReceita->getReceitaJuros() == '') {

        $sMensagem = "Não foi possível incluir o tipo de recolhimento.\n";
        $sMensagem .= "O tipo de recolhimento {$oTipoRecolhimento->getNome()}  está configurado para aceitar valores de Juros. ";
        $sMensagem .= "A receita informada ({$oReceita->getCodigo()} - {$oReceita->getNome()}) não possui receita de Juros configurada";
        throw new BusinessException($sMensagem);
      }
      $oUnidadeGestora->adicionarRecolhicomento($oRecolhimentoUnidade);
      $oUnidadeGestoraRepository->persist($oUnidadeGestora);      
      $oRetorno->sMessage = 'Recolhimento salvo com sucesso.';
    break;
    
    case 'pesquisarUnidade':
     
      $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->codigo);
      if (empty($oUnidadeGestora)) {
        throw new BusinessException('Unidade Gestora '.$oParam->tipo_recolhimento.' não encontrada');
      }

      $dadosUnidade                = new \stdClass();
      $dadosUnidade->codigo        = $oUnidadeGestora->getCodigo();
      $dadosUnidade->nome          = $oUnidadeGestora->getNome();
      $dadosUnidade->coddepto      = $oUnidadeGestora->getDepartamento()->getCodigo();
      $dadosUnidade->nome_depto    = $oUnidadeGestora->getDepartamento()->getNomeDepartamento();
      $aRecolhimentos              = $oTipoRecolhimentoRepository->getTiposRecolhimentoDaUnidadeGestora($oUnidadeGestora);
      $dadosUnidade->recolhimentos = array();
      foreach ($aRecolhimentos as $oRecolhimento) {
        
        $dadosRecolhimento                      = new \stdClass();
        $dadosRecolhimento->codigo_recolhimento = $oRecolhimento->getTipoRecolhimento()->getCodigo();
        $dadosRecolhimento->recolhimento        = $oRecolhimento->getTipoRecolhimento()->getNome(); 
        $dadosRecolhimento->receita             = $oRecolhimento->getReceita()->getCodigo(); 
        $dadosRecolhimento->descricao_receita   = $oRecolhimento->getReceita()->getNome();
        $dadosUnidade->recolhimentos[]          = $dadosRecolhimento; 
      }
      $oRetorno->unidade = $dadosUnidade;
      break;
      
      case 'removerRecolhimento':

        $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->codigo_unidade);
        if (empty($oUnidadeGestora)) {
          throw new BusinessException('Unidade Gestora '.$oParam->codigo_unidade.' não encontrada');
        }
        $oTipoRecolhimento = $oTipoRecolhimentoRepository->getTipoRecolhimento($oParam->recolhimento);
        if (empty($oTipoRecolhimento)) {
          throw new BusinessException('Tipo de recolhimento '.$oParam->rrecolhimento.' não encontrado');
        }
        $oUnidadeGestora->removerRecolhimento($oTipoRecolhimento);
        $oRetorno->sMessage = 'Recolhimento removido com sucesso.';
        break;

        case 'removerUnidade':
      
          $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->codigo_unidade);
          if (empty($oUnidadeGestora)) {
            throw new BusinessException('Unidade Gestora '.$oParam->codigo_unidade.' não encontrada');
          }
          $oUnidadeGestoraRepository->remove($oUnidadeGestora);
          $oRetorno->sMessage = 'Unidade Gestora removida com sucesso.';
        break;  
  }
  db_fim_transacao(false);
} catch (Exception $eErro) {


  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}
echo JSON::create()->stringify($oRetorno);