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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
        $oRotulo = new rotulocampo;
        $oRotulo->label('s161_i_receita');
        $oRotulo->label('s161_i_login');
        $oRotulo->label('s161_d_data');
        $oRotulo->label('s161_c_hora');
        $oRotulo->label('s161_c_motivo');
        $oRotulo->label('login');
        ?>
        <form name="form1" method="post" action=''>
        <center>
        <table border="0">
          <tr>
            <td nowrap>
              <fieldset style='width: 96%;'> <legend><b>Receita:</b></legend>
                <table>
                  <tr>
                    <td nowrap title="<?=$Ts161_i_receita?>">
                      <?=$Ls161_i_receita?>
                    </td>
                    <td> 
                      <?
                      db_input('s161_i_receita', 10, $Is161_i_receita, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <fieldset style='width: 96%;'> <legend><b>Anular Receita:</b></legend>
                <table width="100%">
                  <tr>
                    <td nowrap title="<?=@$Ts161_d_data?>">
                      <?=$Ls161_d_data?>
                    </td>
                    <td> 
                      <?
                      $s161_d_data_dia = date('d', db_getsession('DB_datausu'));
                      $s161_d_data_mes = date('m', db_getsession('DB_datausu'));
                      $s161_d_data_ano = date('Y', db_getsession('DB_datausu'));
                      db_inputdata('s161_d_data', $s161_d_data_dia, $s161_d_data_mes,
                                   $s161_d_data_ano, true, 'text', 3, ''
                                  );
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Ts161_c_hora?>">
                      <?=$Ls161_c_hora?>
                    </td>
                    <td> 
                      <?
                      $s161_c_hora = date('H:i');
                      db_input('s161_c_hora', 5, $Is161_c_hora, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Ts161_i_login?>">
                      <?=$Ls161_i_login?>
                    </td>
                    <td> 
                      <?
                      $s161_i_login = db_getsession('DB_id_usuario');
                      $login        = db_getsession('DB_login');
                      db_input('s161_i_login', 10, $Is161_i_login, true, 'hidden', 3, '');
                      db_input('login', 40, $Ilogin, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=$Ts161_c_motivo?>">
                      <?=$Ls161_c_motivo?>
                    </td>
                    <td> 
                      <?
                      db_input('s161_c_motivo', 30, $Is161_c_motivo, true, 'text', 1, '');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td align="center">
              <input type="button" id="confirmar" value="Confirmar" onclick="js_anularReceita();">
              <input type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_anularreceita.hide();">
            </td>
          </tr>
        </table>

        <script>
        function js_ajax(oParam, jsRetorno, sUrl, lAsync) {
        
          var mRetornoAjax;
        
          if (sUrl == undefined) {
            sUrl = 'sau4_ambulatorial.RPC.php';
          }
        
          if (lAsync == undefined) {
            lAsync = true;
          }
          
          var oAjax = new Ajax.Request(sUrl, 
                                       {
                                         method: 'post', 
                                         asynchronous: lAsync,
                                         parameters: 'json='+Object.toJSON(oParam),
                                         onComplete: function(oAjax) {
                                            
                                                       var evlJS    = jsRetorno+'(oAjax);';
                                                       return mRetornoAjax = eval(evlJS);
                                                       
                                                   }
                                      }
                                     );
        
          return mRetornoAjax;
        
        }

        function js_anularReceita() {

          if ($F('s161_i_receita') == '') {

            alert('Informe a receita a ser anulada.');
            return false;

          }
          if ($F('s161_c_motivo') == '') {

            alert('Informe o motivo da anulação.');
            return false;

          }
          var oParam            = new Object();
          oParam.exec           = 'anularReceitaMedica';
          oParam.s161_i_receita = $F('s161_i_receita');
          oParam.s161_c_motivo  = $F('s161_c_motivo');
        
          js_ajax(oParam, 'js_retornoAnularReceita');
        
        }
        
        function js_retornoAnularReceita(oRetorno) {
        
          oRetorno = eval("("+oRetorno.responseText+")");
        
          alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
          if (oRetorno.iStatus == 1) {
            parent.js_nova();
          }
        
        }
        </script>
      </center>
    </td>
  </tr>
</table>
</center>
</body>
</html>