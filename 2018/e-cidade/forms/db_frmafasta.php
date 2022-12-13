<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: pessoal
$clafasta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
?>
<style>

  #r45_situac{
    width: 401px;
  }
  #r45_codafadescr{
    width: 355px;
  }
  #r45_obs{
    width: 403px;
  }
  

</style>

<form name="form1" method="post" action="">
<center>

<fieldset style="margin-top: 20px; width: 40%;">
<legend><b>Manutenção de Afastamentos</b></legend>

<table border="0">
  <tr>
    <td nowrap title="Ano / Mês de competência">
    <input name="oid" type="hidden" value="<?=@$oid?>">
      <b>Ano / Mês:</b>
    </td>
    <td colspan="3" nowrap>
      <?
      if(!isset($r45_anousu)){
        $r45_anousu = db_anofolha();
      }
      db_input('r45_anousu',4,$Ir45_anousu,true,'text',3,"");
      ?>
      <b>/</b>
      <?
      if(!isset($r45_mesusu)){
        $r45_mesusu = db_mesfolha();
      }
      db_input('r45_mesusu',2,$Ir45_mesusu,true,'text',3,"");
      db_input('r45_codigo',4,$Ir45_codigo,true,'hidden',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr45_regist?>">
      <?
      db_ancora($Lr45_regist,"js_pesquisar45_regist(true);",($db_opcao==1?1:3));
      ?>
    </td>
    <td colspan="3" nowrap>
      <?
      db_input('r45_regist',6,$Ir45_regist,true,'text',($db_opcao==1?1:3),"onChange='js_pesquisar45_regist(false);'");
      db_input('z01_nome',48,$Ir45_regist,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr45_situac?>">
      <?=@$Lr45_situac?>
    </td>
    <td colspan="3" nowrap>
      <?
      $db_situac = Array(
                         "2" => "2 - Afastado sem remuneração",
                         "3" => "3 - Afastado acidente de trabalho +15 dias",
                         "4" => "4 - Afastado serviço militar",
                         "5" => "5 - Afastado licença gestante",
                         "6" => "6 - Afastado doença +15 dias",
                         "7" => "7 - Licença sem vencimento, cessão sem ônus",
                         "8" => "8 - Afastado doença +30 dias",
                        );
      db_select('r45_situac', $db_situac, true, ($db_opcao==1?1:3));
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr45_codafa?>">
      <?=@$Lr45_codafa?>
    </td>
    <td colspan="3" nowrap>
      <?
      $result_codfa = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_codigo,r66_descr","r66_descr","r66_anousu = ".db_anofolha()." and r66_mesusu = ".db_mesfolha()." and r66_tipo = 'A'"));
      if(!isset($r45_codafa) || (isset($r45_codafa) && trim($r45_codafa) == "")){
      	db_fieldsmemory($result_codfa, 0);
      	$r45_codafa = $r66_codigo;
      }
      db_selectrecord("r45_codafa", $result_codfa, true, ($db_opcao==1?1:3), "", "", "", "", "js_abrelista();");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr45_codret?>">
      <?=@$Lr45_codret?>
    </td>
    <td colspan="3" nowrap>
      <?
      $arr_codre = Array();
      $result_codre = $clmovcasadassefip->sql_record($clmovcasadassefip->sql_query(db_anofolha(),db_mesfolha(),$r45_codafa,null,"r67_reto"));
      if($clmovcasadassefip->numrows == 0){
        $result_codre = $clmovcasadassefip->sql_record($clmovcasadassefip->sql_query(db_anofolha(),db_mesfolha(),null,null,"r67_reto"));
      }
      for($i=0; $i<$clmovcasadassefip->numrows; $i++){
        db_fieldsmemory($result_codre, $i);
        $arr_codre[$r67_reto] = $r67_reto;
      }
      db_select("r45_codret", $arr_codre, true, ($db_opcao==1?1:3), "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr45_dtafas?>">
      <?=@$Lr45_dtafas?>
    </td>
    <td nowrap width="40%"> 
      <?
      if(!isset($r45_dtafas_dia) || (isset($r45_dtafas_dia) && trim($r45_dtafas_dia) == "")){
        $r45_dtafas_dia = date("d",db_getsession("DB_datausu"));
      }
      if(!isset($r45_dtafas_mes) || (isset($r45_dtafas_mes) && trim($r45_dtafas_mes) == "")){
        $r45_dtafas_mes = date("m",db_getsession("DB_datausu"));
      }
      if(!isset($r45_dtafas_ano) || (isset($r45_dtafas_ano) && trim($r45_dtafas_ano) == "")){
        $r45_dtafas_ano = date("Y",db_getsession("DB_datausu"));;
      }
      db_inputdata('r45_dtafas',@$r45_dtafas_dia,@$r45_dtafas_mes,@$r45_dtafas_ano,true,'text',($db_opcao==1?1:3),"onchange='js_somardias(3);'","","","parent.js_somardias(3);")
      ?>
    </td>
    <td nowrap title="Dias de afastamento" align="right">
      <b>Dias:</b>
    </td>
    <td nowrap>
      <?
      db_input('dias',3,0,true,'text',$db_opcao,"onchange='js_somardias(1);'");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr45_dtreto?>">
      <?=@$Lr45_dtreto?>
    </td>
    <td colspan="3" nowrap>
      <?
      db_inputdata('r45_dtreto',@$r45_dtreto_dia,@$r45_dtreto_mes,@$r45_dtreto_ano,true,'text',$db_opcao,"onchange='js_somardias(2);'","","","parent.js_somardias(2);")
      ?>
    </td>
  <tr>
     <td nowrap title="<?=@$Tr45_obs?>">
        <?=@$Lr45_obs?>
     </td>
     <td colspan="3"> 
      <?
        db_textarea('r45_obs',10,50,$Ir45_obs,true,'text',$db_opcao,"");
      ?>
     </td>    
  </tr>
</table>
</fieldset>
</center>
<div style="margin-top: 10px;">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_testacampos();">
  <?php if ( $db_opcao != 1 ) : ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?php endif; ?>
  <input type="button"    name="anteriores" id='anteriores' style="width:100px;" disabled="disabled" value="Anteriores"      onclick="js_PesquisaAfastamento('Afastamentos');" >
</div>
</form>
<script>
var time;

function js_afastamentosAnteriores(iMatricula){

  var sUrlRPC           = "pes1_afasta.RPC.php";
  var oParametros       = new Object();
  var msgDiv            = "Gerando arquivo CSV \n Aguarde ...";
  var iAno              = $F("r45_anousu");
  var iMes              = $F("r45_mesusu");  
  
  oParametros.exec       = 'possuiAnteriores';
  oParametros.iMatricula = iMatricula;
  oParametros.iAno       = iAno;
  oParametros.iMes       = iMes;  
  
  
  //js_divCarregando(msgDiv,'msgBox');
  
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoAfastamentosAnteriores
                                             }); 

}

function js_retornoAfastamentosAnteriores(oAjax){

    //js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
       $("anteriores").disabled = false;    
    } else {  
       $("anteriores").disabled = true;
    }
}



function js_PesquisaAfastamento(solicitacao) {

  var iMatricula = $F("r45_regist");
  var iAno       = $F("r45_anousu");
  var iMes       = $F("r45_mesusu");
  
  if(iMatricula == null || iMatricula == ''){
  
    alert('Selecione uma Matrícula');
    return false;
  }
  
  js_OpenJanelaIframe('top.corpo','func_pesquisa','pes3_conspessoal002_detalhes.php?solicitacao='+solicitacao+'&parametro='+iMatricula+'&ano='+iAno+'&mes='+iMes+'','CONSULTA DE FUNCIONÁRIOS',true,'20');
}


function js_seleciona_campo_diar(){
  document.form1.r45_dtreto_dia.focus();
  clearInterval(time);
}
function js_abrelista(){
  js_OpenJanelaIframe("top.corpo","db_iframe_listaretorno","func_retornoafasta.php?pesquisa_chave="+document.form1.r45_codafa.value,"Pesquisa",false,"20");
}
function js_listarretorno(str_retorno){
  x = document.form1.r45_codret;
  arr_retorno = new Array();
  arr_retorno = str_retorno.split(",");
  if(arr_retorno.length > 0){
    for(i=0; i<x.length; i++){
      x.options[i] = null;
    }
    for(i=0; i<arr_retorno.length; i++){
      x.options[i] = new Option(arr_retorno[i],arr_retorno[i]);
    }
  }
}
function js_testacampos(){

  mensagem = false;
  <?
  if($db_opcao == 1){
    echo "mensagem = true;";
  }
  ?>

  if(document.form1.r45_regist.value == ""){
    alert("Informe a matrícula do funcionário.");
    document.form1.r45_regist.focus();
  }else if(document.form1.r45_dtafas_ano.value == "" || document.form1.r45_dtafas_mes.value == "" || document.form1.r45_dtafas_dia.value == ""){
    alert("Informe a data de afastamento.");
    document.form1.r45_dtafas_dia.select();
    document.form1.r45_dtafas_dia.focus();
  }else{
    data_teste = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),<?=db_dias_mes(db_anofolha(),db_mesfolha())?>);
    data_afast = new Date(document.form1.r45_dtafas_ano.value,(document.form1.r45_dtafas_mes.value - 1),document.form1.r45_dtafas_dia.value);

    if(data_afast > data_teste && mensagem == true){
      alert("Data de afastamento deve ser inferior \nao último dia do mês corrente da folha.");
      document.form1.r45_dtafas_dia.select();
      document.form1.r45_dtafas_dia.focus();
    }else{
      return true;
    }
  }
  return false;
}
function js_somardias(opcao){
  // 1 - dias
  // 2 - data retorno
  // 3 - data afastam
  if(document.form1.r45_dtafas_ano.value != "" && document.form1.r45_dtafas_mes.value != "" && document.form1.r45_dtafas_dia.value != ""){
    if(opcao == 1){

      dias = new Number(document.form1.dias.value);	      
      if(dias > 0){
        dias+= new Number(document.form1.r45_dtafas_dia.value);
        mess = new Number(document.form1.r45_dtafas_mes.value);
        mess-= 1;

        datafim = new Date(document.form1.r45_dtafas_ano.value,mess,(dias - 1));

        dia = datafim.getDate();
        if(dia < 10){
          dia = "0" + dia;
        }

        mes = datafim.getMonth() + 1;
        if(mes < 10){
          mes = "0" + mes;
        }

        ano = datafim.getFullYear();

        document.form1.r45_dtreto_dia.value = dia;
        document.form1.r45_dtreto_mes.value = mes;
        document.form1.r45_dtreto_ano.value = ano;
        document.form1.r45_dtreto.value = dia+'/'+mes+'/'+ano;
      }else{
        alert("Informe a quantidade de dias de afastamento.");
        document.form1.dias.select();
        document.form1.dias.focus();
      }
    }else if(opcao == 2){
      if(document.form1.r45_dtreto_ano.value != "" && document.form1.r45_dtreto_mes.value != "" && document.form1.r45_dtreto_dia.value != ""){
        diaa = document.form1.r45_dtafas_dia.value;
        mesa = new Number(document.form1.r45_dtafas_mes.value);
        mesa-= 1;
        anoa = document.form1.r45_dtafas_ano.value;

        diar = document.form1.r45_dtreto_dia.value;
        mesr = new Number(document.form1.r45_dtreto_mes.value);
        mesr-= 1;
        anor = document.form1.r45_dtreto_ano.value;

        dataa = new Date(anoa,mesa,diaa);
        datar = new Date(anor,mesr,diar);
        if(datar >= dataa){
          diassoma = datar - dataa;
          diassoma = diassoma / 86400000;
          diassoma = new Number(diassoma);
          diassoma+= 1;
          diassoma = diassoma.toFixed(0);
          document.form1.dias.value = diassoma;
        }else{
          alert("Data de retorno deve ser superior à data de afastamento.");
          document.form1.r45_dtreto_dia.value = "";
          document.form1.r45_dtreto_mes.value = "";
          document.form1.r45_dtreto_ano.value = "";
          document.form1.r45_dtreto.value = "";
          document.form1.dias.value = ""
          time = setInterval(js_seleciona_campo_diar,10);
        }
      }else{
        document.form1.dias.value = ""
      }
    }else if(opcao == 3){
      if(document.form1.dias.value != ""){
        js_somardias(1);
      }else{
        js_somardias(2);
      }
    }
  }
}
function js_pesquisar45_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe("top.corpo","db_iframe_rhpessoal","func_rhpessoalrescis.php?testarescisao=raf&funcao_js=parent.js_mostraregist1|rh01_regist|z01_nome|r30_perai|r30_per1i|rh05_recis","Pesquisa",true,"20");
  }else{
     if(document.form1.r45_regist.value != ""){ 
       js_OpenJanelaIframe("top.corpo","db_iframe_rhpessoal","func_rhpessoalrescis.php?testarescisao=raf&pesquisa_chave="+document.form1.r45_regist.value+"&funcao_js=parent.js_mostraregist","Pesquisa",false,"20");
     }else{
       document.form1.z01_nome.value = ""; 
     }
  }
}
function js_mostraregist(chave,chave2,chave3,chave4,erro){
  document.form1.z01_nome.value = chave; 
  js_afastamentosAnteriores(document.form1.r45_regist.value);
  if(erro==true){ 
    document.form1.r45_regist.focus(); 
    document.form1.r45_regist.value = ""; 
  }else{
//    js_compara_datas(chave3);
  }
}
function js_mostraregist1(chave1,chave2,chave3,chave4,chave5){
  document.form1.r45_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
  js_afastamentosAnteriores(chave1);
//  js_compara_datas(chave4);
}
function js_compara_datas(data){
  data1 = "<?=(db_anofolha()."-".db_mesfolha()."-01")?>";
  data2 = "<?=date("Y-m-d",mktime(0,0,0,db_mesfolha(),61,db_anofolha()))?>";

  per  = new Date(data.substring(0,4),(data.substring(5,7) - 1),data.substring(8,10));
  per1 = new Date(data1.substring(0,4),(data1.substring(5,7) - 1),data1.substring(8,10));
  per2 = new Date(data2.substring(0,4),(data2.substring(5,7) - 1),data2.substring(8,10));

  if(per > per1 && per < per2){
    alert("AVISO: Funcionário com férias cadastradas.");
  }
}
function js_pesquisa(){
  qry = "";
  <?
  if($db_opcao == 2 || $db_opcao == 22){
  	echo "qry = 'retorno=true&';";
  }
  ?>
  js_OpenJanelaIframe('top.corpo','db_iframe_afasta','func_afasta.php?testarescisao=raf&'+qry+'funcao_js=parent.js_preenchepesquisa|r45_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_afasta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>