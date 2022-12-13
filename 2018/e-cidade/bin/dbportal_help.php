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

include("vihelp.conf");

$fd = fopen("/tmp/dbportal_help.html","w");
$br = '<br>';


//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
function db_formatar($str,$tipo,$caracter=" ",$quantidade=0,$TipoDePreenchimento="e",$casasdecimais=2) {
  switch($tipo) {
    case "cpf":
       return substr($str,0,3).".".substr($str,3,3).".".substr($str,6,3)."/".substr($str,9,2);
    case "cnpj":
       return substr($str,0,2).".".substr($str,2,3).".".substr($str,5,3)."/".substr($str,8,4)."-".substr($str,12,2);
	   //90.832.619/0001-55
    case "b":
      // boolean
      if($str==false){
	     return 'N';
      }else{ 
	     return 'S';		
      }
    case "p":
      // ponto decimal com "."
      if($quantidade==0)
         return str_pad(number_format($str,$casasdecimais,".",""),15,"$caracter",STR_PAD_LEFT);
      else 
        return str_pad(number_format($str,$casasdecimais,".",""),$quantidade,"$caracter",STR_PAD_LEFT);
    case "f":
      // ponto decimal com virgula
      if($quantidade==0)
         return str_pad(number_format($str,$casasdecimais,",","."),15,"$caracter",STR_PAD_LEFT);
      else 
        return str_pad(number_format($str,$casasdecimais,",","."),$quantidade,"$caracter",STR_PAD_LEFT);
    case "d":
     
      if($str!=""){
        $data = split("-",$str);
        return $data[2]."/".$data[1]."/".$data[0];
      }else{
        return $str;
      }
    case "s":   	     
	  if($TipoDePreenchimento == "e") {
        return str_pad($str,$quantidade,$caracter,STR_PAD_LEFT);
	  } else if($TipoDePreenchimento == "d") {
        return str_pad($str,$quantidade,$caracter,STR_PAD_RIGHT);
	  } else if($TipoDePreenchimento == "a") {
        return str_pad($str,$quantidade,$caracter,STR_PAD_BOTH);	  
	  }
	case "v":
	  if(strpos($str,",") != "") {
	    $str = str_replace(".","",$str);
	    $str = str_replace(",",".",$str);
		return $str;
	  } else if(strpos($str,"-") != "") {
        $str = split("-",$str);
		return $str[2]."-".$str[1]."-".$str[0];
	  } else if(strpos($str,"/") != "") {
	    $str = split("/",$str);
		return $str[2]."-".$str[1]."-".$str[0];
	  }
	  break;
  }
  return false;
}



$tags = "";
fputs($fd, "<html><head><title>Dbseller -functions </title><head><body>");
fputs($fd, "===== DBSeller Informática Ltda $br");
fputs($fd, "===== Data: 01/03/2003 $br");
fputs($fd, "===== Funcoes php, classes e funcoes javascripts $br");
fputs($fd, "\n");


function gera_help($arquivo){
  global $tags, $fd , $br;

  $dados = file ($arquivo);
  $linhas = sizeof($dados);
  for($i=0;$i<$linhas;$i++){
    $conteudo = trim($dados[$i]);
    if(substr($conteudo,0,8)=="//|00|//"){
      $eclasse = true;
      fputs($fd, "$br");
      fputs($fd, "========= CLASSE PHP =========== $br");
      fputs($fd, "$br");
      fputs($fd,  "Classe         : *".substr($conteudo,8)."* $br");
      $tags .= substr($conteudo,8)."$br db_phphelp.txt \t/*".substr($conteudo,8)."*$br";
      unset($clistafuncao);
      unset($clistasintaxe);
      unset($clistaparametro);
      unset($clistaobservacao);
      unset($clistavariavel);
      unset($clistaretorno);

      unset($listafuncao);
      unset($listasintaxe);
      unset($listaparametro);
      unset($listaobservacao);
      unset($listavariavel);
      unset($listaretorno);
      
    }
    if(substr($conteudo,0,8)=="//|XX|//"){
      unset($eclasse);
    }
     
    if(substr($conteudo,0,8)=="//#00#//" || substr($conteudo,0,8)=="//#01#//"){
      // nome da funcao
      if(!isset($eclasse)){
       
	if(substr($conteudo,0,8)=="//#00#//" ){
	  fputs($fd, "$br");
	  fputs($fd, "===================================== FUNCAO PHP ================================================\n");
	  fputs($fd, "$br");
	  fputs($fd,  "Função         : *".substr($conteudo,8)."* $br");
	}else{
	  fputs($fd, "$br");
	  fputs($fd, "======================================FUNCAO JAVASCRIPT =========================================\n");
	  fputs($fd, "$br");
	  fputs($fd,  "Função JScript : *".substr($conteudo,8)."* $br");
	}
      }else{
	fputs($fd,  "Método         : *".substr($conteudo,8)."* $br");
      }
      $tags .= substr($conteudo,8)."\t db_phphelp.txt \t /*".substr($conteudo,8)."* $br";
      
      unset($listafuncao);
      unset($listasintaxe);
      unset($listaparametro);
      unset($listaobservacao);
      unset($listavariavel);
      unset($listaretorno);
   
      unset($clistafuncao);
      unset($clistasintaxe);
      unset($clistaparametro);
      unset($clistaobservacao);
      unset($clistavariavel);
      unset($clistaretorno);

     
    }
    if(substr($conteudo,0,8)=="//#10#//" || substr($conteudo,0,8)=="//|10|//"){
      // descricao da funcao    
      if(!isset($listafuncao) && !isset($clistafuncao)){
	 $listafuncao = true;
	 $clistafuncao = true;
	 fputs($fd, "$br");
	 fputs($fd,  "Descrição      : ");
      }else
	 fputs($fd,  "                 ");
      if(isset($eclasse))
	 fputs($fd,  "                 ");
      fputs($fd, substr($conteudo,8)." $br");
    }
    if(substr($conteudo,0,8)=="//#15#//" || substr($conteudo,0,8)=="//|15|//"){
      // sintaxe da sintaxe    
      if(!isset($listasintaxe) && !isset($clistasintaxe)){
	 $listasintaxe = true;
	 $clistasintaxe = true;
	 fputs($fd, "$br");
	 fputs($fd,  "Sintaxe        : ");
      }else
	 fputs($fd,  "                 ");
      if(isset($eclasse))
         fputs($fd,  "                 ");
      fputs($fd, substr($conteudo,8)."\n");
    }
    if(substr($conteudo,0,8)=="//#20#//" || substr($conteudo,0,8)=="//|20|//"){
      // Parametros da funcao    
      if(!isset($listaparametro) && !isset($clistaparametro)){
	 $listaparametro = true;
	 $clistaparametro = true;
	 fputs($fd, "$br");
	 fputs($fd,  "Parâmetros     : ");
      }else
	 fputs($fd,  "                 ");
      if(isset($eclasse))
         fputs($fd,  "                 ");
      fputs($fd, substr($conteudo,8)."$br");
    }
    if(substr($conteudo,0,8)=="//#30#//" || substr($conteudo,0,8)=="//|30|//"){
      // variavel da funcao ou propriedade da classe
      if(!isset($listavariavel) && !isset($clistavariavel)){
	 $listavariavel = true;
	 $clistavariavel = true;
	 fputs($fd, "$br");
	 if(isset($eclasse))
	   fputs($fd, "Propriedade    : ");
	 else
	   fputs($fd, "Parâmetros     : ");
      }else
	  fputs($fd,  "                 ");
      if(isset($eclasse))
         fputs($fd,  "                 ");
      fputs($fd, substr($conteudo,8)."$br");
    }
    if(substr($conteudo,0,8)=="//#40#//" || substr($conteudo,0,8)=="//|40|//"){
      // variavel do retorno
      if(!isset($listavariavel) && !isset($clistavariavel)){
	 $listavariavel = true;
	 $clistavariavel = true;
	 fputs($fd, "$br");
         fputs($fd, "Retorno        : ");
      }else
	  fputs($fd,  "                 ");
       if(isset($eclasse))
         fputs($fd,  "                 ");
       fputs($fd, substr($conteudo,8)."$br");
    }



    if(substr($conteudo,0,8)=="//#99#//" || substr($conteudo,0,8)=="//|99|//"){
      // Parametros da funcao    
      if(!isset($listaobservacao) && !isset($listaobservacao)){
	 $listaobservacao = true;
	 $clistaobservacao = true;
	 fputs($fd, "$br");
	 fputs($fd,  "Observação     : ");
      }else
	 fputs($fd,  "                 ");
      if(isset($eclasse))
         fputs($fd,  "                 ");
      fputs($fd, substr($conteudo,8).$br);
    }
  }

}

for($executa=0;$executa<sizeof($matexec);$executa++){
  gera_help($matexec[$executa]);
}




$conn = pg_connect("host=".$host." dbname=".$dbname." user=".$user);

$result = pg_exec("select * from db_syscampo order by nomecam");
fputs($fd, "\n");
fputs($fd, "================================= LISTA DOS CAMPOS CADASTRADOS ==================================\n");
fputs($fd, "\n");
 
for($i=0;$i<pg_numrows($result);$i++){
  $codcam    = pg_result($result,$i,'codcam');
  $nomecam   = pg_result($result,$i,'nomecam');
  $conteudo  = pg_result($result,$i,'conteudo');
  $descricao = pg_result($result,$i,'descricao');
  $rotulo    = pg_result($result,$i,'rotulo');
  $tamanho   = pg_result($result,$i,'tamanho');
  $nulo      = pg_result($result,$i,'nulo');
  $maiusculo = pg_result($result,$i,'maiusculo');
  $autocompl = pg_result($result,$i,'autocompl');
  $aceitatipo= pg_result($result,$i,'aceitatipo');
  $rotulorel = pg_result($result,$i,'rotulorel');
  fputs($fd, "$br");
  fputs($fd, "Campo          : *".trim($nomecam)."* $br");
  $linha = split("$br",$descricao);
  $tags .= trim($nomecam)."\tdb_phphelp.txt\t/*".trim($nomecam)."*$br";
  for($l=0;$l<sizeof($linha);$l++){
    if($l==0)
       fputs($fd, "Descrição      : ");
    else
       fputs($fd, "                 ");
    fputs($fd, $linha[$l]."$br");
  }
  fputs($fd, "Observação     : Código           - ".$codcam."$br");
  fputs($fd, "                 Tipo             - ".trim($conteudo)."$br");
  fputs($fd, "                 Tamanho          - ".trim($tamanho)."$br");
  fputs($fd, "                 Label            - ".trim($rotulo)."$br");
  fputs($fd, "                 Label Relatõrio  - ".trim($rotulorel)."$br");
  fputs($fd, "                 Aceita Nulo      - ".db_formatar($nulo,'b')."$br");
  fputs($fd, "                 Maisculo         - ".db_formatar($maiusculo,'b')."$br");
  fputs($fd, "                 Auto Completar   - ".db_formatar($autocompl,'b')."$br");
  fputs($fd, "                 Java Script      - ".$aceitatipo." |js_ValidaCampos|$br");

  $resultc = pg_exec("select nomearq
                      from db_syscampo c
		           left outer join db_sysarqcamp a on a.codcam = c.codcam
		           left outer join db_sysarquivo q on q.codarq = a.codarq
	              where c.codcam = ".$codcam."
		      order by nomearq");
  $quaisarq = "";
  for($l=0;$l<pg_numrows($resultc);$l++){
    $quaisarq .= "|".trim(pg_result($resultc,$l,"nomearq"))."|  ";
  }
  fputs($fd, "Arquivos       : ".$quaisarq."$br");
  fputs($fd, "$br");
}
fputs($fd, "$br");
fputs($fd, "================================= LISTA DOS ARQUIVOS CADASTRADOS ================================\n");
fputs($fd, "$br");
 

$result = pg_exec("select a.codarq, nomearq, a.descricao, nomecam , p.descricao as descrcampo,conteudo,tamanho
                   from db_sysarquivo a
		        inner join db_sysarqcamp c on c.codarq = a.codarq
			inner join db_syscampo p on p.codcam = c.codcam
		   order by codarq,nomearq,c.seqarq");
$codarqs = 0;
for($i=0;$i<pg_numrows($result);$i++){
  $codarq = pg_result($result,$i,'codarq');
  if($codarqs!=$codarq){
    $nomearq = pg_result($result,$i,'nomearq');
    $descricao = pg_result($result,$i,'descricao');
    $codarqs = $codarq;
    $listaarq = true;
    fputs($fd, "$br");
    fputs($fd, "Arquivo        : *".trim($nomearq)."* *cl".trim($nomearq)."*$br");
    $linha = split("$br",$descricao);
    $tags .= trim($nomearq)."\tdb_phphelp.txt\t/*".trim($nomearq)."*$br";
    $tags .= "cl_".trim($nomearq)."\tdb_phphelp.txt\t/*cl_".trim($nomearq)."*$br";
    $tags .= "cl".trim($nomearq)."\tdb_phphelp.txt\t/*cl".trim($nomearq)."*$br";
    for($l=0;$l<sizeof($linha);$l++){
      if($l==0)
        fputs($fd, "Descrição      : ");
      else
        fputs($fd, "                 ");
      fputs($fd, $linha[$l]."$br");
    }
   
  }else{
    $listaarq = false;
  }
  if($listaarq==true)
    fputs($fd, "Observação     : Campos do arquivo:$br");
  $nomecam   = pg_result($result,$i,'nomecam');
  $descrcampo  = pg_result($result,$i,'descrcampo');
  $conteudo  = pg_result($result,$i,'conteudo');
  $tamanho   = pg_result($result,$i,'tamanho');
  fputs($fd, "                 |".trim($nomecam)."|\t".$conteudo."\t".$tamanho."\t".$descrcampo."$br");
}

fputs($fd, " $br");
fclose($fd);

$fd = fopen('/tmp/tags',"w");

fputs($fd, $tags);

fclose($fd);

?>