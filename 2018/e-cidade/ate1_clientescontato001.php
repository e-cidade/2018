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

require_once("libs/db_utils.php");
require_once("classes/db_clientescontato_classe.php");
require_once("classes/db_clientes_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clClientes              = new cl_clientes();
$clClienteContato        = new cl_clientescontato();
$clIframeAlterarExcluir  = new cl_iframe_alterar_excluir;

$clClienteContato->rotulo->label();

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;


if( isset($oPost->incluir) || isset($oPost->alterar) || isset($oPost->excluir) ){
	
  if ($lSqlErro == false) {

  	db_inicio_transacao();
  	
  	$clClienteContato->at92_cliente  = $oPost->at92_cliente;
  	$clClienteContato->at92_nome     = $oPost->at92_nome;
  	$clClienteContato->at92_obs      = $oPost->at92_obs;
  	$clClienteContato->at92_cargo    = $oPost->at92_cargo;
  	$clClienteContato->at92_telefone = $oPost->at92_telefone;
  	$clClienteContato->at92_email    = $oPost->at92_email;
  	
  	if ( isset($oPost->incluir) ) {
	  	$clClienteContato->incluir(null);
  	} else if ( isset($oPost->alterar) ) {
  	  $clClienteContato->alterar($oPost->at92_sequencial);
  	} else {
  	  $clClienteContato->excluir($oPost->at92_sequencial);
  	}
  	
  	$sMsgErro = $clClienteContato->erro_msg;
    
    if ($clClienteContato->erro_status == 0) {
      $lSqlErro = true;
    }
    
    db_fim_transacao($lSqlErro);
    
  }
  
} else if ( isset($oPost->opcao) ) {
	
	$sSqlDadosContato = $clClienteContato->sql_query_file($oPost->at92_sequencial);
  $rsDadosContato   = $clClienteContato->sql_record($sSqlDadosContato);
  
  if ( $rsDadosContato ) {
	  db_fieldsmemory($rsDadosContato,0);
  }
  
}



if ( isset($oGet->at92_cliente) ) {
 	$at92_cliente = $oGet->at92_cliente;
} else if ( isset($oPost->at92_cliente) ) {
	$at92_cliente = $oPost->at92_cliente;
}

if (isset($db_opcaoal)){
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao=="alterar"){
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao=true;
  
  if(isset($oPost->novo) || 
     isset($oPost->alterar) ||   
     isset($oPost->excluir) || 
    (isset($oPost->incluir) && $lSqlErro == false ) ){
    
    $at92_sequencial = "";
    $at92_obs        = "";
    $at92_nome       = "";
    $at92_telefone   = "";
    $at92_email      = "";
    $at92_cargo      = "";
    
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
	<table align="center">
	  <tr> 
	    <td> 
			  <fieldset>
			    <legend>
			      <b>Dados Contato</b>
			    </legend>
			  	<table>
	          <tr>
	            <td>
	              <?=@$Lat92_sequencial?>
	            </td>        
	            <td>
	              <?
	                db_input('at92_sequencial',10,$Iat92_sequencial,true,'text',3,'');
	                db_input('at92_cliente'   ,10,'',true,'hidden',3,'');
	              ?>
	            </td>  
	          </tr>		  	
		        <tr>
		          <td>
		            <?=@$Lat92_nome?>
		          </td>        
		      		<td>
		      		  <?
		              db_input('at92_nome',50,$Iat92_nome,true,'text',$db_opcao,'');
		      		  ?>
		      		</td>	 
					  </tr>
	          <tr>
	            <td>
	              <?=@$Lat92_cargo?>
	            </td>        
	            <td>
	              <?
	                db_input('at92_cargo',50,$Iat92_cargo,true,'text',$db_opcao,'');
	              ?>
	            </td>  
	          </tr>
            <tr>
              <td>
                <?=@$Lat92_telefone?>
              </td>        
              <td>
                <?
                  db_input('at92_telefone',15,$Iat92_telefone,true,'text',$db_opcao,'');
                ?>
              </td>  
            </tr>
            <tr>
              <td>
                <?=@$Lat92_email?>
              </td>        
              <td>
                <?
                  db_input('at92_email',50,$Iat92_email,true,'text',$db_opcao,'');
                ?>
              </td>  
            </tr>            	          
	          <tr>
	            <td>
	              <?=@$Lat92_obs?>
	            </td>        
	            <td>
	              <?
	                db_textarea('at92_obs',5,50,$Iat92_obs,true,'text',$db_opcao,'');
	              ?>
	            </td>  
	          </tr>          
	          <tr>				  
					    <td colspan="2" align="center">
							  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
							  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
					    </td>
					  </tr>
					</table>
			  </fieldset>
			</td>
		</tr>
		<tr>
		  <td>	  
			  <table>
				  <tr>
				    <td valign="top"  align="center">  
					    <?
					    
					      $aChavePri     = array("at92_sequencial"=>@$at92_sequencial);
					      $sWhereContato = "at92_cliente = {$oGet->at92_cliente} "; 
					      $sSqlContato   = $clClienteContato->sql_query_file(null,"*",null,$sWhereContato);
					      
					      $clIframeAlterarExcluir->chavepri      = $aChavePri;
							  $clIframeAlterarExcluir->sql           = $sSqlContato;
							  $clIframeAlterarExcluir->campos        = "at92_sequencial,at92_nome,at92_cargo";
							  $clIframeAlterarExcluir->legenda       = "Contatos Lançados";
							  $clIframeAlterarExcluir->iframe_height = "160";
							  $clIframeAlterarExcluir->iframe_width  = "500";
							  $clIframeAlterarExcluir->iframe_alterar_excluir($db_opcao);
					    ?>
			      </td>
			    </tr>
	 	    </table>
	  	</td>
	  </tr>
	</table>
</form>
</body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){

 db_msgbox($sMsgErro);
    
  if($clClienteContato->erro_campo!=""){
    echo "<script> document.form1.".$clClienteContato->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clClienteContato->erro_campo.".focus();</script>";
  }
}
?>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>