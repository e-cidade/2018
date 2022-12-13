<?php
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
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
  
  db_postmemory($HTTP_POST_VARS);
  
  $rotulocampo = new rotulocampo;
  
  $rotulocampo->label("DBtxt23");
  $rotulocampo->label("DBtxt25");
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load('scripts.js,estilos.css, prototype.js, strings.js');
    ?>
  </head>
  <body bgcolor="#CCCCCC">
    <center>
      <fieldset style="width: 500px; margin-top: 35px; margin-bottom: 10px;">
        <legend>
          <strong>Relatório de Servidores sem Conta</strong>
        </legend>
        <table>
          <form name="form1" method="post" action="" onsubmit="return js_verifica();">
            <tr>
              <td nowrap title="Digite o Ano / Mês de competência" >
                <strong>Ano / Mês:</strong>
              </td>
              <td>
                <?php
                  $DBtxt23 = db_anofolha();
                  
                  db_input("DBtxt23", 4, $IDBtxt23, true, "text", 2, "");
                  
                  echo " / ";
                  
                  $DBtxt25 = db_mesfolha();
                  
                  db_input("DBtxt25", 2, $IDBtxt25, true, "text", 2, "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Tipo :</strong>
              </td>
              <td>
                <?php
                  $arr_tipo = array("g" => "Geral",
                                    "o" => "Órgão",
                                    "u" => "Unidade",
                                    "t" => "Locais de Trabalho",
                                    "r" => "Recurso",
                                    );
                  
                  db_select("tipo", $arr_tipo, true, 4);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Troca de Página :</strong>
              </td>
              <td>
                <?php
                  $arr_troca = array("n" => "Não",
                                     "s" => "Sim");
                  
                  db_select("troca", $arr_troca, true, 4, "style='width: 60px;'");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Formato Relatório :</strong>
              </td>
              <td>
                <?php
                  $arr_Formatos = array("pdf" => "PDF",
                                        "csv" => "CSV");
                  
                  db_select("formato", $arr_Formatos, true, 4, "style='width: 60px;'");
                ?>
              </td>
            </tr>
          </form>
        </table>
      </fieldset>
      <table>
        <tr>
          <td colspan="2" align="center"> 
            <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
          </td>
        </tr>
      </table>
    </center>
    <?php
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit"));
    ?>
  </body>
</html>

<?php
  
  if (isset($ordem)) {
    
    echo "<script>
           js_emite();
         </script>";
  }
  
  $func_iframe = new janela("db_iframe", "");
  
  $func_iframe->posX           = 1;
  $func_iframe->posY           = 20;
  $func_iframe->largura        = 780;
  $func_iframe->altura         = 430;
  $func_iframe->titulo         = 'Pesquisa';
  $func_iframe->iniciarVisivel =  false;
  
  $func_iframe->mostrar();
  
?>
<script>
  function js_pesquisatabdesc(mostra) {
    
    if (mostra == true) {
      
      db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
      db_iframe.mostraMsg();
      db_iframe.show();
      db_iframe.focus();
    } else {
      
      db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave=' + document.form1.codsubrec.value + '&funcao_js=parent.js_mostratabdesc';
    }
  }
  
  
  function js_mostratabdesc(chave, erro) {
    
    document.form1.k07_descr.value = chave;
     
    if (erro == true) {
      
      document.form1.codsubrec.focus();
      document.form1.codsubrec.value = '';
    }
  }
  
  
  function js_mostratabdesc1(chave1, chave2) {
    
    document.form1.codsubrec.value = chave1;
    document.form1.k07_descr.value = chave2;
    
    db_iframe.hide();
  }
  
  
  function js_emite() {

    alert('Relatório é emitido a partir da Geração em Disco!');
    
    var sQuery = 'ano='      + document.form1.DBtxt23.value  +
                 '&mes='     + document.form1.DBtxt25.value  +
                 '&trocap='  + document.form1.troca.value    +
                 '&tipo='    + document.form1.tipo.value     +
                 '&formato=' + document.form1.formato.value;
    
    oJanela = window.open('pes2_semconta002.php?'+sQuery,
    	                    '',
    	                    'width=' + (screen.availWidth - 5)
    	                     + ',height=' + (screen.availHeight - 40)
    	                     + ',scrollbars=1,location=0 ');
    oJanela.moveTo(0, 0);
  }
  
  
  function js_detectaarquivo(sNomeArquivo) {
    
    oJanela.close();
    sLista = sNomeArquivo+"#Download arquivo";
    js_montarlista(sLista,"form1");
  }
</script>