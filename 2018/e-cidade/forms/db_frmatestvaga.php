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
$oDaoAtestVaga->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");
$clrotulo->label("nome");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Atestado de Vaga</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Ted102_i_codigo?>">
          <?=@$Led102_i_codigo?>
          <?db_input( 'ed102_i_codigo', 15, $Ied102_i_codigo, true, 'text', 3);?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
         <fieldset class="separator">
           <legend>Situação Atual</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Ted102_i_aluno?>">
                  <?db_ancora( @$Led102_i_aluno, "js_pesquisaed102_i_aluno(true);", $db_opcao );?>
                </td>
                <td>
                  <?php
                    db_input( 'ed102_i_aluno', 15, $Ied102_i_aluno, true, 'text', 3 );
                    db_input( 'ed47_v_nome',   40, @$Ied47_v_nome,  true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Escola Atual:</b>
                </td>
                <td>
                  <?php
                    db_input( 'codigoescola', 15, @$codigoescola, true, 'text', 3 );
                    db_input( 'nomeescola',   40, @$nomeescola,   true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Curso:</b>
                </td>
                <td>
                  <?php
                    db_input( 'codigocurso', 15, @$codigocurso, true, 'text', 3 );
                    db_input( 'nomecurso',   40, @$nomecurso,   true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Situação:</b>
                </td>
                <td>
                  <?db_input( 'situacao', 20, @$situacao, true, 'text', 3 );?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Data Matrícula:</b>
                </td>
                <td>
                  <?db_input( 'datamatricula', 10, @$situacao, true, 'text', 3 );?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Etapa:</b>
                </td>
                <td>
                  <?php
                    db_input( 'codigoserie', 15, @$codigoserie, true, 'text', 3 );
                    db_input( 'nomeserie',   40, @$nomeserie,   true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Ano:</b>
                </td>
                <td>
                  <?db_input( 'anocal', 15, @$anocal, true, 'text', 3 );?>
                </td>
              </tr>
              <tr>
                <td colspan="2" id='mensagemProgressao'></td>
              </tr>
              <tr style="display: none;">
                <td>
                  <input id='etapasProgressao' type="text" value="" />
                </td>
              </tr>
            </table>
         </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <legend>Dados do destino</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Ted102_i_escola?>">
                    <?db_ancora( @$Led102_i_escola, "", 3 );?>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed102_i_escola', 15, $Ied102_i_escola, true, 'text', 3 );
                      db_input( 'ed18_c_nome',    50, @$Ied18_c_nome,   true, 'text', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted102_i_base?>">
                    <?db_ancora( @$Led102_i_base, "js_pesquisaed102_i_base(true);", $db_opcao );?>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed102_i_base', 15, $Ied102_i_base,  true, 'text', $db_opcao, " onchange='js_pesquisaed102_i_base(false);'" );
                      db_input( 'ed31_c_descr', 50, @$Ied31_c_descr, true, 'text', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="Curso">
                    <b>Curso:</b>
                  </td>
                  <?
                    if ( $db_opcao == 3 ) {
                  ?>
                    <td>
                      <?php
                        db_input( 'ed29_i_codigo', 15, @$Ied29_i_codigo, true, 'text', 3 );
                        db_input( 'ed29_c_descr',  50, @$Ied29_c_descr,  true, 'text', 3 );
                      ?>
                    </td>
                  <?
                    } else {
                  ?>
                    <td>
                      <?php
                        db_input( 'codcursodest',  15, @$Icodcursodest,  true, 'text', 3 );
                        db_input( 'nomecursodest', 50, @$Inomecursodest, true, 'text', 3 );
                      ?>
                    </td>
                  <?
                    }
                  ?>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted34_i_serie?>">
                    <?db_ancora( "<b>Etapa:</b>", "js_pesquisaed34_i_serie(true);", $db_opcao );?>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed102_i_serie', 15, @$Ied102_i_serie, true, 'text', 3, " onchange='js_pesquisaed102_i_serie(false);'" );
                      db_input( 'ed11_c_descr',  50, @$Ied11_c_descr,  true, 'text', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted102_i_turno?>">
                    <?db_ancora( @$Led102_i_turno, "js_pesquisaed102_i_turno(true);", $db_opcao );?>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed102_i_turno', 15, $Ied102_i_turno, true, 'text', $db_opcao, " onchange='js_pesquisaed102_i_turno(false);'" );
                      db_input( 'ed15_c_nome',   50, @$Ied15_c_nome,  true, 'text', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted102_i_calendario?>">
                    <?db_ancora( @$Led102_i_calendario, "js_pesquisaed102_i_calendario(true);", $db_opcao );?>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed102_i_calendario', 15, $Ied102_i_calendario, true, 'text', 3 );
                      db_input( 'ed52_c_descr',       50, @$Ied52_c_descr,      true, 'text', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?=@$Led52_d_inicio?>
                  </td>
                  <td>
                    <?db_input( 'ed52_d_inicio', 10, @$Ied52_d_inicio, true, 'text', 3 );?>
                    <?=@$Led52_d_fim?>
                    <?db_input( 'ed52_d_fim', 10, @$Ied52_d_fim, true, 'text', 3 );?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted102_d_data?>">
                    <?=@$Led102_d_data?>
                  </td>
                  <td>
                    <?db_inputdata( 'ed102_d_data', @$ed102_d_data_dia, @$ed102_d_data_mes, @$ed102_d_data_ano, true, 'text', $db_opcao );?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted102_t_obs?>" colspan="2">
                    <fieldset class="separator">
                      <legend><?=@$Led102_t_obs?></legend>
                      <?db_textarea( 'ed102_t_obs', 4, 54, $Ied102_t_obs, true, 'text', $db_opcao );?>
                    </fieldset>
                  </td>
                </tr>
              </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>" 
         type="submit" 
         id="db_opcao" 
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>" 
         <?=( $db_botao == false ? "disabled" : "" )?> 
         <?=( $db_opcao == 1 ? "onclick='return js_submit();'" : "" )?>  
         <?=isset( $incluir ) || isset( $excluir ) ? "style='visibility:hidden;'" : ""?>>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
var sCaminhoMensagens = 'educacao.escola.db_frmatestvaga.';

/**
 * Elemento para mostrar as dependências do aluno
 */
var oLabelProgressao           = document.createElement( "label" );
    oLabelProgressao.innerHTML = '';

$('mensagemProgressao').appendChild( oLabelProgressao );

function js_pesquisaed34_i_serie( mostra ) {
  
  if ( document.form1.ed102_i_base.value == "" ) {
    alert( _M( sCaminhoMensagens + 'informe_base' ) );
  } else {

    var sSerie = '&serie='+document.form1.codigoserie.value;

    /**
     * Caso o aluno tenha progressão ativa, concatena as etapas da progressão a serem passadas para a lookup
     */
    if ( $('etapasProgressao').value != "" ) {
      sSerie += ", " + $('etapasProgressao').value;
    }
    
    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_serieatest',
                         'func_serieatest.php?curso1='+document.form1.codigocurso.value
                                           +'&curso2='+document.form1.codcursodest.value
                                           +sSerie
                                           +'&base='+document.form1.ed102_i_base.value
                                           +'&funcao_js=parent.js_mostraserie1|ed34_i_serie|ed11_c_descr',
                         'Pesquisa de Serie',
                         true
                       );
  }
}

function js_mostraserie1( chave1, chave2 ) {
  
  document.form1.ed102_i_serie.value = chave1;
  document.form1.ed11_c_descr.value  = chave2;
  db_iframe_serieatest.hide();
}

function js_pesquisaed102_i_base( mostra ) {
  
  if ( document.form1.ed102_i_aluno.value == "" ) {
    
    alert( _M( sCaminhoMensagens + 'informe_aluno' ) );
    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_aluno',
                         'func_alunoatest.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome|dl_codigoescola'
                                                                            +'|dl_escola|dl_codigocurso|dl_curso'
                                                                            +'|dl_codigoserie|dl_serie|ed56_c_situacao',
                         'Pesquisa de Alunos',
                         true
                       );
  } else {
    
    if ( mostra == true ) {

      js_OpenJanelaIframe(
                           'top.corpo',
                           'db_iframe_base',
                           'func_baseatest.php?serie='+document.form1.codigoserie.value
                                            +'&curso='+document.form1.codigocurso.value
                                            +'&funcao_js=parent.js_mostrabase1|ed31_i_codigo|ed31_c_descr|ed29_i_codigo|dl_curso',
                           'Pesquisa de Bases Curriculares',
                           true
                         );
    } else {
      
      if ( document.form1.ed102_i_base.value != '' ) {

        js_OpenJanelaIframe(
                             'top.corpo',
                             'db_iframe_base',
                             'func_baseatest.php?serie='+document.form1.codigoserie.value
                                              +'&curso='+document.form1.codigocurso.value
                                              +'&pesquisa_chave='+document.form1.ed102_i_base.value
                                              +'&funcao_js=parent.js_mostrabase',
                             'Pesquisa',
                             false
                           );
      } else {
        document.form1.ed31_c_descr.value = '';
      }
    }
  }
}

function js_mostrabase( chave1, chave2, chave3, erro ) {
  
  document.form1.ed31_c_descr.value  = chave1;
  document.form1.codcursodest.value  = chave2;
  document.form1.nomecursodest.value = chave3;
  
  if ( erro == true ) {
    
    document.form1.ed102_i_base.focus();
    document.form1.ed102_i_base.value  = '';
    document.form1.ed102_i_serie.value = '';
    document.form1.ed11_c_descr.value  = '';
    document.form1.codcursodest.value  = '';
    document.form1.nomecursodest.value = '';
  }
}

function js_mostrabase1( chave1, chave2, chave3, chave4 ) {
  
  document.form1.ed102_i_base.value  = chave1;
  document.form1.ed31_c_descr.value  = chave2;
  document.form1.codcursodest.value  = chave3;
  document.form1.nomecursodest.value = chave4;
  document.form1.ed102_i_serie.value = '';
  document.form1.ed11_c_descr.value  = '';
  db_iframe_base.hide();
}

function js_pesquisaed102_i_calendario( mostra ) {
  
  if ( document.form1.ed102_i_aluno.value == "" ) {
    
    alert( _M( sCaminhoMensagens + 'informe_aluno_calendario' ) );
    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_aluno',
                         'func_alunoatest.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome|dl_codigoescola'
                                                                            +'|dl_escola|dl_codigocurso|dl_curso'
                                                                            +'|dl_codigoserie|dl_serie|ed56_c_situacao',
                         'Pesquisa de Alunos',
                         true
                       );
  } else {
    
    if ( mostra == true ) {

      js_OpenJanelaIframe(
                           'top.corpo',
                           'db_iframe_calendario',
                           'func_calendarioatest.php?anocal='+document.form1.ed102_i_aluno.value
                                                  +'&funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_c_descr'
                                                                                        +'|ed52_i_ano|ed52_d_inicio|ed52_d_fim',
                           'Pesquisa de Calendários',
                           true
                         );
    }
  }
}

function js_mostracalendario1( chave1, chave2, chave3, chave4, chave5 ) {
  
  situacaoaluno = document.form1.situacao.value.replace(/^\s+|\s+$/g,'');
  
  if (    Number( document.form1.anocal.value ) != Number( chave3 ) 
       && situacaoaluno != "APROVADO" 
       && situacaoaluno != "APROVADO PARCIAL" 
       && situacaoaluno != "REPETENTE" 
       && situacaoaluno != "EVADIDO" 
       && situacaoaluno != "CANCELADO" ) {

    var oMensagem             = new Object();
        oMensagem.iAno        = document.form1.anocal.value;
        oMensagem.iCalendario = chave3;
        
    alert( _M( sCaminhoMensagens + 'aluno_pertence_calendario_diferente', oMensagem ) );
    
    document.form1.ed52_c_descr.value       = '';
    document.form1.ed52_d_inicio.value      = '';
    document.form1.ed52_d_fim.value         = '';
    document.form1.ed102_i_calendario.value = '';
    document.form1.ed102_i_calendario.focus();
  } else {
    
    document.form1.ed102_i_calendario.value = chave1;
    document.form1.ed52_c_descr.value       = chave2;
    document.form1.ed52_d_inicio.value      = chave4.substr(8,2) + "/" + chave4.substr(5,2) + "/" + chave4.substr(0,4);
    document.form1.ed52_d_fim.value         = chave5.substr(8,2) + "/" + chave5.substr(5,2) + "/" + chave5.substr(0,4);
    db_iframe_calendario.hide();
  }
}

function js_pesquisaed102_i_turno( mostra ) {
  
  if ( document.form1.ed102_i_aluno.value == "" ) {
    
    alert( _M( sCaminhoMensagens + 'informe_aluno_turno' ) );
    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_aluno',
                         'func_alunoatest.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome|dl_codigoescola'
                                                                            +'|dl_escola|dl_codigocurso|dl_curso'
                                                                            +'|dl_codigoserie|dl_serie|ed56_c_situacao',
                         'Pesquisa de Alunos',
                         true
                       );
  } else if( document.form1.ed102_i_base.value == "" ) {
    
    alert( _M( sCaminhoMensagens + 'informe_base_curricular_turno' ) );
    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_base',
                         'func_baseatest.php?serie='+document.form1.codigoserie.value
                                          +'&curso='+document.form1.codigocurso.value
                                          +'&funcao_js=parent.js_mostrabase1|ed31_i_codigo|ed31_c_descr|ed29_i_codigo|dl_curso',
                         'Pesquisa de Bases Curriculares',
                         true
                       );
  } else {
    
    if ( mostra == true ) {
      
      js_OpenJanelaIframe(
                           'top.corpo',
                           'db_iframe_turno',
                           'func_turnoturma.php?curso='+document.form1.codcursodest.value
                                             +'&funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome',
                           'Pesquisa de Turnos',
                           true
                         );
    } else {
      
      if ( document.form1.ed102_i_turno.value != '' ) {
        
        js_OpenJanelaIframe(
                             'top.corpo',
                             'db_iframe_turno',
                             'func_turnoturma.php?curso='+document.form1.codigocurso.value
                                               +'&pesquisa_chave='+document.form1.ed102_i_turno.value
                                               +'&funcao_js=parent.js_mostraturno',
                             'Pesquisa',
                             false
                           );
      } else {
        document.form1.ed15_c_nome.value = '';
      }
    }
  }
}

function js_mostraturno( chave, erro ) {
  
  document.form1.ed15_c_nome.value = chave;
  
  if ( erro == true ) {
    
    document.form1.ed102_i_turno.focus();
    document.form1.ed102_i_turno.value = '';
  }
}

function js_mostraturno1( chave1, chave2 ) {
  
  document.form1.ed102_i_turno.value = chave1;
  document.form1.ed15_c_nome.value   = chave2;
  db_iframe_turno.hide();
}

function js_pesquisaed102_i_aluno( mostra ) {
  
  if ( mostra == true ) {

    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_aluno',
                         'func_alunoatest.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome|dl_codigoescola'
                                                                            +'|dl_escola|dl_codigocurso|dl_curso'
                                                                            +'|dl_codigoserie|dl_serie|ed56_c_situacao'
                                                                            +'|ed52_i_ano|ed60_d_datamatricula',
                         'Pesquisa de Alunos',
                         true
                       );
  }
}

function js_mostraaluno1( chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, chave10, chave11 ) {
  
  document.form1.ed102_i_aluno.value      = chave1;
  document.form1.ed47_v_nome.value        = chave2;
  document.form1.codigoescola.value       = chave3;
  document.form1.nomeescola.value         = chave4;
  document.form1.codigocurso.value        = chave5;
  document.form1.nomecurso.value          = chave6;
  document.form1.codigoserie.value        = chave7;
  document.form1.nomeserie.value          = chave8;
  document.form1.situacao.value           = chave9;
  document.form1.anocal.value             = chave10;
  document.form1.datamatricula.value      = chave11.substr(8,2) + "/" + chave11.substr(5,2) + "/" + chave11.substr(0,4);
  document.form1.ed102_i_base.value       = "";
  document.form1.ed31_c_descr.value       = "";
  document.form1.ed102_i_serie.value      = "";
  document.form1.ed11_c_descr.value       = "";
  document.form1.ed102_i_turno.value      = "";
  document.form1.ed15_c_nome.value        = "";
  document.form1.ed102_i_calendario.value = "";
  document.form1.ed52_c_descr.value       = "";
  db_iframe_aluno.hide();

  $('etapasProgressao').value           = '';
  oLabelProgressao.innerHTML            = '';
  $('mensagemProgressao').style.display = 'none';
  verificaExistenciaProgressao( chave1 );
}

function js_pesquisa() {

  js_OpenJanelaIframe(
                       'top.corpo',
                       'db_iframe_atestvaga',
                       'func_atestvaga.php?funcao_js=parent.js_preenchepesquisa|ed102_i_codigo',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa( chave ) {
  
  db_iframe_atestvaga.hide();
  <?
  if ( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_submit(){
  
  if ( document.form1.ed102_d_data.value == "" ) {
    
    alert( _M( sCaminhoMensagens + 'informe_data_atestado' ) );
    
    document.form1.ed102_d_data.focus();
    document.form1.ed102_d_data.style.backgroundColor = '#99A9AE';
    return false;
  } else {
    
    datamat   = document.form1.datamatricula.value;
    dataatest = document.form1.ed102_d_data_ano.value + "-" + document.form1.ed102_d_data_mes.value + "-"+document.form1.ed102_d_data_dia.value;
    
    if ( document.form1.ed52_d_inicio.value != "" ) {
      
      dataini = document.form1.ed52_d_inicio.value.substr(6,4) + "-" + document.form1.ed52_d_inicio.value.substr(3,2) + "-"+document.form1.ed52_d_inicio.value.substr(0,2);
      datafim = document.form1.ed52_d_fim.value.substr(6,4) + "-" + document.form1.ed52_d_fim.value.substr(3,2) + "-"+document.form1.ed52_d_fim.value.substr(0,2);
      check   = js_validata(dataatest,dataini,datafim);
      
      if ( check == false ) {
        
        data_ini = dataini.substr(8,2) + "/" + dataini.substr(5,2) + "/" + dataini.substr(0,4);
        data_fim = datafim.substr(8,2) + "/" + datafim.substr(5,2) + "/" + datafim.substr(0,4);

        var oMensagem              = new Object();
            oMensagem.iDataInicial = data_ini;
            oMensagem.iDataFinal   = data_fim;
        alert( _M( sCaminhoMensagens + 'data_atestado_fora_periodo', oMensagem ) );
        
        document.form1.ed102_d_data.focus();
        document.form1.ed102_d_data.style.backgroundColor = '#99A9AE';
        return false;
      }
    }
    
    if ( datamat != "" ) {
      
      datamat   = datamat.substr(6,4) + '' + datamat.substr(3,2) + '' + datamat.substr(0,2);
      dataatest = dataatest.substr(0,4) + '' + dataatest.substr(5,2) + '' + dataatest.substr(8,2);
      
      if ( parseInt( datamat ) > parseInt( dataatest ) ) {
        
        alert( _M( sCaminhoMensagens + 'data_atestado_menor_data_matricula' ) );
        
        document.form1.ed102_d_data.focus();
        document.form1.ed102_d_data.style.backgroundColor = '#99A9AE';
        return false;
      }
    }
  }
  
  document.form1.db_opcao.style.visibility = "hidden";
  return true;
}

/**
 * Verifica se o aluno selecionado possui alguma progressão ativa
 */
function verificaExistenciaProgressao( iAluno ) {

  var oParametro        = new Object();
      oParametro.exec   = "buscaDadosProgressaoAluno";
      oParametro.iAluno = iAluno;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoVerificaExistenciaProgressao;

  js_divCarregando( "Aguarde, carregando.", "msgBox" );
  new Ajax.Request( 'edu4_progressaoparcial.RPC.php', oDadosRequisicao );
}

/**
 * Retorno da verificação de existência de progressão ativa
 */
function retornoVerificaExistenciaProgressao( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  /**
   * Caso existam progressoes, percorre cada uma para apresentação da mensagem na tela
   */
  if ( oRetorno.aProgressoes.length > 0 ) {

    var aEtapas   = new Array();
    var sMensagem = '<br>O(A) aluno(a) possui a(s) seguinte(s) dependência(s): <br>';

    oRetorno.aProgressoes.each(function( oProgressao, iSeq ) {

      aEtapas.push( oProgressao.iEtapa );
      
      sMensagem += ' &nbsp;&nbsp; Etapa: ' + oProgressao.sEtapa.urlDecode();
      sMensagem += ' - Disciplina: ' + oProgressao.sDisciplina.urlDecode();
      sMensagem += ' - Ensino: ' + oProgressao.sEnsino.urlDecode();
      sMensagem += '<br>';
    });

    $('mensagemProgressao').style.display = '';
    oLabelProgressao.innerHTML            = sMensagem;
    $('etapasProgressao').value           = aEtapas.join( ', ' );
  }
}

/**
 * Estilos dos campos
 */
$("ed102_i_codigo").addClassName("field-size2");
$("ed102_i_aluno").addClassName("field-size2");
$("ed47_v_nome").addClassName("field-size7");
$("codigoescola").addClassName("field-size2");
$("nomeescola").addClassName("field-size7");
$("codigocurso").addClassName("field-size2");
$("nomecurso").addClassName("field-size7");
$("situacao").addClassName("field-size9");
$("datamatricula").addClassName("field-size2");
$("codigoserie").addClassName("field-size2");
$("nomeserie").addClassName("field-size7");
$("anocal").addClassName("field-size2");
$("ed102_i_escola").addClassName("field-size2");
$("ed18_c_nome").addClassName("field-size7");
$("ed102_i_base").addClassName("field-size2");
$("ed31_c_descr").addClassName("field-size7");
$("codcursodest").addClassName("field-size2");
$("nomecursodest").addClassName("field-size7");
$("ed102_i_serie").addClassName("field-size2");
$("ed11_c_descr").addClassName("field-size7");
$("ed102_i_turno").addClassName("field-size2");
$("ed15_c_nome").addClassName("field-size7");
$("ed102_i_calendario").addClassName("field-size2");
$("ed52_c_descr").addClassName("field-size7");
$("ed52_d_inicio").addClassName("field-size2");
$("ed52_d_fim").addClassName("field-size2");
$("ed102_d_data").addClassName("field-size2");

$('mensagemProgressao').style.display = 'none';
</script>