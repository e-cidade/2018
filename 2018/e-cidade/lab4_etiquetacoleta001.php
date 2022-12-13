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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_lab_setor_classe.php");
require_once("classes/db_lab_requisicao_classe.php");

db_postmemory($HTTP_POST_VARS);

$oRotulo = new rotulocampo;
$oRotulo->label("la24_i_setor");
$oRotulo->label("la02_i_codigo");
$oDaoLabRequisicao = db_utils::getdao('lab_requisicao');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js");
    db_app::load("estilos.css");
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <center>
      <form name="form1" method="post" action="">
        <table style="width: 760px;">
          <tr>
            <td> 
              <fieldset align="center" style="width:95%; margin-top: 30px;">
                <legend><b>Relatório de Etiquetas:</b></legend>
                <table align="center">
                  <tr>
                    <td colspan="3">
                      <table align="center" style="margin-bottom: 10px;">
                        <tr>
                          <td>
                            <b>Escolha o modelo pra impressão:</b>
                          </td>
                          <td>
                            <?
                            if (isset($modeloselect)) {
                              $on_change = "onChange='document.form1.setor.length=0;'";
                            } else {
                              $on_change = "";
                            }
                            ?>
                            <select name="modelos" <?=$on_change?> style="width:450px;">
                              <option value="">
                              </option>
                              <option value="M1" <?=@$modeloselect=="M1"?"selected":""?>>
                                Modelo 1 ( 3 x 10 - Código de Barras / Código Exame / Paciente )
                              </option>
                              <option value="M2" <?=@$modeloselect=="M2"?"selected":""?>>
                                Modelo 2 ( 4 x 11 - Código de Barras / Código Exame / Paciente )
                              </option>       
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td nowrap title="Laborat&oacute;rio">
                            <?
                            db_ancora('<b>Laborat&oacute;rio:</b>', "js_pesquisala02_i_laboratorio(true);", "");
                            ?>
                          </td>
                          <td> 
                            <?
                            db_input('la02_i_codigo', 10, @$Ila02_i_codigo, true, 'text', "", 
                                     " onchange='js_pesquisala02_i_laboratorio(false);'"
                                    );
                            db_input('la02_c_descr', 50, @$Ila02_c_descr, true, 'text', 3, '');
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td nowrap title="<?=@$Tla24_i_setor?>">
                            <?
                            db_ancora(@$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "");
                            ?>
                          </td>
                          <td> 
                            <?
                            db_input('la24_i_setor', 10, @$Ila24_i_setor, true, 'text', "", 
                                     " onchange='js_pesquisala24_i_setor(false);'"
                                    );
                            db_input('la24_i_codigo', 10, '', true, 'hidden', '', '');
                            db_input('la23_c_descr', 50, @$Ila23_c_descr, true, 'text', 3, '');
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" >
                            <b> Período:</b>
                          </td>
                          <td>
                            <?
                            db_inputdata('la22_d_ini', @$la22_d_ini_dia, @$la22_d_ini_mes, @$la22_d_ini_ano, true, 
                                         'text', 1, ""
                                        );
                            ?>
                            A
                            <?     
                            db_inputdata('la22_d_fim', @$la22_d_fim_dia, @$la22_d_fim_mes, @$la22_d_fim_ano, true,
                                         'text', 1, ""
                                        );
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>      
                            <input type="button" name="buscar" value="Buscar" style="margin-top: 8px;" onclick="js_escolhemodelo()">
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <?
                      if (isset($modeloselect)) {
                        
                        @$ini  = substr(@$la22_d_ini, 6, 4)."-".substr(@$la22_d_ini, 3, 2)."-";
                        @$ini .= substr(@$la22_d_ini, 0, 2);
                        @$fim  = substr(@$la22_d_fim, 6, 4)."-".substr(@$la22_d_fim, 3, 2)."-";
                        @$fim .= substr(@$la22_d_fim, 0, 2);         
                        $sWhere = "";
                        $sSep   = "";
                        if ($la02_i_codigo != "") {
         
                          $sWhere = " la02_i_codigo=$la02_i_codigo ";
                          $sSep   = " and ";
         
                        }
                        if ($la24_i_setor != "") {
                          $sWhere .= " ".$sWhere.$sSep."  la24_i_setor=$la24_i_setor";
                        }
                        $sWhere   .= " la22_d_data between '$ini' and '$fim' and  la22_i_autoriza = 1";
                        $sSql      = $oDaoLabRequisicao->sql_query_requiitem("", "*", "", $sWhere);
                        
                        $rsResult  = $oDaoLabRequisicao->sql_record($sSql);
                        ?>
                        <b>Exames:</b><br>
                        <select name="exames" id="exames" size="10" onclick="js_desabinc()" 
                                style="font-size: 9px;width:320px;height:180px" multiple>
                          <?
                          if ($oDaoLabRequisicao->numrows > 0) {
       
                            db_fieldsmemory($rsResult, 0);
                            $la08_i_codigo = $la08_i_codigo;
                            $la08_c_descr  = substr($la08_c_descr, 0, 40) ;
                            $z01_v_nome    = $z01_v_nome ;
                            for ($iI = 0; $iI < $oDaoLabRequisicao->numrows; $iI++) {

                              db_fieldsmemory($rsResult, $iI);
                              if (($la08_i_codigo == $la08_i_codigo) && ($la08_c_descr == $la08_c_descr)) {
                                
                                echo "<option value='$la21_i_codigo'>";
                                echo $la21_i_codigo."-".$z01_v_nome."-".$la08_c_descr;
                                echo "</option>";
                                     
                              }
                            
                            }
                        
                          } else {

                            db_msgbox("Nenhum Registro encontrado!"); 
                            db_redireciona("lab4_etiquetacoleta001.php");

                          }
                          ?>
                        </select>
                      </td>
                      <td align="center">
                        <br>
                        <table border="0">
                          <tr>
                            <td>
                              <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_exames();" 
                                     style="border:1px outset; border-top-color:#f3f3f3; border-left-color:#f3f3f3;
                                            background:#cccccc; font-size:15px; font-weight:bold ;width:30px; 
                                            height:20px;" 
                                     disabled>
                            </td>
                          </tr>
                          <tr>
                            <td height="1"></td>
                          </tr>
                          <tr>
                            <td>
                              <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" 
                                     onclick="js_incluirtodos();" 
                                     style="border:1px outset; border-top-color:#f3f3f3; border-left-color:#f3f3f3; 
                                            background:#cccccc; font-size:15px; font-weight:bold; width:30px; 
                                            height:20px;">
                              </td>
                          </tr>
                          <tr>
                            <td height="8"></td>
                          </tr>
                          <tr>
                            <td>
                              <hr>
                            </td>
                          </tr>
                          <tr>
                            <td height="8"></td>
                          </tr>
                          <tr>
                            <td>
                              <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
                                     style="border:1px outset; border-top-color:#f3f3f3; border-left-color:#f3f3f3;
                                           background:#cccccc; font-size:15px; font-weight:bold; width:30px;
                                           height:20px;" 
                                     disabled>
                            </td>
                          </tr>
                          <tr>
                            <td height="1"></td>
                          </tr>
                          <tr>
                            <td>
                              <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" 
                                    onclick="js_excluirtodos();" 
                                    style="border:1px outset; border-top-color:#f3f3f3; border-left-color:#f3f3f3; 
                                           background:#cccccc; font-size:15px; font-weight:bold; width:30px;
                                           height:20px;" 
                                    disabled>
                            </td>
                          </tr>
                       </table>
                     </td>
                     <td>
                       <b>Etiquetas para impressão:</b><br>
                       <select name="etiquetas" id="etiquetas" size="10" onclick="js_desabexc()" 
                               style="font-size:9px; width:320px; height:180px" multiple>
                       </select>
                     </td>
                   </tr>
                   <tr>
                     <td>
                       <input name="processar" id="processar" type="button" value="Processar" 
                              onclick="js_emite();" disabled>
                       <input name="limpar" id="limpar" type="button" value="Limpar" onclick="js_limpar();">
                     </td>
                   </tr>
                 </table>
               <? 
               }
               ?>
             </fieldset>
           </td>
         </tr>
       </table>
      </form>
    </center>
    <?
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>
<script>
function js_exames() {

  var iTam = document.form1.exames.length;
  var oF   = document.form1;
  for (iX = 0; iX < iTam; iX++) {

    if (oF.exames.options[iX].selected == true) {

      oF.elements['etiquetas'].options[oF.elements['etiquetas'].options.length] = new Option(oF.exames.options[iX].text,
                                                                                             oF.exames.options[iX].value
                                                                                            );
      oF.exames.options[iX] = null;
      iTam--;
      iX--;

    }
    
 }
 if (document.form1.exames.length > 0) {
   document.form1.exames.options[0].selected = true;
 } else {
	 
   document.form1.incluirum.disabled    = true;
   document.form1.incluirtodos.disabled = true;

 }
 document.form1.processar.disabled    = false;
 document.form1.excluirtodos.disabled = false;
 document.form1.exames.focus();

}

function js_incluirtodos() {

  var iTam = document.form1.exames.length;
  var oF   = document.form1;
  for (iI = 0;iI < iTam; iI++) { 

    oF.elements['etiquetas'].options[oF.elements['etiquetas'].options.length] = new Option(oF.exames.options[0].text,
                                                                                         oF.exames.options[0].value
                                                                                        );
    oF.exames.options[0] = null;

 }
 document.form1.incluirum.disabled    = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 if (document.form1.etiquetas.length > 0) {
   document.form1.processar.disabled = false;
 }
 document.form1.etiquetas.focus();

}

function js_excluir() {

  var oF = document.getElementById("etiquetas"); 
  var iTam = oF.length;
  for (iX = 0; iX < iTam; iX++) { 

    if (oF.options[iX].selected == true) {

      document.form1.exames.options[document.form1.exames.length] = new Option(oF.options[iX].text, 
                                                                             oF.options[iX].value
                                                                            );
      oF.options[iX] = null;
      iTam--;
      iX--;

    }

  }
  if (document.form1.etiquetas.length > 0) {
    document.form1.etiquetas.options[0].selected = true;
  }
  if (oF.length == 0) {

    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;

 }
 document.form1.incluirtodos.disabled = false;
 document.form1.etiquetas.focus();

}

function js_excluirtodos() {

  var iTam = document.form1.etiquetas.length;
  var oF   = document.getElementById("etiquetas");
  for (iI = 0;iI < iTam; iI++) { 

    document.form1.exames.options[document.form1.exames.length] = new Option(oF.options[0].text,
                                                                             oF.options[0].value
                                                                            );
    oF.options[0] = null;

 }
 if (oF.length == 0) {

   document.form1.processar.disabled    = true;
   document.form1.excluirum.disabled    = true;
   document.form1.excluirtodos.disabled = true;
   document.form1.incluirtodos.disabled = false;

 }
 document.form1.exames.focus();

}

function js_desabinc() {

  for (iI = 0;iI < document.form1.exames.length; iI++) {
    
    if (document.form1.exames.length > 0 && document.form1.exames.options[iI].selected) {
        
      if (document.form1.etiquetas.length > 0) {
        document.form1.etiquetas.options[0].selected = false;
      }
      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;

    }

  }

}

function js_desabexc() {

  for (iI = 0; iI < document.form1.etiquetas.length; iI++) {

    if (document.form1.etiquetas.length > 0 && document.form1.etiquetas.options[iI].selected) {

      if (document.form1.exames.length > 0) { 

        document.form1.exames.options[0].selected = false;

      }
      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;

    }

  }

}

function js_escolhemodelo() {

  if (js_validadata()) {

    if ((document.form1.la22_d_ini.value == '') || (document.form1.la22_d_fim.value == '')) {

      alert('Entre com as datas de inicio e fim!');
      return false;

    }
    if (document.form1.modelos.value=="") {
      alert("Informe o modelo para impressão!");
    } else {

      var iTam     = document.form1.la24_i_setor.length;
      la24_i_setor = "";
      sep          = "";
      for (iI = 0; iI < iTam; iI++) {

        if(document.form1.la24_i_setor[iI].selected == true) {

          la24_i_setor += sep + document.form1.la24_i_setor[iI].value;
          sep = ",";
          
        }
      }
      var sLocal     = 'lab4_etiquetacoleta001.php?modeloselect=' + document.form1.modelos.value;
      sLocal        += '&la24_i_setor='+document.form1.la24_i_setor.value;
      sLocal        += '&la02_i_codigo='+document.form1.la02_i_codigo.value;
      sLocal        += '&la02_c_descr='+document.form1.la02_c_descr.value;
      sLocal        += '&la23_c_descr='+document.form1.la23_c_descr.value;
      sLocal        += '&la24_i_codigo='+document.form1.la24_i_codigo.value;
      sLocal        += '&la22_d_ini='+document.form1.la22_d_ini.value;
      sLocal        += '&la22_d_ini_dia='+document.form1.la22_d_ini_dia.value;
      sLocal        += '&la22_d_ini_mes='+document.form1.la22_d_ini_mes.value;
      sLocal        += '&la22_d_ini_ano='+document.form1.la22_d_ini_ano.value;
      sLocal        += '&la22_d_fim='+document.form1.la22_d_fim.value;
      sLocal        += '&la22_d_fim_dia='+document.form1.la22_d_fim_dia.value;
      sLocal        += '&la22_d_fim_mes='+document.form1.la22_d_fim_mes.value;
      sLocal        += '&la22_d_fim_ano='+document.form1.la22_d_fim_ano.value;
      location.href  = sLocal;

    }

  }

}

function js_limpar() {

  js_divCarregando("Aguarde, limpando registros", "MSG");
  document.form1.exames.length    = 0;
  document.form1.etiquetas.length = 0;   
  js_removeObj("MSG");

}

function js_pesquisala02_i_laboratorio(lMostra) {

  if (lMostra==true) {

    var sFnc  = 'func_lab_laboratorio.php?checkLaboratorio=true';
    sFnc     += '&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr';
    js_OpenJanelaIframe('', 'db_iframe_laboratorio',
                      sFnc, 'Pesquisa', true
                     );
  } else {

    if (document.form1.la02_i_codigo.value != '') {
         
      js_OpenJanelaIframe('', 'db_iframe_laboratorio', 
                          'func_lab_laboratorio.php?checkLaboratorio=true&pesquisa_chave='
                          + document.form1.la02_i_codigo.value + '&funcao_js=parent.js_mostralaboratorio',
                          'Pesquisa',
                          false
                         );

    } else {
      document.form1.la02_c_descr.value = ''; 
    }

  }

}

function js_mostralaboratorio(iChave, lErro) {

  document.form1.la02_c_descr.value = iChave; 
  if (lErro == true) { 

    document.form1.la02_i_codigo.focus(); 
    document.form1.la02_i_codigo.value = ''; 

  }

}

function js_mostralaboratorio1(iChave1, sChave2) { 

  document.form1.la02_i_codigo.value = iChave1;
  document.form1.la02_c_descr.value  = sChave2;
  db_iframe_laboratorio.hide();

}


function js_pesquisala24_i_setor(lMostra) {

  if (document.form1.la02_i_codigo.value == '') {

    alert('Escolha um laboratorio primeiro.');
    js_limpaCamposTrocaLab();
    return false;

  }
  sPesq = 'la24_i_laboratorio=' + document.form1.la02_i_codigo.value + '&';
  if (lMostra == true) {
    
    js_OpenJanelaIframe('', 
                        'db_iframe_lab_labsetor', 'func_lab_labsetor.php?' + sPesq + 
                        'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo',
                        'Pesquisa',
                        true
                       );
    
  } else {
    
    if (document.form1.la24_i_setor.value != '') { 
        
      js_OpenJanelaIframe('', 
    	                    'db_iframe_lab_labsetor','func_lab_labsetor.php?' + sPesq +
                          'pesquisa_chave=' + document.form1.la24_i_setor.value + 
                          '&funcao_js=parent.js_mostralab_labsetor', 
                          'Pesquisa',
                          false
                         );

    } else {
       document.form1.la23_c_descr.value = ''; 
    }

  }

}

function js_mostralab_labsetor(sChave, lErro, iChave2) {

 document.form1.la23_c_descr.value  = sChave; 
 document.form1.la24_i_codigo.value = iChave2; 
 if (lErro == true) {
 
   document.form1.la24_i_setor.focus(); 
   document.form1.la24_i_setor.value  = ''; 
   document.form1.la24_i_codigo.value = ''; 

 }
 
}

function js_mostralab_labsetor1(chave1,chave2,chave3) {

 document.form1.la24_i_setor.value  = chave1;
 document.form1.la24_i_codigo.value = chave3;
 document.form1.la23_c_descr.value  = chave2;
 db_iframe_lab_labsetor.hide();

}


function js_validadata() {
          
  if (document.form1.la22_d_ini.value != ''  && document.form1.la22_d_fim.value != '') {

    aIni = document.form1.la22_d_ini.value.split('/');
    aFim = document.form1.la22_d_fim.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);
    if (dFim < dIni) {
                                      
      alert("Data final nao pode ser menor que a data inicial.");
      document.form1.la22_d_fim.value = '';
      return false;
      
    }
    return true;

  } else {

    alert('Preencha o periodo.');
    return false

  }

}

function js_emite() {

  var iQtd   = document.form1.etiquetas.length;
  var sLista = "";
  var sSep   = "";
  for (iI = 0; iI < iQtd; iI++) { 

    sLista += sSep+document.form1.etiquetas[iI].value;
    sSep    = ",";

  }
  if (document.form1.modelos.value == "M1") {

    window.open('lab4_etiquetacoleta002.php?lista=' + sLista , janela, 'width=' + (screen.availWidth-5) + ',height=' + 
                (screen.availHeight-40)+',scrollbars=1,location=0'
               );

  } else if (document.form1.modelos.value == "M2") { 

    window.open('lab4_etiquetacoleta003.php?lista=' + sLista, janela, 'width=' + (screen.availWidth-5) + ',height=' +
                (screen.availHeight-40)+',scrollbars=1,location=0'
               );

  }


}
</script>