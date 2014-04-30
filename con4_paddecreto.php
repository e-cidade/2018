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


class decreto  {
    var $arq=null;


  function decreto($header){
     umask(74);
     $this->arq = fopen("tmp/DECRETO.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");
  }  

function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {
      global $contador,$nomeinst;

     $contador=0;

     $sele = "($instit) ";

     // layout
     // [20] Lei -> lei do orçamento que autoriza a suplementação
     // [08] Data da Lei
     // [20] Numero do decreto, digitado pelo usuário, quando em branco, usamos o numero do projeto
     // [08] Data do decreto, informada pelo usuário, quando em branco, usamos a data de processamento
     // [13] Valor Credito
     // [13] Valor Redução
     // [01] Tipo do Credito ( suplementar, especial, etc, )
     // [01] Origem do Recurso 
     $sql = "
	select *,
	      /*
	        // indica true|false quando a suplementação         
	        // ocorre entre instituicoes distintas
	      */
	      case when ( 
	             select count(distinct(o58_instit))
	             from orcsuplemval s
	                   inner join orcdotacao on o58_coddot=s.o47_coddot and o58_anousu=s.o47_anousu
	             where s.o47_codsup = x.codsup
	             group by o47_codsup                                                 
	           ) = 1 
	      then false 
	      else true end       
	      as interinstit
	from (
	  
	  select 
	      /* numero e data da lei */
	      o45_numlei as num_lei,
	      o45_dataini as data_lei,
	  
	      /* numero e data do decreto */ 
	      o39_numero as num_decreto,    
	      o39_data as data_decreto,
	
	      o46_codsup as codsup,
	      o46_tiposup as tipo_credito,
	
	      round(sum(case when o47_valor > 0 then o47_valor else 0 end),2) as valor_credito,
	      round(sum(case when o47_valor < 0 then abs(o47_valor) else 0 end),2) as valor_reducao

	  from orcprojeto 
	      inner join orclei on o45_codlei = o39_codlei
	      /* pega as suplementacoes */
	      inner join orcsuplem as suplem on o46_codlei=orcprojeto.o39_codproj
	
	      /* carrega valores suplementares  */
	      /* valores negativos sao reducoes e positivos sao suplementacoes */
	      inner join orcsuplemval on o47_codsup = o46_codsup
	
	      /* junta suplementacoes de dotacoes somente da insituticao */
	      inner join orcdotacao d on o58_coddot=o47_coddot and o58_anousu=".db_getsession("DB_anousu")." and o58_instit in $sele                                            
	
	      /* seleciona somente suplementacoes processadas */
	      inner join orcsuplemlan on orcsuplemlan.o49_codsup= o46_codsup
	      
	      left join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj
	
	 where o39_anousu=".db_getsession("DB_anousu")."
	       and o49_data between '$data_ini' and '$data_fim'
	 
	 group by o45_numlei,o45_dataini,o39_numero,o39_data,o46_codsup,o46_tiposup
	 
	 order by o45_numlei,o45_dataini,o39_numero,o39_data
	
	) as x
        ";

     $res=pg_exec($sql);
     for ($x=0;$x < pg_numrows($res);$x++){
        
       $lei          = formatar(pg_result($res,$x,"num_lei"),20,'c');
       $data         = formatar(pg_result($res,$x,"data_lei"),8,'d');
       $decreto      = formatar(pg_result($res,$x,"num_decreto"),20,'c');       
       $data_decreto = formatar(pg_result($res,$x,"data_decreto"),8,'d');       

       $valor_credito  = formatar(pg_result($res,$x,"valor_credito"),13,'v');
       $valor_reducao  = formatar(pg_result($res,$x,"valor_reducao"),13,'v');

       $tipocredito    = pg_result($res,$x,"tipo_credito");

       $interinstit    = pg_result($res,$x,"interinstit");

       if($tipocredito=='1001'){
	 $tip = '1';
	 $ori = '5';
       }else if($tipocredito == '1002'){
	 $tip = '1';
	 $ori = '3';
        }else if($tipocredito == '1003'){
	 $tip = '1';
	 $ori = '1';
         }else if($tipocredito == '1004'){
	 $tip = '1';
	 $ori = '2';
        }else if($tipocredito == '1005'){
	 $tip = '1';
	 $ori = '4';
        }else if($tipocredito == '1006'){
	 $tip = '2';
	 $ori = '5';
        }else if($tipocredito == '1007'){
	 $tip = '2';
	 $ori = '3';
        }else if($tipocredito == '1008'){
	 $tip = '2';
	 $ori = '1';
        }else if($tipocredito == '1009'){
	 $tip = '2';
	 $ori = '2';
        }else if($tipocredito == '1010'){
	 $tip = '2';
	 $ori = '4';
        }else if($tipocredito == '1011'){
	 $tip = '3';
	 $ori = '1';
        }else if($tipocredito == '1012'){
	 $tip = '2';
	 $ori = '1';
        }else if($tipocredito == '1013'){
	 $tip = '2';
	 $ori = '3';
        }else if($tipocredito == '1014'){
	 $tip = '1';
	 $ori = '5';
        }else if($tipocredito = '1015'){
	 $tip = '1';
	 $ori = '5';
        }else if($tipocredito == '1016'){
	 $tip = '1';
	 $ori = '5';
        } 
       $tipo_credito   = formatar($tip,1,'n');

       if ($interinstit=='t')
	     $origem_recurso = formatar(6,1,'n');  // origem = 6 , suplementações/reduções entre instituições
       else 
             $origem_recurso = formatar($ori,1,'n');
     

     
       //-- 
       $line = $lei.$data.$decreto.$data_decreto.$valor_credito.$valor_reducao.$tipo_credito.$origem_recurso; 
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