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

//MODULO: configuracoes
//CLASSE DA ENTIDADE cadenderpaissistema
class cl_cadenderpaissistema { 
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
   var $db135_sequencial = 0; 
   var $db135_db_sistemaexterno = 0; 
   var $db135_db_cadenderpais = 0; 
   var $db135_codigo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db135_sequencial = int4 = Sequencial 
                 db135_db_sistemaexterno = int4 = Código 
                 db135_db_cadenderpais = int4 = Código do País 
                 db135_codigo = varchar(50) = Código do Sistema 
                 ";
   //funcao construtor da classe 
   function cl_cadenderpaissistema() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderpaissistema"); 
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
       $this->db135_sequencial = ($this->db135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db135_sequencial"]:$this->db135_sequencial);
       $this->db135_db_sistemaexterno = ($this->db135_db_sistemaexterno == ""?@$GLOBALS["HTTP_POST_VARS"]["db135_db_sistemaexterno"]:$this->db135_db_sistemaexterno);
       $this->db135_db_cadenderpais = ($this->db135_db_cadenderpais == ""?@$GLOBALS["HTTP_POST_VARS"]["db135_db_cadenderpais"]:$this->db135_db_cadenderpais);
       $this->db135_codigo = ($this->db135_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db135_codigo"]:$this->db135_codigo);
     }else{
       $this->db135_sequencial = ($this->db135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db135_sequencial"]:$this->db135_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db135_sequencial){ 
      $this->atualizacampos();
     if($this->db135_db_sistemaexterno == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "db135_db_sistemaexterno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db135_db_cadenderpais == null ){ 
       $this->erro_sql = " Campo Código do País nao Informado.";
       $this->erro_campo = "db135_db_cadenderpais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db135_codigo == null ){ 
       $this->erro_sql = " Campo Código do Sistema nao Informado.";
       $this->erro_campo = "db135_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db135_sequencial == "" || $db135_sequencial == null ){
       $result = db_query("select nextval('cadenderpaissistema_db135_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderpaissistema_db135_sequencial_seq do campo: db135_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db135_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderpaissistema_db135_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db135_sequencial)){
         $this->erro_sql = " Campo db135_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db135_sequencial = $db135_sequencial; 
       }
     }
     if(($this->db135_sequencial == null) || ($this->db135_sequencial == "") ){ 
       $this->erro_sql = " Campo db135_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderpaissistema(
                                       db135_sequencial 
                                      ,db135_db_sistemaexterno 
                                      ,db135_db_cadenderpais 
                                      ,db135_codigo 
                       )
                values (
                                $this->db135_sequencial 
                               ,$this->db135_db_sistemaexterno 
                               ,$this->db135_db_cadenderpais 
                               ,'$this->db135_codigo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadenderpaissistema ($this->db135_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadenderpaissistema já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadenderpaissistema ($this->db135_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db135_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->db135_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19778,'$this->db135_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3545,19778,'','".AddSlashes(pg_result($resaco,0,'db135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3545,19779,'','".AddSlashes(pg_result($resaco,0,'db135_db_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3545,19780,'','".AddSlashes(pg_result($resaco,0,'db135_db_cadenderpais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3545,19781,'','".AddSlashes(pg_result($resaco,0,'db135_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db135_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderpaissistema set ";
     $virgula = "";
     if(trim($this->db135_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db135_sequencial"])){ 
       $sql  .= $virgula." db135_sequencial = $this->db135_sequencial ";
       $virgula = ",";
       if(trim($this->db135_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db135_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db135_db_sistemaexterno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db135_db_sistemaexterno"])){ 
       $sql  .= $virgula." db135_db_sistemaexterno = $this->db135_db_sistemaexterno ";
       $virgula = ",";
       if(trim($this->db135_db_sistemaexterno) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db135_db_sistemaexterno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db135_db_cadenderpais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db135_db_cadenderpais"])){ 
       $sql  .= $virgula." db135_db_cadenderpais = $this->db135_db_cadenderpais ";
       $virgula = ",";
       if(trim($this->db135_db_cadenderpais) == null ){ 
         $this->erro_sql = " Campo Código do País nao Informado.";
         $this->erro_campo = "db135_db_cadenderpais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db135_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db135_codigo"])){ 
       $sql  .= $virgula." db135_codigo = '$this->db135_codigo' ";
       $virgula = ",";
       if(trim($this->db135_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Sistema nao Informado.";
         $this->erro_campo = "db135_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db135_sequencial!=null){
       $sql .= " db135_sequencial = $this->db135_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->db135_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19778,'$this->db135_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db135_sequencial"]) || $this->db135_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3545,19778,'".AddSlashes(pg_result($resaco,$conresaco,'db135_sequencial'))."','$this->db135_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db135_db_sistemaexterno"]) || $this->db135_db_sistemaexterno != "")
             $resac = db_query("insert into db_acount values($acount,3545,19779,'".AddSlashes(pg_result($resaco,$conresaco,'db135_db_sistemaexterno'))."','$this->db135_db_sistemaexterno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db135_db_cadenderpais"]) || $this->db135_db_cadenderpais != "")
             $resac = db_query("insert into db_acount values($acount,3545,19780,'".AddSlashes(pg_result($resaco,$conresaco,'db135_db_cadenderpais'))."','$this->db135_db_cadenderpais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db135_codigo"]) || $this->db135_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3545,19781,'".AddSlashes(pg_result($resaco,$conresaco,'db135_codigo'))."','$this->db135_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadenderpaissistema nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db135_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadenderpaissistema nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db135_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($db135_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19778,'$db135_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3545,19778,'','".AddSlashes(pg_result($resaco,$iresaco,'db135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3545,19779,'','".AddSlashes(pg_result($resaco,$iresaco,'db135_db_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3545,19780,'','".AddSlashes(pg_result($resaco,$iresaco,'db135_db_cadenderpais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3545,19781,'','".AddSlashes(pg_result($resaco,$iresaco,'db135_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cadenderpaissistema
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db135_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db135_sequencial = $db135_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadenderpaissistema nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db135_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadenderpaissistema nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db135_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderpaissistema";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db135_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderpaissistema ";
     $sql .= "      inner join cadenderpais  on  cadenderpais.db70_sequencial = cadenderpaissistema.db135_db_cadenderpais";
     $sql .= "      inner join db_sistemaexterno  on  db_sistemaexterno.db124_sequencial = cadenderpaissistema.db135_db_sistemaexterno";
     $sql2 = "";
     if($dbwhere==""){
       if($db135_sequencial!=null ){
         $sql2 .= " where cadenderpaissistema.db135_sequencial = $db135_sequencial "; 
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
   function sql_query_file ( $db135_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderpaissistema ";
     $sql2 = "";
     if($dbwhere==""){
       if($db135_sequencial!=null ){
         $sql2 .= " where cadenderpaissistema.db135_sequencial = $db135_sequencial "; 
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
}
?>