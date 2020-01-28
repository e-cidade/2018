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
include("classes/db_orcindicaprograma_classe.php");
include("classes/db_orcprograma_classe.php");
include("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clorcindicaprograma = new cl_orcindicaprograma(); 
$clorcprograma    	 = new cl_orcprograma(); 
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir();

$clorcindicaprograma->rotulo->label();

$lSqlErro = false;


if(isset($oPost->incluir)){
	
	
  db_inicio_transacao();
  $sSqlUltimoAno = "select max(o54_anousu) as anomaximo from orcprograma";
  $rsUltimoAno   = $clorcprograma->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->anomaximo;
  for ($iAno = $oPost->o18_anousu;$iAno <= $iUltimoAno; $iAno++) {  
    
    $clorcindicaprograma->o18_anousu 	  = $iAno;
    $clorcindicaprograma->o18_orcindica	  = $oPost->o18_orcindica;
    $clorcindicaprograma->o18_orcprograma = $oPost->o18_orcprograma;
    $clorcindicaprograma->incluir(null);
    $sErroMsg = $clorcindicaprograma->erro_msg;    
    if ( $clorcindicaprograma->erro_status == 0 ) {
    	$lSqlErro = true;
    }
  }
  db_fim_transacao($lSqlErro);

  
  
}else if(isset($oPost->alterar)){
	
	
  db_inicio_transacao();
  
  $clorcindicaprograma->o18_sequencial	= $oPost->o18_sequencial;
  $clorcindicaprograma->o18_anousu 		= $oPost->o18_anousu;
  $clorcindicaprograma->o18_orcindica	= $oPost->o18_orcindica;
  $clorcindicaprograma->o18_orcprograma	= $oPost->o18_orcprograma;  
  
  $clorcindicaprograma->alterar($oPost->o18_sequencial);
  
  if ( $clorcindicaprograma->erro_status == 0 ) {
  	$lSqlErro = true;
  }
  
  $sErroMsg = $clorcindicaprograma->erro_msg;
  
  db_fim_transacao($lSqlErro);
  
  
}else if(isset($oPost->excluir)){
	
	
  db_inicio_transacao();

  $clorcindicaprograma->excluir($oPost->o18_sequencial);
  
  if ( $clorcindicaprograma->erro_status == 0 ) {
  	$lSqlErro = true;
  }

  $sErroMsg = $clorcindicaprograma->erro_msg;
  
  db_fim_transacao($lSqlErro);	

  
	
}else if(isset($oPost->opcao)){
	
  $rsConsultaIndicadores = $clorcindicaprograma->sql_record($clorcindicaprograma->sql_query($oPost->o18_sequencial));
  
  if ($clorcindicaprograma->numrows > 0) {
  	db_fieldsmemory($rsConsultaIndicadores,0);
  }
	

	
} else if(isset($oGet->codprograma)){

  $o18_orcprograma = $oGet->codprograma;
  $o18_anousu	   = $oGet->anousu;
		
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
	
  $db_botao			 = true;
  $db_opcao 		 = 2;
  $db_opcaoPrincipal = 2;
  
}else if(isset($oPost->opcao) && $oPost->opcao=="excluir"){
	
  $db_botao			 = true;
  $db_opcao 		 = 3;
  $db_opcaoPrincipal = 3;
  
} else {
  	
  $db_opcao 		 = 1;
  $db_botao 		 = true;
  	
  if(isset($oPost->novo) || isset($oPost->alterar) ||   isset($oPost->excluir) || (isset($oPost->incluir) && $lSqlErro==false ) ){
    $o18_orcindica 		 = "";    
    $o18_sequencial	  	 = "";
    $o10_descr			 = "";
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
		    <?=$Lo18_orcprograma?>
		  </td>
          <td>
            <?
		      db_input('o18_orcprograma',10,"",true,'text',3,'');
		    ?>
          </td>
        </tr>
  		<tr>
		  <td nowrap title="<?=@$To18_orcindica?>">
		    <?
			  db_ancora($Lo18_orcindica,"js_consultaindica(true)",$db_opcao);		    
		    ?>
		  </td>
		  <td> 
		    <?
		      db_input('o18_sequencial',10,"",true,'hidden',3,'');
		      db_input('o18_anousu',10,"",true,'hidden',3,'');
		      
		      db_input('o18_orcindica' ,10,$Io18_orcindica,true,'text',$db_opcao," onchange='js_consultaindica(false)'");
		      db_input('o10_descr',40,"",true,'text',3,'');
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
			  $aChavePri = array( "o18_sequencial"  => @$o18_sequencial,
			  					  "o18_orcprograma" => @$o18_orcprograma,
			  					  "o18_orcindica" 	=> @$o18_orcindica );
			  
			  $sWhere  = " 	   o18_orcprograma = ".@$o18_orcprograma;
			  $sWhere .= " and o18_anousu  	   = ".@$o18_anousu;
			  
			  $cliframe_alterar_excluir->chavepri	   = $aChavePri;
			  $cliframe_alterar_excluir->sql   		   = $clorcindicaprograma->sql_query(null,"*","o18_sequencial",$sWhere);						
			  $cliframe_alterar_excluir->campos  	   ="o18_orcindica,o10_descr";
			  $cliframe_alterar_excluir->legenda 	   ="Indicadores";
			  $cliframe_alterar_excluir->iframe_height = "160";
			  $cliframe_alterar_excluir->iframe_width  = "700";
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

    if($clorcindicaprograma->erro_campo!=""){
	      echo "<script> document.form1.".$clorcindicaprograma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	      echo "<script> document.form1.".$clorcindicaprograma->erro_campo.".focus();</script>";
    }
}

?>
<script>

function js_consultaindica(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_orcindica','func_orcindica.php?funcao_js=parent.js_preencheorcindica|o10_indica|o10_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_orcindica','func_orcindica.php?funcao_js=parent.js_preencheorcindica1&pesquisa_chave='+document.form1.o18_orcindica.value,'pesquisa',false);
  }
}

function js_preencheorcindica(chave,chave1){
  document.form1.o18_orcindica.value = chave;
  document.form1.o10_descr.value 	 = chave1;
  db_iframe_orcindica.hide();
}

function js_preencheorcindica1(chave,erro){
  document.form1.o10_descr.value = chave;
  if(erro == true){
    document.form1.o18_orcindica.focus();
    document.form1.o18_orcindica.value='';
  }
  db_iframe_orcindica.hide();
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