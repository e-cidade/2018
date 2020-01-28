<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

$db_opcao = 1;
?>
<form name='formImportaArquivoRetencao' enctype="multipart/form-data" method='post' action='iss4_importaarquivoretencao002.php'>

  <fieldset>
    <legend>Importar Arquivo de Retorno - Retenção</legend>
    <table>
       <tr>
         <td nowrap="nowrap">
            <strong>Arquivo de Retorno:</strong>
         </td>
         <td>
           <?php
             db_input('arquivo', 30, '', true, 'file', $db_opcao, 'onChange="js_liberarEnvio();"');
           ?>
         </td>
       </tr>
    </table>
  </fieldset>

  <input name="importar" type="submit" id="db_opcao" value="Importar Arquivo" disabled="disabled"/>

</form>
<script type="text/javascript">

function js_liberarEnvio (){

  if( !empty($F('arquivo')) ){
    $('db_opcao').disabled = false;
  }
}

</script>