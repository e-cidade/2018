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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

db_app::import("exceptions.*");
$oJson             = new services_json();
$oParametro        = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$lErro             = false;


switch ($oParametro->sExec) {
  
  case 'getParametrosFiscais' :
    
    $oDaoParfiscal = db_utils::getDao('parfiscal');
    
    $rsParfiscal   = $oDaoParfiscal->sql_record($oDaoParfiscal->sql_query_file(db_getsession('DB_instit'), 'y32_calcvistanosanteriores'));
    
    
    if (!$rsParfiscal || $oDaoParfiscal->numrows == 0) {
      
      throw new DBException('Nenhum par�metro fiscal configurado para a institui��o.');
      
    }
    
    $oParametroetrosFiscais = db_utils::fieldsMemory($rsParfiscal, 0);
    
    $oRetorno->aAnosCalculo = array();

    //n�o satisfaz condi��o
    if ($oParametroetrosFiscais->y32_calcvistanosanteriores == 't') {
      
      $oDaoCissqn = db_utils::getDao('cissqn');
      
      $sSqlCissqn = $oDaoCissqn->sql_query_file(null,
                                                "q04_anousu as anocalculo ",
                                                "q04_anousu desc          ",
                                                "q04_anousu <=            " . db_getsession('DB_anousu'));
      
      $rsCissqn   = $oDaoCissqn->sql_record($sSqlCissqn);
      
      if ($oDaoCissqn->numrows == 0) {
        
        throw new BusinessException('Nenhum par�metro do configurado para o exerc�cio '. db_getsession('DB_anousu') . '.');
        
      }
      
      $oRetorno->aAnosCalculo = db_utils::getCollectionByRecord($rsCissqn);
      
    }
    
    break;
    
  case 'getParametros' :
    
    $oDaoParIssqn = db_utils::getDao('parissqn');
    
    $rsParIssqn   = $oDaoParIssqn->sql_record($oDaoParIssqn->sql_query_file(null, "q60_parcelasalvara as parcelas"));
    
    $oRetorno->iNumeroParcelas = db_utils::fieldsMemory($rsParIssqn, 0)->parcelas;
    
    break;
    
  case 'getAtividades' :
    
    require_once('classes/db_tabativ_classe.php');
    
    $oDaoTabativ    = db_utils::getDao('tabativ');
    
    $dData          = date("Y-m-d", db_getsession("DB_datausu"));
    
    $sCampos        = "q07_seq,                                                                    ";
    $sCampos       .= "q03_descr,                                                                  ";
    $sCampos       .= "q07_datain,                                                                 ";
    $sCampos       .= "q07_datafi,                                                                 ";
    $sCampos       .= "q07_databx,                                                                 ";
    $sCampos       .= "q07_perman,                                                                 ";
    $sCampos       .= "q07_quant,                                                                  ";
    $sCampos       .= "q11_tipcalc,                                                                ";
    $sCampos       .= "q81_descr,                                                                  ";
    $sCampos       .= "case when q88_inscr is not null then true else false end as principal       ";
    
    $sWhereTabativ  = "    q07_inscr   = {$oParametro->iInscricao}                                 ";
    $sWhereTabativ .= "and q07_datain <= '{$dData}'                                                ";
    $sWhereTabativ .= "and (q07_datafi is null or q07_datafi >= '{$dData}')                        ";
    $sWhereTabativ .= "and (q07_databx is null or q07_databx >= '{$dData}')                        ";
    
    $sSqlTabativ    = $oDaoTabativ->sql_query_atividade_inscr($oParametro->iInscricao, 
                                                              $sCampos, 
                                                              "q07_seq", 
                                                              $sWhereTabativ); 
    
    $rsTabativ      = $oDaoTabativ->sql_record($sSqlTabativ);
    
    $oRetorno->aAtividades = array();
    
    if ($rsTabativ and $oDaoTabativ->numrows > 0) {
      
      $aAtividades = db_utils::getCollectionByRecord($rsTabativ);
      
      foreach ($aAtividades as $oAtividade) {
        
        $oDadosAtividade = new stdClass();
        
        $oDadosAtividade->iSequencial  = $oAtividade->q07_seq;
        $oDadosAtividade->sDescricao   = urlencode($oAtividade->q03_descr);
        $oDadosAtividade->dDataInicial = $oAtividade->q07_datain;
        $oDadosAtividade->lPermanente  = $oAtividade->q07_perman;
        $oDadosAtividade->iQuantidade  = $oAtividade->q07_quant;
        $oDadosAtividade->lPrincipal   = $oAtividade->principal; 
        
        $oRetorno->aAtividades[] = $oDadosAtividade;
         
      }
      
    }
    
    break;    
    
    case 'processarCalculo' :

      try {

        $oEmpresa = new Empresa($oParametro->iInscricao);

        /**
         * Empresa paralisada 
         */
        if( $oEmpresa->isParalisada() ) {

          $oErroMensagem = (object) array('iInscricao', $oParametro->iInscricao);
          throw new Exception(_M(Empresa::MENSAGENS . 'empresa_paralisada', $oErroMensagem));
        }

        db_inicio_transacao();
        
        if ( empty($oParametro->iAnoInicial) && empty($oParametro->iAnoFinal) ) {
          
          $oParametro->iAnoInicial = db_getsession('DB_anousu');
          $oParametro->iAnoFinal   = db_getsession('DB_anousu');
        }
        
        
        for($iAno = $oParametro->iAnoInicial; $iAno <= $oParametro->iAnoFinal; $iAno ++) {
        
          $iInstituicao   = db_getsession('DB_instit');
          $dDataCalculo   = date('Y-m-d', db_getsession("DB_datausu"));
        
          $sSqlAnoEmpresa = "select extract(year from q02_dtinic) as anoempresa from issbase where q02_inscr = {$oParametro->iInscricao}";
          $rsAnoEmpresa   = db_query($sSqlAnoEmpresa);
          $oAnoEmpresa    = db_utils::fieldsMemory($rsAnoEmpresa,0);
           
          
          if ((int)$oAnoEmpresa->anoempresa > (int)$iAno) {
            
            $oRetorno->status  = 2;
            $oRetorno->message = "Empresa mais nova que ano do Calculo: \n Ano Inicio Empresa: ".$oAnoEmpresa->anoempresa;
            $lProcessaCalculo = false;
            continue;
          }
          
          $lProcessaCalculo = true;
          $sSqlCalculo    = "SELECT fc_issqn({$oParametro->iInscricao}, 
                                             '{$dDataCalculo}', 
                                             {$iAno}, 
                                             null, 
                                             'true', 
                                             'false', 
                                             {$iInstituicao}, 
                                             '".implode(",",  $oParametro->aSelecionados)."', 
                                             {$oParametro->iTipoCalculo},
                                             {$oParametro->iParcelas}) AS resultado_calculo";
                                             
          $rsCalculo   = db_query($sSqlCalculo);
          
          if ( !$rsCalculo ) {
            throw new DBException("Erro ao Processar calculo: ".pg_last_error()." - ".$sSqlCalculo);
          }
          
          $sResultado  = db_utils::fieldsMemory($rsCalculo,0)->resultado_calculo;
          
          if (substr($sResultado, 0, 2) != "01") {
            throw new BusinessException( "Erro ao Processar C�lculo : \n\n{$sResultado}");
          }
        
        }
        
        if ( !$lProcessaCalculo ) {
          throw new BusinessException($oRetorno->message);
        }
        
        $oRetorno->message = "C�lculo Efetuado com Sucesso";
        db_query("COMMIT");
        
      } catch ( Exception $oException ) {
        
        $oRetorno->status  = 2;
        $oRetorno->message = $oException->getMessage();
        db_query("ROLLBACK");
      }

      break;
    
  
}
$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);