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

//MODULO: orcamento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcparamseqorcparamseqcoluna->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o69_codparamrel");
$clrotulo->label("o115_descricao");

if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
   //  $o116_codseq = "";
   //  $o116_codparamrel = "";
     $o116_orcparamseqcoluna = "";
     $o115_descricao        = "";
     $o116_ordem            = "";
     $o116_periodo          = "0";
     $o116_sequencial       = "";
   }
} 
?>
<fieldset style="width:710px;">
<form name="form1" id='frmColunas' method="post" action="">
<center>
<table border="0">
  <tr>
		<?
		db_input('o116_sequencial',10,$Io116_sequencial,true,'hidden',3,"");
    db_input('o69_origem', 10, $Io116_sequencial, true, 'hidden', 3, "");
		?>
  </tr>
  <tr>
    <td nowrap title="<?=@$To116_codseq?>">
       <?
       db_ancora(@$Lo116_codseq,"js_pesquisao116_codseq(true);",3);
       ?>
    </td>
    <td> 
		<?
		db_input('o116_codseq',10,$Io116_codseq,true,'text',3," onchange='js_pesquisao116_codseq(false);'");
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To116_codparamrel?>">
       <?
       db_ancora(@$Lo116_codparamrel,"js_pesquisao116_codparamrel(true);",3);
       ?>
    </td>
    <td> 
		<?
		db_input('o116_codparamrel',10,$Io116_codparamrel,true,'text',3," onchange='js_pesquisao116_codparamrel(false);'");
		db_input('o42_descrrel',50,$Io69_codparamrel,true,'text',3,'');
		if ($db_opcao == 2) {

		  $ordemoriginal   =  $o116_ordem;
		  $colunaoriginal =  $o116_orcparamseqcoluna;
		  db_input('ordemoriginal',50,'',true,'hidden',3,'');
		  db_input('colunaoriginal',50,'',true,'hidden',3,'');
		}
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To116_orcparamseqcoluna?>">
       <?
       db_ancora(@$Lo116_orcparamseqcoluna,"js_pesquisao116_orcparamseqcoluna(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
		db_input('o116_orcparamseqcoluna',10,$Io116_orcparamseqcoluna,true,'text',$db_opcao," onchange='js_pesquisao116_orcparamseqcoluna(false);'");
		db_input('o115_descricao',50,$Io115_descricao,true,'text',3,'')
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To116_ordem?>">
       <?=@$Lo116_ordem?>
    </td>
    <td> 
		<?
		db_input('o116_ordem',10,$Io116_ordem,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$To116_formula?>">
       <?=@$Lo116_formula?>
    </td>
    <td> 
    <?
    db_textarea('o116_formula', 1, 50, $Io116_formula,true,'text',$db_opcao,"")
    ?>
      <Br>
      <b>ctrl + Espaço mostra lista de variáveis disponíveis</b>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To116_periodo?>">
       <?=@$Lo116_periodo?>
    </td>
    <td>
      <?
       $sMultiple = "multiple";
       $sDisabled = '';
       if ($db_opcao == 3) {
         $sDisabled = ' disabled ';
       }
       echo "<select {$sMultiple} size='6' name='o116_periodo[]' {$sDisabled}>";
       $aPeriodos = $oRelatorio->getPeriodos();
       $aListaPeriodos = array();
       $aListaPeriodos[0] = "Selecione";
       foreach ($aPeriodos as $oPeriodo) {
         
         $sSelected = '';
         if ($oPeriodo->o114_sequencial == @$o116_periodo) {
           $sSelected = "selected";
         }
         echo "<option value='{$oPeriodo->o114_sequencial}' {$sSelected}>{$oPeriodo->o114_descricao}</option>";
         
       }
             
      ?>
      </select>
    </td>
  </tr>
  </tr>
	  <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
	  </td>
  </tr>
</form>
</table>
</fieldset>
	
 <table>
<form>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("o116_sequencial"=>@$o116_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = 
	 $clorcparamseqorcparamseqcoluna->sql_query(null,
	                                                 "*",
																									 "o114_sigla,o116_ordem",
																									 "o116_codparamrel = $o116_codparamrel 
           	                                    and o116_codseq = $o116_codseq");
	 $cliframe_alterar_excluir->campos  ="o116_sequencial,o116_codseq,o116_codparamrel,o115_descricao,o115_anousu,o116_ordem,o114_sigla";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	 
	 // o116_codparamrel, o116_codseq
	 
    ?>
    </td>
   </tr>

 </table>
  </center>
</form>

<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisao116_codseq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codparamrel|o69_codparamrel','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o116_codseq.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o116_codseq.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
     }else{
       document.form1.o69_codparamrel.value = ''; 
     }
  }
}
function js_mostraorcparamseq(chave,erro){
  document.form1.o69_codparamrel.value = chave; 
  if(erro==true){ 
    document.form1.o116_codseq.focus(); 
    document.form1.o116_codseq.value = ''; 
  }
}
function js_mostraorcparamseq1(chave1,chave2){
  document.form1.o116_codseq.value = chave1;
  document.form1.o69_codparamrel.value = chave2;
  db_iframe_orcparamseq.hide();
}
function js_pesquisao116_codseq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codseq|o69_codparamrel','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o116_codseq.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o116_codseq.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
     }else{
       document.form1.o69_codparamrel.value = ''; 
     }
  }
}
function js_mostraorcparamseq(chave,erro){
  document.form1.o69_codparamrel.value = chave; 
  if(erro==true){ 
    document.form1.o116_codseq.focus(); 
    document.form1.o116_codseq.value = ''; 
  }
}
function js_mostraorcparamseq1(chave1,chave2){
  document.form1.o116_codseq.value = chave1;
  document.form1.o69_codparamrel.value = chave2;
  db_iframe_orcparamseq.hide();
}
function js_pesquisao116_codparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codparamrel|o69_codparamrel','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o116_codparamrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o116_codparamrel.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
     }else{
       document.form1.o69_codparamrel.value = ''; 
     }
  }
}
function js_mostraorcparamseq(chave,erro){
  document.form1.o69_codparamrel.value = chave; 
  if(erro==true){ 
    document.form1.o116_codparamrel.focus(); 
    document.form1.o116_codparamrel.value = ''; 
  }
}
function js_mostraorcparamseq1(chave1,chave2){
  document.form1.o116_codparamrel.value = chave1;
  document.form1.o69_codparamrel.value = chave2;
  db_iframe_orcparamseq.hide();
}
function js_pesquisao116_codparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codseq|o69_codparamrel','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o116_codparamrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o116_codparamrel.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
     }else{
       document.form1.o69_codparamrel.value = ''; 
     }
  }
}
function js_mostraorcparamseq(chave,erro){
  document.form1.o69_codparamrel.value = chave; 
  if(erro==true){ 
    document.form1.o116_codparamrel.focus(); 
    document.form1.o116_codparamrel.value = ''; 
  }
}
function js_mostraorcparamseq1(chave1,chave2){
  document.form1.o116_codparamrel.value = chave1;
  document.form1.o69_codparamrel.value = chave2;
  db_iframe_orcparamseq.hide();
}
function js_pesquisao116_orcparamseqcoluna(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseqcoluna','func_orcparamseqcoluna.php?funcao_js=parent.js_mostraorcparamseqcoluna1|o115_sequencial|o115_descricao','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o116_orcparamseqcoluna.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcparamseqorcparamseqcoluna','db_iframe_orcparamseqcoluna','func_orcparamseqcoluna.php?pesquisa_chave='+document.form1.o116_orcparamseqcoluna.value+'&funcao_js=parent.js_mostraorcparamseqcoluna','Pesquisa',false);
     }else{
       document.form1.o115_descricao.value = ''; 
     }
  }
}
function js_mostraorcparamseqcoluna(chave,erro){
  document.form1.o115_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o116_orcparamseqcoluna.focus(); 
    document.form1.o116_orcparamseqcoluna.value = ''; 
  }
}
function js_mostraorcparamseqcoluna1(chave1,chave2){
  document.form1.o116_orcparamseqcoluna.value = chave1;
  document.form1.o115_descricao.value = chave2;
  db_iframe_orcparamseqcoluna.hide();
}


var oContextComplete = new DBContextComplete('teste');
oContextComplete.setElementForContext($('o116_formula'));

oContextComplete.setPrependString('#');
oContextComplete.init();
oParam = {exec:'getVariaveis', iOrigemDados: $F('o69_origem'),
          iCodigoRelatorio:$F('o116_codparamrel'),
          iCodigoLinha:$F('o116_codseq') ,
         };
var oAjax = new Ajax.Request('con4_relatorioslegais.RPC.php',
  {
    method:'post',
    parameters:'json='+Object.toJSON(oParam),
    asynchronous:false,
    onComplete: function(oResponse) {

      var oRetorno = eval('('+oResponse.responseText+')');

      oContextComplete.addGroup('colunas', '@');
      oRetorno.oListaVariaveis.campos_relatorios.each(function(sVariavel) {
        oContextComplete.addOption(sVariavel, sVariavel);
      });

      oRetorno.oListaVariaveis.colunas_linha.each(function(sVariavel) {
        oContextComplete.addOption(sVariavel, sVariavel,'colunas');
      });
    }
  }
);
</script>
