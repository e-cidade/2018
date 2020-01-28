<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/JSON.php"));


$oJson        = new services_json();
$oParam       = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
db_inicio_transacao();
try {

  switch ($oParam->exec) {

    case 'getReferenciasAtributo':

      if (!empty($oParam->iCodigoAtributo)) {

        $oAtributoExame = new AtributoExame($oParam->iCodigoAtributo);

        $oDadosAtributo                    = new stdClass();
        $oDadosAtributo->nome              = urlencode($oAtributoExame->getNome());
        $oDadosAtributo->codigo            = urlencode($oAtributoExame->getCodigo());
        $oDadosAtributo->codigo_unidade    = '';
        $oDadosAtributo->descricao_unidade = '';

        $oUnidadeMedida  = $oAtributoExame->getUnidadeMedida();
        if (!empty($oUnidadeMedida)) {

          $oDadosAtributo->codigo_unidade    = $oUnidadeMedida->getCodigo();
          $oDadosAtributo->descricao_unidade = url_encode($oUnidadeMedida->getNome());
        }
        $oRetorno->atributo= $oDadosAtributo;
      }
      break;

    case 'getValoresReferenciaAtributo' :

      if (!empty($oParam->iCodigoAtributo)) {

        $oRetorno->aValoresNumericos = array();

        $oAtributoExame     = new AtributoExame($oParam->iCodigoAtributo);
        $aValoresReferencia = $oAtributoExame->getValoresReferencia();
        foreach ($aValoresReferencia as $oValorReferencia) {

          $oValor                = new stdClass();

          $oLimiteIdade          = new stdClass();
          $oLimiteIdade->anos    = '';
          $oLimiteIdade->meses   = '';
          $oLimiteIdade->dias    = '';
          $oValorIdade           = $oValorReferencia->getIdadeFinal();
          if (!empty($oValorIdade)) {

            $oLimiteIdade->anos  = $oValorReferencia->getIdadeFinal()->getYears();
            $oLimiteIdade->meses = $oValorReferencia->getIdadeFinal()->getMonths();
            $oLimiteIdade->dias  = $oValorReferencia->getIdadeFinal()->getDays();
          }

          $iCasasDecimais        = $oValorReferencia->getCasasDecimaisApresentacao();
          $oValor->valor_inicial = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorMinimo());
          $oValor->valor_final   = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorMaximo());
          $oValor->sexo          = $oValorReferencia->getSexos();
          $oValor->codigo        = $oValorReferencia->getCodigo();
          $oValor->limite_idade  = $oLimiteIdade;

          $oRetorno->aValoresNumericos[] = $oValor;
        }
      }
      break;

      case 'getValorReferenciaAtributo' :

      if(!empty($oParam->iCodigo)) {
        $oValorReferencia = new AtributoValorReferenciaNumerico($oParam->iCodigo);

        $oValor                = new stdClass();

        $oLimiteIdadeInicial          = new stdClass();
        $oLimiteIdadeInicial->anos    = '';
        $oLimiteIdadeInicial->meses   = '';
        $oLimiteIdadeInicial->dias    = '';
        $oValorIdade           = $oValorReferencia->getIdadeInicial();
        if (!empty($oValorIdade)) {

          $oLimiteIdadeInicial->anos  = $oValorReferencia->getIdadeInicial()->getYears();
          $oLimiteIdadeInicial->meses = $oValorReferencia->getIdadeInicial()->getMonths();
          $oLimiteIdadeInicial->dias  = $oValorReferencia->getIdadeInicial()->getDays();
        }

        $oLimiteIdadeFinal          = new stdClass();
        $oLimiteIdadeFinal->anos    = '';
        $oLimiteIdadeFinal->meses   = '';
        $oLimiteIdadeFinal->dias    = '';
        $oValorIdade           = $oValorReferencia->getIdadeFinal();
        if (!empty($oValorIdade)) {

          $oLimiteIdadeFinal->anos  = $oValorReferencia->getIdadeFinal()->getYears();
          $oLimiteIdadeFinal->meses = $oValorReferencia->getIdadeFinal()->getMonths();
          $oLimiteIdadeFinal->dias  = $oValorReferencia->getIdadeFinal()->getDays();
        }

        $iCasasDecimais        = $oValorReferencia->getCasasDecimaisApresentacao();
        $oValor->casas_decimais = $iCasasDecimais;
        $oValor->valor_inicial = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorMinimo());
        $oValor->valor_final   = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorMaximo());
        $oValor->absurdo_minimo = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorAbsurdoMinimo());
        $oValor->absurdo_maximo = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorAbsurdoMaximo());
        $oValor->sexo          = $oValorReferencia->getSexos();
        $oValor->codigo        = $oValorReferencia->getCodigo();
        $oValor->limite_idade_inicial  = $oLimiteIdadeInicial;
        $oValor->limite_idade_final  = $oLimiteIdadeFinal;
        $oValor->tipo_calculo = $oValorReferencia->getTipoCalculo();
        $oValor->calculavel = $oValorReferencia->getCalculavel();

        $oValor->atributo_base = null;
        $oValor->atributo_base_nome = null;
        if($oValorReferencia->getAtributoBase()) {
          $oValor->atributo_base = $oValorReferencia->getAtributoBase()->getCodigo();
          $oValor->atributo_base_nome = $oValorReferencia->getAtributoBase()->getNome();
        }


        $oRetorno->oValorReferencia = $oValor;
      }
      break;

    case "salvarReferenciaNumerica":

      if (empty($oParam->iCodigoReferencia)) {

        $oDaoValorReferencia = new cl_lab_valorreferencia();
        /**
         * Verificamos se j� n�o existe nenhuma referencia cadastrada
         */
        $sSqlVerificaReferencia = $oDaoValorReferencia->sql_query_file(null, "la27_i_codigo",
                                                                       null, "la27_i_atributo = {$oParam->iAtributo}"
                                                                      );

        $rsVerificaReferencia = $oDaoValorReferencia->sql_record($sSqlVerificaReferencia);
        if ($oDaoValorReferencia->numrows == 0) {

          $oDaoValorReferencia->la27_i_atributo = $oParam->iAtributo;
          $oDaoValorReferencia->la27_i_unidade  = $oParam->iUnidadeMedida;
          $oDaoValorReferencia->incluir(null);
          if ($oDaoValorReferencia->erro_status == 0) {
            throw new BusinessException ("Erro ao incluir dados da refer�ncia");
          }
        }
      }

      if (!empty($oParam->iCodigoReferencia)) {

        $oDaoValorReferencia = new cl_lab_valorreferencia();
        /**
         * Verificamos se j� n�o existe nenhuma referencia cadastrada
         */
        $sSqlVerificaReferencia = $oDaoValorReferencia->sql_query_file(null, "la27_i_codigo",
                                                                       null, "la27_i_atributo = {$oParam->iAtributo}"
                                                                      );

        $rsVerificaReferencia = $oDaoValorReferencia->sql_record($sSqlVerificaReferencia);

        if ($oDaoValorReferencia->numrows > 0) {

          $oValorReferencia       = db_utils::fieldsmemory($rsVerificaReferencia, 0);
          $iCodigoValorReferencia = $oValorReferencia->la27_i_codigo;

          $oDaoValorReferencia->la27_i_codigo   = $iCodigoValorReferencia;
          $oDaoValorReferencia->la27_i_atributo = $oParam->iAtributo;
          $oDaoValorReferencia->la27_i_unidade  = $oParam->iUnidadeMedida;

          $oDaoValorReferencia->alterar($iCodigoValorReferencia);
        } else {


          $oDaoValorReferencia->la27_i_atributo = $oParam->iAtributo;
          $oDaoValorReferencia->la27_i_unidade  = $oParam->iUnidadeMedida;
          $oDaoValorReferencia->incluir(null);
          if ($oDaoValorReferencia->erro_status == 0) {
            throw new BusinessException ("Erro ao incluir dados da refer�ncia");
          }
        }
      }


      $oDaoValorReferenciaGrupo = new cl_lab_valorrefselgrupo();
      $oDaoValorReferenciaAlfa  = new cl_lab_tiporeferenciaalfa();
      $oAtributo                = new AtributoExame($oParam->iAtributo);

      /**
       * excluimos as referencias Selecionaveis
       */
      $sWhereGrupo  = "select la29_i_codigo ";
      $sWhereGrupo .= "  from lab_tiporeferenciaalfa ";
      $sWhereGrupo .= "  where la29_i_valorref = {$oAtributo->getCodigoReferencia()}";
      $oDaoValorReferenciaGrupo->excluir(null, "la51_i_referencia in ({$sWhereGrupo})");
      if ($oDaoValorReferenciaGrupo->erro_status == 0) {
        throw new BusinessException ("Erro ao remover dados da refer�ncia selecion�vel");
      }

      $oDaoValorReferenciaAlfa->excluir(null, "la29_i_valorref = {$oAtributo->getCodigoReferencia()}");
      if ($oDaoValorReferenciaAlfa->erro_status == 0) {
        throw new BusinessException ("Erro ao remover dados  da refer�ncia alfa numerica");
      }
      $oReferencia      = $oParam->oReferencia;
      if($oReferencia->iCodigo) {
        $oValorReferencia = new AtributoValorReferenciaNumerico($oReferencia->iCodigo);
      } else {
        $oValorReferencia = new AtributoValorReferenciaNumerico();
      }
      $oValorReferencia->setCalculavel($oReferencia->sCalculavel);
      $oValorReferencia->setValorMinimo($oReferencia->iValorMinimo);
      $oValorReferencia->setValorMaximo($oReferencia->iValorMaximo);
      $oValorReferencia->setValorAbsurdoMinimo($oReferencia->iValorAbsurdoMinimo);
      $oValorReferencia->setValorAbsurdoMaximo($oReferencia->iValorAbsurdoMaximo);
      $oValorReferencia->setCasasDecimaisApresentacao($oReferencia->iCasasDecimais);

      $oValorReferencia->limpaSexos();
      foreach($oReferencia->aSexos as $sSexo) {
        $oValorReferencia->adicionarSexo($sSexo);
      }

      $sIntervalor = "{$oReferencia->iDiasInicial} days {$oReferencia->iMesesInicial} months {$oReferencia->iAnosInicial} years";
      $oValorReferencia->setIdadeInicial(new DBInterval($sIntervalor));

      $sIntervalo = "{$oReferencia->iDiasFinal} days {$oReferencia->iMesesFinal} months {$oReferencia->iAnosFinal} years";
      $oValorReferencia->setIdadeFinal(new DBInterval($sIntervalo));
      $oValorReferencia->setTipoCalculo($oReferencia->iTipoCalculo);
      if ($oReferencia->iTipoCalculo == 2) {
        $oValorReferencia->setAtributoBase(new AtributoExame($oReferencia->iAtributoBase));
      }
      $oValorReferencia->salvar($oAtributo->getCodigoReferencia());
      db_fim_transacao();
      $oRetorno->message = urlencode("Refer�ncia salva com sucesso.");
      break;

    case 'removerReferenciaNumerica':

      $oValorReferencia = new AtributoValorReferenciaNumerico($oParam->iCodigo);
      $oValorReferencia->remover();
      db_fim_transacao();
      $oRetorno->message = urlencode("Refer�ncia removida com sucesso.");
      break;

  }
}  catch (BusinessException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
