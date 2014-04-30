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
require_once("classes/db_parissqn_classe.php");
require_once("classes/db_isstipoalvara_classe.php");
require_once("classes/db_meiimporta_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($_POST);

$clparissqn   = new cl_parissqn();
$clMeiImporta = new cl_meiimporta();
$clTipoAlvara = new cl_isstipoalvara();

$db_opcao = 2;
$db_botao = false;

if(isset($alterar)){
	
	 $sql_erro = false;
   $sMsgErro = '';
   
	 db_inicio_transacao();
	 
   if($q60_modalvara == 9){
   	if(trim($q60_templatealvara) == ''){
   		$sMsgErro = "usuário:\\n\\n Campo Documento Alvará não informado.\\n\\nAdministrador: \\n\\n ";
   		$clparissqn->erro_msg = "usuário:\\n\\n Campo Documento Alvará não informado.\\n\\nAdministrador: \\n\\n ";
   		$clparissqn->erro_status = "0";
   		$sql_erro = true;
   	}
   }else{   	
   	$clparissqn->q60_templatealvara = "null";
   }
   
   if(empty($q60_parcelasalvara)){
     $sMsgErro                = "Campo Limite Parcelas Alvará não pode ser 0 ou em branco.";
     $clparissqn->erro_msg    = $sMsgErro;
     $clparissqn->erro_status = "0";
     $clparissqn->erro_campo  = "q60_parcelasalvara";
     $sql_erro = true;
   }
   
   if(!$sql_erro){
	   $result = $clparissqn->sql_record($clparissqn->sql_query(null,"q60_dataimpmei"));
	   if($result==false || $clparissqn->numrows==0){	   	 
	     $clparissqn->incluir();
	     $sMsgErro = $clparissqn->erro_msg;
	   } else {
	   	 
	   	$oParIssqn = db_utils::fieldsMemory($result,0);
	   	 
	     $rsMei = $clMeiImporta->sql_record($clMeiImporta->sql_query_file());
	     
		   if ( $clMeiImporta->numrows > 0 ) {
		     if ( $oParIssqn->q60_dataimpmei != implode("-",array_reverse(explode("/",$q60_dataimpmei)))) {
		        $sql_erro = true;
		        $sMsgErro  = "Não é possível alterar a data de implantação do MEI"; 
		        $sMsgErro .= "\\nExistem registros já lançados!";
		     }
		   }
		   if ( !$sql_erro ) {
		   	 if ($q60_dataimpmei == "") {
		   	 	 $clparissqn->q60_dataimpmei = 'NULL';
		   	 } else {
		   	  $clparissqn->q60_dataimpmei = $q60_dataimpmei;
		   	 }
		     $clparissqn->alterarParametro();
		     $sMsgErro = $clparissqn->erro_msg;
		   }
	   }
   }
   
   db_fim_transacao($sql_erro);
   
} else {
  
  $db_opcao = 2;
  $result = $clparissqn->sql_record($clparissqn->sql_query());
  
  if($result!=false && $clparissqn->numrows>0){
    db_fieldsmemory($result,0);
  }
  
  $rsTipoAlvara = $clTipoAlvara->sql_record($clTipoAlvara->sql_query($q60_isstipoalvaraprov, 
                                                                     "q98_descricao as q98_descricaoprov"));
  if($rsTipoAlvara != false && $clTipoAlvara->numrows > 0){
      db_fieldsmemory($rsTipoAlvara,0);
  }
  
  $rsTipoAlvara = $clTipoAlvara->sql_record($clTipoAlvara->sql_query($q60_isstipoalvaraper, 
                                                                     "q98_descricao as q98_descricaoper"));
  if($rsTipoAlvara != false && $clTipoAlvara->numrows > 0){
      db_fieldsmemory($rsTipoAlvara,0);
  }
}

$db_botao = true;
?>
<html>
  <head>
  <?php 
    db_app::load('scripts.js, prototype.js, strings.js, DBHint.widget.js');
    db_app::load('estilos.css');
  ?>
  </head>
  <body bgcolor=#CCCCCC>
	<?
	  include("forms/db_frmparissqn.php");
	
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  
  ?>
</body>
</html>
<?
if(isset($alterar)){
  if( $sql_erro ){
  	db_msgbox($sMsgErro);
  	
  	if ($sMsgErro == '') { 
      $clparissqn->erro(false,true);
  	}
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clparissqn->erro_campo!=""){
      echo "<script> document.form1.".$clparissqn->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparissqn->erro_campo.".focus();</script>";
    }
  }else{
    $clparissqn->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>