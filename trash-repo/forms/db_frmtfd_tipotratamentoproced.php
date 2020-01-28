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
$cltfd_tipotratamentoproced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("tf04_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf05_i_codigo?>">
       <?=@$Ltf05_i_codigo?>
    </td>
    <td> 
      <?
      db_input('tf05_i_codigo',10,$Itf05_i_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf05_i_tipotratamento?>">
      <?
      db_ancora(@$Ltf05_i_tipotratamento,"js_pesquisatf05_i_tipotratamento(true);",3);
      ?>
    </td>
    <td> 
      <?
      db_input('tf05_i_tipotratamento',10,'',true,'text',3," onchange='js_pesquisatf05_i_tipotratamento(false);'");
      db_input('tf04_c_descr',50,'',true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf05_i_procedimento?>">
      <?
      db_ancora(@$Ltf05_i_procedimento,"js_pesquisatf05_i_procedimento(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('sd63_c_procedimento',10,$Isd63_c_procedimento,true,'text',$db_opcao," onchange='js_pesquisatf05_i_procedimento(false);'");
      db_input('tf05_i_procedimento',10,'',true,'hidden',3);
      db_input('sd63_c_nome',50,$Isd63_c_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf05_i_ativo?>">
       <?=@$Ltf05_i_ativo?>
    </td>
    <td> 
      <?
      $aX = array('1'=>'SIM', '2'=>'NÃO');
      db_select('tf05_i_ativo',$aX,true,$db_opcao,'');
      ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" <?=(!isset($opcao)?"disabled":"")?> >
</form>

  <table width="100%">
	  <tr>
		  <td valign="top"><br>
        <?
				$aChavepri = array ('tf05_i_codigo' => @$tf05_i_codigo,
                           'tf05_i_procedimento' => @$tf05_i_procedimento, 
                           'tf05_i_tipotratamento' => @$tf05_i_tipotratamento, 
                           'tf05_i_ativo' => @$tf05_i_ativo, 
                           'sd63_c_nome' => @$sd63_c_nome, 
                           'sd63_c_procedimento' => @$sd63_c_procedimento);
				$oIframeAE->chavepri = $aChavepri;

        $sCampos = 
        " tf05_i_codigo,
          tf05_i_procedimento,
          tf05_i_tipotratamento,
          sd63_c_nome,
          sd63_c_procedimento,
          case when tf05_i_ativo = 1
            then 
              'SIM'
            else 
              'NÃO'
          end as tf05_i_ativo ";
        
				$oIframeAE->sql = $cltfd_tipotratamentoproced->sql_query2(null, $sCampos, ' tf05_i_codigo ',
                                                                  " tf05_i_tipotratamento = $tf05_i_tipotratamento ");
				$oIframeAE->campos = 'tf05_i_codigo, sd63_c_procedimento, sd63_c_nome, tf05_i_ativo';
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

function js_cancelar() {

  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tf05_i_tipotratamento=$tf05_i_tipotratamento&tf04_c_descr=$tf04_c_descr'";
  ?>

}

function js_pesquisatf05_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procedimento1|sd63_c_procedimento|sd63_c_nome|sd63_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd63_c_procedimento.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd63_c_procedimento.value+'&funcao_js=parent.js_mostrasau_procedimento','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = ''; 
       document.form1.tf05_i_procedimento.value = ''; 
     }
  }
}
function js_mostrasau_procedimento(chave,erro,chave2){
  document.form1.sd63_c_nome.value = chave; 
    document.form1.tf05_i_procedimento.value = chave2; 
  if(erro==true){ 
    document.form1.tf05_i_procedimento.focus(); 
    document.form1.tf05_i_procedimento.value = ''; 
  }
}
function js_mostrasau_procedimento1(chave1,chave2,chave3){
  document.form1.sd63_c_procedimento.value = chave1;
  document.form1.sd63_c_nome.value = chave2;
  document.form1.tf05_i_procedimento.value = chave3;
  db_iframe_sau_procedimento.hide();
}
/*
function js_pesquisatf05_i_tipotratamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tfd_tipotratamento','func_tfd_tipotratamento.php?funcao_js=parent.js_mostratfd_tipotratamento1|tf04_i_codigo|tf04_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf05_i_tipotratamento.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tfd_tipotratamento','func_tfd_tipotratamento.php?pesquisa_chave='+document.form1.tf05_i_tipotratamento.value+'&funcao_js=parent.js_mostratfd_tipotratamento','Pesquisa',false);
     }else{
       document.form1.tf04_i_codigo.value = ''; 
     }
  }
}
function js_mostratfd_tipotratamento(chave,erro){
  document.form1.tf04_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.tf05_i_tipotratamento.focus(); 
    document.form1.tf05_i_tipotratamento.value = ''; 
  }
}
function js_mostratfd_tipotratamento1(chave1,chave2){
  document.form1.tf05_i_tipotratamento.value = chave1;
  document.form1.tf04_i_codigo.value = chave2;
  db_iframe_tfd_tipotratamento.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tfd_tipotratamentoproced','func_tfd_tipotratamentoproced.php?funcao_js=parent.js_preenchepesquisa|tf05_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_tipotratamentoproced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
*/
</script>