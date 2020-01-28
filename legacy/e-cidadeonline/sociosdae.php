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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
db_logs("","",0,"cadastro de socios do dai.");
postmemory($HTTP_POST_VARS);
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_vericampos(){
  var alerta="";
  if(document.form1.cgc.value!=""){
    cgccpf=document.form1.cgc.value;
  }else{
    cgccpf=document.form1.cpf.value;
  }   
  rg= new Number(document.form1.rg.value);
  nome=document.form1.nome.value;
  ender=document.form1.ender.value;
  numero=new Number(document.form1.numero.value);
  compl=document.form1.compl.value;
  bairro=document.form1.bairro.value;
  cep= document.form1.cep.value;
  cep1 = cep.replace('-',''); 
  cep1= new Number(cep1);
  uf=document.form1.uf.value;
  percentual= new Number(document.form1.percentual.value);
  if(cgccpf==""){
    alerta +="CNPJ/CPF\n";
  }
  if(nome==""){
    alerta +="Nome\n";
  }
  if(isNaN(rg)){
    alerta +="RG\n";
  }
  if(ender==""){
    alerta +="Endereço\n";
  }
  if(numero=="" || isNaN(numero)){
    alerta +="Número\n";
  }
  if(bairro==""){
    alerta +="Bairro\n";
  }
  if(cep=="" || isNaN(cep1)){
    alerta +="Cep\n";
  }
  if(uf==""){
    alerta +="UF\n";
  }
  if(rg==""){
    document.form1.rg.value = 0;
  }
  if(percentual=="" || isNaN(percentual)){
    alerta +="Percentual de Sociedade\n";
  }
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
    return true;
  }
return false;
}
</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<?
mens_div();
?>
<table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>" class="texto">
  <tr>
    <td align="left" valign="top">
      <form name="form1" action="sociosdae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo)?>" method="post">
        <input type="hidden" name="tamanho">
          <table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <table cellpadding="3" cellspacing="0" class="texto">
                  <tr class="titulo2">
                     <td colspan="4" >
                      <br>* digite o CPF ou CNPJ do SÓCIO da empresa
                    </td>
                  </tr>
                     <tr class="titulo2" > 
                    <td align="left">CPF:&nbsp;<br>
                    <input name="cpf" type="text" id="cpf" size="14" maxlength="14" onKeyDown="FormataCPF(this,2)"></td>
                    <td align="left">CNPJ:&nbsp;<br>
                    <input name="cgc" type="text" class="digitacgccpf" onKeyDown="FormataCNPJ(this,event)" size="18" maxlength="18"></td>
                    <td align="left">RG:&nbsp;<br>
                    <input name="rg" type="text" class="digitacgccpf" size="10" maxlength="10"></td>
                    <td align="left">Nome:&nbsp;<br>
                    <input name="nome" type="text" size="40" maxlength="40" onKeyUp="js_maiusculo(this)"></td>
                  </tr>
                </table>
              </td>
            </tr>  
            <tr > 
              <td>
                <table cellpadding="3" cellspacing="0" class="texto">
                     <tr class="titulo2"> 
                    <td align="left">Endereço:&nbsp;<br>
                    <input name="ender" type="text" size="40" maxlength="40" onKeyUp="js_maiusculo(this)"></td>
                    <td align="left">Número:&nbsp;<br>
                    <input name="numero" type="text" size="6" maxlength="6"></td>
                    <td align="left">Compl.:&nbsp;<br>
                    <input name="compl" type="text" size="10" maxlength="10" onKeyUp="js_maiusculo(this)"></td>
                  </tr>
                </table>
              </td>
            </tr> 
            <tr> 
              <td>
                <table cellpadding="3" cellspacing="0" class="texto">
                     <tr class="titulo2"> 
                    <td align="left">Bairro:&nbsp;<br>
                    <input name="bairro" type="text" size="40" maxlength="40" onKeyUp="js_maiusculo(this)"></td>
                    <td align="left">Cep:&nbsp;<br>
                    <input name="cep" type="text" size="9" maxlength="9" onKeyDown="(this.value.length == 5)?this.value=this.value+'-':''"></td>
                    <td align="left">UF:&nbsp;<br>
                    <input name="uf" type="text" size="2" maxlength="2" onKeyUp="js_maiusculo(this)"></td>
                    <td align="left">Sociedade:&nbsp;<br>
                    <input name="percentual" type="text" size="10" maxlength="10" onChange="(this.value.indexOf(',') != -1?this.value = this.value.replace(',','.'):'')">&nbsp;%</td>
                  </tr>
                </table>
              </td>
            </tr>  
            <tr> 
              <td> 
                 <input name="salvasocios" class="botao" type="submit"  value="Salvar" onClick="js_itens();return js_vericampos();"> 
              </td>
            </tr>
            <tr>
              <td>
                <table id="linhas" width="490" class="tab">
                <script>
                function js_itens(){
                  var numero = document.getElementById('linhas').rows.length;
                  document.form1.linhas.value = (numero-1);
                }        
                </script>
                <input type="hidden" name="linhas">
                  <tr>
                    <th id="colocar">
                      Nome
                    </th>
                    <th width="40%" >
                      CNPJ/CPF
                    </th>
                    <th>
                      Percentual
                    </th>
                    <th></th>
                  </tr>
                  <? 
                  if(isset($primeira)){ 
                    $result = db_query("select * from db_daesocios where w06_codigo = $codigo");
                    if(pg_numrows($result) == 0 ){     
                      $result1 = db_query("select * from cgm inner join issbase on q02_numcgm = z01_numcgm where q02_inscr = $inscricaow");
                      if(pg_numrows($result1)!= 0){
                        db_fieldsmemory($result1,0);
                                                
                        $result1 = db_query("select * from cgm inner join socios on z01_numcgm = q95_numcgm where q95_cgmpri = $z01_numcgm");
                        $linha = pg_num_rows($result1);
						if ($linha>0){                        
	                        for($j=0;$j<$linha;$j++){
	                        	                       
	                          db_fieldsmemory($result1,$j);
	                          $result = db_query("insert into db_daesocios values($codigo,$j,'$z01_cgccpf','0','$z01_nome','$z01_ender',$z01_numero,'$z01_compl','$z01_bairro',$z01_cep,'$z01_uf',$q95_perc)");  
	                        }
						}
                       // db_redireciona("sociosdae.php?".base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1'));
                       // exit;
                      }else{ 
                        db_redireciona("sociosdae.php?".base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo));
                        exit;
                      }
                    }else{/// aki   
                          
                      for($i=0;$i<pg_numrows($result);$i++){
                        db_fieldsmemory($result,$i);
                        if(strlen(trim($w06_cgccpf))==14){
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cnpj');
                        }else{
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cpf');
                        }
                        echo"<tr align=\"center\">
                               <td width=\"50%\" >
                                 $w06_nome
                               </td>
                               <td width=\"20%\">
                                 $w06_cgccpf
                               </td>
                               <td width=\"20%\">
                                 $w06_percent %
                               </td>
                               <td>
                                 <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w06_socio'\">
                                 <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w06_socio'\">
                               </td>
                             </tr>
                             <input type=\"hidden\" name=\"item$i\" value=\"$w06_socio#$w06_cgccpf#$w06_rg#$w06_nome#$w06_ender#$w06_numero#$w06_compl#$w06_bairro#$w06_cep#$w06_uf#$w06_percent\">
                             ";
                      }
                    }
                  }        
                  if(isset($salvasocios)){ // entra aki quando inclui 
                    
                    db_query("delete from db_daesocios where w06_codigo = $codigo");
                    if($linhas == 0){  
                      $linhas = 0;
                      if(isset($cgc) && $cgc!=""){
                        $cgccpf = $cgc;
                      }else{
                        $cgccpf = $cpf;
                      }
                      if(isset($cgccpf) && $cgccpf != ""){
                        $cgccpf = str_replace('.','',$cgccpf);
                        $cgccpf = str_replace('-','',$cgccpf);
                        $cgccpf = str_replace('/','',$cgccpf);
                        $cgccpf = $cgccpf;
                      }  
                      if(isset($cep)){
                        $cep = str_replace('-','',$cep);
                      }  
                      if(isset($percentual)){
                        $percentual = str_replace(',','.',$percentual);
                      }  
                      $insere = db_query("insert into db_daesocios values($codigo,$linhas,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual)"); 
db_query("commit"); 
                    }else{// vai entrar aki
                     
                      if(isset($cgc) && $cgc!=""){
                        $cgccpf = $cgc;
                      }else{
                        $cgccpf = $cpf;
                      }
                      if(isset($cgccpf) && $cgccpf != ""){
                        $cgccpf = str_replace('.','',$cgccpf);
                        $cgccpf = str_replace('-','',$cgccpf);
                        $cgccpf = str_replace('/','',$cgccpf);
                        $cgccpf = $cgccpf;
                      }  
                      if(isset($cep)){
                        $cep = str_replace('-','',$cep);
                      }  
                      if(isset($percentual)){
                        $percentual = str_replace(',','.',$percentual);
                      } 
                      //echo "<br>linhas = $linhas<br>";
                      // echo "<br> 1 - insert into db_daesocios values($codigo,$linhas,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual";
                      db_query("insert into db_daesocios values($codigo,$linhas,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual)");  
                      for($x=0;$x<$linhas;$x++){
                        $input = "item".$x;
                        $matriz = split('#',$$input);
                        $item = $matriz[0];
                        $cgccpf = $matriz[1];
                        $rg = $matriz[2];
                        $nome = $matriz[3];
                        $ender = $matriz[4];
                        $numero = $matriz[5];
                        $compl = $matriz[6];
                        $bairro = $matriz[7];
                        $cep = $matriz[8];
                        $uf = $matriz[9];
                        $percentual = $matriz[10];
                        if(isset($cgccpf) && $cgccpf != ""){
                          $cgccpf = str_replace('.','',$cgccpf);
                          $cgccpf = str_replace('-','',$cgccpf);
                          $cgccpf = str_replace('/','',$cgccpf);
                          $cgccpf = $cgccpf;
                        }  
                       // echo "<br> 2 - insert into db_daesocios values($codigo,$x,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual";
                        $result = db_query("insert into db_daesocios values($codigo,$x,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual)");
                          
                      }
                    }  
                    $result = db_query("select * from db_daesocios where w06_codigo = $codigo");
                    if(pg_numrows($result) == 0){
                      db_redireciona("sociosdae.php?".base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1'));
                    }else{  
                      for($i=0;$i<pg_numrows($result);$i++){
                        db_fieldsmemory($result,$i);
                        if(strlen(trim($w06_cgccpf))==14){
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cnpj');
                        }else{
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cpf');
                        }
                        echo"<tr align=\"center\">
                               <td width=\"50%\" >
                                 $w06_nome
                               </td>
                               <td width=\"20%\">
                                 $w06_cgccpf
                               </td>
                               <td width=\"20%\">
                                 $w06_percent %
                               </td>
                               <td>
                                 <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w06_socio'\">
                                 <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w06_socio'\">
                               </td>
                             </tr>
                             <input type=\"hidden\" name=\"item$i\" value=\"$w06_socio#$w06_cgccpf#$w06_rg#$w06_nome#$w06_ender#$w06_numero#$w06_compl#$w06_bairro#$w06_cep#$w06_uf#$w06_percent\">
                             ";
                      }
                    }            
                  }elseif(isset($alterar)){
                    $result = db_query("select * from db_daesocios where w06_codigo = $codigo and w06_socio = $qual");
                    db_fieldsmemory($result,0);
                    db_query("delete from db_daesocios where w06_socio = $qual and w06_codigo = $codigo");
                    if(strlen(trim($w06_cgccpf))==14){
                      echo "<script>document.form1.cgc.value = '".db_formatar($w06_cgccpf,'cnpj')."'</script>";
                    }else{
                      echo "<script>document.form1.cpf.value = '".db_formatar($w06_cgccpf,'cpf')."'</script>";
                    }
                    echo "<script>document.form1.rg.value = '$w06_rg'</script>";
                    echo "<script>document.form1.nome.value = '$w06_nome'</script>";
                    echo "<script>document.form1.ender.value = '$w06_ender'</script>";
                    echo "<script>document.form1.numero.value = '$w06_numero'</script>";
                    echo "<script>document.form1.compl.value = '$w06_compl'</script>";
                    echo "<script>document.form1.bairro.value = '$w06_bairro'</script>";
                    $cinco = substr($w06_cep,0,5); 
                    $tres = substr($w06_cep,4,3);
                    $cep = $cinco."-".$tres;
                    echo "<script>document.form1.cep.value = '$cep'</script>";
                    echo "<script>document.form1.uf.value = '$w06_uf'</script>";
                    echo "<script>document.form1.percentual.value = '$w06_percent'</script>";
                    $result = db_query("select * from db_daesocios where w06_codigo = $codigo");
                    if(pg_numrows($result) == 0 && $qual == ""){
                      db_redireciona("sociosdae.php?".base64_encode("inscricaow=".$inscricaow."&codigo=".$codigo));
                    }else{  
                      db_query("delete from db_daesocios where w06_codigo = $codigo");
                      for($i=0;$i<pg_numrows($result);$i++){
                        db_fieldsmemory($result,$i);
                        if(strlen(trim($w06_cgccpf))==14){
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cnpj');
                        }else{
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cpf');
                        }
                        echo"<tr align=\"center\">
                               <td width=\"50%\" >
                                 $w06_nome
                               </td>
                               <td width=\"20%\">
                                 $w06_cgccpf
                               </td>
                               <td width=\"20%\">
                                 $w06_percent %
                               </td>
                               <td>
                                 <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w06_socio'\">
                                 <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w06_socio'\">
                               </td>
                             </tr>
                             <input type=\"hidden\" name=\"item$i\" value=\"$i#$w06_cgccpf#$w06_rg#$w06_nome#$w06_ender#$w06_numero#$w06_compl#$w06_bairro#$w06_cep#$w06_uf#$w06_percent\">
                             ";
                      }
                    }            
                  }elseif(isset($excluir)){
                    //db_postmemory($HTTP_POST_VARS,2);
                    db_query("delete from db_daesocios where w06_codigo = $codigo and w06_socio = $qual");
                    $result = db_query("select * from db_daesocios where w06_codigo = $codigo");
                    if(pg_numrows($result) == 0){
                      db_redireciona("sociosdae.php?".base64_encode("inscricaow=".$inscricaow."&codigo=".$codigo));
                    }else{  
                      for($i=0;$i<pg_numrows($result);$i++){
                        db_fieldsmemory($result,$i);
                        if(strlen(trim($w06_cgccpf))==14){
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cnpj');
                        }else{
                          $w06_cgccpf = db_formatar($w06_cgccpf,'cpf');
                        }
                        echo"<tr align=\"center\">
                               <td width=\"50%\" >
                                 $w06_nome
                               </td>
                               <td width=\"20%\">
                                 $w06_cgccpf
                               </td>
                               <td width=\"20%\">
                                 $w06_percent %
                               </td>
                               <td>
                                 <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w06_socio'\">
                                 <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w06_socio'\">
                               </td>
                             </tr>
                             <input type=\"hidden\" name=\"item$i\" value=\"$i#$w06_cgccpf#$w06_rg#$w06_nome#$w06_ender#$w06_numero#$w06_compl#$w06_bairro#$w06_cep#$w06_uf#$w06_percent\">
                             ";
                      }
                    }            
                  }        
                  ?>
                  <input type="hidden" name="qual" value="">
                </table>
              </td>
            </tr>
          </table>
      </form>
    </td>
  </tr>
</table>
</form>
</body>
</html>