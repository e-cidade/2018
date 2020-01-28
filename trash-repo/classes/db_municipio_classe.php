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

//MODULO: dbicms
//CLASSE DA ENTIDADE municipio
class cl_municipio { 
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
   var $cgcter = null; 
   var $nomemun = null; 
   var $endermun = null; 
   var $email = null; 
   var $telef = null; 
   var $senha = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cgcter = char(3) = Codigo do Município 
                 nomemun = varchar(100) = Nome do Município 
                 endermun = varchar(100) = Endereço Prefeitura 
                 email = varchar(50) = email 
                 telef = char(11) = Telefone 
                 senha = varchar(20) = senha 
                 ";
   //funcao construtor da classe 
   function cl_municipio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("municipio"); 
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
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->nomemun = ($this->nomemun == ""?@$GLOBALS["HTTP_POST_VARS"]["nomemun"]:$this->nomemun);
       $this->endermun = ($this->endermun == ""?@$GLOBALS["HTTP_POST_VARS"]["endermun"]:$this->endermun);
       $this->email = ($this->email == ""?@$GLOBALS["HTTP_POST_VARS"]["email"]:$this->email);
       $this->telef = ($this->telef == ""?@$GLOBALS["HTTP_POST_VARS"]["telef"]:$this->telef);
       $this->senha = ($this->senha == ""?@$GLOBALS["HTTP_POST_VARS"]["senha"]:$this->senha);
     }else{
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
     }
   }
   // funcao para inclusao
   function incluir ($cgcter){ 
      $this->atualizacampos();
     if($this->nomemun == null ){ 
       $this->erro_sql = " Campo Nome do Município nao Informado.";
       $this->erro_campo = "nomemun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->endermun == null ){ 
       $this->erro_sql = " Campo Endereço Prefeitura nao Informado.";
       $this->erro_campo = "endermun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->telef == null ){ 
       $this->erro_sql = " Campo Telefone nao Informado.";
       $this->erro_campo = "telef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->senha == null ){ 
       $this->erro_sql = " Campo senha nao Informado.";
       $this->erro_campo = "senha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cgcter = $cgcter; 
     if(($this->cgcter == null) || ($this->cgcter == "") ){ 
       $this->erro_sql = " Campo cgcter nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into municipio(
                                       cgcter 
                                      ,nomemun 
                                      ,endermun 
                                      ,email 
                                      ,telef 
                                      ,senha 
                       )
                values (
                                '$this->cgcter' 
                               ,'$this->nomemun' 
                               ,'$this->endermun' 
                               ,'$this->email' 
                               ,'$this->telef' 
                               ,'$this->senha' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Municipio ($this->cgcter) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Municipio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Municipio ($this->cgcter) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cgcter;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cgcter));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','I')");
       $resac = db_query("insert into db_acount values($acount,359,2275,'','".AddSlashes(pg_result($resaco,0,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,359,2276,'','".AddSlashes(pg_result($resaco,0,'nomemun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,359,2277,'','".AddSlashes(pg_result($resaco,0,'endermun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,359,574,'','".AddSlashes(pg_result($resaco,0,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,359,457,'','".AddSlashes(pg_result($resaco,0,'telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,359,572,'','".AddSlashes(pg_result($resaco,0,'senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cgcter=null) { 
      $this->atualizacampos();
     $sql = " update municipio set ";
     $virgula = "";
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomemun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomemun"])){ 
       $sql  .= $virgula." nomemun = '$this->nomemun' ";
       $virgula = ",";
       if(trim($this->nomemun) == null ){ 
         $this->erro_sql = " Campo Nome do Município nao Informado.";
         $this->erro_campo = "nomemun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->endermun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["endermun"])){ 
       $sql  .= $virgula." endermun = '$this->endermun' ";
       $virgula = ",";
       if(trim($this->endermun) == null ){ 
         $this->erro_sql = " Campo Endereço Prefeitura nao Informado.";
         $this->erro_campo = "endermun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["email"])){ 
       $sql  .= $virgula." email = '$this->email' ";
       $virgula = ",";
     }
     if(trim($this->telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["telef"])){ 
       $sql  .= $virgula." telef = '$this->telef' ";
       $virgula = ",";
       if(trim($this->telef) == null ){ 
         $this->erro_sql = " Campo Telefone nao Informado.";
         $this->erro_campo = "telef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->senha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["senha"])){ 
       $sql  .= $virgula." senha = '$this->senha' ";
       $virgula = ",";
       if(trim($this->senha) == null ){ 
         $this->erro_sql = " Campo senha nao Informado.";
         $this->erro_campo = "senha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cgcter!=null){
       $sql .= " cgcter = '$this->cgcter'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cgcter));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcter"]))
           $resac = db_query("insert into db_acount values($acount,359,2275,'".AddSlashes(pg_result($resaco,$conresaco,'cgcter'))."','$this->cgcter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomemun"]))
           $resac = db_query("insert into db_acount values($acount,359,2276,'".AddSlashes(pg_result($resaco,$conresaco,'nomemun'))."','$this->nomemun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["endermun"]))
           $resac = db_query("insert into db_acount values($acount,359,2277,'".AddSlashes(pg_result($resaco,$conresaco,'endermun'))."','$this->endermun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["email"]))
           $resac = db_query("insert into db_acount values($acount,359,574,'".AddSlashes(pg_result($resaco,$conresaco,'email'))."','$this->email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["telef"]))
           $resac = db_query("insert into db_acount values($acount,359,457,'".AddSlashes(pg_result($resaco,$conresaco,'telef'))."','$this->telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["senha"]))
           $resac = db_query("insert into db_acount values($acount,359,572,'".AddSlashes(pg_result($resaco,$conresaco,'senha'))."','$this->senha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Municipio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cgcter;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Municipio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cgcter=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cgcter));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$cgcter','E')");
         $resac = db_query("insert into db_acount values($acount,359,2275,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,359,2276,'','".AddSlashes(pg_result($resaco,$iresaco,'nomemun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,359,2277,'','".AddSlashes(pg_result($resaco,$iresaco,'endermun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,359,574,'','".AddSlashes(pg_result($resaco,$iresaco,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,359,457,'','".AddSlashes(pg_result($resaco,$iresaco,'telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,359,572,'','".AddSlashes(pg_result($resaco,$iresaco,'senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from municipio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cgcter != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcter = '$cgcter' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Municipio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cgcter;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Municipio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cgcter;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cgcter;
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
        $this->erro_sql   = "Record Vazio na Tabela:municipio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>