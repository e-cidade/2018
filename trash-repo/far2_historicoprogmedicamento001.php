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
$clrotulo->label("fa06_i_matersaude");


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
            </table>
          </fieldset>
        </td>
      </tr>

      <tr> 
        <td>
          <fieldset style="width:100%"><legend align='left'><b>Medicamentos</b></legend>
            <table  border="0"  align="center" width='100%'>
              <tr>
                <td width='15%' align='right'>
                  <?
                  db_ancora(@$Lfa06_i_matersaude,"js_pesquisafa01_i_medicamento(true);","");
                  ?>
                </td>
                <td>
                  <?
                  db_input('fa01_i_codigo',10,@$Ifa01_i_codigo,true,'text',1," onchange='js_pesquisafa01_i_medicamento(false);'");
                  db_input('m60_descr',55,@$Im60_descr,true,'text',3,'');
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='button' value='Lan&ccedil;ar' name='lancar_medicamento' id='lancar_medicamento'>
                </td>
              </tr>

              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                  <select multiple size='8' name='select_medicamento[]' id='select_medicamento' style="width: 80%;" onDblClick="js_excluir_item_medicamento();">
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
  <?
  echo '<b>Per&iacute;odo:</b> '; db_inputdata('data_inicio','','','',true,'text',1,""); echo '&nbsp;&nbsp;';
  echo ' <b>At&eacute;:</b> '; db_inputdata('data_fim','','','',true,'text',1,"");
  ?>
  <br><br>
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>

function js_incluir_item_medicamento(){
  var texto=document.form1.m60_descr.value;
  var valor=document.form1.fa01_i_codigo.value;
  if(texto != "" && valor != "")
  {
    var F = document.getElementById("select_medicamento");
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
  texto=document.form1.m60_descr.value="";
  valor=document.form1.fa01_i_codigo.value="";
  document.form1.lancar_medicamento.onclick = '';
}

function js_excluir_item_medicamento(){
  var F = document.getElementById("select_medicamento");
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

function js_pesquisafa01_i_medicamento(mostra){
  prescricao = '';
  if(mostra==true)
    js_OpenJanelaIframe('','db_iframe_far_matersaude','func_far_matersaude.php?'+prescricao+'funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr','Pesquisa',true,3);
  else
  {
    if(document.form1.fa01_i_codigo.value != '')
    { 
      js_OpenJanelaIframe('','db_iframe_far_matersaude','func_far_matersaude.php?'+prescricao+'pesquisa_chave='+document.form1.fa01_i_codigo.value+'&funcao_js=parent.js_mostramatersaude','Pesquisa',false);
    }
    else
	    document.form1.m60_descr.value = "";
  }
}

function js_mostramatersaude(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true)
  { 
    document.form1.fa01_i_codigo.focus(); 
    document.form1.fa01_i_codigo.value = ''; 
  }
  else
  {
    document.form1.m60_descr.value=chave;
    document.form1.lancar_medicamento.onclick = js_incluir_item_medicamento;
  }
}

function js_mostramatersaude1(chave1,chave2)
{
  document.form1.fa01_i_codigo.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_far_matersaude.hide();
  document.form1.lancar_medicamento.onclick = js_incluir_item_medicamento;

}

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

function js_validadata(){
  inicio = new Date(document.form1.data_inicio.value.substring(6,10),
                    document.form1.data_inicio.value.substring(3,5),
                    document.form1.data_inicio.value.substring(0,2));
  fim    = new Date(document.form1.data_fim.value.substring(6,10),
                    document.form1.data_fim.value.substring(3,5),
                    document.form1.data_fim.value.substring(0,2));

  if(document.form1.data_inicio.value == "" || document.form1.data_fim.value == "")
  {
    alert('ERRO: os campos data de inicio e de fim devem sem preenchidos.');
    document.form1.data_inicio.focus();
    return false;
  }

  if( inicio > fim)
  {
    alert('ERRO: A data de Inicio esta maior que a data de Fim.');
    document.form1.data_inicio.value = '';
    document.form1.data_fim.value = '';
    document.form1.data_inicio.focus();
    return false;
  }
                                   
  return true;
}

function js_validaenvio(){
  if(document.form1.select_programa.length <= 0)
  {
    alert('Selecione ao menos um programa.');
    return false;
  }
  if(!js_validadata())
    return false;

  return true;
}

function js_mandadados(){
 
  if(js_validaenvio())
  { 
    vir = '';
    datas = '&datas='+document.form1.data_inicio.value+','+document.form1.data_fim.value;
    programas = 'programas=';
    medicamentos = '&medicamentos=';
    nomes_programas = '&nomes_programas=';
 
    for(x=0;x<document.form1.select_programa.length;x++)
    {
      programas += vir + document.form1.select_programa.options[x].value;
      nomes_programas += vir + document.form1.select_programa.options[x].innerHTML;
      vir = ',';
    }
    
    vir = '';
    for(x=0;x<document.form1.select_medicamento.length;x++)
    {
      medicamentos += vir + document.form1.select_medicamento.options[x].value;
      vir = ',';
    }

    jan = window.open('far2_historicoprogmedicamento002.php?'+programas+datas+medicamentos+nomes_programas,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}

</script>