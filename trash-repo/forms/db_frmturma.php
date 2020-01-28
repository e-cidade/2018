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

//MODULO: educação
$clturma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed29_c_descr");
$clrotulo->label("ed16_i_capacidade");
$clrotulo->label("ed35_i_qtdperiodo");
$clrotulo->label("ed246_i_turno");
$clrotulo->label("ed11_i_codcenso");
$clrotulo->label("ed31_i_regimemat");
$clrotulo->label("ed223_i_regimematdiv");
$clrotulo->label("ed223_i_serie");
$ed57_i_escola = db_getsession("DB_coddepto");
$result        = $clescola->sql_record($clescola->sql_query($ed57_i_escola));
db_fieldsmemory($result,0);
if ($db_opcao != 1 && isset($ed57_i_codigo)) {

  $sWhere  = "ed60_i_turma = ".@$ed57_i_codigo." AND ed60_c_situacao = 'MATRICULADO'";
  $result4 = $clmatricula->sql_record($clmatricula->sql_query("",
                                                              "count(*) as ed57_i_nummatr",
                                                              "",
                                                              $sWhere
                                                             )
                                     );

  if ($clmatricula->numrows > 0) {
    db_fieldsmemory($result4,0);
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted57_i_codigo?>" width="15%">
   <?=@$Led57_i_codigo?>
  </td>
  <td>
    <?db_input('ed57_i_codigo',15,$Ied57_i_codigo,true,'text',3,"")?>
    <spam id ='codigoInep'>
      <?=@$Led57_i_codigoinep?>
      <?db_input('ed57_i_codigoinep',10,$Ied57_i_codigoinep,true,'text',$db_opcao,"")?>
    </spam>
     <?=@$Led57_i_tipoturma?>
     <?
     $x = array('1'=>'NORMAL','2'=>'EJA','3'=>'MULTIETAPA', '6' => 'PROGRESSÃO PARCIAL', '7' => 'CORREÇÃO DE FLUXO');
     if ($db_opcao == 2) {
       if ($ed57_i_tipoturma == 6) {
        $x = array('6' => 'PROGRESSÃO PARCIAL');
       } else if ($ed57_i_tipoturma != 6) {
         $x = array('1'=>'NORMAL','2'=>'EJA','3'=>'MULTIETAPA', '7' => 'CORREÇÃO DE FLUXO');
       }
     }
     db_select('ed57_i_tipoturma',$x,true,$db_opcao,"onchange='js_validaTipoTurma()'");
     ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_c_descr?>">
   <?=@$Led57_c_descr?>
  </td>
  <td>
   <?db_input('ed57_c_descr',80,$Ied57_c_descr,true,'text',$db_opcao,
              " onKeyUp=\"js_ValidaCamposEdu(this,2,'$GLOBALS[Sed57_c_descr]','f','t',event);\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_i_escola?>">
   <?db_ancora(@$Led57_i_escola,"",3);?>
  </td>
  <td>
   <?db_input('ed57_i_escola',15,$Ied57_i_escola,true,'text',3,"")?>
   <?db_input('ed18_c_nome',60,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_i_calendario?>">
   <?db_ancora(@$Led57_i_calendario,"js_pesquisaed57_i_calendario(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed57_i_calendario',15,$Ied57_i_calendario,true,'text',$db_opcao1,
              " onchange='js_pesquisaed57_i_calendario(false);'")?>
   <?db_input('ed52_c_descr',40,@$Ied52_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_i_base?>">
   <?db_ancora(@$Led57_i_base,"js_pesquisaed57_i_base(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed57_i_base',15,$Ied57_i_base,true,'text',$db_opcao1," onchange='js_pesquisaed57_i_base(false);'")?>
   <?db_input('ed31_c_descr',40,@$Ied31_c_descr,true,'text',3,'')?>
   <?db_input('ed29_i_ensino',10,@$Ied29_i_codigo,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted29_c_descr?>">
   <?=@$Led29_c_descr?>
  </td>
  <td>
   <?db_input('ed29_i_codigo',15,@$Ied29_i_codigo,true,'text',3,'')?>
   <?db_input('ed29_c_descr',40,@$Ied29_c_descr,true,'text',3,'')?>
   <?db_input('ed36_c_abrev',2,@$Ied36_c_abrev,true,'hidden',3,'')?>
   <?db_input('ed29_c_historico',1,@$Ied29_c_historico,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr id='regime_matricula'>
  <td nowrap title="<?=@$Ted31_i_regimemat?>">
   <?=@$Led31_i_regimemat?>
  </td>
  <td>
   <?db_input('ed31_i_regimemat',15,@$Ied31_i_regimemat,true,'text',3,"")?>
   <?db_input('ed218_c_nome',40,@$Ied218_c_nome,true,'text',3,"")?>
   <?db_input('ed218_c_divisao',1,@$Ied218_c_divisao,true,'hidden',3,"")?>
  </td>
 </tr>
 <tbody id="div_divisao"></tbody>
 <tbody id="div_etapa"></tbody>
 <?
 if ($db_opcao == 2 || $db_opcao == 3 && !isset($excluir)) {
   $result_etapa   = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("",
                                                                                          "*",
                                                                                          "ed223_i_ordenacao",
                                                                                          "ed220_i_turma = $ed57_i_codigo"
                                                                                         )
                                                       );
   $ed219_i_codigo = pg_result($result_etapa,0,'ed219_i_codigo');
   $ed219_c_nome   = pg_result($result_etapa,0,'ed219_c_nome');
   if ($ed219_i_codigo != "") {
    ?>
    <tr>
     <td nowrap title="<?=@$Ted223_i_regimematdiv?>" valign="top">
      <?=@$Led223_i_regimematdiv?>
      </td>
     <td>
      <?db_input('ed219_i_codigo',15,@$Ied219_i_codigo,true,'text',3,"")?>
      <?db_input('ed219_c_nome',40,@$Ied219_c_nome,true,'text',3,"")?>
     </td>
    </tr>
    <?
   }
  ?>
  <tr>
   <td nowrap title="<?=@$Ted223_i_serie?>" valign="top">
    <?=@$Led223_i_serie?>
   </td>
   <td>
    <?
    for ($p = 0; $p < $clturmaserieregimemat->numrows; $p++) {

      db_fieldsmemory($result_etapa,$p);
      if ($clturmaserieregimemat->numrows == 1) {

        $desab   = "disabled";
        $check   = "checked";
        $clique1 = "";

      } else {

        $desab = "disabled";
        if ($ed220_c_historico == "S") {
          $check = "checked";
        } else {
          $check = "";
        }
        $clique1 = "onclick=\"js_verificaetapahist($p)\"";
      }
      if ($ed29_c_historico == "N") {
        $visible = "hidden";
      } else {
        $visible = "visible";
      }
      echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%">';
      echo ' <input type="checkbox" name="etapa" id="etapa" value="" disabled checked> '.$ed11_i_codigo.' - '.$ed11_c_descr;
      echo '</td><td width="30%">';
      echo ' <b>Proc. Avaliação: </b>'.$ed40_i_codigo.' - '.$ed40_c_descr;
      echo '</td><td>';
      echo '<spam id ="aprovaAutomatico">';
      echo ' <b>Aprovação Automática: </b>';
      $x = array('N'=>'NÃO','S'=>'SIM');
      db_select('ed220_c_aprovauto',$x,true,$db_opcao," onchange=\"js_aprovauto(this.value,$ed220_i_codigo);\"");
      echo '</spam>';
      echo '<span id="checkhist" style="visibility:hidden">';
      echo ' <input type="checkbox" name="etapahistorico" id="etapahistorico" value="'.$ed220_i_codigo.'" '.
            $desab.' '.$check.' '.$clique1.'> Incluir no Histórico <br>';
      echo '</span>';
      echo '</td></tr></table>';
    }
    ?>
   </td>
  </tr>
  <?
 }
 ?>
 <tbody id="div_censoetapa">
 </tbody>
 <?
 if ($db_opcao == 2 || $db_opcao == 3 && !isset($excluir)) {

   ?>
   <tr id ='etapaCenso'>
    <td nowrap title="<?=@$Ted57_i_censoetapa?>">
     <?db_ancora(@$Led57_i_censoetapa,"",3);?>
    </td>
    <td>
     <?db_input('ed57_i_censoetapa',15,$Ied57_i_censoetapa,true,'text',3,'')?>
     <?db_input('ed266_c_descr',40,@$Ied266_c_descr,true,'text',3,'')?>
    </td>
   </tr>
   <?

 }
 ?>
 <tr>
  <td nowrap title="<?=@$Ted57_i_turno?>">
   <?db_ancora(@$Led57_i_turno,"js_pesquisaed57_i_turno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed57_i_turno',15,$Ied57_i_turno,true,'text',3," onchange='js_pesquisaed57_i_turno(false);'")?>
   <?db_input('ed15_c_nome',40,@$Ied15_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_i_sala?>">
   <?db_ancora(@$Led57_i_sala,"js_pesquisaed57_i_sala(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed57_i_sala',15,$Ied57_i_sala,true,'text',3," onchange='js_pesquisaed57_i_sala(false);'")?>
   <?db_input('ed16_c_descr',40,@$Ied16_c_descr,true,'text',3,'')?>
   <?=@$Led16_i_capacidade?>
   <?db_input('ed16_i_capacidade',5,@$Ied16_i_capacidade,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_c_medfreq?>">
   <?=@$Led57_c_medfreq?>
  </td>
  <td>
   <?
   $x = array(''=>'','PERÌODOS'=>'PERÍODOS','DIAS LETIVOS'=>'DIAS LETIVOS');
   db_select('ed57_c_medfreq',$x,true,$db_opcao,"");
   ?>
   <spam id = 'tipoAtendimento'>
     <?=@$Led57_i_tipoatend?>
     <?
     $x = array('0'=>'NÃO SE APLICA','1'=>'CLASSE HOSPITALAR','2'=>'UNIDADE DE INTERNAÇÃO','3'=>'UNIDADE PRISIONAL');
     db_select('ed57_i_tipoatend',$x,true,$db_opcao,"");
     ?>
   </spam>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_i_numvagas?>">
   <?=@$Led57_i_numvagas?>
  </td>
  <td>
   <?db_input('ed57_i_numvagas',10,$Ied57_i_numvagas,true,'text',$db_opcao," onKeyUp=\"js_calcvagas(this.value);\"")?>
   <?=@$Led57_i_nummatr?>
   <?db_input('ed57_i_nummatr',10,$Ied57_i_nummatr,true,'text',3,"")?>
   <b>Vagas Restantes:</b>
   <?db_input('restantes',10,'',true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted57_t_obs?>">
   <?=@$Led57_t_obs?>
  </td>
  <td>
   <table>
     <tr>
      <td>
   <?db_textarea('ed57_t_obs',3,90,$Ied57_t_obs,true,'text',$db_opcao,"","","",200)?>
   </td></tr></table>
  </td>
 </tr>
 <tr id='turnoAdicional'>
  <td nowrap title="<?=@$Ted246_i_turno?>">
   <?db_ancora(@$Led246_i_turno,"js_pesquisaed246_i_turno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed246_i_turno',15,$Ied246_i_turno,true,'text',1," onchange='js_pesquisaed246_i_turno(false);'")?>
   <?db_input('ed15_c_nomeadd',40,@$Ied15_c_nomeadd,true,'text',3,'')?>
  </td>
 </tr>
 <tr id='trMaisEducacao'>
   <td nowrap title="<?=@$Ted57_censoprogramamaiseducacao?>">
     <?=@$Led57_censoprogramamaiseducacao?>
   </td>
   <td>
     <?

      if (empty($ed57_censoprogramamaiseducacao)) {
        $ed57_censoprogramamaiseducacao = 'f';
      }
      $x = array('' => '', 't'=>'SIM', 'f'=>'NÃO');
      db_select('ed57_censoprogramamaiseducacao', $x, true, $db_opcao, '');
     ?>
   </td>
 </tr>
 <tr>
  <td colspan="2" height="19">
   <?
   $visivel = "hidden";
   if ($db_opcao == 2 || $db_opcao == 3 && !isset($excluir)) {

     for ($p = 0; $p < $clturmaserieregimemat->numrows; $p++) {

       db_fieldsmemory($result_etapa,$p);
       if (isset($ed36_c_abrev) && $ed36_c_abrev == "ER"
           && (@$ed11_i_codcenso == 30
               || @$ed11_i_codcenso == 31
               || @$ed11_i_codcenso == 32
               || @$ed11_i_codcenso == 33
               || @$ed11_i_codcenso == 34
               || @$ed11_i_codcenso == 39
               || @$ed11_i_codcenso == 40)) {

         $visivel          = "visible";
         $ver_cursoprofiss = "OK";
         break;

       }

       if (isset($ed36_c_abrev) && $ed36_c_abrev == "ES"
           && (@$ed11_i_codcenso == 30
               || @$ed11_i_codcenso == 31
               || @$ed11_i_codcenso == 32
               || @$ed11_i_codcenso == 33
               || @$ed11_i_codcenso == 34
               || @$ed11_i_codcenso == 39
               || @$ed11_i_codcenso == 40
               || @$ed11_i_codcenso == 62
               || @$ed11_i_codcenso == 63)) {

         $visivel          = "visible";
         $ver_cursoprofiss = "OK";
         break;

       }

       if(isset($ed36_c_abrev) && $ed36_c_abrev == "EJ" && (@$ed11_i_codcenso == 62 || @$ed11_i_codcenso == 63)) {

         $visivel          = "visible";
         $ver_cursoprofiss = "OK";
         break;

       }
     }
   }
   ?>
   <span name="cursoprofiss" id="cursoprofiss" style="visibility:<?=$visivel?>">
   <table>
    <tr>
     <td nowrap title="<?=@$Ted57_i_censocursoprofiss?>" width="34%">
      <?db_ancora(@$Led57_i_censocursoprofiss,"js_pesquisaed57_i_censocursoprofiss(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed57_i_censocursoprofiss',15,$Ied57_i_censocursoprofiss,true,'text',3,
                 " onchange='js_pesquisaed57_i_censocursoprofiss(false);'")?>
      <?db_input('ed247_c_descr',40,@$Ied247_c_descr,true,'text',3,'')?>
      <?db_input('ver_cursoprofiss',2,@$Iver_cursoprofiss,true,'text',3,'')?>
     </td>
    </tr>
   </table>
   </span>
  </td>
 </tr>
</table>
</center>
<input name="etapa_turma" type="hidden" value="">
<input name="linhaproc" type="hidden" value="">
<input name="ed37_c_tipo" type="hidden" value="<?=@$ed37_c_tipo?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?>
        <?=$db_opcao==1?"onclick='return js_validacaoInc();'":"onclick='return js_validacaoAltExc();'"?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
       <?=($db_botao2==false?"disabled":"")?>>
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()"
       <?=$db_opcao==1?"disabled":""?> <?=($db_botao2==false?"disabled":"")?>>
</form>
<iframe src="" name="iframe_verifica" id="iframe_verifica" width="0" height="0" frameborder="0"></iframe>
<script>
function js_pesquisaed57_i_calendario(mostra) {
  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_calendarioturma',
    	                'func_calendarioturma.php?funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_c_descr',
    	                'Pesquisa de Calendários',true);

  } else {

    if (document.form1.ed57_i_calendario.value != '') {

      js_OpenJanelaIframe('','db_iframe_calendarioturma',
    	                  'func_calendarioturma.php?pesquisa_chave='+document.form1.ed57_i_calendario.value+
    	                  '&funcao_js=parent.js_mostracalendario','Pesquisa',false);

    } else {

      document.form1.ed52_c_descr.value = '';
      limpaprocaval();

    }
  }
}

function js_mostracalendario(chave,erro) {

  document.form1.ed52_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed57_i_calendario.focus();
    document.form1.ed57_i_calendario.value = '';

  }

  limpaprocaval();
}

function js_mostracalendario1(chave1,chave2) {

  document.form1.ed57_i_calendario.value = chave1;
  document.form1.ed52_c_descr.value      = chave2;
  limpaprocaval();
  db_iframe_calendarioturma.hide();

}

function js_pesquisaed57_i_base(mostra) {

  var lBaseAtiva = true;
    
  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_base','func_baseturma.php?funcao_js=parent.js_mostrabase1|ed31_i_codigo|'+
    	                'ed31_c_descr|ed29_i_codigo|ed29_c_descr|ed36_c_abrev|ed218_i_codigo|ed218_c_nome|'+
    	                'ed218_c_divisao|ed31_c_medfreq|ed29_i_ensino|ed29_c_historico&lBaseAtiva='+lBaseAtiva,
    	                'Pesquisa de Bases Curriculares',true);

  } else {

    if (document.form1.ed57_i_base.value != '') {

      js_OpenJanelaIframe('','db_iframe_base','func_baseturma.php?pesquisa_chave='+document.form1.ed57_i_base.value+
    	                  '&funcao_js=parent.js_mostrabase&lBaseAtiva='+lBaseAtiva,'Pesquisa',false);

    } else {

      document.form1.ed31_c_descr.value     = '';
      document.form1.ed29_i_codigo.value    = '';
      document.form1.ed29_c_descr.value     = '';
      document.form1.ed36_c_abrev.value     = '';
      document.form1.ed31_i_regimemat.value = '';
      document.form1.ed218_c_nome.value     = '';
      document.form1.ed218_c_divisao.value  = '';
      document.form1.ed57_c_medfreq.value   = '';
      document.form1.ed29_i_ensino.value    = '';
      document.form1.ed29_c_historico.value = '';

    }
  }

  $('div_etapa').innerHTML = '';
  $('div_divisao').innerHTML = '';
  document.form1.etapa_turma.value = "";

}

function js_mostrabase(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,erro) {

  document.form1.ed31_c_descr.value     = chave1;
  document.form1.ed29_i_codigo.value    = chave2;
  document.form1.ed29_c_descr.value     = chave3;
  document.form1.ed36_c_abrev.value     = chave4;
  document.form1.ed31_i_regimemat.value = chave5;
  document.form1.ed218_c_nome.value     = chave6;
  document.form1.ed218_c_divisao.value  = chave7;
  document.form1.ed57_i_turno.value     = '';
  document.form1.ed15_c_nome.value      = '';

  if (chave8 == "P") {
    freq = "PERÌODOS";
  } else if (chave8 == "D") {
    freq = "DIAS LETIVOS";
  } else {
    freq = "";
 }

  document.form1.ed57_c_medfreq.value   = freq;
  document.form1.ed29_i_ensino.value    = chave9;
  document.form1.ed29_c_historico.value = chave10;

  if (erro == true) {

    document.form1.ed57_i_base.focus();
    document.form1.ed57_i_base.value = '';

  } else {

    if (chave7 == "S") {
      js_divisoes(document.form1.ed57_i_base.value);
    } else {
      js_etapa(chave5);
    }
  }
}

function js_mostrabase1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11) {

  document.form1.ed57_i_base.value      = chave1;
  document.form1.ed31_c_descr.value     = chave2;
  document.form1.ed29_i_codigo.value    = chave3;
  document.form1.ed29_c_descr.value     = chave4;
  document.form1.ed36_c_abrev.value     = chave5;
  document.form1.ed31_i_regimemat.value = chave6;
  document.form1.ed218_c_nome.value     = chave7;
  document.form1.ed218_c_divisao.value  = chave8;

  if (chave9 == "P") {
    freq = "PERÌODOS";
  } else {
    freq = "DIAS LETIVOS";
  }

  document.form1.ed57_c_medfreq.value   = freq;
  document.form1.ed29_i_ensino.value    = chave10;
  document.form1.ed29_c_historico.value = chave11;
  document.form1.ed57_i_turno.value     = '';
  document.form1.ed15_c_nome.value      = '';

  if (chave8 == "S") {
    js_divisoes(chave1);
  } else {
    js_etapa(chave6);
  }
  db_iframe_base.hide();
}

function js_pesquisaed57_i_censocursoprofiss(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_censocursoprofiss',
    	                'func_censocursoprofiss.php?funcao_js=parent.js_mostracensocursoprofiss1|'+
    	                'ed247_i_codigo|ed247_c_descr','Pesquisa de Cursos Profissionalizantes',true);

  }
}

function js_mostracensocursoprofiss1(chave1,chave2) {

  document.form1.ed57_i_censocursoprofiss.value = chave1;
  document.form1.ed247_c_descr.value            = chave2;
  db_iframe_censocursoprofiss.hide();

}

function js_pesquisaed57_i_turno(mostra) {

  if (document.form1.ed29_i_codigo.value == "") {

    alert("Informe a Base Curricular!");
    document.form1.ed29_i_codigo.value               = '';
    document.form1.ed57_c_medfreq.value              = '';
    document.form1.ed29_c_descr.value                = '';
    document.form1.ed57_i_base.style.backgroundColor = '#99A9AE';
    document.form1.ed57_i_base.focus();

  } else {

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_turno','func_turnoturma.php?curso='+document.form1.ed29_i_codigo.value+
    	                  '&funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome','Pesquisa de Turnos',true);

    } else {

      if (document.form1.ed57_i_turno.value != '') {

        js_OpenJanelaIframe('','db_iframe_turno','func_turnoturma.php?curso='+document.form1.ed29_i_codigo.value+
                            '&pesquisa_chave='+document.form1.ed57_i_turno.value+'&funcao_js=parent.js_mostraturno',
                            'Pesquisa',false);

      } else {
        document.form1.ed15_c_nome.value = '';
      }
    }
  }
}

function js_mostraturno(chave,erro) {

  document.form1.ed15_c_nome.value       = chave;
  document.form1.ed57_i_sala.value       = "";
  document.form1.ed16_c_descr.value      = "";
  document.form1.ed16_i_capacidade.value = "";

  if (erro == true) {

    document.form1.ed57_i_turno.focus();
    document.form1.ed57_i_turno.value = '';

  }

  if (document.form1.ed246_i_turno.value != "" && document.form1.ed246_i_turno.value == document.form1.ed57_i_turno.value) {

    alert("Turno principal escolhido("+chave+") é igual ao turno adicional.\nInforme outro turno ou troque o turno adicional!");
    document.form1.ed57_i_turno.value = "";
    document.form1.ed15_c_nome.value = "";

  }
}

function js_mostraturno1(chave1,chave2) {

  document.form1.ed57_i_turno.value      = chave1;
  document.form1.ed15_c_nome.value       = chave2;
  document.form1.ed57_i_sala.value       = "";
  document.form1.ed16_c_descr.value      = "";
  document.form1.ed16_i_capacidade.value = "";
  db_iframe_turno.hide();

  if (document.form1.ed246_i_turno.value != "" && document.form1.ed246_i_turno.value == chave1) {

    alert("Turno principal escolhido("+chave2+") é igual ao turno adicional.\nInforme outro turno ou troque o turno adicional!");
    document.form1.ed57_i_turno.value = "";
    document.form1.ed15_c_nome.value  = "";

  }
}

function js_pesquisaed220_i_procedimento(linhaproc,calendario,caldescr) {

  if (calendario == "") {
    alert("Informe o Calendário!");
  } else {

    js_OpenJanelaIframe('','db_iframe_procedimento','func_procedimentoturma.php?calendario='+calendario+
    	                '&caldescr='+caldescr+'&funcao_js=parent.js_mostraprocedimento1|ed40_i_codigo|ed40_c_descr',
    	                'Pesquisa de Procedimentos de Avaliação',true);
    document.form1.linhaproc.value = linhaproc;

  }
}

function js_mostraprocedimento1(chave1,chave2) {

  eval('document.form1.ed220_i_procedimento'+document.form1.linhaproc.value+'.value = chave1');
  eval('document.form1.ed40_c_descr'+document.form1.linhaproc.value+'.value = chave2');
  db_iframe_procedimento.hide();

}

function js_pesquisaed57_i_censoetapa(mostra) {

  if (document.form1.ed57_i_base.value == "") {

    alert("Informe a Base Curricular!");
    document.form1.ed57_i_censoetapa.value = '';
    document.form1.ed57_i_base.style.backgroundColor='#99A9AE';
    document.form1.ed57_i_base.focus();

  } else {

    js_OpenJanelaIframe('','db_iframe_censoetapa',
    	                'func_censoetapaturma.php?abrevtipoensino='+document.form1.ed36_c_abrev.value+
    	                '&funcao_js=parent.js_mostracensoetapa1|ed266_i_codigo|ed266_c_descr',
    	                'Pesquisa de Etapas do Censo',true);

  }
}

function js_mostracensoetapa1(chave1,chave2) {

  document.form1.ed57_i_censoetapa.value = chave1;
  document.form1.ed266_c_descr.value     = chave2;
  db_iframe_censoetapa.hide();

}

function js_pesquisaed57_i_sala(mostra) {

  if (document.form1.ed57_i_turno.value == "") {

    alert("Informe o Turno!");
    document.form1.ed57_i_sala.value = '';
    document.form1.ed57_i_turno.style.backgroundColor='#99A9AE';
    document.form1.ed57_i_turno.focus();

  } else if (document.form1.ed57_i_calendario.value == "") {

    alert("Informe o Calendário!");
    document.form1.ed57_i_sala.value = '';
    document.form1.ed57_i_calendario.style.backgroundColor='#99A9AE';
    document.form1.ed57_i_calendario.focus();

  } else {

    if (mostra == true) {

      if (document.form1.ed57_i_codigo.value == "") {
        turma = 0;
      } else {
        turma = document.form1.ed57_i_codigo.value;
      }
      js_OpenJanelaIframe('','db_iframe_sala','func_salaturma.php?turma='+turma+
    	                  '&curso='+document.form1.ed29_i_codigo.value+'&turno='+document.form1.ed57_i_turno.value+
    	                  '&calendario='+document.form1.ed57_i_calendario.value+
    	                  '&funcao_js=parent.js_mostrasala1|ed16_i_codigo|ed16_c_descr|ed16_i_capacidade',
    	                  'Pesquisa de Salas',true);
    } else {

      if (document.form1.ed57_i_sala.value != '') {

        js_OpenJanelaIframe('','db_iframe_sala','func_salaturma.php?turma='+turma+
                            '&curso='+document.form1.ed29_i_codigo.value+'&turno='+document.form1.ed57_i_turno.value+
                            '&calendario='+document.form1.ed57_i_calendario.value+
                            '&pesquisa_chave='+document.form1.ed57_i_sala.value+'&funcao_js=parent.js_mostrasala',
                            'Pesquisa',false);

      } else {
        document.form1.ed16_c_descr.value = '';
      }
    }
  }
}

function js_mostrasala(chave1,erro,chave2) {

  document.form1.ed16_c_descr.value      = chave1;
  document.form1.ed16_i_capacidade.value = chave2;
  document.form1.ed57_i_numvagas.value   = chave2;

  if (erro == true) {

    document.form1.ed57_i_sala.focus();
    document.form1.ed57_i_sala.value = '';

  } else {

    js_calcvagas();
    if (document.form1.ed57_i_codigo.value == "") {
      turma = 0;
    } else {
      turma = document.form1.ed57_i_codigo.value;
    }
    iframe_verifica.location.href = "edu1_turma004.php?turma="+turma+"&escola="+document.form1.ed57_i_escola.value+
                                    "&turno="+document.form1.ed57_i_turno.value+
                                    "&calendario="+document.form1.ed57_i_calendario.value+
                                    "&sala="+document.form1.ed57_i_sala.value;
  }
}

function js_mostrasala1(chave1,chave2,chave3) {

  document.form1.ed57_i_sala.value       = chave1;
  document.form1.ed16_c_descr.value      = chave2;
  document.form1.ed16_i_capacidade.value = chave3;
  document.form1.ed57_i_numvagas.value   = chave3;
  js_calcvagas();

  if (document.form1.ed57_i_codigo.value == "") {
    turma = 0;
  } else {
    turma = document.form1.ed57_i_codigo.value;
  }
  iframe_verifica.location.href = "edu1_turma004.php?turma="+turma+
                                  "&escola="+document.form1.ed57_i_escola.value+
                                  "&turno="+document.form1.ed57_i_turno.value+
                                  "&calendario="+document.form1.ed57_i_calendario.value+"&sala="+chave1;
  db_iframe_sala.hide();
}

function js_pesquisa() {

  js_OpenJanelaIframe('','db_iframe_turma','func_turma.php?funcao_js=parent.js_preenchepesquisa|ed57_i_codigo',
		              'Pesquisa de Turmas',true);

}

function js_preenchepesquisa(chave) {

  db_iframe_turma.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}

function js_pesquisaed246_i_turno(mostra) {

  if (document.form1.ed29_i_codigo.value == "") {

    alert("Informe a Base Curricular!");
    document.form1.ed29_i_codigo.value               = '';
    document.form1.ed57_c_medfreq.value              = '';
    document.form1.ed29_c_descr.value                = '';
    document.form1.ed57_i_base.style.backgroundColor = '#99A9AE';
    document.form1.ed57_i_base.focus();

  } else if (document.form1.ed57_i_turno.value == "") {

    alert("Primeiro informe o turno principal da turma!");
    document.form1.ed57_i_turno.style.backgroundColor='#99A9AE';
    document.form1.ed57_i_turno.focus();

  } else {

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_turnoadd','func_turnoaddturma.php?curso='+document.form1.ed29_i_codigo.value+
    	                  '&turnoprinc='+document.form1.ed57_i_turno.value+'&funcao_js=parent.js_mostraturnoadd1|'+
    	                  'ed15_i_codigo|ed15_c_nome','Pesquisa de Turno Adicional',true);

    } else {

      if (document.form1.ed246_i_turno.value != '') {

        js_OpenJanelaIframe('','db_iframe_turnoadd','func_turnoaddturma.php?curso='+document.form1.ed29_i_codigo.value+
                            '&turnoprinc='+document.form1.ed57_i_turno.value+
                            '&pesquisa_chave='+document.form1.ed246_i_turno.value+'&funcao_js=parent.js_mostraturnoadd',
                            'Pesquisa',false);

      } else {
        document.form1.ed15_c_nomeadd.value = '';
      }
    }
  }
}

function js_mostraturnoadd(chave,erro) {

  document.form1.ed15_c_nomeadd.value = chave;
  if (erro == true) {

    document.form1.ed246_i_turno.focus();
    document.form1.ed246_i_turno.value = '';

  }
}

function js_mostraturnoadd1(chave1,chave2) {

  document.form1.ed246_i_turno.value  = chave1;
  document.form1.ed15_c_nomeadd.value = chave2;
  db_iframe_turnoadd.hide();

}

function js_novo() {
  parent.location.href="edu1_turmaabas001.php";
}

function js_divisoes(codbase) {

  document.form1.etapa_turma.value = "";
  if (codbase == 0) {

    $('div_divisao').innerHTML = "";
    return false;

  }
  js_divCarregando("Aguarde, buscando registro(s)","msgBox");
  var sAction = 'PesquisaDivisao';
  var url     = 'edu1_turmaRPC.php';
  parametros  = 'sAction='+sAction+'&base='+codbase;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaDivisao
                                   });
}

function js_retornaPesquisaDivisao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml  = '<tr>';
  sHtml += ' <td valign="top"><b><?=@$Led223_i_regimematdiv?></b>';
  sHtml += ' </td>';
  sHtml += ' <td>';

  if (oRetorno.length == 0) {

    sHtml += '  Nenhuma divisão cadastrada para o regime de matrícula selecionado.';
    sHtml += '  <input type="hidden" name="divisao" id="divisao" value="N">';

  } else {

    cont = 0;
    for (var i = 0;i < oRetorno.length; i++) {

      cont++;
      with (oRetorno[i]) {

        sHtml += '  <input type="radio" name="divisao" id="divisao" value="'+ed219_i_codigo+'" '+
                           ' onclick="js_etapadivisao(this.value);"> '+ed219_c_nome.urlDecode();
        if ((cont%3) == 0) {
          sHtml += '<br>';
        }
      }
    }
  }
  sHtml += ' </td>';
  sHtml += '</tr>';
  $('div_divisao').innerHTML = sHtml;
}

function js_etapadivisao(coddivisao) {

  document.form1.etapa_turma.value = "";
  $('div_etapa').innerHTML = '';
  js_divCarregando("Aguarde, buscando registro(s)","msgBox");
  var sAction = 'PesquisaEtapaDivisao';
  var url     = 'edu1_turmaRPC.php';
  parametros  = 'sAction='+sAction+'&coddivisao='+coddivisao+'&codregime='+$('ed31_i_regimemat').value+
                '&codensino='+$('ed29_i_ensino').value;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaEtapaDivisao
                                   });

}

function js_retornaPesquisaEtapaDivisao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml  = '<tr>';
  sHtml += ' <td valign="top"><b><?=@$Led223_i_serie?></b>';
  sHtml += ' </td>';
  sHtml += ' <td>';

  if (oRetorno.length == 0) {

    sHtml += '  Nenhuma disciplina cadastrada na base curricular selecionada..';
    sHtml += '  <input type="hidden" name="etapa" id="etapa" value="N">';

  } else {

    for (var i = 0;i < oRetorno.length; i++) {

      with (oRetorno[i]) {

        if (oRetorno.length == 1) {

          desab   = "disabled";
          check   = "checked";
          clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
          clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

        } else {

          desab   = "";
          check   = "";
          clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
          clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

        }
        if ($('ed29_c_historico').value == "N") {

          check   = "checked";
          visible = "hidden";

        } else {
          visible = "visible";
        }
        sHtml += '  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%">';
        sHtml += '  <input type="hidden" name="etapacenso" id="etapacenso" value="'+ed11_i_codcenso.urlDecode()+'">';
        sHtml += '  <input type="hidden" name="descretapacenso" id="descretapacenso" value="'+ed266_c_descr.urlDecode()+'">';
        sHtml += '  <input type="checkbox" name="etapa" id="etapa" ';
        sHtml += '     value="'+ed223_i_codigo+'" '+clique+'> '+ed11_i_codigo.urlDecode()+' - '+ed11_c_descr.urlDecode();
        sHtml += '  </td><td>';
        sHtml += '  <span id="procavalauto'+i+'">';
        sHtml += '  </span>';
        sHtml += '  <span id="checkhist" style="visibility:hidden">';
        sHtml += '   <input type="checkbox" name="etapahistorico" id="etapahistorico" ';
        sHtml += '          value="" '+desab+' '+check+' '+clique1+'> Incluir no Histórico <br>';
        sHtml += '  </span>';
        sHtml += '  </td></tr></table>';
      }
    }
  }
  sHtml += ' </td>';
  sHtml += '</tr>';
  $('div_etapa').innerHTML = sHtml;
}

function js_etapa(codregime) {

  document.form1.etapa_turma.value = "";
  js_divCarregando("Aguarde, buscando registro(s)","msgBox");
  var sAction = 'PesquisaEtapa';
  var url     = 'edu1_turmaRPC.php';
  parametros  = 'sAction='+sAction+'&codregime='+codregime+'&codensino='+$('ed29_i_ensino').value+
                '&codbase='+$('ed57_i_base').value;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaEtapa
                                   });
}

function js_retornaPesquisaEtapa(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '<tr>';
  sHtml += ' <td valign="top"><b><?=@$Led223_i_serie?></b>';
  sHtml += ' </td>';
  sHtml += ' <td>';

  if (oRetorno.length == 0) {

    sHtml += '  Nenhuma disciplina cadastrada na base curricular selecionada.';
    sHtml += '  <input type="hidden" name="etapa" id="etapa" value="N">';

  } else {

    for (var i = 0;i < oRetorno.length; i++) {

      with (oRetorno[i]) {

        if (oRetorno.length == 1) {

          desab   = "disabled";
          check   = "checked";
          clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
          clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

        } else {

          desab   = "";
          check   = "";
          clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
          clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

        }

        if ($('ed29_c_historico').value == "N") {

          check   = "checked";
          visible = "hidden";

        } else {
          visible = "visible";
        }
        sHtml += '  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%">';
        sHtml += '  <input type="hidden" name="etapacenso" id="etapacenso" value="'+ed11_i_codcenso.urlDecode()+'">';
        sHtml += '  <input type="hidden" name="descretapacenso" id="descretapacenso" ';
        sHtml += '          value="'+ed266_c_descr.urlDecode()+'">';
        sHtml += '  <input type="checkbox" name="etapa" id="etapa" ';
        sHtml += '    value="'+ed223_i_codigo+'" '+clique+'> '+ed11_i_codigo.urlDecode()+' - '+ed11_c_descr.urlDecode();
        sHtml += '  </td><td>';
        sHtml += '  <span id="procavalauto'+i+'">';
        sHtml += '  </span>';
        sHtml += '  <span id="checkhist" style="visibility:hidden">';
        sHtml += '   <input type="checkbox" name="etapahistorico" id="etapahistorico" ';
        sHtml += '          value="" '+desab+' '+check+' '+clique1+'> Incluir no Histórico <br>';
        sHtml += '  </span>';
        sHtml += '  </td></tr></table>';
      }
    }
  }
  sHtml += ' </td>';
  sHtml += '</tr>';
  $('div_etapa').innerHTML = sHtml;
}

function js_calcvagas() {

  if (document.form1.ed57_i_numvagas.value - document.form1.ed57_i_nummatr.value < 0) {
    document.form1.restantes.value = 0;
  } else {
    document.form1.restantes.value = document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value;
  }
}

if (document.form1.ed57_i_nummatr.value == "") {
  document.form1.ed57_i_nummatr.value = 0;
}

if (document.form1.ed57_i_numvagas.value == "") {
  document.form1.ed57_i_numvagas.value = 0;
}

if (document.form1.ed16_i_capacidade.value == "") {
  document.form1.ed16_i_capacidade.value = 0;
}

if (document.form1.restantes.value == "") {
  document.form1.restantes.value = 0;
}

if (document.form1.ed57_i_numvagas.value - document.form1.ed57_i_nummatr.value < 0) {
  document.form1.restantes.value = 0;
} else {
  document.form1.restantes.value = document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value;
}

function js_validacaoInc() {

  if (document.form1.ver_cursoprofiss.value == "OK" && document.form1.ed57_i_censocursoprofiss.value == "") {

    alert("Informe o Curso Profissionalizante!");
    return false;

  }

  if (document.form1.ed57_i_censoetapa && document.form1.ed57_i_censoetapa.value == "") {

    alert("Informe a Etapa Censo!");
    return false;

  }

  if (document.form1.divisao) {

    tam = document.form1.divisao.length;
    if (tam == undefined) {

      if (document.form1.divisao.value == "N") {

        alert("Informe alguma divisão do Regime de Matrícula!");
        return false;

      } else {

        if (document.form1.divisao.checked == false) {

          alert("Informe alguma divisão do Regime de Matrícula!");
          return false;

        }
      }

    } else {

      cont = 0;
      for (i = 0; i < tam; i++) {

        if (document.form1.divisao[i].checked == true) {
          cont++;
        }
      }

      if (cont == 0) {

        alert("Informe alguma divisão do Regime de Matrícula!");
        return false;

      }
    }
  }

  if (document.form1.etapa) {

    tam = document.form1.etapa.length;
    etapa_reg = "";
    etapa_sep = "";

    if (tam == undefined) {

      if (document.form1.etapa.value == "N") {

        alert("Informe alguma etapa!");
        return false;

      } else {

        if (document.form1.etapa.checked == false) {

          alert("Informe alguma etapa!");
          return false;

        } else {

          if (document.form1.etapahistorico.checked == true) {
            historico = "S";
          } else {
            historico = "N";
          }
          etapa_reg = document.form1.etapa.value+"|"+historico+"|0";
        }
      }

      if (document.form1.ed220_i_procedimento0.value == "") {

        alert("Informe o procedimento de avaliação para a(s) etapa(s) selecionada(s)!");
        return false;

      }
    } else {

      cont_hist = 0;
      for (i = 0; i < tam; i++) {

        if (document.form1.etapahistorico[i].checked == true) {

          historico = "S";
          cont_hist++;

        } else {
          historico = "N";
        }

        if (document.form1.etapa[i].checked == true) {

          etapa_reg += etapa_sep+document.form1.etapa[i].value+"|"+historico+"|"+i;
          etapa_sep = ",";

          if (eval('document.form1.ed220_i_procedimento'+i+'.value==""')) {

            alert("Informe o procedimento de avaliação para a(s) etapa(s) selecionada(s)!");
            return false;

          }
        }
      }
      if (etapa_reg == "") {

        alert("Informe alguma etapa!");
        return false;

      }

      if (cont_hist == 0) {

        alert("Alguma das etapas deve estar marcada como Incluir no Histórico!");
        return false;

      }
    }
    document.form1.etapa_turma.value = etapa_reg;
  }
  return true;
}

function js_validacaoAltExc() {

  if (document.form1.ver_cursoprofiss.value == "OK" && document.form1.ed57_i_censocursoprofiss.value == "") {

    alert("Informe o Curso Profissionalizante!");
    return false;

  }

  if (document.form1.ed57_i_censoetapa && document.form1.ed57_i_censoetapa.value == "") {

    alert("Informe a Etapa Censo!");
    return false;

  }

  tam       = document.form1.etapahistorico.length;
  etapa_reg = "";
  etapa_sep = "";
  if (tam == undefined) {
    etapa_reg = document.form1.etapahistorico.value+"|S|0";
  } else {

    cont_hist = 0;
    for (i = 0; i < tam; i++) {

      if (document.form1.etapahistorico[i].checked == true) {

        cont_hist++;
        etapa_reg += etapa_sep+document.form1.etapahistorico[i].value+"|S|"+i;
        etapa_sep = ",";

      } else {

        etapa_reg += etapa_sep+document.form1.etapahistorico[i].value+"|N|"+i;
        etapa_sep = ",";

      }
    }
    if (cont_hist == 0) {

      alert("Alguma das etapas deve estar marcada como Incluir no Histórico!");
      return false;

    }
  }
  document.form1.etapa_turma.value = etapa_reg;
  return true;
}

function js_verificaetapa(linha,codcenso,descrcenso) {

  document.form1.etapa_turma.value = "";
  tam = document.form1.etapa.length;
  if (tam == undefined) {

    if (document.form1.etapa.checked == false) {
      document.form1.etapahistorico.checked = false;
    } else {
      document.form1.etapahistorico.checked = true;
    }

    if (document.form1.etapa.checked == false) {

      $('div_censoetapa').innerHTML                            = "";
      $('procavalauto0').innerHTML                             = "";
      document.getElementById('cursoprofiss').style.visibility = "hidden";
      document.form1.ed57_i_censocursoprofiss.value            = "";
      document.form1.ed247_c_descr.value                       = "";
      document.form1.ver_cursoprofiss.value                    = "";

    } else {

      codcenso = document.form1.etapacenso.value;
      if (document.form1.ed36_c_abrev.value == "ER"
          && (codcenso == 30
              || codcenso == 31
              || codcenso == 32
              || codcenso == 33
              || codcenso == 34
              || codcenso == 39
              || codcenso == 40)) {

        document.getElementById('cursoprofiss').style.visibility = "visible";
        document.form1.ver_cursoprofiss.value                    = "OK";

      } else if (document.form1.ed36_c_abrev.value == "ES"
                 && (codcenso == 30
                     || codcenso == 31
                     || codcenso == 32
                     || codcenso == 33
                     || codcenso == 34
                     || codcenso == 39
                     || codcenso == 40
                     || codcenso == 62
                     || codcenso == 63)) {

        document.getElementById('cursoprofiss').style.visibility = "visible";
        document.form1.ver_cursoprofiss.value                    = "OK";

      } else if (document.form1.ed36_c_abrev.value == "EJ" && (codcenso == 62 || codcenso == 63)) {

        document.getElementById('cursoprofiss').style.visibility = "visible";
        document.form1.ver_cursoprofiss.value = "OK";

      }
      sHtml =  '<tr>';
      sHtml += ' <td>';
      sHtml += '  <b><?=@$Led57_i_censoetapa?></b>';
      sHtml += ' </td>';
      sHtml += ' <td>';
      sHtml += '  <input type="text" name="ed57_i_censoetapa" id="ed57_i_censoetapa" size="15" maxlength="15" ';
      sHtml += '          value="'+codcenso+'" style="background:#DEB887" readonly>';
      sHtml += '  <input type="text" name="ed266_c_descr" id="ed266_c_descr" size="40" maxlength="40" ';
      sHtml += '         value="'+descrcenso+'" style="background:#DEB887" readonly>';
      sHtml += ' </td>';
      sHtml += '</tr>';
      $('div_censoetapa').innerHTML = sHtml;
      sHtml  = '  <a href="javascript:js_pesquisaed220_i_procedimento(0,document.form1.ed57_i_calendario.value,document.form1.ed52_c_descr.value);"><b>Proc.Avaliação:</b></a>';
      sHtml += '  <input type="text" name="ed220_i_procedimento0" id="ed220_i_procedimento0" size="15" maxlength="15" ';
      sHtml += '          value="" style="background:#DEB887" readonly>';
      sHtml += '  <input type="text" name="ed40_c_descr0" id="ed40_c_descr0" size="40" maxlength="40" ';
      sHtml += '         value="" style="background:#DEB887" readonly>';
      sHtml += '  <spam id ="aprovaAutomatico">';
      sHtml += '    <b>Aprov.Automática:</b>';
      sHtml += '    <select name="ed220_c_aprovauto0" id="ed220_c_aprovauto0">';
      sHtml += '      <option value="N">NÃO</option>';
      sHtml += '      <option value="S">SIM</option>';
      sHtml += '    </select>';
      sHtml += '  </spam>';
      $('procavalauto0').innerHTML = sHtml;

    }

  } else {

    if (document.form1.etapa[linha].checked == false) {

      document.form1.etapahistorico[linha].checked = false;
      $('procavalauto'+linha).innerHTML = "";

    } else {

      document.form1.etapahistorico[linha].checked = true;
      sHtml  = '  <a href="javascript:js_pesquisaed220_i_procedimento('+linha+',document.form1.ed57_i_calendario.value,document.form1.ed52_c_descr.value);"><b>Proc.Avaliação:</b></a>';
      sHtml += '  <input type="text" name="ed220_i_procedimento'+linha+'" id="ed220_i_procedimento'+linha+'" ';
      sHtml += '         size="5" maxlength="5" value="" style="background:#DEB887" readonly>';
      sHtml += '  <input type="text" name="ed40_c_descr'+linha+'" id="ed40_c_descr'+linha+'" ';
      sHtml += '         size="30" maxlength="30" value="" style="background:#DEB887" readonly>';
      sHtml += '  <spam id ="aprovaAutomatico">';
      sHtml += '    <b>Aprov.Automática:</b>';
      sHtml += '    <select name="ed220_c_aprovauto'+linha+'" id="ed220_c_aprovauto'+linha+'">';
      sHtml += '     <option value="N">NÃO</option>';
      sHtml += '     <option value="S">SIM</option>';
      sHtml += '    </select>';
      sHtml += '  </spam>';
      $('procavalauto'+linha).innerHTML = sHtml;

    }

    chekado     = "";
    sep_chekado = "";
    conta       = 0;

    for (i = 0; i < tam; i++) {

      if (document.form1.etapa[i].checked == true) {

        conta++;
        codcenso   = document.form1.etapacenso[i].value;
        descrcenso = document.form1.descretapacenso[i].value;
        if (document.form1.ed36_c_abrev.value == "ER"
            && (codcenso == 30
                || codcenso == 31
                || codcenso == 32
                || codcenso == 33
                || codcenso == 34
                || codcenso == 39
                || codcenso == 40)) {

          chekado    += sep_chekado+codcenso;
          sep_chekado = ",";

        } else if (document.form1.ed36_c_abrev.value == "ES"
                   && (codcenso == 30
                       || codcenso == 31
                       || codcenso == 32
                       || codcenso == 33
                       || codcenso == 34
                       || codcenso == 39
                       || codcenso == 40
                       || codcenso == 62
                       || codcenso == 63)) {

          chekado    += sep_chekado+codcenso;
          sep_chekado = ",";

        } else if (document.form1.ed36_c_abrev.value == "EJ" && (codcenso == 62 || codcenso == 63)) {

          chekado += sep_chekado+codcenso;
          sep_chekado = ",";

        }
      }
    }
    if (chekado == "") {

      document.getElementById('cursoprofiss').style.visibility = "hidden";
      document.form1.ed57_i_censocursoprofiss.value            = "";
      document.form1.ed247_c_descr.value                       = "";
      document.form1.ver_cursoprofiss.value                    = "";

    } else {

      document.getElementById('cursoprofiss').style.visibility = "visible";
      document.form1.ver_cursoprofiss.value                    = "OK";

    }

    if (conta == 0) {
      sHtml = '';
    } else if (conta == 1) {

      sHtml  =  '<tr>';
      sHtml += ' <td>';
      sHtml += '  <b><?=@$Led57_i_censoetapa?></b>';
      sHtml += ' </td>';
      sHtml += ' <td>';
      sHtml += '  <input type="text" name="ed57_i_censoetapa" id="ed57_i_censoetapa" size="15" maxlength="15" ';
      sHtml += '         value="'+codcenso+'" style="background:#DEB887" readonly>';
      sHtml += '  <input type="text" name="ed266_c_descr" id="ed266_c_descr" size="40" maxlength="40" ';
      sHtml += '         value="'+descrcenso+'" style="background:#DEB887" readonly>';
      sHtml += ' </td>';
      sHtml += '</tr>';

    } else {

      sHtml =  '<tr>';
      sHtml += ' <td>';
      sHtml += '  <a href="javascript:js_pesquisaed57_i_censoetapa();"><b><?=@$Led57_i_censoetapa?></b></a>';
      sHtml += ' </td>';
      sHtml += ' <td>';
      sHtml += '  <input type="text" name="ed57_i_censoetapa" id="ed57_i_censoetapa" size="15" maxlength="15"';
      sHtml += '         value="" style="background:#DEB887" readonly>';
      sHtml += '  <input type="text" name="ed266_c_descr" id="ed266_c_descr" size="40" maxlength="40"';
      sHtml += '         value="" style="background:#DEB887" readonly>';
      sHtml += ' </td>';
      sHtml += '</tr>';

    }
    $('div_censoetapa').innerHTML = sHtml;
  }
  js_validaTipoTurma();
}

function js_verificaetapahist(linha) {

  document.form1.etapa_turma.value = "";
  if (document.form1.etapahistorico[linha].checked == true) {
    document.form1.etapa[linha].checked = true;
  }
}

function limpaprocaval() {

  if (document.form1.etapa) {

    tam = document.form1.etapa.length;
    if (tam == undefined) {

      document.form1.ed220_i_procedimento0.value = "";
      document.form1.ed40_c_descr0.value = "";

    } else {

      for (i = 0; i < tam; i++) {

        if (eval('document.form1.ed220_i_procedimento'+i)) {

          eval('document.form1.ed220_i_procedimento'+i+'.value = ""');
          eval('document.form1.ed40_c_descr'+i+'.value = ""');

        }
      }
    }
  }
}

function js_aprovauto(valor,codigo) {

  js_divCarregando("Aguarde, atualizando registro","msgBox");
  var sAction = 'AtualizaAuto';
  var url     = 'edu1_turmaRPC.php';
  parametros  = 'sAction='+sAction+'&valorauto='+valor+'&codtsrmat='+codigo;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaAtualizaAuto
                                   });

}

function js_retornaAtualizaAuto() {
  js_removeObj("msgBox");
}

function js_validaTipoTurma() {

  var iTipoTurma = $F('ed57_i_tipoturma');

  if ($('aprovaAutomatico')) {
    $('aprovaAutomatico').style.display = '';
  }
  $('codigoInep').style.display       = '';
  $('tipoAtendimento').style.display  = '';
  $('regime_matricula').style.display = 'table-row';
  $('turnoAdicional').style.display   = 'table-row';
  if ($('etapaCenso')) {
    $('etapaCenso').style.display = 'table-row';
  }
  $('trMaisEducacao').style.display   = 'table-row';


  if (iTipoTurma == 6) {

    if ($('aprovaAutomatico')) {
      $('aprovaAutomatico').style.display = 'none';
    }
    $('codigoInep').style.display       = 'none';
    $('tipoAtendimento').style.display  = 'none';
    $('regime_matricula').style.display = 'none';
    $('turnoAdicional').style.display   = 'none';
    $('trMaisEducacao').style.display   = 'none';
    if ($('etapaCenso')) {
      $('etapaCenso').style.display  = 'none';
    }
  }

  if (iTipoTurma == 2) {

	  $('trMaisEducacao').style.display         = 'none';
	  $('ed57_censoprogramamaiseducacao').value = '';
  }
}
js_validaTipoTurma();
if($F('ed57_i_tipoturma') == 6 ) {
  parent.document.formaba.a5.disabled = true;
}
</script>