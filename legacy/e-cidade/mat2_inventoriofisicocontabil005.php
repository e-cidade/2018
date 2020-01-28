<?php
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
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_materialestoquegrupoconta_classe.php");
$clmaterialestoquegrupoconta = new cl_materialestoquegrupoconta;
$clmaterialestoquegrupoconta->rotulo->label("m66_sequencial");
$clmaterialestoquegrupoconta->rotulo->label("m66_materialestoquegrupo");

$clArquivoAuxiliar = new cl_arquivo_auxiliar();

//               $clrotulocampo = new rotulocampo;
//               $clrotulocampo->label('codConta');
//               $clrotulocampo->label('descricao');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, prototype.js, strings.js, arrays.js");
db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <div style="margin-top: 35px;" ></div>
  <center>
    <div style="width: 500px;">
      <form name='form1' method='post'>
        <table align="center">
          <tr>
            <td>
            <?php
             
              $clArquivoAuxiliar->cabecalho      = '<strong>Contas Contábeis</strong>';
              $clArquivoAuxiliar->codigo         = 'c60_codcon'; 
              $clArquivoAuxiliar->descr          = "c60_descr";  
              $clArquivoAuxiliar->nomeobjeto     = 'contas';
              $clArquivoAuxiliar->funcao_js      = 'js_retornoContas';
              $clArquivoAuxiliar->funcao_js_hide = 'js_retornoContas1';
              $clArquivoAuxiliar->func_arquivo   = 'func_materialestoquegrupoconta2.php';
              $clArquivoAuxiliar->nomeiframe     = 'db_contas_iframe';
              $clArquivoAuxiliar->localjan       = '';
              $clArquivoAuxiliar->tipo           = 2;
              $clArquivoAuxiliar->linhas         = 5;
              $clArquivoAuxiliar->vwhidth        = 500;
              $clArquivoAuxiliar->obrigarselecao = false;
              $clArquivoAuxiliar->nome_botao     = 'db_lanca_conta';
              $clArquivoAuxiliar->funcao_gera_formulario();
        
            ?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </center>
  </body>
</html>
<cripts>

</cripts>