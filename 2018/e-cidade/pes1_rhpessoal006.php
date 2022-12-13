<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));
require_once(modification("classes/db_rhpesrescisao_classe.php"));
require_once(modification("classes/db_rhpesfgts_classe.php"));
require_once(modification("classes/db_rhpesdoc_classe.php"));
require_once(modification("classes/db_rhpessoalmov_classe.php"));
require_once(modification("classes/db_rhraca_classe.php"));
require_once(modification("classes/db_rhinstrucao_classe.php"));
require_once(modification("classes/db_rhestcivil_classe.php"));
require_once(modification("classes/db_rhnacionalidade_classe.php"));
require_once(modification("classes/db_cfpess_classe.php"));
require_once(modification("classes/db_rhpespadrao_classe.php"));
require_once(modification("classes/db_rhpesbanco_classe.php"));
require_once(modification("classes/db_rhfotos_classe.php"));
require_once(modification("classes/db_rhpesorigem_classe.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("classes/db_rhferias_classe.php"));

$clrhferias                       = new cl_rhferias;
$clrhpessoal                      = new cl_rhpessoal;
$clrhpesrescisao                  = new cl_rhpesrescisao;
$clrhpesbanco                     = new cl_rhpesbanco;
$clrhpespadrao                    = new cl_rhpespadrao;
$clrhpesfgts                      = new cl_rhpesfgts;
$clrhpesdoc                       = new cl_rhpesdoc;
$clrhpessoalmov                   = new cl_rhpessoalmov;
$clrhraca                         = new cl_rhraca;
$clrhinstrucao                    = new cl_rhinstrucao;
$clrhestcivil                     = new cl_rhestcivil;
$clrhnacionalidade                = new cl_rhnacionalidade;
$clcfpess                         = new cl_cfpess;
$clrhfotos                        = new cl_rhfotos;
$clrhpesorigem                    = new cl_rhpesorigem;
$clrhcontratoemergencial          = new cl_rhcontratoemergencial;
$clrhcontratoemergencialrenovacao = new cl_rhcontratoemergencialrenovacao;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;

$ano = db_anofolha();
$mes = db_mesfolha();

if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();

  $clrhpesdoc->excluir($rh01_regist);
  $erro_msg = $clrhpesdoc->erro_msg;
  if($clrhpesdoc->erro_status==0){
    $sqlerro=true;
  } 

  $result_pessoalmov = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query_file(null,null,"distinct rh02_seqpes as seqpes","","rh02_regist=".$rh01_regist." and rh02_instit = ".db_getsession("DB_instit")));
  for($i=0; $i<$clrhpessoalmov->numrows; $i++){
  	db_fieldsmemory($result_pessoalmov,$i);

    if($sqlerro==false){
      $clrhpespadrao->excluir($seqpes);
      if($clrhpespadrao->erro_status==0){
        $erro_msg = $clrhpespadrao->erro_msg;
        $sqlerro=true;
        break;
      }
    }

    if($sqlerro==false){
      $clrhpesbanco->excluir($seqpes);
      if($clrhpesbanco->erro_status==0){
        $erro_msg = $clrhpesbanco->erro_msg;
        $sqlerro=true;
        break;
      }
    }

    if($sqlerro==false){ 
      $clrhpesrescisao->excluir($seqpes);
      if($clrhpesrescisao->erro_status==0){
        $erro_msg = $clrhpesrescisao->erro_msg;
        $sqlerro=true;
        break;
      }
    }
  }

  if($sqlerro == false){
      $clrhpessoalmov->excluir(null,db_getsession("DB_instit"),"rh02_regist=".$rh01_regist." and rh02_instit = ".db_getsession("DB_instit"));
    $erro_msg = $clrhpessoalmov->erro_msg;
    if($clrhpessoalmov->erro_status==0){
      $sqlerro=true;
    } 
  }

  if($sqlerro == false){
    $clrhpesorigem->excluir($rh01_regist);
    if($clrhpesorigem->erro_status==0){
      $erro_msg = $clrhpesorigem->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    $clrhferias->excluir(null,"rh109_regist = ".$rh01_regist);
    $erro_msg = $clrhferias->erro_msg;
    if($clrhferias->erro_status==0){
      $sqlerro=true;
    } 
  }

  if($sqlerro == false) {
    $clrhpessoal->excluir($rh01_regist);
    $erro_msg = $clrhpessoal->erro_msg;
    if($clrhpessoal->erro_status==0){
      $sqlerro=true;
    } 
  }

  if($sqlerro == false && $hasContratoEmergencial == "t") {

    $clrhcontratoemergencialrenovacao->excluir(null, "rh164_contratoemergencial in (select rh163_sequencial from rhcontratoemergencial where rh163_matricula = {$rh01_regist})");

    $erro_msg = $clrhcontratoemergencialrenovacao->erro_msg;
    if ($clrhcontratoemergencialrenovacao->erro_status == 0) {
      $sqlerro=true;
    } 

    $clrhcontratoemergencial->excluir(null, "rh163_matricula = {$rh01_regist}");

    $erro_msg = $clrhcontratoemergencial->erro_msg;
    if ($clrhcontratoemergencial->erro_status == 0) {
      $sqlerro=true;
    }     
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clrhpessoal->sql_record($clrhpessoal->sql_query_com_temporarios($chavepesquisa, "*", "rh164_datafim desc"));
   if($clrhpessoal->numrows > 0){
     db_fieldsmemory($result,0);
     if(!empty($rh163_matricula)){
       $visibilityContratoEmergencial  = true;
       $contratoEmergencial = 't';
     }
     $result_rhpesfgts = $clrhpesfgts->sql_record($clrhpesfgts->sql_query_banco($rh01_regist,"rh15_data,rh15_banco,rh15_agencia,rh15_agencia_d,rh15_contac,rh15_contac_d,db90_descr"));
     if($clrhpesfgts->numrows > 0){
     	db_fieldsmemory($result_rhpesfgts,0);
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
<?
  db_app::load("prototype.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
  .fieldset-hr {
    border:none;
    border-top: 1px outset #000;
  }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.getElementById('db_opcao').focus();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include(modification("forms/db_frmrhpessoal.php"));
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrhpessoal->erro_campo!=""){
      echo "<script> document.form1.".$clrhpessoal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhpessoal->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='pes1_rhpessoal003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
      
         parent.document.formaba.rhpesdoc.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpesdoc.location.href='pes1_rhpesdoc001.php?db_opcaoal=33&rh16_regist=".@$rh01_regist."';
         
         parent.document.formaba.rhpessoalmov.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpessoalmov.location.href='pes1_rhpessoalmov001.php?db_opcaoal=33&rh02_regist=".@$rh01_regist."';
         
         parent.document.formaba.rhsuspensaopag.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhsuspensaopag.location.href='pes1_rhsuspensaopag001.php?iMatricula=".@$rh01_regist."&db_opcao={$db_opcao}';
         
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('rhpesdoc');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>