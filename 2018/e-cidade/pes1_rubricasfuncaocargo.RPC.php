<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson = new services_json();
$oParametros = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case "getDados":

      $oDados = (object) array(
        'cargo' => array(),
        'funcao' => array(),
      );
      $clcargorhrubricas = new cl_cargorhrubricas();
      $clfuncaorhrubricas = new cl_funcaorhrubricas();

      $sCamposRubricasFuncao  = "rh176_cargo as funcao_codigo, rh04_descr as funcao_descricao, ";
      $sCamposRubricasFuncao .= "rh176_rubrica as rubrica_codigo, rh27_descr as rubrica_descricao, ";
      $sCamposRubricasFuncao .= "rh176_quantidade as rubrica_quantidade, rh176_valor as rubrica_valor ";
      $sSqlRubricasFuncao = $clcargorhrubricas->sql_query(
        null, $sCamposRubricasFuncao, 'rh176_cargo, rh176_rubrica', 'rh176_instit = ' . db_getsession('DB_instit')
      );

      $rsRubricasFuncao = db_query($sSqlRubricasFuncao); 

      if ($rsRubricasFuncao) {

        $aRubricasFuncao = db_utils::getCollectionByRecord($rsRubricasFuncao);

        foreach ($aRubricasFuncao as $oFuncaoRubrica) {

          if (!isset($oDados->funcao[$oFuncaoRubrica->funcao_codigo])) {

            $oDados->funcao[$oFuncaoRubrica->funcao_codigo] = array(
              'codigo' => $oFuncaoRubrica->funcao_codigo,
              'descricao' => $oFuncaoRubrica->funcao_descricao,
              'rubricas' => array()
            );
          }

          $oDados->funcao[$oFuncaoRubrica->funcao_codigo]['rubricas'][$oFuncaoRubrica->rubrica_codigo] = array(
            'codigo' => $oFuncaoRubrica->rubrica_codigo,
            'descricao' => $oFuncaoRubrica->rubrica_descricao,
            'quantidade' => $oFuncaoRubrica->rubrica_quantidade,
            'valor' => $oFuncaoRubrica->rubrica_valor,
          );
        }
      }

      $sCamposRubricasCargo  = "rh177_funcao as cargo_codigo, rh37_descr cargo_descricao, ";
      $sCamposRubricasCargo .= "rh177_rubrica as rubrica_codigo, rh27_descr as rubrica_descricao, ";
      $sCamposRubricasCargo .= "rh177_quantidade as rubrica_quantidade, rh177_valor as rubrica_valor ";
      $sSqlRubricasCargo = $clfuncaorhrubricas->sql_query(
        null, $sCamposRubricasCargo, 'rh177_funcao, rh177_rubrica', 'rh177_instit = ' . db_getsession('DB_instit')
      );

      $rsRubricasCargo = db_query($sSqlRubricasCargo); 

      if ($rsRubricasCargo) {

        $aRubricasCargo = db_utils::getCollectionByRecord($rsRubricasCargo);

        foreach ($aRubricasCargo as $oCargoRubrica) {

          if (!isset($oDados->cargo[$oCargoRubrica->cargo_codigo])) {

            $oDados->cargo[$oCargoRubrica->cargo_codigo] = array(
              'codigo' => $oCargoRubrica->cargo_codigo,
              'descricao' => $oCargoRubrica->cargo_descricao,
              'rubricas' => array()
            );
          }

          $oDados->cargo[$oCargoRubrica->cargo_codigo]['rubricas'][$oCargoRubrica->rubrica_codigo] = array(
            'codigo' => $oCargoRubrica->rubrica_codigo,
            'descricao' => $oCargoRubrica->rubrica_descricao,
            'quantidade' => $oCargoRubrica->rubrica_quantidade,
            'valor' => $oCargoRubrica->rubrica_valor,
          );
        }
      }

      $oRetorno->oDados = $oDados;

    break;

    case "salvarDados":

      $clcargorhrubricas = new cl_cargorhrubricas();
      $clfuncaorhrubricas = new cl_funcaorhrubricas();

      $clcargorhrubricas->excluir(null, "rh176_instit = " . db_getsession('DB_instit'));
      $clfuncaorhrubricas->excluir(null, "rh177_instit = ". db_getsession('DB_instit'));

      foreach ($oParametros->oDados as $sTipo => $oDados) {

        if ($sTipo == 'cargo') {

          $sClasse = 'cl_funcaorhrubricas';
          $sSigla = 'rh177';
          $sVinculo = 'funcao';

        } elseif ($sTipo == 'funcao') {

          $sClasse = 'cl_cargorhrubricas'; 
          $sSigla = 'rh176';
          $sVinculo = 'cargo';

        } else {
          throw new BusinessException("Erro ao incluir vínculos de rubricas para cargo/função: Tipo inválido.");
        }

        foreach ($oDados as $iCodigo => $oDadosTipo) {

          foreach ($oDadosTipo->rubricas as $oRubrica) {

            $oDao = new $sClasse();
            $oDao->{$sSigla . "_sequencial"} = null;
            $oDao->{$sSigla . "_" . $sVinculo} = $iCodigo;
            $oDao->{$sSigla . "_rubrica"} = $oRubrica->codigo;
            $oDao->{$sSigla . "_instit"} = db_getsession('DB_instit');
            $oDao->{$sSigla . "_quantidade"} = "$oRubrica->quantidade";
            $oDao->{$sSigla . "_valor"} = "$oRubrica->valor";
            $oDao->incluir(null);

            if ($oDao->erro_status == '0') {
              throw new DBException("Erro ao incluir vínculos de rubricas para cargo/função.\n" . $oDao->erro_msg);
            }
          }
        }

      }
                                                         
      $oRetorno->mensagem = urlencode("Vínculos de rubricas para Cargos / Funções salvo com sucesso!");

    break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro = true;
  $oRetorno->mensagem = urlencode(str_replace('\n', "\n", $eErro->getMessage()));
}

echo $oJson->encode($oRetorno);
