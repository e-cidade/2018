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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptubaseregimovel
class cl_iptubaseregimovel { 
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
   var $j04_sequencial = 0; 
   var $j04_setorregimovel = 0; 
   var $j04_matric = 0; 
   var $j04_matricregimo = null; 
   var $j04_quadraregimo = null; 
   var $j04_loteregimo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j04_sequencial = int4 = Código 
                 j04_setorregimovel = int4 = Registro de Imóveis 
                 j04_matric = int4 = Matrícula 
                 j04_matricregimo = varchar(20) = Matrícula do registro de imóveis 
                 j04_quadraregimo = varchar(20) = Quadra do registro de imóveis 
                 j04_loteregimo = varchar(20) = Lote do registro de imóveis 
                 ";
   //funcao construtor da classe 
   function cl_iptubaseregimovel() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptubaseregimovel"); 
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
       $this->j04_sequencial = ($this->j04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_sequencial"]:$this->j04_sequencial);
       $this->j04_setorregimovel = ($this->j04_setorregimovel == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_setorregimovel"]:$this->j04_setorregimovel);
       $this->j04_matric = ($this->j04_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_matric"]:$this->j04_matric);
       $this->j04_matricregimo = ($this->j04_matricregimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_matricregimo"]:$this->j04_matricregimo);
       $this->j04_quadraregimo = ($this->j04_quadraregimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_quadraregimo"]:$this->j04_quadraregimo);
       $this->j04_loteregimo = ($this->j04_loteregimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_loteregimo"]:$this->j04_loteregimo);
     }else{
       $this->j04_sequencial = ($this->j04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j04_sequencial"]:$this->j04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j04_sequencial){ 
      $this->atualizacampos();
     if($this->j04_setorregimovel == null ){ 
       $this->erro_sql = " Campo Registro de Imóveis nao Informado.";
       $this->erro_campo = "j04_setorregimovel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j04_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j04_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j04_sequencial == "" || $j04_sequencial == null ){
       $result = db_query("select nextval('iptubaseregimovel_j04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptubaseregimovel_j04_sequencial_seq do campo: j04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptubaseregimovel_j04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j04_sequencial)){
         $this->erro_sql = " Campo j04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j04_sequencial = $j04_sequencial; 
       }
     }
     if(($this->j04_sequencial == null) || ($this->j04_sequencial == "") ){ 
       $this->erro_sql = " Campo j04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptubaseregimovel(
                                       j04_sequencial 
                                      ,j04_setorregimovel 
                                      ,j04_matric 
                                      ,j04_matricregimo 
                                      ,j04_quadraregimo 
                                      ,j04_loteregimo 
                       )
                values (
                                $this->j04_sequencial 
                               ,$this->j04_setorregimovel 
                               ,$this->j04_matric 
                               ,'$this->j04_matricregimo' 
                               ,'$this->j04_quadraregimo' 
                               ,'$this->j04_loteregimo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados do registro de imóveis ($this->j04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados do registro de imóveis já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados do registro de imóveis ($this->j04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j04_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10491,'$this->j04_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1813,10491,'','".AddSlashes(pg_result($resaco,0,'j04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1813,10492,'','".AddSlashes(pg_result($resaco,0,'j04_setorregimovel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1813,10493,'','".AddSlashes(pg_result($resaco,0,'j04_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1813,10494,'','".AddSlashes(pg_result($resaco,0,'j04_matricregimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1813,10495,'','".AddSlashes(pg_result($resaco,0,'j04_quadraregimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1813,10496,'','".AddSlashes(pg_result($resaco,0,'j04_loteregimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptubaseregimovel set ";
     $virgula = "";
     if(trim($this->j04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j04_sequencial"])){ 
       $sql  .= $virgula." j04_sequencial = $this->j04_sequencial ";
       $virgula = ",";
       if(trim($this->j04_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "j04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j04_setorregimovel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j04_setorregimovel"])){ 
       $sql  .= $virgula." j04_setorregimovel = $this->j04_setorregimovel ";
       $virgula = ",";
       if(trim($this->j04_setorregimovel) == null ){ 
         $this->erro_sql = " Campo Registro de Imóveis nao Informado.";
         $this->erro_campo = "j04_setorregimovel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j04_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j04_matric"])){ 
       $sql  .= $virgula." j04_matric = $this->j04_matric ";
       $virgula = ",";
       if(trim($this->j04_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j04_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j04_matricregimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j04_matricregimo"])){ 
       $sql  .= $virgula." j04_matricregimo = '$this->j04_matricregimo' ";
       $virgula = ",";
     }
     if(trim($this->j04_quadraregimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j04_quadraregimo"])){ 
       $sql  .= $virgula." j04_quadraregimo = '$this->j04_quadraregimo' ";
       $virgula = ",";
     }
     if(trim($this->j04_loteregimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j04_loteregimo"])){ 
       $sql  .= $virgula." j04_loteregimo = '$this->j04_loteregimo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j04_sequencial!=null){
       $sql .= " j04_sequencial = $this->j04_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j04_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10491,'$this->j04_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j04_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1813,10491,'".AddSlashes(pg_result($resaco,$conresaco,'j04_sequencial'))."','$this->j04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j04_setorregimovel"]))
           $resac = db_query("insert into db_acount values($acount,1813,10492,'".AddSlashes(pg_result($resaco,$conresaco,'j04_setorregimovel'))."','$this->j04_setorregimovel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j04_matric"]))
           $resac = db_query("insert into db_acount values($acount,1813,10493,'".AddSlashes(pg_result($resaco,$conresaco,'j04_matric'))."','$this->j04_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j04_matricregimo"]))
           $resac = db_query("insert into db_acount values($acount,1813,10494,'".AddSlashes(pg_result($resaco,$conresaco,'j04_matricregimo'))."','$this->j04_matricregimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j04_quadraregimo"]))
           $resac = db_query("insert into db_acount values($acount,1813,10495,'".AddSlashes(pg_result($resaco,$conresaco,'j04_quadraregimo'))."','$this->j04_quadraregimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j04_loteregimo"]))
           $resac = db_query("insert into db_acount values($acount,1813,10496,'".AddSlashes(pg_result($resaco,$conresaco,'j04_loteregimo'))."','$this->j04_loteregimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do registro de imóveis nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do registro de imóveis nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j04_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j04_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10491,'$j04_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1813,10491,'','".AddSlashes(pg_result($resaco,$iresaco,'j04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1813,10492,'','".AddSlashes(pg_result($resaco,$iresaco,'j04_setorregimovel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1813,10493,'','".AddSlashes(pg_result($resaco,$iresaco,'j04_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1813,10494,'','".AddSlashes(pg_result($resaco,$iresaco,'j04_matricregimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1813,10495,'','".AddSlashes(pg_result($resaco,$iresaco,'j04_quadraregimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1813,10496,'','".AddSlashes(pg_result($resaco,$iresaco,'j04_loteregimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptubaseregimovel
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j04_sequencial = $j04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do registro de imóveis nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do registro de imóveis nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptubaseregimovel";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptubaseregimovel ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptubaseregimovel.j04_matric";
     $sql .= "      inner join setorregimovel  on  setorregimovel.j69_sequencial = iptubaseregimovel.j04_setorregimovel";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j04_sequencial!=null ){
         $sql2 .= " where iptubaseregimovel.j04_sequencial = $j04_sequencial "; 
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
   function sql_query_file ( $j04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptubaseregimovel ";
     $sql2 = "";
     if($dbwhere==""){
       if($j04_sequencial!=null ){
         $sql2 .= " where iptubaseregimovel.j04_sequencial = $j04_sequencial "; 
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