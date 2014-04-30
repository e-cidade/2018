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
$oDaoDividaprotprocesso = new cl_dividaprotprocesso();

if(isset($v01_coddiv)){
  $db_opcao = 3;
  $db_botao = true;
}else{
  $db_opcao=33;
  $db_botao = false;
}  


//echo "<br><br>" . $k00_tipo_select_descr;

if(isset($excluir)){
  $sqlerro=false;
    
  $result_divida = $cldivida->sql_record($cldivida->sql_query_file($v01_coddiv," * ")); 
  if($cldivida->numrows > 0){
    db_fieldsmemory($result_divida,0);
    db_inicio_transacao();

    $cldivida->v01_coddiv = $v01_coddiv;
    $cldivida->v01_numcgm = $v01_numcgm;
    $cldivida->v01_dtinsc = $v01_dtinsc;
    $cldivida->v01_exerc  = $v01_exerc;
    $cldivida->v01_numpre = $v01_numpre;
    $cldivida->v01_numpar = $v01_numpar;
    $cldivida->v01_numtot = $v01_numtot;
    $cldivida->v01_numdig = $v01_numdig;
    $cldivida->v01_vlrhis = $v01_vlrhis;
    $cldivida->v01_proced = $v01_proced;
    $cldivida->v01_obs    = $v01_obs;
    $cldivida->v01_livro  = $v01_livro;
    $cldivida->v01_folha  = $v01_folha;
    $cldivida->v01_dtvenc = $v01_dtvenc;
    $cldivida->v01_dtoper = $v01_dtoper;
    $cldivida->v01_valor  = $v01_valor;

    $cldivida->incluir_adivida();
    if($cldivida->erro_status==0){
      $sqlerro=true;
      $erro_msg = $cldivida->erro_msg;
    } 
    
    if ((int)$oPost->lProcessoSistema == 1) {
      
      $oDaoDividaprotprocesso->excluir(null, "v88_divida = {$cldivida->v01_coddiv}");
      if($oDaoDividaprotprocesso->erro_status==0){
      
        $oDaoDividaprotprocesso->erro_msg = $oDaoDividaprotprocesso->erro_msg;
        $sqlerro=true;
      }      
      
    }
    
    

    if($sqlerro == false){
      $clarrecad->excluir_arrecad($v01_numpre, $v01_numpar);
      if($clarrecad->erro_status==0){
        $erro_msg = $clarrecad->erro_msg;
        $sqlerro=true;
      } 
    }

    if($sqlerro == false){
      $cldivida->excluir($v01_coddiv);
      $erro_msg = $cldivida->erro_msg;
      if($cldivida->erro_status==0){
        $sqlerro=true;
      } 
    }

    db_fim_transacao($sqlerro);
  }else{
    $sqlerro = true;
    $erro_msg = "Alteração abortada.\\nDívida não encontrada.\\n\\nContate o suporte.";
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cldivida->sql_record($cldivida->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<body bgcolor=#CCCCCC  onLoad="a=1" >

  	  <?
  	    include("forms/db_frmdivida.php");
  	  ?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
    if($cldivida->erro_campo!=""){
      echo "<script> document.form1.".$cldivida->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldivida->erro_campo.".focus();</script>";
    }
  }else{
    $cldivida->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>
         document.form1.pesquisar.click();
       </script>";
}

?>