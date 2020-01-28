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

class credor {

  var $arq=null;
  function credor($header){
    //
     umask(74);
     $this->arq = fopen("tmp/CREDOR.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");  

  }  

function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {
        global $contador,$nomeinst;
 

      $contador=0;


//  body

$sql = "select
            z01_numcgm as codigo,
	    z01_nome   as nome,
	    z01_cgccpf as cnpj,
	    z01_cgccpf as cgc,
	    '' as iss,
	    z01_ender as endereco,
	    z01_munic as cidade,
	    z01_uf as uf,
	    z01_cepcon as cep,
	    z01_telcon as fone,
	    z01_telcon as fax,
	    '1' as tipo	
	from cgm
	     inner join empempenho on e60_numcgm = z01_numcgm
	group by z01_numcgm,z01_nome,z01_cgccpf,z01_ender,z01_munic,z01_uf,z01_cepcon,z01_telcon     
       ";

$res=pg_exec($sql);
$rows = pg_numrows($res);
for ($x=0;$x < $rows;$x++){
   
   $codigo  = formatar(pg_result($res,$x,"codigo"),10,'n');
   $nome    = formatar(pg_result($res,$x,"nome"),60,'c');
   $cnpj    = formatar(pg_result($res,$x,"cnpj"),14,'n');
   $cgc     = formatar(pg_result($res,$x,"cgc"),15,'n');
   $iss     = formatar(pg_result($res,$x,"iss"),15,'n');
   $endereco = formatar(pg_result($res,$x,"endereco"),50,'c');
   $cidade  = formatar(pg_result($res,$x,"cidade"),30,'c');
   $uf      = formatar(pg_result($res,$x,"uf"),2,'c');
   $cep     = formatar(str_replace("-","",pg_result($res,$x,"cep")),8,'n');

   $fone    = formatar(str_replace(" ","",pg_result($res,$x,"fone")),15,'n');
   $fone    = formatar(str_replace("(","",$fone),15,'n');
   $fone    = formatar(str_replace(")","",$fone),15,'n');
   $fone    = formatar(str_replace("-","",$fone),15,'n');

   $fax     = formatar(str_replace(" ","",pg_result($res,$x,"fax")),15,'n');
   $fax     = formatar(str_replace("(","",$fax),15,'n');
   $fax     = formatar(str_replace(")","",$fax),15,'n');
   $fax     = formatar(str_replace("-","",$fax),15,'n');

   $tipo    = formatar(pg_result($res,$x,"tipo"),2,'n');
//-- 
  $line = $codigo.$nome.$cnpj.$cgc.$iss.$endereco.$cidade.$uf.$cep.$fone.$fax.$tipo; 
  fputs($this->arq,$line);
  fputs($this->arq,"\r\n");
  
  $contador = $contador+1; // incrementa contador global
  
}



   //  trailer
   $contador = espaco(10-(strlen($contador)),'0').$contador;
   $line = "FINALIZADOR".$contador;
   fputs($this->arq,$line);
   fputs($this->arq,"\r\n");

   fclose($this->arq);


   $teste="true";
   return $teste;
  }
}
?>