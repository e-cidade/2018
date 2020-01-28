<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");


$clrotulo = new rotulocampo();
$clrotulo->label('v13_certid');
$clrotulo->label('v15_observacao');

?>
<html>
<head>
  <? 
    db_app::load('scripts.js, estilos.css');
  ?>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>

<body bgcolor=#CCCCCC>
<form class="container" name="form1" id="form1">
  <fieldset>
    <legend>Cancela CDA Geral</legend>    
    <table class="form-container">
      <tr>
        <td title="<?=$Tv13_certid?>">        
          <?
            db_ancora("Certidão:","js_pesquisa_certid_ini(true);",1); 
          ?>        
        </td>
        <td>
          <strong>
          <?
            db_input("v13_certidini",6,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid_ini(false);'");

            db_ancora("até","js_pesquisa_certid_fim(true);",1);
            
            db_input("v13_certidfim",6,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid_fim(false);'");
          ?>
          </strong>
        </td>        
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">  
            <legend>Observação</legend>            
              <?php    
                db_textarea('v15_observacao', 10, 100, $Iv15_observacao, true, 'text', 1, '','','',500);                
              ?>            
          </fieldset>
        </td>
      </tr>
    </table>    
  </fieldset>  
  
    <input type="button" value="Processar" onclick="js_processar()">

  
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</form>
<script>

function js_processar() {

  iCertidaoInicial = document.form1.v13_certidini.value;

  iCertidaoFinal   = document.form1.v13_certidfim.value;

  sObservacao      = document.form1.v15_observacao.value;

  if (iCertidaoInicial == '') {
    alert('Certidão de inicio não informada.');
    return false;
  }

  if (iCertidaoFinal == '') {
    alert('Certidão de inicio não informada.');
    return false;
  }
  
  js_OpenJanelaIframe('top.corpo','db_iframe_anulacda','div4_cancelcdageral002.php?certidaoinicial='+iCertidaoInicial+'&certidaofinal='+iCertidaoFinal+'&observacao='+sObservacao, 'Processando Anulações', true);
}

function js_fecharJanela() {
  
  db_iframe_anulacda.hide();

  window.location = 'div4_cancelcdageral001.php';
  
}
function js_pesquisa_certid_ini(mostra){
  var certid=document.form1.v13_certidini.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_certid','func_certid.php?funcao_js=parent.js_mostracertid_ini1|0','Pesquisa',true);
  }else{
    if(document.form1.v13_certidini.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_certid','func_certid.php?pesquisa_chave='+document.form1.v13_certidini.value+'&funcao_js=parent.js_mostracertid_ini','Pesquisa',false);
      document.form1.v13_certidfim.value = document.form1.v13_certidini.value; 
    }else{
      document.form1.v13_certidini.value = ''; 
    }
  }
}
function js_mostracertid_ini(chave,erro){
  if(erro==true){ 
    document.form1.v13_certidini.value = ''; 
    document.form1.v13_certidini.focus(); 
  }
}
function js_mostracertid_ini1(chave1){
  document.form1.v13_certidini.value = chave1;
  document.form1.v13_certidfim.value = chave1;
  db_iframe_certid.hide();
}

function js_pesquisa_certid_fim(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_certid','func_certid.php?funcao_js=parent.js_mostracertid_fim1|v13_certid','Pesquisa',true);
  }else{
    if(document.form1.v13_certidfim.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_certid','func_certid.php?pesquisa_chave='+document.form1.v13_certidfim.value+'&funcao_js=parent.js_mostracertidfim','Pesquisa',false);
    }else{
      document.form1.v13_certidfim.value = ''; 
    }
  }
}

function js_mostracertidfim(chave,erro){
  if(erro==true){ 
    document.form1.v13_certidfim.value = ''; 
    document.form1.v13_certidfim.focus(); 
  }
}

function js_mostracertid_fim1(chave1){
  document.form1.v13_certidfim.value = chave1;
  db_iframe_certid.hide();
}

function js_testacampo(func){
  if (document.form1.v13_certidini.value==""){
    db_msgbox('Informe uma Certidão!!');
    document.form1.v13_certidini.focus(); 
    return false;
  }else{
    return true;
  }
  
}
</script>

</body>
</html>
<script>

$("v13_certidini").addClassName("field-size2");
$("v13_certidfim").addClassName("field-size2");

</script>