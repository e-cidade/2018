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

//MODULO: Acordos
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clacordogrupodocumento->rotulo->label();
$clrotulo->label("ac06_sequencial");
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac02_descricao");
$clrotulo->label("db82_descricao");

if (isset($db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao = true;
  if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)) {
  	
    $ac06_acordogrupo       = "";
    $ac06_tipodocumento     = "";
    $ac06_documentotemplate = "";
  }
} 
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Documentos</b></legend>
<table border="0" align="left">
  <tr>
    <td nowrap title="<?=@$Tac02_sequencial?>">
       <b>Grupo:</b>
    </td>
    <td> 
			<?
			  db_input('ac06_sequencial',10,$Iac06_sequencial,true,'hidden',3,"");
			  db_input('ac02_sequencial',10,$Iac02_sequencial,true,'text',3,"");
			?>
    </td>
    <td>
      <?
        db_input('ac02_descricao',40,@$Iac02_descricao,true,'text',3,"");
      ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tac06_documentotemplate?>">
      <?
        db_ancora("<b>Template:</b>","js_pesquisaac06_documentotemplate(true);",$db_opcao);
      ?>
    </td>
    <td>
			<?
			  db_input('ac06_documentotemplate',10,$Iac06_documentotemplate,true,'text',$db_opcao,
			           "  onchange='js_pesquisaac06_documentotemplate(false);'");
			?>
    </td>
    <td>
      <?
        db_input('db82_descricao',40,$Idb82_descricao,true,'text',3,"");
      ?>  
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tac06_tipodocumento?>">
       <?=@$Lac06_tipodocumento?>
    </td>
    <td colspan="2"> 
      <?        
        $aTipoDocumento = array("0"=>"Selecione ...",
                                "1"=>"Acordo",
                                "2"=>"Minuta",
                                "3"=>"Aditivo");
        
        db_select('ac06_tipodocumento',$aTipoDocumento,true,$db_opcao,
                  " onchange='js_desabilitaselecionar();' style='width:100%;'");

      ?>
    </td>
  </tr>
 </table>
</fieldset>
<table>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> onclick="return js_validarcampos();" >

      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
             <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?>>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
       $sCampos             = "acordogrupodocumento.ac06_sequencial,                       ";
       $sCampos            .= "case acordogrupodocumento.ac06_tipodocumento                ";
       $sCampos            .= "  when 1 then 'Acordo'                                      ";
       $sCampos            .= "  when 2 then 'Minuta'                                      ";
       $sCampos            .= "  when 3 then 'Aditivo'                                     ";
       $sCampos            .= "end as ac06_tipodocumento,                                  ";
       $sCampos            .= "db_documentotemplate.db82_descricao                         ";
       $sWhere              = "acordogrupodocumento.ac06_acordogrupo = {$ac02_sequencial}  ";
       $sSqlAcordoDocumento = $clacordogrupodocumento->sql_query(null,$sCampos,"acordogrupodocumento.ac06_sequencial",
                                                                 $sWhere);
                                                                 
			 $chavepri                                = array("ac06_sequencial"=>@$ac06_sequencial);
			 $cliframe_alterar_excluir->chavepri      = $chavepri;
			 $cliframe_alterar_excluir->sql           = $sSqlAcordoDocumento;
			 $cliframe_alterar_excluir->campos        = "ac06_sequencial,db82_descricao,ac06_tipodocumento";
			 $cliframe_alterar_excluir->legenda       = "Documentos Lançados";
			 $cliframe_alterar_excluir->iframe_height = "160";
			 $cliframe_alterar_excluir->iframe_width  = "530";
			 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
</form>
<script>
function js_desabilitaselecionar() {

  var iTipoDocumento = $('ac06_tipodocumento').value;
  if (iTipoDocumento != 0) {
    $('ac06_tipodocumento').options[0].disabled = true; 
  }
}
function js_validarcampos() {

  var iTemplate      = $('ac06_documentotemplate').value;
  var iTipoDocumento = $('ac06_tipodocumento').value;
  
  if (iTemplate == '') {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Selecione um Documento Template!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
  
  if (iTipoDocumento == 0) {
  
    var sMsg0 = "Usuario:\n\n";
    var sMsg1 = " Selecione um Tipo de Documento!\n\n";
    alert(sMsg0+sMsg1);
    return false;
  }
}
function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaac06_documentotemplate(mostra) {

  if (mostra==true) {
    var sUrl1 = 'func_db_documentotemplate.php?funcao_js=parent.js_mostradocumentotemplate1|db82_sequencial|db82_descricao';
    js_OpenJanelaIframe('','db_iframe_documentotemplate',sUrl1,'Pesquisa',true,'0');
  } else {
     if($('ac06_documentotemplate').value != '') { 
        var sUrl2 = 'func_db_documentotemplate.php?pesquisa_chave='+$('ac06_documentotemplate').value
                                                                   +'&funcao_js=parent.js_mostradocumentotemplate';
        js_OpenJanelaIframe('','db_iframe_documentotemplate',sUrl2,'Pesquisa',false,'0');
     }else{
       $('db82_descricao').value = ''; 
     }
  }
}
function js_mostradocumentotemplate(chave1,erro) {

  $('db82_descricao').value = chave1; 
  if (erro==true) { 
  
    $('ac06_documentotemplate').focus(); 
    $('ac06_documentotemplate').value = ''; 
    $('db82_descricao').value         = chave1;
  }
}
function js_mostradocumentotemplate1(chave1,chave2) {

  $('ac06_documentotemplate').value = chave1;
  $('db82_descricao').value         = chave2;
  db_iframe_documentotemplate.hide();
}
</script>