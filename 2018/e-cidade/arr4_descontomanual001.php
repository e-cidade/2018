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
 
require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/db_sql.php';

$oGet = db_utils::postMemory($_GET);

$sActionFormulario = 'arr4_descontomanual002.php';

if ( !empty($oGet->iOpcao) && $oGet->iOpcao == 3 ) {
  $sActionFormulario = 'arr4_descontomanual003.php';
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("estilos.css, scripts.js, strings.js, prototype.js"); ?>
</head>
<body bgcolor="#cccccc">

<div class="container">

  <form name="form1" action="<?php echo $sActionFormulario; ?>" method="post" onSubmit="return js_valida();">
    
    <fieldset>
      <legend>
        <strong>Numpre do Débito:</strong>
      </legend>
      <table>
        <tr>
          <td nowrap>
            <strong>Numpre:</strong>
          </td>
          <td nowrap>
            <?php db_input('k00_numpre', 8, 1, true, 'text', 2); ?>
          </td>
        </tr>
      </table>
    </fieldset>
  
    <input type="submit" name="pesquisar" value="Pesquisar" />
  
  </form>

</div>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

<script type="text/javascript">

/**
 * Validacao do formulario
 * - nao submita formulario caso nao for informado numpre
 *
 * @access public
 * @return void
 */
function js_valida() {

  if ( $F('k00_numpre') == '' ) {

    alert('Por favor, informe um Numpre válido.');
    return false;
  }

  return true;
}

</script>

</body>
</html>