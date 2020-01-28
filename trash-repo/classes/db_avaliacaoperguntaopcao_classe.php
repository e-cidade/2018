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

//MODULO: habitacao
//CLASSE DA ENTIDADE avaliacaoperguntaopcao
class cl_avaliacaoperguntaopcao { 
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
   var $db104_sequencial = 0; 
   var $db104_avaliacaopergunta = 0; 
   var $db104_descricao = null; 
   var $db104_identificador = null; 
   var $db104_aceitatexto = 'f'; 
   var $db104_peso = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db104_sequencial = int4 = Sequencial 
                 db104_avaliacaopergunta = int4 = Avalia��o Pergunta 
                 db104_descricao = varchar(255) = Descri��o 
                 db104_identificador = varchar(50) = Identificador 
                 db104_aceitatexto = bool = Aceita Texto 
                 db104_peso = int4 = Peso 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoperguntaopcao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoperguntaopcao"); 
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
       $this->db104_sequencial = ($this->db104_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db104_sequencial"]:$this->db104_sequencial);
       $this->db104_avaliacaopergunta = ($this->db104_avaliacaopergunta == ""?@$GLOBALS["HTTP_POST_VARS"]["db104_avaliacaopergunta"]:$this->db104_avaliacaopergunta);
       $this->db104_descricao = ($this->db104_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db104_descricao"]:$this->db104_descricao);
       $this->db104_identificador = ($this->db104_identificador == ""?@$GLOBALS["HTTP_POST_VARS"]["db104_identificador"]:$this->db104_identificador);
       $this->db104_aceitatexto = ($this->db104_aceitatexto == "f"?@$GLOBALS["HTTP_POST_VARS"]["db104_aceitatexto"]:$this->db104_aceitatexto);
       $this->db104_peso = ($this->db104_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["db104_peso"]:$this->db104_peso);
     }else{
       $this->db104_sequencial = ($this->db104_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db104_sequencial"]:$this->db104_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db104_sequencial){ 
      $this->atualizacampos();
     if($this->db104_avaliacaopergunta == null ){ 
       $this->erro_sql = " Campo Avalia��o Pergunta nao Informado.";
       $this->erro_campo = "db104_avaliacaopergunta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db104_aceitatexto == null ){ 
       $this->erro_sql = " Campo Aceita Texto nao Informado.";
       $this->erro_campo = "db104_aceitatexto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db104_peso == null ){ 
       $this->db104_peso = "0";
     }
     if($db104_sequencial == "" || $db104_sequencial == null ){
       $result = db_query("select nextval('avaliacaoperguntaopcao_db104_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoperguntaopcao_db104_sequencial_seq do campo: db104_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db104_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoperguntaopcao_db104_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db104_sequencial)){
         $this->erro_sql = " Campo db104_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db104_sequencial = $db104_sequencial; 
       }
     }
     if(($this->db104_sequencial == null) || ($this->db104_sequencial == "") ){ 
       $this->erro_sql = " Campo db104_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoperguntaopcao(
                                       db104_sequencial 
                                      ,db104_avaliacaopergunta 
                                      ,db104_descricao 
                                      ,db104_identificador 
                                      ,db104_aceitatexto 
                                      ,db104_peso 
                       )
                values (
                                $this->db104_sequencial 
                               ,$this->db104_avaliacaopergunta 
                               ,'$this->db104_descricao' 
                               ,'$this->db104_identificador' 
                               ,'$this->db104_aceitatexto' 
                               ,$this->db104_peso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avalia��o Pergunta Op��o ($this->db104_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avalia��o Pergunta Op��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avalia��o Pergunta Op��o ($this->db104_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db104_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db104_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16920,'$this->db104_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2985,16920,'','".AddSlashes(pg_result($resaco,0,'db104_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2985,16921,'','".AddSlashes(pg_result($resaco,0,'db104_avaliacaopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2985,16922,'','".AddSlashes(pg_result($resaco,0,'db104_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2985,19379,'','".AddSlashes(pg_result($resaco,0,'db104_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2985,16923,'','".AddSlashes(pg_result($resaco,0,'db104_aceitatexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2985,19381,'','".AddSlashes(pg_result($resaco,0,'db104_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db104_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoperguntaopcao set ";
     $virgula = "";
     if(trim($this->db104_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db104_sequencial"])){ 
       $sql  .= $virgula." db104_sequencial = $this->db104_sequencial ";
       $virgula = ",";
       if(trim($this->db104_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db104_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db104_avaliacaopergunta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db104_avaliacaopergunta"])){ 
       $sql  .= $virgula." db104_avaliacaopergunta = $this->db104_avaliacaopergunta ";
       $virgula = ",";
       if(trim($this->db104_avaliacaopergunta) == null ){ 
         $this->erro_sql = " Campo Avalia��o Pergunta nao Informado.";
         $this->erro_campo = "db104_avaliacaopergunta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db104_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db104_descricao"])){ 
       $sql  .= $virgula." db104_descricao = '$this->db104_descricao' ";
       $virgula = ",";
     }
     if(trim($this->db104_identificador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db104_identificador"])){ 
       $sql  .= $virgula." db104_identificador = '$this->db104_identificador' ";
       $virgula = ",";
     }
     if(trim($this->db104_aceitatexto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db104_aceitatexto"])){ 
       $sql  .= $virgula." db104_aceitatexto = '$this->db104_aceitatexto' ";
       $virgula = ",";
       if(trim($this->db104_aceitatexto) == null ){ 
         $this->erro_sql = " Campo Aceita Texto nao Informado.";
         $this->erro_campo = "db104_aceitatexto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db104_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db104_peso"])){ 
        if(trim($this->db104_peso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db104_peso"])){ 
           $this->db104_peso = "0" ; 
        } 
       $sql  .= $virgula." db104_peso = $this->db104_peso ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db104_sequencial!=null){
       $sql .= " db104_sequencial = $this->db104_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db104_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16920,'$this->db104_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db104_sequencial"]) || $this->db104_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2985,16920,'".AddSlashes(pg_result($resaco,$conresaco,'db104_sequencial'))."','$this->db104_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db104_avaliacaopergunta"]) || $this->db104_avaliacaopergunta != "")
           $resac = db_query("insert into db_acount values($acount,2985,16921,'".AddSlashes(pg_result($resaco,$conresaco,'db104_avaliacaopergunta'))."','$this->db104_avaliacaopergunta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db104_descricao"]) || $this->db104_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2985,16922,'".AddSlashes(pg_result($resaco,$conresaco,'db104_descricao'))."','$this->db104_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db104_identificador"]) || $this->db104_identificador != "")
           $resac = db_query("insert into db_acount values($acount,2985,19379,'".AddSlashes(pg_result($resaco,$conresaco,'db104_identificador'))."','$this->db104_identificador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db104_aceitatexto"]) || $this->db104_aceitatexto != "")
           $resac = db_query("insert into db_acount values($acount,2985,16923,'".AddSlashes(pg_result($resaco,$conresaco,'db104_aceitatexto'))."','$this->db104_aceitatexto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db104_peso"]) || $this->db104_peso != "")
           $resac = db_query("insert into db_acount values($acount,2985,19381,'".AddSlashes(pg_result($resaco,$conresaco,'db104_peso'))."','$this->db104_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o Pergunta Op��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db104_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o Pergunta Op��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db104_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db104_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db104_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db104_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16920,'$db104_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2985,16920,'','".AddSlashes(pg_result($resaco,$iresaco,'db104_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2985,16921,'','".AddSlashes(pg_result($resaco,$iresaco,'db104_avaliacaopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2985,16922,'','".AddSlashes(pg_result($resaco,$iresaco,'db104_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2985,19379,'','".AddSlashes(pg_result($resaco,$iresaco,'db104_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2985,16923,'','".AddSlashes(pg_result($resaco,$iresaco,'db104_aceitatexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2985,19381,'','".AddSlashes(pg_result($resaco,$iresaco,'db104_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avaliacaoperguntaopcao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db104_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db104_sequencial = $db104_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o Pergunta Op��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db104_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o Pergunta Op��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db104_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db104_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoperguntaopcao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoperguntaopcao ";
     $sql .= "      inner join avaliacaopergunta  on  avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta";
     $sql .= "      inner join avaliacaotiporesposta  on  avaliacaotiporesposta.db105_sequencial = avaliacaopergunta.db103_avaliacaotiporesposta";
     $sql .= "      inner join avaliacaogrupopergunta  on  avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta";
     $sql2 = "";
     if($dbwhere==""){
       if($db104_sequencial!=null ){
         $sql2 .= " where avaliacaoperguntaopcao.db104_sequencial = $db104_sequencial "; 
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
   function sql_query_file ( $db104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoperguntaopcao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db104_sequencial!=null ){
         $sql2 .= " where avaliacaoperguntaopcao.db104_sequencial = $db104_sequencial "; 
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
   function sql_query_respostas($db104_sequencial = null, $campos = "*", $ordem=null, $iAvaliacao, $dbwhere = "") {
     
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
     $sql .= " from avaliacaoperguntaopcao ";
     $sql .= "      inner join avaliacaopergunta              on db103_sequencial = db104_avaliacaopergunta";
     $sql .= "      inner join avaliacaogrupopergunta         on db102_sequencial = db103_avaliacaogrupopergunta";
     $sql .= "      left  join avaliacaoresposta              on db102_sequencial = db106_avaliacaoperguntaopcao";
     $sql .= "      left  join avaliacaogrupoperguntaresposta on db106_sequencial = db108_avaliacaoresposta";
     $sql .= "                                               and db108_avaliacaogruporesposta = {$iAvaliacao}";
     $sql2 = "";
     if($dbwhere==""){
       if($db104_sequencial!=null ){
         $sql2 .= " where avaliacaoperguntaopcao.db104_sequencial = $db104_sequencial "; 
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
  
 function sql_query_tipo_pergunta ( $db104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoperguntaopcao ";
     $sql .= "      inner join avaliacaopergunta  on  avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta";
     $sql .= "      inner join avaliacaotiporesposta  on  db105_sequencial = db103_avaliacaotiporesposta ";
     $sql .= "      inner join avaliacaogrupopergunta  on  avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta";
     $sql2 = "";
     if($dbwhere==""){
       if($db104_sequencial!=null ){
         $sql2 .= " where avaliacaoperguntaopcao.db104_sequencial = $db104_sequencial "; 
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