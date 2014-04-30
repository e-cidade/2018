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

//MODULO: Habitacao
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clhabittipogrupoprograma->rotulo->label();
$clhabittipogrupoprogramaprocdoc->rotulo->label();
$clprocdoc->rotulo->label();

if (isset($db_opcaoal)) {
	
  $db_opcao=33;
  $db_botao=false;
} else if (isset($opcao) && $opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao=true;
} else {
	  
  $db_opcao = 1;
  $db_botao=true;
  if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false )) {
  	
    $ht09_sequencial  = "";
  	$ht09_procdoc     = "";
  	$p56_descr        = "";
  	$ht09_obs         = "";
  	$ht09_obrigatorio = "";         
  }
}
?>
<form name="form1" method="post" action="">
  <fieldset>
    <table border="0" align="left" width="100%">
      
      
		  <tr>
		    <td nowrap title="<?=@$Tht09_sequencial?>">
		      <b>Código:</b>
		    </td>
		    <td> 
		      <?
		        db_input('ht09_sequencial',10,$Iht09_sequencial,true,'text',3,"")
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht09_habittipogrupoprograma?>">
		      <?=@$Lht09_habittipogrupoprograma?>
		    </td>
		    <td> 
		      <?
		        db_input('ht09_habittipogrupoprograma',10,$Iht09_habittipogrupoprograma,true,'text',3,"");
		        db_input('ht02_descricao',50,$Iht02_descricao,true,'text',3,'');
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht09_procdoc?>">
		      <?
		        db_ancora(@$Lht09_procdoc,"js_pesquisaht09_procdoc(true);",$db_opcao);
		      ?>
		    </td>
		    <td> 
		      <?
		        db_input('ht09_procdoc',10,$Iht09_procdoc,true,'text',$db_opcao," onchange='js_pesquisaht09_procdoc(false);'");
		        db_input('p56_descr',50,$Ip56_descr,true,'text',3,'');
		      ?>
		    </td>
		  </tr>  
		  <tr>
		    <td nowrap colspan="2">
		      <fieldset>
		      <legend><?=@$Lht09_obs?></legend>
		      <table>
		        <tr>
		          <td nowrap title="<?=@$Tht09_obs?>"> 
		            <?
		              db_textarea('ht09_obs',8,78,$Iht09_obs,true,'text',$db_opcao,"");
		            ?>
		          </td>
		        </tr>
		      </table>
		      </fieldset>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap colspan="2">
		      <fieldset class="fieldsetinterno">
		        <table align="left" cellpadding="0" cellspacing="0" border="0" width="100%">
		          <tr>
						    <td nowrap title="<?=@$Tht09_obrigatorio?>" width="10%">
						      <?=@$Lht09_obrigatorio?>
						    </td>
						    <td> 
						      <?
						        $aObrigatorio = array("t"=>"SIM","f"=>"NÃO");
						        db_select('ht09_obrigatorio',$aObrigatorio,true,$db_opcao,"");
						      ?>
						    </td>
		          </tr>
		        </table>
		      </fieldset>
		    </td>
		  </tr>
    </table>
  </fieldset>

	<table align="center">
	  <tr>
	    <td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td>
	      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
	             type="submit" id="db_opcao" onclick="return js_validar();"
	             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
	             <?=($db_botao==false?"disabled":"")?>  >
	    </td>
	    <td>
	      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
	             <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
	    </td>
	  </tr>
	  <tr>
	    <td colspan="2">&nbsp;</td>
	  </tr>
	</table>

  <table>
    <tr>
      <td valign="top"  align="center">  
        <?
          $chavepri= array("ht09_sequencial"=>@$ht09_sequencial);
          
          $sWhere                       = "ht09_habittipogrupoprograma = ".@$ht02_sequencial;
          $sSqlTipoGrupoProgramaProcDoc = $clhabittipogrupoprogramaprocdoc->sql_query(null, "*", 
                                                                                      "ht09_sequencial", $sWhere);
          
          $cliframe_alterar_excluir->chavepri      = $chavepri;
	        $cliframe_alterar_excluir->sql           = $sSqlTipoGrupoProgramaProcDoc;
	        $cliframe_alterar_excluir->campos        = "ht09_sequencial,ht09_procdoc,p56_descr,ht09_obs,ht09_obrigatorio";
	        $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	        $cliframe_alterar_excluir->iframe_height = "160";
	        $cliframe_alterar_excluir->iframe_width  = "610";
	        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          
        ?>
      </td>
    </tr>
  </table>

</form>
<script>
function js_validar() {


  var iCodGrupo = $('ht09_habittipogrupoprograma').value;
  var iCodDoc   = $('ht09_procdoc').value;
  
  if (iCodGrupo == '') {
    
    var sMsg  = "Usuário: \n";
        sMsg += "Informe um tipo de grupo válido!";
          
    alert(sMsg);
    return false;
  }
  
  if (iCodDoc == '') {
  
    var sMsg  = "Usuário: \n";
        sMsg += "Informe um documento válido!";
        
    alert(sMsg);
    return false;
  }
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisaht09_procdoc(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_procdoc.php?funcao_js=parent.js_mostraprocdoc1|p56_coddoc|p56_descr';
    js_OpenJanelaIframe('','db_iframe_procdoc',sUrl,'Pesquisa',true,'0');
  } else {
    if(document.form1.ht09_procdoc.value != '') {
      
      var ht09_procdoc = document.form1.ht09_procdoc.value; 
      var sUrl         = 'func_procdoc.php?pesquisa_chave='+ht09_procdoc+'&funcao_js=parent.js_mostraprocdoc';
      js_OpenJanelaIframe('','db_iframe_procdoc',sUrl,'Pesquisa',false);
    } else {
      document.form1.ht09_procdoc.value = ''; 
    }
  }
}

function js_mostraprocdoc(chave,erro) {

  document.form1.p56_descr.value = chave; 
  if (erro == true) {
   
    document.form1.ht09_procdoc.focus(); 
    document.form1.ht09_procdoc.value = ''; 
  }
}

function js_mostraprocdoc1(chave1,chave2) {

  document.form1.ht09_procdoc.value = chave1;
  document.form1.p56_descr.value    = chave2;
  db_iframe_procdoc.hide();
}
</script>