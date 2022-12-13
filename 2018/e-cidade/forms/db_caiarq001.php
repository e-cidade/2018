<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$clrotulo = new rotulocampo;
$clrotulo->label('k15_codbco');
$clrotulo->label('k15_codage');
$clrotulo->label('db90_descr');
$clrotulo->label('arqret');
?>
<form name="form1" enctype="multipart/form-data" onsubmit=" return js_verifica()" method="post" action="">
  <fieldset style="margin: 40px auto 10px; width: 700px;">
    <legend>
      <strong>Processa arquivo txt</strong>
    </legend>
    <table align="center">
      <tr>
        <td nowrap><?php db_ancora($Lk15_codbco,' js_banco(true); ',4); ?></td>
        <td>
          <span style="padding-right: 2px;">
            <?php db_input("k15_codbco",10,$Ik15_codbco,true,"text",4,"onchange='js_banco(false);'"); ?> </span>
            <?php db_input("nome_banco",40,"",true,'text',3); ?>
          <input name="tamanho" type="hidden" id="tamanho" /></td>
      </tr>
      <tr>
        <td nowrap><?php db_ancora($Lk15_codage,' js_agencia(true); ',4); ?></td>
				<td>
					<?php db_input('k15_codage', 10, $Ik15_codage, true, 'text', 4, "onblur='js_agencia(false);'");
					      db_input('db90_descr', 40, '', true, 'text', 3);
				  ?>
        </td>
      </tr>
      <tr>
        <td nowrap><?php echo $Larqret; ?></td>
        <td><?php db_input("arqret",29,$Iarqret,true,"file",4) ?></td>
      </tr>
    </table>
  </fieldset>
  <center>
    <input name="processar" type="submit" id="processar" value="Processar">
  </center>
</form>
<script type="text/javascript">

var sMensagens = "tributario.arrecadacao.cai4_baixabanco001.";

function js_verifica(){

  if( $F('k15_codbco') == "" ){

    alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Banco" } ) );
    $('k15_codbco').focus();
    return false;
  }

  if( $F('k15_codage') == "" ){

    alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Agência" } ) );
    $('k15_codage').focus();
    return false;
  }

  if( $F('arqret') == "" ){

    alert( _M( sMensagens + "campo_obrigatorio", { sCampo : "Arquivo de retorno" } ) );
    $('arqret').focus();
    return false;
  }
}

function js_banco(mostra) {

	var iCodigoBanco = $F('k15_codbco');

	$('k15_codage').value = '';
	$('db90_descr').value = '';

	if ( mostra == true ) {
		js_OpenJanelaIframe('','db_iframe_cadban','func_cadban.php?lPesquisaBanco=true&funcao_js=parent.js_mostrabanco|0|1','Pesquisa',true);
	} else {
		js_OpenJanelaIframe('','db_iframe_cadban','func_cadban.php?pesquisa_chave=' + iCodigoBanco + '&lPesquisaBanco=true&funcao_js=parent.js_mostrabanco1','Pesquisa',false);
	}
}

function js_mostrabanco( chave1, chave2 ) {

  $('k15_codbco').value = chave1;
  $('nome_banco').value = chave2;
  db_iframe_cadban.hide();
}

function js_mostrabanco1( chave, erro ) {

  $('nome_banco').value = chave;
  if ( erro == true ) {

    $('k15_codbco').focus();
    $('k15_codbco').value = '';
  }
}

function js_agencia(mostra) {

  var iCodigoAgencia = $F('k15_codage');
	var iCodigoBanco   = $F('k15_codbco');

	if ( iCodigoBanco == '') {

		alert( _M( sMensagens + "selecione_banco" ) );
    $('k15_codage').value = '';
	  $('k15_codbco').value = '';
		return false;
	}

  if ( mostra == true ) {
    js_OpenJanelaIframe('', 'db_iframe_cadban', 'func_cadban.php?iCodigoBanco=' + iCodigoBanco + '&funcao_js=parent.js_mostraagencia|0|1','Pesquisa',true);
  } else {
		js_OpenJanelaIframe('', 'db_iframe_cadban', 'func_cadban.php?lPesquisaAgencia=true&iCodigoBanco=' + iCodigoBanco + '&pesquisa_chave=' + iCodigoAgencia + '&funcao_js=parent.js_mostraagencia1','Pesquisa',false);
  }
}

function js_mostraagencia( iCodigo, sDescricao ) {

  $('k15_codage').value = iCodigo;
  $('db90_descr').value = sDescricao;
  db_iframe_cadban.hide();
}

function js_mostraagencia1( sDescricao, lErro ) {

	$('db90_descr').value = sDescricao;

  if ( lErro ) {

    $('k15_codage').focus();
    $('k15_codage').value = '';
  }
}
</script>