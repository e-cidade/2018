<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once("libs/exceptions/BusinessException.php");
require_once("model/configuracao/DBEstrutura.model.php");
require_once("model/estoque/MaterialGrupo.model.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->message  = '';


switch ($oParam->exec) {
  
  case "processarImplantacaoTipoGrupo":
    
    db_inicio_transacao();
    try {

      $oDaoImplantacaoTipoGrupo = db_utils::getDao('matimplantacaotipogrupo');
      $sSqlBuscaImplantacao     = $oDaoImplantacaoTipoGrupo->sql_query_file();
      $rsBuscaImplantacao       = $oDaoImplantacaoTipoGrupo->sql_record($sSqlBuscaImplantacao);
      if ($oDaoImplantacaoTipoGrupo->numrows > 0) {
        throw new BusinessException("Implantação já executada. Procedimento abortado.");
      }
      $oDaoImplantacaoTipoGrupo->m93_sequencial      = null;
      $oDaoImplantacaoTipoGrupo->m93_db_usuarios     = db_getsession("DB_id_usuario");
      $oDaoImplantacaoTipoGrupo->m93_dataimplantacao = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoImplantacaoTipoGrupo->incluir(null);
      if ($oDaoImplantacaoTipoGrupo->erro_status == 0) {
      	throw new BusinessException("Impossível salvar os dados da implantação.");
      }
      
      $oDaoMaterialParametro    = db_utils::getDao("matparam");
      $sSqlBuscaParametro       = $oDaoMaterialParametro->sql_query_file();
      $rsBuscaParametro         = $oDaoMaterialParametro->sql_record($sSqlBuscaParametro);
      $oDadoMatParam            = db_utils::fieldsMemory($rsBuscaParametro, 0);
      if (empty($oDadoMatParam->m90_db_estrutura)) {
        throw new BusinessException("Sem parâmetro configurado para o grupo do material.");
      }
      
      $oEstruturaOld = new DBEstrutura($oDadoMatParam->m90_db_estrutura);
      $oEstruturaNew = new DBEstrutura();
      $oEstruturaNew->setSintetico('true');
      $oEstruturaNew->setMascara("000.{$oEstruturaOld->getMascara()}");
      $oEstruturaNew->setDescricao("Implantação Tipo Grupo");
      $oEstruturaNew->salvar();
      $oEstruturaNew->getNiveis();
      
      $oDaoEstruturaValor      = db_utils::getDao('db_estruturavalor');
      $sWhereMenorEstrutura    = "db121_db_estrutura = {$oDadoMatParam->m90_db_estrutura} and db121_estruturavalorpai = 0";
      $sWhereMenorEstrutura   .= "group by db_estruturavalor.db121_sequencial";
      $sSqlBuscaMenorEstrutura = $oDaoEstruturaValor->sql_query_file(null, "db121_sequencial, min(db121_estrutural)", null, $sWhereMenorEstrutura);
      $rsBuscaMenorEstrutura   = $oDaoEstruturaValor->sql_record($sSqlBuscaMenorEstrutura);
      $oDadoMenorEstrutura     = db_utils::fieldsMemory($rsBuscaMenorEstrutura, 0);
      
      $oDaoMaterialGrupoConta  = db_utils::getDao('materialestoquegrupoconta');
      $sSqlBuscaConta          = $oDaoMaterialGrupoConta->sql_query(null, "m66_codcon", null, "m65_db_estruturavalor = {$oDadoMenorEstrutura->db121_sequencial}");
      $rsBuscaConta            = $oDaoMaterialGrupoConta->sql_record($sSqlBuscaConta);
      $iCodigoConta            = db_utils::fieldsMemory($rsBuscaConta, 0)->m66_codcon;      
      
      $aNovasEstruturas   = array("001" => "Bens de Consumo", "002" => "Materiais de Consumo", "003" => "Bens Permanentes");
      $iCodigoBensConsumo = null;
      foreach ($aNovasEstruturas as $sIndice => $sDescricaoGrupo) {
        
        $oMaterialGrupo = new MaterialGrupo();
        $oMaterialGrupo->setDescricao($sDescricaoGrupo);
        $oMaterialGrupo->setEstrutura($oEstruturaNew);
        $oMaterialGrupo->setTipoConta(1);
        $oMaterialGrupo->setEstrutural("{$sIndice}.{$oEstruturaOld->getMascara()}");
        $oMaterialGrupo->setAtivo(true);
        $oMaterialGrupo->setConta($iCodigoConta);
        $oMaterialGrupo->salvar();
        if ($sIndice == "001") {
          $iCodigoBensConsumo = $oMaterialGrupo->getCodigoEstrutural();
        }
      }
      /*
       * Update em DB_ESTRUTURAVALOR para a nova mascara
       */
      $oDaoEstruturaValor      = db_utils::getDao('db_estruturavalor');
      $sSqlBuscaEstruturaValor = $oDaoEstruturaValor->sql_query_file(null, "*", null, "db121_db_estrutura = {$oDadoMatParam->m90_db_estrutura}");
      $rsBuscaEstruturaValor   = $oDaoEstruturaValor->sql_record($sSqlBuscaEstruturaValor);
       
      if ($oDaoEstruturaValor->numrows > 0) {
      	 
      	for ($iRowEstrutura = 0; $iRowEstrutura < $oDaoEstruturaValor->numrows; $iRowEstrutura++) {
      		 
      		$oDadoEstruturaValor = db_utils::fieldsMemory($rsBuscaEstruturaValor, $iRowEstrutura);
      		 
      		$oDaoUpdadeEstruturaValor = db_utils::getDao('db_estruturavalor');
      		$oDaoUpdadeEstruturaValor->db121_sequencial        = $oDadoEstruturaValor->db121_sequencial;
      		$oDaoUpdadeEstruturaValor->db121_db_estrutura      = $oEstruturaNew->getCodigo();
      		$oDaoUpdadeEstruturaValor->db121_estrutural        = "001.{$oDadoEstruturaValor->db121_estrutural}";
      		$oDaoUpdadeEstruturaValor->db121_descricao         = $oDadoEstruturaValor->db121_descricao;
      		$oDaoUpdadeEstruturaValor->db121_estruturavalorpai = $oDadoEstruturaValor->db121_estruturavalorpai;
      		if ($oDadoEstruturaValor->db121_estruturavalorpai == 0) {
      		  $oDaoUpdadeEstruturaValor->db121_estruturavalorpai = $iCodigoBensConsumo;
      		}
      		$oDaoUpdadeEstruturaValor->db121_nivel             = ($oDadoEstruturaValor->db121_nivel+1);
      		$oDaoUpdadeEstruturaValor->db121_tipoconta         = $oDadoEstruturaValor->db121_tipoconta;
      		$oDaoUpdadeEstruturaValor->alterar($oDadoEstruturaValor->db121_sequencial);
      		 
      		if ($oDaoUpdadeEstruturaValor->erro_status == 0) {
      			throw new BusinessException("Impossivel alterar os dados da estrutura.");
      		}
      	}
      }
      
      $oDaoMaterialParametro->m90_db_estrutura = $oEstruturaNew->getCodigo();
      $oDaoMaterialParametro->alterar(null);
      if ($oDaoMaterialParametro->erro_status == 0) {
        throw new BusinessException("Não foi possível alterar o parâmetro do material.");
      }
      
      $oRetorno->message = urlencode("Implantação executada com sucesso!");
      db_fim_transacao(false);

    } catch (BusinessException $eErro) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage())); 
      db_fim_transacao(true);
    }
    
  break;
  
}
echo $oJson->encode($oRetorno);
?>