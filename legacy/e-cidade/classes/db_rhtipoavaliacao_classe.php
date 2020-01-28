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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhtipoavaliacao
class cl_rhtipoavaliacao { 
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
   var $h69_sequencial = 0; 
   var $h69_descricao = null; 
   var $h69_rhgrupotipoavaliacao = 0; 
   var $h69_quantminima = 0; 
   var $h69_quantmaxima = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h69_sequencial = int4 = sequencial 
                 h69_descricao = varchar(150) = Descrição 
                 h69_rhgrupotipoavaliacao = int4 = Grupo de tipo de avaliação 
                 h69_quantminima = int4 = Quantidade minima 
                 h69_quantmaxima = int4 = Quantidade máxima 
                 ";
   //funcao construtor da classe 
   function cl_rhtipoavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhtipoavaliacao"); 
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
       $this->h69_sequencial = ($this->h69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h69_sequencial"]:$this->h69_sequencial);
       $this->h69_descricao = ($this->h69_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["h69_descricao"]:$this->h69_descricao);
       $this->h69_rhgrupotipoavaliacao = ($this->h69_rhgrupotipoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h69_rhgrupotipoavaliacao"]:$this->h69_rhgrupotipoavaliacao);
       $this->h69_quantminima = ($this->h69_quantminima == ""?@$GLOBALS["HTTP_POST_VARS"]["h69_quantminima"]:$this->h69_quantminima);
       $this->h69_quantmaxima = ($this->h69_quantmaxima == ""?@$GLOBALS["HTTP_POST_VARS"]["h69_quantmaxima"]:$this->h69_quantmaxima);
     }else{
       $this->h69_sequencial = ($this->h69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h69_sequencial"]:$this->h69_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h69_sequencial){ 
      $this->atualizacampos();
     if($this->h69_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "h69_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h69_rhgrupotipoavaliacao == null ){ 
       $this->erro_sql = " Campo Grupo de tipo de avaliação nao Informado.";
       $this->erro_campo = "h69_rhgrupotipoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h69_quantminima == null ){ 
       $this->erro_sql = " Campo Quantidade minima nao Informado.";
       $this->erro_campo = "h69_quantminima";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h69_quantmaxima == null ){ 
       $this->erro_sql = " Campo Quantidade máxima nao Informado.";
       $this->erro_campo = "h69_quantmaxima";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h69_sequencial == "" || $h69_sequencial == null ){
       $result = db_query("select nextval('rhtipoavaliacao_h69_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhtipoavaliacao_h69_sequencial_seq do campo: h69_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h69_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhtipoavaliacao_h69_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h69_sequencial)){
         $this->erro_sql = " Campo h69_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h69_sequencial = $h69_sequencial; 
       }
     }
     if(($this->h69_sequencial == null) || ($this->h69_sequencial == "") ){ 
       $this->erro_sql = " Campo h69_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhtipoavaliacao(
                                       h69_sequencial 
                                      ,h69_descricao 
                                      ,h69_rhgrupotipoavaliacao 
                                      ,h69_quantminima 
                                      ,h69_quantmaxima 
                       )
                values (
                                $this->h69_sequencial 
                               ,'$this->h69_descricao' 
                               ,$this->h69_rhgrupotipoavaliacao 
                               ,$this->h69_quantminima 
                               ,$this->h69_quantmaxima 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de avaliação ($this->h69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de avaliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de avaliação ($this->h69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h69_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h69_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18698,'$this->h69_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3312,18698,'','".AddSlashes(pg_result($resaco,0,'h69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3312,18699,'','".AddSlashes(pg_result($resaco,0,'h69_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3312,18700,'','".AddSlashes(pg_result($resaco,0,'h69_rhgrupotipoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3312,18701,'','".AddSlashes(pg_result($resaco,0,'h69_quantminima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3312,18702,'','".AddSlashes(pg_result($resaco,0,'h69_quantmaxima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h69_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhtipoavaliacao set ";
     $virgula = "";
     if(trim($this->h69_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h69_sequencial"])){ 
       $sql  .= $virgula." h69_sequencial = $this->h69_sequencial ";
       $virgula = ",";
       if(trim($this->h69_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "h69_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h69_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h69_descricao"])){ 
       $sql  .= $virgula." h69_descricao = '$this->h69_descricao' ";
       $virgula = ",";
       if(trim($this->h69_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "h69_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h69_rhgrupotipoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h69_rhgrupotipoavaliacao"])){ 
       $sql  .= $virgula." h69_rhgrupotipoavaliacao = $this->h69_rhgrupotipoavaliacao ";
       $virgula = ",";
       if(trim($this->h69_rhgrupotipoavaliacao) == null ){ 
         $this->erro_sql = " Campo Grupo de tipo de avaliação nao Informado.";
         $this->erro_campo = "h69_rhgrupotipoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h69_quantminima)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h69_quantminima"])){ 
       $sql  .= $virgula." h69_quantminima = $this->h69_quantminima ";
       $virgula = ",";
       if(trim($this->h69_quantminima) == null ){ 
         $this->erro_sql = " Campo Quantidade minima nao Informado.";
         $this->erro_campo = "h69_quantminima";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h69_quantmaxima)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h69_quantmaxima"])){ 
       $sql  .= $virgula." h69_quantmaxima = $this->h69_quantmaxima ";
       $virgula = ",";
       if(trim($this->h69_quantmaxima) == null ){ 
         $this->erro_sql = " Campo Quantidade máxima nao Informado.";
         $this->erro_campo = "h69_quantmaxima";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h69_sequencial!=null){
       $sql .= " h69_sequencial = $this->h69_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h69_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18698,'$this->h69_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h69_sequencial"]) || $this->h69_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3312,18698,'".AddSlashes(pg_result($resaco,$conresaco,'h69_sequencial'))."','$this->h69_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h69_descricao"]) || $this->h69_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3312,18699,'".AddSlashes(pg_result($resaco,$conresaco,'h69_descricao'))."','$this->h69_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h69_rhgrupotipoavaliacao"]) || $this->h69_rhgrupotipoavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,3312,18700,'".AddSlashes(pg_result($resaco,$conresaco,'h69_rhgrupotipoavaliacao'))."','$this->h69_rhgrupotipoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h69_quantminima"]) || $this->h69_quantminima != "")
           $resac = db_query("insert into db_acount values($acount,3312,18701,'".AddSlashes(pg_result($resaco,$conresaco,'h69_quantminima'))."','$this->h69_quantminima',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h69_quantmaxima"]) || $this->h69_quantmaxima != "")
           $resac = db_query("insert into db_acount values($acount,3312,18702,'".AddSlashes(pg_result($resaco,$conresaco,'h69_quantmaxima'))."','$this->h69_quantmaxima',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de avaliação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de avaliação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h69_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h69_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18698,'$h69_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3312,18698,'','".AddSlashes(pg_result($resaco,$iresaco,'h69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3312,18699,'','".AddSlashes(pg_result($resaco,$iresaco,'h69_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3312,18700,'','".AddSlashes(pg_result($resaco,$iresaco,'h69_rhgrupotipoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3312,18701,'','".AddSlashes(pg_result($resaco,$iresaco,'h69_quantminima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3312,18702,'','".AddSlashes(pg_result($resaco,$iresaco,'h69_quantmaxima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhtipoavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h69_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h69_sequencial = $h69_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de avaliação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de avaliação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h69_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhtipoavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   // funcao do sql 
   function sql_query ( $h69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhtipoavaliacao ";
     $sql .= "      inner join rhgrupotipoavaliacao  on  rhgrupotipoavaliacao.h68_sequencial = rhtipoavaliacao.h69_rhgrupotipoavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($h69_sequencial!=null ){
         $sql2 .= " where rhtipoavaliacao.h69_sequencial = $h69_sequencial "; 
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
   function sql_query_file ( $h69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhtipoavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h69_sequencial!=null ){
         $sql2 .= " where rhtipoavaliacao.h69_sequencial = $h69_sequencial "; 
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

  
  /**
   * Retorna os tipos de avaliação selecionada, totalizando seus pontos
   */   
  function sql_query_somaRequisitos($iCodigoPromocao){

    $sSql = "select                                                                                                                   ";
    $sSql .= "   h69_sequencial,                                                                                                      ";
    $sSql .= "   h69_descricao,                                                                                                       ";
    $sSql .= "   sum(h76_pontos) as h76_pontos                                                                                        ";
    $sSql .= " from rhtipoavaliacao                                                                                                   ";
    $sSql .= "      inner join rhgrupotipoavaliacao on rhgrupotipoavaliacao.h68_sequencial = rhtipoavaliacao.h69_rhgrupotipoavaliacao ";
    $sSql .= "       left join rhavaliacaotipoavaliacao on h76_rhtipoavaliacao = h69_sequencial                                       ";
    $sSql .= "       left join rhavaliacao on h76_rhavaliacao = h73_sequencial                                                        ";
    $sSql .= "where h68_tipolancamento <> 3                                                                                           ";
    $sSql .= "  and h73_rhpromocao     = {$iCodigoPromocao}                                                                           ";
    $sSql .= "group by h69_sequencial,                                                                                                ";
    $sSql .= "         h69_descricao                                                                                                 ";

    return $sSql;
  }

}
?>