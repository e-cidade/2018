<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_tabrec_classe.php"));
require_once(modification("classes/db_tabrectipo_classe.php"));
require_once(modification("classes/db_tabrecregrasjm_classe.php"));
require_once(modification("classes/db_taborc_classe.php"));
require_once(modification("classes/db_tabplan_classe.php"));
require_once(modification("classes/db_numpref_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_tabrecarretipo_classe.php"));

$cltabrec         = new cl_tabrec;
$cltabtiporec     = new cl_tabrectipo;
$cltabrecregrasjm = new cl_tabrecregrasjm;
$cltaborc         = new cl_taborc;
$cltabplan        = new cl_tabplan;
$clnumpref        = new cl_numpref;
$cltabrecarretipo = new cl_tabrecarretipo;

$db_opcao = 22;
$db_botao = false;
$anousu   = db_getsession("DB_anousu");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$encontroureceita = true;
if (isset($alterar)) {
  db_inicio_transacao();
  $db_opcao = 2;
  $db_botao = true;
  $sqlerro  = false;

  $cltabplan->k02_anousu = $anousu;
  $cltabplan->k02_codigo = $k02_codigo;
  $cltabplan->excluir($k02_codigo,$anousu);
  if($cltabplan->erro_status == 0){
    $sqlerro = true;
    $erro_msg = $cltabplan->erro_msg;
  }

  if($sqlerro == false){
    $result_tabrecregrasjm = $cltabrecregrasjm->sql_record($cltabrecregrasjm->sql_query_file(null,"*","","k04_receit = ".$k02_codigo));
    if($cltabrecregrasjm->numrows == 0){
      $cltabrecregrasjm->k04_receit = $k02_codigo;
      $cltabrecregrasjm->k04_codjm  = $k02_codjm;
      $cltabrecregrasjm->k04_dtini  = "1900-01-01";
      $cltabrecregrasjm->k04_dtfim  = "2099-12-31";
      $cltabrecregrasjm->incluir(null);
      if($cltabrecregrasjm->erro_status == 0){
        $erro_msg = $cltabrecregrasjm->erro_msg;
        $sqlerro = true;
      }
    }
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
    $cltabrec->alterar($k02_codigo);
    $erro_msg = $cltabrec->erro_msg;
    if($cltabrec->erro_status == 0){
      $sqlerro = true;
    }
  }

  if($sqlerro == false){
    if($k02_tipo == "O"){
      $cltaborc->k02_codigo = $k02_codigo;
      $cltaborc->k02_anousu = $anousu;
      $cltaborc->k02_estorc = $estrut;
      $cltaborc->k02_codrec = $codigo;
      $cltaborc->incluir($anousu,$k02_codigo);
      if($cltaborc->erro_status == 0){
        $sqlerro = true;
        $erro_msg = $cltaborc->erro_msg;
      }
    }
  }

  if($sqlerro == false){
    if($k02_tipo == "E"){
      $cltabplan->k02_codigo = $k02_codigo;
      $cltabplan->k02_anousu = db_getsession("DB_anousu");
      $cltabplan->k02_reduz  = $codigo;
      $cltabplan->k02_estpla = $estrut;
      $cltabplan->incluir($k02_codigo,$anousu);
      if($cltabplan->erro_status == 0){
        $sqlerro = true;
        $erro_msg = $cltabplan->erro_msg;
      }
    }
  }

	$cltabrecarretipo-> k79_receit   = $k02_codigo;
	$cltabrecarretipo->excluir(null," k79_receit= $k02_codigo ");
	if($cltabrecarretipo->erro_status="0"){
  	$erro = true;
		$msgerro = $cltabrecarretipo->erro_msg;
  }
	if($sqlerro == false and $k79_arretipo != ""){
		$cltabrecarretipo-> k79_receit   = $k02_codigo;
		$cltabrecarretipo-> k79_arretipo = $k79_arretipo;
		$cltabrecarretipo->incluir(null);
		if($cltabrecarretipo->erro_status="0"){
  	  $erro = true;
		  $msgerro = $cltabrecarretipo->erro_msg;
    }
	}

  db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)) {

  $db_opcao   = 2;
  $db_botao   = true;
  $sCampos    = "*,tabrec.k02_recjur as recjurerecmul,tabrec.k02_drecei as descr_juremul";
  $sSqltabRec = $cltabrec->sql_query($chavepesquisa,$sCampos,null,"");
  $result     = $cltabrec->sql_record($sSqltabRec);
  if($cltabrec->numrows == 0) {
    $encontroureceita = false;
    $db_opcao = 22;
    $db_botao = false;
  } else {
    db_fieldsmemory($result,0);

    $result = $cltaborc->sql_record($cltaborc->sql_query($anousu,$k02_codigo,"k02_codrec as codigo,k02_estorc as estrut"));
    if($cltaborc->numrows > 0){
      db_fieldsmemory($result,0);
    }

    $result = $cltabplan->sql_record($cltabplan->sql_query_file($k02_codigo,$anousu,"k02_reduz as codigo,k02_estpla as estrut"));
    if($cltabplan->numrows > 0){
      db_fieldsmemory($result,0);
    }

		$sqlarretipo = "select k79_arretipo,k00_descr
		                from tabrecarretipo
		                inner join arretipo on k00_tipo = k79_arretipo
										where k79_receit = $chavepesquisa";
    $resultarretipo= db_query($sqlarretipo);
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
      include(modification("forms/db_frmtabrec.php"));
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  db_msgbox($erro_msg);
  if($sqlerro == true){
    if($cltabrec->erro_campo!=""){
      echo "<script> document.form1.".$cltabrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltabrec->erro_campo.".focus();</script>";
    };
  }
}
if((isset($chavepesquisa) || $encontroureceita == true) || (isset($alterar) && $sqlerro == false)){

  if(!isset($opcao)){
  	$opcao=2;
  }
  echo "
        <script>
          function js_db_libera(){
            parent.document.formaba.tabrec.disabled=false;
						parent.document.formaba.tabrecregrasjm.disabled=false;
						parent.document.formaba.tabrecdepto.disabled=false;
            (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrecregrasjm.location.href='cai1_receitaregrasjm001.php?k04_receit=".@$k02_codigo."';
						(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrecdepto.location.href='cai1_tabrec_depto001.php?db_opcao=$opcao&k98_receit=".@$k02_codigo."&k02_descr=".@$k02_descr."';
       ";
						if ($k02_tipo == "E") {

						  echo "(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabplansaldo.location.href='cai4_tabplansaldos001.php?k111_tabplan=".@$k02_codigo."';\n
						  parent.document.formaba.tabplansaldo.disabled=false;\n";
						}
  if(isset($liberaaba) || isset($alterar)){
    echo "
            parent.mo_camada('tabrecregrasjm');
         ";
  }
  echo "
          }\n
          js_db_libera();
        </script>\n
       ";
}
if($encontroureceita == false){
  db_msgbox("Problema ao buscar dados da receita! Contate suporte!");
}
if($db_opcao == 22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>