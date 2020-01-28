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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("libs/db_utils.php"));

$mostramsg = "t";
$erro_msg  = "";
$origemori = "";
$db_opcao  = 1;

if(isset($origem) && $origem != ""){
  $origemori = $origem;
}

$clissbase           = new cl_issbase;
$clarrecad           = new cl_arrecad;
$clarrecant          = new cl_arrecant;
$clvistorias         = new cl_vistorias;
$cltipovistorias     = new cl_tipovistorias;
$clvistoriaandam     = new cl_vistoriaandam;
$clvistinscr         = new cl_vistinscr;
$clvistsanitario     = new cl_vistsanitario;
$clvistoriaslote     = new cl_vistoriaslote;
$clvistoriaslotevist = new cl_vistoriaslotevist;
$cltipoandam         = new cl_tipoandam;
$clfandam            = new cl_fandam;
$clcissqn            = new cl_cissqn;
$clparfiscal         = new cl_parfiscal;
$oDaoCadVenc         = new cl_cadvenc;

$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");

$cliframe_seleciona = new cl_iframe_seleciona;

$rsParfiscal = $clparfiscal->sql_record($clparfiscal->sql_query_file(db_getsession('DB_instit'),"y32_calcvistanosanteriores"));
$oParfiscal  = db_utils::fieldsMemory($rsParfiscal,0);

require_once(modification("forms/db_frmvistgeral.php"));

if (isset($processa) && $processa == 't') {

  $oDataExercicio = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));

  if($oDataExercicio->getAno() < $iExercicio){
    $oDataExercicio = new DBDate($iExercicio."-01-01");
  }

  if(!isset($iExercicio) && $iExercicio == ""){

    db_msgbox("Configure corretamente o ano de exercício do calculo de vistoria");
    echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='fis4_vistgeral001.php'</script>";
    exit;
  }

  /**
   * @todo remover isso
   */
  $where = " where 1=1";

  $rsTipovist  = db_query("select y77_diasgeral, y77_mesgeral from tipovistorias where y77_codtipo = $tipo");
  $numrowstipo = pg_numrows($rsTipovist);
  if ($numrowstipo == 0){

    db_msgbox("Verifique o cadastro e configure corretamente o tipo de vistoria selecionado");
    echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='fis4_vistgeral001.php'</script>";
    exit;
  }else{

    db_fieldsmemory($rsTipovist,0);
    if ($y77_diasgeral == 0 || $y77_mesgeral == 0) {

      db_msgbox("Configure corretamente o dia e o mes de vencimento do tipo de vistoria selecionado");
      echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='fis4_vistgeral001.php'</script>";
      exit;
    }
  }

  /**
   * Buscamos as inscricoes que serao vistoriadas
   */
  if(isset($codigos) && $codigos != ""){
    $where .= " and q82_classe in ($codigos) ";
  }

  if ($origemori == 1){

    $where .= " and q07_perman = 't'    ";
    $where .= " and q81_tipo   = 3      ";
    $where .= " and q02_dtbaix is null  ";
    $where .= " and (q07_datafi is null or q07_datafi >= '".$oDataExercicio->getDate()."') ";

    if ($q02_inscr != '') {
      $where .= " and q02_inscr = {$q02_inscr} ";
    }

    $sSql  = " select *  ";
    $sSql .= "   from (select distinct   ";
    $sSql .= "                 q02_inscr, ";
    $sSql .= "                 case       ";
    $sSql .= "                   when q02_dtcada is not null ";
    $sSql .= "                     then q02_dtcada ";
    $sSql .= "                   else q02_dtinic   ";
    $sSql .= "                 end as datacad      ";
    $sSql .= "            from issbase             ";
    $sSql .= "                 inner join tabativ   on q07_inscr  = issbase.q02_inscr   ";
    $sSql .= "                 inner join ativid    on q03_ativ   = tabativ.q07_ativ    ";
    $sSql .= "                 inner join clasativ  on q82_ativ   = tabativ.q07_ativ    ";
    $sSql .= "                 inner join ativtipo  on q07_ativ   = ativtipo.q80_ativ   ";
    $sSql .= "                 inner join tipcalc   on q81_codigo = ativtipo.q80_tipcal ";
    $sSql .= "                 $where ";
    $sSql .= ") as x ";
    $sSql .= "  where datacad < '".$oDataExercicio->getDate()."' ";


  }elseif($origemori == 2){

    $where .= " and y83_perman = 't'    ";
    $where .= " and q81_tipo = 6        ";
    $where .= " and y80_dtbaixa is null ";
    $where .= " and (y83_dtfim is null or y83_dtfim >= '".$oDataExercicio->getDate()."') ";

    if ($q02_inscr != "") {
      $where .= " and y18_inscr = {$q02_inscr} ";
    }

    $sSql  = " select *                                                                                        ";
    $sSql .= "   from ( select distinct                                                                        ";
    $sSql .= "                 y80_codsani,                                                                    ";
    $sSql .= "                 y18_inscr as q02_inscr,                                                         ";
    $sSql .= "                 y80_data as datacad                                                             ";
    $sSql .= "             from sanitario                                                                      ";
    $sSql .= "                 inner join saniatividade  on y83_codsani = y80_codsani                          ";
    $sSql .= "                 inner join ativid         on q03_ativ    = y83_ativ                             ";
    $sSql .= "                 inner join clasativ       on q82_ativ    = y83_ativ                             ";
    $sSql .= "                 inner join ativtipo       on y83_ativ    = ativtipo.q80_ativ                    ";
    $sSql .= "                 inner join tipcalc        on q81_codigo  = ativtipo.q80_tipcal                  ";
    $sSql .= "                 left  join sanitarioinscr on sanitarioinscr.y18_codsani = sanitario.y80_codsani ";
    $sSql .= "                 $where  ";
    $sSql .= "        ) as x           ";
    $sSql .= "    where  datacad < '".$oDataExercicio->getDate()."' ";

  }

  $rsResult = db_query($sSql) or die($sSql);
  $numrows  = pg_numrows($rsResult);

  if($numrows==0){

    db_msgbox("O filtro selecionado não retornou nenhum registro");
    echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='fis4_vistgeral001.php'</script>";
    exit;
  }

  $sqlerro=false;
  db_inicio_transacao();
  $numpre_par_rec = "";
  echo "<script>document.getElementById('filtro').style.visibility='hidden';</script>";

  //Inserimos na vistoriasLote
  $clvistoriaslote->y06_data          = $oDataExercicio->getDate();
  $clvistoriaslote->y06_hora          = db_hora();
  $clvistoriaslote->y06_instit        = db_getsession('DB_instit');
  $clvistoriaslote->y06_usuario       = db_getsession("DB_id_usuario");
  $clvistoriaslote->y06_codtipo       = $tipo;
  $clvistoriaslote->incluir($clvistoriaslote->y06_vistoriaslote);

  if($clvistoriaslote->erro_status=='0'){

    $erro_msg = $clvistoriaslote->erro_msg."--- Inclusão vistoriaslote";
    $sqlerro  = true;
  }

  $sSqlVistorias = $cltipovistorias->sql_query("","*",""," y77_codtipo = $tipo and y77_instit = ".db_getsession('DB_instit') );
  $rsTipovist    = $cltipovistorias->sql_record($sSqlVistorias);

  if($cltipovistorias->numrows == 0){

    db_msgbox("Tipo de vistoria nao encontrado e/ou pertence a outra instituição.");
    exit;
  }
  db_fieldsmemory($rsTipovist,0);

  $cont_nao_gera = 0;
  $iAnoInicial   = $oDataExercicio->getAno();
  $iAnoFinal     = $oDataExercicio->getAno();
  if ($q02_inscr != "") {

    $iAnoInicial = $anoini;
    $iAnoFinal   = $anofim;
  }

  for ($iAno = $iAnoInicial; $iAno <= $iAnoFinal; $iAno++ ) {

    // Se for calculo para varios anos buscar os dados da cissqn
    $rsParametros = $clcissqn->sql_record($clcissqn->sql_query_file($iAno));
    $oParametros  = db_utils::fieldsMemory($rsParametros,0);

    for ($i=0;$i < $numrows; $i++){

      db_fieldsmemory($rsResult,$i);

      db_atutermometro($i,$numrows,'termometro');

      if ($q02_inscr != "") {

        $sSqlAnoEmpresa = "select extract(year from q02_dtcada) as anoempresa from issbase where q02_inscr = {$q02_inscr}";
        $rsAnoEmpresa   = db_query($sSqlAnoEmpresa);
        $oAnoEmpresa    = db_utils::fieldsMemory($rsAnoEmpresa,0);

        if ((int)$oAnoEmpresa->anoempresa >= (int)$iAno) {
          continue;
        }
      }

      if($duplica=="f"){

        if ($origemori == 1){
          $result_anovist = $clvistinscr->sql_record($clvistinscr->sql_query(null,"*",null,"y71_inscr=$q02_inscr
                                                                                          and   y70_tipovist = $tipo
                                                                                          and (   ( extract(year from y70_data) =  extract(year from q02_dtcada) and extract(year from q02_dtcada) = ".(int)$iAno.")
                                                                                                 or extract(year from y70_data) = ".(int)$iAno." )"));
          if($clvistinscr->numrows>0 ){

            $cont_nao_gera ++;
            continue;
          }
        }else  if ($origemori == 2){

          $result_anovist = $clvistsanitario->sql_record($clvistsanitario->sql_query(null, "*", null, "y74_codsani = $y80_codsani and  y70_tipovist = $tipo and extract(year from y70_data) = ".(int)$iAno));
          if($clvistsanitario->numrows>0 ){
            $cont_nao_gera ++;
            continue;
          }
        }
      }

      if ($origemori != 1){

        $rsSanitario     = db_query(" select extract(year from y80_data) as anosani from sanitario where y80_codsani = $y80_codsani ");
        $intNumsanitario = pg_numrows($rsSanitario);
        if($intNumsanitario > 0){

          db_fieldsmemory($rsSanitario,0);
          if($anosani == $iExercicio){
            continue;
          }
        }
      }

      //Gravamos o primeiro andamento
      $clfandam->y39_data       = $oDataExercicio->getDate();
      $clfandam->y39_codtipo    = $y77_tipoandam;
      $clfandam->y39_obs        = "Inclusão geral de vistorias";
      $clfandam->y39_id_usuario = db_getsession("DB_id_usuario");
      $clfandam->y39_hora       = db_hora();
      $clfandam->incluir("");
      $erro=$clfandam->erro_msg;
      if($clfandam->erro_status==0){

        $erro_msg = $clfandam->erro_msg."--- Inclusão fandam";
        $sqlerro=true;
        break;
      }

      //Gravamos as vistorias
      $sDataVistoria = $oParametros->q04_dtbase;
      if($oDataExercicio->getAno() == $iAno){
        $sDataVistoria = $oDataExercicio->getDate();
      }

      $clvistorias->y70_data        = $sDataVistoria;
      $clvistorias->y70_hora        = db_hora();
      $clvistorias->y70_obs         = "Incluido na rotina de Inclusão geral de vistorias";
      $clvistorias->y70_contato     = "Sem contato cadastrado";
      $clvistorias->y70_tipovist    = $tipo;
      $clvistorias->y70_ultandam    = $clfandam->y39_codandam;
      $clvistorias->y70_instit      = db_getsession('DB_instit') ;
      $clvistorias->y70_id_usuario  = db_getsession("DB_id_usuario");
      $clvistorias->y70_coddepto    = db_getsession("DB_coddepto");
      $clvistorias->y70_numbloco    = "0";
      $clvistorias->y70_parcial     = 'false';
      $clvistorias->y70_ativo       = 'true';
      $clvistorias->incluir(null);

      if($clvistorias->erro_status=='0'){

        $erro_msg = $clvistorias->erro_msg."--- Inclusão vistorias";
        $sqlerro=true;
        break;
      }

      //Gravamos  na  vistinscr
      if ($origemori == 1) {

        $clvistinscr->y71_codvist = $clvistorias->y70_codvist;
        $clvistinscr->y71_inscr   = $q02_inscr;
        $clvistinscr->incluir($clvistorias->y70_codvist);
        if($clvistinscr->erro_status=='0'){

          $erro_msg = $clvistinscr->erro_msg."--- Inclusão vistinscr";
          $sqlerro=true;
          break;
        }

      } else {

        $clvistsanitario->y74_codvist = $clvistorias->y70_codvist;
        $clvistsanitario->y74_codsani = $y80_codsani;
        $clvistsanitario->incluir($clvistorias->y70_codvist);
        if($clvistsanitario->erro_status=='0'){

          $erro_msg = $clvistsanitario->erro_msg."--- Inclusão vistsanitario";
          $sqlerro=true;
          break;
        }

      }

      $clvistoriaandam->incluir($clvistorias->y70_codvist,$clfandam->y39_codandam);
      if($clvistoriaandam->erro_status==0){

        $erro_msg = $clvistoriaandam->erro_msg."--- Inclusão vistoriaandam";
        $sqlerro=true;
        break;
      }

      //Executamos a fc_vistorias do banco para calcular a vistoria
      $result      = $clvistorias->sql_calculo($clvistorias->y70_codvist);
      $numrowscalc = $clvistorias->numrows;
      if(pg_num_rows($result) == 0){

        $erro_msg = "Não foi possivel calcular a vistoria número - $clvistorias->y70_codvist.";
        $sqlerro  = true;
        break;
      }

      db_fieldsmemory($result,0);

      $iCodigoRetorno     = substr($fc_vistorias,0,2);
      $aRetornosIgnorados = array( "08", "09", "25" );

      if( !in_array($iCodigoRetorno, $aRetornosIgnorados) ){

        $mostramsg = 'f';
        $rel       = 't';
      }

      //Gravamos na  vistoriaslote
      $clvistoriaslotevist->y05_vistoriaslote = $clvistoriaslote->y06_vistoriaslote;
      $clvistoriaslotevist->y05_codvist       = $clvistorias->y70_codvist;
      $clvistoriaslotevist->y05_codmsg        = $iCodigoRetorno;
      $clvistoriaslotevist->incluir(null);

      if($clvistoriaslotevist->erro_status=='0'){

        $erro_msg = $clvistoriaslotevist->erro_msg."--- Inclusão vistoriaslotevist";
        $sqlerro=true;
        break;
      }
    }
  }
  if ($cont_nao_gera==$numrows){

    $sqlerro=true;
    $erro_msg = "Ja existem vistorias lançadas para todas as inscriçoes esse ano.";
  }

  db_fim_transacao($sqlerro);

  if( $erro_msg != "" && $mostramsg != 'f'){
    db_msgbox($erro_msg);
  }else{

    if ($mostramsg != 'f'){
      db_msgbox("Processamento concluido com sucesso");
    }
  }

  if($rel == 't'){

    db_msgbox("Existem inscrições desatualizadas, verifique o cadastro.");
    echo "<script>
      jan = window.open('fis4_relerrosvistgeral002.php?numlote=".$clvistoriaslote->y06_vistoriaslote."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
    </script>";
  }

  echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='fis4_vistgeral001.php'</script>";

}