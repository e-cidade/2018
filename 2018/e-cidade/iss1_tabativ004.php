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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/logAtividade.model.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clIssAlvara      = new cl_issalvara;
$clIssMovAlvara   = new cl_issmovalvara;
$clativprinc      = new cl_ativprinc;
$cltabativ        = new cl_tabativ;
$clclasativ       = new cl_clasativ;
$cltabativtipcalc = new cl_tabativtipcalc;
$clissbase        = new cl_issbase;

$clparissqn       = new cl_parissqn;
$clsaniatividade  = new cl_saniatividade;
$clsanitario      = new cl_sanitario;
$clissporte       = new cl_issporte;
$clsanitarioinscr = new cl_sanitarioinscr;
$clativid         = new cl_ativid;
$cllogatividade   = new logatividade;
$lAtivPrinc       = "f";
$lAlvaraPerm      = "";
//************************************************************************************************//

// func para retornar os dias entre datas
function quantDias($data1, $data2) {
  $aVet1=explode("/",$data1);
  $aVet2=explode("/",$data2);
  return round((mktime(0,0,0,$aVet2[1],$aVet2[0],$aVet2[2])-
                mktime(0,0,0,$aVet1[1],$aVet1[0],$aVet1[2])) / (24 * 60 * 60), 0);
}

//verifica o parametro na tabela parissqn para gerar sanitario automaticamente apartir do ISSQN
$rsParissqn      = $clparissqn->sql_record($clparissqn->sql_query(null, "q60_integrasani", null, ""));
$numrowsParissqn = $clparissqn->numrows;
if ($numrowsParissqn > 0) {
  db_fieldsmemory($rsParissqn,0);
} else {
  db_msgbox("Configure os parametro do modulo issqn");
}

$db_botao = true;
$sqlerro  = false;

try {

  if (!empty($incluir) || !empty($alterar)) {

    if (empty($q07_ativ)) {
      throw new Exception("Atividade não informada.");
    }

    $aWhereValidacaoData = array(
        "q07_inscr = {$q07_inscr}",
        "q07_databx is null",
        "q07_ativ = {$q07_ativ}"
      );

    if ($q07_perman == 'f') {
      $oDataInicial = new DBDate("{$q07_datain_ano}-{$q07_datain_mes}-{$q07_datain_dia}");

      $aWhereValidacaoData[] = "q07_datafi >= '{$oDataInicial->getDate()}'::date";
    } else {
      $aWhereValidacaoData[] = "q07_perman is true";
    }

    $sSqlValidacaoData = $cltabativ->sql_query_file( null,
                                                     null,
                                                     '*',
                                                     null,
                                                     implode(" and ", $aWhereValidacaoData) );

    $rsValidacaoData = $cltabativ->sql_record($sSqlValidacaoData);

    if ($cltabativ->numrows > 0) {

     if ((!empty($alterar) && db_utils::fieldsMemory($rsValidacaoData, 0)->q07_seq != $q07_seq) || !empty($incluir)) {

       if ($q07_perman == 'f') {

         $oDataFinalAtividade = new DBDate( db_utils::fieldsMemory($rsValidacaoData, 0)->q07_datafi );
         throw new Exception("A data de início da atividade deve ser maior que {$oDataFinalAtividade->getDate(DBDate::DATA_PTBR)}.");
       }

       throw new Exception("A Atividade {$q07_ativ} já foi cadastrada como uma atividade permanente.");
     }
   }

  }

} catch (Exception $e) {

    $erromsg = $e->getMessage();
    $sqlerro = true;
}

if (isset($incluir) && !$sqlerro) {

  $lAlvaraPerm = $q07_perman;

  db_inicio_transacao();

  $tipoperman = 'false';
  if( $q07_perman == 'f' ){
    $tipoperman = 'true';
  }

  /**
   * Verifica se esta tentando incluir uma atividade diferente do tipo de alvara permanente/provisorio
   */
  $rsPesquisaAtivTipo = $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,'','*',null," q07_perman is {$tipoperman} and q07_inscr = $q07_inscr and q07_databx is null"));
  if ( $cltabativ->numrows > 0 ) {

    $erromsg = "Não é permitido inclusão de atividade permanente e provisório para o mesmo alvará.";
    $sqlerro = true;
  }

  $result01 = $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,'','max(q07_seq)+1 as seq'));
  db_fieldsmemory($result01,0);
  $q07_seq = $seq == ""?"1":$seq;
  $cltabativ->q07_inscr  = $q07_inscr;
  $cltabativ->q07_seq    = $q07_seq;
  $cltabativ->q07_ativ   = $q07_ativ;
  $cltabativ->q07_quant  = $q07_quant;
  $cltabativ->q07_perman = $q07_perman;
  $cltabativ->q07_datain = "$q07_datain_ano-$q07_datain_mes-$q07_datain_dia";
  $cltabativ->q07_horaini= $q07_horaini;
  $cltabativ->q07_horafim= $q07_horafim;
  if (@$q07_datafi_ano!="" && @$q07_datafi_mes!="" && @$q07_datafi_dia!="") {
    $cltabativ->q07_datafi = "$q07_datafi_ano-$q07_datafi_mes-$q07_datafi_dia";
  } else {
    $cltabativ->q07_datafi = null;
  }

  $cltabativ->q07_tipbx = "0";

  $cltabativ->incluir($q07_inscr,$q07_seq);
  if ($cltabativ->erro_status==0) {
    $sqlerro = true;
    $erromsg = "Inclusão tabativ : ".$cltabativ->erro_msg;
  }
  if ($sqlerro==false && $q11_tipcalc!="") {
    $cltabativtipcalc->q11_inscr   = $q07_inscr;
    $cltabativtipcalc->q11_seq     = $q07_seq;
    $cltabativtipcalc->q11_tipcalc = $q11_tipcalc;
    $cltabativtipcalc->incluir($q07_inscr,$q07_seq);
    if ($cltabativtipcalc->erro_status==0) {
      $erromsg = "Inclusão tabativtipcalc : ".$cltabativtipcalc->erro_msg;
      $sqlerro = true;
    }
  }
  if ($princ=='t') {
    $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr));
    if ($clativprinc->numrows>0) {
      $clativprinc->q88_inscr = $q07_inscr;
      $clativprinc->excluir($q07_inscr);
      if ($clativprinc->erro_status==0) {
        $erromsg = "Exclusão ativprinc : ".$clativprinc->erro_msg;
        $sqlerro = true;
      }
    }
    $clativprinc->q88_inscr = $q07_inscr;
    $clativprinc->q88_seq   = $q07_seq;
    $clativprinc->incluir($q07_inscr);
    if ($clativprinc->erro_status==0) {
      $erromsg = "Inclusão ativprinc : ".$clativprinc->erro_msg;
      $sqlerro = true;
    } else {

    	$lAtivPrinc = 't';
    }
  } else {
    $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr));
    if (!$clativprinc->numrows>0) {
      $clativprinc->q88_inscr = $q07_inscr;
      $clativprinc->q88_seq   = $q07_seq;
      $clativprinc->incluir($q07_inscr);
      if ($clativprinc->erro_status==0) {
        $erromsg = "Inclusão ativprint erro 2 : ".$clativprinc->erro_msg;
        $sqlerro = true;
      }
    }
  }

//**********************************************************************************************************************************

  if($sqlerro==false){


    if (isset($q60_integrasani) && $q60_integrasani == 1) {

      // verifica se porte, eh do tipo juridica
      $sql = " select q40_fisica from issbaseporte inner join issporte on q45_codporte = q40_codporte where q40_fisica is false and q45_inscr = $q07_inscr";
      $rsPorte = $clissporte->sql_record($sql);
//    echo "<br><br>".$clissporte->sql_query(null," q40_fisica ",null," q40_fisica is false ");
      if($clissporte->numrows > 0){
        $incluisani = 't';
        $incluiativ = 't';
      }
    }else if (isset($q60_integrasani) && $q60_integrasani == 2) {

      // verifica a classe se a classe esta configurada
      $rsClasAtiv  = $clclasativ->sql_record($clclasativ->sql_query(null,null,"*",null," q82_ativ = $q07_ativ and q12_integrasani = 't' "));
      //die($clclasativ->sql_query(null,null,"*",null," q82_ativ = $q07_ativ and q12_integrasani = 't' "));
      if( $clclasativ->numrows > 0 ){

        $incluisani = 't';
        $incluiativ = 't';
      }
    }

//**********************************************************************************************

    if (isset($incluisani) && $incluisani = 't') {
      $rsSanitario = $clsanitario->sql_record($clsanitario->sql_query_file($q07_inscr,"*",null,""));
      if($clsanitario->numrows == 0){

        $sqlIssbase  = "select issbase.q02_inscr, issbase.q02_numcgm, q30_area, j14_codigo, q13_bairro as j13_codi, q02_numero, q02_compl, q02_dtbaix";
        $sqlIssbase .= "  from issbase ";
        $sqlIssbase .= "   inner join issquant  on issquant.q30_inscr  = issbase.q02_inscr ";
        $sqlIssbase .= "                       and issquant.q30_anousu = ".db_getsession('DB_anousu');
        $sqlIssbase .= "   left  join issruas   on issruas.q02_inscr   = issbase.q02_inscr ";
        $sqlIssbase .= "   left  join issbairro on issbairro.q13_inscr = issbase.q02_inscr ";
        $sqlIssbase .= "where issbase.q02_inscr = $q07_inscr ";
        $rsIssbase  = $clissbase->sql_record($sqlIssbase);
        if($clissbase->numrows > 0){

          db_fieldsmemory($rsIssbase,0);

          $clsanitario->y80_codsani   = $q02_inscr;
          $clsanitario->y80_numbloco  = 0;
          $clsanitario->y80_numcgm    = $q02_numcgm;
          $clsanitario->y80_data      = date("Y-m-d",db_getsession('DB_datausu'));
          $clsanitario->y80_obs       = "";
          $clsanitario->y80_depto     = db_getsession('DB_coddepto');
          $clsanitario->y80_area      = $q30_area;
          if (isset($j14_codigo)&& $j14_codigo != "") {
            $clsanitario->y80_codrua = $j14_codigo;
          } else {
            $clsanitario->y80_codrua = '0';
          }
          if (isset($j13_codi) && $j13_codi != "") {
            $clsanitario->y80_codbairro = $j13_codi;
          } else {
            $clsanitario->y80_codbairro = '0';
          }
          if (isset($q02_numero) && $q02_numero != "") {
            $clsanitario->y80_numero = $q02_numero;
          } else {
            $clsanitario->y80_numero = '0';
          }
          if (isset($q02_compl) && $q02_compl != "") {
            $clsanitario->y80_compl = $q02_compl;
          } else {
            $clsanitario->y80_compl = "0";
          }
          $clsanitario->y80_dtbaixa = $q02_dtbaix;
          $clsanitario->y80_codsani = $q02_inscr;
          $clsanitario->incluir_sem_seq($q02_inscr);
          if ($clsanitario->erro_status==0) {
            $sqlerro = true;
            $erromsg = " -- Sanitario ".$clsanitario->erro_msg;
          }

	        $clsanitarioinscr->y18_codsani = $q07_inscr;
		    $clsanitarioinscr->y18_inscr = $q07_inscr;
		    $clsanitarioinscr->incluir($q07_inscr,$q07_inscr) ;
		    if($clsanitarioinscr->erro_status==0){
		        $sqlerro=true;
		        $erromsg=$clsanitarioinscr->erro_msg;
		    }

        }
      }


    }

//*********************************************************************************************************************************


    if (isset($incluiativ) && $incluiativ = 't') {
      $sqlrua = "select * from issruas where q02_inscr= $q07_inscr";
      $resultrua = db_query($sqlrua);
      $linhasrua = pg_num_rows($resultrua);
      if($linhasrua==0){
        $erromsg = "Inscrição sem logradouro cadastrado.";
        $sqlerro = true;
      }

      $sqlbairro = "select * from issbairro where q13_inscr =  $q07_inscr";
      $resultbairro = db_query($sqlbairro);
      $linhasbairro = pg_num_rows($resultbairro);
      if($linhasbairro==0){
        $erromsg = "Inscrição sem Bairro cadastrado.";
        $sqlerro = true;
      }


      if($sqlerro==false){
      $clsaniatividade->y83_codsani   = $q07_inscr;
      $clsaniatividade->y83_seq       = $q07_seq;
      $clsaniatividade->y83_ativ      = $q07_ativ;
      $clsaniatividade->y83_area      = 0;
      if ($q07_perman=='t') {
        $clsaniatividade->y83_perman  = 'true';
      } else {
        $clsaniatividade->y83_perman  = 'false';
      }
      $clsaniatividade->y83_dtini     = "$q07_datain_ano-$q07_datain_mes-$q07_datain_dia";
      if (@$q07_datafi_ano != "" && @$q07_datafi_mes != "" && @$q07_datafi_dia != "" ) {
        $clsaniatividade->y83_dtfim     = "$q07_datafi_ano-$q07_datafi_mes-$q07_datafi_dia";
      } else {
        $clsaniatividade->y83_dtfim     = null;
      }
      if (isset($princ) && $princ=='t') {
        $clsaniatividade->y83_ativprinc = 'true';
      } else {
        $clsaniatividade->y83_ativprinc = 'false';
      }

      $clsaniatividade->incluir($clsaniatividade->y83_codsani,$clsaniatividade->y83_seq);
      if ($clsaniatividade->erro_status==0) {
        $erromsg = "Inclusão saniatividade : ".$clsaniatividade->erro_msg;
        $sqlerro = true;
      }
    }
    }
  }

//**********************************************************************************************************************************//

if($sqlerro==false){

    $result05 = $clissbase->sql_record($clissbase->sql_query_file($q07_inscr,"q02_dtbaix"));
    if( $clissbase->numrows > 0 ){
      db_fieldsmemory($result05,0);
    }
    $result01 = $cltabativ->sql_record($cltabativ->sql_query_file('','','min(q07_datain) as datainicial','',' q07_inscr = '.$q07_inscr.''));

    if( $cltabativ->numrows > 0 ){
      db_fieldsmemory($result01,0);
    }
    $clissbase->q02_inscr  = $q07_inscr;
    $clissbase->q02_dtinic = $datainicial;
    $clissbase->alterar($q07_inscr);
    if ($clissbase->erro_status==0) {
      $erromsg = "Alteração issbase erro 3: ".$clissbase->erro_msg;
      $sqlerro = true;
    }
    if (isset($q02_dtbaix) && $q02_dtbaix!="") {
      $clissbase->q02_inscr = $q07_inscr;
      $HTTP_POST_VARS["q02_dtbaix_dia"]="";
      $clissbase->alterar($q07_inscr);
      $clissbase->erro(true,false);
      if ($clissbase->erro_status==0) {
        $erromsg = "Alteração issbase erro 4 : ".$clissbase->erro_msg;
        $sqlerro = true;
      }
    }
  }

  if ($sqlerro==false) {
    $q07_ativ="";
    $q07_seq="";
    $q03_descr="";
    $princ="f";
    $q07_perman="t";
    $q11_tipcalc="";
    $q81_descr="";
    $q07_datafi_dia="";
    $q07_datafi_mes="";
    $q07_datafi_ano="";
    $q07_horaini     = "";
    $q07_horafim     = "";

  }

  if (!$sqlerro) {

    try {

      if (isset($cltabativ->q07_ativ) && !empty($cltabativ->q07_ativ)) {

        $cllogatividade->identificaAlteracao($q07_inscr,1,5,$cltabativ->q07_ativ);
        $cllogatividade->gravarLog();
      }

    } catch ( Exception $eExeption ){

      $sqlerro  = true;
      $erromsg  = $eExeption->getMessage();
    }
  }
  if (!$sqlerro) {
    echo "
    <script>
       function js_src(){
        parent.document.formaba.documentos.disabled=false;
        parent.iframe_documentos.location.href=\"iss4_docalvaratab_001.php?aba=1&q123_inscr=$q07_inscr\";\n
        //parent.mo_camada('documentos');
       }
       js_src();
   </script>
       ";
  }
  db_fim_transacao($sqlerro);
} else if (isset($alterar) && !$sqlerro) {
    $sqlerro = false;
    db_inicio_transacao();

    // Verifica se esta tentando incluir uma atividade diferente do tipo de alvara permanente/provisorio
    $tipoperman = 'false';
    if( $q07_perman == 'f' ){
      $tipoperman = 'true';
    }

    /**
     * Verifica se esta tentando incluir uma atividade diferente do tipo de alvara permanente/provisorio
     */
    $sWhereAtividade = "    q07_databx is null
                        and q07_seq <> {$q07_seq}
                        and q07_inscr = {$q07_inscr}
                        and q07_perman is {$tipoperman} ";
    $sSqlAtividade   = $cltabativ->sql_query_file($q07_inscr, '', '*', null, $sWhereAtividade);
    $rsPesquisaAtivTipo = $cltabativ->sql_record($sSqlAtividade);

    if ( $cltabativ->numrows > 0 ) {

      $erromsg = "Não é permitido inclusão de atividade permanente e provisório para o mesmo alvará.";
      $sqlerro = true;
    }

    if(!$sqlerro){

      $cltabativ->q07_inscr   = $q07_inscr;
      $cltabativ->q07_seq     = $q07_seq;
      $cltabativ->q07_ativ    = $q07_ativ;
      $cltabativ->q07_quant   = $q07_quant;
      $cltabativ->q07_perman  = $q07_perman;
      $cltabativ->q07_datain  = "$q07_datain_ano-$q07_datain_mes-$q07_datain_dia";
      $cltabativ->q07_horaini = "$q07_horaini";
      $cltabativ->q07_horafim = "$q07_horafim";
      if(isset($q07_datafi_ano) && $q07_datafi_ano!="" && isset($q07_datafi_mes) && $q07_datafi_mes!="" && isset($q07_datafi_dia) && $q07_datafi_dia!=""){
        $cltabativ->q07_datafi = "$q07_datafi_ano-$q07_datafi_mes-$q07_datafi_dia";
      }else{
        $cltabativ->q07_datafi = null;
      }
      $cltabativ->q07_tipbx = "0";

      $rsPesquisaAtivTipo = $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,'','*',null," q07_perman is {$tipoperman} and q07_seq <> $q07_seq and q07_inscr = $q07_inscr and (case when q07_databx is null then true else false end)"));
      if ( $cltabativ->numrows > 1 and $cltabativ->q07_datafi == "") {
        $erromsg = "Não é permitido inclusão de atividade permanente sem data de baixa e provisório para o mesmo alvara.";
        $sqlerro = true;
      }

      $cltabativ->alterar($q07_inscr,$q07_seq);
      if($cltabativ->erro_status==0){
        $erromsg = "Alteração tabativ : ".$cltabativ->erro_msg;
        $sqlerro = true;
      }
    }

    if(!$sqlerro){
      $cltabativtipcalc->q11_inscr   = $q07_inscr;
      $cltabativtipcalc->q11_seq     = $q07_seq;
      $cltabativtipcalc->q11_tipcalc = $q11_tipcalc;
      $cltabativtipcalc->alterar($q07_inscr,$q07_seq);
      if($cltabativtipcalc->erro_status==0){
        $erromsg = "Alteração tabativ : ".$cltabativtipcalc->erro_msg;
        $sqlerro = true;
      }

    }
    $result01=$cltabativ->sql_record($cltabativ->sql_query_file('','','min(q07_datain) as datainicial','',' q07_inscr = '.$q07_inscr.' '));
    db_fieldsmemory($result01,0);
    $clissbase->q02_inscr  = $q07_inscr;
    $clissbase->q02_dtinic = $datainicial;
    $clissbase->alterar($q07_inscr);
    if($clissbase->erro_status==0){
      $erromsg = "Alteração issbas : ".$clissbase->erro_msg;
      $sqlerro=true;
    }
    if(isset($princ) && $princ=='t'){
       $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr));
       if($clativprinc->numrows>0){
   	     $clativprinc->q88_inscr = $q07_inscr;
         $clativprinc->excluir($q07_inscr);
         if($clativprinc->erro_status==0){
            $erromsg = "Exclusão ativprinc : ".$clativprinc->erro_msg;
            $sqlerro = true;
         }
       }
       $clativprinc->q88_inscr=$q07_inscr;
       $clativprinc->q88_seq=$q07_seq;
       $clativprinc->incluir($q07_inscr);
       if($clativprinc->erro_status==0){
          $erromsg = "Inclusão ativprinc : ".$clativprinc->erro_msg;
          $sqlerro = true;
       }
    }elseif(isset($princ) && $princ=='f'){
       $clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr,"q88_seq","","q88_inscr=$q07_inscr and  q88_seq=$q07_seq"));
       if($clativprinc->numrows>0){
  	     $clativprinc->q88_inscr=$q07_inscr;
         $clativprinc->excluir($q07_inscr);
         if($clativprinc->erro_status==0){
            $erromsg = "Exclusão ativprinc : ".$clativprinc->erro_msg;
            $sqlerro=true;
         }
       }
    }
    if(!$sqlerro){
      if(isset($q60_integrasani) && $q60_integrasani == 1){
        $clsaniatividade->y83_codsani   = $q07_inscr;
        $clsaniatividade->y83_seq       = $q07_seq;
        $clsaniatividade->y83_ativ      = $q07_ativ;
        $clsaniatividade->y83_area      = 0;
        $clsaniatividade->q83_perman    = $q07_perman;
        $clsaniatividade->y83_dtini     = "$q07_datain_ano-$q07_datain_mes-$q07_datain_dia";
        if(@$q07_datafi_ano!="" && @$q07_datafi_mes!="" && @$q07_datafi_dia!=""){
              $clsaniatividade->y83_dtfim     = "$q07_datafi_ano-$q07_datafi_mes-$q07_datafi_dia";
        }else{
              $clsaniatividade->y83_dtfim     = null;
        }
        if(isset($princ) && $princ=='t'){
          $clsaniatividade->y83_ativprinc = 'true';
        }else{
          $clsaniatividade->y83_ativprinc = 'false';
        }
        $clsaniatividade->alterar($q07_codsani,$qy07_seq);
        if($clsaniatividade->erro_status==0){
          $erromsg = "Alteração saniatividade : ".$clsaniatividade->erro_msg;
          $sqlerro = true;
        }
      }
	  }
//  $sqlerro = true;
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      $q07_seq="";
      $q07_ativ="";
      $q03_descr="";
      $princ="f";
      $q07_perman="t";
      $q11_tipcalc="";
      $q81_descr="";
      $q07_datafi_dia="";
      $q07_datafi_mes="";
      $q07_datafi_ano="";
      $q07_horaini="";
      $q07_horafim="";
    }

  }else if(isset($excluir)){

    $sqlerro=false;
    db_inicio_transacao();
    if(!$sqlerro){

      $cltabativtipcalc->q11_inscr=$q07_inscr;
      $cltabativtipcalc->q11_seq=$q07_seq;
      $cltabativtipcalc->excluir($q07_inscr,$q07_seq);
      if($cltabativtipcalc->erro_status==0){
        $erromsg = "Exclusão tabativtipcalc : ".$cltabativtipcalc->erro_msg;
        $sqlerro = true;
      }
    }
    if(!$sqlerro){

      $result22=$clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr));
      if($clativprinc->numrows>0){
	      db_fieldsmemory($result22,0);
	      if($q88_seq==$q07_seq){
   	      $clativprinc->q88_inscr=$q07_inscr;
          $clativprinc->excluir($q07_inscr);
          if($clativprinc->erro_status==0){
             $erromsg = "Excluir ativprinc : ".$clativprinc->erro_msg;
             $sqlerro=true;
          }
	      }
      }
    }

    if(!$sqlerro){

		  if(isset($q60_integrasani) && ( $q60_integrasani == 1 || $q60_integrasani == 2) ){

			  $clsaniatividade->excluir($q07_inscr,$q07_seq);
			  if($clsaniatividade->erro_status==0){
				  $erromsg = "Excluir saniatividade : ".$clsaniatividade->erro_msg;
				  $sqlerro = true;
			  }
      }
	  }

/*****************************************************************************************************/

    $result01=$cltabativ->sql_record($cltabativ->sql_query_file('','','min(q07_datain) as datainicial','',' q07_inscr = '.$q07_inscr.' and q07_ativ <> '.$q07_ativ.''));
    db_fieldsmemory($result01,0);
    $clissbase->q02_inscr=$q07_inscr;
    $clissbase->q02_dtinic=$datainicial;
    $clissbase->alterar($q07_inscr);
    if($clissbase->erro_status==0){
      $erromsg = "Alteração issbase erro 2 : ".$clissbase->erro_msg;
      $sqlerro = true;
    }
    if(!$sqlerro){
      $cltabativ->q07_inscr=$q07_inscr;
      $cltabativ->q07_seq=$q07_seq;
      $cltabativ->excluir($q07_inscr,$q07_seq);
      if($cltabativ->erro_status==0){
        $erromsg = "Exclusão tabativ : ".$cltabativ->erro_msg;
        $sqlerro = true;
      }
    }
    if(!$sqlerro){
      $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,"","q07_inscr","","q07_databx is null and q07_inscr=$q07_inscr"));
      if($cltabativ->numrows<1){
        $result87 = $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,"","max(q07_databx) as q07_databx"));
        db_fieldsmemory($result87,0);
        $clissbase->q02_dtbaix=$q07_databx;
        $clissbase->q02_inscr=$q07_inscr;
        $clissbase->alterar($q07_inscr);
        //$clissbase->erro(true,false);
        if($clissbase->erro_status==0){
          $erromsg = "Alteração issbase erro 1 : ".$clissbase->erro_msg;
          $sqlerro = true;
        }
      }
    }
//  $sqlerro = true;
    db_fim_transacao($sqlerro);
    if($sqlerro == false){
      $q07_ativ       = "";
      $q07_seq        = "";
      $q03_descr      ="";
      $q07_perman     = "t";
      $q11_tipcalc    = "";
      $q81_descr      = "";
      $q07_datafi_dia = "";
      $q07_datafi_mes = "";
      $q07_datafi_ano = "";
      $q07_datafi     = "";
      $q07_horaini     = "";
      $q07_horafim     = "";
    }
  }
if(isset($db_opcaoal)){
  $db_opcao=3;
  $db_botao=false;
}


//================================================  GERAÇÂO AUTOMATICA DO ALVARA ======================================



// verificamos nas classes se alguma das atividades está como principal para verificar o q12_alvaraautomatico

$sSqlAlvaraAuto  = "select   q03_ativ,                                                           ";
$sSqlAlvaraAuto .= "				 q12_alvaraautomatico,																							 ";
$sSqlAlvaraAuto .= "				 q12_classe,                                                         ";
$sSqlAlvaraAuto .= "				 case                                                                ";
$sSqlAlvaraAuto .= "				  when q88_inscr is not null then																		 ";
$sSqlAlvaraAuto .= "				     'sim' else                                                      ";
$sSqlAlvaraAuto .= "				     'nao'                                                           ";
$sSqlAlvaraAuto .= "				 end as principal ,                                                  ";
$sSqlAlvaraAuto .= "				 case                                                                ";
$sSqlAlvaraAuto .= "				  when q07_perman is true then																		   ";
$sSqlAlvaraAuto .= "				     'sim' else                                                      ";
$sSqlAlvaraAuto .= "				     'nao'                                                           ";
$sSqlAlvaraAuto .= "				 end as permanente                                                   ";
$sSqlAlvaraAuto .= "    from tabativ                                                             ";
$sSqlAlvaraAuto .= "			   left  join ativprinc on q88_inscr = q07_inscr                       ";
$sSqlAlvaraAuto .= "                             and q88_seq = q07_seq                           ";
$sSqlAlvaraAuto .= "			   inner join ativid on ativid.q03_ativ = tabativ.q07_ativ             ";
$sSqlAlvaraAuto .= "         inner join clasativ on q82_ativ = q03_ativ                          ";
$sSqlAlvaraAuto .= "         inner join classe on q82_classe = q12_classe                        ";
$sSqlAlvaraAuto .= "    where q07_inscr = {$q07_inscr}                                           ";

$rsAlvaraAuto      = db_query($sSqlAlvaraAuto);
$iLinhasAlvaraAuto = pg_num_rows($rsAlvaraAuto);
$aAlvaraAuto       = array();
$lGeraAutomatico   = 'true';
$lPermanente       = 'false';
$iTipoalvara       = "";




if ($iLinhasAlvaraAuto > 0){

	$aAlvaraAuto     = db_utils::getCollectionByRecord($rsAlvaraAuto);
	foreach ($aAlvaraAuto as $iIndice => $oValor){

		if ($oValor->principal == 'sim' && $oValor->q12_alvaraautomatico == 'f') {

			$lGeraAutomatico = 'false';

		}
		if($oValor->principal == 'sim' && $oValor->permanente == "sim"){

			$lPermanente = 'true';
		}
	}
}

/*
 * Verificamos na tabela issalvara, se ja possui registro para essa matricula
 * caso nao exista incluimos o alvara gerado

 */

$sSqlExisteAlvara = $clIssAlvara->sql_query(null, "q123_sequencial", null, "q123_inscr = {$q07_inscr} and q123_situacao in (1,2)");
$rsExisteAlvara   = $clIssAlvara->sql_record($sSqlExisteAlvara);
$lInserirAlvara   = "true";
if($clIssAlvara->numrows > 0){
	    echo "
    <script>
       function js_src(){
        parent.document.formaba.documentos.disabled=false;
        parent.iframe_documentos.location.href=\"iss4_docalvaratab_001.php?aba=1&q123_inscr=$q07_inscr\";\n
       }
       js_src();
   </script>
       ";
	$lInserirAlvara = "false";
}

//=======  VERIFICAMOS NA PARISSQN o tipo de alvara

$sSqlTipoAlvara = $clparissqn->sql_query_file(null,"q60_isstipoalvaraper,q60_isstipoalvaraprov", null , null);
$rsTipoAlvara   = $clparissqn->sql_record($sSqlTipoAlvara);
$oTipoAlvara    = db_utils::fieldsMemory($rsTipoAlvara,0);


if (@$lAlvaraPerm == 't') {

	$iTipoalvara = $oTipoAlvara->q60_isstipoalvaraper;
} else {
	$iTipoalvara = $oTipoAlvara->q60_isstipoalvaraprov;
}

/*
 * INICIAMOS AS INCLUSOES NECESSARIAS CONFORME DEFINIÇOES ANTERIORES.
 * se o parametro geraautomatico for true inserimos na issalvara e issmovalvara
 * se for false inserimos somente na iss alvara
 * situação:
 *   1 - Ativo
 *
 * tipo de Movimentação :
 *   1 - LIBERACAO
 */
$sDtInclusao     = implode("-", array_reverse(explode("/",@$q07_datain)));
$sValidadeAlvara = '0';
$iValidadeAlvara = "";

if (@$q07_datafi != null || @$q07_datafi != '') {

	$sValidadeAlvara = implode("-", array_reverse(explode("/",$q07_datafi)));
	$iValidadeAlvara = quantDias($q07_datain, $q07_datafi);

}

if ($lGeraAutomatico == 'false') {


  $sCampo   = "q120_issalvara, q123_inscr ";
  $sSql     = $clIssMovAlvara->sql_AlvaraLiberado(null, $q07_inscr, $sCampo, null);
  $rsMovAlv = $clIssMovAlvara->sql_record($sSql);

  if ($clIssMovAlvara->numrows > 0) {

    echo "
          <script>
             function js_src(){
              parent.document.formaba.liberacao.disabled=false;
              parent.iframe_liberacao.location.href=\"iss4_liberacaoalvara_001.php?aba=1&q123_inscr=$q07_inscr\";\n
             }
             js_src();
         </script>
       ";
  } else {

    echo "
          <script>
             function js_src(){
              parent.document.formaba.liberacao.disabled=false;
              parent.iframe_liberacao.location.href=\"iss4_liberacaoalvara_001.php?aba=1&q123_inscr=$q07_inscr\";\n
             }
             js_src();
         </script>
       ";
  }


}

if ($lInserirAlvara == 'true' && $lAtivPrinc == 't' && isset($incluir)){

	try{

    db_inicio_transacao();

		$clIssAlvara->q123_isstipoalvara = $iTipoalvara;  // valor a partir da parissqn
		$clIssAlvara->q123_inscr         = $q07_inscr;
		$clIssAlvara->q123_dtinclusao    = $sDtInclusao;
		$clIssAlvara->q123_situacao      = 1;
		$clIssAlvara->q123_usuario       = db_getsession("DB_id_usuario");
		if ($lGeraAutomatico == 'true') {
			$clIssAlvara->q123_geradoautomatico = "true";
		} else {
			$clIssAlvara->q123_geradoautomatico = "false";
		}
		$clIssAlvara->incluir(null);
		if($clIssAlvara->erro_status == '0'){
			throw new Exception($clIssAlvara->erro_msg);
		}

		// se a for tru a geração automatica, criamos um movimento para o alvara
		if ($lGeraAutomatico == 'true') {

			$clIssMovAlvara->q120_codproc          = "";
			$clIssMovAlvara->q120_issalvara        = $clIssAlvara->q123_sequencial;
			$clIssMovAlvara->q120_isstipomovalvara = 1 ;// liberação
			$clIssMovAlvara->q120_dtmov            = $sDtInclusao;
			$clIssMovAlvara->q120_validadealvara   = $iValidadeAlvara;
			$clIssMovAlvara->q120_usuario          = db_getsession("DB_id_usuario");
			$clIssMovAlvara->q120_obs              = "GERACAO AUTOMATICA";
			$clIssMovAlvara->incluir(null);
			if($clIssMovAlvara->erro_status == '0'){
				throw new Exception($clIssMovAlvara->erro_msg);
			}
		}

		db_fim_transacao(false);
	} catch (Exception $eException){

		db_msgbox($eException->getMessage());
		db_fim_transacao(true);
	}

}



?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
    	<?php include modification("forms/db_frmtabativalt.php"); ?>
    </div>
  </body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){

  if($sqlerro==true){

		db_msgbox($erromsg);
  }else{

     if(isset($errox)){
       db_msgbox($errox);
     }else{
       $cltabativ->erro(true,false);
     }
     echo "<script>top.corpo.iframe_calculo.location.href=\"iss1_isscalc004.php?automatico=$lGeraAutomatico&q07_inscr=$q07_inscr&z01_nome=$z01_nome\";</script>";
  }
}
?>