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
require_once ("libs/db_libpessoal.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("std/db_stdClass.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("fpdf151/pdf.php");

$oJson     = new services_json();
$oParam    = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno  = new db_stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

$lErro   = false;

try {

  switch ($oParam->sExec) {

    case 'listaRelatorios':
       
      $oConsolidacaoDebitosRegistros      = db_utils::getDao('consolidacaodebitosregistros');
       
      $sWhereConsolidacaoDebitosRegistros = "k162_consolidacaodebitos = {$oParam->iCodigoRelatorio} ";
       
      $sSqlConsolidacaoDebitosRegistros   = $oConsolidacaoDebitosRegistros->sql_query(null, 
                                                                                      'distinct k162_tiporelatorio', 
                                                                                      'k162_tiporelatorio asc', 
                                                                                      $sWhereConsolidacaoDebitosRegistros);

      $rsConsolidacaoDebitosRegistros     = $oConsolidacaoDebitosRegistros->sql_record($sSqlConsolidacaoDebitosRegistros);
       
      $aRelatorios = array();
      
      for ($iIndice = 0; $iIndice < $oConsolidacaoDebitosRegistros->numrows; $iIndice++) {
         
        $oRegistros    = db_utils::fieldsMemory($rsConsolidacaoDebitosRegistros, $iIndice, true, false, true);
        $aRelatorios[] = $oRegistros->k162_tiporelatorio;
         
      }
       
      $oRetorno->aRelatorios = $aRelatorios;
       
      break;
       
    case 'processaRelatorios':

      db_app::import('caixa.relatorios.RelatorioConsolidadoReceitas');
      
      if (empty($oParam->aSelecionados)) {
        throw new Exception ('Selecione o(s) relatório(s) para impressão.');
      }

      $oRelatorio    = new RelatorioConsolidadoReceitas();
      
      $lQuebraPagina = $oParam->lQuebraPagina == 1 ? true : false;
      
      $oRelatorio->setQuebraPagina($lQuebraPagina);
      
      foreach ($oParam->aSelecionados as $iSelecionado) {
        $oRelatorio->adicionarModelo($iSelecionado);
      }
      
      if ( $oRelatorio->processar() ) {
        $oRetorno->sNomeArquivo = urlencode($oRelatorio->getNomeArquivo());
      }

      break;

  }

} catch (Exception $eErro) {

  $oRetorno->iStatus    = 2;

  $oRetorno->sMensagem  = urlencode($eErro->getMessage());

  db_fim_transacao(true);

}

echo $oJson->encode($oRetorno);