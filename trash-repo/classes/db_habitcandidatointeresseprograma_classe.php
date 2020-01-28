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
//CLASSE DA ENTIDADE habitcandidatointeresseprograma
class cl_habitcandidatointeresseprograma { 
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
   var $ht13_sequencial = 0; 
   var $ht13_habitprograma = 0; 
   var $ht13_habitcandidatointeresse = 0; 
   var $ht13_codproc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht13_sequencial = int4 = Sequencial 
                 ht13_habitprograma = int4 = Programa 
                 ht13_habitcandidatointeresse = int4 = Interesse do Candidato 
                 ht13_codproc = int4 = Processo 
                 ";
   //funcao construtor da classe 
   function cl_habitcandidatointeresseprograma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitcandidatointeresseprograma"); 
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
       $this->ht13_sequencial = ($this->ht13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"]:$this->ht13_sequencial);
       $this->ht13_habitprograma = ($this->ht13_habitprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_habitprograma"]:$this->ht13_habitprograma);
       $this->ht13_habitcandidatointeresse = ($this->ht13_habitcandidatointeresse == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_habitcandidatointeresse"]:$this->ht13_habitcandidatointeresse);
       $this->ht13_codproc = ($this->ht13_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_codproc"]:$this->ht13_codproc);
     }else{
       $this->ht13_sequencial = ($this->ht13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"]:$this->ht13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht13_sequencial){ 
      $this->atualizacampos();
     if($this->ht13_habitprograma == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "ht13_habitprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht13_habitcandidatointeresse == null ){ 
       $this->erro_sql = " Campo Interesse do Candidato nao Informado.";
       $this->erro_campo = "ht13_habitcandidatointeresse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht13_codproc == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "ht13_codproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht13_sequencial == "" || $ht13_sequencial == null ){
       $result = db_query("select nextval('habitcandidatointeresseprograma_ht13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitcandidatointeresseprograma_ht13_sequencial_seq do campo: ht13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitcandidatointeresseprograma_ht13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht13_sequencial)){
         $this->erro_sql = " Campo ht13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht13_sequencial = $ht13_sequencial; 
       }
     }
     if(($this->ht13_sequencial == null) || ($this->ht13_sequencial == "") ){ 
       $this->erro_sql = " Campo ht13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitcandidatointeresseprograma(
                                       ht13_sequencial 
                                      ,ht13_habitprograma 
                                      ,ht13_habitcandidatointeresse 
                                      ,ht13_codproc 
                       )
                values (
                                $this->ht13_sequencial 
                               ,$this->ht13_habitprograma 
                               ,$this->ht13_habitcandidatointeresse 
                               ,$this->ht13_codproc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Interesse no Programas dos Candidatos ($this->ht13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Interesse no Programas dos Candidatos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Interesse no Programas dos Candidatos ($this->ht13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17853,'$this->ht13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3152,17853,'','".AddSlashes(pg_result($resaco,0,'ht13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3152,17854,'','".AddSlashes(pg_result($resaco,0,'ht13_habitprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3152,17855,'','".AddSlashes(pg_result($resaco,0,'ht13_habitcandidatointeresse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3152,17856,'','".AddSlashes(pg_result($resaco,0,'ht13_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitcandidatointeresseprograma set ";
     $virgula = "";
     if(trim($this->ht13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"])){ 
       $sql  .= $virgula." ht13_sequencial = $this->ht13_sequencial ";
       $virgula = ",";
       if(trim($this->ht13_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht13_habitprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_habitprograma"])){ 
       $sql  .= $virgula." ht13_habitprograma = $this->ht13_habitprograma ";
       $virgula = ",";
       if(trim($this->ht13_habitprograma) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "ht13_habitprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht13_habitcandidatointeresse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_habitcandidatointeresse"])){ 
       $sql  .= $virgula." ht13_habitcandidatointeresse = $this->ht13_habitcandidatointeresse ";
       $virgula = ",";
       if(trim($this->ht13_habitcandidatointeresse) == null ){ 
         $this->erro_sql = " Campo Interesse do Candidato nao Informado.";
         $this->erro_campo = "ht13_habitcandidatointeresse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht13_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht13_codproc"])){ 
       $sql  .= $virgula." ht13_codproc = $this->ht13_codproc ";
       $virgula = ",";
       if(trim($this->ht13_codproc) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "ht13_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht13_sequencial!=null){
       $sql .= " ht13_sequencial = $this->ht13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17853,'$this->ht13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_sequencial"]) || $this->ht13_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3152,17853,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_sequencial'))."','$this->ht13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_habitprograma"]) || $this->ht13_habitprograma != "")
           $resac = db_query("insert into db_acount values($acount,3152,17854,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_habitprograma'))."','$this->ht13_habitprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_habitcandidatointeresse"]) || $this->ht13_habitcandidatointeresse != "")
           $resac = db_query("insert into db_acount values($acount,3152,17855,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_habitcandidatointeresse'))."','$this->ht13_habitcandidatointeresse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht13_codproc"]) || $this->ht13_codproc != "")
           $resac = db_query("insert into db_acount values($acount,3152,17856,'".AddSlashes(pg_result($resaco,$conresaco,'ht13_codproc'))."','$this->ht13_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Interesse no Programas dos Candidatos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Interesse no Programas dos Candidatos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17853,'$ht13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3152,17853,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3152,17854,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_habitprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3152,17855,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_habitcandidatointeresse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3152,17856,'','".AddSlashes(pg_result($resaco,$iresaco,'ht13_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitcandidatointeresseprograma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht13_sequencial = $ht13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Interesse no Programas dos Candidatos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Interesse no Programas dos Candidatos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitcandidatointeresseprograma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitcandidatointeresseprograma ";
     $sql .= "      inner join protprocesso             on protprocesso.p58_codproc                            = habitcandidatointeresseprograma.ht13_codproc";
     $sql .= "      inner join habitprograma            on habitprograma.ht01_sequencial                       = habitcandidatointeresseprograma.ht13_habitprograma";
     $sql .= "      inner join habitcandidatointeresse  on habitcandidatointeresse.ht20_sequencial             = habitcandidatointeresseprograma.ht13_habitcandidatointeresse";
     $sql .= "      left  join habitinscricao           on habitinscricao.ht15_habitcandidatointeresseprograma = habitcandidatointeresseprograma.ht13_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($ht13_sequencial!=null ){
         $sql2 .= " where habitcandidatointeresseprograma.ht13_sequencial = $ht13_sequencial "; 
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
   function sql_query_file ( $ht13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitcandidatointeresseprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht13_sequencial!=null ){
         $sql2 .= " where habitcandidatointeresseprograma.ht13_sequencial = $ht13_sequencial "; 
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