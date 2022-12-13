<?
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_numprebloqpag_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrenumcgm_classe.php");
require_once("classes/db_divida_classe.php");
require_once("classes/db_diversos_classe.php");
require_once("classes/db_termo_classe.php");
require_once("classes/db_inicialnomes_classe.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$clarrecad      = new cl_arrecad();
$clarrenumcgm   = new cl_arrenumcgm();
$cldivida       = new cl_divida();
$cldiversos     = new cl_diversos();
$clinicialnomes = new cl_inicialnomes();
$cltermo        = new cl_termo();


switch ($oParam->exec) {

  case "getNumpresTipoCgm":

	  $sCampos =  " distinct                                                                                            ";
    $sCampos .= " arrecad.k00_numpre,                                                                                 ";
    $sCampos .= " arrecad.k00_tipo,                                                                                   ";
    $sCampos .= " arretipo.k00_descr,                                                                                 ";

    $sCampos .= " case                                                                                                ";
    $sCampos .= "     when arretipo.k03_tipo in (12,18)   then ( select 'Inicial: '||inicialnumpre.v59_inicial        ";
    $sCampos .= "                                                  from inicialnumpre                                 ";
    $sCampos .= "                                                 where inicialnumpre.v59_numpre = arrecad.k00_numpre ";
    $sCampos .= "                                                 limit 1) ";

    $sCampos .= "     when arretipo.k03_tipo = 15         then ( select 'Certidao: '||(select certter.v14_certid      ";
    $sCampos .= "                                                  from certter                                       ";
    $sCampos .= "                                                 inner join termo on termo.v07_parcel = certter.v14_parcel ";
    $sCampos .= "                                                 where termo.v07_numpre = arrecad.k00_numpre         ";
    $sCampos .= "                                                 union                                               ";
    $sCampos .= "                                               select certdiv.v14_certid                             ";
    $sCampos .= "                                                 from certdiv                                        ";
    $sCampos .= "                                                inner join divida on divida.v01_numpre = arrecad.k00_numpre ";
    $sCampos .= "                                                limit 1 ) )                                          ";

    $sCampos .= "    when arretipo.k03_tipo in (6,13,16) then ( select 'Parc.: - '||termo.v07_parcel                  ";
    $sCampos .= "                                                 from termo                                          ";
    $sCampos .= "                                                where termo.v07_numpre = arrecad.k00_numpre limit 1) ";

   /*
    $sCampos = ."    when arretipo.k03_tipo = 5          then (select 'Divida: - '||divida.v01_coddiv                 ";
    $sCampos = ."                                                from divida                                          ";
    $sCampos = ."                                               where divida.v01_numpre = arrecad.k00_numpre          ";
    $sCampos = ."                                                 and divida.v01_numpar = arrecad.k00_numpar limit 1) ";
   */

    $sCampos .= "    when arretipo.k03_tipo = 7          then (select 'Diverso: -'||diversos.dv05_coddiver            ";
    $sCampos .= "                                                from diversos                                        ";
    $sCampos .= "                                               where dv05_numpre = arrecad.k00_numpre limit 1)       ";

    $sCampos .= " end as obs                                                                                          ";

	  $sOrdem  = " arrecad.k00_tipo, arrecad.k00_numpre";

	  $sWhere  = " arrenumcgm.k00_numcgm = {$oParam->iNumcgm}";

    $rsNumpre = $clarrecad->sql_record($clarrecad->sql_query_info(null,$sCampos,$sOrdem,$sWhere));
	  if ($rsNumpre) {
	    $aRegistros = db_utils::getCollectionByRecord($rsNumpre,0,false,false,true);
	  } else {
	    $sMensagem  = "Nenhum dado retornado";
	    $iStatus    = 2;
	    $aRegistros = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));
	  }

	  $oRetorno = new stdClass();
	  $oRetorno->aRegistros = $aRegistros;
	  echo $oJson->encode($oRetorno);

	break;

  case "processaTransferenciaNumpre":

    $oRetorno    = new stdClass();
  	$iCgmOrigem  = $oParam->iOrigem;
  	$iCgmDestino = $oParam->iDestino;
  	$aNumpre     = explode(",",$oParam->sNumPres);

  	try {
  	  db_inicio_transacao();
  	  for ($i=0; $i < count($aNumpre); $i++){

    	  if ($oParam->lProcessaOrigem == 'true') {

    	    //Verificamos o cadtipo do numpre
          $sSqlCadtipo  = " select k03_tipo                                              ";
          $sSqlCadtipo .= "  from arrecad                                                ";
          $sSqlCadtipo .= " inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo  ";
          $sSqlCadtipo .= " where arrecad.k00_numpre = {$aNumpre[$i]} limit 1            ";

          $rsCadtipo = db_query($sSqlCadtipo);
          if (pg_num_rows($rsCadtipo) == 0) {
            throw new Exception("Erro ao processar transferência. Nenhum cadtipo encontrado para o numpre");
          }

          $iCadTipo = db_utils::fieldsmemory($rsCadtipo, 0)->k03_tipo;

          /*
           * Tabela abaixo mostra todos os cadtipo cadastrados no nosso banco de dados que estão ou não
           * implementados para alterar a origem do débito na transferência
           *
           *  Tipo | Descrição                         | Implementado
           * ------+-----------------------------------+---------------
           *     0 | NAO INFORMADO                     | --
           *     1 | I.P.T.U                           | --
           *     2 | ISSQN FIXO                        | --
           *     3 | ISSQN VARIAVEL                    | --
           *     4 | CONTRIBUICAO DE MELHORIA          | --
           *     5 | DIVIDA ATIVA                      | OK
           *     6 | PARCELAMENTO DIVIDA ATIVA         | OK
           *     7 | DIVERSOS                          | OK
           *     8 | I.T.B.I                           | --
           *     9 | ALVARA                            | --
           *    10 | NOTIFICACAO FISCAL                | --
           *    11 | AUTO DE INFRACAO FISCAL           | --
           *    12 | INICIAL DE DIVIDA ATIVA           | OK
           *    13 | PARCELAMENTO DE INICIAL D. ATIVA  | OK
           *    14 | PROTOCOLO GERAL                   | --
           *    15 | CERTIDAO DO FORO                  | OK
           *    16 | PARCELAMENTO DIVERSO              | OK
           *    17 | PARCELAMENTO DE CONTRIB. MELHORIA | OK
           *    18 | INICIAL FORO                      | OK
           *    19 | VISTORIAS                         | --
           *    20 | SANEAMENTO BASICO                 | --
           *    21 | CEMITERIO                         | --
           */


          switch ($iCadTipo) {

            //5 - DIVIDA ATIVA
            case 5:

              //Buscamos as dividas que possuem o numpre que está sendo transferido para serem alterados
              $rsDivida = $cldivida->sql_record($cldivida->sql_query_file(null, "v01_coddiv","v01_coddiv","v01_numpre = {$aNumpre[$i]}"));
              for ( $x = 0; $x < pg_num_rows($rsDivida); $x++){
                $iCoddiv  = db_utils::fieldsmemory($rsDivida,$x)->v01_coddiv;
                $cldivida->v01_coddiv = $iCoddiv;
                $cldivida->v01_numcgm = $iCgmDestino;
                $cldivida->alterar($iCoddiv);
                if ($cldivida->erro_status == "0") {
                  throw new Exception("Erro ao processar transferência. - ".$cldivida->erro_msg);
                }
              }

            break;

            //7 - DIVERSOS
            case 7:

              //buscamos os diversos que possuem o numpre que está sendo transferido para serem alterados
              $rsDiversos = $cldiversos->sql_record($cldiversos->sql_query_file(null, "dv05_coddiver","", "dv05_numpre = {$aNumpre[$i]}"));
              for ( $x = 0; $x < pg_num_rows($rsDiversos); $x++){
                $iDiverso  = db_utils::fieldsmemory($rsDiversos,$x)->dv05_coddiver;
                $cldiversos->dv05_coddiver = $iDiverso;
                $cldiversos->dv05_numcgm   = $iCgmDestino;
                $cldiversos->alterar($iDiverso);
                if ($cldiversos->erro_status == "0") {
                  throw new Exception("Erro ao processar transferência. - ".$cldiversos->erro_msg);
                }
              }

            break;

            //12 - INICIAL DE DIVIDA ATIVA
            //18 - INICIAL FORO
            case 12:
            case 18:

              $sSqlInicial  = " select v59_inicial,                                                                  ";
              $sSqlInicial .= "        v01_coddiv,                                                                   ";
              $sSqlInicial .= "        v07_parcel,                                                                   ";
              $sSqlInicial .= "        ( select array_accum(v59_numpre)                                              ";
              $sSqlInicial .= "            from inicialnumpre as x                                                   ";
              $sSqlInicial .= "           where x.v59_inicial = inicialnumpre.v59_inicial ) as inicial_numpre        ";
              $sSqlInicial .= "  from inicialnumpre                                                                  ";
              $sSqlInicial .= " inner join inicialcert on inicialnumpre.v59_inicial = inicialcert.v51_inicial        ";
              $sSqlInicial .= "  left join certdiv     on inicialcert.v51_certidao  = certdiv.v14_certid             ";
              $sSqlInicial .= "  left join divida      on certdiv.v14_coddiv        = divida.v01_coddiv              ";
              $sSqlInicial .= "  left join certter     on inicialcert.v51_certidao  = certter.v14_certid             ";
              $sSqlInicial .= "  left join termo       on certter.v14_parcel        = termo.v07_parcel               ";
              $sSqlInicial .= " where inicialnumpre.v59_numpre = {$aNumpre[$i]}                                      ";

              $rsInicial = db_query($sSqlInicial);
              for ($x = 0; $x < pg_num_rows($rsInicial); $x++) {
                $iCoddiv         = db_utils::fieldsmemory($rsInicial,$x)->v01_coddiv;
                $iParcel         = db_utils::fieldsmemory($rsInicial,$x)->v07_parcel;
                $iInicial        = db_utils::fieldsmemory($rsInicial,$x)->v59_inicial;
                $aInicialNumpre  = explode(",",str_replace("{","",str_replace("}","",db_utils::fieldsmemory($rsInicial,$x)->inicial_numpre)));

                /*
                 * Verificamos se todos os numpres da inicial foram marcados
                 * Caso não tenham sido todos os numpres marcados, retornamos com erro
                 *
                 */
                for ($y = 0; $y < count($aInicialNumpre); $y++) {
                  if (!in_array($aInicialNumpre[$y],$aNumpre)) {
                    throw new Exception("Erro ao processar transferência. - Todos os numpres da Inicial {$iInicial} precisam ser marcados");
                  }
                }

                if (!empty($iParcel)) {
                  $cltermo->v07_parcel = $iParcel;
                  $cltermo->v07_numcgm = $iCgmDestino;
                  $cltermo->alterar($iParcel);
                  if ($cltermo->erro_status == "0") {
                    throw new Exception("Erro ao processar transferência. - ".$cltermo->erro_msg);
                  }
                }

                if (!empty($iCoddiv)) {
                  $cldivida->v01_coddiv = $iCoddiv;
                  $cldivida->v01_numcgm = $iCgmDestino;
                  $cldivida->alterar($iCoddiv);
                  if ($cldivida->erro_status == "0") {
                    throw new Exception("Erro ao processar transferência. - ".$cldivida->erro_msg);
                  }
                }

              }

              //Excluímos os registros da arrenumcgm do numpre e incluímos novamente com o cgm de destino
              $clinicialnomes->excluir($iInicial);
              if ($clinicialnomes->erro_status == "0") {
                throw new Exception("Erro ao processar transferência. - ".$clarrenumcgm->erro_msg);
              }

              $clinicialnomes->incluir($iInicial, $iCgmDestino);
              if ($clinicialnomes->erro_status == "0") {
                throw new Exception("Erro ao processar transferência. - ".$clarrenumcgm->erro_msg);
              }


            break;

            //15 - CERTIDAO DO FORO
            case 15:

              $sSqlCertidao  = " select v13_certid,                                                               ";
              $sSqlCertidao .= "        v01_coddiv,                                                               ";
              $sSqlCertidao .= "        v07_parcel,                                                               ";
              $sSqlCertidao .= "        (select array_accum(v01_numpre)                                           ";
              $sSqlCertidao .= "           from certdiv                                                           ";
              $sSqlCertidao .= "          inner join divida on v01_coddiv = v14_coddiv                            ";
              $sSqlCertidao .= "          where v14_certid = certid.v13_certid ) as certdiv_numpre,               ";
              $sSqlCertidao .= "        (select array_accum(v07_numpre)                                           ";
              $sSqlCertidao .= "           from certter                                                           ";
              $sSqlCertidao .= "          inner join termo on v07_parcel = v14_parcel                             ";
              $sSqlCertidao .= "          where v14_certid = certid.v13_certid                                    ";
              $sSqlCertidao .= "        ) as certter_numpre                                                       ";
              $sSqlCertidao .= "   from certid                                                                    ";
              $sSqlCertidao .= "   left join certdiv     on certid.v13_certid         = certdiv.v14_certid        ";
              $sSqlCertidao .= "   left join divida      on certdiv.v14_coddiv        = divida.v01_coddiv         ";
              $sSqlCertidao .= "   left join certter     on certid.v13_certid         = certter.v14_certid        ";
              $sSqlCertidao .= "   left join termo       on certter.v14_parcel        = termo.v07_parcel          ";
              $sSqlCertidao .= "  where termo.v07_numpre = {$aNumpre[$i]}                                         ";
              $sSqlCertidao .= "     or divida.v01_numpre = {$aNumpre[$i]}                                        ";
              $sSqlCertidao .= "  group by v13_certid,                                                            ";
              $sSqlCertidao .= "           v01_coddiv,                                                            ";
              $sSqlCertidao .= "           v07_parcel                                                             ";

              $rsCertidao = db_query($sSqlCertidao);
              for ($x = 0; $x < pg_num_rows($rsCertidao); $x++) {
                $iCoddiv          = db_utils::fieldsmemory($rsCertidao,$x)->v01_coddiv;
                $iParcel          = db_utils::fieldsmemory($rsCertidao,$x)->v07_parcel;
                $iCertidao        = db_utils::fieldsmemory($rsCertidao,$x)->v13_certid;
                $aCertDivNumpre   = explode(",",str_replace("{","",str_replace("}","",db_utils::fieldsmemory($rsCertidao,$x)->certdiv_numpre)));
                $aCertterNumpre   = explode(",",str_replace("{","",str_replace("}","",db_utils::fieldsmemory($rsCertidao,$x)->certter_numpre)));
                $aCertidaoNumpre  = array_merge($aCertDivNumpre,$aCertterNumpre);
                /*
                 * Verificamos se todos os numpres da inicial foram marcados
                 * Caso não tenham sido todos os numpres marcados, retornamos com erro
                 *
                 */
                for ($y = 0; $y < count($aCertidaoNumpre); $y++) {

                  if ($aCertidaoNumpre[$y] == "") {
                    continue;
                  }

                  if (!in_array($aCertidaoNumpre[$y],$aNumpre)) {
                    throw new Exception("Erro ao processar transferência. - Todos os numpres da Certidao {$iCertidao} precisam ser marcados");
                  }

                }

                if (!empty($iParcel)) {
                  $cltermo->v07_parcel = $iParcel;
                  $cltermo->v07_numcgm = $iCgmDestino;
                  $cltermo->alterar($iParcel);
                  if ($cltermo->erro_status == "0") {
                    throw new Exception("Erro ao processar transferência. - ".$cltermo->erro_msg);
                  }
                }

                if (!empty($iCoddiv)) {
                  $cldivida->v01_coddiv = $iCoddiv;
                  $cldivida->v01_numcgm = $iCgmDestino;
                  $cldivida->alterar($iCoddiv);
                  if ($cldivida->erro_status == "0") {
                    throw new Exception("Erro ao processar transferência. - ".$cldivida->erro_msg);
                  }
                }

              }

            break;


            //6  - PARCELAMENTO DIVIDA ATIVA
            //13 - PARCELAMENTO DE INICIAL D. ATIVA
            //16 - PARCELAMENTO DIVERSO
            //17 - PARCELAMENTO DE CONTRIB. MELHORIA
            case 6:
            case 13:
            case 16:
            case 17:

              //Buscamos o código do parcelamento para alterarmos o termo para o CGM de destino
              $rsParcel = $cltermo->sql_record($cltermo->sql_query_file(null, "v07_parcel", "", "v07_numpre = {$aNumpre[$i]}"));
              $iParcel  = db_utils::fieldsmemory($rsParcel,0)->v07_parcel;
              $cltermo->v07_parcel = $iParcel;
              $cltermo->v07_numcgm = $iCgmDestino;
              $cltermo->alterar($iParcel);
              if ($cltermo->erro_status == "0") {
                throw new Exception("Erro ao processar transferência. - ".$cltermo->erro_msg);
              }

            break;
          }
    	  }

    	  //Alteramos o Arrecad do débito passando para o novo cgm
        $clarrecad->k00_numpre = $aNumpre[$i];
        $clarrecad->k00_numcgm = $iCgmDestino;
        $clarrecad->alterar_arrecad("k00_numpre = {$aNumpre[$i]}");
        if ($clarrecad->erro_status == "0") {
          throw new Exception("Erro ao processar transferência. - ".$clarrecad->erro_msg);
        }

        //Excluímos os registros da arrenumcgm do numpre e incluímos novamente com o cgm de destino
        $clarrenumcgm->excluir(null,$aNumpre[$i]);
        if ($clarrenumcgm->erro_status == "0") {
          throw new Exception("Erro ao processar transferência. - ".$clarrenumcgm->erro_msg);
        }
        $clarrenumcgm->incluir($iCgmDestino, $aNumpre[$i]);
        if ($clarrenumcgm->erro_status == "0") {
          throw new Exception("Erro ao processar transferência. - ".$clarrenumcgm->erro_msg);
        }
    	}
    	db_fim_transacao(false);
      echo $oJson->encode(array("status" => 1,"message"=> urlencode("Processamento efetuado com sucesso")));

  	} catch (Exception $eException){
      db_fim_transacao(true);
      echo $oJson->encode(array("status" => 2,"message"=> urlencode($eException->getMessage())));
    }
  break;
}
?>