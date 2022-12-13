<?php
/**
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

$sInstit = db_getsession("DB_instit"); 

$sSql  = "SELECT                                                \n";
$sSql .= "      rh141_codigo AS codigo,                         \n";
$sSql .= "      rh144_rubrica AS rubrica,                       \n";
$sSql .= "      CASE WHEN rh27_pd = 1 THEN rh144_valor          \n";
$sSql .= "           ELSE 0                                     \n";
$sSql .= "      END AS provento,                                \n";
$sSql .= "      CASE WHEN rh27_pd = 2 THEN rh144_valor          \n";
$sSql .= "           ELSE 0                                     \n";
$sSql .= "      END AS desconto,                                \n";
$sSql .= "      rh144_quantidade AS quantidade,                 \n";
$sSql .= "      rh27_descr AS descricao,                        \n";
$sSql .= "      CASE WHEN rh27_pd = 1 THEN 'Provento'           \n";
$sSql .= "           WHEN rh27_pd = 2 THEN 'Desconto'           \n";
$sSql .= "           ELSE 'Base'                                \n";
$sSql .= "      END AS tiporubricadescricao,                    \n";
$sSql .= "      rh27_pd AS tiporubrica                          \n";
$sSql .= "    FROM rhhistoricoponto                             \n";
$sSql .= "    INNER JOIN rhrubricas                             \n";
$sSql .= "      ON rh27_rubric = rh144_rubrica                  \n";
$sSql .= "    INNER JOIN rhfolhapagamento                       \n";
$sSql .= "      ON rh144_folhapagamento = rh141_sequencial      \n";
$sSql .= "    WHERE rh144_regist    = {$matricula}              \n";
$sSql .= "      AND rh141_anousu    = {$ano}                    \n";
$sSql .= "      AND rh141_mesusu    = {$mes}                    \n";
$sSql .= "      AND rh141_tipofolha = 1                         \n";
$sSql .= "      AND rh27_instit     = {$sInstit}                \n";
$sSql .= "    ORDER BY codigo, tiporubrica, rubrica             \n";

$rsResultado = db_query($sSql);
$aResultado  = db_utils::getCollectionByRecord($rsResultado);

/**
 * Cria um array onde cada indice é uma complementar.
 * O valor guardado é um array com todos os objetos
 * trazidos do banco de dados.
 */
$aSuplementar = array();
foreach ($aResultado as $oResultado) {
  $aSuplementar[$oResultado->codigo][] = $oResultado;
}

?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="estilos.css">
  <script src="scripts/scripts.js"></script>
  <script>
    function js_mostracgm(cgm){
      parent.func_nome.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=' + cgm;
      parent.func_nome.mostraMsg();
      parent.func_nome.show();
      parent.func_nome.focus();
    }

    function js_mostrabic_matricula(matricula){
      parent.func_nome.jan.location.href = 'cad3_conscadastro_002.php?cod_matricula=' + matricula;
      parent.func_nome.mostraMsg();
      parent.func_nome.show();
      parent.func_nome.focus();
    }

    // esta funcao é utilizada quando clicar na inscricao após pesquisar
    // a mesma
    function js_mostrabic_inscricao(inscricao){
      parent.func_nome.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao=' + inscricao;
      parent.func_nome.mostraMsg();
      parent.func_nome.show();
      parent.func_nome.focus();
    }

    function js_relatorio(){
      jan = window.open('pes3_gerfinanc017.php?opcao=<?=$opcao?>&numcgm=' + document.form1.numcgm.value + '&matricula=' + document.form1.matricula.value + '&ano=<?=$ano?>&mes=<?=$mes?>&tbprev=<?=$tbprev?>','sdjklsdklsdf','width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
      jan.moveTo(0, 0);
    }

    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
      if (init == true) with (navigator) {
        if (appName == "Netscape" && parseInt(appVersion) == 4) {
          document.MM_pgW = innerWidth;
          document.MM_pgH = innerHeight;
          onresize        = MM_reloadPage;
        }
      } else if (innerWidth != document.MM_pgW || innerHeight != document.MM_pgH) {
          location.reload();
      }
    }

    MM_reloadPage(true);
  </script>
  <style>
    html, body, table {
        overflow: hidden;
    }

    .fonte {
      font-family:Arial, Helvetica, sans-serif;
      font-size:12px;
    }

    td {
      font-family:Arial, Helvetica, sans-serif;
      font-size:12px;

    }

    th {
      font-family:Arial, Helvetica, sans-serif;
      font-size:12px;

    }

    .gray {
      background-color: #eeeeee !important;
    }

    .super-gray {
      background-color: #dddddd !important;
    }

    #suplementar,
    #suplementar th,
    #suplementar td {
      border: 1px solid #bbbbbb;
      border-spacing: 0;
    }

    #suplementar tr {
      background-color: white;
    }

    #suplementar tr td {
      padding-left: 5px;
      padding-right: 5px;
    }

    .folha-suplementar {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
    }
  </style>
</head>
<body onload="js_altetaHeightPontoSuplementar();">
  <form name="form1" method="post">
    <?php if (is_array($aSuplementar) && count($aSuplementar) > 0) {?>
      <?php foreach (array_reverse($aSuplementar) as $iCodigo => $aResultado) { ?>
     
        <table id="suplementar" width="100%">
          <tr class="gray">
            <th width="80">Código</th>
            <th>Descrição</th>
            <th width="80">Quantidade</th>
            <th width="80">Proventos</th>
            <th width="80">Descontos</th>
            <th width="80">Prov/Desc</th>
          </tr>
          <?php foreach ($aResultado as $iChave => $oResultado) { ?>
            <?php if ($iChave % 2): ?>
              <tr class="gray">
            <?php else: ?>
              <tr>
            <?php endif; ?>
              <td align="center"><?= $oResultado->rubrica; ?></td>
              <td align="left"><?= $oResultado->descricao; ?></td>
              <td align="right"><?= db_formatar($oResultado->quantidade, 'f'); ?></td>
              <td align="right"><?= db_formatar($oResultado->provento, 'f'); ?></td>
              <td align="right"><?= db_formatar($oResultado->desconto, 'f'); ?></td>
              <td align="left"><?= $oResultado->tiporubricadescricao; ?></td>
            </tr>
          <?php } ?>
          <?php                                                
            $sSql  = "SELECT                                                 \n";
            $sSql .= "   sum(                                                \n";
            $sSql .= "     CASE WHEN rh27_pd = 1 THEN rh144_valor            \n";
            $sSql .= "          ELSE 0                                       \n";
            $sSql .= "     END                                               \n";
            $sSql .= "   ) AS provento,                                      \n";
            $sSql .= "   sum(                                                \n";
            $sSql .= "     CASE WHEN rh27_pd = 2 THEN rh144_valor            \n";
            $sSql .= "          ELSE 0                                       \n";
            $sSql .= "     END                                               \n";
            $sSql .= "   ) AS desconto                                       \n";
            $sSql .= " FROM rhhistoricoponto                                 \n";
            $sSql .= " INNER JOIN rhrubricas                                 \n";
            $sSql .= "   ON rh27_rubric = rh144_rubrica                      \n";
            $sSql .= " INNER JOIN rhfolhapagamento                           \n";
            $sSql .= "   ON rh144_folhapagamento = rh141_sequencial          \n";
            $sSql .= " WHERE rh144_regist    = {$matricula}                  \n";
            $sSql .= "   AND rh141_anousu    = {$ano}                        \n";
            $sSql .= "   AND rh141_mesusu    = {$mes}                        \n";
            $sSql .= "   AND rh141_codigo    = {$aResultado[0]->codigo}      \n";
            $sSql .= "   AND rh141_tipofolha = 1                             \n";
            $sSql .= "   AND rh27_instit     = {$sInstit}                    \n";
            
          $rsTotal = db_query($sSql);
          $oTotal  = db_utils::fieldsMemory($rsTotal, 0);
          ?>
          <tr class="super-gray">
            <td align="center" colspan="3">
              <strong>TOTAL<strong>
            </td>
            <td align="right">
              <strong><?= db_formatar($oTotal->provento, 'f'); ?></strong>
            </td>
            <td align="right">
              <strong><?= db_formatar($oTotal->desconto, 'f'); ?></strong>
            </td>
            <td align="left">
              <strong></strong>
            </td>
          </tr>
          <tr class="super-gray">
            <td align="center" colspan="3">
              <strong>LÍQUIDO<strong>
            </td>
            <td colspan="2" align="right">
              <strong><?= db_formatar($oTotal->provento - $oTotal->desconto, 'f'); ?></strong>
            </td>
            <td align="left">
              <strong></strong>
            </td>
          </tr>
        </table>
      <?php } ?>
    <?php } ?>
    <input type="hidden" name="matricula" value="<?= @$matricula; ?>">
    <input type="hidden" name="numcgm" value="<?= @$numcgm; ?>">
  </form>
  <script src="scripts/prototype.js"></script>
  <script src="scripts/widgets/DBToogle.widget.js"></script>
  <script>
  
    /**
     * Altera o Titulo da Folha.
     */
    parent.document.getElementById('tituloFolha').innerHTML = "<?= $sTituloCalculo; ?>";

    function js_altetaHeightPontoSuplementar() {
      var
        html = document.documentElement,
        iframe = parent.document.getElementById('debitos'),
        fieldset = parent.document.getElementById('calculoFolha');

      fieldset.style.height = html.scrollHeight + 7 + 'px';
      parent.iframeLoaded();
    }
  </script>
</body>
</html>
