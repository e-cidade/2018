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

//MODULO: ambulatorial
$oDaoSauMedicosForaRede->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('sd03_i_codigo');
$oRotulo->label('sd03_i_crm');

$oRotulo->label("s154_rhcbo");
$oRotulo->label("rh70_descr");
$oRotulo->label("rh70_estrutural");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ts154_i_codigo?>">
      <?=@$Ls154_i_codigo?>
    </td>
    <td>
      <?
      db_input('s154_i_codigo', 10, $Is154_i_codigo, true, 'text', 3, "");
      db_input('lBotao', 10, '', true, 'hidden', 3, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts154_i_medico?>">
      <?
      db_ancora(@$Ls154_i_medico, "js_pesquisas154_i_medico(true);", 3);
      ?>
    </td>
    <td>
      <?
      db_input('s154_i_medico', 15, $Is154_i_medico, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts154_c_nome?>">
      <?=@$Ls154_c_nome?>
    </td>
    <td>
      <?
      if (isset($s154_c_nome)) {

        $aOrig = array('á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù', 'ç');
        $aDest = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Ô', 'Ã', 'Õ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ç');
        $s154_c_nome = str_replace($aOrig, $aDest, strtoupper($s154_c_nome));

      }
      db_input('s154_c_nome', 48, $Is154_c_nome, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tsd03_i_crm?>">
      <?=$Lsd03_i_crm?>
    </td>
    <td>
      <?
      db_input('sd03_i_crm', 15, $Isd03_i_crm, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts154_c_cns?>">
      <?=@$Ls154_c_cns?>
    </td>
    <td>
      <?
      db_input('s154_c_cns', 15, $Is154_c_cns, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr id="tipo_consulta">
		<td nowrap title="<?=@$Trh70_sequencial?>">
				<?
				db_ancora ( 'CBO:', "js_pesquisasd27_i_rhcbo(true);", 1 );
				?>
		</td>
		<td>
				<?
				db_input ( 'rh70_estrutural',5,$Irh70_estrutural,true,'text',$db_opcao,"onChange='js_pesquisasd27_i_rhcbo(false);'");
				db_input ( 's154_rhcbo',5,null,true,'hidden',3,"");
				db_input ( 'rh70_descr', 40, @$Irh70_descr, true, 'text', 3 );
				?>
		</td>
	</tr>
  </table>
  </center>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
  type="submit" id="db_opcao"
  value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
  <?=($db_botao == false ? "disabled" : "")?>
  <?=($db_opcao != 3 ? 'onclick="return js_validaEnvio();"' : '')?>>
<?
if (isset($lBotao) && $lBotao == 'true') {
?>
  <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_cadprof.hide();">
<?
} else {
?>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<?
}
?>
</form>

<script>

/**
 * Pesquisa Especialidade
 */
function js_pesquisasd27_i_rhcbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_estrutural|rh70_descr','Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){
        js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?pesquisa_chave='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo','Pesquisa',false);
     }else{
       document.form1.s154_rhcbo.value = '';
       document.form1.rh70_estrutural.value = '';
       document.form1.rh70_descr.value = '';
     }
  }
}
function js_mostrarhcbo(chave1, chave2, chave3,erro){
  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value = chave2;
  document.form1.s154_rhcbo.value = chave3;
  if(erro==true){
    document.form1.rh70_estrutural.focus();
    document.form1.rh70_estrutural.value = '';
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3){
  document.form1.s154_rhcbo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  db_iframe_rhcbo.hide();
}

function js_validaEnvio() {

  if ($F('s154_c_nome') == '') {

    alert('Digite o nome do médico.');
    return false;

  }

  if ($F('s154_c_cns').length != 0 && $F('s154_c_cns').length != 15) {

    alert('O CNS deve possuir 15 dígitos.');
    return false;

  }

  return true;

}

function js_pesquisa() {

  js_OpenJanelaIframe('', 'db_iframe_sau_medicosforarede', 'func_sau_medicosforarede.php?'+
                      'funcao_js=parent.js_preenchepesquisa|s154_i_medico', 'Pesquisa', true
                     );

}
function js_preenchepesquisa(chave) {

  db_iframe_sau_medicosforarede.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}
</script>