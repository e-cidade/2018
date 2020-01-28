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
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_issnotaavulsatomadorcgm_classe.php");
include("classes/db_issnotaavulsatomadorinscr_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("dbforms/db_funcoes.php");

$clissnotaavulsatomador      = new cl_issnotaavulsatomador();
$clissnotaavulsatomadorcgm   = new cl_issnotaavulsatomadorcgm();
$clissnotaavulsatomadorinscr = new cl_issnotaavulsatomadorinscr();
$clissnotaavulsa             = new cl_issnotaavulsa();
$clissbase                   = new cl_issbase();
$clcgm                       = new cl_cgm();
$post                        = db_utils::postmemory($_POST);
$get                         = db_utils::postmemory($_GET);
(integer)$db_opcao           = 1;
(boolean)$db_botao           = true;
(boolean)$lSqlErro           = false;
(string)$erro_msg            = null;

if(isset($post->incluir) or isset($post->alterar)){
 
  if (isset($post->alterar)){

		$db_opcao = 2;
	}
  db_inicio_transacao();
	$clissnotaavulsatomador->q53_issnotaavulsa = $get->q51_sequencial;
	if ($db_opcao == 1){
      $clissnotaavulsatomador->incluir(null);
	}else{
		  
			$clissnotaavulsatomador->q53_sequencial = $post->q53_sequencial;
      $clissnotaavulsatomador->alterar($post->q53_sequencial);
      $clissnotaavulsatomadorinscr->excluir(null,"q54_issnotaavulsatomador=".$post->q53_sequencial);
      $clissnotaavulsatomadorcgm->excluir(null,"q61_issnotaavulsatomador=".$post->q53_sequencial);
	}
	if ($clissnotaavulsatomador->erro_status == 0){
      
			$lSqlErro = true;
			$erro_msg = $clissnotaavulsatomador->erro_msg;
      
	}
	if (!$lSqlErro){

       if ($post->q61_numcgm != ''){
         
				  $clissnotaavulsatomadorcgm->q61_issnotaavulsatomador = $clissnotaavulsatomador->q53_sequencial;
				  $clissnotaavulsatomadorcgm->incluir(null);
					if ($clissnotaavulsatomadorcgm->erro_status == 0){

            $lSqlErro = true;
						$erro_msg  = $clissnotaavulsatomadorcgm->erro_msg;
					}

			 }else if ($post->q54_inscr != ''){
       
				  $clissnotaavulsatomadorinscr->q54_issnotaavulsatomador = $clissnotaavulsatomador->q53_sequencial;
				  $clissnotaavulsatomadorinscr->incluir(null);
					if ($clissnotaavulsatomadorinscr->erro_status == 0){

            $lSqlErro = true;
						$erro_msg  = $clissnotaavulsatomadorinscr->erro_msg;
					}

			 }
	}
  db_fim_transacao($lSqlErro);
}

if (isset($get->q51_sequencial)){
      
    $rsNota = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query($get->q51_sequencial));
    if ($clissnotaavulsa->numrows > 0){ 	

      	$oNota           = db_utils::fieldsmemory($rsNota,0);
        $q51_numnota     = $oNota->q51_numnota;
				$q51_dtemiss     = split("-",$oNota->q51_dtemiss);
        $q51_dtemiss_dia = $q51_dtemiss[2];
        $q51_dtemiss_mes = $q51_dtemiss[1];
        $q51_dtemiss_ano = $q51_dtemiss[0];
	    	$rsBase          = $clissbase->sql_record($clissbase->sql_query($oNota->q51_inscr));
        if ($clissbase->numrows > 0 ){
					  
						$oBase        = db_utils::fieldsmemory($rsBase,0);
            $z01_nome     = $oBase->z01_nome; 
						$q02_inscr    = $oBase->q02_inscr;
						$z01_cpfcgc   = $oBase->z01_cgccpf;
						$z01_endereco = $oBase->z01_ender;
						$z01_numero   = $oBase->z01_numero;
			 }

       $rsTom = $clissnotaavulsatomador->sql_record($clissnotaavulsatomador->sql_query_tomador($get->q51_sequencial));
			 if ($clissnotaavulsatomador->numrows > 0){
         
				   $oTom = db_utils::fieldsmemory($rsTom,0);
           $q53_nome          = $oTom->z01_nome; 
					 $q54_inscr         = $oTom->q02_inscr;
					 $q61_numcgm        = $oTom->q61_numcgm;
					 $q53_cgccpf        = $oTom->z01_cgccpf;
					 $q53_endereco      = $oTom->z01_ender;
					 $q53_numero        = $oTom->z01_numero;
					 $q53_bairro        = $oTom->z01_bairro;
					 $q53_munic         = $oTom->z01_munic;
					 $q53_uf            = $oTom->z01_uf;
					 $q53_cep           = $oTom->z01_cep;
					 $q53_email         = $oTom->z01_email;
					 $q53_sequencial    = $oTom->q53_sequencial;
					 $q53_fone          = $oTom->z01_telef;
					 $q53_dtservico     = explode("-",$oTom->q53_dtservico);
           $q53_dtservico_dia = $q53_dtservico[2];
           $q53_dtservico_mes = $q53_dtservico[1];
           $q53_dtservico_ano = $q53_dtservico[0];
					 $db_opcao          = 2;

			 }
	 }
}
/*
 *Complemento do cadastro quando o usuario informa 0 cpf/cnpj, ou a inscricao;
 * implementar em ajax?
 */ 
if (isset($post->q54_inscr) and $post->q54_inscr !=''){

	 	$rsInscr  = $clissbase->sql_record($clissbase->sql_query($post->q54_inscr));
    if ($clissbase->numrows > 0 ){
					  
						$oInscr       = db_utils::fieldsmemory($rsInscr,0);
            $q53_nome     = $oInscr->z01_nome; 
						$q54_inscr    = $oInscr->q02_inscr;
						$q53_cgccpf   = $oInscr->z01_cgccpf;
						$q53_endereco = $oInscr->z01_ender;
						$q61_numcgm   = null;;
						$q53_numero   = $oInscr->z01_numero;
						$q53_bairro   = $oInscr->z01_bairro;
						$q53_munic    = $oInscr->z01_munic;
						$q53_uf       = $oInscr->z01_uf;
						$q53_cep      = $oInscr->z01_cep;
						$q53_email    = $oInscr->z01_email;
						$q53_fone     = $oInscr->z01_telef;
			 }
			 
}else if((isset($post->q53_cgccpf) or isset($post->q61_numcgm)) and $post->q54_inscr == ''){
 
    $sWhere = $post->q53_cgccpf==''?"z01_numcgm = ".$post->q61_numcgm:"z01_cgccpf=".$post->q53_cgccpf;
	 	$rsCgm  = $clcgm->sql_record($clcgm->sql_query(null,'*','',$sWhere));
    if ($clcgm->numrows > 0 ){
					  
 			 $oCgm         = db_utils::fieldsmemory($rsCgm,0);
       $q53_nome     = $oCgm->z01_nome; 
			 $q53_cgccpf   = $oCgm->z01_cgccpf;
			 $q61_numcgm   = $oCgm->z01_numcgm;
			 $q54_inscr    = null;
			 $q53_endereco = $oCgm->z01_ender;
			 $q53_numero   = $oCgm->z01_numero;
			 $q53_bairro   = $oCgm->z01_bairro;
			 $q53_munic    = $oCgm->z01_munic;
			 $q53_uf       = $oCgm->z01_uf;
			 $q53_cep      = $oCgm->z01_cep;
			 $q53_email    = $oCgm->z01_email;
			 $q53_fone     = $oCgm->z01_telef;
		}else{

       $q53_nome     = null; 
			 $q53_cgccpf   = null;
			 $q61_numcgm   = null;
			 $q54_inscr    = null;
			 $q53_endereco = null;
			 $q53_numero   = null;
			 $q53_bairro   = null;
			 $q53_munic    = null;
			 $q53_uf       = null;
			 $q53_cep      = null;
			 $q53_email    = null;
			 $q53_fone     = null;
     


    }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
	include("forms/db_frmissnotaavulsatomadoralt.php");
	?>
    </center>
</body>
</html>
<script>
</script>
<?
if(isset($post->incluir)){

  if($lSqlErro){
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clissnotaavulsatomador->erro_campo!=""){
      echo "<script> document.form1.".$clissnotaavulsatomador->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissnotaavulsatomador->erro_campo.".focus();</script>";
    }
		}else{
      if ($erro_msg != ''){
        db_msgbox($erro_msg);
      }
       db_redireciona("iss1_issnotaavulsatomador001.php?q51_sequencial=".$get->q51_sequencial."&liberaaba=true");
   // $clissnotaavulsatomador->erro(true,true);
  }
}
if (isset($get->liberaaba)){

  echo "<script>parent.mo_camada('issnotaavulsaservico');</script>";




}
?>