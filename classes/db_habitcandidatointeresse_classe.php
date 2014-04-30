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

//MODULO: habitacao
//CLASSE DA ENTIDADE habitcandidatointeresse
class cl_habitcandidatointeresse { 
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
   var $ht20_sequencial = 0; 
   var $ht20_habitcandidato = 0; 
   var $ht20_habitgrupoprograma = 0; 
   var $ht20_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht20_sequencial = int4 = Sequencial 
                 ht20_habitcandidato = int4 = Candidato 
                 ht20_habitgrupoprograma = int4 = Grupo Programa 
                 ht20_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_habitcandidatointeresse() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitcandidatointeresse"); 
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
       $this->ht20_sequencial = ($this->ht20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht20_sequencial"]:$this->ht20_sequencial);
       $this->ht20_habitcandidato = ($this->ht20_habitcandidato == ""?@$GLOBALS["HTTP_POST_VARS"]["ht20_habitcandidato"]:$this->ht20_habitcandidato);
       $this->ht20_habitgrupoprograma = ($this->ht20_habitgrupoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht20_habitgrupoprograma"]:$this->ht20_habitgrupoprograma);
       $this->ht20_ativo = ($this->ht20_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ht20_ativo"]:$this->ht20_ativo);
     }else{
       $this->ht20_sequencial = ($this->ht20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht20_sequencial"]:$this->ht20_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht20_sequencial){ 
      $this->atualizacampos();
     if($this->ht20_habitcandidato == null ){ 
       $this->erro_sql = " Campo Candidato nao Informado.";
       $this->erro_campo = "ht20_habitcandidato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht20_habitgrupoprograma == null ){ 
       $this->erro_sql = " Campo Grupo Programa nao Informado.";
       $this->erro_campo = "ht20_habitgrupoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht20_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ht20_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht20_sequencial == "" || $ht20_sequencial == null ){
       $result = db_query("select nextval('habitcandidatointeresse_ht20_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitcandidatointeresse_ht20_sequencial_seq do campo: ht20_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht20_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitcandidatointeresse_ht20_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht20_sequencial)){
         $this->erro_sql = " Campo ht20_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht20_sequencial = $ht20_sequencial; 
       }
     }
     if(($this->ht20_sequencial == null) || ($this->ht20_sequencial == "") ){ 
       $this->erro_sql = " Campo ht20_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitcandidatointeresse(
                                       ht20_sequencial 
                                      ,ht20_habitcandidato 
                                      ,ht20_habitgrupoprograma 
                                      ,ht20_ativo 
                       )
                values (
                                $this->ht20_sequencial 
                               ,$this->ht20_habitcandidato 
                               ,$this->ht20_habitgrupoprograma 
                               ,'$this->ht20_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Interesse do Candidato da Habitação ($this->ht20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Interesse do Candidato da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Interesse do Candidato da Habitação ($this->ht20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht20_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht20_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17033,'$this->ht20_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3008,17033,'','".AddSlashes(pg_result($resaco,0,'ht20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3008,17034,'','".AddSlashes(pg_result($resaco,0,'ht20_habitcandidato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3008,17035,'','".AddSlashes(pg_result($resaco,0,'ht20_habitgrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3008,17036,'','".AddSlashes(pg_result($resaco,0,'ht20_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht20_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitcandidatointeresse set ";
     $virgula = "";
     if(trim($this->ht20_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht20_sequencial"])){ 
       $sql  .= $virgula." ht20_sequencial = $this->ht20_sequencial ";
       $virgula = ",";
       if(trim($this->ht20_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht20_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht20_habitcandidato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht20_habitcandidato"])){ 
       $sql  .= $virgula." ht20_habitcandidato = $this->ht20_habitcandidato ";
       $virgula = ",";
       if(trim($this->ht20_habitcandidato) == null ){ 
         $this->erro_sql = " Campo Candidato nao Informado.";
         $this->erro_campo = "ht20_habitcandidato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht20_habitgrupoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht20_habitgrupoprograma"])){ 
       $sql  .= $virgula." ht20_habitgrupoprograma = $this->ht20_habitgrupoprograma ";
       $virgula = ",";
       if(trim($this->ht20_habitgrupoprograma) == null ){ 
         $this->erro_sql = " Campo Grupo Programa nao Informado.";
         $this->erro_campo = "ht20_habitgrupoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht20_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht20_ativo"])){ 
       $sql  .= $virgula." ht20_ativo = '$this->ht20_ativo' ";
       $virgula = ",";
       if(trim($this->ht20_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ht20_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht20_sequencial!=null){
       $sql .= " ht20_sequencial = $this->ht20_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht20_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17033,'$this->ht20_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht20_sequencial"]) || $this->ht20_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3008,17033,'".AddSlashes(pg_result($resaco,$conresaco,'ht20_sequencial'))."','$this->ht20_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht20_habitcandidato"]) || $this->ht20_habitcandidato != "")
           $resac = db_query("insert into db_acount values($acount,3008,17034,'".AddSlashes(pg_result($resaco,$conresaco,'ht20_habitcandidato'))."','$this->ht20_habitcandidato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht20_habitgrupoprograma"]) || $this->ht20_habitgrupoprograma != "")
           $resac = db_query("insert into db_acount values($acount,3008,17035,'".AddSlashes(pg_result($resaco,$conresaco,'ht20_habitgrupoprograma'))."','$this->ht20_habitgrupoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht20_ativo"]) || $this->ht20_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3008,17036,'".AddSlashes(pg_result($resaco,$conresaco,'ht20_ativo'))."','$this->ht20_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Interesse do Candidato da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Interesse do Candidato da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht20_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht20_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17033,'$ht20_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3008,17033,'','".AddSlashes(pg_result($resaco,$iresaco,'ht20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3008,17034,'','".AddSlashes(pg_result($resaco,$iresaco,'ht20_habitcandidato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3008,17035,'','".AddSlashes(pg_result($resaco,$iresaco,'ht20_habitgrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3008,17036,'','".AddSlashes(pg_result($resaco,$iresaco,'ht20_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitcandidatointeresse
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht20_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht20_sequencial = $ht20_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Interesse do Candidato da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Interesse do Candidato da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht20_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitcandidatointeresse";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitcandidatointeresse                                                                                                                                         ";
     $sql .= "      inner join habitcandidato                  on habitcandidato.ht10_sequencial                               = habitcandidatointeresse.ht20_habitcandidato        ";
     $sql .= "      inner join cgm                             on cgm.z01_numcgm                                               = habitcandidato.ht10_numcgm                         ";
     $sql .= "      inner join habitgrupoprograma              on habitgrupoprograma.ht03_sequencial                           = habitcandidatointeresse.ht20_habitgrupoprograma    ";
     $sql .= "      left  join habitcandidatointeresseprograma on habitcandidatointeresseprograma.ht13_habitcandidatointeresse = habitcandidatointeresse.ht20_sequencial            ";
     $sql .= "      left  join cgmsituacaocpf                  on cgmsituacaocpf.z17_numcgm                                    = cgm.z01_numcgm                                     ";
     $sql .= "      left  join habitprograma                   on habitprograma.ht01_habitgrupoprograma                        = habitcandidatointeresseprograma.ht13_habitprograma ";
     $sql2 = "";
     
     if($dbwhere==""){
       if($ht20_sequencial!=null ){
         $sql2 .= " where habitcandidatointeresse.ht20_sequencial = $ht20_sequencial "; 
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
   function sql_query_file ( $ht20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitcandidatointeresse ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht20_sequencial!=null ){
         $sql2 .= " where habitcandidatointeresse.ht20_sequencial = $ht20_sequencial "; 
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