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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_mer_tipocardapio_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_tipocardapio  = new cl_mer_tipocardapio;
$clrotulo            = new rotulocampo;
$db_opcao            = 1;
$db_botao            = true;
$escola              = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%;"><legend><b>Consumo de Refeição por Turma</b></legend>
    <table border="0" align="left" width="100%">
     <tr>
      <td align="center">
       <table>
        <tr>
         <td>
           <b>Cardápio:</b>          
           <?
           $hoje = date("Y-m-d",db_getsession("DB_datausu"));
           $result_tipocardapio = $clmer_tipocardapio->sql_record(
                                   $clmer_tipocardapio->sql_query("",
                                                                  "me27_i_codigo,me27_c_nome,me27_f_versao,me27_i_id",
                                                                  "me27_i_id,me27_f_versao desc",
                                                                  "((me27_d_inicio is not null 
                                                                     and me27_d_fim is null
                                                                     and me27_d_inicio <= '$hoje') 
                                                                     or (me27_d_fim is not null and '$hoje'
                                                                     between me27_d_inicio and me27_d_fim))
                                                                   AND exists(select * from mer_cardapioescola
                                                                              where me32_i_tipocardapio = me27_i_codigo
                                                                              and me32_i_escola = $escola)
                                                                  "
                                                                 ));?>
           <select name="cardapio" id="cardapio" onChange="js_cardapio(this.value);"
                  style="height:18px;font-size:10px;">
           <option value="0"></option>
           <?for ($t=0;$t<$clmer_tipocardapio->numrows;$t++) {
        
              db_fieldsmemory($result_tipocardapio,$t);
              ?>
              <option value="<?=$me27_i_codigo?>"><?=$me27_c_nome?> - Versão: <?=$me27_f_versao?></option>
      
           <?}?>
          </select>
         </td>
        </tr>
       </table>
      </td>
     </tr> 
     <tr>
      <td align="center">
       <table>
        <tr>
         <td>
          <b>Escola:</b>
         </td>
         <td>
          <select name="select_escola" id="select_escola" onchange="js_escola(this.value)" 
                  style="width:450px;height:18px;font-size:10px;;" disabled>
          </select>
         </td>
        </tr>
        <tr>
         <td>
          <b>Refeição:</b>
         </td>
         <td>
          <select name="select_refeicao" id="select_refeicao" onchange="js_turma(this.value)"
                  style="width:450px;height:18px;font-size:10px;;" disabled>
          </select>
         </td>
        </tr>
        <tr>
         <td colspan="2">
          <div id="div_turmas"></div>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td align="center">
       <table>
        <tr>
         <td colspan="3" align="center" height="30">
          &nbsp;<span id="data" style="font-size:18px;font-weight:bold;"></span>
          &nbsp;<span id="titulo" style="font-size:12px;font-weight:bold;"></span>
         </td>
        </tr>
        <tr>
         <td colspan="3" align="center">
          <input name="pesquisar" type="button" id="pesquisar" value="Salvar" onclick="js_verificadia();" disabled>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
   </center>
  </td>
 </tr>
</table>
</center>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_cardapio(cardapio) {

  $('select_escola').innerHTML         = "";
  $('select_escola').disabled          = true;
  $('select_refeicao').innerHTML      = "";
  $('select_refeicao').disabled       = true;
  $('div_turmas').innerHTML      = "";
  $('data').innerHTML                 = "";
  $('titulo').innerHTML               = "";
  $('pesquisar').disabled = true;
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaEscola';
  var url     = 'mer4_mer_cardapioturma.RPC.php';
  var oAjax = new Ajax.Request(url,
                               {
                                 method    : 'post',
                                 parameters: 'cardapio='+cardapio+
                                             '&sAction='+sAction,
                                 onComplete: js_retornoPesquisaEscola
                               }
                              );
    
}
function js_retornoPesquisaEscola(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if (oRetorno.length==0) {
    sHtml += '<option value="">Nenhum escola vinculada ao cardápio selecionado!</option>';
    $('select_escola').innerHTML = sHtml;
  } else {
          
    sHtml += '<option value=""></option>';
    for (var i = 0;i < oRetorno.length; i++) {
            
      with (oRetorno[i]) {
              
        sHtml += '<option value="'+me32_i_codigo+'">'+ed18_c_nome.urlDecode()+'</option>';
             
      }
      
    }
    $('select_escola').innerHTML = sHtml;
    document.form1.select_escola[1].selected = true;
    js_escola(document.form1.select_escola.value);
        
  }  
  $('select_escola').disabled  = false;
  
}
function js_escola(escola) {
    
  $('data').innerHTML                 = "";
  $('titulo').innerHTML               = "";
  $('select_refeicao').innerHTML      = "";
  $('select_refeicao').disabled       = true;
  $('div_turmas').innerHTML      = "";
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaRefeicao';
  var url     = 'mer4_mer_cardapioturma.RPC.php';
  var oAjax = new Ajax.Request(url,
                               {
                                 method    : 'post',
                                 parameters: 'cardapio='+document.form1.cardapio.value+'&escola='+escola+'&sAction='+sAction,
                                 onComplete: js_retornoPesquisaRefeicao
                               }
                              );
    
}

function js_retornoPesquisaRefeicao(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if (oRetorno.length==0) {
    sHtml += '<option value="">Nenhuma refeição consumida para o cardápio e/ou escola selecionados!</option>';
  } else {
      
    sHtml += '<option value=""></option>';
    for (var i = 0;i < oRetorno.length; i++) {
        
      with (oRetorno[i]) {
          
        arr_data = me12_d_data.urlDecode().split("-");
        datanew = arr_data[2]+"/"+arr_data[1]+"/"+arr_data[0];
        sHtml += '<option value="'+me12_i_codigo.urlDecode()+'">'+datanew+' - '+me03_c_tipo.urlDecode()+
                                 ' - '+me01_c_nome.urlDecode()+' - Versão: '+me01_f_versao.urlDecode()+'</option>';
         
      }
      
    }
    
  }  
  $('select_refeicao').innerHTML = sHtml;
  $('select_refeicao').disabled  = false;
  
}
function js_turma(){

  $('data').innerHTML                 = "";
  $('titulo').innerHTML               = "";
  $('div_turmas').innerHTML      = "";
  if ($('select_refeicao').value!="") {
	      
    indice = $('select_refeicao').selectedIndex;
    $('data').innerHTML = $('select_refeicao')[indice].text.substr(0,10);
    $('titulo').innerHTML = $('select_refeicao')[indice].text.substr(10);
    $('pesquisar').disabled = false;
	        
  } else {
    $('pesquisar').disabled = true;
    return false;
  }
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaTurma';
  var url     = 'mer4_mer_cardapioturma.RPC.php';
  var oAjax = new Ajax.Request(url,
                               {
                                 method    : 'post',
                                 parameters: 'cardapio='+document.form1.cardapio.value+'&escola='+document.form1.select_escola.value+'&refeicao='+$('select_refeicao').value+'&sAction='+sAction,
                                 onComplete: js_retornoPesquisaTurma
                               }
                              );

}
function js_retornoPesquisaTurma(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '<b>Turmas:</b><br>';
  if (oRetorno.length==0) {
	      
    sHtml += '  Nenhuma turma foi vinculada ao cardápio e/ou escola selecionados!';
    $('pesquisar').disabled = true;
	    
  } else {
    sHtml += '  <table width="500px;"><tr>';
    sHtml += '  <td>Código/Nome/Etapa</td>';
    sHtml += '  <td>Qtde. Alunos</td>';
    sHtml += '  <td>Qtde. Consumida</td>';
    sHtml += '  <td>Qtde. Repetições</td></tr>';    
    for (var i = 0;i < oRetorno.length; i++) {
	        
      with (oRetorno[i]) {
	          
          sHtml += '  <tr><td><input size="5" type="hidden" name="codigo_turma" id="select_turma" value="'+ed57_i_codigo+'">';
          sHtml += '  '+ed57_i_codigo+' / '+ed57_c_descr.urlDecode()+' / '+ed11_c_descr.urlDecode()+'</td>';
          sHtml += '  <td><input size="5" type="text" name="qtde_matricula" id="qtde_matricula" value="'+qtde+'" disabled></td>';
          sHtml += '  <td><input size="5" type="text" name="qtde_consumida" id="qtde_consumida" value="'+qtde_consumida+'" onKeyUp="js_ValidaCampos(this,1,\'Qtde. Consumida\',\'f\',\'f\',event);"></td>';
          sHtml += '  <td><input size="5" type="text" name="qtde_repeticao" id="qtde_repeticao" value="'+qtde_repeticao+'" onKeyUp="js_ValidaCampos(this,1,\'Qtde. Repetições\',\'f\',\'f\',event);"></td></tr>';
	        
      }
	      
    }
    sHtml += '  </table>';
	    
  }
  $('div_turmas').innerHTML = sHtml;
	 
}
function js_verificadia(oAjax) {

  msgerro = "";
  turmas = "";
  sep = "";
  tam = document.form1.codigo_turma.length;
  if (tam==undefined) {

    if (document.form1.qtde_consumida.value!="") {

      if (parseInt(document.form1.qtde_consumida.value)>parseInt(document.form1.qtde_matricula.value)) {
        msgerro += " Turma "+document.form1.codigo_turma.value+": quantidade consumida não pode ser maior que nº alunos na turma.\n";
      }
	        
    }
    if (document.form1.qtde_repeticao.value!="") {

      if (parseInt(document.form1.qtde_repeticao.value)>parseInt(document.form1.qtde_matricula.value)) {
        msgerro += " Turma "+document.form1.codigo_turma.value+": quantidade de repetições não pode ser maior que nº alunos na turma.\n";
      }
        
    }
    if (document.form1.qtde_repeticao.value!="" && document.form1.qtde_consumida.value!="") {

      if (parseInt(document.form1.qtde_repeticao.value)>parseInt(document.form1.qtde_consumida.value)) {
          msgerro += " Turma "+document.form1.codigo_turma.value+": quantidade de repetições não pode ser maior que quantidade consumida.\n";
      }
          
    }
    if (document.form1.qtde_repeticao.value!="" && document.form1.qtde_consumida.value=="") {
      msgerro += " Turma "+document.form1.codigo_turma.value+": quantidade de repetições não pode ser maior que quantidade consumida.\n";
    }
    turmas += document.form1.codigo_turma.value+"|"+document.form1.qtde_consumida.value+"|"+document.form1.qtde_repeticao.value;
  } else {

	for (i=0;i<tam;i++) {

      if (document.form1.qtde_consumida[i].value!="") {

	    if (parseInt(document.form1.qtde_consumida[i].value)>parseInt(document.form1.qtde_matricula[i].value)) {
	      msgerro += " Turma "+document.form1.codigo_turma[i].value+": quantidade consumida não pode ser maior que nº alunos na turma.\n";
	    }
	              
	  }
	  if (document.form1.qtde_repeticao[i].value!="") {

	    if (parseInt(document.form1.qtde_repeticao[i].value)>parseInt(document.form1.qtde_matricula[i].value)) {
	      msgerro += " Turma "+document.form1.codigo_turma[i].value+": quantidade de repetições não pode ser maior que nº alunos na turma.\n";
	    }
	          
	  }
	  if (document.form1.qtde_repeticao[i].value!="" && document.form1.qtde_consumida[i].value!="") {

	    if (parseInt(document.form1.qtde_repeticao[i].value)>parseInt(document.form1.qtde_consumida[i].value)) {
	      msgerro += " Turma "+document.form1.codigo_turma[i].value+": quantidade de repetições não pode ser maior que quantidade consumida.\n";
	    }
	            
	  }
	  if (document.form1.qtde_repeticao[i].value!="" && document.form1.qtde_consumida[i].value=="") {
	    msgerro += " Turma "+document.form1.codigo_turma[i].value+": quantidade de repetições não pode ser maior que quantidade consumida.\n";
	  }
      turmas += sep+document.form1.codigo_turma[i].value+"|"+document.form1.qtde_consumida[i].value+"|"+document.form1.qtde_repeticao[i].value;
      sep = ";";	  

	}
	
  }
  if (msgerro!="") {
	  
    alert(msgerro);
    return false;
    
  }
  js_divCarregando("Aguarde, salvando registro(s)","msgBox");
  var sAction = 'InclusaoCardapioTurma';
  var url     = 'mer4_mer_cardapioturma.RPC.php';
  var oAjax = new Ajax.Request(url,
                                  {
                                    method    : 'post',
                                    parameters: 'cardapiodia='+$('select_refeicao').value+'&turma='+turmas+'&sAction='+sAction,
                                    onComplete: js_retornoInclusaoCardapiodia
                                   }
                               );
}

function js_retornoInclusaoCardapiodia(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.urlDecode());
  js_turma(document.form1.select_refeicao.value);
 
}
</script>