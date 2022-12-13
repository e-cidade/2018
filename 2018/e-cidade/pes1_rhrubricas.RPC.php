<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/pessoal/Rubrica.model.php"));
require_once(modification("model/pessoal/RegraPonto.model.php"));

$oJson        = new services_json();
$oParametros  = $oJson->decode(str_replace("\\","",$_POST["json"]));
$iAnoFolha    = db_anofolha(); 
$iMesFolha    = db_mesfolha();
$iInstituicao = db_getsession('DB_instit');
$oRetorno     = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->sMensagem = "";

const MENSAGEM_VALIDA_LIMITE_RUBRICA = 'recursoshumanos.pessoal.pes4_valida_limite_rubrica.';

try {
  
  switch ( $oParametros->sExecucao ) {
    
    case 'BuscaPadroesRubrica':

      $oRubrica = new Rubrica($oParametros->sCodigoRubrica);

      $oRetorno->nQuantidadePadrao = $oRubrica->getQuantidadePadrao();
      $oRetorno->nValorPadrao      = $oRubrica->getValorPadrao();
      $oRetorno->nQuantidadeLimite = $oRubrica->getQuantidadeLimite();
      $oRetorno->nValorLimite      = $oRubrica->getValorLimite();
      $oRetorno->sTipoBloqueio     = $oRubrica->getTipoBloqueio();
      
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

      $aRubricasQuantidadeValor = array();
      foreach ($oParametros->aRubricasQuantidadeValor as $oRubricaQuantidadeValor) {
        $aRubricasQuantidadeValor[$oRubricaQuantidadeValor->sRubrica] = $oRubricaQuantidadeValor;
      }

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

        /**
         * Verifica se os valores e quantidades das rubricas
         * estão sento passados para verificar limites
         */
        if(isset($aRubricasQuantidadeValor[$sRubrica])) {

          /**
           * Valida a quantidade informada para a rubrica se excede o limite configurado ou não
           */
          if( $oRubrica->getQuantidadeLimite() > 0 && $aRubricasQuantidadeValor[$sRubrica]->nQuantidade > $oRubrica->getQuantidadeLimite() ) { 

            switch (strtolower($oRubrica->getTipoBloqueio())) { 
              case 'b':
                $aMensagensBloqueio[ $sRubrica ][] = _M( MENSAGEM_VALIDA_LIMITE_RUBRICA . 'limite_quantidade_excedido', (object)array('quantidade'=>$oRubrica->getQuantidadeLimite()));
                break;
              case 'a':
                $aMensagensAviso[ $sRubrica ][] = _M( MENSAGEM_VALIDA_LIMITE_RUBRICA . 'limite_quantidade_excedido', (object)array('quantidade'=>$oRubrica->getQuantidadeLimite()));
                break;
            }
          }

          /**
           * Valida o valor informado para a rubrica se excede o limite configurado ou não
           */
          if( $oRubrica->getValorLimite() > 0 && $aRubricasQuantidadeValor[$sRubrica]->nValor > $oRubrica->getValorLimite() ) { 
            
            switch (strtolower($oRubrica->getTipoBloqueio())) { 
              case 'b':
                $aMensagensBloqueio[ $sRubrica ][] = _M( MENSAGEM_VALIDA_LIMITE_RUBRICA . 'limite_valor_excedido', (object)array('valor'=>$oRubrica->getValorLimite()));
                break;
              case 'a':
                $aMensagensAviso[ $sRubrica ][] = _M( MENSAGEM_VALIDA_LIMITE_RUBRICA . 'limite_valor_excedido', (object)array('valor'=>$oRubrica->getValorLimite()));
                break;
            }
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
        $sMensagensAviso .= "\nDeseja continuar?";
      }

      /**
       * Encontrou regra do ponto com comportamento do tipo bloqueio 
       */
      if ( !empty( $aMensagensBloqueio ) ) {
        $sMensagensBloqueio = mensagemRegraPonto($aMensagensBloqueio, true);
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

function mensagemRegraPonto($aMensagem, $lBloqueio = false) {

  $sMensagem = "";
  if ($lBloqueio) {
    $sMensagem = "Não foi possivel incluir rubrica no ponto:\n\n";
  }

  foreach ( $aMensagem as $sRubrica => $aMensagemRubrica ) {

    foreach ( $aMensagemRubrica as $sMensagemRegraPonto ) {
      $sMensagem .= "$sRubrica - " . $sMensagemRegraPonto . PHP_EOL;
    }
  }
  
  return $sMensagem;
}
