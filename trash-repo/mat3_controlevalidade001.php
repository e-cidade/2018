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
include("classes/db_matparam_classe.php");

db_postmemory($HTTP_POST_VARS);
$clmatparam = new cl_matparam;
$clmatparam->rotulo->label();

function calcula_data($data, $dias= 0, $meses = 0, $ano = 0)
{
  $data = explode("/", $data);
  $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] - $meses,   $data[0] - $dias, $data[2] - $ano));
  return $novadata;
}
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
</table>

<form name="form1" method="post" action="">
  <center>
    <table width='70%'>
      <tr> 
        <td>
          <fieldset style="width:100%"><legend align='left'><b>Materiais</b></legend>
            <table  border="0"  align="center" width='100%'>
              <tr>
                <td width='15%' align='right'>
                  <?
                  db_ancora("<b>Material:</b>","js_pesquisam60_codmater(true);","");
                  ?>
                </td>
                <td nowrap> 
                  <?
                  db_input('m60_codmater',10,"'C&oacute;digo do material'",true,'text',1," onchange='js_pesquisam60_codmater(false);'");
                  db_input('m60_descr',55,'Descri&ccedil;&atilde; do material',true,'text',3,'');
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='button' value='Lan&ccedil;ar' name='lancar_material' id='lancar_material'>
                </td>
              </tr>

              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                  <select multiple size='8' name='select_material[]' id='select_material' style="width: 80%;" onDblClick="js_excluir_item_material();">
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
    <table cellpadding='5' width='70%'>
      <tr>
        <td width='40%' align='center'>
          <b>Per&iacute;odo:</b>
          <?php
          // Calculo da data que vira preenchida por default
          $result_venc = $clmatparam->sql_record($clmatparam->sql_query("","m90_prazovenc","",""));
          db_fieldsmemory($result_venc,0);
          $data_atual = date("d/m/Y",db_getsession("DB_datausu"));
          //$data_ini = calcula_data($data_atual,$m90_prazovenc);
          $data_atual = explode('/',$data_atual);
          //$data_ini = explode('/',$data_ini);
          ?>
        </td>
        <td align='left'>
          <b>De</b> <?=db_inputdata('data_inicio',$data_atual[0],$data_atual[1],$data_atual[2],true,'text',1,"");?>&nbsp;&nbsp;
          <b>At&eacute;:</b> <?=db_inputdata('data_fim','','','',true,'text',1,"");?>
          <?=db_inputdata('data_atual',$data_atual[0],$data_atual[1],$data_atual[2],true,'hidden',3,"");?>
        </td>
      </tr>

      <tr>
        <td align='center'>
          <b>Situa&ccedil;&atilde;o</b>
        </td>
        <td align='left'>
          <?
						$x = array('1'=>'Todos','2'=>'Vencidos','3'=>'&Agrave; Vencer', '4'=>'No Prazo');
						db_select('situacao',$x,true,1,"style='width:200px;'");
					?>
        </td>
      </tr>
 
      <tr>
        <td align='center'>
          <b>Ordena&ccedil;&atilde;o:</b>
        </td>
        <td align='left'>
          <?
						$x = array('1'=>'C&oacute;digo','2'=>'Alfab&eacute;tica');
						db_select('ordenacao',$x,true,1,"style='width:200px;'");
					?>
        </td>
      </tr>

      <tr>
        <td align='center'>
          <b>Prazo &agrave; vencer em dias:</b>
        </td>
        <td align='left'>
          <?
        	  db_input('m90_prazovenc',10,@$Im90_prazovenc,true,'text',1,"")
					?>
        </td>
      </tr>

    </table>
    <br><br>
    <input  name="emite" id="emite" type="button" value="Processar" onclick="js_mandadados();" >
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>

function js_incluir_item_material() {

  var texto=document.form1.m60_descr.value;
  var valor=document.form1.m60_codmater.value;
  if(texto != "" && valor != "") {

    var F = document.getElementById("select_material");
    var valor_default_novo_option = F.length;
    var testa = false;
    for(var x = 0; x < F.length; x++) {
      if(F.options[x].value == valor) {

        testa = true;
        break;

      }
    }

    if(testa == false) {

      F.options[valor_default_novo_option] = new Option(texto,valor);
      for(i=0;i<F.length;i++) {
        F.options[i].selected = false;
      }
      F.options[valor_default_novo_option].selected = true;

    }

  }
  texto=document.form1.m60_descr.value = '';
  valor=document.form1.m60_codmater.value = '';
  document.form1.lancar_material.onclick = '';

}

function js_excluir_item_material() {

  var F = document.getElementById("select_material");
  if(F.length == 1) {
    F.options[0].selected = true;
  }
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
    if(SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}

function js_pesquisam60_codmater(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  } else {

    if(document.form1.m60_codmater.value != '') { 
      js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostramatmater','Controle de Validade',false);
    } else {
      document.form1.m60_descr.value = ''; 
    }

  }

}

function js_mostramatmater(chave,erro) {

  document.form1.m60_descr.value = chave; 
  if(erro == true) {

    document.form1.m60_codmater.focus(); 
    document.form1.m60_codmater.value = '';

  } else {
    
    document.form1.m60_descr.value=chave;
    document.form1.lancar_material.onclick = js_incluir_item_material;

  }

}

function js_mostramatmater1(chave1, chave2) {
  
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
  document.form1.lancar_material.onclick = js_incluir_item_material;

}

function js_validadata() {

  inicio = new Date(document.form1.data_inicio.value.substring(6,10),
                    document.form1.data_inicio.value.substring(3,5),
                    document.form1.data_inicio.value.substring(0,2));
  fim    = new Date(document.form1.data_fim.value.substring(6,10),
                    document.form1.data_fim.value.substring(3,5),
                    document.form1.data_fim.value.substring(0,2));

  if(document.form1.data_inicio.value == "" || document.form1.data_fim.value == "") {

    alert('ERRO: os campos data de inicio e de fim devem sem preenchidos.');
    document.form1.data_inicio.focus();
    return false;

  }

  if( inicio > fim) {

    alert('ERRO: A data de Inicio esta maior que a data de Fim.');
    document.form1.data_inicio.value = '';
    document.form1.data_fim.value = '';
    document.form1.data_inicio.focus();
    return false;

  }
                                   
  return true;

}

function js_validaenvio() {

  if(!js_validadata()) {
    return false;
  }

  return true;

}

function js_mandadados() {
 
  if(js_validaenvio()) {

    vir = '';
    data_atual = '&data_atual='+document.getElementById('data_atual').value;
    m90_prazovenc = '&m90_prazovenc='+document.getElementById('m90_prazovenc').value;
    situacao = '&situacao='+document.getElementById('situacao').value;
    ordenacao = '&ordenacao='+document.getElementById('ordenacao').value;
    datas = '&datas='+document.getElementById('data_inicio').value+','+document.getElementById('data_fim').value;
    departamento = '&departamento='+'<?=db_getsession('DB_coddepto')?>';
    nome_departamento = '&nome_departamento='+'<?=db_getsession('DB_nomedepto')?>';
    materiais = '&materiais=';
 
    for(x = 0; x < document.form1.select_material.length; x++) {

      materiais += vir + document.form1.select_material.options[x].value;
      vir = ',';

    }

    js_OpenJanelaIframe('top.corpo','db_iframe_controlevalidade','mat3_controlevalidade002.php?'+departamento+datas+materiais+situacao+ordenacao+data_atual+m90_prazovenc+nome_departamento,'Pesquisa',true);

  }

}

</script>