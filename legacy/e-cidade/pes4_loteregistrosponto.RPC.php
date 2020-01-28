<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSelller Servicos de Informatica
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
// $Id: pes4_loteregistrosponto.RPC.php,v 1.25 2016/03/21 18:25:01 dbrenan.silva Exp $
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                = new services_json();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_loteregistrosponto.');

if(!defined('MENSAGENS_VALIDAR_LIMITE_RUBRICA')) {
  define('MENSAGENS_VALIDAR_LIMITE_RUBRICA', 'recursoshumanos.pessoal.pes4_valida_limite_rubrica.');
}

try {

  db_inicio_transacao();//Begin
  switch ($oParam->exec) {

  case "salvarLote":

    if ( empty($oParam->iCodigoLote) ) {

      $oLoteRegistros = new LoteRegistrosPonto();
      $oLoteRegistros->setUsuario( UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario")) );
      $oLoteRegistros->setInstituicao( InstituicaoRepository::getInstituicaoSessao() );
      $oLoteRegistros->setCompetencia(DBPessoal::getCompetenciaFolha());
      $oLoteRegistros->setSituacao(LoteRegistrosPonto::ABERTO);
    } else {
      $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote); 
    }
    $oLoteRegistros->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricaoLote));
    $oRetorno->oLote = LoteRegistrosPontoRepository::persist($oLoteRegistros);
    break;

  case "fecharLote":

    $oRetorno->erro      = true;
    $oRetorno->message = urlencode(_M(MENSAGENS ."erro_fechar_lote"));

    $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote); 

    if ( count($oLoteRegistros->getRegistroPonto()) == 0 ) {

      $oRetorno->erro    = true;
      throw new BusinessException(_M(MENSAGENS ."erro_fechar_lote_vazio"));
    }

    $oLoteRegistros->setSituacao(LoteRegistrosPonto::FECHADO);

    if ( LoteRegistrosPontoRepository::persist($oLoteRegistros) instanceof LoteRegistrosPonto ){

      $oRetorno->erro    = false;
      $oRetorno->message = urlencode(_M(MENSAGENS ."sucesso_fechar_lote"));
    }
    break;

  case "excluirLote":

    $oRetorno->erro      = true;
    $oRetorno->message = urlencode(_M(MENSAGENS ."erro_excluir_lote"));

    $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote); 

    if ( LoteRegistrosPontoRepository::remover($oLoteRegistros) === true ) {
      $oRetorno->erro    = false;
      $oRetorno->message = urlencode(_M(MENSAGENS ."sucesso_excluir_lote")); 
    }
    break;

  case "cancelarFechamento":

    $oRetorno->erro        = true;
    $oRetorno->message     = urlencode(_M(MENSAGENS ."erro_reabrir_lote"));
    $oMensagem             = new stdClass();
    $oMensagem->sDescricao = db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricao);

    $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote); 
    $oLoteRegistros->setSituacao(LoteRegistrosPonto::ABERTO);

    if ( LoteRegistrosPontoRepository::persist($oLoteRegistros) instanceof LoteRegistrosPonto ){
      $oRetorno->erro    = false;
      $oRetorno->message = urlencode(_M(MENSAGENS ."sucesso_reabrir_lote", $oMensagem));
    }

    break;

  case "confirmarLote":

    $oRetorno->erro      = true;
    $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_reabrir_lote"));

    $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote); 
    
    if ( $oLoteRegistros->confirmarLote() ) {
      $oRetorno->erro      = false;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."sucesso_confirmar_lote"));
    }
    break;

  case "cancelarConfirmacaoLote":

    $oRetorno->erro      = true;
    $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_cancelar_confirmacao_lote"));

    $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote); 
    
    if ( $oLoteRegistros->cancelarConfirmacao() ) {
      $oRetorno->erro      = false;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."sucesso_cancelar_confirmacao_lote"));
    }
    break;

  case "buscarRegistrosLote":

    $oLoteRegistros = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote);
    $aRegistrosLote = array();

    foreach ($oLoteRegistros->getRegistroPonto() as $oRegistro) {

      $oRegistrosLote = $oRegistro->toStdClass();

      /**
       * Agrupa os registros do Lote por Rubrica, caso contrário agrupo por Servidor.
       */
      if (isset($oParam->lRubrica) && $oParam->lRubrica) {
        $aRegistrosLote[$oRegistrosLote->sRubrica][] = $oRegistrosLote;
      } else {
        $aRegistrosLote[$oRegistrosLote->sMatricula][] = $oRegistrosLote;
      }
    }

    $oRetorno->aRegistros  = $aRegistrosLote;
    $oRetorno->erro        = false;
    break;

  case "salvarRegistroLote":

    $oRegistro    = new RegistroLoteRegistrosPonto();
    $oCompetencia = DBPessoal::getCompetenciaFolha();

    if ( isset($oParam->iSequencial) && !empty($oParam->iSequencial) ) {
      $oRegistro->setCodigo($oParam->iSequencial);
    }

    /** Valida se a Rubrica é do tipo que espera quantidade e/ou valor
     */
    $oRubricaVerificar = RubricaRepository::getInstanciaByCodigo($oParam->sRubrica);
    $sFormulaRubricaVerificar  = str_replace(" ", "", trim($oRubricaVerificar->getFormulaCalculo()));
    $sFormula2RubricaVerificar = str_replace(" ", "", trim($oRubricaVerificar->getFormulaCalculo2()));
    $sFormula3RubricaVerificar = str_replace(" ", "", trim($oRubricaVerificar->getFormulaCalculo3()));
    /**
     * Se a rubrica não tem fórmula deve ser informado valores, senão deve ser informado quantidade
     */
    if ($sFormulaRubricaVerificar == "" && $sFormula2RubricaVerificar == "" && $sFormula3RubricaVerificar == "") {

      if (str_replace(" ", "",trim($oParam->nValor)) == "" || $oParam->nValor <= 0) {
        $oVarErro              = new stdClass();
        $oVarErro->codRubrica  = $oParam->sRubrica;
        $oVarErro->codServidor = $oParam->sMatricula;
        $oRetorno->erro      = true;
        $oRetorno->message   = urlencode(_M(MENSAGENS ."rubrica_informar_valor", $oVarErro));
        break;
      }

    } else {

      if (str_replace(" ", "",trim($oParam->iQuantidade)) == "" || $oParam->iQuantidade <= 0) {
        $oVarErro              = new stdClass();
        $oVarErro->codRubrica  = $oParam->sRubrica;
        $oVarErro->codServidor = $oParam->sMatricula;
        $oRetorno->erro      = true;
        $oRetorno->message   = urlencode(_M(MENSAGENS ."rubrica_informar_quantidade", $oVarErro));
        break;
      }
    }

    /**
     * Valida se já não existe algum Registro do Ponto(Servidor e Rubrica) idêntico 
     * em qualquer lote da competência, caso exista impede de cadastrar um registro duplicado.
     */
    $aLotesRegistrosPonto = LoteRegistrosPontoRepository::getLotesByCompetencia(DBPessoal::getCompetenciaFolha());

    foreach ($aLotesRegistrosPonto as $oLoteRegistroPonto) {

      foreach ($oLoteRegistroPonto->getRegistroPonto() as $oRegistroPonto) {
        
        $sRubrica   = $oRegistroPonto->getRubrica()->getCodigo();
        $iMatricula = $oRegistroPonto->getServidor()->getMatricula();
        if ( $sRubrica == $oParam->sRubrica  && $iMatricula == $oParam->sMatricula ) {

          $oRetorno->erro      = true;
          $oRetorno->message   = urlencode(_M(MENSAGENS ."registro_ponto_duplicado"));
        }
      }
    }

    if ($oRetorno->erro === false) {

      $oRegistro->setCodigoLote($oParam->iCodigoLote);
      $oRegistro->setRubrica(RubricaRepository::getInstanciaByCodigo($oParam->sRubrica));
      $oRegistro->setServidor(ServidorRepository::getInstanciaByCodigo(
        $oParam->sMatricula,
        $oCompetencia->getAno(),
        $oCompetencia->getMes())
      );
      $oRegistro->setValor($oParam->nValor);
      $oRegistro->setQuantidade($oParam->iQuantidade);
      $oRegistro->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
      $oRegistro->setFolhaPagamento(new FolhaPagamentoSalario());
      $oRegistro->setCompetencia($oParam->sCompetencia);

      $oRetorno->oRegistro = RegistroLoteRegistrosPontoRepository::persist($oRegistro);
      $oRetorno->oRegistro = $oRetorno->oRegistro->toStdClass();
      $oRetorno->erro      = false;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."sucesso_salvar_registro_lote"));
    }

    break;

  case "excluirRegistroLote":

    $oRegistro = new RegistroLoteRegistrosPonto();
    $oRegistro->setCodigo($oParam->iCodigo);
    $oRegistro->setCodigoLote($oParam->iCodigoLote);

    if (RegistroLoteRegistrosPontoRepository::excluir($oRegistro)) {
      $oRetorno->iCodigoLote  = $oRegistro->getCodigoLote();
      $oRetorno->erro         = false;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."sucesso_excluir_registro_lote"));
    }

    break;

  case "consultarLotacaoServidor":

    $oRegistro    = new RegistroLoteRegistrosPonto();
    $oCompetencia = DBPessoal::getCompetenciaFolha();
    $oRegistro->setServidor(ServidorRepository::getInstanciaByCodigo(
      $oParam->sMatricula,
      $oCompetencia->getAno(),
      $oCompetencia->getMes())
    );

    $oLotacaoServidor = RegistroLoteRegistrosPontoRepository::getLotacaoServidor($oRegistro);

    $oRetorno->iCodigoLotacao    = $oLotacaoServidor->sCodLotacao;
    $oRetorno->sDescricaoLotacao = $oLotacaoServidor->sDescricaoLotacao;
    $oRetorno->erro              = false;
    break;


  case "buscarLoteUsuario":

    if ( !isset($oParam->iCodigoUsuario) ) {
      $oParam->iCodigoUsuario = db_getsession('DB_id_usuario');
    }

    $aLoteRegistros = LoteRegistrosPontoRepository::getLotesByUsuario(UsuarioSistemaRepository::getPorCodigo($oParam->iCodigoUsuario), DBPessoal::getCompetenciaFolha());
    $aResposta      = array();
    foreach ( $aLoteRegistros as $oLote ) {
      $aResposta[] = $oLote->toStdClass();
    }
    $oRetorno->aResposta = $aResposta;
    break;
  
  case "buscarLoteCompetencia":

    $oCompetencia              = new DBCompetencia($oParam->iAno, $oParam->iMes);
    $aLoteRegistrosCompetencia = LoteRegistrosPontoRepository::getLotesByCompetencia($oCompetencia, false);
    $aResposta                 = array();

    foreach ($aLoteRegistrosCompetencia as $oLote) {
      $aResposta[] = $oLote->toStdClass(); 
    }

    $oRetorno->aResposta = $aResposta;
    break;
  

  case "alterarRegistrosLote":

    $oDadosAtualizar         = $oJson->decode($oParam->oDadosAtualizar);
    $aMessagensLimiteRubricaBloqueio = array();
    $aMessagensLimiteRubricaAviso    = array();
    $lExcedeuLimite                  = false;
    $lExcedeuLimiteBloqueio          = false;

    foreach ($oDadosAtualizar as $oDados) {

      /** Valida se a Rubrica é do tipo que espera quantidade e/ou valor
       */
      $oRubricaVerificar         = RubricaRepository::getInstanciaByCodigo($oDados->sRubrica);
      $sFormulaRubricaVerificar  = str_replace(" ", "", trim($oRubricaVerificar->getFormulaCalculo()));
      $sFormula2RubricaVerificar = str_replace(" ", "", trim($oRubricaVerificar->getFormulaCalculo2()));
      $sFormula3RubricaVerificar = str_replace(" ", "", trim($oRubricaVerificar->getFormulaCalculo3()));
      /**
       * Se a rubrica não tem fórmula deve ser informado valores, senão deve ser informado quantidade
       */
      if ($sFormulaRubricaVerificar == "" && $sFormula2RubricaVerificar == "" && $sFormula3RubricaVerificar == "") {

        if (str_replace(" ", "",trim($oDados->iValor)) == "" || $oDados->iValor <= 0) {
          $oVarErro              = new stdClass();
          $oVarErro->codRubrica  = $oDados->sRubrica;
          $oVarErro->codServidor = $oDados->iMatricula;
          $oRetorno->erro        = true;
          $oRetorno->message     = urlencode(_M(MENSAGENS ."rubrica_informar_valor", $oVarErro));
          break;
        }

      } else {

        if (str_replace(" ", "",trim($oDados->iQuantidade)) == "" || $oDados->iQuantidade <= 0) {
          $oVarErro             = new stdClass();
          $oVarErro->codRubrica = $oDados->sRubrica;
          $oVarErro->codServidor = $oDados->iMatricula;
          $oRetorno->erro      = true;
          $oRetorno->message   = urlencode(_M(MENSAGENS ."rubrica_informar_quantidade", $oVarErro));
          break;
        }
      }

      if(strtolower($oRubricaVerificar->getTipoBloqueio()) != 'n') {

        if((float)$oRubricaVerificar->getQuantidadeLimite() > 0 && (float)$oRubricaVerificar->getQuantidadeLimite() < $oDados->iQuantidade) {
          
          $oVarErroLimiteQuantidade = (object)array('quantidade' => $oRubricaVerificar->getQuantidadeLimite());

          if(strtolower($oRubricaVerificar->getTipoBloqueio()) == 'b') {

            $sMessagemLimiteRubricaBloqueio    = $oRubricaVerificar->getCodigo() .' - ';
            $sMessagemLimiteRubricaBloqueio   .= _M( MENSAGENS_VALIDAR_LIMITE_RUBRICA . "limite_quantidade_excedido", $oVarErroLimiteQuantidade );
            $aMessagensLimiteRubricaBloqueio[$oDados->iMatricula][] = $sMessagemLimiteRubricaBloqueio;
            $lExcedeuLimiteBloqueio = true;
          }

          if(strtolower($oRubricaVerificar->getTipoBloqueio()) == 'a') {

            $sMessagemLimiteRubricaAviso    = $oRubricaVerificar->getCodigo() .' - ';
            $sMessagemLimiteRubricaAviso   .= _M( MENSAGENS_VALIDAR_LIMITE_RUBRICA . "limite_quantidade_excedido", $oVarErroLimiteQuantidade );
            $aMessagensLimiteRubricaAviso[$oDados->iMatricula][] = $sMessagemLimiteRubricaAviso;
          }

          $lExcedeuLimite = true;
        }
        
        if((float)$oRubricaVerificar->getValorLimite() > 0 && (float)$oRubricaVerificar->getValorLimite() < $oDados->iValor) {
          
          $oVarErroLimiteValor = (object)array('valor' => $oRubricaVerificar->getValorLimite());

          if(strtolower($oRubricaVerificar->getTipoBloqueio()) == 'b') {

            $sMessagemLimiteRubricaBloqueio    = $oRubricaVerificar->getCodigo() .' - ';
            $sMessagemLimiteRubricaBloqueio   .= _M( MENSAGENS_VALIDAR_LIMITE_RUBRICA . "limite_valor_excedido", $oVarErroLimiteValor );
            $aMessagensLimiteRubricaBloqueio[$oDados->iMatricula][] = $sMessagemLimiteRubricaBloqueio;
            $lExcedeuLimiteBloqueio = true;
          }
          
          if(strtolower($oRubricaVerificar->getTipoBloqueio()) == 'a') {

            $sMessagemLimiteRubricaAviso    = $oRubricaVerificar->getCodigo() .' - ';
            $sMessagemLimiteRubricaAviso   .= _M( MENSAGENS_VALIDAR_LIMITE_RUBRICA . "limite_valor_excedido", $oVarErroLimiteValor );
            $aMessagensLimiteRubricaAviso[$oDados->iMatricula][] = $sMessagemLimiteRubricaAviso;
          }

          $lExcedeuLimite = true;
        }
      }

      $oRetorno->erro = false;
      $oLote = LoteRegistrosPontoRepository::getInstanceByCodigo($oParam->iCodigoLote);

      $oRegistro    = new RegistroLoteRegistrosPonto();
      $oCompetencia = DBPessoal::getCompetenciaFolha();

      $oRegistro->setCodigo($oDados->iCodigo);
      $oRegistro->setCodigoLote($oParam->iCodigoLote);
      $oRegistro->setRubrica(RubricaRepository::getInstanciaByCodigo($oDados->sRubrica));
      $oRegistro->setServidor(ServidorRepository::getInstanciaByCodigo($oDados->iMatricula, $oCompetencia->getAno(), $oCompetencia->getMes()));
      $oRegistro->setValor($oDados->iValor);
      $oRegistro->setQuantidade($oDados->iQuantidade);
      $oRegistro->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
      $oRegistro->setFolhaPagamento(new FolhaPagamentoSalario());
      $oRegistro->setCompetencia($oDados->sCompetencia);
          
      $oRetorno->oRegistro = RegistroLoteRegistrosPontoRepository::persist($oRegistro);
      $oRetorno->oRegistro = $oRetorno->oRegistro->toStdClass();
      $oRetorno->erro      = false;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."sucesso_alterar_registro_lote"));

    }

    $oRetorno->messagemValidacaoLimites = '';

    if($lExcedeuLimite) {
      
      if($lExcedeuLimiteBloqueio) {

        $oRetorno->messagemValidacaoLimites  = _M(MENSAGENS . 'erro_alterar_rubrica');

        foreach ($aMessagensLimiteRubricaBloqueio as $iMatricula => $aMensagensBloqueio) {
          
          if(!isset($iMatriculaAnterior) || $iMatriculaAnterior != $iMatricula) {
            $oRetorno->messagemValidacaoLimites .= "\n\nServidor ". $iMatricula .': '.PHP_EOL;
          }

          $oRetorno->messagemValidacaoLimites .= implode(PHP_EOL, $aMensagensBloqueio);
          $iMatriculaAnterior = $iMatricula;
        }
        throw new BusinessException('');
      }

      foreach ($aMessagensLimiteRubricaAviso as $iMatricula => $aMensagensAviso) {
          
        if(!isset($iMatriculaAnterior) || $iMatriculaAnterior != $iMatricula) {

          if(isset($iMatriculaAnterior)) {
            $oRetorno->messagemValidacaoLimites .= "\n\n";
          }
          $oRetorno->messagemValidacaoLimites .= "Servidor ". $iMatricula .': '.PHP_EOL;
        }

        $oRetorno->messagemValidacaoLimites .= implode(PHP_EOL, $aMensagensAviso);
        $iMatriculaAnterior = $iMatricula;
      }
    }

    $oRetorno->messagemValidacaoLimites = urlencode($oRetorno->messagemValidacaoLimites);

    break;
  }

  db_fim_transacao();//Commit
} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->erro                     = true;
  $oRetorno->message                  = urlencode($oErro->getMessage());
  $oRetorno->messagemValidacaoLimites = urlencode($oRetorno->messagemValidacaoLimites);
}

echo $oJson->encode($oRetorno);