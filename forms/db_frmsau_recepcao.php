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

//MODULO: saude
$clprontuarios->rotulo->label();
$clcgs->rotulo->label();
$clcgs_und->rotulo->label();

//Prontuario/Agendamento
$clagendamentos->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_numcgs");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s115_c_tipo");

$clrotulo->label("descrdepto");

//Médico
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");
//Unidade / Medicos
$clrotulo->label("sd04_i_cbo");
//especmedico
$clrotulo->label("sd27_i_codigo");

//CBO
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");


?>
   <SCRIPT LANGUAGE="JavaScript">
    team = new Array(
    <?
    # Seleciona todos os calendï¿½rios
    $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
             FROM microarea
             ORDER BY sd34_v_descricao";
    $sql_result = pg_query($sql1);
    $num = pg_num_rows($sql_result);
    $conta = "";
    while ($row=pg_fetch_array($sql_result)){
     $conta = $conta+1;
     $cod_micro = $row["sd34_i_codigo"];
     echo "new Array(\n";
     $sub_sql = "SELECT sd35_i_codigo,sd33_v_descricao
                 FROM familiamicroarea
                  inner join familia on sd33_i_codigo = sd35_i_familia
                 WHERE sd35_i_microarea = '$cod_micro'
                 ORDER BY sd33_v_descricao
                ";
     $sub_result = pg_query($sub_sql);
     $num_sub = pg_num_rows($sub_result);
     if ($num_sub>=1){
      echo "new Array(\"\", ''),\n";
      $conta_sub = "";
      while ($rowx=pg_fetch_array($sub_result)){
       $codigo_fam=$rowx["sd35_i_codigo"];
       $nome_fam=$rowx["sd33_v_descricao"];
       $conta_sub=$conta_sub+1;
       if ($conta_sub==$num_sub){
        echo "new Array(\"$nome_fam\", $codigo_fam)\n";
        $conta_sub = "";
       }else{
        echo "new Array(\"$nome_fam\", $codigo_fam),\n";
       }
      }
     }else{
      echo "new Array(\"Microarea sem familias cadastradas.\", '')\n";
     }
     if ($num>$conta){
      echo "),\n";
     }
   }
   echo ")\n";
   echo ");\n";
   ?>
   //Inicio da funï¿½ï¿½o JS
   function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
    var i, j;
    var prompt;

    // empty existing items
    for (i = selectCtrl.options.length; i >= 0; i--) {
     selectCtrl.options[i] = null;
    }
    prompt = (itemArray != null) ? goodPrompt : badPrompt;
    if (prompt == null) {
     selectCtrl.options[0] = new Option('','');
     j = 0;
    }else{
     selectCtrl.options[0] = new Option(prompt);
     j = 1;
    }
    if (itemArray != null) {
     // add new items
     for (i = 0; i < itemArray.length; i++){
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null){
       selectCtrl.options[j].value = itemArray[i][1];
      }
      <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
       if(<?=trim($z01_i_familiamicroarea)?>==itemArray[i][1]){
        indice = i;
       }
      <?}?>
      j++;
     }
     <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
      selectCtrl.options[indice].selected = true;
     <?}else{?>
      selectCtrl.options[0].selected = true;
     <?}?>
    }
   }
   </script>

<form name="form1" method="post" action="">
<center>
<table width='65%'>
	<tr>
		<td>
  			<fieldset><legend><b>Paciente</b></legend>
			<table border="0" width="100%">
			
			  <tr>
			    <td align="right" nowrap title="<?=@$Tsd24_i_codigo?>">
            <div style="width: 130px;">
             <?=@$Lsd24_i_codigo?>
            </div>
			    </td>
			    <td>
			     <?
			     db_input('sd24_i_codigo',12,$Isd24_i_codigo,true,'text',3,"")
			     ?>
			    </td>
			    <? if( $obj_sau_config->s103_c_lancafaa == "I" ){ ?>
			    <td align="right" title="<?=@$Tsd23_i_codigo?>">
			       <?
			         db_ancora(@$Lsd23_i_codigo,"js_pesquisasd23_i_codigo(true);",$db_opcao);
			       ?>
			    </td>
			    <td>
			     <?
			     db_input('sd23_i_codigo',21,$Isd23_i_codigo,true,'text',3,"")
			     ?>
			    </td>
			    
			    <? } ?>
			  </tr>
			  <tr>
			    <td align="right" nowrap title="<?=@$Tsd24_i_unidade?>">
			       <?
			         db_ancora(@$Lsd24_i_unidade,"js_pesquisasd24_i_unidade(true);",3);
			       ?>
			    </td>
			    <td colspan='3' nowrap>
			     <?
			     //     $sd32_i_unidade = db_getsession("DB_coddepto");
			//     db_input('sd32_i_unidade',10,$Isd32_i_unidade,true,'text',3,"");
			//     $descrdepto=db_getsession("DB_nomedepto");
			     db_input('sd24_i_unidade',12,$Isd24_i_unidade,true,'text',3," onchange='js_pesquisasd24_i_unidade(false);'");
			     @db_input('descrdepto',52,$Idescrdepto,true,'text',3,"");
			     ?>
			     
			    </td>
			  </tr>
			
			  <!--  CGS / Nome -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_i_cgsund?>">
			       <?
			       db_ancora(@$Lz01_i_cgsund,"js_pesquisaz01_i_cgsund(true);",$db_opcao);
			       ?>
			    </td>
			    <td colspan="3" nowrap>
			      <?
			        db_input('z01_i_cgsund',12,$Iz01_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisaz01_i_cgsund(false);'")
			      ?>
			      <?
			        db_input('z01_v_nome',52,$Iz01_v_nome,true,'text',$db_opcao,'')
			      ?>
			    </td>
			  </tr>
			
			  <!-- Micro Área / Familia -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tsd35_i_microarea?>">
			     <?//db_ancora(@$Lz01_i_familiamicroarea,"js_pesquisasd35_i_familiamicroarea(true);",$db_opcao);?>
			     <b>Micro:</b>
			    </td>
			    <td>
			      <!--select name="z01_v_micro" onChange="fillSelectFromArray(this.form1.z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;"-->
			      <select name="z01_v_micro" onChange="fillSelectFromArray(this.form.z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" >
			       <option></option>
			       <?
			       $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
			               FROM microarea
			               ORDER BY sd34_v_descricao";
			       $sql_result = pg_query($sql1);
			       while($row=pg_fetch_array($sql_result)){
			        $cod_micro=$row["sd34_i_codigo"];
			        $desc_micro=$row["sd34_v_descricao"];
			        ?>
			        <option value="<?=$cod_micro;?>" <?=$cod_micro==@$sd34_i_codigo?"selected":""?>><?=$desc_micro;?></option>
			        <?
			       }
			       ?>
			      </select>
			      <?db_input('sd34_i_codigo',20,@$Isd34_i_codigo,true,'hidden',$db_opcao,"")?>         
			     </td>
			    <td nowrap title="<?=@$Tsd35_i_familia?>" align="right">
			     <b>Familia:</b>
			    </td>
			    <td>
			      <!--select name="z01_i_familiamicroarea" style="font-size:9px;width:200px;height:18px;" onchange="if(this.value=='')document.form1.z01_v_micro.value='';"-->
			      <select name="z01_i_familiamicroarea"  onchange="if(this.value=='')document.form1.z01_v_micro.value='';">
			       <option value=""></option>
			      </select>      
			      <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
			       <script>fillSelectFromArray(document.form1.z01_i_familiamicroarea, team[document.form1.z01_v_micro.selectedIndex-1]);</script>
			      <?}?>
			    </td>
			  </tr>
			  
			  <!-- CPF / CGS do municipio -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_v_cgccpf?>">
			    	<?=@$Lz01_v_cgccpf?>
			    </td>
			    <td> 
				    <?
				    	db_input('z01_v_cgccpf',12,$Iz01_v_cgccpf,true,'text',$db_opcao,"")
				    ?>
			    </td>
			 	   <td nowrap title="<?=@$Tz01_c_municipio?>"  align="right">
			    	<B>CGS do Munic.</B>
			    </td>
			    <td> 
				    <?
					    $xz01_c_municipio = array('S'=>'SIM ----------------------','N'=>'NÃO ---------------------');
					    db_select('z01_c_municipio',$xz01_c_municipio,true,$db_opcao,"onchange=js_municipio()");
				    ?>
			    </td>
			    
			  </tr>
			  
			  <!--  CEP  -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_v_cep?>">
			     <?
			       if(!isset($z01_c_municipio)||$z01_c_municipio=='S'){
			       	  echo @$Lz01_v_cep;
			       }else{
			          db_ancora(@$Lz01_v_cep,"js_cepcon(true);",1);
			       }
			     ?>
			    </td>
			    <td colspan="3">
			     <?
			     db_input('z01_v_cep',12,$Iz01_v_cep,true,'text',(!isset($z01_c_municipio)||$z01_c_municipio=='S'?3:$db_opcao),"")
			     ?>
			     <input type="button" name="buscacep" value="Pesquisar" onClick="js_cepcon(false)" <?=(!isset($z01_c_municipio)||$z01_c_municipio=="S"?"disabled":"")?> >
			    </td>
			  </tr>
			  
			  <!--  Endereço -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_v_ender?>">
			     <?
			     		db_ancora(@$Lz01_v_ender,"js_ruas();",$db_opcao);
			     ?>
			    </td>
			    <td colspan="3">
			     <?
			     db_input('z01_v_ender',68,$Iz01_v_ender,true,'text',(!isset($z01_c_municipio)||$z01_c_municipio=='S'?3:$db_opcao),"")
			     ?>
			    </td>
			  </tr>
			  
			  <!--  Número / Complemento -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_i_numero?>">
			       <?=@$Lz01_i_numero?>
			    </td>
			    <td> 
			     <?
			     db_input('z01_i_numero',12,$Iz01_i_numero,true,'text',$db_opcao,"")
			     ?>
			    </td>
			    <td nowrap title="<?=@$Tz01_v_compl?>" align="right">
			       <?=@$Lz01_v_compl?>
			    </td>
			    <td> 
			     <?
			     db_input('z01_v_compl',21,$Iz01_v_compl,true,'text',$db_opcao,"")
			     ?>
			    </td>
			  </tr>
			  
			  <!--  Bairro -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_v_bairro?>">
			       <?
			       		db_ancora(@$Lz01_v_bairro,"js_bairro();",$db_opcao);
			       ?>
			    </td>
			    <td colspan="3">
			     <?
			     db_input('j13_codi',10,@$Ij13_codi,true,'hidden',3);
			     db_input('z01_v_bairro',68,$Iz01_v_bairro,true,'text',(!isset($z01_c_municipio)||$z01_c_municipio=='S'?3:$db_opcao),"")
			     ?>
			    </td>
			  </tr>
			  
			  <!--  Municipio / UF -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_v_munic?>">
			    	<?=@$Lz01_v_munic?>
			    </td>
			    <td>
				    <?
				    	db_input('z01_v_munic',30,$Iz01_v_munic,true,'text',3,"")
				    ?>
			    </td>
			    <td nowrap title="<?=@$Tz01_v_uf?>" align="right">
			    	<?=@$Lz01_v_uf?>
			    </td>
			    <td> 
				    <?
				    	db_input('z01_v_uf',2,$Iz01_v_uf,true,'text',3,"")
				    ?>
			    </td>
			  </tr>
			
			  <!--  Telefone / Cartão SUS -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_v_telef?>">
			       <?=@$Lz01_v_telef?>
			    </td>
			    <td> 
			     <?
			     db_input('z01_v_telef',12,$Iz01_v_telef,true,'text',$db_opcao,"")
			     ?>
			    </td> 
			    <td nowrap title="<?=@$Ts115_c_cartaosus?>" align="right">
			       <?=@$Ls115_c_cartaosus?>
			    </td>
			    <td>
			     <?
                 db_input('s115_i_codigo',15,@$Is115_i_codigo,true,'hidden',$db_opcao);
			     db_input('s115_c_cartaosus',15,$Is115_c_cartaosus,true,'text',$db_opcao,"");
		         $x = array("D"=>"D","P"=>"P");
	        	 db_select('s115_c_tipo',$x,true,$db_opcao);
			     
			     ?>
			    </td>
			  </tr>
			
			  <!--  Nascimento / Sexo -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_d_nasc?>">
			    	<?=@$Lz01_d_nasc?>
			    </td>
			    <td> 
				    <?
				    	db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',$db_opcao,"")
				    ?>
			    </td>
			    <td nowrap title="<?=@$Tz01_v_sexo?>" align="right">
			    	<?=@$Lz01_v_sexo?>
			    </td>
			    <td> 
			    <?
				    $x = array('M'=>'MASCULINO  ----------','F'=>'FEMININO  -------------');
				    db_select('z01_v_sexo',$x,true,$db_opcao,"");
			    ?>
			    </td>
			  </tr>
			
			  <!-- Data Cadastro / Login -->
			  <tr>
			    <td align="right" nowrap title="<?=@$Tz01_d_cadast?>">
			      <B>Cadastro:</B>
          </td>
			    <td>
			     <?
			     	db_inputdata('z01_d_cadast',@$z01_d_cadast_dia,@$z01_d_cadast_mes,@$z01_d_cadast_ano,true,'text',3,"")
			     ?>
			    </td>
			    <td nowrap title="<?=@$Tz01_i_login?>" align="right">
				     <?=@$Lz01_i_login?>
			    </td>
			    <td>
				     <?
					     db_input('z01_i_login',6,$Iz01_i_login,true,'hidden',3,"");
					     db_input('nome',21,@$nome,true,'text',3,"")
				     ?>
			    </td>
			  </tr>
			  <tr>
             <td nowrap title="<?=@$Tsd24_i_tipo?>" align="right">
                <?=@$Lsd24_i_tipo?>
             </td>
             <td colspan="3">
                <select name="sd24_i_tipo" id="sd24_i_tipo">
                    <option value="">Selecione:::</option>
                    <?$rsSelect=pg_query("select s145_i_codigo as cod,s145_c_descr as label from sau_tiposatendimento");
                      for($y=0;$y<pg_num_rows($rsSelect);$y++){
                         db_fieldsmemory($rsSelect,$y);?>
                         <option value="<?=$cod?>" <?=(@$sd24_i_tipo==$cod)?"selected":""?>><?=$label?></option>
                    <?}?>
               </select>   
             </td>
        </tr>
        <tr>
             <td nowrap title="<?=@$Tsd24_i_motivo?>" align="right">
               <?=@$Lsd24_i_motivo?>
             </td>
             <td colspan="3">
                <select name="sd24_i_motivo" id="sd24_i_motivo">
                    <option value="">Selecione:::</option>
                    <?$rsSelect=pg_query("select s144_i_codigo as cod,s144_c_descr as label from sau_motivoatendimento");
                      for($y=0;$y<pg_num_rows($rsSelect);$y++){
                         db_fieldsmemory($rsSelect,$y);?>
                         <option value="<?=$cod?>" <?=(@$sd24_i_motivo==$cod)?"selected":""?>><?=$label?></option>
                    <?}?>
                </select>  
             </td>
        </tr>
        <tr>
             <td nowrap title="<?=@$Tz01_i_login?>" align="right">
                <?=@$Lsd24_i_acaoprog?>
             </td>
             <td colspan="3">
                <select name="sd24_i_acaoprog" id="sd24_i_acaoprog">
                    <option value="">Selecione:::</option>
                    <?$rsSelect=pg_query("select fa12_i_codigo as cod,fa12_c_descricao as label from far_programa");
                      for($y=0;$y<pg_num_rows($rsSelect);$y++){
                         db_fieldsmemory($rsSelect,$y);?>
                         <option value="<?=$cod?>" <?=(@$sd24_i_acaoprog==$cod)?"selected":""?>><?=$label?></option>
                    <?}?>
                </select> 
             </td>
        </tr>
			  </table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<?
			//if( $obj_sau_config->s103_c_lancafaa == "I" ){
				$db_opcaoprof = isset($sd29_i_profissional)&&(int)$sd29_i_profissional!=0?3:$db_opcao;
				?>
        <fieldset><legend><b>Profissional de Atendimento</b></legend>
         <table border="0" width="100%">
								<!-- PROFISSIONAL -->
								<tr>
									<td align="right" nowrap title="<?=@$Tsd03_i_codigo?>" >
                    <div style="width: 130px;">
										<? db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true,1);",$db_opcaoprof); ?>
                    </div>
									</td>
                  <td>
										<? 
                      db_input('sd03_i_codigo',12,$Isd03_i_codigo,true,'text',$db_opcaoprof," onchange='js_pesquisasd03_i_codigo(false,1);' onFocus=\"nextfield='rh70_estrutural'\""); 
										?>
										<? db_input('z01_nome',52,$Iz01_nome,true,'text',3,''); ?>
                   </td>
								</tr>
								<!-- CBO -->
								<tr>
									<td align="right" nowrap title="<?=@$Tsd04_i_cbo?>">
										<? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true,1);",$db_opcaoprof); ?>
									</td>
									<td>
										<?
											db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'hidden',$db_opcaoprof,"");
											db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcaoprof,"");
											db_input('rh70_estrutural',12,$Irh70_estrutural,true,'text',$db_opcaoprof," onchange='js_pesquisasd04_i_cbo(false,1);' onFocus=\"nextfield='sd23_d_consulta'\"");
										?>
										<? db_input('rh70_descr',52,$Irh70_descr,true,'text',3,''); ?>
									</td>
								</tr>
				</table>
				</fieldset>
				<?
			//}
			?>
		</td>
	</tr>
</table>
  </center>
<p>
<input name="incluir" type="submit" id="db_opcao" value="Confirmar" >
<input name="pesquisar" type="button" id="pesquisar" value="Consulta FAA" onclick="js_pesquisaprontuarios();" >
<input name="limpar" type="button" id="limpar" value="Nova FAA" onclick="js_limpa()">
<input name="emitir" type="button" id="emitir" value="Emitir FAA" onclick="js_emitirFaa()">
<?
$oSauConfig = loadConfig('sau_config');
selectModelosFaa($oSauConfig->s103_i_modelofaa);
?>

</form>
<script>

function js_emitirFaa() {

	  if ($F('sd24_i_codigo') != '') {
	     
	    var oParam               = new Object();
	    oParam.exec              = 'gerarFAATXT';
	    oParam.sChaveProntuarios = $F('sd24_i_codigo');
	    oParam.iModelo           = $F('s103_i_modelofaa');
	    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');

	  } else {

	    alert('Nenhuma FAA para gerar.');

	  }

	}

	function js_retornoEmissaofaa (oAjax) {

	  oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.iStatus == 2) {

	    message_ajax(oRetorno.sMessage.urlDecode());
	    return false;

	  } else {
	    if (oRetorno.iTipo == 1) {
	      js_emitiefaaPDF (oRetorno);
	    } else {
	      js_emitirfaaTXT (oRetorno);
	    }
	  }

	}

	function js_emitiefaaPDF (oDados) {

	  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
	  var WindowObjectReference;
	  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
	  sArquivo = js_getArquivoFaa($F('s103_i_modelofaa'));
	  WindowObjectReference = window.open(sArquivo+sChave,"CNN_WindowName", strWindowFeatures);

	}

	function js_emitirfaaTXT (oRetorno) {

	  iTop    = 20;
	  iLeft   = 5;
	  iHeight = screen.availHeight-210;
	  iWidth  = screen.availWidth-35;
	  sChave  = 'sSessionNome='+oRetorno.sSessionNome;

	  js_OpenJanelaIframe ('', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
	                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
	                      );

	}

function js_getArquivoFaa(iCodModelo) {

  oSel = $('sArquivoFaa');
  for (var iCont = 0; iCont < oSel.length; iCont++) {

    if (iCodModelo == oSel.options[iCont].value) {
      return oSel.options[iCont].text;
    }

  }

}

function js_pesquisasd03_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,'Pesquisa',true);
  }else{
     if(document.form1.sd03_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,'Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value = '';
    document.form1.sd27_i_codigo.value = '';
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value = '';
  }else{
    js_pesquisasd04_i_cbo(true);    
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
}

function js_pesquisasd04_i_cbo(mostra){
  if(mostra==true){
//    js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostrarhcbo1|sd04_i_codigo|rh70_estrutural|rh70_descr|rh70_sequencial&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){ 
//        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd04_i_codigo|rh70_estrutural|rh70_descr|rh70_estrutural&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        document.form1.rh70_estrutural.value = '';
        document.form1.rh70_descr.value = '';
     }else{
       document.form1.rh70_estrutural.value = '';
     }
  }
}
function js_mostrarhcbo(erro,chave1, chave2, chave3,chave4){
  document.form1.rh70_descr.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.sd27_i_codigo.value = chave3;
  document.form1.rh70_sequencial.value = chave4;
  if(erro==true){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3,chave4){
  document.form1.sd27_i_codigo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  //document.form1.sd29_i_procedimento.value = '';
  //document.form1.sd63_c_procedimento.value = '';
  //document.form1.sd63_c_nome.value = '';
  db_iframe_especmedico.hide();

  if(chave2=''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  
}



function js_pesquisasd23_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_prontagendamento','func_prontagendamento.php?funcao_js=parent.js_mostraagendamento1|dl_FAA|dl_Agenda|sd23_i_numcgs','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_unidade.value != ''){
        //js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd24_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraagendamento1(faa,agenda,cgs){

  db_iframe_prontagendamento.hide();
  if( faa != "" ){
  	location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+faa+'&triagem='+'<?=@$triagem?>';
  }else if( agenda != "" ){
  	location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaagenda='+agenda+'&triagem='+'<?=@$triagem?>';
  }else{
  	location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+cgs+'&triagem='+'<?=@$triagem?>';
  }
}


 function js_ruas(){
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas(chave,chave1){
   document.form1.z01_v_ender.value = chave1;
   db_iframe_ruas.hide();
 }
 function js_bairro(){
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro(chave,chave1){
  document.form1.j13_codi.value = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
 }
 function js_ruas1(){
  js_OpenJanelaIframe('','db_iframe_ruas1','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas1(chave,chave1){
   document.form1.z01_v_endcon.value = chave1;
   db_iframe_ruas1.hide();
 }
 function js_bairro1(){
  js_OpenJanelaIframe('','db_iframe_bairro1','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro1(chave,chave1){
  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
 }


function js_pesquisasd24_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_unidade.value != ''){
        js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd24_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd24_i_unidade.focus();
    document.form1.sd24_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd24_i_unidade.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}

function js_pesquisaz01_i_cgsund(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund&retornacgs=p.p.document.form1.z01_i_cgsund.value&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);p.p.document.form1.z01_v_nome.value','Pesquisa',true);
  }else{
     if(document.form1.z01_i_cgsund.value != ''){ 
    	js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund&retornacgs=p.p.document.form1.z01_i_cgsund.value&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);p.p.document.form1.z01_v_nome.value&chave_z01_i_cgsund='+document.form1.z01_i_cgsund.value,'Pesquisa',true);
        //js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.z01_i_cgsund.value+'&funcao_js=parent.js_preenchecgs|z01_i_cgsund','Pesquisa',false);
     }else{
       document.form1.z01_i_numcgs.value = ''; 
     }
  }
}
function js_mostracgs(erro, chave){
  document.form1.z01_v_nome.value = chave;
  if(erro==true){ 
    document.form1.z01_i_cgsund.focus(); 
    document.form1.z01_v_nome.value = '';
  }
}
function js_mostracgs1(chave1,chave2){
  document.form1.z01_i_cgsund.value = chave1;
  document.form1.z01_i_numcgs.value = chave2;
  db_iframe_cgs.hide();
}
<?if(isset($triagem) && $triagem=="false"){?>
function js_pesquisaprontuarios(){
  js_OpenJanelaIframe('','db_iframe_prontuarios002','func_prontuarios002.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo','Pesquisa',true);
}
<?}else{?>
function js_pesquisaprontuarios(){
  js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo','Pesquisa',true);
}
<?}?>

function js_preenchecgs(chave){
  db_iframe_cgs_und.hide();
  
  location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+chave+'&triagem='+'<?=@$triagem?>';
}




<?if(isset($triagem) && $triagem=="false"){?>
function js_preenchepesquisa(chave){
  db_iframe_prontuarios002.hide();
 location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave+'&triagem='+'<?=@$triagem?>';
 

}
<?}else{?>
function js_preenchepesquisa(chave){
  db_iframe_prontuarios.hide();
 location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave+'&triagem='+'<?=@$triagem?>';
 

}
<?}?>


function js_limpa(){
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  ?>
}

function js_cepcon(abre){
  if(abre == true){
    js_OpenJanelaIframe("",'db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',true);
  }else{
    js_OpenJanelaIframe("",'db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value+'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',false);
  }
}
function js_preenchecepcon(chave,chave1,chave2,chave3,chave4){
  document.form1.z01_v_cep.value = chave;
  document.form1.z01_v_ender.value = chave1;
  document.form1.z01_v_munic.value = chave2;
  document.form1.z01_v_uf.value = chave3;
  document.form1.z01_v_bairro.value = chave4;
  db_iframe_cep.hide();
}


function js_anular(){
  if(document.form1.sd24_i_codigo.value==""){
	 alert("FAA não informada!");	
  }else{
	  iTop = ( screen.availHeight-600 ) / 2;
	  iLeft = ( screen.availWidth-600 ) / 2;
	  if( document.form1.anular.value == "Anular FAA" ){
	    <? echo "js_OpenJanelaIframe('','db_iframe_prontanulado','sau1_prontanulado001.php?chavepesquisaprontuario='+document.form1.sd24_i_codigo.value,'Anular FAA',true, iTop, iLeft, 600, 210)"; ?>
	  }else{
	  	<? echo "js_OpenJanelaIframe('','db_iframe_prontanulado','sau1_prontanulado003.php?chavepesquisa='+document.form1.sd24_i_codigo.value,'Anular FAA',true, iTop, iLeft, 600, 210)"; ?>
	  }
 	
  }
}

function js_municipio(){
	if( document.form1.z01_i_cgsund.value != ""){
       location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+document.form1.z01_i_cgsund.value+'&chavepesquisaprontuario='+document.form1.sd24_i_codigo.value+'&chavepesquiamunicipio='+document.form1.z01_c_municipio.value;
    }else{
       query  = '?chavepesquiamunicipio='+document.form1.z01_c_municipio.value;
       query += '&z01_v_nome='+document.form1.z01_v_nome.value;
       query += '&sd34_i_codigo='+document.form1.z01_v_micro.value;
       query += '&z01_v_cgccpf='+document.form1.z01_v_cgccpf.value;
       location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+query;
    }	
}

</script>