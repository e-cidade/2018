<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_calendarioescola_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo              = new rotulocampo;
$clcalendarioescola    = new cl_calendarioescola;
$clrotulo->label("ed52_i_ano");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");
$escola   = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
if (!isset($ed52_i_ano)) {

  $ed52_i_ano = date("Y");
  for ($x = 1; $x <= 31; $x++) {

    if (date("w",mktime(0,0,0,5,$x,$ed52_i_ano)) == 3) {

      $data_censo_dia = strlen($x) == 1?"0".$x:$x;
      $data_censo_mes = "05";
      $data_censo_ano = $ed52_i_ano;

    }
  }

  $data_censo = $data_censo_dia."/".$data_censo_mes."/".$data_censo_ano;
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
   fieldset .fieldsetinterno table tr td:FIRST-CHILD {
	    width: 100px;
	    white-space: nowrap;
   }

   fieldset .fieldsetinterno td.coluna3 {

      width: 100px;
      white-space: nowrap;
      text-align: right;
   }
  </style>
 </head>
 <body bgcolor="#CCCCCC"  style="margin-top: 25px">
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <form name="form1" method="post" action="">
    <center>
    <div style='display: table'>
     <fieldset>
        <legend><b>Gerar Arquivo de Exporta��o - CENSO ESCOLAR</b></legend>
        <fieldset  class='fieldsetinterno' style='border: 0;border-top:2px groove white'>
          <legend><b>Data do Censo</b></legend>
          <table border="0">
           <tr>
            <td>
               <b>Data do Censo:</b>
             </td>
             <td>
              <?db_inputdata('data_censo',@$data_censo_dia,@$data_censo_mes,@$data_censo_ano,true,'text',1,
                             " onchange=\"js_ano();\"","","","parent.js_ano();")?>
             </td>
             <td class='coluna3'>
              <b>Ano do Censo:</b>
             </td>
             <td>
              <?db_input('ed52_i_ano',4,@$Ied52_i_ano,true,'text',3,"");?>
            </td>
           </tr>
           <?
            $verif = false;
            if (isset($ed52_i_ano) && $ed52_i_ano != "" && !isset($gerararquivo)) {

              $sOrder                  = "ed52_d_inicio asc,ed52_d_fim desc";
              $sWhere                  = " ed52_i_ano = $ed52_i_ano AND ed38_i_escola = $escola";
              $sSqlCalendarioEscola    = $clcalendarioescola->sql_query("","ed52_d_inicio,ed52_d_fim",$sOrder,$sWhere);
              $sResultCalendarioEscola = $clcalendarioescola->sql_record($sSqlCalendarioEscola);
              if ($clcalendarioescola->numrows > 0) {
                db_fieldsmemory($sResultCalendarioEscola,0);
              } else {

                $verif             = true;
                $db_opcao          = 3;
                $ed52_d_inicio     = "";
                $ed52_d_inicio_dia = "";
                $ed52_d_inicio_mes = "";
                $ed52_d_inicio_ano = "";
                $ed52_d_fim        = "";
                $ed52_d_fim_dia    = "";
                $ed52_d_fim_mes    = "";
                $ed52_d_fim_ano    = "";

              }

            }
           ?>
          </table>
          </fieldset>
          <fieldset class='fieldsetinterno' style='border: 0;border-top:2px groove white'>
             <legend><b>Calend�rio</b></legend>
             <table>
               <tr>
                 <td>
                   <?=@$Led52_d_inicio?>
                 </td>
                 <td>
                  <? db_inputdata('ed52_d_inicio',@$ed52_d_inicio_dia,@$ed52_d_inicio_mes,
                            @$ed52_d_inicio_ano,true,'text', 3,"");?>
                 </td>
                 <td class='coluna3'>
                   <?=@$Led52_d_fim?>
                 </td>
                 <td>
                   <? db_inputdata('ed52_d_fim',@$ed52_d_fim_dia,@$ed52_d_fim_mes,
                                  @$ed52_d_fim_ano,true,'text', 3,"");?>
                 </td>
               </tr>
             </table>
           </fieldset>
       </fieldset>
       </div>
      <input name="gerararquivo" type="button" id="btnGerarArquivo"  <?=$verif==true?"disabled":""?>
             value="Gerar Arquivo" >
      </center>
  </form>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),db_getsession("DB_instit"));
    if ($verif) {
      db_msgbox("Layout {$ed52_i_ano} n�o dispon�vel no momento.");
    }
    ?>
  <script>
   function js_ano() {

     datacenso = document.form1.data_censo.value;
     if (datacenso != "" && datacenso.length == 10) {

       datacenso                       = datacenso.split("/");
       document.form1.ed52_i_ano.value = datacenso[2];
       document.form1.submit();

     } else {

       document.form1.ed52_i_ano.value    = "";
       document.form1.ed52_d_inicio.value = "";
       document.form1.ed52_d_fim.value    = "";

     }
   }

   function js_valida() {

     if (document.form1.data_censo.value == "" || document.form1.ed52_i_ano.value == ""
         || document.form1.ed52_d_inicio.value == "" || document.form1.ed52_d_fim.value == "") {

       alert("Preencha todos os campos do formul�rio!");
       return false;

     }

     if (document.form1.ed52_i_ano.value != document.form1.ed52_d_inicio_ano.value
         || document.form1.ed52_i_ano.value != document.form1.ed52_d_fim_ano.value) {

       alert("Data Inicial e Final do Calend�rio deve estar dentro do Ano do Censo!");
       return false;

     }

     dataini  = document.form1.ed52_d_inicio_ano.value+document.form1.ed52_d_inicio_mes.value;
     dataini += document.form1.ed52_d_inicio_dia.value;
     datafim  = document.form1.ed52_d_fim_ano.value+document.form1.ed52_d_fim_mes.value;
     datafim += document.form1.ed52_d_fim_dia.value;
     if (parseInt(dataini) >= parseInt(datafim)) {

       alert("Data Final do Calend�rio deve ser maior que a Data Inicial!");
       return false;

     }

     document.form1.gerararquivo.style.visibility = "hidden";
     return true;

   }

   $('btnGerarArquivo').observe("click", function() {

      var oParametro     = new Object();
      oParametro.exec    = 'processarArquivoCenso';
      oParametro.iAno    = $F('ed52_i_ano');
      oParametro.dtCenso = $F('data_censo');

      if (oParametro.dtCenso == "") {

        alert('A data do censo deve ser informada.');
        return false;
      }
      $('btnGerarArquivo').disabled = true;
      js_divCarregando('Aguarde, processando dados do censo. Esse processo pode demorar.', 'msgBox');
      var oAjax = new Ajax.Request('edu4_censoescolar.RPC.php',
                                   {method:'post',
                                    parameters:'json='+Object.toJSON(oParametro),
                                    onComplete: js_retornoProcessarCenso
                                    }
                                   );
   });


   function js_retornoProcessarCenso(oResponse) {

     $('btnGerarArquivo').disabled = false;
     js_removeObj('msgBox');
     var oRetorno  = eval("("+oResponse.responseText+")");
     if (oRetorno.status == 1) {

      alert('Dados Gerados com sucesso!');
      var sListagem  = oRetorno.sNomeArquivo.urlDecode()+"#<br>Arquivo de Exporta��o do Censo Escolar<br><br>";
      sListagem      += "Clique neste link para salvar o arquivo de exporta��o do censo escolar gerado pelo sistema, ";
      sListagem      += "para posterior envio deste ao site do INEP|";
      js_montarlista(sListagem,'form1');

     } else {

       alert(oRetorno.message.urlDecode());
       var jan = window.open('edu4_exportarcenso002.php?arquivo_erro='+oRetorno.sNomeArquivoLog.urlDecode(),
                             'Erros Gera��o de Arquivo Censo escolar',
                             'width='+(screen.availWidth-5)+',height='
                             +(screen.availHeight-40)+',scrollbars=1,location=0');
       jan.moveTo(0,0);
     }
   }
  </script>
 </body>
</html>