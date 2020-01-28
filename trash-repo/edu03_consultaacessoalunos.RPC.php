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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("classes/db_controleacessoaluno_classe.php");
require_once("classes/db_controleacessoalunoregistro_classe.php");
require_once("model/webservices/ControleAcessoAluno.model.php");
require_once("std/DBSoapClient.php");
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
switch ($oParam->exec) {
  
  case 'atualizarDados':
    
    try {
      
      $sLockFile = "tmp/consulta_acesso_aluno.lock";
      if (file_exists($sLockFile)) {
        while(file_exists($sLockFile)) {
          if (!file_exists($sLockFile)) {
            break;
          }
        }
      }
     $rsFileLock = fopen($sLockFile, 'w');
     fputs($rsFileLock, db_getsession("DB_id_usuario"));
      
      
      db_inicio_transacao();
      $oRetorno->message = 'Leituras processas com sucesso.';
      $oDaoControleAcessoAluno         = db_utils::getDao('controleacessoaluno');
      $oDaoControleAcessoAlunoRegistro = db_utils::getDao('controleacessoalunoregistro');
      
      $sDataInicial = date('Y-m-d',db_getsession('DB_datausu')).' '.db_hora(0, 'H:i:s');
      if (ControleAcessoAluno::wsdlMode()) {
        $oClient  = new DBSoapClient(ControleAcessoAluno::getUrlWebservice());
      } else {
        
        $aOptions = array('location' => ControleAcessoAluno::getUrlWebservice(),
                          'uri'      => ControleAcessoAluno::getURI());
        $oClient  = new DBSoapClient(null, $aOptions);
      }
      $sCampos  = "to_timestamp(max(ed101_dataleitura|| ' ' || cast(ed101_horaleitura as time) + '1 second'::interval),";
      $sCampos .= " 'YYYY-MM-DD HH24:MI:SS') as ultima_leitura";      
      $sSqlUltimaLeitura = $oDaoControleAcessoAlunoRegistro->sql_query_file(null,$sCampos);
      $rsUltimaLeitura   = $oDaoControleAcessoAlunoRegistro->sql_record($sSqlUltimaLeitura);
      if ($oDaoControleAcessoAlunoRegistro->numrows > 0 ) {
        
        $sDataInicial       = db_utils::fieldsMemory($rsUltimaLeitura,0)->ultima_leitura;
        $sDataInicial = substr($sDataInicial, 0, 19);
      }
      if ($sDataInicial == "") {
        $sDataInicial = date('Y-m-d',db_getsession('DB_datausu')).' '.db_hora(0, 'H:i:s');
      }
      $oRetornoDadosLeitura = $oClient->getDadosLeituras(array("dataHoraInicial" => $sDataInicial));

      if (!isset($oRetornoDadosLeitura->getDadosLeituraResult->Movimentacao)) {
        throw new Exception('Nenhuma leitura para ser processada.');
      } 
      
      $aDadosLeitura            = $oRetornoDadosLeitura->getDadosLeituraResult->Movimentacao;
      if (count($aDadosLeitura) > 0) {
        
        $oDaoControleAcessoAluno->ed100_dataleitura = date('Y-m-d',db_getsession('DB_datausu'));
        $oDaoControleAcessoAluno->ed100_horaleitura = db_hora(0, 'H:i:s');
        $oDaoControleAcessoAluno->ed100_id_usuario  = db_getsession("DB_id_usuario");
        $oDaoControleAcessoAluno->incluir(null);
        if ($oDaoControleAcessoAluno->erro_status == 0) {
          throw new Exception("Erro ao salvar dados da leitura.\n{$oDaoControleAcessoAluno->erro_msg}");
        }

        /**
         * Array de cache para controle dos alunos existentes
         * o indice do array � a propria matricula, para evitarmos
         * de percorrer o array a cada leitura.
         */
        $oDaoAluno              = db_utils::getDao("aluno");
        $aCacheAlunosExistentes = array();
        foreach ($aDadosLeitura as $oParam => $oMovimento) {
          
          /**
           * percorremos os dados das leituras, e verificamos se a matr�cula realmente existe.
           * para realizar a pequisa, devemos primeiramente certificar que o campo da matricula 
           * realmente retornou como um inteiro. caso nao seja, j� marcamos como invalido a leitura.
           *  $ final da string.
           * a express�o regular, valida se existe qualquer numero de caractes que nao seja digitos.
           * caso isso verdadeiro o valor nao � um inteiro
           */
          $oMovimento->Matricula = (int) $oMovimento->Matricula;
          $oMovimento->valido = true;
          if (empty($oMovimento->Matricula)) {
            continue;
          }
          if (!is_int($oMovimento->Matricula)) {
            $oMovimento->valido = false;
          } else {
            
            /**
             * a matricula � um inteiro, pesquisamos pelo c�digo do aluno para ver se � um aluno valido 
             * do sistema.
             */
            if (!isset($aCacheAlunosExistentes[$oMovimento->Matricula])) {
             
              $aCacheAlunosExistentes[$oMovimento->Matricula] = true;
              $sSqlAlunoValido = $oDaoAluno->sql_query_file($oMovimento->Matricula);
              $rsAlunoValido   = $oDaoAluno->sql_record($sSqlAlunoValido);
              if ($oDaoAluno->numrows == 0) {
                $aCacheAlunosExistentes[$oMovimento->Matricula] = false;
              }
            }
            /**
             * Aluno nao � valido
             */
            if (!$aCacheAlunosExistentes[$oMovimento->Matricula]) {
              $oMovimento->valido = false;
            }
          }
          $aDataHoraLeitura    = explode("T", $oMovimento->DataHoraLeitura);
          $aHoraCorreta        = explode(".", $aDataHoraLeitura[1]);
          $aDataHoraLeitura[1] = $aHoraCorreta[0];
          unset($aHoraCorreta);
           
          $aDataHoraSistema = $aDataHoraLeitura; 
          $oDaoControleAcessoAlunoRegistro->ed101_controleacessoaluno = $oDaoControleAcessoAluno->ed100_sequencial;
          $oDaoControleAcessoAlunoRegistro->ed101_dataleitura         = $aDataHoraLeitura[0]; 
          $oDaoControleAcessoAlunoRegistro->ed101_horaleitura         = $aDataHoraLeitura[1]; 
          $oDaoControleAcessoAlunoRegistro->ed101_datasistema         = $aDataHoraSistema[0]; 
          $oDaoControleAcessoAlunoRegistro->ed101_horasistema         = $aDataHoraSistema[1]; 
          $oDaoControleAcessoAlunoRegistro->ed101_entrada             = $oMovimento->IsEntrada?"true":"false";
          $oDaoControleAcessoAlunoRegistro->incluir(null);
          if ($oDaoControleAcessoAlunoRegistro->erro_status == 0) {
            
            $sMsgErro  = "Erro ao salvar dados do registro de movimenta��o do aluno.\n";
            $sMsgErro .= "{$oDaoControleAcessoAlunoRegistro->erro_msg}";
            $sMsgErro .= "\n{$aDataHoraSistema[1]}";
            throw new Exception($sMsgErro);
          }
          
          if ($oMovimento->valido) {
            
            $oDaoControleAcessoAlunoRegistroValido = db_utils::getDao("controleacessoalunoregistrovalido");
            $oDaoControleAcessoAlunoRegistroValido->ed303_aluno = $oMovimento->Matricula; 
            $oDaoControleAcessoAlunoRegistroValido->ed303_controleacessoalunoregistro = 
                                                    $oDaoControleAcessoAlunoRegistro->ed101_sequencial;
            $oDaoControleAcessoAlunoRegistroValido->incluir(null);
            if ($oDaoControleAcessoAlunoRegistroValido->erro_status == 0) {
            
              $sMsgErro  = "Erro ao salvar dados do registro de movimenta��o do aluno.\n";
              $sMsgErro .= "{$oDaoControleAcessoAlunoRegistroValido->erro_msg}";
              $sMsgErro .= "\n{$aDataHoraSistema[1]}";
              throw new Exception($sMsgErro);
            }                                                     
          } else {
            
            $oDaoControleAcessoAlunoRegistroInvalido = db_utils::getDao("controleacessoalunoregistroinvalido");
            $oDaoControleAcessoAlunoRegistroInvalido->ed304_codigoinvalido = $oMovimento->Matricula; 
            $oDaoControleAcessoAlunoRegistroInvalido->ed304_controleacessoalunoregistro = 
                                                    $oDaoControleAcessoAlunoRegistro->ed101_sequencial;
            $oDaoControleAcessoAlunoRegistroInvalido->incluir(null);
            if ($oDaoControleAcessoAlunoRegistroInvalido->erro_status == 0) {
            
              $sMsgErro  = "Erro ao salvar dados do registro de movimenta��o do aluno.\n";
              $sMsgErro .= "{$oDaoControleAcessoAlunoRegistroInvalido->erro_msg}";
              $sMsgErro .= "\n{$aDataHoraSistema[1]}";
              throw new Exception($sMsgErro);
            }
          }
        }
      } else {
        $oRetorno->message = urlencode('Nenhuma leitura para ser processada.');
      }
    
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
    unlink($sLockFile);
    break;
  
  case 'getDadosAcessoAluno':
    
    $oDaoControleAcessoAlunoRegistro = db_utils::getDao('controleacessoalunoregistro');
    
    $sWhere = '';
    $sAnd   = '';
    $oParam->dataInicial = implode("-", array_reverse(explode("/", $oParam->dataInicial)));
    $oParam->dataFinal = implode("-", array_reverse(explode("/", $oParam->dataFinal)));
    
    if ($oParam->dataInicial != "" ) {
      
      $sWhere .=  " ed101_dataleitura >= '{$oParam->dataInicial}' ";
      $sAnd = 'and'; 
    }
    if ($oParam->dataFinal != '') {
      
      $sWhere .=  " {$sAnd} ed101_dataleitura <= '{$oParam->dataFinal}' ";
      $sAnd = 'and';
    }
    if ($oParam->iAluno != '') {
      $sWhere .=  " {$sAnd} ed47_i_codigo = '{$oParam->iAluno}' ";
    }
    
    $sOrderBy          = 'ed303_aluno, ed101_dataleitura, ed101_horaleitura';
    $sCampos           = "ed303_aluno as ed101_aluno, ed47_v_nome, ed101_dataleitura, ed101_horaleitura, ed101_entrada";
    $sSqlDadosAluno    = $oDaoControleAcessoAlunoRegistro->sql_query_acesso_aluno(null, $sCampos, $sOrderBy, $sWhere);
    $rsDadosAcesso     = $oDaoControleAcessoAlunoRegistro->sql_record($sSqlDadosAluno);
    $oRetorno->acessos =  db_utils::getCollectionByRecord($rsDadosAcesso, true, false, true);
    break;    
}
echo $oJson->encode($oRetorno);