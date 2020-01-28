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

//MODULO: Habitacao
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clavaliacaopergunta->rotulo->label();
$clrotulo->label("db102_sequencial");

if (isset($oPost->db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if(isset($oPost->opcao) && $oPost->opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao = true;
  
  if (isset($oPost->incluir) || isset($oPost->alterar) && $sqlerro == false) {
    $db_opcao = 2;  	
  } 
  
  if (isset($oPost->novo) || isset($oPost->excluir)) {

    $db103_descricao             = "";
    $db103_obrigatoria           = "";
    $db103_ativo                 = "";
    $db103_ordem                 = "";
    $db103_avaliacaotiporesposta = "";
    $db103_identificador         = "";
  }
} 
?>
<form name="form1" method="post" action="">
<fieldset><legend><b>Pergunta</b></legend>
<table border="0" align="left" width="100%">
	<tr>
		<td nowrap title="<?=@$Tdb103_avaliacaogrupopergunta?>">
		  <b>Código do Grupo:</b>
		</td>
		<td colspan="3"> 
			<?
			  db_input('db103_sequencial',10,$Idb103_sequencial,true,'hidden',3,"");
			  db_input('db103_avaliacaogrupopergunta',10,$Idb103_avaliacaogrupopergunta,true,'text',3,"");
			  db_input('db102_descricao',40,@$Idb102_descricao,true,'text',3,'');
			?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tdb103_descricao?>">
      <?=@$Ldb103_descricao?>
    </td>
		<td colspan="4"> 
			<?
			  db_input('db103_descricao',50,$Idb103_descricao,true,'text',$db_opcao,"")
			?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tdb103_identificador?>">
      <?=@$Ldb103_identificador?>
    </td>
		<td colspan="4"> 
			<?
			  db_input('db103_identificador',62,$Idb103_identificador,true,'text',$db_opcao,"")
			?>
    </td>
	</tr>
  <tr>
    <td nowrap title="<?=@$Tdb103_avaliacaotiporesposta?>">
      <?=@$Ldb103_avaliacaotiporesposta?>
    </td>
    <td width="30%"> 
      <?
        db_input('iTipoRespostaAnt',10,$iTipoRespostaAnt,true,'hidden',3,"");
        $sSqlAvaliacaoTipoResposta  = $clavaliacaotiporesposta->sql_query(null, "*", "db105_sequencial", "");
        $rsSqlAvaliacaoTipoResposta = $clavaliacaotiporesposta->sql_record($sSqlAvaliacaoTipoResposta);
      
        $aAvaliacaoTipoResposta     = array();
        $aAvaliacaoTipoResposta[0]  = "Selecione ...";
        for ($iInd = 0; $iInd < $clavaliacaotiporesposta->numrows; $iInd++) {
          
          $oAvaliacaoTipoResposta = db_utils::fieldsMemory($rsSqlAvaliacaoTipoResposta, $iInd);
          $aAvaliacaoTipoResposta[$oAvaliacaoTipoResposta->db105_sequencial] = $oAvaliacaoTipoResposta->db105_descricao; 
        }
        
        db_select('db103_avaliacaotiporesposta', $aAvaliacaoTipoResposta, true, $db_opcao, " onchange='js_desabilitaselecionar();'");
       ?>
    </td>
    <td nowrap title="<?=@$Tdb103_ordem?>" width="10%">
      <?=@$Ldb103_ordem?>
    </td>
    <td> 
      <?
        db_input('db103_ordem',10,$Idb103_ordem,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
	<tr>
		<td nowrap title="<?=@$Tdb103_obrigatoria?>">
      <?=@$Ldb103_obrigatoria?>
    </td>
		<td width="30%"> 
			<?
			  $x = array("f"=>"NÃO","t"=>"SIM");
			  db_select('db103_obrigatoria',$x,true,$db_opcao,"");
			?>
    </td>
		<td nowrap title="<?=@$Tdb103_ativo?>" width="10%">
      <?=@$Ldb103_ativo?>
    </td>
		<td> 
      <?
        $x = array("t"=>"SIM","f"=>"NÃO");
        db_select('db103_ativo',$x,true,$db_opcao,"");
      ?>
    </td>
	</tr>
</table>
</fieldset>
<table border="0" width="100%">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr align="center">
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" onclick="return js_validarcampos();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?>>

      <? if ($db_opcao != 1) { ?>
	      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();"
	             <?=($db_opcao==1||isset($oPost->db_opcaoal)?"style='visibility:hidden;'":"")?>>
	
	      <input name="adicionarrespostas" type="button" id="adicionarrespostas" value="Adicionar Respostas"
	             onclick="js_adicionarrespostas();"
	             <?=($db_opcao==1||isset($oPost->db_opcaoal)?"style='visibility:hidden;'":"")?>
	             <?=($db_opcao==3?"disabled":"")?>>
      <? } ?>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table>
	<tr>
		<td valign="top" align="center">  
	    <?
	      $sWhere    = "db103_avaliacaogrupopergunta = {$db103_avaliacaogrupopergunta}";
	      $sCampos   = "db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao";
	      $sCampos  .= ", db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem";
			  $chavepri  = array("db103_sequencial"=>@$db103_sequencial);
			  $cliframe_alterar_excluir->chavepri      = $chavepri;
			  $cliframe_alterar_excluir->sql           = $clavaliacaopergunta->sql_query_file(null,'avaliacaopergunta.*','db103_ordem',$sWhere);
			  $cliframe_alterar_excluir->campos        = $sCampos;
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
$('db102_descricao').style.width   = '79%';
$('db103_descricao').style.width   = '100%';

function js_disbledadicionarresp() {

  var iAvaliacaoTipoResposta = $('db103_avaliacaotiporesposta').value;
  var sBotao = $('db_opcao').value;
  
  if ($('adicionarrespostas')) {
    $('adicionarrespostas').disabled = false;
  }
  
  if (iAvaliacaoTipoResposta == 2) {
  
	  if ($('adicionarrespostas')) {
	    $('adicionarrespostas').disabled = true;
	  }
  }
  
  if (sBotao == 'Excluir') {
    
    if ($('adicionarrespostas')) {
      $('adicionarrespostas').disabled = true;
    }
  }
  
}

function js_desabilitaselecionar() {

  var iAvaliacaoTipoResposta  = $('db103_avaliacaotiporesposta').value; 
  if (iAvaliacaoTipoResposta != 0) {
    $('db103_avaliacaotiporesposta').options[0].disabled = true; 
  }
  
  js_disbledadicionarresp();
}

function js_validarcampos() {

  var iAvaliacaoTipoResposta = $('db103_avaliacaotiporesposta').value;
  var iOrdem                 = $('db103_ordem').value;
  var sOpcao                 = $('db_opcao').value;
  
  if (iAvaliacaoTipoResposta == 0) {
  
    var sMsg  = "Usuario:\n\n";
        sMsg += " Informe o Tipo de Resposta!\n\n";
    alert(sMsg);
    return false;
  }
  
  if (iOrdem == '') {
  
    var sMsg  = "Usuario:\n\n";
        sMsg += " Informe a Ordem!\n\n";
    alert(sMsg);
    return false;
  }
  
  if (sOpcao == 'Excluir') {
  
    if (!confirm('Excluir todas as resposta para essa pergunta?')) {
      return false;
    }
  }

  return js_validaCaracteres();
}

function js_cancelar(){

  var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","novo");
      opcao.setAttribute("value","true");
      document.form1.appendChild(opcao);
      document.form1.submit();
}

function js_adicionarrespostas() {
  
  var sUrl = 'hab1_avaliacaoperguntaopcao001.php?db103_sequencial='+$('db103_sequencial').value;
  js_OpenJanelaIframe('','db_iframe_avaliacaotiporesposta',sUrl,'Pesquisa',true,'0');
}

/**
 * Validamos os caracteres do identificador registrado
 * Primeiramente verificamos o caracter inicial, permitindo apenas letras
 * Em seguida, verificamos o que vem a seguir, permitindo letras, numeros e _
 */
function js_validaCaracteres() {

  var sValorInicial     = $F('db103_identificador').substring(0,1);
  var sExpressaoInicial = /[A-Za-z]/;
  var sRegExpInicial    = new RegExp(sExpressaoInicial);
  var lResultadoInicial = sRegExpInicial.test(sValorInicial);

  if (sValorInicial == '') {

    alert('É necessário informar um identificador');
    $('db103_identificador').focus();
    return false;
  } 
  
  if (lResultadoInicial) {

    var sValorCaracteres      = $F('db103_identificador').substring(1);
    var sExpressaoCaracteres  = /^[A-Za-z0-9_]+?$/i;
    var sRegExpCaracteres     = new RegExp(sExpressaoCaracteres);
    var lResultadoCaracteres  = sRegExpCaracteres.test(sValorCaracteres);
    if (!lResultadoCaracteres) {

      alert('São permitidas apenas letras, números e/ou caracter "_" (underline)');
      return false;
    }
  } else {

    alert('É permitido apenas letra no caracter inicial');
    return false;
  }

  return true;
}
</script>