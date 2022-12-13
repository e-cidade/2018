<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DSeller Servicos de Informatica             
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

/**
 * Variáveis basicas.
 */
$iInstituicao = db_getsession("DB_instit");
$iMatricula   = $matricula;
$sBase        = $bases;

$iAnoFolha = $ano;
$iMesFolha = $mes;

/**
 * Busca registros financeiros nas
 * folhas salário e suplementar.
 */
$sSql = <<<SQL
  SELECT DISTINCT
      CASE WHEN rh141_codigo = 0           THEN 'Salario'
           ELSE rh141_codigo::varchar
      END              AS numero,
      rh143_rubrica    AS rubrica,
      CASE WHEN rh143_tipoevento  =  1     THEN 'Provento'
           WHEN rh143_tipoevento  =  2     THEN 'Desconto'
           ELSE 'Base'
      END              AS tipo,
      CASE WHEN rh143_tipoevento IN (1, 3) THEN rh143_valor
           ELSE  0
      END              AS provento,
      CASE WHEN rh143_tipoevento  =  2     THEN rh143_valor
           ELSE  0
      END              AS desconto,
      rh143_quantidade AS quantidade,
      rh27_descr       AS descricao,
      EXISTS (
        SELECT
            1
          FROM rhrubricas
          WHERE rh27_instit = rh141_instit
            AND rh27_rubric = rh143_rubrica
            AND (rh27_form  LIKE '%{$sBase}%'
              OR rh27_form2 LIKE '%{$sBase}%'
              OR rh27_form3 LIKE '%{$sBase}%'
            )
      )                AS formula,
      EXISTS (
        SELECT
            1
          FROM rhbasesreg
          WHERE rh54_base   = '{$sBase}'
            AND rh54_regist = '{$iMatricula}'
            AND rh54_rubric =  rh143_rubrica
        UNION
        SELECT
            1
          FROM basesr
          WHERE r09_anousu  =  rh141_anousu
            AND r09_mesusu  =  rh141_mesusu
            AND r09_instit  =  rh141_instit
            AND r09_rubric  =  rh143_rubrica
            AND r09_base    = '{$sBase}'
      )                AS compoe
    FROM rhfolhapagamento
    INNER JOIN rhhistoricocalculo
      ON  rh141_sequencial   = rh143_folhapagamento
    INNER JOIN rhrubricas
      ON  rh143_rubrica = rh27_rubric
      AND rh141_instit  = rh27_instit
    WHERE rh141_anousu      = {$iAnoFolha}
      AND rh141_mesusu      = {$iMesFolha}
      AND rh141_instit      = {$iInstituicao}
      AND rh141_tipofolha  IN (1, 6)
      AND rh143_regist      = {$iMatricula}
      AND rh143_tipoevento != 3
    ORDER BY numero DESC, rubrica ASC
SQL;

$rsRegistroFinanceiro = db_query($sSql);
$aRegistroFinanceiro  = db_utils::getCollectionByRecord($rsRegistroFinanceiro);

/**
 * Monta array de registros financeiros
 * colocando o código da folha como
 * indice do array.
 */
foreach ($aRegistroFinanceiro as $oRegistroFinanceiro) {
  $aFolhaPagamento[$oRegistroFinanceiro->numero][] = $oRegistroFinanceiro;
}

/**
 * Busca bases dos registros
 * financeiros.
 */
$sSql = <<<SQL
  SELECT DISTINCT
      'B'              AS numero,
      r14_rubric       AS rubrica,
      CASE WHEN r14_pd  =  1     THEN 'Provento'
           WHEN r14_pd  =  2     THEN 'Desconto'
           ELSE 'Base'
      END              AS tipo,
      CASE WHEN r14_pd IN (1, 3) THEN r14_valor
           ELSE  0
      END              AS provento,
      CASE WHEN r14_pd  =  2     THEN r14_valor
           ELSE  0
      END              AS desconto,
      r14_quant        AS quantidade,
      rh27_descr       AS descricao,
      EXISTS (
        SELECT
            1
          FROM rhrubricas
          WHERE rh27_instit = r14_instit
            AND rh27_rubric = r14_rubric
            AND (rh27_form  LIKE '%{$sBase}%'
              OR rh27_form2 LIKE '%{$sBase}%'
              OR rh27_form3 LIKE '%{$sBase}%'
            )
      )                AS formula,
      EXISTS (
        SELECT
            1
          FROM rhbasesreg
          WHERE rh54_base   = '{$sBase}'
            AND rh54_regist = '{$iMatricula}'
            AND rh54_rubric =  r14_rubric
        UNION
        SELECT
            1
          FROM basesr
          WHERE r09_anousu  =  r14_anousu
            AND r09_mesusu  =  r14_mesusu
            AND r09_instit  =  r14_instit
            AND r09_rubric  =  r14_rubric
            AND r09_base    = '{$sBase}'
      )                AS compoe
    FROM gerfsal
    INNER JOIN rhrubricas
      ON  r14_rubric        = rh27_rubric
      AND r14_instit        = rh27_instit
    WHERE r14_anousu        = {$iAnoFolha}
      AND r14_mesusu        = {$iMesFolha}
      AND r14_instit        = {$iInstituicao}
      AND r14_regist        = {$iMatricula}
      AND r14_pd            = 3
    ORDER BY r14_rubric
SQL;

$rsRegistroFinanceiro = db_query($sSql);

/**
 * Adiciona bases ao array de folhas de
 * pagamento.
 */
$aFolhaPagamento['B'] = db_utils::getCollectionByRecord($rsRegistroFinanceiro);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="ISO-8859-1">
  <title>Consulta Financiera</title>
  <link rel="stylesheet" href="estilos.css">
  <style type="text/css">
    html, body, table {
      overflow: hidden;
    }

    #tabela-calculos,
    #tabela-calculos tr,
    #tabela-calculos td,
    #tabela-calculos th{
      border: 1px solid #bbb;
    }
   
    #tabela-calculos a { 
    }


    #tabela-calculos tr:nth-child(odd) {
      background-color: #EEEEEE;
    }

    #tabela-calculos tr:nth-child(even) {
      background-color: #FFFFFF;
    }

    #tabela-calculos tr:first-child {
      border-right:1px outset #D3D3D3;  
      padding:0;
      margin:0;
      overflow: hidden;
    }

    #tabela-calculos tr td {
      text-align: left;
      padding-left: 5px;
      padding-right: 5px;
    }

    #tabela-calculos tr.totais td[colspan='4'] { 
      text-align: center;
    }

    #tabela-calculos tr td:nth-child(2),
    #tabela-calculos tr td:nth-child(4),
    #tabela-calculos tr td:nth-child(5),
    #tabela-calculos tr td:nth-child(6) {
      text-align: right;
    }

    #tabela-calculos tr td:nth-child(2) {
      font-weight:bold; 
      padding-left: 0px;
      padding-right: 14px;
    }
      
    #tabela-calculos tr td:nth-child(1) {
      text-align: center;
    }
  
    #tabela-calculos tr.totais {
      background-color: #DDDDDD;
      text-align: right;
    }

    #tabela-calculos tr.totais td  {
      font-weight:bold;
      text-align: right;
      padding-right: 5px;
    }
  </style>
</head>
<body>
  <form action="" method="post" name="form1">
    <?php foreach ($aFolhaPagamento as $mNumero => $aRegistroFinanceiro) { ?>
      <fieldset id="folha-<?= $mNumero ?>">
        <?php if ($mNumero === 'Salario') { ?>
          <legend>Salário</legend>
        <?php } elseif ($mNumero === 'B') { ?>
          <legend>Bases</legend>
        <?php } else { ?>
          <legend>Suplementar nº <?= $mNumero ?></legend>
        <?php } ?>
        <table width="100%" id="tabela-calculos" cellspacing="0">
          <tr>
            <th width="25">Fórmula(*)</th>
            <th width="70">Rubrica</th>
            <th width="">Descrição</th>
            <th width="80">Quantidade</th>
            <th width="80">Provento</th>
            <th width="80">Desconto</th>
            <th width="90">Tipo</th>
          </tr>
          <?php
          $nTotalProventos = 0;
          $nTotalDescontos = 0;
          ?>
          <?php foreach ($aRegistroFinanceiro as $oRegistroFinanceiro) { ?>
            <tr>
              <td>1</td>
              <td>
                <?php if ($oRegistroFinanceiro->formula === 't') { ?>
                  B
                <?php } ?>
                <?php if ($oRegistroFinanceiro->compoe  === 't') { ?>
                  #
                <?php } ?>
                <a href="#" id="<?= $oRegistroFinanceiro->rubrica ?>"><?= $oRegistroFinanceiro->rubrica ?></a>
              </td>
              <td><?= $oRegistroFinanceiro->descricao ?></td>
              <td><?= db_formatar($oRegistroFinanceiro->quantidade, 'f') ?></td>
              <td><?= db_formatar($oRegistroFinanceiro->provento, 'f') ?></td>
              <td><?= db_formatar($oRegistroFinanceiro->desconto, 'f') ?></td>
              <td><?= $oRegistroFinanceiro->tipo ?></td>
            </tr>

            <?php
            $nTotalProventos += $oRegistroFinanceiro->provento;
            $nTotalDescontos += $oRegistroFinanceiro->desconto;
            ?>
          <?php } ?>

          <?php if ($mNumero !== 'B') { ?>
            <tr class="totais">
              <td colspan="4">TOTAL</td>
              <td><?= db_formatar($nTotalProventos, 'f') ?></td>
              <td><?= db_formatar($nTotalDescontos, 'f') ?></td>
              <td></td>
            </tr>
            <tr class="totais">
              <td colspan="4">LÍQUIDO</td>
              <td colspan="2"><?= db_formatar($nTotalProventos - $nTotalDescontos, 'f') ?></td>
              <td></td>
            </tr>
          <?php } ?>
        </table>
      </fieldset>
    <?php } ?>
  </form>
  <script src="scripts/scripts.js"></script>
  <script src="scripts/prototype.js"></script>
  <script src="scripts/strings.js"></script>
  <script src="scripts/widgets/DBToogle.widget.js"></script>
  <script>
    $$('fieldset').each(function(oElemento, iIndice) {

      if (iIndice < 2 || iIndice == $$('fieldset').length - 1) {
        var oToggle = new DBToogle(oElemento.id);
      } else {
        var oToggle = new DBToogle(oElemento.id, false);
      }

      oToggle.afterClick = function() {
         js_alteraHeightSalario();
      };
    });

    var sOpcao = js_urlToObject().opcao;

    parent.document.formatu.opcao.value                     = "<?= $opcao; ?>";
    parent.document.getElementById('tituloFolha').innerHTML = 'Salário / Suplementar';
  
    function js_alteraHeightSalario() {

      var
        html = document.documentElement,
        fieldset = parent.document.getElementById('calculoFolha');

      fieldset.style.height = html.offsetHeight + 7 + 'px';
      parent.iframeLoaded(10);
    }

    function js_relatorio() {

      var parametros = [
        'opcao=<?= $opcao ?>',
        'numcgm=<?= $numcgm ?>',
        'matricula=<?= $matricula ?>',
        'ano=<?= $ano ?>',
        'mes=<?= $mes ?>',
        'tbprev=<?= $tbprev ?>'
      ];

      jan = window.open(
        'pes3_gerfinanc017.php?' + parametros.join('&'),
        'sdjklsdklsdf',
        'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0'
      );

      jan.moveTo(0,0);      
    }
  
    function js_Pesquisarubrica(rubrica) {
      
      var janela = js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_pesquisarubrica',
        'pes1_rhrubricas006.php?tela_pesquisa=true&chavepesquisa=' + rubrica,
        'Pesquisa',
        true,
        '20'
      );

      janela.moldura.style.zIndex = 9999;
    }

    var aLinks = document.querySelectorAll("#tabela-calculos tr td:nth-child(2) a");

    for( var iLink = 0; iLink < aLinks.length; iLink++ ) {
      aLinks[iLink].setAttribute("onClick", "js_Pesquisarubrica(this.id)");
    }

    js_alteraHeightSalario();
  </script>
</body>
</html>