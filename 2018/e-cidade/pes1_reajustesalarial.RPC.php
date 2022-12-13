<?php
  require_once ("libs/db_stdlib.php");
  require_once ("libs/db_utils.php");
  require_once ("libs/db_app.utils.php");
  require_once ("libs/db_conecta.php");
  require_once ("libs/db_sessoes.php");
  require_once ("dbforms/db_funcoes.php");
  require_once ("libs/JSON.php");  
  
  $oJson                  = new services_json();
  $oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
  $oRetorno               = new stdClass();
  $oRetorno->iStatus      = 1;
  $oRetorno->sMessage     = '';
  
  try {
  
    db_inicio_transacao();
    
    switch ($oParam->sMetodo) {
  
      case "reajustaServidores":
        
          $lErro = false;

          $sWhere = "rh02_anousu = {$oParam->iAno} and rh02_mesusu = {$oParam->iMes}";

          
          if (($oParam->sTipoLancamento == 'm' && $oParam->sPara == 's') || $oParam->sTipoLancamento == 'a'){
            $sWhere .= " and rh02_salari > 0";
          }

          /**
           * Verifica se foi selecionado um tipo de resumo
           */
          if ($oParam->sTipoResumo != 0) {

            /**
             * Atribui o valor a variaval 'sCampo' de acordo com o
             * tipo de resumo selecionado(Lotação, Matricula, Cargo)
             */
            $aTiposResumo = array( 2 => "r70_codigo", 
                                   3 => "rh01_regist", 
                                   5 => "rh02_funcao");

            $sCampo = $aTiposResumo[$oParam->sTipoResumo];

            /**
             * Verifica se o tipo de Filtro selecionado foi o de 'intervalo'
             */
            if (isset($oParam->iIntervaloInicial) || isset($oParam->iIntervaloFinal)) {

              /**
               * Monta a condição respeitando se foi selecionado 
               * periodo inicial e final ou somente uma das 2 opções
               */
              if (isset($oParam->iIntervaloInicial) && isset($oParam->iIntervaloFinal)) {
                $sWhere .= " and {$sCampo} between {$oParam->iIntervaloInicial} and {$oParam->iIntervaloFinal}";
              } else if (isset($oParam->iIntervaloInicial)) {
                $sWhere .= " and {$sCampo} >= $oParam->iIntervaloInicia";
              } else if (isset($oParam->iIntervaloFinal)) {
                $sWhere .= " and {$sCampo} <= $oParam->iIntervaloFinal";
              }
            }

            /**
             * Verifica se o tipo de filtro selecionado foi o 'Selecionados'
             */
            if (isset($oParam->aRegistros)) {

              $sRegistros = implode(',', $oParam->aRegistros);
              $sWhere .= " and {$sCampo} in ({$sRegistros})";
            }
          }

          /**
           * Verifica o vinculo que foi selecionado. (Ativos, Inativos, Pensionistas, Inativos/Pensionistas)
           */
          if (isset($oParam->sVinculo)) {

            switch ( $oParam->sVinculo ) {
              case 'a': //Ativos
                $sWhere .= " and rh30_vinculo = 'A' ";
              break;
              case 'i': //Inativos
                $sWhere .= " and rh30_vinculo = 'I' ";
              break;
              case 'p': //Pensionista
                $sWhere .= " and rh30_vinculo = 'P' ";
              break;
              case 'ip': //Inativos Pensionistas
                $sWhere .= " and rh30_vinculo in ('I','P') ";
              break;
            }
          }

          /**
           * Verifica se deve ser verificado o tipo de reajuste
           */
          if (isset($oParam->sTipoReajuste)) {
            $sWhere .= " and rh01_reajusteparidade = '{$oParam->sTipoReajuste}'";
          }

          $oDaoRhPessoal = db_utils::getDao('rhpessoal');
          $sSqlRhPessoal = $oDaoRhPessoal->sql_query_cgmmov(null, "rh01_regist", 'z01_nome', $sWhere);
          $rsRhPessoal   = db_query($sSqlRhPessoal);

          if (!$rsRhPessoal){
            throw new DBException("Ocorreu um erro ao retornar os dados do servidor.");
          }

          if (pg_num_rows($rsRhPessoal) == 0){
            throw new BusinessException("Nenhum servidor encontrado para os filtros selecionados.");
          }

          /**
           * Percorre todos os servidores selecionados, adicionando os mesmo a classe ReajusteSalarial, 
           * para ser aplicado o calculo de reajuste.
           */
          $oReajusteSalarial = new ReajusteSalarial();
          $oReajusteSalarial->setPercentual($oParam->iPercentual);

          for ( $iServidor = 0; $iServidor < pg_num_rows($rsRhPessoal); $iServidor++){

            $oDadosServidor = db_utils::fieldsMemory($rsRhPessoal, $iServidor);
            $oServicor = ServidorRepository::getInstanciaByCodigo($oDadosServidor->rh01_regist, $oParam->iAno, $oParam->iMes);
            $oReajusteSalarial->adicionaServidor($oServicor);
          }


          if ($oParam->sTipoLancamento == 'm'){

            $sReajusteSalarial = serialize($oReajusteSalarial);
            db_putsession('DBReajusteSalarial', base64_encode($sReajusteSalarial));
            
            $oRetorno->redireciona = true;
          } else {
            
            if ($oReajusteSalarial->reajustaSalario()) {
              $oRetorno->sMessage = urlencode("Reajuste processado com sucesso.");
            };
          }



      break;
    }
    
    db_fim_transacao(false);
      
    
  } catch (Exception $eErro){
    
    db_fim_transacao(true);
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($eErro->getMessage());
  }

  echo $oJson->encode($oRetorno);