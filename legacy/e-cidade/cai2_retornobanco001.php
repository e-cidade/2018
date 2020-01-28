<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empagegera_classe.php"));
require_once(modification("classes/db_empageconfgera_classe.php"));
require_once(modification("classes/db_empagetipo_classe.php"));
require_once(modification("classes/db_empagedadosret_classe.php"));
$clempagegera     = new cl_empagegera;
$clempageconfgera = new cl_empageconfgera;
$clempagetipo     = new cl_empagetipo;
$clempagedadosret = new cl_empagedadosret;
$clrotulo         = new rotulocampo;
$clempagegera    ->rotulo->label();
$clempagetipo    ->rotulo->label();
$clempagedadosret->rotulo->label();

db_postmemory($_POST);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>

  #e75_codretdescr {
    display: none;
  }
  #e75_codret {
    width: 80px;
  }
  #e83_codtipo {
    width: 80px;
  }
  #e83_codtipodescr {
    width: 300px;
  }
  #ordem {
    width: 382px;
  }
  #modelo {
    width: 80px;
  }

</style>

</head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e87_codgera.focus();" bgcolor="#cccccc">


<center>
<form name="form1" method="post">


<fieldset style="margin-top: 50px; width: 600px;">
<legend><strong>Relatório Arquivo de Retorno</strong></legend>


<table border='0' align='left'>

  <tr>
    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
    <td align="left" nowrap>
  <?
   db_input("e87_codgera",8,$Ie87_codgera,true,"text",4,"onchange='js_pesquisa_gera(false);'");
   db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
  ?>
    </td>
  </tr>
  <tr>
    <td  align="left" nowrap><? db_ancora("<strong>Modelo:</strong>","",3);?>  </td>
    <td align="left" nowrap>
      <?
      $arr_mostra = Array("1"=>"Modelo 1","2"=>"Modelo 2");
      db_select("modelo",$arr_mostra,true,4,"onchange='showMessage(this.value);mostrarFiltro();'");
      ?>
    </td>
  </tr>
  <?
  $desabilita = " disabled ";

  if (isset($e87_codgera) && !empty($e87_codgera)) {

    $sCamposRetorno = "e75_codret, e87_codgera, e87_descgera ";

    $sSqlRetorno = $clempagedadosret->sql_query("", $sCamposRetorno,"e75_codret desc"," e75_codgera = {$e87_codgera}");
    $rsRetorno   = $clempagedadosret->sql_record($sSqlRetorno);
    //echo "<br>".$sSqlRetorno . "<br>";
    if ($clempagedadosret->numrows > 0) {

      $desabilita = "";


      //db_fieldsmemory($rsRetorno, 0);
      echo "
      <tr> 
	      <td align='left' nowrap title='$Te75_codret'> ";
	        db_ancora(@$Le75_codret,"",3);
      echo "      
	      </td>
	      <td align='left' nowrap>";
	        //db_input("e75_codret",8,$Ie75_codret,true,"text",3);
	        db_selectrecord("e75_codret", $rsRetorno, true, 1);
      echo "      
	      </td>
      </tr>";

    }
  }
  ?>


  <tr id="trFiltro">
    <td  align="left" nowrap><? db_ancora("<strong>Mostrar:</strong>","",3);?>  </td>
    <td align="left" nowrap>
  <?
  /*
   retirado a opção "Somente agendados banco" conforme orientação do Leandro, até sabermos como será o filtro para essa situação - Jeferson Santos - 04/04/2013

   $arr_mostra = Array("0"=>"Todos","t"=>"Somente processados","f"=>"Somente não processados","a"=>"Somente agendados banco");

  */

   $arr_mostra = Array("0"=>"Todos","t"=>"Somente processados","f"=>"Somente não processados");
   db_select("ordem",$arr_mostra,true,4);
  ?>
    </td>
  </tr>
  <?
  if(isset($e87_codgera)){
    echo "
    <tr> 
      <td  align='left' nowrap title='Conta pagadora'>
    ";
    db_ancora("<strong>Conta pagadora:</strong>","",3);
    echo "
      <td align='left' nowrap>
    ";
    $result_empagetipo = $clempageconfgera->sql_record($clempageconfgera->sql_query_inf(null,@$e87_codgera,"distinct e83_codtipo,e83_descr"));
    db_selectrecord("e83_codtipo",$result_empagetipo,true,1,"","","","0");
    echo "
      </td>
    </tr>
    
    ";
  }
  ?>
  <tr>
   <td colspan=2>
     <span id='message'></span>
   </td>
  </tr>

</table>

</fieldset>

<div style="margin-top: 10px;">

 <input name="act" type="button" <?=$desabilita?> onclick='js_gerarel();'  value="Mostrar retorno">
</div>



</form>
</center>




<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>

function js_gerarel() {

  if (document.form1.e75_codret && document.form1.e75_codret.value != "") {

    query = "retorno="+document.form1.e75_codret.value;
    if (document.form1.e83_codtipo.value != 0) {
      query += "&contapaga="+document.form1.e83_codtipo.value;
    }
  	query += "&ordem="+document.form1.ordem.value;
    if (document.form1.modelo.value == 1) {
      console.log('aqui 1');
      jan = window.open('cai2_retornobanco002.php?lCancelado=0&'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    } else {
      console.log('aqui 2');
      jan = window.open('cai2_inconsistenciaagenda002.php?lCancelado=0&'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    }

  } else if (!document.form1.e75_codret && document.form1.e87_descgera.value != "") {
    alert("Retorno não processado para este arquivo.");
  } else {
    alert("Informe o código do arquivo precedente do retorno.");
  }
}
//--------------------------------
function js_pesquisa_gera(lMostra) {

  if (lMostra == true){

    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empagegera','func_empagegera.php?lRetorno=1&funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa',true);
  } else {

     if (document.form1.e87_codgera.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empagegera','func_empagegera.php?lRetorno=1&pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
     } else {
       document.form1.e87_descgera.value = '';
     }
  }
}
function js_mostragera(chave, erro) {

  if (document.form1.e75_codret) {
    document.form1.e75_codret.value = "";
  }

  document.form1.e87_descgera.value = chave;
  if (erro == true) {

    document.form1.e87_codgera.focus();
    document.form1.e87_codgera.value = '';
    return false
  }
  document.form1.submit();
}

function js_mostragera1(chave1, chave2) {

  if(document.form1.e75_codret){
    document.form1.e75_codret.value = "";
  }
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;

  if (document.form1.e87_codgera.value == "") {
    return false;
  }

  db_iframe_empagegera.hide();
  document.form1.submit();
}

function showMessage(iCodigo) {

  if (iCodigo == 1) {
    document.getElementById('message').innerHTML ="Relatório de arquivo retorno original.";
  } else if (iCodigo == 2) {
    document.getElementById('message').innerHTML = "Demonstração da baixa de pagamentos pelo retorno do arquivo.";
  }
}
showMessage(1);


function mostrarFiltro() {

  $('trFiltro').style.display = '';
  if ($F('modelo') == 2) {

    $('trFiltro').style.display = 'none';
    $('ordem').value            = '0';
  }
}

mostrarFiltro();
//--------------------------------
</script>
</body>
</html>