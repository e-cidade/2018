<?
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

//MODULO: empenho
$clempempenho->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc50_descr");
$clrotulo->label("e60_codcom");
$clrotulo->label("e63_codhist");
$clrotulo->label("e44_tipo");
$clrotulo->label("c58_descr");

?>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Te60_codemp?>">
          <?=@$Le60_codemp?>
        </td>
        <td>
          <?
          db_input('e60_numemp',10,'',true,'hidden',3);
          db_input('e60_codemp',10,$Ie60_codemp,true,'text',3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Te60_numcgm?>">
          <?=$Le60_numcgm?>
        </td>
        <td>
          <?
          db_input('e60_numcgm',10,$Ie60_numcgm,true,'text',3);
          db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Te60_codcom?>">
          <?=$Le60_codcom?>
        </td>
        <td>
          <?
          db_input('e60_codcom',10,$Ie60_codcom,true,'text',3);
          db_input('pc50_descr',40,$Ipc50_descr,true,'text',3,'');

          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Te60_tipol?>">
          <?=@$Le60_tipol?>
        </td>
        <td>
          <?
          if(isset($e60_codcom)){
            $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",'',"l03_codcom=$e60_codcom"));
            if($clcflicita->numrows>0){
              db_selectrecord("e60_tipol",$result,true,1,"","","");
              $dop=$db_opcao;
            }else{
              $e60_tipol='';
              $e60_numerol='';
              db_input('e60_tipol',10,$Ie60_tipol,true,'text',3);
              $dop='3';
            }
            ?>
            <?=@$Le60_numerol?>
            <?
            db_input('e60_numerol',10,$Ie60_numerol,true,'text',$dop);
          }
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Te60_codtipo?>">
          <?=$Le60_codtipo?>
        </td>
        <td>
          <?
          $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
          db_selectrecord("e60_codtipo",$result,true,$db_opcao);

          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Te63_codhist?>">
          <?=$Le63_codhist?>
        </td>
        <td>
          <?

          $result=$clemphist->sql_record($clemphist->sql_query_file(null,"e40_codhist,e40_descr"));
          db_selectrecord("e63_codhist",$result,true,1,"","","","Nenhum");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Te44_tipo?>">
          <?=$Le44_tipo?>
        </td>
        <td>
          <?
          $result=$clempprestatip->sql_record($clempprestatip->sql_query_file(null,"e44_tipo as tipo,e44_descr,e44_obriga","e44_obriga "));
          $numrows =  $clempprestatip->numrows;
          $arr = array();
          for($i=0; $i<$numrows; $i++){
            db_fieldsmemory($result,$i);
            if($e44_obriga == 0 && empty($e44_tipo)){
              $e44_tipo = $tipo;
            }
            $arr[$tipo] = $e44_descr;
          }
          db_select("e44_tipo",$arr,true,3);

          ?>
        </td>
      </tr>

      <?
      if (isset($e60_numemp)) {

        $sql = "select pagordem.* from pagordem inner join pagordemdesconto on e34_codord = e50_codord
	  													where e50_numemp = $e60_numemp";
        //die($sql);
        $result = $clpagordem->sql_record($sql);
        $ldesconto = false;
        if ($clpagordem->numrows > 0) {
          $ldesconto = true;
        }
      }
      if(isset($e60_vlrliq) && $e60_vlrliq == 0 && !$ldesconto && $e60_anousu >= db_getsession("DB_anousu")){
        ?>
        <tr>
          <td nowrap title="Desdobramentos">
            <b><?="Desdobramento:"?></b>
          </td>
          <td>
            <?
            $result = $clempempaut->sql_record($clempempaut->sql_query(null,"e61_autori","","e61_numemp = $e60_numemp"));
            if($clempempaut->numrows > 0){
              $oResult = db_utils::fieldsMemory($result,0);
              $e54_autori = $oResult->e61_autori;
              $anoUsu = db_getsession("DB_anousu");
              $sWhere = "e56_autori = ".$e54_autori." and e56_anousu = ".$anoUsu;
              $result = $clempautidot->sql_record($clempautidot->sql_query_dotacao(null,"e56_coddot",null,$sWhere));

              if($clempautidot->numrows > 0){
                $oResult = db_utils::fieldsMemory($result,0);
                $result = $clorcdotacao->sql_record($clorcdotacao->sql_query( $anoUsu,$oResult->e56_coddot,"o56_elemento,o56_codele"));
                if ($clorcdotacao->numrows > 0) {

                  $oResult = db_utils::fieldsMemory($result,0);
                  $oResult->estrutural = criaContaMae($oResult->o56_elemento."00");
                  $sWhere = "o56_elemento like '$oResult->estrutural%' and o56_codele <> $oResult->o56_codele and o56_anousu = $anoUsu";
                  $sSql = "select distinct o56_codele,o56_elemento,o56_descr
											  from empempitem
											        inner join pcmater on pcmater.pc01_codmater    = empempitem.e62_item
											        inner join pcmaterele on pcmater.pc01_codmater = pcmaterele.pc07_codmater
											        left join orcelemento on orcelemento.o56_codele = pcmaterele.pc07_codele
											                              and orcelemento.o56_anousu = $anoUsu
											    where o56_elemento like '$oResult->estrutural%'
											    and e62_numemp = $e60_numemp and o56_anousu = $anoUsu";
                  $result = $clorcelemento->sql_record($sSql);

                  $oResult = db_utils::getCollectionByRecord($result);

                  $numrows =  $clorcelemento->numrows;
                  $aEle = array();

                  foreach ($oResult as $oRow){
                    $aEle[$oRow->o56_codele] = $oRow->o56_descr;
                  }
                  //die($clempautitem->sql_query_autoriza (null,null,"e55_codele",null,"e55_autori = $e54_autori"));
                  $result = $clempelemento->sql_record($clempelemento->sql_query_file($e60_numemp,null,"e64_codele"));
                  if($clempelemento->numrows > 0){
                    $oResult = db_utils::fieldsMemory($result,0);
                  }
                  if(!isset($e56_codele)){
                    $e56_codele = $oResult->e64_codele;
                  }
                  $e64_codele = $e56_codele;
                  db_input('e64_codele',10,0,true,'hidden',3);
                  db_select("e56_codele",$aEle,true,1);
                }
              }
            }else{
              $aEle = array();
              $e56_codele = "";
              db_select("e56_codele",$aEle,true,1);
            }
            ?>
          </td>
        </tr>
      <?
      }else{
        if(isset($e60_vlrliq) && $e60_vlrliq != 0){
          $mensagem = "Você não pode alterar o desdobramento deste empenho porque este já possui valor liquidado. Se realmente for necessária a alteração, anule todas as liquidações";
        }else if(isset($ldesconto) && $ldesconto){
          $mensagem = "Este empenho teve uma operação de desconto e isto inviabiliza a substituição do desdobramento.";
        }

      }
      ?>

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
        <td nowrap title="<?=@$Te60_destin?>">
          <?=@$Le60_destin?>
        </td>
        <td>
          <?
          db_input('e60_destin',40,$Ie60_destin,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>

        <!-- ContratosPADRS: despesa funcionario form -->

      <tr>
        <td nowrap title="<?=@$Te60_resumo?>" colspan="2">
          <fieldset>
            <legend><b><?=@$Le60_resumo?></b></legend>
            <?
            db_textarea('e60_resumo',8,90,$Ie60_resumo,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
      <?
      $anousu = db_getsession("DB_anousu");

      if ($anousu > 2007){
        ?>
        <tr>
          <td nowrap title="<?=@$Te60_concarpeculiar?>"><?
            db_ancora(@$Le60_concarpeculiar,"js_pesquisae60_concarpeculiar(true);",$db_opcao);
            ?></td>
          <td>
            <?
            db_input("e60_concarpeculiar",10,$Ie60_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisae60_concarpeculiar(false);'");
            db_input("c58_descr",50,0,true,"text",3);
            ?>
          </td>
        </tr>
      <?
      } else {
        $e60_concarpeculiar = 0;
        db_input("e60_concarpeculiar",10,0,true,"hidden",3,"");

      }
      if (isset($e60_numemp) && isset($e30_notaliquidacao) && $e30_notaliquidacao != '') {
        $rsNotaLiquidacao  = $oDaoEmpenhoNl->sql_record(
          $oDaoEmpenhoNl->sql_query_file(null,"e68_numemp","","e68_numemp = {$e60_numemp}"));
        if ($oDaoEmpenhoNl->numrows == 0) {
          ?>
          <tr>
            <td nowrap title="Nota de liquidação">
              <b>Nota de liquidação:</b>
            </td>
            <td>
              <?
              $aNota = array("s"=>"Sim","n" => "NÃO");
              db_select("e68_numemp",$aNota,true,1);
              ?>
            </td>
          </tr>
        <?
        }
      }
      ?>
      <!--[Extensao OrdenadorDespesa] inclusao_ordenador-->

    </table>
  </center>
  <input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >

  <input type="button" id="btnLancarCotasMensais" value="Manutenção de Cotas Mensais" onclick="manutencaoCotasMensais()" />

  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar empenhos" onclick="js_pesquisa();" >
</form>


<script>

  function manutencaoCotasMensais () {

    oViewCotasMensais = new ViewCotasMensais('oViewCotasMensais', $F('e60_numemp'));
    oViewCotasMensais.setReadOnly(false);
    oViewCotasMensais.abrirJanela();
  }


  function js_pesquisae60_concarpeculiar(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr','Pesquisa',true);
    }else{
      if(document.form1.e60_concarpeculiar.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.e60_concarpeculiar.value+'&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false);
      }else{
        document.form1.c58_descr.value = '';
      }
    }
  }
  function js_mostraconcarpeculiar(chave,erro){
    document.form1.c58_descr.value = chave;
    if(erro==true){
      document.form1.e60_concarpeculiar.focus();
      document.form1.e60_concarpeculiar.value = '';
    }
  }
  function js_mostraconcarpeculiar1(chave1,chave2){
    document.form1.e60_concarpeculiar.value = chave1;
    document.form1.c58_descr.value          = chave2;
    db_iframe_concarpeculiar.hide();
  }
  function js_pesquisa(){
    js_OpenJanelaIframe('','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_empempenho.hide();
    <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    ?>
  }







  /**
   * Ajustes no layout
   */
  $("e60_codtipo").style.width      = "15%";
  $("e63_codhist").style.width      = "15%";
  $("e60_codtipodescr").style.width = "84%";
  $("e63_codhistdescr").style.width = "84%";
  $("e44_tipo").style.width         = "100%";
  if ($("e56_codele")) {
    $("e56_codele").style.width       = "100%";
  }
  $("e60_destin").style.width       = "100%";
  $("e60_resumo").style.width       = "100%";



  function js_verificaFinalidadeEmpenho() {

    js_divCarregando("Aguarde, verificando recurso da dotação...", "msgBox");
    var oParam                = new Object();
    oParam.exec               = "getFinalidadePagamentoFundebEmpenho";
    oParam.iSequencialEmpenho = $F('e60_numemp');

    new Ajax.Request('emp4_empenhofinanceiro004.RPC.php',
      {method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: function (oAjax) {

          js_removeObj("msgBox");
          var oRetorno = eval("("+oAjax.responseText+")");

          if (!oRetorno.lPossuiFinalidadePagamentoFundeb) {

            $('trFinalidadeFundeb').style.display = 'none';

          } else {

            $('trFinalidadeFundeb').style.display = '';
            $("e151_codigo").style.width      = "15%";
            $("e151_codigodescr").style.width = "84%";

            if (oRetorno.oFinalidadePagamentoFundeb) {

              $('e151_codigo').value = oRetorno.oFinalidadePagamentoFundeb.e151_codigo;
              js_ProcCod_e151_codigo('e151_codigo','e151_codigodescr');
            }
          }

        }
      });
  }

  js_verificaFinalidadeEmpenho();



</script>
