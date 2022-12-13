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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_liclicita_classe.php");
include("classes/db_editaltemplategeral_classe.php");

$oPost = db_utils::postMemory($_POST);

$clrotulo              = new rotulocampo();
$clLiclicita           = new cl_liclicita();
$clEditalTemplateGeral = new cl_editaltemplategeral();

$clrotulo->label("l20_codigo");

$lListaModelos = false;
$lGeraEdital   = false;

if ( isset($oPost->l20_codigo) && trim($oPost->l20_codigo) != '' ) {
               
  $lListaModelos = true;
  $lGeraEdital   = true;
  	
  $sCamposModelos        = "db82_sequencial, ";
  $sCamposModelos       .= "db82_descricao   ";
  
  $rsTemplateModalidade  = $clLiclicita->sql_record($clLiclicita->sql_query_modelos($oPost->l20_codigo,$sCamposModelos));

  if ( $clLiclicita->numrows > 0 ) {
  	
  	$rsModelos = $rsTemplateModalidade; 
  	
  } else {

  	$rsTemplateGeral = $clEditalTemplateGeral->sql_record($clEditalTemplateGeral->sql_query(null,$sCamposModelos));
  	
  	if ( $clEditalTemplateGeral->numrows > 0 ) {
  		$rsModelos = $rsTemplateGeral; 
  	} else {
  		$lListaModelos = false;
  		$lGeraEdital   = false;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="form1" method="post" action="">
	<table align="center" style="padding-top:25px">
	  <tr> 
	    <td>
	      <fieldset>
	        <legend align="center">
	          <b>Geração de Editais</b>
	        </legend>
	        <table> 
					  <tr> 
					    <td nowrap title="<?=$Tl20_codigo?>">
						    <b>
						    <?
						      db_ancora('Licitação:',"js_pesquisa_liclicita(true);",1);
						    ?>
						    </b> 
					    </td>
					    <td nowrap>
					      <? 
					        db_input("l20_codigo",8,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
			          ?>
					    </td>
					  </tr>
					  <? if ( $lListaModelos ) { ?>
					   <tr>
					     <td>
					       <b>Modelos:</b>
					     </td>
               <td>
                 <?
			              db_selectrecord('documentotemplate',$rsModelos,true,1,'');
                 ?>
               </td>					     
					   </tr> 
					  <? } ?>
	        </table>
	      </fieldset>
	    </td>
	  </tr>
	  <tr align="center">
	    <td>
	      <input type="button" name="gerar" value="Gerar Edital" <?=(!$lGeraEdital?"disabled":"")?> onClick="js_geraEdital()"/>
	    </td>
	  </tr>
	</table>
</form>
<? 
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  function js_geraEdital(){
  
    var sQuery  = '?iLicitacao='+document.form1.l20_codigo.value;
        sQuery += '&iCodDocumento='+document.form1.documentotemplate.value;
  
    jan = window.open('lic4_geracaoeditais002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    
  }

	function js_pesquisa_liclicita(mostra){
	
	  if( mostra ){
	    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
	  } else {
	    if(document.form1.l20_codigo.value != ''){ 
	      js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
	    } else {
        document.form1.l20_codigo.value = ''; 
	    }
	  }
	  
	}
	
	function js_mostraliclicita(chave,erro){
	  document.form1.l20_codigo.value = chave; 
	  if( erro ){ 
	    document.form1.l20_codigo.value = ''; 
	    document.form1.l20_codigo.focus(); 
	  } else {
	    document.form1.submit();
	  }
	}
	
	function js_mostraliclicita1(chave1){
	   document.form1.l20_codigo.value = chave1;  
	   db_iframe_liclicita.hide();
	   document.form1.submit();
	}
	
</script>