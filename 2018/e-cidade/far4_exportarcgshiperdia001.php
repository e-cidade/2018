<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("classes/db_unidades_classe.php");

function formataData($dData, $iTipo = 1) {

  if(empty($dData)) {
    return '';
  }

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}



db_postmemory($HTTP_POST_VARS);

$oDaoFarArquivoHiperdia  = db_utils::getdao('far_arquivohiperdia');
$oDaoFarAcompPacHiperdia = db_utils::getdao('far_cadacomppachiperdia');
$oRotulo                 = new rotulocampo();

$oRotulo->label("fa56_d_dataini");
$oRotulo->label("fa56_d_datafim");

$db_opcao     = 1;
$db_botao     = true;
$iLogin       = db_getsession('DB_id_usuario');
$dHoje        = date("Y-m-d", db_getsession("DB_datausu"));
$desabilita   = "";
$departamento = db_getsession("DB_coddepto");


?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="100%" border="0" cellpadding="0" cellspacing="0"
    bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"><br>
        <center>
        <form name="form1" method="post" action="">
          <center>
          <fieldset style="width: 70%"><legend><b>Gerador de Arquivo SIA</b></legend>
            <table border="0" align="left">
              <tr>
                <td>
                  <?db_ancora(@$Lfa56_d_dataini,"",3);?>
                </td>
                <td>
                  <?db_inputdata('fa56_d_dataini',@$fa56_d_dataini_dia,@$fa56_d_dataini_mes,@$fa56_d_dataini_ano,true,'text',1,"")?>
                </td>
              </tr>
              <tr>
                <td>
                  <?db_ancora(@$Lfa56_d_datafim,"",3);?>
                </td>
                <td>
                  <?db_inputdata('fa56_d_datafim',@$fa56_d_datafim_dia,@$fa56_d_datafim_mes,@$fa56_d_datafim_ano,true,'text',1,"")?>
                </td>
              </tr> 
              <tr>
                <td><b>Executando:</b></td>
                <td>
                  <table>
                    <tr>
                      <td>
                        <?=db_criatermometro('termometro', 'Concluido...', 'blue', 1);?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </fieldset>
          </center>
          <table border="0">
            <tr>
              <td height="18">&nbsp;</td>
              <td height="18">&nbsp;</td>
            </tr>
            <tr>
              <td>
                <input name="gerararquivo" type="submit" id="arquivo" value="Gerar" onclick="return js_validar()">
              </td>
            </tr>
          </table>
        </form>
        </center>
      </td>
    </tr>
  </table>
<?
db_menu(db_getsession("DB_id_usuario"), 
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit") 
       );
?>
  </body>
</html>
<script>
  function js_validar(){

    oF = document.form1;
    if (oF.fa56_d_dataini.value == '' || oF.fa56_d_datafim.value == '') {

      alert('Entre com uma data!');
      return false;

    }
    aDini = oF.fa56_d_dataini.value.split('/'); 
    aDfim = oF.fa56_d_datafim.value.split('/');
    if (aDini.reverse().join('') > aDfim.reverse().join('')) {

      alert('Data Inicial menor que a data Final!');
      return false;

    }

    return true;

  }
</script>
<?
if (isset($gerararquivo)) {

  $dDataIni = formataData($fa56_d_dataini);
  $dDataFim = formataData($fa56_d_datafim);

  /* Sub selects utilizados na busca de dados dos cadastros e acompanhamentos de pacientes */
  $sSubFatorRisco    = 'select s106_i_codigo ';
  $sSubFatorRisco   .= '  from cgsfatorderisco ';
  $sSubFatorRisco   .= '    inner join sau_fatorderisco  on  sau_fatorderisco.s105_i_codigo = ';
  $sSubFatorRisco   .= '      cgsfatorderisco.s106_i_fatorderisco ';
  $sSubFatorRisco   .= '    inner join far_fatorriscofarmaciaambulatorial  on  ';
  $sSubFatorRisco   .= '      far_fatorriscofarmaciaambulatorial.fa45_i_fatorriscoambulatorial = ';
  $sSubFatorRisco   .= '      sau_fatorderisco.s105_i_codigo ';
  $sSubFatorRisco   .= '    inner join far_fatorrisco  on  far_fatorrisco.fa44_i_codigo = ';
  $sSubFatorRisco   .= '      far_fatorriscofarmaciaambulatorial.fa45_i_fatorriscofarmacia ';
  $sSubFatorRisco   .= '      where cgsfatorderisco.s106_i_cgs = z01_i_cgsund and far_fatorrisco.fa44_i_codrisco ';

  $sSubComplicacoes  = 'select fa52_i_codigo ';
  $sSubComplicacoes .= '  from far_complicacoescadacomp ';
  $sSubComplicacoes .= '    inner join far_complicacoes  on  far_complicacoes.fa51_i_codigo = ';
  $sSubComplicacoes .= '      far_complicacoescadacomp.fa52_i_complicacao ';
  $sSubComplicacoes .= '      where far_complicacoescadacomp.fa52_i_cadacomp = fa50_i_codigo ';
  $sSubComplicacoes .= '        and far_complicacoes.fa51_i_codcomplicacao ';

  $sSubCodMedic      = 'select fa43_c_codhiperdia ';
  $sSubCodMedic     .= '  from far_medicamentocadacomp ';
  $sSubCodMedic     .= '    inner join far_medicamentohiperdia  on ';
  $sSubCodMedic     .= '      far_medicamentohiperdia.fa43_i_codigo = far_medicamentocadacomp.fa49_i_medicamento ';
  $sSubCodMedic     .= '      where far_medicamentocadacomp.fa49_i_cadacomp = fa50_i_codigo ';
  $sSubCodMedic     .= '        order by fa43_i_codigo asc ';
  $sSubCodMedic     .= '          limit 1 ';

  $sSubQuantMedic    = "select case when fa43_c_codhiperdia = '06' ";
  $sSubQuantMedic   .= '         then fa49_n_quantidade::int ';
  $sSubQuantMedic   .= '         else fa49_n_quantidade ';
  $sSubQuantMedic   .= '       end as fa49_n_quantidade ';
  $sSubQuantMedic   .= '  from far_medicamentocadacomp ';
  $sSubQuantMedic   .= '    inner join far_medicamentohiperdia on far_medicamentohiperdia.fa43_i_codigo = ';
  $sSubQuantMedic   .= '      far_medicamentocadacomp.fa49_i_medicamento ';
  $sSubQuantMedic   .= '      where far_medicamentocadacomp.fa49_i_cadacomp = fa50_i_codigo ';
  $sSubQuantMedic   .= '        order by fa43_i_codigo asc ';
  $sSubQuantMedic   .= '          limit 1 ';

  $sSubExames        = 'select fa48_i_codigo ';
  $sSubExames       .= '  from far_examesacomp ';
  $sSubExames       .= '    inner join far_exames  on  far_exames.fa47_i_codigo = far_examesacomp.fa48_i_exame ';
  $sSubExames       .= '      where far_examesacomp.fa48_i_acompanhamento = fa50_i_codigo ';
  $sSubExames       .= '        and far_exames.fa47_i_codigo ';


  /* DADOS PARA A EXPORTAÇÃO DE PROFISSIONAIS */
  $sCampos[0]  = ' distinct on (sd03_i_codigo) ';
  $sCampos[0] .= "  '050' as tipo_op, ";
  $sCampos[0] .= "  lpad(sd02_i_cidade, 7, '0') as cod_munic, ";
  $sCampos[0] .= "  s153_c_codigo as cod_dist_sanit, ";
  $sCampos[0] .= '  case when sd02_c_siasus is not null ';
  $sCampos[0] .= "     then lpad(sd02_c_siasus, 7, '0') ";
  $sCampos[0] .= "     else lpad(sd02_v_cnes, 7, '0') ";
  $sCampos[0] .= '   end as cod_siasus, ';
  $sCampos[0] .= "   lpad(sd02_v_cnes, 7, '0') as cod_cnes, ";
  $sCampos[0] .= "   '000' as controle, ";
  $sCampos[0] .= "   '               ' as cod_cns_profissional, ";
  $sCampos[0] .= "   lpad(sd03_i_codigo, 20, ' ') as matricula_profissional, ";
  $sCampos[0] .= "   lpad(sd03_i_codigo, 20, ' ') as id_profissional, ";
  $sCampos[0] .= "   '           ' as num_pis, ";
  $sCampos[0] .= '   fa53_c_estrutural as cod_cbo, ';
  $sCampos[0] .= "   rpad(z01_nome, 50, ' ') as nome_profissional, ";
  $sCampos[0] .= "   '00000000000' as num_cpf, ";
  $sCampos[0] .= "   '      ' as nome_orgao_classe, ";
  $sCampos[0] .= "   '         ' as num_insc_org_classe, ";
  $sCampos[0] .= "   '          ' as data_desativacao ";

  $sWhere[0]   = " s152_d_datasistema between '$dDataIni' and '$dDataFim' ";
  $sOrder[0]   = '';

  /* DADOS PARA A EXPORTAÇÃO DE PACIENTES */
  $sCampos[1]  = "      z01_i_cgsund, ";
  $sCampos[1] .= "      '060' as tipo_op, ";
  $sCampos[1] .= "      lpad(sd02_i_cidade, 7, '0') as cod_munic, ";
  $sCampos[1] .= "      s153_c_codigo as cod_dist_sanit, ";
  $sCampos[1] .= "      '000' as controle1, ";
  $sCampos[1] .= "      case when sd02_c_siasus is not null ";
  $sCampos[1] .= "        then lpad(sd02_c_siasus, 7, '0') ";
  $sCampos[1] .= "        else lpad(sd02_v_cnes, 7, '0') ";
  $sCampos[1] .= "      end as cod_siasus, ";
  $sCampos[1] .= "      '000' as controle2, ";
  $sCampos[1] .= "      lpad(sd02_v_cnes, 7, '0') as cod_cnes, ";
  $sCampos[1] .= "      lpad(sd03_i_codigo, 20, ' ') as id_profissional, ";
  $sCampos[1] .= "      lpad(z01_i_cgsund, 50, ' ') as id_paciente, ";
  $sCampos[1] .= "      '               ' as num_cns_paciente, ";
  $sCampos[1] .= "      rpad(z01_v_nome, 70, ' ') as nome_paciente, ";
  $sCampos[1] .= "      '552' as cod_logradouro, ";
  $sCampos[1] .= "      case when z01_v_ender is not null ";
  $sCampos[1] .= "        then rpad(upper(z01_v_ender), 50, ' ') ";
  $sCampos[1] .= "        else rpad('RUA', 50, ' ') ";
  $sCampos[1] .= "      end as nome_logradouro, ";
  $sCampos[1] .= "      rpad(z01_v_compl, 15, ' ') as complemento_logradouro, ";
  $sCampos[1] .= "      case when z01_i_numero is not null ";
  $sCampos[1] .= "        then lpad(z01_i_numero, 7, '0') ";
  $sCampos[1] .= "        else rpad('S/N', 7, ' ') ";
  $sCampos[1] .= "      end as num_logradouro, ";
  $sCampos[1] .= "      case when z01_v_bairro is not null ";
  $sCampos[1] .= "        then rpad(upper(z01_v_bairro), 30, ' ') ";
  $sCampos[1] .= "        else rpad('BAIRRO', 30, ' ') ";
  $sCampos[1] .= "      end as nome_bairro, ";
  $sCampos[1] .= "      case when z01_v_cep is not null ";
  $sCampos[1] .= "        then upper(z01_v_cep) ";
  $sCampos[1] .= "        else (select cep from db_config limit 1) ";
  $sCampos[1] .= "      end as cep, ";
  $sCampos[1] .= "      '   ' as ddd, ";
  $sCampos[1] .= "      '         ' as num_telef, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1001) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_ant_famil, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1002) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_diabetes1, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1003) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_diabetes2, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1004) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_tabagismo, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1005) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_sedentarismo, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1006) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_sobrepeso, ";
  $sCampos[1] .= "      case when ($sSubFatorRisco = 1007) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_hipertensao, ";
  $sCampos[1] .= "      case when ($sSubComplicacoes = 2001) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_infarto, ";
  $sCampos[1] .= "      case when ($sSubComplicacoes = 2002) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_outras_coronariopatias, ";
  $sCampos[1] .= "      case when ($sSubComplicacoes = 2003) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_avc, ";
  $sCampos[1] .= "      case when ($sSubComplicacoes = 2004) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_pe_diabetico, ";
  $sCampos[1] .= "      case when ($sSubComplicacoes = 2005) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_amputacao, ";
  $sCampos[1] .= "      case when ($sSubComplicacoes = 2006) is null ";
  $sCampos[1] .= "        then 'N' ";
  $sCampos[1] .= "        else 'S' ";
  $sCampos[1] .= "      end as status_doenca_renal, ";
  $sCampos[1] .= "      substr(s152_d_dataconsulta, 9, 2) || '/' || substr(s152_d_dataconsulta, 6, 2) ";
  $sCampos[1] .= "        || '/' || substr(s152_d_dataconsulta, 1, 4) as data_consulta, ";
  $sCampos[1] .= "      lpad(s152_i_pressaosistolica, 3, '0') as pressao_sistolica, ";
  $sCampos[1] .= "      lpad(s152_i_pressaodiastolica, 3, '0') as pressao_diastolica, ";
  $sCampos[1] .= "      lpad(s152_i_cintura, 3, '0') as cintura, ";
  $sCampos[1] .= "      replace(lpad(s152_n_peso, 7, '0'), '.', ',') as peso, ";
  $sCampos[1] .= "      lpad(s152_i_altura, 3, '0') as altura, ";
  $sCampos[1] .= "      lpad(s152_i_glicemia, 3, '0') as glicemia, ";
  $sCampos[1] .= "      case when fa50_i_outrosmedicamentos = 1 ";
  $sCampos[1] .= "        then 'S' ";
  $sCampos[1] .= "        else 'N' ";
  $sCampos[1] .= "      end as status_outros_med, ";
  $sCampos[1] .= "      case when fa50_i_naomedicamentoso = 1 ";
  $sCampos[1] .= "        then 'S' ";
  $sCampos[1] .= "        else 'N' ";
  $sCampos[1] .= "      end as status_nao_medicamentoso, ";
  $sCampos[1] .= "      case when s152_i_alimentacaoexameglicemia = 1 then 'J' ";
  $sCampos[1] .= "           when s152_i_alimentacaoexameglicemia = 2 then 'P' ";
  $sCampos[1] .= "           else ' ' ";
  $sCampos[1] .= "      end as status_alimentacao, ";
  $sCampos[1] .= "      lpad(coalesce(($sSubCodMedic offset 0), '0'), 2, '0') ";
  $sCampos[1] .= "      as cod_med1, ";
  $sCampos[1] .= "      replace(lpad(coalesce(($sSubQuantMedic offset 0), '0'), 3, '0'), '.', ',') ";
  $sCampos[1] .= "      as quant_med1, ";
  $sCampos[1] .= "      lpad(coalesce(($sSubCodMedic offset 1), '0'), 2, '0') ";
  $sCampos[1] .= "      as cod_med2, ";
  $sCampos[1] .= "      replace(lpad(coalesce(($sSubQuantMedic offset 1), '0'), 3, '0'), '.', ',') ";
  $sCampos[1] .= "      as quant_med2, ";
  $sCampos[1] .= "      lpad(coalesce(($sSubCodMedic offset 2), '0'), 2, '0') ";
  $sCampos[1] .= "      as cod_med3, ";
  $sCampos[1] .= "      replace(lpad(coalesce(($sSubQuantMedic offset 2), '0'), 3, '0'), '.', ',') ";
  $sCampos[1] .= "      as quant_med3, ";
  $sCampos[1] .= "      lpad(coalesce(($sSubCodMedic offset 3), '0'), 2, '0') ";
  $sCampos[1] .= "      as cod_med4, ";
  $sCampos[1] .= "      replace(lpad(coalesce(($sSubQuantMedic offset 3), '0'), 3, '0'), '.', ',') ";
  $sCampos[1] .= "      as quant_med4, ";
  $sCampos[1] .= "      lpad(coalesce(($sSubCodMedic offset 4), '0'), 2, '0') ";
  $sCampos[1] .= "      as cod_med5, ";
  $sCampos[1] .= "      replace(lpad(coalesce(($sSubQuantMedic offset 4), '0'), 3, '0'), '.', ',') ";
  $sCampos[1] .= "      as quant_med5, ";
  $sCampos[1] .= "      lpad(coalesce(($sSubCodMedic offset 5), '0'), 2, '0') ";
  $sCampos[1] .= "      as cod_med6, ";
  $sCampos[1] .= "      replace(lpad(coalesce(($sSubQuantMedic offset 5), '0'), 3, '0'), '.', ',') ";
  $sCampos[1] .= "      as quant_med6, ";
  $sCampos[1] .= "      z01_v_sexo as sexo, ";
  $sCampos[1] .= "      case when z01_d_nasc is not null ";
  $sCampos[1] .= "        then substr(z01_d_nasc, 9, 2) || '/' || substr(z01_d_nasc, 6, 2) ";
  $sCampos[1] .= "          || '/' || substr(z01_d_nasc, 1, 4) ";
  $sCampos[1] .= "        else null ";
  $sCampos[1] .= "      end as data_nascimento, ";
  $sCampos[1] .= "      rpad(z01_v_mae, 70, ' ') as nome_mae, ";
  $sCampos[1] .= "      rpad(z01_v_pai, 70, ' ') as nome_pai, ";
  $sCampos[1] .= "      case when z01_c_raca = 'BRANCA' then 1 ";
  $sCampos[1] .= "           when z01_c_raca = 'PRETA' then 2 ";
  $sCampos[1] .= "           when z01_c_raca = 'AMARELA' then 3 ";
  $sCampos[1] .= "           when z01_c_raca = 'PARDA' then 4 ";
  $sCampos[1] .= "           when z01_c_raca = 'INDÍGENA' then 5 ";
  $sCampos[1] .= "           else 1 ";
  $sCampos[1] .= "      end as cod_raca_cor, ";
  $sCampos[1] .= "      '06' as cod_situ_conjug, ";
  $sCampos[1] .= "      '02' as cod_escol, ";
  $sCampos[1] .= "      '00000000000' as cod_pis, ";
  $sCampos[1] .= "      '00000000000' as num_cpf, ";
  $sCampos[1] .= "      '00' as cod_tipo_certidao, ";
  $sCampos[1] .= "      '                    'as nome_cartorio, ";
  $sCampos[1] .= "      '        ' as num_livro, ";
  $sCampos[1] .= "      '    ' as num_folha, ";
  $sCampos[1] .= "      '        ' as num_termo, ";
  $sCampos[1] .= "      '          ' as data_emissao_certidao, ";
  $sCampos[1] .= "      '00' as cod_org_emissor, ";
  $sCampos[1] .= "      '00000000000' as num_ident, ";
  $sCampos[1] .= "      '    ' as compl_ident, ";
  $sCampos[1] .= "      '  ' as sigla_uf_emissora_ident, ";
  $sCampos[1] .= "      '          ' as data_emissao_ident, ";
  $sCampos[1] .= "      '0000000' as num_ctps, ";
  $sCampos[1] .= "      '00000' as num_serie_ctps, ";
  $sCampos[1] .= "      '  ' as uf_emissora_ctps, ";
  $sCampos[1] .= "      '          ' as data_emissao_ctps, ";
  $sCampos[1] .= "      '0000000000000' as num_titulo_eleitor, ";
  $sCampos[1] .= "      '0000' as num_zona_titulo, ";
  $sCampos[1] .= "      '0000' as secao_titulo, ";
  $sCampos[1] .= "      '010' as cod_pais_origem, ";
  $sCampos[1] .= "      '0000000000000000' as num_portaria_naturalizacao, ";
  $sCampos[1] .= "      '          ' as data_naturalizacao, ";
  $sCampos[1] .= "      lpad(sd02_i_cidade, 7, '0') as cod_munic_nasc, ";
  $sCampos[1] .= "      '          ' as data_entrada_pais, ";
  $sCampos[1] .= "      '          ' as data_obito ";

  $sWhere[1]   = "far_cadacomppachiperdia.fa50_i_tipo = 1 and s152_d_datasistema between '$dDataIni' and '$dDataFim' ";
  $sOrder[1]   = 'z01_v_nome';

  /* DADOS PARA A EXPORTAÇÃO DE ACOMPANHAMENTOS */
  $sCampos[2]  = "     '070' as tipo_op, ";
  $sCampos[2] .= "     lpad(sd02_i_cidade, 7, '0') as cod_munic, ";
  $sCampos[2] .= "     s153_c_codigo as cod_dist_sanit, ";
  $sCampos[2] .= "     case when sd02_c_siasus is not null ";
  $sCampos[2] .= "       then lpad(sd02_c_siasus, 7, '0') ";
  $sCampos[2] .= "       else lpad(sd02_v_cnes, 7, '0') ";
  $sCampos[2] .= "     end as cod_siasus, ";
  $sCampos[2] .= "     lpad(sd02_v_cnes, 7, '0') as cod_cnes, ";
  $sCampos[2] .= "     lpad(sd03_i_codigo, 20, ' ') as id_profissional, ";
  $sCampos[2] .= "     lpad(z01_i_cgsund, 50, ' ') as id_paciente, ";
  $sCampos[2] .= "     '               ' as num_cns_paciente, ";
  $sCampos[2] .= "     substr(s152_d_dataconsulta, 9, 2) || '/' || substr(s152_d_dataconsulta, 6, 2) ";
  $sCampos[2] .= "       || '/' || substr(s152_d_dataconsulta, 1, 4) as data_consulta, ";
  $sCampos[2] .= "     case when s152_i_alimentacaoexameglicemia = 1 then 'J' ";
  $sCampos[2] .= "          when s152_i_alimentacaoexameglicemia = 2 then 'P' ";
  $sCampos[2] .= "          else ' ' ";
  $sCampos[2] .= "     end as status_alimentacao, ";
  $sCampos[2] .= "     case when fa50_i_naomedicamentoso = 1 ";
  $sCampos[2] .= "       then 'S' ";
  $sCampos[2] .= "       else 'N' ";
  $sCampos[2] .= "     end as status_nao_medicamentoso, ";
  $sCampos[2] .= "     lpad(s152_i_pressaosistolica, 3, '0') as pressao_sistolica, ";
  $sCampos[2] .= "     lpad(s152_i_pressaodiastolica, 3, '0') as pressao_diastolica, ";
  $sCampos[2] .= "     lpad(s152_i_cintura, 3, '0') as cintura, ";
  $sCampos[2] .= "     replace(lpad(s152_n_peso, 7, '0'), '.', ',') as peso, ";
  $sCampos[2] .= "     lpad(s152_i_altura, 3, '0') as altura, ";
  $sCampos[2] .= "     lpad(s152_i_glicemia, 3, '0') as glicemia, ";
  $sCampos[2] .= "     case when fa50_i_outrosmedicamentos = 1 ";
  $sCampos[2] .= "       then 'S' ";
  $sCampos[2] .= "       else 'N' ";
  $sCampos[2] .= "     end as status_outros_med, ";
  $sCampos[2] .= "     lpad(coalesce(($sSubCodMedic offset 0), '0'), 2, '0') ";
  $sCampos[2] .= "     as cod_med1, ";
  $sCampos[2] .= "     replace(lpad(coalesce(($sSubQuantMedic offset 0), '0'), 3, '0'), '.', ',') ";
  $sCampos[2] .= "     as quant_med1, ";
  $sCampos[2] .= "     lpad(coalesce(($sSubCodMedic offset 1), '0'), 2, '0') ";
  $sCampos[2] .= "     as cod_med2, ";
  $sCampos[2] .= "     replace(lpad(coalesce(($sSubQuantMedic offset 1), '0'), 3, '0'), '.', ',') ";
  $sCampos[2] .= "     as quant_med2, ";
  $sCampos[2] .= "     lpad(coalesce(($sSubCodMedic offset 2), '0'), 2, '0') ";
  $sCampos[2] .= "     as cod_med3, ";
  $sCampos[2] .= "     replace(lpad(coalesce(($sSubQuantMedic offset 2), '0'), 3, '0'), '.', ',') ";
  $sCampos[2] .= "     as quant_med3, ";
  $sCampos[2] .= "     lpad(coalesce(($sSubCodMedic offset 3), '0'), 2, '0') ";
  $sCampos[2] .= "     as cod_med4, ";
  $sCampos[2] .= "     replace(lpad(coalesce(($sSubQuantMedic offset 3), '0'), 3, '0'), '.', ',') ";
  $sCampos[2] .= "     as quant_med4, ";
  $sCampos[2] .= "     lpad(coalesce(($sSubCodMedic offset 4), '0'), 2, '0') ";
  $sCampos[2] .= "     as cod_med5, ";
  $sCampos[2] .= "     replace(lpad(coalesce(($sSubQuantMedic offset 4), '0'), 3, '0'), '.', ',') ";
  $sCampos[2] .= "     as quant_med5, ";
  $sCampos[2] .= "     lpad(coalesce(($sSubCodMedic offset 5), '0'), 2, '0') ";
  $sCampos[2] .= "     as cod_med6, ";
  $sCampos[2] .= "     replace(lpad(coalesce(($sSubQuantMedic offset 5), '0'), 3, '0'), '.', ',') ";
  $sCampos[2] .= "     as quant_med6, ";
  $sCampos[2] .= "     case when (select fa52_i_codigo from far_complicacoescadacomp ";
  $sCampos[2] .= "                where far_complicacoescadacomp.fa52_i_cadacomp = fa50_i_codigo limit 1) is null ";
  $sCampos[2] .= "       then 'S' ";
  $sCampos[2] .= "       else 'N' ";
  $sCampos[2] .= "     end as status_sem_complicacoes_acomp, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2007) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_angina, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2001) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_infarto, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2003) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_avc, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2004) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_pe_diabetico, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2005) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_amputacao, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2006) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_doenca_renal, ";
  $sCampos[2] .= "     case when ($sSubComplicacoes = 2008) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_fundo_olho_alterado, ";
  $sCampos[2] .= "     case when ($sSubExames = 1) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_hb_glicosilada, ";
  $sCampos[2] .= "     case when ($sSubExames = 2) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_creantina_serica, ";
  $sCampos[2] .= "     case when ($sSubExames = 3) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_colesterol_total, ";
  $sCampos[2] .= "     case when ($sSubExames = 4) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_ecg, ";
  $sCampos[2] .= "     case when ($sSubExames = 5) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_triglicerides, ";
  $sCampos[2] .= "     case when ($sSubExames = 6) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_urina_tipo1, ";
  $sCampos[2] .= "     case when ($sSubExames = 7) is null ";
  $sCampos[2] .= "       then 'N' ";
  $sCampos[2] .= "       else 'S' ";
  $sCampos[2] .= "     end as status_exame_microalbuminuria, ";
  $sCampos[2] .= "     case when fa50_i_hipertensaoacomp = 1 ";
  $sCampos[2] .= "       then 'S' ";
  $sCampos[2] .= "       else 'N' ";
  $sCampos[2] .= "     end as status_hipertensao, ";
  $sCampos[2] .= "     case when fa50_i_diabetesacomp = 1 ";
  $sCampos[2] .= "       then 'S' ";
  $sCampos[2] .= "       else 'N' ";
  $sCampos[2] .= "     end as status_diabetes, ";
  $sCampos[2] .= "     'M' as tipo_risco_paciente ";

  $sWhere[2]   = "far_cadacomppachiperdia.fa50_i_tipo = 2 and s152_d_datasistema between '$dDataIni' and '$dDataFim' ";
  $sOrder[2]   = 'z01_v_nome';

  $aNumLinhas  = array();
  $iCodMunic   = null;
  $aNomesArquivosTmp = array();;
  for ($iCont = 0; $iCont < 3; $iCont++) {

    $sSql               = $oDaoFarAcompPacHiperdia->sql_query2(null, $sCampos[$iCont], 
                                                               $sOrder[$iCont], $sWhere[$iCont]
                                                              );
    $rs[$iCont]         = $oDaoFarAcompPacHiperdia->sql_record($sSql);
    
    $aNumLinhas[$iCont] = $oDaoFarAcompPacHiperdia->numrows;
    if ($oDaoFarAcompPacHiperdia->erro_status == '0') {
      continue;
    } else {

      if (empty($iCodMunic)) {

        $oDados    = db_utils::fieldsmemory($rs[$iCont], 0);
        $iCodMunic = $oDados->cod_munic;

      }

    }

    //função que gera o arquivo
    $lSucesso = geraArquivoHiperdia($iCont + 1, 
                                    $aNomesArquivosTmp[count($aNomesArquivosTmp)] = '/tmp/exportacao_hiperdia'.
                                    ($iCont + 1).'.txt',
                                    $rs[$iCont], $oDaoFarAcompPacHiperdia->numrows
                                   );
    if (!$lSucesso) {

      $iCont++;
      echo" <script>
              listagem = '/tmp/logErroHiperdia$iCont.txt#Download arquivo TXT Log de Erro|';
              js_montarlista(listagem,'form1');
            </script> ";

      die();

    }
    
  }

  $dDataCriacao  = date('d/m/Y', db_getsession('DB_datausu'));
  $sHoraCriacao  = date('H:i:s');
  $aDataCriacao  = explode('/', $dDataCriacao);
  $aHoraCriacao  = explode(':', $sHoraCriacao);
  $sDataCriacao  = $aDataCriacao[2].$aDataCriacao[1].$aDataCriacao[0];
  $sHoraCriacao2 = $aHoraCriacao[0].$aHoraCriacao[1].$aHoraCriacao[2];

  // Dados do cabecalho
  $oDadosHeader->tipo_op        = '001';
  $oDadosHeader->cod_munic      = $iCodMunic;
  $oDadosHeader->data_arquivo   = $dDataCriacao.' '.$sHoraCriacao;
  $oDadosHeader->versao_layout  = '010';
  $oDadosHeader->versao_sistema = '0000001733';
  $oDadosHeader->sistema_origem = '999';


  // Gera o cabecalho
  geraArquivoHiperdia(0, $aNomesArquivosTmp[count($aNomesArquivosTmp)] = '/tmp/exportacao_hiperdia0.txt',
                      $oDadosHeader, 1
                     );


  $iNumLinhasTotal = 0;
  for ($iCont = 0; $iCont < 3; $iCont++) {

    if (!empty($aNumLinhas[$iCont]) && $aNumLinhas[$iCont] > 0) {

      $iNumLinhasTotal += $aNumLinhas[$iCont];

    }

  }

  if ($iNumLinhasTotal == 0) {
    die("Erro ao selecionar os registros ou nenhum registro retornado. <p>Comunique o adminstrador.");
  }

  $iNumLinhasTotal += 2; // header e trailer

  // Dados do trailer
  $oDadosTrailer->tipo_op      = '999';
  $oDadosTrailer->cod_munic    = $iCodMunic;
  $oDadosTrailer->quant_linhas = str_pad($iNumLinhasTotal, 8, '0', STR_PAD_LEFT);

  // Gera o trailer
  geraArquivoHiperdia(4, $aNomesArquivosTmp[count($aNomesArquivosTmp)] = '/tmp/exportacao_hiperdia4.txt',
                      $oDadosTrailer, 1
                     );

  //Nome do arquivo de importação do hiperdia
  $sAlvo = '/tmp/E'.$iCodMunic.$sDataCriacao.$sHoraCriacao2.'.APL';
  
  //função que une os arquivos
  array_multisort($aNomesArquivosTmp, SORT_ASC);
  $lSucesso = unirArquivos($aNomesArquivosTmp, $sAlvo, true);
  
  //se o arquivo foi gerado corretamente gravar no banco
  if ($lSucesso) {

    db_inicio_transacao();
    $oDaoFarArquivoHiperdia->fa56_i_login       = $iLogin;
    $oDaoFarArquivoHiperdia->fa56_d_datasistema = $dHoje;
    $oDaoFarArquivoHiperdia->fa56_c_horasistema = date('H:i');
    $oDaoFarArquivoHiperdia->fa56_c_nomearquivo = $sAlvo;
    $oDaoFarArquivoHiperdia->fa56_o_arquivo     = db_geraArquivoOidfarmacia($sAlvo, '', 1, $conn);
    $oDaoFarArquivoHiperdia->incluir(null);
    db_fim_transacao($oDaoFarArquivoHiperdia->erro_status == '0' ? true : false);

    $oDaoFarArquivoHiperdia->erro(true, false);

    if ($oDaoFarArquivoHiperdia->erro_status != '0') {

      echo" <script>
              listagem = '$sAlvo#Download arquivo TXT |';
              js_montarlista(listagem,'form1');
            </script> ";

    }
    
  }

}
?>