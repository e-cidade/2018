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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rharqbanco_classe.php");

db_postmemory($HTTP_POST_VARS);
$clrharqbanco = new cl_rharqbanco;
$clrotulo     = new rotulocampo;

$clfolha = new cl_rhgeracaofolha;
$clrotulo->label('rh102_sequencial');
$clrotulo->label('rh102_descricao');

$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');

if (isset($emite2)) {

  db_inicio_transacao();
  $clrharqbanco->alterar($rh34_codarq);
  if (isset($rh34_sequencial)){
  	$rh34_sequencial++;
  }
  db_fim_transacao();

} else if(isset($rh34_codarq)) {
  $result = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
  if($clrharqbanco->numrows > 0){
    db_fieldsmemory($result,0);
    $rh34_sequencial += 1;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; js_mostraGeracaoDisco();" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<center>
<form name="form1" method="post" action="">
<fieldset style="width:300px;">
<legend>Gerar Arquivos</legend>
<table align="center" border="0">
  <tr>
    <td><b>Data da Geração:</b></td>
    <td>
      <?
      if (   (!isset($datageracao_dia) || (isset($datageracao_dia) && trim($datageracao_dia) == ""))
      		&& (!isset($datageracao_mes) || (isset($datageracao_mes) && trim($datageracao_mes) == ""))
      		&& (!isset($datageracao_ano) || (isset($datageracao_ano) && trim($datageracao_ano) == "")) ) {
        $datageracao_dia=date('d',db_getsession('DB_datausu'));
        $datageracao_mes=date('m',db_getsession('DB_datausu'));
        $datageracao_ano=date('Y',db_getsession('DB_datausu'));
      }
      db_inputdata('datageracao',@$datageracao_dia,@$datageracao_mes,@$datageracao_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td><b>Data do Depósito:</b></td>
    <td>
      <?
      if (   (!isset($datadeposito_dia) || (isset($datadeposito_dia) && trim($datadeposito_dia) == ""))
      		&& (!isset($datadeposito_mes) || (isset($datadeposito_mes) && trim($datadeposito_mes) == ""))
      		&& (!isset($datadeposito_ano) || (isset($datadeposito_ano) && trim($datadeposito_ano) == "")) ) {
        $datadeposito_dia = "";
        $datadeposito_mes = "";
        $datadeposito_ano = "";
      }
      db_inputdata('datadeposito',@$datadeposito_dia,@$datadeposito_mes,@$datadeposito_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td align="left" nowrap title="<?=@$Trh34_codarq?>">
      <?db_ancora(@$Lrh34_codarq,"js_pesquisa(true);",1);?>
    </td>
    <td align="left" nowrap colspan="3">
      <?db_input("rh34_codarq",6,@$Irh34_codarq,true,"text",4,"onchange='js_pesquisa(false);'");?>
      <?db_input("rh34_descr",40,@$Irh34_descr,true,"text",3);?>
      <?db_input("rodape",40,0,true,"hidden",3);?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_codban?>">
      <?
      db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",1);
      ?>
    </td>
    <td colspan="3">
      <?
      db_input('rh34_codban',6,$Irh34_codban,true,'text',1," onchange='js_pesquisarh34_codban(false);'")
      ?>
      <?
      db_input('db90_descr',40,$Idb90_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr id='ctnGeracaoDisco' style="visibility: hidden;">
    <td align="left" nowrap title="<?=@$Trh102_sequencial?>">
      <?db_ancora(@$Lrh102_sequencial,"js_pesquisafolha(true);",1);?>
    </td>
    <td align="left" nowrap colspan="3">
      <?db_input("rh102_sequencial",6,@$Irh102_sequencial,true,"text",4,"onchange='js_pesquisafolha(false);'");?>
      <?db_input("rh102_descricao",40,@$Irh102_descricao,true,"text",3);?>
    </td>
  </tr>
  </table>
  <fieldset>
  <legend>Dados Bancários</legend>
  <table>
  <tr>
    <td nowrap title="<?=@$Trh34_agencia?>">
      <?=@$Lrh34_agencia?>
    </td>
    <td>
      <?
      db_input('rh34_agencia',5,$Irh34_agencia,true,'text',3,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td>
      <?
      db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_conta?>">
      <?=@$Lrh34_conta?>
    </td>
    <td>
      <?
      db_input('rh34_conta',15,$Irh34_conta,true,'text',3,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
      <?=@$Lrh34_dvconta?>
    </td>
    <td>
      <?
      db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_convenio?>">
      <?=@$Lrh34_convenio?>
    </td>
    <td>
      <?
      db_input('rh34_convenio',15,$Irh34_convenio,true,'text',3,"")
      ?>
    </td>
    <?if(isset($rh34_codban) && $rh34_codban == "104"){?>
    <td align="right">
      <strong>Layout:</strong>
    </td>
    <td>
      <?
      $arr_layout = Array(
                          "9"=>"CNAB240",
                          "3"=>"CEF",
                          "4"=>"CEF SIACC 150"
                         );
      db_select("layout", $arr_layout, true, 1, "");
      ?>
    </td>
    <?}?>
  </tr>
  <tr>
    <td align="left">
      <strong>Vinculo:</strong>
    </td>
    <td>
      <?
      $arr_vinculo = Array(
                          "T"=>"Todos",
                          "A"=>"Ativo",
                          "I"=>"Inativo",
                         );
      db_select("vinculo", $arr_vinculo, true, 1, "");
      ?>
    </td>
  </tr>
  <tr>
    <td align="left">
      <strong>Tipo de Conta:</strong>
    </td>
    <td>
      <?
      $arr_tipo = Array(
                          "C"=>"Conta Corrente",
                          "O"=>"Ordem de Pagamento",
                       );
      db_select("tipoconta", $arr_tipo, true, 1, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_sequencial?>">
      <?=@$Lrh34_sequencial?>
    </td>
    <td>
      <?
      db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',3,"")
      ?>
    </td>
  </tr>
 	</table>
 </fieldset>
</fieldset>
  <br>
  <input name="emite2" id="emite2" type="submit" value="Processar" onclick="return js_valores();" />
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

const MENSAGENS = "recursoshumanos.pessoal.pes2_osoemitearqbanco001.";

function js_pesquisa(mostra) {
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rharqbanco','func_rharqbanco.php?ativas=true&funcao_js=parent.js_mostra1|rh34_codarq|rh34_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codarq.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rharqbanco','func_rharqbanco.php?ativas=true&pesquisa_chave='+document.form1.rh34_codarq.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }else{
      document.form1.rh34_codarq.value = '';
      document.form1.rh34_descr.value = '';
      location.href = 'pes2_osoemitearqbanco001.php';
    }
  }
}

function js_mostra(chave,erro) {
  if(erro==true){
    document.form1.rh34_descr.value = chave;
    document.form1.rh34_codarq.value = '';
    document.form1.rh34_codarq.focus();
    location.href = 'pes2_osoemitearqbanco001.php';
  }else{
    document.form1.submit();
  }
}

function js_mostra1(chave1,chave2) {
  document.form1.rh34_codarq.value = chave1;
  db_iframe_rharqbanco.hide();
  document.form1.submit();
}

function js_pesquisarh34_codban(mostra) {
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codban.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = '';
    }
  }
}

function js_mostradb_bancos(chave,erro) {
	document.form1.db90_descr.value = chave;
  if(erro==true){
    document.form1.rh34_codban.focus();
    document.form1.rh34_codban.value = '';
  }
  js_mostraGeracaoDisco();
}

function js_mostradb_bancos1(chave1,chave2) {

	document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  js_mostraGeracaoDisco();
  db_iframe_db_bancos.hide();
}

function js_mostraGeracaoDisco() {

	if ($F('rh34_codban') == "041") {
    $("ctnGeracaoDisco").style.visibility = 'visible';
	}	else {

    $("rh102_sequencial").value           = '';
    $("rh102_descricao").value            = '';
		$("ctnGeracaoDisco").style.visibility = 'hidden';
	}
}

function js_valores() {

	  if ($F('rh34_codarq') =="") {

	    alert(_M(MENSAGENS + "erro_codigo_arquivo"));
	    $('rh34_codarq').focus();
	    return false;
	  } else if($F('datageracao') == "") {

      alert(_M(MENSAGENS + "erro_data_geracao"));
	    document.form1.datageracao_dia.select();
	    return false;
	  } else if($F('datadeposito') == "") {

      alert(_M(MENSAGENS + "erro_data_deposito"));
      document.form1.datadeposito_dia.select();
      return false;
    } else if($F('rh34_codban') == "") {

	    alert(_M(MENSAGENS + "erro_febraban"));
      $('rh34_codban').focus();
	    return false;
	  } else if ($F('rh102_sequencial') == "" && $F('rh34_codban') == "041") {

		  alert(_M(MENSAGENS + "erro_geracao_disco"));
		  $('rh102_sequencial').focus();
		  return false;
	  } else {
	    return true;
	  }

}

function js_emite() {

	 js_divCarregando("Processando, aguarde ","msgBox");

	 qry  = 'pes2_osoemitearqbanco002.php?';
	 if ($F('rh34_codban') == '041') {
		 qry  = 'pes2_emitearquivobancario_banrisul002.php?';
	 }
	 qry += 'rh34_codarq='+$F('rh34_codarq');
	 qry += '&datadeposito='+$F('datadeposito_ano')+'-'+$F('datadeposito_mes')+'-'+$F('datadeposito_dia');
	 qry += '&datageracao='+$F('datageracao_ano')+'-'+$F('datageracao_mes')+'-'+$F('datageracao_dia');
	 qry += '&codban='+$F('rh34_codban');
	 qry += '&vinculo='+$F('vinculo');
	 qry += '&tipoconta='+$F('tipoconta');
	 qry += '&rh102_sequencial='+$F('rh102_sequencial');
	 if (document.form1.layout) {
	   qry += '&layout='+$F('layout');
	 }


	 js_OpenJanelaIframe('top.corpo','db_iframe_geraarqbanco',qry,'Gerando Arquivo',false);
}


function js_detectaarquivo(arquivo,pdf) {

	js_removeObj("msgBox");

  top.corpo.db_iframe_geraarqbanco.hide();
  listagem = arquivo+"#Download arquivo TXT (pagamento eletrônico)|";
  listagem+= pdf+"#Download relatório";
  js_montarlista(listagem,"form1");

}

function js_erro(msg) {

	js_removeObj("msgBox");

  top.corpo.db_iframe_geraarqbanco.hide();
  alert(msg);
}

function js_fechaiframe(){
	  db_iframe_geraarqbanco.hide();
	}

function js_pesquisafolha(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_rhgeracaofolha',
                        'func_rhgeracaofolha.php?ativas=true&funcao_js=parent.js_mostrafolha1|rh102_sequencial|rh102_descricao','Pesquisa',true);
  } else {
    if (document.form1.rh102_sequencial.value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_rhgeracaofolha',
                          'func_rhgeracaofolha.php?ativas=true&pesquisa_chave='+
                          document.form1.rh102_sequencial.value+'&funcao_js=parent.js_mostrafolha','Pesquisa',false);
    }else{
      document.form1.rh102_descricao.value = '';
    }
  }
}

function js_mostrafolha(erro,chave1,chave2) {

  document.form1.rh102_descricao.value = chave2;

  if (erro == true) {
    document.form1.rh102_sequencial.focus();
    document.form1.rh102_sequencial.value = '';
    document.form1.rh102_descricao.value  = chave1;
  }
}

function js_mostrafolha1(chave1,chave2){

  document.form1.rh102_sequencial.value = chave1;
  document.form1.rh102_descricao.value  = chave2;
  db_iframe_rhgeracaofolha.hide();
}

</script>
<?
if(isset($emite2)){
  if($clrharqbanco->erro_status=="0"){
    $clrharqbanco->erro(true,false);
    $db_botao=true;
  }else{
    echo "<script>js_emite();</script>";
  }
}
?>
