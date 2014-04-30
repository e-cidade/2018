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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/dbLayoutReader.model.php");
require_once("model/dbLayoutLinha.model.php");
require_once("model/meiArquivo.model.php");

$oPost = db_utils::postMemory($_POST);
$oFile = db_utils::postMemory($_FILES);

if ( isset($oPost->importar) ) {
	
	$lErro = false;
	
	db_inicio_transacao();
	
	try {
		
		$oMei = new MeiArquivo();
		$oMei->importaArquivo($oFile->arquivo['name'],$oFile->arquivo['tmp_name']); 
		
	} catch ( Exception $eException ) {
		$lErro    = true;
		$sMsgErro = $eException->getMessage();
	}
	
	db_fim_transacao($lErro);

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
<center>
  <form name="form1" enctype="multipart/form-data" method="post" action="" >
    <table  style="padding-top:25px;" >
      <tr>
        <td>
  		    <fieldset>
		  	    <legend align="center">
	  	  		  <b>Importação do Aquivo MEI</b>
	  	  		</legend>
    		    <table>
		  			  <tr>
		    		    <td nowrap title="Selecione o Arquivo fonte">
		       			  <b> Arquivo :</b>
		      			</td>
		    		    <td> 
			    			  <?
			      				db_input('arquivo',35,'',true,'file',1,"");
			    			  ?>
		    		    </td>
				      </tr>
			   	  </table>
		      </fieldset>
		    </td>
	    </tr>
		  <tr align="center">
		  	<td>
	  		  <input type="submit" name="importar" value="Importar" onClick="return js_validaImportacao();" />
		  	</td>
		  </tr>
	  </table>	  	
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
  function js_validaImportacao(){
    
    if ( document.form1.arquivo.value == '' ) {
      alert('Nenhum arquivo informado!');
      return false;
    } else {
      return true;    
    }
  
  }
</script>
</html>
<?
  if ( isset($oPost->importar) ) {
		if ($lErro) {
		  db_msgbox(str_replace("\n","\\n",$sMsgErro));	 
		} else {
		  db_msgbox("Importação feita com sucesso!");
		}
  }	
?>