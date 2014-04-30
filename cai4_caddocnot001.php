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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_notificaarretipodoc_classe.php");
include("dbforms/db_classesgenericas.php");

$oPost = db_utils::postMemory($_POST);

$clnotificaarratipodoc    = new cl_notificaarretipodoc();
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clnotificaarratipodoc->rotulo->label();

$lSqlErro = false;
$db_opcao = 1;
$k101_sequencial   = "";
$k101_db_documento = "";
$descrDocumento    = "";
$k101_tipo		   = "";
$descrTipo		   = "";

if (isset($oPost->incluir)) {
	
   db_inicio_transacao();
	
   $clnotificaarratipodoc->k101_db_documento = $oPost->k101_db_documento; 
   $clnotificaarratipodoc->k101_tipo		  = $oPost->k101_tipo;
   $clnotificaarratipodoc->incluir(null);
	
   if ($clnotificaarratipodoc->erro_status == 0){
	 $sMsgErro = $clnotificaarratipodoc->erro_msg;		
	 $lSqlErro = true;	
   }
	
   db_fim_transacao($lSqlErro);
   

} else if (isset($oPost->alterar)) {
	
   db_inicio_transacao();
	
   $clnotificaarratipodoc->k101_db_documento = $oPost->k101_db_documento; 
   $clnotificaarratipodoc->k101_tipo		 = $oPost->k101_tipo;
   $clnotificaarratipodoc->alterar($oPost->k101_sequencial);
	
   if ($clnotificaarratipodoc->erro_status == 0){
	 $sMsgErro = $clnotificaarratipodoc->erro_msg;		
	 $lSqlErro = true;	
   }
	
   db_fim_transacao($lSqlErro);

} else if (isset($oPost->excluir)) {
	
   db_inicio_transacao();

   $clnotificaarratipodoc->excluir($oPost->k101_sequencial);
	
   if ($clnotificaarratipodoc->erro_status == 0){
	 $sMsgErro = $clnotificaarratipodoc->erro_msg;		
	 $lSqlErro = true;	
   }
	
   db_fim_transacao($lSqlErro);

} else if (isset($oPost->opcao) && ( $oPost->opcao == "alterar" || $oPost->opcao == "excluir") ) {
	
   $rsArretipoDoc = $clnotificaarratipodoc->sql_record($clnotificaarratipodoc->sql_query($oPost->k101_sequencial,"*",null,""));
   
   if ($clnotificaarratipodoc->numrows > 0) {
   	  $oArretipoDoc = db_utils::fieldsMemory($rsArretipoDoc,0); 
   	 
   	  $k101_sequencial   = $oArretipoDoc->k101_sequencial;
   	  $k101_db_documento = $oArretipoDoc->k101_db_documento;
   	  $descrDocumento    = $oArretipoDoc->db03_descr;
   	  $k101_tipo		 = $oArretipoDoc->k101_tipo;
   	  $descrTipo		 = $oArretipoDoc->k00_descr;
   
   }
   
   if ( $oPost->opcao == "alterar"){
     $db_opcao = 2;
   } else {
	 $db_opcao = 3;
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
  <form name="form1" method="post" action="">
  <table style="padding-top:15px;">
    <tr> 
      <td> 
		<fieldset>
		  <table align="center">
		    <tr>
		      <td>
		      	<?
				  db_ancora($Lk101_db_documento,"js_pesquisaDoc(true);",$db_opcao);
		      	?>
		      </td>
		      <td>
		        <?
				  db_input("k101_sequencial",10,"",true,"hidden",1,"");
		          db_input("k101_db_documento",10,$Ik101_db_documento,true,"text",$db_opcao,"onChange='js_pesquisaDoc(false);'");
				  db_input("descrDocumento",40,"",true,"text",3,"");
		        ?>
		      </td>
		    </tr>
		    <tr>
		      <td>
		      	<?
				  db_ancora($Lk101_tipo,"js_pesquisaTipo(true);",$db_opcao);
		      	?>
		      </td>
		      <td>
		        <?
				  db_input("k101_tipo",10,$Ik101_db_documento,true,"text",$db_opcao,"onChange='js_pesquisaTipo(false);'");
				  db_input("descrTipo",40,"",true,"text",3,"");
		        ?>
		      </td>
		    </tr>
		  </table>
		</fieldset>
      </td>
    </tr>
    <tr align="center">
      <td>
	    <input name=<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?> type="submit" value=<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>>	
	  </td>
    </tr>
	<tr>
	  <td valign="top"  align="center">  
	    <?
		  $chavepri= array("k101_sequencial"=>@$k101_sequencial);
		  
		  $cliframe_alterar_excluir->chavepri	    = $chavepri;
		  $cliframe_alterar_excluir->sql     	    = $clnotificaarratipodoc->sql_query(null,"*",null,"");
		  $cliframe_alterar_excluir->campos  	    = "k101_db_documento,db03_descr,k101_tipo,k00_descr";
		  $cliframe_alterar_excluir->legenda		= "Tipos Cadastrados";
		  $cliframe_alterar_excluir->iframe_height  = "160";
		  $cliframe_alterar_excluir->iframe_width   = "700";
		  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	    ?>
	  </td>
	</tr>
  </table>
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  
  function js_pesquisaDoc(lMostra) {
  	if (lMostra) {
	  js_OpenJanelaIframe("top.corpo","db_iframe_documentos","func_db_documento.php?funcao_js=parent.js_preencheDoc|db03_docum|db03_descr","Pesquisa",true);  	
  	} else {
  	  js_OpenJanelaIframe("top.corpo","db_iframe_documentos","func_db_documento.php?funcao_js=parent.js_preencheDoc1&pesquisa_chave="+document.form1.k101_db_documento.value,"Pesquisa",false);
  	}
  }
  
  function js_preencheDoc(iChave,sChave){
  	document.form1.k101_db_documento.value = iChave;
  	document.form1.descrDocumento.value	   = sChave;
    db_iframe_documentos.hide();
  }
  
  function js_preencheDoc1(sChave,lErro){
	document.form1.descrDocumento.value	 = sChave;
    if(lErro){
      document.form1.k101_db_documento.focus();
      document.form1.descrDocumento.value = "";
    } 
  	db_iframe_documentos.hide();
  } 
 
 
  function js_pesquisaTipo(lMostra) {
  	if (lMostra) {
	  js_OpenJanelaIframe("top.corpo","db_iframe_tipo","func_arretipo.php?funcao_js=parent.js_preencheTipo|k00_tipo|k00_descr","Pesquisa",true);  	
  	} else {
  	  js_OpenJanelaIframe("top.corpo","db_iframe_tipo","func_arretipo.php?funcao_js=parent.js_preencheTipo1&pesquisa_chave="+document.form1.k101_tipo.value,"Pesquisa",false);
  	}
  }
 
 function js_preencheTipo(iChave,sChave){
  	document.form1.k101_tipo.value = iChave;
  	document.form1.descrTipo.value = sChave;
    db_iframe_tipo.hide();
  }
  
  function js_preencheTipo1(sChave,lErro){
	document.form1.descrTipo.value = sChave;
    if(lErro){
      document.form1.k101_tipo.focus();
      document.form1.descrTipo.value = "";
    } 
  	db_iframe_tipo.hide();
  } 

</script>
<?
  if (isset($oPost->incluir)) {
    if($lSqlErro){
	  db_msgbox($sMsgErro);
  	}
  }
?>