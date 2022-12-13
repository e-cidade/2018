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
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsaservico_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsanumpre_classe.php");
include("dbforms/db_funcoes.php");

function db_calculaLinhasTexto22($texto){
 
   $linha = 1;
   $caracter = 0;
   for ($i = 0;$i < strlen($texto); $i++){
       if ($caracter == 59 or substr($texto,$i,1) == "\n"){
         $linha++;
         $caracter = 0;
       }
       $caracter++;
   }
   return $linha;
}
$clissnotaavulsaservico = new cl_issnotaavulsaservico;
$clissnotaavulsa        = new cl_issnotaavulsa;
$clissnotaavulsatomador = new cl_issnotaavulsatomador;
$clparissqn             = new cl_parissqn;
$get                    = db_utils::postmemory($_GET);
$post                   = db_utils::postmemory($_POST);
$db_opcao               = 22;
$db_botao               = false;
$q62_notaavulsa         = isset($post->q62_issnotaavulsa)?$post->q62_issnotaavulsa:$get->q51_sequencial;
$lGeraNota              = false;
$emitenota              = true;    
$rsPar                  = $clparissqn->sql_record($clparissqn->sql_query(null,"*"));
$oPar                   = db_utils::fieldsMemory($rsPar,0); 
if(isset($post->alterar) || isset($post->excluir) || isset($post->incluir)){
  $sqlerro = false;
  /*
$clissnotaavulsaservico->q62_sequencial = $q62_sequencial;
$clissnotaavulsaservico->q62_issnotaavulsa = $q62_issnotaavulsa;
$clissnotaavulsaservico->q62_qtd = $q62_qtd;
$clissnotaavulsaservico->q62_discriminacao = $q62_discriminacao;
$clissnotaavulsaservico->q62_vlruni = $q62_vlruni;
$clissnotaavulsaservico->q62_aliquota = $q62_aliquota;
$clissnotaavulsaservico->q62_vlrdeducao = $q62_vlrdeducao;
$clissnotaavulsaservico->q62_vlrtotal = $q62_vlrtotal;
$clissnotaavulsaservico->q62_vlrbasecalc = $q62_vlrbasecalc;
$clissnotaavulsaservico->q62_vlrissqn = $q62_vlrissqn;
$clissnotaavulsaservico->q62_obs = $q62_obs;

 */

/*
  -- gerar debito
       arrecad -> insert.
	-- gerar o recibo
	     pegar numpre novo na numpref
			 insert na db_reciboweb
       fc_recibo

  
*/
}
if(isset($post->incluir)){
  if($sqlerro==false){

    db_inicio_transacao();
    $clissnotaavulsaservico->incluir(null);
    $erro_msg = $clissnotaavulsaservico->erro_msg;
    if($clissnotaavulsaservico->erro_status==0){
      $sqlerro=true;
    }else{

      $post->totlinhas += db_calculaLinhasTexto22($post->q62_discriminacao);
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($post->alterar)){
  if($sqlerro==false){

    db_inicio_transacao();
    $clissnotaavulsaservico->alterar($post->q62_sequencial);
    $post->totlinhas += db_calculaLinhasTexto22($post->q62_discriminacao);
    $erro_msg = $clissnotaavulsaservico->erro_msg;
    if($clissnotaavulsaservico->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($post->excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clissnotaavulsaservico->excluir($post->q62_sequencial);
    $erro_msg = $clissnotaavulsaservico->erro_msg;
    if($clissnotaavulsaservico->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clissnotaavulsaservico->sql_record($clissnotaavulsaservico->sql_query($post->q62_sequencial));
   if($result!=false && $clissnotaavulsaservico->numrows>0){
     db_fieldsmemory($result,0);
   }
}
if (isset($post->recibo)){
	
  $lSqlErro = false;
  $rsNot    = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query($post->q62_issnotaavulsa,"*"));
  $oNot     = db_utils::fieldsMemory($rsNot,0); 
  if ($post->totlinhas > 40 ){
    
    db_msgbox('Total das linhas da descrição da nota maior que o permitido (40 linha)');
    $emitenota = true; 
    $lGeraNota = true;
    $db_botao  = true;
  }else if (str_replace(",",".",$post->vlrrectotal) >= $oPar->q60_notaavulsavlrmin){
       
			db_inicio_transacao();
		  $clarrecad  = new cl_arrecad();
		  $clarrehist = new cl_arrehist();
			$rsNum      = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
			$oNum       = db_utils::fieldsMemory($rsNum,0);
      //Codigo numpre do Recibo 
			$rsNumnov   = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numnov");
			$oNumnov    = db_utils::fieldsMemory($rsNumnov,0);
      $aDataPgto  = explode("-",$oNot->q51_dtemiss);
      $dataPagto  = date("Y-m-d",mktime(0,0,0,$aDataPgto[1],$aDataPgto[2]+$oPar->q60_notaavulsadiasprazo,$aDataPgto[0]));
      $clarrecad->k00_numpre = $oNum->k03_numpre;
			$clarrecad->k00_numpar = 1;
			$clarrecad->k00_numcgm = $oNot->q02_numcgm;
			$clarrecad->k00_valor  = str_replace(",",".",str_replace(".","",$post->vlrrectotal));
			$clarrecad->k00_receit = $oPar->q60_receit;
			$clarrecad->k00_tipo   = $oPar->q60_tipo;
			$clarrecad->k00_dtoper = $oNot->q51_dtemiss; 
			$clarrecad->k00_dtvenc = $dataPagto; 
			$clarrecad->k00_numtot = 1; 
			$clarrecad->k00_numdig = 1;
			$clarrecad->k00_tipojm = 1;
			$clarrecad->k00_hist   = $oPar->q60_histsemmov;
			$clarrecad->incluir();
			if ($clarrecad->erro_status == 0){

          $lSqlErro = true;
					$erro_msg = $clarrecad->erro_msg;
			}
      if (!$lSqlErro){

           $clarrehist->k00_numpre     = $oNum->k03_numpre;
           $clarrehist->k00_numpar     = 1;
           $clarrehist->k00_hist       = $oPar->q60_histsemmov;
           $clarrehist->k00_dtoper     = $oNot->q51_dtemiss;
           $clarrehist->k00_id_usuario = db_getsession("DB_id_usuario");
           $clarrehist->k00_hora       = date("h:i");
           $clarrehist->k00_histtxt    = "Valor referente a nota fiscal avulsa nº ".$q62_issnotaavulsa." de (".db_formatar($oNot->q51_dtemiss,"d").")";
           $clarrehist->k00_limithist  = null;
           $clarrehist->incluir(null);
           if ($clarrehist->erro_status == 0){

              $lSqlErro = true;
					    $erro_msg = $clarrehist->erro_msg;

           }

      }
			if (!$lSqlErro){

        $clissnotaavulsanumpre = new cl_issnotaavulsanumpre(); 
				$clissnotaavulsanumpre->q52_issnotaavulsa = $q62_issnotaavulsa;
				$clissnotaavulsanumpre->q52_numpre        = $oNum->k03_numpre;
				$clissnotaavulsanumpre->q52_numnov        = $oNumnov->k03_numnov;
				$clissnotaavulsanumpre->incluir(null);
       	if ($clissnotaavulsanumpre->erro_status == 0){
             
						 $lSqlErro = true;
						 $erro_msg = $clissnotaavulsanumpre->erro_msg;

				}
        if (!$lSqlErro){
          
           $clarreinscr             = new cl_arreinscr();
           $clarreinscr->k00_perc   = 100;
           $clarreinscr->k00_inscr  = $oNot->q02_inscr; 
           $clarreinscr->k00_numpre = $oNum->k03_numpre; 
           $clarreinscr->incluir($oNum->k03_numpre,$oNot->q02_inscr);
           if ($clarreinscr->erro_status == 0){
                
                $lSqlErro = true;
                $erro_msg = $clarreinscr->erro_msg;
           }
        }

			}
			
      db_fim_transacao($lSqlErro); 
			if ($lSqlErro){

        db_msgbox($erro_msg);
			}else{
         
       $db_botao = false;
       $rsObs    = $clissnotaavulsaservico->sql_record(
			                      $clissnotaavulsaservico->sql_query(null,"sum(q62_vlrissqn) as tvlrissqn,
														                                         sum(q62_vlrdeducao) as tvlrdeducoes,
																																		 sum(q62_vlrtotal) as tvlrtotal",
																															null,"q62_issnotaavulsa=".$post->q62_issnotaavulsa)); 			 
			 $rsTom = $clissnotaavulsatomador->sql_record($clissnotaavulsatomador->sql_query_tomador($post->q62_issnotaavulsa)); 										
			 $oTom  = db_utils::fieldsMemory($rsTom,0);
		   $oObs  = db_utils::fieldsmemory($rsObs,0);												
       $obs   = "Referente a nota fiscal avulsa nº ".$oNot->q51_numnota."\n";
			 $obs  .= "Tomador : ".$oTom->z01_cgccpf." - ".$oTom->z01_nome."\n";
			 $obs  .= "Imposto : R$ ".trim(db_formatar($oObs->tvlrissqn,"f"))."\n";
			 $obs  .= "Deduções: R$ ".trim(db_formatar($oObs->tvlrdeducoes,"f"))."\n";
			 $obs  .= "Valor serviço: R$ ".trim(db_formatar($oObs->tvlrtotal,"f"))."\n";
       session_register("DB_obsrecibo",$obs);
			 db_putsession("DB_obsrecibo",$obs);
       $url   = "iss1_issnotaavulsarecibo.php?numpre=".$oNum->k03_numpre."&tipo=".$oPar->q60_tipo."&ver_inscr=".$oNot->q02_inscr;
			 $url  .= "&numcgm=".$oNot->q02_numcgm."&emrec=t&CHECK10=".$oNum->k03_numpre."P1&tipo_debito=".$oPar->q60_tipo; 
       $url  .= "&k03_tipo=".$oPar->q60_tipo."&k03_parcelamento=f&k03_perparc=f&ver_numcgm=".$oNot->q02_numcgm;
       $url  .= "&totregistros=1&k03_numnov=".$oNumnov->k03_numnov."&loteador=";
       echo "<script>\n";
			 
			 echo " window.open('$url','','location=0');\n";
       echo "</script>\n";

			}

	}
  $lGeraNota = true;

}
if (isset($post->notaavulsa)){
    
   if ($post->totlinhas > 40 ){
    
    db_msgbox('Total das linhs da descrição da nota maior que o permitido (40 linha)');
    $emitenota = true; 
    $lGeraNota = true;
    $db_botao  = true;
 
   }else{
      if ($clissnotaavulsa->emiteNotaAvulsa($post->q62_issnotaavulsa)){

        $emitenota = false; 
        $lGeraNota = false;
        $db_botao  = false;
      }

   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
	<?
	include("forms/db_frmissnotaavulsaservico.php");
	?>
    </center>
  </body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    if ($erro_msg != ''){
       db_msgbox($erro_msg);
    }
    if($clissnotaavulsaservico->erro_campo!=""){
        echo "<script> document.form1.".$clissnotaavulsaservico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clissnotaavulsaservico->erro_campo.".focus();</script>";
    }
}
?>
<script>
function js_emiteNota(num){

   url = "iss2_issnotaavulsanotafiscal002.php?q51_sequencial="+num;
   window.open(url,'','location=0');

}
<?

if (isset($post->recibo)){

    echo "document.getElementById('db_opcao').disabled=true;\n"; 
    echo "document.getElementById('recibo').disabled=true;\n";
 

}
if (isset($post->notaavulsa)){

    echo "document.getElementById('db_opcao').disabled=true;\n"; 
    echo "document.getElementById('recibo').disabled=true;\n";
    echo "document.getElementById('nota').disabled=true;\n";
 

}
?>
</script>