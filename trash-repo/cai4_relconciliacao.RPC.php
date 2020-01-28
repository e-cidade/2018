<?
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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_corrente_classe.php");
require_once ("classes/db_concilia_classe.php");
require_once ("libs/JSON.php");

db_postmemory($HTTP_POST_VARS);
$clcorrente        = new cl_corrente;
$clconcilia        = new cl_concilia;

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->erro    = false;
$oRetorno->status  = 1;
$oRetorno->message = "";

$sWhereData = "";

switch ($oParam->exec){
  
  case "buscaData": 

    $iConcilia = null;
    if (isset($oParam->concilia) && !empty($oParam->concilia)) {
      $iConcilia = $oParam->concilia;
    }
    
    if (isset($oParam->dia) && !empty($oParam->dia)) {
      $sSqlBuscaDatas  = $clconcilia->retornaTodasDatasComConciliacao($oParam->conta, $iConcilia);
    } else {
      $sSqlBuscaDatas  = $clconcilia->retornaUltimaDataComConciliacao($oParam->conta, $iConcilia);
    }
    $rsDatas         = $clcorrente->sql_record($sSqlBuscaDatas);
    $numrows         = $clcorrente->numrows;
    
    //Objeto com o retorno do result set
    $oDatas = db_utils::getColectionByRecord($rsDatas); 
    $aDatas = array();
    
    if ($numrows > 0) {

      foreach ($oDatas as $key => $value) {
        $aDatas[] = $value;
      }
    } else { 
      $oRetorno->erro    = true;
      $oRetorno->message = "Nenhuma Data retornada para a conta selecionada";
      
    }
        
    // Se o retorno for falso, seta a propriedade aDados do Objéto $oRetorno
    if (!$oRetorno->erro) {
      $oRetorno->dados = $aDatas;
      
    }
    
    break ;
    
  case "buscaMesAno":
    
    $oDaoConcilia   = db_utils::getDao('concilia');
    $sSqlBuscaDatas = $oDaoConcilia->retornaMesAnoComComciliacao($oParam->conta);
    $rsBuscaDatas   = $oDaoConcilia->sql_record($sSqlBuscaDatas);
    $iLinhas        = $oDaoConcilia->numrows;

    if ($iLinhas > 0) {
      
      $oRetorno->dados = array();
      
      for ($i = 0; $i < $iLinhas; $i++) {
        
        $oDadoAnoMes     = db_utils::fieldsMemory($rsBuscaDatas, $i);
        $oAnoMes         = new stdClass();
        $oAnoMes->ano    = $oDadoAnoMes->ano;
        $oAnoMes->aMeses = array();
        
        $aMeses = explode(",", $oDadoAnoMes->mes);
        foreach ($aMeses as $iMes) {
          
          $oMes              = new stdClass();
          $oMes->mes         = $iMes;
          $oAnoMes->aMeses[] = $oMes;
        }
        
        $oRetorno->dados[] = $oAnoMes;
      }
    }
    
    break;
    
  case 'buscaContas': 
    /**
     * @todo refatorar
     */
    $sWhereContas  = " where exists(select 1  ";
    $sWhereContas .= "                from concilia ";
    $sWhereContas .= "               inner join conciliastatus on conciliastatus.k95_sequencial = concilia.k68_conciliastatus ";
    $sWhereContas .= "               where k68_contabancaria = x.db83_sequencial ";
    $sWhereContas .= "                 and k68_data = x.k68_data ";
    $sWhereContas .= "                 and k95_fechada is true ) ";
    
    $sAndConcilia  = "";
    
    if (isset($oParam->concilia) && !empty($oParam->concilia)) {
    
      $sWhereContas = "";
      $sAndConcilia = " and k68_sequencial = {$oParam->concilia} ";
    }
    
    if (isset($oParam->dia) && !empty($oParam->dia)) {
      $sWhereContas = "";
    }
    
    $sqlConta    = " select db83_sequencial,                                                                                 ";
    $sqlConta   .= "        db83_descricao,                                                                                  ";
    $sqlConta   .= "        k68_data                                                                                         ";
    $sqlConta   .= "   from (                                                                                                ";
    $sqlConta   .= " select db83_sequencial,                                                                                 ";
    $sqlConta   .= "        db83_descricao,                                                                                  ";
    $sqlConta   .= "        min(k83_conciliatipo) as conciliatipo,                                                           ";
    $sqlConta   .= "        min(k68_data)         as k68_data                                                                ";
    $sqlConta   .= "   from contabancaria                                                                                    ";
    $sqlConta   .= "        inner join concilia       on concilia.k68_contabancaria    = db83_sequencial                     ";
    $sqlConta   .= $sAndConcilia;
    $sqlConta   .= "        inner join conciliastatus on conciliastatus.k95_sequencial = concilia.k68_conciliastatus         ";
    $sqlConta   .= "        left  join conciliaitem   on k83_concilia                  = concilia.k68_sequencial             ";
    $sqlConta   .= "  group by db83_sequencial,                                                                              ";
    $sqlConta   .= "           db83_descricao                                                                                ";
    $sqlConta   .= " ) as x                                                                                                  ";
    $sqlConta   .= $sWhereContas;

    $oDaoSaltes  = db_utils::getDao('saltes');
    $rsContas    = $oDaoSaltes->sql_record($sqlConta);
    $numrows     = $oDaoSaltes->numrows;
    
    $oRetorno->dados = array();
    
    for ($i = 0; $i < $numrows; $i++) {
      
      $oDadosConta        = db_utils::fieldsMemory($rsContas,$i);
      $oConta             = new stdClass();
      $oConta->sequencial = $oDadosConta->db83_sequencial;
      $oConta->descricao  = urlencode($oDadosConta->db83_descricao);
      $oRetorno->dados[]  = $oConta;
      unset($oConta);
    }
    
    break;
}
echo $oJson->encode($oRetorno);


?>