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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$oGet = db_utils::postMemory($_GET);

$aCampos  = " bi06_titulo as titulo,  ";
$aCampos .= " bi06_subtitulo as subtitulo, ";
$aCampos .= " bi06_titulooriginal as titulo_original, ";
$aCampos .= " bi06_dataregistro as data_registrom, ";
$aCampos .= " bi06_classcdd as cdd, ";
$aCampos .= " bi02_nome as editora, ";
$aCampos .= " bi06_edicao as edicao, ";
$aCampos .= " bi06_anoedicao as ano_edicao,  ";
$aCampos .= " bi06_isbn as isbn, ";
$aCampos .= " bi05_nome as tipo_item, ";
$aCampos .= " bi06_volume as volume, ";
$aCampos .= " bi03_classificacao as classificacao_literaria, ";
$aCampos .= " bi29_nome as colecao, ";
$aCampos .= " bi22_idioma as idioma";

$oDaoAcervo = new cl_acervo;
$sSqlAcervo = $oDaoAcervo->sql_query( null, $aCampos, null, "bi06_seq = {$oGet->iAcervo}" );
$rsAcervo   = db_query($sSqlAcervo);

if ( !$rsAcervo || pg_num_rows($rsAcervo) == 0) {

  $sMsg = urlencode('Erro ao buscar dados do Acervo.');
  db_redireciona('db_erros.php?fechar=sim&pagina_retorno=bib3_acervo001.php&db_erro=' . $sMsg);
}

db_fieldsmemory( $rsAcervo, 0 );

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <style type="text/css">
    .valores {
      background-color: #FFFFFF;
      padding: 3px;
      padding-left: 5px;
      padding-right: 5px;
      min-width: 100px !important;
    }
    .dados-gerais tr td:nth-child(odd) {
      font-weight: bold;
    }

  </style>
</head>
<body class="body-default">

  <div class='subcontainer'  style="width: 99%;">

    <fieldset>
      <legend>Consulta de Acervo</legend>
      <table class='dados-gerais'>
        <tr>
          <td>Título:</td>
          <td class="valores" colspan="2"><?=$titulo?></td>
          <td>Subtítulo:</td>
          <td class="valores" colspan="2"><?=$subtitulo?></td>
        </tr>
        <tr>
          <td>Título Original:</td>
          <td class="valores"><?=$titulo_original?></td>
          <td>Data de Registro:</td>
          <td class="valores"><?php echo db_formatar( $data_registrom, 'd' );?></td>
          <td>Clas. CDD:</td>
          <td class="valores" ><?=$cdd?></td>
        </tr>
        <tr>
          <td>Editora:</td>
          <td class="valores"><?=$editora?></td>
          <td>Edição:</td>
          <td class="valores"><?=$edicao?></td>
          <td>Ano de Edição:</td>
          <td class="valores"><?=$ano_edicao?></td>
        </tr>
        <tr>
          <td>ISBN:</td>
          <td class="valores"><?=$isbn?></td>
          <td>Tipo Item:</td>
          <td class="valores"><?=$tipo_item?></td>
          <td>Volume:</td>
          <td class="valores"><?=$volume?></td>
        </tr>
        <tr>
          <td>Clas. Literária:</td>
          <td class="valores"><?=$classificacao_literaria?></td>
          <td>Coleção:</td>
          <td class="valores"><?=$colecao?></td>
          <td>Idioma:</td>
          <td class="valores"><?=$idioma?></td>
        </tr>
      </table>
    </fieldset>
  </div>
  <!-- Container das abas  -->
  <div class="subcontainer" style="width: 99%;">
    <fieldset>
      <legend>Opções</legend>
      <?php
        $oTabDetalhes = new verticalTab("consultaAcervo", 500, 15);
        $oTabDetalhes->add("abaAutores",     "Autores",    "edu3_autores.php?iAcervo={$oGet->iAcervo}");
        $oTabDetalhes->add("abaExemplares",  "Exemplares", "edu3_exemplares.php?iAcervo={$oGet->iAcervo}");
        $oTabDetalhes->add("abaEmprestimos", "Empréstimos Abertos", "edu3_emprestimosaberto.php?iAcervo={$oGet->iAcervo}");
        $oTabDetalhes->show();
      ?>
    </fieldset>
  </div>