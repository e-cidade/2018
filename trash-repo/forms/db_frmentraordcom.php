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


include ("classes/db_db_depusu_classe.php");
include ("classes/db_db_almoxdepto_classe.php");

$cldb_depusu     = new cl_db_depusu;
$cldb_almoxdepto = new cl_db_almoxdepto;
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
$clrotulo->label("m51_codordem");
$clrotulo->label("e70_valor");
$clrotulo->label("m51_valortotal");
$clrotulo->label("m80_obs");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m90_liqentoc");
$depto_atual = db_getsession("DB_coddepto");
$usu_atual = db_getsession("DB_id_usuario");
$vlrtot = 0;
if (isset ($atualiza)) {
  $e70_valor = db_formatar($e7_valor, 'p');
}
if (isset ($m51_codordem) && $m51_codordem != '') {
  $result_credor = $clmatordem->sql_record($clmatordem->sql_query($m51_codordem, "m51_valortotal,z01_nome,m51_depto, m51_depto as departamento , descrdepto", null, "m51_codordem=$m51_codordem"));
  if ($clmatordem->numrows != 0) {
    db_fieldsmemory($result_credor,0);
    $vlrtot = $m51_valortotal;
    $result_matparam=$clmatparam->sql_record($clmatparam->sql_query_file(db_getsession("DB_instit")));
    if ($clmatparam->numrows>0){
      db_fieldsmemory($result_matparam,0);
    }
    if(isset($m90_tipocontrol)&&$m90_tipocontrol=="F"){
      $result_almox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null, $depto_atual));
      if ($cldb_almoxdepto->numrows!=0){
        db_fieldsmemory($result_almox,0);
        $result_almox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file($m92_codalmox, $m51_depto));
        if ($cldb_almoxdepto->numrows==0){
          db_msgbox('Voc� n�o tem acesso ao Almox. da Ordem de Compra!!');
          echo "<script>location.href='mat1_entraordcom001.php';</script>";					
        }
      }else{
        db_msgbox('Voc� n�o tem acesso ao Almox. da Ordem de Compra!!');
        echo "<script>location.href='mat1_entraordcom001.php';</script>";		
      }
    }else{ 		
      if ($m51_depto != $depto_atual) {
        $result_depusu = $cldb_depusu->sql_record($cldb_depusu->sql_query($usu_atual, $m51_depto, "db_depusu.coddepto as departamento,descrdepto"));
        if ($cldb_depusu->numrows == 0) {
          db_msgbox('Voc� n�o tem acesso ao Departamento da Ordem de Compra!!');
          echo "<script>location.href='mat1_entraordcom001.php';</script>";
        } else {
          db_fieldsmemory($result_depusu, 0);
        }			
      }
    }
  } else {
    db_msgbox('N� da Ordem de Compra Inv�lido!!');
    echo "<script>location.href='mat1_entraordcom001.php';</script>";
  }
  /*$result_temitem=$clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,"sum(m71_quant) as quant_est,sum(m52_quant) as quant_ord",null,"m52_codordem=$m51_codordem"));
  if ($clmatestoqueitemoc->numrows>0){
    db_fieldsmemory($result_temitem,0);    
    if ($quant_est>0){
      if ($quant_est==$quant_ord){
        db_msgbox('J� foi dada entrada para todos os itens da Ordem de Compra!!');
        echo "<script>location.href='mat1_entraordcom001.php';</script>";    
      }
    }
  }*/
} else {
  echo "<script>location.href='mat1_entraordcom001.php';</script>";
}
$opcao = 1;
?>
<style>
<?$cor="#999999"?>
.bordas02{
  border: 2px solid #cccccc;
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
}
</style>
<form name="form1" method="post" action="mat1_entraordcom002.php" onsubmit='return js_buscavalores();'>
<center>
<table border="0" width="100%">
<tr align = 'left'>
<td align="left">
<table align="center" border="0"><br>
<tr>
<td nowrap title="<?=@$Tm51_codordem?>">
<b>
<?

if (isset ($e7_valor) && $e7_valor != "") {
  //$e70_valor=str_replace('.','',$e70_valor);
  //$e70_valor=str_replace(',','.',$e70_valor);
  $valor_nota = db_formatar($e7_valor, 'p');
  db_input('valor_nota', 10, '', true, 'hidden', 3);
  $e7_valor = db_formatar($e7_valor, 'p');
  $opcao = 3;
} else {
  $opcao = 1;
}
db_ancora("Ordem :", "js_consultaordem($m51_codordem);", 1);
?>
</b>
</td>
<td nowrap > 
<?


db_input('m51_codordem', 5, $Im51_codordem, true, 'text', 3);
db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3, '');
?>
</td>
<td nowrap title="<?=@$Te69_numero?>"  >
<?=@$Le69_numero?>
</td>
<td  > 
<?


db_input('e69_numero', 20, $Ie69_numero, true, 'text', $opcao, "")
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Te69_id_usuario?>">
<? 
$e69_id_usuario = db_getsession("DB_id_usuario");
db_ancora(@ $Le69_id_usuario, "js_pesquisae69_id_usuario(true);", $opcao);
?>
</td>
<td>
<?


db_input('e69_id_usuario', 5, $Ie69_id_usuario, true, 'text', $opcao, " onchange='js_pesquisae69_id_usuario(false);'")
?>
<?$result=$clusuarios->sql_record($clusuarios->sql_query_file("","nome","","id_usuario=$e69_id_usuario"));
db_fieldsmemory($result,0);
db_input('nome',30,$Inome,true,'text',3,'');
?>
</td>
<td>
<?=@$Le69_dtrecebe?>
</td>
<td>
<?

if (empty ($e69_dtrecebe_dia)) {
  $e69_dtrecebe_dia = date("d", db_getsession("DB_datausu"));
  $e69_dtrecebe_mes = date("m", db_getsession("DB_datausu"));
  $e69_dtrecebe_ano = date("Y", db_getsession("DB_datausu"));
  
}
db_inputdata('e69_dtrecebe', @ $e69_dtrecebe_dia, @ $e69_dtrecebe_mes, @ $e69_dtrecebe_ano, true, 'text', $opcao, "");
?>
</td>
</tr>
<tr>
<td nowrap title='Departamento da Ordem' >
<b>Departamento: </b>
</td>
<td  > 
<?


db_input('departamento', 5, $Icoddepto, true, 'text', 3);
db_input('descrdepto', 30, $Idescrdepto, true, 'text', 3, '');
?>
</td>
<td nowrap title="<?=@$Te69_dtnota?>">
<?=@$Le69_dtnota?>
</td>
<td> 
<?



if (empty ($e69_dtnota_dia)) {
  $e69_dtnota_dia = date("d", db_getsession("DB_datausu"));
  $e69_dtnota_mes = date("m", db_getsession("DB_datausu"));
  $e69_dtnota_ano = date("Y", db_getsession("DB_datausu"));
  
}
db_inputdata('e69_dtnota', @ $e69_dtnota_dia, @ $e69_dtnota_mes, @ $e69_dtnota_ano, true, 'text', $opcao, "");
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$m51_valortotal?>"  >
<b> Valor da Ordem: </b>
</td>
<td>
<?


$vlrtot = db_formatar($vlrtot, 'p');
db_input('vlrtot', 20, $Im51_valortotal, true, 'text', 3, "");
?>
</td>
<td nowrap title="<?=@$e70_valor?>"  >
<b> Valor da Nota: </b>
</td>
<td>
<?

$lancado = 0;
$result_lancado = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null, null, "sum(m71_valor) as soma", null, "m52_codordem=$m51_codordem"));
if ($clmatestoqueitemoc->numrows != 0) {
  db_fieldsmemory($result_lancado, 0);
  $lancado = $soma;
} else {
  $lancado = 0;
}

if ($opcao != 3) {
  $e7_valor = @$m51_valortotal - $lancado;
}

//echo("m51_valortotal: $m51_valortotal - lancado: $lancado");

db_input('e7_valor', 20, $Ie70_valor, true, 'text', $opcao, "");
db_input('e70_valor', 20, $Ie70_valor, true, 'hidden', 3, "");

?>
</td>
</tr>
<?
   if (isset ($atualiza) || isset ($e70_valor) && $e70_valor != "") {
        $db_opcao = 3;
   } else {
        $db_opcao = 1;
   }

   $resultado = $clmatparam->sql_record($clmatparam->sql_query_file(null,"m90_liqentoc"));
   if ($clmatparam->numrows > 0){
        db_fieldsmemory($resultado,0); 
   }

   if ($m90_liqentoc=="t"){
?>
<tr>
    <td nowrap title="<?=@$Tm90_liqentoc?>"><b>Gerar nota de liquida��o:</b></td>
    <td> 
<?
$x = array('true'=>'Sim','false'=>'N�o');
db_select('gravanota',$x,true,$db_opcao,"");
?>
    </td>
</tr>
<?
   }
?>
</table>
<?


$m51_depto = $m51_depto;
db_input("m51_depto", 5, "", true, "hidden", 3);
if (isset ($atualiza) || isset ($e70_valor) && $e70_valor != "") {
  ?>
  <center>
  <table border="0" width="900">
  <tr>
  <td align='right' >
  <input name="zeraquant" type="button" value="Zera Quant." onclick="js_atualizaquant(true,'<?=$m51_codordem?>' );" >
  <input name="preenchequant" type="button" value="Preenche Quant." onclick="js_atualizaquant(false,false);" >
  </td>
  </tr>
  <tr>
  <td>
  <iframe name="itens" id="itens" src="mat1_entraordcomitemiframe.php?m51_codordem=<?=$m51_codordem?>" width="790" height="420" marginwidth="0" marginheight="0" frameborder="0">
  </iframe>
  </td>
  </tr>
  <tr>
  <td align='right'>
  <b>
  <?
  
  $lancado = db_formatar("$lancado", 'p');
  $valortotal = db_formatar(@ $m51_valortotal, 'p');
	
  ?>
  Total da Ordem:<?db_input('valortotal',15,'',true,'text',3)?>Valor Lan�ado:<?db_input('lancado',15,'',true,'text',3)?>A Lan�ar:<?db_input('alancar',15,'',true,'text',3)?>
  </b>
  </td>
  </tr>
  </table>
  </center>
  <?
  
  
}
?>
</td>
</tr>
<tr>
<td align="center">
<?


if (isset ($atualiza) || isset ($e70_valor) && $e70_valor != "") {
  db_input("m80_obs", 100, "", true, "hidden", 3);
} else {
  echo "<b>Observa��o:</b>";
  db_textarea('m80_obs', 0, 80, $Im80_obs, true, "text", 1, "");
}
?>
</td>
</tr>
<?if (isset($atualiza)||isset($e70_valor)&&$e70_valor!=""){?>
  <tr align = "center">
  <td>
  <input name="confirma" type="submit"  value="Confirma">
  <input name="voltar" type="button" value="Voltar" onclick="location.href='mat1_entraordcom001.php';" >
  <? 
  
  $m51_codordem = $m51_codordem;
  db_input("m51_codordem", 5, "", true, "hidden", 3);
  ?>
  </td>
  </tr>
<?}else{
?>
  <tr align = "center">
  <td>
  <input name="atualiza" type="submit"  value="Confirma" onclick="return js_testaval('<?=$m51_valortotal?>');">
  <input name="voltar" type="button" value="Voltar" onclick="location.href='mat1_entraordcom001.php';" >
  <? 
  
  $m51_codordem = $m51_codordem;
  db_input("m51_codordem", 5, "", true, "hidden", 3);
  ?>
  </td>
  </tr>
<?}?>

</table>
</center>
<?


db_input("valores", 100, 0, true, "hidden", 3);
db_input("val", 100, 0, true, "hidden", 3);
db_input("valmul", 100, 0, true, "hidden", 3);
db_input("codmatmater", 100, 0, true, "hidden", 3);
db_input("codunidade", 100, 0, true, "hidden", 3);
?>
</form>
</body>
<script>
//----------------------------------------------------------
function js_testaval(valorordem){
  if (document.form1.e7_valor.value!=""){
    if (document.form1.e69_numero.value!=""){
      valorordem=new Number(valorordem);
      valornota=new Number(document.form1.e7_valor.value);
      if ( valorordem >= valornota ){
        return true;
      }else{
        alert("Valor da nota n�o pode ser maior que o valor da Ordem de Compra!!");
        return false;
      }
    }else{
      alert('Campo numero da nota n�o informado!!');
      document.form1.e69_numero.focus();
      return false;
    }
  }else{
    alert('Campo valor da nota n�o informado!!');
    document.form1.e7_valor.focus();
    return false;
  }
}
//----------------------------------------------------------
function js_consultaordem(codordem){
  js_OpenJanelaIframe('top.corpo','db_iframe_ordemcompra002','emp3_ordemcompra002.php?m51_codordem='+codordem,'.:Consulta Ordem de Compra:.',true);
}
//---------------------------------------------------------
function js_pesquisae69_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    if(document.form1.e69_id_usuario.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.e69_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
    }else{
      document.form1.nome.value = ''; 
    }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.e69_id_usuario.focus(); 
    document.form1.e69_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.e69_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
//-------------------------------------------------------------
<?if (isset($atualiza)||isset($e70_valor)&&$e70_valor!=""){

  ?>
  function js_buscavalores(){
		
    erro = 0;
    obj= itens.document.form1;
    valor="";
    valormul="";
    valoritem="";
    cods="";
    coduni="";
    totitem = 0;
    vltotal = 0;
    vlrnota = 0;

		var valorpai=0;
		
    for (i=0;i<obj.elements.length;i++){

      if (obj.elements[i].name.substr(0,8)=="coditem_"){
        var objvalorcod=new Number(obj.elements[i].value);
        if (obj.elements[i].name.substr(9,5)!='descr'&&obj.elements[i].name.substr(10,5)!='descr'){
          cods+=obj.elements[i].name+"_"+obj.elements[i].value;
        }
      }
      document.form1.codmatmater.value = cods;
      if (obj.elements[i].name.substr(0,6)=="quant_"){
        var objvalor=new Number(obj.elements[i].value);
        valor+=obj.elements[i].name+"_"+obj.elements[i].value;
      }
      if (obj.elements[i].name.substr(0,7)=="qntmul_"){
        var objvalor=new Number(obj.elements[i].value);
        valormul+=obj.elements[i].name+"_"+obj.elements[i].value;
      }
      if (obj.elements[i].name.substr(0,8)=="codunid_"){
        var objvalor=new Number(obj.elements[i].value);
        coduni+=obj.elements[i].name+"_"+obj.elements[i].value;
      }

      if (obj.elements[i].name.substr(0,6)=="valor_"){
        objvaloritem=new Number(obj.elements[i].value);
        valoritem+=obj.elements[i].name+"_"+obj.elements[i].value;
        
        totitem = obj.elements[i].value;
        if(totitem.search(',') != '-1'){
          totitem=totitem.replace('.','');	
          totitem=totitem.replace(',','.');	
        }

				valorpai=obj.elements[i].value;
        vltotal += new Number(totitem);

      }
      
      if(obj.elements[i].name.substr(0,4)=="val_"){
        objvaloritem=new Number(obj.elements[i].value);
        valoritem+=obj.elements[i].name+"_"+obj.elements[i].value;

				if (valorpai > 0) {
					erro=2;
					break;
				}
        
        totitem = obj.elements[i].value;
        if(totitem.search(',') != '-1'){
          totitem=totitem.replace('.','');	
          totitem=totitem.replace(',','.');	
        }
				
        vltotal += new Number(totitem);
				
      }

    }
    
    document.form1.val.value = valoritem;
    document.form1.valores.value = valor;
    document.form1.valmul.value = valormul;
    document.form1.codunidade.value = coduni;

    //alert(document.form1.valores.value);

    vlrnota = new Number(document.form1.valor_nota.value);
		
		if (erro == 2) {
      alert("Lan�amentos parciais inconsistentes! Verifique!");
		} else if (vlrnota.toFixed(2) != vltotal.toFixed(2)) {
      alert("Valor total dos itens diferente do valor total da nota!");
      erro = 1;
    }
    
    if (erro >= 1){
      return false;
    } else {
      return true;
    }
		
  }
	
  //---------------------------------------------------------------------
  function js_atualizaquant(condicao,codordem ){
    if (condicao==false){
      js_OpenJanelaIframe('top.corpo','db_iframe_lanca','mat1_lancaitens.php?atualizaquant=atualizaquant&excluir=excluir','Pesquisa',false,'0','0','0','0');
      itens.document.form1.submit();
    }else if (condicao==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_lanca','mat1_lancaitens.php?atualizaquant=atualizaquant&excluir=excluir','Pesquisa',false,'0','0','0','0');
      obj= itens.document.form1;
      for (i=0;i<obj.elements.length;i++){
        if (obj.elements[i].name.substr(0,6)=="quant_"){
          obj.elements[i].value='0';
        }
        if (obj.elements[i].name.substr(0,6)=="valor_"){
          obj.elements[i].value='0';
        }
      } 
      itens.location.href='mat1_entraordcomitemiframe.php?m51_codordem='+codordem+'&zera=true';
    }
  }
<?}?>
//----------------------------------------------------------
function js_calcalancar(){
  obj= itens.document.form1;
  lancado=0;
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,3)=="val"){
      if(obj.elements[i].value.search(',') != '-1'){
        vlrinfo=obj.elements[i].value.replace('.','');	
        vlrinfo=vlrinfo.replace(',','.');	
      }else{
        vlrinfo=obj.elements[i].value;
      }
      vlrinfo=new Number(vlrinfo);
      lancado+=vlrinfo;
    }
  }     
  vlnota=document.form1.valor_nota.value;
  if(vlnota.search(',') != '-1'){
    vlnota=vlnota.replace('.','');	
    vlnota=vlnota.replace(',','.');	
  }
  vlnota=new Number(vlnota);
  //  if (lancado>vlnota){
    //    alert('Valor dos itens n�o pode ser maior que o Valor da Nota!!');
    //    js_atualizaquant(true,'<?=$m51_codordem?>' );
  //  }else{
    alancar=vlnota-lancado;
    document.form1.alancar.value=alancar.toFixed(2);
  //  }
}
</script>
</html>