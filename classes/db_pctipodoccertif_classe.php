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

//MODULO: compras
//CLASSE DA ENTIDADE pctipodoccertif
class cl_pctipodoccertif { 
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
   var $pc72_codigo = 0; 
   var $pc72_pctipocertif = 0; 
   var $pc72_pcdoccertif = 0; 
   var $pc72_obrigatorio = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc72_codigo = int4 = Código 
                 pc72_pctipocertif = int4 = Cod. Tipo Certificado 
                 pc72_pcdoccertif = int4 = Cod. Documento 
                 pc72_obrigatorio = bool = Obrigatório 
                 ";
   //funcao construtor da classe 
   function cl_pctipodoccertif() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pctipodoccertif"); 
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
       $this->pc72_codigo = ($this->pc72_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc72_codigo"]:$this->pc72_codigo);
       $this->pc72_pctipocertif = ($this->pc72_pctipocertif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc72_pctipocertif"]:$this->pc72_pctipocertif);
       $this->pc72_pcdoccertif = ($this->pc72_pcdoccertif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc72_pcdoccertif"]:$this->pc72_pcdoccertif);
       $this->pc72_obrigatorio = ($this->pc72_obrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc72_obrigatorio"]:$this->pc72_obrigatorio);
     }else{
       $this->pc72_codigo = ($this->pc72_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc72_codigo"]:$this->pc72_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc72_codigo){ 
      $this->atualizacampos();
     if($this->pc72_pctipocertif == null ){ 
       $this->erro_sql = " Campo Cod. Tipo Certificado nao Informado.";
       $this->erro_campo = "pc72_pctipocertif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc72_pcdoccertif == null ){ 
       $this->erro_sql = " Campo Cod. Documento nao Informado.";
       $this->erro_campo = "pc72_pcdoccertif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc72_obrigatorio == null ){ 
       $this->erro_sql = " Campo Obrigatório nao Informado.";
       $this->erro_campo = "pc72_obrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc72_codigo == "" || $pc72_codigo == null ){
       $result = db_query("select nextval('pctipodoccertif_pc72_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pctipodoccertif_pc72_codigo_seq do campo: pc72_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc72_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pctipodoccertif_pc72_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc72_codigo)){
         $this->erro_sql = " Campo pc72_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc72_codigo = $pc72_codigo; 
       }
     }
     if(($this->pc72_codigo == null) || ($this->pc72_codigo == "") ){ 
       $this->erro_sql = " Campo pc72_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pctipodoccertif(
                                       pc72_codigo 
                                      ,pc72_pctipocertif 
                                      ,pc72_pcdoccertif 
                                      ,pc72_obrigatorio 
                       )
                values (
                                $this->pc72_codigo 
                               ,$this->pc72_pctipocertif 
                               ,$this->pc72_pcdoccertif 
                               ,'$this->pc72_obrigatorio' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "documentos de um determinado grupo de certificado ($this->pc72_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "documentos de um determinado grupo de certificado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "documentos de um determinado grupo de certificado ($this->pc72_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc72_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc72_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7790,'$this->pc72_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1298,7790,'','".AddSlashes(pg_result($resaco,0,'pc72_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1298,7791,'','".AddSlashes(pg_result($resaco,0,'pc72_pctipocertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1298,7793,'','".AddSlashes(pg_result($resaco,0,'pc72_pcdoccertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1298,7794,'','".AddSlashes(pg_result($resaco,0,'pc72_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc72_codigo=null) { 
      $this->atualizacampos();
     $sql = " update pctipodoccertif set ";
     $virgula = "";
     if(trim($this->pc72_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc72_codigo"])){ 
       $sql  .= $virgula." pc72_codigo = $this->pc72_codigo ";
       $virgula = ",";
       if(trim($this->pc72_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc72_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc72_pctipocertif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc72_pctipocertif"])){ 
       $sql  .= $virgula." pc72_pctipocertif = $this->pc72_pctipocertif ";
       $virgula = ",";
       if(trim($this->pc72_pctipocertif) == null ){ 
         $this->erro_sql = " Campo Cod. Tipo Certificado nao Informado.";
         $this->erro_campo = "pc72_pctipocertif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc72_pcdoccertif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc72_pcdoccertif"])){ 
       $sql  .= $virgula." pc72_pcdoccertif = $this->pc72_pcdoccertif ";
       $virgula = ",";
       if(trim($this->pc72_pcdoccertif) == null ){ 
         $this->erro_sql = " Campo Cod. Documento nao Informado.";
         $this->erro_campo = "pc72_pcdoccertif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc72_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc72_obrigatorio"])){ 
       $sql  .= $virgula." pc72_obrigatorio = '$this->pc72_obrigatorio' ";
       $virgula = ",";
       if(trim($this->pc72_obrigatorio) == null ){ 
         $this->erro_sql = " Campo Obrigatório nao Informado.";
         $this->erro_campo = "pc72_obrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc72_codigo!=null){
       $sql .= " pc72_codigo = $this->pc72_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc72_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7790,'$this->pc72_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc72_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1298,7790,'".AddSlashes(pg_result($resaco,$conresaco,'pc72_codigo'))."','$this->pc72_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc72_pctipocertif"]))
           $resac = db_query("insert into db_acount values($acount,1298,7791,'".AddSlashes(pg_result($resaco,$conresaco,'pc72_pctipocertif'))."','$this->pc72_pctipocertif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc72_pcdoccertif"]))
           $resac = db_query("insert into db_acount values($acount,1298,7793,'".AddSlashes(pg_result($resaco,$conresaco,'pc72_pcdoccertif'))."','$this->pc72_pcdoccertif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc72_obrigatorio"]))
           $resac = db_query("insert into db_acount values($acount,1298,7794,'".AddSlashes(pg_result($resaco,$conresaco,'pc72_obrigatorio'))."','$this->pc72_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "documentos de um determinado grupo de certificado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc72_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "documentos de um determinado grupo de certificado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc72_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc72_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7790,'$pc72_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1298,7790,'','".AddSlashes(pg_result($resaco,$iresaco,'pc72_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1298,7791,'','".AddSlashes(pg_result($resaco,$iresaco,'pc72_pctipocertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1298,7793,'','".AddSlashes(pg_result($resaco,$iresaco,'pc72_pcdoccertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1298,7794,'','".AddSlashes(pg_result($resaco,$iresaco,'pc72_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pctipodoccertif
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc72_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc72_codigo = $pc72_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "documentos de um determinado grupo de certificado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc72_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "documentos de um determinado grupo de certificado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc72_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pctipodoccertif";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pctipodoccertif ";
     $sql .= "      inner join pctipocertif  on  pctipocertif.pc70_codigo = pctipodoccertif.pc72_pctipocertif";
     $sql .= "      inner join pcdoccertif  on  pcdoccertif.pc71_codigo = pctipodoccertif.pc72_pcdoccertif";
     $sql2 = "";
     if($dbwhere==""){
       if($pc72_codigo!=null ){
         $sql2 .= " where pctipodoccertif.pc72_codigo = $pc72_codigo "; 
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
   function sql_query_file ( $pc72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pctipodoccertif ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc72_codigo!=null ){
         $sql2 .= " where pctipodoccertif.pc72_codigo = $pc72_codigo "; 
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