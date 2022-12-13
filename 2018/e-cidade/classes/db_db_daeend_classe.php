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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_daeend
class cl_db_daeend { 
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
   var $w05_codigo = 0; 
   var $w05_rua = null; 
   var $w05_numero = 0; 
   var $w05_compl = null; 
   var $w05_bairro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w05_codigo = int4 = Código 
                 w05_rua = varchar(40) = Rua 
                 w05_numero = int4 = Número 
                 w05_compl = varchar(15) = Complemento 
                 w05_bairro = varchar(40) = Bairro 
                 ";
   //funcao construtor da classe 
   function cl_db_daeend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_daeend"); 
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
       $this->w05_codigo = ($this->w05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w05_codigo"]:$this->w05_codigo);
       $this->w05_rua = ($this->w05_rua == ""?@$GLOBALS["HTTP_POST_VARS"]["w05_rua"]:$this->w05_rua);
       $this->w05_numero = ($this->w05_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["w05_numero"]:$this->w05_numero);
       $this->w05_compl = ($this->w05_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["w05_compl"]:$this->w05_compl);
       $this->w05_bairro = ($this->w05_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["w05_bairro"]:$this->w05_bairro);
     }else{
       $this->w05_codigo = ($this->w05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w05_codigo"]:$this->w05_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($w05_codigo){ 
      $this->atualizacampos();
     if($this->w05_rua == null ){ 
       $this->erro_sql = " Campo Rua nao Informado.";
       $this->erro_campo = "w05_rua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w05_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "w05_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w05_compl == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "w05_compl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w05_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "w05_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->w05_codigo = $w05_codigo; 
     if(($this->w05_codigo == null) || ($this->w05_codigo == "") ){ 
       $this->erro_sql = " Campo w05_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_daeend(
                                       w05_codigo 
                                      ,w05_rua 
                                      ,w05_numero 
                                      ,w05_compl 
                                      ,w05_bairro 
                       )
                values (
                                $this->w05_codigo 
                               ,'$this->w05_rua' 
                               ,$this->w05_numero 
                               ,'$this->w05_compl' 
                               ,'$this->w05_bairro' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de endereços do dae ($this->w05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de endereços do dae já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de endereços do dae ($this->w05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w05_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w05_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4638,'$this->w05_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,608,4638,'','".AddSlashes(pg_result($resaco,0,'w05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,608,3618,'','".AddSlashes(pg_result($resaco,0,'w05_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,608,3619,'','".AddSlashes(pg_result($resaco,0,'w05_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,608,3620,'','".AddSlashes(pg_result($resaco,0,'w05_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,608,3621,'','".AddSlashes(pg_result($resaco,0,'w05_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w05_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_daeend set ";
     $virgula = "";
     if(trim($this->w05_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w05_codigo"])){ 
       $sql  .= $virgula." w05_codigo = $this->w05_codigo ";
       $virgula = ",";
       if(trim($this->w05_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "w05_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w05_rua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w05_rua"])){ 
       $sql  .= $virgula." w05_rua = '$this->w05_rua' ";
       $virgula = ",";
       if(trim($this->w05_rua) == null ){ 
         $this->erro_sql = " Campo Rua nao Informado.";
         $this->erro_campo = "w05_rua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w05_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w05_numero"])){ 
       $sql  .= $virgula." w05_numero = $this->w05_numero ";
       $virgula = ",";
       if(trim($this->w05_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "w05_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w05_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w05_compl"])){ 
       $sql  .= $virgula." w05_compl = '$this->w05_compl' ";
       $virgula = ",";
       if(trim($this->w05_compl) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "w05_compl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w05_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w05_bairro"])){ 
       $sql  .= $virgula." w05_bairro = '$this->w05_bairro' ";
       $virgula = ",";
       if(trim($this->w05_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "w05_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w05_codigo!=null){
       $sql .= " w05_codigo = $this->w05_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w05_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4638,'$this->w05_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w05_codigo"]))
           $resac = db_query("insert into db_acount values($acount,608,4638,'".AddSlashes(pg_result($resaco,$conresaco,'w05_codigo'))."','$this->w05_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w05_rua"]))
           $resac = db_query("insert into db_acount values($acount,608,3618,'".AddSlashes(pg_result($resaco,$conresaco,'w05_rua'))."','$this->w05_rua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w05_numero"]))
           $resac = db_query("insert into db_acount values($acount,608,3619,'".AddSlashes(pg_result($resaco,$conresaco,'w05_numero'))."','$this->w05_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w05_compl"]))
           $resac = db_query("insert into db_acount values($acount,608,3620,'".AddSlashes(pg_result($resaco,$conresaco,'w05_compl'))."','$this->w05_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w05_bairro"]))
           $resac = db_query("insert into db_acount values($acount,608,3621,'".AddSlashes(pg_result($resaco,$conresaco,'w05_bairro'))."','$this->w05_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de endereços do dae nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de endereços do dae nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w05_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w05_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4638,'$w05_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,608,4638,'','".AddSlashes(pg_result($resaco,$iresaco,'w05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,608,3618,'','".AddSlashes(pg_result($resaco,$iresaco,'w05_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,608,3619,'','".AddSlashes(pg_result($resaco,$iresaco,'w05_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,608,3620,'','".AddSlashes(pg_result($resaco,$iresaco,'w05_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,608,3621,'','".AddSlashes(pg_result($resaco,$iresaco,'w05_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_daeend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w05_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w05_codigo = $w05_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de endereços do dae nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de endereços do dae nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w05_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_daeend";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>