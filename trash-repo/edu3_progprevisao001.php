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
$db_opcao = 2;
$db_botao = false;
$result = $clprogconfig->sql_record($clprogconfig->sql_query("","*","",""));
db_fieldsmemory($result,0);
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
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Consulta Previsão de Progressão Funcional</b></legend>
    <table align="left" border="0" cellspacing="0" cellpadding="2">
     <tr>
      <td>
       <b>Informe o ano:</b>
      </td>
      <td>
       <?db_input('ano_chave',4,@$Iano_chave,true,'text',$db_opcao,'')?>
       &nbsp;&nbsp;&nbsp;&nbsp;
       <b>Filtro:</b>
       <select name="tipo" style="width:180px;height:15px;font-size:10px;padding:0px;">
        <option value="T" <?=@$tipo=="T"?"selected":""?> >Todos</option>
        <option value="P" <?=@$tipo=="P"?"selected":""?> >Em condições de progressão</option>
       </select>
       <input type="button" name="pesquisar" value="Pesquisar" onclick="js_pesquisar()">
      </td>
     </tr>
    </table>
    <br><br>
    <?
    if(isset($ano_chave)){
     ?>
     <table width="100%" align="left" border="1" cellspacing="0" cellpadding="2">
      <tr class="titulo" align="center">
       <td>Matrícula</td>
       <td>Nome</td>
       <td>Classe</td>
       <td>Início na Classe</td>
       <td>Pontuação</td>
       <td>Próxima Progressão</td>
       <td></td>
      </tr>
      <?
      $result = $clprogmatricula->sql_record($clprogmatricula->sql_query("","*","z01_nome"," ed112_c_situacao = 'A'"));
      $linhas = $clprogmatricula->numrows;
      $cor1 = "#F3F3F3";
      $cor2 = "#DBDBDB";
      $cor = "";
      $count = 0;
      for($y=0;$y<$linhas;$y++){
       db_fieldsmemory($result,$y);
       if($cor==$cor1){
        $cor = $cor2;
       }else{
        $cor = $cor1;
       }
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
        }
       }
       $ed112_d_dataprevisao = strftime("%Y-%m-%d",mktime(0,0,0,$ed112_d_dataprevisao_mes,$ed112_d_dataprevisao_dia+$soma_licenca,$ed112_d_dataprevisao_ano));
       $ed112_d_dataprevisao_dia = substr($ed112_d_dataprevisao,8,2);
       $ed112_d_dataprevisao_mes = substr($ed112_d_dataprevisao,5,2);
       $ed112_d_dataprevisao_ano = substr($ed112_d_dataprevisao,0,4);
       $total_antiguidade = 0;
       $total_convocacao = 0;
       $total_avaladmin = 0;
       $total_avalpedag = 0;
       $total_desempenho = 0;
       $total_conhec = 0;
       for($x=$ed112_d_datainicio_ano+1;$x<=$ed112_d_dataprevisao_ano;$x++){
        $soma_ano = 0;
        $soma_desempenho = 0;
        $result1 = $clprogantig->sql_record($clprogantig->sql_query("","ed113_f_pontuacao",""," ed113_i_progmatricula = $ed112_i_codigo AND ed113_i_ano = $x"));
        if($clprogantig->numrows>0){
         db_fieldsmemory($result1,0);
         $ptantiguidade =  $ed113_f_pontuacao;
        }else{
         $ptantiguidade = 0;
        }
        $soma_ano += $ptantiguidade;
        $total_antiguidade += $ptantiguidade;
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
        $total_convocacao += $ptconvocacao;
        $soma_desempenho += $ptconvocacao;
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
        $total_avaladmin += $ptavaladmin;
        $soma_desempenho += $ptavaladmin;
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
        $total_avalpedag += $ptavalpedag;
        $soma_desempenho += $ptavalpedag;
        $soma_ano += $soma_desempenho;
        $total_desempenho += $soma_desempenho;
        $result3 = $clprogconhec->sql_record($clprogconhec->sql_query("","sum(ed114_f_cargahoraria) as somach",""," ed114_i_ano = $x AND ed114_i_progmatricula = $ed112_i_codigo"));
        db_fieldsmemory($result3,0);
        if($somach==""){
         $somach = 0;
        }
        $total_conhec += $somach;
        $soma_ano += $somach;
       }
       $total_conhec = $total_conhec>200?200:$total_conhec;
       $soma_total = $total_antiguidade+$total_desempenho+$total_conhec;
       if($tipo=="T" && ($ano_chave==""||$ano_chave==$ed112_d_dataprevisao_ano)){
        $count++;
        ?>
        <tr align="center" bgcolor="<?=$cor?>">
         <td>
          <?=$ed112_i_rhpessoal?>
         </td>
         <td align="left">
          <?=$z01_nome?>
         </td>
         <td>
          <?=$ed107_c_descr?>
         </td>
         <td>
          <?=db_formatar($ed112_d_datainicio,'d')?>
         </td>
         <td style="color:<?=$soma_total>=$ed110_i_ptgeral?'green':'red'?>">
          <b><?=number_format($soma_total,2,".",".")?></b>
         </td>
         <td style="color:<?=str_replace('-','',$ed112_d_dataprevisao)-date('Ymd')<0?'green':'red'?>">
          <b><?=db_formatar($ed112_d_dataprevisao,'d')?></b>
         </td>
         <td>
          <?
          if(str_replace("-","",$ed112_d_dataprevisao)-date("Ymd")<0 && $soma_total>=$ed110_i_ptgeral){
           $result4 = $clprogclasse->sql_record($clprogclasse->sql_query("","max(ed107_i_sequencia) as ultimaclasse","",""));
           db_fieldsmemory($result4,0);
           if($ultimaclasse==$ed107_i_sequencia){
            ?>
            <input type="button" name="confirmar" value="Progressão" onclick="location.href='edu3_progmatricula001.php?chavepesquisa=<?=$ed112_i_codigo?>&ano_chave=<?=$ano_chave?>&tipo=<?=$tipo?>'">
            <?
           }else{
            $result4 = $clprogclasse->sql_record($clprogclasse->sql_query("","ed107_i_codigo as prxcod, ed107_c_descr as prxclasse",""," ed107_i_sequencia = ".($ed107_i_sequencia+1).""));
            db_fieldsmemory($result4,0);
            ?>
            <input type="button" name="confirmar" value="Progressão" onclick="location.href='edu3_progmatricula001.php?chavepesquisa=<?=$ed112_i_codigo?>&ano_chave=<?=$ano_chave?>&tipo=<?=$tipo?>'">
            <?
           }
          }else{
           ?>
           <input type="button" name="confirmar" value="Planilha" onclick="location.href='edu3_progmatricula001.php?chavepesquisa=<?=$ed112_i_codigo?>&ano_chave=<?=$ano_chave?>&tipo=<?=$tipo?>'">
           <?
          }
          ?>
         </td>
        </tr>
        <?
       }elseif($tipo=="P" && ($ano_chave==""||$ano_chave==$ed112_d_dataprevisao_ano)){
        if(str_replace("-","",$ed112_d_dataprevisao)-date("Ymd")<0 && $soma_total>=$ed110_i_ptgeral){
         $count++;
         ?>
         <tr align="center" bgcolor="<?=$cor?>">
          <td>
           <?=$ed112_i_rhpessoal?>
          </td>
          <td align="left">
           <?=$z01_nome?>
          </td>
          <td>
           <?=$ed107_c_descr?>
          </td>
          <td>
           <?=db_formatar($ed112_d_datainicio,'d')?>
          </td>
          <td style="color:<?=$soma_total>=$ed110_i_ptgeral?'green':'red'?>">
           <b><?=number_format($soma_total,2,".",".")?></b>
          </td>
          <td style="color:<?=str_replace('-','',$ed112_d_dataprevisao)-date('Ymd')<0?'green':'red'?>">
           <b><?=db_formatar($ed112_d_dataprevisao,'d')?></b>
          </td>
          <td>
           <?
           $result4 = $clprogclasse->sql_record($clprogclasse->sql_query("","max(ed107_i_sequencia) as ultimaclasse","",""));
           db_fieldsmemory($result4,0);
           if($ultimaclasse==$ed107_i_sequencia){
            ?>
            <input type="button" name="confirmar" value="Progressão" onclick="location.href='edu3_progmatricula001.php?chavepesquisa=<?=$ed112_i_codigo?>&ano_chave=<?=$ano_chave?>&tipo=<?=$tipo?>'">
            <?
           }else{
            $result4 = $clprogclasse->sql_record($clprogclasse->sql_query("","ed107_i_codigo as prxcod, ed107_c_descr as prxclasse",""," ed107_i_sequencia = ".($ed107_i_sequencia+1).""));
            db_fieldsmemory($result4,0);
            ?>
            <input type="button" name="confirmar" value="Progressão" onclick="location.href='edu3_progmatricula001.php?chavepesquisa=<?=$ed112_i_codigo?>&ano_chave=<?=$ano_chave?>&tipo=<?=$tipo?>'">
            <?
           }
           ?>
          </td>
         </tr>
         <?
        }
       }
      }
      if($count==0){
       ?>
       <tr bgcolor="#F3F3F3"><td align="center" colspan="6">Nenhum registro com os filtros selecionados.</td></tr>
       <?
      }
     }
    ?>
    </table>
   </fieldset>
   <br><br>
   </center>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
 function js_pesquisar(){
  location.href = "?ano_chave="+document.form1.ano_chave.value+"&tipo="+document.form1.tipo.value;
 }
</script>