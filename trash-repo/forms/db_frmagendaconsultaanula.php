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

//MODULO: Ambulatorial
$oDaoAgendaConsultaAnula->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("sd23_d_consulta");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts114_i_codigo?>">
      <?=@$Ls114_i_codigo?>
    </td>
    <td> 
      <?
      db_input('s114_i_codigo', 10, $Is114_i_codigo, true, 'text', 3, "");
      db_input('iIdJanela', 2, '', true, 'hidden', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts114_i_agendaconsulta?>">
      <?
      db_ancora(@$Ls114_i_agendaconsulta, "js_pesquisas114_i_agendaconsulta(true);", 3);
      ?>
    </td>
    <td> 
      <?
      db_input('s114_i_agendaconsulta', 10, $Is114_i_agendaconsulta, true, 'text', 3, 
               " onchange='js_pesquisas114_i_agendaconsulta(false);'"
              );
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts114_d_data?>">
      <?=@$Ls114_d_data?>
    </td>
    <td> 
      <?
      db_inputdata('s114_d_data', @$s114_d_data_dia, @$s114_d_data_mes, @$s114_d_data_ano, 
                   true, 'text', 3, ""
                  );
      ?>
    </td>
  </tr>
  <tr style="display: <?=isset($lExibirPaciente) ? "''" : 'none'?>;">
    <td nowrap>
      <b>Paciente</b>
    </td>
    <td> 
      <?
      db_input('z01_nome', 50, '', true, 'text', 3, '');
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Ts114_v_motivo?>">
      <?=@$Ls114_v_motivo?>
    </td>
    <td> 
      <?
      db_input('s114_v_motivo', 50, $Is114_v_motivo, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts114_i_situacao?>">
      <?=@$Ls114_i_situacao?>
    </td>
    <td> 
      <?
      $aX = array('1'=>'Cancelado', '2'=>'Faltou', '3'=>'Outros');
      db_select('s114_i_situacao', $aX, true, $db_opcao, "");
      ?>
    </td>
  </tr>
</table>
  <p>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
  type="submit" id="db_opcao" 
  value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" 
  <?=($db_botao == false ? "disabled" : "")?>>
<? 
if($db_opcao != 1) { 
?>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<?
}
?>
<input name="fechar" type="submit" id="fechar" value="Fechar" onclick="js_fechar();">

</center>
</form>

<script>

function js_fechar() {
  
  iIdJanela = document.getElementById('iIdJanela').value;
  if (iIdJanela == '') {
    parent.db_iframe_agendamento.hide();
  } else {
    eval('parent.db_iframe_agendamento'+iIdJanela+'.hide()');
  }
  
}

function js_pesquisas114_i_agendaconsulta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo', 'db_iframe_agendamentos', 'func_agendamentos.php?funcao_js=parent.js_mostraagendamentos1|sd23_i_codigo|sd23_d_consulta', 'Pesquisa', true);
  }else{
     if(document.form1.s114_i_agendaconsulta.value != ''){ 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_agendamentos', 'func_agendamentos.php?pesquisa_chave='+document.form1.s114_i_agendaconsulta.value+'&funcao_js=parent.js_mostraagendamentos', 'Pesquisa', false);
     }else{
       document.form1.sd23_d_consulta.value = ''; 
     }
  }
}
function js_mostraagendamentos(chave, erro){
  document.form1.sd23_d_consulta.value = chave; 
  if(erro==true){ 
    document.form1.s114_i_agendaconsulta.focus(); 
    document.form1.s114_i_agendaconsulta.value = ''; 
  }
}
function js_mostraagendamentos1(chave1, chave2){
  document.form1.s114_i_agendaconsulta.value = chave1;
  document.form1.sd23_d_consulta.value = chave2;
  db_iframe_agendamentos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo', 'db_iframe_agendaconsultaanula', 'func_agendaconsultaanula.php?funcao_js=parent.js_preenchepesquisa|s114_i_codigo', 'Pesquisa', true);
}
function js_preenchepesquisa(chave){
  db_iframe_agendaconsultaanula.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>