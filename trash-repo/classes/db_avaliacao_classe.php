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

//MODULO: habitacao
//CLASSE DA ENTIDADE avaliacao
class cl_avaliacao { 
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
   var $db101_sequencial = 0; 
   var $db101_avaliacaotipo = 0; 
   var $db101_descricao = null; 
   var $db101_identificador = null; 
   var $db101_obs = null; 
   var $db101_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db101_sequencial = int4 = Sequencial 
                 db101_avaliacaotipo = int4 = Avalia��o Tipo 
                 db101_descricao = varchar(50) = Descri��o 
                 db101_identificador = varchar(50) = Identificador 
                 db101_obs = text = Observa��o 
                 db101_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_avaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacao"); 
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
       $this->db101_sequencial = ($this->db101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db101_sequencial"]:$this->db101_sequencial);
       $this->db101_avaliacaotipo = ($this->db101_avaliacaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db101_avaliacaotipo"]:$this->db101_avaliacaotipo);
       $this->db101_descricao = ($this->db101_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db101_descricao"]:$this->db101_descricao);
       $this->db101_identificador = ($this->db101_identificador == ""?@$GLOBALS["HTTP_POST_VARS"]["db101_identificador"]:$this->db101_identificador);
       $this->db101_obs = ($this->db101_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db101_obs"]:$this->db101_obs);
       $this->db101_ativo = ($this->db101_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["db101_ativo"]:$this->db101_ativo);
     }else{
       $this->db101_sequencial = ($this->db101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db101_sequencial"]:$this->db101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db101_sequencial){ 
      $this->atualizacampos();
     if($this->db101_avaliacaotipo == null ){ 
       $this->erro_sql = " Campo Avalia��o Tipo nao Informado.";
       $this->erro_campo = "db101_avaliacaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db101_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "db101_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db101_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "db101_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db101_sequencial == "" || $db101_sequencial == null ){
       $result = db_query("select nextval('avaliacao_db101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacao_db101_sequencial_seq do campo: db101_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db101_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacao_db101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db101_sequencial)){
         $this->erro_sql = " Campo db101_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db101_sequencial = $db101_sequencial; 
       }
     }
     if(($this->db101_sequencial == null) || ($this->db101_sequencial == "") ){ 
       $this->erro_sql = " Campo db101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacao(
                                       db101_sequencial 
                                      ,db101_avaliacaotipo 
                                      ,db101_descricao 
                                      ,db101_identificador 
                                      ,db101_obs 
                                      ,db101_ativo 
                       )
                values (
                                $this->db101_sequencial 
                               ,$this->db101_avaliacaotipo 
                               ,'$this->db101_descricao' 
                               ,'$this->db101_identificador' 
                               ,'$this->db101_obs' 
                               ,'$this->db101_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avalia��o ($this->db101_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avalia��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avalia��o ($this->db101_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db101_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16908,'$this->db101_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2980,16908,'','".AddSlashes(pg_result($resaco,0,'db101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2980,16909,'','".AddSlashes(pg_result($resaco,0,'db101_avaliacaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2980,16910,'','".AddSlashes(pg_result($resaco,0,'db101_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2980,19376,'','".AddSlashes(pg_result($resaco,0,'db101_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2980,16911,'','".AddSlashes(pg_result($resaco,0,'db101_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2980,17079,'','".AddSlashes(pg_result($resaco,0,'db101_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacao set ";
     $virgula = "";
     if(trim($this->db101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db101_sequencial"])){ 
       $sql  .= $virgula." db101_sequencial = $this->db101_sequencial ";
       $virgula = ",";
       if(trim($this->db101_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db101_avaliacaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db101_avaliacaotipo"])){ 
       $sql  .= $virgula." db101_avaliacaotipo = $this->db101_avaliacaotipo ";
       $virgula = ",";
       if(trim($this->db101_avaliacaotipo) == null ){ 
         $this->erro_sql = " Campo Avalia��o Tipo nao Informado.";
         $this->erro_campo = "db101_avaliacaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db101_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db101_descricao"])){ 
       $sql  .= $virgula." db101_descricao = '$this->db101_descricao' ";
       $virgula = ",";
       if(trim($this->db101_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "db101_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db101_identificador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db101_identificador"])){ 
       $sql  .= $virgula." db101_identificador = '$this->db101_identificador' ";
       $virgula = ",";
     }
     if(trim($this->db101_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db101_obs"])){ 
       $sql  .= $virgula." db101_obs = '$this->db101_obs' ";
       $virgula = ",";
     }
     if(trim($this->db101_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db101_ativo"])){ 
       $sql  .= $virgula." db101_ativo = '$this->db101_ativo' ";
       $virgula = ",";
       if(trim($this->db101_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "db101_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db101_sequencial!=null){
       $sql .= " db101_sequencial = $this->db101_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db101_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16908,'$this->db101_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db101_sequencial"]) || $this->db101_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2980,16908,'".AddSlashes(pg_result($resaco,$conresaco,'db101_sequencial'))."','$this->db101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db101_avaliacaotipo"]) || $this->db101_avaliacaotipo != "")
           $resac = db_query("insert into db_acount values($acount,2980,16909,'".AddSlashes(pg_result($resaco,$conresaco,'db101_avaliacaotipo'))."','$this->db101_avaliacaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db101_descricao"]) || $this->db101_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2980,16910,'".AddSlashes(pg_result($resaco,$conresaco,'db101_descricao'))."','$this->db101_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db101_identificador"]) || $this->db101_identificador != "")
           $resac = db_query("insert into db_acount values($acount,2980,19376,'".AddSlashes(pg_result($resaco,$conresaco,'db101_identificador'))."','$this->db101_identificador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db101_obs"]) || $this->db101_obs != "")
           $resac = db_query("insert into db_acount values($acount,2980,16911,'".AddSlashes(pg_result($resaco,$conresaco,'db101_obs'))."','$this->db101_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db101_ativo"]) || $this->db101_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2980,17079,'".AddSlashes(pg_result($resaco,$conresaco,'db101_ativo'))."','$this->db101_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db101_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db101_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db101_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16908,'$db101_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2980,16908,'','".AddSlashes(pg_result($resaco,$iresaco,'db101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2980,16909,'','".AddSlashes(pg_result($resaco,$iresaco,'db101_avaliacaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2980,16910,'','".AddSlashes(pg_result($resaco,$iresaco,'db101_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2980,19376,'','".AddSlashes(pg_result($resaco,$iresaco,'db101_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2980,16911,'','".AddSlashes(pg_result($resaco,$iresaco,'db101_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2980,17079,'','".AddSlashes(pg_result($resaco,$iresaco,'db101_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db101_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db101_sequencial = $db101_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db101_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:avaliacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacao ";
     $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($db101_sequencial!=null ){
         $sql2 .= " where avaliacao.db101_sequencial = $db101_sequencial "; 
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
   function sql_query_file ( $db101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db101_sequencial!=null ){
         $sql2 .= " where avaliacao.db101_sequencial = $db101_sequencial "; 
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