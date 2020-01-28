<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_sql.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_solicitafolha.php");
include("classes/db_rhempfolha_classe.php");
include("classes/db_rhelementoemp_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhempfolha       = new cl_rhempfolha;
$clrhelementoemp    = new cl_rhelementoemp;
$clrhsolicita       = new cl_rhsolicita;

$clsolicita         = new cl_solicita;
$clsolicitem        = new cl_solicitem;
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemele     = new cl_solicitemele;
$clsolicitemunid    = new cl_solicitemunid;

// Se houver dotacao (rh40_coddot)
$clpcdotac          = new cl_pcdotac;
$clorcreserva       = new cl_orcreserva;
$clorcreservasol    = new cl_orcreservasol;

$clpcorcam          = new cl_pcorcam;
$clpcorcamitem      = new cl_pcorcamitem;
$clpcorcamitemsol   = new cl_pcorcamitemsol;
$clpcorcamforne     = new cl_pcorcamforne;
$clpcorcamval       = new cl_pcorcamval;
$clpcorcamjulg      = new cl_pcorcamjulg;

global $numrows_confirma;

function db_gerarsolicitacao($mostra=false){
  if ($mostra==true) {
    echo "if (confirm('Já foram geradas solicitações para este ano/mês. Reprocessar?')){\n";
    echo "  return true\n";
    echo "} else {\n";
    echo "  document.location.href='pes1_rhempgerasolfolha001.php';";
    echo "  return false\n";
    echo "}\n";
  } else {
    echo "return true\n";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center">
  <tr>
    <td>
    <?
$numrows_confirma = 0;
$mostra           = false;
if ((isset($DBtxt23)&&trim(@$DBtxt23)!="") &&
(isset($DBtxt25)&&trim(@$DBtxt25)!="") &&
(isset($ponto)  &&trim(@$ponto)  !="")) {
  if (!isset($rh40_sequencia)||$rh40_sequencia=="") {
    $rh40_sequencia = '0';
  }
  
  $ano = $DBtxt23;
  $mes = $DBtxt25;
  $sequencia = '';
  $rh40_tipo = 'n';

  if ($ponto == 's') {
    $siglaarq = 'r14';

    $pc10_resumo = "Salário - ";
  } else if ($ponto == 'c') {
    $sequencia = " and r48_semest = $rh40_sequencia ";
    $siglaarq  = 'r48';

    $pc10_resumo = "Complementar - Seq. $rh40_sequencia - ";
  } else if ($ponto == 'a') {
    $siglaarq  = 'r22';

    $pc10_resumo = "Adiantamento - ";
  } else if ($ponto == 'r') {
    $siglaarq  = 'r20';

    $pc10_resumo = "Rescisão - ";
  } else if ($ponto == 'd') {
    $siglaarq  = 'r35';

    $pc10_resumo = "13o. Salário - ";
  } else if ($ponto == 'f') {
    $siglaarq  = 'r31';

    $pc10_resumo = "Férias - ";
  }

  $pc10_resumo .= $ano."/".$mes;

  $campos  = "rh40_anousu,rh40_mesusu,rh40_projativ,rh40_recurso,rh40_codele,rh40_instit,rh40_coddot,
              sum(rh40_provento) as rh40_provento, sum(rh40_desconto) as rh40_desconto";
  $ordem   = "";
  $agrupar = "group by rh40_anousu,rh40_mesusu,rh40_projativ,rh40_codele,rh40_recurso,rh40_coddot,rh40_instit";
  if (isset($gerado) && trim(@$gerado)!="") {
    if ($gerado == "O") {
      // Por orgao
      $campos  .= ",rh40_orgao";
      $ordem   .= "rh40_orgao";
      $agrupar .= ",rh40_orgao";
    }
    
    if ($gerado == "U") {
      // Por unidade
      $campos  .= ",rh40_unidade,rh40_orgao";
      $ordem   .= "rh40_orgao,rh40_unidade";
      $agrupar .= ",rh40_orgao,rh40_unidade";
    }
  }

  if ($ordem != ""){
    $ordem   .= ",";
  }

  $ordem .= "rh40_codele,rh40_coddot";
  
  $result_confirma  = $clrhempfolha->sql_record($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,null,null,$campos,$ordem,"rh40_anousu    = $ano and
                                                                                                                                                rh40_mesusu    = $mes and
                                                                                                                                                rh40_sequencia = $rh40_sequencia and 
                                                                                                                                                rh40_siglaarq  = '$siglaarq' and 
                                                                                                                                               rh40_instit    = ".db_getsession("DB_instit")." ".$agrupar));

/*  
  echo($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,null,null,$campos,$ordem,"rh40_anousu    = $ano and
                                                                                                       rh40_mesusu    = $mes and
                                                                                                       rh40_sequencia = $rh40_sequencia and 
                                                                                                       rh40_siglaarq  = '$siglaarq' and 
                                                                                                       rh40_instit    = ".db_getsession("DB_instit")." ".$agrupar));
  exit;
*/  

  $numrows_confirma = $clrhempfolha->numrows;

  $clrhsolicita->sql_record($clrhsolicita->sql_query_file(null,"*",null,"rh33_anousu   = $ano and
                                                                         rh33_mesusu   = $mes and
                                                                         rh33_seqfolha = $rh40_sequencia and 
                                                                         rh33_siglaarq = '$siglaarq' and
                                                                         rh33_instit   = ".db_getsession("DB_instit")));
/*
  echo($clrhsolicita->sql_query_file(null,"*",null,"rh33_anousu   = $ano and
                                                    rh33_mesusu   = $mes and
                                                    rh33_seqfolha = $rh40_sequencia and 
                                                    rh33_siglaarq = '$siglaarq' and
                                                    rh33_instit   = ".db_getsession("DB_instit"))); exit;
*/                                                      

  $numrows_rhsolicita = $clrhsolicita->numrows;

  if ($numrows_rhsolicita > 0) {
    $mostra = true;
  } else {
    $mostra = false;
  }
}

include("forms/db_frmrhempgerasolfolha.php");
    ?>
    </td>
  </tr>
  <tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

$passa = false;
if (isset($confirma) || isset($gera)) {
  if (!isset($confirma)) {
    if ($numrows_confirma > 0) {
      $passa = true;
    } else {
      $erro_msg = "Não existem dados neste periodo para gerar solicitações";
      $sqlerro  = true;
      $confirma = "confirma";
    }
  }
  
  $sqlerro  = false;
  $erro_msg = "";
  if (isset($passa) && $passa == true) {
    db_inicio_transacao();

//    db_criatabela($result_confirma); exit;
    
    if ($numrows_confirma > 0) {
      // Se existir, excluir solicitações caso, exista alguma já com processo de compras cancela
      $cod_sol = "";
      for ($i = 0; $i < $numrows_confirma; $i++) {
        db_fieldsmemory($result_confirma,$i);
        
        $res_solicita = $clrhsolicita->sql_record($clrhsolicita->sql_query_pcproc(null,"pc10_numero,pc80_codproc",null,"rh33_anousu   = $ano and
                                                                                                                        rh33_mesusu   = $mes and
                                                                                                                        rh33_siglaarq = '$siglaarq' and
                                                                                                                        rh33_seqfolha = $rh40_sequencia  and
                                                                                                                        rh33_instit   = ".db_getsession("DB_instit")." and
                                                                                                                        pc80_codproc is not null"));
        if ($clrhsolicita->numrows > 0) {
          db_fieldsmemory($res_solicita,0);
          
          if ($cod_sol != $pc10_numero) {
            $erro_msg .= "Solicitação ".$pc10_numero." incluida no processo de compras ".$pc80_codproc.".\\n";
            $cod_sol   = $pc10_numero;
          }
          $sqlerro   = true;
        }
      }
      
      if ($sqlerro == true) {
        $erro_msg .= "\\nProcessamento cancelado.";
      }
    }
    
    if ($sqlerro == false) {
      $arr_solicitacoes  = array(array("solicitacao"));
      $cont_solicitacoes = 0;

      $res_solicita = $clrhsolicita->sql_record($clrhsolicita->sql_query_file(null,"rh33_solicita",null,"rh33_anousu   = $ano and
                                                                                                         rh33_mesusu   = $mes and
                                                                                                         rh33_siglaarq = '$siglaarq' and
                                                                                                         rh33_seqfolha = $rh40_sequencia and
                                                                                                         rh33_instit   = ".db_getsession("DB_instit")));
      $numrows_solicita = $clrhsolicita->numrows;

      if ($numrows_solicita > 0) {
        for ($i = 0; $i < $numrows_solicita; $i++){
          db_fieldsmemory($res_solicita,$i);
          
          $arr_solicitacoes[$cont_solicitacoes]["solicitacao"] = $rh33_solicita;
          $cont_solicitacoes++;
        }

        $solicitacao      = "";
        $virgula          = "";
        for ($i = 0; $i < $cont_solicitacoes; $i++) {
          $sqlerro = db_exclusao_solicitacao($arr_solicitacoes[$i]["solicitacao"]);

          $solicitacao .= $virgula.$arr_solicitacoes[$i]["solicitacao"];
          $virgula      = ",";

          if ($sqlerro == true){
            break;
          }
        }

        if ($sqlerro == true) {
          $erro_msg = "Erro ao excluir solicitações($solicitacao) do periodo selecionado. Verifique.";
        }
      }
    }

    if ($sqlerro == false) {
      $codele  = "";
      $virgula = "";
      for ($i = 0; $i < $numrows_confirma; $i++) {
        db_fieldsmemory($result_confirma,$i);
        
        $codele  .= $virgula.$rh40_codele;
        $virgula  = ",";
      }

      $res_elementoemp = $clrhelementoemp->sql_record($clrhelementoemp->sql_query_pcmater(null,"rh38_codele,rh36_pcmater",null,"rh38_anousu = $ano and
                                                                                                                                rh38_codele in ($codele) and
                                                                                                                                rh36_pcmater is not null"));
      $numrows_elementoemp = $clrhelementoemp->numrows;
      if ($numrows_elementoemp > 0) {
        $arr_solicita  = array(array("solicitacao","dotacao"));
        $cont_solicita = 0;

        db_criatermometro("gerar_solicitacao","Concluido...","blue",1,"Gerando solicitações...");

        if ($gerado == "O"){
          $orgao_ant = "";
        }

        if ($gerado == "U"){
          $orgao_ant   = "";
          $unidade_ant = ""; 
        }

        //db_criatabela($result_confirma); exit;
        //db_criatabela($res_elementoemp); exit;

        $criar_sol   = true;
        $criar_orcam = true;
        for ($i = 0; $i < $numrows_confirma; $i++) {
          db_fieldsmemory($result_confirma,$i);
          
          $valor = 0;
          $valor = $rh40_provento - $rh40_desconto;
          
          if ($valor <= 0) {
            continue;
          }
          
          if (isset($rh40_coddot) && $rh40_coddot > 0) {
            $pc10_correto = "true";
          } else {
            $pc10_correto = "false";
          }
          
          for ($ii = 0; $ii < $numrows_elementoemp; $ii++) {
            db_fieldsmemory($res_elementoemp,$ii);
            
            if ($rh40_codele == $rh38_codele) {
              break;
            }
          }

            if ($gerado == "O"){  // Orgao
              if ($orgao_ant != $rh40_orgao){
                if ($orgao_ant == ""){
                  $orgao_ant = $rh40_orgao;
                } else {
                  $orgao_ant = $rh40_orgao;
                  $criar_sol   = true;
                  $criar_orcam = true;
                }
              }
            }

            if ($gerado == "U"){  // Unidade
              if ($orgao_ant != $rh40_orgao && $unidade_ant != $rh40_unidade){
                if ($orgao_ant == "" && $unidade_ant == ""){
                  $orgao_ant   = $rh40_orgao;
                  $unidade_ant = $rh40_unidade;
                } else {
                  $orgao_ant   = $rh40_orgao;
                  $unidade_ant = $rh40_unidade;
                  $criar_sol   = true;
                  $criar_orcam = true;
                }
              } else if ($orgao_ant == $rh40_orgao && $unidade_ant != $rh40_unidade){
                  $unidade_ant = $rh40_unidade;
                  $criar_sol   = true;
                  $criar_orcam = true;
              } else if ($orgao_ant != $rh40_orgao && $unidade_ant == $rh40_unidade){
                  $orgao_ant   = $rh40_orgao;
                  $criar_sol   = true;
                  $criar_orcam = true;
              }
            }

            if ($criar_sol == true){
              $clsolicita->pc10_data    = date("Y-m-d",db_getsession("DB_datausu"));
              $clsolicita->pc10_resumo  = $pc10_resumo;
              $clsolicita->pc10_depto   = db_getsession("DB_coddepto");
              $clsolicita->pc10_log     = "0";
              $clsolicita->pc10_instit  = db_getsession("DB_instit");
              $clsolicita->pc10_correto = $pc10_correto;
              $clsolicita->pc10_login   = db_getsession("DB_id_usuario");
              $clsolicita->pc10_solicitacaotipo = 1;
            
              $clsolicita->incluir(null);
              if ($clsolicita->erro_status == "0") {
                $sqlerro  = true;
                $erro_msg = $clsolicita->erro_msg;
                break;
              }
            
              $pc10_numero = $clsolicita->pc10_numero;
            
              $arr_solicita[$cont_solicita]["solicitacao"] = $clsolicita->pc10_numero;
            
              if (isset($rh40_coddot) && $rh40_coddot > 0) {
                $arr_solicita[$cont_solicita]["dotacao"] = $rh40_coddot;
              } else {
                $arr_solicita[$cont_solicita]["dotacao"] = 0;
              }
            
              $cont_solicita++;
            
              $clrhsolicita->rh33_solicita = $pc10_numero;
              $clrhsolicita->rh33_anousu   = $ano;
              $clrhsolicita->rh33_mesusu   = $mes;
              $clrhsolicita->rh33_siglaarq = "$siglaarq";
              $clrhsolicita->rh33_seqfolha = $rh40_sequencia;
              $clrhsolicita->rh33_instit   = $rh40_instit;
              
              $clrhsolicita->incluir(null);
              if ($clrhsolicita->erro_status == "0") {
                $sqlerro  = true;
                $erro_msg = $clrhsolicita->erro_msg;
                break;
              }

              $criar_sol = false;
              $pc11_seq  = 1;
            }

/*
              echo "ORGAO:       ".$rh40_orgao." ==> ";
              echo "UNIDADE:     ".$rh40_unidade." >>> ";
              echo "DOTACAO:     ".$rh40_coddot." >>> ";
              echo "CODELE:      ".$rh40_codele." >>> ";
              echo "VALOR:       ".$valor." >>> ";
              echo "SOLICITACAO: ".$pc10_numero."<BR>";
*/              

            $clsolicitem->pc11_numero   = $pc10_numero;
            $clsolicitem->pc11_seq      = $pc11_seq;
            $clsolicitem->pc11_quant    = "1";
            $clsolicitem->pc11_vlrun    = $valor;
            $clsolicitem->pc11_prazo    = "";
            $clsolicitem->pc11_pgto     = "";
            $clsolicitem->pc11_resum    = "";
            $clsolicitem->pc11_just     = "";
            $clsolicitem->pc11_liberado = "true";
            
            $clsolicitem->incluir(null);
            if ($clsolicitem->erro_status == "0") {
              $sqlerro  = true;
              $erro_msg = $clsolicitem->erro_msg;
              break;
            }
            
            $pc11_seq++;
            
            $pc11_codigo = $clsolicitem->pc11_codigo;
            
            $clsolicitempcmater->pc16_codmater  = $rh36_pcmater;
            $clsolicitempcmater->pc16_solicitem = $pc11_codigo;
            
            $clsolicitempcmater->incluir($rh36_pcmater,$pc11_codigo);
            if ($clsolicitempcmater->erro_status == "0") {
              $sqlerro  = true;
              $erro_msg = $clsolicitempcmater->erro_msg;
              break;
            }
            
            $clsolicitemele->pc18_solicitem = $pc11_codigo;
            $clsolicitemele->pc18_codele    = $rh40_codele;
            
            $clsolicitemele->incluir($pc11_codigo,$rh40_codele);
            if ($clsolicitemele->erro_status == "0") {
              $sqlerro  = true;
              $erro_msg = $clsolicitemele->erro_msg;
              break;
            }
            
            if (isset($rh40_coddot) && $rh40_coddot > 0) {
              $clpcdotac->pc13_anousu = $rh40_anousu;
              $clpcdotac->pc13_coddot = $rh40_coddot;
              $clpcdotac->pc13_depto  = db_getsession("DB_coddepto");
              $clpcdotac->pc13_quant  = "1";
              $clpcdotac->pc13_valor  = $valor;
              $clpcdotac->pc13_codele = $rh40_codele;
              $clpcdotac->pc13_codigo = $pc11_codigo;
              $clpcdotac->incluir(null);
              if ($clpcdotac->erro_status == "0") {
                $sqlerro  = true;
                $erro_msg = $clpcdotac->erro_msg;
                break;
              }
              
              $res_reserva = @db_dotacaosaldo(8,2,2,"true","o58_coddot=$rh40_coddot",db_getsession("DB_anousu"));
              @db_fieldsmemory($res_reserva, 0, true);
              
              $total_reserva = ((0 + $atual_menos_reservado) - (0 + $valor));
              if (((isset($atual_menos_reservado) && $atual_menos_reservado < $valor) ||
                   (isset($total_reserva) && $total_reserva < 0))) {
                $saldo_reserva = $atual_menos_reservado;
              } else {
                $saldo_reserva = $valor;
              }
              
              $clorcreserva->o80_anousu = $rh40_anousu;
              $clorcreserva->o80_coddot = $rh40_coddot;
              $clorcreserva->o80_dtfim  = date("Y", db_getsession("DB_datausu"))."-12-31";
              $clorcreserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
              $clorcreserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
              $clorcreserva->o80_valor  = $saldo_reserva;
              $clorcreserva->o80_descr  = " ";
              
              if ($saldo_reserva > 0) {
                $clorcreserva->incluir(null);
                if ($clorcreserva->erro_status == "0") {
                  $sqlerro  = true;
                  $erro_msg = $clorcreserva->erro_msg;
                  break;
                }
                
                $o80_codres = $clorcreserva->o80_codres;
                
                $clorcreservasol->o82_codres    = $o80_codres;
                $clorcreservasol->o82_solicitem = $pc11_codigo;
                $clorcreservasol->o82_pcdotac   = $clpcdotac->pc13_sequencial;
                $clorcreservasol->incluir(null);
                if ($clorcreservasol->erro_status == 0) {
                  $sqlerro = true;
                  $erro_msg = $clorcreservasol->erro_msg;
                  break;
                }
              }
            }

            if ($criar_orcam == true){
              // Orçamento da solicitação
              $clpcorcam->pc20_dtate = date("Y-m-d",db_getsession("DB_datausu"));
              $clpcorcam->pc20_hrate = db_hora();
              $clpcorcam->pc20_obs   = " ";
            
              $clpcorcam->incluir(null);
              if ($clpcorcam->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clpcorcam->erro_msg;
                break;
              }
            
              $pc20_codorc = $clpcorcam->pc20_codorc;
            }  
            
            $clpcorcamitem->pc22_codorc = $pc20_codorc;
            
            $clpcorcamitem->incluir(null);
            if ($clpcorcamitem->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $clpcorcamitem->erro_msg;
              break;
            }
            
            $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;
            
            $clpcorcamitemsol->pc29_orcamitem = $pc22_orcamitem;
            $clpcorcamitemsol->pc29_solicitem = $pc11_codigo;
            
            $clpcorcamitemsol->incluir($pc22_orcamitem,$pc11_codigo);
            if ($clpcorcamitemsol->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $clpcorcamitemsol->erro_msg;
              break;
            }
            
            if ($criar_orcam == true){
              $clpcorcamforne->pc21_codorc     = $pc20_codorc;
              $clpcorcamforne->pc21_numcgm     = $pc21_numcgm;
              $clpcorcamforne->pc21_importado  = "false";
              $clpcorcamforne->pc21_prazoent   = null;
              $clpcorcamforne->pc21_validadorc = null;
            
              $clpcorcamforne->incluir(null);
              if ($clpcorcamforne->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clpcorcamforne->erro_msg;
                break;
              }
            
              $pc21_orcamforne = $clpcorcamforne->pc21_orcamforne;
              $criar_orcam     = false;
            }
            
            $clpcorcamval->pc23_orcamforne = $pc21_orcamforne;
            $clpcorcamval->pc23_orcamitem  = $pc22_orcamitem;
            $clpcorcamval->pc23_valor      = $valor;
            $clpcorcamval->pc23_quant      = "1";
            $clpcorcamval->pc23_obs        = " ";
            $clpcorcamval->pc23_vlrun      = $valor;
            $clpcorcamval->pc23_validmin   = null;
            
            $clpcorcamval->incluir($pc21_orcamforne,$pc22_orcamitem);
            if ($clpcorcamval->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $clpcorcamval->erro_msg;
              break;
            }
            
            $clpcorcamjulg->pc24_orcamforne = $pc21_orcamforne;
            $clpcorcamjulg->pc24_orcamitem  = $pc22_orcamitem;
            $clpcorcamjulg->pc24_pontuacao  = "1";
            
            $clpcorcamjulg->incluir($pc22_orcamitem,$pc21_orcamforne);
            if ($clpcorcamjulg->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $clpcorcamjulg->erro_msg;
              break;
            }
          
          if ($sqlerro == true) {
            break;
          }
          
          db_atutermometro($i,$numrows_confirma,"gerar_solicitacao",1);
          
        }
      } else {
        $erro_msg = "Não existem elementos para o ano selecionado. Verifique!";
        $sqlerro  = true;
      }
    }
    
    if ($sqlerro == false) {
      if (count($arr_solicita) > 0) {
        $msg_sol  = "Solicitações geradas: ".count($arr_solicita)."\\n\\n";
        $msg_sol .= "Solicitação: ";
        for ($i = 0; $i < $cont_solicita; $i++) {
          $msg_sol .= $arr_solicita[$i]["solicitacao"];
          
          if ($arr_solicita[$i]["dotacao"] == 0) {
            if ($gerado == "O" || $gerado == "U"){
              $msg_sol .= "(Sem Dotacao)";
            } else {
              $msg_sol .= "(Existem um ou mais elementos sem dotacao)";
            }
          }
          
          $msg_sol .= ", ";
        }
        
        $erro_msg = substr($msg_sol,0,(strlen($msg_sol)-2));
      }
    }
    
    $confirma = "confirma";
    //$sqlerro  = true;
    db_fim_transacao($sqlerro);
  }
}

if (isset($gera) || isset($confirma)) {
  if (isset($confirma) && (isset($erro_msg) && trim($erro_msg) != "") || $numrows_confirma == 0) {
    if ($numrows_confirma == 0){
      $erro_msg = "Não existem dados neste periodo para gerar solicitações";
    }

    db_msgbox($erro_msg);
  }
  
  db_redireciona("pes1_rhempgerasolfolha001.php?ponto=$ponto&pc21_numcgm=$pc21_numcgm&z01_nome=$z01_nome");
}
?>
</body>
</html>