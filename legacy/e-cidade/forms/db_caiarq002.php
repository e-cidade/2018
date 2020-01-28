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

$clrotulo = new rotulocampo;
$clrotulo->label('k15_codbco');
$clrotulo->label('k15_codage');
$clrotulo->label('k15_conta');
?>
<form name="form1" method="post" action="">
  <fieldset style="margin: 40px auto 10px; width: 700px;">
    <legend>
      <strong>Processa arquivo txt</strong>
    </legend>
    <table align="center">
      <tr>
        <td><strong>Nome Arquivo:</strong></td>
        <td><input name="arqret" type="hidden" id="arqret" value="<?php echo $arq_tmpname; ?>" size="30" maxlength="30"> <input name="arqname"
          type="text" id="arqname" value="<?php echo $arq_name; ?>" maxlength="30" style="width: 404px;">
        </td>
      </tr>
      <tr>
        <td><?php echo $Lk15_codbco; ?></td>
        <td><input name="k15_codbco" type="text" id="k15_codbco" value="<?php echo $k15_codbco; ?>" size="10" maxlength="3"></td>
      </tr>
      <tr>
        <td><?php echo $Lk15_codage; ?></td>
        <td><input name="k15_codage" type="text" id="k15_codage" value="<?php echo $k15_codage; ?>" size="10" maxlength="5"></td>
      </tr>
      <tr>
        <td><strong>Linhas:</strong></td>
        <td><input name="totalproc" type="text" id="totalproc" value="<?php echo $totalproc; ?>" size="10" maxlength="10"></td>
      </tr>
      <tr>
        <td><strong>Valor total pago:</strong></td>
        <td><input name="totalvalorpago" type="text" id="totalvalorpago" value="<?php echo $totalvalorpago; ?>" size="10" maxlength="10"></td>
      </tr>
      <tr>
        <td><?php echo $Lk15_conta; ?></td>
        <td><input name="k15_conta" type="text" id="k15_conta" value="<?php echo $k15_conta; ?>" size="10" maxlength="5"> <input name="c01_nome"
          type="text" id="c01_nome" value="<?php echo $k13_descr; ?>" size="41" maxlength="40"></td>
      </tr>
    </table>
  </fieldset>
  <center>
    <input name="geradisbanco" type="submit" id="geradisbanco" value="Processar Arquivo"/> &nbsp;&nbsp;
    <input name="Cancela" type="button" onclick="location.href='cai4_baixabanco001.php'" id="Cancela" value="Cancelar"/>
  </center>
</form>