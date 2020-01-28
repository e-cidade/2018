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
include("classes/db_itbitransacaoformapag_classe.php");
include("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clitbitransacaoformapag  = new cl_itbitransacaoformapag(); 
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clitbitransacaoformapag->rotulo->label();

$lSqlErro = false;

if(isset($oPost->incluir)){
	
	
  db_inicio_transacao();
   
  $sWhere  = "     it25_itbitransacao      = {$oPost->it25_itbitransacao} ";
  $sWhere .= " and it25_itbiformapagamento = {$oPost->it25_itbiformapagamento} ";
  
  $rsConsultaForma = $clitbitransacaoformapag->sql_record($clitbitransacaoformapag->sql_query(null,"*",null,$sWhere));

  if ($clitbitransacaoformapag->numrows > 0) {
    $lSqlErro = true;
    $sErroMsg = " Transação {$oPost->it25_itbitransacao} já possui forma de pagamento do tipo {$oPost->it27_descricao}!";
  }  
  
  if ( !$lSqlErro ) {
  	
    $clitbitransacaoformapag->it25_itbiformapagamento = $oPost->it25_itbiformapagamento;
    $clitbitransacaoformapag->it25_itbitransacao	  = $oPost->it25_itbitransacao;
    $clitbitransacaoformapag->incluir(null);
    
    if ( $clitbitransacaoformapag->erro_status == 0 ) {
  	  $lSqlErro = true;
    }
     
    $sErroMsg = $clitbitransacaoformapag->erro_msg;
    
  }
  
  db_fim_transacao($lSqlErro);

  
}else if(isset($oPost->alterar)){
	
	
  db_inicio_transacao();
  
  
  $sWhere  = "     it25_itbitransacao      = {$oPost->it25_itbitransacao}	   ";
  $sWhere .= " and it25_itbiformapagamento = {$oPost->it25_itbiformapagamento} ";
  $sWhere .= " and it25_sequencial		  != {$oPost->it25_sequencial}		   ";
  
  $rsConsultaForma = $clitbitransacaoformapag->sql_record($clitbitransacaoformapag->sql_query(null,"*",null,$sWhere));

  if ($clitbitransacaoformapag->numrows > 0) {
    $lSqlErro = true;
    $sErroMsg = " Transação {$oPost->it25_itbitransacao} já possui forma de pagamento do tipo {$oPost->it27_descricao}!";
  }  
  
  if ( !$lSqlErro ) {  
  
    $clitbitransacaoformapag->it25_itbiformapagamento = $oPost->it25_itbiformapagamento;
    $clitbitransacaoformapag->it25_itbitransacao	  = $oPost->it25_itbitransacao;
    $clitbitransacaoformapag->alterar($oPost->it25_sequencial);
    
    if ( $clitbitransacaoformapag->erro_status == 0 ) {
  	  $lSqlErro = true;
    }

    $sErroMsg = $clitbitransacaoformapag->erro_msg;
    
  }
  
  db_fim_transacao($lSqlErro);
  
  
}else if(isset($oPost->excluir)){
	
	
  db_inicio_transacao();

  $clitbitransacaoformapag->excluir($oPost->it25_sequencial);
    
  if ( $clitbitransacaoformapag->erro_status == 0 ) {
  	$lSqlErro = true;
  }

  $sErroMsg = $clitbitransacaoformapag->erro_msg;
  

  db_fim_transacao($lSqlErro);	

  
	
}else if(isset($oPost->opcao)){
	
  $rsFormaPgto = $clitbitransacaoformapag->sql_record($clitbitransacaoformapag->sql_query($oPost->it25_sequencial));
  if ( $clitbitransacaoformapag->numrows > 0 ) {
    db_fieldsmemory($rsFormaPgto,0);  	
  }
	
} else if(isset($oGet->codTransacao)){

  $it25_itbitransacao = $oGet->codTransacao;
	
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

} 
?>
<form name="form1" method="post" action="">
<table align="center" style="padding-top:15px;" border="0">
  <tr>
    <td>
	    <table border="0" align="center">
	      <tr>
			    <td>
			      <?=$Lit25_itbitransacao?>
			    </td>
	        <td>
	          <?
			        db_input('it25_itbitransacao',10,"",true,'text',3,'');
			      ?>
	        </td>
	      </tr>
	  		<tr>
			    <td nowrap title="<?=@$Tit25_itbiformapagamento?>">
			      <?
				      db_ancora($Lit25_itbiformapagamento,"js_consultaFormaPgto(true)",$db_opcao);		    
			      ?>
			    </td>
			    <td> 
			      <?
			        db_input('it25_sequencial',10,"",true,'hidden',3,'');
			        db_input('it25_itbiformapagamento',10,$Iit25_itbiformapagamento,true,'text',$db_opcao," onchange='js_consultaFormaPgto(false)'");
			        db_input('it27_descricao',40,"",true,'text',3,'');
			      ?>
			    </td>
		    </tr>	
		    <tr>
		      <td nowrap title="<?=@$Tit25_ativo?>">
		        <?=@$Lit25_ativo?>
		      </td>
		      <td>
	          <?
	            $aAtivo = array("t"=>"Sim",
	                            "f"=>"Não");
	            db_select('it25_ativo',$aAtivo,true,1,' style="width: 91"');
	          ?>	      
		      </td>
		    </tr>
	  	</table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
             id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?>  >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
             <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  <tr>
    <td>
      <table>
        <tr>
          <td valign="top"  align="center">  
            <?
              $aChavePri = array( "it25_sequencial"         => @$it25_sequencial,
                                  "it25_itbitransacao"      => @$it25_itbitransacao,
                                  "it25_itbiformapagamento" => @$it25_itbiformapagamento,
                                  "it25_ativo"              => @$it25_ativo );
        
              $cliframe_alterar_excluir->chavepri      = $aChavePri;
              $cliframe_alterar_excluir->sql           = $clitbitransacaoformapag->sql_query(null,"*","it25_sequencial"," it25_itbitransacao = {$it25_itbitransacao}");
              $cliframe_alterar_excluir->campos        ="it25_itbiformapagamento,it27_descricao,it25_ativo";
              $cliframe_alterar_excluir->legenda       ="Formas de Pagamento";
              $cliframe_alterar_excluir->iframe_height = "160";
              $cliframe_alterar_excluir->iframe_width  = "700";
              $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
            ?>
          </td>
        </tr>
      </table>    
    </td>
  </tr>
</table>   
</body>
</html>
</form>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
    db_msgbox($sErroMsg);
    if($clitbitransacaoformapag->erro_campo!=""){
      echo "<script> document.form1.".$clitbitransacaoformapag->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbitransacaoformapag->erro_campo.".focus();</script>";
    } else {
      echo " <script> 																						";
      echo "   location.href='itb1_itbitransacao111.php?codTransacao=$oPost->it25_itbitransacao'; 			";
   	  echo " </script>																						";
    }
}

?>
<script>
function js_consultaFormaPgto(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_itbitransacaoformapagamento','func_itbiformapagamento.php?funcao_js=parent.js_preencheformapgto|it27_sequencial|it27_descricao','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_itbitransacaoformapagamento','func_itbiformapagamento.php?funcao_js=parent.js_preencheformapgto1&pesquisa_chave='+document.form1.it25_itbiformapagamento.value,'pesquisa',false);
  }
}

function js_preencheformapgto(chave,chave1){
  document.form1.it25_itbiformapagamento.value = chave;
  document.form1.it27_descricao.value 		   = chave1;
  db_iframe_itbitransacaoformapagamento.hide();
}

function js_preencheformapgto1(chave,erro){
  document.form1.it27_descricao.value = chave;
  if(erro == true){
    document.form1.it25_itbiformapagamento.focus();
    document.form1.it25_itbiformapagamento.value='';
  }
  db_iframe_itbitransacaoformapagamento.hide();
}

function js_cancelar(){
  document.form1.it25_itbiformapagamento.value = '';
  document.form1.it27_descricao.value          = '';
  document.form1.it25_ativo.value              = 't';
  document.form1.submit();
}
</script>