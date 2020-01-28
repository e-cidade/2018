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

//MODULO: itbi
//CLASSE DA ENTIDADE itbitransacao
class cl_itbitransacao { 
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
   var $it04_codigo = 0; 
   var $it04_descr = null; 
   var $it04_desconto = 0; 
   var $it04_obs = null; 
   var $it04_datalimite_dia = null; 
   var $it04_datalimite_mes = null; 
   var $it04_datalimite_ano = null; 
   var $it04_datalimite = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it04_codigo = int8 = Código 
                 it04_descr = varchar(100) = Descrição 
                 it04_desconto = float8 = Desconto (%) 
                 it04_obs = text = Observações 
                 it04_datalimite = date = Data Limite 
                 ";
   //funcao construtor da classe 
   function cl_itbitransacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbitransacao"); 
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
       $this->it04_codigo = ($this->it04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_codigo"]:$this->it04_codigo);
       $this->it04_descr = ($this->it04_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_descr"]:$this->it04_descr);
       $this->it04_desconto = ($this->it04_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_desconto"]:$this->it04_desconto);
       $this->it04_obs = ($this->it04_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_obs"]:$this->it04_obs);
       if($this->it04_datalimite == ""){
         $this->it04_datalimite_dia = ($this->it04_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_datalimite_dia"]:$this->it04_datalimite_dia);
         $this->it04_datalimite_mes = ($this->it04_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_datalimite_mes"]:$this->it04_datalimite_mes);
         $this->it04_datalimite_ano = ($this->it04_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_datalimite_ano"]:$this->it04_datalimite_ano);
         if($this->it04_datalimite_dia != ""){
            $this->it04_datalimite = $this->it04_datalimite_ano."-".$this->it04_datalimite_mes."-".$this->it04_datalimite_dia;
         }
       }
     }else{
       $this->it04_codigo = ($this->it04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["it04_codigo"]:$this->it04_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($it04_codigo){ 
      $this->atualizacampos();
     if($this->it04_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "it04_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it04_desconto == null ){ 
       $this->erro_sql = " Campo Desconto (%) nao Informado.";
       $this->erro_campo = "it04_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it04_datalimite == null ){ 
       $this->it04_datalimite = "null";
     }
     if($it04_codigo == "" || $it04_codigo == null ){
       $result = db_query("select nextval('itbitransacao_it04_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbitransacao_it04_codigo_seq do campo: it04_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it04_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbitransacao_it04_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $it04_codigo)){
         $this->erro_sql = " Campo it04_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it04_codigo = $it04_codigo; 
       }
     }
     if(($this->it04_codigo == null) || ($this->it04_codigo == "") ){ 
       $this->erro_sql = " Campo it04_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbitransacao(
                                       it04_codigo 
                                      ,it04_descr 
                                      ,it04_desconto 
                                      ,it04_obs 
                                      ,it04_datalimite 
                       )
                values (
                                $this->it04_codigo 
                               ,'$this->it04_descr' 
                               ,$this->it04_desconto 
                               ,'$this->it04_obs' 
                               ,".($this->it04_datalimite == "null" || $this->it04_datalimite == ""?"null":"'".$this->it04_datalimite."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de transação ($this->it04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de transação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de transação ($this->it04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it04_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it04_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5396,'$this->it04_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,795,5396,'','".AddSlashes(pg_result($resaco,0,'it04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,795,5397,'','".AddSlashes(pg_result($resaco,0,'it04_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,795,5407,'','".AddSlashes(pg_result($resaco,0,'it04_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,795,5408,'','".AddSlashes(pg_result($resaco,0,'it04_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,795,13522,'','".AddSlashes(pg_result($resaco,0,'it04_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it04_codigo=null) { 
      $this->atualizacampos();
     $sql = " update itbitransacao set ";
     $virgula = "";
     if(trim($this->it04_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it04_codigo"])){ 
       $sql  .= $virgula." it04_codigo = $this->it04_codigo ";
       $virgula = ",";
       if(trim($this->it04_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "it04_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it04_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it04_descr"])){ 
       $sql  .= $virgula." it04_descr = '$this->it04_descr' ";
       $virgula = ",";
       if(trim($this->it04_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "it04_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it04_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it04_desconto"])){ 
       $sql  .= $virgula." it04_desconto = $this->it04_desconto ";
       $virgula = ",";
       if(trim($this->it04_desconto) == null ){ 
         $this->erro_sql = " Campo Desconto (%) nao Informado.";
         $this->erro_campo = "it04_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it04_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it04_obs"])){ 
       $sql  .= $virgula." it04_obs = '$this->it04_obs' ";
       $virgula = ",";
     }
     if(trim($this->it04_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it04_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it04_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." it04_datalimite = '$this->it04_datalimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it04_datalimite_dia"])){ 
         $sql  .= $virgula." it04_datalimite = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($it04_codigo!=null){
       $sql .= " it04_codigo = $this->it04_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it04_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5396,'$this->it04_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it04_codigo"]))
           $resac = db_query("insert into db_acount values($acount,795,5396,'".AddSlashes(pg_result($resaco,$conresaco,'it04_codigo'))."','$this->it04_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it04_descr"]))
           $resac = db_query("insert into db_acount values($acount,795,5397,'".AddSlashes(pg_result($resaco,$conresaco,'it04_descr'))."','$this->it04_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it04_desconto"]))
           $resac = db_query("insert into db_acount values($acount,795,5407,'".AddSlashes(pg_result($resaco,$conresaco,'it04_desconto'))."','$this->it04_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it04_obs"]))
           $resac = db_query("insert into db_acount values($acount,795,5408,'".AddSlashes(pg_result($resaco,$conresaco,'it04_obs'))."','$this->it04_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it04_datalimite"]))
           $resac = db_query("insert into db_acount values($acount,795,13522,'".AddSlashes(pg_result($resaco,$conresaco,'it04_datalimite'))."','$this->it04_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de transação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de transação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it04_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it04_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5396,'$it04_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,795,5396,'','".AddSlashes(pg_result($resaco,$iresaco,'it04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,795,5397,'','".AddSlashes(pg_result($resaco,$iresaco,'it04_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,795,5407,'','".AddSlashes(pg_result($resaco,$iresaco,'it04_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,795,5408,'','".AddSlashes(pg_result($resaco,$iresaco,'it04_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,795,13522,'','".AddSlashes(pg_result($resaco,$iresaco,'it04_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbitransacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it04_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it04_codigo = $it04_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de transação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de transação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it04_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbitransacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbitransacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($it04_codigo!=null ){
         $sql2 .= " where itbitransacao.it04_codigo = $it04_codigo "; 
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
   function sql_query_file ( $it04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbitransacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($it04_codigo!=null ){
         $sql2 .= " where itbitransacao.it04_codigo = $it04_codigo "; 
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