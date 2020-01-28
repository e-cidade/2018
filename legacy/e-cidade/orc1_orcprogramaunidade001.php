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
include("classes/db_orcprogramaunidade_classe.php");
include("classes/db_orcprograma_classe.php");
include("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clorcprogramaunidade 	  = new cl_orcprogramaunidade(); 
$clorcprograma     	      = new cl_orcprograma; 
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir();

$clorcprogramaunidade->rotulo->label();

$lSqlErro = false;


if(isset($oPost->incluir)){
	
	
  db_inicio_transacao();
  $sSqlUltimoAno = "select max(o54_anousu) as anomaximo from orcprograma";
  $rsUltimoAno   = $clorcprograma->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->anomaximo;
  for ($iAno = $oPost->o14_anousu;$iAno <= $iUltimoAno; $iAno++) { 
    
    $clorcprogramaunidade->o14_anousu 		= $iAno;
    $clorcprogramaunidade->o14_orgao		= $oPost->o14_orgao;
    $clorcprogramaunidade->o14_orcprograma	= $oPost->o14_orcprograma;
    $clorcprogramaunidade->o14_orcunidade	= $oPost->o14_orcunidade;
    $clorcprogramaunidade->incluir(null);
    $sErroMsg = $clorcprogramaunidade->erro_msg;
    if ( $clorcprogramaunidade->erro_status == 0 ) {
    	$lSqlErro = true;
    }
  }
  
  db_fim_transacao($lSqlErro);

  
  
}else if(isset($oPost->alterar)){
	
	
  db_inicio_transacao();
  
  $clorcprogramaunidade->o14_sequencial		= $oPost->o14_sequencial;
  $clorcprogramaunidade->o14_anousu 		= $oPost->o14_anousu;
  $clorcprogramaunidade->o14_orgao			= $oPost->o14_orgao;
  $clorcprogramaunidade->o14_orcprograma	= $oPost->o14_orcprograma;
  $clorcprogramaunidade->o14_orcunidade	    = $oPost->o14_orcunidade;
  
  $clorcprogramaunidade->alterar($oPost->o14_sequencial);
  
  if ( $clorcprogramaunidade->erro_status == 0 ) {
  	$lSqlErro = true;
  }
  
  $sErroMsg = $clorcprogramaunidade->erro_msg;
  
  db_fim_transacao($lSqlErro);
  
  
}else if(isset($oPost->excluir)){
	
	
  db_inicio_transacao();

  $clorcprogramaunidade->excluir($oPost->o14_sequencial);
  
  if ( $clorcprogramaunidade->erro_status == 0 ) {
  	$lSqlErro = true;
  }

  $sErroMsg = $clorcprogramaunidade->erro_msg;
  
  db_fim_transacao($lSqlErro);	

  
	
}else if(isset($oPost->opcao)){
	
  $rsConsultaUnidade = $clorcprogramaunidade->sql_record($clorcprogramaunidade->sql_query($oPost->o14_sequencial));
  
  if ($clorcprogramaunidade->numrows > 0) {
  	db_fieldsmemory($rsConsultaUnidade,0);
  }
	

	
} else if(isset($oGet->codprograma)){

  $o14_orcprograma = $oGet->codprograma;
  $o14_anousu	   = $oGet->anousu;
		
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
    $o14_orgao 			 = "";    
    $o14_sequencial	 = "";
    $o41_descr			 = "";
    $o14_orcunidade  = "";
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
		    <?=$Lo14_orcprograma?>
		  </td>
          <td>
            <?
		      db_input('o14_orcprograma',10,"",true,'text',3,'');
		    ?>
          </td>
        </tr>
  		<tr>
		  <td nowrap title="<?=@$To14_orgao?>">
		    <?
			  db_ancora($Lo14_orcunidade,"js_pesquisaUnidade(true)",$db_opcao);		    
		    ?>
		  </td>
		  <td> 
		    <?
		      db_input('o14_sequencial',10,"",true,'hidden',3,'');
		      db_input('o14_anousu',10,"",true,'hidden',3,'');
		      db_input('o14_orgao',10,"",true,'hidden',3,'');
		      
		      db_input('o14_orcunidade' ,10,$Io14_orcunidade,true,'text',$db_opcao," onchange='js_pesquisaUnidade(false)'");
		      db_input('o41_descr',40,"",true,'text',3,'');
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
			  $aChavePri = array( "o14_sequencial"  => @$o14_sequencial,
			  					  "o14_orcprograma" => @$o14_orcprograma,
			  					  "o14_orgao" 		=> @$o14_orgao );
			  
			  $sWhere  = " 	   o14_orcprograma = ".@$o14_orcprograma;
			  $sWhere .= " and o14_anousu  	   = ".@$o14_anousu;
			  
			  $cliframe_alterar_excluir->chavepri	   = $aChavePri;
			  $cliframe_alterar_excluir->sql   		   = $clorcprogramaunidade->sql_query(null,"*","o14_sequencial",$sWhere);						
			  $cliframe_alterar_excluir->campos  	   ="o14_orgao,o41_descr";
			  $cliframe_alterar_excluir->legenda 	   ="Unidade";
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

    if($clorcprogramaunidade->erro_campo!=""){
	      echo "<script> document.form1.".$clorcprogramaunidade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	      echo "<script> document.form1.".$clorcprogramaunidade->erro_campo.".focus();</script>";
    }
}

?>
<script>

function js_pesquisaUnidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr|o41_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o14_orcunidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro,chave2,chave3,chave4){
  document.form1.o41_descr.value = chave; 
  document.form1.o14_orgao.value = chave4;
  if(erro==true){ 
    document.form1.o14_orcunidade.focus(); 
    document.form1.o14_orcunidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2,chave3){
  document.form1.o14_orcunidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  document.form1.o14_orgao.value = chave3;
  db_iframe_orcunidade.hide();
}

function js_consultaindica(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_orcindica','func_orcindica?funcao_js=parent.js_preencheorcindica|o10_indica|o10_descr','pesquisa',true);
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