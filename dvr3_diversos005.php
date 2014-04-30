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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

require_once("classes/db_diversos_classe.php");
require_once("classes/db_procdiver_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_arreinscr_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_inflan_classe.php");

require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cldiversos = new cl_diversos;
$clinflan = new cl_inflan;
$clprocdiver= new cl_procdiver;
$clcgm= new cl_cgm;
$cliptubase= new cl_iptubase;
$clissbase= new cl_issbase;
$clarrematric= new cl_arrematric;
$clarreinscr= new cl_arreinscr;
$db_opcao = 1;
$db_botao = true;
if(isset($z_numcgm) && $z_numcgm!=""){
  $z01_numcgm=$z_numcgm;
  $result04=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
  if($clcgm->numrows>0){
    $dv05_numcgm=$z01_numcgm;
    db_fieldsmemory($result04,0);
  }else{
     db_redireciona("dvr3_diversos004.php?dado=numcgm");      
     exit;
  }
}else if(isset($j01_matric) && $j01_matric!=""){
  $result05=$cliptubase->sql_record($cliptubase->sql_query($j01_matric,"j01_numcgm,z01_nome"));
  if($cliptubase->numrows>0){
    db_fieldsmemory($result05,0);
    $dv05_numcgm=$j01_numcgm;
    $tipo="matric"; 
    $valor=$j01_matric;
  }else{
     db_redireciona("dvr3_diversos004.php?dado=matric");      
     exit;
  }
}else if(isset($q02_inscr) && $q02_inscr!=""){
  $result06=$clissbase->sql_record($clissbase->sql_query($q02_inscr,"q02_numcgm,z01_nome"));
  if($clissbase->numrows>0){
    db_fieldsmemory($result06,0);
    $dv05_numcgm=$q02_numcgm;
    $tipo="inscr"; 
    $valor=$q02_inscr;
  }else{
     db_redireciona("dvr3_diversos004.php?dado=inscr");      
     exit;
  }
}
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $sqlerro=false;
  $result06=db_query("select nextval('numpref_k03_numpre_seq')");
  db_fieldsmemory($result06,0);
  if($dv05_numtot=="1"){
    $HTTP_POST_VARS["dv05_diaprox"]=$dv05_privenc_dia;
    $HTTP_POST_VARS["dv05_provenc"]=$dv05_privenc_ano.$dv05_privenc_mes.$dv05_privenc_dia;
    $cldiversos->dv05_provenc=$dv05_privenc_ano.$dv05_privenc_mes.$dv05_privenc_dia;
    $cldiversos->dv05_provenc_dia=$dv05_privenc_dia;
    $cldiversos->dv05_provenc_mes=$dv05_privenc_mes;
    $cldiversos->dv05_provenc_ano=$dv05_privenc_ano;
    $cldiversos->dv05_diaprox=$dv05_privenc_dia;
  }
  $cldiversos->dv05_numpre=$nextval; 
  $cldiversos->dv05_instit = db_getsession('DB_instit');
  $cldiversos->incluir($dv05_coddiver);
  if($cldiversos->erro_status=='0'){
     $sqlerro=true;
  }
  if($tipo=="matric"){
    $clarrematric->k00_numpre=$nextval;  
    $clarrematric->k00_matric=$valor;  
    $clarrematric->k00_perc=100;  
    $clarrematric->incluir($nextval,$valor);  
    if($clarrematric->erro_status=='0'){
      $sqlerro=true;
    }
  }
  if($tipo=="inscr"){
    $clarreinscr->k00_numpre=$nextval;  
    $clarreinscr->k00_inscr=$valor;  
    $clarreinscr->k00_perc=100;  
    $clarreinscr->incluir($nextval,$valor);  
    if($clarreinscr->erro_status=='0'){
      $sqlerro=true;
    }
  }
  
	$sqlArretipo = " select dv09_tipo as arretipo from procdiver where dv09_procdiver = $dv05_procdiver and dv09_instit = ".db_getsession('DB_instit') ;
	$rsArretipo  = db_query($sqlArretipo);
	if (pg_num_rows($rsArretipo) > 0 ){
		db_fieldsmemory($rsArretipo,0);		
	}else{
		db_msgbox(_M("tributario.diversos.db_frmdiversosalt.configure_tipo_debitos_destino"));
		db_redireciona('dvr3_diversos005.php');
		exit;
	}

  // $result09 = db_query("select fc_geraarrecad($arretipo,$nextval,true) as retorno");

  $result09 = db_query("select fc_geraarrecad(7,$nextval,true,2) as retorno");
  if (pg_num_rows($result09) > 0 ) {
    db_fieldsmemory($result09,0);
    $iRetorno = substr(trim($retorno),0,1);
    if ($iRetorno != '9') {
      $cldiversos->erro_msg = $retorno;
      $sqlerro=true;
    }    
  }else{
    $cldiversos->erro_msg = _M("tributario.diversos.db_frmdiversosalt.erro_geracao_diverso");
    $sqlerro=true;
  }

  db_fim_transacao($sqlerro);

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

	<?
	include("forms/db_frmdiversosalt.php");
	?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]))){
if($cldiversos->erro_status=="0"){
  $cldiversos->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cldiversos->erro_campo!=""){
    echo "<script> document.form1.".$cldiversos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldiversos->erro_campo.".focus();</script>";
    };
}else{
  $cldiversos->erro(true,false);
  db_redireciona("dvr3_diversos004.php");
};
}
?>