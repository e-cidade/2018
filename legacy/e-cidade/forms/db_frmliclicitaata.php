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

include("classes/db_cflicita_classe.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcflicita = new cl_cflicita;
$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc50_descr");
$clrotulo->label("nome");
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
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){     
     
   }
}
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<fieldset>
  <legend>
    <b>Upload de Atas</b>
  </legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tl20_codigo?>">
	      <b>Cod. Licitação:</b>
	    </td>
	    <td> 
				<?
					db_input('l39_sequencial',10,"",true,'hidden',3,"");
					db_input('l20_codigo',10,$Il20_codigo,true,'text',3,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tl20_dataaber?>">
	      <?=@$Ll20_dataaber?>
	    </td>
	    <td> 
				<?
	  			db_inputdata('l20_dataaber',@$l20_dataaber_dia,@$l20_dataaber_mes,@$l20_dataaber_ano,true,'text',3,"");
				?>
				<?=@$Ll20_horaaber?>
				<?
		   		db_input('l20_horaaber',5,$Il20_horaaber,true,'text',3,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tl20_dtpublic?>">
	      <?=@$Ll20_dtpublic?>
	    </td>
	    <td> 
				<?
				  db_inputdata('l20_dtpublic',@$l20_dtpublic_dia,@$l20_dtpublic_mes,@$l20_dtpublic_ano,true,'text',3,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tl20_local?>">
	      <?=@$Ll20_local?>
	    </td>
	    <td> 
				<?
				  db_textarea('l20_local',0,60,$Il20_local,true,'text',3,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tl20_objeto?>">
	      <?=@$Ll20_objeto?>
	    </td>
	    <td> 
				<?
				  db_textarea('l20_objeto',0,60,$Il20_objeto,true,'text',3,"");
				?>
	    </td>
	  </tr> 
	  <tr>
	    <td nowrap title="Ata">
	      <b>Ata :</b>
	    </td>
	    <td> 
				<?
	   	    db_input("arquivoedital",30,0,true,"file",1);
				?>
	    </td>
	  </tr>
	  <tr>
	  <br>
		  <td colspan=2 align=center>
		   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==22?"style='display:none;'":"")?> >
		   <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=(isset($opcao)?"style='display:none;'":"")?> >
		   <input name="novo"      type="button" id="cancelar"  value="Novo"      onclick="js_cancelar();" <?=($db_opcao==1||$db_opcao==22||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
		  </td>
	  </tr>
	</table>
 </fieldset>
<table>
  <tr>
	  <td valign="top"  align="center">  
	    <?
			  $chavepri= array("l39_sequencial"=>@$l39_sequencial);
			  $cliframe_alterar_excluir->chavepri=$chavepri;
			  $cliframe_alterar_excluir->sql     = $clliclicitaata->sql_query(null,"*",null,"l39_liclicita=".@$l20_codigo);
			  $cliframe_alterar_excluir->campos  ="l39_arqnome";
			  $cliframe_alterar_excluir->legenda="Atas Anexadas";
		 	  $cliframe_alterar_excluir->iframe_height ="160";
			  $cliframe_alterar_excluir->iframe_width ="700";
			  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	    ?>
    </td>
  </tr>
</table>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_liclicita.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  ?>
}
</script>