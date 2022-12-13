<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($HTTP_POST_VARS);

$db_opcao   = 1;
$clrhferias = new cl_rhferias;
$clrhferias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh109_regist");
$clrotulo->label("r44_selec");
$clrotulo->label("z01_nome");
$clrotulo->label("r44_descr");

if(isset($processar)) {

  try {
    $lStatus = true;

    if (!isset($rh109_regist) && !isset($r44_selec) && !isset($lAtivos) ) {
      throw new BusinessException("Informe ao menos uma forma para gerar os períodos aquisitivos.");
    }

    $aMatriculas = array();

    if( (isset($lAtivos) && $lAtivos == true) || (isset($r44_selec) && !empty($r44_selec)) ) {

      $sSlqMatriculas = "select distinct rh01_regist as matricula
                           from rhpessoal 
                                inner join cgm                  on cgm.z01_numcgm                        = rhpessoal.rh01_numcgm 
                                inner join rhpessoalmov         on rhpessoalmov.rh02_regist              = rhpessoal.rh01_regist 
                                                               and rhpessoalmov.rh02_anousu              = fc_anofolha(rhpessoalmov.rh02_instit) 
                                                               and rhpessoalmov.rh02_mesusu              = fc_mesfolha(rhpessoalmov.rh02_instit) 
                                left join rhlota                on rhlota.r70_codigo                     = rhpessoalmov.rh02_lota 
                                                               and rhlota.r70_instit                     = rhpessoalmov.rh02_instit 
                                left join rhregime              on rh30_codreg                           = rh02_codreg 
                                left join rhfuncao              on rhfuncao.rh37_funcao                  = rhpessoalmov.rh02_funcao 
                                                               and rhfuncao.rh37_instit                  = rhpessoalmov.rh02_instit 
                                left join rhpesrescisao         on rh02_seqpes                           = rh05_seqpes 
                                left join rhcontratoemergencial on rhcontratoemergencial.rh163_matricula = rhpessoal.rh01_regist 
                          where rh01_instit = ". db_getsession("DB_instit") ."
                            and rh05_seqpes is null";

      if (isset($r44_selec) && !empty($r44_selec)) {
        $oDaoSelecao = new cl_selecao;
        $rsSelecao   = db_query($oDaoSelecao->sql_query($r44_selec, db_getsession("DB_instit"), "r44_where"));

        if(!$rsSelecao) {
          throw new BusinessException("Não foi possível recuperar a seleção informada.");
        }

        $sWhereSelecao = db_utils::fieldsMemory($rsSelecao, 0)->r44_where;
        $sSlqMatriculas .= " and ". $sWhereSelecao;
      }

      $rsMatriculas = db_query($sSlqMatriculas);

      if(!$rsMatriculas) {
        throw new BusinessException("Não foi possível recuperar as matrículas para gerar o período aquisitivo.");
      }

      for ($iIndMatriculas=0; $iIndMatriculas < pg_num_rows($rsMatriculas); $iIndMatriculas++) { 
        $aMatriculas[] = db_utils::fieldsMemory($rsMatriculas, $iIndMatriculas)->matricula;
      }
    } else if (isset($rh109_regist) && !empty($rh109_regist) ) {

      $aMatriculas = array($rh109_regist);
    }

    foreach ($aMatriculas as $iMatricula) {

      $sSqlNovoPeriodoAquisitivo   = "insert into rhferias
                                      select nextval('rhferias_rh109_sequencial_seq'),
                                             periodosaquisitivos.rh109_regist,
                                             periodosaquisitivos.rh109_periodoaquisitivoinicial,
                                             periodosaquisitivos.rh109_periodoaquisitivofinal,
                                             periodosaquisitivos.rh109_diasdireito,
                                             periodosaquisitivos.rh109_faltasperiodoaquisitivo,
                                             periodosaquisitivos.rh109_observacao
                                      from (select distinct 
                                                   rh109_sequencial,
                                                   rh01_regist                                 as rh109_regist,
                                                   (rh109_periodoaquisitivofinal+1)::date  as rh109_periodoaquisitivoinicial,
                                                   (rh109_periodoaquisitivofinal+365)::date  as rh109_periodoaquisitivofinal,
                                                   rh02_diasgozoferias                         as rh109_diasdireito,
                                                   0                                           as rh109_faltasperiodoaquisitivo,
                                                   'Período gerado automaticamente'            as rh109_observacao
                                              from rhpessoal 
                                                   inner join rhferias             on rhferias.rh109_regist    = rhpessoal.rh01_regist
                                                   inner join cgm                  on cgm.z01_numcgm           = rhpessoal.rh01_numcgm 
                                                   inner join rhpessoalmov         on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist 
                                                                                  and rhpessoalmov.rh02_anousu = fc_anofolha(rhpessoalmov.rh02_instit) 
                                                                                  and rhpessoalmov.rh02_mesusu = fc_mesfolha(rhpessoalmov.rh02_instit) 
                                             where rh01_instit = ". db_getsession("DB_instit") ."
                                               and rh01_regist = {$iMatricula}
                                            order by 1 desc
                                            limit 1
                                            ) as periodosaquisitivos";

      $rsNovoPeriodoAquisitivo = db_query($sSqlNovoPeriodoAquisitivo);

      if(pg_affected_rows($rsNovoPeriodoAquisitivo) == 0) {
        throw new BusinessException("Não foi possível criar o período aquisitivo.");
      }
    }

    $sMensagem = 'Período aquisitivo criado com sucesso.';

  } catch (Exception $e) {
    $lStatus   = true;
    $sMensagem = $e->getMessage();
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, AjaxRequest.js, DBLookUp.widget.js, estilos.css");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

    <form id="form1" name="form1" action="" method="POST" onsubmit="return js_validacampos()" class="container">  
      <fieldset>
        <legend>Gerar Períodos Aquisitivos</legend>
        <table class="container-form">

          <tr>
            <td nowrap title="<?php echo $Trh109_regist; ?>">
              <label><a href="" id="lbl_rh109_regist"><?php echo $Lrh109_regist; ?></a></label>
            </td>
            <td>
              <?php 
                db_input('rh109_regist', 10, $Irh109_regist, true, "text", $db_opcao, 'onchange="js_verificainput()" data="rh01_regist"');
                db_input('z01_nome', 10, $Iz01_nome, true, "text", 3);
              ?>
            </td>
          </tr>
          
          <tr>
            <td nowrap title="<?php echo $Tr44_selec; ?>">
              <label><a href="" id="lbl_r44_selec"><?php echo $Lr44_selec; ?></a></label>
            </td>
            <td>
              <?php 
                db_input('r44_selec', 10, $Ir44_selec, true, "text", $db_opcao, 'onchange="js_verificainput()"');
                db_input('r44_descr', 10, $Ir44_descr, true, "text", 3);
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="Marque para gerar períodos aquisitivos par todos os servidores">
              <label id="lbl_lAtivos" for="lAtivos"><strong>Todos:</strong></label>
            </td>
            <td>
              <?php
                $lAtivos = true;
                db_input('lAtivos', 10, null, true, "checkbox", $db_opcao, 'onclick="js_verificacheckbox()"');
              ?>
            </td>
          </tr>

        </table>
      </fieldset>
      <input type="submit" id="processar" name="processar" value="Processar" />
    </form>

    <script type="text/javascript">

      (function(){
      
        /**
         * Criação da Ancora para a matrícula.
         * @type  {DBLookUp}
         */
        var oMatricula = new DBLookUp($('lbl_rh109_regist'), $('rh109_regist'), $('z01_nome'), {
            'sArquivo'               : 'func_rhpessoal.php',
            'sObjetoLookUp'          : 'db_iframe_rhpessoal',
            'sLabel'                 : 'Pesquisar Matrícula',
            'aParametrosAdicionais'  : ['sAtivos=true']
        });

        /**
         * Criação da Ancora para a seleção.
         * @type  {DBLookUp}
         */
        var oSelecao = new DBLookUp($('lbl_r44_selec'), $('r44_selec'), $('r44_descr'), {
            'sArquivo'     : 'func_selecao.php',
            'sObjetoLookUp': 'db_iframe_selecao',
            'sLabel'       : 'Pesquisar Seleção'
        });

        /**
         * Sobrescrevendo método para pegar retornos da pesquisa
         */
        oMatricula.setCallBack('onChange', function(){
          js_verificainput();
        });
        oMatricula.setCallBack('onClick', function(){
          js_verificainput();
        });

        /**
         * Sobrescrevendo método para pegar retornos da pesquisa
         */
        oSelecao.setCallBack('onChange', function(){
          js_verificainput();
        });
        oSelecao.setCallBack('onClick', function(){
          js_verificainput();
        });
      })();

      function js_verificacheckbox() {

        var oInputMatricula, oInputSelecao, oCheckTodos;
        oInputMatricula = document.form1.rh109_regist;
        oInputSelecao   = document.form1.r44_selec;
        oCheckTodos     = document.form1.lAtivos;

        if(oCheckTodos.checked) {
          oInputMatricula.value = '';
          oInputSelecao.value   = '';
        }
      }

      function js_verificainput() {

        var oInputMatricula, oInputSelecao, oCheckTodos;
        oInputMatricula = document.form1.rh109_regist;
        oInputSelecao   = document.form1.r44_selec;
        oCheckTodos     = document.form1.lAtivos;

        if(oInputMatricula.value.trim() != '' || oInputSelecao.value.trim() != '') {
          oCheckTodos.checked = false;
        }
      }

      function js_validacampos () {

        if (document.form1.rh109_regist.value == "" && document.form1.r44_selec.value == "" && document.form1.lAtivos.checked == false) {
          alert("Informe ao menos uma forma para gerar os períodos aquisitivos.");
          return false;
        }

        if(!confirm('Tem certeza que deseja criar novos períodos aquisitivos?')) {
          return false;
        }
      }

    </script>

    <?php 
      if(isset($processar) && isset($lStatus) && $lStatus) {
        if(isset($sMensagem) && trim($sMensagem) != '') {
          db_msgbox($sMensagem);
        }
        db_redireciona('');
      }
    ?>

    <?php db_menu() ?>
  </body>
</html>