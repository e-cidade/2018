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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE cadenderparam
class cl_cadenderparam { 
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
   var $db99_sequencial = 0; 
   var $db99_cadenderpais = 0; 
   var $db99_cadenderestado = 0; 
   var $db99_cadendermunicipio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db99_sequencial = int4 = Sequencial 
                 db99_cadenderpais = int4 = Código País 
                 db99_cadenderestado = int4 = Código do Estado 
                 db99_cadendermunicipio = int4 = Código do Municipio 
                 ";
   //funcao construtor da classe 
   function cl_cadenderparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderparam"); 
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
       $this->db99_sequencial = ($this->db99_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db99_sequencial"]:$this->db99_sequencial);
       $this->db99_cadenderpais = ($this->db99_cadenderpais == ""?@$GLOBALS["HTTP_POST_VARS"]["db99_cadenderpais"]:$this->db99_cadenderpais);
       $this->db99_cadenderestado = ($this->db99_cadenderestado == ""?@$GLOBALS["HTTP_POST_VARS"]["db99_cadenderestado"]:$this->db99_cadenderestado);
       $this->db99_cadendermunicipio = ($this->db99_cadendermunicipio == ""?@$GLOBALS["HTTP_POST_VARS"]["db99_cadendermunicipio"]:$this->db99_cadendermunicipio);
     }else{
       $this->db99_sequencial = ($this->db99_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db99_sequencial"]:$this->db99_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db99_sequencial){ 
      $this->atualizacampos();
     if($this->db99_cadenderpais == null ){ 
       $this->erro_sql = " Campo Código País nao Informado.";
       $this->erro_campo = "db99_cadenderpais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db99_cadenderestado == null ){ 
       $this->erro_sql = " Campo Código do Estado nao Informado.";
       $this->erro_campo = "db99_cadenderestado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db99_cadendermunicipio == null ){ 
       $this->erro_sql = " Campo Código do Municipio nao Informado.";
       $this->erro_campo = "db99_cadendermunicipio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db99_sequencial == "" || $db99_sequencial == null ){
       $result = db_query("select nextval('cadenderparam_db99_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderparam_db99_sequencial_seq do campo: db99_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db99_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderparam_db99_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db99_sequencial)){
         $this->erro_sql = " Campo db99_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db99_sequencial = $db99_sequencial; 
       }
     }
     if(($this->db99_sequencial == null) || ($this->db99_sequencial == "") ){ 
       $this->erro_sql = " Campo db99_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderparam(
                                       db99_sequencial 
                                      ,db99_cadenderpais 
                                      ,db99_cadenderestado 
                                      ,db99_cadendermunicipio 
                       )
                values (
                                $this->db99_sequencial 
                               ,$this->db99_cadenderpais 
                               ,$this->db99_cadenderestado 
                               ,$this->db99_cadendermunicipio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pârametros do endereço ($this->db99_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pârametros do endereço já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pârametros do endereço ($this->db99_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db99_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db99_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16698,'$this->db99_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2936,16698,'','".AddSlashes(pg_result($resaco,0,'db99_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2936,16699,'','".AddSlashes(pg_result($resaco,0,'db99_cadenderpais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2936,16700,'','".AddSlashes(pg_result($resaco,0,'db99_cadenderestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2936,16701,'','".AddSlashes(pg_result($resaco,0,'db99_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db99_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderparam set ";
     $virgula = "";
     if(trim($this->db99_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db99_sequencial"])){ 
       $sql  .= $virgula." db99_sequencial = $this->db99_sequencial ";
       $virgula = ",";
       if(trim($this->db99_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db99_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db99_cadenderpais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db99_cadenderpais"])){ 
       $sql  .= $virgula." db99_cadenderpais = $this->db99_cadenderpais ";
       $virgula = ",";
       if(trim($this->db99_cadenderpais) == null ){ 
         $this->erro_sql = " Campo Código País nao Informado.";
         $this->erro_campo = "db99_cadenderpais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db99_cadenderestado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db99_cadenderestado"])){ 
       $sql  .= $virgula." db99_cadenderestado = $this->db99_cadenderestado ";
       $virgula = ",";
       if(trim($this->db99_cadenderestado) == null ){ 
         $this->erro_sql = " Campo Código do Estado nao Informado.";
         $this->erro_campo = "db99_cadenderestado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db99_cadendermunicipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db99_cadendermunicipio"])){ 
       $sql  .= $virgula." db99_cadendermunicipio = $this->db99_cadendermunicipio ";
       $virgula = ",";
       if(trim($this->db99_cadendermunicipio) == null ){ 
         $this->erro_sql = " Campo Código do Municipio nao Informado.";
         $this->erro_campo = "db99_cadendermunicipio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db99_sequencial!=null){
       $sql .= " db99_sequencial = $this->db99_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db99_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16698,'$this->db99_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db99_sequencial"]) || $this->db99_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2936,16698,'".AddSlashes(pg_result($resaco,$conresaco,'db99_sequencial'))."','$this->db99_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db99_cadenderpais"]) || $this->db99_cadenderpais != "")
           $resac = db_query("insert into db_acount values($acount,2936,16699,'".AddSlashes(pg_result($resaco,$conresaco,'db99_cadenderpais'))."','$this->db99_cadenderpais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db99_cadenderestado"]) || $this->db99_cadenderestado != "")
           $resac = db_query("insert into db_acount values($acount,2936,16700,'".AddSlashes(pg_result($resaco,$conresaco,'db99_cadenderestado'))."','$this->db99_cadenderestado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db99_cadendermunicipio"]) || $this->db99_cadendermunicipio != "")
           $resac = db_query("insert into db_acount values($acount,2936,16701,'".AddSlashes(pg_result($resaco,$conresaco,'db99_cadendermunicipio'))."','$this->db99_cadendermunicipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pârametros do endereço nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db99_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pârametros do endereço nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db99_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db99_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db99_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db99_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16698,'$db99_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2936,16698,'','".AddSlashes(pg_result($resaco,$iresaco,'db99_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2936,16699,'','".AddSlashes(pg_result($resaco,$iresaco,'db99_cadenderpais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2936,16700,'','".AddSlashes(pg_result($resaco,$iresaco,'db99_cadenderestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2936,16701,'','".AddSlashes(pg_result($resaco,$iresaco,'db99_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadenderparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db99_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db99_sequencial = $db99_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pârametros do endereço nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db99_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pârametros do endereço nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db99_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db99_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db99_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderparam ";
     $sql .= "      inner join cadenderpais  on  cadenderpais.db70_sequencial = cadenderparam.db99_cadenderpais";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial = cadenderparam.db99_cadenderestado";
     $sql .= "      inner join cadendermunicipio  on  cadendermunicipio.db72_sequencial = cadenderparam.db99_cadendermunicipio";
     $sql .= "      inner join cadenderpais  on  cadenderpais.db70_sequencial = cadenderestado.db71_cadenderpais";
     $sql .= "      inner join cadenderestado  as a on   a.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql2 = "";
     if($dbwhere==""){
       if($db99_sequencial!=null ){
         $sql2 .= " where cadenderparam.db99_sequencial = $db99_sequencial "; 
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
  function sql_query_correto ( $db99_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderparam ";
     $sql .= "      inner join cadenderpais    on  cadenderpais.db70_sequencial        = cadenderparam.db99_cadenderpais";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial      = cadenderparam.db99_cadenderestado";
     $sql .= "      inner join cadendermunicipio on  cadendermunicipio.db72_sequencial = cadenderparam.db99_cadendermunicipio";
     $sql .= "      inner join cadenderpais as p on  p.db70_sequencial = cadenderestado.db71_cadenderpais";
     $sql .= "      inner join cadenderestado  as a on a.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql2 = "";
     if($dbwhere==""){
       if($db99_sequencial!=null ){
         $sql2 .= " where cadenderparam.db99_sequencial = $db99_sequencial "; 
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
   function sql_query_file ( $db99_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($db99_sequencial!=null ){
         $sql2 .= " where cadenderparam.db99_sequencial = $db99_sequencial "; 
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