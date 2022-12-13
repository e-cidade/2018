<?
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

//MODULO: juridico
$clprocessoforo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v53_descr");
$clrotulo->label("v75_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("v75_sequencial");
$clrotulo->label("v85_sequencial");
$clrotulo->label("v70_codforo");


?>
<form class="container" name="form1" method="post" action="">
	<?
	  db_input('v75_sequencial',10,$Iv75_sequencial,true,'hidden',3);
    db_input('v85_sequencial',10,$Iv85_sequencial,true,'hidden',3);
  ?>
  <fieldset>
    <legend>Procedimentos - Processo do Foro </legend>
		<table class="form-container">
	    <tr>
	      <td nowrap title="<?=@$Tv70_sequencial?>">
	         <?=@$Lv70_sequencial?>
	      </td>
	      <td> 
					<?
						db_input('v70_sequencial',10,$Iv70_sequencial,true,'text',3);
					?>
	      </td>
	    </tr>
      <tr>
        <td nowrap title="<?=@$Tv70_codforo?>">
           <?=@$Lv70_codforo?>
        </td>
        <td> 
          <?
            db_input('v70_codforo',10,$Iv70_codforo,true,'text',$db_opcao);
            if (isset($v70_codforo)) {
              $v85_processoforo = $v70_codforo;
            }
            db_input('v85_processoforo',10,"",true,'hidden',$db_opcao);
          ?>
        </td>
      </tr>
	    <tr>
	      <td nowrap title="<?=@$Tv70_vara?>">
	         <?
	          db_ancora(@$Lv70_vara,"js_pesquisav70_vara(true);",$db_opcao);
	         ?>
	      </td>
	      <td> 
					 <?
						db_input('v70_vara',10,$Iv70_vara,true,'text',$db_opcao," onchange='js_pesquisav70_vara(false);'");
						db_input('v53_descr',40,$Iv53_descr,true,'text',3,'');
	         ?>
	      </td>
	    </tr>
      <tr>
        <td nowrap title="<?=@$Tv70_valorinicial?>">
           <?=@$Lv70_valorinicial?>
        </td>
        <td> 
          <?
            db_input('v70_valorinicial',10,$Iv70_valorinicial,true,'text',$db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv70_data?>">
           <?=@$Lv70_data?>
        </td>
        <td> 
          <?
          
            if( $db_opcao == 1){
              $v70_data_dia = date("d");
              $v70_data_mes = date("m");
              $v70_data_ano = date("Y");
            }
          
            db_inputdata('v70_data',@$v70_data_dia, @$v70_data_mes, @$v70_data_ano, true, 'text', $db_opcao)
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv75_numcgm?>">
           <?
            db_ancora(@$Lv75_numcgm,"js_pesquisav75_numcgm(true);",$db_opcao);
           ?>
        </td>
        <td> 
           <?
            $v75_numcgm_ant = @$v75_numcgm;
            db_input('v75_numcgm',10,$Iv75_numcgm,true,'text',$db_opcao," onchange='js_pesquisav75_numcgm(false);'");
            db_input('v75_numcgm_ant',10,"",true,'hidden',$db_opcao);
            
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
           ?>
        </td>
      </tr>
      <tr>
        <td>
           <?
            db_ancora("Cartório","js_pesquisaCartorio(true);",$db_opcao);
           ?>
        </td>
        <td> 
           <?
            db_input('v82_sequencial',10,'',true,'text',$db_opcao," onchange='js_pesquisaCartorio(false);'");
            db_input('v82_descricao',40,'',true,'text',3,'');
           ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"> 
          <fieldset class="separator">
            <legend>
              <?
                if ($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 22) {
                  echo $Lv70_observacao;
                } else {
                  echo "<strong>Justificativa:</strong>";
                  $v70_observacao = '';
                }
              ?>
            </legend>
            <?
              db_textarea('v70_observacao', 5, 60, $Iv70_observacao, true, 'text', 1, '', '', '', 500);
            ?>
          </fieldset>
        </td>
      </tr>
	  </table>
  </fieldset>
				<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"anular"))?>" 
				       type="submit" id="db_opcao" onclick="return js_validar();"
				       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Anular"))?>" 
				       <?=($db_botao==false?"disabled":"")?> >
				<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>



function js_pesquisaCartorio(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_cartorio.php?funcao_js=parent.js_mostraCartorio1|v82_sequencial|v82_descricao';
    js_OpenJanelaIframe('', 'db_iframe_cartorio', sUrl, 'Pesquisa', true, 0);
  } else {

    if ($('v82_sequencial').value != '') {
      var sUrl = 'func_cartorio.php?pesquisa_chave='+document.form1.v82_sequencial.value+'&funcao_js=parent.js_mostraCartorio'; 
      js_OpenJanelaIframe('', 'db_iframe_cartorio', sUrl, 'Pesquisa', false, 0);
    } else {
      $('v82_descricao').value = ''; 
    }
  }
}
function js_mostraCartorio(chave1,chave2,erro) {

  $('v82_descricao').value = chave2;
  $('v82_sequencial').value = chave1; 

  if (erro == true || erro == undefined) {
   
    $('v82_sequencial').value = '';
    $('v82_descricao').value = '';
    $('v82_sequencial').focus(); 
  }
}

function js_mostraCartorio1(chave1,chave2) {
  $('v82_sequencial').value   = chave1;
  $('v82_descricao').value  = chave2;
  db_iframe_cartorio.hide();
}

function js_validar() {

  var sOpcao = $('db_opcao').value;

  if($F('v82_descricao') == ''){
    alert("Campo cartório não informado");
    $('v82_sequencial').focus();
    return false;
  }

  if (sOpcao == 'Anular') {
    
    if (!confirm(_M('tributario.juridico.db_frminicialcodforo.deseja_desvincular_todas_inicais'))) {
      return false;    
    }
  }
}

function js_pesquisav75_numcgm(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_cgm.php?funcao_js=parent.js_mostranumcgm1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', true, 0);
  } else {
  
    if ($('v75_numcgm').value != '') {
      var sUrl = 'func_cgm.php?pesquisa_chave='+document.form1.v75_numcgm.value+'&funcao_js=parent.js_mostranumcgm'; 
      js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', false, 0);
    } else {
      $('z01_nome').value = ''; 
    }
  }
}
function js_mostranumcgm(erro,chave) {

  $('z01_nome').value = chave; 
  if (erro == true) {
   
    $('v75_numcgm').value = ''; 
    $('v75_numcgm').focus(); 
  }
}

function js_mostranumcgm1(chave1,chave2) {

  $('v75_numcgm').value   = chave1;
  $('z01_nome').value  = chave2;
  db_iframe_numcgm.hide();
}



function js_pesquisav70_vara(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_vara.php?funcao_js=parent.js_mostravara1|v53_codvara|v53_descr';
    js_OpenJanelaIframe('', 'db_iframe_vara', sUrl, 'Pesquisa', true, 0);
  } else {
  
    if ($('v70_vara').value != '') {
      var sUrl = 'func_vara.php?pesquisa_chave='+document.form1.v70_vara.value+'&funcao_js=parent.js_mostravara'; 
      js_OpenJanelaIframe('', 'db_iframe_vara', sUrl, 'Pesquisa', false, 0);
    } else {
      $('v53_descr').value = ''; 
    }
  }
}

function js_mostravara(chave,erro) {

  $('v53_descr').value = chave; 
  if (erro == true) {
   
    $('v70_vara').value = ''; 
    $('v70_vara').focus(); 
  }
}

function js_mostravara1(chave1,chave2) {

  $('v70_vara').value   = chave1;
  $('v53_descr').value  = chave2;
  db_iframe_vara.hide();
}

function js_pesquisa() {

  var sUrl = 'func_processoforo.php?lAnuladas=false&funcao_js=parent.js_preenchepesquisa|v70_sequencial';
  js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', true, 0);
}

function js_preenchepesquisa(chave) {

  db_iframe_processoforo.hide();
  <?
	  if ($db_opcao != 1) {
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}
</script>
<script>

$("v70_sequencial").addClassName("field-size2");
$("v70_codforo").addClassName("field-size2");
$("v70_vara").addClassName("field-size2");
$("v53_descr").addClassName("field-size7");
$("v70_valorinicial").addClassName("field-size2");
$("v70_data").addClassName("field-size2");
$("v75_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("v82_sequencial").addClassName("field-size2");
$("v82_descricao").addClassName("field-size7");

</script>
