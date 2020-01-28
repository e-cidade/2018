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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_libpessoal.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

  $oGet = db_utils::postMemory($_GET);

  $oDaoRecHumano = new cl_rechumano();
  $rsRecHumano   = db_query( $oDaoRecHumano->sql_query_consulta_rechumano( $oGet->cgm) );

  $iRecHumano = 0;
  if ($rsRecHumano && pg_num_rows($rsRecHumano) > 0) {
    $iRecHumano = db_utils::fieldsMemory($rsRecHumano, 0)->rechumano;
  }


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
<link type="text/css" rel="stylesheet" href="estilos.css">
<link type="text/css" rel="stylesheet" href="estilos/grid.style.css">
<style>

.titulo {
  font-size: 11px;
  text-align: center;
  color: #DEB887;
  background-color:#444444;
  font-weight: bold;
  border: 1px solid #f3f3f3;
}

</style>
</head>
<body style=" background-color: #f3f3f3">

  <input type="hidden" value="<?php echo $iRecHumano ?>" id="iRecHumano">

  <fieldset style="border: 2px solid">
    <legend class='bold'>Movimentação do professor</legend>
    <table bgcolor="#f3f3f3" border='1' style="width: 100%" cellspacing="0" cellpading="0" id="tabelaMovimentacoes">
      <thead class="titulo">
        <th >
          Escola
        </th>
        <th>
          Usuário
        </th>
        <th>
          Data
        </th>
        <th>
          Hora
        </th>
        <th>
          Resumo
        </th>
      </thead>
      <tbody id="corpoMovimentacoes" >
      </tbody>
    </table>
  </fieldset>

</body>
</html>
<script>

/**
 * Mota o corpo da grid onde mostra os dados das movimentações do professor de acordo com os dados retornados pelo RPC
 * @param  Object oAjax
 */
function retornoBuscarMovimentacoes(oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');
  var sCorpo   = "";

  oRetorno.aMovimentacoes.each( function( oMovimentacao ) {

    sCorpo += "<tr>";
    sCorpo += "  <td>";
    sCorpo +=       oMovimentacao.ed118_escola.urlDecode();
    sCorpo += "  </td>";
    sCorpo += "  <td>";
    sCorpo +=       oMovimentacao.ed118_usuario.urlDecode();
    sCorpo += "  </td>";
    sCorpo += "  <td>";
    sCorpo +=       oMovimentacao.ed118_data;
    sCorpo += "  </td>";
    sCorpo += "  <td>";
    sCorpo +=       oMovimentacao.ed118_hora;
    sCorpo += "  </td>";
    sCorpo += "  <td>";
    sCorpo +=       oMovimentacao.ed118_resumo.urlDecode();
    sCorpo += "  </td>";
    sCorpo += "</tr>";
  });

  $('corpoMovimentacoes').update(sCorpo);
}

/**
 * Ao acessar a página, busca as movimentações que o regente tem cadastrado
 */
(function() {

  var oParametros        = new Object();
  oParametros.exec       = 'getMovimentacoesDoRegente';
  oParametros.iRecHumano = $F('iRecHumano');

  if ( parseInt(oParametros.iRecHumano) == 0 ) {
    return;
  }

  var oAjax = new Ajax.Request("edu4_regente.RPC.php",
                               { method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 asynchronous: false,
                                 onComplete: retornoBuscarMovimentacoes
                               }
                              );
})();

</script>