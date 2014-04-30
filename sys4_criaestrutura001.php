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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submeter(obj) {
  if(document.getElementById("ra2").checked == true && obj.nometab.value == "") {
    var str = prompt("Informe um nome de arquivo pra gerar o dump completo",'dump<?=strtoupper(pg_dbname())?>.sql');  
    if(str == null)
      return false;
    obj.nometab.value = str;
  }
  return true;	
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
<? if(!isset($HTTP_POST_VARS["b_estrut"])) { ?>
  <form method="post" name="estrut" onSubmit="return js_submeter(this)">                
        <table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td><strong> 
              <label for="ra1">Módulo:</label>
              </strong> <input type="radio" class="radio" name="tabmod" id="ra1" value="m" > 
              &nbsp;&nbsp; <strong> 
              <label for="ra2">Tabela:</label>
              </strong> <input type="radio" class="radio" name="tabmod" value="t" id="ra2" checked> 
            </td>
          </tr>
          <tr> 
            <td><input name="nometab" type="text" id="nometab" value="<?echo @$nometabela?>"></td>
          </tr>
          <tr> 
            <td><strong> 
              <label for="indi">Incluir Indices:</label>
              </strong></td>
            <td><input type="checkbox" class="radio" id="indi" name="indice" checked></td>
          </tr>
          <tr> 
            <td><strong> 
              <label for="prim">Incluir PK:</label>
              </strong></td>
            <td><input type="checkbox" class="radio" id="prim" name="pk" checked></td>
          </tr>
          <tr> 
            <td><strong> 
              <label for="fore">Incluir FK:</label>
              </strong></td>
            <td><input type="checkbox" class="radio" id="fore" name="fk" checked></td>
          </tr>
          <tr> 
            <td><strong>Incluir Fun&ccedil;&otilde;es:</strong></td>
            <td><input name="funcoes" type="checkbox" id="funcoes" value="funcoes"></td>
          </tr>
          <tr>
            <td><strong>Incluir Views:</strong></td>
            <td><input name="views" type="checkbox" id="views" value="views"></td>
          </tr>
          <tr> 
            <td><strong>Incluir Triggers:</strong></td>
            <td><input name="triggers" type="checkbox" id="triggers" value="triggers"></td>
          </tr>
          <tr> 
            <td> <input id="id_estrut" type="submit" name="b_estrut" value="Criar Estrutura" class="botao"> 
            </td>
            <td>&nbsp;</td>
          </tr>
        </table>
	</form>
<?
} else {
  db_postmemory($HTTP_POST_VARS);
  /*
  if(isset($sobre))
    $fd = fopen($nome_arq,"w");
  else {
    $fd = fopen($nome_arq,"r+");
    fseek($fd,filesize($nome_arq));
  }
  */
  $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
// Tabelas
 // $nometab = strtolower($nometab);
//  if(trim($nometab) == "" && $tabmod == "t")
  $arquivo = tempnam("tmp",$nometab.".sql");
  if(substr($nometab,(strlen($nometab) - 4)) == ".sql" && $tabmod == "t") { 
    $nometab = "%";
  }   
  if(trim($nometab) == "" && $tabmod == "m") {
    db_msgbox("Voce precisa informar um módulo");
    db_redireciona();
  }  
  $fd = fopen($arquivo,"w");
  if($tabmod == "t")
    if($nometab == "%")
      $qr = " ";
    else
      $qr = "where nomearq = '$nometab'";
  else if($tabmod == "m")
    $qr = "where nomemod = '$nometab'";
  $sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod
                     from db_sysmodulo m
                     inner join db_sysarqmod am
                     on am.codmod = m.codmod
                     inner join db_sysarquivo a
                     on a.codarq = am.codarq
                     ".$qr."
                     order by codmod";
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);
  $RecordsetTabMod = $result;
  if($numrows == 0) {
    if($tabmod == "t")
      db_msgbox("Não foi encontrada nenhuma tabela com o argumento $nometab");
    else if($tabmod == "m")
      db_msgbox("Não foi encontrada nenhum módulo com o nome de $nometab");
    db_redireciona();
  } else {
  //cria drop table
    for($i = 0;$i < $numrows;$i++) {
	  $campo = pg_exec("select c.nomecam
	                    from db_syscampo c
				 inner join db_sysarqcamp ac
				 on ac.codcam = c.codcam 
		            where codarq = ".pg_result($result,$i,"codarq")."
			    order by codsequencia");
	  if(pg_numrows($campo) > 0)
        fputs($fd,"DROP TABLE ".trim(pg_result($result,$i,"nomearq")).";\n");      	  
	}
	//cria drop sequence
    fputs($fd,"\n\n\n"); 		
	for($i = 0;$i < $numrows;$i++) {
	  $seq = pg_exec("select s.nomesequencia,s.incrseq,s.minvalueseq,maxvalueseq,startseq,s.cacheseq
	                  from db_syssequencia s
					  inner join db_sysarqcamp a
					  on a.codsequencia = s.codsequencia
					  where a.codsequencia <> 0
					  and a.codarq = ".pg_result($result,$i,"codarq"));
	   if(pg_numrows($seq) > 0)
  	     fputs($fd,"DROP SEQUENCE ".trim(pg_result($seq,0,"nomesequencia")).";\n");      	 
	}
	//cria sequencias
    fputs($fd,"\n\n\n"); 	
	for($i = 0;$i < $numrows;$i++) {
	  $seq = pg_exec("select s.nomesequencia,s.incrseq,s.minvalueseq,maxvalueseq,startseq,s.cacheseq
	                  from db_syssequencia s
					  inner join db_sysarqcamp a
					  on a.codsequencia = s.codsequencia
					  where a.codsequencia <> 0
					  and a.codarq = ".pg_result($result,$i,"codarq"));
	   if(pg_numrows($seq) > 0) {
  	     fputs($fd,"CREATE SEQUENCE ".trim(pg_result($seq,0,"nomesequencia"))."\n");
  	     fputs($fd,"INCREMENT ".trim(pg_result($seq,0,"incrseq"))."\n");
  	     fputs($fd,"MINVALUE ".trim(pg_result($seq,0,"minvalueseq"))."\n");
  	     fputs($fd,"MAXVALUE ".trim(pg_result($seq,0,"maxvalueseq"))."\n");		 
  	     fputs($fd,"START ".trim(pg_result($seq,0,"startseq"))."\n");		 		 
  	     fputs($fd,"CACHE ".trim(pg_result($seq,0,"cacheseq")).";\n");
	     fputs($fd,"\n\n"); 
	   }	   
	}
    fputs($fd,"\n\n\n"); 
	//cria tabelas
    for($i = 0;$i < $numrows;$i++) {
        $campo = pg_exec("select c.nomecam,c.conteudo,c.valorinicial,s.nomesequencia,s.codsequencia
                          from db_syscampo c
                          inner join db_sysarqcamp a
                             on a.codcam = c.codcam
			  inner join db_syssequencia s
		  	     on s.codsequencia = a.codsequencia
                          where codarq = ".pg_result($result,$i,"codarq").
			  "order by a.seqarq");
	  $Ncampos = pg_numrows($campo);
	  if($Ncampos > 0) {
        fputs($fd,"\n-- Módulo: ".trim(pg_result($result,$i,"nomemod"))."\n");
        fputs($fd,"CREATE TABLE ".trim(pg_result($result,$i,"nomearq"))."(\n");      
        for($j = 0;$j < $Ncampos;$j++) {
          if($j == $Ncampos - 1) {
            // Chave Primaria
            if(isset($pk)) {
              $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen,c.conteudo
                             from db_sysprikey p
                                  inner join db_sysarquivo a on a.codarq = p.codarq
                                  inner join db_syscampo c on c.codcam = p.codcam
                             where a.codarq = ".pg_result($result,$i,"codarq"));
              if(pg_numrows($pk) > 0) {
                $x = trim(pg_result($campo,$j,"conteudo"));
				if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                  fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
				}else{
                  fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
                }
                $Npk = pg_numrows($pk);
                $primary_key = "(";
                $nomePK = trim(pg_result($result,$i,"nomearq"))."_";
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
                fputs($fd,"CONSTRAINT $nomePK PRIMARY KEY $primary_key);\n\n");
              } else {
                $x = trim(pg_result($campo,$j,"conteudo"));
				if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                  fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");//tres
				}else{
                  fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
                }
              }
            } else {
              $x = trim(pg_result($campo,$j,"conteudo"));
			  if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
			  }else{
                fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
              }
			}
          }else{
             $x = trim(pg_result($campo,$j,"conteudo"));
			 if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
			 }else{
                fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
             }
          }
		}
      }
	}
  }  
// Foreign Key
if(isset($fk)) {
  fputs($fd,"\n\n\n-- CHAVE ESTRANGEIRA\n\n\n");
  for($i = 0;$i < $numrows;$i++) {
    $grupo = pg_exec("select count(referen),referen
                      from db_sysforkey
                      where codarq = ".pg_result($result,$i,"codarq")."
                      group by referen");
    $Ngrupo = pg_numrows($grupo);
    for($j = 0;$j < $Ngrupo;$j++) {
      $fk = pg_exec("select pai.nomearq as t_pai,c.nomecam
                     from db_sysforkey f
                     inner join db_sysarquivo pai
                     on pai.codarq = f.referen
                     inner join db_syscampo c
                     on c.codcam = f.codcam
                     where f.codarq = ".pg_result($result,$i,"codarq")."
                     and f.referen = ".pg_result($grupo,$j,"referen")."
                     order by f.sequen,f.referen");
      $NomeFK = trim(pg_result($result,$i,"nomearq"))."_";
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
      fputs($fd,"ALTER TABLE ".trim(pg_result($result,$i,"nomearq"))."\n");
      fputs($fd,"ADD CONSTRAINT $NomeFK FOREIGN KEY $CampFK\n");
      fputs($fd,"REFERENCES $TabPai;\n\n");
    }
  }
}
// Indices
if(isset($indice)) {
  fputs($fd,"\n\n\n-- INDICES\n\n\n");
  for($i = 0;$i < $numrows;$i++) {
    $ind = pg_exec("select i.codind,i.nomeind,i.campounico
                    from db_sysindices i
                    inner join db_sysarquivo a
                    on a.codarq = i.codarq
                    where a.codarq = ".pg_result($result,$i,"codarq"));
    if(($Ni = pg_numrows($ind)) > 0) {
      for($j = 0;$j < $Ni;$j++) {
        $Ncam = pg_exec("select c.nomecam
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
        fputs($fd,"CREATE ".(pg_result($ind,$j,"campounico")=="1"?"UNIQUE":"")." INDEX ".trim(pg_result($ind,$j,"nomeind"))." ON ".trim(pg_result($result,$i,"nomearq")).$campos."\n\n");
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
  fputs($fd,"\n\n\n-- TRIGGERS\n\n\n");
  $numrows = pg_numrows($RecordsetTabMod);
  $c = "";
  $str = "";
  for($i = 0;$i < $numrows;$i++) {
    $str .= $c.pg_result($RecordsetTabMod,$i,"codarq");
	$c = ",";
  }
  $result = pg_exec("select f.corpofuncao,f.nomefuncao,t.nometrigger,t.quandotrigger,t.eventotrigger,tab.nomearq
                     from db_sysfuncoes f
					 inner join db_systriggers t
					 on t.codfuncao = f.codfuncao
					 inner join db_sysarquivo tab
					 on tab.codarq = t.codarq
					 where triggerfuncao = '1'
					 and t.codarq in(".$str.")");
  $numrows = pg_numrows($result);
  //drop trigger
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,"DROP TRIGGER ".trim(pg_result($result,$i,"nometrigger"))." ON ".trim(pg_result($result,$i,"nomearq")).";\n");
  }
  //drop functions
  fputs($fd,"\n\n\n");  
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,"DROP FUNCTION ".trim(pg_result($result,$i,"nomefuncao")).";\n");
  }
  //cria as funções das triggers
  fputs($fd,"\n\n\n");  
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,pg_result($result,$i,"corpofuncao").";\n\n\n");
  }
  //cria as triggers
  for($i = 0;$i < $numrows;$i++) {
    fputs($fd,"CREATE TRIGGER ".trim(pg_result($result,$i,"nometrigger"))."\n");
    fputs($fd,pg_result($result,$i,"quandotrigger")." ".pg_result($result,$i,"eventotrigger")." FOR EACH ROW EXECUTE PROCEDURE ".trim(pg_result($result,$i,"nomefuncao")).";\n\n");
  }
}
fclose($fd);
//system("mv ".$root."/".$arquivo." ".$root."/".$arquivo."z");
//shell_exec("bzip2 ".$root."/".$arquivo);
echo "<h3>Estrutura Criada. Arquivo : $arquivo</h3>";
echo "<a href=\"sys4_criaestrutura001.php\">Retorna</a>\n";
}
?>
	</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>