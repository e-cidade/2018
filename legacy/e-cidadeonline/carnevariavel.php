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
include_once("libs/db_stdlib.php");
include_once("libs/db_sql.php");
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitainscricao.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
  echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$vt = $HTTP_POST_VARS;
$tam = sizeof($vt);


if(isset($ano)){
  	
  //$meses = "";
  //for ($x = 1;$x <= 12;$x++) {
  //  $variavel = "CHECK$x";
  //  if(isset($$variavel)) {
  //    $meses .= ($x) . ", ";
  //  }
  //}
 
 // echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>";
  
  $condicao = "";
  $primeiro = true;
  for($i = 0;$i < $tam;$i++) {
    if(db_indexOf(key($vt) ,"CHECK") > 0){
  	//  echo "check: " . 	key($vt) . "-" . $vt[key($vt)] . "<br>";
  	  $xx = key($vt);
  	  $ano1 = substr($xx, 5, 4); 
  	  $mes1 = substr($xx, 9, 2);
  	  $nn = $vt[key($vt)];
  	  
  	//  echo"<br>ano = $ano1 mes= $mes1 numpre = $nn";
  	  if ($primeiro==true){
  	 	 $condicao .= " (q05_ano = $ano1 and q05_mes = $mes1) ";
  	 	 $primeiro = false;
  	  }else{
  	  	$condicao .= "or (q05_ano = $ano1 and q05_mes = $mes1) ";
      }
  	  
    }
    
    next($vt);
  }
  

  //$meses = substr($meses,0,strlen($meses)-2);

  $sqlvariavel = "select arrecad.k00_numpre, arrecad.k00_numpar,q05_ano
                  from issvar
                    inner join arreinscr on arreinscr.k00_numpre = issvar.q05_numpre
                    inner join arrecad on arrecad.k00_numpre = issvar.q05_numpre
                    and arrecad.k00_numpar = issvar.q05_numpar
                  where ($condicao) 
                    and arreinscr.k00_inscr = $inscricao
                    and ( q05_vlrinf = 0 or q05_vlrinf is null )";
//die($sqlvariavel);
// cfe contato com Evandro 09/03/06
// não precisa q05_valor != 0
// and q05_valor != 0 and q05_vlrinf = 0";

  $result = @db_query($sqlvariavel);
  $H_ANOUSU = $ano;
//$H_ANOUSU = '2005';
//$H_DATAUSU = '1109559600';
//$ver_matric = '';
//$ver_inscr = '18900';
//$ver_numcgm = '261257';
//$tipo_debito = '3';
//$k03_tipo = '3';
//$k03_parcelamento = '';
//$k03_permparc = 'f';
//$numpre_unica = '';
//$CHECK1 = '245119P2';
//$geracarne = 'banco';

  if(@pg_num_rows($result) > 0){
    $tipo_debito = 3;
    db_fieldsmemory( $result, 0 );
    include("cai3_gerfinanc033.php");
    exit;
  }else{ 
   ?>
   <script>
    alert("Sem lançamentos!");
    window.close();
   </script>
   <?
  }

}

if (!isset($inscricao) or empty($inscricao)){
   msgbox("Inscrição Inválida.");
   db_logs("","$inscricao",0,"Inscricao Invalida. Numero: $inscricao ");
   redireciona("digitainscricao.php");
}
db_logs("","$inscricao",0,"Inscricao Pesquisada. Numero: $inscricao ");

$result = @db_query("select * from empresa where q02_inscr = $inscricao");

if(@pg_num_rows($result) == 0 ){
   msgbox("Verifique Cadastro com a Prefeitura. (1)");
   db_logs("","$inscricao",0,"Inscricao nao Cadastrada. Numero: $inscricao ");
   redireciona("digitainscricao.php?".base64_encode("inscricao=".$inscricao));
}
db_fieldsmemory($result,0);
if (empty($escritorio)){
   $escritorio = 'O PRÓPRIO';
}

if(!isset($DB_LOGADO) && $m_publico !='t'){
  $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricao)";
  $resultteste = db_query($sql);
  if(pg_num_rows($resultteste)==0){
    db_redireciona("centro_pref.php?".base64_encode('erroscripts=Acesso a rotina inválido.'));
    exit;
  }
  $resultteste = pg_result($result,0,0);
  if($resultteste=="0"){
    db_redireciona("centro_pref.php?".base64_encode('erroscripts=Acesso a rotina inválido.'));
    exit;
  }
} 

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdebitospendentes.php");

function js_emiterecibo() {
  var retorno = false;
  F = document.form1;
  if( js_verifica() == true ){
     jan = window.open('','reciboweb','width=790,height=530,scrollbars=1,location=0');
     jan.moveTo(0,0);
     retorno = true;
  }else {
     alert("Você deverá selecionar algum mês" );
  }
  return retorno;
}

</script>
<style type="text/css">
<?//db_estilosite();
echo"
.tabfonte {
          font-family: $w01_fontesite;
          font-size: $w01_tamfontesite;
          color: $w01_corfontesite;
          }
    ";
?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?
mens_div();
?>
<center>
<table width="100%" height="250" border="0" cellpadding="0" cellspacing="0" class="texto">
 <tr>
  <td align="left" valign="top">
   <form name="form1" method="post" action="" target="reciboweb">
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
    <tr>
     <td class="tabfonte" height="25" width="8%"> Inscri&ccedil;&atilde;o:</td>
     <td  class="tabfonte" height="25" width="55%">&nbsp; <?=$inscricao?> -
     <script>
      var x = CalculaDV("<?=$inscricao?>",11);
      document.write(x);
     </script>
    </td>
    <td class="tabfonte" height="25" colspan="2">CNPJ/CPF:&nbsp;&nbsp;&nbsp;&nbsp;<?=$z01_cgccpf?></td>
   </tr>
   <tr>
    <td class="tabfonte" width="8%" height="24"> Nome:</td>
    <td class="tabfonte" colspan="2" height="24"> &nbsp;<?=$z01_nome?></td>
    <td class="tabfonte" width="9%" height="24">&nbsp;</td>
   </tr>
   <tr valign="top">
    <td class="tabfonte" colspan="4" height="76">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
      <tr>
       <td class="tabfonte" width="44%" height="38">
        Endereco do Contribuinte:<br>
        &nbsp;
        <?=$z01_ender?>
        <br>
        &nbsp;
        <?=$z01_bairro?>
        <br>
        </td>
        <td class="tabfonte" width="15%" valign="top" height="38" align="left"> N&uacute;mero:</strong><br>
         &nbsp;<br>
         &nbsp;
         <?=$z01_cep?>
        </td>
        <td class="tabfonte" width="3%" valign="bottom" height="38" align="left">&nbsp;</td>
        <td class="tabfonte" width="38%" height="38" valign="top"> Complemento:<br>
         &nbsp;<br>
         &nbsp;
         <?=$z01_uf?>
        </td>
       </tr>
      </table>
      <br>
      <table width="95%" border="0" cellspacing="0" cellpadding="0" class="texto">
       <tr>
         <td class="tabfonte" width="44%">  Endere&ccedil;o
           da Inscri&ccedil;&atilde;o:<br>
           &nbsp;
           <?=$z01_nome?>
           <br>
           &nbsp;
           <?=$q03_descr?>
           </td>
         <td class="tabfonte" width="15%" valign="top" align="left"> N&uacute;mero:<br>
             &nbsp;
             <?=$z01_numero?>
             <br>
             &nbsp;
             <?=$z01_cep?>
         </td>
         <td class="tabfonte" width="3%" valign="bottom" align="left">&nbsp;</td>
         <td class="tabfonte" width="38%" valign="top"> Complemento:<br>
           &nbsp;
           <?=$z01_compl?>
           <br>
           &nbsp;RS </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
    <tr>
     <td class="tabfonte" colspan="4"> <hr> </td>
    </tr>
    <tr>
    <tr>
     <td align="center"><tr></tr></td>
    <tr>
     <td class="tabfonte" colspan="4"> <hr> </td>
    </tr>
     <td colspan=2 nowrap>
      <script>
       function js_criames(obj){
         for(i=1;i<document.form1.mes.length;i){
           document.form1.mes.options[i] = null;
         }
         var dth = new Date(<?=date("Y")?>,<?=date("m")?>,'1');
          if(document.form1.ano.options[0].value != obj.value ){
           for(j=1;j<13;j++){
             var dt = new Date('2004',j,'1');
             document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
           }
          }else{
           for(j=1;j<dth.getMonth()+1;j++){
             var dt = new Date('2004',j,'1');
             document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
           }
         }
       }
        function js_marca() {
          var ID = document.getElementById('marca');
          //var BT = document.getElementById('btmarca');
          if(!ID)
            return false;
          var F = document.form1;
          if(ID.innerHTML == 'Desmarcar') {
              var dis = false;
              ID.innerHTML = 'Marcar';
          } else {
              var dis = true;
              ID.innerHTML = 'Desmarcar';
          }
          for(i = 0;i < F.elements.length;i++) {
             if(F.elements[i].type == "checkbox"){
                F.elements[i].checked = dis;
             }
          }
          js_verifica();
        }
          function js_verifica(){
            var marcas = false;
            var F = document.form1;
            for(i = 0;i < F.elements.length;i++) {
              if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
                marcas = true;
              }
            }
            return marcas;
          }

      </script>
      </select>
      <?
      $sano     = date("Y");
      $mesatual = date("m");
      //$sqlvariavel = "select arrecad.k00_numpar,issvar.q05_numpre from issvar inner join arreinscr on arreinscr.k00_numpre = issvar.q05_numpre inner join arrecad on arrecad.k00_numpre = issvar.q05_numpre and arrecad.k00_numpar = issvar.q05_numpar where issvar.q05_ano = $sano and issvar.q05_mes <= " . ($mesatual + 1) . " and arreinscr.k00_inscr = $inscricao and q05_vlrinf = 0";
      $sqlvariavel = "select arrecad.k00_numpar,issvar.q05_numpre, issvar.q05_ano from issvar inner join arreinscr on arreinscr.k00_numpre = issvar.q05_numpre inner join arrecad on arrecad.k00_numpre = issvar.q05_numpre and arrecad.k00_numpar = issvar.q05_numpar where k00_dtvenc >= current_date and arreinscr.k00_inscr = $inscricao and q05_valor = 0 and q05_vlrinf = 0";
      $result = db_query($sqlvariavel);
      //die($sqlvariavel);
      if(pg_num_rows($result)!=0){
      	
       ?>
       <tr height="20">
        <th colspan="4" class="tabfonte" align="center" style="font-size:12px" nowrap>
          <a id="marca" href="#" style="color:black" onclick="js_marca();return false">Desmarcar</a>
        </th>
       </tr>
       <?
       $cont = 0;
       //$numpre = pg_result($result,$ci,1);
       for($ci = 0; $ci < pg_num_rows($result); $ci++){
       	db_fieldsmemory($result,$ci);
        $numpre = pg_result($result,$ci,1);
        $mesmostrar = pg_result($result,$ci,0);
        if($cont==0){
          echo "<tr>";
        }
        $cont++;
        $k00_numpre = pg_result($result,$ci,1);
        $k00_numpar = str_pad(pg_result($result,$ci,0),2,"0", STR_PAD_LEFT);
        $ano = pg_result($result,$ci,0);
        $str_valor= $k00_numpre.'P'.$k00_numpar;
       
        //echo "<td><input type=\"checkbox\" style=\"border:0\" checked name=\"CHECK$ci\" value=\"$str_valor\">".db_mes($mesmostrar)."</td>";
        echo "<td><input type=\"checkbox\" style=\"border:0\" checked name=\"CHECK$q05_ano$k00_numpar \" value=\"$str_valor\">".db_mes($mesmostrar)."/".$q05_ano."</td>";
       // echo" <br> CHECK$q05_ano$k00_numpar - $k00_numpar - $str_valor	";
        if($cont==4){
          echo "</tr>";
          $cont=0;
        }
       }
      }
      ?>
     <input class="carnevariavel" type="hidden" name="carnevariavel" value="1">
     <input class="inscricao" type="hidden" name="ver_inscr" value="<?=$inscricao?>">
     <input class="ano" type="hidden" name="ano" value="<?=date('Y')?>">
     <input class="geracarne" type="hidden" name="geracarne" value="banco">
     <tr>
      <td class="tabfonte" colspan="4"><hr></td>
     </tr>
     <tr>
      <td align="center">
       <input type="button" value="Voltar" onclick="history.go(-2)">
       <input class="botao" type="submit" name="emite" value="Emite carnê" <?if(pg_num_rows($result)==0){echo "disabled";}?> onClick="return js_emiterecibo()">
      </td>
     </tr>
      </td>
     </tr>
    </tr>
   </table>
  </form>
</body>
<!-- InstanceEnd --></html>