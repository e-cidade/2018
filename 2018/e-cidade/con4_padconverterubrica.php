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


  /*   umask(74);
     $this->arq = fopen("tmp/RUBRICA.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");  

  } */ 

  function processa($instit=1,$data_ini="",$data_fim="") {
    global $nomeinst,$o58_anousu,$o56_elemento,$o56_descr,$nivel,$tipo;
    global $contador;
    $contador=0;

    $sql = "select distinct o58_anousu
            from orcelemento
	        left outer join orcdotacao on o56_codele = o58_codele  and o56_anousu = o58_anousu
            where o58_anousu is not null
   	   ";
	
    $result = pg_exec($sql);

    $sql = "";
    $union = " ";
    for($i=0;$i<pg_numrows($result);$i++){
       $ano = pg_result($result,$i,'o58_anousu');
       $sql .= $union;
       $sql .= " select distinct $ano as o58_anousu,'00'||o56_elemento as o56_elemento,o56_descr,case when o58_coddot > 0 then 'A' else 'S' end as tipo,fc_nivel_plano2005(o56_elemento||'00') as nivel
                  from orcelemento
                      left outer join orcdotacao on o58_anousu = o56_anousu and o56_codele = o58_codele
		    where o56_anousu = $ano and o56_elemento like '3%'  
		      ";
		      
       $union = " union ";
    }	

    $sql .= "  order by o58_anousu,o56_elemento ";
    $result = pg_exec($sql);
    //db_criatabela($result);

    for($i=0;$i<pg_numrows($result);$i++){
       db_fieldsmemory($result,$i);
       $contador ++;
  
       $line  = formatar($o58_anousu,4,'n');
       if ($o56_elemento[0]=="4")
	  $o56_elemento[0]="3";
       $line .= formatar($o56_elemento,15,'c');
       $line .= formatar($o56_descr,110,'c');
       $line .= $tipo;
       $line .= formatar($nivel,2,'n');
  
       fputs($this->arq,$line);
       fputs($this->arq,"\r\n");
  
    }
    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    pg_exec("commit");


    $teste = "true";
    return $teste ;

 }



?>