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

require_once("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clminutatemplategeral->rotulo->label();

if(isset($db_opcaoal)){
  $db_opcao = 33;
  $db_botao = false;
} else if(isset($oPost->opcao) && $oPost->opcao == "excluir"){
  $db_opcao = 3;
  $db_botao = true;
} else {  
  $db_opcao = 1;
  $db_botao = true;
  if(isset($oPost->novo) || isset($oPost->excluir) || (isset($oPost->incluir) && !$lErro ) ){
    $l42_db_documentotemplate = "";
    $db82_descricao           = "";
  }
} 
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>
      <b>Cadastro de Modelos Gerais de Minuta</b>
    </legend>
		<table>
		  <tr>
		    <td nowrap title="<?=@$Tl42_db_documentotemplate?>">
		      <b>
		      <?
		        db_ancora("Modelo:","js_pesquisal42_db_documentotemplate(true);",$db_opcao,"");
		      ?>
		      </b>
		    </td>
		    <td> 
					<?
					
					  db_input('l42_db_documentotemplate',10,$Il42_db_documentotemplate,true,'text',$db_opcao,
					           "onChange='js_pesquisal42_db_documentotemplate(false)'");
					  db_input('db82_descricao',45,"",true,'text',3,"");
					  db_input('l42_sequencial',10,"",true,'hidden',3,"");
					  
					?>
		    </td>
		  </tr>
		  </tr>
		    <td colspan="2" align="center">
				  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
				         type="submit" id="db_opcao" 
				         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
				         <?=($db_botao==false?"disabled":"")?>>
				  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
				         <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?>>
		    </td>
		  </tr>
	  </table>
  </fieldset>
	<table>
	  <tr>
	    <td valign="top"  align="center">  
		    <?
					 $chavepri= array("l42_sequencial"=>@$l42_sequencial);
					 
					 $cliframe_alterar_excluir->chavepri      = $chavepri;
					 $cliframe_alterar_excluir->sql           = $clminutatemplategeral->sql_query(null);
					 $cliframe_alterar_excluir->campos        = "l42_db_documentotemplate,db82_descricao";
					 $cliframe_alterar_excluir->legenda       = "Modelos Gerais Lançados";
					 $cliframe_alterar_excluir->iframe_height = "160";
					 $cliframe_alterar_excluir->iframe_width  = "700";
					 $cliframe_alterar_excluir->opcoes        = 3;
					 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		    ?>
	    </td>
	  </tr>
	</table>
</form>
<script>
function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisal42_db_documentotemplate(mostra){
  if (mostra == true) {
  
    var sUrl  = 'func_db_documentotemplate.php?tipo=8';
        sUrl += '&funcao_js=parent.js_mostradb_documentotemplate1|db82_sequencial|db82_descricao';
        
    js_OpenJanelaIframe('', 'db_iframe_db_documentotemplate', sUrl, 'Pesquisa', true);
  } else {
  
     var sUrl  = 'func_db_documentotemplate.php?tipo=8';
         sUrl += '&pesquisa_chave='+document.form1.l42_db_documentotemplate.value;
         sUrl += '&funcao_js=parent.js_mostradb_documentotemplate';
         
     if (document.form1.l42_db_documentotemplate.value != '') { 
       js_OpenJanelaIframe('','db_iframe_db_documentotemplate',sUrl,'Pesquisa',false);
     } else {
       document.form1.db82_descricao.value = ''; 
     }
  }
}

function js_mostradb_documentotemplate(chave,erro) {

  document.form1.db82_descricao.value = chave; 
  if (erro) {
   
    document.form1.l42_db_documentotemplate.focus(); 
    document.form1.l42_db_documentotemplate.value = ''; 
  }
}

function js_mostradb_documentotemplate1(chave1,chave2) {

  document.form1.l42_db_documentotemplate.value = chave1;
  document.form1.db82_descricao.value           = chave2;
  db_iframe_db_documentotemplate.hide();
}
</script>