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

//MODULO: TFD
$oDaotfd_situacaopedidotfd->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf01_i_codigo");
$clrotulo->label("tf01_i_cgsund");
$clrotulo->label("z01_v_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf28_i_codigo?>">
      <?=@$Ltf28_i_codigo?>
    </td>
    <td> 
      <?
      db_input('tf28_i_codigo',10,$Itf28_i_codigo,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf28_i_pedidotfd?>">
      <?=@$Ltf28_i_pedidotfd?>
    </td>
    <td> 
      <?
      db_input('tf28_i_pedidotfd',10,$Itf01_i_codigo,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf01_i_cgsund?>">
      <?
      echo $Ltf01_i_cgsund;
      ?>
    </td>
    <td> 
      <?
      db_input('tf01_i_cgsund',10,$Itf01_i_cgsund,true,'text',3,'');
      db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf28_i_situacao?>">
      <?=@$Ltf28_i_situacao?>
    </td>
    <td> 
      <?
      $aX = array();
      $sSql = $oDaotfd_situacaotfd->sql_query_file(null, ' * ', ' tf26_i_codigo ');
      $rs = $oDaotfd_situacaotfd->sql_record($sSql);

      for($iCont = 0; $iCont < $oDaotfd_situacaotfd->numrows; $iCont++) {

        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $aX[$oDados->tf26_i_codigo] = $oDados->tf26_c_descr;

      }
      db_select('tf28_i_situacao',$aX,true,$db_opcao,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf28_c_obs?>">
       <?=@$Ltf28_c_obs?>
    </td>
    <td> 
      <?
      db_input('tf28_c_obs',80,$Itf28_c_obs,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" <?=(!isset($opcao)?"disabled":"")?> >
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_situacao.hide();">
</form>

  <table width="100%">
	  <tr>
		  <td valign="top"><br>
        <?
				$aChavepri = array ('tf28_i_codigo' => @$tf28_i_codigo,
                            'tf28_i_pedidotfd' => @$tf28_i_pedidotfd, 
                            'tf28_i_situacao' => @$tf28_i_situacao, 
                            'tf28_i_login' => @$tf28_i_login, 
                            'tf28_c_obs' => @$tf28_c_obs,
                            'tf28_d_datasistema' => @$tf28_d_datasistema,
                            'tf28_c_horasistema' => @$tf28_c_horasistema,
                            'tf01_i_cgsund' => @$tf01_i_cgsund);
				$oIframeAE->chavepri = $aChavepri;

        $sCampos = 
        " tf28_i_codigo,
          tf28_i_pedidotfd,
          tf28_i_situacao,
          tf28_i_situacao || ' - ' || tf26_c_descr as tf01_i_situacao,
          login,
          tf28_c_obs,
          tf28_i_login,
          tf28_d_datasistema,
          tf28_c_horasistema,
          tf01_i_cgsund ";
        
				$oIframeAE->sql = $oDaotfd_situacaopedidotfd->sql_query2(null, $sCampos,
                                                                ' tf28_i_codigo desc ',
                                                                " tf28_i_pedidotfd = $tf28_i_pedidotfd ");
				$oIframeAE->campos = 'tf28_i_codigo, tf01_i_situacao, tf28_c_obs, tf28_d_datasistema, tf28_c_horasistema, login';
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
			  $oIframeAE->opcoes = 2;
			  $oIframeAE->iframe_alterar_excluir($db_opcao);
				?>
      </td>
  	</tr>
	</table>


<script>

<?
  if(isset($opcao)) {
    
    if(isset($tf28_i_login) && db_getsession('DB_id_usuario') != $tf28_i_login) {
      echo 'js_desabilitaAlteracao()';
    }

  }
?>

function js_desabilitaAlteracao() {

  document.getElementById('db_opcao').disabled = true;
  alert('Somente o usário que lançou este registro pode alterá-lo.');

}

function js_cancelar() {
 
  <?
  echo ' location.href = "'.basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]).'?tf28_i_pedidotfd='.
                          $tf28_i_pedidotfd.'&tf01_i_cgsund="'.
                          '+document.getElementById(\'tf01_i_cgsund\').value+"&z01_v_nome='.$z01_v_nome.'";'
  ?>

}

/*
function js_pesquisatf28_i_pedidotfd(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tfd_pedidotfd','func_tfd_pedidotfd.php?funcao_js=parent.js_mostratfd_pedidotfd1|tf01_i_codigo|tf01_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf28_i_pedidotfd.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tfd_pedidotfd','func_tfd_pedidotfd.php?pesquisa_chave='+document.form1.tf28_i_pedidotfd.value+'&funcao_js=parent.js_mostratfd_pedidotfd','Pesquisa',false);
     }else{
       document.form1.tf01_i_codigo.value = ''; 
     }
  }
}
function js_mostratfd_pedidotfd(chave,erro){
  document.form1.tf01_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.tf28_i_pedidotfd.focus(); 
    document.form1.tf28_i_pedidotfd.value = ''; 
  }
}
function js_mostratfd_pedidotfd1(chave1,chave2){
  document.form1.tf28_i_pedidotfd.value = chave1;
  document.form1.tf01_i_codigo.value = chave2;
  db_iframe_tfd_pedidotfd.hide();
}
*/
</script>