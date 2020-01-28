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
include("classes/db_cfautentmodeloimpressao_classe.php");
include("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clcfautentmodeloimpressao  = new cl_cfautentmodeloimpressao(); 
$cliframe_alterar_excluir   = new cl_iframe_alterar_excluir();

$clcfautentmodeloimpressao->rotulo->label();

$lSqlErro = false;


if(isset($oPost->incluir)){
	
	
  db_inicio_transacao();
  
  
   $sWhere  = " 		db68_cfautent = $oPost->db68_cfautent 																										";
   $sWhere .= " and db66_db_tipomodeloimpressao = ( select db66_db_tipomodeloimpressao 												";
   $sWhere .= "  																			from db_modeloimpressao 																";	
	 $sWhere .= "   																	 where db66_sequencial = {$oPost->db68_modeloimpressao} ) ";
   
   $sSqlVerificaMod = $clcfautentmodeloimpressao->sql_query(null,"*",null,$sWhere);
   $rsValidaModelo  = $clcfautentmodeloimpressao->sql_record($sSqlVerificaMod);

   if ( $clcfautentmodeloimpressao->numrows > 0 ) {
   	 $lSqlErro = true;
   	 $sErroMsg = " Inclusão abortada, já existe o mesmo tipo de modelo cadastrado para essa autenticadora! ";
   }
   
   if ( !$lSqlErro ) {
   	
     $clcfautentmodeloimpressao->db68_cfautent				= $oPost->db68_cfautent;
     $clcfautentmodeloimpressao->db68_modeloimpressao = $oPost->db68_modeloimpressao;
     $clcfautentmodeloimpressao->incluir(null);
    
     if ( $clcfautentmodeloimpressao->erro_status == 0 ) {
  	    $lSqlErro = true;
     }
     
     $sErroMsg = $clcfautentmodeloimpressao->erro_msg;
   }
   
  db_fim_transacao($lSqlErro);
  
}else if(isset($oPost->excluir)){
	
	
  db_inicio_transacao();

  $clcfautentmodeloimpressao->excluir($oPost->db68_sequencial);
    
  if ( $clcfautentmodeloimpressao->erro_status == 0 ) {
  	$lSqlErro = true;
  }

  $sErroMsg = $clcfautentmodeloimpressao->erro_msg;
  

  db_fim_transacao($lSqlErro);	

  
	
}else if(isset($oPost->opcao)){
	
  $rsFormaPgto = $clcfautentmodeloimpressao->sql_record($clcfautentmodeloimpressao->sql_query($oPost->db68_sequencial));
  if ( $clcfautentmodeloimpressao->numrows > 0 ) {
    db_fieldsmemory($rsFormaPgto,0);  	
  }
	
} else if(isset($oGet->idAutent)){

  $db68_cfautent = $oGet->idAutent;
	
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
<?

if(isset($oPost->opcao) && $oPost->opcao=="alterar"){
	
  $db_botao					 = true;
  $db_opcao 				 = 2;
  $db_opcaoPrincipal = 2;
  
}else if(isset($oPost->opcao) && $oPost->opcao=="excluir"){
	
  $db_botao			 = true;
  $db_opcao 		 = 3;
  $db_opcaoPrincipal = 3;
  
} else {
  	
  $db_opcao 		 = 1;
  $db_botao 		 = true;
  	
  if(isset($oPost->novo) || isset($oPost->alterar) ||   isset($oPost->excluir) || (isset($oPost->incluir) && $lSqlErro==false ) ){
    $db68_sequencial 		 	   = "";
    $db68_modeloimpressao	   = "";
    $db66_descricao  		     = "";
  }
} 
?>
<table  align="center" style="padding-top:15px;">
  <tr>
    <td>
      <form name="form1" method="post" action="">
        <table border="0" align="center">
        <tr>
		  <td>
		    <?=$Ldb68_cfautent?>
		  </td>
          <td>
            <?
		      db_input('db68_cfautent',10,"",true,'text',3,'');
		    ?>
          </td>
        </tr>
  		<tr>
		  <td nowrap title="<?=@$Tdb68_modeloimpressao?>">
		    <?
			  db_ancora($Ldb68_modeloimpressao,"js_consultaModelo(true)",$db_opcao);		    
		    ?>
		  </td>
		  <td> 
		    <?
		      db_input('db68_sequencial',10,"",true,'hidden',3,'');
		      db_input('db68_modeloimpressao',10,$Idb68_modeloimpressao,true,'text',$db_opcao," onchange='js_consultaModelo(false)'");
		      db_input('db66_descricao',40,"",true,'text',3,'');
		    ?>
		  </td>
	    </tr>		
  		<tr>
    	  <td colspan="2" align="center">
			<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
		    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
		  </td>
  		</tr>
  	  </table>
 	  <table>
		<tr>
		  <td valign="top"  align="center">  
		    <?
			  $aChavePri = array( "db68_sequencial"    	 => @$db68_sequencial,
			  					 				  "db68_cfautent" 		   => @$db68_cfautent,
			  					 				  "db68_modeloimpressao" => @$db68_modeloimpressao);
			  
			  $cliframe_alterar_excluir->chavepri	     = $aChavePri;
			  $cliframe_alterar_excluir->sql   		     = $clcfautentmodeloimpressao->sql_query(null,"*","db68_sequencial"," db68_cfautent = {$db68_cfautent}");
			  $cliframe_alterar_excluir->campos  	     = "db68_sequencial,db68_modeloimpressao,db66_descricao";
			  $cliframe_alterar_excluir->legenda 	     = "Modelo de Impressão";
			  $cliframe_alterar_excluir->iframe_height = "160";
			  $cliframe_alterar_excluir->iframe_width  = "700";
			  $cliframe_alterar_excluir->opcoes 		   = 3;
			  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		    ?>
		  </td>
   		</tr>
   	    </table>
        </form>
      </td>
    </tr>
  </table>   
</body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
    db_msgbox($sErroMsg);
    if($clcfautentmodeloimpressao->erro_campo!=""){
      echo "<script> document.form1.".$clcfautentmodeloimpressao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfautentmodeloimpressao->erro_campo.".focus();</script>";
    } else {
      echo " <script> 																																		   ";
      echo "   location.href='cai1_cfautentmodimprime.php?idAutent={$oPost->db68_cfautent}'; ";
   	  echo " </script>																																	     ";
    }
}

?>
<script>

function js_consultaModelo(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_modelo','func_db_modeloimpressao.php?funcao_js=parent.js_preenchemodelo|db66_sequencial|db66_descricao','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_modelo','func_db_modeloimpressao.php?funcao_js=parent.js_preenchemodelo1&pesquisa_chave='+document.form1.db68_modeloimpressao.value,'pesquisa',false);
  }
}

function js_preenchemodelo(chave,chave1){
  document.form1.db68_modeloimpressao.value = chave;
  document.form1.db66_descricao.value 		  = chave1;
  db_iframe_modelo.hide();
}

function js_preenchemodelo1(chave,erro){
  document.form1.db66_descricao.value = chave;
  if(erro == true){
    document.form1.db68_modeloimpressao.focus();
    document.form1.db68_modeloimpressao.value='';
  }
  db_iframe_modelo.hide();
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>