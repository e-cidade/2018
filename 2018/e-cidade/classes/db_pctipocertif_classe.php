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
//CLASSE DA ENTIDADE pctipocertif
class cl_pctipocertif { 
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
   var $pc70_codigo = 0; 
   var $pc70_descr = null; 
   var $pc70_subgrupo = 'f'; 
   var $pc70_obs = null; 
   var $pc70_parag2 = null; 
   var $pc70_tipodoc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc70_codigo = int4 = Cod. Tipo Certificado 
                 pc70_descr = varchar(40) = Descrição Certificado 
                 pc70_subgrupo = bool = Imprimir Subgrupos 
                 pc70_obs = text = Parágrafo do Certificado 
                 pc70_parag2 = text = Paragrafo 2 do certificado 
                 pc70_tipodoc = int8 = Código do Tipo de Documento 
                 ";
   //funcao construtor da classe 
   function cl_pctipocertif() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pctipocertif"); 
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
       $this->pc70_codigo = ($this->pc70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc70_codigo"]:$this->pc70_codigo);
       $this->pc70_descr = ($this->pc70_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["pc70_descr"]:$this->pc70_descr);
       $this->pc70_subgrupo = ($this->pc70_subgrupo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc70_subgrupo"]:$this->pc70_subgrupo);
       $this->pc70_obs = ($this->pc70_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc70_obs"]:$this->pc70_obs);
       $this->pc70_parag2 = ($this->pc70_parag2 == ""?@$GLOBALS["HTTP_POST_VARS"]["pc70_parag2"]:$this->pc70_parag2);
       $this->pc70_tipodoc = ($this->pc70_tipodoc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc70_tipodoc"]:$this->pc70_tipodoc);
     }else{
       $this->pc70_codigo = ($this->pc70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc70_codigo"]:$this->pc70_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc70_codigo){ 
      $this->atualizacampos();
     if($this->pc70_descr == null ){ 
       $this->erro_sql = " Campo Descrição Certificado nao Informado.";
       $this->erro_campo = "pc70_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc70_subgrupo == null ){ 
       $this->erro_sql = " Campo Imprimir Subgrupos nao Informado.";
       $this->erro_campo = "pc70_subgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc70_tipodoc == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Documento nao Informado.";
       $this->erro_campo = "pc70_tipodoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc70_codigo == "" || $pc70_codigo == null ){
       $result = db_query("select nextval('pctipocertif_pc70_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pctipocertif_pc70_codigo_seq do campo: pc70_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc70_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pctipocertif_pc70_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc70_codigo)){
         $this->erro_sql = " Campo pc70_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc70_codigo = $pc70_codigo; 
       }
     }
     if(($this->pc70_codigo == null) || ($this->pc70_codigo == "") ){ 
       $this->erro_sql = " Campo pc70_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pctipocertif(
                                       pc70_codigo 
                                      ,pc70_descr 
                                      ,pc70_subgrupo 
                                      ,pc70_obs 
                                      ,pc70_parag2 
                                      ,pc70_tipodoc 
                       )
                values (
                                $this->pc70_codigo 
                               ,'$this->pc70_descr' 
                               ,'$this->pc70_subgrupo' 
                               ,'$this->pc70_obs' 
                               ,'$this->pc70_parag2' 
                               ,$this->pc70_tipodoc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pctipocertif - tipos de certificado ($this->pc70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pctipocertif - tipos de certificado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pctipocertif - tipos de certificado ($this->pc70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc70_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc70_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7783,'$this->pc70_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1296,7783,'','".AddSlashes(pg_result($resaco,0,'pc70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1296,7784,'','".AddSlashes(pg_result($resaco,0,'pc70_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1296,7785,'','".AddSlashes(pg_result($resaco,0,'pc70_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1296,7786,'','".AddSlashes(pg_result($resaco,0,'pc70_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1296,9148,'','".AddSlashes(pg_result($resaco,0,'pc70_parag2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1296,17721,'','".AddSlashes(pg_result($resaco,0,'pc70_tipodoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc70_codigo=null) { 
      $this->atualizacampos();
     $sql = " update pctipocertif set ";
     $virgula = "";
     if(trim($this->pc70_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc70_codigo"])){ 
       $sql  .= $virgula." pc70_codigo = $this->pc70_codigo ";
       $virgula = ",";
       if(trim($this->pc70_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Tipo Certificado nao Informado.";
         $this->erro_campo = "pc70_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc70_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc70_descr"])){ 
       $sql  .= $virgula." pc70_descr = '$this->pc70_descr' ";
       $virgula = ",";
       if(trim($this->pc70_descr) == null ){ 
         $this->erro_sql = " Campo Descrição Certificado nao Informado.";
         $this->erro_campo = "pc70_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc70_subgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc70_subgrupo"])){ 
       $sql  .= $virgula." pc70_subgrupo = '$this->pc70_subgrupo' ";
       $virgula = ",";
       if(trim($this->pc70_subgrupo) == null ){ 
         $this->erro_sql = " Campo Imprimir Subgrupos nao Informado.";
         $this->erro_campo = "pc70_subgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc70_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc70_obs"])){ 
       $sql  .= $virgula." pc70_obs = '$this->pc70_obs' ";
       $virgula = ",";
     }
     if(trim($this->pc70_parag2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc70_parag2"])){ 
       $sql  .= $virgula." pc70_parag2 = '$this->pc70_parag2' ";
       $virgula = ",";
     }
     if(trim($this->pc70_tipodoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc70_tipodoc"])){ 
       $sql  .= $virgula." pc70_tipodoc = $this->pc70_tipodoc ";
       $virgula = ",";
       if(trim($this->pc70_tipodoc) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Documento nao Informado.";
         $this->erro_campo = "pc70_tipodoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc70_codigo!=null){
       $sql .= " pc70_codigo = $this->pc70_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc70_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7783,'$this->pc70_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc70_codigo"]) || $this->pc70_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1296,7783,'".AddSlashes(pg_result($resaco,$conresaco,'pc70_codigo'))."','$this->pc70_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc70_descr"]) || $this->pc70_descr != "")
           $resac = db_query("insert into db_acount values($acount,1296,7784,'".AddSlashes(pg_result($resaco,$conresaco,'pc70_descr'))."','$this->pc70_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc70_subgrupo"]) || $this->pc70_subgrupo != "")
           $resac = db_query("insert into db_acount values($acount,1296,7785,'".AddSlashes(pg_result($resaco,$conresaco,'pc70_subgrupo'))."','$this->pc70_subgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc70_obs"]) || $this->pc70_obs != "")
           $resac = db_query("insert into db_acount values($acount,1296,7786,'".AddSlashes(pg_result($resaco,$conresaco,'pc70_obs'))."','$this->pc70_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc70_parag2"]) || $this->pc70_parag2 != "")
           $resac = db_query("insert into db_acount values($acount,1296,9148,'".AddSlashes(pg_result($resaco,$conresaco,'pc70_parag2'))."','$this->pc70_parag2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc70_tipodoc"]) || $this->pc70_tipodoc != "")
           $resac = db_query("insert into db_acount values($acount,1296,17721,'".AddSlashes(pg_result($resaco,$conresaco,'pc70_tipodoc'))."','$this->pc70_tipodoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pctipocertif - tipos de certificado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pctipocertif - tipos de certificado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc70_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc70_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7783,'$pc70_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1296,7783,'','".AddSlashes(pg_result($resaco,$iresaco,'pc70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1296,7784,'','".AddSlashes(pg_result($resaco,$iresaco,'pc70_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1296,7785,'','".AddSlashes(pg_result($resaco,$iresaco,'pc70_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1296,7786,'','".AddSlashes(pg_result($resaco,$iresaco,'pc70_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1296,9148,'','".AddSlashes(pg_result($resaco,$iresaco,'pc70_parag2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1296,17721,'','".AddSlashes(pg_result($resaco,$iresaco,'pc70_tipodoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pctipocertif
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc70_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc70_codigo = $pc70_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pctipocertif - tipos de certificado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pctipocertif - tipos de certificado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc70_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pctipocertif";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pctipocertif ";
     $sql .= "      inner join db_tipodoc  on  db_tipodoc.db08_codigo = pctipocertif.pc70_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc70_codigo!=null ){
         $sql2 .= " where pctipocertif.pc70_codigo = $pc70_codigo "; 
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
   function sql_query_file ( $pc70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pctipocertif ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc70_codigo!=null ){
         $sql2 .= " where pctipocertif.pc70_codigo = $pc70_codigo "; 
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
   function sql_query_departamentos ( $pc70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
  	$sql = "select ";
  	
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= " distinct pctipocertif.* "; //$campos;
     }
     
     $sql .= " from pctipocertif ";
     $sql2 = "";
     
     $sql .= " left join pctipocertifdepartamento ";
     $sql .= " ON pc70_codigo = pc34_pctipocertif ";
          
     if($dbwhere==""){
       if($pc70_codigo!=null ){
         $sql2 .= " where pctipocertif.pc70_codigo = $pc70_codigo "; 
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