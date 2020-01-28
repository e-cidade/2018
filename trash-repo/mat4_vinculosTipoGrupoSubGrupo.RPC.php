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
require_once("model/configuracao/DBEstruturaValor.model.php");
require_once("model/configuracao/DBEstrutura.model.php");
require_once("classes/db_matparam_classe.php");
require_once("classes/db_db_estruturavalor_classe.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->message  = '';

switch ($oParam->exec) {
  
  case "processarVinculoGrupoSubgrupo" :

    function  atualizarEstruturalNodo($iCodigo, $sEstruturalFinal, $iNivelAtual, $iNivelDestino, $iMaxNivel, $aMascara){
    
      for ($i = $iNivelDestino+1 ; $i <= $iMaxNivel; $i++) {
       
       $indice = $i-1;
       $sEstruturalFinal = "{$sEstruturalFinal}.$aMascara[$indice]";
        
      }
      
      $oEstruturaValorOrigem = new DBEstruturaValor($iCodigo);
      $oEstruturaValorOrigem->setNivel($iNivelDestino);
      
      $sqlUpdadeDbEstruturaValor  = "update db_estruturavalor set  db121_estrutural = '{$sEstruturalFinal}', db121_nivel = {$iNivelDestino} ";
      $sqlUpdadeDbEstruturaValor .= " where db121_sequencial = {$iCodigo}";

      db_query(" begin; ".$sqlUpdadeDbEstruturaValor."; commit; ");
      
    }
    
    function atualizarEstruturaisArvoreDestino($sEstruturalFinal, $iCodigo, $iNivelDestino,$iNivelOrigem, $iMaxNivel,$aMascara) {

      // Atualiza Estrutural do n� atual
      atualizarEstruturalNodo($iCodigo, $sEstruturalFinal, $iNivelOrigem,$iNivelDestino, $iMaxNivel, $aMascara);

      
      if ($iNivelOrigem > $iMaxNivel) {
        return true;
      }
      //Busca por seus filhos na tabela db_estruturavalor
      $oEstrutura   = new DBEstruturaValor($iCodigo);
      $iNivelOrigem = $oEstrutura->getNivel() + 1;
      
      $aFilhos      = $oEstrutura->getFilhosNivel($iNivelOrigem);
      $count = 0;
      $iNivelDestino++;
      
      $iTotal = count($aMascara)-1;
      
      if ($aFilhos == false && $iNivelOrigem <= $iTotal) {
        
        $iTotalCasas       = strlen($aMascara[$iNivelDestino-1]);
        $sEstruturalFinal .= ".".str_pad(0, $iTotalCasas, "0", STR_PAD_LEFT);
        $iNivelDestino ++;
        $iNivelOrigem  ++;
        $aFilhos           = $oEstrutura->getFilhosNivel($iNivelOrigem);
        $count++;
      }
      
      /**
       * Caso existam filhos do n� atual, eles devem ser atualizados tamb�m
       */
      if ($aFilhos) {
        
        /**
         * Para cada filho do n� atual
         *  1 - realizar update na base de dados para atualizar o estrutural
         *  2 - realizar update tamb�m nos seus pr�prios filhos
         */
        $iContadorFilhos = 1;
        $iTotalCasas     = strlen($aMascara[$iNivelDestino-1]);
        
        
        foreach ($aFilhos as $oFilho) {
          
          $iNivelOrigem2 = $iNivelOrigem; 
          $sEstruturalIntermediario = $sEstruturalFinal;
          $sEstruturalTemp          = $sEstruturalIntermediario.".".str_pad($iContadorFilhos, $iTotalCasas, "0", STR_PAD_LEFT);
          atualizarEstruturaisArvoreDestino($sEstruturalTemp, $oFilho->db121_sequencial,$iNivelDestino,$iNivelOrigem2, $iMaxNivel, $aMascara);
          $iContadorFilhos++;
        }
      }
    }
    
    try {
      
      //Seta c�digo destino e origem
      $iCodigoDestino  = $oParam->aDestino[0];
      $iCodigoOrigem   = $oParam->aOrigem[0];
      
      /**
       * Atualiza o Pai do Origem
       */
      $oEstruturaValorOrigem  = new DBEstruturaValor($iCodigoOrigem);
      $iNivelOrigem           = $oEstruturaValorOrigem->getNivel();

      $oEstruturaValorDestino = new DBEstruturaValor($iCodigoDestino);
      $iNivelDestino          = $oEstruturaValorDestino->getNivel() +1;
      
      /*
       * @todo verificar tamanho arvore
       * validamos os niveis que ser�o possivel serem movidos
       */
      
      if ($iNivelDestino != $iNivelOrigem  ) {
        throw new BusinessException("Nivel de destino n�o pode ser maior que nivel de origem.");
      }
      
      /**
       * Salvar os dados
       * Update na db_estruturaValor
       */
      $sqlUpdadeDbEstruturaValorPai  = "update db_estruturavalor set  db121_estruturavalorpai =  {$iCodigoDestino} ";
      $sqlUpdadeDbEstruturaValorPai .= " where db121_sequencial = {$iCodigoOrigem} "; 
      
      /**
       * Verifica para qual n�vel do Destino o estrutural Origem ir�
       */
      $sEstruturalDestino 	  = $oEstruturaValorDestino->getEstrutural();
      $iSequencia				 		  = 1;
      
      //Caso exista, busca o �ltimo filho, do n�vel Destino em que ser� colocado
      $oDaoEstruturaValor = new cl_db_estruturavalor;
      $sWhere             = "db121_estruturavalorpai = {$iCodigoDestino}";
      $sSqlEstruturaValor = $oDaoEstruturaValor->sql_query(null,"*","db121_estrutural desc limit 1",$sWhere);
      
      $rsEstruturaValor   = $oDaoEstruturaValor->sql_record($sSqlEstruturaValor);
      
      if ($oDaoEstruturaValor->numrows >0) {
        
        $oEstruturalValorFilho = db_utils::fieldsMemory($rsEstruturaValor, 0);
        //$iNivelDestino         = $oEstruturalValorFilho->db121_nivel;
        //$iCodigoDestino        = $oEstruturalValorFilho->db121_sequencial;
        $sEstruturalDestino    = $oEstruturalValorFilho->db121_estrutural;
        $aEstrutural           = explode(".",$oEstruturalValorFilho->db121_estrutural);
        $iSequencia            = $aEstrutural[$iNivelDestino-1] +1;
      }
      
      $aEstruturalDestino = explode(".", $sEstruturalDestino);
      $sEstruturalFinal   = "";
      
      /**
       * Copia o estrutural at� parte correspondente( at� o n�vel do pai destino)  
       */
      
      $sPonto = ""; 
      for ($i = 0; $i < $iNivelDestino-1; $i++) {
        
        $sEstruturalFinal .= $sPonto.$aEstruturalDestino[$i];
        $sPonto            = ".";
      }
     
      
      $oDaoMatParam           = new cl_matparam;
      $sSqlParametroEstrutura = $oDaoMatParam->sql_query(null, "m90_db_estrutura, db77_estrut", null, null);
      $rsParametroEstrutura   = $oDaoMatParam->sql_record($sSqlParametroEstrutura);
      $sMascara               = db_utils::fieldsMemory($rsParametroEstrutura, 0)->db77_estrut;
      
      $iNivelAtual  = $iNivelDestino;
      $aMascara     = explode(".", $sMascara);
      $iMaxNivel    = count($aMascara);
      
      $iTotalCasa         = strlen($aMascara[$iNivelDestino-1]);
      $sProximaEstrutural = str_pad($iSequencia, $iTotalCasa, "0", STR_PAD_LEFT);
      $sEstruturalFinal   = "{$sEstruturalFinal}.{$sProximaEstrutural}";
      
      atualizarEstruturaisArvoreDestino(&$sEstruturalFinal, &$iCodigoOrigem, &$iNivelDestino,&$iNivelOrigem, &$iMaxNivel,&$aMascara);
      
      db_query(" begin; ".$sqlUpdadeDbEstruturaValorPai."; commit ;");
      
      db_fim_transacao(false);
    } catch (BusinessException $eErro) {
      
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);      
    }
    
  break;  
  
  
  case "processarVinculoTipoGrupo":
    
    db_inicio_transacao();
    try {

      /*
       * Excluimos o v�nculo existente e inclu�mos um novo
       */
      $oDaoTipoGrupoVinculo = db_utils::getDao('materialtipogrupovinculo');
      $rsSqlExcluirVinculo  = $oDaoTipoGrupoVinculo->excluir(null, "m04_materialtipogrupo = {$oParam->m03_sequencial}");
      if ($oDaoTipoGrupoVinculo->erro_status == 0) {
        throw new BusinessException("Imposs�vel remover o v�nculo.\n\n{$oDaoTipoGrupoVinculo->erro_msg}");
      }
      
      /*
       * Inclu�mos um novo v�nculo
       */
      $oDaoTipoGrupoVinculo->m04_sequencial        = null;
      $oDaoTipoGrupoVinculo->m04_materialtipogrupo = $oParam->m03_sequencial;
      $oDaoTipoGrupoVinculo->m04_db_estruturavalor = $oParam->db121_sequencial;
      $oDaoTipoGrupoVinculo->incluir(null);
      if ($oDaoTipoGrupoVinculo->erro_status == 0) {
        throw new BusinessException("Imposs�vel v�ncular o grupo ao grupo.\n\n{$oDaoTipoGrupoVinculo->erro_msg}");
      }
      $oRetorno->message = urlencode("V�nculo salvo com sucesso!");
      db_fim_transacao(false);
      
    } catch (BusinessException $eErro) {
      
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }
  break;
}
echo $oJson->encode($oRetorno);
?>