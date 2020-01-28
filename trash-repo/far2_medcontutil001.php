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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oRotulo = new rotulocampo;
$oRotulo->label("fa04_i_unidadess");
$oRotulo->label("fa06_i_matersaude");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<br><br>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >

<form name="form1" method="post" action="">
  <center>
  <br><br>
  <fieldset style="width:65%"><legend align='left'><b>Medicamentos Continuados Utilizados</b></legend>
    <table width='100%' border="0" >
       <tr> 
         <td align="right">
           <?
           db_ancora(@$Lfa06_i_matersaude, "js_pesquisafa01_i_medicamento(true);", '');
           ?>
         </td>
         <td>
           <?
           db_input('fa01_i_codigo', 10, @$Ifa01_i_codigo, true, 'text', 1,
                    " onchange='js_pesquisafa01_i_medicamento(false);'"
                   );
           db_input('m60_descr', 55, @$Im60_descr, true, 'text', 3, '');
           ?>
           &nbsp;&nbsp;&nbsp;&nbsp;
           <input type='button' value='Lan&ccedil;ar' name='lancar_medicamento' id='lancar_medicamento'
             onclick="js_incluir_item_medicamento();">
         </td>
       </tr>
       <tr>
         <td>
           &nbsp;
         </td>
         <td>
           <select multiple size='8' name='select_medicamento[]' id='select_medicamento' style="width: 80%;"
             onDblClick="js_excluir_item_medicamento();">
           </select>
         </td>
       </tr>
       <tr>
         <td align="right">
           <b>Idade:</b>
         </td>
         <td>
           <?
           db_input('idade_inicio', 3, "", true, 'text', 1);
           echo' <b>Até</b> ';
           db_input('idade_fim', 3, "", true, 'text', 1);
           ?>
         </td>
       </tr>
       <tr>
         <td align="right">
           <b>Dispensação:</b>
         </td>
         <td>
           <?
           $aDisp = array("1" => "PENDENTE", "2" => "NÃO CONSTA");
           db_select('iDispensacao', $aDisp, true, ""); 
           ?>
         </td>
       </tr>
       <tr>
         <td align="right">
           <b>Tratamento sem movimentação:</b>
         </td>
         <td>
           <?
           $aX = array('1' => 'Apresentar junto com os outros', '2' => 'Não apresente os pacientes', 
                       '3' => 'Apresentar somente os sem movimentação'
                      );
           db_select('iMovimentacao', $aX, true, ""); 
           ?>
         </td>
       </tr>
     </table>
  </fieldset>
  <br>
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
 </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
?>

</body>
</html>
<script>

function js_incluir_item_medicamento() {

  var texto = document.form1.m60_descr.value;
  var valor = document.form1.fa01_i_codigo.value;
  if (texto != "" && valor != "") {

    F         = document.getElementById("select_medicamento");
    var tam   = F.length;
    var testa = false;
    for (x = 0; x < F.length; x++) {

      if (F.options[x].value == valor) {

        testa = true;
        break;

      }

    }
    if (testa == false) {

      F.options[tam] = new Option(texto,valor);
      for (i = 0; i < F.length; i++) {

        F.options[i].selected = false;

      }
      F.options[tam].selected = true;
    }

  }
  document.form1.m60_descr.value     = '';
  document.form1.fa01_i_codigo.value = '';

}

function js_excluir_item_medicamento() {

  F = document.getElementById("select_medicamento");
  if (F.length == 1) {
    F.options[0].selected = true;
  }
  var SI = F.selectedIndex;
  if (F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
    if (SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}

function js_pesquisafa01_i_medicamento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_far_matersaude',
                        'func_far_matersaude.php'+
                        '?funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr',
                        'Pesquisa',
                        true,
                        3
                       );

  } else {

    if (document.form1.fa01_i_codigo.value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_far_matersaude',
                          'func_far_matersaude.php'
                          +'?pesquisa_chave='+document.form1.fa01_i_codigo.value+
                          '&funcao_js=parent.js_mostramatersaude',
                          'Pesquisa', false
                         );

    } else {
      document.form1.m60_descr.value = "";
    }

  }

}

function js_mostramatersaude(chave, erro) {

  document.form1.m60_descr.value = chave; 
  if (erro == true) {

    document.form1.fa01_i_codigo.focus(); 
    document.form1.fa01_i_codigo.value = '';

  } else {
    document.form1.m60_descr.value=chave;
  }

}

function js_mostramatersaude1(chave1, chave2) {

  document.form1.fa01_i_codigo.value = chave1;
  document.form1.m60_descr.value     = chave2;
  db_iframe_far_matersaude.hide();
  
}

function js_validadata() {

  iVal = 0;
  if (document.form1.idade_inicio.value != '' && document.form1.idade_inicio.value >= 0) {
    iVal++;
  }
  if (document.form1.idade_fim.value != '' && document.form1.idade_fim.value > 0) {
    iVal++;
  }
  if (iVal == 2) {

    if (document.form1.idade_fim.value < document.form1.idade_inicio.value) {
      iVal = 1;
    }

  }
  if (iVal == 1) {

    alert('Idade Invalida!');
    return false;

  }
  return true;

}
function js_mandadados() {

  if (js_validadata()) {

    if (document.form1.select_medicamento.length > 0) {

      medicamentos = '&sMedicamentos=';
      vir = '';
      for (x = 0; x < document.form1.select_medicamento.length; x++) {

        medicamentos += vir + document.form1.select_medicamento.options[x].value;
        vir = ',';

      }

    } else {

      alert('Selecione um medicamento!');
      return false;

    }
    sDisp = '&iDispensacao='+document.form1.iDispensacao.value;
    sMov  = '&iMovimentacao='+document.form1.iMovimentacao.value;
    if (document.form1.idade_inicio.value != '') {
      idade = '&iIdadeIni='+document.form1.idade_inicio.value+'&iIdadeFim='+idade_fim.value;
    } else {
      idade = ''
    }
    jan = window.open('far2_medcontutil002.php?'+medicamentos+idade+sDisp+sMov,
                      '',
                      'width='+(screen.availWidth-5)+
                      ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                     );
    jan.moveTo(0, 0);

  }

}
</script>