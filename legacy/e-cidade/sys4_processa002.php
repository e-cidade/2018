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

function db_fputs($variavel,$conteudo){

  $GLOBALS['fd'] .= $conteudo;

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  marginwidth="0" marginheight="0" bgcolor="#cccccc" valign="top" >

<?

  //cria o arquivo 
  $arquivo = "/tmp/processa002.sql";
  global $fd;
  $fd = "";
  db_fputs($fd,"--DROP TABLE:\n");
          $campos  = pg_exec("select a.codarq,a.nomearq,m.codmod,m.nomemod
                              from   db_sysmodulo m
                                     inner join db_sysarqmod am
                                     on am.codmod = m.codmod
                                     inner join db_sysarquivo a
                                     on a.codarq = am.codarq
                              where a.codarq = $codarq  
                              order by codmod");
	    
           if(pg_numrows($campos) > 0){
               db_fieldsmemory($campos,0);
               @pg_exec("DROP TABLE ".trim($nomearq));      	  
	  }
  //cria drop sequences;
  db_fputs($fd,"--Criando drop sequences\n");
         $seq = pg_exec("select s.nomesequencia,s.incrseq,s.minvalueseq,maxvalueseq,startseq,s.cacheseq
	                  from db_syssequencia s
					  inner join db_sysarqcamp a
					  on a.codsequencia = s.codsequencia
					  where a.codsequencia <> 0
					  and a.codarq = $codarq");
	   if (pg_numrows($seq) > 0){
              $rsseq = pg_fetch_array($seq);
  	      @pg_exec("DROP SEQUENCE ".$rsseq["nomesequencia"]);      	 
           }
  //cria  sequences;
  db_fputs($fd, "\n\n-- Criando  sequences\n");
         $cseq = pg_exec("select s.nomesequencia,s.incrseq,s.minvalueseq,maxvalueseq,startseq,s.cacheseq
	                  from db_syssequencia s
					  inner join db_sysarqcamp a
					  on a.codsequencia = s.codsequencia
					  where a.codsequencia <> 0
					  and a.codarq = $codarq");
	   if (pg_numrows($cseq) > 0){
              $rscseq = pg_fetch_array($cseq);
  	      db_fputs($fd,"CREATE SEQUENCE ".$rsseq["nomesequencia"]."\n");
  	      db_fputs($fd,"INCREMENT ".trim($rscseq["incrseq"])."\n");
  	      db_fputs($fd, "MINVALUE ".trim($rscseq["minvalueseq"])."\n");
  	      db_fputs($fd, "MAXVALUE ".trim($rscseq["maxvalueseq"])."\n");		 
  	      db_fputs($fd, "START ".trim($rscseq["startseq"])."\n");		 		 
  	      db_fputs($fd, "CACHE ".trim($rscseq["cacheseq"]).";\n");
	      db_fputs($fd,"\n\n"); 
           }
   //cria as tabelas e sua estrutura
     db_fputs($fd,"-- TABELAS E ESTRUTURA\n");
          $tabela  = pg_exec("select a.codarq,a.nomearq,m.codmod,m.nomemod
                              from   db_sysmodulo m
                                     inner join db_sysarqmod am
                                     on am.codmod = m.codmod
                                     inner join db_sysarquivo a
                                     on a.codarq = am.codarq
                              where a.codarq = $codarq  
                              order by codmod");
	    
           if(pg_numrows($tabela) > 0){
                db_fieldsmemory($tabela,0);
           }
          $campo = pg_exec("select c.nomecam,c.conteudo,c.valorinicial,s.nomesequencia,s.codsequencia
                          from db_syscampo c
                          inner join db_sysarqcamp a
                             on a.codcam = c.codcam
			  inner join db_syssequencia s
		  	     on s.codsequencia = a.codsequencia
			     where codarq = ".$codarq.
			  "order by a.seqarq");
	  $Ncampos = pg_numrows($campo);
	  if ($Ncampos > 0) {
             db_fputs($fd, "\n-- Módulo: ".trim($nomemod)."\n");
             db_fputs($fd, "CREATE TABLE ".trim($nomearq)."(\n");      
        for($j = 0;$j < $Ncampos;$j++) {
          if($j == $Ncampos - 1) {
            // Chave Primaria
            if(true) {
              $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen,c.conteudo
                             from db_sysprikey p
                                  inner join db_sysarquivo a on a.codarq = p.codarq
                                  inner join db_syscampo c on c.codcam = p.codcam
                             where a.codarq = ".$codarq." order by p.sequen");
              if(pg_numrows($pk) > 0) {
                $x = trim(pg_result($campo,$j,"conteudo"));
				if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                  db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):
                       (pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
				}else{
                  db_fputs($fd,trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
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
               db_fputs($fd, "CONSTRAINT $nomePK PRIMARY KEY $primary_key);\n\n");
              } else {
                $x = trim(pg_result($campo,$j,"conteudo"));
				if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                  db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");//tres
				}else{
                  db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
                }
              }
            } else {
              $x = trim(pg_result($campo,$j,"conteudo"));
			  if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
               db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
			  }else{
               db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).");\n");
              }
			}
          }else{
             $x = trim(pg_result($campo,$j,"conteudo"));
			 if(substr($x,0,3)=="flo" || substr($x,0,3)=="int" || substr($x,0,4)=="date" ){
                db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default ".pg_result($campo,$j,"valorinicial"):(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
			 }else{
                db_fputs($fd, trim(pg_result($campo,$j,"nomecam"))."\t\t".trim(pg_result($campo,$j,"conteudo"))." ".(trim(pg_result($campo,$j,"valorinicial"))!=""?"default '".pg_result($campo,$j,"valorinicial")."'":(pg_result($campo,$j,"codsequencia")!="0"?" default nextval('".pg_result($campo,$j,"nomesequencia")."')":"")).",\n");
             }
          }
	}
      }
// Foreign Key
   db_fputs ($fd,"\n\n\n-- CHAVE ESTRANGEIRA\n\n\n");
          $grupo = pg_exec("select count(referen),referen
                            from db_sysforkey
                            where codarq = $codarq
                            group by referen");
          $nome = pg_exec("select nomearq from db_sysarquivo where codarq = $codarq");
    $Ngrupo = pg_numrows($grupo);
    for($j = 0;$j < $Ngrupo;$j++) {
      $fk = pg_exec("select pai.nomearq as t_pai,c.nomecam
                     from db_sysforkey f
                     inner join db_sysarquivo pai
                     on pai.codarq = f.referen
                     inner join db_syscampo c
                     on c.codcam = f.codcam
                     where f.codarq = $codarq
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
      db_fputs($fd, "ALTER TABLE ".trim(pg_result($nome,0,"nomearq"))."\n");
      db_fputs($fd, "ADD CONSTRAINT $NomeFK FOREIGN KEY $CampFK\n");
      db_fputs($fd, "REFERENCES $TabPai;\n\n");
    }
// Indices
   db_fputs ($fd,"\n\n\n-- INDICES\n\n\n");
         $ind = pg_exec("select i.codind,i.nomeind,i.campounico
                         from db_sysindices i
                              inner join db_sysarquivo a
                               on a.codarq = i.codarq
                         where a.codarq = $codarq");
          $nome = pg_exec("select nomearq from db_sysarquivo where codarq = $codarq");
    if ($Ni = pg_numrows($ind) > 0) {
        for ($j = 0;$j < $Ni;$j++) {
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
        db_fputs($fd,"CREATE ".( pg_result($ind,$j,"campounico")=="1"?"UNIQUE":"")." INDEX ".trim(pg_result($ind,$j,"nomeind"))." ON ".trim(pg_result($nome,0,"nomearq")).$campos."\n\n");
     } 
}


    $result = pg_exec($fd);
    if($result==false){
?>
<center><h3>Erro processamento<br><?=$fd?></h3></center>

<?

    }else{

?>
<table width="100%"><tr><td align="center"><h3>Concluído...</h3></td></tr></table>

<?
    }
?>

</body>
</html>