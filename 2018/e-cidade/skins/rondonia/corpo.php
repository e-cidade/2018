<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

  function removeAcento($var) {

    $var = ereg_replace("[íì]","i",$var);
    $var = ereg_replace("[áàâãª]","a",$var);
    $var = ereg_replace("[éèê]","e",$var);
    $var = ereg_replace("[óòôõº]","o",$var);
    $var = ereg_replace("[úùû]","u",$var);
    $var = str_replace("ç","c",$var);

    return $var;
  }
?>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>

    <?php if (pg_numrows($rsInstituicao) > 0):
      db_fieldsmemory($rsInstituicao, 0); ?>

      parent.topo.document.getElementById('infoConfig').innerHTML = '<strong><?php echo $nome; ?></strong> (<?php echo $ender; ?> \| Fone:&nbsp;<?php echo $telef; ?>'
                                                                    + '&nbsp;&nbsp;-&nbsp;&nbsp;Cep:&nbsp;<?php echo $cep; ?>)';
      parent.topo.document.getElementById('linkprefa').href = '<?php echo $url; ?>/dbpref/';
      parent.topo.document.getElementById('linkprefa').target = '_blank';

    <?php endif; ?>

  </script>
  <style type="text/css">
    <!--
    a {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #FFFFFF;
      text-decoration: none;
    }
    a:hover {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #FFFFFF;
      text-decoration: none;
    }
    .bordas {
      border: 1px solid #000000;
    }
    -->
  </style>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="middle" bgcolor="#f4f4f4" background="skins/img.php?file=bgArea.jpg">
        <table border="0" cellspacing="1" cellpadding="0">
          <tr>
            <?php

              for($i = 0;$i < $iNumRowsModulos;$i++) {
                echo "<a  title='".pg_result($rsModulos,$i,"help")."' id=\"link\" href=\"modulos.php?".base64_encode("anousu="
                     .pg_result($rsModulos,$i,"anousu")."&modulo=".pg_result($rsModulos,$i,"id_item")."&nomemod=".pg_result($rsModulos,$i,"nome_modulo"))
                     ."\"><img src=\"skins/img.php?file=Modulos/"
                     .str_replace(" ", "", removeAcento(pg_result($rsModulos,$i,"nome_modulo"))).".png\" alt=\"".pg_result($rsModulos,$i,"help")
                     ."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\" border=\"0\" width=\"100\" height=\"100\"></a>\n";

                if ((($i + 1) % 3) == 0) {
                  echo "<br />\n";
                }
              }
            ?>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div id="joao"></div>
</body>
<script>
  parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;Selecione o módulo clicando na figura.';
  parent.bstatus.document.getElementById('dtatual').innerHTML  = '<?=(isset($HTTP_SESSION_VARS["DB_datausu"])?date("d/m/Y",db_getsession("DB_datausu")):date("d/m/Y"))  ?>';
  parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?=(isset($HTTP_SESSION_VARS["DB_anousu"])?db_getsession("DB_anousu"):date("Y"))  ?>';
</script>