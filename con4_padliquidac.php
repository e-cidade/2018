<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

class liquidac {
     var $arq=null;

  function liquidac($header){
     umask(74);
     $this->arq = fopen("tmp/LIQUIDAC.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");    
  }  

function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {
  
      global $contador,$nomeinst;
      $contador = 0;

      $anoexe = db_getsession("DB_anousu");

      $sele = " ( $instit ) ";

      $sql = "
          select 
            e60_anousu  as ano,
	          trim(e60_codemp)::integer   as c75_numemp,
	          c75_codlan,
	          c75_data,
	          round(c70_valor,2)::float8  as c70_valor,
	          case when c53_tipo = 20 
	            then  '+'  
	            else  '-'  
            end as sinal,
            'Liquidação Número :'||c70_codlan as historico,
            c75_codlan as operacao,
            e60_instit
      	  from conlancamemp
            inner join conlancam    on c70_codlan = c75_codlan
            inner join empempenho   on e60_numemp = c75_numemp
            inner join conlancamdoc on c71_codlan = c75_codlan
            inner join conhistdoc   on c53_coddoc = c71_coddoc 
                                   and (c53_tipo = 20 or c53_tipo = 21)	        
        	  where 
        	          (c75_data between '$data_ini' and '$data_fim')
                and e60_emiss  <='$data_fim'
                and e60_anousu  = ".db_getsession("DB_anousu")."
                and e60_instit in $sele
        
          union all 	  
          
          select
            e60_anousu as ano,
            trim(e60_codemp)::integer as c75_numemp,
            0 as c75_codlan,
            e60_emiss as c75_data,
            round((e91_vlrliq-e91_vlrpag),2)::float8 as c70_valor,
            '+' as sinal,
            e60_resumo as historico,
            0 as operacao,
            e60_instit
          from empresto
            inner join empempenho on e60_numemp = e91_numemp
          where     
                    e91_anousu = ".db_getsession("DB_anousu")."
                and e60_instit in $sele
                and e91_rpcorreto is false
          
          union all

          select e60_anousu as ano,
            trim(e60_codemp)::integer as c75_numemp,
            c75_codlan,
            c75_data,
            round(c70_valor,2)::float8 as c70_valor,
            case when c53_tipo = 20 then
               '+'  else '-'  
            end as sinal,
            'Liquidação Número :'||c70_codlan as historico,
            c75_codlan as operacao,
            e60_instit
          from empresto 
    	      inner join conlancamemp  on e91_numemp = c75_numemp
    	      inner join conlancam     on c70_codlan = c75_codlan
    	      inner join empempenho    on e60_numemp = c75_numemp
            inner join conlancamdoc  on c71_codlan = c75_codlan
    	      inner join conhistdoc    on c53_coddoc = c71_coddoc 
                                    and (c53_tipo = 20 or c53_tipo = 21)	        
      	  where 1=1 
      	      /* and e91_rpcorreto is true */
    	        and (c75_data between '$data_ini' and '$data_fim')
    	        and e60_emiss <='$data_fim'
    	        and e60_anousu < ".db_getsession("DB_anousu")."
    	        and e60_instit in $sele and e91_anousu = ".db_getsession("DB_anousu")."

	        union all
		 
          select 
              e60_anousu as ano,
              trim(e60_codemp)::integer as c75_numemp,
              c75_codlan,
              c75_data,
              round(c70_valor,2)::float8 as c70_valor,
               '-' as sinal,
              'Liquidação Número :'||c70_codlan as historico,
              c75_codlan as operacao,
              e60_instit
          from conlancamemp
              inner join conlancam     on c70_codlan = c75_codlan
              inner join empempenho    on e60_numemp = c75_numemp
              inner join conlancamdoc  on c71_codlan = c75_codlan
          where 
                  c75_data between '$data_ini' and '$data_fim'
              and c71_coddoc = 31
              and e60_emiss <='$data_fim'
              and e60_anousu < ".db_getsession("DB_anousu")."
              and e60_instit in $sele
          order by ano, c75_numemp 
          
       ";



      $sql2 = "
        select 
          ano,
	        c75_numemp,
	        c75_codlan,
	        c75_data) as c75_data,
		      sinal,
	        sum(c70_valor) as c70_valor,
	        max(historico) as historico,
	        max(operacao) as operacao,
		      e60_instit
    		from ($sql) as x
    		group by ano, c75_numemp, sinal, e60_instit";
    //		 die($sql);

      
      $res  = pg_exec($sql);
      $rows = pg_numrows($res);
      
      for ($x = 0; $x < $rows; $x++) {
        
        $ano         = pg_result($res,$x,"ano");
        $instituicao = pg_result($res,$x,"e60_instit");
        $empenho     = $ano."0".$instituicao."0".formatar(pg_result($res,$x,"c75_numemp"),6,'n');
        $liquidacao  = formatar(pg_result($res,$x,"c75_codlan"),20,'n');
        $data        = formatar(pg_result($res,$x,"c75_data"),8,'d');
        $valor       = formatar(pg_result($res,$x,"c70_valor"),13,'v');
        $sinal       = pg_result($res,$x,"sinal"); 
        $sObsoleto   = str_pad(" ", 165, " ", STR_PAD_RIGHT);
        $hist        = pg_result($res,$x,"historico");
        
        if ($hist =="") $hist = "sem resumo";
        
        $hist  = addcslashes($hist,"\n\r"); 
        $historico = formatar($hist,400,'c');
        $operacao = formatar(pg_result($res,$x,"operacao"),30,'c');
          
        if ($valor == 0) {  
          continue;
        }
         //-- 
        $line = $empenho.$liquidacao.$data.$valor.$sinal.$sObsoleto.$operacao.$historico; 
        
        fputs($this->arq,$line);
        fputs($this->arq,"\r\n");
        $contador = $contador+1; // incrementa contador global
     }
     //trailer
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