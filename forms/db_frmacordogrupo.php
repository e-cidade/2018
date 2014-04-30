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

//MODULO: Acordos
$clacordogrupo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac01_descricao");
$clrotulo->label("ac04_sequencial");

if($db_opcao==1){
  $db_action = "aco1_acordogrupo004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
 	$db_action = "aco1_acordogrupo005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
 	$db_action = "aco1_acordogrupo006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<fieldset>
<legend><b>Grupos de Acordos</b></legend>
<table border="0">
  <tr>
    <td title="<?=@$Tac02_sequencial?>">
       <b>Código do Acordo:</b>
    </td>
    <td colspan="3"> 
			<?
			  db_input('ac02_sequencial',15,$Iac02_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tac02_acordonatureza?>">
       <?
         db_ancora(@$Lac02_acordonatureza,"js_pesquisaac02_acordonatureza(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('ac02_acordonatureza',15,$Iac02_acordonatureza,true,'text',$db_opcao,
			           " onchange='js_pesquisaac02_acordonatureza(false);'");
      ?>
    </td>
    <td colspan="2">
      <?
        db_input('ac01_descricao',40,$Iac01_descricao,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tac02_acordotipo?>">
       <?=@$Lac02_acordotipo;?>
    </td>
    <td colspan="3"> 
      <?
        $sSqlAcordoTipo  = $clacordotipo->sql_query(null,"*","ac04_sequencial","");
        $rsSqlAcordoTipo = $clacordotipo->sql_record($sSqlAcordoTipo);
        
        $aAcordoTipo = array();
        $aAcordoTipo[0] = "Selecione ...";
        for ($iInd = 0; $iInd < $clacordotipo->numrows; $iInd++) {
        	
        	$oAcordoTipo = db_utils::fieldsMemory($rsSqlAcordoTipo,$iInd);
        	$aAcordoTipo[$oAcordoTipo->ac04_sequencial] = $oAcordoTipo->ac04_descricao; 
        }
        
        db_select('ac02_acordotipo',$aAcordoTipo,true,$db_opcao," onchange='js_desabilitaselecionar();' style='width:100%;'");
      ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tac02_descricao?>">
       <?=@$Lac02_descricao?>
    </td>
    <td colspan="3"> 
			<?
			  db_input('ac02_descricao',54,$Iac02_descricao,true,'text',$db_opcao);
			?>
    </td>
  </tr>
  <tr>
    <td align="left" title="<?=@$Tac02_datainicial?>">
      <?=@$Lac02_datainicial?>
    </td>
    <td align="left">
      <?
        db_inputdata('ac02_datainicial',@$ac02_datainicial_dia,@$ac02_datainicial_mes,@$ac02_datainicial_ano,true,
                     'text',$db_opcao,"");
      ?>
    </td>
    <td align="right" title="<?=@$Tac02_datafinal?>">
      <?=@$Lac02_datafinal?>
    </td>
    <td align="right">
      <?
        db_inputdata('ac02_datafinal',@$ac02_datafinal_dia,@$ac02_datafinal_mes,@$ac02_datafinal_ano,true,
                     'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" colspan="4">
      <fieldset>
      <legend><b>Observações</b></legend>
	      <table>
	        <tr>
	          <td>
				      <?
				        db_textarea('ac02_obs',10,70,$Iac02_obs,true,'text',$db_opcao,"")
				      ?>
	          </td>
	        </tr>
	      </table>
      </fieldset>
    </td>
  </tr>
  </table>
</fieldset>
<table>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> onclick="return js_validarcampos();" >   
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
             <?=($db_opcao==1?"disabled":($db_opcao==2||$db_opcao==22?"":""))?> >
    </td>
  </tr>
</table>
</form>
<script>
$('ac01_descricao').style.width = '100%';
$('ac02_descricao').style.width = '100%';

function js_desabilitaselecionar() {

  var iAcordoTipo = $('ac02_acordotipo').value;
  if (iAcordoTipo != 0) {
    $('ac02_acordotipo').options[0].disabled = true; 
  }
}
function js_validarcampos() {

  var iAcordoNatureza = $('ac02_acordonatureza').value;
  var iAcordoTipo     = $('ac02_acordotipo').value;
  var sDescricao      = $('ac02_descricao').value;
  var dtInicial       = $('ac02_datainicial').value;
  var dtFinal         = $('ac02_datafinal').value;
  var sObservacao     = $('ac02_obs').value;
  
  if (iAcordoNatureza == '') {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Informe uma Natureza de Acordo!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
  
  if (iAcordoTipo == 0) {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Selecione um Tipo de Acordo!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
  
  if (sDescricao == '') {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Informe uma Descrição!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
  
  if (dtInicial != '' && dtFinal != '') {
  
	  var validaDatas = js_comparadata(dtInicial,dtFinal,'<=');
	  if (validaDatas == false) {
	    
	    var sMsg0 = "Usuario:\n\n";
	    var sMsg1 = " Data Inicial deve ser menor que a data final!\n\n";
	    $('ac02_datainicial').value = '';
	    $('ac02_datainicial').focus();
	    alert(sMsg0+sMsg1);
	    return false;
	  }
  } else {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Informe uma Data Inicial e Data Final!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
  
  if (sObservacao == '') {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Campo Observação deve ser Preenchido!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
}
function js_pesquisaac02_acordonatureza(mostra){
  if(mostra==true){
    var sUrl1 = 'func_acordonatureza.php?funcao_js=parent.js_mostraacordonatureza1|ac01_sequencial|ac01_descricao';
    js_OpenJanelaIframe('top.corpo.iframe_acordogrupo','db_iframe_acordonatureza',sUrl1,'Pesquisa',true,'0');
  }else{
     if($('ac02_acordonatureza').value != ''){ 
        var sUrl2 = 'func_acordonatureza.php?pesquisa_chave='+$('ac02_acordonatureza').value
                                                             +'&funcao_js=parent.js_mostraacordonatureza';
        js_OpenJanelaIframe('top.corpo.iframe_acordogrupo','db_iframe_acordonatureza',sUrl2,'Pesquisa',false,'0');
     }else{
       $('ac01_descricao').value = ''; 
     }
  }
}
function js_mostraacordonatureza(chave1,chave2,erro){
  $('ac01_descricao').value = chave2; 
  if(erro==true){ 
    $('ac02_acordonatureza').focus(); 
    $('ac02_acordonatureza').value = ''; 
    $('ac01_descricao').value      = chave1;
  }
}
function js_mostraacordonatureza1(chave1,chave2) {
  $('ac02_acordonatureza').value = chave1;
  $('ac01_descricao').value      = chave2;
  db_iframe_acordonatureza.hide();
}
function js_pesquisa(){
  var sUrlPesquisa = 'func_acordogrupo.php?funcao_js=parent.js_preenchepesquisa|ac02_sequencial';
  js_OpenJanelaIframe('top.corpo.iframe_acordogrupo','db_iframe_acordogrupo',sUrlPesquisa,'Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_acordogrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>