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

require_once (modification("libs/db_stdlibwebseller.php"));
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_formaavaliacao_classe.php"));
require_once (modification("model/educacao/ArredondamentoNota.model.php"));
require_once (modification("classes/db_conceito_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));

parse_str($_SERVER["QUERY_STRING"]);
$clformaavaliacao = new cl_formaavaliacao;
$clconceito       = new cl_conceito;
if (isset($codigo)) {

  $sWhere  = " ed37_i_codigo = {$codigo} and ed37_i_escola is null";
  $sClasse = 'ArredondamentoNotaSecretaria';

  if ( db_getsession('DB_modulo') == 1100747 ) {

    $sClasse = 'ArredondamentoNota';
    $sWhere  = " ed37_i_codigo = {$codigo} and ed37_i_escola is not null";
  }
  $result = $clformaavaliacao->sql_record($clformaavaliacao->sql_formaavaliacao(null, "*", "ed39_i_sequencia", $sWhere));
  if ( !$result) {
    return;
  }

  db_fieldsmemory($result,0);
  if ($ed37_c_tipo=="NOTA") {


    $descricao = "Notas de ". $sClasse::formatar($ed37_i_menorvalor, db_getsession("DB_anousu"))." até ".
                  $sClasse::formatar($ed37_i_maiorvalor, db_getsession("DB_anousu"))." <br>
                  com variação de ".$sClasse::formatar($ed37_i_variacao, db_getsession("DB_anousu")).".<br>
                  Mínimo para aprovação: ".$sClasse::formatar($ed37_c_minimoaprov, db_getsession("DB_anousu"));
  } elseif ($ed37_c_tipo == "NIVEL") {

    $aConceitos = array();
    for($x=0;$x<$clformaavaliacao->numrows;$x++){

      db_fieldsmemory($result,$x);
      $aConceitos[] = $ed39_c_conceito;
    }
    $descricao = "Níveis: " . implode(', ', $aConceitos) . "<br> Mínimo para aprovação: {$ed37_c_minimoaprov}";
  } else {
    $descricao = $ed37_c_parecerarmaz=="S"?"Parecer será armazenado":"Parecer não será armazenado";
  }
  ?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%">
   <tr>
     <td>
     <b>Forma de Avaliação:</b><br>
     <?=$descricao;?>
    </td>
   </tr>
  </table>
  </body>
  </html>
<?}?>