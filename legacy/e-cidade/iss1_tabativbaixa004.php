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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cltabativbaixa    = new cl_tabativbaixa;
$cltabativ         = new cl_tabativ;
$clativprinc       = new cl_ativprinc;
$clissbase         = new cl_issbase;
$clarreinscr       = new cl_arreinscr;
$clparissqn        = new cl_parissqn;
$clcertbaixanumero = new cl_certbaixanumero;
$cllogbaixaalvara  = new logbaixaalvara;

$db_opcao = 1;
$db_botao = true;
$ano      = db_getsession("DB_anousu");

if (isset($baixar)) {

  $proibido=false;
  $sqlerro=false;
  $data=date('Y-m-d',db_getsession("DB_datausu"));
  $matriz01=split('#',$chaves);
  $ativbaix=sizeof($matriz01);

  $result_ativs = $cltabativ->sql_record($cltabativ->sql_query_atividade_inscr($q07_inscr,"*","q07_seq","q07_inscr = $q07_inscr and q07_databx is null"));

  $ativs=$cltabativ->numrows;
  if ($ativs==$ativbaix){//2

    $cancelabaixa=false;
    //verifica se tem debitos ..exceto tipo 2,9,19
    $clarreinscr->sql_record($clarreinscr->sql_query_arrecad("","","arrecad.k00_numpre","","k00_inscr=$q07_inscr and k00_dtvenc < '$data' and k03_tipo not in (2,9,19)"));
    if($clarreinscr->numrows>0) {//3
      $cancelabaixa= true;
    }else{//4

      // verificar se tem debitos do tipo 3 vencidos
      $sqldebito3 = "
		      select arrecad.k00_numpre
				from arreinscr
				inner join issbase on issbase.q02_inscr = arreinscr.k00_inscr
				inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm
				inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
				inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
				where  k00_inscr= $q07_inscr
				   and k00_dtvenc < current_date
				   and k03_tipo = 3";

      $resultdebito3 = db_query($sqldebito3);
      $linhasdebito3 = pg_num_rows($resultdebito3);
      if($linhasdebito3>0){//5
        $cancelabaixa= true;
      }

    }

    if($q11_oficio=="false"){//6

      $result_param = $clparissqn->sql_record($clparissqn->sql_query_file());

      if ($clparissqn->numrows>0){//7
        db_fieldsmemory($result_param,0);

      }
      if ((!isset($q60_alvbaixadiv))||(isset($q60_alvbaixadiv)&&@$q60_alvbaixadiv!='1')){//8
        if($cancelabaixa==true){//9

          $proibido=true;
          $mensagem_debito="Operação cancelada.\\n Existem débitos para este contribuinte!";
        }
      }
    }else{//10
      if($cancelabaixa==true){//11
        $mensagem_debito="Existem débitos para este contribuinte!";
      }
    }
  }

  if($proibido==false){

    db_inicio_transacao();
    $matriz01=split('#',$chaves);
    if($calculo=="ok"){
      $seqs="";
      $virgu="";
      for($q=0; $q<sizeof($matriz01); $q++ ){
        $matriz=split("-",$matriz01[$q]);
        $seqs.=$virgu.$matriz[1];
        $virgu=",";
      }

      $ano=db_getsession("DB_anousu");
      $instit=db_getsession('DB_instit');
      $q07_databx="$q07_databx_ano-$q07_databx_mes-$q07_databx_dia";
      $sql02 = "SELECT fc_issqn($q07_inscr,'".date('Y-m-d',db_getsession("DB_datausu"))."',$ano,'$q07_databx','true','false',".$instit.",'".$seqs."') AS RETORNO";

      $result02=db_query($sql02) or die($sql02);
      @db_fieldsmemory($result02,0);
      if(isset($retorno) && (substr($retorno,0,2) == "01" or substr($retorno,0,2) == "24")){
        $trans_calculo=true;
      }else{
        $trans_calculo=false;
        $sqlerro=true;
      }
    }

    $result_param = $clparissqn->sql_record($clparissqn->sql_query_file());
    if ($clparissqn->numrows>0){
      db_fieldsmemory($result_param,0);
    }

if ($sqlerro==false){

  if ($q60_tiponumcertbaixa==1){

    $q11_numero=$q11_processo;

  }elseif( $q60_tiponumcertbaixa == 2 || $q60_tiponumcertbaixa == 3 ){

    $result_certbaixanumero = $clcertbaixanumero->sql_record($clcertbaixanumero->sql_query_file(null,"q79_sequencial,q79_anousu,q79_ultcodcertbaixa",null,"q79_anousu=$ano"));
    if ($clcertbaixanumero->numrows > 0){

      db_fieldsmemory($result_certbaixanumero,0);
      $q11_numero=$q79_ultcodcertbaixa+1;
    }else{

      $sqlerro = true;
      $sMsgErro = "Não encontrada configuração na numeração de certidão de baixa.";
    }

  }
}

if ($sqlerro==false){

    for($q=0; $q<sizeof($matriz01); $q++ ){

      $matriz=split("-",$matriz01[$q]);
      $cltabativbaixa->q11_login=db_getsession("DB_id_usuario");
      $cltabativbaixa->q11_hora=db_hora();
      $cltabativbaixa->q11_data=date('Y-m-d',db_getsession("DB_datausu"));
      $cltabativbaixa->q11_obs=$q11_obs;

      if($q11_processo==""){
        $cltabativbaixa->q11_processo='null';
      }else{
        $cltabativbaixa->q11_processo=$q11_processo;
      }

      if($q60_tiponumcertbaixa==3){
        $q11_numeroanousu=$q11_numero."/".$q79_anousu;
      }else{
        $q11_numeroanousu=$q11_numero;
      }

      $cltabativbaixa->q11_oficio=$q11_oficio;
      $cltabativbaixa->q11_seq=$matriz[1];
      $cltabativbaixa->q11_inscr=$q07_inscr;
      $cltabativbaixa->q11_numero=$q11_numeroanousu;
      $cltabativbaixa->incluir($q07_inscr,$matriz[1]);
      if($cltabativbaixa->erro_status == 0){
        $sqlerro = true;
      }

      if(!$sqlerro && ($q60_tiponumcertbaixa == 2 || $q60_tiponumcertbaixa == 3) ){

        $data=date("Y-m-d",db_getsession("DB_datausu"));
        $clcertbaixanumero->q79_sequencial=$q79_sequencial;
        $clcertbaixanumero->q79_ultcodcertbaixa=$q11_numero;
        $clcertbaixanumero->alterar($clcertbaixanumero->q79_sequencial);
        if($clcertbaixanumero->erro_status==0){
          $sqlerro = true;
        }
      }


      if(!$sqlerro){

        $data=date("Y-m-d",db_getsession("DB_datausu"));
        $cltabativ->q07_seq=$matriz[1];
        $cltabativ->q07_inscr=$q07_inscr;
        $cltabativ->q07_datafi_dia=$q07_databx_dia;
        $cltabativ->q07_datafi_mes=$q07_databx_mes;
        $cltabativ->q07_datafi_ano=$q07_databx_ano;
        $cltabativ->q07_databx_dia=$q07_databx_dia;
        $cltabativ->q07_databx_mes=$q07_databx_mes;
        $cltabativ->q07_databx_ano=$q07_databx_ano;
        $cltabativ->alterar($q07_inscr,$matriz[1]);
        if($cltabativ->erro_status==0){
          $sqlerro = true;
        }
      }
      if(!$sqlerro){
        if($matriz[0]=="*"){

          $clativprinc->q88_inscr=$q07_inscr;
          $clativprinc->q88_seq=$matriz[1];
          $clativprinc->excluir($q88_inscr);
          if($clativprinc->erro_status==0){
            $sqlerro=true;
          }else{
            $ativprinc=true;
          }
        }
      }
    }
  }
    if(!$sqlerro){
      $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,"","q07_inscr","","q07_databx is null and q07_inscr=$q07_inscr"));
      if($cltabativ->numrows<1){
        $clissbase->q02_dtbaix_dia=$q07_databx_dia;
        $clissbase->q02_dtbaix_mes=$q07_databx_mes;
        $clissbase->q02_dtbaix_ano=$q07_databx_ano;
        $clissbase->q02_inscr=$q07_inscr;
        $clissbase->alterar($q07_inscr);
        if($clissbase->erro_status==0){
          $sqlerro=true;
        }else{
          $inscr_baixa=true;
        }
      }
    }

	  if (!$sqlerro) {


      $matriz01=split('#',$chaves);
      $seqs="";
      $virgu="";
      for($q=0; $q<sizeof($matriz01); $q++ ){

        $matriz = split("-",$matriz01[$q]);
        $seqs  .= $virgu.$matriz[1];
        $virgu  = ",";
      }

	  	$sWhere       = "q07_inscr = {$q07_inscr} and q07_seq in ({$seqs})";
	  	$sSqlTabAtiv  = $cltabativ->sql_query(null,null,"tabativ.*","q07_seq",$sWhere);
	  	$rsSqlTabAtiv = $cltabativ->sql_record($sSqlTabAtiv);
	  	if ($cltabativ->numrows > 0) {

	  		for ($iInd = 0; $iInd < $cltabativ->numrows; $iInd++) {

	  			$oTabAtiv = db_utils::fieldsMemory($rsSqlTabAtiv,$iInd);
			  	try {

		        if (isset($oTabAtiv->q07_ativ) && !empty($oTabAtiv->q07_ativ)) {

		        	if (isset($q11_oficio) && $q11_oficio == 'false') {
		        	  $cllogbaixaalvara->identificaAlteracao($q07_inscr,1,6,$oTabAtiv->q07_ativ);
		        	} else {
		        		$cllogbaixaalvara->identificaAlteracao($q07_inscr,1,7,$oTabAtiv->q07_ativ);
		        	}
		        }

		      } catch ( Exception $eExeption ){

		        $sqlerro   = true;
		        $sMsgErro  = $eExeption->getMessage();
		      }
	  		}

  	    try {
          $cllogbaixaalvara->gravarLog();
        } catch ( Exception $eExeption ){

          $sqlerro  = true;
          $sMsgErro = $eExeption->getMessage();
        }
		  }
		}

    db_fim_transacao($sqlerro);

    if(!$sqlerro){
      unset($q11_processo);
      unset($q11_oficio);
    }
  }

// BAIXA DE ALVARA AUTOMATICA CASO SEJA BAIXADO A INSCRIÇÂO
  $sSqlAlvara = "select * from issalvara where q123_inscr = {$oPost->q07_inscr}";
  $rsAlvara   = db_query($sSqlAlvara) ;
  if (pg_num_rows($rsAlvara) > 0) {

  	db_fieldsmemory($rsAlvara,0);
	  $oLiberarAlvara  = new AlvaraMovimentacaoBaixa($q123_sequencial);
	  try {

	    db_inicio_transacao();
	    $oLiberarAlvara->setTipoMovimentacao(2);
	    $oLiberarAlvara->setDataMovimentacao( date("d-m-Y", db_getsession("DB_datausu")));
	    $oLiberarAlvara->setCodigoProcesso($oPost->q11_processo);
	    $oLiberarAlvara->setObservacao($oPost->q11_obs);
	    $oLiberarAlvara->baixar(3, $q123_sequencial);

	    db_fim_transacao(false);
	  } catch (ErrorException $erro) {

	    db_msgbox($erro->getMessage().$erro->getLine().$erro->getFile());
	    db_fim_transacao(true);
	  }

  }else {
  	db_msgbox("Não Existe Alvara Gerado para esta Inscrição");
  }

} else if(isset($q07_inscr) && $q07_inscr !=""){

  $result09=$clissbase->sql_record($clissbase->sql_query_file($q07_inscr,"q02_dtbaix"));
  db_fieldsmemory($result09,0);
  if($q02_dtbaix!=""){
    $baixada=$q07_inscr;
    unset($q07_inscr);
    if(isset($z01_nome)){
      unset($z01_nome);
    }
  }

}
if(empty($sqlerro) || $sqlerro=false){
  $load="onLoad='document.form1.q07_inscr.focus();'";
}else{
  $load="";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css"); ?>
</head>
<body class="body-default" <?=$load?>>
  <div class="container">
			<?php
			  require_once("forms/db_frmtabativbaixa.php");
			?>
  </div>
 <?php
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 ?>
</body>
</html>
<?php
  /** Extensao : Inicio [BloqueioManutencaoInscricaoSistemaExterno] */
  /** Extensao : Fim [BloqueioManutencaoInscricaoSistemaExterno] */

	if(isset($baixada)){
	  db_msgbox("Inscrição $baixada baixada.");
	}
	if (isset($sMsgErro) && !empty($sMsgErro)) {
		unset($mensagem_debito);
		$proibido = true;
    db_msgbox($sMsgErro);

  }
	if(isset($mensagem_debito)){
	  db_msgbox($mensagem_debito);
	}

	if(isset($baixar) && $proibido==false){
	  if(isset($trans_calculo)){
	    if($trans_calculo==true){
	      $cltabativbaixa->erro(true,false);
	      db_msgbox('Calculo efetuado com sucesso!');
	    }else{
	      db_msgbox("Operação cancelada. \\nOcorreu algum problema durante o calculo!\\n Mensagem retornada:$retorno");
	    }
	  }else{
	    $cltabativbaixa->erro(true,false);
	  }
	}
?>