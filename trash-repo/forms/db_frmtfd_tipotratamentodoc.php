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

//MODULO: TFD
$cltfd_tipotratamentodoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf07_c_descr");
$clrotulo->label("tf04_c_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf06_i_codigo?>">
      <?=@$Ltf06_i_codigo?>
    </td>
    <td> 
      <?
      db_input('tf06_i_codigo',10,$Itf06_i_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf06_i_tipotratamento?>">
      <?
      db_ancora(@$Ltf06_i_tipotratamento,"js_pesquisatf06_i_tipotratamento(true);",3);
      ?>
    </td>
    <td> 
      <?
      db_input('tf06_i_tipotratamento',10,'',true,'text',3,'');
      db_input('tf04_c_descr',50,'',true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf06_i_documento?>">
      <?
      db_ancora(@$Ltf06_i_documento,"js_pesquisatf06_i_documento(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('tf06_i_documento',10,$Itf06_i_documento,true,'text',$db_opcao," onchange='js_pesquisatf06_i_documento(false);'");
      db_input('tf07_c_descr',50,$Itf07_c_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf06_i_obrigatorio?>">
      <?=@$Ltf06_i_obrigatorio?>
    </td>
    <td>
      <?
      $aX = array('1'=>'SIM', '2'=>'NÃO');
      db_select('tf06_i_obrigatorio',$aX,true,$db_opcao,'');
      ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" <?=(!isset($opcao)?"disabled":"")?> >
<input name="novo" type="button" id="novo" value="Novo Tratamento" onclick="js_novo()">
</form>

  <table width="100%">
	  <tr>
		  <td valign="top"><br>
        <?
				$aChavepri = array ('tf06_i_codigo' => @$tf06_i_codigo,
                            'tf06_i_documento' => @$tf06_i_documento, 
                            'tf06_i_tipotratamento' => @$tf06_i_tipotratamento, 
                            'tf06_i_obrigatorio' => @$tf06_i_obrigatorio, 
                            'tf07_c_descr' => @$tf07_c_descr);
				$oIframeAE->chavepri = $aChavepri;

        $sCampos = 
        " tf06_i_codigo,
          tf06_i_documento,
          tf06_i_tipotratamento,
          tf07_c_descr,
          case when tf06_i_obrigatorio = 1
            then 
              'SIM'
            else 
              'NÃO'
          end as tf06_i_obrigatorio ";
        
				$oIframeAE->sql = $cltfd_tipotratamentodoc->sql_query(null, $sCampos, ' tf06_i_codigo ',
                                                              " tf06_i_tipotratamento = $tf06_i_tipotratamento ");
				$oIframeAE->campos = 'tf06_i_codigo, tf06_i_documento, tf07_c_descr, tf06_i_obrigatorio';
				$oIframeAE->legenda = "Registros";
   			$oIframeAE->msg_vazio = "Não foi encontrado nenhum registro.";
				$oIframeAE->textocabec = "#DEB887";
				$oIframeAE->textocorpo = "#444444";
			  $oIframeAE->fundocabec = "#444444";
			  $oIframeAE->fundocorpo = "#eaeaea";
			  $oIframeAE->iframe_height = "200";
			  $oIframeAE->iframe_width = "100%";
			  $oIframeAE->tamfontecabec = 9;
			  $oIframeAE->tamfontecorpo = 9;
			  $oIframeAE->formulario = false;
			  $oIframeAE->iframe_alterar_excluir($db_opcao);
				?>
      </td>
  	</tr>
	</table>

<script>

function js_novo() {

  parent.document.formaba.a2.disabled = true;
  parent.document.formaba.a3.disabled = true;
  top.corpo.iframe_a1.location.href   = 'tfd1_tfd_tipotratamento001.php';
  parent.mo_camada('a1');

}
 

function js_cancelar() {

  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tf06_i_tipotratamento=$tf06_i_tipotratamento&tf04_c_descr=$tf04_c_descr'";
  ?>

}

function js_pesquisatf06_i_documento(mostra) {

  if(mostra==true) {

    js_OpenJanelaIframe('','db_iframe_tfd_documento','func_tfd_documento.php?funcao_js=parent.js_mostratfd_documento1'+
                        '|tf07_i_codigo|tf07_c_descr&chave_validade=true','Documentos',true);
  } else {

     if(document.form1.tf06_i_documento.value != '') {

        js_OpenJanelaIframe('','db_iframe_tfd_documento','func_tfd_documento.php?pesquisa_chave='+
                            document.form1.tf06_i_documento.value+'&funcao_js=parent.js_mostratfd_documento'+
                            '&chave_validade=true','Pesquisa',false);

     } else {
       document.form1.tf07_c_descr.value = ''; 
     }

  }

}
function js_mostratfd_documento(chave,erro){
  document.form1.tf07_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.tf06_i_documento.focus(); 
    document.form1.tf06_i_documento.value = ''; 
  }
}
function js_mostratfd_documento1(chave1,chave2){
  document.form1.tf06_i_documento.value = chave1;
  document.form1.tf07_c_descr.value = chave2;
  db_iframe_tfd_documento.hide();
}
/*
function js_pesquisatf06_i_tipotratamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tfd_tipotratamento','func_tfd_tipotratamento.php?funcao_js=parent.js_mostratfd_tipotratamento1|tf04_i_codigo|tf04_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf06_i_tipotratamento.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tfd_tipotratamento','func_tfd_tipotratamento.php?pesquisa_chave='+document.form1.tf06_i_tipotratamento.value+'&funcao_js=parent.js_mostratfd_tipotratamento','Pesquisa',false);
     }else{
       document.form1.tf04_i_codigo.value = ''; 
     }
  }
}
function js_mostratfd_tipotratamento(chave,erro){
  document.form1.tf04_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.tf06_i_tipotratamento.focus(); 
    document.form1.tf06_i_tipotratamento.value = ''; 
  }
}
function js_mostratfd_tipotratamento1(chave1,chave2){
  document.form1.tf06_i_tipotratamento.value = chave1;
  document.form1.tf04_i_codigo.value = chave2;
  db_iframe_tfd_tipotratamento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tfd_tipotratamentodoc','func_tfd_tipotratamentodoc.php?funcao_js=parent.js_preenchepesquisa|tf06_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_tipotratamentodoc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
*/
</script>