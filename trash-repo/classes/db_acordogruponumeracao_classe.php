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
//CLASSE DA ENTIDADE acordogruponumeracao
class cl_acordogruponumeracao { 
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
   var $ac03_sequencial = 0; 
   var $ac03_acordogrupo = 0; 
   var $ac03_anousu = 0; 
   var $ac03_numero = 0; 
   var $ac03_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac03_sequencial = int4 = Sequencial 
                 ac03_acordogrupo = int4 = Acordo Grupo 
                 ac03_anousu = int4 = Ano Exercício 
                 ac03_numero = int4 = Número 
                 ac03_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_acordogruponumeracao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordogruponumeracao"); 
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
       $this->ac03_sequencial = ($this->ac03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac03_sequencial"]:$this->ac03_sequencial);
       $this->ac03_acordogrupo = ($this->ac03_acordogrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac03_acordogrupo"]:$this->ac03_acordogrupo);
       $this->ac03_anousu = ($this->ac03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ac03_anousu"]:$this->ac03_anousu);
       $this->ac03_numero = ($this->ac03_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ac03_numero"]:$this->ac03_numero);
       $this->ac03_instit = ($this->ac03_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ac03_instit"]:$this->ac03_instit);
     }else{
       $this->ac03_sequencial = ($this->ac03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac03_sequencial"]:$this->ac03_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac03_sequencial){ 
      $this->atualizacampos();
     if($this->ac03_acordogrupo == null ){ 
       $this->erro_sql = " Campo Acordo Grupo nao Informado.";
       $this->erro_campo = "ac03_acordogrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac03_anousu == null ){ 
       $this->erro_sql = " Campo Ano Exercício nao Informado.";
       $this->erro_campo = "ac03_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac03_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "ac03_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac03_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "ac03_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac03_sequencial == "" || $ac03_sequencial == null ){
       $result = db_query("select nextval('acordogruponumeracao_ac03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordogruponumeracao_ac03_sequencial_seq do campo: ac03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordogruponumeracao_ac03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac03_sequencial)){
         $this->erro_sql = " Campo ac03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac03_sequencial = $ac03_sequencial; 
       }
     }
     if(($this->ac03_sequencial == null) || ($this->ac03_sequencial == "") ){ 
       $this->erro_sql = " Campo ac03_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordogruponumeracao(
                                       ac03_sequencial 
                                      ,ac03_acordogrupo 
                                      ,ac03_anousu 
                                      ,ac03_numero 
                                      ,ac03_instit 
                       )
                values (
                                $this->ac03_sequencial 
                               ,$this->ac03_acordogrupo 
                               ,$this->ac03_anousu 
                               ,$this->ac03_numero 
                               ,$this->ac03_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Grupo Numeração ($this->ac03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Grupo Numeração já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Grupo Numeração ($this->ac03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac03_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16099,'$this->ac03_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2824,16099,'','".AddSlashes(pg_result($resaco,0,'ac03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2824,16100,'','".AddSlashes(pg_result($resaco,0,'ac03_acordogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2824,16101,'','".AddSlashes(pg_result($resaco,0,'ac03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2824,16102,'','".AddSlashes(pg_result($resaco,0,'ac03_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2824,16103,'','".AddSlashes(pg_result($resaco,0,'ac03_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac03_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordogruponumeracao set ";
     $virgula = "";
     if(trim($this->ac03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac03_sequencial"])){ 
       $sql  .= $virgula." ac03_sequencial = $this->ac03_sequencial ";
       $virgula = ",";
       if(trim($this->ac03_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac03_acordogrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac03_acordogrupo"])){ 
       $sql  .= $virgula." ac03_acordogrupo = $this->ac03_acordogrupo ";
       $virgula = ",";
       if(trim($this->ac03_acordogrupo) == null ){ 
         $this->erro_sql = " Campo Acordo Grupo nao Informado.";
         $this->erro_campo = "ac03_acordogrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac03_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac03_anousu"])){ 
       $sql  .= $virgula." ac03_anousu = $this->ac03_anousu ";
       $virgula = ",";
       if(trim($this->ac03_anousu) == null ){ 
         $this->erro_sql = " Campo Ano Exercício nao Informado.";
         $this->erro_campo = "ac03_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac03_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac03_numero"])){ 
       $sql  .= $virgula." ac03_numero = $this->ac03_numero ";
       $virgula = ",";
       if(trim($this->ac03_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "ac03_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac03_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac03_instit"])){ 
       $sql  .= $virgula." ac03_instit = $this->ac03_instit ";
       $virgula = ",";
       if(trim($this->ac03_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "ac03_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac03_sequencial!=null){
       $sql .= " ac03_sequencial = $this->ac03_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac03_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16099,'$this->ac03_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac03_sequencial"]) || $this->ac03_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2824,16099,'".AddSlashes(pg_result($resaco,$conresaco,'ac03_sequencial'))."','$this->ac03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac03_acordogrupo"]) || $this->ac03_acordogrupo != "")
           $resac = db_query("insert into db_acount values($acount,2824,16100,'".AddSlashes(pg_result($resaco,$conresaco,'ac03_acordogrupo'))."','$this->ac03_acordogrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac03_anousu"]) || $this->ac03_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2824,16101,'".AddSlashes(pg_result($resaco,$conresaco,'ac03_anousu'))."','$this->ac03_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac03_numero"]) || $this->ac03_numero != "")
           $resac = db_query("insert into db_acount values($acount,2824,16102,'".AddSlashes(pg_result($resaco,$conresaco,'ac03_numero'))."','$this->ac03_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac03_instit"]) || $this->ac03_instit != "")
           $resac = db_query("insert into db_acount values($acount,2824,16103,'".AddSlashes(pg_result($resaco,$conresaco,'ac03_instit'))."','$this->ac03_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Grupo Numeração nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Grupo Numeração nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac03_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac03_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16099,'$ac03_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2824,16099,'','".AddSlashes(pg_result($resaco,$iresaco,'ac03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2824,16100,'','".AddSlashes(pg_result($resaco,$iresaco,'ac03_acordogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2824,16101,'','".AddSlashes(pg_result($resaco,$iresaco,'ac03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2824,16102,'','".AddSlashes(pg_result($resaco,$iresaco,'ac03_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2824,16103,'','".AddSlashes(pg_result($resaco,$iresaco,'ac03_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordogruponumeracao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac03_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac03_sequencial = $ac03_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Grupo Numeração nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Grupo Numeração nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordogruponumeracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogruponumeracao ";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordogruponumeracao.ac03_acordogrupo";
     $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
     $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac03_sequencial!=null ){
         $sql2 .= " where acordogruponumeracao.ac03_sequencial = $ac03_sequencial "; 
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
   function sql_query_file ( $ac03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogruponumeracao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac03_sequencial!=null ){
         $sql2 .= " where acordogruponumeracao.ac03_sequencial = $ac03_sequencial "; 
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