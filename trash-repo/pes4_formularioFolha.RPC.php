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
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->sMensagem = "";

try {
  
  switch ($oParam->sExecucao) {
    
    case 'BuscaPrevidencia':
      
        $oDaoInss = db_utils::getDao('inssirf');
        
        $sCampos  = " distinct                                  ";
        $sCampos .= " case r33_codtab                           ";
        $sCampos .= "      when 2 then 0                        ";
        $sCampos .= "      when 1 then 5                        ";
        $sCampos .= "      else (cast(r33_codtab as integer)- 2)";
        $sCampos .= " end as r33_codtab,                        ";
        $sCampos .= " case r33_codtab                           ";
        $sCampos .= "      when 2 then 'Todos'                  ";
        $sCampos .= "      when 1 then 'Sem Prev.'              ";
        $sCampos .= "      else r33_nome                        ";
        $sCampos .= " end as r33_nome                           ";
        
        $sWhere   =  " r33_anousu       = ".db_anofolha();
        $sWhere  .=  "   and r33_mesusu = ".db_mesfolha();
        $sWhere  .=  "   and r33_instit = ".db_getsession('DB_instit');
        
        $sQueryInss  = $oDaoInss->sql_query_file('r33_codtab',null, $sCampos, null, $sWhere);
        
        $rsQueryInss = db_query($sQueryInss);
        
        if ( !$rsQueryInss ) {
          throw new DBException('Erro ao buscar os dados sobre as tabelas de previdencia');
        }
        
        $oRetorno->aPrevidencias = db_utils::getCollectionByRecord($rsQueryInss, false, false, true);
        
        break;
    case 'BuscaAnoMesFolha':
      require_once 'model/pessoal/std/DBPessoal.model.php';
      
      $oRetorno->iAno = DBPessoal::getAnoFolha();
      $oRetorno->iMes = str_pad(DBPessoal::getMesFolha(), 2, "0", STR_PAD_LEFT);
      
      break;
  }
} catch ( Exception $eErro ) {
  
  $oRetorno->iStatus   = "2";
  $oRetorno->sMensagem = $eErro->getMessage();
}
echo $oJson->encode($oRetorno);