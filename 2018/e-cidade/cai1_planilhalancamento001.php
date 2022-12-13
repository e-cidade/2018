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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libdicionario.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_placaixa_classe.php"));
require_once(modification("classes/db_placaixarec_classe.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$clplacaixa    = new cl_placaixa;
$clplacaixarec = new cl_placaixarec;
$clrotulo      = new rotulocampo;

$clplacaixa->rotulo->label();
$clrotulo->label("nomeinst");

$clplacaixarec->rotulo->label();
$clrotulo->label("k80_data");
$clrotulo->label("k13_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("k02_drecei");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_codigo");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("q02_inscr");
$clrotulo->label("j01_matric");

$db_opcao = 1;
$db_action = '';
$c58_sequencial = "000";
$c58_descr      = "NAO SE APLICA";
/*
 * definimos qual funcao sera usada para consultar a matricula.
* se o campo db_config.db21_usasisagua for true, usamos a func_aguabase.
* se for false, usamos a func_iptubase
*/
$oDaoDBConfig = db_utils::getDao("db_config");
$rsInstit     = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_file(db_getsession("DB_instit")));
$oInstit      = db_utils::fieldsMemory($rsInstit, 0);
$sFuncaoBusca = "js_pesquisaMatricula";
if ($oInstit->db21_usasisagua == "t") {
  $sFuncaoBusca = "js_pesquisa_agua";
}
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("datagrid.widget.js");
    db_app::load("strings.js");
    db_app::load("grid.style.css");
    db_app::load("estilos.css");
    db_app::load("classes/dbViewAvaliacoes.classe.js");
    db_app::load("widgets/windowAux.widget.js");
    db_app::load("widgets/dbmessageBoard.widget.js");
    db_app::load("dbcomboBox.widget.js");
  ?>
<style>

  #k81_origem {
    width: 95px;
  }
  .tamanho-primeira-col{
    width:150px;
  }

  .input-menor {
    width:100px;
  }

  .input-maior {
    width:400px;
  }

   #k81_codigo {
     width: 95px;
   }
   #k81_codigodescr {
     width: 77%;
   }

   #k81_obs {
     width:100%;
     height: 50px;
   }

</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>

<form name="form1" method="post" action="<?=$db_action?>">

<fieldset style="margin-top: 30px; width: 800px;">
  <legend><strong>Planilha de Arrecadação</strong></legend>
  <fieldset style='width:95%;'>
    <legend><strong>Dados da Planilha</strong></legend>

    <table  width="100%" border="0">
      <!-- Número da Planilha -->
      <tr>
        <td class='tamanho-primeira-col' nowrap><strong>Código da Planilha:</strong></td>
        <td>
          <?
          db_input('k80_codpla',10, $Ik80_codpla,true,'text',3,"")
          ?>
        </td>
        <td nowrap width="50px"><strong>Data:</strong></td>
        <td>
          <?php
          db_inputdata('k80_data',@$k80_data_dia,@$k80_data_mes,@$k80_data_ano,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Processo Administrativo:</b>
        </td>
        <td colspan="3">
          <?php
            db_input('k144_numeroprocesso',10,null, true,'text',$db_opcao , null,null,null,null, 15)
          ?>
        </td>
      </tr>

  </table>
  </fieldset>


<!-- Dados Receita -->
<fieldset style="width:95%; margin-top: 20px">
<legend><b>Receita</b></legend>
  <table border="0" width="100%">

     <!-- Receita -->
    <tr>
      <td class='tamanho-primeira-col' nowrap><?db_ancora($Lk81_receita,"js_pesquisaReceita(true)",$db_opcao);?></td>
      <td colspan='3'>
        <?
        db_input('codigo_receitaplanilha',10,null,true,'text',2," style='display:none;'");
        db_input('k81_receita',10,$Ik81_receita,true,'text',2," onchange='js_pesquisaReceita(false)'");
        db_input('c61_codigo' , 5,$Ic61_codigo,true,'text',3," onfocus=\"document.getElementById('k81_conta').focus()\" ",'recurso');
        db_input('k02_drecei' ,50,$Ik02_drecei,true,'text',3,"class='input-maior'");
        db_input('estrutural' ,20,null,true,'hidden',2,"");
        db_input('k02_tipo', 1, null, true, 'hidden');
        ?>
      </td>
    </tr>

    <!-- Código Conta -->
    <tr>
      <td class='tamanho-primeira-col' nowrap title="<?=@$Tk81_conta?>">
          <?
          db_ancora($Lk81_conta,"js_pesquisaConta(true);",$db_opcao);
          ?>
      </td>
      <td colspan='3'>
        <?
        db_input('k81_conta' ,10,$Ik81_conta,true,'text',2,"onchange='js_pesquisaConta(false);'");
        db_input('c61_codigo',5,$Ic61_codigo,true,'text',3);
        db_input('k13_descr' ,50,$Ik13_descr,true,'text',3,"class='input-maior'");
        ?>
      </td>
    </tr>

    <tr id="notificacao" style="display:none;">
      <td colspan='4' style="text-align: left; background-color: #fcf8e3; border: 1px solid #fcc888; padding: 10px">
        <!-- Mensagem de notificação -->
      </td>
    </tr>

    <!-- Origem -->
     <tr>
      <td class='tamanho-primeira-col' nowrap title="<?=@$Tk81_origem?>"><?=$Lk81_origem?></td>
      <td colspan='3'>
        <?
          db_select("k81_origem",getValoresPadroesCampo("k81_origem"),true,1,"class='input-menor' onChange='toogleOrigem()'");
        ?>
      </td>
    </tr>

    <!-- CGM -->
    <tr id='inputCgm' style=''>
      <td nowrap title="<?=@$Tk81_conta?>">
        <?db_ancora(@$Lk81_numcgm,"js_pesquisaCgm(true);",$db_opcao);?>
      </td>
      <td colspan='3'>
          <?
          db_input('k81_numcgm',10,$Ik81_numcgm,true,'text',2,"onchange='js_pesquisaCgm(false);'");
          db_input('z01_nome',63,$Iz01_nome,true,'text',3);
          ?>
      </td>
    </tr>

  <!-- Inscricao -->
  <tr id='inputInscr' style='display:none'>
    <td nowrap title="<?=@$Tq02_inscr?>"><?db_ancora(@$Lq02_inscr,"js_pesquisaInscricao(true);",$db_opcao);?></td>
    <td colspan='3'>
      <?
      db_input('q02_inscr',10,$Iq02_inscr,true,'text',2," onchange='js_pesquisaInscricao(false);'");
      db_input('nomeinscr',65,$Iz01_nome,true,'text',3, "class='input-maior'");
      ?>
    </td>
  </tr>

  <!-- Matricula -->
   <tr id='inputMatric' style='display:none'>
    <td class='tamanho-primeira-col' nowrap title="<?=@$Tj01_matric?>"><?db_ancora(@$Lj01_matric,"{$sFuncaoBusca}(true);",$db_opcao);?></td>
    <td colspan='3'>
      <?
      db_input('j01_matric',10,$Ij01_matric,true,'text',2," onchange='{$sFuncaoBusca}(false);'");
      db_input('nomematric',63,$Iz01_nome,true,'text',3);
      ?>
    </td>
  </tr>

  <!-- Recurso -->
  <tr>
    <td class='tamanho-primeira-col' nowrap title="<?=@$To15_codigo?>"><?echo $Lo15_codigo?></td>
    <td colspan='3'>
     <?
       $oDaoOrctiporec = db_utils::getDao("orctiporec");
       $sWhere         = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
       $sCampos        = "o15_codigo,o15_descr";
       $sSQLOrctiporec = $oDaoOrctiporec->sql_query_file(null,$sCampos,"o15_codigo",$sWhere);
       $rsOrctiporec   = $oDaoOrctiporec->sql_record($sSQLOrctiporec);
       db_selectrecord('k81_codigo', $rsOrctiporec, true, $db_opcao);
     ?>
    </td>
  </tr>

  <!-- Característica Peculiar -->
  <tr style=''>
    <td ><b><?db_ancora("C.Peculiar / C.Aplicação :","js_pesquisaPeculiar(true);",$db_opcao);?></b></td>
    <td colspan='3'>
        <?
        db_input('c58_sequencial',10,'',true,'text',2,"onchange='js_pesquisaPeculiar(false);'");
        db_input('c58_descr',63,'',true,'text',3);
        ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tk81_datareceb?>"><?echo $Lk81_datareceb?></td>
    <td><?
          if ($db_opcao==1) {

            $k81_datareceb_dia = date("d",db_getsession("DB_datausu"));
            $k81_datareceb_mes = date("m",db_getsession("DB_datausu"));
            $k81_datareceb_ano = date("Y",db_getsession("DB_datausu"));
          }
          db_inputdata('k81_datareceb',@$k81_datareceb_dia,@$k81_datareceb_mes,@$k81_datareceb_ano,true,'text',$db_opcao,"class='input-menor'")
          ?>
    </td>
    <td nowrap title="<?=@$Tk81_operbanco?>" width="100px"><?=@$Lk81_operbanco?></td>
    <td><?db_input('k81_operbanco',10,$Ik81_operbanco,true,'text',$db_opcao); ?></td>
  </tr>

  <tr>

    <td nowrap title="<?=@$Tk81_valor?>"><?=@$Lk81_valor?></td>
    <td ><?db_input('k81_valor',10,$Ik81_valor,true,'text',$db_opcao)?></td>

  </tr>


  <tr>
    <td colspan='4'>
      <fieldset>
        <legend><strong>Observação</strong></legend>
        <?db_textarea("k81_obs",1,40,$Ik81_obs,"true","text",$db_opcao);?>
      </fieldset>
    </td>
  </tr>
  </table>
</fieldset>
<br>

<input type='button'  value='Salvar Item'            id ='incluir'   onclick='js_addReceita();' />
<input type='button'  value='Pesquisar'              id ='btnPesquisar' onclick='js_pesquisaPlanilha(false);' />
<input type='button'  value='Importar'               id ='importar'  onclick='js_pesquisaPlanilha(true);' />
<input type='button'  value='Limpar Dados Receita'   id ='limpar'    onclick='js_limpaFormularioReceita();'/>
<div id='ctnReceitas' style="margin-top: 20px;"></div>

</fieldset>
<input type="button" value='Salvar Planilha'      id='salvar'  style="margin-top: 10px;" onclick='js_salvarPlanilha()'/>
<input type="button" value='Excluir Selecionados' id='excluir' style="margin-top: 10px;" onclick='js_excluiSelecionados();' />
<input type="button" value='Nova Planilha'        id='excluir' style="margin-top: 10px;" onclick='js_novaReceita()' />
</form>
</center>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

const CAMINHO_MENSAGEM = 'financeiro.caixa.cai1_planilhalancamento001.';

/*
 * funcao para verificar o grupo das receitas.
 * Nesta rotina nao permitiremos mais receitas do Grupo 11
 */
 function js_verificaReceita() {

  var sUrlRPC          = "cai4_devolucaoadiantamento004.RPC.php";
  var iReceita         = $F("k81_receita");
	var oParametros      = new Object();
 	var msgDiv           = "Verificando grupo receita selecionado \n Aguarde ...";

 	oParametros.exec     = 'verificaGrupoReceita';
 	oParametros.iReceita = iReceita;

  if (iReceita == '' || iReceita == null) {
    return false;
	}

 	js_divCarregando(msgDiv,'msgBox');

 	new Ajax.Request(sUrlRPC,
 	                 {method: "post",
 	                  parameters:'json='+Object.toJSON(oParametros),
 	                  onComplete: js_retornoVerificacaoReceita
 	                 });
 }

 function js_retornoVerificacaoReceita(oAjax) {

 	js_removeObj('msgBox');
 	var oRetorno = eval("(" + oAjax.responseText + ")");

 	if (oRetorno.iStatus == '2') {

 	  alert(oRetorno.sMessage.urlDecode());
 	  $('codigo_receitaplanilha').value = '';
 	  $('k81_receita').value            = '';
 	  $('c61_codigo').value             = '';
    $('k02_drecei').value             = '';
 	  $('estrutural').value             = '';
    $('recurso').value                = '';

 	  return false;
 	}
 }

/**
 * função para montar a grid de receitas:
 */
 var oGridReceitas;
 var aReceitas       = new Array();
 var iIndiceReceitas = 0;
 var iAlteracao      = null;
 var oGet            = js_urlToObject();
 var iPlanilha       = null;
 var dtPlanilha      = null;
 var lMenuAlteracao  = false;
 var lImportacao     = false;
 $('btnPesquisar').style.display = "none";
 if (oGet.lAlteracao == 'true') {

   $('btnPesquisar').style.display = "";
   lMenuAlteracao      = true;
 }

 function js_novaReceita(){

   document.form1.reset();
   toogleOrigem();
   oGridReceitas.clearAll(true);
   aReceitas       = new Array();
   iIndiceReceitas = 0;
   iAlteracao      = null;
 }

 function js_limpaFormularioReceita() {

   document.form1.reset();

   toogleOrigem();
   iAlteracao      = null;

    if (lMenuAlteracao) {

      $('k80_codpla').value = iPlanilha;
      $('k80_data').value   = dtPlanilha;
    }
  }

 function js_gridReceitas() {

   oGridReceitas = new DBGrid('ctnReceitas');
   oGridReceitas.nameInstance = 'oGridReceitas';
   oGridReceitas.setCheckbox(0);
   oGridReceitas.setCellWidth(new Array( '1%',
                                         '40%',
                                         '40%',
                                         '10%',
                                         '5%'));

   oGridReceitas.setCellAlign(new Array( 'center',
                                         'left',
                                         'left',
                                         'right',
                                         'center'));


   oGridReceitas.setHeader(new Array( 'Indice',
                                      'Dados da Conta',
                                      'Conta Tesouraria',
                                      'Valor',
                                      'Ação'));


   oGridReceitas.aHeaders[1].lDisplayed = false;
   oGridReceitas.setHeight(100);
   oGridReceitas.show($('ctnReceitas'));
   oGridReceitas.clearAll(true);
  }


 function toogleOrigem() {

   iTipo = $F("k81_origem");

   $('k81_numcgm').value = '';
   $('q02_inscr').value  = '';
   $('j01_matric').value = '';
   $('z01_nome').value   = '';
   $('nomematric').value = '';

   switch (iTipo) {

     case '1' :

       $('inputCgm').style.display    = '';
       $('inputMatric').style.display = 'none';
       $('inputInscr').style.display  = 'none';
       break;

    case '2' :

       $('inputInscr').style.display  = '';
       $('inputMatric').style.display = 'none';
       $('inputCgm').style.display    = 'none';
       break;

    case '3' :

       $('inputMatric').style.display = '';
       $('inputInscr').style.display  = 'none';
       $('inputCgm').style.display    = 'none';
       break;

   }
 }

 function js_pesquisak81_codpla(lMostra) {

   if (lMostra==true) {
     js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_placaixa','func_placaixa.php?funcao_js=parent.js_preenchePlacaixa|k80_codpla|k80_data','Pesquisa',true,'0');
   } else {

      if(document.form1.k81_codpla.value != '') {
         js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_placaixa','func_placaixa.php?pesquisa_chave='+document.form1.k81_codpla.value+'&funcao_js=parent.js_preenchePlacaixa','Pesquisa',false);
      } else {
        $('k80_data').value = '';
      }
   }
 }

 function js_preenchePlacaixa (chave,erro) {

   document.form1.k80_data.value = chave;
   if (erro==true) {

     document.form1.k81_codpla.focus();
     document.form1.k81_codpla.value = '';
   }
 }

 function js_preenchePlacaixa(chave1,chave2){
   document.form1.k81_codpla.value = chave1;
   document.form1.k80_data.value = chave2;
   db_iframe_placaixa.hide();
 }


 /**
  *   CONTA
  */
function js_pesquisaConta(lMostra) {

  if ($('recurso').value == '') {

    alert('Receita não selecionada.');
    return false;
  }

  if ($('estrutural').value.substr(0,3) == '211' || $('estrutural').value.substr(0,3) == '497' ) {
    recurso = '0';
  } else {

    recurso ='0';
  }

  var sFuncao   = 'funcao_js=parent.js_mostraSaltes|k13_conta|k13_descr|c61_codigo';
  var sPesquisa = 'func_saltesrecurso.php?recurso='+recurso+'&'+sFuncao+'&data_limite=<?=date("Y-m-d",db_getsession("DB_datausu"))?>'


  if (!lMostra){

    if ($F('k81_conta')== '') {
       $('k13_descr').value = '';
    } else {

      sFuncao   = 'funcao_js=parent.js_preencheSaltes';
      sPesquisa = 'func_saltesrecurso.php?pesquisa_chave='+$('k81_conta').value+'&'+sFuncao+'&data_limite=<?=date("Y-m-d",db_getsession("DB_datausu"))?>'
    }
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_saltes',sPesquisa+'&data_limite=<?=date("Y-m-d",db_getsession("DB_datausu"))?>','Pesquisa',lMostra);
}


function js_preencheSaltes(iCodigoConta,sDescricao,iCodigoRecurso,lErro) {

  $('k81_conta') .value = iCodigoConta;
  $('k13_descr') .value = sDescricao;
  $('c61_codigo').value = iCodigoRecurso;

  if (iAlteracao != null) {
    return;
  }

  if( $('estrutural').value.substr(0,3) == '211' ) {

    $('k81_codigo').value = $('c61_codigo').value;
    $('k81_codigo').onchange() ;
  } else {

    $('k81_codigo').value = iCodigoRecurso;
    $('k81_codigo').onchange() ;
  }

  if(lErro) {

    $('k81_conta')  .focus();
    $('k81_receita').focus();
    $('k81_conta')  .value = '';

  } else {
    js_getCgmConta(iCodigoConta);
  }

  js_mostrarNotificacaoConta();
}

function js_mostraSaltes (iCodigoConta,sDescricao,iCodigoRecurso) {

  $('k81_conta').value = iCodigoConta;
  $('k13_descr').value = sDescricao;
  $('c61_codigo').value = iCodigoRecurso;

  if ( $F('estrutural').substr(0,3) == '211' ) {

    $('k81_codigo').value = $('c61_codigo').value;
    $('k81_codigo').onchange() ;

  } else {

    $('k81_codigo').value = iCodigoRecurso;
    $('k81_codigo').onchange() ;
  }

  js_getCgmConta(iCodigoConta);
  db_iframe_saltes.hide();

  js_mostrarNotificacaoConta();
}

 function js_getCgmConta(iReduz) {
     sJson    = '{"exec":"getCgmConta","iCodReduz":'+iReduz+'}';
     url      = 'cai4_placaixaRPC.php';
     oAjax    = new Ajax.Request(
                            url,
                              {
                               method: 'post',
                               parameters: 'sJson='+sJson,
                               onComplete: js_retornoCgm
                              }
                             );
}


/**
   RECEITA
*/
 function js_pesquisaReceita(lMostra){

   var sPesquisa = 'func_tabrec_recurso.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei|recurso|k02_estorc|k02_tipo';

   if(!lMostra) {

    if ($F('k81_receita') == '') {

      $('k02_descr').value = '';
      return;
    }
    sPesquisa = 'func_tabrec_recurso.php?pesquisa_chave='+$F('k81_receita')+'&funcao_js=parent.js_mostratabrec';

   }
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec',sPesquisa,'Pesquisa',lMostra);
 }

 function js_mostratabrec(iReceita, sReceita, chave3, chave4, erro, chave5){

   $('k81_receita').value = iReceita;
   $('k02_drecei') .value = sReceita;
   $('recurso')    .value = chave3;
   $('estrutural') .value = chave4;
   $('k02_tipo')   .value = chave5;

   if(erro){
     $('k81_receita').focus();
     $('k81_receita').value = '';
   }
   js_verificaReceita();
   js_mostrarNotificacaoConta();
 }

 function js_mostratabrec1(iReceita, sReceita, chave3, chave4, chave5){

   $('k81_receita').value = iReceita;
   $('k02_drecei') .value = sReceita;
   $('recurso')    .value = chave3;
   $('estrutural') .value = chave4;
   $('k02_tipo')   .value = chave5;

   db_iframe_tabrec.hide();
   js_verificaReceita();
   js_mostrarNotificacaoConta();

 }

/**
 * Pesquisa CGM
 */
function js_pesquisaCgm(lMostra){

  if(lMostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostraCgm|z01_numcgm|z01_nome','Pesquisa',true);
  } else {

    if ($('k81_numcgm').value == '') {
      return;
    }
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+$('k81_numcgm').value+'&funcao_js=parent.js_preencheCgm','Pesquisa',false);
  }
}

function js_mostraCgm(iCodigoCgm, sDescricao){

  $('k81_numcgm').value = iCodigoCgm;
  $('z01_nome')  .value = sDescricao;
  db_iframe_cgm.hide();
}

function js_preencheCgm(lErro,sDescricao){

  $('z01_nome').value = sDescricao;

  if(lErro){
    $('k81_numcgm').focus();
    $('k81_numcgm').value = '';
    $('z01_nome')  .value = sDescricao;
  }
}


/**
 *  Pesquisa Inscrição
 */
function js_pesquisaInscricao(lMostra){

  var sFuncao = 'func_issbase.php?funcao_js=parent.js_mostraInscricao|q02_inscr|z01_nome';

  if(!lMostra){

    if ($F('q02_inscr') == '') {
      return;
    }
    sFuncao = 'func_issbase.php?pesquisa_chave='+$F('q02_inscr')+'&funcao_js=parent.js_preencheInscricao';
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscr',sFuncao,'Pesquisa',lMostra,'10');
}

function js_mostraInscricao(iCodigo,sDescricao) {

  $('q02_inscr').value = iCodigo;
  $('nomeinscr').value = sDescricao;
  db_iframe_inscr.hide();
}

function js_preencheInscricao(sDescricao,lErro) {

  $('nomeinscr').value = sDescricao;

  if (lErro) {
    $('q02_inscr').focus();
    $('nomeinscr').value = sDescricao;
  }
}

/**
 * Pesquisa Matricula
 */
function js_pesquisaMatricula(lMostra){
  if(lMostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric','func_iptubase.php?funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome','Pesquisa',true);
  }else{

    if ($F('j01_matric') == '') {
      return;
    }
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric','func_iptubase.php?pesquisa_chave='+$F('j01_matric')+'&funcao_js=parent.js_preencheMatricula','Pesquisa',false);
  }
}

function js_mostraMatricula(chave1, chave2){

  $('j01_matric').value = chave1;
  $('nomematric').value = chave2;
  db_iframe_matric.hide();
}

function js_preencheMatricula(chave,erro){
  $('nomematric').value = chave;
  if (erro == true) {
    $('j01_matric').focus();
    $('nomematric').value = chave;
  }
}

function js_pesquisa_agua(lMostra){

  if (lMostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric','func_aguabase.php?funcao_js=parent.js_mostraMatricula|x01_matric|z01_nome','Pesquisa',true,'10');
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matric','func_aguabase.php?pesquisa_chave='+$F('j01_matric')+'&funcao_js=parent.js_preencheMatricula','Pesquisa',false);
  }
}

/**
 *  Caracteristica Peculiar
 */
function js_pesquisaPeculiar (lMostra){

  var sPesquisa = 'func_concarpeculiar.php?funcao_js=parent.js_mostraPeculiar|c58_sequencial|c58_descr';
  if (!lMostra) {

    if (document.form1.c58_sequencial.value == '') {
      return;
    }

    sPesquisa  = 'func_concarpeculiar.php?pesquisa_chave='+document.form1.c58_sequencial.value;
    sPesquisa += '&funcao_js=parent.js_mostraPeculiar1';
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_peculiar', sPesquisa,'Pesquisa',lMostra,'10');
}

function js_mostraPeculiar (iCodigoCaracteristica, sDescricaoCaracteristica) {

  $('c58_sequencial').value = iCodigoCaracteristica;
  $('c58_descr').value      = sDescricaoCaracteristica;
  db_iframe_peculiar.hide();
}

function js_mostraPeculiar1 (sDescricao, lErro) {

  if (lErro) {

    $('c58_sequencial').value = "";
    $('c58_descr').value     = "";
    return;
  }
  $('c58_sequencial').focus();
  $('c58_descr').value = sDescricao;
}

 function js_getCgmConta(iReduz) {

   oParam           = new Object();
   oParam.exec      = "getCgmConta";
   oParam.iCodReduz =  iReduz;


   url      = 'cai4_placaixaRPC.php';
   oAjax    = new Ajax.Request(
                          url,
                            {
                             method: 'post',
                             parameters: 'sJson='+Object.toJSON(oParam),
                             onComplete: js_retornoCgm
                            }
                           );
}

function js_retornoCgm(oAjax) {

   oCgm = eval("("+oAjax.responseText+")");
   $('k81_numcgm').value = oCgm.z01_numcgm;
   $('z01_nome').value   = oCgm.z01_nome;
}

/**
 * Função para Adicionar uma Receita na Grid
 */
function js_addReceita () {

  if ($F('k81_receita') == '') {

    alert("Informe o código da receita.");
    $('k81_receita').focus();
    return false;
  }


  if ($F('k81_conta') == '') {

    alert("Informe o código da conta.");
    $('k81_conta').focus();
    return false;
  }

  if ($F('k81_numcgm') == '' &&  $F('j01_matric') == '' &&  $F('q02_inscr') =='' ) {

    alert("Informe a origem.");
    return false;
  }

  if ($F('c58_sequencial') == '') {

    alert("Informe a característica peculiar.");
    $('c58_sequencial').focus();
    return false;
  }

  if ($F('k81_datareceb') == '') {

    alert("Informe a data do recebimento.");
    $('k81_datareceb').focus();
    return false;
  }

  if ($F('k81_valor') == '') {

    alert("Informe o valor recebido.");
    $('k81_valor').focus();
    return false;
  }

  var oReceita             = new Object();
  //Receita
  oReceita.iReceitaPlanilha = $F('codigo_receitaplanilha');
  oReceita.k81_receita      = $F('k81_receita');
  oReceita.k02_drecei       = $F('k02_drecei');

  //Conta
  oReceita.k81_conta       = $F('k81_conta');
  oReceita.k13_descr       = $F('k13_descr');

  //Origem
  oReceita.k81_origem      = $F('k81_origem');
  oReceita.k81_numcgm      = $F('k81_numcgm');
  oReceita.q02_inscr       = $F('q02_inscr');
  oReceita.j01_matric      = $F('j01_matric');

  //Recurso
  oReceita.k81_codigo      = $F('k81_codigo');
  oReceita.k81_codigodescr = $F('k81_codigodescr');

  //Característica Peculiar
  oReceita.c58_sequencial  = $F('c58_sequencial');

  //Data Recebimento
  oReceita.k81_datareceb   = $F('k81_datareceb');

  //Dados Adicionais
  oReceita.k81_valor        = $F('k81_valor');
  oReceita.k81_obs          = $F('k81_obs');
  oReceita.recurso          = $F('recurso');
  oReceita.k81_operbanco    = $F('k81_operbanco');

  if ($F('k02_tipo') == "E") {
    oReceita.recurso = $F('k81_codigo');
  }

  if (iAlteracao == null) {

    oReceita.iIndice               = "a"+iIndiceReceitas;
    aReceitas["a"+iIndiceReceitas] = oReceita;
    iIndiceReceitas++;
  } else {
    aReceitas[iAlteracao] = oReceita;
    iAlteracao            = null;

  }

  js_renderizarGrid();
  alert("Receita inserida com sucesso!");
  //js_limpaFormularioReceita();
}

/**
 * Função para redesenhar a grid na tela
 */
function js_renderizarGrid() {

   oGridReceitas.clearAll(true);

  for (var iIndice in aReceitas) {

    var oReceita = aReceitas[iIndice];

    if (typeof(oReceita) == 'function') {
      continue;
    }
    var aRow = new Array();
    aRow[0]  = iIndice;
    aRow[1]  = oReceita.k81_conta + " - " + oReceita.k13_descr;
    aRow[2]  = oReceita.k81_receita + " - " + oReceita.k02_drecei;
    aRow[3]  = js_formatar(oReceita.k81_valor, "f");
    aRow[4]  = "<input type='button' onclick=js_mostraReceita(\'"+iIndice+"\') value='A'/>";
    oGridReceitas.addRow(aRow);
  }
  oGridReceitas.renderRows();
}

/**
 * Função que mostra na tela, para alteração, uma receita selecionada através da grid
 */
function js_mostraReceita(iIndice) {

  iAlteracao                 = iIndice;
  $('codigo_receitaplanilha').value  = aReceitas[iIndice].iReceitaPlanilha;
  $('k81_receita').value     = aReceitas[iIndice].k81_receita;
  $('k81_conta').value       = aReceitas[iIndice].k81_conta;

  $('k81_origem').value      = aReceitas[iIndice].k81_origem;
  $('k81_numcgm').value      = aReceitas[iIndice].k81_numcgm;
  $('q02_inscr').value       = aReceitas[iIndice].q02_inscr;
  $('j01_matric').value      = aReceitas[iIndice].j01_matric;

  $('c58_sequencial').value  = aReceitas[iIndice].c58_sequencial;
  $('k81_datareceb').value   = aReceitas[iIndice].k81_datareceb;
  $('k81_valor').value       = aReceitas[iIndice].k81_valor;
  $('k81_obs').value         = aReceitas[iIndice].k81_obs;
  $('recurso').value         = aReceitas[iIndice].recurso;
  $('k81_operbanco').value   = aReceitas[iIndice].k81_operbanco;

  js_pesquisaReceita(false);
  js_pesquisaCgm(false);
  js_pesquisaMatricula(false);
  js_pesquisaInscricao(false);
  js_pesquisaPeculiar(false);
  js_pesquisaConta(false);

  $('k81_codigo').value      = aReceitas[iIndice].k81_codigo;
  $('k81_codigodescr').value = aReceitas[iIndice].k81_codigodescr;
}

function js_salvarPlanilha() {

  if (lMenuAlteracao && !$F('k80_codpla')) {
    alert("Selecione uma planilha para alteração.");
    return false;
    }
  var aReceitasPlanilha = new Array();

  for (var iIndice in aReceitas) {

    var oReceitaTela = aReceitas[iIndice];

    if (typeof(oReceitaTela) == 'function') {
      continue;
    }

    var oReceita                   = new Object();
        oReceita.iReceitaPlanilha      = oReceitaTela.iReceitaPlanilha;
        oReceita.iOrigem               = oReceitaTela.k81_origem;
        oReceita.iCgm                  = oReceitaTela.k81_numcgm;
        oReceita.iInscricao            = oReceitaTela.q02_inscr;
        oReceita.iMatricula            = oReceitaTela.j01_matric;
        oReceita.iCaracteriscaPeculiar = oReceitaTela.c58_sequencial;
        oReceita.iContaTesouraria      = oReceitaTela.k81_conta;
        oReceita.sObservacao           = encodeURIComponent(tagString(oReceitaTela.k81_obs));
        oReceita.nValor                = oReceitaTela.k81_valor;
        oReceita.iRecurso              = oReceitaTela.recurso;
        oReceita.iReceita              = oReceitaTela.k81_receita;
        oReceita.dtRecebimento         = oReceitaTela.k81_datareceb;
        oReceita.sOperacaoBancaria     = oReceitaTela.k81_operbanco;

    aReceitasPlanilha.push(oReceita);
  }

  if (aReceitasPlanilha.length == 0) {

    alert("Não é possível incluir uma planilha zerada.");
    return false;
  }

  var sMensagemSalvar  = "Deseja salvar a planilha de arrecadação?\n\n";
  sMensagemSalvar     += "Este procedimento pode demandar algum tempo.";
  if (!confirm(sMensagemSalvar)) {
    return false;
  }

  js_divCarregando("Aguarde, salvando planilha de arrecadação...", "msgBox");

  var oParametro                 = new Object();
  oParametro.exec                = 'salvarPlanilha';
  oParametro.k144_numeroprocesso = encodeURIComponent(tagString($F('k144_numeroprocesso')));

  if (lMenuAlteracao) {
    oParametro.exec    = 'alterarPlanilha';
  }

  oParametro.iCodigoPlanilha = $F("k80_codpla");
  oParametro.aReceitas       = aReceitasPlanilha;
  sRPC                       = 'cai4_planilhaarrecadacao.RPC.php';

  var oAjax = new Ajax.Request(sRPC,
              {
               method: 'post',
               parameters: 'json='+Object.toJSON(oParametro),
               onComplete: js_completaSalvar
               });

}

function js_completaSalvar (oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    if (confirm(oRetorno.message.urlDecode())) {

      var sUrlOpen = "cai2_emiteplanilha002.php?codpla="+oRetorno.iCodigoPlanilha;
      var oJanelaRelatorio = window.open(sUrlOpen,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }
    //js_novaReceita();
  } else {
    alert(oRetorno.message.urlDecode());
  }
  //document.form1.reset();
}


/**
 * função para retornar registros selecinados na grid
 *
 */
function getSelecionados() {

  var aListaCheckbox     = oGridReceitas.getSelection("object");
  var aListaSelecionados = new Array();

  aListaCheckbox.each(
    function ( aRow ) {
      aListaSelecionados.push(aRow.aCells[1].getValue());
  });

  return aListaSelecionados;
}

function js_excluiSelecionados() {

  var aSelecionados = getSelecionados();
  aSelecionados.each(
    function (oSelecionado, iIndice) {
      delete aReceitas[oSelecionado];
    }
  );
  js_renderizarGrid();
}

/**
 * Funções para importar dados de uma segunda planilha
 **/
function js_pesquisaPlanilha(lImportarPlanilha) {

  var sAutenticadas = '';
  lImportacao       = lImportarPlanilha;
  if (lMenuAlteracao && !lImportarPlanilha) {
    sAutenticadas = '&lAutenticada=false';
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_placaixa','func_placaixa.php?lPlanilhasSemSlip=true&funcao_js=parent.js_getItensPlanilha|k80_codpla'+sAutenticadas,'Pesquisa',true);
}

function js_getItensPlanilha(iCodigoPlanilha) {

  if(!lMenuAlteracao && lImportacao && !confirm("Deseja importar dados da planilha "+iCodigoPlanilha+" ?")) {
    return false;
   }

  db_iframe_placaixa.hide();
  js_divCarregando("Aguarde, buscando dados da planilha...", "msgBox");

  var oParametro       = new Object();
  oParametro.exec      = 'importarPlanilha';
  oParametro.iPlanilha = iCodigoPlanilha;
  sRPC                 = 'cai4_planilhaarrecadacao.RPC.php';

  new Ajax.Request(sRPC,
                  {
                   method: 'post',
                   parameters: 'json='+Object.toJSON(oParametro),
                   onComplete: js_completaImportar
                   });
}

/**
 * Função que cria um objeto para cada receita da planilha importada, adicionando ao array (objeto)
 * que possui todas receitas que serão vinculadas a planilha atual
 */
function js_completaImportar (oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (lMenuAlteracao && !lImportacao) {

    aReceitas     = new Array();
    iPlanilha     = oRetorno.oPlanilha.iPlanilha;
    dtDataCriacao = oRetorno.oPlanilha.dtDataCriacao;

    $('k80_codpla').value = oRetorno.oPlanilha.iPlanilha;
    $('k80_data').value   = oRetorno.oPlanilha.dtDataCriacao;
  }

  var oInputProcesso = $('k144_numeroprocesso');
  oInputProcesso.value = "";
  if (oRetorno.oPlanilha.k144_numeroprocesso != null) {
    oInputProcesso.value = oRetorno.oPlanilha.k144_numeroprocesso.urlDecode();
  }

  //Adiciona as novas receitas importadas ao array de receitas
  oRetorno.oPlanilha.aReceitas.each(

    function ( oReceita ) {

      var oReceitaImportada               = new Object();
      if (!lImportacao) {
        oReceitaImportada.iReceitaPlanilha  = oReceita.iCodigo;
      }
      oReceitaImportada.k81_receita       = oReceita.iReceita;
      oReceitaImportada.k02_drecei        = oReceita.sDescricaoReceita.urlDecode();

      oReceitaImportada.k81_origem        = oReceita.iOrigem;
      oReceitaImportada.k81_numcgm        = oReceita.iCgm;
      oReceitaImportada.q02_inscr         = oReceita.iInscricao;
      oReceitaImportada.j01_matric        = oReceita.iMatricula;

      oReceitaImportada.c58_sequencial    = oReceita.iCaracteriscaPeculiar;
      oReceitaImportada.k81_conta         = oReceita.iContaTesouraria;
      oReceitaImportada.k13_descr         = oReceita.sDescricaoConta.urlDecode();

      oReceitaImportada.k81_datareceb     = oReceita.dtRecebimento;
      oReceitaImportada.k81_obs           = oReceita.sObservacao.urlDecode();
      oReceitaImportada.recurso           = oReceita.iRecurso;
      oReceitaImportada.k81_valor         = oReceita.nValor;
      oReceitaImportada.k81_operbanco     = oReceita.sOperacaoBancaria.urlDecode();

      //Adiciona índice na receita e adiciona no array de receitas (cria propriedade no objeto)
      oReceitaImportada.iIndice        = "a"+iIndiceReceitas;
      aReceitas["a"+iIndiceReceitas]   = oReceitaImportada;
      iIndiceReceitas++;
    }
  );
  js_renderizarGrid();

  if (lMenuAlteracao && !lImportacao) {

    $('k80_codpla').value = oRetorno.oPlanilha.iPlanilha;
    $('k80_data').value   = oRetorno.oPlanilha.dtDataCriacao;
  }
}

 js_gridReceitas();
 toogleOrigem();

 if (lMenuAlteracao) {
   js_pesquisaPlanilha(false);
 }

/**
 * Verifica se a conta da receita orcamentária é igual ao código da conta
 *
 * @returns {Boolean}
 */
function js_mostrarNotificacaoConta() {

  var iContaReceita = $('recurso').value;
  var iConta        = $('c61_codigo').value;

  if (!empty(iContaReceita) && !empty(iConta)) {

    var sTipoReceita  = $('k02_tipo').value;

    if((sTipoReceita == 'O') && (iConta !== iContaReceita)) {

      var sMensagem = _M(CAMINHO_MENSAGEM + 'contas_diferentes', {ContaReceita : iContaReceita, Conta : iConta});

      $('notificacao').childElements()[0].update("");
      $('notificacao').childElements()[0].insert("<b>" + sMensagem + "</b>");

      $('notificacao').setStyle({
        display : 'table-row'
      });

      return true;
    }
  }

  $('notificacao').setStyle({
    display : 'none'
  });

  return false;
}
</script>