<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
?>
<center>
  <form name="form1" action="" method="post">

    <fieldset style="width: 300px;" >

        <table width="100%" border="0" cellspacing="0">
          <tr>
            <td width="33%">Banco:</td>
            <td width="67%"><input name="k15_codbco" type="text" id="k15_codbco" size="4" maxlength="3">    <input name="db_opcao" type="hidden" value="<?=$db_opcao?>"></td>
          </tr>
          <tr>
            <td>Agência:</td>
            <td><input name="k15_codage" type="text" id="k15_codage" size="6" maxlength="5"></td>
          </tr>
          <?
            if (@$db_opcao != 5){
          ?>
          <tr>
            <td>Data:</td>
            <td>
              <?
             db_inputdata('datapes', '', '', '', true, 'text', 1);
              ?>
            </td>
          </tr>
          <?
            }
          ?>
      </table>

     </fieldset>
      <table width="100%" border="0" cellspacing="0" style="margin-top: 10px;">
          <tr align="center">
            <td colspan="2">
         <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisa"></td>
      </tr>
        </table>

    </form>
      </center>