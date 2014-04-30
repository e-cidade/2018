<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("fpdf151/scpdf.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_regenteconselho_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_procavaliacao_classe.php");
require_once("classes/db_pareceraval_classe.php");
require_once("classes/db_aprovconselho_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_escola_classe.php");

require_once("libs/db_utils.php");
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("model/educacao/DBEducacaoTermo.model.php");
require_once("libs/db_libdocumento.php");
require_once("dbforms/db_funcoes.php");

$clmatricula       = new cl_matricula;
$clturma           = new cl_turma;
$clregenteconselho = new cl_regenteconselho;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clprocavaliacao   = new cl_procavaliacao;
$clpareceraval     = new cl_pareceraval;
$claprovconselho   = new cl_aprovconselho;
$clDBConfig        = new cl_db_config();
$clEscola          = new cl_escola();
$oDadosGrade       = null;

$escola   = db_getsession("DB_coddepto");
$result00 = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                "ed57_i_codigo as turma",
                                                                "",
                                                                " ed220_i_codigo = $turma"
                                                               )
                                );
db_fieldsmemory($result00,0);

$result = $clmatricula->sql_record($clmatricula->sql_query("",
                                                           "*",
                                                           "ed60_i_numaluno,to_ascii(ed47_v_nome)",
                                                           " ed60_i_codigo in ($alunos) AND ed60_i_turma = $turma"
                                                          )
                                  );
if ($clmatricula->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma matrícula para a turma selecionada<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
 <?
  exit;

}

$result_per = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("",
                                                                       "ed09_c_descr as periodoselecionado,ed41_i_sequencia as seqatual",
                                                                       "",
                                                                       " ed41_i_codigo = $periodo"
                                                                      )
                                          );
  db_fieldsmemory($result_per,0);
  /**
   * Dados Instituição
   */
  $sCamposInstit   = "nomeinst as nome,ender,munic,uf,telef,email,url,logo";
  $sSqlDadosInstit = $clDBConfig->sql_query_file(db_getsession('DB_instit'),$sCamposInstit);
  $rsDadosInstit   = db_query($sSqlDadosInstit);
  $oDadosInstit    = db_utils::fieldsMemory($rsDadosInstit,0);
  $url             = $oDadosInstit->url;
  $nome            = $oDadosInstit->nome;
  $sLogoInstit     = $oDadosInstit->logo;
  $munic           = $oDadosInstit->munic;


  /**
   * Dados Escola
   */
  $sCamposEscola   = "ed18_i_codigo,ed18_c_nome,j14_nome,ed18_i_numero,j13_descr,ed261_c_nome,ed260_c_sigla, ";
  $sCamposEscola  .= "ed18_c_email,ed18_c_logo";
  $sSqlDadosEscola = $clEscola->sql_query_dados(db_getsession("DB_coddepto"),$sCamposEscola);
  $rsDadosEscola   = db_query($sSqlDadosEscola);
  $oDadosEscola    = db_utils::fieldsMemory($rsDadosEscola,0);
  $sNomeEscola     = $oDadosEscola->ed18_c_nome;
  $sLogoEscola     = $oDadosEscola->ed18_c_logo;
  $iCodigoEscola   = $oDadosEscola->ed18_i_codigo;
  $ruaescola       = $oDadosEscola->j14_nome;
  $numescola       = $oDadosEscola->ed18_i_numero;
  $bairroescola    = $oDadosEscola->j13_descr;
  $cidadeescola    = $oDadosEscola->ed261_c_nome;
  $estadoescola    = $oDadosEscola->ed260_c_sigla;
  $emailescola     = $oDadosEscola->ed18_c_email;

  $sSqlTelefoneEscola = $clEscola->sql_query_telefone("","ed26_i_numero,ed26_i_ddd","","ed26_i_escola= $escola");
  $rsTelefoneEscola   = db_query($sSqlTelefoneEscola);
  $oTelefoneEscola    = db_utils::fieldsMemory($rsTelefoneEscola,0);
  $iTelefoneEscola    = $oTelefoneEscola->ed26_i_numero;
  $iDTelefone         = $oTelefoneEscola->ed26_i_ddd;

  $DadosCabecalho = $sNomeEscola. " (".$iDTelefone.")" .$iTelefoneEscola;
  $result6 = @$clregenteconselho->sql_record($clregenteconselho->sql_query("",
                                                                         "cgmrh.z01_nome as conselheiro",
                                                                         "",
                                                                         " ed235_i_turma = $turma"
                                                                        )
                                          );
 if ($clregenteconselho->numrows>0) {
   db_fieldsmemory($result6,0);
 } else {
   $conselheiro = "";
 }

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->ln(5);

if ( strlen($nome) > 42 || strlen($sNomeEscola) > 42 ) {
  $TamFonteNome = 8;
} else {
  $TamFonteNome = 9;
}

$y1         = 9;
$y2         = 14;
$y3         = 18;
$y4         = 22;
$y5         = 26;
$y6         = 30;
$y7         = 6;
$y8         = 6;
$y9         = 35;
$y10        = 33;
$y11        = 63;
$y12        = 3;
$y13        = 12;
$y14        = 35;
$m0         = 9;
$m1         = 14;
$m2         = 18;
$m3         = 22;
$m4         = 26;
$m5         = 30;
$m6         = 33;
$m7         = 3;
$m8         = 5;
$a          = 5;
$f          = 195;
$r          = 33;
$alturahead = $pdf->sety(6);

for ($x = 0; $x < $clmatricula->numrows; $x++) {

  db_fieldsmemory($result,$x);
  $head1    = "BOLETIM DE DESEMPENHO $periodoselecionado";
  $head2    = "Nome: $ed47_v_nome";
  $head3    = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head4    = "Código Aluno: $ed47_i_codigo  Matrícula: $ed60_i_codigo";
  $head5    = "Etapa: $ed11_c_descr Ano: $ed52_i_ano";
  $head6    = "Turma: $ed57_c_descr";
  $situacao = trim($ed60_c_concluida) == "S" &&
              trim($ed60_c_situacao) != 'AVANÇADO' &&
              trim($ed60_c_situacao) != 'CLASSIFICADO'?"CONCLUÍDO":Situacao($ed60_c_situacao,$ed60_i_codigo);
  $head7 = "Situação: $situacao";

  if (($x%2) == 0 || $pdf->getAvailHeight() < (($pdf->h / 2)-20)) {

    $pdf->addpage('P');
    $y1         =  9;     //$y1  9      $y1
    $y2         = 14;     //$y2  14     $y2
    $y3         = 18;     //$y3  18     $y3
    $y4         = 22;     //$y4  22     $y4
    $y5         = 26;     //$y5  26     $y5
    $y6         = 30;     //$y6  30     $y6
    $y7         =  6;     //$y7  6      $y7
    $y8         =  6;     //$y8  5      $y8 -1
    $y9         = 35;     //$y9  35
    $y10        = 33;     //$y10 33
    $y11        = 63;     //$y11 63
    $y12        =  3;     //$y12 3
    $y13        = 12;     //$y13 12
    $y14        = 30;     //$y14 30
    $m0         = 9;      //$m0  160;   $m0 + 150;
    $m1         = 14;     //$m1  163;   $m1 + 150;
    $m2         = 18;     //$m2  167;   $m2 + 150;
    $m3         = 22;     //$m3  170;   $m3 + 150;
    $m4         = 26;     //$m4  173;   $m4 + 150;
    $m5         = 30;     //$m5  176;   $m5 + 150;
    $m6         = 33;     //$m6  180;   $m6 + 150;
    $m7         = 3;      //$m7         $m7;
    $m8         = 5;      //$m8  152;   $m8 + 150;
    $a          = 5;      //$a   5
    $f          = 195;    //$f   195;
    $r          = 33;     //$r   33;
    $alturahead = $pdf->SetY(6);

  }

  $margemesquerda = $pdf->lMargin;


  $pdf->setfillcolor(223);
  $pdf->SetFont('arial','b',6);
  $pdf->SetY($y8);
  $oLibDocumento = new libdocumento(5001,null);

  if ( $oLibDocumento->lErro ){
    db_redireciona("db_erros.php?fechar=true&db_erro={$oLibDocumento->sMsgErro}");
  }

  $aParagrafo = $oLibDocumento->getDocParagrafos();

  foreach ($aParagrafo as $oParagrafo ) {
    eval($oParagrafo->oParag->db02_texto);
  }


  $pdf->setleftmargin($margemesquerda);


 if ($grade == "yes") {
   $oDadosGrade =  GradeAproveitamentoBOLETIM($ed60_i_codigo,195,$pdf,$niveis,"P",$seqatual, "N", "S");
 }
 $sWhere     = " ed95_i_aluno = $ed60_i_aluno AND ed59_i_turma = $turma AND ed72_i_procavaliacao = $periodo";
 $result_obs = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",
                                                                            "ed72_t_obs",
                                                                            "",
                                                                            $sWhere
                                                                           )
                                             );
 if ($cldiarioavaliacao->numrows > 0) {
   db_fieldsmemory($result_obs, 0);
 } else {
   $ed72_t_obs = "";
 }
 $impresso = false;
 if ($padrao == "yes") {

   $sWhere     = " ed95_i_aluno = $ed60_i_aluno AND ed72_i_procavaliacao = $periodo AND ed59_i_turma = $turma";
   $result_par =  $clpareceraval->sql_record($clpareceraval->sql_query("",
                                                                       "ed93_t_parecer",
                                                                       "ed93_i_codigo",
                                                                       $sWhere
                                                                      )
                                            );
   if ($clpareceraval->numrows > 0) {

     $pdf->setfont('arial','b',7);
     $pdf->cell(195 ,4,"Parecer $periodoselecionado:",1,1,"L",1);
     $pdf->setfont('arial','',7);
     if ($padraotipo == "L") {
       $pdf->cell(195,4,"Seq - Parecer => Legenda",1,1,"L",0);
     }
     $seq = "";
     $sep = "";
     $parpadrao = "";
     $seppadrao = "";
     for ($g = 0; $g < $clpareceraval->numrows; $g++) {

       db_fieldsmemory($result_par,$g);
       if (!strstr($seq,"#".$ed93_t_parecer."#")) {

         $parpadrao .= $seppadrao.$ed93_t_parecer;
         $seq .= $sep."#".$ed93_t_parecer."#";
         $sep = ",";
         $seppadrao = "    ";

         if ($padraotipo == "L") {

           $explode_parecer = explode("**",$ed93_t_parecer);
           for ($b = 0; $b < count($explode_parecer); $b++) {
             $pdf->cell($oDadosGrade->nLarguraGrade,4,trim($explode_parecer[$b]),1,1,"L",0);
           }

         }
       }
     }
     if ($padraotipo == "C") {
       $pdf->multicell($oDadosGrade->nLarguraGrade,4,str_replace("**","  ",$parpadrao),1,"L",0,0);
     }
     $impresso = true;
   }
 }
 if ($descritivo == "yes") {

   $sWhere          = " ed95_i_aluno = $ed60_i_aluno AND ed59_i_turma = $turma ";
   $sWhere         .= "AND ed72_i_procavaliacao = $periodo AND ed72_t_parecer != ''";
   $result_pardescr = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",
                                                                                   "DISTINCT ed72_t_parecer as pardescr",
                                                                                   "",
                                                                                   $sWhere
                                                                                  )
                                                    );
   if ($cldiarioavaliacao->numrows > 0) {

     $pardescr = trim(pg_result($result_pardescr,0,'pardescr'));
     if ($pardescr != "") {

       $pdf->setfont('arial','b',7);
       $pdf->cell($oDadosGrade->nLarguraGrade,4,"Parecer $periodoselecionado:",1,1,"L",1);
       $pdf->setfont('arial','',7);

       for($g = 0; $g < $cldiarioavaliacao->numrows; $g++) {

         db_fieldsmemory($result_pardescr,$g);
         $pdf->multicell($oDadosGrade->nLarguraGrade,4,$pardescr,1,"L",0,0);

       }
       $impresso = true;

     }
   }
 }
 $obs2 = "";
 if(isset($boletim_convencao)){
	 if (trim($boletim_convencao) != "") {

	   $arr_convencao = str_replace("{","",$boletim_convencao);
	   $arr_convencao = str_replace("}","",$arr_convencao);
	   $troca         = chr(34).",".chr(34);
	   $arr_convencao = str_replace($troca," - ",$arr_convencao);
	   $arr_convencao = str_replace(chr(34),"",$arr_convencao);
	   $obs2          = $arr_convencao;

	 }
 }
 $obs3         = "";
 $sCampos      = "cgmrh.z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed59_i_ordenacao,";
 $sCampos     .= "ed122_sequencial,ed122_descricao";
 $sWhere       = " ed95_i_aluno = $ed60_i_aluno AND ed59_i_turma = $turma AND ed59_i_serie = $ed221_i_serie";
 $result_cons  = @$claprovconselho->sql_record($claprovconselho->sql_query("",
                                                                         $sCampos,
                                                                         "ed59_i_ordenacao",
                                                                         $sWhere
                                                                        )
                                            );
 if ($claprovconselho->numrows > 0) {

   $sepobs = "";
   for ($g = 0; $g < $claprovconselho->numrows; $g++) {

     db_fieldsmemory($result_cons,$g);

     if ($ed122_sequencial == 1) {
       $obs3  .= $sepobs."-Disciplina $disc_conselho: Aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs";
     } else {
       $obs3  .= $sepobs."-Disciplina $disc_conselho: Aprovado conforme regimento escolar. Justificativa: $ed253_t_obs";
     }
     $sepobs = "\n";
   }
 }

 if (trim($ed60_t_obs) != ""
     || trim($ed72_t_obs) != ""
     || trim(str_replace(chr(92),"",$obs1)) != ""
     || trim($obs2) != ""
     || trim($obs3) != "") {

   $pdf->setfont('arial','b',7);
   $pdf->cell($oDadosGrade->nLarguraGrade,4,"Observações / Mensagens",1,1,"L",1);
   $pdf->setfont('arial','',7);

   $ed60_t_obs = substr($ed60_t_obs, 0, 270);
   $ed72_t_obs = substr($ed72_t_obs, 0, 270);
   $obs2 = substr($obs2, 0, 350);
   $obs3 = substr($obs3, 0, 610);

   $pdf->multicell($oDadosGrade->nLarguraGrade,4,($ed60_t_obs!=""?$ed60_t_obs."\n":"").($ed72_t_obs!=""?$ed72_t_obs."\n":"").
                         (str_replace(chr(92),"",$obs1)!=""?str_replace(chr(92),"",$obs1)."\n":"").
                         ($obs2!=""?$obs2."\n":"").($obs3!=""?$obs3."\n":""),1,"L",0,0);
   $impresso = true;

 }
 if ($impresso == false) {

   $pdf->cell($oDadosGrade->nLarguraGrade, 4,"TF - Total Faltas | ".(trim($ed57_c_medfreq) == "PERÌODOS"?"AD - Aulas Dadas":"DL - Dias Letivos").
                                                                                  " | FA - Faltas Abonadas",1,1,"L",0);

 } else {

   $pdf->cell($oDadosGrade->nLarguraGrade, 4,"TF - Total Faltas | ".(trim($ed57_c_medfreq)=="PERÌODOS"?"AD - Aulas Dadas":"DL - Dias Letivos").
                                                                             " | FA - Faltas Abonadas","LRB",1,"L",0);

 }
 if ($assinaturaregente == "S") {

    $pdf->cell($oDadosGrade->nLarguraGrade,7,"__________________________________________________","LR",1,"C",0);
    $pdf->cell($oDadosGrade->nLarguraGrade,3,"Professor Conselheiro  ".$conselheiro,"LBR",1,"C",0);

  } else {

    $pdf->cell($oDadosGrade->nLarguraGrade,7,"","LR",1,"C",0);
    $pdf->cell($oDadosGrade->nLarguraGrade,3,"","LBR",1,"C",0);

  }

 if ($grade == "yes") {
   //$pdf->line(10,$y14,205,$y14);
 }

 $iAdicionalSegundoBoletim = $pdf->GetY();
 
 
 if ($pdf->GetY() < $pdf->h / 2) {
  $iAdicionalSegundoBoletim = $pdf->h / 2;
 } 
 $y1         += $iAdicionalSegundoBoletim;
 $y2         += $iAdicionalSegundoBoletim;
 $y3         += $iAdicionalSegundoBoletim;
 $y4         += $iAdicionalSegundoBoletim;
 $y5         += $iAdicionalSegundoBoletim;
 $y6         += $iAdicionalSegundoBoletim;
 $y7         += $iAdicionalSegundoBoletim;
 $y8         += $iAdicionalSegundoBoletim;
 $y9         += $iAdicionalSegundoBoletim;
 $y10        += $iAdicionalSegundoBoletim;
 $y11        += $iAdicionalSegundoBoletim;
 $y12        += $iAdicionalSegundoBoletim;
 $y13        += $iAdicionalSegundoBoletim;
 $y14        += $iAdicionalSegundoBoletim;
 $m0         += $iAdicionalSegundoBoletim;
 $m1         += $iAdicionalSegundoBoletim;
 $m2         += $iAdicionalSegundoBoletim;
 $m3         += $iAdicionalSegundoBoletim;
 $m4         += $iAdicionalSegundoBoletim;
 $m5         += $iAdicionalSegundoBoletim;
 $m6         += $iAdicionalSegundoBoletim;
 //$m7          = $m7;//Não Muda
 $m8         += $iAdicionalSegundoBoletim;
 $a          += $iAdicionalSegundoBoletim;
// $f          = $f;//Não muda
// $r          = $r;//Não muda
 $alturahead = $pdf->SetY($pdf->GetY());
}

$pdf->Output();
?>