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
$cllab_coletaitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("la21_i_codigo");
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");
$clrotulo->label("la24_i_laboratorio"); 
$clrotulo->label("la09_i_exame");
$clrotulo->label("la21_d_data");
$clrotulo->label("la21_c_hora");
$clrotulo->label("la21_d_entrega");

function somardata($data, $dias= 0, $meses = 0, $ano = 0){
   $data = explode("/", $data);
   $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano) );
   return $novadata;
}


if(!isset($la32_d_data)){
	$la32_d_data=$la32_d_entrega=date("d/m/Y",db_getsession("DB_datausu"));
	$la32_d_data_dia=$la32_d_entrega_dia=date("d",db_getsession("DB_datausu"));
	$la32_d_data_mes=$la32_d_entrega_mes=date("m",db_getsession("DB_datausu"));
	$la32_d_data_ano=$la32_d_entrega_ano=date("Y",db_getsession("DB_datausu"));
	$la32_c_hora=$la32_c_horaentrega=date("H:i");
}
?>
<center>
<form name="form1" method="post" action="">
<center>
<fieldset style='width: 100%;'><legend><b>Exames</b><legend>
  <table border="0" style='width: 95%;'>
     <tr>
        <td nowrap title="<?=@$Tla22_i_codigo?>">
           <?db_ancora("<stronger><b>Requisição</b></stronger>","js_pesquisala22_i_codigo(true);",$db_opcao);?>
        </td>
        <td> 
           <?db_input('la22_i_codigo',10,$Ila22_i_codigo,true,'text',$db_opcao," onchange='js_pesquisala22_i_codigo(false);'")?>
           <?db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'')?>
        </td>
     </tr>
     <tr>
        <td colspan="2">
            <div id="GridExames" id="GridExames"></div>
            <select name="exames" style="display:none"></select>
        </td>
     </tr>
   </table>
</fieldset>
</center>
<center>
<table style="width: 100%;" border="0">
   <tr>
     <td>
         <fieldset style='width: 90%;'><legend><b>Receber</b><legend>
            <table style='width: 95%;'>
               <tr>
                 <td nowrap title="<?=@$Tla32_d_data?>">
                    <?=@$Lla32_d_data?>  
                 </td>
                 <td> 
                    <?db_inputdata('la32_d_data',@$la32_d_data_dia,@$la32_d_data_mes,@$la32_d_data_ano,true,'text',$db_opcao,"")?>
                    <?db_input('la32_i_codigo',10,$Ila32_i_codigo,true,'hidden',3,"")?>
                 </td>
               </tr>
               <tr>
                  <td nowrap title="<?=@$Tla32_c_hora?>">
                     <?=@$Lla32_c_hora?>
                  </td>
                  <td> 
                     <?db_input('la32_c_hora',5,$Ila32_c_hora,true,'text',$db_opcao,"")?>
                  </td>
               </tr>
               <tr>
                  <td colspan="2">
                     <input value="Falta material" name="falta" id="falta" type="submit" onclick="return js_valida(0)" disabled>
                  </td>
               </tr>
            </table>
       </fieldset>
    </td>
    <td>
       <fieldset style='width: 90%;'><legend><b>Entrega</b><legend>
          <table style='width: 95%;'>  
               <tr>
                   <td nowrap title="<?=@$Tla32_d_entrega?>">
                       <?=@$Lla32_d_entrega?>
                   </td>
                   <td> 
                       <?db_inputdata('la32_d_entrega',@$la32_d_entrega_dia,@$la32_d_entrega_mes,@$la32_d_entrega_ano,true,'text',$db_opcao,"")?>
                   </td>
               </tr>
               <tr>
                   <td nowrap title="<?=@$Tla32_c_horaentrega?>">
                       <?=@$Lla32_c_horaentrega?>
                   </td>
                   <td> 
                       <?db_input('la32_c_horaentrega',5,$Ila32_c_horaentrega,true,'text',$db_opcao,"")?>
                   </td>
               </tr>
               <tr>
                   <td nowrap title="<?=@$Tla32_i_avisapaciente?>" colspan="2">
                       <input type="checkbox" name="avisa" id="avisa" >
                       <?=substr(@$Lla32_i_avisapaciente,8,-10)?>
                       <?//db_input('la32_i_avisapaciente',10,$Ila32_i_avisapaciente,true,'text',$db_opcao,"")?>
                   </td>
               </tr>
          </table>
       </fieldset>
    </td>
    <td valign="top">
       <fieldset style='width: 90%;'><legend><b>Op&ccedil;oes</b><legend>
          <table style='width: 95%;'>
              <tr>
                  <td>
                      <select name="modelos" <?=$on_change?> style="width:450px;">
                          <option value="M1" <?=@$modeloselect=="M1"?"selected":""?>>Modelo 1 ( 3 x 10 - Código de Barras / Código Exame / Paciente )</option>
                          <option value="M2" <?=@$modeloselect=="M2"?"selected":""?>>Modelo 2 ( 4 x 11 - Código de Barras / Código Exame / Paciente )</option>       
                      </select>
                  </td>
              </tr>
              <tr>  
                  <td>      
                      <input type="button" name="imprimir" id="imprimir" value="Imprimir etiqueta" onclick="js_emite();" disabled>
                      <input type="submit" name="incluir" id="incluir" value="Salvar" onclick="return js_valida(1)" disabled>
                  </td>
              </tr> 
          </table>
       </fieldset>
    </td>
  </tr>
</table> 
</center>

</form>
</center>
<script>
sRPC = 'lab4_agendar.RPC.php';
objGridExames = new DBGrid(' GridExames ');
F = document.form1;
js_init();
<?if(isset($la22_i_codigo)){
	  echo"js_carregaexames($la22_i_codigo);";
  }
?>
//validaÃ§Ã£o
function js_valida(entrega){
	if((F.rad_exame.checked==false)){
        alert('Marque um exame!');
        return false;
	}
	if(F.la32_d_data.value==''){
        alert('Entre com a data da coleta!');
        return false;
	}
	if(F.la32_c_hora.value==''){
        alert('Entre com a hora da coleta!');
        return false;
	}
	if(entrega==1){
		if(F.la32_d_entrega.value==''){
	        alert('Entre com a data da entrega!');
	        return false;
		}
		if(F.la32_c_horaentrega.value==''){
	        alert('Entre com a data da entrega!');
	        return false;
		}
	}
	if(confirm("Tem certeza deseja Registrar a coleta?")){
	   return true;
	}else{
	   return false;
	}
	
}

function js_emite(){	
 lista = F.rad_exame.value;
 if((lista!='')&&(lista!=undefined)){
    if(document.form1.modelos.value=="M1"){
       window.open('lab4_etiquetacoleta002.php?lista='+lista,janela,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }else if(document.form1.modelos.value=="M2"){
       window.open('lab4_etiquetacoleta003.php?lista='+lista,janela,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }
 }else{
    alert('Seleciona um Exame');
 }
}

//grid exames
function js_init() {

     var arrHeader = new Array ( "M",
                                 " Cod. ",  
                                 "    <?=substr(@$Lla24_i_laboratorio,8,-10)?>   ", 
                                 "     <?=substr(@$Lla09_i_exame,8,-10)?>     ",
                                 " <?=substr(@$Lla21_d_data,8,-10)?>  ",
                                 " <?=substr(@$Lla21_c_hora,8,-10)?> ",
                                 " Situação ",
                                 " urgente");

    objGridExames.nameInstance = 'oGridExames';
    objGridExames.setHeader( arrHeader );
    objGridExames.setHeight(80);
    objGridExames.show($('GridExames')); 

}
function js_AtualizaGrid(){

    objGridExames.clearAll(true);
    tam=F.exames.length; 
    for(x=0;x<tam;x++){

       sText=F.exames.options[x].text;
       avet=sText.split('#');
       alinha= new Array();
       alinha[0]='<input type="radio" name="rad_exame" id="rad_exame" value="'+avet[7]+'" onclick="js_mudadata(\''+avet[8]+'\')" >';  
       alinha[1]=avet[0]; //codigo Setor/Exame
       alinha[2]=avet[1]; //descr  laboratorio
       alinha[3]=avet[2]; //descr  exame
       alinha[4]=avet[3]; //data coleta
       alinha[5]=avet[4]; //hora coleta
       alinha[6]=avet[5]; //data entrega
       scheck=(avet[6]==1)?' checked ':'';
       alinha[7]='<input type="checkbox" id="urgente'+x+'" '+scheck+' >';
       objGridExames.addRow(alinha);

    }
    objGridExames.renderRows();

  }
function js_mudadata(data){

	F.la32_d_entrega.value=data;
	
}
function js_carregaexames(requisicao){
	var oParam      = new Object();
    oParam.exec     = 'CarregaGridAutorizado';
    oParam.requisicao  = requisicao;
    oParam.iLaboratorioLogado  = <?=$iLaboratorioLogado?>;
    js_ajax( oParam, 'js_retornocarregaexames' );
}
function js_retornocarregaexames(objAjax){
	oAjax=eval("("+objAjax.responseText+")");
    while(F.exames.length>0){
    	F.exames.remove(0);
    }
	if(oAjax.status==1){
	       if(oAjax.alinhasgrid.length>0){
	            for(x=0;x<oAjax.alinhasgrid.length;x++){
	               F.exames.add(new Option(oAjax.alinhasgrid[x],F.exames.length),null);   
	            }
	            js_AtualizaGrid();
	            F.falta.disabled=false;
	            F.incluir.disabled=false;
	            F.imprimir.disabled=false;
	       }else{
	            objGridExames.clearAll(true);
	       }
	}
}

//lookup's

function js_pesquisala22_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&funcao_js=parent.js_mostralab_requisicao1|la22_i_codigo|z01_v_nome','Pesquisa',true);
  }else{
     if(document.form1.la22_i_codigo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&pesquisa_chave='+document.form1.la22_i_codigo.value+'&funcao_js=parent.js_mostralab_requisicao','Pesquisa',false);
     }else{
       document.form1.la21_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_requisicao(chave,erro){
  document.form1.z01_v_nome.value = chave; 
  if(erro==true){ 
    document.form1.la22_i_codigo.focus(); 
    document.form1.la22_i_codigo.value = ''; 
  }else{
     js_carregaexames(F.la22_i_codigo.value);
  }
}
function js_mostralab_requisicao1(chave1,chave2){
  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_lab_requisicao.hide();
  js_carregaexames(chave1);
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_coletaitem','func_lab_coletaitem.php?funcao_js=parent.js_preenchepesquisa|la32_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_coletaitem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_ajax( objParam,jsRetorno ){
	  var objAjax = new Ajax.Request(
	                         sRPC, 
	                         {
	                          method    : 'post', 
	                          parameters: 'json='+Object.toJSON(objParam),
	                          onComplete: function(objAjax){
	                                  var evlJS = jsRetorno+'( objAjax );';
	                                  eval( evlJS );
	                                }
	                         }
	                        );
	}
</script>