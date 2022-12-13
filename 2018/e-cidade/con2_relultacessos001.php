<?
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
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_liborcamento.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
  <br/>
  <br/>
  <form action="" method="post" name="form1">  
    <table align="center">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>&nbsp;Relatório de Acessos por Usuário&nbsp;</b>
            </legend>
            <table width="100%">
              <tr>
                <td width="100"><strong>Período de: </strong></td>
                <td>
                  <? db_inputdata('sDataInicial', '', '', '', true, 'text', 1); ?>&nbsp;
                  <b>até:</b> <? db_inputdata('sDataFinal', '', '', '', true, 'text', 1); ?>
                </td>
              </tr>
              <tr>
                <td><strong>Tipo de Usuário:</strong></td>
                <td>
                  <?
                    $aTiposUsuario = array ('t' => 'Todos', 
                                            '0' => 'Internos', 
                                            '1' => 'Externos');
                    db_select('iTipoUsuario', $aTiposUsuario, true, 1,"style='width:125px'");
                  ?>
                </td>
              </tr>
              <tr>
                <td><strong>Somente Ativos:</strong></td>
                <td>
                  <?
                    $aSomenteAtivos = array(1 => 'Sim', 0 => 'Não');
                    db_select('iSomenteAtivo', $aSomenteAtivos, true, 1,"style='width:125px'");
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <?
                    db_selinstit("", 300, 100);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>        
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="button" name='btnRelatorio' value='Relatório' onclick="js_imprimir()" />
        </td>
      </tr>
    </table>    
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  function js_imprimir() {
      
  
    var oDataInicial = new Date ($F('sDataInicial')).toJSON();
    var oDataFinal   = new Date ($F('sDataFinal')).toJSON();
  
  
    if (js_comparadata($F('sDataInicial'),$F('sDataFinal'),'>')) {
       
      alert ("A data final deve ser menor que a data inicial.");
      return false;
    } else {

      var sListaInstit = new String(document.form1.db_selinstit.value);
      var sRegExp = new RegExp('-','g');
      var sListaInstit = sListaInstit.replace(sRegExp,',');             
  
      var sQuery  ="?iTipoUsuario="+$F('iTipoUsuario')
                  +"&iSomenteAtivo="+$F('iSomenteAtivo')
                  +"&sListaInstit="+sListaInstit
                  +"&sDataInicial="+$F('sDataInicial')
                  +"&sDataFinal="+$F('sDataFinal');                
  
      var sUrl    = 'con2_relultacessos002.php'+sQuery;
      var sParam  = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
      var jan     = window.open(sUrl,'',sParam);
          jan.moveTo(0,0);
    }
  }
  
</script>