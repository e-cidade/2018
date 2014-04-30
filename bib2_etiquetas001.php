<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_exemplar_classe.php");
require_once("classes/db_impexemplar_classe.php");
require_once("classes/db_impexemplaritem_classe.php");
require_once("classes/db_localizacao_classe.php");

db_postmemory($HTTP_POST_VARS);

$clexemplar        = new cl_exemplar;
$climpexemplar     = new cl_impexemplar;
$climpexemplaritem = new cl_impexemplaritem;
$cllocalizacao     = new cl_localizacao;
$depto             = db_getsession("DB_coddepto");
$sql               = "SELECT bi17_codigo FROM biblioteca WHERE bi17_coddepto = $depto";
$result            = pg_query($sql);
$linhas            = pg_num_rows($result);

if ($linhas > 0) {
  db_fieldsmemory($result, 0);
}

if (isset($listaimpressao)) {

  $climpexemplar->bi24_biblioteca = $bi17_codigo;
  $climpexemplar->bi24_usuario    = db_getsession("DB_id_usuario");
  $climpexemplar->bi24_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $climpexemplar->bi24_hora       = date("H:i");
  $climpexemplar->bi24_modelo     = $modeloselect;
  $climpexemplar->incluir(null);
  $bi25_impexemplar = $climpexemplar->bi24_codigo;
  $qtdexemp         = explode(",",$listaimpressao);
  
  for ($i = 0; $i < count($qtdexemp); $i++) {
  
    $climpexemplaritem->bi25_impexemplar = $bi25_impexemplar;
    $climpexemplaritem->bi25_exemplar    = $qtdexemp[$i];
    $climpexemplaritem->incluir(null);
  
  }

  db_putsession('sListaImpressao', $listaimpressao);
  
  if ($modeloselect == "M1") {
  	
  	echo "<script>";
  	echo "window.open('bib2_etiquetas003.php?ordenacao=$ordenacao','',
  										'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
  	echo "</script>";
  	
  } else if ($modeloselect == "M2") {

  	echo "<script>";
  	echo "window.open('bib2_etiquetas002.php?ordenacao=$ordenacao','',
  	  										'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
  	echo "</script>";
  	
  } else if ($modeloselect == "M3") {
  	
  	echo "<script>";
  	echo "window.open('bib2_etiquetas004.php?ordenacao=$ordenacao','',
  	  										'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
  	echo "</script>";
  	
  }
  
  db_redireciona("bib2_etiquetas001.php");
  exit;

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script>
      function js_emite() {
        
        qtd   = document.form1.alunos.length;
        lista = "";
        sep   = "";
        
        for (i = 0; i < qtd; i++) {
          
          lista += sep+document.form1.alunos[i].value;
          sep    = ",";
        
        }

        if(lista == '') {
				  alert('Nenhuma lista de impressão selecionada!');
				  return false;
        }
        document.form1.listaimpressao.value = lista;

        return true;

        
        /*if (document.form1.modelos.value == "M1") {
          
          window.open('bib2_etiquetas003.php?lista='+lista+'&ordenacao='+document.form1.ordenacao.value,janela,
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        
        } else if (document.form1.modelos.value == "M2") {
          
          window.open('bib2_etiquetas002.php?lista='+lista+'&ordenacao='+document.form1.ordenacao.value,janela,
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

        } else if (document.form1.modelos.value == "M3") {

          window.open('bib2_etiquetas004.php?lista='+lista+'&ordenacao='+document.form1.ordenacao.value,janela,
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

        }*/
        
        //location.href = "bib2_etiquetas001.php?modeloselect="+document.form1.modelos.value+"&listaimpressao="+lista;
       
      }

    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="10">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",
               ""," bi17_coddepto = ".db_getsession("DB_coddepto").""
              );
    ?>
    <br>
    <center>
      <fieldset align="center" style="width:95%"><legend><b>Relatório de Etiquetas</b></legend>
        <table align="center">
          <form name="form1" method="post" action="" onsubmit="return js_emite();">
            <tr>
              <td colspan="3">
                <table align="center">
                  <tr>
                    <td>
                      <b>Escolha o modelo pra impressão:</b>
                    </td>
                    <td>
                      <?
                        if (isset($modeloselect)) {
                          $on_change = "onChange='document.form1.alunospossib.length=0;document.form1.alunos.length=0;'";
                        } else {
                          $on_change = "";
                        }
                      ?>
                      <select name="modeloselect" <?=$on_change?> style="width:450px;">
                        <option value=""></option>
                        <option value="M1" <?=@$modeloselect == "M1" ? "selected" : ""?>>
                            Modelo 1 ( 3 x 10 - Código de Barras / Código Exemplar / Título )</option>
                        <option value="M2" <?=@$modeloselect == "M2" ? "selected" : ""?>>
                            Modelo 2 ( 4 x 11 - Código de Barras / Código Exemplar / Título )</option>
                        <option value="M3" <?=@$modeloselect == "M3" ? "selected" : ""?>>
                            Modelo 3 ( 4 x 10 - Localização do Acervo / Assunto / Ordenação )</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="javascript:js_pesquisalocalizacao();"><b>Localização:</b></a>
                    </td>
                    <td>
                      <select name="cod_localizacao" onblur="js_selected_all()" 
                              onclick="js_selected_all()" size="4" multiple style="width:450px;">
                      <?
                        if ($cod_localizacao != "") {
                          
                          $sCampos       = " bi09_codigo,bi09_nome ";
                          $sOrder        = " bi09_nome ";
                          $sWhere        = " bi09_biblioteca = $bi17_codigo AND bi09_codigo in ($cod_localizacao) ";
                          $sSql          = $cllocalizacao->sql_query_file("", $sCampos, $sOrder, $sWhere);
                          $rsLocalizacao = $cllocalizacao->sql_record($sSql);
                          
                          for ($iCont = 0; $iCont < $cllocalizacao->numrows; $iCont++) {
                          
                            $oDados = db_utils::fieldsmemory($rsLocalizacao, $iCont);
                            echo "<option value='$oDados->bi09_codigo' selected>$oDados->bi09_nome</option>";
                          
                          }
                        
                        }
                      ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Buscar por:</b>
                    </td>
                    <td>
                      <select name="ordembusca" <?=$on_change?> >
                        <option value="bi09_nome,bi06_titulo,bi23_codigo" 
                          <?=@$ordembusca == "bi09_nome,bi06_titulo,bi23_codigo" ? "selected" : ""?> >
                          Ordem Alfabética
                        </option>
                        <option value="bi09_nome,bi20_sequencia,bi27_letra" 
                          <?=@$ordembusca == "bi09_nome,bi20_sequencia,bi27_letra" ? "selected" : ""?> >
                          Ordem na Localização
                        </option>
                      </select>
                      <input type="button" name="buscar" value="Buscar" onclick="js_escolhemodelo()">
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <? if (isset($modeloselect)) {

                  $sql2 = "SELECT * from impexemplaritem
                               inner join impexemplar  on  impexemplar.bi24_codigo = impexemplaritem.bi25_impexemplar
                               inner join exemplar on impexemplaritem.bi25_exemplar=exemplar.bi23_codigo
                               inner join acervo on exemplar.bi23_acervo=acervo.bi06_seq
                             WHERE bi24_biblioteca = $bi17_codigo
                               AND bi24_usuario = ".db_getsession("DB_id_usuario")."
                             ORDER BY bi24_data desc,bi24_hora desc";
                  $result2   = pg_query($sql2);
                  
                  $restricao = "SELECT * from impexemplaritem
                                    inner join impexemplar  on  impexemplar.bi24_codigo = impexemplaritem.bi25_impexemplar
                                  WHERE bi24_modelo = '$modeloselect'
                                    AND bi23_codigo = bi25_exemplar";
                  
                  if (trim($cod_localizacao) != "") {
                    $cond_local = " AND bi20_localizacao in ($cod_localizacao)";
                  } else {
                    $cond_local = "";
                  }

                  $sCampos  = " bi23_codigo,bi06_titulo,bi23_codbarras,bi20_sequencia";
                  $sCampos .= " ||bi27_letra as sequencial,bi09_nome ";
                  $sWhere   = " bi23_situacao = 'S' AND bi06_biblioteca = $bi17_codigo AND not exists($restricao) $cond_local ";
                  $sSql     = $clexemplar->sql_query("", $sCampos, $ordembusca, $sWhere);
                  $result   = $clexemplar->sql_record($sSql);
   
                  //Retirada clausula onde a etiqueta não podia ser impressa pela segunda vez
                  //$sql = $clexemplar->sql_query("",,$ordembusca," bi23_situacao = 'S' AND bi06_biblioteca = $bi17_codigo AND not exists($restricao) $cond_local"); die($sql);
                  //$result = $clexemplar->sql_record($clexemplar->sql_query("","bi23_codigo,bi06_titulo,bi23_codbarras,bi20_sequencia||bi27_letra as sequencial,bi09_nome",$ordembusca," bi23_situacao = 'S' AND bi06_biblioteca = $bi17_codigo AND not exists($restricao) $cond_local"));
                ?>
                <b>Exemplares disponíveis:</b> Cód.Exemplar (Localização__Ordem) Título Acervo<br>
                <select name="alunospossib" id="alunospossib" size="10" onclick="js_desabinc()" 
                        ondblclick="js_alunospossib()" style="font-size:9px;width:450px;height:180px" multiple>
                <?
                  if ($clexemplar->numrows > 0) {
                    
                    for ($i = 0; $i < $clexemplar->numrows; $i++) {
                      
                      db_fieldsmemory($result, $i);
                      $desc_local = $bi09_nome == "" ? "SEM LOCALIZAÇÃO" : 
                                      $bi09_nome.str_pad($sequencial, 5, "_", STR_PAD_LEFT);
                      $verifica   = false;
                      
                      if (pg_num_rows($result2) > 0) {
                        
                        $data = pg_result($result2, 0, 'bi24_data');
                        $hora = pg_result($result2, 0, 'bi24_hora');
                        
                        for ($f = 0; $f < pg_num_rows($result2); $f++) {
                          
                          $codigo_exemp = pg_result($result2, $f, 'bi23_codigo');
                          $bi24_data    = pg_result($result2, $f, 'bi24_data');
                          $bi24_hora    = pg_result($result2, $f, 'bi24_hora');
                          $bi24_modelo  = pg_result($result2, $f, 'bi24_modelo');
                          
                          if (trim($codigo_exemp) == trim($bi23_codigo) 
                              && ($bi24_data == $data) && ($bi24_hora == $hora) 
                              && ($modeloselect != $bi24_modelo) && ($bi24_modelo != 'M3')) {
                            
                            $verifica = true;
                            break;
                          
                          }
                        
                        }
                      
                      }
                      
                      if ($verifica == false) {
                        
                        echo "<option value='$bi23_codigo'>".$bi23_codigo." ($desc_local) 
                                $bi06_titulo - $bi23_codbarras</option>\n";
                      
                      }
                    
                    }
                  
                  }
                ?>
                </select>
              </td>
              <td align="center">
                <br>
                <table border="0">
                  <tr>
                    <td>
                      <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_alunospossib();" 
                             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" 
                             disabled>
                    </td>
                  </tr>
                  <tr>
                    <td height="1">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" 
                             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;">
                    </td>
                  </tr>
                  <tr>
                    <td height="8">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <hr>
                    </td>
                  </tr>
                  <tr>
                    <td height="8">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
                             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" 
                             disabled>
                    </td>
                  </tr>
                  <tr>
                    <td height="1">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" 
                             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" 
                             disabled>
                    </td>
                  </tr>
                </table>
              </td>
              <td>
                <? 
                  if (isset($modeloselect)) {
                    
                    $linhas2 = pg_num_rows($result2);
                ?>
                <b>Exemplares para impressão:</b>
                <br>
                <select name="alunos" id="alunos" size="10" onclick="js_desabexc()" 
                         style="font-size:9px;width:350px;height:180px" multiple>
                <?
                  $chek = 0;
                  
                  if ($linhas2 > 0) {
                    
                    db_fieldsmemory($result2, 0);
                    $data = $bi24_data;
                    $hora = $bi24_hora;
                    
                    for ($i = 0;$i < $linhas2; $i++) {
                      
                      db_fieldsmemory($result2, $i);
                      if (($bi24_data == $data) and ($bi24_hora == $hora) 
                           and ($modeloselect != $bi24_modelo) and ($bi24_modelo != 'M3')) {
                        
                        echo "<option value='$bi23_codigo'>".$bi23_codigo." - $bi06_titulo - $bi23_codbarras</option>\n";
                        $chek = 1;
                      
                      }
                    
                    }
                  
                  }
                ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <b>Ordenação da impressão:</b>
                <select name="ordenacao">
                  <option value="bi23_codigo">Código Exemplar</option>
                  <option value="bi06_titulo">Título Acervo</option>
                  <option value="bi09_nome,bi20_sequencia,bi27_letra">Ordem na Localização</option>
                </select>
                <br><br>
                <input name="processar" id="processar" type="submit" value="Processar" disabled>
                <input name="limpar" id="limpar" type="button" value="Limpar" onclick="js_limpar();">
              </td>
            </tr>
            <?
            	db_input('listaimpressao', 10, null, true, 'hidden', 1);
              if ($chek == 1) {
                echo("<script>document.form1.processar.disabled = false;</script>");
              }
              }
              }
            ?>
          </form>
        </table>
      </fieldset>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
              db_getsession("DB_anousu"), db_getsession("DB_instit")
             );
    ?>
  </body>
</html>

<script>

function js_alunospossib() {
 
  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;
  
  for (x = 0; x < Tam; x++) {
    
    if (F.alunospossib.options[x].selected == true) {
      
      F.elements['alunos'].options[F.elements['alunos'].options.length] = new Option(F.alunospossib.options[x].text,F.alunospossib.options[x].value)
      F.alunospossib.options[x] = null;
      Tam--;
      x--;
    
    }
  
  }
  
  if (document.form1.alunospossib.length > 0) {
    
    document.form1.alunospossib.options[0].selected = true;
  
  } else {
    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;
  }

  document.form1.processar.disabled    = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunospossib.focus();

}

function js_incluirtodos() {

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;
  
  for (i = 0;i < Tam; i++) {
    
    F.elements['alunos'].options[F.elements['alunos'].options.length] = new Option(F.alunospossib.options[0].text,F.alunospossib.options[0].value);
    F.alunospossib.options[0] = null;
 
  }

  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  
  if (document.form1.alunos.length > 0) {
    document.form1.processar.disabled = false;
  }

  document.form1.alunos.focus();

}

function js_excluir() {
  
  var F = document.getElementById("alunos");
  Tam   = F.length;
  
  for (x = 0; x < Tam;x++) {

    if (F.options[x].selected == true) {
      
      document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
    
    }
  
  }
  
  if (document.form1.alunos.length > 0) {
    document.form1.alunos.options[0].selected = true;
  }

  if (F.length == 0) {
    
    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
  
  }
  
  document.form1.incluirtodos.disabled = false;
  document.form1.alunos.focus();

}

function js_excluirtodos() {

  var Tam = document.form1.alunos.length;
  var F   = document.getElementById("alunos");
  
  for (i = 0; i < Tam; i++) {

    document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;
  
  }
  
  if (F.length == 0) {

    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
 
  }

  document.form1.alunospossib.focus();

}

function js_desabinc() {

  for (i = 0; i < document.form1.alunospossib.length; i++) {
    
    if (document.form1.alunospossib.length > 0 
        && document.form1.alunospossib.options[i].selected) {
      
      if (document.form1.alunos.length > 0) {
        document.form1.alunos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
    
    }
  
  }

}

function js_desabexc() {

  for (i = 0; i < document.form1.alunos.length; i++) {

    if (document.form1.alunos.length > 0 
        && document.form1.alunos.options[i].selected) {

      if(document.form1.alunospossib.length > 0) {
        document.form1.alunospossib.options[0].selected = false;
      }

      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
    
    }
  
  }

}

function js_escolhemodelo() {

  
  if (document.form1.modeloselect.value == "") {
    alert("Informe o modelo para impressão!");
  } else {
    
    tam             = document.form1.cod_localizacao.length;
    cod_localizacao = "";
    sep             = "";
    
    for (i = 0; i < tam; i++) {
      
      if(document.form1.cod_localizacao[i].selected == true) {
        
        cod_localizacao += sep+document.form1.cod_localizacao[i].value;
        sep = ",";
      
      }
    
    }
    location.href = "bib2_etiquetas001.php?ordembusca="+document.form1.ordembusca.value+
                    "&modeloselect="+document.form1.modeloselect.value+"&cod_localizacao="+cod_localizacao;
  
  }

}

function js_pesquisalocalizacao() {
  
  tam   = document.form1.cod_localizacao.length;
  jatem = "";
  sep   = "";
  
  for (i = 0; i < tam; i++) {
    
    jatem += sep+document.form1.cod_localizacao[i].value+"|"+document.form1.cod_localizacao[i].text;
    sep    = ",";
  
  }
  
  js_OpenJanelaIframe('','db_iframe_localizacao','func_localizacaoetiq.php?jatem='+jatem,'Pesquisa de Localização',true);

}

function js_selected_all() {
  
  tam = document.form1.cod_localizacao.length;
  
  for (i = 0; i < tam; i++) {
    document.form1.cod_localizacao[i].selected = true;
  }

}

function js_limpar() {
 
  js_divCarregando("Aguarde, limpando registros", "MSG");
 
  document.form1.alunospossib.length    = 0;
  document.form1.alunos.length          = 0;
  document.form1.cod_localizacao.length = 0;
 
  js_removeObj("MSG");

}

</script>