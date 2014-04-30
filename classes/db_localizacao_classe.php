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

//MODULO: biblioteca
//CLASSE DA ENTIDADE localizacao
class cl_localizacao { 
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
   var $bi09_codigo = 0; 
   var $bi09_nome = null; 
   var $bi09_biblioteca = 0; 
   var $bi09_capacidade = 0; 
   var $bi09_abrev = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi09_codigo = int8 = Código 
                 bi09_nome = char(30) = Localização 
                 bi09_biblioteca = int8 = Biblioteca 
                 bi09_capacidade = int4 = Capacidade de exemplares 
                 bi09_abrev = char(10) = Abreviatura 
                 ";
   //funcao construtor da classe 
   function cl_localizacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("localizacao"); 
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
       $this->bi09_codigo = ($this->bi09_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi09_codigo"]:$this->bi09_codigo);
       $this->bi09_nome = ($this->bi09_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["bi09_nome"]:$this->bi09_nome);
       $this->bi09_biblioteca = ($this->bi09_biblioteca == ""?@$GLOBALS["HTTP_POST_VARS"]["bi09_biblioteca"]:$this->bi09_biblioteca);
       $this->bi09_capacidade = ($this->bi09_capacidade == ""?@$GLOBALS["HTTP_POST_VARS"]["bi09_capacidade"]:$this->bi09_capacidade);
       $this->bi09_abrev = ($this->bi09_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["bi09_abrev"]:$this->bi09_abrev);
     }else{
       $this->bi09_codigo = ($this->bi09_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi09_codigo"]:$this->bi09_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi09_codigo){ 
      $this->atualizacampos();
     if($this->bi09_nome == null ){ 
       $this->erro_sql = " Campo Localização nao Informado.";
       $this->erro_campo = "bi09_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi09_biblioteca == null ){ 
       $this->erro_sql = " Campo Biblioteca nao Informado.";
       $this->erro_campo = "bi09_biblioteca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi09_capacidade == null ){ 
       $this->erro_sql = " Campo Capacidade de exemplares nao Informado.";
       $this->erro_campo = "bi09_capacidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi09_abrev == null ){ 
       $this->erro_sql = " Campo Abreviatura nao Informado.";
       $this->erro_campo = "bi09_abrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi09_codigo == "" || $bi09_codigo == null ){
       $result = db_query("select nextval('localizacao_bi09_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: localizacao_bi09_codigo_seq do campo: bi09_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi09_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from localizacao_bi09_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi09_codigo)){
         $this->erro_sql = " Campo bi09_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi09_codigo = $bi09_codigo; 
       }
     }
     if(($this->bi09_codigo == null) || ($this->bi09_codigo == "") ){ 
       $this->erro_sql = " Campo bi09_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into localizacao(
                                       bi09_codigo 
                                      ,bi09_nome 
                                      ,bi09_biblioteca 
                                      ,bi09_capacidade 
                                      ,bi09_abrev 
                       )
                values (
                                $this->bi09_codigo 
                               ,'$this->bi09_nome' 
                               ,$this->bi09_biblioteca 
                               ,$this->bi09_capacidade 
                               ,'$this->bi09_abrev' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Localização ($this->bi09_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Localização já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Localização ($this->bi09_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi09_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi09_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008137,'$this->bi09_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1008017,1008137,'','".AddSlashes(pg_result($resaco,0,'bi09_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008017,1008138,'','".AddSlashes(pg_result($resaco,0,'bi09_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008017,1008930,'','".AddSlashes(pg_result($resaco,0,'bi09_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008017,11714,'','".AddSlashes(pg_result($resaco,0,'bi09_capacidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008017,11713,'','".AddSlashes(pg_result($resaco,0,'bi09_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bi09_codigo=null) { 
      $this->atualizacampos();
     $sql = " update localizacao set ";
     $virgula = "";
     if(trim($this->bi09_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi09_codigo"])){ 
       $sql  .= $virgula." bi09_codigo = $this->bi09_codigo ";
       $virgula = ",";
       if(trim($this->bi09_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi09_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi09_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi09_nome"])){ 
       $sql  .= $virgula." bi09_nome = '$this->bi09_nome' ";
       $virgula = ",";
       if(trim($this->bi09_nome) == null ){ 
         $this->erro_sql = " Campo Localização nao Informado.";
         $this->erro_campo = "bi09_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi09_biblioteca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi09_biblioteca"])){ 
       $sql  .= $virgula." bi09_biblioteca = $this->bi09_biblioteca ";
       $virgula = ",";
       if(trim($this->bi09_biblioteca) == null ){ 
         $this->erro_sql = " Campo Biblioteca nao Informado.";
         $this->erro_campo = "bi09_biblioteca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi09_capacidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi09_capacidade"])){ 
       $sql  .= $virgula." bi09_capacidade = $this->bi09_capacidade ";
       $virgula = ",";
       if(trim($this->bi09_capacidade) == null ){ 
         $this->erro_sql = " Campo Capacidade de exemplares nao Informado.";
         $this->erro_campo = "bi09_capacidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi09_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi09_abrev"])){ 
       $sql  .= $virgula." bi09_abrev = '$this->bi09_abrev' ";
       $virgula = ",";
       if(trim($this->bi09_abrev) == null ){ 
         $this->erro_sql = " Campo Abreviatura nao Informado.";
         $this->erro_campo = "bi09_abrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi09_codigo!=null){
       $sql .= " bi09_codigo = $this->bi09_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi09_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008137,'$this->bi09_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi09_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1008017,1008137,'".AddSlashes(pg_result($resaco,$conresaco,'bi09_codigo'))."','$this->bi09_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi09_nome"]))
           $resac = db_query("insert into db_acount values($acount,1008017,1008138,'".AddSlashes(pg_result($resaco,$conresaco,'bi09_nome'))."','$this->bi09_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi09_biblioteca"]))
           $resac = db_query("insert into db_acount values($acount,1008017,1008930,'".AddSlashes(pg_result($resaco,$conresaco,'bi09_biblioteca'))."','$this->bi09_biblioteca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi09_capacidade"]))
           $resac = db_query("insert into db_acount values($acount,1008017,11714,'".AddSlashes(pg_result($resaco,$conresaco,'bi09_capacidade'))."','$this->bi09_capacidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi09_abrev"]))
           $resac = db_query("insert into db_acount values($acount,1008017,11713,'".AddSlashes(pg_result($resaco,$conresaco,'bi09_abrev'))."','$this->bi09_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Localização nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi09_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Localização nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi09_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi09_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bi09_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi09_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008137,'$bi09_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1008017,1008137,'','".AddSlashes(pg_result($resaco,$iresaco,'bi09_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008017,1008138,'','".AddSlashes(pg_result($resaco,$iresaco,'bi09_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008017,1008930,'','".AddSlashes(pg_result($resaco,$iresaco,'bi09_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008017,11714,'','".AddSlashes(pg_result($resaco,$iresaco,'bi09_capacidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008017,11713,'','".AddSlashes(pg_result($resaco,$iresaco,'bi09_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from localizacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi09_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi09_codigo = $bi09_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Localização nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi09_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Localização nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi09_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi09_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:localizacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi09_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localizacao ";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = localizacao.bi09_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($bi09_codigo!=null ){
         $sql2 .= " where localizacao.bi09_codigo = $bi09_codigo "; 
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
   function sql_query_file ( $bi09_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localizacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi09_codigo!=null ){
         $sql2 .= " where localizacao.bi09_codigo = $bi09_codigo "; 
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