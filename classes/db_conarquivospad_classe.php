<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conarquivospad
class cl_conarquivospad { 
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
   var $c54_codarq = 0; 
   var $c54_nomearq = null; 
   var $c54_arquivo = null; 
   var $c54_codtrib = 0; 
   var $c54_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c54_codarq = int4 = Código do Arquivo 
                 c54_nomearq = varchar(20) = Nome do Arquivo 
                 c54_arquivo = text = Arquivo 
                 c54_codtrib = int4 = codigo tribunal 
                 c54_anousu = int4 = exercício 
                 ";
   //funcao construtor da classe 
   function cl_conarquivospad() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conarquivospad"); 
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
       $this->c54_codarq = ($this->c54_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["c54_codarq"]:$this->c54_codarq);
       $this->c54_nomearq = ($this->c54_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["c54_nomearq"]:$this->c54_nomearq);
       $this->c54_arquivo = ($this->c54_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["c54_arquivo"]:$this->c54_arquivo);
       $this->c54_codtrib = ($this->c54_codtrib == ""?@$GLOBALS["HTTP_POST_VARS"]["c54_codtrib"]:$this->c54_codtrib);
       $this->c54_anousu = ($this->c54_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c54_anousu"]:$this->c54_anousu);
     }else{
       $this->c54_codarq = ($this->c54_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["c54_codarq"]:$this->c54_codarq);
     }
   }
   // funcao para inclusao
   function incluir ($c54_codarq){ 
      $this->atualizacampos();
     if($this->c54_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "c54_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c54_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "c54_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c54_codtrib == null ){ 
       $this->erro_sql = " Campo codigo tribunal nao Informado.";
       $this->erro_campo = "c54_codtrib";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c54_anousu == null ){ 
       $this->erro_sql = " Campo exercício nao Informado.";
       $this->erro_campo = "c54_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c54_codarq == "" || $c54_codarq == null ){
       $result = db_query("select nextval('conarquivospad_c54_codarq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conarquivospad_c54_codarq_seq do campo: c54_codarq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c54_codarq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conarquivospad_c54_codarq_seq");
       if(($result != false) && (pg_result($result,0,0) < $c54_codarq)){
         $this->erro_sql = " Campo c54_codarq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c54_codarq = $c54_codarq; 
       }
     }
     if(($this->c54_codarq == null) || ($this->c54_codarq == "") ){ 
       $this->erro_sql = " Campo c54_codarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conarquivospad(
                                       c54_codarq 
                                      ,c54_nomearq 
                                      ,c54_arquivo 
                                      ,c54_codtrib 
                                      ,c54_anousu 
                       )
                values (
                                $this->c54_codarq 
                               ,'$this->c54_nomearq' 
                               ,'$this->c54_arquivo' 
                               ,$this->c54_codtrib 
                               ,$this->c54_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivos do PAD ($this->c54_codarq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivos do PAD já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivos do PAD ($this->c54_codarq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c54_codarq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c54_codarq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6821,'$this->c54_codarq','I')");
       $resac = db_query("insert into db_acount values($acount,1118,6821,'','".AddSlashes(pg_result($resaco,0,'c54_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1118,6822,'','".AddSlashes(pg_result($resaco,0,'c54_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1118,6823,'','".AddSlashes(pg_result($resaco,0,'c54_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1118,7828,'','".AddSlashes(pg_result($resaco,0,'c54_codtrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1118,7829,'','".AddSlashes(pg_result($resaco,0,'c54_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c54_codarq=null) { 
      $this->atualizacampos();
     $sql = " update conarquivospad set ";
     $virgula = "";
     if(trim($this->c54_codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c54_codarq"])){ 
       $sql  .= $virgula." c54_codarq = $this->c54_codarq ";
       $virgula = ",";
       if(trim($this->c54_codarq) == null ){ 
         $this->erro_sql = " Campo Código do Arquivo nao Informado.";
         $this->erro_campo = "c54_codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c54_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c54_nomearq"])){ 
       $sql  .= $virgula." c54_nomearq = '$this->c54_nomearq' ";
       $virgula = ",";
       if(trim($this->c54_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "c54_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c54_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c54_arquivo"])){ 
       $sql  .= $virgula." c54_arquivo = '$this->c54_arquivo' ";
       $virgula = ",";
       if(trim($this->c54_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "c54_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c54_codtrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c54_codtrib"])){ 
       $sql  .= $virgula." c54_codtrib = $this->c54_codtrib ";
       $virgula = ",";
       if(trim($this->c54_codtrib) == null ){ 
         $this->erro_sql = " Campo codigo tribunal nao Informado.";
         $this->erro_campo = "c54_codtrib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c54_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c54_anousu"])){ 
       $sql  .= $virgula." c54_anousu = $this->c54_anousu ";
       $virgula = ",";
       if(trim($this->c54_anousu) == null ){ 
         $this->erro_sql = " Campo exercício nao Informado.";
         $this->erro_campo = "c54_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c54_codarq!=null){
       $sql .= " c54_codarq = $this->c54_codarq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c54_codarq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6821,'$this->c54_codarq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c54_codarq"]) || $this->c54_codarq != "")
           $resac = db_query("insert into db_acount values($acount,1118,6821,'".AddSlashes(pg_result($resaco,$conresaco,'c54_codarq'))."','$this->c54_codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c54_nomearq"]) || $this->c54_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,1118,6822,'".AddSlashes(pg_result($resaco,$conresaco,'c54_nomearq'))."','$this->c54_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c54_arquivo"]) || $this->c54_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,1118,6823,'".AddSlashes(pg_result($resaco,$conresaco,'c54_arquivo'))."','$this->c54_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c54_codtrib"]) || $this->c54_codtrib != "")
           $resac = db_query("insert into db_acount values($acount,1118,7828,'".AddSlashes(pg_result($resaco,$conresaco,'c54_codtrib'))."','$this->c54_codtrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c54_anousu"]) || $this->c54_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1118,7829,'".AddSlashes(pg_result($resaco,$conresaco,'c54_anousu'))."','$this->c54_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos do PAD nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c54_codarq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos do PAD nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c54_codarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c54_codarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c54_codarq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c54_codarq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6821,'$c54_codarq','E')");
         $resac = db_query("insert into db_acount values($acount,1118,6821,'','".AddSlashes(pg_result($resaco,$iresaco,'c54_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1118,6822,'','".AddSlashes(pg_result($resaco,$iresaco,'c54_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1118,6823,'','".AddSlashes(pg_result($resaco,$iresaco,'c54_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1118,7828,'','".AddSlashes(pg_result($resaco,$iresaco,'c54_codtrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1118,7829,'','".AddSlashes(pg_result($resaco,$iresaco,'c54_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conarquivospad
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c54_codarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c54_codarq = $c54_codarq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos do PAD nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c54_codarq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos do PAD nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c54_codarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c54_codarq;
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
        $this->erro_sql   = "Record Vazio na Tabela:conarquivospad";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c54_codarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conarquivospad ";
     $sql2 = "";
     if($dbwhere==""){
       if($c54_codarq!=null ){
         $sql2 .= " where conarquivospad.c54_codarq = $c54_codarq "; 
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
   function sql_query_file ( $c54_codarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conarquivospad ";
     $sql2 = "";
     if($dbwhere==""){
       if($c54_codarq!=null ){
         $sql2 .= " where conarquivospad.c54_codarq = $c54_codarq "; 
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