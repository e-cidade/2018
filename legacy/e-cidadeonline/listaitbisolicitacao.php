<?
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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaitbi.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
if(isset($HTTP_POST_VARS['id_itbi'])) {
  db_postmemory($HTTP_POST_VARS);
  $areaedificada = $areaedificada + 0;
  $areaterreno = $areaterreno+0; 
  $areareal = $areareal+0;  
  $valortransacao = $valortransacao+0;
  $mfrente = $mfrente+0;
  $mladodireito = $mladodireito+0;
  $mfundos = $mfundos+0;
  $mladoesquerdo = $mladoesquerdo+0;
  $numerocomprador = $numerocomprador+0;
  if(empty($id_itbi)) {
//    $result = db_query("select max(id_itbi) from db_itbi");
    $result = db_query("select nextval('db_itbi_id_itbi_seq')");
//    $id_itbi = (integer)pg_result($result,0,0) + 1;
    $id_itbi = (integer)pg_result($result,0,0);
    db_query("BEGIN");
    //Soma das caracteristicas e grava
        /*
    $tam_vetor = sizeof($HTTP_POST_VARS);
    $areaedificada = 0;
    reset($HTTP_POST_VARS);
    for($i = 0;$i < $tam_vetor;$i++) {
      if(db_indexOf(key($HTTP_POST_VARS),"CARAC") > 0) {
        $str = "insert into db_caritbilan values($id_itbi,".db_parse_int(key($HTTP_POST_VARS)).",".$HTTP_POST_VARS[key($HTTP_POST_VARS)].")";
        db_query($str);
        $areaedificada += $HTTP_POST_VARS[key($HTTP_POST_VARS)];        
      }
      next($HTTP_POST_VARS);
    } 
        */
        for($i=0;$i<10;$i++){
           $carconstr = "cardescr$i";
           $tipo = "tipo$i";
           $area = "area_$i";
           $anoconstr = "anoconstr$i";
           if(($$area+0)>0){
         $str = "insert into db_caritbilan 
                            values($id_itbi,
                                               ".$$tipo.",
                                                   ".$$area.",
                                                   '".$$carconstr."',
                                                   ".$$anoconstr."
                                                   )";
         db_query($str);
           }
        } 
    $result = db_query("insert into db_itbi(matricula,
                                       areaterreno,
                                       areareal,
                                       situacao,
                                       areaedificada,
                                       nomecomprador,
                                       cgccpfcomprador,
                                       enderecocomprador,
                                       municipiocomprador,
                                       bairrocomprador,
                                       cepcomprador,
                                       ufcomprador,
                                       numerocomprador,
                                       complcomprador,
                                       cxpostcomprador,
                                       tipotransacao,
                                       valortransacao,
                                       caracteristicas,
                                       mfrente,
                                       mladodireito,
                                       mfundos,
                                       mladoesquerdo,
                                       email,
                                       obs,
                                       id_itbi,
                                       datasolicitacao)
                       values ('$cod_matricula',
                                       $areaterreno,
                                       $areareal,
                                       '$situacao',
                                       $areaedificada,
                                       '$nomecomprador',
                                       '$cgccpfcomprador',
                                       '$enderecocomprador',
                                       '$municipiocomprador',
                                       '$bairrocomprador',
                                       '$cepcomprador',
                                       '$ufcomprador',
                                       $numerocomprador,
                                       '$complcomprador',
                                       $cxpostcomprador',
                                       '$tipotransacao',
                                       $valortransacao,
                                       'caracteristicas',
                                       $mfrente,
                                       $mladodireito,
                                       $mfundos,
                                       $mladoesquerdo,
                                       '$email',
                                       '$obs',
                                       $id_itbi,
                                       '".date("Y-m-d")."')
                                       ") or die('Erro no Sql');
    db_query("COMMIT");
        db_msgbox2("Solicitação gerada com o número $id_itbi");
        redireciona("digitaitbi.php");
        exit;
  }else{
    db_query("BEGIN");
    //Soma das caracteristicas e grava
        /*
    $tam_vetor = sizeof($HTTP_POST_VARS);
    $areaedificada = 0;
    reset($HTTP_POST_VARS);
    for($i = 0;$i < $tam_vetor;$i++) {
      if(db_indexOf(key($HTTP_POST_VARS),"CARAC") > 0) {
        $str = "update db_caritbilan  set area = ".$HTTP_POST_VARS[key($HTTP_POST_VARS)]."
                           where codcaritbi = ".db_parse_int(key($HTTP_POST_VARS));
        db_query($str);
        $areaedificada += $HTTP_POST_VARS[key($HTTP_POST_VARS)];        
      }
      next($HTTP_POST_VARS);
    } 
        */ 
    $str = "delete from db_caritbilan where id_itbi = $id_itbi";
        db_query($str);
        for($i=0;$i<10;$i++){
           $carconstr = "cardescr$i";
           $tipo = "tipo$i";
           $area = "area_$i";
           $anoconstr = "anoconstr$i";
           if(($$area+0)>0){
         $str = "insert into db_caritbilan 
                 values($id_itbi,
                        ".$$tipo.",
                        ".$$area.",
                        '".$$carconstr."',
                        ".$$anoconstr."
                        )";
        db_query($str);
          }
        } 
    $result = db_query("update db_itbi set matricula ='$cod_matricula' ,
                                       areaterreno=$areaterreno,
                                       areareal=$areareal,
                                       situacao='$situacao',
                                       areaedificada=$areaedificada,
                                       nomecomprador='$nomecomprador',
                                       cgccpfcomprador='$cgccpfcomprador',
                                       enderecocomprador='$enderecocomprador',
                                       municipiocomprador='$municipiocomprador',
                                       bairrocomprador='$bairrocomprador',
                                       cepcomprador='$cepcomprador',
                                       ufcomprador='$ufcomprador',
                                       numerocomprador=$numerocomprador,
                                       complcomprador='$complcomprador',
                                       cxpostcomprador='$cxpostcomprador',
                                       tipotransacao='$tipotransacao',
                                       valortransacao='$valortransacao',
                                       caracteristicas='caracteristicas',
                                       mfrente=$mfrente,
                                       mladodireito=$mladodireito,
                                       mfundos=$mfundos,
                                       mladoesquerdo=$mladoesquerdo,
                                       email='$email',
                                       obs='$obs'
                       where id_itbi = $id_itbi") or die('Erro no Sql');
    if(pg_cmdtuples($result) == 0) {
      db_query("rollback");
          db_msgbox2('Erro ao Gravar Solicitação.');
          redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
          exit;
    } else {
          db_query("commit");
            db_msgbox2('Solicitação alterada com sucesso');
          redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
          exit;
        }
  }
}
mens_help();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$cod_matricula = 0 + $matricula;
if ( !is_int($cod_matricula) or $cod_matricula == "" ){
   msgbox("Código da Matrícula Inválido.");
   db_logs("","",0,"Código da Matrícula Inválido.");
   redireciona("digitaitbi.php");
}

$result = db_query("select * from db_itbi where matricula = $cod_matricula and libpref = '1'");
if (pg_numrows($result) > 0){
   msgbox("Socilitação de Guia de ITBI está em processo de avaliação. Volte mais tarde.");
   db_logs("$cod_matricula","",0,"Socilitação de Guia de ITBI está em processo de avaliação. Volte mais tarde. Numero: $cod_matricula");
   redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
}
$result = db_query("select * from db_itbi where matricula = $cod_matricula and liberado = 1 and ( datavencimento is null or datavencimento >= CURRENT_DATE)");
if (pg_numrows($result) != 0){
   msgbox("Verifique Liberação de Guia.");
   db_logs("$cod_matricula","",0,"Verifique Liberacao da Guia. Numero: $cod_matricula");
   redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
}
$result = db_query("select * from db_itbi where matricula = $cod_matricula and (liberado is null or liberado = 0)");
if (pg_numrows($result) != 0){
  msgbox("Socilitação Recentemente Encaminhada. Proceda as Alterações.");
  db_logs("$cod_matricula","",0,"Socilitação Recentemente Encaminhada. Proceda as Alterações. Numero: $cod_matricula");
  db_fieldsmemory($result,0);
}
/*$result = db_query("select ctmbase.*,zona.*,cgm.z01_nome as imobiliaria from (select *
                                  from ctmbase
                                                                   left outer join lote on lote.v04_codbai = substr(ctmbase.v11_bql,1,4) and lote.v04_codqua = substr(ctmbase.v11_bql,5,4) and lote.v04_codlot = substr(ctmbase.v11_bql,9,4)
                                                                   left outer join edifica on edifica.v12_matric = ctmbase.v11_matric
                                                                   left outer join imobil on imobil.v28_matric = ctmbase.v11_matric
                                                                   left outer join cadlog on cadlog.v01_codigo = ctmbase.v11_lograd
                                                                   left outer join iptucalc on iptucalc.v23_matric = ctmbase.v11_matric and iptucalc.v23_anoexe = 2002
                                                                           , cgm
                                  where ctmbase.v11_matric = $cod_matricula and ctmbase.v11_numcgm = cgm.z01_numcgm
                                                                  ) as ctmbase
                                  left outer join zona on zona.v07_zona = ctmbase.v04_zona and zona.v07_setor = ctmbase.v04_setor
                                  left outer join cgm on ctmbase.v28_numcgm = cgm.z01_numcgm");
*/
$result = db_query("select p.*,pm.z01_nome as promitente, m.z01_nome as imobiliaria
                   from proprietario p
                   left outer join promitente o 
                     on o.j41_matric = p.j01_matric
                   left outer join cgm pm 
                     on pm.z01_numcgm = o.j41_numcgm
                   left outer join imobil i 
                     on i.j44_matric = p.j01_matric
                   left outer join cgm m
                     on m.z01_numcgm = i.j44_numcgm
                   where j01_matric = $cod_matricula");
if (pg_numrows($result) == 0){
   msgbox("Matrícula não Cadastrada.");
   db_logs("$cod_matricula","",0,"Matrícula não Cadastrada. Numero: $cod_matricula");
   redireciona("index.php");
   exit;
}
db_fieldsmemory($result,0);
db_logs("$cod_matricula","",0,"Solicitacao de Guia. Numero: $cod_matricula");
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesitbi.php");
function jsss_verificadados() {
/*  for(contador = 0; contador < document.form1.elements.length; contador++) {
    if(document.form1.elements[contador].type != "hidden" && 
       document.form1.elements[contador].name != "tipotransacao" && 
       document.form1.elements[contador].name != "caracteristicas" && 
       document.form1.elements[contador].name != "areaedificada" && 
       document.form1.elements[contador].name != "obs"  
       document.form1.elements[contador].name.substr(0,3) != "are"  
       document.form1.elements[contador].name.substr(0,3) != "car"  
       document.form1.elements[contador].name.substr(0,3) != "car" )  
    {
      if (document.form1.elements[contador].value == "" ){
        alert("Campo Inválido.");
             document.form1.elements[contador].focus(); 
            return;
      }
    }
  }
  */
  if ( document.form1.areaedificada.value == ""  ){
    document.form1.areaedificada.value = '0';
  }
  if (isNaN(document.form1.areaterreno.value) ){
     alert("Area Territorial da Transmissão Inválida");
         document.form1.areaterreno.focus(); 
         return;
  }
  if ( isNaN(document.form1.areaedificada.value) ){
     alert("Area Predial da Transmissão Inválida");
         document.form1.areaedificada.focus(); 
         return;
  }
  document.form1.submit();
}
function js_soma() {
  var F = document.form1;
  F.areaconstruida.value = 0;
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "text") {
      var str = new String(F.elements[i].name);
      if(str.indexOf("area_") != -1)
            F.areaconstruida.value = Number(F.areaconstruida.value) + Number(F.elements[i].value);
        }
  }
}
</script>
<style type="text/css">
<?db_estilosite();
echo"
.tabfonte {
               font-family: $w01_fontesite;
          font-size: $w01_tamfontesite;
          color: $w01_corfontesite;
          }
    ";
?>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?mens_div();?>
<center>
<form name="form1" method="post" action="">
<table width="100%" height="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
 <tr>
   <td class="tabfonte" valign="top" height="564">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" height="564">
       <tr>

       <td class="tabfonte" width="11%">Matr&iacute;cula:</td>
         <td class="tabfonte" width="30%" align="left" nowrap> &nbsp;
           <?=trim($j01_matric)?>
           -
           <script>
            var x = CalculaDV("<?=$j01_matric?>",11);
            document.write(x);
           </script>
         </td>
         <td width="19%" nowrap class="tabfonte"> Refer&ecirc;ncia
          Anterior:</td>
         <td class="tabfonte" width="40%" nowrap> &nbsp;
          <?=$j40_refant?>
         </td>
        </tr>
        <tr>
        <td class="tabfonte" colspan="4">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>

            <td class="tabfonte" width="12%">Setor:</td>
              <td class="tabfonte" width="24%" align="left" nowrap> &nbsp;
                <?=$j34_setor?>
                </td>

            <td class="tabfonte" width="16%">Quadra:</td>
              <td class="tabfonte" width="14%"> &nbsp;
                <?=$j34_quadra?>
                </td>

            <td class="tabfonte" width="14%">Lote:</td>
              <td class="tabfonte" width="20%" nowrap> &nbsp;
                <?=$j34_lote?>
                </td>
            </tr>
          </table></td>
        </tr>
        <tr align="left">
          <td class="tabfonte" height="19" colspan="4">
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>

              <td class="tabfonte" width="12%" height="19">Logradouro:</td>
                <td class="tabfonte" align="left"> &nbsp;
                  <?=$codpri?>
                  </td>
                <td class="tabfonte" nowrap> &nbsp;
                  <?=$nomepri?>
                  </td>
                <td class="tabfonte" nowrap> &nbsp;
                  <?=$j39_numero?>
                  /
                  <?=$j39_compl?>
                  </td>
              </tr>
            </table></td>
        </tr>
        <tr align="center" valign="top">
          <td class="tabfonte" colspan="4" height="443"> <hr> SOLICITA&Ccedil;&Atilde;O
            DE GUIA DE ITBI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?=@$id_itbi?>
            <input type="hidden" name="id_itbi" size="10" readonly value="<?=@$id_itbi?>">
             <table width="100%" cellspacing="0" cellpadding="0">
              <tr valign="top">
                <td class="tabfonte" width="100%" height="125" colspan="3">
                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
                    <tr valign="top">

                    <td class="tabfonte" colspan="2" height="23"><b>IDENTIFICA&Ccedil;&Atilde;O
                      DO COMPRADOR:</b></td>
                    </tr>
                    <tr>

                    <td class="tabfonte" width="28%" height="25" valign="top">Nome:
                      <br>
                        <input name="nomecomprador" type="text" onBlur="js_maiuscula(this)" value="<?=@$nomecomprador?>" size="40" maxlength="40">
                        </td>

                    <td class="tabfonte" width="21%" height="25">CNPJ
                      /CPF<br>
                        <input name="cgccpfcomprador" type="text" onBlur="js_maiuscula(this)" value="<?=@$cgccpfcomprador?>" size="14" maxlength="14">
                        </td>
                    </tr>
                    <tr>

                    <td class="tabfonte" height="25">Endere&ccedil;o:
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Número:&nbsp;Complemento:<br>
                        <input type="text" name="enderecocomprador" size="40" maxlength="40" value="<?=@$enderecocomprador?>" onBlur="js_maiuscula(this)">
                        <input type="text" name="numerocomprador" size="5" maxlength="10" value="<?=@$numerocomprador?>" >
                        <input type="text" name="complcomprador" size="12" maxlength="20" value="<?=@$complcomprador?>" onBlur="js_maiuscula(this)">
                        </td>

                    <td class="tabfonte" height="25">Caixa Postal
                      <br>
                        <input type="text" name="cxpostcomprador" size="20" maxlength="20" value="<?=@$cxpostcomprador?>" onBlur="js_maiuscula(this)">
                        </td>
                    </tr>
                    <tr>
                      <td> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                          <td width="35%" class="tabfonte" >Bairro
                      </td>
                          <td  width="35%" class="tabfonte" width="38%">Munic&iacute;pio:</td>

                          <td  width="30%" class="tabfonte" width="62%">&nbsp;&nbsp;UF:</td>
                          </tr>
                          <tr>
                        <td><input type="text" name="bairrocomprador" size="20" maxlength="20" value="<?=@$bairrocomprador?>" onBlur="js_maiuscula(this)">
                            </td>
                            <td>
                              <input type="text" name="municipiocomprador" size="20" maxlength="20" value="<?=@$municipiocomprador?>" onBlur="js_maiuscula(this)">
                              </td>
                            <td>&nbsp;&nbsp;
                              <select name="ufcomprador">
                                <option value="RS" <? echo @$ufcomprador=="RS"?"selected":"" ?>>
                                RS </option>
                                <option value="AC" <? echo @$ufcomprador=="AC"?"selected":"" ?>>
                                AC </option>
                                <option value="AL" <? echo @$ufcomprador=="AL"?"selected":"" ?>>
                                AL </option>
                                <option value="AM" <? echo @$ufcomprador=="AM"?"selected":"" ?>>
                                AM </option>
                                <option value="AP" <? echo @$ufcomprador=="AP"?"selected":"" ?>>
                                AP </option>
                                <option value="BA" <? echo @$ufcomprador=="BA"?"selected":"" ?>>
                                BA </option>
                                <option value="CE" <? echo @$ufcomprador=="CE"?"selected":"" ?>>
                                CE </option>
                                <option value="DF" <? echo @$ufcomprador=="DF"?"selected":"" ?>>
                                DF </option>
                                <option value="ES" <? echo @$ufcomprador=="ES"?"selected":"" ?>>
                                ES </option>
                                <option value="GO" <? echo @$ufcomprador=="GO"?"selected":"" ?>>
                                GO </option>
                                <option value="MA" <? echo @$ufcomprador=="MA"?"selected":"" ?>>
                                MA </option>
                                <option value="MG" <? echo @$ufcomprador=="MG"?"selected":"" ?>>
                                MG </option>
                                <option value="MS" <? echo @$ufcomprador=="MS"?"selected":"" ?>>
                                MS </option>
                                <option value="MT" <? echo @$ufcomprador=="MT"?"selected":"" ?>>
                                MT </option>
                                <option value="PA" <? echo @$ufcomprador=="PA"?"selected":"" ?>>
                                PA </option>
                                <option value="PB" <? echo @$ufcomprador=="PB"?"selected":"" ?>>
                                PB </option>
                                <option value="PE" <? echo @$ufcomprador=="PE"?"selected":"" ?>>
                                PE </option>
                                <option value="PI" <? echo @$ufcomprador=="PI"?"selected":"" ?>>
                                PI </option>
                                <option value="PR" <? echo @$ufcomprador=="PR"?"selected":"" ?>>
                                PR </option>
                                <option value="RJ" <? echo @$ufcomprador=="RJ"?"selected":"" ?>>
                                RJ </option>
                                <option value="RN" <? echo @$ufcomprador=="RN"?"selected":"" ?>>
                                RN </option>
                                <option value="RO" <? echo @$ufcomprador=="RO"?"selected":"" ?>>
                                RO </option>
                                <option value="RR" <? echo @$ufcomprador=="RR"?"selected":"" ?>>
                                RR </option>
                                <option value="SC" <? echo @$ufcomprador=="SC"?"selected":"" ?>>
                                SC </option>
                                <option value="SE" <? echo @$ufcomprador=="SE"?"selected":"" ?>>
                                SE </option>
                                <option value="SP" <? echo @$ufcomprador=="SP"?"selected":"" ?>>
                                SP </option>
                                <option value="TO" <? echo @$ufcomprador=="TO"?"selected":"" ?>>
                                TO </option>
                              </select>
                              </td>
                          </tr>
                        </table></td>

                    <td class="tabfonte" height="55">CEP:
                     <br>
                        <input type="text" name="cepcomprador" size="8" maxlength="8" value="<?=@$cepcomprador?>" onBlur="js_maiuscula(this)">
                        </td>
                    </tr>
                  </table>
                <br>
              </td>
              </tr>
              <tr>
                <td class="tabfonte" colspan="3" height="140">
                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
                    <tr>
                      <td class="tabfonte" colspan="4">DADOS
                        DO IM&Oacute;VEL:</td>
                    </tr>
                    <tr>
                      <td class="tabfonte">Tipo de Transmiss&atilde;o:</td>
                      <td class="tabfonte" colspan="3"><select name="tipotransacao">

<option  <? echo @$tipotransacao=="ABERT.CRED.FIXO REAL"?"selected":"" ?>             value='ABERT.CRED.FIXO REAL'>ABERTURA DE CRÉDITO FIXO COM GARANTIA REAL</option>
<option  <? echo @$tipotransacao=="AVALIAÇÃO FISCAL"?"selected":"" ?>                 value='AVALIAÇÃO FISCAL'>AVALIAÇÃO FISCAL</option>
<option  <? echo @$tipotransacao=="CANCEL.  DE USUFRUTO"?"selected":"" ?>             value='CANCEL.  DE USUFRUTO'>CANCELAMENTO DE USUFRUTO</option>
<option  <? echo @$tipotransacao=="CANCEL.USUF FAL USUF"?"selected":"" ?>             value='CANCEL.USUF FAL USUF'>CANCELAMENTO DE USUFRUTO P/FALECIMENTO DO USUFRUTUÁRIO</option>
<option  <? echo @$tipotransacao=="CARTA DE ADJUDICAÇÃO"?"selected":"" ?>             value='CARTA DE ADJUDICAÇÃO'>CARTA DE ADJUDICAÇÃO</option>
<option  <? echo @$tipotransacao=="CARTA DE ARREMATAÇÃO"?"selected":"" ?>             value='CARTA DE ARREMATAÇÃO' >CARTA DE ARREMATAÇÃO</option>
<option  <? echo @$tipotransacao=="CARTA DE ARR LEILÃO"?"selected":"" ?>              value='CARTA DE ARR LEILÃO'>CARTA DE ARREMATAÇÃO - LEILÃO</option>
<option  <? echo @$tipotransacao=="CESSÃO DE DIR HERED."?"selected":"" ?>             value='CESSÃO DE DIR HERED.' >CESSÃO DE DIREITOS HEREDITÁRIOS</option>
<option  <? echo @$tipotransacao=="CESSÃO DIR POSSESSÓR"?"selected":"" ?>             value='CESSÃO DIR POSSESSÓR'>CESSÃO DE DIREITOS POSSESSÓRIOS</option>
<option  <? echo @$tipotransacao=="CESSÃO E TRA DIR HER"?"selected":"" ?>             value='CESSÃO E TRA DIR HER'>CESSÃO E TRANSFERÊNCIA DE DIREITOS HEREDITÁRIOS</option>
<option  <? echo @$tipotransacao=="COMPRA E VENDA"?"selected":"" ?> selected        value='COMPRA E VENDA' >COMPRA E VENDA</option>
<option  <? echo @$tipotransacao=="COMPRA E VENDA-COMPL"?"selected":"" ?>              value='COMPRA E VENDA-COMPL'>COMPRA E VENDA - COMPLEMENTAR</option>
<option  <? echo @$tipotransacao=="COMPRA E VEN RES USU"?"selected":"" ?>             value='COMPRA E VEN RES USU' >COMPRA E VENDA COM RESERVA DE USUFRUTO</option>
<option  <? echo @$tipotransacao=="COMPRA E VEN NUA PRO"?"selected":"" ?>             value='COMPRA E VEN NUA PRO'>COMPRA E VENDA DA NUA PROPRIEDADE</option>
<option  <? echo @$tipotransacao=="COMPRA VENDA MEAÇÃO"?"selected":"" ?>              value='COMPRA VENDA MEAÇÃO'>COMPRA E VENDA DE MEAÇÃO</option>
<option  <? echo @$tipotransacao=="COMPRA VENDA DO USU"?"selected":"" ?>              value='COMPRA VENDA DO USU'>COMPRA E VENDA DO USUFRUTO</option>
<option  <? echo @$tipotransacao=="CONF  DÍV C/GAR HIP"?"selected":"" ?>              value='CONF  DÍV C/GAR HIP'>CONFISSÃO DE DÍVIDA C/GARANTIA HIPOTECÁRIA</option>
<option  <? echo @$tipotransacao=="CONTR DE PROM COMP V"?"selected":"" ?>             value='CONTR DE PROM COMP V'>CONTRATO DE PROMESSA DE COMPRA E VENDA</option>
<option  <? echo @$tipotransacao=="DAÇÃO EM PAGAMENTO"?"selected":"" ?>               value='DAÇÃO EM PAGAMENTO'>DAÇÃO EM PAGAMENTO</option>
<option  <? echo @$tipotransacao=="DAÇÃO PAG P/INT COT"?"selected":"" ?>              value='DAÇÃO PAG P/INT COT'>DAÇÃO EM PAGTO P/FINS DE INTEGRALIZAÇÃO DE COTA CAPITAL</option>
<option  <? echo @$tipotransacao=="DESAPROPIAÇÃO"?"selected":"" ?>                    value='DESAPROPIAÇÃO'>DESAPROPIAÇÃO</option>
<option  <? echo @$tipotransacao=="DIV EXT DE USUFRUTO"?"selected":"" ?>              value='DIV EXT DE USUFRUTO'>DIVISÃO E EXTINÇÃO DE USUFRUTO</option>
<option  <? echo @$tipotransacao=="HIPOTECA"?"selected":"" ?>                         value='HIPOTECA'>HIPOTECA</option>
<option  <? echo @$tipotransacao=="INCORPORAÇÃO"?"selected":"" ?>                     value='INCORPORAÇÃO'>INCORPORAÇÃO</option>
<option  <? echo @$tipotransacao=="INST DE GAR HIPOTEC"?"selected":"" ?>              value='INST DE GAR HIPOTEC'>INSTITUIÇÃO DE GARANTIA HIPOTECÁRIA</option>
<option  <? echo @$tipotransacao=="INSTITUIÇÃO DE USUFR"?"selected":"" ?>             value='INSTITUIÇÃO DE USUFR"'>INSTITUIÇÃO DE USUFRUTO</option>
<option  <? echo @$tipotransacao=="L E I L Ã O"?"selected":"" ?>                         value='L E I L Ã O' >L E I L Ã O</option>
<option  <? echo @$tipotransacao=="PERMUTA"?"selected":"" ?>                          value='PERMUTA'>PERMUTA</option>
<option  <? echo @$tipotransacao=="RENÚNCIA DE USUFRUTO"?"selected":"" ?>               value='RENÚNCIA DE USUFRUTO' >RENÚNCIA DE USUFRUTO</option>
<option  <? echo @$tipotransacao=="T O R N A"?"selected":"" ?>                        value='T O R N A' >T O R N A</option>
<option  <? echo @$tipotransacao=="T O R N A (div Cons)"?"selected":"" ?>             value='T O R N A (div Cons)'>T O R N A (divórcio Consensual)</option>
<option  <? echo @$tipotransacao=="TORNA-SEP JUD CONSEN"?"selected":"" ?>             value='TORNA-SEP JUD CONSEN'>TORNA - SEPARAÇÃO JUDICIAL CONSENSUAL</option>
<option  <? echo @$tipotransacao=="USUCAPIAO"?"selected":"" ?>                      value='USUCAPIAO' >USUCAPIAO</option>
<option  <? echo @$tipotransacao=="VENDA DE MEAÇÃO"?"selected":"" ?>                value='VENDA DE MEAÇÃO'>VENDA DE MEAÇÃO</option>
<option  <? echo @$tipotransacao=="CISÃO PARCIAL COM INCORPORAÇÃO"?"selected":"" ?> value='CISÃO PARCIAL COM INCORPORAÇÃO'>CISÃO PARCIAL COM INCORPORAÇÃO</option>
<option  <? echo @$tipotransacao=="EXTINÇÃO DE PESSOA JURÍDICA"?"selected":"" ?>   value='EXTINÇÃO DE PESSOA JURÍDICA'>EXTINÇÃO DE PESSOA JURÍDICA</option>

                                        </select>
                                     </td>
                                     </tr>
                                     <tr>
                                      <td class="tabfonte">Situa&ccedil;&atilde;o:</td>
                                      <td class="tabfonte" colspan="3">
<select name="situacao" id="situacao">
<option value="INTERNO" <?=(@$situacao=="INTERNO"?"selected":"")?>>INTERNO</option>
<option value="ESQUINA" <?=(@$situacao=="ESQUINA"?"selected":"")?>>ESQUINA</option>
<option value="ENCRAVADO" <?=(@$situacao=="ENCRAVADO"?"selected":"")?>>ENCRAVADO</option>
</select>
</td>
                                    </tr>
                                    <tr> 
                                      <td class="tabfonte" width="27%">&Aacute;rea 
                                        do Terreno: </td>
                                      <td class="tabfonte" width="15%"> <input name="areareal" type="text" id="areareal" value="<?=@$areareal?>"  size="20"> 
                                      </td>
                                      <td class="tabfonte" width="20%">Frente:</td>
                                      <td class="tabfonte" width="38%"> <input type="text" name="mfrente" size="20" value="<?=@$mfrente?>"> 
                                      </td>
                                    </tr>
                                    <tr> 
                                      <td class="tabfonte" nowrap>&Aacute;rea 
                                        Transmitida:</td>
                                      <td><input name="areaterreno" type="text" id="areaterreno" value="<?=@$areaterreno?>" size="20"> 
                                      </td>
                                      <td class="tabfonte" nowrap>Lado 
                                        Direito:</td>
                                      <td> <input type="text" name="mladodireito" size="20" value="<?=@$mladodireito?>"> 
                                      </td>
                                    </tr>
                                    <tr> 
                                      <td class="tabfonte" nowrap>Valor 
                                        da Transmiss&atilde;o:</td>
                                      <td><input type="text" name="valortransacao" size="20" value="<?=@$valortransacao?>"> 
                                      </td>
                                      <td class="tabfonte">Fundos:</td>
                                      <td class="tabfonte"> <input type="text" name="mfundos" size="20" value="<?=@$mfundos?>"> 
                                      </td>
                                    </tr>
                                    <tr> 
                                      <td class="tabfonte" > &Aacute;rea 
                                        Constru&iacute;da :</td>
                                      <td class="tabfonte" > 
                                        <input name="areaedificada" type="text" id="areaedificada" value="<?=@$areaedificada?>" size="20" >
                                        </td>
                                      <td class="tabfonte" nowrap>Lado 
                                        Esquerdo:</td>
                                      <td class="tabfonte"> <input type="text" name="mladoesquerdo" size="20" value="<?=@$mladoesquerdo?>"> 
                                      </td>
                                    </tr>
                                    <!--select name="caracteristicas"-->
                                    <?

                                  echo "<tr>
                                                                         <td class=\"tabfonte\">
                                                                                    Descrição
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                            Tipo
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    Área Construída Transm.
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                            Ano Construção
                                                                                   </td>
                                                                                 </tr>\n";

                                                          if(@$id_itbi == ""){
                                                            $numrows = 0;
                                                          }else{
                                                            $sql = "select c.codcaritbi,c.descricao,i.area,i.descrcar,i.anoconstr 
                                                                                   from db_caritbi c,db_caritbilan i
                                                                                                   where c.codcaritbi = i.codcaritbi
                                                                                                   and i.id_itbi = $id_itbi";
                                                            $result = db_query($sql);
                                                                $numrows = pg_numrows($result);
                                                            for($i=0;$i<$numrows;$i++) {
                                  //echo "<tr><td class=\"tabfonte\">".pg_result($result,$i,"descricao").":</td><td class=\"tabfonte\"> <input type=\"text\" onkeyup=\"js_soma()\" name=\"CARAC".pg_result($result,$i,"descricao").pg_result($result,$i,"codcaritbi")."\" value=\"".(@pg_result($result,$i,"area")==""?"0":@pg_result($result,$i,"area"))."\"></td><td class=\"tabfonte\">&nbsp;</td><td>&nbsp;</td></tr>\n";
                                  echo "<tr>
                                                                         <td class=\"tabfonte\">
                                                                                    <input type=\"text\" name=\"cardescr$i\" value=\"".(@pg_result($result,$i,"descrcar")==""?"":@pg_result($result,$i,"descrcar"))."\">
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    <select name=\"tipo$i\">";
                                                                  $result1 = db_query("select * from db_caritbi");
                                                                  for($x=0;$x<pg_numrows($result1);$x++){
                                                                    echo " <option value=\"".pg_result($result1,$x,'codcaritbi')."\" ".(pg_result($result1,$x,'codcaritbi')==pg_result($result,$i,'codcaritbi')?"selected":"").">".pg_result($result1,$x,'descricao')."</option>";
                                                              }
                                                                    echo "          </select>
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    <input type=\"text\" onkeyup=\"js_soma()\" name=\"area_$i\" value=\"".(@pg_result($result,$i,"area")==""?"0":@pg_result($result,$i,"area"))."\">
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    <input type=\"text\" name=\"anoconstr$i\" size=\"5\" maxlength=\"4\" value=\"".(@pg_result($result,$i,"anoconstr")==""?"0":@pg_result($result,$i,"anoconstr"))."\">
                                                                                 </td>
                                                                                 </tr>\n";
                                                             }
                                                          }
                                                          for($x=$numrows;$x<10;$x++){
                                  echo "<tr>
                                                                         <td class=\"tabfonte\">
                                                                                    <input type=\"text\" name=\"cardescr$x\" value=\"\" onBlur=\"js_maiuscula(this)\">
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    <select name=\"tipo$x\">";
                                                                  $result = db_query("select * from db_caritbi");
                                                                  for($y=0;$y<pg_numrows($result);$y++){
                                                                    echo " <option value=\"".pg_result($result,$y,'codcaritbi')."\">".pg_result($result,$y,'descricao')."</option>";
                                                                    }
                                                                    echo "          </select>
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    <input type=\"text\" onkeyup=\"js_soma()\" name=\"area_$x\" value=\"0\">
                                                                                 </td>
                                                                                 <td class=\"tabfonte\">
                                                                                    <input type=\"text\" size=\"5\" name=\"anoconstr$x\" maxlength=\"4\" value=\"0\">
                                                                                 </td>
                                                                                 </tr>\n";
                                                          }        
                                                        ?>
                                    <!--option selected>Casa</option>
                            <option>Apartamento</option>
                            <option>Garagem</option>
                            <option>Galp&atilde;o</option>
                            <option>Terreno Baldio</option-->
                                    <!--/select-->
                                    <tr> 
                                      <td height="27" nowrap class="tabfonte">Total 
                                        de &aacute;rea constru&iacute;da transm.:</td>
                                      <td> <input name="areaconstruida" type="text" id="areaconstruida" value="<?=@$areacontriuda?>" size="20" readonly> 
                                      </td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                  </table></td>
                              </tr>
                            </table>
                            <table width="100%" border="0">
                              <tr> 
                                
                              <td class="tabfonte" width="33%" height="82" valign="top"><strong>E-mail 
                                para Contato:</strong><br>
                                  <input type="text" name="email" size="20" maxlength="40" onblur="js_minuscula(this)" value="<?=@$email?>">
                                  </td>
                                
                              <td class="tabfonte" width="18%" valign="top"><strong>Observa&ccedil;&otilde;es:</strong></td>
                                <td class="tabfonte" width="49%">  
                                  <textarea name="obs" cols="30" rows="4"><?=@$obs?></textarea>
                                  </td>
                              </tr>
                            </table>
                            <hr> </td>
                        </tr>
                        <tr align="center" valign="top"> 
                          <td class="tabfonte" height="23" colspan="4">  
                            <input class="botao" type="button" name="confirma" value="Confirma Solicita&ccedil;&atilde;o" onclick="jsss_verificadados()">
                            <input type="hidden" name="cod_matricula" value="<?=$cod_matricula?>">
                            </td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
</form>
</center>