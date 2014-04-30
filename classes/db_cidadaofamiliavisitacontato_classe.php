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

//MODULO: social
//CLASSE DA ENTIDADE cidadaofamiliavisitacontato
class cl_cidadaofamiliavisitacontato { 
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
   var $as10_sequencial = 0; 
   var $as10_cidadaofamiliavisita = 0; 
   var $as10_profissionalcontato = 0; 
   var $as10_data_dia = null; 
   var $as10_data_mes = null; 
   var $as10_data_ano = null; 
   var $as10_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as10_sequencial = int4 = Código 
                 as10_cidadaofamiliavisita = int4 = Código Cidadao Família Visita 
                 as10_profissionalcontato = int4 = Código Profissional 
                 as10_data = date = Data do Contato 
                 ";
   //funcao construtor da classe 
   function cl_cidadaofamiliavisitacontato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaofamiliavisitacontato"); 
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
       $this->as10_sequencial = ($this->as10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_sequencial"]:$this->as10_sequencial);
       $this->as10_cidadaofamiliavisita = ($this->as10_cidadaofamiliavisita == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_cidadaofamiliavisita"]:$this->as10_cidadaofamiliavisita);
       $this->as10_profissionalcontato = ($this->as10_profissionalcontato == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_profissionalcontato"]:$this->as10_profissionalcontato);
       if($this->as10_data == ""){
         $this->as10_data_dia = ($this->as10_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_data_dia"]:$this->as10_data_dia);
         $this->as10_data_mes = ($this->as10_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_data_mes"]:$this->as10_data_mes);
         $this->as10_data_ano = ($this->as10_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_data_ano"]:$this->as10_data_ano);
         if($this->as10_data_dia != ""){
            $this->as10_data = $this->as10_data_ano."-".$this->as10_data_mes."-".$this->as10_data_dia;
         }
       }
     }else{
       $this->as10_sequencial = ($this->as10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as10_sequencial"]:$this->as10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as10_sequencial){ 
      $this->atualizacampos();
     if($this->as10_cidadaofamiliavisita == null ){ 
       $this->erro_sql = " Campo Código Cidadao Família Visita nao Informado.";
       $this->erro_campo = "as10_cidadaofamiliavisita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as10_profissionalcontato == null ){ 
       $this->erro_sql = " Campo Código Profissional nao Informado.";
       $this->erro_campo = "as10_profissionalcontato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as10_data == null ){ 
       $this->erro_sql = " Campo Data do Contato nao Informado.";
       $this->erro_campo = "as10_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as10_sequencial == "" || $as10_sequencial == null ){
       $result = db_query("select nextval('cidadaofamiliavisitacontato_as10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaofamiliavisitacontato_as10_sequencial_seq do campo: as10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaofamiliavisitacontato_as10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as10_sequencial)){
         $this->erro_sql = " Campo as10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as10_sequencial = $as10_sequencial; 
       }
     }
     if(($this->as10_sequencial == null) || ($this->as10_sequencial == "") ){ 
       $this->erro_sql = " Campo as10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaofamiliavisitacontato(
                                       as10_sequencial 
                                      ,as10_cidadaofamiliavisita 
                                      ,as10_profissionalcontato 
                                      ,as10_data 
                       )
                values (
                                $this->as10_sequencial 
                               ,$this->as10_cidadaofamiliavisita 
                               ,$this->as10_profissionalcontato 
                               ,".($this->as10_data == "null" || $this->as10_data == ""?"null":"'".$this->as10_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaofamiliavisitacontato ($this->as10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaofamiliavisitacontato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaofamiliavisitacontato ($this->as10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->as10_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19640,'$this->as10_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3491,19640,'','".AddSlashes(pg_result($resaco,0,'as10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3491,19641,'','".AddSlashes(pg_result($resaco,0,'as10_cidadaofamiliavisita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3491,19642,'','".AddSlashes(pg_result($resaco,0,'as10_profissionalcontato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3491,19643,'','".AddSlashes(pg_result($resaco,0,'as10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaofamiliavisitacontato set ";
     $virgula = "";
     if(trim($this->as10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as10_sequencial"])){ 
       $sql  .= $virgula." as10_sequencial = $this->as10_sequencial ";
       $virgula = ",";
       if(trim($this->as10_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as10_cidadaofamiliavisita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as10_cidadaofamiliavisita"])){ 
       $sql  .= $virgula." as10_cidadaofamiliavisita = $this->as10_cidadaofamiliavisita ";
       $virgula = ",";
       if(trim($this->as10_cidadaofamiliavisita) == null ){ 
         $this->erro_sql = " Campo Código Cidadao Família Visita nao Informado.";
         $this->erro_campo = "as10_cidadaofamiliavisita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as10_profissionalcontato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as10_profissionalcontato"])){ 
       $sql  .= $virgula." as10_profissionalcontato = $this->as10_profissionalcontato ";
       $virgula = ",";
       if(trim($this->as10_profissionalcontato) == null ){ 
         $this->erro_sql = " Campo Código Profissional nao Informado.";
         $this->erro_campo = "as10_profissionalcontato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as10_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as10_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as10_data_dia"] !="") ){ 
       $sql  .= $virgula." as10_data = '$this->as10_data' ";
       $virgula = ",";
       if(trim($this->as10_data) == null ){ 
         $this->erro_sql = " Campo Data do Contato nao Informado.";
         $this->erro_campo = "as10_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as10_data_dia"])){ 
         $sql  .= $virgula." as10_data = null ";
         $virgula = ",";
         if(trim($this->as10_data) == null ){ 
           $this->erro_sql = " Campo Data do Contato nao Informado.";
           $this->erro_campo = "as10_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($as10_sequencial!=null){
       $sql .= " as10_sequencial = $this->as10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->as10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19640,'$this->as10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as10_sequencial"]) || $this->as10_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3491,19640,'".AddSlashes(pg_result($resaco,$conresaco,'as10_sequencial'))."','$this->as10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as10_cidadaofamiliavisita"]) || $this->as10_cidadaofamiliavisita != "")
           $resac = db_query("insert into db_acount values($acount,3491,19641,'".AddSlashes(pg_result($resaco,$conresaco,'as10_cidadaofamiliavisita'))."','$this->as10_cidadaofamiliavisita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as10_profissionalcontato"]) || $this->as10_profissionalcontato != "")
           $resac = db_query("insert into db_acount values($acount,3491,19642,'".AddSlashes(pg_result($resaco,$conresaco,'as10_profissionalcontato'))."','$this->as10_profissionalcontato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as10_data"]) || $this->as10_data != "")
           $resac = db_query("insert into db_acount values($acount,3491,19643,'".AddSlashes(pg_result($resaco,$conresaco,'as10_data'))."','$this->as10_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaofamiliavisitacontato nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaofamiliavisitacontato nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as10_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($as10_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19640,'$as10_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3491,19640,'','".AddSlashes(pg_result($resaco,$iresaco,'as10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3491,19641,'','".AddSlashes(pg_result($resaco,$iresaco,'as10_cidadaofamiliavisita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3491,19642,'','".AddSlashes(pg_result($resaco,$iresaco,'as10_profissionalcontato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3491,19643,'','".AddSlashes(pg_result($resaco,$iresaco,'as10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cidadaofamiliavisitacontato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as10_sequencial = $as10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaofamiliavisitacontato nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaofamiliavisitacontato nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaofamiliavisitacontato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofamiliavisitacontato ";
     $sql .= "      inner join cgm as profissionalcontato on  profissionalcontato.z01_numcgm = cidadaofamiliavisitacontato.as10_profissionalcontato";
     $sql .= "      inner join cidadaofamiliavisita       on  cidadaofamiliavisita.as05_sequencial = cidadaofamiliavisitacontato.as10_cidadaofamiliavisita";
     $sql .= "      inner join cgm as profissionalvisita  on  profissionalvisita.z01_numcgm = cidadaofamiliavisita.as05_profissional";
     $sql .= "      inner join cidadaofamilia  as a       on  a.as04_sequencial = cidadaofamiliavisita.as05_cidadaofamilia";
     $sql2 = "";
     if($dbwhere==""){
       if($as10_sequencial!=null ){
         $sql2 .= " where cidadaofamiliavisitacontato.as10_sequencial = $as10_sequencial "; 
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
   function sql_query_file ( $as10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofamiliavisitacontato ";
     $sql2 = "";
     if($dbwhere==""){
       if($as10_sequencial!=null ){
         $sql2 .= " where cidadaofamiliavisitacontato.as10_sequencial = $as10_sequencial "; 
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