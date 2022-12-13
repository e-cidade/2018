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
include("classes/db_mer_cardapioaluno_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_cardapioaluno = new cl_mer_cardapioaluno;
$clmer_cardapio      = new cl_mer_cardapio;
$clrotulo            = new rotulocampo;
$clrotulo->label("me11_d_data");
$clrotulo->label("me11_i_repeticao");
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
   <fieldset style="width:95%;"><legend><b>Consumo de Refeição</b></legend>
    <table border="0" align="left" width="100%">
     <tr>
      <td align="center">
       <table>
        <tr>
         <td>
           <b>Cardápio:</b>          
           <?$result_cardapio = $clmer_cardapio->sql_record($clmer_cardapio->sql_query("",
                                                                                       "me01_i_codigo,me01_c_nome",
                                                                                        "",
                                                                                        ""
                                                                                      )
                                                           );?>
         <select name="cardapio" id="cardapio"   onChange="js_cardapio(this.value);"
                  style="height:18px;font-size:10px;">
           <option value="0"></option>
           <?for ($t=0;$t<$clmer_cardapio->numrows;$t++) {
        
               db_fieldsmemory($result_cardapio,$t);
           ?>
               <option value="<?=$me01_i_codigo?>"><?=$me01_c_nome?></option>
      
           <?}?>
          </select>
         </td>
        </tr>
       </table>
      </td>
     </tr> 
        <tr>
        <td nowrap title="<?=@$Tme39_i_repeticao?>">
          <?=@$Lme39_i_repeticao?>   
          <?db_input('me39_i_repeticao',10,@$Ime39_i_repeticao,true,'text',$db_opcao,"")?>
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
	
  $('select_turma').innerHTML         = "";
  $('select_turma').disabled          = true;
  $('select_refeicao').innerHTML      = "";
  $('select_refeicao').disabled       = true;
  $('select_aluno').innerHTML         = "";
  $('select_aluno').disabled          = true;
  $('select_cardapioaluno').innerHTML = "";
  $('select_cardapioaluno').disabled  = true;
  $('data').innerHTML                 = "";
  $('titulo').innerHTML               = "";
  if (cardapio == "") { 
	return false;
  }
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaTurma_e_Refeicao';
  var url     = 'mer4_mer_cardapioalunoRPC.php';
  var oAjax = new Ajax.Request(url,
		                       {
                                 method    : 'post',
                                 parameters: 'cardapio='+cardapio+
                                             '&sAction='+sAction,
                                 onComplete: js_retornoPesquisaTurma_e_Refeicao
		                       }
                              );
    
}

function js_retornoPesquisaTurma_e_Refeicao(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if (oRetorno[0].length==0) {
    sHtml += '<option value="">Nenhuma refeição consumida para o cardápio selecionado!</option>';
  } else {
	  
    sHtml += '<option value=""></option>';
    for (var i = 0;i < oRetorno[0].length; i++) {
        
      with (oRetorno[0][i]) {
          
        arr_data = me12_d_data.urlDecode().split("-");
        datanew = arr_data[2]+"/"+arr_data[1]+"/"+arr_data[0];
        sHtml += '<option value="'+me12_i_codigo.urlDecode()+'">'+datanew+' - '+me03_c_tipo.urlDecode()+
                                 ' - '+me01_c_nome.urlDecode()+' - Versão: '+me01_f_versao.urlDecode()+'</option>';
         
      }
      
    }
    
  }  
  $('select_refeicao').innerHTML = sHtml;
  $('select_refeicao').disabled  = false;
  sHtml = '';
  if (oRetorno[1].length==0) {
    sHtml += '  <option value="">Nenhuma turma foi vinculada ao cardápio selecionado!</option>';
  } else {
    sHtml += '  <option value=""></option>';
    for (var i = 0;i < oRetorno[1].length; i++) {
        
      with (oRetorno[1][i]) {
        sHtml += '  <option value="'+ed11_i_codigo.urlDecode()+'">'+ed11_c_descr.urlDecode()+'</option>';
      }
    }
  }
  $('select_turma').innerHTML = sHtml;
  $('select_turma').disabled  = false; 
}

function js_aluno(){
  $('select_aluno').innerHTML         = "";
  $('select_aluno').disabled          = true;
  $('select_cardapioaluno').innerHTML = "";
  $('select_cardapioaluno').disabled  = true;
  $('data').innerHTML                 = "";
  $('titulo').innerHTML               = "";
  if ($('select_refeicao').value!="") {
	  
    indice = $('select_refeicao').selectedIndex;
    $('data').innerHTML = $('select_refeicao')[indice].text.substr(0,10);
    $('titulo').innerHTML = $('select_refeicao')[indice].text.substr(10);
    
  }
  if ($('select_turma').value=="" || $('select_refeicao').value=="") {
    return false;
  }
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaAluno';
  var url     = 'mer4_mer_cardapioalunoRPC.php';
  var oAjax = new Ajax.Request(url,
		                          { 
                                    method    : 'post',
                                    parameters: 'refeicao='+$('select_refeicao').value+
                                                '&turma='+$('select_turma').value+'&sAction='+sAction,
                                    onComplete: js_retornoPesquisaAluno
                                  }
                               );
  
}

function js_retornoPesquisaAluno(oAjax) {
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  for (var i = 0;i < oRetorno[0].length; i++) {
	  
    with (oRetorno[0][i]) {
	  
      sHtml += '<option value="'+ed60_i_codigo.urlDecode()+'">'+ed47_i_codigo.urlDecode()+
              ' - '+ed47_v_nome.urlDecode()+'</option>';
    
    }
  
  }
  $('select_aluno').innerHTML = sHtml;
  $('select_aluno').disabled = false;
  sHtml = '';
  for (var i = 0;i < oRetorno[1].length; i++) { 
	  
    with (oRetorno[1][i]) {
        
      sHtml += '<option value="'+ed60_i_codigo.urlDecode()+'">'+ed47_i_codigo.urlDecode()+
               ' - '+ed47_v_nome.urlDecode()+'</option>';
      
    }    
  }
  $('select_cardapioaluno').innerHTML = sHtml;
  $('select_cardapioaluno').disabled  = false;
  if (oRetorno[0].length==0) {
	  
    $('excluirtodos').disabled = false;
    $('incluirtodos').disabled = true;
    
  }
  
  if (oRetorno[1].length==0) {
	  
    $('excluirtodos').disabled = true;
    $('incluirtodos').disabled = false;
    
  }
  $('pesquisar').disabled = false;
}

function js_verificadia() {
	
  js_divCarregando("Aguarde, verificando registro(s)","msgBox");
  var Tam = document.form1.select_cardapioaluno.length;
  cod_alunos = '';
  sep = '';
  for (x=0;x<Tam;x++) {
	  
    cod_alunos += sep+document.form1.select_cardapioaluno.options[x].value;
    sep = ',';
    
  }
  var sAction = 'VerificaDia';
  var url     = 'mer4_mer_cardapioalunoRPC.php';
  var oAjax = new Ajax.Request(url,
		                          {
                                    method    : 'post',
                                    parameters: 'cardapiodia='+$('select_refeicao').value+'&turma='
                                                              +$('select_turma').value+'&cod_alunos='
                                                              +cod_alunos+'&sAction='+sAction,
                                    onComplete: js_retornoVerificaDia
                                   }
                               );

}

function js_retornoVerificaDia(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  numreg       = parseInt(oRetorno.urlDecode());
  var Tam      = document.form1.select_cardapioaluno.length;
  cod_alunos   = '';
  sep          = '';
  for (x=0;x<Tam;x++) {
	  
    cod_alunos += sep+document.form1.select_cardapioaluno.options[x].value;
    sep = ',';
    
  }
  if (numreg==0 && cod_alunos=="") {
	  
    alert("Informe algum aluno para consumo da refeição!");
    return false;
    
  }
  if (numreg>0 && cod_alunos=="") {
	  
    if (!confirm("Confirmar exclusão de todos registros desta turma para esta data?")) {
        
      js_aluno();
      return false;
      
   }    
  }
  js_divCarregando("Aguarde, salvando registro(s)","msgBox");
  var sAction = 'InclusaoCardapiodia';
  var url     = 'mer4_mer_cardapioalunoRPC.php';
  var oAjax = new Ajax.Request(url,
		                          {
                                    method    : 'post',
                                    parameters: 'cardapiodia='+$('select_refeicao').value+'&turma='
                                                              +$('select_turma').value+'&cod_alunos='
                                                              +cod_alunos+'&sAction='+sAction,
                                    onComplete: js_retornoInclusaoCardapiodia
                                   }
                               );
}

function js_retornoInclusaoCardapiodia(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.urlDecode());
 
}

function js_incluir() {
	
  var Tam = document.form1.select_aluno.length;
  var F   = document.form1;
  for (x=0;x<Tam;x++) {
	  
    if (F.select_aluno.options[x].selected==true) {
        
      F.elements['select_cardapioaluno'].options[F.elements['select_cardapioaluno'].options.length] = 
          new Option(F.select_aluno.options[x].text,F.select_aluno.options[x].value);
      F.select_aluno.options[x] = null;
      Tam--;
      x--;
      
    }    
  }
  if (document.form1.select_aluno.length>0) {
    document.form1.select_aluno.options[0].selected = true;
  } else {
	  
    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;
    
  }
  document.form1.excluirtodos.disabled = false;
  document.form1.select_aluno.focus();
  
}

function js_incluirtodos() {
	
  var Tam = document.form1.select_aluno.length;
  var F = document.form1;
  for (i=0;i<Tam;i++) {
	  
    F.elements['select_cardapioaluno'].options[F.elements['select_cardapioaluno'].options.length] = 
      new Option(F.select_aluno.options[0].text,F.select_aluno.options[0].value)
    F.select_aluno.options[0] = null;
    
  }
  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.select_cardapioaluno.focus();
}

function js_excluir() {
	
  var F = document.getElementById("select_cardapioaluno");
  Tam = F.length;
  for (x=0;x<Tam;x++) {
	  
    if (F.options[x].selected==true) {
        
      document.form1.select_aluno.options[document.form1.select_aluno.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
      
    }    
  }
  if (document.form1.select_cardapioaluno.length>0) {
    document.form1.select_cardapioaluno.options[0].selected = true;
  }
  if (F.length == 0) {
	  
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  document.form1.select_cardapioaluno.focus();
}

function js_excluirtodos() {
	
  var Tam = document.form1.select_cardapioaluno.length;
  var F = document.getElementById("select_cardapioaluno");
  for (i=0;i<Tam;i++) {
	  
    document.form1.select_aluno.options[document.form1.select_aluno.length] = 
    new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;
    
  }
  if (F.length == 0) {
	  
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  document.form1.select_aluno.focus();
}

function js_desabinc() {
	
  for(i=0;i<document.form1.select_aluno.length;i++) {
	  
    if (document.form1.select_aluno.length>0 && document.form1.select_aluno.options[i].selected) {
        
      if (document.form1.select_cardapioaluno.length>0) {
        document.form1.select_cardapioaluno.options[0].selected = false;
      }
      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
      
    }    
  }  
}

function js_desabexc() {
	
  for (i=0;i<document.form1.select_cardapioaluno.length;i++) {
	  
    if (document.form1.select_cardapioaluno.length>0 && document.form1.select_cardapioaluno.options[i].selected) {
        
      if (document.form1.select_aluno.length>0) {
        document.form1.select_aluno.options[0].selected = false;
      }
      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
      
    }    
  }  
}

function js_verifica() {

  repeticao   = document.form1.me11_i_repeticao.value;
  if ((document.form1.me11_i_repeticao.value > document.form1.select_aluno.length)) {
		        
	alert('Número de repetições informadas é maior que o número de alunos matriculados!');
	document.form1.me11_i_repeticao.value='';
	document.form1.me11_i_repeticao.focus();
	return false;
	    
  }
  return true;
	
}
</script>