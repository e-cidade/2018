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

//MODULO: configuracoes
//CLASSE DA ENTIDADE cadendermunicipiosistema
class cl_cadendermunicipiosistema { 
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
   var $db125_sequencial = 0; 
   var $db125_cadendermunicipio = 0; 
   var $db125_db_sistemaexterno = 0; 
   var $db125_codigosistema = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db125_sequencial = int8 = Código 
                 db125_cadendermunicipio = int4 = Código do Município 
                 db125_db_sistemaexterno = int4 = Código 
                 db125_codigosistema = varchar(50) = Código Sistema Externo 
                 ";
   //funcao construtor da classe 
   function cl_cadendermunicipiosistema() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadendermunicipiosistema"); 
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
       $this->db125_sequencial = ($this->db125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db125_sequencial"]:$this->db125_sequencial);
       $this->db125_cadendermunicipio = ($this->db125_cadendermunicipio == ""?@$GLOBALS["HTTP_POST_VARS"]["db125_cadendermunicipio"]:$this->db125_cadendermunicipio);
       $this->db125_db_sistemaexterno = ($this->db125_db_sistemaexterno == ""?@$GLOBALS["HTTP_POST_VARS"]["db125_db_sistemaexterno"]:$this->db125_db_sistemaexterno);
       $this->db125_codigosistema = ($this->db125_codigosistema == ""?@$GLOBALS["HTTP_POST_VARS"]["db125_codigosistema"]:$this->db125_codigosistema);
     }else{
       $this->db125_sequencial = ($this->db125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db125_sequencial"]:$this->db125_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db125_sequencial){ 
      $this->atualizacampos();
     if($this->db125_cadendermunicipio == null ){ 
       $this->erro_sql = " Campo Código do Município nao Informado.";
       $this->erro_campo = "db125_cadendermunicipio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db125_db_sistemaexterno == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "db125_db_sistemaexterno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db125_codigosistema == null ){ 
       $this->erro_sql = " Campo Código Sistema Externo nao Informado.";
       $this->erro_campo = "db125_codigosistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db125_sequencial == "" || $db125_sequencial == null ){
       $result = db_query("select nextval('cadendermunicipiosistema_db125_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadendermunicipiosistema_db125_sequencial_seq do campo: db125_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db125_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadendermunicipiosistema_db125_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db125_sequencial)){
         $this->erro_sql = " Campo db125_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db125_sequencial = $db125_sequencial; 
       }
     }
     if(($this->db125_sequencial == null) || ($this->db125_sequencial == "") ){ 
       $this->erro_sql = " Campo db125_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadendermunicipiosistema(
                                       db125_sequencial 
                                      ,db125_cadendermunicipio 
                                      ,db125_db_sistemaexterno 
                                      ,db125_codigosistema 
                       )
                values (
                                $this->db125_sequencial 
                               ,$this->db125_cadendermunicipio 
                               ,$this->db125_db_sistemaexterno 
                               ,'$this->db125_codigosistema' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadendermunicipiosistema ($this->db125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadendermunicipiosistema já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadendermunicipiosistema ($this->db125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db125_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db125_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18598,'$this->db125_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3292,18598,'','".AddSlashes(pg_result($resaco,0,'db125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3292,18600,'','".AddSlashes(pg_result($resaco,0,'db125_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3292,18601,'','".AddSlashes(pg_result($resaco,0,'db125_db_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3292,18602,'','".AddSlashes(pg_result($resaco,0,'db125_codigosistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db125_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadendermunicipiosistema set ";
     $virgula = "";
     if(trim($this->db125_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db125_sequencial"])){ 
       $sql  .= $virgula." db125_sequencial = $this->db125_sequencial ";
       $virgula = ",";
       if(trim($this->db125_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db125_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db125_cadendermunicipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db125_cadendermunicipio"])){ 
       $sql  .= $virgula." db125_cadendermunicipio = $this->db125_cadendermunicipio ";
       $virgula = ",";
       if(trim($this->db125_cadendermunicipio) == null ){ 
         $this->erro_sql = " Campo Código do Município nao Informado.";
         $this->erro_campo = "db125_cadendermunicipio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db125_db_sistemaexterno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db125_db_sistemaexterno"])){ 
       $sql  .= $virgula." db125_db_sistemaexterno = $this->db125_db_sistemaexterno ";
       $virgula = ",";
       if(trim($this->db125_db_sistemaexterno) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db125_db_sistemaexterno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db125_codigosistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db125_codigosistema"])){ 
       $sql  .= $virgula." db125_codigosistema = '$this->db125_codigosistema' ";
       $virgula = ",";
       if(trim($this->db125_codigosistema) == null ){ 
         $this->erro_sql = " Campo Código Sistema Externo nao Informado.";
         $this->erro_campo = "db125_codigosistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db125_sequencial!=null){
       $sql .= " db125_sequencial = $this->db125_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db125_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18598,'$this->db125_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db125_sequencial"]) || $this->db125_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3292,18598,'".AddSlashes(pg_result($resaco,$conresaco,'db125_sequencial'))."','$this->db125_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db125_cadendermunicipio"]) || $this->db125_cadendermunicipio != "")
           $resac = db_query("insert into db_acount values($acount,3292,18600,'".AddSlashes(pg_result($resaco,$conresaco,'db125_cadendermunicipio'))."','$this->db125_cadendermunicipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db125_db_sistemaexterno"]) || $this->db125_db_sistemaexterno != "")
           $resac = db_query("insert into db_acount values($acount,3292,18601,'".AddSlashes(pg_result($resaco,$conresaco,'db125_db_sistemaexterno'))."','$this->db125_db_sistemaexterno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db125_codigosistema"]) || $this->db125_codigosistema != "")
           $resac = db_query("insert into db_acount values($acount,3292,18602,'".AddSlashes(pg_result($resaco,$conresaco,'db125_codigosistema'))."','$this->db125_codigosistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadendermunicipiosistema nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadendermunicipiosistema nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db125_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db125_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18598,'$db125_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3292,18598,'','".AddSlashes(pg_result($resaco,$iresaco,'db125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3292,18600,'','".AddSlashes(pg_result($resaco,$iresaco,'db125_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3292,18601,'','".AddSlashes(pg_result($resaco,$iresaco,'db125_db_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3292,18602,'','".AddSlashes(pg_result($resaco,$iresaco,'db125_codigosistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadendermunicipiosistema
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db125_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db125_sequencial = $db125_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadendermunicipiosistema nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadendermunicipiosistema nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db125_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadendermunicipiosistema";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db125_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadendermunicipiosistema ";
     $sql .= "      inner join cadendermunicipio  on  cadendermunicipio.db72_sequencial = cadendermunicipiosistema.db125_cadendermunicipio";
     $sql .= "      inner join db_sistemaexterno  on  db_sistemaexterno.db124_sequencial = cadendermunicipiosistema.db125_db_sistemaexterno";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql2 = "";
     if($dbwhere==""){
       if($db125_sequencial!=null ){
         $sql2 .= " where cadendermunicipiosistema.db125_sequencial = $db125_sequencial "; 
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
   function sql_query_file ( $db125_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadendermunicipiosistema ";
     $sql2 = "";
     if($dbwhere==""){
       if($db125_sequencial!=null ){
         $sql2 .= " where cadendermunicipiosistema.db125_sequencial = $db125_sequencial "; 
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