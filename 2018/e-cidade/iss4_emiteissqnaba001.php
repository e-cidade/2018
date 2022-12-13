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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_issbase_classe.php"));
require_once(modification("classes/db_isscalc_classe.php"));
require_once(modification("classes/db_arrecad_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$erro           = false;
$descricao_erro = false;

$iAnousu        = db_getsession('DB_anousu');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
   db_app::load("scripts.js");
   db_app::load("prototype.js");
   db_app::load("datagrid.widget.js");
   db_app::load("strings.js");
   db_app::load("grid.style.css");
   db_app::load("estilos.css");
   db_app::load("classes/dbViewAvaliacoes.classe.js");
   db_app::load("widgets/windowAux.widget.js");
   db_app::load("widgets/dbmessageBoard.widget.js");
   db_app::load("dbcomboBox.widget.js");
   db_app::load("DBHint.widget.js");
?>
</head>
<body class="body-default abas" onload="js_mostraTipoImp(document.form1.arq);">

<div class="container">

<form name="form1" action="" method="post">

<fieldset>
  <legend>Emissão ISSQN</legend>

 <input type="hidden" name="cgmescrito" value="">
 <input type="hidden" name="k00_tipoant" value="">

  <table border="0" class="form-container" style="width: 500px;">
	  <tr>
	    <td>
        <label for="k03_tipo">Tipo para impressão:</label>
	    </td>
	    <td>
	      <?php

		      $aOpcoes = array ("2"  => "Fixo",
		                        "3"  => "Variável",
		                        "19" => "Vistorias",
		                        "5"  => "Vistorias sem ISSQN");

		      db_select('k03_tipo', $aOpcoes, true, 1,"onchange='js_submitform();'");
	      ?>
	    </td>
	  </tr>

	  <tr>
	     <?php

        /**
         * Opção default "Fixo"
         */
				if (!isset($k03_tipo)) {
					$k03_tipo = 2;
				}

				if (isset($k03_tipo)) {
				?>
    	    <td>
            <label for="k00_tipo">Tipo de débito:</label>
    	    </td>
    	    <td>
    	      <?php

    					if ($k03_tipo == 19 or $k03_tipo == 5) {

    						$sSql  = " select distinct arrecad.k00_tipo, arretipo.k00_descr                                    ";
    						$sSql .= "   from vistorias                                                                        ";
    						$sSql .= " 		   inner join vistorianumpre on vistorianumpre.y69_codvist   = vistorias.y70_codvist ";
    						$sSql .= "		     inner join arrecad        on k00_numpre                   = y69_numpre          ";
    						$sSql .= "				   							          and extract (year from y70_data) = {$iAnousu}          ";
    						$sSql .= "		     inner join arretipo       on arrecad.k00_tipo             = arretipo.k00_tipo   ";

    					} else {

    						$sSql  = " select distinct arrecad.k00_tipo, arretipo.k00_descr               ";
    						$sSql .= "   from isscalc                                                     ";
    						$sSql .= "	 		  inner join arrecad  on k00_numpre       = q01_numpre        ";
    						$sSql .= "			 									   and q01_anousu       = {$iAnousu}        ";
    						$sSql .= "			  inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
    						$sSql .= " where k03_tipo = {$k03_tipo}                                       ";
    					}

    					$oDaoTipoDebito  = db_query($sSql);

              if ($oDaoTipoDebito) {

                $iTotalRegistros = 0;
      					$iTotalRegistros = pg_numrows($oDaoTipoDebito);

      					$aTipoDebito = array("0" => " Selecione o tipo de débito ");

      					for($iIndice=0; $iIndice < $iTotalRegistros; $iIndice++){

      						$oTipoDebito = db_utils::fieldsMemory($oDaoTipoDebito, $iIndice);
      						$aTipoDebito[$oTipoDebito->k00_tipo] = $oTipoDebito->k00_tipo." - ".$oTipoDebito->k00_descr;
      					}

      					if(isset($k00_tipoant) && $k00_tipoant != ""){
      					  $k00_tipo = $k00_tipoant;
      					}
      					db_select('k00_tipo', $aTipoDebito, true, 1,"onchange='js_controlaSelectTipo();'");
              }
            ?>
	        </td>
	  </tr>

    <?php
     if ($k03_tipo == 3){
    ?>
    <tr>
      <td>
        <label for="numparini">Parcelas de:</label>
      </td>
      <td>
        <input type="text" id="numparini" name="numparini" size="5" value=<?php echo (isset($numparini)?$numparini:"1");  ?> />
        <strong>A</strong>
	      <input type="text" id="numparfim" name="numparfim" size="5" value=<?php echo (isset($numparfim)?$numparfim:"12"); ?> />
      </td>
    </tr>

	  <tr>
	    <td>
	      <label for="emiteVal"> Emite Valores:</label>
	    </td>
	    <td>
	      <?php
		      $aOpcoes = array ( "0" => "Nenhum",
		                         "1" => "Emite Valor Lançado",
		                         "2" => "Emite Valor Zerado");

		      db_select('emiteVal', $aOpcoes, true, 1,"onchange='js_submitform();'");
	      ?>
	    </td>
	  </tr>
	  <?php
     } // If issqn variável
    ?>

		<tr>
	    <td>
	      <label for="arq">Arquivo:</label>
	    </td>
	    <td>
	      <?php
		      $aOpcoes = array ( "pdf"    => "PDF",
		                         "txt"    => "TXT",
                             "bsjtxt" => "TXT/BSJ");
		      db_select('arq', $aOpcoes, true, 1,"onchange='js_mostraordem(); js_submitform();'");
	      ?>
	    </td>
	  </tr>
    <?php
     } // If issqn variável
		?>

	  <tr>
	    <td>
	      <label for="emis">Tipo emissão:</label>
	    </td>
	    <td>
	      <?php
		      $aOpcoes = array ("geral"   => "Geral",
		                        "comescr" => "Com os escritórios",
		                        "semescr" => "Sem os escritórios");

		      db_select('emis', $aOpcoes, true, 1,"onchange='js_mostraordem();'");
	      ?>
	    </td>
	  </tr>

    <tr id="m_imprimir">
      <td>
        <label for="impr">Imprimir:</label>
      </td>
      <td>
        <?php
	        $aOpcoes = array ("todas"      => "Todas",
	                          "socotunica" => "Só Cota Única",
	                          "soparcela"  => "Só Parcelas");
	        db_select('impr', $aOpcoes, true, 1);
        ?>
      </td>
    </tr>

	  <tr>
	    <td>
        <label for="ord">Ordem:</label>
	    </td>
	    <td>
	      <?php
		      $aOpcoes = array ("inscricao"  => "Inscricão",
		                        "nome"       => "Nome",
		                        "escritorio" => "Escritorio");
		      db_select('ord', $aOpcoes, true, 1);
	      ?>
	      </div>
	    </td>
	  </tr>

	  <tr>
	    <td>
       <label for="quantidade">Quantidade de registros do select:</label>
	    </td>
      <td>
	      <input type="text" name="quantidade" id="quantidade" value="<?php echo (isset($quantidade)?$quantidade:""); ?>" />
	    </td>
	  </tr>

	  <tr>
	    <td>
       <label for="quantidade_registros_real">Quantidade de registros a gerar no txt:</label>
	    </td>
      <td>
	     <input type="text" name="quantidade_registros_real" id="quantidade_registros_real" value="<?php echo (isset($quantidade_registros_real)?$quantidade_registros_real:"");?>" />
	   </td>
	  </tr>

		<tr id="idTipoImp">
      <td>
	      <label for="imprimeparcelas">Imprimir parcelas:</label>
      </td>
      <td>
        <?php
	        $aOpcoes = array ("s" => "Sim", "n" => "Não");
	        db_select('imprimeparcelas', $aOpcoes, true, 1);
				?>
      </td>
    </tr>

    <tr>
      <td>
      <?php

    	  if (isset($k03_tipo)) {

          $sSql  = " select distinct * from                                                   ";
          $sSql .= "      (select recibounica.k00_dtvenc as k00_dtvenc,                  ";
          $sSql .= "              recibounica.k00_dtoper as k00_dtoper,                  ";
          $sSql .= "              recibounica.k00_percdes                                     ";
          $sSql .= "         from recibounica                                                 ";
          $sSql .= "              inner join isscalc  on q01_numpre = recibounica.k00_numpre  ";
          $sSql .= "                                 and q01_anousu = {$iAnousu}              ";
          $sSql .= "              inner join arrecad  on q01_numpre = arrecad.k00_numpre      ";
          $sSql .= "              inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
          $sSql .= "                                 and arretipo.k03_tipo = $k03_tipo        ";
          $sSql .= "       where k00_tipoger = 'G' group by recibounica.k00_dtvenc, recibounica.k00_dtoper, k00_percdes) as x                 ";
          $sSql .= "  where k00_dtvenc > '".date('Y-m-d')."'                        ";
          $sSql .= " order by k00_dtvenc, k00_percdes                                         ";

          if ($k03_tipo == 19 || $k03_tipo == 5) {

            $sSql  = " select recibounica.k00_dtvenc as k00_dtvenc,                                          ";
            $sSql .= "        recibounica.k00_dtoper as k00_dtoper,                                          ";
            $sSql .= "        recibounica.k00_percdes                                                             ";
            $sSql .= "   from recibounica                                                                         ";
            $sSql .= "        inner join vistorianumpre   on y69_numpre                  = recibounica.k00_numpre ";
            $sSql .= "        inner join vistorias        on y70_codvist                 = y69_codvist            ";
            $sSql .= "                                   and extract(year from y70_data) = '{$iAnousu}'           ";
            $sSql .= "        inner join arrecad          on y69_numpre                  = arrecad.k00_numpre     ";
            $sSql .= "        inner join arretipo         on arretipo.k00_tipo           = arrecad.k00_tipo       ";
            $sSql .= "                                   and arretipo.k03_tipo           = $k03_tipo              ";
            $sSql .= "  where recibounica.k00_dtvenc > '".date('Y-m-d')."'                              ";
            $sSql .= "group by recibounica.k00_dtvenc, recibounica.k00_dtoper, k00_percdes order by k00_dtvenc, k00_percdes";
          }

          $result = db_query($sSql);

         if ($result && pg_numrows($result) > 0) { ?>
          <label for="totcheck">Unicas:</label>
      <? } ?>
      </td>
    </tr>

    <tr>
      <td>
        <?php

            if ($result && pg_numrows($result) > 0) {

              for ($iIndice = 0; $iIndice < pg_numrows($result); $iIndice ++) {

                db_fieldsmemory($result, $iIndice);
                $expressao = $k00_dtvenc . "=" . $k00_dtoper . "=" . $k00_percdes;
                ?>
                <input type="checkbox" value="<?=$expressao?>" name="check_<?=$iIndice?>" checked><?php echo "Vencimento: ".db_formatar($k00_dtvenc,"d")."- Lançamento: ".db_formatar($k00_dtoper,"d")."- Desconto: ".$k00_percdes."<br/>" ?>
                <?
              }
            }

      	} // Issqn variavel
        ?>
      <input name="totcheck" type="hidden" id="totcheck" value="<?=pg_numrows($result)?>" />
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
       <input name="geracarnes" type="submit" id="geracarnes" value="Gerar Carnes" onclick="return js_verifica();" />
      </td>
    </tr>

    <tr>
	    <td colspan="2" align="center" style="padding-top:10px;">
	      <input name="termometro" style="background: transparent" id="termometro" type="text" value="" size="50" />
	    </td>
    </tr>

 </table>
  </fieldset>
  <input name="processando" id="processando" style="color:#FF0000; border:none; visibility:hidden" type="button" readonly value="Processando. Aguarde...">
 </form>
</div>
</body>
</html>
<script type="text/javascript">

var aEventoShow = new Array('onMouseover','onFocus');
var aEventoHide = new Array('onMouseout' ,'onBlur');

var oDbHintQuantidade = new DBHint('oDbHintQuantidade');
    sHintQuantidade   = "Quantidade de registros a processar no select principal. <br/> ";
    sHintQuantidade  += "Nao significa que vao ser gerados essa quantidade de registros no txt, <br/> ";
    sHintQuantidade  += "pois existes testes e bloqueios que podem limitar alguns registros, dependendo dos filtros. <br/> ";
    sHintQuantidade  += "<strong>* deixe em branco para processar todos </strong>";
    oDbHintQuantidade.setText(sHintQuantidade);
    oDbHintQuantidade.setShowEvents(aEventoShow);
    oDbHintQuantidade.setHideEvents(aEventoHide);
    oDbHintQuantidade.make($('quantidade'));

var oDbHintQuantidadeRegistrosReal = new DBHint('oDbHintQuantidadeRegistrosReal');
    sHintQuantidadeRegistrosReal   = "Quantidade de registros real a serem gerados no txt. <br/> ";
    sHintQuantidadeRegistrosReal  += "<br/> Valor limitado ao campo [Quantidade de registros do select].  <br/> ";
    sHintQuantidadeRegistrosReal  += "<strong>* deixe em branco para processar todos </strong>";
    oDbHintQuantidadeRegistrosReal.setText(sHintQuantidadeRegistrosReal);
    oDbHintQuantidadeRegistrosReal.setShowEvents(aEventoShow);
    oDbHintQuantidadeRegistrosReal.setHideEvents(aEventoHide);
    oDbHintQuantidadeRegistrosReal.make($('quantidade_registros_real'));

function js_mostra_processando(){
  document.form1.processando.style.visibility = 'visible';
}

function js_verifica(){

  /**
   * iTipo     - Tipo de débito para impressão
   * sImprimir - Todas / Só única / Só parcelas
   */
  var iTipo      = document.getElementById('k03_tipo').value;
  var sLabelTipo = "";
  var sImprimir  = document.getElementById('impr').value;

  if(iTipo == 3 || iTipo == 5 || iTipo == 19){

    if(sImprimir == 'socotunica'){

      if(iTipo == 3){
        sLabelTipo = "Variável";
      } else if(iTipo == 5){
        sLabelTipo = "Vistorias sem ISSQN";
      } else if(iTipo == 19){
        sLabelTipo = "Vistorias";
      }

      alert('Impressão não permitida favor verificar os filtros. \n - '+sLabelTipo+' \n - Só Cota Única');
      return false;

    } else {

      js_mostra_processando();
      parent.iframe_g2.js_mandadados();
      return true;
    }

  } else {

    js_mostra_processando();
    parent.iframe_g2.js_mandadados();
    return true;
  }
}

function js_mostraTipoImp( obj ){

  if ( obj.value == "txt" || obj.value == "bsjtxt" ) {

    document.getElementById('idTipoImp').style.display  = "none";
    document.getElementById('m_imprimir').style.display = "none";
  } else {
    document.getElementById('idTipoImp').style.display  = "";
  }
}

function js_controlaSelectTipo(){
  document.form1.k00_tipoant.value = document.form1.k00_tipo.value;
}

function js_submitform(){
  document.form1.submit();
}

function termo(qual, total, sql){

  if (sql == 0) {
    document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
  } else {
    document.getElementById('termometro').innerHTML='processando select...';
  }
}

function js_mostraordem(){

  if ( document.form1.emis.value == 'semescr') {

    document.form1.ord.options[0] = null;
    parent.document.formaba.g2.disabled = true;

  } else {

    if(document.form1.emis.value == 'comescr'){
      parent.document.formaba.g2.disabled = false;
    } else {
      parent.document.formaba.g2.disabled = true;
    }

    if (document.form1.ord.options[0].value != 'escritorio'){

      document.form1.ord.options[0] = new Option('Inscrição', 'inscricao');
      document.form1.ord.options[1] = new Option('Escritório', 'escritorio');
      document.form1.ord.options[2] = new Option('Nome', 'nome');
    }

  }
}
</script>

<?php

if (isset($geracarnes)) {

  if(isset($emiteVal) && $emiteVal == 0 ){

		$processa = false;
	  echo $processa;
	} else {

    $processa = true;
	  echo $processa;
	}

  $unica = "";
  $U     = "U";

  for ($iIndice=0; $iIndice < $totcheck; $iIndice++) {

    $check = "check_".$iIndice;
    if (isset($$check) and $$check != "--") {

      if ($iIndice == $totcheck-1) {
        $U = "";
      }
      $unica .= $$check.$U;
    }
  }

  if( $processa == true ){

		if (isset($arq) && ($arq == "txt" or $arq == "bsjtxt")) {

      echo " <script>
							 	 js_OpenJanelaIframe('','db_iframe_carne','iss4_emitetxtissqn003.php?quantidade=$quantidade&quantidade_registros_real=$quantidade_registros_real&selunica=$unica&k03_tipo=$k03_tipo&k00_tipo=".(isset($k00_tipoant)&&$k00_tipoant!=""?$k00_tipoant:$k00_tipo)."&arq=$arq&emis=$emis&ord=$ord&cgmescrito=$cgmescrito&imprimir=$impr','Emitindo carnes...',true,5);
						 </script> ";
    } else {

      if (isset($numparini)){

        if ($numparfim == ''){
          $numparfim = $numparini;
        }

        $fparc = "numparini={$numparini}&numparfim={$numparfim}";
			}

			if(isset($emiteVal)){
				$femite = "&emiteVal={$emiteVal}";
			} else {
        $femite = "";
			}

			echo " <script>
								js_OpenJanelaIframe('','db_iframe_carne','iss4_emiteissqn003.php?quantidade=$quantidade".$femite."&unica=$unica&quantidade_registros_real=$quantidade_registros_real&k03_tipo=$k03_tipo&imprimeparcelas=$imprimeparcelas&k00_tipo=".(isset($k00_tipoant)&&$k00_tipoant!=""?$k00_tipoant:$k00_tipo)."&arq=$arq&emis=$emis&ord=$ord&cgmescrito=$cgmescrito&imprimir=$impr&{$fparc}','Emitindo carnes...',true,5);
						 </script> ";
		}

  } else {
    echo "<script>alert('Selecionar uma opção do campo Emite Valor!');</script>";
  }
}

/**
 * Geração de carnes
 */
if( $erro == true ){
  echo "<script>alert('$descricao_erro');</script>";
}
?>