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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_inssirf_classe.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_funcoes.php");

//$r33_codele = null;
//$o56_descr  = null;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//echo($HTTP_SERVER_VARS["QUERY_STRING"]);

$clinssirf    = new cl_inssirf;
$clrhrubricas = new cl_rhrubricas;
$oDaoRegimePrevidenciaInssirf = new cl_regimeprevidenciainssirf;

/**
 * --------------------------------------------
 * INCLUIR
 * --------------------------------------------
 */
if(isset($incluir)){
	
  if (db_mesfolha() == "") {
    $erro_msg = "Não encontrados parâmetros configurados para esta instituição. [cfpess] ";
  } else {

	  db_inicio_transacao();
	  $sqlerro = false;
	  $codigo  = (int)$codtab;

	  if(!isset($r33_basfer) || (isset($r33_basfer) && trim($r33_basfer) != "")){
	      $r33_basfer = "";
	    if($codigo > 2){
	      $r33_basfer = "B002";
	    }
	  }
	  if(!isset($r33_basfet) || (isset($r33_basfet) && trim($r33_basfet) != "")){
	      $r33_basfet = "";
	    if($codigo > 2){
	      $r33_basfet = "B977";
	    }
	  }
	
	  if($codigo > 2){
	    $r33_deduzi = 0;
	  }else{
	    $r33_tipo = "";
	    $r33_nome = "";
	  }
	
	  $clinssirf->r33_anousu = db_anofolha();
	  $clinssirf->r33_mesusu = db_mesfolha();
	  $clinssirf->r33_codtab = $codtab;
	  $clinssirf->r33_inic   = $r33_inic;
	  $clinssirf->r33_fim    = $r33_fim;
	  $clinssirf->r33_perc   = $r33_perc;
	  $clinssirf->r33_deduzi = $r33_deduzi;
	  $clinssirf->r33_basfer = $r33_basfer;
	  $clinssirf->r33_basfet = $r33_basfet;
	  $clinssirf->r33_instit = db_getsession("DB_instit");
	  $clinssirf->r33_codele = $r33_codele;
	                            
	  $clinssirf->incluir(null,db_getsession("DB_instit"));

	  /**
	   * Na inserção, insere o registro de vinculacao
	   */ 
	  if (!empty($rh129_regimeprevidencia)) {

	  	$oDaoRegimePrevidenciaInssirf->rh129_codigo = $clinssirf->r33_codigo;
	  	$oDaoRegimePrevidenciaInssirf->rh129_instit = $clinssirf->r33_instit;
	  	$oDaoRegimePrevidenciaInssirf->rh129_regimeprevidencia = $rh129_regimeprevidencia;
	  	$oDaoRegimePrevidenciaInssirf->incluir(null);

	  	$rh129_regimeprevidencia = '';
	  	$rh127_descricao         = '';

		}

	  $erro_msg = $clinssirf->erro_msg;
	  if($clinssirf->erro_status=="0"){
	    $sqlerro = true;
	  }
	
	
	  if($sqlerro == false){
	
	    if($codigo > 2){
	      $clinssirf->r33_tipo   = $r33_tipo;
	      $clinssirf->r33_rubmat = $r33_rubmat;
	      $clinssirf->r33_ppatro = $r33_ppatro;
	      $clinssirf->r33_rubsau = $r33_rubsau;
	      $clinssirf->r33_rubaci = $r33_rubaci;
	      $clinssirf->r33_tinati = $r33_tinati;
	      $completa = 0;
	      $ini = (3 * $codigo) - 8;
	      $fim = $ini + 2;
	      for($i=$ini; $i<=$fim; $i++){
	        $completa ++;
	        $descricao = "% ".$r33_nome;
	        if($completa == 1){
	          $descricao.= " S/ SALÁRIO";
	        }else if($completa == 2){
	          $descricao.= " S/ 13o SALÁRIO";
	        }else{
	          $descricao.= " S/ FÉRIAS";
	        }
	        $clrhrubricas->rh27_descr  = substr($descricao,0,30);
	        $clrhrubricas->rh27_rubric = "R9".db_formatar($i,"s","0",2,"e",0);
	        $clrhrubricas->rh27_instit = db_getsession('DB_instit');
	        $clrhrubricas->alterar("R9".db_formatar($i,"s","0",2,"e",0), db_getsession('DB_instit'));
	        if($clrhrubricas->erro_status=="0"){
	          $erro_msg = $clrhrubricas->erro_msg;
	          $sqlerro = true;
	          break;
	        }
	      }
	    }
	
	    if ($sqlerro == false) {

	      $clinssirf->r33_nome   = $r33_nome;
	      $clinssirf->r33_basfer = $r33_basfer;
	      $clinssirf->r33_basfet = $r33_basfet;
	      $result_dadosinssirf = $clinssirf->sql_record($clinssirf->sql_query_file(null,null,"r33_codigo as codigo,r33_anousu,r33_mesusu,r33_codtab,r33_inic,r33_fim,r33_perc,r33_deduzi","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".$codtab."' and r33_instit = ".db_getsession("DB_instit")));
	      $numrows_dadosinssirf = $clinssirf->numrows;

	      for ($i=0; $i<$numrows_dadosinssirf; $i++) {
		      db_fieldsmemory($result_dadosinssirf, $i);
		      $clinssirf->r33_codigo = $codigo;
		      $clinssirf->r33_anousu = $r33_anousu;
	      	$clinssirf->r33_mesusu = $r33_mesusu;
		      $clinssirf->r33_codtab = $r33_codtab;
		      $clinssirf->r33_inic   = $r33_inic;
		      $clinssirf->r33_fim    = $r33_fim;
		      $clinssirf->r33_perc   = $r33_perc;
		      $clinssirf->r33_deduzi = $r33_deduzi;
		      $clinssirf->r33_instit = db_getsession("DB_instit");
					$clinssirf->r33_codele = $r33_codele;
		      $clinssirf->alterar($codigo,db_getsession("DB_instit"),db_anofolha(),db_mesfolha());
		      if ($clinssirf->erro_status=="0") {
		        $erro_msg = $clinssirf->erro_msg;
		        $sqlerro = true;
		        break;
		      }

	      }
	    }

	    
	  }

	  db_fim_transacao($sqlerro);
  }
}

/**
 * --------------------------------------------
 * ALTERAR
 * --------------------------------------------
 */
else if(isset($alterar)){
  if (db_mesfolha() == "") {
    $erro_msg = "Não encontrados parâmetros configurados para esta instituição. [cfpess] ";
  } else {
	  db_inicio_transacao();
	  $sqlerro = false;
	  $codigo  = (int)$codtab;
	
	  if($codigo > 2){
	    $r33_deduzi = 0;
	  }else{
	    $r33_tipo = "";
	    $r33_nome = "";
	  }
	
	  $clinssirf->r33_codigo = $r33_codigo;
	  $clinssirf->r33_anousu = db_anofolha();
	  $clinssirf->r33_mesusu = db_mesfolha();
	  $clinssirf->r33_codtab = $codtab;
	  $clinssirf->r33_inic   = $r33_inic;
	  $clinssirf->r33_fim    = $r33_fim;
	  $clinssirf->r33_perc   = $r33_perc;
	  $clinssirf->r33_deduzi = $r33_deduzi;
	  $clinssirf->r33_instit = db_getsession("DB_instit");
		$clinssirf->r33_codele = $r33_codele;
	
		$clinssirf->alterar($r33_codigo,db_getsession("DB_instit"),db_anofolha(),db_mesfolha());


	  /**
	   * Na alteração, apagas os registros atuais e insere denovo
	   */ 

	  $oDaoRegimePrevidenciaInssirf->excluir(null, "rh129_codigo = " . $r33_codigo . " and rh129_instit = " . db_getsession("DB_instit"));

	  if (!empty($rh129_regimeprevidencia)) {

	  	$oDaoRegimePrevidenciaInssirf->rh129_codigo = $clinssirf->r33_codigo;
	  	$oDaoRegimePrevidenciaInssirf->rh129_instit = $clinssirf->r33_instit;
	  	$oDaoRegimePrevidenciaInssirf->rh129_regimeprevidencia = $rh129_regimeprevidencia;
	  	$oDaoRegimePrevidenciaInssirf->incluir(null);

	  	$rh129_regimeprevidencia = '';
	  	$rh127_descricao         = '';

		}
	
	  $erro_msg = $clinssirf->erro_msg;
	  if($clinssirf->erro_status=="0"){
	    $sqlerro = true;
	  }
	
	  if($codigo > 2 && $sqlerro == false){
	    $completa = 0;
	    $ini = (3 * $codigo) - 8;
	    $fim = $ini + 2;
	    for($i=$ini; $i<=$fim; $i++){
	      $completa ++;
	      $descricao = "%".$r33_nome;
	      if($completa == 1){
		      $descricao.= " S/ SALÁRIO";
	      }else if($completa == 2){
		      $descricao.= " S/13o SALÁRIO";
	      }else{
		      $descricao.= " S/ FÉRIAS";
	      }
	      $clrhrubricas->rh27_descr  = $descricao;
	      $clrhrubricas->rh27_rubric = "R9".db_formatar($i,"s","0",2,"e",0);
	      $clrhrubricas->rh27_instit = db_getsession('DB_instit');
	      $clrhrubricas->alterar("R9".db_formatar($i,"s","0",2,"e",0), db_getsession('DB_instit'));
	      if($clrhrubricas->erro_status=="0"){
		      $erro_msg = $clrhrubricas->erro_msg;
		      $sqlerro = true;
	      	break;
	      }
	    }
	    if ($sqlerro == false) {
	      $clinssirf->r33_nome   = $r33_nome;
	      $clinssirf->r33_tipo   = $r33_tipo;
	      $clinssirf->r33_rubmat = $r33_rubmat;
	      $clinssirf->r33_ppatro = $r33_ppatro;
	      $clinssirf->r33_rubsau = $r33_rubsau;
	      $clinssirf->r33_basfer = $r33_basfer;
	      $clinssirf->r33_basfet = $r33_basfet;
	      $clinssirf->r33_rubaci = $r33_rubaci;
	      $clinssirf->r33_tinati = $r33_tinati;
	      $result_dadosinssirf = $clinssirf->sql_record($clinssirf->sql_query_file(null,null,"r33_codigo as codigo,r33_anousu,r33_mesusu,r33_codtab,r33_inic,r33_fim,r33_perc,r33_deduzi","","r33_instit = ".db_getsession("DB_instit")." and r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".$codtab."'"));
	      $numrows_dadosinssirf = $clinssirf->numrows;
	      for ($i=0; $i<$numrows_dadosinssirf; $i++) {
		      db_fieldsmemory($result_dadosinssirf, $i);
		      $clinssirf->r33_codigo = $codigo;
		      $clinssirf->r33_anousu = $r33_anousu;
		      $clinssirf->r33_mesusu = $r33_mesusu;
		      $clinssirf->r33_codtab = $r33_codtab;
		      $clinssirf->r33_inic   = $r33_inic;
		      $clinssirf->r33_fim    = $r33_fim;
		      $clinssirf->r33_perc   = $r33_perc;
		      $clinssirf->r33_deduzi = $r33_deduzi;
		      $clinssirf->r33_instit = db_getsession("DB_instit");
					$clinssirf->r33_codele = $r33_codele;
		      $clinssirf->alterar($codigo,db_getsession("DB_instit"),db_anofolha(),db_mesfolha());
		      if ($clinssirf->erro_status=="0") {
		        $erro_msg = $clinssirf->erro_msg;
		        $sqlerro = true;
		        break;
		      }
	      }
	    }
	  }
	
	  if ($sqlerro == false){
	    unset($r33_codigo);
	  }
	  
	  db_fim_transacao($sqlerro);
  }
}

/**
 * --------------------------------------------
 * EXCLUIR
 * --------------------------------------------
 */
else if(isset($excluir)){
  if (db_mesfolha() == "") {
    $erro_msg = "Não encontrados parâmetros configurados para esta instituição. [cfpess] ";
  } else {	
	  db_inicio_transacao();
	  $sqlerro = false;
	  $clinssirf->r33_instit = db_getsession("DB_instit")	;

	  /**
	   * Na exclusão, apagas os registros atuais
	   */ 
	  $oDaoRegimePrevidenciaInssirf->excluir(null, "rh129_codigo = " . $r33_codigo . " and rh129_instit = " . db_getsession("DB_instit"));

	  $clinssirf->excluir($r33_codigo,db_getsession("DB_instit"));
	  $erro_msg = $clinssirf->erro_msg;
	  if($clinssirf->erro_status=="0"){
	    $sqlerro = true;
	  }
	  if ($sqlerro == false){
	    unset($r33_codigo);
	  }
	  db_fim_transacao($sqlerro);
  }
}else if(isset($r33_codigo) && trim($r33_codigo) != ""){
  $dbwhere = "r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codigo = $r33_codigo and r33_instit = ".db_getsession("DB_instit")." and r33_codtab = '$codtab'";
  $result = $clinssirf->sql_record($clinssirf->sql_query_file(null,null,"r33_codigo,r33_codtab as codtab,round(r33_inic,2) as r33_inic,round(r33_fim,2) as r33_fim,round(r33_perc,2) as r33_perc,round(r33_deduzi,2) as r33_deduzi,r33_nome",null,$dbwhere)); 
  db_fieldsmemory($result,0);
  $db_botao = true;

  /**
   * Busca os dados do regimeprevidencia inssirf
   */
  $rh129_regimeprevidencia    = '';
	$rh127_descricao            = ''; 
  $sWhereGetDados             = " rh129_codigo = $r33_codigo and rh129_instit = ".db_getsession("DB_instit");
  $sSqlRegime                 = $oDaoRegimePrevidenciaInssirf->sql_query(null, "rh129_regimeprevidencia, rh127_descricao", null, $sWhereGetDados);
  $rsRegimePrevidenciaInssirf = $oDaoRegimePrevidenciaInssirf->sql_record($sSqlRegime);

  if ($oDaoRegimePrevidenciaInssirf->numrows > 0) {

    $oDadosRegime             = db_utils::fieldsMemory($rsRegimePrevidenciaInssirf,0);
    $rh129_regimeprevidencia  = $oDadosRegime->rh129_regimeprevidencia;
    $rh127_descricao          = $oDadosRegime->rh127_descricao;
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frminssirf001.php");
      ?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro == true){
    if($clinssirf->erro_campo!=""){
      echo "<script> document.form1.".$clinssirf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clinssirf->erro_campo.".focus();</script>";
    }
  }else{
    echo "<script>location.href='pes1_inssirf002.php?codtab=$codtab'</script>";
  }
}
?>
<script>
js_setar_foco();
</script>