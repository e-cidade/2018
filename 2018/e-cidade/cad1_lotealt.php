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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cllotedist       = new cl_lotedist;
$clface           = new cl_face;
$cllote           = new cl_lote;
$clloteloc        = new cl_loteloc;
$clloteam         = new cl_loteam;
$clloteloteam     = new cl_loteloteam;
$clcarlote        = new cl_carlote;
$cltestada        = new cl_testada;
$cltestpri        = new cl_testpri;
$cliptubase       = new cl_iptubase;
$clsetor          = new cl_setor;
$cltestadanumero  = new cl_testadanumero;
$cllotesetorfiscal= new cl_lotesetorfiscal;
$clcfiptu         = new cl_cfiptu;
$cltesinterlote   = new cl_tesinterlote;
$cltesinteroutros = new cl_tesinteroutros;
$cltesinter       = new cl_tesinter;

$cllotedist->rotulo->label();
$clloteam->rotulo->tlabel();
$clcarlote->rotulo->tlabel();
$cllote->rotulo->label();
$cltestada->rotulo->tlabel();

$cltestadanumero->rotulo->tlabel();

$cllotedist->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("j30_descr");
$clrotulo->label("j13_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j34_loteam");
$clrotulo->label("j34_descr");
$cllotedist->rotulo->tlabel();
$cllotesetorfiscal->rotulo->label();
$clrotulo->label("j90_descr");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_areapreservada");
$cllotedist->rotulo->tlabel();
$trans_erro = false;
$rsResultmostra = ($clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession('DB_anousu'), '*', "", "")));
if ($clcfiptu->numrows > 0) {
	db_fieldsmemory($rsResultmostra, 0);
	$mostrasetfiscal = $j18_utilizasetfisc;
	$numerotestada = $j18_testadanumero;
}

if (isset ($incluquadra) && $incluquadra != "") {
	$resulta = $clsetor->sql_record($clsetor->sql_query($j34_setor, "j30_descr"));
	db_fieldsmemory($resulta, 0);
	$db_opcao = $incluquadra;
} else {
	$db_opcao = 1;
}
$db_botao = true;
$selface = false;
$testasetor = false;
$replote = false;

if (isset ($j01_matric)) {
	$idmatricu = $j01_matric;
}
if (isset ($incluir) || isset ($alterar)) {
	$mesmo = false;
	$result = @ $cllote->sql_record($cllote->sql_query("", "j34_idbql as tidbql", "", "j34_setor= '$j34_setor' and j34_quadra='$j34_quadra' and j34_lote='$j34_lote'"));
	$numrows = $cllote->numrows;
	if ($result != false && $numrows != 0) {
		if (isset ($alterar)) {
			for ($xi = 0; $xi < $numrows; $xi++) {
				db_fieldsmemory($result, $xi);
				if ($j34_idbql == $tidbql) {
					$mesmo = true;
					break;
				}
			}
		}
		if ($mesmo == false) {
			$replote = true;
			if (isset ($incluir)) {
				unset ($incluir);
				$repete = "incluir";
				$db_opcao = 1;
			} else {
				unset ($alterar);
				$repete = "alterar";
				$db_opcao = 2;
			}
		}
	}
}
if (isset ($outrolote) && $outrolote != "") {
	$$outrolote = "ok";
}
if ($replote == true) {

} else if (isset ($j34_setor) && !isset ($incluir) && !isset ($alterar)) {
		$resultface = $clface->sql_record($clface->sql_query("", "distinct j37_quadra", "", "j37_setor='$j34_setor'"));
		$clface->numrows == 0;
		$selface = true;
	} else if (isset ($incluir)) {
			db_inicio_transacao();
			$j34_lote = str_pad($j34_lote, 4, "0", STR_PAD_LEFT);
			$cllote->j34_lote = $j34_lote;
			$cllote->j34_areapreservada = $j34_areapreservada;
			if ($cllote->incluir(null) == true) {
				$j34_idbql = $cllote->j34_idbql;

				if ($idmatricu != "") {
					$cliptubase->j01_idbql = $j34_idbql;
					$cliptubase->j01_matric = $idmatricu;
					$cliptubase->alterar($idmatricu);
				}

				if ($j34_loteam != "") {
					$result = $clloteam->sql_record($clloteam->sql_query($j34_loteam, "j34_loteam"));
					$numrows = $clloteam->numrows;
					if ($numrows >= 1) {
						$clloteloteam->j34_idbql = $j34_idbql;
						$clloteloteam->j34_loteam = $j34_loteam;
						$clloteloteam->incluir($j34_idbql, $j34_loteam);
					}
				}
       /*============ TESTADAS INTERNAS ============== */

				$matriztesinter = split("X", $testadainter);
				foreach ($matriztesinter as $valor) {
					$dadosTestadaInterna = split("-", $valor);

					$idbqlInterLote   = $dadosTestadaInterna[0];
					$j39_idbql        = $cllote->j34_idbql;
					$j39_orientacao   = (isset($dadosTestadaInterna[1])?$dadosTestadaInterna[1]:"");
					$j39_testad       = (isset($dadosTestadaInterna[2])?$dadosTestadaInterna[2]:"");
          $j39_testle       = (isset($dadosTestadaInterna[3])?$dadosTestadaInterna[3]:"");
          $j84_tesintertipo = (isset($dadosTestadaInterna[4])?$dadosTestadaInterna[4]:"");

          if (($j39_testad != "0" && $j39_testad != "") || ($j39_testle != "0" && $j39_testle != "")) {
						$cltesinter->j39_idbql      = $j39_idbql;
						$cltesinter->j39_orientacao = $j39_orientacao;
						$cltesinter->j39_testad     = $j39_testad;
						$cltesinter->j39_testle     = $j39_testle;
						$cltesinter->incluir(null);
						if($cltesinter->erro_status == 0){
							db_msgbox("TESINTER : ".$cltesinter->erro_msg);
							$trans_erro = true;
						}

						if (isset($idbqlInterLote) && $idbqlInterLote <> 0){
							$cltesinterlote->j69_tesinter = $cltesinter->j39_sequencial;
							$cltesinterlote->j69_idbql    = $idbqlInterLote;
							$cltesinterlote->incluir($cltesinter->j39_sequencial);
							if($cltesinterlote->erro_status == 0){
							  db_msgbox("TESINTERLOTE :".$cltesinterlote->erro_msg);
							  $trans_erro = true;

							}
						}else if (isset($j84_tesintertipo) && $j84_tesintertipo <> '0'){

              $cltesinteroutros->j84_tesintertipo = $j84_tesintertipo;
              $cltesinteroutros->j84_tesinter     = $cltesinter->j39_sequencial;
							$cltesinteroutros->incluir();
							if($cltesinteroutros->erro_status == 0){
							  db_msgbox("TESINTEROUTROS :".$cltesinteroutros->erro_msg);
							  $trans_erro = true;

							}

            }
					}
				}

				//=============================================

				$resultado = db_query("select * from face where j37_face = $cartestpri");
				$j37_codigo = pg_result($resultado, 0, 3);
				$cltestpri->j49_face = $cartestpri;
				$cltestpri->j49_codigo = $j37_codigo;

				$cltestpri->incluir($cllote->j34_idbql, $cartestpri);
				$matriztesta = explode("x", $cartestada);
				for ($i = 0; $i < sizeof($matriztesta); $i++) {
					$dados = $matriztesta[$i];
					$matrizdados = explode("||", $dados);

					$j37_face = $matrizdados[0];
					$j14_codigo = $matrizdados[1];
					$j36_testad = $matrizdados[2];
					$j36_testle = $matrizdados[3];

					//==============================================================
					$j15_numero = $matrizdados[4];
					$j15_compl = $matrizdados[5];
					//==============================================================
					if ($j36_testad != "0" || $j36_testle != "0") {
						$cltestada->j36_idbql = $cllote->j34_idbql;
						$cltestada->j36_face = $j37_face;
						$cltestada->j36_codigo = $j14_codigo;
						$cltestada->j36_testad = $j36_testad;
						$cltestada->j36_testle = $j36_testle;
						$cltestada->incluir($cllote->j34_idbql, $j37_face);
					}
				if ($cltestada->erro_status == "0") {
								$trans_erro = true;
								db_msgbox($cltestada->erro_msg);
							}

				//===============================================================
					if (isset ($numerotestada) && $numerotestada == 't') {

						if ((isset ($j15_numero) && $j15_numero != "") || (isset ($j15_compl) && $j15_compl != "")) {
							$cltestadanumero->j15_idbql = $cllote->j34_idbql;
							$cltestadanumero->j15_face = $j37_face;
							$cltestadanumero->j15_compl = $j15_compl;
							$cltestadanumero->j15_numero = $j15_numero;
							$cltestadanumero->incluir("");
							if ($cltestadanumero->erro_status == "0") {
								$trans_erro = true;
								db_msgbox("testadanumero".$cltestadanumero->erro_msg);
							}
						}
					}
				//===============================================================

				}

				$j34_idbql = $cllote->j34_idbql;
				$clcarlote->j35_idbql = $j34_idbql;
				$matriz = explode("X", $caracteristica);
				for ($i = 0; $i < sizeof($matriz); $i++) {
					$j35_caract = $matriz[$i];
					if ($j35_caract != "") {

						$clcarlote->j35_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
					 	$clcarlote->incluir($j34_idbql, $j35_caract);
						if ($clcarlote->erro_status == "0") {
							$trans_erro = true;
							db_msgbox("carlote".$clcarlote->erro_msg);
						}
					}
				}

				if ($j54_codigo != "" && $j54_distan != "" ) {

					$cllotedist->j54_idbql      = $cllote->j34_idbql;
					$cllotedist->j54_codigo     = $j54_codigo;
					$cllotedist->j54_distan     = $j54_distan;
					$cllotedist->j54_orientacao = $j54_orientacao;
					$cllotedist->incluir($j34_idbql);
					if ($cllotedist->erro_status == "0") {
						$trans_erro = true;
						db_msgbox("lotedist".$cllotedist->erro_msg);
					}
				}

				//   INCLUSAO  NA TABELA LOTESETORFISCAL

				if (isset ($j91_codigo) && $j91_codigo != "") {
					$cllotesetorfiscal->j91_idbql = $cllote->j34_idbql;
					$cllotesetorfiscal->j91_codigo = $j91_codigo;
					$cllotesetorfiscal->incluir($cllote->j34_idbql);
					if ($cllotesetorfiscal->erro_status == "0") {
						$trans_erro = true;
					}
				}

				//INCLUSAO NA TABELA LOTELOC
				if (isset ($j06_setorloc) && $j06_setorloc != "") {
				  //db_msgbox("setor = $j06_setorloc / quadra= $j06_quadraloc / lote = $j06_lote  / id = ".$cllote->j34_idbql);
					$clloteloc->j06_idbql     = $cllote->j34_idbql;
					$clloteloc->j06_setorloc  = $j06_setorloc;
					$clloteloc->j06_quadraloc = $j06_quadraloc;
					$clloteloc->j06_lote      = $j06_lote ;
  				$clloteloc->incluir($cllote->j34_idbql);
					if ($clloteloc->erro_status == "0") {
						$trans_erro = true;
						//db_msgbox("LOTELOC... deu erro");
					}
				}

				//============================================

			}
			db_fim_transacao($trans_erro);
		} else if (isset ($alterar)) {
				$sqlerro = false;
				db_inicio_transacao();

				if ($j34_loteam != "") {
					$result = $clloteloteam->sql_record($clloteloteam->sql_query_file("", "", "loteloteam.j34_loteam as loteam", "", "loteloteam.j34_idbql=$j34_idbql"));
					$numrows = $clloteloteam->numrows;
					if ($numrows > 0) {
						db_fieldsmemory($result, 0);
						if ($j34_loteam != $loteam) {
							$result = $clloteam->sql_record($clloteam->sql_query($j34_loteam, "j34_loteam"));
							$numrows = $clloteam->numrows;
							if ($numrows > 0) {
								$clloteloteam->j34_idbql = $j34_idbql;
								$clloteloteam->j34_loteam = $loteam;
								$clloteloteam->excluir($j34_idbql, $loteam);
								if ($clloteloteam->erro_status == 0) {
									//		       	db_msgbox("erro numero 7");
									$sqlerro = true;
								}
								$clloteloteam->j34_idbql = $j34_idbql;
								$clloteloteam->j34_loteam = $j34_loteam;
								$clloteloteam->incluir($j34_idbql, $j34_loteam);
								if ($clloteloteam->erro_status == 0) {
									//		       	db_msgbox("erro numero 6");
									$sqlerro = true;
								}
							}
						}
					} else {
						$result = $clloteam->sql_record($clloteam->sql_query($j34_loteam));
						$numrows = $clloteam->numrows;
						if ($numrows > 0) {
							$clloteloteam->j34_idbql = $j34_idbql;
							$clloteloteam->j34_loteam = $j34_loteam;
							$clloteloteam->incluir($j34_idbql, $j34_loteam);
							if ($clloteloteam->erro_status == 0) {
								//		   	db_msgbox("erro numero 5");
								$sqlerro = true;
							}
						}
					}
				} else {
					$result = $clloteloteam->sql_record($clloteloteam->sql_query_file("", "", "loteloteam.j34_loteam as loteam", "", "loteloteam.j34_idbql=$j34_idbql"));
					$numrows = $clloteloteam->numrows;
					if ($numrows > 0) {
						db_fieldsmemory($result, 0);
						$clloteloteam->j34_idbql = $j34_idbql;
						$clloteloteam->j34_loteam = $loteam;
						$clloteloteam->excluir($j34_idbql);
						if ($clloteloteam->erro_status == 0) {
							$sqlerro = true;
						}
					}
				}
				if ($idmatricu != "") {
					$cliptubase->j01_idbql = $j34_idbql;
					$cliptubase->j01_matric = $idmatricu;
					$cliptubase->alterar($idmatricu);
				}
				$j34_lote = str_pad($j34_lote, 4, "0", STR_PAD_LEFT);
				$cllote->j34_lote = $j34_lote;
				$cllote->j34_areapreservada = $j34_areapreservada;
				$cllote->alterar($j34_idbql);
				if ($cllote->erro_status == 0) {
					db_msgbox($cllote->erro_msg);
					$sqlerro = true;
				}
				//===================================================================================================================================================
				$resultt = $cltestadanumero->sql_record($cltestadanumero->sql_query(null, "*", null, " j15_idbql = $j34_idbql "));
				$xxx = $cltestadanumero->numrows;
				if ((isset ($numerotestada) && $numerotestada == 't' && (isset ($matriztesta) && $matriztesta != "")) || $xxx > 0) {
					$sqlerro = false;
					for ($i = 0; $i < $xxx; $i++) {
						db_fieldsmemory($resultt, $i);
    				$cltestadanumero->sql_record($cltestadanumero->sql_query(null, "*", null, "j15_idbql = $j36_idbql"));
						$numrowstestadanumero = $cltestadanumero->numrows;
						if ($numrowstestadanumero > 0) {
							$cltestadanumero->j15_idbql = $j15_idbql;
							$cltestadanumero->j15_face = $j15_face;
							$cltestadanumero->excluir("", " j15_idbql = $j36_idbql and j15_face = $j15_face ");
							if ($cltestadanumero->erro_status == 0) {
								$sqlerro = true;
								$erro_msg = $cltestadanumero->erro_msg;
								break;
							}
						}
					}
				}
				//===================================================================================================================================================

				if ($cllote->erro_status == 1) {
					$rsCarLote = $clcarlote->sql_record($clcarlote->sql_query_file($j34_idbql));
					$iClcarloteNumrows = $clcarlote->numrows;
					$xx = $clcarlote->numrows;

					for ($i = 0; $i < $xx; $i++) {

						db_fieldsmemory($rsCarLote, $i);
						$clcarlote->j35_idbql = $j35_idbql;
						$clcarlote->j35_caract = $j35_caract;
						$clcarlote->excluir($j35_idbql, $j35_caract);
						if ($clcarlote->erro_status == 0) {
							//    	db_msgbox("erro numero 2");
							$sqlerro = true;
						}
					}

					// exclusao da tesinter e tesinterlote
					/*========================================================================================================================*/

					if ($sqlerro == false) {

						$rsTesinterlote = $cltesinter->sql_record($cltesinter->sql_query_file(null,'j39_sequencial',null,"j39_idbql = $j34_idbql"));
						$intNumrows = $cltesinter->numrows;
						for($intTes = 0; $intTes < $intNumrows; $intTes++){

							db_fieldsmemory($rsTesinterlote,$intTes);

              $cltesinteroutros->excluir(null,"j84_tesinter = {$j39_sequencial}" );
							if($cltesinteroutros->erro_status == 0){
								db_msgbox("TESINTEROUTROS EXCLUSAO : ".$cltesinteroutros->erro_msg);
								$trans_erro = true;
							}

							$cltesinterlote->excluir($j39_sequencial);
							if($cltesinterlote->erro_status == 0){
								db_msgbox("TESINTERLOTE EXCLUSAO : ".$cltesinterlote->erro_msg);
								$trans_erro = true;
							}

							$cltesinter->excluir($j39_sequencial);
							if($cltesinter->erro_status == 0){
								db_msgbox("TESINTER EXCLUSAO: ".$cltesinter->erro_msg);
								$trans_erro = true;
							}

						}
					}
				 /*============ TESTADAS INTERNAS ============== */

					$matriztesinter = explode("X", $testadainter);

          if ( count($matriztesinter) >= 1 && $testadainter != "" ) {

            foreach ($matriztesinter as $valor) {
              $dadosTestadaInterna = explode("-", $valor);
              $idbqlInterLote   = $dadosTestadaInterna[0];
              $j39_idbql        = $cllote->j34_idbql;
              $j39_orientacao   = $dadosTestadaInterna[1];
              $j39_testad       = $dadosTestadaInterna[2];
              $j39_testle       = $dadosTestadaInterna[3];
              $j84_tesintertipo = $dadosTestadaInterna[4];
              if (($j39_testad != "0" && $j39_testad != "") || ($j39_testle != "0" && $j39_testle != "")) {
                $cltesinter->j39_idbql      = $j39_idbql;
                $cltesinter->j39_orientacao = $j39_orientacao;
                $cltesinter->j39_testad     = $j39_testad;
                $cltesinter->j39_testle     = $j39_testle;
                $cltesinter->incluir(null);
                if($cltesinter->erro_status == 0){
                  db_msgbox("TESINTER : ".$cltesinter->erro_msg);
                  $trans_erro = true;
                }

                if (isset($idbqlInterLote) && $idbqlInterLote <> 0){

                  $cltesinterlote->j69_tesinter = $cltesinter->j39_sequencial;
                  $cltesinterlote->j69_idbql    = $idbqlInterLote;
                  $cltesinterlote->incluir($cltesinter->j39_sequencial);
                  if($cltesinterlote->erro_status == 0){
                    db_msgbox("TESINTERLOTE :".$cltesinterlote->erro_msg);
                    $trans_erro = true;

                  }

                }else if (isset($j84_tesintertipo) && $j84_tesintertipo <> '0'){

                  $cltesinteroutros->j84_tesintertipo = $j84_tesintertipo;
                  $cltesinteroutros->j84_tesinter     = $cltesinter->j39_sequencial;
                  $cltesinteroutros->incluir();
                  if($cltesinteroutros->erro_status == 0){
                    db_msgbox("TESINTEROUTROS :".$cltesinteroutros->erro_msg);
                    $trans_erro = true;

                  }

                }

              }
            }

          }

  				//=============================================

					if ($sqlerro == false) {

						$result = $cltestada->sql_record($cltestada->sql_query($j34_idbql));
						$xx = $cltestada->numrows;
						for ($i = 0; $i < $xx; $i++) {

							db_fieldsmemory($result, $i);
							$cltestada->j36_idbql = $j36_idbql;
							$cltestada->j36_face = $j36_face;
							$cltestada->excluir($j36_idbql, $j36_face);
							if ($cltestada->erro_status == 0) {
								$msg = $cltestada->erro_banco;
								//      	db_msgbox("testada - ".$msg);
								$sqlerro = true;
							}
						}

						$cltestpri->j49_idbql = $j34_idbql;
						$cltestpri->excluir($j34_idbql);
						if ($cltestpri->erro_status == 0) {
							$sqlerro = true;
						}
					}
					$result = $clface->sql_record($clface->sql_query_file("", "j37_codigo", "", "j37_face=$cartestpri"));
					$num = $clface->numrows; //pg_numrows($result);
					if ($num != 0) {
						db_fieldsmemory($result, 0);
						$cltestpri->j49_face = $cartestpri;
						$cltestpri->j49_codigo = $j37_codigo;
						$cltestpri->incluir($j34_idbql, $cartestpri);
						if ($cltestpri->erro_status == 0) {
							//	     db_msgbox("erro testepri");
							echo $cltestpri->erro_msg;
							$sqlerro = true;
						}
					}

					$matriztesta = explode("x", $cartestada);

          for ($i = 0; $i < sizeof($matriztesta); $i++) {
						$dados = $matriztesta[$i];
						$matrizdados = explode("||", $dados);
						$j37_face   = $matrizdados[0];
						$j14_codigo = $matrizdados[1];
						$j36_testad = $matrizdados[2];
						$j36_testle = $matrizdados[3];

						//==============================================================
						$j15_numero = $matrizdados[4];
						$j15_compl  = $matrizdados[5];
						//==============================================================

						if($j36_testad != "0" && $j36_testad != ""){

							if ($sqlerro == false) {
								$cltestada->j36_idbql = $cllote->j34_idbql;
								$cltestada->j36_face = $j37_face;
								$cltestada->j36_codigo = $j14_codigo;
								$cltestada->j36_testad = empty($j36_testad) ? '0' : $j36_testad;
								$cltestada->j36_testle = empty($j36_testle) ? '0' : $j36_testle;
								$cltestada->incluir($cllote->j34_idbql, $j37_face);

								if ($cltestada->erro_status == 0) {

                	db_msgbox("Erro ao incluir Testada: " . $cltestada->erro_msg);
									$sqlerro = true;
								}
							}

							if (isset ($numerotestada) && $numerotestada == 't') {
								if ((isset ($j15_numero) && $j15_numero != "") || (isset ($j15_compl) && $j15_compl != "")) {
									if ($sqlerro == false) {
										$cltestadanumero->j15_idbql = $cllote->j34_idbql;
										$cltestadanumero->j15_face = $j37_face;
										$cltestadanumero->j15_compl = $j15_compl;
										$cltestadanumero->j15_numero = $j15_numero;
										$cltestadanumero->incluir("");
										if ($cltestadanumero->erro_status == 0) {
											$sqlerro = true;
										}
									}
								}
							}
						}
					}

					$j34_idbql = $cllote->j34_idbql;
					$clcarlote->j35_idbql = $j34_idbql;
					$matriz = explode("X", $caracteristica);
					for ($i = 1; $i < sizeof($matriz); $i++) {

						$j35_caract = $matriz[$i];
						if ($j35_caract != "") {
							$clcarlote->j35_caract = $j35_caract;

							/**
							 * Caso haja alteraçao de caracteristica, alterar a data para a data atual do usuario
							 * Senao, mantem a que ja estava
							 */
							$clcarlote->j35_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
							if($iClcarloteNumrows > 0){

								$oCarLote = db_utils::fieldsMemory($rsCarLote, $i - 1);

								if($oCarLote->j35_caract == $j35_caract) {
									$clcarlote->j35_dtlanc = $oCarLote->j35_dtlanc;
								}
							}

							$clcarlote->incluir($j34_idbql, $j35_caract);

							if ($clcarlote->erro_status == 0) {
								$sqlerro = true;
							}
						}

					  if (isset($j54_codigo) && isset($j54_distan) && isset($j54_orientacao)) {

              $cllotedist->j54_idbql = $j34_idbql;
              $cllotedist->excluir($j34_idbql);
              if ($cllotedist->erro_status == 0) {
                db_msgbox("LOTEDIST : ".$cllotedist->erro_msg);
                $sqlerro = true;
              }

              if ($j54_codigo != "" && $j54_distan != "" ) {

                $cllotedist->j54_idbql      = $cllote->j34_idbql;
                $cllotedist->j54_codigo     = $j54_codigo;
                $cllotedist->j54_distan     = $j54_distan;
                $cllotedist->j54_orientacao = $j54_orientacao;
                $cllotedist->incluir($j34_idbql);

                if ($cllotedist->erro_status == 0){
                  db_msgbox("LOTEDIST : ".$cllotedist->erro_msg);
                  $sqlerro = true;
                }
              }

            }

					}
					//  ALTERACAO  NA TABELA LOTESETORFISCAL

					if (isset ($mostrasetfiscal) && $mostrasetfiscal == 't') {
						if (!isset ($j91_codigo) || $j91_codigo == "") {
							$cllotesetorfiscal->excluir("", " j91_idbql = $cllote->j34_idbql ");
							if ($cllotesetorfiscal->erro_status == "0") {
								$trans_erro = true;
							}
						} else
							if (isset ($j91_codigo) && $j91_codigo != "") {
								$cllotesetorfiscal->excluir("", " j91_idbql = $cllote->j34_idbql ");
								$cllotesetorfiscal->j91_idbql = $cllote->j34_idbql;
								$cllotesetorfiscal->j91_codigo = $j91_codigo;
								$cllotesetorfiscal->incluir($cllote->j34_idbql);
								if ($cllotesetorfiscal->erro_status == "0") {
									$trans_erro = true;
								}
							}
					}

					//ALTERACAO NA TABELA LOTELOC
					if (isset ($j06_setorloc) && $j06_setorloc != "") {
						$clloteloc->j06_idbql = $cllote->j34_idbql;
						$result = $clloteloc->sql_record($clloteloc->sql_query($cllote->j34_idbql));
						if ($clloteloc->numrows > 0) {
							$clloteloc->alterar($cllote->j34_idbql);
						} else {
							$clloteloc->incluir($cllote->j34_idbql);
						}
					}
					//============================================
				}
				db_fim_transacao($sqlerro);
				$db_opcao = 2;

			} else if (isset ($j34_idbql) || isset ($alterando) || isset ($chavepesquisa) && !isset ($incluquadra)) {

					if (isset ($chavepesquisa)) {
						$j34_idbql = $chavepesquisa;
					}

					if (isset ($alterando)) {

						$result = $cliptubase->sql_record($cliptubase->sql_query("", "j01_idbql", "", "j01_matric=$j01_matric"));
						db_fieldsmemory($result, 0);

						$result = $cllote->sql_record($cllote->sql_query($j01_idbql, "j34_idbql", "", ""));
						db_fieldsmemory($result, 0);
					}
					//setor fiscal ================================================================================================================
					$rsResultsetfis = $cllotesetorfiscal->sql_record($cllotesetorfiscal->sql_query("", "j91_codigo, j90_descr", "", " j91_idbql = $j34_idbql"));
					if ($cllotesetorfiscal->numrows != 0) {

						db_fieldsmemory($rsResultsetfis, 0);
						$j91_codigo = $j91_codigo;
					}
					//=============================================================================================================================

          $testadainter = null;
					$carX = "";
          $sqlTesinter  = " select coalesce(j69_idbql,0) as idbql, ";
          $sqlTesinter .= "				 j39_orientacao as orientacao, ";
          $sqlTesinter .= "				 j39_testad as testad, ";
          $sqlTesinter .= "				 j84_tesintertipo, ";
          $sqlTesinter .= "				 j39_testle as testle ";
          $sqlTesinter .= "		from tesinter ";
          $sqlTesinter .= " 	     left join tesinterlote   on j69_tesinter = j39_sequencial ";
          $sqlTesinter .= " 	     left join tesinteroutros on j84_tesinter = j39_sequencial ";
					$sqlTesinter .= " where j39_idbql = $j34_idbql ";

          $rsTestadaInter = $cltesinter->sql_record($sqlTesinter);
					for ($i = 0; $i < $cltesinter->numrows; $i++) {

					  db_fieldsmemory($rsTestadaInter,$i);
						$testadainter .= $carX.$idbql."-".$orientacao."-".$testad."-".$testle."-".$j84_tesintertipo;
					  $carX = "X";
					}

					$result = $cllote->sql_record($cllote->sql_query($j34_idbql));
					db_fieldsmemory($result, 0);

					$testasetor = true;

					$result = $clloteloteam->sql_record($clloteloteam->sql_query("", "", "loteloteam.j34_loteam,loteam.j34_descr", "", "loteloteam.j34_idbql=$j34_idbql"));
					$numrows = $clloteloteam->numrows;
					if ($result > 0) {
						db_fieldsmemory($result, 0);
					}

					$result = $cllotedist->sql_record($cllotedist->sql_query($j34_idbql));
					if ($cllotedist->numrows != 0) {
						db_fieldsmemory($result, 0);

					} else {

						$j54_codigo     = "";
						$j54_distan     = "";
						$j54_orientacao = "";
						$j14_nome       = "";

					}
					$result = $cltestpri->sql_record($cltestpri->sql_query_file($j34_idbql));
					if ($result) {

						db_fieldsmemory($result, 0);
						$cartestpri = $j49_face;
					}
					$result = $cltestada->sql_record($cltestada->sql_query_file($j34_idbql));
					$cartestada = null;
					$cart = "";
					for ($i = 0; $i < $cltestada->numrows; $i++) {
						db_fieldsmemory($result, $i);

            $sqlTestadaNumero  = " select coalesce( ( select j15_numero from testadanumero where j15_idbql=$j34_idbql and j15_face=$j36_face ),0) as j15_numero ,";
            $sqlTestadaNumero .= "        case";
            $sqlTestadaNumero .= "          when ( select j15_compl from testadanumero where j15_idbql=$j34_idbql and j15_face=$j36_face ) is null then '0'";
            $sqlTestadaNumero .= "          else ( select j15_compl from testadanumero where j15_idbql=$j34_idbql and j15_face=$j36_face )";
            $sqlTestadaNumero .= "        end as j15_compl";

            $rsTestadanumero   = db_query($sqlTestadaNumero);
            db_fieldsmemory($rsTestadanumero,0);
					  $cartestada .= $cart . $j36_face . "||" . $j36_codigo . "||" . $j36_testad . "||" . $j36_testle . "||" . $j15_numero . "||" . $j15_compl;
            $cart = "x  ";
          }

					$result = $clcarlote->sql_record($clcarlote->sql_query($j34_idbql));
					$caracteristica = null;
					$car = "X";
					for ($i = 0; $i < $clcarlote->numrows; $i++) {
						db_fieldsmemory($result, $i);
						$caracteristica .= $car . $j35_caract;
						$car = "X";
					}
					$caracteristica .= $car;
					$db_opcao = 2;

					$db_botao = true;
				}

if (isset ($j34_areapreservada) && $j34_areapreservada == "" ) {
  $j34_areapreservada = 0;
}

if (isset ($j34_setor) && $j34_setor == "") {
	$j30_descr = "ss";
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript"
      src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      #j34_quadra, #j54_orientacao {
        width:92px;
      }
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('load').style.visibility='hidden'">
    <table width="790" align="center" border="0" cellspacing="0" cellpadding="0">
      <form name="form1" method="post" action="" onSubmit="return js_verifica_campos_digitados();">
        <tr>
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
            <input type="hidden" name="outrolote">
            <center>
              <?  require_once(modification("forms/db_frmlotealt.php")); ?>
            </center>
          </td>
        </tr>
      </form>
    </table>
  </body>
</html>
<?


if ($replote == true) {

	echo "<script>";
	if ($repete == "incluir") {
		echo "var confirma=confirm('Este Lote já foi cadastrado!  Deseja cadastrar outro?');";
	} else {
		echo "var confirma=confirm('Este Lote já foi cadastrado!  Deseja continuar a alteração?');";
	}
	echo "if(confirma){\n
		         document.form1.outrolote.value='$repete'; \n
		         document.form1.submit(); \n
		       }\n
		      ";
	echo "</script>";
	exit;

}
if (isset ($incluir) || isset ($alterar)) {
	if ($cllote->erro_status == "0") {
		$cllote->erro(true, false);
		if ($cllote->erro_campo != "") {
			echo "<script> document.form1." . $cllote->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1." . $cllote->erro_campo . ".focus();</script>";
		}
	} else {
		$cllote->erro(true, false);
		echo "<script>
				         parent.document.form1.idlote.value='" . $j34_idbql . "'; \n
				         parent.document.form1.idsetor.value='" . $j34_setor . "'; \n
				         parent.document.form1.idquadra.value='" . $j34_quadra . "'; \n
				         parent.js_parentiframe('lote',true);

				         </script>
				        ";
		if (isset ($idmatricu)) {
			db_redireciona("cad1_lotealt.php?j34_idbql=$j34_idbql&idmatricu=$idmatricu");
		} else {
			db_redireciona("cad1_lotealt.php?j34_idbql=$j34_idbql");
		}
	}
}
if (isset ($chavepesquisa) && !isset ($incluquadra)) {
	if (isset($idmatricu) && $idmatricu != "") {
		$cliptubase->j01_idbql = $j34_idbql;
		$cliptubase->j01_matric = $idmatricu;
		$cliptubase->alterar($idmatricu);
	}
	echo "<script>
		         parent.document.form1.idlote.value='" . $j34_idbql . "'; \n
		         parent.document.form1.idsetor.value='" . $j34_setor . "'; \n
		         parent.document.form1.idquadra.value='" . $j34_quadra . "'; \n
		         parent.js_parentiframe('lote',true);
		         </script>
		        ";
}
if (isset ($alterando) || isset ($novolote)) {
	echo "<script>
		         parent.document.form1.idsetor.value='" . $j34_setor . "'; \n
		         parent.document.form1.idquadra.value='" . $j34_quadra . "';";

	echo " parent.js_parentiframe('alterando',true);
		          </script>
		        ";
}
?>