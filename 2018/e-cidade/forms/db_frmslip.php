<?php
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

require_once("classes/db_conplanoexe_classe.php");
require_once("classes/db_saltes_classe.php");
require_once("libs/db_libdicionario.php");

$clsaltes      = new cl_saltes;
$clconplanoexe = new cl_conplanoexe;

$sql_conta_debitar = $clconplanoexe->sql_conta_debitar(date('Y-m-d',db_getsession("DB_datausu")),
                                     null,"c62_reduz,  c60_estrut ||'-'|| c60_descr",
                                     "c60_estrut",
                                     "c62_anousu = ".db_getsession("DB_anousu")."
                                      and c60_codsis in (1,5,6,7,8)
                                      and substr(c60_estrut,1,1) not in ('3','4')
                                      and c61_instit = ".db_getsession("DB_instit").
                                     "and (case
                                           when (( t1.k02_codigo  is not null
                                                   and t1.k02_limite is not null
                                                   and t1.k02_limite  < '".date('Y-m-d',db_getsession("DB_datausu"))."')
                                                  or (t2.k02_codigo is not null
                                                      and t2.k02_limite  is not null
                                                      and t2.k02_limite < '".date('Y-m-d',db_getsession("DB_datausu"))."'
                                                     )
                                                  or (saltes.k13_reduz  is not null
                                                      and saltes.k13_limite is not null
                                                      and saltes.k13_limite < '".date('Y-m-d',db_getsession("DB_datausu"))."'
                                                     )
                                                ) then false
                                           else
                                             true
                                           end)");

$result_conta_debitar = $clconplanoexe->sql_record($sql_conta_debitar);
// seleciona conta a creditar
$sqlsaltes = $clsaltes->sql_query_anousu(null,
                                         "k13_conta,
                                         k13_descr",
                                         null,
                                         "c61_instit = ".db_getsession("DB_instit") . "
                                          and k13_limite is null
                                           or k13_limite > '".date("Y-m-d",db_getsession("DB_datausu"))."'");

$result_conta_creditar = $clsaltes->sql_record($sqlsaltes);
$clrotulo = new rotulocampo;
$clrotulo->label("k17_hist");
$clrotulo->label("k17_codigo");
$clrotulo->label("k18_motivo");
//$clrotulo->label("k17_dtanu");
// $clrotulo->label("k17_valor");
$clrotulo->label("c50_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("c58_sequencial");
$clrotulo->label("c58_descr");


?>

<script>
function js_atualiza1(qual){
  if(qual=='debito')
    document.form1.descr_debito.options[document.form1.debito.selectedIndex].selected = true;
  if(qual=='descr_debito')
    document.form1.debito.options[document.form1.descr_debito.selectedIndex].selected = true;
}

function js_atualiza2(qual){
  if(qual=='credito')
    document.form1.descr_credito.options[document.form1.credito.selectedIndex].selected = true;
  if(qual=='descr_credito')
    document.form1.credito.options[document.form1.descr_credito.selectedIndex].selected = true;
}
</script>
<form name="form1" method="post" onsubmit="return js_gravar();">
<input type="hidden" name="chaves" value="">
<center>
<table border="0">
	<tr>
	  <td>&nbsp;</td>
	</tr>
  <tr>
    <td>
      <fieldset>
        <legend>
            <b>Manutenção de Slips</b>
        </legend>
        <table border=0>
          <tr>
            <td align="left">
              <strong>Código do Slip:</strong>  </td>
            <td>
             <?  db_input("k17_codigo", 10, $Ik17_codigo, true, 'text', 3, "", "numslip");  ?>
            </td>
          </tr>
          <tr>
            <td align="left"><strong>
            <? db_ancora('Conta a Debitar (Receber): ',"js_pesquisac01_reduz(true);",2);   ?></strong></td>
            <td nowrap>
            <? db_selectrecord("debito", $result_conta_debitar,
                               true,
                               (isset($read_only) && trim($read_only) != "" ? 3 : 1),
                                "",
                                "",
                                "",
                                " -(Selecione)","js_atuRecursoConta(this);");?>
             </td>
          </tr>
          <tr id="tr_cacp_1" style="display: none;" title="C. Peculiar / C.Aplicação - Débito">
            <td><b><? db_ancora("C.Peculiar / C. Aplicação (Receber)", "js_abrePesquisaDebito(true);", 1); ?></b></td>
            <td>
              <?
                db_input("k17_caracteristicapeculiardebito", 10, $Ic58_sequencial, true, "text", 1, "onchange='js_abrePesquisaDebito(false);'");
                db_input("k17_caracteristicapeculiardebitodesc", 40, $Ic58_descr, true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td align="left"><strong>
              <? db_ancora('Conta a Creditar (Pagar): ',"js_pesquisac01_reduz1(true);",2); ?></strong>
            </td>
            <td nowrap>
              <? db_selectrecord("credito",
                                 $result_conta_creditar,
                                 true,
                                 (isset($read_only) && trim($read_only) != "" ? 3 : 1),
                                 "", "", "", " -(Selecione)");   ?>
            </td>
          </tr>
          <tr id="tr_cacp_2" style="display: none;" title="C. Peculiar / C.Aplicação - Crédito">
            <td><b><? db_ancora("C.Peculiar / C. Aplicação (Pagar)", "js_abrePesquisaCredito(true);", 1); ?></b></td>
            <td>
              <?
                db_input("k17_caracteristicapeculiarcredito", 10, $Ic58_sequencial, true, "text", 1, "onchange='js_abrePesquisaCredito(false);'");
                db_input("k17_caracteristicapeculiarcreditodesc", 40, $Ic58_descr, true, "text", 3);
              ?>
            </td>
          </tr>

          <tr id="trFinalidadeFundeb_Credito" style="display: none;">
            <td><b>Finalidade C. Crédito:</b></td>
            <td>
              <?php
                $oDaoFinalidadeFundeb = db_utils::getDao('finalidadepagamentofundeb');
                $sSqlFinalidadeFundeb = $oDaoFinalidadeFundeb->sql_query_file(null, "e151_codigo, e151_descricao", "e151_codigo");
                $rsBuscaFinalidadeFundeb = $oDaoFinalidadeFundeb->sql_record($sSqlFinalidadeFundeb);
                db_selectrecord('e151_codigo_credito', $rsBuscaFinalidadeFundeb, true, 1);
              ?>
            </td>
          </tr>

          <tr>
            <td align="left">
              <? db_ancora(@$Lk17_hist,"js_pesquisac50_codhist(true);",2);  ?>
            </td>
            <td>
            <?
              db_input('k17_hist',10,$Ik17_hist,true,'text',1," onchange='js_pesquisac50_codhist(false);'");
              db_input('c50_descr',40,$Ic50_descr,true,'text',3);
            ?>
            </td>
          </tr>
          <tr>
            <td align="left">
              <?
                db_ancora("<b>CGM do Favorecido:</b>","js_pesquisaz01_numcgm(true);",2);
              ?>
            </td>
            <td>
               <?
                db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
                db_input('z01_nome',40,$Iz01_nome,true,'text',3);
               ?>
            </td>
          </tr>
          <tr id='tiposdepagamento' style='display: none;'>
            <td>
                <b>Tipo do Pagamento:</b>
            </td>
            <td>
               <?
                 db_select("k17_tipopagamento",
                           getValoresPadroesCampo("k17_tipopagamento"),
                           true,
                           $db_opcao,
                           "onchange='js_mostraTipoBaixa(this.value)'"
                          );
               ?>
            </td>
          </tr>
          <tr>
            <td align="left"><strong>Valor da Transação:</strong></td>
             <td>
                <input type="text" size="10"
        	             name="k17_valor"
        	             id="k17_valor"
        		           value="<?=@trim(db_formatar(($k17_valor),'p'));?>"
        		           onblur="js_atuValor(this.value);"  autocomplete="off"
                       onKeyPress="js_ValidaCampos(this,4,'Valor','','',event); js_atuValor(this.value);"
        		           style="text-align:right;font-weight:bold;background-color:#FFFFFF">

            </td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>Observações:</strong></td>
            <td align="left" valign="top">
              <?
                    if((!isset($texto) || (isset($texto) && trim($texto) == "")) && isset($k17_texto) && trim($k17_texto) != ""){
                          $texto = $k17_texto;
                   }
                   db_textarea("texto", 4, 65, 0, true, 'text', (isset($read_only) && trim($read_only) != "" ? 3 : 1), "onDblClick='document.form1.texto.value=\"\"'");
              ?>
            </td>
          </tr>
          <tr id='recursosmanuais'>
            <td valign="top">
                 <input type=button name=btn value="ADICIONAR RECURSOS" onclick="js_adiciona_linha(true);">
            </td>
              <td>
                 <div id=tbl style="overflow:auto; height:150px;max-height:150px">
                  <table id=tabRecursos width=100% border=0 style="border:2x inset white;background-color:white">
                         <tr style="font-weight:bold">
                              <td class='table_header' width=10%> RECURSO</td>
                              <td class='table_header' width=75%> DESCRIÇÃO </td>
                              <td class='table_header' width=10%> VALOR </td>
                              <td class='table_header' width=5%> CANCELAR </TD>
                         </tr>
                      </table>
                   </div>
              </td>
          </tr>
          <tr id='recursoscorrente' style='display: none'>
            <td valign="top" colspan="3">
              <fieldset>
                <legend><b>Arrecadações Extras não Pagas</legend>
                <div id='correntenaopagas'></div>
                </fieldset>
              </td>
          </tr>
          <tr id="saldoinicialrecurso" style='display: none'>
            <td valign="top" colspan="3">
              <fieldset>
                <legend><b>Controle do Saldo Inicial da Conta por Recurso</b></legend>
                <div id='controlesaldoinicialrecurso'></div>
              </fieldset>
            </td>
          </tr>
      </table>
    </fieldset>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
</table>

<?
if ($desabilitabotao == false) {

  echo "<input type='button'  id='btnemitir' name='confirma' value='Emitir' onclick='js_gravar()'>";
} else {
  echo "<input type='submit'  name='confirma' value='Emitir Slip' disabled >";
}
$db_opcao = isset($db_opcao)?$db_opcao:1;
echo "<input name=\"pesquisar\" type=\"button\" ";
echo "id=\"pesquisar\" value='".($db_opcao==1?"Importar":"Pesquisar")."' onclick=\"js_pesquisa();\">" ;
?>
</center>
</form>
<script>
getItensSlip = false;
lExtra       = false;
function primeiroRecurso() {

  var tab = document.getElementById("tabRecursos");
  if (tab.rows.length > 1 ){
        id_tr = tab.rows[1].id;
        id = id_tr.split('_');
        id  = id[1];
        receita = eval('document.form1.rec_'+id+'.value');
	return receita;
  }
  return false;

}

function js_adiciona_linha(mostra,chave) {

    var sem_rec ='';
    var tab = document.getElementById("tabRecursos");
    var sep = '';
    for (var x=1; x< tab.rows.length;x++){

       recurso = tab.rows[x].id.split('_');
       sem_rec += sep + recurso[1];
	     sep =',';
    }

    if (mostra==true) {
	    if (sem_rec=='') {
        js_OpenJanelaIframe('top.corpo','db_iframe',
                            'func_orctiporec.php?funcao_js=parent.js_mostratiporec|o15_codigo|o15_descr',
                            'Pesquisa Receitas',
                             true);
	    } else {
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe',
                            'func_orctiporec.php?sem_recurso='+sem_rec+
                            '&funcao_js=parent.js_mostratiporec|o15_codigo|o15_descr',
                            'Pesquisa Receitas'
                            );
	    }
    } else {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe','func_orctiporec.php?pesquisa_conta='+chave+
                          '&funcao_js=parent.js_mostratiporec',
                          'Pesquisa Receitas',
                          false);

    }
}


function js_mostratiporec(chave1, chave2){
  db_iframe.hide();
  // se adicionar pelo menos um recurso a mais que o primeiro adicionado, bloqueia a alteração de valores
  if (primeiroRecurso()!=false){
       document.form1.k17_valor.disabled=true;
       document.form1.k17_valor.style.color='#000000';
       document.form1.k17_valor.style.backgroundColor='#DEB887';
  }

  bloqueia_linha=false
  if  (primeiroRecurso()==false){
      bloqueia_linha=true

  }
   // adiciona o recurso ao final da tabela com o campo valor aberto para preenchimento
  var tab = document.getElementById("tabRecursos");

  var row =   tab.insertRow(tab.rows.length)  ;
  row.style.backgroundColor='white';
  row.setAttribute("id","rec_"+chave1);

  var col_A = row.insertCell(0);
  col_A.innerHTML ='<input type="text" name="rec_'+chave1+'" value='+chave1+' size="10" readonly style="background-color:#DEB887">';

  var col_B = row.insertCell(1);
  col_B.innerHTML = chave2;

  var col_C = row.insertCell(2);
  if (bloqueia_linha && !lExtra){
      // o primeiro recurso não pode ter o valor digitado manualmente
      col_C.innerHTML = '<input type="text" id="rec_val_'+chave1+'" name="rec_val_'+chave1+'" readonly  value=\'\' size="12" style=\'text-align:right;\' >';
  } else {
      col_C.innerHTML = '<input type="text" name="rec_val_'+chave1+'" id="rec_val_'+chave1+'"'+
                        'value=\'\' size="12" style=\'text-align:right\' onKeyUp=\"this.value=this.value.replace(\',\',\'.\')\";  onblur=\'js_ajustaValor(this);\'>';
  }
  col_D = row.insertCell(3);
  col_D.setAttribute("align","center");
  col_D.innerHTML = '<input type="button" name="btn_E"  value="E" onclick="js_excluir('+chave1+')";>';

  // tab.appendChild(row);
  // não precisa executar o apend acima porque a linha criada já esta vinculada a tabela.
  isExtra();
}

function js_excluir(linha){
    var tab = document.getElementById("tabRecursos");
    var inicio = 2;
    if (lExtra) {
      inicio = 1;
    }
    for(var x=inicio; x< tab.rows.length;x++){
         // começa na linha 1 para nao permitir excluir a primeiro recurso
         if (tab.rows[x].id  == 'rec_'+linha  ){
               valor = eval('document.form1.rec_val_'+linha+'.value');

	       var cRec = eval('document.form1.rec_val_'+primeiroRecurso()+'.value');

	       cRec = parseFloat(cRec) + parseFloat(valor);

               eval('document.form1.rec_val_'+primeiroRecurso()+'.value = cRec.toFixed(2)');
	       tab.deleteRow(x);
               break;
         }
    }
    // se só ficar a linha do recurso então libera o campo pra alterar o valor do slip
    if ( tab.rows.length < 3  ){
        document.form1.k17_valor.disabled=false;
        document.form1.k17_valor.style.backgroundColor='#FFFFFF';
    }
}
function js_atuValor(valor){
    eval('document.form1.rec_val_'+primeiroRecurso()+'.value=valor');
}
function js_ajustaValor(obj){

    var valor_livre = parseFloat(eval('document.form1.rec_val_'+primeiroRecurso()+'.value'));
    if (valor_livre >= parseFloat(obj.value) ){

           var vRec = eval('document.form1.rec_val_'+primeiroRecurso()+'.value');
           vRec = parseFloat(valor_livre) - parseFloat(obj.value);
	   eval('document.form1.rec_val_'+primeiroRecurso()+'.value=vRec.toFixed(2)');
	        if (!lExtra) {
             obj.disabled='true';
           }  // funciona desabilitando o objeto para edição
           obj.style.color='#000000';
    }else {
    	alert('Valor incorreto :  Recurso Livre: '+valor_livre+', Digitado: '+obj.value);
    	obj.value = 0;
    }

}


function js_atuRecursoConta(obj){
    // primeiro apaga todos os recursos já lançados ( aqui o usuario esta trocando a conta recebedora )
    var tab = document.getElementById("tabRecursos");
    for(var x=1; x< tab.rows.length;x++){
         // começa na linha 1 para nao permitir excluir a primeiro recurso
        tab.deleteRow(x);
    }
    document.form1.k17_valor.value='';
    js_adiciona_linha(false,obj.value);
}


function js_pesquisac50_codhist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conhist.php?funcao_js=parent.js_mostrahist1|c50_codhist|c50_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conhist.php?pesquisa_chave='+document.form1.k17_hist.value+'&funcao_js=parent.js_mostrahist','Pesquisa',false);
  }
}
function js_mostrahist(chave,erro){
  document.form1.c50_descr.value = chave;
  if(erro==true){
    document.form1.k17_hist.focus();
    document.form1.k17_hist.value = '';
  }
}
function js_mostrahist1(chave1,chave2){
  document.form1.k17_hist.value = chave1;
  document.form1.c50_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
    db_iframe.focus();
  }else{
  	if (document.form1.z01_numcgm.value == '') {
 	 document.form1.z01_nome.value = '';
	}
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
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
  db_iframe.hide();
}

function js_pesquisac01_reduz(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conplanoexe_financeiro.php?ver_datalimite=1&funcao_js=parent.js_mostrareduz1|c62_reduz|c60_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conplanoexe_financeiro.php?ver_datalimite=1&pesquisa_chave='+document.form1.c62_reduz.value+'&funcao_js=parent.js_mostrareduz','Pesquisa',false);
  }
}
function js_mostrareduz(chave,erro){
  document.form1.c60_descr.value = chave;
  if(erro==true){
    document.form1.c62_reduz.focus();
    document.form1.c62_reduz.value = '';
  }
}
function js_mostrareduz1(chave1,chave2){
  document.form1.debito.value = chave1;
  document.form1.debito.onchange();
  js_pesquisaDadosContaPlano('debito');
  db_iframe.hide();
}
function js_pesquisac01_reduz1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?ver_datalimite=1&funcao_js=parent.js_mostrareduz2|k13_conta|k13_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?ver_datalimite=1&pesquisa_chave='+document.form1.k13_conta.value+'&funcao_js=parent.js_mostrareduz','Pesquisa',false);
  }
}
function js_mostrareduz2(chav1,chav2){
  document.form1.credito.value = chav1;
  document.form1.credito.onchange();
  js_pesquisaDadosContaPlano('credito');
  db_iframe_saltes.hide();
}

function js_mostraTipoBaixa(iTipoPagamento) {

  switch(iTipoPagamento) {

    case '1':

      $('saldoinicialrecurso').style.display  = '';
      $('recursoscorrente').style.display     = 'none';
      $('recursosmanuais').style.display      = 'none';
      with($('k17_valor')) {

        value                 = oGridSaldoInicialRecurso.sum(4);
        readOnly              = true;
        style.backgroundColor = '#DEB887';

      }

      isSaldoContaRecurso();

      break;

    case '2':

      $('recursoscorrente').style.display     = '';
      $('recursosmanuais').style.display      = 'none';
      $('saldoinicialrecurso').style.display  = 'none';
      with($('k17_valor')) {

        value                 = oGridCorrente.sum(4);
        readOnly              = true;
        style.backgroundColor = '#DEB887';

      }

      break;

    default:

      $('saldoinicialrecurso').style.display  = 'none';
      $('recursoscorrente').style.display     = 'none';
      $('recursosmanuais').style.display      = '';
      with($('k17_valor')) {

        //value                 = 0;
        readOnly              = false;
        style.backgroundColor = '#FFFFFF';

      }
      break;
  }

}

function js_drawGrid() {

   oGridCorrente              = new DBGrid('oGridCorrente');
   oGridCorrente.nameInstance = 'oGridCorrente';
   oGridCorrente.setCheckbox(0);
   oGridCorrente.setCellAlign(new Array("Right", "center", "right", "right"));
   aHeaders                   = new Array('Arrecadação', 'Data', 'Recurso','Valor');
   oGridCorrente.setHeader(aHeaders);
   oGridCorrente.selectSingle = function (oCheckbox, sRow, oRow,lVerificaSaldo) {

   if (oCheckbox.checked) {

     oRow.isSelected    = true;
     $(sRow).className  = 'marcado';

   } else {

     $(sRow).className = oRow.getClassName();
     oRow.isSelected   = false;

    }
     $('total_selecionados').innerHTML = oGridCorrente.getElementsByClass('marcado').length;
     $('k17_valor').value              = oGridCorrente.sum(4);

  }
   oGridCorrente.show($('correntenaopagas'));

}

sUrl = 'cai4_slipRPC.php';
function isExtra() {

  var oParam   = new Object();
  oParam.exec  = "isExtra";
  oParam.conta = $F('debito');
  var oAjax    = new Ajax.Request(
                                  sUrl,
                                  {
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoisExtra
                                  }
                                 ) ;
}

function js_retornoisExtra(oResponse) {

  var oRetorno = eval("("+oResponse.responseText+")");
  lExtra = false;
  if (oRetorno.status == 1) {
    lExtra  = oRetorno.lExtra;
  } else {
    lExtra = false;
  }
  if (lExtra == true) {

    $('tiposdepagamento').style.display = '';
    <?
     if ($db_opcao == 1) {
      echo "js_getPagamentosEmAberto();\n";
    }
    ?>

  } else {

    $('tiposdepagamento').style.display     = 'none';
    $('k17_tipopagamento').value            = 0;
    $('recursoscorrente').style.display     = 'none';
    $('saldoinicialrecurso').style.display  = 'none';
    $('recursosmanuais').style.display      = '';

  }
}

function isSaldoContaRecurso() {

  js_divCarregando("Aguarde, pesquisando saldo de contas","msgBoxSaldoContas");

  var oParam   = new Object();
  oParam.exec  = "isSaldoContaRecurso";
  oParam.conta = $F('debito');
  var oAjax    = new Ajax.Request(
                                  sUrl,
                                  {
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoisSaldoContaRecurso
                                  }
                                 ) ;
}

function js_retornoisSaldoContaRecurso(oResponse) {

  js_removeObj("msgBoxSaldoContas");

  var oRetorno = eval("("+oResponse.responseText+")");

  if (oRetorno.status == 1) {

    if (oRetorno.itens.length > 0) {

      for (i = 0; i < oRetorno.itens.length; i++) {

        with (oRetorno.itens[i]) {

          var aRetorno     = new Array();
              aRetorno[0]  = o15_codigo;
              aRetorno[1]  = o15_descr.urlDecode().substring(0,50);
              aRetorno[2]  = js_formatar(saldoini,"f");
        }
      }

	    if (!$F('numslip')) {

		    var sMensagem  = " Usuário: \n\n";
		        sMensagem += "Está conta tem atualmente um saldo de R$ "+aRetorno[2]+", ";
		        sMensagem += "classificado no recurso "+aRetorno[0]+" - "+aRetorno[1]+". ";
		        sMensagem += "Parte deste valor pode ter origem em outros recursos e você pode fazer esta composição ";
		        sMensagem += "acessando o menu CAIXA > CADASTROS > MANUTENÇÃO DE RECEITAS > ALTERAÇÃO DE RECEITAS, ";
		        sMensagem += "selecionando esta conta e clicando na aba 'Saldos por Recurso', ou continuar ";
		        sMensagem += "esta operação sem fazer nenhuma alteração.\n\n Tem certeza que deseja continuar?";

				    if (!confirm(sMensagem)) {

				      <?
				        if ($db_opcao == 1) {
				         	echo "window.document.location.href='cai1_slip001.php';";
				        } else if ($db_opcao == 2) {
				         	echo "window.document.location.href='cai1_slip002.php';";
				        }
				      ?>

				    } else {
				      js_getSaldoInicialRecurso();
				    }
	    } else {
	      js_getSaldoInicialRecurso();
	    }

    }


  } else {

    var sMensagem  = " Usuário: \n\n";
        sMensagem += "A conta selecionada não possui nenhum recurso com saldo. ";
        sMensagem += "Antes de prosseguir, informe os saldos  dos recursos da conta em: ";
        sMensagem += "CAIXA > CADASTROS > MANUTENÇÃO DE RECEITAS > ALTERAÇÃO DE RECEITAS.";

        alert(sMensagem);

        <?
          if ($db_opcao == 1) {
            echo "window.document.location.href='cai1_slip001.php';";
          } else if ($db_opcao == 2) {
            echo "window.document.location.href='cai1_slip002.php';";
          }
        ?>

  }
}

function js_getPagamentosEmAberto() {

  var oParam   = new Object();
  oParam.exec  = "getPagamentosEmAberto";
  oParam.conta = $F('debito');
  oParam.lApenasSlip = false;
  oParam.k17_codigo = $F('numslip');
  var oAjax    = new Ajax.Request(
                                  sUrl,
                                  {
                                   asynchronous:false,
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornogetPagamentosAberto
                                  }
                                 );
}

function js_retornogetPagamentosAberto(oResponse) {

  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {

    oGridCorrente.clearAll(true);
    if (oRetorno.itens.length > 0) {

      for (i = 0; i < oRetorno.itens.length; i++) {

        with (oRetorno.itens[i]) {

          var aLinha = new Array();
          aLinha[0]  = k12_numpre;
          aLinha[1]  = js_formatar(data,"d");
          aLinha[2]  = k00_recurso;
          aLinha[3]  = js_formatar(valor,'f');
          oGridCorrente.addRow(aLinha);
        }
      }
      oGridCorrente.renderRows();
    }
    $('oGridCorrentestatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
  }
  if ($F('numslip') != "") {

    if (getItensSlip) {
      return false;
    } else {
      js_getArrecadacoesSlip($F('numslip'));
    }
  }
}

function js_getSaldoInicialRecurso() {

  js_divCarregando("Aguarde, pesquisando saldo inicial por recurso","msgBoxSaldoInicialRecurso");

  var oParam         = new Object();
  oParam.exec        = "getSaldoInicialRecurso";
  oParam.conta       = $F('debito');
  oParam.lApenasSlip = false;
  oParam.k17_codigo  = $F('numslip');

  var oAjax    = new Ajax.Request(
                                  sUrl,
                                  {
                                   asynchronous:false,
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornogetSaldoInicialRecurso
                                  }
                                 );
}

function js_retornogetSaldoInicialRecurso(oResponse) {

  js_removeObj("msgBoxSaldoInicialRecurso");

  var oRetorno     = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {

    oGridSaldoInicialRecurso.clearAll(true);
    if (oRetorno.itens.length > 0) {

      for (i = 0; i < oRetorno.itens.length; i++) {

        with (oRetorno.itens[i]) {

          if (k29_recurso != "") {

            var nValorApagar = k29_valor;
            var lMarca       = true;
          } else {

            var nValorApagar = 0;
            var lMarca       = false;
          }

          var aLinha = new Array();
              aLinha[0]  = o15_codigo;
              aLinha[1]  = o15_descr.urlDecode().substring(0,40);
              aLinha[2]  = saldorecurso;
              aLinha[3]  = "<input type='text' id='valorapagar_"+o15_codigo+"' name='valorapagar_"+o15_codigo+"' ";
              aLinha[3] += "        onblur='return js_somarSaldoInicialRecurso("+o15_codigo+", "+i+");' ";
              aLinha[3] += "        onKeyPress=\"return js_mask(event,'0-9|.')\"";
              aLinha[3] += "        value='"+nValorApagar+"' style='text-align: right; width: 100%'>";

          oGridSaldoInicialRecurso.addRow(aLinha, false, false, lMarca);
        }
      }
      oGridSaldoInicialRecurso.renderRows();
    }

    $('k17_valor').value = oGridSaldoInicialRecurso.sum(4, false);
    $('oGridSaldoInicialRecursostatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados_saldo'>0</span> Selecionados";
  }

}

function js_gravar() {

  /**
   * verificamos o tipo de pagamento selecionado pelo usuário
   */
   var iTipo = $F('k17_tipopagamento');

//   if (new Number($F('k17_valor')) <= 0) {
//
//     alert('Informe o valor do slip');
//     return false;
//
//   }
   var oParam                     = new Object();
   oParam.exec                    = "incluirSlip";
   oParam.k17_tipopagamento       = iTipo;
   oParam.k17_codigo              = $F('numslip');
   oParam.aRecursos               = new Array();
   oParam.iCodigoFinalidadeFundeb = $F('e151_codigo_credito');

   switch (iTipo) {

     case '1':

       var aItensSelecionados = oGridSaldoInicialRecurso.getSelection("object");
       if (aItensSelecionados.length == 0) {

         alert('Marque no mínimo um saldo inicial da conta por recurso!');
         return false;

       }

       aItensSelecionados.each(function(oRow, idRow) {

          var oRecurso        = new Object();
          oRecurso.o15_codigo = oRow.aCells[1].getValue();
          oRecurso.o15_valor  = oRow.aCells[4].getValue();
          oParam.aRecursos.push(oRecurso);

       });
       break;

     case '2':

       var aItensSelecionados = oGridCorrente.getSelection("object");
       if (aItensSelecionados.length == 0) {

         alert('Marque no mínimo uma arrecadação!');
         return false;

       }

       oParam.aArrecadacoes   = new Array();
       aItensSelecionados.each(function(oRow, idRow) {

          var oRecurso        = new Object();
          oRecurso.o15_codigo = oRow.aCells[3].getValue();
          oRecurso.o15_valor  = oRow.aCells[4].getValue();
          oParam.aRecursos.push(oRecurso);
          oParam.aArrecadacoes.push(oRow.aCells[1].getValue());


       });
       break;

     default:

      var tab = $('tabRecursos');
      if (tab.rows.length > 1 ) {

        var id_tr    = tab.rows[1].id;
        var id       = id_tr.split('_');
        var iRecurso = id[1];
        var oRecurso        = new Object();
        oRecurso.o15_codigo = iRecurso;
        oRecurso.o15_valor  = $('rec_val_'+iRecurso).value;
        oParam.aRecursos.push(oRecurso);

       break;

     }
   }

   oParam.k17_debito  = $F('debito');
   oParam.k17_credito = $F('credito');
   oParam.k17_numcgm  = $F('z01_numcgm');
   oParam.k17_hist    = $F('k17_hist');
   oParam.k17_valor   = $F('k17_valor');

   oParam.iCPCADebito  = $('k17_caracteristicapeculiardebito').value;
   oParam.iCPCACredito = $('k17_caracteristicapeculiarcredito').value;
   if ($('tr_cacp_1').style.display == '') {
     if (oParam.iCPCADebito == '') {

       alert('Você deve selecionar uma C.Peculiar/Cod. de Aplicação para a conta débito antes de emitir o Slip.');
       return false;
     }
   }

   if ($('tr_cacp_2').style.display == '') {
     if (oParam.iCPCACredito == '') {

       alert('Você deve selecionar uma C.Peculiar/Cod. de Aplicação para a conta crédito antes de emitir o Slip.');
       return false;
     }
   }
   js_divCarregando("Aguarde, incluíndo slip.","msgBox");

   $('btnemitir').disabled = true;
   $('pesquisar').disabled = true;

   oParam.k17_obs = $F('texto').replace(/\n/g,"/n" );
   oParam.k17_obs = encodeURIComponent(oParam.k17_obs.replace(/\"/g, "<aspa>"));

   var oAjax          = new Ajax.Request(
                              sUrl,
                                  {
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoIncluirSlip
                                  }
                                 );
}

function js_retornoIncluirSlip(oAjax) {

  js_removeObj("msgBox");
  $('btnemitir').disabled = false;
  $('pesquisar').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    if (confirm('Slip '+oRetorno.k17_codigo+' foi incluído.\nImprimir Slip?')) {

      window.open('cai1_slip003.php?&numslip='+oRetorno.k17_codigo,'','location=0');
      location.href='cai1_slip001.php';

    } else {
      location.href='cai1_slip001.php';
    }
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_getArrecadacoesSlip(iSlip) {

  if (getItensSlip) {
    return false;
  }
  getItensSlip       = true;
  var oParam         = new Object();
  oParam.exec        = "getPagamentosEmAberto";
  oParam.conta       = $F('debito');
  oParam.lApenasSlip = true;
  oParam.k17_codigo = null;
  if (iSlip != null) {
    oParam.k17_codigo = iSlip;
  }
  var oAjax  = new Ajax.Request(
                              sUrl,
                                  {
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoGetArrecadacoesSlip
                                  }
                                 );

}

function js_retornoGetArrecadacoesSlip(oTransport) {

  var oRetorno = eval("("+oTransport.responseText+")");
  if (oRetorno.status == 1) {

    for (var i = 0 ; i <  oRetorno.itens.length; i++) {

      with (oRetorno.itens[i]) {

        $$('input[type=checkbox]').each(function (input, id) {

          if (input.value == k12_numpre) {
             input.click();
          }
        });
      }
    }
  }
}

function js_drawGridSaldoInicialRecurso() {

   oGridSaldoInicialRecurso              = new DBGrid('oGridSaldoInicialRecurso');
   oGridSaldoInicialRecurso.nameInstance = 'oGridSaldoInicialRecurso';
   oGridSaldoInicialRecurso.setCheckbox(0);
   oGridSaldoInicialRecurso.setCellAlign(new Array("Right", "left", "right", "center"));
   oGridSaldoInicialRecurso.setCellWidth(new Array("20%","40%","26%",'34%'));
   oGridSaldoInicialRecurso.setHeader(new Array('Recurso', 'Descrição', 'Saldo','Valor à Pagar'));
   oGridSaldoInicialRecurso.selectSingle = function (oCheckbox, sRow, oRow) {

   if (oCheckbox.checked) {

     oRow.isSelected    = true;
     $(sRow).className  = 'marcado';

   } else {

     $(sRow).className = oRow.getClassName();
     oRow.isSelected   = false;

    }

     $('total_selecionados_saldo').innerHTML = oGridSaldoInicialRecurso.getElementsByClass('marcado').length;

  }

   oGridSaldoInicialRecurso.show($('controlesaldoinicialrecurso'));

}

function js_somarSaldoInicialRecurso(iRecurso, iLinha) {

   var nSaldo      = new Number(oGridSaldoInicialRecurso.aRows[iLinha].aCells[3].getValue());
   var nValorPagar = new Number($('valorapagar_'+iRecurso).value);

   if (nValorPagar > nSaldo) {
     $('valorapagar_'+iRecurso).value = 0;
     if ($('chk'+iRecurso).checked) {
       $('chk'+iRecurso).click();
     }
     return false;
   }

   $('k17_valor').value = oGridSaldoInicialRecurso.sum(4, false);
   if (nValorPagar > 0) {

     if (!$('chk'+iRecurso).checked) {
       $('chk'+iRecurso).click();
     }
   } else {
     if ($('chk'+iRecurso).checked) {
       $('chk'+iRecurso).click();
     }
   }

}

js_drawGridSaldoInicialRecurso();
js_drawGrid();

$('debito').style.width             = "95px";
$('debitodescr').style.width        = "400px";
$('credito').style.width            = "95px";
$('creditodescr').style.width       = "400px";
$('c50_descr').style.width          = "400px";
$('z01_nome').style.width           = "400px";
$('k17_tipopagamento').style.width  = "100%";

  /**
   * Observa o campo "debito". Quando ele for alterado irá executar uma consulta ajax para
   * verificar se o codsis é igual a 5. Caso seja o campo para seleção de característica peculiar
   * será apresentado na tela.
   */
  $("debito").observe('change',
    function(){
      js_pesquisaDadosContaPlano('debito');
    }
  );
  $("debitodescr").observe('change',
    function(){
      js_pesquisaDadosContaPlano('debito');
    }
  );

  /**
   * Observa o campo "credito". Quando ele for alterado irá executar uma consulta ajax para
   * verificar se o codsis é igual a 5. Caso seja o campo para seleção de característica peculiar
   * será apresentado na tela.
   */
  $("credito").observe('change',
    function(){
      js_pesquisaDadosContaPlano('credito');
      js_pesquisaRecursoConta();
    }
  );
  $("creditodescr").observe('change',
    function(){
      js_pesquisaDadosContaPlano('credito');
      js_pesquisaRecursoConta();
    }
  );


  /**
   * Função que pesquisa os dados da conta plano. Recebe por parâmetro o tipo de conta selecionada
   * e então executa um AJAX para validar se o codsis da conta é 5.
   * Caso seja 5 o campo de Caracteristica Peculiar é habilitado.
   */
  function js_pesquisaDadosContaPlano(sTipoConta) {

    js_divCarregando("Verificando código do sistema...","msgBoxDadosContaPlano");

    var oParam    = new Object();
    oParam.exec   = "getDadosContaPlano";

    if (sTipoConta == 'debito') {
      oParam.iConta = $("debito").value;
    } else if (sTipoConta == 'credito') {
      oParam.iConta = $("credito").value;
    }

    if (oParam.iConta == " ") {

      $('tr_cacp_1').style.display = 'none';
      $('tr_cacp_2').style.display = 'none';
      js_removeObj("msgBoxDadosContaPlano");
      return false;
    }

    var oAjax = new Ajax.Request(
                              "con4_planoContas.RPC.php",
                                  {
                                    method: 'post',
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: function(oAjax) {

                                      var oRetorno = eval("("+oAjax.responseText+")");

                                      if (oRetorno.erro = 2) {

                                        if (sTipoConta == 'debito') {

                                          if (oRetorno.oDados[0].c60_codsis == 5) {
                                            $('tr_cacp_1').style.display = '';
                                          } else {

                                            $('tr_cacp_1').style.display                    = 'none';
                                            $('k17_caracteristicapeculiardebito').value     = '';
                                            $('k17_caracteristicapeculiardebitodesc').value = '';
                                          }
                                        }

                                        if (sTipoConta == 'credito') {

                                          if (oRetorno.oDados[0].c60_codsis == 5) {
                                            $('tr_cacp_2').style.display = '';
                                          } else {

                                            $('tr_cacp_2').style.display                     = 'none';
                                            $('k17_caracteristicapeculiarcredito').value     = '';
                                            $('k17_caracteristicapeculiarcreditodesc').value = '';
                                          }
                                        }

                                      } else {
                                        alert("Não foi possível recuperar os dados da conta!");
                                      }
                                    }
                                  }
                                 );
    js_removeObj("msgBoxDadosContaPlano");
  }


  /**
   * Funções de Pesquisa dos campos concarpeculiar DÉBITO
   */
  function js_abrePesquisaDebito(lMostra) {

    if (lMostra) {
      js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiarDeb','func_concarpeculiar.php?funcao_js=parent.js_preencheDebito|c58_sequencial|c58_descr','Pesquisa',true);
    } else {
      var iSequencialDebito = $('k17_caracteristicapeculiardebito').value;
      js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiarDeb','func_concarpeculiar.php?pesquisa_chave='+iSequencialDebito+'&funcao_js=parent.js_completaDebito','Pesquisa',false);
    }
  }

  function js_preencheDebito(iSequencial, sDescricao) {

    $('k17_caracteristicapeculiardebito').value     = iSequencial;
    $('k17_caracteristicapeculiardebitodesc').value = sDescricao;
    db_iframe_concarpeculiarDeb.hide();
  }

  function js_completaDebito(sDescricao,lErro) {

    if (lErro) {
      $('k17_caracteristicapeculiardebito').value     = '';
      $('k17_caracteristicapeculiardebitodesc').value = sDescricao;
    } else {
      $('k17_caracteristicapeculiardebitodesc').value = sDescricao;
    }
  }


  /**
   * Funções de Pesquisa dos campos concarpeculiar CRÉDITO
   */
  function js_abrePesquisaCredito(lMostra) {

    if (lMostra) {
      js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiarCre','func_concarpeculiar.php?funcao_js=parent.js_preencheCredito|c58_sequencial|c58_descr','Pesquisa',true);
    } else {
      var iSequencialCredito = $('k17_caracteristicapeculiarcredito').value;
      js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiarCre','func_concarpeculiar.php?pesquisa_chave='+iSequencialCredito+'&funcao_js=parent.js_completaCredito','Pesquisa',false);
    }
  }

  function js_preencheCredito(iSequencial, sDescricao) {

    $('k17_caracteristicapeculiarcredito').value     = iSequencial;
    $('k17_caracteristicapeculiarcreditodesc').value = sDescricao;
    db_iframe_concarpeculiarCre.hide();
  }

  function js_completaCredito(sDescricao,lErro) {

    if (lErro) {
      $('k17_caracteristicapeculiarcredito').value     = '';
      $('k17_caracteristicapeculiarcreditodesc').value = sDescricao;
    } else {
      $('k17_caracteristicapeculiarcreditodesc').value = sDescricao;
    }
  }


  function js_pesquisaRecursoConta() {

    js_divCarregando("Aguarde, verificando recurso da conta...", "msgBox");

    var oParam    = new Object();
    oParam.exec   = "verificaRecursoContaReduzida";
    oParam.iConta = $F("credito");

    new Ajax.Request("con4_planoContas.RPC.php",
                    {
                      method: 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      onComplete: function (oAjax) {

                        js_removeObj("msgBox");
                        var oRetorno = eval("("+oAjax.responseText+")");
                        if (oRetorno.lUtilizaFundeb) {

                          $('trFinalidadeFundeb_Credito').style.display = '';
                          $('e151_codigo_credito').style.width             = "95px";
                          $('e151_codigo_creditodescr').style.width        = "400px";

                        } else {
                          $('trFinalidadeFundeb_Credito').style.display = 'none';
                        }

                      }
                    }) ;

  }

js_pesquisaRecursoConta();
//$('e151_codigo_credito').change();
<?
if ($db_opcao == 2) {

   echo "isExtra();\n";
   echo "js_getPagamentosEmAberto();\n";
   echo "js_mostraTipoBaixa('{$k17_tipopagamento}');\n";
   echo "js_getSaldoInicialRecurso();\n";
   echo "js_pesquisaDadosContaPlano('credito');\n";
   echo "js_pesquisaDadosContaPlano('debito');\n";
   echo "js_pesquisaRecursoConta();\n";
   echo "$('e151_codigo_credito').change();\n";
}
?>
</script>