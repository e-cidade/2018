<?php
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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o56_elemento");
$clrotulo->label("e69_numero");
$clrotulo->label("e11_cfop");
$clrotulo->label("e10_cfop");
$clrotulo->label("e11_seriefiscal");
$clrotulo->label("e11_inscricaosubstitutofiscal");
$clrotulo->label("e11_valoricmssubstitutotrib");
$clrotulo->label("e11_basecalculoicmssubstitutotrib");
$clrotulo->label("e11_basecalculoicms");
$clrotulo->label("e11_valoricms");
$clrotulo->label("e12_descricao");
$clrotulo->label("e10_descricao");
$db_opcao = 1;
$db_data  = date("d/m/Y");
$clorctiporec->rotulo->label();
$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();
$clpagordemnota->rotulo->label();
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();
$cltabrec->rotulo->label();
if ($tela_estorno){

   $operacao  = 2;//operacao a ser realizada:1 = liquidacao, 2 estorno
   $labelVal  = "SALDO A ESTORNAR";
   $metodo    = "estornarLiquidacaoAJAX";

}else{

   $operacao  = 1;//operacao a ser realizada:1 = liquidacao, 2 estorno
   $labelVal  = "SALDO A LIQUIDAR";
   $metodo    = "liquidarAjax";
}
$db_opcao_inf = 1;
$aParamKeys   = array(
                    "cc09_anousu" => db_getsession("DB_anousu"),
                    "cc09_instit" => db_getsession("DB_instit"),
                   );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 
$iControlaPit        = 0;
 
if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}

$aParamKeys = array(
                    db_getsession("DB_instit")
                   );
$aParametrosPit   = db_stdClass::getParametro("matparaminstit",$aParamKeys);
if (count($aParametrosPit) > 0) {
  $iControlaPit = $aParametrosPit[0]->m10_controlapit;
}  

$lUsaPCASP = "false";
if (USE_PCASP) {
  $lUsaPCASP = "true";
}

?>
<center>
<form name=form1 action="" method="POST">
<table width='80%' cellspacing='0' style='padding:0px' border='0'>
<tr><td  style='padding:0px' valign="top">
 <fieldset><legend><b>&nbsp;Empenho&nbsp;</b></legend>
    <table >
          <tr>
            <td><?=db_ancora($Le60_codemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', 3)?> </td>
            <td width='20'><?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3)?> </td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_numcgm,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numcgm', 13, $Ie60_numcgm, true, 'text', 3); ?> </td>
            <td colspan=2><? db_input('z01_nome', 52, $Iz01_nome, true, 'text', 3, '');?></td>
          </tr>
          <tr>
            <td><?=db_ancora('<b>Credor:</b>',"js_pesquisae49_numcgm(true)",1)?></td>
            <td><? db_input('e49_numcgm', 13, $Ie60_numcgm, true, 'text', 1,"onchange='js_pesquisae49_numcgm(false)'"); ?> </td>
            <td colspan=2><? db_input('z01_credor', 52, $Iz01_nome, true, 'text', 3, '');?></td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",$db_opcao_inf)?></td>
            <td nowrap ><? db_input('e60_coddot', 13, $Ie60_coddot, true, 'text', 3); ?></td>
            <td width="20"><?=db_ancora($Lo15_codigo,"",3)?></td>
            <td nowrap><? db_input('o15_codigo', 5, $Io15_codigo, true, 'text', 3); db_input('o15_descr', 33, $Io15_descr, true, 'text', 3)?></td>
          </tr>
          <tr id='controlepit' style='display: <?=$iControlaPit==1?"":"none"?>'>
            <td><b>Tipo da Entrada: </b></td>
            <td colspan="4">
            <?
            $oDaoDocumentoFiscais = db_utils::getDao("tipodocumentosfiscal");
            $rsDocs = $oDaoDocumentoFiscais->sql_record($oDaoDocumentoFiscais->sql_query(null, "*", "e12_sequencial"));
            $aItens[0] = "selecione"; 
            for($i = 0; $i < $oDaoDocumentoFiscais->numrows; $i ++) {
              
              $oItens = db_utils::fieldsMemory($rsDocs, $i);
              $aItens [$oItens->e12_sequencial] = $oItens->e12_descricao;
            
            }
            db_select('e69_tipodocumentofiscal', $aItens, true, 1, "onchange=js_abreNotaExtra()");
            ?>
            <a href='#' onclick='js_abreNotaExtra()' style='display: none' 
               id='dadosnotacomplementar'>Outros Dados</a>
           </td>
          </tr>  
          <tr>
            <td nowrap><b>N�mero da Nota:</b></td>
            <td><?db_input('e69_numnota', 13, '', true, 'text', 1); ?></td>
            <td nowrap><b>Data da Nota:</b></td>
            <td><?db_inputData('e69_dtnota', '', '','', true, 'text', 1); ?></td>
            
          </tr>
          
          
        </table>
        </fieldset>
     </td>
     <td valign='top' style='padding:0px'>
    <fieldset ><legend><b>&nbsp;Valores do Empenho&nbsp;</b></legend>
    <table style="width:200px;height:100%" >
          <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>  
          <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><b>Saldo</b></td><td align=right><? db_input('saldodis', 12, 0, true, 'text', 3, '','','','text-align:right')?></td></tr>
       </table>
       </fieldset>
     </td>
     </tr>
     <tr>
     <td colspan='2' style='padding:0px'>
   <fieldset><legend><b>&nbsp;Itens&nbsp;</b></legend>
          <div style='border:2px inset white'> 
        <table  cellspacing=0 cellpadding=0 width='100%'>
          <tr>
            <th class='table_header'>
	          <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>
           	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
            <th class='table_header' width='30%'>Material</th>
            <th class='table_header'>Sequ�ncia</th>
            <th class='table_header'>Valor Unit�rio</th>
            <th class='table_header'>Quantidade</th>
            <th class='table_header'>Valor Total</th>
            <th class='table_header'>Quantidade <br>Entregue</th>
            <th class='table_header'>Valor <br>Entregue</th>
            <?
              if ($iTipoControleCustos > 0) {
               echo "<th class='table_header'>Centro de Custo</th>";
              }
            
            ?>
            <th class='table_header' style='width:18px'>&nbsp;</th>
          </tr>
          <tbody id='dados' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
          </tbody>
          <tfoot>
          <tr>
            <th colspan='7' style='text-align:right' class='table_footer'>Valor Total:</th>
            <th class='table_footer' id='valorTotalItens'>&nbsp;</th>
            <?
              if ($iTipoControleCustos > 1) {
               echo "<th class='table_footer' >&nbsp;</th>";
              }
            
            ?>
            <th class='table_footer' style='width:18px'>&nbsp;</th>
          </tr>
          </tfoot>
        </table></div>
        </fieldset>
        </td>
        </tr>
        <tr>
        <td colspan='2' style='padding:0px'>
         <fieldset><legend><b>&nbsp;Hist�rico&nbsp;</b></legend>
          <table>
        <tr>
          <td>
          <?
          db_textarea('historico',5,200,0,true,'text',1,"")
          ?>
          </td>
        </tr>
        <tr>
          <td colspan='2'>
            
          </td>
        </tr>
    </table>
    </fieldset>
  </table>

    <input name="confirmar"  type="button" id="confirmar"  value="Confirmar" onclick="return js_liquidar('<?=$metodo?>')" disabled>
    <input name="pesquisar"  type="button" id="pesquisar"  value="Pesquisar" onclick="js_pesquisa();" >
    <input name="zeraritens" type="button" id="zeraritens" value="Zerar Itens" onclick="js_zeraItens();" >
    <input name="preenche"   type="button" id="preenche"   value="Preencher Itens" onclick="js_preencheItens();" >
    <input name="retencoes"  type="button" id="retencoes"  disabled value="Reten��es" onclick="js_lancarRetencao();" >
    <input name="iCodMov"    type="hidden" id="e81_codmov" value=""  >
    <input name="iCodOrd"    type="hidden" id="e50_codord" value=""  >
    <input name="iCodNota"   type="hidden" id="e69_codnota" value=""  >
 
</form>
</center>
<div id='divDadosNotaAux' style='display:none; text-align: center;' >
  <table width="100%">
    <tr>
       <td>
         <fieldset>
           <legend>
             <b>Dados Complementares</b>
           </legend>
           <table>
           <tr>
             <td nowrap title="<?=@$Te11_cfop?>">
               <?
               db_ancora("<b>CPOF</b>","js_pesquisae11_cfop(true);",$db_opcao);
               ?>
             </td>
             <td nowrap> 
              <?
              db_input('e11_cfop',10,$Ie11_cfop,true,'hidden',3," onchange='js_pesquisae11_cfop(false);'");
              db_input('e10_cfop',10,$Ie10_cfop,true,'text',$db_opcao," onchange='js_pesquisae11_cfop(false);'");
              db_input('e10_descricao',40,$Ie10_descricao,true,'text',3,'')
               ?>
             </td>
           </tr> 
           <tr>
             <td  nowrap>
                <b>S�rie:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_seriefiscal',10,$Ie11_seriefiscal,true,'text',1,'');
             ?>
             </td>
           </tr>
            <tr>
             <td  nowrap>
                <b>Inscri��o Subst.Fiscal:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_inscricaosubstitutofiscal',10,$Ie11_inscricaosubstitutofiscal,true,'text',1,'');
             ?>
             </td>
           </tr>  
           <tr>
             <td  nowrap>
                <b>Base Calculo ICMS:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_basecalculoicms',10,@$Ie11_basecalculoicms,true,'text',1,'');
             ?>
             </td>
           </tr> 
           <tr>
             <td  nowrap>
                <b>Valor ICMS:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_valoricms',10,$Ie11_valoricms,true,'text',1,'');
             ?>
             </td>
           </tr>
           <tr>
             <td  nowrap>
                <b>Base Calculo ICMS Substituto:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_basecalculosubstitutotrib',10,@$Ie11_basecalculosubstitutotrib,true,'text',1,'');
             ?>
             </td>
           </tr> 
           <tr>
             <td  nowrap>
                <b>Valor ICMS Substituto:</b>
             </td>
             <td  nowrap>
             <? 
             db_input('e11_valoricmssubstitutotrib',10,$Ie11_valoricmssubstitutotrib,true,'text',1,'');
             ?>
             </td>
           </tr>
         </table>
        </fieldset>
       </td>
    </tr>
    <tr>
      <td colspan="4" style='text-align: center'>
        <input type='button' value='Salvar Informa��es' onclick='windowAuxiliarNota.hide()'>
      </td>
    </tr>
  </table>
</div>
<script>
iTipoControle = <?=$iTipoControleCustos;?>;
iControlaPit  = <?=$iControlaPit?>;

var lUsaPCASP = <?php echo $lUsaPCASP;?>;


function js_emitir(codordem){
  jan = window.open('emp2_emitenotaliq002.php?codordem='+codordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}  
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  js_consultaEmpenho(chave,<?=$operacao?>);
}
iEmpenho = null;
function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkmarca');
	 for (var i = 0;i < itens.length;i++){
     if (itens[i].disabled == false){
        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
     }
	 }
}

function js_pesquisae11_cfop(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_cfop', 
                        'func_cfop.php?funcao_js=parent.js_mostracfop1|e10_sequencial|e10_descricao|e10_cfop',
                        'Pesquisa CFOP',true);
  }else{
     if($('e10_cfop').value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_cfop',
                            'func_cfop.php?pesquisa_chave='+$('e10_cfop').value+'&funcao_js=parent.js_mostracfop',
                            'Pesquisa CFOP',false);
     }else{
       $('e10_descricao').value = ''; 
     }
  }
}
function js_mostracfop(chave,chave2, erro){

  $('e10_descricao').value = chave; 
  $('e11_cfop').value      = chave2; 
  if(erro==true){ 
    $('e10_cfop').focus(); 
    $('e10_cfop').value = ''; 
  }
}
function js_mostracfop1(chave1,chave2, chave3){

  $('e11_cfop').value = chave1;
  $('e10_descricao').value = chave2;
  $('e10_cfop').value = chave3;
  db_iframe_cfop.hide();
  
}

function js_consultaEmpenho(iEmpenho,operacao){

   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getEmpenhos","pars":"'+iEmpenho+'","operacao":"1","itens":"1","iEmpenho":"'+iEmpenho+'"}';
   $('dados').innerHTML    = '';
   $('e69_tipodocumentofiscal').value       = 0;
   $('e11_cfop').value                      = "";
   $('e11_valoricms').value                 = "";
   $('e11_valoricmssubstitutotrib').value   = "";
   $('e11_basecalculosubstitutotrib').value = "";
   $('e11_basecalculoicms').value           = "";
   $('e11_basecalculoicms').value           = "";
   $('e11_inscricaosubstitutofiscal').value = "";
   $('e11_cfop').value                      = "";
   //$('pesquisar').disabled = true;
   url     = 'emp4_liquidacao004.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_saida
                              }
                             );

}

function js_saida(oAjax){

    obj  = eval("("+oAjax.responseText+")");

    if (lUsaPCASP == true || lUsaPCASP == 'true') {

      if (obj.oGrupoElemento.iGrupo != "") {
      
        switch (obj.oGrupoElemento.iGrupo) {
    
          case  "7":
          case  "8":
          case  "9":
          case "10":
    
            var sGrupo = obj.oGrupoElemento.sGrupo.urlDecode();
            var sMensagem  = "O desdobramento deste empenho est� no grupo " + sGrupo;
                sMensagem += "\nPara desdobramentos deste grupo n�o � possivel liquidar atrav�s desta rotina.";
            js_removeObj("msgBox");
            alert(sMensagem);
            js_pesquisa();   
            return false;
               
          break;
        }
  
      }
    }
     
    $('e60_codemp').value  = obj.e60_codemp.urlDecode()+"/"+obj.e60_anousu.urlDecode();
    $('e60_numemp').value  = obj.e60_numemp.urlDecode();
    $('e60_numcgm').value  = obj.e60_numcgm.urlDecode();
    $('z01_nome').value    = obj.z01_nome.urlDecode();
    $('e49_numcgm').value  = '';	
    $('z01_credor').value  = '';	
    $('e60_coddot').value  = obj.e60_coddot.urlDecode();
    $('o15_codigo').value  = obj.o58_codigo;
    $('o15_descr').value   = obj.o15_descr.urlDecode();
    $('e60_vlremp').value  = obj.e60_vlremp;
    $('e60_vlranu').value  = obj.e60_vlranu;
    $('e60_vlrpag').value  = obj.e60_vlrpag;
    $('e60_vlrliq').value  = obj.e60_vlrliq;
    $('historico').value   = obj.e60_resumo.urlDecode();
    $('saldodis').value    = obj.saldo_dis;
    $('e69_numnota').value = 'S/N';
    $('e69_dtnota').value  = '<?=$db_data;?>';	
    saida = '';
    $('dados').innerHTML   = '';
    iTotItens = 0;
    //alert(obj.numnotas);
    if (obj.numnotas > 0){
    
      for (i = 0; i < obj.data.length;i++){
        
    	  var sDisabilitaQuantidade  = '';
    	  var sDesabilitaValor       = '';
         descrmater = obj.data[i].pc01_descrmater.replace(/\+/g," ");
         descrmater = unescape(descrmater);
         sClassName = 'normal';
         if (obj.data[i].libera.trim() == "disabled") {
           sClassName = 'disabled';
         } else {
           iTotItens++;
         }
         if (obj.data[i].pc01_fraciona == 'f') {
           var lFraciona = false;
         }else{
           var lFraciona = true;
         }
         sDisabilitaValor  = '';
         if (obj.data[i].pc01_servico == 't' && obj.data[i].servicoquantidade == "f") {
             sDisabilitaQuantidade  = 'disabled';
         }
         if (obj.data[i].pc01_servico == 't'  && obj.data[i].servicoquantidade == "t") {
             sDesabilitaValor  = 'disabled';
         }
         
         saida += "<tr class='"+sClassName+"' id='trchk"+obj.data[i].e62_sequen+"' style='height:1em'>";
         saida += "  <td class='linhagrid' style='text-align:center'>";
         saida += "    <input type='checkbox' "+obj.data[i].libera+" onclick='js_marcaLinha(this)'";
         saida += "           class='chkmarca' name='chk"+obj.data[i].e62_sequen+"'";
         saida += "           id='chk"+obj.data[i].e62_sequen+"' value='"+obj.data[i].e62_sequen+"'>";
         saida += "  </td>";
         saida += "  <td class='linhagrid' id='descr"+obj.data[i].e62_sequen+"' style='text-align:left'>";
         saida +=     descrmater;
         saida += "  </td>";
         saida += "  <td class='linhagrid' style='text-align:right'>";
         saida +=      obj.data[i].e62_sequen;
         saida += "  </td>";
         saida += "  <td class='linhagrid' id='vlruni"+obj.data[i].e62_sequen+"' style='text-align:right'>";
         saida +=     obj.data[i].e62_vlrun;
         saida += "  </td>";
         saida += "  <td class='linhagrid' id='saldo"+obj.data[i].e62_sequen+"' style='text-align:right'>";
         saida +=     obj.data[i].saldo;
         saida += "  </td>";
         saida += "  <td class='linhagrid' id='saldovlr"+obj.data[i].e62_sequen+"' style='text-align:right'>"
         saida +=      obj.data[i].e62_vlrtot;
         saida += "  </td>";
         saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
         saida += "    <input type='text' name='qtdesol"+obj.data[i].e62_sequen+"'";
         saida += "           id='qtdesol"+obj.data[i].e62_sequen+"' "+sDisabilitaQuantidade;
         saida += "           value='"+obj.data[i].saldo+"' style='text-align:right'";
         saida += "           size='5' onkeypress='return js_validaFracionamento(event,"+lFraciona+",this)'";
         saida += "           onblur='js_calculaValor("+obj.data[i].e62_sequen+",1)' "+obj.data[i].libera+">";
         saida += "  </td>";
         saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
         saida += "    <input type='text' style='text-align:right' name='vlrtot"+obj.data[i].e62_sequen+"'";
         saida += "           id='vlrtot"+obj.data[i].e62_sequen+"'  value='"+obj.data[i].e62_vlrtot+"' "+sDesabilitaValor;
         saida += "           size='5' class='valores' onkeypress='return js_teclas(event)'";
         saida += "          onblur='js_calculaValor("+obj.data[i].e62_sequen+",2)' "+obj.data[i].libera+">"
         saida += "  </td>";
         if (iTipoControle > 0) {
         
           saida += "  <td class='linhagrid' id='custo"+obj.data[i].e62_sequen+"' style='text-align:left'>";
           saida += "  <span id='cc08_sequencial"+obj.data[i].e62_sequen+"'></span>";
           saida += "  <a id='cc08_descricao"+obj.data[i].e62_sequen+"' href='#' ";
           saida += "     onclick='js_adicionaCentroCusto("+obj.data[i].e62_sequen+","+obj.data[i].e62_sequencial+");";
           saida += " return false'>Escolher</a>";
           saida += "  </td>";
           
         }
         saida += "</tr>";
      }
    }
    saida += "<tr style='height:auto'><td>&nbsp;</td></tr>";
    $('dados').innerHTML          = saida;
    js_removeObj("msgBox");
    $('pesquisar').disabled = false;
    if (iTotItens == 0 && iEmpenho != obj.e60_numemp ) {
    
      alert("Todos os Itens j� foram liquidados, ou est�o em ordem de compra.");
      $('confirmar').disabled = true;
      
    } else {
    
      $('confirmar').disabled = false;
      js_setValorTotal();
    }
    if (iEmpenho != obj.empenho) {
     
      $('retencoes').disabled = true;
      $('e81_codmov').value   = "";
      $('e69_codnota').value  = "";
      $('e50_codord').value   = "";
      
    }  
    iEmpenho  = obj.e60_numemp;
}

function js_marcaLinha(obj){
 
  if (obj.checked){
    $('tr'+obj.id).className='marcado';
  }else{
   $('tr'+obj.id).className='normal';
  }
  js_setValorTotal();
}

function js_liquidar(metodo) {
   
   itens = js_getElementbyClass(form1,'chkmarca');
   notas = '';
   sV    = '';
   if ($F('e69_numnota') == ''){

      alert('Preencha o n�mero da nota.');
      $('e69_numnota').focus();
      return false;
   }

   if ($F('e69_dtnota') == ''){
      
      alert('Preencha a data da nota.');
      $('e69_dtnota').focus();
      return false;
   }
   $('pesquisar').disabled = true;
   $('confirmar').disabled = true;
   valorTotal = 0;
   var aNotas = new Array();
   for (var i = 0;i < itens.length;i++){
     if (itens[i].checked == true){
       
       if (js_strToFloat($("saldovlr"+itens[i].value).innerHTML) < $F('vlrtot'+itens[i].value)) {
         
         sMsgErro  = 'Item '+itens[i].value+'('+$("descr"+itens[i].value).innerHTML.trim()+')';
         sMsgErro += 'com valor total maior que o saldo disponivel.\nVerifique';
         alert(sMsgErro);
         $('pesquisar').disabled = false;
         $('confirmar').disabled = false;
         return false;  
       }
       
       if (js_strToFloat($("saldo"+itens[i].value).innerHTML) < $F('qtdesol'+itens[i].value)) {
         
         sMsgErro  = 'Item '+itens[i].value+'('+$("descr"+itens[i].value).innerHTML.trim()+')';
         sMsgErro += 'com valor total maior que o saldo.\nVerifique';
         alert(sMsgErro);
         $('pesquisar').disabled = false;
         $('confirmar').disabled = false;
         return false;  
       }
       
       if ($F('qtdesol'+itens[i].value) <= 0  || $F('vlrtot'+itens[i].value) <=0 ) {
         
         alert("Valor do item "+itens[i].value+"("+$('descr'+itens[i].value).innerHTML.trim()+") inv�lido.");
         $('pesquisar').disabled = false;
         $('confirmar').disabled = false;
         return false;
         
       }
       var iCodigoCriterioCusto = "";
       /*
        * controlamos se deve ser solicitado o centro de custo para o item.
        * iTipoControle = 2 Uso Obrigatorio.
        *                 1 uso nao obrigatorio
        *                 0 Nao usa 
        */ 
       if (iTipoControle  == 2  ) {
       
         if ($('cc08_sequencial'+itens[i].value).innerHTML.trim() == "") {
             
           alert("Item "+itens[i].value+"("+$('descr'+itens[i].value).innerHTML.trim()+") sem centro de custo Informado");
           $('pesquisar').disabled = false;
           $('confirmar').disabled = false;
           return false;
            
          }
          iCodigoCriterioCusto = $('cc08_sequencial'+itens[i].value).innerHTML.trim();
       } else if (iTipoControle == 1) {
          iCodigoCriterioCusto = $('cc08_sequencial'+itens[i].value).innerHTML.trim();
       }

       /*
       
       JSON escrito manualmente. Alterado para objeto Javascript e ent�o passado para o RPC com o Object.toJSON()
       
       notas += sV+'{"sequen":"'+itens[i].value+'","quantidade":"'+$F('qtdesol'+itens[i].value)+'","vlrtot":"';
       notas += $F('vlrtot'+itens[i].value)+'","vlruni":"'+$('vlruni'+itens[i].value).innerHTML+'",';
       notas += '"iCodigoCriterioCusto":'+iCodigoCriterioCusto+'}';
       */

       var oDadosNota        = new Object();
       oDadosNota.sequen     = itens[i].value;
       oDadosNota.quantidade = $F('qtdesol'+itens[i].value);
       oDadosNota.vlrtot     = $F('vlrtot'+itens[i].value);
       oDadosNota.vlruni     = $('vlruni'+itens[i].value).innerHTML;
       oDadosNota.iCodigoCriterioCusto = iCodigoCriterioCusto;
       aNotas.push(oDadosNota);
       
       sV         = ",";
       valorTotal += new Number($F('vlrtot'+itens[i].value)); 
       valorTotal = valorTotal.toFixed(2);
     } 
   }
   if (aNotas.length != 0){
     
     
     if (valorTotal > js_strToFloat($F('saldodis'))) {
       
       var sErroMsg  = "Voc� est� tentando liquidar um valor superior ao saldo dispon�vel.\n";
           sErroMsg += "Verifique os dados constantes em cada item da nota fiscal do credor,\n";
           sErroMsg += "pois podem haver diferen�as em quantidades ou mesmo arredondamento no c�lculo do valor total.";
       alert(sErroMsg);  
       $('pesquisar').disabled = false;
       $('confirmar').disabled = false;     
       return false;
       
     }
     var iTipoDocumentoFiscal = $F('e69_tipodocumentofiscal');
     var iCfop                = $F('e11_cfop');
     var iInscrSubstituto     = $F('e11_inscricaosubstitutofiscal');  
     var nBaseCalculoICMS     = $F('e11_basecalculoicms');  
     var nValorICMS           = $F('e11_valoricms');  
     var nBaseCalculoSubst    = $F('e11_basecalculosubstitutotrib');  
     var nValorICMSSubst      = $F('e11_valoricmssubstitutotrib');
     var sSerieFiscal         = $F('e11_seriefiscal');
     
     if (iTipoDocumentoFiscal == 0 && iControlaPit == 1) {
        
        alert('Informe o Tipo da Nota Fiscal');
        $('pesquisar').disabled = false;
        $('confirmar').disabled = false; 
        return false;
        
     }
     
     if (iControlaPit == 1) {
       /**
        * Caso o documento fiscal for do tipo 50, devemos obrigar o usu�rio 
        * a selecionar uma cfop
        */
       if (iTipoDocumentoFiscal == '50') {
           
         if (iCfop == "") {
           
           alert('Campo cfop Deve ser preenchido!');
           js_abreNotaExtra();
           $('pesquisar').disabled = false;
           $('confirmar').disabled = false; 
           return false;
           
         }  
       }
     } else {
     
       /**
        * senao � controlado o pit, tipo do documento fiscal = 4 - Outros
        */
       iTipoDocumentoFiscal = 4;
     }
     
     js_divCarregando("Aguarde, Liquidando Empenho ","msgLiq");

     
     /*
     
     JSON escrito manualmente. Alterado para objeto Javascript e ent�o passado para o RPC com o Object.toJSON()
     
     strJson  = '{"method":"geraOC","e69_nota":"'+$F('e69_numnota');
     strJson += '","e69_dtnota":"'+$F('e69_dtnota')+'","valorTotal":"'+valorTotal+'" ,"iEmpenho":"';
     strJson += $F('e60_numemp')+'","notas":['+notas+'],"historico":"'+encodeURIComponent($F('historico'))+'",';
     strJson += '"oInfoNota":{"iCfop":"'+iCfop+'","iTipoDocumentoFiscal":"'+iTipoDocumentoFiscal+'","iInscrSubstituto":"'+iInscrSubstituto+'",';
     strJson += '"nBaseCalculoICMS":"'+nBaseCalculoICMS+'","nValorICMS":"'+nValorICMS+'","nBaseCalculoSubst":"'+nBaseCalculoSubst+'",';
     strJson += '"nValorICMSSubst":"'+nValorICMSSubst+'","sSerieFiscal":"'+sSerieFiscal+'"},';
     strJson += '"pars":"'+$F('e60_numemp')+'","z01_credor":"'+$F('e49_numcgm')+'"}';
     */

     var oParam        = new Object();
     oParam.method     = "geraOC";
     oParam.e69_nota   = $F('e69_numnota');
     oParam.e69_dtnota = $F('e69_dtnota');
     oParam.valorTotal = valorTotal;
     oParam.iEmpenho   = $F('e60_numemp');
     oParam.notas      = aNotas;
     oParam.historico  = encodeURIComponent($F('historico'));
     oParam.pars       = $F('e60_numemp');
     oParam.z01_credor = $F('e49_numcgm');
     
     var oInfoNota                  = new Object();
     oInfoNota.iCfop                = iCfop;
     oInfoNota.iTipoDocumentoFiscal = iTipoDocumentoFiscal;
     oInfoNota.iInscrSubstituto     = iInscrSubstituto;
     oInfoNota.nBaseCalculoICMS     = nBaseCalculoICMS;
     oInfoNota.nValorICMS           = nValorICMS;
     oInfoNota.nBaseCalculoSubst    = nBaseCalculoSubst;
     oInfoNota.nValorICMSSubst      = nValorICMSSubst;
     oInfoNota.sSerieFiscal         = sSerieFiscal;
     
     oParam.oInfoNota = oInfoNota;

     
     url      = 'emp4_liquidacao004.php';
     oAjax    = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+Object.toJSON(oParam), 
                               onComplete: js_saidaLiquidacao
                              }
                             );
   }else{
          
     alert('Selecione ao menos 1 (uma) nota para liquidar');
     $('pesquisar').disabled = false;
     $('confirmar').disabled = false;

   }
}
function js_saidaLiquidacao(oAjax){
 
    js_removeObj("msgLiq");
    $('pesquisar').disabled = false;
    $('confirmar').disabled = false;
    obj      = eval("("+oAjax.responseText+")");
    mensagem = obj.mensagem.replace(/\+/g," ");
    mensagem = unescape(mensagem);
    if (obj.erro == 2){
       alert(mensagem);
    }
    if (obj.erro ==1){
      
       if (confirm("A Ordem de Pagamento "+obj.e50_codord+" foi gerada.\nDeseja Visualiza-la?")){
            js_emitir(obj.e50_codord);
            iCodigoOrdemPagamento = obj.e50_codord;
       }
       
       js_consultaEmpenho($F('e60_numemp'),<?=$operacao?>);
       $('retencoes').disabled = false;
       $('e81_codmov').value   = obj.iCodMov;
       $('e50_codord').value   = obj.e50_codord;
       $('e69_codnota').value  = obj.iCodNota;
    }
}

function js_calculaValor(id,tipo){

   nVlrUni   = new Number($('vlruni'+id).innerHTML); 
   nQtde     = new Number($F('qtdesol'+id));
   nVlrTotal = new Number($F('vlrtot'+id));
   iSaldo    = new Number($('saldo'+id).innerHTML);
   iSaldovlr = new Number($('saldovlr'+id).innerHTML);
   if (tipo == 1){
      nTotal = (nVlrUni*nQtde); 
      nTotal = new Number(nTotal);
      if ((nQtde <= iSaldo)){
        if (nTotal > 0){
           $('vlrtot'+id).value    = nTotal.toFixed(2);
           $('confirmar').disabled = false;
           if ($('chk'+id).checked == false ){
              $('chk'+id).click();
          } else {
            js_setValorTotal();
          }
        }
      }else{

        alert("Valor total maior que o saldo restante.");
        $('confirmar').disabled = true;
     
      }
   }else if(tipo == 2){
	   $('pesquisar').disabled = false;
     $('confirmar').disabled = false;
     if (iSaldo != 0){
       nTotal = new Number(nVlrTotal/nVlrUni); 
       if ((nVlrTotal <= iSaldovlr)) {
          if (nVlrTotal > 0){
             //$('qtdesol'+id).value = nTotal.toFixed(2);
             $('confirmar').disabled = false;
             if ($('chk'+id).checked == false ){
                 $('chk'+id).click();
            } else {
              js_setValorTotal();
            }
         } else {

          alert("Valor total deve ser maior que zero.");
          $('confirmar').disabled = true;
         }
       }else{

          alert("Valor total maior que o saldo restante.");
          $('confirmar').disabled = true;

       }
     }
   }
}

//zera os itens (valor, e quantidade )do empenho
function js_zeraItens() {
  
  sMsg  = 'Esta rotina ira zerar os valores lan�ados.';
  sMsg += '\nTodas a altera��es ser�o perdidas.';
  if (confirm(sMsg)) {
    
    itens = js_getElementbyClass(form1,'chkmarca');
    for (var iInd = 0; iInd < itens.length; iInd++) {
    
      if (!itens[iInd].disabled) {
     
        $('vlrtot' + itens[iInd].value).value = 0;
        $('qtdesol' + itens[iInd].value).value = 0;
       
      }
    }
    js_setValorTotal();  
  }
}

//preenche os itens (valor, e quantidade )do empenho com o saldo Atual
function js_preencheItens() {
  
  sMsg  = 'Esta rotina ira preencher os valores dos itens com seus saldos atuais.';
  sMsg += '\nTodas a altera��es ser�o perdidas.';
  if (confirm(sMsg)) {
    
    itens = js_getElementbyClass(form1,'chkmarca');
    for (iInd = 0; iInd < itens.length; iInd++) {
      if (!itens[iInd].disabled) {
      
        $('vlrtot' + itens[iInd].value).value = $('saldovlr' + itens[iInd].value).innerHTML.trim();
        $('qtdesol' + itens[iInd].value).value = $('saldo' + itens[iInd].value).innerHTML.trim();
        
      }
    }
    js_setValorTotal();  
  }
}
function js_pesquisae49_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Consulta CGM',true);
  }else{
     if(document.form1.e49_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm',
                            'func_nome.php?pesquisa_chave='+document.form1.e49_numcgm.value
                            +'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_credor.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_credor.value = chave; 
  if(erro==true){ 
    document.form1.e49_numcgm.focus(); 
    document.form1.e49_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e49_numcgm.value = chave1;
  document.form1.z01_credor.value = chave2;
  db_iframe_cgm.hide();
}

function js_setValorTotal() {

  var aItens   = js_getElementbyClass(form1,'chkmarca',"checked==true");
  var nTotal = new Number;
  for (var iInd  = 0; iInd < aItens.length; iInd++) {
    
    if (!aItens[iInd].disabled) {
       nTotal += new Number($('vlrtot' + aItens[iInd].value).value);
    }
  }
  if (nTotal > js_strToFloat($F('saldodis'))) {
    $('valorTotalItens').style.color="#FF0000";
  } else {
    $('valorTotalItens').style.color="#000000";
  }
  $('valorTotalItens').innerHTML = js_formatar(nTotal,'f');
}

function js_lancarRetencao(){
  
   var lSession = "false";
   var iCodOrd  = $F('e50_codord');
   var iCodMov  = $F('e81_codmov');
   var iCodNota = $F('e69_codnota');
   var iNumEmp  = $F('e60_numemp');
   js_OpenJanelaIframe('top.corpo', 'db_iframe_retencao',
                       'emp4_lancaretencoes.php?iNumNota='+iCodNota+
                       '&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+"&lSession="+lSession
                       +'&iCodMov='+iCodMov+'&callback=true',
                       'Lancar Reten��es', true);
     
}


function js_atualizaValorRetencao(iCodMov, nValor, iNota, iCodOrdem) {

   db_iframe_retencao.hide();
   if (nValor > 0) {
     if (confirm("As reten��es lan�adas alteraram o valor l�quido da OP "+iCodOrdem+". Deseja reimprimir?")){
       js_emitir(iCodOrdem);
     }
   }
   
}

function js_adicionaCentroCusto(iLinha, iCodItem) {
 
  var iOrigem  = 1;
  var iEmpenho = $F('e60_numemp');
  var sUrl     = 'iOrigem='+iOrigem+'&iNumEmp='+iEmpenho+'&iCodItem='+iCodItem+'&iCodigoDaLinha='+iLinha;
  js_OpenJanelaIframe('',
                      'db_iframe_centroCusto',
                      'cus4_escolhercentroCusto.php?'+sUrl,
                      'Centro de Custos',
                      true,
                      '25',
                      '1',
                      (document.body.scrollWidth-10),
                      (document.body.scrollHeight-100)
                     );
  
   
}

function js_completaCustos(iCodigo, iCriterio, iDescr) {
  
  $('cc08_sequencial'+iCodigo).innerHTML = iCriterio;
  $('cc08_descricao'+iCodigo).innerHTML  = iDescr;
  db_iframe_centroCusto.hide();

}

function js_abreNotaExtra() {

  if ($F('e69_tipodocumentofiscal') == 50) {
    
    if (!$('wnddadosnota')) { 
      js_createJanelaDadosComplentar();
    }
    
    windowAuxiliarNota.show(100,300);
    $('dadosnotacomplementar').style.display='';
    $('e10_cfop').focus();
  } else {
  
    $('dadosnotacomplementar').style.display='none';
    
    if($('wnddadosnota')){
      $('dadosnotacomplementar').style.display='none';
      windowAuxiliarNota.hide();
    }
    
    /*
    if (windowAuxiliarNota) {
      windowAuxiliarNota.hide();
    }
    */
  }
  
  if($F('e69_tipodocumentofiscal') == 0){
    $('e69_numnota').readOnly         = true;
    $('e69_numnota').style.background = "#DEB887";
    $('e69_numnota').value            = "";
  }else{
    $('e69_numnota').readOnly           = false;
    $('e69_numnota').style.background   = "#FFFFFF"; 
  }
   js_validarNumeroNota()
  
}

function js_createJanelaDadosComplentar() {

  windowAuxiliarNota = new windowAux('wnddadosnota', 'DadosComplementares', 600, 500);
  windowAuxiliarNota.setObjectForContent($('divDadosNotaAux'));
  $('dadosnotacomplementar').style.display='';
   
}
function js_validarNumeroNota() {
  if ($F('e69_tipodocumentofiscal') == 50) {
    $('e69_numnota').value = '';
    $('e69_numnota').observe("keypress", function (event) {
        var lValidar = js_mask(event,"0-9");
        if (!lValidar) {
          event.stopPropagation();
          event.preventDefault();
          return false;
        } else {
          return true;
        }
    });
  } else {
    $('e69_numnota').stopObserving("keypress");
  }
}
</script>