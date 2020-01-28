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
require_once ("libs/JSON.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");

$iAnoSessao = db_getsession("DB_anousu");
$oJson                 = new services_json();
$oParam                = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno              = new stdClass();
$oRetorno->status      = 1;
$oRetorno->message     = '';

switch ($oParam->exec) {
  
  case "emiteCSV":
    
    try {
      
      /* configuramos as variaveis para ficar mais facil o tratamento das mesmas */
      $oParam->lQuebraPagina == 't' ? $oParam->lQuebraPagina = true : $oParam->lQuebraPagina = false;
      $oParam->lParametro    == 't' ? $oParam->lParametro    = true : $oParam->lParametro    = false;
      $oParam->sOrdem = str_replace("coddepto"  , "depart_origem.coddepto", $oParam->sOrdem);
      $oParam->sOrdem = str_replace("t30_codigo", "div_origem.t30_codigo" , $oParam->sOrdem);
      
      $sCampos  = "bens.t52_bem                       as codigo_bem,";
      $sCampos .= "bensplaca.t41_placa                as placa_bem, ";
      $sCampos .= "bens.t52_descr                     as descricao_bem,";
      $sCampos .= "depart_origem.coddepto             as codigo_departamento_origem,";
      $sCampos .= "depart_origem.descrdepto           as departamento_origem,";
      $sCampos .= "div_origem.t30_descr               as divisao_origem,";
      $sCampos .= "inventariobem.t77_valordepreciavel as valor_depreciavel,";
      $sCampos .= "depart_destino.descrdepto          as departamento_destino,";
      $sCampos .= "div_destino.t30_descr              as divisao_destino,";
      $sCampos .= "situabens.t70_descr                as situacao,";
      if ($oParam->lParametro) {
      	$sCampos .= "orcorgao.o40_orgao   as codigo_orgao,";
      	$sCampos .= "orcorgao.o40_descr   as descricao_orgao,";
      	$sCampos .= "orcunidade.o41_descr as descricao_unidade,";
      }
      $sCampos .= "inventariobem.t77_vidautil as vida_util";

      
      $sCamposOrdem = null;
      if (!empty($oParam->sOrdem)) {
        $sCamposOrdem = "{$oParam->sOrdem} {$oParam->sTipoOrdem}";
      }

      $sWhereBens      = "inventariobem.t77_inventario = {$oParam->iCodigoInventario}";
      $oDaoBens        = db_utils::getDao("bens");
      $sSqlBuscaBens   = $oDaoBens->sql_query_dados_bem_inventario(null, $sCampos, $sCamposOrdem, $sWhereBens);
      $rsBuscaBens     = $oDaoBens->sql_record($sSqlBuscaBens);
      $iTotalRegistros = $oDaoBens->numrows;
      if ($iTotalRegistros == 0) {
        
        $sMsg = _M('patrimonial.patrimonio.pat3_relatoriomanutencaoinventario003.nenhum_registro_encontrado');
        db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
      }
      
      $sTituloArquivo  = "codigo_bem,placa_bem,descricao_bem,codigo_departamento_origem,departamento_origem,";
      $sTituloArquivo .= "divisao_origem,valor_depreciavel,departamento_destino,divisao_destino,situacao,";
      if ($oParam->lParametro == "t") {
        $sTituloArquivo .= "codigo_orgao,descricao_orgao,descricao_unidade,";
      }
      $sTituloArquivo  .= "vida_util";
      
      $aLinhasArquivo   = array();
      $aLinhasArquivo[] = $sTituloArquivo;
      for ($iRowBem = 0; $iRowBem < $iTotalRegistros; $iRowBem++) {
        
        $oStdBem = db_utils::fieldsMemory($rsBuscaBens, $iRowBem);
        
        $sStringArquivo  = "{$oStdBem->codigo_bem},";
        $sStringArquivo .= "{$oStdBem->placa_bem},";
        $sStringArquivo .= "{$oStdBem->descricao_bem},";
        $sStringArquivo .= "{$oStdBem->codigo_departamento_origem},";
        $sStringArquivo .= "{$oStdBem->departamento_origem},";
        $sStringArquivo .= "{$oStdBem->divisao_origem},";
        $sStringArquivo .= "{$oStdBem->valor_depreciavel},";
        $sStringArquivo .= "{$oStdBem->departamento_destino},";
        $sStringArquivo .= "{$oStdBem->divisao_destino},";
        $sStringArquivo .= "{$oStdBem->situacao},";
        if ($oParam->lParametro == "t") {
          $sStringArquivo .= "{$oStdBem->codigo_orgao},";
          $sStringArquivo .= "{$oStdBem->descricao_orgao},";
          $sStringArquivo .= "{$oStdBem->descricao_unidade},";
        }
        $sStringArquivo .= "{$oStdBem->vida_util}";
        
        $aLinhasArquivo[] = $sStringArquivo;
      }
      
      $sData             = date("Y_m_d");
      $sDiretorioArquivo = "/tmp/inventario_{$oParam->iCodigoInventario}_{$sData}.csv";
      $rsFileOpen        = fopen($sDiretorioArquivo, "w");
      if (!$rsFileOpen) {
        
        throw new Exception(_M('patrimonial.patrimonio.pat3_relatoriomanutencaoinventario003.nao_foi_possivel_criar_arquivo'));
      }
      $rsEscrita  = fwrite($rsFileOpen, implode("\n", $aLinhasArquivo));
      $rsClose    = fclose($rsFileOpen);
      
      $oRetorno->sCaminhoArquivo = $sDiretorioArquivo;
    
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = $eErro->getMessage();
    }
    
  break;
}
echo $oJson->encode($oRetorno);