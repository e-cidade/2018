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

  //
  include("fpdf151/pdf.php");
  // variaveis de cabeçalho
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  $sql=base64_decode($sql); 
  
  $pega = @pg_exec("select * from db_gerador where codger=$codigo");
  $tsql= @pg_result($pega,0,"sqlger");
  if(@$libera==true&&$tsql==$sql){
    $busca= pg_exec("select * from db_gerador where codger=$codigo");
    $limite= pg_result($busca,0,5);
    $tvisualizacao= pg_result($busca,0,6);
    $intercalar1= pg_result($busca,0,7);
    $intercalar2= pg_result($busca,0,8);
    $pcabecaltura= pg_result($busca,0,9);
    $pcorpaltura= pg_result($busca,0,10);
  }else{
    $sele="selected";
    $sele1="checked";  
    $limite=0;
  }
  
  $resultsql = pg_exec(str_replace('\\','',$sql." limit 1" ));
  
  $rotulocampo = new rotulocampo;
  $clrotulolov = new rotulolov;
  $fm_numfields = pg_numfields($resultsql);
?>                                           
<script>
function gera(){
 document.form1.action = "con2_gerelatorio004.php";
 document.form1.method="post";
 document.form1.target="_blank";
 document.form1.submit();
}
function armazena(){
 document.form1.action = "con2_gerelatorio005.php";
 document.form1.method="post";
 document.form1.target="";
 document.form1.submit();
}

function js_cabecaltura(){
 valor=document.form1.pcabecaltura.value;
 <?  
   for ($i = 0;$i < $fm_numfields;$i++){
     echo "document.form1.cabecaltura_".$i.".value=valor;";
   }  
?>
}
function js_corpaltura(){
 valor=document.form1.pcorpaltura.value;
 <?  
   for ($i = 0;$i < $fm_numfields;$i++){
     echo "document.form1.corpaltura_".$i.".value=valor;";
   }  
?>
}
function nova(){
location.href="con2_gerelatorio001.php";
}
</script>
<html>
<head>
<style type="text/css">
<!--
td {
           font-family: Arial, Helvetica, sans-serif;
	   font-size: 12px;
	   font-weight: bold;
}
input {
           font-family: Arial, Helvetica, sans-serif;
	   font-size: 12px;
	   height: 17px;
	   border: 1px solid #999999;
}

.pref {
text-align: center;
color: blue;
}
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc ;       
border-color: darkblue;
}
.corpo {
text-align: center;
color: #6699cc;
border: 2px outset #999999;
}
-->
</style>
<title>DBSeller Inform&aacute;tica Ltda - Relatórios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" bgcolor="#cccccc">
<form name="form1" method="post"><br>
<table cellspacing="0" cellpadding="0" border="1"  width="100%">
  <input type="hidden" name="codigo" value="<?=@$codigo?>">
  <input type="hidden" name="nome" value="<?=@$nome?>">
  <input type="hidden" name="titulo" value="<?=@$titulo?>">
  <input type="hidden" name="finalidade" value="<?=@$finalidade?>">
  <input type="hidden" name="sql" value="<?=@$sql?>">
  <input type="hidden" name="fm_numfields" value="<?=@$fm_numfields?>">
  <input type="hidden" name="libera" value="<?=@$libera?>">
  <tr >
    <td width"100%">
      <table width="100%" cellspacing="0" cellpadding="0" border="1">
        <tr>
	  <td valign="top" width="100%" >
	    <br>
           <?
             if(@$libera==true){    
	       echo "<input type=\"button\" size=\"10\" name=\"armazenar\" value=\"Confirmar Alteração\" onclick=\"armazena()\" >";
             }else{    
	       echo "<input type=\"button\" name=\"gerar\" value=\"Gerar relatório\" onclick=\"gera()\">";
	       echo "<input type=\"button\" size=\"10\" name=\"armazenar\" value=\"Gerar e armazenar relatório\" onclick=\"armazena()\" >";
             }
            ?>
	     <input type="button" size="10" name="novo" value="Novo relatório" onclick="nova()">
	  </td>
        </tr> 		    
        <tr><td>&nbsp;</td></tr> 		    
        <tr  >
	  <td align="right" width="100%" >  
            <table width="100%"cellspacing="0" cellpadding="0" border="1" class='pref' >
	      <tr width="100%"> 
	        <td colspan="1" width="20%" align="center">Página
		   <select  name="visualizacao" value="<?=@$visualizacao?>">
                     <option <?echo(@$tvisualizacao=="P"? "selected":"selected")?> value="P">Retrato</option>
                     <option <?echo(@$tvisualizacao=="L"? "selected":"")?> value="L">Paisagem</option>
                </td>
	        <td width="28%" nowrap align="center">
		   Altura padrão cabeçalhos 
		  <select name="pcabecaltura" onchange="js_cabecaltura()" > 
		    <?
		     for($t=0; $t<= 20; $t++){
       		       if(isset($pcabecaltura)){	
                         echo "<option ".($t==@$pcabecaltura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==4?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>

	        <td  nowrap  width="25%" align="center"> 
		  Altura padrão corpo
		  <select name="pcorpaltura" onchange="js_corpaltura()" > 
		    <?
		     for($t=0; $t<= 20; $t++){
       		       if(isset($pcorpaltura)){	
                         echo "<option ".($t==@$pcorpaltura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==4?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
		<td width="35%" nowrap align="center">
		  Limite de linhas 
		  <input type="text" maxlength="6" size="4" name="limite" value="<?=@$limite?>">
		</td>
             </tr>
	     <tr>
                <td  colspan="2" nowrap width="15%">
		  Intercalar cores de fundo
	          <select name="intercalar1"> 
		    <option <?echo(@$intercalar1=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$intercalar1=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$intercalar1=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$intercalar1=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$intercalar1=="0#0#0"? "selected":"")?> value="0#0#0">Preto</option>
		    <option <?echo(@$intercalar1=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$intercalar1=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		  </select>  
                     com
		  <select name="intercalar2"> 
		    <option <?echo(@$intercalar2=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$intercalar2=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$intercalar2=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$intercalar2=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$intercalar2=="0#0#0"? "selected":"")?> value="0#0#0">Preto</option>
		    <option <?echo(@$intercalar2=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$intercalar2=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		  </select>  
		</td>
	      </tr>  	 		    
	    </table>  	    
	 </td>
	</tr>
      </table>  
  </tr>
        <tr><td>&nbsp;</td></tr> 		    
  <tr>
    <td valign="top">
      <table border="0" cellspacing="0" cellpadding="0" width="100%" style="border: 2px outset #999999">
        <tr>
	  <td  align="top" width="85%" height="25">
	    <table  width="100%" cellspacing="0" cellpadding="0" border="" style="border: 2px outset #999999">
	      <tr>
                <td width="15%" align="center" bordercolor="#ffffff"  style="border: 2px outset #999999" colspan="1">Colunas</td>
	        <td width="53%" align="center" bordercolor="#ffffff" style="border: 2px outset #999999" colspan="4">Texto</td>
	        <td width="40%" align="center" bordercolor="#ffffff" style="border: 2px outset #999999" colspan="4">Célula</td>
	      </tr>
              <tr class='cabec'>
                <td width="15%"nowrap  style="border: 2px outset #999999" bordercolor="#cccccc">Cabeçalho</td>
                <td align="center"width="8%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Fonte</td>
                <td align="center"width="10%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Tamanho</td>
                <td align="center"width="10%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Cor</td>
                <td align="center"width="12%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Estilos</td>
                <td align="center"width="10%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Borda</td>
                <td align="center"width="12%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Fundo</td>
	        <td align="center"width="10%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Altura</td>
                <td align="center"width="10%"  style="border: 2px outset #999999"nowrap bordercolor="#cccccc">Largura</td>
	      </tr>
	      <?
                 for ($i = 0;$i < $fm_numfields;$i++){
	          $qualcampo = pg_fieldname($resultsql,$i);
                  $tcampo = "T".$qualcampo;
                  $lcampo = "L".$qualcampo;
                  $mcampo = "M".$qualcampo;
                
		  $clrotulolov->label(pg_fieldname($resultsql,$i));
                  $largura =(($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2;

                  if(@$libera==true&&$tsql==$sql){
                    $consult= pg_exec("select * from db_gerpref where codger = $codigo");
                    $tcabecfonte =@pg_result($consult,$i,2);
                    $tcabectamanho = @pg_result($consult,$i,3);
                    $tcabecn = @pg_result($consult,$i,4);
                    $tcabeci = @pg_result($consult,$i,5);
                    $tcabecs= @pg_result($consult,$i,6);
                    $tcabeccortexto= @pg_result($consult,$i,7);
                    $tcabeccorborda= @pg_result($consult,$i,8);
                    $tcabeccorfundo= @pg_result($consult,$i,9);
                    $tcabecaltura= @pg_result($consult,$i,10);
                    $tcabeclargura= @pg_result($consult,$i,11);
                  }
	      ?>
	      <tr height="28" width="100%"  class='corpo'>
	        <?
		$qualcampo = pg_fieldname($resultsql,$i);
                $rotulocampo->label($qualcampo);
                $tcampo = "T".$qualcampo;
                $lcampo = "L".$qualcampo;
                $mcampo = "M".$qualcampo;
		
		?>
	        <td  height="25" width="20%" bordercolor="#ffffff"nowrap title="<?=$$tcampo?>">
                  <?
		   echo $$lcampo;
	           ?>
		</td>
		  <td width="14%" nowrap bordercolor="#ffffff">
		  <select name="cabecfonte_<?=@$i?>">
		    <option <?echo(@$tcabecfonte=="courier"? "selected":"")?> <?echo @$sele?> value="courier" >Courier</option>
		    <option <?echo(@$tcabecfonte=="arial"? "selected":"")?> value="arial">Arial</option>
		    <option <?echo(@$tcabecfonte=="times"? "selected":"")?>value="times">Times</option>
		</td>
		<td align="center" width="12%" bordercolor="#ffffff"nowrap >
		  <select name="cabectamanho_<?=@$i?>">
		    <?
		     for($t=0; $t<= 20; $t++){
       		       if(isset($tcabectamanho)){	
                         echo "<option ".($t==@$tcabectamanho?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==9?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
	        <td width="12%" bordercolor="#ffffff"nowrap >
		  <select name="cabeccortexto_<?=@$i?>">
		    <option <?echo(@$tcabeccortexto=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$tcabeccortexto=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$tcabeccortexto=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$tcabeccortexto=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$tcabeccortexto=="0#0#0"? "selected":"")?> <?echo @$sele?> value="0#0#0">Preto</option>
		    <option <?echo(@$tcabeccortexto=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$tcabeccortexto=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		</td>
		<td width="12%" bordercolor="#ffffff"nowrap>
		  N<input type="checkbox" name="cabecn_<?=@$i?>" value="B" <?echo(@$tcabecn=="B"? "checked":"")?> <?echo @$sele1?> >
		  I<input type="checkbox" name="cabeci_<?=@$i?>" value="I" <?echo(@$tcabeci=="I"? "checked":"")?>>
		  S<input type="checkbox" name="cabecs_<?=@$i?>" value="U" <?echo(@$tcabecs=="U"? "checked":"")?>>
	        </td>
	        <td width="12%" bordercolor="#ffffff"nowrap>
		  <select name="cabeccorborda_<?=@$i?>">
		    <option <?echo(@$tcabeccorborda=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$tcabeccorborda=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$tcabeccorborda=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$tcabeccorborda=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$tcabeccorborda=="0#0#0"? "selected":"")?> <?echo @$sele?> value="0#0#0">Preto</option>
		    <option <?echo(@$tcabeccorborda=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$tcabeccorborda=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		</td>
	        <td width="12%" bordercolor="#ffffff"nowrap>
		  <select name="cabeccorfundo_<?=@$i?>">
		    <option <?echo(@$tcabeccorfundo=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$tcabeccorfundo=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$tcabeccorfundo=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$tcabeccorfundo=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$tcabeccorfundo=="0#0#0"? "selected":"")?> value="0#0#0">Preto</option>
		    <option <?echo(@$tcabeccorfundo=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$tcabeccorfundo=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		</td>
	        <td align="center" bordercolor="#ffffff" width="12%" nowrap >
		  <select name="cabecaltura_<?=@$i?>">
		    <?
		     for($t=0; $t<= 20; $t++){
       		       if(isset($tcabecaltura)){	
                         echo "<option ".($t==$tcabecaltura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==4?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
	        <td align="center"width="12%" bordercolor="#ffffff"nowrap >
		  <select name="cabeclargura_<?=@$i?>">
		    <?
		     for($t=0; $t<= 250; $t++){
       		       if(isset($tcabeclargura)){	
                         echo "<option ".($t==@$tcabeclargura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==$largura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
	      <tr>
	      <?}?>
	    </table>
	  </td>
	</tr>
      </table>
    
  </tr>
  <tr>
    <td valign="top">
      <table cellspacing="0" cellpadding="0" border="1"  width="100%">
        <tr>
          <td width="15%" align="center" bordercolor="#ffffff"colspan="1">Colunas</td>
          <td width="53%" align="center" bordercolor="#ffffff"colspan="5">Texto</td>
          <td width="40%" align="center" bordercolor="#ffffff"colspan="5">Célula</td>
        </tr>
        <tr class='cabec'>
          <td width="15%"nowrap bordercolor="#cccccc"  >Corpo</td>
          <td align="center"width="8%" nowrap bordercolor="#cccccc">Fonte</td>
          <td align="center"width="8%" nowrap bordercolor="#cccccc">Opções</td>
          <td align="center"width="10%" nowrap bordercolor="#cccccc">Tamanho</td>
          <td align="center"width="10%" nowrap bordercolor="#cccccc">Cor</td>
          <td align="center"width="12%" nowrap bordercolor="#cccccc">Estilos</td>
	  <td align="center"width="10%" nowrap bordercolor="#cccccc">Borda</td>
	  <td align="center"width="12%" nowrap bordercolor="#cccccc">Fundo</td>
	  <td align="center"width="10%" nowrap bordercolor="#cccccc">Altura</td>
	  <td align="center"width="10%" nowrap bordercolor="#cccccc">Largura</td>
	</tr>
	 <? for ($i = 0;$i < $fm_numfields;$i++){
	      $qualcampo = pg_fieldname($resultsql,$i);
              $tcampo = "T".$qualcampo;
              $lcampo = "L".$qualcampo;
              $mcampo = "M".$qualcampo;
	      $clrotulolov->label(pg_fieldname($resultsql,$i));
              $largura =(($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2;

              if(@$libera==true&&$tsql==$sql){
                $consult= pg_exec("select * from db_gerpref where codger=$codigo");
                $tcorpfonte = @pg_result($consult,$i,12);
                $tcorpopcao = @pg_result($consult,$i,13);
                $tcorptamanho = @pg_result($consult,$i,14);
                $tcorpn = @pg_result($consult,$i,15);
                $tcorpi = @pg_result($consult,$i,16);
                $tcorps= @pg_result($consult,$i,17);
                $tcorpcortexto= @pg_result($consult,$i,18);
                $tcorpcorborda= @pg_result($consult,$i,19);
                $tcorpcorfundo= @pg_result($consult,$i,20);
                $tcorpaltura= @pg_result($consult,$i,21);
                $tcorplargura= @pg_result($consult,$i,22); 
 	      }    	
          ?> 
	      <tr height="25"  class='corpo'>
	        <td  height="25" width="20%" bordercolor="#ffffff"nowrap title="<?=$$tcampo?>">
                  <?
	           echo $$lcampo;
		   ?>
		</td>
	        <td width="14%" bordercolor="#ffffff"nowrap>
		  <select name="corpfonte_<?=@$i?>">
		    <option <?echo(@$tcorpfonte=="courier"? "selected":"")?> value="courier" >Courier</option>
		    <option <?echo(@$tcorpfonte=="arial"? "selected":"")?> value="arial">Arial</option>
		    <option <?echo(@$tcorpfonte=="times"? "selected":"")?> value="times">Times</option>
		</td>
	        <td width="14%" bordercolor="#ffffff"nowrap>
		  <select name="corpopcao_<?=@$i?>">
		    <option <?echo(@$tcorpopcao=="nenhuma"? "selected":"")?> value="nenhuma">Nenhuma</option>
		    <option <?echo(@$tcorpopcao=="somar"? "selected":"")?> value="somar" >Somar</option>
		    <option <?echo(@$tcorpopcao=="contar"? "selected":"")?> value="contar" >Contar</option>
		    <option <?echo(@$tcorpopcao=="preenchidos"? "selected":"")?> value="preenchidos" >Preenchidos</option>
		</td>
		<td align="center" bordercolor="#ffffff"width="12%" nowrap >
		  <select name="corptamanho_<?=@$i?>">
		    <?
		     for($t=0; $t<= 20; $t++){
       		       if(isset($tcorptamanho)){	
                         echo "<option ".($t==@$tcorptamanho?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==9?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
	        <td width="12%" bordercolor="#ffffff"nowrap >
		  <select name="corpcortexto_<?=@$i?>">
		    <option <?echo(@$tcorpcortexto=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$tcorpcortexto=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$tcorpcortexto=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$tcorpcortexto=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$tcorpcortexto=="0#0#0"? "selected":"")?> <?echo @$sele?> value="0#0#0">Preto</option>
		    <option <?echo(@$tcorpcortexto=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$tcorpcortexto=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		</td>
		<td width="12%" bordercolor="#ffffff"nowrap>
		  N<input type="checkbox" name="corpn_<?=@$i?>" value="B" <?echo(@$tcorpn=="B"? "checked":"")?>>
		  I<input type="checkbox" name="corpi_<?=@$i?>" value="I" <?echo(@$tcorpi=="I"? "checked":"")?>>
		  S<input type="checkbox" name="corps_<?=@$i?>" value="U" <?echo(@$tcorps=="U"? "checked":"")?>>
		</td>
	        <td width="12%" bordercolor="#ffffff"nowrap>
		  <select name="corpcorborda_<?=@$i?>">
		    <option <?echo(@$tcorpcorborda=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$tcorpcorborda=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$tcorpcorborda=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$tcorpcorborda=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$tcorpcorborda=="0#0#0"? "selected":"")?> value="0#0#0">Preto</option>
		    <option <?echo(@$tcorpcorborda=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$tcorpcorborda=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
                </td>
	        <td width="12%" bordercolor="#ffffff"nowrap>
		  <select name="corpcorfundo_<?=@$i?>">
		    <option <?echo(@$tcorpcorfundo=="255#255#255"? "selected":"")?> value="255#255#255">Branco</option>
		    <option <?echo(@$tcorpcorfundo=="0#0#200"? "selected":"")?> value="0#0#200">Azul</option>
		    <option <?echo(@$tcorpcorfundo=="255#255#0"? "selected":"")?> value="255#255#0">Amarelo</option>
		    <option <?echo(@$tcorpcorfundo=="200#200#200"? "selected":"")?> value="200#200#200">Cinza</option>
		    <option <?echo(@$intercalar1=="150#150#150"? "selected":"")?> value="150#150#150">Cinza2</option>
		    <option <?echo(@$tcorpcorfundo=="0#0#0"? "selected":"")?> value="0#0#0">Preto</option>
		    <option <?echo(@$tcorpcorfundo=="0#100#0"? "selected":"")?> value="0#100#0">Verde</option>
		    <option <?echo(@$tcorpcorfundo=="200#0#0"? "selected":"")?> value="200#0#0">Vermelho</option>
		</td>
	        <td align="center"width="12%" bordercolor="#ffffff"nowrap >
		  <select name="corpaltura_<?=@$i?>">
		    <?
		     for($t=0; $t<= 20; $t++){
       		       if(isset($tcorpaltura)){	
                         echo "<option ".($t==@$tcorpaltura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==4?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
	        <td align="center"width="12%" bordercolor="#ffffff"nowrap >
		  <select name="corplargura_<?=@$i?>">
		    <?
		     for($t=0; $t<= 250; $t++){
       		       if(isset($tcorplargura)){	
                         echo "<option ".($t==$tcorplargura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
		       }else{
                         echo "<option ".($t==$largura?'selected':'')." value=\"".$t."\">".$t."</option>\n";
                       } 
 		     }
		    ?> 
		</td>
	      <tr>
	      <?}?>
	    </table>
	  
	</tr>
      </table>
    </table>
	 <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
     ?>
</body>
<html>