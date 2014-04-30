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
$clrotulo->label("e82_codord");
$clrotulo->label("e87_descgera");

$dados="ordem";


?>
<script>
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";
  
  if((evt.charCode >46 && evt.charCode <58) || evt.charCode ==0){//8:backspace|46:delete|190:.
    return true;
  }else{
    return false;
  }  
}

function js_atualizar(){
  obj = ordem.document.form1;
  var coluna='';
  var sep=''; 
  var tcoluna='';
  var tsep='';
  var asep='';
  var agens = '';

  for(i=0; i<obj.length; i++){    
    nome = obj[i].name.substr(0,5);
    if(nome=="CHECK" && obj[i].checked==true){
      ord     = obj[i].name.substring(6);
      numemp  = obj[i+1].value;
      tipo    = obj[i+2].value;
      conta   = obj[i+4].value;
      forma   = obj[i+6].value;
      valor   = obj[i+8].value;
      
      coluna += sep+obj[i+9].value+"-"+obj[i].value+"-"+numemp+"-"+valor+"-"+tipo+"-"+forma+"-"+conta;
      sep= "XX";
    }
    if(nome=="CHECK"){
      if(agens.search(obj[i+1].value)==-1){
        agens+= asep+obj[i+1].value ;
        asep  = ",";
	if(tsep!=""){
	  tsep = ",";
	}
      }
      tcoluna+= tsep+obj[i].value;
      tsep    = "-";
    }  
  }
  document.form1.tords.value = tcoluna;
  document.form1.ords.value = coluna;
  document.form1.agens.value = agens;
  if(coluna==''){
    alert("Selecione algum movimento para confirmar.");
    return false;
  }
}

function js_label(liga,uak1,uak2){
  if(liga){
    document.getElementById('uak1').innerHTML=uak1;
    document.getElementById('uak2').innerHTML=uak2;
    document.getElementById('divlabel').style.visibility='visible';
  }else{
    document.getElementById('divlabel').style.visibility='hidden';
  }  
}

function js_labelconta(liga,uak1,uak2,uak3){
  if(liga){
    document.getElementById('uak3').innerHTML=uak1;
    document.getElementById('uak4').innerHTML=uak2;
    document.getElementById('uak5').innerHTML=uak3;
    document.getElementById('divlabelconta').style.visibility='visible';
  }else{
    document.getElementById('divlabelconta').style.visibility='hidden';
  }  
}
function js_mostravalores(){
  obj = ordem.document.form1;
  coluna = "";
  sep = "";
  for(i=0;i<obj.length;i++){
    nome = obj[i].name.substr(0,5);
    if(nome=="CHECK" && obj[i].checked==true){
      ord = obj[i].name.substring(6);
      valor   = obj[i+8].value;
      tipo    = obj[i+2].value;
      
      if(tipo!=0 && valor!=""){
        coluna += sep+tipo+'-'+valor;
        sep= ",";
      }
    }
  }
  if(coluna!=""){
    js_OpenJanelaIframe('top.corpo','db_iframe_mostratotal','func_mostratotal.php?coluna='+coluna,'Pesquisa',true,'20','390','400','300');
  }else{
    alert("Selecione algum movimento.");
  }
}
</script>
<BR><BR>
  <div align="left" id="divlabel" style="position:absolute; z-index:1; top:400; left:420; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
      Pago:    <span id="uak1"></span><br> 
      Anulado: <span id="uak2"></span><br> 
  </div>
  <div align="left" id="divlabelconta" style="position:absolute; z-index:1; top:400; left:420; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
      Banco:   <span id="uak3"></span><br> 
      Agência: <span id="uak4"></span><br> 
      Conta Padrão:   <span id="uak5"></span><br> 
  </div>

<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Filtros</b></legend>
<table border="0" align="left" >
  <tr>
     <td nowrap title="<?=@$Te82_codord?>" align='right'>
       <? db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
     </td>
     <td nowrap> 
       <? db_input('e82_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'")  ?>
       <? db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",$db_opcao);  ?>
     </td>
     <td nowrap>
       <? db_input('e82_codord2',8,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord02(false);'","e82_codord02")?>
     </td>
  </tr>   
  <tr>
     <td align="right" nowrap title="<?=$Te60_numemp?>">
      <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
      </td>
     <td nowrap> 
      <input name="e60_codemp" id='e60_codemp' title='<?=$Te60_codemp?>' size="10" type='text'  onKeyPress="return js_mascara(event);" >
    <b>Recursos:</b></td>
     <td >

     <?
       if(!isset($recursos)){
         $recursos = "proprios";
       }
       $ar = array("proprios"=>"Vinculados","todos"=>"Todos");
       db_select("recursos",$ar,true,1);
     ?>
     </td>    
  </tr>
  <tr>
    <td style='text-align:right'>
      <b>Data Inicial</b>
    </td>
    <td nowrap>
      <?
       db_inputdata("dataordeminicial",null,null,null,true,"text", 1);
      ?>
      <b>Data Final:</b>
      </td>
      <td nowrap>
      <?
       db_inputdata("dataordemfinal",null,null,null,true,"text", 1);
      ?>
    </td>
    <td align="right">
      <b>Banco:</b>
    </td>
    <td>
      <?

        $oDaoCadBan = db_utils::getDao('cadban');
        $rsBancos   = $oDaoCadBan->sql_record($oDaoCadBan->sql_query(null,"distinct k15_codbco,z01_nome","k15_codbco"));
        $aBancos[]  = "Todos"; 
        $aBancosCol = db_utils::getColectionByRecord($rsBancos); 
        foreach ($aBancosCol as $oBanco) {
          $aBancos[$oBanco->k15_codbco] = $oBanco->z01_nome;
        }
        
        db_select("k15_codbco",$aBancos,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numcgm?>" align='right'>
    <?
	db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
	?>        
	</td>
	<td  colspan='4'> 
	<?
	 db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
	 db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
	?>
  </td>
  </tr>
   <?=db_input('ords',100,'',true,'hidden',1);?>
   <?=db_input('tords',40,'',true,'hidden',1);?>
   <?=db_input('dados',10,'',true,'hidden',1);?>
   <?=db_input('agens',40,'',true,'hidden',1);?>
  <tr>
    <td align='right' nowrap><b>Conta pagadora padrão:</b></td>
    <td colspan=3>

     <?
      $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_codtipo as codtipo,e83_descr","e83_descr"));
      $numrows05 = $clempagetipo->numrows;
      $arr['0']="Nenhum";
      for($r=0; $r<$numrows05; $r++){
	db_fieldsmemory($result05,$r);
	$arr[$codtipo] = $e83_descr;
      }
      $e83_codtipo ='0';
      db_select("e83_codtipo",$arr,true,1,"onchange='ordem.js_padrao(this.value)';");
     ?>
     </td>
    
    <td align='left'>
      <input name="pesquisar" type="button"  value="Pesquisar" onclick='return js_pesquisarOrdens();'>
      <input name="atualizar" type="submit"  value="Atualizar" onclick='return js_atualizar();'>
      <input name="total" type="button" value="Ver totais" onclick='js_mostravalores();'>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  <table width="100%">
  <tr>
    <td>
     <fieldset><legend><b>Ordens</legend>
     <table width="100%">
    <tr>
    <td  align='center'>
     <iframe name="ordem" src='emp4_empageforma001_ordem.php?recursos=<?=($recursos)?>' width="100%" height="500" marginwidth="0" marginheight="0" frameborder="0"></iframe>
    </td>
  </tr>
  <tr>
    <td colspan='5' align='left'>
      <span style="color:red;">**</span><b>Conta conferida</b><br>
      <b>Total de registros: </b><span style='color:blue;font-weight:bold' id='totalregistros'></span>
    </td>
  </tr>
  </table>
  </fieldset> 
  </td>
  </tr>
  </table>
  </form>
</center>
<script>
function js_reload(){
  document.form1.submit();
}
//-----------------------------------------------------------
//---ordem 01
function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione uma ordem menor que a segunda!");
      document.form1.e82_codord.focus(); 
      document.form1.e82_codord.value = ''; 
    }
  }
}
function js_mostrapagordem1(chave1){
  document.form1.e82_codord.value = chave1;
  db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae82_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione uma ordem maior que a primeira");
      document.form1.e82_codord02.focus(); 
      document.form1.e82_codord02.value = ''; 
    }
  }
}
function js_mostrapagordem102(chave1,chave2){
  document.form1.e82_codord02.value = chave1;
  db_iframe_pagordem.hide();
}
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho.hide();
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
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
  func_nome.hide();
}

function js_pesquisarOrdens() {

  
  var iOrdemIni     = $F('e82_codord');
  var iOrdemFim     = $F('e82_codord02');
  var iCodEmp       = $F('e60_codemp');
  var dtDataIni     = $F('dataordeminicial');
  var dtDataFim     = $F('dataordemfinal');
  var iNumCgm       = $F('z01_numcgm');
  var iCodBanco     = $F('k15_codbco');
  var sRecursos     = $F('recursos');
  var sQueryString  = "lOk=true&iOrdemIni="+iOrdemIni+"&iOrdemFim="+iOrdemFim+"&iCodEmp="+iCodEmp;
  sQueryString     += "&dtDataIni="+dtDataIni+"&dtDataFim="+dtDataFim+"&iNumCgm="+iNumCgm;
  sQueryString     += "&iCodBanco="+iCodBanco+"&sRecursos="+sRecursos;
  ordem.location.href = "emp4_empageforma001_ordem.php?"+sQueryString;
   
}
</script>