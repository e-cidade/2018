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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_progmatricula_classe.php");
include("classes/db_proginterrompe_classe.php");
include("classes/db_progconfig_classe.php");
include("classes/db_progantig_classe.php");
include("classes/db_progconvocacao_classe.php");
include("classes/db_progconvocacaores_classe.php");
include("classes/db_progavaladmin_classe.php");
include("classes/db_progavalpedag_classe.php");
include("classes/db_progconhec_classe.php");
include("classes/db_proglicencamatr_classe.php");
include("classes/db_convocacao_classe.php");
include("classes/db_opcaoquestao_classe.php");
include("classes/db_progclasse_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clprogmatricula = new cl_progmatricula;
$clproginterrompe = new cl_proginterrompe;
$clprogconfig = new cl_progconfig;
$clprogantig = new cl_progantig;
$clprogconvocacao = new cl_progconvocacao;
$clprogconvocacaores = new cl_progconvocacaores;
$clprogavaladmin = new cl_progavaladmin;
$clprogavalpedag = new cl_progavalpedag;
$clprogconhec = new cl_progconhec;
$clproglicencamatr = new cl_proglicencamatr;
$clconvocacao = new cl_convocacao;
$clopcaoquestao = new cl_opcaoquestao;
$clprogclasse = new cl_progclasse;
$clrotulo->label("ed112_i_rhpessoal");
$db_opcao = 22;
$db_botao = false;
$result = $clprogconfig->sql_record($clprogconfig->sql_query("","*","",""));
db_fieldsmemory($result,0);
if(isset($progredir)){
 //muda situacao da atual matricula para E-Encerrada
 db_inicio_transacao();
 $db_opcao = 2;
 $clprogmatricula->ed112_c_situacao = "E";
 $clprogmatricula->ed112_d_datafinal = date("Y-m-d");
 $clprogmatricula->ed112_i_usuario = db_getsession("DB_id_usuario");
 $clprogmatricula->ed112_i_codigo = $codigoprog;
 $clprogmatricula->alterar($codigoprog);
 db_fim_transacao();
 $result = $clprogmatricula->sql_record($clprogmatricula->sql_query("","ed112_c_classeesp,ed112_c_dedicacao,ed112_i_nivel,ed112_d_database",""," ed112_i_codigo = $codigoprog"));
 db_fieldsmemory($result,0);
 //Inclui novo registro para progressão com a data de inicio sendo a data de hoje
 db_inicio_transacao();
 $clprogmatricula->ed112_i_rhpessoal = $matricula;
 $clprogmatricula->ed112_i_progclasse = $prxclasse;
 $clprogmatricula->ed112_i_nivel = $ed112_i_nivel;
 $clprogmatricula->ed112_i_usuario = db_getsession("DB_id_usuario");
 $clprogmatricula->ed112_d_database = $ed112_d_database;
 $clprogmatricula->ed112_d_datainicio = date("Y-m-d");
 $clprogmatricula->ed112_d_datafinal = null;
 $clprogmatricula->ed112_c_dedicacao = $ed112_c_dedicacao;
 $clprogmatricula->ed112_c_classeesp = $ed112_c_classeesp;
 $clprogmatricula->ed112_c_situacao = "A";
 $clprogmatricula->incluir(null);
 db_fim_transacao();
 db_redireciona("edu3_progmatricula001.php");
 exit;
}
if(isset($encerrar)){
 //muda situacao da matricula para E-Encerrada
 db_inicio_transacao();
 $db_opcao = 2;
 $clprogmatricula->ed112_c_situacao = "E";
 $clprogmatricula->ed112_d_datafinal = date("Y-m-d");
 $clprogmatricula->ed112_i_usuario = db_getsession("DB_id_usuario");
 $clprogmatricula->ed112_i_codigo = $codigoprog;
 $clprogmatricula->alterar($codigoprog);
 db_fim_transacao();
 db_redireciona("edu3_progmatricula001.php");
 exit;
}
if(isset($chavepesquisa)){
 $db_opcao = 2;
 $result = $clprogmatricula->sql_record($clprogmatricula->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $ed112_d_database_dia = substr($ed112_d_database,8,2);
 $ed112_d_database_mes = substr($ed112_d_database,5,2);
 $ed112_d_database_ano = substr($ed112_d_database,0,4);
 $ed112_d_datainicio_dia = substr($ed112_d_datainicio,8,2);
 $ed112_d_datainicio_mes = substr($ed112_d_datainicio,5,2);
 $ed112_d_datainicio_ano = substr($ed112_d_datainicio,0,4);
 $total_dias_prog = $ed110_i_intervalo*365;
 $ed112_d_dataprevisao = strftime("%Y-%m-%d",mktime(0,0,0,$ed112_d_datainicio_mes,$ed112_d_datainicio_dia+$total_dias_prog,$ed112_d_datainicio_ano));
 $ed112_d_dataprevisao_dia = substr($ed112_d_dataprevisao,8,2);
 $ed112_d_dataprevisao_mes = substr($ed112_d_dataprevisao,5,2);
 $ed112_d_dataprevisao_ano = substr($ed112_d_dataprevisao,0,4);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10;
 background-color: #f3f3f3;
}
.aluno2{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10;
 background-color: #888888;
}
.aluno3{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 14;
 background-color: #C0FFC0;
}
.aluno4{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 14;
 background-color: #FFC0C0;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Consulta Planilha de Carreira</b></legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
     <tr>
      <td nowrap title="<?=@$Ted112_i_rhpessoal?>">
       <?db_ancora(@$Led112_i_rhpessoal,"js_pesquisa();",1);?>
      </td>
      <td>
       <?db_input('ed112_i_rhpessoal',10,$Ied112_i_rhpessoal,true,'text',3,"")?>
       <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td>
       <b>Data Admissão:</b>
      </td>
      <td>
       <?db_inputdata('ed112_d_database',@$ed112_d_database_dia,@$ed112_d_database_mes,@$ed112_d_database_ano,true,'text',3,"")?>
       &nbsp;&nbsp;&nbsp;&nbsp;
       <b>Classe Atual:</b>
       <?db_input('ed107_c_descr',10,@$Ied107_c_descr,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td>
       <b>Data de Início na Classe:</b>
      </td>
      <td>
       <?db_inputdata('ed112_d_datainicio',@$ed112_d_datainicio_dia,@$ed112_d_datainicio_mes,@$ed112_d_datainicio_ano,true,'text',3,"")?>
       &nbsp;&nbsp;&nbsp;&nbsp;
       <b>Situação:</b>
       <?=@$Led112_c_situacao?>
       <?
       $x = array(''=>'','A'=>'ABERTA','E'=>'ENCERRADA','I'=>'INTERROMPIDA');
       db_select('ed112_c_situacao',$x,true,3,"");
       ?>
      </td>
     </tr>
     <?if($ed112_c_situacao=="I"){
      $result = $clproginterrompe->sql_record($clproginterrompe->sql_query("","*",""," ed123_i_progmatricula = $chavepesquisa"));
      db_fieldsmemory($result,0);
      ?>
      <tr>
       <td><b>Interrompida em:</b></td>
       <td><?db_inputdata('ed112_d_datafinal',@$ed112_d_datafinal_dia,@$ed112_d_datafinal_mes,@$ed112_d_datafinal_ano,true,'text',3,"")?></td>
      </tr>
      <tr>
       <td><b>Motivo:</b></td>
       <td><?=nl2br($ed123_t_motivo)?></td>
      </tr>
    <?}elseif($ed112_c_situacao=="E"){?>
      <tr>
       <td><b>Encerrada em:</b></td>
       <td><?db_inputdata('ed112_d_datafinal',@$ed112_d_datafinal_dia,@$ed112_d_datafinal_mes,@$ed112_d_datafinal_ano,true,'text',3,"")?></td>
      </tr>
    <?}?>
    </table>
    <?if(isset($chavepesquisa)){?>
    <table width="100%" border="1" cellspacing="0" cellpadding="2">
     <tr class="titulo" align="center">
      <td rowspan="2">Ano</td>
      <td rowspan="2">Antiguidade</td>
      <td colspan="4">Desempenho</td>
      <td rowspan="2">Conhecimento</td>
      <td rowspan="2">Total<br>no Ano</td>
     </tr>
     <tr class="titulo" align="center">
      <td>Convocação</td>
      <td>Administrativo</td>
      <td>Pedagógico</td>
      <td>Total</td>
     </tr>
     <?
     $total_antiguidade = 0;
     $total_convocacao = 0;
     $total_avaladmin = 0;
     $total_avalpedag = 0;
     $total_desempenho = 0;
     $total_conhec = 0;
     for($x=$ed112_d_datainicio_ano+1;$x<=$ed112_d_dataprevisao_ano;$x++){
      $soma_ano = 0;
      ?>
      <tr align="center">
       <td class="titulo"><?=$x?></td>
       <td class="aluno1">
        <?
        $soma_desempenho = 0;
        $result1 = $clprogantig->sql_record($clprogantig->sql_query("","ed113_f_pontuacao",""," ed113_i_progmatricula = $ed112_i_codigo AND ed113_i_ano = $x"));
        if($clprogantig->numrows>0){
         db_fieldsmemory($result1,0);
         $ptantiguidade =  $ed113_f_pontuacao;
        }else{
         $ptantiguidade = 0;
        }
        echo number_format($ptantiguidade,2,".",".");
        $soma_ano += $ptantiguidade;
        $total_antiguidade += $ptantiguidade;
        ?>
       </td>
       <td class="aluno1">
        <?
        $result3 = $clprogconvocacaores->sql_record($clprogconvocacaores->sql_query("","ed127_i_nconvoca as qtdconvocacao,ed127_i_nparticipa as qtdparticipacao,ed127_i_nfaltajust as qtdft",""," ed127_i_ano = $x AND ed127_i_progmatricula = $ed112_i_codigo"));
        if($clprogconvocacaores->numrows>0){
         db_fieldsmemory($result3,0);
         if($qtdparticipacao==0){
          $ptconvocacao = 0;
         }else{
          $ptconvocacao = (($qtdparticipacao+$qtdft)/$qtdconvocacao);
          $ptconvocacao = $ed110_i_ptconvocacao*$ptconvocacao;
         }
        }else{
         $ptconvocacao = 0;
        }
        echo number_format($ptconvocacao,2,".",".");
        $total_convocacao += $ptconvocacao;
        $soma_desempenho += $ptconvocacao;
        ?>
       </td>
       <td class="aluno1">
        <?
        $result1 = $clopcaoquestao->sql_record($clopcaoquestao->sql_query("","max(ed106_f_pontuacao) as maiorpt","","ed106_c_ativo = 'S'"));
        db_fieldsmemory($result1,0);
        $result2 = $clprogavaladmin->sql_record($clprogavaladmin->sql_query("","count(*) as qtdquestao",""," ed116_i_ano = $x AND ed116_i_progmatricula = $ed112_i_codigo"));
        db_fieldsmemory($result2,0);
        $result3 = $clprogavaladmin->sql_record($clprogavaladmin->sql_query("","sum(ed106_f_pontuacao) as somapt",""," ed116_i_ano = $x AND ed116_i_progmatricula = $ed112_i_codigo"));
        db_fieldsmemory($result3,0);
        $maximopt = $maiorpt*$qtdquestao;
        if($somapt==""){
         $somapt = 0;
        }
        if($maximopt==0){
         $ptavaladmin = 0;
        }else{
         $ptavaladmin = ($somapt/$maximopt);
         $ptavaladmin = $ed110_i_ptavaladmin*$ptavaladmin;
        }
        echo number_format($ptavaladmin,2,".",".");
        $total_avaladmin += $ptavaladmin;
        $soma_desempenho += $ptavaladmin;
        ?>
       </td>
       <td class="aluno1">
        <?
        $result1 = $clopcaoquestao->sql_record($clopcaoquestao->sql_query("","max(ed106_f_pontuacao) as maiorpt","","ed106_c_ativo = 'S'"));
        db_fieldsmemory($result1,0);
        $result2 = $clprogavalpedag->sql_record($clprogavalpedag->sql_query("","count(*) as qtdquestao",""," ed117_i_ano = $x AND ed117_i_progmatricula = $ed112_i_codigo"));
        db_fieldsmemory($result2,0);
        $result3 = $clprogavalpedag->sql_record($clprogavalpedag->sql_query("","sum(ed106_f_pontuacao) as somapt",""," ed117_i_ano = $x AND ed117_i_progmatricula = $ed112_i_codigo"));
        db_fieldsmemory($result3,0);
        $maximopt = $maiorpt*$qtdquestao;
        if($somapt==""){
         $somapt = 0;
        }
        if($maximopt==0){
         $ptavalpedag = 0;
        }else{
         $ptavalpedag = ($somapt/$maximopt);
         $ptavalpedag = $ed110_i_ptavalpedag*$ptavalpedag;
        }
        echo number_format($ptavalpedag,2,".",".");
        $total_avalpedag += $ptavalpedag;
        $soma_desempenho += $ptavalpedag;
        $soma_ano += $soma_desempenho;
        $total_desempenho += $soma_desempenho;
        ?>
       </td>
       <td class="aluno1">
        <?=number_format($soma_desempenho,2,".",".");?>
       </td>
       <td class="aluno1">
        <?
        $result3 = $clprogconhec->sql_record($clprogconhec->sql_query("","sum(ed114_f_cargahoraria) as somach",""," ed114_i_ano = $x AND ed114_i_progmatricula = $ed112_i_codigo"));
        db_fieldsmemory($result3,0);
        if($somach==""){
         $somach = 0;
        }
        echo number_format($somach,2,".",".");
        $total_conhec += $somach;
        $soma_ano += $somach;
        ?>
       </td>
       <td class="aluno1">
        <?=number_format($soma_ano,2,".",".");?>
       </td>
      </tr>
     <?
     }
     $total_conhec = $total_conhec>200?200:$total_conhec;
     $soma_total = $total_antiguidade+$total_desempenho+$total_conhec;
     ?>
     <tr>
      <td class="titulo" align="center"><b>Totais:</b></td>
      <td class="aluno2"><?=number_format($total_antiguidade,2,".",".")?></td>
      <td class="aluno2"><?=number_format($total_convocacao,2,".",".")?></td>
      <td class="aluno2"><?=number_format($total_avaladmin,2,".",".")?></td>
      <td class="aluno2"><?=number_format($total_avalpedag,2,".",".")?></td>
      <td class="aluno2"><?=number_format($total_desempenho,2,".",".")?></td>
      <td class="aluno2"><?=number_format($total_conhec,2,".",".")?></td>
      <?$nomeclass = $soma_total>=$ed110_i_ptgeral?"aluno3":"aluno4";?>
      <td class="<?=$nomeclass?>"><?=number_format($soma_total,2,".",".")?></td>
     </tr>
    </table>
    <table width="100%" align="left" border="0" cellspacing="0" cellpadding="2">
     <tr>
      <td valign="top" width="20%">
       <b>Licenças:</b>
      </td>
      <td colspan="2">
       <?
       $result3 = $clproglicencamatr->sql_record($clproglicencamatr->sql_query("","ed121_c_descr,ed121_i_tempolimite,ed122_d_inicio,ed122_d_final",""," ed122_i_progmatricula = $ed112_i_codigo AND ed122_d_inicio BETWEEN '$ed112_d_datainicio' AND '$ed112_d_dataprevisao' AND ed121_c_suspensao = 'S'"));
       $soma_licenca = 0;
       if($clproglicencamatr->numrows>0){
        for($x=0;$x<$clproglicencamatr->numrows;$x++){
         $dias_licenca = 0;
         db_fieldsmemory($result3,$x);
         $data_inicio = mktime(0,0,0,substr($ed122_d_inicio,5,2),substr($ed122_d_inicio,8,2),substr($ed122_d_inicio,0,4));
         $data_final = mktime(0,0,0,substr($ed122_d_final,5,2),substr($ed122_d_final,8,2),substr($ed122_d_final,0,4));
         $data_entre = $data_final - $data_inicio;
         $dias = ceil($data_entre/86400);
         if($ed121_i_tempolimite>0){
          if($dias>$ed121_i_tempolimite){
           $dias_licenca = $dias;
          }
         }else{
          $dias_licenca = $dias;
         }
         $soma_licenca += $dias_licenca;
         echo "* ".$ed121_c_descr." - DE ".db_formatar($ed122_d_inicio,'d')." ATÉ ".db_formatar($ed122_d_final,'d')." - N° DIAS SUSPENSÃO: ".$dias_licenca."<br>";
        }
       }else{
        echo "Nenhuma licença.";
       }
       $ed112_d_dataprevisao = strftime("%Y-%m-%d",mktime(0,0,0,$ed112_d_dataprevisao_mes,$ed112_d_dataprevisao_dia+$soma_licenca,$ed112_d_dataprevisao_ano));
       $ed112_d_dataprevisao_dia = substr($ed112_d_dataprevisao,8,2);
       $ed112_d_dataprevisao_mes = substr($ed112_d_dataprevisao,5,2);
       $ed112_d_dataprevisao_ano = substr($ed112_d_dataprevisao,0,4);
       ?>
      </td>
     </tr>
     <?if($ed112_c_situacao=="A"){?>
     <tr>
      <td>
       <b>Próxima Progressão:</b>
      </td>
      <td>
       <?db_inputdata('ed112_d_dataprevisao',@$ed112_d_dataprevisao_dia,@$ed112_d_dataprevisao_mes,@$ed112_d_dataprevisao_ano,true,'text',3,"")?>
      </td>
      <td rowspan="3" valign="top">
       <?if(str_replace("-","",$ed112_d_dataprevisao)-date("Ymd")<0 && $soma_total>=$ed110_i_ptgeral && $ed112_c_situacao=="A"){?>
       <form name="form1" method="post" action="">
       <fieldset style="width:95%;">
        <table align="center" border="0" cellspacing="0" cellpadding="2">
           <?
           $result = $clprogclasse->sql_record($clprogclasse->sql_query("","max(ed107_i_sequencia) as ultimaclasse","",""));
           db_fieldsmemory($result,0);
           if($ultimaclasse==$ed107_i_sequencia){
            ?>
            <tr>
             <td align="center" style="text-decoration:blink;">
              <b>Matrícula <?=$ed112_i_rhpessoal?> encerrou última classe na progressão.</b><br>
             </td>
            </tr>
            <tr>
             <td align="center">
              <input type="button" name="confirmar" value="Encerrar Progressão" onclick="js_encerrar(<?=$ed112_i_codigo?>)">
             </td>
            </tr>
            <?
           }else{
            $result = $clprogclasse->sql_record($clprogclasse->sql_query("","ed107_i_codigo as prxcod, ed107_c_descr as prxclasse",""," ed107_i_sequencia = ".($ed107_i_sequencia+1).""));
            db_fieldsmemory($result,0);
            ?>
            <tr>
             <td align="center" style="text-decoration:blink;">
             <b>Matrícula <?=$ed112_i_rhpessoal?> está apta para progressão à classe <?=$prxclasse?>.</b>
             </td>
            </tr>
            <tr>
             <td align="center">
              <input type="button" name="confirmar" value="Confirmar Progressão" onclick="js_progredir(<?=$ed112_i_codigo?>,<?=$ed112_i_rhpessoal?>,<?=$prxcod?>)">
             </td>
            </tr>
            <?
           }
           ?>
        </table>
       </fieldset>
       </form>
       <?}?>
      </td>
     </tr>
     <?}?>
     <tr>
      <td nowrap title="<?=@$Ted112_c_classeesp?>">
       <b>Classe Especial:</b>
      </td>
      <td>
       <?
       $x = array(''=>'','N'=>'NÃO','S'=>'SIM');
       db_select('ed112_c_classeesp',$x,true,3," style='width:80px;height:15px;font-size:10px;padding:0px;'");
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted112_c_dedicacao?>">
       <b>Dedicação Docente:</b>
      </td>
      <td>
       <?
       $x = array(''=>'','N'=>'NÃO','S'=>'SIM');
       db_select('ed112_c_dedicacao',$x,true,3," style='width:80px;height:15px;font-size:10px;padding:0px;'");
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap valign="top">
       <b>Difícil Acesso:</b>
      </td>
      <td colspan="2">
       <?
       $sql1 = "SELECT ed18_i_codigo,ed18_c_nome,ed125_c_descr
                FROM rechumanoescola
                 inner join escoladifacesso on ed126_i_escola = ed75_i_escola
                 inner join tipoacesso on ed125_i_codigo = ed126_i_tipoacesso
                 inner join escola on ed18_i_codigo = ed126_i_escola
                WHERE ed75_i_rechumano = $ed112_i_rhpessoal
               ";
       $result1 = pg_query($sql1);
       $linhas1 = pg_num_rows($result1);
       if($linhas1>0){
        $ed112_c_dacesso = 'S';
        $x = array('S'=>'SIM');
        db_select('ed112_c_dacesso',$x,true,3," style='width:80px;height:15px;font-size:10px;padding:0px;'");
        for($x=0;$x<$linhas1;$x++){
         db_fieldsmemory($result1,$x);
         echo "<br><b>Escola:</b> ".$ed18_i_codigo." - ".$ed18_c_nome." <b>Tipo:</b> ".$ed125_c_descr;
        }
       }else{
        $ed112_c_dacesso = 'N';
        $x = array('N'=>'NÃO');
        db_select('ed112_c_dacesso',$x,true,3," style='width:80px;height:15px;font-size:10px;padding:0px;'");
       }
       ?>
      </td>
     </tr>
     <tr>
      <td colspan="3" align="center">
       <?
       if(isset($ano_chave)){
        ?>
         <input type="button" name="voltar" value="Voltar para Previsão" onclick="location.href='edu3_progprevisao001.php?ano_chave=<?=$ano_chave?>&tipo=<?=$tipo?>'">
        <?
       }
       ?>
      </td>
     </tr>
    </table>
    <?}?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?funcao_js=parent.js_preenchepesquisa|ed112_i_codigo','Pesquisa de Matrículas',true);
}
function js_preenchepesquisa(chave){
 db_iframe_progmatricula.hide();
 <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 ?>
}
function js_encerrar(codigo){
 if(confirm("Confirmar encerramento da progressão de matrícula?")){
  location.href = "?encerrar&codigoprog="+codigo;
 }
}
function js_progredir(codigo,matricula,classe){
 if(confirm("Confirmar progressão da matrícula?")){
  location.href = "?progredir&codigoprog="+codigo+"&matricula="+matricula+"&prxclasse="+classe;
 }
}
</script>
<?
if($db_opcao==22){
 echo "<script>js_pesquisa();</script>";
}
?>