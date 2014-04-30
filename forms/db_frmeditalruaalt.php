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

//MODULO: contrib
$cleditalrua->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("d03_tipos");
$clrotulo->label("d03_descr");
$clrotulo->label("d01_descr");
$clrotulo->label("d04_quant");
$clrotulo->label("d40_codigo");
$clrotulo->label("d04_vlrcal");
$clrotulo->label("d04_vlrval");
$clrotulo->label("d04_mult");
$clrotulo->label("d04_forma");
$clrotulo->label("d04_vlrobra");
$clrotulo->label("j14_nome");
$clrotulo->label("nome");
if(isset($dados)){
  $d03_tipos="";
  $d03_descr="";
  $d04_quant="";
  $d04_vlrcal="";
  $d04_vlrval="";
  $d04_mult=""; 
  $d04_forma=""; 
  $d04_vlrobra=""; 
}
?>
<script>

function js_insere(){

  var expr    = new RegExp("[^0-9\.]+");
  var quant   = document.form1.d04_quant.value;
  var vlrcal  = document.form1.d04_vlrcal.value;
  var vlrval  = document.form1.d04_vlrval.value;
  var mult    = document.form1.d04_mult.value;
  var forma   = document.form1.d04_forma.value;
  var texto   = document.form1.d03_descr.value;
  var tipos   = document.form1.d03_tipos.value;
  var vlrobra = document.form1.d04_vlrobra.value;

  if (vlrcal.match(expr)) {
    document.form1.d04_vlrcal.select();
    alert("Este campo deve preenchido somente com números decimais!");
    return false;
  }
  if (quant.match(expr)) {
    document.form1.d04_quant.select();
    alert("Este campo deve preenchido somente com números decimais!");
    return false;
  }
  if (vlrcal=="") {
    document.form1.d04_vlrcal.select();
    alert("Preencha este campo.");
    return false;
    
  }
  if (mult=="") {
    document.form1.d04_mult.select();
    alert("Preencha este campo.");
    return false;
    
  }
  if (quant=="") {
    document.form1.d04_quant.select();
    alert("Preencha este campo.");
    return false;
    
  }
  if (vlrobra=="") {
    document.form1.d04_vlrobra.select();
    alert("Preencha valor da obra.");
    return false;
    
  }
  
  OBJ = tiposerv.document.form1;
  for (i=0; i<OBJ.length; i++) {
    if (OBJ.elements[i].type=='checkbox') {
      if (OBJ.elements[i].name.substr(6) ==tipos ) {
        reton=confirm('Tipo de serviço já Informado. Deseja alterar?');
        if (reton==false) {
          return false;
        } else {
          tab=tiposerv.document.getElementById('id_tabela');
          for (i=0; i<tab.rows.length; i++) {
            if ("linha_"+tipos == tab.rows[i].id) {
              tab.deleteRow(i);
              break;
            }
          }
        }
      }
    }
  }
  
  document.getElementById('id_div').style.visibility="visible";
  tiposerv.js_incluirlinha(quant,vlrcal,texto,tipos,vlrval,mult,forma,vlrobra);
  texto=document.form1.d03_descr.value="";
  valor=document.form1.d03_tipos.value="";
  valor=document.form1.d04_vlrcal.value="";
  valor=document.form1.d04_vlrval.value="";
  valor=document.form1.d04_quant.value="";
  valor=document.form1.d04_mult.value="";
  valor=document.form1.d04_forma.value="";
  valor=document.form1.d04_vlrobra.value="";
  document.form1.lanca.onclick = '';
  
  
}
function js_confirma(){
  var obj = tiposerv.document.form1;
  var ob = tiposerv.document;
  var tipos="";
  var quant="";
  var xx="XX";
  var dados="";
  var certo=false;
  for (i=0; i<obj.length; i++) {
    if (obj.elements[i].type=='checkbox' && obj.elements[i].checked ) {
      tipos = obj.elements[i].name.substr(6);
      quant = ob.getElementById('quant_'+tipos).innerHTML;
      vlrcal = ob.getElementById('vlrcal_'+tipos).innerHTML;
      texto  = ob.getElementById('texto_'+tipos).innerHTML;
      vlrval = ob.getElementById('vlrval_'+tipos).innerHTML;
      mult = ob.getElementById('mult_'+tipos).innerHTML;
      forma = ob.getElementById('forma_'+tipos).innerHTML;
      vlrobra = ob.getElementById('vlrobra_'+tipos).innerHTML;
      dados += tipos+"-"+quant+"-"+vlrcal+"-"+texto+"-"+vlrval+"-"+mult+"-"+forma+"-"+vlrobra+xx;
      certo=true;
    }
  }
  if (certo==false) {
    alert("É obrigatório lançar os serviços antes de incluir a contribuição.");
    return false;
  }
  document.form1.dados.value=dados;
  return true;
}

function js_trocarua(valor){
  obj    = document.getElementById("r_"+valor.value);
  profun = document.getElementById("p_"+valor.value).value;
  valorizacao = document.getElementById("v_"+valor.value).value;
  document.form1.d02_codigo.value = obj.value;
  document.form1.d02_profun.value = profun;
  document.form1.d02_valorizacao.value = valorizacao;
  js_pesquisad02_codigo(false);
}
</script>


<form name="form1" method="post" action="">
<center>

<table border='0'>
<tr>    

<td width="70%">
	    <td width="100%" colspan="2" align="center">
	      <fieldset>
          <Legend>
            <b>Dados da contribuição</b>
          </legend>

	       <table border='0' width="100%">
           <tr>
             <td nowrap title="<?=@$Td02_contri?>" width="25%">
                <input name="dados" type="hidden">
                <input name="numedital" type="hidden">
                <?=@$Ld02_contri?>
             </td>
             <td> 
               <?
                 db_input('d02_contri',10,$Id02_contri,true,'text',3,"")
               ?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$Td02_codedi?>">
                <?
                db_ancora(@$Ld02_codedi,"js_pesquisad02_codedi(true);",$db_opcao);
                ?>
             	</td>
             <td> 
               <?
                 if(empty($d02_codedi)|| isset($d02_codedi) && $d02_codedi==""){
                   $d01_descr="";  
                 }
                 db_input('d02_codedi',10,$Id02_codedi,true,'text',$db_opcao," onchange='js_pesquisad02_codedi(false);'");
         
                 db_input('d01_descr',50,$Id01_descr,true,'text',3,'');
               ?>
              </td>
           </tr>
           <?
              if (isset($numedital)&& $numedital!="" && $d02_codedi!="") {
                if ($db_opcao==1) {
                  $result=$cleditalproj->sql_record($cleditalproj->sql_query($numedital,"","d40_codigo,d40_trecho,d40_codlog,j14_nome,d40_profun","","d40_codigo not in (select d11_codproj from editalruaproj)  and d10_codedi=$numedital"));
                  $numrows2=$cleditalproj->numrows;
                } else {
                  if (empty($editalpri)) {
                    $editalpri=$numedital;
                  }
                  db_input('editalpri',35,0,true,'hidden',1);
                  $sql01=$cleditalproj->sql_query($numedital,"","d40_codigo,d40_trecho,d40_codlog,ruas.j14_nome,d40_profun","","d40_codigo not in (select d11_codproj from editalruaproj) and d10_codedi=$numedital ");
                  if ($editalpri==$numedital) {
                    $sql02=$cleditalruaproj->sql_query($d02_contri,"","d40_codigo,d40_trecho,d40_codlog,ruas.j14_nome,d40_profun","","");
                    $result=$cleditalruaproj->sql_record($sql01." union  ".$sql02);
                  } else {
                    $result=$cleditalruaproj->sql_record($sql01);
                  }
                  $numrows2=$cleditalruaproj->numrows;                  
                }
                
                if ($numrows2>0) {
                  echo "<input type='hidden' name='testedi' value='ok'>";
                  echo "<tr>";
                  echo "  <td> <b>Lista:</b> </td>";
                  echo "  <td>";
                  db_selectrecord("d40_codigo",$result,true,$db_opcao,"","","","","js_trocarua(this);");
                  echo "  </td>";
                  echo "</tr>";
                  for ($i=0; $i<$numrows2; $i++) {
                    db_fieldsmemory($result,$i);
                    echo "<input type='hidden' id='r_$d40_codigo' name='rua_$d40_codigo' value='$d40_codlog'>";
                    echo "<input type='hidden' id='p_$d40_codigo' name='profun_$d40_codigo' value='$d40_profun'>";
                    echo "<input type='hidden' id='v_$d40_codigo' name='valorizacao_$d40_codigo' value='0'>";
                  }
                  db_fieldsmemory($result,0);
                } else {

                  $result=$cleditalproj->sql_record($cleditalproj->sql_query($numedital,"","d40_codigo,d40_trecho,d40_codlog,j14_nome"));
                  if ($cleditalproj->numrows>0) {
                    $terminolistas=true;
                  }

                }                
              }
           ?>	  
	         <tr>
	         <?
	           if(isset($numrows2) && $numrows2>0){
	             $d02_codigo=$d40_codlog;
	             $db_opcao2=3;
	           }else{
	             $db_opcao2=$db_opcao;   
	           }
	          ?>
	           <td nowrap title="<?=@$Td02_codigo?>">
	              <?
	              db_ancora(@$Ld02_codigo,"js_pesquisad02_codigo(true);",$db_opcao2);
	              ?>
	           </td>
	           <td> 
           	 <?
             	 if(empty($d02_codigo)|| isset($d02_codigo) && $d02_codigo==""){
             	   $j14_nome="";  
             	 }
             	 db_input('d02_codigo',10,$Id02_codigo,true,'text',$db_opcao2," onchange='js_pesquisad02_codigo(false);'");

               db_input('j14_nome',50,$Ij14_nome,true,'text',3,'')
	           ?>
	           </td>
	         </tr>
	         <tr>
	           <td nowrap title="<?=@$Td02_profun?>">
	            <?=$Ld02_profun?>
	           </td>
	           <td nowrap> 
           	<?
               $db_opcao69=$db_opcao;
               if (isset($d40_codigo)) {
                 $result69=$cleditalproj->sql_record($cleditalproj->sql_query($d02_codedi,$d40_codigo,'d40_profun as d02_profun'));
                 db_fieldsmemory($result69,0);
                 if ($cleditalproj->numrows>0 && $d02_profun !="") {
                   $db_opcao69=3;
                 }
               }
               db_input('d02_profun',10,$Id02_profun,true,'text',$db_opcao69);
           	?>
	           </td>   
	         </tr> 
           <tr>
	           <td nowrap title="<?=@$Td02_valorizacao?>">
	            <?=$Ld02_valorizacao?>
	           </td>
	           <td nowrap> 
	           <?
	             db_input('d02_valorizacao',10,$Id02_valorizacao,true,'text',$db_opcao);
           	 ?>
	           </td>   
	         </tr> 
	       </table>
       </fieldset>
     </td>	 
	     <?
   		 $d02_autori="f";
   		 db_input('d02_autori',3,$Id02_autori,true,'hidden',3,'')
   		 ?>
  </td>
</tr>

<tr>    
  <td>
	  <td colspan="2" align="center">
	    <fieldset>
        <Legend>
          <b>Selecione os serviços</b>
        </legend>
	    <table border="0">
				<tr>
				  <td nowrap title="<?=@$Td03_tipos?>">
				   <?
				     db_ancora(@$Ld03_tipos,"js_tipos(true);",$db_opcao);
           ?>
          </td>
				  <td nowrap>         
           <?
	           db_input('d03_tipos',10,$Id03_tipos,true,'text',$db_opcao,"onchange='js_tipos(false);'");
           	 db_input('d03_descr',50,$Id03_descr,true,'text',3);
         	 ?>
   	 	 	  </td>          
	        <td nowrap title="<?=@$Td04_mult?>">
            <b>Multiplicador:</b>
          </td>
          <td nowrap>
				  <?
					  if (!isset($d04_mult) or $d04_mult == "") {
					    $d04_mult = 1;
  				  }
	          db_input('d04_mult',10,@$Id04_mult,true,'text',$db_opcao);
          ?>
				  </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Td04_forma?>">
           <?=@$Ld04_forma?>
          </td>
          <td nowrap> 
      			<?
	    		  $x = array('1'=>'utilizando valor para calculo','2'=>'utilizando valor para valorizacao', '3'=>'testada proporcional');
	    	  	db_select('d04_forma',$x,true,$db_opcao,"onChange='js_controlaFormaCalculo(this.value)';");
	      		?>
          </td>	   
	    	  <td nowrap title="<?=@$Td04_quant?>">
	    	    <?=@$Ld04_quant?>
	    	  </td>
	    	  <td nowrap> 
        	  <?
          	  db_input('d04_quant',10,$Id04_quant,true,'text',$db_opcao,"")
          	?>
        	</td>
      	</tr>

      	<tr id="linhavalores">
        	<td nowrap>
	   	      <?=@$Ld04_vlrcal?>
          </td>
          <td nowrap>
          <?
         	  db_input('d04_vlrcal',10,$Id04_vlrcal,true,'text',$db_opcao,"")
         	?>       
	     	  </td>   
          <td nowrap> 
	   	      <?=@$Ld04_vlrval?>
          </td>
          <td>
          	<?
          	db_input('d04_vlrval',10,$Id04_vlrval,true,'text',$db_opcao,"")
          	?>       
         	</td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Td04_vlrobra?>">
            <?=@$Ld04_vlrobra?>
          </td>
          <td> 
          <?
            db_input('d04_vlrobra',10,$Id04_vlrobra,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>
        <tr>
     		  <td align="right" colspan='4'>
	     	    <input name="lanca" type="button" value="Lançar">
	   	    </td>
        </tr>

        <tr>   
				  <td align="center" colspan="4" width="100%"> 
				    <div id="id_div" <?=(!isset($dados)?'style="visibility:hidden"':'')?>>  
         			<iframe name="tiposerv" id="tiposerv" src="con1_editalrua004.php?db_opcao=<?=$db_opcao?><?=(isset($dados)?'&dados='.$dados:"")?>" width="100%" height="150">
				      </iframe>
				    </div>
				  </td>
				</tr>
   	  </table>
    </fieldset>
  </td>	 
</td>
</tr>
</table>

</center>

<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"  <?=($db_botao==false?"disabled":"")?> <?=($db_opcao!=3?"onclick='return js_confirma()'":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>
<?
if (isset($terminolistas) && $db_opcao==1 && empty($execucao) ) {
  echo "function hj(){\n";
  echo "  alert('As listas deste edital já estam sendo usadas em outras contribuições.');\n";
  echo "  document.form1.d02_codedi.value='';\n";
  echo "  document.form1.d01_descr.value='';\n";
  echo "}\n";
  echo "hj();\n";
}
?>

function js_controlaFormaCalculo(sValor) {

  if (sValor == '3') {
    document.getElementById('linhavalores').style.display = 'none';
    document.form1.d04_vlrcal.value = '0';
    document.form1.d04_vlrval.value = '0';
  } else {
    document.getElementById('linhavalores').style.display = '';
    document.form1.d04_vlrcal.value = '';
    document.form1.d04_vlrval.value = '';
  }
  
}

function js_tipos(mostra){
  document.form1.lanca.onclick = "";
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframet','func_editaltipo.php?funcao_js=parent.js_mostraeditaltipos1|d03_tipos|d03_descr','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframet','func_editaltipo.php?pesquisa_chave='+document.form1.d03_tipos.value+'&funcao_js=parent.js_mostraeditaltipos','Pesquisa',false);
  }
}
function js_mostraeditaltipos(chave,erro){
  document.form1.d03_descr.value = chave;
  if (erro==true) {
    document.form1.d03_tipos.focus();
    document.form1.d03_tipos.value = '';
  } else {
    document.form1.lanca.onclick = js_insere;
  }
}
function js_mostraeditaltipos1(chave1,chave2){
  document.form1.d03_tipos.value = chave1;
  document.form1.d03_descr.value = chave2;
  db_iframet.hide();
  document.form1.lanca.onclick = js_insere;
}


function js_pesquisad02_codedi(mostra){
  document.form1.d03_tipos.disabled=true;
  document.form1.d02_codigo.disabled=true;
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe02','func_edital.php?funcao_js=parent.js_mostraedital1|d01_codedi|d01_descr','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe02','func_edital.php?pesquisa_chave='+document.form1.d02_codedi.value+'&funcao_js=parent.js_mostraedital','Pesquisa',false);
  }
}
function js_mostraedital(chave,erro) {
  
  document.form1.d01_descr.value = chave;
  
  if (erro==true) {
    document.form1.d02_codedi.focus();
    document.form1.d02_codedi.value = '';
  } else {
    document.form1.d03_tipos.disabled=false;
    document.form1.d02_codigo.disabled=false;
    if (document.form1.d02_codedi.value !="") {
      document.form1.numedital.value= document.form1.d02_codedi.value;
      ;
      var obj = tiposerv.document.form1;
      var ob = tiposerv.document;
      var tipos="";
      var quant="";
      var xx="XX";
      var dados="";
      for (i=0; i<obj.length; i++) {
        if (obj.elements[i].type=='checkbox' && obj.elements[i].checked ) {
          tipos = obj.elements[i].name.substr(6);
          quant = ob.getElementById('quant_'+tipos).innerHTML;
          vlrcal = ob.getElementById('vlrcal_'+tipos).innerHTML;
          texto  = ob.getElementById('texto_'+tipos).innerHTML;
          vlrval = ob.getElementById('vlrval_'+tipos).innerHTML;
          forma = ob.getElementById('forma_'+tipos).innerHTML;
          vlrobra = ob.getElementById('vlrobra_'+tipos).innerHTML;
          dados += tipos+"-"+quant+"-"+vlrcal+"-"+texto+"-"+vlrval+"-"+forma+"-"+vlrobra+xx;
        }
      }
      document.form1.dados.value=dados;
      document.form1.submit();
    }
  }
}
function js_mostraedital1(chave1,chave2){
  document.form1.d02_codedi.value = chave1;
  document.form1.d01_descr.value = chave2;
  document.form1.numedital.value=chave1;
  var obj = tiposerv.document.form1;
  var ob = tiposerv.document;
  var tipos="";
  var quant="";
  var xx="XX";
  var dados="";
  for (i=0; i<obj.length; i++) {
    if (obj.elements[i].type=='checkbox' && obj.elements[i].checked ) {
      tipos   = obj.elements[i].name.substr(6);
      quant   = ob.getElementById('quant_'+tipos).innerHTML;
      vlrcal  = ob.getElementById('vlrcal_'+tipos).innerHTML;
      texto   = ob.getElementById('texto_'+tipos).innerHTML;
      vlrval  = ob.getElementById('vlrval_'+tipos).innerHTML;
      forma   = ob.getElementById('forma_'+tipos).innerHTML;
      vlrobra = ob.getElementById('vlrobra_'+tipos).innerHTML;
      dados += tipos+"-"+quant+"-"+vlrcal+"-"+texto+"-"+vlrval+"-"+forma+"-"+vlrobra+xx;
    }
  }
  document.form1.submit();
}

function js_pesquisad02_codigo(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe03','func_ruas.php?funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe03','func_ruas.php?pesquisa_chave='+document.form1.d02_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if (erro==true) {
    document.form1.d02_codigo.focus();
    document.form1.d02_codigo.value = '';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.d02_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide03();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe04','func_editalrua.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe04.hide();
  <?
  if ($db_opcao!=1) {
    ?>
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
    <?
  }
  ?>
}
<?
if (($db_opcao==33 || $db_opcao==22) && empty($calcnops) && empty($chavepesquisa)) {
  
  echo "\njs_pesquisa();";
}
?>
</script>