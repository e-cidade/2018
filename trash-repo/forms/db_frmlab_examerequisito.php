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
$cllab_examerequisito->rotulo->label ();
$clrotulo = new rotulocampo ( );
$clrotulo->label ( "la12_i_codigo" );
$clrotulo->label ( "la08_i_codigo" );
$db_botao1 = false;
if (isset ( $opcao ) && $opcao == "alterar") {
	$db_opcao = 2;
	$db_botao1 = true;
	$result1 = $cllab_examerequisito->sql_record ( $cllab_examerequisito->sql_query ( $la20_i_codigo ) );
	db_fieldsmemory ( $result1, 0 );
	if ($cllab_examerequisito->numrows > 0) {
		db_fieldsmemory ( $result1, 0 );
	}
} elseif (isset ( $opcao ) && $opcao == "excluir" || isset ( $db_opcao ) && $db_opcao == 3) {
	$db_opcao = 3;
	$db_botao1 = true;
	$result1 = $cllab_examerequisito->sql_record ( $cllab_examerequisito->sql_query ( $la20_i_codigo ) );
	db_fieldsmemory ( $result1, 0 );
	if ($cllab_examerequisito->numrows > 0) {
		db_fieldsmemory ( $result1, 0 );
	}
} else {
	if (isset ( $alterar )) {
		$db_opcao = 2;
		$db_botao1 = true;
		$result1 = $cllab_examerequisito->sql_record ( $cllab_examerequisito->sql_query ( $la20_i_codigo ) );
		db_fieldsmemory ( $result1, 0 );
		if ($cllab_examerequisito->numrows > 0) {
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
         <td nowrap title="<?=@$Tla20_i_codigo?>">
            <?=@$Lla20_i_codigo?>
         </td>
         <td>
          <?          
          db_input('la20_i_codigo',10,$Ila20_i_codigo,true,'text',3,"");
          ?>
         </td>
         <td rowspan=11>
               <table border="0">
                 <tr>
                    <td valign="top">
                      <fieldset><legend><b>Validade</b></legend>
                      <table  width="90%"  border="0">
                      <tr>
                        <td nowrap align="right" title="<?=@$Tla20_d_inicio?>">
                           <?=@$Lla20_d_inicio?>
                         <?
                          if (! isset ( $la20_d_inicio )) {
                           $vet = explode ( "-", @$la20_d_inicio );
                           @$la20_d_inicio = $vet [2] . "/" . $vet [1] . "/" . $vet [0];
                           @$la20_d_inicio_dia = $vet [2];
                           @$la20_d_inicio_mes = $vet [1];
                           @$la20_d_inicio_ano = $vet [0]; 
                          }
                          db_inputdata ( 'la20_d_inicio', @$la20_d_inicio_dia, @$la20_d_inicio_mes, @$la20_d_inicio_ano, true, 'text', $db_opcao,"" )
                         ?>                         
                        </td>
                      </tr>
                      <tr>
                      <td nowrap align="right" title="<?=@$Tla20_d_fim?>">
                       <?=@$Lla20_d_fim?>
                       <?
                         if (! isset ( $la20_d_fim )) {
                          $vet = explode ( "-", @$la20_d_fim );
                          @$la20_d_fim = $vet [2] . "/" . $vet [1] . "/" . $vet [0];
                          @$la20_d_fim_dia = $vet [2];
                          @$la20_d_fim_mes = $vet [1];
                          @$la20_d_fim_ano = $vet [0];
                         }
                         db_inputdata ( 'la20_d_fim', @$la20_d_fim_dia, @$la20_d_fim_mes, @$la20_d_fim_ano, true, 'text', $db_opcao, "onchange=\"js_validaData();\"","","","parent.js_validaData();");
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
    <td nowrap title="<?=@$Tla20_i_exame?>">
       <?
              db_ancora ( @$Lla20_i_exame, "js_pesquisala20_i_exame(true);", 3 );
              ?>
    </td>
    <td> 
<?
db_input ( 'la20_i_exame', 10, $Ila20_i_exame, true, 'text', 3, "" )?>
       <?
              db_input ( 'la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '' )?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla20_i_requisito?>">
       <?
              db_ancora ( @$Lla20_i_requisito, "js_pesquisala20_i_requisito(true);", $db_opcao );
              ?>
    </td>
    <td> 
<?
db_input ( 'la20_i_requisito', 10, $Ila20_i_requisito, true, 'text', $db_opcao, " onchange='js_pesquisala20_i_requisito(false);'")?>
       <?
              db_input ( 'la12_c_descr', 50, @$Ila12_c_descr, true, 'text', 3, '' )?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla20_t_descr?>">
       <?=@$Lla20_t_descr?>
    </td>
    <td> 
<?
db_textarea ( 'la20_t_descr', 6, 59, $Ila20_t_descr, true, 'text', $db_opcao, "" )?>
    </td>
  </tr>
</table>
  </center>
<input
	name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
	<?=($db_botao == false ? "disabled" : "")?>> 
	<input name="cancelar"
	type="submit" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?>>
<table width="100%">
	<tr>
		<td valign="top"><br>
  <?
		$chavepri = array ("la20_i_codigo" => @$la20_i_codigo, "la20_i_requisito" => @$la20_i_requisito, "la20_i_exame" => @$la20_i_exame, "la12_c_descr" => @$la12_c_descr, "la08_c_descr" => @$la08_c_descr, "la20_t_descr" => @$la20_t_descr, "la20_d_inicio" => @$la20_d_inicio, "la20_d_fim" => @$la20_d_fim );
		$cliframe_alterar_excluir->chavepri = $chavepri;
		@$cliframe_alterar_excluir->sql = $cllab_examerequisito->sql_query ("", "*", "la12_c_descr","la20_i_exame= $la20_i_exame" );
		$cliframe_alterar_excluir->campos = "la20_i_codigo,la20_i_requisito,la12_c_descr,la20_t_descr,la20_d_inicio,la20_d_fim";
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
if(document.form1.la20_i_requisito.value==''){
	   document.form1.la20_i_requisito.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}
function js_pesquisala20_i_requisito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_requisito','func_lab_requisito.php?funcao_js=parent.js_mostralab_requisito1|la12_i_codigo|la12_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la20_i_requisito.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_requisito','func_lab_requisito.php?pesquisa_chave='+document.form1.la20_i_requisito.value+'&funcao_js=parent.js_mostralab_requisito','Pesquisa',false);
     }else{
       document.form1.la12_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_requisito(chave,erro){
  document.form1.la12_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la20_i_requisito.focus(); 
    document.form1.la20_i_requisito.value = ''; 
  }
}
function js_mostralab_requisito1(chave1,chave2){
  document.form1.la20_i_requisito.value = chave1;
  document.form1.la12_c_descr.value = chave2;
  db_iframe_lab_requisito.hide();
}
function js_pesquisala20_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la20_i_exame.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?pesquisa_chave='+document.form1.la20_i_exame.value+'&funcao_js=parent.js_mostralab_exame','Pesquisa',false);
     }else{
       document.form1.la08_i_c_descr.value = ''; 
     }
  }
}
function js_mostralab_exame(chave,erro){
  document.form1.la08_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la20_i_exame.focus(); 
    document.form1.la20_i_exame.value = ''; 
  }
}
function js_mostralab_exame1(chave1,chave2){
  document.form1.la20_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  db_iframe_lab_exame.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_examerequisito','func_lab_examerequisito.php?funcao_js=parent.js_preenchepesquisa|la20_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_examerequisito.hide();
  <?
		if ($db_opcao != 1) {
			echo " location.href = '" . basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] ) . "?chavepesquisa='+chave";
		}
		?>
}

function js_validaData() {
   	data=false;
   		  if(document.form1.la20_d_fim.value != ""  && document.form1.la20_d_inicio.value != "" ){
   				if(document.form1.la20_d_fim.value < document.form1.la20_d_inicio.value){
   					alert("Data final menor que a data inicial");
   					document.form1.la20_d_fim.value = "";
   				    document.form1.la20_d_fim_dia.value = "";
   				    document.form1.la20_d_fim_mes.value = "";
   		        	document.form1.la20_d_fim_ano.value = "";
   		    		data=false;
   				}	
   				
   		  }
   		  return data;
   		}
</script>