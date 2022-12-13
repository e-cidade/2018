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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oArreTipo = new cl_arquivo_auxiliar;
$oCaracteristicasPeculiares = new cl_arquivo_auxiliar();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label("c58_sequencial");

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta http-equiv="Expires" CONTENT="0"/>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css"/>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Débitos Cancelados</legend>

          <table align="center" width="650px" border="0">
            <tr>
              <td nowrap title="<?=@$Tz01_numcgm?>" >
                <?php db_ancora("CGM:","js_pesquisaz01_numcgm(true);",1); ?>
              </td>
              <td>
                <?php
        					db_input('z01_numcgm', 15, $Iz01_numcgm, true, 'text', 1, " onchange='js_pesquisaz01_numcgm(false);'");
      				    db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3, '');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=@$Tj01_matric?>" >
                <?php db_ancora('Matrícula:',"js_pesquisaj01_matric(true);",1); ?>
              </td>
              <td>
                <?php db_input('j01_matric',15,$Ij01_matric,true,'text',1,""); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=@$Tq02_inscr?>" >
                <strong>
                  <?php db_ancora('Inscrição:',"js_pesquisaq02_inscr(true);",1); ?>
                </strong>
              </td>
              <td>
                <?php db_input('q02_inscr',15,$Iq02_inscr,true,'text',1,""); ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Data Inicial:</strong>
              </td>
              <td>
                <?php

                  $dtd = date("d",db_getsession("DB_datausu"));
                  $dtm = date("m",db_getsession("DB_datausu"));
                  $dta = date("Y",db_getsession("DB_datausu"));

                  db_inputdata("datai","{$dtd}","{$dtm}","{$dta}","true","text",2);
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Data Final:</strong>
              </td>
              <td>
                <?php db_inputdata("dataf","{$dtd}","{$dtm}","{$dta}","true","text",2); ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Tipo de cancelamento:</strong>
              </td>
              <td>
                <?php

          			  $resulttipo = db_query("select 3 as k73_sequencial,'Todos' as k73_descricao union all select k73_sequencial,k73_descricao from cancdebitostipo ");
          			  $linhasTipo = pg_num_rows($resulttipo);
          			  $tipo = array();

          			  if($linhasTipo > 0 ){

          			    for($t=0;$t<$linhasTipo;$t++){
          			    	db_fieldsmemory($resulttipo, $t);
          					  $tipo[$k73_sequencial] = $k73_descricao;
          			    }
          			  }

          			  db_select("tipoDebito",$tipo,true,1,"onChange='js_mostraAgrupar(document.form1.tipoDebito.value);'");
			          ?>
              </td>
            </tr>

						<tr id="agr" >
              <td>
                <strong>Agrupar por:</strong>
              </td>
              <td>
                <?php
                  $arr = array("N"=> "Nenhum", "CP"=>"Característica Peculiar");
									db_select("agrupar",$arr,true,1,"onChange='js_mostraQuebrar(document.form1.agrupar.value);'");
                ?>
              </td>
            </tr>

						<tr id="queb">
              <td>
                <strong>Quebrar a página:</strong>
              </td>
              <td>
                <?php
                  $arrQuebra = array("N"=> "Não", "S"=>"Sim");
									db_select("quebrar",$arrQuebra,true,1);
                ?>
              </td>
            </tr>

            <tr>
              <td>
                <strong>Mostrar endereço:</strong>
              </td>
              <td>
                <?php
                  $aMostEnder = array("S"=>"Sim","N"=> "Não");
                  db_select("mostender",$aMostEnder,true,1);
                ?>
              </td>
            </tr>
          </table>


          <div id="div-renuncia">

            <fieldset class="separator"></fieldset>

            <table align="center">
              <tr>
                <td align="right">
                  <label class="bold" for="ifiltroconcarpeculiar" id="lbl_ifiltroconcarpeculiar">Opções:</label>
                </td>
                <td>
                  <?php

                    $aOpcoes = array(
                      "1" => "Com as caracteríticas peculiares selecionadas",
                      "2" => "Sem as caracteríticas peculiares selecionadas"
                    );

                    db_select( "iFiltroConCarPeculiar", $aOpcoes, true, 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php

                    $oCaracteristicasPeculiares->codigo         = "c58_sequencial";
                    $oCaracteristicasPeculiares->descr          = "c58_descr";
                    $oCaracteristicasPeculiares->nome_botao     = "concarpeculiar";
                    $oCaracteristicasPeculiares->Labelancora    = "Característica:";
                    $oCaracteristicasPeculiares->cabecalho      = "Características Peculiares";
                    $oCaracteristicasPeculiares->nomeobjeto     = 'aCaracteristicasPeculiares';
                    $oCaracteristicasPeculiares->funcao_js      = 'js_funcaocaracteristicapeculiar';
                    $oCaracteristicasPeculiares->funcao_js_hide = 'js_funcaocaracteristicapeculiar1';
                    $oCaracteristicasPeculiares->sql_exec       = "";
                    $oCaracteristicasPeculiares->func_arquivo   = "func_concarpeculiar.php";
                    $oCaracteristicasPeculiares->nomeiframe     = "iframe_concarpeculiar";
                    $oCaracteristicasPeculiares->localjan       = "";
                    $oCaracteristicasPeculiares->tipo           = 2;
                    $oCaracteristicasPeculiares->db_opcao       = 2;
                    $oCaracteristicasPeculiares->top            = 0;
                    $oCaracteristicasPeculiares->linhas         = 7;
                    $oCaracteristicasPeculiares->vwidth         = 520;
                    $oCaracteristicasPeculiares->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>

          </div>

          <fieldset class="separator"></fieldset>

          <table align="center" border="0">
            <tr>
              <td align="left" nowrap title="">
                <strong>Tipo:</strong>
              </td>
              <td align="left">
                <?php
                  $xx = array("c"=>"Completo","r"=>"Resumido por tipo","rc"=>"Resumido por contribuinte");
								  db_select('seltipo',$xx,true,4,"");
                ?>
                &nbsp;&nbsp;&nbsp;
              </td>
              <td  nowrap title="Ordem para a emissão do relatório">
                <strong>Ordem:</strong>
              </td>
              <td align="left">
                <?php
								  $xx = array("d"=>"Data","c"=>"CGM","m"=>"Matrícula","i"=>"Inscrição");
							 	  db_select('selordem',$xx,true,4,"");
                ?>
                &nbsp;&nbsp;&nbsp;
              </td>
              <td nowrap title="">
                <strong>Histórico:</strong>
              </td>
              <td align="left">
                <?php
                  $xx = array("s"=>"Sim","n"=>"Não");
							 	  db_select('selhist',$xx,true,4,"");
                ?>
                &nbsp;&nbsp;&nbsp;
              </td>
            </tr>
          </table>

          <table align="center">
            <tr>
              <td>
                <?php

                  $oArreTipo->codigo         = "k00_tipo";
                  $oArreTipo->descr          = "k00_descr";
                  $oArreTipo->cabecalho      = "Tipos de Débito";
                  $oArreTipo->nomeobjeto     = 'arqarretipo';
                  $oArreTipo->funcao_js      = 'js_funcaotipo';
                  $oArreTipo->funcao_js_hide = 'js_funcaotipo1';
                  $oArreTipo->sql_exec       = "";
                  $oArreTipo->func_arquivo   = "func_arretipo.php";
                  $oArreTipo->nomeiframe     = "iframe_arretipo";
                  $oArreTipo->localjan       = "";
                  $oArreTipo->tipo           = 2;
                  $oArreTipo->db_opcao       = 2;
                  $oArreTipo->top            = 0;
                  $oArreTipo->linhas         = 10;
                  $oArreTipo->vwidth         = 520;
                  $oArreTipo->funcao_gera_formulario();
                ?>
              </td>
            </tr>
          </table>
        </fieldset>

        <input name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" />
      </form>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit"));
    ?>
  </body>

  <script type="text/javascript">
    var sMensagens = "tributario.arrecadacao.cai2_debitocanc001.";

    function js_emite() {

      if ($F("datai") == '') {
        alert( _M(sMensagens + "campo_obrigatorio", {sCampo : "Data Inicial"}) );
        return false;
      }

      if ($F("dataf") == '') {
        alert( _M(sMensagens + "campo_obrigatorio", {sCampo : "Data Final"}) );
        return false;
      }

      qry  = '?seltipo=' + $F("seltipo");
      qry += '&selordem=' + $F("selordem");
      qry += '&selhist=' + $F("selhist");
      qry += '&datai=' + $F("datai_ano") + '-' + $F("datai_mes") + '-' + $F("datai_dia");
      qry += '&dataf=' + $F("dataf_ano") + '-' + $F("dataf_mes") + '-' + $F("dataf_dia");

      qry += '&z01_numcgm=' + $F("z01_numcgm");
      qry += '&j01_matric=' + $F("j01_matric");
      qry += '&q02_inscr=' + $F("q02_inscr");
      qry += '&tipoDebito=' + $F("tipoDebito");
      qry += '&agrupar=' + $F("agrupar");
      qry += '&quebrar=' + $F("quebrar");
      qry += '&mostender=' + $F("mostender");
      qry += '&iFiltroConCarPeculiar=' + $F("iFiltroConCarPeculiar");

      qry += '&arqarretipo=' + Array.apply(null, $('arqarretipo').options).map( function(oOption) {
                                  return oOption.value;
                                }).join(',');

      qry += '&aCaracteristicasPeculiares=' + Array.apply(null, $('aCaracteristicasPeculiares').options).map( function(oOption) {
                                  return oOption.value;
                                }).join(',');

      jan = window.open( 'cai2_debitocanc002.php' + qry,
                         '',
                         'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ' );
      jan.moveTo(0, 0);
    }


    function js_pesquisaz01_numcgm(mostra){

        $("z01_nome").value = '';
        if (mostra == true) {
          
          js_OpenJanelaIframe('top.corpo', 'db_iframe_nome', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true);
        }
        else {
            if (document.form1.z01_numcgm.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_nome', 'func_nome.php?pesquisa_chave=' + document.form1.z01_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false);
            }
            else {
                document.form1.kz01_numcgm.value = '';
            }
        }
    }

    function js_mostracgm(erro, chave){

        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.z01_numcgm.focus();
            document.form1.z01_numcgm.value = '';
        }
    }

    function js_mostracgm1(chave1, chave2){

        document.form1.z01_numcgm.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_nome.hide();
    }


    function js_pesquisaj01_matric(mostra){

        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_matric', 'func_iptubase.php?funcao_js=parent.js_mostramatric|j01_matric', 'Pesquisa', true);
        }
        else {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_matric', 'func_iptubase.php?pesquisa_chave=' + document.form1.j01_matric.value + '&funcao_js=parent.js_mostramatric', 'Pesquisa', false);
        }
    }


    function js_mostramatric(chave){

        document.form1.j01_matric.value = chave;
        db_iframe_matric.hide();
    }


    function js_pesquisaq02_inscr(mostra){

        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_inscr', 'func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr', 'Pesquisa', true);
        }
        else {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_inscr', 'func_issbase.php?pesquisa_chave=' + document.form1.q02_inscr.value + '&funcao_js=parent.js_mostrainscr', 'Pesquisa', false);
        }
    }

    function js_mostrainscr(chave){

        document.form1.q02_inscr.value = chave;
        db_iframe_inscr.hide();
    }

    function js_pesquisac58_sequencial(mostra){

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr&filtro=receita', 'Pesquisa', true, '0', '1');
        }
        else {
            if (document.form1.c58_sequencial.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?pesquisa_chave=' + document.form1.c58_sequencial.value + '&funcao_js=parent.js_mostraconcarpeculiar&filtro=receita', 'Pesquisa', false);
            }
            else {
                document.form1.c58_descr.value = '';
            }
        }
    }

    function js_mostraconcarpeculiar(chave, erro){

        document.form1.c58_descr.value = chave;
        if (erro == true) {
            document.form1.c58_sequencial.focus();
            document.form1.c58_sequencial.value = '';
        }
    }

    function js_mostraconcarpeculiar1(chave1, chave2){

        document.form1.c58_sequencial.value = chave1;
        document.form1.c58_descr.value = chave2;
        db_iframe_concarpeculiar.hide();
    }


    function js_mostraAgrupar(iValor) {

      $("div-renuncia").hide();
      $("agr").hide();

      if (iValor != 1) {

        $("agr").show();
      }

      if (iValor == 2) {

        while($("aCaracteristicasPeculiares").length > 0) {
       
          $("aCaracteristicasPeculiares").remove($("aCaracteristicasPeculiares").length -1);
        }
        $("div-renuncia").show();
      }

      js_mostraQuebrar("N");
      $('agrupar').value = "N";
    }

    function js_mostraQuebrar(qb) {

      $("queb").hide();

      if (qb != "N") {
        $("queb").show();
      }
    }

    function js_limpaLacador() {

      
    }

    js_mostraQuebrar($F("quebrar"));
    js_mostraAgrupar($F("tipoDebito"));
  </script>
</html>