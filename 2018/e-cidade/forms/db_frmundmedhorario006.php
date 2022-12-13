<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: saude
$clunidademedicos->rotulo->label();
$clundmedhorario->rotulo->label();
$clrotulo = new rotulocampo;
//Nome do profissional
$clrotulo->label("z01_nome");
//Unidade/Departamento
$clrotulo->label("descrdepto");
//Rhcbo
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
//sau_turnoatend
$clrotulo->label("sd43_cod_turnat");
$clrotulo->label("sd43_v_descricao");
$clrotulo->label("sd43_c_horainicial");
$clrotulo->label("sd43_c_horafinal");

if ( !isset($sd30_i_diasemana) ) {
  $sd30_i_diasemana = null;
}

?>

<form name="form1" method="post">
  <div class="container">
    <fieldset>
      <legend><b>Horário de Atendimento</b></legend>
      <table>
        <tr>
          <td nowrap title="<?php echo @$Tsd04_i_medico?>"><?php echo@$Lsd04_i_medico?></td>
          <td>
             <?php
               db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',3);
               db_input('z01_nome',59,$Iz01_nome,true,'text',3,'');
               db_input("possui_agenda", 10, null, true, 'hidden');
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap id='ctnAncoraVinculo' title="<?php echo @$Tsd30_i_undmed?>">
              <?php
                 db_ancora(@$Lsd30_i_undmed,"js_pesquisasd30_i_undmed(true);",$db_opcao);
              ?>
          </td>
          <td>
              <?php
                db_input('sd30_i_codigo',10,$Isd30_i_codigo,true,'hidden',$db_opcao);
                db_input('sd30_i_undmed',10,$Isd30_i_undmed,true,'text',$db_opcao," onchange='js_pesquisasd30_i_undmed(false);'");
                db_input('rh70_descr',59,$Irh70_descr,true,'text',3,'');
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Tsd04_i_unidade?>">
             <?php echo @$Lsd04_i_unidade?>
          </td>
          <td>
          <?php
             db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',3);
             db_input('descrdepto',59,$Idescrdepto,true,'text',3,'');
             db_input('sd30_i_turno',10,$Isd43_cod_turnat,true,'hidden',3);
             db_input('sd43_v_descricao',10,$Isd43_v_descricao,true,'hidden',3);
             db_input('sd43_c_horainicial',10,$Isd43_c_horainicial,true,'hidden',3);
             db_input('sd43_c_horafinal',10,$Isd43_c_horafinal,true,'hidden',3);

           ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" >
            <fieldset class="separator">
              <legend><b>Lançamento</b></legend>
              <table border="0">
                <tr>
                  <td rowspan="2">
                    <table>
                      <tr>
                        <td nowrap title="<?echo @$Tsd30_c_tipograde?>"><?echo @$Lsd30_c_tipograde?></td>
                        <td>
                           <?php
                              $x = array('I'=>'INTERVALO','P'=>'PERÍODO');
                              db_select('sd30_c_tipograde',$x,true,$db_opcao,"");
                           ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap title="<?php echo @$Tsd30_i_tipoficha?>"><?php echo @$Lsd30_i_tipoficha?></td>
                        <td>
                          <?
                            $result = $clsau_tipoficha->sql_record($clsau_tipoficha->sql_query("","*"));
                            db_selectrecord("sd30_i_tipoficha",$result,true,$db_opcao,'','','','','',1);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap title="<?php echo @$Tsd30_i_diasemana?>"><?php echo @$Lsd30_i_diasemana?></td>
                        <td>
                           <input type="checkbox" name="chk_seg" value="2" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 2 ? 'checked' : '' ?> >Seg
                           <input type="checkbox" name="chk_ter" value="3" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 3 ? 'checked' : '' ?> >Ter
                           <input type="checkbox" name="chk_qua" value="4" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 4 ? 'checked' : '' ?> >Qua<br>
                           <input type="checkbox" name="chk_qui" value="5" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 5 ? 'checked' : '' ?> >Qui
                           <input type="checkbox" name="chk_sex" value="6" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 6 ? 'checked' : '' ?> >Sex
                           <input type="checkbox" name="chk_sab" value="7" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 7 ? 'checked' : '' ?> >Sáb
                           <input type="checkbox" name="chk_dom" value="1" onchange="js_diasemana(<?php echo $db_opcao?>, this)" <?php echo $db_opcao != 1 ? 'readonly' : '' ?> <?php echo $sd30_i_diasemana == 1 ? 'checked' : '' ?> >Dom

                        </td>
                      </tr>
                      <tr>
                          <td nowrap title=""><b>Periodicidade:</b></td>
                          <td>
                            <input type="radio" name="rad_periodo" value="1" onClick="js_semanames();" checked> Semanal
                            <input type="radio" name="rad_periodo" value="2" onClick="js_semanames();" > Quinzenal
                            <br>
                            <input type="radio" name="rad_periodo" value="3" onClick="js_semanames();" > Mensal
                            <select id="semanames" name="semanames" readonly>
                                <option value="0">1°-Semana</option>
                                <option value="1">2°-Semana</option>
                                <option value="2">3°-semana</option>
                                <option value="3">4°-semana</option>
                            </select>
                          </td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td>
                          <fieldset>
                            <legend><b>Data Validade</b></legend>
                            <table>
                              <tr>
                                <td nowrap title="<?php echo @$Tsd30_d_valinicial?>">
                                  <b>Início: </b>
                                </td>
                                <td>
                                   <?php
                                      db_inputdata('sd30_d_valinicial',@$sd30_d_valinicial_dia,@$sd30_d_valinicial_mes,@$sd30_d_valinicial_ano,true,'text',$db_opcao );
                                   ?>
                                </td>
                              </tr>
                              <tr>
                                 <td nowrap title="<?php echo @$Tsd30_d_valfinal?>"><?php echo @$Lsd30_d_valfinal?></td>
                                 <td>
                                    <?php
                                       db_inputdata('sd30_d_valfinal',@$sd30_d_valfinal_dia,@$sd30_d_valfinal_mes,@$sd30_d_valfinal_ano,true,'text',$db_opcao );
                                    ?>
                                 </td>
                              </tr>
                            </table>
                          </fieldset>
                        </td>
                        <td>
                          <fieldset>
                            <legend><b>Horário</b></legend>
                            <table>
                              <tr>
                                <td nowrap title="<?php echo @$Tsd30_c_horaini?>"> <?php echo @$Lsd30_c_horaini?> </td>
                                <td>
                                  <?php
                                    db_input('sd30_c_horaini', 5, $Isd30_c_horaini, true, 'text', $db_opcao);
                                  ?>
                                </td>
                              </tr>
                              <tr>
                                <td nowrap title="<?php echo @$Tsd30_c_horafim?>"> <?php echo @$Lsd30_c_horafim?> </td>
                                <td>
                                  <?php
                                    db_input('sd30_c_horafim', 5, $Isd30_c_horafim, true, 'text', $db_opcao);
                                  ?>
                                </td>
                              </tr>
                            </table>
                          </fieldset>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?php echo @$Tsd30_i_reservas?>">
                    <?php
                      echo @$Lsd30_i_fichas;
                      db_input('sd30_i_fichas', 10, $Isd30_i_fichas, true, 'text', $db_opcao, "");
                      echo @$Lsd30_i_reservas;
                      db_input('sd30_i_reservas', 10, $Isd30_i_reservas, true, 'text', $db_opcao, "");
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
		<input name="<?php echo($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
		       type="submit" id="db_opcao"
		       value="<?php echo($db_opcao==1?"Lançar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
           onclick="return js_valida();"
           >
    <input type="button" name="limpa" value="Limpar" onclick="location.href='sau1_undmedhorario006.php?sd04_i_medico=<?php echo $sd04_i_medico?>&z01_nome=<?php echo $z01_nome?>'">
  </div>

  <div class="container" style="width: 75%">

    <fieldset>
      <legend>Horários</legend>


      <label class="bold" for="validade" title="Mostra todas as agendas do médico na Grade de Horários" >
        Mostrar Todas as Agendas:
      </label>
      <input rel="ignore-css" style="margin: 0; padding: 0; vertical-align: bottom;"
             id = "validade"
             name="validade"
             type="checkbox"
             <?php echo (isset($validade)&&$validade == "true")?"checked":"" ?>
             onclick="location.href='sau1_undmedhorario006.php?sd04_i_medico=<?php echo $sd04_i_medico?>&z01_nome=<?php echo $z01_nome?>&validade='+this.checked" >

      <?php
        $chavepri                           = array("sd30_i_codigo"=>@$sd30_i_codigo );
        $cliframe_alterar_excluir->chavepri = $chavepri;
        $str_validade                       = " and (sd30_d_valfinal is null or sd30_d_valfinal >= '".date('Y-m-d', db_getsession('DB_datausu') )."' )";

        if( isset($validade) && $validade == "true" ) {
        	  $str_validade = " ";
        }

        $sCamposSql  = " sd30_i_codigo, sd04_i_unidade, rh70_descr, sd30_d_valinicial, sd30_d_valfinal, ";
        $sCamposSql .= " case sd30_c_tipograde when 'I' then 'Intervalo' when 'P' then 'Período' else 'Não Informado' end as sd30_c_tipograde,";
        $sCamposSql .= " sd101_c_descr, ed32_c_descr, sd30_c_horaini, sd30_c_horafim, sd30_i_fichas, sd30_i_reservas ";
        $sOrdem   = "sd04_i_unidade, sd30_i_diasemana, sd30_d_valinicial,sd30_c_horaini";
        $sWhere   = " sd04_i_medico = $sd04_i_medico $str_validade ";

        @$cliframe_alterar_excluir->sql = $clundmedhorario->sql_query_ext( "",$sCamposSql, $sOrdem, $sWhere );

        $sCampos  = "sd30_i_codigo, sd04_i_unidade, rh70_descr, sd30_d_valinicial, sd30_d_valfinal, ";
        $sCampos .= "sd30_c_tipograde, sd101_c_descr, ed32_c_descr, sd30_c_horaini, sd30_c_horafim, ";
        $sCampos .= "sd30_i_fichas, sd30_i_reservas";
        @$cliframe_alterar_excluir->campos       = $sCampos;
        $cliframe_alterar_excluir->legenda       = "Grade de Horário";
        $cliframe_alterar_excluir->alignlegenda  = "left";
        $cliframe_alterar_excluir->iframe_width  = "100%";
        $cliframe_alterar_excluir->iframe_height = "20%";
        $cliframe_alterar_excluir->tamfontecabec = 9;
        $cliframe_alterar_excluir->tamfontecorpo = 9;
        $cliframe_alterar_excluir->formulario    = false;

        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?>
    </fieldset>
  </div>
</form>

<script type="text/javascript">

const MENSAGEM_FRM_UNDMEDHORARIO = "saude.ambulatorial.db_frmundmedhorario006.";

var oHoraIncio = new DBInputHora($('sd30_c_horaini'));
var oHoraFim   = new DBInputHora($('sd30_c_horafim'));

function js_diasemana( opcao, obj ) {

	if ( opcao == 2 ) {

		document.form1.chk_seg.checked = false;
		document.form1.chk_ter.checked = false;
		document.form1.chk_qua.checked = false;
		document.form1.chk_qui.checked = false;
		document.form1.chk_sex.checked = false;
		document.form1.chk_sab.checked = false;
		document.form1.chk_dom.checked = false;
		obj.checked = true;
	}
}

(function () {
  /*
   * Utilizado para verificar se a agenda possui data final de validade
   * e se esta data for menor que a data atual, não permitir alteração / exclusão
   */
  var lValidaDataFinal = false;

  if ($F('sd30_d_valfinal') != '' ) {

    var oDtHoje = new Date();
    var oDtFinalAgenda = new Date.convertFrom( $F('sd30_d_valfinal'), 'd/m/Y' );

    if (oDtFinalAgenda.getTime() < oDtHoje.getTime()) {
      lValidaDataFinal = true;
    }
  }

  if ( $F('db_opcao') == 'Excluir' ) {

    $$('input[type="radio"]').each( function (oElemento) {

      oElemento.setAttribute("readonly", "readonly");
      //oElemento.disabled = true;
    });
  }

  if ( ($('possui_agenda') && $F('possui_agenda') == "t") || lValidaDataFinal ) {


    document.form1.chk_seg.setAttribute("readonly", "readonly");
    document.form1.chk_ter.setAttribute("readonly", "readonly");
    document.form1.chk_qua.setAttribute("readonly", "readonly");
    document.form1.chk_qui.setAttribute("readonly", "readonly");
    document.form1.chk_sex.setAttribute("readonly", "readonly");
    document.form1.chk_sab.setAttribute("readonly", "readonly");
    document.form1.chk_dom.setAttribute("readonly", "readonly");


    $('sd30_c_horaini').setAttribute("readonly", "readonly");
    $('sd30_c_horafim').setAttribute("readonly", "readonly");
    $('sd30_i_fichas').setAttribute("readonly", "readonly");
    $('sd30_i_reservas').setAttribute("readonly", "readonly");
    $('sd30_d_valinicial').setAttribute("readonly", "readonly");    
    $('sd30_c_tipograde').setAttribute("readonly", "readonly");
    $('sd30_i_tipoficha').setAttribute("readonly", "readonly");
    $('sd30_i_undmed').setAttribute("readonly", "readonly");

    $('dtjs_sd30_d_valinicial').remove();

    $('sd30_c_horaini').addClassName('readonly');
    $('sd30_c_horafim').addClassName('readonly');
    $('sd30_i_fichas').addClassName('readonly');
    $('sd30_i_reservas').addClassName('readonly');
    $('sd30_d_valinicial').addClassName('readonly');
    $('sd30_c_tipograde').addClassName('readonly');
    $('sd30_i_tipoficha').addClassName('readonly');
    $('sd30_i_undmed').addClassName('readonly');

    $('ctnAncoraVinculo').innerHTML = '<b>Vínculo:</b>';


    if ( $('dtjs_sd30_d_valinicial') ) {
        
      //$('dtjs_sd30_d_valinicial').disabled = true;
      $('dtjs_sd30_d_valinicial').readonly = true;
    }

    if ( $('dtjs_sd30_d_valfinal') && $F('sd30_d_valfinal') != '' ) {

      //$('dtjs_sd30_d_valfinal').disabled = true;
      $('dtjs_sd30_d_valfinal').readonly = true;
    }

    $$('input[type="radio"]').each( function (oElemento) {

      oElemento.setAttribute("readonly", "readonly");
      //oElemento.disabled = true;
    });

    if ($F('sd30_d_valfinal') != '' ) {

      $('sd30_d_valfinal').readonly = 'readonly';
     // $('sd30_d_valfinal').disabled = true;
      $('sd30_d_valfinal').addClassName('readonly');
     // $('db_opcao').disabled = true;
    }
  }

  if ( lValidaDataFinal ) {

    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "data_fim_maior_data_atual", { "sErro" : $('db_opcao').name } ) );
    return;
  }

  if ( $('possui_agenda') && $F('possui_agenda') == "t" && $F('sd30_d_valfinal') != '' ) {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "nao_possivel_alterar_excluir", { "sErro" : $('db_opcao').name } ) );
  }

})();

function js_valida() {

  if ($F('sd30_i_undmed') == '') {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "informe_vinculo") );
    return false;
  }

  if ($F('sd30_c_horaini') == '' ) {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "hora_inicial_nao_informada") );
    return false;
  }

  if ($F('sd30_c_horafim') == '' ) {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "hora_final_nao_informada") );
    return false;
  }

  if ($F('sd30_i_fichas') == '' ) {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "informe_numero_fichas") );
    return false;
  }

  if ($F('sd30_i_reservas') == '' ) {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "informe_numero_fichas_reservas") );
    return false;
  }

  if ( $F('sd30_d_valinicial') == '' ) {

    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "informe_data_inicio") );
    return false;
  }

  if ($F('sd30_d_valfinal') && js_comparadata($F('sd30_d_valinicial'), $F('sd30_d_valfinal'), ">" )) {

    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "data_inicio_maior_fim") );
    return false;
  }

  cfm = false;
  F   = document.form1;
  if ( F.chk_seg.checked == true ) { cfm=true; }
  if ( F.chk_ter.checked == true ) { cfm=true; }
  if ( F.chk_qua.checked == true ) { cfm=true; }
  if ( F.chk_qui.checked == true ) { cfm=true; }
  if ( F.chk_sex.checked == true ) { cfm=true; }
  if ( F.chk_sab.checked == true ) { cfm=true; }
  if ( F.chk_dom.checked == true ) { cfm=true; }

  if ( cfm == false) {
    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "escolha_dia_semana") );
  }

  if ( ( document.form1.rad_periodo[2].checked == true ) || ( document.form1.rad_periodo[2].checked == true ) ) {

    if ( ( document.form1.sd30_d_valinicial.value == "" ) || ( document.form1.sd30_d_valfinal.value == "" ) ) {

       alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "entre_com_data_validade") );
       cfm = false;
    }
  }

  return cfm;
}

function js_validahora( hora, x ) {

	if ( document.form1.sd43_c_horainicial.value == "" || document.form1.sd43_c_horafinal.value == "" ) {

		alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "ups_nao_tem_turno") );
	  document.form1[x].value = "";
	 	document.form1[x].focus();
	} else {

		hr_atuali = (document.form1.sd30_c_horaini.value.substring(0,2));
	 	mi_atuali = (document.form1.sd30_c_horaini.value.substring(3,5));
		hr_atualf = (document.form1.sd30_c_horafim.value.substring(0,2));
	 	mi_atualf = (document.form1.sd30_c_horafim.value.substring(3,5));

		hr_inicial = (document.form1.sd43_c_horainicial.value.substring(0,2));
	 	mi_inicial = (document.form1.sd43_c_horainicial.value.substring(3,5));
		hr_final   = (document.form1.sd43_c_horafinal.value.substring(0,2));
	 	mi_final   = (document.form1.sd43_c_horafinal.value.substring(3,5));

	 	hora_ini  = (hr_inicial) * 60 + parseInt(mi_inicial);
	 	hora_fin  = (hr_final)   * 60 + parseInt(mi_final);
	 	hora_atui = (hr_atuali)  * 60 + parseInt(mi_atuali);
	 	hora_atuf = (hr_atualf)  * 60 + parseInt(mi_atualf);

	 	if ( ( hora_atui != 0 && hora_atui < hora_ini) || ( hora_atuf != 0 && hora_atuf > hora_fin ) ) {

	 		alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "horario_nao_corresponde_turno") );
	    document.form1[x].value = "";
	 	  document.form1[x].focus();
	 	}
	}
}

function js_pesquisasd30_i_undmed( mostra ) {

  if ( mostra == true ) {
    js_OpenJanelaIframe( '',
                         'db_iframe_especmedico',
                         'func_especmedico.php?chave_sd04_i_medico='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal',
                         'Pesquisa',
                         true );
  } else {

    if ( document.form1.sd30_i_undmed.value != '' ) {
       js_OpenJanelaIframe( '',
                            'db_iframe_especmedico',
                            'func_especmedico.php?chave_sd04_i_medico='+document.form1.sd04_i_medico.value+'&chave_sd27_i_codigo='+document.form1.sd30_i_undmed.value+'&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal',
                            'Pesquisa',
                            false);
    } else {
      document.form1.sd30_i_undmed.value = '';
    }
  }
}

function js_mostraespecmedico( chave, erro ) {

  document.form1.sd30_i_undmed.value = chave;

  if ( erro == true ) {

    document.form1.sd30_i_undmed.focus();
    document.form1.sd30_i_undmed.value = '';
  }
}

function js_mostraespecmedico1( chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8 ) {

  document.form1.sd30_i_undmed.value      = chave1;
  document.form1.rh70_descr.value         = chave2;
  document.form1.sd04_i_unidade.value     = chave3;
  document.form1.descrdepto.value         = chave4;
  document.form1.sd30_i_turno.value       = chave5;
  document.form1.sd43_v_descricao.value   = chave6;
  document.form1.sd43_c_horainicial.value = chave7;
  document.form1.sd43_c_horafinal.value   = chave8;
  db_iframe_especmedico.hide();
}

function js_semanames() {

  if ( ( document.form1.sd30_d_valinicial.value == "" ) || ( document.form1.sd30_d_valfinal.value == "" ) ) {

    alert( _M( MENSAGEM_FRM_UNDMEDHORARIO + "entre_com_data_validade") );
    document.form1.rad_periodo[0].checked=true;
  }

  if ( document.form1.rad_periodo[2].checked == true ) {
	  
    //document.form1.semanames.disabled = false;
    document.form1.semanames.readonly = false;
  } else {

	document.form1.semanames.readonly = true;	  
    //document.form1.semanames.disabled = true;
  }
}
</script>