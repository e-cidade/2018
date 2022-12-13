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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );

$clprontuarios    = new cl_prontuarios_ext();
$clprontproced    = new cl_prontproced_ext();
$clcgs_und        = new cl_cgs_und();
$clprontprofatend = new cl_prontprofatend_ext();
$oDaoCgm          = new cl_cgm();
$oDaoDbDepart     = new cl_db_depart();
$clprontprocedcid = new cl_prontprocedcid();

//Variaveis padrão
$db_opcao         = 1;
$db_botao         = true;
$db_botao1        = false;
$lFaaDigidada     = false;
$sd29_d_data_dia  = date( "d", db_getsession("DB_datausu") );
$sd29_d_data_mes  = date( "m", db_getsession("DB_datausu") );
$sd29_d_data_ano  = date( "Y", db_getsession("DB_datausu") );
$sd29_c_hora      = date("H:i");
$sd24_i_unidade   = db_getsession("DB_coddepto");
$oSauConfig       = loadConfig('sau_config');

//Verifica se o departamento é uma unidade
$sCampos = "sd02_i_codigo,descrdepto";
$sJoins  = " inner join unidades on unidades.sd02_i_codigo = db_depart.coddepto ";
$sWhere  = " coddepto = ".db_getsession("DB_coddepto");
$sSql    = $oDaoDbDepart->sql_query_file(null,$sCampos);
$sSql   .= $sJoins.' where '.$sWhere;
$rs      = $oDaoDbDepart->sql_record($sSql);
if ( $oDaoDbDepart->numrows == 0 ) {

  echo "<table width='100%'> ";
  echo "<tr> ";
  echo "<td align='center'> ";
  echo "  <font  face='arial'> ";
  echo "    <b> ";
  echo "      <p> ";
  echo "      Departamento ".db_getsession("DB_coddepto")." não cadastrado como UPS. ";
  echo "      </p> ";
  echo "      Selecione um departamento válido. ";
  echo "    </b> ";
  echo "  </font> ";
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  exit;
} else {

  $oUnidade      = db_utils::fieldsmemory($rs,0);
  $sd02_i_codigo = $oUnidade->sd02_i_codigo;
  $descrdepto    = $oUnidade->descrdepto;
}


//Pega Profissional de Atendimento
if( isset($chavepesquisaprontuario) ){

  $sCampos          = "cgs_und.*, m.*, rhcbo.*, especmedico.*, medicos.*, prontprofatend.*, sd24_c_digitada";
  $sWhere           = "s104_i_prontuario = ".$chavepesquisaprontuario;
  $sSql             = $clprontprofatend->sql_query_ext(null, $sCampos, "s104_i_codigo", $sWhere);
  $rsProntprofatend = $clprontprofatend->sql_record($sSql);

  if ($clprontprofatend->numrows > 0) {

   $oProntprofatend = db_utils::fieldsMemory($rsProntprofatend, 0);

    if ($oProntprofatend->sd24_c_digitada == 'S') {
      $lFaaDigidada = true;
    }

    if (!isset($sd29_i_profissional)) {

      if ($clprontprofatend->sql_prontproced($chavepesquisaprontuario, $oProntprofatend->s104_i_profissional)) {
        db_fieldsmemory($rsProntprofatend,0);
      }
    }
  }
}

if( isset( $opcao ) ) {

  $db_botao1  = true;
  $db_opcao   = $opcao=="alterar"?2:3;
  $result     = $clprontproced->sql_record($clprontproced->sql_query_nolote_ext($sd29_i_codigo));
  db_fieldsmemory($result, 0);
}

try {
  db_inicio_transacao();

  if( isset( $incluir ) ) {

    if ( $sd24_i_codigo == "" ) {     
      throw new Exception("Pesquise um paciente para incluir uma consulta médica.");            
    } else {

      $clprontprocedcid->erro_status = "1";


      $clprontproced->sd29_i_profissional = $sd29_i_profissional;
      $clprontproced->sd29_i_prontuario   = $chavepesquisaprontuario;
      $clprontproced->sd29_i_usuario      = DB_getsession("DB_id_usuario");
      $clprontproced->sd29_d_cadastro     = date("Y-m-d",db_getsession("DB_datausu"));
      $clprontproced->sd29_c_cadastro     = date("H:i",db_getsession("DB_datausu"));
      $clprontproced->sd29_sigilosa       = isset($sd29_sigilosa) ? 'true' : 'false';
      $clprontproced->incluir(null);
      if ($clprontproced->erro_status == 0) {
        throw new Exception($clprontproced->erro_msg);      
      }

      if ((int)$sd70_i_codigo > 0 && $clprontproced->erro_status != '0') {

        $clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
        $clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
        $clprontprocedcid->incluir(null);

        if ($clprontprocedcid->erro_status == 0) {
          throw new Exception($clprontprocedcid->erro_msg);        
        }
      }

      /**
       * Altera o vínculo do médico que esta atendendo a FAA.
       */
      $sWhere             = " s104_i_prontuario = {$chavepesquisaprontuario} ";
      $oDaoProntProfAtend = new cl_prontprofatend();
      $sSqlProntProfAtend = $oDaoProntProfAtend->sql_query_file(null, "s104_i_codigo", null, $sWhere);
      $rsProntProfAtend   = db_query($sSqlProntProfAtend);

      $iCodigoProntProfAtend = null;
      if ( $rsProntProfAtend && pg_num_rows($rsProntProfAtend) == 1 ) {
        $iCodigoProntProfAtend = db_utils::fieldsMemory( $rsProntProfAtend, 0 )->s104_i_codigo;
      }

      $oDaoProntProfAtend->s104_i_prontuario   = $chavepesquisaprontuario;
      $oDaoProntProfAtend->s104_i_profissional = $sd29_i_profissional;
      $oDaoProntProfAtend->s104_rhcbo          = $rh70_sequencial;

      if ( empty($iCodigoProntProfAtend) ) {

        $oDaoProntProfAtend->s104_i_codigo = null;
        $oDaoProntProfAtend->incluir(null);
      } else {

        $oDaoProntProfAtend->s104_i_codigo = $iCodigoProntProfAtend;
        $oDaoProntProfAtend->alterar($iCodigoProntProfAtend);
      }

      if ( $oDaoProntProfAtend->erro_status == 0 ) {
        throw new Exception($oDaoProntProfAtend->erro_msg);
      }

      db_fim_transacao();
    }

  } else if( isset( $alterar ) ) {

    $clcgs_und->alterar($z01_i_cgsund);
    $clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
    $clprontproced->sd29_sigilosa  = isset($sd29_sigilosa) ? 'true' : 'false';
    $clprontproced->alterar($sd29_i_codigo);
    $clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");

    if ((int)$sd70_i_codigo > 0) {

      $clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
      $clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
      $clprontprocedcid->incluir(null);

      if ($clprontprocedcid->numrows_incluir == 0) {
        throw new Exception($clprontprocedcid->erro_msg);
      }
    }

    db_fim_transacao();
  } else if( isset( $excluir ) ) {

    $clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");
    $clprontproced->excluir($sd29_i_codigo);
    db_fim_transacao();
  } else if( isset( $chavepesquisaprontuario ) && !empty( $chavepesquisaprontuario ) ) {

     $sd24_i_codigo = $chavepesquisaprontuario;
     if ($db_opcao == 1) {

       $sCampos  = "prontuarios.*,m.z01_nome as profissional_triagem, rhcbo.rh70_descr as cbo_triagem, sau_lotepront.*,";
       $sCampos .= "cgs_und.* ";
       $sSql     = $clprontuarios->sql_query_nolote_ext(null,
                                                        $sCampos,
                                                        null,
                                                        "sd24_i_codigo = $chavepesquisaprontuario");
       $result = $clprontuarios->sql_record($sSql);
       if ($clprontproced->numrows > 0) {
         db_fieldsmemory($result,0);
       }

       if ($clprontuarios->numrows > 0) {

        $oProntuario = db_utils::fieldsMemory($result, 0);
        if ($oProntuario->sd59_i_prontuario != "") {

           $sd24_i_codigo = null;
           throw new Exception("Impossível alteração de FAA incluída via Lote.");
        } else {

          $sSql  = "select * ";
          $sSql .= "  from sau_fechapront ";
          $sSql .= "       inner join prontproced on prontproced.sd29_i_codigo = sau_fechapront.sd98_i_prontproced ";
          $sSql .= "       inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario ";
          $sSql .= " where prontuarios.sd24_i_codigo = $chavepesquisaprontuario ";
          $res_pronproced = $clprontuarios->sql_record($sSql);

          if( $clprontuarios->numrows > 0  ){

            db_msgbox("Impossível alteração de FAA fechada.");
            $sd24_i_codigo = null;
          } else {

            db_fieldsmemory($result,0);
            if( isset($sd03_i_codigo) && (int)$sd03_i_codigo != 0 ){
              $profissional_branco = false;
            }

            $sCampos = "prontuarios.*, cgs_und.*, medicos.*, m.*, rhcbo.*, prontproced.sd29_i_profissional ";
            $sSql    = $clprontproced->sql_query_nolote_ext(null,
                                                            $sCampos,
                                                            null,
                                                            "sd29_i_prontuario = $chavepesquisaprontuario");

            $res_proced = $clprontproced->sql_record($sSql);
            if( $clprontproced->numrows > 0){
              db_fieldsmemory($res_proced,0);
            }
          }
        }
      }
    }
  }

} catch (Exception $oErro) {

 db_fim_transacao(true); 
 $clprontproced->erro_msg    = $oErro->getMessage();
 $clprontproced->erro_status = 0;
}

//Varifica se o profissional é um profissional da saude1
$sCampos  = " z01_nome, sd03_i_codigo, z01_numcgm  ";
$sCampos .= " ,rh70_sequencial, rh70_estrutural, rh70_descr, sd27_i_codigo ";

$sJoins  = " inner join db_usuacgm     on cgmlogin                     = z01_numcgm ";
$sJoins .= " inner join db_usuarios    on db_usuarios.id_usuario       = db_usuacgm.id_usuario ";
$sJoins .= " inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm ";
$sJoins .= " inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo ";
$sJoins .= " inner join unidades       on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade ";
$sJoins .= " left  join especmedico    on especmedico.sd27_i_undmed    = unidademedicos.sd04_i_codigo ";
$sJoins .= " left  join rhcbo          on rhcbo.rh70_sequencial        = especmedico.sd27_i_rhcbo ";

$sWhere  = " sd02_i_codigo = ".db_getsession("DB_coddepto");
$sWhere .= " and db_usuacgm.id_usuario = ".db_getsession("DB_id_usuario");

if ( isset($iRhCbo) && !empty($iRhCbo ) ) {
  $sWhere .= " and rh70_sequencial = {$iRhCbo} ";
}

$sSql               = $oDaoCgm->sql_query_file(null,$sCampos);
$sSql              .= $sJoins.' where '.$sWhere;
$rs                 = $oDaoCgm->sql_record($sSql);
$lDepartamentoSaude = false;

if ($oDaoCgm->numrows > 0) {

  $oProfissional = db_utils::fieldsmemory($rs, 0);
  $z01_nome      = $oProfissional->z01_nome;
  $sd03_i_codigo = $oProfissional->sd03_i_codigo;
  $z01_numcgm    = $oProfissional->z01_numcgm;

  $rh70_sequencial     = $oProfissional->rh70_sequencial;
  $rh70_estrutural     = $oProfissional->rh70_estrutural;
  $rh70_descr          = $oProfissional->rh70_descr     ;
  $sd29_i_profissional = $oProfissional->sd27_i_codigo  ;

  if ($oDaoCgm->numrows > 1) {

    $rh70_sequencial     = "";
    $rh70_estrutural     = "";
    $rh70_descr          = "";
    $sd29_i_profissional = "";
  }
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
    db_app::load("strings.js");
    db_app::load("webseller.js");
    db_app::load("widgets/windowAux.widget.js");
    db_app::load("widgets/dbmessageBoard.widget.js");
    db_app::load("classes/saude/ambulatorial/DBViewMotivosAlta.classe.js");
    db_app::load("classes/saude/ambulatorial/DBViewEncaminhamento.classe.js");
    db_app::load("estilos.css");
  ?>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script type="text/javascript" language="JavaScript" src="scripts/AjaxRequest.js"> </script>
  <script type="text/javascript" language="JavaScript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"> </script>
  <script>
    lHabilitaSigilosa = true;
  </script>
  <style type="text/css">
  .addElipisis{
    text-overflow: ellipsis;
  }
  </style>
</head>
<body class="body_default" >
  <div class="container">
      <?php
      include(modification("forms/db_frmfichaatendproced.php"));
      ?>
    <div style="width: 98%;">
      <fieldset style="width: 100%;">
        <legend>Procedimentos</legend>
        <div id="ctnGridProcedimentos"></div>
      </fieldset>
    </div>
  </div>
</body>
</html>
<script>

const MENSAGEM_CONSULTAMEDICA001 = "saude.ambulatorial.sau4_consultamedica001.";

js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);
document.form1.sd24_i_unidade.value = parent.iframe_a1.document.form1.sd24_i_unidade.value;

var oGet = js_urlToObject();

var oGridProcedimentos   = new DBGrid('gridProcedimentos');
var aHeaders   = ['Procedimento', 'Nome', 'Data', 'Hora', 'Opções'];
var aCellWidth = [ '15%', '55%', '10%', '10%', '10%'];
var aCellAlign = ['center', 'left', 'center', 'center', 'center' ];

oGridProcedimentos.nameInstance = 'oGridProcedimentos';
oGridProcedimentos.setCellWidth(aCellWidth);
oGridProcedimentos.setCellAlign(aCellAlign);
oGridProcedimentos.setHeader(aHeaders);
oGridProcedimentos.setHeight(150);
oGridProcedimentos.show($('ctnGridProcedimentos'));

buscaProcedimentos();

function buscaProcedimentos() {

  if ( !empty(oGet.chavepesquisaprontuario) ) {

    var sRPC = 'sau4_fichaatendimento.RPC.php';
    var oAjaxRequest = new AjaxRequest(sRPC, {sExecucao: 'getProcedimentos', iProntuario : oGet.chavepesquisaprontuario }, js_callBackProcedimentos);
    oAjaxRequest.setMessage( _M( MENSAGEM_CONSULTAMEDICA001 + 'buscando_procedimentos') );
    oAjaxRequest.execute();

  }
}

function js_callBackProcedimentos(oRetorno, lErro) {

  if (lErro) {

    alert( _M( MENSAGEM_CONSULTAMEDICA001 + "erro_buscar_procedimentos") );
    return false;
  }

  oGridProcedimentos.clearAll(true);

  oRetorno.aProcedimentos.each(function(oProcedimento) {

    var aLinha = [];
    aLinha.push( oProcedimento.sProcedimento );
    aLinha.push( oProcedimento.sNomeProcedimento.urlDecode() );
    aLinha.push( oProcedimento.sData );
    aLinha.push( oProcedimento.sHora );

    var oBtnAlterar     = document.createElement('input');
    oBtnAlterar.id      = "btnAlterar" + oProcedimento.iVinculoProcedimento;
    oBtnAlterar.type    = "button";
    oBtnAlterar.value   = "A";

    var oBtnExcluir     = document.createElement('input');
    oBtnExcluir.id      = "btnExcluir" + oProcedimento.iVinculoProcedimento;
    oBtnExcluir.type    = "button";
    oBtnExcluir.value   = "E";

    aLinha.push( oBtnAlterar.outerHTML + oBtnExcluir.outerHTML );

    oGridProcedimentos.addRow(aLinha);
  });

  oGridProcedimentos.renderRows();

  oRetorno.aProcedimentos.each(function(oProcedimento, iSequencial) {

    oGridProcedimentos.aRows[iSequencial].aCells[1].addClassName('addElipisis');

    var sHint = "";

    if ( oProcedimento.iCid != '') {
     sHint += "<strong>CID: </strong>" + oProcedimento.sCid + " - " + oProcedimento.sNomeCid.urlDecode() + "<br>";
    }

    if ( oProcedimento.sTratamento.urlDecode() != '' ) {
       sHint += "<strong>Prescrição: </strong>" + oProcedimento.sTratamento.urlDecode();
    }

    if( oProcedimento.lPermiteManutencao ) {
      $("btnAlterar" + oProcedimento.iVinculoProcedimento).onclick = function( ) {
        jsAlterar(oProcedimento);
      }

      $("btnExcluir" + oProcedimento.iVinculoProcedimento).onclick = function( ) {
        jsExcluir(oProcedimento);
      }
    } else {

      $("btnAlterar" + oProcedimento.iVinculoProcedimento).disabled = "true";
      $("btnExcluir" + oProcedimento.iVinculoProcedimento).disabled = "true";
    }

    if ( sHint != '' ) {
      oParametros = {iWidth:'400', oPosition : {sVertical : 'B', sHorizontal : 'R'}};
      oGridProcedimentos.setHint(iSequencial, 1, sHint,  oParametros);
    }

  });
}

function jsAlterar(oProcedimento) {

  $('sd29_i_codigo').value       = oProcedimento.iVinculoProcedimento;
  $('sd29_i_procedimento').value = oProcedimento.iProcedimento;
  $('sd63_c_procedimento').value = oProcedimento.sProcedimento;
  $('sd63_c_nome').value         = oProcedimento.sNomeProcedimento.urlDecode();
  $('sd70_i_codigo').value       = oProcedimento.iCid;
  $('sd70_c_cid').value          = oProcedimento.sCid.urlDecode();
  $('sd70_c_nome').value         = oProcedimento.sNomeCid.urlDecode();
  $('sd29_t_tratamento').value   = oProcedimento.sTratamento.urlDecode();
  $('sd29_sigilosa').checked     = oProcedimento.lSigilosa;
  $('rh70_sequencial').value     = oProcedimento.iCodigoEspecialidade;
  $('rh70_estrutural').value     = oProcedimento.iEstruturalEspecialidade;
  $('rh70_descr').value          = oProcedimento.sEspecialidade.urlDecode();
  $('sd29_i_profissional').value = oProcedimento.iProfissionalEspecialidade;


  $('db_opcao').value            = "Alterar";
  $('db_opcao').name             = "alterar";
  $("cancelar").disabled         = false;
}

function jsExcluir(oProcedimento) {

  if( oProcedimento.iVinculoProcedimento == '' ) {
    return false;
  }

  var oParametros = { "sProcedimento" : oProcedimento.sProcedimento, "sNomeProcedimento" : oProcedimento.sNomeProcedimento.urlDecode() };

  if( !confirm( _M( MENSAGEM_CONSULTAMEDICA001 + 'confirma_exclusao_procedimento', oParametros ) ) ) {
    return false;
  }

  var sRPC = 'sau4_fichaatendimento.RPC.php';
  var oAjaxRequest = new AjaxRequest( sRPC,
                                      { sExecucao           : 'excluirProcedimento',
                                        iProntuario         : oGet.chavepesquisaprontuario,
                                        iCodigoProcedimento : oProcedimento.iVinculoProcedimento
                                      },
                                      js_callBackExcluir
                                    );
  oAjaxRequest.setMessage( _M( MENSAGEM_CONSULTAMEDICA001 + 'excluindo_procedimento')  );
  oAjaxRequest.execute();
}

function js_callBackExcluir( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );

  if ( lErro ) {
    return false;
  }

  buscaProcedimentos();
}

</script>
<?php
if ($lFaaDigidada == true && isset($lAlertDiditada)) {

  echo "<script type=\"text/javascript\">";
  echo "  alert('FA já digitada. Manutenção não permitida.');";
  echo "</script>";
}

if(isset($incluir) || isset($alterar)){

  if ($clprontproced->erro_status=="0") {

    $clprontproced->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clprontproced->erro_campo!=""){

      echo "<script> document.form1.".$clprontproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontproced->erro_campo.".focus();</script>";
    }
  } else {

    echo "<script>";
    $sParam = "chavepesquisaprontuario=$chavepesquisaprontuario&z01_i_cgsund=$z01_i_cgsund";
    echo"location.href='sau4_consultamedica001.php?$sParam'";
    echo"</script>";
  }
} else if (isset($excluir)) {

  $clprontproced->erro(true, false);
  echo"<script>";
  echo"location.href='sau4_consultamedica001.php?chavepesquisaprontuario=$chavepesquisaprontuario'";
  echo"</script>";
}