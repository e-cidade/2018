<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
define( 'MENSAGENS_TRE4_VEICULOTRANSPORTE_RPC', 'educacao.transporteescolar.tre4_veiculotransporte.' );

require_once ("std/db_stdClass.php");
require_once ("std/DBNumber.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvar":

      $oVeiculoTransporte = new VeiculoTransporte($oParam->iCodigoVeiculo);
      $oVeiculoTransporte->setIdentificacao(db_stdClass::normalizeStringJsonEscapeString($oParam->sIdentificacao));
      $oVeiculoTransporte->setNumeroPassageiros($oParam->iNumeroPassageiros);
      $oVeiculoTransporte->setTipoTransporte(TipoTransporteRepository::getTipoByCodigo($oParam->iTipoTransporte));
      if (!empty($oParam->iVinculoVeiculo)) {
        $oVeiculoTransporte->setVeiculo(new Veiculo($oParam->iVinculoVeiculo));
      }
      if (!empty($oParam->iVinculoCgm)) {
        $oVeiculoTransporte->setEmpresaResponsavel(new CgmJuridico($oParam->iVinculoCgm));
      }
      $oVeiculoTransporte->salvar();
      $oRetorno->iCodigoVeiculo = $oVeiculoTransporte->getCodigo();

      $oRetorno->message = urlencode( _M( MENSAGENS_TRE4_VEICULOTRANSPORTE_RPC . 'confirma_salvar' ) );
      break;

    case 'excluir':

      $oVeiculoTransporte = new VeiculoTransporte($oParam->iCodigoVeiculo);
      $oVeiculoTransporte->remover();
      $oRetorno->message = urlencode( _M( MENSAGENS_TRE4_VEICULOTRANSPORTE_RPC . 'confirma_excluir' ) );
      break;

    case 'getDados':

        $oVeiculoTransporte = new VeiculoTransporte($oParam->iCodigoVeiculo);

        $oDadosRetorno                           = new stdClass();
        $oDadosRetorno->iCodigoVeiculo           = $oVeiculoTransporte->getCodigo();
        $oDadosRetorno->iNumeroPassageiros       = $oVeiculoTransporte->getNumeroDePassageiros();
        $oDadosRetorno->sIdentificacao           = urldecode($oVeiculoTransporte->getIdentificacao());
        $oDadosRetorno->iTipoTransporte          = $oVeiculoTransporte->getTipoTransporte()->getCodigo();
        $oDadosRetorno->sDescricaoTipoTransporte = urlencode($oVeiculoTransporte->getTipoTransporte()->getDescricao());
        $oDadosRetorno->iVinculoVeiculo          = '';
        $oDadosRetorno->sMarcaVinculoTransporte  = '';
        $oDadosRetorno->iVinculoCgm              = '';
        $oDadosRetorno->sNomeEmpresa             = '';
        if ($oVeiculoTransporte->getVeiculo() != "") {

          $oDadosRetorno->iVinculoVeiculo         = $oVeiculoTransporte->getVeiculo()->getCodigo();
          $oDadosRetorno->sMarcaVinculoTransporte = urlencode($oVeiculoTransporte->getVeiculo()->getMarca()->getNome());
        }

        if ($oVeiculoTransporte->getEmpresaResponsavel() != "") {

          $oDadosRetorno->iVinculoCgm  = $oVeiculoTransporte->getEmpresaResponsavel()->getCodigo();
          $oDadosRetorno->sNomeEmpresa = urlencode($oVeiculoTransporte->getEmpresaResponsavel()->getNomeCompleto());
        }
        $oRetorno->dados = $oDadosRetorno;
        break;

    case "salvarVeiculoLinha":

      $oLinhaItinerarioHorario = new LinhaItinerarioHorario($oParam->iHorario);

      $aCodigoVeiculosVinculados = array();
      $lVeiculoJaExistente       = false;
      $oMensagem                 = new stdClass();
      $oMensagem->sVeiculo       = '';
      $oMensagem->sNomeLinha     = '';
      $oMensagem->sHorario       = '';
      $oMensagem->sTipo          = '';

      foreach ($oLinhaItinerarioHorario->getTransportes() as $oVeiculoVinculado) {

        if ($oVeiculoVinculado->getCodigo() == $oParam->iVeiculo) {

          $lVeiculoJaExistente = true;

          $oMensagem->sNomeLinha  = $oLinhaItinerarioHorario->getLinhaItinerario()->getLinhaTransporte()->getNome();
          $oMensagem->sHorario    = $oLinhaItinerarioHorario->getHoraSaida().' - '.$oLinhaItinerarioHorario->getHoraChegada();
          $oMensagem->sTipo       = $oLinhaItinerarioHorario->getTipoItinerario() == 1 ? 'Ida': 'Retorno';

          if ($oVeiculoVinculado->getEmpresaResponsavel() != '') {
            $oMensagem->sVeiculo = $oVeiculoVinculado->getEmpresaResponsavel()->getNome();
          } else {
            $oMensagem->sVeiculo = $oVeiculoVinculado->getVeiculo()->getModelo();
          }
        }
      }

      if( $lVeiculoJaExistente ) {
        throw new BusinessException( _M( MENSAGENS_TRE4_VEICULOTRANSPORTE_RPC . 'veiculo_ja_vinculado', $oMensagem ) );
      }

      foreach ($oLinhaItinerarioHorario->getTransportes() as $oVeiculoTransporte) {
        $oLinhaItinerarioHorario->adicionarTransporte($oVeiculoTransporte);
      }

      $oLinhaItinerarioHorario->adicionarTransporte(new VeiculoTransporte($oParam->iVeiculo));
      $oLinhaItinerarioHorario->salvarVeiculo();

      $oRetorno->message = urlencode( _M( MENSAGENS_TRE4_VEICULOTRANSPORTE_RPC . 'confirma_salvar_vinculo' ) );

      break;

    case "getVeiculosHorario":

      $oRetorno->aVeiculosHorario = array();
      $oLinhaTransporte           = new LinhaTransporte($oParam->iLinha);

      foreach ($oLinhaTransporte->getItinerarios() as $oLinhaItinerario) {

        foreach ($oLinhaItinerario->getHorarios() as $oLinhaItinerarioHorario) {

          foreach ($oLinhaItinerarioHorario->getTransportes() as $oVeiculoTransporte) {

            $oVinculosVeiculoLinha                     = new stdClass();
            $oVinculosVeiculoLinha->iVeiculoHorario    = $oVeiculoTransporte->getCodigo();
            $oVinculosVeiculoLinha->iItinerarioHorario = $oLinhaItinerarioHorario->getCodigo();
            $oVinculosVeiculoLinha->sItinerario        = urlencode("Ida");
            if ($oLinhaItinerario->getTipo() == 2) {
              $oVinculosVeiculoLinha->sItinerario = urlencode("Retorno");
            }
            $sHorario = $oLinhaItinerarioHorario->getHoraSaida()." à ".$oLinhaItinerarioHorario->getHoraChegada();
            $oVinculosVeiculoLinha->sHorario           = urlencode($sHorario);

            if ($oVeiculoTransporte->getEmpresaResponsavel() != '') {
              $oVinculosVeiculoLinha->sVeiculo         = urlencode($oVeiculoTransporte->getEmpresaResponsavel()->getNome());
            } else {
              $oVinculosVeiculoLinha->sVeiculo         = urlencode($oVeiculoTransporte->getVeiculo()->getModelo());
            }

            $oVinculosVeiculoLinha->iNumeroPassageiros = $oVeiculoTransporte->getNumeroDePassageiros();
            $oRetorno->aVeiculosHorario[]              = $oVinculosVeiculoLinha;

          }
        }
      }

      break;

    /**
     * Remove o vínculo de um veículo com a linha de transporte
     * @param integer $oParam->iItinerarioHorario - Codigo de LinhaItinerarioHorario
     * @param integer $oParam->iVeiculoHorario    - Codigo do vinculo do veiculo com o itinerario
     */
    case 'removerVinculoVeiculo':

      if (isset($oParam->iItinerarioHorario) && isset($oParam->iVeiculoHorario)) {

        $oLinhaItinerarioHorario = new LinhaItinerarioHorario($oParam->iItinerarioHorario);
        foreach ($oLinhaItinerarioHorario->getTransportes() as $oVeiculoTransporte) {

          if ($oParam->iVeiculoHorario == $oVeiculoTransporte->getCodigo()) {
            $oLinhaItinerarioHorario->removerTransporte($oVeiculoTransporte);
          }
        }
        $oLinhaItinerarioHorario->salvarVeiculo();
      }

      $oRetorno->message = urlencode( _M( MENSAGENS_TRE4_VEICULOTRANSPORTE_RPC . 'confirma_remover_vinculo' ) );

      break;
  }

  db_fim_transacao(false);
} catch (Exception $eException) {

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($eException->getMessage());
  db_fim_transacao(true);
}
echo $oJson->encode($oRetorno);