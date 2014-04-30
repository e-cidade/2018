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

//MODULO: Vacinas
$clvac_vacinadose->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc06_c_descr");
$clrotulo->label("vc03_c_descr");
$clrotulo->label("vc05_c_descr");
?>
<fieldset style='width: 75%;'> <legend><b>Doses da Vacina</b></legend>
<form name="form2" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc07_i_codigo?>">
      <?=@$Lvc07_i_codigo?>
    </td>
    <td> 
      <?
      db_input('vc07_i_codigo',8,$Ivc07_i_codigo,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr style="display: none;">
    <td nowrap title="<?=@$Tvc07_i_vacina?>">
      <?=@$Lvc07_i_vacina?>
    </td>
    <td> 
      <?
        db_input('vc07_i_vacina',8,$Ivc07_i_vacina,true,'text',3,
                 " onchange='js_pesquisavc07_i_vacina(false);'");
        db_input('vc06_c_descr',40,$Ivc06_c_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_dose?>">
      <?
      db_ancora(@$Lvc07_i_dose,"js_pesquisavc07_i_dose(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
        db_input('vc07_i_dose',8,$Ivc07_i_dose,true,'text',$db_opcao,
                  " onchange='js_pesquisavc07_i_dose(false);'");
        db_input('vc03_c_descr',40,$Ivc03_c_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_calendario?>">
      <?
      db_ancora(@$Lvc07_i_calendario,"js_pesquisavc07_i_calendario(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
        db_input('vc07_i_calendario',8,$Ivc07_i_calendario,true,'text',$db_opcao,
                 " onchange='js_pesquisavc07_i_calendario(false);'");
        db_input('vc05_c_descr',40,$Ivc05_c_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_c_nome?>">
      <?=@$Lvc07_c_nome?>
    </td>
    <td> 
      <?
      db_input('vc07_c_nome',50,$Ivc07_c_nome,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_n_quant?>">
      <?=@$Lvc07_n_quant?>
    </td>
    <td> 
      <?
      db_input('vc07_n_quant',10,$Ivc07_n_quant,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_c_descr?>">
      <?=@$Lvc07_c_descr?>
    </td>
    <td> 
      <?
      db_input('vc07_c_descr',50,$Ivc07_c_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_situacao?>">
      <?=@$Lvc07_i_situacao?>
    </td>
    <td> 
      <?
         $aX = array('1'=>'ATIVA', '2'=>'INATIVA');
         db_select('vc07_i_situacao', $aX, true, $db_opcao,"");
      ?>
    </td>
  </tr>
</table>
</center>
<input name  = "<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
       type  = "submit" id="db_opcao" 
       value = "<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?> >
<input name    = "cancelar" 
       type    = "button"
       id      = "cancelar"
       value   = "Cancelar"
       onclick = "location.href='vac1_vac_vacinadose004.php?vc07_i_vacina=<?=$vc07_i_vacina?>
                                                        &vc06_c_descr=<?=$vc06_c_descr?>';"
       <?=($db_botao1==false?"disabled":"")?> >
</form>
</fieldset>
<br><br>

<?
  $chavepri= array("vc07_i_codigo"=>@$vc07_i_codigo);
  $cliframe_alterar_excluir->chavepri     = $chavepri;
  $cliframe_alterar_excluir->sql          = $clvac_vacinadose->sql_query2(null,
                                                                '*',
                                                                "vc03_i_ordem",
                                                                " vc07_i_vacina=$vc07_i_vacina "
                                                               );
  $cliframe_alterar_excluir->legenda       = "Registros Doses de vacinação";
  $cliframe_alterar_excluir->campos        ="vc07_i_codigo,vc07_c_nome";
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
function js_pesquisavc07_i_dose(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_dose',
                        'func_vac_dose.php?funcao_js=parent.js_mostravac_dose1|vc03_i_codigo|vc03_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form2.vc07_i_dose.value != '') {  

        js_OpenJanelaIframe('',
                            'db_iframe_vac_dose',
                            'func_vac_dose.php?pesquisa_chave='+document.form2.vc07_i_dose.value+
                            '&funcao_js=parent.js_mostravac_dose',
                            'Pesquisa',
                            false
                           );

     } else {
       document.form2.vc03_c_descr.value = ''; 
     }
  }
}

function js_mostravac_dose(chave,erro) {

  document.form2.vc03_c_descr.value = chave; 
  if (erro == true) {  

    document.form2.vc07_i_dose.focus(); 
    document.form2.vc07_i_dose.value = ''; 

  }
}

function js_mostravac_dose1(chave1,chave2) {

  document.form2.vc07_i_dose.value  = chave1;
  document.form2.vc03_c_descr.value = chave2;
  db_iframe_vac_dose.hide();

}

function js_pesquisavc07_i_calendario(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_calendario',
                        'func_vac_calendario.php?funcao_js=parent.js_mostravac_calendario1|vc05_i_codigo|vc05_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form2.vc07_i_calendario.value != '') {  

        js_OpenJanelaIframe('',
                            'db_iframe_vac_calendario',
                            'func_vac_calendario.php?pesquisa_chave='+document.form2.vc07_i_calendario.value+
                            '&funcao_js=parent.js_mostravac_calendario',
                            'Pesquisa',
                            false
                           );
        
     } else {
       document.form2.vc05_c_descr.value = ''; 
     }
  }
}

function js_mostravac_calendario(chave,erro) {

  document.form2.vc05_c_descr.value = chave; 
  if (erro == true) {  

    document.form2.vc07_i_calendario.focus(); 
    document.form2.vc07_i_calendario.value = ''; 

  }
}

function js_mostravac_calendario1(chave1,chave2) {

  document.form2.vc07_i_calendario.value = chave1;
  document.form2.vc05_c_descr.value = chave2;
  db_iframe_vac_calendario.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('',
                      'db_iframe_vac_vacinadose',
                      'func_vac_vacinadose.php?funcao_js=parent.js_preenchepesquisa|vc07_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_vacinadose.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>