<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_db_documento_classe.php");
require_once ('libs/db_libdicionario.php');
$cldbdocumento = new cl_db_documento;
$clcadtipoparc->rotulo->label();
$cldbdocumento->rotulo->label();
$clrotulo =new  rotulocampo;
$clrotulo->label("db03_descr");
      if($db_opcao==1){
 	   $db_action="cai1_cadtipoparc004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="cai1_cadtipoparc005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="cai1_cadtipoparc006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk40_codigo?>">
       <?=@$Lk40_codigo?>
    </td>
    <td> 
<?
db_input('k40_codigo',10,$Ik40_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_descr?>">
       <?=@$Lk40_descr?>
    </td>
    <td> 
<?
db_input('k40_descr',40,$Ik40_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_datalanc?>">
       <?=@$Lk40_datalanc?>
    </td>
    <td> 
<?
if (!isset($k40_datalanc_ano)&&!isset($k40_datalanc_dia)&&!isset($k40_datalanc_mes)){
	$k40_datalanc_ano=date("Y",db_getsession("DB_datausu"));
	$k40_datalanc_dia=date("d",db_getsession("DB_datausu"));
	$k40_datalanc_mes=date("m",db_getsession("DB_datausu"));
}
db_inputdata('k40_datalanc',@$k40_datalanc_dia,@$k40_datalanc_mes,@$k40_datalanc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_dtini?>">
       <?=@$Lk40_dtini?>
    </td>
    <td> 
<?
db_inputdata('k40_dtini',@$k40_dtini_dia,@$k40_dtini_mes,@$k40_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_dtfim?>">
       <?=@$Lk40_dtfim?>
    </td>
    <td> 
<?
db_inputdata('k40_dtfim',@$k40_dtfim_dia,@$k40_dtfim_mes,@$k40_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_todasmarc?>">
       <?=@$Lk40_todasmarc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k40_todasmarc',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_permvalparc?>">
       <?=@$Lk40_permvalparc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k40_permvalparc',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  
  
<?
if (!isset($k40_vctopadrao)) {
	$k40_vctopadrao = 0;
}
?>
  <tr>
    <td nowrap title="<?=@$Tk40_vctopadrao?>">
       <?=@$Lk40_vctopadrao?>
    </td>
    <td> 
<?
db_input('k40_vctopadrao',10,$Ik40_vctopadrao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
<?
if (!isset($k40_diapulames)) {
	$k40_diapulames = 0;
}
?>
  <tr>
    <td nowrap title="<?=@$Tk40_diapulames?>">
       <?=@$Lk40_diapulames?>
    </td>
    <td> 
<?
db_input('k40_diapulames',10,$Ik40_diapulames,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
  	<td colspan="2">
  		<fieldset style="width: 450px;"><legend><b>Reparcelamento</b></legend>
  		<table width="100%">
  			<tr>
    			<td nowrap title="<?=@$Tk40_dtreparc?>" width="50%">
       			<?=@$Lk40_dtreparc?>
    			</td>
    			<td width="50%">&nbsp; 
						<?
						if(!isset($k40_dtreparc_dia)){
							$k40_dtreparc_mes = "";
							$k40_dtreparc_ano = "";
							$k40_dtreparc_dia = "";
						}
						db_inputdata("k40_dtreparc",$k40_dtreparc_dia,$k40_dtreparc_mes,$k40_dtreparc_ano,true,'text',1);
						?>
    			</td>
  			</tr>
  			<tr>
    			<td nowrap title="<?=@$Tk40_qtdreparc?>" width="50%">
       			<?=@$Lk40_qtdreparc?>
    			</td>
    			<td width="50%">&nbsp; 
						<?
						db_input('k40_qtdreparc',10,$Ik40_qtdreparc,true,'text',$db_opcao,"")
						?>
    			</td>
  			</tr>
  		</table>
  		</fieldset>
  	</td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_permanula?>">
       <?=@$Lk40_permanula?>
    </td>
    <td> 
			<?
			$x = array('1'=>'Sempre','2'=>'Nunca', '3'=>'Somente sem Pagamentos/Cancelamentos');
			db_select('k40_permanula',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_tipoanulacao?>">
       <?=@$Lk40_tipoanulacao?>
    </td>
    <td> 
      <?
        $x = getValoresPadroesCampo('k40_tipoanulacao');
        db_select('k40_tipoanulacao',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tk40_forma?>">
       <?=@$Lk40_forma?>
    </td>
    <td> 
			<?
			$x = array('1'=>'Normal','2'=>'Juros e Multa na ultima', '3'=>'Loteamento');
			db_select('k40_forma',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk40_aplicacao?>">
       <?=$Lk40_aplicacao?>
    </td>
    <td> 
<?
$aplic = array('1'=>'Antes do Lançamento do Débito','2'=>'Após o Lançamento do Débito');
db_select('k40_aplicacao',$aplic,true,$db_opcao,"");
       ?>
    </td>
  </tr> 
  
  
  <tr>
    <td nowrap title="<?=@$Tdb03_docum?>">
       <b>
       <?
       db_ancora("Documento:","js_pesquisadb03_docum(true);",$db_opcao);
       ?>
       </b>
    </td>
    <td> 
<?
db_input('db03_docum',10,$Idb03_docum,true,'text',$db_opcao," onchange='js_pesquisadb03_docum(false);'")
?>
       <?
db_input('db03_descr',40,$Idb03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
  	<td colspan="2">
  		<fieldset style="width: 475px;"><legend><b>Unificação</b></legend>
  		<table width="100%">
	  		<tr>
			    <td nowrap title="<?=@$Tk40_regraunif?>" width="50%">
			       <?=@$Lk40_regraunif?>
			    </td>
			    <td> 
						<?
						$x = array('1'=>'Permite agrupar origens','2'=>'Permite apenas individual por origem');
						db_select('k40_regraunif',$x,true,$db_opcao,"onchange=js_ddl_regraunif()");
						?>
			    </td>
			  </tr>
			  <? 
			  	$display_id_k40_bloqueio = '';
			  	if(isset($k40_regraunif) && $k40_regraunif == 1){
							$k40_bloqueio = 'false';
							$display_id_k40_bloqueio = 'none';
					}else{
						$display_id_k40_bloqueio = '';
					}
			  ?>
  			<tr style="display: <?=$display_id_k40_bloqueio ?>;" id="id_k40_bloqueio">
    			<td nowrap title="<?=@$Tk40_bloqueio?>" width="50%">
       			<?=@$Lk40_bloqueio?>
    			</td>
    			<td width="50%"> 
						  
       			<?
       			$k40_bloqueio = (isset($k40_bloqueio)&& $k40_bloqueio == 't') ? 'true' : 'false';
						$x = array('false'=>'Não','true'=>'Sim');
						db_select('k40_bloqueio',$x,true,$db_opcao,"");
						?>
    			</td>
  			</tr>
  		</table>
  		</fieldset>
  	</td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" onclick="js_valida('<?=$db_opcao?>')" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="hidden" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_ddl_regraunif(){
	var ddl_regraunif = document.getElementById('k40_regraunif').value;
	if(ddl_regraunif == '2'){
		document.getElementById('id_k40_bloqueio').style.display = '';
	}else{
		document.getElementById('k40_bloqueio').value = 'false';
		document.getElementById('id_k40_bloqueio').style.display = 'none';
		
	}
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_cadtipoparc','db_iframe_cadtipoparc','func_cadtipoparc.php?funcao_js=parent.js_preenchepesquisa|k40_codigo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_cadtipoparc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}


function js_pesquisadb03_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_documento','func_db_documento_alt.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.db03_docum.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_documento','func_db_documento_alt.php?pesquisa_chave='+document.form1.db03_docum.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = ''; 
     }
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.db03_docum.focus(); 
    document.form1.db03_docum.value = ''; 
  }
}
function js_mostradb_documento1(chave1,chave2){
  document.form1.db03_docum.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}

function js_valida(db_opcao){
 if(db_opcao == 1 || db_opcao == 2){
  obj = document.form1;	 	
  data1 = obj.k40_dtini_ano.value+obj.k40_dtini_mes.value+obj.k40_dtini_dia.value;
  data2 = obj.k40_dtfim_ano.value+obj.k40_dtfim_mes.value+obj.k40_dtfim_dia.value;	
  if(data1 > data2){
   alert('Data Inicial maior que a Data Final');	 	
   return false;
  }
 }	
 document.form1.submit();
}

</script>