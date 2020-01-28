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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo;
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('z01_v_nome');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<br><br>
  <table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
        <form name='form1'>
        <fieldset style='width: 40%;'>
          <legend>
            <b>Cadastro Geral da Saúde</b>
          </legend>
            <table>
	            <tr>
	              <td title="<?=@$Tz01_i_cgsund?>">
                  <?php
                    db_ancora($Lz01_i_cgsund, 'buscaCGS(true);', 1);
                  ?>
                </td>
		            <td>
                  <?php
                    db_input('z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', 1,
                             'onchange="buscaCGS(false);"'
                            );
                    db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 1,
                             'onchange="buscaCGS(false);"'
                            );
                  ?>
                </td>
	            </tr>
            </table>
        </fieldset>
        <table>
          <tr>
            <td>
              <input name="pesquisar" id="pesquisar" type="button" value="Pesquisar" onclick="pesquisaCGS();">
            </td>
           </tr>
        </table>
       </form>
      </td>
    </tr>
  </table>
</center>
<?php
  db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'),
          db_getsession('DB_anousu'), db_getsession('DB_instit')
         );
?>

<script type="text/javascript">

  document.observe("dom:loaded", function() {

    $('z01_v_nome').readOnly = true;
    $('z01_v_nome').style.backgroundColor = '#DEB887';
  });

  function buscaCGS(mostra) {

    if(mostra == true) {

      js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.mostraCSG1|'+
                          'z01_i_cgsund|z01_v_nome', 'Pesquisa', true
                         );
      return;
    }

    if($F('z01_i_cgsund') != '') {

      js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?pesquisa_chave='+$F('z01_i_cgsund')+
                              '&funcao_js=parent.mostraCSG', 'Pesquisa', false
                          );

    } else {
      $('z01_v_nome').value = '';
    }
  }

  function mostraCSG(chave, erro) {

    $('z01_v_nome').value = chave;

    if(erro == true) {

      $('z01_i_cgsund').focus();
      $('z01_i_cgsund').value = '';
    }
  }

  function mostraCSG1(chave1, chave2) {

    $('z01_i_cgsund').value = chave1;
    $('z01_v_nome').value   = chave2;
    db_iframe_cgs_und.hide();
  }

  function pesquisaCGS() {

    var iCodigoCSG = $F('z01_i_cgsund');

    if(iCodigoCSG == '') {

      alert('Selecione um CGS.');
      return false;
    }

    js_OpenJanelaIframe('', 'db_iframe_cgs_consulta', 'sau3_consultacgs002.php?iCgs=' + iCodigoCSG,
                        'Cadastro Geral da Saúde', true
                       );
  }

</script>

</body>
</html>