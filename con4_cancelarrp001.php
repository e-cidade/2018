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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conencerramento_classe.php");
include("classes/db_conencerramentolancam_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_empelemento_classe.php");
include("libs/db_libcontabilidade.php");

$clconencerramento       = new cl_conencerramento();
$clconencerramentolancam = new cl_conencerramentolancam;
$clempelemento           = new cl_empelemento();
$clconlancam             = new cl_conlancam();
$clconlancamemp          = new cl_conlancamemp();
$clconlancamdoc          = new cl_conlancamdoc();
$clconlancamval          = new cl_conlancamval();
$clconlancamlr           = new cl_conlancamlr();
$get                     = db_utils::postmemory($_GET);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cab  {font-weight:bold;text-align:center;
       padding:2px;
			 border-bottom:1px outset black;
			 border-left:1px outset black;           
       background-color:#EEEFF2;          
	
	}
.linhagrid{ border:collapse;
            border-right:1px inset black;
            border-bottom:1px inset black;
            cursor:normal;
 }
.marcado{ background-color:#EFEFEF}
.normal{background-color:#FFFFFF}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<div class='cab'>
<?
switch ($get->tipo){
   case 1 :
    echo "Cancelamento da Inscrição de Restos a Pagar Não Processados";
    $liberabotao   = "processa_rp";
    $bloqueiabotao = "";
    break;
   case 2 :
     
    echo "Cancelamento do Saldo Receita/despesa";
    $liberabotao   = "processarec";
    $bloqueiabotao = "cancelarec";
    break;
    
   case 3:
    echo  "Cancelamento do Encerramento das Contas do Compensado";
    $liberabotao   = "processacompensado";
    $bloqueiabotao = "cancelacompensado";
    break;
   case 4:
      echo "Transferência de saldo das contas de resultado";
      $iCodDoc       = 1006;
      $liberabotao   = "processatrans";
      $bloqueiabotao = "cancelatrans";
  break;
}
?>
</div>
<br>
<br>
<center>
<?php
(boolean)$lSqlErro = false;
(string) $sErro    = null;
(integer)$iAnoUsu  = db_getsession("DB_anousu");
(integer)$iInstit  = db_getsession("DB_instit");
$rsEnce = $clconencerramento->sql_record($clconencerramento->sql_query(null,"*",null," c42_instit = $iInstit
                                                                      and  c42_anousu             = $iAnoUsu
                                                                      and  c42_encerramentotipo   = ".$get->tipo)); 
(int)$iNumRows = $clconencerramento->numrows;
if ($iNumRows > 0) {
  
  /**
   * Antes de excluir o lançamento em conencerramento, verifica se há lançamento referente ao anousu, instituicao
   * e data em condataconf. Se houver ele exclui este lançamento existente e continua a exclusão em conencerramento
   */
  try {
    $lVerificaBloqueio = $clconencerramento->verificaLancamentoContabil();

  } catch (Exception $eErro) {
    
    $lSqlErro = true;
    $sErroMsg = $eErro->getMessage(); 
  }
  
  $oEnc  = db_utils::fieldsMemory($rsEnce,0);
  $rsLan = $clconencerramentolancam->sql_record(
                                      $clconencerramentolancam->sql_query(null,"*"
                                                                         ,null,"c44_encerramento=".$oEnc->c42_sequencial));
  $iNumRowsLan = $clconencerramentolancam->numrows;
  db_inicio_transacao();
	db_criatermometro("divimp2",'concluido...','blue',1,null);
	
  if ($iNumRowsLan > 0){

     for ($i = 0;$i < $iNumRowsLan;$i++){
      
      //deletand da conlancamlr
        $oLan       = db_utils::fieldsMemory($rsLan, $i);
        $sDeletelr  = "delete 
                         from conlancamlr
                        using conlancamval
                        where c69_sequen = c81_sequen
                          and c69_codlan = ".$oLan->c44_conlancam;
       $rsLr        = @db_query($sDeletelr);
       if (!$rsLr){

        $lSqlErro = true;
        $sErro    = "conlancamlr:".pg_last_error(); 
        break;
       }

       if (!$lSqlErro){
       //Deletando da Valores da Conlancam
           $sDeleteVal  = "delete "; 
           $sDeleteVal .= "  from conlancamval ";
           $sDeleteVal .= " where c69_codlan = ".$oLan->c44_conlancam;
           $rsVal       = @db_query($sDeleteVal);
           if (!$rsVal){
        
             $lSqlErro = true;
             $sErro    = "Nao foi Possivel Excluir Lançamentos contábeis (Valores)\\nErro:".pg_last_error();
          }
       }
       if (!$lSqlErro){            
        //deletando lançamentos da  conlancamdoc
          $clconlancamdoc->Excluir(null,"c71_codlan = ".$oLan->c44_conlancam);
          if ($clconlancamdoc->erro_status == 0){
          
             $lSqlErro = true;
             $sErro    = $clconlancamdoc->erro_msg;

          }
       }

       if (!$lSqlErro){
         // deletando da conlancamemp
          $clconlancamemp->Excluir($oLan->c44_conlancam);
          if ($clconlancamemp->erro_status == 0){
              
            $lSqlErro = true;
            $sErro    = $conlancamemp->erro_msg;
          }
       }
       if (!$lSqlErro){

         $clconencerramentolancam->Excluir(null,"c44_conlancam = ".$oLan->c44_conlancam);
         if ($clconencerramentolancam->erro_status == 0){
              
            $lSqlErro = true;
            $sErro    = $clconencerramentolancam->erro_msg;
              
         }
       }
       if (!$lSqlErro){

          $clconlancam->Excluir($oLan->c44_conlancam);
          if ($clconlancam->erro_status == 0){
              
             $lSqlErro = true;
             $sErro    = "conlancam:\\n".$clconlancam->erro_msg;
           }
       }
		  db_atutermometro($i,$iNumRowsLan,'divimp2');
    }
  }
  $clconencerramento->Excluir($oEnc->c42_sequencial);

  /**
   * Try Catch do lançamento contabil
   */
  try {
    
    $iBloqueioLancamento = $clconencerramento->lancaBloqueioContabil(); 

    if ($iBloqueioLancamento == 1) {
      
      $sMsgLancamento_1  = "Você acaba de executar o encerramento contábil do exercício. O sistema lançou ";
      $sMsgLancamento_1 .= "automaticamente a data de 31/12/$iAnoUsu no encerramento de período contábil e bloqueará ";
      $sMsgLancamento_1 .= "qualquer tentativa de inclusão, alteração e exclusão de lançamentos até este período. Caso seja "; 
      $sMsgLancamento_1 .= "necessário, você pode desfazer este bloqueio através do menu CONTABILIDADE > PROCEDIMENTOS > "; 
      $sMsgLancamento_1 .= "UTILITÁRIOS DA CONTABILIDADE > ENCERRAMENTO DE PERÍODO CONTÁBIL";
      db_msgbox($sMsgLancamento_1);
            
    } else if ($iBloqueioLancamento == 2 || $lVerificaBloqueio) {
     
      $sMsgLancamento_2  = "Você está desfazendo o encerramento do exercício de $iAnoUsu. Isto implicará no desbloqueio "; 
      $sMsgLancamento_2 .= "de inclusões, alterações e exclusões de lançamentos contábeis neste período. Se você desejar ";
      $sMsgLancamento_2 .= "confirmar o desprocessamento do encerramento e manter o período bloqueado, basta acessar o menu "; 
      $sMsgLancamento_2 .= "CONTABILIDADE > PROCEDIMENTOS > UTILITÁRIOS DA CONTABILIDADE > ENCERRAMENTO DE PERÍODO CONTÁBIL e ";
      $sMsgLancamento_2 .= "realizar o bloqueio manualmente.";
      db_msgbox($sMsgLancamento_2);
    } 

  } catch (Exception $eErro) {
    
    $lSqlErro = true;
    $sErroMsg = $eErro->getMessage(); 
  }
 
  db_fim_transacao($lSqlErro);
  
  if (!$lSqlErro){

   $sErro  = "Cancelamento Efetuado com Sucesso.";
  }
  
  db_msgbox($sErro);
  echo "<script>";
  echo "parent.$('$liberabotao').disabled   = false;\n";
  if ($bloqueiabotao != ""){
     echo "parent.$('$bloqueiabotao').disabled = true;\n";
  }
  echo "</script>";
  
}

echo "<script>																										 ";
echo "   parent.db_iframe_canccomp.hide();                         ";
echo "   parent.window.location='con4_processaencerramento001.php';";
echo "</script>																										 ";

?>
</center>
</body>
</html>