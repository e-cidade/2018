<?php
/*
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
require_once(modification("model/empenho/AutorizacaoEmpenho.model.php"));

//MODULO: empenho
$clempautoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("e44_tipo");
$clrotulo->label("pc50_descr");
$clrotulo->label("e57_codhist");
$clrotulo->label("c58_descr");
$clrotulo->label("e69_numero");
$clrotulo->label("e69_dtnota");
$clrotulo->label("e69_dtvencimento");
$clrotulo->label("e69_localrecebimento");
$clrotulo->label("e69_dtrecebe");
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac10_obs");
$clrotulo->label("e50_obs");
$clrotulo->label("cc31_justificativa");
$clrotulo->label("cc31_classificacaocredores");

$oDaoClassificaoCredor   = new cl_classificacaocredores();
$rsClassificacaoCredor   = $oDaoClassificaoCredor->sql_record($oDaoClassificaoCredor->sql_query());

$aClassificacaoCredor = array();
if ($rsClassificacaoCredor != false && $oDaoClassificaoCredor->numrows > 0) {

  for ($i = 0; $i < $oDaoClassificaoCredor->numrows; $i++) {

    $oClassificacaoCredor = db_utils::fieldsMemory($rsClassificacaoCredor, $i);
    $aClassificacaoCredor[$oClassificacaoCredor->cc30_codigo] = $oClassificacaoCredor->cc30_descricao;
  }
}

if ($db_opcao == 1) {
  $ac="emp4_empempenho004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $ac = "";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $ac = "";
}


if (!isset($ac)) {
  $ac = "";
}

$db_disab = true;

if (isset($chavepesquisa) && $db_opcao == 1) {

  $result_param = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
  if ($clpcparam->numrows>0){
    db_fieldsmemory($result_param,0);
    if ($pc30_contrandsol=='t'){
      $sql ="    select  pc81_codprocitem
                 from (   select solandam.pc43_solicitem,
                                 max(pc43_ordem) as pc43_ordem
                            from solandam
                        group by solandam.pc43_solicitem
                      ) as x
                      inner join solandam             on solandam.pc43_solicitem             = x.pc43_solicitem
                                                     and solandam.pc43_ordem                 = x.pc43_ordem
		                  inner join solandpadrao         on solandam.pc43_solicitem             = solandpadrao.pc47_solicitem
                                                     and solandam.pc43_ordem                 = solandpadrao.pc47_ordem
                      inner join pcprocitem           on x.pc43_solicitem                    = pc81_solicitem
                      inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                      inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
	                    inner join solicitemprot        on pc49_solicitem                      = x.pc43_solicitem
                where e55_autori= {$chavepesquisa}
                  and solandpadrao.pc47_pctipoandam <> 7
                  and solandam.pc43_depto = ".db_getsession("DB_coddepto");

      $result_andam = db_query($sql);
      if (pg_numrows($result_andam)>0){
        $sqltran = "select distinct x.p62_codtran,
      				x.pc11_numero,
				x.pc11_codigo,
                            x.p62_dttran,
                            x.p62_hora,
                			x.descrdepto,
							x.login
			from ( select distinct p62_codtran,
                          p62_dttran,
                          p63_codproc,
                          descrdepto,
                          p62_hora,
                          login,
                          pc11_numero,
	 		  pc11_codigo,
                          pc81_codproc,
                          e55_autori,
			  e54_anulad
		        from proctransferproc

                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						inner join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						inner join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						inner join empautoriza on empautoriza.e54_autori= empautitem.e55_autori
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and
			      p68_codproc is null and
			      x.e55_autori = {$chavepesquisa} ";

        $result_tran=db_query($sqltran);
        if(pg_numrows($result_tran)==0){
          $db_disab=false;
        }
      }
    }
  }
}

if (!isset($chavepesquisa)) {
  $chavepesquisa = "";
}
?>
<form name="form1" method="post" action="<?=$ac?>" >

  <fieldset style="width:800px">
    <legend><strong>Emissão do Empenho</strong></legend>


    <input type=hidden name=dadosRet value="">
    <input type=hidden name=chavepesquisa value="<?=$chavepesquisa?>" >

    <?php
    db_input('lanc_emp',6,"",true,'hidden',3);
    db_input('lLiquidaMaterialConsumo',10,"",true,'hidden',3);
    db_input('iElemento', 20, "", true, 'hidden', 3);
    db_input('e60_numemp', 20, "", true, 'hidden', 3);
    ?>

    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Te54_autori?>">
            <?=@$Le54_autori?>
          </td>
          <td>
            <?
            db_input('e54_autori',10,$Ie54_autori,true,'text',3)
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te54_numcgm?>">
            <?=$Le54_numcgm?>
          </td>
          <td>
            <?
            db_input('e54_numcgm',10,$Ie54_numcgm,true,'text',3);
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te54_codcom?>">
            <?=@$Le54_codcom?>
          </td>
          <td>
            <?
            if(isset($e54_codcom) && $e54_codcom==''){
              $pc50_descr='';
            }

            /*
               a opção mantem a seleção escolhida pelo usuario ao trocar o tipo de compra


            */
            if (isset($tipocompra) && $tipocompra!=''){
              $e54_codcom=$tipocompra;
            }

            $result=$clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom as e54_codcom,pc50_descr"));
            db_selectrecord("e54_codcom",$result,true,$db_opcao,"","","","","js_reload(this.value)");

            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te54_tipol?>">
            <?=@$Le54_tipol?>
          </td>
          <td>
            <?
            if(isset($tipocompra) || isset($e54_codcom)){
              if(isset($e54_codcom) && empty($tipocompra)){
                $tipocompra=$e54_codcom;
              }
              $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",'',"l03_codcom=$tipocompra"));
              if($clcflicita->numrows>0){
                db_selectrecord("e54_tipol",$result,true,1,"","","");
                $dop=$db_opcao;
              }else{
                $e54_tipol='';
                $e54_numerl='';
                db_input('e54_tipol',10,$Ie54_tipol,true,'text',3);
                $dop='3';
              }
            }else{
              $dop='3';
              $e54_tipol='';
              $e54_numerl='';
              db_input('e54_tipol',10,$Ie54_tipol,true,'text',3);
            }
            ?>
            <?=@$Le54_numerl?>
            <?
            db_input('e54_numerl',8,$Ie54_numerl,true,'text',$dop);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te54_codtipo?>">
            <?=$Le54_codtipo?>
          </td>
          <td>
            <?
            $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
            db_selectrecord("e54_codtipo",$result,true,$db_opcao);

            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te57_codhist?>">
            <?=$Le57_codhist?>
          </td>
          <td>
            <?

            $result=$clemphist->sql_record($clemphist->sql_query_file(null,"e40_codhist,e40_descr"));
            db_selectrecord("e57_codhist",$result,true,1,"","","","Nenhum");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te44_tipo?>">
            <?=$Le44_tipo?>
          </td>
          <td>
            <?php

            $aEventosPrestacaoContas = array();
            $result  = $clempprestatip->sql_record($clempprestatip->sql_query_file(null, "e44_tipo as tipo, e44_descr, e44_obriga", "e44_obriga "));
            $numrows = $clempprestatip->numrows;
            $arr     = array();
            for ($i = 0; $i < $numrows; $i++) {

              db_fieldsmemory($result, $i);
              if ($e44_obriga == 0 && empty($e44_tipo)) {
                $e44_tipo = $tipo;
              }
              $arr[$tipo] = $e44_descr;

              if ($e44_obriga) {
                $aEventosPrestacaoContas[] = $tipo;
              }
            }
            db_select("e44_tipo", $arr, true, 1, "onChange='js_carregarLista()'");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Desdobramentos">
            <b>Desdobramento:</b>
          </td>
          <td>
            <?
            if(isset($e54_autori)){
              $anoUsu = db_getsession("DB_anousu");
              $sWhere = "e56_autori = ".$e54_autori." and e56_anousu = ".$anoUsu;
              $result = $clempautidot->sql_record($clempautidot->sql_query_dotacao(null,"e56_coddot",null,$sWhere));
              echo pg_last_error();
              if($clempautidot->numrows > 0){
                $oResult = db_utils::fieldsMemory($result,0);
                $result = $clorcdotacao->sql_record($clorcdotacao->sql_query( $anoUsu,$oResult->e56_coddot,"o56_elemento,o56_codele"));
                if ($clorcdotacao->numrows > 0) {

                  $oResult = db_utils::fieldsMemory($result,0);
                  $oResult->estrutural = criaContaMae($oResult->o56_elemento."00");
                  $sWhere = "o56_elemento like '$oResult->estrutural%' and o56_codele <> $oResult->o56_codele and o56_anousu = $anoUsu";
                  //$sSql   = $clempautitem->sql_query_pcmaterele(null,null,"o56_codele,o56_elemento,o56_descr",null,$sWhere);
                  $sSql = "select distinct o56_codele,o56_elemento,o56_descr
											  from empautitem
											        inner join empautoriza on empautoriza.e54_autori = empautitem.e55_autori
											        inner join pcmater on pcmater.pc01_codmater    = empautitem.e55_item
											        inner join pcmaterele on pcmater.pc01_codmater = pcmaterele.pc07_codmater
											        left join orcelemento on orcelemento.o56_codele = pcmaterele.pc07_codele
											                              and orcelemento.o56_anousu = $anoUsu
											    where o56_elemento like '$oResult->estrutural%'
											    and e55_autori = $e54_autori and o56_anousu = $anoUsu";
                  //die($sSql);
                  $result = $clempautitem->sql_record($sSql);
                  $aEle = array();
                  if($clempautitem->numrows > 0){
                    $oResult = db_utils::getCollectionByRecord($result);

                    $numrows =  $clorcelemento->numrows;


                    foreach ($oResult as $oRow){
                      $aEle[$oRow->o56_codele] = $oRow->o56_descr;
                    }
                  }

                  $result = $clempautitem->sql_record($clempautitem->sql_query_autoriza (null,null,"e55_codele",null,"e55_autori = $e54_autori"));
                  if($clempautitem->numrows > 0){
                    $oResult = db_utils::fieldsMemory($result,0);
                  }
                  $e56_codele = $oResult->e55_codele;
                  db_select('e56_codele', $aEle, true, 1, "onChange='js_carregarLista()'");
                }
              }
            }else{
              $aEle = array();
              $e56_codele = "";
              db_select('e56_codele', $aEle, true, 1, "onChange='js_carregarLista()'");
            }
            ?>
          </td>
        </tr>

        <tr id="trFinalidadeFundeb" style="display: none;">
          <td><b>Finalidade:</b></td>
          <td>
            <?php
            $oDaoFinalidadeFundeb = db_utils::getDao('finalidadepagamentofundeb');
            $sSqlFinalidadeFundeb = $oDaoFinalidadeFundeb->sql_query_file(null, "e151_codigo, e151_descricao", "e151_codigo");
            $rsBuscaFinalidadeFundeb = $oDaoFinalidadeFundeb->sql_record($sSqlFinalidadeFundeb);
            db_selectrecord('e151_codigo', $rsBuscaFinalidadeFundeb, true, 1);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Te54_destin?>">
            <?=@$Le54_destin?>
          </td>
          <td>
            <?
            db_input('e54_destin',90,$Ie54_destin,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>

          <!-- ContratosPADRS: despesa funcionario form -->

        <tr>
          <td nowrap title="<?=@$Te54_resumo?>" valign ='top' colspan="2">

            <fieldset>
              <legend><strong><?=@$Le54_resumo?></strong></legend>
              <?php
              db_textarea('e54_resumo',3,90,$Ie54_resumo,true,'text',$db_opcao,"");
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td nowrap valign ='top' colspan="2">
            <fieldset>
              <legend><b>Informações da OP</b></legend>
              <?php
              if (isset($e54_resumo)) {
                $e50_obs = $e54_resumo;
              }
              db_textarea('e54_resumo',3,90,$Ie54_resumo,true,'text',$db_opcao,"","e50_obs");
              ?>
            </fieldset>
          </td>
          <td>
          </td>
        </tr>
        <?
        $anousu = db_getsession("DB_anousu");

        if ($anousu > 2007){
          ?>
          <tr>
            <td nowrap title="<?=@$Te54_concarpeculiar?>"><?
              db_ancora(@$Le54_concarpeculiar,"js_pesquisae54_concarpeculiar(true);",$db_opcao);
              ?></td>
            <td>
              <?
              if (isset($concarpeculiar) && trim(@$concarpeculiar) != ""){
                $e54_concarpeculiar = $concarpeculiar;
                $c58_descr          = $descr_concarpeculiar;
              }
              db_input("e54_concarpeculiar",10,$Ie54_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisae54_concarpeculiar(false);'");
              db_input("c58_descr",50,0,true,"text",3);
              ?>
            </td>
          </tr>
        <?
        } else {
          $e54_concarpeculiar = 0;
          db_input("e54_concarpeculiar",10,0,true,"hidden",3,"");
        }
        ?>

        <tr>
          <td title="<?=@$Tac16_sequencial?>" align="left">
            <?php
            $db_opcao_antiga = $db_opcao;
            if ($lAutorizacaoAcordo) {
              $db_opcao = 3;
            }
            db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);",$db_opcao);
            ?>
          </td>
          <td align="left">
            <?php
            db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text',
                     $db_opcao," onchange='js_pesquisaac16_sequencial(false);'");
            db_input('ac16_resumoobjeto',40,$Iac16_resumoobjeto,true,'text',3);
            $db_opcao = $db_opcao_antiga;
            ?>
          </td>
        </tr>

        <tr>
          <td colspan="2" nowrap>
            <fieldset>
              <legend>Lista de Classificação de Credor</legend>
              <table border="0" style="width: 100%;">
                <tr>

                  <td nowrap id="dispensa_titulo" style="width: 210px; display: table-cell;">
                    <label class="bold" for="classificacao_credor_combo">Dispensa:</label>
                  </td>
                  <td id="dispensa_linha" style="display: table-cell;" nowrap>
                  <?php
                  $aOpcoes = array(0 => "Não", 1 => "Sim");
                  db_select('classificacao_credor_combo', $aOpcoes, true, 1, "style='width: 95px;' onChange='js_classificacaoCredor()'");
                  ?>
                  </td>
                </tr>
                <tr id="lista_credor_linha">
                  <td style="width: 210px;">
                    <label class="bold" for="cc30_descricao"><?= $Lcc31_classificacaocredores ?></label>
                  </td>
                  <td nowrap>
                    <?php

                    $cc30_descricao             = "";
                    $cc31_classificacaocredores = "";
                    if (isset($iClassificacaoCredor) && isset($aClassificacaoCredor[$iClassificacaoCredor])) {

                      $cc31_classificacaocredores = $iClassificacaoCredor;
                      $cc30_descricao             = $aClassificacaoCredor[$iClassificacaoCredor];
                    }

                    $Gcc30_descricao = 't';
                    db_input('cc31_classificacaocredores', 10, 0, true, 'text', 3);
                    db_input('cc30_descricao', 40, 0, true, 'text', 3);
                    ?>
                  </td>
                </tr>
                <tr style="display: none;" id="justificativa_dispensa_linha">
                  <td colspan="2">
                    <fieldset>
                      <legend><label class="bold" for="cc31_justificativa"><?= $Lcc31_justificativa ?></label></legend>
                      <?php
                      db_textarea('cc31_justificativa', 3, 100, $Icc31_justificativa, true, 'text', $db_opcao, "");
                      ?>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
        </td>
        </tr>

        <tr id='notas' style="display: none;">
          <td colspan="2">
            <fieldset>
              <legend>Nota</legend>
              <table width="100%" border="0">
                <tr>
                  <td style="width: 140px;" nowrap title="<?= $Te69_numero?>">
                    <label class="bold" for="e69_numero"><?php echo $Le69_numero;?></label>
                  </td>
                  <td>
                    <? db_input('e69_numero', 10, 1, true, 'text', 1); ?>
                  </td>
                  <td style="width: 140px;" nowrap title="<?= $Te69_dtnota ?>">
                    <label class="bold" for="e69_dtnota"><?= $Le69_dtnota?></label>
                  </td>
                  <td>
                    <?php $e69_dtnota_dia = !empty($_POST['e69_dtnota_dia']) ? $_POST['e69_dtnota_dia'] : null ?>
                    <?php $e69_dtnota_mes = !empty($_POST['e69_dtnota_mes']) ? $_POST['e69_dtnota_mes'] : null ?>
                    <?php $e69_dtnota_ano = !empty($_POST['e69_dtnota_ano']) ? $_POST['e69_dtnota_ano'] : null ?>
                    <?php db_inputData('e69_dtnota', $e69_dtnota_dia, $e69_dtnota_mes, $e69_dtnota_ano, true, 'text', 1); ?>
                  </td>
                </tr>
                <!--[Extensao ContratosPADRS] campo serie nota -->
                <tr>
                  <td nowrap title="<?= $Te69_dtrecebe ?>">
                    <label class="bold" for="e69_dtrecebe"><?= $Le69_dtrecebe ?></label>
                  </td>
                  <td>
                    <?php $e69_dtrecebe_dia = !empty($_POST['e69_dtrecebe_dia']) ? $_POST['e69_dtrecebe_dia'] : null ?>
                    <?php $e69_dtrecebe_mes = !empty($_POST['e69_dtrecebe_mes']) ? $_POST['e69_dtrecebe_mes'] : null ?>
                    <?php $e69_dtrecebe_ano = !empty($_POST['e69_dtrecebe_ano']) ? $_POST['e69_dtrecebe_ano'] : null ?>
                    <?php db_inputdata('e69_dtrecebe', $e69_dtrecebe_dia, $e69_dtrecebe_mes, $e69_dtrecebe_ano, true, 'text', 1) ?>
                  </td>
                  <td nowrap title="<?= $Te69_dtvencimento ?>">
                    <label class="bold" for="e69_dtvencimento"><? echo $Le69_dtvencimento; ?></label>
                  </td>
                  <td>
                    <?php $e69_dtvencimento_dia = !empty($_POST['e69_dtvencimento_dia']) ? $_POST['e69_dtvencimento_dia'] : null ?>
                    <?php $e69_dtvencimento_mes = !empty($_POST['e69_dtvencimento_mes']) ? $_POST['e69_dtvencimento_mes'] : null ?>
                    <?php $e69_dtvencimento_ano = !empty($_POST['e69_dtvencimento_ano']) ? $_POST['e69_dtvencimento_ano'] : null ?>
                    <?php db_inputdata('e69_dtvencimento', $e69_dtvencimento_dia, $e69_dtvencimento_mes, $e69_dtvencimento_ano, true, 'text', 1) ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?= $Te69_localrecebimento ?>">
                    <label class="bold" for="e69_localrecebimento"><?= $Le69_localrecebimento?></label>
                  </td>
                  <td colspan="3">
                    <?php
                    $Ne69_localrecebimento = null;
                    db_input("e69_localrecebimento", 70, $Ie69_localrecebimento, true, "text", $db_opcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="regime_competencia" style="display:">
                    <label for="competencia_regime"><b>Competência:</b></label>
                  </td>
                  <td class="regime_competencia" style="display:">
                    <input id="competencia_regime" name="competencia_regime" class="field-size3">
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>

        <!--[Extensao OrdenadorDespesa] inclusao_ordenador-->

      </table>

      <table style="margin-top: 10px;">
        <tr>
          <td colspan='2' align='center' nowrap>
            <fieldset>
              <strong>
                <?php $opc = !empty($_POST['opc']) ? $_POST['opc'] : null ?>
                <input onclick="js_mostrarNota(false);" name='opc' type='radio' value='0' id='id_0' <?php echo $opc == 0 ? 'checked' : '' ?>> <label for="id_0">Não liquidar</label>
                <input onclick="js_mostrarNota(true);" name='opc' type='radio' <?php echo $lLiquidar ?> value='2' id='id_1' <?php echo $opc == 2 ? 'checked' : '' ?>> <label for="id_1">Liquidar</label>
              </strong>
            </fieldset>
          </td>
        </tr>
      </table>

  </fieldset>


  <br>

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit"
         id="db_opcao"
         onclick='return js_valida()';
         value="<?=($db_opcao==1||$db_opcao==33?"Empenhar e imprimir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
  "<?=($db_botao==false?"disabled":($db_disab==false?"disabled":""))?>" >

  <?if($db_opcao==1){?>
    <input name="op"
           type="button"

           value="Empenhar e não imprimir"
	  "<?=($db_disab==false?"disabled":"")?>" onclick="return js_naoimprimir();" >
<?}?>

  <input name="lanc" type="button" id="lanc" value="Lançar autorizações" onclick="parent.location.href='emp1_empautoriza001.php';">

  <?php $lDisable = empty($e60_numemp) ? "disabled" : ''; ?>

  <input type="button" id="btnLancarCotasMensais" value="Manutenção das Cotas Mensais" onclick="manutencaoCotasMensais();" <?php echo $lDisable; ?> />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar autorizações" onclick="js_pesquisa();" >
  </center>
</form>

<div id="ctnCotasMensais" class="container" style=" width: 500px;">
</div>
<?php
$e69_localrecebimento = !empty($_POST['e69_localrecebimento']) ? $_POST['e69_localrecebimento'] : null;
?>
<script>

  js_carregarLista();

  oCodClassificacaoCredor = $('cc31_classificacaocredores');
  oComboDispensa          = $('classificacao_credor_combo');

  oLinhasNotas         = $('notas');
  oLinhaJustificativa  = $('justificativa_dispensa_linha');
  oLinhaClassificacao  = $('lista_credor_linha');
  oOpcaoLiquidar       = document.form1.opc;
  iCodigoClassificacao = '';
  lDispensa                = false;
  var lInformarCompetencia = <?=$lMostrarCompetencia ? 'true' : 'false';?>;

  //Guarda o código da classificação do tipo dispensa.
  const CODIGO_DISPENSA = <?= ClassificacaoCredor::DISPENSA ?>;

  //Array para guardar as informações referente as classificações de credor codigo => descricao
  var aClassificaoCredor      = new Array();
  var aEventosPrestacaoContas = new Array();

  <?php
  //Preenche os arrays do javascript com as informações vindas do php.
  foreach ($aEventosPrestacaoContas as $iEvento) {
  ?>
    aEventosPrestacaoContas[aEventosPrestacaoContas.length] = "<?= $iEvento ?>";
  <?php
  }
  foreach ($aClassificacaoCredor as $iCodigo => $sDescricao) {
  ?>
    aClassificaoCredor[<?= $iCodigo ?>] = "<?= $sDescricao ?>";
  <?php
  }
  ?>

  if (oOpcaoLiquidar.value == '2') {
    js_mostrarNota(true);
  }

  /**
   * Função responsável por carregar a sugestão de Lista de Classificação
   */
  function js_carregarLista() {

    if (empty($('e54_autori').value)) {
      return;
    }

    var oParametros = {
      exec               : 'getListaPorAutorizacao',
      iCodigo            : $('e54_autori').value,
      iTipoCompra        : $('e54_codcom').value,
      iEvento            : $('e44_tipo').value,
      iElemento          : $('e56_codele').value
    };
    var fnRetorno = function(oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.message.urlDecode());
      }

      $('cc31_classificacaocredores').value = oRetorno.iCodigoLista;
      $('cc30_descricao').value = oRetorno.sDescricaoLista;
      lDispensa = oRetorno.lDispensa;
      js_desabilitaDispensa();
      iCodigoClassificacao = oCodClassificacaoCredor.value;
      oComboDispensa.value = 0;
      js_classificacaoCredor();
    };
    new AjaxRequest("emp1_classificacaocredores.RPC.php", oParametros, fnRetorno).execute();
  }

  /**
   * Função responsável por desabilitar o campo "Dispensa" quando a Lista for do tipo Dispensa
   */
  function js_desabilitaDispensa() {

    var oDispensaTitulo = $('dispensa_titulo');
    oComboDispensa.style.color = '#000';
    if (lDispensa == true) {

      oDispensaTitulo.style.display = "none";
      oComboDispensa.style.display  = "none";
      return;
    }
      oDispensaTitulo.style.display = "table-cell";
      oComboDispensa.style.display  = "table-cell";
  }

  /**
   * Função responsável por mudanças na tela referente a alteração da opção "Dispensa" da Classificação de Credor.
   */
  function js_classificacaoCredor() {

    var sVisivel              = 'table-row';
    var iClassificaoCredor    = iCodigoClassificacao;
    var sDisplayJustificativa = 'none';

    if (oComboDispensa.value == 1) {

      sVisivel              = 'none';
      iClassificaoCredor    = CODIGO_DISPENSA;
      sDisplayJustificativa = 'table-row';
    } else {
      $('cc31_justificativa').value = "";
    }

    oCodClassificacaoCredor.value        = iClassificaoCredor;
    oLinhaJustificativa.style.display    = sDisplayJustificativa;
    oLinhaClassificacao.style.display    = sVisivel;
  }

  /**
   * Função responsável pelas mudanças na tela referente a alteração entre as opções Não Liquidar/Liquidar.
   */
  function js_mostrarNota(lMostar) {

    var sDisplay = 'none';
    if (lMostar) {
      sDisplay = 'table-row';
    }
    oLinhasNotas.style.display = sDisplay;
  }

  function manutencaoCotasMensais () {

    oViewCotasMensais = new ViewCotasMensais('oViewCotasMensais', $F('e60_numemp'));
    oViewCotasMensais.setReadOnly(false);
    oViewCotasMensais.abrirJanela();
  }


  /**
   * funcao para avisar o usuario sobre liquidar empenho dos grupos 7, 8, 10
   * onde nao ira mais forçar pela ordem de compra
   */
  $('id_1').observe('change', function() {

    if ($F('lLiquidaMaterialConsumo') == 'true') {

      var sGrupo = '<?php echo $sGrupoDesdobramento; ?>';
      var sMensagem = _M('financeiro.empenho.emp4_empempenho004.liquidacao_item_consumo_imediato', {sGrupo : sGrupo});
      alert(sMensagem);
    }
  });

  function js_pesquisae54_concarpeculiar(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr','Pesquisa',true,'0','1');
    }else{
      if(document.form1.e54_concarpeculiar.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.e54_concarpeculiar.value+'&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false);
      }else{
        document.form1.c58_descr.value = '';
      }
    }
  }

  function js_mostraconcarpeculiar(chave,erro){
    document.form1.c58_descr.value = chave;
    if(erro==true){
      document.form1.e54_concarpeculiar.focus();
      document.form1.e54_concarpeculiar.value = '';
    }
  }

  function js_mostraconcarpeculiar1(chave1,chave2){
    document.form1.e54_concarpeculiar.value = chave1;
    document.form1.c58_descr.value          = chave2;
    db_iframe_concarpeculiar.hide();
  }

  function js_naoimprimir(){
    if (!js_valida()) {
      return false;
    }
    obj=document.createElement('input');
    obj.setAttribute('name','naoimprimir');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','true');
    document.form1.appendChild(obj);
    document.form1.incluir.click();
  }

  function js_reload(valor){
    obj=document.createElement('input');
    obj.setAttribute('name','tipocompra');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value',valor);
    document.form1.appendChild(obj);
    document.form1.submit();
  }

  function js_pesquisae54_numcgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,0);
    }else{
      if(document.form1.e54_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e54_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
      }else{
        document.form1.z01_nome.value = '';
      }
    }
  }

  function js_mostracgm(erro,chave){
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.e54_numcgm.focus();
      document.form1.e54_numcgm.value = '';
    }
  }

  function js_mostracgm1(chave1,chave2){
    document.form1.e54_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_cgm.hide();
  }

  function js_pesquisae54_login(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
    }else{
      if(document.form1.e54_login.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.e54_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
      }else{
        document.form1.nome.value = '';
      }
    }
  }

  function js_mostradb_usuarios(chave,erro){
    document.form1.nome.value = chave;
    if(erro==true){
      document.form1.e54_login.focus();
      document.form1.e54_login.value = '';
    }
  }

  function js_mostradb_usuarios1(chave1,chave2){
    document.form1.e54_login.value = chave1;
    document.form1.nome.value = chave2;
    db_iframe_db_usuarios.hide();
  }

  function js_pesquisa(){

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empempenho','db_iframe_orcreservaaut','func_orcreservaautnota.php?funcao_js=parent.js_preenchepesquisa|e54_autori|e55_codele','Pesquisa',true,0);
  }

  function js_preenchepesquisa(chave, chave2){
    db_iframe_orcreservaaut.hide();
    <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&iElemento='+chave2";
    ?>
  }

  function js_valida() {
    // [Extensao ContratosPADRS] validacao campo modalidade

    var sMensagem = 'O empenho não se enquadra na regra de nenhuma Lista de Classificação de Credores. ';
    sMensagem += 'Para verificar as listas disponíveis acesse o menu Empenho > Cadastros > Lista de Classificação de Credores. ';
    sMensagem += 'Deseja emitir o empenho sem vínculo a uma lista?';
    if (empty($('cc31_classificacaocredores').value) && !confirm(sMensagem)) {
      return false;
    }

    options = document.form1.opc;
    sValor  = '';
    for (var i  = 0; i < options.length; i++) {

      if (options[i].checked) {

        sValor = options[i].value;
        break;
      }
    }

    //Obriga preenchimento da justificativa quando a opção dispensa for selecionada.
    if (oComboDispensa.value.trim() == 1 && $F('cc31_justificativa').trim() == '') {

      alert("Para dispensar o empenho da classificação de credores é obrigatório informar uma justificativa.");
      return false;
    }

    //o usuario escolheu liquidar o empenho. entao é obrigatorio informar
    //a data, o número, data de recebimento, local de recebimento e data de vencimento da nota
    //(data de vencimento somente se não for dispensa(4));
    if (sValor == 2) {

      if (!validarCompetencia()) {
        return;
      }
      sNumeroNota    = $F('e69_numero');
      /** [Extensao ContratosPADRS] valor serie nota */
      sDtNota        = $F('e69_dtnota');
      sDtRecebe      = $F('e69_dtrecebe');
      sDtVence       = $F('e69_dtvencimento');
      sLocalRecebe   = $F('e69_localrecebimento');
      sObs           = $F('e50_obs');
      sClassificacao = oCodClassificacaoCredor.value;

      if (oComboDispensa.value == 1) {

        sClassificacao = CODIGO_DISPENSA;
        lDispensa = true;
      }

      /** [Extensao ContratosPADRS] valida serie nota */

      if (sDtNota.trim() == '') {

        alert('O campo Data nota é de preenchimento obrigatório.');
        return false;
      }
      if (sDtRecebe.trim() == '') {

        alert('O campo Data do Recebimento da nota é de preenchimento obrigatório.');
        return false;
      }
      if (!lDispensa && sDtVence.trim() == '') {

        alert('O campo Data de Vencimento da nota é de preenchimento obrigatório.');
        return false;
      }
      if (!lDispensa && sLocalRecebe.trim() == '') {

        alert('O campo Local de Recebimento da nota é de preenchimento obrigatório.');
        return false;
      }
      if (sObs.trim() == '') {

        alert('O campo Informações da OP é de preenchimento obrigatório.');
        return false;
      }
      return true;
    } else {
      return true;
    }
  }

  /**
   * Pesquisa acordos
   */
  function js_pesquisaac16_sequencial(lMostrar) {

    if (lMostrar == true) {

      var sUrl = 'func_acordo.php?lDepartamento=1&funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto';
      js_OpenJanelaIframe('',
        'db_iframe_acordo',
        sUrl,
        'Pesquisar Acordo',
        true);
    } else {

      if ($('ac16_sequencial').value != '') {

        var sUrl = 'func_acordo.php?lDepartamento=1&descricao=true&pesquisa_chave='+$('ac16_sequencial').value+
          '&funcao_js=parent.js_mostraacordo';

        js_OpenJanelaIframe('',
          'db_iframe_acordo',
          sUrl,
          'Pesquisar Acordo',
          false);
      } else {
        $('ac16_sequencial').value = '';
      }
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo(chave1,chave2,erro) {

    if (erro == true) {

      $('ac16_sequencial').value   = '';
      $('ac16_resumoobjeto').value = chave1;
      $('ac16_sequencial').focus();

    } else {

      $('ac16_sequencial').value   = chave1;
      $('ac16_resumoobjeto').value = chave2;
      pesquisarAcordoCompetencia();
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo1(chave1,chave2) {

    $('ac16_sequencial').value    = chave1;
    $('ac16_resumoobjeto').value  = chave2;
    pesquisarAcordoCompetencia();
    db_iframe_acordo.hide();
  }
  var oInputCompetencia = new MaskedInput($('competencia_regime'), '99/9999', {placeholder:'_'});

  function habilitaRegimeCompetencia(lHabilitar) {

    $('competencia_regime').value = '';
    for (oCampo of $$('td.regime_competencia')) {
      oCampo.style.display  = lHabilitar ?'': 'none';
    }
  }

  function validarCompetencia() {

    if (lInformarCompetencia) {
      competencia = getValorCompetencia();

      var aPartesCompetencia = competencia.split("/");
      if ((new Number(aPartesCompetencia[0]).valueOf() < 1) || (new Number(aPartesCompetencia[0]).valueOf() > 12)) {

        alert('O mês da competência informada é invalida!');
        return false;
      }
      if (aPartesCompetencia[1].length != 4) {
        alert('Ano da competência está inválido!');
        return false;
      }
    }
    return true;
  }

  function getValorCompetencia() {

    var oCampoCompetencia = $F('competencia_regime');
    valorCompetencia      = oCampoCompetencia.replace(/_/g,'');
    return valorCompetencia;

  }

  function pesquisarAcordoCompetencia() {

    var oParametros = {
      exec   : 'getDadosAcordo',
      acordo : $('ac16_sequencial').value,
    };
    var fnRetorno = function(oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.message.urlDecode());
      }

     lInformarCompetencia = !oRetorno.despesa_antecipada;
     habilitaRegimeCompetencia(!oRetorno.despesa_antecipada);
    };
    new AjaxRequest("con4_programacaoregimecompetencia.RPC.php", oParametros, fnRetorno).setMessage('Aguarde, pesquisando dados do acordo').execute();
  }

  habilitaRegimeCompetencia(lInformarCompetencia);
  /**
   * Ajustes no layout
   */
  $('e54_codcom').style.width       = "15%";
  $('e54_codtipo').style.width      = "15%";
  $('e57_codhist').style.width      = "15%";
  $('e151_codigo').style.width      = "15%";
  $('e54_codcomdescr').style.width  = "84%";
  $('e54_codtipodescr').style.width = "84%";
  $('e57_codhistdescr').style.width = "84%";
  $('e151_codigodescr').style.width = "84%";
  $('e56_codele').style.width       = "100%";
  $('e44_tipo').style.width         = "100%";
  $('e54_resumo').style.width       = "100%";
  $('e50_obs').style.width          = "100%";


  function js_pesquisarRecursoDotacao() {

    js_divCarregando("Aguarde, verificando recurso da dotação...", "msgBox");
    var oParam = new Object();
    oParam.exec = "validarRecursoDotacaoPorAutorizacao";
    oParam.iCodigoAutorizacaoEmpenho = $F('e54_autori');

    new Ajax.Request('emp4_empenhofinanceiro004.RPC.php',
      {method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: function (oAjax) {

          js_removeObj("msgBox");
          var oRetorno = eval("("+oAjax.responseText+")");

          if (oRetorno.lFundeb) {
            $('trFinalidadeFundeb').style.display = '';
          } else {
            $('trFinalidadeFundeb').style.display = 'none';
          }

        }
      });
  }
  js_pesquisarRecursoDotacao();
</script>
