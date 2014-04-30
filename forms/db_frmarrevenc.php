<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: caixa

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clarrevenc->rotulo->label();
$clarrevenclog->rotulo->label();
 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
    <td nowrap>
       
    </td>
    <td> 
			 <?
				db_input('k75_sequencial',10,"",true,'hidden',3);
				db_input('k00_sequencial',10,"",true,'hidden',3);
       ?>
    </td>
  </tr>
	
	  <tr>
    <td nowrap title="<?=@$Tk75_usuario?>">
       <b>Usuário:</b>
    </td>
    <td> 
			 <?
				
				db_input('k75_usuario',10,$Ik75_usuario,true,'text',3);
				
				db_input('login',40,"",true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk75_data?>">
       <?=@$Lk75_data?>
    </td>
    <td> 
<?

db_inputdata('k75_data',@$k75_data_dia,@$k75_data_mes,@$k75_data_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk75_hora?>">
       <?=@$Lk75_hora?>
    </td>
    <td> 
<?

db_input('k75_hora',10,$Ik75_hora,true,'text',3,"")
?>
    </td>
  </tr>
	
  <tr>
    <td nowrap title="<?=@$Tk00_numpre?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?=@$Lk00_numpre?>
    </td>
    <td> 
<?
db_input('k00_numpre',8,$Ik00_numpre,true,'text',$db_opcaonumpre,"onChange= 'js_ajaxRequest(document.form1.k00_numpre.value);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_numpar?>">
       <?=@$Lk00_numpar?>
    </td>
    <td> 
<?
$aParcelas = array("0" => "Todas as parcelas");
db_select('k00_numpar',$aParcelas,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_dtini?>">
       <?=@$Lk00_dtini?>
    </td>
    <td> 
<?
db_inputdata('k00_dtini',@$k00_dtini_dia,@$k00_dtini_mes,@$k00_dtini_ano,true,'text',$db_opcao,"onChange='js_validaData();'","","","","","","js_validaData();")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_dtfim?>">
       <?=@$Lk00_dtfim?>
    </td>
    <td> 
<?
db_inputdata('k00_dtfim',@$k00_dtfim_dia,@$k00_dtfim_mes,@$k00_dtfim_ano,true,'text',$db_opcao,"onChange='js_validaData();'","","","","","","js_validaData();")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_obs?>">
       <?=@$Lk00_obs?>
    </td>
    <td> 
<?
db_textarea('k00_obs',3,40,$Ik00_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

<table width="90%" border="0">
    <tr>
        <td>
        	<?

   $sql = " select * from arrevenc where k00_arrevenclog = ".@$k75_sequencial;
	 
   $chavepri = array("k00_sequencial"=>@$k00_sequencial);
   $cliframe_alterar_excluir->chavepri      = $chavepri;
   if(isset($botaoiframe) and $botaoiframe!=""){
	    $cliframe_alterar_excluir->opcoes        = 3;
	 }else{
	 	  $cliframe_alterar_excluir->opcoes        = 1;
	 }
   $cliframe_alterar_excluir->sql           = $sql;
   $cliframe_alterar_excluir->campos        = "k00_sequencial,k00_numpre,k00_numpar,k00_dtini,k00_dtfim,k00_obs";
   $cliframe_alterar_excluir->legenda       = "Prorrogados";
   $cliframe_alterar_excluir->iframe_height = "180";
   $cliframe_alterar_excluir->iframe_width  = "800";
	 $cliframe_alterar_excluir->iframe_alterar_excluir(1);
	 
  


?>
        </td>
    </tr>
</table>




</form>
<script>
db_opcao = <?=$db_opcao;?>;	
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_arrevenc','func_arrevenclog.php?funcao_js=parent.js_preenchepesquisa|k75_sequencial','Pesquisa',true);
}


document.form1.k00_numpar.disabled = true;

function js_ajaxRequest(iNumpre) {
	
  if(iNumpre!=""){
		
		js_divCarregando('Buscando Parcelas','div_processando');
    var objParcelas = document.form1.k00_numpar;
    var url       = 'cai4_consnumpreRPC.php';
    var parametro = "json={inumpre:"+iNumpre+"}";
    var objAjax   = new Ajax.Request (url,{ 
                                           method:'post',
                                           parameters:parametro, 
                                           onComplete:carregaDadosSelect
                                         }
                                    );
  } else {
    $('k00_numpar').length   = 1;
    $('k00_numpar').disabled = true;

  }
	
}

function carregaDadosSelect(oResposta) {
	
	eval('var aParcelas = '+oResposta.responseText);

	var objParcelas = document.form1.k00_numpar;

	if (aParcelas != '') {
  	objParcelas.length = 0;
	 	for (var i =0; i < aParcelas.length; i++) {
			if (i == 0) {
	  	  objParcelas.options[i] = new Option();
	  	  objParcelas.options[i].value = aParcelas[i].k00_numpar.urlDecode();
	  	  objParcelas.options[i].text  = 'Todas as parcelas';
	    } else {
	  	 objParcelas.options[i] = new Option();
	  	 objParcelas.options[i].value = aParcelas[i].k00_numpar.urlDecode();
	  	 objParcelas.options[i].text = aParcelas[i].k00_numpar.urlDecode();
	    }
  	}
		objParcelas.disabled = false;
		<?
		
		if(isset($k00_numpar) && $k00_numpar!=""){
			echo " document.form1.k00_numpar.value = $k00_numpar; ";
		}
		if(isset($alterar) || isset($incluir) ){
			echo " document.form1.k00_numpar.value = 0 ; ";
		}
		
		?>

  	objParcelas.disabled = false;

  }else{
		objParcelas.disabled = true;
  	$('k00_numpre').value="";  // = 		document.form1.k00_numpre.value = "";
		alert('Numpre inválido');
	}
	js_removeObj('div_processando');
}

function js_validaData(){

	dDataInicial = document.form1.k00_dtini.value;
	dDataFinal   = document.form1.k00_dtfim.value;

	if (dDataInicial == '' || dDataFinal == '') {
		return false;
	} 
	
	var retorno = js_comparadata(dDataInicial, dDataFinal, "<=");
	
	if(retorno == false){
		alert('Data inicial maior que Data final');
		document.form1.k00_dtfim.value = "";
		document.form1.k00_dtini.value = "";
	}
	
}

function js_preenchepesquisa(chave) {
  db_iframe_arrevenc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}

<?

if(isset($k00_numpre) && $k00_numpre!=""){
	if($db_opcao== 3 || $db_opcao==33){
		echo " document.form1.k00_numpar_select_descr.value = $k00_numpar; ";
	}else{
		 	 echo " js_ajaxRequest($k00_numpre); ";
	}
	 	
}

?>

</script>