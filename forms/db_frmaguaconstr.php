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

//MODULO: agua
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claguaconstr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x01_numcgm");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
      
      $x11_codconstr   = "";
      $x11_numero      = "";
      $x11_complemento = "";
      $x11_area        = "";
      $x11_pavimento   = "";
      $x11_qtdfamilia  = "";
      $x11_qtdpessoas  = "";
   }
} 
?>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Imóveis/Terrenos - Construções</b></legend>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
  	  <tr>
        <td nowrap title="<?=@$Tx11_codconstr?>">
          <?=@$Lx11_codconstr?>
    	</td>
    	<td> 
		  <?
			db_input('x11_codconstr',10,$Ix11_codconstr,true,'text',3,"");
		  ?>
    	</td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Tx11_matric?>">
          <?=@$Lx11_matric?>
    	</td>
    	<td> 
		  <?
			db_input('x11_matric',10,$Ix11_matric,true,'text',3," onchange='js_pesquisax11_matric(false);'");
		  ?>
	    </td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Tx11_numero?>">
       	  <?=@$Lx11_numero?>
    	</td>
    	<td> 
		  <?
			db_input('x11_numero',10,$Ix11_numero,true,'text',$db_opcao,"");
		  ?>
    	</td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Tx11_complemento?>">
       	  <?=@$Lx11_complemento?>
    	</td>
    	<td> 
		  <?
			db_input('x11_complemento',20,$Ix11_complemento,true,'text',$db_opcao,"");
		  ?>
    	</td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Tx11_area?>">
       	  <?=@$Lx11_area?>
    	</td>
    	<td> 
		  <?
			db_input('x11_area',10,$Ix11_area,true,'text',$db_opcao,"");
		  ?>
  	    </td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Tx11_pavimento?>">
       	  <?=@$Lx11_pavimento?>
    	</td>
    	<td> 
	   	  <?
		 	db_input('x11_pavimento',20,$Ix11_pavimento,true,'text',$db_opcao,"");
	   	  ?>
    	</td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Tx11_qtdfamilia?>">
       	  <?=@$Lx11_qtdfamilia?>
    	</td>
    	<td> 
		  <?
			db_input('x11_qtdfamilia',5,$Ix11_qtdfamilia,true,'text',$db_opcao,"");
		  ?>
  	    </td>
  	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tx11_qtdpessoas?>">
    	  <?=@$Lx11_qtdpessoas?>
    	</td>
    	<td> 
		  <?
			db_input('x11_qtdpessoas',5,$Ix11_qtdpessoas,true,'text',$db_opcao,"");
		  ?>
    	</td>
  	  </tr>
	  <tr>
    	<td nowrap title="<?=@$Tx11_tipo?>">
      	  <?=@$Lx11_tipo?>
    	</td>
    	<td> 
	  	  <?
		    $aTipoConstr = array("P"=>"Principal","S"=>"Secundária");
		  	db_select("x11_tipo",$aTipoConstr,true,$db_opcao,"");
//		  	db_select();
		  ?>
    	</td>
  	  </tr>
	  <tr>
   	    <td>
    	  <b>
			<?
  			  db_ancora("Caracteristicas","js_mostracaracteristica();",1);
			?>
    	  </b>
   		</td>
   		<td>
		  <?
  		    db_input('caracteristica',15,1,true,'hidden',1,"");
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
 	<table>
  	  <tr>
	    <td valign="top"  align="center">  
	      <?
	      
	        $sCampos  = " x11_codconstr,   ";
	        $sCampos .= " x11_matric, 	   ";
	        $sCampos .= " case 			   ";
	        $sCampos .= "   when x11_tipo = 'P' then 'Principal' else 'Secundária' ";
	        $sCampos .= " end as x11_tipo, ";
	        $sCampos .= " x11_numero, 	   ";
	        $sCampos .= " x11_complemento, ";
	        $sCampos .= " x11_area, 	   ";
	        $sCampos .= " x11_pavimento,   ";
	        $sCampos .= " x11_qtdfamilia,  ";
	        $sCampos .= " x11_qtdpessoas   ";
	        
		 	$chavepri= array("x11_codconstr"=>@$x11_codconstr);
		 	$cliframe_alterar_excluir->chavepri=$chavepri;
		 	$cliframe_alterar_excluir->sql     = $claguaconstr->sql_query(null,$sCampos,"x11_numero", "aguaconstr.x11_matric=".$x11_matric);
		 	$cliframe_alterar_excluir->campos  = "x11_codconstr,x11_matric,x11_tipo,x11_numero,x11_complemento,x11_area,x11_pavimento,x11_qtdfamilia,x11_qtdpessoas";
		 	$cliframe_alterar_excluir->legenda="CONSTRUÇÕES";
		 	$cliframe_alterar_excluir->iframe_height ="160";
		 	$cliframe_alterar_excluir->iframe_width ="700";
		 	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	      ?>
    	</td>
   	  </tr>
 	</table>
  </center>
</form>
<script>
function js_cancelar(){
	document.form1.caracteristica.value = '';
	
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();

}
function js_pesquisax11_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguaconstr','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x11_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaconstr','db_iframe_aguabase','func_aguabase.php?pesquisa_chave='+document.form1.x11_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false);
     }else{
       document.form1.x01_numcgm.value = ''; 
     }
  }
}
function js_mostraaguabase(chave,erro){
  document.form1.x01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.x11_matric.focus(); 
    document.form1.x11_matric.value = ''; 
  }
}
function js_mostraaguabase1(chave1,chave2){
  document.form1.x11_matric.value = chave1;
  document.form1.x01_numcgm.value = chave2;
  db_iframe_aguabase.hide();
}

function js_mostracaracteristica(){
  caracteristica=document.form1.caracteristica.value;

  if(caracteristica == '') {
	  caracteristica = 'X';
  }
  js_OpenJanelaIframe('top.corpo.iframe_aguaconstr','db_iframe','cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=C','Pesquisa',true,0);

}

</script>