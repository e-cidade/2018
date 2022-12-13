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
$oDaoVacDoseperiodica->rotulo->label();
$oDaoVacVacinadose->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc06_c_descr");
$clrotulo->label("vc03_i_codigo");
$clrotulo->label("vc05_i_codigo");
?>
<fieldset style='width: 75%;'> <legend><b>Limites</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc07_i_codigo?>">
       <?=@$Lvc07_i_codigo?>
    </td>
    <td> 
      <?
      db_input('vc07_i_codigo',10,$Ivc07_i_codigo,true,'text',3,"");
      db_input('vc07_c_nome',30,$Ivc07_c_nome,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_diasatraso?>">
       <?=@$Lvc07_i_diasatraso?>
    </td>
    <td>
     <?db_input('vc07_i_diasatraso',10,$Ivc07_i_diasatraso,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
  <tr style="display: none;">
    <td nowrap title="<?=@$Tvc07_i_vacina?>">
      <?=@$Lvc07_i_vacina?>
    </td>
    <td> 
      <?
      db_input('vc07_i_vacina',10,$Ivc07_i_vacina,true,'text',3,"");
      db_input('vc06_c_descr',10,$Ivc06_c_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_diasantecipacao?>">
       <?=@$Lvc07_i_diasantecipacao?>
    </td>
    <td> 
     <?db_input('vc07_i_diasantecipacao',10,$Ivc07_i_diasantecipacao,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_sexo?>">
       <?=@$Lvc07_i_sexo?>
    </td>
    <td> 
     <?
       $x = array('1'=>'MASCULINO','2'=>'FEMININO','3'=>'AMBOS');
       if (!isset($vc07_i_sexo) || empty($vc07_i_sexo)) {
         $vc07_i_sexo = 3;
       }
       db_select('vc07_i_sexo',$x,true,$db_opcao,"");
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_diasvalidade?>">
       <?=@$Lvc07_i_diasvalidade?>
    </td>
    <td>
     <?db_input('vc07_i_diasvalidade',14,$Ivc07_i_diasvalidade,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc07_i_tipocalculo?>">
       <?=@$Lvc07_i_tipocalculo?>
    </td>
    <td> 
     <?
       $x = array('1'=>'DATA DE NASCIMENTO','2'=>'ULTIMA APLICAÇÃO','3'=>'APLICAÇÃO PERIODICA');
       db_select('vc07_i_tipocalculo',$x,true,$db_opcao," onchange=\"js_dosePeriodo(this.value);\" ");
       db_input('vc14_i_codigo',2,$Ivc14_i_codigo,true,'hidden',$db_opcao,"");
     ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" id="dose_periodo" style="display: none;" >
      <fieldset style='width: 45%;'> <legend><b>Dose Periodica</b></legend>
      <table>
        <tr>
          <td><b>Dia:</b></td>
          <td><?db_input('vc14_i_faixadia',2,$Ivc14_i_faixadia,true,'text',$db_opcao,"")?></td>
          <td><b>Mes:</b></td>
          <td><?db_input('vc14_i_faixames',2,$Ivc14_i_faixames,true,'text',$db_opcao,"")?></td>
          <td><b>Ano:</b></td>
          <td><?db_input('vc14_i_faixaano',3,$Ivc14_i_faixaano,true,'text',$db_opcao,"")?></td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style='width: 55%;'> <legend><b>Faixa</b></legend>
      <table>
        <tr>
          <td nowrap title="<?=@$Tvc07_i_faixainidias?>">
            <?=@$Lvc07_i_faixainidias?>
          </td>
          <td> 
          <?db_input('vc07_i_faixainidias',2,$Ivc07_i_faixainidias,true,'text',$db_opcao,"")?>
          </td>
          <td nowrap title="<?=@$Tvc07_i_faixafimdias?>">
            <?=@$Lvc07_i_faixafimdias?>
          </td>
          <td>
           <?db_input('vc07_i_faixafimdias',2,$Ivc07_i_faixafimdias,true,'text',$db_opcao,"")?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc07_i_faixainimes?>">
            <?=@$Lvc07_i_faixainimes?>
          </td>
          <td> 
           <?db_input('vc07_i_faixainimes',2,$Ivc07_i_faixainimes,true,'text',$db_opcao,"")?>
          </td>
          <td nowrap title="<?=@$Tvc07_i_faixafimmes?>">
            <?=@$Lvc07_i_faixafimmes?>
          </td>
          <td> 
           <?db_input('vc07_i_faixafimmes',2,$Ivc07_i_faixafimmes,true,'text',$db_opcao,"")?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc07_i_faixainiano?>">
            <?=@$Lvc07_i_faixainiano?>
          </td>
          <td>
           <?db_input('vc07_i_faixainiano',3,$Ivc07_i_faixainiano,true,'text',$db_opcao,"")?>
          </td>
          <td nowrap title="<?=@$Tvc07_i_faixafimano?>">
            <?=@$Lvc07_i_faixafimano?>
          </td>
          <td> 
           <?db_input('vc07_i_faixafimano',3,$Ivc07_i_faixafimano,true,'text',$db_opcao,"")?>
          </td>
        </tr>
      </table>
      </fieldset>
    </td>
  <tr>
<table>
</center>
<input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >
</form>
</fieldset>
<script>
<?
 if (isset($vc07_i_tipocalculo)) {
   echo"js_dosePeriodo('$vc07_i_tipocalculo');  ";
 }
?>

function js_dosePeriodo(valor) {

  if (valor == '3') {
    document.getElementById('dose_periodo').style.display = '';
  } else {
    document.getElementById('dose_periodo').style.display = 'none';
  }
}

function js_pesquisavc07_i_dose(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_dose',
                        'func_vac_dose.php?funcao_js=parent.js_mostravac_dose1|vc03_i_codigo|vc03_i_codigo',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form1.vc07_i_dose.value != '') {  

        js_OpenJanelaIframe('',
                              'db_iframe_vac_dose',
                           'func_vac_dose.php?pesquisa_chave='+document.form1.vc07_i_dose.value+
                           '&funcao_js=parent.js_mostravac_dose',
                            'Pesquisa',
                             false
                           );

     } else {
       document.form1.vc03_i_codigo.value = ''; 
     }
  }
}

function js_mostravac_dose(chave,erro) {

  document.form1.vc03_i_codigo.value = chave; 
  if (erro == true) {  

    document.form1.vc07_i_dose.focus(); 
    document.form1.vc07_i_dose.value = ''; 

  }
}

function js_mostravac_dose1(chave1,chave2) {

  document.form1.vc07_i_dose.value   = chave1;
  document.form1.vc03_i_codigo.value = chave2;
  db_iframe_vac_dose.hide();

}

function js_pesquisavc07_i_calendario(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_calendario',
                        'func_vac_calendario.php?funcao_js=parent.js_mostravac_calendario1|vc05_i_codigo|vc05_i_codigo',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form1.vc07_i_calendario.value != '') {  

        js_OpenJanelaIframe('',
                            'db_iframe_vac_calendario',
                            'func_vac_calendario.php?pesquisa_chave='+document.form1.vc07_i_calendario.value+
                            '&funcao_js=parent.js_mostravac_calendario',
                            'Pesquisa',
                            false
                           );

     } else {
       document.form1.vc05_i_codigo.value = ''; 
     }
  }
}

function js_mostravac_calendario(chave,erro) {

  document.form1.vc05_i_codigo.value = chave; 
  if (erro == true) {  

    document.form1.vc07_i_calendario.focus(); 
    document.form1.vc07_i_calendario.value = ''; 

  }
}

function js_mostravac_calendario1(chave1,chave2) {

  document.form1.vc07_i_calendario.value = chave1;
  document.form1.vc05_i_codigo.value     = chave2;
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