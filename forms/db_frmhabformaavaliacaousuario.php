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

$clhabitformaavaliacao->rotulo->label();
$clhabitformaavaliacaousuario->rotulo->label();
$cldb_usuarios->rotulo->label();

if (isset($oPost->db_opcaoal)) {
	
  $db_opcao=33;
  $db_botao=false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($oPost->opcao) && $oPost->opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao=true;
} else {
	  
  $db_opcao = 1;
  $db_botao=true;
  if (isset($oPost->novo)    || 
      isset($oPost->alterar) || 
      isset($oPost->excluir) || (isset($oPost->incluir) && $sqlerro == false )) {
  	
    $ht08_sequencial = "";
  	$id_usuario      = "";
  	$nome            = "";
  }
}
?>
<form name="form1" method="post" action="">
  <fieldset>
    <table border="0" align="left" width="100%">
		  <tr>
		    <td nowrap title="<?=@$Tht08_sequencial?>">
		      <b>Código:</b>
		    </td>
		    <td> 
		      <?
		        db_input('ht08_sequencial',10,$Iht08_sequencial,true,'text',3,"")
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tht08_habitformaavaliacao?>">
		      <?=@$Lht08_habitformaavaliacao?>
		    </td>
		    <td> 
		      <?
		        db_input('ht08_habitformaavaliacao',10,$Iht08_habitformaavaliacao,true,'text',3,"");
		        db_input('ht07_descricao',50,$Iht07_descricao,true,'text',3,'');
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tid_usuario?>">
		      <?
		        db_ancora(@$Lid_usuario,"js_pesquisaid_usuario(true);",$db_opcao);
		      ?>
		    </td>
		    <td> 
		      <?
		        db_input('id_usuario',10,$Iid_usuario,true,'text',$db_opcao," onchange='js_pesquisaid_usuario(false);'");
		        db_input('nome',50,$Inome,true,'text',3,'');
		      ?>
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
          $chavepri= array("ht08_sequencial"=>@$ht08_sequencial);
          
          $sWhere                    = "ht08_habitformaavaliacao = ".@$ht08_habitformaavaliacao;
          $sSqlFormaAvaliacaoUsuario = $clhabitformaavaliacaousuario->sql_query(null, "*", 
                                                                                "ht08_sequencial", $sWhere);
          
          $cliframe_alterar_excluir->chavepri      = $chavepri;
	        $cliframe_alterar_excluir->sql           = $sSqlFormaAvaliacaoUsuario;
	        $cliframe_alterar_excluir->campos        = "ht08_sequencial,id_usuario,nome";
	        $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	        $cliframe_alterar_excluir->iframe_height = "160";
	        $cliframe_alterar_excluir->iframe_width  = "600";
	        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          
        ?>
      </td>
    </tr>
  </table>
</form>
<script>
function js_validar() {

  var iCodFormaAvaliacao = $('ht08_habitformaavaliacao').value;
  var iCodUsuario        = $('id_usuario').value;
  
  if (iCodFormaAvaliacao == '') {
    
    var sMsg  = "Usuário: \n";
        sMsg += "Informe uma forma de avaliação válida!";
          
    alert(sMsg);
    return false;
  }
  
  if (iCodUsuario == '') {
  
    var sMsg  = "Usuário: \n";
        sMsg += "Informe um código de usuário válido!";
        
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

function js_pesquisaid_usuario(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_db_usuarios.php?funcao_js=parent.js_mostrausuario1|id_usuario|nome';
    js_OpenJanelaIframe('','db_iframe_usuario',sUrl,'Pesquisa',true,'0');
  } else {
    if(document.form1.id_usuario.value != '') {
      
      var id_usuario = document.form1.id_usuario.value; 
      var sUrl         = 'func_db_usuarios.php?pesquisa_chave='+id_usuario+'&funcao_js=parent.js_mostrausuario';
      js_OpenJanelaIframe('','db_iframe_usuario',sUrl,'Pesquisa',false);
    } else {
      document.form1.id_usuario.value = ''; 
    }
  }
}

function js_mostrausuario(chave,erro) {

  document.form1.nome.value = chave; 
  if (erro == true) {
   
    document.form1.id_usuario.focus(); 
    document.form1.id_usuario.value = ''; 
  }
}

function js_mostrausuario1(chave1,chave2) {

  document.form1.id_usuario.value = chave1;
  document.form1.nome.value    = chave2;
  db_iframe_usuario.hide();
}
</script>