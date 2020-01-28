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
<form name="form1" method="post" action="">
  <fieldset style="margin: 40px auto 10px; width: 700px;">
    <legend>
      <strong>Processa arquivo txt</strong>
    </legend>
    <table align="center">
      <tr>
        <td><strong>Arquivo:</strong></td>
        <td><input name="arqret" type="text" readonly id="arqret" value="<?=$arqname?>" size="40" maxlength="40">
        </td>
      </tr>
      <tr>
        <td><strong>N&uacute;mero Registro:</strong></td>
        <td><input name="totalproc" type="text" id="totalproc" readonly value="<?=$totalproc?>" size="10" maxlength="10">
        </td>
      </tr>
      <tr>
        <td><strong>Processados:</strong></td>
        <td id="proc"><input name="processa" type="text" id="processa" readonly size="10" maxlength="10">
        </td>
      </tr>
    </table>
  </fieldset>
</form>