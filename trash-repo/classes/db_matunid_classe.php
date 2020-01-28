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

//MODULO: material
//CLASSE DA ENTIDADE matunid
class cl_matunid { 
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
   var $m61_codmatunid = 0; 
   var $m61_descr = null; 
   var $m61_usaquant = 'f'; 
   var $m61_abrev = null; 
   var $m61_usadec = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m61_codmatunid = int8 = C�digo da unidade 
                 m61_descr = varchar(40) = Descri��o da unidade 
                 m61_usaquant = bool = Se usa quantidade da unidade 
                 m61_abrev = varchar(6) = Abreviatura da descri��o 
                 m61_usadec = bool = Aceita casas decimais 
                 ";
   //funcao construtor da classe 
   function cl_matunid() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matunid"); 
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
       $this->m61_codmatunid = ($this->m61_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"]:$this->m61_codmatunid);
       $this->m61_descr = ($this->m61_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_descr"]:$this->m61_descr);
       $this->m61_usaquant = ($this->m61_usaquant == "f"?@$GLOBALS["HTTP_POST_VARS"]["m61_usaquant"]:$this->m61_usaquant);
       $this->m61_abrev = ($this->m61_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_abrev"]:$this->m61_abrev);
       $this->m61_usadec = ($this->m61_usadec == "f"?@$GLOBALS["HTTP_POST_VARS"]["m61_usadec"]:$this->m61_usadec);
     }else{
       $this->m61_codmatunid = ($this->m61_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"]:$this->m61_codmatunid);
     }
   }
   // funcao para inclusao
   function incluir ($m61_codmatunid){ 
      $this->atualizacampos();
     if($this->m61_descr == null ){ 
       $this->erro_sql = " Campo Descri��o da unidade nao Informado.";
       $this->erro_campo = "m61_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m61_usaquant == null ){ 
       $this->erro_sql = " Campo Se usa quantidade da unidade nao Informado.";
       $this->erro_campo = "m61_usaquant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m61_abrev == null ){ 
       $this->erro_sql = " Campo Abreviatura da descri��o nao Informado.";
       $this->erro_campo = "m61_abrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m61_usadec == null ){ 
       $this->erro_sql = " Campo Aceita casas decimais nao Informado.";
       $this->erro_campo = "m61_usadec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m61_codmatunid == "" || $m61_codmatunid == null ){
       $result = db_query("select nextval('matunid_m61_codmatunid_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matunid_m61_codmatunid_seq do campo: m61_codmatunid"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m61_codmatunid = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matunid_m61_codmatunid_seq");
       if(($result != false) && (pg_result($result,0,0) < $m61_codmatunid)){
         $this->erro_sql = " Campo m61_codmatunid maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m61_codmatunid = $m61_codmatunid; 
       }
     }
     if(($this->m61_codmatunid == null) || ($this->m61_codmatunid == "") ){ 
       $this->erro_sql = " Campo m61_codmatunid nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matunid(
                                       m61_codmatunid 
                                      ,m61_descr 
                                      ,m61_usaquant 
                                      ,m61_abrev 
                                      ,m61_usadec 
                       )
                values (
                                $this->m61_codmatunid 
                               ,'$this->m61_descr' 
                               ,'$this->m61_usaquant' 
                               ,'$this->m61_abrev' 
                               ,'$this->m61_usadec' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidades dos materiais ($this->m61_codmatunid) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidades dos materiais j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidades dos materiais ($this->m61_codmatunid) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m61_codmatunid));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6262,'$this->m61_codmatunid','I')");
       $resac = db_query("insert into db_acount values($acount,1017,6262,'','".AddSlashes(pg_result($resaco,0,'m61_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1017,6263,'','".AddSlashes(pg_result($resaco,0,'m61_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1017,6461,'','".AddSlashes(pg_result($resaco,0,'m61_usaquant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1017,6603,'','".AddSlashes(pg_result($resaco,0,'m61_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1017,8637,'','".AddSlashes(pg_result($resaco,0,'m61_usadec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m61_codmatunid=null) { 
      $this->atualizacampos();
     $sql = " update matunid set ";
     $virgula = "";
     if(trim($this->m61_codmatunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"])){ 
       $sql  .= $virgula." m61_codmatunid = $this->m61_codmatunid ";
       $virgula = ",";
       if(trim($this->m61_codmatunid) == null ){ 
         $this->erro_sql = " Campo C�digo da unidade nao Informado.";
         $this->erro_campo = "m61_codmatunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_descr"])){ 
       $sql  .= $virgula." m61_descr = '$this->m61_descr' ";
       $virgula = ",";
       if(trim($this->m61_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o da unidade nao Informado.";
         $this->erro_campo = "m61_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_usaquant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_usaquant"])){ 
       $sql  .= $virgula." m61_usaquant = '$this->m61_usaquant' ";
       $virgula = ",";
       if(trim($this->m61_usaquant) == null ){ 
         $this->erro_sql = " Campo Se usa quantidade da unidade nao Informado.";
         $this->erro_campo = "m61_usaquant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_abrev"])){ 
       $sql  .= $virgula." m61_abrev = '$this->m61_abrev' ";
       $virgula = ",";
       if(trim($this->m61_abrev) == null ){ 
         $this->erro_sql = " Campo Abreviatura da descri��o nao Informado.";
         $this->erro_campo = "m61_abrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_usadec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_usadec"])){ 
       $sql  .= $virgula." m61_usadec = '$this->m61_usadec' ";
       $virgula = ",";
       if(trim($this->m61_usadec) == null ){ 
         $this->erro_sql = " Campo Aceita casas decimais nao Informado.";
         $this->erro_campo = "m61_usadec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m61_codmatunid!=null){
       $sql .= " m61_codmatunid = $this->m61_codmatunid";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m61_codmatunid));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6262,'$this->m61_codmatunid','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"]))
           $resac = db_query("insert into db_acount values($acount,1017,6262,'".AddSlashes(pg_result($resaco,$conresaco,'m61_codmatunid'))."','$this->m61_codmatunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m61_descr"]))
           $resac = db_query("insert into db_acount values($acount,1017,6263,'".AddSlashes(pg_result($resaco,$conresaco,'m61_descr'))."','$this->m61_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m61_usaquant"]))
           $resac = db_query("insert into db_acount values($acount,1017,6461,'".AddSlashes(pg_result($resaco,$conresaco,'m61_usaquant'))."','$this->m61_usaquant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m61_abrev"]))
           $resac = db_query("insert into db_acount values($acount,1017,6603,'".AddSlashes(pg_result($resaco,$conresaco,'m61_abrev'))."','$this->m61_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m61_usadec"]))
           $resac = db_query("insert into db_acount values($acount,1017,8637,'".AddSlashes(pg_result($resaco,$conresaco,'m61_usadec'))."','$this->m61_usadec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades dos materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidades dos materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m61_codmatunid=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m61_codmatunid));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6262,'$m61_codmatunid','E')");
         $resac = db_query("insert into db_acount values($acount,1017,6262,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,6263,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,6461,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_usaquant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,6603,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,8637,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_usadec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matunid
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m61_codmatunid != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m61_codmatunid = $m61_codmatunid ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades dos materiais nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m61_codmatunid;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidades dos materiais nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m61_codmatunid;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m61_codmatunid;
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
        $this->erro_sql   = "Record Vazio na Tabela:matunid";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m61_codmatunid=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matunid ";
     $sql2 = "";
     if($dbwhere==""){
       if($m61_codmatunid!=null ){
         $sql2 .= " where matunid.m61_codmatunid = $m61_codmatunid "; 
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
   function sql_query_file ( $m61_codmatunid=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matunid ";
     $sql2 = "";
     if($dbwhere==""){
       if($m61_codmatunid!=null ){
         $sql2 .= " where matunid.m61_codmatunid = $m61_codmatunid "; 
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