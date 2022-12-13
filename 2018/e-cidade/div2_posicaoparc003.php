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

  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_proced_classe.php");
  $clproced = new cl_proced;

  $clrotulo = new rotulocampo;
  $clrotulo->label('DBtxt21');
  $clrotulo->label('DBtxt22');
  $clrotulo->label('DBtxt34');
  $clrotulo->label('k60_codigo');
  $clrotulo->label('k60_descr');
  $clrotulo->label('k51_procede');
  $clrotulo->label('k51_descr');
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);

  $db_botao = true;
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script>

      function js_emite() {

        var data1 = new Date(document.form1.DBtxt21_ano.value,
                             document.form1.DBtxt21_mes.value,
                             document.form1.DBtxt21_dia.value,0,0,0);
        var data2 = new Date(document.form1.DBtxt22_ano.value,
                             document.form1.DBtxt22_mes.value,
                             document.form1.DBtxt22_dia.value,0,0,0);

        if (data1.valueOf() > data2.valueOf()) {

          alert('Data inicial maior que data final. Verifique!');
          return false;
        }

        var dtVencumentoIni = new Date(document.form1.vencimentoini_ano.value,
                                       document.form1.vencimentoini_mes.value,
                                       document.form1.vencimentoini_dia.value,0,0,0);
        var dtVencimentoFim = new Date(document.form1.vencimentofim_ano.value,
                                       document.form1.vencimentofim_mes.value,
                                       document.form1.vencimentofim_dia.value,0,0,0);

        if (dtVencumentoIni.valueOf() > dtVencimentoFim.valueOf()) {

          alert('Data Vencimento inicial maior que data final. Verifique!');
          return false;
        }

        vir       = "";
        listatipo = "";

        for (x = 0; x < document.form1.tipodivida.length; x++) {

          listatipo += vir + document.form1.tipodivida.options[x].value;
          vir        = ",";
        }

        vir        = "";
        listaregra = "";

        for (x = 0; x < parent.iframe_g2.document.form1.regras.length; x++) {

          listaregra += vir + parent.iframe_g2.document.form1.regras.options[x].value;
          vir = ",";
        }

        qry  = "";
        qry += '&considera='     + document.form1.considera.value;
        qry += '&listatipo='     + listatipo + '&vertipo=' + document.form1.ver.value;
        qry += '&listaregra='    + listaregra + '&verregra=' + parent.iframe_g2.document.form1.ver.value;
        qry += '&grafico=-'      + document.form1.grafico.value;
        qry += '&ordem='         + document.form1.ordem.value;
        qry += '&quantini='      + document.form1.quantparcini.value;
        qry += '&quantfim='      + document.form1.quantparcfim.value;
        qry += '&numini='        + document.form1.numparcini.value;
        qry += '&numfim='        + document.form1.numparcfim.value;
        qry += '&data='          + document.form1.DBtxt21_ano.value + '-'
                                   + document.form1.DBtxt21_mes.value + '-'
                                   + document.form1.DBtxt21_dia.value;
        qry += '&data1='         + document.form1.DBtxt22_ano.value + '-'
                                   + document.form1.DBtxt22_mes.value + '-'
                                   + document.form1.DBtxt22_dia.value;

        if (    document.form1.vencimentoini_ano.value != ""
            && document.form1.vencimentoini_mes.value != ""
            && document.form1.vencimentoini_dia.value != "") {

          qry += '&vencimentoini=' + document.form1.vencimentoini_ano.value + '-'
                                     + document.form1.vencimentoini_mes.value + '-'
                                     + document.form1.vencimentoini_dia.value;
        }

        if (    document.form1.vencimentoini_ano.value != ""
            && document.form1.vencimentoini_mes.value != ""
                && document.form1.vencimentoini_dia.value != "") {

          qry += '&vencimentofim=' + document.form1.vencimentofim_ano.value + '-'
                                     + document.form1.vencimentofim_mes.value + '-'
                                     + document.form1.vencimentofim_dia.value;
        }

        jan = window.open('div2_posicaoparc002.php?' + qry, '', 'width=' + (screen.availWidth - 5)
                           + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
      }

      function js_valida (fim, ini, obj) {

        if (ini > fim){

          if (obj == "num") {

             alert("Número de parcelas inicial não pode ser maior que o número final.");
             document.form1.numparcini.value = "";
             document.form1.numparcfim.value = "";
           } else {

             alert("Quantidade de parcelas inicial não pode ser maior que quantidade final.");
             document.form1.quantparcini.value = "";
             document.form1.quantparcfim.value = "";
           }
        }
      }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC onLoad="a=1">
    <form class="container"  name="form1" method="post" action="" >
      <fieldset>
        <legend>Posição dos Parcelamentos</legend>
        <table class="form-container">
          <tr>
            <td>Parcelamentos Emitidos Entre :</td>
            <td  align="left">
              <?php db_inputdata('DBtxt21', '', '', '', true, 'text', 4); ?>
              &nbsp;<strong>Até: </strong>&nbsp;
              <?php
                $dia = date('d');
                $mes = date('m');
                $ano = date('Y');
                db_inputdata('DBtxt22', $dia, $mes, $ano, true, 'text', 4);
              ?>
            </td>
          </tr>
          <tr>
            <td>Vencimentos Entre :</td>
            <td align="left">
              <?php db_inputdata('vencimentoini', '', '', '', true, 'text', 4); ?>
              &nbsp;<strong>Até: </strong>&nbsp;
              <?php
                db_inputdata('vencimentofim', '', '', '', true, 'text', 4);
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?php echo $TDBtxt34; ?>">Quantidade Parcelas Atraso:</td>
            <td>
              <?php
                db_input("quantparcini", 4, $IDBtxt34, true, 'text', 4, 'onchange=document.form1.quantparcfim.value="";');
              ?>
              <b>a</b>
              <?php
                db_input("quantparcfim", 4, $IDBtxt34, true, 'text',
                         4, 'onchange=js_valida(this.value.getNumber(),document.form1.quantparcini.value.getNumber(),"quant");')
              ?>
            </td>
          <tr>
            <td>Número da Parcela em Atraso:</td>
            <td>
              <?php
                $y = array("C" => "Considerar",
                           "D" => "Desconsiderar");
                db_select('considera', $y, true, 2);

                db_input("numparcini", 4, $IDBtxt34, true, 'text', 4, 'onchange=document.form1.numparcfim.value="";');
              ?>
              <b>a</b>
              <?php
                db_input("numparcfim", 4, $IDBtxt34, true, 'text',
                         4, 'onchange=js_valida(this.value.getNumber(),document.form1.numparcini.value.getNumber(),"num");')
              ?>
            </td>
          </tr>
          <tr>
            <td>Ordem:</td>
            <td>
              <?php
                $x = array("NP"   => "Numpre/Numpar",
                           "DTPV" => "Data da primeira parcela vencida",
                           "NOME" => "Nome do contribuinte");
                db_select('ordem', $x, true, 2);
              ?>
            </td>
          </tr>
          <tr>
             <td>Emitir :</td>
             <td>
               <?php
                 $x = array("RG" => "Relatório e Gráfico",
                            "R"  => "Somente Relatório",
                            "G"  => "Somente Gráfico");
                 db_select('grafico', $x, true, 2);
               ?>
             </td>
           </tr>
          <tr>
            <td>Opções:</td>
            <td>
              <select name="ver">
                <option name="condicao1" value="com">Com os tipos selecionados</option>
                <option name="condicao1" value="sem">Sem os tipos selecionados</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <?php
                include("dbforms/db_classesgenericas.php");

                $aux = new cl_arquivo_auxiliar;

                $aux->cabecalho      = "Selecione um Tipo de Dívida ou Deixe em Branco para Todos";
                $aux->codigo         = "k00_tipo";
                $aux->descr          = "k00_descr";
                $aux->nomeobjeto     = 'tipodivida';
                $aux->funcao_js      = 'js_mostra';
                $aux->funcao_js_hide = 'js_mostra1';
                $aux->sql_exec       = "";
                $aux->func_arquivo   = "func_arretipo.php";
                $aux->parametros     = "+'&k03_tipo=6,13,16,17'";
                $aux->nomeiframe     = "iframe_arretipo";
                $aux->localjan       = "";
                $aux->onclick        = "";
                $aux->db_opcao       = 2;
                $aux->tipo           = 2;
                $aux->top            = 0;
                $aux->linhas         = 10;
                $aux->vwhidth        = 400;
                $aux->funcao_gera_formulario();
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="db_opcao" type="button" id="db_opcao" value="Imprimir" onClick="js_emite();">
    </form>
  </body>
</html>
<script>

  $("DBtxt21").addClassName("field-size2");
  $("DBtxt22").addClassName("field-size2");
  $("vencimentoini").addClassName("field-size2");
  $("vencimentofim").addClassName("field-size2");
  $("considera").setAttribute("rel", "ignore-css");
  $("considera").addClassName("field-size3");
  $("quantparcini").addClassName("field-size2");
  $("quantparcfim").addClassName("field-size2");
  $("numparcini").addClassName("field-size2");
  $("numparcfim").addClassName("field-size2");
  $("fieldset_tipodivida").addClassName("separator");
  $("k00_tipo").addClassName("field-size1");
  $("k00_descr").addClassName("field-size6");
  $("tipodivida").style.width = "100%";
</script>