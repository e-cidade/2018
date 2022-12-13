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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_stdlibwebseller.php"));
require_once (modification("libs/JSON.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

$objJson             = new services_json();
if(isset($_GET['tipo'])){
  $iTipo = $_GET['tipo'];
} else {
  $iTipo = 0;
  $objParam = $objJson->decode(str_replace("\\","",$_POST["json"]));
}

$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';
$objRetorno->erro    = false;

//Variaveis uteis
$departamento = db_getsession("DB_coddepto");
$hoje         = date("Y-m-d",db_getsession("DB_datausu"));
$descrdepto   = db_getsession("DB_nomedepto");
$login        = DB_getsession("DB_id_usuario");

//classes do RPC
$cllab_setorexame     = new cl_lab_setorexame;
$cllab_examerequisito = new cl_lab_examerequisito;
$cllab_requisicao     = new cl_lab_requisicao;
$cllab_requiitem      = new cl_lab_requiitem;
$cllab_resultado      = new cl_lab_resultado;
$cllab_resultadoitem  = new cl_lab_resultadoitem;
$cllab_resultadoalfa  = new cl_lab_resultadoalfa;
$cllab_resultadonum   = new cl_lab_resultadonum;

if($iTipo==1){ //Auto complete profissional agendamento de exames
	$sName   = html_entity_decode(crossUrlDecode($_POST['string']));
	$oDaoMedicos = db_utils::getdao('medicos');
  $sCampos  = 'distinct medicos.sd03_i_codigo as cod, ';
  $sCampos .= '         case ';
  $sCampos .= '           when sd03_i_tipo = 1 ';
  $sCampos .= '             then cgm.z01_nome ';
  $sCampos .= '           else ';
  $sCampos .= '             s154_c_nome ';
  $sCampos .= '          end as label ';
  $sWhere   = "z01_nome like upper('".$sName."%') or s154_c_nome like upper('".$sName."%')";
  $sSql     = $oDaoMedicos->sql_query_cgm_fora_rede(null, $sCampos, 'label', $sWhere);
  $rs       = $oDaoMedicos->sql_record($sSql);
  $iLinhas  = $oDaoMedicos->numrows;
  $aRetorno = "";
  if ($iLinhas > 0){
    $aRetorno = db_utils::getCollectionByRecord($rs, false, false, true);
  }
	echo $objJson->encode($aRetorno);
	exit;
}
if ($iTipo == 2) { //Auto complete exames agendamento de exames

  $sName         = html_entity_decode(crossUrlDecode($_POST['string']));
  $oDaoLabExames = db_utils::getdao('lab_exame');

  $sCampos  = 'la08_i_codigo as cod, ';
  $sCampos .= 'la08_c_descr as label ';
  $sWhere   = "(la08_c_descr like upper('%".$sName."%') or sau_procedimento.sd63_c_procedimento like ('".$sName."%'))";
  $sWhere  .= "and lab_exameproced.la53_i_ativo = 1";
  $sWhere  .= "and EXISTS ( select * from lab_setorexame where la09_i_exame = la08_i_codigo ) ";
  $sSql     = $oDaoLabExames->sql_query_procedimento(null, $sCampos, 'label', $sWhere);
  $rs       = $oDaoLabExames->sql_record($sSql);
  $iLinhas  = $oDaoLabExames->numrows;
  $aRetorno = "";

  if ($iLinhas > 0){
    $aRetorno = db_utils::getCollectionByRecord($rs, false, false, true);
  }
  echo $objJson->encode($aRetorno);
  exit;
}

if ($objParam->exec == 'getProcedimento') {

  $clsau_proccbo = db_utils::getDao("sau_proccbo_ext");

  $strWhere  = "     sau_proccbo.sd96_i_cbo = {$objParam->rh70_sequencial} ";
  $strWhere .= " and sau_procedimento.sd63_c_procedimento = '{$objParam->sd63_c_procedimento}'";
  $campos = "sau_procedimento.*,sau_proccbo.*";

  //remove filtro de unidade
  if(!isset($objParam->sd24_i_unidade)){

    $objParam->sd24_i_unidade = 0;
    $lFiltraServico           = false;

  }else{
    $lFiltraServico = true;
  }

  $strSQL = $clsau_proccbo->sql_query_ext("",
                                $campos,
                                "sd96_i_anocomp desc , sd96_i_mescomp desc limit 1",
                                $strWhere,
                                $objParam->sd24_i_unidade,
                                $lFiltraServico );
  $strSQLCID  = "select *, ";
  $strSQLCID .= "  ( select count(*) ";
  $strSQLCID .= "      from sau_proccid ";
  $strSQLCID .= "     where sd72_i_procedimento = sd96_i_procedimento ";
  $strSQLCID .= "       and sd72_i_anocomp = sd96_i_anocomp ";
  $strSQLCID .= "       and sd72_i_mescomp = sd96_i_mescomp ";
  $strSQLCID .= "  ) as intCID ";
  $strSQLCID .= "  from ( $strSQL ) as xx";

  $res_sau_proccbo = $clsau_proccbo->sql_record($strSQLCID);
  $objRetorno->iIndex = $objParam->iIndex;
  if ($clsau_proccbo->numrows > 0) {
    $objRetorno->itens   = db_utils::getCollectionByRecord($res_sau_proccbo, true, false, true);
  } else {

    $objRetorno->status  = 2;
    $objRetorno->message = urlencode( "Procedimento [{$objParam->sd63_c_procedimento}], não encontrado ou não vinculado com a especialidade! " );

  }
}

if ($objParam->exec == 'getGridExames') {

  $oDaoLabRequiitem = db_utils::getdao('lab_requiitem');
  $sCampos          = "distinct la21_i_codigo as codigo,";
  $sCampos         .= "la08_c_descr  as descr,";
  $sCampos         .= "(select sd63_c_procedimento||'__'||(sd63_f_sa+sd63_f_sp)||'__'||nome||'__'||la47_d_data||";
  $sCampos         .= "'__'||la47_c_hora||'__'||id_usuario||'__'||sd63_i_codigo";
  $sInnerJoin       = " inner join sau_procedimento on sd63_i_codigo = la47_i_procedimento ";
  $sInnerJoin      .= " inner join db_usuarios on id_usuario = la47_i_login ";
  $sCampos         .= " from lab_conferencia $sInnerJoin where la47_i_requiitem=la21_i_codigo order by la47_d_data desc,la47_c_hora desc limit 1) as conferencia, ";
  $sCampos         .= " (select la34_c_nomearq from lab_emissao where la34_i_requiitem=la21_i_codigo ";
  $sCampos         .= "order by la34_d_data desc,la34_c_hora desc limit 1) as arqui";
  $sSql             = $oDaoLabRequiitem->sql_query(null,$sCampos,null," la21_i_requisicao=$objParam->iRequi ");
  $rsResult         = $oDaoLabRequiitem->sql_record($sSql);
  if ($oDaoLabRequiitem->numrows > 0) {
    $objRetorno->aItens = db_utils::getCollectionByRecord($rsResult,true);
  } else {

  	$objRetorno->status  = 0;
    $objRetorno->message = ' Nenhum exame para a requisicao! ';

  }

}

if($objParam->exec == 'LoadLaboratorio'){
  $sSql=$cllab_setorexame->sql_query("",
                                     " la09_i_codigo as chave,la02_c_descr||'-'||la23_c_descr as descricao",
                                     "",
                                     " la09_i_exame=$objParam->exame and la09_i_ativo=1 ");
  $rResult=$cllab_setorexame->sql_record($sSql);
  if($cllab_setorexame->numrows>0){
     $codigos=array();
     $exames=array();
     for($x=0;$x<$cllab_setorexame->numrows;$x++){

        db_fieldsmemory($rResult,$x);
        $codigos[]      = $chave;
        $laboratorios[] = urlencode($descricao);

     }
     $objRetorno->codigos      = $codigos;
     $objRetorno->laboratorios = $laboratorios;
  }else{
     $objRetorno->status  = 0;
     $objRetorno->message = ' Exame sem laboratorios! ';
  }
}
if($objParam->exec == 'DadosExame'){
  $sSql=$cllab_setorexame->sql_query(""," la08_i_dias as dias,la08_c_descr as sexame,la09_i_exame as iexame,la02_i_codigo,la02_c_descr ",""," la09_i_codigo=$objParam->la09_i_codigo");
  $rResult=$cllab_setorexame->sql_record($sSql);
  if($cllab_setorexame->numrows>0){
      db_fieldsmemory($rResult,0);

      $sSql=$cllab_examerequisito->sql_query(""," la20_t_descr as trequisito,la12_c_descr as srequisito ",""," la20_i_exame=$iexame and la20_d_fim >='$hoje' and la20_d_inicio <= '$hoje'");
      $rResult=$cllab_examerequisito->sql_record($sSql);
      $sStr="$sexame \n";
      for($x=0;$x<$cllab_examerequisito->numrows;$x++){
          db_fieldsmemory($rResult,$x);
          $sStr = $sStr."\n *$trequisito - $srequisito ";
      }

      $objRetorno->dias         = $dias;
      $objRetorno->sRequisitos  = $sStr;
      $objRetorno->iLaboratorio = $la02_i_codigo;
      $objRetorno->sLaboratorio = urlencode($la02_c_descr);
  }else{
      $objRetorno->status  = 0;
      $objRetorno->message = ' Exame codigo '+$objParam->la29_i_codigo+' inexistente! ';
  }
}
if($objParam->exec == 'CarregaGrid'){

	$sSql=$cllab_requisicao->sql_query_requiitem("","lab_requiitem.*,lab_exame.*,lab_laboratorio.*,lab_coletaitem.la32_i_codigo",""," la21_i_requisicao=$objParam->requisicao ");
	$result = $cllab_requisicao->sql_record($sSql);
    $alinhasgrid=Array();
    $y=0;
    for($x=0;$x<$cllab_requisicao->numrows;$x++){
    	$oExame=db_utils::fieldsmemory($result,$x);
        if($oExame->la32_i_codigo==""){
          //montar array com linhas do grid
          $aData=explode("-",$oExame->la21_d_data);
          $alinhasgrid[$y]="$oExame->la21_i_setorexame#$oExame->la02_c_descr#$oExame->la08_c_descr#".$aData[2]."/".$aData[1]."/".$aData[0]."#$oExame->la21_c_hora#$oExame->la08_i_dias#$oExame->la21_i_emergencia#$oExame->la21_i_codigo";
          $y++;
        }
    }
	$objRetorno->alinhasgrid=$alinhasgrid;
}
if ( $objParam->exec == 'CarregaGridAutorizado' ) {

  $sCampos     = "lab_requiitem.*, lab_exame.*, lab_laboratorio.*";
  $sWhere      = "la21_i_requisicao = {$objParam->requisicao} and la02_i_codigo = {$objParam->iLaboratorioLogado}";
	$sSql        = $cllab_requisicao->sql_query_coleta_amostra( "", $sCampos, "", $sWhere );
	$result      = $cllab_requisicao->sql_record( $sSql );
  $alinhasgrid = array();
  $y           = 0;

  for ( $x = 0; $x < $cllab_requisicao->numrows; $x++ ) {

    $oExame = db_utils::fieldsmemory( $result, $x );

    if ( ( $oExame->la21_c_situacao == "8 - Autorizado" ) || ( $oExame->la21_c_situacao == "f - falta material" ) ) {

      //montar array com linhas do grid
      $aData            = explode( "-", $oExame->la21_d_data );
      $aData2           = explode( "-", $oExame->la21_d_entrega );
      $alinhasgrid[$y]  = "{$oExame->la21_i_setorexame}#{$oExame->la02_c_descr}#{$oExame->la08_c_descr}#";
      $alinhasgrid[$y] .= $aData[2] . "/" . $aData[1] . "/" . $aData[0];
      $alinhasgrid[$y] .= "#{$oExame->la21_c_hora}#{$oExame->la21_c_situacao}#{$oExame->la21_i_emergencia}";
      $alinhasgrid[$y] .= "#{$oExame->la21_i_codigo}#" . $aData2[2] . "/" . $aData2[1] . "/" . $aData2[0];
      $y++;
    }
  }

	$objRetorno->alinhasgrid = $alinhasgrid;
}

if($objParam->exec == 'CarregaGridRequi'){

  $sSql=$cllab_requisicao->sql_query_requiitem("",
                                               "db_usuarios.nome,lab_requiitem.*,lab_requisicao.*,lab_exame.*,lab_laboratorio.*",
                                               "",
                                               " la21_i_requisicao=$objParam->requisicao and".
                                               " la02_i_codigo=$objParam->iLaboratorioLogado and ".
                                               " la21_c_situacao='1 - Nao Digitado' ");
  $result      = $cllab_requisicao->sql_record($sSql);
  $alinhasgrid = Array();
  $iCgs        = 0;
  $sLogin      = "";
  $dDataRequi  = "";
  for($x=0;$x<$cllab_requisicao->numrows;$x++){

    $oExame=db_utils::fieldsmemory($result,$x);
    //montar array com linhas do grid
    if ($x == 0) {

    	$sLogin     = $oExame->nome;
    	$iCgs       = $oExame->la22_i_cgs;
    	$aData      = explode("-",$oExame->la22_d_data);
    	$dDataRequi = $aData[2]."/".$aData[1]."/".$aData[0];

    }
    $aData           = explode("-",$oExame->la21_d_data);
    $aData2          = explode("-",$oExame->la21_d_entrega);
    $alinhasgrid[$x] = "$oExame->la21_i_setorexame#$oExame->la02_c_descr#$oExame->la08_c_descr#".$aData[2];
    $alinhasgrid[$x].= "/".$aData[1]."/".$aData[0]."#$oExame->la21_c_hora#$oExame->la21_c_situacao#";
    $alinhasgrid[$x].= "$oExame->la21_i_emergencia#$oExame->la21_i_codigo#".$aData2[2]."/".$aData2[1]."/";
    $alinhasgrid[$x].= $aData2[0]."";

  }
  $objRetorno->iCgs        = $iCgs;
  $objRetorno->sLogin      = $sLogin;
  $objRetorno->dDataRequi  = $dDataRequi;
  $objRetorno->alinhasgrid = $alinhasgrid;
}
if($objParam->exec == 'CarregaGridColetado'){

	$sSql=$cllab_requisicao->sql_query_requiitem("","lab_requiitem.*,lab_exame.*,lab_laboratorio.*,lab_coletaitem.la32_i_codigo",""," la21_i_requisicao=$objParam->requisicao ");
	$result = $cllab_requisicao->sql_record($sSql);
    $alinhasgrid=Array();
    $y=0;
    for($x=0;$x<$cllab_requisicao->numrows;$x++){
    	$oExame=db_utils::fieldsmemory($result,$x);
        if((($oExame->la32_i_codigo!="")&&($oExame->la21_c_situacao=="6 - Coletado")||($oExame->la21_c_situacao=="2 - Lancado"))){
          //montar array com linhas do grid
          $aData=explode("-",$oExame->la21_d_data);
          $alinhasgrid[$y]="$oExame->la21_i_setorexame#$oExame->la02_c_descr#$oExame->la08_c_descr#".$aData[2]."/".$aData[1]."/".$aData[0]."#$oExame->la21_c_hora#$oExame->la08_i_dias#$oExame->la21_i_emergencia#$oExame->la21_i_codigo";
          $y++;
        }
    }
	$objRetorno->alinhasgrid=$alinhasgrid;
}
if($objParam->exec == 'CarregaGridConfirmado'){

	$sSql=$cllab_requisicao->sql_query_requiitem("","lab_requiitem.*,lab_exame.*,lab_laboratorio.*,lab_coletaitem.la32_i_codigo",""," la21_i_requisicao=$objParam->requisicao ");
	$result = $cllab_requisicao->sql_record($sSql);
    $alinhasgrid=Array();
    $y=0;
    for($x=0;$x<$cllab_requisicao->numrows;$x++){
    	$oExame=db_utils::fieldsmemory($result,$x);
        if(($oExame->la32_i_codigo!="")&&($oExame->la21_c_situacao=="6 - Coletado")){
          //montar array com linhas do grid
          $aData=explode("-",$oExame->la21_d_data);
          $alinhasgrid[$y]="$oExame->la21_i_setorexame#$oExame->la02_c_descr#$oExame->la08_c_descr#".$aData[2]."/".$aData[1]."/".$aData[0]."#$oExame->la21_c_hora#$oExame->la08_i_dias#$oExame->la21_i_emergencia#$oExame->la21_i_codigo";
          $y++;
        }
    }
	$objRetorno->alinhasgrid=$alinhasgrid;
}


if($objParam->exec == 'digitacaoinc'){

	db_inicio_transacao();
    $cllab_resultado->la52_i_requiitem=$objParam->iRequiitem;
    $cllab_resultado->la52_i_usuario=$login;
    $cllab_resultado->la52_c_hora=date("H:i");
    $cllab_resultado->la52_d_data=$hoje;
    $cllab_resultado->la52_t_motivo=$objParam->sMotivo;
	$cllab_resultado->incluir(null);
    if($cllab_resultado->erro_status!="0"){

       $cllab_resultadoitem->la39_i_resultado=$cllab_resultado->la52_i_codigo;
       $aAtributos=explode("|",$objParam->sAtributos);
       $aValores=explode("|",$objParam->sValores);
       $aTipo=explode("|",$objParam->sTipos);
	   for($x=0;$x<count($aAtributos);$x++){

	   	   if($cllab_resultadoitem->erro_status!="0"){

	   	   	  $cllab_resultadoitem->la39_i_atributo=$aAtributos[$x];
    	      $cllab_resultadoitem->incluir(null);
    	      if($cllab_resultadoitem->erro_status!="0"){
    	         if(($aTipo[$x]==1)||($aTipo[$x]==2)){

                    $cllab_resultadoalfa->la40_i_result=$cllab_resultadoitem->la39_i_codigo;
                    if($aTipo[$x]==1){

                       $cllab_resultadoalfa->la40_c_valor=$aValores[$x];
                       $cllab_resultadoalfa->la40_i_valorrefsel="";

                    }else{

                       $cllab_resultadoalfa->la40_i_valorrefsel=$aValores[$x];
                       $cllab_resultadoalfa->la40_c_valor="";

                    }
    	            $cllab_resultadoalfa->incluir(null);
    	            if($cllab_resultadoalfa->erro_status=="0"){

    	            	$cllab_resultadoitem->erro_status="0";
    	            	$cllab_resultadoitem->erro_sql   = $cllab_resultadoalfa->erro_sql;
                        $cllab_resultadoitem->erro_campo = $cllab_resultadoalfa->erro_campo;
                        $cllab_resultadoitem->erro_banco = $cllab_resultadoalfa->erro_banco;
                        $cllab_resultadoitem->erro_msg   = $cllab_resultadoalfa->erro_msg;

                    }

    	         }
    	         if($aTipo[$x]==3){

    	   	         $cllab_resultadonum->la41_i_result=$cllab_resultadoitem->la39_i_codigo;
                     $cllab_resultadonum->la41_f_valor=$aValores[$x];
                     $cllab_resultadonum->incluir(null);
    	             if($cllab_resultadonum->erro_status=="0"){

    	             	$cllab_resultadoitem->erro_status="0";
    	             	$cllab_resultadoitem->erro_sql   = $cllab_resultadonum->erro_sql;
                        $cllab_resultadoitem->erro_campo = $cllab_resultadonum->erro_campo;
                        $cllab_resultadoitem->erro_banco = $cllab_resultadonum->erro_banco;
                        $cllab_resultadoitem->erro_msg   = $cllab_resultadonum->erro_msg;

    	             }

    	         }
    	      }else{

    	      	 $cllab_resultado->erro_status="0";
    	      	 $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
                 $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
                 $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
                 $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    	      }
    	   }else{

    	   	     $cllab_resultado->erro_status="0";
    	      	 $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
                 $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
                 $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
                 $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    	   }
       }
    }
    if($cllab_resultadoitem->erro_status=="0"){

       $cllab_resultado->erro_status="0";
       $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
       $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
       $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
       $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    }
    if($cllab_resultado->erro_status!="0"){

       $cllab_requiitem->la21_c_situacao="2 - Lancado";
       $cllab_requiitem->la21_i_codigo=$objParam->iRequiitem;
       $cllab_requiitem->alterar($objParam->iRequiitem);
       if($cllab_requiitem->erro_status=="0"){

          $cllab_resultado->erro_status="0";
    	  $cllab_resultado->erro_sql   = $cllab_requiitem->erro_sql;
          $cllab_resultado->erro_campo = $cllab_requiitem->erro_campo;
          $cllab_resultado->erro_banco = $cllab_requiitem->erro_banco;
          $cllab_resultado->erro_msg   = $cllab_requiitem->erro_msg;

       }

    }
    if($cllab_resultado->erro_status=="0"){
    	$objRetorno->status  = 0;
    }
    $objRetorno->message = $cllab_resultado->erro_msg;
    db_fim_transacao($cllab_resultado->erro_status=="0");
}

if($objParam->exec == 'digitacaoalt'){

	db_inicio_transacao();
    $cllab_resultado->la52_t_motivo=$objParam->sMotivo;
	$cllab_resultado->la52_i_codigo=$objParam->la52_i_codigo;
    $cllab_resultado->alterar($objParam->la52_i_codigo);
	if($cllab_resultado->erro_status!="0"){

       $aAtributos=explode("|",$objParam->sAtributos);
       $aValores=explode("|",$objParam->sValores);
       $aTipo=explode("|",$objParam->sTipos);
	   for($x=0;$x<count($aAtributos);$x++){

	   	   if($cllab_resultadoitem->erro_status!="0"){

	   	   	  $sSql=$cllab_resultadoitem->sql_query_file(""," la39_i_codigo ",""," la39_i_atributo=$aAtributos[$x] and la39_i_resultado=$objParam->la52_i_codigo ");
    	      $rResult=$cllab_resultadoitem->sql_record($sSql);
	   	   	  if($rResult!=false){
    	         db_fieldsmemory($rResult,0);
    	         if($cllab_resultadoitem->erro_status!="0"){
    	            if(($aTipo[$x]==1)||($aTipo[$x]==2)){

                       $sSql=$cllab_resultadoalfa->sql_query_file(""," la40_i_codigo ",""," la40_i_result=$la39_i_codigo ");
    	               $rResult=$cllab_resultadoalfa->sql_record($sSql);
	   	   	           db_fieldsmemory($rResult,0);
	   	   	           if($aTipo[$x]==1){

                          $cllab_resultadoalfa->la40_c_valor=$aValores[$x];
                          $cllab_resultadoalfa->la40_i_valorrefsel="";

                       }else{

                          $cllab_resultadoalfa->la40_i_valorrefsel=$aValores[$x];
                          $cllab_resultadoalfa->la40_c_valor="";

                       }
                       $cllab_resultadoalfa->la40_i_codigo=$la40_i_codigo;
    	               $cllab_resultadoalfa->alterar($la40_i_codigo);
    	               if($cllab_resultadoalfa->erro_status=="0"){

    	            	 $cllab_resultadoitem->erro_status="0";
    	            	 $cllab_resultadoitem->erro_sql   = $cllab_resultadoalfa->erro_sql;
                         $cllab_resultadoitem->erro_campo = $cllab_resultadoalfa->erro_campo;
                         $cllab_resultadoitem->erro_banco = $cllab_resultadoalfa->erro_banco;
                         $cllab_resultadoitem->erro_msg   = $cllab_resultadoalfa->erro_msg;

                       }

    	            }
    	            if($aTipo[$x]==3){

                       $cllab_resultadonum->la41_f_valor=$aValores[$x];
                       $sSql=$cllab_resultadonum->sql_query_file(""," la41_i_codigo ",""," la41_i_result=$la39_i_codigo ");
    	               $rResult=$cllab_resultadonum->sql_record($sSql);
	   	   	           db_fieldsmemory($rResult,0);
                       $cllab_resultadonum->la41_i_codigo=$la41_i_codigo;
	   	   	           $cllab_resultadonum->alterar($la41_i_codigo);
    	               if($cllab_resultadonum->erro_status=="0"){

    	              	 $cllab_resultadoitem->erro_status="0";
    	              	 $cllab_resultadoitem->erro_sql   = $cllab_resultadonum->erro_sql;
                         $cllab_resultadoitem->erro_campo = $cllab_resultadonum->erro_campo;
                         $cllab_resultadoitem->erro_banco = $cllab_resultadonum->erro_banco;
                         $cllab_resultadoitem->erro_msg   = $cllab_resultadonum->erro_msg;

    	               }

    	            }
    	         }else{

    	      	    $cllab_resultado->erro_status="0";
    	      	    $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
                    $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
                    $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
                    $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    	         }
	   	   	  }else{
	   	   	  	 $cllab_resultadoitem->erro_status="1";
	   	   	  	 if($cllab_resultadoitem->erro_status!="0"){

	   	   	       $cllab_resultadoitem->la39_i_resultado=$objParam->la52_i_codigo;
	   	   	  	   $cllab_resultadoitem->la39_i_atributo=$aAtributos[$x];
    	           $cllab_resultadoitem->incluir(null);
    	           if($cllab_resultadoitem->erro_status!="0"){
    	             if(($aTipo[$x]==1)||($aTipo[$x]==2)){
                       $cllab_resultadoalfa->la40_i_result=$cllab_resultadoitem->la39_i_codigo;
                       if($aTipo[$x]==1){

                         $cllab_resultadoalfa->la40_c_valor=$aValores[$x];
                         $cllab_resultadoalfa->la40_i_valorrefsel="";

                       }else{

                         $cllab_resultadoalfa->la40_i_valorrefsel=$aValores[$x];
                         $cllab_resultadoalfa->la40_c_valor="";

                       }
    	               $cllab_resultadoalfa->incluir(null);
    	               if($cllab_resultadoalfa->erro_status=="0"){

    	                 $cllab_resultadoitem->erro_status="0";
    	                 $cllab_resultadoitem->erro_sql   = $cllab_resultadoalfa->erro_sql;
                         $cllab_resultadoitem->erro_campo = $cllab_resultadoalfa->erro_campo;
                         $cllab_resultadoitem->erro_banco = $cllab_resultadoalfa->erro_banco;
                         $cllab_resultadoitem->erro_msg   = $cllab_resultadoalfa->erro_msg;

                       }

    	             }
    	             if($aTipo[$x]==3){

    	   	            $cllab_resultadonum->la41_i_result=$cllab_resultadoitem->la39_i_codigo;;
                        $cllab_resultadonum->la41_f_valor=$aValores[$x];
                        $cllab_resultadonum->incluir(null);
    	                if($cllab_resultadonum->erro_status=="0"){

    	             	   $cllab_resultadoitem->erro_status="0";
    	             	   $cllab_resultadoitem->erro_sql   = $cllab_resultadonum->erro_sql;
                           $cllab_resultadoitem->erro_campo = $cllab_resultadonum->erro_campo;
                           $cllab_resultadoitem->erro_banco = $cllab_resultadonum->erro_banco;
                           $cllab_resultadoitem->erro_msg   = $cllab_resultadonum->erro_msg;

    	                }

    	             }
    	           }else{

    	      	    $cllab_resultado->erro_status="0";
    	      	    $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
                    $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
                    $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
                    $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    	           }
    	         }else{

    	   	       $cllab_resultado->erro_status="0";
    	      	   $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
                   $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
                   $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
                   $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    	         }
	   	   	  }
    	   }else{

    	   	     $cllab_resultado->erro_status="0";
    	      	 $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
                 $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
                 $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
                 $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    	   }
       }
    }
    if($cllab_resultadoitem->erro_status=="0"){

       $cllab_resultado->erro_status="0";
       $cllab_resultado->erro_sql   = $cllab_resultadoitem->erro_sql;
       $cllab_resultado->erro_campo = $cllab_resultadoitem->erro_campo;
       $cllab_resultado->erro_banco = $cllab_resultadoitem->erro_banco;
       $cllab_resultado->erro_msg   = $cllab_resultadoitem->erro_msg;

    }
    if($cllab_resultado->erro_status!="0"){

       $cllab_requiitem->la21_c_situacao="2 - Lancado";
       $cllab_requiitem->la21_i_codigo=$objParam->iRequiitem;
       $cllab_requiitem->alterar($objParam->iRequiitem);
       if($cllab_requiitem->erro_status=="0"){

          $cllab_resultado->erro_status="0";
    	  $cllab_resultado->erro_sql   = $cllab_requiitem->erro_sql;
          $cllab_resultado->erro_campo = $cllab_requiitem->erro_campo;
          $cllab_resultado->erro_banco = $cllab_requiitem->erro_banco;
          $cllab_resultado->erro_msg   = $cllab_requiitem->erro_msg;

       }

    }
    if($cllab_resultado->erro_status=="0"){
    	$objRetorno->status  = 0;
    }
    $objRetorno->message = $cllab_resultado->erro_msg;
    db_fim_transacao($cllab_resultado->erro_status=="0");
}

$objRetorno->erro = $objRetorno->status == 1;
echo $objJson->encode($objRetorno);

function crossUrlDecode($sSource) {

 // Troco os caracteres especiais por pelo coringa
 $aOrig   = array('á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù', 'ç',
                  'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Ô', 'Ã', 'Õ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ç'
                 );

 return str_replace($aOrig, '_', mb_convert_encoding($sSource, "ISO-8859-1", "UTF-8"));

}