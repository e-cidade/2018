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
$clvac_vacinadoenca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc06_c_descr");
$clrotulo->label("sd70_c_nome");
?>
<fieldset style='width: 75%;'> <legend><b>Vacina Doença</b></legend>
<form name="form2" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc10_i_codigo?>">
       <?=@$Lvc10_i_codigo?>
    </td>
    <td> 
      <?
      db_input('vc10_i_codigo',10,$Ivc10_i_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc10_i_vacina?>">
       <?=$Lvc10_i_vacina?>
    </td>
    <td> 
     <?db_input('vc10_i_vacina',10,$Ivc10_i_vacina,true,'text',3,"")?>
     <?db_input('vc06_c_descr',40,$Ivc06_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc10_i_cid?>">
     <?db_ancora(@$Lvc10_i_cid,"js_pesquisavc10_i_cid(true);",$db_opcao);?>
    </td>
    <td> 
    <?
      db_input('vc10_i_cid',10,$Ivc10_i_cid,true,'text',$db_opcao,
                " onchange='js_pesquisavc10_i_cid(false);'");
      db_input('sd70_c_nome',40,$Isd70_c_nome,true,'text',3,'');
    ?>
    </td>
  </tr>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type  = "submit"
       id    = "db_opcao"
       value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?>>
<input name    = "cancelar"
       type    = "button"
       id      = "cancelar"
       value   = "Cancelar"
       onclick = "location.href='vac1_vac_vacinadoenca004.php?vc10_i_vacina=<?=$vc10_i_vacina?>
                                                            &vc06_c_descr=<?=$vc06_c_descr?>';"
       <?=($db_botao1==false?"disabled":"")?>>
</form>
<br>

<?
  $chavepri                                = array("vc10_i_codigo"=>@$vc10_i_codigo);
  $cliframe_alterar_excluir->chavepri      = $chavepri;
  if (isset($vc10_i_vacina) && $vc10_i_vacina != "") {
    $cliframe_alterar_excluir->sql         = $clvac_vacinadoenca->sql_query(null,
                                                                            '*',
                                                                            null,
                                                                            " vc10_i_vacina = $vc10_i_vacina "
                                                                           );
  }
  $cliframe_alterar_excluir->legenda       = "Registros Doses de vacinação";
  $cliframe_alterar_excluir->campos        ="vc10_i_codigo,sd70_c_nome";
  $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec    = "darkblue";
  $cliframe_alterar_excluir->textocorpo    = "black";
  $cliframe_alterar_excluir->fundocabec    = "#aacccc";
  $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_height = "130";
  $cliframe_alterar_excluir->opcoes        = 1;
  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?> 


<script>
function js_pesquisavc10_i_cid(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_sau_cid',
                        'func_sau_cid.php?funcao_js=parent.js_mostrasau_cid1|sd70_i_codigo|sd70_c_nome',
                        'Pesquisa',
                        true
                       );

  } else {
     if (document.form2.vc10_i_cid.value != '') {  

       js_OpenJanelaIframe('',
                           'db_iframe_sau_cid',
                           'func_sau_cid.php?pesquisa_chave='+document.form2.vc10_i_cid.value+
                           '&funcao_js=parent.js_mostrasau_cid',
                           'Pesquisa',
                           false
                          );

     } else {
       document.form2.sd70_c_nome.value = ''; 
     }
  }
}

function js_mostrasau_cid(chave,erro) {

  document.form2.sd70_c_nome.value = chave; 
  if (erro==true) {  

    document.form2.vc10_i_cid.focus(); 
    document.form2.vc10_i_cid.value = ''; 

  }
}

function js_mostrasau_cid1(chave1,chave2) {

  document.form2.vc10_i_cid.value = chave1;
  document.form2.sd70_c_nome.value = chave2;
  db_iframe_sau_cid.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('',
                      'db_iframe_vac_vacinadoenca',
                      'func_vac_vacinadoenca.php?funcao_js=parent.js_preenchepesquisa|vc10_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_vacinadoenca.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>