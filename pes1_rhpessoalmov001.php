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
require_once("libs/db_utils.php");
require_once("classes/db_rhpessoalmov_classe.php");
require_once("classes/db_rhpesrescisao_classe.php");
require_once("classes/db_rhpespadrao_classe.php");
require_once("classes/db_rhpesbanco_classe.php");
require_once("classes/db_rescisao_classe.php");
require_once("classes/db_rhregime_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("classes/db_rhtipoapos_classe.php");
require_once("classes/db_inssirf_classe.php");
require_once("classes/db_rhfuncao_classe.php");
require_once("classes/db_rhpescargo_classe.php");
require_once("classes/db_rhpesorigem_classe.php");
require_once("classes/db_rhpeslocaltrab_classe.php");
require_once("classes/db_rhlocaltrab_classe.php");
require_once("classes/db_pontofs_classe.php");
require_once("classes/db_pontofx_classe.php");
require_once("classes/db_rhpesprop_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrhpessoalmov    = new cl_rhpessoalmov;
$clrhtipoapos      = new cl_rhtipoapos;
$clrhpesrescisao   = new cl_rhpesrescisao;
$clrescisao        = new cl_rescisao;
$clrhpesbanco      = new cl_rhpesbanco;
$clrhpespadrao     = new cl_rhpespadrao;
$clrhpessoal       = new cl_rhpessoal;
$clinssirf         = new cl_inssirf;
$clrhpescargo      = new cl_rhpescargo;
$clrhregime        = new cl_rhregime;
$clrhpesorigem     = new cl_rhpesorigem;
$clrhpeslocaltrab  = new cl_rhpeslocaltrab;
$clrhlocaltrab     = new cl_rhlocaltrab;
$clpontofs         = new cl_pontofs;
$clpontofx         = new cl_pontofx;
$clrhpesprop       = new cl_rhpesprop;
$clrhfuncao        = new cl_rhfuncao;
$clpensao          = new cl_pensao;

$db_opcao  = 22;
$db_botao  = false;
$sDisabled = "";

if(isset($incluir)){
  db_inicio_transacao();
  
  $sqlerro = false;
  
  $clrhpessoalmov->rh02_funcao = $rh02_funcao;
  $clrhpessoalmov->rh02_instit = db_getsession("DB_instit");
  $clrhpessoalmov->rh02_equip  = "false";
  $clrhpessoalmov->incluir(null,db_getsession("DB_instit"));
  $rh02_seqpes    = $clrhpessoalmov->rh02_seqpes; 
  $erro_msg       = $clrhpessoalmov->erro_msg;
  if($clrhpessoalmov->erro_status==0){
    $sqlerro=true;
  }
  
  if ($sqlerro == false) {
  	
	  $clrhpessoal->rh01_funcao = $rh02_funcao;
	  $clrhpessoal->rh01_regist = $oPost->rh02_regist;
	  $clrhpessoal->alterar($oPost->rh02_regist);
	  if ($clrhpessoal->erro_status == 0) {
	    $sqlerro = true;
	  }	
  }

  if($sqlerro == false){
    if(trim($rh21_regpri)!=""){
      $clrhpesorigem->incluir($rh02_regist);
      if($clrhpesorigem->erro_status==0){
        $erro_msg = $clrhpesorigem->erro_msg;
        $sqlerro=true;
      }
    }
  }
  
  if($sqlerro==false){
    if(trim($rh44_codban) != ""){      	
      $clrhpesbanco->incluir($rh02_seqpes);
      if($clrhpesbanco->erro_status==0){
        $erro_msg = $clrhpesbanco->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){

    if(trim($rh05_recis_dia)!="" && trim($rh05_recis_mes)!="" && trim($rh05_recis_ano)!=""){
      $clrhpesrescisao->rh05_seqpes = $rh02_seqpes;
      $clrhpesrescisao->incluir($rh02_regist);

      if($clrhpesrescisao->erro_status==0){
        $erro_msg = $clrhpesrescisao->erro_msg;
        $sqlerro=true;
      } else {

        $clpontofs->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);

        if($clpontofs->erro_status==0){
          $erro_msg = $clpontofs->erro_msg;
          $sqlerro=true;
        }else{

          $clpontofx->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);

          if($clpontofx->erro_status==0){
            $erro_msg = $clpontofx->erro_msg;
            $sqlerro=true;
          }
        }
      }
    }

  }

  if($sqlerro == false){
    if(trim($rh03_padrao) != ""){
      $clrhpespadrao->rh03_anousu = $rh02_anousu;
      $clrhpespadrao->rh03_mesusu = $rh02_mesusu;
      $clrhpespadrao->rh03_padrao = $rh03_padrao;
      $clrhpespadrao->rh03_regime = $rh30_regime;
      $clrhpespadrao->incluir($rh02_seqpes);
      if($clrhpespadrao->erro_status==0){
        $erro_msg = $clrhpespadrao->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){
    if(trim($rh20_cargo) != ""){
      $clrhpescargo->rh20_instit = db_getsession("DB_instit");
      $clrhpescargo->rh20_cargo = $rh20_cargo;
      $clrhpescargo->incluir($rh02_seqpes);
      if($clrhpescargo->erro_status==0){
        $erro_msg = $clrhpescargo->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){
    if(trim($rh19_propi) != ""){
      $clrhpesprop->rh19_propi = $rh19_propi;
      $clrhpesprop->incluir($rh02_regist);
      if($clrhpesprop->erro_status==0){
        $erro_msg = $clrhpesprop->erro_msg;
        $sqlerro=true;
      }
    }
  }

  db_fim_transacao($sqlerro);

}else if(isset($alterar)){
  db_inicio_transacao();

  $sqlerro = false;
  $clrhpessoalmov->rh02_instit = db_getsession('DB_instit');
  $clrhpessoalmov->alterar($rh02_seqpes,db_getsession('DB_instit'));
  $erro_msg = $clrhpessoalmov->erro_msg;
  if($clrhpessoalmov->erro_status==0){
    $sqlerro=true;
  }
  
  if ($sqlerro == false) {
    
    $clrhpessoal->rh01_funcao = $rh02_funcao;
    $clrhpessoal->rh01_regist = $oPost->rh02_regist;
    $clrhpessoal->alterar($oPost->rh02_regist);
    if ($clrhpessoal->erro_status == 0) {
      $sqlerro = true;
    } 
  }  
  
  if($sqlerro == false){
    if(trim($rh21_regpri)!=""){
      $result_origem = $clrhpesorigem->sql_record($clrhpesorigem->sql_query_file($rh02_regist));
      if($clrhpesorigem->numrows > 0){
        $clrhpesorigem->rh21_regist = $rh02_regist;
        $clrhpesorigem->rh21_regpri = $rh21_regpri;
        $clrhpesorigem->alterar($rh02_regist);
      }else{
        $clrhpesorigem->incluir($rh02_regist);
      }
    }else{
      $clrhpesorigem->excluir($rh02_regist);
    }
    if($clrhpesorigem->erro_status==0){
      $erro_msg = $clrhpesorigem->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    $clrhpescargo->excluir($rh02_seqpes);
    if($clrhpescargo->erro_status==0){
      $erro_msg = $clrhpescargo->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    if(trim($rh20_cargo) != ""){
      $clrhpescargo->rh20_instit = db_getsession('DB_instit');
      $clrhpescargo->rh20_cargo = $rh20_cargo;
      $clrhpescargo->incluir($rh02_seqpes);
      if($clrhpescargo->erro_status==0){
        $erro_msg = $clrhpescargo->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro==false){
    if(trim($rh44_codban) != ""){
      $result_banco = $clrhpesbanco->sql_record($clrhpesbanco->sql_query($rh02_seqpes,"rh02_seqpes"));
      if($clrhpesbanco->numrows>0){
        db_fieldsmemory($result_banco,0);
        $clrhpesbanco->rh44_seqpes = $rh02_seqpes;
        $clrhpesbanco->alterar($rh02_seqpes);
      }else{
        $clrhpesbanco->rh44_seqpes = $rh02_seqpes;
        $clrhpesbanco->incluir($rh02_seqpes);
      }
    }else{
      $clrhpesbanco->excluir($rh02_seqpes);
    }
    if($clrhpesbanco->erro_status==0){
      $erro_msg = $clrhpesbanco->erro_msg;
      $sqlerro=true;
    }
  }

  $excluiponto = false;
  if($sqlerro == false){
    if(trim($rh05_recis_dia)!="" && trim($rh05_recis_mes)!="" && trim($rh05_recis_ano)!=""){

      $sCamposPensao = "distinct(r52_regist+r52_numcgm), r52_regist, r52_numcgm";
      $sWherePensao  = " r52_anousu = " . db_anofolha() . " and r52_mesusu = " . db_mesfolha();
      $sWherePensao .= " and rh05_recis is null and r52_regist = {$rh02_regist}";

      $sSqlPensao = $clpensao->sql_query_pensao_rescisao(null, null, null, null, $sCamposPensao, "r52_regist", $sWherePensao);
      $rsPensao   = $clpensao->sql_record( $sSqlPensao );

      if ($clpensao->numrows > 0) {
        $aPensoes = db_utils::getCollectionByRecord($rsPensao);

        foreach ($aPensoes as $oPensao) {

          $clpensao->r52_anousu = db_anofolha();
          $clpensao->r52_mesusu = db_mesfolha();
          $clpensao->r52_regist = $rh02_regist;
          $clpensao->r52_numcgm = $oPensao->r52_numcgm;
          $clpensao->r52_valor  = '0';
          $clpensao->r52_valcom = '0';
          $clpensao->r52_val13  = '0';
          $clpensao->r52_valfer = '0';

          $clpensao->alterar(db_anofolha(), db_mesfolha(), $rh02_regist, $oPensao->r52_numcgm);

          if ($clpensao->erro_status == 0) {
            $erro_msg = $clpensao->erro_msg;
            $sqlerro  = true;
          }
        }
      }

      $excluiponto = true;
      $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_file($rh02_seqpes));

      if($clrhpesrescisao->numrows > 0){
        $clrhpesrescisao->rh05_seqpes = $rh02_seqpes;
        $clrhpesrescisao->alterar($rh02_seqpes);
      }else{
        $clrhpesrescisao->rh05_seqpes = $rh02_seqpes;
        $clrhpesrescisao->incluir($rh02_seqpes);
      }
    }else{
      $clrhpesrescisao->excluir($rh02_seqpes);
    }

    if($clrhpesrescisao->erro_status==0){
      $erro_msg = $clrhpesrescisao->erro_msg;
      $sqlerro=true;
    } else if($excluiponto == true){

      $clpontofs->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);
      if($clpontofs->erro_status==0){
        $erro_msg = $clpontofs->erro_msg;
        $sqlerro=true;
      }else{

        $clpontofx->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);
        if($clpontofx->erro_status==0){
          $erro_msg = $clpontofx->erro_msg;
          $sqlerro=true;
        }
      }

    }
  }
  
  if($sqlerro == false){
    if(trim($rh03_padrao) != ""){
      $result_testa = $clrhpespadrao->sql_record($clrhpespadrao->sql_query_file($rh02_seqpes));
      if($clrhpespadrao->numrows == 0){
        $clrhpespadrao->rh03_anousu = $rh02_anousu;
        $clrhpespadrao->rh03_mesusu = $rh02_mesusu;
        $clrhpespadrao->rh03_padrao = $rh03_padrao;
        $clrhpespadrao->rh03_regime = $rh30_regime;
        $clrhpespadrao->incluir($rh02_seqpes);
      }else{
      	$clrhpespadrao->rh03_seqpes = $rh02_seqpes;
        $clrhpespadrao->rh03_anousu = $rh02_anousu;
        $clrhpespadrao->rh03_mesusu = $rh02_mesusu;
        $clrhpespadrao->rh03_padrao = $rh03_padrao;
        $clrhpespadrao->rh03_regime = $rh30_regime;
        $clrhpespadrao->alterar($rh02_seqpes);
      }
    }else{
      $clrhpespadrao->excluir($rh02_seqpes);
    }
    if($clrhpespadrao->erro_status==0){
      $erro_msg = $clrhpespadrao->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    if(trim($rh19_propi) != ""){
      $result_propi = $clrhpesprop->sql_record($clrhpesprop->sql_query_file($rh02_regist));
      $clrhpesprop->rh19_regist = $rh02_regist;
      $clrhpesprop->rh19_propi = $rh19_propi;
      if($clrhpesprop->numrows > 0){
        $clrhpesprop->alterar($rh02_regist);
      }else{
        $clrhpesprop->incluir($rh02_regist);
      }
    }else{
      $clrhpesprop->rh19_propi = $rh19_propi;
      $clrhpesprop->excluir($rh02_regist);
    }
    if($clrhpesprop->erro_status==0){
      $erro_msg = $clrhpesprop->erro_msg;
      $sqlerro=true;
    }
  }

  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();

    $clrhpescargo->excluir($rh02_seqpes);
    if($clrhpescargo->erro_status==0){
      $erro_msg = $clrhpescargo->erro_msg;
      $sqlerro=true;
    }

    if($sqlerro==false){
      $clrhpeslocaltrab->excluir($rh02_seqpes);
      if($clrhpeslocaltrab->erro_status==0){
        $erro_msg = $clrhpeslocaltrab->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpespadrao->excluir(null,"rh56_seqpes = ".$rh02_seqpes);
      if($clrhpespadrao->erro_status==0){
        $erro_msg = $clrhpespadrao->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesbanco->excluir($rh02_seqpes);
      if($clrhpesbanco->erro_status==0){
        $erro_msg = $clrhpesbanco->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesrescisao->excluir($rh02_seqpes);
      if($clrhpesrescisao->erro_status==0){
        $erro_msg = $clrhpesrescisao->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesorigem->excluir($rh02_regist);
      if($clrhpesorigem->erro_status==0){
        $erro_msg = $clrhpesorigem->erro_msg;
        $sqlerro=true;
      }
    }
 
    if($sqlerro==false){
      $clrhpesprop->excluir($rh02_regist);
      if($clrhpesprop->erro_status==0){
        $erro_msg = $clrhpesprop->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpessoalmov->excluir($rh02_seqpes);
      $erro_msg = $clrhpessoalmov->erro_msg;
      if($clrhpessoalmov->erro_status==0){
        $sqlerro=true;
      }
    }
    db_fim_transacao($sqlerro);
  }
}

$rh02_anousu = db_anofolha();
$rh02_mesusu  = db_mesfolha();
$limparrecis = false;
$limparbanco = false;
if(isset($rh02_regist)){
	$instit = db_getsession("DB_instit");
  $result = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query(null,null,"*","","rh02_regist=$rh02_regist and rh02_anousu=$rh02_anousu and rh02_mesusu=$rh02_mesusu and rh02_instit = $instit "));
  if($clrhpessoalmov->numrows>0){
    db_fieldsmemory($result,0);
    $opcao = "alterar";
    $result_banco = $clrhpesbanco->sql_record($clrhpesbanco->sql_query($rh02_seqpes));
    if($clrhpesbanco->numrows>0){
      db_fieldsmemory($result_banco,0);
    }else{
      $limparbanco = true;
    }
    if($rh30_vinculo == "P"){
      $result_rhpesorigem = $clrhpesorigem->sql_record($clrhpesorigem->sql_query_file($rh02_regist));
      if($clrhpesorigem->numrows > 0){
        db_fieldsmemory($result_rhpesorigem,0);
        $result_nomeorigem = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh21_regpri,"z01_nome as z01_nomeorigem"));
        if($clrhpessoal->numrows > 0){
          db_fieldsmemory($result_nomeorigem, 0);
        }
      }
    }
    $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_file($rh02_seqpes));
    if($clrhpesrescisao->numrows > 0){
      db_fieldsmemory($result_rescisao,0);
      if(trim($rh30_regime) != ""){
        $result_descricoes = $clrescisao->sql_record($clrescisao->sql_query_file($rh02_anousu,$rh02_mesusu,$rh30_regime,$rh05_causa,$rh05_caub,null,null,"r59_descr,r59_descr1"));
        if($clrescisao->numrows > 0){
          db_fieldsmemory($result_descricoes,0);
        }else{
          $limparrecis = true;
        }
      }else{
        $limparrecis = true;
      }
    }else{
      $limparrecis = true;
    }
    // echo "<BR><BR>".($clrhpespadrao->sql_query_padroes($rh02_seqpes,"rh03_padrao,r02_descr"));
    $result_pespadrao = $clrhpespadrao->sql_record($clrhpespadrao->sql_query_padroes($rh02_seqpes,"rh03_padrao,r02_descr"));
    if($clrhpespadrao->numrows > 0){
      db_fieldsmemory($result_pespadrao,0);
    }

    $result_cargo = $clrhpescargo->sql_record($clrhpescargo->sql_query_descr($rh02_seqpes,"rh20_cargo,rh04_descr"));
    if($clrhpescargo->numrows > 0){
    	db_fieldsmemory($result_cargo, 0);
    }

    $result_rhpeslocaltrab = $clrhpeslocaltrab->sql_record($clrhpeslocaltrab->sql_query_descrlocal($rh02_seqpes));
    if($clrhpeslocaltrab->numrows > 0){
      db_fieldsmemory($result_rhpeslocaltrab, 0);
    }

    $result_propi = $clrhpesprop->sql_record($clrhpesprop->sql_query_file($rh02_regist));
    if($clrhpesprop->numrows > 0){
      db_fieldsmemory($result_propi,0);
    }

    $sSqlRhFuncao = $clrhfuncao->sql_query($rh02_funcao,$instit,"rh37_funcao,rh37_descr",null,"");
    $rsRhFuncao   = $clrhfuncao->sql_record($sSqlRhFuncao);
    if ($clrhfuncao->numrows > 0) {
    	db_fieldsmemory($rsRhFuncao,0);
    }
  }
}

if(isset($limparbanco) && $limparbanco == true){
  unset($rh44_codban,$db90_descr,$rh44_agencia,$rh44_dvagencia,$rh44_conta,$rh44_dvconta);
}
if(isset($limparrecis) && $limparrecis == true){
  unset($rh05_recis_dia,$rh05_recis_mes,$rh05_recis_ano,$rh05_causa,$rh05_caub,$r59_descr,$rh05_aviso_dia,$rh05_aviso_mes,$rh05_aviso_ano,$r59_descr1,$rh05_taviso);
}

if ( !isset($rh30_vinculo) ) {
	$rh30_vinculo = "";
}

if (isset($rh02_salari)) {
	$rh02_salari = trim(db_formatar($rh02_salari,"p"));
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_disabledtipoapos('<?=$rh30_vinculo?>');">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
			<?
			   include("forms/db_frmrhpessoalmov.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?

if(isset($alterar) || isset($excluir) || isset($incluir)){
  
  /**
   * Configura WHERE rhpesbanco
   */
  $sWherePesBanco  = "     rh44_codban    = '{$rh44_codban}'    ";
  $sWherePesBanco .= " and rh44_agencia   = '{$rh44_agencia}'   ";
  $sWherePesBanco .= " and rh44_dvagencia = '{$rh44_dvagencia}' ";
  $sWherePesBanco .= " and rh44_conta     = '{$rh44_conta}'     ";
  $sWherePesBanco .= " and rh44_dvconta   = '{$rh44_dvconta}'   ";
  $sWherePesBanco .= " and rh02_regist   <> '{$rh02_regist}'    ";
  $sWherePesBanco .= " and rh02_mesusu    = ".db_mesfolha();
  $sWherePesBanco .= " and rh02_anousu    = ".db_anofolha();
  $sWherePesBanco .= " and rhpesrescisao.rh05_seqpes is null";

  $sSqlValidaRhPesBanco = "select distinct
                                  rh02_regist, 
                                  z01_nome 
                             from rhpesbanco 
                                  inner join rhpessoalmov  on rhpessoalmov.rh02_seqpes = rhpesbanco.rh44_seqpes
                                  inner join rhpessoal     on rhpessoal.rh01_regist    = rhpessoalmov.rh02_regist
                                  inner join cgm           on cgm.z01_numcgm           = rhpessoal.rh01_numcgm  
		                               left join rhpesrescisao on rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
		                        where {$sWherePesBanco}";
  $rsRhPesBanco = $clrhpesbanco->sql_record($sSqlValidaRhPesBanco);
  if ( $clrhpesbanco->numrows > 0 ) {
    $oDadosRhPesBanco    = db_utils::getColectionByRecord($rsRhPesBanco);
    $sStrDadosServidores = "";
    foreach ($oDadosRhPesBanco as $oDados) {
      $sStrDadosServidores .= $oDados->rh02_regist." - ".$oDados->z01_nome."\\n";
    }
    db_msgbox("AVISO:\\nExistem servidores cadastrados com os mesmos dados de conta informados.\\n\\nServidor(es):\\n {$sStrDadosServidores}");
  }
  
  db_msgbox($erro_msg);
  
  if((isset($alterar) || isset($incluir)) && $sqlerro == false){
    echo "<script> parent.mo_camada('rhdepend'); </script>";
  }
}
/**
 * Verifica se  o  usuário possui permissao para liberar as abas para o lançamento 
 */
if(isset($rh02_seqpes)){
  echo "
        <script>
          parent.document.formaba.rhpeslocaltrab.disabled=false;
          top.corpo.iframe_rhpeslocaltrab.location.href='pes1_rhpeslocaltrab001.php?rh56_seqpes=".@$rh02_seqpes."&rh02_regist={$rh02_regist}';
          ";
  if (db_permissaomenu(db_getsession("DB_anousu"), 952,4507) == 'true'|| 
      db_permissaomenu(db_getsession("DB_anousu"), 952, 4515) == 'true') {
   
    echo "parent.document.formaba.rhpontofixo.disabled=false;\n";
    echo "top.corpo.iframe_rhpontofixo.location.href='pes1_rhpessoalponto001.php?ponto=fx&r90_regist=".@$rh02_regist."'\n";
  }
  if (db_permissaomenu(db_getsession("DB_anousu"), 952, 4506)  == 'true' || 
      db_permissaomenu(db_getsession("DB_anousu"), 952, 4514)  =='true') {
    echo "parent.document.formaba.rhpontosalario.disabled=false;\n";
    echo "top.corpo.iframe_rhpontosalario.location.href='pes1_rhpessoalponto001.php?ponto=fs&r90_regist=".@$rh02_regist."'\n";
   }
          
  echo "</script>";
}



?>