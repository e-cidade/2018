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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/JSON.php");
require_once("model/pessoal/Rubrica.model.php");
require_once("model/pessoal/RegraPonto.model.php");

$oJson        = new services_json();
$oParametros  = $oJson->decode(str_replace("\\","",$_POST["json"]));
$iAnoFolha    = db_anofolha(); 
$iMesFolha    = db_mesfolha();
$iInstituicao = db_getsession('DB_instit');
$oRetorno     = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->sMensagem = "";

try {
  
  switch ( $oParametros->sExecucao ) {
    
    case 'BuscaPadroesRubrica':

      $oRubrica = new Rubrica($oParametros->sCodigoRubrica);

      $oRetorno->nQuantidadePadrao = $oRubrica->getQuantidadePadrao();
      $oRetorno->nValorPadrao      = $oRubrica->getValorPadrao();
      
    break;

    /**
     * Retorna array com codigo e descricao das rubricas da regra 
     */
    case 'getRubricasRegra' :

      $aRubricasLancadas = array(); 
      $oRegraPonto = new RegraPonto($oParametros->iRegraPonto);

      foreach($oRegraPonto->getRubricas() as $oRubrica) {
        $aRubricasLancadas[] = array('codigo' => $oRubrica->getCodigo(), 'descricao' => $oRubrica->getDescricao()); 
      }

      $oRetorno->aRubricasLancadas = $aRubricasLancadas;

    break;

    /**
     * Incluir regra do ponto 
     */
    case 'incluir' :

      db_inicio_transacao();

      $sDescricao = db_stdClass::normalizeStringJsonEscapeString($oParametros->sDescricao);

      $oRegraPonto = new RegraPonto();
      $oRegraPonto->setDescricao($sDescricao);
      $oRegraPonto->setCodigoSelecao($oParametros->iSelecao);
      $oRegraPonto->setComportamento($oParametros->iComportamento);
      $oRegraPonto->setInstituicao(new Instituicao(db_getsession('DB_instit')));

      foreach ( $oParametros->aRubricas as $sRubrica ) {
        $oRegraPonto->adicionarRubrica(new Rubrica( $sRubrica ));
      }

      $oRegraPonto->salvar();

      db_fim_transacao(false);
      
      $oRetorno->sMensagem = 'Inclusão efetuada com sucesso.';

    break;

    /**
     * Alterar regra do ponto  
     */
    case 'alterar' :

      db_inicio_transacao();

      $sDescricao = db_stdClass::normalizeStringJsonEscapeString($oParametros->sDescricao);

      $oRegraPonto = new RegraPonto($oParametros->iRegraPonto);
      $oRegraPonto->setDescricao($sDescricao);
      $oRegraPonto->setCodigoSelecao($oParametros->iSelecao);
      $oRegraPonto->setComportamento($oParametros->iComportamento);
      $oRegraPonto->setInstituicao(new Instituicao(db_getsession('DB_instit')));
      $oRegraPonto->limparRubricas();

      foreach ( $oParametros->aRubricas as $sRubrica ) {
        $oRegraPonto->adicionarRubrica(new Rubrica( $sRubrica ));
      }

      $oRegraPonto->salvar();

      db_fim_transacao(false);

      $oRetorno->sMensagem = 'Alteração efetuada com sucesso.';

    break;

    /**
     * Excluir regra do ponto 
     */
    case 'excluir' :

      db_inicio_transacao();

      $oRegraPonto = new RegraPonto($oParametros->iRegraPonto);
      $oRegraPonto->excluir();

      $oRetorno->sMensagem = 'Exclusão efetuada com sucesso.';

      db_fim_transacao(false);

    break;

    case 'testarRegistroPonto' :

      $aPontos = array(
        'fx'      => Ponto::FIXO,
        'fs'      => Ponto::SALARIO,
        'fa'      => Ponto::ADIANTAMENTO,
        'f13'     => Ponto::PONTO_13o,
        'com'     => Ponto::COMPLEMENTAR,
        'fe'      => Ponto::FERIAS,
        'fr'      => Ponto::RESCISAO,
      );

      $aMensagensAviso = array();
      $aMensagensBloqueio = array();

      $sMensagensAviso    = '';
      $sMensagensBloqueio = '';

      $sTabelaPonto = $aPontos[$oParametros->sTipoPonto];
      $oServidor = new Servidor($oParametros->iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao);
      $oPonto = $oServidor->getPonto($sTabelaPonto);

      /**
       * Busca regras usando as rubricas passadas por parametro
       */
      foreach ( $oParametros->aRubricas as $sRubrica ) {

        $oRubrica = RubricaRepository::getInstanciaByCodigo($sRubrica);
        $aRegrasPonto = RegraPonto::getRegrasPorRubrica($oRubrica);

        foreach ( $aRegrasPonto as $oRegraPonto ) {

          $lTestarRegraPonto = $oRegraPonto->testarRegistroPonto($oPonto);

          /**
           * Regra retorno true, passa para proxima regra 
           */
          if ( $lTestarRegraPonto ) {
            continue;
          }

          /**
           * Aviso
           * Regra com comportamento do tipo 1 - aviso
           */
          if ( $oRegraPonto->getComportamento() == RegraPonto::COMPORTAMENTO_AVISO ) {
            $aMensagensAviso[ $sRubrica ][] = $oRegraPonto->getDescricao();
          }

          /**
           * Bloqueio
           * Regra com comportamento do tipo 2 - bloqueio
           */
          if ( $oRegraPonto->getComportamento() == RegraPonto::COMPORTAMENTO_BLOQUEIO ) {
            $aMensagensBloqueio[ $sRubrica ][] = $oRegraPonto->getDescricao();
          }

        }

      }

      /**
       * Encontrou regra do ponto com comportamento do tipo aviso 
       * - adiciona a mensagem perguna se deseja continuar, para ser usado 
       *   com confirm() do javascript
       */
      if ( !empty( $aMensagensAviso ) ) {

        $sMensagensAviso  = mensagemRegraPonto($aMensagensAviso);
        $sMensagensAviso .= "\n\nDeseja continuar?";
      }

      /**
       * Encontrou regra do ponto com comportamento do tipo bloqueio 
       */
      if ( !empty( $aMensagensBloqueio ) ) {
        $sMensagensBloqueio = mensagemRegraPonto($aMensagensBloqueio);        
      }

      $oRetorno->sMensagensAviso    = urlEncode($sMensagensAviso);
      $oRetorno->sMensagensBloqueio = urlEncode($sMensagensBloqueio);            

    break;

    default :
      throw new Exception('Parâmetro inválido');
    break;
  }

} catch ( Exception $eErro ) {
  
  $oRetorno->iStatus   = "2";
  $oRetorno->sMensagem = $eErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);

function mensagemRegraPonto($aMensagem) {

  $sMensagem = "Não foi possivel incluir rubrica no ponto:\n";

  foreach ( $aMensagem as $sRubrica => $aMensagemRubrica ) {

    foreach ( $aMensagemRubrica as $sMensagemRegraPonto ) {
      $sMensagem .= "\n$sRubrica - " . $sMensagemRegraPonto;
    }
  }
  
  return $sMensagem;
}