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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_suspensao_classe.php");
include("classes/db_procjur_classe.php");
include("classes/db_db_usuarios_classe.php");

include("model/suspensaoDebitos.model.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clsuspensao   = new cl_suspensao();
$cldb_usuarios = new cl_db_usuarios();
$clprocjur     = new cl_procjur();
$db_opcao 	   = 2;
$db_botao	   = false;

if ( isset($oPost->alterar)  ) {

  $lSqlErro = false;
 
  db_inicio_transacao();

  $clsuspensao->ar18_procjur = $oPost->ar18_procjur; 
  $clsuspensao->ar18_usuario = $oPost->ar18_usuario; 
  $clsuspensao->ar18_data  	 = $oPost->ar18_data_ano."-".$oPost->ar18_data_mes."-".$oPost->ar18_data_dia;  	 
  $clsuspensao->ar18_hora	 = $oPost->ar18_hora;	 
  $clsuspensao->ar18_obs	 = $oPost->ar18_obs;
  	 
  $clsuspensao->alterar($oPost->ar18_sequencial);
  
  if ( $clsuspensao->erro_status == 0) {
  	$lSqlErro = true;
  	$sMsgErro = $clsuspensao->erro_msg;
  }
  
  db_fim_transacao($lSqlErro);
  
} else if (isset($oGet->chavepesquisa) && trim($oGet->chavepesquisa) != ""){

  $rsSuspensao = $clsuspensao->sql_record($clsuspensao->sql_query($oGet->chavepesquisa));	
  db_fieldsmemory($rsSuspensao,0);
  $db_botao	   = true;
  
} else {
  $db_opcao = 22;
}
  	
$clprocjur->rotulo->label();
$clsuspensao->rotulo->label();
4
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" 		content="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" 			rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table style="padding-top:20px;" align="center">
    <form name="form1" method="post" action="">
    <tr>
  	  <td valign="top">
  	    <fieldset>
  	      <legend align="center">
  	       <b>Alterar Suspensão</b>
  	      </legend>
  	      <table>
  	        <tr>
  	          <td>
  	            <b>Código:</b>
  	          </td>
  	          <td>
  	            <?
				  db_input("ar18_sequencial",10,"",true,"text",3,"");
  	            ?>
  	          </td>
  	        </tr>
		    <tr>
		      <td width="110px;">
		        <b>Processo:</b>
			  </td>
		  	  <td>
		  	    <?
				  
				  $rsProcjur = $clprocjur->sql_record($clprocjur->sql_query_file(null,"v62_sequencial,v62_descricao","v62_sequencial"," v62_situacao = 1 ")); 
				  if ( $clprocjur->numrows > 0 ) {
				  	db_selectrecord("ar18_procjur",$rsProcjur,true,1 ,"style='width:340px;'","","","","",1);
				  } else {
				  	db_msgbox("Não há nenhum processo cadastrado ou ativo!");
		      		echo "<script>parent.document.formatu.pesquisar.click();</script>";		  	
				  }
				  
			  	?>
			  </td>
			</tr>
		   </table>	
		   <table width="100%">
			<tr>
			  <td width="110px;">
			  	<? echo $Lar18_data ?>
			  </td>
			  <td>
			  	<? 
			  	   db_inputdata("ar18_data",@$ar18_data_dia,@$ar18_data_mes,@$ar18_data_ano,true,"text",3); 	  
			  	?>
			  </td>
			  <td  align="right">
			  	<?=@$Lar18_hora?>
			  </td>
			  <td  align="left" width="53px;">
			  	<?
			  	   db_input("ar18_hora",5,$Iar18_hora,true,"text",3,"");
			  	?>
			  </td>
			</tr>
		   </table>	
		   <table>				
			<tr>
			  <td width="110px;">
			  	<? echo $Lar18_obs ?>
			  </td>
			  <td>
			  	<? 
			  	  db_textarea("ar18_obs"  ,3,51,$Iar18_obs,true,"text",1);
			  	?>
			  </td>
			</tr>
		    <tr>
			  <td>
			  	<b>Usuário :</b>
			  </td>
			  <td>
			  	<?
			  	   $rsNomeUsu 	 = $cldb_usuarios->sql_record($cldb_usuarios->sql_query(db_getsession('DB_id_usuario'),"id_usuario,nome",null,""));
			  	   $oNomeUsu  	 = db_utils::fieldsMemory($rsNomeUsu,0);
			  	   $nomeUsu 	 = $oNomeUsu->nome;
			  	   $ar18_usuario = $oNomeUsu->id_usuario;
			  	    
			  	   db_input("ar18_usuario",10,"",true,"hidden",3,"");
			  	   db_input("nomeUsu",54,"",true,"text",3,"");
			  	   
			  	?>
			  </td>
			</tr>
		  </table> 
		  <table align="center"> 
		    <tr>
		      <td>
		      	<input name="alterar"   type="submit" value="Alterar" <?=($db_botao?"":"disabled")?>>
		      	<input name="pesquisar" type="button" value="Pesquisar" onClick='js_pesquisar()' >
		      </td>
		    </tr>
		  </table>
		</fieldset>
	  </td>
    </tr>
    </form>
  </table>
</body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
  function js_pesquisar(){
    js_OpenJanelaIframe('','db_iframe_suspensao','func_suspensao.php?situacao=1&funcao_js=parent.js_mostrasuspensao1|ar18_sequencial','Pesquisa',true);
  }
  
  function js_mostrasuspensao1(iCodSuspensao){
	document.location.href = 'arr4_alterasuspensao001.php?chavepesquisa='+iCodSuspensao;  	
  } 
  
</script>
<?
	if (isset($oPost->alterar)) {

		if($lSqlErro){	
			db_msgbox($sMsgErro);
		} else {
			db_msgbox( "Alteração feita com sucesso!");
			echo "<script>document.location.href = 'arr4_alterasuspensao001.php';</script>";
		}
	
	} else if ($db_opcao == 22) {
		echo "<script>js_pesquisar();</script>";
	}
?>