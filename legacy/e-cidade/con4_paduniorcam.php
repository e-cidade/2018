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

class uniorcam {
    var $arq=null;

  function uniorcam($header){

     umask(74);
     $this->arq = fopen("tmp/UNIORCAM.TXT",'w+');
     
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");  

  }  
  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {
    global $contador,$nomeinst;
    
    ///// abre arquivo dos exercícios anteriores
    $exercicios = "";
    $virg = "";

    $clarqpad = new cl_conarquivospad;
    
    $res =$clarqpad->sql_record(
               $clarqpad->sql_query(null,"*",null," c54_nomearq = 'UNIORCAM.TXT' and c54_anousu=".db_getsession("DB_anousu")."  and c54_codtrib = $tribinst "));
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

  $anousu = db_getsession("DB_anousu");
  $it = $instit;
  $instit = " o58_instit in ($instit)";

  if (!empty($exercicios)) {
	  $sql_not = "  and not o58_anousu in ($exercicios) ";
  }

  $sql_verifica = "select o41_anousu   as anousu_erro,
                   o41_orgao           as orgao_erro,
                   o41_unidade         as unidade_erro,
                   o41_descr           as nome_erro,
                   o41_ident           as identificador_erro,
                   count(distinct cgc) as quant_cnpj_erro,
                   array_accum(distinct o58_instit) as instit_erro,
                   array_accum(distinct o58_coddot || '=' || o58_instit) as dotacoes_erro
            from orcunidade
            inner join orcdotacao on o58_orgao  = o41_orgao and o58_unidade= o41_unidade and o58_anousu = o41_anousu 
            inner join orcorgao   on o41_orgao  = o40_orgao and o41_anousu = o40_anousu and o41_instit in ($it)
            inner join db_config on codigo = o58_instit
            where $instit and o58_anousu <= $anousu $sql_not
            group by
                   o41_anousu,
                   o41_orgao,
                   o41_unidade,
                   o41_descr,
                   o41_ident
            having count(distinct cgc) > 1";
   $rs_verifica = pg_exec($sql_verifica) or die($sql_verifica);
   $rows = pg_numrows($rs_verifica);
   if ( $rows > 0 ) {
     echo "<br><b>PROVAVEIS ERROS NOS REGISTROS DA UNIORCAM:</b><br>";
     for ($x=0;$x < $rows;$x++) {

       $anousu_erro        = pg_result($rs_verifica,$x,"anousu_erro");
       $orgao_erro         = pg_result($rs_verifica,$x,"orgao_erro");
       $unidade_erro       = pg_result($rs_verifica,$x,"unidade_erro");
       $nome_erro          = pg_result($rs_verifica,$x,"nome_erro");
       $identificador_erro = pg_result($rs_verifica,$x,"identificador_erro");
       $quant_cnpj_erro    = pg_result($rs_verifica,$x,"quant_cnpj_erro");
       $instit_erro        = pg_result($rs_verifica,$x,"instit_erro");
       $dotacoes_erro      = pg_result($rs_verifica,$x,"dotacoes_erro");

       echo "ano: $anousu_erro - orgao: $orgao_erro - unidade: $unidade_erro [$nome_erro] - identificador: $identificador_erro - quantidade cnpj: $quant_cnpj_erro - dotacoes: $dotacoes_erro - instituicoes: $instit_erro" . "<br>";
     }
     echo "<br>";
   }

   $sql = "select distinct 
            o41_anousu  as anousu,
            o41_orgao  as orgao,
            o41_unidade as unidade,
            o41_descr as nome,
	          o41_ident as identificador, 
	          cgc  as cnpj 
       from orcunidade
	     inner join orcdotacao on o58_orgao  = o41_orgao and o58_unidade= o41_unidade and o58_anousu = o41_anousu 
	     inner join orcorgao   on o41_orgao  = o40_orgao and o41_anousu = o40_anousu and o40_instit in ($it)
	     inner join db_config on codigo = o58_instit
       where $instit and o58_anousu <= $anousu $sql_not";

  // echo $sql;exit;
   $res=pg_exec($sql);
   $rows = pg_numrows($res);
   for ($x=0;$x < $rows;$x++){
      $anousu         = formatar(pg_result($res,$x,"anousu"),4,'n');
      $orgao          = formatar(pg_result($res,$x,"orgao"),2,'n');
      $unidade        = formatar(pg_result($res,$x,"unidade"),2,'n');
      $nome           = formatar(pg_result($res,$x,"nome"),80,'c');
      $identificador  = formatar(pg_result($res,$x,"identificador"),2,'n');
      $cnpj           = formatar(pg_result($res,$x,"cnpj"),14,'n');
      
   
      //-- 
      $line = $anousu.$orgao.$unidade.$nome.$identificador.$cnpj; 
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


   $teste= "true";
   return $teste;

  }
}  

?>