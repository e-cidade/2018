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

//MODULO: selecao
$clcadferia->rotulo->label();
$clselecao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");

$result_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),"r11_pagarferias as pontofer,r11_13ferias as pagafer13"));
if($clcfpess->numrows > 0){
  db_fieldsmemory($result_cfpess, 0);
}
?>

<form name="form1" method="post" action="pes4_cadferia004.php">
<center>
<table border="0">
  <tr>
    <td align="right" nowrap title="<?=@$Tr44_selec?>">
      <?
      db_ancora(@$Lr44_selec, "js_pesquisar44_selec(true);", $db_opcao);
      ?>
    </td>
    <td colspan="3"> 
      <?
      db_input('r44_selec', 8, $Ir44_selec, true, 'text', $db_opcao, " onchange='js_pesquisar44_selec(false);'")
      ?>
      <?
      db_input('r44_descr', 60, $Ir44_descr, true, 'text', 3);
      ?>
    </td>
  </tr>
   <tr>
            <td style="text-align: right;">
               <?=$Lr30_tipoapuracaomedia?>
            </td>
            <td>
                
               <? 
               $aTipos = array(1 => "Período Aquisitivo Normal",
                               2 => "Período Específico"
                              );
                db_select('r30_tipoapuracaomedia', $aTipos, true, 1, "onchange='js_showCamposMedia()'")?>
            </td>
          </tr>
    <tr>
    
  <tr>
    <td nowrap title="Período a gozar" align="right">
      <?
      db_ancora("<b>Período a gozar:</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      $perini = "";
      $perini_dia = "";
      $perini_mes = "";
      $perini_ano = "";
      db_inputdata('perini', @$perini_dia, @$perini_mes, @$perini_ano, true, 'text', 1, "onchange='js_verificadataini();'","","","parent.js_verificadataini();");
      ?>
      &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
      <?
      $perfim = "";
      $perfim_dia = "";
      $perfim_mes = "";
      $perfim_ano = "";
      db_inputdata('perfim', @$perfim_dia, @$perfim_mes, @$perfim_ano, true, 'text', 1, "onchange='js_verificadatafim();'","","","parent.js_verificadatafim();");
      ?>
    </td>
    <!--
    <td nowrap title="Dias a gozar" align="right">
      <?
      db_ancora("<b>Dias a gozar:</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      if(!isset($saldofer) || (isset($saldofer) && trim($saldofer) == "")){
        $saldofer = 30;
      }
      db_input('saldofer', 7, $Ir30_ndias, true, 'text', 1, "onchange='js_verificadataini()'");
      ?>
    </td>
    -->
  </tr>
  <tr id='linhadatasespecificas' style='display: none'>
      <td align="right">
        <b>
          <b>Período Específico:</b>
        </b>
      </td>
      <td colspan="3">
        <?
        db_inputdata('r30_periodolivreinicial', 
                     @$r30_periodolivreinicial_dia, 
                     @$r30_periodolivreinicial_mes, 
                     @$r30_periodolivreinicial_ano, 
                     true, 'text', 1, 
                     "onchange='js_calcFim();'",
                     "", "", "js_calcFim();"
                     );
        ?>
        &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
        <?
        db_inputdata('r30_periodolivrefinal',
                     @$r30_periodolivrefinal_dia, 
                     @$r30_periodolivrefinal_mes, 
                     @$r30_periodolivrefinal_ano, 
                     true, 'text', 1,
                     "onchange='js_calcIni();'",
                     "", "", "js_calcIni();"
                    );
        ?>
    </tr>
  <tr>
    <td nowrap title="Forma de gozo" align="right">
      <?
      db_ancora("<b>Forma de gozo</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      $arr_fpagto = Array(
                          "01"=>"01 - 30 dias ferias",
                          "02"=>"02 - 20 dias ferias",
                          "03"=>"03 - 15 dias ferias",
                          "04"=>"04 - 10 dias ferias",
                          "05"=>"05 - 20 dias ferias + 10 dias abono",
                          "06"=>"06 - 15 dias ferias + 15 dias abono",
                          "07"=>"07 - 10 dias ferias + 20 dias abono",
                          "08"=>"08 - 30 dias abono",
                          "12"=>"12 - Dias Livre"
                         );
      db_select("tipofer", $arr_fpagto, true, 1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Pagar férias" align="right">
      <?
      db_ancora("<b>Pagar férias: </b>", "", 3);
      ?>
    </td>
    <td>
      <?
      if(!isset($pontofer)){
        $pontofer = "S";
      }
      $arr_SorC = Array("S"=>"Salário","C"=>"Complementar");
      db_select("pontofer", $arr_SorC, true, 1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Pagar somente 1/3 férias" align="right">
      <?
      db_ancora("<b>Pagar somente 1/3 férias:</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      if(!isset($pagafer13)){
        $pagafer13 = "f";
      }
      $arr_SorN = Array("t"=>"Sim","f"=>"Não");
      db_select("pagafer13", $arr_SorN, true, 1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Digite o Ano / Mês de competência" align="right">
      <?
      db_ancora("<b>Ano / Mês pagamento:</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      $preanopagto = db_anofolha();
      $premespagto = db_mesfolha();
      db_input("DBtxt23", 4, $IDBtxt23, true, "text", 1,"","preanopagto");
      ?>
      &nbsp;/&nbsp;
      <?
      db_input("DBtxt25", 2, $IDBtxt25, true, "text", 1,"","premespagto");
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <b>Trazer Férias já Processadas no Lote :</b>
    </td>
    <td>
     <?
      $filtraferiasprocessadas = 2;
      
      $aFiltroFerias  = Array("1" => "Sim",
                              "2" => "Não"
                             );
      db_select("filtraferiasprocessadas", $aFiltroFerias, true, 1);
     ?>
    </td>
  </tr>
  
  
  <tr>
    <td align="right">
      <b>Períodos Aquisitivos :</b>
    </td>
    <td>
     <?
      //$filtraferiasprocessadas = 2;
      
      $aFiltroPeriodo  = Array("3" => "Todos",
                               "1" => "Vencidos Até",
                               "2" => "Não Vencidos"
                              );
      db_select("periodoaquisitivo", $aFiltroPeriodo, true, 1, 'onchange=show_dtvencidos(this.value);');
     ?>
    </td>
  </tr> 
  
  <tr id='tr_vencidos' style="display: none;">
    <td align="right">
      <b>Período Vencidos até :</b>
    </td>
    <td>
     <?
      //$filtraferiasprocessadas = 2;
      
        db_inputdata('periodosvencidosate', '', '', '', true, 'text', 1);
     ?>
    </td>
  </tr>    
  
  
          <tr>
            <td nowrap title="Observações" align="right">
              <b>Observações:</b>
            </td>
            <td> 
              <?
                db_textarea("r30_obs",5, 45,  "", true,null, 1)
              ?>
            </td>
          </tr>   
  
  
</table>
</center>
<input name="enviar_selecao" value="Enviar" type="submit" <?=($db_botao==false?"disabled":"")?> 
      onblur="document.form1.r44_selec.focus();" onclick="return js_verificacampos();" style="margin-top: 5px;">
</form>
<script>

function show_dtvencidos(sValor){

  if (sValor == 1 || sValor == '1') {
      $('tr_vencidos').style.display = 'table-row';
  } else {
      $('tr_vencidos').style.display = 'none';
      $('periodosvencidosate').value = '';
  }

}

function js_verificacampos(){
  if(document.form1.r44_selec.value == ""){
    alert("Seleção não informada. Verifique!");
    document.form1.r44_selec.focus();
    return false;
  }

  if(document.form1.perini_dia.value == "" || document.form1.perini_mes.value == "" || document.form1.perini_ano.value == ""){
    alert("Periodo de gozo inicial não informado. Verifique!");
    document.form1.perini_dia.focus();
    return false;
  }

  if(document.form1.perfim_dia.value == "" || document.form1.perfim_mes.value == "" || document.form1.perfim_ano.value == ""){
    alert("Periodo de gozo final não informado. Verifique!");
    document.form1.perfim_dia.focus();
    return false;
  }

  return true;
}
function js_pesquisar44_selec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostraselecao1|r44_selec|r44_descr','Pesquisa',true);
  }else{
    if(document.form1.r44_selec.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostraselecao','Pesquisa',false);
    }else{
      document.form1.r44_descr.value = '';
    }
  }
}

function js_mostraselecao(chave,erro){
  document.form1.r44_descr.value = chave;
  if(erro == true){
    document.form1.r44_selec.focus(); 
    document.form1.r44_selec.value = '';
  }
}

function js_mostraselecao1(chave1,chave2){
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value = chave2;
  db_iframe_selecao.hide();
}

function js_verificadataini(){
  x = document.form1;

  evaldiai = x.perini_dia;
  evalmesi = x.perini_mes;
  evalanoi = x.perini_ano;

  evaldiaf = x.perfim_dia;
  evalmesf = x.perfim_mes;
  evalanof = x.perfim_ano;

  if(evaldiai.value!= "" && evalmesi.value != "" && evalanoi.value != ""){
    saldofer = new Number(30);
    somadias = 0;
    if(saldofer > 0){
      somadias = new Number(evaldiai.value);
      somadias+= new Number(saldofer);
      somadias-= new Number(1);
    }

    qualmess = new Number(evalmesi.value);
    qualmess-= new Number(1);


    per2i = new Date(evalanoi.value,qualmess,evaldiai.value,1,0,0);
    per2f = new Date(evalanoi.value,qualmess,somadias,1,0,0);
    diaci = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),1);
    diacf = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),(<?=db_dias_mes(db_anofolha(),db_mesfolha())?> + 180));

    if(per2i >= diaci && per2f <= diacf){
      if(per2i > per2f){
        per2f = per2i;
      }
      evaldiaf.value = per2f.getDate()<10?"0"+per2f.getDate():per2f.getDate();
      evalmesf.value = (per2f.getMonth() + 1)<10?"0"+(per2f.getMonth() + 1):(per2f.getMonth() + 1);
      evalanof.value = per2f.getFullYear();
    }else{
      alert("A data para gozo deve ficar entre o primeiro dia do mês de competência\n e até 180 dias após o fim do período de competência");
      evaldiaf.value = '';
      evalmesf.value = '';
      evalanof.value = '';
      evaldiai.value = '';
      evalmesi.value = '';
      evalanoi.value = '';

      evaldiai.focus();
    }
  }else{
    evaldiaf.value = '';
    evalmesf.value = '';
    evalanof.value = '';
  }
}

function js_verificadatafim(){
  x = document.form1;

  evaldiai = x.perini_dia;
  evalmesi = x.perini_mes;
  evalanoi = x.perini_ano;

  evaldiaf = x.perfim_dia;
  evalmesf = x.perfim_mes;
  evalanof = x.perfim_ano;

  if(evaldiai.value != "" && evalmesi.value != "" && evalanoi.value != ""){
    if(evaldiaf.value != "" && evalmesf.value != "" && evalanof.value != ""){
      qualmesi = new Number(evalmesi.value);
      qualmesi-= new Number(1);

      qualmesf = new Number(evalmesf.value);
      qualmesf-= new Number(1);

      per2i = new Date(evalanoi.value,qualmesi,evaldiai.value);
      per2f = new Date(evalanof.value,qualmesf,evaldiaf.value);

      qualmess = new Number(<?=db_mesfolha()?>);
      qualmess-= new Number(1);

      qualdias = new Number(<?=db_dias_mes(db_anofolha(),db_mesfolha())?>);
      qualdias+= new Number(180);

      diaci = new Date(<?=db_anofolha()?>,qualmess,1);
      diacf = new Date(<?=db_anofolha()?>,qualmess,qualdias);

      if(per2f > diacf){
        alert("A data para gozo deve ficar entre o primeiro dia do mês de competência\n e até 180 dias após o fim do período de competência");
        evaldiaf.value = '';
        evalmesf.value = '';
        evalanof.value = '';

        evaldiaf.focus();
      }else if(per2i > per2f){
        alert("A data final para gozo deve ser inferior à data inicial.");
        evaldiaf.value = '';
        evalmesf.value = '';
        evalanof.value = '';

        evaldiaf.focus();
      }
    }
  }else{
    alert("Informe o período para gozo inicial.");
    evaldiaf.value = '';
    evalmesf.value = '';
    evalanof.value = '';
    evaldiai.focus();
  }
}
function js_showCamposMedia() {
   
   switch ($F('r30_tipoapuracaomedia')) {
   
     case '1':
       
       $('linhadatasespecificas').style.display = 'none';
       $('r30_periodolivrefinal').value         = '';
       $('r30_periodolivreinicial').value       = '';
       break;
     
     case '2':
     
       $('linhadatasespecificas').style.display='';
       break;
   }
    
}

function js_calcIni(){
  
  var  x = document.form1;
  
  var datainicial = eval("x.r30_periodolivreinicial");

  evaldiaf = eval("x.r30_periodolivrefinal_dia");
  evalmesf = eval("x.r30_periodolivrefinal_mes");
  evalanof = eval("x.r30_periodolivrefinal_ano");
  
  evaldiai = eval("x.r30_periodolivrefinal_dia");
  evalmesi = eval("x.r30_periodolivrefinal_mes");
  evalanoi = eval("x.r30_periodolivrefinal_ano");
  
  evaldatacompletaf = eval("x.r30_periodolivrefinal");
  
   if(evaldiaf.value!= "" && evalmesf.value != "" && evalanof.value != ""){
    
      nsaldo = new Number(365);
    
      subtraidias  = new Number(evaldiaf.value);
      subtraidias -= new Number(nsaldo);
      subtraidias += new Number(1);
    
    
      //se o ano  anterior for bissesto diminui mais um dia para subtraidias para fechar calculo
      if (checkleapyear(evalanof.value - 1)){
      subtraidias -= new Number(1);
        if ( evalmesf.value > 02 ){
          subtraidias += new Number(1);
        }
      }
      
      //se ano atual bissesto e mes maior que 02 diminui um dia para fechar calculo
      if (checkleapyear(evalanof.value) ){
           if ( evalmesf.value > 02 ){
          subtraidias -= new Number(1);
          }
        }
     
      qualmess  = new Number(evalmesf.value);
      qualmess -= new Number(1);
    
      dataini = new Date(evalanof.value, qualmess, subtraidias);
      
      evaldiai.value = dataini.getDate()<10?"0"+dataini.getDate():dataini.getDate();
      evalmesi.value = (dataini.getMonth() + 1)<10?"0"+(dataini.getMonth() + 1):(dataini.getMonth() + 1);
      evalanoi.value = dataini.getFullYear();
    

      if (evaldiai.value != '') {  
        datainicial.value = evaldiai.value+'/'+evalmesi.value+'/'+evalanoi.value; 
      }
  
      $('r30_periodolivreinicial').value = datainicial.value; 
      
    }    
  
}

//calcula a data final
function js_calcFim(){
 
 
 var x = document.form1;
 
 var datafinal = eval("x.r30_periodolivrefinal");
  
  evaldiai = eval("x.r30_periodolivreinicial_dia");
  evalmesi = eval("x.r30_periodolivreinicial_mes");
  evalanoi = eval("x.r30_periodolivreinicial_ano");
  
  evaldiaf = eval("x.r30_periodolivreinicial_dia");
  evalmesf = eval("x.r30_periodolivreinicial_mes");
  evalanof = eval("x.r30_periodolivreinicial_ano");
  
  evaldatacompletai = eval("x.r30_periodolivreinicial");
  
   if(evaldiai.value!= "" && evalmesi.value != "" && evalanoi.value != ""){
    
    //retorna true ou false se o ano é bissesto a para total de dias 
      nsaldo = new Number(364);//364 para fechar o calculo de ferias
   
      somadias = new Number(evaldiai.value);
      somadias += new Number(nsaldo);
      
      var anoAtual = evalanoi;
      var anoNext = new Number(evalanoi.value);

      
      //se ano atual for bissesto diminui  mais um dia para fechar o calculo de ferias
      if (checkleapyear(anoAtual.value)  ) { 
        somadias += new Number(1);
        //se data for maior que 29/02 em ano bissesto diminui mais um dia para fechar calculo
        if( evalmesi.value > 02 ){
          somadias -= new Number(1);
        }
      }
      
      //calcula proximo ano
      anoNext += new Number(1);
   
      //se ano posterior for bissesto e mes mair que 02 soma  mais um dia para fechar o calculo de ferias
      if(checkleapyear(anoNext) && (evalmesi.value > 2 ) ) { 
        somadias += new Number(1);
      }
    
      qualmess = new Number(evalmesi.value);
      qualmess -= new Number(1);
    
      datafim = new Date(evalanoi.value,qualmess,somadias,1,0,0);
    
      evaldiaf.value = datafim.getDate()<10?"0"+datafim.getDate():datafim.getDate();
      evalmesf.value = (datafim.getMonth() + 1)<10?"0"+(datafim.getMonth() + 1):(datafim.getMonth() + 1);
      evalanof.value = datafim.getFullYear();    

      if (evaldiaf.value != '') {
        datafinal.value = evaldiaf.value+'/'+evalmesf.value+'/'+evalanof.value; 
      }
      
      $('r30_periodolivrefinal').value = datafinal.value;
      
    }    
}

js_showCamposMedia();



</script>