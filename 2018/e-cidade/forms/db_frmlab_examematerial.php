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

//MODULO: Laboratório
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
$cllab_examematerial->rotulo->label ();
$clrotulo = new rotulocampo ( );
$clrotulo->label ( "la15_c_descr" );
$clrotulo->label ( "la11_i_codigo" );
$clrotulo->label ( "la08_i_codigo" );

$db_botao1 = false;
if (isset ( $opcao ) && $opcao == "alterar") {
	$db_opcao = 2;
	$db_botao1 = true;
	$result1 = $cllab_examematerial->sql_record ( $cllab_examematerial->sql_query ( $la19_i_codigo ) );
	db_fieldsmemory ( $result1, 0 );
	if ($cllab_examematerial->numrows > 0) {
		db_fieldsmemory ( $result1, 0 );
	}
} elseif (isset ( $opcao ) && $opcao == "excluir" || isset ( $db_opcao ) && $db_opcao == 3) {
	$db_opcao = 3;
	$db_botao1 = true;
	$result1 = $cllab_examematerial->sql_record ( $cllab_examematerial->sql_query ( $la19_i_codigo ) );
	db_fieldsmemory ( $result1, 0 );
	if ($cllab_examematerial->numrows > 0) {
		db_fieldsmemory ( $result1, 0 );
	}
} else {
	if (isset ( $alterar )) {
		$db_opcao = 2;
		$db_botao1 = true;
		$result1 = $cllab_examematerial->sql_record ( $cllab_examematerial->sql_query ( $la19_i_codigo ) );
		db_fieldsmemory ( $result1, 0 );
		if ($cllab_examematerial->numrows > 0) {
			db_fieldsmemory ( $result1, 0 );
		}
	} else {
		$db_opcao = 1;
	}
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td>
       <tr>
         <td nowrap title="<?=@$Tla19_i_codigo?>">
            <?=@$Lla19_i_codigo?>
         </td>
         <td>
          <?          
          db_input('la19_i_codigo',10,$Ila19_i_codigo,true,'text',3,"");
          ?>
         </td>
         <td rowspan=11>
               <table border="0">
                 <tr>
                    <td valign="top">
                      <fieldset><legend><b>Validade</b></legend>
                      <table  width="100%"  border="0">
                      <tr>
                        <td nowrap align="right" title="<?=@$Tla19_d_inicio?>">
                           <?=@$Lla19_d_inicio?>
                         <?
                          if (! isset ( $la19_d_inicio )) {
                           $vet = explode ( "-", @$la19_d_inicio );
                           @$la19_d_inicio = $vet [2] . "/" . $vet [1] . "/" . $vet [0];
                           @$la19_d_inicio_dia = $vet [2];
                           @$la19_d_inicio_mes = $vet [1];
                           @$la19_d_inicio_ano = $vet [0];
                         }
                           db_inputdata ( 'la19_d_inicio', @$la19_d_inicio_dia, @$la19_d_inicio_mes, @$la19_d_inicio_ano, true, 'text', $db_opcao, "")
                           ?>                           
                        </td>
                      </tr>
                      <tr>
                      <td nowrap align="right" title="<?=@$Tla19_d_fim?>">
                       <?=@$Lla19_d_fim?>
                       <?
                        if (! isset ( $la19_d_fim )) {
                         $vet = explode ( "-", @$la19_d_fim );
                         @$la19_d_fim = $vet [2] . "/" . $vet [1] . "/" . $vet [0];
                         @$la19_d_fim_dia = $vet [2];
                         @$la19_d_fim_mes = $vet [1];
                         @$la19_d_fim_ano = $vet [0];
                        }
                        db_inputdata ( 'la19_d_fim', @$la19_d_fim_dia, @$la19_d_fim_mes, @$la19_d_fim_ano, true, 'text', $db_opcao, "onchange=\"js_validaData();\"","","","parent.js_validaData();");
                        ?>                       
                        </td>
                      </tr>                     
                      </table>
                      </fieldset>
                    </td>
                 </tr>
                 </table>
         </td>
       </tr>
        <tr>
    <td nowrap title="<?=@$Tla19_i_exame?>">
       <?db_ancora ( @$Lla19_i_exame, "js_pesquisala19_i_exame(true);", 3 );?>
    </td>
    <td>
     <?db_input ( 'la19_i_exame', 10, $Ila19_i_exame, true, 'text', 3, "" )?>
     <?db_input ( 'la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '' )?>
    </td>
  </tr>
     <tr>
       <td nowrap title="<?=@$Tla19_i_materialcoleta?>">
       <?db_ancora ( @$Lla19_i_materialcoleta, "js_pesquisala19_i_materialcoleta(true);", $db_opcao );?>
    </td>
    <td>
      <?db_input ( 'la19_i_materialcoleta', 10, $Ila19_i_materialcoleta, true, 'text', $db_opcao, " onchange='js_pesquisala19_i_materialcoleta(false);'" )?>
      <?db_input ( 'la15_c_descr', 50, @$Ila15_c_descr, true, 'text', 3, '' )?>
    </td>
  </tr>
       <tr>
    <td nowrap title="<?=@$Tla19_i_metodo?>">
       <?db_ancora ( @$Lla19_i_metodo, "js_pesquisala19_i_metodo(true);", $db_opcao );?>
    </td>
    <td>
      <?db_input ( 'la19_i_metodo', 10, $Ila19_i_metodo, true, 'text', $db_opcao, " onchange='js_pesquisala19_i_metodo(false);'"  )?>
       <?db_input ( 'la11_c_descr', 50, @$Ila11_c_descr, true, 'text', 3, '' )?>
    </td>
  </tr>
<tr>
    <td nowrap title="<?=@$Tla19_c_amb?>">
       <?=@$Lla19_c_amb?>
    </td>
    <td> 
       <?db_input ( 'la19_c_amb', 20, $Ila19_c_amb, true, 'text', $db_opcao, "" )?>
    </td>
  </tr>
</table>
  </center>


<input
	name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
	<?=($db_botao == false ? "disabled" : "")?> > <input name="cancelar"
	type="submit" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?>>
<table width="100%">
	<tr>
		<td valign="top"><br>
  <?
		$chavepri = array ("la19_i_codigo" => @$la19_i_codigo, "la19_i_materialcoleta" => @$la19_i_materialcoleta, "la19_i_metodo" => @$la19_i_metodo, "la15_c_descr" => @$la15_c_descr, "la11_c_descr" => @$la11_c_descr, "la19_i_exame" => @$la19_i_exame, "la08_c_descr" => @$la08_c_descr, "la19_c_amb" => @$la19_c_amb, "la19_d_inicio" => @$la19_d_inicio, "la19_d_fim" => @$la19_d_fim );
		$cliframe_alterar_excluir->chavepri = $chavepri;
		@$cliframe_alterar_excluir->sql = $cllab_examematerial->sql_query ("", "*", "la08_c_descr","la19_i_exame= $la19_i_exame" );		
		$cliframe_alterar_excluir->campos = "la19_i_codigo,la19_i_materialcoleta,la15_c_descr,la19_i_metodo,la11_c_descr,la19_c_amb,la19_d_inicio,la19_d_fim";
		$cliframe_alterar_excluir->legenda = "Registros";
		$cliframe_alterar_excluir->msg_vazio = "Não foi encontrado nenhum registro.";
		$cliframe_alterar_excluir->textocabec = "#DEB887";
		$cliframe_alterar_excluir->textocorpo = "#444444";
		$cliframe_alterar_excluir->fundocabec = "#444444";
		$cliframe_alterar_excluir->fundocorpo = "#eaeaea";
		$cliframe_alterar_excluir->iframe_height = "200";
		$cliframe_alterar_excluir->iframe_width = "100%";
		$cliframe_alterar_excluir->tamfontecabec = 9;
		$cliframe_alterar_excluir->tamfontecorpo = 9;
		$cliframe_alterar_excluir->formulario = false;
		$cliframe_alterar_excluir->iframe_alterar_excluir ( $db_opcao );
		?>
  </td>
	</tr>
</table>
</center>
</form>
<script>
if(document.form1.la19_i_materialcoleta.value==''){
	   document.form1.la19_i_materialcoleta.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}
function js_pesquisala19_i_materialcoleta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_materialcoleta','func_lab_materialcoleta.php?funcao_js=parent.js_mostralab_materialcoleta1|la15_i_codigo|la15_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la19_i_materialcoleta.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_materialcoleta','func_lab_materialcoleta.php?pesquisa_chave='+document.form1.la19_i_materialcoleta.value+'&funcao_js=parent.js_mostralab_materialcoleta','Pesquisa',false);
     }else{
       document.form1.la15_c_descr.value = ''; 
     }
  }
}
function js_mostralab_materialcoleta(chave,erro){
  document.form1.la15_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la19_i_materialcoleta.focus(); 
    document.form1.la19_i_materialcoleta.value = ''; 
  }
}
function js_mostralab_materialcoleta1(chave1,chave2){
  document.form1.la19_i_materialcoleta.value = chave1;
  document.form1.la15_c_descr.value = chave2;
  db_iframe_lab_materialcoleta.hide();
}
function js_pesquisala19_i_metodo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_metodo','func_lab_metodo.php?funcao_js=parent.js_mostralab_metodo1|la11_i_codigo|la11_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la19_i_metodo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_metodo','func_lab_metodo.php?pesquisa_chave='+document.form1.la19_i_metodo.value+'&funcao_js=parent.js_mostralab_metodo','Pesquisa',false);
     }else{
       document.form1.la11_c_descr.value = ''; 
     }
  }
}
function js_mostralab_metodo(chave,erro){
  document.form1.la11_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la19_i_metodo.focus(); 
    document.form1.la19_i_metodo.value = ''; 
  }
}
function js_mostralab_metodo1(chave1,chave2){
  document.form1.la19_i_metodo.value = chave1;
  document.form1.la11_c_descr.value = chave2;
  db_iframe_lab_metodo.hide();
}

function js_pesquisala19_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la19_i_exame.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?pesquisa_chave='+document.form1.la19_i_exame.value+'&funcao_js=parent.js_mostralab_exame','Pesquisa',false);
     }else{
       document.form1.la08_c_descr.value = ''; 
     }
  }
}
function js_mostralab_exame(chave,erro){
  document.form1.la08_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la19_i_exame.focus(); 
    document.form1.la19_i_exame.value = ''; 
  }
}
function js_mostralab_exame1(chave1,chave2){
  document.form1.la19_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  db_iframe_lab_exame.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_examematerial','func_lab_examematerial.php?funcao_js=parent.js_preenchepesquisa|la19_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_examematerial.hide();
  <?
		if ($db_opcao != 1) {
			echo " location.href = '" . basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] ) . "?chavepesquisa='+chave";
		}
		?>
}

function js_validaData() {
   	data=false;
   		  if(document.form1.la19_d_fim.value != ""  && document.form1.la19_d_inicio.value != "" ){
   				if(document.form1.la19_d_fim.value < document.form1.la19_d_inicio.value){
   					alert("Data final menor que a data inicial");
   					document.form1.la19_d_fim.value = "";
   				    document.form1.la19_d_fim_dia.value = "";
   				    document.form1.la19_d_fim_mes.value = "";
   		        	document.form1.la19_d_fim_ano.value = "";
   		    		data=false;
   				}	
   				
   		  }
   		  return data;
   		}
</script>