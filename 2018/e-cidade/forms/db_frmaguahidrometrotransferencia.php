<?php
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

require_once("libs/db_app.utils.php");
require_once("classes/db_aguahidromatric_classe.php");

db_app::load('scripts.js, prototype.js, strings.js');
db_app::load('estilos.css');

$claguahidromatric = new cl_aguahidromatric();
$claguahidromatric->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("x15_diametro");
$clrotulo->label("x03_nomemarca");
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_nome');
?>
<style>
fieldset {
  margin: 4px;
}
.fieldsetinterno {
  border: 0px;
  border-top: 2px groove white;
  margin-top: 10px;
}
td {
  white-space: nowrap;
}
form > fieldset > fieldset {
  text-align: left;  
}
form > fieldset > fieldset > table {
  padding-left: 11px;  
}
fieldset table tr td:first-child {
   width: 100px;
}
</style>
<div class="container">
  <form class='form' name="form1" method="post" action="">
    <fieldset>
      <legend>
        <b>Tranfer&ecirc;ncia de hidr&ocirc;metro</b>
      </legend>
      <fieldset style="margin-top: 8px;">
        <legend>
          <b>Origem</b>
        </legend>
        <table class='table-container'>
          <tr>
            <td title="<?=@$Tx04_matric?>">
              <?php db_ancora(@$Lx04_matric,"js_pesquisa_x04_matric_origem(true);",$db_opcao); ?>
            </td>
            <td>
              <?php 
                db_input('x04_matric_origem',6,$Ix04_matric,true,'text',$db_opcao," onchange='js_pesquisa_x04_matric_origem(false);'",'x04_matric_origem');
                db_input('z01_nome_origem',40,$Iz01_nome,true,'text',3,'','z01_nome_origem');
              ?>
            </td>
          </tr>
        </table>
        <fieldset class='fieldsetinterno'>
          <legend>
            <b>Hidr&ocirc;metro</b>
          </legend>
          <table>
            <tr>
              <td title="<?=@$Tx04_nrohidro?>">
                <?=@$Lx04_nrohidro?>
              </td>
              <td>
              <?php
                db_input('x04_nrohidro_origem',30,$Ix04_nrohidro,true,'text',3,'','x04_nrohidro_origem');
                db_input('x04_codhidrometro_origem',6,$Ix04_codhidrometro,true,'hidden',3,'','x04_codhidrometro_origem');
              ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tx04_qtddigito?>">
                <?php db_ancora(@$Lx04_qtddigito,"",3); ?>
              </td>
              <td>
                <?php db_input('x04_qtddigito_origem',12,$Ix04_qtddigito,true,'text',3,'','x04_qtddigito_origem'); ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tx03_nomemarca?>">
                <?php db_ancora(@$Lx03_nomemarca,"",3); ?>
              </td>
              <td>
                <?php db_input('x03_nomemarca_origem',30,$Ix03_nomemarca,true,'text',3,'','x03_nomemarca_origem'); ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tx15_diametro?>">
                <?=@$Lx15_diametro?>
              </td>
              <td>
                <?php db_input('x15_diametro_origem',12,$Ix15_diametro,true,'text',3,'','x15_diametro_origem'); ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tx04_dtinst?>">
                <?=@$Lx04_dtinst?>
              </td>
              <td>
                <?php db_inputdata('x04_dtinst_origem',@$x21_dtleituraant_dia,@$x21_dtleituraant_mes,@$x21_dtleituraant_ano,true,'text',3,'','x04_dtinst_origem'); 
                //db_input('x04_dtinst_origem',8,$Ix04_dtinst,true,'text',3,'','x04_dtinst_origem'); ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tx04_leitinicial?>">
                <?=@$Lx04_leitinicial?>
              </td>
              <td> 
                <?php db_input('x04_leitinicial_origem',8,$Ix04_leitinicial,true,'text',3,'','x04_leitinicial_origem'); ?>
              </td>
            </tr>            
			      <tr>
			        <td title="<?=@$Tx04_observacao?>">
			          <?=@$Lx04_observacao?>
			        </td>
			        <td>
			          <?php db_textarea('x04_observacao_origem',4,47,$Ix04_observacao,true,'text',3,'','x04_observacao_origem'); ?>
			        </td>
			      </tr>
          </table>
        </fieldset>
      </fieldset>
      <br />
      <fieldset>
        <legend>
          <b>Destino</b>
        </legend>
        <table class='table-container'>
          <tr>
            <td title="<?= @$Tx04_matric ?>"><?php db_ancora(@$Lx04_matric,"js_pesquisa_x04_matric_destino(true);",$db_opcao); ?>
            </td>
            <td>
              <?php 
                db_input('x04_matric_destino',6,$Ix04_matric,true,'text',$db_opcao," onchange='js_pesquisa_x04_matric_destino(false);'",'x04_matric_destino');
                db_input('z01_nome_destino',40,$Iz01_nome,true,'text',3,'','z01_nome_destino'); 
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </fieldset>
    <div class='btn-container' style="margin-top: 25px;">
      <input class='btn' name="<?= ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir")) ?>" type="submit" id="db_opcao"
        value="<?= ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir")) ?>" <?= ($db_botao==false?"disabled":"") ?>>
    </div>
  </form>
</div>
<script type="text/javascript" language="javascript">
  
  function js_pesquisa_x04_matric_origem(mostra) {
        
    if (mostra == true) {
        
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_aguabase',
                          'func_aguabasealt.php?funcao_js=parent.js_mostra_dados_matricula_origem1|x01_matric|z01_nome|x04_matric',
                          'Pesquisa',true);
    	
    } else {
      
      if (document.form1.x04_matric_origem.value != '') {

    	  js_OpenJanelaIframe('top.corpo',
                            'db_iframe_aguabase',
                            'func_aguabasealt.php?pesquisa_chave=' + document.form1.x04_matric_origem.value + 
                            '&funcao_js=parent.js_mostra_dados_matricula_origem',                  
                            'Pesquisa',false);

      } else {
          
        document.form1.z01_nome_origem.value = '';
        js_limpa_dados_hidrometro();
         
      }
      
    }
    
  }
  
  function js_mostra_dados_matricula_origem (chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,erro){

	  if(chave1 == ""){
         
      document.form1.x04_matric_origem.value = ''; 
      document.form1.z01_nome_origem.value   = "";
      js_limpa_dados_hidrometro();
      alert("Matrícula não encontrada.");
      document.form1.x04_matric_origem.focus();
        
    }else{
        
    	document.form1.z01_nome_origem.value   = chave2;
      js_retorna_dados_hidrometro_origem(document.form1.x04_matric_origem.value);
    	
    }

  }
  
  function js_mostra_dados_matricula_origem1(chave1,chave2,chave3) {

    if(chave1 != ""){

      document.form1.x04_matric_origem.value = chave1;
      document.form1.z01_nome_origem.value = chave2;
      js_retorna_dados_hidrometro_origem(chave1);      
      db_iframe_aguabase.hide();
      document.form1.x04_matric_origem.focus();

    }else{

      document.form1.x04_matric_origem.value = ''; 
      document.form1.z01_nome_origem.value   = "";
      js_limpa_dados_hidrometro();
      alert("Matrícula não encontrada.");
      document.form1.x04_matric_origem.focus();

    }
    
  }

  function js_retorna_dados_hidrometro_origem(matricula){

    var oParam                  = new Object();
    oParam.sExec                = 'getDadosHidrometro';
    oParam.iMatric              = matricula;

    var oAjax = new Ajax.Request("agu4_aguahidrometro.RPC.php",
                                 {
                                  method    : 'POST',
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoProcessamento_origem
                                 });
    
  }

  function js_retornoProcessamento_origem(oAjax) {

    var oGet = js_urlToObject();
    
    var oRetorno  = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {

      document.form1.x04_nrohidro_origem.value      = oRetorno.aDadosHidrometro[0]['x04_nrohidro'].urlDecode();
      document.form1.x04_codhidrometro_origem.value = oRetorno.aDadosHidrometro[0]['x04_codhidrometro'].urlDecode();
      document.form1.x04_qtddigito_origem.value     = oRetorno.aDadosHidrometro[0]['x04_qtddigito'].urlDecode();
      document.form1.x03_nomemarca_origem.value     = oRetorno.aDadosHidrometro[0]['x03_nomemarca'].urlDecode();
      document.form1.x15_diametro_origem.value      = oRetorno.aDadosHidrometro[0]['x15_diametro'].urlDecode();
      document.form1.x04_leitinicial_origem.value   = oRetorno.aDadosHidrometro[0]['x04_leitinicial'].urlDecode();
      document.form1.x04_observacao_origem.value    = oRetorno.aDadosHidrometro[0]['x04_observacao'].urlDecode();
      document.form1.x04_dtinst_origem.value        = js_formatar(oRetorno.aDadosHidrometro[0]['x04_dtinst'].urlDecode(),'d');
        
    } else {

      alert(oRetorno.message);
      js_limpa_dados_hidrometro();      
      document.form1.x04_matric_origem.focus();
            
    }
    
  }

  function js_limpa_dados_hidrometro() {

    document.form1.x04_matric_origem.value        = ''; 
    document.form1.z01_nome_origem.value          = '';
    document.form1.x04_nrohidro_origem.value      = '';
    document.form1.x04_codhidrometro_origem.value = '';
    document.form1.x04_qtddigito_origem.value     = '';
    document.form1.x03_nomemarca_origem.value     = '';
    document.form1.x15_diametro_origem.value      = '';
    document.form1.x04_leitinicial_origem.value   = '';
    document.form1.x04_observacao_origem.value    = '';
    document.form1.x04_dtinst_origem.value        = '';
	    
  }

  function js_pesquisa_x04_matric_destino(mostra) {
      
    if (mostra == true) {
        
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_aguabase',
                          'func_aguabasealt.php?funcao_js=parent.js_mostra_dados_matricula_destino1|x01_matric|z01_nome|x04_matric',
                          'Pesquisa',true);
      
    } else {
      
      if (document.form1.x04_matric_destino.value != '') {

        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_aguabase',
                            'func_aguabasealt.php?pesquisa_chave=' + document.form1.x04_matric_destino.value + 
                            '&funcao_js=parent.js_mostra_dados_matricula_destino',                  
                            'Pesquisa',false);

      } else {
          
        document.form1.z01_nome_destino.value = '';
         
      }
      
    }
    
  }
    
  function js_mostra_dados_matricula_destino (chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,erro){

	  if(chave1 == ""){
         
      document.form1.x04_matric_destino.value = ''; 
      document.form1.z01_nome_destino.value   = "";
      alert("Matrícula não encontrada.");
      document.form1.x04_matric_destino.focus();
        
    }else{
        
    	document.form1.z01_nome_destino.value   = chave2;
      js_retorna_dados_hidrometro_destino(document.form1.x04_matric_destino.value);
    	
    }

  }
  
  function js_mostra_dados_matricula_destino1(chave1,chave2,chave3) {

    if(chave1 != ""){

      document.form1.x04_matric_destino.value = chave1;
      document.form1.z01_nome_destino.value = chave2;
      js_retorna_dados_hidrometro_destino(chave1);      
      db_iframe_aguabase.hide();
      document.form1.x04_matric_destino.focus();

    }else{

      document.form1.x04_matric_destino.value = ''; 
      document.form1.z01_nome_destino.value   = "";
      alert("Matrícula não encontrada.");
      document.form1.x04_matric_destino.focus();

    }
    
  }

  function js_retorna_dados_hidrometro_destino(matricula){

    var oParam                  = new Object();
    oParam.sExec                = 'getDadosHidrometro';
    oParam.iMatric              = matricula;

    var oAjax = new Ajax.Request( "agu4_aguahidrometro.RPC.php",
                                  {
                                    method    : 'POST',
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoProcessamento_destino
                                  }
                                );
      
  }

  function js_retornoProcessamento_destino(oAjax) {

    var oGet = js_urlToObject();
    
    var oRetorno  = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {

      document.form1.x04_matric_destino.value = ''; 
      document.form1.z01_nome_destino.value   = "";
      alert("Matrícula já possui hidrômetro.");
      document.form1.x04_matric_destino.focus();
            
    }
    
  }

  function js_limpa_formulario() {

    document.form1.x04_matric_origem.value = ''; 
    document.form1.z01_nome_origem.value   = "";
    document.form1.x04_matric_destino.value = ''; 
    document.form1.z01_nome_destino.value   = "";
    js_limpa_dados_hidrometro();
            
  }
  
</script>