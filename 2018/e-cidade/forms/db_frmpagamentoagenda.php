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
$clrotulo->label("e87_codgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");

$dbwhere = '';
/* [Extensão] Filtro da Despesa */


$sSqlContaTesouraria = $clempagetipo->sql_query_reduz(null,"e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo","e83_conta", $dbwhere);
$result05  = $clempagetipo->sql_record($sSqlContaTesouraria);
$numrows05 = $clempagetipo->numrows;


$arr = array();
$arr['0']="Nenhum";

for($r=0; $r<$numrows05; $r++){
  db_fieldsmemory($result05,$r);
  $arr[$codtipo] = $e83_conta." - ".$e83_descr . " - " . str_pad($c61_codigo, 4, "0", STR_PAD_LEFT);
}

$lUsaData    = false;
$aParametrosCaixa = db_stdClass::getParametro("caiparametro",array(db_getsession("DB_instit")));
if (count($aParametrosCaixa) > 0) {
  $lUsaData = $aParametrosCaixa[0]->k29_trazdatacheque =="t"?true:false;
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


  //-------------------------------------ORDEM--------------------------------------------------------------------------



  function js_pesquisar(tipo){
    //tipo = 1... pesquisa empennhos com cheques
    //tipo = 2... emite relatorios
    //tipo = 3... pesquisa empennhos sem cheques
    //tipo = 4... pesquisa slips com cheques
    //tipo = 5... pesquisa slips sem cheques
    //tipo = 6... pagamento eletronico
    form = document.form1;

    query = '1=1';

    if(tipo == 1 || tipo == 3 || tipo == 6 || tipo == 7){
      if(form.e50_codord.value != ""){
        query += "&e50_codord="+form.e50_codord.value;
      }

      if(form.e50_codord02.value != ""){
        query += "&e50_codord02="+form.e50_codord02.value;
      }

      if(form.e60_codempx.value != ""){
        codemp = form.e60_codempx.value;
        arr = codemp.split('/');
        if(arr.length==2){
          query += "&e60_codemp="+arr[0]+"&e60_emiss="+arr[1];
        }else{
          query += "&e60_codemp="+form.e60_codempx.value;
        }
      }

      // if(form.e60_numemp.value != ""){
      //   query += "&e60_numemp="+form.e60_numemp.value;
      // }

    }else if(tipo == 4 || tipo == 5){

      if(form.k17_codigo.value != ""){
        query += "&k17_codigo="+form.k17_codigo.value;
      }
      if(form.k17_codigo2.value != ""){
        query += "&k17_codigo2="+form.k17_codigo2.value;
      }
      if (form.dataslipini.value != '') {
        query += "&dtDataSlipInicial="+form.dataslipini.value;
      }
      if (form.dataslipfim.value != '') {
        query += "&dtDataSlipFinal="+form.dataslipfim.value;
      }
    }

    if(form.z01_numcgm.value != ""){
      query += "&z01_numcgm="+form.z01_numcgm.value;
    }

    if( form.dtchequeini.value != ""){
      query +="&dtfi="+ form.dtchequeini_ano.value+"_"+form.dtchequeini_mes.value+"_"+form.dtchequeini_dia.value;
    }

    if (form.dtchequefim.value != '') {
      query += '&dtDataChequeFinal='+form.dtchequefim.value;
    }
    //if(form.e83_codtipo.value!=0){
    //  query += "&e83_codtipo="+form.e83_codtipo.value;
    // }

    if(form.cheque.value !=''){
      query += "&cheque="+form.cheque.value;
    }

    /**
     * Zera total e quantidade de registros selecionados
     */
    $('tot').value = '0,00';
    $('registros').value = '0';

    /**
     * Emite relatorio
     */
    if (tipo == 2) {

      jan = window.open('emp4_empagepagamento001_cheques002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      jan.moveTo(0,0);

      /**
       * Empenhos com cheques
       * botao "Pgtos C/Cheques"
       */
    } else if(tipo==1) {

      ordem.location.href = "emp4_pagamentoagenda001.php?iForma=2&"+query;
      //ordem.location.href = "emp4_empagepagamento001_cheques.php?"+query;

      /**
       * Pagamento eletronico
       * Botao "Pgtos Bancários"
       */
    } else if(tipo==6) {

      ordem.location.href = "emp4_empagepagamento001_banco.php?"+query;
      js_limparcampos();

      /**
       * Empenhos sem cheques
       * Botao "Pgtos em Dinheiro"
       */
    } else if(tipo==3) {

      ordem.location.href = "emp4_pagamentoagenda001.php?iForma=1&"+query;

      /**
       * Slips com cheques
       * Botao "Slip C/Cheques"
       */
    } else if(tipo == 4) {

      ordem.location.href = "emp4_empagepagamento001_slipche.php?"+query;
      js_limparcampos();

      /**
       * Slips sem cheques
       * Botao "Slip S/Cheques"
       */
    } else if(tipo == 5) {

      ordem.location.href = "emp4_empagepagamento001_slip.php?"+query;
      js_limparcampos();

      /**
       * Debito em conta
       * Botao "Débito Em Conta"
       */
    } else if (tipo == 7) {
      ordem.location.href = "emp4_pagamentoagenda001.php?iForma=4&"+query;
    }

  }

  function js_atualizar(){
    tipo = document.form1.tipo.value;
    if(tipo == ""){
      alert("Selecione algum registro!");
    }else if(tipo == 'slip_cheque' || tipo == 'slip'){  //quando for pagar slip com cheque
      chaves =  ordem.js_retorna_chaves();
      if (chaves=='') {

        alert("Selecione um cheque para efetuar o pagamento!");
        return false;

      }else{

        document.form1.chaves.value=chaves;
        return true;
      }

    }else if(tipo == 'banco'){  //quando for pagar por cheque
      chaves =  ordem.js_retorna_chaves();
      if(chaves==''){
        alert("Selecione um registro para efetuar o pagamento!");
        return false;
      }else{
        document.form1.chaves.value=chaves;
        return true;
      }

    }else if(tipo == 'cheque'){  //quando for pagar por cheque
      chaves =  ordem.js_retorna_chaves();
      if(chaves==''){
        alert("Selecione um cheque para efetuar o pagamento!");
        return false;
      }else{
        document.form1.chaves.value=chaves;
        return true;
      }

    }else if(tipo == 'ordem'){  //quando for pagar por ordem de pagamento
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
          tipo  = eval("ordem.document.form1.e83_codtipo_"+ord+".value");
          if(tipo == 0){
            alert('Não foi selecionado a conta para a ordem '+ord+'!');
            return false;
          }
          coluna += sep+obj[i].value+"-"+valor+"-"+tipo;
          sep= "#";
        }
      }
      document.form1.chaves.value = coluna;
      return true;
    }
  }

  function js_padrao(obj){
    if(ordem.document.form1){
      ordem.js_padrao(obj);
    }
  }
</script>
<form name="form1" method="post" action="">
  <? db_input('chaves',8,0,true,'hidden',1)?>
  <? db_input('tipo',8,0,true,'hidden',1)?>
  <center>
    <br>
    <fieldset>
      <legend><b>Pagamentos</legend>
      <table border="0" width="100%" align="left" cellpadding='0' cellspacing='0'>
        <tr>
          <td align='left' width='20%'height="100%" nowrap valign='top'>
            <table border='0' style='border-right:2px groove white'>
              <tr>
                <td nowrap title="<?=@$Te50_codord?>" align='left'>
                  <? db_ancora(@$Le50_codord,"js_pesquisae50_codord(true);",$db_opcao);  ?>
                </td>
                <td nowrap>
                  <? db_input('e50_codord',10,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'")  ?>
                </td>
                <td>
                  <? db_ancora("<b>Até:</b>","js_pesquisae50_codord02(true);",$db_opcao);  ?>
                </td>
                <td align="left">
                  <? db_input('e50_codord',10,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord02(false);'","e50_codord02")?>
                </td>
              </tr>
              <tr>
                <td nowrap><b>Data Ordem:</b>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('dataordemini',null,null,null,true,'text',1);
                  ?>
                </td>
                <td nowrap style='text-align:center'><b>A</b>
                </td>
                <td nowrap style='text-align:left;padding:0px'>
                  <?
                  db_inputdata('dataordemfim',null,null,null,true,'text',1);
                  ?>
                </td>
              </tr>
              <tr >
                <td nowrap title="<?=@$Tz01_numcgm?>" align='left'>
                  <?
                  db_ancora("<b>Nome:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
                  ?>
                </td>
                <td nowrap colspan=3>
                  <?
                  db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'")
                  ?>
                  <?
                  db_input('z01_nome',25,$Iz01_nome,true,'text',3,'')
                  ?>
                </td>
              </tr>
              <tr>
                <td  align="left" nowrap title="<?=$Te60_numemp?>">
                  <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
                </td>
                <td  nowrap>
                  <input name="e60_codempx" id='e60_codemp' title='<?=$Te60_codemp?>' size="10" type='text'  onKeyPress="return js_mascara(event);" >
                </td>
                <td>
                  <?db_ancora("<b>Até:</b>","js_pesquisae60_codemp2(true);",$db_opcao);  ?>
                </td>
                <td align='left'>
                  <? db_input('e60_codemp2',10,$Ie60_codemp,true,'text',$db_opcao," onchange='js_pesquisae60_codemp2(false);'")  ?>
                </td>
              </tr>
              <tr>
                <td nowrap><b>Conta Pagadora:</b></td>
                <td colspan=3 nowrap>

                  <?php
                  $arr['0']="Nenhum";
                  for($r=0; $r<$numrows05; $r++){
                    db_fieldsmemory($result05,$r);
                    $arr[$codtipo] = "{$e83_conta} - {$e83_descr} - {$c61_codigo}";
                  }
                  $e83_codtipo ='0';
                  db_select("e83_codtipo",$arr,true,1,"onchange='js_setContaPadrao(this.value);' style='width:100%'");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap>
                  <b>Data de Pagamento: </b>
                </td>
                <td colspan='1'>
                  <?
                  db_inputdata("e42_dtpagamento",@$dtfi_dia,@$dtfi_mes,@$dtfi_ano,true,"text", 1);
                  ?>
                </td>
              </tr>
            </table>
          </td>
          <td valign='top' width="20%" height="100%" style='border-right:2px groove white'>
            <table  border='0'>
              <tr>
                <td align='left'>
                  <b> <? db_ancora("SLIP","js_slip(true);",1);  ?>:</b>
                </td>
                <td nowrap>
                  <?=db_input('k17_codigo',10,'',true,'text',1,"onchange='js_slip(false);'", '', '', '', 7)?>
                </td><td>
                  <b> <? db_ancora("Até:","js_slip2(true);",1);  ?></b>
                </td><td>
                  <?=db_input('k17_codigo',10,'',true,'text',1,"onchange='js_slip2(false);'","k17_codigo2", '', '', 7)?>

                </td>
              </tr>
              <tr>
                <td nowrap><b>Data Slip:</b>
                </td>
                <td nowrap>
                  <?

                  db_inputdata('dataslipini',@$dtslipini_dia,@$dtslipini_mes,@$dtslipini_ano,true,'text',1);
                  ?>
                </td>
                <td nowrap><b>A</b>
                </td>
                <td nowrap >
                  <?

                  db_inputdata('dataslipfim',@$dtslipfi_dia,@$dtslipfi_mes,@$dtslipfi_ano,true,'text',1);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  &nbsp;<b>Cheque:</b></b>
                </td>
                <td nowrap="nowrap">
                  <?
                  db_input('cheque',10,'',true,'text',1);
                  ?>

                </td>
              </tr>
              <tr>
                <td nowrap><b>Data cheques:</b>
                </td>
                <td nowrap>
                  <?
                  if (empty($dtfi_dia)  && $lUsaData) {

                    $dtfi_dia = date("d",db_getsession("DB_datausu"));
                    $dtfi_mes = date("m",db_getsession("DB_datausu"));
                    $dtfi_ano = date("Y",db_getsession("DB_datausu"));
                  }

                  db_inputdata('dtchequeini',@$dtfi_dia,@$dtfi_mes,@$dtfi_ano,true,'text',1);
                  ?>
                </td>
                <td nowrap><b>A</b>
                </td>
                <td nowrap >
                  <?
                  if (empty($dtfi_dia) && $lUsaData){

                    $dtfi_dia = date("d",db_getsession("DB_datausu"));
                    $dtfi_mes = date("m",db_getsession("DB_datausu"));
                    $dtfi_ano = date("Y",db_getsession("DB_datausu"));

                  }
                  db_inputdata('dtchequefim',@$dtfi_dia,@$dtfi_mes,@$dtfi_ano,true,'text',1);
                  ?>
                </td>
              </tr>
              <tr nowrap>
                <td nowrap title="<?=@$To15_codigo?>"><? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",$db_opcao); ?>
                </td>
                <td colspan=3 nowrap>
                  <? db_input('o15_codigo',10 ,$Io15_codigo,true,'text',$db_opcao," onchange='js_pesquisac62_codrec(false);'") ?>
                  <? db_input('o15_descr',30 ,$Io15_descr,true,'text',3,'')   ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>
                    <? db_ancora("<b>OP auxiliar</b>","js_pesquisae42_sequencial(true);",$db_opcao);  ?>
                  </b>
                </td>
                <td>
                  <input type='text' size="10" id='e42_sequencial' onchange='js_pesquisae42_sequencial(false);' name='e42_sequencial'>
                </td>
              </tr>
              </tr>
            </table>
          </td>
          <td align='left' valign='top' width="60%">
            <table>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan='4' align='left'>
            <hr style='color:white'>
            <input name="atualizar" type="submit" id='efetuapagamento' value="Pagamento de Slips"
                   onclick='return js_atualizar();' style='display:none'>
            <!--
              &nbsp;
            <input  name="pesquisar" type="button" value="Pgtos Bancários" onclick='js_pesquisar("6");' >
            -->
            <input name="pesquisar" type="button" value="Pgtos C/Cheques" onclick='js_pesquisar("1");' >
            <input name="pesquisar02_" type="button" value="Pgtos em Dinheiro " onclick='js_pesquisar("3");' >
            <input name="pesquisar03" type="button" value="Débito Em Conta" onclick='js_pesquisar("7");' >
            <input name="pesquisar02_" type="button" value="Slip C/Cheques " onclick='js_pesquisar("4");' >
            <input name="pesquisar02_" type="button" value="Slip S/Cheques " onclick='js_pesquisar("5");' >

          </td>
        </tr>
        <tr>
          <td colspan='4' align='left'>
            <iframe name="ordem"  width="100%" height="350" marginwidth="0" marginheight="0" frameborder="0">
            </iframe>
          </td>
        </tr>
        <tr>
          <td colspan='2' align='left'>
            <b>Total:</b>
            <?
            $tot = '0.00';
            db_input('tot',14,'',true,'text',3);
            ?>
            &nbsp;&nbsp;&nbsp;&nbsp;<b>Registros:</b>
            <?
            $registros = '0';
            db_input('registros',4,'',true,'text',3);
            ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
          </td>
        </tr>
      </table>
    </fieldset>
  </center>
</form>
<script>
  function js_limparcampos(){
    l = document.form1;
    l.e50_codord.value = "";
    l.e50_codord02.value = "";
    l.k17_codigo.value = "";
    l.k17_codigo2.value = "";
    l.e60_codempx.value = "";
    l.z01_numcgm.value = "";
    l.z01_nome.value = "";
    l.cheque.value = "";
  }
  function js_empage(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage','Pesquisa',true);
    }else{
      codage =  document.form1.e80_codage.value;
      if(codage != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empage','func_empage.php?pesquisa_chave='+codage+'&funcao_js=parent.js_mostra02','Pesquisa',false);
      }
    }
  }
  function js_mostra(codage){
    db_iframe_empage.hide();
    document.form1.e80_codage.value =  codage;
  }

  function js_mostra02(chave,erro){
    if(erro==true){
      document.form1.e80_codage.focus();
      document.form1.e80_codage.value = '';
    }
  }


  // arquivos



  function js_empagearquivos(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empagearquivos','func_empagegera.php?funcao_js=parent.js_mostraarquivos|e87_codgera','Pesquisa',true);
    }else{
      codgera =  document.form1.e87_codgera.value;
      if(codgera != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empagearquivos','func_empagegera.php?pesquisa_chave='+codgera+'&funcao_js=parent.js_mostraarquivos02','Pesquisa',false);
      }
    }
  }
  function js_mostraarquivos(codgera){
    db_iframe_empagearquivos.hide();
    document.form1.e87_codgera.value = codgera;
  }

  function js_mostraarquivos02(chave,erro){
    if(erro==true){
      document.form1.e87_codgera.focus();
      document.form1.e87_codgera.value = '';
    }
  }




  function js_pesquisae60_codemp(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
    }else{
      // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
    }
  }

  function js_mostraempempenho2(chave1){
    document.form1.e60_codempx.value = chave1;
    db_iframe_empempenho02.hide();
  }

  function js_pesquisae60_codemp2(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho3|e60_codemp','Pesquisa',true);
    }else{
      // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
    }
  }

  function js_mostraempempenho3(chave1){
    document.form1.e60_codemp2.value = chave1;
    db_iframe_empempenho02.hide();
  }

  function js_pesquisae60_numemp(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
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
  //---ordem 01
  function js_pesquisae50_codord(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
    }else{
      ord01 = new Number(document.form1.e50_codord.value);
      if(ord01 != ""){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord01+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
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
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord','Pesquisa',true);
    }else{
      ord02 = new Number(document.form1.e50_codord02.value);
      if(ord02 != ""){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord02+'&funcao_js=parent.js_mostrapagordem02','Pesquisa',false);
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
  //------------SLIP
  function js_slip(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip|k17_codigo','Pesquisa',true);
    }else{
      codigo  =  document.form1.k17_codigo.value;
      if(codigo != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?pesquisa_chave='+codigo+'&funcao_js=parent.js_mostraslip02','Pesquisa',false);
      }
    }
  }
  function js_mostraslip(codage){
    db_iframe_slip.hide();
    document.form1.k17_codigo.value =  codage;
  }

  function js_mostraslip02(chave,erro){
    if(erro==true){
      document.form1.k17_codigo.focus();
      document.form1.k17_codigo.value = '';
    }
  }

  //------------SLIP2
  function js_slip2(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip2|k17_codigo','Pesquisa',true);
    }else{
      codigo  =  document.form1.k17_codigo2.value;
      if(codigo != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?pesquisa_chave='+codigo+'&funcao_js=parent.js_mostraslip022','Pesquisa',false);
      }
    }
  }
  function js_mostraslip2(codage){
    db_iframe_slip.hide();
    document.form1.k17_codigo2.value =  codage;
  }
  function js_mostraconta(chave1,chave2){
    document.form1.c62_reduz.value = chave1;
    document.form1.c60_descr.value = chave2;
    db_iframe_conplanoreduz.hide();
  }

  function js_pesquisac62_codrec(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
    }else{
      if(document.form1.o15_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
      }else{
        document.form1.o15_descr.value = '';
      }
    }
  }

  function js_mostraorctiporec(chave,erro){
    document.form1.o15_descr.value = chave;
    if(erro==true){
      document.form1.o15_codigo.focus();
      document.form1.o15_codigo.value = '';
    }
  }

  function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
  }
  function js_mostraslip022(chave,erro){
    if(erro==true){
      document.form1.k17_codigo2.focus();
      document.form1.k17_codigo2.value = '';
    }
  }
  function js_pesquisae42_sequencial(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('','func_nome','func_empageordem.php?funcao_js=parent.js_mostraordem1|e42_sequencial|e42_dtpagamento','Pesquisa',true);
    } else {
      if ($F('e42_sequencial') != "") {
        js_OpenJanelaIframe('','func_nome','func_empageordem.php?pesquisa_chave='+$F('e42_sequencial')+'&funcao_js=parent.js_mostraordemagenda',
          'Pesquisa',false);
      } else {
        $('e42_sequencial').value = '';
      }
    }
  }

  function js_mostraordem1(chave1,chave2){

    document.form1.e42_sequencial.value = chave1;
    document.form1.e42_dtpagamento.value = js_formatar(chave2,"d");
    func_nome.hide();

  }

  function js_mostraordemagenda(chave,erro){

    if(!erro) {
      document.form1.e42_dtpagamento.value = chave;
    } else {

      document.form1.e42_sequencial.value  = '';
      document.form1.e42_dtpagamento.value = '';

    }
  }

</script>