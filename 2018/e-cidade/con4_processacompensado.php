<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


$cltranslan           = new cl_translan();
$clencerramentolancam = new cl_conencerramentolancam();
$clconencerramento    = new cl_conencerramento();
$clempelemento        = new cl_empelemento();
$clconlancam          = new cl_conlancam();
$clconlancamemp       = new cl_conlancamemp();
$clconlancamdoc       = new cl_conlancamdoc();
$clconlancamval       = new cl_conlancamval();
$clconlancamlr        = new cl_conlancamlr();
$get                  = db_utils::postmemory($_GET);
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
<?php
switch ($get->tipo){
   case 3:
      echo "Encerramento das Contas do Compensado";
      $iCodDoc       = 1005;
      $liberabotao   = "cancelacompensado";
      $bloqueiabotao = "processacompensado";
   break;
   case 4:
      echo "Transferência de saldo das contas de resultado";
      $iCodDoc       = 1006;
      $liberabotao   = "cancelatrans";
      $bloqueiabotao = "processatrans";
  break;
}
?>
</div>
<br>
<br>
<center>
<?php
(boolean)$lSqlErro     = false;
(string) $sErroMsg     = null;
(float)  $nValorLancar = 0;
(integer)$iAnoUsu      = db_getsession("DB_anousu");
(integer)$iInstit      = db_getsession("DB_instit");
$data                  = explode("/",$get->datalanc);
$dataIni               = $iAnoUsu.'-01-01';
$dataUsu               = $data[2]."-".$data[1]."-".$data[0];
db_inicio_transacao();
db_criatermometro("divimp2",'concluido...','blue',1,null);

$sSQlContas  = "SELECT contranslr.*, ";
$sSQlContas .= "       c46_codhist ";
$sSQlContas .= "  from contrans ";
$sSQlContas .= "          inner join contranslan on c46_seqtrans    = c45_seqtrans ";
$sSQlContas .= "          inner join contranslr  on c46_seqtranslan = c47_seqtranslan ";
$sSQlContas .= " where c45_coddoc = $iCodDoc";
$sSQlContas .= "   and c45_instit = ".$iInstit;
$sSQlContas .= "   and c45_anousu = ".$iAnoUsu;
$rsContas    = db_query($sSQlContas);
$iNumRowsLan = pg_num_rows($rsContas);
if ($rsContas){
    $rsEnce   = $clconencerramento->sql_record($clconencerramento->sql_query(null,"*",null," c42_instit       = $iInstit
                                                               and  c42_anousu           = $iAnoUsu
                                                               and  c42_encerramentotipo = ".$get->tipo));
    echo pg_last_error()."<br>";
    if ($clconencerramento->numrows == 0){

       $clconencerramento->c42_instit           = $iInstit;
       $clconencerramento->c42_anousu           = $iAnoUsu;
       $clconencerramento->c42_encerramentotipo = $get->tipo;
       $clconencerramento->c42_usuario          = db_getsession("DB_id_usuario");
       $clconencerramento->c42_hora             = date("H:i");
       $clconencerramento->c42_data             = $dataUsu;
       $clconencerramento->incluir(null);
       $iEncerramento = $clconencerramento->c42_sequencial;
    }else{

      db_msgbox("Procedimento já executado!\\nPara executa-lo novamente, primeiro  cancele o existente.");
      echo "<script>";
      echo "   parent.db_iframe_canccomp.hide()</script>";
      exit;

   }
   for ($i = 0;$i < pg_num_rows($rsContas); $i++){

       $oContas = db_utils::fieldsMemory($rsContas,$i);
       if ($oContas->c47_compara == 1){
           $c61_reduz= $oContas->c47_debito;
       }else if ($oContas->c47_compara ==2) {
           $c61_reduz= $oContas->c47_credito;
       }else{

        $sErroMsg = "Transacao sem Definição de regra de comparacao ( Cta Cred: ".$oContas->c47_credito." Cta Deb: ".$oContas->c47_debito." )" ;
        $lSqlErro = true;
       }
       if (!$lSqlErro){
           $sSQlPlan = db_planocontassaldo_matriz($iAnoUsu,$dataIni,$dataUsu,true,"c61_reduz = $c61_reduz
                                           and c61_instit= ".db_getsession("DB_instit"), '', 'true','true');
          $rsPlan=db_query($sSQlPlan);
          db_query("drop table work_pl");
          if (pg_num_rows($rsPlan) > 0){

             $oPlan        = db_utils::fieldsMemory($rsPlan,0);
             $nValorLancar = $oPlan->saldo_final;
          }else{

            $lSqlErro = true;
            $sErroMsg = "Sem saldo para conta ( $c61_reduz )";
          }
       }
       if (!$lSqlErro){
         //incluindo na conlancam
	   	    $clconlancam->c70_anousu = $iAnoUsu;
		      $clconlancam->c70_data   = $dataUsu;
		      $clconlancam->c70_valor  = $nValorLancar;
		      $clconlancam->incluir(null);
		      if ($clconlancam->erro_status==0){

		        $lSqlErro =   true;
            $sErroMsg =  "Erro ao Incluir lançamento. Erro:".$clconlancam->erro_msg;
          }
          $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
          $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
       }
		   $c70_codlan = $clconlancam->c70_codlan;
       //conencerramentolancam
       if (!$lSqlErro){

          $clencerramentolancam->c44_encerramento = $iEncerramento;
          $clencerramentolancam->c44_conlancam    = $clconlancam->c70_codlan;
          $clencerramentolancam->incluir(null);
          if ($clencerramentolancam->erro_status == 0){

		         $lSqlErro =   true;
             $sErroMsg =  $clencerramentolancam->erro_msg;
          }
       }
       //lancando na conlancamdoc
       if (!$lSqlErro){
		      $clconlancamdoc->c71_data    = $dataUsu;
   	 	    $clconlancamdoc->c71_coddoc  = $iCodDoc;
	  	    $clconlancamdoc->c71_codlan  = $c70_codlan;
		      $clconlancamdoc->incluir($c70_codlan);
		      if ($clconlancamdoc->erro_status==0){

		          $this->lSqlErro  = true;
              $sErroMsg        = $clconlancamdoc->erro_msg;
		      }
      }
      if (!$lSqlErro){

			   $clconlancamval->c69_codlan  = $c70_codlan;
         $clconlancamval->c69_credito = $oContas->c47_credito;
			   $clconlancamval->c69_debito  = $oContas->c47_debito;
			   $clconlancamval->c69_codhist = $oContas->c46_codhist;
			   $clconlancamval->c69_valor   = $nValorLancar;
			   $clconlancamval->c69_data    = $dataUsu;
			   $clconlancamval->c69_anousu  = $iAnoUsu;
			   $clconlancamval->incluir(null);
			   if ($clconlancamval->erro_status==0){

			       $lSqlErro = true;
			       $sErroMsg = $clconlancamval->erro_msg;
          }
       }
       db_atutermometro($i,$iNumRowsLan,'divimp2');
    }
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

      }

      } catch (Exception $eErro) {

        $lSqlErro = true;
        $sErroMsg = $eErro->getMessage();
      }
    db_fim_transacao($lSqlErro);
    if (!$lSqlErro){

      $sErroMsg = "Lançamentos realizados com Sucesso";
    }
    db_msgbox($sErroMsg);
}else{

  db_msgbox("Não existe o documento ( $iCodDoc ) cadastrado. Lançamentos nao realizados.");

}

echo "<script>";
echo "parent.$('$liberabotao').disabled   = false;\n";
echo "parent.$('$bloqueiabotao').disabled = true;\n";
echo "   parent.db_iframe_canccomp.hide();
</script>";
?>
</center>
</body>
</html>