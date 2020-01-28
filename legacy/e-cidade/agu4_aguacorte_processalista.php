<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_aguacorte_classe.php");
  include("classes/db_aguacortemat_classe.php");
  include("classes/db_aguacortematmov_classe.php");
  include("classes/db_aguacortematnumpre_classe.php");
  include("classes/db_aguacortetipodebito_classe.php");
  include("classes/db_aguabasecar_classe.php");

  $claguacorte = new cl_aguacorte;
  $claguacortemat = new cl_aguacortemat;
  $claguacortematmov = new cl_aguacortematmov;
  $claguacortematnumpre = new cl_aguacortematnumpre;
  $claguacortetipodebito = new cl_aguacortetipodebito;
  $claguabasecar = new cl_aguabasecar;

  db_postmemory($HTTP_POST_VARS);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >		    
    <center>
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	          <?
	            include("forms/db_frmaguacorte_processalista.php");
	          ?>
          </td>
        </tr>
      </table>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
  </body>
</html>

<?
  if  (isset($processa) && $acao == 'gerar') {
?>
  <script>
    js_OpenJanelaIframe('top.corpo', 'db_iframe1', 'agu4_aguacorte_processalista001.php?x40_codcorte=' +
    	document.form1.x40_codcorte.value + '&x41_dtprazo_ano=' + document.form1.x41_dtprazo_ano.value +
    	'&x41_dtprazo_mes=' + document.form1.x41_dtprazo_mes.value + '&x41_dtprazo_dia=' +
    	document.form1.x41_dtprazo_dia.value, 'Processa Lista', true, 20);
  </script>
<?
  } elseif(isset($processa) && $acao == 'reprocessar') {
?>
  <script>
    js_OpenJanelaIframe('top.corpo', 'db_iframe', 'agu4_aguacorte_processalista003.php?x40_codcorte=' + 
    	document.form1.x40_codcorte.value + '&x43_codsituacao=' + document.form1.x43_codsituacao.value +
    	'&x43_codsituacao2=' + document.form1.x43_codsituacao2.value + '&x43_codsituacao3=' +
    	document.form1.x43_codsituacao3.value, 'Reprocessa Lista', true, 20);
  </script>
<?
  }
?>