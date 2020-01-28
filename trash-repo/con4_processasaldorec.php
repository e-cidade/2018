<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
Encerramento de saldo receita/despesa
</div>
<br>
<br>
<center>
<?php
(boolean)$llSqlErro     = false;
(string) $sErroMsg     = null;
(float)  $nValorLancar = 0;
(integer)$iAnoUsu      = db_getsession("DB_anousu");
(integer)$iInstit      = db_getsession("DB_instit");
$data                  = explode("/",$get->datalanc);
$dataIni               = $iAnoUsu.'-01-01';
$dataUsu               = $data[2]."-".$data[1]."-".$data[0];
$debug                 = false;
db_inicio_transacao();
db_criatermometro("divimp2",'concluido...','blue',1,null);
// lan�amentos da receita e DESPESA, zera contas conta 511, documento 1001 � debito
$anousu  = db_getsession("DB_anousu");
$dt_ini = $anousu.'-01-01';
$dt_fin = $dataUsu;
$lSqlErro =false;
$doc = "";
$arr_debito  = array();
$arr_credito = array();
$arr_histori  = array();
$arr_seqtranslr = array();


db_inicio_transacao();    
$rsEnce   = $clconencerramento->sql_record($clconencerramento->sql_query(null,"*",null," c42_instit       = $iInstit 
                                                               and  c42_anousu           = $iAnoUsu
                                                               and  c42_encerramentotipo = 2")); 
if ($clconencerramento->numrows == 0){
          
   $clconencerramento->c42_instit           = $iInstit;
   $clconencerramento->c42_anousu           = $iAnoUsu;
   $clconencerramento->c42_encerramentotipo = 2;
   $clconencerramento->c42_usuario          = db_getsession("DB_id_usuario");
   $clconencerramento->c42_hora             = date("H:i");
   $clconencerramento->c42_data             = $dataUsu;
   $clconencerramento->incluir(null);
   $iEncerramento = $clconencerramento->c42_sequencial;
}else{
      
   db_msgbox("Procedimento j� executado!\\nPara executa-lo novamente, primeiro  cancele o existente.");
   echo "<script>";
   echo "   parent.db_iframe_canccomp.hide()</script>";
   exit;

}
$result = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,"substr(c60_estrut,1,1) in ('3','4','9') and c61_instit= ".db_getsession("DB_instit"),'','true','true');    
$iNumRowsLan = pg_num_rows($result);
if ($debug==true){
      echo "processando receita, zera contas conta 511, documento 1001 � debito";
      db_criatabela($result);    	  	
}
//db_criatabela($result);    	  	
//exit;
for ($x=0;$x< pg_numrows($result);$x++){
	db_fieldsmemory($result,$x);       		        
  	    
  $lanca = true;      
  if ((substr($estrutural,0,2)=='41'  || substr($estrutural,0,2)=='47' ) && $c61_reduz !=0  ){ 
  	// receitas correntes
  	 $doc ="1001";
     $cltranslan->db_trans_documento($doc,$c61_reduz);	        
     $arr_debito  = $cltranslan->arr_debito;
     $arr_credito = $cltranslan->arr_credito;
     $arr_histori  = $cltranslan->arr_histori;		      
     $arr_seqtranslr = $cltranslan->arr_seqtranslr;
     
  } else if (substr($estrutural,0,2)=='42' && $c61_reduz !=0  ){
      //  receitas de capital
     $doc ="1002"; 
     $cltranslan->db_trans_documento($doc,$c61_reduz);	        
     $arr_debito  = $cltranslan->arr_debito;
     $arr_credito = $cltranslan->arr_credito;
     $arr_histori  = $cltranslan->arr_histori;
     $arr_seqtranslr = $cltranslan->arr_seqtranslr;
  }elseif (db_conplano_grupo($anousu,substr($estrutural,0,2)."%",9000) == true && $c61_reduz !=0  ){  // 49
      //  dedu��es da receita corrente
     $doc ="1001"; 
     $cltranslan->db_trans_documento($doc,$c61_reduz);
     $arr_debito  = $cltranslan->arr_credito; // invertemos debito e credito
     $arr_credito = $cltranslan->arr_debito;
     $arr_histori  = $cltranslan->arr_histori;
     $arr_seqtranslr = $cltranslan->arr_seqtranslr;
     
  }elseif (substr($estrutural,0,2)=='33' && $c61_reduz !=0  ){
      //  dedu��es da despesa corrente
     $doc ="1003"; 
     $cltranslan->db_trans_documento($doc,$c61_reduz);
     $arr_debito  = $cltranslan->arr_debito;
     $arr_credito = $cltranslan->arr_credito;
     $arr_histori  = $cltranslan->arr_histori;
     $arr_seqtranslr = $cltranslan->arr_seqtranslr;
  }elseif (substr($estrutural,0,2)=='34' && $c61_reduz !=0  ){
      //  dedu��es da despesa de capital
     $doc ="1004"; 
     $cltranslan->db_trans_documento($doc,$c61_reduz);
     $arr_debito  = $cltranslan->arr_debito;
     $arr_credito = $cltranslan->arr_credito;
     $arr_histori  = $cltranslan->arr_histori;
     $arr_seqtranslr = $cltranslan->arr_seqtranslr;
  }else{
    $lanca = false;
  }
  // critica
  if(count($arr_credito)==0  && $c61_reduz !=0 && $lanca){
        echo "{$c61_reduz} - {$estrutural}<br>";
        $lSqlErro = true;
        $erro_msg="N�o encontrei transa��es cadastradas";
        db_msgbox($erro_msg);
        break;
    }	    
  if ($debug==true && $c61_reduz !=0 ){         
   	 echo "imprimindo transa��es" ;
     print_r($arr_debito); 
	   print_r($arr_credito);			  
	   print_r($arr_histori);
	   echo " saldo da conta em 31/12 ".$saldo_final;
	   echo "<br>";	
 }
 // gera conlancam
 if ($lSqlErro==false && $c61_reduz !=0  && $saldo_final > 0 && $lanca){ 

    $clconlancam->c70_anousu = $anousu; 
		$clconlancam->c70_data   = $dt_fin; 
		$clconlancam->c70_valor  = $saldo_final;
		$clconlancam->incluir(null);
		$erro_msg=$clconlancam->erro_msg;
		if($clconlancam->erro_status==0){
		     $lSqlErro=true;
		     db_msgbox($clconlancam->erro_msg);
		}else{
		     $c70_codlan = $clconlancam->c70_codlan;
		}
    if (!$lSqlErro){
          $clencerramentolancam->c44_encerramento = $iEncerramento;
          $clencerramentolancam->c44_conlancam    = $clconlancam->c70_codlan;
          $clencerramentolancam->incluir(null);
          if ($clencerramentolancam->erro_status == 0){

		         $lSqlErro =   true;
             $sErroMsg =  $clencerramentolancam->erro_msg;
          } 
       }
	     // documento	
	      $clconlancamdoc->c71_data   = $dt_fin;
          $clconlancamdoc->c71_coddoc  = $doc;
          $clconlancamdoc->c71_codlan  = $c70_codlan;
          $clconlancamdoc->incluir($c70_codlan);
          $erro_msg=$clconlancamdoc->erro_msg;
          if($clconlancamdoc->erro_status==0){
          	 db_msgbox($clconlancamdoc->erro_msg);
             $lSqlErro=true;
          }				
		// gera conlancamval
		for($t=0; $t<count($arr_credito); $t++) {
		   
				$clconlancamval->c69_codlan  = $c70_codlan;
				$clconlancamval->c69_credito  = $arr_credito[$t];
				$clconlancamval->c69_debito   = $arr_debito[$t];
				$clconlancamval->c69_codhist = $arr_histori[$t];
				$clconlancamval->c69_valor     = $saldo_final;
				$clconlancamval->c69_data      = $dt_fin;
				$clconlancamval->c69_anousu  = $anousu;
				$clconlancamval->incluir(null);
				$erro_msg=$clconlancamval->erro_msg;
				if($clconlancamval->erro_status==0){
			  	    $lSqlErro=true;
			  	    db_msgbox($clconlancamval->erro_msg);
				    break;
				}else{
				    $c69_sequen =  $clconlancamval->c69_sequen;
				}				       
		                if($lSqlErro==false){
				          /*
					  $clconlancamlr->c81_sequen      = $c69_sequen;
					  $clconlancamlr->c81_seqtranslr  = $arr_seqtranslr[$t];
					  $clconlancamlr->incluir($c69_sequen,$arr_seqtranslr[$t]);
					  $erro_msg=$clconlancamlr->erro_msg;
			                  if($clconlancamlr->erro_status==0){
					       $lSqlErro=true;
					       db_msgbox($clconlancamlr->erro_msg);
					       break;
			                  }
					  */ 
			        }  
		}//  end for
 } // end if	   
 db_atutermometro($x,$iNumRowsLan,'divimp2');
} // end for

    /**
   * Try Catch do lan�amento contabil
   */
  try {
    
    $iBloqueioLancamento = $clconencerramento->lancaBloqueioContabil(); 

    if ($iBloqueioLancamento == 1) {
      
      $sMsgLancamento_1  = "Voc� acaba de executar o encerramento cont�bil do exerc�cio. O sistema lan�ou ";
      $sMsgLancamento_1 .= "automaticamente a data de 31/12/$iAnoUsu no encerramento de per�odo cont�bil e bloquear� ";
      $sMsgLancamento_1 .= "qualquer tentativa de inclus�o, altera��o e exclus�o de lan�amentos at� este per�odo. Caso seja "; 
      $sMsgLancamento_1 .= "necess�rio, voc� pode desfazer este bloqueio atrav�s do menu CONTABILIDADE > PROCEDIMENTOS > "; 
      $sMsgLancamento_1 .= "UTILIT�RIOS DA CONTABILIDADE > ENCERRAMENTO DE PER�ODO CONT�BIL";
      db_msgbox($sMsgLancamento_1);
            
    } 

  } catch (Exception $eErro) {
    
    $lSqlErro = true;
    $sErroMsg = $eErro->getMessage(); 
  }
  db_fim_transacao($lSqlErro);

if (!$lSqlErro){

  $sErroMsg = "Lan�amentos realizados com Sucesso";
 }
 db_msgbox($sErroMsg);
echo "<script>";
echo "parent.$('cancelarec').disabled   = false;\n";
echo "parent.$('processarec').disabled = true;\n";
echo "   parent.db_iframe_canccomp.hide()</script>";
?>
</center>
</body>
</html>