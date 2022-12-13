<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE cadenderlocal
class cl_cadenderlocal { 
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
   var $db75_sequencial = 0; 
   var $db75_cadenderbairrocadenderrua = 0; 
   var $db75_numero = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db75_sequencial = int4 = Código do Local 
                 db75_cadenderbairrocadenderrua = int4 = Codigo bairro rua 
                 db75_numero = varchar(10) = Número 
                 ";
   //funcao construtor da classe 
   function cl_cadenderlocal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderlocal"); 
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
       $this->db75_sequencial = ($this->db75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db75_sequencial"]:$this->db75_sequencial);
       $this->db75_cadenderbairrocadenderrua = ($this->db75_cadenderbairrocadenderrua == ""?@$GLOBALS["HTTP_POST_VARS"]["db75_cadenderbairrocadenderrua"]:$this->db75_cadenderbairrocadenderrua);
       $this->db75_numero = ($this->db75_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["db75_numero"]:$this->db75_numero);
     }else{
       $this->db75_sequencial = ($this->db75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db75_sequencial"]:$this->db75_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db75_sequencial){ 
      $this->atualizacampos();
     if($this->db75_cadenderbairrocadenderrua == null ){ 
       $this->erro_sql = " Campo Codigo bairro rua nao Informado.";
       $this->erro_campo = "db75_cadenderbairrocadenderrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db75_numero == null ){ 
       $this->db75_numero = "0";
     }
     if($db75_sequencial == "" || $db75_sequencial == null ){
       $result = db_query("select nextval('cadenderlocal_db75_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderlocal_db75_sequencial_seq do campo: db75_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db75_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderlocal_db75_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db75_sequencial)){
         $this->erro_sql = " Campo db75_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db75_sequencial = $db75_sequencial; 
       }
     }
     if(($this->db75_sequencial == null) || ($this->db75_sequencial == "") ){ 
       $this->erro_sql = " Campo db75_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderlocal(
                                       db75_sequencial 
                                      ,db75_cadenderbairrocadenderrua 
                                      ,db75_numero 
                       )
                values (
                                $this->db75_sequencial 
                               ,$this->db75_cadenderbairrocadenderrua 
                               ,'$this->db75_numero' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Locais ($this->db75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Locais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Locais ($this->db75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db75_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db75_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15865,'$this->db75_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2784,15865,'','".AddSlashes(pg_result($resaco,0,'db75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2784,16580,'','".AddSlashes(pg_result($resaco,0,'db75_cadenderbairrocadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2784,15868,'','".AddSlashes(pg_result($resaco,0,'db75_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db75_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderlocal set ";
     $virgula = "";
     if(trim($this->db75_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db75_sequencial"])){ 
       $sql  .= $virgula." db75_sequencial = $this->db75_sequencial ";
       $virgula = ",";
       if(trim($this->db75_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Local nao Informado.";
         $this->erro_campo = "db75_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db75_cadenderbairrocadenderrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db75_cadenderbairrocadenderrua"])){ 
       $sql  .= $virgula." db75_cadenderbairrocadenderrua = $this->db75_cadenderbairrocadenderrua ";
       $virgula = ",";
       if(trim($this->db75_cadenderbairrocadenderrua) == null ){ 
         $this->erro_sql = " Campo Codigo bairro rua nao Informado.";
         $this->erro_campo = "db75_cadenderbairrocadenderrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db75_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db75_numero"])){ 
       $sql  .= $virgula." db75_numero = '$this->db75_numero' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db75_sequencial!=null){
       $sql .= " db75_sequencial = $this->db75_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db75_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15865,'$this->db75_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db75_sequencial"]) || $this->db75_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2784,15865,'".AddSlashes(pg_result($resaco,$conresaco,'db75_sequencial'))."','$this->db75_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db75_cadenderbairrocadenderrua"]) || $this->db75_cadenderbairrocadenderrua != "")
           $resac = db_query("insert into db_acount values($acount,2784,16580,'".AddSlashes(pg_result($resaco,$conresaco,'db75_cadenderbairrocadenderrua'))."','$this->db75_cadenderbairrocadenderrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db75_numero"]) || $this->db75_numero != "")
           $resac = db_query("insert into db_acount values($acount,2784,15868,'".AddSlashes(pg_result($resaco,$conresaco,'db75_numero'))."','$this->db75_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Locais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Locais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db75_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db75_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15865,'$db75_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2784,15865,'','".AddSlashes(pg_result($resaco,$iresaco,'db75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2784,16580,'','".AddSlashes(pg_result($resaco,$iresaco,'db75_cadenderbairrocadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2784,15868,'','".AddSlashes(pg_result($resaco,$iresaco,'db75_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadenderlocal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db75_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db75_sequencial = $db75_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Locais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Locais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db75_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderlocal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderlocal ";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua";
     $sql .= "      inner join cadenderbairro  on  cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro";
     $sql .= "      inner join cadenderrua  on  cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua";
     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial "; 
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
   function sql_query_file ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderlocal ";
     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial "; 
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
   function sql_query_completo ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderlocal                                                                                       ";
     $sql .= "      inner join cadenderbairrocadenderrua on db87_sequencial         = db75_cadenderbairrocadenderrua    ";
     $sql .= "      inner join cadenderbairro            on db73_sequencial         = db87_cadenderbairro               ";
     $sql .= "      inner join cadenderrua               on db74_sequencial         = db87_cadenderrua                  ";
     $sql .= "      inner join cadendermunicipio         on db72_sequencial         = db73_cadendermunicipio            ";
     $sql .= "      inner join cadendermunicipio  as a   on a.db72_sequencial       = db74_cadendermunicipio            ";
     $sql .= "      inner join cadenderestado            on db71_sequencial         = a.db72_cadenderestado             ";
     $sql .= "      inner join cadenderpais              on db70_sequencial         = db71_cadenderpais                 ";
     $sql .= "      inner join endereco                  on db76_cadenderlocal      = db75_sequencial                   ";
     $sql .= "      inner join cadenderruaruastipo       on db85_cadenderrua        = db74_sequencial                   ";
     $sql .= "      inner join ruastipo                  on j88_codigo              = db85_ruastipo                     ";
     $sql .= "      left  join cadenderruacep            on db86_cadenderrua        = db74_sequencial                   ";
     $sql .= "      left  join cadendermunicipiosistema  on db125_cadendermunicipio = cadendermunicipio.db72_sequencial ";
     $sql .= "                                          and db125_db_sistemaexterno = 4                                 ";
     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial ";
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
   function sql_query_local ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderlocal ";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua";
     $sql .= "      inner join cadenderbairro  on  cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro";
     $sql .= "      inner join cadenderrua     on  cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua";
     $sql .= "      inner join endereco        on  endereco.db76_cadenderlocal = cadenderlocal.db75_sequencial";
     $sql .= "      inner join cadenderruaruastipo on cadenderruaruastipo.db85_cadenderrua = cadenderrua.db74_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial "; 
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
  
  function sql_query_cgmendereco ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderlocal ";
     $sql .= "      inner join cadenderbairrocadenderrua  on  
                              cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua";
     $sql .= "      inner join cadenderbairro  on  cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro";
     $sql .= "      inner join cadenderrua     on  cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua";
     $sql .= "      inner join endereco        on  endereco.db76_cadenderlocal = cadenderlocal.db75_sequencial";
     $sql .= "      inner join cadenderruaruastipo on cadenderruaruastipo.db85_cadenderrua = cadenderrua.db74_sequencial ";
     $sql .= "      inner join cgmendereco     on cgmendereco.z07_endereco = endereco.db76_sequencial ";
     $sql .= "      inner join cadendermunicipio on cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio ";
     $sql .= "      inner join cadenderestado on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado ";
     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial "; 
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