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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_conplanoexe_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcreceita_classe.php");
include("classes/db_orcreceitaval_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamrec_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_conlancamimp_classe.php");

db_postmemory($HTTP_POST_VARS);

$clorcreceita   = new cl_orcreceita;
$clorcreceitaval= new cl_orcreceitaval;
$clconlancam    = new cl_conlancam;
$clconlancamrec = new cl_conlancamrec;
$clconlancamdoc = new cl_conlancamdoc;
$cldb_config    = new cl_db_config;
$clconlancamimp = new cl_conlancamimp;

if (!isset($db_opcao)){
  $db_opcao= 1;
}    
$db_botao = true;
$ano = 2004;
$op=1;

//--
if (isset($processar)){
  // seleciona os registros do orcreceitaval e gera lançamento contabil
  $res = $clconlancamimp->sql_record($clconlancamimp->sql_query(null,"c88_codlan",null,"c70_anousu=".db_getsession("DB_anousu")." and c71_coddoc in (100,101)"));  
  if ($clconlancamimp->numrows > 0 ){
     $rows = $clconlancamimp->numrows;
     for ($linha=0;$linha < $rows ;$linha++ ){
        db_fieldsmemory($res,$linha);
	
	$rr = $clconlancamimp->excluir($c88_codlan);
	if ($clconlancamimp->erro_status===0){
	   db_msgbox($clconlancamimp->erro_msg);
           break;    
	}  	

	$rr = $clconlancamdoc->excluir($c88_codlan);
	if ($clconlancamdoc->erro_status===0){
	   db_msgbox($clconlancamdoc->erro_msg);
           break;    
	}  	

	$rr = $clconlancamrec->excluir($c88_codlan);
	if ($clconlancamrec->erro_status===0){
	   db_msgbox($clconlancamrec->erro_msg);
           break;    
	}  	

	$rr = $clconlancam->excluir($c88_codlan);
	if ($clconlancam->erro_status===0){
	   db_msgbox($clconlancam->erro_msg);
           break;    
	}  	

     }  
  }  
  // insere dados na contabilidade novamente  
  $res  = $clorcreceitaval->sql_record($clorcreceitaval->sql_query_file(db_getsession("DB_anousu")));
  //  db_criatabela($res);   
  if ($clorcreceitaval->numrows > 0){
      //    db_inicio_transacao();
     for($x=0;$x < $clorcreceitaval->numrows;$x++){
       db_fieldsmemory($res,$x);
       
       $data = $o71_anousu."-".$o71_mes."-"."01";
       //--
       $clconlancam->c70_data= $data;
       $clconlancam->c70_valor = $o71_valor;
       $clconlancam->c70_anousu = $o71_anousu;
       $clconlancam->incluir("");
       if ($clconlancam->erro_status===0){
           db_msgbox($clconlancam->erro_msg);
           break;
       }
	
       $codlan = $clconlancam->c70_codlan;
       //---
       $clconlancamrec->c74_anousu= $o71_anousu;
       $clconlancamrec->c74_data = $data;
       $clconlancamrec->c74_codrec = $o71_codrec;
       $clconlancamrec->incluir($codlan);
       if ($clconlancamrec->erro_status===0){
           db_msgbox($clconlancamrec->erro_msg);
           break;
       }
				       
       //--- 
       $clconlancamdoc->c71_data = $data;
       $clconlancamdoc->c71_coddoc = $o71_coddoc;
       $clconlancamdoc->incluir($codlan);
       if ($clconlancamdoc->erro_status===0){
           db_msgbox($clconlancamdoc->erro_msg);
           break;
       }
	
       $clconlancamimp->c88_codlan = $codlan;
       $clconlancamimp->incluir($codlan);
       if ($clconlancamimp->erro_status===0){
           db_msgbox($clconlancamimp->erro_msg);
           break;
       }
	
       
     } 
     // db_fim_transacao();     
  }  
  
  
}
//--
///////////////////////////////////////////////////////////////////////////
// atualiza tabela orcreceitaval
function atuOrcreceitaval($codrec,$mes,$valor){
     $msg = "0|Registro Atualizado !";
     $clorcreceitaval = new cl_orcreceitaval;		
     $clorcreceita    = new cl_orcreceita;		
     if ($mes == 0 || $mes =='0' || $mes === 0){
        // ajusta o saldo previsto
	$clorcreceita->o70_valor = $valor;
	$clorcreceita->o70_codrec= $codrec;
	$clorcreceita->o70_anousu= db_getsession("DB_anousu");
        $rr= $clorcreceita->alterar(db_getsession("DB_anousu"),$codrec);
	if ($clorcreceita->erro_status==='0'){
	      $msg = "1|".$clorcreceita->erro_msg;
	}else {
	      $msg = "0|".$clorcreceita->erro_msg;  
	}  
        return $msg;
     }  
     if ($valor > 0){
        $clorcreceitaval->o71_coddoc = 100; 
     }else{
        $clorcreceitaval->o71_coddoc = 101; 
     }
     $clorcreceitaval->o71_anousu = db_getsession("DB_anousu");    
     $clorcreceitaval->o71_mes    = $mes; 
     $clorcreceitaval->o71_codrec = $codrec;
     $clorcreceitaval->o71_valor  = $valor;         


     $rr = $clorcreceitaval->sql_record($clorcreceitaval->sql_query_file(db_getsession("DB_anousu"),$codrec,$clorcreceitaval->o71_coddoc,$mes));
     if ($clorcreceitaval->numrows >0){
          $clorcreceitaval->alterar($clorcreceitaval->o71_anousu,$clorcreceitaval->o71_codrec,$clorcreceitaval->o71_coddoc,$clorcreceitaval->o71_mes);

     } else { 
          $clorcreceitaval->incluir($clorcreceitaval->o71_anousu,$clorcreceitaval->o71_codrec,$clorcreceitaval->o71_coddoc,$clorcreceitaval->o71_mes);
     }	  
     
     $erro = $clorcreceitaval->erro_msg;
     if ($clorcreceitaval->erro_status===0){
        $msg= "1|".$erro;
	return $msg;
     } else {
        $msg = "0|".$clorcreceitaval->erro_msg;
        return $msg;
     }
}
include("dbforms/Sajax.php");  // inclusão da biblioteda ajax
sajax_init();// Inicializar o sajax
$sajax_debug_mode = 0;// para Debugar o sajax = 0 desligado 1 = ligado
sajax_export("atuOrcreceitaval");// função exportada !
sajax_handle_client_request();


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
<? sajax_show_javascript();   /* imprime a função do sajax */ ?>
function js_atuOrcreceitaval(codrec,mes,valor){	 
    x_atuOrcreceitaval(codrec,mes,valor,mensagem);	 
}
function mensagem(retorno){
    js_ajax_msg(retorno); // chama função de retorno, div com mensagem
}	
</script>
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
    <td height="500" align="center" valign="top" bgcolor="#CCCCCC">     
    <center>
	<?
	include("forms/db_frmimplantadespesa.php");
	?>
    </center>
	</td>
  </tr>
</table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<?

/*
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  //if($clconplanoexe->erro_status=="0"){
  //  $clconplanoexe->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clconplanoexe->erro_campo!=""){
      echo "<script> document.form1.".$clconplanoexe->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconplanoexe->erro_campo.".focus();</script>";
    };
  }else{
    $clconplanoexe->erro(true,true);
  };
};
*/
?>