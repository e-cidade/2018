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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

if ( !empty($oid) ) {
  // Inicia transação (Obrigatório)
  db_query("begin");

  // Abrindo o objeto no modo leitura "r" passando como parâmetro o OID.
  $objetoleitura = pg_lo_open($oid,"r");

  // Setando Cabeçalho do browser para interpretar que o binário que será carregado é de uma foto do tipo JPEG.
  header("Content-Type: image/jpeg");

  // Lendo binário da foto.
  $mostrar = pg_lo_read_all($objetoleitura);

  // Fechando Objeto que foi aberto para leitura
  pg_lo_close($objetoleitura);

  // Finaliza transação
  db_query("commit");
}
?>
<script type="text/javascript">
(function() {
  
  var query = frameElement.getAttribute('name').replace('IF', ''),;
  var input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
