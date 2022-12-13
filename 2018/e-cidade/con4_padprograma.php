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

class programa {
    var $arq = null;

  function programa($header){
     umask(74);
     $this->arq = fopen("tmp/PROGRAMA.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");  
  }  

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {
      global $contador,$nomeinst;

    $contador=0;
    $exercicios = "";
    $virg = "";

    ///// abre arquivo dos exercícios anteriores

    $clarqpad = new cl_conarquivospad;
    $res =$clarqpad->sql_record(
               $clarqpad->sql_query(null,"*",null," c54_nomearq = 'PROGRAMA.TXT' and c54_anousu=".db_getsession("DB_anousu")."  and c54_codtrib = $tribinst "));
    
    if($clarqpad->numrows > 0){
      $rubant = split("\r\n",pg_result($res,0,"c54_arquivo"));
      for($yy=0;$yy<sizeof($rubant);$yy++){
         $contador++;
         $line = $rubant[$yy];
 
         $exercicios .= $virg.substr($rubant[$yy],0,4);
         $virg = ",";

         fputs($this->arq,$line);
         fputs($this->arq,"\r\n");
      }
    }

    //////
      //$where = " where o54_instit in ($instit)";
/*
      if(db_getsession("DB_instit") != 3){
         $where = " where o54_instit in (1,2)";
      }else{
         $where = " where o54_instit in (3)";
      }
*/					    
       //  body

      $sql = "select distinct
                 o54_anousu as anousu,
	         o54_programa as codigo,
  	         o54_descr as nome
              from orcprograma
	           inner join orcdotacao on o58_programa = o54_programa
		                        and o58_anousu   = o54_anousu
	       and o54_anousu <= " . db_getsession("DB_anousu");
	 if (!empty($exercicios))
	   $sql .= "  and not o54_anousu in ($exercicios) ";

      $res=pg_exec($sql);
      $rows = pg_numrows($res);
      for ($x=0;$x < $rows;$x++){
          $anousu = formatar(pg_result($res,$x,"anousu"),4,'n');
          $codigo = formatar(pg_result($res,$x,"codigo"),4,'n');
          $nome   = formatar(pg_result($res,$x,"nome"),80,'c');

          //-- 
          $line = $anousu.$codigo.$nome; 
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