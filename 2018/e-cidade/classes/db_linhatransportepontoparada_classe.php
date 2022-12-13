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

//MODULO: transporteescolar
//CLASSE DA ENTIDADE linhatransportepontoparada
class cl_linhatransportepontoparada { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $tre11_sequencial = 0; 
   var $tre11_pontoparada = 0; 
   var $tre11_itinerariologradouro = 0; 
   var $tre11_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tre11_sequencial = int4 = Sequencial 
                 tre11_pontoparada = int4 = Sequencial 
                 tre11_itinerariologradouro = int4 = Sequencial 
                 tre11_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_linhatransportepontoparada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("linhatransportepontoparada"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->tre11_sequencial = ($this->tre11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre11_sequencial"]:$this->tre11_sequencial);
       $this->tre11_pontoparada = ($this->tre11_pontoparada == ""?@$GLOBALS["HTTP_POST_VARS"]["tre11_pontoparada"]:$this->tre11_pontoparada);
       $this->tre11_itinerariologradouro = ($this->tre11_itinerariologradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["tre11_itinerariologradouro"]:$this->tre11_itinerariologradouro);
       $this->tre11_ordem = ($this->tre11_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["tre11_ordem"]:$this->tre11_ordem);
     }else{
       $this->tre11_sequencial = ($this->tre11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre11_sequencial"]:$this->tre11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tre11_sequencial){ 
      $this->atualizacampos();
     if($this->tre11_pontoparada == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "tre11_pontoparada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre11_itinerariologradouro == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "tre11_itinerariologradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre11_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "tre11_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tre11_sequencial == "" || $tre11_sequencial == null ){
       $result = db_query("select nextval('linhatransportepontoparada_tre11_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: linhatransportepontoparada_tre11_sequencial_seq do campo: tre11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tre11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from linhatransportepontoparada_tre11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tre11_sequencial)){
         $this->erro_sql = " Campo tre11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tre11_sequencial = $tre11_sequencial; 
       }
     }
     if(($this->tre11_sequencial == null) || ($this->tre11_sequencial == "") ){ 
       $this->erro_sql = " Campo tre11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into linhatransportepontoparada(
                                       tre11_sequencial 
                                      ,tre11_pontoparada 
                                      ,tre11_itinerariologradouro 
                                      ,tre11_ordem 
                       )
                values (
                                $this->tre11_sequencial 
                               ,$this->tre11_pontoparada 
                               ,$this->tre11_itinerariologradouro 
                               ,$this->tre11_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Linha de Transporte Ponto de Parada ($this->tre11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Linha de Transporte Ponto de Parada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Linha de Transporte Ponto de Parada ($this->tre11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre11_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20111,'$this->tre11_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3608,20111,'','".AddSlashes(pg_result($resaco,0,'tre11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3608,20113,'','".AddSlashes(pg_result($resaco,0,'tre11_pontoparada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3608,20114,'','".AddSlashes(pg_result($resaco,0,'tre11_itinerariologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3608,20115,'','".AddSlashes(pg_result($resaco,0,'tre11_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tre11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update linhatransportepontoparada set ";
     $virgula = "";
     if(trim($this->tre11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre11_sequencial"])){ 
       $sql  .= $virgula." tre11_sequencial = $this->tre11_sequencial ";
       $virgula = ",";
       if(trim($this->tre11_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre11_pontoparada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre11_pontoparada"])){ 
       $sql  .= $virgula." tre11_pontoparada = $this->tre11_pontoparada ";
       $virgula = ",";
       if(trim($this->tre11_pontoparada) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre11_pontoparada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre11_itinerariologradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre11_itinerariologradouro"])){ 
       $sql  .= $virgula." tre11_itinerariologradouro = $this->tre11_itinerariologradouro ";
       $virgula = ",";
       if(trim($this->tre11_itinerariologradouro) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre11_itinerariologradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre11_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre11_ordem"])){ 
       $sql  .= $virgula." tre11_ordem = $this->tre11_ordem ";
       $virgula = ",";
       if(trim($this->tre11_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "tre11_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tre11_sequencial!=null){
       $sql .= " tre11_sequencial = $this->tre11_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre11_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20111,'$this->tre11_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre11_sequencial"]) || $this->tre11_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3608,20111,'".AddSlashes(pg_result($resaco,$conresaco,'tre11_sequencial'))."','$this->tre11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre11_pontoparada"]) || $this->tre11_pontoparada != "")
             $resac = db_query("insert into db_acount values($acount,3608,20113,'".AddSlashes(pg_result($resaco,$conresaco,'tre11_pontoparada'))."','$this->tre11_pontoparada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre11_itinerariologradouro"]) || $this->tre11_itinerariologradouro != "")
             $resac = db_query("insert into db_acount values($acount,3608,20114,'".AddSlashes(pg_result($resaco,$conresaco,'tre11_itinerariologradouro'))."','$this->tre11_itinerariologradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre11_ordem"]) || $this->tre11_ordem != "")
             $resac = db_query("insert into db_acount values($acount,3608,20115,'".AddSlashes(pg_result($resaco,$conresaco,'tre11_ordem'))."','$this->tre11_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linha de Transporte Ponto de Parada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linha de Transporte Ponto de Parada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tre11_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tre11_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20111,'$tre11_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3608,20111,'','".AddSlashes(pg_result($resaco,$iresaco,'tre11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3608,20113,'','".AddSlashes(pg_result($resaco,$iresaco,'tre11_pontoparada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3608,20114,'','".AddSlashes(pg_result($resaco,$iresaco,'tre11_itinerariologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3608,20115,'','".AddSlashes(pg_result($resaco,$iresaco,'tre11_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from linhatransportepontoparada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tre11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tre11_sequencial = $tre11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linha de Transporte Ponto de Parada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tre11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linha de Transporte Ponto de Parada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tre11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tre11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:linhatransportepontoparada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tre11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from linhatransportepontoparada ";
     $sql .= "      inner join pontoparada  on  pontoparada.tre04_sequencial = linhatransportepontoparada.tre11_pontoparada";
     $sql .= "      inner join itinerariologradouro  on  itinerariologradouro.tre10_sequencial = linhatransportepontoparada.tre11_itinerariologradouro";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = pontoparada.tre04_cadenderbairrocadenderrua";
     $sql .= "      inner join cadenderbairrocadenderrua  as a on   a.db87_sequencial = itinerariologradouro.tre10_cadenderbairrocadenderrua";
     $sql .= "      inner join linhatransporteitinerario  on  linhatransporteitinerario.tre09_sequencial = itinerariologradouro.tre10_linhatransporteitinerario";
     $sql2 = "";
     if($dbwhere==""){
       if($tre11_sequencial!=null ){
         $sql2 .= " where linhatransportepontoparada.tre11_sequencial = $tre11_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $tre11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from linhatransportepontoparada ";
     $sql2 = "";
     if($dbwhere==""){
       if($tre11_sequencial!=null ){
         $sql2 .= " where linhatransportepontoparada.tre11_sequencial = $tre11_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  
  function sql_query_pontos_parada ( $tre11_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    
    $sql .= " from linhatransportepontoparada ";
    $sql .= "      inner join pontoparada on pontoparada.tre04_sequencial = linhatransportepontoparada.tre11_pontoparada";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($tre11_sequencial != null ) {
        $sql2 .= " where linhatransportepontoparada.tre11_sequencial = $tre11_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>