<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


//include("classes/db_conarquivospad_classe.php");

class rubrica {

  var $arq=null;

  function rubrica($header){
     umask(74);
     $this->arq = fopen("tmp/RUBRICA.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");  

  }  

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {
      global $nomeinst,$o58_anousu,$o56_elemento,$o56_descr,$nivel,$tipo,$elemento,$ano;
      $contador=0;

      ///// abre arquivo dos exercícios anteriores
      $exercicios="";
      $virg="";
      $clarqpad = new cl_conarquivospad;

      $res =$clarqpad->sql_record(
            $clarqpad->sql_query(null,"*",null," c54_nomearq = 'RUBRICA.TXT' and c54_anousu=".db_getsession("DB_anousu")."  and c54_codtrib = $tribinst "));
    
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
      $sql = "select distinct on (o56_anousu,elemento) elemento,
	                   o56_anousu as ano,
                     tipo,
                     o56_descr,
	                   nivel
                from (
                  select o56_anousu,
                           
                         case when o56_anousu >= 2005 then
                           substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15)
                         else
                           substr(trim(o56_elemento)||'000000000',1,15)::varchar(15)
                         end as elemento,

                         case when o56_anousu >= 2005 then
                           case when c61_anousu is null then
                             'S' else 'A'
                           end
                         else
                           case when o58_anousu is null then
                             'S' else 'A'
                           end
                         end as tipo,

                         o56_descr,

                         case when o56_anousu >= 2005 then
                           fc_nivel_plano2005(substr(o56_elemento,2,12)::varchar(15)||'000')
                         else
                           fc_nivel_plano2005(substr(trim(o56_elemento)||'000000000',1,15)::varchar(15))
                         end as nivel
                    from orcelemento
                         left join conplanoreduz  on o56_codele = c61_codcon
                                                 and o56_anousu = c61_anousu
                         left join orcdotacao     on o58_anousu = o56_anousu
                                                 and o58_codele = o56_codele
                   where o56_anousu <=".db_getsession("DB_anousu")."						 
                order by o56_anousu,elemento
	            ) as x";      
	   
         // echo $sql;exit;
       $result = pg_exec(analiseQueryPlanoOrcamento($sql));
         
       for($i=0;$i<pg_numrows($result);$i++){
            db_fieldsmemory($result,$i);

	    
  	        if (($elemento+0) == 0) 
                continue;
      
//            if(substr($elemento,0,1) == '4')
//              continue;
      
      
            $contador ++;
      
            $line  = formatar($ano,4,'n');
            if ($line ==2005){
               $elemento = $elemento."0"; 
            }
            $line .= formatar($elemento,15,'c');
            $line .= formatar($o56_descr,110,'c');
         
  	    if($ano<2005 && substr($elemento,1,14)=='00000000000000'){
                $line .= 'S';
                $line .= formatar(1,2,'n');
  	    }else{
                // ajusta tipo S01 - sintetico nivel 01 
                //if (substr($elemento,1,14)=='00000000000000'){
                   // echo "<br> acessou ";
                //   $line .= 'S';
                //   $line .= '01';                 
                //} else {              
                   $line .= $tipo;
                   $line .= formatar($nivel,2,'n');
                //}   
            }
            fputs($this->arq,$line);
            fputs($this->arq,"\r\n");
            
      } // end loop
     
      $contador = espaco(10-(strlen($contador)),'0').$contador;
      $line = "FINALIZADOR".$contador;
      fputs($this->arq,$line);
      fputs($this->arq,"\r\n");

      fclose($this->arq);

      pg_exec("commit");


      $teste = "true";
      return $teste ;

 }
}



?>