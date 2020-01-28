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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tabrec_classe.php");
require_once("classes/db_tabrectipo_classe.php");
require_once("classes/db_tabrecregrasjm_classe.php");
require_once("classes/db_taborc_classe.php");
require_once("classes/db_tabplan_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("classes/db_tabrecarretipo_classe.php");
require_once("classes/db_tabrecdepto_classe.php");
require_once("dbforms/db_funcoes.php");

$cltabrec         = new cl_tabrec;
$cltabrecregrasjm = new cl_tabrecregrasjm;
$cltaborc         = new cl_taborc;
$cltabplan        = new cl_tabplan;
$clnumpref        = new cl_numpref;
$cltabrecarretipo = new cl_tabrecarretipo;
$cltabtiporec     = new cl_tabrectipo;
$cltabrecdepto    = new cl_tabrecdepto;

$db_opcao = 33;
$db_botao = false;
$anousu   = db_getsession("DB_anousu");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

if (isset($excluir)) {
	
  $sqlerro=false;
  db_inicio_transacao();

  $cltabplan->k02_anousu = $anousu;
  $cltabplan->k02_codigo = $k02_codigo;
  $cltabplan->excluir($k02_codigo,$anousu);
  if($cltabplan->erro_status==0){
    $cltabrecregrasjm->incluir(null);
    if($cltabrecregrasjm->erro_status == 0){
      $erro_msg = $cltabrecregrasjm->erro_msg;
      $sqlerro = true;
    }
    $sqlerro = true;
    $erro_msg = $cltabplan->erro_msg;
  }

  if($sqlerro == false){
    $cltaborc->k02_anousu = $anousu;
    $cltaborc->k02_codigo = $k02_codigo;
    $cltaborc->excluir($anousu,$k02_codigo);
    if($cltaborc->erro_status == 0){
      $sqlerro = true;
      $erro_msg = $cltaborc->erro_msg;
    }
  }

  if($sqlerro == false){
    $cltabrecregrasjm->excluir(null,"k04_receit = ".$k02_codigo);
    if($cltabrecregrasjm->erro_status == 0){
      $erro_msg = $cltabrecregrasjm->erro_msg;
      $sqlerro = true;
    }
  }
	if($sqlerro == false){
		$cltabrecarretipo-> k79_receit   = $k02_codigo;
		$cltabrecarretipo->excluir(null," k79_receit= $k02_codigo ");
		if($cltabrecarretipo->erro_status="0"){
	  	$erro = true;
			$msgerro = $cltabrecarretipo->erro_msg;
	  } 
  }
	if($sqlerro == false){
		$cltabrecdepto->k98_receit  = $k02_codigo;
		$cltabrecdepto->excluir(null, " k98_receit = $k02_codigo ");
		if($cltabrecdepto->erro_status="0"){
	  	    $erro = true;
			    $msgerro = $cltabrecdepto->erro_msg;
		}
	}
  if($sqlerro == false){
    $cltabrec->k02_codigo = $k02_codigo;
    $cltabrec->excluir($k02_codigo);
    $erro_msg = $cltabrec->erro_msg;
    if($cltabrec->erro_status == 0){
      $sqlerro = true;
    }
  }
  
  db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)){
  $db_botao = true;
  $db_opcao = 3;
  $sCampos    = "*,tabrec.k02_recjur as recjurerecmul,tabrec.k02_drecei as descr_juremul";
  $sSqltabRec = $cltabrec->sql_query($chavepesquisa,$sCampos,null,"");
  $result     = $cltabrec->sql_record($sSqltabRec);
  db_fieldsmemory($result,0);
     
  $result = $cltaborc->sql_record($cltaborc->sql_query_file($anousu,$k02_codigo,"k02_codrec as codigo,k02_estorc as estrut"));
  if($cltaborc->numrows>0){
    db_fieldsmemory($result,0);
  }

  $result = $cltabplan->sql_record($cltabplan->sql_query_file($k02_codigo,$anousu,"k02_reduz as codigo,k02_estpla as estrut"));
  if($cltabplan->numrows>0){
    db_fieldsmemory($result,0);
  }  
		$sqlarretipo = "select k79_arretipo,k00_descr 
		                from tabrecarretipo 
		                inner join arretipo on k00_tipo = k79_arretipo
										where k79_receit = $chavepesquisa";
    $resultarretipo= pg_query($sqlarretipo);
		$linhasarretipo = pg_num_rows($resultarretipo);
		if($linhasarretipo > 0){
			db_fieldsmemory($resultarretipo,0);
		}
		
    $sCampos             = "juros.k02_descr as descr_jur,multa.k02_descr as descr_mul";
    $sSqlTabRecJurRecMul = $cltabrec->sql_query_recjur_recmul($chavepesquisa,$sCampos,null,"");
    $result              = $cltabrec->sql_record($sSqlTabRecJurRecMul);   
    
    if (  pg_num_rows($result) > 0 ) {
      db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_onload();">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <?
      include("forms/db_frmtabrec.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
}
if(isset($chavepesquisa) || isset($excluir)){
  echo "
        <script>
          function js_db_libera(){
          	
						parent.document.formaba.tabrec.disabled=false;
						parent.document.formaba.tabrecregrasjm.disabled=false;
						parent.document.formaba.tabrecdepto.disabled=false;
            top.corpo.iframe_tabrecregrasjm.location.href='cai1_receitaregrasjm001.php?k04_receit=".@$k02_codigo."&db_opcaoal=true';
						top.corpo.iframe_tabrecdepto.location.href='cai1_tabrec_depto001.php?k98_receit=".@$k02_codigo."&k02_descr=$k02_descr&db_opcao=3';
           
          }\n
          js_db_libera();
        </script>\n
       ";
}
if($db_opcao == 33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>