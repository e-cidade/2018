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
$clrotulo->label("e82_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("k17_codigo");
$clrotulo->label("e60_emiss");
$clrotulo->label("e87_descrgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");

$clempagepag= new cl_empagepag;
$dbwhere = '';
//$dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e90_codmov is null and e97_codforma=2 ";
$rsContas       = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_codtipo as codtipo,
                                                                     e83_descr,
                                                                     e83_conta,
                                                                     c61_codigo"
                                                                    ,"e83_conta"));
$iNumRowsContas = $clempagetipo->numrows;
$arr['0']="Nenhum";
for($r = 0; $r < $iNumRowsContas; $r++){

  db_fieldsmemory($rsContas,$r);
  $arr[$codtipo] = "{$e83_conta} - {$e83_descr} - {$c61_codigo}";

}
$e83_codtipo   = '0';
$e83_sequencia = '';
?>

<script>
function js_mascara(evt) {

  var evt = (evt) ? evt : (window.event) ? window.event : '';

  if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:.
    return true;
  }else{
    return false;
  }
}

function js_atualizar(){
  if(ordem.document.form1){
    obj = ordem.document.form1;
	var coluna='';
	var sep='';
	for(i=0; i<obj.length; i++){
	  nome = obj[i].name.substr(0,5);
      if(nome=="CHECK" && obj[i].checked==true){
        ord = obj[i].name.substring(6);
        coluna += sep+obj[i].value;
        sep= "XX";
      }
    }
    if(coluna==''){
      alert("Selecione um movimento!");
      return false;
    }
    document.form1.movs.value = coluna;
    return true;
  }else{
    alert("Clique em pesquisar para selecionar um movimento!");
    return false;
  }
}
function js_troca(campo){
  document.form1.e83_sequencia.value= eval('document.form1.e83_sequencia_'+campo.value+'.value');
}

function js_ver(){
  if(document.form1.e83_codtipo.value!=0){
    query = "?e83_codtipo="+document.form1.e83_codtipo.value;
    js_OpenJanelaIframe('','db_iframe_anula','func_empageconf001.php'+query,'Pesquisa',true);
  }
}
function js_anular(){
  if(document.form1.e83_codtipo.value!=0){
    query = "?e83_codtipo="+document.form1.e83_codtipo.value;
    js_OpenJanelaIframe('','db_iframe_anula','emp4_empageconfcanc001.php'+query,'Pesquisa',true);
  }
}

</script>
<br/>
<form name='form1' method="post">
<center>
  <table width="80%">
    <tr>
      <td>
        <fieldset>
          <legend><b>Filtros</b></legend>
          <table border="0">
            <tr>
              <td>
                <? db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
              </td>
              <td nowrap>
                <? db_input('e82_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'")  ?>
              </td>
              <td>
                <? db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",$db_opcao);  ?>
              </td>
              <td nowrap>
                <? db_input('e82_codord2',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord02(false);'","e82_codord02")?>
              </td>
              <td align="right" nowrap title="<?=$Te60_numemp?>">
               <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
              </td>
              <td nowrap>
                 <input name="e60_codemp" id='e60_codemp' title='<?=$Te60_codemp?>' size="10" type='text'
                        onKeyPress="return js_mascara(event);" >
              </td>
            </tr>
            <tr>
              <td style='text-align:left'>
                <b>Data Inicial:</b>
              </td>
              <td nowrap>
                <?
                 db_inputdata("dataordeminicial",null,null,null,true,"text", 1);
                ?>
              </td>
              <td>
                <b>Data Final:</b>
              </td>
              <td nowrap>
                <?
                 db_inputdata("dataordemfinal",null,null,null,true,"text", 1);
                ?>
              </td>
              <td align="right" nowrap title="<?=$Tk17_codigo?>">
              <? db_ancora(@$Lk17_codigo,"js_pesquisak17_codigo(true);",$db_opcao);  ?>
              </td>
              <td nowrap>
                 <input name="k17_codigo" id='k17_codigo' title='<?=$Tk17_codigo?>' size="10" type='text'>
              </td>
              <td align="right" nowrap title="<?=$Tk17_codigo?>">
              <? db_ancora("<b>Até</b>","js_pesquisak17_codigo2(true);",$db_opcao);  ?>
              </td>
              <td nowrap>
                 <input name="k17_codigo2" id='k17_codigo2' title='<?=$Tk17_codigo?>' size="10" type='text'>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_numcgm?>">
                <?
                db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
                ?>
              </td>
              <td  colspan='3'>
                <?
                 db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
                 db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
               <td>
                 <b>Data de Pagamento: </b>
                </td>
                <td colspan='1'>
                 <?
                   db_inputdata("e42_dtpagamento",null,null,null,true,"text", 1);
                 ?>
               </td>
               <td>
                 <b>
                   <? db_ancora("<b>OP auxiliar</b>","js_pesquisae42_sequencial(true);",$db_opcao);  ?>
                 </b>
               </td>
               <td>
                <input type='text' size="10" id='e42_sequencial' onchange='js_pesquisae42_sequencial(false);' name='e42_sequencial'>
               </td>
            </tr>
            <tr nowrap>
              <td nowrap title="<?=@$To15_codigo?>"><? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",$db_opcao); ?>
              </td>
              <td colspan=3 nowrap>
              <? db_input('o15_codigo',10,$Io15_codigo,true,'text',$db_opcao," onchange='js_pesquisac62_codrec(false);'") ?>
              <? db_input('o15_descr',30,$Io15_descr,true,'text',3,'')   ?>
              </td>
            </tr>
            <tr>
              <td nowrap>
                 <b>Conta pagadora:</b>
              </td>
              <td colspan="4">
              <?
               if (isset($iNumRowsContas) && $iNumRowsContas>0) {

                db_select("e83_codtipo",$arr,true,1,"style='width:25em'");

               }
               ?>
               </td>
              </tr>
            <tr>
              <td colspan='6' style='text-align: center'>

                <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar Ordens" onclick='js_pesquisarOrdens(1)'>
                <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar Slips" onclick='js_pesquisarOrdens(2)'>

              </td>
            </tr>
          </table>
        </fieldset>
      </td>
      <tr>
        <td>
          <fieldset>
            <legend><b>Informação para o Cheque</b></legend>
            <table border=0>
              <tr>
                <td>
                  <b>Credor:</b>
                </td>
                <td>
                  <?
                    db_input('z01_nome',52,@$z01_nome,true,'text',1,'','credor');
                  ?>
                </td>
                <td>
                  <b>Data:</b>
                </td>
                <td>
                  <?
                  if(empty($dtin_dia)){

                    $dtin_dia = date('d',db_getsession('DB_datausu'));
                    $dtin_mes = date('m',db_getsession('DB_datausu'));
                    $dtin_ano = date('Y',db_getsession('DB_datausu'));
                  }
                  db_inputdata('dtimp',$dtin_dia,$dtin_mes,$dtin_ano,true,'text',1);
                  ?>
                </td>
                <td rowspan="4" valign="top">
                <fieldset style="height: 88%">
                  <legend><b>Saldos da Conta</b></legend>
                  <table>
                    <tr>
                      <td style='color:blue' id='descrConta' colspan='4'>
                      </td>
                    </tr>
                      <td>
                        <b>Tesouraria:</b>
                      </td>
                      <td>
                         <?
                           db_input("saldotesouraria",15,null,true,"text",3);
                         ?>
                      </td>
                      <td>
                        <b>Cheques:</b>
                      </td>
                      <td>
                         <?
                           db_input("totalcheques",15,null,true,"text",3);
                         ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <b>Disponível:</b>
                      </td>
                      <td>
                         <?
                           db_input("saldoatual",15,null,true,"text",3);
                         ?>
                      </td>
                    </tr>
                     </table>
                     <hr>
                     <b>Número do próximo cheque:</b>
                      <?
                       db_input("numerocheque",15,null,true,"text",3);
                      ?>
                </fieldset>
                </td>
              </tr>
              <tr>
                <td>
                 &nbsp;
                </td>
                <td>
                   <input type='checkbox' id="imprimirverso" name='imprimirverso' value='imprimirverso'>
                   <label for="imprimirverso">Imprimir Verso</label>
                   <input type='checkbox' id="imprimircomplemento" name='imprimircomplemento' value='1'>
                   <label for="imprimircomplemento">Imprimir Complemento</label>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Verso:</b>
                </td>
                <td colspan='3'>
                  <?
                    db_textarea('verso',5,80,0,true,'text',1);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan='2' align='center'>
          <input name="processar" type="button" id="processar" value="Emitir Cheque" onclick='js_emiteCheques()');'>
          <input name="prever" id='prever' type="button" value="Visualizar verso"  onclick='return js_verVerso();' >
          <input name="cancelar_cheque" type="button" id="cancelar_cheque" value="Cancelar Cheque" onclick='js_anularCheque()'>
          <input name="manut" type="button" id="manut" value="Manutenção de Pagamentos" onclick='location.href="emp4_empageforma001.php"'>
          <b>Total: </b>
          <?=db_input('total_dos_cheques',10,'',true,'hidden',3)?>
          <?=db_input('total',10,'',true,'text',3)?>
          <?=db_input('valor_dos_cheques',10,'',true,'hidden',3)?>
          <?
          $arr_c = array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6");
          db_select("cheques",$arr_c,true,1);
          ?>
          <input name="valorescheques" id='valoresdoschques' type="button" value="Informar valores" onclick='js_informar_valores();'>
        </td>
      </tr>
      <tr>
         <td>
           <fieldset>
             <legend>
               <b>Ordens Encontradas:</b>
             </legend>
             <div id='gridOrdens'>
             </div>
           </fieldset>
         </td>
      </tr>
      <tr>
        <td colspan='5' align='left'>
          <span>
          <fieldset><legend><b>Mostrar Movimentos</b></legend>
            <input type="checkbox" id='disabled'  onclick='js_showFiltro("comcheque",this.checked)'>
            <label for="disabled" ><b>Com cheque Emitido</b></label>
            <input type="checkbox" id='normal' checked onclick='js_showFiltro("normal",this.checked)'>
            <label for="normal" style='color:black'><b>Sem Cheque</b></label>
          </fieldset>
          </span>
       </td>
    </tr>
  </table>
  </center>
</form>
<div id='callback'></div>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<script>
iTipoControleRetencaoMesAnterior = <?=$iTipoControleRetencaoMesAnterior?>;
function js_fechariframe(con) {

  document.form1.cheques.options[(con-1)].selected = true;
  (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_informar_valores.hide();
  js_liberar_botao(false);
}

function js_recebeval(con,valores_cheques){

  if (aQtdCheques) {
   delete aQtdCheques;
  }
  document.form1.cheques.options[(con-1)].selected = true;
  document.form1.valor_dos_cheques.value = valores_cheques;
  var aCheques = valores_cheques.split("-");
  nTotalCheques = 0;
  for (var iCheques = 0; iCheques < aCheques.length; iCheques++) {
    nTotalCheques += new Number(aCheques[iCheques]);
  }
  $('total_dos_cheques').value = nTotalCheques;
  aQtdCheques                  = aCheques;
  (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_informar_valores.hide();
  js_liberar_botao(false);

}

function js_informar_valores(){
  js_OpenJanelaIframe('','db_iframe_informar_valores','func_informarvalores.php?forma=true&quantidade='+document.form1.cheques.value+'&total='+document.form1.total.value+'&ch='+document.form1.valor_dos_cheques.value,'Pesquisa',true);
}

function js_liberar_botao(limpar){
	if(limpar == true){
    document.form1.valor_dos_cheques.value = "";
	}
  if(document.form1.cheques.value > 1){
  	valor = new Number(document.form1.total.value);
  	if(valor > 0){
  	  document.form1.valorescheques.disabled = false;
  	}
  }else{
  	document.form1.valorescheques.disabled = false;
  }
}

function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho.hide();
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
function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
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
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord','Pesquisa',true);
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

//---------------------------------------------------
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

function js_pesquisak17_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
  }
}
function js_mostraslip1(chave1){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}

function js_pesquisak17_codigo2(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip2|k17_codigo','Pesquisa',true);
  }
}
function js_mostraslip1(chave1){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}

function js_mostraslip2(chave){
  document.form1.k17_codigo2.value = chave;
  db_iframe_slip.hide();
}
function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
   }else{
       if(document.form1.o15_codigo.value != ''){
           js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false,0,0,0);
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
/*
 * Monta o grid, e algumas informações necessárias para o programa.
 */

function js_init() {

  gridOrdens              = new DBGrid("gridOrdens");
  gridOrdens.selectSingle = function (oCheckbox,sRow,oRow,lVerificaSaldo) {

    if (lVerificaSaldo == null) {
      lVerificaSaldo = true;
    }
    if (oCheckbox.checked ) {

      oRow.isSelected    = true;
      var iAgendaOld     = null;
      $(sRow).className  += 'Marcado';
      oRow.isSelected    = true;
      $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)+1;
      var oTotalCheques  = new Number($F('total'));
      oTotalCheques     += js_strToFloat(oRow.aCells[7].getValue());
      $('credor').value = oRow.aCells[5].getValue();
      if (oRow.aCells[7].getValue() != "" && lVerificaSaldo) {
        js_getSaldos(oRow.aCells[8].getValue(),oRow.aCells[8].getContent());
      }

    } else {

      $(sRow).className  = oRow.getClassName();
      oRow.isSelected    = false;
      var oTotalCheques  = new Number($F('total'));
      oTotalCheques     -= js_strToFloat(oRow.aCells[7].getValue());
      $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)-1;
    }
    $('total').value  = oTotalCheques.toFixed(2);
  }

  gridOrdens.selectAll = function(idObjeto, sClasse, sLinha) {

    var obj = document.getElementById(idObjeto);
    if (obj.checked){
      obj.checked = false;
    } else{
      obj.checked = true;
    }

    itens = this.getElementsByClass(sClasse);
    for (var i = 0;i < itens.length;i++){

      if (itens[i].disabled == false){
        if (obj.checked == true){

          if ($(this.aRows[i].sId).style.display != 'none') {
            if (!itens[i].checked) {

              itens[i].checked=true;
              this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);

            }

          }
        } else {

          if (itens[i].checked) {

            itens[i].checked=false;
            this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i]);
          }
        }
      }
    }
    js_getSaldos(oRow.aCells[8].getValue(),oRow.aCells[8].getContent());
  }
  gridOrdens.nameInstance = "gridOrdens";
  gridOrdens.setCheckbox(0);
  gridOrdens.setSelectAll(true);
  gridOrdens.allowSelectColumns(true);
  gridOrdens.setCellAlign(new Array("right", "Right", "Right",'right', "left", "center", "right", "left", "right"));
  gridOrdens.setHeader(new Array("Mov","Ordem/Slip", "Empenho", "Recurso", 'Nome',"Emissão","Valor" , "Cta. Pag", "Cheque"));
  gridOrdens.aHeaders[9].lDisplayed = false;
  gridOrdens.show(document.getElementById('gridOrdens'));
  $('total').value             = '';
  $('valor_dos_cheques').value = '';
  $('gridOrdensstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
  aQtdCheques = new Array();
  //$('e83_codtipo').style.width="25em";


}

function js_pesquisarOrdens(iTipoPesquisa){

  js_bloqueiaBotoes(true);
  js_divCarregando("Aguarde, consultando Ordens.","msgBox");
  var oParam           = new Object();
  $('total').value     = '';
  js_reset();
  /*
   * VARIAVEL EM ESCOPO GLOBAL!!!!!
   * controle do tipo de consulta feita pelo usuario.
   * tipo 1  - Consulta Ordem tipo 2 consulta de slips
   */

  iTipoConsulta        = iTipoPesquisa;

  aQtdCheques = new Array();
  oParam.iOrdemIni      = $F('e82_codord');
  oParam.iOrdemFim      = $F('e82_codord02');
  oParam.iCodEmp        = $F('e60_codemp');
  oParam.dtDataIni      = $F('dataordeminicial');
  oParam.dtDataFim      = $F('dataordemfinal');
  oParam.iNumCgm        = $F('z01_numcgm');
  oParam.iCodigoSlip    = $F('k17_codigo');
  oParam.iCodigoSlipFim = $F('k17_codigo2');
  oParam.iCodigoConta   = $F('e83_codtipo');
  oParam.sDtAut         = $F('e42_dtpagamento');
  oParam.iRecurso       = $F('o15_codigo');
  oParam.iOPauxiliar    = $F('e42_sequencial');
  oParam.iTipoConsulta  = iTipoConsulta;
  var sParam            = js_objectToJson(oParam);
  url       = 'emp4_agendaPagamentoRPC.php';
  var sJson = '{"exec":"getOrdens","params":['+sParam+']}';
  var oAjax   = new Ajax.Request(
                         url,
                         {
                          method    : 'post',
                          parameters: 'json='+sJson,
                          onComplete: js_retornoConsultaOrdens
                          }
                        );
}

function js_retornoConsultaOrdens(oAjax) {

  js_bloqueiaBotoes(false);
  js_removeObj("msgBox");
  var oResponse = eval("("+oAjax.responseText+")");
  gridOrdens.clearAll(true);
  var iRowAtiva    = 0;
  var iTotalizador = 0;
  $('gridOrdensstatus').innerHTML = "";
  if (oResponse.status == 1) {

    for (var iNotas = 0; iNotas < oResponse.aNotasLiquidacao.length; iNotas++) {

      with (oResponse.aNotasLiquidacao[iNotas]) {

        iTotalizador++;
        var iValTotalNota = js_round((e81_valor) - valorretencao,2);
        if (iValTotalNota > new Number(0)) {

          var sClassName = '';
          var sReadOnly  = '';
          if (e91_codmov != '') {

            sClassName = 'comcheque';
            sReadOnly  = ' readonly ';
          }
          validaretencao = validaretencao == 't'?true:false;
          var aLinha = new Array();
          aLinha[0]  = e81_codmov
          aLinha[1]  = e50_codord;
          if (tipo == 2) {

            aLinha[2]  = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
            aLinha[2] += e60_codemp+"/"+e60_anousu+"</a>";

          } else {
            aLinha[2] = "<a onclick='js_pesquisaSlip("+e50_codord+");return false;' href='#'>Slip<\a>";
          }
          aLinha[3]  = o15_codigo,
          aLinha[4]  = z01_nome.urlDecode();
          aLinha[5]  = js_formatar(e50_data,"d");
          aLinha[6]  = js_formatar(iValTotalNota,'f');
          var sConta  = new String(e83_conta+" - "+e83_descr.urlDecode());
          aLinha[7] = "<span style='display:none'>"+e85_codtipo+"</span>"+sConta.substring(0,25);
          aLinha[7] += "<span style='display:inline' id='validarretencao"+e81_codmov+"'></span></div>";
          //aLinha[6]  = js_createComboContasPag(e81_codmov, aContasVinculadas, e85_codtipo, '');
          aLinha[8]  = e91_cheque;
          gridOrdens.addRow(aLinha);
          if (e91_codmov != '' || e90_codmov != '') {

            if (!$('disabled').checked) {
               gridOrdens.aRows[iRowAtiva].lDisplayed   = false;
               iTotalizador--;
            }
            gridOrdens.aRows[iRowAtiva].setClassName('comcheque');

          } else {
             if (!$('normal').checked) {

               gridOrdens.aRows[iRowAtiva].lDisplayed   = false;
               iTotalizador--;

             }
          }
          gridOrdens.aRows[iRowAtiva].aCells[5].sEvents  = "onmouseover='js_setAjuda(\""+z01_nome.urlDecode()+"\",true)'";
          gridOrdens.aRows[iRowAtiva].aCells[5].sEvents += "onmouseOut='js_setAjuda(null,false)'";
          gridOrdens.aRows[iRowAtiva].aCells[8].sEvents  = "onmouseover='js_setAjuda(\""+sConta+"\",true)'";
          gridOrdens.aRows[iRowAtiva].aCells[8].sEvents += "onmouseOut='js_setAjuda(null,false)'";
          gridOrdens.aRows[iRowAtiva].sValue  = e81_codmov;
          iRowAtiva++;

        }
      }
    }
    gridOrdens.renderRows();
    gridOrdens.setNumRows(iTotalizador);
     $('gridOrdensstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
  } else if (oResponse.status == 2) {
     $('gridOrdensstatus').innerHTML = "&nbsp;<b>Não foram encontrados movimentos.</b>";
  }

}
function js_objectToJson(oObject) { return JSON.stringify(oObject); 

   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;

}

function js_createComboContasPag(iCodOrdem, aContas, iContaConfigurada,sClassName) {

  if (sClassName == '') {
    sClassName = 'ctapag';
  }
  var sCombo  = "<select id='ctapag"+iCodOrdem+"' class='"+sClassName+"' style='width:100%'";
  sCombo     += " onchange='js_getSaldos(this)'>";
  sCombo     += "<option value=''>Selecione</option>";
  if (aContas != null) {

    for (var i = 0; i < aContas.length; i++) {

      var sSelected = '';
      if (aContas[i].e83_codtipo == iContaConfigurada) {
        sSelected = " selected ";
      }
      var sDescrConta =  aContas[i].e83_conta+" - "+aContas[i].e83_descr.urlDecode()+" - "+aContas[i].c61_codigo;
      sCombo += "<option "+sSelected+" value = "+aContas[i].e83_codtipo+">"+sDescrConta+"</option>";

    }
  }
  sCombo  += "</select>";
  return sCombo;
}

function js_verVerso() {

  var aMovimentos        = new Array();
  var aNotasSelecionadas = gridOrdens.getSelection("object");
  for (var i = 0;i < aNotasSelecionadas.length; i++ ) {

    var oMovimento  = new Object();
    oMovimento.iCodMov = aNotasSelecionadas[i].aCells[0].getValue();
    aMovimentos.push(oMovimento);

  }
  url       = 'emp4_agendaPagamentoRPC.php';
  var sJson = '{"exec":"getVersoCheque","params":[{"aMovimentos":'+aMovimentos.toSource()+'}]}';
  $('imprimirverso').checked = true;
  var oAjax   = new Ajax.Request(
                         url,
                         {
                          method    : 'post',
                          parameters: 'json='+sJson,
                          onComplete: js_retornoVerVerso
                          }
                        );
}

function js_retornoVerVerso(oAjax) {

  $('verso').value = oAjax.responseText.urlDecode();

}

function js_getSaldos(iCodConta, sInfoConta) {

  if (iCodConta!= 0) {

    if ($('descrConta').innerHTML == sInfoConta) {
      return false;
    }
    js_divCarregando("Aguarde, Verificando saldo da conta.","msgBox");
    $('descrConta').innerHTML = sInfoConta;
    url       = 'emp4_agendaPagamentoRPC.php';
    var sJson = '{"exec":"getSaldos","params":[{"iCodTipo":"'+iCodConta+'","dtBase":"'+$F('dtimp')+'"}]}';
    var oAjax   = new Ajax.Request(
                           url,
                           {
                            method    : 'post',
                            parameters: 'json='+sJson,
                            onComplete: js_retornoGetSaldos
                            }
                          );
  }

}
function js_retornoGetSaldos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno               = eval("("+oAjax.responseText+")");
  $('saldotesouraria').value = new Number(oRetorno.oSaldoTes.rnvalortesouraria);
  $('totalcheques').value    = new Number(oRetorno.oSaldoTes.rnvalorcheques);
  $('saldoatual').value      = new Number(oRetorno.oSaldoTes.rnsaldofinal).toFixed(2);
  $('numerocheque').value    = new Number(oRetorno.iCheque);
}
/**
 * Emite o cheque
 */
function js_emiteCheques() {

   var aNotasSelecionadas = gridOrdens.getSelection("object");

   if (aNotasSelecionadas.length == 0) {

     alert('Nao há Notas selecionadas para gerar cheque.');
     return false;
   }
   /*
    * Verificamos se o usuário marcou a mesma conta em todas as ordens selecionados.
    * so podemos permitir que o cheque seje emitido se todas as ordens possuirem a mesma conta
    * Conta pagadora nao pode ser vazia.
    */
    var sJsonItens              = "";
    var iCtaPagadora            = null;
    var sVirgula                = '';
    var lMostraMsgErroRetencao  = false;
    var sMsgRetencaoMesAnterior = "Atenção:\n";
    var sVirgulaMsg             = "";
    for (var iNotas  = 0; iNotas < aNotasSelecionadas.length; iNotas++) {

      var iCtaConfigurada      = aNotasSelecionadas[iNotas].aCells[8].getValue();
      var iCodMov              = aNotasSelecionadas[iNotas].aCells[1].getValue();
      var iCodNota             = aNotasSelecionadas[iNotas].aCells[2].getValue();
      var nValor               = js_strToFloat(aNotasSelecionadas[iNotas].aCells[7].getValue()).valueOf();
      var lRetencaoMesAnterior = $('validarretencao'+iCodMov).innerHTML;
      if (aNotasSelecionadas[iNotas].aCells[3].getValue() == "Slip") {
        var iNumEmp = "0";
      } else {
        var iNumEmp = aNotasSelecionadas[iNotas].aCells[3].getValue();
      }

      if (lRetencaoMesAnterior == "true") {

        lMostraMsgErroRetencao   = true;
        sMsgRetencaoMesAnterior += sVirgulaMsg+"Movimento "+iCodMov+" da OP ";
        sMsgRetencaoMesAnterior += iCodNota+" possui retenções configuradas em meses anteriores.\n";
        sVirgulaMs = ", ";

     }
      /*
       * caso o movimento esta já possui um cheque emitido, não podemos deixar emitir
       * Novamente. Avisamos o usuário, e cancelamos a operação.
       */
      if (aNotasSelecionadas[iNotas].getClassName() == "comcheque") {

         alert("A nota "+iCodNota+", Movimento "+iCodMov+" Já possui Cheque emitido.\nPara prosseguir, desmarque esse Movimento.");
         return false;

      }
      if (iCtaConfigurada == "") {

        alert("A nota "+iCodNota+", Movimento "+iCodMov+" não possui conta pagadora configurada.\nVerifique.");
        return false;

      }
      if (iCtaPagadora == null) {
        iCtaPagadora = iCtaConfigurada;
      } else {
        if (iCtaPagadora != iCtaConfigurada) {

          alert("As contas pagadoras devem ser iguais.\nVerifique.");
          return false;

        }
      }

      sJsonItens += sVirgula+'{"iCodAgenda":null,';
      sJsonItens += '"iCodMov":'+iCodMov+',"iCodNota":'+iCodNota+',';
      sJsonItens += '"iNumEmp":"'+iNumEmp+'",';
      sJsonItens += '"nValor":'+nValor+',"iCodTipo":'+iCtaConfigurada+'}';
      sVirgula    = ", ";

    }
    /**
     * verificamos o parametro para controle de retencões em meses anteriores.
     * caso seje 0 - não faz nenhuma critica ao usuário. apenas realiza o pagamento.
     *           1 - Avisa ao usuário e pede uma confirmação para realizar o pagamento.
     *           2 - Avisa ao usuário e cancela o pagamento do movimento
     */
    var sMsgConfirmaPagamento = "Deseja realmente confirmar a emissão do(s) cheque(s) os movimentos selecionados?";
    if (iTipoControleRetencaoMesAnterior == 1) {

      if (lMostraMsgErroRetencao) {

        sMsgConfirmaPagamento  =  sMsgRetencaoMesAnterior;
        sMsgConfirmaPagamento += "É Recomendável recalcular as retenções.\n";
        sMsgConfirmaPagamento += "Deseja realmente confirmar a emissão do(s) cheque(s) os movimentos selecionados?";
        if (!confirm(sMsgConfirmaPagamento)) {
           return false;
        }
      }
    } else if (iTipoControleRetencaoMesAnterior == 2) {

      if (lMostraMsgErroRetencao) {

        sMsgConfirmaPagamento    =  sMsgRetencaoMesAnterior;
        sMsgRetencaoMesAnterior += "Recalcule as Retenções do movimento.";
        alert(sMsgRetencaoMesAnterior);
        return false;

      }
    }
    if ($F('cheques') > 1 ) {

      var nTotalCheques = new Number($F('total_dos_cheques'));
      var nTotalOrdens  = new Number($F('total'));
      if (nTotalOrdens.valueOf().toFixed(2) != nTotalCheques.valueOf().toFixed(2)) {

        alert('Total de Cheques diferente do total das Ordens.\nDivida o valor dos Cheques novamente.');
        return false;

      }

    }
    //js_bloqueiaBotoes(true);
    js_divCarregando("Aguarde, Efetuando emissão do cheques.","msgBox");
    var sJson  = '{"exec":"emitirCheque","params":[{"sCredor":"'+encodeURIComponent($F('credor'))+'","dtData":"'+$F('dtimp')+'",';
    sJson     += '"aNotasLiquidacao":['+sJsonItens+'],"aTotCheques":'+aQtdCheques.toSource()+'}]}';
    url        = 'emp4_agendaPagamentoRPC.php';
    var oAjax  = new Ajax.Request(
                           url,
                           {
                            method    : 'post',
                            parameters: 'json='+sJson,
                            onComplete: js_retornoEmissaoCheques
                            }
                          );

}
function js_retornoEmissaoCheques(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    aInfoCheques = oRetorno.aInfoCheques;
    js_bloqueiaBotoes(true);
    if (aInfoCheques.length > 1) {
      if (confirm('Deseja Imprimir Cheque '+aInfoCheques[0].iSeqCheque+'?')) {
        js_imprimirCheques(0);
      } else {
        js_imprimirCheques(1);
      }
    } else {
      js_imprimirCheques(0);
    }
  } else {
    alert(oRetorno.message.urlDecode());
    js_bloqueiaBotoes(false);
  }
}

/**
 *Seta a conta padrao para pagamento em todas as ordens;
 */
function js_setContaPadrao(iCodigoConta) {

  var aItens = gridOrdens.getElementsByClass('ctapag');
  var oUltimoSelect = null;
  for (var i = 0; i < aItens.length; i++) {

    aItens[i].value = $F('e83_codtipo');
    oUltimoSelect = aItens[i];
  }

  if (aItens.length > 0) {
    js_getSaldos(oUltimoSelect);
  }

}
/**
 *Realiza a impressão do cheque.
 * @param integer iSeqCheque sequencia da array dos aQtdCheque
 */
function js_imprimirCheques(iSeqCheque) {

  /*
   *Setamos o cheque ativo.
   */
  js_bloqueiaBotoes(true);
  iChequeAtivo = iSeqCheque;
  var aNotas   = gridOrdens.getSelection();
  var iCodTipo = aNotas[0][8];
  var sJson    = '{"exec":"imprimirFrenteCheque","params":[{"sCredor":"'+encodeURIComponent($F('credor'))+'","dtData":"'+$F('dtimp')+'",';
  sJson       += '"nValor":'+aInfoCheques[iChequeAtivo].nValorCheque+',"iCodTipo":"'+iCodTipo+'"}]}';
  url          = 'emp4_agendaPagamentoRPC.php';
  var oAjax    = new Ajax.Request(
                           url,
                           {
                            method    : 'post',
                            parameters: 'json='+sJson,
                            onComplete: js_retornoimpressaoCheques
                            }
    );

}
function js_retornoimpressaoCheques(oAjax) {

  js_bloqueiaBotoes(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    if (confirm('Deseja Reimprimir o Cheque '+aInfoCheques[iChequeAtivo].iSeqCheque+'?')) {
      js_imprimirCheques(iChequeAtivo);
    } else if ($('imprimirverso').checked == true) {
      if (confirm('Deseja Imprimir Verso?')) {
        js_imprimirVerso(iChequeAtivo);
      }
    } else if (aInfoCheques.length > 0) {

       if (aInfoCheques[iChequeAtivo+1]) {


         delete aInfoCheques[iChequeAtivo];
         if (confirm('Deseja imprimir o Cheque '+aInfoCheques[iChequeAtivo+1].iSeqCheque+'?')) {
           js_imprimirCheques(iChequeAtivo+1);
         } else {

           js_bloqueiaBotoes(false);
           js_pesquisarOrdens();

         }

       }  else {

         js_bloqueiaBotoes(false);
         js_pesquisarOrdens(iTipoConsulta);

       }
    } else {

      js_bloqueiaBotoes(false);
      js_pesquisarOrdens(iTipoConsulta);

    }
  } else {
    alert(oRetorno.message.urlDecode());
    js_pesquisarOrdens(iTipoConsulta);
  }
}

function js_imprimirVerso(iChequeAtivo) {

  js_bloqueiaBotoes(true);
  var aMovimentos        = new Array();
  var aNotasSelecionadas = gridOrdens.getSelection("object");
  for (var i = 0;i < aNotasSelecionadas.length; i++ ) {

    var oMovimento       = new Object();
    oMovimento.iCodMov   = aNotasSelecionadas[i].aCells[0].getValue();
    oMovimento.iCodTipo  = aNotasSelecionadas[i].aCells[8].getValue();
    oMovimento.iCodOrdem = aNotasSelecionadas[i].aCells[2].getValue();
    aMovimentos.push(oMovimento);

  }
  var sVerso  = $F('verso');
  var iCheque = aInfoCheques[iChequeAtivo].iSeqCheque;
  sVerso      = sVerso.replace( /\n/g ,'/n');
  sVerso      = (encodeURIComponent(sVerso));
  var sJson   = '{"exec":"emitirVersoCheque","params":[{"sStringVerso":"'+sVerso+'","aMovimentos":'+aMovimentos.toSource();
  sJson      += ',"lImprimirComplemento":'+$('imprimircomplemento').checked+',"iCheque":'+iCheque+'}]}';
  url         = 'emp4_agendaPagamentoRPC.php';
  var oAjax   = new Ajax.Request(
                           url,
                           {
                            method    : 'post',
                            parameters: 'json='+sJson,
                            onComplete: js_retornoEmissaoVersoCheques
                            }
                          );

}

function js_retornoEmissaoVersoCheques(oAjax) {

  js_bloqueiaBotoes(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    if (confirm('Deseja Reimprimir o verso do Cheque?')) {
      js_imprimirVerso(iChequeAtivo);

    } else if (aInfoCheques.length > 0) {

       if (aInfoCheques[iChequeAtivo+1]) {

         delete aInfoCheques[iChequeAtivo];
        if (confirm('Deseja imprimir o Cheque '+aInfoCheques[iChequeAtivo+1].iSeqCheque+'?')) {
         js_imprimirCheques(iChequeAtivo+1)
        } else {

          js_bloqueiaBotoes(false);
          js_pesquisarOrdens(iTipoConsulta);

        }

       }  else {

         js_bloqueiaBotoes(false);
         js_pesquisarOrdens(iTipoConsulta);

       }
    } else {

      js_bloqueiaBotoes(false);
      js_pesquisarOrdens(iTipoConsulta);


    }
  } else {

    alert(oRetorno.message.urlDecode());
    js_pesquisarOrdens(iTipoConsulta);

  }

}

function js_calculaValor(oTextObj, iRow,iValue,iValTot) {

   if (oTextObj.value > iValTot) {
     oTextObj.value  = iValTot;
   }

   if (oTextObj.value > 0) {

     $('chk'+iValue).checked = true;
     gridOrdens.selectSingle($('chk'+iValue),"rowgridOrdens"+iRow, gridOrdens.aRows[iRow]);

   }
   $('total').value = gridOrdens.sum(7);
}

function js_anularCheque() {

  var aMovimentos = gridOrdens.getSelection("object");
  if (aMovimentos.length == 0) {

    alert('Selecione algum movimento.');
    return false;

  }
  if (!confirm('Confirma a anulação dos Cheques?')) {
    return false;
  }
  /*
   *
   */
  js_bloqueiaBotoes(true);
  var oRequisicao      = new Object();
  oRequisicao.exec     = "cancelarCheques";
  oRequisicao.aCheques = new Array();
  for (var i = 0;i < aMovimentos.length; i++) {

    with (aMovimentos[i]) {

      var iCodMov = aCells[1].getValue()
      if (getClassName() != 'comcheque' ) {

        alert('Movimento '+iCodMov+" sem Cheque. Operação cancelada");
        return false;

      }
      var oCheque     = new Object();
      oCheque.iCodMov =  iCodMov;
      oRequisicao.aCheques.push(oCheque);
    }
  }
  js_divCarregando("Aguarde, Cancelando Cheques.","msgBox");
  var sJson = js_objectToJson(oRequisicao);
  url         = 'emp4_agendaPagamentoRPC.php';
  var oAjax   = new Ajax.Request(
                           url,
                           {
                            method    : 'post',
                            parameters: 'json='+sJson,
                            onComplete: js_retornoCancelarCheques
                            }
                          );
}

function js_retornoCancelarCheques(oAjax) {

  js_bloqueiaBotoes(false);
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert("Cheques Cancelados com Sucesso");
    js_pesquisarOrdens(iTipoConsulta);

  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_reset() {

  $('cheques').value             = 1;
  $('total_dos_cheques').value   = '';
  $('descrConta').innerHTML      = '';
  $('saldotesouraria').value     = '';
  $('totalcheques').value        = '';
  $('saldoatual').value          = '';
  $('numerocheque').value        = '';
  $('verso').value               = '';
  $('credor').value              = '';
  $('valor_dos_cheques').value   = '';
  $('imprimirverso').checked     = false;
  $('cheques').value             = 1;
  //$('valoresdoschques').disabled = true;

}

function js_bloqueiaBotoes(lDisabled) {

    $('processar').disabled = lDisabled;
    $('cancelar_cheque').disabled = lDisabled;
    $('prever').disabled = lDisabled;
    $('pesquisar').disabled = lDisabled;
}


function js_showFiltro(sQualFiltro,lMostrar) {

   var aMatched = gridOrdens.getElementsByClass(sQualFiltro);
   aMatched     = aMatched.concat(gridOrdens.getElementsByClass(sQualFiltro+"Marcado"));
   var iTotalizador = 0;
   for (var i = 0; i < aMatched.length; i++) {
     if (lMostrar) {

       aMatched[i].style.display = '';
       iTotalizador++;

     } else {

       aMatched[i].style.display = 'none';
       iTotalizador--;

     }
   }
   var iTotal = gridOrdens.getNumRows();
   gridOrdens.setNumRows(iTotal + iTotalizador);
}

function js_pesquisaSlip(iCodigoSlip) {
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip2',
                       'cai3_conslip003.php?slip='+iCodigoSlip,'Consulta Lançamento',true);
}

function js_setAjuda(sTexto,lShow) {

  if (lShow) {

    el =  $('gridOrdens');
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;

  } else {
   $('ajudaItem').style.display = 'none';
  }
}
js_init();
js_reset();
</script>
