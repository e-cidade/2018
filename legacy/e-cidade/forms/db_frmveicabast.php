<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: veiculos
$clveicabast->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve01_placa");
$clrotulo->label("ve26_descr");
$clrotulo->label("nome");
$clrotulo->label("ve71_veiccadposto");
$clrotulo->label("ve71_nota");
$clrotulo->label("ve72_empnota");
$clrotulo->label("ve73_veicretirada");
$clrotulo->label("ve60_codigo");
$clrotulo->label("ve07_sigla");
$clrotulo->label("ve70_observacao");

$sHora = db_hora();
if (isset($ve70_hora)) {
	if ($ve70_hora != '') {
		$sHora =  $ve70_hora;
	}
}

$res_veicparam = $clveicparam->sql_record($clveicparam->sql_query_file(null,"ve50_postoproprio",null,"ve50_instit = ".db_getsession("DB_instit")));
if ($clveicparam->numrows > 0){
  db_fieldsmemory($res_veicparam,0);
} else {
  db_msgbox("Parâmetros não configurados.Verifique");
  $db_botao = false;
}
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");

?>
<?
  db_app::load("prototype.js, scripts.js, strings.js, prototype.maskedinput.js");
  db_app::load("estilos.css");
?>
<form name="form1" method="post" action="" onSubmit="return js_verifica('<?=$db_opcao?>'); return js_verificaDataAbastecimento();" >
<input type="hidden" value="" name="self">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Dados do Abastecimento</b>
        </legend>
          <table border="0">
            <tr>
              <td nowrap title="<?=@$Tve70_codigo?>">
                 <?=@$Lve70_codigo?>
              </td>
              <td> 
                <?
                db_input('ve70_codigo',10,$Ive70_codigo,true,'text',3,"");
                db_input('ve60_codigo',10,$Ive60_codigo,true,'hidden',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tve70_veiculos?>">
                 <?
                 db_ancora(@$Lve70_veiculos,"js_pesquisave70_veiculos(true);",$db_opcao);
                 ?>
              </td>
              <td> 
                <?
                db_input('ve70_veiculos',10,$Ive70_veiculos,true,'text',$db_opcao,
                         " onchange='js_pesquisave70_veiculos(false);'");
                db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
                 ?>
              </td>
            </tr>
            
            
            
              <tr>
                <td nowrap title="<?=@$Tve73_veicretirada?>">
                   <?
                   db_ancora(@$Lve73_veicretirada,"js_pesquisave73_veicretirada(true);",$db_opcao);
                   ?>
                </td>
                <td> 
                  <?
                  db_input('ve73_veicretirada',10,$Ive73_veicretirada,true,'text',$db_opcao," onchange='js_pesquisave73_veicretirada(false);'")
                  ?>
                </td>
              </tr>        
              <tr>
                <td nowrap title="Data da retirada">
                   <strong>Data da Retirada:</strong>
                </td>
                <td> 
                  <?
                    db_inputdata('ve60_datasaida', @$ve60_datasaida_dia, @$ve60_datasaida_mes, @$ve60_datasaida_ano, true, 'text', 3,
                      "onchange='js_pesquisa_medida();'", "", "", "none", "", "", "js_pesquisa_medida();")
                  ?>
                </td>
              </tr>
              
              <tr>
                <td nowrap title="Hora da Retirada">
                   <strong>Hora da Retirada:</strong>
                </td>
                <td> 
                  <?
                    db_input('ve60_horasaida', 10, null, true, 'text', 3, "onchange='js_verifica_hora(this.value,this.name)';onkeypress='return js_mask(event, \"0-9|:|0-9\"); '");
                  ?>
                </td>
              </tr>            
            
            
            <tr>
              <td nowrap title="<?=@$Tve70_veiculoscomb?>">
                 <?
                 db_ancora(@$Lve70_veiculoscomb,"js_pesquisave70_veiculoscomb(true);",$db_opcao);
                 ?>
              </td>
              <td> 
                <?
                db_input('ve70_veiculoscomb',10,$Ive70_veiculoscomb,true,'text',$db_opcao,
                         " onchange='js_pesquisave70_veiculoscomb(false);'");
                db_input('ve26_descr',40,$Ive26_descr,true,'text',3,'')
                 ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tve70_dtabast?>">
                 <?=@$Lve70_dtabast?>
              </td>
              <td> 
                <?
                  db_inputdata('ve70_dtabast',@$ve70_dtabast_dia,@$ve70_dtabast_mes,@$ve70_dtabast_ano,true,'text',$db_opcao,
                    "onchange='js_pesquisa_ultimamedida();'","","","none","","", "js_pesquisa_ultimamedida();")
                ?>
              </td>
            </tr>
            
            
            <tr>
              <td nowrap title="Hora do abastecimento">
                 <strong>Hora do abastecimento:</strong>
              </td>
              <td> 
                <?
                  db_input('ve70_hora', 10, $Ive70_hora, true, 'text', $db_opcao, "onchange='js_verifica_hora(this.value,this.name)';onkeypress='return js_mask(event, \"0-9|:|0-9\"); '");
                ?>
                
              </td>
            </tr>            
            
            
            
            
            <tr>
              <td nowrap title="<?=@$Tve70_litros?>">
                 <?=@$Lve70_litros?>
              </td>
              <td> 
          <?
          db_input('ve70_litros',10,$Ive70_litros,true,'text',$db_opcao,"")
          ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="Última Medida"><b>Última Medida:</b></td>
              <td>
                <?
                  $dData = substr(@$ve70_dtabast,6,4).'-'.substr(@$ve70_dtabast,3,2).'-'.substr(@$ve70_dtabast,0,2);
                  if (isset($ve70_veiculos)) {  
                    $Queryultimamedida = $clveiculos->sql_record( $clveiculos->sql_query_ultimamedida(@$ve70_veiculos, @$dData, $sHora));
                    if($clveiculos->numrows > 0){
                      db_fieldsmemory($Queryultimamedida,0); 
                    }
                  } else{
                    $ultimamedida = 0;	
                  }
                  db_input("ultimamedida",10,0,true,"text",3);
                  if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
                    echo " ".db_input("ve07_sigla", 10,0,true,"text",3);
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tve70_medida?>">
                <?=@$Lve70_medida?>
              </td>
              <td> 
                <?
                  db_input('ve70_medida',10,$Ive70_medida,true,'text',$db_opcao,"");
                  db_input("ve07_sigla", 10,0,true,"text",3);
                ?>
              </td>
            </tr>
            <tr id='tr_proximamedida' style="display:none">
              <td nowrap title="Próxima Medida"><b>Próxima Medida:</b></td>
              <td>
                <?
                	if (isset($ve70_veiculos)) {
	                  $Queryproximamedida = $clveiculos->sql_record($clveiculos->sql_query_proximamedida(@$ve70_veiculos,@$dData,$sHora));
	                  if($clveiculos->numrows > 0){
	                    db_fieldsmemory($Queryproximamedida,0);  
	                  } else {
	                    $proximamedida = 0;
	                  }
	                  db_input("proximamedida",15,0,true,"text",3);
	                  if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
	                   echo " ".db_input("ve07_sigla",3,0,true,"text",3);
	                  }
                  }
                ?>
              </td>
            </tr>

            <?  
              if (isset($ve50_postoproprio) && $ve50_postoproprio == 3){
                db_input('posto_proprio',1,"",true,'hidden',3,'');
            ?>    
            <tr>
              <td nowrap title="Tipo de Posto"><b>Tipo de Posto:</b></td>
              <td>
                  <select name="sel_proprio" onChange="js_mostravalores(this,<?=$db_opcao?>);" style="width: 100px;">
                    <option value="0" <? if (isset($sel_proprio) && $sel_proprio == 0){ echo "SELECTED"; } ?>>Nenhum</option>
                    <option value="1" <? if (isset($sel_proprio) && $sel_proprio == 1){ echo "SELECTED"; } ?>>Interno</option>
                    <option value="2" <? if (isset($sel_proprio) && $sel_proprio == 2){ echo "SELECTED"; } ?>>Externo</option>
                  </select>
              </td>    
           </tr>
        <?
        }
        
        $mostrar = true;
        if (isset($sel_proprio) && $sel_proprio == 0 || 
           !isset($sel_proprio) && isset($ve50_postoproprio) && $ve50_postoproprio == 3){
          $mostrar = false;
        }
      
        if ($db_opcao != 1) {
          $mostrar = true;
        }
      
        if ($mostrar == true){
      ?>
        <tr>
          <td nowrap title="<?=@$Tve71_veiccadposto?>">
             <?
             db_ancora(@$Lve71_veiccadposto,"js_pesquisave71_veiccadposto(true);",$db_opcao);
             ?>
          </td>
          <td> 
      <?
      db_input('ve71_veiccadposto',10,$Ive71_veiccadposto,true,'text',$db_opcao," onchange='js_pesquisave71_veiccadposto(false);'")
      ?>
             <?
      db_input('posto',40,"",true,'text',3,'');
             ?>
          </td>
        </tr>
      <?
        }
      
        if (isset($ve50_postoproprio) && $ve50_postoproprio == 0 || 
            isset($ve70_valor)        && $ve70_valor > 0         ||
            isset($sel_proprio)       && $sel_proprio == 2){
          if (isset($sel_proprio) && $sel_proprio == 2 || !isset($sel_proprio)){
      ?>
        <tr>
          <td nowrap title="<?=@$Tve70_valor?>">
             <?=@$Lve70_valor?>
          </td>
          <td> 
      <?
      db_input('ve70_valor',10,$Ive70_valor,true,'text',$db_opcao,"")
      ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve70_vlrun?>">
             <?=@$Lve70_vlrun?>
          </td>
          <td> 
      <?
      db_input('ve70_vlrun',10,$Ive70_vlrun,true,'text',$db_opcao,"")
      ?>
          </td>
        </tr>
        <tr id="nota">
          <td nowrap title="<?=@$Tve71_nota?>">
             <?=@$Lve71_nota?>
          </td>
          <td> 
      		<?
      		db_input('ve71_nota',10,$Ive71_nota,true,'text',$db_opcao,"onBlur='js_ve71_nota(false);'");
      		?>
          </td>
        </tr>  
        <tr id="empnota">
          <td   nowrap title="<?=$Tve72_empnota?>">
          <?db_ancora(@$Lve72_empnota,"js_pesquisa_empnota(true);",$db_opcao);?></td>
          <td >
          <? 
          
          db_input("e69_codnota",10,$Ive72_empnota,true,"text",$db_opcao,"onchange='js_pesquisa_empnota(false);'");
          db_input("empnota",10,$Ive72_empnota,true,"text",3,"onchange='js_pesquisa_empnota(false);'");
          ?></td>
        </tr>
        <?
          }
        }
      ?>
      <tr>
        <td nowrap title = "Observação" colspan = "2">
          <fieldset>
            <legend><strong>Observação</strong></legend>
            <?php db_textarea('ve70_observacao', 4, 69, $Ive70_observacao, true, 'text', $db_opcao); ?>
          </fieldset>
        </td>
       </tr>
     </table>
   </fieldset>
  </td>
 </tr>
 <tr>
   <td colspan="2" style="text-align: center;">
     <input onclick='return js_verificaDataAbastecimento();' name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
            type="submit" id="db_opcao" 
            value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
            <?=($db_botao==false?"disabled":"")?>  >
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  </td>
 </tr>  
</table>     
  </center>
</form>
<script>


function js_verificaDataAbastecimento(){

  var dtRetirada      = $F("ve60_datasaida");
  var dtAbastecimento = $F("ve70_dtabast");
  
  var sHoraRetirada      = $F("ve60_horasaida");
  var sHoraAbastecimento = $F("ve70_hora");

  if (dtAbastecimento == "" || sHoraAbastecimento == "") {

    alert("Informe Data / Hora do abastecimento");
    return false;
  }

  if(js_comparadata(dtRetirada, dtAbastecimento, ">")){

    alert("Data de retirada maior que data de abastecimento.");
    return false;
    
  } 
  
  if (js_comparadata(dtRetirada, dtAbastecimento, "==")) {

    var nHoraRetirada  = (sHoraRetirada.replace(":", ""));
    var nHoraAbastecimento = (sHoraAbastecimento.replace(":", ""));
    
    if (nHoraRetirada > nHoraAbastecimento) {
     
      alert("Hora da retirada é maior que a hora de abastecimento.");
      return false;
    }
  }
 
  switch($F("sel_proprio")) {


    case 0 :
      
      alert("Informe um Tipo de Posto.");
      return false
    break;

    //Posto Interno  
    case 1: 

      //Sem posto informado
      if ($F("ve71_veiccadposto") == "") {
        
        alert("Informe o Posto do realizamento do reabastecimento.");
        return false;
      }  
      
    break;

    //Posto Externo
    case 2:

      //sem posto informado
      if ($F("ve71_veiccadposto") == ""){

        alert("Informe o Posto do realizamento do reabastecimento.");        
        return false;
        }
      
      //sem nota informada
      if ($F("e69_codnota") == "") {

        alert("Informe a nota.");
        return false;
        }
    break;
  }
  


  return true;
}


            
function js_ve71_nota(){
  var iNota = document.form1.ve71_nota.value;
      if (iNota!=""){
          document.getElementById("empnota").style.display="none";
          document.form1.empnota.value="";
      }else{
          document.getElementById("empnota").style.display="";
          document.form1.empnota.value="";
      }
}



function js_pesquisave73_veicretirada(mostra){

  var iCodVeiculo = document.form1.ve70_veiculos.value;

  if (iCodVeiculo == "" ) {

    alert("Selecione um veículo para selecionar a retirada.");
    return false;
  }
  

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veicretirada','func_veicretirada.php?funcao_js=parent.js_mostraveicretirada1|ve60_codigo|ve60_codigo|ve60_datasaida|ve60_horasaida&codigoveiculo='+iCodVeiculo,'Pesquisa',true);
  }else{
     if(document.form1.ve73_veicretirada.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veicretirada','func_veicretirada.php?pesquisa_chave='+document.form1.ve73_veicretirada.value+'&funcao_js=parent.js_mostraveicretirada&codigoveiculo='+iCodVeiculo,'Pesquisa',false);
     }else{
       document.form1.ve60_codigo.value = ''; 
     }
  }
}
function js_mostraveicretirada(chave,erro, dtRetirada, sHoraRetirada){
  
  document.form1.ve60_codigo.value = chave;  
  $("ve60_datasaida").value = js_formatar(dtRetirada,"d");
  $("ve60_horasaida").value = sHoraRetirada;

  if(erro==true){ 

    document.form1.ve73_veicretirada.focus(); 
    document.form1.ve73_veicretirada.value = ''; 
    $("ve60_datasaida").value = "";
    $("ve60_horasaida").value = "";
    alert("Retirada " + chave +" não encontrada.");
  }
}
function js_mostraveicretirada1(chave1,chave2, dtRetirada, sHoraRetirada){

  $("ve60_datasaida").value = js_formatar(dtRetirada,"d");
  $("ve60_horasaida").value = sHoraRetirada;
  
  document.form1.ve73_veicretirada.value = chave1;
  document.form1.ve60_codigo.value       = chave2;

  
  db_iframe_veicretirada.hide();
}



function js_pesquisave70_veiculos(mostra){

  $("ve73_veicretirada").value = "";
  $("ve60_datasaida").value    = ""; 
  $("ve60_horasaida").value    = ""; 
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculosalt.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_placa','Pesquisa',true);
  }else{
     if(document.form1.ve70_veiculos.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculosalt.php?pesquisa_chave='+document.form1.ve70_veiculos.value+'&funcao_js=parent.js_mostraveiculos','Pesquisa',false);
     }else{
       document.form1.ve01_placa.value = ''; 
     }
  }
}
function js_mostraveictipoabast(chave,erro){
  document.form1.ve07_sigla.value = chave; 
  if(erro==true){ 
    document.form1.ve07_sigla.value = ''; 
  }
}
function js_mostraveiculos(chave,erro){
  document.form1.ve01_placa.value = chave; 
  if(erro==true){ 
    document.form1.ve70_veiculos.focus(); 
    document.form1.ve70_veiculos.value = ''; 
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?sigla=true&pesquisa_chave='+document.form1.ve70_veiculos.value+'&funcao_js=parent.js_mostraveictipoabast','Pesquisa',false);
  }
}
function js_mostraveiculos1(chave1,chave2){
  document.form1.ve70_veiculos.value = chave1;
  document.form1.ve01_placa.value = chave2;
  js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?sigla=true&pesquisa_chave='+document.form1.ve70_veiculos.value+'&funcao_js=parent.js_mostraveictipoabast','Pesquisa',false);
  db_iframe_veiculos.hide();
}

function js_pesquisave70_veiculoscomb(mostra){

  
  if(mostra==true){
    if (document.form1.ve70_veiculos.value != ""){
      js_OpenJanelaIframe('top.corpo','db_iframe_veiculoscomb','func_veiculoscomb.php?filtrar_veiculo='+document.form1.ve70_veiculos.value+'&funcao_js=parent.js_mostraveiculoscomb1|ve06_sequencial|ve26_descr|ve06_veiccadcomb','Pesquisa',true);
    }
  }else{
     if(document.form1.ve70_veiculoscomb.value != '' && document.form1.ve70_veiculos.value != ""){
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculoscomb','func_veiculoscombabast.php?filtrar_veiculocomb='+document.form1.ve70_veiculoscomb.value+'&funcao_js=parent.js_mostraveiculoscomb&cod_veiculo='+document.form1.ve70_veiculos.value+'&pesquisa_chave='+document.form1.ve70_veiculoscomb.value,'Pesquisa',false);
     }else{
       document.form1.ve70_veiculoscomb.value = "";
       document.form1.ve26_descr.value        = ""; 
     }
  }
}
function js_mostraveiculoscomb(chave1,erro,chave2,chave3){
  
  if(erro==true){ 
    document.form1.ve70_veiculoscomb.focus(); 
    document.form1.ve70_veiculoscomb.value = ''; 
    document.form1.ve26_descr.value = chave1; 
  } else {
   
    document.form1.ve26_descr.value = chave2; 
  }
}
function js_mostraveiculoscomb1(chave1,chave2,chave3){

  document.form1.ve70_veiculoscomb.value = chave3;
  document.form1.ve26_descr.value = chave2;
  db_iframe_veiculoscomb.hide();
}
function js_pesquisave71_veiccadposto(mostra){
<?
  $param_tipo = $ve50_postoproprio;
  if (isset($sel_proprio) && $sel_proprio > 0){
    if ($sel_proprio == 2){
      $param_tipo = 0;            // Externo
    } else {
      $param_tipo = $sel_proprio; // Interno
    }
  }
?>
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadposto','func_veiccadpostoalt.php?param_tipo=<?=$param_tipo?>&funcao_js=parent.js_mostraposto1|ve29_codigo|z01_nome|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.ve71_veiccadposto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadposto','func_veiccadpostoalt.php?param_tipo=<?=$param_tipo?>&pesquisa_chave='+document.form1.ve71_veiccadposto.value+'&funcao_js=parent.js_mostraposto','Pesquisa',false);
     }else{
       document.form1.posto.value = ''; 
     }
  }
}
function js_mostraposto(chave,erro){
  document.form1.posto.value = chave; 
  if(erro==true){ 
    document.form1.ve71_veiccadposto.focus(); 
    document.form1.ve71_veiccadposto.value = ''; 
  }
}
function js_mostraposto1(chave1,chave2,chave3){
  document.form1.ve71_veiccadposto.value = chave1;
  if (chave2!=""){
		posto=chave2;
  }
	if (chave3!=""){
		posto=chave3;
  }
  document.form1.posto.value = posto;
  db_iframe_veiccadposto.hide();
}

function js_pesquisa_empnota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?funcao_js=parent.js_mostraempnota1|e69_codnota|e69_numero','Pesquisa',true);
  }else{

     var iEmpNota = document.form1.empnota.value;
      if (iEmpNota!=""){
          document.getElementById("nota").style.display="none";
          document.form1.ve71_nota.value="";
      }else{
          document.getElementById("nota").style.display="";
          document.form1.ve71_nota.value="";
      }

     if(document.form1.empnota.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?pesquisa_chave='+document.form1.empnota.value+'&funcao_js=parent.js_mostraempnota','Pesquisa',false);
     }
  }
}
function js_mostraempnota(chave,erro){
  if(erro==true){ 
    document.form1.empnota.value = ''; 
    document.form1.empnota.focus(); 
  }



}
function js_mostraempnota1(chave1,chave2){
   document.form1.e69_codnota.value = chave1; 
   document.form1.empnota.value = chave2; 
   db_iframe_empnota.hide();
 var iEmpNota = document.form1.empnota.value;
     if (iEmpNota!=""){
         document.getElementById("nota").style.display="none";
         document.form1.ve71_nota.value="";
     }else{
         document.getElementById("nota").style.display="";
         document.form1.ve71_nota.value="";
    }
                                                                                 

}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicabast','func_veicabast.php?funcao_js=parent.js_preenchepesquisa|dl_Cod_Abast','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veicabast.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_mostravalores(obj,modo){
  var index = obj.selectedIndex;

  if (document.form1.ve71_veiccadposto){
    document.form1.ve71_veiccadposto.value = "";
    document.form1.posto.value             = "";
  }

  obj.options[index].select = true;
  document.form1.posto_proprio.value = obj.options[index].value;

  if (obj.options[index].value == 0){
    alert("Deve-se optar por escolhar um Tipo de Posto"); 
    document.form1.ve71_veiccadposto.focus();
    document.form1.ve71_veiccadposto.select();
    exit;
  }

  if (modo != 1){
     obj2 = document.createElement('input');
     obj2.setAttribute('name','alterado');
     obj2.setAttribute('type','hidden');
     obj2.setAttribute('value','true');
     document.form1.appendChild(obj2);
  }

  document.form1.submit();
}

 var ve70_medida=new Number();
 ve70_medida = document.form1.ve70_medida.value;

function js_verifica(op){

  document.form1.self.value = 'true';
  if (op=='1'){
    if (document.form1.ve70_medida.value==0){
      resp=confirm('Você deseja realmente zerar a medida de consumo?');  
      if (resp==true){
        obj = document.createElement('input');
        obj.setAttribute('name','confirmamedida');
        obj.setAttribute('type','hidden');
        obj.setAttribute('value','true');
        document.form1.appendChild(obj);
		    document.form1.self.value = 'true';
        return true;
      }else{
        document.form1.ve70_medida.value=ve70_medida;
        return false;
      }
    }

  } else {
 	  if (document.form1.ve70_medida.value == 0) {
 		  alert("Não pode zerar a medida de consumo.");
 		  document.form1.ve70_medida.value = ve70_medida;
 		  return false;
 	  }
  }

  // Verifica a medida
  var medidasaida = new Number(document.form1.ve70_medida.value);
  var ultimasaida = new Number(document.form1.ultimamedida.value);
  var proxima     = new Number(document.form1.proximamedida.value);

  if(ultimasaida > medidasaida){
    alert ("Valor da medida menor que o valor da última medida");
    document.form1.ve70_medida.style.backgroundColor='#99A9AE';
    document.form1.ve70_medida.value='';  
    document.form1.ve70_medida.focus();
    return false;
  }  

  if(proxima > 0) {
    if(proxima < medidasaida){
      alert ("Valor da medida maior que o valor da proxima medida");
      document.form1.ve70_medida.style.backgroundColor='#99A9AE';
      document.form1.ve70_medida.value='';  	
      document.form1.ve70_medida.focus();	
      return false;   	
    }
  }

  return true;
}

function js_pesquisa_ultimamedida() {
	
  var databanco = document.form1.ve70_dtabast_ano.value + '-' + 
                  document.form1.ve70_dtabast_mes.value + '-' +
                  document.form1.ve70_dtabast_dia.value;
  var abastecimento = document.form1.ve70_codigo.value;
  js_OpenJanelaIframe('top.corpo', 'db_iframe_medida',
    'func_veiculos_medida.php?metodo=ultimamedida&veiculo='+document.form1.ve70_veiculos.value+
                                                '&data='+databanco+
                                                '&abastecimento='+abastecimento+
                                                '&funcao_js=parent.js_mostraultimamedida', 'Pesquisa', false);

  js_OpenJanelaIframe('top.corpo', 'db_iframe_proximamedida',
    'func_veiculos_medida.php?metodo=proximamedida&veiculo='+document.form1.ve70_veiculos.value+
                                                '&data='+databanco+
                                                '&abastecimento='+abastecimento+                                                
                                                '&funcao_js=parent.js_mostraproximamedida', 'Pesquisa Proxima Medida', false);
  return true;
}

function js_mostraultimamedida(ultimamedida,outro) {
  document.form1.ultimamedida.value = ultimamedida; 
  return true;
}

function js_mostraproximamedida(proximamedida,outro) {
  document.form1.proximamedida.value = proximamedida; 

  if(proximamedida != '0') {
    document.getElementById('tr_proximamedida').style.display = '';
  } else {
    document.getElementById('tr_proximamedida').style.display = 'none';
  }

  return true;
}

function js_verifica_hora(valor,campo) {
  erro= 0;
  ms  = "";
  hs  = "";
  
  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");
  if (pos!=-1) {
    if (pos==0 || pos>2) {
      erro++;
    } else {
      if (pos==1) {
        hs = "0"+valor.substr(0,1);
        ms = valor.substr(pos+1,2);
      } else if (pos==2) {
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if (ms=="") {
        ms = "00";
      }
    }
  } else {
    if (tam>=4) {
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    } else if (tam==3) {
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    } else if (tam==2) {
      hs = valor;
      ms = "00";
    } else if (tam==1) {
      hs = "0"+valor;
      ms = "00";
    }
  }
  if (ms!="" && hs!="") {
    if (hs>24 || hs<0 || ms>60 || ms<0) {
      erro++
    } else {
      if (ms==60) {
        ms = "59";
      }
      if (hs==24) {
        hs = "00";
      }
      hora = hs;
      minu = ms;
    }
  }
  
  if (erro>0) {
    alert("Informe uma hora válida.");
  }
  if (valor!="") {
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }
}
/**
 * Formata o campo da hora
 */
function js_formataHora() {

  new MaskedInput("#ve70_hora", "00:00", {placeholder:"0"});
 // new MaskedInput("#ve61_horadevol", "00:00", {placeholder:"0"});
}


$('ve70_hora').onblur = function() {

	js_pesquisa_ultimamedida();

	};


js_formataHora();
</script>