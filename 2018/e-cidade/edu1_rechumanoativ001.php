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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);


$aTurnos    = array();
$aTurnos[1] = new stdClass();
$aTurnos[2] = new stdClass();
$aTurnos[3] = new stdClass();

$aTurnos[1]->sDescricao  = "Manhã" ;
$aTurnos[1]->lAtivo      = false;
$aTurnos[1]->sHoraInicio = "";
$aTurnos[1]->sHoraFim    = "";
$aTurnos[2]->sDescricao  = "Tarde" ;
$aTurnos[2]->lAtivo      = false;
$aTurnos[2]->sHoraInicio = "";
$aTurnos[2]->sHoraFim    = "";
$aTurnos[3]->sDescricao  = "Noite" ;
$aTurnos[3]->lAtivo      = false;
$aTurnos[3]->sHoraInicio = "";
$aTurnos[3]->sHoraFim    = "";


$oRotulo = new rotulocampo();
$oRotulo->label("ed22_i_codigo");
$oRotulo->label("ed75_i_codigo");
$oRotulo->label("ed22_i_atividade");
$oRotulo->label("ed01_c_descr");
$oRotulo->label("ed129_turno");
$oRotulo->label("ed22_i_atolegal");
$oRotulo->label("ed05_c_finalidade");
$oRotulo->label("ed75_i_escola");
$oRotulo->label("ed01_c_exigeato");

$ed75_i_escola = db_getsession('DB_coddepto');

define('MSG_RECHUMANOATIVIDADE', "educacao.escola.edu_rechumanoatividade.");

try {

  if( isset( $z01_nome ) && !empty( $z01_nome ) ) {
    $z01_nome = stripslashes( $z01_nome );
  }

  $sWhereRechumano   = "     ed75_i_rechumano = {$oGet->ed75_i_rechumano} ";
  $sWhereRechumano  .= " and ed75_i_escola    = {$ed75_i_escola} ";
  $sWhereRechumano  .= " and ed75_i_saidaescola is null ";
  $oDaoVinculoEscola = new cl_rechumanoescola();
  $sqlVinculoEscola  = $oDaoVinculoEscola->sql_query_file(null, "ed75_i_codigo", null, $sWhereRechumano);
  $rsVinculoEscola   = db_query($sqlVinculoEscola);

  if ( !$rsVinculoEscola ) {
    throw new Exception( _M(MSG_RECHUMANOATIVIDADE . "erro_verificar_vinculo_escola") );
  }

  $ed75_i_codigo = "";
  if ( pg_num_rows($rsVinculoEscola) > 0) {
    $ed75_i_codigo = db_utils::fieldsMemory($rsVinculoEscola, 0)->ed75_i_codigo;
  }

  $sCampos           = " ed123_turnoreferencia, ed123_horainicio, ed123_horafim ";
  $sWhere            = " ed123_escola = {$ed75_i_escola} ";
  $oDaoHorarioEscola = new cl_horarioescola();
  $sSqlHorarioEscola = $oDaoHorarioEscola->sql_query_file(null, $sCampos, null, $sWhere);
  $rsHorarioEscola   = db_query($sSqlHorarioEscola);

  if ( !$rsHorarioEscola || pg_num_rows($rsHorarioEscola) == 0 ) {

    throw new Exception( _M(MSG_RECHUMANOATIVIDADE . "sem_horarios_escola_cadastrado") );
  }

  $iLinhas = pg_num_rows($rsHorarioEscola);
  for ($i = 0; $i < $iLinhas; $i++) {

    $oDados = db_utils::fieldsMemory($rsHorarioEscola, $i);

    if ( array_key_exists($oDados->ed123_turnoreferencia, $aTurnos) ) {

      $aTurnos[$oDados->ed123_turnoreferencia]->sHoraInicio = $oDados->ed123_horainicio;
      $aTurnos[$oDados->ed123_turnoreferencia]->sHoraFim    = $oDados->ed123_horafim;
      $aTurnos[$oDados->ed123_turnoreferencia]->lAtivo      = true;
    }
  }

} catch (Exception $eError ) {

  /**
   * @todo verificar como tratar essas mensagens... para não alertar no carregar da primeira aba
   */
  db_msgbox( $eError->getMessage() );
}


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link rel="stylesheet" type="text/css" href="estilos.css" >
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css">
</head>
<body class="body-default">
  <?php
    require_once(modification("forms/db_frmrechumanoativ.php"));
  ?>
</body>
</html>
