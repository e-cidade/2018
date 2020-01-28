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

class subprog {
  var $arq = null;
  function subprog($header){
    //
     umask(74);
     $this->arq = fopen("tmp/SUBPROG.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");

  }  

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {
     global $contador,$nomeinst,$o32_anousu,$o32_subprog,$o32_descr;
 
     $contador=0;
     $anousu = 0;
    ///// abre arquivo dos exerc�cios anteriores
    $exercicios="";
    $clarqpad = new cl_conarquivospad;
    
    $res =$clarqpad->sql_record(
               $clarqpad->sql_query(null,"*",null," c54_nomearq = 'SUBPROG.TXT' and c54_anousu=".db_getsession("DB_anousu")."  and c54_codtrib = $tribinst "));
        
    $virg="";
    if($clarqpad->numrows > 0){
      $rubant = split("\r\n",pg_result($res,0,"c54_arquivo"));
      for($yy=0;$yy<sizeof($rubant);$yy++){
         $contador++;
         $line = $rubant[$yy];
         
         $exercicios .= $virg.substr($rubant[$yy],0,4);
         $anousu = substr($rubant[$yy],0,4)+0;
         $virg = ",";
                  
         fputs($this->arq,$line);
         fputs($this->arq,"\r\n");
      }
    }

    //////

     
     $sql = "select 
                  o32_anousu,
		  o32_subprog,
		  o32_descr 
             from orcsubprogramarp
	     where o32_anousu <= ".db_getsession('DB_anousu')."
	     ";

	 // if (!empty($exercicios))
	 //   $sql .= "  and not o32_anousu in ($exercicios) ";
	 
	 $sql .= "
	     order by o32_anousu
	     ";

     $res = pg_exec($sql);
     if (pg_numrows($res) > 0) {
       for ($x=0;$x < pg_numrows($res);$x++){
           db_fieldsmemory($res,$x);
	   $anousu = formatar($o32_anousu,4,'n');
	   $codigo = formatar($o32_subprog,3,'n');
	   $nome   = formatar($o32_descr,80,'c');

	   //-- 
	   $line = $anousu.$codigo.$nome; 
	   fputs($this->arq,$line);
	   fputs($this->arq,"\r\n");
    
	   $contador = $contador+1; // incrementa contador global
       }
     }

     for ($x=($anousu+1);$x <= db_getsession("DB_anousu");$x++){
	   $anousux = formatar($x,4,'n');
	   $codigo = formatar(0,3,'n');
	   $nome   = formatar('N�o Existe',80,'c');

	   //-- 
	   $line = $anousux.$codigo.$nome; 
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


    $teste = "true";
    return $teste;

  }
}



?>