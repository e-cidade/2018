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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("v56_codsit");
$clrotulo->label("v52_descr");
$clrotulo->label("v50_data");
$clrotulo->label("v50_advog");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("v53_descr");
$clrotulo->label("v70_vara");
$instit=db_getsession("DB_instit");
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  		<meta http-equiv="Expires" CONTENT="0">
  		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  		<script>
  			
  			function js_emite(){
  			  				
  			  var F = document.form1; 				
  			  var sQuery  	 = "";
  			  var sOrigem    = "";
  			  var sSeparador = "";  
  			  var aChkSituacao = js_getElementbyClass(form1,"origem");
  			  
  			  for (var i=0; i < aChkSituacao.length; i++ ) {
  			    if (aChkSituacao[i].checked == true) {
  			  	 sOrigem += sSeparador+aChkSituacao[i].name;
      			 sSeparador = "|";
      			} 
  			  }

			  if ( sOrigem == "" ) {
			  	alert(_M('tributario.juridico.jur2_relinicial001.selecione_origem'));
			  	return false;
			  } 
  			   
  			  sQuery += '?numcgm='+F.z01_numcgm.value;
  			  sQuery += '&nvalminacao='+F.nvalminacao.value;
  			  sQuery += '&nvalmaxacao='+F.nvalmaxacao.value;
  			  sQuery += '&nvalminatu='+F.nvalminatu.value;
  			  sQuery += '&nvalmaxatu='+F.nvalmaxatu.value;
  			  sQuery += '&codsit='+F.v56_codsit.value;
  			  sQuery += '&advog='+F.v50_advog.value;
  			  sQuery += '&codvara='+F.v70_vara.value;
  			  sQuery += '&ordem='+F.ordem.value;
  			  sQuery += '&origem='+sOrigem;  			  
  			  sQuery += '&listar='+F.listar.value;
  			  sQuery += '&selSituacao='+F.selSituacao.value;
  			  sQuery += '&selTipo='+F.selTipo.value;
  			  sQuery += '&dataini='+F.dataini_ano.value+"-"+F.dataini_mes.value+"-"+F.dataini_dia.value;
  			  sQuery += '&datafim='+F.datafim_ano.value+"-"+F.datafim_mes.value+"-"+F.datafim_dia.value;
  				    				
  			  if(F.xdata){
  			    sQuery += '&xdata='+F.xdata.value;
  			  }
  			  
  			  jan = window.open('jur2_relinicial002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  			  jan.moveTo(0,0);
			}
			
  		</script>
  		<link href="estilos.css" rel="stylesheet" type="text/css">
  		<style type="text/css">
  		</style>
	</head>
  	<body bgcolor=#CCCCCC>
		  <form class="container" name="form1" method="post" action="jur3_emiteinicial021.php" onsubmit="return js_testacodforo();">
			<fieldset>
		 	  <legend>Relatórios - Lista de Inicial</legend>	
		  	  <table class="form-container">
  			    <tr>   
      	  		<td title="<?=@$Tz01_numcgm?>">
      				  <? db_ancora($Lz01_numcgm,' js_cgm(true); ',1);?>
       		  	</td>
       		 	  <td> 
      				  <?
      				 	  db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
      					  db_input('z01_nome',48,0,true,'text',3,"","z01_nomecgm");
      				  ?>
      		 	  </td>
      		  </tr>    
				    <tr>
				      <td title="<?=@$Tv56_codsit?>">
      					<?
      				 	  db_ancora(@ $Lv56_codsit, "js_pesquisav56_codsit(true);", "1");
      					?>
				      </td>
				      <td> 
      					<?
      					  db_input('v56_codsit', 10, $Iv56_codsit, true, 'text', "1", "onchange='js_pesquisav56_codsit(false);'");
      					  db_input('v52_descr', 48, $Iv52_descr, true, 'text', 3, '');
      					?>
      			  </td>
      			</tr>
				    <tr>
      			  <td title="<?=@$Tv50_advog?>">
      					<?
      					  db_ancora(@ $Lv50_advog, "js_pesquisav50_advog(true);", 1);
      					?>
      			  </td>
      			  <td>
        				<?
    					    db_input('v50_advog', 10, $Iv50_advog, true, 'text', 1, "onchange='js_pesquisav50_advog(false);'");
    				      db_input('z01_nome', 48, $Iz01_nome, true, 'text', 3, '');
  					    ?>
      			  </td>
    				</tr>  
    				<tr>
    		  	  <td title="<?=@$Tv70_vara?>">
      					<?
      					  db_ancora(@$Lv70_vara,"js_pesquisav70_vara(true);",1);
      					?>
    			    </td>
    		      <td>
        				<?
    			  		  db_input('v70_vara',10,$Iv70_vara,true,'text',1," onchange='js_pesquisav70_vara(false);'");
    				  	  db_input('v53_descr',48,$Iv53_descr,true,'text',3,'');
    					  ?>
    			    </td>
    				</tr>
    				<tr>
				      <td>
				        Listar:
				      </td>
				      <td>
    					  <?
      				  	$tipo_lista = array("t"=>"Todas","p"=>"Somente as que possuem processo no foro","n"=>"Somente as que NÃO possuem processo no foro");
      					  db_select("listar",$tipo_lista,true,1,"");
    				    ?>
				      </td>
			      </tr> 
			 	    <tr>
      		    <td title="<?=@$Tv50_data?>">
					      <?=@$Lv50_data?>
      			  </td>
      		  	<td> 
      					<?
      					  db_inputdata('dataini', "", "", "", true, 'text',1, "")
      					?>
      					  <b style="padding:0px 25px 0px 10px;">até</b>
      					<?
      					  db_inputdata('datafim', "", "", "", true, 'text',1, "")
      					?>
      			  </td>
			 	    <tr>
      		    <td>
                Valor da ação:
      			  </td>
      		  	<td> 
      					<?
      			  	  db_input('nvalminacao',10,4,true,'text',1,"");
      					?>
      					  <b style="padding:0px 25px 0px 42px;">até</b>
      					<?
      			  	  db_input('nvalmaxacao',10,4,true,'text',1,"");
      					?>
      			  </td>
				    </tr>
			 	    <tr>
      		    <td>
                Valor atualizado:
      			  </td>
      		  	<td> 
      					<?
      			  	  db_input('nvalminatu',10,4,true,'text',1,"");
      					?>
      					  <b style="padding:0px 25px 0px 42px;">até</b>
      					<?
      			  	  db_input('nvalmaxatu',10,4,true,'text',1,"");
      					?>
      			  </td>
				    </tr>
				    <tr>
  			  	  <td>
  					    Origem:
  				    </td>
  				    <td>
  				  	  <table>
    				  	  <tr>
    				  	    <td align="right" width="85px">
    				  	      <label><b>Matrícula</b></label>
    				  	    </td>
    				  	    <td>   
    				  	      <input class="origem" name="M" id="chkMat"   type="checkbox" checked="true" onChange="js_marcatodas(false)" >
    				  	    </td>
    				  	    <td align="right" width="140px">
    				  	      <label><b>Inscrição</b></label>
    				  	    </td>
    				  	    <td>   
    				  	      <input class="origem" name="I" id="chkInscr" type="checkbox" checked="true" onChange="js_marcatodas(false)">
    				  	    </td>  				  	    
    				  	  </tr>
    				  	  <tr>
    				  	    <td align="right">
    				  	      <label><b>CGM</b></label>
    				  	    </td>
    				  	    <td> 
    				  	      <input class="origem" name="C" id="chkCgm"   type="checkbox" checked="true" onChange="js_marcatodas(false)">
    				  	    </td>
    				  	    <td align="right">
    				  	      <label><b>Todos</b></label>
    				  	    </td>
    				  	    <td> 
    				  	      <input class="origem" name="T" id="chkTodos" type="checkbox" checked="true" onChange="js_marcatodas(true)">
    				  	    </td>  				  	    
    				  	  </tr>  				  	  
  				  	  </table>
  				    </td>
    				</tr>
    				<tr>
  			  	  <td>
  					    Ordenar por:
  				    </td>
  				    <td>
      				  <?
      				    $tipo_ordem = array("n"=>"Nome","i"=>"Nº Inicial","p"=>"Nº Processo do Foro","v"=>"Vara");
      				    db_select("ordem",$tipo_ordem,true,1,"");
      				  ?>
    				  </td>
    				</tr>
    				<tr>
    				  <td>
    						Tipo:
    				  </td>
    				  <td>
  						  <?
  					  		$aTipo = array("c"=>"Completo","r"=>"Resumido");
  					  		db_select("selTipo",$aTipo,true,1,"");
  				    	?>
    				  </td>
    				</tr>  
				    <tr>
  				    <td>
  					    Status:
  				    </td>
  			  	  <td>
  				    	<?
  		 			  		$aSituacao = array("0"=>"Todas","1"=>"Ativa","2"=>"Anulada");
  					  		db_select("selSituacao",$aSituacao,true,1,"");
  				    	?>
  				  	</td>
    				</tr>
			  </table>
			</fieldset>	
      <input name="processar" type="button" id="processar" value="Processar" onclick='js_emite();' >
		  </form>
	<?
 	db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
	?>
  	</body>
</html>
<script>
function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_pesquisav70_vara(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vara','func_vara.php?funcao_js=parent.js_mostravara1|v53_codvara|v53_descr','Pesquisa',true);
  }else{
     if(document.form1.v70_vara.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vara','func_vara.php?pesquisa_chave='+document.form1.v70_vara.value+'&funcao_js=parent.js_mostravara','Pesquisa',false);
     }else{
       document.form1.v53_descr.value = ''; 
     }
  }
}
function js_mostravara(chave,erro){
  document.form1.v53_descr.value = chave; 
  if(erro==true){ 
    document.form1.v70_vara.focus(); 
    document.form1.v70_vara.value = ''; 
  }
}
function js_mostravara1(chave1,chave2){
  document.form1.v70_vara.value = chave1;
  document.form1.v53_descr.value = chave2;
  db_iframe_vara.hide();
}
function js_pesquisav56_codsit(mostra){
	if(mostra==true){
    	js_OpenJanelaIframe('top.corpo','db_iframe_sit','func_situacao.php?funcao_js=parent.js_mostrasituacao1|0|1','Pesquisa',true);
  	}else{
    	if(document.form1.v56_codsit.value!=""){
      		js_OpenJanelaIframe('top.corpo','db_iframe_sit','func_situacao.php?pesquisa_chave='+document.form1.v56_codsit.value+'&funcao_js=parent.js_mostrasituacao','Pesquisa',false);
    	}
  	}  	
}
function js_mostrasituacao(chave,erro){
	document.form1.v52_descr.value = chave; 
    if(erro==true){ 
    	document.form1.v56_codsit.focus(); 
      	document.form1.v56_codsit.value = ''; 
    }
}
function js_mostrasituacao1(chave1,chave2){
    document.form1.v56_codsit.value = chave1;
    document.form1.v52_descr.value = chave2;
    db_iframe_sit.hide();
}
function js_pesquisav50_advog(mostra){
  	if(mostra==true){
  		js_OpenJanelaIframe('top.corpo','db_iframe_adv','func_advog.php?funcao_js=parent.js_mostracgm3|0|z01_nome','Pesquisa',true);    	
  	}else{
  		if(document.form1.v50_advog.value!=""){
      		js_OpenJanelaIframe('top.corpo','db_iframe_adv','func_advog.php?pesquisa_chave='+document.form1.v50_advog.value+'&funcao_js=parent.js_mostracgm4','Pesquisa',false);
    	}    	
  	}
}
function js_mostracgm4(erro,chave){
  	document.form1.z01_nome.value = chave; 
  	if(erro==true){ 
    	document.form1.v50_advog.focus(); 
    	document.form1.v50_advog.value = ''; 
  	}
}
function js_mostracgm3(chave1,chave2){
  	document.form1.v50_advog.value = chave1;
  	document.form1.z01_nome.value = chave2;
  	db_iframe_adv.hide();
}

function js_marcatodas(lPri){

  var aChkSituacao = js_getElementbyClass(form1,'origem');
  var CampPri      = document.getElementById('chkTodos');

	if (lPri) {
	  if ( CampPri.checked == false ){
		for(var i=0; i < aChkSituacao.length; i++) {
		  aChkSituacao[i].checked = false;
		}
	  }else{	
		for(var i=0; i < aChkSituacao.length; i++) {
		  if (aChkSituacao[i].checked == false) {
			aChkSituacao[i].checked = true;
		  }
		}
      }
	}else{
	  document.getElementById('chkTodos').checked = false;
	}

}


</script>
<script>

$("z01_numcgm").addClassName("field-size2");
$("z01_nomecgm").addClassName("field-size7");
$("v56_codsit").addClassName("field-size2");
$("v52_descr").addClassName("field-size7");
$("v50_advog").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("v70_vara").addClassName("field-size2");
$("v53_descr").addClassName("field-size7");
$("dataini").addClassName("field-size2");
$("datafim").addClassName("field-size2");
$("nvalminacao").addClassName("field-size2");
$("nvalmaxacao").addClassName("field-size2");
$("nvalminatu").addClassName("field-size2");
$("nvalmaxatu").addClassName("field-size2");

</script>