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

$clrotulo = new rotulocampo;
$clrotulo->label('e53_valor');
?>
<div id="processando" style="position:absolute; left:0px; top:0px; width:'100%'; height:581px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="center" id="texto">
    </td>
  </tr>
</table>
</div>
<form name="form1" method="post">
<table border=0 style="border:1px solid #999999" id="tabreceitas">
  <tr>
    <td colspan=3>&nbsp;</td>
    <td width=10% nowrap>
      <b>Valor da Nota</b>
    </td>
    <td align=right width=10%>
      <?db_input('valor_nota', 15, '', true, 'text',3, '','','','text-align:right')?>
    </td> 
  </tr>
  <tr bgcolor="#BDC6BD">
    <td>
      &nbsp;
    </td>
    <td>
      <b>REGRA</b>
    </td>
    <td>
      <b>DESCRIÇÃO</b>
    </td>
    <td>
      <b>ALÍQUOTA</b>
    </td>
    <td align=center>
      <b>VALOR</b>
    </td>
  </tr>
  <?
  $retencoes = array ();
  $res = $clpagordemtiporec->sql_record($clpagordemtiporec->sql_query_retencao(null, "e65_seq, k02_drecei, e59_codrec, e59_aliquota,
                                                                                      case when e65_receita is null then e59_codrec else e65_receita end as e65_receita,
                                                                                      case when e65_aliquota is null then e59_aliquota else e65_aliquota end as e65_aliquota,
                                                                                      case when e65_valor is null then 0 else e65_valor end as e65_valor
                                                                                     ", null, " e59_codrec is not null or (e59_codrec is null and e65_seq is not null) ",$e66_autori));
  if($clpagordemtiporec->numrows > 0){
    $cont = 1;
    for ($x = 0; $x < $clpagordemtiporec->numrows; $x ++) {
      db_fieldsmemory($res, $x);
      $marca = false;
      $bloqueia_aliquota = 3;
      if(trim($e65_seq) != ""){
        if(trim($e59_codrec) == "" || (trim($e59_codrec) != "" && $e59_aliquota != $e65_aliquota)){
          $bloqueia_aliquota = 1;
        }
        $e59_codrec = $e65_receita;
        $e59_aliquota = $e65_aliquota;
        $v = 'valor_chk_'.$cont;
        $$v = $e65_valor;
        $marca = true;
      }
      ?>
      <tr id="ret_<?=$x?>" class="tr_tab">
        <td>
          <input id="chk_<?=$cont?>" type=checkbox name=regra <?=($op==1?"":"disabled")?>  <?=($marca==true?"checked":""); ?> onChange="js_calculaRetencao(this,false);">
        </td>
        <td>
          <? 
          $v  = 'receita_chk_'.$cont;
          $$v = $e59_codrec;
          global $$v;                  
          db_input('receita_chk_'.$cont, 10, '', true, 'text',3);
          ?>
        </td>
        <td><?=$k02_drecei?></td>
        <td align=right>
          <?
          $v = 'aliquota_chk_'.$cont;
          $$v   = $e59_aliquota;
          global $$v;
          db_input('aliquota_chk_'.$cont, 15, $Ie53_valor, true, 'text',$bloqueia_aliquota, '','','','text-align:right')
          ?>
        </td>
        <td align=right>
          <?
          db_input('valor_chk_'.$cont, 15, $Ie53_valor, true, 'text',$op, ' onchange=js_testaRetencao();','','','text-align:right')
          ?>
        </td>
      </tr>
      <?
      $cont ++;
    }
  }

  db_input("valores_selecionados", 30, 0, true, 'hidden',3);
  db_input("e66_autori", 8, 0, true, 'hidden',3);

  if(!isset($nao_mostrar_botao)){
  ?>
  <tr>
    <td colspan="5" align="center">
      <input type="submit" name="incluir" value="Enviar Dados" onclick="return retencoes();">
      <input type="button" name="nova_linha" value="Nova receita" onclick="return js_adiciona_linha();">
    </td>
  </tr>
  <?
  }
  ?>
</table>
</form>
<script>
function js_adiciona_linha(){
  js_OpenJanelaIframe('top.corpo.iframe_empautret','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostrareceita|k02_codigo|k02_drecei','Pesquisa',true,0);
}
function js_mostrareceita(chave1, chave2){
  var       tab = document.getElementById("tabreceitas");
  var posLinhas = tab.rows.length - 1;
  var novaLinha = tab.insertRow(posLinhas);

  novaLinha.id = "ret_" + (posLinhas - 1);
  novaLinha.style.backgroundColor = "white";
  novaLinha.style.fontsize        = "8px";
  novaLinha.style.height          = "8px";

  novaColuna = novaLinha.insertCell(0);
  novaColuna.innerHTML = "<input id='chk_" + posLinhas + "' type='checkbox' name='regra' checked onChange='js_calculaRetencao(this,false);'>";

  novaColuna = novaLinha.insertCell(1);
  novaColuna.innerHTML = "<input id='receita_chk_" + posLinhas + "' type='text' id='receita_chk_" + posLinhas + "'  value='" + chave1 + "'  size='10' readonly style='background-color:#DEB887;'>";

  novaColuna = novaLinha.insertCell(2);
  novaColuna.innerHTML = chave2;

  novaColuna = novaLinha.insertCell(3);
  novaColuna.align = 'right';
  novaColuna.innerHTML = "<input id='aliquota_chk_" + posLinhas + "' type='text' id='aliquota_chk_" + posLinhas + "' value='' size='15' onChange='js_calculaRetencao(document.getElementById(\"chk_" + posLinhas + "\"),false);' style='text-align:right'>";

  novaColuna = novaLinha.insertCell(4);
  novaColuna.align = 'right';
  novaColuna.innerHTML = "<input id='valor_chk_" + posLinhas + "' type='text' id='valor_chk_" + posLinhas + "' value='' size='15' onChange='js_calculaRetencao(document.getElementById(\"chk_" + posLinhas + "\"),true);' style='text-align:right'>";

  db_iframe_tabrec.hide();
}
function setValorNota(valor){	
  var vl = new Number(parseFloat(valor));
  document.form1.valor_nota.value=vl.valueOf().toFixed(2);
  js_mostrardiv(false);
}

function retencoes(){

  testaTotais = js_testaRetencao();
  if(testaTotais == false){
    return false;
  }

  var str_lista='';
  var sep = '';	
  obj =  document.form1;
  qtd = obj.length;
  for(linha=0;linha < qtd; linha++){
    if(obj[linha].type=='checkbox'){
      objeto = obj[linha];
      if(objeto.checked==true){
	id = objeto.id; // pega o nome do objeto.
	_receita  = 'receita_'+id;
	_aliquota = 'aliquota_'+id; // captura o objeto aliquota
	_valor     = 'valor_'+id;   // captura o objeto valor
	receita = eval('document.form1.'+_receita+'.value');
	aliquota = eval('document.form1.'+_aliquota+'.value');
	valor     = eval('document.form1.'+_valor+'.value');

	str_lista += sep+'ret_'+receita+'_'+aliquota+'_'+valor; 
	sep='|';
      }
    }
  }
  obj.valores_selecionados.value = str_lista;
  return str_lista;
}

function teste(){
  var lista = retencoes(); // invora a funcao retencoes e pega o retorno
  alert(lista);   
}
function js_calculaRetencao(objeto, opcao){
  valor_nota = document.form1.valor_nota.value;
  id = objeto.id;
  _aliquota = 'aliquota_'+id;
  _valor = 'valor_'+id;
  aliquota = eval('document.form1.'+_aliquota);
  valor    = eval('document.form1.'+_valor);
  if(objeto.checked==true && opcao == false){
    valor.value = (valor_nota * aliquota.value / 100).toFixed(2);
  }else if(objeto.checked==true){
    aliquota.value = (100 * valor.value / valor_nota).toFixed(2);
  }else{
    obj_valor = 'valor_'+id;  
    objeto_nota = eval('document.form1.'+obj_valor);
    objeto_nota.value = '0.00';  	 
  }
}

function js_testaRetencao(){
  valor_nota = document.form1.valor_nota.value;
  obj =  document.form1;
  qtd = obj.length;
  totalRetencao=0;
  for(linha=0;linha < qtd; linha++){
    if(obj[linha].type=='checkbox'){	           
       objeto = obj[linha];
      if(objeto.checked==true){
        id = objeto.id; // peta o nome do objeto.	   	           
        _valor = 'valor_'+id;   // captura o objeto valor
        valor     = eval('document.form1.'+_valor);
        totalRetencao = totalRetencao + parseFloat(valor.value) ;   	    	       
      }
    }
  }    
  totalRetencao = new Number(parseFloat(totalRetencao));
  valor_nota       = new Number(parseFloat(valor_nota));
  if(totalRetencao.valueOf() > valor_nota.valueOf()){
    alert('Valor da retenção '+ totalRetencao.toFixed(2)+' não deve ser maior que o valor da nota '+valor_nota.toFixed(2)+'!');
    return false;
  }
  return true;
}
function js_mostrardiv(TorF){
  <?
  if(!isset($incluir) && !isset($alterar) && !isset($excluir)){
  ?>
  if(TorF == true){
    document.getElementById('processando').style.height = (screen.availHeight-155)+'px';
    document.getElementById('processando').style.visibility = 'visible';
    document.getElementById('texto').innerHTML = '<h3>Aguarde, processando valores...</h3>';
  }else{
    document.getElementById('processando').style.visibility = 'hidden';
    document.getElementById('texto').innerHTML = '';
  }
  <?}?>
}
</script>