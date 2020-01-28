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
//CLASSE DA ENTIDADE impexemplar
class cl_impexemplar { 
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
   var $bi24_codigo = 0; 
   var $bi24_biblioteca = 0; 
   var $bi24_usuario = 0; 
   var $bi24_data_dia = null; 
   var $bi24_data_mes = null; 
   var $bi24_data_ano = null; 
   var $bi24_data = null; 
   var $bi24_hora = null; 
   var $bi24_modelo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi24_codigo = int8 = Código 
                 bi24_biblioteca = int8 = Biblioteca 
                 bi24_usuario = int8 = Usuário 
                 bi24_data = date = Data 
                 bi24_hora = char(5) = Hora 
                 bi24_modelo = char(2) = Modelo 
                 ";
   //funcao construtor da classe 
   function cl_impexemplar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("impexemplar"); 
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
       $this->bi24_codigo = ($this->bi24_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_codigo"]:$this->bi24_codigo);
       $this->bi24_biblioteca = ($this->bi24_biblioteca == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_biblioteca"]:$this->bi24_biblioteca);
       $this->bi24_usuario = ($this->bi24_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_usuario"]:$this->bi24_usuario);
       if($this->bi24_data == ""){
         $this->bi24_data_dia = ($this->bi24_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_data_dia"]:$this->bi24_data_dia);
         $this->bi24_data_mes = ($this->bi24_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_data_mes"]:$this->bi24_data_mes);
         $this->bi24_data_ano = ($this->bi24_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_data_ano"]:$this->bi24_data_ano);
         if($this->bi24_data_dia != ""){
            $this->bi24_data = $this->bi24_data_ano."-".$this->bi24_data_mes."-".$this->bi24_data_dia;
         }
       }
       $this->bi24_hora = ($this->bi24_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_hora"]:$this->bi24_hora);
       $this->bi24_modelo = ($this->bi24_modelo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_modelo"]:$this->bi24_modelo);
     }else{
       $this->bi24_codigo = ($this->bi24_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi24_codigo"]:$this->bi24_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi24_codigo){ 
      $this->atualizacampos();
     if($this->bi24_biblioteca == null ){ 
       $this->erro_sql = " Campo Biblioteca nao Informado.";
       $this->erro_campo = "bi24_biblioteca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi24_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "bi24_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi24_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "bi24_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi24_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "bi24_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi24_modelo == null ){ 
       $this->erro_sql = " Campo Modelo nao Informado.";
       $this->erro_campo = "bi24_modelo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi24_codigo == "" || $bi24_codigo == null ){
       $result = db_query("select nextval('impexemplar_bi24_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: impexemplar_bi24_codigo_seq do campo: bi24_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi24_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from impexemplar_bi24_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi24_codigo)){
         $this->erro_sql = " Campo bi24_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi24_codigo = $bi24_codigo; 
       }
     }
     if(($this->bi24_codigo == null) || ($this->bi24_codigo == "") ){ 
       $this->erro_sql = " Campo bi24_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into impexemplar(
                                       bi24_codigo 
                                      ,bi24_biblioteca 
                                      ,bi24_usuario 
                                      ,bi24_data 
                                      ,bi24_hora 
                                      ,bi24_modelo 
                       )
                values (
                                $this->bi24_codigo 
                               ,$this->bi24_biblioteca 
                               ,$this->bi24_usuario 
                               ,".($this->bi24_data == "null" || $this->bi24_data == ""?"null":"'".$this->bi24_data."'")." 
                               ,'$this->bi24_hora' 
                               ,'$this->bi24_modelo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Impressão de Exemplares ($this->bi24_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Impressão de Exemplares já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Impressão de Exemplares ($this->bi24_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi24_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi24_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12248,'$this->bi24_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2129,12248,'','".AddSlashes(pg_result($resaco,0,'bi24_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2129,12251,'','".AddSlashes(pg_result($resaco,0,'bi24_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2129,12250,'','".AddSlashes(pg_result($resaco,0,'bi24_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2129,12249,'','".AddSlashes(pg_result($resaco,0,'bi24_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2129,12259,'','".AddSlashes(pg_result($resaco,0,'bi24_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2129,12296,'','".AddSlashes(pg_result($resaco,0,'bi24_modelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bi24_codigo=null) { 
      $this->atualizacampos();
     $sql = " update impexemplar set ";
     $virgula = "";
     if(trim($this->bi24_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi24_codigo"])){ 
       $sql  .= $virgula." bi24_codigo = $this->bi24_codigo ";
       $virgula = ",";
       if(trim($this->bi24_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi24_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi24_biblioteca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi24_biblioteca"])){ 
       $sql  .= $virgula." bi24_biblioteca = $this->bi24_biblioteca ";
       $virgula = ",";
       if(trim($this->bi24_biblioteca) == null ){ 
         $this->erro_sql = " Campo Biblioteca nao Informado.";
         $this->erro_campo = "bi24_biblioteca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi24_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi24_usuario"])){ 
       $sql  .= $virgula." bi24_usuario = $this->bi24_usuario ";
       $virgula = ",";
       if(trim($this->bi24_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "bi24_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi24_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi24_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi24_data_dia"] !="") ){ 
       $sql  .= $virgula." bi24_data = '$this->bi24_data' ";
       $virgula = ",";
       if(trim($this->bi24_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "bi24_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_data_dia"])){ 
         $sql  .= $virgula." bi24_data = null ";
         $virgula = ",";
         if(trim($this->bi24_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "bi24_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi24_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi24_hora"])){ 
       $sql  .= $virgula." bi24_hora = '$this->bi24_hora' ";
       $virgula = ",";
       if(trim($this->bi24_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "bi24_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi24_modelo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi24_modelo"])){ 
       $sql  .= $virgula." bi24_modelo = '$this->bi24_modelo' ";
       $virgula = ",";
       if(trim($this->bi24_modelo) == null ){ 
         $this->erro_sql = " Campo Modelo nao Informado.";
         $this->erro_campo = "bi24_modelo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi24_codigo!=null){
       $sql .= " bi24_codigo = $this->bi24_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi24_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12248,'$this->bi24_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2129,12248,'".AddSlashes(pg_result($resaco,$conresaco,'bi24_codigo'))."','$this->bi24_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_biblioteca"]))
           $resac = db_query("insert into db_acount values($acount,2129,12251,'".AddSlashes(pg_result($resaco,$conresaco,'bi24_biblioteca'))."','$this->bi24_biblioteca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2129,12250,'".AddSlashes(pg_result($resaco,$conresaco,'bi24_usuario'))."','$this->bi24_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_data"]))
           $resac = db_query("insert into db_acount values($acount,2129,12249,'".AddSlashes(pg_result($resaco,$conresaco,'bi24_data'))."','$this->bi24_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_hora"]))
           $resac = db_query("insert into db_acount values($acount,2129,12259,'".AddSlashes(pg_result($resaco,$conresaco,'bi24_hora'))."','$this->bi24_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi24_modelo"]))
           $resac = db_query("insert into db_acount values($acount,2129,12296,'".AddSlashes(pg_result($resaco,$conresaco,'bi24_modelo'))."','$this->bi24_modelo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impressão de Exemplares nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi24_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impressão de Exemplares nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi24_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi24_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bi24_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi24_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12248,'$bi24_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2129,12248,'','".AddSlashes(pg_result($resaco,$iresaco,'bi24_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2129,12251,'','".AddSlashes(pg_result($resaco,$iresaco,'bi24_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2129,12250,'','".AddSlashes(pg_result($resaco,$iresaco,'bi24_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2129,12249,'','".AddSlashes(pg_result($resaco,$iresaco,'bi24_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2129,12259,'','".AddSlashes(pg_result($resaco,$iresaco,'bi24_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2129,12296,'','".AddSlashes(pg_result($resaco,$iresaco,'bi24_modelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from impexemplar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi24_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi24_codigo = $bi24_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impressão de Exemplares nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi24_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impressão de Exemplares nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi24_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi24_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:impexemplar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi24_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from impexemplar ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = impexemplar.bi24_usuario";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = impexemplar.bi24_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($bi24_codigo!=null ){
         $sql2 .= " where impexemplar.bi24_codigo = $bi24_codigo "; 
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
   function sql_query_file ( $bi24_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from impexemplar ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi24_codigo!=null ){
         $sql2 .= " where impexemplar.bi24_codigo = $bi24_codigo "; 
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