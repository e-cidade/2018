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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet                  = db_utils::postMemory($_GET);
$ed69_d_datatransf_dia = date("d",db_getsession("DB_datausu"));
$ed69_d_datatransf_mes = date("m",db_getsession("DB_datausu"));
$ed69_d_datatransf_ano = date("Y",db_getsession("DB_datausu"));

db_postmemory($HTTP_POST_VARS);

$resultedu             = eduparametros(db_getsession("DB_coddepto"));
$clalunotransfturma    = new cl_alunotransfturma;
$clregencia            = new cl_regencia;
$clturma               = new cl_turma;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clprocavaliacao       = new cl_procavaliacao;
$clmatricula           = new cl_matricula;
$clmatriculamov        = new cl_matriculamov;
$clmatriculaserie      = new cl_matriculaserie;
$cldiario              = new cl_diario;
$cldiarioavaliacao     = new cl_diarioavaliacao;
$cldiarioresultado     = new cl_diarioresultado;
$cldiariofinal         = new cl_diariofinal;
$clpareceraval         = new cl_pareceraval;
$clparecerresult       = new cl_parecerresult;
$clabonofalta          = new cl_abonofalta;
$clamparo              = new cl_amparo;
$cltransfescolarede    = new cl_transfescolarede;
$cltransfescolafora    = new cl_transfescolafora;
$cltransfaprov         = new cl_transfaprov;
$clalunocurso          = new cl_alunocurso;
$claprovconselho       = new cl_aprovconselho;
$clserieequiv          = new cl_serieequiv;

$clalunotransfturma->rotulo->label();
$db_opcao = 22;
$db_botao = false;

?>
<table width="300" height="100" id="tab_aguarde" style="border:2px solid #444444;position:absolute;top:100px;left:250px;" cellspacing="1" cellpading="2">
 <tr>
  <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;">
   <b>Aguarde...Carregando.</b>
  </td>
 </tr>
</table>
<?
if(!isset($incluir)&&!isset($incluir2)){
 ?>
 <html>
 <head>
 <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta http-equiv="Expires" CONTENT="0">
 <script type="text/javascript" src="scripts/scripts.js"></script>
 <script type="text/javascript" src="scripts/prototype.js"></script>
 <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
 <form name="form1" METHOD="POST" action="">
 <table border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td colspan="4" valign="top" bgcolor="#CCCCCC">
   <br>
   <b>
   Caso as turmas de origem e destino tenham disciplinas e/ou períodos de avaliação diferentes,
   informe abaixo quais disciplinas e períodos de avaliação da turma de destino que vão receber
   as informações do aluno.
   </b>
   <br><br>
  </td>
 </tr>
 <tr>
  <td width="28%" valign="top" bgcolor="#CCCCCC">
   <b>Disciplinas TURMA DE ORIGEM:</b>
  </td>
  <td width="20"></td>
  <td width="28%" valign="top" bgcolor="#CCCCCC">
   <b>Disciplinas TURMA DE DESTINO:</b>
  </td>
  <td valign="top" bgcolor="#CCCCCC">
   <b>Aproveitamento na TURMA DE ORIGEM:</b>
  </td>
 </tr>
 <?
 
 /**
  * Variáveis para controle das etapas das progressões de um aluno
  */
 $aEtapasProgressao = array();
 $sEtapasProgressao = "";
 $oAluno            = AlunoRepository::getAlunoByMatricula( $matricula );
 
 /**
  * Percorre as progressões do aluno, caso existam, e adicione ao array para em seguida adicionar a string
  */
 foreach ( $oAluno->getProgressaoParcial() as $oProgressaoParcial ) {
   $aEtapasProgressao[] = $oProgressaoParcial->getEtapa()->getCodigo();
 }
 
 $sEtapasProgressao = implode( ", ", $aEtapasProgressao );
 
 $sSqlMatricula = $clmatricula->sql_query( "", "ed60_i_aluno, ed221_i_serie as etapaorigem", "", " ed60_i_codigo = {$matricula}" );
 $result_cod    = $clmatricula->sql_record( $sSqlMatricula );
 db_fieldsmemory( $result_cod, 0 );
 
 /**
  * Existindo etapas de progressão, adiciona a variável $etapaorigem
  */
 if ( !empty( $sEtapasProgressao ) ) {
   $etapaorigem = $etapaorigem.", ".$sEtapasProgressao;
 }
 
 $sSqlSerieEquiv   = $clserieequiv->sql_query( "", "ed234_i_serieequiv as equivorig", "", " ed234_i_serie in ({$etapaorigem})" );
 $result_equivorig = $clserieequiv->sql_record( $sSqlSerieEquiv );
 $codequivorig     = "";;
 $seporig          = "";
 
 for ( $ww = 0; $ww < $clserieequiv->numrows; $ww++ ) {

   db_fieldsmemory( $result_equivorig, $ww );
   $codequivorig .= $seporig.$equivorig;
   $seporig       = ",";
 }
 
 $codequivorig = ( $codequivorig == "" ? 0 : $codequivorig ).",".$etapaorigem;
 
 $sCamposProcOrigem  = "ed59_i_codigo, ed232_i_codigo, ed232_c_descr, ed232_c_abrev, ed220_i_procedimento as procorigem";
 $sCamposProcOrigem .= ", ed59_i_ordenacao";
 $sWhereProcOrigem   = " ed59_i_turma = {$turmaorigem} AND ed59_i_serie in ({$etapaorigem})";
 $sSqlProcOrigem     = $clregencia->sql_query( "", $sCamposProcOrigem, "ed59_i_ordenacao", $sWhereProcOrigem );
 $result             = $clregencia->sql_record( $sSqlProcOrigem );
 
 $procorigem = "";
 if ( $result && pg_num_rows( $result ) > 0 ) {
   $procorigem = pg_result( $result, 0, 'procorigem' );
 }
 
 $procorigem = pg_result( $result, 0, 'procorigem' );
 $linhas     = $clregencia->numrows;
 
 $sCamposProcDestino  = "ed59_i_codigo as regdestino, ed232_i_codigo as coddestino, ed232_c_descr as descrdestino";
 $sCamposProcDestino .= ", ed220_i_procedimento as procdestino, ed59_i_ordenacao";
 $sWhereProcDestino   = " ed59_i_turma = {$turmadestino} AND ed59_i_serie in ({$codequivorig})";
 $sSqlProcDestino     = $clregencia->sql_query( "", $sCamposProcDestino, "ed59_i_ordenacao", $sWhereProcDestino );
 $result1             = $clregencia->sql_record( $sSqlProcDestino );
 
 $procdestino = "";
 if ( $result1 && pg_num_rows( $result1 ) > 0 ) {
   $procdestino = pg_result( $result1, 0, 'procdestino' );
 }
 
 $linhas1     = $clregencia->numrows;
 $regmarcadas = "";
 
 for ( $t = 0; $t < $linhas; $t++ ) {

  db_fieldsmemory( $result, $t );
  ?>
  <tr>
   <td valign="top" bgcolor="#CCCCCC">
    <input name="regenciaorigem" type="text" value="<?=$ed59_i_codigo?>" size="10" readonly style="width:75px">
    <input name="regorigemdescr" type="text" value="<?=$ed232_c_descr?>" size="30" readonly style="width:180px">
   </td>
   <td align="center">--></td>
   <td>
    <?
    $temreg = false;
    for($w=0;$w<$linhas1;$w++){
     db_fieldsmemory($result1,$w);
     if($ed232_i_codigo==$coddestino){
      $temreg = true;
      $regenciadestino = $regdestino;
      $regdestinodescr = $descrdestino;
      $regmarcadas .= "#".$regdestino."#";
     }
    }
    if($temreg==true){
     ?>
      <input name="regenciadestino" type="text" value="<?=$regenciadestino?>" size="10" readonly style="width:75px">
      <input name="regdestinodescr" type="text" value="<?=$regdestinodescr?>" size="30" readonly style="width:180px">
     <?
    }else{
     $sql2 = "select ed59_i_codigo as regsobra,trim(ed232_c_descr) as descrsobra
                from regencia
                     inner join disciplina    on ed12_i_codigo  = ed59_i_disciplina
                     inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
               where ed59_i_turma = {$turmadestino}
                 and ed59_i_serie in ($codequivorig)
                 and ed232_i_codigo not in (select ed232_i_codigo 
                                              from regencia
                                                   inner join disciplina    on ed12_i_codigo  = ed59_i_disciplina
                                                   inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
                                             where ed59_i_turma = {$turmaorigem}
                                               and ed59_i_serie in ({$etapaorigem})
                                           )";
     $result2 = db_query($sql2);
     $linhas2 = pg_num_rows($result2);
     ?>
     <select name="regenciadestino" style="padding:0px;width:75px;height:16px;font-size:12px;" onchange="js_eliminareg(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     for($w=0;$w<$linhas2;$w++){
      db_fieldsmemory($result2,$w);
      echo "<option value='$regsobra'>$regsobra</option>";
     }
     ?>
     </select>
     <select name="regdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;" onchange="js_eliminareg(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     for($w=0;$w<$linhas2;$w++){
      db_fieldsmemory($result2,$w);
      echo "<option value='$regsobra'>$descrsobra</option>";
     }
     ?>
     </select>
     <input type="hidden" name="combo" value="<?=$t?>">
     <input type="hidden" name="comboselect<?=$t?>" value="">
     <?
    }
    ?>
  </td>
  <td>
   <table border="1" cellspacing="0" cellpadding="0">
    <tr>
     <?
     $result_diario = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed09_c_abrev,ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,ed37_c_tipo","ed41_i_sequencia ASC"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed09_c_somach = 'S'"));
     echo "<td width='60px' style='background:#444444;color:#DEB887;font-size:9px;'><b>$ed232_c_abrev</b></td>";
     if($cldiarioavaliacao->numrows==0){
      echo "<td width='160px' style='background:#f3f3f3;'>Nenhum registro.</td>";
     }else{
      for($v=0;$v<$cldiarioavaliacao->numrows;$v++){
       db_fieldsmemory($result_diario,$v);
       if(trim($ed37_c_tipo)=="NOTA"){
        if($resultedu=='S'){
         $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,2,",","."):"";
        }else{
         $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,0):"";
        }
       }elseif(trim($ed37_c_tipo)=="NIVEL"){
        $aproveitamento = $ed72_c_valorconceito;
       }else{
        $aproveitamento = $ed72_t_parecer!=""?"<font size='1'>Parecer</font>":"";
       }
       echo "<td width='80px' style='background:#f3f3f3;font-size:9px;'><b>$ed09_c_abrev:</b></td>
             <td width='80px' style='font-size:9px;' align='center'>".($aproveitamento==""?"&nbsp;":$aproveitamento)."</td>";
      }
     }
     ?>
    </tr>
   </table>
  </td>
 </tr>
  <?
 }
 ?>
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <b>Períodos de Avaliação TURMA DE ORIGEM:</b>
  </td>
  <td width="10"></td>
  <td valign="top" bgcolor="#CCCCCC">
   <b>Períodos de Avaliação  TURMA DE DESTINO:</b>
  </td>
 </tr>
 <?
 $result = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo,ed09_i_codigo,ed09_c_descr,ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor","ed41_i_sequencia"," ed41_i_procedimento = $procorigem"));
 $linhas = $clprocavaliacao->numrows;
 
 $sCamposProcAvaliacaoDestino  = "ed41_i_codigo as codaval, ed09_i_codigo as codperaval, ed09_c_descr as descraval";
 $sCamposProcAvaliacaoDestino .= ", ed37_c_tipo as tipodest, ed37_i_menorvalor as menordest, ed37_i_maiorvalor as maiordest";
 $sWhereProcAvaliacaoDestino   = " ed41_i_procedimento = {$procdestino}";
 $sSqlProcAvaliacaoDestino     = $clprocavaliacao->sql_query( "", $sCamposProcAvaliacaoDestino, "ed41_i_sequencia", $sWhereProcAvaliacaoDestino );
 $result1                      = $clprocavaliacao->sql_record( $sSqlProcAvaliacaoDestino );
 $linhas1                      = $clprocavaliacao->numrows;
 
 for ( $t = 0; $t < $linhas; $t++ ) {

  db_fieldsmemory($result,$t);
  $tipoavaliacao = $ed37_c_tipo.($ed37_c_tipo=='NOTA'?' ('.$ed37_i_menorvalor.' a '.$ed37_i_maiorvalor.')':'');
  ?>
  <tr>
   <td valign="top" bgcolor="#CCCCCC">
    <input name="periodoorigem" type="text" value="<?=$ed41_i_codigo?>" size="10" readonly style="width:75px">
    <input name="perorigemdescr" type="text" value="<?=$ed09_c_descr.' - '.$tipoavaliacao?>" size="30" readonly style="width:180px">
   </td>
   <td align="center">--></td>
   <td>
    <?
    $temper = false;
    for($w=0;$w<$linhas1;$w++){
     db_fieldsmemory($result1,$w);
     if($ed09_i_codigo==$codperaval){
      $temper = true;
      $periododestino = $codaval;
      $tipoavaliacao1 = $tipodest.($tipodest=='NOTA'?' ('.$menordest.' a '.$maiordest.')':'');
      $perdestinodescr = $descraval.' - '.$tipoavaliacao1;
     }
    }
    if($temper==true){
     ?>
      <input name="periododestino" type="text" value="<?=$periododestino?>" size="10" readonly style="width:75px">
      <input name="perdestinodescr" type="text" value="<?=$perdestinodescr?>" size="30" readonly style="width:180px">
     <?
    }else{
     $sql2 = "select ed41_i_codigo as persobra, 
                     ed09_c_descr as descrsobra,
                     ed37_c_tipo as tipodest,
                     ed37_i_menorvalor as menordest,
                     ed37_i_maiorvalor as maiordest
                from procavaliacao
                     inner join periodoavaliacao    on ed09_i_codigo        = ed41_i_periodoavaliacao
                     inner join formaavaliacao      on ed37_i_codigo        = ed41_i_formaavaliacao
                     inner join procedimento        on ed40_i_codigo        = ed41_i_procedimento
                     inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo
                     inner join serieregimemat      on ed223_i_codigo       = ed220_i_serieregimemat
                     inner join turma               on ed57_i_codigo        = ed220_i_turma
               where ed57_i_codigo = {$turmadestino}
                 and ed223_i_serie in ({$etapaorigem})
                 and ed09_i_codigo not in ( select ed09_i_codigo 
                                              from procavaliacao
                                                   inner join periodoavaliacao    on ed09_i_codigo        = ed41_i_periodoavaliacao
                                                   inner join procedimento        on ed40_i_codigo        = ed41_i_procedimento
                                                   inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo
                                                   inner join serieregimemat      on ed223_i_codigo       = ed220_i_serieregimemat
                                                   inner join turma               on ed57_i_codigo        = ed220_i_turma
                                             where ed57_i_codigo = $turmaorigem
                                               and ed223_i_serie in ({$etapaorigem})
                                          )
               order by ed41_i_sequencia";
     $result2 = db_query($sql2);
     $linhas2 = pg_num_rows($result2);
     ?>
     <select name="periododestino" style="padding:0px;width:75px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     for($w=0;$w<$linhas2;$w++){
      db_fieldsmemory($result2,$w);
       echo "<option value='$persobra'>$persobra</option>";
     }
     ?>
     </select>
     <select name="perdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     for($w=0;$w<$linhas2;$w++){
      db_fieldsmemory($result2,$w);
       $tipoavaliacao2 = $tipodest.($tipodest=='NOTA'?' ('.$menordest.' a '.$maiordest.')':'');
       echo "<option value='$persobra'>$descrsobra - $tipoavaliacao2</option>";
     }
     ?>
     </select>
     <input type="hidden" name="pcombo" value="<?=$t?>">
     <input type="hidden" name="pcomboselect<?=$t?>" value="">
     <?
    }
    ?>
  </td>
  <td></td>
  </tr>
  <?
 }
 ?>
 <tr>
  <td height="10" colspan="3"></td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted69_d_datatransf?>" colspan="3">
   <?=@$Led69_d_datatransf?>
   <?db_inputdata('ed69_d_datatransf',@$ed69_d_datatransf_dia,@$ed69_d_datatransf_mes,@$ed69_d_datatransf_ano,true,'text',1,"")?>
  </td>
 </tr>
 <tr>
  <td colspan="3">
   <b>Importar aproveitamento da turma de origem:</b>
   <select name="import" id='import' onchange="js_importar(this.value);">
    <option value="S">SIM</option>
    <option value="N">NÃO</option>
   </select>
  </td>
 </tr>
 <?
 $result_etp = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie,ed11_c_descr as descretapa","ed223_i_ordenacao"," ed220_i_turma = $turmadestino"));
 //db_criatabela($result_etp);exit;
 if($clturmaserieregimemat->numrows>1){
  ?>
  <tr>
   <td colspan="3">
    <?
    $tem = false;
    for($c=0;$c<$clturmaserieregimemat->numrows;$c++){
     db_fieldsmemory($result_etp,$c);
     if($ed223_i_serie==$etapaorigem){
      $tem = true;
      break;
     }
    }
    if($tem==true){
     ?>
     <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
     <?
    }else{
     ?>
     <b>Informe a Etapa na turma de destino:</b>
     <select name="codetapadestino">
       <?
       $result_equiv = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv",""," ed234_i_serie = $etapaorigem"));
       for($r=0;$r<$clturmaserieregimemat->numrows;$r++){
        db_fieldsmemory($result_etp,$r);
        $selected = "";
        $disabled = "disabled";
        if($clserieequiv->numrows>0){
         for($w=0;$w<$clserieequiv->numrows;$w++){
          db_fieldsmemory($result_equiv,$w);
          if($ed234_i_serieequiv==$ed223_i_serie){
           $selected = "selected";
           $disabled = "";
           break;
          }
         }
        }
        ?>
        <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
        <?
       }
       ?>
     </select>
     <?
    }
    ?>
   </td>
  </tr>
  <?
 }else{
  db_fieldsmemory($result_etp,0);
  ?>
  <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
  <?
 }
 ?>
 <tr>
  <td height="10" colspan="3"></td>
 </tr>
 <tr>
  <td colspan="3">
   <?
   $data=$ed69_d_datatransf_ano."-".$ed69_d_datatransf_mes."-".$ed69_d_datatransf_dia;
   $sqld="select (ed52_d_inicio) as inicio , (ed52_d_fim) as fim from calendario inner join turma on ed57_i_calendario=ed52_i_codigo where ed57_i_codigo=".$turmaorigem;
   $resultd=db_query($sqld);
   db_fieldsmemory($resultd,0);
   ?>
   <input type="button" name="incluir" value="Incluir" onclick="js_processar('<?=$inicio?>','<?=$fim?>');" <?=isset($incluir)||isset($incluir2)?"style='visibility:hidden;'":"style='position:absolute;visibility:visible;'"?>>
   <input type="button" name="incluir2" value="Incluir" onclick="js_processar2('<?=$inicio?>','<?=$fim?>');" <?=isset($incluir)||isset($incluir2)?"style='visibility:hidden;'":"style='position:absolute;visibility:hidden;'"?>>
  </td>
 </tr>
 </table>
 </form>
 </body>
 </html>
 <script>
 function js_eliminareg(valor,seq){
  C = document.form1.combo;
  RD = document.form1.regenciadestino;
  RDC = document.form1.regdestinodescr;
  tamC = C.length;
  tamC = tamC==undefined?1:tamC;
  campo = "comboselect"+seq;
  valorant = eval("document.form1."+campo+".value");
  if(tamC==1){
   tamRD = RD.length;
   for(r=0;r<tamRD;r++){
    if(parseInt(RD.options[r].value)==parseInt(valor) || parseInt(RDC.options[r].value)==parseInt(valor)){
     RD.options[r].selected = true;
     RDC.options[r].selected = true;
    }
    if(parseInt(RD.options[r].value)==parseInt(valorant) || parseInt(RDC.options[r].value)==parseInt(valorant)){
     RD.options[r].selected = false;
     RDC.options[r].selected = false;
    }
   }
  }else{
   for(i=0;i<tamC;i++){
    tamRD = RD[C[i].value].length;
    if(parseInt(C[i].value)!=parseInt(seq)){
     for(r=0;r<tamRD;r++){
      if(parseInt(RD[C[i].value].options[r].value)==parseInt(valor) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valor)){
       RD[C[i].value].options[r].disabled = true;
       RDC[C[i].value].options[r].disabled = true;
      }
      if(parseInt(RD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valorant)){
       RD[C[i].value].options[r].disabled = false;
       RDC[C[i].value].options[r].disabled = false;
      }
     }
    }else{
     for(r=0;r<tamRD;r++){
      if(parseInt(RD[C[i].value].options[r].value)==parseInt(valor) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valor)){
       RD[C[i].value].options[r].selected = true;
       RDC[C[i].value].options[r].selected = true;
      }
      if(parseInt(RD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valorant)){
       RD[C[i].value].options[r].selected = false;
               RDC[C[i].value].options[r].selected = false;
      }
     }
    }
   }
  }
  eval("document.form1."+campo+".value = valor");
 }
 function js_eliminaper(valor,seq){
  C = document.form1.pcombo;
  PD = document.form1.periododestino;
  PDC = document.form1.perdestinodescr;
  tamC = C.length;
  tamC = tamC==undefined?1:tamC;
  campo = "pcomboselect"+seq;
  valorant = eval("document.form1."+campo+".value");
  if(tamC==1){
   tamPD = PD.length;
   for(r=0;r<tamPD;r++){
    if(parseInt(PD.options[r].value)==parseInt(valor) || parseInt(PDC.options[r].value)==parseInt(valor)){
     PD.options[r].selected = true;
     PDC.options[r].selected = true;
    }
    if(parseInt(PD.options[r].value)==parseInt(valorant) || parseInt(PDC.options[r].value)==parseInt(valorant)){
     PD.options[r].selected = false;
     PDC.options[r].selected = false;
    }
   }
  }else{
   for(i=0;i<tamC;i++){
    tamPD = PD[C[i].value].length;
    if(parseInt(C[i].value)!=parseInt(seq)){
     for(r=0;r<tamPD;r++){
      if(parseInt(PD[C[i].value].options[r].value)==parseInt(valor) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valor)){
       PD[C[i].value].options[r].disabled = true;
       PDC[C[i].value].options[r].disabled = true;
       }
      if(parseInt(PD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valorant)){
       PD[C[i].value].options[r].disabled = false;
       PDC[C[i].value].options[r].disabled = false;
      }
     }
    }else{
      for(r=0;r<tamPD;r++){
      if(parseInt(PD[C[i].value].options[r].value)==parseInt(valor) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valor)){
       PD[C[i].value].options[r].selected = true;
       PDC[C[i].value].options[r].selected = true;
      }
      if(parseInt(PD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valorant)){
       PD[C[i].value].options[r].selected = false;
       PDC[C[i].value].options[r].selected = false;
      }
     }
    }
   }
  }
  eval("document.form1."+campo+".value = valor");
 }
 function js_processar(inicio,fim){

  var lImportarDisciplinas = $F('import') == 'S'? true:false;
  if(document.form1.codetapadestino.value==""){
   alert("Informe a Etapa na turma de destino!");
   return false;
  }
  RO = document.form1.regenciaorigem;
  RD = document.form1.regenciadestino;
  RC = document.form1.regorigemdescr;
  PO = document.form1.periodoorigem;
  PD = document.form1.periododestino;
  PC = document.form1.perorigemdescr;
  tamRO = RO.length;
  tamRO = tamRO==undefined?1:tamRO;
  regequiv = "";
  sepreg = "";
  msgreg = "Atenção:\nAs informações das seguintes disciplinas não serão transportadas, pois as mesmas não contém disciplinas equivalentes na turma de destino:\n\n";
  regnull = false;
  for(i=0;i<tamRO;i++){
   if(tamRO==1){
    if(RD.value!=""){
     regequiv += sepreg+RO.value+"|"+RD.value;
     sepreg = "X";
    }else{
     msgreg += RC.value+"\n";
     regnull = true;
    }
   }else{
    if(RD[i].value!=""){
     regequiv += sepreg+RO[i].value+"|"+RD[i].value;
     sepreg = "X";
    }else{
     msgreg += RC[i].value+"\n";
     regnull = true;
    }
   }
  }
  tamPO = PO.length;
  tamPO = tamPO==undefined?1:tamPO;
  perequiv = "";
  sepper = "";
  msgper = "Atenção:\nAs informações dos seguintes períodos de avaliação não serão transportadas, pois os mesmos não contém períodos de avaliação equivalentes na turma de destino:\n\n";
  pernull = false;
  for(i=0;i<tamPO;i++){
   if(tamPO==1){
    if(PD.value!=""){
     perequiv += sepper+PO.value+"|"+PD.value;
     sepper = "X";
    }else{
     msgper += PC.value+"\n";
     pernull = true;
    }
   }else{
    if(PD[i].value!=""){
     perequiv += sepper+PO[i].value+"|"+PD[i].value;
     sepper = "X";
    }else{
     msgper += PC[i].value+"\n";
     pernull = true;
    }
   }
  }
  msggeral = "";
  if(regnull==true){
   msggeral += msgreg+"\n";
  }
  if(pernull==true){
   msggeral += msgper;
  }
  tamRO = RO.length;
  tamRO = tamRO==undefined?1:tamRO;
  regselec = false;
  for(t=0;t<tamRO;t++){
   if(tamRO==1){
    if(RD.value!=""){
     regselec = true;
     break;
    }
   }else{
    if(RD[t].value!=""){
     regselec = true;
     break;
    }
   }
  }
  if(regselec == false && lImportarDisciplinas) {

   alert("Informe alguma disciplina da turma de destino para receber as informações da origem!");
   return false;
  }
  tamPO = PO.length;
  tamPO = tamPO==undefined?1:tamPO;
  perselec = false;
  for(t=0;t<tamPO;t++){
   if(tamPO==1){
    if(PD.value!=""){
     perselec = true;
     break;
    }
   }else{
    if(PD[t].value!=""){
     perselec = true;
     break;
    }
   }
  }
  if(perselec==false && lImportarDisciplinas){
   alert("Informe algum período de avaliação da turma de destino para receber as informações da origem!");
   return false;
  }
  if(document.form1.ed69_d_datatransf.value==""){
   alert("Informe a Data da Transferência!");
   return false;
  }
  datat = document.form1.ed69_d_datatransf.value;
  datat = datat.substr(6,4)+"-"+datat.substr(3,2)+"-"+datat.substr(0,2);
  check=js_validata(datat,inicio,fim);
  if(check==false){
   alert("Data da Transferência fora do periodo do calendario!");
   return false;
  }
  datat = document.form1.ed69_d_datatransf.value;
  datat = datat.substr(6,4)+""+datat.substr(3,2)+""+datat.substr(0,2);
  datamat = parent.document.form1.datamatricula.value;
  datamat = datamat.substr(6,4)+""+datamat.substr(3,2)+""+datamat.substr(0,2);
  if(parseInt(datat) < parseInt(datamat)){
   alert("Data da Transferência menor que a Data da Matrícula!");
   return false;
  }
  if(msggeral!=""){
   if(confirm(msggeral+"\n\nConfirmar Troca de Turma para o aluno?")){
    document.form1.incluir.style.visibility = "hidden";
    location.href = "edu1_alunotransfturma002.php?incluir&regequiv="+regequiv+
                    "&perequiv="+perequiv+
                    "&matricula=<?=$matricula?>&turmaorigem=<?=$turmaorigem?>&turmadestino=<?=$turmadestino?>&data="+document.form1.ed69_d_datatransf.value+
                    "&codetapadestino="+document.form1.codetapadestino.value+
                    "&iMatriculaOrigem=<?=$oGet->iMatriculaOrigem?>";
   }
  }else{
   document.form1.incluir.style.visibility = "hidden";
   location.href = "edu1_alunotransfturma002.php?incluir&regequiv="+regequiv+
                   "&perequiv="+perequiv+
                   "&matricula=<?=$matricula?>&turmaorigem=<?=$turmaorigem?>&turmadestino=<?=$turmadestino?>&data="+document.form1.ed69_d_datatransf.value+
                   "&codetapadestino="+document.form1.codetapadestino.value+
                   "&iMatriculaOrigem=<?=$oGet->iMatriculaOrigem?>";
  }
 }
 function js_importar(valor){
  if(valor=="N"){
   document.form1.incluir.style.visibility = "hidden";
   document.form1.incluir2.style.visibility = "visible";
   alert("Importar aproveitamento da turma de origem está marcado como NÃO. Caso este aluno tenha algum aproveitamento na turma de origem, este terá quer ser digitado manualmente!");
  }else{
   document.form1.incluir.style.visibility = "visible";
   document.form1.incluir2.style.visibility = "hidden";
  }
 }
 function js_processar2(inicio,fim){
  if(document.form1.codetapadestino.value==""){
   alert("Informe a Etapa na turma de destino!");
   return false;
  }
  if(document.form1.ed69_d_datatransf.value==""){
   alert("Informe a data da transferência!");
   return false;
  }
  datat = document.form1.ed69_d_datatransf.value;
  datat = datat.substr(6,4)+"-"+datat.substr(3,2)+"-"+datat.substr(0,2);
  check=js_validata(datat,inicio,fim);
  if(check==false){
   alert("Data da Transferência fora do periodo do calendario!");
   return false;
  }
  datat = document.form1.ed69_d_datatransf.value;
  datat = datat.substr(6,4)+""+datat.substr(3,2)+""+datat.substr(0,2);
  datamat = parent.document.form1.datamatricula.value;
  datamat = datamat.substr(6,4)+""+datamat.substr(3,2)+""+datamat.substr(0,2);
  if(parseInt(datat) < parseInt(datamat)){
   alert("Data da Transferência menor que a Data da Matrícula!");
   return false;
  }
  location.href = "edu1_alunotransfturma002.php?incluir2&matricula=<?=$matricula?>&turmaorigem=<?=$turmaorigem?>"+
                  "&turmadestino=<?=$turmadestino?>"+"&iMatriculaOrigem=<?=$oGet->iMatriculaOrigem?>"+
                  "&data="+document.form1.ed69_d_datatransf.value+"&codetapadestino="+document.form1.codetapadestino.value;
 }
 </script>
 <?
}
if(isset($incluir)) {
  //db_query("begin");
  db_inicio_transacao();

  $sSqlMatricula  = $clmatricula->sql_query("","ed60_i_aluno,ed60_c_rfanterior as rfanterior, ed60_i_numaluno",
                                            ""," ed60_i_codigo = $matricula");
  $result         = $clmatricula->sql_record($sSqlMatricula);
  db_fieldsmemory($result,0);
  $iNumAtualAluno = $ed60_i_numaluno;
  $sWhereMatricula = " ed60_i_turma = $turmadestino AND ed60_i_aluno = $ed60_i_aluno AND ed60_c_ativa = 'S'";
  $sMatriculaTurmaDestino = $clmatricula->sql_query_file("","ed60_i_codigo as iAlunoComMatriculaNaTurma","",
                                                         $sWhereMatricula);
  $result0                = $clmatricula->sql_record($sMatriculaTurmaDestino);

  $iAlunoComMatriculaNaTurma = "";
  if ($clmatricula->numrows > 0) {

   db_fieldsmemory($result0,0);
  }
  if ($iAlunoComMatriculaNaTurma != "") {
   $transfmatricula = $iAlunoComMatriculaNaTurma;
  }else{
   $transfmatricula = $matricula;
  }

  $clalunotransfturma->ed69_i_matricula    = $transfmatricula;
  $clalunotransfturma->ed69_i_turmaorigem  = $turmaorigem;
  $clalunotransfturma->ed69_i_turmadestino = $turmadestino;
  $clalunotransfturma->ed69_d_datatransf   = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $clalunotransfturma->incluir(null);

  $result = $clturma->sql_record($clturma->sql_query("","ed57_i_calendario,ed57_i_escola",""," ed57_i_codigo = $turmadestino"));
  db_fieldsmemory($result,0);
  $periodos      = explode("X",$perequiv);
  $msg_conversao = "";
  $sep_conversao = "";

  $aRegenciasExcluidas = array();
  $aRegencias = explode("X", $regequiv);
  if (isset($import) && $import == 'N') {

    $aRegencias = array();
    $periodos   = array();
  }
  foreach ($aRegencias as $sRegencia) {

    $aPartesRegencia = explode("|", $sRegencia);
    $iCodigoRegencia = $aPartesRegencia[1];
    $oDaoDiario              = db_utils::getDao("diario");
    $sCamposDiarioTurmaIgual = "ed95_i_codigo";
    $sWhereDiarioTurmaIgual  = "ed60_i_turma = {$turmadestino} AND ed95_i_aluno = {$ed60_i_aluno}";
    $sWhereDiarioTurmaIgual .= "AND ed95_i_regencia = {$iCodigoRegencia}";
    $sSqlDiarioTurmaIgual    = $oDaoDiario->sql_query_diario_classe(null,
                                                                    $sCamposDiarioTurmaIgual,
                                                                    null,
                                                                    $sWhereDiarioTurmaIgual);
    $rsDiarioTurmaIgual      = $oDaoDiario->sql_record($sSqlDiarioTurmaIgual);
    $iTotalDiarioTurmaIgual  = $oDaoDiario->numrows;
    if ($iTotalDiarioTurmaIgual > 0) {

        $iLinhasExclusao = $oDaoDiario->numrows;
        for ($z = 0; $z < $iLinhasExclusao; $z++) {

          $iCodigoDiario = db_utils::fieldsmemory($rsDiarioTurmaIgual, $z)->ed95_i_codigo;
          $clamparo->excluir(""," ed81_i_diario = $iCodigoDiario");
          $cldiariofinal->excluir(""," ed74_i_diario = $iCodigoDiario");
          $clparecerresult->excluir(""," ed63_i_diarioresultado in
                                         (select ed73_i_codigo from diarioresultado
                                           where ed73_i_diario = {$iCodigoDiario})");
          $cldiarioresultado->excluir(""," ed73_i_diario = $iCodigoDiario");
          $clpareceraval->excluir(""," ed93_i_diarioavaliacao in (select ed72_i_codigo
                                                                    from diarioavaliacao
                                                                   where ed72_i_diario = {$iCodigoDiario})");
          $clabonofalta->excluir(""," ed80_i_diarioavaliacao in (select ed72_i_codigo
                                                                   from diarioavaliacao
                                                                  where ed72_i_diario = {$iCodigoDiario})");
          $cldiarioavaliacao->excluir(""," ed72_i_diario = {$iCodigoDiario}");
          $claprovconselho->excluir(""," ed253_i_diario = {$iCodigoDiario}");
          $cldiario->excluir(""," ed95_i_codigo = $iCodigoDiario");
        }
     }
  }
  for ($x = 0; $x < count($periodos); $x++) {

    $divideperiodos = explode("|",$periodos[$x]);
    $periodoorigem  = $divideperiodos[0];
    $periododestino = $divideperiodos[1];
    $result_per     = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed09_i_codigo,ed09_c_descr as perdestdescricao,ed37_c_tipo as tipodestino,ed37_i_maiorvalor as mvdestino",""," ed41_i_codigo = $periododestino"));
    db_fieldsmemory($result_per,0);
    $result_per1    = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed37_c_tipo as tipoorigem,ed37_i_maiorvalor as mvorigem",""," ed41_i_codigo = $periodoorigem"));
    db_fieldsmemory($result_per1,0);
    if (trim($tipoorigem) != trim($tipodestino) ||
        (trim($tipoorigem) == trim($tipodestino) && $mvorigem != $mvdestino)) {

      $msg_conversao .= $sep_conversao." ".$perdestdescricao;
      $sep_conversao  = ",";
    }
    $regencias = explode("X", $regequiv);
    for ($r = 0; $r < count($regencias); $r++) {

      $divideregencias  = explode("|",$regencias[$r]);
      $regenciaorigem   = $divideregencias[0];
      $regenciadestino  = $divideregencias[1];
      $sCamposOrigem    = "ed95_i_codigo as coddiarioorigem";
      $sWhereOrigem     = " ed95_i_regencia = $regenciaorigem AND ed95_i_aluno = $ed60_i_aluno";
      $sSqlDiarioOrigem = $cldiario->sql_query_file("",$sCamposOrigem,"",$sWhereOrigem);
      $result11         = $cldiario->sql_record($sSqlDiarioOrigem);
      if ($cldiario->numrows > 0) {
        db_fieldsmemory($result11, 0);
      } else {
        $coddiarioorigem = 0;
      }

      $sCamposDestino    = "ed95_i_codigo";
      $sWhereDestino     = " ed95_i_regencia = $regenciadestino AND ed95_i_aluno = $ed60_i_aluno";
      $sSqlDiarioDestino = $cldiario->sql_query_file("", $sCamposDestino,"",$sWhereDestino);
      $rsDiarioAvaliacao = $cldiario->sql_record($sSqlDiarioDestino);
      if ($cldiario->numrows == 0) {

        $cldiario->ed95_c_encerrado = "N";
        $cldiario->ed95_i_escola = $ed57_i_escola;
        $cldiario->ed95_i_calendario = $ed57_i_calendario;
        $cldiario->ed95_i_aluno = $ed60_i_aluno;
        $cldiario->ed95_i_serie = $codetapadestino;
        $cldiario->ed95_i_regencia = $regenciadestino;
        $cldiario->incluir(null);
        $ed95_i_codigo = $cldiario->ed95_i_codigo;
      } else {
        $ed95_i_codigo = db_utils::fieldsMemory($rsDiarioAvaliacao, 0)->ed95_i_codigo;
      }

      $result6 = $clamparo->sql_record($clamparo->sql_query_file("","ed81_i_codigo as codamparoorigem,ed81_i_justificativa,ed81_i_convencaoamp,ed81_c_todoperiodo,ed81_c_aprovch",""," ed81_i_diario = $coddiarioorigem"));
      if ($clamparo->numrows > 0) {

        db_fieldsmemory($result6,0);
        $result7                        = $clamparo->sql_record($clamparo->sql_query_file("","ed81_i_codigo",""," ed81_i_diario = $ed95_i_codigo"));
        $clamparo->ed81_i_diario        = $ed95_i_codigo;
        $clamparo->ed81_c_aprovch       = $ed81_c_aprovch;
        $clamparo->ed81_c_todoperiodo   = $ed81_c_todoperiodo;
        $clamparo->ed81_i_justificativa = $ed81_i_justificativa;
        $clamparo->ed81_i_convencaoamp  = $ed81_i_convencaoamp;
        if ($clamparo->numrows == 0) {
          $clamparo->incluir(null);
        } else {

          db_fieldsmemory($result7,0);
          $clamparo->ed81_i_codigo = $ed81_i_codigo;
          $clamparo->alterar($ed81_i_codigo);
        }
      }
      $result9 = $cldiariofinal->sql_record($cldiariofinal->sql_query_file("","ed74_i_diario",""," ed74_i_diario = $ed95_i_codigo"));
      if ($cldiariofinal->numrows == 0) {

        $cldiariofinal->ed74_i_diario = $ed95_i_codigo;
        $cldiariofinal->incluir(null);
      }
      $sSqlDiarioAvaliacao = $cldiarioavaliacao->sql_query_file("",
                                                                "ed72_i_codigo as codavalorigem,
                                                                 ed72_i_numfaltas,
                                                                 ed72_i_valornota,
                                                                 ed72_c_valorconceito,
                                                                 ed72_t_parecer,
                                                                 ed72_c_aprovmin,
                                                                 ed72_c_amparo,
                                                                 ed72_t_obs,
                                                                 ed72_i_escola,
                                                                 ed72_c_tipo",
                                                                 "",
                                                                 " ed72_i_diario = {$coddiarioorigem}
                                                                 AND ed72_i_procavaliacao = {$periodoorigem}"
                                                                 );

      $result3             = $cldiarioavaliacao->sql_record($sSqlDiarioAvaliacao);
      if ($cldiarioavaliacao->numrows > 0) {
        db_fieldsmemory($result3,0);
      } else {
        $codavalorigem = "";
        $ed72_i_numfaltas = null;
        $ed72_i_valornota = null;
        $ed72_c_valorconceito = "";
        $ed72_t_parecer = "";
        $ed72_c_aprovmin = "N";
        $ed72_c_amparo = "N";
        $ed72_t_obs = "";
        $ed72_i_escola = db_getsession("DB_coddepto");
        $ed72_c_tipo = "M";
      }
      if (trim($tipoorigem)!=trim($tipodestino) || (trim($tipoorigem)==trim($tipodestino) && $mvorigem!=$mvdestino)) {

        if ($ed72_i_valornota=="" && $ed72_c_valorconceito=="" && $ed72_t_parecer=="") {
          $ed72_c_convertido = "N";
        } else {
          $ed72_c_convertido = "S";
        }
      } else {
       $ed72_c_convertido = "N";
      }

      $result4 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query_file("",
                                                                                   "ed72_i_codigo",
                                                                                   "",
                                                                                   " ed72_i_diario = {$ed95_i_codigo}
                                                                                   AND ed72_i_procavaliacao = $periododestino")
                                                                                  );
      $cldiarioavaliacao->ed72_i_diario        = $ed95_i_codigo;
      $cldiarioavaliacao->ed72_i_procavaliacao = $periododestino;
      $cldiarioavaliacao->ed72_i_numfaltas     = $ed72_i_numfaltas;
      $cldiarioavaliacao->ed72_i_valornota     = $ed72_i_valornota;
      $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
      $cldiarioavaliacao->ed72_t_parecer       = pg_escape_string($ed72_t_parecer);
      $cldiarioavaliacao->ed72_c_aprovmin      = $ed72_c_aprovmin;
      $cldiarioavaliacao->ed72_c_amparo        = $ed72_c_amparo;
      $cldiarioavaliacao->ed72_t_obs           = $ed72_t_obs;
      $cldiarioavaliacao->ed72_i_escola        = $ed72_i_escola;
      $cldiarioavaliacao->ed72_c_tipo          = $ed72_c_tipo;
      $cldiarioavaliacao->ed72_c_convertido    = $ed72_c_convertido;
      if ($cldiarioavaliacao->numrows == 0) {

        $cldiarioavaliacao->incluir(null);
        $ed72_i_codigo = $cldiarioavaliacao->ed72_i_codigo;
      } else {

        db_fieldsmemory($result4,0);
        $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
        $cldiarioavaliacao->alterar($ed72_i_codigo);
      }
      if($codavalorigem != ""){

        $result_transfaprov = $cltransfaprov->sql_record($cltransfaprov->sql_query_file("","ed251_i_codigo",""," ed251_i_diariodestino = $codavalorigem"));
        if ($cltransfaprov->numrows > 0) {

          db_fieldsmemory($result_transfaprov,0);
          $cltransfaprov->ed251_i_diariodestino = $ed72_i_codigo;
          $cltransfaprov->ed251_i_codigo        = $ed251_i_codigo;
          $cltransfaprov->alterar($ed251_i_codigo);
        } else {

          if($ed72_c_convertido == "S") {

            $cltransfaprov->ed251_i_diariodestino = $ed72_i_codigo;
            $cltransfaprov->ed251_i_diarioorigem  = $codavalorigem;
            $cltransfaprov->incluir(null);
          }
        }
      }
      if ($codavalorigem != "") {

        $sSqlPareceres = $clpareceraval->sql_query_file("","ed93_t_parecer","",
                                                        " ed93_i_diarioavaliacao = $codavalorigem");

        $result41 = $clpareceraval->sql_record($sSqlPareceres);
        $linhas41 = $clpareceraval->numrows;
        if($linhas41 > 0) {

          $clpareceraval->excluir(""," ed93_i_diarioavaliacao = $ed72_i_codigo");
          for($w = 0; $w <$linhas41; $w++) {

            db_fieldsmemory($result41,$w);
            $clpareceraval->ed93_i_diarioavaliacao = $ed72_i_codigo;
            $clpareceraval->ed93_t_parecer         = $ed93_t_parecer;
            $clpareceraval->incluir(null);
          }
        }
        $result42 = $clabonofalta->sql_record($clabonofalta->sql_query_file("","ed80_i_codigo",""," ed80_i_diarioavaliacao = $codavalorigem"));
        $linhas42 = $clabonofalta->numrows;
        if($linhas42>0){

          for($w = 0; $w < $linhas42; $w++) {

            db_fieldsmemory($result42,$w);
            $clabonofalta->ed80_i_diarioavaliacao = $ed72_i_codigo;
            $clabonofalta->ed80_i_codigo          = $ed80_i_codigo;
            $clabonofalta->alterar($ed80_i_codigo);
          }
        }
      }
    }
  }

  $result_orig = $clturma->sql_record($clturma->sql_query("","ed57_c_descr as ed57_c_descrorig,ed57_i_base as baseorig,ed57_i_calendario as calorig,ed57_i_turno as turnoorig,ed10_i_codigo as ensinorigem",""," ed57_i_codigo = $turmaorigem"));
  db_fieldsmemory($result_orig,0);

  $result_alu = $clalunocurso->sql_record($clalunocurso->sql_query_file("","ed56_i_codigo",""," ed56_i_aluno = $ed60_i_aluno"));
  db_fieldsmemory($result_alu,0);

  $result_dest = $clturma->sql_record($clturma->sql_query("","ed57_c_descr as ed57_c_descrdest,ed57_i_base as basedest,ed57_i_calendario as caldest,ed57_i_turno as turnodest, ed10_i_codigo as ensinodestino",""," ed57_i_codigo = $turmadestino"));
  db_fieldsmemory($result_dest,0);

  $result1 = $clmatricula->sql_record($clmatricula->sql_query_file("","max(ed60_i_numaluno)",""," ed60_i_turma = $turmadestino"));
  db_fieldsmemory($result1,0);

  $max = $max==""?"null":($max+1);

  $ed60_d_datamodif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $result_etp       = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie","ed223_i_ordenacao"," ed220_i_turma = $turmadestino"));
  if($ensinorigem == $ensinodestino) {

    if (empty($oGet->iMatriculaOrigem)) {
      $oGet->iMatriculaOrigem = null;
    }

    if ($iAlunoComMatriculaNaTurma!="") {

      $sql  = " UPDATE matricula                            ";
      $sql .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql .= "        ed60_c_ativa     = 'N',              ";
      $sql .= "        ed60_d_datasaida = '".date("Y-m-d", db_getsession("DB_datausu"))."'";
      $sql .= "  WHERE ed60_i_codigo    = {$iAlunoComMatriculaNaTurma}";
      $query = db_query($sql);
      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem);
      $matrmov = $iAlunoComMatriculaNaTurma;
      LimpaResultadofinal($iAlunoComMatriculaNaTurma);
    } else {

      $sql  = " UPDATE matricula                            ";
      $sql .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql .= "        ed60_c_ativa     = 'N',              ";
      $sql .= "        ed60_d_datasaida = '".date("Y-m-d", db_getsession("DB_datausu"))."', ";
      $sql .= "        ed60_d_datamodif = '".date("Y-m-d", db_getsession("DB_datausu"))."'";
      $sql .= "  WHERE ed60_i_codigo    = {$matricula}      ";
      $query = db_query($sql);
      $matrmov = $matricula;
      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem);
      LimpaResultadofinal($matricula);
    }
  } else {     ///termina ensino igual else

    $sql = "UPDATE matricula SET
             ed60_d_datamodif = '$ed60_d_datamodif',
             ed60_c_situacao= 'TROCA DE MODALIDADE',
             ed60_d_datamodifant = '$ed60_d_datamodif',
             ed60_d_datasaida = '$ed60_d_datamodif',
             ed60_c_concluida = 'S'
            WHERE ed60_i_codigo = $matricula
           ";
    $query = db_query($sql);
    $sql21 = "UPDATE diario SET
               ed95_c_encerrado = 'S'
              WHERE ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $turmaorigem)
              AND ed95_i_aluno = $ed60_i_aluno
             ";
    $result21 = db_query($sql21);
    LimpaResultadofinal($matricula);
    $clmatricula->ed60_d_datamodif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
    $clmatricula->ed60_d_datamatricula = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
    $clmatricula->ed60_d_datamodifant = null;
    $clmatricula->ed60_d_datasaida = null;
    $clmatricula->ed60_t_obs = "";
    $clmatricula->ed60_i_aluno = $ed60_i_aluno;
    $clmatricula->ed60_i_turma = $turmadestino;
    $clmatricula->ed60_i_turmaant = $turmaorigem;
    $clmatricula->ed60_c_rfanterior = $rfanterior;
    $clmatricula->ed60_i_numaluno = $max;
    $clmatricula->ed60_c_situacao = "MATRICULADO";
    $clmatricula->ed60_c_concluida = "N";
    $clmatricula->ed60_c_ativa = "S";
    $clmatricula->ed60_c_tipo = "N";
    $clmatricula->ed60_c_parecer = "N";
    $clmatricula->ed60_matricula = $oGet->iMatriculaOrigem;
    $clmatricula->incluir(null);
    $matrmov = $clmatricula->ed60_i_codigo;

    for ($rr = 0; $rr < $clturmaserieregimemat->numrows; $rr++) {

      db_fieldsmemory($result_etp,$rr);
      if ($codetapadestino == $ed223_i_serie) {
       $origem = "S";
      }else{
       $origem = "N";
      }
      $clmatriculaserie->ed221_i_matricula = $matrmov;
      $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
      $clmatriculaserie->ed221_c_origem    = $origem;
      $clmatriculaserie->incluir(null);
    }
  }
  if ($ensinorigem == $ensinodestino) {

    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE TURMA";
    $clmatriculamov->ed229_t_descr        = "ALUNO TROCOU DE TURMA, PASSANDO DA TURMA ".trim($ed57_c_descrorig)." PARA A TURMA ".trim($ed57_c_descrdest);
  }else{

    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE MODALIDADE";
    $clmatriculamov->ed229_t_descr        = "ALUNO TROCOU DE MODALIDADE, PASSANDO DA TURMA ".trim($ed57_c_descrorig)." PARA A TURMA ".trim($ed57_c_descrdest);
  }
  $clmatriculamov->ed229_i_matricula  = $matrmov;
  $clmatriculamov->ed229_i_usuario    = db_getsession("DB_id_usuario");
  $clmatriculamov->ed229_d_dataevento = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $clmatriculamov->ed229_c_horaevento = date("H:i");
  $clmatriculamov->ed229_d_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clmatriculamov->incluir(null);
  $sSqlMatricula = $clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $turmadestino AND ed60_c_situacao = 'MATRICULADO'");
  $result_qtd    = $clmatricula->sql_record($sSqlMatricula);
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turma SET
            ed57_i_nummatr = $qtdmatricula
           WHERE ed57_i_codigo = $turmadestino
           ";
  $query1     = db_query($sql1);
  $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $turmaorigem AND ed60_c_situacao = 'MATRICULADO'"));
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turma SET
            ed57_i_nummatr = $qtdmatricula
           WHERE ed57_i_codigo = $turmaorigem
           ";
  $query1 = db_query($sql1);
  $sql_alunocurso = "UPDATE alunocurso SET
                      ed56_i_base = $basedest,
                      ed56_i_calendario = $caldest
                     WHERE ed56_i_codigo = $ed56_i_codigo
                    ";
  $result_alunocurso = db_query($sql_alunocurso);
  $sql_alunopossib = "UPDATE alunopossib SET
                       ed79_i_serie = $codetapadestino,
                       ed79_i_turno = $turnodest
                      WHERE ed79_i_alunocurso = $ed56_i_codigo
                     ";
  $result_alunopossib = db_query($sql_alunopossib);

  if (!empty($iNumAtualAluno)) {

    $oDaoEduNumalunoBloqueado = db_utils::getdao('edu_numalunobloqueado');
    $oDaoEduNumalunoBloqueado->ed289_i_numaluno = $iNumAtualAluno;
    $oDaoEduNumalunoBloqueado->ed289_i_turma    = $turmaorigem;
    $oDaoEduNumalunoBloqueado->incluir(null);

  }


  db_fim_transacao();
  if($msg_conversao!=""){
   $mensagem = "ATENÇÃO!\\n\\n Caso o aluno tenha algum aproveitamento nos períodos abaixo relacionados, os mesmos deverão ser convertidos no Diário de Classe, devido a forma de avaliação da turma de origem ser diferente da turma de destino:\\n\\n$msg_conversao";
   db_msgbox($mensagem);
  }

  if ($clalunotransfturma->erro_status == 0) {
    $clalunotransfturma->erro(true,false);
  } else {

    $sMsgSucesso = "Troca de turma realizada com sucesso.\n";
    $oMatricula  = MatriculaRepository::getMatriculaByCodigo($matricula);
    if ( count( $oMatricula->getAluno()->getProgressaoParcial() ) > 0 ) {

      $sMsgSucesso .= "Aluno {$oMatricula->getAluno()->getNome()} com Progressão Parcial ATIVA. \n";
      $sMsgSucesso .= "Acesse:\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma";
    }
    db_msgbox($sMsgSucesso);

  }
  ?><script>parent.location.href = "edu1_alunotransfturma001.php";</script><?
}

if (isset($incluir2)) {

//  die ('asdsada 2222 ');
  db_inicio_transacao();
  $result = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_aluno,ed60_c_rfanterior as rfanterior",""," ed60_i_codigo = $matricula"));
  db_fieldsmemory($result,0);
  $sWhereMatricula = " ed60_i_turma = $turmadestino AND ed60_i_aluno = $ed60_i_aluno AND ed60_c_ativa = 'S'";
  $result0 = $clmatricula->sql_record($clmatricula->sql_query_file("","ed60_i_codigo as iAlunoComMatriculaNaTurma","",$sWhereMatricula));
  $iAlunoComMatriculaNaTurma = "";
  if($clmatricula->numrows>0){

    db_fieldsmemory($result0,0);
    $iAlunoComMatriculaNaTurma = $ialunocommatriculanaturma;
  }
  if ($iAlunoComMatriculaNaTurma !="") {
    $transfmatricula = $iAlunoComMatriculaNaTurma;
  }else{
    $transfmatricula = $matricula;
  }
  $clalunotransfturma->ed69_i_matricula    = $transfmatricula;
  $clalunotransfturma->ed69_i_turmaorigem  = $turmaorigem;
  $clalunotransfturma->ed69_i_turmadestino = $turmadestino;
  $clalunotransfturma->ed69_d_datatransf = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $clalunotransfturma->incluir(null);
  $result = $clturma->sql_record($clturma->sql_query("","ed57_i_calendario,ed57_i_escola",""," ed57_i_codigo = $turmadestino"));
  db_fieldsmemory($result,0);
  $result_orig = $clturma->sql_record($clturma->sql_query("","ed57_c_descr as ed57_c_descrorig,ed57_i_base as baseorig,ed57_i_calendario as calorig,ed57_i_turno as turnoorig,ed10_i_codigo as ensinorigem",""," ed57_i_codigo = $turmaorigem"));
  db_fieldsmemory($result_orig,0);
  $result_alu = $clalunocurso->sql_record($clalunocurso->sql_query_file("","ed56_i_codigo",""," ed56_i_aluno = $ed60_i_aluno"));
  db_fieldsmemory($result_alu,0);
  $result_dest = $clturma->sql_record($clturma->sql_query("","ed57_c_descr as ed57_c_descrdest,ed57_i_base as basedest,ed57_i_calendario as caldest,ed57_i_turno as turnodest, ed10_i_codigo as ensinodestino",""," ed57_i_codigo = $turmadestino"));
  db_fieldsmemory($result_dest,0);
  $result1 = $clmatricula->sql_record($clmatricula->sql_query_file("","max(ed60_i_numaluno)",""," ed60_i_turma = $turmadestino"));
  db_fieldsmemory($result1,0);
  $max = $max==""?"null":($max+1);
  $ed60_d_datamodif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $result_etp = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie","ed223_i_ordenacao"," ed220_i_turma = $turmadestino"));
  if ($ensinorigem == $ensinodestino){

    $result_del = $cldiario->sql_record($cldiario->sql_query_file("","ed95_i_codigo as coddiariodel",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $turmaorigem)"));
    $linhas_del = $cldiario->numrows;
    for($z=0;$z<$linhas_del;$z++) {

      db_fieldsmemory($result_del,$z);
      $clamparo->excluir(""," ed81_i_diario = $coddiariodel");
      $cldiariofinal->excluir(""," ed74_i_diario = $coddiariodel");
      $clparecerresult->excluir(""," ed63_i_diarioresultado in (select ed73_i_codigo from diarioresultado where ed73_i_diario = $coddiariodel)");
      $cldiarioresultado->excluir(""," ed73_i_diario = $coddiariodel");
      $clpareceraval->excluir(""," ed93_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao where ed72_i_diario = $coddiariodel)");
      $clabonofalta->excluir(""," ed80_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao where ed72_i_diario = $coddiariodel)");
      $cldiarioavaliacao->excluir(""," ed72_i_diario = $coddiariodel");
      $claprovconselho->excluir(""," ed253_i_diario = $coddiariodel");
      $cldiario->excluir(""," ed95_i_codigo = $coddiariodel");
    }

    if (empty($oGet->iMatriculaOrigem)) {
      $oGet->iMatriculaOrigem = null;
    }

    if ($iAlunoComMatriculaNaTurma != "") {

      $sql  = " UPDATE matricula                            ";
      $sql .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql .= "        ed60_c_ativa     = 'N',              ";
      $sql .= "        ed60_d_datasaida = '".date("Y-m-d", db_getsession("DB_datausu"))."'";
      $sql .= "  WHERE ed60_i_codigo    = {$iAlunoComMatriculaNaTurma}";
      $query = db_query($sql);
      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem);
      LimpaResultadofinal($iAlunoComMatriculaNaTurma);
      $matrmov = $matricula;
    } else {

      $sql  = " UPDATE matricula                            ";
      $sql .= "    SET ed60_c_situacao  = 'TROCA DE TURMA', ";
      $sql .= "        ed60_c_ativa     = 'N',              ";
      $sql .= "        ed60_d_datasaida = '".date("Y-m-d", db_getsession("DB_datausu"))."', ";
      $sql .= "        ed60_d_datamodif = '".date("Y-m-d", db_getsession("DB_datausu"))."'";
      $sql .= "  WHERE ed60_i_codigo    = {$matricula}      ";
      $query = db_query($sql);
      trocaTurma($matricula, $turmadestino, false, $oGet->iMatriculaOrigem);
      LimpaResultadofinal($matricula);
      $matrmov = $matricula;
    }
  } else{     ///termina ensino igual else

    $sql = "UPDATE matricula SET
    ed60_d_datamodif = '$ed60_d_datamodif',
    ed60_c_situacao= 'TROCA DE MODALIDADE',
    ed60_d_datamodifant = '$ed60_d_datamodif',
    ed60_d_datasaida = '$ed60_d_datamodif',
    ed60_c_concluida = 'S'
    WHERE ed60_i_codigo = $matricula
    ";
    $query = db_query($sql);
    $sql21 = "UPDATE diario SET
    ed95_c_encerrado = 'S'
    WHERE ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $turmaorigem)
    AND ed95_i_aluno = $ed60_i_aluno
    ";
    $result21 = db_query($sql21);
    LimpaResultadofinal($matricula);
    $clmatricula->ed60_d_datamodif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
    $clmatricula->ed60_d_datamatricula = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
    $clmatricula->ed60_d_datamodifant = null;
    $clmatricula->ed60_d_datasaida = null;
    $clmatricula->ed60_t_obs = "";
    $clmatricula->ed60_i_aluno = $ed60_i_aluno;
    $clmatricula->ed60_i_turma = $turmadestino;
    $clmatricula->ed60_i_turmaant = $turmaorigem;
    $clmatricula->ed60_c_rfanterior = $rfanterior;
    $clmatricula->ed60_i_numaluno = $max;
    $clmatricula->ed60_c_situacao = "MATRICULADO";
    $clmatricula->ed60_c_concluida = "N";
    $clmatricula->ed60_c_ativa = "S";
    $clmatricula->ed60_c_tipo = "N";
    $clmatricula->ed60_matricula = $oGet->iMatriculaOrigem;
    $clmatricula->ed60_c_parecer = "N";
    $clmatricula->incluir(null);
    $matrmov = $clmatricula->ed60_i_codigo;
    for($rr=0;$rr<$clturmaserieregimemat->numrows;$rr++){
    db_fieldsmemory($result_etp,$rr);
    if($codetapadestino==$ed223_i_serie){
    $origem = "S";
    }else{
        $origem = "N";
        }
        $clmatriculaserie->ed221_i_matricula = $matrmov;
        $clmatriculaserie->ed221_i_serie = $ed223_i_serie;
        $clmatriculaserie->ed221_c_origem = $origem;
        $clmatriculaserie->incluir(null);
    }
  }
  if($ensinorigem==$ensinodestino){
    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE TURMA";
    $clmatriculamov->ed229_t_descr = "ALUNO TROCOU DE TURMA, PASSANDO DA TURMA ".trim($ed57_c_descrorig)." PARA A TURMA ".trim($ed57_c_descrdest);
  }else{
    $clmatriculamov->ed229_c_procedimento = "TROCAR ALUNO DE MODALIDADE";
    $clmatriculamov->ed229_t_descr = "ALUNO TROCOU DE MODALIDADE, PASSANDO DA TURMA ".trim($ed57_c_descrorig)." PARA A TURMA ".trim($ed57_c_descrdest);
  }
  $clmatriculamov->ed229_i_matricula = $matrmov;
  $clmatriculamov->ed229_i_usuario = db_getsession("DB_id_usuario");
  $clmatriculamov->ed229_d_dataevento = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $clmatriculamov->ed229_c_horaevento = date("H:i");
  $clmatriculamov->ed229_d_data = date("Y-m-d",db_getsession("DB_datausu"));
  $clmatriculamov->incluir(null);
  $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $turmadestino AND ed60_c_situacao = 'MATRICULADO'"));
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turma SET
  ed57_i_nummatr = $qtdmatricula
  WHERE ed57_i_codigo = $turmadestino
  ";
  $query1 = db_query($sql1);
  $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $turmaorigem AND ed60_c_situacao = 'MATRICULADO'"));
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turma SET
  ed57_i_nummatr = $qtdmatricula
  WHERE ed57_i_codigo = $turmaorigem
  ";
  $query1 = db_query($sql1);
  $sql_alunocurso = "UPDATE alunocurso SET
  ed56_i_base = $basedest,
  ed56_i_calendario = $caldest
  WHERE ed56_i_codigo = $ed56_i_codigo
  ";
  $result_alunocurso = db_query($sql_alunocurso);
  $sql_alunopossib = "UPDATE alunopossib SET
  ed79_i_serie = $codetapadestino,
  ed79_i_turno = $turnodest
  WHERE ed79_i_alunocurso = $ed56_i_codigo
  ";
  $result_alunopossib = db_query($sql_alunopossib);
  db_fim_transacao(false);


  if ($clalunotransfturma->erro_status == 0 ) {
    $clalunotransfturma->erro(true,false);
  } else {

    $sMsgSucesso = "Troca de turma realizada com sucesso.\n";
    $oMatricula  = MatriculaRepository::getMatriculaByCodigo($matricula);
    if ( count( $oMatricula->getAluno()->getProgressaoParcial() ) > 0 ) {

      $sMsgSucesso .= "Aluno {$oMatricula->getAluno()->getNome()} com Progressão Parcial ATIVA. \n";
      $sMsgSucesso .= "Acesse:\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial\n";
      $sMsgSucesso .= "\tMatrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma";
    }
    db_msgbox($sMsgSucesso);
  }

  ?>
  <script>
   parent.location.href = "edu1_alunotransfturma001.php";
   </script><?

}
?>
<script>document.getElementById("tab_aguarde").style.visibility = "hidden";</script>