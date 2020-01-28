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

//MODULO: Vacinas
$oDaoVacDependencia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc07_c_nome");
$clrotulo->label("vc07_c_nome");
?>
<fieldset style='width: 75%;'> <legend><b>Dependencia</b></legend>
<form name="form2" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc09_i_codigo?>">
       <?=@$Lvc09_i_codigo?>
    </td>
    <td> 
     <?db_input('vc09_i_codigo',10,$Ivc09_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc09_i_dependente?>">
    <?db_ancora(@$Lvc09_i_dependente,"",3);?>
    </td>
    <td> 
     <?db_input('vc09_i_dependente',10,$Ivc09_i_dependente,true,'text',3,"")?>
     <?db_input('dependente',30,$Ivc07_c_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc09_i_dependencia?>">
     <?db_ancora(@$Lvc09_i_dependencia,"js_pesquisavc09_i_dependencia(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('vc09_i_dependencia',10,$Ivc09_i_dependencia,true,'text',$db_opcao,
                " onchange='js_pesquisavc09_i_dependencia(false);'")?>
     <?db_input('vc07_c_nome',30,$Ivc07_c_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc09_i_situacao?>">
       <?=@$Lvc09_i_situacao?>
    </td>
    <td> 
    <?
     $x = array('1'=>'ATIVA','2'=>'INATIVA');
     db_select('vc09_i_situacao',$x,true,$db_opcao,"");
    ?>
    </td>
  </tr>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type  = "submit" 
       id    = "db_opcao" 
       value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?> 
       onClick="return js_valida();" >
<input name  = "cancelar" 
       type  = "button" 
       id    = "cancelar" 
       value = "Cancelar" 
       onclick="location.href='vac1_vac_dependencia004.php?vc09_i_dependente=<?=$vc09_i_dependente?>
                                                                     &dependente=<?=$dependente?>';"
       <?=($db_botao1==false?"disabled":"")?> >
</form>
</fieldset>
<br><br>

<?
  $chavepri= array("vc09_i_codigo"=>@$vc09_i_codigo);
  $oIframeAE->chavepri=$chavepri;
  if (isset($vc09_i_dependente) && $vc09_i_dependente != "") {

    $oIframeAE->sql = $oDaoVacDependencia->sql_query_alt(null,
                                                        'vc09_i_codigo,dependencia.vc07_c_nome',
                                                        null,
                                                        "vc09_i_dependente=$vc09_i_dependente"
                                                       );
  }
  $oIframeAE->legenda       = "Registros de Dependencias";
  $oIframeAE->campos        = "vc09_i_codigo,vc07_c_nome";
  $oIframeAE->msg_vazio     = "Não foi encontrado nenhum registro.";
  $oIframeAE->textocabec    = "darkblue";
  $oIframeAE->textocorpo    = "black";
  $oIframeAE->fundocabec    = "#aacccc";
  $oIframeAE->fundocorpo    = "#ccddcc";
  $oIframeAE->iframe_width  = "100%";
  $oIframeAE->iframe_height = "130";
  $oIframeAE->opcoes        = 1;
  $oIframeAE->iframe_alterar_excluir($db_opcao);
?> 

<script>
function js_valida() { 
  if (document.form2.vc09_i_dependente.value == document.form2.vc09_i_dependencia.value) {
    alert('Vacina dependencia não pode ser igual a dependente!');
    return false;
  }
  return true;
}
function js_pesquisavc09_i_dependencia(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_vacinadose',
                        'func_vac_vacinadose.php?funcao_js=parent.js_mostravac_vacinadose1|vc07_i_codigo|vc07_c_nome',
                        'Pesquisa',
                        true
                       );

  } else {
     if (document.form2.vc09_i_dependencia.value != '') {  

       js_OpenJanelaIframe('',
                           'db_iframe_vac_vacinadose',
                           'func_vac_vacinadose.php?pesquisa_chave='+document.form2.vc09_i_dependencia.value+
                           '&funcao_js=parent.js_mostravac_vacinadose',
                           'Pesquisa',
                           false
                          );

     } else {
       document.form2.vc07_c_nome.value = ''; 
     }
  }
}

function js_mostravac_vacinadose(chave,erro) {

  document.form2.vc07_c_nome.value = chave; 
  if (erro==true) {  

    document.form2.vc09_i_dependencia.focus(); 
    document.form2.vc09_i_dependencia.value = ''; 

  }
}

function js_mostravac_vacinadose1(chave1,chave2) {

  document.form2.vc09_i_dependencia.value = chave1;
  document.form2.vc07_c_nome.value = chave2;
  db_iframe_vac_vacinadose.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_vac_dependencia',
                      'func_vac_dependencia.php?funcao_js=parent.js_preenchepesquisa|vc09_i_codigo',
                      'Pesquisa',
                       true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_dependencia.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}
</script>