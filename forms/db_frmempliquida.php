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


include ("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("o56_elemento");
$clrotulo->label("e60_coddot");
$clrotulo->label("e69_numero");
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();
$clempnotaele->rotulo->label();

if (isset ($e60_vlremp)) {

	$vlrdis = ($e60_vlremp - $e60_vlrliq - $e60_vlranu);

	$vlrdis = number_format($vlrdis, "2", ".", "");
	if ($vlrdis == 0 || $vlrdis == '') {
		$db_opcao = 33;
	}

	//empnotaele
	$sql = $clempnotaele->sql_query(null, null, "e70_codnota,sum(e70_valor) as e70_valor, sum(e70_vlrliq) as e70_vlrliq, sum(e70_vlranu) as    e70_vlranu", "", "e69_numemp=$e60_numemp  and ((e70_valor-e70_vlranu)<>0) group by e70_codnota");
	$result = $clempnotaele->sql_record($sql);
	$numrows_nota = $clempnotaele->numrows;

	if ($numrows_nota > 0) {
		db_fieldsmemory($result, 0, true);
		if (($e70_valor - $e70_vlrliq - $e70_vlranu) == 0 && $vlrdis == 0) {
			$db_opcao = 33;
			$tranca = true;
		}
	}

	if ($numrows_nota > 0) {
		$vlrliq = "0.00";
	} else {
		$vlrliq = $vlrdis;
	}
}

if (empty ($e60_numemp)) {
	$db_opcao_inf = 3;
} else {
	$db_opcao_inf = $db_opcao;
}

if (isset ($e60_numemp)) {
	$sql = $clempnotaele->sql_query(null, null, "sum(e70_vlranu) as tot_vlranul,sum(e70_valor) as tot_valorl", "", "e69_numemp=$e60_numemp  and e70_vlrliq=0 and ((e70_valor-e70_vlranu)<>0)");
	$result = $clempnotaele->sql_record($sql);
	db_fieldsmemory($result, 0, true);
}
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
        font-size: 8px;
}
</style>
<form name="form1" method="post" action="">
<center>
<table border='0' cellspacing='0' cellpadding='0' width='100%' >
  <tr>
  <td colspan='2' align='center'valign='top' >
<?


db_input('dados', 6, 0, true, 'hidden', 3)
?>

<table border="0" cellspacing='0' cellpadding='0'>
  <tr>
    <td nowrap title="<?=@$Te60_numemp?>">
       <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho','".@$e60_numemp."')",$db_opcao_inf)?>        
    </td>
    <td> 
<?

 db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
    <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','".@$e60_numcgm."')",$db_opcao_inf)?>        
    </td>
    <td> 
<?

 db_input('e60_numcgm', 10, $Ie60_numcgm, true, 'text', 3)
?>
       <?

 db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
      <td nowrap title="<?=@$Te60_coddot?>">
         <?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao','".@$e60_coddot."','".@$e60_anousu."')",$db_opcao_inf)?>        
      </td>
      <td>
          <? db_input('e60_coddot',8,$Ie60_coddot,true,'text',3); ?>
      </td>
  </tr>
     <? 

/* busca dados da dotação  */
if ((isset ($e60_coddot))) {
	$instit = db_getsession("DB_instit");
	$clorcdotacao->sql_record($clorcdotacao->sql_query_file("", "", "*", "", "o58_coddot=$e60_coddot and o58_instit=$instit"));
	if ($clorcdotacao->numrows > 0) {
		$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e60_coddot", $e60_anousu);
		db_fieldsmemory($result, 0);
		$atual = number_format($atual, 2, ",", ".");
		$reservado = number_format($reservado, 2, ",", ".");
		$atudo = number_format($atual_menos_reservado, 2, ",", ".");
	} else {
		$nops = " Dotação $e60_coddot  não encontrada ";
	}
}
?>
          <tr>
             <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
	     <td nowrap >
	       <? db_input('o58_orgao',14,"$Io58_orgao",true,'text',3,"");  ?>
	       <? db_input('o40_descr',40,"",true,'text',3,"");  ?> 
	     </td>     
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
	     <td nowrap >
	       <? db_input('o58_unidade',14,"",true,'text',3,"");  ?>
	       <? db_input('o41_descr',40,"",true,'text',3,"");  ?> 
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
	     <td nowrap >
	       <? db_input('o58_funcao',14,"",true,'text',3,"");  ?>
	       <? db_input('o52_descr',40,"",true,'text',3,"");  ?> 
	     </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
	     <td nowrap >
	       <? db_input('o58_subfuncao',14,"",true,'text',3,"");  ?>
	       <? db_input('o53_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
	     <td nowrap >
	       <? db_input('o58_programa',14,"",true,'text',3,"");  ?>
	       <? db_input('o54_descr',40,"",true,'text',3,"");  ?>      
             </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
	     <td nowrap >
	       <? db_input('o58_projativ',14,"",true,'text',3,"");  ?> 
	       <? db_input('o55_descr',40,"",true,'text',3,"");  ?>  
	     </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$To56_elemento ?>" ><?=@$Lo56_elemento ?> </td>
	     <td nowrap > 
	       <? db_input('o58_elemento',14,"",true,'text',3,"");  ?> 
	       <? db_input('o56_descr',40,"",true,'text',3,"");  ?>     
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
	     <td nowrap >
	       <? db_input('o58_codigo',14,"",true,'text',3,"");  ?>
	       <? db_input('o15_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
 <tr>
   <td align='center' colspan='2'>
<input name="confirmar" type="submit" id="db_opcao" value="Confirmar" onclick="return js_verificar('botao');" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
   </td>
 </tr>  
 <tr>
   <td colspan='2' align='center'>
<? 

if (isset ($e60_numemp)) {
	$sql = $clempnotaele->sql_query(null, null, "nome,e69_numero,e69_dtnota,e70_codnota,sum(e70_valor) as e70_valor, sum(e70_vlrliq) as e70_vlrliq, sum(e70_vlranu) as    e70_vlranu", "", "e69_numemp=$e60_numemp  and e70_vlrliq = 0 and ((e70_valor-e70_vlranu)<>0)  group by e70_codnota,nome,e69_dtnota,e69_numero ");
	$clempnotaele->sql_record($sql);
	$numrows_nota = $clempnotaele->numrows;
	if ($numrows_nota > 0) {

		$cliframe_seleciona->textocabec = "black";
		$cliframe_seleciona->textocorpo = "black";
		$cliframe_seleciona->fundocabec = "#999999";
		$cliframe_seleciona->fundocorpo = "#cccccc";
		$cliframe_seleciona->iframe_height = "80";
		$cliframe_seleciona->iframe_width = "450";
		$cliframe_seleciona->iframe_nome = "notas";
		$cliframe_seleciona->fieldset = false;

		$cliframe_seleciona->marcador = false;
		$cliframe_seleciona->dbscript = "onclick=\\\"parent.js_calcula(this);\\\"";

		$cliframe_seleciona->campos = "e70_codnota,e69_numero,e69_dtnota,e70_valor,e70_vlrliq,e70_vlranu";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->input_hidden = true;
		$cliframe_seleciona->chaves = "e70_codnota";
		$cliframe_seleciona->iframe_seleciona($db_opcao);
	}

	//rotina que monta campos com elementos e seus valores...
	$sql = $clempnotaele->sql_query(null, null, "e70_codnota,e70_codele,e70_valor,e70_vlrliq,e70_vlranu", "", "e69_numemp=$e60_numemp and e70_vlrliq=0 and ((e70_valor-e70_vlranu)<>0)");
	$result = $clempnotaele->sql_record($sql);
	$numrows = $clempnotaele->numrows;
	if ($numrows > 0) {
		for ($i = 0; $i < $numrows; $i ++) {
			db_fieldsmemory($result, $i);
			echo "\n<input id='nota_$e70_codnota' name='nota_$e70_codnota' type='hidden' value='".$e70_codele."_".$e70_valor."'>";
		}
	}
}
?>
   </td>
 </tr>
    </table>	  
     </td> 
     <td>&nbsp;</td>
     <td valign='bottom'>
    <table border='1' cellspacing='0' cellpadding='0' class='bordas'>	  
<? 

if (isset ($e60_anousu) && $e60_anousu < db_getsession("DB_anousu")) {
?>
	<tr class='bordas'>
	  <td  colspan='2' align='center'>
	    <b style='color:red'>RESTO À PAGAR</b>
	  </td>
	</tr>
<?


}
?>	
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>EMPENHO</small></b>
	  </td>
	</tr>
  <tr class='bordas'>
    <td  class='bordas'nowrap title="<?=@$Te60_vlremp?>">
      <?=@$Le60_vlremp?>
    </td>
    <td class='bordas'> 
<?


db_input('e60_vlremp', 15, $Ie60_vlremp, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
    <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
       <?=@$Le60_vlranu?>
    </td>
    <td class='bordas'> 
<?

 db_input('e60_vlranu', 15, $Ie60_vlranu, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
    <td class='bordas' nowrap title="<?=@$Te60_vlrliq?>">
       <?=@$Le60_vlrliq?>
    </td>
    <td class='bordas'> 
<?

 db_input('e60_vlrliq', 15, $Ie60_vlrliq, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
    <td class='bordas' nowrap title="<?=@$Te60_vlrpag?>">
       <?=@$Le60_vlrpag?>
    </td>
    <td class='bordas'> 
<?

 db_input('e60_vlrpag', 15, $Ie60_vlrpag, true, 'text', 3, '')
?>
    </td>
  </tr>
<?

 if (isset ($e60_numemp)) {
	$result = $clempnotaele->sql_record($clempnotaele->sql_query(null, null, "sum(e70_valor) as tot_valorn, sum(e70_vlrliq) as tot_vlrliqn, sum(e70_vlranu) as tot_vlranun", "", "e69_numemp=$e60_numemp"));
	if ($clempnotaele->numrows > 0) {
		db_fieldsmemory($result, 0, true);
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap>
	    <b><small>NOTA</small></b>
	  </td>
	</tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te70_valor?>">
	       <?=@$Le70_valor?>
	    </td>
	    <td class='bordas'> 
	<?


		db_input('tot_valorn', 15, $Ie70_valor, true, 'text', 3, '')
?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te70_vlrliq?>">
	       <?=@$Le70_vlrliq?>
	    </td>
	    <td class='bordas'> 
	<?

 db_input('tot_vlrliqn', 15, $Ie70_vlrliq, true, 'text', 3, '')
?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te70_vlranu?>">
	       <?=@$Le70_vlranu?>
	    </td>
	    <td class='bordas'> 
	<?

 db_input('tot_vlranun', 15, $Ie70_vlranu, true, 'text', 3, '')
?>
	    </td>
	  </tr>

<?


}
}
?>
<?


if (isset ($e60_numemp)) {
	$result = $clpagordemele->sql_record($clpagordemele->sql_query(null, null, "sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu", "", "e60_numemp=$e60_numemp"));
	if ($clpagordemele->numrows > 0) {
		db_fieldsmemory($result, 0, true);
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>ORDEM</small></b>
	  </td>
	</tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	       <?=@$Le53_valor?>
	    </td>
	    <td class='bordas'> 
	<?


		db_input('tot_valor', 15, $Ie60_vlranu, true, 'text', 3, '')
?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te53_vlrpag?>">
	       <?=@$Le53_vlrpag?>
	    </td>
	    <td class='bordas'> 
	<?

 db_input('tot_vlrpag', 15, $Ie53_vlrpag, true, 'text', 3, '')
?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te53_vlranu?>">
	       <?=@$Le53_vlranu?>
	    </td>
	    <td class='bordas'> 
	<?

 db_input('tot_vlranu', 15, $Ie53_vlranu, true, 'text', 3, '')
?>
	    </td>
	  </tr>

<?


}
}
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>SALDO</small></b>
	  </td>
	</tr>
  <tr>
    <td class='bordas' nowrap title="Valor que deseja anular">
       <b>Valor disponível:</b>
    </td>
    <td class='bordas'> 
<?


db_input('vlrdis', 15, 0, true, 'text', 3);
?>
    </td>
  </tr>
  <tr class='bordas'>
    <td class='bordas' nowrap title="Valor que deseja liquidar">
       <b>Valor à liquidar:</	b>
</td>
    <td class='bordas'> 
<?


db_input('vlrliq', 15, 4, true, 'text', $db_opcao, "onchange='js_verificar(\"campo\");'");
?>
    </td>
  </tr>
  </table>
     </td>
     </tr>
     <tr>
  <td colspan='4' align='left'>
    <br>
    <iframe name="elementos" id="elementos" src="forms/db_frmempliquida_elementos.php?db_opcao=<?=$db_opcao?>&e60_numemp=<?=@$e60_numemp?>" width="760" height="100" marginwidth="0" marginheight="0" frameborder="0">
    </iframe>
  </td>
 </tr> 
 </table>
  </center>
</form>
<script>
//============================================================================================
//função responsavel de pegar o valor da nota cliacada e colocar no campo para estornar	    


cont = new Number(0);
function js_calcula(campo){
     obj = document.form1.elements; 
     soma_total  = new Number();


        if(campo.checked==true){
   	  if(cont == '0' ){
	    elementos.js_coloca('0.00',true,false);
	    document.form1.vlrliq.value = "0.00";
            document.form1.vlrliq.readOnly=true;
	    document.form1.vlrliq.style.backgroundColor = '#DEB887';
	    document.form1.vlrliq.value = '0.00';
            elementos.js_tranca(); 
          }
    	  cont++;
	}else{ 
	     cont--;
	    if(cont == '0' ){
	      document.form1.vlrliq.readOnly=false;
              document.form1.vlrliq.style.backgroundColor = 'white';
	      elementos.js_coloca('0.00',true,false);
   	      document.form1.vlrliq.value = '0.00';
              elementos.js_libera(); 
	      return true;
	    }   
        }


  //atualiza os valores  
    
     eleval = '';
     vir='';
    nota = campo.value;
     for(w=0; w<obj.length; w++){
       nome = obj[w].name;


       if(nome == "nota_"+campo.value ){
	 eles = obj[w].value;
         arr_eles = eles.split("_");
	 elemento = arr_eles[0];
	 valor    = new Number(arr_eles[1]);
         soma_total += valor;
         eleval += vir+nota+"-"+elemento+"-"+valor;
          vir='#';
       } 
     }

	  if(campo.checked==true){
              elementos.js_coloca_notas(eleval,true);
	  }else{
              elementos.js_coloca_notas(eleval,false);
	  }  
	  document.form1.vlrliq.value =  soma_total;

//          elementos.js_coloca(soma_total,true);   
//    elementos.js_calcular();
}
//================================================================================================



<?


if (isset ($e60_numemp)) {
	if (($vlrdis == 0 || $vlrdis == '') || isset ($tranca)) { //var tranca é para quando o valor disponivel da nota for zero
		echo "document.form1.confirmar.disabled=true;";
		if (empty ($confirmar) && empty ($novo) && empty ($tranca)) {
			echo "alert(\"Não existe valor disponível para liquidar!\");\n";
		} else
			if (isset ($tranca) && empty ($novo) && empty ($confirmar)) {
				echo "alert(\"Não existe valor disponível com nota para liquidar!\");\n";
			}
	}

	$saldon = $vlrdis - ($tot_valorl - $tot_vlranul);
?>
  
      function js_verificar(tipo){

	  if(tipo=='botao'){
	      if(document.form1.vlrliq.value == '' || document.form1.vlrliq.value == 0 ){
	        alert('Informe o valor à ser liquidado!');
	        return false;
	      }
	      /**
              * verifica o valor dos elementos se confere  
	      */
	      erro = true;
 	      for(i=0;i<elementos.document.form1.length;i++){
	        if(elementos.document.form1.elements[i].name.substr(0,9)=="generico_"){
		     valor = Number(elementos.document.form1.elements[i].value);
		     if(valor>0){
		       erro = false
		     }
	        } // end if
	      } // end for
	      if (erro==true){
	         alert("Informe o valor a liquidar!");      
	         return false;
	      }
    
	  }  
	
	saldon =  new Number(<?=$saldon?>);
        erro=false; 
	vlrliq= new Number(document.form1.vlrliq.value);
       if(tipo == 'campo'){ 
	  if(vlrliq > saldon){
	    alert("O valor díponivel para liquidar sem notas é "+saldon+"!");
   	    erro= true;
   	  }
        }
	
	if(isNaN(vlrliq)){
	  erro=true;
	}

	if(erro==false){
	  val = vlrliq.toFixed(2);
	  document.form1.vlrliq.value=val;
	  if(tipo=='campo'){
	    elementos.js_coloca(val,false,false);
	  }else{
	    elementos.js_calcular();
	  }
<?if($numrows_nota>0){?>       
	  js_gera_chaves();
<?}?>	  
	  return true;
	}else{
          document.form1.vlrliq.focus();
          document.form1.vlrliq.value=saldon;
	  elementos.js_coloca(saldon,false,false);
	  return false;
	}
	
      }
       
<?



}
?>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  <?


echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?e60_numemp='+chave";
?>
}
</script>