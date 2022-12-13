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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_concilia_classe.php");
require_once("classes/db_conciliaitem_classe.php");
require_once("classes/db_conciliacor_classe.php");
require_once("classes/db_conciliapendcorrente_classe.php");
require_once("classes/db_conciliaextrato_classe.php");
require_once("classes/db_conciliapendextrato_classe.php");
require_once("std/db_stdClass.php");

$clconcilia             = new cl_concilia;
$clconciliaitem         = new cl_conciliaitem;
$clconciliacor          = new cl_conciliacor;
$clconciliapendcorrente = new cl_conciliapendcorrente;
$clconciliaextrato      = new cl_conciliaextrato;
$clconciliapendextrato  = new cl_conciliapendextrato;
$objJSON                = new Services_JSON();

$erromsg = "";
$sqlerro = false;

$lJustificativaCaixa = false;
$strJSONExtrato = '';
$strJSONAutent  = '';

db_postmemory($HTTP_POST_VARS);

if ( !empty($strJSONExtrato) ) {
  $strJSONExtrato = str_replace("\\","",$strJSONExtrato);
}

if ( !empty($strJSONAutent) ) {
  $strJSONAutent = str_replace("\\","",$strJSONAutent);
}

if ($solicitacao == 'gravarJustificativaPendente') {

  try {

    if ( $lJustificativaCaixa == "true" ) {

      $oDataConciliacao = new DBDate($sDataConciliacao);
      $sData            = $oDataConciliacao->convertTo(DBDate::DATA_EN);

      $oDaoConciliaPendenteCorrente= new cl_conciliapendcorrente();
      $sSqlCodigo                  = $oDaoConciliaPendenteCorrente->sql_query_file( 
          null,
          "k89_sequencial",
          null,
          "   k89_id       = {$iCodigoCaixaLinha}
          and k89_autent   = {$iCodigoAutenticacao}
          and k89_concilia = {$iCodigoConciliacao}
          and k89_data     = '{$sData}' "
          ); 
      $rsCodigo                    = $oDaoConciliaPendenteCorrente->sql_record($sSqlCodigo);

      if ( $oDaoConciliaPendenteCorrente->erro_status == "0" ) {
        throw new Exception("Erro ao Buscar cdigo para Justificativa: " . pg_last_error());
      }

      if ( pg_num_rows($rsCodigo) == "0" ) {
        throw new Exception("Nenhum registro para a Conciliao($iCodigoConciliacao), Caixa($iCodigoCaixaLinha) e Autenticao($iCodigoAutenticacao) informados.");
      }

      $iSequencialCorrentePendente = db_utils::fieldsMemory($rsCodigo, 0)->k89_sequencial;

      $oDaoConciliaPendenteCorrente->k89_justificativa = corrigeCodificacaoCaracteres($sJustificativa);
      $oDaoConciliaPendenteCorrente->k89_sequencial    = $iSequencialCorrentePendente;
      $oDaoConciliaPendenteCorrente->alterar($iSequencialCorrentePendente);

      if ( $oDaoConciliaPendenteCorrente->erro_status == '0' ) {
        throw new Exception("Erro ao Alterar Justificativa: " . pg_last_error());
      }


    } else {

      $oDaoConciliaPendenteExtrato = new cl_conciliapendextrato();
      $sSqlCodigo                  = $oDaoConciliaPendenteExtrato->sql_query_file( 
          null,
          "k88_sequencial",
          null,
          "   k88_extratolinha = {$iCodigoExtratoLinha} 
          and k88_concilia     = {$iCodigoConciliacao}  "
      ); 
      $rsCodigo                    = $oDaoConciliaPendenteExtrato->sql_record($sSqlCodigo);

      if ( $oDaoConciliaPendenteExtrato->erro_status == "0" ) {
        throw new Exception("Erro ao Buscar cdigo para Justificativa: " . pg_last_error());
      }

      if ( pg_num_rows($rsCodigo) == 0 ) {
        throw new Exception("Nenhum registro para a Conciliao($iCodigoConciliacao) e Linha do Extrato($iCodigoExtratoLinha) informados.");
      }
      $iSequencialExtratoPendente = db_utils::fieldsMemory($rsCodigo, 0)->k88_sequencial;

      $oDaoConciliaPendenteExtrato->k88_justificativa = corrigeCodificacaoCaracteres($sJustificativa);
      $oDaoConciliaPendenteExtrato->k88_sequencial    = $iSequencialExtratoPendente;
      $oDaoConciliaPendenteExtrato->alterar($iSequencialExtratoPendente);


      if ( $oDaoConciliaPendenteExtrato == "0" ) {
        throw new Exception("Erro ao Alterar Justificativa: " . pg_last_error());
      }

    }
    db_fim_transacao(false);
    echo 1;//"Alterado com Sucesso";

  } catch ( Exception $eErro ) {
    db_fim_transacao(true);
    echo $eErro->getMessage();
  }
  exit;

} else if ($solicitacao == 'manual') {

  db_inicio_transacao();
  /* insere o item */ 
  $clconciliaitem->k83_conciliatipo = 1;
  $clconciliaitem->k83_concilia     = $concilia;
  $clconciliaitem->k83_hora         = db_hora();
  $clconciliaitem->k83_usuario      = db_getsession('DB_id_usuario');
  $clconciliaitem->incluir(null);
  $erromsg = $clconciliaitem->erro_msg;
  if($clconciliaitem->erro_status == 0){
    $sqlerro = true;
  }
  if (isset($strJSONExtrato) && $strJSONExtrato != ''){
    // inserindo os itens do extrato
    $arrayObjExtrato = $objJSON->decode(utf8_encode($strJSONExtrato));
    foreach ($arrayObjExtrato as $i => $objExtrato){
      if (is_object($objExtrato)){


        /*
         * verificamos se existe uma conciliação para o extrato em alguma data
         */
        $sSqlConciliaExtrato = "select k68_data,
                                       k68_contabancaria
                              from conciliaextrato
                                   inner join conciliaitem on conciliaitem.k83_sequencial = conciliaextrato.k87_conciliaitem
                                   inner join concilia     on concilia.k68_sequencial     = conciliaitem.k83_concilia
                             where k87_extratolinha = {$objExtrato->extratolinha}
                               and k68_contabancaria = $conta";
        $rsConciliaExtrato   = $clconciliaextrato->sql_record($sSqlConciliaExtrato);
        if ($clconciliaextrato->numrows > 0) {

           $oDados = db_utils::fieldsMemory($rsConciliaExtrato, 0, true); 
           $sqlerro  = true;
           $erromsg  = "Não foi possível realizar a conciliação da linha do extrato {$objExtrato->extratolinha} - {$objExtrato->numeroDocumento}\n";
           $erromsg .= "Registro conciliado na data {$oDados->k68_data} na conta {$oDados->k68_contabancaria}";
           break;
        }

        // so concilia se nao estiver conciliado
        if($objExtrato->classe != 'conciliado'){
          // verifica se esta tentando conciliar uma pendencia, se sim, deleta a pendencia das conciliacoes posteriores
          $sqlPendenciasExtrato  = " select k88_sequencial ";     
          $sqlPendenciasExtrato .= "   from conciliapendextrato ";
          $sqlPendenciasExtrato .= "        inner join concilia on k88_concilia = k68_sequencial ";
          $sqlPendenciasExtrato .= "  where k68_data         >= '".$data."'";
          $sqlPendenciasExtrato .= "    and k68_contabancaria = $conta ";
          $sqlPendenciasExtrato .= "    and k88_extratolinha  = ".$objExtrato->extratolinha;

          $rsPendenciasExtrato   = $clconciliapendextrato->sql_record($sqlPendenciasExtrato);
          $intPendenciasExtrato  = $clconciliapendextrato->numrows;
          if ($intPendenciasExtrato > 0) {
            
            $conciliaorigem = 2;
            for ($ind = 0; $ind < $intPendenciasExtrato; $ind ++) {
              
              db_fieldsmemory($rsPendenciasExtrato,$ind);
              $clconciliapendextrato->excluir($k88_sequencial);
                
              if ($clconciliapendextrato->erro_status == 0) {
                
                $sqlerro = true;
                $erromsg = "conciliapendextrato - ".$clconciliapendextrato->erro_msg;
                break;
              }
            }
          } else {
            $conciliaorigem = 1;
          }
        
          $clconciliaextrato->k87_conciliaitem   = $clconciliaitem->k83_sequencial;
          $clconciliaextrato->k87_extratolinha   = $objExtrato->extratolinha;
          $clconciliaextrato->k87_conciliaorigem = $conciliaorigem;
          $clconciliaextrato->incluir(null);
          if($clconciliaextrato->erro_status == 0){
            $sqlerro = true;
            $erromsg = "ConciliaExtrato - ".$clconciliaextrato->erro_msg;
            break;
          }
        }
      }
    }
  }

//  $arrayObjAutent = $objJSON->decode($strJSONAutent);
  if(isset($strJSONAutent) && $strJSONAutent != ""){

//    $strJSONAutent = str_replace("&","",$strJSONAutent);
//    echo $strJSONAutent;
//    $strJSONAutent = urldecode($strJSONAutent);
//    echo($strJSONAutent);

    $arrayObjAutent = $objJSON->decode(utf8_encode($strJSONAutent));

    foreach ( $arrayObjAutent as $i => $objAutent ){
      if (is_object($objAutent)){


        /*
         * verificamos se existe uma conciliação para a autenticação em alguma data
         */
        $sSqlConciliaCorrente = "select k68_data, 
                                        k68_contabancaria 
                               from conciliacor
                                    inner join conciliaitem on conciliaitem.k83_sequencial = conciliacor.k84_conciliaitem
                                    inner join concilia     on concilia.k68_sequencial     = conciliaitem.k83_concilia
                              where k84_id     = {$objAutent->caixa}
                                and k84_data   = '{$objAutent->data}'
                                and k84_autent = $objAutent->autent
                                and k68_contabancaria = $conta";
        $rsConciliaCorrente   = $clconciliacor->sql_record($sSqlConciliaCorrente);
        if ($clconciliacor->numrows > 0) {

           $oDados = db_utils::fieldsMemory($rsConciliaCorrente, 0, true);  
           
           $sqlerro  = true;
           $erromsg  = "Não foi possível realizar a conciliação da autenticação do caixa {$objAutent->caixa} na data {$objAutent->data} com o autent. {$objAutent->autent} \n";
           $erromsg .= "Registro já conciliado na data {$oDados->k68_data} na conta {$oDados->k68_contabancaria}";
           break;
        }

        if($objAutent->classe != 'conciliado'){
          // verifica se esta tentando conciliar uma pendencia, se sim, deleta a pendencia das conciliacoes posteriores
          $sqlPendenciasAutent  = " select k89_sequencial ";
          $sqlPendenciasAutent .= "   from conciliapendcorrente ";
          $sqlPendenciasAutent .= "        inner join concilia on k89_concilia = k68_sequencial ";
          $sqlPendenciasAutent .= "  where k68_data   >= '".$data."'";
          $sqlPendenciasAutent .= "    and k68_contabancaria = $conta ";
          $sqlPendenciasAutent .= "    and k89_id      = ".$objAutent->caixa;
          $sqlPendenciasAutent .= "    and k89_data    = '".substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2)."'";
          $sqlPendenciasAutent .= "    and k89_autent  = ".$objAutent->autent;

          $rsPendenciasAutent  = $clconciliapendcorrente->sql_record($sqlPendenciasAutent);
          $intPendenciasAutent = $clconciliapendcorrente->numrows;
          if ($intPendenciasAutent > 0){
            $conciliaorigem = 2;
            for($ind = 0; $ind < $intPendenciasAutent; $ind ++ ){
              db_fieldsmemory($rsPendenciasAutent,$ind);
              $clconciliapendcorrente->excluir($k89_sequencial);  
              if( $clconciliapendcorrente->erro_status == 0){
                $sqlerro = true;
                $erromsg = "conciliapendcorrente - ".$clconciliapendcorrente->erro_msg;
                break;
              }
            }
          }else{
            $conciliaorigem = 1;
          }
          $clconciliacor->k84_conciliaitem   = $clconciliaitem->k83_sequencial;
          $clconciliacor->k84_id             = $objAutent->caixa;
          $clconciliacor->k84_data           = substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2);
          $clconciliacor->k84_autent         = $objAutent->autent;
          $clconciliacor->k84_conciliaorigem = $conciliaorigem;
          $clconciliacor->incluir(null);
          if($clconciliacor->erro_status == 0){
            $sqlerro = true;
            $erromsg = "ConciliaCor - ".$clconciliacor->erro_msg;
            break;
          }
        }
      }
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == true) {
    echo $erromsg;
  }else{
    echo '1';
  }

}else if ($solicitacao == 'gerarpendencias') {
  
  $erromsg = "";

  db_inicio_transacao();
  if ($strJSONExtrato != '') {
     
    $arrayObjExtrato = $objJSON->decode($strJSONExtrato);

    foreach ($arrayObjExtrato as $i => $objExtrato){

      if ($objExtrato->classe == 'pendente') {
        continue;
      }

      if (is_object($objExtrato)) {
        
        $clconciliapendextrato->k88_concilia       = $concilia;
        $clconciliapendextrato->k88_extratolinha   = $objExtrato->extratolinha;
        $clconciliapendextrato->k88_conciliaorigem = 1; 
        $clconciliapendextrato->k88_justificativa  = corrigeCodificacaoCaracteres($objExtrato->justificativa);
        $clconciliapendextrato->incluir(null); //2
        if ($clconciliapendextrato->erro_status == 0) {
          
          $sqlerro = true;
          $erromsg = "conciliapendextrato - ".$clconciliapendextrato->erro_msg;
          break;
        }
      }
    }
  }

  if ($sqlerro == false) {
    
    if ($strJSONAutent != '') {
       
      $arrayObjAutent = $objJSON->decode($strJSONAutent);
      
      foreach ($arrayObjAutent as $i => $objAutent) {
  
        $objAutent->detalhe = db_stdClass::db_stripTagsJson($objAutent->detalhe);
        
        if ($objAutent->classe == 'pendente') {
          continue;
        }
        
        if ($objAutent->classe == 'preselecionado') {
          
          $sSqlCadastrado = "select * 
                               from conciliapendcorrente 
                              where k89_concilia = {$concilia} 
                                and k89_id = {$objAutent->caixa}
                                and k89_data = '".substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2)."'
                                and k89_autent = {$objAutent->autent}";
          $rsCadastrado   = db_query($sSqlCadastrado);
          if (pg_num_rows($rsCadastrado) > 0) {
            continue;  
          }
        }
        
        if (is_object($objAutent)) {
          
          $clconciliapendcorrente->k89_concilia       = $concilia;
          $clconciliapendcorrente->k89_id             = $objAutent->caixa;
          $clconciliapendcorrente->k89_data           = substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2);
          $clconciliapendcorrente->k89_autent         = $objAutent->autent;
          $clconciliapendcorrente->k89_justificativa  = corrigeCodificacaoCaracteres($objAutent->justificativa);
          $clconciliapendcorrente->k89_conciliaorigem = 1;
          $clconciliapendcorrente->incluir(null);
          if($clconciliapendcorrente->erro_status == "0") {
            
            $sqlerro = true;
            $erromsg = "conciliapendcorrente - ".$clconciliapendcorrente->erro_msg."\n$objAutent->classe Concilia: $concilia - Caixa: $objAutent->caixa - Autent: $objAutent->autent - Data: $clconciliapendcorrente->k89_data";
            break;
          }
        }
      }
    }
  }
  
  if($sqlerro == false){
    
    $clconcilia->k68_conciliastatus = 2;
    $clconcilia->k68_sequencial     = $concilia;
    $clconcilia->alterar($concilia);
    $erromsg = $clconcilia->erro_msg;
    if($clconcilia->erro_status == "0"){
      $sqlerro = true;
      $erromsg = "concilia - ".$clconcilia->erro_msg;
    }
    
  }
  
  db_fim_transacao($sqlerro);

  if ($sqlerro == true) {
    echo $erromsg;
  }else{
    echo '1';
  }
 
}else if ($solicitacao == 'desprocessaritem'){

  db_inicio_transacao();
//  $erromsg .= "inicio 111 ||||";
  $arrayItens = array();
  if ($strJSONExtrato != ''){ 
    $arrayObjExtrato = $objJSON->decode($strJSONExtrato);
    foreach ($arrayObjExtrato as $i => $objExtrato){
      if (is_object($objExtrato)){

        if(!in_array($objExtrato->itemconciliacao,$arrayItens)){
          array_push($arrayItens,$objExtrato->itemconciliacao);
        }
        // select verifica se item desprocessado nao era oriundo de uma pendencia conciliada (k87_conciliaorigem = 2) se sim insere novamente como pendencia
        $sqlRetornoPendenciasExtrato  = " select distinct ";
        $sqlRetornoPendenciasExtrato .= "        k68_sequencial  ";
        $sqlRetornoPendenciasExtrato .= "   from concilia  ";
        $sqlRetornoPendenciasExtrato .= "        inner join conciliaitem    on k83_concilia     = k68_sequencial  ";
        $sqlRetornoPendenciasExtrato .= "        inner join conciliaextrato on k87_conciliaitem = k83_sequencial ";
        $sqlRetornoPendenciasExtrato .= "  where k68_contabancaria = (select k68_contabancaria from concilia where k68_sequencial = $concilia )  ";
        $sqlRetornoPendenciasExtrato .= "    and k68_data   = (select k68_data   from concilia where k68_sequencial = $concilia ) ";
        $sqlRetornoPendenciasExtrato .= "    and k87_conciliaorigem = 2";
        $sqlRetornoPendenciasExtrato .= "    and k87_extratolinha   = ".$objExtrato->extratolinha ;
        $rsRetornoPendenciasExtrato = $clconciliaextrato->sql_record($sqlRetornoPendenciasExtrato);
        $intNumRows = $clconciliaextrato->numrows;
        for($x = 0 ; $x < $intNumRows; $x++){
          
          db_fieldsmemory($rsRetornoPendenciasExtrato,$x);
          $clconciliapendextrato->k88_concilia       = $k68_sequencial;
          $clconciliapendextrato->k88_extratolinha   = $objExtrato->extratolinha;
          $clconciliapendextrato->k88_conciliaorigem = 1;
          $clconciliapendextrato->incluir(null); // 3
          if($clconciliapendextrato->erro_status == 0){
            $sqlerro = true;
            $erromsg = "conciliapendextrato inclusao - ".$clconciliapendextrato->erro_msg;
            break;
          }
        }
                                         
        $clconciliaextrato->excluir(null,"k87_extratolinha = ".$objExtrato->extratolinha);
        if($clconciliaextrato->erro_status == 0){
          $sqlerro = true;
          $erromsg = "conciliaextrato exclusao - ".$clconciliaextrato->erro_msg;
          break;
        }
      }
    }
  }

  if ($strJSONAutent != ''){ 
    $arrayObjAutent = $objJSON->decode($strJSONAutent);
    foreach ($arrayObjAutent as $i => $objAutent){
      if (is_object($objAutent)){

        if(!in_array($objAutent->itemconciliacao,$arrayItens)){
          array_push($arrayItens,$objAutent->itemconciliacao);
        }
        // select verifica se item desprocessado nao era oriundo de uma pendencia conciliada (k84_conciliaorigem = 2) se sim insere novamente como pendencia
        $sqlRetornoPendenciasAutent  = " select distinct ";
        $sqlRetornoPendenciasAutent .= "        k68_sequencial  ";
        $sqlRetornoPendenciasAutent .= "   from concilia  ";
        $sqlRetornoPendenciasAutent .= "        inner join conciliaitem on k83_concilia     = k68_sequencial  ";
        $sqlRetornoPendenciasAutent .= "        inner join conciliacor  on k84_conciliaitem = k83_sequencial ";
        $sqlRetornoPendenciasAutent .= "  where k68_contabancaria = (select k68_contabancaria from concilia where k68_sequencial = $concilia )  ";
        $sqlRetornoPendenciasAutent .= "    and k68_data  >= (select k68_data   from concilia where k68_sequencial = $concilia ) ";
        $sqlRetornoPendenciasAutent .= "    and k84_conciliaorigem = 2";
        $sqlRetornoPendenciasAutent .= "    and k84_id     = ".$objAutent->caixa;
        $sqlRetornoPendenciasAutent .= "    and k84_data   = '".substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2)."'";
        $sqlRetornoPendenciasAutent .= "    and k84_autent = ".$objAutent->autent;
        $rsRetornoPendenciasAutent = $clconciliacor->sql_record($sqlRetornoPendenciasAutent);
        $intNumRows = $clconciliacor->numrows;
        for($x = 0 ; $x < $intNumRows; $x++){
          db_fieldsmemory($rsRetornoPendenciasAutent,$x);

          $dDataConcilia = substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2);           

          $clconciliapendcorrente->k89_concilia       = $k68_sequencial;
          $clconciliapendcorrente->k89_id             = $objAutent->caixa;
          $clconciliapendcorrente->k89_data           = $dDataConcilia;
          $clconciliapendcorrente->k89_autent         = $objAutent->autent;
          $clconciliapendcorrente->k89_conciliaorigem = 1;
          $clconciliapendcorrente->incluir(null);
          if($clconciliapendcorrente->erro_status == 0){

            $sqlerro = true;
            $erromsg = "conciliapendcorrente inclusao - ".$clconciliapendcorrente->erro_msg;
            break;
          }
        }
             
        $clconciliacor->excluir(null,"k84_id = ".$objAutent->caixa." 
                                  and k84_data = '".substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2)."' 
                                  and k84_autent = ".$objAutent->autent."
                                  and k84_conciliaitem = ".$objAutent->itemconciliacao);
        if($clconciliacor->erro_status == 0){
          $sqlerro = true;
          $erromsg = "conciliacor exclusao - ".$clconciliacor->erro_msg;
          break;
        }
      }
    }

    foreach ($arrayItens as $i => $item){
      // veirifica se restou algum registro conciliado ligado ao item, se nao existir deleta o item
      $sqlVerificaItem  = " select (select k84_conciliaitem from conciliacor     where k84_conciliaitem = $item limit 1) as k84_conciliaitem, ";
      $sqlVerificaItem .= "        (select k87_conciliaitem from conciliaextrato where k87_conciliaitem = $item limit 1) as k87_conciliaitem ";
      $sqlVerificaItem .= "   from conciliaitem ";
      $sqlVerificaItem .= "  where k83_sequencial = $item ";  
      $rsVerificaItem  = $clconciliaitem->sql_record($sqlVerificaItem);
      if($clconciliaitem->numrows  > 0){
        db_fieldsmemory($rsVerificaItem,$i);
        if ( ($k84_conciliaitem != '' && $k84_conciliaitem != null) && ($k87_conciliaitem != '' && $k87_conciliaitem != null) ){
          continue;
        }else{
          $clconciliaitem->excluir($item);
          if($clconciliaitem->erro_status == 0){
            $sqlerro = true;
            $erromsg = "conciliaitem exclusao - ".$clconciliaitem->erro_msg;
            break;
          }
        }
      }
    }
  }

//---------------------------------------------------------------------------------------------------------------------------------

  // select pegando todas as conciliacoes para incluir os itens desprocessados como pendente de todas as conciliacoes posteriores
  //$rsConcilia = $clconcilia->sql_record($clconcilia->sql_query_file(null," k68_sequencial ","k68_saltes = (select k68_saltes from concilia where k68_sequencial = $concilia limit 1) and k68_data > (select k68_data from concilia where k68_sequencial = $concilia limit 1)"));
  $rsConcilia = db_query("select k68_sequencial 
                            from concilia 
                           where k68_contabancaria = (select k68_contabancaria 
                                                        from concilia 
                                                       where k68_sequencial = $concilia) 
                             and k68_data >= (select k68_data from concilia where k68_sequencial = $concilia);");
  $intNumrows = pg_numrows($rsConcilia); //$clconcilia->numrows;

  for($x= 0; $x < $intNumrows; $x++){
    db_fieldsmemory($rsConcilia,$x);
    $arrayObjExtrato = null;
    $arrayObjAutent  = null;

    if ($strJSONExtrato != ''){ 
      $arrayObjExtrato = $objJSON->decode($strJSONExtrato);
      foreach ($arrayObjExtrato as $i => $objExtrato){
        
        if ($objExtrato->classe == 'pendente') {
          continue;
        }

        if (is_object($objExtrato)){

          /*
           *
           * Validamos a existencia de um cadastro de pendencia para a autenticacao na conciliacao
           * Caso seja encontrado registro o mesmo nao sera incluido
           */
          $sWhereValidaExistenciaConciliaPendExtrato = "k88_concilia = {$k68_sequencial} and k88_extratolinha = {$objExtrato->extratolinha}";
          $sSqlValidaExistenciaConciliaPendExtrato   = $clconciliapendextrato->sql_query_file(null, "1", null, $sWhereValidaExistenciaConciliaPendExtrato);
          $rsValidaExistenciaConciliaPendExtrato     = $clconciliapendextrato->sql_record($sSqlValidaExistenciaConciliaPendExtrato);
          if ($clconciliapendextrato->numrows > 0) {
            continue;
          }

          $clconciliapendextrato->k88_concilia       = $k68_sequencial;
          $clconciliapendextrato->k88_extratolinha   = $objExtrato->extratolinha;  
          $clconciliapendextrato->k88_conciliaorigem = 1;
          $clconciliapendextrato->incluir(null);                                  // 1
          if($clconciliapendextrato->erro_status == 0){                          
            $sqlerro = true;                                                     
            $erromsg = "conciliapendextrato inclusao - ".$clconciliapendextrato->erro_msg;
            break;
          }
        }
      }
    }


    if ($strJSONAutent != ''){ 
      $arrayObjAutent = $objJSON->decode($strJSONAutent);

      foreach ($arrayObjAutent as $i => $objAutent){

        if ($objAutent->classe == 'pendente') {
          continue;
        }
        if (is_object($objAutent)){

          $dDataConcilia = substr($objAutent->data,6,4)."-".substr($objAutent->data,3,2)."-".substr($objAutent->data,0,2);

          /*
           *
           * Validamos a existencia de um cadastro de pendencia para a autenticacao na conciliacao
           * Caso seja encontrado registro o mesmo nao sera incluido
           */
          $sWhereValidaExistenciaConciliaPendCorrente = "k89_concilia = {$k68_sequencial} and k89_id = {$objAutent->caixa} and k89_data = '{$dDataConcilia}' and k89_autent = {$objAutent->autent}";
          $sSqlValidaExistenciaConciliaPendCorrente   = $clconciliapendcorrente->sql_query_file(null, "1", null, $sWhereValidaExistenciaConciliaPendCorrente);
          $rsValidaExistenciaConciliaPendCorrente     = $clconciliapendcorrente->sql_record($sSqlValidaExistenciaConciliaPendCorrente);
          if ($clconciliapendcorrente->numrows > 0) {
            continue;
          }  

          $clconciliapendcorrente->k89_concilia       = $k68_sequencial;
          $clconciliapendcorrente->k89_id             = $objAutent->caixa;
          $clconciliapendcorrente->k89_data           = $dDataConcilia;
          $clconciliapendcorrente->k89_autent         = $objAutent->autent;
          $clconciliapendcorrente->k89_conciliaorigem = 1;
          $clconciliapendcorrente->incluir(null);
          if($clconciliapendcorrente->erro_status == 0) {
            $sqlerro = true;
            $erromsg = "conciliapendcorrente inclusao - ".$clconciliapendcorrente->erro_msg;
            break;
          }
        }
      }
    }
  }
  
//  $sqlerro = true;

  db_fim_transacao($sqlerro);

  if ($sqlerro == true) {
    echo $erromsg;
  }else{
    echo '1';
  }
}

function corrigeCodificacaoCaracteres($sString) {

  return db_stdclass::db_stripTagsJson(utf8_decode(rawurldecode($sString)));
}
?>