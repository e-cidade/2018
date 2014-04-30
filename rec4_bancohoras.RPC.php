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


require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMensagem     = '';

define("MENSAGENS", "recursoshumanos.rh.rec4_bancohoras.");

$oDaoBancoHoras = db_utils::getDao("bancohoras");

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "setBancoHoras":

      $sWhere                   = '';
      $iSaldoRestante           = '';
      $iSaldoPassivelLancamento = 0;
      list($iDia, $iMes, $iAno) = split('/', $oParametros->sData);
      $sData                    = $iAno . '-' . $iMes . '-' . $iDia;

      $lSoma    = 'true';
      if( $oParametros->iTipo != 1 ){
        $lSoma  = 'false';
      }

      $oDaoBancoHoras->rh126_regist       = $oParametros->iServidor;
      $oDaoBancoHoras->rh126_soma         = $lSoma;
      $oDaoBancoHoras->rh126_data         = $oParametros->sData;
      $oDaoBancoHoras->rh126_horas        = $oParametros->iHoras;
      $oDaoBancoHoras->rh126_minutos      = $oParametros->iMinutos;
      $oDaoBancoHoras->rh126_observacao   = db_stdClass::normalizeStringJsonEscapeString( $oParametros->sObservacao );

      if ( !empty( $oParametros->iSequencial ) ) {

        $oDaoBancoHoras->rh126_sequencial = $oParametros->iSequencial;
        $oDaoBancoHoras->alterar( $oDaoBancoHoras->rh126_sequencial );

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_alterar_bancohoras' ) );
      } else {

        $oDaoBancoHoras->incluir(null);
        $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_cadastrar_bancohoras' ) );
      }

      if ($oDaoBancoHoras->erro_status == "0") {
        throw new BusinessException( _M( MENSAGENS . 'erro_cadastrar_bancohoras' ) );
      }

    break;

    case "deleteBancoHoras":

      if( empty( $oParametros->iSequencial ) ){
        throw new BusinessException( _M( MENSAGENS . 'banco_horas_obrigatorio' ) );
      }

      $oDaoBancoHoras->excluir($oParametros->iSequencial);
      if ( $oDaoBancoHoras->erro_status == 0 ) {
        throw new BusinessException( _M( MENSAGENS . 'erro_excluir_bancohoras' ) );
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_excluir_bancohoras' ) );
    break;

    /**
     * Retorna o Historico do servidor informado
     */
    case "getHistorico":

      if( empty( $oParametros->iServidor ) ){
        throw new BusinessException( _M( MENSAGENS . 'preenchimento_servidor_obrigatorio' ) );
      }

      $sCampos  = " rh126_sequencial,                               ";
      $sCampos .= " rh126_data,                                     ";
      $sCampos .= " rh126_horas||':'||rh126_minutos as rh126_horas, ";
      $sCampos .= " CASE rh126_soma                                 ";
      $sCampos .= "   WHEN true THEN 'Soma'                         ";
      $sCampos .= "   ELSE 'Diminui'                                ";
      $sCampos .= " END as rh126_soma,                              ";
      $sCampos .= " rh126_observacao                                ";

      $sSqlHistorico  = $oDaoBancoHoras->sql_query_file( null, $sCampos, 'rh126_data desc', "rh126_regist = {$oParametros->iServidor}" );
      $rsHistorico    = $oDaoBancoHoras->sql_record( $sSqlHistorico );

      $oHistorico     = db_utils::getCollectionByRecord($rsHistorico, true, false, true);

      $oRetorno->oHistorico = $oHistorico;
    break;

    /**
     * GetSaldo Servidor
     * @todo mover para model
     */
    case "getSaldoHoras":

      function segundosParaHoras( $iSegundos ){

        $iSegundos       = ( int ) $iSegundos;

        $lValidaNegativo = $iSegundos < 0;
        $iSegundos       = abs( $iSegundos );

        $iHoras          = floor( $iSegundos / 3600 );
        $iSegundos      -= $iHoras * 3600;
        $iMinutos        = floor( $iSegundos / 60 );
        $iSegundos      -= $iMinutos * 60;

        if($iHoras <= 9){
          $iHoras = '0'.$iHoras;
        }

        return ( $lValidaNegativo ? '-' : '' ) . $iHoras . ':' . str_pad( $iMinutos, 2, '0', STR_PAD_LEFT );
      }

      $sSqlSaldo   = $oDaoBancoHoras->sql_query_file( null, 'rh126_soma, rh126_horas||\':\'||rh126_minutos||\':\'||\'00\' as horas', 'rh126_soma asc', "rh126_regist = {$oParametros->iServidor}" );
      $rsSaldo     = $oDaoBancoHoras->sql_record( $sSqlSaldo );

      $rsSaldo     = db_query($sSqlSaldo);
      $aSaldoHoras = db_utils::getColectionByRecord($rsSaldo, true);

      $iSegundos = 0;

      foreach ( $aSaldoHoras as $aSaldo ){

         $lSoma = false;
         if( $aSaldo->rh126_soma == 't' ){
          $lSoma = true;
         }

         list( $g, $i, $s ) = explode( ':', $aSaldo->horas );

         if($lSoma){

           $iSegundos += $g * 3600;
           $iSegundos += $i * 60;
           $iSegundos += $s;
         }else{

           $iSegundos -= $g * 3600;
           $iSegundos -= $i * 60;
           $iSegundos -= $s;
         }
      }

      $oRetorno->saldoHoras = segundosParaHoras($iSegundos);

    break;

    /**
     * Retorna um registro especifico do Banco de Horas.
     */
    case "getBancoHoras":

      if( empty( $oParametros->iSequencial ) ){
        throw new BusinessException( _M( MENSAGENS . 'banco_horas_obrigatorio' ) );
      }

      $sCampos  = " rh126_sequencial,                               ";
      $sCampos .= " rh126_data,                                     ";
      $sCampos .= " rh126_horas||':'||rh126_minutos as rh126_horas, ";
      $sCampos .= " rh126_soma,                                     ";
      $sCampos .= " rh126_observacao                                ";

      $sSqlBancoHoras = $oDaoBancoHoras->sql_query_file($oParametros->iSequencial, $sCampos);
      $rsBancoHoras   = $oDaoBancoHoras->sql_record($sSqlBancoHoras);
      $oBancoHoras    = db_utils::getCollectionByRecord($rsBancoHoras, true, false, true);

      $oRetorno->oBancoHoras = $oBancoHoras;
    break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);