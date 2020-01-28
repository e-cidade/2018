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
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
    ?>
    
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

    <?php if (isset($_GET['sMessage'])) { ?>
            
            <script type="text/javascript">
              alert('<?php echo $_GET['sMessage']; ?>');
            </script>
    <?php } ?>

    <div class="container">
      <form action="iss1_importararquivosimplesnacional002.php" method="POST" id="importa_arquivo" enctype="multipart/form-data">
        <fieldset>
          <legend>Importar Arquivo Simples Nacional</legend>
          <table class="form-container">
            <tr>
              <td>
                <label>Arquivo: </label>
              </td>
              <td>
                <input type="file" name="arquivo" id="arquivo" />
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="submit" name="processar" value="Processar" onclick="return js_processar()" />
      </form>
    </div>


  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

  <script type="">

    function js_processar() {
      
      var sCaminhoMensagens = "tributario.issqn.iss1_importararquivosimplesnacional001.";

      /**
       * Verifica se o arquivo é .txt
       */
      var sArquivo  = $F('arquivo');
      var aExtencao = sArquivo.split('.');
      var sExtencao = aExtencao[aExtencao.length - 1];
      var sErro     = '';
      var lErro     = false;

      if( empty(sArquivo)) {

        var sErro     = _M(sCaminhoMensagens+'arquivo_obrigatorio');
        var lErro     = true;
      }

      if (sExtencao != 'txt' && sExtencao != 'TXT') {

        var sErro     = _M(sCaminhoMensagens+'extencao_invalida');
        var lErro     = true;
      }

      if (lErro) {

        alert(sErro);
        return false;
      }

      return true;
    }
  </script>
  </body>
</html>