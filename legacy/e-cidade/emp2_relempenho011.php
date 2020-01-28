<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_lote_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_empempenho_classe.php"));

//---  parser POST/GET
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

//---- instancia classes
$clempempenho = new cl_empempenho;
$clselorcdotacao = new cl_selorcdotacao;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if (!isset($testdt)){
  $testdt='sem';
}

if (!isset($desdobramento)){
  $desdobramento = "true";
}

$anousu = db_getsession("DB_anousu");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
    select {
      width: 250px;
    }
  </style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC">
<br/>
<center>
  <form name="form1" method="post" action="emp2_relempenho002.php">
    <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
    <fieldset style="width: 800px">
      <legend><b>Movimentação de Empenho</b></legend>
      <?php
      /*
       * Campos HIDDEN
       */
      db_input('testdt',10,"",true,"hidden",1);
      db_input('listacredor',10,"",true,"hidden",1);
      db_input('listahist',10,"",true,"hidden",1);
      db_input('listaevento',10,"",true,"hidden",1);
      db_input('listaitem',10,"",true,"hidden",1);
      db_input('ver',10,"",true,"hidden",1);
      db_input('verhist',10,"",true,"hidden",1);
      db_input('veritem',10,"",true,"hidden",1);
      db_input('vercom',10,"",true,"hidden",1);
      db_input('listacom',10,"",true,"hidden",1);
      db_input('datacredor',10,"",true,"hidden",1);
      db_input('datacredor1',10,"",true,"hidden",1);
      db_input('dataesp11',10,"",true,"hidden",1);
      db_input('dataesp22',10,"",true,"hidden",1);
      db_input('hist',10,"",true,"hidden",1);
      db_input('mostraritem',10,"",true,"hidden",1);
      db_input('mostrarobs',10,"",true,"hidden",1);
      db_input('mostralan',10,"",true,"hidden",1);
      db_input('agrupar',10,"",true,"hidden",1);
      db_input('listasub',10,"",true,"hidden",1);
      db_input("desdobramento",10,0,true,"hidden",3);
      db_input("listaconcarpeculiar",10,0,true,"hidden",3);
      db_input("verconcarpeculiar",  10,0,true,"hidden",3);
      db_input("orgaos",  10,0,true,"hidden",3);
      db_input("vernivel",  10,0,true,"hidden",3);
      ?>
      <table style="width: 100%" border='0'>
        <tr>
          <td colspan="4" align="center">
            <fieldset style="width: 98%; border-left: none; border-bottom: none; border-right: none;">
              <legend><b>Instituições</b></legend>
              <?php
              db_selinstit('', 500, 130);
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td width="140">
            <b>Data de Emissão:</b>
          </td>
          <td colspan="3">
            <?php
            $resultmin = db_query("select e60_emiss from empempenho order by e60_emiss limit 1");
            db_fieldsmemory($resultmin,0);
            $dia=substr($e60_emiss,8,2);
            $dia="01";
            $mes=substr($e60_emiss,5,2);
            $mes="01";
            $ano=substr($e60_emiss,0,4);
            $ano= db_getsession("DB_anousu");
            $dia2=date("d",db_getsession("DB_datausu"));
            $mes2=date("m",db_getsession("DB_datausu"));
            $ano2= db_getsession("DB_anousu");
            db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
            echo " a ";
            db_inputdata('data11',@$dia2,@$mes2,@$ano2,true,'text',1,"");
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Processar:</b></td>
          <td colspan="3">
            <?php
            $aProcessar = array("a"=>"Posição atual","e"=>"Período de Lançamentos");
            db_select('processar', $aProcessar, true, 1, "onchange='js_testadata(this.value);'");
            ?>
            <span id="dataespec">
            &nbsp;&nbsp;
              <?php
              $diaesp1 = "01";
              $mesesp1 = "01";
              $anoesp1 = db_getsession("DB_anousu");
              db_inputdata('dataesp1',@$diaesp1,@$mesesp1,@$anoesp1,true,'text',1,"");
              echo " a ";
              $diaesp2 = date('d',db_getsession("DB_datausu"));
              $mesesp2 = date('m',db_getsession("DB_datausu"));
              $anoesp2 = db_getsession("DB_anousu");
              db_inputdata('dataesp2',@$diaesp2,@$mesesp2,@$anoesp2,true,'text',1,"");
              ?>
          </span>
          </td>
        </tr>
        <tr>
          <td><b>Filtro de Listagem:</b></td>
          <td colspan="3">
            <?php
            $aFiltroListagem = array("todos"       => "Todos",
                                     "somemp"      => "Somente Empenhado",
                                     "saldo"       => "Com Saldo a Pagar Geral",
                                     "saldoliq"    => "Com Saldo a Pagar Liquidados",
                                     "saldonaoliq" => "Com Saldo a Pagar não Liquidados",
                                     "anul"        => "Com Anulação Lançada",
                                     "anultot"     => "Totalmente Anulados",
                                     "anulparc"    => "Parcialmente Anulados",
                                     "anulsem"     => "Sem Anulação",
                                     "liqtot"      => "Totalmente Liquidados",
                                     "liqparc"     => "Parcialmente Liquidados",
                                     "liqparc"     => "Sem Liquidação",
                                     "pagtot"      => "Totalmente Pagos",
                                     "pagparc"     => "Parcialmente Pagos");
            db_select("tipoemp", $aFiltroListagem, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Agrupar Por:</b></td>
          <td>
            <?php
            $aAgruparPor = array("oo"    => "Não Agrupar",
                                 "a"     => "Fornecedor",
                                 "orgao" => "Orgão",
                                 "r"     => "Recurso",
                                 "d"     => "Desdobramento");
            db_select("agrupar", $aAgruparPor, true, 1);
            ?>
          </td>
          <td><b>Mostrar:</b></td>
          <td>
            <?php
            $aMostrar = array("r" => "Recurso",
                              "t" => "Tipo de Compra");
            db_select("mostrar", $aMostrar, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Trazer Valor Em Ordem:</b></td>
          <td>
            <?php
            $aValorEmOrdem = array("0" => "Selecione", "E" => "Empenhado", "L" => "Liquidado", "P" => "Pago");
            db_select("chk_ordem",$aValorEmOrdem,true,2);
            ?>
          </td>
          <td><b>Mostrar Empenho:</b></td>
          <td>
            <?php
            $aMostrarEmpenho = array("n" => "Sim", "s" => "Não");
            db_select("sememp",$aMostrarEmpenho,true,2);
            ?>
          </td>
        </tr>

        <tr>
          <td><b>Valor Empenho:</b></td>
          <td colspan="3">
            <?php
            db_input("nValorEmpenhoInicial", 10, false, true, 'text', 1, "onkeypress='return js_mask(event,\"0-9|,|-\");'");
            echo " <b>até</b> ";
            db_input("nValorEmpenhoFinal", 10, false,   true, 'text', 1, "onkeypress='return js_mask(event,\"0-9|,|-\");'");
            ?>
          </td>
        </tr>

        <tr>
          <td><b>Tipo Empenho:</b></td>
          <td>
            <?php

            $oDaoEmpTipo    = new cl_emptipo();
            $sSqlBuscaTipos = $oDaoEmpTipo->sql_query_file();
            $rsBuscaTipos   = $oDaoEmpTipo->sql_record($sSqlBuscaTipos);
            $aTiposEmpenhos = array();
            for ($iRowTipo = 0; $iRowTipo < $oDaoEmpTipo->numrows; $iRowTipo++) {

              $oStdDadosTipo = db_utils::fieldsMemory($rsBuscaTipos, $iRowTipo);
              $aTiposEmpenhos[$oStdDadosTipo->e41_codtipo] = $oStdDadosTipo->e41_descr;
            }
            $aTiposEmpenhos[0] = "Todos";
            db_select("emptipo",$aTiposEmpenhos,true,1);
            ?>
          </td>
        </tr>

        <tr>
          <td><b>Opções:</b></td>
          <td colspan="3">
            <input type="checkbox" name="hist"   	    value="h">Mostrar totalizações
            <input type="checkbox" name="mostraritem" value="m">Mostrar itens
            <input type="checkbox" name="mostrarobs" 	value="m">Mostrar resumo
            <input type="checkbox" name="mostralan" 	value="m">Mostrar lançamentos
          </td>
        </tr>
      </table>
    </fieldset>
    <br>
    <input type="button" value="Relatório" onClick="js_emite(<?=$anousu?>)">
</center>
</form>
<script>

  tr = document.getElementById("dataespec");
  tr.style.display = "none";
  document.form1.dataesp1_dia.style.visibility="hidden";
  document.form1.dataesp1_mes.style.visibility="hidden";
  document.form1.dataesp1_ano.style.visibility="hidden";
  document.form1.dataesp1.style.visibility="hidden";
  document.form1.dataesp2_dia.style.visibility="hidden";
  document.form1.dataesp2_mes.style.visibility="hidden";
  document.form1.dataesp2_ano.style.visibility="hidden";
  document.form1.dataesp2.style.visibility="hidden";


  function js_testadata(valor){
    tr = document.getElementById("dataespec");
    if (valor=='e'){
      tr.style.display = "";
      document.form1.dataesp1_dia.style.visibility="visible";
      document.form1.dataesp1_mes.style.visibility="visible";
      document.form1.dataesp1_ano.style.visibility="visible";
      document.form1.dataesp1.style.visibility="visible";
      document.form1.dataesp2_dia.style.visibility="visible";
      document.form1.dataesp2_mes.style.visibility="visible";
      document.form1.dataesp2_ano.style.visibility="visible";
      document.form1.dataesp2.style.visibility="visible";
    }else if (valor=='a'){
      tr.style.display = "none";
      document.form1.dataesp1_dia.style.visibility="hidden";
      document.form1.dataesp1_mes.style.visibility="hidden";
      document.form1.dataesp1_ano.style.visibility="hidden";
      document.form1.dataesp1.style.visibility="hidden";
      document.form1.dataesp2_dia.style.visibility="hidden";
      document.form1.dataesp2_mes.style.visibility="hidden";
      document.form1.dataesp2_ano.style.visibility="hidden";
      document.form1.dataesp2.style.visibility="hidden";
    }
  }

  variavel = 1;

  function js_emite(anousu){

    vir="";
    listacredor="";
    for(x=0;x<parent.iframe_g2.document.form1.credor.length;x++){
      listacredor+=vir+parent.iframe_g2.document.form1.credor.options[x].value;
      vir=",";
    }

    vir="";
    listahist="";
    for(x=0;x<parent.iframe_g3.document.form1.historico.length;x++){
      listahist+=vir+parent.iframe_g3.document.form1.historico.options[x].value;
      vir=",";
    }

    vir="";
    listaevento="";
    for(x=0;x<parent.iframe_g3.document.form1.evento.length;x++){
      listaevento+=vir+parent.iframe_g3.document.form1.evento.options[x].value;
      vir=",";
    }

    vir="";
    listacom="";
    for(x=0;x< parent.iframe_g4.document.form1.tipocom.length;x++){
      listacom+=vir+ parent.iframe_g4.document.form1.tipocom.options[x].value;
      vir=",";
    }

    vir="";
    listaitem="";
    listasub ="";
    for(x=0;x<parent.iframe_g5.document.form1.item.length;x++){
      listaitem+=vir+parent.iframe_g5.document.form1.item.options[x].value;
      vir=",";
    }

    if (listaitem==""){
      for(x=0;x<parent.iframe_g5.document.form1.sub.length;x++){
        listasub+=vir+parent.iframe_g5.document.form1.sub.options[x].value;
        vir=",";
      }
    }

    if (anousu > 2007){
      vir="";
      listaconcarpeculiar="";
      for(x=0;x< parent.iframe_g6.document.form1.concarpeculiar.length;x++){
        listaconcarpeculiar+=vir+ parent.iframe_g6.document.form1.concarpeculiar.options[x].value;
        vir=",";
      }

      document.form1.listaconcarpeculiar.value = listaconcarpeculiar;
      document.form1.verconcarpeculiar.value   = parent.iframe_g6.document.form1.verconcarpeculiar.value;
    }

    document.form1.listacom.value = listacom;

    document.form1.datacredor.value=document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
    document.form1.datacredor1.value=document.form1.data11_ano.value+'-'+document.form1.data11_mes.value+'-'+document.form1.data11_dia.value;

    document.form1.dataesp11.value=document.form1.dataesp1_ano.value+'-'+document.form1.dataesp1_mes.value+'-'+document.form1.dataesp1_dia.value;
    document.form1.dataesp22.value=document.form1.dataesp2_ano.value+'-'+document.form1.dataesp2_mes.value+'-'+document.form1.dataesp2_dia.value;

    document.form1.hist=document.form1.hist.checked;

    document.form1.listacredor.value = listacredor;
    document.form1.listahist.value   = listahist;
    document.form1.listaevento.value = listaevento;

    document.form1.listaitem.value=listaitem;
    document.form1.listasub.value=listasub;

    document.form1.ver.value         = parent.iframe_g2.document.form1.ver.value;
    document.form1.verhist.value     = parent.iframe_g3.document.form1.ver.value;
    document.form1.vercom.value      = parent.iframe_g4.document.form1.ver.value;
    document.form1.veritem.value     = parent.iframe_g5.document.form1.veritem.value;

    document.form1.mostraritem=document.form1.mostraritem.checked;
    document.form1.mostrarobs=document.form1.mostrarobs.checked;
    document.form1.mostralan=document.form1.mostralan.checked;
    document.form1.agrupar=document.form1.agrupar.value;

    // pega dados da func_selorcdotacao_aba.php
    document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();


    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    setTimeout("document.form1.submit()",1000);
    return true;
  }


  function js_mandadados(){

    tipoemp=document.form1.tipoemp.value;
    <?
      if ($testdt=='com'){
    ?>
    jan = window.open('emp2_relempenho002.php?tipoemp='+tipoemp
      +'&listacredor='+listacredor
      +'&emptipo='+emptipo
      +'&listahist='+listahist
      +'&listaevento='+listaevento
      +'&listacom='+listacom
      +'&datacredor='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value
      +'&datacredor1='+document.form1.data11_ano.value+'-'+document.form1.data11_mes.value+'-'+document.form1.data11_dia.value
      +'&dataesp1='+document.form1.dataesp1_ano.value+'-'+document.form1.dataesp1_mes.value+'-'+document.form1.dataesp1_dia.value
      +'&dataesp2='+document.form1.dataesp2_ano.value+'-'+document.form1.dataesp2_mes.value+'-'+document.form1.dataesp2_dia.value
      +'&processar='+document.form1.processar.value
      +'&vercredor='+parent.iframe_g2.document.form1.ver.value
      +'&verhist='+parent.iframe_g3.document.form1.ver.value
      +'&vercom='+parent.iframe_g4.document.form1.ver.value
      +'&mostrar='+document.form1.mostrar.value
      +'&tipo='+document.form1.tipo.value
      +'&hist='+document.form1.hist.checked
      +'&mostraritem='+document.form1.mostraritem.checked,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    <?
      }else{
    ?>
    jan = window.open('emp2_relempenho002.php?tipoemp='+tipoemp
      +'&listacredor='+listacredor
      +'&emptipo='+emptipo
      +'&listahist='+listahist
      +'&listaevento='+listaevento
      +'&listacom='+listacom
      +'&datacredor='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value
      +'&datacredor1='+document.form1.data11_ano.value+'-'+document.form1.data11_mes.value+'-'+document.form1.data11_dia.value
      +'&processar='+document.form1.processar.value
      +'&vercredor='+parent.iframe_g2.document.form1.ver.value
      +'&verhist='+parent.iframe_g3.document.form1.ver.value
      +'&vercom='+parent.iframe_g4.document.form1.ver.value
      +'&mostrar='+document.form1.mostrar.value
      +'&tipo='+document.form1.tipo.value
      +'&hist='+document.form1.hist.checked
      +'&mostraritem='+document.form1.mostraritem.checked,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    <?
      }
    ?>
    jan.moveTo(0,0);

  }
</script>

</center>
</body>
</html>