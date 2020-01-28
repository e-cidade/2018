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
$cltabativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("q07_seq");
$clrotulo->label("q11_tipcalc");
$clrotulo->label("q81_descr");
$clrotulo->label("q88_inscr");
if(isset($seq) && $seq!="" && isset($op) && $op!=""){
  $q07_seq=$seq;
  $result19=$cltabativ->sql_record($cltabativ->sql_query($inscr,$seq,'q07_ativ,q07_perman,q03_descr,q07_inscr,z01_nome,q07_quant,q07_datain,q07_datafi'));
  db_fieldsmemory($result19,0);
  $result20=$cltabativtipcalc->sql_record($cltabativtipcalc->sql_query($inscr,$seq,'q81_descr,q11_tipcalc'));
  if($cltabativtipcalc->numrows>0){
    db_fieldsmemory($result20,0);
  }  
  $result21=$clativprinc->sql_record($clativprinc->sql_query($q07_inscr,"q88_seq"));
  if($clativprinc->numrows>0){
    db_fieldsmemory($result21,0);
    if($seq==$q88_seq){
        $princ='t';
    }else{
        $princ='f';
    }  
  }else{
    $princ='f';
  }
}
?>
<form name="form1" method="post" action="iss1_tabativ004.php">
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <td height="160" align="center" valign="top">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap title="<?=@$Tq07_seq?>">
       <?=$Lq07_seq?>
    </td>
    <td> 
<?
db_input('q07_seq',6,$Iq07_seq,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_inscr?>">
       <?=$Lq07_inscr?>
    </td>
    <td> 
<?
db_input('q07_inscr',6,$Iq07_inscr,true,'text',3);
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_ativ?>">
       <?
       db_ancora(@$Lq07_ativ,"js_pesquisaq07_ativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q07_ativ',6,$Iq07_ativ,true,'text',$db_opcao," onchange='js_pesquisaq07_ativ(false);'")
?>
       <?
db_input('q03_descr',40,$Iq03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Atividade principal">
      <b>Atividade principal:</b>
    </td>
    <td> 
<?
$xq = array("f"=>"NÃO","t"=>"SIM");
 db_select('princ',$xq,true,$db_opcao);
if(isset($pods) && $pods=="nops"){
 echo "<small><b>Não será possível alterar este campo.</b></small>";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_quant?>">
       <?=@$Lq07_quant?>
    </td>
    <td> 
<?
if(empty($q07_quant)){
  $q07_quant=1;
}  
db_input('q07_quant',6,$Iq07_quant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_perman?>">
       <?=@$Lq07_perman?>
    </td>
    <td> 
<?
$xe = array("t"=>"PERMANENTE","f"=>"PROVISÓRIO");
db_select('q07_perman',$xe,true,$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_datain?>">
       <?=@$Lq07_datain?>
    </td>
    <td> 
<?
if(empty($q07_datain_dia)){
  $q07_datain_dia = date("d",db_getsession("DB_datausu"));
  $q07_datain_mes = date("m",db_getsession("DB_datausu"));
  $q07_datain_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('q07_datain',@$q07_datain_dia,@$q07_datain_mes,@$q07_datain_ano,true,'text',$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq07_datafi?>">
       <?=@$Lq07_datafi?>
    </td>
    <td> 
<?
db_inputdata('q07_datafi',@$q07_datafi_dia,@$q07_datafi_mes,@$q07_datafi_ano,true,'text',$db_opcao,"","","E6E4F1")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq11_tipcalc?>">
       <?
       db_ancora(@$Lq11_tipcalc,"js_tipcalc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
  db_input('q11_tipcalc',6,$Iq07_inscr,true,'text',$db_opcao,'onchange="js_tipcalc(false);"');
?>
       <?
db_input('q81_descr',40,$Iz01_nome,true,'text',3,"","","#E6E4F1");
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
   </td>
 </tr>
 <tr>
   <td valign="top">
     <center>
     <fieldset><Legend align="center"><b>ATIVIDADES CADASTRADAS</b></Legend>
     <?
      if(isset($seq) && $seq!="" && isset($op) && $op!=""){
     ?>	
        <iframe id="ativ"  class="bordasi"  frameborder="0" name="ativ"   leftmargin="0" topmargin="0" src="iss1_tabativ014.php?seqno=<?=$seq?><?=(isset($q07_inscr)?"&q07_inscr=$q07_inscr":"")?>" height="200" width="740">
    <?}else{?>           
        <iframe id="ativ"  class="bordasi"  frameborder="0" name="ativ"   leftmargin="0" topmargin="0" src="iss1_tabativ014.php?a=1<?=(isset($q07_inscr)?"&q07_inscr=$q07_inscr":"")?><?=(isset($db_opcaoal)?"&db_opcaoal=3":"")?>" height="200" width="740">
    <?}?>           
       </iframe> 
     </fieldset>
     </center>
   </td>
 </tr>
 <table>
</form>
<script>
function js_xx(){
  alert("Não pods");  
  return false;
}
<?
if(isset($q07_inscr)){
  $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr,"q88_inscr"));
  if($clativprinc->numrows==0){
  echo "
        function js_re(){
          document.getElementById('princ').options[1].selected=true; 	
	}
	js_re();
      ";
  }
}  
?>
<?
 if(isset($pods) && $pods=="nops"){
    echo "document.form1.princ.disabled=true;\n\n";
 }
  
if(isset($q07_inscr) && $q07_inscr!=""){
?>
function js_cancelar(){
  location.href='iss1_tabativ004.php?q07_inscr=<?=$q07_inscr?>&z01_nome=<?=$z01_nome?>';
}
<?
}
?>
function js_tipcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_tipcalcalt.php?funcao_js=parent.js_mostratip1|0|1','Pesquisa',true,0);
  }else{
   js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_tipcalcalt.php?pesquisa_chave='+document.form1.q11_tipcalc.value+'&funcao_js=parent.js_mostratip','Pesquisa',false,0);
  }
}
function js_mostratip(chave,erro){
  document.form1.q81_descr.value = chave; 
  if(erro==true){ 
    document.form1.q11_tipcalc.focus(); 
    document.form1.q11_tipcalc.value = ''; 
  }
}
function js_mostratip1(chave1,chave2){
  document.form1.q11_tipcalc.value = chave1;
  document.form1.q81_descr.value = chave2;
  db_iframe_ativid.hide();
}
function js_pesquisaq07_ativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_ativid.php?funcao_js=parent.js_mostraativid1|q03_ativ|q03_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_atividades','db_iframe_ativid','func_ativid.php?pesquisa_chave='+document.form1.q07_ativ.value+'&funcao_js=parent.js_mostraativid','Pesquisa',false);
  }
}
function js_mostraativid(chave,erro){
  document.form1.q03_descr.value = chave; 
  if(erro==true){ 
    document.form1.q07_ativ.focus(); 
    document.form1.q07_ativ.value = ''; 
  }
}
function js_mostraativid1(chave1,chave2){
  document.form1.q07_ativ.value = chave1;
  document.form1.q03_descr.value = chave2;
  db_iframe_ativid.hide();
}
</script>