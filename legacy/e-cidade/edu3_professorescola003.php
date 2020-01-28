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

$aCamposComuns = array('ed20_i_tiposervidor', 'ed20_i_codigo', 'ed18_i_codigo',
                       'ed75_d_ingresso', 'ed75_i_saidaescola', 'ed24_c_descr', 'ed25_c_descr');
$sCampos  = "  CASE                                                             ";
$sCampos .= "    WHEN ed20_i_tiposervidor = 1                                   ";
$sCampos .= "      THEN rechumanopessoal.ed284_i_rhpessoal                      ";
$sCampos .= "    ELSE rechumanocgm.ed285_i_cgm                                  ";
$sCampos .= "  END AS identificacao,                                            ";
$sCampos .= "  trim(ed18_c_nome) as escola,                                     ";
$sCampos .= "  array_to_string(array_accum(ed232_c_descr), ', ') as disciplina, ";
$sCampos .= "  (select array_to_string(array_accum(distinct trim(ed15_c_nome)), ', ')
                  from regenciahorario
                 inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo
                 inner join turno       on  turno.ed15_i_codigo = periodoescola.ed17_i_turno
                 inner join regencia    on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
                 inner join escola esc  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola
                 inner join turma       on  turma.ed57_i_codigo = regencia.ed59_i_turma
                 inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario
                 where regenciahorario.ed58_i_rechumano = ed20_i_codigo
                   and regenciahorario.ed58_ativo is true
                   and esc.ed18_i_codigo = ed18_i_codigo
                   and ed52_i_ano = ".date("Y");
$sCampos .= "  ) as turno,";

$sCampos .= implode(", ", $aCamposComuns);

$sGroupBy  = " group by identificacao, ed18_c_nome, ";
$sGroupBy .= implode(", ", $aCamposComuns);

$sOrdem              = "ed20_i_codigo, ed75_d_ingresso DESC ";
$oDaoRecHumanoEscola = new cl_rechumanoescola();
$sSqlEscola          = $oDaoRecHumanoEscola->sql_query_relacao_trabalho(null, $sCampos, $sOrdem, " {$where} {$sGroupBy}");
$rsEscola            = $oDaoRecHumanoEscola->sql_record($sSqlEscola);
$aDadosEscola        = db_utils::getCollectionByRecord($rsEscola);
?>
<tr>
  <td valign="top" >
    <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Escolas Atuais</b></legend>

      <table style="width: 100%" class="form-container"  >
      <?php
      if ( count($aDadosEscola) == 0 ) {
      ?>
        <tr>
            <td>
              Nenhum registro.
            </td>
          </tr>
      <?php
      } else {

        foreach($aDadosEscola as $oDadosEscola) {

          $iIdentificador = uniqid();
      ?>
        <tr style="border: 1px solid #000; height: 30px; padding: 15px;">
          <td >
            <table class="form-container">
              <tr  onclick="js_liberaDadosEscola('<?=$iIdentificador?>' );">
                <td width="10%">
                  <b><?=$oDadosEscola->ed20_i_tiposervidor == 1 ? "Matrícula": "CGM"?></b>: <?=$oDadosEscola->identificacao?>
                </td>
                <td width="35%"><?=$Led75_i_escola?> <?=$oDadosEscola->ed18_i_codigo?> - <?=$oDadosEscola->escola?></td>
                <td width="20%"><?=$Led75_d_ingresso?> <?=db_formatar($oDadosEscola->ed75_d_ingresso,'d')?> </td>
                <td width="20%"><?=$Led75_i_saidaescola?> <?=db_formatar($oDadosEscola->ed75_i_saidaescola,'d')?></td>
              </tr>

              <tr class=" esconde dadosEscola<?=$iIdentificador?>" style="display: none;">
                <td style="font-weight: normal; " class='field-size3' colspan="2">
                  <b class='field-size3'>Regime de Trabalho:</b> <?=$oDadosEscola->ed24_c_descr?></td>
                <td style="font-weight: normal;" ><b>Área de Trabalho:</b> <?=$oDadosEscola->ed25_c_descr?></td>
                <td style="font-weight: normal;" ><b>Turno:</b> <?=$oDadosEscola->turno?></td>
              </tr>
              <tr class="esconde dadosEscola<?=$iIdentificador?>" style="display: none;">
                <td style="font-weight: normal;" class='field-size3' colspan="4">
                  <b class='field-size3'>Disciplinas:</b> <?=$oDadosEscola->disciplina?></td>
              </tr>

            </table>
          </td>
      </tr>

      <?php
        }
      }
      ?>

    </fieldset>
  </td>
</tr>

<script type="text/javascript">

  function js_liberaDadosEscola (iIdentificador) {

    $$('.esconde').each( function(oElement) {
      oElement.style.display = 'none';
    });


    $$('.dadosEscola'+iIdentificador).each( function(oElement) {
      oElement.style.display = '';
    });
  }

</script>