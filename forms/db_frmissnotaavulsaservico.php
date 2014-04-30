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

//MODULO: issqn
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clissnotaavulsaservico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q51_sequencial");
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
     $q62_issnotaavulsa = "";
     $q62_qtd = "";
     $q62_discriminacao = "";
     $q62_vlruni = "";
     $q62_aliquota = "";
     $q62_vlrdeducao = "";
     $q62_vlrtotal = "";
     $q62_vlrbasecalc = "";
     $q62_vlrissqn = "";
     $q62_obs = "";
   }
} 
$SQLTotLinhas  = "select q62_discriminacao";
$SQLTotLinhas .= "  from issnotaavulsaservico ";
$SQLTotLinhas .= " where q62_issnotaavulsa = {$get->q51_sequencial}";
$rsTotLInhas  = pg_query($SQLTotLinhas);
$totlinhas    = 0;
if (pg_num_rows($rsTotLInhas) > 0){

  for ($i = 0; $i < pg_num_rows($rsTotLInhas); $i++){
    
    $oLinha     = db_utils::fieldsMemory($rsTotLInhas,$i);
    $totlinhas += db_calculaLinhasTexto22($oLinha->q62_discriminacao);

  }

}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<tr><td>
<fieldset><legend><b>Serviços</b></legend>
<table>
<?
$q62_issnotaavulsa = $get->q51_sequencial;
db_input('q62_issnotaavulsa',10,$Iq62_issnotaavulsa,true,'hidden',$db_opcao," onchange='js_pesquisaq62_issnotaavulsa(false);'");
db_input('q62_sequencial',10,$Iq62_sequencial,true,'hidden',$db_opcao,"");
?>
  <tr>
    <td nowrap title="<?=@$Tq62_qtd?>">
       <?=@$Lq62_qtd?>
    </td>
    <td> 
<?
db_input('q62_qtd',10,$Iq62_qtd,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq62_discriminacao?>">
       <?=@$Lq62_discriminacao?>
    </td>
    <td colspan='3'> 
<?
db_textarea('q62_discriminacao',2,57,$Iq62_discriminacao,true,'text',$db_opcao,"onkeyup=''");

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq62_vlruni?>">
       <?=@$Lq62_vlruni?>
    </td>
    <td> 
<?
db_input('q62_vlruni',12,$Iq62_vlruni,true,'text',$db_opcao,"onblur='js_calcula()'");
?>
    </td>
    <td nowrap title="<?=@$Tq62_vlrtotal?>">
       <?=@$Lq62_vlrtotal?>
    </td>
    <td> 
<?
db_input('q62_vlrtotal',15,$Iq62_vlrtotal,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq62_vlrdeducao?>">
       <?=@$Lq62_vlrdeducao?>
    </td>
    <td> 
<?
db_input('q62_vlrdeducao',12,$Iq62_vlrdeducao,true,'text',$db_opcao, "onblur=\"js_calcula();\"")
?>
    </td>
    <td nowrap title="<?=@$Tq62_vlrbasecalc?>">
       <?=@$Lq62_vlrbasecalc?>
    </td>
    <td> 
<?
db_input('q62_vlrbasecalc',15,$Iq62_vlrbasecalc,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq62_aliquota?>">
       <?=@$Lq62_aliquota."%"?>
    </td>
    <td> 
<?
db_input('q62_aliquota',12,$Iq62_aliquota,true,'text',$db_opcao,"onBlur='js_calcula()'")
?>
    </td>
    <td nowrap title="<?=@$Tq62_vlrissqn?>">
       <?=@$Lq62_vlrissqn?>
    </td>
    <td> 
<?
db_input('q62_vlrissqn',15,$Iq62_vlrissqn,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq62_obs?>">
       <?=@$Lq62_obs?>
    </td>
    <td colspan='3'> 
<?
db_textarea('q62_obs',0,57,$Iq62_obs,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,200);'");
?>
    </td>
  </tr>
	</table>
	</fieldset>
	</td></tr>
  </tr>
    <td colspan="2" align="center">
    <input type='hidden' id='totlinhas' readonly name='totlinhas' value="<?=$totlinhas;?>">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
  <input name="recibo" type="submit"  onclick='return js_emiteRecibo(<?=$oPar->q60_notaavulsavlrmin;?>)' id="recibo" value="Emitir Recibo" > 
	 <?
      $fTotal    = 0;
			$sql       = "select sum(q62_vlrissqn) as totalissqn,";
      $sql .= "sum(q62_vlruni) as q62_vlrini,";
      $sql .= "sum(q62_vlrdeducao) as q62_vlrdeducao,";
      $sql .= "sum(q62_vlrtotal) as q62_vlrtotal,";
      $sql .= "sum(q62_vlrbasecalc) as q62_vlrbasecalc";
			$sql .= " from issnotaavulsaservico
 									where q62_issnotaavulsa = ".$q62_issnotaavulsa;
      $oTotal = db_utils::fieldsMemory(pg_query($sql),0);            
      $totalissqn = $oTotal->totalissqn;
      if (($lGeraNota and $emitenota) or ($oPar->q60_notaavulsavlrmin > $totalissqn )){

           echo " <input name='notaavulsa' onclick='return js_verificaNota();' type='submit' id='nota' value='Emitir nota'>";

      }

   ?>
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("q62_sequencial"=>$get->q51_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clissnotaavulsaservico->sql_query_file(null,"*","q62_sequencial"
	                                     ,"q62_issnotaavulsa=".$get->q51_sequencial);
	 $cliframe_alterar_excluir->campos  ="q62_sequencial,q62_issnotaavulsa,q62_qtd,q62_discriminacao,q62_vlruni,q62_aliquota,q62_vlrdeducao,q62_vlrtotal,q62_vlrbasecalc,q62_vlrissqn";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
   if (isset($post->recibo) || isset($post->notaavulsa)){

     $cliframe_alterar_excluir->opcoes = 4;

   }
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
	 <tr>
   <td align="left">
      <table class='tab' width='100%'>
      <tr style='text-align:right'>
      <th rowspan='2'><B>TOTAIS</b></th>
      <th><b>Deduções</b></th>
      <th><b>Valor Total</b></th>
      <th><b>Base Cálculo </b></th>
      <th><b>valor ISSQN </b></th>
      </tr>
      <tr>
      <td>
      <?=number_format($oTotal->q62_vlrdeducao,2,",",".")?>
      </td>
      <td>
      <?=number_format($oTotal->q62_vlrtotal,2,",",".")?>
      </td>
      <td>
      <?=number_format($oTotal->q62_vlrbasecalc,2,",",".")?>
      </td>
      <td>
		   <input type='' id='vlrrectotal' 
         readonly style='border:0;background:transparent'value='<?=number_format($totalissqn,2,',','.');?>'name='vlrrectotal'>
	 </td>
	 </tr>
 </table>
  </center>
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
function js_pesquisaq62_issnotaavulsa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsaservico','db_iframe_issnotaavulsa','func_issnotaavulsa.php?funcao_js=parent.js_mostraissnotaavulsa1|q51_sequencial|q51_sequencial','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.q62_issnotaavulsa.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsaservico','db_iframe_issnotaavulsa','func_issnotaavulsa.php?pesquisa_chave='+document.form1.q62_issnotaavulsa.value+'&funcao_js=parent.js_mostraissnotaavulsa','Pesquisa',false);
     }else{
       document.form1.q51_sequencial.value = ''; 
     }
  }
}
function js_mostraissnotaavulsa(chave,erro){
  document.form1.q51_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.q62_issnotaavulsa.focus(); 
    document.form1.q62_issnotaavulsa.value = ''; 
  }
}
function js_mostraissnotaavulsa1(chave1,chave2){
  document.form1.q62_issnotaavulsa.value = chave1;
  document.form1.q51_sequencial.value = chave2;
  db_iframe_issnotaavulsa.hide();
}

function js_setValorTotal(){
 
   iQtde  =  new Number(document.getElementById('q62_qtd').value);
   dVlUni =  new Number(document.getElementById('q62_vlruni').value);
   dTotal = (iQtde*dVlUni);
	 dTotal = js_round(dTotal,2);
	 document.getElementById('q62_vlrtotal').value = dTotal;
}

function js_setValorIssqn(){

   dBaseCalc =  new Number(document.getElementById('q62_vlrbasecalc').value);
   dAliquota =  new Number(document.getElementById('q62_aliquota').value);
   dTotal    = (dBaseCalc*(dAliquota/100));
	 dTotal    = js_round(dTotal,2);
	 document.getElementById('q62_vlrissqn').value = dTotal;
}

function js_setValorBaseCalculo(){

   dDeducoes =  new Number(document.getElementById('q62_vlrdeducao').value);
   dVlTotal  =  new Number(document.getElementById('q62_vlrtotal').value);
   dTotal    = (dVlTotal-dDeducoes);
	 dTotal   = js_round(dTotal,2);
	 document.getElementById('q62_vlrbasecalc').value = dTotal;
}
function js_testaDeducao(){

  dDeducao = new Number(document.getElementById('q62_vlrdeducao').value);
  dVlTotal =  new Number(document.getElementById('q62_vlrtotal').value); 
  if (dDeducao != 0 && (dDeducao > dVlTotal)){

     document.getElementById('q62_vlrdeducao').value = '';
     alert('Valor da Deducao nao pode ser maior que o valor total');
     document.getElementById('q62_vlrdeducao').focus();
  }
}

function js_calcula(){
 
   js_setValorTotal();
   js_testaDeducao();
	 js_setValorBaseCalculo();
	 js_setValorIssqn();

} 
function js_emiteRecibo(valMin){

   valNota = $F('vlrrectotal').replace(".",''); 
   valNota = valNota.replace(",",'.'); 
   valNota = new Number(valNota);
   valMin  = new Number(valMin);

   if ($F('totlinhas') > 40){

      alert('Total das linhas da descrição da nota maior que o permitido (40 linhas)');
      return false;

   }
   if (valNota >= valMin){
   	   parent.iframe_issnotaavulsa.document.getElementById('db_opcao').disabled = true;
   	   parent.iframe_issnotaavulsa.document.getElementById('novaNota').style.display = "";

   	   parent.iframe_issnotaavulsatomador.document.getElementById('db_opcao').disabled 	  = true;
   	   parent.iframe_issnotaavulsatomador.js_controlaAncora(false);
 	      	      	   
       return true;
   }else{
      alert('Recibo não pode ser emitido.\nValor do imposto menor que o  valor configurado R$'+valMin);
      return false;
   }
   
}
function js_verificaNota(){

   if ($F('totlinhas') > 40){

      alert('Total das linhas da descrição da nota maior que o permitido (40 linhas)');
      return false;

   }
}
function js_controlatextarea(objt,max){
  obj = eval('document.form1.'+objt);
  atu = max-obj.value.length;
  if(obj.value.length > max){
	  alert('A mensagem pode ter no máximo '+max+' caracteres !');
	  obj.value = obj.value.substr(0,max);
	  obj.select();
	  obj.focus();
  }
}

</script>
<?
?>