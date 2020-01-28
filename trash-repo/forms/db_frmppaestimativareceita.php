<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: orcamento
$clppaestimativareceita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o57_codfon");
$clrotulo->label("o57_codfon");
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o57_codfon");
$clrotulo->label("o57_codfon");
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o05_valor");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset>
<legend><B>Receitas</B></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To06_sequencial?>">
       <?=@$Lo06_sequencial?>
    </td>
    <td> 
    <?
    db_input('o06_sequencial',10,$Io06_sequencial,true,'text',3,"")
   ?>
    </td>
  </tr>
  <?
    if ($db_opcao == 1) {
    	
    ?>
    <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                ?>
              </td>
              <td nowrap> 
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <b>Perspectiva:</b>
              </td>
              <td id='verppa'> 
                
              </td>
            </tr>
    <?
    } else {
   ?>
   <tr>
     <td nowrap title="<?=@$To05_ppaversao?>">
      <?
       $db_opcaoversao  = $db_opcao; 
       if ($db_opcao == 2 || $db_opcao == 22) {
        $db_opcaoversao  = 3;   
       }
       db_ancora(@$Lo05_ppaversao,"js_pesquisao05_ppalei(true);",$db_opcaoversao);
       ?>
       </td>
       <td> 
       <?
       
       db_input('o05_ppaversao',10,$Io05_ppaversao,true,'text',$db_opcaoversao," onchange='js_pesquisao05_ppalei(false);'")
       ?>
       <?
       db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'')
       ?>
      </td>
   </tr>
   <?
    }
   ?>
  <tr>
    <td nowrap title="<?=@$To06_codrec?>">
       <?
       db_ancora(@$Lo06_codrec,"js_pesquisao06_codrec(true);",$db_opcao);
       ?>
    </td>
    <td> 
   <?
    db_input('o06_codrec',10,$Io06_codrec,true,'text',$db_opcao," onchange='js_pesquisao06_codrec(false);'");
    db_input('o57_descr',40,$Io57_codfon,true,'text',3,'');
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te06_concarpeculiar?>"><?
       db_ancora(@$Lo06_concarpeculiar,"js_pesquisae54_concarpeculiar(true);",$db_opcao);
    ?></td>
    <td>
    <?
      db_input("o06_concarpeculiar",10,$Io06_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisae54_concarpeculiar(false);'");
      db_input("c58_descr",50,0,true,"text",3);
    ?>
    </td>
  </tr>
  <?
   if (isset($oLei) && $db_opcao == 1 ) {
     echo "<tr><td colspan='3'> <fieldset>";
     echo " <legend><b>Valores</b></legend>";
     echo " <table>";
     for ($i = $oLei->o01_anoinicio; $i <= $oLei->o01_anofinal; $i++) { 
        
       echo "<tr>";
       echo "  <td>";
       echo "     <b>{$i}:</b>";
       echo "  </td>";
       echo "  <td>";
       echo "    <input type='text' class='anovalor' onkeypress='return js_mask(event,\"0-9|.\" )'";
       echo "           name='valor{$i}' onblur='js_calculaValores({$i}, {$oLei->o01_anofinal}, this.value)' ";
       echo "           size='10' id='{$i}'>";
       echo "  </td>";
       echo "</tr>";
       
     }
     echo "</table>";
    echo "</fieldset>";
   } else if ($db_opcao == 2 || $db_opcao == 22 || $db_opcao == 3) {
     
     echo "<tr>";
     echo "  <td>";
     echo "    <b>Valor</b>";
     echo "  </td>";
     echo "  <td>";
     db_input('o05_valor',10,$Io05_valor,true,'text',$db_opcao,"");
     db_input('o05_sequencial',10,"",true,'hidden',3);
     echo "  </td>";
     echo "</tr>";
     
   }
   ?>
   </tr>
  </table>
  </fieldset>
  </table>
  </center>
  <?
  if ($db_opcao == 1) {
    echo "<input name='pesquisar' type='button' id='btncadastrar' value='Cadastrar' onclick='js_cadastrarReceita();'>";
  } else {
?>    
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
 <?
  }
 ?>      
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input type='button' value='ver Parametros' onclick="js_mostraParametros();">
</form>
<script>
sUrlRPC    = 'orc4_ppaRPC.php';
function js_pesquisao06_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_mostraorcfontes1|o57_codfon|o57_descr','Pesquisa',true);
  }else{
     if(document.form1.o06_codrec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes',
                            'func_orcfontes.php?lPesquisaCodigo=true&pesquisa_chave='+document.form1.o06_codrec.value+
                            '&funcao_js=parent.js_mostraorcfontes','Pesquisa',false);
     }else{
       document.form1.o57_descr.value = ''; 
     }
  }
}
function js_mostraorcfontes(chave,erro){
  document.form1.o57_descr.value = chave; 
  if(erro==true){ 
    document.form1.o06_codrec.focus(); 
    document.form1.o06_codrec.value = ''; 
  }
}
function js_mostraorcfontes1(chave1,chave2){
  document.form1.o06_codrec.value = chave1;
  document.form1.o57_descr.value = chave2;
  db_iframe_orcfontes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ppaestimativareceita','func_ppaestimativareceita.php?lEstimativa=true&funcao_js=parent.js_preenchepesquisa|o06_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppaestimativareceita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_ppalei',
                        'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao&verificaano=1',
                        'Pesquisa de Versões para o PPA',
                        true);
  }else{
     if(document.form1.o05_ppalei.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_ppalei',
                            'func_ppalei.php?pesquisa_chave='
                            +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei&verificaano=1',
                            'versao PPA',
                            false);
     }else{
       document.form1.o01_descricao.value = ''; 
     }
  }
}
function js_mostrappalei(chave, erro) {

  document.form1.o01_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o05_ppaversao.focus(); 
    document.form1.o05_ppversao.value = ''; 
  } else {
    document.form1.submit();
  }
  
}
function js_mostrappalei1(chave1,chave2){
  document.form1.o05_ppalei.value    = chave1; 
  document.form1.o05_ppaversao.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
  document.form1.submit();
    
}

function js_pesquisae54_concarpeculiar(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_concarpeculiar',
                        'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr',
                        'Caracteristica Peculiar',true
                        );
  }else{
     if ($F('o06_concarpeculiar') != ''){ 
        js_OpenJanelaIframe('',
                            'db_iframe_concarpeculiar',
                            'func_concarpeculiar.php?pesquisa_chave='+$F('o06_concarpeculiar')+
                            '&funcao_js=parent.js_mostraconcarpeculiar',
                            'Caracteristica Peculiar',
                            false
                           );
     }else{
       document.form1.c58_descr.value = ''; 
     }
  }
}
function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave; 
  if(erro==true) { 
  
    document.form1.o06_concarpeculiar.focus(); 
    document.form1.o06_concarpeculiar.value = ''; 
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.o06_concarpeculiar.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}

function js_calculaValores(iAno, iAnoFinal, nValor) {

  if ($F('o06_codrec') == "") {
  
    alert('antes de informar os valores, informe o elemento');
    return false;
    
  } 
  js_divCarregando("Aguarde, Calculando Valores","msgBox");
  $('btncadastrar').disabled = true;
  var oParam            = new Object();
  oParam.exec           = "calculaValorEstimativa";
  oParam.iCodCon        = $F('o06_codrec');
  oParam.iAno           = iAno;
  oParam.iAnoFinal      = iAnoFinal;
  oParam.nValor         = nValor;
  oParam.iTipo          = 2;
  oParam.iCodigoLei     = '<?=@$oLei->o01_sequencial?>';
  oParam.iCodigoVersao  = $F('o05_ppaversao');
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoCalculo
                          }
                        );
}

function js_retornoCalculo(oAjax) {
  
  js_removeObj("msgBox"); 
  $('btncadastrar').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1){
  
    var aInputsValores = js_getElementbyClass(form1,"anovalor");
    for (var i = 0; i < aInputsValores.length; i++) {
      
      for (var j = 0; j < oRetorno.itens.length; j++) {
        if (oRetorno.itens[j].iAno == aInputsValores[i].id) {
          aInputsValores[i].value = oRetorno.itens[j].nValor;
        }
      }
    } 
  }
}
function js_cadastrarReceita() {

  if ($F('o06_codrec') == "") {
  
    alert('informe a Receita');
    return false;
  }    
  if ($F('o06_concarpeculiar') == "") {
    
    alert('Informe a Caracteristica Peculiar');
    return false;
    
  }
  var oParam               = new Object();
  oParam.exec              = "adicionaEstimativaReceita";
  oParam.iCodCon           = $F('o06_codrec');
  oParam.iTipo             = 1;
  oParam.iCodigoLei        = '<?=@$oLei->o01_sequencial?>';
  oParam.aAnos             = new Array();
  oParam.iConcarPeculiar   = $F('o06_concarpeculiar');
  oParam.iCodigoVersao     = $F('o05_ppaversao');
  /*
   * percorremos os valores cadastrados para o anos da lei, 
   * verificamos quais nao foram prrenchidos.
   * 
   */
   var aInputsValores = js_getElementbyClass(form1,"anovalor");
   var sMsgValores    = ""; 
   var sVirgula       = " ";
   
   for (var i = 0; i < aInputsValores.length; i++) {
    
     var nValor = new Number(aInputsValores[i].value);
     if (nValor == 0 || nValor == "") {

       sMsgValores += sVirgula+aInputsValores[i].id;
       sVirgula    = ", ";
       
     }       
     var aAno    = new Object();
     aAno.iAno   = aInputsValores[i].id;
     aAno.nValor = aInputsValores[i].value;
     oParam.aAnos.push(aAno);
   }
   if (sMsgValores != "") {
     
     var sMSgUsuario  = 'O(s) ano(s) '+sMsgValores+' estão sem valores definidos.\nPara esses anos, não sera cadastrados ';  
     sMSgUsuario     += 'Receitas\nDeseja continuar?';  
     if (!confirm(sMSgUsuario)) {
       return false;
     }
   }
  js_divCarregando("Aguarde, Cadastrando Receitas","msgBox");
  $('btncadastrar').disabled = true;
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoAdicaoReceita
                          }
                        );
  
  
}

function js_retornoAdicaoReceita(oAjax) {
   
  js_removeObj("msgBox"); 
  $('btncadastrar').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    if (!confirm('Cadastro das Estimativas Realizadas com sucesso.\nDeseja incluir novas estimativas para a Receita?')) {
    } else {

      $('o06_codrec').value    = "";
      $('o57_descr').value     = "";
      var aInputsValores = js_getElementbyClass(form1,"anovalor");
      for (var i = 0; i < aInputsValores.length; i++) {
         aInputsValores[i].value = "";    
      }
    }
  } else {
    alert(oRetorno.message.urlDecode()); 
  }
}
function js_mostraParametros() {
   
   var iCodCon = $F('o06_codrec');
   if (iCodCon == "") {
     
     alert('Informe o elemento');
     return;
     
   }
   var iCodigoLei    = '<?=@$oLei->o01_sequencial?>;' 
   var iCodigoVersao = '<?=@$oLei->o119_sequencial?>;' 
   js_OpenJanelaIframe('',
                       'db_iframe_reprocppaestimativa',
                       'orc4_mostraparametrosestimativa.php?o01_sequencial='+iCodigoLei+'&iCodCon='+iCodCon+
                       "&iTipo=1",
                       'Parâmetros de estimativas',
                       true,
                       ((screen.availHeight-700)/2),
                       ((screen.availWidth-500)/2),
                       650,
                       350);
  
}
  js_drawSelectVersaoPPA($('verppa')); 
<?
if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "" && $db_opcao == 1) {
  echo "js_getVersoesPPA({$oPost->o05_ppalei}, 2);\n";
  
}
?>
</script>