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

include(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcorcamforne->rotulo->label();
$clpcorcamfornelic->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("l20_codigo");

if(isset($db_opcaoal)){
   $db_opcao=33;
   $db_botao=true;
   $op=1;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
    $op=1;

    $rsCgm = $clpcorcamfornelic->sql_record($clpcorcamfornelic->sql_query($pc21_orcamforne));
    if ($clpcorcamfornelic->numrows > 0){

      db_fieldsmemory($rsCgm, 0);
    }
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
    $op=1;
    $rsCgm = $clpcorcamfornelic->sql_record($clpcorcamfornelic->sql_query($pc21_orcamforne));
    if ($clpcorcamfornelic->numrows > 0){

      db_fieldsmemory($rsCgm, 0);
    }
}else{
    $db_opcao = 1;
    if(isset($novo) || isset($verificado) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $pc21_orcamforne = "";
     $pc21_numcgm = "";
     $z01_nome = "";
   }
}
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Fornecedor da Licitação</legend>

        <table>
          <tr>
            <td nowrap>
              <label class="bold" for="l20_codigo">Licitação:</label>
            </td>
            <td>
              <?php
                db_input('pc21_orcamforne',8,$Ipc21_orcamforne,true,'hidden',3);
                db_input('solic',40,"",true,'hidden',3);
                db_input('l20_codigo',8,$Il20_codigo,true,'text',3)
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold" for="pc20_codorc"><?php echo $Lpc21_codorc; ?></label>
            </td>
            <td>
              <?php db_input('pc20_codorc',8,$Ipc21_codorc,true,'text',3); ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold" for="pc21_numcgm"><?php db_ancora($Lpc21_numcgm,"js_pesquisapc21_numcgm(true);",$db_opcao); ?></label>
            </td>
            <td>
              <?php
                db_input('pc21_numcgm',8,$Ipc21_numcgm,true,'text',($db_opcao != 1 ? 3 : 1)," onchange='js_pesquisapc21_numcgm(false);'");
                db_input('z01_nome',40,$Iz01_nome,true,'text',3);
              ?>
            </td>
          </tr>

          <tr id="notificacao" style="display: none">
            <td colspan="2" style="text-align: left; background-color: #fcf8e3; border: 1px solid #fcc888; padding: 5px">
              O CGM informado como Fornecedor da Licitação não está cadastrado como Fornecedor no módulo "Compras".
            </td>
          </tr>

          <tr>
            <td nowrap>
              <label class="bold" for="pc31_liclicitatipoempresa"><?php echo $Lpc31_liclicitatipoempresa; ?></label>
            </td>
            <td>
               <?php
                 $sSqlTipoEmpresas = $oDaoTipoEmpresa->sql_query(null,"*","l32_sequencial");
                 $rsTipoEmpresa    = $oDaoTipoEmpresa->sql_record($sSqlTipoEmpresas);
                 db_selectrecord("pc31_liclicitatipoempresa",$rsTipoEmpresa,true, ($db_opcao != 1 ? 3 : 1));
               ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold" for="pc31_tipocondicao"><?php echo $Lpc31_tipocondicao; ?></label>
            </td>
            <td>
              <?php
                $aOpcoes = array(
                    '' => '',
                    1  => "Convidado e Participante",
                    2  => "Convidado e Não Participante",
                    3  => "Não Convidado e Participante"
                  );

                db_select("pc31_tipocondicao", $aOpcoes, true, $db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold" for="pc31_dtretira"><?php echo $Lpc31_dtretira; ?></label>
            </td>
            <td>
              <?php
                $pc31_dtretira_dia=date('d',db_getsession("DB_datausu"));
                $pc31_dtretira_mes=date('m',db_getsession("DB_datausu"));
                $pc31_dtretira_ano=date('Y',db_getsession("DB_datausu"));
                db_inputdata("pc31_dtretira", $pc31_dtretira_dia, $pc31_dtretira_mes, $pc31_dtretira_ano,true,'text',$db_opcao);
              ?>
              <label class="bold" for="pc31_horaretira"><?php echo $Lpc31_horaretira; ?></label>
              <?php
                $pc31_horaretira=db_hora();
                db_input('pc31_horaretira',8,$Ipc31_horaretira,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold" for="pc31_nomeretira"><?php echo $Lpc31_nomeretira; ?></label>
            </td>
            <td nowrap title="<?php echo $Tpc31_nomeretira; ?>">
              <?php db_input('pc31_nomeretira',50,"",true,'text',$db_opcao,""); ?>
            </td>
          </tr>
        </table>
    </fieldset>
    <?php
      $sWhere = "1!=1";
      if (isset($pc20_codorc) && !empty($pc20_codorc)) {
        $sWhere = "pc22_codorc=".@$pc20_codorc;
      }
      $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_file(null,"pc22_codorc","",$sWhere));

      if($clpcorcamitem->numrows>0){

        if(!empty($pc20_codorc)) {

          $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null,"pc21_codorc","","pc21_codorc=".@$pc20_codorc));

          if($clpcorcamforne->numrows>0){
            ?>
              <input name='lancval' type='button' id='lancval' value='Lançar Propostas'  onclick='(window.CurrentWindow || parent.CurrentWindow).corpo.document.location.href="lic1_orcamlancval001.php?l20_codigo=<?php echo "{$l20_codigo}&pc20_codorc={$pc20_codorc}"; ?>"' <?php ($db_botao==false?" disabled " : "") ?>/>
            <?php
          }
        }
      }
    ?>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='display:none;'":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <div style="margin-top: 5px">
      <?php
        $chavepri= array("pc21_orcamforne"=>@$pc21_orcamforne,"pc21_codorc"=>@$pc21_codorc);
        $cliframe_alterar_excluir->chavepri=$chavepri;

        $sWhere     = "1!=1";
        if (isset($pc20_codorc) && !empty($pc20_codorc)) {
        $sWhere = " pc21_codorc=".@$pc20_codorc;
        }

        $cliframe_alterar_excluir->sql     = $clpcorcamforne->sql_query(null,"pc21_orcamforne,pc21_codorc,pc21_numcgm,z01_nome","",$sWhere);
        $cliframe_alterar_excluir->campos  ="pc21_orcamforne,pc21_numcgm,z01_nome";
        $cliframe_alterar_excluir->legenda="Fornecedores Cadastrados";
        $cliframe_alterar_excluir->iframe_height ="160";
        $cliframe_alterar_excluir->iframe_width ="600";
        $cliframe_alterar_excluir->opcoes =1;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?>
    </div>
  </form>
</div>
<script type="text/javascript">

  function verificaFornecedor() {

    var iCgm = $("pc21_numcgm").value;

    if (iCgm.trim() == '') {
      return false;
    }

    var oParam = {
      exec : "verificaFornecedor",
      iCgm : iCgm
    }

    new AjaxRequest("com1_fornecedor.RPC.php", oParam, function(oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.message.urlDecode());
      }

      if (!oRetorno.lFornecedor) {
        $("notificacao").show();
      }

    }).setMessage("Verificando Fornecedor...")
      .execute();
  }

  function js_cancelar(){
    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
  function js_pesquisapc21_numcgm(mostra){

    $("notificacao").hide();

    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
    }else{
       if(document.form1.pc21_numcgm.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.pc21_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
       }else{
         document.form1.z01_nome.value = '';
       }
    }
  }
  function js_mostracgm(erro,chave){
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.pc21_numcgm.focus();
      document.form1.pc21_numcgm.value = '';
    } else {
      verificaFornecedor();
    }
  }
  function js_mostracgm1(chave1,chave2){
    document.form1.pc21_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    func_nome.hide();
    verificaFornecedor();
  }
  function js_pesquisa(){
    js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?lCredenciamento&tipo=1&funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_liclicita.hide();
    <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
    ?>
  }
  document.getElementById("pc31_liclicitatipoempresa").style.width      = '5em';
  document.getElementById("pc31_liclicitatipoempresadescr").style.width = '20em';
</script>