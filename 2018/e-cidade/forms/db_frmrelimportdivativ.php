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

//MODULO: dividaativa

?>
<form name="form1" method="post" action="" class="container">
  <center>
    <fieldset>
    <legend><b>Relatório de Inscrição em Dívida Ativa</b></legend>
	  <table border="0" class="form-container">
	    <tr>
	      <td width="25%" nowrap="nowrap">
	      <?
	          db_ancora("<b>Nome Contribuinte :</b>","js_mostranomes(true);",1);
	      ?>
	      </td>
	      <td colspan="3">
	      <?
	          db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_mostranomes(false);'");
	          db_input('z01_nome',40,0,true,'text',3,"","z01_nomecgm");
	      ?>
	      </td>
	    </tr>
      <tr>
        <td width="25%" nowrap="nowrap">
        <?
            db_ancora("<b>Matrícula :</b>","js_mostramatriculas(true);",1);
        ?>
        </td>
        <td colspan="3">
        <?
            db_input('j01_matric',5,@$Ij01_matric,true,'text',1,"onchange='js_mostramatriculas(false);'");
            db_input('z01_nome',40,0,true,'text',3,"","z01_nomematri");
        ?>
        </td>
      </tr>
      <tr>
        <td width="25%" nowrap="nowrap">
        <?
            db_ancora("<b>Inscrição :</b>","js_mostrainscricoes(true);",1);
        ?>
        </td>
        <td colspan="3">
        <?
            db_input('q02_inscr',5,$Iz01_numcgm,true,'text',1,"onchange='js_mostrainscricoes(false);'");
            db_input('z01_nome',40,0,true,'text',3,"","z01_nomeinscr");
        ?>
        </td>
      </tr>
	    <tr>
	      <td width="25%">
	        <b>Período:</b>
	      </td>
        <td colspan="3">
         <?
             db_inputdata('data1','','','',true,'text',1,"");
               echo "<b> a </b> ";
             db_inputdata('data2','','','',true,'text',1,"");

         ?>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php
            $matriz = array("0"=>"Lançamento",
                            "1"=>"Inscrição");
            db_select("tipoper", $matriz,true,"");
          ?>
        </td>
	    </tr>
      <tr>
        <td width="25%">
          <b>Tipo de Relatório:</b>
        </td>
        <td width="26%">
         <?
             $matriz = array("0"=>"Sintético",
                             "1"=>"Analítico");
             db_select("tiporel", $matriz,true,""," onchange='js_validarGenerico(false);'");
         ?>
        </td>


        </tr>
        <tr>
        <td width="12%" id="ordenar1">
          <b>Ordenar:</b>
        </td>
        <td id="ordenar2">
         <?
             $matriz = array("0"=>"Código de Importação",
                             "1"=>"Usuário",
                             "2"=>"Tipo",
                             "3"=>"Data Inicial");
             db_select("ordenar", $matriz,true,"");
         ?>
        </td>

        <td  width="12%" id="agrupar1" style="display: none;">
          <b>Agrupar:</b>
        </td>
        <th id="agrupar2" style="display: none;">
         <?
             $matriz = array("0"=>"Nome",
                             "1"=>"Origem",
                             "2"=>"Origem/Exercício",
                             "3"=>"Código de Importação",
                             "4"=>"Somente no Final");
             db_select("agrupar", $matriz,true,"");
         ?>
        </th>


      </tr>
	    <tr>
	      <td width="25%">
	        <b> Tipo de Inscrição: </b>
	      </td>
	      <td width="26%">
	       <?
	           $matriz = array("0"=>"Todos",
	                           "1"=>"Parcial",
	                           "2"=>"Geral",
	                           "3"=>"Inclusão Manual");
	           db_select("tipoimp", $matriz,true,"");
	       ?>
	      </td>
	    </tr>
	  </table>
    </fieldset>
    <table>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">
          <input  name="imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_validarGenerico(true);">
        </td>
      </tr>
    </table>
  </center>
</form>
<script>

function js_mostranomes(mostra){
  var obj    = document.form1;
  var numcgm = obj.z01_numcgm.value;
  var sUrl1  = 'func_nome.php?funcao_js=parent.js_preenchenomes|z01_numcgm|z01_nome';
  var sUrl2  = 'func_nome.php?pesquisa_chave='+numcgm+'&funcao_js=parent.js_preenchenomes1';

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nomes',sUrl1,'Pesquisa Nome Contribuinte',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_nomes',sUrl2,'Pesquisa Nome Contribuinte',false);

    if(numcgm == ''){
      obj.z01_numcgm.value  = '';
      obj.z01_nomecgm.value = '';
    }
  }
}
function js_preenchenomes(chave1,chave2){
  var obj               = document.form1;
  obj.z01_numcgm.value  = chave1;
  obj.z01_nomecgm.value = chave2;
  db_iframe_nomes.hide();
}
function js_preenchenomes1(erro,chave){
  var obj    = document.form1;
  var numcgm = obj.z01_numcgm.value;

  if(erro == true){
    obj.z01_numcgm.value  = '';
    obj.z01_nomecgm.value = chave;
    obj.z01_numcgm.focus();
  } else {
    if(numcgm != ''){
      obj.z01_nomecgm.value = chave;
    } else {
      obj.z01_numcgm.value  = '';
      obj.z01_nomecgm.value = '';
    }
  }
}

function js_mostramatriculas(mostra){
  var obj   = document.form1;
  var matri = obj.j01_matric.value;
  var sUrl1 = 'func_iptubase.php?funcao_js=parent.js_preenchematriculas|j01_matric|z01_nome';
  var sUrl2 = 'func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_preenchematriculas1';

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matriculas',sUrl1,'Pesquisa Matricula',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matriculas',sUrl2,'Pesquisa Matricula',false);

    if(matri != ''){
      obj.z01_nomematri.value = chave;
    } else {
      obj.j01_matric.value    = '';
      obj.z01_nomematri.value = '';
    }
  }
}
function js_preenchematriculas(chave1,chave2){
  var obj                 = document.form1;
  obj.j01_matric.value    = chave1;
  obj.z01_nomematri.value = chave2;
  db_iframe_matriculas.hide();
}
function js_preenchematriculas1(chave,erro){
  var obj   = document.form1;
  var matri = obj.j01_matric.value;

  if(erro==true){
    obj.j01_matric.value    = '';
    obj.z01_nomematri.value = chave;
    obj.j01_matric.focus();
  } else {
    if(matri != ''){
      obj.z01_nomematri.value = chave;
    } else {
      obj.j01_matric.value    = '';
      obj.z01_nomematri.value = '';
    }
  }
}

function js_mostrainscricoes(mostra){
  var obj   = document.form1;
  var inscr = obj.q02_inscr.value;
  var sUrl1 = 'func_issbase.php?funcao_js=parent.js_preencheinscricoes|q02_inscr|z01_nome';
  var sUrl2 = 'func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_preencheinscricoes1';

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscricoes',sUrl1,'Pesquisa Inscrição',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscricoes',sUrl2,'Pesquisa Inscrição',false);
  }
}
function js_preencheinscricoes(chave1,chave2){
  var obj                 = document.form1;
  obj.q02_inscr.value     = chave1;
  obj.z01_nomeinscr.value = chave2;
  db_iframe_inscricoes.hide();
}
function js_preencheinscricoes1(chave,erro){
  var obj   = document.form1;
  var inscr = obj.q02_inscr.value;

  if(erro==true){
    obj.q02_inscr.value = '';
    obj.z01_nomeinscr.value = chave;
    obj.q02_inscr.focus();
  } else {
    if(inscr != ''){
      obj.z01_nomeinscr.value = chave;
    } else {
      obj.q02_inscr.value     = '';
      obj.z01_nomeinscr.value = '';
    }
  }
}

function js_validarGenerico(param){
  var obj       = document.form1;
  var lParam    = param;
  var numcgm    = obj.z01_numcgm.value;
  var nummatric = obj.j01_matric.value;
  var numinscr  = obj.q02_inscr.value;
  var data_ini  = obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
  var data_fin  = obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
  var tiporel   = obj.tiporel.value;
  var tipoimp   = obj.tipoimp.value;
  var tipoper   = obj.tipoper.value;
  var ordenar   = obj.ordenar.value;
  var agrupar   = obj.agrupar.value;

  if(lParam == true){
    if(data_fin < data_ini){
       alert('Datas invalidas!');
       return false;
    } else {

      var oParametros = {
        'dataini'    : data_ini,
        'datafim'    : data_fin,
        'z01_numcgm' : numcgm,
        'j01_matric' : nummatric,
        'q02_inscr'  : numinscr,
        'tipoper'    : tipoper,
        'tiporel'    : tiporel,
        'tipoimp'    : tipoimp,
        'ordenar'    : ordenar,
        'agrupar'    : agrupar
      }

      new AjaxRequest('div2_reldivimporta002.php', oParametros, function(oRetorno, lErro){

        if(lErro){

          alert(oRetorno.sMessage.urlDecode());
          return false;
        }

        var oDownloadWindow = new DBDownload();

        oDownloadWindow.addFile(oRetorno.sPdfPathRelatorio.urlDecode(), "Relatório de Inscrição em Dívida Ativa");

        if(oRetorno.sPdfPathResumo != undefined){
          
          oDownloadWindow.addFile(oRetorno.sPdfPathResumo.urlDecode(), "Resumo por Curto e Longo Prazo");
        }

        oDownloadWindow.show();

      }).setMessage('Carregando...').execute();
    }
  }else{
	  if(tiporel == 0){
      document.getElementById('ordenar1').style.display = '';
      document.getElementById('ordenar2').style.display = '';
      document.getElementById('agrupar1').style.display = 'none';
      document.getElementById('agrupar2').style.display = 'none';
	  }else{
      document.getElementById('ordenar1').style.display = 'none';
      document.getElementById('ordenar2').style.display = 'none';
	    document.getElementById('agrupar1').style.display = '';
	    document.getElementById('agrupar2').style.display = '';
	  }
  }
}
</script>