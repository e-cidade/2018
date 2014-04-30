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
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("k17_codigo");

if( (isset($e50_codord) && $e50_codord != "") && (isset($e50_codord02) && $e50_codord02 != "") ){
  $dbwhere = "e50_codord > $e50_codord and  e50_codord < $e50_codord02";
}else if( isset($e50_codord) && $e50_codord != ""){
  $dbwhere = "e50_codord = $e50_codord ";
}else if( isset($e50_codord02) && $e50_codord02 != "") {
  $dbwhere = "e50_codord < $e50_codord02 ";
}  

if(isset($dbwhere) && $dbwhere != ''){
  $result05  = $clempagetipo->sql_record($clempagetipo->sql_query_emprec(null,"e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo","e83_conta",""," $dbwhere "));
}else{
  $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo","e83_conta"));
}
$numrows05 = $clempagetipo->numrows;
$arr = array();
$arr['0']="Nenhum";
for($r=0; $r<$numrows05; $r++){
  db_fieldsmemory($result05,$r);
  $arr[$codtipo] = $e83_conta." - ".$e83_descr . " - " . str_pad($c61_codigo, 4, "0", STR_PAD_LEFT);
}


?>
<script>
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";
  
  if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
    return true;
  }else{
    return false;
  }  
}

//---------------------------------------------SLIP----------------------------------------------------------
function js_pesquisar_slip(){
  
  form = document.form1;
  
  form.dados.value = "slip";
  
  query = 'data=<?=($e80_data_ano."_".$e80_data_mes."_".$e80_data_ano)?>';
  query += "&e80_codage=<?=$e80_codage?>";
  query += "&slip1="+form.k17_codigo.value;
  if (form.k17_codigo02.value == "" && form.k17_codigo.value != "") {
    form.k17_codigo02.value = form.k17_codigo.value;
  }
  query += "&slip2="+form.k17_codigo02.value;
  query += "&ordens="+form.ordens.value;
  
  if(form.e83_codtipo.value!=0){
    query += "&e83_codtipo="+form.e83_codtipo.value;
  }
  
  ordem.location.href = "emp4_empage002_slip.php?"+query;
  js_limparcampos();
}


//-------------------------------------ORDEM--------------------------------------------------------------------------
function js_pesquisar(){
  
  form = document.form1;
  
  form.dados.value = "ordem";
  
  query = 'data=<?=($e80_data_ano."_".$e80_data_mes."_".$e80_data_ano)?>';
  query += "&e80_codage=<?=$e80_codage?>";
  
  if(form.e50_codord.value != ""){
    query += "&e50_codord="+form.e50_codord.value;
  }
  
  if(form.e50_codord02.value != ""){
    query += "&e50_codord02="+form.e50_codord02.value;
  }
  
  if(form.e60_codemp.value != ""){
    codemp = form.e60_codemp.value;
    arr = codemp.split('/');
    if(arr.length==2){
      query += "&e60_codemp="+arr[0]+"&e60_anousu="+arr[1];  
    }else{  
      query += "&e60_codemp="+form.e60_codemp.value;
    }  
  }
  if(form.e60_numemp.value != ""){
    query += "&e60_numemp="+form.e60_numemp.value;
  }
  if(form.z01_numcgm.value != ""){
    query += "&z01_numcgm="+form.z01_numcgm.value;
  }
  if( form.dtin_dia.value != "" && form.dtin_mes.value != "" && form.dtin_ano.value != ""  ){
    query +="&dtin="+ form.dtin_ano.value+"_"+form.dtin_mes.value+"_"+form.dtin_dia.value; 
  }
  if( form.dtfi_dia.value != "" && form.dtfi_mes.value != "" && form.dtfi_ano.value != ""  ){
    query +="&dtfi="+ form.dtfi_ano.value+"_"+form.dtfi_mes.value+"_"+form.dtfi_dia.value; 
  }
  if(form.e83_codtipo.value!=0){
    query += "&e83_codtipo="+form.e83_codtipo.value;
  }
  query += "&recursos="+form.recursos.value;
  
  //selecionadas,naum selecionadas ou todas
  query += "&ordens="+form.ordens.value;
  
  ordem.location.href = "emp4_empage002_ordem.php?"+query;
  js_limparcampos();
}
function js_atualizar(){
  if(ordem.document.form1){
    obj = ordem.document.form1;
    var coluna='';
    var sep=''; 
    
    var tcoluna='';
    var tsep=''; 
    
    for(i=0; i<obj.length; i++){
      nome = obj[i].name.substr(0,5);  
      
      if(nome=="CHECK" && obj[i].checked==true){
        ord = obj[i].name.substring(6);
        valor = eval("ordem.document.form1.valor_"+ord+".value");
        //alert(ord+' --- '+eval("ordem.document.form1.valor_"+ord+".value"));
        tipo  = eval("ordem.document.form1.e83_codtipo_"+ord+".value");
        //testa se eh por ordem
        if(document.form1.dados.value == "ordem"){
          numemp  = eval("ordem.document.getElementById('e60_numemp_"+ord+"').innerHTML");
        }else{
          numemp = '0';
        }   
        
        coluna += sep+obj[i].value+"-"+numemp+"-"+valor+"-"+tipo;
        sep= "XX";
      }
      if(nome=="CHECK"){
        tcoluna += tsep+obj[i].value;
        tsep= ",";
      }  
    } 	
    /*
    if(coluna==''){
      alert("Selecione um registro!");
      return false;
    }
    */
    document.form1.tords.value = tcoluna;
    document.form1.ords.value = coluna;
    return true;
  }else{
    alert("Clique em pesquisar para selecionar uma ordem!");
    return false;
  }	  
  //return coluna ;
}

//function que coloca os valores anulado e pagos
function js_label(liga,uak1,uak2){
  //  evt= (evt)?evt:(window.event)?window.event:""; 
  if(liga){
    document.getElementById('uak1').innerHTML=uak1;
    document.getElementById('uak2').innerHTML=uak2;
    //  document.getElementById('divlabel').style.left=evt.clientX;
    //  document.getElementById('divlabel').style.top=evt.clientY;
    document.getElementById('divlabel').style.visibility='visible';
  }else{
    document.getElementById('divlabel').style.visibility='hidden';
  }  
}
//-------------------
function js_labelconta(liga,uak1,uak2,uak3){
  //  evt= (evt)?evt:(window.event)?window.event:""; 
  //   this.teste = uak1;
  if(liga){
    document.getElementById('uak3').innerHTML=uak1;
    document.getElementById('uak4').innerHTML=uak2;
    document.getElementById('uak5').innerHTML=uak3;
    //  document.getElementById('divlabel').style.left=evt.clientX;
    //  document.getElementById('divlabel').style.top=evt.clientY;
    document.getElementById('divlabelconta').style.visibility='visible';
  }else{
    document.getElementById('divlabelconta').style.visibility='hidden';
  }  
}
</script>
<form name="form1" method="post" action="">
<?=db_input('ords',10,'',true,'hidden',1);?>
<?=db_input('tords',10,'',true,'hidden',1);?>
<?=db_input('dados',10,'',true,'hidden',1);?>

<? db_inputdata('dtp',@$dtp_dia,@$dtp_mes,@$dtp_ano,true,'hidden',3);//data que será padrao quando entrar para emitir os cheques?>
<center>
<div align="left" id="divlabel" style="position:absolute; z-index:1; top:400; left:420; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
Pago:    <span id="uak1"></span><br> 
Anulado: <span id="uak2"></span><br> 
</div>
<div align="left" id="divlabelconta" style="position:absolute; z-index:1; top:400; left:420; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
Banco:   <span id="uak3"></span><br> 
Agência: <span id="uak4"></span><br> 
Conta:   <span id="uak5"></span><br> 
</div>
<table border="0 " align="left" cellpadding='0' cellspacing='0'>
<tr>     
<td>
<table border='0'>  
<tr>
<td nowrap title="<?=@$Te80_codage?>" align='right'>
<?=$Le80_codage?>
</td>	
<td>	
<? db_input('e80_codage',8,$Ie80_codage,true,'text',3)?>
<?=$Le80_data?>
<?
db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',3);
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Te50_codord?>" align='right'>
<? db_ancora(@$Le50_codord,"js_pesquisae50_codord(true);",$db_opcao);  ?>
</td>
<td> 
<?
//db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'")  
db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao,"")  
?>
<? db_ancora("<b>até</b>","js_pesquisae50_codord02(true);",$db_opcao);  ?>
<?
//db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord02(false);'","e50_codord02")
db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao,"","e50_codord02")
?>

<input name="procura" type="submit"  value="Pesquisa tipos">


</td>
</tr>
<tr>
<td nowrap title="<?=@$Tk17_codigo?>" align='right'>
<? db_ancora(@$Lk17_codigo,"js_pesquisak17_codigo(true);",$db_opcao);  ?>
</td>
<td> 
<? db_input('k17_codigo',8,$Ik17_codigo,true,'text',$db_opcao," ")  ?>
<? db_ancora("<b>até</b>","js_pesquisak17_codigo02(true);",$db_opcao);  ?>
<? db_input('k17_codigo',8,$Ik17_codigo,true,'text',$db_opcao," onchange='js_pesquisak17_codigo02(false);'","k17_codigo02")?>
</td>
</tr>
<tr> 
<td  align="right" nowrap title="<?=$Te60_numemp?>">
<? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
</td>

<td  nowrap> 

<input name="e60_codemp" title='<?=$Te60_codemp?>' size="12" type='text'  onKeyPress="return js_mascara(event);" >
<? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",$db_opcao);  ?>
<? db_input('e60_numemp',12,$Ie60_numemp,true,'text',$db_opcao," onchange='js_pesquisae60_numemp(false);'")  ?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tz01_numcgm?>" align='right'>
<?
db_ancora(@$Lz01_nome,"js_pesquisaz01_numcgm(true);",$db_opcao);
?>        
</td>
<td> 
<?
db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'")
?>
<?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
?>
</td>
</tr>
</table>  
</td>  
<td align='left' valign='top'>
<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
<tr>
<td><b>Data inicial</b></td>
<td>
<?db_inputdata('dtin',@$dtin_dia,@$dtin_mes,@$dtin_ano,true,'text',1);?>
</td>  
</tr>  
<tr>
<td><b>Data final</b></td>
<td>
<?db_inputdata('dtfi',@$dtfi_dia,@$dtfi_mes,@$dtfi_ano,true,'text',1);?>
</td>
<td>
</tr>  
<tr>
<td><b>Recursos</b></td>
<td> 

<?
if(empty($atualizar)){
  $e83_codtipo ='0';
}	  
$recursos = "proprios";
$ar = array("proprios"=>"Próprios","todos"=>"Todos");
db_select("recursos",$ar,true,1);
?>
</td>
</tr>
<tr>
<td><b>Ordens</b></td>
<td > 
<?     
$xy = array("t"=>"Todas","s"=>"Selecionadas","n"=>"Não selecionadas");
db_select('ordens',$xy,true,1);
//db_select('ordens',$xy,true,1,"onchange='js_troca();'");
?>     
</td>
</tr>  

<tr>
<td><b><?=$RLe83_codtipo?> padrão</b></td>
<td> 

<?
if(empty($atualizar)){
  $e83_codtipo ='0';
}	  
db_select("e83_codtipo",$arr,true,1,"onchange='ordem.js_padrao(this.value)';");
?>
</td>
</tr>
</table>
<br>
</td>
</tr>
<tr>
<td colspan='2' align='center'>
<input name="atualizar" type="submit"  value="Atualizar" onclick='return js_atualizar();'>
&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;
<input name="pesquisar" type="button" value="Pesquisar Ordens" onclick='js_pesquisar();' >
<input name="pesquisa_slip" type="button" value="Pesquisar Slips" onclick='js_pesquisar_slip();' >
&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;
<input name="emite" type="button" value="Emitir cheque" onclick='js_emite();' >
<input name="emite" type="button" value="Emitir cheque Slip" onclick='js_emite_slip();' >
<input name="anular" type="button" value="Cancelar cheque" onclick='js_anular();'>
&nbsp;&nbsp;&nbsp;&nbsp;
<input name="outro" type="button" value="Outra/Nova agenda" onclick='location.href="emp4_empage001.php"'>
<!--<input name="limpa" type="reset"  value="Limpar campos">-->
<!--<input name="limpa" type="reset"  value="Limpar campos">-->
</td>
</tr>
<tr>
<td colspan='2' align='center'>
<iframe name="ordem"  width="950" height="400" marginwidth="0" marginheight="0" frameborder="0">
</iframe>
</td>
</tr>
<tr>
<td colspan='2' align='left'>
<span style="color:red;">**</span><b>Conta de outro credor</b>
</td>
</tr>

</table>
</center>
</form>
<script>
function js_limparcampos(){
  l = document.form1;
  l.e50_codord.value = "";
  l.e50_codord02.value = "";
  l.k17_codigo.value = "";
  l.k17_codigo02.value = "";
  l.e60_numemp.value = "";
  l.e60_codemp.value = "";
  l.z01_numcgm.value = "";
  l.z01_nome.value = "";
}
function js_emite_slip(){
  js_OpenJanelaIframe('','db_iframe_cheque','emp4_empageconfslip001.php?e80_codage=<?=$e80_codage?>','Pesquisa',true);
}
function js_emite(){
  
  js_OpenJanelaIframe('','db_iframe_cheque','emp4_empageconf001.php?e80_codage=<?=$e80_codage?>','Pesquisa',true);
}
function js_anular(){
  query = "?e80_codage=<?=$e80_codage?>";
  js_OpenJanelaIframe('','db_iframe_cheque','emp4_empageconfcanc001.php'+query,'Pesquisa',true);
}



function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
    // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho02.hide();
}


function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}

//------------------------------------------------------------
function js_mostraempempenho(chave,erro){
  if(erro==true){ 
    document.form1.e60_numemp.focus(); 
    document.form1.e60_numemp.value = ''; 
  }
}
function js_mostraempempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}

//-----------------------------------------------------------
//---slip 01
function js_pesquisak17_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
  }
}
function js_mostraslip1(chave1,chave2){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}
//-----------------------------------------------------------
//---slip 01
function js_pesquisak17_codigo02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip12|k17_codigo','Pesquisa',true);
  }
}
function js_mostraslip12(chave1,chave2){
  document.form1.k17_codigo02.value = chave1;
  db_iframe_slip.hide();
}

//-----------------------------------------------------------
//---ordem 01
function js_pesquisae50_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord&e80_codage=<?=@$e80_codage?>','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e50_codord.value);
    if(ord01 != ""){
      js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord01+'&funcao_js=parent.js_mostrapagordem&e80_codage=<?=@$e80_codage?>','Pesquisa',false);
    }else{
      document.form1.e50_codord.value='';
    }   
  }
}
function js_mostrapagordem(chave,erro){
  if(erro==true){ 
    document.form1.e50_codord.focus(); 
    document.form1.e50_codord.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e50_codord.value = chave1;
  db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae50_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord&e80_codage=<?=@$e80_codage?>','Pesquisa',true);
  }else{
    ord02 = new Number(document.form1.e50_codord02.value);
    if(ord02 != ""){
      js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord02+'&funcao_js=parent.js_mostrapagordem02&e80_codage=<?=@$e80_codage?>','Pesquisa',false);
    }else{
      document.form1.e50_codord02.value='';
    }   
  }
}
function js_mostrapagordem02(chave,erro){
  if(erro==true){ 
    document.form1.e50_codord02.focus(); 
    document.form1.e50_codord02.value = ''; 
  }
}
function js_mostrapagordem102(chave1,chave2){
  document.form1.e50_codord02.value = chave1;
  db_iframe_pagordem.hide();
}

//---------------------------------------------------
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.z01_numcgm.value != ''){ 
      js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = ''; 
    }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
//------------------------------------------------------------

</script>