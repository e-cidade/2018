<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
const MENSAGEM          = 'tributario.projetos.pro4_obrasalvara.';

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case 'renovaAlvara':

      if (empty($oParam->iCodigoObra)){
        throw new ParameterException(_M(MENSAGEM . 'codigo_obra_nao_informado'));
      }

      if (empty($oParam->sDataInicial)) {
        throw new ParameterException(_M(MENSAGEM . 'data_inicial_nao_informado'));
      }

      if (empty($oParam->sDataFinal)) {
        throw new ParameterException(_M(MENSAGEM . 'data_final_nao_informado'));
      }

      if($oParam->sDataFinal < $oParam->sDataInicial) {
        throw new BusinessException(_M(MENSAGEM . 'data_final_menor_data_inicial'));
      }

      /**
       * Verifica se a data inicial informada não é menor que a data inical da 
       * ultima renovação
       */
      $oDaoObrasAlvara = db_utils::getDao('obrasalvara');
      $sWhere          = "ob04_codobra = {$oParam->iCodigoObra}";
      $sSqlObrasAlvara = $oDaoObrasAlvara->sql_query_file(null, "*", null, $sWhere);
      $rsObrasAlvara   = pg_query($sSqlObrasAlvara);

      if(pg_num_rows($rsObrasAlvara) == 0){
        throw new BusinessException(_M(MENSAGEM . 'obra_sem_alvara'));
      }

      $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0);
      if ($oObrasAlvara->ob04_data >= $oParam->sDataInicial) {
        throw new BusinessException(_M(MENSAGEM . 'data_inicial_menor_ultima_renovacao'));
      }
      
      db_inicio_transacao();

      /**
       * Adiciona os dados da tabela obrasalvara na tabela obrasalvarahistorico.
       */
      $oDaoObrasAlvaraHistorico = db_utils::getDao('obrasalvarahistorico');
      $oDaoObrasAlvaraHistorico->ob35_sequencial  = null;
      $oDaoObrasAlvaraHistorico->ob35_codobra     = $oObrasAlvara->ob04_codobra;
      $oDaoObrasAlvaraHistorico->ob35_datainicial = $oObrasAlvara->ob04_data;
      $oDaoObrasAlvaraHistorico->ob35_datafinal   = $oObrasAlvara->ob04_dtvalidade;
      $oDaoObrasAlvaraHistorico->incluir(null);

      if($oDaoObrasAlvaraHistorico->erro_status == '0') {
        throw new DBException(_M(MENSAGEM . 'erro_obrasalvaraistorico'));
      }

      /**
       * Atualiza os dados na tabela obrasalvara.
       */
      $oDaoObrasAlvara->ob04_codobra    = $oParam->iCodigoObra;
      $oDaoObrasAlvara->ob04_data       = $oParam->sDataInicial;
      $oDaoObrasAlvara->ob04_dtvalidade = $oParam->sDataFinal;
      $oDaoObrasAlvara->alterar($oParam->iCodigoObra);

      if ($oDaoObrasAlvara->erro_status == '0') {
        throw new DBException(_M(MENSAGEM . 'erro_obrasalvara'));
      }

      db_fim_transacao(false);
      $oRetorno->sMessage = urlencode(_M(MENSAGEM . 'alvara_renovado'));
    break;
    case 'getAlvara':

      if (empty($oParam->iCodigoObra)){
        throw new ParameterException(_M(MENSAGEM . 'codigo_obra_nao_informado'));
      }

      $oDaoObrasAlvara = db_utils::getDao('obrasalvara');
      $sWhere          = "ob04_codobra = {$oParam->iCodigoObra}";
      $sSqlObrasAlvara = $oDaoObrasAlvara->sql_query_file(null, "*", null, $sWhere);
      $rsObrasAlvara   = pg_query($sSqlObrasAlvara);

      if(pg_num_rows($rsObrasAlvara) == 0){
        throw new BusinessException(_M(MENSAGEM . 'obra_sem_alvara'));
      }

      $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0);
      
      $oRetorno->oAlvara = $oObrasAlvara;

    break;

    case 'getHistorico':

      if (empty($oParam->iCodigoObra)){
        throw new ParameterException(_M(MENSAGEM . 'codigo_obra_nao_informado'));
      }

      $oDaoObrasAlvaraHistorico = db_utils::getDao("obrasalvarahistorico");

      $sWhere = "ob35_codobra = {$oParam->iCodigoObra}";
      $sSqlHistorico = $oDaoObrasAlvaraHistorico->sql_query(null, "ob35_datainicial, ob35_datafinal", "ob35_datainicial", $sWhere);

      $rsHistorico = db_query($sSqlHistorico);

      if (!$rsHistorico) {
        throw new DBException(_M(MENSAGEM . "erro_busca_historico"));
      }

      $oRetorno->aHistoricos = db_utils::getCollectionByRecord($rsHistorico);
      
    break;
  }
  
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
?>