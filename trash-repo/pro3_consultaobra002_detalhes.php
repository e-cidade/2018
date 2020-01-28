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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_obrasconstr_classe.php");
include("classes/db_obrasender_classe.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_obrasalvara_classe.php");
include("classes/db_obras_classe.php");
include("classes/db_obrastec_classe.php");
include("classes/db_obrastecnicos_classe.php");
$clobrasconstr= new cl_obrasconstr;
$clobrasender= new cl_obrasender;
$clobrastec= new cl_obrastec;
$clobrastecnicos= new cl_obrastecnicos;
$clobras= new cl_obras;
$clobrashabite= new cl_obrashabite;
$clobrasalvara= new cl_obrasalvara;
?>
<script>

function js_emite(){
  jan = window.open('pro2_execobra002.php?codigo=<?=@$parametro?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?  
if ($solicitacao == "construcoes") {
  $result_obrasconstr=$clobrasconstr->sql_record($clobrasconstr->sql_query(null,"*","","ob08_codobra=$parametro"));
  $tituloJanela = "Construções";
?>
<table width="95%" border="0" align="center"  cellpadding="0" cellspacing="2">
  <tr align="center"> 
    <td colspan="4"><b> <u><?=$tituloJanela?></b></u></td>
  </tr>
  <tr align="center"> 
    <td colspan="4">&nbsp; </td>
  </tr>
</table>
<table width="100%" height="100%"  border="0" align="left"  cellpadding="0" cellspacing="2">
<?
  if(pg_numrows($result_obrasconstr)!= 0) {
    for ($x=0;$x < pg_numrows($result_obrasconstr);$x++ ){
      db_fieldsmemory($result_obrasconstr,$x);
      $result_obrasender=$clobrasender->sql_record($clobrasender->sql_query(null,"*","","ob07_codconstr=$ob08_codconstr"));
      if($clobrasender->numrows>0){
        db_fieldsmemory($result_obrasender,0);
      }
      $passa = false;
      $result_obrashabite=$clobrashabite->sql_record($clobrashabite->sql_query_file(null,"*","","ob09_codconstr=$ob08_codconstr"));
      if($clobrashabite->numrows>0){
        db_fieldsmemory($result_obrashabite,0);
	$passa = true;
      }
?>
  
  <tr align="center"> 
    <td align="right"  nowrap bgcolor="#CCCCCC">Constru&ccedil;&atilde;o:</td>
    <td align="left" nowrap bgcolor="#FFFFFF"><?=@$ob08_codconstr?>&nbsp;</td>
    <td align="right" nowrap bgcolor="#CCCCCC">&Aacute;rea:</td>
    <td align="left" nowrap bgcolor="#FFFFFF"><?=@db_formatar($ob08_area,"p")?>&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC" >Ocupa&ccedil;&atilde;o:</td>
    <td align="left" nowrap bgcolor="#FFFFFF" >
      <?=@$ob08_ocupacao?>
      -
      <? $sql="select j31_descr as ocupacao  from caracter where j31_codigo=$ob08_ocupacao";
      $result2= pg_query($sql);
      db_fieldsmemory($result2,0);
      ?><?=@$ocupacao?>
      &nbsp;
    </td>
    <td  align="right" nowrap bgcolor="#CCCCCC">Tipo de Contru&ccedil;&atilde;o:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF">
      <?=@$ob08_tipoconstr?>
      -
      <? $sql="select j31_descr as tipoconstr  from caracter where j31_codigo=$ob08_tipoconstr";
      $result1= pg_query($sql);
      db_fieldsmemory($result1,0);
      ?><?=@$tipoconstr?>
      &nbsp;
    </td>
  </tr> 
  
  <tr align="center"> 
    <td align="right" nowrap bgcolor="#CCCCCC">Tipo de Lan&ccedil;amento:</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> 
      <?=@$ob08_tipolanc?>
      -
      <? $sql="select j31_descr as tipolanc from caracter where j31_codigo=$ob08_tipolanc";
      $result3= pg_query($sql);
      db_fieldsmemory($result3,0);
      ?><?=@$tipolanc?>
      &nbsp; 
    </td>
    <td align="right" nowrap bgcolor="#CCCCCC">&Aacute;rea atual:</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <?=@db_formatar($ob07_areaatual,"p")?>&nbsp;</td>
  </tr>
  
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC">Cod. Rua/Avenida:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob07_lograd?>&nbsp;</td>
    <td align="left" colspan=2  nowrap bgcolor="#FFFFFF"> <?=@$j14_nome?></td>
  </tr>
  
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC">N&uacute;mero:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob07_numero?>&nbsp;</td>
    <td  align="right" nowrap bgcolor="#CCCCCC">Complemento:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob07_compl?>&nbsp;</td>
  </tr>
  
  <tr align="center"> 
    <td align="right" nowrap bgcolor="#CCCCCC">Bairro:</td>
    <td align="left" nowrap bgcolor="#FFFFFF"><?=@$ob07_bairro?>&nbsp;</td>
    <td align="left" colspan=2     nowrap bgcolor="#FFFFFF"><?=@$j13_descr?></td>
  </tr>
 
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC">Unidade:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob07_unidades?>&nbsp;</td>
    <td  align="right" nowrap bgcolor="#CCCCCC">Pavimento:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob07_pavimentos?>&nbsp;</td>
  </tr>
  
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC">Data inicio:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@db_formatar($ob07_inicio,"d")?>&nbsp;</td>
    <td  align="right" nowrap bgcolor="#CCCCCC">Data final:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"> <?=@db_formatar($ob07_fim,"d")?>&nbsp; </td>
  </tr>
<? 
        if($passa==true){ 
?>
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC"><b>Habite-se:</b></td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob09_habite?>&nbsp;</td>
    <td  align="right" nowrap bgcolor="#CCCCCC"><b>Data do habite-se:</b></td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@db_formatar($ob09_data,"d")?> &nbsp;</td>
  </tr>
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC">&Aacute;rea:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF"><?=@$ob09_area?>&nbsp;</td>
    <td  align="right" nowrap bgcolor="#CCCCCC">Tipo de habite-se:</td>
    <td  align="left" nowrap bgcolor="#FFFFFF">
    <?
    if ($ob09_parcial==true){
      $tipo="Parcial";
      }else $tipo="Total";
    ?>
    <?=@$tipo?>&nbsp; 
    </td>
  </tr>
  <tr align="center"> 
    <td  align="right" nowrap bgcolor="#CCCCCC">Obs:</td>
    <td colspan=3 align="left" nowrap bgcolor="#FFFFFF"><?=@$ob09_obs?>&nbsp;</td>
  </tr>
<?     
        }else if($passa==false){
?>
  <tr align="center"> 
    <td colspan="4" align="center" nowrap bgcolor="#CCCCCC"><b>Constru&ccedil;&atilde;o n&atilde;o possui  habite-se. </b> &nbsp;</td>
  </tr>
</table>
<?	  
	}
      }
    }
  }

if ($solicitacao=="alvara"){
  
  


  $result_obrasalvara=$clobrasalvara->sql_record($clobrasalvara->sql_query(null,"*","","ob04_codobra=$parametro"));
  if($clobrasalvara->numrows == 0){
?>
<br><br>   
<table width="95%" border="0" align="center"  cellpadding="0" cellspacing="2">
  <tr align="center"> 
    <td colspan="4" align="center" nowrap bgcolor="#CCCCCC"><b>Esta obra  n&atilde;o possui  alvar&aacute;. </b> &nbsp;</td>
  </tr>
</table>
<?
  }else{
  db_fieldsmemory($result_obrasalvara,0,true);
  //db_criatabela($result_obrasalvara);
  ?>
<table width="95%" border="0" align="center"  cellpadding="0" cellspacing="2">
  <tr align="center"> 
    <td colspan="4"><b> <u>Alvar&aacute;</b></u></td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr align="center"> 
    <td width="20%"  align="right" nowrap bgcolor="#CCCCCC">Cod. Alvar&aacute;:</td>
    <td  width="30%" align="left" nowrap bgcolor="#FFFFFF"><?=@$ob04_alvara?>&nbsp;</td>
    <td width="20%"  align="right" nowrap bgcolor="#CCCCCC">Data:</td>
    <td  width="30%" align="left" nowrap bgcolor="#FFFFFF"><?=@$ob04_data?>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" colspan=4 >
      <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
    </td>
</table>

<?
   }
}
if ($solicitacao=="tecnico"){
$rsTecnicos = $clobrastecnicos->sql_record($clobrastecnicos->sql_query(null,"z01_nome,ob15_crea,ob15_numcgm","","ob20_codobra = $parametro")); 

	if($clobrastecnicos->numrows==0){

?>
<br><br>   
<table width="95%" border="0" align="center"  cellpadding="0" cellspacing="2">
  <tr align="center"> 
    <td colspan="4" align="center" nowrap bgcolor="#CCCCCC"><b>Esta obra  n&atilde;o possui  t&eacute;cnico. </b> &nbsp;</td>
  </tr>
</table>
<?
  }else{
  db_fieldsmemory($rsTecnicos,0,true);
  //db_fieldsmemory($result_obrastec,0,true);
  //db_criatabela($result_obrastec);
  ?>
<table width="95%" border="0" align="center"  cellpadding="0" cellspacing="2">
  <tr align="center"> 
    <td colspan="4"><b> <u>T&eacute;cnico</b></u></td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr>
  <td colspan=4>        </td>
  </tr>
  <tr align="center"> 
    <td width="20%"  align="right" nowrap bgcolor="#CCCCCC"><b><?
				db_ancora('Numcgm:','js_mostracgm();',4)
				?></b></td>
    <td  width="30%" align="left" nowrap bgcolor="#FFFFFF"><?=@$ob15_numcgm?>&nbsp;</td>
    <td width="20%"  align="right" nowrap bgcolor="#CCCCCC">Nome:</td>
    <td  width="30%" align="left" nowrap bgcolor="#FFFFFF"><?=@$z01_nome?>&nbsp;</td>
  </tr>
  <tr>
    <td width="20%"  align="right" nowrap bgcolor="#CCCCCC">Crea:</td>
    <td  width="30%" align="left" nowrap bgcolor="#FFFFFF"><?=@$ob15_crea?>&nbsp;</td>
    <td width="20%"  align="right" nowrap bgcolor="#CCCCCC"></td>
    <td  width="30%" align="left" nowrap bgcolor="#CCCCCC">&nbsp;</td>
  </tr>

</table>
<?}}?>
</body>
<script>
function js_mostracgm(){
    func_nome.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=<?=@$ob15_numcgm?>';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
}

</script>
<?

$func_nome = new janela('func_nome','');
$func_nome ->posX=0;
$func_nome ->posY=0;
$func_nome ->largura=780;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();
?>




</html>