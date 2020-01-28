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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$resultedu           = eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));

$claluno             = new cl_aluno;
$clalunoprimat       = new cl_alunoprimat;
$clalunonecessidade  = new cl_alunonecessidade;
$clmatricula         = new cl_matricula;
$clturmaacmatricula  = new cl_turmaacmatricula;
$clmatriculamov      = new cl_matriculamov;
$clturma             = new cl_turma;
$clserieregimemat    = new cl_serieregimemat;
$clprocavaliacao     = new cl_procavaliacao;
$clregencia          = new cl_regencia;
$cldiarioavaliacao   = new cl_diarioavaliacao;
$clregenciaperiodo   = new cl_regenciaperiodo;
$clhistoricomps      = new cl_historicomps;
$clhistoricompsfora  = new cl_historicompsfora;
$clhistmpsdisc       = new cl_histmpsdisc;
$clhistmpsdiscfora   = new cl_histmpsdiscfora;
$cllogmatricula      = new cl_logmatricula;
$clprocresultado     = new cl_procresultado;
$clrotulo            = new rotulocampo;

$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed60_i_codigo");
$claluno->rotulo->label();
$clmatricula->rotulo->label();
$clturma->rotulo->label();
$clserieregimemat->rotulo->label();
$clalunoprimat->rotulo->label();
$cllogmatricula->rotulo->label();

$db_opcao = 1;
$db_botao = true;

if (isset($chavepesquisa)) {

  $campos  = "aluno.*, ";
  $campos .= " censoufident.ed260_c_sigla as ufident, ";
  $campos .= " censoufnat.ed260_c_sigla as ufnat, ";
  $campos .= " censoufcert.ed260_c_sigla as ufcert, ";
  $campos .= " censoufend.ed260_c_sigla as ufend, ";
  $campos .= " censomunicnat.ed261_c_nome as municnat, ";
  $campos .= " censomuniccert.ed261_c_nome as municcert, ";
  $campos .= " censomunicend.ed261_c_nome as municend, ";
  $campos .= " censoorgemissrg.ed132_c_descr as orgemissrg ";
  $result  = $claluno->sql_record($claluno->sql_query("",$campos,""," ed47_i_codigo = $chavepesquisa"));
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11px;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11px;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10px;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10px;
}
.aluno2{
 color: #000000;
 font-family : Verdana;
 font-size: 10px;
 font-weight: bold;
}
</style>
</head>
<body bgcolor="#f3f3f3" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table bgcolor="#f3f3f3" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <table border="0" bgcolor="#f3f3f3" width="100%" cellspacing="0" cellpading="0" height="800" >
    <?if ($evento == 1) {?>

         <tr>
           <td valign="top" >
             <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Documentos</b></legend>
               <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
                 <tr>
                   <td>
                    <b>Certidão (matrícula): </b>
                    <?
                      if ($ed47_certidaomatricula == "" || $ed47_certidaomatricula == null) {

                        echo $ed47_certidaomatricula = "Não Informado ";

                      } else {
                        echo substr($ed47_certidaomatricula, 0, 6)." ".substr($ed47_certidaomatricula, 6, 2)." ".
                             substr($ed47_certidaomatricula, 8, 2)." ".substr($ed47_certidaomatricula, 10, 4)." ".
                             substr($ed47_certidaomatricula, 14, 1)." ".substr($ed47_certidaomatricula, 15, 5)." ".
                             substr($ed47_certidaomatricula, 20, 3)." ".substr($ed47_certidaomatricula, 23, 7)." ".
                             substr($ed47_certidaomatricula, 30, 2);
                      }
                    ?>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <?=$Led47_c_certidaotipo?>
                     <?=$ed47_c_certidaotipo == "" ? "Não Informado" : ($ed47_c_certidaotipo == "C" ? "CASAMENTO" : "NASCIMENTO")?>
                     &nbsp;&nbsp;
                     <?=$Led47_c_certidaonum?> <?=$ed47_c_certidaonum == "" ? "Não Informado" : $ed47_c_certidaonum?>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <?=$Led47_c_certidaolivro?> <?=$ed47_c_certidaolivro == "" ? "Não Informado" : $ed47_c_certidaolivro?>
                     &nbsp;&nbsp;
                     <?=$Led47_c_certidaofolha?> <?=$ed47_c_certidaofolha == "" ? "Não Informado" : $ed47_c_certidaofolha?>
                     &nbsp;&nbsp;
                     <?=$Led47_c_certidaodata?>
                     <?=$ed47_c_certidaodata == "" ? "Não Informado" : db_formatar($ed47_c_certidaodata, 'd')?>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <?=$Led47_c_certidaocart?>
                     <?

                      if ( isset( $ed47_i_censocartorio ) && !empty( $ed47_i_censocartorio ) ) {

                        $oDaoCensoCartorio = db_utils::getdao('censocartorio');
                        $sSqlCartorio      = $oDaoCensoCartorio->sql_query("",
                                                                           "*",
                                                                           "",
                                                                           " ed291_i_codigo = ".$ed47_i_censocartorio);
                        $rsResultCartorio  = $oDaoCensoCartorio->sql_record($sSqlCartorio);

                        if ($oDaoCensoCartorio->numrows > 0) {

                          $oDadosCartorio = db_utils::fieldsmemory($rsResultCartorio, 0);
                          echo $oDadosCartorio->ed291_c_nome;
                        } else {
                          echo "Não Informado.";
                        }
                      } else {
                        echo "Não Informado.";
                      }

                     ?>
                     &nbsp;&nbsp;
                     <?=$Led47_i_censomuniccert?> <?=$ed47_i_censomuniccert == "" ? "Não Informado" : $municcert?>
                     &nbsp;&nbsp;
                     <?=$Led47_i_censoufcert?> <?=$ed47_i_censoufcert == "" ? "Não Informado" : $ufcert?>
                   </td>
                 </tr>
               </table>
               <br>
               <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
                 <tr>
                   <td>
                     <?=$Led47_v_ident?> <?=$ed47_v_ident == "" ? "Não Informado" : $ed47_v_ident?>
                     &nbsp;&nbsp;
                     <?=$Led47_v_identcompl?> <?=$ed47_v_identcompl == "" ? "Não Informado" : $ed47_v_identcompl?>
                     &nbsp;&nbsp;
                     <?=$Led47_i_censoufident?> <?=$ed47_i_censoufident == "" ? "Não Informado" : $ufident?>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <?=$Led47_i_censoorgemissrg?> <?=$ed47_i_censoorgemissrg == "" ? "Não Informado" : $orgemissrg?>
                     &nbsp;&nbsp;
                     <?=$Led47_d_identdtexp?> <?=$ed47_d_identdtexp == "" ? "Não Informado" : db_formatar($ed47_d_identdtexp, 'd')?>
                   </td>
                 </tr>
               </table>
               <br>
               <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
                 <tr>
                   <td>
                     <?=$Led47_v_cnh?> <?=$ed47_v_cnh == "" ? "Não Informado" : $ed47_v_cnh?>
                     &nbsp;&nbsp;
                     <?=$Led47_v_categoria?> <?=$ed47_v_categoria == "" ? "Não Informado" : $ed47_v_categoria?>
                     &nbsp;&nbsp;
                     <?=$Led47_d_dtemissao?> <?=$ed47_d_dtemissao == "" ? "Não Informado" : db_formatar($ed47_d_dtemissao, 'd')?>
                   </td>
                 </tr>
                <tr>
                  <td>
                    <?=$Led47_d_dthabilitacao?>
                    <?=$ed47_d_dthabilitacao == "" ? "Não Informado" : db_formatar($ed47_d_dthabilitacao, 'd')?>
                    &nbsp;&nbsp;
                    <?=$Led47_d_dtvencimento?>
                    <?=$ed47_d_dtvencimento == "" ? "Não Informado" : db_formatar($ed47_d_dtvencimento, 'd')?>
                  </td>
                </tr>
               </table>
               <br>
               <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
                 <tr>
                   <td>
                     <?=$Led47_v_cpf?> <?=$ed47_v_cpf == "" ? "Não Informado" : $ed47_v_cpf?>
                     &nbsp;&nbsp;
                     <?=$Led47_c_passaporte?> <?=$ed47_c_passaporte == "" ? "Não Informado" : $ed47_c_passaporte?>
                     &nbsp;&nbsp;
                     <?=$Led47_cartaosus?> <?=$ed47_cartaosus == "" ? "Não Informado" : $ed47_cartaosus?>
                   </td>
                 </tr>
               </table>
             </fieldset>
           </td>
         </tr>
    <?}

      if ($evento == 2) {?>

        <tr>
         <td valign="top" >
          <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Outras Informações</b></legend>
            <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
              <tr>
               <td>
                <?=$Led47_i_filiacao?> <?=$ed47_i_filiacao=="0"?"NÃO DECLARADO/IGNORADO":"PAI E/OU MÃE"?>
               </td>
              </tr>
              <tr>
               <td>
                 <?=$Led47_v_mae?> <?=$ed47_v_mae==""?"Não Informado":$ed47_v_mae?>
               </td>
              </tr>
              <tr>
               <td>
                 <?=$Led47_v_pai?> <?=$ed47_v_pai==""?"Não Informado":$ed47_v_pai?>
               </td>
              </tr>
              <tr>
               <td>
                 <?=$Led47_c_nomeresp?> <?=$ed47_c_nomeresp==""?"Não Informado":$ed47_c_nomeresp?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_c_emailresp?> <?=$ed47_c_emailresp==""?"Não Informado":$ed47_c_emailresp?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_celularresponsavel?> <?=$ed47_celularresponsavel==""?"Não Informado":$ed47_celularresponsavel?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_c_bolsafamilia?> <?=$ed47_c_bolsafamilia=="S"?"SIM":"NÃO"?>
                &nbsp;&nbsp;
                <?=$Led47_c_nis?> <?=$ed47_c_nis==""?"Não Informado":$ed47_c_nis?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_i_transpublico?> <?=$ed47_i_transpublico=="0"?"NÃO UTILIZA":"UTILIZA"?>
                &nbsp;&nbsp;
                <?=$Led47_c_transporte?>
                <?=$ed47_c_transporte==""?"Não INformado":($ed47_c_transporte=="1"?"ESTADUAL":"MUNICIPAL")?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_v_email?> <?=$ed47_v_email==""?"Não Informado":$ed47_v_email?>
               </td>
              </tr>
              <tr>
               <td>
                 <?=$Led47_v_profis?> <?=$ed47_v_profis==""?"Não Informado":$ed47_v_profis?>
               </td>
              </tr>
              <tr>
               <td>
                 <?=$Led47_c_atenddifer?>
                 <?=$ed47_c_atenddifer=="1"?"Em Hospital":($ed47_c_atenddifer=="2"?"Em Domicílio":"Não Recebe")?>
                 <?

                    $sWhere    = " ed47_i_codigo = $chavepesquisa AND ed268_i_tipoatend = 5";

                    /**
                     * Removido vinculo com a matricula pois a tabela turmaacmatricula não é vinculado com
                     * matricula e sim com aluno.
                     */
                    //$sWhere   .= " AND ed60_c_situacao = 'MATRICULADO' AND ed60_c_concluida='N'";

                   $sSqlAtendimentoDiferenciado = $clturmaacmatricula->sql_query("",
                                                                                "ed268_c_descr, ed52_c_descr",
                                                                                "",
                                                                                $sWhere);
                  $result221 = $clturmaacmatricula->sql_record($sSqlAtendimentoDiferenciado);

                  if ($clturmaacmatricula->numrows > 0) {

                    db_fieldsmemory($result221,0);
                    echo "<tr>                                                          ";
                    echo " <td><b>Atendimento Educacional Especializado:</b> Recebe</td>";
                    echo "</tr>                                                         ";
                  }
                  ?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_d_cadast?> <?=db_formatar($ed47_d_cadast,'d')?>
                &nbsp;&nbsp;
                <?=$Led47_d_ultalt?> <?=db_formatar($ed47_d_ultalt,'d')?>
               </td>
              </tr>
              <tr>
               <td>
                <?=$Led47_t_obs?> <?=$ed47_t_obs==""?"Nenhuma":$ed47_t_obs?>
                &nbsp;&nbsp;
                <?=$Led47_v_contato?> <?=$ed47_v_contato==""?"Nenhuma":$ed47_v_contato?>
               </td>
              </tr>
             </table>
            </fieldset>
           </td>
          </tr>
    <?}

      if ($evento == 3) {

        $camp = "ed60_d_datasaida as datasaida, ";
        $camp .= " case ";
        $camp .= "  when ed60_c_situacao = 'TRANSFERIDO REDE' then ";
        $camp .= "   (select escoladestino.ed18_c_nome from transfescolarede ";
        $camp .= "     inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ";
        $camp .= "     inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ";
        $camp .= "    where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1) ";
        $camp .= "  when ed60_c_situacao = 'TRANSFERIDO FORA' then ";
        $camp .= "   (select escolaproc1.ed82_c_nome from transfescolafora ";
        $camp .= "    inner join escolaproc as escolaproc1 on  escolaproc1.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
        $camp .= "    where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1) ";
        $camp .= "else null ";
        $camp .= " end as destinosaida, ";
        $camp .= "  matricula.*, ";
        $camp .= "  serie.ed11_i_codigo, ";
        $camp .= " turma.ed57_c_descr, ";
        $camp .= " turmaserieregimemat.ed220_i_procedimento, ";
        $camp .= " turma.ed57_c_medfreq, ";
        $camp .= " calendario.ed52_c_descr, ";
        $camp .= " calendario.ed52_i_ano, ";
        $camp .= " case when turma.ed57_i_tipoturma = 2 then ";
        $camp .= "   fc_nomeetapaturma(ed60_i_turma) else ";
        $camp .= "  serie.ed11_c_descr  ";
        $camp .= " end as ed11_c_descr, ";
        $camp .= " escola.ed18_c_nome, ";
        $camp .= " turno.ed15_c_nome, ";
        $camp .= " aluno.ed47_v_nome, ";
        $camp .= " alunoprimat.ed76_i_codigo, ";
        $camp .= " alunoprimat.ed76_i_escola, ";
        $camp .= " alunoprimat.ed76_d_data, ";
        $camp .= " alunoprimat.ed76_c_tipo, ";
        $camp .= " case when ed76_c_tipo = 'M' ";
        $camp .= "  then escolaprimat.ed18_c_nome else escolaproc.ed82_c_nome end as nomeescola ";
        $result1 = $clmatricula->sql_record($clmatricula->sql_query("",$camp,""," ed60_i_codigo = $ed60_i_codigo"));
        ?>
        <tr>
         <td valign="top" >
          <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Matrículas</b></legend>
           <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
           <?
           if ($clmatricula->numrows > 0) {

             db_fieldsmemory($result1,0);

             /**
              * Verifica se a turma é do tipo Integral e Infantil, alterando a forma como é apresentada a descrição do
              * turno.
              * Por padrão, mostra somente a descrição do Turno (Ex.: MANHÃ)
              * No caso de turno Integral e Infantil, mostra também o turno referente o qual a matrícula está vinculada
              * Ex.: INTEGRAL - MANHÃ / TARDE
              */
             $oMatricula = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );
             if (    $oMatricula->getTurma()->getTurno()->isIntegral()
                  && $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getEnsino()->isInfantil()
                ) {

               $aDescricaoTurno = array();
               $aTurnoReferente = array( 1 => 'MANHÃ', 2 => 'TARDE', 3 => 'NOITE' );

               foreach ( $oMatricula->getTurnosVinculados() as $oTurnoReferente ) {
                 $aDescricaoTurno[] = $aTurnoReferente[ $oTurnoReferente->ed336_turnoreferente ];
               }

               $ed15_c_nome = "INTEGRAL - " . implode( " / ", $aDescricaoTurno );
             }
           ?>
            <tr>
             <td>
              <?=$Led60_i_codigo?> <?=$ed60_matricula?>
               &nbsp;&nbsp;
              <?=$Led57_i_escola?> <?=$ed18_c_nome?>
             </td>
            </tr>
            <tr>
             <td>
              <?=$Led60_d_datamatricula?> <?=db_formatar($ed60_d_datamatricula,'d')?>
              &nbsp;&nbsp;
              <?
              if (trim($ed60_c_situacao) == "AVANÇADO" || trim($ed60_c_situacao) == "CLASSIFICADO") {
                $sitt = 'Aprovado através de progressão';
              } else {

                if ($ed60_c_concluida == "S") {
                  $sitt = Situacao($ed60_c_situacao,$ed60_i_codigo)."(CONCLUÍDA)";
                } else {
                  $sitt = Situacao($ed60_c_situacao,$ed60_i_codigo);
                }

              }
              ?>
              <b>Situação:</b> <?=$sitt?>
             </td>
            </tr>
           <?if (trim(Situacao($ed60_c_situacao,$ed60_i_codigo)) != "MATRICULADO"
               && trim(Situacao($ed60_c_situacao,$ed60_i_codigo)) != "REMATRICULADO") {?>

               <tr>
                <td>
                 <b>Data Saída:</b> <?=db_formatar($datasaida,'d')?>
                 <b>Destino Saída:</b> <?=$destinosaida?>
                </td>
               </tr>

           <?}?>

            <tr>
             <td>
              <?=$Led57_c_descr?> <?=$ed57_c_descr?>
              &nbsp;&nbsp;
              <?=$Led223_i_serie?> <?=$ed11_c_descr?>
              &nbsp;&nbsp;
              <?=$Led57_i_turno?> <?=$ed15_c_nome?>
              &nbsp;&nbsp;
              <?=$Led57_i_calendario?> <?=$ed52_c_descr?> / <?=$ed52_i_ano?>
             </td>
            </tr>
            <tr>
             <td>
              <?=$Led76_i_escola?> <?=$ed76_i_escola==""?"Não Informado":($ed76_i_escola."-".$nomeescola)?>
              &nbsp;&nbsp;
              <?=$Led76_d_data?> <?=$ed76_d_data==""?"Não Informado":db_formatar($ed76_d_data,'d')?>
             </td>
            </tr>
            <tr>
             <td>


              <?php

                /**
                 * @todo realizar melhoria para as classes ArredondamentoNota e ArredondamentoFrequencia para que as
                 * mesmas "enxerge" a escola do aluno
                 */
                $iEscolaSessao    = db_getsession('DB_coddepto');
                $oMatricula       = MatriculaRepository::getMatriculaByCodigo($ed60_i_codigo);
                $iEscolaMatricula = $oMatricula->getTurma()->getEscola()->getCodigo();

                $_SESSION["DB_coddepto"] = $iEscolaMatricula;

                GradeAproveitamentoHTML($ed60_i_codigo, "S", $ed52_i_ano);

                $_SESSION["DB_coddepto"] = $iEscolaSessao;
              ?>
              <?php
              $sCamposAprovCons  = " distinct ed11_c_descr as serie_conselho, ed52_i_ano, ed253_aprovconselhotipo";
              $sCamposAprovCons .= ", ed253_t_obs, ed12_i_codigo, ed11_i_codigo";
              $sWhereAprovCons   = " ed95_i_aluno = {$ed60_i_aluno} and serie.ed11_i_codigo = {$ed11_i_codigo}";
              $oDaoAprovConselho = new cl_aprovconselho();
              $sSqlAprovCons     = $oDaoAprovConselho->sql_query("", $sCamposAprovCons, "ed11_c_descr, ed52_i_ano", $sWhereAprovCons);
              $rsAprovConselho   = $oDaoAprovConselho->sql_record($sSqlAprovCons);
              $iLinhasAprovCons  = $oDaoAprovConselho->numrows;

              $aAprovadoConselhoRegimento = array();

              if ($iLinhasAprovCons > 0) {

                $lGrava = true;
                for ($iContObs = 0; $iContObs < $iLinhasAprovCons; $iContObs++) {

                  $oDadosAprovConselho = db_utils::fieldsmemory($rsAprovConselho, $iContObs);
                  $oEtapa              = EtapaRepository::getEtapaByCodigo( $oDadosAprovConselho->ed11_i_codigo );
                  $iControleFrquencia  = $oMatricula->getTurma()
                                                    ->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa )
                                                    ->getFormaCalculoFrequencia();
                  $oDisciplina         = DisciplinaRepository::getDisciplinaByCodigo( $oDadosAprovConselho->ed12_i_codigo );
                  $sTipoAprovConselho  = "aprovado pelo conselho";

                  if ( !$lGrava && $oDadosAprovConselho->ed253_aprovconselhotipo == 2 ) {
                    continue;
                  }

                  switch ($oDadosAprovConselho->ed253_aprovconselhotipo) {

                    case 2:

                      $sTipoAprovConselho = "reclassificado por baixa frequência";
                      break;
                    case 3:

                      $sTipoAprovConselho = "aprovado pelo regimento escolar";
                      break;
                  }

                  $sStringAprovado  = " <b>-</b> Na etapa {$oDadosAprovConselho->serie_conselho} no ano de ";
                  $sStringAprovado .= " {$oDadosAprovConselho->ed52_i_ano},";
                  $sStringAprovado .= " o aluno foi {$sTipoAprovConselho}";

                  $aTipoConselho = array(1, 3);
                  if(    in_array( $oDadosAprovConselho->ed253_aprovconselhotipo,$aTipoConselho  )
                      || ( $oDadosAprovConselho->ed253_aprovconselhotipo == 2 && $iControleFrquencia == 1 ) ) {
                    $sStringAprovado .= " na disciplina {$oDisciplina->getNomeDisciplina()}";
                  }

                  if (!empty($oDadosAprovConselho->ed253_t_obs)) {
                  	$sStringAprovado .= ". Justificativa: {$oDadosAprovConselho->ed253_t_obs}";
                  }

                  $aAprovadoConselhoRegimento[] = $sStringAprovado;

                  if ( $oDadosAprovConselho->ed253_aprovconselhotipo == 2 && $iControleFrquencia == 2 ) {
                    $lGrava = false;
                  }
                }
              }

              foreach ($aAprovadoConselhoRegimento as $sString) {
              	echo "<p>{$sString}</p>";
              }
              ?>
             </td>
            </tr>
           </table>
           <br>
           <table>
            <tr>
             <td>
              <b>Outras Matrículas:</b>
             </td>
            </tr>
            <tr>
             <td height="1" bgcolor="#000000">
            </td>
           </tr>
           <tr>
            <td>
             <?
             $sCampos = "calendario.ed52_i_ano,escola.ed18_c_nome,ed60_i_codigo, ed60_matricula";
             $sWhere  = " ed60_i_codigo not in($ed60_i_codigo) AND ed60_i_aluno = $chavepesquisa";
             $result2 = $clmatricula->sql_record($clmatricula->sql_query("",
                                                                         $sCampos,
                                                                         "ed60_d_datamatricula desc",
                                                                         $sWhere
                                                                        )
                                                );
             if ($clmatricula->numrows > 0) {

               for ($x = 0; $x < $clmatricula->numrows; $x++) {

                 db_fieldsmemory($result2,$x);
             ?>
                 <a href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=3&ed60_i_codigo=<?=$ed60_i_codigo?>">Matricula nº <?=$ed60_matricula?></a>
                 ->&nbsp;&nbsp;<b>Ano:</b> <?=$ed52_i_ano?>&nbsp;&nbsp;<b>Escola:</b> <?=$ed18_c_nome?>
                 <br>
             <?
               }

             } else {
               echo "Nenhum registro.";
             }
            ?>
            </td>
           </tr>
           <?
           } else {
            ?>
             <tr>
              <td>
               Nenhum registro.
              </td>
             </tr>
            <?
           }
        ?></table>
         </fieldset>
        </td>
       </tr>
    <?}

      if( $evento == 4 ) {

        $sCamposHistoricoMps  = "ed29_c_descr, ed62_i_codigo, ed11_c_descr, ed11_i_codigo, ed62_i_anoref";
        $sCamposHistoricoMps .= ", ed62_i_periodoref, ed18_c_nome, ed11_i_sequencia, ed11_i_ensino, 'REDE' as tipo";

        $sCamposHistoricoMpsFora  = "ed29_c_descr, ed99_i_codigo, ed11_c_descr, ed11_i_codigo, ed99_i_anoref";
        $sCamposHistoricoMpsFora .= ", ed99_i_periodoref, ed82_c_nome, ed11_i_sequencia, ed11_i_ensino, 'FORA' as tipo";

        $sql3  = "SELECT {$sCamposHistoricoMps} ";
        $sql3 .= "  FROM historicomps ";
        $sql3 .= "       inner join serie     on ed11_i_codigo = ed62_i_serie ";
        $sql3 .= "       inner join historico on ed61_i_codigo = ed62_i_historico ";
        $sql3 .= "       inner join cursoedu  on ed29_i_codigo = ed61_i_curso ";
        $sql3 .= "       inner join escola    on ed18_i_codigo = ed62_i_escola ";
        $sql3 .= " WHERE ed61_i_aluno = {$chavepesquisa} ";
        $sql3 .= " UNION ";
        $sql3 .= "SELECT {$sCamposHistoricoMpsFora} ";
        $sql3 .= "  FROM historicompsfora ";
        $sql3 .= "       inner join serie      on ed11_i_codigo = ed99_i_serie ";
        $sql3 .= "       inner join historico  on ed61_i_codigo = ed99_i_historico ";
        $sql3 .= "       inner join cursoedu   on ed29_i_codigo = ed61_i_curso ";
        $sql3 .= "       inner join escolaproc on ed82_i_codigo = ed99_i_escolaproc ";
        $sql3 .= " WHERE ed61_i_aluno = {$chavepesquisa} ";
        $sql3 .= " ORDER BY ed62_i_anoref DESC, ed11_i_sequencia desc";

        $result3 = db_query($sql3);
        $linhas3 = pg_num_rows($result3);
        ?>
        <tr>
          <td valign="top" >
            <fieldset style="background:#f3f3f3;border:2px solid #000000">
              <legend class="cabec">
                <label class="bold">Histórico</label>
              </legend>
              <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0">
                <?php
                if( $linhas3 > 0 ) {

                  $primeiro = "";

                  for( $t = 0; $t < $linhas3; $t++ ) {

                    db_fieldsmemory( $result3, $t );

                    if( $primeiro != $ed29_c_descr ) {

                      ?>
                      <tr>
                        <td class="cabec1">
                          <?=$ed29_c_descr?>
                        </td>
                      </tr>
                      <?php
                      $primeiro = $ed29_c_descr;
                    }

                    if( ( $t == 0 && !isset( $chaveserie ) ) || ( @$chaveserie == $ed62_i_codigo ) ) {
                      $class = "titulo";
                    } else {
                      $class = "aluno";
                    }
                    ?>
                    <tr>
                      <td class="<?=$class?>">
                        <?php
                        if( ( $t == 0 && !isset( $chaveserie ) ) || ( @$chaveserie == $ed62_i_codigo ) ) {

                          ?>
                          Etapa: <?=$ed11_c_descr?>
                          &nbsp;&nbsp;Ano: <?=$ed62_i_anoref?>&nbsp;&nbsp;Escola: <?=$ed18_c_nome?>
                          <?
                        } else {

                          ?>
                          <a class="<?=$class?>" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&chaveserie=<?=$ed62_i_codigo?>&evento=4">
                            Etapa: <?=$ed11_c_descr?>
                          </a>
                          &nbsp;&nbsp;Ano: <?=$ed62_i_anoref?>&nbsp;&nbsp;Escola: <?=$ed18_c_nome?>
                          <?php
                        }
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <?php
                        if( ( $t == 0 && !isset( $chaveserie ) ) || ( @$chaveserie == $ed62_i_codigo ) ) {

                          if( $tipo == "REDE" ) {

                            $campos = "ed65_i_codigo,
                                       ed232_c_descr,
                                       ed65_c_situacao,
                                       case when ed65_c_situacao!='CONCLUÍDO' then '&nbsp;' else ed65_t_resultobtido end as ed65_t_resultobtido,
                                       ed65_c_resultadofinal,
                                       ed65_i_qtdch,
                                       ed65_c_tiporesultado,
                                       ed65_i_historicomps,
                                       ed29_c_descr";
                            $sWhere = "ed65_i_historicomps = {$ed62_i_codigo}";
                            $sSql   = $clhistmpsdisc->sql_query( "", $campos, "ed65_i_ordenacao", $sWhere );
                            $result = $clhistmpsdisc->sql_record( $sSql );

                            if( $result ) {
                            ?>
                              <table width="100%" border="1" cellspacing="0" cellpadding="0">
                                <tr class='titulo' align="center">
                                  <td>Disciplina</td>
                                  <td>Situação</td>
                                  <td>Aprov.</td>
                                  <td>RF</td>
                                  <td>CH</td>
                                  <td>TR</td>
                                </tr>
                                <?php
                                if( $clhistmpsdisc->numrows > 0 ) {

                                  $cor1 = "#f3f3f3";
                                  $cor2 = "#DBDBDB";
                                  $cor  = "";

                                  for( $x =0 ; $x < $clhistmpsdisc->numrows; $x++ ) {

                                    db_fieldsmemory( $result, $x );

                                    if( $cor == $cor1 ) {
                                      $cor = $cor2;
                                    } else {
                                      $cor = $cor1;
                                    }

                                    if( trim( $ed65_c_situacao ) == "AMPARADO" ) {
                                      $ed65_t_resultobtido = "&nbsp;";
                                    }
                                    ?>
                                    <tr height="18" bgcolor="<?=$cor?>">
                                      <td class='aluno'><?=$ed232_c_descr?></td>
                                      <td class='aluno' align="center"><?=$ed65_c_situacao?></td>
                                      <td class='aluno' align="<?=$ed65_c_tiporesultado == 'N' ? 'right' : 'center'?>"><?=$ed65_t_resultobtido?></td>
                                      <td class='aluno' align="center"><?=$ed65_c_resultadofinal == "R" ? "REPROVADO" : "APROVADO"?></td>
                                      <td class='aluno' align="right"><?=$ed65_i_qtdch == "" ? 0 : DBNumber::truncate( $ed65_i_qtdch )?></td>
                                      <td class='aluno' align="center"><?=trim($ed65_c_tiporesultado)?></td>
                                    </tr>
                                    <?php
                                  }
                                } else {

                                  ?>
                                  <tr height="18" bgcolor="#f3f3f3">
                                    <td colspan="6" class="aluno" align="center">
                                      <label>Nenhuma disciplina cadastrada para esta etapa.</label>
                                    </td>
                                  </tr>
                                  <?php
                                }
                                ?>
                              </table>
                              <?php
                            }
                          } else {

                            $campos = "ed100_i_codigo,
                                       ed232_c_descr,
                                       ed100_c_situacao,
                                       case
                                         when ed100_c_situacao != 'CONCLUÍDO' OR ed100_t_resultobtido = ''
                                           then '&nbsp;'
                                           else ed100_t_resultobtido
                                       end as ed100_t_resultobtido,
                                       ed100_c_resultadofinal,
                                       ed100_i_qtdch,
                                       ed100_c_tiporesultado,
                                       ed100_i_historicompsfora,
                                       ed29_c_descr";
                            $sWhere = "ed100_i_historicompsfora = {$ed62_i_codigo}";
                            $sSql   = $clhistmpsdiscfora->sql_query( "", $campos, "ed100_i_ordenacao", $sWhere );
                            $result = $clhistmpsdiscfora->sql_record( $sSql );

                            if( $result ) {
                              ?>
                              <table width="100%" border="1" cellspacing="0" cellpadding="0">
                                <tr class='titulo'>
                                  <td>Disciplina</td>
                                  <td>Situação</td>
                                  <td>Aprov.</td>
                                  <td>RF</td>
                                  <td>CH</td>
                                  <td>TR</td>
                                </tr>
                                <?php
                                if( $clhistmpsdiscfora->numrows > 0 ) {

                                  $cor1 = "#f3f3f3";
                                  $cor2 = "#DBDBDB";
                                  $cor  = "";

                                  for( $x = 0; $x < $clhistmpsdiscfora->numrows; $x++ ) {

                                    db_fieldsmemory( $result, $x );

                                    if( $cor == $cor1 ) {
                                      $cor = $cor2;
                                    } else {
                                      $cor = $cor1;
                                    }

                                    if( trim( $ed100_c_situacao ) == "AMPARADO" ) {
                                      $ed100_t_resultobtido = "&nbsp;";
                                    }
                                    ?>
                                    <tr height="18" bgcolor="<?=$cor?>">
                                      <td class='aluno'><?=$ed232_c_descr?></td>
                                      <td class='aluno'><?=$ed100_c_situacao?></td>
                                      <td class='aluno' align="<?=$ed100_c_tiporesultado == 'N' ? 'right' : 'center'?>"><?=$ed100_t_resultobtido?></td>
                                      <td class='aluno'><?=$ed100_c_resultadofinal == "R" ? "REPROVADO" : "APROVADO"?></td>
                                      <td class='aluno' align="right"><?=$ed100_i_qtdch == "" ? 0 : $ed100_i_qtdch?></td>
                                      <td class='aluno' align="right"><?=trim( $ed100_c_tiporesultado )?></td>
                                    </tr>
                                    <?php
                                  }
                                } else {

                                  ?>
                                  <tr height="18" bgcolor="#f3f3f3">
                                    <td colspan="6" class="aluno" align="center">
                                      <label>Nenhuma disciplina cadastrada para esta etapa.</label>
                                    </td>
                                  </tr>
                                  <?php
                                }
                                ?>
                              </table>
                              <?
                            }
                          }
                        }
                        ?>
                      </td>
                    </tr>
                  <?php
                  }
                } else {
                ?>
                <tr>
                  <td>
                    <label>Nenhum registro.</label>
                  </td>
                </tr>
                <?php
              }
              ?>
              </table>
            </fieldset>
          <?php
          }

      if ($evento == 5) { ?>
        <tr>
         <td valign="top" >
          <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Necessidades Especiais</b></legend>
           <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
           <?
            $result = $clalunonecessidade->sql_record($clalunonecessidade->sql_query("",
                                                                                     "*",
                                                                                     "ed48_c_descr",
                                                                                     " ed214_i_aluno = $chavepesquisa"
                                                                                    )
                                                     );
            if ($clalunonecessidade->numrows > 0) {

            ?>
              <tr>
               <td class="cabec1">
                <table width="100%" cellspacing="0" cellpading="0">
                 <tr>
                  <td width="40%" class="cabec1">
                   Descrição:
                  </td>
                  <td class="cabec1">
                    Necessidade Maior:
                  </td>
                 </tr>
                </table>
               </td>
              </tr>
              <?
              for ($f = 0; $f < $clalunonecessidade->numrows; $f++) {

                db_fieldsmemory($result,$f);
              ?>
                <tr>
                 <td>
                  <table width="100%" cellspacing="0" cellpading="0">
                   <tr>
                    <td width="40%">
                     <?=$ed48_c_descr?>
                    </td>
                    <td>
                     <?=$ed214_c_principal?>
                    </td>
                   </tr>
                  </table>
                 </td>
                </tr>
                <?
              }
            } else {

            ?>
              <tr>
               <td>
                Nenhum registro.
               </td>
              </tr>
            <?
            }
            ?>
           </table>
          </fieldset>
         </td>
        </tr>
    <?}
      if ($evento == 6) {

        if (!isset($ordem)) {
          $ordem = "ASC";
        }
        ?>
        <tr>
         <td valign="top" >
          <fieldset style="background:#f3f3f3;border:2px solid #000000">
           <legend class="cabec"><b>Movimentação Escolar - Ordem: <select name="ordem"
                   onchange="location.href='edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=6&ordem='+this.value"
                   style="height:15px;font-size:9px;"><option value="ASC"
                   <?=$ordem=="ASC"?"selected":""?>>Crescente</option><option value="DESC"
                   <?=$ordem=="DESC"?"selected":""?>>Decrescente</option></select></b></legend>
            <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
             <?
              $array_mov = array();
              $sCamposResult  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
              $sCamposResult .= " ed52_i_ano,ed11_c_descr,ed229_c_procedimento, ed60_matricula,";
              $sCamposResult .= " ed57_c_descr,ed229_t_descr,id_usuario||' / '||nome as nome";
              $sOrderResult   = "ed229_d_dataevento $ordem,ed229_i_codigo $ordem";
              $sWhereResult   = " ed60_i_aluno = $chavepesquisa AND ed229_c_procedimento NOT LIKE  'CANCELAR ENCERRAMENTO%'";
              $sWhereResult  .= " AND ed229_c_procedimento NOT LIKE  'ENCERRAR%'";
              $result         = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                                       $sCamposResult,
                                                                                       $sOrderResult,
                                                                                       $sWhereResult
                                                                                      )
                                                           );
              if ($clmatriculamov->numrows > 0) {

                for ($f = 0; $f < $clmatriculamov->numrows; $f++) {

                  db_fieldsmemory($result,$f);
                  $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
                  $iContador = count($array_mov)-1;
                  $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d');
                  $array_mov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
                  $array_mov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
                  $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;
                }
              }

              $sCamposResult1  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo, ";
              $sCamposResult1 .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr, ";
              $sCamposResult1 .= " ed60_matricula, id_usuario||' / '||nome as nome ";
              $sOrderResult1   = " ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1";
              $sWhereResult1   =  " ed60_i_aluno = $chavepesquisa AND ed229_c_procedimento LIKE  'CANCELAR ENCERRAMENTO%'";
              $result1         = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                                        $sCamposResult1,
                                                                                        $sOrderResult1,
                                                                                        $sWhereResult1
                                                                                       )
                                                            );

              if ($clmatriculamov->numrows > 0) {

                db_fieldsmemory($result1,0);
                $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
                $iContador = count($array_mov)-1;
                $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d');
                $array_mov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
                $array_mov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
                $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;

              }

              $sCamposResult2  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
              $sCamposResult2 .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento, ed60_matricula,";
              $sCamposResult2 .= " ed229_t_descr,id_usuario||' / '||nome as nome" ;
              $sOrderResult2   = " ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1 ";
              $sWhereResult2   = " ed60_i_aluno = $chavepesquisa AND ed229_c_procedimento LIKE  'ENCERRAR%' ";
              $result2         = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                                        $sCamposResult2,
                                                                                        $sOrderResult2,
                                                                                        $sWhereResult2
                                                                                       )
                                                            );
              if ($clmatriculamov->numrows > 0) {

                db_fieldsmemory($result2,0);
                $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
                $iContador = count($array_mov)-1;
                $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d');
                $array_mov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
                $array_mov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
                $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;
              }

              if ($ordem == "DESC") {
                $array_ordem = SORT_DESC;
              } else {
                $array_ordem = SORT_ASC;
              }
              array_multisort($array_mov,$array_ordem);
              if (count($array_mov) > 0) {

              ?>
                <tr class="titulo" align="center">
                 <td>Data</td>
                 <td>Escola</td>
                 <td>Matr.</td>
                 <td>Turma</td>
                 <td>Ano</td>
                 <td>Etapa</td>
                 <td>Procedimento</td>
                </tr>

              <?
                for ($f = 0; $f < count($array_mov); $f++) {

                  $array_mov1 = explode("|",$array_mov[$f]);
                  $array_mov2 = explode("#",$array_mov1[1]);

                  if ($f > 0) {
                  ?>
	                 <tr><td height="1" bgcolor="black" colspan="7"></td></tr>
                  <?
                  }
                  ?>
                  <tr bgcolor="#dbdbdb">
                   <td class="aluno2" align="center"><?=$array_mov2[0]?></td>
                   <td class="aluno2"><?=$array_mov2[1]?></td>
                   <td class="aluno2" align="center"><?=$array_mov2[2]?></td>
                   <td class="aluno2" align="center"><?=$array_mov2[3]?></td>
                   <td class="aluno2" align="center"><?=$array_mov2[4]?></td>
                   <td class="aluno2" align="center"><?=$array_mov2[5]?></td>
                   <td class="aluno2"><?=$array_mov2[6]?></td>
	              </tr>
	              <tr>
                   <td>&nbsp;</td>
                    <td bgcolor="#f3f3f3" colspan="6" class="aluno2">
                      <table width="100%" cellspacing="0" cellpading="0">
                       <tr>
                         <td width="60%">
                           <?=$array_mov2[7]?>
                         </td>
                         <td align="right" valign="top">
                           <b>Usuário: </b><?=$array_mov2[8]?>
                         </td>
                        </tr>
                       </table>
                      </td>
                     </tr>
                    <?
                }
              } else {
              ?>
                <tr>
                 <td>
                  Nenhum registro.
                 </td>
                </tr>
              <?
              }
              ?>
            </table>
            <?
            $result_log = $cllogmatricula->sql_record($cllogmatricula->sql_query("",
                                                                                 "*",
                                                                                 "ed248_d_data,ed248_c_hora",
                                                                                 " ed248_i_aluno = $chavepesquisa"
                                                                                )
                                                     );
            if ($cllogmatricula->numrows > 0) {

            ?>
              <br>
              <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0" style="border:2px solid #999999">
               <tr>
                <td colspan="2">
                 <b>Outras Movimentações:</b>
                </td>
               </tr>
               <tr>
                <td colspan="2" height="1" bgcolor="#999999">
                </td>
               </tr>
            <?for ($q = 0; $q < $cllogmatricula->numrows; $q++) {

                db_fieldsmemory($result_log,$q);
            ?>
                <tr>
                 <td colspan="2">
                 <?
                  if ($ed248_c_tipo == "E") {
                    $descrlog = "Matrícula Excluída";
                  } else if ($ed248_c_tipo == "R") {
                    $descrlog = "Reativação de Matrícula";
                  } else if ($ed248_c_tipo == "T") {
                    $descrlog = "Cancelamento de Transferência";
                  }
                  ?>
                  <b><?=$descrlog?></b>
                 </td>
                </tr>
                <tr>
                 <td width="10"></td>
                  <td>
                   <?=$ed248_t_origem?><br>
                   <?=$ed248_t_obs?><br>
                   Data/Hora: <?=db_formatar($ed248_d_data,'d')."&nbsp;&nbsp;".$ed248_c_hora?>&nbsp;&nbsp;&nbsp;
                   Usuário: <?=$nome?><br>
                   <?=$ed248_c_tipo=="E"?"Motivo: ".($ed249_c_motivo==""?"Não Informado":$ed249_c_motivo):""?><br>
                   <?=trim($ed248_t_obs)!=""?"Observações: $ed248_t_obs":""?>
                  </td>
                 </tr>
              <?
              }
              ?>
             </table>
            <?
            }
            ?>
        </fieldset>
       </td>
      </tr>
    <?
      }
      if ($evento == 7) {
    ?>
      <tr>
        <td valign="top">
          <fieldset>
            <legend><b>Consulta de Faltas</b></legend>
            <div id='ctnConsultaFaltas'>
            </div>
          </fieldset>
        </td>
      </tr>
    <?
      }
    ?>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<script>
var sUrlRPC      = 'edu04_controleacessofrequencia.RPC.php';
var iCodigoAluno = <?=$chavepesquisa;?>;

function js_init() {

  js_gridFaltas();
  js_pesquisaFaltas();
}

/**
 * Montamos a grid das faltas do aluno
 */
function js_gridFaltas() {

	oDataGridFaltas              = new DBGrid("gridFaltas");
	oDataGridFaltas.nameInstance = "oDataGridFaltas";
	oDataGridFaltas.setHeader(new Array("Data da Falta", "Total de Faltas", "Disciplinas"));
	oDataGridFaltas.setCellAlign(new Array("center", "center", "left"));
	oDataGridFaltas.setCellWidth(new Array("20%", "10%", "70%"));
	oDataGridFaltas.show($('ctnConsultaFaltas'));
}

/**
 * Pesquisamos as faltas do aluno
 */
function js_pesquisaFaltas() {

	var oParametro          = new Object();
	oParametro.exec         = 'getFaltasAluno';
	oParametro.iCodigoAluno = iCodigoAluno;

	var oAjax = new Ajax.Request(sUrlRPC,
			                         {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornaPesquisaFaltas
                               }
			                        );
}

/**
 * Retornamos os dados da pesquisa pelas faltas do aluno
 */
function js_retornaPesquisaFaltas(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');

	if (oRetorno.status == 1) {

		oDataGridFaltas.clearAll(true);
		oRetorno.aFaltas.each(function(oFalta, iSeq) {

			var aLinha = new Array();
			aLinha[0]  = oFalta.dtFalta.urlDecode();
			aLinha[1]  = oFalta.iTotalFaltas;
			aLinha[2]  = oFalta.sDisciplinas.urlDecode();
			oDataGridFaltas.addRow(aLinha);
	  });
	  oDataGridFaltas.renderRows();
	}
}

js_init();
</script>