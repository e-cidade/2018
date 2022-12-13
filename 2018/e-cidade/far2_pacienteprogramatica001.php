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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
//include("classes/db_cgs_und_classe.php");
db_postmemory($HTTP_POST_VARS);

//$clcgs_und = new cl_cgs_und;
$clrotulo = new rotulocampo;
$clrotulo->label("fa10_i_programa");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>  
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
    <table width='70%'>
      <tr>
        <td>
          <fieldset style="width:100%"><legend align='left'><b>A&ccedil;&otilde;es Program&aacute;ticas</b></legend>
            <table  border="0"  align="center" width='100%'>
              <tr>
                <td width='15%' align='right'>
                  <?
                  db_ancora(@$Lfa10_i_programa,"js_pesquisafa12_i_programa(true);","");
                  ?>
                </td>
                <td>
                  <?
                  db_input('fa12_i_codigo',10,@$Ifa12_i_codigo,true,'text',1," onchange='js_pesquisafa12_i_programa(false);'");
                  db_input('fa12_c_descricao',55,@$Ifa12_c_descricao,true,'text',3,"");
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='button' value='Lan&ccedil;ar' name='lancar_programa' id='lancar_programa'>
                </td>
              </tr>

              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                  <select multiple size='8' name='select_programa[]' id='select_programa' style="width: 80%;" onDblClick="js_excluir_item_programa();">
                  </select>
                </td>
              </tr>

              <tr>
                <td height='50' align='right'>
                  <b>Ordenar por:</b>
                </td>
                <td height='50' align='left'>
                  <select name='ordem' id='ordem'>
                    <option value='1' selected>Nome</option>
                    <option value='2'>CGS</option>
                  </select>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </center>
<center>
  <br>
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>

function js_incluir_item_programa(){
  var texto=document.form1.fa12_c_descricao.value;
  var valor=document.form1.fa12_i_codigo.value;
  if(texto != "" && valor != "")
  {
    var F = document.getElementById("select_programa");
    var valor_default_novo_option = F.length;
    var testa = false;
    for(var x = 0; x < F.length; x++)
    {
      if(F.options[x].value == valor)
      {
        testa = true;
        break;
      }
    }
    if(testa == false)
    {
      F.options[valor_default_novo_option] = new Option(texto,valor);
      for(i=0;i<F.length;i++)
      {
        F.options[i].selected = false;
      }
      F.options[valor_default_novo_option].selected = true;
      //js_trocacordeselect();
    }
  }
  texto=document.form1.fa12_c_descricao.value="";
  valor=document.form1.fa12_i_codigo.value="";
  document.form1.lancar_programa.onclick = '';
}

function js_excluir_item_programa(){
  var F = document.getElementById("select_programa");
  if(F.length == 1)
    F.options[0].selected = true;
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0)
  {
    F.options[SI] = null;
    //js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}

function js_pesquisafa12_i_programa(mostra){
  if(mostra==true)
    js_OpenJanelaIframe('top.corpo','db_iframe_far_programa','func_far_programa.php?funcao_js=parent.js_mostrafar_programa1|fa12_i_codigo|fa12_c_descricao','Pesquisa',true);
  else
  {
     if(document.form1.fa12_i_codigo.value != '')
     { 
        js_OpenJanelaIframe('top.corpo','db_iframe_far_programa','func_far_programa.php?pesquisa_chave='+document.form1.fa12_i_codigo.value+'&funcao_js=parent.js_mostrafar_programa','Pesquisa',false);
     }
     else
     {
       document.form1.fa12_i_codigo.value = ''; 
     }
  }
}

function js_mostrafar_programa(chave,erro){
  document.form1.fa12_c_descricao.value = chave; 
  if(erro==true)
  { 
    document.form1.fa12_i_codigo.focus(); 
    document.form1.fa12_i_codigo.value = ''; 
  }
  else
    document.form1.lancar_programa.onclick = js_incluir_item_programa;
}

function js_mostrafar_programa1(chave1,chave2){
  document.form1.fa12_i_codigo.value = chave1;
  document.form1.fa12_c_descricao.value = chave2;
  db_iframe_far_programa.hide();
  document.form1.lancar_programa.onclick = js_incluir_item_programa;
}

function js_validaenvio(){
  if(document.form1.select_programa.length <= 0)
  {
    alert('Selecione ao menos um programa.');
    return false;
  }
  return true;
}

function js_mandadados(){
 
  if(js_validaenvio())
  { 
    vir = '';
    programas = 'programas=';
    ordem = '&ordem='+document.form1.ordem.value; 
 
    for(x=0;x<document.form1.select_programa.length;x++)
    {
      programas +=vir + document.form1.select_programa.options[x].value;
      vir = ',';
    }
    jan = window.open('far2_pacienteprogramatica002.php?'+programas+ordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}

</script>