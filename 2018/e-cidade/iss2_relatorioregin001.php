<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBSeller Servicos de Informatica
*                    www.dbseller.com.br
*                 e-cidade@dbseller.com.br
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");
?>
<html>
 <head>
   <script src="scripts/scripts.js"></script>
   <script src="scripts/prototype.js"></script>
   <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
  <body>
    <div class="container">
      <form>
     <fieldset>
        <legend>Relatório de Integração com a Junta Comercial (REGIN)</legend>
       <table>
         <tbody>
          <tr>
            <td>
              <label for="data_inicial" class="bold">Data Inicial:</label>
            </td>
            <td>
              <input type="text" name="data_inicial" id="data_inicial">
            </td>
            <td>
              <label for="data_final" class="bold">Data Final:</label>
            </td>
            <td>
              <input type="text" name="data_final" id="data_final">
            </td>
          </tr>
         <tr>
           <td>
             <label for="acao" class="bold">Ação:</label>
           </td>
           <td>
             <?php db_select('acao', array(0 => 'Todos', 1 => 'Constituição', 2 => 'Alteração'), true, 1); ?>
           </td>
         </tr>
         </tbody>
       </table>

     </fieldset>
          <input type="button" value="Gerar" onclick="validar();">
      </form>

    </div>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script type="text/javascript">
      var campoDataInicial = new DBInputDate($('data_inicial'));
      var campoDataFinal = new DBInputDate($('data_final'));
      var hoje = new Date;
      var primeiroDiaDoMesAtual = new Date(hoje.getFullYear(), hoje.getMonth(), 1);

      campoDataInicial.setValue(primeiroDiaDoMesAtual.toLocaleDateString());
      campoDataFinal.setValue(hoje.toLocaleDateString());

      function validar () {

        var valorDataInicial = $F('data_inicial');
        var valorDataFinal = $F('data_final');
        var acao = $F('acao');
        if ( !valorDataInicial && !valorDataFinal ) {

          alert('Pelo menos uma data deve ser informada.');
          return false;

        }

        if ( valorDataInicial && valorDataFinal ) {
          if ( js_comparadata(valorDataInicial, valorDataFinal, '>') ) {

            alert('Data inicial deve ser anterior à data final.');
            return false;

          }
        }
        var url  = 'iss2_relatorioregin002.php?data_inicial=' + valorDataInicial + '&data_final=' + valorDataFinal;
            url += '&acao=' + acao;
        window.open(url, '', 'scrollbars=1,location=0');
      }
    </script>
  </body>
</html>
<?php
db_menu()
?>
