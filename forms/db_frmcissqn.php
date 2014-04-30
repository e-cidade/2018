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
$clcissqn->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_descr");
$clrotulo->label("v03_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
<legend>
  <b>Configuração de Cálculo ISSQN</b>
</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq04_anousu?>">
       <?=@$Lq04_anousu?>
    </td>
    <td> 
			<?
			  db_input('q04_anousu',10,$Iq04_anousu,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_inflat?>">
       <?
       db_ancora(@$Lq04_inflat,"js_pesquisaq04_inflat(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('q04_inflat',10,$Iq04_inflat,true,'text',$db_opcao," onchange='js_pesquisaq04_inflat(false);'");
        db_input('i01_descr',20,$Ii01_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_vbase?>">
       <?=@$Lq04_vbase?>
    </td>
    <td> 
			<?
			  db_input('q04_vbase',10,$Iq04_vbase,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_dtbase?>">
       <?=@$Lq04_dtbase?>
    </td>
    <td> 
			<?
			  db_inputdata('q04_dtbase',@$q04_dtbase_dia,@$q04_dtbase_mes,@$q04_dtbase_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_proced?>">
      <?
        db_ancora(@$Lq04_proced,"js_pesquisaq04_proced(true);",$db_opcao);
      ?>
    </td>
    <td> 
			<?
			  db_input('q04_proced',10,$Iq04_proced,true,'text',$db_opcao," onchange='js_pesquisaq04_proced(false);'");
         db_input('v03_descr',20,$Iv03_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_calfixvar?>">
       <b>Forma de Cálculo :</b> 
    </td>
    <td> 
			<?
			  $x = array('1'=>'Calcula somente variavel','2'=>'Calcula somente fixo','3'=>'Calcula variavel e fixo');
			  db_select('q04_calfixvar',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_diasvcto?>">
       <b>Dia Padrão para Vencimento :</b>
    </td>
    <td> 
			<?
			  db_input('q04_diasvcto',10,$Iq04_diasvcto,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq04_perccorrepadrao?>">
       <?=@$Lq04_perccorrepadrao?>
    </td>
    <td> 
      <?
        db_input('q04_perccorrepadrao', 10, $Iq04_perccorrepadrao, true, 'text', $db_opcao);
      ?>
    </td>
  </tr>
</table>
</fieldset>
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
			<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
			       type="submit" id="db_opcao" 
			       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
			       <?=($db_botao==false?"disabled":"")?> >
			<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
			<?
        if ($db_opcao == 1) {
			?>
			<input name="importar" type="button" id="importar" value="Importar Parâmetros do Exercício Anterior"
			       onclick="js_importar();">
			<?
        }
			?>
    </td>
  </tr>
</table>
</form>
<script>
var sUrlRC = 'iss1_configuracaocalculo.RPC.php';

function js_pesquisaq04_inflat(mostra) {

  if (mostra == true) {
    
    var sUrl = 'func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr';
    js_OpenJanelaIframe('top.corpo','db_iframe_inflan',sUrl,'Pesquisa',true);
  } else {
  
    if (document.form1.q04_inflat.value != '') {
    
      var sUrl = 'func_inflan.php?pesquisa_chave='+document.form1.q04_inflat.value+'&funcao_js=parent.js_mostrainflan';
      js_OpenJanelaIframe('top.corpo','db_iframe_inflan',sUrl,'Pesquisa',false);
    } else {
      document.form1.i01_descr.value = ''; 
    }
  }
}

function js_mostrainflan(chave,erro) {

  document.form1.i01_descr.value = chave; 
  if (erro == true) {
   
    document.form1.q04_inflat.focus(); 
    document.form1.q04_inflat.value = ''; 
  }
}

function js_mostrainflan1(chave1,chave2) {

  document.form1.q04_inflat.value = chave1;
  document.form1.i01_descr.value  = chave2;
  db_iframe_inflan.hide();
}

function js_pesquisaq04_proced(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr';
    js_OpenJanelaIframe('top.corpo','db_iframe_proced',sUrl,'Pesquisa',true);
  } else {
  
    if (document.form1.q04_proced.value != '') {
    
      var sUrl = 'func_proced.php?pesquisa_chave='+document.form1.q04_proced.value+'&funcao_js=parent.js_mostraproced';
      js_OpenJanelaIframe('top.corpo','db_iframe_proced',sUrl,'Pesquisa',false);
    } else {
      document.form1.v03_descr.value = ''; 
    }
  }
}

function js_mostraproced(chave,erro) {

  document.form1.v03_descr.value = chave; 
  if (erro == true) {
   
    document.form1.q04_proced.focus(); 
    document.form1.q04_proced.value = ''; 
  }
}

function js_mostraproced1(chave1,chave2) {

  document.form1.q04_proced.value = chave1;
  document.form1.v03_descr.value  = chave2;
  db_iframe_proced.hide();
}

function js_pesquisa() {

  var sUrl = 'func_cissqn.php?funcao_js=parent.js_preenchepesquisa|q04_anousu';
  js_OpenJanelaIframe('top.corpo','db_iframe_cissqn',sUrl,'Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_cissqn.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_importar() {

  windowwndImportar  = new windowAux('wndImportar', 'Importar Parâmetros do Exercício Anterior', 420, 200);
  var sContent           = '<div style="">';
      sContent          += ' <fieldset>';
      sContent          += ' <table border="0">';
      sContent          += '   <tr>';
      sContent          += '     <td><b>Percentual Correção:</b></td>';
      sContent          += '     <td>';
      sContent          += '       <input type="txt" id="perccorrecao" name="perccorrecao" value="0" size="10">';
      sContent          += '     </td>';
      sContent          += '     <td>%</td>';
      sContent          += '   </tr>';
      sContent          += ' </table>';
      sContent          += ' </fieldset>';
      sContent          += ' <table border="0" align="center" cellpadding="3">';
      sContent          += '   <tr align="center">';
      sContent          += '     <td><input type="button" id="btnProcessar" value="Processar"></td>';
      sContent          += '     <td><input type="button" id="btnCancelar" value="Cancelar"></td>';
      sContent          += '   </tr>';
      sContent          += ' </table>';
      sContent          += '</div>';
  windowwndImportar.setContent(sContent);
  windowwndImportar.allowDrag(true);
  
  oMessageBoard = new DBMessageBoard('msgBoardImportacao',
                                     '',
                                     'Aplicar percentual de correção',
                                     $('windowwndImportar_content')
                                    );   
  oMessageBoard.show();
    
  $('windowwndImportar_btnclose').onclick = function() {
  
    windowwndImportar.destroy();
    $('db_opcao').disabled  = false;
    $('pesquisar').disabled = false;
    $('importar').disabled  = false;
  };
  
  
  if (!$('windowwndImportar')) {
  
    var iWidth  = 150;
    var iHeight = (880)/2;
    
    windowwndImportar.allowCloseWithEsc(false);
    windowwndImportar.show(iWidth, iHeight);
    
    $('db_opcao').disabled  = true;
    $('pesquisar').disabled = true;
    $('importar').disabled  = true;
  }
  
  $('btnCancelar').onclick = function() {
  
    windowwndImportar.destroy();
    $('db_opcao').disabled  = false;
    $('pesquisar').disabled = false;
    $('importar').disabled  = false;
  };
  
  $('btnProcessar').onclick = function() {
  
  
    js_divCarregando('Aguarde, pesquisando...',"msgBoxImportacao");
    
    var oParam         = new Object();
    oParam.exec        = "importarParametrosExercicioAnterior";
    oParam.perc        = $('perccorrecao').value;     
    var oAjax          = new Ajax.Request(sUrlRC,
                                           {
                                             method: "post",
                                             parameters:'json='+Object.toJSON(oParam),
                                             onComplete: function (oAjax) {
                                             
                                               js_removeObj('msgBoxImportacao');
                                             
                                               var oRetorno = eval("("+oAjax.responseText+")");
                                               if (oRetorno.status == 1) {
                                                  
                                                 $('q04_anousu').value          = oRetorno.dados.q04_anousu;
                                                 $('q04_inflat').value          = oRetorno.dados.q04_inflat;
                                                 $('q04_vbase').value           = oRetorno.dados.q04_vbase;
                                                 $('q04_dtbase').value          = js_formatar(oRetorno.dados.q04_dtbase, 'd');
                                                 $('q04_proced').value          = oRetorno.dados.q04_proced;
                                                 $('q04_calfixvar').value       = oRetorno.dados.q04_calfixvar;
                                                 $('q04_diasvcto').value        = oRetorno.dados.q04_diasvcto;
                                                 
                                                 var iPercCorr                  = js_formatar(oRetorno.dados.q04_perccorrepadrao, 'f'); 
                                                 $('q04_perccorrepadrao').value = js_strToFloat(iPercCorr);
                                               } else {
                                                 alert(oRetorno.message.urlDecode());
                                               }
                                                  
                                               if (windowwndImportar) {
                                                  
                                                 windowwndImportar.destroy();
                                                 $('db_opcao').disabled  = false;
                                                 $('pesquisar').disabled = false;
                                                 $('importar').disabled  = false;
                                               }
                                             }
                                           });
  };
}
</script>