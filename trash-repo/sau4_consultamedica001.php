<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

//Estanciando classes
$clprontuarios    = db_utils::getDao("prontuarios_ext");
$clprontproced    = db_utils::getDao("prontproced_ext");
$clcgs_und        = db_utils::getDao("cgs_und");
$clprontprofatend = db_utils::getDao("prontprofatend_ext");
$oDaoCgm          = db_utils::getDao("cgm");
$oDaoDbDepart     = db_utils::getDao("db_depart");
$clprontprocedcid = db_utils::getDao("prontprocedcid");

//Variaveis padrão
$db_opcao         = 1;
$db_botao         = true;
$db_botao1        = false;
$lFaaDigidada     = false;
$sd29_d_data_dia  = date("d",db_getsession("DB_datausu"));
$sd29_d_data_mes  = date("m",db_getsession("DB_datausu"));
$sd29_d_data_ano  = date("Y",db_getsession("DB_datausu"));
$sd29_c_hora      = date("H").":".$sd29_d_data_mes;
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

//Varifica se o profissional é um profissional da saude
$sCampos    = "z01_nome,sd03_i_codigo,z01_numcgm";
$sJoins     = "inner join db_usuacgm     on cgmlogin                     = z01_numcgm ";
$sJoins    .= "inner join db_usuarios    on db_usuarios.id_usuario       = db_usuacgm.id_usuario ";
$sJoins    .= "inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm ";
$sJoins    .= "inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo ";
$sJoins    .= "inner join unidades       on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade ";
$sWhere     = " sd02_i_codigo = ".db_getsession("DB_coddepto");
$sWhere    .= " and db_usuacgm.id_usuario = ".db_getsession("DB_id_usuario");
$sSql       = $oDaoCgm->sql_query_file(null,$sCampos);
$sSql      .= $sJoins.' where '.$sWhere;
$rs         = $oDaoCgm->sql_record($sSql);
$lProfSaude = false;
if ($oDaoCgm->numrows > 0) {

  $oProfissional = db_utils::fieldsmemory($rs, 0);
  $z01_nome      = $oProfissional->z01_nome;
  $sd03_i_codigo = $oProfissional->sd03_i_codigo;
  $z01_numcgm    = $oProfissional->z01_numcgm;
  $lProfSaude    = true;

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
        if (!isset($incluir) && !isset($alterar) && !isset($excluir)) {
          $sd29_i_profissional = $oProntprofatend->s104_i_profissional; 
        }

      }

    }

  }

}

if (isset($opcao)) {

  $lProfSaude = true;
  $db_botao1  = true;
  $db_opcao   = $opcao=="alterar"?2:3;
  $result     = $clprontproced->sql_record($clprontproced->sql_query_nolote_ext($sd29_i_codigo)); 
  db_fieldsmemory($result, 0);

}

if (isset($incluir)) {

  if ($sd24_i_codigo == "") {
    db_msgbox("Pesquise um profissional");
  } else {

    $clprontprocedcid->erro_status = "1";
    db_inicio_transacao();
    $clprontproced->sd29_i_prontuario = $chavepesquisaprontuario;
    $clprontproced->sd29_i_usuario    = DB_getsession("DB_id_usuario");
    $clprontproced->sd29_d_cadastro   = date("Y-m-d",db_getsession("DB_datausu"));
    $clprontproced->sd29_c_cadastro   = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
    $clprontproced->incluir(null);
    if ((int)$sd70_i_codigo > 0 && $clprontproced->erro_status != '0') {

      $clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
      $clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
      $clprontprocedcid->incluir(null); 
      if ($clprontprocedcid->numrows_incluir == 0) {

        $clprontproced->erro_msg    =  $clprontprocedcid->erro_msg;
        $clprontproced->erro_status =  $clprontprocedcid->erro_status;

      }

    }
    if ($clprontproced->erro_status != 0) {

      $clprontuarios->sd24_i_codigo   = $chavepesquisaprontuario;
      $clprontuarios->sd24_c_digitada = 'S';
      $clprontuarios->alterar($chavepesquisaprontuario);
      if ($clprontuarios->erro_status == "0") {

        $clprontproced->erro_msg    = $clprontuarios->erro_msg;
        $clprontproced->erro_status = $clprontuarios->erro_status;

      }
 
    }
    db_fim_transacao();

  }
} else if (isset($alterar)) {

  db_inicio_transacao();
  $clcgs_und->alterar($z01_i_cgsund);
  $clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
  $clprontproced->alterar($sd29_i_codigo);
  $clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");
  if ((int)$sd70_i_codigo > 0) {

    $clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
    $clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
    $clprontprocedcid->incluir(null); 
    if ($clprontprocedcid->numrows_incluir == 0) {

      $clprontproced->erro_msg    = $clprontprocedcid->erro_msg;
      $clprontproced->erro_status =  $clprontprocedcid->erro_status;

    }

  }
  db_fim_transacao();

} else if(isset($excluir)) {

  db_inicio_transacao();
  $clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");
  $clprontproced->excluir($sd29_i_codigo);
  db_fim_transacao();

} else if (isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)) {

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

           db_msgbox("Impossível alteração de FAA incluída via Lote.");
           $sd24_i_codigo = null;

      }else{

        $sSql = "select * 
                 from sau_fechapront
                  inner join prontproced on prontproced.sd29_i_codigo = sau_fechapront.sd98_i_prontproced
                  inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
                 where prontuarios.sd24_i_codigo = $chavepesquisaprontuario ";
        $res_pronproced = $clprontuarios->sql_record($sSql);
        if( $clprontuarios->numrows > 0  ){

          db_msgbox("Impossível alteração de FAA fechada.");
          $sd24_i_codigo = null;

        }else{

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

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  try{

    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("strings.js");
    db_app::load("webseller.js");
    db_app::load("estilos.css");

  }catch (Exception $eException){
    die( $eException->getMessage() );
  }
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmfichaatendproced.php");
        ?>
    </center>
    </td>
  </tr>
 <tr>
</table>
<center>
<table>
<tr>
  <td valign="top"><br>
  <?
   $chavepri= array("sd29_i_codigo"=>@$sd29_i_codigo );

   $cliframe_alterar_excluir->chavepri=$chavepri;
   if (isset($chavepesquisaprontuario)) {

     $sWhere = "sd29_i_prontuario = $chavepesquisaprontuario";
     $cliframe_alterar_excluir->sql = $clprontproced->sql_query_nolote_ext("","*","sd29_i_codigo",$sWhere);

   }
   $cliframe_alterar_excluir->campos  ="sd29_i_codigo,sd29_d_data,sd29_c_hora,sd29_i_procedimento,sd63_c_nome";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->alignlegenda="left";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
<table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);
document.form1.sd24_i_unidade.value = parent.iframe_a1.document.form1.sd24_i_unidade.value;
</script>
<?
if (!isset($rh70_estrutural) && $lProfSaude == true) {

  echo "<script type=\"text/javascript\">";
  echo "  js_pesquisasd04_i_cbo(true);";
  echo "</script>";

}
if ($lProfSaude == false) {

  echo "<script type=\"text/javascript\">";
  echo "  alert('Usuário logado não é um profissional da saúde ou não está vinculado ao departamento.');";
  echo "</script>";

}
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

?>