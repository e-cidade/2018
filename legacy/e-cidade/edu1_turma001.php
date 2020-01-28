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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");

db_postmemory( $_POST );

$clturma               = new cl_turma;
$clbase                = new cl_base;
$clbasemps             = new cl_basemps;
$clbasediscglob        = new cl_basediscglob;
$clregencia            = new cl_regencia;
$clescola              = new cl_escola;
$clescolaestrutura     = new cl_escolaestrutura;
$clmatricula           = new cl_matricula;
$clturmaturnoadicional = new cl_turmaturnoadicional;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$oDaoTurmaCensoEtapa   = new cl_turmacensoetapa();

$db_opcao     = 1;
$db_opcao1    = 1;
$db_botao     = true;
$db_botao2    = true;
$codigoescola = db_getsession("DB_coddepto");

$aMapaTurnoReferente = array(1 => 'MANHÃ', 2 => 'TARDE', 3 => 'NOITE');
$lTemErro            = false;

if( isset( $incluir ) ) {

  db_inicio_transacao();

  if ($ed57_i_tipoturma == 2 && isset($ed57_censoprogramamaiseducacao)) {
  	$ed57_censoprogramamaiseducacao = '';
  }

  $clturma->ed57_censoprogramamaiseducacao = $ed57_censoprogramamaiseducacao;
  $clturma->ed57_c_descr                   = trim($ed57_c_descr);
  $clturma->incluir($ed57_i_codigo);

  $oCalendario  = new Calendario($ed57_i_calendario);
  $iAnoCenso    = DadosCenso::getUltimoAnoEtapaCenso();
  $iAnoConsulta = 2014;

  if ( $oCalendario->getAnoExecucao() > 2014 && $oCalendario->getAnoExecucao() == $iAnoCenso ) {
    $iAnoConsulta = $iAnoCenso;
  }

  if ( !empty($clturma->ed57_i_codigo) ) {
    
    $oDaoTurmaCensoEtapa->ed132_codigo     = null;
    $oDaoTurmaCensoEtapa->ed132_turma      = $clturma->ed57_i_codigo;
    $oDaoTurmaCensoEtapa->ed132_ano        = $iAnoConsulta;
    $oDaoTurmaCensoEtapa->ed132_censoetapa = $ed132_censoetapa;
    $oDaoTurmaCensoEtapa->incluir(null);
  }

  $aTurnoReferenteInformado = explode(", ", $ed336_turnoreferente);

  $oEnsino = EnsinoRepository::getEnsinoByCodigo($ed29_i_ensino);
  $oTurno  = new Turno($ed57_i_turno);

  for ($i = 0; $i < count($aTurnoReferenteInformado); $i++) {

    if ($iTurnoReferente = array_search($aTurnoReferenteInformado[$i], $aMapaTurnoReferente) ) {

      $iVagas = 0;

      if ( !$oEnsino->isInfantil() || !$oTurno->isIntegral() ) {
        $iVagas = $vagasTurma;
      } else {

        switch ($iTurnoReferente) {

          case 1:

            $iVagas = $vagasmanha;
            break;

          case 2:

            $iVagas = $vagastarde;
            break;

          case 3:

            $iVagas = $vagasnoite;
            break;
        }
      }

      $oDaoTurmaTurnoReferente                       = new cl_turmaturnoreferente;
      $oDaoTurmaTurnoReferente->ed336_codigo         = null;
      $oDaoTurmaTurnoReferente->ed336_turma          = $clturma->ed57_i_codigo;
      $oDaoTurmaTurnoReferente->ed336_turnoreferente = $iTurnoReferente;
      $oDaoTurmaTurnoReferente->ed336_vagas          = $iVagas;
      $oDaoTurmaTurnoReferente->incluir(null);

      if ($oDaoTurmaTurnoReferente->erro_status == 0) {
        db_msgBox("Não foi possível incluir vagas na turma");
        $lTemErro = true;
      }
    }
  }

  $sSqlBase = $clbase->sql_query_file( "", "ed31_c_contrfreq", "", " ed31_i_codigo = {$ed57_i_base}" );
  $result1  = $clbase->sql_record( $sSqlBase );
  db_fieldsmemory( $result1, 0 );

  if( trim( $ed31_c_contrfreq == "I" ) ) {
    $tipofreq = "I";
  } else {
    $tipofreq = "G";
  }

  $ultimo    = $clturma->ed57_i_codigo;
  $arr_etapa = explode( ",", $etapa_turma );

  for ($t = 0; $t < count($arr_etapa); $t++) {

    $arr_campos      = explode( "|", $arr_etapa[$t] );
    $codprocedaval   = "ed220_i_procedimento" . $arr_campos[2];
    $aprovautomatica = "ed220_c_aprovauto" . $arr_campos[2];

    $clturmaserieregimemat->ed220_i_procedimento   = $$codprocedaval;
    $clturmaserieregimemat->ed220_c_aprovauto      = $$aprovautomatica;
    $clturmaserieregimemat->ed220_i_serieregimemat = $arr_campos[0];
    $clturmaserieregimemat->ed220_c_historico      = $arr_campos[1];
    $clturmaserieregimemat->ed220_i_turma          = $ultimo;
    $clturmaserieregimemat->incluir(null);

    if( $clturmaserieregimemat->erro_status == 0 ) {

      $lTemErro = true;
      db_msgbox( $clturmaserieregimemat->erro_msg );
    }

    $iTurmaSerieRegimeMat = $clturmaserieregimemat->ed220_i_codigo;
    $sWhereSerieTurma     = "ed220_i_codigo = {$iTurmaSerieRegimeMat}";
    $sSqlSerieTurma       = $clturmaserieregimemat->sql_query( "", "ed223_i_serie", "", $sWhereSerieTurma ) ;
    $rsSerieTurma         = $clturmaserieregimemat->sql_record($sSqlSerieTurma);

    if( $rsSerieTurma && pg_num_rows( $rsSerieTurma ) > 0 && $ed57_i_tipoturma != 6 ) {

      $iSerieTurma = db_utils::fieldsMemory( $rsSerieTurma, 0 )->ed223_i_serie;

      $sql  = " SELECT * ";
      $sql .= "   FROM basemps ";
      $sql .= "  WHERE ed34_i_base = {$ed57_i_base} ";
      $sql .= "    AND ed34_i_serie = {$iSerieTurma} ";

      $query  = db_query($sql);
      $linhas = pg_num_rows($query);

      for ($x = 0; $x < $linhas; $x++) {

        db_fieldsmemory( $query, $x );
        $dia = date( "d", db_getsession("DB_datausu") );
        $mes = date( "m", db_getsession("DB_datausu") );
        $ano = date( "Y", db_getsession("DB_datausu") );

        $clregencia->ed59_i_turma      = $ultimo;
        $clregencia->ed59_i_disciplina = $ed34_i_disciplina;
        $clregencia->ed59_i_qtdperiodo = $ed34_i_qtdperiodo;
        $clregencia->ed59_i_ordenacao  = $ed34_i_ordenacao;
        $clregencia->ed59_c_condicao   = $ed34_c_condicao;
        $clregencia->ed59_c_freqglob   = "I";

        /**
         * Se controle de frequencia da base for global, verificamos qual disciplina foi informada como globalizada
         * e incluimos ela como FA, as demais serão inclusas como tratada
         */
        if( $tipofreq == "G" ) {

          if ($ed34_disiciplinaglobalizada == 't' )  {
            $clregencia->ed59_c_freqglob = "FA";
          } else {
            $clregencia->ed59_c_freqglob = "A";
          }
        }

        $clregencia->ed59_c_ultatualiz         = "SI";
        $clregencia->ed59_d_dataatualiz        = $ano."-".$mes."-".$dia;
        $clregencia->ed59_c_encerrada          = "N";
        $clregencia->ed59_i_serie              = $iSerieTurma;
        $clregencia->ed59_lancarhistorico      = $ed34_lancarhistorico      == 't' ? 'true' : 'false';
        $clregencia->ed59_caracterreprobatorio = $ed34_caracterreprobatorio == 't' ? 'true' : 'false';
        $clregencia->ed59_basecomum            = $ed34_basecomum            == 't' ? 'true' : 'false';
        $clregencia->ed59_procedimento         = $$codprocedaval;
        $clregencia->incluir(null);

        if ($clregencia->erro_status == 0) {

          $lTemErro = true;
          db_msgbox($clregencia->erro_msg);
        }
      }
    }
  }

  if ($ed246_i_turno != "") {

    $clturmaturnoadicional->ed246_i_turno = $ed246_i_turno;
    $clturmaturnoadicional->ed246_i_turma = $ultimo;
    $clturmaturnoadicional->incluir(null);
  }

  db_fim_transacao( $lTemErro );
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
  <script type="text/javascript" src="scripts/arrays.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Turma</b></legend>
    <?include("forms/db_frmturma.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed57_c_descr",true,1,"ed57_c_descr",true);
</script>
<?php
if( isset( $incluir ) ) {

  if( $clturma->erro_status == "0" ) {

    $clturma->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clturma->erro_campo != "" ) {

      echo "<script> document.form1.".$clturma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturma->erro_campo.".focus();</script>";
    }

    if( isset($ed57_i_base) && $ed57_i_base != "" ) {
      echo "<script>js_pesquisaed57_i_base(false);</script>";
    }
  } else {
    
    $clturma->erro(true,false);
    db_redireciona("edu1_turma002.php?chavepesquisa=$ultimo");
  }
}