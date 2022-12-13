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

$rstab = pg_exec("select d.nomemod,m.codarq,a.nomearq
                   from   db_sysarquivo a
                          inner join db_sysarqmod m
                          on a.codarq = m.codarq
                          inner join db_sysmodulo d
                          on d.codmod = m.codmod order by nomemod");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function mo_camada(camada){
   x = camada;
   alvo = document.getElementById(camada);
   divs  = document.getElementsByTagName("DIV");
   for (var j = 0; j < divs.length; j++){
      if (divs[j].className =="tabela" && alvo.id == divs[j].id){
         alvo.style.visibility = "visible";
      }else if (divs[j].className =="tabela" && alvo.id != divs[j].id){
         divs[j].style.visibility = "hidden";
      }
   }
}
function valida_submit(){
    if (document.estrut.nome_arq == ""){
       alert('O nome do arquivo deve ser informado!!!'); 
       return false;
    }else{
       return true
    }
}	
</script>
<style type="text/css">
.tabela {border:1px solid black; top:25px; left:150}
.input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="js_trocacordeselect()">
<table width=790 bgcolor="#CCCCCC">

  <tr>
     <td height=25>&nbsp;</td>
  </tr>		
</table>
<?if(!isset($HTTP_POST_VARS["b_estrut"])) {?>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc" style='border:1px solid black'>
   <form method="post" name="estrut" onsubmit="return valida_submit();" action="<?echo $PHP_SELF;?>">
  <tr> 
     <td colspan=4 align='center' style='border-bottom:1px solid black'><font size='4'><b>Módulos</b></font></td>
  </tr>
  <tr>
  <td colspan=2>
 <? 
      $rsmod = pg_exec("select m.codmod,m.nomemod 
     	                from   db_sysmodulo m
	               	       inner join db_sysarqmod s
			       on s.codmod = m.codmod
		        group by m.codmod,m.nomemod
   	                order by nomemod");
      echo  "<select  name='modulos' size='10' onchange=\"mo_camada(document.estrut.modulos.value);\">";
     for ($i = 0;$i < pg_numrows($rsmod); $i++) {
          echo "<option value='".trim(pg_result($rsmod,$i,"nomemod"))."'>".trim(pg_result($rsmod,$i,"nomemod"))."</option>\n";  
      }
 ?>
  </select></td></tr>
  <tr><td colspan5><b>Nome do Arquivo</b></td></tr>
   <tr>
     <td><input type="text" name="nome_arq" class="input" value=""></td></tr>
 </table>
<br>
 <table  border=0 cellspacing=0 style="border:1px solid black">
  <tr> 
     <td colspan=2 align='center' style='border-bottom:1px solid black'><font size='4'><b>Opções</b></font></td>
  </tr>
   <tr>
      <td><b>Incluir Indices:</b></td>
      <td> <input type="checkbox" class="radio" id="indi" name="indice" checked></td>
   </tr>
   <tr>
       <td><b>Incluir PK:</b></td>
       <td><input type="checkbox" class="radio" id="prim" name="pk" checked></td>
    </tr>
    <tr>
       <td><b>Incluir FK:</b></td>
       <td> <input type="checkbox" class="radio" id="fore" name="fk" checked></td>
    </tr>
    <tr>
       <td><b>Incluir Fun&ccedil;&otilde;es:</b></td>
       <td><input name="funcoes" type="checkbox" id="funcoes" value="funcoes"></td>
    </tr>
    <tr>
       <td><b>Incluir Views:</b></td>
       <td> <input name="views" type="checkbox" id="views" value="views"></td>
    </tr>
    <tr>
       <td><b>Incluir Triggers:</b></td>
       <td><input name="triggers" type="checkbox" id="triggers" value="triggers"></td>
  </tr>
  <tr>
     <td colspan=2 align='center'><input type="submit" name="b_estrut" value="Gerar" class="input"></td>
  </tr>
  <script>
  var x = '';
     function js_marcatodos(nome,tam){
       for(i =0;i < document.estrut.elements.length;i++){
         if(document.estrut.elements[i].type == 'checkbox' && document.estrut.elements[i].name != 'indice' && document.estrut.elements[i].name != 'pk' && document.estrut.elements[i].name != 'fk' && document.estrut.elements[i].name != 'funcoes' && document.estrut.elements[i].name != 'views' && document.estrut.elements[i].name != 'triggers'){
	   if(document.estrut.elements[i].id.substr(0,tam) == nome && document.estrut.elements[i].checked == false){
	     document.estrut.elements[i].checked = true;
	     document.estrut.nome_arq.value = nome + '.sql';
	   }else{
	     if(document.estrut.elements[i].id.substr(0,tam) == nome && document.estrut.elements[i].checked == true){
	       document.estrut.elements[i].checked = false;
	     }
	   }
	 }
       }
     }
   </script>  

 <?
   // cria as layers com o conteúdo das tabelas
   $j = 0;
   $modulo = "";
  //define quantos checkboxes iram ficar por linha da tabela.
   $quebratab = 1;
   while ($j < pg_numrows($rstab)){
      db_fieldsmemory($rstab,$j); 
      if ($modulo == $nomemod){
	if ($quebratab == 4){
            $quebratab = 1;	
            echo "</tr><tr>";
        }else{
            $quebratab++;
        }
        echo "<td width=135><input type='checkbox' id='".$nomemod.$j."'  name='chk".$nomearq.$codarq."' onClick=\"js_preenchenome('$nomemod',chk".$nomearq.$codarq.")\" value='".$codarq."'>".$nomearq."</td>\n";
      }else{
         $quebratab=1;
         echo "</table>";
         echo "</div><div id='".$nomemod."'style='position:absolute; visibility:hidden' class='tabela'>";
         echo "<table border=0 cellspacing=0>";
         echo "<td style='border-bottom:1px solid black'>&nbsp;&nbsp;<a  onClick='js_marcatodos(\"".$nomemod."\",\"".strlen($nomemod)."\");return false'href='' title='Marcar/Desmarcar todos'>M</a></td><td colspan='3' align='center' style='border-bottom:1px solid black'><font size='4'><b>Tabelas - módulo $nomemod</b></font></td></tr>";
         echo "<tr>\n<td width=135><input id='".$nomemod.$j."' type='checkbox' name='chk".$nomearq.$codarq."' value='".$codarq."' onClick=\"js_preenchenome('$nomemod',chk".$nomearq.$codarq.")\">".$nomearq."</td>\n";
     }
   $modulo = $nomemod;
   $j++;
   }
    echo "</tr>";
echo "</table>";
   echo "</div>";     
        

  ?>      
  </form> 
  <?
} else {
  if (empty($_POST["nome_arq"])){
     db_msgbox('O nome do Arquivo não pode estar vazio');
     db_redireciona(); 
  }
  //cria o arquivo 
  umask(74); 
  $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
  $arquivo = "/tmp/".$_POST["nome_arq"];
  $fd = fopen($arquivo,'w');
  $sequencias = $_POST;
  $indi = @$_POST["pk"];
  $csequencias = $_POST;
  $FKs = $_POST;
  $PKS = $_POST;
  $camp = $_POST;
  $IND =  $_POST;
  $indice =@ $_POST["indice"];
  fputs($fd,"--DROP TABLE:\n");
  while (list($campo,$valor) = each($_POST)){
     if (substr($campo,0,3) == "chk"){
          $campos  = pg_exec("select a.codarq,a.nomearq,m.codmod,m.nomemod
                              from   db_sysmodulo m
                                     inner join db_sysarqmod am
                                     on am.codmod = m.codmod
                                     inner join db_sysarquivo a
                                     on a.codarq = am.codarq
                              where a.codarq = $valor  
                              order by codmod");
	    
           if(pg_numrows($campos) > 0){
               db_fieldsmemory($campos,0);
                fputs($fd, "DROP TABLE ".trim($nomearq).";\n");      	  
	  }
     }
  }
  //cria drop sequences;
  fputs($fd,"--Criando drop sequences\n");
  while (list($campo1,$valor1) = each($sequencias)){
     if (substr($campo1,0,3) == "chk"){
         $seq = pg_exec("select s.nomesequencia,s.incrseq,s.minvalueseq,maxvalueseq,startseq,s.cacheseq
	                  from db_syssequencia s
					  inner join db_sysarqcamp a
					  on a.codsequencia = s.codsequencia
					  where a.codsequencia <> 0
					  and a.codarq = $valor1");
	   if (pg_numrows($seq) > 0){
              $rsseq = pg_fetch_array($seq);
  	      fputs($fd, "DROP SEQUENCE ".$rsseq["nomesequencia"].";\n");      	 
           }
      } 
 } 
  //cria  sequences;
  fputs($fd, "\n\n-- Criando  sequences\n");
  while (list($campo2,$valor2) = each($csequencias)){
     if (substr($campo2,0,3) == "chk"){
         $cseq = pg_exec("select s.nomesequencia,s.incrseq,s.minvalueseq,maxvalueseq,startseq,s.cacheseq
	                  from db_syssequencia s
					  inner join db_sysarqcamp a
					  on a.codsequencia = s.codsequencia
					  where a.codsequencia <> 0
					  and a.codarq = $valor2");
	   if (pg_numrows($cseq) > 0){
              $rscseq = pg_fetch_array($cseq);
  	      fputs($fd,"CREATE SEQUENCE ".$rscseq["nomesequencia"]."\n");
  	      fputs($fd,"INCREMENT ".trim($rscseq["incrseq"])."\n");
  	      fputs($fd, "MINVALUE ".trim($rscseq["minvalueseq"])."\n");
  	      fputs($fd, "MAXVALUE ".trim($rscseq["maxvalueseq"])."\n");		 
  	      fputs($fd, "START ".trim($rscseq["startseq"])."\n");		 		 
  	      fputs($fd, "CACHE ".trim($rscseq["cacheseq"]).";\n");
	      fputs($fd,"\n\n"); 
           }
      } 
   }
   //cria as tabelas e sua estrutura
     fputs($fd,"-- TABELAS E ESTRUTURA\n");
    while (list($campo3,$valor3) = each($camp)){;
       if (substr($campo3,0,3) == "chk"){
          $tabela  = pg_exec("select a.codarq,a.nomearq,m.codmod,m.nomemod
                              from   db_sysmodulo m
                                     inner join db_sysarqmod am
                                     on am.codmod = m.codmod
                                     inner join db_sysarquivo a
                                     on a.codarq = am.codarq
                              where a.codarq = $valor3  
                              order by codmod");
	    
           if(pg_numrows($tabela) > 0){
                db_fieldsmemory($tabela,0);
           }
          $campo = pg_exec("select 'c01_' || substr(c.nomecam,5,length(c.nomecam)-5) as nomecam,c.conteudo,c.valorinicial,s.nomesequencia,s.codsequencia
                          from db_syscampo c
                          inner join db_sysarqcamp a
                             on a.codcam = c.codcam
			  inner join db_syssequencia s
		  	     on s.codsequencia = a.codsequencia
			     where codarq = ".$valor3.
			  "order by a.seqarq");
	  $Ncampos = pg_numrows($campo);
	  if ($Ncampos > 0) {
             fputs($fd, "\n-- Módulo: ".trim($nomemod)."\n");
             fputs($fd, "CREATE TABLE ".trim($nomearq)."(\n");      
        for($j = 0;$j < $Ncampos;$j++) {
          if($j == $Ncampos - 1) {
            // Chave Primaria
            if(isset($pk)) {
              $pk = pg_exec("select a.nomearq,'c01_' || substr(c.nomecam,5,length(c.nomecam)-5) as nomecam,p.sequen,c.conteudo
                             from db_sysprikey p
                                  inner join db_sysarquivo a on a.codarq = p.codarq
                                  inner join db_syscampo c on c.codcam = p.codcam
                             where a.codarq = ".$valor3);
              if(pg_numrows($pk) > 0) {
                $x = trim(pg_result($campo,$j,"conteudo"));
				if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                  fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):
                       (pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
				}else{
                  fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
                }
                $Npk = pg_numrows($pk);
                $primary_key = "(";
                $nomePK = trim($nomearq)."_";
                for($p = 0;$p < $Npk;$p++) {
                  $cc = split("_",trim(pg_result($pk,$p,"nomecam")));
                  if(strlen(strstr(strtoupper(@$cc[1]),"ANOUSU")) > 0)
                    $nomePK = $nomePK."ae_";
                  else if(strlen(strstr(strtoupper(@$cc[1]),"MESUSU")) > 0)
                    $nomePK = $nomePK."me_";
                  else {
				    $ICC = sizeof($cc) == 1?0:1;
					if(strlen($cc[$ICC]) >=4 )
                      $nomePK = $nomePK.$cc[$ICC][0].$cc[$ICC][1].$cc[$ICC][2].$cc[$ICC][3]."_";
					else
					  $nomePK = $nomePK.$cc[$ICC]."_";
				  }
                  if($p == $Npk - 1)
                    $primary_key = $primary_key.trim(pg_result($pk,$p,"nomecam")).")";
                  else
                    $primary_key = $primary_key.trim(pg_result($pk,$p,"nomecam")).",";
                }
                $nomePK = $nomePK."pk";
               fputs($fd, "CONSTRAINT $nomePK PRIMARY KEY $primary_key);\n\n");
              } else {
                $x = trim(pg_result($campo,$j,"conteudo"));
				if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                  fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");//tres
				}else{
                  fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
                }
              }
            } else {
              $x = trim(pg_result($campo,$j,"conteudo"));
			  if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
               fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
			  }else{
               fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
              }
			}
          }else{
             $x = trim(pg_result($campo,$j,"conteudo"));
			 if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
			 }else{
                fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
             }
          }
	}
      }
    }
  }  
// Foreign Key
if (isset($fk)) {
   fputs ($fd,"\n\n\n-- CHAVE ESTRANGEIRA\n\n\n");
   while (list($campo5,$valor5) = each($FKs)){
      if (substr($campo5,0,3) == "chk"){
          $grupo = pg_exec("select count(referen),referen
                            from db_sysforkey
                            where codarq = $valor5
                            group by referen");
          $nome = pg_exec("select nomearq from db_sysarquivo where codarq = $valor5");
    $Ngrupo = pg_numrows($grupo);
    for($j = 0;$j < $Ngrupo;$j++) {
      $fk = pg_exec("select pai.nomearq as t_pai,'c01_' || substr(c.nomecam,5,length(c.nomecam)-5) as nomecam
                     from db_sysforkey f
                     inner join db_sysarquivo pai
                     on pai.codarq = f.referen
                     inner join db_syscampo c
                     on c.codcam = f.codcam
                     where f.codarq = $valor5
                     and f.referen = ".pg_result($grupo,$j,"referen")."
                     order by f.sequen,f.referen");
      $NomeFK = trim(pg_result($nome,0,"nomearq"))."_";
      $_f_ = pg_numrows($fk);
      $CampFK = "(";
      for($f = 0;$f < $_f_;$f++) {
        if($f == $_f_ - 1)
          $CampFK = $CampFK.trim(pg_result($fk,$f,"nomecam")).")";
	   else
          $CampFK = $CampFK.trim(pg_result($fk,$f,"nomecam")).",";
        $cc = split("_",trim(pg_result($fk,$f,"nomecam")));
        if(strlen(strstr(strtoupper(@$cc[1]),"ANOUSU")) > 0)
          $NomeFK = $NomeFK."ae_";
        else if(strlen(strstr(strtoupper(@$cc[1]),"MESEXE")) > 0)
          $NomeFK = $NomeFK."me_";
        else {
          $ICC = sizeof($cc) == 1?0:1;
          if(strlen($cc[$ICC]) >=4 )
		    $NomeFK = $NomeFK.$cc[$ICC][0].$cc[$ICC][1].$cc[$ICC][2].$cc[$ICC][3]."_";
          else			
            $NomeFK = $NomeFK.@$cc[$ICC];
		}
        $TabPai = trim(pg_result($fk,$f,"t_pai"));
      }
      $NomeFK = $NomeFK."fk";
      fputs($fd, "ALTER TABLE ".trim(pg_result($nome,0,"nomearq"))."\n");
      fputs($fd, "ADD CONSTRAINT $NomeFK FOREIGN KEY $CampFK\n");
      fputs($fd, "REFERENCES $TabPai;\n\n");
    }
   }
 }
}
// Indices
if (isset($indice)) {
   fputs ($fd,"\n\n\n-- INDICES\n\n\n");
   while (list($campo6,$valor6) = each($IND)){
      if (substr($campo6,0,3) == "chk"){
         $ind = pg_exec("select i.codind,i.nomeind,i.campounico
                         from db_sysindices i
                              inner join db_sysarquivo a
                               on a.codarq = i.codarq
                         where a.codarq = $valor6");
          $nome = pg_exec("select nomearq from db_sysarquivo where codarq = $valor6");
    if ($Ni = pg_numrows($ind) > 0) {
        for ($j = 0;$j < $Ni;$j++) {
        $Ncam = pg_exec("select 'c01_' || substr(c.nomecam,5,length(c.nomecam)-5) as nomecam
                         from db_syscampo c
                         inner join db_syscadind ci
                         on ci.codcam = c.codcam
                         inner join db_sysindices i
                         on i.codind = ci.codind
                         where i.codind = ".pg_result($ind,$j,"codind"));
        $campos = "(";
        for($n = 0;$n < pg_numrows($Ncam);$n++) {
          if($n == pg_numrows($Ncam) - 1)
            $campos = $campos.trim(pg_result($Ncam,$n,"nomecam"));
          else
            $campos = $campos.trim(pg_result($Ncam,$n,"nomecam")).",";
        }
        $campos = $campos.");";
        fputs($fd,"CREATE ".( pg_result($ind,$j,"campounico")=="1"?"UNIQUE":"")." INDEX ".trim(pg_result($ind,$j,"nomeind"))." ON ".trim(pg_result($nome,0,"nomearq")).$campos."\n\n");
     } 
     }
    }
  }
}

//Funções
if(isset($funcoes)) {
  fputs($fd,"\n\n\n-- FUNÇÕES\n\n\n");
  $result = pg_exec("select triggerfuncao,nomefuncao,corpofuncao from db_sysfuncoes where triggerfuncao = '0'");
  $numrows = pg_numrows($result);
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,"DROP FUNCTION ".trim(pg_result($result,$i,"nomefuncao")).";\n");	
  }  
  fputs($fd,"\n\n\n");  
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,pg_result($result,$i,"corpofuncao").";\n\n\n");
  }
}
//Views
if(isset($views)) {
  fputs($fd,"\n\n\n-- VISÕES\n\n\n");
  $result = pg_exec("select triggerfuncao,nomefuncao,corpofuncao from db_sysfuncoes where triggerfuncao = '2'");
  $numrows = pg_numrows($result);
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,"DROP VIEW ".trim(pg_result($result,$i,"nomefuncao")).";\n");
  }  
  fputs($fd,"\n\n\n");  
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,pg_result($result,$i,"corpofuncao").";\n\n\n");
  }
}

//triggers
if(isset($triggers)) {
  while (list($campo7,$valor7) = each($IND)){
     if (substr($campo7,0,3) == "chk"){
         $meta = "where a.codarq=$valor7";
     }else{
         $meta = "";
          $RecordsetTabMod  = pg_exec("select a.codarq,a.nomearq,m.codmod,m.nomemod
                                       from   db_sysmodulo m
                                              inner join db_sysarqmod am
                                              on am.codmod = m.codmod
                                              inner join db_sysarquivo a
                                              on a.codarq = am.codarq".
                                              $meta."
                                       order by codmod");
 
           fputs($fd,"\n\n\n-- TRIGGERS\n\n\n");
           $numrows = pg_numrows($RecordsetTabMod);
           $c = "";
           $str = "";
           for ($i = 0;$i < $numrows;$i++) {
              $str .= $c.pg_result($RecordsetTabMod,$i,"codarq");
	      $c = ",";
           }
  	   $result = pg_exec("select f.corpofuncao,f.nomefuncao,t.nometrigger,t.quandotrigger,t.eventotrigger,tab.nomearq
           		      from   db_sysfuncoes f
				     inner join db_systriggers t
				     on t.codfuncao = f.codfuncao
				     inner join db_sysarquivo tab
				     on tab.codarq = t.codarq
		               where triggerfuncao = '1'
			       and t.codarq in(".$str.")");
           $numrows = pg_numrows($result);
           //drop trigger
           for ($i = 0;$i < $numrows;$i++) {
              fputs($fd,"DROP TRIGGER ".trim(pg_result($result,$i,"nometrigger"))." ON ".trim(pg_result($result,$i,"nomearq")).";\n");
           }
           //drop functions
           fputs($fd,"\n\n\n");  
           for ($i = 0;$i < $numrows;$i++) {
              fputs($fd,"DROP FUNCTION ".trim(pg_result($result,$i,"nomefuncao")).";\n");
           }
          //cria as funções das triggers
          fputs($fd,"\n\n\n");  
          for ($i = 0;$i < $numrows;$i++) {
             fputs ($fd,pg_result($result,$i,"corpofuncao").";\n\n\n");
          }
          //cria as triggers
         for ($i = 0;$i < $numrows;$i++) {
           fputs($fd,"CREATE TRIGGER ".trim(pg_result($result,$i,"nometrigger"))."\n");
           fputs ($fd,pg_result($result,$i,"quandotrigger")." ".pg_result($result,$i,"eventotrigger").
                 " FOR EACH ROW EXECUTE PROCEDURE ".trim(pg_result($result,$i,"nomefuncao")).";\n\n");
         }
     }
  }
}
fclose($fd);
//system("mv ".$root."/".$arquivo." ".$root."/".$arquivo."z");
//shell_exec("bzip2 ".$root."/".$arquivo);
echo "<center>";
echo "<h3>Estrutura Criada. Arquivo : $arquivo</h3>";
echo "<a href=\"sys4_criaestrutura_nova.php\">Retorna</a>\n";
echo "</center>";
$salvando = 1;
}
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
     <script>
     <?
     if(!isset($salvando)){
     ?>
      arquivo = '';
      veio = '';
      for(i =0;i < document.estrut.elements.length;i++){
        if(document.estrut.elements[i].type == 'checkbox'){
          if(i > 7){
	    document.estrut.elements[i].checked = false;
	  }
	}
      }
      document.estrut.nome_arq.value = '';
      <?
      }
      ?>
      function js_preenchenome(obj,obj1){
	if(obj1.checked == true){
	  if(arquivo.search(obj) == -1){
	    if(arquivo != ''){
	      arquivo += '_' + obj;
	    }else{
	      arquivo = obj;
	    }
	  }
	}else{
	  if(arquivo.search(obj) > -1 && obj1.checked == true){
	     arquivo = arquivo.replace('_' + obj,'');
	  }
	}
	document.estrut.nome_arq.value = arquivo + '.sql';
      }
     </script>