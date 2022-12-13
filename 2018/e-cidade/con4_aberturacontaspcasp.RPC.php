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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$iInstituicao        = db_getsession("DB_instit");
$iAno                = db_getsession("DB_anousu");
$sCaminhoMensagem    = "financeiro.contabilidade.con4_aberturacontaspcasp.";
$oInstituicao        = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));

try {

  if (!USE_PCASP) {
    throw new BusinessException( _M( "{$sCaminhoMensagem}sem_pcasp_ativo" ) );
  }

  switch ($oParam->sExecucao) {

    /**
     * Retorna as contas do ano anterior, com exceção de:
     * - Contas que não tiveram movimentação ( débito e crédito zero )
     * - Contas vinculadas ao PCASP no ano de processamento
     *
     * @return array $oRetorno->aContasAnterior
     */
  	case 'getContasPlanoAnterior':

  	  if ( trim($oParam->sEstrutural)!= '' && !is_numeric( trim( $oParam->sEstrutural ) ) ) {
  	    throw new ParameterException( _M( $sCaminhoMensagem.'estrutural_invalido' ) );
  	  }

  	  $iAnoAnterior               = $iAno - 1;
  	  $sEstrutural                = '';
  	  $iTamanhoEstruturalDigitado = 0;

  	  if ((trim($oParam->sEstrutural)) != '') {

  	    $sEstrutural                = $oParam->sEstrutural;
    	  $iTamanhoEstruturalDigitado = strlen($sEstrutural);
  	  }

  	  $sWhereContasPlanoAnterior = " c61_instit = {$iInstituicao} ";
  	  $rsContasPlanoAnterior = db_planocontassaldo_matriz(
  	                                                       $iAnoAnterior,
  	                                                       "{$iAnoAnterior}-01-01",
  	                                                       "{$iAnoAnterior}-12-31",
  	                                                       false,
  	                                                       $sWhereContasPlanoAnterior
                                                         );

  	  if ( !$rsContasPlanoAnterior ) {
  	    throw new BusinessException( _M( "{$sCaminhoMensagem}contas_anteriores_nao_encontradas" ) );
  	  }

  	  $oRetorno->aContasAnterior = array();
  	  $iTotalContas              = pg_numrows( $rsContasPlanoAnterior );

  	  for ( $iContador = 0; $iContador < $iTotalContas; $iContador++ ) {

    	  $oConta = db_utils::fieldsMemory( $rsContasPlanoAnterior, $iContador, false, false, true );

    	  if ( $iTamanhoEstruturalDigitado > 0 && substr($oConta->estrutural, 0, $iTamanhoEstruturalDigitado) != $sEstrutural ) {
    	    continue;
    	  }

  	    if ( $oConta->saldo_anterior_debito == 0 && $oConta->saldo_anterior_credito == 0 ) {
  	      continue;
  	    }

  	    $oConta->iAno    = $iAnoAnterior;
  	    $oConta->iCodCon = $oConta->c61_codcon;

    	  if ( $oConta->c61_codcon == 0 ) {

    	    $oDaoConPlano    = new cl_conplano();
    	    $sWhereConPlano  = "c60_estrut = '{$oConta->estrutural}'";
    	    $sWhereConPlano .= " and c60_anousu = '{$iAnoAnterior}'";
    	    $sSqlConPlano    = $oDaoConPlano->sql_query_file( null, $iAnoAnterior, "c60_codcon", null, $sWhereConPlano );
    	    $rsConPlano      = $oDaoConPlano->sql_record( $sSqlConPlano );


    	    if ( $oDaoConPlano->numrows > 0 ) {
      	    $oConta->iCodCon = db_utils::fieldsMemory($rsConPlano, 0)->c60_codcon;
    	    }
    	  }

  	    $oDaoConPlanoConPlanoOrcamento    = new cl_conplanoconplanoorcamento();
  	    $sWhereConPlanoConPlanoOrcamento  = "     conplanoorcamento.c60_estrut = '{$oConta->estrutural}'";
  	    $sWhereConPlanoConPlanoOrcamento .= " and conplanoorcamento.c60_anousu = {$iAno}";
  	    $sSqlConPlanoConPlanoOrcamento    = $oDaoConPlanoConPlanoOrcamento->sql_query(
  	                                                                                   null,
  	                                                                                   'c72_sequencial',
  	                                                                                   null,
  	                                                                                   $sWhereConPlanoConPlanoOrcamento
  	                                                                                 );
  	    $rsConPlanoConPlanoOrcamento     = $oDaoConPlanoConPlanoOrcamento->sql_record($sSqlConPlanoConPlanoOrcamento);

  	    if ($oDaoConPlanoConPlanoOrcamento->numrows > 0) {
  	      continue;
  	    }

  	    $oConta->sContaPai           = db_le_mae_conplano( $oConta->estrutural );
  	    $oRetorno->aContasAnterior[] = $oConta;
  	  }

  	  break;

  	/**
  	 * Retorna as contas sintéticas do PCASP de acordo com o ano da sessão
  	 *
  	 * @return array $oRetorno->aContasPcasp
  	 */
  	case 'getContasPCASP':

  	  if ( trim($oParam->sEstrutural)!= '' && !is_numeric( trim( $oParam->sEstrutural ) ) ) {
  	    throw new ParameterException( _M( $sCaminhoMensagem.'estrutural_invalido' ) );
  	  }

  	  $oRetorno->aContasPcasp = array();
  	  $oDaoConPlano           = new cl_conplano();
  	  $sCamposConPlano        = "c60_codcon, c60_estrut, c60_descr, c60_anousu";
  	  $sWhereConPlano         = "c61_codcon is null and c60_anousu = {$iAno}";

  	  if ( trim($oParam->sEstrutural) != '' ) {
  	    $sWhereConPlano .= " and c60_estrut ilike '{$oParam->sEstrutural}%' ";
  	  }

  	  $sSqlConPlano = $oDaoConPlano->sql_query_planocontas(
  	                                                        null,
  	                                                        $sCamposConPlano,
  	                                                        "c60_estrut",
  	                                                        $sWhereConPlano
  	                                                      );
  	  $rsConPlano   = $oDaoConPlano->sql_record( $sSqlConPlano );

  	  if ( $oDaoConPlano->numrows == 0 ) {
  	    throw new BusinessException( _M( "{$sCaminhoMensagem}contas_pcasp_nao_encontradas" ) );
  	  }

	    $oRetorno->aContasPcasp = db_utils::getCollectionByRecord( $rsConPlano, false, false, true );

  	  break;

    case 'vincularContas':


      $aNaturezaDebito  = array('1' => 1, '3' => 1, '5' => 1, '7' => 1);
      $aNaturezaCredito = array('2' => 2, '4' => 2, '6' => 2, '8' => 2);

      db_inicio_transacao();
      $oContaPcasp = ContaPlanoPCASPRepository::getContaByCodigo($oParam->oContaPcasp->iCodigoConta,
                                                                 $oParam->oContaPcasp->iAnoConta, null,
                                                                 $oInstituicao->getSequencial()
                                                                );
      if (empty($oContaPcasp)) {
        throw new BusinessException(_M("{$sCaminhoMensagem}conta_pcasp_nao_existe"));
      }

      if ($oContaPcasp->hasReduzidoAnoInstituicao()) {
        throw new BusinessException(_M("{$sCaminhoMensagem}conta_pcasp_analitica"));
      }

      $oDaoConplano             = new cl_conplano();
      $aContasSinteticasCriadas = array();
      foreach ($oParam->aContasOrcamento as $oConta) {

        $sSqlConta = $oDaoConplano->sql_query_dados_banco($oConta->iCodigoConta, $oConta->iAnoConta);
        $rsConta   = $oDaoConplano->sql_record($sSqlConta);
        if ($oDaoConplano->numrows == 0) {
          throw new BusinessException(_M("{$sCaminhoMensagem}conta_plano_anterior_nao_existe"));
        }
        $oDadosConta      = db_utils::fieldsMemory($rsConta, 0);
        $sEstruturalConta = ContaPlano::getProximoEstruturalDisponivel($oContaPcasp->getEstrutural());
        if ($oDadosConta->c61_reduz == '') {
          $aContasSinteticasCriadas[$oDadosConta->c60_estrut] = $sEstruturalConta;
        }

        $sEstruturalPai = db_le_mae_conplano($oDadosConta->c60_estrut);

        /**
         * caso a conta seja analitica no plano anterior, e criamos a conta pai dessa conta no PCASP,
         * devemos levar a conta como filha da conta criada no pcasp;
         */
        if (isset($aContasSinteticasCriadas[$sEstruturalPai])) {
          $sEstruturalConta = ContaPlano::getProximoEstruturalDisponivel($aContasSinteticasCriadas[$sEstruturalPai]);
        }
        /*
         *Criamos um de para pelo tipo de sistema  da conta (ConsistemaConta)
         */
        $iConsistemaConta    = '0';
        $sIndicadorSuperAvit = 'N';
        switch (trim($oDadosConta->c52_descrred)) {

          case 'O':

            $iConsistemaConta = 1;
            break;

          case 'F':
          case 'P':

            $iConsistemaConta    = 2;
            $sIndicadorSuperAvit = 'F';
            break;
          case 'C':

            $iConsistemaConta = 3;
            break;
        }

        /**
         * Migramos os dados de contabancaria, caso a conta seja conta bancaria
         */
        $oContaBancaria = null;
        if ($oDadosConta->c52_codsis == 6 && !empty( $oDadosConta->c61_reduz ) ) {

          /**
           * Cancelamos o reduzido na saltes
           */
          if (!empty($oDadosConta->c61_reduz)) {

            $oDaoSaltes             = new cl_saltes();
            $oDaoSaltes->k13_reduz  = $oDadosConta->c61_reduz;
            $oDaoSaltes->k13_conta  = $oDadosConta->c61_reduz;
            $oDaoSaltes->k13_limite = "{$iAno}-01-01";
            $oDaoSaltes->alterar($oDadosConta->c61_reduz);
            if ($oDaoSaltes->erro_status == 0) {
              throw new BusinessException(_M("{$sCaminhoMensagem}erro_desabilitar_saltes"));
            }
          }
          if (!empty($oDadosConta->c56_contabancaria)) {
            $oContaBancaria = new ContaBancaria($oDadosConta->c56_contabancaria);
          } else {

            if ($oDadosConta->c63_banco != '' && $oDadosConta->c63_agencia != '' && $oDadosConta->c63_conta != '') {

              $sIdentificador = $oDadosConta->c63_identificador;
              if ($oDadosConta->c63_identificador == '') {
                $sIdentificador = ' ';
              }
              $oContaBancaria = new ContaBancaria();
              $oContaBancaria->setCodigoBanco($oDadosConta->c63_banco);
              $oContaBancaria->setCodigoOperacao($oDadosConta->c63_codigooperacao);
              $oContaBancaria->setNumeroAgencia($oDadosConta->c63_agencia);
              $oContaBancaria->setDVAgencia($oDadosConta->c63_dvagencia);
              $oContaBancaria->setNumeroConta($oDadosConta->c63_conta);
              $oContaBancaria->setDVConta($oDadosConta->c63_dvconta);
              $oContaBancaria->setIdentificador($sIdentificador);
              $oContaBancaria->setTipoConta($oDadosConta->c63_tipoconta);
              $oContaBancaria->setPlanoConta(true);
              $oContaBancaria->salvar();
            }
          }
        }

        $iSistemaConta = $oDadosConta->c60_codsis;
        $iNaturaSaldo  = 0;
        /**
         * Criamos a nova Conta no PCASP, Conforme Dados do Orcamento
         */
        $oNovaContaPcasp = new ContaPlanoPCASP();
        $oNovaContaPcasp->setAno($iAno);
        $oNovaContaPcasp->setClassificacaoConta(new ClassificacaoConta($oDadosConta->c60_codcla));
        $oNovaContaPcasp->setDescricao($oDadosConta->c60_descr);
        $oNovaContaPcasp->setEstrutural($sEstruturalConta);
        $oNovaContaPcasp->setFinalidade($oDadosConta->c60_finali);
        $oNovaContaPcasp->setFuncao($oDadosConta->c60_funcao);
        $oNovaContaPcasp->setIdentificadorFinanceiro($sIndicadorSuperAvit);
        if (!empty($oContaBancaria)) {
          $oNovaContaPcasp->setContaBancaria($oContaBancaria);
        }
        $oNovaContaPcasp->setNaturezaSaldo($oDadosConta->c60_naturezasaldo);
        $oNovaContaPcasp->setSistemaConta(SistemaContaRepository::getSistemaContaByCodigo($iSistemaConta));
        $oNovaContaPcasp->setSubSistema(new SubSistemaConta($iConsistemaConta));

        if (!empty($oDadosConta->c61_reduz)) {

          $oNovaContaPcasp->setRecurso($oDadosConta->c61_codigo);
          $oNovaContaPcasp->setInstituicao($oInstituicao->getSequencial());
        }

        $oNovaContaPcasp->salvar();
        if (!empty($oDadosConta->c61_reduz)) {
          $oNovaContaPcasp->persistirReduzido();
        }

        /**
         * Verificamos se a conta do ano anterior existe no plano orcamentario;
         * Caso exista devemos apenas vincular a conta com o plano orcamentario.
         */
        $oContaOrcamento = ContaOrcamentoRepository::getContaPorEstrutural($oDadosConta->c60_estrut,
                                                                           $iAno,
                                                                           $oInstituicao
                                                                          );

        if (!empty($oContaOrcamento) && $oContaOrcamento->getPlanoContaPCASP() == null) {

          $oContaOrcamento->setPlanoContaPCASP($oNovaContaPcasp);
          $oContaOrcamento->salvar();
        }
      }

      db_fim_transacao(false);

      $oRetorno->sMensagem = urlencode( _M( $sCaminhoMensagem."contas_vinculadas" ) );
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
catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage()."Classificacao:{$oDadosConta->c60_estrut} - {$oDadosConta->c60_codcla}");
}

echo $oJson->encode( $oRetorno );
?>