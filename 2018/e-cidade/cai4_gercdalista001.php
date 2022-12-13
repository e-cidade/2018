<?php
/**
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

set_time_limit(0);
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));

$clcgm              = new cl_cgm;
$cllista            = new cl_lista;
$cllistadeb         = new cl_listadeb;
$cllistanotifica    = new cl_listanotifica;
$clListaCda         = new cl_listacda;
$clCertid           = new cl_certid;
$clCertdiv          = new cl_certdiv;
$clArreforo         = new cl_arreforo;
$clArrecad          = new cl_arrecad;
$clCertter          = new cl_certter;
$clPardivultcodcert = new cl_pardivultcodcert;
$clrotulo           = new rotulocampo;
$clPardiv           = new cl_pardiv;

$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css");
  ?>
</head>
<body class="body-default" >
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>Dados da Lista</legend>

        <table>
          <tr>
            <td nowrap title="<?php echo $Tk60_codigo?>" >
              <?php
                db_ancora($Lk60_codigo, "js_pesquisalista(true);", 4);
              ?>
            </td>

            <td>
              <?php
                db_input("k60_codigo",  4, $Ik60_codigo, true, "text", 4, "onchange='js_pesquisalista(false);'");
                db_input("k60_descr",  40, $Ik60_descr,  true, "text", 3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="tipolista"><strong>Opções de lista:</strong></label>
            </td>
            <td>
              <?
                $aTipoLista = array("lista"       => "Somente gerados na lista",
                                    "notificados" => "Somente notificados",
                                    "noticonf"    => "Somente notificados e confirmados");
                db_select("tipolista", $aTipoLista, true, 1, "");
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="opcoes"><strong>Opções:</strong></label>
            </td>
            <td>
              <?
                $aOpcoes = array("todos" => "Por CGM/Inscrição/Matricula",
                                 "cgm"   => "Por CGM");
                db_select("opcoes", $aOpcoes, true, 1, "");
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="agrupa"><strong>Gerar CDA uma para cada ano:</strong></label>
            </td>
            <td>
              <?
                $aAgrupa = array("n" => "Não",
                                 "s" => "Sim");
                db_select("agrupa", $aAgrupa, true, 1, "");
              ?>
            </td>
          </tr>

        </table>
      </fieldset>
      <input  name="geracda" id="" type="submit" value="Gerar CDA(s)" onclick= "return js_validacodigo()" >
      <?php
        if (isset ($geracda)) {
          db_criatermometro("termometro",'concluido...','blue',1,"Gerando CDA(s)...");
        }
      ?>
    </form>
  </div>
  <?php
    db_menu( db_getsession("DB_id_usuario"),
             db_getsession("DB_modulo"),
             db_getsession("DB_anousu"),
             db_getsession("DB_instit") );
  ?>
</body>

<script>
  function js_validacodigo(){
    if (document.form1.k60_codigo.value == ""){
      alert ("Preencha o campo Código da lista ! ");
      document.form1.k60_codigo.focus();
      return false;
    }else{
      return true;
    }
  }

  function js_pesquisalista(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lista','func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lista','func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa','false');
    }
  }

  function js_mostralista(chave,erro){
    document.form1.k60_descr.value = chave;
    if(erro==true){
      document.form1.k60_descr.focus();
      document.form1.k60_descr.value = '';
    }
    db_iframe_lista.hide();
  }

  function js_mostralista1(chave1,chave2){
    document.form1.k60_codigo.value = chave1;
    document.form1.k60_descr.value = chave2;
    db_iframe_lista.hide();
  }
</script>
<?php
$tot         = 0;
$sqldivida   = "";
$campos      = "";
$mostrabotao = 'f';
$count       = 0;
$count6      = 0;
$todascda    = "CDA(s) Geradas : ";
$aCertidoes  = array();
//**************************************************************************************************************************************

if (isset ($geracda)) {

  db_inicio_transacao();

  try {

	  $sqlperm  = "select *                                               ";
	  $sqlperm .= "  from lista                                           ";
	  $sqlperm .= "       left join listatipos on k60_codigo  = k62_lista ";
	  $sqlperm .= "       inner join arretipo  on k62_tipodeb = k00_tipo  ";
    $sqlperm .= " where k60_codigo = $k60_codigo                        ";
    $sqlperm .= "   and k03_tipo not in (5, 6, 17, 21, 26)              ";
	  $sqlperm .= "   and k60_instit = {$instit}                          ";

	  $resultperm = db_query($sqlperm);
	  $linhasperm = pg_numrows($resultperm);
	  if ($linhasperm > 0) {
	    throw new Exception("Lista com tipos de débitos não permitidos !");
	  }

    $sqltipo  = " select *                                                 ";
    $sqltipo .= "   from lista                                             ";
    $sqltipo .= "         inner join listatipos on k60_codigo  = k62_lista ";
    $sqltipo .= "         inner join arretipo   on k62_tipodeb = k00_tipo  ";
    $sqltipo .= " where k60_codigo = {$k60_codigo}                         ";
    $sqltipo .= "   and k60_instit = {$instit}                             ";
    $resulttipo = db_query($sqltipo);
    $linhastipo = pg_numrows($resulttipo);
    if ($linhastipo == 0) {
      throw new Exception("Sem tipos de débitos definidos na lista !");
    }

    if ($tipolista == "lista") {
      $sqldivida = "";
    } elseif ($tipolista == "notificados") {
      $sqldivida = " inner join listanotifica on listanotifica.k63_numpre = listadeb.k61_numpre ";
    } elseif ($tipolista == "noticonf") {

      $sqldivida  = " inner join listanotifica on listanotifica.k63_numpre = listadeb.k61_numpre ";
      $sqldivida .= " inner join noticonf on noticonf.k54_notifica = listanotifica.k63_notifica ";
    }

    if (isset ($opcoes) && $opcoes == "todos") {
      $campos = " k00_matric, k00_inscr, k00_numcgm ";
    } elseif (isset ($opcoes) && $opcoes == "cgm") {
      $campos = " k00_numcgm ";
    }

    if ($campos!="") {
      $campos.=" , v01_exerc ";
    } else if ($campos=="") {
      $campos.=" v01_exerc ";
    }

    $sql = "select * from (select  distinct v01_coddiv,
																		        v01_exerc,
																			      k60_codigo,
																			      k61_numpre,
																			      k61_numpar,
																			      arrematric.k00_matric,
																			      arreinscr.k00_inscr,
																			      arrenumcgm.k00_numcgm,
																			      arrecad.k00_tipo,
																			      cadtipo.k03_tipo,
																			      0 as v07_parcel
                              from lista
                                   inner join listadeb on k60_codigo = k61_codigo
                                   ".$sqldivida."
                                   inner join divida     on k61_codigo            = k60_codigo
                                                        and k61_numpre            = v01_numpre
                                                        and k61_numpar            = v01_numpar
                                                        and v01_instit            = ".db_getsession('DB_instit')."
                                   inner join arrecad    on v01_numpre            = arrecad.k00_numpre
                                                        and v01_numpar            = arrecad.k00_numpar
                                   inner join arrenumcgm on arrecad.k00_numpre    = arrenumcgm.k00_numpre
													         inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo
													         inner join cadtipo    on cadtipo.k03_tipo      = arretipo.k03_tipo
													         left join arrematric  on arrematric.k00_numpre = arrecad.k00_numpre
													         left join arreinscr   on arreinscr.k00_numpre  = arrecad.k00_numpre
                             where k60_codigo       = {$k60_codigo}
                               and cadtipo.k03_tipo = 5
                               and lista.k60_instit = {$instit}
                        union
                          select  distinct  0 as v01_coddiv,
																			      0 as v01_exerc,
																			      k60_codigo,
																			      k61_numpre,
																			      0 as k61_numpar,
																			      arrematric.k00_matric,
																			      arreinscr.k00_inscr,
																			      arrenumcgm.k00_numcgm,
																			      arrecad.k00_tipo,
																			      cadtipo.k03_tipo,
																			      termo.v07_parcel
                            from lista
                                 inner join listadeb on k60_codigo = k61_codigo
                                 ".$sqldivida."
                                 inner join termo      on k61_codigo = k60_codigo
                                                      and v07_numpre = k61_numpre
                                                      and v07_instit = ".db_getsession('DB_instit')."
																 inner join arrecad    on v07_numpre            = arrecad.k00_numpre
                                 inner join arrenumcgm on arrecad.k00_numpre    = arrenumcgm.k00_numpre
																 inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo
																 inner join cadtipo    on cadtipo.k03_tipo      = arretipo.k03_tipo
																 left  join arrematric on arrematric.k00_numpre = arrecad.k00_numpre
																 left  join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre
                           where k60_codigo       = ".$k60_codigo."
                             and cadtipo.k03_tipo = 6
                             and lista.k60_instit = $instit) as x
                        order by  k00_tipo, {$campos} ; ";

    $rsResult = db_query($sql);
    $mat      = pg_numrows($rsResult);

    if ($mat == 0){
      throw new Exception("Voce não pode gerar CDA para esta lista !");
    }

    /**
     * Inicio geracao de cda's
     */
	      $newchave = "";
	      $chave    = "";
	      $cdaini   = 0;
	      $cdafim   = 0;
	      $entrou   = false;
	      $perc     = 0;
	      $ultano   = "";

	      for ($contalistacda = 0; $contalistacda < $mat; $contalistacda ++) {
	        db_atutermometro($contalistacda, $mat, 'termometro');

	        db_fieldsmemory($rsResult, $contalistacda,true);

	        $numpre      = $k61_numpre;
	        $numpar      = $k61_numpar;
	        $tipo_debito = $k00_tipo;

	        if ($k03_tipo == 5) {
	          $res = debitos_numpre($numpre, 0, $tipo_debito, db_getsession("DB_datausu"), db_getsession("DB_anousu"), $numpar, "k00_numpre,k00_numpar");
	        }elseif ($k03_tipo == 6) {
	          $res = debitos_numpre($numpre, 0, $tipo_debito, db_getsession("DB_datausu"), db_getsession("DB_anousu"), 0, "k00_numpre,k00_numpar");
	        }

	        if ($res == false) {
	          continue;
	        }

	        if($opcoes=="cgm") {
	          $chave = str_pad($k00_numcgm, 10, "0", STR_PAD_LEFT);
	        } else {
	          $chave = str_pad($k00_matric, 10, "0", STR_PAD_LEFT).str_pad($k00_inscr, 10, "0", STR_PAD_LEFT).str_pad($k00_numcgm, 10, "0", STR_PAD_LEFT);
	        }

	        if (  $newchave != $chave ||
	             ($newchave == $chave && isset($agrupa) && $agrupa == "s" && $k03_tipo == 5 && $v01_exerc != $ultano)) {

	          if ($entrou == false) {
	            $r = db_query("select coalesce(v05_codultcert,0) + 1 as v13_certid from pardivultcodcert limit 1 for update");
	            db_fieldsmemory($r, 0);
	            $clPardivultcodcert->v05_codultcert = $v13_certid;
	            $clPardivultcodcert->alterar(null);
	            if ($clPardivultcodcert->erro_msg == "0") {
	            	throw new Exception($clPardivultcodcert->erro_msg);
	            }
	            $entrou = true;
	            $cdaini = $v13_certid;
	          } else {
	            $v13_certid++;
	          }

	          $clCertid->v13_certid = $v13_certid;
	          $clCertid->v13_dtemis = date("Y-m-d", db_getsession("DB_datausu"));
	          $clCertid->v13_memo   = "null";
	          $clCertid->v13_login  = db_getsession("DB_id_usuario");
	          $clCertid->v13_instit = $instit;
	          $clCertid->incluir($v13_certid);
	          if ($clCertid->erro_status == 0) {
	          	throw new Exception($clCertid->erro_msg);
	          }
	          $todascda .= $v13_certid." - ";

	          if($opcoes=="cgm") {
	            $newchave = str_pad($k00_numcgm, 10, "0", STR_PAD_LEFT);
	          } else {
	            $newchave = str_pad($k00_matric, 10, "0", STR_PAD_LEFT).str_pad($k00_inscr, 10, "0", STR_PAD_LEFT).str_pad($k00_numcgm, 10, "0", STR_PAD_LEFT);
	          }

	          if ($k03_tipo == 5 && (isset($agrupa) && $agrupa == "s")) {
	            $ultano = $v01_exerc;
	          }
	          $tot++;

	        }
	        // Processa CDA de Divida Ativa
	        if ($k03_tipo == 5) {

	          if (pg_num_rows($res) != 0) {

	            db_fieldsmemory($res, 0);
	            $clCertdiv->v14_certid = $v13_certid;
	            $clCertdiv->v14_coddiv = $v01_coddiv;
	            $clCertdiv->v14_vlrhis = $vlrhis;
	            $clCertdiv->v14_vlrcor = $vlrcor;
	            $clCertdiv->v14_vlrjur = $vlrjuros;
	            $clCertdiv->v14_vlrmul = $vlrmulta;
	            $clCertdiv->incluir($v13_certid,$v01_coddiv);
              if ($clCertdiv->erro_status == 0) {
              	throw new Exception($clCertdiv->erro_msg);
              }
	            $rsArrecad = $clArrecad->sql_record($clArrecad->sql_query_file(null,"*",null,"k00_numpre = $numpre and k00_numpar = $numpar"));
              if (pg_num_rows($rsArrecad) == 0) {
                throw new Exception("Nenhum registro encontrado para o numpre {$numpre} em Aberto(Arrecad)");
              }
	            for ($i=0; $i<pg_num_rows($rsArrecad); $i++) {
                $oDadosArrecad = db_utils::fieldsMemory($rsArrecad,$i);

                $clArreforo->k00_numcgm   = $oDadosArrecad->k00_numcgm;
                $clArreforo->k00_dtoper   = $oDadosArrecad->k00_dtoper;
                $clArreforo->k00_receit   = $oDadosArrecad->k00_receit;
                $clArreforo->k00_hist     = $oDadosArrecad->k00_hist;
                $clArreforo->k00_valor    = $oDadosArrecad->k00_valor;
                $clArreforo->k00_dtvenc   = $oDadosArrecad->k00_dtvenc;
                $clArreforo->k00_numpre   = $oDadosArrecad->k00_numpre;
                $clArreforo->k00_numpar   = $oDadosArrecad->k00_numpar;
                $clArreforo->k00_numtot   = $oDadosArrecad->k00_numtot;
                $clArreforo->k00_numdig   = $oDadosArrecad->k00_numdig;
                $clArreforo->k00_tipo     = $oDadosArrecad->k00_tipo;
                $clArreforo->k00_tipojm   = $oDadosArrecad->k00_tipojm;
                $clArreforo->k00_certidao = $v13_certid;
                $clArreforo->incluir();
                if ($clArreforo->erro_status == 0) {
                  throw new Exception("Erro incluindo na arreforo. Erro do Banco: ".$clArreforo->erro_msg);
                }
              }
              $sCampoParDiv = "v04_tipocertidao as tipocertidao";
              $sSqlPardiv   = $clPardiv->sql_query_param(null, $sCampoParDiv, null,"v04_instit  = ".db_getsession('DB_instit'));
	            $rsPardiv     = $clPardiv->sql_record($sSqlPardiv);

							if (pg_num_rows($rsPardiv) > 0 ) {
								db_fieldsmemory($rsPardiv,0);
							}else{
	              db_fim_transacao(true);
	              unset($gerada);
								throw new Exception("Configure o parametro para o tipo de debito de certidao do foro ");
							}
	            $clArrecad->k00_tipo = $tipocertidao;
	            $clArrecad->alterar('', "k00_numpre = {$numpre} and k00_numpar = {$numpar} ");
	            if ($clArrecad->erro_status == '0') {
	            	throw new Exception($clArrecad->erro_msg);
	            }
	          }
	        // Processa CDA de Parcelamento
	        }elseif ($k03_tipo == 6) {

	          if (pg_num_rows($res) != 0) {
	            db_fieldsmemory($res, 0);

	            $clCertter->v14_certid = $v13_certid;
	            $clCertter->v14_parcel = $v07_parcel;
	            $clCertter->v14_vlrhis = $vlrhis;
	            $clCertter->v14_vlrcor = $vlrcor;
	            $clCertter->v14_vlrjur = $vlrjuros;
	            $clCertter->v14_vlrmul = $vlrmulta;
	            $clCertter->incluir($v13_certid, $v07_parcel);
	            if($clCertter->erro_status == '0'){
	            	throw new Exception($clCertter->erro_msg);
	            }

	            $rsArrecad = $clArrecad->sql_record($clArrecad->sql_query_file(null,"*",null,"k00_numpre = $numpre"));
	            if (pg_num_rows($rsArrecad) == 0) {
	            	throw new Exception("Nenhum registro encontrado para o numpre {$numpre} em Aberto(Arrecad)");
	            }
	            for ($i=0; $i< pg_num_rows($rsArrecad); $i++) {
	            	$oDadosArrecad = db_utils::fieldsMemory($rsArrecad,$i);

		            $clArreforo->k00_numcgm   = $oDadosArrecad->k00_numcgm;
	              $clArreforo->k00_dtoper   = $oDadosArrecad->k00_dtoper;
	              $clArreforo->k00_receit   = $oDadosArrecad->k00_receit;
	              $clArreforo->k00_hist     = $oDadosArrecad->k00_hist;
	              $clArreforo->k00_valor    = $oDadosArrecad->k00_valor;
	              $clArreforo->k00_dtvenc   = $oDadosArrecad->k00_dtvenc;
	              $clArreforo->k00_numpre   = $oDadosArrecad->k00_numpre;
	              $clArreforo->k00_numpar   = $oDadosArrecad->k00_numpar;
	              $clArreforo->k00_numtot   = $oDadosArrecad->k00_numtot;
	              $clArreforo->k00_numdig   = $oDadosArrecad->k00_numdig;
	              $clArreforo->k00_tipo     = $oDadosArrecad->k00_tipo;
	              $clArreforo->k00_tipojm   = $oDadosArrecad->k00_tipojm;
	              $clArreforo->k00_certidao = $v13_certid;
	              $clArreforo->incluir();
	              if ($clArreforo->erro_status == '0') {
	                throw new Exception("Erro incluindo na arreforo. Erro do Banco:".$clArreforo->erro_msg);
	              }

	            }

              $sCampoParDiv = "v04_tipocertidao as tipocertidao";
              $sSqlPardiv   = $clPardiv->sql_query_param(null, $sCampoParDiv, null,"v04_instit  = ".db_getsession('DB_instit'));
	            $rsPardiv     = $clPardiv->sql_record($sSqlPardiv);
							if (pg_num_rows($rsPardiv) > 0 ) {
								db_fieldsmemory($rsPardiv, 0);
							} else {
	              unset($gerada);
	              throw new Exception("Configure o parametro para o tipo de debito de certidao do foro ");
							}
	            $clArrecad->k00_tipo = $tipocertidao;
	            $clArrecad->alterar(null, "k00_numpre = {$numpre} ");
	            if ($clArrecad->erro_status == '0') {
	            	throw new Exception($clArrecad->erro_msg);
	            }
	          }
	        }


	        if (!in_array($v13_certid, $aCertidoes)) {

		        $clListaCda->v81_lista  = $k60_codigo;
		        $clListaCda->v81_certid = $v13_certid;
		        $clListaCda->incluir(null);
		        if ($clListaCda->erro_status == '0') {
		        	throw new Exception($clListaCda->erro_msg);
		        }

		        $aCertidoes[] = $v13_certid;

          }

	      }

	      if (@$v13_certid != ""){

	        $clPardivultcodcert->v05_codultcert = $v13_certid;
	        $clPardivultcodcert->alterar(null);
	        if ($clPardivultcodcert->erro_status == '0') {
	        	throw new Exception($clPardivultcodcert->erro_msg);
	        }
	        $cdafim = $v13_certid;
	      }

	      $mostrabotao = 't';

	      if (isset($tot) && $tot > '0') {
	        $msg = "Operação realizada com sucesso ! \\n Foram gerada(s) : {$tot} CDA(s)\\nIntervalo de {$cdaini} a {$cdafim} ";
	        db_msgbox($msg);
	        unset($geracda);
	      }

	  db_fim_transacao(false);
	  db_redireciona("cai4_gercdalista001.php");

  } catch (Exception $eException) {

  	db_fim_transacao(true);
  	db_msgbox($eException->getMessage());
  	db_redireciona("cai4_gercdalista001.php");
  }

}
?>
</html>