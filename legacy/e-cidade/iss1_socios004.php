<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_socios_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/logSocios.model.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_meicgm_classe.php");
require_once("classes/db_meiprocessaregmeicgm_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clsocios               = new cl_socios;
$clissbase              = new cl_issbase;
$clcgm                  = new cl_cgm;
$cllogsocios            = new logsocios;
$clmeicgm               = new cl_meicgm; 
$clmeiprocessaregmeicgm = new cl_meiprocessaregmeicgm;
$iSeqMeiImportado = 0;
$db_botao = true;
$MeiExisteArquivo = false;


/*
 * verificamos se o cgmpri, esta cadastrado na tabela meiprocessaregmeicgm,
 * se tiver, significa q o mei foi cadastrado mediante importação de arquivo, 
 * sendo assim o sistema nao permitira incluir alterar ou excluir socios.
 */
$sSqlMeiImportado = $clmeicgm->sql_query_file(null, "q115_sequencial", null, "q115_numcgm = {$q95_cgmpri}");
$rsMeiImportado   = $clmeicgm->sql_record($sSqlMeiImportado);
if ($clmeicgm->numrows > 0) {
  
  db_fieldsmemory($rsMeiImportado,0);
  $iSeqMeiImportado = $q115_sequencial;
}
if ($iSeqMeiImportado != 0) {

  $sSqlExisteMeiImportado = $clmeiprocessaregmeicgm->sql_query_file(null,"*",null,"q118_meicgm = {$iSeqMeiImportado} ");
  $rsExisteMeiImportado   = $clmeiprocessaregmeicgm->sql_record($sSqlExisteMeiImportado);
  if ($clmeiprocessaregmeicgm->numrows > 0) {
    
    $sqlerro  = true;
    $sMsgErro = "Mei cadastrado mediante importação de arquivo. Imposivel realizar a Operação";
    $MeiExisteArquivo = true;
  }
  
}

if(isset($incluir)){
	
	if ($MeiExisteArquivo == false) {
  
		$sqlerro = false;
	  db_inicio_transacao();
	// verifica se ja existe socio mei.
	  $sSqlSocioExiste = $clsocios->sql_query_file("", "", "*","", "q95_cgmpri = {$q95_cgmpri} and q95_tipo = 2");
	  $rsSocioExiste   = $clsocios->sql_record($sSqlSocioExiste);
	  if( $clsocios->numrows > 0 && $q95_tipo == 2){
	  	$clsocios->erro_status = "0";
	  	$erromsg = "Responsavel MEI ja existente";
	  	$clsocios->erro_msg = $erromsg;
	  	$sqlerro = true;
  } else {
  
  
	  $result_inscr = $clissbase->sql_record($clissbase->sql_query_file(null,'q02_inscr',null,"q02_numcgm=$q95_cgmpri"));
	  
	  if ($clissbase->numrows != 0) {
	  	
	    for ($y = 0; $y < $clissbase->numrows; $y++) {
	    	
	      $regissbase = $clissbase->numrows;
	      db_fieldsmemory($result_inscr,$y);
	      
	      if ($sqlerro == false) {
	      	
					if ($somaval != "" && $somaval != 0) {
						
					  $clissbase->q02_inscr=$q02_inscr;
					  $clissbase->q02_capit=$somaval+$q95_perc;
					  $clissbase->alterar($q02_inscr);
					  $erromsg=$clissbase->erro_msg;
					  if ($clissbase->erro_status == 0) {
					    $sqlerro=true;
					  }
					}
	      }
	    }
	  }
	  
	  $clsocios->q95_cgmpri = $q95_cgmpri;
	  $clsocios->q95_numcgm = $q95_numcgm;
	  $clsocios->q95_tipo   = $q95_tipo;
	  $clsocios->q95_perc   = $q95_perc;
	  $clsocios->incluir($q95_cgmpri,$q95_numcgm);
	  if ($clsocios->erro_status == 0) {
	     $sqlerro = true;
	  } else {
	  	
	    $q95_numcgm     = "";
	    $z01_nome_socio = "";
	    $q95_perc       = "";
	  }  
	  // se o tipo for responsavel MEI, incluimos o CGMPRI na tabela meicgm
	  if ($q95_tipo == 2) {
		  $clmeicgm->q115_meisitucao  = 1;
		  $clmeicgm->q115_numcgm      = $q95_cgmpri;
		  $clmeicgm->incluir(null);
		  if ($clmeicgm->erro_status == 0) {
		     $sqlerro = true;
		  }
	  }    
	  
	  if (!$sqlerro) {
	  	
	  	if ($clissbase->numrows != 0) {
	  		
		  	try {
		
		      if (isset($clsocios->q95_numcgm) && !empty($clsocios->q95_numcgm)) {
		        $cllogsocios->identificaAlteracao($q02_inscr,1,3,$clsocios->q95_numcgm);        
		        $cllogsocios->gravarLog();
		      }
		    
		    } catch ( Exception $eExeption ){
		      
		      $sqlerro  = true;
		      $sMsgErro = $eExeption->getMessage();
		    }
	  	}
	  }
  } 
  db_fim_transacao($sqlerro);
	}
}else if(isset($alterar)){
	
	if ($MeiExisteArquivo == false) {
		
		$sqlerro=false;
	  db_inicio_transacao();
	  $sSqlSocioExiste = $clsocios->sql_query_file("", "", "*","", "q95_cgmpri = {$q95_cgmpri} and q95_tipo = 2");
	  $rsSocioExiste   = $clsocios->sql_record($sSqlSocioExiste);
	  if( $clsocios->numrows > 0 && $q95_tipo == 2){
	    $clsocios->erro_status = "0";
	    $erromsg = "Responsavel MEI ja existente";
	    $clsocios->erro_msg = $erromsg;
	    $sqlerro = true;
	  } else {  
	  $result_perc = $clsocios->sql_record($clsocios->sql_query_file($q95_cgmpri,$q95_numcgm,'q95_perc as perc'));
	  if ($clsocios->numrows!=0){
	    db_fieldsmemory($result_perc,0);
	    $soma= $somaval-$perc ;
	  }
	  $result_inscr = $clissbase->sql_record($clissbase->sql_query_file(null,'q02_inscr',null,"q02_numcgm=$q95_cgmpri"));
	  if ($clissbase->numrows!=0){
	    $regissbase =$clissbase->numrows;
	    for($y=0;$y<$regissbase;$y++){
	      db_fieldsmemory($result_inscr,$y);
	      if ($sqlerro==false){
		if ($somaval!=""&&$somaval!=0){
		  $clissbase->q02_inscr=$q02_inscr;
		  $clissbase->q02_capit=$soma+$q95_perc;
		  $clissbase->alterar($q02_inscr);
		  $erromsg=$clissbase->erro_msg;
		  if($clissbase->erro_status==0){
		    $sqlerro=true;
		  }
		}
	      }
	    }
	  }
	  
	  $clsocios->q95_cgmpri=$q95_cgmpri;
	  $clsocios->q95_numcgm=$q95_numcgm;
	  $clsocios->q95_perc=$q95_perc;
	  $clsocios->alterar($q95_cgmpri,$q95_numcgm);
	  if($clsocios->erro_status==0){
	     $sqlerro=true;
	  }else{
	    $q95_numcgm="";
	    $z01_nome_socio="";
	    $q95_perc="";
	  }
	  
	  if ($q95_tipo != 2) {
	    $clmeicgm->excluir(null,"q115_numcgm = {$q95_cgmpri} ");
	    if ($clmeicgm->erro_status == 0) {
	       $sqlerro = true;
	    }
	  }   
	  if ($q95_tipo == 2) {
	    $clmeicgm->q115_meisitucao  = 1;
	    $clmeicgm->q115_numcgm      = $q95_cgmpri;
	    $clmeicgm->incluir(null);
	    if ($clmeicgm->erro_status == 0) {
	       $sqlerro = true;
	    }
	  } 
	  if (!$sqlerro) {
	    
	  	if ($clissbase->numrows != 0) {
	  		
		  	try {
		    
		      if (isset($clsocios->q95_numcgm) && !empty($clsocios->q95_numcgm)) {
		        $cllogsocios->identificaAlteracao($q02_inscr,2,4,$clsocios->q95_numcgm,$clsocios->q95_perc,$perc);        
		        $cllogsocios->gravarLog();
		      }
		    
		    } catch ( Exception $eExeption ){
		      
		      $sqlerro  = true;
		      $sMsgErro = $eExeption->getMessage();
		    }
	  	}
	  }
	  } 
	  db_fim_transacao($sqlerro);
	}
} elseif(isset($excluir)) {
	
	if ($MeiExisteArquivo == false) {
		
	  $sqlerro=false;
	  db_inicio_transacao();
	  $result_inscr = $clissbase->sql_record($clissbase->sql_query_file(null,'q02_inscr',null,"q02_numcgm=$q95_cgmpri"));
	  if ($clissbase->numrows!=0){
	    $regissbase =$clissbase->numrows;
	    for($y=0;$y<$regissbase;$y++){
	      db_fieldsmemory($result_inscr,$y);
	      if ($sqlerro==false){
		if ($somaval!=""&&$somaval!=0){
		  $clissbase->q02_inscr=$q02_inscr;
		  $clissbase->q02_capit=$somaval-$q95_perc;
		  $clissbase->alterar($q02_inscr);
		  $erromsg=$clissbase->erro_msg;
		  if($clissbase->erro_status==0){
		    $sqlerro=true;
		  }
		}
	      }
	    }
	  }
	  $clsocios->q95_cgmpri=$q95_cgmpri;
	  $clsocios->q95_numcgm=$q95_numcgm;
	  $clsocios->excluir($q95_cgmpri,$q95_numcgm);
	  if($clsocios->erro_status==0){
	     $sqlerro=true;
	  }else{
	    $q95_numcgm="";
	    $z01_nome_socio="";
	    $q95_perc="";
	  }
	  $clmeicgm->excluir(null,"q115_numcgm = {$q95_cgmpri} ");
	  if ($clmeicgm->erro_status == 0) {
	     $sqlerro = true;
	  }  
	  if (!$sqlerro) {
	  	
	  	if ($clissbase->numrows != 0) {
	  		
		  	try {
		    
		      if (isset($clsocios->q95_numcgm) && !empty($clsocios->q95_numcgm)) {
		    
		        $cllogsocios->identificaAlteracao($q02_inscr,3,3,$clsocios->q95_numcgm);        
		        $cllogsocios->gravarLog();
		      }
		    
		    } catch ( Exception $eExeption ) {
		      
		      $sqlerro  = true;
		      $sMsgErro = $eExeption->getMessage();
		    }
	  	}
	  }
	  
	  db_fim_transacao($sqlerro);
	}
}
if(isset($db_opcaoal)){
  $db_opcao=3;
  $db_botao=false;
}
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad='js_atualiza_abainscr(); js_tipoPessoa();' >
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmsociosalt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
function js_atualiza_abainscr(){
  parent.iframe_issbase.document.form1.submit();
}

</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
	
	if (isset($sqlerro)) {
		
		if (isset($sMsgErro) && !empty($sMsgErro)) {
			db_msgbox($sMsgErro);
		}
	}
	
  if($clsocios->erro_status=="0"){
    $clsocios->erro(true,false);
    $db_botao=true;
    if($clsocios->erro_campo!=""){
      echo "<script> document.form1.".$clsocios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsocios->erro_campo.".focus();</script>";
    }
  }else{
    $clsocios->erro(true,false);
  }
}  
?>