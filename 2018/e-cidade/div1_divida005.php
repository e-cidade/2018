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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_divida_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_proced_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_certdiv_classe.php");
require_once("classes/db_dividaprotprocesso_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oPost                  = db_utils::postMemory($_POST);
$oGet                   = db_utils::postMemory($_GET);
                        
$clcgm                  = new cl_cgm;
$cliptubase             = new cl_iptubase;
$clissbase              = new cl_issbase;
$cldivida               = new cl_divida;
$clnumpref              = new cl_numpref;
$clproced               = new cl_proced;
$clarrecad              = new cl_arrecad;
$clcertdiv              = new cl_certdiv;
$oDaoDividaprotprocesso = new cl_dividaprotprocesso();


if (isset($v01_coddiv)) {
  $db_opcao = 2;
  $db_botao = true;
} else {
  $db_opcao = 22;
  $db_botao = false;
}  

$existeumacda = false;

if(isset($alterar)){

  $result_divida = $cldivida->sql_record($cldivida->sql_query_file($v01_coddiv,"v01_numpre,v01_numpar as numpar_arrecad"));
 
  if ($cldivida->numrows > 0) {
    
    db_fieldsmemory($result_divida,0);
    db_inicio_transacao();
 
    $sqlerro = false;
    
    $dtvenc = $v01_dtvenc_ano."-".$v01_dtvenc_mes."-".$v01_dtvenc_dia;
    $dtoper = $v01_dtoper_ano."-".$v01_dtoper_mes."-".$v01_dtoper_dia;
    $dtinsc = $v01_dtinsc_ano."-".$v01_dtinsc_mes."-".$v01_dtinsc_dia;
    
    $cldivida->v01_numcgm = $v01_numcgm;
    $cldivida->v01_dtinsc = $dtinsc;
    $cldivida->v01_exerc  = $v01_exerc;
    $cldivida->v01_numpre = $v01_numpre;
    $cldivida->v01_numpar = $v01_numpar;
    $cldivida->v01_numtot = 1;
    $cldivida->v01_numdig = 1;
    $cldivida->v01_vlrhis = $v01_vlrhis;
    $cldivida->v01_proced = $v01_proced;
    $cldivida->v01_obs    = $v01_obs;
    $cldivida->v01_livro  = $v01_livro;
    $cldivida->v01_folha  = $v01_folha;
    $cldivida->v01_dtvenc = $dtvenc;
    $cldivida->v01_dtoper = $dtoper;
    $cldivida->v01_valor  = $v01_valor;
    
    if (isset($oPost->lProcessoSistema) && (int)$oPost->lProcessoSistema == 0) {   // PROCESSO EXTERNO
       
      $sSqlInternoExterno = $oDaoDividaprotprocesso->sql_query_file(null, '*', null, "v88_divida = {$oPost->v01_coddiv} ");
      $rsInternoExterno   = $oDaoDividaprotprocesso->sql_record($sSqlInternoExterno);
      
      if ($oDaoDividaprotprocesso->numrows > 0) {
        
        $oDaoDividaprotprocesso->excluir(null, "v88_divida = {$oPost->v01_coddiv}");
        if($oDaoDividaprotprocesso->erro_status==0){
        
          $oDaoDividaprotprocesso->erro_msg = $oDaoDividaprotprocesso->erro_msg;
          $sqlerro=true;
        }        
        
      }
      
      $cldivida->v01_processo   = $v01_processoExterno;
      $cldivida->v01_dtprocesso = $v01_dtprocesso;
      $cldivida->v01_titular    = $v01_titular;
    } 
    
    $cldivida->alterar($v01_coddiv);
    
    if ($cldivida->erro_status==0) {
      $sqlerro=true;
    }
    
    if (isset($oPost->lProcessoSistema) && (int)$oPost->lProcessoSistema == 1 && isset($v01_processo) && $v01_processo != null) {        // PROCESSO INTERNO
    
      /**
       * se o processo for interno inserimos na tabela de ligação dividaprotprocesso
       */
      $oDaoDividaprotprocesso->excluir(null, "v88_divida = {$cldivida->v01_coddiv}");
      if($oDaoDividaprotprocesso->erro_status==0){
      
        $oDaoDividaprotprocesso->erro_msg = $oDaoDividaprotprocesso->erro_msg;
        $sqlerro=true;
      }       
      
      $oDaoDividaprotprocesso->v88_divida       = $cldivida->v01_coddiv;
      $oDaoDividaprotprocesso->v88_protprocesso = $v01_processo;
      $oDaoDividaprotprocesso->incluir(null);
    
      //echo "<br>proc -> ".$v01_processo; die();
      if($oDaoDividaprotprocesso->erro_status==0){
        
        $oDaoDividaprotprocesso->erro_msg = $oDaoDividaprotprocesso->erro_msg;
        $sqlerro=true;
      }
    
    }    
    
    if ($sqlerro==false) {
      
    	$result_receit = $clproced->sql_record($clproced->sql_query_file($v01_proced));
    	
    	if ($clproced->numrows > 0){
    	  
    		db_fieldsmemory($result_receit,0);
    		$clarrecad->k00_receit=@$v03_receit;
    	}
    	
    	$dtvenc = $v01_dtvenc_ano."-".$v01_dtvenc_mes."-".$v01_dtvenc_dia;
    	$dtoper = $v01_dtoper_ano."-".$v01_dtoper_mes."-".$v01_dtoper_dia;
    	
    	$clarrecad->k00_numcgm = $v01_numcgm;
    	$clarrecad->k00_dtoper = @$dtoper;  	
    	$clarrecad->k00_valor  = $v01_vlrhis ;
    	$clarrecad->k00_dtvenc = @$dtvenc;
    	$clarrecad->k00_numpar = $v01_numpar;
    	$clarrecad->k00_numpre = $v01_numpre;
    	$clarrecad->k00_hist   = $k00_hist;
    	$clarrecad->k00_numtot = 1;
    	$clarrecad->k00_numdig = 1;
    	
      $clarrecad->alterar_arrecad("k00_numpre=$v01_numpre and k00_numpar=$numpar_arrecad");
      
      if ($clarrecad->erro_status==0){
      	$sqlerro=true; 	
      }
    }
      
    db_fim_transacao($sqlerro);
    
  } else {
    
    $sqlerro = true;
    $cldivida->erro_status = "0";
    $cldivida->erro_msg    = "Alteração abortada.\\nDívida não encontrada.\\n\\nContate o suporte.";
  }
  
  
} else if(isset($chavepesquisa)) {
  
  $db_opcao = 2;
  $db_botao = true;
  $result   = $cldivida->sql_record($cldivida->sql_query($chavepesquisa));
    
  db_fieldsmemory($result,0);
      
  $rsTipoDebito = $cldivida->sql_record("select k00_tipo from arrecad where k00_numpre = $v01_numpre");
   
  if ($cldivida->numrows > 0) { 
    db_fieldsmemory($rsTipoDebito,0);
  }   
   
  //Verifica se a Divida está em aberto (no arrecad)
  
  $sSqlDebitosDivida  = " select v01_coddiv,                                  ";
  $sSqlDebitosDivida .= "        v01_numpre,                                  ";
  $sSqlDebitosDivida .= "        v01_numpar                                   ";
  $sSqlDebitosDivida .= "   from arrecad                                      ";
  $sSqlDebitosDivida .= "        inner join divida on v01_numpre = k00_numpre "; 
  $sSqlDebitosDivida .= "                         and v01_numpar = k00_numpar ";
  $sSqlDebitosDivida .= "  where v01_coddiv = $chavepesquisa                  ";
  
  $rsDebitosDivoda = $cldivida->sql_record($sSqlDebitosDivida);
   
  if ( $cldivida->numrows == 0 ) {
	 
    db_msgbox("Dívida não está em aberto!!\\nAlteração não permitida!");
    $db_opcao = 22;
	  $db_botao = false;
    
  } else {
     
    $oDebitoDivida   = db_utils::fieldsMemory($rsDebitosDivoda,0);

    $sSqlPgtoParcial = "select fc_verifica_abatimento(1,{$oDebitoDivida->v01_numpre},{$oDebitoDivida->v01_numpar}) as pgtoparcial  ";
    $rsPgtoParcial   = db_query($sSqlPgtoParcial);    
    $lPgtoParcial    = db_utils::fieldsMemory($rsPgtoParcial,0)->pgtoparcial;
                                                           
    if ($lPgtoParcial == 't') {
      
      db_msgbox('Pagamento parcial existente para o débito informado!\\nAlteração não permitida!');
      $db_opcao = 22;
      $db_botao = false;
      
    } else {
      
   	  // Verifica se existe CDA para Divida
      $result_cda = $clcertdiv->sql_record($clcertdiv->sql_query_file(null,$chavepesquisa));
      
      if ($clcertdiv->numrows > 0) {
     	  db_msgbox("Existe CDA para esta dívida!!\\nAlteração permitida apenas no campo observações!");
        $existeumacda = true;
      }
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

<body bgcolor=#CCCCCC onLoad="a=1" >

    	<?
    	  require_once("forms/db_frmdivida.php");
    	?>



<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?



if(isset($alterar)){
  
  if ($cldivida->erro_status=="0") {
    
    $cldivida->erro(true,false);
    
    if ($cldivida->erro_campo!="") {
      echo "<script> document.form1.".$cldivida->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldivida->erro_campo.".focus();</script>";
    }
    
  } else {
    $cldivida->erro(true,true);
  }
}
if($db_opcao==22 && empty($subtes)){
  echo "<script>document.form1.pesquisar.click();</script>";
}

if (isset($oGet->chavepesquisa)) {
  
  /*
   * verificamos se o processo é interno ou externo
   */
  
  $sSqlInternoExterno = $oDaoDividaprotprocesso->sql_query_file(null, '*', null, "v88_divida = {$oGet->chavepesquisa} ");
  $rsInternoExterno   = $oDaoDividaprotprocesso->sql_record($sSqlInternoExterno);
  
  if ($oDaoDividaprotprocesso->numrows > 0) {
    
    echo "<script>";
    echo " js_pesquisaProcesso(false);";
    echo "</script>";
  } else {
    
    echo "<script>                                                        \n";
    echo " $('lProcessoSistema').options.length = 0;                      \n";
    echo " $('lProcessoSistema').options[0]     = new Option('Não', '0'); \n";   
    echo " $('lProcessoSistema').options[1]     = new Option('Sim', '1'); \n";
    echo " $('processoExterno1').style.display = '';                      \n";
    echo " $('processoExterno2').style.display = '';                      \n";
    echo " $('processoExterno3').style.display = '';                      \n";
    echo " $('processoSistema').style.display  = 'none';                  \n";   
    echo " $('v01_processoExterno').value = {$v01_processo} ;             \n";
    echo "</script>                                                       \n";   
    
  }
  
}

if (isset($subtes) && $subtes=="ok" && empty($incluir) && empty($alterar) && empty($excluir)) {
  /*
   * verificamos se o processo é interno ou externo
  */
  
  $sSqlInternoExterno = $oDaoDividaprotprocesso->sql_query_file(null, '*', null, "v88_divida = {$v01_coddiv} ");
  $rsInternoExterno   = $oDaoDividaprotprocesso->sql_record($sSqlInternoExterno);
  if ($oDaoDividaprotprocesso->numrows > 0) {
  
    echo "<script>";
    echo " js_pesquisaProcesso(false);";
    echo "</script>";
  } else {
  
    echo "<script>                                                        \n";
    echo " $('lProcessoSistema').options.length = 0;                      \n";
    echo " $('lProcessoSistema').options[0]     = new Option('Não', '0'); \n";
    echo " $('lProcessoSistema').options[1]     = new Option('Sim', '1'); \n";
    echo " $('processoExterno1').style.display = '';                      \n";
    echo " $('processoExterno2').style.display = '';                      \n";
    echo " $('processoExterno3').style.display = '';                      \n";
    echo " $('processoSistema').style.display  = 'none';                  \n";
    echo " $('v01_processoExterno').value = {$v01_processo} ;             \n";
    echo "</script>                                                       \n";
  
  }  
  
}

?>