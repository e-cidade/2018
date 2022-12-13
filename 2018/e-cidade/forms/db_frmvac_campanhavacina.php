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
$clvac_campanhavacina->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc11_c_nome");
$clrotulo->label("vc06_c_descr");
?>
<fieldset style='width: 75%;'> <legend><b>Vacinas</b></legend>
<form name="form2" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc12_i_codigo?>">
       <?=@$Lvc12_i_codigo?>
    </td>
    <td> 
     <?db_input('vc12_i_codigo',10,$Ivc12_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc12_i_campanha?>">
     <? db_ancora(@$Lvc12_i_campanha,"js_pesquisavc12_i_campanha(true);",3);?>
    </td>
    <td> 
     <?db_input('vc12_i_campanha',10,$Ivc12_i_campanha,true,'text',3,
                 " onchange='js_pesquisavc12_i_campanha(false);'")?>
     <?db_input('vc11_c_nome',20,$Ivc11_c_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc12_i_vacina?>">
     <? db_ancora(@$Lvc12_i_vacina,"js_pesquisavc12_i_vacina(true);",$db_opcao);?>
    </td>
    <td> 
      <?db_input('vc12_i_vacina',10,$Ivc12_i_vacina,true,'text',$db_opcao,
                  " onchange='js_pesquisavc12_i_vacina(false);'")?>
      <?db_input('vc06_c_descr',10,$Ivc06_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type  = "submit" 
       id    = "db_opcao" 
       value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?> >
<input name  = "cancelar" 
       type  = "button" 
       id    = "cancelar"
       value = "Cancelar" 
       onclick="location.href='vac1_vac_campanhavacina004.php?vc12_i_campanha=<?=$vc12_i_campanha?>
                               &vc11_c_nome=<?=$vc11_c_nome?>';" <?=($db_botao1==false?"disabled":"")?> >
</form>
</fieldset>

<?
  $chavepri= array("vc12_i_codigo"=>@$vc12_i_codigo);
  $cliframe_alterar_excluir->chavepri=$chavepri;
  if (isset($vc12_i_campanha) && @$vc12_i_campanha!="") {
    $cliframe_alterar_excluir->sql = $clvac_campanhavacina->sql_query(null,
                                                                      '*',
                                                                      null,
                                                                      " vc12_i_campanha=$vc12_i_campanha"
                                                                     );
  }
  $cliframe_alterar_excluir->legenda       = "Registro Campanhas de vacinação";
  $cliframe_alterar_excluir->campos        ="vc12_i_codigo,vc06_c_descr";
  $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec    = "darkblue";
  $cliframe_alterar_excluir->textocorpo    = "black";
  $cliframe_alterar_excluir->fundocabec    = "#aacccc";
  $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_height = "130";
  $cliframe_alterar_excluir->opcoes = 1;
  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?> 

<script>
function js_pesquisavc12_i_vacina(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_vacina',
                        'func_vac_vacina.php?funcao_js=parent.js_mostravac_vacina1|vc06_i_codigo|vc06_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form2.vc12_i_vacina.value != '') {  

        js_OpenJanelaIframe('',
                            'db_iframe_vac_vacina',
                            'func_vac_vacina.php?pesquisa_chave='+document.form2.vc12_i_vacina.value+
                            '&funcao_js=parent.js_mostravac_vacina',
                            'Pesquisa',
                            false
                           );

     } else {
       document.form2.vc06_c_descr.value = ''; 
     }
  }
}

function js_mostravac_vacina(chave,erro) {

  document.form2.vc06_c_descr.value = chave; 
  if (erro == true) {  

    document.form2.vc12_i_vacina.focus(); 
    document.form2.vc12_i_vacina.value = ''; 

  }
}

function js_mostravac_vacina1(chave1,chave2) {

  document.form2.vc12_i_vacina.value = chave1;
  document.form2.vc06_c_descr.value = chave2;
  db_iframe_vac_vacina.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('',
                      'db_iframe_vac_campanhavacina',
                      'func_vac_campanhavacina.php?funcao_js=parent.js_preenchepesquisa|vc12_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_campanhavacina.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}
</script>