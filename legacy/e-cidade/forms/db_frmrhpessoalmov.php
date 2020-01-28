<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: pessoal
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpessoalmov->rotulo->label();
$clrhtipoapos->rotulo->label();
$clrhpesrescisao->rotulo->label();
$clrhpesbanco->rotulo->label();
$clrhpespadrao->rotulo->label();
$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("r70_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("rh30_descr");
$clrotulo->label("rh30_regime");
$clrotulo->label("rh30_vinculo");
$clrotulo->label("h13_descr");
$clrotulo->label("h13_tpcont");
$clrotulo->label("db90_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("r59_descr1");
$clrotulo->label("r02_descr");
$clrotulo->label("rh20_cargo");
$clrotulo->label("rh04_descr");
$clrotulo->label("rh21_regpri");
$clrotulo->label("rh19_propi");
$clrotulo->label("rh05_empenhado");
$clrotulo->label("db83_sequencial");
$clrotulo->label("rh01_reajusteparidade");
$clrotulo->label("rh03_padraoprev");
$clrotulo->label("r02_descrprev");
$Irh02_horasdiarias = 0;
$Lrh02_horasdiarias = "Horas Diárias:";
if(isset($db_opcaoal)){

  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){

  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){

  $db_opcao = 3;
  $db_botao=true;
}else{

  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false && !$lErro ) ){

    $rh02_anousu   = "";
    $rh02_mesusu   = "";
    $rh02_regist   = "";
    $rh02_codreg   = "";
    $rh02_tipsal   = "";
    $rh02_folha    = "";
    $rh02_fpagto   = "";
    $rh02_banco    = "";
    $rh02_agenc    = "";
    $rh02_agenc_d  = "";
    $rh02_contac   = "";
    $rh02_contac_d = "";
    $rh02_tbprev   = "";
    $rh02_hrsmen   = "";
    $rh02_hrssem   = "";
    $rh02_ocorre   = "";
    $rh02_ponto    = "";
    $rh02_progr    = "";
    $rh02_salari   = "";
  }
}


?>
<style>
  /* Ajusta o tamanho dos campos do fieldset da aba de movimentações */
  #rh02_salari,
  #rh02_fpagto {
    width: 200px;
  }
  #rh02_anousu,
  #rh02_regist,
  #rh02_lota,
  #rh02_codreg,
  #rh02_tpcont,
  #rh02_funcao,
  #rh20_cargo,
  #rh03_padrao,
  #rh03_padraoprev,
  #rh02_folha,
  #rh19_propi,
  #rh02_hrssem,
  #rh02_hrsmen {
    width: 65px;
  }
  #rh02_vincrais,
  #rh02_ocorre {
    width: 460px;
  }
  #rh01_reajusteparidade,
  #rh02_tipsal,
  #rh02_folha,
  #rh02_deficientefisico,
  #rh02_portadormolestia,
  #rh02_abonopermanencia {
    width: 115px;
  }
  
  #rh02_cedencia,
  #rh02_onus,
  #rh02_ressarcimento
  {width: 100%}

</style>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td><fieldset>
    <table border="0">
      <tr>
        <td align="center">
          <fieldset>
            <legend>Dados Cadastrais</legend>
          <?
          db_input('rh02_seqpes',6,$Irh02_seqpes,true,'hidden',3,"");
          ?>
          <table width="100%" border="0">
            <tr>
              <td nowrap title="Ano / Mês exercício">
                <b>Exercício:</b>
              </td>
              <td nowrap>
                <?
                db_input('rh02_anousu',4,$Irh02_anousu,true,'text',3,"")
                ?>
                &nbsp;<b>/</b>&nbsp;
                <?
                db_input('rh02_mesusu',2,$Irh02_mesusu,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_regist?>">
                <?
                db_ancora(@$Lrh02_regist,"js_pesquisarh02_regist(true);",3);
          ?>
              </td>
              <td nowrap>
                <?
                db_input('rh02_regist',6,$Irh02_regist,true,'text',3,"");
                $result_nome = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh02_regist,"z01_nome"));
                if($clrhpessoal->numrows>0){
                  db_fieldsmemory($result_nome,0);
                }
                ?>
                <?
                db_input('z01_nome',34,$Iz01_nome,true,'text',3,'');
                ?>
              </td>
              <td nowrap title="<?=@$Trh02_funcao?>" align="right">
                <?
                  db_ancora(@ $Lrh02_funcao, "js_pesquisarh02_funcao(true);", $db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('rh02_funcao', 6, $Irh02_funcao, true, 'text', $db_opcao, "onchange='js_pesquisarh02_funcao(false);'")
                ?>
                <?
                db_input('rh37_descr', 33, $Irh37_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_lota?>">
                <?
                db_ancora(@$Lrh02_lota,"js_pesquisarh02_lota(true);",$db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('rh02_lota',6,$Irh02_lota,true,'text',$db_opcao,"onchange='js_pesquisarh02_lota(false);'");
                ?>
                <?
                db_input('r70_descr',34,$Ir70_descr,true,'text',3,'');
                ?>
              </td>
              <td nowrap title="<?=@$Trh20_cargo?>" align="right">
                <?
                db_ancora(@ $Lrh20_cargo, "js_pesquisarh20_cargo(true);", $db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('rh20_cargo', 6, $Irh20_cargo, true, 'text', $db_opcao, "onchange='js_pesquisarh20_cargo(false);'");
                ?>
                <?
                db_input('rh04_descr', 33, $Irh04_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_codreg?>">
                <?
                db_ancora(@$Lrh02_codreg,"js_pesquisarh02_codreg(true);",$db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('rh02_codreg',6,$Irh02_codreg,true,'text',$db_opcao,"onchange='js_pesquisarh02_codreg(false);'")
                ?>
                <?
                db_input('rh30_regime',2,$Irh30_regime,true,'text',3,'');
                ?>
                <?
                db_input('rh30_descr',28,$Irh30_descr,true,'text',3,'');
                ?>
                <?
                db_input('rh30_vinculo',2,$Irh30_vinculo,true,'hidden',3,'');
                ?>
              </td>

              <td nowrap align="right" id="torigem">
          <div id="vinculoorigem" style="visibility:hidden">
            <?
            $opcaoorigem = 3;
            if($db_opcao == 1 || $db_opcao == 2){
              $opcaoorigem = $db_opcao;
            }
            db_ancora(@$Lrh21_regpri, "js_pesquisarh21_regpri(true);", $opcaoorigem);
            ?>
          </div>
              </td>
              <td nowrap>
                <?
                db_input('rh21_regpri',6,$Irh21_regpri,true,'hidden',$opcaoorigem,"onchange='js_pesquisarh21_regpri(false);'")
                ?>
                <?
                db_input('z01_nome',33,$Iz01_nome,true,'hidden',3,'','z01_nomeorigem')
                ?>
              </td>
           </tr>

           <tr>
             <td nowrap title="<?=@$Trh02_tipsal?>">
               <?=@$Lrh01_reajusteparidade?>
             </td>
             <td>
               <?php
                 try{


                   $oDaoReajusteParidade = db_utils::getDao('rhreajusteparidade');
                   $sSql                 = $oDaoReajusteParidade->sql_query_file(null, '*', 'rh148_sequencial');
                   $rsReajusteParidade   = db_query($sSql);

                   if (!$rsReajusteParidade) {
                     throw new DBException('Erro ao buscar os dados da tabela rhreajusteparidade.');
                   }

                   $aTipoReajuste     = array('0' => '');
                   $aReajusteParidade = db_utils::getCollectionByRecord($rsReajusteParidade, false, false, true);

                   foreach ($aReajusteParidade as $oReajusteParidade) {
                     $aTipoReajuste[$oReajusteParidade->rh148_sequencial] = $oReajusteParidade->rh148_descricao;
                   }
                 } catch (\Exception $e)  {
                   db_msgbox($e->getMessage());
                   $aTipoReajuste     = array('0' => '');
                 }
                 db_select('rh01_reajusteparidade', $aTipoReajuste, true, $db_opcao);
               ?>
             </td>
             <td nowrap title="<?=@$Trh03_padrao?>" align="right" id="Labelpadrao">
               <?
               db_ancora(@$Lrh03_padrao,"js_pesquisarh03_padrao(true);",$db_opcao);
               ?>
             </td>
             <td nowrap id="padrao">
               <?
               db_input('rh03_padrao',6,$Irh03_padrao,true,'text',$db_opcao,"onchange='js_pesquisarh03_padrao(false);'")
               ?>
               <?
               db_input('r02_descr',33,$Ir02_descr,true,'text',3,'');
               ?>
             </td>
           </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_tpcont?>">
                <?
                db_ancora(@$Lrh02_tpcont,"js_pesquisarh02_tpcont(true);",$db_opcao);
                ?>
              </td>
              <td nowrap id="tipoContrato">
                <?
                db_input('rh02_tpcont',6,$Irh02_tpcont,true,'text',$db_opcao,"onchange='js_pesquisarh02_tpcont(false);'")
                ?>
                <?
                db_input('h13_tpcont',2,$Ih13_tpcont,true,'text',3,'');
                ?>
                <?
                db_input('h13_descr',28,$Ih13_descr,true,'text',3,'');
                ?>
              </td>
              <td nowrap align="right" id="LabelPadraoPrev">
                <?
                db_ancora(@$Lrh03_padraoprev,"js_pesquisarh03_padraoprev(true);",$db_opcao);
                ?>
              </td>
              <td nowrap id="padrao_prev">
                <?
                $Trh03_padraoprev = ''; // Remove title.
                db_input('rh03_padraoprev',6,$Irh03_padraoprev,true,'text',$db_opcao,"oninput='js_pesquisarh03_padraoprev(false);'","","#E6E4F1")
                ?>
                <?
                $Tr02_descrprev = ''; // Remove title.
                db_input('r02_descrprev',33,@$Ir02_descrprev,true,'text',3,'');
                ?>
              </td>

            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_vincrais?>">
                <?=@$Lrh02_vincrais?>
              </td>
              <td nowrap colspan="3">
                <?
                $arr_vincrais = array(
                                      '00'=>'   - Nenhum',
                                      '10'=>'10 - Trab urbano vinc a empr pessoa juridica - CLT p/tempo indeterminado',
                                      '15'=>'15 - Trab urbano vinc a empr pessoa fisica  - CLT p/tempo indeterminado',
                                      '20'=>'20 - Trab rural vinc a empr pessoa juridica - CLT p/tempo indeterminado',
                                      '25'=>'25 - Trab rural vinc a empr pessoa fisica  - CLT p/tempo indeterminado',
                                      '30'=>'30 - Serv regido pelo regime juridico unico (Fed,est,munic) e militar',
                                      '31'=>'31 - Serv regido pelo Regime Jurídico Único (fed,est,munic) e militar,vinc a RGPS',
                                      '35'=>'35 - Serv publico nao-efetivo',
                                      '40'=>'40 - Trabalhador avulso',
                                      '50'=>'50 - Trab temporario, regido pela Lei n. 6.019 de 03.01.74',
                                      '55'=>'55 - Aprendiz contratado na termos do art. 428 da CLT.',
                                      '60'=>'60 - Trab urbano vinc a empr pessoa juridica - CLT p/tempo determinado',
                                      '65'=>'65 - Trab urbano vinc a empr pessoa fisica - CLT p/tempo determinado',
                                      '70'=>'70 - Trab rural vinc a empr pessoa juridica - CLT p/tempo determinado',
                                      '75'=>'75 - Trab rural vinc a empr pessoa fisica - CLT p/tempo determinado',
                                      '80'=>'80 - Diretor sem vinc empregaticio c/ recolhimento do FGTS',
                                      '90'=>'90 - Contrato de trabalho p/prazo determinado Lei 9.601 CLT',
                                      '90'=>'90 - Contrato de Trabalho por Tempo Determinado, reg pela Lei no. 8.745',
                                      '95'=>'95 - Contrato de Trabalho por Tempo Determinado, reg pela Lei no. 8.745 e 9.849',
                                      '96'=>'96 - Contrato de Trabalho por Prazo Determinado, regido por Lei Estadual',
                                      '97'=>'97 - Contrato de Trabalho por Prazo Determinado, regido por Lei Municipal'
                                     );
                db_select("rh02_vincrais",$arr_vincrais,true,$db_opcao);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_tipsal?>">
                <?=@$Lrh02_tipsal?>
              </td>
              <td nowrap>
                <?
                $arr_tipsal = array('M'=>'Mensal','Q'=>'Quinzenal','D'=>'Diário','H'=>'Hora');
                db_select("rh02_tipsal",$arr_tipsal,true,$db_opcao);
                ?>
              </td>
              <td nowrap title="<?=@$Trh02_salari?>" align="right">
                <?=@$Lrh02_salari?>
              </td>
              <td nowrap>
                <?
                db_input('rh02_salari',15,$Irh02_salari,true,'text',$db_opcao,"onchange='js_validaPadraoPrevidencia(false);'");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_folha?>">
                <?=@$Lrh02_folha?>
              </td>
              <td nowrap>
                <?
                $arr_folha = array('M'=>'Mensal','S'=>'Semanal','Q'=>'Quinzenal', 'D' => 'Diário');
                db_select("rh02_folha",$arr_folha,true,$db_opcao);
                ?>
              </td>
              <td nowrap title="<?=@$Trh02_fpagto?>" align="right">
                <?=@$Lrh02_fpagto?>
              </td>
              <td nowrap>
                <?
                $arr_fpagto = array('3' => 'Crédito em conta',
                                    '1' => 'Dinheiro',
                                    '2' => 'Cheque',
                                    '4' => 'Cheque/Pagamento Administrativo'
                                   );
                db_select("rh02_fpagto",$arr_fpagto,true,$db_opcao);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_tbprev?>" id="LabelTabelaPrev">
                <?=@$Lrh02_tbprev?>
              </td>
              <td nowrap>
                <?
                $result_tbprev = $clinssirf->sql_record($clinssirf->sql_query_file(null,null," distinct cast(r33_codtab as integer)-2 as r33_codtab,r33_nome","r33_codtab","r33_instit = ".db_getsession("DB_instit")." and r33_codtab between 3 and 6 and r33_mesusu=$rh02_mesusu and r33_anousu=$rh02_anousu "));
                db_selectrecord("rh02_tbprev",$result_tbprev,true,$db_opcao,"","","","0-Nenhum...");
                ?>
              </td>
              <td nowrap title="<?=$Trh19_propi?>" align="right">
                <? db_ancora(@$Lrh19_propi, "", 3); ?>
              </td>
              <td nowrap>
                <?
                if(isset($rh30_vinculo) && $rh30_vinculo == "A"){
                   db_input('rh19_propi',6,$Irh19_propi,true,'text',3,"");
                }else{
                   db_input('rh19_propi',6,$Irh19_propi,true,'text',2,"");
                }
                ?>
          <b>%</b>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_hrsmen?>">
                <?=@$Lrh02_hrsmen?>
              </td>
              <td nowrap>
                <?
                db_input('rh02_hrsmen',4,$Irh02_hrsmen,true,'text',$db_opcao,"")
                ?>
              </td>
              <td nowrap title="<?=@$Trh02_hrssem?>" align="right">
                <?=@$Lrh02_hrssem?>
              </td>
              <td nowrap align="left">
                <?
                db_input('rh02_hrssem',4,$Irh02_hrssem,true,'text',$db_opcao,"")
                ?>&nbsp;
                <label for="rh02_horasdiarias">
                  <b><?=@$Lrh02_horasdiarias?></b>
              </label>
               <?
                db_input('rh02_horasdiarias',4,$Irh02_horasdiarias, true,'text',$db_opcao,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh02_ocorre?>">
                <?=@$Lrh02_ocorre?>
              </td>
              <td nowrap colspan="3">
                <?
                $arr_ocorre = array(
                                    ''=>'Nunca esteve exposta',
                                    '01'=>'01 - Não exposto no momento, mas já esteve',
                                    '02'=>'02 - Exposta (aposentadoria esp. 15 anos)',
                                    '03'=>'03 - Exposta (aposentadoria esp. 20 anos)',
                                    '04'=>'04 - Exposta (aposentadoria esp. 25 anos)',
                                    '05'=>'05 - Mais de um vínculo (ou fonte pagadora) - Não exposição a agente nocivo'
                                   );
                db_select("rh02_ocorre",$arr_ocorre,true,$db_opcao);
                ?>
              </td>
            </tr>


            <tr>
               <td nowrap title="<?=@$Trh02_deficientefisico?>" align="left">
                  <?=@$Lrh02_deficientefisico?>
                </td>
                <td nowrap>
                  <?$clrotulo->label("rh02_deficientefisico");
                  $aDeficiente = array('f' => 'Não','t'=>'Sim');
                  db_select("rh02_deficientefisico",$aDeficiente,true,$db_opcao,"onchange='js_informarTipoDeficiencia()'");
                  ?>
                </td>
                <td nowrap align="right" title="<?php echo $Trh02_diasgozoferias; ?>">
                  <?php echo @$Lrh02_diasgozoferias; ?>
                </td>
                <td>
                  <?php

                    if(!isset($rh02_diasgozoferias) && $db_opcao == 1) {
                      $rh02_diasgozoferias = 30;
                    }

                    db_input('rh02_diasgozoferias', 10, 1, true, 'text', $db_opcao);

                   ?>
                </td>
            </tr>
            <tr id="row_rh02_tipodeficiencia" <? echo ($GLOBALS['rh02_deficientefisico'] == 't') ? '' : 'style="display: none;"' ?> >
               <td nowrap title="<?=@$Trh02_tipodeficiencia?>" align="left">
                  <?=@$Lrh02_tipodeficiencia?>
                </td>
                <td colspan="2" nowrap>
                  <?
                  $result_tipodeficiencia = $cltipodeficiencia->sql_record($cltipodeficiencia->sql_query_file(null,"rh150_sequencial, rh150_descricao", 'rh150_sequencial asc', null));
                  db_selectrecord("rh02_tipodeficiencia",$result_tipodeficiencia,true,$db_opcao,"","","","");
                  ?>
                </td>
            </tr>
            <tr>
               <td nowrap title="<?=@$Trh02_portadormolestia ?>" align="left">
                  <?=@$Lrh02_portadormolestia ?>
                </td>
                <td colspan="2" nowrap>
                  <?$clrotulo->label("rh02_portadormolestia ");
                  $aMolestia = array('f' => 'Não','t'=>'Sim');
                  db_select("rh02_portadormolestia",$aMolestia,true,$db_opcao,"");
                  ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Trh02_datalaudomolestia?>">
                  <?=@$Lrh02_datalaudomolestia?>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('rh02_datalaudomolestia',@$rh02_datalaudomolestia_dia,@$rh02_datalaudomolestia_mes,@$rh02_datalaudomolestia_ano,true,'text',$db_opcao,"")
                  ?>
                </td>

            </tr>


            <tr id="tipoapos">
              <td nowrap title="<?=$Trh02_rhtipoapos?>">
                <?=@$Lrh02_rhtipoapos?>
              </td>
              <td nowrap>
                <?
                  $sSqlRhTipoApos  = $clrhtipoapos->sql_query(null,"*","rh88_sequencial","");
                  $rsSqlRhTipoApos = $clrhtipoapos->sql_record($sSqlRhTipoApos);
                  db_selectrecord('rh02_rhtipoapos',$rsSqlRhTipoApos,true,$db_opcao);
                ?>
              </td>
              <td id="labelvalidadepensao">
                <?=@$Lrh02_validadepensao?>
              </td>
              <td id="validadepensao">
                <?
                  db_inputdata('rh02_validadepensao',@$rh02_validadepensao_dia,@$rh02_validadepensao_mes,@$rh02_validadepensao_ano,true,'text',$db_opcao,"")
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <?php echo $Lrh02_abonopermanencia ?>
              </td>
              <td>
                <?php
                  $aAbonoPermanencia = array('f' => 'Não', 't' => 'Sim');
                  db_select('rh02_abonopermanencia', $aAbonoPermanencia, true, $db_opcao);
                ?>
              </td>
            </tr>


          </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center" width="100%">
          <div id="ctnContaBancariaServidor"></div>
       </td>
      </tr>
      <tr>
        <td align="center">
          <fieldset id="fldRescisao">
            <legend align="left"><b>Rescisão</b></legend>
      <center>
            <table width="100%">
              <tr>
                <td nowrap title="<?=@$Trh05_recis?>">
                  <?=@$Lrh05_recis?>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('rh05_recis',@$rh05_recis_dia,@$rh05_recis_mes,@$rh05_recis_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
                <td nowrap title="<?=@$Trh05_causa?>">
                  <?
                  db_ancora(@$Lrh05_causa,"js_pesquisarh05_causa(true);",$db_opcao);
                  ?>
                </td>
                <td nowrap>
                  <?
                  db_input('rh05_causa',6,$Irh05_causa,true,'text',3,"")
                  ?>
                  <?
                  db_input('r59_descr',40,$Ir59_descr,true,'text',3,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td nowrap title="<?=@$Trh05_caub?>">
                  <?
                  db_ancora(@$Lrh05_caub,"",3);
                  ?>
                </td>
                <td nowrap>
                  <?
                  db_input('rh05_caub',6,$Irh05_caub,true,'text',3,"")
                  ?>
                  <?
                  db_input('r59_descr1',40,$Ir59_descr1,true,'text',3,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh05_taviso?>">
                  <?
                  db_ancora(@$Lrh05_taviso,"",3);
                  ?>
                </td>
                <td nowrap>
                  <?
                  if(!isset($rh05_taviso)){
                    $rh05_taviso = 3;
                  }
                  $x = array("1"=>"Trabalhado","2"=>"Aviso indenizado","3"=>"Sem aviso");
                  db_select('rh05_taviso',$x,true,$db_opcao,"onchange='js_disabdata(this.value);'");
                  ?>
                  <?
                  $rh05_mremun = 0;
                  db_input('rh05_mremun',10,$Irh05_mremun,true,'hidden',3,"")
                  ?>
                </td>
                <td nowrap title="<?=@$Trh05_aviso?>" >
                  <?=@$Lrh05_aviso?>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('rh05_aviso',@$rh05_aviso_dia,@$rh05_aviso_mes,@$rh05_aviso_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td><B>Empenhos Gerados: </B></td>
                <td colspan="3">
                <?
                  db_select('rh05_empenhado', array("f"=>"Não","t"=>"Sim",),1,1);
                ?>
                </td>
              </tr>

              <tr>
                <td title="<?php echo $Trh05_codigoseguranca; ?>">
                  <?php echo $Lrh05_codigoseguranca; ?>
                </td>
                <td colspan="3">
                <?php db_input('rh05_codigoseguranca', 10, $Irh05_codigoseguranca, true, 'text', $db_opcao); ?>
                </td>
              </tr>

              <tr>
                <td title="<?php echo $Trh05_trct; ?>">
                  <?php echo $Lrh05_trct; ?>
                </td>
                <td colspan="3">
                <?php db_input('rh05_trct', 10, $Irh05_trct, true, 'text', $db_opcao); ?>
                <?php db_input('db83_sequencial',10,$Idb83_sequencial,true,'hidden',$db_opcao,""); ?>
                </td>
              </tr>
            </table>
            </center>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset id="fdlDadosCedencia">
            <legend>
              Dados da Cedência
            </legend>
            <table>
              <tr>
                <td>
                  <label for="rh02_cedencia">
                    <?=$Lrh02_cedencia;?>
                  </label>
                </td>
                <td>
                  <?php

                    if (!isset($rh02_cedencia)) {
                      $rh02_cedencia = 'X';
                    }

                    db_select('rh02_cedencia', getValoresPadroesCampo('rh02_cedencia'), true, $db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="rh02_onus">
                    <?=$Lrh02_onus;?>
                  </label>
                </td>
                <td>
                  <?php

                  if (!isset($rh02_onus)) {
                    $rh02_onus = 'X';
                  }

                  db_select('rh02_onus', getValoresPadroesCampo('rh02_onus'), true, $db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="rh02_ressarcimento">
                    <?=$Lrh02_ressarcimento;?>
                  </label>
                </td>
                <td>
                  <?php
                    if (!isset($rh02_ressarcimento)) {
                      $rh02_ressarcimento = 'X';
                    }
                    db_select('rh02_ressarcimento', getValoresPadroesCampo('rh02_ressarcimento'), true, $db_opcao);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for='rh02_datacedencia'>
                    <?=$Lrh02_datacedencia;?>
                  </label>
                </td>
                <td id="ctnDataCedecnia">
                  <?php
                    db_input('rh02_datacedencia', 20, $Irh02_datacedencia, true, 'text', $db_opcao);
                  ?>
                </td>
              </tr>
              <td>
                <label for='rh02_cnpjcedencia'>
                  <?=$Lrh02_cnpjcedencia;?>
                </label>
              </td>
              <td id="ctnCnpjCedente">
                <?php
                  db_input('rh02_cnpjcedencia', 20, $Irh02_cnpjcedencia, true, 'text', $db_opcao);
                ?>
              </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>

    </table>
  </fieldset>
  </td>
</tr>
  <tr>
    <td align="center">
      <input type="hidden" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" />
      <input type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  <?if($db_opcao!=3)echo "onclick='js_validaDados();'";?>>
    </td>
  </tr>
</table>
<?php
db_app::load("DBHint.widget.js");

$lExcluir = 'false';

if ($db_opcao == 3 || $db_opcao == 33) {
  $lExcluir = 'true';
}
?>

<script>
var sMensagem = 'recursoshumanos.pessoal.db_frmrhpessoalmov.';

/**
 * Instancia componente de dados da conta bancária
 */
var oContaBancariaServidor = new DBViewContaBancariaServidor($F('db83_sequencial'), 'oContaBancariaServidor', <?=$lExcluir?>);
    oContaBancariaServidor.show('ctnContaBancariaServidor');
    oContaBancariaServidor.getDados($F('db83_sequencial'));

/**
 * valida antes de colar no campo valor
 */

$('inputCodigoBanco').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
}

$('inputDvConta').onpaste = function(event) {
  return /^[0-9|.xX]+$/.test(event.clipboardData.getData('text/plain'));
}

$('inputDvAgencia').onpaste = function(event) {
  return /^[0-9|.xX]+$/.test(event.clipboardData.getData('text/plain'));
}
$('inputNumeroAgencia').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
}

$('inputOperacao').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
}

$('inputNumeroConta').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
}

$('inputOperacao').onkeyup = function(event) {
  return js_ValidaCampos(this, 1, 'Código da Operação', false, false, event);
}

function js_disabledtipoapos(vinculo) {

  var sVinculo = vinculo;

  if ( sVinculo != "" ) {

    if ( sVinculo != "A" ) {

      document.getElementById("tipoapos").style.display         = "";
      document.getElementById("rh02_rhtipoapos").disabled       = false;
      document.getElementById("rh02_rhtipoaposdescr").disabled  = false;

      if ( sVinculo == 'P' ) {

        document.getElementById("labelvalidadepensao").style.display = "";
        document.getElementById("validadepensao").style.display      = "";
        document.getElementById("rh02_validadepensao").disabled      = false;
        document.form1.dtjs_rh02_validadepensao.disabled             = false;
      } else {

        document.getElementById("labelvalidadepensao").style.display = "none";
        document.getElementById("validadepensao").style.display      = "none";
        document.getElementById("rh02_validadepensao").disabled      = true;
        document.form1.dtjs_rh02_validadepensao.disabled             = true;
      }
    } else {

      document.getElementById("tipoapos").style.display            = "none";
      document.getElementById("labelvalidadepensao").style.display = "none";
      document.getElementById("validadepensao").style.display      = "none";
      document.getElementById("rh02_rhtipoapos").disabled          = true;
      document.getElementById("rh02_rhtipoaposdescr").disabled     = true;
      document.getElementById("rh02_validadepensao").disabled      = true;
      document.form1.dtjs_rh02_validadepensao.disabled             = true;
    }
  } else {

    document.getElementById("tipoapos").style.display            = "none";
    document.getElementById("labelvalidadepensao").style.display = "none";
    document.getElementById("validadepensao").style.display      = "none";
    document.getElementById("rh02_rhtipoapos").disabled          = true;
    document.getElementById("rh02_rhtipoaposdescr").disabled     = true;
    document.getElementById("rh02_validadepensao").disabled      = true;
    document.form1.dtjs_rh02_validadepensao.disabled             = true;
  }
}

function js_disabdata(valor){
  if(valor == 1){
    document.form1.dtjs_rh05_aviso.disabled = false;
    document.form1.rh05_aviso_dia.readOnly  = false;
    document.form1.rh05_aviso_mes.readOnly  = false;
    document.form1.rh05_aviso_ano.readOnly  = false;

    document.form1.rh05_aviso_dia.style.backgroundColor='';
    document.form1.rh05_aviso_mes.style.backgroundColor='';
    document.form1.rh05_aviso_ano.style.backgroundColor='';
  }else{
    document.form1.dtjs_rh05_aviso.disabled = true;
    document.form1.rh05_aviso_dia.readOnly  = true;
    document.form1.rh05_aviso_mes.readOnly  = true;
    document.form1.rh05_aviso_ano.readOnly  = true;

    document.form1.rh05_aviso_dia.style.backgroundColor='#DEB887';
    document.form1.rh05_aviso_mes.style.backgroundColor='#DEB887';
    document.form1.rh05_aviso_ano.style.backgroundColor='#DEB887';

    document.form1.rh05_aviso_dia.value  = "";
    document.form1.rh05_aviso_mes.value  = "";
    document.form1.rh05_aviso_ano.value  = "";
  }
}

function js_pesquisarh02_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhfuncao','func_rhfuncao.php?funcao_js=parent.js_mostrarhfuncao1|rh37_funcao|rh37_descr&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true,'0');
  }else{
    if(document.form1.rh02_funcao.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhfuncao','func_rhfuncao.php?pesquisa_chave='+document.form1.rh02_funcao.value+'&funcao_js=parent.js_mostrarhfuncao&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false,'0');
    }else{
      document.form1.rh37_descr.value = '';
    }
  }
}
function js_mostrarhfuncao(chave,erro){
  document.form1.rh37_descr.value = chave;
  if(erro==true){
    document.form1.rh02_funcao.focus();
    document.form1.rh02_funcao.value = '';
  }
}
function js_mostrarhfuncao1(chave1,chave2){
  document.form1.rh02_funcao.value = chave1;
  document.form1.rh37_descr.value = chave2;
  db_iframe_rhfuncao.hide();
}

function js_pesquisarh02_lota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframerhlota','func_rhlota.php?funcao_js=parent.js_mostrarhlota1|r70_codigo|r70_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,'0');
  }else{
     if(document.form1.rh02_lota.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframerhlota','func_rhlota.php?pesquisa_chave='+document.form1.rh02_lota.value+'&funcao_js=parent.js_mostrarhlota&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
     }else{
       document.form1.r70_descr.value = '';
     }
  }
}
function js_mostrarhlota(chave,erro){
  document.form1.r70_descr.value = chave;
  if(erro==true){
    document.form1.rh02_lota.focus();
    document.form1.rh02_lota.value = '';
  }
}
function js_mostrarhlota1(chave1,chave2){
  document.form1.rh02_lota.value = chave1;
  document.form1.r70_descr.value = chave2;
  db_iframerhlota.hide();
}
function js_pesquisarh21_regpri(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhpessoal','func_rhpessoal.php?lTodos=1&funcao_js=parent.js_mostraorigem1|rh01_regist|z01_nome','Pesquisa',true,0);
  }else{

    if(document.form1.rh21_regpri.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhpessoal','func_rhpessoal.php?lTodos=1&pesquisa_chave='+document.form1.rh21_regpri.value+'&funcao_js=parent.js_mostraorigem','Pesquisa',false,0);
    }else{
      document.form1.z01_nomeorigem.value = '';
    }
  }
}
function js_mostraorigem(chave,erro){
  document.form1.z01_nomeorigem.value = chave;
  if(erro==true){
    document.form1.rh21_regpri.focus();
    document.form1.rh21_regpri.value = '';
  }
}
function js_mostraorigem1(chave1,chave2){
  document.form1.rh21_regpri.value = chave1;
  document.form1.z01_nomeorigem.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisarh03_padrao(mostra){
  if (document.form1.rh30_regime.value != "") {
    if (js_validaPadraoPrevidencia(false)) {
      if(mostra==true){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_padroes','func_padroes.php?funcao_js=parent.js_mostrapadrao1|r02_codigo|r02_descr&regime='+document.form1.rh30_regime.value+'&chave_r02_anousu='+document.form1.rh02_anousu.value+'&chave_r02_mesusu='+document.form1.rh02_mesusu.value,'Pesquisa',true,'0');
      }else{
        if(document.form1.rh03_padrao.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_padroes','func_padroes.php?pesquisa_chave='+document.form1.rh03_padrao.value+'&funcao_js=parent.js_mostrapadrao&regime='+document.form1.rh30_regime.value+'&chave_r02_anousu='+document.form1.rh02_anousu.value+'&chave_r02_mesusu='+document.form1.rh02_mesusu.value,'Pesquisa',false,'0');
        }else{
          document.form1.rh03_padrao.value = '';
          document.form1.r02_descr.value   = '';
        }
      }
    }
  } else {
    alert(_M(sMensagem + 'erro_regime'));
    document.form1.rh03_padrao.value = '';
    document.form1.r02_descr.value   = '';
  }
}
function js_mostrapadrao(chave,erro){
  document.form1.r02_descr.value = chave;
  if(erro==true){
    document.form1.rh03_padrao.focus();
    document.form1.rh03_padrao.value = '';
  }
}
function js_mostrapadrao1(chave1,chave2){
  document.form1.rh03_padrao.value = chave1;
  document.form1.r02_descr.value  = chave2;
  db_iframe_padroes.hide();
}
function js_pesquisarh03_padraoprev(mostra){
  if(document.form1.rh30_regime.value != ""){
    if (js_validaPadraoPrevidencia(true)) {
      if(mostra==true){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_padroes','func_padroes.php?funcao_js=parent.js_mostrapadrao_prev1|r02_codigo|r02_descr&regime='+document.form1.rh30_regime.value+'&chave_r02_anousu='+document.form1.rh02_anousu.value+'&chave_r02_mesusu='+document.form1.rh02_mesusu.value,'Pesquisa',true,'0');
      }else{
        if(document.form1.rh03_padraoprev.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_padroes','func_padroes.php?pesquisa_chave='+document.form1.rh03_padraoprev.value+'&funcao_js=parent.js_mostrapadrao_prev&regime='+document.form1.rh30_regime.value+'&chave_r02_anousu='+document.form1.rh02_anousu.value+'&chave_r02_mesusu='+document.form1.rh02_mesusu.value,'Pesquisa',false,'0');
        }else{
          document.form1.rh03_padraoprev.value = '';
          document.form1.r02_descrprev.value   = '';
        }
      }
    }
  }else{
    alert(_M(sMensagem + 'erro_regime'));
    document.form1.rh03_padraoprev.value = '';
    document.form1.r02_descrprev.value  = '';
  }
}
function js_mostrapadrao_prev(chave,erro){
  document.form1.r02_descrprev.value = chave;
  if(erro==true){
    document.form1.rh03_padraoprev.focus();
    document.form1.rh03_padraoprev.value = '';
  }
}
function js_mostrapadrao_prev1(chave1,chave2){
  document.form1.rh03_padraoprev.value = chave1;
  document.form1.r02_descrprev.value  = chave2;
  db_iframe_padroes.hide();
}
function js_pesquisarh02_tpcont(mostra){
  if(document.form1.rh30_regime.value != ""){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_tpcontra','func_tpcontra.php?funcao_js=parent.js_mostratpcontra1|h13_codigo|h13_descr|h13_tpcont&regime='+document.form1.rh30_regime.value,'Pesquisa',true,'0');
    }else{
      if(document.form1.rh02_tpcont.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_tpcontra','func_tpcontra.php?pesquisa_chave='+document.form1.rh02_tpcont.value+'&funcao_js=parent.js_mostratpcontra&regime='+document.form1.rh30_regime.value,'Pesquisa',false,'0');
      }else{
        document.form1.rh02_tpcont.value = '';
        document.form1.h13_descr.value  = '';
        document.form1.h13_tpcont.value  = '';
      }
    }
  }else{
    alert(_M(sMensagem + 'erro_regime'));
    document.form1.rh02_tpcont.value = '';
    document.form1.h13_descr.value  = '';
    document.form1.h13_tpcont.value  = '';
  }
}
function js_mostratpcontra(chave,chave2,erro){
  document.form1.h13_descr.value = chave;
  if(erro==true){
    document.form1.rh05_causa.focus();
    document.form1.rh02_tpcont.value = '';
    document.form1.h13_tpcont.value  = '';
  }else{
    document.form1.h13_tpcont.value  = chave2;
  }
}
function js_mostratpcontra1(chave1,chave2,chave3){
  document.form1.rh02_tpcont.value = chave1;
  document.form1.h13_descr.value  = chave2;
  document.form1.h13_tpcont.value  = chave3;
  db_iframe_tpcontra.hide();
}
function js_pesquisarh05_causa(mostra){
  if(document.form1.rh02_codreg.value != ""){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rescisao','func_rescisaoalt.php?funcao_js=parent.js_mostrarescisao1|r59_causa|r59_descr|r59_caub|r59_descr1&chave_r59_anousu=<?=$rh02_anousu?>&chave_r59_mesusu=<?=$rh02_mesusu?>&regime='+document.form1.rh02_codreg.value,'Pesquisa',true,'0');
    }else{
      if(document.form1.rh05_causa.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rescisao','func_rescisaoalt.php?pesquisa_chave='+document.form1.rh05_causa.value+'&funcao_js=parent.js_mostrarescisao&ano=<?=$rh02_anousu?>&mes=<?=$rh02_mesusu?>&regime='+document.form1.rh02_codreg.value,'Pesquisa',false,'0');
      }else{
        document.form1.rh05_caub.value  = '';
        document.form1.r59_descr.value  = '';
        document.form1.r59_descr1.value = '';
      }
    }
  }else{
    alert(_M(sMensagem + 'erro_regime'));
    document.form1.rh05_causa.value = '';
    document.form1.rh05_caub.value  = '';
    document.form1.r59_descr.value  = '';
    document.form1.r59_descr1.value = '';
  }
}
function js_mostrarescisao(chave,chave2,chave3,erro){
  document.form1.r59_descr.value = chave;
  if(erro==true){
    document.form1.rh05_causa.focus();
    document.form1.rh05_causa.value = '';
    document.form1.rh05_caub.value  = '';
    document.form1.r59_descr1.value = '';
  }else{
    document.form1.rh05_caub.value   = chave2;
    document.form1.r59_descr1.value  = chave3;
  }
}
function js_mostrarescisao1(chave1,chave2,chave3,chave4) {

  document.form1.rh05_causa.value = chave1;
  document.form1.r59_descr.value  = chave2;
  document.form1.rh05_caub.value  = chave3;
  document.form1.r59_descr1.value = chave4;
  db_iframe_rescisao.hide();
}

function js_validaDados(){

  var validaDados = setInterval(function() {

    if(!js_validaTipoDeficiencia()){

      clearInterval(validaDados);
      return false;

    }

    if ( oContaBancariaServidor.lStatus ||  $F('inputCodigoBanco') == ''){

      js_verificaconta();
      clearInterval(validaDados)
    }
  }, 500);
}

function js_verificaconta() {

  /**
   * Válida se o campo salário é negativo
   */
  var salario = parseFloat(document.form1.rh02_salari.value.trim());
  if(salario < 0){

    alert(_M(sMensagem + 'salario_negativo'));
    return false;
  }


  if (document.form1.rh30_vinculo.value == 'I' || document.form1.rh30_vinculo.value == 'P') {

	  if (document.form1.rh02_rhtipoapos.value == 0) {

	    alert(_M(sMensagem + 'erro_tipo_pensao'));
	    return false;
	  }
  }

  if (document.form1.rh02_fpagto.value == 3) {

    if (document.form1.inputCodigoBanco.value == "") {

      alert(_M(sMensagem + 'erro_codigo_banco'));
      document.form1.inputCodigoBanco.focus();
      return false;
    } else if (document.form1.inputNumeroAgencia.value == "") {

      alert(_M(sMensagem + 'erro_numero_agencia'));
      document.form1.inputNumeroAgencia.focus();
      return false;
    } else if (document.form1.inputDvAgencia.value == "") {

      alert(_M(sMensagem + 'erro_dv_agencia'));
      document.form1.inputDvAgencia.focus();
      return false;
    } else if (document.form1.inputNumeroConta.value == "") {

      alert(_M(sMensagem + 'erro_numero_conta_corrente'));
      document.form1.inputNumeroConta.focus();
      return false;
    } else if (document.form1.inputDvConta.value == "") {

      alert(_M(sMensagem + 'erro_dv_conta_corrente'));
      document.form1.inputDvConta.focus();
      return false;
    } else if (document.form1.cboTipoConta.value == 0) {

      alert(_M(sMensagem + 'erro_tipo_conta'));
      return false;
    }
  }

  if (document.form1.rh02_lota.value == "") {

    alert(_M(sMensagem + 'erro_lotacao'));
    document.form1.rh02_lota.focus();
    return false;
  } else if (document.form1.rh02_codreg.value == "") {

    alert(_M(sMensagem + 'erro_regime'));
    document.form1.rh02_codreg.focus();
    return false;
  } else if (document.form1.rh02_tpcont.value == "") {

    alert(_M(sMensagem + 'erro_tipo_contato'));
    document.form1.rh02_tpcont.focus();
    return false;
  } else if (document.form1.rh02_hrsmen.value == "") {

    alert(_M(sMensagem + 'erro_qnd_horas_mensais'));
    document.form1.rh02_hrsmen.focus();
    return false;
  } else if (document.form1.rh02_hrssem.value == "") {

    alert(_M(sMensagem + 'erro_qnd_horas_semanais'));
    document.form1.rh02_hrssem.focus();
    return false;
  } else if (document.form1.rh02_tbprev.value == "0") {
    if (!confirm(_M(sMensagem + 'confirm_calculo_previdencia'))) {
      return false;
    }
  } else if (document.form1.rh02_funcao.value == "") {
    alert(_M(sMensagem + 'erro_cargo'));
    document.form1.rh02_funcao.focus();
    return false;
  } else if (document.form1.rh02_diasgozoferias.value == "") {
    alert(_M(sMensagem + 'erro_diasgozoferias'));
    document.form1.rh02_diasgozoferias.focus();
    return false;
  } else if (document.form1.rh02_diasgozoferias.value < 30) {
    alert(_M(sMensagem + 'erro_diasgozoferias_minimo'));
    document.form1.rh02_diasgozoferias.focus();
    return false;
  }

  if (document.form1.rh02_cedencia.value != 'X') {

    if (document.form1.rh02_onus.value == 'X') {

      alert(_M(sMensagem + 'onus_obrigatorio'));
      return false;
    }  


    if (!oInputData.isValid() || oInputData.getValue() === null) {

      alert(_M(sMensagem + 'data_movimentacao_obrigatoria'));
      return false;
    }  

    if (!oInputCnpj.isValid() || empty(oInputCnpj.getValue())) {

      alert(_M(sMensagem + 'cnpj_cedente_obrigatorio'));
      return false;
    }
  }

  if (document.form1.rh02_folha.value == 'D' && empty(document.form1.rh02_horasdiarias.value)) {

    alert(_M(sMensagem + 'erro_carga_horaria_diaria'));
    document.form1.rh02_horasdiarias.focus();
    return false;
  }

  if (!oInputCnpj.isValid() && oInputCnpj.getValue() != '') {

    alert(_M(sMensagem + 'erro_cnpj_cedencia'));
    oInputCnpj.getElement().focus();
    return false;
  }

  document.form1.submit();
}

function js_cancelar(){

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisarh44_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostrabancos1|db90_codban|db90_descr','Pesquisa',true,0);
  }else{
    if(document.form1.rh44_codban.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh44_codban.value+'&funcao_js=parent.js_mostrabancos','Pesquisa',false,0);
    }else{
      document.form1.db90_descr.value = '';
    }
  }
}
function js_mostrabancos(chave,erro){
  document.form1.db90_descr.value = chave;
  if(erro==true){
    document.form1.rh44_codban.focus();
    document.form1.rh44_codban.value = '';
  }
}
function js_mostrabancos1(chave1,chave2){
  document.form1.rh44_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}

function js_pesquisarh20_cargo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhcargo','func_rhcargo.php?funcao_js=parent.js_mostrarhcargo1|rh04_codigo|rh04_descr','Pesquisa',true,'0');
  }else{
    if(document.form1.rh20_cargo.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhcargo','func_rhcargo.php?pesquisa_chave='+document.form1.rh20_cargo.value+'&funcao_js=parent.js_mostrarhcargo','Pesquisa',false,0);
    }else{
      document.form1.rh04_descr.value = '';
    }
  }
}

function js_mostrarhcargo(chave,erro){
  document.form1.rh04_descr.value = chave;
  if(erro==true){
    document.form1.rh20_cargo.focus();
    document.form1.rh20_cargo.value = '';
  }
}

function js_mostrarhcargo1(chave1,chave2){
  document.form1.rh20_cargo.value = chave1;
  document.form1.rh04_descr.value = chave2;
  db_iframe_rhcargo.hide();
}

function js_pesquisarh02_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|rh01_numcgm','Pesquisa',true,'0');
  }else{
    if(document.form1.rh02_regist.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh02_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false,0);
    }else{
      document.form1.rh01_numcgm.value = '';
    }
  }
}

function js_mostrarhpessoal(chave,erro){
  document.form1.rh01_numcgm.value = chave;
  if(erro==true){
    document.form1.rh02_regist.focus();
    document.form1.rh02_regist.value = '';
  }
}

function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh02_regist.value = chave1;
  document.form1.rh01_numcgm.value = chave2;
  db_iframe_rhpessoal.hide();
}

function js_camposorigem(opcao){
  if(opcao == false){
    document.form1.rh21_regpri.type = "hidden";
    document.form1.z01_nomeorigem.type= "hidden";
    document.form1.rh21_regpri.value = "";
    document.form1.z01_nomeorigem.value = "";
    document.getElementById("vinculoorigem").style.visibility = "hidden";
    document.getElementById("torigem").title = "";
  }else{
    document.form1.rh21_regpri.type = "text";
    document.form1.z01_nomeorigem.type= "text";
    document.form1.rh21_regpri.readOnly = false;
    document.form1.rh21_regpri.style.backgroundColor = "";
    document.getElementById("vinculoorigem").style.visibility = "visible";
    document.getElementById("torigem").title = "<?=str_replace("\r","\\r",str_replace("\n","\\n",AddSlashes($Trh21_regpri)))?>";
  }
}

function js_pesquisarh02_codreg(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhregime','func_rhregimereg.php?funcao_js=parent.js_mostrarhregime1|rh30_codreg|rh30_descr|rh30_regime|rh30_vinculo','Pesquisa',true,0);
  }else{
    if(document.form1.rh02_codreg.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoalmov','db_iframe_rhregime','func_rhregimereg.php?pesquisa_chave='+document.form1.rh02_codreg.value+'&funcao_js=parent.js_mostrarhregime','Pesquisa',false,0);
    }else{
      document.form1.rh30_regime.value = '';
      document.form1.rh30_descr.value = '';
      document.form1.rh30_vinculo.value = '';
      document.form1.rh05_causa.value = '';
      document.form1.rh05_caub.value  = '';
      document.form1.r59_descr.value  = '';
      document.form1.r59_descr1.value = '';
      document.form1.rh02_tpcont.value = '';
      document.form1.h13_descr.value  = '';
      document.form1.h13_tpcont.value  = '';
      js_camposorigem(false);
      js_disabpropri("");
      js_disabledtipoapos("");
    }
  }
}

function js_mostrarhregime(chave,chave2,chave3,erro){
  document.form1.rh30_descr.value  = chave;
  if(erro==true){
    document.form1.rh02_codreg.focus();
    document.form1.rh02_codreg.value = '';
    document.form1.rh30_regime.value = '';
    document.form1.rh30_vinculo.value = '';
    js_camposorigem(false);
  }else{
    document.form1.rh30_regime.value = chave2;
    document.form1.rh30_vinculo.value = chave3;
    document.form1.rh02_tpcont.value = '';
    document.form1.h13_descr.value  = '';
    document.form1.h13_tpcont.value  = '';
    js_pesquisarh02_tpcont(true);
    if(chave3 == "P"){
      js_camposorigem(true);
    }else{
      js_camposorigem(false);
    }
  }
  js_disabpropri(chave3);
  js_disabledtipoapos(chave3);
}
function js_mostrarhregime1(chave1,chave2,chave3,chave4){
  document.form1.rh02_codreg.value = chave1;
  document.form1.rh30_descr.value  = chave2;
  document.form1.rh30_regime.value = chave3;
  document.form1.rh30_vinculo.value = chave4;
  db_iframe_rhregime.hide();
  document.form1.rh02_tpcont.value = '';
  document.form1.h13_descr.value  = '';
  document.form1.h13_tpcont.value  = '';
  js_pesquisarh02_tpcont(true);
  if(chave4 == "P"){
    js_camposorigem(true);
  }else{
    js_camposorigem(false);
  }
  js_disabpropri(chave4);
  js_disabledtipoapos(chave4);
}
function js_disabpropri(opcao){
  if(opcao == "A"){
    document.form1.rh19_propi.style.backgroundColor='#DEB887';
    document.form1.rh19_propi.readOnly = true;
    document.form1.rh19_propi.value = "";
  }else{
    document.form1.rh19_propi.style.backgroundColor='';
    document.form1.rh19_propi.readOnly = false;
  }
}
/**
 * Verifica se foi selecionado algum valor de salário ou uma função padrão para o calculo
 */
function js_validaPadraoPrevidencia(bAvisoPrevidencia) {

  var salario     = parseFloat(document.form1.rh02_salari.value.trim());
  var valorPadrao = document.form1.rh03_padrao.value;

  if (valorPadrao.trim() == "" && (isNaN(salario) || salario.valueOf() == 0)) {

    document.form1.rh03_padraoprev.value = '';
    document.form1.r02_descrprev.value   = '';

    if (bAvisoPrevidencia) {

      document.form1.rh03_padraoprev.focus();

      alert(_M(sMensagem + 'erro_selecionar_padraoprev'));

      return false;
    }
  }

  return true;
}

/**
 * Habilita select para informar tipo de deficiência
 * no caso de informar sim como deficiente físico
 */
function js_informarTipoDeficiencia() {

  var nodeDeficiente      = $("rh02_deficientefisico");
  var nodeTipoDeficiencia = $("row_rh02_tipodeficiencia");

  if (nodeDeficiente.value == 't' || nodeDeficiente.value.toLowerCase().indexOf('sim') > -1 ) {
    nodeTipoDeficiencia.show();
  } else if (nodeDeficiente.value == 'f' || nodeDeficiente.value.toLowerCase().indexOf('não') > -1 ) {
    nodeTipoDeficiencia.hide();
  }
}

/**
 * Valida tipo de deficiência
 */
function js_validaTipoDeficiencia() {

  var nodeDeficiente      = $("rh02_deficientefisico");
  var nodeTipoDeficiencia = $("rh02_tipodeficiencia");

  if (nodeDeficiente.value == 't' || nodeDeficiente.value.toLowerCase().indexOf('sim') > -1 ) {

    if (nodeTipoDeficiencia.value == 0) {

      alert(_M(sMensagem + 'tipo_deficiencia'));
      return false;

    }

  } else if (nodeDeficiente.value == 'f' || nodeDeficiente.value.toLowerCase().indexOf('não') > -1 ) {

    nodeTipoDeficiencia.value = 0;
    js_ProcCod_rh02_tipodeficiencia('rh02_tipodeficiencia','rh02_tipodeficienciadescr');

  }

  return true;
}

var oFieldSetContaBancaria = $$('div#ctnContaBancariaServidor > fieldset')[0];
oFieldSetContaBancaria.id  ='fdlContaBancaria';
var oToogleContaBancaria   = new DBToogle('fdlContaBancaria', false);
var oToogleRescisao        = new DBToogle('fldRescisao', false);
var oToogleCedencia        = new DBToogle('fdlDadosCedencia', false);

fdlDadosCedencia

js_disabdata("<?=($rh05_taviso)?>");

// Informações das mensagens de sugestão para o padrão de previdência
var aEventoShow       = new Array('onMouseover','onFocus');
var aEventoHide       = new Array('onMouseout' ,'onBlur');
var oDbHintPadraoPrev = new DBHint('oDbHintPadraoPrev');
oDbHintPadraoPrev.setText(_M(sMensagem + 'suggest_padrao_prev'));
oDbHintPadraoPrev.setShowEvents(aEventoShow);
oDbHintPadraoPrev.setHideEvents(aEventoHide);
oDbHintPadraoPrev.make($('LabelPadraoPrev'));
oDbHintPadraoPrev.make($('rh03_padraoprev'));
oDbHintPadraoPrev.make($('r02_descrprev'));

// Hint de aviso para alterações na tabela de previdência
var oDbHintTabelaPrev = new DBHint('oDbHintTabelaPrev');
oDbHintTabelaPrev.setText(_M(sMensagem + 'suggest_tabela_prev'));
oDbHintTabelaPrev.setShowEvents(aEventoShow);
oDbHintTabelaPrev.setHideEvents(aEventoHide);
oDbHintTabelaPrev.make($('LabelTabelaPrev'));
oDbHintTabelaPrev.make($('rh02_tbprev'));
oDbHintTabelaPrev.make($('rh02_tbprevdescr'));


var oInputCnpj = new DBInputCNPJ($('rh02_cnpjcedencia'));
var oInputData = new DBInputDate($('rh02_datacedencia'));

<?
if(isset($rh30_vinculo) && $rh30_vinculo == "P"){
  echo "js_camposorigem(true);";
}else{
  echo "js_camposorigem(false);";
  echo "js_disabpropri('".@$rh30_vinculo."');";
}
?>

</script>
<?

if(isset($rh21_regpri)){

  echo "<script>";
  echo "js_pesquisarh21_regpri(false);";
  echo "</script>";
}
?>
