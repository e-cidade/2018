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

class rd_extra {
    var $arq=null;

  function rd_extra($header){
     umask(74);
     $this->arq = fopen("tmp/RD_EXTRA.TXT",'w+');   
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");

  }  

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {
     global $contador,$nomeinst; 
     global $instituicoes,$c61_instit,$c61_reduz,$nivel,$estrutural,$saldo_anterior,$saldo_anterior_debito,$saldo_anterior_credito,$saldo_final,$c60_descr;

     $perini = $data_ini;
     $perfin = $data_fim;

     if  (substr($perfin,5,2) == '12'){
     
     $where = " c61_instit in ($instit) and c60_codsis=7";
     $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$data_ini,$data_fim,false,$where,'',false,'true');

     $contador=0;


     // este arquivo gera somente no sexto bimestre
       for($x = 0; $x < pg_numrows($result);$x++){
          db_fieldsmemory($result,$x);

          // pegar somente as analiticas
	  if ($c61_reduz==""||$c61_reduz==0) continue;
	  
          $contador ++;
  
          $line  = formatar($estrutural,20,'n');       
          if($c61_instit == 0 || empty($c61_instit))
            $line .= "0000";
          else
            $line .= $instituicoes[$c61_instit];    // aqui é o codtrib, da tabela db_config
 
          // valor,13:[25-37] 
          $line .= formatar(dbround_php_52($saldo_final,2),13,'v');

          // identificador 'D'-despesa-extra ou 'R'-receita-extra 
          // consistema=7, D-xxxx, R-1xxxx
          if (substr($estrutural,0,1)==1) 
	     $line.='R';
	  else  
             $line.='D';

          
          // classificação [02posições: 39-40]
          // 01-RP,02-serv da dívida,03-Depósitos,04-convenios,05-debitos caixa,06-sentenças judiciais,07-outras operações
	  $line .='07';

	  
 
          fputs($this->arq,$line);
          fputs($this->arq,"\r\n");
   
       }
     }
     //  trailer
     $contador = espaco(10-(strlen($contador)),'0').$contador;
     $line = "FINALIZADOR".$contador;
     fputs($this->arq,$line);
     fputs($this->arq,"\r\n");

     fclose($this->arq);

     @pg_exec("drop table if exists work_pl");

     $teste = "true";
     return $teste ;

 }
}


?>