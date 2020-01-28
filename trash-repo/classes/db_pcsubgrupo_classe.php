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
//CLASSE DA ENTIDADE pcsubgrupo
class cl_pcsubgrupo { 
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
   var $pc04_codsubgrupo = 0; 
   var $pc04_descrsubgrupo = null; 
   var $pc04_codgrupo = 0; 
   var $pc04_codtipo = 0; 
   var $pc04_ativo = 'f'; 
   var $pc04_tipoutil = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc04_codsubgrupo = int4 = C�digo do Subgrupo 
                 pc04_descrsubgrupo = varchar(40) = Descri��o do Sub-Grupo 
                 pc04_codgrupo = int4 = C�digo do Grupo 
                 pc04_codtipo = int4 = C�digo do Tipo 
                 pc04_ativo = bool = Ativo 
                 pc04_tipoutil = int4 = Utilizado 
                 ";
   //funcao construtor da classe 
   function cl_pcsubgrupo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcsubgrupo"); 
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
       $this->pc04_codsubgrupo = ($this->pc04_codsubgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc04_codsubgrupo"]:$this->pc04_codsubgrupo);
       $this->pc04_descrsubgrupo = ($this->pc04_descrsubgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc04_descrsubgrupo"]:$this->pc04_descrsubgrupo);
       $this->pc04_codgrupo = ($this->pc04_codgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc04_codgrupo"]:$this->pc04_codgrupo);
       $this->pc04_codtipo = ($this->pc04_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc04_codtipo"]:$this->pc04_codtipo);
       $this->pc04_ativo = ($this->pc04_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc04_ativo"]:$this->pc04_ativo);
       $this->pc04_tipoutil = ($this->pc04_tipoutil == ""?@$GLOBALS["HTTP_POST_VARS"]["pc04_tipoutil"]:$this->pc04_tipoutil);
     }else{
       $this->pc04_codsubgrupo = ($this->pc04_codsubgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc04_codsubgrupo"]:$this->pc04_codsubgrupo);
     }
   }
   // funcao para inclusao
   function incluir ($pc04_codsubgrupo){ 
      $this->atualizacampos();
     if($this->pc04_descrsubgrupo == null ){ 
       $this->erro_sql = " Campo Descri��o do Sub-Grupo nao Informado.";
       $this->erro_campo = "pc04_descrsubgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc04_codgrupo == null ){ 
       $this->erro_sql = " Campo C�digo do Grupo nao Informado.";
       $this->erro_campo = "pc04_codgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc04_codtipo == null ){ 
       $this->erro_sql = " Campo C�digo do Tipo nao Informado.";
       $this->erro_campo = "pc04_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc04_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "pc04_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc04_tipoutil == null ){ 
       $this->erro_sql = " Campo Utilizado nao Informado.";
       $this->erro_campo = "pc04_tipoutil";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc04_codsubgrupo == "" || $pc04_codsubgrupo == null ){
       $result = db_query("select nextval('pcsubgrupo_pc04_codsubgrupo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcsubgrupo_pc04_codsubgrupo_seq do campo: pc04_codsubgrupo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc04_codsubgrupo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcsubgrupo_pc04_codsubgrupo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc04_codsubgrupo)){
         $this->erro_sql = " Campo pc04_codsubgrupo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc04_codsubgrupo = $pc04_codsubgrupo; 
       }
     }
     if(($this->pc04_codsubgrupo == null) || ($this->pc04_codsubgrupo == "") ){ 
       $this->erro_sql = " Campo pc04_codsubgrupo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcsubgrupo(
                                       pc04_codsubgrupo 
                                      ,pc04_descrsubgrupo 
                                      ,pc04_codgrupo 
                                      ,pc04_codtipo 
                                      ,pc04_ativo 
                                      ,pc04_tipoutil 
                       )
                values (
                                $this->pc04_codsubgrupo 
                               ,'$this->pc04_descrsubgrupo' 
                               ,$this->pc04_codgrupo 
                               ,$this->pc04_codtipo 
                               ,'$this->pc04_ativo' 
                               ,$this->pc04_tipoutil 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Sub-Grupo ($this->pc04_codsubgrupo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Sub-Grupo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Sub-Grupo ($this->pc04_codsubgrupo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc04_codsubgrupo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc04_codsubgrupo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5502,'$this->pc04_codsubgrupo','I')");
       $resac = db_query("insert into db_acount values($acount,864,5502,'','".AddSlashes(pg_result($resaco,0,'pc04_codsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,864,5503,'','".AddSlashes(pg_result($resaco,0,'pc04_descrsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,864,5504,'','".AddSlashes(pg_result($resaco,0,'pc04_codgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,864,5505,'','".AddSlashes(pg_result($resaco,0,'pc04_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,864,7815,'','".AddSlashes(pg_result($resaco,0,'pc04_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,864,8710,'','".AddSlashes(pg_result($resaco,0,'pc04_tipoutil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc04_codsubgrupo=null) { 
      $this->atualizacampos();
     $sql = " update pcsubgrupo set ";
     $virgula = "";
     if(trim($this->pc04_codsubgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc04_codsubgrupo"])){ 
       $sql  .= $virgula." pc04_codsubgrupo = $this->pc04_codsubgrupo ";
       $virgula = ",";
       if(trim($this->pc04_codsubgrupo) == null ){ 
         $this->erro_sql = " Campo C�digo do Subgrupo nao Informado.";
         $this->erro_campo = "pc04_codsubgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc04_descrsubgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc04_descrsubgrupo"])){ 
       $sql  .= $virgula." pc04_descrsubgrupo = '$this->pc04_descrsubgrupo' ";
       $virgula = ",";
       if(trim($this->pc04_descrsubgrupo) == null ){ 
         $this->erro_sql = " Campo Descri��o do Sub-Grupo nao Informado.";
         $this->erro_campo = "pc04_descrsubgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc04_codgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc04_codgrupo"])){ 
       $sql  .= $virgula." pc04_codgrupo = $this->pc04_codgrupo ";
       $virgula = ",";
       if(trim($this->pc04_codgrupo) == null ){ 
         $this->erro_sql = " Campo C�digo do Grupo nao Informado.";
         $this->erro_campo = "pc04_codgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc04_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc04_codtipo"])){ 
       $sql  .= $virgula." pc04_codtipo = $this->pc04_codtipo ";
       $virgula = ",";
       if(trim($this->pc04_codtipo) == null ){ 
         $this->erro_sql = " Campo C�digo do Tipo nao Informado.";
         $this->erro_campo = "pc04_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc04_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc04_ativo"])){ 
       $sql  .= $virgula." pc04_ativo = '$this->pc04_ativo' ";
       $virgula = ",";
       if(trim($this->pc04_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "pc04_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc04_tipoutil)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc04_tipoutil"])){ 
       $sql  .= $virgula." pc04_tipoutil = $this->pc04_tipoutil ";
       $virgula = ",";
       if(trim($this->pc04_tipoutil) == null ){ 
         $this->erro_sql = " Campo Utilizado nao Informado.";
         $this->erro_campo = "pc04_tipoutil";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc04_codsubgrupo!=null){
       $sql .= " pc04_codsubgrupo = $this->pc04_codsubgrupo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc04_codsubgrupo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5502,'$this->pc04_codsubgrupo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc04_codsubgrupo"]))
           $resac = db_query("insert into db_acount values($acount,864,5502,'".AddSlashes(pg_result($resaco,$conresaco,'pc04_codsubgrupo'))."','$this->pc04_codsubgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc04_descrsubgrupo"]))
           $resac = db_query("insert into db_acount values($acount,864,5503,'".AddSlashes(pg_result($resaco,$conresaco,'pc04_descrsubgrupo'))."','$this->pc04_descrsubgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc04_codgrupo"]))
           $resac = db_query("insert into db_acount values($acount,864,5504,'".AddSlashes(pg_result($resaco,$conresaco,'pc04_codgrupo'))."','$this->pc04_codgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc04_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,864,5505,'".AddSlashes(pg_result($resaco,$conresaco,'pc04_codtipo'))."','$this->pc04_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc04_ativo"]))
           $resac = db_query("insert into db_acount values($acount,864,7815,'".AddSlashes(pg_result($resaco,$conresaco,'pc04_ativo'))."','$this->pc04_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc04_tipoutil"]))
           $resac = db_query("insert into db_acount values($acount,864,8710,'".AddSlashes(pg_result($resaco,$conresaco,'pc04_tipoutil'))."','$this->pc04_tipoutil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sub-Grupo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc04_codsubgrupo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Sub-Grupo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc04_codsubgrupo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc04_codsubgrupo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc04_codsubgrupo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc04_codsubgrupo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5502,'$pc04_codsubgrupo','E')");
         $resac = db_query("insert into db_acount values($acount,864,5502,'','".AddSlashes(pg_result($resaco,$iresaco,'pc04_codsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,864,5503,'','".AddSlashes(pg_result($resaco,$iresaco,'pc04_descrsubgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,864,5504,'','".AddSlashes(pg_result($resaco,$iresaco,'pc04_codgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,864,5505,'','".AddSlashes(pg_result($resaco,$iresaco,'pc04_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,864,7815,'','".AddSlashes(pg_result($resaco,$iresaco,'pc04_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,864,8710,'','".AddSlashes(pg_result($resaco,$iresaco,'pc04_tipoutil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcsubgrupo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc04_codsubgrupo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc04_codsubgrupo = $pc04_codsubgrupo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sub-Grupo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc04_codsubgrupo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Sub-Grupo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc04_codsubgrupo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc04_codsubgrupo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcsubgrupo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc04_codsubgrupo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcsubgrupo ";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc04_codsubgrupo!=null ){
         $sql2 .= " where pcsubgrupo.pc04_codsubgrupo = $pc04_codsubgrupo "; 
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
   function sql_query_file ( $pc04_codsubgrupo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcsubgrupo ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc04_codsubgrupo!=null ){
         $sql2 .= " where pcsubgrupo.pc04_codsubgrupo = $pc04_codsubgrupo "; 
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
   function sql_query_orcelement ( $pc04_codsubgrupo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcsubgrupo ";
     $sql .= "      inner join pcgrupo  on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      inner join pctipoelemento on pctipoelemento.pc06_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      inner join orcelemento on orcelemento.o56_codele = pctipoelemento.pc06_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";   
     if($dbwhere==""){
       if($pc04_codsubgrupo!=null ){
         $sql2 .= " where pcsubgrupo.pc04_codsubgrupo = $pc04_codsubgrupo ";
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