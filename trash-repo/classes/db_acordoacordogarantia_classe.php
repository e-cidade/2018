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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordoacordogarantia
class cl_acordoacordogarantia { 
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
   var $ac12_sequencial = 0; 
   var $ac12_acordo = 0; 
   var $ac12_acordogarantia = 0; 
   var $ac12_texto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac12_sequencial = int4 = Sequencial 
                 ac12_acordo = int4 = Acordo 
                 ac12_acordogarantia = int4 = Acordo Garantia 
                 ac12_texto = text = Texto 
                 ";
   //funcao construtor da classe 
   function cl_acordoacordogarantia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoacordogarantia"); 
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
       $this->ac12_sequencial = ($this->ac12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac12_sequencial"]:$this->ac12_sequencial);
       $this->ac12_acordo = ($this->ac12_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac12_acordo"]:$this->ac12_acordo);
       $this->ac12_acordogarantia = ($this->ac12_acordogarantia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac12_acordogarantia"]:$this->ac12_acordogarantia);
       $this->ac12_texto = ($this->ac12_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["ac12_texto"]:$this->ac12_texto);
     }else{
       $this->ac12_sequencial = ($this->ac12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac12_sequencial"]:$this->ac12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac12_sequencial){ 
      $this->atualizacampos();
     if($this->ac12_acordo == null ){ 
       $this->erro_sql = " Campo Acordo nao Informado.";
       $this->erro_campo = "ac12_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac12_acordogarantia == null ){ 
       $this->erro_sql = " Campo Acordo Garantia nao Informado.";
       $this->erro_campo = "ac12_acordogarantia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac12_texto == null ){ 
       $this->erro_sql = " Campo Texto nao Informado.";
       $this->erro_campo = "ac12_texto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac12_sequencial == "" || $ac12_sequencial == null ){
       $result = db_query("select nextval('acordoacordogarantia_ac12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoacordogarantia_ac12_sequencial_seq do campo: ac12_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoacordogarantia_ac12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac12_sequencial)){
         $this->erro_sql = " Campo ac12_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac12_sequencial = $ac12_sequencial; 
       }
     }
     if(($this->ac12_sequencial == null) || ($this->ac12_sequencial == "") ){ 
       $this->erro_sql = " Campo ac12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoacordogarantia(
                                       ac12_sequencial 
                                      ,ac12_acordo 
                                      ,ac12_acordogarantia 
                                      ,ac12_texto 
                       )
                values (
                                $this->ac12_sequencial 
                               ,$this->ac12_acordo 
                               ,$this->ac12_acordogarantia 
                               ,'$this->ac12_texto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Acordo Garantia ($this->ac12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Acordo Garantia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Acordo Garantia ($this->ac12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac12_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac12_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16112,'$this->ac12_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2827,16112,'','".AddSlashes(pg_result($resaco,0,'ac12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2827,16113,'','".AddSlashes(pg_result($resaco,0,'ac12_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2827,16114,'','".AddSlashes(pg_result($resaco,0,'ac12_acordogarantia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2827,16115,'','".AddSlashes(pg_result($resaco,0,'ac12_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoacordogarantia set ";
     $virgula = "";
     if(trim($this->ac12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac12_sequencial"])){ 
       $sql  .= $virgula." ac12_sequencial = $this->ac12_sequencial ";
       $virgula = ",";
       if(trim($this->ac12_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac12_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac12_acordo"])){ 
       $sql  .= $virgula." ac12_acordo = $this->ac12_acordo ";
       $virgula = ",";
       if(trim($this->ac12_acordo) == null ){ 
         $this->erro_sql = " Campo Acordo nao Informado.";
         $this->erro_campo = "ac12_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac12_acordogarantia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac12_acordogarantia"])){ 
       $sql  .= $virgula." ac12_acordogarantia = $this->ac12_acordogarantia ";
       $virgula = ",";
       if(trim($this->ac12_acordogarantia) == null ){ 
         $this->erro_sql = " Campo Acordo Garantia nao Informado.";
         $this->erro_campo = "ac12_acordogarantia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac12_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac12_texto"])){ 
       $sql  .= $virgula." ac12_texto = '$this->ac12_texto' ";
       $virgula = ",";
       if(trim($this->ac12_texto) == null ){ 
         $this->erro_sql = " Campo Texto nao Informado.";
         $this->erro_campo = "ac12_texto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac12_sequencial!=null){
       $sql .= " ac12_sequencial = $this->ac12_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac12_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16112,'$this->ac12_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac12_sequencial"]) || $this->ac12_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2827,16112,'".AddSlashes(pg_result($resaco,$conresaco,'ac12_sequencial'))."','$this->ac12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac12_acordo"]) || $this->ac12_acordo != "")
           $resac = db_query("insert into db_acount values($acount,2827,16113,'".AddSlashes(pg_result($resaco,$conresaco,'ac12_acordo'))."','$this->ac12_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac12_acordogarantia"]) || $this->ac12_acordogarantia != "")
           $resac = db_query("insert into db_acount values($acount,2827,16114,'".AddSlashes(pg_result($resaco,$conresaco,'ac12_acordogarantia'))."','$this->ac12_acordogarantia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac12_texto"]) || $this->ac12_texto != "")
           $resac = db_query("insert into db_acount values($acount,2827,16115,'".AddSlashes(pg_result($resaco,$conresaco,'ac12_texto'))."','$this->ac12_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Acordo Garantia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Acordo Garantia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac12_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac12_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16112,'$ac12_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2827,16112,'','".AddSlashes(pg_result($resaco,$iresaco,'ac12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2827,16113,'','".AddSlashes(pg_result($resaco,$iresaco,'ac12_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2827,16114,'','".AddSlashes(pg_result($resaco,$iresaco,'ac12_acordogarantia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2827,16115,'','".AddSlashes(pg_result($resaco,$iresaco,'ac12_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoacordogarantia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac12_sequencial = $ac12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Acordo Garantia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Acordo Garantia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoacordogarantia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoacordogarantia ";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoacordogarantia.ac12_acordo";
     $sql .= "      inner join acordogarantia  on  acordogarantia.ac11_sequencial = acordoacordogarantia.ac12_acordogarantia";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac12_sequencial!=null ){
         $sql2 .= " where acordoacordogarantia.ac12_sequencial = $ac12_sequencial "; 
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
   function sql_query_file ( $ac12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoacordogarantia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac12_sequencial!=null ){
         $sql2 .= " where acordoacordogarantia.ac12_sequencial = $ac12_sequencial "; 
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