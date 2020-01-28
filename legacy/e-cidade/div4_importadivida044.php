<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tabrec_classe.php");
require_once("classes/db_proced_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_arreinscr_classe.php");
require_once("classes/db_arreold_classe.php");
require_once("classes/db_divida_classe.php");
require_once("classes/db_divmatric_classe.php");
require_once("classes/db_divold_classe.php");
require_once ("classes/db_divimporta_classe.php");
require_once ("classes/db_divimportareg_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arrecadcompos_classe.php");
require_once("classes/db_arreckey_classe.php");
require_once("classes/db_dividaprotprocesso_classe.php");

db_postmemory($HTTP_POST_VARS);

$cltabrec               = new cl_tabrec;
$clarrecad              = new cl_arrecad;
$clarrematric           = new cl_arrematric;
$clarreinscr            = new cl_arreinscr;
$clarreold              = new cl_arreold;
$clproced               = new cl_proced;
$cldivida               = new cl_divida;
$cldivmatric            = new cl_divmatric;
$cldivold               = new cl_divold;
$cldivimporta           = new cl_divimporta;
$cldivimportareg        = new cl_divimportareg;
$clarrecadcompos        = new cl_arrecadcompos;
$clarreckey             = new cl_arreckey;
$oDaoDividaprotprocesso = new cl_dividaprotprocesso;

$teste	          = false;
$iInstit          = db_getsession("DB_instit");
$aTipos           = array(3, 7, 4, 11, 16, 17, 19);

$oProcesso        = db_getsession("oDadosProcesso");

$lProcessoSistema = $oProcesso->lProcessoSistema;
$iProcesso        = $oProcesso->iProcesso;
$sTitular         = $oProcesso->sTitular;
$dDataProcesso    = $oProcesso->dDataProcesso;

//variáveis retornadas por sessÃ£o do arquivo div4_importadivida002.php pois em alguns casos ultrapassavam o limite
$txt_where = @$_SESSION["where_divida"];
$txt_inner = @$_SESSION["inner_divida"];

if (empty($txt_where)) {

  $teste = true;
  $erro_msg = "Parametros inválidos!";
  $sqlerro  = true;
}

if (isset($cod_k02_codigo) && trim($cod_k02_codigo)!="" && isset($cod_v03_codigo) && trim($cod_v03_codigo)!="" && !empty($txt_where)) {

  $teste = true;


  $sSqlArrecad = $clarrecad->sql_query_info(null,"distinct
                                                  arrecad.k00_numcgm,
                                                  min(arrecad.k00_dtoper) as k00_dtoper,
                                                  arrecad.k00_receit,
                                                  arrecad.k00_dtvenc,
                                                  arrecad.k00_numpre,
                                                  arrecad.k00_numpar,
                                                  arrecad.k00_numtot,
                                                  arrecad.k00_numdig,
                                                  arrecad.k00_tipo,
                                                  arrecad.k00_tipojm,
                                                  ( select sum(k00_valor)
                                                      from arrecad as x
                                                     where x.k00_numpre = arrecad.k00_numpre
                                                       and x.k00_numpar = arrecad.k00_numpar
                                                       and x.k00_receit = arrecad.k00_receit ) as k00_valor,
                                                  arretipo.k03_tipo",
                                                  "arrecad.k00_numpre, arrecad.k00_receit",
                                                  " arreinstit.k00_instit = ".db_getsession('DB_instit')."
                                                    and arrecad.k00_tipo = $chave_origem $txt_where
                                                    group by arrecad.k00_numcgm,
                                                             arrecad.k00_receit,
                                                             arrecad.k00_dtvenc,
                                                             arrecad.k00_numpre,
                                                             arrecad.k00_numpar,
                                                             arrecad.k00_numtot,
                                                             arrecad.k00_numdig,
                                                             arrecad.k00_tipo,
                                                             arrecad.k00_tipojm,
                                                             arretipo.k03_tipo ");
  $result_pesq_divida = $clarrecad->sql_record( $sSqlArrecad );

  $numrows 	  = $clarrecad->numrows;
  $codigo_k02 = split(",", $cod_k02_codigo);
  $codigo_v03 = split(",", $cod_v03_codigo);
  $sqlerro	  = false;
  $dataini    = date("Y-m-d",db_getsession('DB_datausu'));
  $horaini    = db_hora();

  db_inicio_transacao();

  $numpre_par_rec = "";
  $erro_msg       = "";

  $cldivimporta->v02_usuario = db_getsession('DB_id_usuario');
  $cldivimporta->v02_instit  = db_getsession('DB_instit') ;
  $cldivimporta->v02_data 	 = $dataini;
  $cldivimporta->v02_hora 	 = $horaini;
  $cldivimporta->v02_tipo 	 = 1;
  $cldivimporta->v02_datafim = $dataini;
  $cldivimporta->v02_horafim = $horaini;
  $cldivimporta->incluir(null);

  if ($cldivimporta->erro_status == 0) {

  	db_msgbox($cldivimporta->erro_msg);
	  $sqlerro = true;
  }
  $iDivImporta = $cldivimporta->v02_divimporta;

  for ($i = 0; $i < $numrows;$i++ ) {

    db_fieldsmemory($result_pesq_divida,$i,true);
    /*
     * testamos se o valor é maior que zero para incluirmos as importações
     */
    if ($k00_valor <= 0) {
      continue;
    }
    for ($ii = 0; $ii < sizeof($codigo_k02); $ii++) {

      if ($k00_numtot == "") {
        $k00_numtot = 1;
      }

      $cod_k02_codigo = $codigo_k02[$ii];
      $cod_v03_codigo = $codigo_v03[$ii];
      if ($k00_receit == $cod_k02_codigo && $sqlerro==false) {

        if ($numpre_par_rec <> $k00_numpre . $k00_receit) {

          $sqljadivida = "select v03_receit as receita_nova,
	  	  	    					         v01_coddiv
                  			    from divida
                    			       inner join proced on v01_proced = v03_codigo
             	  		       where v01_numpre = $k00_numpre
							               and v01_numpar = $k00_numpar
							               and v01_instit = ".db_getsession('DB_instit') ;

	  	    $resultjadivida  = db_query($sqljadivida);
	  	    $jaexiste        = false;
          $numrowsjadivida = pg_numrows($resultjadivida);

          // Trata tipo de Debito 20 - Saneamento Basico
          if ($numrowsjadivida == 0 || ( $numrowsjadivida > 0 && $k03_tipo == 20) ) {

            $nextval_numpre = db_query("select nextval('numpref_k03_numpre_seq') as numpre_novo");
	    	    db_fieldsmemory($nextval_numpre, 0);

	        		// Trata tipo de Debito 20 - Saneamento Basico
			      if ($numrowsjadivida > 0 && $k03_tipo == 20) {

			        $sWhereDividaMatricula  = " divmatric.v01_coddiv in ( select divida.v01_coddiv                   ";
			        $sWhereDividaMatricula .= "                             from divida                              ";
			        $sWhereDividaMatricula .= "                            where divida.v01_numpre = {$k00_numpre}   ";
			        $sWhereDividaMatricula .= "                              and divida.v01_numpar = {$k00_numpar} ) ";
			        $cldivmatric->excluir(null, $sWhereDividaMatricula);

				      $sqldeletejadivida  = " delete  ";
				      $sqldeletejadivida .= "   from divida ";
              $sqldeletejadivida .= "  where divida.v01_numpre = {$k00_numpre} ";
							$sqldeletejadivida .= "    and divida.v01_numpar = {$k00_numpar} ";
 							$sqldeletejadivida .= " 	 and divida.v01_instit = ".db_getsession('DB_instit');
				      db_query($sqljadivida);
			      }

	        } else {

	          db_fieldsmemory($resultjadivida, 0);
	          $numpre_novo = $k00_numpre;
	          $jaexiste    = true;
	        }
 	        $numpre_par_rec = $k00_numpre . $k00_receit;
          $result_arrematric=$clarrematric->sql_record($clarrematric->sql_query_file($k00_numpre,0,"k00_matric"));
          if ($clarrematric->numrows > 0 && $jaexiste == false) {

	          db_fieldsmemory($result_arrematric,0);
	          $clarrematric->k00_numpre = $numpre_novo;
	          $clarrematric->k00_matric = $k00_matric;
	          $clarrematric->k00_perc   = 100;
	          $clarrematric->incluir($numpre_novo,$k00_matric);
      	    if ($clarrematric->erro_status == '0') {

      	      $sqlerro = true;
      	      break;
      	    }
      	  }
          $result_arreinscr = $clarreinscr->sql_record($clarreinscr->sql_query_file($k00_numpre, 0, "k00_inscr"));
          if ($clarreinscr->numrows > 0 && $jaexiste == false) {

            db_fieldsmemory($result_arreinscr, 0);
	          $clarreinscr->k00_numpre = $numpre_novo;
      	    $clarreinscr->k00_inscr  = $k00_inscr;
      	    $clarreinscr->k00_perc   = 100;
      	    $clarreinscr->incluir($numpre_novo,$k00_inscr);
      	    if ($clarreinscr->erro_status == '0') {

      	      $sqlerro = true;
      	      break;
      	    }
      	  }
        }
        $v01_obs = "";

        if (in_array($k03_tipo, $aTipos)) {

          $v01_obs = $cldivida->resumo_importacao($k00_numpre, $k03_tipo);
        }
        $iExercicioDivida         = $cldivida->getExercicioDivida($k00_numpre, $k03_tipo, substr($k00_dtoper, 6, 4));

      	$cldivida->v01_numcgm     = $k00_numcgm;
      	$cldivida->v01_dtinsc     = date("Y-m-d",db_getsession('DB_datausu') );
      	$cldivida->v01_dtinclusao = date('Y-m-d',db_getsession('DB_datausu'));
      	$cldivida->v01_instit     = db_getsession('DB_instit');
      	$cldivida->v01_exerc      = $iExercicioDivida; //substr($k00_dtoper,6,4);
      	$cldivida->v01_numpre     = $numpre_novo;
      	$cldivida->v01_numpar     = $k00_numpar;
      	$cldivida->v01_numtot     = $k00_numtot;
        $k00_numdig 		          = 1;
      	$cldivida->v01_numdig     = $k00_numdig;
      	$cldivida->v01_vlrhis     = $k00_valor;
      	$cldivida->v01_proced     = $cod_v03_codigo;
      	$cldivida->v01_obs        = pg_escape_string($v01_obs);
      	$cldivida->v01_livro      = "";
      	$cldivida->v01_folha      = "";
      	$dt_venc			            = split("/",$k00_dtvenc);
      	$dt_venc_data 		        = $dt_venc[2]."-".$dt_venc[1]."-".$dt_venc[0];
      	$cldivida->v01_dtvenc     = $dt_venc_data;
      	$dt_oper			            = split("/",$k00_dtoper);
      	$dt_oper_data 		        = $dt_oper[2]."-".$dt_oper[1]."-".$dt_oper[0];
      	$cldivida->v01_dtoper     = $dt_oper_data;
      	$cldivida->v01_valor      = $k00_valor;

        /*
      	 * verificamos se o processo selecionado for externo
      	 */
      	if ($lProcessoSistema == 0) {

      	  $cldivida->v01_processo   = $iProcesso;
      	  $cldivida->v01_titular    = $sTitular;
      	  $cldivida->v01_dtprocesso = $dDataProcesso;
      	}

      	if ($jaexiste == false) {

      	  $cldivida->incluir(null);
      	  $erro_msg = __LINE__ . $cldivida->erro_msg."--- Inclusão Divida";

      	  if ($cldivida->erro_status == 0) {

      	    $erro_msg = __LINE__ . $cldivida->erro_msg."--- Inclusão Divida";
      	    $sqlerro  = true;
      	    break;
      	  }

      	  /**
      	   * se o processo for interno
      	   * incluimos na dividaprotprocesso
      	   */
      	  if ($lProcessoSistema == 1 && $iProcesso != null) {

      	    $oDaoDividaProtprocesso = db_utils::getDao('dividaprotprocesso');
      	    $oDaoDividaProtprocesso->v88_divida       = $cldivida->v01_coddiv;
      	    $oDaoDividaProtprocesso->v88_protprocesso = $iProcesso;
      	    $oDaoDividaProtprocesso->incluir(null);
      	    if ($oDaoDividaProtprocesso->erro_status == 0) {

      	      $erro_msg = __LINE__ . $oDaoDividaProtprocesso->erro_msg."--- Inclusao DividaProtprocesso";
      	      db_msgbox($erro_msg);
      	      $sqlerro = true;
      	    }

      	  }
      	}

      	if ($sqlerro == false) {

      	  $sSqlDebitosArrecad = $clarrecad->sql_query_info (null,
      	                                                   "distinct arrecad.k00_numcgm,
      	                                                    arrecad.k00_dtoper,
      	                                                    arrecad.k00_receit,
      	                                                    arrecad.k00_hist,
      	                                                    arrecad.k00_dtvenc,
      	                                                    arrecad.k00_numpre,
      	                                                    arrecad.k00_numpar,
      	                                                    arrecad.k00_numtot,
      	                                                    arrecad.k00_numdig,
      	                                                    arrecad.k00_tipo,
      	                                                    arrecad.k00_tipojm,
      	                                                    arrecad.k00_valor,
      	                                                    arretipo.k03_tipo",
      	                                                    "arrecad.k00_numpre,
      	                                                    arrecad.k00_receit",
      	                                                    " arreinstit.k00_instit = ".db_getsession('DB_instit')."
      	                                                     and arrecad.k00_numpre = {$k00_numpre}
      	                                                     and arrecad.k00_numpar = {$k00_numpar}
      	                                                     and arrecad.k00_receit = {$k00_receit}"
      	                                                    );
      	  $rsConsultDebito    = $clarrecad->sql_record($sSqlDebitosArrecad);
      	  $iNroDebitos        = $clarrecad->numrows;

      	  for ($x = 0; $x < $iNroDebitos; $x++) {

      	    $oDebitos              = db_utils::fieldsMemory($rsConsultDebito, $x);
      	    $k00_numpre_exc		     = $oDebitos->k00_numpre;
      	    $k00_numpar_exc	       = $oDebitos->k00_numpar;
      	    $k00_receit_exc		     = $oDebitos->k00_receit;
      	    $k00_tipojm 		       = (int) $oDebitos->k00_tipojm;
      	    $clarreold->k00_numpre = $oDebitos->k00_numpre;
      	    $clarreold->k00_numpar = $oDebitos->k00_numpar;
      	    $clarreold->k00_numcgm = $oDebitos->k00_numcgm;
      	    $clarreold->k00_dtoper = $dt_oper_data;
      	    $clarreold->k00_receit = $oDebitos->k00_receit;
      	    $clarreold->k00_hist   = $oDebitos->k00_hist  ;
      	    $clarreold->k00_valor  = $oDebitos->k00_valor ;
      	    $clarreold->k00_dtvenc = $dt_venc_data;
      	    $clarreold->k00_numtot = $oDebitos->k00_numtot;
      	    $clarreold->k00_numdig = $oDebitos->k00_numdig;
      	    $clarreold->k00_tipo   = $oDebitos->k00_tipo  ;
      	    $clarreold->k00_tipojm = "$oDebitos->k00_tipojm";
      	    $clarreold->incluir();

      	    if ($clarreold->erro_status == 0) {

      	      $sqlerro  = true;
      	      $erro_msg = __LINE__ . $clarreold->erro_msg."--- Inclusão ArreOld";
      	      break;
      	    }
      	  }

      	  $cldivimportareg->v04_divimporta = $cldivimporta->v02_divimporta;
      	  $cldivimportareg->v04_coddiv = $cldivida->v01_coddiv;

      	  if ($jaexiste == false) {

        	  $cldivimportareg->incluir();
        	  if ($cldivimportareg->erro_status == 0) {
        		$sqlerro = true;
        		$erro_msg = __LINE__ . $cldivimportareg->erro_msg;
        		break;
        	  }
      	  }
      	  if ($sqlerro == false && $jaexiste == false) {

      	    $cldivold->k10_coddiv=$cldivida->v01_coddiv;
      	    $cldivold->k10_numpre=$k00_numpre;
      	    $cldivold->k10_numpar=$k00_numpar;
      	    $cldivold->k10_receita=$k00_receit;
      	    $cldivold->incluir(null);
      	    if ($cldivold->erro_status == 0) {

      	       $sqlerro  = true;
      	       $erro_msg = __LINE__ . $cldivold->erro_msg."--- Inclusão DIVOLD";
      	       break;
      	    }
      	  }
      	  if ($sqlerro == false) {

      	  	$sSqlProced        = $clproced->sql_query_file(null,"v03_receit,k00_hist",null," v03_codigo=$cod_v03_codigo");
      	    $result_pes_proced = $clproced->sql_record($sSqlProced);
      	    db_fieldsmemory($result_pes_proced,0);
      	    $v03_hist = $k00_hist;

      	    $clarrecad->k00_numpre = $numpre_novo;
      	    $clarrecad->k00_numpar = $k00_numpar;
      	    $clarrecad->k00_numcgm = $k00_numcgm;
      	    $clarrecad->k00_dtoper = $dt_oper_data;
      	    $clarrecad->k00_receit = $v03_receit;
      	    $clarrecad->k00_hist   = $v03_hist;
      	    $clarrecad->k00_valor  = $k00_valor;
      	    $clarrecad->k00_dtvenc = $dt_venc_data;
      	    $clarrecad->k00_numtot = $k00_numtot;
      	    $clarrecad->k00_numdig = $k00_numdig;
      	    $clarrecad->k00_tipo   = $chave_destino;
      	    $clarrecad->k00_tipojm = "0";
      	    if ($jaexiste == false) {
      	      $clarrecad->incluir();
      	    } else {

      	      $sqlarrecad  = "update arrecad                         ";
      				$sqlarrecad .= "   set k00_receit = $v03_receit,       ";
      				$sqlarrecad	.= "			    k00_tipo   = $chave_destino  ";
      				$sqlarrecad .= " where	k00_numpre = $k00_numpre       ";
      				$sqlarrecad	.= "		  and k00_numpar = $k00_numpar     ";
      				$sqlarrecad	.= "			and k00_receit = $k00_receit     ";

      	      $resultarrecad = db_query($sqlarrecad) or die($sqlarrecad);
      	      $erro_msg = "Processamento efetuado com sucesso";
      	      $sqlerro = false;
      	    }
      	    if ($clarrecad->erro_status == 0 && $jaexiste == false) {

      	      $sqlerro  = true;
      	      $erro_msg = __LINE__ . $clarrecad->erro_msg."--- Inclusão Arrecad";
      	      break;
      	    }
      	  }

      	  if ($sqlerro == false) {

      	    if ($jaexiste == false) {

      	      $clarrecad->excluir(null," k00_numpre = $k00_numpre_exc
      	                             and k00_numpar = $k00_numpar_exc
      	                             and k00_receit = $k00_receit_exc"
      	                          );

      	      if ($clarrecad->erro_status == 0) {

            		$sqlerro  = true;
            		$erro_msg = __LINE__ . $clarrecad->erro_msg."--- Exclusão Arrecad";
            		break;
      	      }
      	    }
      	  }
	     }
      }
    }

    $sWhere               = "     arreckey.k00_numpre = {$k00_numpre_exc} ";
    $sWhere              .= " and arreckey.k00_numpar = {$k00_numpar_exc} ";
    $sWhere              .= " and arreckey.k00_receit = {$k00_receit_exc} ";
    $sCampos              = " k00_vlrhist,k00_correcao,k00_juros,k00_multa ";
    $sSqlArrecadCompos    = $clarrecadcompos->sql_query(null, $sCampos, null, $sWhere);
    $rsArrecadCompos      = $clarrecadcompos->sql_record($sSqlArrecadCompos);
    $iLinhasArrecadCompos = $clarrecadcompos->numrows;

    if ($iLinhasArrecadCompos > 0) {

      $oArrecadacao = db_utils::fieldsMemory($rsArrecadCompos, 0);

      if ($sqlerro == false) {
        // Insere em arreckey
        $clarreckey->k00_numpre = $clarrecad->k00_numpre;
        $clarreckey->k00_numpar = $clarrecad->k00_numpar;
        $clarreckey->k00_receit = $clarrecad->k00_receit;
        $clarreckey->k00_hist   = $clarrecad->k00_hist;
        $clarreckey->k00_tipo   = $clarrecad->k00_tipo;
        $clarreckey->incluir(null);

        if ($clarreckey->erro_status == "0") {

          $erro_msg = __LINE__ . $clarreckey->erro_msg;
          $sqlerro  = true;
          break;
        }
      }

      if ($sqlerro == false) {

        // Insere ArrecCompos
        $clarrecadcompos->k00_arreckey = $clarreckey->k00_sequencial;
        $clarrecadcompos->k00_vlrhist  = $oArrecadacao->k00_vlrhist;
        $clarrecadcompos->k00_correcao = $oArrecadacao->k00_correcao;
        $clarrecadcompos->k00_juros    = $oArrecadacao->k00_juros;
        $clarrecadcompos->k00_multa    = $oArrecadacao->k00_multa;
        $clarrecadcompos->incluir(null);

        if ($clarrecadcompos->erro_status == "0") {

          $erro_msg = __LINE__ . $clarrecadcompos->erro_msg;
          $sqlerro  = true;
          break;
        }
      }
    }
  }


  $datafim = date("Y-m-d");
  $horafim = date("H:i");
  $cldivimporta->v02_divimporta = $iDivImporta;
  $cldivimporta->v02_datafim    = $datafim;
  $cldivimporta->v02_horafim    = $horafim;
  $cldivimporta->alterar($iDivImporta);

  if ($cldivimporta->erro_status == "0") {

    db_msgbox($cldivimporta->erro_msg);
	  $sqlerro = true;
  }

  $chave_origem  = "";
  $chave_destino = "";

  db_fim_transacao($sqlerro);
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
  <form name="form1" method="post" action="">
  <table>
    <?php
    if (isset($chave_origem) && trim($chave_origem)!= "" && isset($chave_destino) && trim($chave_destino)!= "") {

      $sql0  = " select tabrec.k02_codigo,                                ";
      $sql0 .= "             tabrec.k02_drecei                            ";
      $sql0 .= "  from (select distinct k00_receit                        ";
      $sql0 .= "          from arrecad $txt_inner                         ";
      $sql0 .= "        where k00_tipo  = $chave_origem $txt_where ) as x ";
      $sql0 .= "inner join tabrec on k02_codigo = x.k00_receit            ";

      $result0  = $cltabrec->sql_record($sql0);
      $numrows0 = $cltabrec->numrows;

      if($numrows0 == 0){

        echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
        echo "<script>
                parent.document.form1.gerar.disabled=true;
    	          alert('Nenhum tipo de débito encontrado com este código');
              </script>";
        echo "<script>top.corpo.db_iframe.hide();</script>";
        echo "<script>top.corpo.location.href='div4_importadivida011.php'</script>";
      }

      $sql1 = "select v03_codigo, v03_descr from proced where v03_instit = {$iInstit} order by v03_descr ";
      $result1        = $clproced->sql_record($sql1);
      $numrows1       = $clproced->numrows;
      $vir            = "";
      $cod_k02_codigo = "";
      $vir1           = "";
      $cod_v03_codigo = "";

      for ($i = 0; $i < $numrows0; $i++) {

        db_fieldsmemory($result0, $i);
        $cod_k02_codigo .= $vir.$k02_codigo;
        $vir = ",";
        echo "
        <tr>
          <td nowrap>";
             db_input("$k02_drecei",40,"",true,"text",3,"","k02_drecei");
        echo "
        	    <select name=\"v03_descr\" onchange=\"js_troca();\" id=\"v03_descr\">
        	     <option value=\"0\" >Escolha uma procedencia</option>
        	   ";
            for( $ii = 0; $ii < $numrows1; $ii++) {

              db_fieldsmemory($result1,$ii);
              echo "<option value=\"$v03_codigo\" >$v03_codigo - $v03_descr</option>";

              if($ii == 0){

              	$cod_v03_codigo .= $vir1.$v03_codigo;
              	$vir1=",";
              }
            }
        echo "
        	</select>
          </td>
        </tr>";
      }
      db_input("txt_where",40,"0",true,"hidden",3);
      db_input("txt_inner",40,"0",true,"hidden",3);
      db_input("chave_origem",40,"0",true,"hidden",3);
      db_input("chave_destino",40,"0",true,"hidden",3);
      db_input("cod_k02_codigo",40,"0",true,"hidden",3);
      db_input("cod_v03_codigo",40,"0",true,"hidden",3);
      echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
    }
    ?>
  </table>
  </form>
</div>
</body>
</html>
<script type="text/javascript">

function js_troca(){

  vir    = "";
  codigo = "";
  cont   = 0;

  for (i=0; i < document.form1.length; i++) {

    if (document.form1.elements[i].type == "select-one") {

      if (document.form1.elements[i].value != 0) {

        codigo += vir + document.form1.elements[i].value;
        vir=",";
      } else {
	      cont++;
      }
    }
  }
  if (cont == 0) {
    parent.document.form1.gerar.disabled = false;
  } else {
    parent.document.form1.gerar.disabled = true;
  }
  document.form1.cod_v03_codigo.value = codigo;
}
</script>
<?php
if ($teste == true){

 unset($_SESSION["where_divida"]);
 unset($_SESSION["inner_divida"]);

  echo $erro_msg;
  if($erro_msg!=""){
    echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
    db_msgbox($erro_msg);
    echo "<script>top.corpo.db_iframe.hide();</script>";
    echo "<script>top.corpo.location.href='div4_importadivida011.php'</script>";
  }
}
?>