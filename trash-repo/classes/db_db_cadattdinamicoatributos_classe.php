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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_cadattdinamicoatributos
class cl_db_cadattdinamicoatributos { 
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
   var $db109_sequencial = 0; 
   var $db109_db_cadattdinamico = 0; 
   var $db109_codcam = 0; 
   var $db109_descricao = null; 
   var $db109_valordefault = null; 
   var $db109_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db109_sequencial = int4 = Código Sequencial 
                 db109_db_cadattdinamico = int4 = Código Atributo Dinâmico 
                 db109_codcam = int4 = Código Campo 
                 db109_descricao = varchar(100) = Descrição 
                 db109_valordefault = varchar(100) = Valor Default 
                 db109_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_db_cadattdinamicoatributos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cadattdinamicoatributos"); 
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
       $this->db109_sequencial = ($this->db109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_sequencial"]:$this->db109_sequencial);
       $this->db109_db_cadattdinamico = ($this->db109_db_cadattdinamico == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_db_cadattdinamico"]:$this->db109_db_cadattdinamico);
       $this->db109_codcam = ($this->db109_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_codcam"]:$this->db109_codcam);
       $this->db109_descricao = ($this->db109_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_descricao"]:$this->db109_descricao);
       $this->db109_valordefault = ($this->db109_valordefault == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_valordefault"]:$this->db109_valordefault);
       $this->db109_tipo = ($this->db109_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_tipo"]:$this->db109_tipo);
     }else{
       $this->db109_sequencial = ($this->db109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_sequencial"]:$this->db109_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db109_sequencial){ 
      $this->atualizacampos();
     if($this->db109_db_cadattdinamico == null ){ 
       $this->erro_sql = " Campo Código Atributo Dinâmico nao Informado.";
       $this->erro_campo = "db109_db_cadattdinamico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db109_codcam == null ){
       $this->db109_codcam = 'null';  
     }
     if($this->db109_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db109_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db109_valordefault == null ){ 
       $this->db109_valordefault = '';
     }
     if($this->db109_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "db109_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db109_sequencial == "" || $db109_sequencial == null ){
       $result = db_query("select nextval('db_cadattdinamicoatributos_db109_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cadattdinamicoatributos_db109_sequencial_seq do campo: db109_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db109_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_cadattdinamicoatributos_db109_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db109_sequencial)){
         $this->erro_sql = " Campo db109_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db109_sequencial = $db109_sequencial; 
       }
     }
     if(($this->db109_sequencial == null) || ($this->db109_sequencial == "") ){ 
       $this->erro_sql = " Campo db109_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cadattdinamicoatributos(
                                       db109_sequencial 
                                      ,db109_db_cadattdinamico 
                                      ,db109_codcam 
                                      ,db109_descricao 
                                      ,db109_valordefault 
                                      ,db109_tipo 
                       )
                values (
                                $this->db109_sequencial 
                               ,$this->db109_db_cadattdinamico 
                               ,$this->db109_codcam 
                               ,'$this->db109_descricao' 
                               ,'$this->db109_valordefault' 
                               ,$this->db109_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "db_cadattdinamicoatributos ($this->db109_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "db_cadattdinamicoatributos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "db_cadattdinamicoatributos ($this->db109_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db109_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17885,'$this->db109_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3163,17885,'','".AddSlashes(pg_result($resaco,0,'db109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3163,17886,'','".AddSlashes(pg_result($resaco,0,'db109_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3163,17887,'','".AddSlashes(pg_result($resaco,0,'db109_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3163,17888,'','".AddSlashes(pg_result($resaco,0,'db109_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3163,17889,'','".AddSlashes(pg_result($resaco,0,'db109_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3163,17890,'','".AddSlashes(pg_result($resaco,0,'db109_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db109_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_cadattdinamicoatributos set ";
     $virgula = "";
     if(trim($this->db109_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_sequencial"])){ 
       $sql  .= $virgula." db109_sequencial = $this->db109_sequencial ";
       $virgula = ",";
       if(trim($this->db109_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db109_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_db_cadattdinamico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_db_cadattdinamico"])){ 
       $sql  .= $virgula." db109_db_cadattdinamico = $this->db109_db_cadattdinamico ";
       $virgula = ",";
       if(trim($this->db109_db_cadattdinamico) == null ){ 
         $this->erro_sql = " Campo Código Atributo Dinâmico nao Informado.";
         $this->erro_campo = "db109_db_cadattdinamico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_codcam"])){ 
       $sql  .= $virgula." db109_codcam = $this->db109_codcam ";
       $virgula = ",";
     }
     if(trim($this->db109_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_descricao"])){ 
       $sql  .= $virgula." db109_descricao = '$this->db109_descricao' ";
       $virgula = ",";
       if(trim($this->db109_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db109_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_valordefault)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_valordefault"])){ 
       $sql  .= $virgula." db109_valordefault = '$this->db109_valordefault' ";
       $virgula = ",";
     }
     if(trim($this->db109_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_tipo"])){ 
       $sql  .= $virgula." db109_tipo = $this->db109_tipo ";
       $virgula = ",";
       if(trim($this->db109_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "db109_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db109_sequencial!=null){
       $sql .= " db109_sequencial = $this->db109_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db109_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17885,'$this->db109_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db109_sequencial"]) || $this->db109_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3163,17885,'".AddSlashes(pg_result($resaco,$conresaco,'db109_sequencial'))."','$this->db109_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db109_db_cadattdinamico"]) || $this->db109_db_cadattdinamico != "")
           $resac = db_query("insert into db_acount values($acount,3163,17886,'".AddSlashes(pg_result($resaco,$conresaco,'db109_db_cadattdinamico'))."','$this->db109_db_cadattdinamico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db109_codcam"]) || $this->db109_codcam != "")
           $resac = db_query("insert into db_acount values($acount,3163,17887,'".AddSlashes(pg_result($resaco,$conresaco,'db109_codcam'))."','$this->db109_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db109_descricao"]) || $this->db109_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3163,17888,'".AddSlashes(pg_result($resaco,$conresaco,'db109_descricao'))."','$this->db109_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db109_valordefault"]) || $this->db109_valordefault != "")
           $resac = db_query("insert into db_acount values($acount,3163,17889,'".AddSlashes(pg_result($resaco,$conresaco,'db109_valordefault'))."','$this->db109_valordefault',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db109_tipo"]) || $this->db109_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3163,17890,'".AddSlashes(pg_result($resaco,$conresaco,'db109_tipo'))."','$this->db109_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_cadattdinamicoatributos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_cadattdinamicoatributos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db109_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db109_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17885,'$db109_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3163,17885,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17886,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17887,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17888,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17889,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17890,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_cadattdinamicoatributos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db109_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db109_sequencial = $db109_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_cadattdinamicoatributos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_cadattdinamicoatributos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db109_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cadattdinamicoatributos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db109_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cadattdinamicoatributos ";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = db_cadattdinamicoatributos.db109_db_cadattdinamico";
     $sql2 = "";
     if($dbwhere==""){
       if($db109_sequencial!=null ){
         $sql2 .= " where db_cadattdinamicoatributos.db109_sequencial = $db109_sequencial "; 
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
   function sql_query_file ( $db109_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cadattdinamicoatributos ";
     $sql2 = "";
     if($dbwhere==""){
       if($db109_sequencial!=null ){
         $sql2 .= " where db_cadattdinamicoatributos.db109_sequencial = $db109_sequencial "; 
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