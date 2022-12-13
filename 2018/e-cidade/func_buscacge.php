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

$sSql  = "SELECT ed47_i_codigo, ed47_v_nome, ed47_v_cpf FROM aluno";
$where = array();

if(isset($_POST['nome']) && !empty($_POST['nome'])) {

  $nomePesquisa = $_POST['nome'];
  $where[]      = "ed47_v_nome ILIKE '{$nomePesquisa}%'";
}

if(isset($_POST['cpf']) && !empty($_POST['cpf'])) {

  $cpfPesquisa = $_POST['cpf'];
  $where[]     = "ed47_v_cpf ILIKE '{$cpfPesquisa}%'";
}

if(count($where)) {

  $sSql .= ' WHERE ';
  $sSql .= implode(' AND ', $where);
}

$sSql     .= " ORDER BY ed47_v_nome";
$funcao_js = $_GET['funcao_js'];
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <style>
    .tabela a { text-decoration:none; color:#000000; }
  </style>
</head>
<body class="body-default">

  <div class="container">
    <form name="form2" method="post" action="">
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td nowrap>
              <label for="nome">Nome:</label>
            </td>
            <td nowrap>
              <input id="nome" type="text" name="nome" value="<?=isset($_POST['nome']) ? $_POST['nome'] : ''?>">
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label for="cpf">CPF:</label>
            </td>
            <td nowrap>
              <input id="cpf" type="text" name="cpf" value="<?=isset($_POST['cpf']) ? $_POST['cpf'] : ''?>">
            </td>
          </tr>

        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="reset"  id="limpar"     value="Limpar">
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_buscacgm.hide();">
    </form>

  </div>

  <div class="container">
    <?php db_lovrot($sSql, 15, '()', '',$funcao_js, '', 'NoMe', array()); ?>
  </div>
</body>
</html>
<script>

</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
