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

$oDaofar_tiporeceitapadrao->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('descrdepto');
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Tfa42_i_codigo?>">
      <?=@$Lfa42_i_codigo?>
    </td>
    <td> 
      <?
      db_input('fa42_i_codigo',10,$Ifa42_i_codigo,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa42_i_tiporeceita?>">
      <?
      echo $Lfa42_i_tiporeceita;
      ?>
    </td>
    <td> 
      <?
      $aX = array();
      $sSql = $oDaofar_tiporeceita->sql_query_file(null, 'fa03_i_codigo, fa03_c_descr', 'fa03_c_descr', 'fa03_i_ativa = 1');
      $rsFar_tiporeceita = $oDaofar_tiporeceita->sql_record($sSql);

      for($iCont = 0; $iCont < $oDaofar_tiporeceita->numrows; $iCont++) {
                 
        $oDados = db_utils::fieldsmemory($rsFar_tiporeceita, $iCont);
        $aX[$oDados->fa03_i_codigo] = $oDados->fa03_c_descr;

      }
      db_select('fa42_i_tiporeceita',$aX,true,$db_opcao,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa42_i_departamento?>">
      <?
      db_ancora(@$Lfa42_i_departamento,"js_pesquisafa42_i_departamento(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('fa42_i_departamento',10,$Ifa42_i_departamento,true,'text',$db_opcao," onchange='js_pesquisafa42_i_departamento(false);'");
      db_input('descrdepto',50,$Idescrdepto,true,'text',3,'');
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>" 
  type="submit" id="db_opcao" 
  value="<?=($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao ==22 ? 'Alterar' : 'Excluir'))?>" 
  <?=($db_botao == false ? 'disabled' : '')?>>
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();"
  <?=(!isset($opcao) ? 'disabled' : '')?>>
</form>

  <table width="100%">
	  <tr>
		  <td valign="top"><br>
        <?
				$aChavepri = array ('fa42_i_codigo' => @$fa42_i_codigo,
                            'fa42_i_tiporeceita' => @$fa02_i_tiporeceita,
                            'fa42_i_departamento' => @$fa42_i_departamento, 
                            'fa03_c_descr' => @$fa03_c_descr, 
                            'descrdepto' => @$descrdepto);
				$oIframeAE->chavepri = $aChavepri;

        $sCampos = 
        " fa42_i_codigo,
          fa42_i_tiporeceita,
          fa42_i_departamento,
          fa03_c_descr,
          descrdepto ";
        
				$oIframeAE->sql = $oDaofar_tiporeceitapadrao->sql_query(null, $sCampos, ' coddepto ');
				$oIframeAE->campos = 'fa42_i_codigo, fa42_i_tiporeceita, fa03_c_descr, fa42_i_departamento, descrdepto';
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
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  ?>

}

function js_pesquisafa42_i_departamento(mostra) {

  if(mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_db_depart', 'func_db_depart.php?funcao_js=parent.js_mostradb_depart1'+
                        '|coddepto|descrdepto', 'Pesquisa Departamento', true);

  } else {

    if(document.form1.fa42_i_departamento.value != '') { 

      js_OpenJanelaIframe('', 'db_iframe_db_depart', 'func_db_depart.php?pesquisa_chave='+
                          document.form1.fa42_i_departamento.value+'&funcao_js=parent.js_mostradb_depart', 
                          'Pesquisa Departamento', false);

    } else {
      document.form1.descrdepto.value = ''; 
    }

  }

}

function js_mostradb_depart(chave, erro) {

  document.form1.descrdepto.value = chave; 

  if(erro == true) {

    document.form1.fa42_i_departamento.focus(); 
    document.form1.fa42_i_departamento.value = ''; 

  }

}
function js_mostradb_depart1(chave1, chave2) {

  document.form1.fa42_i_departamento.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();

}
</script>